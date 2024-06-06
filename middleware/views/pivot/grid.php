<div class="pivotgrid-table-right-cell-inside" id="pivotgrid-main-<?php echo $this->uniqId; ?>">
<?php
if ($this->columnFieldsGrid && $this->rowFieldsGrid && $this->valueFieldsGrid) {
    if ($this->filterFields) {
        $isFilter = true;
?>
<div class="xs-form" id="dm-pivot-search-<?php echo $this->uniqId; ?>">
    <fieldset class="collapsible mb5">
        <legend>Шүүлт</legend>
        <form class="form-horizontal" method="post">
            <div class="row">    
                <?php
                foreach ($this->filterFields as $param) {
                    
                    $metaDataCode = $param['FIELD_PATH'];
                    $param['PARAM_REAL_PATH'] = $metaDataCode;
                    $param['META_DATA_CODE'] = $metaDataCode;
                ?>
                <div class="col-md-4">
                    <div class="form-group row fom-row">
                        <?php 
                        $labelArr = array(
                            'text' => $this->lang->line($param['META_DATA_NAME']),
                            'for' => 'param['.$metaDataCode.']',
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
                            if (!empty($param['LOOKUP_META_DATA_ID']) && $param['LOOKUP_TYPE'] == 'popup' && $param['CHOOSE_TYPE'] == 'multi' && Input::isEmpty('defaultCriteriaData') == false) {
                                
                                if ($param['LOOKUP_META_DATA_ID'] != '' && $param['LOOKUP_TYPE'] == 'combo' && $param['CHOOSE_TYPE'] != 'singlealways') {
                                    $param['CHOOSE_TYPE'] = 'multi';
                                }
                                    
                                parse_str($_POST['defaultCriteriaData'], $criteriaParam);
                                
                                if (isset($criteriaParam[$metaDataCode.'_displayField']) && $criteriaParam[$metaDataCode.'_displayField'] != '') {
                                    $criteriaParamParse = $criteriaParam['param'][$metaDataCode];
                            ?>
                            <div class="meta-autocomplete-wrap" data-section-path="<?php echo $metaDataCode; ?>">
                                <div class="input-group double-between-input">
                                    
                                    <?php
                                    foreach ($criteriaParamParse as $ck => $cv) {
                                        echo '<input name="param['.$metaDataCode.'][]" class="popupInit" id="'.$metaDataCode.'_valueField" data-path="'.$metaDataCode.'" value="'.$cv.'" type="hidden">';
                                    }
                                    ?>

                                    <input id="<?php echo $metaDataCode; ?>_displayField" name="<?php echo $metaDataCode; ?>_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" value="<?php echo $criteriaParam[$metaDataCode.'_displayField']; ?>" title="<?php echo $criteriaParam[$metaDataCode.'_displayField']; ?>" placeholder="<?php echo $this->lang->line('code_search'); ?>" data-processid="<?php echo $this->dataViewId; ?>" data-lookupid="<?php echo $param['LOOKUP_META_DATA_ID']; ?>" data-lookuptypeid="<?php echo $param['LOOKUP_META_TYPE_ID']; ?>" data-field-name="<?php echo $metaDataCode; ?>" autocomplete="off" type="text"<?php echo ($param['IS_REQUIRED'] == '1' ? ' required="required"' : ''); ?>>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewSelectableGrid('<?php echo $metaDataCode; ?>', '<?php echo $this->dataViewId; ?>', '<?php echo $param['LOOKUP_META_DATA_ID']; ?>', 'multi', '<?php echo $metaDataCode; ?>', this, '<?php echo $this->dataViewId; ?>');"><i class="fa fa-search"></i></button>
                                    </span>     
                                    <span class="input-group-btn">
                                        <input id="<?php echo $metaDataCode; ?>_nameField" name="<?php echo $metaDataCode; ?>_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" value="<?php echo $criteriaParam[$metaDataCode.'_nameField']; ?>" title="<?php echo $criteriaParam[$metaDataCode.'_nameField']; ?>" placeholder="<?php echo $this->lang->line('name_search'); ?>" data-processid="<?php echo $this->dataViewId; ?>" data-lookupid="<?php echo $param['LOOKUP_META_DATA_ID']; ?>" data-lookuptypeid="<?php echo $param['LOOKUP_META_TYPE_ID']; ?>" data-field-name="<?php echo $metaDataCode; ?>" type="text"<?php echo ($param['IS_REQUIRED'] == '1' ? ' required="required"' : ''); ?>>      
                                    </span>     
                                </div>
                            </div>
                            <?php   
                            
                                } else {
                                    echo Mdcommon::criteriaCondidion(
                                        $param,     
                                        Mdwebservice::renderParamControl($this->dataViewId, $param, 'param['.$metaDataCode.']', $metaDataCode, null)
                                    );
                                }
                                
                            } else {
                                echo Mdcommon::criteriaCondidion(
                                    $param,     
                                    Mdwebservice::renderParamControl($this->dataViewId, $param, 'param['.$metaDataCode.']', $metaDataCode, null)
                                );
                            }
                            
                            if ($param['LOOKUP_META_DATA_ID'] != '' && $param['LOOKUP_TYPE'] != '') {
                                echo '<input type="hidden" name="criteriaCondition['.$metaDataCode.']" value="=">';
                            }
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
                            'class' => 'btn btn-sm btn-circle blue-madison pivot-calculate-btn', 
                            'value' => '<i class="fa fa-calculator"></i> Бодох', 
                            'data-view-id' => $this->calculateProcessId 
                        ),
                        ($this->calculateProcessId) ? true : false     
                    ) . ' '; 
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
<div class="jeasyuiPivotTheme2" id="pv-theme-<?php echo $this->uniqId; ?>">
    <table id="pv-table-<?php echo $this->uniqId; ?>" class="pv-main-element"></table>
</div>

<script type="text/javascript">
$(function(){
    
    $('.stop-propagation').on('click', function(e){
        e.stopPropagation();
    });
    
    $('.pv-filter-button').on('click', function(){
        var _this = $(this);
    });
    
    loadPivotGridSize_<?php echo $this->uniqId; ?>();
    
    <?php
    if (isset($this->fieldChooserMode) && $this->fieldChooserMode == 1) {
        echo 'loadPivotGrid_'.$this->uniqId.'();';
    }
    ?>
    
    <?php
    if (isset($isFilter)) {
    ?>
    var dv_search_<?php echo $this->uniqId; ?> = $('#dm-pivot-search-<?php echo $this->uniqId; ?>');
    
    dv_search_<?php echo $this->uniqId; ?>.on("change", "input.linked-combo", function(){
        
        var _this = $(this);
        var _outParam = _this.attr('data-out-param');
        var _outParamSplit = _outParam.split('|');

        for (var i = 0; i < _outParamSplit.length; i++) {
            var selfParam = _outParamSplit[i];
            var _cellSelect = dv_search_<?php echo $this->uniqId; ?>.find("select[data-path='" + selfParam + "']");

            if (_cellSelect.length === 0) {
                var _cellInp = dv_search_<?php echo $this->uniqId; ?>.find("input[data-path='" + selfParam + "']");
                if (_this.val().length > 0 && _cellInp.length > 0) {
                    _cellInp.closest('.meta-autocomplete-wrap').find('input').removeAttr('readonly disabled');
                    _cellInp.parent().find('button').removeAttr('disabled');
                }

            } else {

                var _inParams = '';

                if (_cellSelect.length) {
                    if (typeof _cellSelect.attr("data-in-param") !== 'undefined') {
                        var _inParam = _cellSelect.attr("data-in-param");
                        var _inParamSplit = _inParam.split("|");

                        for (var j = 0; j < _inParamSplit.length; j++) {
                            var _lastCombo = dv_search_<?php echo $this->uniqId; ?>.find("input[data-path='" + _inParamSplit[j] + "']");
                            if (_lastCombo.length && _lastCombo.val() !== '') {
                                _inParams += _inParamSplit[j] + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                            } else {
                                var _lastCombo = dv_search_<?php echo $this->uniqId; ?>.find("input[data-path='" + _inParamSplit[j] + "']");
                                if (_lastCombo.length && _lastCombo.val() !== '') {
                                    _inParams += _inParamSplit[j] + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                                }
                            }
                        }
                    }
                }

                if (_inParams !== '') {
                    $.ajax({
                        type: 'post',
                        url: 'mdobject/bpLinkedCombo',
                        data: {inputMetaDataId: '<?php echo $this->dataViewId; ?>', selfParam: selfParam, inputParams: _inParams},
                        dataType: "json",
                        async: false,
                        beforeSend: function () {
                            Core.blockUI({
                                animate: true
                            });
                        },
                        success: function (dataStr) {
                            if (_cellSelect.hasClass("select2")) {
                                _cellSelect.select2('val', '');
                                _cellSelect.select2('enable');
                            } else {
                                _cellSelect.val('');
                                _cellSelect.removeAttr('disabled');
                            }
                            $("option:gt(0)", _cellSelect).remove();

                            var comboData = dataStr[selfParam];
                            _cellSelect.addClass("data-combo-set");

                            $.each(comboData, function () {
                                _cellSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME));
                            });
                            Core.initSelect2(_cellSelect);
                            Core.unblockUI();
                        },
                        error: function () {
                            alert("Error");
                        }
                    });

                } else {
                    _cellSelect.select2('val', '');
                    _cellSelect.select2('disable');
                    $("option:gt(0)", _cellSelect).remove();
                    Core.initSelect2(_cellSelect);
                }
            }
        }
    });        
    
    $('#dm-pivot-search-<?php echo $this->uniqId; ?>').on('click', 'button.dm-pivot-filter-btn', function(){
        loadPivotGridSize_<?php echo $this->uniqId; ?>();
        loadPivotGrid_<?php echo $this->uniqId; ?>();
    });
    <?php
    }
    ?>
    
    $(window).bind('resize', function() {
        if ($("body").find("#pv-table-<?php echo $this->uniqId; ?>").length > 0 && $("body").find("#pivotgrid-main-<?php echo $this->uniqId; ?> .jeasyuiPivotTheme").is(':visible')) {
            var toolbarWidth = $("body").find("#pivotgrid-main-<?php echo $this->uniqId; ?>").width();
            var dataGridWidth = $("body").find("#pivotgrid-main-<?php echo $this->uniqId; ?>").find('div.datagrid-wrap:first').width();
            if (toolbarWidth !== dataGridWidth) {
                $("#pv-table-<?php echo $this->uniqId; ?>").treegrid('resize');
            }
        }
    });
    
    $('.pv-excel').on('click', function(){
        
        Core.blockUI({
            message: 'Exporting...', 
            boxed: true
        });
        
        var $this = $(this);
        var $parent = $this.closest('.pivotgrid-table-right-cell-inside');
        var $columnHtml = $parent.find('.datagrid-view2 .datagrid-header-inner').html();
        var $rowHtml = $parent.find('.datagrid-view1 .datagrid-body-inner').html();
        var $valueHtml = $parent.find('.datagrid-view2 .datagrid-body').html();
        
        $.fileDownload(URL_APP + 'mdpivot/excelExport', {
            httpMethod: "POST",
            data: {
                columnHtml: $columnHtml,
                rowHtml: $rowHtml,
                valueHtml: $valueHtml
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
    
    <?php
    if ($this->calculateProcessId) {
    ?>
    $("button.pivot-calculate-btn", '#pivotgrid-main-<?php echo $this->uniqId; ?>').on("click", function() {
        var _this = $(this);
        var form = $('form', '#dm-pivot-search-<?php echo $this->uniqId; ?>');
        
        $.ajax({
            type: 'post',
            url: 'mdstatement/processRun',
            data: {
                processMetaId: _this.attr("data-view-id"), 
                filterStartDate: form.find("input[data-path='filterstartdate']").val(),
                filterEndDate: form.find("input[data-path='filterenddate']").val(),
                departmentId: form.find("[data-path='filterdepartmentid']").val()
            }, 
            dataType: 'json', 
            beforeSend: function () {
                Core.blockUI({
                    message: '<?php echo $this->lang->line('process_running'); ?>',
                    boxed: true
                });
            },
            success: function (data) {
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        });
    });    
    <?php
    }
    ?>        
});

function loadPivotGridSize_<?php echo $this->uniqId; ?>() {
    if ($('#pivotgrid-main-<?php echo $this->uniqId; ?>').closest("div[id*='dialog-pivot']").length > 0) {
        
        var isDynamicSizeSet = true;
        
        $('#pivotgrid-main-<?php echo $this->uniqId; ?>').closest("div[id*='dialog-pivot']").bind("dialogextendmaximize", function(){
            
            var dialogHeight = $('#pivotgrid-main-<?php echo $this->uniqId; ?>').closest("div[id*='dialog-pivot']").innerHeight();
            var scrollTop = $(window).scrollTop();
            var elementOffset = $('#pv-theme-<?php echo $this->uniqId; ?>').offset().top;
            var currentElementOffset = (elementOffset - scrollTop);
            isDynamicSizeSet = false;
            
            $('#pv-table-<?php echo $this->uniqId; ?>').attr('height', (dialogHeight - currentElementOffset));
            loadPivotGrid_<?php echo $this->uniqId; ?>();
        });

        if (isDynamicSizeSet) {
            var dialogHeight = $('#pivotgrid-main-<?php echo $this->uniqId; ?>').closest("div[id*='dialog-pivot']").innerHeight();
            var scrollTop = $(window).scrollTop();
            var elementOffset = $('#pv-theme-<?php echo $this->uniqId; ?>').offset().top;
            var currentElementOffset = (elementOffset - scrollTop);
            $('#pv-table-<?php echo $this->uniqId; ?>').attr('height', (dialogHeight - currentElementOffset));
        }
        
    } else {
        $('#pv-table-<?php echo $this->uniqId; ?>').attr('height', ($(window).height() - $('#pv-table-<?php echo $this->uniqId; ?>').offset().top - 20));
        loadPivotGrid_<?php echo $this->uniqId; ?>();
    }
}
function loadPivotGrid_<?php echo $this->uniqId; ?>() {

    if ($('#dm-pivot-search-<?php echo $this->uniqId; ?>').length) {
        
        $('form', '#dm-pivot-search-<?php echo $this->uniqId; ?>').validate({errorPlacement: function () {}});
        var select2MultiValidate = true;
        
        $('select.select2', 'form', '#dm-pivot-search-<?php echo $this->uniqId; ?>').each(function(){
            var _this = $(this);
            if (typeof _this.attr('required') !== 'undefined' && typeof _this.attr('multiple') !== 'undefined' && _this.val() === null) {
                _this.addClass('error');
                select2MultiValidate = false;
            } else if (typeof _this.attr('required') !== 'undefined' && typeof _this.attr('multiple') !== 'undefined' && _this.val() !== null) {
                _this.removeClass('error');
                select2MultiValidate = true;
            }
        });
        
        if (!$('form', '#dm-pivot-search-<?php echo $this->uniqId; ?>').valid() || !select2MultiValidate) {
            return;
        }
    }
    
    $('#pv-table-<?php echo $this->uniqId; ?>').pivotgrid({
        method: 'post',
        url: '<?php echo isset($this->commandName) ? 'mdpivot/dataViewByProcess' : 'mdobject/dataViewDataGrid'; ?>', 
        queryParams: {
            metaDataId: '<?php echo $this->dataViewId; ?>',  
            defaultCriteriaData: $('form', '#dm-pivot-search-<?php echo $this->uniqId; ?>').serialize(), 
            isPivot: true, 
            commandName: '<?php echo issetVar($this->commandName); ?>', 
            reportModelId: '<?php echo issetVar($this->reportModelId); ?>'
        }, 
        showFooter: true,          
        pivot: {
            columns: [<?php echo rtrim($this->columnFieldsGrid, ','); ?>],
            rows: [<?php echo rtrim($this->rowFieldsGrid, ','); ?>],
            values: [<?php echo rtrim($this->valueFieldsGrid, ','); ?>]
        },
        frozenColumnTitle: '<span style="font-weight: bold">Pivot Grid</span>',
        valueFieldWidth: 110, 
        onLoadSuccess: function(row, data){
            
            var $thisPivot = $(this);
            var $thisColumnFields = $thisPivot.pivotgrid('getColumnFields');
            var $thisRoots = $thisPivot.pivotgrid('getRoots');
            var $pivotFooter = {
                _tree_field: 'НИЙТ:'
            };
            var i = 0, j = 0, $thisColumnFieldsLength = $thisColumnFields.length, $thisRootsLength = $thisRoots.length;
            
            for (i; i < $thisColumnFieldsLength; i++) {
                $pivotFooter[$thisColumnFields[i]] = 0;
            }
            
            var v = 0;
            
            for (j; j < $thisRootsLength; j++) {
                
                var $thisRootsRow = $thisRoots[j], i = 0;
                
                for (i; i < $thisColumnFieldsLength; i++) {
                    
                    v = $thisRootsRow[$thisColumnFields[i]];
                    v = parseFloat(v.replace(/,/g, ''))||0;

                    $pivotFooter[$thisColumnFields[i]] += v;
                }
            }
            
            i = 0;
            
            for (i; i < $thisColumnFieldsLength; i++) {
                $pivotFooter[$thisColumnFields[i]] = accounting.formatMoney($pivotFooter[$thisColumnFields[i]], '');
            }

            $thisPivot.pivotgrid('reloadFooter', [$pivotFooter]);
        }
    });
}
</script>
<?php
} else {
    echo html_tag('div', array('class' => 'alert alert-warning'), 'Та тохиргооны талбаруудыг бүрэн сонгоно уу.');
}
?>
</div>