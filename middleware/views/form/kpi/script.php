<?php 
if (Mdform::$isWizard == false) { 
?>
<script type="text/javascript">
<?php 
}
$uniqId = getUID();
?>
    
var $kpiTmp_<?php echo $this->templateId; ?> = $('#kpiDmDtl-<?php echo $this->templateId; ?>'), 
    $kpiTmpUniq_<?php echo $uniqId; ?> = $kpiTmp_<?php echo $this->templateId; ?>;

<?php
if (Mdform::$isUseMergeMatrix) {
?>
    $kpiTmp_<?php echo $this->templateId; ?>.find('.kpi-dtl-table > tbody').TableSpan('verticalstatement').TableSpan('horizontalstatement');
<?php
}
?>
    
if ($kpiTmp_<?php echo $this->templateId; ?>.find("td[data-merge-cell='true']:eq(0)").length) {
    $kpiTmp_<?php echo $this->templateId; ?>.find("table > tbody:has(td[data-merge-cell='true'])").each(function() {
        $(this).TableSpan('verticalstatement').TableSpan('horizontalstatement');
    });
}

Core.initNumberInput($kpiTmp_<?php echo $this->templateId; ?>);
Core.initDateInput($kpiTmp_<?php echo $this->templateId; ?>);
Core.initDateTimeInput($kpiTmp_<?php echo $this->templateId; ?>);
Core.initSelect2($kpiTmp_<?php echo $this->templateId; ?>);
Core.initUniform($kpiTmp_<?php echo $this->templateId; ?>);
Core.initDateMinuteInput($kpiTmp_<?php echo $this->templateId; ?>);
Core.initTimeInput($kpiTmp_<?php echo $this->templateId; ?>);
Core.initTextareaAutoHeight($kpiTmp_<?php echo $this->templateId; ?>);
Core.initRegexMaskInput($kpiTmp_<?php echo $this->templateId; ?>);
Core.initTinymceEditor($kpiTmp_<?php echo $this->templateId; ?>);
Core.initMaxLength($kpiTmp_<?php echo $this->templateId; ?>);
$kpiTmp_<?php echo $this->templateId; ?>.find('.longInit, .integerInit').autoNumeric('init', {aSep: '', vMin: '-999999999999999999999999999999', vMax: '999999999999999999999999999999'});

<?php echo $this->kpiFullExpressionVarFnc; ?>    

