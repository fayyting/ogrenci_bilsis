<?php

class Blog_EditController extends Page{
    public $blog;

    protected function preprocessPage()
    {
       $blog_id = $this->arguments[0];
        $this->blog = Blog::get(["ID" => $blog_id]);
       if(isset($_POST["add"])){
           //Ekleme işlemi yapılacak
           $this->blog = new Blog();
           object_map($this->blog, $_POST["blog"]);
           $this->blog->insert();
           create_warning_message("Kayıt başarıyla eklendi.", "alert-success");
           core_go_to(BASE_URL."/blog_edit/{$this->blog->ID}");

       }else if(isset($_POST["update"])) {
           //güncelleme işlemi yapılacak
           object_map($this->blog, $_POST["blog"]);
           $this->blog->update();
           create_warning_message("kaydınız gücellendi." ,"alert-success");
         
       }else if(isset($_POST["delete"])){
           $this->blog->delete();
           create_warning_message("Blogunuz silinmiştir.","alert-success");
       }
    } 

    public function echoContent(){
        include "blog_edit_html.php";
        echo_blog_edit_page($this);
    }

    public function check_access(): bool
    {
        return isset($this->arguments[0]);
    }
} 