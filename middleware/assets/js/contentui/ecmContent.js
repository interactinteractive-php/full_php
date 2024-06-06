/* global dUniqid, globalFolderId, PNotify, dataviewIdForContent, message_to_uploader */

var ecmContent=function(){
    //<editor-fold defaultstate="collapsed" desc="variables">
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="events">
    var initEvent=function(){
        initDropZoneEvent();
    };

    var initDropZoneEvent=function(){
        var ecmContentDropZone=new Dropzone("#ecmContentDropzone_" + dUniqid, {
            url: "mdcontentui/createEcmContent",
            acceptedFiles: ".png,.gif,.jpeg,.pjpeg,.jpg,.x-png,.bmp,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar",
            method: "POST",
            addRemoveLinks: true,
            dictDefaultMessage: message_to_uploader,
            dictFallbackMessage: "Your browser does not support drag'n'drop file uploads.",
            dictFallbackText: "Please use the fallback form below to upload your files like in the olden days.",
            dictFileTooBig: "File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.",
            dictInvalidFileType: "You can't upload files of this type.",
            dictResponseError: "Server responded with {{statusCode}} code.",
            dictCancelUpload: "Болих",
            dictCancelUploadConfirmation: "Are you sure you want to cancel this upload?",
            dictRemoveFile: "Устгах",
            dictRemoveFileConfirmation: null,
            dictMaxFilesExceeded: "You can not upload any more files."
        });


        $('div.dz-default.dz-message > span').css({'display': 'block', 'font-size': '22px', 'text-align': 'center', 'line-height': '22px'});
        $('div.dz-default.dz-message').css({'opacity': 1, 'background-image': 'none'});

        ecmContentDropZone.on('sending', function(file, xhr, formData){
            if(typeof globalFolderId !== "undefined"){
                formData.append('folderId', globalFolderId);
            }
        });

        ecmContentDropZone.on("success", function(file, responseText){
            var response=jQuery.parseJSON(responseText);
            if(response.status === "error"){
                PNotify.removeAll();
                new PNotify({
                    title: '',
                    text: response.message,
                    type: response.status,
                    sticker: false
                });
            } else {
                var thumbnail=$('.dz-details:last').find('img[data-dz-thumbnail]');

                if(response.fileExtension == 'png' ||
                        response.fileExtension == 'gif' ||
                        response.fileExtension == 'jpeg' ||
                        response.fileExtension == 'pjpeg' ||
                        response.fileExtension == 'jpg' ||
                        response.fileExtension == 'x-png' ||
                        response.fileExtension == 'bmp'){
                } else {
                    thumbnail.attr('src', 'assets/core/global/img/filetype/64/' + response.fileExtension + '.png');
                }

                dataViewReload(dataviewIdForContent);
            }
        });
    };
    //</editor-fold>

    return {
        init: function(){
            initEvent();
        }
    };
}();