$(function() { 
    
    <?php
    if ($this->viewMode != 'horizontalform') {
    ?>
    var $detailElement_<?php echo $uniqId; ?> = $kpiTmpUniq_<?php echo $uniqId; ?>.find('table:eq(0)');
    
    <?php 
    if ($this->renderType != 'detail') {
        if (Mdform::$isUseMergeMatrix) {
    ?>
            $detailElement_<?php echo $uniqId; ?>.tableHeadFixer({'head': true, 'foot': true, 'left': <?php echo issetDefaultVal($this->kpiCountColumnFreeze, 1) ?>, 'z-index': 9}); 
    <?php
        } else {
    ?>
            $detailElement_<?php echo $uniqId; ?>.tableHeadFixer({'head': true, 'foot': true, 'left': <?php echo isset($this->kpiCountColumnFreeze) ? $this->kpiCountColumnFreeze + Mdform::$mergeColCount : 2 ?>, 'z-index': 9}); 
    <?php
        }
    ?>
            
    $kpiTmp_<?php echo $this->templateId; ?>.trigger('scroll');
    
    <?php 
    }
    
    if (Mdform::$isUseMergeMatrix) {
    ?>
    var $hightLightRows_<?php echo $this->templateId; ?> = $detailElement_<?php echo $uniqId; ?>.find('tr.kpi-row-yellowbold');
    var $descrRows_<?php echo $this->templateId; ?> = $detailElement_<?php echo $uniqId; ?>.find('tr[data-descr][data-descr!=""]');
    
    if ($hightLightRows_<?php echo $this->templateId; ?>.length) {
        $hightLightRows_<?php echo $this->templateId; ?>.each(function() {
            var $thisRow = $(this);
            $thisRow.find('td[data-merge-cell]:not([rowspan])').addClass('kpi-col-yellowbold');
        });
    }
    if ($descrRows_<?php echo $this->templateId; ?>.length) {
        $descrRows_<?php echo $this->templateId; ?>.each(function() {
            var $thisRow = $(this);
            $thisRow.find('td[data-merge-cell]:not(.kpi-num-cell,[rowspan]):eq(0)').append(' <i class="icon-info22 font-size-12" title="'+$thisRow.data('descr')+'"></i>');
        });
    }
    
    <?php
    }
    ?>
    
    <?php
    }
    ?>
    
    <?php echo $this->kpiFullExpressionEvent; ?>  
    
    if ('<?php echo $this->viewMode; ?>' == 'view') {
        
        bp_window_<?php echo $this->templateId; ?>.find('input[type=text]', '#kpiDmDtl-<?php echo $this->templateId; ?>').each(function(){
            var $this = $(this);
            $this.replaceWith($('<span class="kpi-view-span"/>').text($this.val()));
        });
        bp_window_<?php echo $this->templateId; ?>.find('input[type=radio]:checked', '#kpiDmDtl-<?php echo $this->templateId; ?>').each(function(){
            var $this = $(this);
            var _text = $this.closest('.radio-inline').text();
            $this.closest('.radioInit').replaceWith($('<span class="kpi-view-span"/>').text(_text));
        });
        bp_window_<?php echo $this->templateId; ?>.find('select', '#kpiDmDtl-<?php echo $this->templateId; ?>').each(function(){
            var $this = $(this), selectedText = '';
            $this.select2('destroy');
            
            if ($this.val() != '') {
                selectedText = $this.find("option:selected").text();
            } 
            $this.replaceWith($('<span class="kpi-view-span"/>').text(selectedText));
        });
        bp_window_<?php echo $this->templateId; ?>.find('.radioInit', '#kpiDmDtl-<?php echo $this->templateId; ?>').each(function(){
            var $this = $(this);
            $this.replaceWith($('<span class="kpi-view-span"/>').text(''));
        });
        bp_window_<?php echo $this->templateId; ?>.find('textarea:not(".text-left")', '#kpiDmDtl-<?php echo $this->templateId; ?>').each(function(){
            var $this = $(this);
            $this.replaceWith($('<span class="kpi-view-span"/>').text($this.val()));
        });
        bp_window_<?php echo $this->templateId; ?>.find('textarea.text-left', '#kpiDmDtl-<?php echo $this->templateId; ?>').each(function(){
            var $this = $(this);
            $this.replaceWith($('<span class="kpi-view-span text-left w-100 d-block"/>').text($this.val()));
        });
        bp_window_<?php echo $this->templateId; ?>.find('input[type=checkbox]:checked', '#kpiDmDtl-<?php echo $this->templateId; ?>').each(function(){
            var $this = $(this);
            $this.closest('.checker').replaceWith($('<div class="text-center"><i class="fa fa-check-circle"></i></div>'));
        }); 
        bp_window_<?php echo $this->templateId; ?>.find('.checker', '#kpiDmDtl-<?php echo $this->templateId; ?>').each(function(){
            var $this = $(this);
            $this.replaceWith($('<div class="text-center"><i class="fa fa-times-circle"></i></div>'));
        }); 
    }
    
    bp_window_<?php echo $this->templateId; ?>.on('select2-opening', 'select[data-criteria-param].select2', function(e) {
        var $this = $(this), $cellSelect = $this, _inParams = '', select2 = $this.data('select2');
        
        if ($cellSelect.hasAttr('data-live-search') && $cellSelect.attr('data-live-search') !== '') {
            _inParams = $cellSelect.attr('data-live-search') + '&';
        }
        
        if ($cellSelect.hasAttr('data-criteria-param') && $cellSelect.attr('data-criteria-param') !== '') {
            var _inParam = $cellSelect.attr('data-criteria-param');
            var _inParamSplit = _inParam.split('|');

            for (var j = 0; j < _inParamSplit.length; j++) {
                var fieldPathArr = _inParamSplit[j].split('@');
                var fieldPath = fieldPathArr[0];
                var inputPath = fieldPathArr[1];
                var fieldPathKpi = fieldPath.split('.');
                
                if (!fieldPathKpi.hasOwnProperty(1)) {
                    _inParams += inputPath + '=' + encodeURIComponent(fieldPath) + '&';
                } else {
                    var dtlCode = fieldPathKpi[0].toLowerCase().trim();
                    var $getRow = bp_window_<?php echo $this->templateId; ?>.find("[data-dtl-code='"+dtlCode+"']");       
                    var $table = $getRow.closest("[data-table-path='kpiDmDtl']");
                    var factName = fieldPathKpi[1].trim();
                    var groupPath = $table.attr('data-group-path');                

                    if (groupPath) {
                        var $getField = $getRow.find('[data-path="'+groupPath+'kpiDmDtl.'+factName+'"]:eq(0)');
                    } else {
                        var $getField = $getRow.find('[data-path="kpiDmDtl.'+factName+'"]:eq(0)');
                    }                

                    if ($getField.length && $getField.val() !== '') {
                        _inParams += inputPath + '=' + encodeURIComponent($getField.val()) + '&';
                    }
                }
            }
        }        

        if (_inParams !== '' && !$this.hasClass('data-combo-set')) {
            $this.addClass('data-combo-set');
            var comboDatas = [];

            $.ajax({
                type: 'post',
                url: 'mdform/kpiLinkedCombo',
                data: {lookupMetaDataId: $cellSelect.attr('data-metadataid'), lookupCriteria: _inParams},
                dataType: 'json',
                async: false,
                beforeSend: function () {
                    Core.blockUI({animate: true});
                },
                success: function (dataStr) {
                    if ($cellSelect.hasClass('select2')) {
                        $cellSelect.select2('val', '');
                    } else {
                        $cellSelect.val('');
                    }

                    $('option:gt(0)', $cellSelect).remove();

                    $.each(dataStr.data, function (i, r) {
                        $cellSelect.append($('<option />').val(r[dataStr.id]).text(r[dataStr.name]));

                        comboDatas.push({id: r[dataStr.id], text: r[dataStr.name]});
                    });

                    Core.unblockUI();
                },
                error: function () { alert('Error'); Core.unblockUI(); }
            }).done(function(){
                $cellSelect.select2({
                    results: comboDatas,
                    allowClear: true,
                    dropdownAutoWidth: true,
                    escapeMarkup: function(markup) {
                        return markup;
                    }
                });

                if (!select2.opened()) {
                    $cellSelect.select2('open');
                }                
            });
        }
    });

    $(document.body).delegate('table.bprocess-table-dtl > tbody > tr > td input.kpiDecimalInit:not([readonly], [disabled])', 'paste', function(e){
        var $start = $(this), source;

        if (window.clipboardData !== undefined) {
            source = window.clipboardData;
        } else {
            source = e.originalEvent.clipboardData;
        }
        var data = source.getData('Text');
        
        if (data.length) {
            
            var $rowCell = $start.closest('td'); 
            var $colIndex = $rowCell.index();
            var columns = data.split("\n");
            var i, columnsLength = columns.length;
            
            for (i = 0; i < columnsLength; i++) {
                if (columns[i]) {
                    $start.autoNumeric('set', columns[i].replace(/[,]/g, '')).trigger('keyup').trigger('change');
                    $start = $start.closest('tr').next('tr').find('td:eq('+$colIndex+') input[type=text]:visible:eq(0)');
                    if (!$start.length) {
                        return false;  
                    }
                }
            }
            
            e.preventDefault();
        }
    });    

    $(document.body).delegate('table.bprocess-table-dtl > tbody > tr > td input.kpiDecimalInit', 'change', function(e){
        var $thisInput = $(this),
            dtlId = $thisInput.attr('data-parent-dtlid'),
            $kpiDmDtl = $thisInput.closest('div[data-parent-path="kpiDmDtl"]'), 
            $getIndicator = $kpiDmDtl.find('[data-aggregate-indicator="'+dtlId+'"]');
        
        if ($getIndicator.length) {
            
            if ($getIndicator.hasAttr('data-max-val') || $getIndicator.hasAttr('data-min-val')) {
                var $kpitr, kpitrsum = 0;

                $thisInput.closest('tbody').find('tr').each(function(){
                    $kpitr = $(this);
                    if ($kpitr.find('[data-parent-dtlid="'+dtlId+'"]').hasClass('kpiDecimalInit')) {
                        kpitrsum += Number($kpitr.find('[data-parent-dtlid="'+dtlId+'"]').autoNumeric('get'));
                    }
                });

                if ($getIndicator.attr('data-max-val') < kpitrsum) {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Warning',
                        text: 'Хамгийн их утгаас их байна.',
                        type: 'warning',
                        addclass: pnotifyPosition,
                        sticker: false
                    });
                    $thisInput.val('');
                }
                if ($getIndicator.attr('data-min-val') > kpitrsum) {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Warning',
                        text: 'Хамгийн бага утгаас бага байна.',
                        type: 'warning',
                        addclass: pnotifyPosition,
                        sticker: false
                    });
                    $thisInput.val('');
                }
            }
            
            var inputFactCode = $thisInput.attr('data-col-path'),
                $getIndicator = $kpiDmDtl.find('[data-aggregate-indicator="'+dtlId+'"][data-fact-code="'+inputFactCode+'"]');
        
            if ($getIndicator.length) {
                
                var $indicatorAggrFnc = $getIndicator.parent().find('.aggregate-indicator-total');
                
                if ($indicatorAggrFnc.length) {
                    
                    var dtlId    = $getIndicator.attr('data-aggregate-indicator');
                    var aggregateVal = 0;
                    var $input = $kpiDmDtl.find('input[data-col-path="'+inputFactCode+'"][data-parent-dtlid="'+dtlId+'"]:not([data-not-aggregate])');
        
                    if ($indicatorAggrFnc.hasAttr('data-aggr-fnc')) {
                        var aggrFnc = $indicatorAggrFnc.attr('data-aggr-fnc');
                        if (aggrFnc == 'sum') {
                            aggregateVal = Number($input.sum()); 
                        } else if (aggrFnc == 'avg') {
                            var sum = Number($input.sum());
                            if (sum > 0) {
                                aggregateVal = sum / Number($input.length); 
                            }
                        }
                    } else {
                        aggregateVal = Number($input.sum()); 
                    }
                    
                    if ($indicatorAggrFnc.hasAttr('data-aggr-input') && $indicatorAggrFnc.attr('data-aggr-input') == '1') {
                        $indicatorAggrFnc.parent().find('input.kpiDecimalInit').autoNumeric('set', gridAmountNullField(aggregateVal)).trigger('change');
                    } else {
                        $indicatorAggrFnc.text(gridAmountNullField(aggregateVal));
                    }        
                }
            } 
        } 
        e.preventDefault();
    });    
    
    $kpiTmp_<?php echo $this->templateId; ?>.on('click', '.kpi-obj-view-controller button.btn', function() {
    
        var $this = $(this);
        var $parent = $this.parent();
        var type = $this.attr('data-value');
        var $tabPane = $this.closest('.tab-pane');
        
        $parent.find('.active').removeClass('active');
        $this.addClass('active');
        
        if (type == 'box') {
            $tabPane.find('[data-section="list"], [data-section="graph"], [data-section="dependencymap"]').addClass('d-none');
            $tabPane.find('[data-section="box"]').removeClass('d-none');
        } else if (type == 'list') {
            $tabPane.find('[data-section="box"], [data-section="graph"], [data-section="dependencymap"]').addClass('d-none');
            $tabPane.find('[data-section="list"]').removeClass('d-none');
        } else if (type == 'graph') {
            
            var $graphSection = $tabPane.find('[data-section="graph"]');
            
            $tabPane.find('[data-section="box"], [data-section="list"], [data-section="dependencymap"]').addClass('d-none');
            $graphSection.removeClass('d-none');
            
            if ($graphSection.children().length == 0) {
                kpiDmMartRelationTreeChartRender($graphSection, $tabPane.innerWidth() - 15, $this.attr('data-record-id'));
            }
            
        } else if (type == 'dependencymap') {
            
            var $dependencySection = $tabPane.find('[data-section="dependencymap"]');
            
            $tabPane.find('[data-section="box"], [data-section="graph"], [data-section="list"]').addClass('d-none');
            $dependencySection.removeClass('d-none');
            
            if ($dependencySection.children().length == 0) {
                $.ajax({
                    type: 'post',
                    url: 'mdobject/dataview/1587360386256/0/json?dv[id]='+$this.attr('data-record-id'),
                    dataType: 'json',
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function(dataHtml) {
                        $dependencySection.css('height', '500px').attr('data-hard-height', '500').empty().append(dataHtml.Html).promise().done(function() {
                            $dependencySection.find('> .row > .col-md-12:eq(0), .remove-type-1587360386256').remove();
                            Core.unblockUI();
                        });
                    }
                });
            }
        }
    });
    
    $kpiTmp_<?php echo $this->templateId; ?>.on('click', 'button.kpi-form-fullsreen-btn', function() {
        
        var $this = $(this), $parent = $this.closest('.tab-pane'), 
            $sections = $parent.find('> div[data-section]');
        
        if (!$this.hasAttr('data-fullscreen')) {
            
            $this.attr({'data-fullscreen': '1', 'title': 'Restore'}).find('i').removeClass('fa-expand').addClass('fa-compress');
            $parent.addClass('bp-dtl-fullscreen');
            
            var toolbarHeight = $this.closest('.kpi-form-view-toolbar').outerHeight(true);
            var windowHeight = $(window).height() - toolbarHeight - 20;
            $sections.css({'height': windowHeight, 'overflow': 'auto', 'overflow-x': 'hidden'});
            
        } else {
            $this.attr('title', 'Fullscreen').removeAttr('data-fullscreen').find('i').removeClass('fa-compress').addClass('fa-expand');
            $parent.removeClass('bp-dtl-fullscreen');
            $sections.css({'height': '', 'overflow': '', 'overflow-x': ''});
        }
    });
    
    $kpiTmp_<?php echo $this->templateId; ?>.on('change', 'input[name*="param[kpiDmDtl.childObjectPopup]"]', function() {
        var $this = $(this);
        var $parent = $this.closest('.meta-autocomplete-wrap');
        var rowData = $this.attr('data-row-data');
        var $hidden = $parent.next('input[type="hidden"]');
        
        if (rowData) {
            var jsonObj = JSON.parse(rowData);
            $hidden.val(jsonObj.templateid);
        } else {
            $hidden.val('');
        }
        
        var $childObjectPopup = $this.closest('[data-section-path="kpiDmDtl"]').find('input[name*="param[kpiDmDtl.childObjectPopup]"]').filter(function() { return this.value != ''; }); 
        var pLength = $childObjectPopup.length;
        
        if (pLength) {
            var i = 0, names = '';
            for (i; i < pLength; i++) { 
                var subRowData = $($childObjectPopup[i]).attr('data-row-data');
                var subJsonObj = JSON.parse(subRowData);
                names += subJsonObj.name + ' ';
            }
            $this.closest('form').find('input[data-path="name"]').val(names);
        }
    });
    
    $kpiTmp_<?php echo $this->templateId; ?>.on('click', '.kpi-indicator-child-collapse', function() {
        var $this = $(this), $parent = $this.closest('tr[data-dtl-id]'), 
            $tbody = $this.closest('tbody'), 
            $icon = $this.find('i'), 
            dtlId = $parent.attr('data-dtl-id');
        
        if ($this.hasAttr('data-opened')) {
            $icon.removeClass('fa-minus-square').addClass('fa-plus-square');
            $this.removeAttr('data-opened');
            $tbody.find('tr[data-dtl-parentid="'+dtlId+'"]').addClass('d-none');
        } else {
            $icon.removeClass('fa-plus-square').addClass('fa-minus-square');
            $this.attr('data-opened', 1);
            $tbody.find('tr[data-dtl-parentid="'+dtlId+'"]').removeClass('d-none');
        }
    });
    
    var $rows = $kpiTmpUniq_<?php echo $uniqId; ?>.find('[data-aggregate-indicator]'); 

    if ($rows.length) {
        var len = $rows.length, i = 0;

        for (i; i < len; i++) { 
            
            var $row = $($rows[i]);
            
            if ($row.hasAttr('data-aggr-input') && $row.attr('data-aggr-input') == '1') {
                
                var dtlId = $row.attr('data-aggregate-indicator');
                var $calcAggrInput = $kpiTmpUniq_<?php echo $uniqId; ?>.find('[data-parent-dtlid="'+dtlId+'"]').filter(function() { return this.value != ''; }); 
				
                if ($calcAggrInput.length && $calcAggrInput.eq(0).val() != '') {
                    $calcAggrInput.eq(0).trigger('change');
                }
                
            } else {
                
                var $indicatorAggrFnc = $row.parent().find('.aggregate-indicator-total');
            
                if ($indicatorAggrFnc.length) {

                    var factCode = $row.attr('data-fact-code');
                    var dtlId    = $row.attr('data-aggregate-indicator');
                    var aggregateVal = 0;
                    var $input = $kpiTmpUniq_<?php echo $uniqId; ?>.find('input[data-col-path="'+factCode+'"][data-parent-dtlid="'+dtlId+'"]');

                    if ($indicatorAggrFnc.hasAttr('data-aggr-fnc')) {
                        var aggrFnc = $indicatorAggrFnc.attr('data-aggr-fnc');
                        if (aggrFnc == 'sum') {
                            aggregateVal = Number($input.sum()); 
                        } else if (aggrFnc == 'avg') {
                            var sum = Number($input.sum());
                            if (sum > 0) {
                                aggregateVal = sum / Number($input.length); 
                            }
                        }
                    } else {
                        aggregateVal = Number($input.sum()); 
                    }

                    if (aggregateVal !== 0) {
                        if ($indicatorAggrFnc.hasAttr('data-aggr-input') && $indicatorAggrFnc.attr('data-aggr-input') == '1') {
                            $indicatorAggrFnc.parent().find('input.kpiDecimalInit').autoNumeric('set', gridAmountNullField(aggregateVal));
                        } else {
                            $indicatorAggrFnc.text(gridAmountNullField(aggregateVal));
                        }     
                    }
                }
            }
        }
    }    
    
    var $graphInputs = $kpiTmp_<?php echo $this->templateId; ?>.find('.mxgraph-load').filter(function() { return this.value != ''; });
    
    if ($graphInputs.length) {
    
        if (typeof isBpmEditorUiInit === 'undefined') {
            $.getScript(URL_APP + 'middleware/assets/js/bpm/addon.js').done(function() {
                bpmDiagramViewByElement($graphInputs);
            });
        } else {
            bpmDiagramViewByElement($graphInputs);
        }
    }

});

