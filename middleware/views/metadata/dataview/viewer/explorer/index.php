<div class="center-sidebar col-md-12" id="objectDataView_<?php echo $this->metaDataId; ?>">
    <div class="row">
        <?php echo $this->detailHeader; ?>
        <div class="<?php echo $this->dataViewClass; if (!$this->isTree && !$this->dataViewHeaderData) { echo ' pl0'; }?> col right-sidebar-content-for-resize <?php echo (isset($this->dataViewCriteriaType) && ($this->dataViewCriteriaType == 'left web' || $this->dataViewCriteriaType == 'left web civil') && issetParam($this->isEmptyCriteria)) ? 'web-tabdataview' : ''; ?>">
            <div class="row <?php echo (isset($this->dataViewCriteriaType) && ($this->dataViewCriteriaType == 'left web' || $this->dataViewCriteriaType == 'left web civil') && issetParam($this->isEmptyCriteria)) ? 'web-margin-left' : '' ?>">    
                <?php if ((isset($this->hiddenFields) && ($this->hiddenFields == '0' || $this->hiddenFields == 'false')) && (!isset($this->dataGridOptionData['SHOWTOOLBAR']) || $this->dataGridOptionData['SHOWTOOLBAR'] != '0')) { ?>
                    <div class="col-md-12 remove-type-<?php echo $this->metaDataId; ?> object-height-row3-minus-<?php echo $this->metaDataId ?>" >
                        <div class="table-toolbar">
                            <div class="d-flex dv-button-style-<?php echo $this->buttonBarStyle; ?>">
                                <div class="col p-0">
                                    <div class="dv-process-buttons">
                                    <?php 
                                    if ($this->isTree || $this->dataViewHeaderData) {
                                            if ($this->dataViewCriteriaType == 'button' && $this->isCheckDataViewHeaderData) {
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
                                                                            <?php if (count($this->treeCategoryList) === 1) {
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
                                                                    <?php } ?>
                                                                    <?php if ($this->dataViewHeaderData) { ?>
                                                                        <div class="tab-pane in active " id="meta-search-view-tab-2-<?php echo $this->metaDataId; ?>">
                                                                            <?php echo $this->defaultCriteria; ?> 
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <a type="button" id="search-sidebar-<?php echo $this->metaDataId; ?>" data-close-others="true" class="btn btn-circle top-stoggler btn-sm btn-secondary search-sidebar-<?php echo $this->metaDataId; ?>"><?php echo $this->lang->line('filter'); ?> <i class="fa fa-angle-down"></i></a>
                                    <?php
                                        }
                                    }
                                    
                                    $commandBtn = $this->dataViewProcessCommand['commandBtn']; $addonBtn = $wfmBtn = '';
                                    
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
                                    <div class="btn-group btn-group-devided">
                                        <?php
                                        echo Html::anchor(
                                                'javascript:;', '<i class="fa fa-list"></i>', array(
                                                'class' => 'btn btn-secondary btn-circle btn-sm default',
                                                'title' => 'Detail view',
                                                'onclick' => 'dataViewer_'.$this->metaDataId.'(this, \'detail\', \''.$this->metaDataId.'\');'
                                            ), true
                                        );
                                        echo Html::anchor(
                                                'javascript:;', '<i class="fa fa-folder-open"></i>', array(
                                                'class' => 'btn btn-secondary btn-circle btn-sm default',
                                                'title' => 'Card view',
                                                'onclick' => 'dataViewFilterCardViewForm_' . $this->metaDataId . '(this);'
                                            ), isset($this->isCardSee) ? $this->isCardSee : false
                                        );
                                        /*echo Html::anchor(
                                                'javascript:;', '<i class="fa fa-info-circle"></i>', array(
                                                'class' => 'btn btn-secondary btn-sm btn-circle default',
                                                'onclick' => 'getHelpContent(1, \''. $this->metaDataId .'\', \'\');',
                                                'title' => 'Тусламж',
                                            ), true
                                        );
                                        echo Html::anchor(
                                                'javascript:;', '<i class="fa fa-info"></i>', array(
                                                'class' => 'btn btn-secondary btn-sm btn-circle default',
                                                'onclick' => 'getHelpContent(1, \''. $this->metaDataId .'\', \'\');',
                                                'title' => 'Тусламж',
                                            ), true
                                        );*/
                                        echo Html::anchor(
                                                'javascript:;', '<i class="fa fa-refresh"></i>', array(
                                                'class' => 'btn btn-secondary btn-circle btn-sm default refresh_btn',
                                                'title' => $this->lang->line('refresh_btn'),
                                                'onclick' => 'explorerRefresh_' . $this->metaDataId . '(this);'
                                            ), true
                                        );
                                        echo Html::anchor(
                                                'javascript:;', (new Mduser())->iconQuickMenu($this->metaDataId), array(
                                                'class' => 'btn btn-secondary btn-sm btn-circle default',
                                                'onclick' => 'toQuickMenu(\''.$this->metaDataId.'\', \'dataview\', this);',
                                                'title' => 'Quick menu',
                                            ), true
                                        );
                                        echo Html::anchor(
                                                'javascript:;', '<i class="far fa-print"></i>', array(
                                                'class' => 'btn btn-secondary btn-circle btn-sm default',
                                                'title' => $this->lang->line('print_btn'),
                                                'onclick' => 'explorerPrint_' . $this->metaDataId . '(this);'
                                            ), issetParam($this->row['IS_DIRECT_PRINT'])
                                        );
                                        echo Html::anchor(
                                                'javascript:;', '<i class="far fa-language"></i>', array(
                                                'class' => 'btn btn-secondary btn-sm btn-circle default',    
                                                'onclick' => 'metaTranslator(this, \''.$this->metaDataId.'\');',
                                                'title' => 'Translate',
                                            ), Mdlanguage::isTranslateOptionByConfig() 
                                        );
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="col">
                    <div class="explorer-table">
                        <div class="explorer-table-row">
                            <div id="objectdatagrid-<?php echo $this->metaDataId; ?>" class="not-datagrid explorer-table-cell bgnone div-objectdatagrid-<?php echo $this->metaDataId; ?>"></div>
                            <div class="explorer-table-cell-sidebar explorer-sidebar-<?php echo $this->metaDataId; ?> d-none"></div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>
</div>
<div id="objectDashboardView_<?php echo $this->metaDataId; ?>"></div>
<?php 
echo Form::hidden(array('id' => 'cardViewerFieldPath')); 
echo Form::hidden(array('id' => 'cardViewerValue')); 
echo Form::hidden(array('id' => 'treeFolderValue')); 
echo Form::hidden(array('id' => 'refStructureId', 'value' => $this->refStructureId)); 
echo $this->mainDvScripts; 
?>