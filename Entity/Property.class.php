<?php

class Property extends DBObject{
    const TABLE = "properties";
    public $ID, $adress, $bedrooms, $type, $floor, $status, $scheme, $landlord;

    public function __construct()
    {
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
        $condition_sentence = "p.type = pta.ID AND p.scheme = psa.ID AND p.status = ps.ID AND p.landlord = u.ID";
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
        if(intval($_GET["scheme"])){
            $condition_sentence .= " AND p.scheme = :scheme";
            $condition_params[":scheme"] = intval($_GET["scheme"]);
        }
        if(intval($_GET["user"])){
            $condition_sentence .= " AND p.landlord = :user";
            $condition_params[":user"] = intval($_GET["user"]);
        }
        $query = db_select(self::TABLE, "p")
        ->join("property_type_a", "pta")
        ->join("property_scheme_a", "psa")
        ->join("property_statuses", "ps")
        ->join(USERS, "u")
        ->condition($condition_sentence,
                $condition_params
        );
        $count = $query->select_with_function(["Count(*) as count"])->execute()->fetchObject()->count;
        $query->unset_fields();
        $query->select("p", ["ID", "adress"])
        ->select_with_function([" (select count(*) from maintenance_reports mr where mr.property = p.ID ) AS MR ", 
        "(select count(*) from incident_reports ir where ir.property = p.ID ) AS 'IR'"])
        ->select("p", ["bedrooms"])
        ->select("pta",["shortcode AS 'Type' "])
        ->select("p", ["floor"])
        ->select("ps", ["shortcode AS 'Status' "])
        ->select("psa", ["shortcode AS 'Scheme' "])
        ->select("u",["NAME", "SURNAME"])
        ->select_with_function(["(select count(*) from messages where messages.property = p.ID ) AS 'messages'"])
        ->orderBy("ID")
        ->limit(PAGE_SIZE_LIMIT, PAGE_SIZE_LIMIT * ($page -1));
        return [$query->execute()->fetchAll(PDO::FETCH_ASSOC), $count]; 
    }

}