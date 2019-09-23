<?php

class Property extends DBObject{
    const TABLE = "properties";
    public $ID, $adress, $postcode, $bedrooms, $type, $floor, $status, $scheme_a, $scheme_b, $landlord,
    $is_view;

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
    
    /**
     * @Override
     */
    public static function get(array $filter, string $table = self::TABLE){
        return parent::get($filter, self::TABLE);
    }

    public static function getAll(array $filter, string $table = self::TABLE) : array
    {
        return parent::getAll($filter, $table);
    }
    
    /**
     * @Override
     */
    public function delete()
    {
       foreach($this->getAreas() as $area){
           $area->delete();
       } 
       foreach($this->getDocuments() as $document){
           $document->delete();
       }
       parent::delete();
    }

    public static function getTableDataByFilter(string $list, int $page){
        $condition_sentence = "";
        if($list == "view"){
            $condition_sentence .= " p.is_view = 1 ";
        }else{
            $condition_sentence .= " p.is_view = 0 ";
        }
        if ($list == "active"){
            $condition_sentence .= " AND ps.shortcode NOT IN ('A', 'CS')";
        }elseif($list == "new"){
            $condition_sentence .= " AND ps.shortcode = 'CS'"; //Coming Soon
        }elseif ($list == "archived") {
            $condition_sentence .= " AND ps.shortcode = 'A'"; //Archived
        }
        if(intval($_GET["type"])){
            $condition_sentence .= " AND p.type = :type";
            $condition_params[":type"] = intval($_GET["type"]);
        }
        if(intval($_GET["status"])){
            $condition_sentence .= " AND p.status = :status";
            $condition_params[":status"] = intval($_GET["status"]);
        }
        if(intval($_GET["scheme_a"])){
            $condition_sentence .= " AND p.scheme_a = :scheme";
            $condition_params[":scheme"] = intval($_GET["scheme_a"]);
        }
        if(intval($_GET["scheme_b"])){
            $condition_sentence .= " AND p.scheme_b = :scheme";
            $condition_params[":scheme"] = intval($_GET["scheme_b"]);
        }
        if(intval($_GET["user"])){
            $condition_sentence .= " AND p.landlord = :user";
            $condition_params[":user"] = intval($_GET["user"]);
        }
        $query = db_select(self::TABLE, "p")
        ->leftjoin("property_type_a", "pta", "p.type = pta.ID")
        ->leftjoin("property_scheme_a", "psa", "p.scheme_a = psa.ID")
        ->leftjoin("property_scheme_b", "psb", "p.scheme_b = psb.ID")
        ->leftjoin("property_statuses", "ps", "p.status = ps.ID")
        ->leftjoin(USERS, "u", "p.landlord = u.ID")
        ->condition($condition_sentence,
                $condition_params
        );
        $count = $query->select_with_function(["Count(*) as count"])->execute()->fetchObject()->count;
        $query->unset_fields();
        $query->select("p", ["ID", "adress", "postcode"])
        ->select_with_function([" (select count(*) from maintenance_reports mr where mr.property = p.ID ) AS MR ", 
        "(select count(*) from incident_reports ir where ir.property = p.ID ) AS 'IR'"])
        ->select("p", ["bedrooms"])
        ->select("pta",["shortcode AS 'Type' "])
        ->select("p", ["floor"])
        ->select("ps", ["shortcode AS 'Status' "])
        ->select("psa", ["shortcode AS 'Scheme A' "])
        ->select("psb", ["shortcode AS 'Scheme B' "])
        ->select("u",["NAME", "SURNAME"]);
        if( in_array($list, ["active","view"]) ){
            $query->select_with_function(["(select count(*) from messages where messages.property = p.ID ) AS 'messages'"]);
        }
        $query->select_with_function([" CONCAT('<a href=\"".BASE_URL."/properties/edit/',p.ID, '\" >"._t(115)."</a> ') AS 'edit' "])
        ->select_with_function([" CONCAT('<a href=\"#\" class=\"remove_button\" data-id=\"',p.ID, '\" >"._t(82)."</a> ') AS 'remove' "])
        ->orderBy("ID")
        ->limit(PAGE_SIZE_LIMIT, PAGE_SIZE_LIMIT * ($page -1));
        return [$query->execute()->fetchAll(PDO::FETCH_ASSOC), $count]; 
    }

    public function getAreas(){
        return PropertyArea::getAll(["property" => $this->ID]);
    }

    public function addArea(PropertyArea $area){
        $area->property = $this->ID;
        $area->insert();
    }
    
    public function updateAreas(array $area_list){
        $current_areas = $this->getAreas();
        foreach($area_list as $index => $area){
            if(isset($current_areas[$index])){
                object_map($current_areas[$index], $area);
                if($current_areas[$index]->remove){
                    unset($current_areas[$index]->remove);
                    $current_areas[$index]->delete();
                }else{
                    $area_photos = $current_areas[$index]->getPhotos();
                    if(isset($current_areas[$index]->photos)){
                        $photos_to_remove = array_keys($current_areas[$index]->photos);
                        foreach($photos_to_remove as $photo_to_remove_index){
                            $area_photos[$photo_to_remove_index]->delete();
                        }
                        unset($current_areas[$index]->photos);
                    }
                    if(isset($_FILES["areas"]["tmp_name"][$index]["photos"])){
                        $area_photos_tmp_names = $_FILES["areas"]["tmp_name"][$index]["photos"];
                        $area_photos_types = $_FILES["areas"]["type"][$index]["photos"];
                        $current_areas[$index]->updatePhotos($area_photos_tmp_names, $area_photos_types);
                    }
                    $current_areas[$index]->update();
                }
            }else{
                $new_area = new PropertyArea();
                object_map($new_area, $area);
                $new_area->property = $this->ID;
                $new_area->insert();
                if(isset($_FILES["areas"]["tmp_name"][$index]["photos"])){
                    $area_photos_tmp_names = $_FILES["areas"]["tmp_name"][$index]["photos"];
                    $area_photos_types = $_FILES["areas"]["type"][$index]["photos"];
                    $new_area->updatePhotos($area_photos_tmp_names, $area_photos_types);
                }
            }
        }
    }

    public function getDocuments(){
        $documents = PropertyDocument::getAll(["property" => $this->ID]);
        //If not exist property documents, create all documents
        if(empty($documents)){
            $document_types = PropertyDocumentType::getAll([]);
            foreach($document_types as $document_type){
                $document = new PropertyDocument();
                $document->property = $this->ID;
                $document->document_type = $document_type->ID;
                $document->required = 0;
                $document->received = 0;
                $document->insert();
            }
            $documents = PropertyDocument::getAll(["property" => $this->ID]);
        }
        return $documents;
    }

    public function updateDocuments(array $new_documents){
        $documents = $this->getDocuments();
        foreach($new_documents as $index => $new_document){
            object_map($documents[$index], $new_document);
            if($_FILES["documents"]["tmp_name"][$index]){
                $documents[$index]->updateDocument($_FILES["documents"]["tmp_name"][$index], $_FILES["documents"]["name"][$index]);
            }
            $documents[$index]->update();
        }
    }
}