<form class="property_search_form">
    <div class="row">
        <div class="col-lg-3 col-sm-4 col-xs-6">
            <label for="scheme_a"><?php echo _t(227).":";?></label>
            <?php
            $category_options = [0 => _t(132)];
            $category_options = array_merge($category_options, Property::getAvailableCategoryOptions());
            echo prepare_select_box($category_options, [
                "name" => "property[category]"
            ]);    
            ?>
        </div>
        <div class="col-lg-3 col-sm-4 col-xs-6">
            <label for="scheme_a"><?php echo _t(119).":";?></label>
            <input type="text" name="property[adress]" class="form-control"/>
        </div>
        <div class="col-lg-3 col-sm-4 col-xs-6">
            <label for="scheme_a"><?php echo _t(133).":";?></label>
            <input type="text" name="property[postcode]" class="form-control uppercase_filter"/>
        </div>
        <div class="col-lg-3 col-sm-4 col-xs-6">
            <label for="scheme_a"><?php echo _t(116); ?>:</label>
            <?php 
                echo prepare_select_box_from_query_result(
                    db_select("property_type_a","pta")->select("pta", ["ID", "explain"])->execute(),
                    [ "name" => "property[type]",
                    "null_element" => _t(132)
                    ]
                )
            ?>
        </div>
        <div class="col-lg-3 col-sm-4 col-xs-6">
            <label for="scheme_a"><?php echo _t(117); ?>:</label>
            <?php 
                echo prepare_select_box_from_query_result(
                    db_select("property_statuses", "ps")->select("ps", ["ID", "explain"])->execute(),
                    [ "name" => "property[status]",
                    "null_element" => _t(132)
                    ]
                );
            ?>
        </div>
        <div class="col-lg-3 col-sm-4 col-xs-6">
            <label for="scheme_a"><?php echo _t(122); ?> A:</label>
            <?php 
                echo prepare_select_box_from_query_result(
                    db_select("property_scheme_a","psa")->select("psa", ["ID", "explain"])->execute(),
                    [ "name" => "property[scheme_a]",
                    "null_element" => _t(132)
                    ]
                );
            ?>
        </div>
        <div class="col-lg-3 col-sm-4 col-xs-6">
            <label for="scheme_b"><?php echo _t(122); ?> B:</label>
            <?php 
                echo prepare_select_box_from_query_result(
                    db_select("property_scheme_b","psb")->select("psb", ["ID", "explain"])->execute(),
                    [ "name" => "property[scheme_b]",
                    "null_element" => _t(132)
                    ]
                )
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <button type="button" class="btn btn-info form-control property_search_button">
                <span class="glyphicon glyphicon-search"></span> <?php echo _t(55); ?>
            </button>
        </div>
    </div>
</form>
<div class="property_find_result"></div>
