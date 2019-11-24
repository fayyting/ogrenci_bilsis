<?php

class InspectionController extends PropertyManagementController{

    public $inspection_report;

    public $form_id = "inspection_form";
    public $form_build_id;

    protected function preprocessPage()
    {
        if(isset($this->arguments[2])){
            $this->inspection_report = is_numeric($this->arguments[2]) ? InspectionReport::get(["ID" => $this->arguments[2]]) : new InspectionReport();
            $this->form_build_id = create_csrf($this->form_id, $this->arguments[2]);
        }
        if(isset($_POST["add"]) && $this->checkCsrfToken()){
            object_map($this->inspection_report, $_POST["inspection"]);
            $this->inspection_report->insert();
            create_warning_message(_t(91), "alert-success");
            core_go_to(BASE_URL."/department/property_management/inspection/{$this->inspection_report->ID}");
        }elseif(isset($_POST["update"]) && $this->checkCsrfToken() && $this->inspection_report->ID){
            object_map($this->inspection_report, $_POST["inspection"]);
            $this->inspection_report->update();
            create_warning_message(_t(32), "alert-success");
        }
    }

    public function echoContent(){
        if($this->inspection_report){
            include "inspection_html.php";
            echo_inpection_form($this);
        }else{
            $inspection_reports = InspectionReport::getAll([]);
            $page_table = array_chunk($inspection_reports, PAGE_SIZE_LIMIT)[0];
            $this->import_view("table_view");
            echo_table([], $page_table ? : []);
        }
    }
}