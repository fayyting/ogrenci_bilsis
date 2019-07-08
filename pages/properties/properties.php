<?php

class PropertiesController extends AdminPage {
    
    public $table_headers;
    public $operation;

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
                "Type".get_supported_data_types()["MUL"]["input_field_callback"](null, ["type", "int(11)", "YES", "MUL", NULL, ""], Property::TABLE), 
                "Floor", 
                "Status".get_supported_data_types()["MUL"]["input_field_callback"](null, ["status", "int(11)", "YES", "MUL", NULL, ""], Property::TABLE), 
                "Scheme".get_supported_data_types()["MUL"]["input_field_callback"](null, ["scheme", "int(11)", "YES", "MUL", NULL, ""], Property::TABLE), 
                "Landlord".get_supported_data_types()["MUL"]["input_field_callback"](null, ["landlord", "int(11)", "YES", "MUL", NULL, ""], Property::TABLE),
                "Messages"];
                break;
            case "new":
            $this->table_headers =  [
                "Reference", 
                "Address", 
                "PSF", 
                "PCL", 
                "Bedrooms", 
                "Type".get_supported_data_types()["MUL"]["input_field_callback"](null, ["type", "int(11)", "YES", "MUL", NULL, ""], Property::TABLE), 
                "Floor", 
                "Status".get_supported_data_types()["MUL"]["input_field_callback"](null, ["status", "int(11)", "YES", "MUL", NULL, ""], Property::TABLE), 
                "Scheme".get_supported_data_types()["MUL"]["input_field_callback"](null, ["scheme", "int(11)", "YES", "MUL", NULL, ""], Property::TABLE), 
                "Landlord".get_supported_data_types()["MUL"]["input_field_callback"](null, ["landlord", "int(11)", "YES", "MUL", NULL, ""], Property::TABLE), 
                "Send Active"];
                break;
            case "archived":
            $this->table_headers =  [
                "Reference", 
                "Address", 
                "MR", 
                "IR", 
                "Bedrooms", 
                "Type".get_supported_data_types()["MUL"]["input_field_callback"](null, ["type", "int(11)", "YES", "MUL", NULL, ""], Property::TABLE), 
                "Floor", 
                "Status".get_supported_data_types()["MUL"]["input_field_callback"](null, ["status", "int(11)", "YES", "MUL", NULL, ""], Property::TABLE), 
                "Scheme".get_supported_data_types()["MUL"]["input_field_callback"](null, ["scheme", "int(11)", "YES", "MUL", NULL, ""], Property::TABLE), 
                "Landlord".get_supported_data_types()["MUL"]["input_field_callback"](null, ["landlord", "int(11)", "YES", "MUL", NULL, ""], Property::TABLE), 
                "Send to New"];
                break;
        }
        echo_properties_page($this, Property::getAll());
    }

}
