<?php

class PropertyAreaPhotos extends DBObject{
    const TABLE = "area_photos";
    public $ID, $property_area, $photo;

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

    public function getPhotoUrl(){
        return $this->get_file_url_for_field("photo");
    }

    public function importFile($tmp_name, $type){
        if(strpos($type, "image/") !== 0){
            create_warning_message(_t(203));
            return false;
        }
        $file_url = "files/uploaded/$this->table/photo/";
        is_dir($file_url) ?  : mkdir($file_url, 0777, true);
        $extension = str_replace("image/","", $type);
        $file_name = md5($tmp_name).".".$extension;
        $file_url.= $file_name;
        move_uploaded_file($tmp_name, $file_url);
        $this->photo = $file_name;
        return true;
    }

    public function getPhotoRendered(int $area_index, int $photo_index){
        return "<div class='area_image'> 
                    <img src='".$this->getPhotoUrl()."' class='area_photo'/>
                    <a href='' class='remove_photo' data-connected-name='areas[$area_index][photos][$photo_index]'><span class='glyphicon glyphicon-remove core-control'></span></a>
                </div>";
    }
}