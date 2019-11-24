<?php

function echo_bloglar_page(BloglarController $controller){ ?>
    <div class="container-fluid text-center content">   
        <div class="row">
            <div class="col-sm-4">
                <a href="/blog_edit/add" class="form-control btn btn-primary pull-left"
                style="margin-bottom: 20px;"
                ><span class="glyphicon glyphicon-plus"></span> Ekle </a> 
            </div>
        </div>
        <div class="row">
            <?php foreach($controller->bloglar as $blog){ ?>
            <div class="col-sm-4">
                <div class="card">
                    <a href="<?php echo BASE_URL."/"; ?>">
                        <div class="card-container">
                            <span class="glyphicon glyphicon-book"></span> <?php echo $blog->baslik; ?>
                            <br>
                            <a href="<?php echo BASE_URL."/blog_edit/$blog->ID"; ?>">Düzenle</a> 
                            <a href="<?php echo BASE_URL."/blog_gor/$blog->ID";?>">görüntüle</a>
                        </div>
                    </a>   
                </div>            
            </div>
            <?php } ?>
        </div>
    </div>
<?php 
}