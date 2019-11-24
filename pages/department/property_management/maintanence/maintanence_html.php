<?php
function echo_maintanence_page(MaintanenceController $controller){ ?>
        <h1><?php echo _t(260); ?></h1>
        <form method='POST' id="complaint_edit_form" class="text-left">
            <?php 
                $report = $controller->maintanence_report;
                $controller->printMessages();
                $controller->import_view("property_section");
                render_property_section($report->property ? Property::get(["ID" => $report->property]) : NULL, "maintanence[property]");
            ?>
            <div class="row">
                <div class="col-sm-3">
                        <label for="status"><?php echo _t(117); ?></label>
                        <?php echo prepare_select_box(MaintanenceReport::getAvailableVariablesForStatus(),
                        [
                                "name" => "maintanence[status]",
                                "default_value" => $report->status,
                                "classes" => ["autocomplete"],
                                "attributes" => [
                                        "id" => "status"
                                ],
                                "null_element" => _t(137)  
                        ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                        <label for="reported"><?php echo _t(332); ?>:</label>
                        <input type="text" id="reported" class="form-control datetimeinput" name="maintanence[reported]"
                        value="<?php echo $report->reported; ?>"/>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                        <label for="issue_notes"><?php echo _t(333); ?>:</label>
                        <textarea id="issue_notes" class="form-control" name="maintanence[issue_notes]"><?php echo $report->issue_notes; ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                        <label for="appointment"><?php echo _t(266); ?>:</label>
                        <input type="text" id="appointment" class="form-control datetimeinput" name="maintanence[appointment]"
                        value="<?php echo $report->appointment; ?>"/>
                </div>
                <div class="col-sm-4">
                        <label for="arrive"><?php echo _t(268); ?>:</label>
                        <input type="arrive" id="arrive" class="form-control datetimeinput" name="maintanence[arrive]"
                        value="<?php echo $report->arrive; ?>"/>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                        <label for="notes"><?php echo _t(334); ?>:</label>
                        <textarea id="notes" class="form-control" name="maintanence[notes]"><?php echo $report->notes; ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                        <label for="expenses"><?php echo _t(335); ?>:</label>
                        <textarea id="expenses" class="form-control" name="maintanence[expenses]"><?php echo $report->expenses; ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                        <label for="completed"><?php echo _t(287); ?>:</label>
                        <input type="text" id="completed" class="form-control datetimeinput" name="maintanence[completed]"
                        value="<?php echo $report->completed; ?>"/>
                </div>
                <div class="col-sm-3">
                    <label for="signed"><?php echo _t(288); ?>:</label>
                    <?php echo prepare_select_box_from_query_result(
                            db_select(USERS,"u")
                            ->select("u", ["ID","NAME"])
                            ->condition("NAME != '' AND NAME IS NOT NULL")
                            ->limit(AUTOCOMPLETE_SELECT_BOX_LIMIT)
                            ->orderBy("ID")
                            ->execute(), [
                            "name" => "maintanence[signed]",
                            "default_value" => $report->signed,
                            "classes" => ["autocomplete", "create_if_not_exist"],
                            "attributes" => [
                                "id" => "signed",
                                "data-reference-table" => USERS, 
                                "data-reference-column" => "NAME"
                            ],
                            "null_element" => _t(137)  
                        ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <label for="completed_in_time"><?php echo _t(289); ?></label>
                    <input type="checkbox" id="completed_in_time" name="maintanence[completed_in_time]" class="yes_no_box"
                    <?php echo $report->completed_in_time ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-4">
                    <label for="lonlord_expense"><?php echo _t(336); ?></label>
                    <input type="checkbox" id="lonlord_expense" name="maintanence[lonlord_expense]" class="yes_no_box"
                    <?php echo $report->lonlord_expense ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-4">
                    <label for="tenant_expense"><?php echo _t(337); ?></label>
                    <input type="checkbox" id="tenant_expense" name="maintanence[tenant_expense]" class="yes_no_box"
                    <?php echo $report->tenant_expense ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-4">
                    <label for="third_party_expense"><?php echo _t(338); ?></label>
                    <input type="checkbox" id="third_party_expense" name="maintanence[third_party_expense]" class="yes_no_box"
                    <?php echo $report->third_party_expense ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-4">
                    <label for="expenses_added"><?php echo _t(339); ?></label>
                    <input type="checkbox" id="expenses_added" name="maintanence[expenses_added]" class="yes_no_box"
                    <?php echo $report->expenses_added ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-4">
                    <label for="care"><?php echo _t(295); ?></label>
                    <input type="checkbox" id="care" name="maintanence[care]" class="yes_no_box"
                    <?php echo $report->care ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-4">
                    <label for="compliance"><?php echo _t(296); ?></label>
                    <input type="checkbox" id="compliance" name="maintanence[compliance]" class="yes_no_box"
                    <?php echo $report->compliance ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-4">
                    <label for="health_safety"><?php echo _t(297); ?></label>
                    <input type="checkbox" id="health_safety" name="maintanence[health_safety]" class="yes_no_box"
                    <?php echo $report->health_safety ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-4">
                    <label for="enviromental"><?php echo _t(298); ?></label>
                    <input type="checkbox" id="enviromental" name="maintanence[enviromental]" class="yes_no_box"
                    <?php echo $report->enviromental ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-4">
                    <label for="sorting"><?php echo _t(299); ?></label>
                    <input type="checkbox" id="sorting" name="maintanence[sorting]" class="yes_no_box"
                    <?php echo $report->sorting ? "checked" : ""; ?>/>
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