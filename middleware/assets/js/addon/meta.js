function clearProcessCache() {
    $.ajax({
        type: 'post',
        url: 'mdmeta/clearProcessCache',
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            var $dialogName = 'dialog-clearprocesscache';
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName);
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 500,
                height: 'auto',
                modal: true,
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{
                        text: data.clear_btn,
                        class: 'btn btn-sm green-meadow',
                        click: function() {
                            $.ajax({
                                type: 'post',
                                url: 'mdmeta/runClearProcessCache',
                                dataType: 'json',
                                beforeSend: function() {
                                    Core.blockUI({animate: true});
                                },
                                success: function(dataSub) {
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: dataSub.status,
                                        text: dataSub.message,
                                        type: dataSub.status,
                                        sticker: false
                                    });
                                    if (dataSub.status === 'success') {
                                        $dialog.dialog('close');
                                    }
                                    Core.unblockUI();
                                }
                            });
                        }
                    },
                    {
                        text: data.close_btn,
                        class: 'btn blue-madison btn-sm',
                        click: function() {
                            $dialog.dialog('close');
                        }
                    }
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        }
    });
}

function generateLanguageFile() {
    $.ajax({
        type: 'post',
        url: 'mdlanguage/renderGenerateLanguageFile',
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            var $dialogName = 'dialog-generatelangfile';
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName);
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 400,
                height: 'auto',
                modal: true,
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{
                        text: data.create_btn,
                        class: 'btn btn-sm green-meadow',
                        click: function() {
                            $.ajax({
                                type: 'post',
                                url: 'mdlanguage/generateLanguageFile',
                                dataType: 'json',
                                beforeSend: function() {
                                    Core.blockUI({animate: true});
                                },
                                success: function(dataSub) {
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: dataSub.status,
                                        text: dataSub.message,
                                        type: dataSub.status,
                                        sticker: false
                                    });
                                    if (dataSub.status === 'success') {
                                        $dialog.dialog('close');
                                    }
                                    Core.unblockUI();
                                },
                                error: function() { alert('Error'); }
                            });
                        }
                    },
                    {
                        text: data.close_btn,
                        class: 'btn blue-madison btn-sm',
                        click: function() {
                            $dialog.dialog('close');
                        }
                    }
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        }
    });
}
function metaDataDefault(params) {
    var $renderMeta = $('#renderMeta'), locationHash = '', focusMetaId = null, editMetaId = null;

    if (window.location.hash !== '') {
        var parsedHash = queryString.parse(window.location.hash);
        if (typeof parsedHash.focusMetaId !== 'undefined' && parsedHash.focusMetaId !== '') {
            focusMetaId = parsedHash.focusMetaId;
        }
        if (typeof parsedHash.editMetaId !== 'undefined' && parsedHash.editMetaId !== '') {
            editMetaId = parsedHash.editMetaId;
        }
    }

    $.ajax({
        type: 'post',
        url: 'mdmetadata/defaultList',
        data: { params: params },
        beforeSend: function() {
            Core.blockUI({animate: true});
        },
        success: function(data) {

            $renderMeta.empty().append(data).promise().done(function() {

                if (focusMetaId) {
                    var $focusMeta = $renderMeta.find('li#' + focusMetaId);
                    
                    $focusMeta.addClass('pf-focused-meta');
                    $focusMeta.find('input').prop('checked', true);
                    $focusMeta.find('a').focus();
                    
                    locationHash += '#focusMetaId=' + focusMetaId;
                }

                if (editMetaId) {
                    editFormMeta(editMetaId, '', this);
                }

                window.location.hash = locationHash;
            });

            Core.unblockUI();
        },
        error: function() { alert('Error'); }
    });
}

function refreshList(id, type, params) {
    childRecordView(id, type, params);
}

function historyBackList(id, type, params) {
    if (id === '' && type === '') {
        metaDataDefault();
    } else {
        $.ajax({
            type: 'post',
            url: 'mdmetadata/historyBackList',
            data: { ROW_ID: id, TYPE: type },
            dataType: 'json',
            success: function(data) {
                if (data.rowId !== null) {
                    childRecordView(data.rowId, data.rowType, params);
                } else {
                    metaDataDefault(params);
                }
            },
            error: function() { alert('Error'); }
        });
    }
}

function childRecordView(id, type, params) {
    var $renderMeta = $('#renderMeta');
    var locationHash = 'objectType=' + type + '&objectId=' + id;
    var focusMetaId = null,
        editMetaId = null;

    if (window.location.hash !== '') {
        var parsedHash = queryString.parse(window.location.hash);
        if (typeof parsedHash.focusMetaId !== 'undefined' && parsedHash.focusMetaId !== '') {
            focusMetaId = parsedHash.focusMetaId;
        }
        if (typeof parsedHash.editMetaId !== 'undefined' && parsedHash.editMetaId !== '') {
            editMetaId = parsedHash.editMetaId;
        }
    }

    $.ajax({
        type: 'post',
        url: 'mdmetadata/childRecordList',
        data: { ROW_ID: id, TYPE: type, params: params },
        beforeSend: function() {
            Core.blockUI({animate: true});
        },
        success: function(data) {

            $renderMeta.empty().append(data).promise().done(function() {

                if (focusMetaId) {
                    
                    var $focusMeta = $renderMeta.find('li#' + focusMetaId);
                    
                    $focusMeta.addClass('pf-focused-meta');
                    $focusMeta.find('input').prop('checked', true);
                    $focusMeta.find('a').focus();
                    
                    locationHash += '&focusMetaId=' + focusMetaId;
                }

                if (editMetaId) {
                    editFormMeta(editMetaId, id, this);
                }

                window.location.hash = locationHash;
                
                Core.unblockUI();
                
                $('html, body').animate({scrollTop: 0}, 'fast');
            });
        },
        error: function() { alert('Error'); }
    });
}
function metaConfigReplace(elem) {
    var $dialogName = 'dialog-meta-configreplace';
    if (!$("#" + $dialogName).length) { $('<div id="' + $dialogName + '"></div>').appendTo('body'); }
    var $dialog = $('#' + $dialogName);
    var metaName = elem.find('.ellipsis').text();
    var metaTypeId = '';
    
    if (elem.hasClass('dataview') || elem.hasClass('metagroup') || elem.hasClass('tablestructure') || elem.hasClass('parameter')) {
        metaTypeId = '200101010000016';
    } else if (elem.hasClass('process')) {
        metaTypeId = '200101010000011';
    } else if (elem.hasClass('dashboard')) {
        metaTypeId = '200101010000013';
    } else if (elem.hasClass('bookmark')) {
        metaTypeId = '200101010000010';
    } else if (elem.hasClass('field')) {
        metaTypeId = '200101010000017';
    } else if (elem.hasClass('donut')) {
        metaTypeId = '200101010000028';
    } else if (elem.hasClass('report_template')) {
        metaTypeId = '200101010000029';
    } else if (elem.hasClass('card')) {
        metaTypeId = '200101010000031';
    } else if (elem.hasClass('diagram')) {
        metaTypeId = '200101010000032';
    } else if (elem.hasClass('package')) {
        metaTypeId = '200101010000033';
    } else if (elem.hasClass('workspace')) {
        metaTypeId = '200101010000034';
    } else if (elem.hasClass('statement')) {
        metaTypeId = '200101010000035';
    } else if (elem.hasClass('layout')) {
        metaTypeId = '200101010000036';
    } else if (elem.hasClass('menu_meta')) {
        metaTypeId = '200101010000025';
    } 
    
    var html = '<form method="post" id="formMetaConfigReplace">'+ 
        '<div class="col-md-12 xs-form">'+ 
            '<div class="form-group row">'+
                '<label class="col-form-label col-md-3 text-right pr0">Үзүүлэлт:</label>'+
                '<div class="col-md-9 font-size-15 font-weight-bold">'+
                    metaName+
                '</div>'+
            '</div>'+
            '<div class="form-group row">'+
                '<label class="col-form-label col-md-3 text-right pr0" for="_displayField">Тохиргоо авах үзүүлэлт:</label>'+
                '<div class="col-md-9">'+
                    '<div class="meta-autocomplete-wrap" data-params="autoSearch=1&metaTypeId='+metaTypeId+'">'+
                        '<div class="input-group double-between-input">'+
                            '<input id="configReplaceTargetMetaId" name="configReplaceTargetMetaId" type="hidden" required>'+
                            '<input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="'+plang.get('META_00068')+'" type="text" required>'+
                            '<span class="input-group-btn">'+
                                '<button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="commonMetaDataSelectableGrid(\'single\', \'\', this);"><i class="fa fa-search"></i></button>'+
                            '</span>'+
                            '<span class="input-group-btn not-group-btn">'+
                                '<div class="btn-group pf-meta-manage-dropdown">'+
                                    '<button class="btn grey-cascade btn-bordered form-control-sm mr0 dropdown-toggle" type="button" data-toggle="dropdown"></button>'+
                                    '<ul class="dropdown-menu dropdown-menu-right" style="min-width: 126px;" role="menu"></ul>'+
                                '</div>'+
                            '</span>'+
                            '<span class="input-group-btn flex-col-group-btn">'+
                                '<input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="'+plang.get('META_00099')+'" type="text" required>'+      
                            '</span>'+     
                        '</div>'+
                    '</div>'+
                '</div>'+
            '</div>'+
        '</div>'+
    '</form>';
    
    $dialog.html(html);
    $dialog.dialog({
        cache: false,
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        title: 'Тохиргоог ижилсүүлэх',
        width: 620,
        height: 'auto',
        modal: true,
        close: function () {
            $dialog.dialog('destroy').remove();
        },
        buttons: [
            {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow', click: function() {
                
                PNotify.removeAll();
                
                var $form = $('#formMetaConfigReplace');
                $form.validate({ errorPlacement: function() {} });
                
                if ($form.valid()) {
                    
                    var subDialogName = '#dialog-metaconfigreplace-confirm';
                    if (!$(subDialogName).length) {
                        $('<div id="' + subDialogName.replace('#', '') + '"></div>').appendTo('body');
                    }
                    var $subDialog = $(subDialogName);

                    $subDialog.html('Та тохиргоог ижилсүүлэхдээ итгэлтэй байна уу?');
                    $subDialog.dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: plang.get('msg_title_confirm'), 
                        width: 320,
                        height: 'auto',
                        modal: true,
                        buttons: [
                            {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function() {
                                
                                $subDialog.dialog('close');
                                
                                $.ajax({
                                    type: 'post',
                                    url: 'mdupgrade/metaConfigReplace',
                                    data: {
                                        sourceId: elem.attr('data-id'), 
                                        targetId: $('#configReplaceTargetMetaId').val()
                                    },
                                    dataType: 'json',
                                    beforeSend: function() {
                                        Core.blockUI({message: 'Loading...', boxed: true});
                                    },
                                    success: function(data) {

                                        PNotify.removeAll();
                                        new PNotify({
                                            title: data.status,
                                            text: data.message,
                                            type: data.status,
                                            sticker: false
                                        });

                                        if (data.status == 'success') {
                                            $dialog.dialog('close');
                                        }

                                        Core.unblockUI();
                                    }, 
                                    error: function() { alert('Error'); Core.unblockUI(); }
                                });
                            }},
                            {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                $subDialog.dialog('close');
                            }}
                        ]
                    });
                    $subDialog.dialog('open');
                }
            }},
            {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() { 
                $dialog.dialog('close');
            }}
        ]
    });
    $dialog.dialog('open');
}

function addMetaBySystem(folderId, dialogMode, metaDataId, metaDataType) {
    var postData = { folderId: folderId };
    var dataType = 'html';
    var url = 'mdmetadata/addMetaBySystem';
    if (typeof dialogMode !== 'undefined') {
        postData = { folderId: folderId, metaDataId: metaDataId, dialogMode: dialogMode, isDialog: false, metaDataType: metaDataType };
        dataType = 'json';
        url = 'mdmetadata/editMetaBySystem';
    }
    $.ajax({
        type: 'post',
        url: url,
        dataType: dataType,
        data: postData,
        beforeSend: function() {
            Core.blockUI({animate: true});
            if (!$().iconpicker) {
                $.cachedScript('assets/custom/addon/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js?v=1').done(function() {      
                    $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>');
                });
            }
        },
        success: function(data) {
            if (typeof dialogMode !== 'undefined') {
                var $dialogName = 'dialog-add-dashboard-' + metaDataId;
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                if (typeof metaDataType != 'undefined' && metaDataType == 'reportTemplate')
                    $('.viewer-report-template-' + metaDataId).html('<div class="col-md-12">' + data.mainHtml + '</div>');
                else if (typeof metaDataType != 'undefined' && metaDataType == 'dashboard')
                    $('.viewer-dashboard-' + metaDataId).html('<div class="col-md-12">' + data.mainHtml + '</div>');

                Core.unblockUI();
                return;

                $("#" + $dialogName).empty().append(data.mainHtml);
                $("#" + $dialogName).find('.form-actions').hide();
                $("#" + $dialogName).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: 'DASHBOARD НЭМЭХ',
                    height: ($('#object-value-list-' + metaDataId).height() > 200) ? 'auto' : 500,
                    width: 1000,
                    maxHeight: ($('#object-value-list-' + metaDataId).height() > 200) ? $('#object-value-list-' + metaDataId).height() : 500,
                    modal: false,
                    close: function() {
                        $('#object-value-list-' + metaDataId).empty().dialog('destroy').remove();
                    },
                    buttons: [{
                            text: data.save_btn,
                            class: 'btn btn-success btn-sm',
                            click: function() {
                                createMetaForm(this, dialogMode, $dialogName, metaDataId);
                            }
                        },
                        {
                            text: data.close_btn,
                            class: 'btn blue-madison btn-sm',
                            click: function() {
                                $("#" + $dialogName).empty().dialog('destroy').remove();
                            }
                        }
                    ]
                }).dialogExtend({
                    'closable': true,
                    'maximizable': true,
                    'minimizable': true,
                    'collapsable': true,
                    'dblclick': 'maximize',
                    'minimizeLocation': 'left',
                    'icons': {
                        'close': 'ui-icon-circle-close',
                        'maximize': 'ui-icon-extlink',
                        'minimize': 'ui-icon-minus',
                        'collapse': 'ui-icon-triangle-1-s',
                        'restore': 'ui-icon-newwin'
                    }
                });
                $("#" + $dialogName).dialog('open');
                $("#" + $dialogName).dialogExtend("maximize");
                Core.initAjax($("#" + $dialogName));
            } else {
                var $viewFormMeta = $('#viewFormMeta');
                $viewFormMeta.empty().append(data);
                Core.initAjax($viewFormMeta);
                $("#renderMeta, #editFormGroup").hide();
                $viewFormMeta.show();
            }

            Core.unblockUI();
        },
        error: function() {
            alert('Error');
        }
    });
}

function backFormMeta() {
    var $renderMeta = $('#renderMeta');
    Core.destroyIconPicker();
    $('#viewFormMeta, #editFormMeta, #editFormGroup, #editFormFolder').empty().hide();
    $renderMeta.show();
    $renderMeta.find('li.meta-selected > figure > a, li.ui-selected > figure > a').focus();
}

function backEditFormMeta(elem) {
    var $this = $(elem), $form = $this.closest('form');
    
    if ($form.length && $form.hasAttr('data-changed') && $form.attr('data-changed') == '1') {
        
        var dialogName = '#dialog-metaedit-confirm';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        var $dialog = $(dialogName);

        $dialog.html(plang.get('msg_sure_leave_this_page'));
        $dialog.dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: plang.get('msg_title_confirm'), 
            width: 300,
            height: 'auto',
            modal: true,
            buttons: [
                {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function() {
                    $dialog.dialog('close');
                    fncBackEditFormMeta();
                }},
                {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                    $dialog.dialog('close');
                }}
            ]
        });
        $dialog.dialog('open');
                
    } else {
        fncBackEditFormMeta();
    }
}

function fncBackEditFormMeta() {
    var $viewFormMeta = $('#viewFormMeta'), $renderMeta = $('#renderMeta');
    Core.destroyIconPicker();
    
    if ($.trim($viewFormMeta.html()).length === 0) {
        $("#editFormMeta").empty().hide();
        $viewFormMeta.hide();
        $("#editFormGroup, #editFormFolder").hide();
        $renderMeta.show();
        $renderMeta.find('li.meta-selected > figure > a, li.ui-selected > figure > a').focus();
    } else {
        $renderMeta.hide();
        $("#editFormMeta, #editFormGroup, #editFormFolder").empty().hide();
        $viewFormMeta.show();
    }
}

function createMetaForm(elem, dialogMode, $mainDialogName, mainMetaDataId) {
    var metaSystemFormName = "#addMetaSystemForm";
    if (typeof dialogMode !== 'undefined') {
        metaSystemFormName = "#editMetaSystemForm";
    }
    $(metaSystemFormName).validate({ errorPlacement: function() {} });
    if ($(metaSystemFormName).valid()) {
        $(metaSystemFormName).ajaxSubmit({
            type: 'post',
            url: 'mdmetadata/createMetaSystemModuleForm',
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: plang.get('msg_saving_block'), boxed: true});
            },
            success: function(data) {
                PNotify.removeAll();

                if (data.status === 'success') {
                    new PNotify({
                        title: 'Success',
                        text: data.message,
                        type: 'success',
                        sticker: false
                    });

                    if (typeof dialogMode !== 'undefined') {
                        $("#" + $mainDialogName).empty().dialog('destroy').remove();
                        if (data.metaTypeId == 'dashboard')
                            window['objectDashboardView_' + mainMetaDataId]();
                        else {
                            if (data.metaTypeId == 'reportTemplate')
                                window['objectReportTemplateView_' + mainMetaDataId]();
                        }
                    } else {
                        $("#viewFormMeta, #editFormMeta, #editFormGroup, #editFormFolder").empty().hide();
                        if (data.folderId == '' || data.folderId == 'null' || data.folderId == null) {
                            metaDataDefault();
                        } else {
                            refreshList(data.folderId, 'folder');
                        }
                        $("#renderMeta").show();
                    }
                } else {
                    if (typeof data.fieldName !== 'undefined') {
                        $("input[name='" + data.fieldName + "']", 'form#addMetaSystemForm').addClass("error");
                    }
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                }
                Core.unblockUI();
            }
        });
    }
}

function updateMetaForm(elem, submitType, $mainMetaDataId) {
    var $editMetaSystemForm = $('#editMetaSystemForm');
    $editMetaSystemForm.validate({ errorPlacement: function() {} });
    if ($editMetaSystemForm.valid()) {
        var $saveBtn = $(elem);
        var url = 'mdmetadata/updateMetaSystemModuleForm';
        if (typeof submitType !== 'undefined' && submitType === 1) {
            url = 'mdmetadata/createMetaSystemModuleForm'
        }
        
        Core.blockUI({message: plang.get('msg_saving_block'), boxed: true});
        $saveBtn.attr({'disabled': 'disabled'}).prepend('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
        
        setTimeout(function() {
            
            $editMetaSystemForm.ajaxSubmit({
                type: 'post',
                url: url,
                dataType: 'json',
                success: function(data) {
                    PNotify.removeAll();
                    if (data.status == 'success') {
                        new PNotify({
                            title: data.status,
                            text: data.message,
                            type: data.status,
                            sticker: false
                        });
                        $editMetaSystemForm.trigger('remove');
                        if (typeof submitType !== 'undefined' && submitType === 1) {
                            if (typeof data.metaTypeId != 'undefined' && data.metaTypeId == 'dashboard') {
                                window['objectDashboardView_' + $mainMetaDataId]();
                            } else {
                                if (typeof data.metaTypeId != 'undefined' && data.metaTypeId == 'reportTemplate') {
                                    window['objectReportTemplateView_' + $mainMetaDataId]();
                                }
                            }
                            return false;
                        }
                        if (data.folderId == '' || data.folderId == 'null' || data.folderId == null) {
                            metaDataDefault();
                        } else {
                            refreshList(data.folderId, 'folder');
                        }
                        viewMetaData(data.metaDataId, data.folderId);
                        
                    } else {
                        if (data.status === 'locked') {
                            lockedRequestMeta(data);
                        } else {
                            if (typeof data.fieldName !== 'undefined') {
                                $("input[name='" + data.fieldName + "']", 'form#editMetaSystemForm').addClass("error");
                            }
                            new PNotify({
                                title: data.status,
                                text: data.message,
                                type: data.status,
                                sticker: false
                            });
                        }
                    }
                    $saveBtn.removeAttr('disabled').find('i:eq(0)').remove();
                    Core.unblockUI();
                }
            });
        
        }, 100);
    }
}

function viewMetaData(metaDataId, folderId) {
    $.ajax({
        type: 'post',
        url: 'mdmetadata/viewMetaBySystem',
        data: { metaDataId: metaDataId, folderId: folderId },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({animate: true});
        },
        success: function(data) {
            $("#viewFormMeta").empty().append(data.mainHtml);
            $("#renderMeta, #editFormMeta, #editFormGroup, #editFormFolder").hide();
            $("#viewFormMeta").show();
            Core.unblockUI();
            $(".scroll-to-top").trigger("click");
        },
        error: function() { alert('Error'); }
    }).done(function() {
        Core.initAjax($("#viewFormMeta"));
    });
}

function removeMetaPhoto(element) {
    $(element).parent().parent().remove();
}

function removeMetaFile(element) {
    $(element).parent().parent().remove();
}

function editFormMeta(metaDataId, folderId, element, params, $mainMetaDataId, metaDataType) {
    if (typeof(params) === 'undefined') {
        params = false;
    }

    if (typeof $mainMetaDataId === 'undefined') {
        $mainMetaDataId = '';
    }

    $.ajax({
        type: 'post',
        url: 'mdmeta/checkLock',
        data: { metaDataId: metaDataId },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({animate: true});
        },
        success: function(passData) {

            if (passData.isLocked == 'true' || passData.isLocked == true) {

                var $dialogName = 'dialog-meta-password';
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $('#' + $dialogName);
                $dialog.empty().append(passData.html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: passData.title,
                    width: 500,
                    height: 'auto',
                    modal: true,
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [{
                        text: passData.close_btn,
                        class: 'btn blue-madison btn-sm',
                        click: function() {
                            $dialog.dialog('close');
                        }
                    }]
                });
                $dialog.dialog('open');
                Core.unblockUI();

            } else {
                Core.unblockUI();
                if (typeof(params.isDialog) === 'undefined') {
                    redirectEditMetaData(metaDataId, folderId);
                } else {
                    if (typeof(params.dataView) !== 'undefined') {
                        /*if (passData.hasOwnProperty('isDev')) {
                            metaFullOptions(metaDataId, folderId, element, true);
                        } else {
                            dataViewEditMetaData(metaDataId, folderId, $mainMetaDataId, metaDataType);
                        }*/
                        dataViewEditMetaData(metaDataId, folderId, $mainMetaDataId, metaDataType);
                    } else if (typeof(params.businessProcess) !== 'undefined') {
                        if (passData.hasOwnProperty('isDev') && !params.hasOwnProperty('statement')) {
                            metaFullOptions(metaDataId, folderId, element, true);
                        } else {
                            businessProcessEditMetaData(metaDataId);
                        }
                    } else {
                        redirectEditMetaData(metaDataId, folderId);
                    }
                }
            }
        },
        error: function() { alert('Error'); }
    });
    clearConsole();
}

function businessProcessEditMetaData(metaDataId) {
    var $dialogName = 'dialog-main-meta-edit-' + metaDataId;
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);

    $.ajax({
        type: 'post',
        url: 'mdmetadata/editMetaBySystem',
        data: { metaDataId: metaDataId, isDialog: true, isProcess: true },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({animate: true});
            if (!$().iconpicker) {
                $.cachedScript('assets/custom/addon/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js?v=1').done(function() {      
                    $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>');
                });
            }
        },
        success: function(dataHtml) {

            if (dataHtml.status != 'success') {
                Core.unblockUI();
                PNotify.removeAll();
                new PNotify({
                    title: dataHtml.status,
                    text: dataHtml.message,
                    type: dataHtml.status,
                    sticker: false
                });
                return;
            }

            $dialog.empty().append('<div class="col-md-12">' + dataHtml.mainHtml + '</div>');
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: dataHtml.title,
                width: 1000,
                height: "auto",
                modal: true,
                beforeClose: function() {
                    
                    if ($dialog.data('can-close')) {
                        $dialog.removeData('can-close');
                        return true;
                    }
     
                    var $form = $("#editMetaSystemForm", "#" + $dialogName);
    
                    if ($form.length && $form.hasAttr('data-changed') && $form.attr('data-changed') == '1') {

                        var dialogNameConfirm = '#dialog-metaedit-confirm';
                        if (!$(dialogNameConfirm).length) {
                            $('<div id="' + dialogNameConfirm.replace('#', '') + '"></div>').appendTo('body');
                        }
                        var $dialogConfirm = $(dialogNameConfirm);

                        $dialogConfirm.html(plang.get('msg_sure_leave_this_page'));
                        $dialogConfirm.dialog({
                            cache: false,
                            resizable: true,
                            bgiframe: true,
                            autoOpen: false,
                            title: plang.get('msg_title_confirm'), 
                            width: 300,
                            height: 'auto',
                            modal: true,
                            buttons: [
                                {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function() {
                                    $dialogConfirm.dialog('close');
                                    $dialog.data('can-close', true);
                                    $dialog.dialog('close');
                                }},
                                {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                    $dialogConfirm.dialog('close');
                                }}
                            ]
                        });
                        $dialogConfirm.dialog('open');
                        
                        return false;

                    } else {
                        return true;
                    }
                },
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{
                        text: dataHtml.save_btn,
                        class: 'btn btn-sm green-meadow bp-btn-save',
                        click: function() {
                            $("#editMetaSystemForm", "#" + $dialogName).validate({ errorPlacement: function() {} });

                            if ($("#editMetaSystemForm", "#" + $dialogName).valid()) {
                                $('#editMetaSystemForm').ajaxSubmit({
                                    type: 'post',
                                    url: 'mdmetadata/updateMetaSystemModuleForm',
                                    dataType: 'json',
                                    beforeSend: function() {
                                        Core.blockUI({message: plang.get('msg_saving_block'), boxed: true});
                                    },
                                    success: function(data) {
                                        PNotify.removeAll();
                                        if (data.status === 'success') {
                                            new PNotify({
                                                title: data.status,
                                                text: metaSuccessMessage(data.message, metaDataId),
                                                type: data.status,
                                                sticker: false, 
                                                delay: 4000
                                            });
                                            $dialog.data('can-close', true);
                                            $dialog.dialog('close');
                                        } else {

                                            if (data.status === 'locked') {
                                                lockedRequestMeta(data);
                                            } else {
                                                if (typeof data.fieldName !== 'undefined') {
                                                    $("input[name='" + data.fieldName + "']", 'form#editMetaSystemForm').addClass("error");
                                                }
                                                new PNotify({
                                                    title: data.status,
                                                    text: data.message,
                                                    type: data.status,
                                                    sticker: false
                                                });
                                            }
                                        }
                                        Core.unblockUI();
                                    }
                                });
                            }
                        }
                    },
                    {
                        text: dataHtml.close_btn,
                        class: 'btn blue-madison btn-sm',
                        click: function() {
                            $dialog.dialog('close');
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
            $dialog.dialogExtend('maximize');
            $dialog.dialog('open');
            Core.initAjax($dialog);
            Core.unblockUI();
        },
        error: function() { alert('Error'); }
    });
}

function dataViewEditMetaData(metaDataId, folderId, $mainMetaDataId, metaDataType) {
    var $dialogName = 'dialog-main-meta-edit';
    var postData = { metaDataId: metaDataId, folderId: folderId, isDialog: true };
    if (typeof metaDataType != 'undefined') {
        postData = { metaDataId: metaDataId, folderId: folderId, isBack: '1', isDialog: false };
    }
    
    $.ajax({
        type: 'post',
        url: 'mdmetadata/editMetaBySystem',
        data: postData,
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({animate: true});
            if (!$().iconpicker) {
                $.cachedScript('assets/custom/addon/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js?v=1').done(function() {      
                    $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>');
                });
            }
        },
        success: function(dataHtml) {

            if (dataHtml.status != 'success') {
                Core.unblockUI();
                PNotify.removeAll();
                new PNotify({
                    title: dataHtml.status,
                    text: dataHtml.message,
                    type: dataHtml.status,
                    sticker: false
                });
                return;
            }

            if (typeof $mainMetaDataId !== 'undefined' && $mainMetaDataId != '') {
                if (typeof metaDataType != 'undefined' && metaDataType == 'reportTemplate')
                    $('.viewer-report-template-' + $mainMetaDataId).html('<div class="col-md-12">' + dataHtml.mainHtml + '</div>');
                else if (typeof metaDataType != 'undefined' && metaDataType == 'dashboard')
                    $('.viewer-dashboard-' + $mainMetaDataId).html('<div class="col-md-12">' + dataHtml.mainHtml + '</div>');

                Core.unblockUI();
                return false;
            }

            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }

            var $dialog = $('#' + $dialogName);
            $dialog.empty().append('<div class="col-md-12">' + dataHtml.mainHtml + '</div>');
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: dataHtml.title,
                width: 1000,
                height: "auto",
                modal: true,
                beforeClose: function() {
                    
                    if ($dialog.data('can-close')) {
                        $dialog.removeData('can-close');
                        return true;
                    }
     
                    var $form = $("#editMetaSystemForm", "#" + $dialogName);
    
                    if ($form.length && $form.hasAttr('data-changed') && $form.attr('data-changed') == '1') {

                        var dialogNameConfirm = '#dialog-metaedit-confirm';
                        if (!$(dialogNameConfirm).length) {
                            $('<div id="' + dialogNameConfirm.replace('#', '') + '"></div>').appendTo('body');
                        }
                        var $dialogConfirm = $(dialogNameConfirm);

                        $dialogConfirm.html(plang.get('msg_sure_leave_this_page'));
                        $dialogConfirm.dialog({
                            cache: false,
                            resizable: true,
                            bgiframe: true,
                            autoOpen: false,
                            title: plang.get('msg_title_confirm'), 
                            width: 300,
                            height: 'auto',
                            modal: true,
                            buttons: [
                                {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function() {
                                    $dialogConfirm.dialog('close');
                                    $dialog.data('can-close', true);
                                    $dialog.dialog('close');
                                }},
                                {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                    $dialogConfirm.dialog('close');
                                }}
                            ]
                        });
                        $dialogConfirm.dialog('open');
                        
                        return false;

                    } else {
                        return true;
                    }
                },
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{
                        text: dataHtml.save_btn,
                        class: 'btn btn-sm green-meadow bp-btn-save',
                        click: function() {
                            $("#editMetaSystemForm", "#" + $dialogName).validate({ errorPlacement: function() {} });

                            if ($("#editMetaSystemForm", "#" + $dialogName).valid()) {
                                $('#editMetaSystemForm').ajaxSubmit({
                                    type: 'post',
                                    url: 'mdmetadata/updateMetaSystemModuleForm',
                                    dataType: 'json',
                                    beforeSend: function() {
                                        Core.blockUI({message: plang.get('msg_saving_block'), boxed: true});
                                    },
                                    success: function(data) {
                                        PNotify.removeAll();

                                        if (data.status === 'success') {
                                            new PNotify({
                                                title: data.status,
                                                text: metaSuccessMessage(data.message, metaDataId),
                                                type: data.status,
                                                sticker: false, 
                                                delay: 4000
                                            });
                                            $dialog.data('can-close', true);
                                            $dialog.dialog('close');

                                            if (typeof $mainMetaDataId === 'undefined' || ($mainMetaDataId == '' || $mainMetaDataId == null)) {
                                                
                                                if (isFunction(window['dataViewer_' + metaDataId])) {    
                                                    Core.blockUI({message: 'Reload...', boxed: true});

                                                    window['dataViewer_' + metaDataId](this, $("input#valueViewerType", window['objectWindow_' + metaDataId]).val(), metaDataId, function(){
                                                        Core.unblockUI();
                                                    });
                                                } else {
                                                    Core.unblockUI();
                                                }

                                            } else {
                                                window['objectDashboardView_' + $mainMetaDataId]();
                                                Core.unblockUI();
                                            }

                                        } else {

                                            if (data.status === 'locked') {
                                                lockedRequestMeta(data);
                                            } else {
                                                if (typeof data.fieldName !== 'undefined') {
                                                    $("input[name='" + data.fieldName + "']", 'form#editMetaSystemForm').addClass("error");
                                                }
                                                new PNotify({
                                                    title: data.status,
                                                    text: data.message,
                                                    type: data.status,
                                                    sticker: false
                                                });
                                            }

                                            Core.unblockUI();
                                        }
                                    }
                                });
                            }
                        }
                    },
                    {
                        text: dataHtml.close_btn,
                        class: 'btn blue-madison btn-sm',
                        click: function() {
                            $dialog.dialog('close');
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
            $dialog.dialogExtend('maximize');
            $dialog.dialog('open');

            Core.initAjax($dialog);

            Core.unblockUI();
        },
        error: function() { alert('Error'); }
    });
}
function metaSuccessMessage(msg, metaId) {
    return msg + '<br /><br /><a href="javascript:;" class="btn green btn-xs" onclick="metaPHPExportById(\''+metaId+'\');"><i class="far fa-download"></i> '+plang.get('download_btn')+'</a>';
}

