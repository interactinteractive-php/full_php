<div class="center-sidebar overflow-hidden content">
    <div class="row">
        
        <?php
        if (!isset($this->isIgnoreFilter) && false) {
        ?>
        <div class="col-md-auto pl-0">
            <div class="kpidv-data-filter-col pr-1"></div>
        </div>
        <?php
        }
        ?>
        <div class="col right-sidebar-content-for-resize content-wrapper pl-0 pr-0 overflow-hidden">
            <div class="row">
                <div class="col-md-12">
                    
                    <?php
                    if (!isset($this->isBasket) && Input::numeric('isIgnoreTitle') != '1') {
                    ?>
                    <div class="text-uppercase font-weight-bold mt-0 mb-2">
                        <?php echo $this->title; ?>
                    </div>
                    <?php
                    }
                    ?>
                    
                    <div class="table-toolbar">
                        <div class="d-flex">
                            <div class="col p-0">
                                <div class="dv-process-buttons">
                                    <div class="btn-group btn-group-devided">
                                        <?php
                                        if ($this->isDataMart) {
                                            
                                            echo html_tag('a', 
                                                array(
                                                    'class' => 'btn btn-success btn-circle btn-sm', 
                                                    'onclick' => "generateKpiDataMart(this, '".$this->indicatorId."');", 
                                                    'data-actiontype' => 'generateDataMart', 
                                                    'href' => 'javascript:;'
                                                ), 
                                                '<i class="far fa-database"></i> Датамарт бэлдэх', true
                                            ); 
                                            
                                            echo html_tag('a', 
                                                array(
                                                    'class' => 'btn btn-success btn-circle btn-sm', 
                                                    'onclick' => "generateDataMartSqlView(this, '".$this->indicatorId."');", 
                                                    'data-actiontype' => 'generateDataMartSqlView', 
                                                    'href' => 'javascript:;'
                                                ), 
                                                '<i class="far fa-database"></i> SQL харах', true
                                            );

                                        } elseif ($this->isRawDataMart) {
                                            
                                            echo html_tag('a', 
                                                array(
                                                    'class' => 'btn btn-success btn-circle btn-sm', 
                                                    'onclick' => "generateKpiRawDataMart(this, '".$this->indicatorId."');", 
                                                    'data-actiontype' => 'generateRawDataMart', 
                                                    'href' => 'javascript:;'
                                                ), 
                                                '<i class="far fa-database"></i> Датамарт бэлдэх', true
                                            ); 
                                            
                                        } elseif ($this->isCheckQuery) {
                                            
                                            echo html_tag('a', 
                                                array(
                                                    'class' => 'btn btn-success btn-circle btn-sm', 
                                                    'onclick' => "mvExecuteCheckQuery(this, '".$this->indicatorId."');", 
                                                    'data-actiontype' => 'executeCheckQuery', 
                                                    'href' => 'javascript:;'
                                                ), 
                                                '<i class="far fa-database"></i> Run check query', true
                                            ); 
                                            
                                            echo html_tag('a', 
                                                array(
                                                    'class' => 'btn btn-success btn-circle btn-sm', 
                                                    'onclick' => "mvExecuteFixQuery(this, '".$this->indicatorId."');", 
                                                    'data-actiontype' => 'executeFixQuery', 
                                                    'href' => 'javascript:;'
                                                ), 
                                                '<i class="far fa-database"></i> Run fix query', true
                                            );

                                        } else {
                                            
                                            $contextMenu = array();
                                            
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
                                                        
                                                        $labelName = $process['label_name'] == 'Нэмэх' ? $this->lang->line('add_btn') : $this->lang->line($process['label_name']);
                                                        $className = 'btn btn-success btn-circle btn-sm';
                                                        $buttonName = '<i class="far fa-plus"></i> '.$labelName;
                                                        
                                                        if ($isNormalRelation) {
                                                            $onClick = "mvNormalRelationRender(this, '$kpiTypeId', '".$this->indicatorId."', {methodIndicatorId: $crudIndicatorId, structureIndicatorId: $srcIndicatorId});";
                                                        } else {
                                                            $onClick = "manageKpiIndicatorValue(this, '$kpiTypeId', '".$this->indicatorId."', false);";
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
                                                        $onClick = "removeKpiIndicatorValue(this, '".$this->indicatorId."');";
                                                        
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
                                                        
                                                        if ($isFillRelation) {
                                                            $opt = ', {fillSelectedRow: true, mode: \'create\'}';
                                                        } elseif ($isDfillRelation) {
                                                            $opt = ', {fillDynamicSelectedRow: true, mode: \'create\'}';
                                                        }
                                                        
                                                        $onClick = "manageKpiIndicatorValue(this, '$kpiTypeId', '$srcIndicatorId', false$opt);";
                                                        
                                                    } elseif ($typeCode == 'update') {
                                                        
                                                        $className = 'btn btn-warning btn-circle btn-sm';
                                                        $buttonName = '<i class="far fa-edit"></i> '.$processName;
                                                        
                                                        if ($isFillRelation) {
                                                            $opt = ', {fillSelectedRow: true, mode: \'update\'}';
                                                        } elseif ($isDfillRelation) {
                                                            $opt = ', {fillDynamicSelectedRow: true, mode: \'update\'}';
                                                        }
                                                        
                                                        $onClick = "manageKpiIndicatorValue(this, '$kpiTypeId', '$srcIndicatorId', true$opt);";
                                                        
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
                                                        $onClick = "removeKpiIndicatorValue(this, '$srcIndicatorId');";
                                                        
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
                                            }
                                            
                                            echo html_tag('a', 
                                                array(
                                                    'class' => 'btn green btn-circle btn-sm', 
                                                    'onclick' => "callWebServiceKpiIndicatorValue(this, '".$this->indicatorId."');", 
                                                    'data-actiontype' => 'callwebservice', 
                                                    'href' => 'javascript:;'
                                                ), 
                                                'Call service', $this->isCallWebService
                                            );
                                            
                                            if ($this->isPrint) {
                                                
                                                echo html_tag('a', 
                                                    array(
                                                        'class' => 'btn green btn-circle btn-sm', 
                                                        'onclick' => "reportTemplateKpiIndicatorValue(this, '".$this->indicatorId."');", 
                                                        'data-actiontype' => 'reporttemplate', 
                                                        'href' => 'javascript:;'
                                                    ), 
                                                    '<i class="far fa-print"></i> Хэвлэх'
                                                );
                                            }
                                            
                                            if ($this->isUseWorkflow) {
                                                
                                                echo '<div class="btn-group workflow-btn-group-'.$this->indicatorId.'">
                                                    <button type="button" class="btn btn-sm blue btn-circle dropdown-toggle workflow-btn-'.$this->indicatorId.'" data-toggle="dropdown"><i class="far fa-cogs"></i> '.$this->lang->line('change_workflow').'</button>
                                                    <ul class="dropdown-menu workflow-dropdown-'.$this->indicatorId.'" role="menu"></ul>
                                                </div>';
                                            }
                                            
                                            echo html_tag('a', 
                                                array(
                                                    'class' => 'btn btn-success btn-circle btn-sm', 
                                                    'onclick' => "renderIframeIndicator(this);", 
                                                    'href' => 'javascript:;'
                                                ), 
                                                '<i class="far fa-database"></i> iFrame', false
                                            ); 
                                        }
                                        
                                        if (isset($this->isImportManage) && $this->isImportManage) {
                                            
                                            echo html_tag('a', 
                                                array(
                                                    'class' => 'btn btn-info btn-circle btn-sm', 
                                                    'onclick' => 'mvImportManageFieldsConfig(this, \''.$this->indicatorId.'\', \''.$this->mainIndicatorId.'\');', 
                                                    'href' => 'javascript:;'
                                                ), 
                                                '<i class="far fa-cogs"></i> Талбарын тохиргоо', true
                                            ); 
                                            
                                            echo html_tag('a', 
                                                array(
                                                    'class' => 'btn btn-warning btn-circle btn-sm', 
                                                    'onclick' => 'mvImportManageDataCheck(this, \''.$this->indicatorId.'\', \''.$this->mainIndicatorId.'\');', 
                                                    'href' => 'javascript:;'
                                                ), 
                                                '<i class="far fa-check"></i> Шалгах', true
                                            ); 
                                            
                                            echo html_tag('a', 
                                                array(
                                                    'class' => 'btn btn-success btn-circle btn-sm', 
                                                    'onclick' => 'mvImportManageDataUpdate(this, \''.$this->indicatorId.'\', \''.$this->mainIndicatorId.'\');', 
                                                    'href' => 'javascript:;'
                                                ), 
                                                '<i class="far fa-database"></i> Update', true
                                            ); 
                                            
                                            echo html_tag('a', 
                                                array(
                                                    'class' => 'btn btn-success btn-circle btn-sm', 
                                                    'onclick' => 'mvImportManageDataCommit(this, \''.$this->indicatorId.'\', \''.$this->mainIndicatorId.'\');', 
                                                    'href' => 'javascript:;'
                                                ), 
                                                '<i class="far fa-database"></i> Commit', true
                                            ); 
                                        ?>
                                        <div class="mv-imp-manage-info d-inline-block ml-4"></div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php    
                            if (Input::numeric('isIgnoreRightTools') != 1) {
                            ?>
                            <div class="dv-right-tools-btn ml-2 text-right">
                                <div class="btn-group btn-group-devided">
                                    <?php if ($this->relationComponentsOther) { ?>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="icon-stack2"></i></button>
                                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-75px, 36px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <?php echo Html::anchor(
                                                        'javascript:;', '<i class="far fa-list"></i> Жагсаалт', array(
                                                        'class' => 'dropdown-item',
                                                        'title' => 'Calendar',     
                                                        'onclick' => 'kpiIndicatorViewList_'.$this->indicatorId.'(this, \''.$this->indicatorId.'\');'
                                                    ), true  
                                                );  ?>
                                                <?php echo Html::anchor(
                                                        'javascript:;', '<i class="far fa-calendar"></i> Calendar', array(
                                                        'class' => 'dropdown-item',
                                                        'title' => 'Calendar',     
                                                        'onclick' => 'kpiIndicatorViewCalendar_'.$this->indicatorId.'(this, \''.$this->indicatorId.'\');'
                                                    ), true  
                                                );  ?>
                                            </div>
                                        </div>
                                    <?php }
                                    echo Html::anchor(
                                            'javascript:;', '<i class="far fa-file-excel"></i>', array(
                                            'class' => 'btn btn-secondary btn-circle btn-sm default',
                                            'title' => 'Excel гаргах',     
                                            'onclick' => 'excelExportKpiIndicatorValue(this, \''.$this->indicatorId.'\');'
                                        ), true  
                                    ); 
                                    
                                    echo Html::anchor(
                                            'javascript:;', '<i class="far fa-cube"></i>', array(
                                            'class' => 'btn btn-secondary btn-circle btn-sm default',
                                            'title' => 'Pivot view',     
                                            'onclick' => 'pivotKpiIndicatorValue(this, \''.$this->indicatorId.'\');'
                                        ), (defined('CONFIG_PIVOT_SERVICE_ADDRESS') && CONFIG_PIVOT_SERVICE_ADDRESS)  
                                    ); 
                                    
                                    echo Html::anchor(
                                            'javascript:;', '<i class="far fa-map-marker"></i>', array(
                                            'class' => 'btn btn-secondary btn-circle btn-sm default',
                                            'title' => 'Google map',
                                            'onclick' => 'googleMapKpiIndicatorValue(this, \''.$this->indicatorId.'\', \''.$this->coordinateField.'\');'
                                        ), $this->coordinateField ? true : false
                                    );
                                    ?>
                                </div>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>    
                <div class="col-md-12 div-objectdatagrid-<?php echo $this->subGridUniqId; ?> jeasyuiTheme3">
                    
                    <?php
                    if (isset($this->isBasket)) {
                    ?>
                    <table id="objectdatagrid-<?php echo $this->subGridUniqId; ?>" style="height: 400px"></table>
                    <?php
                    } else {
                    ?>
                    <table id="objectdatagrid-<?php echo $this->subGridUniqId; ?>"></table>
                    <?php
                    }
                    ?>
                    <div id="md-map-canvas-<?php echo $this->subGridUniqId; ?>" style="display: none"></div>
                </div>
            </div>    
        </div>     
    </div>    
</div>  

<style type="text/css">
.kpidv-data-filter-col {
    width: 240px;
    border-right: 1px solid #ddd;
    overflow-x: hidden;
    overflow-y: auto;
}
.kpidv-data-filter-col .list-group {
    border: none;
    padding: 0;
}
.kpidv-data-filter-col .list-group-item {
    padding: 0.28rem 0;
}
.kpidv-data-filter-col .list-group-item.active {
    color: rgba(51,51,51,.85);
    background-color: rgba(93, 173, 226, 0.3);
    border-color: rgba(93, 173, 226, 0.3);
}
</style>

<script type="text/javascript">
var isIgnoreWfmHistory_<?php echo $this->subGridUniqId; ?> = false;
var isGoogleMapView_<?php echo $this->subGridUniqId; ?> = false;
var isFilterShowData_<?php echo $this->subGridUniqId; ?> = <?php echo ($this->isFilterShowData == '1' ? 'true' : 'false'); ?>;
var idField_<?php echo $this->subGridUniqId; ?> = '<?php echo $this->idField; ?>';
var indicatorName_<?php echo $this->subGridUniqId; ?> = '<?php echo $this->title; ?>';
var objectdatagrid_<?php echo $this->subGridUniqId; ?> = $('#objectdatagrid-<?php echo $this->subGridUniqId; ?>');
var drillDownCriteria_<?php echo $this->subGridUniqId; ?> = '<?php echo $this->drillDownCriteria; ?>';
var dynamicHeight = 0;

if (typeof isKpiIndicatorScript === 'undefined') {
    $.cachedScript('<?php echo autoVersion('middleware/assets/js/addon/indicator.js'); ?>');
}

setTimeout(function() {

dynamicHeight = $(window).height() - objectdatagrid_<?php echo $this->subGridUniqId; ?>.offset().top - 40;

if (dynamicHeight < 230) {
    dynamicHeight = 350;
}

if (objectdatagrid_<?php echo $this->subGridUniqId; ?>.closest('.package-tab').length) {
    dynamicHeight = 'auto';
}

<?php
if (Input::numeric('isDrilldown') == '1') {
?>
dynamicHeight = dynamicHeight - 50;
<?php
} elseif ($dynamicHeight = Input::numeric('dynamicHeight')) {
?>
dynamicHeight = <?php echo $dynamicHeight; ?>;
<?php
}
?>

objectdatagrid_<?php echo $this->subGridUniqId; ?>.attr('height', dynamicHeight); 

<?php
if (!isset($this->isIgnoreFilter) && false) {
?>
filterKpiIndicatorValueForm(<?php echo $this->indicatorId; ?>);
<?php
}
?>

$(function() {
    
    objectdatagrid_<?php echo $this->subGridUniqId; ?>.<?php echo $this->isGridType; ?>({
        <?php
        if (!$this->isTreeGridData && !$this->subgrid) {
        ?>
            view: horizonscrollview,
        <?php
        } elseif ($this->subgrid) { 
        ?>
            view: detailview,
        <?php
        }
        ?>
        url: 'mdform/indicatorDataGrid',
        method: 'post',
        queryParams: {
            indicatorId: '<?php echo $this->indicatorId; ?>', 
            treeConfigs: '<?php echo $this->isTreeGridData; ?>', 
            ignoreFirstLoad: isFilterShowData_<?php echo $this->subGridUniqId; ?>, 
            drillDownCriteria: drillDownCriteria_<?php echo $this->subGridUniqId; ?>, 
            postHiddenParams: '<?php echo $this->postHiddenParams; ?>', 
            hiddenParams: '<?php echo $this->hiddenParams; ?>', 
            filter: '<?php echo $this->filter; ?>'
        }, 
        <?php
        echo $this->subgrid;
        if ($this->isTreeGridData) {
            echo "idField: '".$this->idField."',"."\n"; 
            echo "treeField: '".$this->nameField."',"."\n";
        }
        ?>
        resizeHandle: 'right',
        fitColumns: false,
        autoRowHeight: true,
        striped: false,
        nowrap: true,
        showHeader: true,
        showFooter: true,
        loadMsg: 'Түр хүлээнэ үү',
        pagination: true,
        rownumbers: true,
        singleSelect: false,
        ctrlSelect: true,
        checkOnSelect: true,
        selectOnCheck: true,
        pagePosition: 'bottom',
        pageNumber: 1,
        pageSize: 50,
        pageList: [50,100,200,300,500], 
        remoteFilter: true,
        multiSort: false,
        remoteSort: true,
        scrollbarSize: 18,
        filterDelay: 10000000000,
        clickToEdit: false, 
        <?php
        if (isset($this->row['gridOption'])) {
            foreach ($this->row['gridOption'] as $optName => $optVal) {
                
                if ($optName == 'nowrap') { 
                    echo 'nowrap: ' . (is_bool($optVal) ? json_encode($optVal) : $optVal) . ', ';
                }
            }
        }
        ?> 
        frozenColumns: [
            [{field: 'ck', rowspan:1, checkbox: true }]
        ],
        columns: [
            <?php echo $this->columns['comboColumnsRender']; ?> 
            [<?php echo $this->columns['columnsRender']; ?>]
        ],
        onSelectAll: function() {
            dvSelectionCountToFooter_<?php echo $this->indicatorId; ?>();
        }, 
        onUnselectAll: function() {
            dvSelectionCountToFooter_<?php echo $this->indicatorId; ?>();
        }, 
        onUnselect: function() {
            dvSelectionCountToFooter_<?php echo $this->indicatorId; ?>();
        },
        onSelect: function(index, row) {
            dvSelectionCountToFooter_<?php echo $this->indicatorId; ?>();
        },   
        <?php
        if (isset(Mdform::$gridStyler['row'])) {
        ?>
        rowStyler: function(index, row) {
            <?php echo Mdform::$gridStyler['row']; ?>
        },        
        <?php 
        }
        if (isset($this->isBasket)) {
            
            if ($this->isTreeGridData) {
                echo 'onDblClickRow:function(row) {'."\n";
            } else {
                echo 'onDblClickRow:function(index, row) {'."\n";
            } 
            ?>
            dblClickCommonSelectableDataGrid_<?php echo $this->indicatorId; ?>(row);
        },       
                
        <?php
        }
        if ($this->isTreeGridData) {
        ?>
        onContextMenu: function (e, row) {
            e.preventDefault();
            
            <?php
            if (!isset($this->isBasket)) {
            ?>
            $(this).treegrid('unselectAll');
            <?php
            }
            ?>
            
            $(this).treegrid('select', row.<?php echo $this->idField; ?>);   
        <?php
        } else {
        ?>
        onRowContextMenu: function (e, index, row) {
            e.preventDefault();
            
            <?php
            if (!isset($this->isBasket)) {
            ?>
            $(this).datagrid('unselectAll');
            <?php
            }
            ?>
            
            $(this).datagrid('selectRow', index);
        <?php
        }
        ?>
            <?php
            if (!isset($this->isBasket) && !$this->isDataMart && isset($contextMenu) && $contextMenu) {
                
                $menuCallBack = $menuItems = '';
                
                foreach ($contextMenu as $menu) {
                    
                    $menu['onClick'] = str_replace('this', '$a', $menu['onClick']);
                    
                    $menuCallBack .= 'if (key === \''.$menu['crudIndicatorId'].'_'.$menu['data-actiontype'].'\') { ';
                        
                        $menuCallBack .= 'var $a = $(\'<a />\'); ';
                        $menuCallBack .= '$a.attr(\'data-actiontype\', \''.$menu['data-actiontype'].'\')';
                        $menuCallBack .= '.attr(\'data-main-indicatorid\', \''.$menu['data-main-indicatorid'].'\')';
                        $menuCallBack .= '.attr(\'data-structure-indicatorid\', \''.$menu['data-structure-indicatorid'].'\')';
                        $menuCallBack .= '.attr(\'data-crud-indicatorid\', \''.$menu['data-crud-indicatorid'].'\')';
                        $menuCallBack .= '.attr(\'data-mapid\', \''.$menu['data-mapid'].'\'); ';
                        
                        $menuCallBack .= $menu['onClick'];
                    $menuCallBack .= '} ';
                    
                    $menuItems .= '"'.$menu['crudIndicatorId'].'_'.$menu['data-actiontype'].'": {name: \''.$menu['labelName'].'\', icon: \''.$menu['iconName'].'\'}, ';
                }
            ?>
            $.contextMenu({
                selector: 'div#object-value-list-<?php echo $this->subGridUniqId; ?> .datagrid .datagrid-view .datagrid-view1 .datagrid-body .datagrid-row, div#object-value-list-<?php echo $this->subGridUniqId; ?> .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row',
                callback: function (key, opt) {
                    <?php echo $menuCallBack; ?>
                },
                items: {
                    <?php echo $menuItems; ?> 
                }
            });
            <?php
            } elseif (isset($this->isBasket)) {
            ?>
            $.contextMenu({
                selector: 'div#object-value-list-<?php echo $this->subGridUniqId; ?> .datagrid .datagrid-view .datagrid-view1 .datagrid-body .datagrid-row, div#object-value-list-<?php echo $this->subGridUniqId; ?> .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row',
                callback: function(key, opt) {
                    if (key === 'basket') {
                        basketCommonSelectableDataGrid_<?php echo $this->indicatorId; ?>();
                    }
                },
                items: {
                    "basket": {name: "<?php echo $this->lang->line('META_00042'); ?>", icon: "plus-circle"}
                }
            });
            <?php
            }
            ?>
        },       
        <?php
        if ($this->isTreeGridData) {
        ?>
        onBeforeLoad: function(row, param) { 
            if (!row) {   
                delete param.id;
            }
        },
        onLoadSuccess: function(row, data) {
        <?php
        } else {
        ?>
        onBeforeLoad: function(param) { 
            <?php
            if (isset($this->isImportManage) && $this->isImportManage) {
            ?>
            var $panelView = objectdatagrid_<?php echo $this->subGridUniqId; ?>.datagrid('getPanel').children('div.datagrid-view');
            Core.initSelect2($panelView.find('.datagrid-view2 .datagrid-header-row:eq(0)'));
            <?php
            }
            ?>
        },
        onLoadSuccess: function(data) {
        <?php
        }
        ?>

            var _thisGrid = objectdatagrid_<?php echo $this->subGridUniqId; ?>;
            var parentDgIndex = _thisGrid.closest('.dv-subgrid').closest('tr').index();
            _thisGrid.closest('.dv-subgrid').closest('.datagrid-view').find('.datagrid-view1').find('table.datagrid-btable > tbody > tr:eq('+parentDgIndex+')').css('height', (dynamicHeight+110)+'px');

            if (data.status === 'error' && data.message != '') {
                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: data.message,
                    type: 'error',
                    addclass: pnotifyPosition,
                    sticker: false
                });
            }
            
            <?php
            if ($this->isTreeGridData) {
            ?>
            showTreeGridMessage(_thisGrid, '');
            <?php
            } else {
            ?>
            showGridMessage(_thisGrid, '');
            <?php
            }
            ?>

            var $panelView = _thisGrid.datagrid('getPanel').children('div.datagrid-view');
            var $panelFilterRow = $panelView.find('.datagrid-filter-row');

            if (_thisGrid.datagrid('getRows').length == 0) {
                var $tr = $panelView.find(".datagrid-view2").find(".datagrid-footer").find(".datagrid-footer-inner table").find("tbody tr");
                $tr.find('td').find('div').find('span').each(function () {
                    this.remove();
                });
            } else {
                <?php
                if ($this->columns['mergeColumns']) {
                ?>
                var isMergeColumn = <?php echo json_encode($this->columns['mergeColumns']); ?>;        
                _thisGrid.datagrid('autoMergeCells', isMergeColumn);
                <?php
                }
                ?>
            }

            $('div.div-objectdatagrid-<?php echo $this->indicatorId; ?>').find("input.datagrid-filter[data-filter='1']").removeAttr('data-filter');
                                    
            if ($panelFilterRow.length) {
                Core.initNumberInput($panelFilterRow);
                Core.initDateInput($panelFilterRow);
                Core.initDateTimeInput($panelFilterRow);
                Core.initDateMaskInput($panelFilterRow);
                Core.initDateMinuteMaskInput($panelFilterRow);
                Core.initTimeInput($panelFilterRow);
                Core.initAccountCodeMask($panelFilterRow);
                Core.initStoreKeeperKeyCodeMask($panelFilterRow);
            }

            initDVClearColumnFilterBtn($panelView, $panelFilterRow);    

            /*dvReloadFooterData(_thisGrid, dvLoadSuccessData_1642386237438218);*/
            _thisGrid.datagrid('resize'); 
        }
    });
    
    objectdatagrid_<?php echo $this->subGridUniqId; ?>.datagrid('getPager').pagination({
        showPageList: true,
        layout: ['list','sep','first','prev','sep','manual','sep','next','last','sep','refresh','info'],
    });

    objectdatagrid_<?php echo $this->subGridUniqId; ?>.datagrid('enableFilter');
    
    $(window).bind('resize', function() {
        var $dvElem = $('body').find('#object-value-list-<?php echo $this->subGridUniqId; ?>');
        if ($dvElem.length > 0 && $dvElem.is(':visible') && $dvElem.find('.panel-eui').length) {
            objectdatagrid_<?php echo $this->subGridUniqId; ?>.datagrid('resize');
        }
    });
    
    $('.workflow-btn-<?php echo $this->indicatorId ?>').on('click', function (e, type) {
        wfmstatusRender_<?php echo $this->indicatorId ?>(e, type);
    });
    
});

}, 200);
        
function dvSelectionCountToFooter_<?php echo $this->indicatorId; ?>() {
    var $panelView = objectdatagrid_<?php echo $this->subGridUniqId; ?>.datagrid('getPanel');    
    if ($panelView.find(".datagrid-pager").length) {
        if ($panelView.find(".datagrid-pager").find('tbody > tr:eq(0)').find('.custom-selected-counter').length) {
            $panelView.find(".datagrid-pager").find('tbody > tr:eq(0)').find('.custom-selected-counter').remove();
        }
        var rows = window['objectdatagrid_<?php echo $this->indicatorId ?>'].datagrid('getSelections');
        $panelView.find(".datagrid-pager").find('tbody > tr:eq(0)').append('<td class="custom-selected-counter"><div class="pagination-btn-separator"></div></td><td class="custom-selected-counter pl6">'+plang.get('has_chosen')+': '+rows.length+'</td>');
    }
}

function filterKpiIndicatorValueForm(indicatorId) {
    var drillDownCriteria = window['drillDownCriteria_<?php echo $this->subGridUniqId ?>'];
    
    $.ajax({
        type: 'post',
        url: 'mdform/filterKpiIndicatorValueForm',
        data: {indicatorId: indicatorId, drillDownCriteria: drillDownCriteria},
        dataType: 'json',
        success: function(data) {
            
            var $filterCol = $('#object-value-list-<?php echo $this->subGridUniqId ?> .kpidv-data-filter-col');
            
            if (data.status == 'success' && data.html != '') {
                
                $filterCol.css('height', dynamicHeight + 47);
                
                $filterCol.append(data.html).promise().done(function() {
                    Core.initNumberInput($filterCol);
                    Core.initLongInput($filterCol);
                    Core.initDateInput($filterCol);
                    Core.initSelect2($filterCol);
                });
                
            } else {
                $filterCol.closest('.col-md-auto').remove();
                objectdatagrid_<?php echo $this->subGridUniqId; ?>.datagrid('resize');
                console.log(data);
            }
        }
    });
}
function filterKpiIndicatorValueGrid(elem) {
    
    var getFilterData = getKpiIndicatorFilterData(elem);
    var indicatorId = getFilterData.indicatorId;
    var filterData = getFilterData.filterData;
    
    window['isFilterShowData_<?php echo $this->subGridUniqId ?>'] = false; 
    
    var op = objectdatagrid_<?php echo $this->subGridUniqId; ?>.datagrid('options');
    var queryParams = op.queryParams;
    
    queryParams.filterData = filterData;
    queryParams.ignoreFirstLoad = window['isFilterShowData_<?php echo $this->subGridUniqId ?>'];
    
    if (op.idField === null) {
        objectdatagrid_<?php echo $this->subGridUniqId; ?>.datagrid('load', queryParams);
    } else {
        objectdatagrid_<?php echo $this->subGridUniqId; ?>.treegrid('load', queryParams);
    }
    
    mvFilterRelationLoadData(elem, indicatorId, filterData);
}

function wfmstatusRender_<?php echo $this->indicatorId ?>(e, type, isIgnoreAlert) {
    var $workflowDropdown = $('.workflow-dropdown-<?php echo $this->indicatorId ?>');
    $workflowDropdown.empty();

    var rows = getDataViewSelectedRows('<?php echo $this->indicatorId ?>');

    if (rows.length === 0) {
        if (typeof isIgnoreAlert == 'undefined') {
            $workflowDropdown.dropdown('toggle');
            alert(plang.get('msg_pls_list_select'));
        }
        return;
    }

    var row = rows[0], wfmActions = [], isManyRows = '';

    if (rows.length > 1) {
        row = rows;
        isManyRows = '1';
    } 

    $.ajax({
        type: 'post',
        url: 'mdobject/getWorkflowNextStatus',
        data: {metaDataId: '<?php echo $this->indicatorId; ?>', dataRow: row, isManyRows: isManyRows, isIndicator: 1},
        dataType: 'json',
        async: false,
        success: function(response) {
            PNotify.removeAll();
            
            if (response.status === 'success') {

                if (response.datastatus && response.data) {
                    var rowId = '', realWfmName = '', advancedCriteria = '', wfmIcon = '';

                    if (typeof row.id !== 'undefined') {
                        rowId = row.id;
                    }

                    $.each(response.data, function (i, v) {

                        advancedCriteria = '';
                        wfmStatusIcon = '';

                        if (typeof v.advancedCriteria !== "undefined" && v.advancedCriteria !== null) {
                            advancedCriteria = ' data-advanced-criteria="' + v.advancedCriteria.replace(/\"/g, '') + '"';
                        }

                        realWfmName = v.wfmstatusname;
                        if (typeof v.wfmstatusname != 'undefined' && typeof v.processname != 'undefined' && v.processname != '') {
                            v.wfmstatusname = v.processname;
                        }

                        if (v.wfmstatusicon) {
                            wfmIcon = '<i class="fa '+v.wfmstatusicon+'"></i> ';
                        }

                        if (isManyRows !== '') {

                            if (typeof v.usedescriptionwindow != 'undefined' && !v.usedescriptionwindow && typeof v.wfmuseprocesswindow != 'undefined' && !v.wfmuseprocesswindow) {
                                $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\', \'\', \'\', \'\', '+ undefined +', '+ undefined +', \''+ isManyRows +'\', \'\');">'+wfmIcon + v.wfmstatusname +'</a></li>'); 
                                wfmActions.push({icon: wfmIcon, action:'changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\', \'\', \'\', \'\', '+ undefined +', '+ undefined +', \''+ isManyRows +'\', \'\')', name: v.wfmstatusname});
                            } else {
                                var isIgnoreMultiRowRunBp = ('isignoremultirowrunbp' in Object(v) && v.isignoremultirowrunbp == '1') ? 1 : 0;
                                if (typeof v.wfmstatusname != 'undefined' && v.wfmstatusname != '' && ((v.wfmstatusprocessid == '' || v.wfmstatusprocessid == 'null' || v.wfmstatusprocessid == null) || isIgnoreMultiRowRunBp)) {
                                    if (v.wfmisneedsign == '1') {
                                        $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="beforeSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" data-isindicator="1">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                        wfmActions.push({icon: wfmIcon, action:'beforeSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                    } else if (v.wfmisneedsign == '2') {
                                        $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="beforeHardSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" data-isindicator="1">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                        wfmActions.push({icon: wfmIcon, action:'beforeHardSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId ?>\', \'<?php echo $this->indicatorId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                    } else if (v.wfmisneedsign == '3') {
                                        $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="cloudSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" data-isindicator="1">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                        wfmActions.push({icon: wfmIcon, action:'cloudSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId ?>\', \'<?php echo $this->indicatorId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                    } else if (v.wfmisneedsign == '4') {
                                        $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="pinCodeChangeWfmStatusId(this, undefined, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" data-isindicator="1">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                        wfmActions.push({icon: wfmIcon, action:'pinCodeChangeWfmStatusId(this, undefined, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                    } else if (v.wfmisneedsign == '6') {
                                        $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="otpChangeWfmStatusId(this, undefined, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" data-isindicator="1">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                        wfmActions.push({icon: wfmIcon, action:'otpChangeWfmStatusId(this, undefined, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                    } else {
                                        $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\', '+ undefined +', '+ undefined +', '+ undefined +', '+ undefined +', '+ undefined +', \''+ isManyRows +'\', \'\');" data-isindicator="1">'+wfmIcon + v.wfmstatusname +'</a></li>'); 
                                        wfmActions.push({icon: wfmIcon, action:'changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId ?>\', \'<?php echo $this->indicatorId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\', '+ undefined +', '+ undefined +', '+ undefined +', '+ undefined +', '+ undefined +', \''+ isManyRows +'\', \'\')', name: v.wfmstatusname});
                                    }
                                } else if (v.wfmstatusprocessid != '' && v.wfmstatusprocessid != 'null' && v.wfmstatusprocessid != null) {
                                    var wfmStatusCode = ('wfmstatuscode' in Object(v)) ? v.wfmstatuscode : ''; 
                                    var metaTypeId = ('metatypeid' in Object(v)) ? v.metatypeid : '200101010000011';
                                    if (v.wfmisneedsign == '1') {
                                        $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'signProcess\', \'<?php echo $this->indicatorId; ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');" data-isindicator="1">'+wfmIcon + v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                        wfmActions.push({icon: '<i class="fa fa-key"></i>', action:'transferProcessAction(\'signProcess\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\')', name: v.wfmstatusname});
                                    } else if (v.wfmisneedsign == '2') {
                                        $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'hardSignProcess\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');" data-isindicator="1">'+wfmIcon + v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                        wfmActions.push({icon: '<i class="fa fa-key"></i>', action:'transferProcessAction(\'hardSignProcess\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\')', name: v.wfmstatusname});
                                    } else if (v.wfmisneedsign == '4') {
                                        $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'pinCode\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');" data-isindicator="1">'+wfmIcon + v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                        wfmActions.push({icon: '<i class="fa fa-key"></i>', action:'transferProcessAction(\'pinCode\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\')', name: v.wfmstatusname});
                                    } else if (v.wfmisneedsign == '6') {
                                        $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'otp\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');" data-isindicator="1">'+wfmIcon + v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                        wfmActions.push({icon: '<i class="fa fa-key"></i>', action:'transferProcessAction(\'otp\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\')', name: v.wfmstatusname});
                                    } else {
                                        $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');" data-isindicator="1">'+wfmIcon + v.wfmstatusname+'</a></li>');
                                        wfmActions.push({icon: wfmIcon, action:'transferProcessAction(\'\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\')', name: v.wfmstatusname});
                                    }
                                }    
                            }
                            
                        } else {
                            
                            if (v.hasOwnProperty('indicatorid') && v.indicatorid != '' && v.indicatorid != null) {
                                var jsonStr = JSON.stringify(v).replace(/&quot;/g, '\\&quot;').replace(/"/g, '&quot;');
                                $workflowDropdown.append('<li><a href="javascript:;" onclick="mvChangeWfmStatus(this, \'<?php echo $this->indicatorId; ?>\');" data-statusconfig="'+jsonStr+'">'+wfmIcon + v.wfmstatusname +'</a></li>'); 
                            } else {
                                
                                if (typeof v.usedescriptionwindow != 'undefined' && !v.usedescriptionwindow && typeof v.wfmuseprocesswindow != 'undefined' && !v.wfmuseprocesswindow) {
                                    $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\', \'\', \'\', \'\');" data-isindicator="1">'+wfmIcon + v.wfmstatusname +'</a></li>'); 
                                    wfmActions.push({icon: wfmIcon, action:'changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\', \'\', \'\', \'\')', name: v.wfmstatusname});
                                } else {
                                    if (typeof v.wfmstatusname != 'undefined' && v.wfmstatusname != '' && (v.wfmstatusprocessid == '' || v.wfmstatusprocessid == 'null' || v.wfmstatusprocessid == null)) {
                                        if (v.wfmisneedsign == '1') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="beforeSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" data-isindicator="1">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                            wfmActions.push({icon: wfmIcon, action:'beforeSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '2') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="beforeHardSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" data-isindicator="1">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                            wfmActions.push({icon: wfmIcon, action:'beforeHardSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '3') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="cloudSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" data-isindicator="1">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                            wfmActions.push({icon: wfmIcon, action:'cloudSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '4') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="pinCodeChangeWfmStatusId(this, undefined, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" data-isindicator="1">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                            wfmActions.push({icon: wfmIcon, action:'pinCodeChangeWfmStatusId(this, undefined, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '6') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="otpChangeWfmStatusId(this, undefined, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'" data-isindicator="1">'+wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i></a></li>'); 
                                            wfmActions.push({icon: wfmIcon, action:'otpChangeWfmStatusId(this, undefined, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                        } else {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\');" data-isindicator="1">'+wfmIcon + v.wfmstatusname +'</a></li>'); 
                                            wfmActions.push({icon: wfmIcon, action:'changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->indicatorId; ?>\', \'<?php echo $this->indicatorId; ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+realWfmName+'\')', name: v.wfmstatusname});
                                        }
                                    } else if (v.wfmstatusprocessid != '' && v.wfmstatusprocessid != 'null' && v.wfmstatusprocessid != null) {
                                        var wfmStatusCode = ('wfmstatuscode' in Object(v)) ? v.wfmstatuscode : ''; 
                                        var metaTypeId = ('metatypeid' in Object(v)) ? v.metatypeid : '200101010000011';
                                        if (v.wfmisneedsign == '1') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'signProcess\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId; ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');" data-isindicator="1">'+wfmIcon+v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                            wfmActions.push({icon: '<i class="fa fa-key"></i>', action:'transferProcessAction(\'signProcess\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId; ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '2') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'hardSignProcess\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId; ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');" data-isindicator="1">'+wfmIcon+v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                            wfmActions.push({icon: '<i class="fa fa-key"></i>', action:'transferProcessAction(\'hardSignProcess\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId; ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '4') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'pinCode\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId; ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');" data-isindicator="1">'+wfmIcon+v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                            wfmActions.push({icon: '<i class="fa fa-key"></i>', action:'transferProcessAction(\'pinCode\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId; ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '6') {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'otp\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId; ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');" data-isindicator="1">'+wfmIcon+v.wfmstatusname+' <i class="fa fa-key"></i></a></li>');
                                            wfmActions.push({icon: '<i class="fa fa-key"></i>', action:'transferProcessAction(\'otp\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId; ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\')', name: v.wfmstatusname});
                                        } else {
                                            $workflowDropdown.append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId; ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');" data-isindicator="1">'+wfmIcon+v.wfmstatusname+'</a></li>');
                                            wfmActions.push({icon: wfmIcon, action:'transferProcessAction(\'\', \'<?php echo $this->indicatorId ?>\', \''+v.wfmstatusprocessid+'\', \''+metaTypeId+'\', \'toolbar\', this, {callerType: \'<?php echo $this->indicatorCode; ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->indicatorId ?>&refStructureId=<?php echo $this->indicatorId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\')', name: v.wfmstatusname});
                                        }
                                    }    
                                }
                            }
                        }
                    });    

                    $workflowDropdown.append('<div class="dropdown-divider"></div>');
                    
                } else if (response.hasOwnProperty('isShowMsgNotNextStatus') && response.isShowMsgNotNextStatus == '1') {
                    $workflowDropdown.dropdown('toggle');
                    new PNotify({
                        title: 'Info',
                        text: plang.get('wfm_permission_info'),
                        type: 'info',
                        addclass: pnotifyPosition,
                        sticker: false
                    });
                    Core.unblockUI();
                    return;
                } 

                /*if (response.hasOwnProperty('getUseAssignRuleId')) {
                    $workflowDropdown.append('<li><a href="javascript:;" onclick="userDefAssignWfmStatus(this, \''+response.getUseAssignRuleId+'\', \'<?php echo $this->indicatorId ?>\');">'+plang.get('MET_99990846')+'</a></li>');
                }*/

                if (!isIgnoreWfmHistory_<?php echo $this->subGridUniqId; ?>) {
                    wfmIcon = '';
                    if (typeof type !== 'undefined') {
                        wfmIcon = '<i class="icon-history"></i> ';
                    }
                    $workflowDropdown.append('<li><a href="javascript:;" onclick="seeWfmStatusForm(this, \'<?php echo $this->indicatorId ?>\');" data-isindicator="1">'+wfmIcon + plang.getDefault('wfm_log_history', 'Өөрчлөлтийн түүх харах')+'</a></li>');
                    wfmActions.push({icon: wfmIcon, action:'seeWfmStatusForm(this, \'<?php echo $this->indicatorId ?>\')', name: plang.getDefault('wfm_log_history', 'Өөрчлөлтийн түүх харах')});                  
                }

            } else {
                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: response.message,
                    type: response.status,
                    addclass: pnotifyPosition,
                    sticker: false
                });
            }

            Core.unblockUI();
        },
        error: function() { alert("Error"); }
    });
}

</script>