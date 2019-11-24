<?php

class Blog extends DBObject{
    const TABLE = "bloglar";
    public $ID, $baslik, $yazar,$yayinlanma_tarihi, $icerik;

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
    public function delete(){
        $yorumlar = Yorum::getAll(["blog_id" => $this->ID]);
        foreach($yorumlar as $yorum){
            $yorum->delete();
        }
        parent::delete();
    }
}