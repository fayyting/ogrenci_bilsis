var object_id;
$(document).ready(function(){
    $(document).on("change", "#filter_form select", function(){ $("#filter_form").submit(); });
    $(".remove_button").click(function(e){
        e.preventDefault();
        object_id = $(e.target).attr("data-id");
        alertMessage(_t(81), _t(82),BootstrapDialog.TYPE_Danger, remove_object);
    });

    $(document).on("click",".measurementtype_picker", function(){
        if($(this).val() == "m2"){
            $(this).val("ft2");
            $(this).next().text("ft2");
        }else{
            $(this).val("m2");
            $(this).next().text("m2");
        }
    })

    $(document).on("click", ".area_photo", function(){
        var images = $(this).parents("td").find("img");
        var image_links = [];
        for(image of images){
            image_links.push($(image).attr("src"));
        }
        show_gallery(image_links, $(this).parent(".area_image").index());
    })

    $(document).on("click", ".fire_safety_item_edit", function(e){
        e.preventDefault();
        var button = $(this);
        $.ajax({
            url : root+"/ajax/fireSafetyItemSelection?type="+type,
            success: function(response){
                BootstrapDialog.show({
                    type : BootstrapDialog.TYPE_ONFO,
                    title: "",
                    message: $(response),
                    buttons: [{
                        label: _t(77),
                        cssClass: 'btn-primary',
                        action: function(dialog){
                            let dialogContent = dialog.getModalContent();
                            let chosen_items = dialogContent.find(".fire_safety_item_selection input[type='checkbox']:checked");
                            button.parent("div").html(button);
                            let values = [];
                            for(item of chosen_items){
                                console.log(button);
                                button.before($(item).prev().addClass("fire_safety_item_photo"));
                                values.push($(item).val());
                            }
                            button.parent("div").prev().val(values.join(","));
                            button.before("<br>");
                            dialog.close();
                        }
                    }]
                });
            }
        });
    })

    $("#add_new_area_button").click(function(e){
        e.preventDefault();
        BootstrapDialog.show({
            type : BootstrapDialog.TYPE_ONFO,
            title: "",
            message: function(dialog){
                let content = $(`<div class="area_easy_selection_box">
                    <button class="area_selection_button" data-type="general_living_area"><img src='`+root+`/assets/dinner.jpg'/></button>
                    <button class="area_selection_button" data-type="bathroom"><img src='`+root+`/assets/bathroom.png'/></button>
                    <button class="area_selection_button" data-type="parking"><img src='`+root+`/assets/parking.png'/></button>
                    <button class="area_selection_button" data-type="bedroom"><img src='`+root+`/assets/bedroom.jpg'/></button>
                    <button class="area_selection_button" data-type="gardens"><img src='`+root+`/assets/gardens.png'/></button>
                </div>`);
                content.find(".area_selection_button").click(function(){
                    showAreaPropertySelection($(this).attr("data-type"), $(this).html());
                    dialog.close();
                });
                return content;
            }
        });
    });

    $(document).on("click", ".remove_new_area", function(e){
        e.preventDefault();
        var remove_button = $(this);
        alertMessage(_t(81), _t(54), BootstrapDialog.TYPE_DANGER, function(){
            remove_button.parents("tr").fadeOut(1000).remove();
        })
    })

    $(document).on("click", ".remove_area", function(e){
        e.preventDefault();
        var remove_button = $(this);
        alertMessage(_t(81), _t(54), BootstrapDialog.TYPE_DANGER, function(){
            remove_button.next().val("1");
            remove_button.next().prop('checked', true);
            remove_button.parents("tr").fadeOut(1000);
            $.notify(_t(199));
        })
    })

    $(document).on("change", ".property_file_input input[type='file']", function(e){
        
        input_area = $(this).parents(".property_file_input");
        area_index = input_area.attr("data-area-index");
        file_index = parseInt(input_area.attr("data-area-file-index"));
        new_input_file_index = file_index + 1 ;
        new_input = input_area.clone();
        new_input.find("input[type='file']").attr("name", "areas["+area_index+"][photos]["+new_input_file_index+"]").val("");
        new_input.attr("data-area-file-index", new_input_file_index);
        input_area.hide();
        input_area.after(new_input);

        var file = e.target.files[0];
        var reader = new FileReader();
    
        reader.onload = function(e2) {
            new_image = $(`<div class='area_image'>
                            <img class="area_photo"/>
                            <a href='' class='remove_new_photo' data-connected-name='areas[`+area_index+`][photos][`+file_index+`]'><span class='glyphicon glyphicon-remove core-control'></span></a>
                            </div>`);
            new_image.find("img").attr('src', e2.target.result);
            input_area.parents("td").find(".area_images").append(new_image);
        };
    
        reader.readAsDataURL(file);
    });


    $(document).on("click", ".remove_photo", function(e){
        e.preventDefault();
        remove_button = $(this);
        alertMessage(_t(202), _t(54), BootstrapDialog.TYPE_DANGER, function(){
            remove_button.prev().addClass("removed");
            remove_button.after("<input type='checkbox' value='1' class='hidden' name='"+remove_button.attr("data-connected-name")+"[remove]' checked />");
            remove_button.remove();
            $.notify(_t(199));
        })
    });

    $(document).on("click", ".remove_new_photo", function(e){
        e.preventDefault();
        remove_button = $(this);
        alertMessage(_t(202), _t(54), BootstrapDialog.TYPE_DANGER, function(){
            $("input[name='"+remove_button.attr("data-connected-name")+"']").parents(".property_file_input").remove();
            remove_button.parents(".area_image").fadeOut(1000).remove();
        })
    });

    $(document).on("keyup", "#living_area_other", function(){
        $(this).next().attr("data-type", $(this).val());
        $(this).next().text($(this).val());
    });

    $(document).on("click", ".area_other_selection_button", function(){
        $(this).prev().click();
    });

    $(document).on("change", ".cover_option", function(){
        console.log(this.checked);
        if(this.checked){
            $(this).parent(".cover_control").addClass("checked");
        }else{
            $(this).parent(".cover_control").removeClass("checked");
        }
    });

    $(".cover_option:checked").parent(".cover_control").addClass("checked");
})
function remove_object(){
    var data = {
        "ID" : object_id
    };
    $.ajax({
        url: root+"/ajax/deleteProperty",
        type: "POST",
        data : data,
        error: function(){
            
        },
        success : function(response){
            $(".remove_button[data-id='"+object_id+"']").parents("tr").remove();
        }
    });
}

