<?php
class ogrenci_bilgileriController extends Page{
    public $ogrenci;
    protected function preprocessPage()
    {
    
       $ogrenci_id = $this->arguments[0];
        $this->ogrenci = Ogrenci::get(["ID" => $ogrenci_id]);
       if(isset($_POST["add"])){
           //Ekleme işlemi yapılacak
           $this->ogrenci = new Ogrenci();
           object_map($this->ogrenci, $_POST["ogrenci"]);
           $this->ogrenci->insert();
           create_warning_message("Kayıt başarıyla eklendi.", "alert-success");
           

       }else if(isset($_POST["update"])) {
           //güncelleme işlemi yapılacak
           object_map($this->ogrenci, $_POST["ogrenci"]);
           $this->ogrenci->update();
           create_warning_message("kaydınız gücellendi." ,"alert-success");
         
       }else if(isset($_POST["delete"])){
           $this->ogrenci_->delete();
           create_warning_message("Ögrenci silinmiştir.","alert-success");
       }
    }
    
    public function echoContent(){
        include "ogrenci_html.php";
        echo_ogrenci_page($this);
    }

    public function check_access(): bool
    {
        return true;
    }
}
