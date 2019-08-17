<?php
function echo_properties_page(PropertiesController $controller){ ?>

    <div class="container-fluid text-center content">
        <div class="row">
            <div class="col-sm-3">
                    <a href="<?php echo BASE_URL."/properties/edit/0"; ?>" class="btn btn-info pull-left form-control" >
                        <span class="glyphicon glyphicon-plus"></span> <?php echo _t(135); ?>
                    </a>
            </div>
        </div>

        <ul class="nav nav-tabs nav-justified">
            <li class="<?php echo $controller->operation == "active" ? "active" : ""; ?>"><a href="<?php echo BASE_URL."/properties/active"; ?>"><?php echo _t(124); ?></a></li>
            <li class="<?php echo $controller->operation == "new" ? "active" : ""; ?>"><a href="<?php echo BASE_URL."/properties/new"; ?>"><?php echo _t(125); ?></a></li>
            <li class="<?php echo $controller->operation == "archived" ? "active" : ""; ?>"><a href="<?php echo BASE_URL."/properties/archived"; ?>"><?php echo _t(126); ?></a></li>
            <li class="<?php echo $controller->operation == "view" ? "active" : ""; ?>"><a href="<?php echo BASE_URL."/properties/view"; ?>"><?php echo _t(136); ?></a></li>
        </ul>
        <div class="row" id="main_content">
            <form method='GET' id="filter_form">
                <?php $controller->printMessages();
                echo_table($controller->table_headers, $controller->table_data, "", "", true); ?>
            </form>
        </div>
        <div class="row">
        <?php $controller->import_view("pagination");
            unset($_GET["page"]);
            echo_pagination_view("?".http_build_query($_GET), 1, $controller->total_count);
            ?>
        </div>
    </div>

<?php }