<?php

class EditPropertiesController extends AdminPage{
    public $property;

    public $form_id = "property_edit_form";
    public $form_build_id;

    protected function preprocessPage()
    {
        if($this->arguments[1]){
            $this->property = new Property();
            $this->property->getById($this->arguments[1]);
        }
        if(isset($_POST["add"])){
            $this->addNewProperty();
        }elseif(isset($_POST["update"])){
            $this->updateProperty();
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
        $this->property = new Property();
        object_map($this->property, $_POST["property"]);
        $this->property->insert();
        create_warning_message(_t(91));
        core_go_to(BASE_URL."/properties/edit/".$this->property->ID);
    }

    private function updateProperty(){
        if(!$this->check_csrf()){
            return;
        }
        object_map($this->property, $_POST["property"]);
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
}