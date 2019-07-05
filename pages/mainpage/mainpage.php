<?php

class MainpageController extends AdminPage{
    public function __construct(array $arguments) {
        parent::__construct($arguments);
    }
    
    public function check_access() : bool {
        return get_current_core_user()->isLoggedIn();
    }

    protected function preprocessPage() {
        $this->add_css_files("pages/mainpage/css/mainpage.css");
    }

    protected function echoContent() {
        require 'mainpage_html.php';
        echo_mainpage($this);
    }
    
    

}