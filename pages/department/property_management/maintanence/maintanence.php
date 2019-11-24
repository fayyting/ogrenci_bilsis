<?php

class MaintanenceController extends PropertyManagementController{

    public $maintanence_report;

    public $form_id = "maintanence_form";
    public $form_build_id;

    protected function preprocessPage()
    {
        if(isset($this->arguments[2])){
            $this->maintanence_report = is_numeric($this->arguments[2]) ? MaintanenceReport::get(["ID" => $this->arguments[2]]) : new MaintanenceReport();
            $this->form_build_id = create_csrf($this->form_id, $this->arguments[2]);
        }
        if(isset($_POST["add"]) && $this->checkCsrfToken()){
            object_map($this->maintanence_report, $_POST["maintanence"]);
            $this->maintanence_report->insert();
            create_warning_message(_t(91), "alert-success");
            core_go_to(BASE_URL."/department/property_management/maintanence/{$this->maintanence_report->ID}");
        }elseif(isset($_POST["update"]) && $this->checkCsrfToken() && $this->maintanence_report->ID){
            object_map($this->maintanence_report, $_POST["maintanence"]);
            $this->maintanence_report->update();
            create_warning_message(_t(32), "alert-success");
        }
    }

    public function echoContent(){
        if($this->maintanence_report){
            include "maintanence_html.php";
            echo_maintanence_page($this);
        }else{
            $maintanence_reports = MaintanenceReport::getAll([]);
            $page_table = array_chunk($maintanence_reports, PAGE_SIZE_LIMIT)[0];
            $this->import_view("table_view");
            echo_table([], $page_table ? : []);
        }
    }
}