var IS_LOAD_LOG_SCRIPT = true;

function bpLogView(elem, dvId) {
    PNotify.removeAll();
    
    $.ajax({
        type: 'post',
        url: 'mdlog/getLogData',
        data: {id: 123},
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({boxed: true, message: 'Loading...'});
        },
        success: function(response) {
            
            if (response.status == 'success') {
            
                var $dialogName = 'dialog-recordlogview';
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $('#' + $dialogName), dialogWidth = 900;
                var html = renderLogNativeView(response.data);
                
                $dialog.empty().append(html);  
                $dialog.dialog({
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: 'Log',
                    width: dialogWidth,
                    height: 'auto',
                    modal: true,
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [ 
                        {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
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
                    }
                });
                $dialog.dialog('open');
                
            } else {
                new PNotify({
                    title: response.status,
                    text: response.message,
                    type: response.status,
                    sticker: false
                });
            }
            
            Core.unblockUI();
        }, 
        error: function() { alert('Error'); Core.unblockUI(); }
    });
}
function renderRowsLogView(colCount, groupPath, data) {
    var tbl = [], firstRow = data[0];
    
    tbl.push('<div class="table-scrollable" style="border: 1px #ddd solid;">');
        tbl.push('<table class="table table-bordered table-hover">');
            tbl.push('<thead>');
                tbl.push('<tr>');

                for (var f in firstRow) {
                    tbl.push('<th class="text-center">'+f+'</th>');
                }

                tbl.push('</tr>');
            tbl.push('</thead>');
            tbl.push('<tbody>');

        for (var d in data) {

            tbl.push('<tr>');

            for (var f in firstRow) {
                var val = data[d][f], styles = '';

                if (isObject(val)) {
                    val = '<button type="button" class="btn btn-xs purple-plum">...</button>';
                    styles = ' style="width: 25px;"';
                }

                tbl.push('<td'+styles+'>'+val+'</td>');
            }

            tbl.push('</tr>');
        }

            tbl.push('</tbody>');
        tbl.push('</table>');
    tbl.push('</div>');
    
    return tbl.join('');
}
function renderRowLogView(colCount, groupPath, data) {
    var headerCount = 0, hdrControls = {}, tabs = {}, render = [], colClassName = 'col';
    
    for (var i in data) {
                    
        var firstKey;

        if (isArray(data[i]) || isObject(data[i])) {

            for (var k in data[i]) { 
                firstKey = k;
                break;
            }

            if (isNumeric(firstKey)) {
                tabs[i] = renderRowsLogView(colCount, i, data[i]);
            } else {
                tabs[i] = renderRowLogView(colCount, i, data[i]);
            }
            
            var hdrControl = '<div class="form-group row mb-1">'+
                '<label class="col-md-4 col-form-label text-right">'+i+':</label>'+
                '<div class="col-md-8 pl0 pr0"><button type="button" class="btn btn-xs purple-plum">...</button></div>'+
            '</div>';

            hdrControls[headerCount] = hdrControl;

        } else {

            var hdrControl = '<div class="form-group row mb-1">'+
                '<label class="col-md-4 col-form-label text-right">'+i+':</label>'+
                '<div class="col-md-8 pl0 pr0">'+data[i]+'</div>'+
            '</div>';

            hdrControls[headerCount] = hdrControl;
        }
        
        headerCount++;
    }
    
    var colDivide = Math.round(headerCount / colCount), checkDivide = 1;
                
    render.push('<div class="row">');

    for (var h in hdrControls) {

        if (checkDivide == 1) {
            render.push('<div class="'+colClassName+'">');
        }

        render.push(hdrControls[h]);

        if (checkDivide == colDivide || headerCount == (Number(h) + 1)) {
            render.push('</div>');
            checkDivide = 1;
        } else {
            checkDivide ++;
        }
    }

    render.push('</div>');
    
    return render.join('');
}
function renderLogNativeView(data) {
    var headerCount = 0, tabCount = 0, colCount = 2, render = [], 
        hdrControls = {}, tabs = {}, colClassName = 'col';
        
    for (var i in data) {
                    
        var firstKey;

        if (isArray(data[i]) || isObject(data[i])) {

            for (var k in data[i]) { 
                firstKey = k;
                break;
            }

            if (isNumeric(firstKey)) {
                tabs[i] = renderRowsLogView(colCount, i, data[i]);
            } else {
                tabs[i] = renderRowLogView(colCount, i, data[i]);
            }

        } else {

            var hdrControl = '<div class="form-group row mb-1">'+
                '<label class="col-md-4 col-form-label text-right">'+i+':</label>'+
                '<div class="col-md-8 pl0 pr0">'+data[i]+'</div>'+
            '</div>';

            hdrControls[headerCount] = hdrControl;

            headerCount++;
        }
    }

    //<editor-fold defaultstate="collapsed" desc="Header controls">

    var colDivide = Math.round(headerCount / colCount), checkDivide = 1;

    render.push('<div class="row">');

    for (var h in hdrControls) {

        if (checkDivide == 1) {
            render.push('<div class="'+colClassName+'">');
        }

        render.push(hdrControls[h]);

        if (checkDivide == colDivide || headerCount == (Number(h) + 1)) {
            render.push('</div>');
            checkDivide = 1;
        } else {
            checkDivide ++;
        }
    }

    render.push('</div>');

    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Detail tabs">

    if (Object.keys(tabs).length) {

        var tabItems = [], tabContents = [], tabIndex = 0;

        for (var t in tabs) {

            var activeClass = (tabIndex == 0) ? ' active' : '';

            tabItems.push('<li class="nav-item">');
                tabItems.push('<a href="#log_dtl_tab_'+tabIndex+'" class="nav-link'+activeClass+'" data-toggle="tab">'+t+'</a>');
            tabItems.push('</li>');

            tabContents.push('<div class="tab-pane'+activeClass+'" id="log_dtl_tab_'+tabIndex+'">'+tabs[t]+'</div>');

            tabIndex ++;
        }

        render.push('<div class="row">');
            render.push('<div class="col-md-12">');

                render.push('<div class="tabbable-line tabbable-tabdrop">');
                    render.push('<ul class="nav nav-tabs">');
                        render.push(tabItems.join(''));
                    render.push('</ul>');
                    render.push('<div class="tab-content">');
                        render.push(tabContents.join(''));
                    render.push('</div>');
                render.push('</div>');

            render.push('</div>');
        render.push('</div>');
    }

    //</editor-fold>
    
    return render.join('');
}

