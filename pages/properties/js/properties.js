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
        var fire_safety_items = $(this).parents("td").find("input").val();
        $.ajax({
            url : root+"/ajax/fireSafetyItemSelection",
            method: "post",
            data: {
                fire_safety_items : fire_safety_items
            },
            success: function(response){
                BootstrapDialog.show({
                    type : BootstrapDialog.TYPE_INFO,
                    title: "",
                    message: $(response),
                    buttons: [{
                        label: _t(77),
                        cssClass: 'btn-primary',
                        action: function(dialog){
                            let dialogContent = dialog.getModalContent();
                            let chosen_items = dialogContent.find(".fire_safety_item_selection input[type='number']");
                            button.parent("div").html(button);
                            let values = [];
                            for(item of chosen_items){
                                data = new Object();
                                data.ID = $(item).attr("data-item");
                                data.count = $(item).val();
                                values.push(data);
                            }
                            button.parent("div").prev().val(JSON.stringify(values));
                            dialog.close();
                            $.notify(_t(199));
                        }
                    }]
                });
            }
        });
    });

    $(document).on("click",".fire_safety_item_selection",function(e){
        e.preventDefault();
        let input = $(this).find("input[type='number']");
        input.val(parseInt(input.val())+1);
    });

    $(document).on("click",".facilities_edit", function(e){
        e.preventDefault();
        var edit_button = $(this);
        $.ajax({
            url : root+"/ajax/editFacilities",
            method: "post",
            data: {
                facilities: edit_button.parents("td").find("input").val()
            },
            success: function(response){
                BootstrapDialog.show({
                    type : BootstrapDialog.TYPE_INFO,
                    title: "",
                    message: $(response),
                    onshow: function(dialog){
                        let dialogContent = dialog.getModalContent();
                        dialogContent.find("select").selectpicker();
                    },
                    buttons: [{
                        label: _t(37),
                        cssClass: 'btn-primary',
                        action: function(dialog){
                            let dialogContent = dialog.getModalContent();
                            let facilities = [];
                            for(facility_selection of dialogContent.find("select")){
                                facilities.push(facility_selection.value);
                            }
                            edit_button.parent().prev().val(facilities.join(";"));
                            dialog.close();
                            $.notify(_t(199));
                        }
                    }]
                });
            }
        });
    });

    $(document).on("click", ".remove_facility", function(e){
        e.preventDefault();
        $(this).parent().remove();
    })

    $(document).on("click",".add_new_area_facility",function(){
        var button = $(this);
        $.ajax({
            url : root+"/ajax/getNewFacilityInput",
            success: function(response){
                var new_input = $(response);
                if(button.parent().prev().hasClass("alert")){
                    button.parent().prev().remove();
                }
                button.parent().before(new_input);
                setTimeout( function(){
                    new_input.find("select").selectpicker();
                }, 100);
            }
        });
    });

    $("#add_new_area_button").click(function(e){
        e.preventDefault();
        $.ajax({
            url : root+"/ajax/areaSelection",
            success: function(response){
                dialog = {
                    type : BootstrapDialog.TYPE_INFO,
                    title: "",
                    message: function(dialog){
                        let content = $(response);
                        content.find(".area_selection_button").click(function(){
                            let type = $(this).attr("data-type");
                            $.ajax({
                                url: root + "/ajax/getNewAreaRow",
                                method: "post",
                                data: {
                                    type : type,
                                    index : $(".area_table tbody tr").length,
                                    order: $(".area_type_value[value='"+type+"']").length +1
                                },
                                success: function(response){
                                    $(".area_table tbody").append(response);
                                    $.notify(_t(199));
                                }
                            });
                            dialog.close();
                        });
                        return content;
                    }
                };
                BootstrapDialog.show(dialog);
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

    if( $("#landlord").val() != "NULL"){
        $("#landlord_info input").prop("disabled", true);
        $("#landlord_info textarea").prop("disabled", true);
    }
    $("#landlord").change(function(){
        user_id = $(this).val();
        if(user_id == "NULL"){
            $("#landlord_info input").prop("disabled", false);
            $("#landlord_info textarea").prop("disabled", false);
            $("#landlord_info input").val("");
            $("#landlord_info textarea").val("");
            return;
        }
        $.ajax({
            url : root + "/ajax/getUserInfoForLandlordSelection",
            method: "post",
            data: {user_id : user_id},
            dataType: "json",
            success :function(response){
                $("#landlord_name").val(response.NAME);
                $("#landlord_surname").val(response.SURNAME);
                $("#landlord_phone").val(response.PHONE);
                $("#landlord_email").val(response.EMAIL);
                $("#landlord_address").val(response.address);
                $("#landlord_postcode").val(response.postcode);
                $("#landlord_info input").prop("disabled", true);
                $("#landlord_info textarea").prop("disabled", true);
            }
        })
    });

    $("#management_fee_type").click(function(){
        if($(this).val() == "%"){
            $(this).val("£");
            $("#management_fee_type_value").val("£");
        }else{
            $(this).val("%");
            $("#management_fee_type_value").val("%");
        }
    });

    $(".documents_table tr").each(function(i, el){
        let inputs = $(el).find(".yes_no_box_info + input");
        if( $(inputs[0]).attr("disabled") && !$(inputs[1]).attr("disabled")){
            $(el).addClass("not_received");
        }
    });
    $(".documents_table tr .yes_no_box").click(function(){
        let inputs = $(this).parents("tr").find(".yes_no_box_info + input");
        if( $(inputs[0]).attr("disabled") && !$(inputs[1]).attr("disabled")){
            $(this).parents("tr").addClass("not_received");
        }else{
            $(this).parents("tr").removeClass("not_received");
        }
    });

    $(".create_if_not_exist").change(function(){
        let value =$(this).val();
        var phone_input = $(this).parents(".bootstrap-select").next();
        if(isNaN(value)){
            phone_input.val("");
            comment_input.val("");
        }else{
            $.ajax({
                url: root+"/ajax/getServiceProviderInfo",
                method: "post",
                dataType: "json",
                data: {service_provider_id : value},
                success: function(response){
                    phone_input.val(response.phone);
                }
            })
        }
    });

    $(".document_comment").click(function(){
        let document_id = $(this).data("document-id");
        $.ajax({
            url: root +"/ajax/getDocumentComment",
            method: "post",
            dataType: "json",
            data: {document_id : document_id},
            success: function(response){
                var text_area = $(`<textarea class="form-control">`+response.document_comment+`</textarea>`);
                alertMessage(
                    text_area,
                    _t(115),
                    BootstrapDialog.TYPE_INFO,
                    function(dialog){
                        $.ajax({
                            url: root +"/ajax/getDocumentComment",
                            method: "post",
                            dataType: "json",
                            data: {document_id : document_id, document_comment: text_area.val()}
                        });
                    }
                )
            }
        });
    });

    $(".step_count_control + .yes_no_box_div input").click(function(){
        if(this.checked){
            $(this).parents(".yes_no_box_div").prev().removeClass("hidden");
        }else{
            $(this).parents(".yes_no_box_div").prev().addClass("hidden");
        }
    });

    $(document).on("change",".document-file-input input[type='file']",function(e){
        let new_input = $(this).parents(".document-file-input").clone();
        let new_docoment_index = new_input.attr("data-document-index");
        let new_file_index = parseInt(new_input.attr("data-file-index"))+1;
        new_input.attr("data-file-index", new_file_index);
        new_input.find("input[type='file']").attr("name", "documents["+new_docoment_index+"][files]["+(new_file_index-1)+"]");
        $(this).parents("td").append(new_input);
        
        let file_name = e.target.files[0].name;
        let new_file_output = "<div class='dbl_click_file'><a>"+file_name+"</a>"+
        "<a href='' class='remove_new_document' data-connected-name='documents["+new_docoment_index+"][files]["+new_file_index+"]'><span class='glyphicon glyphicon-remove core-control'></span></a>"+
                        "</div></div>";
        $(this).parents(".document-file-input").hide().before(new_file_output);
    });
    $(document).on("click", ".remove_new_document",function(e){
        e.preventDefault();
        remove_button = $(this);
        alertMessage(_t(242), _t(54), BootstrapDialog.TYPE_DANGER, function(){
            $("input[name='"+remove_button.attr("data-connected-name")+"']").parents(".document-file-input").remove();
            remove_button.parents(".dbl_click_file").fadeOut(1000).remove();
        })
    });

    $(".remove_document").click(function(e){
        e.preventDefault();
        remove_button = $(this);
        alertMessage(_t(242), _t(54), BootstrapDialog.TYPE_DANGER, function(){
            remove_button.parents("td").append("<input type='checkbox' value='1' class='hidden' name='"+remove_button.attr("data-connected-name")+"[remove]' checked=''>");
            remove_button.parents(".dbl_click_file").fadeOut(1000).remove();
            $.notify(_t(199));
        });
    });

    $("#suitable_for_disabled").click(function(){
        let keys = Object.keys(suitable_for_disabled_types);
        let selected_key = keys.find(key => suitable_for_disabled_types[key] === $(this).val());
        let selected_key_index = keys.indexOf(selected_key);
        let new_key = keys[0];
        if(selected_key_index+1 != keys.length){
            new_key = keys[selected_key_index+1];
        }
        $(this).val(suitable_for_disabled_types[new_key]);
        $(this).prev().val(new_key);
    });


    $("#property_table tr").click(function(e){
        if(e && e.target.tagName.toLowerCase() !== "a"){
            window.open($(this).find("a.edit_button").attr("href"), "_self");
        }        
    })
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

