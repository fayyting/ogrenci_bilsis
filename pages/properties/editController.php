<?php

class EditPropertiesController extends AdminPage{
    public $property;

    public $form_id = "property_edit_form";
    public $form_build_id;

    public $intervals;
    public $payment_types;
    public $electricity_meter_types;
    public $gas_meter_types;
    public $water_meter_types;

    public $locations;

    public $heating_types;

    public $stopcock_locations;

    private $create_if_not_exist_fields = [
        "electricity" => "electricity_provider",
        "gas" => "gas_provider",
        "water" => "water_provider"
    ];

    public $area_table_headers;
    public $area_table_data;

    public $documents_table_headers;
    public $documents_table_data;
    protected function preprocessPage()
    {
        $this->add_frontend_translation(142);
        $this->add_frontend_translation(143);
        $this->add_frontend_translation(199);
        $this->add_frontend_translation(202);
        $this->add_frontend_translation(115);
        $this->add_css_files("pages/properties/css/properties.css");
        $this->add_js_files("pages/properties/js/properties.js");

        $this->import_view("table_view");
        $this->import_view("file_input");

        $this->intervals = [
            "day" => _t(188),
            "week" => _t(189),
            "month" => _t(190),
            "quaterly" => _t(191),
            "annual" => _t(192),
            "collection" => _t(193) 
        ];
        $this->payment_types = [
            "GR" => _t(196),
            "AC" => _t(197),
            "PU" => _t(108)
        ];

        $this->electricity_meter_types = [
            "bill-meter" => _t(157),
            "pre-pay" => _t(158),
        ];
        $this->gas_meter_types = [
            "bill-meter" => _t(157),
            "pre-pay" => _t(158),
        ];
        $this->water_meter_types = [
            "bill-meter" => _t(157),
            "set-pay" => _t(159),
        ];

        $this->locations = [
            "front-garden" => _t(166),
            "hallway" => _t(167),
            "cupboard" => _t(168),
            "kitchen" => _t(169),
            "living-room" => _t(170)
        ];

        $this->heating_types = [
            "gch" => _t(176),
            "economy_7" => _t(177),
            "electric_heating" => _t(178),
            "solar_green" => _t(179),
            "oil" => _t(180),
            "wood" => _t(181),
            "coal" => _t(182),
            "communal" => _t(183),
            "other" => _t(184)
        ];

        $this->area_table_headers = [
            _t(212),
            "",
            _t(204),
            _t(205),
            "Level",
            _t(206),
            _t(207),
            _t(224)
        ];

        $this->documents_table_headers = [
            _t(208),
            _t(209),
            _t(210),
            _t(211)
        ];

        if($this->arguments[1]){
            $this->property = new Property();
            $this->property->getById($this->arguments[1]);
        }
        if(isset($_POST["add"]) || isset($_POST["add_and_publish"]) ){
            $this->addNewProperty();
        }elseif(isset($_POST["update"]) || isset($_POST["update_and_publish"])){
            $this->updateProperty();
        }

        /**
         * Area table generation
         */
        $areas =  $this->property ? $this->property->getAreas() : [];
        $this->area_table_data = [];
        $type_and_image_map = [
            "general_living_area" => "/assets/dinner.jpg",
            "bathroom" => "/assets/bathroom.png",
            "parking" => "/assets/parking.png",
            "bedroom" => "/assets/bedroom.jpg",
            "gardens" => "/assets/gardens.png"
        ];
        foreach($areas as $index=>$area){
            $area_table_row = [];

            $area_table_row[] = "<div class='area_type_selection'><img src='".BASE_URL.$type_and_image_map[$area->area_type]."' /></div>";
            $area_table_row[] = "<div class='area_type_selection'>".($area->getAreaType2Rendered())."</div>";
            $area_table_row[] = "<input type='text' name='areas[$index][area_comment]' class='form-control'>";
            $photos_html = "<div class='area_images'>";
            $photos = $area->getPhotos();
            
            foreach($photos as $photo_index => $photo){
                $photos_html .= "<div class='area_image'> 
                                    <img src='".$photo->getPhotoUrl()."' class='area_photo'/>
                                    <a href='' class='remove_photo' data-connected-name='areas[$index][photos][$photo_index]'><span class='glyphicon glyphicon-remove core-control'></span></a>
                                </div>";
            }
            $photos_html .= "</div>";
            $photos_html .= get_file_input("areas[$index][photos][0]", "", 
            [
                "label" => "<span class='glyphicon glyphicon-camera'></span>",
                "classes" => ["property_file_input"],
                "button_style" => ["col-sm-4", "col-xs-12"],
                "attributes" => [
                    "data-area-index" => $index,
                    "data-area-file-index" => "0"
                ],
                "accept" => "image/*"
            ]);
            $area_table_row[] = $photos_html;
            $area_table_row[] = "Level";
            $area_table_row[] = "<input type='number' name='areas[$index][width]' value='$area->width' class='numberpicker area_width' readonly='true' data-on-change='updateTotal'/>".
                                "<input type='number' name='areas[$index][length]' value='$area->length' class='numberpicker area_length' readonly='true' data-on-change='updateTotal'/>".
                                "<input type='checkbox' name='areas[$index][measurement_type]' id='areas_{$index}_measurementtype' value='$area->measurement_type' class='measurementtype_picker checked hidden'/>".
                                "<label for='areas_{$index}_measurementtype' class='btn btn-default'>$area->measurement_type</label>";
            $area_table_row[] = $area->width*$area->length;
            $area_table_row[] = 
            "<input type='text' name='areas[$index][fire_safety_items]' class='hidden' value='{$area->fire_safety_items}' />
            <div>
                ".$area->getFireSafetyItemsRendered()."
                <br>
                <a href='' class='fire_safety_item_edit'>"._t(115)."</a>
            </div>";
            $area_table_row[] = "<a href='' class='glyphicon glyphicon-remove remove_area'>"._t(82)."</a>".
                                "<input type='checkbox' value='0' name='areas[$index][remove]' class='hidden' />";
            $this->area_table_data[] = $area_table_row;
        }

        /**
         * Documents table generation
         */
        $this->documents_table_data = [];
        $document_types = PropertyDocumentType::getAll([]);
        $documents = $this->property ? $this->property->getDocuments() : [];
        foreach($document_types as $index => $document_type){
            $document_table_row = [];
            $document_table_row[] = $document_type->document_name;
            $document_table_row[] = "<input type='checkbox' class='yes_no_box' id='document_{$index}_required' "
                                    ."name='documents[$index][required]'"
                                    .($documents[$index]->required ? "checked" : "")
                                    ."/>";
            $document_table_row[] = ($documents[$index]->document ?  
                            "<div class='dbl_click_file'><a target='_blank' href='{$documents[$index]->getDocumentUrl()}'>{$documents[$index]->document_name}</a>"
                            : "").
                            get_file_input("documents[$index]", "", 
                            [
                                "label" => "<span class='glyphicon glyphicon glyphicon-paperclip'></span>",
                                "classes" => ["document-file-input"]
                            ]);
            $document_table_row[] = "<input type='checkbox' class='yes_no_box' id='document_{$index}_received' "
                                    ."name='documents[$index][received]'"
                                    .($documents[$index]->received ? "checked" : "")
                                    ."/>";
            $this->documents_table_data[] = $document_table_row;
        }

        $this->form_build_id = create_csrf($this->form_id, $this->arguments[1] ? $this->arguments[1] : 0);
    }

