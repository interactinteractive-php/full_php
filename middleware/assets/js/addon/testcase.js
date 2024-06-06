var isTestCaseAddonScript = true;

function runTestCase(elem, processMetaDataId, dataViewId, paramData) {
    
    var $dataGrid = window['objectdatagrid_' + dataViewId];
    var selectedRows = $dataGrid.datagrid('getSelections');
    
    if (selectedRows.length) {
        
        Core.blockUI({
            message: 'Таны хийсэн үйлдлийг боловсруулж байна түр хүлээнэ үү',
            boxed: true
        });
        
        var $panel = $dataGrid.datagrid('getPanel').find('div.datagrid-view > .datagrid-view2 > .datagrid-body');
        
        setTimeout(function() {
            
            for (var k in selectedRows) {
                
                var row = selectedRows[k];
                var rowIndex = $dataGrid.datagrid('getRowIndex', row);
                var yesLabel = plang.get('yes_btn');
                var noLabel = plang.get('no_btn');
                
                $.ajax({
                    type: 'post',
                    url: 'mdprocess/runTestCase',
                    data: {caseId: row.id},
                    dataType: 'json',
                    async: false, 
                    success: function (response) {
                        
                        var $rowElement = $panel.find('tr[datagrid-row-index="'+rowIndex+'"]');
                        var $isPass = $rowElement.find('[field="ispass"] .datagrid-cell');
                        var $resultDe = $rowElement.find('[field="resultde"] .datagrid-cell');
                        var $timer = $rowElement.find('[field="timer"] .datagrid-cell');
                        
                        $isPass.html('');
                        $resultDe.html('');
                        $timer.html('');
                        
                        if (response.status == 'success') {
                            $isPass.html('<span class="badge badge-success">'+yesLabel+'</span>');
                            $resultDe.html(JSON.stringify(response.result));
                        } else {
                            $isPass.html('<span class="badge badge-danger">'+noLabel+'</span>');
                            $resultDe.html(response.message);
                        }
                        
                        $timer.html(number_format(response.costTime, 2, '.', ',') + ' секунд');
                    }
                });
            }
            
            $dataGrid.datagrid('resize');
            Core.unblockUI();
        }, 300);
        
    } else {
        alert(plang.get('msg_pls_list_select'));
    }
}
function runAllTestCase(elem, processMetaDataId, dataViewId, paramData) {
    PNotify.removeAll();
    
    $.ajax({
        type: 'post',
        url: 'mdprocess/runAllTestCase',
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            
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
        }
    });
}
function renderBpByTestCase(elem, processMetaDataId, dataViewId, paramData) {
    
    PNotify.removeAll();
    paramData = paramDataToObject(paramData);
    
    $.ajax({
        type: 'post',
        url: 'mdprocess/renderBpByTestCase',
        data: paramData,
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            
            if (data.hasOwnProperty('status') && data.status != 'success') {
                
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
                
            } else {
                
                var $dialogName = 'dialog-testcase-' + data.uniqId;
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $('#' + $dialogName);
                
                $dialog.empty().append(data.Html);
                
                var processForm = $dialog.find('form');
                var processUniqId = processForm.parent().attr('data-bp-uniq-id');
                var isHideBtn = (paramData.mode == 'testcaselog') ? ' d-none' : '';
                
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 1100,
                    height: "auto",
                    modal: true,
                    closeOnEscape: isCloseOnEscape,
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [{
                            text: data.run_btn,
                            class: 'btn green-meadow btn-sm bp-btn-save' + isHideBtn,
                            click: function(e) {

                                if (window['processBeforeSave_' + processUniqId]($(e.target))) {

                                    if (bpFormValidate(processForm)) {

                                        processForm.ajaxSubmit({
                                            type: 'post',
                                            url: 'mdwebservice/runProcess',
                                            dataType: 'json',
                                            beforeSubmit: function(formData, jqForm, options) {
                                                formData.push(
                                                    {name: 'isTestCase', value: 1}, 
                                                    {name: 'testCaseId', value: paramData.id}
                                                );
                                            },
                                            beforeSend: function() {
                                                Core.blockUI({message: 'Түр хүлээнэ үү', boxed: true});
                                            },
                                            success: function(responseData) {

                                                PNotify.removeAll();

                                                if (responseData.status === 'success') {
                                                    new PNotify({
                                                        title: 'Success',
                                                        text: responseData.message,
                                                        type: 'success',
                                                        insert_brs: false,
                                                        addclass: pnotifyPosition,
                                                        sticker: false
                                                    });
                                                    $dialog.dialog('close');
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
                                    } else {
                                        bpIgnoreGroupRemove(processForm);
                                    }
                                } else {
                                    bpIgnoreGroupRemove(processForm);
                                }
                            }
                        },
                        {
                            text: data.close_btn,
                            class: 'btn blue-hoki btn-sm bp-btn-close',
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
                
                if (data.dialogSize === 'fullscreen') {
                    $dialog.dialogExtend('maximize');
                }
                
                $dialog.dialog('open');
                
                Core.initBPAjax($dialog);
            }
            
            Core.unblockUI();
        }
    });
}
function renderTestCaseProcess(elem, data) {
    var $dialogName = 'dialog-testcase-' + data.uniqId;
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);

    $dialog.empty().append(data.Html);
    $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: data.Title,
        width: 1100,
        height: "auto",
        modal: true,
        closeOnEscape: isCloseOnEscape,
        close: function() {
            $dialog.empty().dialog('destroy').remove();
        },
        buttons: [{
                text: plang.get('save_btn'),
                class: 'btn green-meadow btn-sm bp-btn-save',
                click: function(e) {
                    
                    var processForm = $dialog.find('form');
                    var dialogId = 'save-bp-testcase-dialog-' + data.uniqId;
                    var $testCaseDialog = $('#' + dialogId);

                    if ($testCaseDialog.length === 0) {

                        $('<div id="' + dialogId + '"></div>').appendTo('body');
                        $testCaseDialog = $('#' + dialogId);

                        $.ajax({
                            type: 'post',
                            url: 'mdprocess/getTestCaseSaveForm',
                            beforeSend: function() {
                                Core.blockUI({message: 'Loading...', boxed: true});
                            },
                            success: function(data) {
                                $testCaseDialog.empty().append(data);
                                $testCaseDialog.dialog({
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
                                        class: 'btn green-meadow btn-sm bp-btn-save',
                                        click: function() {

                                            var $testCaseForm = $testCaseDialog.find('form');
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
                                                            {name: 'isOnlyTemplate', value: 1}
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
                                                            $testCaseDialog.dialog('destroy').remove();
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
                                            $testCaseDialog.dialog('close');
                                        }
                                    }]
                                });
                                $testCaseDialog.dialog('open');
                                Core.unblockUI();
                            },
                            error: function() { Core.unblockUI(); }
                        });
                    } else {
                        $testCaseDialog.dialog('open');
                    }

                    processForm.on('remove', function() {
                        $('#' + dialogId).dialog('destroy').remove();
                    });
                }
            },
            {
                text: plang.get('close_btn'),
                class: 'btn blue-hoki btn-sm bp-btn-close',
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

    if (data.dialogSize === 'fullscreen') {
        $dialog.dialogExtend('maximize');
    }

    $dialog.dialog('open');

    Core.initBPAjax($dialog);
}