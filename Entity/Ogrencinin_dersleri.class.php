<?php

class Ogrencinin_dersleri extends DBObject{
    const TABLE = "ogrencinin_desleri";
    public $ID, $adi, $dersleri;

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

}