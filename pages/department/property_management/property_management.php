<?php

class PropertyManagementController extends DepartmentController{

    protected function preprocessPage()
    {
        switch($this->arguments[1]){
            case "inspection":
                include "inspection/inspection.php";
                $this->subPageController = new InspectionController($this->arguments);
                break;
            case "placements":
                include "placements/placements.php";
                $this->subPageController = new PlacementsController($this->arguments);
                break;
            case "maintanence":
                include "maintanence/maintanence.php";
                $this->subPageController = new MaintanenceController($this->arguments);
                break;
            case "complaints":
                include "complaints/complaints.php";
                $this->subPageController = new ComplaintsController($this->arguments);
                break;
        }
        if($this->subPageController){
            $this->subPageController->preprocessPage();
        }
    }

    public function echoContent(){
        if($this->subPageController){
            $this->subPageController->echoContent();
        }
    }

    protected function checkCsrfToken(){
        if(get_csrf($_POST["form_build_id"], $this->form_id) != $this->arguments[2]){
            create_warning_message(_t(67));
            return FALSE;
        }else{
            return TRUE;
        }
    }
}