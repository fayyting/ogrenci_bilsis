<?php

class Property extends DBObject{
    const TABLE = "properties";
    public $ID, $adress, $postcode, $bedrooms, $type, $floor, $status, $scheme_a, $scheme_b, $landlord,
    $category;

    const PROPERTY_CATEGORY_VIEWING = 0;
    const PROPERTY_CATEGORY_ACTIVE = 1;
    const PROPERTY_CATEGORY_NEW = 2;
    const PROPERTY_CATEGORY_ARCHIVED = 3;

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
        $category = Property::PROPERTY_CATEGORY_ACTIVE;
        switch($list){
            case "view":
                $category = Property::PROPERTY_CATEGORY_VIEWING;
                break;
            case "active":
                $category = Property::PROPERTY_CATEGORY_ACTIVE;
                break;
            case "new":
                $category = Property::PROPERTY_CATEGORY_NEW;
                break;
            case "archived":
                $category = Property::PROPERTY_CATEGORY_ARCHIVED;
                break;
        }
        $condition_sentence = "p.category = :category";
        $condition_params[":category"] = $category;
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
        $query->select_with_function([" CONCAT('<a href=\"".BASE_URL."/properties/edit/',p.ID, '\" class=\"edit_button\" >"._t(115)."</a> ') AS 'edit' "])
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
                    $current_areas[$index]->updateFacilities($_POST["facilities"][$index]);
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
                    $new_area->updateFacilities($_POST["facilities"][$index]);
                }
            }
        }
    }

    public function getDocuments(){
        $documents = PropertyDocument::getAll(["property" => $this->ID]);
        //If not exist property documents, create all documents
        if(count($documents) == 0 && $this->ID){
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
            if(isset($_FILES["documents"]["tmp_name"][$index]["files"])){
                $documents_tmp_names = $_FILES["documents"]["tmp_name"][$index]["files"];
                $documents_names = $_FILES["documents"]["name"][$index]["files"];
                $documents[$index]->updateDocumentFiles($documents_tmp_names, $documents_names, $index);
            }
            $documents[$index]->update();
        }
    }

    public function getAreasTableData(){
        $areas =  $this->getAreas();
        $area_table_data = [];
        $type_orders = PropertyArea::getAvailableOptionsForAreaType();
        foreach($type_orders as &$value){
            $value = 0;
        }
        foreach($areas as $index=>$area){
            $type_orders[$area->area_type]++;
            $area_table_data[] = $area->getAreaTableRowData($index, FALSE, $type_orders[$area->area_type]);
        }
        return $area_table_data;
    }

    public static function getDocumentsTableData(Property $property = NULL){
        $documents_table_data = [];
        $document_types = PropertyDocumentType::getAll([]);
        $documents = $property ? $property->getDocuments() : NULL;
        foreach($document_types as $index => $document_type){
            $document_table_row = [];
            $document_table_row[] = $document_type->document_name.($property->ID ? "<span href='' class='document_comment glyphicon glyphicon-comment' data-document-id='".$documents[$index]->ID."'></span>" : "");
            $document_table_row[] = "<input type='checkbox' class='yes_no_box' id='document_{$index}_required' "
                                    ."name='documents[$index][required]'"
                                    .($documents[$index]->required ? "checked" : "")
                                    ."/>";
            $document_table_row[] = $documents[$index] ? $documents[$index]->getFilesRendered($index) : PropertyDocument::getDocumentFileInput($index);
            $document_table_row[] = "<input type='checkbox' class='yes_no_box' id='document_{$index}_received' "
                                    ."name='documents[$index][received]'"
                                    .($documents[$index]->received ? "checked" : "")
                                    ."/>";
            $document_table_row[] = "<input type='checkbox' class='yes_no_box' id='document_{$index}_checked' "
                                    ."name='documents[$index][checked]'"
                                    .($documents[$index]->checked ? "checked" : "")
                                    ."/>";
            $documents_table_data[] = $document_table_row;
        }
        return $documents_table_data;
    }
}