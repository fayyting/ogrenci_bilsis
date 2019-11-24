<?php
class ogrencinin_dersleriController extends Page{
    public $ogrencinin_dersleri;
    public $tablo_basligi;

    protected function preprocessPage()
    {
        $this->add_css_files("pages/ogrencinin_dersleri/ogrencinin_dersleri.css");
        $this->tablo_basligi = [
            "ID",
            "Ögrenci Adı",
            "Dersleri" 
        ];
    }

    protected function echoContent()
    {
        $this->ogrencinin_dersleri= Ders::getAll([]);
        foreach($this->ogrencinin_dersleri as $alinan_dersler){
            unset($alinan_dersler->table);
            $alinan_dersler->duzenle = "<a href='".BASE_URL."/ogrencinin_dersleri/$alinan_dersler->ID'>Düzenle</a>";
        
        }
        $this->import_view("table_view");
        include "ogrencinin_dersleri_html.php";
        echo_ogrencinin_dersleri_page($this);
        
    }
    public function check_access(): bool
    {
        return true;
    }
}