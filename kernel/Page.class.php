<?php

abstract class Page {
    
    protected $arguments;
    protected $js_files;
    protected $js_codes;
    protected $css_files;
    protected $frontend_translations = [];
    protected $accessable_roles = ["USER"];


    public function __construct(array $arguments){
        $this->arguments = $arguments;
    }

    public function check_access(): bool {
        $user_roles = get_current_core_user()->getUserRoles();
        return array_diff($this->accessable_roles, $user_roles) == 0 ? TRUE : FALSE;
    }


    public function echoPage(){
        $this->add_default_js_files();
        $this->add_default_css_files();
        $this->add_default_translations();
        
        $this->preprocessPage();
        echo "<!DOCTYPE html>";
        echo "<html>";
        $this->echoHeader();
        echo '<body>';
        $this->echoNavbar();
        $this->echoContent();
        $this->echoFooter();
        $this->echoTranslations();
        $this->echoJSCodes();
        echo '</body>';
        echo '</html>';
    }
    
    public function import_view($view_name) {
        require __DIR__."/../views/$view_name.php";
    }
    
    public function printMessages() {
        $types = [
            "alert-danger",
            "alert-success",
            "alert-warning",
            "alert-info"
        ];
        if(isset($_SESSION["messages"])){
            foreach ($types as $type){
                if(isset($_SESSION["messages"][$type])){
                    foreach ($_SESSION["messages"][$type] as $key => $message) {
                        $this->printMessage($type, $message);
                        unset($_SESSION["messages"][$type][$key]);
                    }
                }
            }
        }
    }
    
    public function printMessage($type, $message) {
        echo "<div class=\"alert $type \">
            <strong>". get_message_header($type)."</strong> {$message}
        </div>";
    }
    
    protected function preprocessPage(){}
    abstract protected function echoContent();
    
    protected function add_js_files($js_file_path){
        if(is_array($js_file_path)){
            $this->js_files = array_merge($this->css_files, $js_file_path);
        }else{
            $this->js_files[] = $js_file_path;
        }
    }

    protected function add_js(string $js_code){
        if(!$this->js_codes){
            $this->js_codes = [];
        }
        $this->js_codes[] = $js_code;
    }
    
    protected function add_css_files($css_file_path){
        if(is_array($css_file_path)){
            $this->css_files = array_merge($this->css_files, $css_file_path);
        }else{
            $this->css_files[] = $css_file_path;
        }
        
    }
    
    protected function add_frontend_translation(int $translation_id) {
        $this->frontend_translations[$translation_id] = _t($translation_id);
    }

    protected function echoHeader(){
        $this->import_view("header");
        echo_header($this->js_files, $this->css_files);
    }
    protected function echoNavbar(){
        $this->import_view("navbar");
    }
    protected function echoFooter(){
        $this->import_view("footer");
    }
    
    protected function add_default_js_files(){
        $default_js_files = [
            "js/jquery.js",
            "js/bootstrap.min.js",
            "js/bootstrap-select.js",
            "js/moment.js",
            "js/bootstrap-datetimepicker.min.js",
            "js/bootstrap-dialog.min.js",
            "js/easy-numpad.min.js",
            "js/bootstrap-notify.js",
            "js/summernote.js",
            "js/summernote-tr-TR.js",
            "js/core.js",
            "js/housingbritain_gallery.js"
        ];
        if(Translator::$language != "EN"){
            $default_js_files[] = "js/bootstrap-select.".Translator::$language.".js";
        }
        $this->js_files = $default_js_files;
    }
    protected function add_default_css_files(){
        $default_css_files = [
            "css/core.css",
            "css/bootstrap.min.css",
            "css/bootstrap-select.min.css",
            "css/bootstrap-datetimepicker.min.css",
            "css/bootstrap-dialog.min.css",
            "css/easy-numpad.min.css",
            "css/summernote.css",
            "css/animate.css",
            "css/footer.css",
            "css/housingbritain.css",
            "css/housingbritain_gallery.css"
        ];
        $this->css_files = $default_css_files;
    }
    
    protected function add_default_translations(){
        $this->add_frontend_translation(76);
        $this->add_frontend_translation(77);
        $this->add_frontend_translation(53);
        $this->add_frontend_translation(52);
        $this->add_frontend_translation(54);
        $this->add_frontend_translation(84);
        $this->add_frontend_translation(82);
        $this->add_frontend_translation(200);
        $this->add_frontend_translation(201);
        $this->add_frontend_translation(37);
    }

    protected function echoTranslations() {
        echo "<script> var translations = ".json_encode($this->frontend_translations).";"
                . "var language = '".Translator::$language."';</script>";
    }

    protected function echoJSCodes()
    {
        if(!is_array($this->js_codes)){
            return;
        }
        foreach($this->js_codes as $js_code){
            echo "<script> $js_code </script>";
        }
    }
}

