<?php
if (!$this->isDialog) {
?>
<div class="pivotgrid-table" id="<?php echo $this->uniqId; ?>" data-run-mode="<?php echo $this->runMode; ?>">
    <div class="pivotgrid-table-row">
        <div class="pivotgrid-table-left-cell">
            <?php
            if ($this->runMode != 'show') {
                echo Form::select(
                    array(
                        'class' => 'form-control form-control-sm select2 mb10 pv-report-dropdown', 
                        'data' => $this->dmReportModels, 
                        'op_value' => 'REPORT_MODEL_ID',
                        'op_text' => 'REPORT_MODEL_NAME'
                    )
                );
            }
            ?>
            <div class="pv-field-options"><?php echo $this->fieldOptions; ?></div>
        </div>
        <div class="pivotgrid-table-collapse-cell d-none"></div>
        <div class="pivotgrid-table-right-cell pv-grid"><?php echo $this->grid; ?></div>
    </div>
</div>
<?php
} elseif ($this->isDialog) {
    if ($this->runMode == 'edit') {
?>
<div id="<?php echo $this->uniqId; ?>" data-run-mode="<?php echo $this->runMode; ?>">
    <form class="form-horizontal xs-form" role="form" method="post">
        <div class="form-body mb20">
            <div class="row">
                <div class="col-md-5 col-sm-5">
                    <div class="form-group row fom-row">
                        <?php echo Form::label(array('text' => 'Data Source', 'for' => 'dataSourceId', 'class' => 'col-form-label col-md-4 custom-label')); ?>
                        <div class="col-md-8">
                            <?php
                            echo Form::select(
                                array(
                                    'id' => 'dataSourceId', 
                                    'class' => 'form-control form-control-sm select2', 
                                    'data' => $this->dataViewList, 
                                    'op_value' => 'META_DATA_ID',
                                    'op_text' => 'META_DATA_NAME', 
                                    'value' => $this->dataViewId
                                )
                            );
                            ?>
                        </div>
                    </div>
                </div>  
                <div class="col-md-6 col-sm-6">
                    <div class="form-group row fom-row">
                        <?php echo Form::label(array('text' => 'Загварын нэр', 'for' => 'reportModelName', 'class' => 'col-form-label col-md-4 custom-label', 'required' => 'required')); ?>
                        <div class="col-md-8">
                            <?php
                            echo Form::text(
                                array(
                                    'required' => 'required',
                                    'id' => 'reportModelName', 
                                    'name' => 'reportModelName', 
                                    'class' => 'form-control form-control-sm', 
                                    'value' => $this->row['REPORT_MODEL_NAME']
                                )
                            );
                            ?>
                        </div>
                    </div>
                </div> 
            </div>
        </div>   
        <?php echo Form::hidden(array('name' => 'categoryId', 'value' => $this->categoryId)); ?>
        <?php echo Form::hidden(array('name' => 'reportModelId', 'value' => $this->reportModelId)); ?>
    </form> 
    <div class="pivotgrid-table">
        <div class="pivotgrid-table-row">
            <div class="pivotgrid-table-left-cell">
                <div class="pv-field-options"><?php echo $this->fieldOptions; ?></div>
            </div>
            <div class="pivotgrid-table-collapse-cell"></div>
            <div class="pivotgrid-table-right-cell pv-grid" data-dm-report-id="<?php echo $this->reportModelId; ?>"><?php echo $this->grid; ?></div>
        </div>
    </div>
</div>
<?php
    } else {
?>
<div id="<?php echo $this->uniqId; ?>" data-run-mode="<?php echo $this->runMode; ?>">
    <form class="form-horizontal xs-form" role="form" method="post">
        <div class="form-body mb20">
            <div class="row">
                <div class="col-md-5 col-sm-5">
                    <div class="form-group row fom-row">
                        <?php echo Form::label(array('text' => 'Data Source', 'for' => 'dataSourceId', 'required' => 'required', 'class' => 'col-form-label col-md-4 custom-label')); ?>
                        <div class="col-md-8">
                            <?php
                            echo Form::select(
                                array(
                                    'required' => 'required',
                                    'name' => 'dataSourceId', 
                                    'id' => 'dataSourceId', 
                                    'class' => 'form-control form-control-sm select2 pv-report-dataview', 
                                    'data' => $this->dataViewList, 
                                    'op_value' => 'META_DATA_ID',
                                    'op_text' => 'META_DATA_NAME'
                                )
                            );
                            ?>
                        </div>
                    </div>
                </div>  
                <div class="col-md-6 col-sm-6">
                    <div class="form-group row fom-row">
                        <?php echo Form::label(array('text' => 'Загварын нэр', 'for' => 'reportModelName', 'class' => 'col-form-label col-md-4 custom-label', 'required' => 'required')); ?>
                        <div class="col-md-8">
                            <?php
                            echo Form::text(
                                array(
                                    'required' => 'required',
                                    'id' => 'reportModelName', 
                                    'name' => 'reportModelName', 
                                    'class' => 'form-control form-control-sm', 
                                )
                            );
                            ?>
                        </div>
                    </div>
                </div> 
            </div>
        </div>   
        <?php echo Form::hidden(array('name' => 'categoryId', 'value' => $this->categoryId)); ?>
    </form> 
    <div class="pivotgrid-table">
        <div class="pivotgrid-table-row">
            <div class="pivotgrid-table-left-cell">
                <div class="pv-field-options"><?php echo $this->fieldOptions; ?></div>
            </div>
            <div class="pivotgrid-table-collapse-cell"></div>
            <div class="pivotgrid-table-right-cell pv-grid"><?php echo $this->grid; ?></div>
        </div>
    </div>
</div>
<?php
    }
}
?>

