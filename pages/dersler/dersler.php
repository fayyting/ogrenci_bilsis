<?php
class derslerController extends Page{
    public $dersler;
    public $tablo_basligi;

    protected function preprocessPage()
    {
        $this->add_css_files("pages/ogrenciler/ogrenciler.css");
        $this->tablo_basligi = [
            "ID",
            "Ders Adı", 
        ];
    }

    protected function echoContent()
    {
        $this->dersler= Ders::getAll([]);
        foreach($this->dersler as $ders){
            unset($ders->table);
            $ders->duzenle = "<a href='".BASE_URL."/insert/dersler/$ders->ID'>Düzenle</a>";
        
        }
        $this->import_view("table_view");
        include "dersler_html.php";
        echo_dersler_page($this);
        
    }
    public function check_access(): bool
    {
        return true;
    }
}