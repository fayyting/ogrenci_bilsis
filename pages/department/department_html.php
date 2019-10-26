<?php
function echo_department_page(DepartmentController $controller){ ?>
    <div class="container-fluid text-center">    
        <div class="row content">
            <?php 
                if($controller->subPageController){?>
                    <div class="col-sm-3">
                        <?php $controller->import_view("sidebar_list");
                        echo_sidebar_list($controller->card_items, [
                            "icon" => "glyphicon glyphicon-plus"
                        ]); ?> 
                    </div>
                    <div class="col-sm-6">
                        <?php $controller->subPageController->echoContent(); ?>
                    </div>
                <?php }else{ ?>
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6">
                    <?php foreach($controller->card_items as $item){
                    ?>
                        <div class="col-sm-6">
                            <div class="card">
                                <a href="<?php echo $item["url"]; ?>">
                                    <div class="card-container">
                                        <span class="<?php echo $item["icon"] ?>"></span><?php echo $item["label"]; ?>
                                    </div>
                                </a>   
                            </div>            
                        </div>
                    <?php } ?> 
                    </div>
                <?php } ?>
                
            </div>
        </div>
    </div>
<? }