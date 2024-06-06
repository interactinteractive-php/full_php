<div class="row">
    <div class="col-md-auto imp-file-left-part">
        
        <button 
            type="button" 
            class="btn btn-block green-meadow" 
            onclick="createMvStructureFromFile(this, '', {isContextMenu: false, isImportManage: true, isImportManageAI: true, mainIndicatorId: '<?php echo $this->mainIndicatorId; ?>'});">
            <i class="fa fa-plus"></i> Импорт хийх
        </button>
        
        <div class="tabbable-line bp-tabs">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#mv-imp-file-tab1" class="nav-link active" data-toggle="tab">Файл</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane mv-imp-file-list active" id="mv-imp-file-tab1">
                    <?php echo $this->renderChildDataSets; ?>
                </div>
            </div>
        </div>
        
    </div>
    <div class="col overflow-auto ml-2" id="mv-imp-imported-data">
        
    </div>
</div>

<style type="text/css">
.imp-file-left-part {
    width: 300px;
    height: 80vh;
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
#mv-imp-imported-data .jeasyuiTheme3 .datagrid-htable .datagrid-header-row:not(.datagrid-filter-row) {
    height: inherit !important;
}
</style>

<script type="text/javascript">
$(function() {
    $('.mv-imp-file-list').on('click', '.imp-file-item', function() {
        var $this = $(this), $parent = $this.closest('.mv-imp-file-list'), 
            indicatorId = $this.attr('data-id');
        
        $parent.find('.imp-file-item.selected').removeClass('selected');
        $this.addClass('selected');
        
        $.ajax({
            type: 'post',
            url: 'mdform/indicatorImportList',
            data: {indicatorId: indicatorId, mainIndicatorId: '<?php echo $this->mainIndicatorId; ?>', isAIImport: 1}, 
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
            url: 'mdform/importManageAIChangeColumn',
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
function mvImportManageAIDataCommit(elem, indicatorId, mainIndicatorId) {
    PNotify.removeAll();
    
    $.ajax({
        type: 'post',
        url: 'mdform/importManageAIDataCommit',
        data: {indicatorId: indicatorId, mainIndicatorId: mainIndicatorId, usageType: 1}, 
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            Core.unblockUI();
            new PNotify({
                title: data.status,
                text: data.message,
                type: data.status,
                sticker: false, 
                addclass: 'pnotify-center'
            }); 
            
            if (data.refresh == 1) {
                $('.mv-imp-file-list').find('.imp-file-item.selected').trigger('click');
            }
            
            /*if (data.status == 'success') {
                
                if (data.hasOwnProperty('colors')) {
                    var colors = data.colors;
                    var dg = window['objectdatagrid_' + indicatorId];
                    if (colors.length) {
                        for (var c in colors) {
                            var col = dg.datagrid('getColumnOption', colors[c]['COLUMN_NAME']);
                            if (colors[c]['COLOR'] != '' && colors[c]['COLOR'] != null) {
                                col.styler = function() {
                                    return 'background-color:#' + colors[c]['COLOR'];
                                };
                            } else {
                                col.styler = function() {
                                    return '';
                                };
                            }
                        }
                        dg.datagrid('refreshRow', 0);
                    } else {
                        var opts = dg.datagrid('getColumnFields');
                    }
                }
                dataViewReload(indicatorId);
            }*/
        }
    });
}
function mvImportManageAIDataRollback(elem, indicatorId, mainIndicatorId) {
    PNotify.removeAll();
    
    var dialogName = '#dialog-import-rollback-confirm';
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
                PNotify.removeAll();
                
                $.ajax({
                    type: 'post',
                    url: 'mdform/importManageAIDataCommit',
                    data: {indicatorId: indicatorId, mainIndicatorId: mainIndicatorId, usageType: 2}, 
                    dataType: 'json',
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function(data) {
                        Core.unblockUI();
                        new PNotify({
                            title: data.status,
                            text: data.message,
                            type: data.status,
                            sticker: false, 
                            addclass: 'pnotify-center'
                        }); 
                        $dialog.dialog('close');
                        
                        if (data.refresh == 1) {
                            $('.mv-imp-file-list').find('.imp-file-item.selected').trigger('click');
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
</script>