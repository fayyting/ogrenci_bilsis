<?php

class DepartmentController extends AdminPage{
    
    public $card_items;

    public $subPageController;
    public function __construct($arguments){
        parent::__construct($arguments);
    }

     public function echoPage()
     {
         if(get_called_class() === "DepartmentController"){
             parent::echoPage();
         }else{
             $this->echoContent();
         }
     }
    
    public function check_access(): bool
    {
        return get_current_core_user()->isLoggedIn();
    }

    protected function preprocessPage(){
        $this->add_frontend_translation(142);
        $this->add_frontend_translation(143);
        $this->add_js_files("js/property_finder.js");

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
                    "placements" => [
                        "label" => _t(259)
                    ],
                    "maintanence" => [
                        "label" => _t(260)
                    ],
                    "inspection" => [
                        "label" => _t(261)
                    ],
                    "incidents" => [
                        "label" => _t(262)
                    ],
                    "voids" => [
                        "label" => _t(263)
                    ],
                    "document_monitoring_list" => [
                        "label" => _t(264)
                    ],
                    "complaints" => [
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
        if($this->arguments[0]){
            $this->setTitle(_t(129).": ".$this->card_items[$this->arguments[0]]["label"]);
        }else{
            $this->setTitle(_t(129));
        }
        foreach($this->card_items as $item_key => &$item){
            if(is_array($item["subitems"])){
                foreach($item["subitems"] as $subitem_key => &$subitem){
                    $subitem["url"] = BASE_URL."/department/$item_key/$subitem_key";
                    $subitem["options"] = [
                        [
                            "label" => _t(14), 
                            "icon" => "glyphicon glyphicon-floppy-disk",
                            "url" => BASE_URL."/department/$item_key/$subitem_key/add"
                        ]
                    ];
                }
            }
        }

        if($this->subPageController){
            $this->subPageController->preprocessPage();
        }
    }

    protected function echoContent(){
        if($this->arguments[0]){
            $this->card_items[$this->arguments[0]]["classes"] = ["active"];
            $this->add_js("$('.list-group-item.text-left.active .subitems').toggle()");
            $this->add_js("$('.list-group-item.text-left.active span.glyphicon').toggleClass('glyphicon-plus glyphicon-minus');");
            
            if($this->arguments[1]){
                $this->card_items[$this->arguments[0]]["subitems"][$this->arguments[1]]["classes"] = ["active"];
            }
        }
        include "department_html.php";
        echo_department_page($this);
    }
}