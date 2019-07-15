<?php
function echo_properties_page(PropertiesController $controller){ ?>

    <div class="container-fluid text-center content">
        <ul class="nav nav-tabs nav-justified">
            <li class="<?php echo $controller->operation == "active" ? "active" : ""; ?>"><a href="<?php echo BASE_URL."/properties/active"; ?>">Active</a></li>
            <li class="<?php echo $controller->operation == "new" ? "active" : ""; ?>"><a href="<?php echo BASE_URL."/properties/new"; ?>">New</a></li>
            <li class="<?php echo $controller->operation == "archived" ? "active" : ""; ?>"><a href="<?php echo BASE_URL."/properties/archived"; ?>">Archived</a></li>
        </ul>
        <form method='GET' id="filter_form">
            <?php $controller->printMessages();
            echo_table($controller->table_headers, $controller->table_data); ?>
        </form>
    </div>

<?php }