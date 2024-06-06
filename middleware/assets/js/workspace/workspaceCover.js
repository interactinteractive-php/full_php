/* global Core, PNotify, wsDmMetaDataId, wsOneSelectedRow */

var workspaceCover=function(){
    //<editor-fold defaultstate="collapsed" desc="variables">
    var crop_max_width=1000,
            crop_max_height=1000,
            inputTmp,
            workspaceId,
            $wsCoverDiv,
            $workspaceDiv;
//</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="events">
    var initEvent=function(){
        $workspaceDiv=$('#workspace-id-' + workspaceId);
    };

    var renderCropModal=function(response, input){
        var config={
            title: response.Title,
            width: '1200',
            height: 'auto',
            modal: false,
            open: function(){
                readAsDataUrlFn(input);
            },
            buttons: [
                {
                    html: response.crop_btn, class: 'btn btn-sm btn-circle green', click: function(){
                        saveCover($("#dialog-image-crop"));
                    }
                }, {
                    html: response.close_btn, class: 'btn blue-madison btn-sm bp-close-btn', click: function(){
                        $("#dialog-image-crop").dialog('close');
                    }
                }
            ]
        };

        Core.initDialog('dialog-image-crop', response.html, config, function($dialog){
            $wsCoverDiv=$('#ws_cover_div_' + workspaceId);
        });
    };

    var saveCover=function($ecmContentDialog){
        var isAddBlob=true;
        if($wsCoverDiv.find("#pngInput").val() === ''){
            isAddBlob=false;
        }
        var base64=$wsCoverDiv.find("#pngInput").val();
        $wsCoverDiv.find("#pngInput").val("");
        var data=new FormData($wsCoverDiv.find("#wsCoverForm")[0]);

        if(isAddBlob){
            var blob=dataURLtoBlob(base64);
            data.append("coverImg", blob);
            data.append("dataViewId", wsDmMetaDataId);
            data.append("srcRecordId", wsOneSelectedRow.id);
        }

        Core.blockUI();
        $.ajax({
            url: "mdworkspace/saveCover",
            type: "POST",
            dataType: "JSON",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data){
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });

                if(data.status === 'success'){
                    $ecmContentDialog.dialog('close');
                }
            },
            error: function(jqXHR, exception){
                Core.unblockUI();
            }
        }).complete(function(){
            Core.unblockUI();
        });
    };

    var readAsDataUrlFn=function(input){
        inputTmp=input;
        if(input.files && input.files[0]){
            var reader=new FileReader;
            reader.onload=function(e){
                $wsCoverDiv.find("#jcropDiv").html("").append('<img src="' + e.target.result + '" alt="" />');
                $wsCoverDiv.find("#jcropDiv img").Jcrop({
                    onChange: canvas,
                    onSelect: canvas,
                    boxWidth: crop_max_width,
                    boxHeight: crop_max_height
                });
            };
            reader.readAsDataURL(input.files[0]);
        }
    };

    var canvas=function(coords){
        var imageObj=$wsCoverDiv.find("#jcropDiv img")[0];

        var canvas=$wsCoverDiv.find("#canvas")[0];
        if(coords.h > 0 && coords.w > 0){
            canvas.width=coords.w;
            canvas.height=coords.h;
            context=canvas.getContext("2d");
            context.drawImage(imageObj, coords.x, coords.y, coords.w, coords.h, 0, 0, coords.w, coords.h);
            toPng();
        }
    };

    var toPng=function(){
        var png=$wsCoverDiv.find("#canvas")[0].toDataURL("image/png");
        $wsCoverDiv.find("#pngInput").val(png);
        $workspaceDiv.find('.ws-bg').css('background-image', 'url(' + png + ')');
    };

    var dataURLtoBlob=function(dataURL){
        var BASE64_MARKER=";base64,";
        if(dataURL.indexOf(BASE64_MARKER) === -1){
            var parts=dataURL.split(",");
            var contentType=parts[0].split(":")[1];
            var raw=decodeURIComponent(parts[1]);
            return new Blob([raw], {
                type: contentType
            });
        }
        var parts=dataURL.split(BASE64_MARKER);
        var contentType=parts[0].split(":")[1];
        var raw=window.atob(parts[1]);
        var rawLength=raw.length;
        var uInt8Array=new Uint8Array(rawLength);
        for(var i=0; i < rawLength; ++i){
            uInt8Array[i]=raw.charCodeAt(i);
        }
        return new Blob([uInt8Array], {
            type: contentType
        });
    };
    //</editor-fold>

    return {
        init: function(response, input, id){
            workspaceId=id;
            initEvent();
            renderCropModal(response, input);
        }
    };
}();