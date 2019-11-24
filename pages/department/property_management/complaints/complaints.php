<?php

class ComplaintsController extends PropertyManagementController{

    public $complaint_report;

    public $form_id = "complaints_form";
    public $form_build_id;

    protected function preprocessPage()
    {
        if(isset($this->arguments[2])){
            $this->complaint_report = is_numeric($this->arguments[2]) ? ComplaintReport::get(["ID" => $this->arguments[2]]) : new ComplaintReport();
            $this->form_build_id = create_csrf($this->form_id, $this->arguments[2]);
        }
        if(isset($_POST["add"]) && $this->checkCsrfToken()){
            object_map($this->complaint_report, $_POST["complaint"]);
            $this->complaint_report->insert();
            create_warning_message(_t(91), "alert-success");
            core_go_to(BASE_URL."/department/property_management/complaints/{$this->complaint_report->ID}");
        }elseif(isset($_POST["update"]) && $this->checkCsrfToken() && $this->complaint_report->ID){
            object_map($this->complaint_report, $_POST["complaint"]);
            $this->complaint_report->update();
            create_warning_message(_t(32), "alert-success");
        }   
    }

    public function echoContent(){
        if($this->complaint_report){
            include "complaints_html.php";
            echo_complaints_page($this);
        }else{
            $complaint_reports = ComplaintReport::getAll([]);
            $page_table = array_chunk($complaint_reports, PAGE_SIZE_LIMIT)[0];
            $this->import_view("table_view");
            echo_table([], $page_table ? : []);
        }
    }
}