function redirectEditMetaData(metaDataId, folderId) {
    var $editFormMeta = $('#editFormMeta');
    $.ajax({
        type: 'post',
        url: 'mdmetadata/editMetaBySystem',
        data: { metaDataId: metaDataId, folderId: folderId },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({animate: true});
            if (!$().iconpicker) {
                $.cachedScript('assets/custom/addon/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js?v=1').done(function() {      
                    $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>');
                });
            }
        },
        success: function(data) {

            if (data.status == 'success') {
                $editFormMeta.empty().append(data.mainHtml);
                $('#renderMeta, #viewFormMeta, #editFormGroup, #editFormFolders').hide();
                $editFormMeta.show();
                $('.scroll-to-top').trigger('click');
            } else {
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
            }

            Core.unblockUI();
        },
        error: function() { alert('Error'); }
    }).done(function() {
        Core.initAjax($editFormMeta);
    });
}

function addFolder(folderId) {
    var $editFormFolder = $("#editFormFolder");
    $.ajax({
        type: 'post',
        url: 'mdmetadata/addFolder',
        data: { folderId: folderId },
        beforeSend: function() {
            Core.blockUI({animate: true});
        },
        success: function(data) {
            $editFormFolder.empty().append(data);
            $("#renderMeta, #viewFormMeta, #editFormMeta, #editFormGroup").hide();
            $editFormFolder.show();
            Core.unblockUI();
        },
        error: function() { alert('Error'); }
    }).done(function() {
        Core.initAjax($editFormFolder);
    });
}

function createFolderForm(elem) {
    var $addFolderForm = $('#addFolder-form');
    $addFolderForm.validate({ errorPlacement: function() {} });
    if ($addFolderForm.valid()) {
        $.ajax({
            type: 'post',
            url: 'mdmetadata/createFolder',
            data: $addFolderForm.serialize(),
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: plang.get('msg_saving_block'), boxed: true});
            },
            success: function(data) {
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
                if (data.status === 'success') {
                    $('#viewFormMeta, #editFormMeta, #editFormGroup, #editFormFolder').empty().hide();
                    if (data.folderId == '' || data.folderId == 'null' || data.folderId == null) {
                        metaDataDefault();
                    } else {
                        refreshList(data.folderId, 'folder');
                    }
                    $('#renderMeta').show();
                }
                Core.unblockUI();
            }
        });
    }
}

function editFormFolder(folderId, parentFolderId, element) {
    var $editFormFolder = $('#editFormFolder');
    $.ajax({
        type: 'post',
        url: 'mdmetadata/editFolder',
        data: { folderId: folderId, parentFolderId: parentFolderId },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({animate: true});
        },
        success: function(data) {
            $editFormFolder.empty().append(data.mainHtml);
            $('#renderMeta, #viewFormMeta, #editFormMeta, #editFormGroup').hide();
            $editFormFolder.show();
            Core.unblockUI();
            $('.scroll-to-top').trigger('click');
        },
        error: function() { alert('Error'); }
    }).done(function() {
        Core.initAjax($editFormFolder);
    });
}

function updateFolderForm(elem) {
    var $editFolderForm = $('#editFolder-form');
    $editFolderForm.validate({ errorPlacement: function() {} });
    if ($editFolderForm.valid()) {
        $.ajax({
            type: 'post',
            url: 'mdmetadata/updateFolder',
            data: $editFolderForm.serialize(),
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: plang.get('msg_saving_block'), boxed: true});
            },
            success: function(data) {
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
                if (data.status === 'success') {
                    $('#viewFormMeta, #editFormMeta, #editFormGroup, #editFormFolder').empty().hide();
                    $('button.refreshBtn').trigger("click");
                    $('#renderMeta').show();
                }
                Core.unblockUI();
            }
        });
    }
}

function metaThumbChoose(elem,type,diagramtype) {

    $.ajax({
        type: 'post',
        url: 'dashboard/allimages',
        data:{ charttype: type, diagram:diagramtype },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: plang.get('msg_saving_block'), boxed: true});
        },
        success: function(data) {
            PNotify.removeAll();
            
            var $dialogName = 'dialog-dashboardview';
            
            if (!$($dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            var _html = '<div class="w-100">'; 
                    _html += '<h3> ' +diagramtype +' </h3><span>Diagram style and Settings</span>'; 
                    _html += '<div id="diagrame_image" class="carousel slide" data-ride="carousel">'; 
                    _html += '<div class="carousel-inner row">'; 
                    _html +=  data.Html; 
                    _html += '</div>'; 
                    _html += '</div>'; 
                    _html += '</div>';

                $("#" + $dialogName).empty().append(_html);
                $("#" + $dialogName).dialog({
                    appendTo: "body",
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: type,
                    width: 600,
                    minWidth: 600,
                    height: 500,
                    modal: false,
                    close: function() {
                        $("#" + $dialogName).empty().dialog('destroy').remove();
                    }, 
                    buttons: [
                        {text: 'Хаах', class: 'btn btn-sm blue-hoki', click: function () {
                            $("#" + $dialogName).dialog('close');
                        }}
                    ]
                });
                $("#" + $dialogName).dialog('open');
                Core.unblockUI();
            }
        });
}

function metaIconChoose(elem) {
    var $dialogName = 'dialog-metaicon';
    if (!$("#" + $dialogName).length) { $('<div id="' + $dialogName + '"></div>').appendTo('body'); }
    var $dialog = $('#' + $dialogName);
    var parent = $(elem).parent();
    var metaChoosedIcon = parent.find(".metaChoosedIcon");
    var metaIconId = metaChoosedIcon.find("input[name=metaIconId]").val();

    $.ajax({
        type: 'post',
        url: 'mdmetadata/iconChoose',
        data: { metaIconId: metaIconId },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({animate: true});
        },
        success: function(data) {
            $dialog.empty().append(data.Html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: 850,
                height: 'auto',
                modal: true,
                open: function() {
                    disableScrolling();
                },
                close: function() {
                    enableScrolling();
                    $dialog.empty().dialog('close');
                },
                buttons: [{
                    text: data.close_btn,
                    class: 'btn btn-sm blue-hoki',
                    click: function() {
                        $dialog.dialog('close');
                    }
                }]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        },
        error: function() { alert('Error'); }
    }).done(function() {
        Core.initAjax($dialog);
    });
}

function metaIconSelect(iconId, iconCode) {
    var $metaChoosedIcon = $('.metaChoosedIcon');
    $metaChoosedIcon.find('.iconpath').html('<img src="assets/core/global/img/metaicon/small/' + iconCode + '"/>');
    $metaChoosedIcon.find('input[name=metaIconId]').val(iconId);
}
function metaExport(rowId, exportType) {
    Core.blockUI({
        boxed: true,
        message: 'Exporting...'
    });
    $.fileDownload(URL_APP + 'mdmeta/metaFolderExportFile', {
        httpMethod: 'POST',
        data: {
            rowId: rowId,
            exportType: exportType,
            isOverride: 0
        }
    }).done(function() {
        PNotify.removeAll();
        new PNotify({
            title: 'Success',
            text: 'Successfuly',
            type: 'success',
            sticker: false,
            hide: true,
            addclass: pnotifyPosition,
            delay: 1000000000
        });
        Core.unblockUI();
    }).fail(function(msg, url) {
        PNotify.removeAll();
        new PNotify({
            title: 'Error',
            text: msg,
            type: 'error',
            sticker: false,
            hide: true,
            addclass: pnotifyPosition,
            delay: 1000000000
        });
        Core.unblockUI();
    });
}

function groupCreate(folderId) {
    $.ajax({
        type: 'post',
        url: 'mdmeta/groupCreate',
        data: { folderId: folderId },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            PNotify.removeAll();
            if (data.status === 'error') {
                new PNotify({
                    title: 'Error',
                    text: data.message,
                    type: 'error',
                    sticker: false
                });
            } else {
                var $dialogName = 'dialog-group-create';
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $("#" + $dialogName);
                $dialog.empty().append(data.html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: 600,
                    minWidth: 600,
                    height: "auto",
                    modal: true,
                    close: function() {
                        $dialog.empty().dialog('close');
                    },
                    buttons: [{
                            text: data.save_btn,
                            class: 'btn btn-sm green',
                            click: function() {
                                $("#generate-MetaGroup-form", "#" + $dialogName).validate({ errorPlacement: function() {} });
                                if ($("#generate-MetaGroup-form", "#" + $dialogName).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: 'mdmeta/generateEntityToGroup',
                                        data: $("#generate-MetaGroup-form").serialize(),
                                        dataType: 'json',
                                        beforeSend: function() {
                                            Core.blockUI({message: 'Loading...', boxed: true});
                                        },
                                        success: function(data) {
                                            PNotify.removeAll();
                                            new PNotify({
                                                title: data.status,
                                                text: data.message,
                                                type: data.status,
                                                sticker: false
                                            });
                                                
                                            if (data.status === 'success') {
                                                $dialog.dialog('close');
                                            } 
                                            Core.unblockUI();
                                        },
                                        error: function() { alert('Error'); }
                                    });
                                }
                            }
                        },
                        {
                            text: data.close_btn,
                            class: 'btn btn-sm blue-hoki',
                            click: function() {
                                $dialog.dialog('close');
                            }
                        }
                    ]
                });
                $dialog.dialog('open');
                Core.initAjax($dialog);
            }
            Core.unblockUI();
        },
        error: function() { alert('Error'); }
    });
}

function structureCreate(folderId) {
    $.ajax({
        type: 'post',
        url: 'mdmeta/structureCreate',
        data: { folderId: folderId },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            PNotify.removeAll();
            if (data.status === 'error') {
                new PNotify({
                    title: 'Error',
                    text: data.message,
                    type: 'error',
                    sticker: false
                });
            } else {
                var $dialogName = 'dialog-group-structure';
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $("#" + $dialogName);
                $dialog.empty().append(data.html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: 600,
                    height: "auto",
                    modal: true,
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [{
                            text: data.save_btn,
                            class: 'btn btn-sm green',
                            click: function() {
                                $("#generate-structure-form", "#" + $dialogName).validate({ errorPlacement: function() {} });
                                if ($("#generate-structure-form", "#" + $dialogName).valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: 'mdmeta/generateTableToStructure',
                                        data: $("#generate-structure-form").serialize(),
                                        dataType: 'json',
                                        beforeSend: function() {
                                            Core.blockUI({message: 'Loading...', boxed: true});
                                        },
                                        success: function(data) {
                                            PNotify.removeAll();
                                            new PNotify({
                                                title: data.status,
                                                text: data.message,
                                                type: data.status,
                                                sticker: false
                                            });
                                                
                                            if (data.status === 'success') {
                                                $dialog.dialog('close');
                                            } 
                                            Core.unblockUI();
                                        },
                                        error: function() { alert('Error'); }
                                    });
                                }
                            }
                        },
                        {
                            text: data.close_btn,
                            class: 'btn btn-sm blue-hoki',
                            click: function() {
                                $dialog.dialog('close');
                            }
                        }
                    ]
                });
                $dialog.dialog('open');
                Core.initAjax($dialog);
            }
            Core.unblockUI();
        },
        error: function(data) {
            new PNotify({
                title: 'Error',
                text: data.message,
                type: 'error',
                sticker: false
            });
        }
    });
}

function metaImport(rowId, importType) {
    var $dialogName = 'dialog-meta-import';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    $.ajax({
        type: 'post',
        url: 'mdmeta/metaImport',
        data: { rowId: rowId, importType: importType },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function(data) {
            PNotify.removeAll();
            if (data.status === 'success') {
                $dialog.empty().append(data.Html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: '90%',
                    minWidth: '90%',
                    height: "auto",
                    modal: true,
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [{
                            text: data.import_btn,
                            class: 'btn btn-sm green',
                            click: function() {
                                $("#metaImportForm").validate({ errorPlacement: function() {} });
                                if ($("#metaImportForm").valid()) {
                                    $('#metaImportForm').ajaxSubmit({
                                        type: 'post',
                                        url: 'mdmeta/metaImportFile',
                                        dataType: 'json',
                                        beforeSend: function() {
                                            Core.blockUI({
                                                message: 'Түр хүлээнэ үү',
                                                boxed: true
                                            });
                                        },
                                        success: function(data) {

                                            PNotify.removeAll();

                                            if (data.status === 'success') {

                                                if (data.hasOwnProperty('result')) {

                                                    var metaImportResultHtml = "<thead><tr><th>№</th><th>Query</th><th>Error</th></tr></thead><tbody>";

                                                    $.each(data.result, function(i, v) {
                                                        metaImportResultHtml += '<tr><td>' + v.query + '</td><td>' + v.error + '</td></tr>';
                                                    });
                                                    metaImportResultHtml += '</tbody>';

                                                    $('#importResultTable').html(metaImportResultHtml);

                                                } else {
                                                    new PNotify({
                                                        title: 'Success',
                                                        text: data.message,
                                                        type: 'success',
                                                        sticker: false,
                                                        hide: true,
                                                        addclass: pnotifyPosition,
                                                        delay: 1000000000
                                                    });
                                                    $dialog.dialog('close');
                                                }

                                            } else {
                                                new PNotify({
                                                    title: 'Error',
                                                    text: data.message,
                                                    type: 'error',
                                                    sticker: false,
                                                    hide: true,
                                                    addclass: pnotifyPosition,
                                                    delay: 1000000000
                                                });
                                            }
                                            Core.unblockUI();
                                        }
                                    });
                                }
                            }
                        },
                        {
                            text: data.close_btn,
                            class: 'btn btn-sm blue-hoki',
                            click: function() {
                                $dialog.dialog('close');
                            }
                        }
                    ]
                });

                /*if (importType !== 'upgrade') {
                    $("#" + $dialogName).dialog('option', 'position', {my: 'top', at: 'top+50'});
                }*/

                $dialog.dialog('open');
            } else {
                new PNotify({
                    title: 'Error',
                    text: data.message,
                    type: 'error',
                    sticker: false
                });
            }
            Core.unblockUI();
        },
        error: function() {
            alert('Error');
        }
    }).done(function() {
        Core.initAjax($dialog);
    });
}
function importWorkflow(elem, processMetaDataId, dataViewId, selectedRow, paramData) {

    $.ajax({
        type: 'post',
        url: 'mdprocessflow/importWorkflowForm',
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                animate: true
            });
        },
        success: function(data) {
            PNotify.removeAll();

            if (data.status == 'success') {

                var $dialogName = 'dialog-meta-import';
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $("#" + $dialogName);

                $dialog.empty().append(data.Html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 700,
                    minWidth: 700,
                    height: "auto",
                    modal: true,
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [{
                            text: data.import_btn,
                            class: 'btn btn-sm green',
                            click: function() {
                                $("#workflowImportForm").validate({ errorPlacement: function() {} });
                                if ($("#workflowImportForm").valid()) {
                                    $('#workflowImportForm').ajaxSubmit({
                                        type: 'post',
                                        url: 'mdprocessflow/importWorkflow',
                                        dataType: 'json',
                                        beforeSend: function() {
                                            Core.blockUI({
                                                message: 'Түр хүлээнэ үү',
                                                boxed: true
                                            });
                                        },
                                        success: function(data) {

                                            PNotify.removeAll();
                                            new PNotify({
                                                title: data.status,
                                                text: data.message,
                                                type: data.status,
                                                sticker: false,
                                                hide: true,
                                                addclass: pnotifyPosition,
                                                delay: 1000000000
                                            });

                                            if (data.status === 'success') {
                                                $dialog.dialog('close');
                                                dataViewReload(dataViewId)
                                            }

                                            Core.unblockUI();
                                        }
                                    });
                                }
                            }
                        },
                        {
                            text: data.close_btn,
                            class: 'btn btn-sm blue-hoki',
                            click: function() {
                                $dialog.dialog('close');
                            }
                        }
                    ]
                });
                $dialog.dialog('open');
            } else {
                new PNotify({
                    title: 'Error',
                    text: data.message,
                    type: 'error',
                    sticker: false
                });
            }
            Core.unblockUI();
        },
        error: function() {
            alert('Error');
        }
    });
}
function popupConnectGeneralLedger(elem, processMetaDataId, dataViewId, selectedRow, isMulti, connectType) {
    var isMultiBoolean = false;

    if (typeof isMulti !== 'undefined') {
        isMultiBoolean = true;
    }

    if (selectedRow.length > 1 && isMultiBoolean == false) {
        popupMultiConnectGeneralLedger(elem, processMetaDataId, dataViewId, selectedRow);
        return;
    }

    if (typeof connectType == 'undefined') {
        var connectType = '';
    }

    if (isMultiBoolean == false) {
        var glData = { processMetaDataId: processMetaDataId, dataViewId: dataViewId, selectedRow: selectedRow, connectType: connectType };
    } else {
        var glData = { processMetaDataId: processMetaDataId, dataViewId: dataViewId, selectedRow: selectedRow, isMulti: 1, connectType: connectType };
    }

    $.ajax({
        type: 'post',
        url: 'mdgl/popupConnectGL',
        data: glData,
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {

            if (data.status == 'success') {

                var dialogName = '#dialog-connectgl-' + processMetaDataId;
                if (!$(dialogName).length) {
                    $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                }
                var $dialog = $(dialogName), dialogWidth = 1180;
                
                if (connectType != '3') {
                    var windowWidth = $(window).width(), dialogWidth = 1350;
                    if (dialogWidth > windowWidth) {
                        dialogWidth = windowWidth - 10;
                    }
                }

                $dialog.empty().append(data.html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: dialogWidth, 
                    height: 'auto',
                    modal: true,
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [{
                            text: data.save_btn,
                            class: 'btn green-meadow btn-sm bp-btn-save',
                            click: function() {
                                if (connectType == '3') {
                                    var saveUrl = 'mdgl/saveMultiGlEntry';
                                } else {
                                    var saveUrl = 'mdgl/createGlEntry';
                                }
                                $('form#glEntryForm', dialogName).ajaxSubmit({
                                    type: 'post',
                                    url: saveUrl,
                                    dataType: 'json',
                                    beforeSubmit: function(formData, jqForm, options) {
                                        if (connectType == '3') {
                                            formData.push(
                                                {name: 'selectedRows', value: JSON.stringify(selectedRow)}, 
                                                {name: 'glConnectType', value: 3}, 
                                                {name: 'bookTypeId', value: data.bookTypeId}, 
                                                {name: 'processId', value: data.processId}, 
                                                {name: 'objectId', value: data.objectId}, 
                                                {name: 'processMetaDataId', value: processMetaDataId}, 
                                                {name: 'dataViewId', value: dataViewId}
                                            );
                                        }
                                    },
                                    beforeSend: function() {
                                        Core.blockUI({message: 'Loading...', boxed: true});
                                    },
                                    success: function(data) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: data.status,
                                            text: data.message,
                                            type: data.status,
                                            sticker: false
                                        });

                                        if (data.status == 'success') {
                                            $dialog.dialog('close');

                                            var $elem = $(elem);
                                            if (typeof $elem !== 'undefined' && $elem.parents('.dataViewUseBasketViewWrap').length) {
                                                $('#objectdatagrid-' + $elem.parents('.dataViewUseBasketViewWrap').data('basketid')).datagrid('loadData', []);
                                                $('.div-objectdatagrid-' + $elem.parents('.dataViewUseBasketViewWrap').data('basketid')).find('.basket-data-count-' + $elem.parents('.dataViewUseBasketViewWrap').data('basketid')).remove();
                                            }

                                            dataViewReload(dataViewId);

                                        } else if (data.status == 'info') {

                                            $dialog.dialog('close');
                                            glMultiConnectInfoDialog(data.resultList);
                                            dataViewReload(dataViewId);
                                        }

                                        Core.unblockUI();
                                    }
                                });
                            }
                        },
                        {
                            text: data.close_btn,
                            class: 'btn blue-madison btn-sm',
                            click: function() {
                                $dialog.dialog('close');
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
                $dialog.dialog('open');

            } else {
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    addclass: pnotifyPosition,
                    sticker: false
                });
            }

            Core.unblockUI();
        },
        error: function() {
            alert('Error');
            Core.unblockUI();
        }
    });
}

function popupMultiConnectGeneralLedger(elem, processMetaDataId, dataViewId, selectedRow) {

    var $cDialogName = '#dialog-connectgl-confirm';
    if (!$($cDialogName).length) {
        $('<div id="' + $cDialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    var $cDialog = $($cDialogName);  

    $.ajax({
        type: 'post',
        url: 'mdgl/confirmMultiGLConnect',
        data: { selectedRow: selectedRow },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(cData) {

            if (cData.status == 'success') {

                $cDialog.empty().append(cData.html);

                var buildTbl = dvSelectedRowsSumCount(window['objectdatagrid_' + dataViewId], selectedRow, selectedRow.length);
                $cDialog.find('#dv-selection-sum').append(buildTbl);

                $cDialog.dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: cData.title,
                    width: 420,
                    height: 'auto',
                    modal: true,
                    close: function() {
                        $cDialog.empty().dialog('destroy').remove();
                    },
                    buttons: [{
                            text: cData.continue_btn,
                            class: 'btn green-meadow btn-sm',
                            click: function() {

                                var glConnectTypeVal = $cDialog.find("input[name=glConnectType]:checked").val();

                                $cDialog.dialog('close');

                                if (glConnectTypeVal == '1' || glConnectTypeVal == '3') {
                                    popupConnectGeneralLedger(elem, processMetaDataId, dataViewId, selectedRow, true, glConnectTypeVal);
                                } else {
                                    multiGLConnect(elem, processMetaDataId, dataViewId, selectedRow, glConnectTypeVal);
                                }
                            }
                        },
                        {
                            text: cData.close_btn,
                            class: 'btn blue-madison btn-sm',
                            click: function() {
                                $cDialog.dialog('close');
                            }
                        }
                    ]
                });
                $cDialog.dialog('open');

            } else {
                PNotify.removeAll();
                new PNotify({
                    title: cData.status,
                    text: cData.message,
                    type: cData.status,
                    addclass: pnotifyPosition,
                    sticker: false
                });
            }
        }

    }).done(function() {
        Core.unblockUI();
        Core.initUniform($cDialog);
    });

    return;
}

function multiGLConnect(elem, processMetaDataId, dataViewId, selectedRow, connectType) {

    var dialogName = '#dialog-connectgl-' + processMetaDataId;
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    var $dialog = $(dialogName);
    var incostAmt = 0, outcostAmt = 0;
    var gldata = { processMetaDataId: processMetaDataId, dataViewId: dataViewId, selectedRow: selectedRow, connectType: connectType, isMulti: 1 };
    for (var i = 0; i < selectedRow.length; i++) {
        if (selectedRow[i].hasOwnProperty('debitamount') && selectedRow[i].hasOwnProperty('creditamount')) {
            incostAmt += Number(selectedRow[i]['debitamount']);
            outcostAmt += Number(selectedRow[i]['creditamount']);
        } else if (selectedRow[i].hasOwnProperty('totalincostamount') && selectedRow[i].hasOwnProperty('totaloutcostamount')) {
            incostAmt += Number(selectedRow[i]['totalincostamount']);
            outcostAmt += Number(selectedRow[i]['totaloutcostamount']);
        }
    }
    gldata['totalDebitAmount'] = incostAmt;
    gldata['totalCreditAmount'] = outcostAmt;      

    $.ajax({
        type: 'post',
        url: 'mdgl/popupMultiConnectGL',
        data: gldata,
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {

            if (data.status == 'success') {

                $dialog.empty().append(data.html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: 1000,
                    height: 'auto',
                    modal: true,
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [{
                            text: data.save_btn,
                            class: 'btn green-meadow btn-sm',
                            click: function() {
                                $('form#multiGL-form', dialogName).ajaxSubmit({
                                    type: 'post',
                                    url: 'mdgl/saveMultiGlEntry',
                                    dataType: 'json',
                                    beforeSubmit: function(formData, jqForm, options) {
                                        formData.push({ name: 'selectedRows', value: JSON.stringify(selectedRow) }, { name: 'glConnectType', value: connectType }, { name: 'processMetaDataId', value: processMetaDataId }, { name: 'dataViewId', value: dataViewId });
                                    },
                                    beforeSend: function() {
                                        Core.blockUI({message: 'Loading...', boxed: true});
                                    },
                                    success: function(data) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: data.status,
                                            text: data.message,
                                            type: data.status,
                                            sticker: false
                                        });

                                        if (data.status == 'info') {

                                            glMultiConnectInfoDialog(data.resultList);
                                            dataViewReload(dataViewId);

                                        } else if (data.status == 'success' || connectType == '2') {
                                            dataViewReload(dataViewId);
                                        }
                                        
                                        if (data.status == 'success') {
                                            $dialog.dialog('close');
                                        }

                                        Core.unblockUI();
                                    }
                                });
                            }
                        },
                        {
                            text: data.close_btn,
                            class: 'btn blue-madison btn-sm',
                            click: function() {
                                $dialog.dialog('close');
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
                $dialog.dialog('open');

            } else {
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
            }

            Core.unblockUI();
        },
        error: function() {
            alert('Error');
            Core.unblockUI();
        }
    });
}
function metaDataDelete(metaDataId, refreshMetaData, metaDataType) {

    $.ajax({
        type: 'post',
        url: 'mdmeta/metaDelete',
        data: { metaDataId: metaDataId },
        dataType: 'json', 
        beforeSend: function() {
            Core.blockUI({animate: true});
        },
        success: function(data) {
            
            if (data.status === 'locked') {
                lockedRequestMeta(data);
                Core.unblockUI();
                return;
            }
            
            var $dialogName = 'dialog-confirm';
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            var $dialog = $("#" + $dialogName);
            $dialog.empty().append(data.Html);
            $dialog.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: 500,
                height: "auto",
                modal: true,
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{
                        text: data.yes_btn,
                        class: 'btn green-meadow btn-sm',
                        click: function() {
                            if (metaDataId !== '') {
                                $.ajax({
                                    type: 'post',
                                    url: 'mdmetadata/deleteMetaData',
                                    data: { metaDataId: metaDataId, replaceMetaDataId: $("input[name='replaceMetaDataId']", "#" + $dialogName).val() },
                                    dataType: 'json',
                                    beforeSend: function() {
                                        Core.blockUI({animate: true});
                                    },
                                    success: function(dataSub) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: dataSub.status,
                                            text: dataSub.message,
                                            type: dataSub.status,
                                            sticker: false
                                        });
                                            
                                        if (dataSub.status === 'success') {
                                            $("button.refreshBtn").trigger("click");
                                            if (typeof refreshMetaData !== 'undefined') {
                                                if (metaDataType == 'dashboard')
                                                    window['objectDashboardView_' + refreshMetaData]();
                                                else {
                                                    if (metaDataType == 'reportTemplate')
                                                        window['objectReportTemplateView_' + refreshMetaData]();
                                                }
                                            }
                                        } 
                                        $dialog.dialog('close');
                                        Core.unblockUI();
                                    },
                                    error: function() {
                                        alert('Error');
                                    }
                                });
                            }
                        }
                    },
                    {
                        text: data.no_btn,
                        class: 'btn blue-madison btn-sm',
                        click: function() {
                            $dialog.dialog('close');
                        }
                    }
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        }
    });
}

