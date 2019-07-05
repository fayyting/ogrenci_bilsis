<?php

abstract class AdminPage extends Page {
    
    public function check_access(): bool {
        return get_current_core_user()->isRoot();
    }
    
    public function __construct(array $arguments) {
        parent::__construct($arguments);
    }
    
    protected function preprocessPage() {
        $this->add_js_files("js/core.js");
        if(get_current_core_user()->isRoot()){
            $this->add_js_files("js/core-admin.js");
        }
    }
    
    protected function echoTranslations() {
        $this->add_frontend_translation(79);
        $this->add_frontend_translation(80);
        $this->add_frontend_translation(81);
        $this->add_frontend_translation(62);
        $this->add_frontend_translation(63);
        $this->add_frontend_translation(82);
        $this->add_frontend_translation(83);
        
        parent::echoTranslations();
    }
}


