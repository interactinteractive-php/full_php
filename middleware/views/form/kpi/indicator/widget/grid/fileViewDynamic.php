<style type="text/css">
.mv-cardview {
    display: inline-block;
    width: 90px;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    -ms-border-radius: 4px;
    -o-border-radius: 4px;
    border-radius: 4px;
}
.mv-cardview:hover {
    box-shadow: none;
}
.mv-cardview:hover .no-dataview {
    display: block !important;
}
.mv-cardview .card {
    margin-bottom: 0;
}
.mv-cardview .card-body .card-img {
    border-radius: 0;
    border-bottom: 1px #eee solid;
}
.mv-cardview h5 {
    display: block;
    padding: 0 10px;
    font-size: 13px;
    height: 35px;
    color: #333;
    line-height: 18px;
    text-align: center;
    height: 36px;
    overflow: hidden;
}
</style>

<div class="dv-process-buttons mb-2 d-none">
    <div class="btn-group btn-group-devided">
    <?php 
    $contextMenu = array();
    $createClickAction = '';
    $deleteClickAction = '';
    $deleteCrudId = '';
    $createCrudId = '';
    $mapId = '';
    
    foreach ($this->process as $process) {

        $srcIndicatorId = $process['structure_indicator_id'];
        $crudIndicatorId = issetParam($process['crud_indicator_id']);
        $isFillRelation = issetParam($process['is_fill_relation']);
        $isNormalRelation = issetParam($process['is_normal_relation']);
        $typeCode = $process['type_code'];
        $kpiTypeId = $process['kpi_type_id'];
        $buttonName = $className = $onClick = $description = $opt = '';

        if ($srcIndicatorId == $this->indicatorId) {

            if ($typeCode == 'create') {

                $createCrudId = $process['crud_indicator_id'];
                $mapId = issetParam($process['map_id']);
                $labelName = $process['label_name'] == 'Нэмэх' ? $this->lang->line('add_btn') : $this->lang->line($process['label_name']);
                $className = 'btn btn-success btn-circle btn-sm';
                $buttonName = '<i class="far fa-plus"></i> '.$labelName;

                if ($isNormalRelation) {
                    $onClick = "mvNormalRelationRender(this, '$kpiTypeId', '".$this->indicatorId."', {methodIndicatorId: $crudIndicatorId, structureIndicatorId: $srcIndicatorId});";
                } else {
                    $onClick = "manageKpiIndicatorValue(this, '$kpiTypeId', '".$this->indicatorId."', true, undefined, undefined, 'mvWidgetFileViewCreateCallback');";
                    $createClickAction = $onClick;
                }

            } elseif ($typeCode == 'update') {

                $labelName = $process['label_name'] == 'Засах' ? $this->lang->line('edit_btn') : $this->lang->line($process['label_name']);
                $isUpdate = true;

                if ($isFillRelation) {
                    $opt = ', {fillSelectedRow: true, mode: \'update\'}';
                } 

                $className = 'btn btn-warning btn-circle btn-sm';
                $buttonName = '<i class="far fa-edit"></i> '.$labelName;

                if ($isNormalRelation) {
                    $onClick = "mvNormalRelationRender(this, '$kpiTypeId', '".$this->indicatorId."', {methodIndicatorId: $crudIndicatorId, structureIndicatorId: $srcIndicatorId, mode: 'update'});";
                } else {
                    $onClick = "manageKpiIndicatorValue(this, '$kpiTypeId', '".$this->indicatorId."', true$opt);";
                }

                $contextMenu[] = array(
                    'crudIndicatorId' => $crudIndicatorId, 
                    'labelName' => $labelName,
                    'onClick' => $onClick,
                    'actionName' => 'edit',
                    'iconName' => 'edit', 
                    'data-actiontype' => $typeCode, 
                    'data-main-indicatorid' => $this->indicatorId, 
                    'data-structure-indicatorid' => $this->indicatorId, 
                    'data-crud-indicatorid' => $crudIndicatorId,
                    'data-mapid' => issetParam($process['map_id'])
                );

            } elseif ($typeCode == 'read') {

                $isUpdate = true;
                $className = 'btn purple btn-circle btn-sm';
                $buttonName = '<i class="fas fa-eye"></i> '.$this->lang->line('view_btn');
                $onClick = "manageKpiIndicatorValue(this, '$kpiTypeId', '".$this->indicatorId."', true, {mode: 'view'});";

                $contextMenu[] = array(
                    'crudIndicatorId' => $crudIndicatorId, 
                    'labelName' => $this->lang->line('view_btn'),
                    'onClick' => $onClick,
                    'actionName' => 'view',
                    'iconName' => 'eye', 
                    'data-actiontype' => $typeCode, 
                    'data-main-indicatorid' => $this->indicatorId, 
                    'data-structure-indicatorid' => $this->indicatorId, 
                    'data-crud-indicatorid' => $crudIndicatorId,
                    'data-mapid' => issetParam($process['map_id'])
                );

            } elseif ($typeCode == 'delete') {

                $isDelete = true;
                $className = 'btn btn-danger btn-circle btn-sm';
                $buttonName = '<i class="far fa-trash"></i> '.$this->lang->line('delete_btn');
                $onClick = "removeKpiIndicatorValue(this, '".$this->indicatorId."', 'mvWidgetFileViewDeleteCallback');";
                $deleteClickAction = $onClick;
                $deleteCrudId = $process['crud_indicator_id'];

                $contextMenu[] = array(
                    'crudIndicatorId' => $crudIndicatorId, 
                    'labelName' => $this->lang->line('delete_btn'),
                    'onClick' => $onClick,
                    'actionName' => 'delete',
                    'iconName' => 'trash', 
                    'data-actiontype' => $typeCode, 
                    'data-main-indicatorid' => $this->indicatorId, 
                    'data-structure-indicatorid' => $this->indicatorId, 
                    'data-crud-indicatorid' => $crudIndicatorId,
                    'data-mapid' => issetParam($process['map_id'])
                );

            } elseif ($typeCode == 'config') {

                $isDelete = true;
                $className = 'btn blue-steel btn-circle btn-sm';
                $buttonName = '<i class="far fa-tools"></i> '.$this->lang->line('Config');
                $onClick = "mapKpiIndicatorValue(this, '$kpiTypeId', '".$this->indicatorId."', 'config');";

                $contextMenu[] = array(
                    'crudIndicatorId' => $crudIndicatorId, 
                    'labelName' => $this->lang->line('Config'),
                    'onClick' => $onClick,
                    'actionName' => 'config',
                    'iconName' => 'tools', 
                    'data-actiontype' => $typeCode, 
                    'data-main-indicatorid' => $this->indicatorId, 
                    'data-structure-indicatorid' => $this->indicatorId, 
                    'data-crud-indicatorid' => $crudIndicatorId,
                    'data-mapid' => issetParam($process['map_id'])
                );

            } elseif ($typeCode == '360') {

                $isDelete = true;
                $className = 'btn blue-steel btn-circle btn-sm';
                $buttonName = '<i class="far fa-tools"></i> '.$this->lang->line('360');
                $onClick = "mapKpiIndicatorValue(this, '$kpiTypeId', '".$this->indicatorId."', '360');";

                $contextMenu[] = array(
                    'crudIndicatorId' => $crudIndicatorId, 
                    'labelName' => $this->lang->line('360'),
                    'onClick' => $onClick,
                    'actionName' => '360',
                    'iconName' => 'tools', 
                    'data-actiontype' => $typeCode, 
                    'data-main-indicatorid' => $this->indicatorId, 
                    'data-structure-indicatorid' => $this->indicatorId, 
                    'data-crud-indicatorid' => $crudIndicatorId,
                    'data-mapid' => issetParam($process['map_id'])
                );

            } elseif ($typeCode == 'excel') {

                $className = 'btn green btn-circle btn-sm';
                $buttonName = '<i class="far fa-file-excel"></i> '.$this->lang->line('pf_excel_import');
                $onClick = "excelImportKpiIndicatorValue(this, '".$this->indicatorId."');";

            } elseif ($typeCode == 'excel_export_one_line') {

                $className = 'btn green btn-circle btn-sm';
                $buttonName = '<i class="far fa-file-excel"></i> Эксель нэг мөрөөр татах';
                $onClick = "exportExcelOneLineKpiIndicatorValue(this, '".$this->indicatorId."');";

            } elseif ($typeCode == 'export') {

                $isDelete = true;
                $className = 'btn green btn-circle btn-sm';
                $buttonName = '<i class="far fa-download"></i> '.$this->lang->line('excel_export_btn');
                $onClick = "exportKpiIndicatorValue(this, '".$this->indicatorId."');";

            } elseif ($kpiTypeId == '1191') {

                $className = 'btn blue-steel btn-circle btn-sm';
                $buttonName = '<i class="far fa-play"></i> ' . $this->lang->line($process['label_name'] ? $process['label_name'] : $process['name']);
                $onClick = "manageKpiIndicatorValue(this, '$kpiTypeId', '$crudIndicatorId', false, {transferSelectedRow: true});";

            } elseif ($kpiTypeId == '1080') {

                $className = 'btn blue-steel btn-circle btn-sm';
                $buttonName = '<i class="far fa-play"></i> ' . $this->lang->line($process['label_name'] ? $process['label_name'] : $process['name']);
                $onClick = "callWebServiceKpiIndicatorValue(this, '$crudIndicatorId');";
            } 

        } else {

            $description = $this->lang->line(issetParam($process['description']));
            $processName = $this->lang->line(issetParam($process['label_name']));
            $isDfillRelation = issetParam($process['is_dfill_relation']);

            if ($typeCode == 'create') {

                $className = 'btn btn-success btn-circle btn-sm';
                $buttonName = '<i class="far fa-plus"></i> '.$processName;
                $createCrudId = $process['crud_indicator_id'];
                $mapId = issetParam($process['map_id']);

                if ($isFillRelation) {
                    $opt = ', {fillSelectedRow: true, mode: \'create\'}';
                } elseif ($isDfillRelation) {
                    $opt = ', {fillDynamicSelectedRow: true, mode: \'create\'}';
                }

                if ($isNormalRelation) {
                    $onClick = "mvNormalRelationRender(this, '$kpiTypeId', '".$this->indicatorId."', {methodIndicatorId: $crudIndicatorId, structureIndicatorId: $srcIndicatorId});";
                } else {
                    $onClick = "manageKpiIndicatorValue(this, '$kpiTypeId', '".$srcIndicatorId."', true, undefined, undefined, 'mvWidgetFileViewCreateCallback');";
                    $createClickAction = $onClick;
                }

            } elseif ($typeCode == 'update') {

                $className = 'btn btn-warning btn-circle btn-sm';
                $buttonName = '<i class="far fa-edit"></i> '.$processName;

                if ($isFillRelation) {
                    $opt = ', {fillSelectedRow: true, mode: \'update\'}';
                } elseif ($isDfillRelation) {
                    $opt = ', {fillDynamicSelectedRow: true, mode: \'update\'}';
                }

                if ($isNormalRelation) {
                    $onClick = "mvNormalRelationRender(this, '$kpiTypeId', '".$this->indicatorId."', {methodIndicatorId: $crudIndicatorId, structureIndicatorId: $srcIndicatorId, mode: 'update'});";
                } else {
                    $onClick = "manageKpiIndicatorValue(this, '$kpiTypeId', '$srcIndicatorId', true$opt);";
                }

            } elseif ($typeCode == 'read') {

                $className = 'btn purple btn-circle btn-sm';
                $buttonName = '<i class="far fa-eye"></i> '.$processName;

                if ($isFillRelation) {
                    $opt = ', {fillSelectedRow: true, mode: \'view\'}';
                } elseif ($isDfillRelation) {
                    $opt = ', {fillDynamicSelectedRow: true, mode: \'view\'}';
                } else {
                    $opt = ', {mode: \'view\'}';
                }

                $onClick = "manageKpiIndicatorValue(this, '$kpiTypeId', '$srcIndicatorId', true$opt);";

            } elseif ($typeCode == 'delete') {

                $className = 'btn btn-danger btn-circle btn-sm';
                $buttonName = '<i class="far fa-trash"></i> '.$processName;
                $onClick = "removeKpiIndicatorValue(this, '$srcIndicatorId', 'mvWidgetFileViewDeleteCallback');";
                $deleteClickAction = $onClick;
                
                $deleteCrudId = $process['crud_indicator_id'];

            } elseif ($typeCode == 'excel') {

                $className = 'btn green btn-circle btn-sm';
                $buttonName = '<i class="far fa-file-excel"></i> '.$processName;
                $onClick = "excelImportKpiIndicatorValue(this, '$srcIndicatorId');";
            }
        }

        echo html_tag('a', 
            array( 
                'href' => 'javascript:;', 
                'class' => $className, 
                'data-qtip-title' => $description, 
                'data-qtip-pos' => 'top', 
                'onclick' => $onClick, 
                'data-actiontype' => $typeCode, 
                'data-main-indicatorid' => $this->indicatorId, 
                'data-structure-indicatorid' => $this->indicatorId, 
                'data-crud-indicatorid' => $crudIndicatorId,
                'data-mapid' => issetParam($process['map_id'])
            ), 
            $buttonName, true
        );
    } ?>
    </div>        
