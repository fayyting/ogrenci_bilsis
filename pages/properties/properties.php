<?php

class PropertiesController extends AdminPage {
    
    public $table_headers;
    public $table_data;
    public $operation;
    public $total_count;
    public $active_page;

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
    }

    protected function echoContent() {
        include "properties_html.php";
        $this->import_view("table_view");
        switch($this->operation){
            case "active":
            $this->table_headers =  [
                "Reference", 
                "Address", 
                "MR", 
                "IR", 
                "<img src='".(BASE_URL."/assets/bed.jpg")."' />", 
                "Type".prepare_select_box_from_query_result(db_select("property_type_a")->execute(), "type", "-- Type --", intval($_GET["type"]) ), 
                "Floor", 
                "Status".prepare_select_box_from_query_result(db_select("property_statuses")->execute(), "status","-- Status --", intval($_GET["status"])), 
                "Scheme".prepare_select_box_from_query_result(db_select("property_scheme_a")->execute(), "scheme","-- Scheme --", intval($_GET["scheme"])), 
                "<span class='glyphicon glyphicon-user'></span> Landlord".prepare_select_box_from_query_result(db_select(USERS)->execute(), "user","-- Landlord --", intval($_GET["user"])), 
                "Surname",
                "<img src='".(BASE_URL."/assets/message.png")."' />" ];
                break;
            case "new":
            $this->table_headers =  [
                "Reference", 
                "Address", 
                "PSF", 
                "PCL", 
                "<img src='".(BASE_URL."/assets/bed.jpg")."' />", 
                "Type".prepare_select_box_from_query_result(db_select("property_type_a")->execute(), "type", "-- Type --", intval($_GET["type"]) ), 
                "Floor", 
                "Status".prepare_select_box_from_query_result(db_select("property_statuses")->execute(), "status","-- Status --", intval($_GET["status"])), 
                "Scheme".prepare_select_box_from_query_result(db_select("property_scheme_a")->execute(), "scheme","-- Scheme --", intval($_GET["scheme"])), 
                "<span class='glyphicon glyphicon-user'></span> Landlord".prepare_select_box_from_query_result(db_select(USERS)->execute(), "user","-- Landlord --", intval($_GET["user"])), 
                "Surname",
                "<img src='".(BASE_URL."/assets/send_to_active.png")."' />"];
                break;
            case "archived":
            $this->table_headers =  [
                "Reference", 
                "Address", 
                "MR", 
                "IR", 
                "<img src='".(BASE_URL."/assets/bed.jpg")."' />", 
                "Type".prepare_select_box_from_query_result(db_select("property_type_a")->execute(), "type", "-- Type --", intval($_GET["type"]) ), 
                "Floor", 
                "Status".prepare_select_box_from_query_result(db_select("property_statuses")->execute(), "status","-- Status --", intval($_GET["status"])), 
                "Scheme".prepare_select_box_from_query_result(db_select("property_scheme_a")->execute(), "scheme","-- Scheme --", intval($_GET["scheme"])), 
                "<span class='glyphicon glyphicon-user'></span> Landlord".prepare_select_box_from_query_result(db_select(USERS)->execute(), "user","-- Landlord --", intval($_GET["user"])), 
                "Surname",
                "Send to New"];
                break;
        }
        $this->active_page = isset($_GET["page"]) && $_GET["page"] > 0 ? intval($_GET["page"]) : 1;
        list($this->table_data, $this->total_count) = Property::getTableDataByFilter($this->operation, $this->active_page);
        echo_properties_page($this);
    }

}
