<?php

class PropertiesController extends AdminPage {
    
    public $table_headers;
    public $table_data;
    public $operation;
    public $total_count;
    public $active_page;

    public $edit_controller;

    public function __construct($arguments)
    {
        parent::__construct($arguments);
        if($arguments[0] == "edit"){
            include __DIR__."/editController.php";
            $this->edit_controller = new EditPropertiesController($this->arguments);
            $this->edit_controller->echoPage();
            die();
        }
    }

    public function check_access(): bool
    {
        return get_current_core_user()->isLoggedIn();
    }

    protected function preprocessPage()
    {
        if(count($this->arguments) == 0){
            $this->operation = "active";
        }else{
            $this->operation = $this->arguments[0];
            if(!in_array($this->operation, ["active", "new", "archived"])){
                create_warning_message(_t(67));
                $this->operation = "active";
            }
        }
        $this->add_css_files("pages/properties/css/properties.css");
        $this->add_js_files("pages/properties/js/properties.js");
        $this->add_frontend_translation(115);
    }

    protected function echoContent() {
        include "properties_html.php";
        $this->import_view("table_view");
        switch($this->operation){
            case "active":
            $this->table_headers =  [
                _t(118), 
                _t(119), 
                _t(133),
                "MR", 
                "IR", 
                "<img src='".(BASE_URL."/assets/bed.jpg")."' title='"._t(120)."' />", 
                _t(116).prepare_select_box_from_query_result(
                    db_select("property_type_a")->execute(), 
                    ["name" => "type",
                    "null_element" => "-- "._t(132)." --",
                    "default_value" => intval($_GET["type"]) ]
                ), 
                _t(121), 
                _t(117).prepare_select_box_from_query_result(
                    db_select("property_statuses")->execute(), 
                    ["name" => "status",
                    "null_element" => "-- "._t(132)." --",
                    "default_value" => intval($_GET["status"]) ]
                ), 
                _t(122)." A".prepare_select_box_from_query_result(
                    db_select("property_scheme_a")->execute(), 
                    ["name" => "scheme_a",
                    "null_element" => "-- "._t(132)." --",
                    "default_value" => intval($_GET["scheme_a"]) ]
                ), 
                _t(122)." B".prepare_select_box_from_query_result(
                    db_select("property_scheme_b")->execute(), 
                    ["name" => "scheme_b",
                    "null_element" => "-- "._t(132)." --",
                    "default_value" => intval($_GET["scheme_b"]) ]
                ), 
                "<span class='glyphicon glyphicon-user'></span> Landlord".
                prepare_select_box_from_query_result(
                    db_select(USERS)->limit(AUTOCOMPLETE_SELECT_BOX_LIMIT)->execute(), 
                    ["name" => "user",
                    "null_element" => "-- "._t(132)." --",
                    "default_value" => intval($_GET["user"]),
                    "classes" => ["autocomplete"],
                    "attributes" => ["data-reference-table" => USERS, 
                                    "data-reference-column" => "USERNAME"
                                    ]]
                ), 
                mb_convert_case(_t(28), MB_CASE_TITLE),
                "<img src='".(BASE_URL."/assets/message.png")."' title='"._t(123)."' />" ];
                break;
            case "new":
            $this->table_headers =  [
                _t(118), 
                _t(119), 
                _t(133),
                "PSF", 
                "PCL", 
                "<img src='".(BASE_URL."/assets/bed.jpg")."' title='"._t(120)."' />", 
                _t(116).prepare_select_box_from_query_result(
                    db_select("property_type_a")->execute(), 
                    ["name" => "type",
                    "null_element" => "-- "._t(132)." --",
                    "default_value" => intval($_GET["type"])
                    ]
                ), 
                "Floor", 
                _t(117).prepare_select_box_from_query_result(
                    db_select("property_statuses")->execute(), 
                    ["name" => "status",
                    "null_element" => "-- "._t(132)." --",
                    "default_value" => intval($_GET["status"]) ]
                ), 
                _t(122)." A".prepare_select_box_from_query_result(
                    db_select("property_scheme_a")->execute(), 
                    ["name" => "scheme_a",
                    "null_element" => "-- "._t(132)." --",
                    "default_value" => intval($_GET["scheme_a"]) ]
                ), 
                _t(122)." B".prepare_select_box_from_query_result(
                    db_select("property_scheme_b")->execute(), 
                    ["name" => "scheme_b",
                    "null_element" => "-- "._t(132)." --",
                    "default_value" => intval($_GET["scheme_b"]) ]
                ),
                "<span class='glyphicon glyphicon-user'></span> Landlord".
                prepare_select_box_from_query_result(
                    db_select(USERS)->limit(AUTOCOMPLETE_SELECT_BOX_LIMIT)->execute(), 
                    ["name" => "user",
                    "null_element" => "-- "._t(132)." --",
                    "default_value" => intval($_GET["user"]),
                    "classes" => ["autocomplete"],
                    "attributes" => ["data-reference-table" => USERS, 
                                    "data-reference-column" => "USERNAME"
                                    ]  ]
                ), 
                mb_convert_case(_t(28), MB_CASE_TITLE),
                //"<img src='".(BASE_URL."/assets/send_to_active.png")."' />"
            ];
                break;
            case "archived":
            $this->table_headers =  [
                _t(118), 
                _t(119), 
                _t(133),
                "MR", 
                "IR", 
                "<img src='".(BASE_URL."/assets/bed.jpg")."' title='"._t(120)."' />", 
                _t(116).prepare_select_box_from_query_result(
                    db_select("property_type_a")->execute(), 
                    ["name" => "type",
                    "null_element" => "-- "._t(132)." --",
                    "default_value" => intval($_GET["type"]) ]
                ), 
                _t(121), 
                _t(117).prepare_select_box_from_query_result(
                    db_select("property_statuses")->execute(), 
                    ["name" => "status",
                    "null_element" => "-- "._t(132)." --",
                    "default_value" => intval($_GET["status"]) ]
                ), 
                _t(122)." A".prepare_select_box_from_query_result(
                    db_select("property_scheme_a")->execute(), 
                    ["name" => "scheme_a",
                    "null_element" => "-- "._t(132)." --",
                    "default_value" => intval($_GET["scheme_a"]) ]
                ),
                _t(122)." B".prepare_select_box_from_query_result(
                    db_select("property_scheme_b")->execute(), 
                    ["name" => "scheme_b",
                    "null_element" => "-- "._t(132)." --",
                    "default_value" => intval($_GET["scheme_b"]) ]
                ), 
                "<span class='glyphicon glyphicon-user'></span> Landlord".
                prepare_select_box_from_query_result(
                    db_select(USERS)->limit(AUTOCOMPLETE_SELECT_BOX_LIMIT)->execute(), 
                    ["name" => "user",
                    "null_element" => "-- "._t(132)." --",
                    "default_value" => intval($_GET["user"]),
                    "classes" => ["autocomplete"],
                    "attributes" => ["data-reference-table" => USERS, 
                                    "data-reference-column" => "USERNAME"
                                    ] ]
                ), 
                mb_convert_case(_t(28), MB_CASE_TITLE)
            ];
                break;
        }
        $this->active_page = isset($_GET["page"]) && $_GET["page"] > 0 ? intval($_GET["page"]) : 1;
        list($this->table_data, $this->total_count) = Property::getTableDataByFilter($this->operation, $this->active_page);
        echo_properties_page($this);
    }

}
