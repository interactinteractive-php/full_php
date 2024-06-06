/* global tinymce, Core, PNotify, globalFolderId, MET_99990632, move_btn_txt, make_copy_txt, copy_btn_txt, ECM_CONTENT_FOLDER_DV, close_btn, $parentObjectValueList, selectedDataviewId, selectedContentId */

var folderAction=function(){
    //<editor-fold defaultstate="collapsed" desc="variables">
    var isExplorer;
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="events">
    var initEvent=function(type){
        if(type === 1){
            var $ecmContentFolderDialog=$("#actions-div-" + selectedContentId);

            if(isExplorer){
                var dialogName='dialog-ecm-content-move-to-folder';

                if($("#" + dialogName).length === 0){
                    $('<div id="' + dialogName + '"></div>').appendTo('body');
                }
                $ecmContentFolderDialog=$("#" + dialogName);
            }

            loadDataViewFolderList($ecmContentFolderDialog, 'mdcontentui/moveToFolder',
                    {
                        title: MET_99990632,
                        btnTxt: move_btn_txt
                    }
            );
        } else if(type === 2){
            if(isExplorer){
                var dialogName='dialog-ecm-content-copy-to-folder';

                if($("#" + dialogName).length === 0){
                    $('<div id="' + dialogName + '"></div>').appendTo('body');
                }
                $ecmContentFolderDialog=$("#" + dialogName);
            }

            loadDataViewFolderList($ecmContentFolderDialog, 'mdcontentui/copyToFolder',
                    {
                        title: make_copy_txt,
                        btnTxt: copy_btn_txt
                    }
            );
        }
    };

    var loadDataViewFolderList=function($ecmContentFolderDialog, url, textTrans){
        $.ajax({
            type: 'post',
            url: 'mdobject/dataview/' + ECM_CONTENT_FOLDER_DV,
            beforeSend: function(){
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            dataType: 'html',
            success: function(response){
                if(isExplorer){
                    $ecmContentFolderDialog.html('<div class="static-folder-list">' + response + '</div>');
                    openDataViewFolderList($ecmContentFolderDialog, url, textTrans);
                } else {
                    $ecmContentFolderDialog.html(
                            '<button type="button" class="btn btn-circle btn-sm green actionBtn" style="float: right;"></button><div class="static-folder-list">' +
                            response +
                            '</div>');
                    $ecmContentFolderDialog.find('.actionBtn').off().on('click', function(){
                        moveOrCopyToFolder($ecmContentFolderDialog, url);
                    });
                    $ecmContentFolderDialog.find('.actionBtn').html(textTrans.btnTxt);
                    $ecmContentFolderDialog.show();
                }
            }
        }).complete(function(){
            Core.unblockUI();
        });
    };

    var openDataViewFolderList=function($ecmContentFolderDialog, url, textTrans){

        $ecmContentFolderDialog.dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: textTrans.title,
            width: 500,
            height: 500,
            clickOutside: true,
            modal: true,
            close: function(){
                $ecmContentFolderDialog.empty().dialog('destroy').remove();
            },
            buttons: [
                {
                    html: textTrans.btnTxt, class: 'btn btn-sm btn-circle green', click: function(){
                        moveOrCopyToFolder($ecmContentFolderDialog, url);
                    }
                }, {
                    html: plang.get('close_btn'), class: 'btn blue-madison btn-sm bp-close-btn', click: function(){
                        $ecmContentFolderDialog.dialog('close');
                    }
                }
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

        $ecmContentFolderDialog.dialog('open');
    };

    var moveOrCopyToFolder=function($ecmContentFolderDialog, url){
        var rows=window['objectdatagrid_' + ECM_CONTENT_FOLDER_DV].datagrid('getSelections');
        if(rows.length === 0){
            PNotify.removeAll();
            new PNotify({
                title: 'Warning',
                text: 'Та мөр сонгоно уу',
                type: 'warning',
                sticker: false
            });
        } else {
            var id=selectedContentId;

            if(typeof id !== "undefined"){
                $.ajax({
                    type: 'post',
                    url: url,
                    data: {
                        id: id,
                        directoryId: rows[0].id
                    },
                    beforeSend: function(){
                        Core.blockUI({
                            message: 'Loading...',
                            boxed: true
                        });
                    },
                    dataType: 'json',
                    success: function(response){
                        PNotify.removeAll();
                        if(response.status === 'success'){
                            new PNotify({
                                title: 'Success',
                                text: response.message,
                                type: 'success',
                                sticker: false
                            });

                            if(isExplorer){
                                dataViewReload(selectedDataviewId);
                                $ecmContentFolderDialog.dialog('close');
                            } else {
                                var $wsAreaPrevMenuId=$('.ws-area').data('previous-menu-id');
                                if(typeof $wsAreaPrevMenuId !== "undefined"){
                                    $('a[data-menu-id="' + $wsAreaPrevMenuId + '"]').trigger('click');
                                }
                            }
                        } else {
                            new PNotify({
                                title: 'Error',
                                text: response.message,
                                type: 'error',
                                sticker: false
                            });
                        }
                    }
                }).complete(function(){
                    Core.unblockUI();
                });
            } else {
                PNotify.removeAll();
                new PNotify({
                    title: 'Warning',
                    text: 'Та мөр сонгоно у',
                    type: 'warning',
                    sticker: false
                });
            }
        }
    };

    //</editor-fold>

    return {
        init: function(type, isExplorerP){
            isExplorer=isExplorerP;
            initEvent(type);
        }
    };
}();