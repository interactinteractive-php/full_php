var isCommonAddonScript = true;

function customerFingerRegisterZk(customerId) {
    if (customerId) {
        if ("WebSocket" in window) {
            var ws = new WebSocket("ws://localhost:58324/socket");

            ws.onopen = function () {
                var currentDateTime = GetCurrentDateTime();
                var fingerIpAddress = getConfigValue('FingerPrint_URL');
                ws.send('{"command":"fingerprint_register_zk", details: [{"key": "server", "value": "' + fingerIpAddress + '"},{"key": "customer_id", "value": "' + customerId + '"}]}');
            };
            ws.onmessage = function (evt) {
                var received_msg = evt.data;
                var jsonData = JSON.parse(received_msg);
                
                if (jsonData.status == 'success') {
                    $.ajax({
                        type: 'get',
                        url: 'mdpos/reloadFingerServer',
                        success: function(data) {
                        },
                        error: function() {
                            alert('Error');
                        }
                    });                    
                    console.log(jsonData);
                } else {
                    new PNotify({
                        title: 'Warning',
                        text: jsonData.description,
                        type: 'warning',
                        sticker: false
                    });  
                }
            };
            ws.onerror = function (event) {
                var resultJson = {
                    Status: 'Error',
                    Error: event.code
                }

                console.log(JSON.stringify(resultJson));
            };
            ws.onclose = function () {
                console.log("Connection is closed...");
            };
        } else {
            var resultJson = {
                Status: 'Error',
                Error: "WebSocket NOT supported by your Browser!"
            }

            console.log(JSON.stringify(resultJson));
        }
    } else {
        new PNotify({
            title: 'Warning',
            text: 'Empty Customer Id',
            type: 'warning',
            sticker: false
        });        
    }
}
function customerFingerReloadZk() {
    if ("WebSocket" in window) {
        var ws = new WebSocket("ws://localhost:58324/socket");

        ws.onopen = function () {
            var currentDateTime = GetCurrentDateTime();
            var fingerIpAddress = getConfigValue('FingerPrint_URL');
            ws.send('{"command":"finger_reload_zk", details: [{"key": "server", "value": "' + fingerIpAddress + '"}]}');
        };
        ws.onmessage = function (evt) {
            var received_msg = evt.data;
            var jsonData = JSON.parse(received_msg);

            if (jsonData.status == 'success') {
                new PNotify({
                    title: 'Success',
                    text: 'Success',
                    type: 'success',
                    sticker: false
                });  
            } else {
                new PNotify({
                    title: 'Warning',
                    text: jsonData.description,
                    type: 'warning',
                    sticker: false
                });  
            }
        };
        ws.onerror = function (event) {
            var resultJson = {
                Status: 'Error',
                Error: event.code
            }

            console.log(JSON.stringify(resultJson));
        };
        ws.onclose = function () {
            console.log("Connection is closed...");
        };
    } else {
        var resultJson = {
            Status: 'Error',
            Error: "WebSocket NOT supported by your Browser!"
        }

        console.log(JSON.stringify(resultJson));
    }
}
function exportWorkflowFull(elem, processMetaDataId, dataViewId, selectedRow, paramData, url) {
    var $dialogName = 'dialog-workflow-export';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var data = '<div class="alert alert-primary">'+plang.get('WINDOW_INFO_DESCRIPTION')+'</div>';
        data += '<div class=""><input type="checkbox" id="workflowIsUserExport"> <label for="workflowIsUserExport">Хэрэглэгч</label></div>';
        data += '<div><input type="checkbox" id="workflowIsRoleExport"> <label for="workflowIsRoleExport">Дүр</label></div>';
        data += '<div><input type="checkbox" id="workflowIsNotificationExport"> <label for="workflowIsNotificationExport">Сонордуулга</label></div>';
        
    $("#" + $dialogName).empty().append(data);
    $("#" + $dialogName).dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'Экспорт тохиргоо',
        width: 350,
        height: "auto",
        modal: true,
        close: function() {
            $("#" + $dialogName).empty().dialog('destroy').remove();
        },
        buttons: [{
            text: 'Экспорт',
            class: 'btn yellow-gold btn-circle btn-sm',
            click: function() {    
                var $isCheckedUser = $("#" + $dialogName).find('#workflowIsUserExport').is(':checked') ? '1' : '0';
                paramData.push({
                    'name': 'workflowIsUserExport',
                    'value': $isCheckedUser
                });
                
                $isCheckedUser = $("#" + $dialogName).find('#workflowIsRoleExport').is(':checked') ? '1' : '0';
                paramData.push({
                    'name': 'workflowIsRoleExport',
                    'value': $isCheckedUser
                });
                
                $isCheckedUser = $("#" + $dialogName).find('#workflowIsNotificationExport').is(':checked') ? '1' : '0';
                paramData.push({
                    'name': 'workflowIsNotificationExport',
                    'value': $isCheckedUser
                });
                
                Core.blockUI({
                    boxed: true,
                    message: 'Exporting...'
                });
                $.fileDownload(URL_APP + 'mdprocessflow/exportWorkflowFull', {
                    httpMethod: 'POST',
                    data: paramData
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
                $("#" + $dialogName).dialog('close');
            }
        }]
    });
    $("#" + $dialogName).dialog('open');    
    Core.initAjax($("#" + $dialogName));
}
function parseQuery(queryString) {
    var query = {};
    var pairs = (queryString[0] === '?' ? queryString.substr(1) : queryString).split('&');
    for (var i = 0; i < pairs.length; i++) {
        var pair = pairs[i].split('=');
        query[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1] || '');
    }
    return query;
}
function callBudgetProcess(elem, rowStr) {
    var $dialogName = 'dialog-call-budget-bp';
    if (!$('#' + $dialogName).length) {
        $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    var fillDataParams = '';
    var queryData = parseQuery(rowStr);
    
    // if (id) {
    //     fillDataParams = 'id='+id+'&defaultGetPf=1';
    // } else {
        // }
    fillDataParams = 'activityKeyId='+queryData.activityKeyId;
    
    $.ajax({
        type: 'post',
        url: 'mdwebservice/callMethodByMeta',
        data: {
            metaDataId: queryData.processId, 
            isDialog: true, 
            isSystemMeta: false, 
            fillDataParams: fillDataParams
        },
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({
                message: 'Loading...', 
                boxed: true
            });
        },
        success: function (data) {

            $dialog.empty().append(data.Html);

            var $processForm = $('#wsForm', '#' + $dialogName), 
                processUniqId = $processForm.parent().attr('data-bp-uniq-id');

            var buttons = [
                {text: data.run_btn, class: 'btn green-meadow btn-sm bp-btn-save', click: function (e) {
                    if (window['processBeforeSave_'+processUniqId]($(e.target))) {     

                        $processForm.validate({ 
                            ignore: '', 
                            highlight: function(element) {
                                $(element).addClass('error');
                                $(element).parent().addClass('error');
                                if ($processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                    $processForm.find("div.tab-pane:hidden:has(.error)").each(function(index, tab){
                                        var tabId = $(tab).attr('id');
                                        $processForm.find('a[href="#'+tabId+'"]').tab('show');
                                    });
                                }
                            },
                            unhighlight: function(element) {
                                $(element).removeClass('error');
                                $(element).parent().removeClass('error');
                            },
                            errorPlacement: function(){} 
                        });

                        var isValidPattern = initBusinessProcessMaskEvent($processForm);

                        if ($processForm.valid() && isValidPattern.length === 0) {
                            $processForm.ajaxSubmit({
                                type: 'post',
                                url: 'mdwebservice/runProcess',
                                dataType: 'json',
                                beforeSend: function () {
                                    Core.blockUI({
                                        boxed: true, 
                                        message: 'Түр хүлээнэ үү'
                                    });
                                },
                                success: function (responseData) {
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: responseData.status,
                                        text: responseData.message,
                                        type: responseData.status, 
                                        sticker: false
                                    });
                                        
                                    if (responseData.status === 'success') {

                                        var budgetIframe = document.getElementById('sheet_budget_iframe');
                                        var sendIframeSetValue = function(msg) {
                                            budgetIframe.contentWindow.postMessage(msg, '*');
                                        };
                                        sendIframeSetValue(JSON.stringify(responseData));                                        

                                        $dialog.dialog('close');
                                    } 
                                    Core.unblockUI();
                                },
                                error: function () {
                                    alert("Error");
                                }
                            });
                        }
                    }    
                }},
                {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                    $dialog.dialog('close');
                }}
            ];

            var dialogWidth = data.dialogWidth, dialogHeight = data.dialogHeight;

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
                closeOnEscape: (typeof isCloseOnEscape == 'undefined' ? true : isCloseOnEscape), 
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
                $dialog.dialogExtend("maximize");
            }
            $dialog.dialog('open');
        },
        error: function () {
            alert("Error");
        }
    }).done(function () {
        Core.initBPAjax($dialog);
        Core.unblockUI();
    });
}
function changeVisualWfm(elem, processMetaDataId, dataViewId, selectedRow, paramData, url) {
    $.ajax({
        type: 'post',
        url: 'mdprocessflow/controlProcessInteractive/' + dataViewId,
        data: { selectedRow: selectedRow },
        dataType: "json",
        beforeSend: function() {
            Core.blockUI({boxed: true, message: 'Loading...'});
        },
        success: function(data) {
            var dialogId = '#dialog-drilldown-dataview-' + dataViewId;

            if (!$(dialogId).length) {
                $("<div id='" + dialogId.replace('#', '') + "'></div>").appendTo('body');
            }

            $(dialogId).empty().append('<span style="background-color: #28df99;height: 12px;width: 40px;display: inline-block;margin-left: 10px;"></span> Идэвхитэй төлөв <span style="background-color: #ccc;height: 12px;width: 40px;display: inline-block;margin-left: 20px;"></span> Ажиллах болоогүй төлөв <span style="background-color: #f5a25d;height: 12px;width: 40px;display: inline-block;margin-left: 20px;"></span> Ажилласан болон Эхлэлийн төлөв<br>'+data.Html);
            $(dialogId).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: '',
                height: 900,
                width: 1000,
                modal: false,
                close: function() {
                    $(dialogId).empty().dialog('destroy').remove();
                },
                buttons: [{
                        text: plang.get('close_btn'),
                        class: 'btn blue-madison btn-sm',
                        click: function() {
                            $(dialogId).dialog('close');
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
            $(dialogId).dialog('open');
            $(dialogId).dialogExtend("maximize");

            Core.unblockUI();
        },
        error: function() {
            alert('Error');
        }
    });
}
function customImageMarkerWithDVView(elem, processMetaDataId, dataViewId, selectedRow, paramData, urlLower) {
    var $dialogName = 'dialog-pos-rest-drawtables';
    if (!$('#' + $dialogName).length) {
        $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    
    paramData.push({
        name: 'picture',
        value: selectedRow.planpicture
    });
    $.ajax({
        type: 'post',
        url: 'mdmeta/customImageMarkerWithDvCtrl',
        data: paramData,
        beforeSend: function() {
            Core.blockUI({animate: true});
        },
        success: function(dataHtml) {
            $dialog.empty().append(dataHtml);

            var buttons = [
                {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                    $dialog.dialog('close');
                }}
            ];

            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Ширээний байршил тодорхойлох',
                modal: true,
                closeOnEscape: true, 
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
                    "close": "ui-icon-circle-close"
                }
            });
            $dialog.dialogExtend("maximize");
            $dialog.dialog('open');
            Core.unblockUI();
        },
        error: function() {
            alert('Error');
        }
    }).done(function () {
        Core.initBPAjax($dialog);
        Core.unblockUI();
    });    
}
function selectedRowsToPdfZip(elem, processMetaDataId, dataViewId, selectedRow, paramData) {
    
    Core.blockUI({boxed: true, message: 'Exporting...'});
    
    var postData = paramDataToObject(paramData);
    var selectedRows = getDataViewSelectedRows(dataViewId);
    
    postData['selectedRows'] = selectedRows;
    
    $.fileDownload(URL_APP + 'mddatamodel/selectedRowsToPdfZip', {
        httpMethod: 'POST', 
        data: postData, 
        successCallback: function (url) {
            PNotify.removeAll();
            new PNotify({
                title: 'Success',
                text: 'Successfuly',
                type: 'success',
                sticker: false,
                hide: true,
                addclass: pnotifyPosition
            });
            Core.unblockUI();
        },
        failCallback: function (responseHtml, url) {
            PNotify.removeAll();
            new PNotify({
                title: 'Error',
                text: responseHtml, 
                type: 'error',
                sticker: false, 
                addclass: pnotifyPosition
            });
            Core.unblockUI();
        }
    });
}