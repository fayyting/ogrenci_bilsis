<?php

use ___PHPSTORM_HELPERS\object;

function echo_properties_edit_page(EditPropertiesController $controller){ ?>

    <div class="container container-fluid content">
        <div class="row">
            <form method='POST' id="edit_form" enctype="multipart/form-data">
                <?php $controller->printMessages(); ?>
                <div class="col-sm-12 back_link">
                    <a href="<?php echo BASE_URL."/properties"; ?>" class="btn btn-info">
                    <span class="glyphicon glyphicon-menu-left"></span> <?php echo _t(134);?>
                    </a>
                </div>
                <?php if($controller->property->created_date){ ?>
                <div class="col-sm-12">
                    <label><?php echo _t(48);?>:</label>
                    <div id="property[created_date]"><?php echo $controller->property->created_date; ?></div>
                </div>
                <?php } ?>
                <div class="col-sm*12">
                    <div class="col-sm-3">
                    <label for="category"><?php echo _t(227); ?>:</label>
                        <?php 
                            echo prepare_select_box(
                                $controller->property_categories,
                                [ "name" => "property[category]",
                                "default_value" => $controller->property->category,
                                "attributes" => ["id" => "category"]
                                ]
                            )
                        ?>
                    </div>
                </div>
                <div class="col-sm-12 section">
                    <div class="col-sm-12">
                        <h3><?php echo _t(245); ?></h3>
                    </div>
                    <div class="col-sm-6">
                        <label for="adress"><?php echo _t(119); ?>:</label>
                        <textarea id="adress" name="property[adress]" class="form-control"><?php echo $controller->property->adress ? $controller->property->adress : ""; ?></textarea>
                    </div>
                    <div class="col-sm-3">
                        <label for="postcode"><?php echo _t(133); ?>:</label>
                        <input type="text" id="postcode" name="property[postcode]" class="form-control uppercase_filter" value="<?php echo $controller->property->postcode ? $controller->property->postcode : ""; ?>"/>
                    </div>
                </div>

                <div class="col-sm-12 section">
                    <div class="col-sm-12">
                        <h3><?php echo _t(246); ?></h3>
                    </div>
                    <div class="col-sm-3">
                        <label for="landlord">Landlord:</label>
                        <?php 
                            echo prepare_select_box_from_query_result(
                                db_select(USERS)->condition("USERNAME != :guest", [":guest" => "guest"])->execute(),
                                [ "name" => "property[landlord]",
                                "default_value" => $controller->property->landlord ? $controller->property->landlord : "",
                                "attributes" => ["id" => "landlord",
                                        "data-reference-table" => USERS, 
                                        "data-reference-column" => "USERNAME"
                                    ],
                                "classes" => ["autocomplete"],
                                "null_element" => _t(225)  
                                ]
                            )
                        ?>
                    </div>
                    <?php 
                    if($_POST["landlord"] && is_array($_POST["landlord"])){
                        foreach($_POST["landlord"] as &$field){
                            $field = htmlspecialchars($field);
                        }
                    }
                    $landlord = !$_POST["landlord"] && $controller->property->landlord ? User::getUserById(intval($controller->property->landlord)) : (object)$_POST["landlord"]; ?>
                    <div class="col-sm-3">
                        <label for="landlord_name"><?php echo _t(27); ?>:</label>
                        <input type="text" class="form-control" id="landlord_name" name="landlord[NAME]"
                        value="<?php echo $landlord->NAME; ?>" />
                    </div>
                    <div class="col-sm-3">
                        <label for="landlord_surname"><?php echo _t(28); ?>:</label>
                        <input type="text" class="form-control" id="landlord_surname" name="landlord[SURNAME]" 
                        value="<?php echo $landlord->SURNAME; ?>"/>
                    </div>
                    <div class="col-sm-3">
                        <label for="landlord_email"><?php echo _t(35); ?>:</label>
                        <input type="email" class="form-control" id="landlord_email" name="landlord[EMAIL]"
                        value="<?php echo $landlord->EMAIL; ?>"/>
                    </div>
                    <div class="col-sm-6">
                        <label for="landlord_address"><?php echo _t(119); ?>:</label>
                        <textarea id="landlord_address" name="landlord[address]" class="form-control"><?php echo $landlord->address; ?></textarea>
                    </div>
                    <div class="col-sm-3">
                        <label for="landlord_postcode"><?php echo _t(133); ?>:</label>
                        <input type="text" id="landlord_postcode" name="landlord[postcode]" class="form-control uppercase_filter" value="<?php echo $landlord->postcode; ?>"/>
                    </div>
                    <div class="col-sm-3">
                        <label for="landlord_phone"><?php echo _t(29); ?>:</label>
                        <input type="tel" class="form-control" id="landlord_phone" name="landlord[PHONE]" placeholder="05xxxxxxxxx" 
                        value="<?php echo $landlord->PHONE; ?>"/>
                    </div>
                </div>
                <div class="col-sm-12">
                    <label for="comment"><?php echo _t(204); ?>:</label>
                    <textarea id="comment" name="property[comment]" class="form-control"><?php echo $controller->property->comment; ?></textarea>
                </div>
                <div class="col-sm-12 section">
                    <div class="col-sm-12">
                        <h3><?php echo _t(247); ?></h3>
                    </div>
                    <div class="col-sm-3">
                        <label for="rent"><?php echo _t(139); ?>:</label>
                        <input type="number" step="0.01" id="rent" name="property[rent]" class="form-control" placeholder="<?php echo _t(139); ?>" 
                        value="<?php echo $controller->property->rent !== null ? $controller->property->rent : ""; ?>"/>
                    </div>
                    <div class="col-sm-3">
                        <label for="management_fee"><?php echo _t(228); ?>:</label>
                        <input type="button" class="btn btn-default" value="<?php echo $controller->property->management_fee_type ? : "%"; ?>" id="management_fee_type"/>
                        <input type="hidden" value="<?php echo $controller->property->management_fee_type ? : "%"; ?>" name="property[management_fee_type]" id="management_fee_type_value"/>
                        <input type="number" step="0.01" id="management_fee" name="property[management_fee]" class="form-control" placeholder="<?php echo _t(228); ?>" 
                        value="<?php echo $controller->property->management_fee; ?>"/>
                    </div>
                    <div class="col-sm-3">
                        <label for="interval"><?php echo _t(140); ?>:</label>
                        <?php
                        echo prepare_select_box($controller->intervals,
                        [ "name" => "property[interval]",
                        "default_value" => $controller->property->interval ? $controller->property->interval : "month",
                        "attributes" => ["id" => "interval"]
                    ]);
                    ?>
                    </div>
                    <div class="col-sm-3">
                        <label for="payment_type"><?php echo _t(195); ?>:</label>
                        <?php
                        echo prepare_select_box($controller->payment_types,
                            [ "name" => "property[payment_type]",
                            "default_value" => $controller->property->payment_type ? $controller->property->payment_type : "",
                            "attributes" => ["id" => "payment_type"],
                            "null_element" => _t(137)
                        ]);
                        ?>
                    </div>
                    <div class="col-sm-3">
                        <label for="bedrooms"><?php echo _t(120); ?>:</label>
                        <input type="number" id="bedrooms" name="property[bedrooms]" class="form-control" 
                        placeholder="<?php echo _t(120); ?>"
                        value="<?php echo $controller->property->bedrooms !== null ? $controller->property->bedrooms : ""; ?>" />
                    </div>
                    <div class="col-sm-3">
                        <label for="type"><?php echo _t(116); ?>:</label>
                        <?php 
                            echo prepare_select_box_from_query_result(
                                db_select("property_type_a","pta")->select("pta", ["ID", "explain"])->execute(),
                                [ "name" => "property[type]",
                                "default_value" => $controller->property->type ? $controller->property->type : "",
                                "attributes" => ["id" => "type"],
                                "null_element" => _t(137)
                                ]
                            )
                        ?>
                    </div>
                    <div class="col-sm-3">
                        <label for="floor"><?php echo _t(121); ?>:</label>
                        <input type="number" id="floor" name="property[floor]" class="form-control" placeholder="<?php echo _t(121); ?>" 
                        value="<?php echo $controller->property->floor !== null ? $controller->property->floor : ""; ?>"/>
                    </div>
                    <div class="col-sm-3">
                        <label for="status"><?php echo _t(117); ?>:</label>
                        <?php 
                            echo prepare_select_box_from_query_result(
                                db_select("property_statuses", "ps")->select("ps", ["ID", "explain"])->execute(),
                                [ "name" => "property[status]",
                                "default_value" => $controller->property->status ? $controller->property->status : "",
                                "attributes" => ["id" => "status"],
                                "null_element" => _t(137)
                                ]
                            )
                        ?>
                    </div>
                    <div class="col-sm-3">
                        <label for="scheme_a"><?php echo _t(122); ?> A:</label>
                        <?php 
                            echo prepare_select_box_from_query_result(
                                db_select("property_scheme_a","psa")->select("psa", ["ID", "explain"])->execute(),
                                [ "name" => "property[scheme_a]",
                                "default_value" => $controller->property->scheme_a ? $controller->property->scheme_a : "",
                                "attributes" => ["id" => "scheme_a"],
                                "null_element" => _t(137)
                                ]
                            )
                        ?>
                    </div>
                    <div class="col-sm-3">
                        <label for="scheme_b"><?php echo _t(122); ?> B:</label>
                        <?php 
                            echo prepare_select_box_from_query_result(
                                db_select("property_scheme_b","psb")->select("psb", ["ID", "explain"])->execute(),
                                [ "name" => "property[scheme_b]",
                                "default_value" => $controller->property->scheme_b ? $controller->property->scheme_b : "",
                                "attributes" => ["id" => "scheme_b"],
                                "null_element" => _t(137)
                                ]
                            )
                        ?>
                    </div>
                </div>
                <div class="col-sm-12 section">
                    <div class="col-sm-12">
                        <h3><?php echo _t(248); ?> <img src="http://housingbritain.com/admin/assets/disabled_person.png" class="img-responsive" style="display: initial;"></h3>
                    </div>
                    <div class="col-sm-3">
                        <label for="internal_steps"><?php echo _t(144); ?></label> 
                        <input type="number" name="property[internal_steps_count]" class="form-control step_count_control <?php echo !$controller->property->internal_steps ? "hidden" : ""; ?>"
                        placeholder="<?php echo _t(241); ?>" value="<?php echo $controller->property->internal_steps_count ? : "";?>">
                        <input type="checkbox" id="internal_steps" name="property[internal_steps]" class="yes_no_box"
                        <?php echo $controller->property->internal_steps ? "checked" : ""; ?> />
                    </div>
                    <div class="col-sm-3">
                        <label for="external_steps"><?php echo _t(145); ?></label> 
                        <input type="number" name="property[external_steps_count]" class="form-control step_count_control <?php echo !$controller->property->external_steps ? "hidden" : ""; ?>" 
                        placeholder="<?php echo _t(241); ?>" value="<?php echo $controller->property->external_steps_count ? : "";?>"/>
                        <input type="checkbox" id="external_steps" name="property[external_steps]" class="yes_no_box"
                        <?php echo $controller->property->external_steps ? "checked" : ""; ?> />
                    </div>
                    <br clear="all">
                    <div class="col-sm-3">
                        <label for="lift"><?php echo _t(146); ?></label> 
                        <input type="checkbox" id="lift" name="property[lift]" class="yes_no_box"
                        <?php echo $controller->property->lift ? "checked" : ""; ?> />
                    </div>
                    <div class="col-sm-3">
                        <label for="entrance"><?php echo _t(150); ?></label> 
                        <input type="checkbox" id="entrance" name="property[entrance]" class="yes_no_box"
                        <?php echo $controller->property->entrance ? "checked" : ""; ?> />
                    </div>
                    <div class="col-sm-3">
                        <label for="toilet"><?php echo _t(223); ?></label> 
                        <input type="checkbox" id="toilet" name="property[toilet]" class="yes_no_box"
                        <?php echo $controller->property->toilet ? "checked" : ""; ?> />
                    </div>
                    <div class="col-sm-3">
                        <label for="shower_bath"><?php echo _t(151); ?></label> 
                        <input type="checkbox" id="shower_bath" name="property[shower_bath]" class="yes_no_box"
                        <?php echo $controller->property->shower_bath ? "checked" : ""; ?> />
                    </div>
                    <div class="col-sm-3">
                        <label for="kitchen"><?php echo _t(169); ?></label> 
                        <input type="checkbox" id="kitchen" name="property[kitchen]" class="yes_no_box"
                        <?php echo $controller->property->kitchen ? "checked" : ""; ?> />
                    </div>
                    <div class="col-sm-3">
                        <label for="living_area"><?php echo _t(170); ?></label> 
                        <input type="checkbox" id="living_area" name="property[living_area]" class="yes_no_box"
                        <?php echo $controller->property->living_area ? "checked" : ""; ?> />
                    </div>
                    <div class="col-sm-3">
                        <label><?php echo _t(152); ?></label>
                        <input type="text" name="property[suitable_for_disabled]" class="hidden" />
                        <input type="button" class="btn btn-default form-control" id="suitable_for_disabled" value="<?php echo $controller->suitable_for_disabled_types[$controller->property->suitable_for_disabled ? : "not_suitable"]; ?>" />
                    </div>
                    <div class="col-sm-3">
                        <label for="disabled_comment"><?php echo _t(204); ?></label>
                        <input type="text" class="form-control" name="property[disabled_comment]" id="disabled_comment" value="<?php echo $controller->property->disabled_comment; ?>" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2 col-xs-3">
                        
                    </div>
                    <div class="col-sm-2 col-xs-3 text-center">
                       <label><?php echo _t(162); ?></label>
                    </div>
                    <div class="col-sm-2 col-xs-3 text-center">
                        <label><?php echo _t(163); ?></label>
                    </div>
                    <div class="col-sm-2 col-xs-3 text-center">
                        <label><?php echo _t(164); ?></label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2 col-xs-3">
                        <label><?php echo _t(161); ?> :</label>
                    </div>
                    <div class="col-sm-2 col-xs-3">
                        <?php echo prepare_select_box($controller->electricity_meter_types, [
                            "name" => "property[electricity_meter_type]",
                            "null_element" => _t(137),
                            "default_value" => $controller->property->electricity_meter_type
                        ]); ?>
                    </div>
                    <div class="col-sm-2 col-xs-3">
                        <?php echo prepare_select_box($controller->gas_meter_types, [
                            "name" => "property[gas_meter_type]",
                            "null_element" => _t(137),
                            "default_value" => $controller->property->gas_meter_type
                        ]); ?>
                    </div>
                    <div class="col-sm-2 col-xs-3">
                        <?php echo prepare_select_box($controller->water_meter_types, [
                            "name" => "property[water_meter_type]",
                            "null_element" => _t(137),
                            "default_value" => $controller->property->water_meter_type
                        ]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2 col-xs-3">
                        <label><?php echo _t(165); ?> :</label>
                    </div>
                    <div class="col-sm-2 col-xs-3">
                        <?php echo prepare_select_box($controller->locations, [
                            "name" => "property[electricity_location]",
                            "default_value" => $controller->property->electricity_location,
                            "null_element" => _t(137)  
                        ]); ?>
                    </div>
                    <div class="col-sm-2 col-xs-3">
                        <?php echo prepare_select_box($controller->locations, [
                            "name" => "property[gas_location]",
                            "default_value" => $controller->property->gas_location,
                            "null_element" => _t(137)  
                        ]); ?>
                    </div>
                    <div class="col-sm-2 col-xs-3">
                        <?php echo prepare_select_box($controller->locations, [
                            "name" => "property[water_location]",
                            "default_value" => $controller->property->water_location,
                            "null_element" => _t(137)  
                        ]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2 col-xs-3">
                        <label><?php echo _t(149); ?> :</label>
                    </div>
                    <div class="col-sm-2 col-xs-3">
                        <input type="text" name="property[electricity_location_comment]" class="form-control" placeholder="<?php echo _t(204); ?>" 
                        value="<?php echo $controller->property->electricity_location_comment; ?>"/>
                    </div>
                    <div class="col-sm-2 col-xs-3">
                        <input type="text" name="property[gas_location_comment]" class="form-control" placeholder="<?php echo _t(204); ?>"
                        value="<?php echo $controller->property->gas_location_comment; ?>"/>
                    </div>
                    <div class="col-sm-2 col-xs-3">
                        <input type="text" name="property[water_location_comment]" class="form-control" placeholder="<?php echo _t(204); ?>"
                        value="<?php echo $controller->property->water_location_comment; ?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2 col-xs-3">
                        <label><?php echo _t(171); ?> :</label>
                        <label style="margin-top:15px;"><?php echo _t(249); ?> :</label>
                    </div>
                    <div class="col-sm-2 col-xs-3">
                        <?php echo prepare_select_box_from_query_result(
                            db_select("service_providers")
                            ->condition("provider_type = 'electricity' ")
                            ->execute(), [
                            "name" => "property[electricity_provider]",
                            "default_value" => $controller->property->electricity_provider,
                            "classes" => ["autocomplete", "create_if_not_exist"],
                            "attributes" => [
                                "data-reference-table" => "service_providers", 
                                "data-reference-column" => "provider_name",
                                "data-reference-filter-column" => "provider_type",
                                "data-reference-filter-value" => "electricity"
                            ],
                            "null_element" => _t(137)  
                        ]);
                        $electricity_provider = ServiceProvider::get(["ID" => $controller->property->electricity_provider]); 
                        ?>
                        <input type="text" name="electricity_provider[phone]" class="form-control" placeholder="05xxxxxxxxx"
                        value="<?php echo $electricity_provider->phone;?>"/>
                    </div>
                    <div class="col-sm-2 col-xs-3">
                        <?php echo prepare_select_box_from_query_result(
                            db_select("service_providers")
                            ->condition("provider_type = 'gas' ")
                            ->execute(), [
                            "name" => "property[gas_provider]",
                            "default_value" => $controller->property->gas_provider,
                            "classes" => ["autocomplete", "create_if_not_exist"],
                            "attributes" => [
                                "data-reference-table" => "service_providers", 
                                "data-reference-column" => "provider_name",
                                "data-reference-filter-column" => "provider_type",
                                "data-reference-filter-value" => "gas"
                            ],
                            "null_element" => _t(137)  
                        ]); 
                        $gas_provider = ServiceProvider::get(["ID" => $controller->property->gas_provider]); 
                        ?>
                        <input type="text" name="gas_provider[phone]" class="form-control" placeholder="05xxxxxxxxx"
                        value="<?php echo $gas_provider->phone;?>"/>
                    </div>
                    <div class="col-sm-2 col-xs-3">
                        <?php echo prepare_select_box_from_query_result(
                            db_select("service_providers")
                            ->condition(" provider_type = 'water' ")
                            ->execute(), [
                            "name" => "property[water_provider]",
                            "default_value" => $controller->property->water_provider,
                            "classes" => ["autocomplete", "create_if_not_exist"],
                            "attributes" => [
                                "data-reference-table" => "service_providers", 
                                "data-reference-column" => "provider_name",
                                "data-reference-filter-column" => "provider_type",
                                "data-reference-filter-value" => "water"
                            ],
                            "null_element" => _t(137)  
                        ]); 
                        $water_provider = ServiceProvider::get(["ID" => $controller->property->water_provider]); ?>
                        <input type="text" name="water_provider[phone]" class="form-control" placeholder="05xxxxxxxxx"
                        value="<?php echo $water_provider->phone;?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2 col-xs-3">
                        <label><?php echo _t(173); ?> :</label>
                    </div>
                    <div class="col-sm-2 col-xs-3">
                        <input type="number" step="0.01" id="electricity_reading" name="property[electricity_reading]" class="form-control" placeholder="<?php echo _t(173); ?>" 
                        value="<?php echo $controller->property->electricity_reading ? $controller->property->electricity_reading : ""; ?>"/>
                    </div>
                    <div class="col-sm-2 col-xs-3">
                        <input type="number" step="0.01" id="gas_reading" name="property[gas_reading]" class="form-control" placeholder="<?php echo _t(173); ?>" 
                        value="<?php echo $controller->property->gas_reading ? $controller->property->gas_reading : ""; ?>"/>
                    </div>
                    <div class="col-sm-2 col-xs-3">
                        <input type="number" step="0.01" id="water_reading" name="property[water_reading]" class="form-control" placeholder="<?php echo _t(173); ?>" 
                        value="<?php echo $controller->property->water_reading ? $controller->property->water_reading : ""; ?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2 col-xs-3">
                        <label><?php echo _t(147); ?> :</label>
                    </div>
                    <div class="col-sm-2 col-xs-3">
                        <input type="text" id="electricity_reading_date" name="property[electricity_reading_date]" class="form-control dateinput" placeholder="<?php echo _t(174); ?>" 
                        <?php echo $controller->property->electricity_reading_date ? "value=\"{$controller->property->electricity_reading_date}\"" : ""; ?> />
                        <input type="text" class="timeinput form-control" name="property[electricity_reading_time]" value="<?php echo $controller->property->electricity_reading_time; ?>"
                        placeholder="<?php echo _t(147); ?>"/>
                    </div>
                    <div class="col-sm-2 col-xs-3">
                        <input type="text" id="gas_reading_date" name="property[gas_reading_date]" class="form-control dateinput" placeholder="<?php echo _t(174); ?>" 
                        <?php echo $controller->property->gas_reading_date ? "value=\"{$controller->property->gas_reading_date}\"" : ""; ?> />
                        <input type="text" class="timeinput form-control" name="property[gas_reading_time]" value="<?php echo $controller->property->gas_reading_time; ?>"
                        placeholder="<?php echo _t(147); ?>"/>
                    </div>
                    <div class="col-sm-2 col-xs-3">
                        <input type="text" id="water_reading_date" name="property[water_reading_date]" class="form-control dateinput" placeholder="<?php echo _t(174); ?>" 
                        <?php echo $controller->property->water_reading_date ? "value=\"{$controller->property->water_reading_date}\"" : ""; ?> />
                        <input type="text" class="timeinput form-control" name="property[water_reading_time]" value="<?php echo $controller->property->water_reading_time; ?>"
                        placeholder="<?php echo _t(147); ?>"/>
                    </div>
                </div>
                <div class="col-sm-3">
                    <label for="heating_type"><?php echo _t(175); ?> :</label>
                    <?php echo prepare_select_box($controller->heating_types,
                    [
                        "name" => "property[heating_type]",
                        "default_value" => $controller->property->heating_type,
                        "attributes" => [
                                "id" => "heating_type"
                        ],
                        "null_element" => _t(137)  
                    ]);
                    ?>
                </div>
                <div class="col-sm-9">
                    <label for="property[heating_comment]"><?php echo _t(185); ?> :</label>
                    <input id="property[heating_comment]" type="text" name="property[heating_comment]" class="form-control"
                    value="<?php echo $controller->property->heating_comment ? $controller->property->heating_comment : ""; ?>" />
                </div>
                <div class="col-sm-3">
                    <label for="stopcock_location"><?php echo _t(186); ?> :</label>
                    <?php echo prepare_select_box($controller->locations, [
                        "name" => "property[stopcock_location]",
                        "default_value" => $controller->property->stopcock_location,
                        "attributes" => ["id" => "stopcock_location"],
                        "null_element" => _t(137)  
                    ]); ?>
                </div>
                <div class="col-sm-9">
                    <label for="property[stopcock_comment]"><?php echo _t(187); ?> :</label>
                    <input id="property[stopcock_comment]" type="text" name="property[stopcock_comment]" class="form-control"
                    value="<?php echo $controller->property->stopcock_comment ? $controller->property->stopcock_comment : ""; ?>" />
                </div>
                <div class="col-sm-12">
                    <hr>
                    <h3>Areas</h3>
                </div>
                <div class="col-12 area_table table_container">
                    <?php 
                        echo_table($controller->area_table_headers, $controller->area_table_data);
                    ?>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <button class="btn btn-success" id="add_new_area_button"><span class="glyphicon glyphicon-plus"></span> <?php echo _t(135); ?></button>
                    </div>
                </div>
                <hr>
                <div class="col-12 documents_table table_container">
                    <?php 
                        echo_table($controller->documents_table_headers, $controller->documents_table_data);
                    ?>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-2 cover_control text-center btn btn-default" title="<?php echo _t(229); ?>">
                        <label for="maintanence_notify_required"><img src="<?php echo BASE_URL."/assets/maintanence.png"; ?>" class="cover_icon"/></label>
                        <input type='checkbox' name='property[maintanence_notify_required]' class="hidden cover_option" id='maintanence_notify_required' <?php echo $controller->property->maintanence_notify_required ? "checked" : ""; ?> />
                    </div>
                    <div class="col-xs-10">
                        <input type="text" name='property[maintanence_notify_required_note]' class="form-control" value="<?php echo $controller->property->maintanence_notify_required_note; ?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-2 cover_control text-center btn btn-default" title="<?php echo _t(230); ?>">
                        <label for="general_maintanence_cover"><img src="<?php echo BASE_URL."/assets/general_maintanence.png"; ?>" class="cover_icon"/></label>
                        <input type='checkbox' name='property[general_maintanence_cover]' class="hidden cover_option" id='general_maintanence_cover' <?php echo $controller->property->general_maintanence_cover ? "checked" : ""; ?> />
                    </div>
                    <div class="col-xs-10">
                        <input type="text" name='property[general_maintanence_cover_note]' class="form-control" value="<?php echo $controller->property->general_maintanence_cover_note; ?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-2 cover_control text-center btn btn-default" title="<?php echo _t(231); ?>">
                        <label for="boiler_cover"><img src="<?php echo BASE_URL."/assets/boiler_cover.png"; ?>" class="cover_icon"/></label>
                        <input type='checkbox' name='property[boiler_cover]' class="hidden cover_option" id='boiler_cover' <?php echo $controller->property->boiler_cover ? "checked" : ""; ?> />
                    </div>
                    <div class="col-xs-10">
                        <input type="text" name='property[boiler_cover_note]' class="form-control" value="<?php echo $controller->property->boiler_cover_note; ?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-2 cover_control text-center btn btn-default" title="<?php echo _t(232); ?>">
                        <label for="plumbing_cover"><img src="<?php echo BASE_URL."/assets/plumbing_cover.png"; ?>" class="cover_icon"/></label>
                        <input type='checkbox' name='property[plumbing_cover]' class="hidden cover_option" id='plumbing_cover' <?php echo $controller->property->plumbing_cover ? "checked" : ""; ?> />
                    </div>
                    <div class="col-xs-10">
                        <input type="text" name='property[plumbing_cover_note]' class="form-control" value="<?php echo $controller->property->plumbing_cover_note; ?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-2 cover_control text-center btn btn-default" title="<?php echo _t(233); ?>">
                        <label for="drainage_cover"><img src="<?php echo BASE_URL."/assets/drainage_cover.png"; ?>" class="cover_icon"/></label>
                        <input type='checkbox' name='property[drainage_cover]' class="hidden cover_option" id='drainage_cover' <?php echo $controller->property->plumbing_cover ? "checked" : ""; ?> />
                    </div>
                    <div class="col-xs-10">
                        <input type="text" name='property[drainage_cover_note]' class="form-control" value="<?php echo $controller->property->drainage_cover_note; ?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-2 cover_control text-center btn btn-default" title="<?php echo _t(234); ?>">
                        <label for="electrical_cover"><img src="<?php echo BASE_URL."/assets/electrical_cover.png"; ?>" class="cover_icon"/></label>
                        <input type='checkbox' name='property[electrical_cover]' class="hidden cover_option" id='electrical_cover' <?php echo $controller->property->electrical_cover ? "checked" : ""; ?> />
                    </div>
                    <div class="col-xs-10">
                        <input type="text" name='property[electrical_cover_note]' class="form-control" value="<?php echo $controller->property->electrical_cover_note; ?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-2 cover_control text-center btn btn-default" title="<?php echo _t(237); ?>">
                        <label for="appliance_cover"><img src="<?php echo BASE_URL."/assets/appliance_cover.png"; ?>" class="cover_icon"/></label>
                        <input type='checkbox' name='property[appliance_cover]' class="hidden cover_option" id='appliance_cover' <?php echo $controller->property->appliance_cover ? "checked" : ""; ?> />
                    </div>
                    <div class="col-xs-10">
                        <input type="text" name='property[appliance_cover_note]' class="form-control" value="<?php echo $controller->property->appliance_cover_note; ?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-2 cover_control text-center btn btn-default" title="<?php echo _t(235); ?>">
                        <label for="miscellaneous_cover"><img src="<?php echo BASE_URL."/assets/miscellaneous.png"; ?>" class="cover_icon"/></label>
                        <input type='checkbox' name='property[miscellaneous_cover]' class="hidden cover_option" id='miscellaneous_cover' <?php echo $controller->property->miscellaneous_cover ? "checked" : ""; ?> />
                    </div>
                    <div class="col-xs-10">
                        <input type="text" name='property[miscellaneous_cover_note]' class="form-control" value="<?php echo $controller->property->miscellaneous_cover_note; ?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-2 cover_control text-center btn btn-default" title="<?php echo _t(236); ?>">
                        <label for="parking_restrictions"><img src="<?php echo BASE_URL."/assets/parking.png"; ?>" class="cover_icon"/></label>
                        <input type='checkbox' name='property[parking_restrictions]' class="hidden cover_option" id='parking_restrictions' <?php echo $controller->property->parking_restrictions ? "checked" : ""; ?> />
                    </div>
                    <div class="col-xs-10">
                        <input type="text" name='property[parking_restrictions_note]' class="form-control" value="<?php echo $controller->property->parking_restrictions_note; ?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-2 cover_control text-center btn btn-default" title="<?php echo _t(238); ?>">
                        <label for="nearest_bus_stations"><img src="<?php echo BASE_URL."/assets/bus_route.png"; ?>" class="cover_icon"/></label>
                        <input type='checkbox' name='property[nearest_bus_stations]' class="hidden cover_option" id='nearest_bus_stations' <?php echo $controller->property->nearest_bus_stations ? "checked" : ""; ?> />
                    </div>
                    <div class="col-xs-10">
                        <input type="text" name='property[nearest_bus_stations_note]' class="form-control" value="<?php echo $controller->property->nearest_bus_stations_note; ?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-2 cover_control text-center btn btn-default" title="<?php echo _t(239); ?>">
                        <label for="nearest_train_stations"><img src="<?php echo BASE_URL."/assets/train_route.png"; ?>" class="cover_icon"/></label>
                        <input type='checkbox' name='property[nearest_train_stations]' class="hidden cover_option" id='nearest_train_stations' <?php echo $controller->property->nearest_train_stations ? "checked" : ""; ?> />
                    </div>
                    <div class="col-xs-10">
                        <input type="text" name='property[nearest_train_stations_note]' class="form-control" value="<?php echo $controller->property->nearest_train_stations_note; ?>"/>
                    </div>
                </div>
                    <?php if(!$controller->property->ID){ ?>
                        <div class="submit_container">
                            <input type="submit" name="add" value="<?php echo _t(14); ?>" class="btn btn-info form-control"/>
                        </div>
                    <?php }else{ ?>
                        <div class="submit_container">
                            <input type="submit" name="update" value="<?php echo _t(85); ?>" class="btn btn-warning form-control"/>
                        </div>
                    <?php } ?>
                <input type="text" class="hidden" name="form_build_id" value="<?php echo $controller->form_build_id; ?>"/>
            </form>
        </div>
    </div>

<?php }