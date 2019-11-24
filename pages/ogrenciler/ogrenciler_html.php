<?php 
function echo_ogrenciler_page(ogrencilerController $controller){

?>
<a href="<?php echo BASE_URL."/ogrenci_bilgileri"; ?>">Yeni Kayıt Ekle</a>
<?php 
echo_table($controller->tablo_basligi, $controller->ogrenciler);

}
