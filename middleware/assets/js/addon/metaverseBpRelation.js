var isMvBpRelationScript = true;
function mvBpRelationAddObject(elem) {
    dataViewSelectableGrid('nullmeta', '0', '16424911273171', 'multi', 'nullmeta', elem, 'mvBpRelationAddObjectFill');
}
function mvBpRelationAddObjectFill(metaDataCode, processMetaDataId, chooseType, elem, rows, paramRealPath, lookupMetaDataId, isMetaGroup) {
    var $this = $(elem), $parent = $this.closest('div').find('.kpi-component-wrap'), 
        add_btn = plang.get('add_btn'), delete_btn = plang.get('delete_btn'), html = [], mode = $this.attr('data-m');
    
    for (var k in rows) {
        
        var row = rows[k];
        
        if ($parent.find('div[data-indicator-id="'+row.id+'"]').length == 0) {
            
            html.push('<div class="col reldetail mt-2" data-indicator-id="'+row.id+'" style="background-color: #f1f8e9; border: 1px solid #e0e0e0;">');
                html.push('<div class="d-flex align-items-center align-items-md-start flex-column flex-md-row pt-2">');
                    html.push('<h5 class="reltitle line-height-normal font-size-14 font-weight-bold cursor-pointer text-select-none" style="-ms-flex: 1;flex: 1;" onclick="kpiIndicatorRelationCollapse(this);">');
                        html.push('<i class="far fa-angle-down"></i> '+row.name);
                    html.push('</h5>');
                    html.push('<a href="javascript:;" onclick="chooseKpiIndicatorRowsFromBasket(this, \''+row.id+'\', \'multi\', \'mvIndicatorRelationFillRows\');" title="'+add_btn+'" data-action-name="addIndicatorValue">');
                        html.push('<i class="far fa-plus font-size-20"></i>');
                    html.push('</a>');
                    
                    if (mode == '0') {
                        html.push('<a href="javascript:;" onclick="mvBpRelationRemoveObject(this);" title="'+delete_btn+'" data-action-name="removeIndicator">');
                            html.push('<i class="far fa-trash font-size-20 text-danger ml-2"></i>');
                        html.push('</a>');
                    }
                    
                html.push('</div>');
                html.push('<table class="table table-sm table-hover" style="border-top: 1px #ddd solid;">');
                    html.push('<tbody></tbody>');
                html.push('</table>');
            html.push('</div>');
        }
    }
    
    $parent.append(html.join('')).promise().done(function() {
        mvBpRelationActionControl($parent.closest('.tab-pane'));
    });
}
function mvIndicatorRelationFillRows(elem, indicatorId, rows, idField, nameField, chooseType) {
    var html = [], $tbody = elem.closest('[data-indicator-id]').find('table.table > tbody');
    var delete_btn = plang.get('delete_btn');
    var view_btn = plang.get('view_btn');

    for (var i in rows) {
        
        var row = rows[i], rowId = row[idField], rowName = row[nameField];
        var $checkRow = $tbody.find('> tr[data-rowid="'+rowId+'"]');
        
        if ($checkRow.length == 0) {
            
            html.push('<tr data-rowid="'+rowId+'">');
                html.push('<td style="height: 35px; max-width: 0;" class="text-left text-truncate">');
                    html.push('<input type="hidden" name="mvDmRecordMaps[indicatorId][]" value="'+indicatorId+'">');
                    html.push('<input type="hidden" name="mvDmRecordMaps[recordId][]" value="'+rowId+'">');
                    html.push('<a href="javascript:;" onclick="bpCallKpiIndicatorForm(this, this, \''+indicatorId+'\', \''+rowId+'\', \'view\');" class="font-size-14" title="'+view_btn+'">');
                        html.push('<i style="color:blue" class="far fa-file-search mr-1"></i>');
                        html.push(rowName);
                    html.push('</a>');
                html.push('</td>');
                html.push('<td style="width: 60px" class="text-right">');
                    html.push('<a href="javascript:;" onclick="kpiIndicatorRelationRemoveRows(this);" class="font-size-14" title="'+delete_btn+'" data-action-name="removeIndicatorValue"><i class="far fa-trash text-danger"></i></a>');
                html.push('</td>');
            html.push('</tr>');
        }
    }
    
    $tbody.append(html.join('')).promise().done(function() {
        mvBpRelationActionControl($tbody.closest('.tab-pane'));
    });
}
function mvBpRelationRemoveObject(elem) {
    var dialogName = '#dialog-kpidmart-obj-confirm';
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
                
                var $row              = $(elem).closest('[data-indicator-id]');
                var $parent           = $row.closest('[data-refstructureid]');
                var refStructureId    = $parent.attr('data-refstructureid');
                var sourceId          = $parent.attr('data-sourceid');
                var trgRefStructureId = $row.attr('data-indicator-id');
                
                if (refStructureId != '' && sourceId != '' && trgRefStructureId != '') {
                    $.ajax({
                        type: 'post',
                        url: 'mdform/bpRelationRemoveRow',
                        data: {refStructureId: refStructureId, sourceId: sourceId, trgRefStructureId: trgRefStructureId}, 
                        dataType: 'json',
                        beforeSend: function () {
                            Core.blockUI({message: 'Loading...', boxed: true});
                        },
                        success: function (data) {
                            PNotify.removeAll();
                            if (data.status == 'success') {
                                $row.remove();
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
                    $row.remove();
                }
                
                $dialog.dialog('close');
            }},
            {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $dialog.dialog('close');
            }}
        ]
    });
    $dialog.dialog('open');
}
function mvBpRelationRemoveRow(elem) {
    var dialogName = '#dialog-kpidmart-obj-confirm';
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
                
                var $row = $(elem).closest('tr');
                var $parent = $row.closest('[data-refstructureid]');
                var refStructureId = $parent.attr('data-refstructureid');
                var sourceId = $parent.attr('data-sourceid');
                var trgRefStructureId = $row.attr('data-indicatorid');
                var trgSourceId = $row.attr('data-rowid');
                
                $.ajax({
                    type: 'post',
                    url: 'mdform/bpRelationRemoveRow',
                    data: {refStructureId: refStructureId, sourceId: sourceId, trgRefStructureId: trgRefStructureId, trgSourceId: trgSourceId}, 
                    dataType: 'json',
                    beforeSend: function () {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function (data) {
                        PNotify.removeAll();
                        if (data.status == 'success') {
                            $row.remove();
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
                
                $dialog.dialog('close');
            }},
            {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $dialog.dialog('close');
            }}
        ]
    });
    $dialog.dialog('open');
}
function mvBpRelationActionControl($mvRelationElem) {
    if ($mvRelationElem.hasAttr('data-controls')) {
        var mvRelationControls = $mvRelationElem.attr('data-controls');
        if (mvRelationControls.indexOf('addIndicator=hide') !== -1) {
            $mvRelationElem.find('[data-action-name="addIndicator"]').hide();
        }
        if (mvRelationControls.indexOf('addIndicatorValue=hide') !== -1) {
            $mvRelationElem.find('[data-action-name="addIndicatorValue"]').hide();
        }
        if (mvRelationControls.indexOf('removeIndicator=hide') !== -1) {
            $mvRelationElem.find('[data-action-name="removeIndicator"]').hide();
        }
        if (mvRelationControls.indexOf('removeIndicatorValue=hide') !== -1) {
            $mvRelationElem.find('[data-action-name="removeIndicatorValue"]').hide();
        }
        
        if (mvRelationControls.indexOf('addIndicator=show') !== -1) {
            $mvRelationElem.find('[data-action-name="addIndicator"]').show();
        }
        if (mvRelationControls.indexOf('addIndicatorValue=show') !== -1) {
            $mvRelationElem.find('[data-action-name="addIndicatorValue"]').show();
        }
        if (mvRelationControls.indexOf('removeIndicator=show') !== -1) {
            $mvRelationElem.find('[data-action-name="removeIndicator"]').show();
        }
        if (mvRelationControls.indexOf('removeIndicatorValue=show') !== -1) {
            $mvRelationElem.find('[data-action-name="removeIndicatorValue"]').show();
        }
        
        if (mvRelationControls.indexOf('allaction=hide') !== -1) {
            $mvRelationElem.find('[data-action-name="addIndicator"], [data-action-name="addIndicatorValue"], [data-action-name="removeIndicator"], [data-action-name="removeIndicatorValue"]').hide();
        }
        if (mvRelationControls.indexOf('allaction=show') !== -1) {
            $mvRelationElem.find('[data-action-name="addIndicator"], [data-action-name="addIndicatorValue"], [data-action-name="removeIndicator"], [data-action-name="removeIndicatorValue"]').show();
        }
    }
}