    protected function echoContent()
    {
        include __DIR__."/edit_html.php";
        echo_properties_edit_page($this);
    }

    private function addNewProperty(){
        if(!$this->check_csrf()){
            return;
        }
        if(!$_POST["property"]["adress"]){
            create_warning_message(_t(138));
            return;
        }
        $this->property = new Property();
        object_map($this->property, $_POST["property"]);
        $this->check_creatable_fields();
        if(isset($_POST["add_and_publish"])){
            $this->property->is_view = "0";
        }else{
            $this->property->is_view = "1";
        }
        $this->property->created_date = get_current_date();
        $this->property->insert();

        $areas = $_POST["areas"];
        foreach($areas as $index => $area_data){
            $area = new PropertyArea();
            object_map($area, $area_data);
            $this->property->addArea($area);
            if(isset($_FILES["areas"]["tmp_name"][$index]["photos"])){
                $area_photos_tmp_names = $_FILES["areas"]["tmp_name"][$index]["photos"];
                $area_photos_types = $_FILES["areas"]["type"][$index]["photos"];
                $area->updatePhotos($area_photos_tmp_names, $area_photos_types);
            }
        }

        create_warning_message(_t(91), "alert-success");
        core_go_to(BASE_URL."/properties/edit/".$this->property->ID);
    }

    private function updateProperty(){
        if(!$this->check_csrf()){
            return;
        }
        object_map($this->property, $_POST["property"]);
        if(isset($_POST["update_and_publish"])){
            $this->property->is_view = "0";
        }
        $this->property->updateAreas($_POST["areas"]);
        $this->property->updateDocuments($_POST["documents"]);
        $this->check_creatable_fields();
        $this->property->update();
        create_warning_message(_t(32), "alert-success");
    }

    private function check_csrf(){
        if(!isset($_POST["form_build_id"]) || !$_POST["form_build_id"] || $this->arguments[1] != get_csrf($_POST["form_build_id"], $this->form_id) ){
            create_warning_message(_t(67));
            return false;
        }
        return true;
    }

    private function check_creatable_fields(){
        foreach($this->create_if_not_exist_fields as $type => $field_name){
            if(!is_numeric($this->property->$field_name)){
                $service_provider = new DBObject("service_providers");
                $service_provider->provider_name = $this->property->$field_name;
                $service_provider->provider_type = $type;
                $service_provider->insert();
                $this->property->$field_name = $service_provider->ID;
            }
        }
    }
    
}