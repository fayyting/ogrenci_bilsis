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

    public static function getAll() : array
    {
        return db_select(self::TABLE)->execute()->fetchAll(PDO::FETCH_CLASS, __CLASS__, [self::TABLE]);
    }

}