<?php
function echo_inpection_form(InspectionController $controller){ 
        $report = $controller->inspection_report;
        ?>
        <h1><?php echo _t(261); ?></h1>
        <form method='POST' id="inspection_edit_form" class="text-left">
            <?php $controller->printMessages(); ?>
            <div class="row">
                <div class="col-sm-3">
                    <div class="input-group">
                        <label class="input-group-addon">PI:</label>
                        <input type="number" id="inspection_id" class="form-control" readonly value="<?php echo $report->ID; ?>"/>
                    </div>
                </div>
            </div>
            <hr>
            <?php 
                $controller->import_view("property_section");
                render_property_section($report->property ? Property::get(["ID" => $report->property]) : NULL, "inspection[property]");
            ?>
            <div class="row">
                <div class="col-sm-4">
                    <label for="appointment"><?php echo _t(266); ?>:</label>
                    <input type="text" id="appointment" class="form-control datetimeinput" name="inspection[appointment]"
                    value="<?php echo $report->appointment; ?>"/>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <label><?php echo _t(267); ?>:</label>
                    <?php echo prepare_select_box_from_query_result(
                            db_select(USERS,"u")
                            ->select("u", ["ID","NAME"])
                            ->condition("NAME != '' AND NAME IS NOT NULL")
                            ->limit(AUTOCOMPLETE_SELECT_BOX_LIMIT)
                            ->orderBy("ID")
                            ->execute(), [
                            "name" => "inspection[allocated]",
                            "default_value" => $report->allocated,
                            "classes" => ["autocomplete", "create_if_not_exist"],
                            "attributes" => [
                                "data-reference-table" => USERS, 
                                "data-reference-column" => "NAME"
                            ],
                            "null_element" => _t(137)  
                        ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <label><?php echo _t(268); ?>:</label>
                    <input type="text" class="form-control datetimeinput" name="inspection[arrived]"
                    value="<?php echo $report->arrived; ?>"/>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <label for="tenant_present"><?php echo _t(269); ?></label> 
                    <input type="checkbox" id="tenant_present" name="inspection[tenant_present]" class="yes_no_box"
                    <?php echo $report->tenant_present ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-3">
                    <label for="damages_or_defects"><?php echo _t(270); ?></label> 
                    <input type="checkbox" id="damages_or_defects" name="inspection[damages_or_defects]" class="yes_no_box"
                    <?php echo $report->damages_or_defects ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-3">
                    <label for="adequate_proof_of_occupation"><?php echo _t(271); ?></label> 
                    <input type="checkbox" id="adequate_proof_of_occupation" name="inspection[adequate_proof_of_occupation]" class="yes_no_box"
                    <?php echo $report->adequate_proof_of_occupation ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-3">
                    <label for="lifestyle_damp"><?php echo _t(272); ?></label> 
                    <input type="checkbox" id="lifestyle_damp" name="inspection[lifestyle_damp]" class="yes_no_box"
                    <?php echo $report->lifestyle_damp ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-3">
                    <label for="clean_and_tidy"><?php echo _t(273); ?></label> 
                    <input type="checkbox" id="clean_and_tidy" name="inspection[clean_and_tidy]" class="yes_no_box"
                    <?php echo $report->clean_and_tidy ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-3">
                    <label for="outstanding_maintanence_issues"><?php echo _t(274); ?></label> 
                    <input type="checkbox" id="outstanding_maintanence_issues" name="inspection[outstanding_maintanence_issues]" class="yes_no_box"
                    <?php echo $report->outstanding_maintanence_issues ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-3">
                    <label for="fire_alarm_tested_and_operating"><?php echo _t(275); ?></label> 
                    <input type="checkbox" id="fire_alarm_tested_and_operating" name="inspection[fire_alarm_tested_and_operating]" class="yes_no_box"
                    <?php echo $report->fire_alarm_tested_and_operating ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-3">
                    <label for="safeguarding_issues"><?php echo _t(276); ?></label> 
                    <input type="checkbox" id="safeguarding_issues" name="inspection[safeguarding_issues]" class="yes_no_box"
                    <?php echo $report->safeguarding_issues ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-3">
                    <label for="tenant_has_concerns_or_complaints"><?php echo _t(277); ?></label> 
                    <input type="checkbox" id="tenant_has_concerns_or_complaints" name="inspection[tenant_has_concerns_or_complaints]" class="yes_no_box"
                    <?php echo $report->tenant_has_concerns_or_complaints ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-3">
                    <label for="satisfied_property"><?php echo _t(278); ?></label> 
                    <input type="checkbox" id="satisfied_property" name="inspection[satisfied_property]" class="yes_no_box"
                    <?php echo $report->satisfied_property ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-3">
                    <label for="satisfied_service_delivery"><?php echo _t(279); ?></label> 
                    <input type="checkbox" id="satisfied_service_delivery" name="inspection[satisfied_service_delivery]" class="yes_no_box"
                    <?php echo $report->satisfied_service_delivery ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-3">
                    <label for="satisfied_tenant"><?php echo _t(280); ?></label> 
                    <input type="checkbox" id="satisfied_tenant" name="inspection[satisfied_tenant]" class="yes_no_box"
                    <?php echo $report->satisfied_tenant ? "checked" : ""; ?>/>
                </div>
            </div>
            <div class="row">
                <label for="tenant_comments"><?php echo _t(281); ?></label> 
                <textarea class="form-control" id="tenant_comments" name="inspection[tenant_comments]"><?php echo $report->tenant_comments; ?></textarea>
            </div>
            <div class="row">
                <label for="officer_comments"><?php echo _t(282); ?></label> 
                <textarea class="form-control" id="officer_comments" name="inspection[officer_comments]"><?php echo $report->officer_comments; ?></textarea>
            </div>
            <div class="row section">
                <h4><?php echo _t(283); ?></h4>
                <div class="col-sm-3">
                    <input type="radio" name="inspection[next_inspection]" value="routine" id="ni_routine" 
                    <?php echo $report->next_inspection == "routine" ? "checked" : ""; ?>/>
                    <label for="ni_routine"><?php echo _t(285); ?></label>
                </div>
                <div class="col-sm-3">
                    <input type="radio" name="inspection[next_inspection]" value="earlier" id="ni_earlier"
                    <?php echo $report->next_inspection == "earlier" ? "checked" : ""; ?>/>
                    <label for="ni_earlier"><?php echo _t(286); ?></label>
                </div>
                <div class="col-sm-3">
                    <label for="ni_date"><?php echo _t(174); ?></label>
                    <input type="text" name="inspection[next_inspection_date]" class="form-control dateinput" id="ni_date" value="<?php echo $report->next_inspection_date; ?>"/>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <label for="completed"><?php echo _t(287); ?></label>
                    <input type="text" name="inspection[completed]" class="form-control datetimeinput" id="completed" value="<?php echo $report->completed; ?>"/>
                </div>
                <div class="col-sm-5">
                    <label for="signed"><?php echo _t(288); ?></label>
                    <?php echo prepare_select_box_from_query_result(
                            db_select(USERS,"u")
                            ->select("u", ["ID","NAME"])
                            ->condition("NAME != '' AND NAME IS NOT NULL")
                            ->limit(AUTOCOMPLETE_SELECT_BOX_LIMIT)
                            ->orderBy("ID")
                            ->execute(), [
                            "name" => "inspection[signed]",
                            "default_value" => $report->signed,
                            "classes" => ["autocomplete", "create_if_not_exist"],
                            "attributes" => [
                                "data-reference-table" => USERS, 
                                "data-reference-column" => "NAME"
                            ],
                            "null_element" => _t(137)  
                        ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <label for="in_time"><?php echo _t(289); ?></label>
                    <input type="checkbox" id="in_time" name="inspection[in_time]" class="yes_no_box" <?php echo $report->in_time ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-3">
                    <label for="mr_required"><?php echo _t(290); ?></label>
                    <input type="checkbox" id="mr_required" name="inspection[mr_required]" class="yes_no_box" <?php echo $report->mr_required ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-3">
                    <label for="ir_required"><?php echo _t(291); ?></label>
                    <input type="checkbox" id="ir_required" name="inspection[ir_required]" class="yes_no_box" <?php echo $report->ir_required ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-3">
                    <label for="disciplinary_required"><?php echo _t(292); ?></label>
                    <input type="checkbox" id="disciplinary_required" name="inspection[disciplinary_required]" class="yes_no_box" <?php echo $report->disciplinary_required ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-3">
                    <label for="action_taken"><?php echo _t(294); ?></label>
                    <input type="checkbox" id="action_taken" name="inspection[action_taken]" class="yes_no_box" <?php echo $report->action_taken ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-3">
                    <label for="care"><?php echo _t(295); ?></label>
                    <input type="checkbox" id="care" name="inspection[care]" class="yes_no_box" <?php echo $report->care ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-3">
                    <label for="compliance"><?php echo _t(296); ?></label>
                    <input type="checkbox" id="compliance" name="inspection[compliance]" class="yes_no_box" <?php echo $report->compliance ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-3">
                    <label for="health_and_safety"><?php echo _t(297); ?></label>
                    <input type="checkbox" id="health_and_safety" name="inspection[health_and_safety]" class="yes_no_box" <?php echo $report->health_and_safety ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-3">
                    <label for="enviromental"><?php echo _t(298); ?></label>
                    <input type="checkbox" id="enviromental" name="inspection[enviromental]" class="yes_no_box" <?php echo $report->enviromental ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-3">
                    <label for="sorting"><?php echo _t(299); ?></label>
                    <input type="checkbox" id="sorting" name="inspection[sorting]" class="yes_no_box" <?php echo $report->sorting ? "checked" : ""; ?>/>
                </div>
            </div>
            <?php if(!$report->ID){ ?>
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
<?php }