function deleteFolder(folderId) {
    var $dialogName = 'dialog-confirm';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $("#" + $dialogName);
    
    $.ajax({
        type: 'post',
        url: 'mdfolder/deleteFolderDialog',
        dataType: 'json',
        data: { folderId: folderId },
        beforeSend: function() {
            Core.blockUI({animate: true});
        },
        success: function(data) {
            $dialog.empty().append(data.Html);
            $dialog.dialog({
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: (data.isParent != 'true' ? 300 : 600),
                height: 'auto',
                modal: true,
                buttons: [{
                        text: data.yes_btn,
                        class: "btn btn-sm green-meadow",
                        click: function() {
                            $.ajax({
                                type: 'post',
                                url: 'mdfolder/deleteFolder',
                                data: { folderId: folderId, moveFolderId: $("input[name='moveFolderId']", "#" + $dialogName).val() },
                                dataType: 'json',
                                beforeSend: function() {
                                    Core.blockUI({animate: true});
                                },
                                success: function(data) {
                                    PNotify.removeAll();
                                    if (data.status === 'success') {
                                        new PNotify({
                                            title: 'Success',
                                            text: data.message,
                                            type: 'success',
                                            sticker: false
                                        });
                                        $("button.refreshBtn").trigger("click");
                                    } else if (data.status === 'child') {
                                        $dialog.dialog('close');
                                        $dialog.dialog({
                                            resizable: false,
                                            bgiframe: true,
                                            autoOpen: false,
                                            title: plang.get('msg_title_confirm'),
                                            width: 400,
                                            height: 'auto',
                                            modal: true,
                                            position: 'center',
                                            buttons: [{
                                                    text: plang.get('yes_btn'),
                                                    class: "btn green-meadow btn-sm",
                                                    click: function() {
                                                        $.ajax({
                                                            type: 'post',
                                                            url: 'mdmetadata/deleteAllFolders',
                                                            data: { folderId: folderId, replaceId: $("input[name='replaceMetaDataId']", "#" + $dialogName).val() },
                                                            dataType: 'json',
                                                            beforeSend: function() {
                                                                Core.blockUI({animate: true});
                                                            },
                                                            success: function(dataChild) {
                                                                PNotify.removeAll();
                                                                new PNotify({
                                                                    title: dataChild.status,
                                                                    text: dataChild.message,
                                                                    type: dataChild.status,
                                                                    sticker: false
                                                                });
                                                                $dialog.dialog('close');
                                                                if (dataChild.status === 'success') {
                                                                    $("button.refreshBtn").trigger("click");
                                                                }
                                                                Core.unblockUI();
                                                            }
                                                        });
                                                    }
                                                },
                                                {
                                                    text: plang.get('no_btn'),
                                                    class: "btn btn-sm blue-madison",
                                                    click: function() {
                                                        $dialog.dialog('close');
                                                    }
                                                }
                                            ]
                                        });
                                        $dialog.html(data.message).dialog('open');
                                    } else {
                                        new PNotify({
                                            title: 'Error',
                                            text: data.message,
                                            type: 'error',
                                            sticker: false
                                        });
                                    }
                                    Core.unblockUI();
                                },
                                error: function() { alert('Error'); }
                            });
                            $dialog.dialog('close');
                        }
                    },
                    {
                        text: data.no_btn,
                        class: "btn btn-sm blue-madison",
                        click: function() {
                            $dialog.dialog('close');
                        }
                    }
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        },
        error: function() { alert('Error'); }
    });
}

function searchFileType(elem) {
    var $inputVal = $(elem).val();
    var $table = $('#main-item-container');
    $table.find('li:not(.back)').each(function(index, row) {
        var $allCells = $(row).find('div.box h4');
        if ($allCells.length > 0) {
            var found = false;
            $allCells.each(function(index, td) {
                var regExp = new RegExp($inputVal, 'i');
                if (regExp.test($(td).text())) {
                    found = true;
                    return false;
                }
            });
            if (found == true)
                $(row).show();
            else
                $(row).hide();
        }
    });
}

function searchType(elem) {
    var $this = $(elem);
    var $searchTypeId = $('input.search_type:checked').val();
    var $thisInput = $('input#search_txt');
    var $thisVal = $.trim($thisInput.val());

    if ($thisVal === '') {
        $thisInput.addClass('error');
    } else {
        $thisInput.removeClass('error');

        var $searchTypeCondition = $('#search_type_condition').val();
        $.cookie('meta_search_type', $searchTypeId);
        $.cookie('meta_search_condition', $searchTypeCondition);

        $.ajax({
            type: 'post',
            url: 'mdmetadata/allTypeSearch',
            data: { value: $thisVal, searchType: $searchTypeId, condition: $searchTypeCondition },
            beforeSend: function() {
                Core.blockUI({target: '#mainRenderDiv', animate: true});
            },
            success: function(data) {
                $('#renderMeta').empty().append(data);
                Core.unblockUI('#mainRenderDiv');
                window.location.hash = '';
            },
            error: function() {
                alert('Error');
            }
        });

        metaIdData = [];
    }
}
function saveBusinessProcessTestCase(elem, processForm) {
    var $formParent = processForm.parent();
    var uniqId = $formParent.attr('data-bp-uniq-id');
    
    if (window['processBeforeSave_' + uniqId](elem) && bpFormValidate(processForm)) {
        
        var dialogId = 'save-bp-testcase-dialog-' + uniqId;
        var $dialog = $('#' + dialogId);
    
        if ($dialog.length === 0) {
        
            $('<div id="' + dialogId + '"></div>').appendTo('body');
            $dialog = $('#' + dialogId);

            $.ajax({
                type: 'post',
                url: 'mdprocess/getTestCaseSaveForm',
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function(data) {
                    $dialog.empty().append(data);
                    $dialog.dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: 'Тест кэйс хадгалах',
                        width: 600,
                        height: 'auto',
                        modal: true,
                        buttons: [{
                            text: plang.get('save_btn'),
                            class: 'btn green-meadow btn-sm',
                            click: function() {
                                
                                var $testCaseForm = $dialog.find('form');
                                $testCaseForm.validate({ errorPlacement: function() {} });
                                
                                if ($testCaseForm.valid()) {
                                    
                                    processForm.ajaxSubmit({
                                        type: 'post',
                                        url: 'mdwebservice/runProcess',
                                        dataType: 'json',
                                        beforeSubmit: function(formData, jqForm, options) {
                                            formData.push(
                                                {name: 'isTestCase', value: 1}, 
                                                {name: 'testCaseSystemId', value: $testCaseForm.find('#testCaseSystemId').val()}, 
                                                {name: 'testCaseName', value: $testCaseForm.find('#testCaseName').val()}, 
                                                {name: 'testCaseScenarioId', value: $testCaseForm.find('#testCaseScenarioId').val()}, 
                                                {name: 'testOrderNumber', value: $testCaseForm.find('#testOrderNumber').val()}, 
                                                {name: 'testCaseModeId', value: $testCaseForm.find('#testCaseModeId').val()}, 
                                                {name: 'isOnlyTemplate', value: $testCaseForm.find('#isOnlyTemplate').is(':checked') ? 1 : 0}
                                            );
                                        },
                                        beforeSend: function() {
                                            Core.blockUI({message: 'Loading...', boxed: true});
                                        },
                                        success: function(responseData) {
                                            new PNotify({
                                                title: responseData.status,
                                                text: responseData.message,
                                                type: responseData.status,
                                                sticker: false, 
                                                addclass: pnotifyPosition
                                            });  
                                            if (responseData.status === 'success') {
                                                $dialog.dialog('destroy').remove();
                                            } 
                                            Core.unblockUI();
                                        }
                                    });
                                }
                            }
                        },{
                            text: plang.get('close_btn'),
                            class: 'btn blue-madison btn-sm',
                            click: function() {
                                $dialog.dialog('close');
                            }
                        }]
                    });
                    $dialog.dialog('open');
                    Core.unblockUI();
                },
                error: function() { Core.unblockUI(); }
            });
        } else {
            $dialog.dialog('open');
        }

        processForm.on('remove', function() {
            $('#' + dialogId).dialog('destroy').remove();
        });
    }
}

function metaDataAutoComplete(elem, type) {
    var _this = elem;
    var _parent = _this.closest("div.meta-autocomplete-wrap");
    var params = _parent.attr('data-params');
    var isHoverSelect = false;

    _this.autocomplete({
        minLength: 1,
        maxShowItems: 30,
        delay: 500,
        highlightClass: "lookup-ac-highlight",
        appendTo: "body",
        position: { my: "left top", at: "left bottom", collision: "flip flip" },
        autoSelect: false,
        source: function(request, response) {
            $.ajax({
                type: 'post',
                url: 'mdmetadata/metaDataAutoComplete',
                dataType: 'json',
                data: {
                    q: request.term,
                    type: type,
                    params: params
                },
                success: function(data) {
                    if (type == 'code') {
                        response($.map(data, function(item) {
                            var code = item.split("|");
                            return {
                                value: code[1],
                                label: code[1],
                                name: code[2],
                                id: code[0]
                            };
                        }));
                    } else {
                        response($.map(data, function(item) {
                            var code = item.split("|");
                            return {
                                value: code[2],
                                label: code[1],
                                name: code[2],
                                id: code[0]
                            };
                        }));
                    }
                }
            });
        },
        focus: function(event, ui) {
            if (typeof event.keyCode === 'undefined' || event.keyCode == 0) {
                isHoverSelect = false;
            } else {
                if (event.keyCode == 38 || event.keyCode == 40) {
                    isHoverSelect = true;
                }
            }
            return false;
        },
        open: function() {
            /*$(this).autocomplete('widget').zIndex(99999999999999);*/
            return false;
        },
        close: function() {
            $(this).autocomplete("option", "appendTo", "body");
        },
        select: function(event, ui) {
            var origEvent = event;

            if (isHoverSelect || event.originalEvent.originalEvent.type == 'click') {
                if (type === 'code') {
                    _parent.find("input[id*='_displayField']").val(ui.item.label);
                    _parent.find("input[id*='_displayField']").attr('data-ac-id', ui.item.id);
                } else {
                    _parent.find("input[id*='_nameField']").val(ui.item.name);
                    _parent.find("input[id*='_nameField']").attr('data-ac-id', ui.item.id);
                }
            } else {
                if (type === 'code') {
                    if (ui.item.label === _this.val()) {
                        _parent.find("input[id*='_displayField']").val(ui.item.label);
                        _parent.find("input[id*='_nameField']").val(ui.item.name);
                    } else {
                        _parent.find("input[id*='_displayField']").val(_this.val());
                        event.preventDefault();
                    }
                } else {
                    if (ui.item.name === _this.val()) {
                        _parent.find("input[id*='_displayField']").val(ui.item.label);
                        _parent.find("input[id*='_nameField']").val(ui.item.name);
                    } else {
                        _parent.find("input[id*='_nameField']").val(_this.val());
                        event.preventDefault();
                    }
                }
            }

            while (origEvent.originalEvent !== undefined) {
                origEvent = origEvent.originalEvent;
            }

            if (origEvent.type === 'click') {
                var e = jQuery.Event("keydown");
                e.keyCode = e.which = 13;
                _this.trigger(e);
            }
        }
    }).autocomplete("instance")._renderItem = function(ul, item) {
        ul.addClass('lookup-ac-render');

        if (type === 'code') {
            var re = new RegExp("(" + this.term + ")", "gi"),
                cls = this.options.highlightClass,
                template = "<span class='" + cls + "'>$1</span>",
                label = item.label.replace(re, template);

            return $('<li>').append('<div class="lookup-ac-render-code">' + label + '</div><div class="lookup-ac-render-name">' + item.name + '</div>').appendTo(ul);
        } else {
            var re = new RegExp("(" + this.term + ")", "gi"),
                cls = this.options.highlightClass,
                template = "<span class='" + cls + "'>$1</span>",
                name = item.name.replace(re, template);

            return $('<li>').append('<div class="lookup-ac-render-code">' + item.label + '</div><div class="lookup-ac-render-name">' + name + '</div>').appendTo(ul);
        }
    };
}
function checkListBusinessProcess(processId, elem, selectedRowData, dmDataViewId) {
    var params = {
        metaDataId: processId,
        isDialog: true,
        isSystemMeta: false,
        callerType: 'checklist',
        openParams: '{"callerType":"checklist"}',
        notCheckList: true,
    };
    if (typeof selectedRowData !== 'undefeined' && typeof dmDataViewId !== 'undefined') {
        params = {
            metaDataId: processId,
            isDialog: true,
            isSystemMeta: false,
            callerType: 'checklist',
            openParams: '{"callerType":"checklist"}',
            notCheckList: true,
            rowDataEncode: selectedRowData,
            dmMetaDataId: dmDataViewId
        };
    }

    var $dialogName = 'dialog-checkListBp-' + processId;
    $.ajax({
        type: 'post',
        url: 'mdwebservice/callMethodByMeta',
        data: params,
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
            }
            var $dialog = $("#" + $dialogName);
            $dialog.empty().append(data.Html);

            var processForm = $dialog.find('form');
            var processUniqId = processForm.parent().attr('data-bp-uniq-id');
            var runModeButton = '';
            if (typeof data.run_mode !== 'undefined') {
                runModeButton = data.run_mode;
            }

            var buttons = [{
                    text: data.run_btn,
                    class: 'btn green-meadow btn-sm ' + runModeButton,
                    click: function(e) {

                        if (window['processBeforeSave_' + processUniqId]($(e.target))) {
                            
                            if (bpFormValidate(processForm)) {

                                processForm.ajaxSubmit({
                                    type: 'post',
                                    url: 'mdwebservice/runProcess',
                                    dataType: 'json',
                                    beforeSend: function() {
                                        Core.blockUI({
                                            boxed: true,
                                            message: 'Түр хүлээнэ үү'
                                        });
                                    },
                                    success: function(responseData) {

                                        if (responseData.status === 'success') {

                                            elem.addClass('bp-checklist-row-checked');
                                            elem.find("input[name*='bp_checklist[']").val('1');

                                            $dialog.dialog('close');
                                        }

                                        bpIgnoreGroupRemove(processForm);

                                        Core.unblockUI();
                                    }
                                });
                            }

                        } else {
                            bpIgnoreGroupRemove(processForm);
                        }
                    }
                },
                {
                    text: data.close_btn,
                    class: 'btn blue-madison btn-sm',
                    click: function() {
                        $dialog.dialog('close');
                    }
                }
            ];

            var dialogWidth = data.dialogWidth;
            var dialogHeight = data.dialogHeight;

            if (data.isDialogSize === 'auto') {
                dialogWidth = 1200;
                dialogHeight = 'auto';
            }

            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: dialogWidth,
                height: dialogHeight,
                modal: true,
                closeOnEscape: isCloseOnEscape,
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: buttons
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
            if (data.dialogSize === 'fullscreen') {
                $dialog.dialogExtend("maximize");
            }
            $dialog.dialog('open');
        },
        error: function() {
            alert('Error');
        }
    }).done(function() {
        Core.initBPAjax($("#" + $dialogName));
        Core.unblockUI();
    });
}
function callSystemFunction(funcName, elem, blockMsg, alertMsg, ruleId, fiscalPeriodId) {
    funcName = funcName.toLowerCase();

    switch (funcName) {
        case 'shiftf1':
            var fncArguments = [blockMsg, alertMsg, ruleId, fiscalPeriodId, 0, 1];
            checkUrlAuthLoginByFnc('systemUpdatePusher', fncArguments);
            break;
        case 'shiftf2':
            var fncArguments = ['', '', ruleId, fiscalPeriodId, 1, 1];
            checkUrlAuthLoginByFnc('systemUpdatePusher', fncArguments);
            break;
    }

    clearConsole();
}

function systemCacheClear() {

    var $dialogName = 'dialog-cache-clear';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }

    $dialogName = $('#' + $dialogName);

    $.ajax({
        type: 'post',
        url: 'mdmeta/systemCacheClearForm',
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            $dialogName.empty().append(data.html);
            $dialogName.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 250,
                height: 'auto',
                modal: true,
                close: function() {
                    $dialogName.empty().dialog('destroy').remove();
                },
                buttons: [{
                        text: data.clear_btn,
                        class: 'btn green-meadow btn-sm',
                        click: function() {

                            PNotify.removeAll();

                            if ($('#form-cache-clear').find('input[type=checkbox]:checked').length) {

                                $('#form-cache-clear').validate({ errorPlacement: function() {} });

                                if ($('#form-cache-clear').valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: 'mdmeta/systemCacheClear',
                                        data: $('#form-cache-clear').serialize(),
                                        dataType: 'json',
                                        beforeSend: function() {
                                            Core.blockUI({message: 'Clearing...', boxed: true});
                                        },
                                        success: function(dataSub) {
                                            new PNotify({
                                                title: dataSub.status,
                                                text: dataSub.message,
                                                type: dataSub.status,
                                                sticker: false
                                            });
                                            if (dataSub.status === 'success') {
                                                $dialogName.dialog('close');
                                            } 
                                            Core.unblockUI();
                                        },
                                        error: function() { alert('Error'); }
                                    });
                                }

                            } else {
                                new PNotify({
                                    title: 'Info',
                                    text: 'Төрөлүүдээс сонгоно уу!',
                                    type: 'info',
                                    sticker: false
                                });
                            }

                        }
                    },
                    {
                        text: data.close_btn,
                        class: 'btn blue-madison btn-sm',
                        click: function() {
                            $dialogName.dialog('close');
                        }
                    }
                ]
            });
            $dialogName.dialog('open');
            Core.unblockUI();
        },
        error: function() { alert('Error'); }
    }).done(function() {
        Core.initUniform($dialogName);
    });
}

function checkUrlAuthLoginByFnc(fncName, fncArguments) {

    if (typeof isUrlAuth === 'undefined') {

        if (typeof fncArguments === 'undefined') {
            window[fncName]();
        } else {
            window[fncName].apply(null, fncArguments);
        }

    } else {

        var $dialogName = 'dialog-auth-login', postData = {};
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        $dialogName = $('#' + $dialogName);
        
        if (fncName == 'editFormMeta') {
            postData.metaId = fncArguments[0];
        }

        $.ajax({
            type: 'post',
            url: 'mdmeta/checkUrlAuthLoginForm',
            data: postData, 
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                $dialogName.empty().append(data.html);
                $dialogName.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: 450,
                    height: 'auto',
                    modal: true,
                    open: function() {
                        $(this).keypress(function(e) {
                            if (e.keyCode == $.ui.keyCode.ENTER) {
                                $(this).parent().find(".ui-dialog-buttonpane button:first").trigger('click');
                            }
                        });
                    },
                    close: function() {
                        $dialogName.empty().dialog('destroy').remove();
                    },
                    buttons: [{
                            text: data.login_btn,
                            class: 'btn green-meadow btn-sm',
                            click: function() {

                                PNotify.removeAll();

                                $('#auth-login-form').validate({ errorPlacement: function() {} });

                                if ($('#auth-login-form').valid()) {
                                    $.ajax({
                                        type: 'post',
                                        url: 'mdmeta/checkUrlAuthLogin',
                                        data: $('#auth-login-form').serialize(),
                                        dataType: 'json',
                                        beforeSend: function() {
                                            Core.blockUI({message: 'Checking...', boxed: true});
                                        },
                                        success: function(dataSub) {
                                            if (dataSub.status === 'success') {

                                                $dialogName.dialog('close');

                                                if (typeof fncArguments === 'undefined') {
                                                    window[fncName]();
                                                } else {
                                                    window[fncName].apply(null, fncArguments);
                                                }

                                            } else {
                                                new PNotify({
                                                    title: dataSub.status,
                                                    text: dataSub.message,
                                                    type: dataSub.status,
                                                    sticker: false
                                                });
                                            }
                                            Core.unblockUI();
                                        },
                                        error: function() { alert('Error'); }
                                    });
                                }
                            }
                        },
                        {
                            text: data.close_btn,
                            class: 'btn blue-madison btn-sm',
                            click: function() {
                                $dialogName.dialog('close');
                            }
                        }
                    ]
                });
                $dialogName.dialog('open');
                Core.unblockUI();
            },
            error: function() { alert('Error'); }
        });
    }
}

function systemUpdatePusher(blockMsg, alertMsg, ruleId, fiscalPeriodId, isOpen, isDvReload) {
    var $dialogName = 'dialog-new-notify';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);

    blockMsg = (typeof blockMsg !== 'undefined' ? blockMsg : '');
    alertMsg = (typeof alertMsg !== 'undefined' ? alertMsg : '');
    isOpen = (typeof isOpen !== 'undefined' ? isOpen : 0);

    $.ajax({
        type: 'post',
        url: 'profile/newNotify',
        data: {blockMsg: blockMsg, alertMsg: alertMsg, isOpen: isOpen},
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {

            if (data.isOpen == true) {

                $dialog.empty().append(data.html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: 630,
                    height: 'auto',
                    modal: true,
                    position: { my: 'top', at: 'top+53' },
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [{
                            text: data.save_btn,
                            class: 'btn green-meadow btn-sm',
                            click: function() {
                                $("#form-new-notify").validate({ errorPlacement: function() {} });

                                if ($("#form-new-notify").valid()) {

                                    $('#form-new-notify').ajaxSubmit({
                                        type: 'post',
                                        url: 'profile/saveNotify',
                                        dataType: 'json',
                                        beforeSubmit: function(formData, jqForm, options) {
                                            if (typeof ruleId !== 'undefined') {
                                                formData.push({ name: 'ruleId', value: ruleId }, { name: 'fiscalPeriodId', value: fiscalPeriodId });
                                            }
                                        },
                                        beforeSend: function() {
                                            Core.blockUI({message: 'Loading...', boxed: true});
                                        },
                                        success: function(dataSub) {
                                            PNotify.removeAll();
                                            new PNotify({
                                                title: dataSub.status,
                                                text: dataSub.message,
                                                type: dataSub.status,
                                                sticker: false
                                            });

                                            if (dataSub.status === 'success') {
                                                $dialog.dialog('close');
                                                if (typeof isDvReload !== 'undefined' && isDvReload == 1 &&
                                                    $('body').find('.main-dataview-container').length > 0 && $('body').find('.main-dataview-container').is(':visible')) {
                                                    var $dataViewElement = $('body').find(".main-dataview-container:visible");
                                                    var $dataViewElementArr = $dataViewElement.attr('id').split('-');
                                                    dataViewReload($dataViewElementArr[3]);
                                                }
                                            }
                                            Core.unblockUI();
                                        },
                                        error: function() { alert('Error'); }
                                    });
                                }
                            }
                        },
                        {
                            text: data.close_btn,
                            class: 'btn blue-madison btn-sm',
                            click: function() {
                                $dialog.dialog('close');
                            }
                        }
                    ]
                });
                $dialog.dialog('open');
            }

            Core.unblockUI();
        },
        error: function() { alert('Error'); }
    }).done(function() {
        Core.initDateMinuteMaskInput($dialog);
        Core.initLongInput($dialog);
        Core.initUniform($dialog);
    });
}

function systemUpdatePusherStop() {
    systemUpdatePusher('', '', '', '', 1, 1);
}

var _parentGlobeCode;

function setGlobeCode(elem) {
    _parentGlobeCode = $(elem).closest(".input-group");

    $.ajax({
        type: 'post',
        url: 'mdlanguage/renderGenerateGlobeList',
        data: { code: _parentGlobeCode.find("input[type=text]:visible").val() },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({animate: true});
        },
        success: function(data) {
            var $dialogName = 'dialog-globecode-list';
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            var $dialog = $("#" + $dialogName);

            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 1100,
                height: 'auto',
                modal: true,
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{
                        text: data.choose_btn,
                        class: 'btn blue btn-sm',
                        click: function() {
                            var rows = $('#languageDataGrid').datagrid('getSelections');
                            if (rows.length > 0) {
                                _parentGlobeCode.find("input[type=text]:visible").val(rows[0]['CODE']).attr('title', rows[0]['MONGOLIAN']).trigger('change');
                                $dialog.dialog('close');
                            }
                        }
                    },
                    {
                        text: data.close_btn,
                        class: 'btn blue-hoki btn-sm',
                        click: function() {
                            $dialog.dialog('close');
                        }
                    }
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        }
    });
}
function bpOpenConfigContainer(_elem, mainMetaDataId, processMetaDataId, processUniqId, title, html, isEdit, row) {

    if (typeof isAppMultiTab !== 'undefined' && isAppMultiTab) {
        if (typeof isAlwaysNewTab !== 'undefined' && isAlwaysNewTab) {
            if (isEdit) {
                appMultiTabByContent({ metaDataId: getUniqueId(1), title: title, type: 'process', content: html });
            } else if (processAlreadyLoad) {
                appMultiTabByContent({ metaDataId: processMetaDataId, title: title, type: 'process', content: html });
            } else {
                appMultiTabByContent({ metaDataId: processMetaDataId, title: title, type: 'newprocess', content: html });
            }
        } else {
            bpContainerAppendByDv(_elem, mainMetaDataId, '<div id="editFormGroup">' + html + '</div>');
        }
    } else {
        newContainerAppend('<div id="editFormGroup">' + html + '</div>');
    }
}
function metaCopy(metaDataId) {
    var $dialogName = 'dialog-meta-copy';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $("#" + $dialogName),
        $breadcrumb = $('.meta-breadcrumb > a[data-folder-id]:last'),
        folderId = '', folderName = '';

    if ($breadcrumb.length) {
        folderId = $breadcrumb.attr('data-folder-id');
        folderName = $breadcrumb.text();
    }

    $.ajax({
        type: 'post',
        url: 'mdmeta/copyMetaData',
        data: { metaDataId: metaDataId, folderId: folderId, folderName: folderName },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({animate: true});
        },
        success: function(data) {
            
            if (data.hasOwnProperty('status') && data.status != 'success') {
                Core.unblockUI();
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
                return;
            } 
            
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 650,
                height: 'auto',
                modal: true,
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{
                        text: data.save_btn,
                        class: 'btn green-meadow btn-sm',
                        click: function() {
                            PNotify.removeAll();
                            $("#copyMetaData-form", "#" + $dialogName).validate({ errorPlacement: function() {} });

                            if ($("#copyMetaData-form", "#" + $dialogName).valid()) {
                                $.ajax({
                                    type: 'post',
                                    url: 'mdmeta/saveCopyMetaData',
                                    data: $("#copyMetaData-form", "#" + $dialogName).serialize(),
                                    dataType: 'json',
                                    beforeSend: function() {
                                        Core.blockUI({animate: true});
                                    },
                                    success: function(dataSub) {
                                        new PNotify({
                                            title: dataSub.status,
                                            text: dataSub.message,
                                            type: dataSub.status,
                                            sticker: false
                                        });
                                        if (dataSub.status === 'success') {
                                            
                                            var newFolderId = $dialog.find('input[name="folderId"]').val();
                                            $dialog.dialog('close');
                                            
                                            if (typeof newFolderId == 'undefined' || newFolderId == '' || newFolderId == null) {
                                                metaDataDefault();
                                            } else {
                                                refreshList(newFolderId, 'folder');
                                            }
                                            
                                        } else if (typeof dataSub.fieldName !== 'undefined') {
                                            $("input[name='" + dataSub.fieldName + "']", 'form#copyMetaData-form').addClass("error");
                                        }
                                        
                                        Core.unblockUI();
                                    },
                                    error: function() { alert('Error'); }
                                });
                            }
                        }
                    },
                    {
                        text: data.close_btn,
                        class: 'btn blue-hoki btn-sm',
                        click: function() {
                            $dialog.dialog('close');
                        }
                    }
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        }
    });
}

function metaTableStructure(metaDataId) {
    var $dialogName = 'dialog-meta-tablestructure';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $("#" + $dialogName);

    $.ajax({
        type: 'post',
        url: 'mdmeta/tableStructure',
        data: { metaDataId: metaDataId },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({animate: true});
        },
        success: function(data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 700,
                height: "auto",
                modal: true,
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{
                        text: data.save_btn,
                        class: 'btn green-meadow btn-sm',
                        click: function() {
                            $("#tableStructure-form", "#" + $dialogName).validate({ errorPlacement: function() {} });

                            if ($("#tableStructure-form", "#" + $dialogName).valid()) {
                                $.ajax({
                                    type: 'post',
                                    url: 'mdmeta/createTableStructure',
                                    data: { metaDataId: metaDataId },
                                    dataType: 'json',
                                    beforeSend: function() {
                                        Core.blockUI({animate: true});
                                    },
                                    success: function(dataSub) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: dataSub.status,
                                            text: dataSub.message,
                                            type: dataSub.status,
                                            sticker: false
                                        });
                                        if (dataSub.status === 'success') {
                                            $dialog.dialog('close');
                                        } 
                                        Core.unblockUI();
                                    },
                                    error: function() { alert('Error'); }
                                });
                            }
                        }
                    },
                    {
                        text: data.close_btn,
                        class: 'btn blue-hoki btn-sm',
                        click: function() {
                            $dialog.dialog('close');
                        }
                    }
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        },
        error: function() { alert('Error'); }
    }).done(function() {
        Core.initAjax($dialog);
    });
}

function dataViewSql(metaDataId) {
    var $dialogName = 'dialog-sql-view-' + metaDataId;
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $("#" + $dialogName);

    $.ajax({
        type: 'post',
        url: 'mdmeta/dataViewSql',
        data: { metaDataId: metaDataId },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                animate: true
            });
        },
        success: function(data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 700,
                height: 500,
                modal: false,
                close: function() {
                    $dialog.empty().dialog('close');
                },
                buttons: [{
                    text: data.close_btn,
                    class: 'btn blue-hoki btn-sm',
                    click: function() {
                        $dialog.dialog('close');
                    }
                }]
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
                },
                "maximize": function() {
                    var dialogHeight = $dialog.height();
                    $dialog.find("div.dataview-sql").find("textarea").css("height", (dialogHeight) + 'px');
                },
                "restore": function() {
                    var dialogHeight = $dialog.height();
                    $dialog.find("div.dataview-sql").find("textarea").css("height", (dialogHeight) + 'px');
                }
            });
            $dialog.dialog('open');
            $dialog.dialogExtend('restore');
            Core.unblockUI();
        }
    });
}

function groupPathView(metaDataId) {
    var $dialogName = 'dialog-path-view-' + metaDataId;
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }

    $.ajax({
        type: 'post',
        url: 'mdmeta/groupPathView',
        data: { metaDataId: metaDataId },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                animate: true
            });
        },
        success: function(data) {
            if (data.status === 'error') {
                new PNotify({
                    title: 'Error',
                    text: data.message,
                    type: 'error',
                    sticker: false
                });
            } else {
                $("#" + $dialogName).empty().append(data.html);
                $("#" + $dialogName).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: 1000,
                    height: "auto",
                    modal: false,
                    close: function() {
                        $("#" + $dialogName).empty().dialog('close');
                    },
                    buttons: [{
                        text: data.close_btn,
                        class: 'btn blue-hoki btn-sm',
                        click: function() {
                            $("#" + $dialogName).dialog('close');
                        }
                    }]
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
                $("#" + $dialogName).dialog('open');
                $("#" + $dialogName).dialogExtend("restore");
            }
            Core.unblockUI();
        },
        error: function() {
            alert('Error');
        }
    }).done(function() {
        Core.initAjax($("#" + $dialogName));
    });
}

function structureRefresh(metaDataId) {
    $.ajax({
        type: 'post',
        url: 'mdmeta/refreshStructure',
        data: { metaDataId: metaDataId },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                animate: true
            });
        },
        success: function(data) {
            PNotify.removeAll();
            new PNotify({
                title: data.status,
                text: data.message,
                type: data.status,
                sticker: false
            });
            Core.unblockUI();
        },
        error: function() {
            alert('Error');
        }
    });
}
function internalProcess(metaDataId, folder_id) {
    var $dialogName = 'dialog-internal-process';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);

    $.ajax({
        type: 'post',
        url: 'mdmeta/internalProcess',
        data: { metaDataId: metaDataId, folderId: folder_id },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({animate: true});
        },
        success: function(data) {

            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 400,
                height: 'auto',
                modal: true,
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{
                        text: data.save_btn,
                        class: 'btn btn-sm green-meadow',
                        click: function() {
                            PNotify.removeAll();
                            if ($('input[name="sendType"]').val() == '1') {
                                $.ajax({
                                    type: 'post',
                                    url: 'mdmeta/internalProcessAction',
                                    data: $("#internal-process-form").serialize(),
                                    dataType: 'json',
                                    beforeSend: function() {
                                        Core.blockUI({animate: true});
                                    },
                                    success: function(data) {
                                        new PNotify({
                                            title: data.status,
                                            text: data.message,
                                            type: data.status,
                                            sticker: false
                                        });
                                        if (data.status == 'success') {
                                            refreshList(folder_id, 'folder', '');
                                        }
                                        $dialog.dialog('close');
                                        Core.unblockUI();
                                    },
                                    error: function() {
                                        alert('Error');
                                        Core.unblockUI();
                                    }
                                });
                            } else {
                                new PNotify({
                                    title: 'Error',
                                    text: 'Action сонгоогүй байна',
                                    type: 'error',
                                    sticker: false
                                });
                            }
                        }
                    },
                    {
                        text: data.close_btn,
                        class: 'btn blue-madison btn-sm',
                        click: function() {
                            $dialog.dialog('close');
                        }
                    }
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        }
    }).done(function() {
        Core.initUniform($dialog);
    });
}
function bpAddPhoto(elem) {
    var getTable = $(elem).closest("table");
    $("tbody", getTable).append(
        '<tr>' +
        '<td style="width: 65%"><input type="file" name="bp_photo[]" class="col-md-12" onchange="hasPhotoExtension(this);"></td>' +
        '<td style="width: 35%"><input type="text" name="bp_photo_name[]" class="form-control col-md-12" placeholder="Тайлбар"/></td>' +
        '<td style="width: 20px">' +
        '<a href="javascript:;" class="btn btn-xs btn-danger" onclick="removeMetaPhoto(this);"><i class="icon-cross2 font-size-12"></i></a>' +
        '</td>' +
        '</tr>');
}

function bpAddFile(elem) {
    var getTable = $(elem).closest("table");
    $("tbody", getTable).append(
        '<tr>' +
        '<td style="width: 65%"><input type="file" name="bp_file[]" class="col-md-12" onchange="hasFileExtension(this);"></td>' +
        '<td style="width: 35%"><input type="text" name="bp_file_name[]" class="form-control col-md-12" placeholder="Тайлбар"/></td>' +
        '<td style="width: 20px">' +
        '<a href="javascript:;" class="btn btn-xs btn-danger" onclick="removeMetaPhoto(this);"><i class="icon-cross2 font-size-12"></i></a>' +
        '</td>' +
        '</tr>');
}

function bpCommentRemove(elem) {
    var $this = $(elem);
    var $parent = $this.closest("li");
    $parent.remove();
}
function workSpaceThemePositionList(metaDataId, groupMetaDataId) {
    var $dialogName = 'dialog-theme-position';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $("#" + $dialogName);

    $.ajax({
        type: 'post',
        url: 'mdworkspace/workSpaceThemePositionList',
        data: { metaDataId: metaDataId, groupMetaDataId: groupMetaDataId },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({animate: true});
        },
        success: function(data) {
            $dialog.empty().append(data.Html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: 650,
                height: 500,
                modal: true,
                close: function() {
                    $dialog.empty().dialog('close');
                },
                buttons: [{
                    text: data.close_btn,
                    class: 'btn btn-sm blue-hoki',
                    click: function() {
                        $dialog.dialog('close');
                    }
                }]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        },
        error: function() { alert('Error'); }
    }).done(function() {Core.initAjax($dialog);});
}

function workSpaceProcessList(metaDataId, groupMetaDataId) {
    var $dialogName = 'dialog-workspace-process';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $("#" + $dialogName);

    $.ajax({
        type: 'post',
        url: 'mdworkspace/workSpaceProcessList',
        data: { metaDataId: metaDataId, groupMetaDataId: groupMetaDataId },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({animate: true});
        },
        success: function(data) {
            $dialog.empty().append(data.Html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: 900,
                height: 655,
                modal: true,
                close: function() {
                    $dialog.empty().dialog('close');
                },
                buttons: [{
                    text: data.close_btn,
                    class: 'btn btn-sm blue-hoki',
                    click: function() {
                        $dialog.dialog('close');
                    }
                }]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        },
        error: function() { alert('Error'); }
    }).done(function() { Core.initAjax($dialog); });
}
function groupConfigBackup(metaDataId) {
    $.ajax({
        type: 'post',
        url: 'mdmeta/groupConfigBackup',
        data: { metaDataId: metaDataId },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({animate: true});
        },
        success: function(data) {
            var $dialogName = 'dialog-configbackup';
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            $("#" + $dialogName).empty().append(data.html);
            $("#" + $dialogName).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 700,
                height: 'auto',
                modal: true,
                close: function() {
                    $("#" + $dialogName).empty().dialog('destroy').remove();
                },
                buttons: [{
                    text: data.close_btn,
                    class: 'btn blue-madison btn-sm',
                    click: function() {
                        $("#" + $dialogName).dialog('close');
                    }
                }]
            });
            $("#" + $dialogName).dialog('open');
            Core.unblockUI();
        },
        error: function() {
            alert('Error');
        }
    });
}

