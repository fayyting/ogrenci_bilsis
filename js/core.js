$.expr[':'].textEquals = function(el, i, m) {
    var searchText = m[3];
    var match = $(el).text().trim().match("^" + searchText + "$")
    return match && match.length > 0;
}
$(document).ready(function () {
   $(document).on("keyup", ".uppercase_filter", uppercase_filter);
   $(document).on("keyup", ".lowercase_filter", lowercase_filter);
   $(".datetimeinput").datetimepicker({
       format: "YYYY-MM-DD HH:mm"
   });
   $(".dateinput").datetimepicker({
       format: "YYYY-MM-DD"
   });
   $(".timeinput").datetimepicker({
    format: 'HH:mm'
   });
    
    $(".search-field").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#main_content table tr:not('.head')").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      });
     
     $('.search-field').keyup(function(e){//on press enter
            if(e.keyCode == 13)
            {
                $(".btn-search").click();
            }
        });
    $(".table-search-field").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $(".tablelist").filter(function() {
          $(this).toggle($(this).find("a").text().toLowerCase().includes(value));
      });
    });
    
    $(".summernote").summernote({
        lang: language
    });
    
    $(".btn-search").click(function () {
        var link = "";
        var search_text = $(".search-field").val();
        search_text.split(" ").forEach(function (item){
            if(item && !link.includes(item)){
                link += (link ? "&" : "")+item;
            }
        });
        window.location = "?"+link;
    });
    
    $(document).on("click", ".file-field",function(e) {
        var file_input = $(this).next("input");
        var file_path = $(this).next().next().children("input");
        file_input.change(function (){
            if(file_input.get(0).files){
                var file = file_input.get(0).files[0];
                file_path.val(file.name);
            }
        });
        file_input.click();
        e.preventDefault();
    });

    $(".yes_no_box").each(function(){
        this.outerHTML = "<div class='yes_no_box_div'>"+this.outerHTML+"</div>";
    });
    $(".yes_no_box").val(1);
    $(".yes_no_box:checked").each(function(){
        $(this).after(" <label for='"+$(this).attr("id")+"' class='yes_no_box_info'>"+_t(142)+"</label>"
        +"<input type='hidden' value='0' name='"+$(this).attr("name")+"' disabled='disabled'>");
    })
    
    .after(
        
    );
    $(".yes_no_box:not(:checked)").each(function(){
        $(this).after(" <label for='"+$(this).attr("id")+"' class='yes_no_box_info'>"+_t(143)+"</label>"
        + "<input type='hidden' value='0' name='"+$(this).attr("name")+"'>");
    })
    $(".yes_no_box").click(function(){
        if($(this).is(':checked')){
            $(this).next().html(_t(142));
            $(this).next().next().attr("disabled","disabled");
        }else{
            $(this).next().html(_t(143));
            $(this).next().next().removeAttr("disabled");
        }
    })

    $(document).on("keyup", ".bootstrap-select.autocomplete.create_if_not_exist .bs-searchbox input", function(){
        text_val = $(this).val();
        select_field = $(this).parents(".bootstrap-select").find("select");
        if($(select_field).find("option:contains('"+text_val+"')").length == 0){
            $(select_field).append("<option value='"+text_val+"'>"+text_val+"</option>");
            select_field.selectpicker("refresh");
        }
    })

    $(document).submit(function () {
        $(".loader").removeClass("hidden");
    }); 
    $(document).ajaxSend(function(){
        $(".loader").removeClass("hidden");
    });
    $(document).ajaxComplete(function () {
        if($.active == 1){
            $(".loader").addClass("hidden");
        }
    })
    
    $(document).ajaxError(function (evt, request, settings){
        var data=request.responseText;
        if (data.length>0) {
            try{
                var resp=$.parseJSON(data);
                if (resp.msg)
                {
                    alertMessage(resp.msg, _t(53), BootstrapDialog.TYPE_DANGER);
                }         
            }catch(ex){

            }
        }   
    });

    $(document).ajaxSuccess(function (evt, request, settings){

        var data=request.responseText;
        if (data.length>0) {
            try{
                var resp=$.parseJSON(data);
                if (resp.msg) {
                    alertMessage(resp.msg, _t(52), BootstrapDialog.TYPE_INFO);
                }           
            }catch(ex){

            }
        }   
    });
    $(document).on("keyup", ".bootstrap-select.autocomplete .bs-searchbox input", autocompleteFilter);

    $(".list-group > .list-group-item > a span.glyphicon").click(function(e){
        e.preventDefault();
        $(this).parents(".list-group-item").find(".subitems").slideToggle();
        $(this).toggleClass("glyphicon-plus glyphicon-minus");
    })

    $(document).on("click", ".property_finder", function(){
        console.log("find property");
        let output = $($(this).parents(".property_finder_section"));
        let finder = new PropertyFinder(output);
        finder.openFinderModal();
    });
    
});

