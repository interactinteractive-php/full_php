var isMailSelectionRowsAddonScript = true;

function dataViewSendMailBySelectionRows(elem, processMetaDataId, dataViewId, postParams, getParams) {
    var $elem = $(elem);
    var $dialogName = 'dialog-send-email';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $("#" + $dialogName);
    var $basketWrap = $elem.parents('.dataViewUseBasketViewWrap');
    
    if (typeof $elem !== 'undefined' && $basketWrap.length) {

        var selectedRows = $('#objectdatagrid-' + $basketWrap.data('basketid')).datagrid('getRows');

        if (!Object.keys(selectedRows[0]).length) {
            alert(plang.get('msg_pls_list_select'));
            return;
        }
    } else {
        var selectedRows = getDataViewSelectedRows(dataViewId);
    }
                    
    var postData = {
        dataViewId: dataViewId,
        processMetaDataId: processMetaDataId,
        selectedRows: selectedRows
    };

    if (typeof postParams !== 'undefined' && postParams !== null) {
        var postParamsArr = postParams.split('&');
        var selectedRow = selectedRows[0];

        for (var i = 0; i < postParamsArr.length; i++) {
            var fieldPathArr = postParamsArr[i].split('=');
            var postParam = fieldPathArr[0];
            var inputPath = fieldPathArr[1].toLowerCase();
            var fieldValue = '';

            if (inputPath.indexOf('[') !== -1) {
                if (postParam != 'emailSubject') {
                    fieldValue = inputPath.match(/\[(.*?)\]/);
                    fieldValue = fieldValue[1];
                } else {
                    fieldValue = inputPath;
                }
            } else if (typeof selectedRow[inputPath] !== 'undefined') {
                fieldValue = selectedRow[inputPath];
            } else {
                fieldValue = fieldPathArr[1];
            }

            postData[postParam] = fieldValue;
        }
    }
    
    if (typeof getParams !== 'undefined' && getParams !== null) {
        var getParamsArr = getParams.split('&');
        var selectedRow = (isArray(selectedRows) && Object.keys(selectedRows).length) ? selectedRows[0] : {};

        for (var i = 0; i < getParamsArr.length; i++) {
            var fieldPathArr = getParamsArr[i].split('=');
            var postParam = fieldPathArr[0];
            var inputPath = fieldPathArr[1].toLowerCase();
            var fieldValue = '';

            if (inputPath.indexOf('[') !== -1) {
                fieldValue = inputPath.match(/\[(.*?)\]/);
                fieldValue = fieldValue[1];
            } else if (typeof selectedRow[inputPath] !== 'undefined') {
                fieldValue = selectedRow[inputPath];
            } else {
                fieldValue = fieldPathArr[1];
            }

            postData[postParam] = fieldValue;
        }
    }
    
    if (postData.hasOwnProperty('isOnlySingleSelect') && (postData.isOnlySingleSelect == '1' || postData.isOnlySingleSelect == 'true') && selectedRows.length > 1) {
        PNotify.removeAll();
        new PNotify({
            title: 'Info',
            text: 'Та зөвхөн нэг мөр сонгоно уу!',
            type: 'info',
            sticker: false
        });
        return false;
    }

    postData['footerSumCount'] = dvSelectedRowsSumCountJson(window['objectdatagrid_'+dataViewId], selectedRows, selectedRows.length);
    postData['ref_structure_id'] = $("input#refStructureId", "#object-value-list-" + dataViewId).val();

    $.ajax({
        type: 'post',
        url: 'mddatamodel/sendMailBySelectionRowsForm',
        data: postData,
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
            $("link[href='assets/custom/addon/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css']").remove();
            $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css"/>');            
        },
        success: function(data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 960,
                height: 'auto',
                modal: true,
                close: function() {
                    tinymce.remove('#emailBody');
                    $dialog.empty().dialog('destroy').remove();
                },
                position: {my: 'top', at: 'top+20'},
                buttons: [{
                    text: data.send_btn,
                    class: 'btn green-meadow btn-sm',
                    click: function() {

                        tinymce.triggerSave();

                        $("#dataview-mail-form").validate({ errorPlacement: function() {} });

                        if ($("#dataview-mail-form").valid()) {

                            $('#dataview-mail-form', '#' + $dialogName).ajaxSubmit({
                                type: 'post',
                                url: 'mddatamodel/sendMailBySelectionRows',
                                dataType: 'json',
                                beforeSubmit: function(formData, jqForm, options) {
                                    formData.push(
                                        {name: 'dataViewId', value: dataViewId}, 
                                        {name: 'processMetaDataId', value: processMetaDataId}, 
                                        {name: 'selectedRows', value: JSON.stringify(selectedRows)}
                                    );
                                    
                                    if (postData.hasOwnProperty('groupField') && postData.groupField) {
                                        formData.push({name: 'groupField', value: postData.groupField});
                                    }
                                },
                                beforeSend: function() {
                                    Core.blockUI({message: 'Sending...', boxed: true});
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
                                        
                                        if (postData.hasOwnProperty('isListReload') && (postData.isListReload == '1' || postData.isListReload == 'true')) {
                                            dataViewReload(dataViewId);
                                        }
                                    }
                                    Core.unblockUI();
                                }
                            });
                        }
                    }},
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
            Core.unblockUI();
        }
    }).done(function() {
        Core.initUniform($dialog);
    });
}

