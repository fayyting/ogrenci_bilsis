var object_id;
$(document).ready(function(){
    $(document).on("change", "#filter_form select", function(){ $("#filter_form").submit(); });
    $(".remove_button").click(function(e){
        e.preventDefault();
        object_id = $(e.target).attr("data-id");
        alertMessage(_t(81), _t(82),BootstrapDialog.TYPE_Danger, remove_object);
    });
})
function remove_object(){
    var data = {
        "table" : "properties",
        "ID" : object_id
    };
    $.ajax({
        url: root+"/ajax/delete",
        type: "POST",
        data : data,
        error: function(){
            
        },
        success : function(response){
            $(".remove_button[data-id='"+object_id+"']").parents("tr").remove();
        }
    });
}