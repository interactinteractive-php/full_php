<div class="row">
    <div class="col-md-auto imp-file-left-part">
        
        <div class="input-group">
            <div class="input-group-prepend">
                <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown"><i class="far fa-download"></i></button>
                <div class="dropdown-menu">
                    <a href="javascript:;" class="dropdown-item" onclick="mvImportManageDownloadExcelTemplate(this, '<?php echo $this->mainIndicatorId; ?>');"><i class="far fa-file-excel"></i> Эксель загвар татах</a>
                </div>
            </div>
            <button 
                type="button" 
                class="btn btn-block green-meadow" 
                style="position: relative;-ms-flex: 1 1 auto;flex: 1 1 auto;width: 1%;margin-bottom: 0;" 
                onclick="createMvStructureFromFile(this, '', {isContextMenu: false, isImportManage: true, mainIndicatorId: '<?php echo $this->mainIndicatorId; ?>'});">
                <i class="fa fa-plus"></i> Импорт хийх
            </button>
        </div>
        
        <div class="tabbable-line bp-tabs mt-3">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#mv-imp-file-tab1" class="nav-link active" data-toggle="tab">Файл</a>
                </li>
                <li class="nav-item">
                    <a href="#mv-imp-file-tab2" class="nav-link" data-toggle="tab">Шүүлт</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane mv-imp-file-list active" id="mv-imp-file-tab1">
                    <?php echo $this->renderChildDataSets; ?>
                </div>
                <div class="tab-pane mv-imp-file-filter" id="mv-imp-file-tab2">
                    
                </div>
            </div>
        </div>
        
    </div>
    <div class="col overflow-auto ml-1" id="mv-imp-imported-data">
        
    </div>
</div>

<style type="text/css">
.imp-file-left-part {
    width: 300px;
    padding-top: 10px;
    background-color: #f2f2f2;
    border: 1px #ebeef2 solid;
}
.imp-file-left-part a.nav-link, 
.imp-file-left-part a.nav-link.active {
    background-color: transparent;
}
.imp-file-left-part .tabbable-line>.tab-content {
    background-color: transparent;
}
.imp-file-left-part .mv-imp-file-list {
    height: 70vh;
    overflow: auto;
}
.imp-file-left-part .imp-file-item {
    background-color: #fff;
    border: 1px #dadee2 solid;
    padding: 10px;
    margin-top: 10px;
    margin-bottom: 10px;
    border-radius: 6px;
    box-shadow: 0 1px 2px rgba(0,0,0,.05);
    cursor: pointer;
}
.imp-file-left-part .imp-file-item:first-child {
    margin-top: 0;
}
.imp-file-left-part .imp-file-item.selected {
    border: 1px #a7aaad solid;
    background-color: #afe4fb;
}
.imp-file-left-part .imp-file-item .imp-file-item-name {
    font-weight: bold;
}
.imp-file-left-part .imp-file-item .imp-file-item-date {
    margin-top: 10px;
    color: #999;
}
</style>

<script type="text/javascript">
var dataViewId_<?php echo $this->mainIndicatorId; ?> = '<?php echo issetParam($this->dataViewId); ?>';

$(function() {
    $('.mv-imp-file-list').on('click', '.imp-file-item', function() {
        var $this = $(this), $parent = $this.closest('.mv-imp-file-list'), 
            indicatorId = $this.attr('data-id');
        
        $parent.find('.imp-file-item.selected').removeClass('selected');
        $this.addClass('selected');
        
        $.ajax({
            type: 'post',
            url: 'mdform/indicatorImportList',
            data: {indicatorId: indicatorId, mainIndicatorId: '<?php echo $this->mainIndicatorId; ?>'}, 
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                $('.mv-imp-file-filter').empty();
                $('#mv-imp-imported-data').empty().append(data + '<div class="clearfix"/>').attr('data-indicator-id', indicatorId);
            }
        }).done(function() {
            Core.unblockUI();
        });
    });
    
    $('#mv-imp-imported-data').on('change', 'select[data-match-field]', function() {
        var $this = $(this), $parent = $this.closest('#mv-imp-imported-data'), 
            indicatorId = $parent.attr('data-indicator-id');
        $.ajax({
            type: 'post',
            url: 'mdform/importManageChangeColumn',
            data: {
                indicatorId: indicatorId, 
                mainIndicatorId: '<?php echo $this->mainIndicatorId; ?>', 
                columnName: $this.attr('data-match-field'), 
                mainColumnName: $this.val()
            }, 
            dataType: 'json', 
            success: function(data) {}
        });
    });
    
    <?php
    if (isset($this->isPopup) && $this->isPopup) {
    ?>
    $('.mv-imp-file-list').css('height', $(window).height() - 208);
    <?php
    }
    ?>
});

$.contextMenu({
    selector: '.mv-imp-file-list .imp-file-item',
    callback: function(key, opt) {
        if (key == 'remove') {
            var $this = opt.$trigger;
            mvImportManageRemoveIndicator($this);
        } 
    },
    items: {
        "remove": {name: plang.get('delete_btn'), icon: "trash"}
    }
});

