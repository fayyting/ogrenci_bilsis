<?php

class DepartmentController extends AdminPage{
    
    public $card_items;

    public $subPageController;
    public function __construct($arguments){
        parent::__construct($arguments);
    }
    
    public function check_access(): bool
    {
        return get_current_core_user()->isLoggedIn();
    }

    protected function preprocessPage(){
        switch($this->arguments[0]){
            case "property_management":
                include "property_management/property_management.php";
                $this->subPageController = new PropertyManagementController($this->arguments);
                break;
            case "human_resources":
                break;
            case "finance":
                break;
            case "research-and-development":
                break;
        }
        $this->add_css_files("pages/department/css/department.css");
        $this->card_items = [
            "property_management" => [
                "url"=> BASE_URL."/department/property_management",
                "icon" => "glyphicon glyphicon-list-alt",
                "label" => _t(255),
                "subitems" => [
                    [
                        "url" => "#",
                        "label" => _t(259   )
                    ],
                    [
                        "url" => "#",
                        "label" => _t(260)
                    ],
                    [
                        "url" => "#",
                        "label" => _t(261)
                    ],
                    [
                        "url" => "#",
                        "label" => _t(262)
                    ],
                    [
                        "url" => "#",
                        "label" => _t(263)
                    ],
                    [
                        "url" => "#",
                        "label" => _t(264)
                    ],
                    [
                        "url" => "#",
                        "label" => _t(265)
                    ]
                ]
            ],
            "human_resources" => [
                "url"=> BASE_URL."/department/human_resources",
                "icon" => "glyphicon glyphicon-globe",
                "label" => _t(256)
            ],
            "finance" => [
                "url"=> BASE_URL."/department/finance",
                "icon" => "glyphicon glyphicon-gbp",
                "label" => _t(257)
            ],
            "research-and-development" => [
                "url"=> BASE_URL."/research-and-development",
                "icon" => "glyphicon glyphicon-blackboard",
                "label" => _t(258)
            ]
        ];
    }

    protected function echoContent(){
        if($this->arguments[0]){
            $this->card_items[$this->arguments[0]]["classes"] = ["active"];
            $this->add_js("$('.list-group-item.text-left.active .subitems').slideToggle()");
        }
        include "department_html.php";
        echo_department_page($this);
    }
}