function bpCacheClear(metaDataId, elem) {
    $.ajax({
        type: 'post',
        url: 'mdmeta/bpExpressionCacheClearByMetaId',
        data: { metaDataId: metaDataId },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            PNotify.removeAll();
            new PNotify({
                title: data.status,
                text: data.message,
                type: data.status,
                sticker: false
            });
            Core.unblockUI();
        }
    });
}
function bpExpressionCacheClear(metaDataId) {
    $.ajax({
        type: 'post',
        url: 'mdmeta/bpExpressionCacheClear',
        data: {metaDataId: metaDataId},
        dataType: "json",
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            PNotify.removeAll();
            new PNotify({
                title: data.status,
                text: data.message,
                type: data.status,
                sticker: false
            });
            Core.unblockUI();
        }
    });
}
function dvCacheClear(metaDataId, elem) {
    $.ajax({
        type: 'post',
        url: 'mdmeta/dvCacheClearByPost',
        data: { metaDataId: metaDataId },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            PNotify.removeAll();
            new PNotify({
                title: data.status,
                text: data.status,
                type: 'success',
                sticker: false
            });
            Core.unblockUI();
        }
    });
}
function commonFolderDataGrid(chooseType, params, funcName, _this) {
    var funcName = typeof funcName === 'undefined' ? 'selectableCommonFolderGrid' : funcName;
    var _this = typeof _this === 'undefined' ? '' : _this;
    var $dialogName = 'dialog-commonfolder';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $("#" + $dialogName);
    
    $.ajax({
        type: 'post',
        url: 'mdfolder/folderSelectableGrid',
        data: { chooseType: chooseType, params: params },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({animate: true});
        },
        success: function(data) {
            $dialog.empty().append(data.Html);
            $dialog.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: 1100,
                height: "auto",
                modal: true,
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{
                        text: data.addbasket_btn,
                        class: 'btn green-meadow btn-sm float-left',
                        click: function() {
                            basketCommonFolderGrid();
                        }
                    },
                    {
                        text: data.choose_btn,
                        class: 'btn blue btn-sm',
                        click: function() {
                            if (typeof(window[funcName]) === 'function') {
                                window[funcName](chooseType, _this, params);
                            } else {
                                alert('Function undefined error: ' + funcName);
                            }
                            var countBasketList = $('#commonBasketFolderGrid').datagrid('getData').total;
                            if (countBasketList > 0) {
                                $dialog.dialog('close');
                            }
                        }
                    },
                    {
                        text: data.close_btn,
                        class: 'btn blue-hoki btn-sm',
                        click: function() {
                            $dialog.dialog('close');
                        }
                    }
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        },
        error: function() {
            alert('Error');
        }
    }).done(function() {
        Core.initAjax($dialog);
    });
}

function glInvoiceProcessAction(metadataId, uniqId, processMetaDataId, isEdit, _elem) {
    var tr = $('body').find("#glDtl").find('tr.gl-selected-row');
    var addonJsonParam;
    if (typeof tr !== 'undefined') {
        addonJsonParam = {
            "accountId": $(tr).find("input[name='gl_accountId[]']").val(),
            'customerId': $(tr).find("input[name='gl_customerId[]']").val(),
            "description": $(tr).find("input[name='gl_rowdescription[]']").val(),
            "debitAmount": $(tr).find("input[name='gl_debitAmount[]']").val(),
            "debitAmountBase": $(tr).find("input[name='gl_debitAmountBase[]']").val(),
            "creditAmount": $(tr).find("input[name='gl_creditAmount[]']").val(),
            "creditAmountBase": $(tr).find("input[name='gl_creditAmountBase[]']").val()
        };
    }
    metadataId = metadataId.split('_')[0];
    var selectedRow = '';
    var dataGrid = $('#objectdatagrid_' + uniqId);
    var inputData = {
        metaDataId: processMetaDataId,
        isDialog: true,
        addonJsonParam: JSON.stringify(addonJsonParam),
        callerType: 'generalledger',
        openParams: '{"callerType":"generalledger"}'
    };
    if (isEdit) {
        dataGrid = $('#commonSelectableBasketDataGrid_' + uniqId);
        var rows = dataGrid.datagrid('getSelections');
        if (rows.length > 0) {
            selectedRow = rows[0];
            var editProcess = JSON.parse(processMetaDataId);
            if (jQuery.type(editProcess) === "object") {
                if (selectedRow.isdebit == "1") {
                    processMetaDataId = editProcess.debit;
                } else {
                    processMetaDataId = editProcess.credit;
                }
            } else {
                processMetaDataId = editProcess;
            }
            inputData = {
                metaDataId: processMetaDataId,
                isDialog: true,
                recordId: selectedRow.id,
                callerType: 'generalledger',
                openParams: '{"callerType":"generalledger"}'
            };
        } else {
            alert(plang.get('msg_pls_list_select'));
            return;
        }
    }
    $.ajax({
        type: 'post',
        url: 'mdwebservice/callMethodByMeta',
        data: inputData,
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                animate: true
            });
        },
        success: function(data) {
            if (data.mode === 'dialog') {
                var $dialogName = 'dialog-businessprocess-' + processMetaDataId;
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
                }
                $("#" + $dialogName).empty().append(data.Html);

                var hidePrintButton = '';
                if (typeof data.save_and_print === 'undefined') {
                    hidePrintButton = ' hide';
                }
                var runModeButton = '';
                if (data.run_mode === '') {
                    runModeButton = ' hide';
                }

                var processForm = $("#wsForm", "#" + $dialogName);
                var processUniqId = processForm.parent().attr('data-bp-uniq-id');

                var buttons = [{
                        text: 'Тусламж',
                        class: 'btn btn-info btn-sm float-left' + (data.helpContentId === null ? ' hidden' : ''),
                        click: function() {
                            getHelpContent(data.helpContentId, data.metaDataId, data.Title);
                        }
                    },
                    {
                        text: data.run_mode,
                        class: 'btn green-meadow btn-sm bp-run-btn ' + runModeButton,
                        click: function(e) {

                            if (window['processBeforeSave_' + processUniqId]($(e.target))) {

                                processForm.find('select:visible').each(function() {
                                    var _this = $(this);
                                    if (_this.parent().find("div:first").hasClass("select2-container-disabled")) {
                                        _this.parent().find("div:first").attr("data-readonly", "");
                                    }
                                    if (typeof _this.parent().find("div:first").attr("data-readonly") !== 'undefined') {
                                        _this.prop("readonly", true);
                                    }
                                });
                                processForm.validate({
                                    ignore: "",
                                    highlight: function(label) {
                                        $(label).addClass('error');
                                        $(label).parent().addClass('error');
                                        if (processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                            processForm.find("div.tab-pane:hidden:has(.error)").each(function(index, tab) {
                                                var tabId = $(tab).attr("id");
                                                processForm.find('a[href="#' + tabId + '"]').tab('show');
                                            });
                                        }
                                    },
                                    unhighlight: function(label) {
                                        $(label).removeClass('error');
                                        $(label).parent().removeClass('error');
                                    },
                                    errorPlacement: function() {}
                                });

                                var isValidPattern = initBusinessProcessMaskEvent(processForm);

                                if (processForm.valid() && isValidPattern.length === 0) {

                                    processForm.ajaxSubmit({
                                        type: 'post',
                                        url: 'mdwebservice/runProcess',
                                        dataType: 'json',
                                        beforeSend: function() {
                                            Core.blockUI({
                                                message: 'Түр хүлээнэ үү',
                                                boxed: true
                                            });
                                        },
                                        success: function(responseData) {
                                            PNotify.removeAll();

                                            if (responseData.status === 'success') {
                                                new PNotify({
                                                    title: 'Success',
                                                    text: responseData.message,
                                                    type: 'success',
                                                    addclass: pnotifyPosition,
                                                    sticker: false
                                                });
                                                dataViewReloadByElement(dataGrid);

                                                bpProcessFieldClear(processForm, responseData.uniqId);

                                            } else {
                                                new PNotify({
                                                    title: 'Error',
                                                    text: responseData.message,
                                                    type: 'error',
                                                    sticker: false,
                                                    hide: true,
                                                    addclass: pnotifyPosition,
                                                    delay: 1000000000
                                                });
                                            }

                                            bpIgnoreGroupRemove(processForm);

                                            Core.unblockUI();
                                        },
                                        error: function() {
                                            alert('Error');
                                        }
                                    });
                                }

                            } else {
                                bpIgnoreGroupRemove(processForm);
                            }
                        }
                    },
                    {
                        text: data.run_btn,
                        class: 'btn green-meadow btn-sm bp-run-btn main-run-btn',
                        click: function(e) {

                            if (window['processBeforeSave_' + processUniqId]($(e.target))) {

                                processForm.find('select:visible').each(function() {
                                    var _this = $(this);
                                    if (_this.parent().find("div:first").hasClass("select2-container-disabled")) {
                                        _this.parent().find("div:first").attr("data-readonly", "");
                                    }
                                    if (typeof _this.parent().find("div:first").attr("data-readonly") !== 'undefined') {
                                        _this.prop("readonly", true);
                                    }
                                });
                                processForm.validate({
                                    ignore: "",
                                    highlight: function(label) {
                                        $(label).addClass('error');
                                        $(label).parent().addClass('error');
                                        if (processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                            processForm.find("div.tab-pane:hidden:has(.error)").each(function(index, tab) {
                                                var tabId = $(tab).attr("id");
                                                processForm.find('a[href="#' + tabId + '"]').tab('show');
                                            });
                                        }
                                    },
                                    unhighlight: function(label) {
                                        $(label).removeClass('error');
                                        $(label).parent().removeClass('error');
                                    },
                                    errorPlacement: function() {}
                                });

                                var isValidPattern = initBusinessProcessMaskEvent(processForm);

                                if (processForm.valid() && isValidPattern.length === 0) {

                                    processForm.ajaxSubmit({
                                        type: 'post',
                                        url: 'mdwebservice/runProcess',
                                        dataType: 'json',
                                        beforeSend: function() {
                                            Core.blockUI({
                                                message: 'Түр хүлээнэ үү',
                                                boxed: true
                                            });
                                        },
                                        success: function(responseData) {
                                            PNotify.removeAll();

                                            if (responseData.status === 'success') {

                                                new PNotify({
                                                    title: 'Success',
                                                    text: responseData.message,
                                                    type: 'success',
                                                    addclass: pnotifyPosition,
                                                    sticker: false
                                                });

                                                dataViewReloadByElement(dataGrid);

                                                if ($("#commonSelectableTabBasket_" + uniqId).length > 0) {
                                                    if (responseData.rowId != '') {
                                                        if (isEdit) {
                                                            updateRowToBpDvBasket(metadataId, responseData.rowId, dataGrid);
                                                        } else {
                                                            addRowToBpDvBasket(metadataId, responseData.rowId, dataGrid);
                                                            closeDataGridWithBpValue(dataGrid, responseData.rowId);
                                                        }
                                                    }
                                                }

                                                $("#" + $dialogName).dialog('close');

                                            } else {
                                                new PNotify({
                                                    title: 'Error',
                                                    text: responseData.message,
                                                    type: 'error',
                                                    sticker: false,
                                                    hide: true,
                                                    addclass: pnotifyPosition,
                                                    delay: 1000000000
                                                });
                                            }

                                            bpIgnoreGroupRemove(processForm);

                                            Core.unblockUI();
                                        },
                                        error: function() {
                                            alert('Error');
                                        }
                                    });
                                }

                            } else {
                                bpIgnoreGroupRemove(processForm);
                            }
                        }
                    },
                    {
                        text: data.save_and_print,
                        class: 'btn purple-plum btn-sm bp-run-btn bp-btn-saveprint ' + hidePrintButton,
                        click: function(e) {

                            if (window['processBeforeSave_' + processUniqId]($(e.target))) {

                                processForm.find('select:visible').each(function() {
                                    var _this = $(this);
                                    if (_this.parent().find("div:first").hasClass("select2-container-disabled")) {
                                        _this.parent().find("div:first").attr("data-readonly", "");
                                    }
                                    if (typeof _this.parent().find("div:first").attr("data-readonly") !== 'undefined') {
                                        _this.prop("readonly", true);
                                    }
                                });
                                processForm.validate({
                                    ignore: "",
                                    highlight: function(label) {
                                        $(label).addClass('error');
                                        $(label).parent().addClass('error');
                                        if (processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                            processForm.find("div.tab-pane:hidden:has(.error)").each(function(index, tab) {
                                                var tabId = $(tab).attr("id");
                                                processForm.find('a[href="#' + tabId + '"]').tab('show');
                                            });
                                        }
                                    },
                                    unhighlight: function(label) {
                                        $(label).removeClass('error');
                                        $(label).parent().removeClass('error');
                                    },
                                    errorPlacement: function() {}
                                });

                                var isValidPattern = initBusinessProcessMaskEvent(processForm);

                                if (processForm.valid() && isValidPattern.length === 0) {

                                    processForm.ajaxSubmit({
                                        type: 'post',
                                        url: 'mdwebservice/runProcess',
                                        dataType: 'json',
                                        beforeSend: function() {
                                            Core.blockUI({
                                                message: 'Түр хүлээнэ үү',
                                                boxed: true
                                            });
                                        },
                                        success: function(responseData) {

                                            PNotify.removeAll();

                                            if (responseData.status === 'success') {
                                                new PNotify({
                                                    title: 'Success',
                                                    text: responseData.message,
                                                    type: 'success',
                                                    addclass: pnotifyPosition,
                                                    sticker: false
                                                });
                                                if (responseData.rowId !== '') {
                                                    processPrintPreview(e.target, processMetaDataId, responseData.rowId, data.get_process_id, responseData.resultData);
                                                }

                                                dataViewReloadByElement(dataGrid);                                                

                                                if ($("#commonSelectableTabBasket_" + uniqId).length > 0 && responseData.rowId != '') {
                                                    addRowToBpDvBasket(metadataId, responseData.rowId, dataGrid);
                                                    closeDataGridWithBpValue(dataGrid, responseData.rowId);
                                                }
                                                $("#" + $dialogName).dialog('close');

                                            } else {
                                                new PNotify({
                                                    title: 'Error',
                                                    text: responseData.message,
                                                    type: 'error',
                                                    sticker: false,
                                                    hide: true,
                                                    addclass: pnotifyPosition,
                                                    delay: 1000000000
                                                });
                                            }

                                            bpIgnoreGroupRemove(processForm);

                                            Core.unblockUI();
                                        },
                                        error: function() {
                                            alert('Error');
                                        }
                                    });
                                }

                            } else {
                                bpIgnoreGroupRemove(processForm);
                            }
                        }
                    },
                    {
                        text: data.close_btn,
                        class: 'btn blue-madison btn-sm bp-close-btn',
                        click: function() {
                            $("#" + $dialogName).dialog('close');
                        }
                    }
                ];

                $("#" + $dialogName).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: data.dialogWidth,
                    height: data.dialogHeight,
                    modal: true,
                    closeOnEscape: isCloseOnEscape,
                    open: function(event, ui) {
                        if ($(_elem).closest("div.tabbable-line").find("div#commonSelectableTabBasket").length > 0) {
                            $("tr[data-cell-path='isUsedGl']").hide();
                        }
                    },
                    close: function() {
                        $("#" + $dialogName).empty().dialog('destroy').remove();
                    },
                    buttons: buttons
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
                if (data.dialogSize === 'fullscreen') {
                    $("#" + $dialogName).dialogExtend("maximize");
                }
                $("#" + $dialogName).dialog('open');
                Core.initBPAjax($("#" + $dialogName));
            } else {
                if (!$("#viewFormMeta").length) {
                    newContainerAppend(data.Html);
                } else {
                    $("#viewFormMeta").empty().append(data.Html);
                    Core.initBPAjax($("#viewFormMeta"));
                    $("#renderMeta, #editFormGroup").hide();
                    $("#viewFormMeta").show();
                }
            }
            Core.unblockUI();
        },
        error: function() {
            alert('Error');
        }
    });
}

function removeMetaFolderTag(elem) {
    var $this = $(elem);
    $this.closest('td').find('input[name="isFolderManage"]').val('1'); 
    $this.closest('td').find('input[name="isTagsManage"]').val('1'); 
    $this.closest('div.meta-folder-tag').remove();
}
function changeMetaFolder(metaDataId, metaDataIds) {
    $.ajax({
        type: 'post',
        url: 'mdmeta/changeMetaFolder',
        data: {
            metaDataId: metaDataId,
            metaDataIds: metaDataIds
        },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                animate: true
            });
        },
        success: function(data) {
            var $dialogName = 'dialog-changemetafolder';
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            var $dialog = $("#" + $dialogName);
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 600,
                height: 'auto',
                modal: true,
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{
                        text: data.save_btn,
                        class: 'btn btn-sm green-meadow',
                        click: function() {
                            $("#changefolder-form", "#" + $dialogName).validate({ errorPlacement: function() {} });

                            if ($("#changefolder-form", "#" + $dialogName).valid()) {
                                var folderId = $("input[name='folderId']", "#changefolder-form").val();

                                $.ajax({
                                    type: 'post',
                                    url: 'mdmeta/saveChangeMetaFolder',
                                    data: {
                                        folderId: folderId,
                                        metaDataId: metaDataId,
                                        metaDataIds: metaDataIds
                                    },
                                    dataType: 'json',
                                    beforeSend: function() {
                                        Core.blockUI({
                                            animate: true
                                        });
                                    },
                                    success: function(dataSub) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: dataSub.status,
                                            text: dataSub.message,
                                            type: dataSub.status,
                                            sticker: false
                                        });
                                        if (dataSub.status === 'success') {
                                            $dialog.dialog('close');
                                            refreshList(folderId, 'folder');
                                        }
                                        Core.unblockUI();
                                    }
                                });
                            }
                        }
                    },
                    {
                        text: data.close_btn,
                        class: 'btn blue-madison btn-sm',
                        click: function() {
                            $dialog.dialog('close');
                        }
                    }
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        }
    });
}

function validateGlBook(windowId) {
    if ($(windowId).length > 0 && $("#glDtl", windowId).length > 0) {

        var mainErrorStatus = false;
        var array = [];
        var errorList = '';

        $('#glDtl > tbody > tr', windowId).each(function(i) {

            var _this = $(this);
            var errorStatus = false;
            var childErrorList = '';
            var accountid = _this.find("input[name='gl_accountId[]']").val();

            if (_this.attr('data-row-index') != 'undefined') {
                var index = parseInt(_this.attr('data-row-index')) + parseInt(1);
            }

            if (accountid == '') {
                errorStatus = true;
                childErrorList += "<dd> -Данс сонгоогүй байна</dd>";
            }
            if (_this.find("input[name='gl_debitAmount[]']").val() == 0 && _this.find("input[name='gl_creditAmount[]']").val() == 0) {
                errorStatus = true;
                childErrorList += "<dd> -Гүйлгээний ДТ/КТ дүн хоосон байна</dd>";
            }
            if (_this.find("input[name='gl_subid[]']").val() == '') {
                errorStatus = true;
                childErrorList += "<dd> -Багцын дугаар хоосон байна</dd>";
            }
            
            if (_this.find("input[name='gl_useDetailBook[]']").val() == '1') {

                if ((_this.find("input[name='gl_objectId[]']").val() == '20006' || _this.find("input[name='gl_objectId[]']").val() == '20007') &&
                    _this.find("input[name='gl_customerId[]']").val() == '') {
                
                    errorStatus = true;
                    childErrorList += "<dd> -Харилцагч сонгоогүй байна</dd>";

                } else if (_this.find("input[name='gl_objectId[]']").val() != '20006' && _this.find("input[name='gl_objectId[]']").val() != '20007') {

                    if (_this.find("td:last-child").find("#detailedMeta").length > 0 &&
                        _this.find("input[name='invoiceBookValue[]']").val() == '' &&
                        _this.find("input[name='defaultInvoiceBook[]']").val() == '' &&
                        _this.find("input[name='gl_invoiceBookId[]']").val() == '') {

                        errorStatus = true;
                        childErrorList += "<dd> -Баримт сонгоогүй байна</dd>";
                    }
                }
            }
            
            if (errorStatus) {
                mainErrorStatus = true;
                errorList += "<dt>" + (index) + "-р мөрөн дээр: </dt>";
                errorList += childErrorList;
                $('#glDtl > tbody', windowId).find("tr:eq(" + i + ")").children("td:not(:last-child)").addClass("validation-error-tr");
            }
        });
        if (mainErrorStatus) {
            array = {
                status: 'error',
                text: "Журналын: <br/>" + errorList
            };
        } else {
            var debitsum = $('#glDtl', windowId).find("td.foot-sum-debitamount").autoNumeric('get');
            var creditsum = $('#glDtl', windowId).find("td.foot-sum-creditamount").autoNumeric('get');

            if (debitsum != creditsum) {
                array = {
                    status: 'error',
                    text: '<dt>Дебит кредитийн дүн тэнцэхгүй байна</dt>'
                };
            } else {
                array = {
                    status: 'success',
                    text: 'success'
                };
                /*var equalizedAmount = checkSubProportion(windowId);
                if (equalizedAmount.status == 'success') {
                    array = {
                        status: 'success',
                        text: 'success'
                    };
                } else {
                    array = {
                        status: 'error',
                        text: '<dt>'+equalizedAmount.subid+' багцад дебит, кредит дүн тэнцэхгүй байна</dt>'
                    };
                }*/
            }
        }

        if ($('input[name="secondaryRate"]', windowId).length && Number($('input[name="secondaryRate"]', windowId).autoNumeric('get')) == 0) {
            array = {
                status: 'error',
                text: '<dt>'+plang.get('FIN_00071')+'</dt>'
            };            
        }

        return array;
    }
}
function checkSubProportion(windowId) {
    var array = [];
    var equalizeForSubNumber = true;
    var subs = new Array();
    var notEqualSubs = '';
    var errorSub = '';

    $("#glDtl > tbody > tr", windowId).each(function() {
        var bagts = $(this).find("input[name='gl_subid[]']").val();
        if (bagts != undefined) {
            subs.push(bagts);
        }
    });
    subs = $.unique(subs);
    $.each(subs, function(index, value) {
        var sumDb = 0;
        var sumCr = 0;
        $("#glDtl > tbody > tr", windowId).each(function() {
            if (typeof $(this).find("input[name='gl_subid[]']").val() !== 'undefined') {
                var this_bagts = $(this).find("input[name='gl_subid[]']").val();
                var this_debit = Number($(this).find("input[name='gl_debitAmount[]']").val());
                var this_credit = Number($(this).find("input[name='gl_creditAmount[]']").val());
                if (value == this_bagts) {
                    sumDb = parseFloat(sumDb) + this_debit;
                    sumCr = parseFloat(sumCr) + this_credit;
                }
            }
        });
        if (sumDb.toFixed(2) != sumCr.toFixed(2)) {
            equalizeForSubNumber = false;
            notEqualSubs += value + ', ';
        }
    });
    if (equalizeForSubNumber) {
        array = {
            status: 'success'
        };
    } else {
        errorSub = errorSub.substring(0, notEqualSubs.length - 2);
        array = {
            status: 'error',
            subid: errorSub
        };
    }
    return array;
}
function bpFullExpression(metaDataId, isTempSave) {
    var $dialogName = 'dialog-fullExpcriteria-' + metaDataId;
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $("#" + $dialogName);

    $.ajax({
        type: 'post',
        url: isTempSave ? 'mdmeta/tempProcessFullExpressionForm' : 'mdmeta/setProcessFullExpressionCriteria',
        data: {metaDataId: metaDataId},
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            PNotify.removeAll();
            if (data.status != 'success') {
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
                return;
            }
                
            $.cachedScript('assets/custom/addon/plugins/codemirror/lib/codemirror.min.js').done(function() {
                if ($("link[href='assets/custom/addon/plugins/codemirror/lib/codemirror.v1.css']").length == 0) {
                    $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/codemirror/lib/codemirror.v1.css"/>');
                }
                
                var buttons = [{
                        text: data.create_version_btn,
                        class: 'btn btn-sm green-meadow',
                        click: function() {

                            var $subDialogName = 'dialog-fullexp-sub-' + getUniqueId(1);
                            if (!$("#" + $subDialogName).length) {
                                $('<div id="' + $subDialogName + '"></div>').appendTo('body');
                            }
                            var $subDialog = $("#" + $subDialogName);

                            $.ajax({
                                type: 'post',
                                url: 'mdmeta/fullExpNewVersion',
                                dataType: 'json',
                                beforeSend: function() {
                                    Core.blockUI({message: 'Loading...', boxed: true});
                                },
                                success: function(dataSub) {
                                    $subDialog.empty().append(dataSub.html);
                                    $subDialog.dialog({
                                        cache: false,
                                        resizable: true,
                                        bgiframe: true,
                                        autoOpen: false,
                                        title: dataSub.title,
                                        width: 550,
                                        height: 'auto',
                                        modal: true,
                                        close: function() {
                                            $subDialog.empty().dialog('destroy').remove();
                                        },
                                        buttons: [{
                                                text: dataSub.save_btn,
                                                class: 'btn green-meadow btn-sm',
                                                click: function() {

                                                    $("#new-version-form").validate({ errorPlacement: function() {} });

                                                    if ($("#new-version-form").valid()) {

                                                        fullExpressionEditor.save();
                                                        fullExpressionOpenEditor.save();
                                                        fullExpressionVarFncEditor.save();
                                                        fullExpressionSaveEditor.save();
                                                        fullExpressionAfterSaveEditor.save();

                                                        $.ajax({
                                                            type: 'post',
                                                            url: 'mdmeta/saveNewVersionFullExpression',
                                                            dataType: 'json',
                                                            data: $dialog.find("form#fullExpression-form").serialize() + '&' + $subDialog.find("form#new-version-form").serialize(),
                                                            beforeSend: function() {
                                                                Core.blockUI({animate: true});
                                                            },
                                                            success: function(data) {
                                                                PNotify.removeAll();
                                                                if (data.status === 'success') {
                                                                    new PNotify({
                                                                        title: 'Success',
                                                                        text: data.message,
                                                                        type: 'success',
                                                                        sticker: false
                                                                    });
                                                                    $subDialog.dialog('close');
                                                                } else {
                                                                    if (data.status === 'locked') {
                                                                        lockedRequestMeta(data);
                                                                    } else {
                                                                        new PNotify({
                                                                            title: 'Error',
                                                                            text: data.message,
                                                                            type: 'error',
                                                                            sticker: false
                                                                        });
                                                                    }
                                                                }
                                                                Core.unblockUI();
                                                            }
                                                        });
                                                    }
                                                }
                                            },
                                            {
                                                text: dataSub.close_btn,
                                                class: 'btn blue-hoki btn-sm',
                                                click: function() {
                                                    $subDialog.dialog('close');
                                                }
                                            }
                                        ]
                                    });
                                    $subDialog.dialog('open');

                                    Core.unblockUI();
                                }
                            }).done(function() {
                                Core.initAjax($subDialog);
                            });
                        }
                    },
                    {
                        text: data.save_btn,
                        class: 'btn btn-sm green bp-btn-subsave',
                        click: function() {

                            fullExpressionEditor.save();
                            fullExpressionOpenEditor.save();
                            fullExpressionVarFncEditor.save();
                            fullExpressionSaveEditor.save();
                            fullExpressionAfterSaveEditor.save();

                            $.ajax({
                                type: 'post',
                                url: isTempSave ? 'mdmeta/tempSaveFullExpression' : 'mdmeta/saveFullExpression',
                                dataType: 'json',
                                data: $dialog.find("form#fullExpression-form").serialize(),
                                beforeSend: function() {
                                    Core.blockUI({animate: true});
                                },
                                success: function(data) {
                                    PNotify.removeAll();
                                    if (data.status === 'success') {
                                        new PNotify({
                                            title: 'Success',
                                            text: data.message,
                                            type: 'success',
                                            sticker: false
                                        });
                                        $dialog.dialog('close');
                                    } else {
                                        if (data.status === 'locked') {
                                            lockedRequestMeta(data);
                                        } else {
                                            new PNotify({
                                                title: 'Error',
                                                text: data.message,
                                                type: 'error',
                                                sticker: false
                                            });
                                        }
                                    }
                                    Core.unblockUI();
                                }
                            });
                        }
                    },
                    {
                        text: data.close_btn,
                        class: 'btn btn-sm blue-hoki',
                        click: function() {
                            $dialog.dialog('close');
                        }
                    }
                ];
                
                if (isTempSave) {
                    buttons.shift();
                }
                    
                $dialog.empty().append('<form id="fullExpression-form" method="post">' + data.Html + '</form>');
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 1200,
                    minWidth: 1200,
                    height: "auto",
                    modal: false,
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: buttons
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
                    },
                    "maximize": function() {
                        var dialogHeight = $dialog.height();
                        $dialog.find("div.table-scrollable").css({ "height": dialogHeight, "max-height": dialogHeight });
                        $dialog.find(".CodeMirror").css("height", (dialogHeight - 48) + 'px');
                    },
                    "restore": function() {
                        var dialogHeight = $dialog.height();
                        $dialog.find("div.table-scrollable").css({ "height": (dialogHeight - 25) + 'px', "max-height": (dialogHeight - 25) + 'px' });
                        $dialog.find(".CodeMirror").css("height", (dialogHeight - 48) + 'px');
                    }
                });
                $dialog.dialog('open');
                $dialog.dialogExtend('maximize');
                Core.unblockUI();
            });
        }
    });
}

function bpFullExpressionCP(metaDataId) {
    $.ajax({
        type: 'post',
        url: 'mdmeta/checkLock',
        data: { metaDataId: metaDataId },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({animate: true});
        },
        success: function(passData) {
            if (passData.isLocked == 'true' || passData.isLocked == true) {

                var $dialogName = 'dialog-meta-password';
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $("#" + $dialogName);
                
                $dialog.empty().append(passData.html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: passData.title,
                    width: 500,
                    height: "auto",
                    modal: true,
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [{
                        text: passData.close_btn,
                        class: 'btn blue-madison btn-sm',
                        click: function() {
                            $dialog.dialog('close');
                        }
                    }]
                });
                $dialog.dialog('open');
                Core.unblockUI();

            } else {
                Core.unblockUI();
                bpFullExpression(metaDataId, false);
            }
        },
        error: function() { alert('Error'); }
    });
    clearConsole();
}

function bpFullExpressionCPNew(metaDataId) {
    $.ajax({
        type: 'post',
        url: 'mdmeta/checkLock',
        data: { metaDataId: metaDataId },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({animate: true});
        },
        success: function(passData) {
            if (passData.isLocked == 'true' || passData.isLocked == true) {

                var $dialogName = 'dialog-meta-password';
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $("#" + $dialogName);
                
                $dialog.empty().append(passData.html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: passData.title,
                    width: 500,
                    height: "auto",
                    modal: true,
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [{
                        text: passData.close_btn,
                        class: 'btn blue-madison btn-sm',
                        click: function() {
                            $dialog.dialog('close');
                        }
                    }]
                });
                $dialog.dialog('open');
                Core.unblockUI();

            } else {
                Core.unblockUI();
                bpFullExpressionList(metaDataId);
            }
        },
        error: function() { alert('Error'); }
    });
    clearConsole();
}

