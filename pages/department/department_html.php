<?php
function echo_department_page(DepartmentController $controller){ ?>
    <div class="container-fluid text-center">    
        <div class="row content">
            <div class="col-sm-3">
                <?php $controller->import_view("sidebar_list");
                echo_sidebar_list($controller->card_items, [
                    "icon" => "glyphicon glyphicon-plus"
                ]); ?> 
            </div>
            <?php 
                if($controller->subPageController){?>
                    <div class="col-sm-6">
                        <?php $controller->subPageController->echoPage(); ?>
                    </div>
                <?php } ?>
                
            </div>
        </div>
    </div>
<? }