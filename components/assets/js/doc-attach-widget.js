/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function init_click_handlers() {
    console.log('doc-attach-widget init');

    $("#attach-widget").on("click",".res-attach-delete",function (e) {
        e.preventDefault();
        console.log("delete attach",$);
        
        $.get($(this).attr('href'), function(data){
            console.log("delete attach success");
            console.log(data);
            if (data === 'success') {
                $.pjax.reload({container:"#doc_attach_widget_pjax",timeout:false});
            }
        }).fail(function(data){
            console.log("Error",data);
            bootbox.alert({
                message:data.responseText
            });
        });

    });
    
    $("#attach-widget").on("click","#uploadBtn",function(e){
        e.preventDefault();
        var form = document.forms.namedItem("fileinfo");
        console.log(form);
        //console.log(document.getElementById("myfile").files);
        var formData = new FormData(form);
        formData.append("CustomField","This is some extra field");
        //formData.append("MyFile",document.getElementById("myfile").files[0]);
        console.log(formData);
        //formData.append('myfile',$("#myfile").files[0]);
        $.ajax({
            url:'/resource/res-attach/upload-ajax',
            type:'POST',
            data:formData,
            processData:false,
            contentType:false,
            success:function(data){
                console.log(data);
                if(data.result=='success'){
                   $.pjax.reload({container: "#doc_attach_widget_pjax",timeout:false});
                } else {
                    bootbox.alert({
                        backdrop:true,
                        message:"<div style='color:red;'>"+data.msg+"</div>"});
                }
            },
            error:function(data){
                console.log("Error",data);
                bootbox.alert({
                    message:data.responseText
                });
            }
        });
    });

    $("#attach-widget").on("click","#doc-attach-widget-toggle",function(e){
        e.preventDefault();
        $("#doc-attach-widget-panel").toggle();
    });

// Check for the various File API support.
if (window.File && window.FileReader && window.FileList && window.Blob) {
  // Great success! All the File APIs are supported.
} else {
  alert('The File APIs are not fully supported in this browser.');
}

}

init_click_handlers(); // first run
//$("#doc_attach_widget_pjax").on("pjax:success", function () {
//    console.log("pjax:success","init_click_handlers",$.pjax);
//    init_click_handlers(); // reactivate links in grid after pjax update
//}); 

