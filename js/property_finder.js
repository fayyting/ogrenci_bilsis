class PropertyFinder{
    constructor(output){
        this.output = output;
    }

    openFinderModal(){
        let output = this.output;
        $.ajax({
            url: root + "/ajax/getPropertyFinder",
            success: function(response){
                BootstrapDialog.show({
                    type : BootstrapDialog.TYPE_INFO,
                    cssClass: 'finder-dialog',
                    title: "",
                    message: $(response),
                    onshow: function(dialog){
                        let dialogContent = dialog.getModalContent();
                        dialogContent.find("select").selectpicker();
                        dialogContent.find(".property_search_button").click(function(){
                            let form_values = dialogContent.find("form").serializeArray();
                            $.ajax({
                                url: root + "/ajax/findProperty",
                                data: form_values,
                                method: "post",
                                success: function(response){
                                    response = $(response);
                                    response.find(".property_finder_select").click(function(){
                                        let property = $(this).attr("data-select-id");
                                        $.ajax({
                                            url: root+"/ajax/getPropertyInfoForPropertySection",
                                            method: "post",
                                            dataType: "json",
                                            data: {property: property},
                                            success: function(result){
                                                console.log(result);
                                                for(let [key, value] of Object.entries(result)){
                                                    value = $("<div />").html(value).text(); //html special chars decoded string
                                                    output.find("#"+key).val(value);
                                                }
                                                dialog.close();

                                            }
                                        })
                                    });
                                    dialogContent.find(".property_find_result").html(response);
                                }
                            })
                        });
                    }
                });
            }
        });
    }
}