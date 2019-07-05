<?php

class LoginController extends Page{
    
    const FORM_ID = "login_form";
    

    public $form_build_id;
    
    public function __construct($arguments) {
        parent::__construct($arguments);
        if(get_current_core_user()->isLoggedIn()){
            core_go_to(BASE_URL."/");
        }
    }
    
    public function check_access() : bool {
        return TRUE;
    }

    protected function preprocessPage(){
        $userip = isset($_POST["form_build_id"]) ? get_csrf($_POST["form_build_id"], self::FORM_ID) : get_user_ip();
        if($userip != get_user_ip()){
            if(isset($_SESSION[LOGIN_UNTRUSTED_ACTIONS])){
                $_SESSION[LOGIN_UNTRUSTED_ACTIONS]++;
            }else{
                $_SESSION[LOGIN_UNTRUSTED_ACTIONS] = 1;
            }
        }
        try{
            if(isset($_POST["login"])){
                $user = User::login($_POST["username"], $_POST["password"]);    
                $redirect = BASE_URL;
                if(isset($_GET["destination"])){
                    $destination = $_GET["destination"];
                    if(filter_var(BASE_URL.$destination, FILTER_VALIDATE_URL)){
                        $redirect .= $destination;
                    }
                }
                core_go_to($redirect);
            }
        } catch (Exception $ex){
            http_response_code(400);
            create_warning_message($ex->getMessage());
            return;
        }
    }


    protected function echoContent() {
        $this->form_build_id = create_csrf(self::FORM_ID , get_user_ip());
        require 'login_html.php';
        echo_login_page($this);
    }
    
    public function echoNavbar(){}
}