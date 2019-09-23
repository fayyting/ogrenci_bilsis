function show_gallery(images, index = 0){
    var message = `<img src='`+images[index]+`' class="gallery_image" />`;
    BootstrapDialog.show({
         title : "",
         cssClass: 'gallery-dialog text-center',
         message: message,
         closable: true,
         data: {
             'images': images,
             'index' : index
         },
         buttons : [
            {
                label: _t(201),
                cssClass: "pull-left glyphicon glyphicon-chevron-left btn btn-info",
                action : function (dialog){
                    var index = dialog.getData("index");
                    var images = dialog.getData("images");
                    if(index == 0){
                        index = images.length -1;
                    }else{
                        index--;
                    }
                    dialog.setData("index", index);
                    dialog.getModalBody().find("img").attr("src", images[index]);
                }
            },
            {
                label: _t(200),
                cssClass: "glyphicon glyphicon-chevron-right btn btn-info",
                action : function (dialog){
                    var index = dialog.getData("index");
                    var images = dialog.getData("images");
                    index = (index+1)%images.length;
                    dialog.setData("index", index);
                    dialog.getModalBody().find("img").attr("src", images[index]);
                }
            }
        ]
     });
}