function generateBpAjax(metaDataId, isDialog, valuePackageId, isSystemMeta, htmlTag, params, wfmStatusParams, callback, callbackAfterSave, selectedRowData, dmMetaDataId, addonJsonParam, runDefaultGet, runDefaultGetParam) {

    if (typeof (isDialog) === 'undefined') {
        isDialog = false;
    }
    if (typeof (valuePackageId) === 'undefined') {
        valuePackageId = '';
    }
    if (typeof (isSystemMeta) === 'undefined') {
        isSystemMeta = 'false';
    }

    var workSpaceId = '', workSpaceParams = '';

    var processParam = {
        metaDataId: metaDataId,
        isDialog: isDialog,
        valuePackageId: valuePackageId,
        isSystemMeta: isSystemMeta,
        wfmStatusParams: wfmStatusParams,
        openParams: JSON.stringify(params),
        oneSelectedRow: selectedRowData,
        dmMetaDataId: dmMetaDataId,
        workSpaceId: workSpaceId,
        workSpaceParams: workSpaceParams,
        addonJsonParam: JSON.stringify(addonJsonParam),
        runDefaultGet: (typeof runDefaultGet !== 'undefined') ? '1' : '0',
        runDefaultGetParam: runDefaultGetParam
    };

    $.ajax({
        type: 'post',
        url: 'mdcommon/generateBpAjax',
        data: processParam,
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {

            if (typeof callback !== 'function') {
                if (data.mode === 'dialog') {
                    var $dialogName = 'dialog-businessprocess-' + metaDataId;

                    if (!$('#' + $dialogName).length) {
                        $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
                    } else {
                        $('#' + $dialogName).dialogExtend('restore');
                        Core.unblockUI();
                        return;
                    }

                    var $dialog = $('#' + $dialogName);
                    $dialog.empty().append(data.Html);

                    var hidePrintButton = '', runModeButton = '';
                    var processForm = $dialog.fins('form'),
                        processUniqId = processForm.parent().attr('data-bp-uniq-id');

                    if (typeof data.save_and_print === 'undefined') {
                        hidePrintButton = ' hide';
                    }

                    if (data.run_mode === '') {
                        runModeButton = ' hide';
                    }

                    var buttons = [
                        {
                            text: 'Тусламж', class: 'btn btn-info btn-sm float-left bp-btn-help' + (data.helpContentId === null ? ' hidden' : ''), click: function () {
                                getHelpContent(data.helpContentId, data.metaDataId, data.Title);
                            }
                        },
                        {
                            text: data.run_mode,
                            class: 'btn green-meadow btn-sm bp-run-btn bp-btn-saveadd ' + runModeButton,
                            click: function (e) {

                                if (window['processBeforeSave_' + processUniqId]($(e.target))) {

                                    if (bpFormValidate(processForm)) {

                                        if (typeof window[processUniqId + '_dialog'] !== 'undefined' && typeof window[processUniqId + '_note'] !== 'undefined' || typeof window[processUniqId + '_title'] !== 'undefined') {
                                            $("#" + window[processUniqId + '_dialog']).empty().append(window[processUniqId + '_note']);
                                            $("#" + window[processUniqId + '_dialog']).dialog({
                                                cache: false,
                                                resizable: false,
                                                bgiframe: true,
                                                autoOpen: false,
                                                title: window[processUniqId + '_title'],
                                                width: 370,
                                                height: "auto",
                                                modal: true,
                                                close: function () {
                                                    $("#" + window[processUniqId + '_dialog']).empty().dialog('destroy').remove();
                                                },
                                                buttons: [{
                                                        text: 'Тийм',
                                                        class: 'btn green-meadow btn-sm',
                                                        click: function () {
                                                            if (typeof window[processUniqId + '_message'] !== 'undefined' && typeof window[processUniqId + '_messageType'] !== 'undefined') {
                                                                PNotify.removeAll();
                                                                new PNotify({
                                                                    title: window[processUniqId + '_messageType'],
                                                                    text: window[processUniqId + '_message'],
                                                                    type: window[processUniqId + '_messageType'],
                                                                    sticker: false
                                                                });
                                                            }
                                                            callWebServiceByMetaRunMode(processForm, $dialogName, processUniqId, e.target);

                                                            $("#" + window[processUniqId + '_dialog']).empty().dialog('destroy').remove();
                                                        }
                                                    },
                                                    {
                                                        text: 'Үгүй',
                                                        class: 'btn blue-madison btn-sm',
                                                        click: function () {
                                                            $("#" + window[processUniqId + '_dialog']).empty().dialog('destroy').remove();
                                                        }
                                                    }
                                                ]
                                            });
                                            $("#" + window[processUniqId + '_dialog']).dialog('open');
                                        } else {
                                            callWebServiceByMetaRunMode(processForm, $dialogName, processUniqId, e.target, callbackAfterSave);
                                        }

                                    } else {
                                        bpIgnoreGroupRemove(processForm);
                                    }

                                } else {
                                    bpIgnoreGroupRemove(processForm);
                                }
                            }
                        }, {
                            text: data.run_btn,
                            class: 'btn green-meadow btn-sm bp-run-btn bp-btn-save',
                            click: function (e) {

                                if (window['processBeforeSave_' + processUniqId]($(e.target))) {

                                    if (bpFormValidate(processForm)) {

                                        if (typeof window[processUniqId + '_dialog'] !== 'undefined' && typeof window[processUniqId + '_note'] !== 'undefined' || typeof window[processUniqId + '_title'] !== 'undefined') {
                                            $("#" + window[processUniqId + '_dialog']).empty().append(window[processUniqId + '_note']);
                                            $("#" + window[processUniqId + '_dialog']).dialog({
                                                cache: false,
                                                resizable: false,
                                                bgiframe: true,
                                                autoOpen: false,
                                                title: window[processUniqId + '_title'],
                                                width: 370,
                                                height: "auto",
                                                modal: true,
                                                close: function () {
                                                    $("#" + window[processUniqId + '_dialog']).empty().dialog('destroy').remove();
                                                },
                                                buttons: [{
                                                        text: 'Тийм',
                                                        class: 'btn green-meadow btn-sm',
                                                        click: function () {
                                                            if (typeof window[processUniqId + '_message'] !== 'undefined' && typeof window[processUniqId + '_messageType'] !== 'undefined') {
                                                                PNotify.removeAll();
                                                                new PNotify({
                                                                    title: window[processUniqId + '_messageType'],
                                                                    text: window[processUniqId + '_message'],
                                                                    type: window[processUniqId + '_messageType'],
                                                                    sticker: false
                                                                });
                                                            }
                                                            callWebServiceByMetaRunAjaxSubmit(processForm, $dialogName, processUniqId, e.target, callbackAfterSave);

                                                            $("#" + window[processUniqId + '_dialog']).empty().dialog('destroy').remove();
                                                        }
                                                    },
                                                    {
                                                        text: 'Үгүй',
                                                        class: 'btn blue-madison btn-sm',
                                                        click: function () {
                                                            $("#" + window[processUniqId + '_dialog']).empty().dialog('destroy').remove();
                                                        }
                                                    }
                                                ]
                                            });
                                            $("#" + window[processUniqId + '_dialog']).dialog('open');
                                        } else {
                                            callWebServiceByMetaRunAjaxSubmit(processForm, $dialogName, processUniqId, e.target, callbackAfterSave);
                                        }

                                    } else {
                                        bpIgnoreGroupRemove(processForm);
                                    }
                                } else {
                                    bpIgnoreGroupRemove(processForm);
                                }
                            }
                        }, {
                            text: data.save_and_print,
                            class: 'btn purple-plum btn-sm bp-run-btn bp-btn-saveprint ' + hidePrintButton,
                            click: function (e) {

                                if (window['processBeforeSave_' + processUniqId]($(e.target))) {

                                    if (bpFormValidate(processForm)) {

                                        if (typeof window[processUniqId + '_dialog'] !== 'undefined' && typeof window[processUniqId + '_note'] !== 'undefined' || typeof window[processUniqId + '_title'] !== 'undefined') {
                                            $("#" + window[processUniqId + '_dialog']).empty().append(window[processUniqId + '_note']);
                                            $("#" + window[processUniqId + '_dialog']).dialog({
                                                cache: false,
                                                resizable: false,
                                                bgiframe: true,
                                                autoOpen: false,
                                                title: window[processUniqId + '_title'],
                                                width: 370,
                                                height: "auto",
                                                modal: true,
                                                close: function () {
                                                    $("#" + window[processUniqId + '_dialog']).empty().dialog('destroy').remove();
                                                },
                                                buttons: [{
                                                        text: 'Тийм',
                                                        class: 'btn green-meadow btn-sm',
                                                        click: function () {
                                                            if (typeof window[processUniqId + '_message'] !== 'undefined' && typeof window[processUniqId + '_messageType'] !== 'undefined') {
                                                                PNotify.removeAll();
                                                                new PNotify({
                                                                    title: window[processUniqId + '_messageType'],
                                                                    text: window[processUniqId + '_message'],
                                                                    type: window[processUniqId + '_messageType'],
                                                                    sticker: false
                                                                });
                                                            }
                                                            callWebServiceByMetaPrintAjaxSubmit(processForm, $dialogName, processUniqId, e.target, metaDataId, data.get_process_id);

                                                            $("#" + window[processUniqId + '_dialog']).empty().dialog('destroy').remove();
                                                        }
                                                    },
                                                    {
                                                        text: 'Үгүй',
                                                        class: 'btn blue-madison btn-sm',
                                                        click: function () {
                                                            $("#" + window[processUniqId + '_dialog']).empty().dialog('destroy').remove();
                                                        }
                                                    }
                                                ]
                                            });
                                            $("#" + window[processUniqId + '_dialog']).dialog('open');
                                        } else {
                                            callWebServiceByMetaPrintAjaxSubmit(processForm, $dialogName, processUniqId, e.target, metaDataId, data.get_process_id);
                                        }

                                    } else {
                                        bpIgnoreGroupRemove(processForm);
                                    }

                                } else {
                                    bpIgnoreGroupRemove(processForm);
                                }
                            }
                        },
                        {text: data.close_btn, class: 'btn blue-madison btn-sm bp-btn-close', click: function () {
                                $dialog.dialog('close');
                            }}
                    ];

                    $dialog.dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.Title,
                        width: (typeof data.dialogWidth !== 'undefined') ? data.dialogWidth : '1000',
                        height: data.dialogHeight,
                        modal: true,
                        closeOnEscape: isCloseOnEscape,
                        close: function () {
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
                        $dialog.dialogExtend('maximize');
                    }

                    $dialog.dialog('open');
                    $dialog.bind("dialogextendminimize", function () {
                        $dialog.closest(".ui-dialog").nextAll(".ui-widget-overlay:first").addClass("display-none");
                    });
                    $dialog.bind("dialogextendmaximize", function () {
                        $dialog.closest(".ui-dialog").nextAll(".ui-widget-overlay:first").removeClass("display-none");
                    });
                    $dialog.bind("dialogextendrestore", function () {
                        $dialog.closest(".ui-dialog").nextAll(".ui-widget-overlay:first").removeClass("display-none");
                    });

                    Core.initBPAjax($dialog);

                } else {   
                    if (typeof htmlTag !== 'undefined' && htmlTag !== '') {
                        var $viewFormMeta = $(htmlTag);

                        $viewFormMeta.empty().append(data.Html).promise().done(function() {
                            Core.initBPAjax($viewFormMeta);
                        });
                    }
                }
            } else {
                callback(data);
            }

            Core.unblockUI();
        },
        error: function () {
            alert('Error');
        }
    });
}