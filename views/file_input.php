<?php 
function get_file_input(string $file_field, string $file_name = "", $options = []) {
    $classes = isset($options["classes"]) ? "class='".implode(" ",$options["classes"])."'" : "";
    $attributes = "";
    if(isset($options["attributes"])){
        foreach($options["attributes"] as $name => $value){
            $attributes .="$name='$value' ";
        }
    }
    $button_style = isset($options["button_style"]) ? implode(" ",$options["button_style"]) : "col-sm-2 col-xs-12";
    $label = isset($options["label"]) ? $options["label"] : _t(111);
    $accept = isset($options["accept"]) ? "accept='{$options['accept']}'" : "";
    return
    "<div $classes $attributes>
        <div class='btn btn-success file-field $button_style'>
            $label
        </div>
        <input type='file' name='$file_field' style='display: none;' $accept/>
        <div class='col-sm-10 col-xs-12'>
            <input class='file-path form-control' type='text' value='$file_name' placeholder='"._t(112)."'/>
        </div>
    </div>";
}