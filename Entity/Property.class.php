<?php

class Property extends DBObject{
    const TABLE = "properties";
    public $ID, $adress, $postcode, $bedrooms, $type, $floor, $status, $scheme_a, $scheme_b, $landlord;

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

    public static function getTableDataByFilter(string $list, int $page){
        $condition_sentence = "p.type = pta.ID AND p.scheme_a = psa.ID AND p.scheme_b = psb.ID AND p.status = ps.ID AND p.landlord = u.ID";
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
        ->join("property_type_a", "pta")
        ->join("property_scheme_a", "psa")
        ->join("property_scheme_b", "psb")
        ->join("property_statuses", "ps")
        ->join(USERS, "u")
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
        if($list == "active"){
            $query->select_with_function(["(select count(*) from messages where messages.property = p.ID ) AS 'messages'"]);
        }
        $query->select_with_function([" CONCAT('<a href=\"".BASE_URL."/properties/edit/',p.ID, '\" >"._t(115)."</a> ') AS 'edit' "])
        ->select_with_function([" CONCAT('<a href=\"#\" class=\"remove_button\" data-id=\"',p.ID, '\" >"._t(82)."</a> ') AS 'remove' "])
        ->orderBy("ID")
        ->limit(PAGE_SIZE_LIMIT, PAGE_SIZE_LIMIT * ($page -1));
        return [$query->execute()->fetchAll(PDO::FETCH_ASSOC), $count]; 
    }

}