</div>        
        
<?php                                            
$dataResult = $this->response['rows'];
// if (!isset($dataResult[0]['C9_DESC'])) {
//     die();
// }
$dataResult = Arr::groupByArrayByNullKey($dataResult, $this->relationViewConfig['position1']); 

foreach ($dataResult as $groupName => $groupRow) {
    if ($groupName != 'яяяrow') {
?>
    <div class="mb-2" style="font-weight: bold;">        
        <?php 
            echo $groupName . (issetParam($groupRow['row'][issetParam($this->relationViewConfig['position5'])]) ? '<div style="font-weight: normal;color: #a9a9a9;font-size: 11px;">'.$groupRow['row'][$this->relationViewConfig['position5']].'</div>' : ''); 
        ?>            
    </div>
    <div class="d-flex flex-wrap">
    <?php
    foreach ($groupRow['rows'] as $row) {
        $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
        if (issetParam($row[$this->relationViewConfig['position4']])) {
    ?>
    <a href="javascript:;" class="mv-cardview" title="<?php echo issetParam($row[$this->relationViewConfig['position2']]); ?>">
        <div class="no-dataview" data-crud-indicatorid="<?php echo $deleteCrudId ?>" data-rowdata="<?php echo $rowJson; ?>" style="position: absolute;z-index: 10;margin-left: 80px;display: none" onclick="<?php echo $deleteClickAction ?>">
            <i class="far fa-times" style="font-size: 13px;color: red;"></i>
        </div>
        <div class="card" style="border: none;box-shadow: none;">
            <div class="card-body">
                <div class="card-img-actions mb-2 mt-2" onclick="bpFilePreview(this);" data-fileurl="<?php echo $row[$this->relationViewConfig['position4']] ?>" data-filename="<?php echo issetParam($row[$this->relationViewConfig['position2']]) ?>" data-extension="<?php echo $row[$this->relationViewConfig['position3']] ?>">
                    <img class="directory-img ml20" style="height: 70px;" src="assets/core/global/img/document/big/pdf2.png"/>
                </div>
                <h5>
                    <?php echo issetParam($row[$this->relationViewConfig['position2']]); ?>
                </h5>
            </div>
        </div>
    </a>
    <?php
        }
    } 
    ?>
    <a href="javascript:;" onclick="<?php echo $createClickAction ?>" data-list-relation="1" data-rowdata="<?php echo htmlentities(json_encode($groupRow['row'], JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>" data-mapid="<?php echo $mapId ?>" data-main-indicatorid="<?php echo $this->indicatorId; ?>" data-crud-indicatorid="<?php echo $createCrudId ?>" class="mv-cardview no-dataview" style="margin-bottom: 30px;background-color: #eaeaea;height: 70px;width: 60px;margin-top: 10px;margin-left: 20px;">
        <div class="card" style="border: none;box-shadow: none;">
            <div class="card-body">
                <i style="font-size: 26px;position: absolute;margin-top: 20px;margin-left: 18px;color:#ccc" class="fa fa-plus"></i>
            </div>
        </div>
    </a>    
    <?php 
    }
    echo '</div>';
}
?>

<script type="text/javascript">
function mvCustomCardMoreView(elem, indicatorId, rowId, title) {
    $.ajax({
        type: 'post',
        url: 'mdform/renderCustomMoreView',
        data: {indicatorId: indicatorId, rowId: rowId},
        success: function(content) {
            appMultiTabByContent({ metaDataId: indicatorId+'_'+rowId, title: title, type: 'newprocess', content: content });
        }
    });
}    
function mvWidgetFileViewDeleteCallback(elem) {
    elem.parent().remove();
}
function mvWidgetFileViewCreateCallback() {
    $('.mv_checklist_02_sub.active').trigger('click');
}
</script>