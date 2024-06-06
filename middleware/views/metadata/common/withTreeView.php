<div class="row selectableGrid-<?php echo $this->metaDataId; ?> selectable-dataview-grid" data-view-id="<?php echo $this->metaDataId; ?>">
    <div class="col-md-4 left-content-selectableGrid">
        <div class="tabbable-line">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#commonSelectableTabTreeFilter_<?php echo $this->metaDataId; ?>" class="nav-link active" data-toggle="tab"><?php echo $this->lang->line('META_00193'); ?></a>
                </li>
                <li class="nav-item">
                    <a href="#commonSelectableTabFilter_<?php echo $this->metaDataId; ?>" data-toggle="tab" class="nav-link"><?php echo $this->lang->line('search'); ?></a>
                </li>
            </ul>
            <div class="tab-content pt5 pb0">
                <div class="tab-pane in active" id="commonSelectableTabTreeFilter_<?php echo $this->metaDataId; ?>">
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
                                'onchange' => 'lookupDrawTree_' . $this->metaDataId . '();',
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
                                'onchange' => 'lookupDrawTree_' . $this->metaDataId . '();',
                                'text' => 'notext'
                            )
                        );
                    }
                    ?>
                    <div id="treeContainer">
                        <div id="dataViewStructureTreeView_<?php echo $this->metaDataId; ?>" class="tree-demo" style="max-height: 380px; overflow: auto; overflow-x: hidden;"></div>
                    </div>
                </div>
                <div class="tab-pane in selectableGrid-tab-<?php echo $this->metaDataId; ?>" id="commonSelectableTabFilter_<?php echo $this->metaDataId; ?>">
                    <form id="commonSelectableSearchForm_<?php echo $this->metaDataId ?>" method="post">
                        <div class="form-body xs-form">
                            <?php 
                            echo $this->dataGridSearchForm; 
                            echo Form::hidden(array('name' => 'folderId', 'id' => 'folderId')); 
                            ?>    
                        </div>
                        <div class="form-actions float-right">
                            <?php echo Form::button(array('class' => 'btn blue btn-sm mr-1', 'onclick' => 'commonSelectableDataGridSearch_'. $this->metaDataId . '()', 'value' => '<i class="fa fa-search"></i> ' . $this->lang->line('search_btn'))); ?>
                            <?php echo Form::button(array('class' => 'btn grey-cascade btn-sm', 'onclick' => 'commonSelectableDataGridReset_'. $this->metaDataId . '();', 'value' => $this->lang->line('clear_btn'))); ?>
                        </div>
                    </form>    
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8 right-content-selectableGrid">
        <div class="tabbable-line">
            <ul class="nav nav-tabs">
                <li class="nav-item commonSelectableTabOrder-<?php echo $this->metaDataId; ?>">
                    <a href="#commonSelectableTabOrder_<?php echo $this->metaDataId; ?>" class="nav-link active" data-toggle="tab"><?php echo $this->lang->line('META_00062'); ?></a>
                </li>
                <li class="nav-item">
                    <a href="#commonSelectableTabBasket_<?php echo $this->metaDataId; ?>" class="nav-link" data-toggle="tab"><?php echo $this->lang->line('basket'); ?> ( <span id="commonSelectedCount_<?php echo $this->metaDataId; ?>" class="dv-basket-count">0</span> )</a>
                </li>
            </ul>
            <div class="tab-content pt5 pb0 tab-content-selectableGrid-<?php echo $this->metaDataId; ?>">
                <div class="tab-pane active in disactive" id="commonSelectableTabOrder_<?php echo $this->metaDataId; ?>">
                    
                    <?php echo $this->defaultCriteriaMandatory; ?>
                    
                    <div class="row selectableGrid-row-<?php echo $this->metaDataId; ?>">
                        <?php
                        if (isset($this->processButtons) && $this->processButtons['commandBtn'] != '') {
                        ?>
                        <input type="hidden" id="dataview-criteria-params-<?php echo $this->metaDataId; ?>" value="<?php echo $this->requestParams; ?>">
                        <div class="col-md-12 selectableGrid-datatable-process-btn-<?php echo $this->metaDataId; ?> mt5">
                            <div class="table-toolbar">
                                <div class="row">
                                    <div class="col-md-12 dv-process-buttons">
                                        <?php echo $this->processButtons['commandBtn']; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                        <div class="col-md-12 jeasyuiTheme3 selectableGrid-datatable-<?php echo $this->metaDataId; ?>">
                            <table id="objectdatagrid_<?php echo $this->metaDataId; ?>" style="height: <?php echo (isset($this->basketGridHeight) && $this->basketGridHeight && $this->basketGridHeight != 'auto') ? $this->basketGridHeight : '380'; ?>px"></table>
                        </div>    
                    </div>
                </div>
                <div class="tab-pane in disactive" id="commonSelectableTabBasket_<?php echo $this->metaDataId; ?>">
                    <div class="table-toolbar d-none"></div>
                    <div class="disactive row selectableGrid-row-<?php echo $this->metaDataId; ?> selectableGrid-nogrid-row-<?php echo $this->metaDataId; ?>">
                        <div class="col-md-12 jeasyuiTheme3 selectableGrid-datatable-<?php echo $this->metaDataId; ?>">
                            <table id="commonSelectableBasketDataGrid_<?php echo $this->metaDataId ?>"></table>
                        </div>
                    </div>
                    <div class="disactive row selectableGrid-row-<?php echo $this->metaDataId; ?>">
                        <div class="col-md-12 grid-column-to-label selectableGrid-datatable-<?php echo $this->metaDataId; ?>"></div>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</div>