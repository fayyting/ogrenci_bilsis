<?php

class FireSafetyItem extends DBObject{
    const TABLE = "fire_safety_items";
    public $ID, $image;

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

    public function getImageUrl(){
        return $this->get_file_url_for_field("image");
    }
}