function bpFullExpressionList(metaDataId) {
    var $dialogName = 'dialog-fullExpList';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $("#" + $dialogName);

    $.ajax({
        type: 'post',
        url: 'mdmeta/bpFullExpressionList',
        data: { metaDataId: metaDataId },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 800,
                height: "auto",
                modal: false,
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{
                    text: data.close_btn,
                    class: 'btn btn-sm blue-hoki',
                    click: function() {
                        $dialog.dialog('close');
                    }
                }]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        }
    });
}
function metaFullOptions(metaDataId, folderId, element, isMetaArea) {
    $.ajax({
        type: 'post',
        url: 'mdmetadata/metaFullOptions',
        data: {metaDataId: metaDataId, folderId: folderId},
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({boxed: true, message: 'Loading...'});
            
            if (typeof CodeMirror === 'undefined') {
                $.cachedScript('assets/custom/addon/plugins/codemirror/lib/codemirror.min.js').done(function() {
                    $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/codemirror/lib/codemirror.v1.css"/>');
                });
            }
            
            if (!$().iconpicker) {
                $.cachedScript('assets/custom/addon/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js?v=1').done(function() {      
                    $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>');
                });
            }
        },
        success: function(data) {
            PNotify.removeAll();
            
            if (data.status == 'success') {
                
                var $dialogName = 'dialog-bp-' + data.uniqId;
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $("#" + $dialogName);

                $dialog.empty().append(data.html);
                $dialog.dialog({
                    dialogClass: 'bp-settings-dialog', 
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: $(window).width(),
                    height: $(window).height(),
                    modal: true,
                    open: function() {
                        disableScrolling();
                        Core.initUniform($dialog);
                        Core.initLongInput($dialog);
                        Core.initComponentSwitchery($dialog);
                    },
                    beforeClose: function() {
                        
                        var $form = $('#meta-form-v2');
                        
                        if ($dialog.data('can-close')) {
                            $dialog.removeData('can-close');
                            $form.trigger('remove');
                            return true;
                        }

                        if ($form.length && $form.hasAttr('data-changed') && $form.attr('data-changed') == '1') {

                            var dialogNameConfirm = '#dialog-metaedit-confirm';
                            if (!$(dialogNameConfirm).length) {
                                $('<div id="' + dialogNameConfirm.replace('#', '') + '"></div>').appendTo('body');
                            }
                            var $dialogConfirm = $(dialogNameConfirm);

                            $dialogConfirm.html(plang.get('msg_sure_leave_this_page'));
                            $dialogConfirm.dialog({
                                cache: false,
                                resizable: true,
                                bgiframe: true,
                                autoOpen: false,
                                title: plang.get('msg_title_confirm'), 
                                width: 300,
                                height: 'auto',
                                modal: true,
                                buttons: [
                                    {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function() {
                                        $dialogConfirm.dialog('close');
                                        $dialog.data('can-close', true);
                                        $dialog.dialog('close');
                                    }},
                                    {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                        $dialogConfirm.dialog('close');
                                    }}
                                ]
                            });
                            $dialogConfirm.dialog('open');

                            return false;

                        } else {
                            $form.trigger('remove');
                            return true;
                        }
                    },
                    close: function() {
                        enableScrolling();
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow bp-btn-save', click: function(e) {
                            
                            var $metaSystemForm = $('#meta-form-v2');
                            $metaSystemForm.validate({ errorPlacement: function() {} });
                            
                            if ($metaSystemForm.valid()) {
                                var $saveBtn = $(e.target);
                                
                                Core.blockUI({message: plang.get('msg_saving_block'), boxed: true});
                                $saveBtn.attr({'disabled': 'disabled'}).prepend('<i class="fa fa-spinner fa-pulse fa-fw"></i>');

                                setTimeout(function() {
                                    
                                    if (typeof fullExpressionEditor !== 'undefined') {
                                        fullExpressionEditor.save();
                                        fullExpressionOpenEditor.save();
                                        fullExpressionVarFncEditor.save();
                                        fullExpressionSaveEditor.save();  
                                        fullExpressionAfterSaveEditor.save();
                                    }

                                    $metaSystemForm.ajaxSubmit({
                                        type: 'post',
                                        url: 'mdmetadata/updateMetaSystemModuleForm',
                                        dataType: 'json',
                                        success: function(data) {
                                            PNotify.removeAll();

                                            if (data.status == 'success') {

                                                new PNotify({
                                                    title: data.status,
                                                    text: metaSuccessMessage(data.message, metaDataId),
                                                    type: data.status,
                                                    sticker: false, 
                                                    delay: 4000
                                                });
                                                $dialog.data('can-close', true);
                                                $dialog.dialog('close');

                                                if (typeof isMetaArea == 'undefined') {
                                                    if (data.folderId == '' || data.folderId == 'null' || data.folderId == null) {
                                                        metaDataDefault();
                                                    } else {
                                                        refreshList(data.folderId, 'folder');
                                                    }
                                                }

                                            } else {
                                                if (data.status === 'locked') {
                                                    lockedRequestMeta(data);
                                                } else {
                                                    if (typeof data.fieldName !== 'undefined') {
                                                        $metaSystemForm.find("input[name='" + data.fieldName + "']").addClass('error');
                                                    }
                                                    new PNotify({
                                                        title: data.status,
                                                        text: data.message,
                                                        type: data.status,
                                                        sticker: false
                                                    });
                                                }
                                                $saveBtn.removeAttr('disabled').find('i:eq(0)').remove();
                                            }
                                            Core.unblockUI();
                                        }
                                    });
                                
                                }, 100);
                            }
                        }}, 
                        {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open');
                
            } else {
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
            }
            Core.unblockUI();
        },
        error: function() { alert('Error'); Core.unblockUI(); }
    });
}
function createPivotGrid(elem, processMetaDataId, dataViewId, selectedRow) {

    var param = {};

    if ($("input#cardViewerFieldPath", "#object-value-list-" + dataViewId).val() !== '' &&
        $("input#cardViewerValue", "#object-value-list-" + dataViewId).val() !== '') {
        param[$("input#cardViewerFieldPath", "#object-value-list-" + dataViewId).val()] = $("input#cardViewerValue", "#object-value-list-" + dataViewId).val();
    }

    $.ajax({
        type: 'post',
        url: 'mdpivot/createPivotGrid',
        data: { selectedRow: selectedRow, param: param },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            var dialogName = '#dialog-pivot-grid';
            if (!$(dialogName).length) {
                $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
            }
            var $dialog = $(dialogName);
            
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 950,
                height: 'auto',
                modal: true,
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{
                        text: data.save_btn,
                        class: 'btn green-meadow btn-sm',
                        click: function() {

                            $(dialogName).find('form').validate({ errorPlacement: function() {} });

                            if ($(dialogName).find('form').valid()) {

                                var filters = {}, columns = {}, rows = {}, values = {};

                                $(dialogName).find('.pv-filters').find('.pv-field').each(function(fi, fk) {
                                    filters[fi] = $(this).attr('data-field-name');
                                });

                                $(dialogName).find('.pv-columns').find('.pv-field').each(function(ci, ck) {
                                    columns[ci] = $(this).attr('data-field-name');
                                });

                                $(dialogName).find('.pv-rows').find('.pv-field').each(function(ri, rk) {
                                    rows[ri] = $(this).attr('data-field-name');
                                });

                                $(dialogName).find('.pv-values').find('.pv-field').each(function(vi, vk) {
                                    var _this = $(this);
                                    values[_this.attr('data-field-name')] = (typeof _this.attr('data-aggr-name') !== 'undefined') ? _this.attr('data-aggr-name') : 'sum';
                                });

                                $.ajax({
                                    type: 'post',
                                    url: 'mdpivot/createPivotGridSave',
                                    data: {
                                        param: $(dialogName).find('form').serialize(),
                                        filters: filters,
                                        columns: columns,
                                        rows: rows,
                                        values: values
                                    },
                                    dataType: 'json',
                                    beforeSend: function() {
                                        Core.blockUI({message: 'Loading...', boxed: true});
                                    },
                                    success: function(data) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: data.status,
                                            text: data.message,
                                            type: data.status,
                                            sticker: false
                                        });
                                        if (data.status === 'success') {
                                            $dialog.dialog('close');
                                            dataViewReload(dataViewId);
                                        }
                                        Core.unblockUI();
                                    }
                                });
                            }
                        }
                    },
                    {
                        text: data.close_btn,
                        class: 'btn blue-madison btn-sm',
                        click: function() {
                            $(dialogName).dialog('close');
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
            $dialog.dialog('open');
            $dialog.dialogExtend("maximize");

            Core.initDVAjax($dialog);
            Core.unblockUI();
        },
        error: function() { alert('Error'); }
    });
}

function editPivotGrid(elem, processMetaDataId, dataViewId, selectedRow) {
    $.ajax({
        type: 'post',
        url: 'mdpivot/editPivotGrid',
        data: {
            selectedRow: selectedRow
        },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function(data) {
            var dialogName = '#dialog-pivot-grid';
            if (!$(dialogName).length) {
                $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
            }

            $(dialogName).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 950,
                height: 'auto',
                modal: true,
                close: function() {
                    $(dialogName).empty().dialog('destroy').remove();
                },
                buttons: [{
                        text: data.save_btn,
                        class: 'btn green-meadow btn-sm',
                        click: function() {

                            $(dialogName).find('form').validate({ errorPlacement: function() {} });

                            if ($(dialogName).find('form').valid()) {

                                var filters = {},
                                    columns = {},
                                    rows = {},
                                    values = {};

                                $(dialogName).find('.pv-filters').find('.pv-field').each(function(fi, fk) {
                                    filters[fi] = $(this).attr('data-field-name');
                                });

                                $(dialogName).find('.pv-columns').find('.pv-field').each(function(ci, ck) {
                                    columns[ci] = $(this).attr('data-field-name');
                                });

                                $(dialogName).find('.pv-rows').find('.pv-field').each(function(ri, rk) {
                                    rows[ri] = $(this).attr('data-field-name');
                                });

                                $(dialogName).find('.pv-values').find('.pv-field').each(function(vi, vk) {
                                    var _this = $(this);
                                    values[_this.attr('data-field-name')] = (typeof _this.attr('data-aggr-name') !== 'undefined') ? _this.attr('data-aggr-name') : 'sum';
                                });

                                $.ajax({
                                    type: 'post',
                                    url: 'mdpivot/editPivotGridSave',
                                    data: {
                                        param: $(dialogName).find('form').serialize(),
                                        filters: filters,
                                        columns: columns,
                                        rows: rows,
                                        values: values
                                    },
                                    dataType: 'json',
                                    beforeSend: function() {
                                        Core.blockUI({
                                            message: 'Loading...',
                                            boxed: true
                                        });
                                    },
                                    success: function(data) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: data.status,
                                            text: data.message,
                                            type: data.status,
                                            sticker: false
                                        });
                                        if (data.status === 'success') {
                                            $(dialogName).dialog('close');
                                            dataViewReload(dataViewId);
                                        }
                                        Core.unblockUI();
                                    }
                                });
                            }
                        }
                    },
                    {
                        text: data.close_btn,
                        class: 'btn blue-madison btn-sm',
                        click: function() {
                            $(dialogName).dialog('close');
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
            $(dialogName).empty().append(data.html);
            $(dialogName).dialog('open');
            $(dialogName).dialogExtend("maximize");

            Core.initDVAjax($(dialogName));
            Core.unblockUI();
        },
        error: function() {
            alert('Error');
        }
    });
}

function showDmPivotGrid(elem) {
    var _this = $(elem);
    var rowData = _this.attr('data-row-data');
    var jsonObj = JSON.parse(rowData);

    if ('reportmodelid' in Object(jsonObj)) {
        var reportModelId = jsonObj.reportmodelid;
    } else {
        var reportModelId = jsonObj.id;
    }

    $.ajax({
        type: 'post',
        url: 'mdpivot/showPivotGrid',
        data: { reportModelId: reportModelId },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function(data) {
            var dialogName = '#dialog-pivot-grid';
            if (!$(dialogName).length) {
                $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
            }

            $(dialogName).empty().append(data.html);
            $(dialogName).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 950,
                height: 'auto',
                modal: true,
                close: function() {
                    $(dialogName).empty().dialog('destroy').remove();
                },
                buttons: [{
                    text: data.close_btn,
                    class: 'btn blue-madison btn-sm',
                    click: function() {
                        $(dialogName).dialog('close');
                    }
                }]
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
            $(dialogName).dialog('open');
            $(dialogName).dialogExtend("maximize");

            Core.initDVAjax($(dialogName));
            Core.unblockUI();
        },
        error: function() {
            alert('Error');
        }
    });
}
function pfObjectExport(elem, processMetaDataId, dataViewId, selectedRow, paramData) {
    Core.blockUI({
        boxed: true,
        message: 'Exporting...'
    });
    $.fileDownload(URL_APP + 'mdmeta/pfObjectExport', {
        httpMethod: 'POST',
        data: { selectedRow: selectedRow, paramData: paramData }
    }).done(function() {
        PNotify.removeAll();
        new PNotify({
            title: 'Success',
            text: 'Successful Export',
            type: 'success',
            sticker: false
        });
        Core.unblockUI();
    }).fail(function(msg, url) {
        PNotify.removeAll();
        new PNotify({
            title: 'Error',
            text: msg,
            type: 'error',
            sticker: false
        });
        Core.unblockUI();
    });
}
function showPfCheckListButton(elem, tempName, tempId, dataViewId, refStructureId) {

    var $process_buttons = $('.dv-process-buttons', '#object-value-list-' + dataViewId);

    if ($process_buttons.length) {

        var buttonHtml = '<button type="button" data-tempid="' + tempId + '"' +
            ' data-tempname="' + tempName + '" data-dvid="' + dataViewId + '"' +
            ' data-refstructureid="' + refStructureId + '"' +
            ' onclick="showPfCheckListForm(this);"' +
            ' class="btn btn-warning btn-circle btn-sm dv-checklist-btn">' +
            '<i class="fa fa-check"></i> Check List</button>';

        $process_buttons.find('button.dv-checklist-btn').remove();
        $process_buttons.append(buttonHtml);
    }
    return;
}
function hidePfCheckListButton(elem, dataViewId) {
    var $process_buttons = $('.dv-process-buttons', '#object-value-list-' + dataViewId);

    if ($process_buttons.length) {
        $process_buttons.find('button.dv-checklist-btn').remove();
    }
    return;
}
function showPfCheckListForm(elem) {

    var _this = $(elem);
    var tempId = _this.attr('data-tempid');
    var tempName = _this.attr('data-tempname');
    var dataViewId = _this.attr('data-dvid');
    var refStructureId = _this.attr('data-refstructureid');
    var row = getDataViewSelectedRowByIndex(dataViewId);
    var $dialogName = 'dialog-checklist-' + getUniqueId(1);

    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }

    var $dialog = $('#' + $dialogName);

    $.ajax({
        type: 'post',
        url: 'mdcommon/showCheckListForm',
        data: {
            tempId: tempId,
            tempName: tempName,
            dataViewId: dataViewId,
            refStructureId: refStructureId,
            row: row
        },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function(data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 550,
                height: 400,
                modal: true,
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{
                        text: data.save_btn,
                        class: 'btn green-meadow btn-sm',
                        click: function() {

                            $("#pf-checklist-form").validate({ errorPlacement: function() {} });

                            if ($("#pf-checklist-form").valid()) {

                                $.ajax({
                                    type: 'post',
                                    url: 'mdcommon/saveCheckListForm',
                                    data: $("#pf-checklist-form").serialize(),
                                    dataType: 'json',
                                    beforeSend: function() {
                                        Core.blockUI({
                                            message: 'Loading...',
                                            boxed: true
                                        });
                                    },
                                    success: function(data) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: data.status,
                                            text: data.message,
                                            type: data.status,
                                            sticker: false
                                        });
                                        if (data.status === 'success') {
                                            $dialog.dialog('close');
                                        }
                                        Core.unblockUI();
                                    }
                                });
                            }
                        }
                    },
                    {
                        text: data.close_btn,
                        class: 'btn blue-hoki btn-sm',
                        click: function() {
                            $dialog.dialog('close');
                        }
                    }
                ]
            });
            $dialog.dialog('open');

            Core.unblockUI();
        }
    }).done(function() {
        Core.initAjax($dialog);
    });
}
function metaUnLockByMenu(elem, metaId, typeCode, param) {
    $.ajax({
        type: 'post',
        url: 'mdmeta/metaUnLockForm',
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({animate: true});
        },
        success: function(passData) {

            var $dialogName = 'dialog-meta-unlock';
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName);

            $dialog.empty().append(passData.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: passData.title,
                width: 500,
                height: 'auto',
                modal: true,
                open: function() {
                    $(this).keypress(function(e) {
                        if (e.keyCode == $.ui.keyCode.ENTER) {
                            $(this).parent().find(".ui-dialog-buttonpane button:last").trigger("click");
                        }
                    });
                    //$(this).parent().find(".ui-dialog-buttonpane button:first").html('<i class="fa fa-refresh"></i>').attr('title', 'Нууц үг сэргээх');
                },
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{
                        text: 'Нууц үг сэргээх',
                        class: 'btn btn-sm purple-plum float-left',
                        click: function() {
                            $.ajax({
                                type: 'post',
                                url: 'mdmeta/metaUnLockPasswordReset',
                                data: { metaDataId: metaId },
                                dataType: 'json',
                                beforeSend: function() {
                                    Core.blockUI({message: 'Loading...', boxed: true});
                                },
                                success: function(dataCheck) {
                                    if (dataCheck.hasOwnProperty('html')) {
                                        metaUnLockGetPassChooseMode(metaId, dataCheck.html);
                                    } else {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: dataCheck.status,
                                            text: dataCheck.message,
                                            type: dataCheck.status,
                                            sticker: false
                                        });
                                    }

                                    Core.unblockUI();
                                },
                                error: function() { alert('Error'); }
                            });
                            clearConsole();
                        }
                    },
                    {
                        text: passData.change_btn,
                        class: 'btn btn-sm purple-plum float-left',
                        click: function() {
                            var $dialogName = '#dialog-change-password';
                            if (!$($dialogName).length) {
                                $('<div id="' + $dialogName.replace('#', '') + '"></div>').appendTo('body');
                            }
                            var $dialog = $($dialogName);

                            $.ajax({
                                type: 'post',
                                url: 'profile/changePasswordForm',
                                dataType: 'json',
                                beforeSend: function() {
                                    Core.blockUI({animate: true});
                                },
                                success: function(data) {
                                    $dialog.empty().append(data.html);
                                    $dialog.dialog({
                                        cache: false,
                                        resizable: true,
                                        bgiframe: true,
                                        autoOpen: false,
                                        title: data.title,
                                        width: 500,
                                        minWidth: 500,
                                        height: "auto",
                                        modal: true,
                                        closeOnEscape: isCloseOnEscape,
                                        close: function() {
                                            $dialog.empty().dialog('destroy').remove();
                                        },
                                        buttons: [{
                                                text: data.save_btn,
                                                "class": 'btn btn-sm green-meadow',
                                                click: function() {
                                                    $("#form-change-password").validate({
                                                        rules: {
                                                            currentPassword: {
                                                                required: true
                                                            },
                                                            newPassword: {
                                                                required: true
                                                            },
                                                            confirmPassword: {
                                                                required: true,
                                                                equalTo: "#newPassword"
                                                            }
                                                        },
                                                        messages: {
                                                            currentPassword: {
                                                                required: plang.get('user_insert_password')
                                                            },
                                                            newPassword: {
                                                                required: plang.get('user_insert_password')
                                                            },
                                                            confirmPassword: {
                                                                required: plang.get('user_insert_password'),
                                                                equalTo: plang.get('user_equal_password')
                                                            }
                                                        }
                                                    });

                                                    if ($("#form-change-password").valid()) {
                                                        $.ajax({
                                                            type: 'post',
                                                            url: 'mdmeta/changePassword',
                                                            data: $("#form-change-password").serialize() + "&metaDataId=" + metaId,
                                                            dataType: "json",
                                                            beforeSend: function() {
                                                                Core.blockUI({animate: true});
                                                            },
                                                            success: function(data) {
                                                                PNotify.removeAll();
                                                                new PNotify({
                                                                    title: data.status,
                                                                    text: data.message,
                                                                    type: data.status,
                                                                    sticker: false
                                                                });
                                                                if (data.status === 'success') {
                                                                    $dialog.dialog("close");
                                                                }
                                                                Core.unblockUI();
                                                            },
                                                            error: function() { alert("Error"); }
                                                        });
                                                    }
                                                }
                                            },
                                            {
                                                text: data.close_btn,
                                                "class": 'btn btn-sm blue-hoki',
                                                click: function() {
                                                    $dialog.dialog('close');
                                                }
                                            }
                                        ]
                                    });
                                    $dialog.dialog('open');
                                    Core.unblockUI();
                                },
                                error: function() { alert("Error"); }
                            });
                        }
                    },
                    {
                        text: passData.login_btn,
                        class: 'btn btn-sm green-meadow',
                        click: function() {
                            $("#metaUnlockForm", "#" + $dialogName).validate({ errorPlacement: function() {} });

                            if ($("#metaUnlockForm", "#" + $dialogName).valid()) {
                                $.ajax({
                                    type: 'post',
                                    url: 'mdmeta/metaUnLock',
                                    data: "metaDataId=" + metaId + "&passwordHash=" + $("#passwordHash", "#" + $dialogName).val(),
                                    dataType: 'json',
                                    beforeSend: function() {
                                        Core.blockUI({animate: true});
                                    },
                                    success: function(dataCheck) {
                                        PNotify.removeAll();

                                        if (dataCheck.status === 'success') {

                                            $dialog.dialog('close');

                                            if (typeCode == 'dataview' || typeCode == 'package' || typeCode == 'workspace' || typeCode == 'statement') {
                                                appMultiTab(param, elem);
                                            } else if (typeCode == 'process') {
                                                callWebServiceByMeta(metaId, true, '', false, { callerType: param.callerType, isMenu: param.isMenu });
                                            }

                                        } else {
                                            new PNotify({
                                                title: 'Error',
                                                text: dataCheck.message,
                                                type: 'error',
                                                sticker: false
                                            });
                                        }

                                        Core.unblockUI();
                                    },
                                    error: function() { alert('Error'); }
                                });
                                clearConsole();
                            }
                        }
                    }
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        },
        error: function() { alert('Error'); }
    });
    clearConsole();
}
function metaUnLockGetPassChooseMode(metaId, html) {
    var $dialogName = 'dialog-meta-unlock-choosemode';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);

    $dialog.empty().append(html);
    $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: plang.get('select_btn'),
        width: 300,
        height: 'auto',
        modal: true,
        open: function() {
            $(this).keypress(function(e) {
                if (e.keyCode == $.ui.keyCode.ENTER) {
                    $(this).parent().find(".ui-dialog-buttonpane button:last").trigger("click");
                }
            });
        },
        close: function() {
            $dialog.dialog('destroy').remove();
        },
        buttons: [{
                text: plang.get('send_btn'),
                class: 'btn btn-sm green-meadow',
                click: function() {
                    $.ajax({
                        type: 'post',
                        url: 'mdmeta/metaUnLockPasswordReset',
                        data: 'metaDataId='+metaId+'&'+$('#metaunlock-getpass-mode').serialize(), 
                        dataType: 'json',
                        beforeSend: function() {
                            Core.blockUI({message: 'Loading...', boxed: true});
                        },
                        success: function(dataCheck) {
                            PNotify.removeAll();
                            new PNotify({
                                title: dataCheck.status,
                                text: dataCheck.message,
                                type: dataCheck.status,
                                sticker: false
                            });
                            
                            if (dataCheck.status == 'success') {
                                $dialog.dialog('close');
                            }

                            Core.unblockUI();
                        },
                        error: function() { alert('Error'); }
                    });
                    clearConsole();
                }
            }, 
            {
                text: plang.get('close_btn'),
                class: 'btn btn-sm purple-plum',
                click: function() {
                    $dialog.dialog('close');
                }
            }
        ]
    });
    Core.initUniform($dialog);
    $dialog.dialog('open');
}
function kpiExportInit(elem, processMetaDataId, dataViewId, paramData) {
    if (typeof isKpiAddonScript === 'undefined') {
        $.getScript('middleware/assets/js/addon/kpi.js').done(function() {
            kpiExport(elem, processMetaDataId, dataViewId, paramData);
        });
    } else {
        kpiExport(elem, processMetaDataId, dataViewId, paramData);
    }
}
function kpiImportInit(elem, dataViewId, getParams) {
    if (typeof isKpiAddonScript === 'undefined') {
        $.getScript('middleware/assets/js/addon/kpi.js').done(function() {
            kpiImport(elem, dataViewId, getParams);
        });
    } else {
        kpiImport(elem, dataViewId, getParams);
    }
}
function metaExportInit(elem, processMetaDataId, dataViewId, postParams) {
    if (typeof isMetaUpgrade === 'undefined') {
        $.getScript('middleware/assets/js/upgrade/script.js').done(function() {
            metaExportFromList(elem, processMetaDataId, dataViewId, postParams);
        });
    } else {
        metaExportFromList(elem, processMetaDataId, dataViewId, postParams);
    }
}
function umDataPermissionRender(selectedRow, callback) {
    $.ajax({
        type: 'post',
        url: 'mdum/dataPermission',
        data: { selectedRow: selectedRow },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                animate: true
            });
        },
        success: function(data) {
            PNotify.removeAll();

            if (typeof callback !== 'function') {
                var config = {
                    title: data.Title,
                    width: data.width,
                    buttons: [{
                            text: data.save_btn,
                            class: 'btn btn-success btn-sm',
                            click: function() {
                                if (typeof MdUmDataPermission !== 'undefined') {
                                    MdUmDataPermission.saveDataPermission();
                                }
                            }
                        },
                        {
                            text: data.close_btn,
                            class: 'btn btn-sm blue-madison',
                            click: function() {
                                $('#dialog-data-permission').dialog('close');
                            }
                        }
                    ]
                };

                Core.initDialog('dialog-data-permission', data.html, config, function($dialog) {
                    $dialog.dialogExtend("maximize");
                });
            } else {
                callback(data);
            }

            Core.unblockUI();
        },
        error: function() {
            new PNotify({
                title: 'Error',
                type: 'error',
                sticker: false
            });
        }
    });
}

function datePermissionCriteriaRender(selectedRow, callback) {
    $.ajax({
        type: 'post',
        url: 'mdum/datePermissionCriteriaRender',
        data: { selectedRow: selectedRow },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                animate: true
            });
        },
        success: function(data) {
            PNotify.removeAll();

            if (typeof callback !== 'function') {
                var config = {
                    title: data.Title,
                    width: data.width,
                    buttons: [{
                            text: data.save_btn,
                            class: 'btn btn-success btn-sm',
                            click: function() {
                                if (typeof mdUmDataPermissionCriteria !== 'undefined') {
                                    mdUmDataPermissionCriteria.saveDataPermissionCriteria();
                                }
                            }
                        },
                        {
                            text: data.close_btn,
                            class: 'btn btn-sm blue-madison',
                            click: function() {
                                $('#dialog-datePermissionCriteriaRender').dialog('close');
                            }
                        }
                    ]
                };

                Core.initDialog('dialog-datePermissionCriteriaRender', data.html, config, function($dialog) {
                    //                    $dialog.dialogExtend("maximize");
                });
            } else {
                callback(data);
            }

            Core.unblockUI();

        },
        error: function() {
            new PNotify({
                title: 'Error',
                type: 'error',
                sticker: false
            });
        }
    });
}
function isLockMeta(id, type) {
    $.ajax({
        type: 'post',
        url: 'mdmeta/isLock',
        data: { id: id },
        dataType: 'json',
        success: function(response) {
            if (response.isLocked == true || response.isLocked == 'true') {
                lockedMeta(id, type, response.personName);
            } else {
                lockMeta(id);
            }
        }
    });
}
function lockedMeta(id, type, personName) {
    var $dialogName = 'dialog-lock-' + getUniqueId(1);
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $("#" + $dialogName);

    $.ajax({
        type: 'post',
        url: 'mdmeta/locked',
        data: { id: id, personName: personName },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function(data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 530,
                height: 'auto',
                modal: true,
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{
                        text: data.unlock_btn,
                        class: 'btn red btn-sm',
                        click: function() {
                            unlockMeta(id, $dialogName);
                        }
                    },
                    {
                        text: data.access_btn,
                        class: 'btn yellow btn-sm',
                        click: function() {
                            shareLockMeta(id, $dialogName);
                        }
                    },
                    {
                        text: data.close_btn,
                        class: 'btn blue-hoki btn-sm',
                        click: function() {
                            $dialog.dialog('close');
                        }
                    }
                ]
            });
            $dialog.dialog('open');

            Core.unblockUI();
        },
        error: function() { alert('Error'); }
    }).done(function() { Core.initAjax($dialog); });
}

function lockMeta(id) {
    var $dialogName = 'dialog-lock-' + getUniqueId(1);
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $("#" + $dialogName);

    $.ajax({
        type: 'post',
        url: 'mdmeta/lockMeta',
        data: { id: id },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 530,
                height: 'auto',
                modal: true,
                open: function() {
                    $(this).keypress(function(e) {
                        if (e.keyCode == $.ui.keyCode.ENTER) {
                            $(this).parent().find(".ui-dialog-buttonpane button:first").trigger("click");
                        }
                    });
                },
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{
                        text: data.lock_btn,
                        class: 'btn green-meadow btn-sm',
                        click: function() {

                            $("#metaLockForm").validate({ errorPlacement: function() {} });

                            if ($("#metaLockForm").valid()) {

                                $('#metaLockForm', '#' + $dialogName).ajaxSubmit({
                                    type: 'post',
                                    url: 'mdmeta/locking',
                                    dataType: 'json',
                                    beforeSend: function() {
                                        Core.blockUI({message: 'Loading...', boxed: true});
                                    },
                                    success: function(data) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: data.status,
                                            text: data.message,
                                            type: data.status,
                                            sticker: false
                                        });
                                        if (data.status === 'success') {
                                            $dialog.dialog('close');
                                        }
                                        Core.unblockUI();
                                    }
                                });
                            }
                        }
                    },
                    {
                        text: data.close_btn,
                        class: 'btn blue-hoki btn-sm',
                        click: function() {
                            $dialog.dialog('close');
                        }
                    }
                ]
            });
            $dialog.dialog('open');

            Core.unblockUI();
        },
        error: function() { alert('Error'); }
    }).done(function() {
        Core.initAjax($dialog);
    });
}

function unlockMeta(id, prevDialogName) {
    var $dialogName = 'dialog-unlock-' + getUniqueId(1);
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $("#" + $dialogName);

    $.ajax({
        type: 'post',
        url: 'mdmeta/unlockMeta',
        data: { id: id },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 530,
                height: 'auto',
                modal: true,
                open: function() {
                    $(this).keypress(function(e) {
                        if (e.keyCode == $.ui.keyCode.ENTER) {
                            $(this).parent().find(".ui-dialog-buttonpane button:first").trigger("click");
                        }
                    });
                },
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{
                        text: data.unlock_btn,
                        class: 'btn green-meadow btn-sm',
                        click: function() {

                            $("#metaUnLockForm").validate({ errorPlacement: function() {} });

                            if ($("#metaUnLockForm").valid()) {

                                $('#metaUnLockForm', '#' + $dialogName).ajaxSubmit({
                                    type: 'post',
                                    url: 'mdmeta/unlocking',
                                    dataType: 'json',
                                    beforeSend: function() {
                                        Core.blockUI({message: 'Loading...', boxed: true});
                                    },
                                    success: function(data) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: data.status,
                                            text: data.message,
                                            type: data.status,
                                            sticker: false
                                        });
                                        if (data.status === 'success') {
                                            $dialog.dialog('close');
                                            $("#" + prevDialogName).dialog('close');
                                        }
                                        Core.unblockUI();
                                    }
                                });
                            }
                        }
                    },
                    {
                        text: plang.get('close_btn'),
                        class: 'btn blue-hoki btn-sm',
                        click: function() {
                            $dialog.dialog('close');
                        }
                    }
                ]
            });
            $dialog.dialog('open');

            Core.unblockUI();
        },
        error: function() { alert('Error'); }
    }).done(function() { Core.initAjax($dialog); });
}