function kpiBeforeSave_<?php echo $this->templateId; ?>(thisButton) {
    PNotify.removeAll();

    <?php echo $this->kpiFullExpressionBeforeSave; ?>

    return true;
}

function addChooseKpiObjectData(elem) {
    var $this = $(elem), $parent = $this.closest('.reldetail'), $tbl = $parent.find('table[data-name]');
    var templateDtlId = $tbl.attr('data-templateDtlId');
    var recordId = $tbl.attr('data-recordId');
    
    $this.attr({
        'data-name': $tbl.attr('data-name'), 
        'data-templateDtlId': templateDtlId, 
        'data-recordId': recordId, 
        'data-subtmpid': $tbl.attr('data-subtmpid')
    });
    
    dataViewCustomSelectableGrid('kpiRelatedObjectList', 'multi', 'chooseKpiObjectData', 'criteriaCondition[templateDtlId]==&param[templateDtlId]='+templateDtlId+'&criteriaCondition[filterId]==&param[filterId]='+recordId, elem, '', 1);
}

function chooseKpiObjectData(metaDataCode, chooseType, elem, rows) {
    
    var $this = $(elem), $table = $this.closest('table[data-dtlcode]');
    
    if ($table.length == 0) {
        $table = $this.closest('.reldetail').find('table[data-dtlcode]');
    }
    
    var $tbody = $table.find('> tbody:eq(0)'), i = 0, length = rows.length, 
        trHtml = '', subTmpBtn = '', isAddRow = true, 
        inputName = $this.data('name'), 
        rowRemove = '<a href="javascript:;" onclick="deleteKpiObjectData(this);" class="font-size-14"><i style="color:red;" class="fa fa-trash"></i></a>', 
        isSubTmpKeyId = $this.hasAttr('data-trgobjid');
    
    if ($this.hasAttr('data-subtmpid') && $this.attr('data-subtmpid')) {
        subTmpBtn = '<a href="javascript:;" onclick="bpKpiObjectSubTemplate(this, \''+$this.data('subtmpid')+'\');" class="mr10 font-size-14"><i style="color:#5c6bc0;" class="fa fa-external-link-square"></i></a> ';
    }
    
    if ($table.hasAttr('data-objtype-ignore-action') && $table.attr('data-objtype-ignore-action') == 'remove') {
        rowRemove = '';
    }
    
    if (isSubTmpKeyId) {
        var subTmpKeyIdIndex = $this.attr('data-kindex');
    }
    
    for (i; i < length; i++) {
        
        isAddRow = true;
        
        if ($tbody.find('tr[data-basketrowid="'+rows[i].id+'"]').length) {
            isAddRow = false;
        }
        
        if (isAddRow) {
            var name = htmlentities(rows[i].name, 'ENT_QUOTES', 'UTF-8'), subTmpKeyId = '';
            
            if (isSubTmpKeyId) {
                subTmpKeyId = '<input type="hidden" name="param[kpiDmDtl.subTmpKeyId]['+subTmpKeyIdIndex+'][]" data-path="kpiDmDtl.subTmpKeyId" value="'+rows[i].id+'">';
            }
    
            trHtml += '<tr data-basketrowid="'+rows[i].id+'">'+
                '<td style="height: 25px; max-width: 0;" class="text-left text-truncate" title="'+name+'"><i class="fa fa-tag bgicon"></i><input type="hidden" name="'+inputName+'" data-path="'+inputName+'" value="'+rows[i].id+'~~~'+name+'~~~'+rows[i].templateid+'"/>'+subTmpKeyId+rows[i].name+'</td>'+
                '<td style="width: 60px" class="text-right">' + subTmpBtn + rowRemove + '</td>'+
            '</tr>';
        }
    }
    
    $tbody.append(trHtml);
};   

