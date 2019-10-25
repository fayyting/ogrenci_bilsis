<?php

class PropertyAreaFacility extends DBObject{
    const AREA_FACILITIES = "area_facilities";
    const TABLE = "property_area_facilities";
    public $ID, $property_area, $facility;

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
    
    public static function get(array $filter, string $table = self::TABLE){
        return parent::get($filter, self::TABLE);
    }

    public static function getAll(array $filter, string $table = self::TABLE) : array
    {
        return parent::getAll($filter, $table);
    }

    public function insert()
    {
        if(!$this->checkFacilityExist()){
            return FALSE;
        }
        parent::insert();
    }

    public function update()
    {
        if(!$this->checkFacilityExist()){
            return FALSE;
        }
        parent::update();
    }

    private function checkFacilityExist(){
        if(!$this->facility){
            return FALSE;
        }
        if(!is_numeric($this->facility)){
            $check = DBObject::get(["facility_name" => $this->facility], self::AREA_FACILITIES);
            if(!$check->ID){
                $check = new DBObject(self::AREA_FACILITIES);
                $check->facility_name = $this->facility;
                $check->insert();
            }
            $this->facility = $check->ID;
        }
        return TRUE;
    }

    public static function getFacilityOptions(){
        return db_select(self::AREA_FACILITIES)
        ->limit(AUTOCOMPLETE_SELECT_BOX_LIMIT)
        ->orderBy("ID")
        ->execute()
        ->fetchAll(PDO::FETCH_KEY_PAIR);
    }
}