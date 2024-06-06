/* global Core, PNotify */

var processExcelTemplate=function(){

    //<editor-fold defaultstate="collapsed" desc="variables">
    var $hiddenExcelTemplateDiv,
            $tmpElem,
            $excelTemplateForm;
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="events">
    var initEvent=function(processMetaDataId){
        initSetExcelTemplate(processMetaDataId);
    };

    var initSetExcelTemplate=function(processMetaDataId){
        $.ajax({
            type: 'post',
            url: 'mdmetadata/setExcelTemplate',
            data: {processMetaDataId: processMetaDataId},
            dataType: "json",
            beforeSend: function(){
                if(!$("link[href='assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css']").length){
                    $("head").prepend(
                            '<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css"/>');
                }
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function(data){
                var dialogName='dialog-excel-template';
                if(!$("#" + dialogName).length){
                    $('<div id="' + dialogName + '" class="display-none"></div>').appendTo('body');
                }
                var $dialogName=$("#" + dialogName);
                $dialogName.empty().html(data.html);
                $excelTemplateForm=$('#excelTemplateForm');
                showFileAttachDialog($dialogName, data);
            },
            error: function(){
                alert("Error");
            }
        }).complete(function(){
            Core.unblockUI();
        });
    };

    var showFileAttachDialog=function($dialogName, data){
        $dialogName.dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: data.title,
            width: 300,
            minWidth: 300,
            height: 'auto',
            modal: false,
            open: function(){

            },
            close: function(){
                $dialogName.empty().dialog('destroy').remove();
            },
            buttons: [
                {text: data.save_btn, class: 'btn btn-sm green', click: function(){
                        saveSetExcelTemplate($dialogName);
                    }},
                {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function(){
                        $dialogName.dialog('close');
                    }}
            ]
        }).dialogExtend({
            "closable": true,
            "maximizable": true,
            "minimizable": true,
            "collapsable": true,
            "dblclick": "maximize",
            "minimizeLocation": "left",
            "icons": {
                "close": "ui-icon-circle-close",
                "maximize": "ui-icon-extlink",
                "minimize": "ui-icon-minus",
                "collapse": "ui-icon-triangle-1-s",
                "restore": "ui-icon-newwin"
            }
        });
        $dialogName.dialog('open');
    };

    var saveSetExcelTemplate=function($dialogName){
        Core.blockUI({
            message: 'Loading...',
            boxed: true,
            target: $excelTemplateForm
        });

        $excelTemplateForm.ajaxSubmit({
            url: 'mdcontentui/saveExcelTemplate/',
            type: "POST",
            dataType: "json",
            success: function(response){
                PNotify.removeAll();
                if(response.status === 'success'){
                    new PNotify({
                        title: 'Success',
                        text: response.message,
                        type: 'success',
                        sticker: false
                    });

                    $dialogName.dialog('close');
                } else {
                    new PNotify({
                        title: 'Error',
                        text: response.message,
                        type: 'error',
                        sticker: false
                    });
                }
            },
            error: function(jqXHR, exception){
                Core.unblockUI($excelTemplateForm);
            }
        });
    };

    var onChangeAttachFile=function(input){
        if($(input).hasExtension(["xls", "xlsx"])){
            var ext=input.value.match(/\.([^\.]+)$/)[1];
            if(typeof ext !== "undefined"){
                var li='',
                        fileImgUniqId=Core.getUniqueID('file_img'),
                        fileAUniqId=Core.getUniqueID('file_a'),
                        extension=ext.toLowerCase();

                li='<figure class="directory">' +
                        '<div class="img-precontainer">' +
                        '<div class="img-container directory">';
                li+='<a href="javascript:;" title="">';
                li+='<img src="assets/core/global/img/filetype/64/' + extension + '.png"/>';
                li+='</a>';

                li+='</div>' +
                        '</div>' +
                        '<div class="box">';
                li+=
                        '<h4 class="ellipsis"><input type="text" name="excel_template_file_name[]" class="form-control col-md-12 excel_template_file_name" placeholder="Нэр..."/></h4>' +
                        '</div>' +
                        '</a>' +
                        '</figure>';
                var $listViewFile=$('.list-view-excel-template');
                $hiddenExcelTemplateDiv=$('#hiddenExcelTemplateDiv');
                $listViewFile.find("#excelTemplateFile").html(li);

                previewPhotoAddMode(input, $listViewFile.find('#' + fileImgUniqId), $listViewFile.find('#' + fileAUniqId));
            }
        } else {
            alert('Excel файл сонгоно уу.');
            $(input).val('');
        }
    };

    var previewPhotoAddMode=function(input, $targetImg, $targetAnchor){
        if(input.files && input.files[0]){
            var reader=new FileReader();
            reader.onload=function(e){
                $targetImg.attr('src', e.target.result);
                $targetAnchor.attr('href', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }

        var $this=$(input), $clone=$this.clone();
        $this.after($clone).appendTo($hiddenExcelTemplateDiv);
    };
    //</editor-fold>

    return {
        init: function(elem, processMetaDataId){
            $tmpElem=$(elem);
            initEvent(processMetaDataId);
        },
        onChangeAttachFile: function(input){
            onChangeAttachFile(input);
        }
    };
}();
