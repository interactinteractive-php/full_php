<?php
    $euseBasket = isset($this->useBasket) ? $this->useBasket : false;
?>
<div class="page-content ecommerce_<?php echo $this->metaDataId ?> <?php echo ($this->row['LAYOUT_TYPE'] === 'ecommerce' ? ' dvecommerce' : ''); echo ($euseBasket && issetParam($this->istwocolumn)) == '1' ? 'padding-left-15 padding-right-15' : '' ?> <?php echo isset($this->appendClass) ? $this->appendClass : '' ?> <?php echo $euseBasket ? 'dialog-ecommerce-basket' : ''; ?>" id="objectDataView_<?php echo $this->metaDataId; ?>">
    <?php 
    $detectFilter = ((strpos($this->defaultCriteria, 'param[') === false && strpos($this->defaultCriteria, 'tab-lookupdata-') === false) && empty($this->getChildDataviewData)) ? ' hidden' : '';
    if (!$euseBasket) {
    ?>
        <div class="sidebar-sticky sidebar sidebar-light sidebar-secondary sidebar-component sidebar-expand-md<?php echo ($this->dataViewCriteriaType == 'hidden' ? ' hidden' : '') . $detectFilter; ?> ecommerce-left-sidebar">
            <div class="sidebar-content">
                <?php if ($this->getChildDataviewData) {
                    $topCheck = false;
                    foreach ($this->getChildDataviewData as $dvkey => $dv) {
                    if (($dv['permission'] == 0 || $dvkey == 0) && empty($dv['SHOW_POSITION'])) {
                        $topCheck = true;
                    }} if ($topCheck) { ?>
                    <div>
                        <ul class="nav navbar-nav sub-dv-list-<?php echo $this->metaDataId; ?> bp-icon-selection p-2" data-choose-type="single">
                            <div class="dvecommercetitle">
                                <h3><?php echo isset($this->row['LIST_MENU_NAME']) ? $this->lang->line($this->row['LIST_MENU_NAME']) : ''; ?></h3>
                            </div>
                            <?php foreach ($this->getChildDataviewData as $dvkey => $dv) {
                                if (($dv['permission'] == 0 || $dvkey == 0) && empty($dv['SHOW_POSITION'])) { ?>
                                    <li class="" data-id="<?php echo $dvkey; ?>" data-permission="<?php echo $dv['permission']; ?>" data-meta-data-id="<?php echo $dv['TRG_META_DATA_ID']; ?>" onclick="appMultiTabEcommerce<?php echo $this->metaDataId; ?>(this, '<?php echo $dv['TRG_META_DATA_ID']; ?>', '<?php echo $dv['META_DATA_NAME']; ?>')">
                                        <div class="item-icon-selection">
                                            <div><img src="assets/custom/addon/admin/layout4/img/user.png" onerror="onBankImgError(this);"></div>
                                            <p><?php echo $dv['META_DATA_NAME']; ?></p>
                                            <span class="m-menu__link-badge">
                                                <span class="badge badge-pill" title="<?php echo $dv['count']; ?>"><?php echo $dv['count']; ?></span>
                                            </span>
                                        </div>
                                    </li>
                            <?php }} ?>
                        </ul>
                    </div>
                <?php }} ?>
                <div class="card-body p-0">
                    <ul class="nav nav-tabs nav-tabs-bottom nav-justified mb-0">
                        <li class="nav-item">
                            <a href="#basket-tab-popup1-<?php echo $this->metaDataId; ?>" class="nav-link v2" data-toggle="tab">
                                <span><i class="icon-search4 mr-1"></i></span>
                                <span>Шүүлтүүр</span>
                            </a>
                        </li>
                        <?php if ($this->isTree) { ?>
                        <li class="nav-item">
                            <a href="#basket-tab-popup2-<?php echo $this->metaDataId; ?>" class="nav-link v2" data-toggle="tab">
                                <span><i class="icon-tree7 mr-1"></i></span>
                                <span>Tree</span>
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                    <div class="ecommerce-criteria-wrap-<?php echo $this->metaDataId; ?> tab-content">
                        <div class="p-2 tab-pane fade <?php echo ($this->isTree) ? '' : 'show active' ?>" id="basket-tab-popup1-<?php echo $this->metaDataId; ?>">
                            <?php echo $this->defaultCriteria; ?>
                        </div>
                        <?php if ($this->isTree) { ?>
                            <div class="tab-pane fade show active" id="basket-tab-popup2-<?php echo $this->metaDataId; ?>">
                                <div class="card-body tree-js dynamic-height-<?php echo $this->metaDataId; ?>">
                                    <div>
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
                                </div>
                            </div>
                        <?php } ?>   
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    
    $commandBtn = '';
    $commandBtn .= $this->dataViewProcessCommand['commandBtn'];
    $addonBtn = $wfmBtn = '';

    if ($this->isPrint) {
        $addonBtn .= html_tag('button', array(
            'type' => 'button',
            'class' => 'btn btn-sm btn-circle green',
            'onclick' => 'dataViewPrintPreview_' . $this->metaDataId . '(\'' . $this->metaDataId . '\', true, \'toolbar\', this);'
        ), '<i class="far fa-print"></i> ' . ($this->lang->line('printTemplate' . $this->metaDataId) == 'printTemplate' . $this->metaDataId ? $this->lang->line('printTemplate') : $this->lang->line('printTemplate' . $this->metaDataId)));
    }
    
    if (isset($this->dataViewWorkFlowBtn) && $this->dataViewWorkFlowBtn == true) {
        $wfmBtn = '<ul class="turulbtn workflow-dropdown-' . $this->metaDataId . '" role="menu"></ul>
                <li class="nav-item btn btn-sm btn-group workflow-btn-group-' . $this->metaDataId . '">'
                    . '<button type="button" class="btn hidden btn-sm blue btn-circle dropdown-toggle workflow-btn-' . $this->metaDataId . '" data-toggle="dropdown">'
                    . '<i class="fa icon-shuffle"></i> ' . $this->lang->line('change_workflow') . '</button>'
                . '</li>';

        $commandBtn = str_replace('<!--changewfmstatus-->', $wfmBtn, $commandBtn, $wfmBtnReplace);

        if (!$wfmBtnReplace) {
            $addonBtn .= $wfmBtn;
        }
    }
    
    if ($this->refStructureId) {
                                            
        if (strpos($commandBtn, '<!--pfaddeditlogview-->') !== false && strpos($commandBtn, '<!--pfremovedlogview-->') !== false) {

            $isLogRecover = (strpos($commandBtn, '<!--pfrestoredeleteddata-->') !== false) ? 'true' : 'false';

            $addonBtn .= '<a href="javascript:;" class="btn btn-warning btn-circle btn-sm" onclick="bpRecordHistoryLogList(this, \''.$this->metaDataId.'\', \''.$this->refStructureId.'\');"><i class="icon-history"></i> '.$this->lang->line('PF_ADD_EDIT_LOG_VIEW').'</a>
                    <a href="javascript:;" class="btn btn-warning btn-circle btn-sm" onclick="bpRecordHistoryRemovedLogList(this, \''.$this->metaDataId.'\', \''.$this->refStructureId.'\', '.$isLogRecover.');"><i class="icon-calendar5"></i> '.$this->lang->line('PF_REMOVE_LOG_VIEW').'</a>';

        } elseif (strpos($commandBtn, '<!--pfaddeditlogview-->') !== false) {

            $addonBtn .= html_tag('button', array(
                'type' => 'button', 
                'class' => 'btn btn-sm btn-circle btn-secondary', 
                'onclick' => 'bpRecordHistoryLogList(this, \''.$this->metaDataId.'\', \''.$this->refStructureId.'\');'
            ), '<i class="icon-history"></i> ' . $this->lang->line('PF_ADD_EDIT_LOG_VIEW'));

        } elseif (strpos($commandBtn, '<!--pfremovedlogview-->') !== false) {

            $isLogRecover = (strpos($commandBtn, '<!--pfrestoredeleteddata-->') !== false) ? 'true' : 'false';

            $addonBtn .= html_tag('button', array(
                'type' => 'button', 
                'class' => 'btn btn-sm btn-circle btn-secondary', 
                'onclick' => 'bpRecordHistoryRemovedLogList(this, \''.$this->metaDataId.'\', \''.$this->refStructureId.'\', '.$isLogRecover.');'
            ), '<i class="icon-calendar5"></i> ' . $this->lang->line('PF_REMOVE_LOG_VIEW'));
        }
    }
    
    if ($commandBtn) {
        $commandBtn = str_replace('<!--endbutton-->', $addonBtn, $commandBtn);
        if (isset($filterButton)) {
            $commandBtn = str_replace('<!--startbutton-->', $filterButton, $commandBtn);
        }
    } else {
        if (isset($filterButton)) {
            $addonBtn = $filterButton . $addonBtn;
        }
        $commandBtn = $addonBtn;
    }

    $rightShowSidebar = !empty($commandBtn) || $euseBasket ? isset($this->notuseSidebars) ? false : true : false;
    
    ?>
    <div class="content-wrapper">
        <div class="ecommerce-breadcumb header-elements-md-inline<?php echo $euseBasket && issetParam($this->istwocolumn) ? ' d-none' : ' '; ?>">
            <h5 class="list_name"><?php echo isset($this->row['LIST_NAME']) ? $this->lang->line($this->row['LIST_NAME']) : '---'; ?></h5>
            
            <?php echo $euseBasket && ($this->chooseTypeBasket != 'single' && $this->chooseTypeBasket != 'singlealways') ? '<a type="button" class=" datagrid-choose-btn pull-right" onclick="selectAllBasket_'. $this->metaDataId .'(this)"><i class="fa fa-check"></i> Бүгдийг сонгох</a>' : ' '; ?>
            <div class="<?php echo !$euseBasket ? '' : ' hidden'; ?>">
                <div class="ecommerce-buttons header-elements">
                    <?php 
                    if (!empty($this->dataViewProcessCommand['commandBtnPosition'])) { ?>
                        <div class="top-process-btn">
                            <?php 
                            $topPositionCommand = '';
                            foreach ($this->dataViewProcessCommand['commandBtnPosition'] as $rowBtn) {
                                if ($rowBtn['position'] === 'top') {
                                    $topPositionCommand .= $rowBtn['html'];
                                }
                            }
                            
                            if ($this->refStructureId) {
                                            
                                if (strpos($topPositionCommand, '<!--pfaddeditlogview-->') !== false && strpos($topPositionCommand, '<!--pfremovedlogview-->') !== false) {

                                    $isLogRecover = (strpos($topPositionCommand, '<!--pfrestoredeleteddata-->') !== false) ? 'true' : 'false';

                                    $topPositionCommand .= '<div class="btn-group dv-buttons-batch">
                                        <button class="btn btn-secondary btn-circle btn-sm dropdown-toggle" type="button" data-toggle="dropdown"><i class="icon-history"></i> '.$this->lang->line('PF_VIEW_LOG').'</button>
                                        <ul class="dropdown-menu">
                                            <li><a href="javascript:;" onclick="bpRecordHistoryLogList(this, \''.$this->metaDataId.'\', \''.$this->refStructureId.'\');">'.$this->lang->line('PF_ADD_EDIT_LOG_VIEW').'</a></li>
                                            <li><a href="javascript:;" onclick="bpRecordHistoryRemovedLogList(this, \''.$this->metaDataId.'\', \''.$this->refStructureId.'\', '.$isLogRecover.');">'.$this->lang->line('PF_REMOVE_LOG_VIEW').'</a></li>
                                        </ul>
                                    </div>';

                                } elseif (strpos($topPositionCommand, '<!--pfaddeditlogview-->') !== false) {

                                    $topPositionCommand .= html_tag('button', array(
                                        'type' => 'button', 
                                        'class' => 'btn btn-sm btn-circle btn-secondary', 
                                        'onclick' => 'bpRecordHistoryLogList(this, \''.$this->metaDataId.'\', \''.$this->refStructureId.'\');'
                                    ), $this->lang->line('PF_ADD_EDIT_LOG_VIEW'));

                                } elseif (strpos($topPositionCommand, '<!--pfremovedlogview-->') !== false) {

                                    $isLogRecover = (strpos($topPositionCommand, '<!--pfrestoredeleteddata-->') !== false) ? 'true' : 'false';

                                    $topPositionCommand .= html_tag('button', array(
                                        'type' => 'button', 
                                        'class' => 'btn btn-sm btn-circle btn-secondary', 
                                        'onclick' => 'bpRecordHistoryRemovedLogList(this, \''.$this->metaDataId.'\', \''.$this->refStructureId.'\', '.$isLogRecover.');'
                                    ), $this->lang->line('PF_REMOVE_LOG_VIEW'));
                                }
                            }
                                        
                            echo $topPositionCommand;
                            ?>
                        </div>
                    <?php 
                    }
                    
                    if (Config::getFromCache('ECOMMERCE_GANTT_CHART')) {
                        echo Html::anchor(
                            'javascript:;', '<i class="icon-stats-bars"></i>', array(
                                'class' => 'btn btn-success btn-sm sidebar-control d-none d-md-block callGanttView_' . $this->metaDataId,
                                'title' => Lang::line('viewtype_ganttchart'),
                                'ddonclick' => 'callGanttView_' . $this->metaDataId . '(' . $this->metaDataId . ', this);',
                                'onclick' => 'dataViewer_' . $this->metaDataId . '(this, \'ganttchart\', ' . $this->metaDataId . ');'
                            ), $this->ganttChartView
                        );
                    }
                    if (issetParam($this->viewType) == 'ganttchart') { ?>
                        <button type="button" class="btn btn-primary btn-icon card-switch" onclick="dataViewer_<?php echo $this->metaDataId ?>(this, 'detail', '<?php echo $this->metaDataId ?>');" data-view-type="detail" data-old-type="ecommerce" title="<?php echo Lang::line('viewtype_list'); ?>"><i class="icon-list"></i></button>
                    <?php } ?>
                    <div class="ecomcardview">
                        <?php if (isset($GLOBALS['ecommerceAdvancedCriteria']) && $GLOBALS['ecommerceAdvancedCriteria']) { ?>
                            <button type="button" class="btn btn-icon mr-0" onclick="dvecommerceAdvancedCriteria<?php echo $this->metaDataId; ?>(this)">
                                <i class="icon-search4"></i>
                            </button>
                        <?php 
                        }
                        if (!isset($this->notuseSidebars)) {
                            include_once "sub/ecommerceButtons.php";
                        } ?>
                    </div>

                    <?php if (!$euseBasket) {                
                        if (isset($this->notuseSidebars)) {
//                            if (!$rightShowSidebar) {
//                                echo $commandBtn;
//                            }
                        } else { 
                            echo Html::anchor(
                                'javascript:;', '<i class="far fa-shopping-cart"></i> <span class="save-database-'. $this->metaDataId .'">0</span>', array(
                                'class' => 'btn btn-secondary btn-sm btn-circle default',
                                'onclick' => 'dataViewUseBasketView_' . $this->metaDataId . '(this);',
                                'title' => $this->lang->line('META_00113'),
                            ), isset($this->useBasketBtn) ? $this->useBasketBtn : false
                            );                               
                        ?>
                        <a href="javascript:void(0);" class="btn btn-success btn-sm sidebar-control sidebar-secondary-toggle d-none d-md-block">
                            <i class="icon-indent-decrease"></i>
                        </a>
                        <a href="javascript:void(0);" data-isusesidebar="<?php echo $this->isUseSidebar ?>" class="btn btn-success btn-sm sidebar-control sidebar-right-toggle d-none d-md-block">
                            <i class="icon-indent-increase"></i>
                        </a>
                    <?php }
                    } ?>
                </div>
            </div>
        </div>

        <?php
        if ($this->dataViewMandatoryHeaderData) {
            echo '<div class="p-2 pb0">';
                echo $this->defaultCriteriaMandatory;
            echo '</div>';
        }
        ?>
        <div class="topdpbutton">
            <?php
            if (isset($GLOBALS['tabCriteriaArr']) && $GLOBALS['tabCriteriaArr']) {
            foreach ($GLOBALS['tabCriteriaArr'] as $tabCriteriaRow) {
                ?>
                <div class="btn-group">
                    <button class="btn btn-secondary btn-lg tab-lookupcriteria-<?php echo $this->metaDataId; ?>" type="button" data-type="" data-metas="<?php echo urlencode(json_encode($tabCriteriaRow)); ?>" aria-expanded="false">
                <?php echo $tabCriteriaRow['TAB_NAME']; ?>
                    </button>
                </div>
                <?php }}
                if ($this->getChildDataviewData) {
                foreach ($this->getChildDataviewData as $dvkey => $dv) {
                    $active = '';
                    if (($dv['permission'] == 0 || $dvkey == 0) && $dv['SHOW_POSITION'] == 'top') {
                        if ($dv['TRG_META_DATA_ID'] == $this->metaDataId) {
                            $active = ' active';
                        }
                    ?>
                    <div class="btn-group">
                        <a class="btn btn-secondary btn-lg<?php echo $active; ?>" style="background:none;" href="javascript:;" onclick="appMultiTabEcommerce<?php echo $this->metaDataId; ?>(this, '<?php echo $dv['TRG_META_DATA_ID']; ?>', '<?php echo $dv['META_DATA_NAME']; ?>')"><?php echo $dv['META_DATA_NAME']; ?></a>
                    </div>
            <?php }}}
            if ($this->getIsCountCardData) {
            $countCardHide = count($this->getIsCountCardData) === 1 ? ' hidden' : '';
            foreach ($this->getIsCountCardData as $k => $row) { ?>
                <div class="btn-group<?php echo $countCardHide ?>">
                <?php echo '<button class="btn btn-secondary btn-lg tab-lookupcriteria-' . $this->metaDataId . '" type="button" data-type="card" data-path="' . $row['FIELD_PATH'] . '" data-type="' . $row['META_TYPE_CODE'] . '" data-theme="' . $row['COUNTCARD_THEME'] . '" data-selection="' . $row['COUNTCARD_SELECTION'] . '">' . $this->lang->line($row['META_DATA_NAME']) . '</button>';
                ?>
                </div>
            <?php }} if ($this->layoutLink) { ?>
                <div class="btn-group">
                    <button class="btn btn-secondary btn-lg tab-lookupcriteria-<?php echo $this->metaDataId; ?>" data-layoutid="<?php echo $this->row['LAYOUT_META_DATA_ID']; ?>" type="button" aria-expanded="false">
                <?php echo $this->layoutLink['META_DATA_NAME']; ?>
                    </button>
                </div>                 
            <?php } ?>
        </div>
        <?php if ($this->layoutLink) { ?>
            <div class=" div-ecommercelayoutmeta-<?php echo $this->metaDataId; ?>"></div>
        <?php } ?>
        <?php if (issetParam($this->row['SHOW_POSITION']) === 'left') { ?>
            <div class="col-4" id="md-bp-left-<?php echo $this->metaDataId; ?>"></div>
        <?php } ?>
        <div class="<?php echo issetParam($this->row['SHOW_POSITION']) === 'left' ? 'col-8' : '' ?> div-objectdatagrid-<?php echo $this->metaDataId; ?> md-listcomment <?php echo $this->dataGridOptionData['VIEWTHEME'] . ' ' . $this->layoutTheme; ?> web-dataview p-2">
            <?php if (issetParam($this->layoutTheme) == 'ganttchart') {
                echo '<div id="objectdatagrid-'. $this->metaDataId .'" class="not-datagrid explorer-table-cell bgnone div-objectdatagrid-'. $this->metaDataId .'"></div>';
            } else {
                echo '<table id="objectdatagrid-'. $this->metaDataId .'"></table>';
            } ?>
            
        </div>
        <div class="div-dataGridLayout-<?php echo $this->metaDataId; ?>"></div>
        <div class="div-ganttLayout-<?php echo $this->metaDataId; ?>" style="height: 100%;width: 100%; display:none"></div>
        <div class="<?php echo $this->dataViewClass; ?>"><div id="md-map-canvas-<?php echo $this->metaDataId; ?>" style="display: none;"></div></div>            
        <div id="md-map-civil-<?php echo $this->metaDataId; ?>"></div>
    </div>
    <?php if ($rightShowSidebar) { ?>
        <div class="sidebar sidebar-light sidebar-right sidebar-expand-md ecommerce-right-sidebar">
            <div class="sidebar-content">
                <div>
                    <div class="title-right header-elements-inline">
                        <div class="list_name">
                            <span>
                                <i class="icon-task mr-1 small"></i>
                            </span>
                            <span class="mr-1">
                                <?php echo $euseBasket ? 'Сонгогдсон' : 'Үйлдэл'; ?>
                            </span>
                            <span class="basket_ecommerce_counter_<?php echo $this->metaDataId; ?>">
                                <?php echo ($this->selectedRowData) ? !isset($this->selectedRowData[0]) ? '(1)' : '(' . count($this->selectedRowData) . ')' : ''; ?>
                            </span>
                        </div>
                        <div class="header-elements">
                            <?php echo $euseBasket ? '<a type="button" class=" datagrid-choose-btn pull-right" onclick="removeAllBasket_'. $this->metaDataId .'(this)"><i class="fa fa-trash"></i></a>' : ' '; ?>
                            <div class="list-icons">
                                <a class="list-icons-item" data-toggle="collapse" href="#card-collapse-options" role="button" aria-expanded="false" aria-controls="card-collapse-options">
                                    <i class="icon-chevron-down"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="collapse show p-2" id="card-collapse-options">
                        <div>
                            <?php echo $commandBtn; ?>       
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<div id="objectDashboardView_<?php echo $this->metaDataId; ?>"></div>
<div id="objectReportTemplateView_<?php echo $this->metaDataId; ?>"></div>

<?php
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

<?php echo isset($this->ecommerce_js) ? $this->ecommerce_js : ''; ?>
<?php echo isset($this->ecommerce_css) ? $this->ecommerce_css : ''; ?>