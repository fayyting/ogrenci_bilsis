<?php
function echo_complaints_page(ComplaintsController $controller){ 
        $report = $controller->complaint_report;
        ?>
        <h1><?php echo _t(265); ?></h1>
        <form method='POST' id="complaint_edit_form" class="text-left">
            <?php $controller->printMessages(); ?>
            <div class="row">
                <div class="col-sm-4">
                    <label for="date_received"><?php echo _t(318); ?>:</label>
                    <input type="text" id="date_received" class="form-control datetimeinput" name="complaint[date_received]"
                    value="<?php echo $report->date_received; ?>"/>
                </div>
                <div class="col-sm-3">
                    <label><?php echo _t(319); ?>:</label>
                    <input type="text" id="reported_via" class="form-control" name="complaint[reported_via]"
                    value="<?php echo $report->reported_via; ?>"/>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <label><?php echo _t(320); ?>:</label>
                    <textarea name="complaint[description]" class="form-control"><?php echo $report->description; ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <label for="immadiate_or_foreseable_risks"><?php echo _t(321); ?>:</label>
                    <input type="checkbox" id="immadiate_or_foreseable_risks" class="yes_no_box" name="complaint[immadiate_or_foreseable_risks]"
                    <?php echo $report->immadiate_or_foreseable_risks ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-4">
                    <label for="risks_of_breakdown_in_meeting_expectations"><?php echo _t(322); ?>:</label>
                    <input type="checkbox" id="risks_of_breakdown_in_meeting_expectations" class="yes_no_box" name="complaint[risks_of_breakdown_in_meeting_expectations]"
                    <?php echo $report->risks_of_breakdown_in_meeting_expectations ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-4">
                    <label for="risks_to_any_persons_wellbeing"><?php echo _t(323); ?>:</label>
                    <input type="checkbox" id="risks_to_any_persons_wellbeing" class="yes_no_box" name="complaint[risks_to_any_persons_wellbeing]"
                    <?php echo $report->risks_to_any_persons_wellbeing ? "checked" : ""; ?>/>
                </div> <br clear="all">
                <div class="col-sm-4">
                    <label for="risk_of_legislative_non_compliance"><?php echo _t(324); ?>:</label>
                    <input type="checkbox" id="risk_of_legislative_non_compliance" class="yes_no_box" name="complaint[risk_of_legislative_non_compliance]"
                    <?php echo $report->risk_of_legislative_non_compliance ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-4">
                    <label for="risk_to_any_property"><?php echo _t(325); ?>:</label>
                    <input type="checkbox" id="risk_to_any_property" class="yes_no_box" name="complaint[risk_to_any_property]"
                    <?php echo $report->risk_to_any_property ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-4">
                    <label for="pass_info_third_party"><?php echo _t(326); ?>:</label>
                    <input type="checkbox" id="pass_info_third_party" class="yes_no_box" name="complaint[pass_info_third_party]"
                    <?php echo $report->pass_info_third_party ? "checked" : ""; ?>/>
                </div> <br clear="all">
                <div class="col-sm-4">
                    <label for="risk_of_enviromental_impact"><?php echo _t(327); ?>:</label>
                    <input type="checkbox" id="risk_of_enviromental_impact" class="yes_no_box" name="complaint[risk_of_enviromental_impact]"
                    <?php echo $report->risk_of_enviromental_impact ? "checked" : ""; ?>/>
                </div>
                <div class="col-sm-4">
                    <label for="intervention_of_thirty_party_required"><?php echo _t(328); ?>:</label>
                    <input type="checkbox" id="intervention_of_thirty_party_required" class="yes_no_box" name="complaint[intervention_of_thirty_party_required]"
                    <?php echo $report->intervention_of_thirty_party_required ? "checked" : ""; ?>/>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <label><?php echo _t(329); ?>:</label>
                    <textarea name="complaint[factors_to_complaint]" class="form-control"><?php echo $report->factors_to_complaint; ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <label><?php echo _t(330); ?>:</label>
                    <textarea name="complaint[immadiate_action_has_been_taken]" class="form-control"><?php echo $report->immadiate_action_has_been_taken; ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <label for="complaint_been_resolved"><?php echo _t(331); ?></label>
                    <input type="checkbox" id="complaint_been_resolved" name="complaint[complaint_been_resolved]" class="yes_no_box"
                    <?php echo $report->complaint_been_resolved ? "checked" : ""; ?>/>
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