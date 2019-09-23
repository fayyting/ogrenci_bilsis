<?php
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
                <div class="col-sm-3">
                    <label for="rent"><?php echo _t(139); ?>:</label>
                    <input type="number" step="0.01" id="rent" name="property[rent]" class="form-control" placeholder="<?php echo _t(139); ?>" 
                    value="<?php echo $controller->property->rent !== null ? $controller->property->rent : ""; ?>"/>
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
                <br clear="all">
                <div class="col-sm-12">
                    <label for="adress"><?php echo _t(119); ?>:</label>
                    <textarea id="adress" name="property[adress]" class="form-control"><?php echo $controller->property->adress ? $controller->property->adress : ""; ?></textarea>
                </div>
                <div class="col-sm-3">
                    <label for="postcode"><?php echo _t(133); ?>:</label>
                    <input type="text" id="postcode" name="property[postcode]" class="form-control uppercase_filter" value="<?php echo $controller->property->postcode ? $controller->property->postcode : ""; ?>"/>
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
                            db_select("property_type_a")->execute(),
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
                            db_select("property_statuses")->execute(),
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
                            db_select("property_scheme_a")->execute(),
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
                            db_select("property_scheme_b")->execute(),
                            [ "name" => "property[scheme_b]",
                              "default_value" => $controller->property->scheme_b ? $controller->property->scheme_b : "",
                              "attributes" => ["id" => "scheme_b"],
                              "null_element" => _t(137)
                            ]
                        )
                    ?>
                </div>
                <div class="col-sm-3">
                    <label for="landlord">Landlord:</label>
                    <?php 
                        echo prepare_select_box_from_query_result(
                            db_select(USERS)->execute(),
                            [ "name" => "property[landlord]",
                              "default_value" => $controller->property->landlord ? $controller->property->landlord : "",
                              "attributes" => ["id" => "landlord",
                                    "data-reference-table" => USERS, 
                                    "data-reference-column" => "USERNAME"
                                ],
                              "classes" => ["autocomplete"],
                              "null_element" => _t(137)  
                            ]
                        )
                    ?>
                </div>
                <div class="col-sm-3">
                    <label for="new_letting"><?php echo _t(141); ?></label> 
                    <input type="checkbox" id="new_letting" name="property[new_letting]" class="yes_no_box"
                    <?php echo $controller->property->new_letting ? "checked" : ""; ?> />
                </div>
                <div class="col-sm-3">
                    <label for="internal_steps"><?php echo _t(144); ?></label> 
                    <input type="checkbox" id="internal_steps" name="property[internal_steps]" class="yes_no_box"
                    <?php echo $controller->property->internal_steps ? "checked" : ""; ?> />
                </div>
                <div class="col-sm-3">
                    <label for="external_steps"><?php echo _t(145); ?></label> 
                    <input type="checkbox" id="external_steps" name="property[external_steps]" class="yes_no_box"
                    <?php echo $controller->property->external_steps ? "checked" : ""; ?> />
                </div>
                <div class="col-sm-3">
                    <label for="lift"><?php echo _t(146); ?></label> 
                    <input type="checkbox" id="lift" name="property[lift]" class="yes_no_box"
                    <?php echo $controller->property->lift ? "checked" : ""; ?> />
                </div>
                <div class="col-sm-3">
                    <label for="downstairs_shower"><?php echo _t(147); ?></label> 
                    <input type="checkbox" id="downstairs_shower" name="property[downstairs_shower]" class="yes_no_box"
                    <?php echo $controller->property->downstairs_shower ? "checked" : ""; ?> />
                </div>
                <div class="col-sm-3">
                    <label for="upstairs_shower"><?php echo _t(148); ?></label> 
                    <input type="checkbox" id="upstairs_shower" name="property[upstairs_shower]" class="yes_no_box"
                    <?php echo $controller->property->upstairs_shower ? "checked" : ""; ?> />
                </div>
                <div class="col-sm-3">
                    <label for="downstairs_bathroom"><?php echo _t(149); ?></label> 
                    <input type="checkbox" id="downstairs_bathroom" name="property[downstairs_bathroom]" class="yes_no_box"
                    <?php echo $controller->property->downstairs_bathroom ? "checked" : ""; ?> />
                </div>
                <div class="col-sm-3">
                    <label for="upstairs_bathroom"><?php echo _t(150); ?></label> 
                    <input type="checkbox" id="upstairs_bathroom" name="property[upstairs_bathroom]" class="yes_no_box"
                    <?php echo $controller->property->upstairs_bathroom ? "checked" : ""; ?> />
                </div>
                <div class="col-sm-3">
                    <label for="downstairs_toilet"><?php echo _t(151); ?></label> 
                    <input type="checkbox" id="downstairs_toilet" name="property[downstairs_toilet]" class="yes_no_box"
                    <?php echo $controller->property->downstairs_toilet ? "checked" : ""; ?> />
                </div>
                <div class="col-sm-3">
                    <label for="upstairs_toilet"><?php echo _t(152); ?></label> 
                    <input type="checkbox" id="upstairs_toilet" name="property[upstairs_toilet]" class="yes_no_box"
                    <?php echo $controller->property->upstairs_toilet ? "checked" : ""; ?> />
                </div>
                <div class="col-sm-3">
                    <label for="available_for_disabled_person"><img src="<?php echo BASE_URL."/assets/disabled_person.png"; ?>" class="icon" /> </label> 
                    <input type="checkbox" id="available_for_disabled_person" name="property[available_for_disabled_person]" class="yes_no_box"
                    <?php echo $controller->property->available_for_disabled_person ? "checked" : ""; ?> />
                </div>
                <div class="col-sm-3">
                    <label for="special_facilities"><?php echo _t(153); ?></label> 
                    <input type="checkbox" id="special_facilities" name="property[special_facilities]" class="yes_no_box"
                    <?php echo $controller->property->special_facilities ? "checked" : ""; ?> />
                </div>
                <br clear="all"/>
                <div class="col-sm-3">
                    <label for="downstairs_level_access"><?php echo _t(154); ?></label> 
                    <input type="checkbox" id="downstairs_level_access" name="property[downstairs_level_access]" class="yes_no_box"
                    <?php echo $controller->property->downstairs_level_access ? "checked" : ""; ?> />
                </div>
                <div class="col-sm-3">
                    <label for="upstairs_level_access"><?php echo _t(155); ?></label> 
                    <input type="checkbox" id="upstairs_level_access" name="property[upstairs_level_access]" class="yes_no_box"
                    <?php echo $controller->property->upstairs_level_access ? "checked" : ""; ?> />
                </div>
                <div class="col-sm-3">
                    <label for="downstairs_seperate"> Downstairs <?php echo _t(156); ?></label> 
                    <input type="checkbox" id="downstairs_seperate" name="property[downstairs_seperate]" class="yes_no_box"
                    <?php echo $controller->property->downstairs_seperate ? "checked" : ""; ?> />
                </div>
                <div class="col-sm-3">
                    <label for="upstairs_seperate"> Upstairs <?php echo _t(156); ?></label> 
                    <input type="checkbox" id="upstairs_seperate" name="property[upstairs_seperate]" class="yes_no_box"
                    <?php echo $controller->property->upstairs_seperate ? "checked" : ""; ?> />
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
                        <label><?php echo _t(171); ?> :</label>
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
                        ]); ?>
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
                        ]); ?>
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
                        ]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2 col-xs-3">
                        <label><?php echo _t(172); ?> :</label>
                    </div>
                    <div class="col-sm-2 col-xs-3">
                        <input type="number" step="0.01" id="electricity_reading" name="property[electricity_reading]" class="form-control" placeholder="<?php echo _t(173); ?>" 
                        value="<?php echo $controller->property->electricity_reading ? $controller->property->electricity_reading : ""; ?>"/>
                        <input type="text" id="electricity_reading_date" name="property[electricity_reading_date]" class="form-control datetimeinput" placeholder="<?php echo _t(174); ?>" 
                        value="<?php echo $controller->property->electricity_reading_date ? $controller->property->electricity_reading_date : ""; ?>"
                        data-date-format="yyyy-mm-dd" />
                    </div>
                    <div class="col-sm-2 col-xs-3">
                        <input type="number" step="0.01" id="gas_reading" name="property[gas_reading]" class="form-control" placeholder="<?php echo _t(173); ?>" 
                        value="<?php echo $controller->property->gas_reading ? $controller->property->gas_reading : ""; ?>"/>
                        <input type="text" id="gas_reading_date" name="property[gas_reading_date]" class="form-control datetimeinput" placeholder="<?php echo _t(174); ?>" 
                        value="<?php echo $controller->property->gas_reading_date ? $controller->property->gas_reading_date : ""; ?>"
                        data-date-format="yyyy-mm-dd" />
                    </div>
                    <div class="col-sm-2 col-xs-3">
                        <input type="number" step="0.01" id="water_reading" name="property[water_reading]" class="form-control" placeholder="<?php echo _t(173); ?>" 
                        value="<?php echo $controller->property->water_reading ? $controller->property->water_reading : ""; ?>"/>
                        <input type="text" id="water_reading_date" name="property[water_reading_date]" class="form-control datetimeinput" placeholder="<?php echo _t(174); ?>" 
                        value="<?php echo $controller->property->water_reading_date ? $controller->property->water_reading_date : ""; ?>"
                        data-date-format="yyyy-mm-dd" />
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
                        <h3>Areas</h3>
                </div>
                <div class="col-12 area_table">
                    <?php 
                        echo_table($controller->area_table_headers, $controller->area_table_data);
                    ?>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <button class="btn btn-success" id="add_new_area_button"><span class="glyphicon glyphicon-plus"></span> <?php echo _t(135); ?></button>
                    </div>
                </div>
                <div class="col-12 documents_table">
                    <?php 
                        echo_table($controller->documents_table_headers, $controller->documents_table_data);
                    ?>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-2 cover_control text-center">
                        <label for="maintanence_notify_required"><img src="<?php echo BASE_URL."/assets/maintanence.png"; ?>" class="cover_icon"/></label>
                        <input type='checkbox' name='property[maintanence_notify_required]' class="hidden cover_option" id='maintanence_notify_required' <?php echo $controller->property->maintanence_notify_required ? "checked" : ""; ?> />
                    </div>
                    <div class="col-sm-10">
                        <input type="text" name='property[maintanence_notify_required_note]' class="form-control" value="<?php echo $controller->property->maintanence_notify_required_note; ?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2 cover_control text-center">
                        <label for="general_maintanence_cover"><img src="<?php echo BASE_URL."/assets/general_maintanence.png"; ?>" class="cover_icon"/></label>
                        <input type='checkbox' name='property[general_maintanence_cover]' class="hidden cover_option" id='general_maintanence_cover' <?php echo $controller->property->general_maintanence_cover ? "checked" : ""; ?> />
                    </div>
                    <div class="col-sm-10">
                        <input type="text" name='property[general_maintanence_cover_note]' class="form-control" value="<?php echo $controller->property->general_maintanence_cover_note; ?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2 cover_control text-center">
                        <label for="boiler_cover"><img src="<?php echo BASE_URL."/assets/boiler_cover.png"; ?>" class="cover_icon"/></label>
                        <input type='checkbox' name='property[boiler_cover]' class="hidden cover_option" id='boiler_cover' <?php echo $controller->property->boiler_cover ? "checked" : ""; ?> />
                    </div>
                    <div class="col-sm-10">
                        <input type="text" name='property[boiler_cover_note]' class="form-control" value="<?php echo $controller->property->boiler_cover_note; ?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2 cover_control text-center">
                        <label for="plumbing_cover"><img src="<?php echo BASE_URL."/assets/plumbing_cover.png"; ?>" class="cover_icon"/></label>
                        <input type='checkbox' name='property[plumbing_cover]' class="hidden cover_option" id='plumbing_cover' <?php echo $controller->property->plumbing_cover ? "checked" : ""; ?> />
                    </div>
                    <div class="col-sm-10">
                        <input type="text" name='property[plumbing_cover_note]' class="form-control" value="<?php echo $controller->property->plumbing_cover_note; ?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2 cover_control text-center">
                        <label for="drainage_cover"><img src="<?php echo BASE_URL."/assets/drainage_cover.png"; ?>" class="cover_icon"/></label>
                        <input type='checkbox' name='property[drainage_cover]' class="hidden cover_option" id='drainage_cover' <?php echo $controller->property->plumbing_cover ? "checked" : ""; ?> />
                    </div>
                    <div class="col-sm-10">
                        <input type="text" name='property[drainage_cover_note]' class="form-control" value="<?php echo $controller->property->drainage_cover_note; ?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2 cover_control text-center">
                        <label for="electrical_cover"><img src="<?php echo BASE_URL."/assets/electrical_cover.png"; ?>" class="cover_icon"/></label>
                        <input type='checkbox' name='property[electrical_cover]' class="hidden cover_option" id='electrical_cover' <?php echo $controller->property->electrical_cover ? "checked" : ""; ?> />
                    </div>
                    <div class="col-sm-10">
                        <input type="text" name='property[electrical_cover_note]' class="form-control" value="<?php echo $controller->property->electrical_cover_note; ?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2 cover_control text-center">
                        <label for="miscellaneous_cover"><img src="<?php echo BASE_URL."/assets/miscellaneous.png"; ?>" class="cover_icon"/></label>
                        <input type='checkbox' name='property[miscellaneous_cover]' class="hidden cover_option" id='miscellaneous_cover' <?php echo $controller->property->miscellaneous_cover ? "checked" : ""; ?> />
                    </div>
                    <div class="col-sm-10">
                        <input type="text" name='property[miscellaneous_cover_note]' class="form-control" value="<?php echo $controller->property->miscellaneous_cover_note; ?>"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2 cover_control text-center">
                        <label for="parking_restrictions"><img src="<?php echo BASE_URL."/assets/parking.png"; ?>" class="cover_icon"/></label>
                        <input type='checkbox' name='property[parking_restrictions]' class="hidden cover_option" id='parking_restrictions' <?php echo $controller->property->parking_restrictions ? "checked" : ""; ?> />
                    </div>
                    <div class="col-sm-10">
                        <input type="text" name='property[parking_restrictions_note]' class="form-control" value="<?php echo $controller->property->parking_restrictions_note; ?>"/>
                    </div>
                </div>
                    <?php if(!$controller->property){ ?>
                        <div class="col-sm-6">
                            <input type="submit" name="add" value="<?php echo _t(14); ?>" class="btn btn-info form-control"/>
                        </div>
                        <?php if(!$controller->property || $controller->property->is_view){ ?>
                            <div class="col-sm-6">
                                <input type="submit" name="add_and_publish" value="<?php echo _t(194); ?>" class="btn btn-primary form-control"/>
                            </div>
                        <?php } ?>
                    <?php }else{ ?>
                        <div class="col-sm-6">
                            <input type="submit" name="update" value="<?php echo _t(85); ?>" class="btn btn-warning form-control"/>
                        </div>
                        <?php if($controller->property->is_view){ ?>
                            <div class="col-sm-6">
                                <input type="submit" name="update_and_publish" value="<?php echo _t(194); ?>" class="btn btn-primary form-control"/>
                            </div>    
                        <?php } ?>
                    <?php } ?>
                <input type="text" class="hidden" name="form_build_id" value="<?php echo $controller->form_build_id; ?>"/>
            </form>
        </div>
    </div>

<?php }