function mvDataViewSendMailBySelectionRows(elem, processMetaDataId, dataViewId, postParams, getParams) {
    var $elem = $(elem);
    var $dialogName = 'dialog-send-email';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $("#" + $dialogName);
    var $basketWrap = $elem.parents('.dataViewUseBasketViewWrap');
    
    if (typeof $elem !== 'undefined' && $basketWrap.length) {

        var selectedRows = $('#objectdatagrid-' + $basketWrap.data('basketid')).datagrid('getRows');

        if (!Object.keys(selectedRows[0]).length) {
            alert(plang.get('msg_pls_list_select'));
            return;
        }
    } else {
        var selectedRows = getDataViewSelectedRows(dataViewId);
    }
                    
    var postData = {
        dataViewId: dataViewId,
        processMetaDataId: processMetaDataId,
        selectedRows: selectedRows
    };
    
    if (typeof getParams !== 'undefined' && getParams !== null) {
        var getParamsArr = getParams.split('&');
        var selectedRow = (isArray(selectedRows) && Object.keys(selectedRows).length) ? selectedRows[0] : {};

        for (var i = 0; i < getParamsArr.length; i++) {
            var fieldPathArr = getParamsArr[i].split('=');
            var postParam = fieldPathArr[0];
            var inputPath = fieldPathArr[1].toLowerCase();
            var fieldValue = '';

            if (inputPath.indexOf('[') !== -1) {
                fieldValue = inputPath.match(/\[(.*?)\]/);
                fieldValue = fieldValue[1];
            } else if (typeof selectedRow[inputPath] !== 'undefined') {
                fieldValue = selectedRow[inputPath];
            } else {
                fieldValue = fieldPathArr[1];
            }

            postData[postParam] = fieldValue;
        }
    }
    
    if (postData.hasOwnProperty('isOnlySingleSelect') && (postData.isOnlySingleSelect == '1' || postData.isOnlySingleSelect == 'true') && selectedRows.length > 1) {
        PNotify.removeAll();
        new PNotify({
            title: 'Info',
            text: 'Та зөвхөн нэг мөр сонгоно уу!',
            type: 'info',
            sticker: false
        });
        return false;
    }

    postData['footerSumCount'] = dvSelectedRowsSumCountJson(window['objectdatagrid_'+dataViewId], selectedRows, selectedRows.length);
    postData['ref_structure_id'] = $("input#refStructureId", "#object-value-list-" + dataViewId).val();

    $.ajax({
        type: 'post',
        url: 'mddatamodel/sendMailBySelectionRowsMVForm',
        data: postData,
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
            $("link[href='assets/custom/addon/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css']").remove();
            $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css"/>');            
        },
        success: function(data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 960,
                height: 'auto',
                modal: true,
                close: function() {
                    tinymce.remove('#emailBody');
                    $dialog.empty().dialog('destroy').remove();
                },
                position: {my: 'top', at: 'top+20'},
                buttons: [{
                    text: data.send_btn,
                    class: 'btn green-meadow btn-sm',
                    click: function() {

                        tinymce.triggerSave();

                        $("#dataview-mail-form").validate({ errorPlacement: function() {} });

                        if ($("#dataview-mail-form").valid()) {

                            $('#dataview-mail-form', '#' + $dialogName).ajaxSubmit({
                                type: 'post',
                                url: 'mddatamodel/sendMailBySelectionRows',
                                dataType: 'json',
                                beforeSubmit: function(formData, jqForm, options) {
                                    formData.push(
                                        {name: 'dataViewId', value: dataViewId}, 
                                        {name: 'processMetaDataId', value: processMetaDataId}, 
                                        {name: 'selectedRows', value: JSON.stringify(selectedRows)}
                                    );
                                    
                                    if (postData.hasOwnProperty('groupField') && postData.groupField) {
                                        formData.push({name: 'groupField', value: postData.groupField});
                                    }
                                },
                                beforeSend: function() {
                                    Core.blockUI({message: 'Sending...', boxed: true});
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
                                        
                                        if (postData.hasOwnProperty('isListReload') && (postData.isListReload == '1' || postData.isListReload == 'true')) {
                                            dataViewReload(dataViewId);
                                        }
                                    }
                                    Core.unblockUI();
                                }
                            });
                        }
                    }},
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
            Core.unblockUI();
        }
    }).done(function() {
        Core.initUniform($dialog);
    });
}