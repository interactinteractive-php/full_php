<div class="center-sidebar overflow-hidden content <?php echo isset($this->appendClass) ? $this->appendClass : '' ?>" id="objectDataView_<?php echo $this->metaDataId; ?>">
    <div class="row">
        <?php if (issetParam($this->row['SHOW_POSITION']) === 'left') { ?>
            <div class="col-4" id="md-bp-left-<?php echo $this->metaDataId; ?>"></div>
            <div class="col-8"> 
        <?php } ?>
        <?php echo $this->detailHeader; ?>
        <div class="<?php echo $this->dataViewClass; ?> right-sidebar-content-for-resize  <?php echo (isset($this->dataViewCriteriaType) && ($this->dataViewCriteriaType == 'left web' || $this->dataViewCriteriaType == 'left web civil') && issetParam($this->isEmptyCriteria)) ? 'fall-fixed web-tabdataview' : 'content-wrapper' ?>">
            <div class="row  <?php echo (isset($this->dataViewCriteriaType) && ($this->dataViewCriteriaType == 'left web' || $this->dataViewCriteriaType == 'left web civil') && issetParam($this->isEmptyCriteria)) ? 'web-margin-left' : '' ?>">
                <?php if (!isset($this->dataGridOptionData['SHOWTOOLBAR']) || $this->dataGridOptionData['SHOWTOOLBAR'] != '0') { ?>
                    <div class="col-md-12 remove-type-<?php echo $this->metaDataId; ?> object-height-row3-minus-<?php echo $this->metaDataId ?>" >
                        <div class="table-toolbar">
                            <div class="d-flex dv-button-style-<?php echo $this->buttonBarStyle; ?>">
                                <div class="col p-0">
                                    <div class="dv-process-buttons">
                                        <?php
                                        if (isset($this->openDefaultBp) && !empty($this->openDefaultBp)) {
                                            $defaultOpenTitle = trim(issetParam($this->title));
                                            if ($defaultOpenTitle != '&nbsp;' && $defaultOpenTitle != '' && $defaultOpenTitle != ' ' && $defaultOpenTitle != ' ' && $defaultOpenTitle != ' ') {
                                                echo '<div class="row d-none"><div class="col-md-12 uppercase is-bp-open-dataview-title mb10">'.$defaultOpenTitle.'</div></div>';
                                            }
                                        }
                                        if (($this->isTree || $this->dataViewHeaderData) && $this->dataViewCriteriaType == 'button' && $this->isCheckDataViewHeaderData) {
                                        ?>
                                        <div class="top-sidebar datagrid-filter-panel remove-type-<?php echo $this->metaDataId; ?> search-topsidebar-<?php echo $this->metaDataId; ?>">
                                            <div class="top-sidebar-content">
                                                <div class="col-md-12 mb10 mt10">
                                                    <div class="tabbable-line">
                                                        <ul class="nav nav-tabs">     
                                                            <?php if ($this->isTree) { ?>
                                                                <li class="nav-item">
                                                                    <a href="#meta-tree-view-tab-1-<?php echo $this->metaDataId; ?>" class="nav-link <?php echo (!$this->dataViewHeaderData) ? 'active' : '' ?>" data-toggle="tab"><?php echo $this->lang->line('filter'); ?></a>
                                                                </li>      
                                                            <?php } if ($this->dataViewHeaderData) { ?>
                                                                <li class="nav-item">
                                                                    <a href="#meta-search-view-tab-2-<?php echo $this->metaDataId; ?>" class="nav-link active" data-toggle="tab"><?php echo $this->lang->line('search'); ?></a>
                                                                </li>      
                                                            <?php } ?>
                                                        </ul>
                                                        <div class="tab-content">
                                                            <?php if ($this->isTree) { ?>
                                                                <div class="tab-pane in <?php echo (!$this->dataViewHeaderData) ? 'active' : '' ?> height-dynamic" id="meta-tree-view-tab-1-<?php echo $this->metaDataId; ?>">
                                                                    <?php 
                                                                    if (count($this->treeCategoryList) === 1) {
                                                                        echo "<span class='hide'>";
                                                                        echo Form::select(
                                                                            array(
                                                                                'name' => 'treeCategory',
                                                                                'id' => 'treeCategory',
                                                                                'op_value' => 'ID',
                                                                                'op_text' => 'NAME',
                                                                                'glue' => '-',
                                                                                'data' => $this->treeCategoryList,
                                                                                'onchange' => 'drawTree_' . $this->metaDataId . '();',
                                                                                'text' => 'notext'
                                                                            )
                                                                        );
                                                                        echo "</span>";
                                                                    } else {
                                                                        echo Form::select(
                                                                            array(
                                                                                'name' => 'treeCategory',
                                                                                'id' => 'treeCategory',
                                                                                'class' => 'form-control form-control-sm select2 mb10',
                                                                                'op_value' => 'ID',
                                                                                'op_text' => 'NAME',
                                                                                'glue' => '-',
                                                                                'data' => $this->treeCategoryList,
                                                                                'onchange' => 'drawTree_' . $this->metaDataId . '();',
                                                                                'text' => 'notext'
                                                                            )
                                                                        );
                                                                    }
                                                                    ?>
                                                                    <div id="treeContainer">
                                                                        <div id="dataViewStructureTreeView_<?php echo $this->metaDataId; ?>" class="tree-demo"></div>
                                                                    </div>
                                                                    <form role="form" id="tree-click-form" method="post">
                                                                        <input type="hidden" id="tree-click-hidden-input" />
                                                                    </form>
                                                                </div>     
                                                            <?php } if ($this->dataViewHeaderData) { ?>
                                                                <div class="tab-pane in active" id="meta-search-view-tab-2-<?php echo $this->metaDataId; ?>">
                                                                    <?php echo $this->defaultCriteria; ?> 
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        $filterButton = '<a type="button" id="search-sidebar-'.$this->metaDataId.'" data-close-others="true" class="btn btn-circle top-stoggler btn-sm btn-secondary search-sidebar-'.$this->metaDataId.'"><i class="fa fa-search"></i> '.$this->lang->line('filter').' <i class="fa fa-angle-down"></i></a>';
                                        }
                                        if (($this->isTree || $this->dataViewHeaderData) && $this->dataViewCriteriaType == 'popup' && $this->isCheckDataViewHeaderData) {
                                        ?>
                                        <div class="search-topsidebar-popup-<?php echo $this->metaDataId; ?>" style="display: none;">
                                            <div class="col-md-12">
                                                <?php echo $this->defaultCriteria; ?> 
                                            </div>
                                        </div>
                                        <?php
                                        $filterButton = '<a type="button" href="javascript:;" onclick="dvPopupCriteria'.$this->metaDataId.'()" id="search-sidebar-popup-'.$this->metaDataId.'" data-close-others="true" class="btn btn-circle top-stoggler btn-sm btn-secondary search-sidebar-popup-'.$this->metaDataId.'"><i class="fa fa-search"></i> '.$this->lang->line('filter').'</a>';
                                        }
                                        
                                        $commandBtn = '';
                                        if ($this->dataGridOptionData['INLINEEDIT'] == 'true' && false) {
                                            $commandBtn = ''.
                                            '<button type="button" title="Мөр нэмэх" onClick="insertRowInlineEditDataView_'.$this->metaDataId.'(this)" class="btn default btn-circle btn-sm inline-edit-actions-btn"><img src="'.URL.'assets/core/global/img/ico/inline_add.png"></button>
                                            <button type="button" title="Хадгалах" onClick="saveRowInlineEditDataView_'.$this->metaDataId.'(this)" class="btn default btn-circle btn-sm inline-edit-actions-btn"><img src="'.URL.'assets/core/global/img/ico/inline_save.png"></button>
                                            <button type="button" title="Нэмсэн мөр устгах" onClick="deleteRowInlineEditDataView_'.$this->metaDataId.'(this)" class="btn default btn-circle btn-sm inline-edit-actions-btn"><img src="'.URL.'assets/core/global/img/ico/inline_remove.png"></button> <span style="height: 25px; display: inline-block; border-right: solid 1px #7b7b7b;" class="mr5"></span>';
                                        }

                                        $commandBtn .= $this->dataViewProcessCommand['commandBtn']; $addonBtn = $wfmBtn = '';
                                        
                                        if ($this->isPrint) {
                                            
                                            $invoicePrintBtn = html_tag('button', array(
                                                'type' => 'button', 
                                                'class' => 'btn btn-sm btn-circle green', 
                                                'onclick' => 'dataViewPrintPreview_'.$this->metaDataId.'(\''.$this->metaDataId.'\', true, \'toolbar\', this);'
                                            ), '<i class="far fa-print"></i> '.($this->lang->line('printTemplate'.$this->metaDataId) == 'printTemplate'.$this->metaDataId ? $this->lang->line('printTemplate') : $this->lang->line('printTemplate'.$this->metaDataId)));
                                            
                                            if (issetParam($this->row['IS_INVOICE_PRINT_BTN']) != '') {
                                                
                                                $commandBtn = str_replace('<!--invoiceprintbutton-->', $invoicePrintBtn, $commandBtn);
                                                
                                            } else {
                                                $addonBtn .= $invoicePrintBtn;
                                            }
                                        }
                                        
                                        if (isset($this->dataViewWorkFlowBtn) && $this->dataViewWorkFlowBtn == true) { 
                                            
                                            $wfmBtn = '<div class="btn-group workflow-btn-group-'.$this->metaDataId.'">
                                                <button type="button" class="btn btn-sm blue btn-circle dropdown-toggle workflow-btn-'.$this->metaDataId.'" data-toggle="dropdown"><i class="far fa-cogs"></i> '.$this->lang->line('change_workflow').'</button>
                                                <ul class="dropdown-menu workflow-dropdown-'.$this->metaDataId.'" role="menu"></ul>
                                            </div>';
                                            
                                            $commandBtn = str_replace('<!--changewfmstatus-->', $wfmBtn, $commandBtn, $wfmBtnReplace);
                                            
                                            if (!$wfmBtnReplace) {
                                                $addonBtn .= $wfmBtn;
                                            }
                                        } 
                                        
                                        if (isset($this->useBasketBtn) && $this->useBasketBtn && $this->layoutType != 'ecommerce_basket') {
                                            $addonBtn .= html_tag('button', array(
                                                'type' => 'button', 
                                                'class' => 'btn btn-sm btn-circle btn-secondary', 
                                                'onclick' => 'dataViewToBasket_'.$this->metaDataId.'(this);'
                                            ), '<i class="far fa-shopping-cart"></i> Сагсанд нэмэх');
                                        }
                                        
                                        if (issetParam($this->row['IS_USE_SEMANTIC'])) {
                                            $addonBtn .= '<div class="btn-group dv-buttons-batch">'
                                                . '<button type="button" class="btn bg-slate btn-circle btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">'.$this->lang->line('dmrmap_button_name').'</button>'
                                                . '<ul class="dropdown-menu" role="menu">'
                                                    . '<li><a href="javascript:;" onclick="dvDmRecordMapSet(this, \''.$this->metaDataId.'\', \''.$this->refStructureId.'\');"><i class="fa fa-link"></i> '.$this->lang->line('dmrmap_connect').'</a></li>'
                                                    . '<li><a href="javascript:;" onclick="dvDmRecordMapList(this, \''.$this->metaDataId.'\');"><i class="fa fa-list"></i> '.$this->lang->line('dmrmap_history').'</a></li>'
                                                . '</ul>'
                                            . '</div>';
                                            
                                            $addonBtn .= html_tag('button', array(
                                                'type' => 'button', 
                                                'class' => 'btn btn-sm btn-circle btn-secondary', 
                                                'onclick' => 'dataViewToBasket(this);'
                                            ), '<i class="far fa-shopping-cart"></i> Сагсанд нэмэх');
                                        }
                                        
                                        if ($this->refStructureId) {
                                            
                                            if (strpos($commandBtn, '<!--pfaddeditlogview-->') !== false && strpos($commandBtn, '<!--pfremovedlogview-->') !== false) {
                                                
                                                $isLogRecover = (strpos($commandBtn, '<!--pfrestoredeleteddata-->') !== false) ? 'true' : 'false';
                                                
                                                $addonBtn .= '<div class="btn-group dv-buttons-batch">
                                                    <button class="btn btn-secondary btn-circle btn-sm dropdown-toggle" type="button" data-toggle="dropdown"><i class="icon-history"></i> '.$this->lang->line('PF_VIEW_LOG').'</button>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="javascript:;" onclick="bpRecordHistoryLogList(this, \''.$this->metaDataId.'\', \''.$this->refStructureId.'\');">'.$this->lang->line('PF_ADD_EDIT_LOG_VIEW').'</a></li>
                                                        <li><a href="javascript:;" onclick="bpRecordHistoryRemovedLogList(this, \''.$this->metaDataId.'\', \''.$this->refStructureId.'\', '.$isLogRecover.');">'.$this->lang->line('PF_REMOVE_LOG_VIEW').'</a></li>
                                                    </ul>
                                                </div>';
                                                
                                            } elseif (strpos($commandBtn, '<!--pfaddeditlogview-->') !== false) {

                                                $addonBtn .= html_tag('button', array(
                                                    'type' => 'button', 
                                                    'class' => 'btn btn-sm btn-circle btn-secondary', 
                                                    'onclick' => 'bpRecordHistoryLogList(this, \''.$this->metaDataId.'\', \''.$this->refStructureId.'\');'
                                                ), $this->lang->line('PF_ADD_EDIT_LOG_VIEW'));

                                            } elseif (strpos($commandBtn, '<!--pfremovedlogview-->') !== false) {
                                                
                                                $isLogRecover = (strpos($commandBtn, '<!--pfrestoredeleteddata-->') !== false) ? 'true' : 'false';
                                                
                                                $addonBtn .= html_tag('button', array(
                                                    'type' => 'button', 
                                                    'class' => 'btn btn-sm btn-circle btn-secondary', 
                                                    'onclick' => 'bpRecordHistoryRemovedLogList(this, \''.$this->metaDataId.'\', \''.$this->refStructureId.'\', '.$isLogRecover.');'
                                                ), $this->lang->line('PF_REMOVE_LOG_VIEW'));
                                            }
                                        }
                                        
                                        if ($commandBtn) {
                                            $commandBtn = str_replace('<!--endbutton-->', $addonBtn, $commandBtn);
                                            if (isset($filterButton)) {
                                                $commandBtn = str_replace('<!--startbutton-->', $filterButton, $commandBtn);
                                            }
                                        } else {
                                            if (isset($filterButton)) {
                                                $addonBtn = $filterButton.$addonBtn;
                                            }
                                            $commandBtn = $addonBtn;
                                        }
                                        
                                        echo $commandBtn;
                                        ?>
                                    </div>
                                </div>
                                <div class="<?php echo isset($this->dataGridOptionData['SHOWTOOLBARRIGHT']) && $this->dataGridOptionData['SHOWTOOLBARRIGHT'] == '0' ? ' hidden' : ''; ?> dv-right-tools-btn ml-2 text-right">
                                    <?php
                                    echo $this->quickSearch; 
                                    
                                    if (issetParam($this->dataGridOptionData['GROUPFIELDUSER']) == 'true') {
                                        echo Form::select(
                                            array(
                                                'class' => 'form-control select2 form-control-sm groupfield-combo', 
                                                'name' => 'subQueryId',
                                                'data' => Mdobject::$onlyShowColumns,
                                                'op_value' => 'FIELD_PATH',
                                                'op_text' => 'LABEL_NAME', 
                                                'value' => strtolower($this->dataGridOptionData['GROUPFIELD']), 
                                                'translationText' => true, 
                                                'style' => 'position:relative;display:inline-block;vertical-align:middle;width:140px'
                                            )
                                        );
                                    }
                                    
                                    if (isset($this->row['subQuery']) && $this->row['subQuery']) {
                                        echo Form::select(
                                            array(
                                                'class' => 'form-control select2 form-control-sm subquery-combo', 
                                                'name' => 'subQueryId',
                                                'id' => 'subQueryId-'.$this->metaDataId,
                                                'data' => $this->row['subQuery'],
                                                'op_value' => 'ID',
                                                'op_text' => 'GLOBE_CODE', 
                                                'style' => 'position:relative;display:inline-block;vertical-align:middle;width:140px', 
                                                'op_custom_attr' => array(
                                                    array(
                                                        'attr' => 'data-code', 
                                                        'key' => 'CODE'
                                                    )
                                                )
                                            )
                                        );
                                    }
                                    ?>
                                    <?php 
                                    if (($this->dataViewCriteriaType == 'left web' || $this->dataViewCriteriaType == 'left web civil') || Config::getFromCache('CONFIG_IS_COLLAPSE_BUTTON')) { 
                                        
                                        echo Html::anchor(
                                                'javascript:;', '<i class="far fa-shopping-cart"></i> <span class="save-database-'. $this->metaDataId .'">0</span>', array(
                                                'class' => 'btn btn-secondary btn-sm btn-circle default',
                                                'onclick' => 'dataViewUseBasketView_' . $this->metaDataId . '(this);',
                                                'title' => $this->lang->line('META_00113'),
                                            ), (isset($this->useBasketBtn) && $this->layoutType == 'ecommerce_basket') ? $this->useBasketBtn : false
                                        );
                                    ?>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?php echo $this->lang->line('dropdown_action') ?> </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <?php 
                                                echo Html::anchor(
                                                        'javascript:;', '<i class="fa fa-file"></i> '.$this->lang->line('META_VIEW_REPORT_TEMPLATE'), array(
                                                        'class' => 'dropdown-item',
                                                        'onclick' => 'objectReportTemplateView_'.$this->metaDataId.'()'
                                                    ), $this->isReportTemplate  
                                                ); 
                                                echo Html::anchor(
                                                        'javascript:;', '<i class="fa fa-dashboard"></i> '.$this->lang->line('META_VIEW_DASHBOARD'), array(
                                                        'class' => 'dropdown-item',
                                                        'onclick' => 'objectDashboardView_'.$this->metaDataId.'()'
                                                    ), $this->isDashboard  
                                                ); 
                                                echo Html::anchor(
                                                        'javascript:;', '<i class="icon-cube"></i> Pivot view', array(
                                                        'class' => 'dropdown-item',
                                                        'onclick' => 'dataViewPivotView(\''.$this->metaDataId.'\', this);'
                                                    ), (defined('CONFIG_PIVOT_SERVICE_ADDRESS') && CONFIG_PIVOT_SERVICE_ADDRESS)  
                                                ); 
                                                echo Html::anchor(
                                                        'javascript:;', '<i class="icon-qrcode"></i> Statement', array(
                                                        'class' => 'dropdown-item',
                                                        'onclick' => 'dataViewStatementPreview_'.$this->metaDataId.'(\''.$this->metaDataId.'\', true, \'toolbar\', this);'
                                                    ), $this->isStatementBtnSee
                                                );  
                                                
                                                if (isset($this->row['dataViewLayoutTypes']['explorer']) || isset($this->row['dataViewLayoutTypes']['calendar'])) {
                                                
                                                    if (isset($this->row['dataViewLayoutTypes']['calendar'])) {
                                                        $iconName = 'calendar';
                                                        $title = 'Calendar view';
                                                    } else {
                                                        $iconName = 'folder';
                                                        $title = 'Explorer view';
                                                    }
                                                    
                                                    echo Html::anchor(
                                                            'javascript:;', '<i class="icon-'.$iconName.'"></i> '.$title, array(
                                                            'title' => $title,
                                                            'class' => 'dropdown-item',
                                                            'onclick' => 'dataViewer_'.$this->metaDataId.'(this, \''.key($this->row['dataViewLayoutTypes']).'\', \''.$this->metaDataId.'\');'
                                                        ), true
                                                    );
                                                }
                                                
                                                echo Html::anchor(
                                                        'javascript:;', '<i class="icon-chart"></i> Layout', array(
                                                        'class' => 'dropdown-item callLayoutDataView_'. $this->metaDataId,
                                                        'title' => 'Layout',
                                                        'onclick' => 'callLayoutDataView_'. $this->metaDataId .'('. $this->metaLayoutLinkId .', this);'
                                                    ), isset($this->metaLayoutBtn) ? $this->metaLayoutBtn : false
                                                ); 
                                                echo Html::anchor(
                                                        'javascript:;', '<i class="icon-table"></i> Table' , array(
                                                        'class' => 'dropdown-item callDataView_'. $this->metaDataId,
                                                        'title' => 'Table',
                                                        'onclick' => 'callDataView_'. $this->metaDataId .'('. $this->metaDataId .', this);'
                                                    ), isset($this->metaLayoutBtn) ? $this->metaLayoutBtn : false 
                                                ); 
                                                echo Html::anchor(
                                                        'javascript:;', '<i class="icon-map"></i> Map', array(
                                                        'class' => 'dropdown-item googleMapBtnByDataView_'. $this->metaDataId,
                                                        'title' => 'Map',
                                                        'onclick' => 'googleMapBtnByDataView_' . $this->metaDataId . '(this);'
                                                    ), isset($this->isGoogleMap) ? $this->isGoogleMap : false
                                                );       
                                                
                                                /*echo Html::anchor(
                                                        'javascript:;', '<i class="fa fa-th-large"></i> Card view', array(
                                                        'class' => 'dropdown-item dv-layout-type-'. $this->metaDataId,
                                                        'title' => 'Card view',
                                                        'onclick' => 'renderCardView_'. $this->metaDataId .'(this);',
                                                        'data-view-type' => $this->layoutType 
                                                    ), ($this->layoutType) ? true : false
                                                );*/
                                                echo Html::anchor(
                                                        'javascript:;', '<i class="icon-calendar"></i> Calendar view', array(
                                                        'title' => 'Calendar view',
                                                        'class' => 'dropdown-item',
                                                        'onclick' => 'callCalendarByMeta(' . $this->calendarMetaDataId . ');'
                                                    ), isset($this->isCalendarSee) ? $this->isCalendarSee : false
                                                ); 

                                                if (issetParam($this->row['IS_EXCEL_EXPORT_BTN']) != '') {
                                                    
                                                    if (strpos($commandBtn, '<!--excelexportbutton-->') !== false) {
                                                        echo Html::anchor(
                                                                'javascript:;', '<i class="icon-file-excel"></i> '.$this->lang->line('excel_btn'), array(
                                                                'title' => $this->lang->line('excel_btn'),
                                                                'class' => 'dropdown-item',
                                                                'onclick' => 'dataViewExportToExcel_' . $this->metaDataId . '();'
                                                            ), true
                                                        ); 
                                                    }
                                                    
                                                } else {
                                                    echo Html::anchor(
                                                            'javascript:;', '<i class="icon-file-excel"></i> '.$this->lang->line('excel_btn'), array(
                                                            'title' => $this->lang->line('excel_btn'),
                                                            'class' => 'dropdown-item',
                                                            'onclick' => 'dataViewExportToExcel_' . $this->metaDataId . '();'
                                                        ), (!isset($this->row['IS_IGNORE_EXCEL_EXPORT']) || (isset($this->row['IS_IGNORE_EXCEL_EXPORT']) && $this->row['IS_IGNORE_EXCEL_EXPORT'] != '1'))
                                                    ); 
                                                }

                                                echo Html::anchor(
                                                        'javascript:;', '<i class="icon-file-text"></i> Text file', array(
                                                        'title' => 'Text file',
                                                        'class' => 'dropdown-item',
                                                        'onclick' => 'dataViewExportToText_' . $this->metaDataId . '();'
                                                    ), isset($this->isExportText) ? $this->isExportText : false
                                                ); 
                                                echo Html::anchor(
                                                        'javascript:;', '<i class="far fa-print"></i> Print', array(
                                                        'title' => 'Print',
                                                        'class' => 'dropdown-item',
                                                        'onclick' => 'dataViewExportToPrint_' . $this->metaDataId . '();'
                                                    ), (issetParam($this->row['IS_DIRECT_PRINT']) == '1')
                                                );
                                                echo Html::anchor(
                                                        'javascript:;', '<i class="icon-table2"></i> Merge cell', array(
                                                        'class' => 'dropdown-item value-grid-merge-cell',
                                                        'title' => 'Merge cell'
                                                    ), (issetParam($this->dataGridOptionData['MERGECELLS']) == 'true' ? true : false)
                                                ); 
                                                echo Html::anchor(
                                                        'javascript:;', '<i class="icon-cog"></i> '.$this->lang->line('user_configuration'), array(
                                                        'title' => $this->lang->line('user_configuration'),
                                                        'class' => 'dropdown-item',
                                                        'onclick' => 'dataViewAdvancedConfig_' . $this->metaDataId . '(this);'
                                                    ),  (Ue::sessionUserId() === Config::getFromCache('ignoreDvBtnsByUserId')) ? false: true
                                                ); 
                                                echo Html::anchor(
                                                        'javascript:;', '<i class="far fa-shopping-cart"></i> '.$this->lang->line('META_00113').' (<span class="save-database-'. $this->metaDataId .'">0</span>)', array(
                                                        'onclick' => 'dataViewUseBasketView_' . $this->metaDataId . '(this);',
                                                        'class' => 'dropdown-item',
                                                        'title' => $this->lang->line('META_00113'),
                                                    ), (isset($this->useBasketBtn) && $this->layoutType != 'ecommerce_basket') ? $this->useBasketBtn : false
                                                ); 
                                                echo Html::anchor(
                                                        'javascript:;', (new Mduser())->iconQuickMenu($this->metaDataId) . ' QuickMenu', array(
                                                        'onclick' => 'toQuickMenu(\''.$this->metaDataId.'\', \'dataview\', this);',
                                                        'class' => 'dropdown-item',
                                                        'title' => 'Quick menu',
                                                    ), true
                                                );
                                                echo Html::anchor(
                                                        'javascript:;', '<i class="far fa-language"></i> Translate', array(
                                                        'onclick' => 'metaTranslator(this, \''.$this->metaDataId.'\');',
                                                        'class' => 'dropdown-item',
                                                        'title' => 'Translate',
                                                    ), Mdlanguage::isTranslateOptionByConfig() 
                                                );
                                                echo Mdcommon::listHelpContentButton([
                                                    'contentId' => issetParam($this->row['HELP_CONTENT_ID']), 
                                                    'sourceId' => $this->metaDataId, 
                                                    'fromType' => 'meta_dv', 
                                                    'parentControl' => 'dropdown'
                                                ]);
                                                ?>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="btn-group btn-group-devided">
                                            <?php 
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="fa fa-file"></i>', array(
                                                    'class' => 'btn btn-secondary btn-circle btn-sm default', 
                                                    'title' => $this->lang->line('META_VIEW_REPORT_TEMPLATE'), 
                                                    'onclick' => 'objectReportTemplateView_'.$this->metaDataId.'()'
                                                ), $this->isReportTemplate  
                                            ); 
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="fa fa-dashboard"></i>', array(
                                                    'class' => 'btn btn-secondary btn-circle btn-sm default', 
                                                    'title' => $this->lang->line('META_VIEW_DASHBOARD'), 
                                                    'onclick' => 'objectDashboardView_'.$this->metaDataId.'()'
                                                ), $this->isDashboard  
                                            ); 
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="far fa-cube"></i>', array(
                                                    'class' => 'btn btn-secondary btn-circle btn-sm default', 
                                                    'title' => 'Pivot view', 
                                                    'onclick' => 'dataViewPivotView(\''.$this->metaDataId.'\', this);'
                                                ), (defined('CONFIG_PIVOT_SERVICE_ADDRESS') && CONFIG_PIVOT_SERVICE_ADDRESS)  
                                            ); 
                                            echo Html::anchor(
                                                    'javascript:;', (Config::isCode('dataViewStatementHtml') ? Config::getFromCache('dataViewStatementHtml'): '<i class="fa fa-qrcode"></i>'), array(
                                                    'title' => 'Жагсаалтыг хэвлэх',     
                                                    'class' => 'btn btn-secondary btn-circle btn-sm default',
                                                    'onclick' => 'dataViewStatementPreview_'.$this->metaDataId.'(\''.$this->metaDataId.'\', true, \'toolbar\', this);'
                                                ), $this->isStatementBtnSee
                                            ); 
                                            
                                            if (isset($this->row['dataViewLayoutTypes']['explorer']) || isset($this->row['dataViewLayoutTypes']['calendar'])) {
                                                
                                                if (isset($this->row['dataViewLayoutTypes']['calendar'])) {
                                                    $iconName = 'calendar';
                                                    $title = 'Calendar view';
                                                } else {
                                                    $iconName = 'folder';
                                                    $title = 'Explorer view';
                                                }
                                                
                                                echo Html::anchor(
                                                        'javascript:;', '<i class="fa fa-'.$iconName.'"></i>', array(
                                                        'class' => 'btn btn-secondary btn-circle btn-sm default',
                                                        'title' => $title,
                                                        'onclick' => 'dataViewer_'.$this->metaDataId.'(this, \''.key($this->row['dataViewLayoutTypes']).'\', \''.$this->metaDataId.'\');'
                                                    ), true
                                                );
                                            }
                                            
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="fa fa-th-large"></i>', array(
                                                    'class' => 'btn btn-secondary btn-circle btn-sm default dv-layout-type-'. $this->metaDataId,
                                                    'title' => 'Card View',
                                                    'onclick' => 'renderCardView_'. $this->metaDataId .'(this);', 
                                                    'data-view-type' => $this->layoutType    
                                                ), ($this->layoutType) ? true : false
                                            );
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="fa fa-bar-chart-o"></i>', array(
                                                    'class' => 'btn btn-secondary btn-circle btn-sm default callLayoutDataView_'. $this->metaDataId,
                                                    'title' => 'Layout',
                                                    'onclick' => 'callLayoutDataView_'. $this->metaDataId .'('. $this->metaLayoutLinkId .', this);'
                                                ), isset($this->metaLayoutBtn) ? $this->metaLayoutBtn : false
                                            );
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="fa fa-table"></i>', array(
                                                    'class' => 'btn btn-secondary btn-circle btn-sm default callDataView_'. $this->metaDataId,
                                                    'title' => 'Table',
                                                    'onclick' => 'callDataView_'. $this->metaDataId .'('. $this->metaDataId .', this);'
                                                ), isset($this->metaLayoutBtn) ? $this->metaLayoutBtn : false 
                                            );
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="fa fa-map-marker"></i>', array(
                                                    'class' => 'btn btn-secondary btn-circle btn-sm default googleMapBtnByDataView_'. $this->metaDataId,
                                                    'title' => 'Map',
                                                    'onclick' => 'googleMapBtnByDataView_' . $this->metaDataId . '(this);'
                                                ), isset($this->isGoogleMap) ? $this->isGoogleMap : false
                                            );
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="fa fa-folder-open"></i>', array(
                                                    'class' => 'btn btn-secondary btn-circle btn-sm default',
                                                    'title' => 'Card view',
                                                    'onclick' => 'dataViewFilterCardViewForm_' . $this->metaDataId . '(this);'
                                                ), isset($this->isCardSee) ? $this->isCardSee : false
                                            );
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="fa fa-calendar"></i>', array(
                                                    'class' => 'btn btn-secondary btn-circle btn-sm default',
                                                    'title' => 'Calendar view',
                                                    'onclick' => 'callCalendarByMeta(' . $this->calendarMetaDataId . ');'
                                                ), isset($this->isCalendarSee) ? $this->isCalendarSee : false
                                            );
                                            
                                            if (issetParam($this->row['IS_EXCEL_EXPORT_BTN']) != '') {
                                                
                                                if (strpos($commandBtn, '<!--excelexportbutton-->') !== false) {
                                                    echo Html::anchor(
                                                            'javascript:;', '<i class="fa fa-file-excel-o"></i>', array(
                                                            'class' => 'btn btn-secondary btn-circle btn-sm default',
                                                            'title' => $this->lang->line('excel_btn'),
                                                            'onclick' => 'dataViewExportToExcel_' . $this->metaDataId . '();'
                                                        ), true
                                                    );
                                                }

                                            } else {
                                                
                                                echo Html::anchor(
                                                        'javascript:;', '<i class="far fa-file-excel"></i>', array(
                                                        'class' => 'btn btn-secondary btn-circle btn-sm default',
                                                        'title' => $this->lang->line('excel_btn'),
                                                        'onclick' => 'dataViewExportToExcel_' . $this->metaDataId . '();'
                                                    ), (!isset($this->row['IS_IGNORE_EXCEL_EXPORT']) || (isset($this->row['IS_IGNORE_EXCEL_EXPORT']) && $this->row['IS_IGNORE_EXCEL_EXPORT'] != '1'))
                                                );
                                            }
                                            
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="far fa-file-text"></i>', array(
                                                    'class' => 'btn btn-secondary btn-circle btn-sm default',
                                                    'title' => 'Text file',
                                                    'onclick' => 'dataViewExportToText_' . $this->metaDataId . '();'
                                                ), isset($this->isExportText) ? $this->isExportText : false
                                            );
                                            
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="far fa-print"></i>', array(
                                                    'class' => 'btn btn-secondary btn-circle btn-sm default',
                                                    'title' => 'Print',
                                                    'onclick' => 'dataViewExportToPrint_' . $this->metaDataId . '();'
                                                ), (issetParam($this->row['IS_DIRECT_PRINT']) == '1')
                                            );
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="icon-table2"></i>', array(
                                                    'class' => 'btn btn-secondary btn-sm btn-circle value-grid-merge-cell default',
                                                    'title' => 'Merge cell'
                                                ), (issetParam($this->dataGridOptionData['MERGECELLS']) == 'true' ? true : false)
                                            );
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="far fa-cog"></i>', array(
                                                    'class' => 'btn btn-secondary btn-sm btn-circle default',
                                                    'title' => $this->lang->line('user_configuration'),
                                                    'onclick' => 'dataViewAdvancedConfig_' . $this->metaDataId . '(this);'
                                                ),  (Ue::sessionUserId() === Config::getFromCache('ignoreDvBtnsByUserId')) ? false: true
                                            );
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="far fa-shopping-cart"></i> <span class="save-database-'. $this->metaDataId .'">0</span>', array(
                                                    'class' => 'btn btn-secondary btn-sm btn-circle default',
                                                    'onclick' => 'dataViewUseBasketView_' . $this->metaDataId . '(this);',
                                                    'title' => $this->lang->line('META_00113'),
                                                ), isset($this->useBasketBtn) ? $this->useBasketBtn : false
                                            );
                                            echo Html::anchor(
                                                    'javascript:;', (new Mduser())->iconQuickMenu($this->metaDataId), array(
                                                    'class' => 'btn btn-secondary btn-sm btn-circle default',
                                                    'onclick' => 'toQuickMenu(\''.$this->metaDataId.'\', \'dataview\', this);',
                                                    'title' => 'Quick menu',
                                                ), true
                                            );
                                            echo Html::anchor(
                                                    'javascript:;', (new Mduser())->iconHelpMenu($this->metaDataId), array(
                                                    'class' => 'btn btn-secondary btn-sm btn-circle default d-none',
                                                    'onclick' => 'dataViewHelp_' . $this->metaDataId . '(this);',
                                                    'title' => 'Тусламж',
                                                ), true
                                            );
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="far fa-language"></i>', array(
                                                    'class' => 'btn btn-secondary btn-sm btn-circle default',    
                                                    'onclick' => 'metaTranslator(this, \''.$this->metaDataId.'\');',
                                                    'title' => 'Translate',
                                                ), Mdlanguage::isTranslateOptionByConfig() 
                                            );
                                            echo Mdcommon::listHelpContentButton([
                                                'contentId' => issetParam($this->row['HELP_CONTENT_ID']), 
                                                'sourceId' => $this->metaDataId, 
                                                'fromType' => 'meta_dv'
                                            ]);
                                            ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php 
                } 
                
                $dataGridClass = ' ' . $this->dataGridOptionData['VIEWTHEME'];
                
                if (isset($this->dataViewCriteriaType) && ($this->dataViewCriteriaType == 'left web' || $this->dataViewCriteriaType == 'left web civil') && $this->layoutType != 'ecommerce_basket') {
                    $dataGridClass .= ' web-dataview';
                }
                ?>
                <div class="col-md-12 div-objectdatagrid-<?php echo $this->metaDataId . $dataGridClass; ?>" dv-metadataid="<?php echo $this->metaDataId; ?>">
                    <table id="objectdatagrid-<?php echo $this->metaDataId; ?>"></table>
                </div>
                <div class="div-dataGridLayout-<?php echo $this->metaDataId; ?>"></div>
                <div id="md-map-civil-<?php echo $this->metaDataId; ?>"></div>
                <?php if (issetParam($this->dataviewLegendData)) { ?>
                <div class="col-md-12">
                    <div class="row mt18">
                        <?php 
                        foreach ($this->dataviewLegendData as $legend) {
                            echo '<div class="col">';
                            echo '<span style="background-color: '.$legend['legendcolor'].'; height: 21px; width: 40px; position: absolute;"></span>';
                            echo '<span class="ml48 mr40 pt3 pb3 badge badge-secondary" style="font-size:13px">'.$legend['legendtext'].'</span>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
                <?php } ?>
            </div> 
        </div>
        <?php if ($this->metaDataId == '1529727324487237' || $this->metaDataId == '1539681311719') { ?>
            <style>
                #google-map-api-floating-panel {
                  position: absolute;
                  top: 36px;
                  right: 23px;
                  z-index: 5;
                  background-color: #fff;
                  padding: 5px;
                  border: 1px solid #c7c7c7;
                  text-align: center;
                  font-family: 'Roboto','sans-serif';
                  line-height: 20px;
                  padding-left: 6px;
                }                
                #google-map-api-duration-panel {
                  position: absolute;
                  top: 76px;
                  right: 23px;
                  z-index: 5;
                  background-color: #fff;
                  padding: 5px;
                  border: 1px solid #c7c7c7;
                  text-align: left;
                  font-family: 'Roboto','sans-serif';
                  line-height: 20px;
                  padding-left: 6px;
                }                
            </style>
            <div id="google-map-api-floating-panel" class="hide">
                <b>Явах төрөл: </b>
                <select id="googleMapApiDirectionMode">
                  <option value="DRIVING">Машинаар</option>
                  <option value="WALKING">Явганаар</option>
                  <option value="BICYCLING">Дугуйгаар</option>
                  <!--<option value="TRANSIT">Transit</option>-->
                </select>
            </div>        
            <div id="google-map-api-duration-panel" class="hide">
                <div id='google-map-api-distance-length'></div>
                <div id='google-map-api-duration-length'></div>
            </div>            
        <?php } ?>
        <div class="flex-fill overflow-auto <?php echo $this->dataViewClass; ?> hidden">
        <?php if (issetParam($this->row['IS_USE_BUTTON_MAP']) == '1') { ?>
                <div class="col-md-12 pl0 remove-type-<?php echo $this->metaDataId; ?> object-height-row3-minus-<?php echo $this->metaDataId ?>" >
                    <div class="table-toolbar">
                        <div class="row justify-content-between mt5 helper-mt dv-button-style-<?php echo $this->buttonBarStyle; ?>">
                            <div class="col-md dv-process-buttons pl20 d-flex flex-row align-items-center">
                                <?php
                                if (isset($this->openDefaultBp) && !empty($this->openDefaultBp)) {
                                    $defaultOpenTitle = trim(issetParam($this->title));
                                    if ($defaultOpenTitle != '&nbsp;' && $defaultOpenTitle != '' && $defaultOpenTitle != ' ' && $defaultOpenTitle != ' ' && $defaultOpenTitle != ' ') {
                                        echo '<div class="row d-none"><div class="col-md-12 uppercase is-bp-open-dataview-title mb10">'.$defaultOpenTitle.'</div></div>';
                                    }
                                }
                                if (($this->isTree || $this->dataViewHeaderData) && $this->dataViewCriteriaType == 'button' && $this->isCheckDataViewHeaderData) {
                                ?>
                                <div class="top-sidebar datagrid-filter-panel remove-type-<?php echo $this->metaDataId; ?> search-topsidebar-<?php echo $this->metaDataId; ?>">
                                    <div class="top-sidebar-content">
                                        <div class="col-md-12 mb10 mt10">
                                            <div class="tabbable-line">
                                                <ul class="nav nav-tabs">     
                                                    <?php if ($this->isTree) { ?>
                                                        <li class="nav-item">
                                                            <a href="#meta-tree-view-tab-1-<?php echo $this->metaDataId; ?>" class="nav-link <?php echo (!$this->dataViewHeaderData) ? 'active' : '' ?>" data-toggle="tab"><?php echo $this->lang->line('filter'); ?></a>
                                                        </li>      
                                                    <?php } if ($this->dataViewHeaderData) { ?>
                                                        <li class="nav-item">
                                                            <a href="#meta-search-view-tab-2-<?php echo $this->metaDataId; ?>" class="nav-link active" data-toggle="tab"><?php echo $this->lang->line('search'); ?></a>
                                                        </li>      
                                                    <?php } ?>
                                                </ul>
                                                <div class="tab-content">
                                                    <?php if ($this->isTree) { ?>
                                                        <div class="tab-pane in <?php echo (!$this->dataViewHeaderData) ? 'active' : '' ?> height-dynamic" id="meta-tree-view-tab-1-<?php echo $this->metaDataId; ?>">
                                                            <?php 
                                                            if (count($this->treeCategoryList) === 1) {
                                                                echo "<span class='hide'>";
                                                                echo Form::select(
                                                                    array(
                                                                        'name' => 'treeCategory',
                                                                        'id' => 'treeCategory',
                                                                        'op_value' => 'ID',
                                                                        'op_text' => 'NAME',
                                                                        'glue' => '-',
                                                                        'data' => $this->treeCategoryList,
                                                                        'onchange' => 'drawTree_' . $this->metaDataId . '();',
                                                                        'text' => 'notext'
                                                                    )
                                                                );
                                                                echo "</span>";
                                                            } else {
                                                                echo Form::select(
                                                                    array(
                                                                        'name' => 'treeCategory',
                                                                        'id' => 'treeCategory',
                                                                        'class' => 'form-control form-control-sm select2 mb10',
                                                                        'op_value' => 'ID',
                                                                        'op_text' => 'NAME',
                                                                        'glue' => '-',
                                                                        'data' => $this->treeCategoryList,
                                                                        'onchange' => 'drawTree_' . $this->metaDataId . '();',
                                                                        'text' => 'notext'
                                                                    )
                                                                );
                                                            }
                                                            ?>
                                                            <div id="treeContainer">
                                                                <div id="dataViewStructureTreeView_<?php echo $this->metaDataId; ?>" class="tree-demo"></div>
                                                            </div>
                                                            <form role="form" id="tree-click-form" method="post">
                                                                <input type="hidden" id="tree-click-hidden-input" />
                                                            </form>
                                                        </div>     
                                                    <?php } if ($this->dataViewHeaderData) { ?>
                                                        <div class="tab-pane in active" id="meta-search-view-tab-2-<?php echo $this->metaDataId; ?>">
                                                            <?php echo $this->defaultCriteria; ?> 
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $filterButton = '<a type="button" id="search-sidebar-'.$this->metaDataId.'" data-close-others="true" class="btn btn-circle top-stoggler btn-sm btn-secondary search-sidebar-'.$this->metaDataId.'"><i class="fa fa-search"></i> '.$this->lang->line('filter').' <i class="fa fa-angle-down"></i></a>';
                                }
                                if (($this->isTree || $this->dataViewHeaderData) && $this->dataViewCriteriaType == 'popup' && $this->isCheckDataViewHeaderData) {
                                ?>
                                <div class="search-topsidebar-popup-<?php echo $this->metaDataId; ?>" style="display: none;">
                                    <div class="col-md-12">
                                        <?php echo $this->defaultCriteria; ?> 
                                    </div>
                                </div>
                                <?php
                                $filterButton = '<a type="button" href="javascript:;" onclick="dvPopupCriteria'.$this->metaDataId.'()" id="search-sidebar-popup-'.$this->metaDataId.'" data-close-others="true" class="btn btn-circle top-stoggler btn-sm btn-secondary search-sidebar-popup-'.$this->metaDataId.'"><i class="fa fa-search"></i> '.$this->lang->line('filter').'</a>';
                                }
                                
                                $commandBtn = '';
                                if ($this->dataGridOptionData['INLINEEDIT'] == 'true' && false) {
                                    $commandBtn = ''.
                                    '<button type="button" title="Мөр нэмэх" onClick="insertRowInlineEditDataView_'.$this->metaDataId.'(this)" class="btn default btn-circle btn-sm inline-edit-actions-btn"><img src="'.URL.'assets/core/global/img/ico/inline_add.png"></button>
                                    <button type="button" title="Хадгалах" onClick="saveRowInlineEditDataView_'.$this->metaDataId.'(this)" class="btn default btn-circle btn-sm inline-edit-actions-btn"><img src="'.URL.'assets/core/global/img/ico/inline_save.png"></button>
                                    <button type="button" title="Нэмсэн мөр устгах" onClick="deleteRowInlineEditDataView_'.$this->metaDataId.'(this)" class="btn default btn-circle btn-sm inline-edit-actions-btn"><img src="'.URL.'assets/core/global/img/ico/inline_remove.png"></button> <span style="height: 25px; display: inline-block; border-right: solid 1px #7b7b7b;" class="mr5"></span>';
                                }

                                $commandBtn .= $this->dataViewProcessCommand['commandBtn']; $addonBtn = $wfmBtn = '';
                                
                                if ($this->isPrint) {
                                    
                                    $invoicePrintBtn = html_tag('button', array(
                                        'type' => 'button', 
                                        'class' => 'btn btn-sm btn-circle green', 
                                        'onclick' => 'dataViewPrintPreview_'.$this->metaDataId.'(\''.$this->metaDataId.'\', true, \'toolbar\', this);'
                                    ), '<i class="far fa-print"></i> '.($this->lang->line('printTemplate'.$this->metaDataId) == 'printTemplate'.$this->metaDataId ? $this->lang->line('printTemplate') : $this->lang->line('printTemplate'.$this->metaDataId)));
                                    
                                    if (issetParam($this->row['IS_INVOICE_PRINT_BTN']) != '') {
                                        
                                        $commandBtn = str_replace('<!--invoiceprintbutton-->', $invoicePrintBtn, $commandBtn);
                                        
                                    } else {
                                        $addonBtn .= $invoicePrintBtn;
                                    }
                                }
                                
                                if (isset($this->dataViewWorkFlowBtn) && $this->dataViewWorkFlowBtn == true) { 
                                    
                                    $wfmBtn = '<div class="btn-group workflow-btn-group-'.$this->metaDataId.'">
                                        <button type="button" class="btn btn-sm blue btn-circle dropdown-toggle workflow-btn-'.$this->metaDataId.'" data-toggle="dropdown"><i class="far fa-cogs"></i> '.$this->lang->line('change_workflow').'</button>
                                        <ul class="dropdown-menu workflow-dropdown-'.$this->metaDataId.'" role="menu"></ul>
                                    </div>';
                                    
                                    $commandBtn = str_replace('<!--changewfmstatus-->', $wfmBtn, $commandBtn, $wfmBtnReplace);
                                    
                                    if (!$wfmBtnReplace) {
                                        $addonBtn .= $wfmBtn;
                                    }
                                } 
                                
                                if (isset($this->useBasketBtn) && $this->useBasketBtn) {
                                    $addonBtn .= html_tag('button', array(
                                        'type' => 'button', 
                                        'class' => 'btn btn-sm btn-circle btn-secondary', 
                                        'onclick' => 'dataViewToBasket_'.$this->metaDataId.'(this);'
                                    ), '<i class="far fa-shopping-cart"></i> Сагсанд нэмэх');
                                }
                                
                                if (issetParam($this->row['IS_USE_SEMANTIC'])) {
                                    $addonBtn .= '<div class="btn-group dv-buttons-batch">'
                                        . '<button type="button" class="btn bg-slate btn-circle btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">'.$this->lang->line('dmrmap_button_name').'</button>'
                                        . '<ul class="dropdown-menu" role="menu">'
                                            . '<li><a href="javascript:;" onclick="dvDmRecordMapSet(this, \''.$this->metaDataId.'\', \''.$this->refStructureId.'\');"><i class="fa fa-link"></i> '.$this->lang->line('dmrmap_connect').'</a></li>'
                                            . '<li><a href="javascript:;" onclick="dvDmRecordMapList(this, \''.$this->metaDataId.'\');"><i class="fa fa-list"></i> '.$this->lang->line('dmrmap_history').'</a></li>'
                                        . '</ul>'
                                    . '</div>';
                                    
                                    $addonBtn .= html_tag('button', array(
                                        'type' => 'button', 
                                        'class' => 'btn btn-sm btn-circle btn-secondary', 
                                        'onclick' => 'dataViewToBasket(this);'
                                    ), '<i class="far fa-shopping-cart"></i> Сагсанд нэмэх');
                                }
                                
                                if ($commandBtn) {
                                    $commandBtn = str_replace('<!--endbutton-->', $addonBtn, $commandBtn);
                                    if (isset($filterButton)) {
                                        $commandBtn = str_replace('<!--startbutton-->', $filterButton, $commandBtn);
                                    }
                                } else {
                                    if (isset($filterButton)) {
                                        $addonBtn = $filterButton.$addonBtn;
                                    }
                                    $commandBtn = $addonBtn;
                                }
                                
                                echo $commandBtn;
                                ?>
                            </div>
                            <div class="col-md-auto d-flex flex-row align-items-center<?php echo isset($this->dataGridOptionData['SHOWTOOLBARRIGHT']) && $this->dataGridOptionData['SHOWTOOLBARRIGHT'] == '0' ? ' hidden' : ''; ?>">

                                <?php
                                echo $this->quickSearch; 
                                
                                if (isset($this->row['subQuery']) && $this->row['subQuery']) {
                                    echo Form::select(
                                        array(
                                            'class' => 'form-control select2 form-control-sm subquery-combo mr5', 
                                            'name' => 'subQueryId',
                                            'id' => 'subQueryId-'.$this->metaDataId,
                                            'data' => $this->row['subQuery'],
                                            'op_value' => 'ID',
                                            'op_text' => 'GLOBE_CODE', 
                                            'style' => 'position:relative;display:inline-block;vertical-align:middle;width:140px'
                                        )
                                    );
                                }
                                if (($this->dataViewCriteriaType == 'left web' || $this->dataViewCriteriaType == 'left web civil') || Config::getFromCache('CONFIG_IS_COLLAPSE_BUTTON')) { 
                                ?>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?php echo $this->lang->line('dropdown_action') ?> </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <?php 
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="fa fa-file"></i> '.$this->lang->line('META_VIEW_REPORT_TEMPLATE'), array(
                                                    'class' => 'dropdown-item',
                                                    'onclick' => 'objectReportTemplateView_'.$this->metaDataId.'()'
                                                ), $this->isReportTemplate  
                                            ); 
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="fa fa-dashboard"></i> '.$this->lang->line('META_VIEW_DASHBOARD'), array(
                                                    'class' => 'dropdown-item',
                                                    'onclick' => 'objectDashboardView_'.$this->metaDataId.'()'
                                                ), $this->isDashboard  
                                            ); 
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="icon-cube"></i> Pivot view', array(
                                                    'class' => 'dropdown-item',
                                                    'onclick' => 'dataViewPivotView(\''.$this->metaDataId.'\', this);'
                                                ), (defined('CONFIG_PIVOT_SERVICE_ADDRESS') && CONFIG_PIVOT_SERVICE_ADDRESS)  
                                            ); 
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="icon-qrcode"></i> Qrcode', array(
                                                    'class' => 'dropdown-item',
                                                    'onclick' => 'dataViewStatementPreview_'.$this->metaDataId.'(\''.$this->metaDataId.'\', true, \'toolbar\', this);'
                                                ), $this->isStatementBtnSee
                                            );  
                                            
                                            if (isset($this->row['dataViewLayoutTypes']['explorer']) || isset($this->row['dataViewLayoutTypes']['calendar'])) {
                                                
                                                if (isset($this->row['dataViewLayoutTypes']['calendar'])) {
                                                    $iconName = 'calendar';
                                                    $title = 'Calendar view';
                                                } else {
                                                    $iconName = 'folder';
                                                    $title = 'Explorer view';
                                                }
                                                
                                                echo Html::anchor(
                                                        'javascript:;', '<i class="icon-'.$iconName.'"></i> '.$title, array(
                                                        'title' => $title,
                                                        'class' => 'dropdown-item',
                                                        'onclick' => 'dataViewer_'.$this->metaDataId.'(this, \''.key($this->row['dataViewLayoutTypes']).'\', \''.$this->metaDataId.'\');'
                                                    ), true
                                                );
                                            }
                                                
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="icon-chart"></i> Layout', array(
                                                    'class' => 'dropdown-item callLayoutDataView_'. $this->metaDataId,
                                                    'title' => 'Layout',
                                                    'onclick' => 'callLayoutDataView_'. $this->metaDataId .'('. $this->metaLayoutLinkId .', this);'
                                                ), isset($this->metaLayoutBtn) ? $this->metaLayoutBtn : false
                                            ); 
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="icon-table"></i> Table' , array(
                                                    'class' => 'dropdown-item callDataView_'. $this->metaDataId,
                                                    'title' => 'Table',
                                                    'onclick' => 'callDataView_'. $this->metaDataId .'('. $this->metaDataId .', this);'
                                                ), isset($this->metaLayoutBtn) ? $this->metaLayoutBtn : false 
                                            ); 
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="icon-map"></i> Map', array(
                                                    'class' => 'dropdown-item googleMapBtnByDataView_'. $this->metaDataId,
                                                    'title' => 'Map',
                                                    'onclick' => 'googleMapBtnByDataView_' . $this->metaDataId . '(this);'
                                                ), isset($this->isGoogleMap) ? $this->isGoogleMap : false
                                            );                                 
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="icon-folder-open"></i> Card view', array(
                                                    'class' => 'dropdown-item dv-layout-type-'. $this->metaDataId,
                                                    'title' => 'Card view',
                                                    'onclick' => 'renderCardView_'. $this->metaDataId .'(this);',
                                                    'data-view-type' => $this->layoutType 
                                                ), isset($this->isCardSee) ? $this->isCardSee : false
                                            ); 
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="icon-calendar"></i> Calendar view', array(
                                                    'title' => 'Calendar view',
                                                    'class' => 'dropdown-item',
                                                    'onclick' => 'callCalendarByMeta(' . $this->calendarMetaDataId . ');'
                                                ), isset($this->isCalendarSee) ? $this->isCalendarSee : false
                                            ); 

                                            if (issetParam($this->row['IS_EXCEL_EXPORT_BTN']) != '') {
                                                
                                                if (strpos($commandBtn, '<!--excelexportbutton-->') !== false) {
                                                    echo Html::anchor(
                                                            'javascript:;', '<i class="icon-file-excel"></i> '.$this->lang->line('excel_btn'), array(
                                                            'title' => $this->lang->line('excel_btn'),
                                                            'class' => 'dropdown-item',
                                                            'onclick' => 'dataViewExportToExcel_' . $this->metaDataId . '();'
                                                        ), true
                                                    ); 
                                                }
                                                
                                            } else {
                                                echo Html::anchor(
                                                        'javascript:;', '<i class="icon-file-excel"></i> '.$this->lang->line('excel_btn'), array(
                                                        'title' => $this->lang->line('excel_btn'),
                                                        'class' => 'dropdown-item',
                                                        'onclick' => 'dataViewExportToExcel_' . $this->metaDataId . '();'
                                                    ), (!isset($this->row['IS_IGNORE_EXCEL_EXPORT']) || (isset($this->row['IS_IGNORE_EXCEL_EXPORT']) && $this->row['IS_IGNORE_EXCEL_EXPORT'] != '1'))
                                                ); 
                                            }

                                            echo Html::anchor(
                                                    'javascript:;', '<i class="icon-file-text"></i> Text file', array(
                                                    'title' => 'Text file',
                                                    'class' => 'dropdown-item',
                                                    'onclick' => 'dataViewExportToText_' . $this->metaDataId . '();'
                                                ), isset($this->isExportText) ? $this->isExportText : false
                                            ); 
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="far fa-print"></i> Print', array(
                                                    'title' => 'Print',
                                                    'class' => 'dropdown-item',
                                                    'onclick' => 'dataViewExportToPrint_' . $this->metaDataId . '();'
                                                ), (issetParam($this->row['IS_DIRECT_PRINT']) == '1')
                                            );
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="icon-table2"></i> Merge cell', array(
                                                    'class' => 'dropdown-item value-grid-merge-cell',
                                                    'title' => 'Merge cell'
                                                ), (issetParam($this->dataGridOptionData['MERGECELLS']) == 'true' ? true : false)
                                            ); 
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="icon-cog"></i> Тохиргоо', array(
                                                    'title' => $this->lang->line('user_configuration'),
                                                    'class' => 'dropdown-item',
                                                    'onclick' => 'dataViewAdvancedConfig_' . $this->metaDataId . '(this);'
                                                ),  (Ue::sessionUserId() === Config::getFromCache('ignoreDvBtnsByUserId')) ? false: true
                                            ); 
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="icon-cart"></i> Сагс <span class="save-database-'. $this->metaDataId .'">0</span>', array(
                                                    'onclick' => 'dataViewUseBasketView_' . $this->metaDataId . '(this);',
                                                    'class' => 'dropdown-item',
                                                    'title' => $this->lang->line('META_00113'),
                                                ), isset($this->useBasketBtn) ? $this->useBasketBtn : false
                                            ); 
                                            echo Html::anchor(
                                                    'javascript:;', (new Mduser())->iconQuickMenu($this->metaDataId) . ' QuickMenu', array(
                                                    'onclick' => 'toQuickMenu(\''.$this->metaDataId.'\', \'dataview\', this);',
                                                    'class' => 'dropdown-item',
                                                    'title' => 'Quick menu',
                                                ), true
                                            );
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="far fa-language"></i> Translate', array(
                                                    'onclick' => 'metaTranslator(this, \''.$this->metaDataId.'\');',
                                                    'class' => 'dropdown-item',
                                                    'title' => 'Translate',
                                                ), Mdlanguage::isTranslateOptionByConfig() 
                                            );
                                            echo Mdcommon::listHelpContentButton([
                                                'contentId' => issetParam($this->row['HELP_CONTENT_ID']), 
                                                'sourceId' => $this->metaDataId, 
                                                'fromType' => 'meta_dv', 
                                                'parentControl' => 'dropdown'
                                            ]);
                                            ?>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="btn-group btn-group-devided">
                                        <?php 
                                        echo Html::anchor(
                                                'javascript:;', '<i class="fa fa-file"></i>', array(
                                                'class' => 'btn btn-secondary btn-circle btn-sm default', 
                                                'title' => $this->lang->line('META_VIEW_REPORT_TEMPLATE'), 
                                                'onclick' => 'objectReportTemplateView_'.$this->metaDataId.'()'
                                            ), $this->isReportTemplate  
                                        ); 
                                        echo Html::anchor(
                                                'javascript:;', '<i class="fa fa-dashboard"></i>', array(
                                                'class' => 'btn btn-secondary btn-circle btn-sm default', 
                                                'title' => $this->lang->line('META_VIEW_DASHBOARD'), 
                                                'onclick' => 'objectDashboardView_'.$this->metaDataId.'()'
                                            ), $this->isDashboard  
                                        ); 
                                        echo Html::anchor(
                                                'javascript:;', '<i class="far fa-cube"></i>', array(
                                                'class' => 'btn btn-secondary btn-circle btn-sm default', 
                                                'title' => 'Pivot view', 
                                                'onclick' => 'dataViewPivotView(\''.$this->metaDataId.'\', this);'
                                            ), (defined('CONFIG_PIVOT_SERVICE_ADDRESS') && CONFIG_PIVOT_SERVICE_ADDRESS)  
                                        ); 
                                        echo Html::anchor(
                                                'javascript:;', (Config::isCode('dataViewStatementHtml') ? Config::getFromCache('dataViewStatementHtml'): '<i class="fa fa-qrcode"></i>'), array(
                                                'title' => 'Жагсаалтыг хэвлэх',     
                                                'class' => 'btn btn-secondary btn-circle btn-sm default',
                                                'onclick' => 'dataViewStatementPreview_'.$this->metaDataId.'(\''.$this->metaDataId.'\', true, \'toolbar\', this);'
                                            ), $this->isStatementBtnSee
                                        ); 
                                        
                                        if (isset($this->row['dataViewLayoutTypes']['explorer']) || isset($this->row['dataViewLayoutTypes']['calendar'])) {
                                                
                                            if (isset($this->row['dataViewLayoutTypes']['calendar'])) {
                                                $iconName = 'calendar';
                                                $title = 'Calendar view';
                                            } else {
                                                $iconName = 'folder';
                                                $title = 'Explorer view';
                                            }

                                            echo Html::anchor(
                                                    'javascript:;', '<i class="fa fa-'.$iconName.'"></i>', array(
                                                    'class' => 'btn btn-secondary btn-circle btn-sm default',
                                                    'title' => $title,
                                                    'onclick' => 'dataViewer_'.$this->metaDataId.'(this, \''.key($this->row['dataViewLayoutTypes']).'\', \''.$this->metaDataId.'\');'
                                                ), true
                                            );
                                        }
                                            
                                        echo Html::anchor(
                                                'javascript:;', '<i class="fa fa-th-large"></i>', array(
                                                'class' => 'btn btn-secondary btn-circle btn-sm default dv-layout-type-'. $this->metaDataId,
                                                'title' => 'Card View',
                                                'onclick' => 'renderCardView_'. $this->metaDataId .'(this);', 
                                                'data-view-type' => $this->layoutType    
                                            ), ($this->layoutType) ? true : false
                                        );
                                        echo Html::anchor(
                                                'javascript:;', '<i class="fa fa-bar-chart-o"></i>', array(
                                                'class' => 'btn btn-secondary btn-circle btn-sm default callLayoutDataView_'. $this->metaDataId,
                                                'title' => 'Layout',
                                                'onclick' => 'callLayoutDataView_'. $this->metaDataId .'('. $this->metaLayoutLinkId .', this);'
                                            ), isset($this->metaLayoutBtn) ? $this->metaLayoutBtn : false
                                        );
                                        echo Html::anchor(
                                                'javascript:;', '<i class="fa fa-table"></i>', array(
                                                'class' => 'btn btn-secondary btn-circle btn-sm default callDataView_'. $this->metaDataId,
                                                'title' => 'Table',
                                                'onclick' => 'callDataView_'. $this->metaDataId .'('. $this->metaDataId .', this);'
                                            ), isset($this->metaLayoutBtn) ? $this->metaLayoutBtn : false 
                                        );
                                        echo Html::anchor(
                                                'javascript:;', '<i class="fa fa-map-marker"></i>', array(
                                                'class' => 'btn btn-secondary btn-circle btn-sm default googleMapBtnByDataView_'. $this->metaDataId,
                                                'title' => 'Map',
                                                'onclick' => 'googleMapBtnByDataView_' . $this->metaDataId . '(this);'
                                            ), isset($this->isGoogleMap) ? $this->isGoogleMap : false
                                        );
                                        echo Html::anchor(
                                                'javascript:;', '<i class="fa fa-folder-open"></i>', array(
                                                'class' => 'btn btn-secondary btn-circle btn-sm default',
                                                'title' => 'Card view',
                                                'onclick' => 'dataViewFilterCardViewForm_' . $this->metaDataId . '(this);'
                                            ), isset($this->isCardSee) ? $this->isCardSee : false
                                        );
                                        echo Html::anchor(
                                                'javascript:;', '<i class="fa fa-calendar"></i>', array(
                                                'class' => 'btn btn-secondary btn-circle btn-sm default',
                                                'title' => 'Calendar view',
                                                'onclick' => 'callCalendarByMeta(' . $this->calendarMetaDataId . ');'
                                            ), isset($this->isCalendarSee) ? $this->isCalendarSee : false
                                        );
                                        
                                        if (issetParam($this->row['IS_EXCEL_EXPORT_BTN']) != '') {
                                            
                                            if (strpos($commandBtn, '<!--excelexportbutton-->') !== false) {
                                                echo Html::anchor(
                                                        'javascript:;', '<i class="fa fa-file-excel-o"></i>', array(
                                                        'class' => 'btn btn-secondary btn-circle btn-sm default',
                                                        'title' => $this->lang->line('excel_btn'),
                                                        'onclick' => 'dataViewExportToExcel_' . $this->metaDataId . '();'
                                                    ), true
                                                );
                                            }

                                        } else {
                                            
                                            echo Html::anchor(
                                                    'javascript:;', '<i class="fa fa-file-excel-o"></i>', array(
                                                    'class' => 'btn btn-secondary btn-circle btn-sm default',
                                                    'title' => $this->lang->line('excel_btn'),
                                                    'onclick' => 'dataViewExportToExcel_' . $this->metaDataId . '();'
                                                ), (!isset($this->row['IS_IGNORE_EXCEL_EXPORT']) || (isset($this->row['IS_IGNORE_EXCEL_EXPORT']) && $this->row['IS_IGNORE_EXCEL_EXPORT'] != '1'))
                                            );
                                        }
                                        
                                        echo Html::anchor(
                                                'javascript:;', '<i class="fa fa-file-text-o"></i>', array(
                                                'class' => 'btn btn-secondary btn-circle btn-sm default',
                                                'title' => 'Text file',
                                                'onclick' => 'dataViewExportToText_' . $this->metaDataId . '();'
                                            ), isset($this->isExportText) ? $this->isExportText : false
                                        );
                                        
                                        echo Html::anchor(
                                                'javascript:;', '<i class="far fa-print"></i>', array(
                                                'class' => 'btn btn-secondary btn-circle btn-sm default',
                                                'title' => 'Print',
                                                'onclick' => 'dataViewExportToPrint_' . $this->metaDataId . '();'
                                            ), (issetParam($this->row['IS_DIRECT_PRINT']) == '1')
                                        );
                                        echo Html::anchor(
                                                'javascript:;', '<i class="icon-table2"></i>', array(
                                                'class' => 'btn btn-secondary btn-sm btn-circle value-grid-merge-cell default',
                                                'title' => 'Merge cell'
                                            ), true
                                        );
                                        echo Html::anchor(
                                                'javascript:;', '<i class="far fa-cog"></i>', array(
                                                'class' => 'btn btn-secondary btn-sm btn-circle default',
                                                'title' => $this->lang->line('user_configuration'),
                                                'onclick' => 'dataViewAdvancedConfig_' . $this->metaDataId . '(this);'
                                            ),  (Ue::sessionUserId() === Config::getFromCache('ignoreDvBtnsByUserId')) ? false: true
                                        );
                                        echo Html::anchor(
                                                'javascript:;', '<i class="far fa-shopping-cart"></i> <span class="save-database-'. $this->metaDataId .'">0</span>', array(
                                                'class' => 'btn btn-secondary btn-sm btn-circle default',
                                                'onclick' => 'dataViewUseBasketView_' . $this->metaDataId . '(this);',
                                                'title' => $this->lang->line('META_00113'),
                                            ), isset($this->useBasketBtn) ? $this->useBasketBtn : false
                                        );
                                        echo Html::anchor(
                                                'javascript:;', (new Mduser())->iconQuickMenu($this->metaDataId), array(
                                                'class' => 'btn btn-secondary btn-sm btn-circle default',
                                                'onclick' => 'toQuickMenu(\''.$this->metaDataId.'\', \'dataview\', this);',
                                                'title' => 'Quick menu',
                                            ), true
                                        );
                                        echo Html::anchor(
                                                'javascript:;', '<i class="far fa-language"></i>', array(
                                                'class' => 'btn btn-secondary btn-sm btn-circle default',    
                                                'onclick' => 'metaTranslator(this, \''.$this->metaDataId.'\');',
                                                'title' => 'Translate',
                                            ), Mdlanguage::isTranslateOptionByConfig() 
                                        );
                                        echo Mdcommon::listHelpContentButton([
                                            'contentId' => issetParam($this->row['HELP_CONTENT_ID']), 
                                            'sourceId' => $this->metaDataId, 
                                            'fromType' => 'meta_dv'
                                        ]);
                                        ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>        
            <div id="md-map-canvas-<?php echo $this->metaDataId; ?>" class="<?php echo isset($this->row['dataViewLayoutTypes']['locationTracking']) ? 'mapLocationTracking' : '' ?>" style="display: none;"></div>
        </div>
        <?php if (issetParam($this->row['SHOW_POSITION']) === 'left') { ?>
            </div>
        <?php } ?>
    </div>
</div>

<?php 
if ($this->isDashboard || $this->isReportTemplate) { 
?>
<div id="objectDashboardView_<?php echo $this->metaDataId; ?>"></div>
<div id="objectReportTemplateView_<?php echo $this->metaDataId; ?>"></div>
<?php
} 

echo Form::hidden(array('id' => 'cardViewerFieldPath')); 
echo Form::hidden(array('id' => 'cardViewerValue'));
echo Form::hidden(array('id' => 'treeFolderValue')); 
echo Form::hidden(array('id' => 'currentSelectedRowIndex')); 
echo Form::hidden(array('id' => 'refStructureId', 'value' => $this->refStructureId)); 
echo Form::hidden(array('id' => 'isDynamicHeightDatagrid', 'value' => $this->isDynamicHeight)); 
?>

<div class="right-sidebar" data-status="closed">
    <div class="stoggler sidebar-right hide">
        <span class="fa fa-chevron-right hide">&nbsp;</span> 
        <span class="fa fa-chevron-left">&nbsp;</span>
    </div>
    <div class="right-sidebar-content"></div>
</div>
<div class="clearfix w-100"></div>

<?php echo $this->mainDvScripts; ?>