<?php
function echo_ogrencinin_dersleri_page(ogrencinin_dersleriController $controller){
?>
<a class="btn btn-info" href="<?php echo BASE_URL."/insert/dersler"; ?>"><span class="glyphicon glyphicon-plus"></span>Yeni Ders Ekle</a>
<?php  
echo_table($controller->adi,$controller->dersleri);
}
