    <div class="page-content ecommerce_<?php echo $this->metaDataId ?> <?php echo ($this->row['LAYOUT_TYPE'] === 'ecommerce' ? ' dvecommerce' : '') . (issetParam($this->appendClass) ? ' ' . $this->appendClass : '') ?> dialog-ecommerce-basket m-0 row" id="objectDataView_<?php echo $this->metaDataId; ?>">
        <?php $detectFilter = strpos($this->defaultCriteria, 'param[') === false && empty($this->getChildDataviewData) ? ' hidden' : '';
        ?>
        <div class="sidebar-sticky <?php echo 'col-md-3 p-0 pl-1 border-none' ?> sidebar-light sidebar-secondary sidebar-component sidebar-expand-md<?php echo ($this->dataViewCriteriaType == 'hidden' ? ' hidden' : '') . $detectFilter; ?> ecommerce-left-sidebar">
            <div class="sidebar-content">
                <?php if ($this->getChildDataviewData) {
                    $topCheck = false;
                    foreach ($this->getChildDataviewData as $dvkey => $dv) {
                        if (($dv['permission'] == 0 || $dvkey == 0) && empty($dv['SHOW_POSITION'])) {
                            $topCheck = true;
                        }
                    } 

                    if ($topCheck) { ?>
                    <div>
                        <ul class="nav navbar-nav sub-dv-list-<?php echo $this->metaDataId; ?> bp-icon-selection p-2" data-choose-type="single">
                            <div class="dvecommercetitle">
                                <h3><?php echo isset($this->row['LIST_MENU_NAME']) ? $this->lang->line($this->row['LIST_MENU_NAME']) : ''; ?></h3>
                            </div>
                            <?php 
                            foreach ($this->getChildDataviewData as $dvkey => $dv) {
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
                            <?php }
                            } ?>
                        </ul>
                    </div>
                <?php }
                } ?>
                <div class="card-body p-0">
                    <ul class="nav nav-tabs nav-tabs-bottom nav-justified mb-0">
                        <li class="nav-item">
                            <a href="#basket-tab-popup1-<?php echo $this->metaDataId; ?>" class="nav-link v2" data-toggle="tab">
                                <span><i class="icon-search4 mr-1"></i></span>
                                <span><?php echo Lang::line('filter'); ?></span>
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
                    <div class="ecommerce-criteria-wrap-<?php echo $this->metaDataId; ?> tab-content basket-height-scroll">
                        <div class="p-2 tab-pane fade <?php echo ($this->isTree) ? '' : 'show active' ?>" id="basket-tab-popup1-<?php echo $this->metaDataId; ?>">
                            <?php  echo $this->defaultCriteria; ?>
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

        $commandBtn = '';
        $commandBtn .= $this->dataViewProcessCommand['commandBtn'];
        $addonBtn = $wfmBtn = '';

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
        ?>
            <div class="<?php echo (($this->dataViewCriteriaType == 'hidden' || $detectFilter == ' hidden') ? 'col-md-9 p-0 border-right-1 border-gray bg-white basket-list' : 'col-md-6 p-0 border-left-1 border-right-1 border-gray bg-white basket-list'); ?>">
                <div class="ecommerce-breadcumb header-elements-md-inline pull-left w-100">
                    <a type="button" class="datagrid-choose-btn pull-right" onclick="selectAllBasket_<?php echo $this->metaDataId; ?>(this)"><i class="fa fa-check"></i> Бүгдийг сонгох</a>
                    <div class="">
                        <div class="ecommerce-buttons header-elements">
                            <div class="ecomcardview">
                                <?php if (isset($GLOBALS['ecommerceAdvancedCriteria']) && $GLOBALS['ecommerceAdvancedCriteria']) { ?>
                                    <button type="button" class="btn btn-icon mr-0" onclick="dvecommerceAdvancedCriteria<?php echo $this->metaDataId; ?>(this)">
                                        <i class="icon-search4"></i>
                                    </button>
                                <?php 
                                }
                                ?>
                            </div>
                            <?php
                                echo Html::anchor(
                                    'javascript:;', '<i class="far fa-shopping-cart"></i> <span class="save-database-'. $this->metaDataId .'">0</span>', array(
                                        'class' => 'btn btn-secondary btn-sm btn-circle default',
                                        'onclick' => 'dataViewUseBasketView_' . $this->metaDataId . '(this);',
                                        'title' => $this->lang->line('META_00113'),
                                    ), isset($this->useBasketBtn) ? $this->useBasketBtn : false
                                );  ?>
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

                <div class="div-objectdatagrid-<?php echo $this->metaDataId; ?> md-listcomment <?php echo $this->dataGridOptionData['VIEWTHEME'] . ' ' . $this->layoutTheme; ?> web-dataview p-2  w-100 pull-left" style="  ">
                    <table id="objectdatagrid-<?php echo $this->metaDataId; ?>"></table>
                </div>
            </div>
            <div class="col-md-3 p-0 pr-1 border-none sidebar-light sidebar-expand-md ecommerce-right-sidebar  pull-left w-100">
                <div class="sidebar-content">
                    <div class="border-radius-0">
                        <div class="title-right header-elements-inline">
                            <div class="list_name">
                                <span>
                                    <i class="icon-task mr-1 small"></i>
                                </span>
                                <span class="mr-1">
                                    <?php echo Lang::line('has_chosen'); ?>
                                </span>
                                <span class="basket_ecommerce_counter_<?php echo $this->metaDataId; ?>">
                                    <?php echo ($this->selectedRowData) ? !isset($this->selectedRowData[0]) ? '(1)' : '(' . count($this->selectedRowData) . ')' : ''; ?>
                                </span>
                            </div>
                            <div class="header-elements">
                                <a type="button" class=" datagrid-choose-btn pull-right" onclick="removeAllBasket_<?php echo $this->metaDataId ?>(this)"><i class="fa fa-trash"></i></a>
                            </div>
                        </div>
                        <div class="collapse show p-2 height-scroll right-add-list" id="card-collapse-options">
                            <div class="basket-add">
                                <div class="mb0">
                                    <ul class="media-list" id="basket_ecommerce_<?php echo $this->metaDataId; ?>">
                                    <?php
                                    if (issetParamArray($this->selectedRowData)) {
                                        $typeRow = $this->row['dataViewLayoutTypes']['ecommerce'];
                                        $basketPhoto = '';
                                        $selectedRows = $this->selectedRowData;

                                        if (issetParam($typeRow['fields']['basketname']) !== '' && !issetParamArray($selectedRows[0])) { 
                                            $selectedRows = array();
                                            array_push($selectedRows, $this->selectedRowData);
                                        }
                                        if (issetParam($typeRow['fields']['basketname']) !== '' && issetParamArray($selectedRows[0])) {
                                            foreach ($selectedRows as $index =>  $selRow) {

                                                if (isset($typeRow['fields']['basketphoto'])) {
                                                    $basketImage = (strpos($selRow[Str::lower($typeRow['fields']['basketphoto'])], '<img') === false) ? '<img src="' . $selRow[Str::lower($typeRow['fields']['basketphoto'])] . '" width="25" height="25" class="rounded-circle" alt="" onerror="onUserImgError(this);">' : $selRow[Str::lower($typeRow['fields']['basketphoto'])];
                                                    $basketPhoto = '<a href="javascript:;" class="mr-2 position-relative">'. $basketImage .'</a>';
                                                }

                                                ?>
                                                <li data-index="<?php echo $index; ?>" class="datagrid-row media p-1 border-bottom-1 border-gray"style="height: 43px;"><?php echo $basketPhoto; ?>
                                                    <div class="media-body <?php echo issetParam($typeRow['fields']['basketcode']) == '' ? 'one-row' : '' ?>">
                                                        <div class="line-height-normal d-flex align-items-center">
                                                            <span><?php echo $selRow[Str::lower($typeRow['fields']['basketname'])]; ?></span>
                                                        </div>
                                                        <?php if (issetParam($typeRow['fields']['basketcode'])) { ?>
                                                            <span class="memberposition" style="font-size: 10px;color: #999;text-transform: uppercase;"><?php echo issetParam($selRow[Str::lower($typeRow['fields']['basketcode'])]); ?></span>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="ml10 mr10 align-self-center">
                                                        <a href="javascript:;" class="position-relative" onclick="removeCommerceBasket<?php echo $this->metaDataId; ?>(this)"><i class="fa fa-close basket-choose-icon"></i></a>
                                                    </div>
                                                </li>
                                            <?php 
                                            }
                                        }
                                    } ?>
                                    </ul>
                                </div>
                                <?php echo $commandBtn; ?>       
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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