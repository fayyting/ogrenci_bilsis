<?php

class Not_foundController extends Page {
    
    public function check_access(): bool {
        return TRUE;
    }
    
    protected function echoContent() {
        http_response_code(404);
        require 'not_found_html.php';
    }

}