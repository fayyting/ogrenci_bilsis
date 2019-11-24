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

    public $property_categories;

    private $create_if_not_exist_fields = [
        "electricity" => "electricity_provider",
        "gas" => "gas_provider",
        "water" => "water_provider"
    ];

    public $area_table_headers;
    public $area_table_data;

    public $documents_table_headers;
    public $documents_table_data;

    public $suitable_for_disabled_types;
    protected function preprocessPage()
    {
        if($this->arguments[1]){
            $this->property = new Property();
            $this->property->getById($this->arguments[1]);
            if(!$this->property->ID){
                Router::getInstance()->route(Router::$notFound);
            }
            $this->setTitle(_t(343).": ".$this->property->adress);
        }else{
            $this->setTitle(_t(342));
        }

        $this->add_frontend_translation(142);
        $this->add_frontend_translation(143);
        $this->add_frontend_translation(199);
        $this->add_frontend_translation(202);
        $this->add_frontend_translation(115);
        $this->add_frontend_translation(242);
        $this->add_frontend_translation(254);
        $this->add_frontend_translation(344);
        $this->add_frontend_translation(345);
        $this->add_frontend_translation(346);
        $this->add_frontend_translation(351);
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
            "PU" => _t(198)
        ];

        $this->electricity_meter_types = [
            "bill-meter" => _t(157),
            "pre-pay" => _t(158),
            "smart-meter" => _t(148)
        ];
        $this->gas_meter_types = [
            "bill-meter" => _t(157),
            "pre-pay" => _t(158),
            "smart-meter" => _t(148)
        ];
        $this->water_meter_types = [
            "bill-meter" => _t(157),
            "set-pay" => _t(159),
            "smart-meter" => _t(148)
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
            _t(204),
            _t(205),
            _t(244),
            _t(206),
            _t(207),
            _t(250),
            _t(224),
            _t(240)
        ];

        $this->documents_table_headers = [
            _t(208),
            _t(209),
            _t(210),
            _t(211),
            _t(240)
        ];

        $this->property_categories = Property::getAvailableCategoryOptions();
        
        $this->suitable_for_disabled_types = [
            "not_suitable" => _t(251),
            "suitable" => _t(152),
            "part_suitable" => _t(252)
        ];

        $this->add_js("var suitable_for_disabled_types = ".json_encode($this->suitable_for_disabled_types).";");

        if(isset($_POST["add"]) ){
            $this->addNewProperty();
        }elseif(isset($_POST["update"]) ){
            $this->updateProperty();
        }

        /**
         * Area table generation
         */
        $this->area_table_data = $this->property ? $this->property->getAreasTableData() : [];

        /**
         * Documents table generation
         */
        $this->documents_table_data = Property::getDocumentsTableData($this->property);

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
        if(!$_POST["property"]["adress"] || !$_POST["property"]["postcode"]){
            create_warning_message(_t(138));
            return;
        }
        $this->property = new Property();
        object_map($this->property, $_POST["property"]);
        $this->check_creatable_fields();
        if(!$this->checkUser()){
            return;
        }
        $this->property->created_date = get_current_date();
        $this->property->insert();

        $areas = $_POST["areas"] ? : [];
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
        $this->property->updateDocuments($_POST["documents"]);
        create_warning_message(_t(91), "alert-success");
        core_go_to(BASE_URL."/properties/edit/".$this->property->ID);
    }

    private function updateProperty(){
        if(!$this->check_csrf()){
            return;
        }
        object_map($this->property, $_POST["property"]);
        $_POST["areas"] ? $this->property->updateAreas($_POST["areas"]) : "";
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
                //Empty input
                if($this->property->$field_name == "NULL" || !$this->property->$field_name){
                    $this->property->$field_name = NULL;
                    continue;
                }
                $service_provider = new ServiceProvider();
                $service_provider->provider_name = $this->property->$field_name;
                $service_provider->provider_type = $type;
                $service_provider->phone = $_POST[$field_name]["phone"];
                $service_provider->insert();
                $this->property->$field_name = $service_provider->ID;
            }else{
                $service_provider = ServiceProvider::get(["ID" => $this->property->$field_name]);
                $service_provider->phone = $_POST[$field_name]["phone"];
                $service_provider->update();
            }
        }
    }
    
    private function checkUser(){
        if($this->property->landlord != "NULL"){
            $landlord = User::getUserById($this->property->landlord);
            if(!$landlord->ID){
                create_warning_message(_t(67));
                return FALSE;
            }else{
                return TRUE;
            }
        }else if(empty( array_filter($_POST["landlord"]) )){
            return TRUE;
        }else if(!$_POST["landlord"]["NAME"] || !$_POST["landlord"]["SURNAME"] || !$_POST["landlord"]["PHONE"] || !$_POST["landlord"]["EMAIL"]){
            create_warning_message(_t(226));
            return FALSE;
        }elseif (preg_match("/[^a-z\s\p{L}]+/iu", $_POST["landlord"]["NAME"]) ){
            create_warning_message(_t(26, [mb_strtolower(_t(27))]));
        }elseif (preg_match("/[^a-z\s\p{L}]+/iu", $_POST["landlord"]["SURNAME"]) ) {
            create_warning_message(_t(26, [_t(28)]));
        }elseif (preg_match("/[^0-9]+/i", $_POST["landlord"]["PHONE"]) || strlen($_POST["landlord"]["PHONE"]) != 11) {
            create_warning_message(_t(26, [_t(29)]));
        }elseif(filter_var($_POST["landlord"]["EMAIL"], FILTER_VALIDATE_EMAIL) == ""){
            create_warning_message(_t(30));
        }else{
            $user = new User();
            $user->NAME = $_POST["landlord"]["NAME"];
            $user->SURNAME = $_POST["landlord"]["SURNAME"];
            $user->PHONE = $_POST["landlord"]["PHONE"];
            $user->USERNAME = preg_replace("/[^a-z_\-0-9]+/i", "",mb_strtolower($user->NAME));
            $user->EMAIL = $_POST["landlord"]["EMAIL"];
            $user->address = $_POST["landlord"]["address"];
            $user->postcode = $_POST["landlord"]["postcode"];
            while(!$user->checkUsernameInsertAvailable()){
                $user->USERNAME .= random_int(0,9);
            }
            $user->STATUS = User::STATUS_PENDING;
            $user->insert();
            $this->property->landlord = $user->ID;
            return TRUE;
        }
        return FALSE;
    }

}