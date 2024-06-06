<div data-refstructureid="<?php echo $this->refStructureId; ?>" data-sourceid="<?php echo $this->sourceId; ?>">
    
    <?php
    if ($this->isUserControl) {
    ?>
    <button type="button" class="btn green-meadow btn-circle btn-sm" onclick="mvBpRelationAddObject(this);" data-m="0" data-action-name="addIndicator">
        <i class="icon-plus3 font-size-12"></i> <?php echo $this->lang->line('add_btn'); ?>
    </button>
    <?php
    }
    ?>
    
    <div class="kpi-component-wrap mt-2">
        <?php
        foreach ($this->savedRows as $trgIndicatorId => $row) {
        ?>
        <div class="col reldetail mt-2" data-indicator-id="<?php echo $trgIndicatorId; ?>" style="background-color: #f1f8e9; border: 1px solid #e0e0e0;">
            <div class="d-flex align-items-center align-items-md-start flex-column flex-md-row pt-2">
                <h5 class="reltitle line-height-normal font-size-14 font-weight-bold cursor-pointer text-select-none" style="-ms-flex: 1;flex: 1;" onclick="kpiIndicatorRelationCollapse(this);">
                    <i class="far fa-angle-down"></i> <?php echo $row['indicatorName']; ?>
                </h5>
                <a href="javascript:;" onclick="chooseKpiIndicatorRowsFromBasket(this, '<?php echo $trgIndicatorId; ?>', 'multi', 'mvIndicatorRelationFillRows');" title="<?php echo $this->lang->line('add_btn'); ?>" data-action-name="addIndicatorValue">
                    <i class="far fa-plus font-size-20"></i>
                </a>
                
                <?php
                if ($this->isUserControl) {
                ?>
                <a href="javascript:;" onclick="mvBpRelationRemoveObject(this);" title="<?php echo $this->lang->line('delete_btn'); ?>" data-action-name="removeIndicator">
                    <i class="far fa-trash font-size-20 text-danger ml-2"></i>
                </a>
                <?php
                }
                ?>
                
            </div>
            <table class="table table-sm table-hover" style="border-top: 1px #ddd solid;">
                <tbody>
                    <?php 
                    foreach ($row['data'] as $dataRow) {
                    ?>
                    <tr data-indicatorid="<?php echo $trgIndicatorId; ?>" data-rowid="<?php echo $dataRow[$row['id']]; ?>">
                        <td style="height: 35px; max-width: 0;" class="text-left text-truncate">
                            <a href="javascript:;" onclick="bpCallKpiIndicatorForm(this, this, '<?php echo $trgIndicatorId; ?>', '<?php echo $dataRow[$row['id']]; ?>', 'view');" class="font-size-14" title="<?php echo $this->lang->line('view_btn'); ?>">
                                <i style="color:blue" class="far fa-file-search mr-1"></i>
                                <?php echo $dataRow[$row['name']]; ?>
                            </a>
                        </td>
                        <td style="width: 60px" class="text-right">
                            <a href="javascript:;" onclick="mvBpRelationRemoveRow(this);" class="font-size-14" title="<?php echo $this->lang->line('delete_btn'); ?>" data-action-name="removeIndicatorValue">
                                <i class="far fa-trash text-danger"></i>
                            </a>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php
        }
        ?>
    </div>
</div>

<script type="text/javascript">
var $mvRelationElem = $('div[data-refstructureid="<?php echo $this->refStructureId; ?>"]').closest('.tab-pane');    
if (typeof isKpiIndicatorScript === 'undefined') {
    $.cachedScript('<?php echo autoVersion('middleware/assets/js/addon/indicator.js'); ?>');
}  
if (typeof isMvBpRelationScript === 'undefined') {
    $.cachedScript('<?php echo autoVersion('middleware/assets/js/addon/metaverseBpRelation.js'); ?>').done(function() {
        mvBpRelationActionControl($mvRelationElem);
    });
} else {
    mvBpRelationActionControl($mvRelationElem);
}
</script>