/*function bpRecordHistoryLogListInit(elem, dvId, refStructureId) {
    PNotify.removeAll();
    var selectedRows = getDataViewSelectedRows(dvId);
    
    if (selectedRows.length == 0) {
        alert(plang.get('msg_pls_list_select'));
        return;
    }
    
    $.ajax({
        type: 'post',
        url: 'mdlog/getRecordLogHistoryList',
        data: {refStructureId: refStructureId, selectedRow: selectedRows[0]},
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({boxed: true, message: 'Loading...'});
        },
        success: function(response) {
            
            if (response.status == 'success') {
                
                var render = [], list = response.data, n = 1, moreLabel = plang.get('more');
                
                render.push('<table class="table table-bordered table-hover">');
                    render.push('<thead>');
                        render.push('<tr>');
                            render.push('<th class="text-center" style="width: 20px">№</th>');
                            render.push('<th class="text-center">Үйлдэл</th>');
                            render.push('<th class="text-center">Огноо</th>');
                            render.push('<th class="text-center">Хэрэглэгч</th>');
                            render.push('<th></th>');
                        render.push('</tr>');
                    render.push('</thead>');
                    render.push('<tbody>');
                    
                    for (var i in list) {
                        render.push('<tr>');
                            render.push('<td class="text-center">'+(n++)+'</td>');
                            render.push('<td class="text-center">'+(list[i]['operationtype'] == 'UPDATE' ? '<span class="badge badge-info" style="font-size: inherit;"><i class="icon-database-edit2"></i> Зассан</span>' : '<span class="badge badge-success" style="font-size: inherit;"><i class="icon-database-add"></i> Нэмсэн</span>')+'</td>');
                            render.push('<td>'+list[i]['createddate']+'</td>');
                            render.push('<td>'+list[i]['userid']+'</td>');
                            render.push('<td class="text-center">');
                                render.push('<button type="button" class="btn btn-xs btn-light" onclick="bpRecordLogDetail(this, \''+list[i]['id']+'\');">'+moreLabel+'</button>');
                            render.push('</td>');
                        render.push('</tr>');
                    }
                
                    render.push('</tbody>');
                render.push('</table>');    
                
                var $dialogName = 'dialog-recordloglist';
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $('#' + $dialogName);
                
                $dialog.empty().append(render.join(''));  
                $dialog.dialog({
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: 'Log',
                    width: 700,
                    height: 'auto',
                    modal: true,
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [ 
                        {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open');
                            
            } else {
                new PNotify({
                    title: response.status,
                    text: response.message,
                    type: response.status,
                    sticker: false
                });
            }
            
            Core.unblockUI();
        }
    });
}*/