function shareLockMeta(id, prevDialogName) {
    var $dialogName = 'dialog-sharelock-' + getUniqueId(1);
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $("#" + $dialogName);

    $.ajax({
        type: 'post',
        url: 'mdmeta/shareLockMeta',
        data: { id: id },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 530,
                height: 'auto',
                modal: true,
                open: function() {
                    $(this).keypress(function(e) {
                        if (e.keyCode == $.ui.keyCode.ENTER) {
                            $(this).parent().find(".ui-dialog-buttonpane button:first").trigger("click");
                        }
                    });
                },
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{
                        text: plang.get('save_btn'),
                        class: 'btn green-meadow btn-sm',
                        click: function() {

                            $("#metaShareLockForm").validate({ errorPlacement: function() {} });

                            if ($("#metaShareLockForm").valid()) {

                                $('#metaShareLockForm', '#' + $dialogName).ajaxSubmit({
                                    type: 'post',
                                    url: 'mdmeta/sharelocking',
                                    dataType: 'json',
                                    beforeSend: function() {
                                        Core.blockUI({message: 'Loading...', boxed: true});
                                    },
                                    success: function(data) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: data.status,
                                            text: data.message,
                                            type: data.status,
                                            sticker: false
                                        });
                                        if (data.status === 'success') {
                                            $dialog.dialog('close');
                                            $("#" + prevDialogName).dialog('close');
                                        }
                                        Core.unblockUI();
                                    }
                                });
                            }
                        }
                    },
                    {
                        text: plang.get('close_btn'),
                        class: 'btn blue-hoki btn-sm',
                        click: function() {
                            $dialog.dialog('close');
                        }
                    }
                ]
            });
            $dialog.dialog('open');

            Core.unblockUI();
        },
        error: function() { alert('Error'); }
    }).done(function() { Core.initAjax($dialog); });
}
function importExcelTemplate(elem, processMetaDataId, dataViewId, selectedRow, paramData, getParams) {
    var $dialogName = 'dialog-importexcel';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);

    paramData.push({ name: 'getParams', value: getParams });

    $.ajax({
        type: 'post',
        url: 'mddatamodel/importExcelTemplate',
        data: paramData,
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 600,
                height: 'auto',
                modal: true,
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{
                        text: data.import_btn,
                        class: 'btn green-meadow btn-sm',
                        click: function() {

                            $("#excelTempImport-form").validate({ errorPlacement: function() {} });

                            if ($("#excelTempImport-form").valid()) {

                                $('#excelTempImport-form', '#' + $dialogName).ajaxSubmit({
                                    type: 'post',
                                    url: 'mddatamodel/importingExcelTemplate',
                                    dataType: 'json',
                                    beforeSend: function() {
                                        Core.blockUI({message: 'Importing...', boxed: true});
                                    },
                                    success: function(data) {
                                        PNotify.removeAll();

                                        if (data.status === 'success') {
                                            new PNotify({
                                                title: 'Success',
                                                text: data.message,
                                                type: 'success',
                                                sticker: false,
                                                hide: true,
                                                addclass: pnotifyPosition,
                                                delay: 100000000000
                                            });
                                            $dialog.dialog('close');

                                        } else {

                                            $('input#excelFile', '#' + $dialogName).val('');

                                            if (data.hasOwnProperty('uniqId')) {
                                                new PNotify({
                                                    title: data.status,
                                                    text: data.message + '<br /><br /><a href="javascript:;" class="btn green btn-xs" onclick="errorExcelFileDownload(\'' + data.uniqId + '\', \'' + data.fileExtension + '\');"><i class="fa fa-download"></i> Файл татах</a>',
                                                    type: data.status,
                                                    sticker: false,
                                                    hide: true,
                                                    insert_brs: false,
                                                    addclass: pnotifyPosition,
                                                    delay: 100000000000
                                                });
                                            } else {
                                                new PNotify({
                                                    title: data.status,
                                                    text: data.message,
                                                    type: data.status,
                                                    sticker: false,
                                                    hide: true,
                                                    insert_brs: false,
                                                    addclass: pnotifyPosition,
                                                    delay: 100000000000
                                                });
                                            }
                                        }
                                        Core.unblockUI();
                                    }
                                });
                            }
                        }
                    },
                    {
                        text: data.close_btn,
                        class: 'btn blue-hoki btn-sm',
                        click: function() {
                            $dialog.dialog('close');
                        }
                    }
                ]
            });
            $dialog.dialog('open');

            Core.unblockUI();
        },
        error: function() {
            alert('Error');
        }
    }).done(function() {
        Core.initAjax($dialog);
    });
}

function errorExcelFileDownload(uniqId, fileExtension) {
    $.fileDownload(URL_APP + 'mddatamodel/errorExcelFileDownload', {
        httpMethod: 'post',
        data: { uniqId: uniqId, fileExtension: fileExtension }
    }).done(function() {
    }).fail(function(response) {
        new PNotify({
            title: 'Error',
            text: response,
            type: 'error',
            sticker: false
        });
    });
}
function importExcelTemplateAdd(elem, processMetaDataId, dataViewId, selectedRow, getParams, paramData) {
    var $dialogName = 'dialog-importexcel-add';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }

    $.ajax({
        type: 'post',
        url: 'mddatamodel/importExcelTemplateAdd',
        data: { getParams: getParams, paramData: paramData },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function(data) {
            $("#" + $dialogName).empty().append(data.html);
            $("#" + $dialogName).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 950,
                height: 'auto',
                modal: true,
                close: function() {
                    $("#" + $dialogName).empty().dialog('destroy').remove();
                },
                buttons: [{
                        text: data.save_btn,
                        class: 'btn green-meadow btn-sm',
                        click: function() {

                            $("#excelTempImportAdd-form").validate({ errorPlacement: function() {} });

                            if ($("#excelTempImportAdd-form").valid()) {

                                $('#excelTempImportAdd-form', '#' + $dialogName).ajaxSubmit({
                                    type: 'post',
                                    url: 'mddatamodel/importExcelTemplateAddSave',
                                    dataType: 'json',
                                    beforeSend: function() {
                                        Core.blockUI({
                                            message: 'Saving...',
                                            boxed: true
                                        });
                                    },
                                    success: function(data) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: data.status,
                                            text: data.message,
                                            type: data.status,
                                            sticker: false
                                        });
                                        if (data.status === 'success') {
                                            dataViewReload(dataViewId);
                                            $("#" + $dialogName).dialog('close');
                                        }
                                        Core.unblockUI();
                                    }
                                });
                            }
                        }
                    },
                    {
                        text: data.close_btn,
                        class: 'btn blue-hoki btn-sm',
                        click: function() {
                            $("#" + $dialogName).dialog('close');
                        }
                    }
                ]
            });
            $("#" + $dialogName).dialog('open');

            Core.unblockUI();
        },
        error: function() {
            alert('Error');
        }
    }).done(function() {
        Core.initAjax($("#" + $dialogName));
    });
}

function importExcelTemplateEdit(elem, processMetaDataId, dataViewId, selectedRow) {
    var $dialogName = 'dialog-importexcel-add';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $("#" + $dialogName);

    $.ajax({
        type: 'post',
        url: 'mddatamodel/importExcelTemplateEdit',
        data: { id: selectedRow.id },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 950,
                height: 'auto',
                modal: true,
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{
                        text: data.save_btn,
                        class: 'btn green-meadow btn-sm',
                        click: function() {

                            $("#excelTempImportAdd-form").validate({ errorPlacement: function() {} });

                            if ($("#excelTempImportAdd-form").valid()) {

                                $('#excelTempImportAdd-form', '#' + $dialogName).ajaxSubmit({
                                    type: 'post',
                                    url: 'mddatamodel/importExcelTemplateEditSave',
                                    dataType: 'json',
                                    beforeSend: function() {
                                        Core.blockUI({message: 'Saving...', boxed: true});
                                    },
                                    success: function(data) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: data.status,
                                            text: data.message,
                                            type: data.status,
                                            sticker: false
                                        });
                                        if (data.status === 'success') {
                                            dataViewReload(dataViewId);
                                            $dialog.dialog('close');
                                        }
                                        Core.unblockUI();
                                    }
                                });
                            }
                        }
                    },
                    {
                        text: data.close_btn,
                        class: 'btn blue-hoki btn-sm',
                        click: function() {
                            $dialog.dialog('close');
                        }
                    }
                ]
            });
            $dialog.dialog('open');

            Core.unblockUI();
        },
        error: function() {
            alert('Error');
        }
    }).done(function() {
        Core.initAjax($dialog);
    });
}
function folderCacheClear(id) {
    $.ajax({
        type: 'post',
        url: 'mdmeta/folderCacheClear',
        data: {id: id},
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            PNotify.removeAll();
            new PNotify({
                title: data.status,
                text: data.message,
                type: data.status,
                sticker: false
            });
            Core.unblockUI();
        },
        error: function() { alert('Error'); }
    });
}
function importLicense(elem, processMetaDataId, dataViewId) {
    $.ajax({
        type: 'post',
        url: 'mdlicense/importLicense',
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            PNotify.removeAll();
            new PNotify({
                title: data.status,
                text: data.message,
                type: data.status,
                sticker: false
            });
            if (data.status == 'success') {
                dataViewReload(dataViewId);
            }
            Core.unblockUI();
        },
        error: function() { alert('Error'); }
    });
}

function lockedRequestMeta(options) {
    var $dialogName = 'dialog-lock-confirm';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);

    $dialog.empty().append(options.html);
    $dialog.dialog({
        cache: false,
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        title: options.title,
        width: 350,
        height: 'auto',
        modal: true,
        close: function() {
            $dialog.empty().dialog('destroy').remove();
        },
        buttons: [{
                text: options.request_btn,
                class: 'btn green-meadow btn-sm',
                click: function() {

                    $dialog.dialog('close');

                    var $dialogNameRequest = 'dialog-lock-request';
                    if (!$("#" + $dialogNameRequest).length) {
                        $('<div id="' + $dialogNameRequest + '"></div>').appendTo('body');
                    }
                    var $dialogRequest = $('#' + $dialogNameRequest);

                    $.ajax({
                        type: 'post',
                        url: 'mdlock/requestEdit',
                        dataType: 'json',
                        beforeSend: function() {
                            Core.blockUI({message: 'Loading...', boxed: true});
                        },
                        success: function(data) {

                            $dialogRequest.empty().append(data.html);
                            $dialogRequest.dialog({
                                cache: false,
                                resizable: false,
                                bgiframe: true,
                                autoOpen: false,
                                title: data.title,
                                width: 600,
                                height: 'auto',
                                modal: true,
                                close: function() {
                                    $dialogRequest.empty().dialog('destroy').remove();
                                },
                                buttons: [{
                                        text: data.send_btn,
                                        class: 'btn green-meadow btn-sm',
                                        click: function() {

                                            $('#request-edit-form').validate({ errorPlacement: function() {} });

                                            if ($('#request-edit-form').valid()) {
                                                $.ajax({
                                                    type: 'post',
                                                    url: 'mdlock/sendRequestEdit',
                                                    data: $('#request-edit-form').serialize() + '&metaDataId=' + options.metaDataId,
                                                    dataType: 'json',
                                                    beforeSend: function() {
                                                        Core.blockUI({message: 'Loading...', boxed: true});
                                                    },
                                                    success: function(dataSub) {
                                                        Core.unblockUI();

                                                        PNotify.removeAll();
                                                        new PNotify({
                                                            title: dataSub.status,
                                                            text: dataSub.message,
                                                            type: dataSub.status,
                                                            sticker: false
                                                        });

                                                        if (dataSub.status == 'success') {
                                                            $dialogRequest.dialog('close');
                                                        }
                                                    }
                                                });
                                            }
                                        }
                                    },
                                    {
                                        text: data.close_btn,
                                        class: 'btn blue-madison btn-sm',
                                        click: function() {
                                            $dialogRequest.dialog('close');
                                        }
                                    }
                                ]
                            });
                            $dialogRequest.dialog('open');
                        }
                    }).done(function() {
                        Core.initDateTimeInput($dialogRequest);
                        Core.unblockUI();
                    });
                }
            },
            {
                text: options.close_btn,
                class: 'btn blue-madison btn-sm',
                click: function() {
                    $dialog.dialog('close');
                }
            }
        ]
    });
    $dialog.dialog('open');
}
function posApiSendDataInit(dataViewId, row) {
    if (typeof isPosAddonScript === 'undefined') {
        $.getScript('middleware/assets/js/pos/addon.js').done(function() {
            posApiSendData(dataViewId, row);
        });
    } else {
        posApiSendData(dataViewId, row);
    }
}
function posDiscountDrugImportInit(dataViewId) {
    if (typeof isPosAddonScript === 'undefined') {
        $.getScript('middleware/assets/js/pos/addon.js').done(function() {
            posDiscountDrugImport(dataViewId);
        });
    } else {
        posDiscountDrugImport(dataViewId);
    }
}
function electronRegisterLegal(elem, processMetaDataId, dataViewId, selectedRow, paramData, $type) {
    if (typeof IS_LOAD_ERL_SCRIPT === 'undefined') {
        $.getScript("assets/custom/addon/scripts/project/erl.js").done(function() {
            electronRegisterLegalInit(elem, processMetaDataId, dataViewId, selectedRow, paramData, $type);
        });
    } else {
        electronRegisterLegalInit(elem, processMetaDataId, dataViewId, selectedRow, paramData, $type);
    }
}
function electronRegisterLegalView(elem, processMetaDataId, dataViewId, selectedRow, paramData, $type) {

    if (typeof $type !== 'undefined' && $type === '3') {

        if (typeof IS_LOAD_ERLVIEW_SCRIPT === 'undefined') {
            $.getScript("assets/custom/addon/plugins/jqTree/tree.jquery.js").done(function() {
                $.getScript("assets/custom/addon/scripts/project/erlview.js").done(function() {
                    electronCvlLegalViewInit(elem, processMetaDataId, dataViewId, selectedRow, paramData, $type);
                });
            });
        } else {
            electronCvlLegalViewInit(elem, processMetaDataId, dataViewId, selectedRow, paramData, $type);
        }

    } else {
        if (typeof IS_LOAD_ERLVIEW_SCRIPT === 'undefined') {
            $.getScript("assets/custom/addon/plugins/jqTree/tree.jquery.js").done(function() {
                $.getScript("assets/custom/addon/scripts/project/erlview.js").done(function() {
                    electronRegisterLegalViewInit(elem, processMetaDataId, dataViewId, selectedRow, paramData, $type);
                });
            });
        } else {
            electronRegisterLegalViewInit(elem, processMetaDataId, dataViewId, selectedRow, paramData, $type);
        }
    }
}
function erlDirectScan(elem, processMetaDataId, dataViewId, selectedRow, paramData, $type, variable) {
    var addinVariable = (typeof variable !== 'undefined') ? variable : '';
    if (typeof IS_LOAD_ERL_SCRIPT === 'undefined') {
        $.getScript("assets/custom/addon/scripts/project/erl.js").done(function() {
            erlDirectScanInit(elem, processMetaDataId, dataViewId, selectedRow, paramData, $type, addinVariable);
        });
    } else {
        erlDirectScanInit(elem, processMetaDataId, dataViewId, selectedRow, paramData, $type, addinVariable);
    }
}
function erlReDirectScan(elem, processMetaDataId, dataViewId, selectedRow, paramData, $type, variable) {
    var addinVariable = (typeof variable !== 'undefined') ? variable : '';
    if (typeof IS_LOAD_ERL_SCRIPT === 'undefined') {
        $.getScript("assets/custom/addon/scripts/project/erl.js").done(function() {
            erlReDirectScanInit(elem, processMetaDataId, dataViewId, selectedRow, paramData, $type, addinVariable);
        });
    } else {
        erlReDirectScanInit(elem, processMetaDataId, dataViewId, selectedRow, paramData, $type, addinVariable);
    }
}

function metaPHPImport() {
    if (typeof isMetaUpgrade === 'undefined') {
        $.getScript('middleware/assets/js/upgrade/script.js').done(function() {
            metaPHPImportInit();
        });
    } else {
        metaPHPImportInit();
    }
}
function metaPHPExportById(metaId) {

    if ($('#renderMeta').is(':visible') && typeof metaIdData != 'undefined' && metaIdData.length) {
        var exportMetaId = metaIdData;
    } else {
        var exportMetaId = metaId;
    }

    if (typeof isMetaUpgrade === 'undefined') {
        $.getScript('middleware/assets/js/upgrade/script.js').done(function() {
            metaPHPExport(exportMetaId);
        });
    } else {
        metaPHPExport(exportMetaId);
    }
}
function metaSendToById(metaId) {

    if ($('#renderMeta').is(':visible') && typeof metaIdData != 'undefined' && metaIdData.length) {
        var exportMetaId = metaIdData;
    } else {
        var exportMetaId = metaId;
    }

    if (typeof isMetaUpgrade === 'undefined') {
        $.getScript('middleware/assets/js/upgrade/script.js').done(function() {
            metaSendTo(exportMetaId);
        });
    } else {
        metaSendTo(exportMetaId);
    }
}
function metaCopyReplaceById(metaId, folderId) {
    if (typeof isMetaUpgrade === 'undefined') {
        $.getScript('middleware/assets/js/upgrade/script.js').done(function() {
            metaCopyReplace(metaId, folderId);
        });
    } else {
        metaCopyReplace(metaId, folderId);
    }
}
function metaReplaceById(metaId, folderId) {
    if (typeof isMetaUpgrade === 'undefined') {
        $.getScript('middleware/assets/js/upgrade/script.js').done(function() {
            metaReplace(metaId, folderId);
        });
    } else {
        metaReplace(metaId, folderId);
    }
}
function callPosLocker(elem, processMetaDataId, dataViewId, selectedRow, paramData, customerId, objectParam) {
    if (typeof selectedRow === 'undefined') {
        var dataGrid = window['objectdatagrid_' + dataViewId];
        var rows = getDataViewSelectedRowsByElement(dataGrid);
        selectedRow = rows[0];
    }

    var $tabMainContainer = $('body').find("div.m-tab > div.tabbable-line > ul.card-multi-tab-navtabs");
    if ($tabMainContainer.find("a[href='#app_tab_mdposLocker_1566556713853_1991']").length) {
        $('div.card-multi-tab > div.card-body > div.card-multi-tab-content').find('div#app_tab_mdposLocker_1566556713853_1991').empty().remove();
        $tabMainContainer.find("a[href='#app_tab_mdposLocker_1566556713853_1991']").closest('li').remove();
    }

    appMultiTab({ weburl: 'mdpos', dataViewId: dataViewId, metaDataId: 'mdposLocker_1566556713853_1991', title: 'POS', type: 'selfurl', recordId: selectedRow.id, selectedRow: selectedRow, vipLockerId: (typeof paramData === 'object' ? '' : paramData), customerId: (typeof customerId === 'undefined' ? '' : customerId), objectParam: JSON.stringify((typeof objectParam === 'undefined' ? '' : objectParam)) }, this, function(elem, param, data) {
        if (typeof data.chooseCashier === 'undefined') {
            elem.find('.pos-wrap').css({"margin-left":"-15px", "margin-right":"-16px", "margin-top":"-9px"});
            if (typeof checkInitPosJS === 'undefined') {
                $.ajax({
                    url: "middleware/assets/js/pos/pos.js",
                    dataType: "script",
                    cache: false,
                    async: false
                });
            } else {
                setTimeout(function() {
                    Core.initDecimalPlacesInput();
                    posConfigVisibler($('body'));
                    posPageLoadEndVisibler();
                    posItemCombogridList('');
                    $('.pos-item-combogrid-cell').find('input.textbox-text').val('').focus();
                    
                    var $tbody = $('#posTable').find('> tbody');

                    if (posOrderTimer && isBasketOnly) {
                        $('.posTimerInit').countdown({
                            until: posOrderTimer,
                            compact: true,
                            description: '',
                            format: 'MS',
                            onExpiry: function() {
                                if (isBasketOnly) {                
                                    posNoPayment('auto');
                                }
                            }
                        });
                        if (!$('#posTable > tbody > tr[data-item-id]').length) {
                            $('.posTimerInit').countdown('pause');            
                        }
                    }                    

                    if ($tbody.find('> tr').length) {

                        Core.initLongInput($tbody);
                        Core.initUniform($tbody);

                        posGiftRowsSetDelivery($tbody);

                        var $firstRow = $tbody.find('tr[data-item-id]:eq(0)');
                        $firstRow.click();

                        posCalcTotal();
                    }                  
                    
                    if (posUseIpTerminal === '1') {
                        posConnectBankTerminal();
                    }
                    
                    if (isConfirmSaleDate === '1' && !isBasketOnly) {
                        askDateTransaction();
                    }                    
                    
                }, 300);
            }
            setTimeout(function() {
                posTableSetHeight();
                posFixedHeaderTable();
            }, 300);
        }
    });
}
function dataMartRelationConfigInit(elem, processMetaDataId, dataViewId, paramData, $appendElement) {
    if (typeof isDataMartRelationConfig === 'undefined') {
        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jsplumb/css/style.v2.css"/>');
        $.getScript('assets/custom/addon/plugins/jsplumb/jsplumb.min.js').done(function() {
            $.getScript('middleware/assets/js/addon/dataMartRelationConfig.js').done(function() {
                dataMartRelationConfig(elem, processMetaDataId, dataViewId, paramData, $appendElement);
            });
        });
    } else {
        dataMartRelationConfig(elem, processMetaDataId, dataViewId, paramData, $appendElement);
    }
}
function dataMartRelationConfigViewInit(elem, processMetaDataId, dataViewId, paramData, $appendElement) {
    if (typeof isDataMartRelationConfig === 'undefined') {
        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jsplumb/css/style.v2.css"/>');
        $.getScript('assets/custom/addon/plugins/jsplumb/jsplumb.min.js').done(function() {
            $.getScript('middleware/assets/js/addon/dataMartRelationConfig.js').done(function() {
                dataMartRelationConfigView(elem, processMetaDataId, dataViewId, paramData, $appendElement);
            });
        });
    } else {
        dataMartRelationConfigView(elem, processMetaDataId, dataViewId, paramData, $appendElement);
    }
}
function erdConfigInit(elem, processMetaDataId, dataViewId, paramData, $appendElement) {
    if (typeof isErdConfig === 'undefined') {
        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jsplumb/css/style.v3.css"/>');
        $.getScript('assets/custom/addon/plugins/jsplumb/jsplumb.min.js').done(function() {
            $.getScript('middleware/assets/js/addon/erdConfig.js').done(function() {
                erdConfig(elem, processMetaDataId, dataViewId, paramData, $appendElement);
            });
        });
    } else {
        erdConfig(elem, processMetaDataId, dataViewId, paramData, $appendElement);
    }
}
function eaServiceMetaRenderInit(elem, workSpaceId, metaDataId, paramResolveData, $appendElement) {
    if (typeof isEaServiceMetaRender === 'undefined') {
        $.getScript('middleware/assets/js/addon/easervice.js').done(function() {
            eaServiceMetaRender(elem, workSpaceId, metaDataId, paramResolveData, $appendElement);
        });
    } else {
        eaServiceMetaRender(elem, workSpaceId, metaDataId, paramResolveData, $appendElement);
    }
}
function eaServicePivotRenderInit(elem, workSpaceId, metaDataId, paramResolveData, $appendElement) {
    if (typeof isEaServiceMetaRender === 'undefined') {
        $.getScript('middleware/assets/js/addon/easervice.js').done(function() {
            eaServicePivotRender(elem, workSpaceId, metaDataId, paramResolveData, $appendElement);
        });
    } else {
        eaServicePivotRender(elem, workSpaceId, metaDataId, paramResolveData, $appendElement);
    }
}
function metaTranslator(elem, metaDataId) {
    if (typeof IS_LOAD_TRANSLATE_SCRIPT === 'undefined') {
        $.getScript('middleware/assets/js/translate/meta-translate.js').done(function() {
            metaTranslatorInit(elem, metaDataId);
        });
    } else {
        metaTranslatorInit(elem, metaDataId);
    }            
}
function menuMetaTranslator(elem, metaDataId) {
    if (typeof IS_LOAD_TRANSLATE_SCRIPT === 'undefined') {
        $.getScript('middleware/assets/js/translate/meta-translate.js').done(function() {
            menuMetaTranslatorInit(elem, metaDataId);
        });
    } else {
        menuMetaTranslatorInit(elem, metaDataId);
    }            
}
function pfHelpDataView(metaDataId) {
    var $dialogName = 'dialog-pfhelp-dataview';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName), dvId = '1567753457397', 
        postData = {metaDataId: dvId, viewType: 'detail', dataGridDefaultHeight: 510, ignorePermission: 1};
    
    if (typeof metaDataId !== 'undefined' && metaDataId) {
        postData.drillDownDefaultCriteria = 'filterMetaDataId=' + metaDataId;
    }
            
    $.ajax({
        type: 'post',
        url: 'mdobject/dataValueViewer',
        data: postData,
        beforeSend: function() { Core.blockUI({animate: true}); },
        success: function(dataHtml) {
            
            $dialog.empty().append('<div class="row" id="object-value-list-'+dvId+'">' + dataHtml + '</div>');
            $dialog.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                dialogClass: 'no-padding-dialog dialog-overflow-hidden',
                title: plang.get('platform_help'), 
                width: 1000,
                height: 'auto',
                modal: false,
                position: {my: 'top', at: 'top+37'},
                open: function() {
                    
                    var $processButtons = $dialog.find('.dv-process-buttons > .btn-group > a.btn');
                    var $filter = $dialog.find('.col-md-12.text-right.pr0');
                    if ($processButtons.length) {
                        var $processClone = $processButtons.clone(true);
                        $filter.prepend($processClone);
                    }
                    
                    $dialog.find('.top-sidebar-content:eq(0)').attr('style', 'padding-left: 15px !important');
                    $dialog.find('.div-accordionToggler, .remove-type-'+dvId+', .clearfix.w-100, .row.w-100').remove();
                    $dialog.find('.card-collapse').empty();
                    $filter.removeClass('col-md-12').addClass('float-right').css('margin-top', '-30px');
                    $dialog.find('.mb5.pb5').removeClass('mb5 pb5');
                    $dialog.find('.xs-form.top-sidebar-content').css('padding-left', '').removeClass('mb10');
                    $dialog.find('.center-sidebar.overflow-hidden').css('padding-bottom', '0');
                    
                    $dialog.find('.div-objectdatagrid-'+dvId).addClass('jeasyuiThemeFaqListPaddingBody');
                    
                    setTimeout(function() {
                        $dialog.find('input[type="text"]:eq(0)').focus();
                    }, 5);
                }, 
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                }
            }).dialogExtend({
                "closable": true,
                "maximizable": false, 
                "minimizable": true,
                "collapsable": true,
                "minimizeLocation": "left",
                "icons": {
                    "close": "ui-icon-circle-close",
                    "maximize": "ui-icon-extlink",
                    "minimize": "ui-icon-minus",
                    "collapse": "ui-icon-triangle-1-s",
                    "restore": "ui-icon-newwin"
                }
            });
            
            $dialog.dialog('open');
            Core.unblockUI();
        },
        error: function() { alert('Error'); Core.unblockUI(); }
    });
}
function clipboardMetaPaste(folderId, clipboardData) {
    var prefix = 'metaCopyPf0e41gT015p-';
    if (clipboardData && clipboardData.substr(0, 21) == prefix) {
        var ids = clipboardData.replace(prefix, '');
        $.ajax({
            type: 'post',
            url: 'mdmeta/clipboardMetaPaste', 
            data: {ids: ids, folderId: folderId}, 
            dataType: 'json', 
            beforeSend: function() {
                PNotify.removeAll();
                Core.blockUI({boxed : true, message: 'Loading...'});  
            }, 
            success: function (data) {
                Core.unblockUI();
                
                if (data.status != 'success') {
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false, 
                        addclass: pnotifyPosition
                    });
                } 
                
                refreshList(folderId, 'folder', '');
            }
        });
    }
    return;
}
function dataViewQueryEditor(metaId) {
    if ($("link[href='assets/custom/addon/plugins/codemirror/lib/codemirror.v1.css']").length == 0) {
        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/codemirror/lib/codemirror.v1.css"/>');
    }
    
    if (typeof CodeMirror === 'undefined') {
        $.cachedScript('assets/custom/addon/plugins/codemirror/lib/codemirror.min.js').done(function() {
            dataViewQueryEditorInit(metaId);
        });
    } else {
        dataViewQueryEditorInit(metaId);
    }
}
function bpInputParams(metaId) {
    
    if ($("link[href='assets/custom/addon/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css']").length == 0) {
        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>');
    }
    
    if (typeof CodeMirror === 'undefined') {
        
        if ($("link[href='assets/custom/addon/plugins/codemirror/lib/codemirror.v1.css']").length == 0) {
            $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/codemirror/lib/codemirror.v1.css"/>');
        }
    
        $.cachedScript('assets/custom/addon/plugins/codemirror/lib/codemirror.min.js').done(function() {
            $.cachedScript("assets/custom/addon/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js?v=1").done(function() {
                bpInputParamsInit(metaId);
            });
        });
    } else {
        bpInputParamsInit(metaId);
    }
}
function dataViewQueryEditorInit(metaId) {
    var $dialogName = 'dialog-dataViewQueryEditor';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    
    $.ajax({
        type: 'post',
        url: 'mdmeta/dvQueryEditor',
        data: {metaId: metaId},
        dataType: 'json', 
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            PNotify.removeAll();
            if (data.status != 'success') {
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
                return;
            }
            
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Query editor',
                width: 1000,
                minWidth: 1000, 
                height: 600,
                modal: false,
                open: function() {
                    disableScrolling();
                }, 
                close: function() {
                    enableScrolling();
                    $dialog.empty().dialog('destroy').remove();
                    $('ul.grid > li.meta-selected > figure > a, ul.grid > li.ui-selected > figure > a').focus();
                },
                buttons: [
                    {text: 'Formatting', class: 'btn btn-sm purple-plum float-left', click: function() {
                        var $activeTab = $('.dbdriver-tabs [aria-expanded="true"]'), 
                            hrefUrl = $activeTab.attr('href'), sqlQuerySql = '', dbDriverTab = '';

                        if (hrefUrl == '#default-tab') {

                            dvSqlQueryEditor.save();
                            sqlQuerySql = dvSqlQueryEditor.getValue();
                            dbDriverTab = 'default';

                        } else if (hrefUrl == '#postgresql-tab') {

                            postgreSqlEditor.save();
                            sqlQuerySql = postgreSqlEditor.getValue();
                            dbDriverTab = 'postgresql';

                        } else if (hrefUrl == '#mssql-tab') {

                            msSqlEditor.save();
                            sqlQuerySql = msSqlEditor.getValue();
                            dbDriverTab = 'mssql';
                        }

                        $.ajax({
                            type: 'post',
                            url: 'mdmeta/sqlFormatting',
                            data: {query: sqlQuerySql},
                            beforeSend: function() {
                                Core.blockUI({message: 'Formatting...', boxed: true});
                            },
                            success: function(content) {

                                if (dbDriverTab == 'default') {
                                    dvSqlQueryEditor.setValue(content);
                                    dvSqlQueryEditor.focus();
                                } else if (dbDriverTab == 'postgresql') {
                                    postgreSqlEditor.setValue(content);
                                    postgreSqlEditor.focus();
                                } else if (dbDriverTab == 'mssql') {
                                    msSqlEditor.setValue(content);
                                    msSqlEditor.focus();
                                }

                                Core.unblockUI();
                            }
                        });
                    }}, 
                    {text: plang.get('save_btn'), class: 'btn btn-sm green bp-btn-save', click: function() {
                        
                        PNotify.removeAll();
                        
                        dvSqlQueryEditor.save();
                        postgreSqlEditor.save();
                        msSqlEditor.save();
                        
                        $.ajax({
                            type: 'post',
                            url: 'mdmeta/dvQuerySave',
                            data: $('#dataview-queryeditor-form').serialize(), 
                            dataType: 'json', 
                            beforeSend: function() {
                                Core.blockUI({message: 'Saving...', boxed: true});
                            },
                            success: function(dataSub) {
                                
                                new PNotify({
                                    title: dataSub.status,
                                    text: dataSub.message,
                                    type: dataSub.status,
                                    sticker: false
                                });
                                    
                                if (dataSub.status == 'success') {
                                    $dialog.dialog('close');
                                }
                                
                                Core.unblockUI();
                            }
                        });
                    }},
                    {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                        $dialog.dialog('close');
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
                }, 
                "maximize": function() { 
                    var dialogHeight = $dialog.height();
                    $dialog.find('.CodeMirror').css('height', (dialogHeight - 50)+'px');
                }, 
                "restore": function() { 
                    var dialogHeight = $dialog.height();
                    $dialog.find('.CodeMirror').css('height', (dialogHeight - 50)+'px');
                }
            });
            $dialog.dialog('open');
            $dialog.dialogExtend('maximize');
            Core.unblockUI();
        },
        error: function() { alert("Error"); Core.unblockUI(); }
    });
}
function bpInputParamsInit(metaId) {
    Core.blockUI({message: 'Loading...', boxed: true});
    
    var $dialogName = 'dialog-bpinputparams';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
    }
    var $dialogContainer = $('#' + $dialogName);
    
    $.ajax({
        type: 'post',
        url: 'mdmetadata/setParamAttributesEditModeNew',
        data: {metaDataId: metaId},
        dataType: 'json',
        success: function (data) {
            
            PNotify.removeAll();
            if (data.status != 'success') {
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
                return;
            }
            
            $dialogContainer.empty().append('<form method="post" id="bpinputparams-form">' + data.Html + '</form>');

            var $detachedChildren = $dialogContainer.children().detach();

            $dialogContainer.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: 1200,
                minWidth: 1200,
                height: 'auto',
                modal: false,
                open: function() {
                    disableScrolling();
                    $detachedChildren.appendTo($dialogContainer);
                    Core.unblockUI();
                }, 
                close: function() {
                    enableScrolling();
                    $dialogContainer.empty().dialog('destroy').remove();
                    $('ul.grid > li.meta-selected > figure > a, ul.grid > li.ui-selected > figure > a').focus();
                },
                buttons: [
                    {text: plang.get('save_btn'), class: 'btn btn-sm green bp-btn-save', click: function () {
                            
                        $('#bpinputparams-form').ajaxSubmit({
                            type: 'post',
                            url: 'mdmetadata/saveBpInputParams',
                            dataType: 'json', 
                            beforeSubmit: function (formData, jqForm, options) {
                                formData.push(
                                    {name: 'mainMetaDataId', value: metaId}
                                );
                            },
                            beforeSend: function() {
                                Core.blockUI({message: 'Saving...', boxed: true});
                            },
                            success: function(dataSub) {
                                
                                new PNotify({
                                    title: dataSub.status,
                                    text: dataSub.message,
                                    type: dataSub.status,
                                    sticker: false
                                });
                                    
                                if (dataSub.status == 'success') {
                                    $dialogContainer.dialog('close');
                                }
                                
                                Core.unblockUI();
                            }
                        });
                    }},
                    {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
                        $dialogContainer.dialog('close');
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
                }, 
                "maximize": function() { 
                    var dialogHeight = $dialogContainer.height();
                    $dialogContainer.find("div#fz-process-params-option").css({"height": (dialogHeight - 41)+'px'});
                    $dialogContainer.find("div.params-addon-config").css({"height": (dialogHeight - 41)+'px'});
                }
            });
            $dialogContainer.dialog('open');
            $dialogContainer.dialogExtend('maximize');
        }

    }).done(function () {
        Core.initNumber($dialogContainer);
    });
}
function knowMetasInFile() {
    if (typeof isMetaUpgrade === 'undefined') {
        $.getScript('middleware/assets/js/upgrade/script.js').done(function() {
            knowMetasInFileInit();
        });
    } else {
        knowMetasInFileInit();
    }
}
function viewBpDetailWidget(elem) {
    var $this = $(elem), $parent = $this.closest('.input-group'), 
        $combo = $parent.find('select'), selectedId = $combo.val(), 
        html = [], optionItem = [];
    
    $combo.find('option').each(function() {
        
        var $opt = $(this);
        
        if ($opt.hasAttr('data-preview-webimage') && ($opt.attr('data-preview-webimage') || $opt.attr('data-preview-mobileimage'))) {
            
            var webImage = '', mobileImage = '', selectedClass = '';
            
            if ($opt.attr('data-preview-webimage')) {
                webImage = '<img src="api/image_thumbnail?width=200&src='+$opt.attr('data-preview-webimage')+'" data-src="'+$opt.attr('data-preview-webimage')+'">';
            }
            
            if ($opt.attr('data-preview-mobileimage')) {
                mobileImage = '<img src="api/image_thumbnail?width=200&src='+$opt.attr('data-preview-mobileimage')+'" data-src="'+$opt.attr('data-preview-mobileimage')+'">';
            }
            
            if (selectedId == $opt.attr('value')) {
                selectedClass = ' selected';
            }
            
            optionItem.push('<tr data-id="'+$opt.attr('value')+'" class="cursor-pointer'+selectedClass+'">');
                optionItem.push('<td>'+$opt.text()+'</td>');
                optionItem.push('<td>'+webImage+'</td>');
                optionItem.push('<td>'+mobileImage+'</td>');
            optionItem.push('</tr>');
        }
    });
        
    if (optionItem) {
        
        html.push('<table class="table table-border table-hover">');
            html.push('<thead>');
                html.push('<tr>');
                    html.push('<th>Нэр</th>');
                    html.push('<th>Вэб зураг</th>');
                    html.push('<th>Мобайл зураг</th>');
                html.push('</tr>');
            html.push('</thead>');
            html.push('<tbody>');
                html.push(optionItem.join(''));
            html.push('</tbody>');
        html.push('</table>');

        var $dialogName = 'dialog-widget-bp-detail';
        if (!$("#" + $dialogName).length) {$('<div id="' + $dialogName + '"></div>').appendTo('body');}
        var $dialog = $('#' + $dialogName);
    
        $dialog.append(html.join(''));
        $dialog.dialog({
            cache: false,
            resizable: false,
            bgiframe: true,
            autoOpen: false,
            title: 'Widget',
            width: 650,
            height: 'auto',
            maxHeight: $(window).height() - 50,
            modal: true,
            closeOnEscape: isCloseOnEscape, 
            position: {my: 'top', at: 'top+30'}, 
            close: function () {
                $dialog.empty().dialog('destroy').remove();
            }, 
            buttons: [{
                    text: plang.get('choose_btn'),
                    class: 'btn btn-sm green-meadow',
                    click: function() {
                        PNotify.removeAll();
                        var $selected = $dialog.find('tr.selected');
                        
                        if ($selected.length) {
                            
                            $combo.val($selected.attr('data-id'));
                            $dialog.dialog('close');
                            
                        } else {
                            new PNotify({
                                title: 'Warning',
                                text: 'Та widget сонгоно уу!',
                                type: 'warning',
                                sticker: false,
                                hide: true,
                                addclass: pnotifyPosition
                            });
                        }
                    }
                },
                {
                    text: plang.get('close_btn'),
                    class: 'btn blue-madison btn-sm',
                    click: function() {
                        $dialog.dialog('close');
                    }
                }
            ]
        });
        $dialog.dialog('open');
        
        $dialog.on('click', 'table > tbody > tr', function() {
            var $row = $(this), $tbody = $row.closest('tbody');
            
            $tbody.find('tr.selected').removeClass('selected');
            $row.addClass('selected');
        });
        
        $dialog.on('click', 'img', function() {
            var $this = $(this);
            var realSrc = $this.attr('data-src');
            var photoName = $this.closest('tr').find('> td:eq(0)').text();

            $.fancybox.open(
                [{src: realSrc, opts: {caption: photoName}}],
                {buttons: ['zoom', 'close']}
            );
        });
    }
}

