var isMetaUpgrade = true;

function metaPHPExport(metaId) {
    Core.blockUI({
        boxed: true, 
        message: 'Exporting...' 
    });    
    $.fileDownload(URL_APP + 'mdupgrade/exportMeta', {
        httpMethod: 'POST', 
        data: {metaId: metaId} 
    }).done(function(){
        PNotify.removeAll();
        new PNotify({
            title: 'Success',
            text: 'Амжилттай татагдлаа',
            type: 'success',
            sticker: false, 
            hide: true,  
            addclass: pnotifyPosition,
            delay: 1000000000 
        });
        Core.unblockUI();
    }).fail(function (msg, url) {
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
function metaPHPImportInit() {
    var $dialogName = 'dialog-meta-phpimport';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    
    $.ajax({
        type: 'post',
        url: 'mdupgrade/importMeta',
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            PNotify.removeAll();

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
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{
                    text: plang.get('preview_btn'), class: 'btn btn-sm purple-plum', click: function () {
                        
                        PNotify.removeAll();
                        $("#newImportForm").validate({errorPlacement: function () {}});
                        
                        if ($("#newImportForm").valid()) {
                            $('#newImportForm').ajaxSubmit({
                                type: 'post',
                                url: 'mdupgrade/knowMetasInFile',
                                dataType: 'json',
                                beforeSend: function() {
                                    Core.blockUI({message: 'Түр хүлээнэ үү...', boxed: true});
                                },
                                success: function (data) {
                                    
                                    var $knowMetasInFile = $('#knowmetasinfile'), tbl = [];
                                     
                                    if (data.status == 'success') {
                                        
                                        if (Object.keys(data.metaList).length) {
                                            
                                            tbl.push('<div class="knowmetasinfile-tbl mt20" style="overflow: auto;">');
                                            tbl.push('<input type="hidden" name="isPreviewUpdateMeta" value="1">');
                                                tbl.push('<table class="table table-hover">');
                                                    tbl.push('<thead>');
                                                        tbl.push('<tr>');
                                                            tbl.push('<th class="font-weight-bold" style="width: 30px">№</th>');
                                                            tbl.push('<th style="width: 32px"><input type="checkbox" class="all-check-update-meta" checked></th>');
                                                            tbl.push('<th class="font-weight-bold">Файлын нэр</th>');
                                                            tbl.push('<th class="font-weight-bold">Мета ID</th>');
                                                            tbl.push('<th class="font-weight-bold">Мета код</th>');
                                                            tbl.push('<th class="font-weight-bold">Төрөл</th>');
                                                            tbl.push('<th class="font-weight-bold">Огноо</th>');
                                                            tbl.push('<th class="font-weight-bold">Хэрэглэгч</th>');
                                                        tbl.push('</tr>');
                                                    tbl.push('</thead>');
                                                    tbl.push('<tbody>');
                                                    
                                                    var metaList = data.metaList, n = 1;
                        
                                                    for (var i in metaList) {
                                                        
                                                        tbl.push('<tr'+(metaList[i]['isMetaCreated'] ? ' class="table-info" title="Энд байгаа мета"' : '')+'>');
                                                            tbl.push('<td>'+n+'.</td>');
                                                            tbl.push('<td><input type="checkbox" name="updateMeta[]" value="'+metaList[i]['metaId']+'" checked></td>');
                                                            tbl.push('<td>'+metaList[i]['fileName']+'</td>');
                                                            tbl.push('<td>'+metaList[i]['metaId']+'</td>');
                                                            tbl.push('<td>'+metaList[i]['metaCode']+'</td>');
                                                            tbl.push('<td>'+metaList[i]['metaType']+'</td>');
                                                            tbl.push('<td>'+metaList[i]['modifiedDate']+'</td>');
                                                            tbl.push('<td>'+metaList[i]['userName']+'</td>');
                                                        tbl.push('</tr>');
                                                        
                                                        n++;
                                                    }

                                                    tbl.push('</tbody>');
                                                tbl.push('</table>');
                                            tbl.push('</div>');
                                        }
                                        
                                    } else {
                                        new PNotify({
                                            title: data.status,
                                            text: data.message,
                                            type: data.status,
                                            sticker: false, 
                                            hide: true,  
                                            addclass: pnotifyPosition,
                                            delay: 1000000000
                                        });
                                    }
                                    
                                    $knowMetasInFile.empty().append(tbl.join('')).promise().done(function() {
                                        $('.all-check-update-meta').on('click', function() {
                                            var $this = $(this), $tbody = $this.closest('table').find('tbody');
                                            
                                            if ($this.is(':checked')) {
                                                $tbody.find('input[type="checkbox"]').prop('checked', true);
                                            } else {
                                                $tbody.find('input[type="checkbox"]').prop('checked', false);
                                            }
                                        });
                                    });
                                    
                                    $dialog.dialog('option', 'position', {my: 'center', at: 'center', of: window});

                                    Core.unblockUI();
                                }
                            });
                        }
                    }}, 
                    {text: plang.get('Уншуулах'), class: 'btn btn-sm green', click: function () {
                        PNotify.removeAll();
                        $("#newImportForm").validate({errorPlacement: function () {}});
                        
                        if ($("#newImportForm").valid()) {
                            $('#newImportForm').ajaxSubmit({
                                type: 'post',
                                url: 'mdupgrade/importMetaFile',
                                dataType: 'json',
                                beforeSend: function () {
                                    Core.blockUI({message: 'Түр хүлээнэ үү...', boxed: true});
                                },
                                success: function (data) {

                                    new PNotify({
                                        title: data.status,
                                        text: data.message,
                                        type: data.status,
                                        sticker: false, 
                                        hide: true,  
                                        addclass: pnotifyPosition,
                                        delay: 1000000000
                                    });

                                    if (data.status == 'success') {
                                        $dialog.dialog('close');
                                    } 

                                    Core.unblockUI();
                                }
                            });
                        }
                    }},
                    {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            
            Core.unblockUI();
        },
        error: function () { alert('Error'); }
    });
}
function importAnotherServer(metaId) {
    var $dialogName = 'dialog-meta-upgradeserver';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    
    $.ajax({
        type: 'post',
        url: 'mdupgrade/importAnotherServerForm',
        data: {metaId: metaId}, 
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            PNotify.removeAll();

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
                open: function () {
                    if (data.is_import_btn == false) {
                        $dialog.parent().find('.upgrade-import-btn').remove();
                    }
                },
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{
                    text: data.import_btn, class: 'btn btn-sm green upgrade-import-btn', click: function () {
                        $.ajax({
                            type: 'post',
                            url: 'mdupgrade/importAnotherServer',
                            data: {metaId: metaId}, 
                            dataType: 'json',
                            beforeSend: function () {
                                Core.blockUI({message: 'Түр хүлээнэ үү...', boxed: true});
                            },
                            success: function (data) {

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

                                if (data.status == 'success') {
                                    $dialog.dialog('close');
                                } 

                                Core.unblockUI();
                            }
                        });
                    }},
                    {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            
            Core.unblockUI();
        },
        error: function () {
            alert('Error');
        }
    });
}
function knowMetasInFileInit() {
    var $dialogName = 'dialog-knowmetasinfile';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    
    $.ajax({
        type: 'post',
        url: 'mdupgrade/importMeta',
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            PNotify.removeAll();

            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Файл доторхи метаг харах',
                width: 700,
                height: 'auto',
                modal: true,
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{
                    text: plang.get('see_btn'), class: 'btn btn-sm green', click: function () {
                        $("#newImportForm").validate({errorPlacement: function () {}});
                        
                        if ($("#newImportForm").valid()) {
                            $('#newImportForm').ajaxSubmit({
                                type: 'post',
                                url: 'mdupgrade/knowMetasInFile',
                                dataType: 'json',
                                beforeSend: function() {
                                    Core.blockUI({message: 'Түр хүлээнэ үү...', boxed: true});
                                },
                                success: function (data) {

                                    PNotify.removeAll();
                                    
                                    var $knowMetasInFile = $('#knowmetasinfile'), tbl = [];
                                     
                                    if (data.status == 'success') {
                                        
                                        if (Object.keys(data.metaList).length) {

                                            tbl.push('<div class="knowmetasinfile-tbl mt20" style="overflow: auto;">');
                                                tbl.push('<table class="table table-hover">');
                                                    tbl.push('<thead>');
                                                        tbl.push('<tr>');
                                                            tbl.push('<th class="font-weight-bold" style="width: 30px">№</th>');
                                                            tbl.push('<th class="font-weight-bold">Файлын нэр</th>');
                                                            tbl.push('<th class="font-weight-bold">Мета ID</th>');
                                                            tbl.push('<th class="font-weight-bold">Мета код</th>');
                                                            tbl.push('<th class="font-weight-bold">Төрөл</th>');
                                                        tbl.push('</tr>');
                                                    tbl.push('</thead>');
                                                    tbl.push('<tbody>');
                                                    
                                                    var metaList = data.metaList, n = 1;
                        
                                                    for (var i in metaList) {
                                                        tbl.push('<tr>');
                                                            tbl.push('<td>'+n+'.</td>');
                                                            tbl.push('<td>'+metaList[i]['fileName']+'</td>');
                                                            tbl.push('<td>'+metaList[i]['metaId']+'</td>');
                                                            tbl.push('<td>'+metaList[i]['metaCode']+'</td>');
                                                            tbl.push('<td>'+metaList[i]['metaType']+'</td>');
                                                        tbl.push('</tr>');
                                                        
                                                        n++;
                                                    }

                                                    tbl.push('</tbody>');
                                                tbl.push('</table>');
                                            tbl.push('</div>');
                                        }
                                        
                                    } else {
                                        new PNotify({
                                            title: data.status,
                                            text: data.message,
                                            type: data.status,
                                            sticker: false, 
                                            hide: true,  
                                            addclass: pnotifyPosition,
                                            delay: 1000000000
                                        });
                                    }
                                    
                                    $knowMetasInFile.empty().append(tbl.join(''));
                                    
                                    $dialog.dialog('option', 'position', {my: 'center', at: 'center', of: window});

                                    Core.unblockUI();
                                }
                            });
                        }
                    }},
                    {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            
            Core.unblockUI();
        },
        error: function () { alert('Error'); }
    });
}
function metaSendTo(metaId) {
    PNotify.removeAll();
    
    var $dialogName = 'dialog-metasendto';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName), tbl = [], domains = getConfigValue('metaSendToDomains');
    
    tbl.push('<div class="metasendtodomain-tbl" style="overflow: auto;">');
        tbl.push('<table class="table table-hover">');
            tbl.push('<thead>');
                tbl.push('<tr>');
                    tbl.push('<th class="font-weight-bold" style="width: 30px"><input type="checkbox" class="notuniform metasendto-check-all"></th>');
                    tbl.push('<th class="font-weight-bold" style="width: 270px">Domain</th>');
                    tbl.push('<th class="font-weight-bold">Status</th>');
                tbl.push('</tr>');
            tbl.push('</thead>');
            tbl.push('<tbody>');

            var domainList = domains.split(',');

            for (var i in domainList) {
                tbl.push('<tr>');
                    tbl.push('<td><input type="checkbox" class="notuniform" value="'+domainList[i]+'" data-domain="'+domainList[i]+'"></td>');
                    tbl.push('<td class="font-size-14">'+domainList[i]+'</td>');
                    tbl.push('<td data-status="1"></td>');
                tbl.push('</tr>');
            }

            tbl.push('</tbody>');
        tbl.push('</table>');
    tbl.push('</div>');

    $dialog.empty().append(tbl.join(''));
    $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'Send to',
        width: 800,
        height: 'auto',
        modal: true,
        close: function () {
            $dialog.empty().dialog('destroy').remove();
        },
        buttons: [{
            text: plang.get('send_btn'), class: 'btn btn-sm green', click: function () {
                
                PNotify.removeAll();
                var $checkedDomains = $dialog.find('.metasendtodomain-tbl tbody input[type="checkbox"]:checked');
                
                if ($checkedDomains.length) {
                    
                    Core.blockUI({message: 'Loading...', boxed: true});
                    var domainIds = '-';
                    
                    $checkedDomains.each(function() {
                        domainIds += $(this).val() + '-';
                    });

                    metaSendToRunLoop(domainIds, metaId, bpGetUid());
                    
                } else {
                    new PNotify({
                        title: 'Info',
                        type: 'info',
                        text: 'Domain-с сонголт хийнэ үү!',
                        sticker: false, 
                        hide: true,  
                        addclass: pnotifyPosition
                    });
                }
            }},
            {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
                $dialog.dialog('close');
            }}
        ]
    });
    $dialog.dialog('open');
    
    $dialog.on('click', '.metasendto-check-all', function() {
        var $this = $(this), $tbody = $this.closest('table').find('tbody');
        
        if ($this.is(':checked')) {
            $tbody.find('input[type="checkbox"]').prop('checked', true);
        } else {
            $tbody.find('input[type="checkbox"]').prop('checked', false);
        }
    });
}

