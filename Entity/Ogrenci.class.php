<?php
class Ogrenci extends DBObject{
    const TABLE = "ogrenci_bilgileri";
    public $ID, $name, $surname,$sex, $tckn;

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
