<?php

class PlacementsController extends PropertyManagementController{

    public $placement_report;

    public $form_id = "placements_form";
    public $form_build_id;

    protected function preprocessPage()
    {
        if(isset($this->arguments[2])){
            $this->placement_report = is_numeric($this->arguments[2]) ? PlacementReport::get(["ID" => $this->arguments[2]]) : new PlacementReport();
            $this->form_build_id = create_csrf($this->form_id, $this->arguments[2]);
        }
        if(isset($_POST["add"]) && $this->checkCsrfToken()){
            object_map($this->placement_report, $_POST["placement"]);
            $this->placement_report->insert();
            create_warning_message(_t(91), "alert-success");
            core_go_to(BASE_URL."/department/property_management/placements/{$this->placement_report->ID}");
        }elseif(isset($_POST["update"]) && $this->checkCsrfToken() && $this->placement_report->ID){
            object_map($this->placement_report, $_POST["placement"]);
            $this->placement_report->update();
            create_warning_message(_t(32), "alert-success");
        }
    }

    public function echoContent(){
        if($this->placement_report){
            include "placements_html.php";
            echo_placements_page($this);
        }else{
            $placement_reports = PlacementReport::getAll([]);
            $page_table = array_chunk($placement_reports, PAGE_SIZE_LIMIT)[0];
            $this->import_view("table_view");
            echo_table([], $page_table ? : []);
        }
    }
}