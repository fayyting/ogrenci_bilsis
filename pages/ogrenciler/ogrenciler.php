<?php
class ogrencilerController extends Page{
    public $ogrenciler;
    public $tablo_basligi;

    protected function preprocessPage()
    {
        $this->add_css_files("pages/ogrenciler/ogrenciler.css");
        $this->tablo_basligi = [
            "ID",
            "Ad",
            "Soyad",
            "Cinsiyet",
            "TCKN"
        ];
    }

    protected function echoContent()
    {
        $this->ogrenciler= Ogrenci::getAll([]);
        foreach($this->ogrenciler as $ogrenci){
            unset($ogrenci->table);
            $ogrenci->duzenle = "<a href='".BASE_URL."/ogrenci_bilgileri/$ogrenci->ID'>Düzenle</a>";
            $ogrenci->ders_ekle = "<a href='".BASE_URL."/insert/ogrencinin_dersleri/$ogrenci->ID'>Ders Ekle</a>";
            $ogrenci->ders_notu_ekle = "<a href='".BASE_URL."/insert/sinav/$ogrenci->ID'>Sınav Notu Ekle</a>";
            $ogrenci->karne = "<a href='".BASE_URL."/ogrenci_bilgileri/$ogrenci->ID'>Karne Görüntüle</a>";
        
        }
        $this->import_view("table_view");
        include "ogrenciler_html.php";
        echo_ogrenciler_page($this);
        
    }
    public function check_access(): bool
    {
        return true;
    }
}