function deleteKpiObjectData(elem) {
    var $row = $(elem).closest('tr');
    
    if ($row.hasAttr('data-relationid') && $row.attr('data-relationid')) {
        
        var $input = $row.find('> td:eq(0) > input');
        $input.val($input.val() + '~~~removed');
        
        $row.hide();
        
    } else {
        $row.remove();
    }
}
function kpiDmMartRelationTreeChart(elem, id) {
    if (typeof d3 === 'undefined') {
        $.getScript('assets/core/js/plugins/visualization/d3/d3.min.js').done(function() {
            $.getScript('middleware/assets/js/addon/kpi.js').done(function() {
                kpiDmMartRelationTreeChartInit(elem, id);
            });
        });
    } else if (typeof isKpiAddonScript === 'undefined') {
        $.getScript('middleware/assets/js/addon/kpi.js').done(function() {
            kpiDmMartRelationTreeChartInit(elem, id);
        });
    } else {
        kpiDmMartRelationTreeChartInit(elem, id);
    }
}
function kpiDmMartRelationTreeChartRender(elem, width, id) {
    if (typeof d3 === 'undefined') {
        $.cachedScript('assets/core/js/plugins/visualization/d3/d3.min.js').done(function() {   
            $.getScript('middleware/assets/js/addon/kpi.js').done(function() {
                kpiDmMartRelationTreeChartRenderInit(elem, width, id);
            });
        });
    } else if (typeof isKpiAddonScript === 'undefined') {
        $.getScript('middleware/assets/js/addon/kpi.js').done(function() {
            kpiDmMartRelationTreeChartRenderInit(elem, width, id);
        });
    } else {
        kpiDmMartRelationTreeChartRenderInit(elem, width, id);
    }
}
function drillLinkKpiMenu(id) {
    $.ajax({
        type: 'post',
        url: 'mdform/getDrillPanelTypeList',
        data: {objectId: id},
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            
            var metaDataId = data.metaDataId;
            
            window['ignoreFirstRowSelect_' + metaDataId] = data.clickMenuId;
            window['filterObjectDtl_' + metaDataId] = data.filterObjectDtl;
                
            appMultiTabByContent({metaDataId: ''+metaDataId, title: data.title, type: 'process', content: data.html});
        }
    });
}
function kpiIndicatorDrillProcess(elem, refId) {
    callWebServiceByMeta('1587374572669', true, undefined, undefined, undefined, undefined, undefined, undefined, undefined, undefined, undefined, undefined, 1, 'id='+refId);
}
function kpiTmpltDrillByDtlId(elem, metaId, dtlId) {
    callWebServiceByMeta(metaId, true, undefined, undefined, undefined, undefined, undefined, undefined, undefined, undefined, undefined, undefined, 1, 'id='+dtlId);
}

<?php if (Mdform::$isWizard == false) { ?>
</script>
<?php } ?>