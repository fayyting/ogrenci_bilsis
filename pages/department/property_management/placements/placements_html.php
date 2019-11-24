<?php
function echo_placements_page(PlacementsController $controller){ ?>
        <h1><?php echo _t(259); ?></h1>
        <form method='POST' id="placement_edit_form" class="text-left">
            <?php $controller->printMessages(); ?>
            <?php 
                $controller->import_view("property_section");
                $report = $controller->placement_report;
                render_property_section($report->property ? Property::get(["ID" => $report->property]) : NULL, "placement[property]");
            ?>
            <div class="row">
                <div class="col-sm-4">
                    <label for="placement_date"><?php echo _t(300); ?>:</label>
                    <input type="text" id="placement_date" class="form-control datetimeinput" name="placement[placement_date]"
                    value="<?php echo $report->placement_date; ?>"/>
                </div>
                <div class="col-sm-3">
                    <label><?php echo _t(267); ?>:</label>
                    <?php echo prepare_select_box_from_query_result(
                            db_select(USERS,"u")
                            ->select("u", ["ID","NAME"])
                            ->condition("NAME != '' AND NAME IS NOT NULL")
                            ->limit(AUTOCOMPLETE_SELECT_BOX_LIMIT)
                            ->orderBy("ID")
                            ->execute(), [
                            "name" => "placement[allocated]",
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
                    <label for="keys_ready"><?php echo _t(302); ?></label>
                    <input type="checkbox" id="keys_ready" name="placement[keys_ready]" class="yes_no_box"
                    <?php echo $report->keys_ready ? "checked" : "";?>/>
                </div>
                <div class="col-sm-3">
                    <label for="welcome_pack_ready"><?php echo _t(303); ?></label> 
                    <input type="checkbox" id="welcome_pack_ready" name="placement[welcome_pack_ready]" class="yes_no_box"
                    <?php echo $report->welcome_pack_ready ? "checked" : "";?>/>
                </div>
                <div class="col-sm-3">
                    <label for="client_contracted"><?php echo _t(304); ?></label> 
                    <input type="checkbox" id="client_contracted" name="placement[client_contracted]" class="yes_no_box"
                    <?php echo $report->client_contracted ? "checked" : "";?>/>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <label for="prior_notes"><?php echo _t(301); ?></label> 
                    <textarea id="prior_notes" class="form-control" name="placement[prior_notes]"><?php echo $report->prior_notes; ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <label for="welcome_pack_received"><?php echo _t(305); ?>:</label>
                    <input type="checkbox" id="welcome_pack_received" class="yes_no_box" name="placement[welcome_pack_received]"
                    <?php echo $report->welcome_pack_received ? "checked" : "";?>/>
                </div>
                <div class="col-sm-4">
                    <label for="pip_confirmation_signed"><?php echo _t(306); ?>:</label>
                    <input type="checkbox" id="pip_confirmation_signed" class="yes_no_box" name="placement[pip_confirmation_signed]"
                    <?php echo $report->pip_confirmation_signed ? "checked" : "";?>/>
                </div>
                <div class="col-sm-4">
                    <label for="guidelines_understood"><?php echo _t(307); ?>:</label>
                    <input type="checkbox" id="guidelines_understood" class="yes_no_box" name="placement[guidelines_understood]"
                    <?php echo $report->guidelines_understood ? "checked" : "";?>/>
                </div> <br clear="all">
                <div class="col-sm-4">
                    <label for="questionnaire_completed"><?php echo _t(308); ?>:</label>
                    <input type="checkbox" id="questionnaire_completed" class="yes_no_box" name="placement[questionnaire_completed]"
                    <?php echo $report->questionnaire_completed ? "checked" : "";?>/>
                </div>
                <div class="col-sm-4">
                    <label for="stopcock_info_understood"><?php echo _t(309); ?>:</label>
                    <input type="checkbox" id="stopcock_info_understood" class="yes_no_box" name="placement[stopcock_info_understood]"
                    <?php echo $report->stopcock_info_understood ? "checked" : "";?>/>
                </div>
                <div class="col-sm-4">
                    <label for="all_keys_received"><?php echo _t(310); ?>:</label>
                    <input type="checkbox" id="all_keys_received" class="yes_no_box" name="placement[all_keys_received]"
                    <?php echo $report->all_keys_received ? "checked" : "";?>/>
                </div> <br clear="all">
                <div class="col-sm-4">
                    <label for="property_accepted"><?php echo _t(311); ?>:</label>
                    <input type="checkbox" id="property_accepted" class="yes_no_box" name="placement[property_accepted]"
                    <?php echo $report->property_accepted ? "checked" : "";?>/>
                </div>
                <div class="col-sm-8">
                    <textarea name="placement[property_not_accepted_comment]" class="form-control"><?php echo $report->property_not_accepted_comment;?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <label for="completed"><?php echo _t(287); ?>:</label>
                    <input type="text" id="completed" class="form-control datetimeinput" name="placement[completed]"
                    value="<?php echo $report->completed;?>"/>
                </div>
                <div class="col-sm-3">
                    <label for="completed_in_time"><?php echo _t(289); ?></label>
                    <input type="checkbox" id="completed_in_time" name="placement[completed_in_time]" class="yes_no_box"
                    <?php echo $report->completed_in_time ? "checked" : "";?>/>
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
                            "name" => "placement[signed]",
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
                <div class="col-sm-4">
                    <label for="utilities_and_ctax_updated"><?php echo _t(312); ?></label>
                    <input type="checkbox" id="utilities_and_ctax_updated" name="placement[utilities_and_ctax_updated]" class="yes_no_box"
                    <?php echo $report->utilities_and_ctax_updated ? "checked" : "";?>/>
                </div>
                <div class="col-sm-4">
                    <label for="updated_voids"><?php echo _t(313); ?></label>
                    <input type="checkbox" id="updated_voids" name="placement[updated_voids]" class="yes_no_box"
                    <?php echo $report->updated_voids ? "checked" : "";?>/>
                </div>
                <div class="col-sm-4">
                    <label for="updated_tenant_history"><?php echo _t(314); ?></label>
                    <input type="checkbox" id="updated_tenant_history" name="placement[updated_tenant_history]" class="yes_no_box"
                    <?php echo $report->updated_tenant_history ? "checked" : "";?>/>
                </div>
                <div class="col-sm-4">
                    <label for="updated_data_registry"><?php echo _t(315); ?></label>
                    <input type="checkbox" id="updated_data_registry" name="placement[updated_data_registry]" class="yes_no_box"
                    <?php echo $report->updated_data_registry ? "checked" : "";?>/>
                </div>
                <div class="col-sm-4">
                    <label for="updated_property_details"><?php echo _t(316); ?></label>
                    <input type="checkbox" id="updated_property_details" name="placement[updated_property_details]" class="yes_no_box"
                    <?php echo $report->updated_property_details ? "checked" : "";?>/>
                </div>
                <div class="col-sm-4">
                    <label for="paperwork_filed_in_property"><?php echo _t(317); ?></label>
                    <input type="checkbox" id="paperwork_filed_in_property" name="placement[paperwork_filed_in_property]" class="yes_no_box"
                    <?php echo $report->paperwork_filed_in_property ? "checked" : "";?>/>
                </div>
            </div>
            <?php if(!$controller->placement_report->ID){ ?>
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