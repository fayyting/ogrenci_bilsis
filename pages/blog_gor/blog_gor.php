<?php
class Blog_gorController extends Page{
    public $blog ,$yorumlar;
    protected function preprocessPage()
    {
        $blog_id = $this->arguments[0];
        $this->blog = Blog::get(["ID" => $blog_id]);
        if(isset($_POST["ekle"])){
            //ekleme işlemi yapılacak
            $yorum = new Yorum();
            $yorum->yorum_metni= $_POST["comment"];
            $yorum->yazar = get_current_core_user()->ID;
            $yorum->yayin_tarihi= date("Y-m-d h:i:s");
            $yorum->blog_id =$this->blog->ID;
            $yorum->insert();
            create_warning_message("yorumunuz eklendi", "alert-success");

        }



        
        $this->yorumlar = Yorum::getAll(["blog_id"=> $this->blog->ID]);

    }
    public function echoContent(){
        include "blog_gor_html.php";
        echo_blog_gor_page($this);
    }
    public function check_access(): bool
    {
        return true;
    }
}