function updateTotal(element){
    let width = element.parents("td").find(".area_width").val();
    let length = element.parents("td").find(".area_length").val();
    element.parents("td").next().text(width*length);
}

function showAreaPropertySelection(type, type_image){
    $.ajax({
        url : root+"/ajax/areaSelectionByType?type="+type,
        success: function(response){
            dialog = {
                type : BootstrapDialog.TYPE_ONFO,
                title: "",
                message: function(dialog){
                    let content = $(response);
                    content.find(".area_selection_button").click(function(){
                        var type2 = $(this).attr("data-type");
                        var type2_visible = $(this).html();
                        let new_area_index = $(".area_table #result_table tr").length -1;
                        let new_row = `<tr>
                                        <td>
                                            <div class='area_type_selection'>`+type_image+`</div>
                                            <input type="text" name="areas[`+new_area_index+`][area_type]" class="hidden" value="`+type+`">
                                        </td>
                                        <td>
                                            <div class='area_type_selection'>`+type2_visible+`</div>
                                            <input type="text" name="areas[`+new_area_index+`][area_type_2]" class="hidden" value="`+type2+`">
                                        </td>
                                        <td>
                                            <input type="text" name="areas[`+new_area_index+`][area_comment]" class="form-control">
                                        </td>
                                        <td>
                                            <div class='area_images'></div>
                                            <div class="property_file_input" data-area-index="0" data-area-file-index="0">
                                                <div class="btn btn-success file-field col-sm-4 col-xs-12">
                                                    <span class="glyphicon glyphicon-camera"></span>
                                                </div>
                                                <input type="file" name="areas[`+new_area_index+`][photos][0]" style="display: none;">
                                                <div class="col-sm-10 col-xs-12">
                                                    <input class="file-path form-control" type="text" value="" placeholder="">
                                                </div>
                                            </div>
                                        </td>
                                        <td>Level</td>
                                        <td>
                                            <input type="number" name="areas[`+new_area_index+`][width]" class="numberpicker area_width" readonly="true" data-on-change="updateTotal">
                                            <input type="number" name="areas[`+new_area_index+`][length]" class="numberpicker area_length" readonly="true" data-on-change="updateTotal">
                                            <input type="checkbox" name="areas[`+new_area_index+`][measurement_type]" id="areas_`+new_area_index+`_measurementtype" value="m2" checked class="measurementtype_picker hidden">
                                            <label for="areas_`+new_area_index+`_measurementtype" class="btn btn-default">m2</label>
                                            </td>
                                            <td>0</td>
                                            <td>
                                                <input type='text' name='areas[`+new_area_index+`][fire_safety_items]' class='hidden' />
                                                <div>
                                                    <br>
                                                    <a href='' class='fire_safety_item_edit'>`+_t(115)+`</a>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="" class="glyphicon glyphicon-remove remove_new_area">`+_t(82)+`</a>
                                            </td>
                                        </tr>`;
                        $(".area_table #result_table tbody").append(new_row);
                        $.notify(_t(199));
                        dialog.close();
                    });
                    return content;
                }
            };
            if(type == "bathroom"){
                dialog.buttons = [{
                    label: _t(77),
                    cssClass: 'btn-primary',
                    action: function(dialog){
                        let dialogContent = dialog.getModalContent();
                        let values = [];
                        let selection_button = dialogContent.find(".area_selection_button");
                        for(radio of dialogContent.find("input[type='radio']:checked") ) { 
                             let outerHtml = $(radio).next().prop("outerHTML") ? $(radio).next().prop("outerHTML") : "";
                             selection_button.html(selection_button.html() + outerHtml);
                             values.push($(radio).val());
                        }
                        selection_button.attr("data-type", values.join(","));
                        selection_button.click();
                    }
                }];
            }
            BootstrapDialog.show(dialog);
        }
    });
}