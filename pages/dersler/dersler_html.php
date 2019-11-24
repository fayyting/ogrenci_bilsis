<?php
function echo_dersler_page(derslerController $controller){
?>
<a class="btn btn-info" href="<?php echo BASE_URL."/insert/dersler"; ?>"><span class="glyphicon glyphicon-plus"></span>Yeni Kayıt Ekle</a>
<?php  
echo_table($controller->tablo_basligi,$controller->dersler);
}
