    
<?php function echo_blog_edit_page(Blog_EditController $controller){ ?>    
    <div class="container-fluid text-center content">
        <form method="post" class="text-left">
            <div class="row">
                <?php $controller->printMessages(); ?>

            </div>
            <div class="row">
                <div class="col-xs-12">
                    <label>Başlık: </label>
                    <input type="text" class="form-control" name="blog[baslik]" placeholder="Başlık"
                    value="<?php echo $controller->blog->baslik; ?>"/>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <label>İçerik: </label>
                    <textarea class="summernote" name="blog[icerik]"><?php echo $controller->blog->icerik; ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-3">
                    <label>Yazar: </label>
                    <?php 
                    echo prepare_select_box_from_query_result(
                        db_select(USERS)->condition("USERNAME != :guest", [":guest" => "guest"])->limit(AUTOCOMPLETE_SELECT_BOX_LIMIT)->execute(),
                        [ "name" => "blog[yazar]",
                        "default_value" => $controller->blog->yazar,
                        "attributes" => ["id" => "landlord",
                                "data-reference-table" => USERS, 
                                "data-reference-column" => "USERNAME"
                            ],
                        "classes" => ["autocomplete", "create_if_not_exist"],
                        "null_element" => _t(225)  
                        ]
                        );
                ?>
                </div>

                <div class="col-xs-3">
                    <label>Tarih: </label>
                    <input type="text" class="form-control dateinput" name="blog[yayinlanma_tarihi]" 
                    value="<?php echo $controller->blog->yayinlanma_tarihi; ?>"/>
                </div>
            </div>
            <?php if(!$controller->blog->ID){ ?>
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