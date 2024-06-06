<?php
if ($this->components) {
?>

<div class="kpi-component-wrap mt-2" id="kpi-component-<?php echo $this->componentUniqId; ?>">
    
    <?php
    echo Form::hidden(array('name' => 'isKpiComponent', 'value' => '1'));
    
    $add_btn = $this->lang->line('add_btn');
    $view_btn = $this->lang->line('view_btn');
    $delete_btn = $this->lang->line('delete_btn');
    $excelExportBtn = '<a href="javascript:;" onclick="mvRecordRelationExcelExport(this);" class="font-size-16 mr-2 d-none" title="'.$this->lang->line('excel_export_btn').'" data-action-name="exportexcel"><i style="color:green" class="far fa-file-excel"></i></a>';
    
    foreach ($this->components as $row) {
        
        if ($row['CODE'] != '' && Mdform::$defaultTplSavedId) {
            
            $columnsData = (new Mdform_Model())->getKpiIndicatorColumnsModel($this->indicatorId, array('isIgnoreStandardFields' => true));
            $fieldConfig = (new Mdform_Model())->getKpiIndicatorIdFieldModel($this->indicatorId, $columnsData);
            
            if (isset($fieldConfig['codeField']) 
                && $fieldConfig['codeField'] != '' 
                && isset(Mdform::$kpiDmMart[$fieldConfig['codeField']]) 
                && Str::lower(Mdform::$kpiDmMart[$fieldConfig['codeField']]) != Str::lower($row['CODE'])) {
                
                continue;
            }
        }
    ?>
    <div class="col reldetail mt-2" style="background-color: #f1f8e9; border: 1px solid #e0e0e0;">                       
        <div class="d-flex align-items-center align-items-md-start flex-column flex-md-row pt-2">
            <h5 class="reltitle line-height-normal font-size-14 font-weight-bold cursor-pointer text-select-none" style="-ms-flex: 1;flex: 1;" onclick="kpiIndicatorRelationCollapse(this);">
                <i class="far fa-angle-down"></i> <?php echo $row['NAME']; ?>
            </h5>
            
            <!--<a href="javascript:;" onclick="chooseKpiIndicatorRowsFromBasket(this, '<?php echo $row['ID']; ?>', 'multi');" title="<?php echo $add_btn; ?>">
                <i class="icon-plus3 relicon"></i>
            </a>-->
            
            <div class="input-group quick-item-process float-left" style="margin-top: -6px;">
                <div class="input-icon meta-autocomplete-wrap mv-popup-control">
                    <i class="far fa-search"></i>
                    <?php
                    echo Form::text(array(
                        'class' => 'form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete',
                        'id' => $row['ID'].'_nameField', 
                        'style' => 'padding-left:25px;border-top-left-radius: 0.1875rem;border-bottom-left-radius: 0.1875rem;',
                        'data-processid' => $row['ID'],
                        'data-lookupid' => $row['ID'],
                        'placeholder' => 'Хайх'
                    ));
                    echo Form::hidden(array('id' => $row['ID'].'_valueField', 'data-listen-path' => 1, 'data-lookupid' => $row['ID']));
                    ?>
                </div>
                <span class="input-group-btn">
                    <?php 
                    $configArr = array('isAddonForm' => $row['IS_ADDON_FORM'], 'metaInfoIndicatorId' => $row['META_INFO_INDICATOR_ID']);
                    if (isset($this->fromWebLink)) {
                        echo Form::button(array(
                            'class' => 'btn btn-xs green-meadow',
                            'value' => '<i class="icon-plus3 font-size-12"></i>', 
                            'data-config' => htmlentities(str_replace('&quot;', '\\&quot;', json_encode($configArr)), ENT_QUOTES, 'UTF-8'), 
                            'onclick' => 'chooseKpiIndicatorRowsFromBasket(this, \''.$row['ID'].'\', \'multi\', \'kpiIndicatorMainRelationFillRows\');'
                        ));
                    } else {
                        echo Form::button(array(
                            'class' => 'btn btn-xs green-meadow',
                            'value' => '<i class="icon-plus3 font-size-12"></i>', 
                            'data-config' => htmlentities(str_replace('&quot;', '\\&quot;', json_encode($configArr)), ENT_QUOTES, 'UTF-8'), 
                            'onclick' => 'chooseKpiIndicatorRowsFromBasket(this, \''.$row['ID'].'\', \'multi\');'
                        ));
                    }
                    ?>
                </span>
            </div>
            
        </div>

        <table class="table table-sm table-hover mv-record-map-tbl" style="border-top: 1px #ddd solid;">
            <tbody>
                <?php
                if (isset($this->savedComponentRows[$row['ID']])) {
                    
                    $childRows = $this->savedComponentRows[$row['ID']];
                    
                    foreach ($childRows as $childRow) {
                ?>
                    <tr data-basketrowid="<?php echo $childRow['PF_MAP_RECORD_ID']; ?>">
                        <td style="height: 25px; max-width: 0;" class="text-left text-truncate">
                            <input type="hidden" name="metaDmRecordMaps[indicatorId][]" value="<?php echo $row['ID']; ?>">
                            <input type="hidden" name="metaDmRecordMaps[recordId][]" value="<?php echo $childRow['PF_MAP_RECORD_ID']; ?>">
                            <input type="hidden" name="metaDmRecordMaps[mapId][]" value="<?php echo $childRow['PF_MAP_ID']; ?>">
                            <input type="hidden" name="metaDmRecordMaps[rowState][]" value="saved">
                            <input type="hidden" name="metaDmRecordMaps[childRecordId][]" value="<?php echo $childRow['PF_MAP_TRG_RECORD_ID']; ?>">
                            <textarea class="d-none" name="metaDmRecordMaps[childRowData][]"><?php echo json_encode($childRow, JSON_UNESCAPED_UNICODE); ?></textarea>
                            
                            <a href="javascript:;" onclick="bpCallKpiIndicatorForm(this, this, '<?php echo $row['ID']; ?>', '<?php echo $childRow['PF_MAP_RECORD_ID']; ?>', 'view');" class="font-size-14" title="<?php echo $view_btn; ?>">
                                <i style="color:blue" class="far fa-file-search mr-1"></i>
                                <?php echo $childRow['PF_MAP_NAME']; ?>
                            </a>
                        </td>
                        <td style="width: 70px" class="text-right">
                            <?php
                            if ($row['IS_ADDON_FORM'] && $row['META_INFO_INDICATOR_ID']) {
                            ?>
                            <a href="javascript:;" onclick="kpiIndicatorRelationSubRows(this, '<?php echo $row['META_INFO_INDICATOR_ID']; ?>', '<?php echo $childRow['PF_MAP_TRG_RECORD_ID']; ?>');" class="font-size-16 mr-3" title="Холбоос"><i style="color:#5c6bc0;" class="far fa-external-link-square"></i></a>
                            <?php
                            }
                            echo $excelExportBtn; 
                            ?>
                            <a href="javascript:;" onclick="kpiIndicatorRelationRemoveRows(this);" class="font-size-16" title="<?php echo $delete_btn; ?>"><i style="color:red" class="far fa-trash"></i></a>
                        </td>
                    </tr>
                <?php
                    }
                }
                ?>
                    
            </tbody>
        </table>
    </div>
    <?php
    }
    ?>

</div>

<script type="text/javascript">
$(function() {
    
    $('#kpi-component-<?php echo $this->componentUniqId; ?>').on('change', 'input[data-listen-path="1"]', function() {
        var $this = $(this);
        if ($this.val() != '') {
            var attributes = $this.attr('data-attributes');
            var rowData = $this.attr('data-row-data');
            var $parent = $this.closest('.quick-item-process');
            
            $parent.find('input[id*="_nameField"], input[id*="_valueField"]').val('').attr({'data-attributes': '', 'data-row-data': ''});
            
            if (typeof attributes !== 'object') {
                var attributesObj = JSON.parse(html_entity_decode(attributes, 'ENT_QUOTES'));
            } else {
                var attributesObj = attributes;
            }
            
            if (typeof rowData !== 'object') {
                var rowDataObj = JSON.parse(html_entity_decode(rowData, 'ENT_QUOTES'));
            } else {
                var rowDataObj = rowData;
            }
            var rows = [rowDataObj];
            
            kpiIndicatorRelationFillRows($this, $this.attr('data-lookupid'), rows, attributesObj.id, attributesObj.code, attributesObj.name, 'single');
        }
    });
    
});  
function kpiIndicatorMainRelationFillRows(elem, indicatorId, rows, idField, codeField, nameField, chooseType) {
    
    var html = [], $tbody = elem.closest('.reldetail').find('table.mv-record-map-tbl > tbody:eq(0)');
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
        var $checkRow = $tbody.find('> tr[data-basketrowid="'+rowId+'"]');
        var childRowData = '';
        
        if ($checkRow.length == 0) {
            
            if (isAddonForm) {
                childRowData = JSON.stringify(row);
            }
            
            indicatorRecordMaps.push(rowId);

            html.push('<tr data-basketrowid="'+rowId+'">');
                html.push('<td style="height: 25px; max-width: 0;" class="text-left text-truncate">');
                
                    html.push('<input type="hidden" name="metaDmRecordMaps[indicatorId][]" value="'+indicatorId+'">');
                    html.push('<input type="hidden" name="metaDmRecordMaps[recordId][]" value="'+rowId+'">');
                    html.push('<input type="hidden" name="metaDmRecordMaps[mapId][]">');
                    html.push('<input type="hidden" name="metaDmRecordMaps[rowState][]" value="added">');
                    html.push('<input type="hidden" name="metaDmRecordMaps[childRecordId][]">');
                    html.push('<textarea class="d-none" name="metaDmRecordMaps[childRowData][]">'+childRowData+'</textarea>');
                    
                    html.push('<a href="javascript:;" onclick="bpCallKpiIndicatorForm(this, this, \''+indicatorId+'\', \''+rowId+'\', \'view\');" class="font-size-14" title="'+view_btn+'">');
                        html.push('<i style="color:blue" class="far fa-file-search mr-1"></i>');
                        html.push(rowName);
                    html.push('</a>');
                    
                html.push('</td>');
                
                html.push('<td style="width: 60px" class="text-right">');
                
                    if (isAddonForm) {
                        html.push('<a href="javascript:;" onclick="kpiIndicatorRelationSubRows(this, \''+metaInfoIndicatorId+'\');" class="font-size-16 mr-3" title="Холбоос"><i style="color:#5c6bc0;" class="far fa-external-link-square"></i></a>');
                    }
                    html.push('<a href="javascript:;" onclick="kpiIndicatorRelationRemoveRows(this);" class="font-size-14 d-none" title="'+delete_btn+'"><i style="color:red" class="far fa-trash"></i></a>');
                    
                html.push('</td>');
            html.push('</tr>');
        }
    }    

    $.ajax({
      type: "post",
      url: "mdform/kpiSaveMetaDmRecordMap2",
      data: {
        mainIndicatorId: '<?php echo $this->indicatorId; ?>',
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
</script>
<?php
}
?>