<?php 
function get_file_input(string $file_field, string $file_name = "") {
    return
    "<div >
        <div class='btn btn-success col-sm-2 col-xs-12 file-field'>
            "._t(111)."
        </div>
        <input type='file' name='$file_field' style='display: none;'/>
        <div class='col-sm-10 col-xs-12'>
            <input class='file-path form-control' type='text' value='$file_name' placeholder='"._t(112)."'/>
        </div>
    </div>";
}