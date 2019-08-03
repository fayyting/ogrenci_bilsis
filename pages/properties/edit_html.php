<?php
function echo_properties_edit_page(EditPropertiesController $controller){ ?>

    <div class="container container-fluid content">
        <div class="row">
            <form method='POST' id="edit_form">
                <?php $controller->printMessages(); ?>
                <div class="col-12">
                    <label for="adress"><?php echo _t(119); ?></label>
                    <textarea id="adress" name="property[adress]" class="form-control"><?php echo $controller->property->adress ? $controller->property->adress : ""; ?></textarea>
                </div>
                <div class="col-12">
                    <label for="bedrooms"><?php echo _t(120); ?></label>
                    <input type="number" id="bedrooms" name="property[bedrooms]" class="form-control" 
                    placeholder="<?php echo _t(120); ?>"
                    value="<?php echo $controller->property->bedrooms ? $controller->property->bedrooms : ""; ?>" />
                </div>
                <div class="col-12">
                    <label for="type"><?php echo _t(116); ?></label>
                    <?php 
                        echo prepare_select_box_from_query_result(
                            db_select("property_type_a")->execute(),
                            [ "name" => "property[type]",
                              "default-value" => $controller->property->type ? $controller->property->type : "",
                              "attributes" => ["id" => "type"]
                            ]
                        )
                    ?>
                </div>
                <div class="col-12">
                    <label for="floor"><?php echo _t(121); ?></label>
                    <input type="number" id="floor" name="property[floor]" class="form-control" placeholder="<?php echo _t(121); ?>" 
                    value="<?php echo $controller->property->floor ? $controller->property->floor : ""; ?>"/>
                </div>
                <div class="col-12">
                    <label for="status"><?php echo _t(117); ?></label>
                    <?php 
                        echo prepare_select_box_from_query_result(
                            db_select("property_statuses")->execute(),
                            [ "name" => "property[status]",
                              "default-value" => $controller->property->status ? $controller->property->status : "",
                              "attributes" => ["id" => "status"]
                            ]
                        )
                    ?>
                </div>
                <div class="col-12">
                    <label for="scheme"><?php echo _t(122); ?></label>
                    <?php 
                        echo prepare_select_box_from_query_result(
                            db_select("property_scheme_a")->execute(),
                            [ "name" => "property[scheme]",
                              "default-value" => $controller->property->scheme ? $controller->property->scheme : "",
                              "attributes" => ["id" => "scheme"]
                            ]
                        )
                    ?>
                </div>
                <div class="col-12">
                    <label for="landlord">Landlord</label>
                    <?php 
                        echo prepare_select_box_from_query_result(
                            db_select(USERS)->execute(),
                            [ "name" => "property[landlord]",
                              "default-value" => $controller->property->landlord ? $controller->property->landlord : "",
                              "attributes" => ["id" => "landlord",
                                    "data-reference-table" => USERS, 
                                    "data-reference-column" => "USERNAME"
                                ],
                              "classes" => ["autocomplete"]  
                            ]
                        )
                    ?>
                </div>
                <div class="col-12">
                    <?php if(!$controller->property){ ?>
                        <input type="submit" name="add" value="<?php echo _t(14); ?>" class="btn btn-info form-control"/>
                    <?php }else{ ?>
                        <input type="submit" name="update" value="<?php echo _t(85); ?>" class="btn btn-warning form-control"/>
                    <?php } ?>
                </div>
                <input type="text" class="hidden" name="form_build_id" value="<?php echo $controller->form_build_id; ?>"/>
            </form>
        </div>
    </div>

<?php }