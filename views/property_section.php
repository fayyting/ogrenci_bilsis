<?php
function render_property_section(Property $property = NULL, string $name = NULL){
    $property_scheme_a = $property->scheme_a ? DBObject::get(["ID" => $property->scheme_a], "property_scheme_a") : NULL;
    $property_scheme_b = DBObject::get(["ID" => $property->scheme_b], "property_scheme_b");

    $property_type = DBObject::get(["ID" => $property->type], "property_type_a");
    ?>
    <div class="row section property_finder_section">
        <div class="col-xs-12">
            <h4>
                <?php echo _t(245); ?>
                <label class="property_finder pull-right btn btn-default">
                    <?php echo _t(55); ?>
                    <span class="glyphicon glyphicon-search"></span>
                </label>
            </h4>            
        </div>
        <div class="col-sm-3">
            <label for="pf_id">ID:</label>
            <input type="text" class="form-control" id="pf_id" <?php echo $name ? "name='$name'" : ""; ?> readonly
            value="<?php echo $property->ID; ?>"/>
        </div>
        <div class="col-sm-3">
            <label for="pf_council"><?php echo _t(348); ?></label>
            <input type="text" class="form-control" id="pf_council" readonly
            value="<?php echo $property_scheme_a->explain; ?>"/>
        </div>
        <div class="col-xs-9">
            <label for="pf_adress"><?php echo _t(119); ?></label>
            <input type="text" class="form-control" id="pf_adress" readonly
            value="<?php echo $property->adress; ?>"/>
        </div>
        <div class="col-xs-3">
            <label for="pf_postcode"><?php echo _t(133); ?></label>
            <input type="text" class="form-control" id="pf_postcode" readonly
            value="<?php echo $property->postcode; ?>"/>
        </div>
        <div class="col-sm-3">
            <label for="pf_scheme_a"><?php echo _t(122); ?></label>
            <input type="text" class="form-control" id="pf_scheme_a" readonly
            value="<?php echo $property_scheme_a->shortcode; ?>"/>
            <input type="text" class="form-control" id="pf_scheme_b" readonly
            value="<?php echo $property_scheme_b->shortcode; ?>"/>
        </div>
        <div class="col-sm-3">
            <label for="pf_beds">Beds?</label>
        </div>
        <div class="col-sm-3">
            <label for="pf_type"><?php echo _t(116); ?></label>
            <input type="text" class="form-control" id="pf_type" readonly
            value="<?php echo $property_type->shortcode; ?>"/>
        </div>
        <div class="col-sm-3">
            <label for="pf_floor"><?php echo _t(121); ?></label>
            <input type="text" class="form-control" id="pf_floor" readonly
            value="<?php echo $property->floor; ?>"/>
        </div>
    </div>
<?php }