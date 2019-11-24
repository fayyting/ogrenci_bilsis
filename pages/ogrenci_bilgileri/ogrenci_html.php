<?php 
function echo_ogrenci_page(ogrenci_bilgileriController $controller){ 
    $ogrenci = $controller->ogrenci;
    $controller->printMessages();
    ?> 
    <div class="container-fluid text-center content">
        <form method="post" class="text-left">
            <div class="row">
                <?php $controller->printMessages(); ?>

            </div>
            <div class="row">
                <div class="col-xs-12">
                    <label>ad: </label>
                    <input type="text" class="form-control" name="ogrenci[name]" placeholder="name"
                    value="<?php echo $controller->ogrenci->name; ?>"/>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <label>soyad: </label>
                    <input type="text" class="form-control" name="ogrenci[surname]"
                    value="<?php echo $controller->ogrenci->surname; ?>"/>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-3">
                    <label>cinsiyet: </label><br>
                    <?php
                        echo prepare_select_box(
                            [
                                "E" => "E",
                                "K" => "K"
                            ], [
                                "name" => "ogrenci[sex]",
                                "default_value" => $controller->ogrenci->sex
                            ]); 
                    ?>
                </div>
                <div class="col-xs-3">
                    <label> TC: </label>
                    <input type="num" class="form-control" name="ogrenci[tckn]" maxlength="11"
                    value="<?php echo $controller->ogrenci->tckn; ?>"/>
                </div>
            </div>
            <?php if(!$controller->ogrenci-> ID){ ?>
                <div class="submit_container">
                    <input type="submit" name="add" value="<?php echo _t(14); ?>" class="btn btn-info form-control"/>
                </div>
            <?php }else{ ?>
                <div class="submit_container">
                    <input type="submit" name="update" value="<?php echo _t(85); ?>" class="btn btn-warning form-control"/>
                    <input type="submit" name="delete" value="Sil" class="btn btn-danger form-control"/>
                </div>
            <?php } ?>
        </form>
    </div>
<?php }
    