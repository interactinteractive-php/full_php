<div class="pivotgrid-table-right-cell-inside" id="pivotgrid-main-<?php echo $this->uniqId; ?>">
    <div class="tabbable-line">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a href="#tab_pivotgird<?php echo $this->uniqId ?>" id="pivotgird<?php echo $this->uniqId ?>" class="nav-link active" data-toggle="tab">Грид </a>
            </li>
            <li class="nav-item">
                <a href="#tab_pivotdashboard<?php echo $this->uniqId ?>" id="pivotdashboard<?php echo $this->uniqId ?>" data-toggle="tab" class="nav-link">Дашбоард </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_pivotgird<?php echo $this->uniqId ?>">
                <?php
                if ($this->columnFieldsGrid && $this->rowFieldsGrid && $this->valueFieldsGrid) {
                    if ($this->filterFields) {
                ?>
                <div class="xs-form" id="dm-pivot-search-<?php echo $this->uniqId; ?>">
                    <fieldset class="collapsible mb5">
                        <legend>Шүүлт</legend>
                        <form class="form-horizontal" method="post">
                            <div class="row">    
                                <?php
                                foreach ($this->filterFields as $param) {
                                ?>
                                <div class="col-md-4">
                                    <div class="form-group row fom-row">
                                        <?php 
                                        $labelArr = array(
                                            'text' => $this->lang->line($param['META_DATA_NAME']),
                                            'for' => 'param['.$param['META_DATA_CODE'].']',
                                            'class' => 'col-form-label col-md-4'
                                        );
                                        if ($param['IS_REQUIRED'] == '1') {
                                            $labelArr['required'] = 'required'; 
                                        }
                                        if (!empty($param['LOOKUP_META_DATA_ID']) && $param['LOOKUP_TYPE'] == 'combo') {
                                            $param['CHOOSE_TYPE'] = 'multi';
                                        }
                                        echo Form::label($labelArr); 
                                        ?>
                                        <div class="col-md-8">
                                            <?php
                                            echo Mdcommon::criteriaCondidion(
                                                $param,     
                                                Mdwebservice::renderParamControl($this->dataViewId, $param, 'param['.$param['META_DATA_CODE'].']', $this->dataViewId, $param['META_DATA_CODE'], null)
                                            );
                                            ?>  
                                        </div>
                                    </div>    
                                </div>    
                                <?php
                                }
                                ?>
                                <div class="clearfix w-100"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <?php 
                                    echo Form::button(
                                        array(
                                            'class' => 'btn btn-sm btn-circle blue-madison dm-pivot-filter-btn', 
                                            'value' => '<i class="fa fa-search"></i> Шүүх'
                                        )
                                    ); 
                                    ?>
                                </div>    
                            </div>  
                        </form>        
                    </fieldset>
                </div>
                <div class="clearfix w-100"></div>
                <?php
                }
                ?>
                <div class="row mb5">
                    <div class="col-md-8">
                        <?php echo $this->filterButtons; ?>   
                    </div>
                    <div class="col-md-4 text-right">
                        <div class="btn-group">
                            <?php
                            echo Form::button(
                                array(
                                    'class' => 'btn btn-sm btn-secondary pv-excel', 
                                    'value' => '<i class="fa fa-file-excel-o"></i> Эксель гаргах'
                                )
                            ); 
                            echo Form::button(
                                array(
                                    'class' => 'btn btn-sm btn-secondary', 
                                    'value' => '<i class="fa fa-file-word-o"></i> Word гаргах'
                                ), 
                                false    
                            );
                            ?>
                        </div>
                    </div>
                </div>
                <div class="jeasyuiPivotTheme2">
                    <table id="pv-table-<?php echo $this->uniqId; ?>" class="pv-main-element"></table>
                </div>
                <?php
                } else {
                    echo html_tag('div', array('class' => 'alert alert-warning'), 'Та тохиргооны талбаруудыг бүрэн сонгоно уу.');
                }
                ?>
            </div>
            <div class="tab-pane" id="tab_pivotdashboard<?php echo $this->uniqId ?>">
                <div class="row">
                    <div class="col-md-12">
                        <div id="pv-dashboard-render-<?php echo $this->uniqId ?>" style="height: 350px; margin-bottom: 10px; display: none;">
                        
                        </div>
                    </div>
                </div>
                <div class="pv-dashboard-message-<?php echo $this->uniqId ?>">
                    <?php echo html_tag('div', array('class' => 'alert alert-warning'), 'Та тохиргооны талбаруудыг бүрэн сонгоно уу.'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function() {
    
    $('.stop-propagation').on('click', function(e){
        e.stopPropagation();
    });
    
    $('.pv-filter-button').on('click', function(){
        var _this = $(this);
    });
    
    <?php
    if (isset($this->fieldChooserMode) && $this->fieldChooserMode == 1) {
        echo 'loadPivotGrid_'.$this->uniqId.'();';
    }
    ?>

    if ($('#pivotgrid-main-<?php echo $this->uniqId; ?>').closest("div[id*='dialog-pivot']").length > 0) {
        $('#pivotgrid-main-<?php echo $this->uniqId; ?>').closest("div[id*='dialog-pivot']").bind("dialogextendmaximize", function(){
            var dialogHeight = $('#pivotgrid-main-<?php echo $this->uniqId; ?>').closest("div[id*='dialog-pivot']").innerHeight();
            $('#pv-table-<?php echo $this->uniqId; ?>').attr('height', (dialogHeight - $('#pv-table-<?php echo $this->uniqId; ?>').offset().top + 20));
            loadPivotGrid_<?php echo $this->uniqId; ?>();
        });
    } else {
        $('#pv-table-<?php echo $this->uniqId; ?>').attr('height', ($(window).height() - $('#pv-table-<?php echo $this->uniqId; ?>').offset().top - 20));
        loadPivotGrid_<?php echo $this->uniqId; ?>();
    }
    
    $('#dm-pivot-search-<?php echo $this->uniqId; ?>').on('click', 'button.dm-pivot-filter-btn', function(){
        loadPivotGrid_<?php echo $this->uniqId; ?>();
    });
    
    $(window).bind('resize', function() {
        if ($("body").find("#pv-table-<?php echo $this->uniqId; ?>").length > 0 && $("body").find("#pivotgrid-main-<?php echo $this->uniqId; ?> .jeasyuiPivotTheme").is(':visible')) {
            var toolbarWidth = $("body").find("#pivotgrid-main-<?php echo $this->uniqId; ?>").width();
            var dataGridWidth = $("body").find("#pivotgrid-main-<?php echo $this->uniqId; ?>").find('div.datagrid-wrap:first').width();
            if (toolbarWidth !== dataGridWidth) {
                $("#pv-table-<?php echo $this->uniqId; ?>").treegrid('resize');
            }
        }
    });
    
    $('.pv-excel').on('click', function() {
        
        Core.blockUI({
            message: 'Exporting...', 
            boxed: true
        });
        
        var _this = $(this);
        var _parent = _this.closest('.pivotgrid-table-right-cell-inside');
        var _columnHtml = _parent.find('.datagrid-view2 .datagrid-header-inner').html();
        var _rowHtml = _parent.find('.datagrid-view1 .datagrid-body-inner').html();
        var _valueHtml = _parent.find('.datagrid-view2 .datagrid-body').html();
        
        $.fileDownload(URL_APP + 'mdpivot/excelExport', {
            httpMethod: "POST",
            data: {
                columnHtml: _columnHtml,
                rowHtml: _rowHtml,
                valueHtml: _valueHtml
            }
        }).done(function() {
            Core.unblockUI();
        }).fail(function(response){
            PNotify.removeAll();
            new PNotify({
                title: 'Error',
                text: response,
                type: 'error',
                sticker: false
            });
            Core.unblockUI();
        });
    });
    
});

function loadPivotGrid_<?php echo $this->uniqId; ?>() {
    $('#pv-table-<?php echo $this->uniqId; ?>').pivotgrid({
        method: 'post',
        url: 'mdobject/dataViewDataGrid', 
        queryParams: {
            metaDataId: '<?php echo $this->dataViewId; ?>',  
            defaultCriteriaData: $('form', '#dm-pivot-search-<?php echo $this->uniqId; ?>').serialize(), 
            isPivot: true
        }, 
        pivot: {
            columns: [<?php echo rtrim($this->columnFieldsGrid, ','); ?>],
            rows: [<?php echo rtrim($this->rowFieldsGrid, ','); ?>],
            values: [<?php echo rtrim($this->valueFieldsGrid, ','); ?>]
        },
        forzenColumnTitle: '<span style="font-weight: bold">Pivot Grid</span>',
        valueFieldWidth: 110, 
        onLoadSuccess: function(row, data) {
            $('#pv-table-<?php echo $this->uniqId; ?>').pivotgrid('resize');
        }
    });
}

$('#pivotgird<?php echo $this->uniqId ?>').on('click', function() {
    $('#pv-table-<?php echo $this->uniqId; ?>').pivotgrid('resize');
});

</script>