function mvRenderChildDataSets(id) {
    $.ajax({
        type: 'post',
        url: 'mdform/renderChildDataSets/'+id,
        dataType: 'html', 
        success: function(data) {
            $('.mv-imp-file-list').empty().append(data);
            $('.mv-imp-file-filter').empty();
            $('a[href="#mv-imp-file-tab1"]').tab('show');
        }
    });
}
function mvImportManageDataCheck(elem, indicatorId, mainIndicatorId) {
    
    PNotify.removeAll();
    var matchColumns = mvImportManageMatchColumn(indicatorId);
        
    $.ajax({
        type: 'post',
        url: 'mdform/importManageDataCheck',
        data: {indicatorId: indicatorId, mainIndicatorId: mainIndicatorId, matchColumns: matchColumns}, 
        dataType: 'json', 
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            if (data.status == 'success') {

                var html = [], addData = data.add, updateData = data.update;

                html.push('<div class="btn-group btn-group-only-left-right-radius" data-action="add" data-ids="'+addData.ids+'">');
                    html.push('<button type="button" onclick="mvImportManageDataCheckFilter(this, \''+indicatorId+'\', \''+mainIndicatorId+'\');" class="btn btn-primary">Шинэ ( '+addData.count+' )</button>');
                html.push('</div>');

                html.push('<div class="btn-group btn-group-only-left-right-radius ml-1" data-action="update" data-ids="'+updateData.ids+'">');
                    html.push('<button type="button" onclick="mvImportManageDataCheckFilter(this, \''+indicatorId+'\', \''+mainIndicatorId+'\');" class="btn btn-primary">Засагдах ( '+updateData.count+' )</button>');
                html.push('</div>');

                $('.mv-imp-file-filter').empty().append(html.join(''));

                $('a[href="#mv-imp-file-tab2"]').tab('show');

            } else {
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false, 
                    addclass: 'pnotify-center'
                }); 
            }
            Core.unblockUI();
        }
    });
}
function mvImportManageDataCheckFilter(elem, indicatorId, mainIndicatorId) {
    var $this = $(elem), $parent = $this.closest('.btn-group'), ids = $parent.attr('data-ids');
    if (ids != '') {
        var op = window['objectdatagrid_' + indicatorId].datagrid('options');
        var queryParams = op.queryParams;
        var idsArr = ids.split(',');
        var total = idsArr.length;
        var pageSize = 500;
        
        if (total > 500) {
            var pages = Math.ceil(total / pageSize);
            var idsOr = '';
            
            for (var p = 1; p <= pages; p++) {
                var chunkIdsArr = idsArr.slice((p - 1) * pageSize, p * pageSize);
                var chunkIds = '';
                
                for (var c in chunkIdsArr) {
                    chunkIds += chunkIdsArr[c] + ',';
                }
                
                idsOr += 'ID IN (' + rtrim(chunkIds, ',') + ') OR ';
            }
            
            idsOr = rtrim(trim(idsOr), 'OR');
            queryParams.whereClause = '('+idsOr+')';
        } else {
            queryParams.whereClause = 'ID IN ('+ids+')';
        }
        
        window['objectdatagrid_' + indicatorId].datagrid('load', queryParams);
    }
}
function mvImportManageFieldsConfig(elem, indicatorId, mainIndicatorId) {
    $.ajax({
        type: 'post',
        url: 'mdform/importManageFieldsConfig',
        data: {indicatorId: indicatorId, mainIndicatorId: mainIndicatorId}, 
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(dataHtml) {
            var $dialogName = 'dialog-mvImportManageFieldsConfig';
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName);

            $dialog.empty().append(dataHtml);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Талбарын тохиргоо',
                width: 800,
                height: $(window).height() - 100,
                modal: true,
                open: function () {
                    Core.initSelect2($dialog);
                },
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow', click: function () {
                        
                        PNotify.removeAll();
                        var $matchCombo = $dialog.find('select.select2').filter(function() { return this.value != ''; });
                        
                        if ($matchCombo.length) {
                            
                            $dialog.find('form').ajaxSubmit({
                                type: 'post',
                                url: 'mdform/importManageFieldsConfigSave',
                                dataType: 'json',
                                beforeSend: function() {
                                    Core.blockUI({message: 'Loading...', boxed: true});
                                },
                                success: function(data) {
                                    Core.unblockUI();
                                    if (data.status == 'success') {
                                        $dialog.dialog('close');
                                        $('.mv-imp-file-list').find('.imp-file-item.selected').click();
                                    } else {
                                        new PNotify({
                                            title: data.status,
                                            text: data.message,
                                            type: data.status,
                                            sticker: false, 
                                            addclass: 'pnotify-center'
                                        }); 
                                    }
                                }
                            });
                        } else {
                            new PNotify({
                                title: 'Info',
                                text: 'Та харгалзах талбарын тохиргоог хийнэ үү!',
                                type: 'info',
                                sticker: false, 
                                addclass: 'pnotify-center'
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
        }
    });
}
function mvImportManageDataUpdate(elem, indicatorId, mainIndicatorId) {
    PNotify.removeAll();
    var matchColumns = mvImportManageMatchColumn(indicatorId);
    
    $.ajax({
        type: 'post',
        url: 'mdform/importManageDataUpdate',
        data: {indicatorId: indicatorId, mainIndicatorId: mainIndicatorId, matchColumns: matchColumns}, 
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            Core.unblockUI();
            if (data.status == 'success') {
                dataViewReload(indicatorId);
            } else {
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false, 
                    addclass: 'pnotify-center'
                }); 
            }
        }
    });
}
function mvImportManageDataCommit(elem, indicatorId, mainIndicatorId) {
    PNotify.removeAll();
    var dialogName = '#dialog-import-confirm';
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
                var matchColumns = mvImportManageMatchColumn(indicatorId);
                $.ajax({
                    type: 'post',
                    url: 'mdform/importManageDataCommit',
                    data: {indicatorId: indicatorId, mainIndicatorId: mainIndicatorId, matchColumns: matchColumns}, 
                    dataType: 'json',
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function(data) {
                        Core.unblockUI();
                        if (data.hasOwnProperty('uniqueFields')) {
                            mvImportManagePopupDuplicateRows(data.uniqueFields, data.data);
                        } else {
                            new PNotify({
                                title: data.status,
                                text: data.message,
                                type: data.status,
                                sticker: false, 
                                addclass: 'pnotify-center'
                            }); 

                            if (data.status == 'success') {
                                dataViewReload(indicatorId);
                                if (dataViewId_<?php echo $this->mainIndicatorId; ?> != '') {
                                    dataViewReload(dataViewId_<?php echo $this->mainIndicatorId; ?>);
                                }
                            } 
                        }
                    }
                });
            }},
            {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $dialog.dialog('close');
            }}
        ]
    });
    $dialog.dialog('open');
}
function mvImportManageMatchColumn(indicatorId) {
    var $panelView = window['objectdatagrid_'+indicatorId].datagrid('getPanel').children('div.datagrid-view'), 
        $header = $panelView.find('.datagrid-view2 .datagrid-header-row:eq(0)'), 
        $matchCombo = $header.find('select[data-match-field]').filter(function() { return this.value != ''; }); 
    var matchColumn = [];
    
    if ($matchCombo.length > 0) {
        
        $matchCombo.each(function() {
            var $this = $(this);
            matchColumn.push({'columnName': $this.attr('data-match-field'), 'mainColumnName': $this.val()});
        });
    }
    
    return matchColumn;
}
function mvImportManageRemoveIndicator(elem) {
    var $row = $(elem);
    var dialogName = '#dialog-kpiindicatortmp-confirm';
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    var $dialog = $(dialogName);

    $dialog.html(plang.get('msg_delete_confirm'));
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
                PNotify.removeAll();
                
                $.ajax({
                    type: 'post',
                    url: 'mdform/importManageRemoveIndicator',
                    data: {indicatorId: $row.attr('data-id'), mainIndicatorId: '<?php echo $this->mainIndicatorId; ?>'}, 
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
                            addclass: pnotifyPosition
                        });

                        if (data.status == 'success') {
                            $row.remove();
                            $dialog.dialog('close');
                        }

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
}
function mvImportManagePopupDuplicateRows(uniqueFields, data) {
    var render = [], n = 1;
                
    render.push('<table class="table table-bordered table-hover">');
        render.push('<thead>');
            render.push('<tr>');
                render.push('<th class="text-center" style="width: 20px">№</th>');
                render.push('<th class="text-center" style="width: 85px">Давхардсан тоо</th>');
                
                for (var u in uniqueFields) {
                    render.push('<th class="text-center">'+uniqueFields[u]+'</th>');
                }
                
            render.push('</tr>');
        render.push('</thead>');
        render.push('<tbody>');

        for (var d in data) {
            render.push('<tr>');
                render.push('<td class="text-center">'+(n++)+'</td>');
                render.push('<td class="text-center">'+data[d]['ROW_COUNT']+'</td>');
                for (var u in uniqueFields) {
                    render.push('<td>'+data[d][u]+'</td>');
                }
            render.push('</tr>');
        }

        render.push('</tbody>');
    render.push('</table>');    

    var $dialogName = 'dialog-mvduplicate-rows';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);

    $dialog.empty().append(render.join(''));  
    $dialog.dialog({
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'Duplicate rows',
        width: 800,
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
}
function mvImportManageDownloadExcelTemplate(elem, indicatorId) {
    Core.blockUI({message: 'Loading...', boxed: true});
    
    $.fileDownload(URL_APP + 'mdform/downloadExcelImportTemplate', {
        httpMethod: 'post',
        data: {indicatorId: indicatorId, isImportManage: 1}
    }).done(function() {
        Core.unblockUI();
    }).fail(function(response){
        PNotify.removeAll();
        new PNotify({
            title: 'Error',
            text: response,
            type: 'error',
            sticker: false
        });
        Core.unblockUI();
    });
}
</script>