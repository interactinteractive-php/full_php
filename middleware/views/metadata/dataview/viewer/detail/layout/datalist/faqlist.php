<?php
if (isset($this->row['dataViewLayoutTypes']['datalist'])) {
    
    $typeRow = $this->row['dataViewLayoutTypes']['datalist'];
    
    $idField = $this->row['idField'] ? $this->row['idField'] : 'id';
    $groupField = '';
    
    if (isset($typeRow['fields']['groupName']) && $typeRow['fields']['groupName'] != '') {
        
        $groupField = strtolower($typeRow['fields']['groupName']);
        
    } elseif (issetParam($this->dataGridOptionData['GROUPFIELD']) != '') {
        
        $groupField = strtolower($this->dataGridOptionData['GROUPFIELD']);
    }
    
    if (isset($typeRow['fields']['title']) && $typeRow['fields']['title'] != '') {
        $titleField = strtolower($typeRow['fields']['title']);
    } else {
        $titleField = $idField;
    }
    
    if (isset($typeRow['fields']['descr']) && $typeRow['fields']['descr'] != '') {
        $descrField = strtolower($typeRow['fields']['descr']);
    } else {
        $descrField = $idField;
    }
    
    if (isset($typeRow['fields']['name1']) && $typeRow['fields']['name1'] != '') {
        $name1 = strtolower($typeRow['fields']['name1']);
    } 
    
    if (isset($typeRow['fields']['name2']) && $typeRow['fields']['name2'] != '') {
        $name2 = strtolower($typeRow['fields']['name2']);
    } 
    
    if (isset($typeRow['fields']['name3']) && $typeRow['fields']['name3'] != '') {
        $name3 = strtolower($typeRow['fields']['name3']);
    }
    
    if (isset($typeRow['fields']['name4']) && $typeRow['fields']['name4'] != '') {
        $name4 = strtolower($typeRow['fields']['name4']);
    }
    
    if (isset($typeRow['fields']['rowColor']) && $typeRow['fields']['rowColor'] != '') {
        $rowColor = strtolower($typeRow['fields']['rowColor']);
    } 
    
    if (isset($typeRow['fields']['icon']) && $typeRow['fields']['icon'] != '') {
        $icon = strtolower($typeRow['fields']['icon']);
    } 
    
    if (isset($typeRow['fields']['iconColor']) && $typeRow['fields']['iconColor'] != '') {
        $iconColor = strtolower($typeRow['fields']['iconColor']);
    } 
?>
var datalistview_<?php echo $this->metaDataId; ?> = $.extend({}, $.fn.datagrid.defaults.view, {
    render: function (target, container, frozen) {
        var data = $.data(target, "datagrid");
        var rows = data.data.rows;
        var rowsLength = rows.length;
        var fields = $(target).datagrid("getColumnFields", frozen);
        var cls = "class=\"card mb-1 datagrid-row datagrid-row-faqlist\"";
        var table = [];
        
        if ('<?php echo $groupField ?>' === '') {
            table.push('<div class="card-group-control card-group-control-right">');
            for (var i = 0; i < rowsLength; i++) {
                table.push("<div datagrid-row-index=\"" + i + "\" " + cls + " style=\"height: inherit;<?php echo (isset($rowColor) ? 'background-color:"+rows[i][\''.$rowColor.'\']+";' : ''); ?>\">");
                table.push(this.renderRow.call(this, target, fields, frozen, rowKey, rows[i]));
                table.push("</div>");
            }
            table.push('</div>');
        } else if ('<?php echo $groupField ?>' === 'semantictypename') {
        
            var rowKey = 0, groupCheck = {};
            
            for (var i = 0; i < rowsLength; i++) {
                if (typeof groupCheck[rows[i].<?php echo $idField; ?>] === 'undefined') {
                    table.push('<div class="datagrid-group-name pt-0 mb-2 font-weight-bold text-muted">'+rows[i]['<?php echo $groupField ?>']+'</div>');
                }
                table.push('<div class="card-group-control card-group-control-right">');
                for (var ii = 0; ii < rowsLength; ii++) {
                    if (rows[i]['<?php echo $groupField ?>'] == rows[ii]['<?php echo $groupField ?>'] && typeof groupCheck[rows[ii].<?php echo $idField; ?>] === 'undefined') {
                        groupCheck[rows[ii].<?php echo $idField; ?>] = true;
                        
                        table.push("<div datagrid-row-index=\"" + rowKey + "\" " + cls + " style=\"padding: 0;height: inherit;<?php echo (isset($rowColor) ? 'background-color:"+rows[ii][\''.$rowColor.'\']+";' : ''); ?>\">");
                        table.push(this.renderRow2.call(this, target, fields, frozen, rowKey, rows[ii]));
                        table.push("</div>");
                        rowKey++;
                    }
                }
                table.push('</div>');
            }
        } else {
        
            var rowKey = 0, groupCheck = {};
            
            for (var i = 0; i < rowsLength; i++) {
                if (typeof groupCheck[rows[i].<?php echo $idField; ?>] === 'undefined') {
                    table.push('<div class="datagrid-group-name pt-1 mb-2 font-weight-bold text-muted">'+rows[i]['<?php echo $groupField ?>']+'</div>');
                }
                table.push('<div class="card-group-control card-group-control-right">');
                for (var ii = 0; ii < rowsLength; ii++) {
                    if (rows[i]['<?php echo $groupField ?>'] == rows[ii]['<?php echo $groupField ?>'] && typeof groupCheck[rows[ii].<?php echo $idField; ?>] === 'undefined') {
                        groupCheck[rows[ii].<?php echo $idField; ?>] = true;
                        
                        table.push("<div datagrid-row-index=\"" + rowKey + "\" " + cls + " style=\"height: inherit;<?php echo (isset($rowColor) ? 'background-color:"+rows[ii][\''.$rowColor.'\']+";' : ''); ?>\">");
                        table.push(this.renderRow.call(this, target, fields, frozen, rowKey, rows[ii]));
                        table.push("</div>");
                        rowKey++;
                    }
                }
                table.push('</div>');
            }
        }
        $(container).html(table.join(''));
    },
    renderRow: function (target, fields, frozen, rowIndex, rowData) {
        var cc = [];
        var icon = <?php echo (isset($icon) ? "(rowData.$icon ? rowData.$icon : 'icon-help')" : "'icon-help'"); ?>;
        var iconColor = '<?php echo (isset($iconColor) ? $iconColor : ''); ?>';
        
        cc.push('<div class="card-header" style="padding: .9375rem 1.25rem;" onclick="dvFaqlistDropDownClick(this, \''+rowData.<?php echo $idField; ?>+'\');">');
            cc.push('<h6 class="card-title">');
                cc.push('<a class="text-default collapsed" data-toggle="collapse" href="#question'+rowData.<?php echo $idField; ?>+'">');
                    cc.push('<i class="'+icon+' mr-2 text-slate" style="color:'+iconColor+'"></i> ' + rowData.<?php echo $titleField; ?>);
                cc.push('</a>');
            cc.push('</h6>');
        cc.push('</div>');

        cc.push('<div id="question'+rowData.<?php echo $idField; ?>+'" class="collapse">');
            cc.push('<div class="card-body" style="padding: 0 1.25rem 1.25rem 1.25rem">');
            cc.push('<div class="d-flex justify-content-between">');
                <?php
                if (isset($name2)) {
                ?>
                cc.push(dvFaqListBreadcrumb(rowData.<?php echo $name2; ?>));
                <?php
                }
                ?>
                <?php
                if (isset($name3)) {
                ?>                
                cc.push('<div style="font-weight: bold;">');
                cc.push(gridHtmlDecode(rowData.<?php echo $name3; ?>));
                cc.push('</div>');
                <?php
                }
                ?>
                cc.push('</div>');
                
                cc.push(gridHtmlDecode(rowData.<?php echo $descrField; ?>));
            cc.push('</div>');
            
            <?php
            if (isset($name1)) {
            ?>
            if (rowData.<?php echo $name1; ?>) {
                cc.push('<div class="card-footer bg-transparent border-top-0 pt-0">');
                    cc.push('<div class="d-flex justify-content-between">');
                    cc.push('<div>');
                    <?php
                    if (isset($name1)) {
                    ?>
                    cc.push(dvFaqListTags(rowData.<?php echo $name1; ?>));
                    <?php
                    }
                    ?>
                    cc.push('</div>');
                    <?php
                    if (isset($name4)) {
                    ?>
                    cc.push('<div>');
                    cc.push(gridHtmlDecode(rowData.<?php echo $name4; ?>));
                    cc.push('</div>');
                    <?php
                    }
                    ?>
                    cc.push('</div>');                    

                cc.push('</div>');
            }
            <?php
            }
            ?>
        cc.push('</div>');

        return cc.join('');
    },
    renderRow2: function (target, fields, frozen, rowIndex, rowData) {
        var cc = [];
        var icon = <?php echo (isset($icon) ? "(rowData.$icon ? rowData.$icon : 'far fa-file-search')" : "'far fa-file-search'"); ?>;
        var iconColor = '<?php echo (isset($iconColor) ? $iconColor : 'blue'); ?>';
        
        cc.push('<div class="card-header" style="padding: 0.4rem 0.4rem;height: 35px;" onclick="dvFaqlistDropDownClick(this, \''+rowData.<?php echo $idField; ?>+'\');">');
            cc.push('<h6 class="d-flex justify-content-between">');
                cc.push('<a class="text-default collapsed" style="font-size:12px" data-toggle="collapse" href="#question'+rowData.<?php echo $idField; ?>+'">');
                    cc.push('<i class="'+icon+' mr-2 text-slate" style="color:'+iconColor+'"></i> ' + rowData.<?php echo $titleField; ?>);
                cc.push('</a>');
                cc.push('<a href="javascript:;" onclick="chooseKpiIndicatorRowsFromBasket(this, \''+rowData.trgindicatorid+'\', \'multi\', \'kpiIndicatorMainRelationFillRows\', \''+rowData.srcindicatorid+'\');"><i class="icon-plus3 font-size-12" style="color:#333"></i></a>');
            cc.push('</h6>');
        cc.push('</div>');

        cc.push('<div id="question'+rowData.<?php echo $idField; ?>+'" class="collapse">');
            cc.push('<div class="card-body" style="padding: 0 1.25rem 1.25rem 1.25rem">');
            cc.push('</div>');
            
            <?php
            if (isset($name1)) {
            ?>
            if (rowData.<?php echo $name1; ?>) {
                cc.push('<div class="card-footer bg-transparent border-top-0 pt-0">');
                    cc.push('<div class="d-flex justify-content-between">');
                    cc.push('<div>');
                    <?php
                    if (isset($name1)) {
                    ?>
                    cc.push(dvFaqListTags(rowData.<?php echo $name1; ?>));
                    <?php
                    }
                    ?>
                    cc.push('</div>');
                    <?php
                    if (isset($name4)) {
                    ?>
                    cc.push('<div>');
                    cc.push(gridHtmlDecode(rowData.<?php echo $name4; ?>));
                    cc.push('</div>');
                    <?php
                    }
                    ?>
                    cc.push('</div>');                    

                cc.push('</div>');
            }
            <?php
            }
            ?>
        cc.push('</div>');

        return cc.join('');
    }
});

function dvFaqListBreadcrumb(pathName, pathId) {
    
    if (pathName) {
        var cc = [], pathNameArr = pathName.split('♠');

        cc.push('<div class="breadcrumb ml-0 mb-2 font-weight-bold">');

            for (var p in pathNameArr) {
                cc.push('<a href="javascript:;" class="breadcrumb-item py-0">'+pathNameArr[p]+'</a>');
            }

        cc.push('</div>');

        return cc.join('');
    }
    
    return null;
}

function dvFaqListTags(tags) {
    
    if (tags) {
        var cc = [], tagsArr = tags.split(',');

        for (var t in tagsArr) {
            cc.push('<span class="badge badge-info badge-pill font-size-11 mr-1">'+(tagsArr[t]).trim()+'</span>');
        }

        return cc.join('');
    }
    
    return null;
}

function dvFaqlistDropDownClick(elem, id) {
    var index = $(elem).closest('[datagrid-row-index]').index();
    objectdatagrid_<?php echo $this->metaDataId; ?>.addClass('not-datagrid');
    objectdatagrid_<?php echo $this->metaDataId; ?>.val(id);
}

function explorerRefresh_<?php echo $this->metaDataId; ?>(elem) {
    objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('reload');
}

function kpiIndicatorMainRelationFillRows(elem, indicatorId, rows, idField, codeField, nameField, chooseType, srcIndicatorId) {
    
    var html = [], $tbody = elem.closest('.datagrid-row-faqlist').find('.card-body');
    var delete_btn = plang.get('delete_btn');
    var view_btn = plang.get('view_btn');
    var isAddonForm = false;
    var indicatorRecordMaps = [];
    
    if (elem.hasAttr('data-config')) {
        var configObj = elem.attr('data-config');
        if (typeof configObj !== 'object') {
            configObj = JSON.parse(html_entity_decode(configObj, "ENT_QUOTES"));
        } 
        if (Number(configObj.isAddonForm) > 0 && configObj.metaInfoIndicatorId != '') {
            var metaInfoIndicatorId = configObj.metaInfoIndicatorId;
            isAddonForm = true;
        }
    }

    for (var i in rows) {
        
        var row = rows[i], rowId = row[idField], rowName = row[nameField];
        var $checkRow = $tbody.find('> div[data-basketrowid="'+rowId+'"]');
        var childRowData = '';
        
        if ($checkRow.length == 0) {
            
            if (isAddonForm) {
                childRowData = JSON.stringify(row);
            }
            
            indicatorRecordMaps.push(rowId);

            html.push('<div data-basketrowid="'+rowId+'" style="margin-left: 0.75rem" class="d-flex justify-content-between">');                
                    html.push('<input type="hidden" name="metaDmRecordMaps[indicatorId][]" value="'+indicatorId+'">');
                    html.push('<input type="hidden" name="metaDmRecordMaps[recordId][]" value="'+rowId+'">');
                    html.push('<input type="hidden" name="metaDmRecordMaps[mapId][]">');
                    html.push('<input type="hidden" name="metaDmRecordMaps[rowState][]" value="added">');
                    html.push('<input type="hidden" name="metaDmRecordMaps[childRecordId][]">');
                    html.push('<textarea class="d-none" name="metaDmRecordMaps[childRowData][]">'+childRowData+'</textarea>');                    
                    html.push('<a href="javascript:;" onclick="bpCallKpiIndicatorForm(this, this, \''+indicatorId+'\', \''+rowId+'\', \'view\');" class="font-size-12" title="'+view_btn+'">');
                        html.push('<i style="color:blue" class="far fa-file mr-1"></i>');
                        html.push(rowName);
                    html.push('</a>');            
                html.push('<div');
                
                    if (isAddonForm) {
                        html.push('<a href="javascript:;" onclick="kpiIndicatorRelationSubRows(this, \''+metaInfoIndicatorId+'\');" class="font-size-16 mr-3" title="Холбоос"><i style="color:#5c6bc0;" class="far fa-external-link-square"></i></a>');
                    }
                    html.push('<a href="javascript:;" onclick="kpiIndicatorRelationRemoveRows(this);" class="font-size-12" title="'+delete_btn+'"><i style="color:red" class="far fa-trash"></i></a>');
                    
                html.push('</div>');
            html.push('</div>');
        }
    }

    $.ajax({
      type: "post",
      url: "mdform/kpiSaveMetaDmRecordMap2",
      data: {
        mainIndicatorId: srcIndicatorId,
        indicatorId: indicatorId,
        indicatorRecordMaps: indicatorRecordMaps
      },
      dataType: "json",
      beforeSend: function () {
        Core.blockUI({
          message: "Loading...",
          boxed: true,
        });
      },
      success: function (data) {
        if (data.status == "success") {
            $tbody.parent().addClass('show');
            $tbody.append(html.join(''));            
            new PNotify({
            title: data.status,
            text: data.message,
            type: data.status,
            sticker: false,
          });            
        }
        Core.unblockUI();
      },
    });    
}

    <?php
    if ($this->dataGridOptionData['DRILLDBLCLICKROW'] == 'true' && $this->dataGridOptionData['DRILL_CLICK_FNC']) {
    ?>        
        $('.div-objectdatagrid-<?php echo $this->metaDataId; ?>').on("dblclick", ".datagrid-row-faqlist", function () {
            $(this).find(".card-header").click();
            <?php echo $this->dataGridOptionData['DRILL_CLICK_FNC']; ?>
        });        
    <?php
    }
}
?>