<?php

class PropertyArea extends DBObject{
    const TABLE = "property_areas";
    public $ID, $property, $area_type, $width, $length, $measurement_type, $area_comment;

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

    public function getFacilities(){
        return PropertyAreaFacility::getAll(["property_area" => $this->ID]);
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

    public function updateFacilities($facilities_info){
        $facilities = $facilities_info ? explode(";", $facilities_info) : [];
        $current_facilities = $this->getFacilities();
        if( count($facilities) < count($current_facilities) ){
            $facilities_to_delete = array_slice($current_facilities, count($facilities));
            $current_facilities = array_slice($current_facilities, 0, count($facilities));
            foreach($facilities_to_delete as $to_delete){
                $to_delete->delete();
            }
        }elseif(count($facilities) > count($current_facilities)){
            $facilities_to_add = array_slice($facilities, count($current_facilities));
            foreach($facilities_to_add as $to_add){
                $area_facility = new PropertyAreaFacility();
                $area_facility->property_area = $this->ID;
                $area_facility->facility = $to_add;
                $area_facility->insert();
            }
        }

        foreach($current_facilities as $index => $facility){
            $facility->facility = $facilities[$index];
            $facility->update();
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

    public function getAreaTableRowData(int $area_index, bool $is_new_area, int $type_order){
        $area_table_row = [];
        $options = $this->getAvailableOptionsForAreaType();
        $type_output = $options[$this->area_type] ? : $this->area_type;
        $area_table_row[] = "<div class='area_type_selection'> $type_order. $type_output
                            <input type='hidden' class='area_type_value' value='$this->area_type' name='areas[$area_index][area_type]'/></div>";
        $area_table_row[] = "<input type='text' name='areas[$area_index][area_comment]' class='form-control'>";        
        $area_table_row[] = $this->getPhotosRendered($area_index);
        $area_table_row[] = "<input type='number' name='areas[$area_index][level]' value='$this->level' class='form-control'/>";
        $area_table_row[] = "<input type='number' name='areas[$area_index][width]' value='$this->width' class='numberpicker area_width' readonly='true' data-on-change='updateTotal'/>".
                            "<input type='number' name='areas[$area_index][length]' value='$this->length' class='numberpicker area_length' readonly='true' data-on-change='updateTotal'/>".
                            "<input type='checkbox' name='areas[$area_index][measurement_type]' id='areas_{$area_index}_measurementtype' value='$this->measurement_type' class='measurementtype_picker checked hidden' ".($is_new_area ? "checked": "")."/>".
                            "<label for='areas_{$area_index}_measurementtype' class='btn btn-default'>$this->measurement_type</label>";
        $area_table_row[] = $this->width*$this->length;
        $area_table_row[] = "<input type='text' name='facilities[$area_index]' class='hidden' 
        value='".implode(";", array_map(function($el){
            return $el->facility;
        }, $this->getFacilities() ) )."' />
        <div>
            <a href='' class='facilities_edit' data-area='{$this->ID}'>"._t(115)."</a>
        </div>";
        $area_table_row[] = 
        "<input type='text' name='areas[$area_index][fire_safety_items]' class='hidden' value='{$this->fire_safety_items}' />
        <div>
            <a href='' class='fire_safety_item_edit' data-area='{$this->ID}'>"._t(115)."</a>
        </div>";
        $area_table_row[] = "<input type='checkbox' class='yes_no_box' id='areas_{$area_index}_checked' "
                            ."name='areas[$area_index][checked]'"
                            .($this->checked ? "checked" : "")
                            ."/>";
        $area_table_row[] = "<a href='' class='glyphicon glyphicon-remove ".($is_new_area ? "remove_new_area" : "remove_area")."'>"._t(82)."</a>".
                            "<input type='checkbox' value='0' name='areas[$area_index][remove]' class='hidden' />";
        return $area_table_row;
    }

    private function getPhotosRendered(int $area_index){
        $photos = $this->getPhotos();
        $photos_html = "<div class='area_images'>";
        foreach($photos as $photo_index => $photo){
            $photos_html .= $photo->getPhotoRendered($area_index, $photo_index);
        }
        $photos_html .= "</div>";
        $photos_html .= get_file_input("areas[$area_index][photos][0]", "", 
        [
            "label" => "<span class='glyphicon glyphicon-camera'></span>",
            "classes" => ["property_file_input"],
            "button_style" => ["col-sm-4", "col-xs-12"],
            "attributes" => [
                "data-area-index" => $area_index,
                "data-area-file-index" => "0"
            ],
            "accept" => "image/*"
        ]);
        return $photos_html;
    }

    public static function getAvailableOptionsForAreaType(){
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
            "utility_area" => _t(220),
            "bathroom" => _t(221),
            "shower" => _t(222),
            "toilet" => _t(223),
            "garden" => _t(153),
            "balcony" => _t(154),
            "terrace" => _t(155),
            "parking" => _t(156)
        ];
    }
}