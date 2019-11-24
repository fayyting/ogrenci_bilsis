<?php

class Yorum extends DBObject{
    const TABLE = "blog_yorumlari";
    public $ID, $yorum_metni, $yazar,$yayin_tarihi,$blog_id; 

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