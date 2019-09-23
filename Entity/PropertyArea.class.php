<?php

class PropertyArea extends DBObject{
    const TABLE = "property_areas";
    public $ID, $property, $area_type, $area_type_2,$width, $length, $measurement_type, $area_comment;

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

    public function getPhotos(){
        return PropertyAreaPhotos::getAll(["property_area" => $this->ID]);
    }

    public function updatePhotos($photo_file_tmp_names, $photo_file_types){
        foreach($photo_file_tmp_names as $index => $photo_file_tmp_name){
            if(!$photo_file_tmp_name) return;
            $photo = new PropertyAreaPhotos();
            $photo->property_area = $this->ID;
            if($photo->importFile($photo_file_tmp_name, $photo_file_types[$index])){
                $photo->insert();
            }
        }
    }

    public function delete()
    {
        $photos = $this->getPhotos();
        foreach($photos as $photo){
            $photo->delete();
        }
        parent::delete();
    }

    public function getAreaType2Rendered(){
        switch($this->area_type){
            case "general_living_area":
                return self::getAvailableVariablesForLivingArea()[$this->area_type_2] ? : $this->area_type_2;
                break;
            case "bathroom":
                $values = explode(",",$this->area_type_2);
                $result = "";
                foreach($values as $value){
                    $url_value = self::getAvailableVariablesForBathroom()[$value];
                    if(!$url_value) continue;
                    $result .= "<img src='".BASE_URL.$url_value."' />";
                }
                return $result;
                break;
            case "parking":
                return "<img src='".BASE_URL.self::getAvailableVariablesForParking()[$this->area_type_2]."' />";
                break;
            case "bedroom":
                return $this->area_type_2;
                break;
            case "gardens":
                return "<img src='".BASE_URL.self::getAvailableVariablesForGardens()[$this->area_type_2]."' />";
                break;
        }
    }

    public function getFireSafetyItemsRendered(){
        if(!$this->fire_safety_items){
            return;
        }
        $items = explode(",",$this->fire_safety_items);
        $result = "";
        $fire_safety_item = new FireSafetyItem();
        foreach($items as $item){
            $fire_safety_item->getById(intval($item));
            $result .= "<img src='".$fire_safety_item->getImageUrl()."' class='fire_safety_item_photo'/>";
        }
        return $result;
    }

    public static function getAvailableVariablesForLivingArea(){
        return [
            "kitchen" => _t(169),
            "kitchen_dinner" => _t(213),
            "kitchen_living_area" => _t(214),
            "living_room" => _t(170),
            "dining_room" => _t(215),
            "conservatory" => _t(216),
            "hallway" => _t(167),
            "landing" => _t(217),
            "porch" => _t(218),
            "study" => _t(219),
            "utility_area" => _t(220)
        ];
    }

    public static function getAvailableVariablesForParking(){
        return [
            "garage" => "/assets/parking1.png",
            "off-street" => "/assets/parking2.jpg",
            "resident-permit" => "/assets/parking3.png",
            "free-on-street" => "/assets/parking4.png"
        ];
    }
    
    public static function getAvailableVariablesForGardens(){
        return [
            "side-garden" => "/assets/garden1.png",
            "shed-outbuilding" => "/assets/garden2.png",
            "terrace-balcony" => "/assets/garden3.png",
            "communal" => "/assets/garden4.png"
        ];
    }
    public static function getAvailableVariablesForBathroom(){
        return [
            "none" => "",
            "with_shower_bath" => "/assets/bathroom.png",
            "without_shower_bath" => "/assets/bathroom2.png",
            "with_shower" => "/assets/shower.png",
            "with_toilet" => "/assets/toilet.png"
        ];
    }
}