function bpRecordLogDetail(elem, logId, isRecoverLog, dvId) {
    
    PNotify.removeAll();
    
    dvId = (typeof dvId == 'undefined') ? '' : dvId;
    var isRemovedLog = (isObject(elem) && elem.isRemovedLog) ? true : false;
    
    $.ajax({
        type: 'post',
        url: 'mdlog/getRecordLogDetail',
        data: {logId: logId, dvId: dvId, isRemovedLog: (isRemovedLog ? 1 : 0)},
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({boxed: true, message: 'Loading...'});
        },
        success: function(response) {
            
            if (response.hasOwnProperty('errorMsg')) {
                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: response.errorMsg,
                    type: 'error',
                    sticker: false
                });
                Core.unblockUI();
                return;
            }
            
            if (response.hasOwnProperty('processId') && !response.processId) {
                response.Title = plang.get('PF_REMOVE_LOG_VIEW');
                response.dialogWidth = 900;
                response.dialogHeight = 'auto';
                response.isDialogSize = '';
                response.dialogSize = '';
                response.Html = renderLogNativeView(response.data);
            } else if (isRemovedLog) {
                response.Title = plang.get('PF_REMOVE_LOG_VIEW');
            }
            
            var $dialogName = 'dialog-logbp-' + logId;
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName), dialogWidth = response.dialogWidth, dialogHeight = response.dialogHeight;
                
            if (response.isDialogSize === 'auto') {
                dialogWidth = 1350;
                dialogHeight = 'auto';
            }
            
            dialogWidth = !dialogWidth ? 900 : dialogWidth;
            
            if (isRecoverLog) {
                var dialogButtons = [ 
                    {text: plang.get('recover_btn'), class: 'btn green-meadow btn-sm float-left', click: function () {
                            
                        var subDialogName = '#dialog-logrecover-confirm';
                        if (!$(subDialogName).length) {
                            $('<div id="' + subDialogName.replace('#', '') + '"></div>').appendTo('body');
                        }
                        var $subDialog = $(subDialogName);

                        $subDialog.html(plang.get('PF_RECOVER_LOG_APPROVE'));
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
                                        url: 'mdlog/logRecover',
                                        data: {logId: logId},
                                        dataType: 'json',
                                        beforeSend: function() {
                                            Core.blockUI({boxed: true, message: 'Loading...'});
                                        },
                                        success: function(subResponse) {
                                            PNotify.removeAll();
                                            new PNotify({
                                                title: subResponse.status,
                                                text: subResponse.message,
                                                type: subResponse.status,
                                                sticker: false
                                            });
                                            if (subResponse.status == 'success') {
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
                    }}, 
                    {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ];
            } else {
                var dialogButtons = [ 
                    {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ];
            }
            
            $dialog.empty().append(response.Html);

            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: response.Title,
                width: dialogWidth,
                height: dialogHeight,
                modal: true,
                closeOnEscape: (typeof isCloseOnEscape == 'undefined' ? true : isCloseOnEscape), 
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: dialogButtons 
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
            if (response.dialogSize === 'fullscreen' || (response.hasOwnProperty('mode') && response.mode == 'main')) {
                $dialog.dialogExtend('maximize');
            }
            $dialog.dialog('open');
            Core.initBPAjax($dialog);
            Core.unblockUI();
        }
    });
}
function bpRecordHistoryLogListInit(elem, dvId, refStructureId) {
    PNotify.removeAll();
    var selectedRows = getDataViewSelectedRows(dvId);
    
    if (selectedRows.length == 0) {
        alert(plang.get('msg_pls_list_select'));
        return;
    }
    
    $.ajax({
        type: 'post',
        url: 'mdlog/renderAddEditLogs',
        data: {dvId: dvId, refStructureId: refStructureId, selectedRow: selectedRows[0]},
        dataType: 'json', 
        beforeSend: function() {
            Core.blockUI({boxed: true, message: 'Loading...'});
        },
        success: function(response) {
            
            if (response.status == 'error') {
                new PNotify({
                    title: response.status,
                    text: response.message,
                    type: response.status,
                    sticker: false
                });
                return;
            }
            
            var $dialogName = 'dialog-logaddedit-' + dvId;
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName);
            
            $dialog.empty().append(response.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: plang.get('PF_ADD_EDIT_LOG_VIEW'),
                width: 1000,
                height: 'auto',
                modal: true,
                closeOnEscape: (typeof isCloseOnEscape == 'undefined' ? true : isCloseOnEscape), 
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [ 
                    {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
        }
    }).done(function() {
        Core.unblockUI();
    });
}
function bpRecordHistoryRemovedLogListInit(elem, dvId, refStructureId, isLogRecover) {
    $.ajax({
        type: 'post',
        url: 'mdlog/renderRemovedLogs',
        data: {dvId: dvId, refStructureId: refStructureId, islr: isLogRecover},
        beforeSend: function() {
            Core.blockUI({boxed: true, message: 'Loading...'});
        },
        success: function(response) {
            var $dialogName = 'dialog-logremoved-' + dvId;
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName);
            
            $dialog.empty().append(response);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: plang.get('PF_REMOVE_LOG_VIEW'),
                width: 1000,
                height: 'auto',
                modal: true,
                closeOnEscape: (typeof isCloseOnEscape == 'undefined' ? true : isCloseOnEscape), 
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [ 
                    {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        }
    });
}

function bpRecordRemovedLogDetail(elem, logId) {
    PNotify.removeAll();
    
    $.ajax({
        type: 'post',
        url: 'mdlog/getRecordRemovedLogDetail',
        data: {logId: logId},
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({boxed: true, message: 'Loading...'});
        },
        success: function(response) {
            if (response.status == 'error') {
                new PNotify({
                    title: response.status,
                    text: response.message,
                    type: response.status,
                    sticker: false
                });
                Core.unblockUI();
                return;
            }
            
            var data = response.data, headerCount = 0, tabCount = 0, colCount = 2, render = [], 
                hdrControls = {}, tabs = {}, colClassName = 'col';

            var $dialogName = 'dialog-recordlogview';
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName), dialogWidth = 900;

            for (var i in data) {

                var firstKey;

                if (isArray(data[i]) || isObject(data[i])) {

                    for (var k in data[i]) { 
                        firstKey = k;
                        break;
                    }

                    if (isNumeric(firstKey)) {
                        tabs[i] = renderRowsLogView(colCount, i, data[i]);
                    } else {
                        tabs[i] = renderRowLogView(colCount, i, data[i]);
                    }

                } else {

                    var hdrControl = '<div class="form-group row mb-1">'+
                        '<label class="col-md-4 col-form-label text-right">'+i+':</label>'+
                        '<div class="col-md-8 pl0 pr0">'+dvFieldValueShow(data[i])+'</div>'+
                    '</div>';

                    hdrControls[headerCount] = hdrControl;

                    headerCount++;
                }
            }

            //<editor-fold defaultstate="collapsed" desc="Header controls">

            var colDivide = Math.round(headerCount / colCount), checkDivide = 1;

            render.push('<div class="row">');

            for (var h in hdrControls) {

                if (checkDivide == 1) {
                    render.push('<div class="'+colClassName+'">');
                }

                render.push(hdrControls[h]);

                if (checkDivide == colDivide || headerCount == (Number(h) + 1)) {
                    render.push('</div>');
                    checkDivide = 1;
                } else {
                    checkDivide ++;
                }
            }

            render.push('</div>');

            //</editor-fold>

            $dialog.empty().append(render.join(''));  
            $dialog.dialog({
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: plang.get('PF_REMOVE_LOG_VIEW'),
                width: dialogWidth,
                height: 'auto',
                maxHeight: $(window).height() - 20, 
                modal: true,
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [ 
                    {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
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
                }
            });
            $dialog.dialog('open');
            Core.unblockUI();
        }
    });
}