$(function() {
   
    $(document.body).on('click', 'ul.fiscal-period-child li.root-period > a > i', function(e) {
        var _this = $(this), _current = _this;

        if (e.currentTarget.tagName === 'I') {
            _this = $(this).closest('li');
            _current = _this;
        }
        
        var childId = _this.attr('data-id');
        if (_current.find('.fa-angle-right').length) {
            _current.closest('ul').find('li.root-period').children().children('i:not(".fa-check-circle,.fa-lock")').removeClass('fa-angle-down').addClass('fa-angle-right');
            _current.find('.fa-angle-right').removeClass('fa-angle-right').addClass('fa-angle-down');
          
            _this.parent().children().each(function() {
                var _childThis = $(this),
                    childAttr = _childThis.attr('data-parent-id');
                if (!_childThis.hasClass('root-period')) {
                    _childThis.children().addClass('hidden');
                }
                if (childAttr == 'child-' + childId)
                    _childThis.children().removeClass('hidden');
            });
        } else {
            _current.find('.fa-angle-down').removeClass('fa-angle-down').addClass('fa-angle-right');
            _this.parent().children().each(function() {
                var _childThis = $(this);
                if (!_childThis.hasClass('root-period')) {
                    _childThis.children().addClass('hidden');
                    _childThis.children().children('i:not(".fa-check-circle,.fa-lock")').removeClass('fa-angle-down').addClass('fa-angle-right');
                }
            });
        }
        e.preventDefault();
        e.stopPropagation();
    });
    $(document.body).on('click', 'ul.fiscal-period-child > li > a.period-level-0 > i', function(e) {
        var _this = $(this).closest('li'),
            _current = $(this);
        var childId = _this.attr('data-id');
        if (_current.hasClass('fa-angle-right')) {
            _current.closest('ul').find('.period-level-0').children('i:first-child').removeClass('fa-angle-down').addClass('fa-angle-right');
            _current.removeClass('fa-angle-right').addClass('fa-angle-down');

            _this.parent().children().each(function() {
                var _childThis = $(this),
                    childAttr = _childThis.attr('data-parent-id');
                if (_childThis.children().hasClass('child-period-link') || _childThis.children().hasClass('child-period-link-deep') || _childThis.children().hasClass('period-level-1')) {
                    _childThis.children().addClass('hidden');
                }
                if (childAttr == 'child-' + childId)
                    _childThis.children().removeClass('hidden');
            });
        } else {
            _current.removeClass('fa-angle-down').addClass('fa-angle-right');
            _this.parent().children().each(function() {
                var _childThis = $(this),
                    childAttr = _childThis.attr('data-parent-id');
                if (childAttr == 'child-' + childId)
                    _childThis.children().addClass('hidden');
            });
        }
        e.preventDefault();
        e.stopPropagation();
    });
    $(document.body).on('click', 'ul.fiscal-period-child > li > a.period-level-1 > i', function(e) {
        var _this = $(this).closest('li'),
            _current = $(this);
        var childId = _this.attr('data-id');
        if (_current.hasClass('fa-angle-right')) {

            if (!_current.closest('ul').find('.period-level-1').children('i:first-child').hasClass('fa-check-circle'))
                _current.closest('ul').find('.period-level-1').children('i:first-child').removeClass('fa-angle-down').addClass('fa-angle-right');
            _current.removeClass('fa-angle-right').addClass('fa-angle-down');

            _this.parent().children().each(function() {
                var _childThis = $(this),
                    childAttr = _childThis.attr('data-parent-id');
                if (_childThis.children().hasClass('child-period-link-deep')) {
                    _childThis.children().addClass('hidden');
                }
                if (childAttr == 'child-' + childId)
                    _childThis.children().removeClass('hidden');
            });
        } else {
            _current.removeClass('fa-angle-down').addClass('fa-angle-right');
            _this.parent().children().each(function() {
                var _childThis = $(this),
                    childAttr = _childThis.attr('data-parent-id');
                if (childAttr == 'child-' + childId)
                    _childThis.children().addClass('hidden');
            });
        }
        e.preventDefault();
        e.stopPropagation();
    });
    $(document.body).on('click', 'ul.fiscal-period-child li', function() {
        var $this = $(this);
        var childId = $this.attr('data-id');
        var yearId = typeof $this.attr('data-root-year') === 'undefined' ? childId : $this.attr('data-root-year');
        var periodName = $this.find('a').text();
        
        $.ajax({
            type: 'post',
            url: 'profile/changeFiscalPeriodChild',
            data: {childId: childId, yearId: yearId},
            dataType: 'json',
            success: function(data) {
                
                var $body = $('body');
                
                $body.find('li.fiscal-period-child-container').each(function() {
                    var $parent = $(this);
                    $parent.find('a.dropdown-toggle').html('<span class="langname">' + periodName + '</span>');
                    var $liThis = $parent.find("li[data-id='" + childId + "']");
                    $parent.find('ul.fiscal-period-child').find("i.fa-check-circle").remove();
                    $liThis.find('a').append('<i class="fa fa-check-circle"></i>');
                });

                $this.parent().find('li').removeClass('hidden current');
                $this.addClass('current');
                
                if (typeof $this.attr('data-parent-id') !== 'undefined') {
                    var periodLevel = $this.attr('data-parent-id').split('-');
                    periodLevel = periodLevel[1];
                } else {
                    var periodLevel = $this.attr('data-id');
                }

                $this.parent().find('li[data-id="' + yearId + '"]').addClass('current');
                $this.parent().find('li[data-id="' + periodLevel + '"]').addClass('current');
                
                $body.find('[data-rpbyvar="fiscalPeriodName"]').text(periodName);

                if (typeof data.startDate !== 'undefined' && typeof data.endDate !== 'undefined') {

                    var responseStartDate = $.trim(data.startDate);
                    var responseEndDate = $.trim(data.endDate);

                    $body.find("input.fin-fiscalperiod-startdate, input.fin-fiscalperiod-enddate").attr({'data-mindate': responseStartDate, 'data-maxdate': responseEndDate});
                    
                    /*$body.find("input.fin-fiscalperiod-startdate[data-mindate], input.fin-fiscalperiod-enddate[data-mindate]").attr('data-mindate', responseStartDate);
                    $body.find("input.fin-fiscalperiod-startdate[data-maxdate], input.fin-fiscalperiod-enddate[data-maxdate]").attr('data-maxdate', responseEndDate);*/
                    
                    var fiscalStartDate = new Date(responseStartDate);
                    var fiscalEndDate = new Date(responseEndDate);
                    var $dvElementBtns = $();

                    $body.find("input.fin-fiscalperiod-startdate").each(function() {
                        var $thisFFStartDate = $(this);
                        var maxPickerSDate = '';
                        var minPickerSDate = '';

                        if ($thisFFStartDate.hasAttr('data-mindate')) {
                            minPickerSDate = new Date($thisFFStartDate.attr('data-mindate'));
                        }
                        if ($thisFFStartDate.hasAttr('data-maxdate')) {
                            maxPickerSDate = new Date($thisFFStartDate.attr('data-maxdate'));
                        }

                        $thisFFStartDate.datepicker('setStartDate', minPickerSDate);
                        $thisFFStartDate.datepicker('setEndDate', maxPickerSDate);
                        
                        if (fiscalStartDate.getTime() < fiscalEndDate.getTime()) {
                            $thisFFStartDate.datepicker('update', responseStartDate);

                            var parentContainer = $thisFFStartDate.closest('.main-dataview-container:visible, .pack_default_criteria:visible');
                            if (parentContainer.length) {
                                $dvElementBtns = $dvElementBtns.add(parentContainer.find('.dataview-default-filter-btn:eq(0)'));
                            }
                        }
                    });
                    $body.find("input.fin-fiscalperiod-enddate").each(function() {
                        var $thisFFEndDate = $(this);
                        var maxPickerDate = '';
                        var minPickerDate = '';

                        if ($thisFFEndDate.hasAttr('data-maxdate')) {
                            maxPickerDate = new Date($thisFFEndDate.attr('data-maxdate'));
                        }
                        if ($thisFFEndDate.hasAttr('data-mindate')) {
                            minPickerDate = new Date($thisFFEndDate.attr('data-mindate'));
                        }

                        $thisFFEndDate.datepicker('setStartDate', minPickerDate);
                        $thisFFEndDate.datepicker('setEndDate', maxPickerDate);
                        
                        if (fiscalStartDate.getTime() < fiscalEndDate.getTime()) {
                            
                            $thisFFEndDate.datepicker('update', responseEndDate);

                            var parentContainer = $thisFFEndDate.closest('.main-dataview-container:visible, .pack_default_criteria:visible');
                            if (parentContainer.length) {
                                $dvElementBtns = $dvElementBtns.add(parentContainer.find('.dataview-default-filter-btn:eq(0)'));
                            }
                        }
                    });

                    if ($dvElementBtns.length) {
                        $dvElementBtns.each(function() {
                            $(this).click();
                        });
                    }
                }
            }
        });
    });
    
    $(document.body).on('shown.bs.dropdown', '.fiscal-period-child-container', function() {
        var $thisli = $(this);
        var $ul = $thisli.find('ul.dropdown-menu:eq(0)');
        
        if ($ul.children().length == 0) {
            
            $.ajax({
                type: 'post',
                url: 'mduser/getFiscalPeriod',
                beforeSend: function() {
                    $ul.empty().append('<div class="text-center" style="height: 14px;"><i class="icon-spinner spinner" style="width:inherit;height:inherit;"></i></div>');
                },
                success: function(data) { 
                    
                    $ul.empty().append(data).promise().done(function() {
                        
                        $ul.find('li:not(".root-period")').children().addClass('hidden');
                        $ul.find('li').each(function() {
                            var $li = $(this);

                            if ($li.hasClass('current')) {

                                if (($li.hasClass('root-period') || $li.children().hasClass('period-level-0') || $li.children().hasClass('period-level-1')) && $li.find('.fa-check-circle').length) {
                                    return;
                                }

                                $li.find('.fa-angle-right').removeClass('fa-angle-right').addClass('fa-angle-down');

                                var did = 'child-' + $li.attr('data-id');
                                $li.children().children('i:not(".fa-check-circle,.fa-lock")').removeClass('fa-angle-right').addClass('fa-angle-down');

                                $li.children().removeClass('hidden');

                                if ($li.children().hasClass('period-level-2')) {
                                    var periodLevel = $li.attr('data-parent-id').split('-');
                                    periodLevel = $li.parent().find('li[data-id="' + periodLevel[1] + '"]').attr('data-parent-id');

                                    $thisli.find('li[data-parent-id="' + periodLevel + '"]').children().removeClass('hidden');
                                    $thisli.find('li[data-parent-id="' + $li.attr('data-parent-id') + '"]').children().removeClass('hidden');

                                    periodLevel = periodLevel.split('-');
                                    $li.parent().find('li[data-id="' + periodLevel[1] + '"]').children().children('i:not(".fa-check-circle,.fa-lock")').removeClass('fa-angle-right').addClass('fa-angle-down');
                                } else {
                                    $thisli.find('li[data-parent-id="' + did + '"]').children().not(".period-level-2").removeClass('hidden');
                                }
                            } else {
                                $li.children().children('i:not(".fa-check-circle,.fa-lock")').removeClass('fa-angle-down').addClass('fa-angle-right');
                            }
                        });
                    });                
                }
            });
        }
    });

    $("body").on('click', 'ul.semister-year-child li.root-semister, ul.semister-year-child li.root-semister > a > i', function(e) {
        var _this = $(this),
            _current = _this;

        if (e.currentTarget.tagName === 'I') {
            _this = $(this).closest('li');
            _current = _this;
        }

        var childId = _this.attr('data-id');
        if (_current.find('.fa-angle-right').length) {
            _current.closest('ul').find('li.root-semister').children().children('i:not(".fa-check-circle,.fa-lock")').removeClass('fa-angle-down').addClass('fa-angle-right');
            _current.find('.fa-angle-right').removeClass('fa-angle-right').addClass('fa-angle-down');

            _this.parent().children().each(function() {
                var _childThis = $(this),
                    childAttr = _childThis.attr('data-parent-id');
                if (!_childThis.hasClass('root-semister')) {
                    _childThis.children().addClass('hidden');
                }
                if (childAttr == 'child-' + childId)
                    _childThis.children().removeClass('hidden');
            });
        } else {
            _current.find('.fa-angle-down').removeClass('fa-angle-down').addClass('fa-angle-right');
            _this.parent().children().each(function() {
                var _childThis = $(this);
                if (!_childThis.hasClass('root-semister')) {
                    _childThis.children().addClass('hidden');
                    _childThis.children().children('i:not(".fa-check-circle,.fa-lock")').removeClass('fa-angle-down').addClass('fa-angle-right');
                }
            });
        }
        e.preventDefault();
        e.stopPropagation();
    });
    $("body").on('click', 'ul.academic-year li', function() {
        var _this = $(this);
        var yearId = _this.attr('data-id');
        var _parent = _this.closest("li.dropdown");
        $.ajax({
            type: 'post',
            url: 'profile/changeAcademicYear',
            data: { yearId: yearId },
            success: function(data) {

                _parent.find("a.dropdown-toggle").empty().append('<span class="langname">' + _this.find("a").text() + '</span><i class="fa fa-angle-down"></i>');

                if ($("body").find("li.semister-year-child-container").length > 0) {
                    $("body").find("ul.semister-year-child").html(data);
                } else {
                    var liHtml = '<li class="dropdown dropdown-language dropdown-dark semister-year-child-container">' +
                        '<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-close-others="true">' +
                        '</a>' +
                        '<ul class="dropdown-menu dropdown-menu-default semister-year-child">' +
                        data +
                        '</ul>' +
                        '</li>';
                    $(liHtml).insertAfter(_parent);
                }
            }
        });
    });
    $("body").on('click', 'ul.semister-year-child li:not(".root-semister")', function() {
        var _this = $(this);
        var childId = _this.attr('data-id');
        $.ajax({
            type: 'post',
            url: 'profile/changecheckSemisterYear',
            data: { childId: childId },
            dataType: 'json',
            success: function(data) {

                $('body').find('li.semister-year-child-container').each(function() {
                    var _parent = $(this);
                    _parent.find('a.dropdown-toggle').html('<span class="langname">' + _this.find('a').text() + '</span>');
                    var liThis = _parent.find("li[data-id='" + childId + "']");
                    _parent.find('ul.semister-year-child').find("i.fa-check-circle").remove();
                    liThis.find('a').append('<i class="fa fa-check-circle"></i>');
                    location.reload();
                });
            }
        });
    });
    
    $(document.body).on('click', 'ul.scenario-child li i', function(e) {
        var $this = $(this);
        var $parent = $this.closest('ul');
        var $row = $this.closest('li');
        var id = $row.attr('data-id');
        var $child = $parent.find('li[data-parentid="'+id+'"] > a');
        
        if ($child.length) {
            if ($child.hasClass('d-none')) {
                $child.removeClass('d-none');
            } else {
                $child.addClass('d-none');
            }
        }
        
        e.preventDefault();
        e.stopPropagation();
    });
    $(document.body).on('click', 'ul.scenario-child li', function() {
        
        var $this = $(this);
        var $row = $this.closest('li');
        var $parent = $this.closest('.scenario-container');
        var scenarioId = $row.attr('data-id');
        
        $.ajax({
            type: 'post',
            url: 'profile/changeScenario',
            data: {id: scenarioId},
            dataType: 'json',
            success: function(data) {
                
                if (data.status == 'success') {
                    
                    $parent.find('> a.dropdown-toggle').html('<span class="langname">' + $this.find('a').text() + '</span>');
                    $parent.find('.current').removeClass('current');
                    $parent.find('.fa-check-circle').remove();
                    $row.addClass('current');
                    $row.find('a').append('<i class="fa fa-check-circle"></i>');
                    
                    var bgColor = $row.css('background-color');
                    var $filterScenarioIds = $('input[data-path="filterScenarioId"]');
                    
                    if (bgColor) {
                        $parent.css('background-color', bgColor);
                    }
                    
                    if ($filterScenarioIds.length) {
                        
                        $filterScenarioIds.each(function() {
                            
                            var $filterScenarioId = $(this);
                            var $filterScenarioForm = $filterScenarioId.closest('[data-meta-type="dv"]');
                            var $filterScenarioPivotForm = $filterScenarioId.closest('[data-pivot-filter]');
                            var $filterScenarioPanelForm = $filterScenarioId.closest('.dv-paneltype-filter-form');
                            
                            setLookupPopupValue($filterScenarioId, scenarioId);
                            
                            if ($filterScenarioForm.length) {
                                
                                var dvId = $filterScenarioForm.attr('data-process-id');
                                
                                if (typeof window['objectdatagrid_'+dvId] !== 'undefined' 
                                    && typeof $.data(window['objectdatagrid_'+dvId][0], 'datagrid') != 'undefined') {
                                
                                    var $dataGrid = window['objectdatagrid_'+dvId], 
                                        $op = $dataGrid.datagrid('options'), 
                                        queryParams = $op.queryParams;
                                    queryParams.defaultCriteriaData = $('div#object-value-list-'+dvId+' form#default-criteria-form').serialize();
                                    $dataGrid.datagrid('load', queryParams);
                                }
                            }
                            
                            if ($filterScenarioPivotForm.length) {
                                var pivotUniqId = $filterScenarioPivotForm.attr('data-pivot-filter');
                                window['pivotFilter_' + pivotUniqId]($filterScenarioPivotForm.find('form'));
                            }
                            
                            if ($filterScenarioPanelForm.length) {
                                $filterScenarioPanelForm.find('.dv-paneltype-filter-btn').click();
                            }
                        });
                        
                        var $kpiDmDtlVisible = $('div[data-section-path="kpiDmDtl"]:visible:last').closest('div[data-bp-uniq-id]');
                        
                        if ($kpiDmDtlVisible.length) {
                            showKpiForm($kpiDmDtlVisible, this, 1, 'horizontalForm');
                        }
                    }
                }
            }
        });
    });
    
    var $currentScenario = $('ul.scenario-child > li.current');
    if ($currentScenario.length) {
        var scenarioParentId = $currentScenario.attr('data-parentid');
        if (scenarioParentId) {
            $('ul.scenario-child > li[data-parentid="'+scenarioParentId+'"] > a.d-none').removeClass('d-none');
        }
    }
    
    $(document).bind('keydown', 'Shift+e', function() {
        if ($('body').find('.main-dataview-container').length > 0 && $('body').find('.main-dataview-container').is(':visible')) {

            PNotify.removeAll();

            var $dataViewElement = $('body').find(".main-dataview-container:visible");
            var $dataViewElementArr = $dataViewElement.attr('id').split('-');

            var fncArguments = [$dataViewElementArr[3], $dataViewElement.attr('data-folder-id'), this, { isDialog: true, dataView: true }];

            checkUrlAuthLoginByFnc('editFormMeta', fncArguments);
        }
    });
    $(document).bind('keydown', 'Shift+p', function() {
        if ($('.shift-p-ignore:visible').length == 0 && $('body').find("div[id*='bp-window-']").length > 0 && $('body').find("div[id*='bp-window-']").is(':visible')) {

            PNotify.removeAll();

            var $processElement = $('body').find("div[id*='bp-window-']:visible");
            var opts = {isDialog: true, businessProcess: true};
            
            if ($processElement.hasAttr('data-isgroup') && $processElement.attr('data-isgroup') == '1') {
                opts = {isDialog: true, dataView: true};
            }
            
            var fncArguments = [$processElement.attr('data-process-id'), '', this, opts];

            checkUrlAuthLoginByFnc('editFormMeta', fncArguments);
        }
    });
    $(document).bind('keydown', 'Shift+s', function(e) {
        
        if ($('body').find('button.shift-s-save').length > 0 && $('body').find('button.shift-s-save').is(':visible')) {
            var $buttonElement = $('body').find('button.shift-s-save:visible:last');
            if (!$buttonElement.is(':disabled')) {
                $buttonElement.click();
                e.preventDefault();
                return false;
            }
        }
        
        if ($('body').find("div[id*='dataview-statement-search-']").length > 0 && $('body').find("div[id*='dataview-statement-search-']").is(':visible')) {

            PNotify.removeAll();

            $(document).on('focusin', function(e) {
                if ($(e.target).closest(".mce-window, .moxman-window").length) {
                    e.stopImmediatePropagation();
                }
            });

            var $statementElement = $('body').find("div[id*='dataview-statement-search-']:visible:last");
            var fncArguments = [$statementElement.attr('data-process-id'), '', this, { isDialog: true, businessProcess: true, statement: true }];

            checkUrlAuthLoginByFnc('editFormMeta', fncArguments);
        }
    });
    $(document).bind('keydown', 'Shift+q', function() {
        if ($("body").find(".main-action-meta").length > 0 && $("body").find(".main-action-meta").is(':visible')) {
            var $processElement = $("body").find(".main-action-meta:visible:last");

            var fncArguments = [$processElement.attr('data-process-id'), $processElement.attr('data-meta-type')];
            checkUrlAuthLoginByFnc('isLockMeta', fncArguments);
        }
    });
    $(document).bind('keydown', 'Ctrl+Shift+f1', function() {
        checkUrlAuthLoginByFnc('tempBpFullExpressionSave');
        clearConsole();
    });
    
    $(document.body).on("keydown", 'input.md-code-autocomplete', function(e) {
        if (e.which === 13) {

            var $this = $(this);
            var $value = $this.val();
            var $parent = $this.closest("div.meta-autocomplete-wrap");
            var $isName = false;
            var $params = $parent.attr('data-params');

            if (typeof $this.attr('data-ac-id') !== 'undefined') {
                $isName = 'idselect';
                $value = $this.attr('data-ac-id');
            }

            $.ajax({
                type: 'post',
                url: 'mdmetadata/metaDataAutoCompleteById',
                data: {
                    code: $value,
                    isName: $isName,
                    params: $params
                },
                dataType: 'json',
                async: false,
                beforeSend: function() {
                    $this.addClass('spinner2');
                },
                success: function(data) {

                    $this.removeAttr('data-ac-id');

                    if (data.META_DATA_ID !== '') {
                        $parent.find("input[id*='_displayField']").val(data.META_DATA_CODE).attr('title', data.META_DATA_CODE);
                        $parent.find("input[id*='_nameField']").val(data.META_DATA_NAME).attr('title', data.META_DATA_NAME);
                        $parent.find("input[type='hidden']").val(data.META_DATA_ID).trigger('change');
                    } else {
                        $parent.find("input[id*='_nameField']").val('').attr('title', '');
                        $parent.find("input[type='hidden']").val('').trigger('change');
                    }

                    $this.removeClass('spinner2');
                },
                error: function() {
                    alert('Error');
                }
            });
        }
    });
    $(document.body).on("keydown", 'input.md-name-autocomplete', function(e) {
        if (e.which === 13) {

            var $this = $(this);
            var $value = $this.val();
            var $parent = $this.closest("div.meta-autocomplete-wrap");
            var $isName = true;
            var $params = $parent.attr('data-params');

            if (typeof $this.attr('data-ac-id') !== 'undefined') {
                $isName = 'idselect';
                $value = $this.attr('data-ac-id');
            }

            $.ajax({
                type: 'post',
                url: 'mdmetadata/metaDataAutoCompleteById',
                data: {
                    code: $value,
                    isName: $isName,
                    params: $params
                },
                dataType: 'json',
                async: false,
                beforeSend: function() {
                    $this.addClass('spinner2');
                },
                success: function(data) {

                    $this.removeAttr('data-ac-id');

                    if (data.META_DATA_ID !== '') {
                        $parent.find("input[id*='_displayField']").val(data.META_DATA_CODE).attr('title', data.META_DATA_CODE);
                        $parent.find("input[id*='_nameField']").val(data.META_DATA_NAME).attr('title', data.META_DATA_NAME);
                        $parent.find("input[type='hidden']").val(data.META_DATA_ID).trigger('change');
                    } else {
                        $parent.find("input[id*='_nameField']").val('').attr('title', '');
                        $parent.find("input[type='hidden']").val('').trigger('change');
                    }

                    $this.removeClass('spinner2');
                },
                error: function() {
                    alert('Error');
                }
            });
        }
    });

    $(document.body).on("focus", 'input.md-code-autocomplete:not(disabled, readonly)', function(e) {
        metaDataAutoComplete($(this), 'code');
    });
    $(document.body).on("focus", 'input.md-name-autocomplete:not(disabled, readonly)', function(e) {
        metaDataAutoComplete($(this), 'name');
    });

    $(document.body).on("keydown", 'input.md-code-autocomplete:not(disabled, readonly)', function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        var $this = $(this);
        if (code === 13) {
            if ($this.data("ui-autocomplete")) {
                $this.autocomplete("destroy");
            }
            return false;
        } else {
            if (!$this.data("ui-autocomplete")) {
                metaDataAutoComplete($this, 'code');
            }
        }
    });
    $(document.body).on("keydown", 'input.md-name-autocomplete:not(disabled, readonly)', function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        var $this = $(this);
        if (code === 13) {
            if ($this.data("ui-autocomplete")) {
                $this.autocomplete("destroy");
            }
            return false;
        } else {
            if (!$this.data("ui-autocomplete")) {
                metaDataAutoComplete($this, 'name');
            }
        }
    });

    $(document.body).on('shown.bs.dropdown', '.pf-meta-manage-dropdown', function() {
        var $this = $(this);
        var $parent = $this.closest('.input-group');
        var $button = $this.children('.dropdown-toggle');
        var $ul = $this.find('.dropdown-menu');
        var ulOffset = $ul.offset();
        var metaDataId = $parent.find('input[type="hidden"]').val();

        var spaceUp = (ulOffset.top - $button.height() - $ul.height()) - $(window).scrollTop();
        var spaceDown = $(window).scrollTop() + $(window).height() - (ulOffset.top + $ul.height());
        var $parentTable = $this.closest('.bp-overflow-xy-auto');
        
        if (spaceDown < 0 && (spaceUp >= 0 || spaceUp > spaceDown)) {
            $this.addClass('dropup');
        }
        
        $this.closest('.table-scrollable').css('overflow', 'visible');
        
        if ($parentTable.length) {
            $parentTable.css('overflow', 'visible');
            var $zIndexCells = $parentTable.find('th[data-cell-path], td[data-cell-path]').filter(function() {
                return $(this).css('z-index');
            });
            $zIndexCells.addClass('zindex-auto');
        }

        if (metaDataId != '') {
            
            var items = ['<li><a href="mdmetadata/gotoEditMeta/' + metaDataId + '" target="_blank">Засах</a></li>'];
            items.push('<li><a href="mdmetadata/gotoFolder/' + metaDataId + '" target="_blank">Фолдер руу очих</a></li>');
            
            if ($this.hasAttr('data-isworkflow') && $this.attr('data-isworkflow') == '1') {
                items.push('<li><a href="mdobject/dataview/1469764796675829&dv[refstructureid][]='+metaDataId+'" target="_blank">Ажлын урсгал</a></li>');
            }
                
            $ul.empty().append(items.join(''));
        } else {
            $ul.empty().append('<li><a href="javascript:;">Үзүүлэлт сонгоно уу!</a></li>');
        }
        
    });
    $(document.body).on('hidden.bs.dropdown', '.pf-meta-manage-dropdown', function() {
        var $this = $(this);
        var $parentTable = $this.closest('.bp-overflow-xy-auto');
        $this.removeClass('dropup');
        $this.closest('.table-scrollable').css('overflow', '');
        if ($parentTable.length) {
            $parentTable.css('overflow', 'visible');
            var $zIndexCells = $parentTable.find('th[data-cell-path], td[data-cell-path]').filter(function() {
                return $(this).css('z-index');
            });
            $zIndexCells.removeClass('zindex-auto');
        }
    });
    
    $(document.body).on('focusin', 'input.globeCodeInput', function(e) {
        var $this = $(this);
        var $parent = $this.parent();

        if (!$parent.hasClass('input-group')) {

            var $class = $this.attr('class');
            var $value = $this.val();
            var $name = $this.attr('name');
            var $placeholder = (typeof $this.attr("placeholder") === 'undefined' ? '' : $this.attr("placeholder"));
            var $style = (typeof $this.attr("style") === 'undefined' ? '' : $this.attr("style"));
            var $id = (typeof $this.attr("id") === 'undefined' ? '' : $this.attr("id"));
            var $title = (typeof $this.attr("title") === 'undefined' ? '' : $this.attr("title"));

            $this.remove();

            $parent.prepend('<div class="input-group" style="width: 100%">' +
                '<input type="text" name="' + $name + '" id="' + $id + '" class="' + $class + '" style="' + $style + '" value="' + $value + '" title="' + $title + '" placeholder="' + $placeholder + '">' +
                '<span class="input-group-btn">' +
                    '<button type="button" class="btn btn-sm purple-plum form-control-sm mr0" onclick="setGlobeCode(this);">...</button>' +
                '</span>' +
            '</div>');
        }

        $parent.find('input[type="text"]').select();

        return e.preventDefault();
    });
    
    $(document.body).on('click', 'ul.bp-options-tab > li > a', function(){
        var $this = $(this);
        var $ul = $this.closest('ul');
        var $uniqId = $ul.attr('data-uniq-id');
        var $processId = $ul.attr('data-process-id');
        var $href = $this.attr('href').replace('#', '');
        var $hrefList = $href.split('-');
        var $optionCode = $hrefList[3];
        
        if ($('body').find('#bp-op-'+$uniqId+'-'+$optionCode).children().length == 0) {
            businessProcessOption($uniqId, $processId, $optionCode);
        }
    });
    
    $('.change-user-key').on('click', function(e) {
        var $dialogName = 'dialog-confirm-bp-detail';
        if (!$("#" + $dialogName).length) {$('<div id="' + $dialogName + '"></div>').appendTo('body');}
        var $dialog = $('#' + $dialogName);
        
        $.ajax({
            type: 'post',
            url: 'mduser/userKeys', 
            beforeSend: function() {
                Core.blockUI({boxed : true, message: 'Loading...'});  
            }, 
            success: function (dataHtml) {
                $dialog.append(dataHtml);
                $dialog.dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    dialogClass: 'altn-custom-dialog', 
                    title: '',
                    width: 550,
                    height: 'auto',
                    maxHeight: $(window).height() - 50,
                    modal: true,
                    closeOnEscape: isCloseOnEscape, 
                    position: {my: 'top', at: 'top+40'}, 
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    }
                });
                $dialog.dialog('open');
                Core.unblockUI();
            }
        });
    });
    
    $(document.body).on("keydown", 'input.md-folder-code-autocomplete:not(disabled, readonly)', function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var _this = $(this);
        var $isName = 'code';
        var $value = _this.val();

        if (code === 13) {
            if (_this.data("ui-autocomplete")) {
                _this.autocomplete("destroy");
            }

            if (typeof _this.attr('data-ac-id') !== 'undefined') {
                $isName = 'idselect';
                $value = _this.attr('data-ac-id');
            }            

            $.ajax({
                type: 'post',
                url: 'mdfolder/metaFolderGridAutoComplete',
                data: {q: $value, type: $isName},
                dataType: "json",
                async: false,
                beforeSend: function () {
                    _this.addClass("spinner2");
                },
                success: function (data) {
                    _this.removeAttr('data-ac-id');

                    if (data.length === 1) {
                        var code = data[0].split("|#");
                        $('input[name="folderId"]').val(code[0]);
                        $("input[id*='_displayField']").val(code[1]);
                        $("input[id*='_nameField']").val(code[2]);
                    } else {
                        $('input[name="folderId"]').val('');
                        $("input[id*='_displayField']").val('');
                        $("input[id*='_nameField']").val('');
                    }
                    
                    _this.removeClass("spinner2");
                }
            });
            return false;
        } else {
            if (!_this.data("ui-autocomplete")) {
                metaFolderAutoComplete(_this, 'code');
            }
        }
    });   
    $(document.body).on("keydown", 'input.md-folder-name-autocomplete:not(disabled, readonly)', function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var _this = $(this);
        var $isName = 'name';
        var $value = _this.val();

        if (code === 13) {
            if (_this.data("ui-autocomplete")) {
                _this.autocomplete("destroy");
            }

            if (typeof _this.attr('data-ac-id') !== 'undefined') {
                $isName = 'idselect';
                $value = _this.attr('data-ac-id');
            }            

            $.ajax({
                type: 'post',
                url: 'mdfolder/metaFolderGridAutoComplete',
                data: {q: $value, type: $isName},
                dataType: "json",
                async: false,
                beforeSend: function () {
                    _this.addClass("spinner2");
                },
                success: function (data) {
                    _this.removeAttr('data-ac-id');

                    if (data.length === 1) {
                        var code = data[0].split("|#");
                        $('input[name="folderId"]').val(code[0]);
                        $("input[id*='_displayField']").val(code[1]);
                        $("input[id*='_nameField']").val(code[2]);
                    } else {
                        $('input[name="folderId"]').val('');
                        $("input[id*='_displayField']").val('');
                        $("input[id*='_nameField']").val('');
                    }
                    
                    _this.removeClass("spinner2");
                }
            });
            return false;
        } else {
            if (!_this.data("ui-autocomplete")) {
                metaFolderAutoComplete(_this, 'name');
            }
        }
    });   
    
    $('#isHdrTestCaseMode').on('click', function() {
        $.ajax({
            type: 'post',
            url: 'mduser/setTestCaseMode',
            data: {isChecked: $(this).is(':checked') ? 1 : 0},
            dataType: 'json',
            success: function(data) {}
        });
    });
    
});

