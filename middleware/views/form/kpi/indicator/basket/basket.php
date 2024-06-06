<div class="center-sidebar overflow-hidden content mv-datalist-container">
    <div class="row">
        <div class="col right-sidebar-content-for-resize content-wrapper pl-0 pr-0 overflow-hidden">
            <div class="row">
                <div class="col-md-12">
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
                                                
                                                if (!$process['is_use_basket']) continue;
                                                
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
                                                        
                                                        if ($isNormalRelation) {
                                                            $onClick = "mvNormalRelationRender(this, '$kpiTypeId', '".$this->indicatorId."', {methodIndicatorId: $crudIndicatorId, structureIndicatorId: $srcIndicatorId, mode: 'view'});";
                                                        } else {
                                                            $onClick = "manageKpiIndicatorValue(this, '$kpiTypeId', '".$this->indicatorId."', true, {mode: 'view'});";
                                                        }
                                                        
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
                                                        
                                                        if ($isNormalRelation) {
                                                            $onClick = "mvNormalRelationRender(this, '$kpiTypeId', '".$this->indicatorId."', {methodIndicatorId: $crudIndicatorId, structureIndicatorId: $srcIndicatorId});";
                                                        } else {
                                                            $onClick = "manageKpiIndicatorValue(this, '$kpiTypeId', '$srcIndicatorId', false$opt);";
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
                                                        
                                                        if ($isNormalRelation) {
                                                            $onClick = "mvNormalRelationRender(this, '$kpiTypeId', '".$this->indicatorId."', {methodIndicatorId: $crudIndicatorId, structureIndicatorId: $srcIndicatorId, mode: 'view'});";
                                                        } else {
                                                            $onClick = "manageKpiIndicatorValue(this, '$kpiTypeId', '$srcIndicatorId', true$opt);";
                                                        }
                                                        
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
                                                    'class' => 'btn btn-success btn-circle btn-sm', 
                                                    'onclick' => "chooseKpiIndicatorRowsFromBasket(this, '170082237622310', 'single', 'selectedSegmentationData_".$this->uniqId."');", 
                                                    'href' => 'javascript:;'
                                                ), 
                                                '<i class="far fa-plus"></i> Сегмент холбох', true
                                            );
                                            
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
                                                
                                                echo '<div class="btn-group workflow-btn-group-'.$this->uniqId.'">
                                                    <button type="button" class="btn btn-sm blue btn-circle dropdown-toggle workflow-btn-'.$this->uniqId.'" data-toggle="dropdown"><i class="far fa-cogs"></i> '.$this->lang->line('change_workflow').'</button>
                                                    <ul class="dropdown-menu workflow-dropdown-'.$this->uniqId.'" role="menu"></ul>
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
                                    
                                    echo Html::anchor(
                                            'javascript:;', '<i class="far fa-shopping-cart"></i> <span class="save-database-'. $this->indicatorId .'">0</span>', array(
                                            'class' => 'btn btn-secondary btn-sm btn-circle default',
                                            'onclick' => 'dataListUseBasketView_' . $this->indicatorId . '(this);',
                                            'title' => $this->lang->line('META_00113'),
                                        ), true
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
                <div class="col-md-12 div-objectdatagrid-<?php echo $this->uniqId; ?> jeasyuiTheme3">
                    
                    <?php
                    if (isset($this->isBasket)) {
                    ?>
                    <table id="objectdatagrid-<?php echo $this->uniqId; ?>" style="height: 400px"></table>
                    <?php
                    } else {
                    ?>
                    <table id="objectdatagrid-<?php echo $this->uniqId; ?>" style="height: 400px"></table>
                    <?php
                    }
                    ?>
                    <div id="md-map-canvas-<?php echo $this->uniqId; ?>" style="display: none"></div>
                </div>
            </div>    
        </div>     
    </div>    
</div>  

<div class="clearfix w-100"></div>
    
<script type="text/javascript">
    var dataGridTypeBtn_<?php echo $this->uniqId; ?> = 'datagrid';
    var objectdatagrid_<?php echo $this->uniqId; ?> = $('#objectdatagrid-<?php echo $this->uniqId; ?>');
    var windowId_<?php echo $this->uniqId; ?> = 'div#object-value-list-<?php echo $this->uniqId; ?>';
    var rows_<?php echo $this->uniqId; ?> = <?php echo $this->selectedBasketRows ?>;
    
    $(function() {
        
        objectdatagrid_<?php echo $this->uniqId; ?>.<?php echo $this->isGridType; ?>({
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
            data: rows_<?php echo $this->uniqId; ?>,
            method: 'post',
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
            remoteFilter: false,
            multiSort: false,
            remoteSort: false,
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
                <?php echo !$this->isHideCheckBox ? "" : "[{field: 'ck', rowspan:1, checkbox: true }]," ?>
                [{field: 'action', rowspan:1 }]
            ],
            columns: [
                <?php echo $this->columns['comboColumnsRender']; ?> 
                [<?php echo $this->columns['columnsRender']; ?>]
            ],
            onSelectAll: function() {
                dvSelectionCountToFooter_<?php echo $this->uniqId; ?>();
            }, 
            onUnselectAll: function() {
                dvSelectionCountToFooter_<?php echo $this->uniqId; ?>();
            }, 
            onUnselect: function() {
                dvSelectionCountToFooter_<?php echo $this->uniqId; ?>();
            },
            onSelect: function(index, row) {
                dvSelectionCountToFooter_<?php echo $this->uniqId; ?>();
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
                    selector: 'div#object-value-list-<?php echo $this->uniqId; ?> .datagrid .datagrid-view .datagrid-view1 .datagrid-body .datagrid-row, div#object-value-list-<?php echo $this->uniqId; ?> .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row',
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
                    selector: 'div#object-value-list-<?php echo $this->uniqId; ?> .datagrid .datagrid-view .datagrid-view1 .datagrid-body .datagrid-row, div#object-value-list-<?php echo $this->uniqId; ?> .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row',
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
                var $panelView = objectdatagrid_<?php echo $this->uniqId; ?>.datagrid('getPanel').children('div.datagrid-view');
                Core.initSelect2($panelView.find('.datagrid-view2 .datagrid-header-row:eq(0)'));
                <?php
                }
                ?>
            },
            onLoadSuccess: function(data) {
            <?php
            }
            ?>

                var _thisGrid = objectdatagrid_<?php echo $this->uniqId; ?>;

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

        objectdatagrid_<?php echo $this->uniqId; ?>.datagrid('getPager').pagination({
            showPageList: true,
            layout: ['list','sep','first','prev','sep','manual','sep','next','last','sep','refresh','info'],
        });

        objectdatagrid_<?php echo $this->uniqId; ?>.datagrid('enableFilter', [
            {field: 'action', type: 'label'}
        ]);
        objectdatagrid_<?php echo $this->uniqId; ?>.datagrid('hideColumn', 'ck');

        $(window).bind('resize', function() {
            var $dvElem = $('body').find('#object-value-list-<?php echo $this->uniqId; ?>');
            if ($dvElem.length > 0 && $dvElem.is(':visible') && $dvElem.find('.panel-eui').length) {
                objectdatagrid_<?php echo $this->uniqId; ?>.datagrid('resize');
            }
        });   
        
        $('.workflow-btn-<?php echo $this->uniqId ?>').on('click', function (e, type) {
            wfmstatusRender_<?php echo $this->uniqId ?>(e, type);
        });        

    });
    
    function wfmstatusRender_<?php echo $this->uniqId ?>(e, type, isIgnoreAlert) {
        var $workflowDropdown = $('.workflow-dropdown-<?php echo $this->uniqId ?>');
        $workflowDropdown.empty();

        var rows = getDataViewSelectedRows('<?php echo $this->uniqId ?>');

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

                    wfmIcon = '';
                    if (typeof type !== 'undefined') {
                        wfmIcon = '<i class="icon-history"></i> ';
                    }
                    $workflowDropdown.append('<li><a href="javascript:;" onclick="seeWfmStatusForm(this, \'<?php echo $this->indicatorId ?>\');" data-isindicator="1">'+wfmIcon + plang.getDefault('wfm_log_history', 'Өөрчлөлтийн түүх харах')+'</a></li>');
                    wfmActions.push({icon: wfmIcon, action:'seeWfmStatusForm(this, \'<?php echo $this->indicatorId ?>\')', name: plang.getDefault('wfm_log_history', 'Өөрчлөлтийн түүх харах')});                  

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
    
    function deleteSelectableBasketWindow_<?php echo $this->indicatorId ?>(target) {
        
        setTimeout(function(){
            var basketRows = objectdatagrid_<?php echo $this->uniqId; ?>.datagrid('getSelections');
            var selectedRow = basketRows[0], rowId = selectedRow.id; 
            
            for (var key in rows_<?php echo $this->uniqId; ?>) {
                var row = rows_<?php echo $this->uniqId; ?>[key], childId = row.id;
                
                if (rowId == childId) {

                    var index = objectdatagrid_<?php echo $this->uniqId; ?>.datagrid('getRowIndex', row);
                    
                    if (index < 0) {
                        objectdatagrid_<?php echo $this->uniqId; ?>.datagrid('deleteRow', 0);
                        rows_<?php echo $this->uniqId; ?>.splice(key, 1);
                    } else {
                        objectdatagrid_<?php echo $this->uniqId; ?>.datagrid('deleteRow', index);
                    }

                    _selectedRows_<?php echo $this->indicatorId; ?>.splice(key, 1);
                    
                    break;
                } 
            }
            
            objectdatagrid_<?php echo $this->uniqId; ?>.datagrid('loadData', rows_<?php echo $this->uniqId; ?>);
            
            $('.save-database-<?php echo $this->indicatorId; ?>').text(_selectedRows_<?php echo $this->indicatorId; ?>.length);
            
        }, 5);
    }
    
    function selectedSegmentationData_<?php echo $this->uniqId; ?>(elem, indicatorId, rows, idField, codeField, nameField, chooseType) {
        var iids = [], rids = [];
        for (var i = 0; i < _selectedRows_<?php echo $this->indicatorId; ?>.length; i++) {
            iids.push(<?php echo $this->indicatorId; ?>);
            rids.push(_selectedRows_<?php echo $this->indicatorId; ?>[i]['ID']);
        }
        $.ajax({
            type: 'post',
            url: 'mdwebservice/saveMetaVerseRecordSegmentMap/1/<?php echo $this->indicatorId; ?>/'+rows[0][idField],
            dataType: 'json',
            data: {
                mvDmRecordMaps: {
                    indicatorId: iids,
                    recordId: rids,
                }
            }, 
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },            
            success: function (data) {
                new PNotify({
                    title: 'Success',
                    text: plang.get('msg_save_success'),
                    type: 'success',
                    addclass: pnotifyPosition,
                    sticker: false
                });
                console.log(2424234);
                Core.unblockUI();
                $('#dataViewBasket-dialog-<?php echo $this->indicatorId; ?>').dialog('close');
            }
        });        
    }
    
    function dvSelectionCountToFooter_<?php echo $this->uniqId; ?>() {
        var $panelView = objectdatagrid_<?php echo $this->uniqId; ?>.datagrid('getPanel');    
        if ($panelView.find(".datagrid-pager").length) {
            if ($panelView.find(".datagrid-pager").find('tbody > tr:eq(0)').find('.custom-selected-counter').length) {
                $panelView.find(".datagrid-pager").find('tbody > tr:eq(0)').find('.custom-selected-counter').remove();
            }
            var rows = window['objectdatagrid_<?php echo $this->uniqId ?>'].datagrid('getSelections');
            $panelView.find(".datagrid-pager").find('tbody > tr:eq(0)').append('<td class="custom-selected-counter"><div class="pagination-btn-separator"></div></td><td class="custom-selected-counter pl6">'+plang.get('has_chosen')+': '+rows.length+'</td>');
        }
    }    

    function basketDvAllRowsRemove_<?php echo $this->uniqId; ?>(elem) {
        var $dialogName = 'dialog-basket-remove-confirm';
        
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');

            $.ajax({
                type: 'post',
                url: 'mdcommon/deleteConfirm',
                dataType: 'json',
                async: false, 
                success: function (data) {
                    $("#" + $dialogName).empty().append(data.Html);
                }
            });
        }

        var $dialog = $('#' + $dialogName);

        $dialog.dialog({
            cache: false,
            resizable: false,
            bgiframe: true,
            autoOpen: false,
            title: plang.get('msg_title_confirm'),
            width: 330,
            height: 'auto',
            modal: true,
            buttons: [
                {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                    rows_<?php echo $this->uniqId; ?> = [];
                    _selectedRows_<?php echo $this->indicatorId; ?> = [];
                    $('.save-database-<?php echo $this->indicatorId; ?>').text('0');
                    $dialog.dialog('close');
                    $('#dataViewBasket-dialog-<?php echo $this->indicatorId; ?>').dialog('close');
                }},
                {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                    $dialog.dialog('close');
                }}
            ]
        });
        $dialog.dialog('open');

        return;
    }
</script>