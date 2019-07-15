<?php

class PropertiesController extends AdminPage {
    
    public $table_headers;
    public $table_data;
    public $operation;

    public function check_access(): bool
    {
        return true;
    }

    protected function preprocessPage()
    {
        //$lang = Property::get(["adress" => "Logout"]);
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
                "Bedrooms", 
                "Type".prepare_select_box_from_query_result(db_select("property_type_a")->execute(), "type", "-- Type --", intval($_GET["type"]) ), 
                "Floor", 
                "Status".prepare_select_box_from_query_result(db_select("property_statuses")->execute(), "status","-- Status --", intval($_GET["status"])), 
                "Scheme".prepare_select_box_from_query_result(db_select("property_scheme_a")->execute(), "scheme","-- Scheme --", intval($_GET["scheme"])), 
                "Landlord".prepare_select_box_from_query_result(db_select(USERS)->execute(), "user","-- Landlord --", intval($_GET["user"])), 
                "Surname",
                "Messages"];
                break;
            case "new":
            $this->table_headers =  [
                "Reference", 
                "Address", 
                "PSF", 
                "PCL", 
                "Bedrooms", 
                "Type".prepare_select_box_from_query_result(db_select("property_type_a")->execute(), "type", "-- Type --", intval($_GET["type"]) ), 
                "Floor", 
                "Status".prepare_select_box_from_query_result(db_select("property_statuses")->execute(), "status","-- Status --", intval($_GET["status"])), 
                "Scheme".prepare_select_box_from_query_result(db_select("property_scheme_a")->execute(), "scheme","-- Scheme --", intval($_GET["scheme"])), 
                "Landlord".prepare_select_box_from_query_result(db_select(USERS)->execute(), "user","-- Landlord --", intval($_GET["user"])), 
                "Surname",
                "Send Active"];
                break;
            case "archived":
            $this->table_headers =  [
                "Reference", 
                "Address", 
                "MR", 
                "IR", 
                "Bedrooms", 
                "Type".prepare_select_box_from_query_result(db_select("property_type_a")->execute(), "type", "-- Type --", intval($_GET["type"]) ), 
                "Floor", 
                "Status".prepare_select_box_from_query_result(db_select("property_statuses")->execute(), "status","-- Status --", intval($_GET["status"])), 
                "Scheme".prepare_select_box_from_query_result(db_select("property_scheme_a")->execute(), "scheme","-- Scheme --", intval($_GET["scheme"])), 
                "Landlord".prepare_select_box_from_query_result(db_select(USERS)->execute(), "user","-- Landlord --", intval($_GET["user"])), 
                "Surname",
                "Send to New"];
                break;
        }
        $this->table_data = Property::getTableDataByFilter($this->operation);
        echo_properties_page($this);
    }

}