function metaSendToRunLoop(domainIds, metaId, fileId) {
    var domainIdsMatches = domainIds.match(/\-(.*?)\-/);

    if (domainIdsMatches) {
        var domain = domainIdsMatches[1];
        
        if (domain) {
            var $statusCell = $('body').find('.metasendtodomain-tbl tbody input[data-domain="'+domain+'"]').closest('tr').find('[data-status="1"]');
            $statusCell.html('<i class="icon-spinner4 spinner-sm mr-1"></i>');
            
            $.ajax({
                type: 'post',
                url: 'mdupgrade/metaSendToRunLoop',
                data: {domain: domain, metaId: metaId, fileId: fileId},
                dataType: 'json',
                success: function(data) {
                    
                    if (data) {
                        if (data.status == 'success') {
                            $statusCell.html('<span class="badge badge-success font-size-12">Success</span>');
                        } else {
                            $statusCell.html('<span class="badge badge-warning font-size-12">'+data.message+'</span>');
                        }
                    } else {
                        $statusCell.html('<span class="badge badge-warning font-size-12">Unkhown error!</span>');
                    }
                    
                    metaSendToRunLoop(domainIds.replace('-' + domain, ''), metaId, fileId);
                }
            });
        }
    } else {
        Core.unblockUI();
    }
}
function downloadBugFixingInit(elem, processMetaDataId, dataViewId, selectedRows) {
    
    if (selectedRows.length == 0) {
        alert(plang.get('msg_pls_list_select'));
        return;
    }
    
    Core.blockUI({boxed: true, message: 'Downloading...'}); 
    
    var bugfixIds = '';
    for (var i in selectedRows) { 
        bugfixIds += selectedRows[i]['id'] + ',';
    }
    
    $.fileDownload(URL_APP + 'mdupgrade/clientDownloadBugFixing', {
        httpMethod: 'POST', 
        data: {bugfixIds: bugfixIds}
    }).done(function(){
        PNotify.removeAll();
        new PNotify({
            title: 'Success',
            text: 'Амжилттай татагдлаа',
            type: 'success',
            sticker: false, 
            hide: true,  
            addclass: pnotifyPosition,
            delay: 1000000000 
        });
        Core.unblockUI();
    }).fail(function (msg, url) {
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

function metaExportFromList(elem, processMetaDataId, dataViewId, postParams) {
    
    PNotify.removeAll();
    
    var postParams = qryStrToObj(postParams.toLowerCase());
    
    if (postParams.hasOwnProperty('metaid')) {
        
        var metaId = postParams.metaid, rows = getDataViewSelectedRows(dataViewId), firstRow = rows[0];
        
        if (firstRow.hasOwnProperty(metaId)) {
            var metaIds = [];
            
            for (var i in rows) {
                var row = rows[i];
                metaIds.push(row[metaId]);
            }
            
            metaPHPExport(metaIds);
        }
        
    } else {
        new PNotify({
            title: 'Error',
            text: 'Invalid metaid!',
            type: 'error',
            sticker: false, 
            hide: true,  
            addclass: pnotifyPosition
        });
    }
    
    return;
}
function metaPatchRollback(elem, processMetaDataId, dataViewId, selectedRow) {
    PNotify.removeAll();
    
    if (typeof selectedRow != 'undefined') {
        
        var dialogName = '#dialog-rollback-confirm';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        var $dialog = $(dialogName);

        $dialog.html(plang.get('Та итгэлтэй байна уу?'));
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
                    
                    $.ajax({
                        type: 'post',
                        url: 'mdupgrade/metaPatchRollback',
                        data: {id: selectedRow.id}, 
                        dataType: 'json',
                        beforeSend: function () {
                            Core.blockUI({message: 'Loading...', boxed: true});
                        },
                        success: function (data) {
                            new PNotify({
                                title: data.status,
                                text: data.message,
                                type: data.status,
                                sticker: false, 
                                hide: true,  
                                addclass: pnotifyPosition
                            });
                            Core.unblockUI();
                        }
                    });

                }},
                {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                    $dialog.dialog('close');
                }}
            ]
        });
        $dialog.dialog('open');
                    
    } else {
        new PNotify({
            title: 'Error',
            text: plang.get('msg_pls_list_select'),
            type: 'error',
            sticker: false, 
            hide: true,  
            addclass: pnotifyPosition
        });
    }
}
function runMetaPatchImport(id) {
    
    $.ajax({
        type: 'post',
        url: 'mdupgrade/getBugFixingKnowledge',
        data: {bugfixId: id}, 
        dataType: 'json', 
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {

            if (data.status == 'success') {
                
                var confirmWidth = 300, html = [], isKnowledge = false;
                
                if (data.knowledge != '') {
                    
                    confirmWidth = 950;
                    isKnowledge = true;
                    
                    html.push('<div style="max-height: '+($(window).height() - 250)+'px; overflow: auto">');
                        html.push(html_entity_decode(data.knowledge, 'ENT_QUOTES'));
                    html.push('</div>');
                    html.push('<hr />');
                    html.push('<h2>' + plang.get('Та дээрхи мэдээлэлтэй танилцсан бол Тийм дарж үргэлжлүүлнэ үү?') + '</h2>');
                    
                } else {
                    html.push(plang.get('Та итгэлтэй байна уу?'));
                }
                
                var dialogName = '#dialog-metapatchimport-confirm';
                if (!$(dialogName).length) {
                    $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                }
                var $dialog = $(dialogName);

                $dialog.html(html.join(''));
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: plang.get('msg_title_confirm'), 
                    width: confirmWidth, 
                    height: 'auto',
                    modal: true,
                    buttons: [
                        {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function() {

                            $dialog.dialog('close');

                            $.ajax({
                                type: 'post',
                                url: 'mdupgrade/metaPatchImport',
                                data: {id: id}, 
                                dataType: 'json',
                                beforeSend: function () {
                                    Core.blockUI({message: 'Loading...', boxed: true});
                                },
                                success: function (data) {
                                    new PNotify({
                                        title: data.status,
                                        text: data.message,
                                        type: data.status,
                                        sticker: false, 
                                        hide: true,  
                                        addclass: pnotifyPosition
                                    });
                                    Core.unblockUI();

                                    if (data.hasOwnProperty('logs') && data.logs != '' && data.logs != null) {
                                        metaPatchImportLogs(data.logs);
                                    }
                                }
                            });

                        }},
                        {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                
                if (isKnowledge) {
                    $dialog.dialogExtend({ 
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
                }
                
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
            
            Core.unblockUI();
        }, 
        error: function () { alert('Error'); Core.unblockUI(); }
    });
}
function metaPatchImport(elem, processMetaDataId, dataViewId, selectedRow) {
    PNotify.removeAll();

    if (typeof selectedRow != 'undefined') {
        
        var fncArguments = [selectedRow.id];
        checkUrlAuthLoginByFnc('runMetaPatchImport', fncArguments);
                    
    } else {
        new PNotify({
            title: 'Error',
            text: plang.get('msg_pls_list_select'),
            type: 'error',
            sticker: false, 
            hide: true,  
            addclass: pnotifyPosition
        });
    }
}
function metaImportCopyInit(folderId) {
    var $dialogName = 'dialog-meta-phpimport-copy';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    
    $.ajax({
        type: 'post',
        url: 'mdupgrade/metaImportCopy',
        data: {folderId: folderId}, 
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            PNotify.removeAll();

            $dialog.empty().append(data);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Copy meta',
                width: 950,
                height: 'auto',
                modal: true,
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{ 
                    text: plang.get('Уншуулах'), class: 'btn btn-sm green', click: function () {
                        PNotify.removeAll();
                        $("#newCopyImportForm").validate({errorPlacement: function () {}});
                        
                        if ($("#newCopyImportForm").valid()) {
                            $('#newCopyImportForm').ajaxSubmit({
                                type: 'post',
                                url: 'mdupgrade/metaImportCopyFile',
                                dataType: 'json',
                                beforeSend: function () {
                                    Core.blockUI({message: 'Loading...', boxed: true});
                                },
                                success: function (data) {

                                    new PNotify({
                                        title: data.status,
                                        text: data.message,
                                        type: data.status,
                                        sticker: false, 
                                        hide: true,  
                                        addclass: pnotifyPosition,
                                        delay: 1000000000
                                    });

                                    if (data.status == 'success') {
                                        
                                        $dialog.dialog('close');
                                        
                                        if (data.hasOwnProperty('folderId') && data.folderId) {
                                            refreshList(data.folderId, 'folder');
                                        } else {
                                            metaDataDefault();
                                        }
                                    } 

                                    Core.unblockUI();
                                }
                            });
                        }
                    }},
                    {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            
            Core.unblockUI();
        },
        error: function () { alert('Error'); }
    });
}
function metaPatchImportLogs(logs){
    var $dialogName = 'dialog-metaimport-logs';
    if (!$('#' + $dialogName).length) {
        $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
    } 
    var $dialog = $('#' + $dialogName), html = [], windowHeight = $(window).height();
    
    html.push('<iframe border="0" frameborder="0" style="width: 100%; height: '+(windowHeight - 120)+'px"></iframe>');
    
    $dialog.empty().append(html.join(''));
    $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'Logs',
        width: 1100,
        height: windowHeight - 20,
        modal: true,
        closeOnEscape: isCloseOnEscape,
        open: function() {
            var iframeLogs = $dialog.find('iframe')[0].contentDocument;
            iframeLogs.open();
            iframeLogs.write(logs);
            iframeLogs.close();
        },
        close: function() {
            $dialog.empty().dialog('destroy').remove();
        },
        buttons: [{
            text: plang.get('close_btn'),
            class: 'btn blue-madison btn-sm bp-btn-close',
            click: function() {
                $dialog.dialog('close');
            }
        }]
    });
    $dialog.dialog('open');
}
function metaPatchViewScript(elem, processMetaDataId, dataViewId, selectedRow) {
    PNotify.removeAll();

    if (typeof selectedRow != 'undefined') {
        
        $.ajax({
            type: 'post',
            url: 'mdupgrade/getBugFixingScript',
            data: {bugfixId: selectedRow.id}, 
            dataType: 'json', 
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function (data) {
                
                if (data.status == 'success') {
                    
                    if (data.script != '') {
                        
                        var $dialogName = 'dialog-metapatchviewscript';
                        if (!$("#" + $dialogName).length) {
                            $('<div id="' + $dialogName + '"></div>').appendTo('body');
                        }
                        var $dialog = $('#' + $dialogName), form = [], script = (data.script).replace(/♠/g, ';');

                        form.push('<div class="row xs-form">');
                            form.push('<div class="col-md-12">');
                                form.push('<textarea class="form-control form-control-sm" rows="30" readonly>'+script+'</textarea>');
                            form.push('</div>');
                        form.push('</div>');

                        $dialog.empty().append(form.join(''));
                        $dialog.dialog({
                            cache: false,
                            resizable: true,
                            bgiframe: true,
                            autoOpen: false,
                            title: 'Script',
                            width: 800,
                            height: 'auto',
                            modal: true,
                            close: function () {
                                $dialog.empty().dialog('destroy').remove();
                            },
                            buttons: [{
                                text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
                                    $dialog.dialog('close');
                                }
                            }]
                        });
                        $dialog.dialog('open');
                        
                    } else {
                        new PNotify({
                            title: 'Info',
                            text: 'Script хоосон байна!',
                            type: 'info',
                            sticker: false, 
                            hide: true,  
                            addclass: pnotifyPosition
                        });
                    }
                    
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
                
                Core.unblockUI();
            }
        });
                    
    } else {
        new PNotify({
            title: 'Error',
            text: plang.get('msg_pls_list_select'),
            type: 'error',
            sticker: false, 
            hide: true,  
            addclass: pnotifyPosition
        });
    }
}
function metaCopyReplace(metaId, folderId) {
    var $dialogName = 'dialog-meta-copyreplace';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    
    $.ajax({
        type: 'post',
        url: 'mdupgrade/metaCopyReplaceForm',
        data: {metaId: metaId, folderId: folderId}, 
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            PNotify.removeAll();

            $dialog.empty().append(data);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Copy & Replace meta',
                width: 800,
                height: 'auto',
                modal: true,
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{ 
                    text: plang.get('save_btn'), class: 'btn btn-sm green', click: function () {
                        PNotify.removeAll();
                        
                        var $form = $('#newCopyReplaceForm');
                        $form.validate({errorPlacement: function () {}});
                        
                        if ($form.valid()) {
                            
                            var confirmDialogName = '#dialog-rollback-confirm';
                            if (!$(confirmDialogName).length) {
                                $('<div id="' + confirmDialogName.replace('#', '') + '"></div>').appendTo('body');
                            }
                            var $confirmDialog = $(confirmDialogName);

                            $confirmDialog.html(plang.get('Та итгэлтэй байна уу?'));
                            $confirmDialog.dialog({
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

                                        $confirmDialog.dialog('close');
                                        
                                        $form.ajaxSubmit({
                                            type: 'post',
                                            url: 'mdupgrade/metaCopyReplace',
                                            dataType: 'json',
                                            beforeSubmit: function (formData, jqForm, options) {
                                                formData.push({name: 'copyMetaDataId', value: metaId});
                                            },
                                            beforeSend: function () {
                                                Core.blockUI({message: 'Loading...', boxed: true});
                                            },
                                            success: function (data) {

                                                new PNotify({ 
                                                    title: data.status, 
                                                    text: data.message, 
                                                    type: data.status, 
                                                    sticker: false, 
                                                    hide: true, 
                                                    addclass: pnotifyPosition,
                                                    delay: 1000000000
                                                });

                                                if (data.status == 'success') {

                                                    $dialog.dialog('close');

                                                    if (data.hasOwnProperty('folderId') && data.folderId) {
                                                        refreshList(data.folderId, 'folder');
                                                    } else {
                                                        metaDataDefault();
                                                    }
                                                } 

                                                Core.unblockUI();
                                            }
                                        });
                                    }},
                                    {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                        $confirmDialog.dialog('close');
                                    }}
                                ]
                            });
                            $confirmDialog.dialog('open');
                        }
                    }},
                    {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            
            Core.unblockUI();
        },
        error: function () { alert('Error'); }
    });
}
function metaReplace(metaId, folderId) {
    var $dialogName = 'dialog-meta-copyreplace';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    
    $.ajax({
        type: 'post',
        url: 'mdupgrade/metaReplaceForm',
        data: {metaId: metaId, folderId: folderId}, 
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            PNotify.removeAll();

            $dialog.empty().append(data);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Copy & Replace meta',
                width: 800,
                height: 'auto',
                modal: true,
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [{ 
                    text: plang.get('save_btn'), class: 'btn btn-sm green', click: function () {
                        PNotify.removeAll();
                        
                        var $form = $('#newReplaceForm');
                        $form.validate({errorPlacement: function () {}});
                        
                        if ($form.valid()) {
                            
                            var confirmDialogName = '#dialog-rollback-confirm';
                            if (!$(confirmDialogName).length) {
                                $('<div id="' + confirmDialogName.replace('#', '') + '"></div>').appendTo('body');
                            }
                            var $confirmDialog = $(confirmDialogName);

                            $confirmDialog.html(plang.get('Та итгэлтэй байна уу?'));
                            $confirmDialog.dialog({
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

                                        $confirmDialog.dialog('close');
                                        
                                        $form.ajaxSubmit({
                                            type: 'post',
                                            url: 'mdupgrade/metaReplace',
                                            dataType: 'json',
                                            beforeSubmit: function (formData, jqForm, options) {
                                                formData.push({name: 'oldMetaDataId', value: metaId});
                                            },
                                            beforeSend: function () {
                                                Core.blockUI({message: 'Loading...', boxed: true});
                                            },
                                            success: function (data) {

                                                new PNotify({ 
                                                    title: data.status, 
                                                    text: data.message, 
                                                    type: data.status, 
                                                    sticker: false, 
                                                    hide: true, 
                                                    addclass: pnotifyPosition,
                                                    delay: 1000000000
                                                });

                                                if (data.status == 'success') {
                                                    $dialog.dialog('close');
                                                } 

                                                Core.unblockUI();
                                            }
                                        });
                                    }},
                                    {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                        $confirmDialog.dialog('close');
                                    }}
                                ]
                            });
                            $confirmDialog.dialog('open');
                        }
                    }},
                    {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            
            Core.unblockUI();
        },
        error: function () { alert('Error'); }
    });
}
function metaPatchToCloudApps(elem, processMetaDataId, dataViewId) {
    PNotify.removeAll();
    var selectedRows = getDataViewSelectedRows(dataViewId);
    
    if (selectedRows.length == 0) {
        new PNotify({ 
            title: 'Info', 
            text: 'Мөрөө сонгоно уу!', 
            type: 'info', 
            sticker: false, 
            hide: true, 
            addclass: 'pnotify-center'
        });
        return;
    }
    
    $.ajax({
        type: 'post',
        url: 'mdupgrade/getCloudPatchList',
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            
            if (data.status == 'success') {
                
                var $dialogName = 'dialog-patchtocloudapps';
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $('#' + $dialogName), tbl = [], patchList = data.data;
                
                tbl.push('<div class="row">');
                    tbl.push('<div class="col-md-2 text-right pt4 pr-0">');
                        tbl.push('<label><span class="required">*</span>Patch сонгох<span class="label-colon">:</span></label>');
                    tbl.push('</div>');
                    tbl.push('<div class="col-md-7">');
                        
                        tbl.push('<select class="form-control select2">');
                            tbl.push('<option value="">- Сонгох -</option>');
                            for (var p in patchList) {
                                tbl.push('<option value="'+patchList[p]['ID']+'">'+patchList[p]['DESCRIPTION']+'</option>');
                            }
                        tbl.push('</select>');
                        
                    tbl.push('</div>');
                    tbl.push('<div class="col-md-3">');
                        tbl.push('<button type="button" class="btn btn-xs btn-primary" onclick="importPatchToCloudApps(this);"><i class="far fa-upload"></i> Оруулах</button>');
                    tbl.push('</div>');
                tbl.push('</div>');

                tbl.push('<div class="knowmetasinfile-tbl mt20" style="overflow: auto;">');
                    tbl.push('<table class="table table-hover">');
                        tbl.push('<thead>');
                            tbl.push('<tr>');
                                tbl.push('<th class="font-weight-bold" style="width: 30px">№</th>');
                                tbl.push('<th class="font-weight-bold">Харилцагчийн нэр</th>');
                                tbl.push('<th class="font-weight-bold">Домэйн нэр</th>');
                                tbl.push('<th class="font-weight-bold">Төлөв</th>');
                            tbl.push('</tr>');
                        tbl.push('</thead>');
                        tbl.push('<tbody>');

                        var n = 1;

                        for (var s in selectedRows) {

                            tbl.push('<tr>');
                                tbl.push('<td>'+n+'.</td>');;
                                tbl.push('<td data-id="'+selectedRows[s]['customerid']+'">'+selectedRows[s]['customername']+'</td>');
                                tbl.push('<td data-col="domain">'+selectedRows[s]['domainname']+'</td>');
                                tbl.push('<td data-col="status"></td>');
                            tbl.push('</tr>');

                            n++;
                        }

                        tbl.push('</tbody>');
                    tbl.push('</table>');
                tbl.push('</div>');

                $dialog.empty().append(tbl.join(''));
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: 'Patch to Cloud Apps',
                    width: 800,
                    height: 'auto',
                    modal: true,
                    position: {my: 'top', at: 'top+30'}, 
                    open: function () {
                        Core.initSelect2($dialog);
                    },
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [{
                        text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
                            $dialog.dialog('close');
                        }
                    }]
                });
                $dialog.dialog('open');
                
            } else {
                new PNotify({ 
                    title: data.status, 
                    text: data.message, 
                    type: data.status, 
                    sticker: false, 
                    hide: true, 
                    addclass: 'pnotify-center'
                });
            }
            
            Core.unblockUI();
        }
    });
}
function importPatchToCloudApps(elem) {
    PNotify.removeAll();
    
    var $parent = $(elem).closest('.ui-dialog-content');
    var $patchCombo = $parent.find('select');
    var patchId = $patchCombo.val();
    
    if (patchId != '') {
        $patchCombo.removeClass('error');
        
        var $domainRows = $parent.find('table > tbody > tr'), 
            $selected = $patchCombo.find('option:selected'), 
            patchName = $selected.text();
        
        $.ajax({
            type: 'post',
            url: 'mdupgrade/installCloudPatchDownload', 
            data: {patchId: patchId}, 
            dataType: 'json', 
            beforeSend: function () {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function (data) {
                
                Core.unblockUI();
                
                if (data.status == 'success') {
                    var fileId = data.fileId;
                    
                    $domainRows.each(function() {
                        var $thisRow = $(this);
                        var customerId = $thisRow.find('td[data-id]').attr('data-id');
                        var domain = $thisRow.find('td[data-col="domain"]').text();
                        var $statusCell = $thisRow.find('td[data-col="status"]');
                        
                        $.ajax({
                            type: 'post',
                            url: 'mdupgrade/installCloudPatchImport', 
                            data: {customerId: customerId, domain: domain, patchId: patchId, fileId: fileId, patchName: patchName}, 
                            dataType: 'json', 
                            beforeSend: function () {
                                $statusCell.html('<i class="icon-spinner4 spinner-sm mr-1"></i>');
                            },
                            success: function (dataSub) {
                                
                                if (dataSub) {
                                    if (dataSub.status == 'success') {
                                        $statusCell.html('<span class="badge bg-success font-size-12">Success</span>');
                                    } else {
                                        $statusCell.html('<span class="badge badge-danger font-size-12">'+dataSub.message+'</span>');
                                    }
                                } else {
                                    $statusCell.html('<span class="badge badge-warning font-size-12">Unkhown error!</span>');
                                }
                            }
                        });
                    });
                    
                } else {
                    new PNotify({ 
                        title: data.status, 
                        text: data.message, 
                        type: data.status, 
                        sticker: false, 
                        hide: true, 
                        addclass: 'pnotify-center'
                    });
                }
            }
        });
        
    } else {
        $patchCombo.addClass('error');
    }
}
function metaPatchToCloudAppDbs(elem, processMetaDataId, dataViewId) {
    PNotify.removeAll();
    var selectedRows = getDataViewSelectedRows(dataViewId);
    
    if (selectedRows.length == 0) {
        new PNotify({ 
            title: 'Info', 
            text: 'Мөрөө сонгоно уу!', 
            type: 'info', 
            sticker: false, 
            hide: true, 
            addclass: 'pnotify-center'
        });
        return;
    }
    
    $.ajax({
        type: 'post',
        url: 'mdupgrade/getCloudPatchList',
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            
            if (data.status == 'success') {
                
                var $dialogName = 'dialog-patchtocloudapps';
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $('#' + $dialogName), tbl = [], patchList = data.data;
                
                tbl.push('<div class="row">');
                    tbl.push('<div class="col-md-2 text-right pt4 pr-0">');
                        tbl.push('<label><span class="required">*</span>Patch сонгох<span class="label-colon">:</span></label>');
                    tbl.push('</div>');
                    tbl.push('<div class="col-md-7">');
                        
                        tbl.push('<select class="form-control select2">');
                            tbl.push('<option value="">- Сонгох -</option>');
                            for (var p in patchList) {
                                tbl.push('<option value="'+patchList[p]['ID']+'">'+patchList[p]['DESCRIPTION']+'</option>');
                            }
                        tbl.push('</select>');
                        
                    tbl.push('</div>');
                    tbl.push('<div class="col-md-3">');
                        tbl.push('<button type="button" class="btn btn-xs btn-primary" onclick="importPatchToCloudAppDbs(this);"><i class="far fa-upload"></i> Оруулах</button>');
                    tbl.push('</div>');
                tbl.push('</div>');

                tbl.push('<div class="knowmetasinfile-tbl mt20" style="overflow: auto;">');
                    tbl.push('<table class="table table-hover">');
                        tbl.push('<thead>');
                            tbl.push('<tr>');
                                tbl.push('<th class="font-weight-bold" style="width: 30px">№</th>');
                                tbl.push('<th class="font-weight-bold">Харилцагчийн нэр</th>');
                                tbl.push('<th class="font-weight-bold">Төлөв</th>');
                            tbl.push('</tr>');
                        tbl.push('</thead>');
                        tbl.push('<tbody>');

                        var n = 1;

                        for (var s in selectedRows) {

                            tbl.push('<tr>');
                                tbl.push('<td>'+n+'.</td>');;
                                tbl.push('<td data-id="'+selectedRows[s]['customerid']+'">'+selectedRows[s]['customername']+'</td>');
                                tbl.push('<td data-col="status"></td>');
                            tbl.push('</tr>');

                            n++;
                        }

                        tbl.push('</tbody>');
                    tbl.push('</table>');
                tbl.push('</div>');

                $dialog.empty().append(tbl.join(''));
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: 'Patch to Cloud Apps',
                    width: 800,
                    height: 'auto',
                    modal: true,
                    position: {my: 'top', at: 'top+30'}, 
                    open: function () {
                        Core.initSelect2($dialog);
                    },
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [{
                        text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
                            $dialog.dialog('close');
                        }
                    }]
                });
                $dialog.dialog('open');
                
            } else {
                new PNotify({ 
                    title: data.status, 
                    text: data.message, 
                    type: data.status, 
                    sticker: false, 
                    hide: true, 
                    addclass: 'pnotify-center'
                });
            }
            
            Core.unblockUI();
        }
    });
}
function importPatchToCloudAppDbs(elem) {
    PNotify.removeAll();
    
    var $parent = $(elem).closest('.ui-dialog-content');
    var $patchCombo = $parent.find('select');
    var patchId = $patchCombo.val();
    
    if (patchId != '') {
        $patchCombo.removeClass('error');
        
        var $domainRows = $parent.find('table > tbody > tr'), 
            $selected = $patchCombo.find('option:selected'), 
            patchName = $selected.text();
        
        $.ajax({
            type: 'post',
            url: 'mdupgrade/installCloudPatchDownload', 
            data: {patchId: patchId}, 
            dataType: 'json', 
            beforeSend: function () {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function (data) {
                
                Core.unblockUI();
                
                if (data.status == 'success') {
                    var fileId = data.fileId;
                    
                    $domainRows.each(function() {
                        var $thisRow = $(this);
                        var customerId = $thisRow.find('td[data-id]').attr('data-id');
                        var $statusCell = $thisRow.find('td[data-col="status"]');
                        
                        $.ajax({
                            type: 'post',
                            url: 'mdupgrade/installCloudPatchDbImport', 
                            data: {customerId: customerId, patchId: patchId, fileId: fileId, patchName: patchName}, 
                            dataType: 'json', 
                            beforeSend: function () {
                                $statusCell.html('<i class="icon-spinner4 spinner-sm mr-1"></i>');
                            },
                            success: function (dataSub) {
                                
                                if (dataSub) {
                                    if (dataSub.status == 'success') {
                                        $statusCell.html('<span class="badge bg-success font-size-12">Success</span>');
                                    } else {
                                        $statusCell.html('<span class="badge badge-danger font-size-12">'+dataSub.message+'</span>');
                                    }
                                } else {
                                    $statusCell.html('<span class="badge badge-warning font-size-12">Unkhown error!</span>');
                                }
                            }, 
                            error: function (jqXHR, exception) {
                                var jsonValue = JSON.parse(jqXHR.responseText);
                                $statusCell.html('<span class="badge badge-danger font-size-12">'+jsonValue.message+'</span>');
                            }
                        });
                    });
                    
                } else {
                    new PNotify({ 
                        title: data.status, 
                        text: data.message, 
                        type: data.status, 
                        sticker: false, 
                        hide: true, 
                        addclass: 'pnotify-center'
                    });
                }
            }
        });
        
    } else {
        $patchCombo.addClass('error');
    }
}