$(document).bind('keydown', 'Shift+l', function() {
    generateLanguageFile();
    /*checkUrlAuthLoginByFnc('generateLanguageFile');*/
    clearConsole();
});
$(document).bind('keydown', 'Shift+d', function() {
    checkUrlAuthLoginByFnc('clearProcessCache');
    clearConsole();
});
$(document).bind('keydown', 'Shift+f1', function() {
    systemUpdatePusher();
    clearConsole();
});
$(document).bind('keydown', 'Shift+f12', function() {
    systemUpdatePusherStop();
    clearConsole();
});
$(document).bind('keydown', 'Ctrl+Shift+f12', function() {
    systemCacheClear();
    /*checkUrlAuthLoginByFnc('systemCacheClear');*/
    clearConsole();
});
$(document).bind('keydown', 'Ctrl+q', function(e){
    if ($('body').find('input[data-path="isUsedGl"]').length > 0 && $('body').find('input[data-path="isUsedGl"]').is(':visible') && $('body').find('.CodeMirror:visible').length == 0) {
        var $glConnectElement = $('body').find('input[data-path="isUsedGl"]:visible:last');
        if (!$glConnectElement.is(':disabled') && !$glConnectElement.is('[readonly]')) {
            $glConnectElement.click();
        }
    }
    e.preventDefault();
    return false;
});
$(document.body).on('keydown', 'input, select, textarea, a, button', 'Ctrl+q', function(e){
    if ($('body').find('input[data-path="isUsedGl"]').length > 0 && $('body').find('input[data-path="isUsedGl"]').is(':visible') && $('body').find('.CodeMirror:visible').length == 0) {
        var $glConnectElement = $('body').find('input[data-path="isUsedGl"]:visible:last');
        if (!$glConnectElement.is(':disabled') && !$glConnectElement.is('[readonly]')) {
            $glConnectElement.click();
        }
    }
    e.preventDefault();
    return false;
});
$(document).bind('keydown', 'Alt+Shift+f11', function(e){
    if (typeof isSysUpdateAddonScript === 'undefined') {
        $.getScript('middleware/assets/js/addon/sysupdate.js').done(function() {
            sysUpdatePopup();
        });
    } else {
        sysUpdatePopup();
    }
    e.preventDefault();
    return false;
});
$(document).bind('keydown', 'F1', function(e){
    helpHotKeys();
    e.preventDefault();
    return false;
});
$(document.body).on('keydown', 'input, select, textarea, a, button', 'F1', function(e){
    $(this).trigger('change');
    helpHotKeys();
    e.preventDefault();
    return false;
});
$(document).bind('keydown', 'Shift+t', function(){
    var metaDvId = '', metaBpId = '', metaStId = '';

    if ($('body').find('.main-dataview-container').length > 0 && $('body').find('.main-dataview-container').is(':visible')) {

        var $dataViewElement = $('body').find(".main-dataview-container:visible");
        var $dataViewElementArr = $dataViewElement.attr('id').split('-');
        metaDvId = $dataViewElementArr[3];
    } 

    if ($('body').find("div[id*='bp-window-']").length > 0 && $('body').find("div[id*='bp-window-']").is(':visible')) {

        var $processElement = $('body').find("div[id*='bp-window-']:visible");
        metaBpId = $processElement.attr('data-process-id');
    } 

    if ($('body').find("div[id*='dataview-statement-search-']").length > 0 && $('body').find("div[id*='dataview-statement-search-']").is(':visible')) {

        $(document).on('focusin', function(e) {
            if ($(e.target).closest(".mce-window, .moxman-window").length) {
                e.stopImmediatePropagation();
            }
        });            
        var $statementElement = $('body').find("div[id*='dataview-statement-search-']:visible:last");
        metaStId = $statementElement.attr('data-process-id');
    }

    if (metaDvId || metaBpId || metaStId) {
        var setMetaId = '';

        if (metaDvId) {
            setMetaId = metaDvId;
        }

        if (metaBpId) {
            setMetaId = metaBpId;
        }

        if (metaStId) {
            setMetaId = metaStId;
        }

        if (typeof isMetaUpgrade === 'undefined') {
            $.getScript('middleware/assets/js/upgrade/script.js').done(function() {
                importAnotherServer(setMetaId);
            });
        } else {
            importAnotherServer(setMetaId);
        }
    }
});
$(document).bind('keydown', 'Ctrl+Shift+f', function(e){
    pfHelpDataView();
    e.preventDefault();
    return false;
});
$(document.body).on('keydown', 'input, select, textarea, a, button', 'Ctrl+Shift+f', function(e){
    $(this).trigger('change');
    pfHelpDataView();
    e.preventDefault();
    return false;
});
$(document).bind('keydown', 'Alt+s', function(e){
    pfQuickLinkDataView();
    e.preventDefault();
    return false;
});
$(document.body).on('keydown', 'input, select, textarea, a, button', 'Alt+s', function(e){
    pfQuickLinkDataView();
    e.preventDefault();
    return false;
});
function bindEvent(element, eventName, eventHandler) {
    if (element.addEventListener) {
        element.addEventListener(eventName, eventHandler, false);
    } else if (element.attachEvent) {
        element.attachEvent('on' + eventName, eventHandler);
    }
}
bindEvent(window, 'message', function (e) {
    
    if (typeof e.data !== 'undefined' && typeof e.data === 'string') {
        
        if ((e.data).indexOf('CLICK_DRILL_LAYOUT_ID') !== -1 || (e.data).indexOf('CLICK_EXPAND_DV_ID') !== -1) {
            
            drillDownStatement(this, e.data);
            
        } else if ((e.data).indexOf('isBudget=1') !== -1) {

            if (typeof IS_LOAD_GL_SCRIPT === 'undefined') {
                $.getScript('middleware/assets/js/mdgl.js').done(function() {
                    budgetConnectGeneralLedger(this, e.data);
                });
            } else {
                budgetConnectGeneralLedger(this, e.data);
            }
            
        } else if ((e.data).indexOf('isProcess=1') !== -1) {

            if (typeof isCommonAddonScript === 'undefined') {
                $.getScript('middleware/assets/js/addon/common.js').done(function() {
                    callBudgetProcess(this, e.data);
                });
            } else {
                callBudgetProcess(this, e.data);
            }
            
        } else if ((e.data).indexOf('{"kpiIndicatorCommand"') !== -1) {
            kpiIndicatorFormCommand(this, e.data);
        }
    } else if (e.data.hasOwnProperty('monpass_token')) {
        signCloundMonpass(e.data); 
    }
});
function metaFolderAutoComplete(elem, type) {
    var _this = elem;
    var _parent = _this.closest("div.meta-autocomplete-wrap");
    var params = _parent.attr('data-params');
    var isHoverSelect = false;

    _this.autocomplete({
        minLength: 1,
        maxShowItems: 30,
        delay: 500,
        highlightClass: "lookup-ac-highlight",
        appendTo: "body",
        position: { my: "left top", at: "left bottom", collision: "flip flip" },
        autoSelect: false,
        source: function(request, response) {
            $.ajax({
                type: 'post',
                url: 'mdfolder/metaFolderGridAutoComplete',
                dataType: 'json',
                data: {
                    q: request.term,
                    type: type,
                    params: params
                },
                success: function(data) {
                    if (type == 'code') {
                        response($.map(data, function(item) {
                            var code = item.split("|#");
                            return {
                                value: code[1],
                                label: code[1],
                                name: code[2],
                                id: code[0]
                            };
                        }));
                    } else {
                        response($.map(data, function(item) {
                            var code = item.split("|#");
                            return {
                                value: code[2],
                                label: code[1],
                                name: code[2],
                                id: code[0]
                            };
                        }));
                    }
                }
            });
        },
        focus: function(event, ui) {
            if (typeof event.keyCode === 'undefined' || event.keyCode == 0) {
                isHoverSelect = false;
            } else {
                if (event.keyCode == 38 || event.keyCode == 40) {
                    isHoverSelect = true;
                }
            }
            return false;
        },
        open: function() {
            /*$(this).autocomplete('widget').zIndex(99999999999999);*/
            return false;
        },
        close: function() {
            $(this).autocomplete("option", "appendTo", "body");
        },
        select: function(event, ui) {
            var origEvent = event;

            if (isHoverSelect || event.originalEvent.originalEvent.type == 'click') {
            _parent.find('input[name="folderId"]').val(ui.item.id);
                if (type === 'code') {
                    _parent.find("input[id*='_displayField']").val(ui.item.label);            
                    _parent.find("input[id*='_displayField']").attr('data-ac-id', ui.item.id);        
                } else {
                    _parent.find("input[id*='_nameField']").val(ui.item.name);
                    _parent.find("input[id*='_nameField']").attr('data-ac-id', ui.item.id);
                }
            } else {
                if (type === 'code') {
                    if (ui.item.label === _this.val()) {
                        _parent.find("input[id*='_displayField']").val(ui.item.label);
                        _parent.find("input[id*='_nameField']").val(ui.item.name);
                    } else {
                        _parent.find("input[id*='_displayField']").val(_this.val());
                        event.preventDefault();
                    }
                } else {
                    if (ui.item.name === _this.val()) {
                        _parent.find("input[id*='_displayField']").val(ui.item.label);
                        _parent.find("input[id*='_nameField']").val(ui.item.name);
                    } else {
                        _parent.find("input[id*='_nameField']").val(_this.val());
                        event.preventDefault();
                    }
                }
            }

            while (origEvent.originalEvent !== undefined) {
                origEvent = origEvent.originalEvent;
            }

            if (origEvent.type === 'click') {
                var e = jQuery.Event("keydown");
                e.keyCode = e.which = 13;
                _this.trigger(e);
            }
        }
    }).autocomplete("instance")._renderItem = function(ul, item) {
        ul.addClass('lookup-ac-render');

        if (type === 'code') {
            var re = new RegExp("(" + this.term + ")", "gi"),
                cls = this.options.highlightClass,
                template = "<span class='" + cls + "'>$1</span>",
                label = item.label.replace(re, template);

            return $('<li>').append('<div class="lookup-ac-render-code">' + label + '</div><div class="lookup-ac-render-name">' + item.name + '</div>').appendTo(ul);
        } else {
            var re = new RegExp("(" + this.term + ")", "gi"),
                cls = this.options.highlightClass,
                template = "<span class='" + cls + "'>$1</span>",
                name = item.name.replace(re, template);

            return $('<li>').append('<div class="lookup-ac-render-code">' + item.label + '</div><div class="lookup-ac-render-name">' + name + '</div>').appendTo(ul);
        }
    };
}
function initPosLiftPrinter(responsedata) {
    if (typeof checkInitPosJS === 'undefined') {
        $.ajax({
            url: "middleware/assets/js/pos/pos.js",
            dataType: "script",
            cache: false,
            async: false
        });
    }
    callPosLiftPrint(responsedata);
}
function menuMetaAddByUser(elem, metaDataId) {
    if (typeof IS_LOAD_MENUMETA_SCRIPT === 'undefined') {
        $.getScript('middleware/assets/js/addon/menumeta.js').done(function() {
            menuMetaAddByUserInit(elem, metaDataId);
        });
    } else {
        menuMetaAddByUserInit(elem, metaDataId);
    }            
}
function moduleMetaAddByUser(elem) {
    if (typeof IS_LOAD_MENUMETA_SCRIPT === 'undefined') {
        $.getScript('middleware/assets/js/addon/menumeta.js').done(function() {
            moduleMetaAddByUserInit(elem);
        });
    } else {
        moduleMetaAddByUserInit(elem);
    }            
}
function metaImportBasketByExternalServer(elem, processMetaDataId, dataViewId, selectedRow) {
    commonMetaDataGrid('multi', elem, 'autoSearch=1&isExternalServer=1&lookupMetaId='+dataViewId, 'metaImportExternalServerFromBasket');
}
function metaImportExternalServerFromBasket(chooseType, elem, params) {
    PNotify.removeAll();
    var metaBasketNum = $('#commonBasketMetaDataGrid').datagrid('getData').total;
    
    if (metaBasketNum > 0) {
        
        var rows = $('#commonBasketMetaDataGrid').datagrid('getRows'), metaId = [];
        for (var i = 0; i < rows.length; i++) {    
            metaId.push(rows[i].META_DATA_ID);
        }
        
        $.ajax({
            type: 'post',
            url: 'mdupgrade/metaImportExternalServer', 
            data: {metaId: metaId}, 
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({boxed : true, message: 'Loading...'});  
            }, 
            success: function (data) {
                
                if (data.status == 'success') {
                    
                    $('#dialog-commonmetadata').dialog('close');
                    
                    var paramsObj = qryStrToObj(params);
                    var lookupMetaId = paramsObj.lookupMetaId;
                    var $basketGrid = $('#commonSelectableBasketDataGrid_' + lookupMetaId);
                    
                    for (var i = 0; i < rows.length; i++) {
                        var row = rows[i];
                        var isAddRow = true;
                        var subrows = $basketGrid.datagrid('getRows');
                        for (var j = 0; j < subrows.length; j++) {
                            var subrow = subrows[j];
                            if (subrow.id === row.META_DATA_ID) {
                                isAddRow = false;
                            }
                        }
                        if (isAddRow) {
                            $basketGrid.datagrid('appendRow', {
                                id: row.META_DATA_ID,
                                metadatacode: row.META_DATA_CODE,
                                metadataname: row.META_DATA_NAME, 
                                action: '<a href="javascript:;" onclick="deleteCommonSelectableBasket_'+lookupMetaId+'(this);" class="btn btn-xs red" title="'+plang.get('META_00002')+'"><i class="far fa-trash"></i></a>'
                            });
                        }
                    }
                    
                    $('#commonSelectedCount_' + lookupMetaId).text($basketGrid.datagrid('getData').total);
    
                } else {
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                }
                
                Core.unblockUI();
            }
        });
        
    } else {
        new PNotify({
            title: 'Warning',
            text: 'Үзүүлэлт сонгоно уу!',
            type: 'warning',
            sticker: false
        });
    }
}
function metaImportCopy(folderId) {
    if (typeof isMetaUpgrade === 'undefined') {
        $.getScript('middleware/assets/js/upgrade/script.js').done(function() {
            metaImportCopyInit(folderId);
        });
    } else {
        metaImportCopyInit(folderId);
    }
}
function pfQuickLinkDataView() {
    var isDev = getConfigValue('is_dev');
    
    if (isDev) {
        $.ajax({
            type: 'post',
            url: 'api/callDataview',
            data: {dataviewId: '1670646118004411'},
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                
                if (data.status == 'success') {
                
                    var $dialogName = 'dialog-pfquicklink';
                    if (!$("#" + $dialogName).length) {
                        $('<div id="' + $dialogName + '"></div>').appendTo('body');
                    }
                    var $dialog = $('#' + $dialogName), html = [], rows = data.result, n = 0;

                    html.push('<div class="form-group-feedback form-group-feedback-left">');
                        html.push('<input type="text" class="form-control form-control-lg alpha-grey" placeholder="Хайх.." style="background-color: #f2f2f2;" spellcheck="false">');
                        html.push('<div class="form-control-feedback form-control-feedback-lg">');
                            html.push('<i class="icon-search4 text-muted"></i>');
                        html.push('</div>');
                    html.push('</div>');
                    
                    const groupByRows = rows.reduce((acc, obj) => {
                        const key = obj['groupname'];
                        if (!acc[key]) {
                            acc[key] = [];
                        }
                        acc[key].push(obj);
                        return acc;
                    }, {});
                    
                    html.push('<div class="list-group pf-quick-link" style="max-height: 500px; overflow: auto;">');
                    
                    for (var r in groupByRows) {
            
                        var linkList = groupByRows[r];

                        if (r != null && r != 'null') {
                            html.push('<div class="list-group-item font-weight-semibold">'+r+'</div>');
                        }

                        for (var k in linkList) {
                            
                            var link = ltrim(trim(linkList[k]['url']), '/');
                            var selectedLink = '';
                            var metaTypeId = linkList[k]['metatypeid'];
                            var metaDataId = linkList[k]['metadataid'];
                            var onClick = '';
                            var target = '_blank';
                            
                            if (n == 0 && k == 0) {
                                selectedLink = ' selected';
                            }
                            
                            if (metaTypeId != '' & metaTypeId != null && metaDataId != '' & metaDataId != null) {
                                
                                link = 'javascript:;';
                                target = '';
                                
                                if (metaTypeId == '200101010000011') {
                                    
                                    onClick = "callWebServiceByMeta('"+metaDataId+"', true, '', false, {callerType: 'PLATFORM_SUPER_SEARCH_LIST', isMenu: false});";
                                            
                                } else {
                                    
                                    var metaTypeCode = '';
                                    
                                    if (metaTypeId == '200101010000016') {
                                        metaTypeCode = 'dataview';
                                    } else if (metaTypeId == '200101010000011') {
                                        metaTypeCode = 'process';
                                    }

                                    onClick = "gridDrillDownLink(this, 'PLATFORM_SUPER_SEARCH_LIST', '"+metaTypeCode+"', '1', '', '"+metaDataId+"', '', '"+metaDataId+"', '', true, true);";
                                }
                            }
                            
                            html.push('<a href="'+link+'" class="list-group-item list-group-item-action'+selectedLink+'" target="'+target+'" onclick="'+onClick+'">'+linkList[k]['name']+'</a>');
                        }
                        
                        n ++;
                    }
                    
                    html.push('</div>');

                    html.push('<style type="text/css">');
                        html.push('.pf-quick-link {'+
                            'border: none;'+
                        '}'+
                        '.pf-quick-link .list-group-item {'+
                            'padding: 6px 0px;'+
                            'text-transform: uppercase;'+
                            'color: #555;'+
                        '}'+
                        '.pf-quick-link .list-group-item-action {'+
                            'text-transform: inherit;'+
                            'color: #222;'+
                            'padding-left: 10px;'+
                            'padding-right: 10px;'+
                            'border-radius: 4px;'+
                            'font-size: 13px;'+
                        '}'+
                        '.pf-quick-link .list-group-item-action.selected,'+ 
                        '.pf-quick-link .list-group-item-action:hover,'+ 
                        '.pf-quick-link .list-group-item-action:focus-visible {'+
                            'border: none;'+
                            'background-color: #cae1fe;'+
                        '}');
                    html.push('</style>');

                    $dialog.empty().append(html.join(''));
                    $dialog.dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: 'Түргэн холбоос',
                        width: 700,
                        height: 'auto',
                        modal: true,
                        close: function() {
                            $dialog.empty().dialog('destroy').remove();
                        }
                    });
                    $dialog.dialog('open');
                    
                    $dialog.on('keyup', 'input.form-control', function(e) {
                        
                        var code = (e.keyCode ? e.keyCode : e.which);
                        
                        if (code == 13) {
                            
                            var $link = $dialog.find('a.list-group-item-action.selected');
                            
                            if ($link.length) {
                                window.open($link.attr('href'), '_blank');
                            }
                            
                        } else if (code != 40) {
                            
                            $dialog.find('div.list-group-item').hide();
                            $dialog.find('a.list-group-item-action.selected').removeClass('selected');
                            
                            var $inputVal = $(this).val();
                        
                            $dialog.find('a.list-group-item-action').each(function(index, row) {
                                
                                var $item = $(row);
                                var found = false;
                                var regExp = new RegExp($inputVal, 'i');
                                if (regExp.test($item.text())) {
                                    found = true;
                                }

                                if (found == true) {
                                    $item.prevAll('div.list-group-item:eq(0)').show();
                                    $item.show();
                                } else {
                                    $item.hide();
                                }
                            });
                            
                            $dialog.find('a.list-group-item-action:visible:eq(0)').addClass('selected');
                        
                        } else {
                            $dialog.find('a.list-group-item-action:visible:eq(0)').focus();
                        }
                    });
                    
                    $dialog.on('keydown', 'a.list-group-item-action', function(e) {
                        var code = (e.keyCode ? e.keyCode : e.which);
                        var $this = $(this);
                        $dialog.find('a.list-group-item-action.selected').removeClass('selected');
                        
                        if (code == 40) { /*down*/
                            $this.nextAll('a.list-group-item-action:visible:eq(0)').addClass('selected').focus();
                        } else if (code == 38) { /*up*/
                            $this.prevAll('a.list-group-item-action:visible:eq(0)').addClass('selected').focus();
                        }
                    });
                }
                
                Core.unblockUI();
            }
        });
    }
}
function mvFlowChartExecuteInit(elem, url, indicatorId) {
    if (typeof isKpiIndicatorScript === 'undefined') {
        $.getScript('middleware/assets/js/addon/indicator.js').done(function() {
            mvFlowChartExecute(elem, url, indicatorId);
        });
    } else {
        mvFlowChartExecute(elem, url, indicatorId);
    } 
}
function mvProductRenderInit(elem, url, indicatorId) {
    if (typeof isKpiIndicatorScript === 'undefined') {
        $.getScript('middleware/assets/js/addon/indicator.js').done(function() {
            mvProductRender(elem, url, indicatorId);
        });
    } else {
        mvProductRender(elem, url, indicatorId);
    } 
}
function tempBpFullExpressionSave() {
    if ($('.shift-p-ignore:visible').length == 0 && $('body').find("div[id*='bp-window-']").length > 0 && $('body').find("div[id*='bp-window-']").is(':visible')) {

        PNotify.removeAll();

        var $processElement = $('body').find("div[id*='bp-window-']:visible");
        bpFullExpression($processElement.attr('data-process-id'), true);
    }
}

var metaConfigChangeLogRequest = null;
function metaConfigChangeLog(mainSelector, isSetUniqId) {
    if (isSetUniqId == true && !mainSelector.hasAttr('data-uniqid')) {
        if (metaConfigChangeLogRequest != null) {
            metaConfigChangeLogRequest.abort();
        }
        metaConfigChangeLogRequest = $.ajax({
            type: 'post',
            url: 'mdlog/metaConfigChangeLog',
            data: {metaId: mainSelector.attr('data-metadataid')},
            dataType: 'json',
            success: function(data) {
                metaConfigChangeLogRequest = null;
                if (data.status == 'success') {
                    mainSelector.attr('data-uniqid', data.uniqId);
                }
            },
            error: function(e) { console.log(e); }
        });
    } else if (isSetUniqId == false && mainSelector.hasAttr('data-uniqid')) {
        if (metaConfigChangeLogRequest != null) {
            metaConfigChangeLogRequest.abort();
        }
        metaConfigChangeLogRequest = $.ajax({
            type: 'post',
            url: 'mdlog/metaConfigChangeLog',
            data: {metaId: mainSelector.attr('data-metadataid'), uniqId: mainSelector.attr('data-uniqid')},
            dataType: 'json',
            success: function(data) { 
                metaConfigChangeLogRequest = null;
                if (data.status == 'success') {
                    mainSelector.removeAttr('data-changed data-uniqid');
                }
            },
            error: function(e) { console.log(e); }
        });
    }
}
function metaVerseCommandPromptIframe(elem) {
    $.ajax({
        type: 'post',
        url: 'mdintegration/metaVerseCommandPromptIframeUrl',
        dataType: 'json',
        success: function(data) { 
            PNotify.removeAll();
            
            if (data.status == 'success') {
                
                var $dialogName = 'dialog-mv-commandprompt-iframe';
                if (!$('#' + $dialogName).length) { 
                    $('<div id="' + $dialogName + '"></div>').appendTo('body'); 
                } else {
                    $('#' + $dialogName).dialogExtend('restore');
                    return;
                }
                var $dialog = $('#' + $dialogName), html = [];
                
                html.push('<iframe style="width: 100%; height: '+($(window).height() - 150)+'px; border: 0" src="'+data.url+'"></iframe>');

                $dialog.empty().append(html.join(''));
                $dialog.dialog({
                    dialogClass: 'no-padding-dialog',
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: 'MetaVerse Command Prompt', 
                    width: 1000,
                    height: 'auto',
                    minHeight: 500,
                    modal: true,
                    position: {my: 'top', at: 'top+0'},
                    resize: function() {
                        metaVerseCommandPromptIframeResize($dialog);
                    }, 
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                    }
                }).dialogExtend({
                    "closable": true,
                    "maximizable": true,
                    "minimizable": true,
                    "collapsable": false,
                    "dblclick": "maximize",
                    "minimizeLocation": "left",
                    "icons": {
                        "close": "ui-icon-circle-close",
                        "maximize": "ui-icon-extlink",
                        "minimize": "ui-icon-minus",
                        "collapse": "ui-icon-triangle-1-s",
                        "restore": "ui-icon-newwin"
                    }, 
                    "maximize": function() { 
                        metaVerseCommandPromptIframeResize($dialog);
                        $dialog.closest(".ui-dialog").nextAll('.ui-widget-overlay:first').removeClass('display-none');
                    }, 
                    "minimize": function() { 
                        $dialog.closest('.ui-dialog').nextAll('.ui-widget-overlay:first').addClass('display-none');
                    }, 
                    "restore": function() { 
                        metaVerseCommandPromptIframeResize($dialog, $(window).height() - 150);
                        $dialog.closest('.ui-dialog').nextAll('.ui-widget-overlay:first').removeClass('display-none');
                    }
                });
                $dialog.dialog('open');
            } else {
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false,
                    hide: true,
                    addclass: pnotifyPosition
                });
            }
        },
        error: function(e) { console.log(e); }
    });
}

function metaVerseCommandPromptIframeResize(dialog, setHeight) {
    var $iframe = dialog.find('iframe');
    if ($iframe.length) {
        /*var iframeHeight = (typeof setHeight != 'undefined' ? setHeight : (dialog.height() - 10));*/
        $iframe.css('height', dialog.height() - 10);
    }
    return;
}