<script type="text/javascript">
var pv_selector_<?php echo $this->uniqId; ?> = $('div#<?php echo $this->uniqId; ?>');

$(function(){
    
    if (pv_selector_<?php echo $this->uniqId; ?>.attr('data-run-mode') == 'show') {
        $('div#<?php echo $this->uniqId; ?>').find('.pv-grid').attr('data-dm-report-id', '<?php echo $this->reportModelId; ?>');
        $('div#<?php echo $this->uniqId; ?>').find('.pivotgrid-table-collapse-cell').removeClass('d-none');
    }
    
    pv_selector_<?php echo $this->uniqId; ?>.on('change', 'select.pv-report-dropdown', function(){
        var dmReportId = $(this).val();
        
        if (dmReportId != '') {
            $.ajax({
                type: 'post',
                url: 'mdpivot/renderFieldOptions/'+dmReportId,
                data: {runMode: '<?php echo $this->runMode; ?>'}, 
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({
                        boxed: true, 
                        message: 'Loading...'
                    });
                },
                success: function (data) {
                    
                    $('div#<?php echo $this->uniqId; ?>').find('.pv-grid').attr('data-dm-report-id', dmReportId);
                    $('div#<?php echo $this->uniqId; ?>').find('.pv-field-options').html(data.fieldOptions);
                    $('div#<?php echo $this->uniqId; ?>').find('.pivotgrid-table-collapse-cell').removeClass('d-none');
                    $('div#<?php echo $this->uniqId; ?>').find('.pv-grid').html(data.grid);
                    
                },
                error: function () {
                    alert('Error');
                }
            }).done(function(){
                Core.initDVAjax($('div#<?php echo $this->uniqId; ?>').find('.pv-grid'));
                Core.unblockUI();
            });
        }
    });
    
    pv_selector_<?php echo $this->uniqId; ?>.on('change', 'select.pv-report-dataview', function(){
        var dataViewId = $(this).val();
        
        if (dataViewId != '') {
            $.ajax({
                type: 'post',
                url: 'mdpivot/renderFieldOptions/'+dataViewId,
                data: {isDataView: true, runMode: '<?php echo $this->runMode; ?>'}, 
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({
                        boxed: true, 
                        message: 'Loading...'
                    });
                },
                success: function (data) {
                    
                    $('div#<?php echo $this->uniqId; ?>').find('.pv-grid').attr('data-dm-report-id', dataViewId);
                    $('div#<?php echo $this->uniqId; ?>').find('.pv-field-options').html(data.fieldOptions);
                    $('div#<?php echo $this->uniqId; ?>').find('.pivotgrid-table-collapse-cell').removeClass('d-none');
                    $('div#<?php echo $this->uniqId; ?>').find('.pv-grid').html(data.grid);
                    
                },
                error: function () {
                    alert('Error');
                }
            }).done(function(){
                Core.initDVAjax($('div#<?php echo $this->uniqId; ?>').find('.pv-grid'));
                Core.unblockUI();
            });
        }
    });
    
    pv_selector_<?php echo $this->uniqId; ?>.on('click', '.pivotgrid-table-collapse-cell', function(){
        var _this = $(this); 
        var _thisPvGridContainer = $('div#<?php echo $this->uniqId; ?>').find('.pv-grid');
        var _thisPvGrid = _thisPvGridContainer.find('table.pv-main-element');
        
        if (_this.hasClass('pv-panel-closed')) {
            $('div#<?php echo $this->uniqId; ?>').find('.pivotgrid-table-left-cell').removeClass('d-none');
            _this.removeClass('pv-panel-closed');
            var _rightWidth = _thisPvGridContainer.attr('data-width');
            _thisPvGridContainer.find('.datagrid-wrap:first').css('width', _rightWidth);
            _thisPvGridContainer.find('.datagrid-view:first').css('width', _rightWidth - 2);
            var _pvGridLeftPartWidth = _thisPvGridContainer.find('.datagrid-view1:first').css('width').replace('px', '');
            var _calWidth = _rightWidth - 2 - _pvGridLeftPartWidth;
            _thisPvGridContainer.find('.datagrid-view2:first').css('width', _calWidth);
            _thisPvGridContainer.find('.datagrid-view2 .datagrid-header:first').css('width', _calWidth);
            _thisPvGridContainer.find('.datagrid-view2 .datagrid-body:first').css('width', _calWidth);
            _thisPvGridContainer.find('.datagrid-view2 .datagrid-footer:first').css('width', _calWidth);
        } else {
            _thisPvGridContainer.attr('data-width', _thisPvGridContainer.find('.pivotgrid-table-right-cell-inside').innerWidth());
            $('div#<?php echo $this->uniqId; ?>').find('.pivotgrid-table-left-cell').addClass('d-none');
            _this.addClass('pv-panel-closed');
            _thisPvGrid.treegrid('resize');
        }
    });
    
});    
</script>