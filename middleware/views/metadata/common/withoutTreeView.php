<div class="selectableGrid-<?php echo $this->metaDataId; ?> selectable-dataview-grid" data-view-id="<?php echo $this->metaDataId; ?>">
    <div class="row<?php echo $this->isComboGrid ? ' d-none' : ''; ?>">
        <div class="col lefttab-content-selectableGrid" style="-ms-flex: 0 0 120px;flex: 0 0 120px;max-width: 120px;">
            <div class="tabbable-line">
                <ul class="nav nav-tabs" style="border-bottom: 1px #eee solid;">
                    <li class="nav-item<?php echo $this->isGridShow == false ? ' disabled' : ''; ?>">
                        <a href="#commonSelectableTabFilter_<?php echo $this->metaDataId; ?>" data-toggle="tab" class="nav-link active" onclick="hiddenLeftContentSelectableGrid_<?php echo $this->metaDataId ?>(this);" data-status="closed"><?php echo $this->lang->line('META_00193'); ?> <i class="far fa-angle-left selectableGrid-li-<?php echo $this->metaDataId; ?>"></i></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col">
            <div class="tabbable-line">
                <ul class="nav nav-tabs" style="border-bottom: 1px #eee solid;">
                    <li class="nav-item commonSelectableTabOrder-<?php echo $this->metaDataId.($this->isGridShow == false ? ' disabled' : ''); ?>">
                        <a href="#commonSelectableTabOrder_<?php echo $this->metaDataId; ?>" data-toggle="tab" class="nav-link active"><?php echo $this->lang->line('META_00062'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a href="#commonSelectableTabBasket_<?php echo $this->metaDataId; ?>" data-toggle="tab" class="nav-link"><?php echo $this->lang->line('basket'); ?> ( <span id="commonSelectedCount_<?php echo $this->metaDataId; ?>" class="dv-basket-count">0</span> )</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col d-none left-content-selectableGrid" style="-ms-flex: 0 0 120px;flex: 0 0 120px;max-width: 120px;">
            <div class="tab-content pt5 pb0">
                <div class="tab-pane in active selectableGrid-tab-<?php echo $this->metaDataId; ?>" id="commonSelectableTabFilter_<?php echo $this->metaDataId; ?>">
                    <form id="commonSelectableSearchForm_<?php echo $this->metaDataId ?>" method="post">
                        <div class="form-body xs-form">
                            <?php 
                            echo $this->dataGridSearchForm; 
                            echo Form::hidden(array('name' => 'folderId', 'id' => 'folderId')); 
                            ?>    
                        </div>    
                        <div class="form-actions float-right">
                            <?php 
                            echo Form::button(array('class' => 'btn blue btn-sm mr-1', 'onclick' => 'commonSelectableDataGridSearch_'. $this->metaDataId . '()', 'value' => '<i class="fa fa-search"></i> ' . $this->lang->line('search_btn'))); 
                            echo Form::button(array('class' => 'btn grey-cascade btn-sm', 'onclick' => 'commonSelectableDataGridReset_'. $this->metaDataId . '();', 'value' => $this->lang->line('clear_btn'))); 
                            ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="tab-content <?php echo $this->isComboGrid ? '' : 'pt5'; ?> pb0">
                <div class="tab-pane in active" id="commonSelectableTabOrder_<?php echo $this->metaDataId; ?>">
                    
                    <?php echo $this->defaultCriteriaMandatory; ?>
                    
                    <div class="row selectableGrid-row-<?php echo $this->metaDataId; ?>">
                        <?php
                        if (isset($this->processButtons) && $this->processButtons['commandBtn'] != '' && $this->isComboGrid !== '1') {
                        ?>
                        <input type="hidden" id="dataview-criteria-params-<?php echo $this->metaDataId; ?>" value="<?php echo $this->requestParams; ?>">
                        <div class="col-md-12 selectableGrid-datatable-process-btn-<?php echo $this->metaDataId; ?> <?php echo $this->isComboGrid ? '' : 'mt5'; ?>">
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
                        <input type="hidden" class="bp-combogrid-jsonrow-<?php echo $this->metaDataId; ?>" value=""/>
                        <div class="col-md-12 <?php echo $this->isComboGrid ? 'jeasyuiTheme10 jeasyuiTheme10-'.$this->isGridType : 'jeasyuiTheme3'; ?> selectableGrid-datatable-<?php echo $this->metaDataId; ?>">
                            <table id="objectdatagrid_<?php echo $this->metaDataId; ?>" style="height: <?php echo (isset($this->basketGridHeight) && $this->basketGridHeight && $this->basketGridHeight != 'auto') ? $this->basketGridHeight : '380'; ?>px"></table>
                        </div>    
                        <?php
                        if (isset($this->processButtons) && $this->processButtons['commandBtn'] != '' && $this->isComboGrid) {
                        ?>
                        <input type="hidden" id="dataview-criteria-params-<?php echo $this->metaDataId; ?>" value="<?php echo $this->requestParams; ?>">
                        <div class="col-md-12 dv-process-buttons-wrapper selectableGrid-datatable-process-btn-<?php echo $this->metaDataId; ?> <?php echo $this->isComboGrid ? '' : 'mt5'; ?>">
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
                    </div>
                </div>
                <div class="tab-pane in" id="commonSelectableTabBasket_<?php echo $this->metaDataId; ?>">
                    <div class="table-toolbar d-none"></div>
                    <div class="disactive row selectableGrid-row-<?php echo $this->metaDataId; ?> selectableGrid-nogrid-row-<?php echo $this->metaDataId; ?>">
                        <div class="col-md-12 <?php echo $this->isComboGrid ? 'jeasyuiTheme10' : 'jeasyuiTheme3'; ?> selectableGrid-datatable-<?php echo $this->metaDataId; ?>">
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