function alertMessage(message, title = _t(54) , type = BootstrapDialog.TYPE_WARNING, callback = function () {}){
       BootstrapDialog.show({
            type : type,
            title: title,
            message: message,
            closable: false,
            buttons : [
                {
                    label : _t(76),
                    cssClass: "btn-danger",
                    action : function (dialog){
                        dialog.close();
                    }
                },
                {
                    label : _t(77),
                    action : function (dialog){
                        callback();
                        dialog.close();
                    }
                }
            ]
        });
   }

var lowercase_filter = function () {
    $(this).val($(this).val().toLowerCase());
    if(!($(this).val().match(/^[a-z0-9_\s]+$/))){
        $(this).parent().addClass("has-error");
    }else{
        $(this).parent().removeClass("has-error");
    }
}
   
var uppercase_filter = function () {
    $(this).val($(this).val().toUpperCase());
    if(!($(this).val().match(/^[A-Z0-9_\s]+$/))){
        $(this).parent().addClass("has-error");
    }else{
        $(this).parent().removeClass("has-error");
    }
}

function _t(id, arguments){
    if(arguments){
        return translations[id].format(arguments);
    }
    return translations[id];
}

String.prototype.format = function () {
    var a = this, b;
    for (b in arguments) {
        a = a.replace(/%[a-z]/, arguments[b]);
    }
    return a; // Make chainable
};

var filter_caller = null;
function autocompleteFilter(event){
    var input = String.fromCharCode(event.keyCode);
    var text  = $(this).val();
    //input key is not a character
    if (text && !/[a-zA-Z0-9-_ ]/.test(input)){
        return;
    }
    if(filter_caller){
        clearInterval(filter_caller);
    }
    var select_field = $(this).parents(".bootstrap-select").find("select");
    filter_caller = setTimeout(
        function(){
            let data = {
                table : $(select_field).attr("data-reference-table"),
                column : $(select_field).attr("data-reference-column"),
                data : text
            };
            if($(select_field).attr("data-reference-filter-column")){
                data["filter-column"] = $(select_field).attr("data-reference-filter-column");
            }
            if($(select_field).attr("data-reference-filter-value")){
                data["filter-value"] = $(select_field).attr("data-reference-filter-value");
            }
            $.ajax({
                url: root+"/ajax/AutoCompleteSelectBoxFilter",
                method: "post",
                data: data,
                success: function(response){
                    response_data = $.parseJSON(response);
                    let options = "";
                    let null_option = select_field.find("option[value='0']");
                    options += null_option.length > 0 ? null_option[0].outerHTML : "";
                    let selected_value = select_field.val();
                    let selected_option = select_field.find("option[value='"+selected_value+"']")[0].outerHTML;
                    let selected = false;
                    for(data of response_data){
                        if(data[0] == selected_value){
                            selected = true;
                        }
                        options += "<option value='"+data[0]+"' "+(selected ? "selected" : "")+">"+data[1]+"</option>";
                    }
                    if(!selected && selected_value != 0){
                        options +=selected_option;
                    }
                    select_field.html(options);
                    if(select_field.hasClass("create_if_not_exist")){
                        if($(select_field).find("option:contains('"+text_val+"')").length == 0){
                            $(select_field).append("<option value='"+text_val+"'>"+text_val+"</option>");
                        }
                    }
                    select_field.selectpicker("refresh");
                }
            });
        }
    , 500);
}