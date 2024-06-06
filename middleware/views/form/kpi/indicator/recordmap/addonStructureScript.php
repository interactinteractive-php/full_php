<?php
if (Mdform::$addRowsTemplate) {
    $addRowsTemplate = implode('', Mdform::$addRowsTemplate);
    $addRowsTemplate = str_replace('type="text/template"', 'type="text/template" data-uniqid="'.$this->subUniqId.'"', $addRowsTemplate);
    echo $addRowsTemplate;
}
?>
<script type="text/javascript">
var $kpiTmp_<?php echo $this->subUniqId; ?> = $('div[data-addonform-uniqid="<?php echo $this->subUniqId; ?>"]');
var bp_window_<?php echo $this->subUniqId; ?> = $kpiTmp_<?php echo $this->subUniqId; ?>;
var isEditMode_<?php echo $this->subUniqId; ?> = <?php echo ((Mdform::$firstTplId) ? 'true' : 'false'); ?>;
var $aggregate_<?php echo $this->subUniqId; ?> = bp_window_<?php echo $this->subUniqId; ?>.find('.kpi-dtl-table:not(.bprocess-table-subdtl, [data-pager="true"]) > thead > tr > th[data-aggregate]:not([data-aggregate=""])');

if ($kpiTmp_<?php echo $this->subUniqId; ?>.find("th[data-merge-cell='true']:eq(0)").length) {
    $kpiTmp_<?php echo $this->subUniqId; ?>.find("table > thead:has(th[data-merge-cell='true'])").each(function() {
        $(this).TableSpan('horizontal');
    });
}

if ($kpiTmp_<?php echo $this->subUniqId; ?>.find("td[data-merge-cell='true']:eq(0)").length) {
    $kpiTmp_<?php echo $this->subUniqId; ?>.find("table > tbody:has(td[data-merge-cell='true'])").each(function() {
        $(this).TableSpan('verticalstatement').TableSpan('horizontalstatement');
    });
}

Core.initNumberInput($kpiTmp_<?php echo $this->subUniqId; ?>);
Core.initLongInput($kpiTmp_<?php echo $this->subUniqId; ?>);
Core.initDateInput($kpiTmp_<?php echo $this->subUniqId; ?>);
Core.initDateTimeInput($kpiTmp_<?php echo $this->subUniqId; ?>);
Core.initSelect2($kpiTmp_<?php echo $this->subUniqId; ?>);
Core.initUniform($kpiTmp_<?php echo $this->subUniqId; ?>);
Core.initDateMinuteInput($kpiTmp_<?php echo $this->subUniqId; ?>);
Core.initTimeInput($kpiTmp_<?php echo $this->subUniqId; ?>);
Core.initTextareaAutoHeight($kpiTmp_<?php echo $this->subUniqId; ?>);
Core.initRegexMaskInput($kpiTmp_<?php echo $this->subUniqId; ?>);
Core.initTinymceEditor($kpiTmp_<?php echo $this->subUniqId; ?>);
Core.initFieldSetCollapse($kpiTmp_<?php echo $this->subUniqId; ?>);

<?php echo $this->addonFullExp['varFnc']; ?>  
    
$(function() {
    
    $kpiTmp_<?php echo $this->subUniqId; ?>.on('keyup paste cut', 'input.kpiDecimalInit', function(e){
        var code = e.keyCode || e.which;
        if (code == 9 || code == 13 || code == 27 || code == 37 || code == 38 || code == 39 || code == 40) return false;
        var $this = $(this);
        $this.next('input[type=hidden]').val($this.val().replace(/[,]/g, ''));
    });
    
    $kpiTmp_<?php echo $this->subUniqId; ?>.on('keydown', 'input.kpiDecimalInit', function(e){
        var code = e.keyCode || e.which;
        if (code == 9 || code == 13 || code == 38 || code == 40) {
            var $this = $(this), $thisNext = $this.next('input[type=hidden]'), $thisVal = $this.val();
            if ($thisVal !== $thisNext.val()) {
                $thisNext.val($thisVal.replace(/[,]/g, ''));
            }
        }
    });
    
    $kpiTmp_<?php echo $this->subUniqId; ?>.on('change', 'select.select2', function() {
        
        var $this = $(this), $parent = $this.parent(), $descName = $parent.find('input[name*="_DESC]"]');
        
        if ($descName.length) {
            var descName = '';
            if ($this.val() != '') {
                if ($this.hasAttr('data-name')) {
                    var $option = $this.find('option:selected');
                    var rowData = $option.data('row-data');
                    
                    if (typeof rowData !== 'object') {
                        rowData = JSON.parse(html_entity_decode(rowData, 'ENT_QUOTES'));
                    } 
                    
                    descName = rowData[$this.attr('data-name')];
                } else {
                    descName = $this.find('option:selected').text();
                }
            }
            $descName.val(descName);
        }
    });
    
    $kpiTmp_<?php echo $this->subUniqId; ?>.on('change', 'input.md-radio', function() {

        var $this = $(this), $parent = $this.closest('.radioInit'), $descName = $parent.find('input[name*="_DESC]"]');
        
        if ($descName.length) {
            var descName = '';
            if ($this.val() != '') {
                if ($this.hasAttr('data-name')) {
                    var $option = $this.find('option:selected');
                    var rowData = $option.data('row-data');
                    
                    if (typeof rowData !== 'object') {
                        rowData = JSON.parse(html_entity_decode(rowData, 'ENT_QUOTES'));
                    } 
                    
                    descName = rowData[$this.attr('data-name')];
                } else {
                    descName = ($parent.find('input:checked').closest('.radio-inline').text()).trim();
                }
            }
            $descName.val(descName);
        }
    });
    
    <?php echo $this->addonScripts; ?>
    
    bpFullScriptsWithoutEvent_<?php echo $this->subUniqId; ?>();
    
    <?php echo $this->addonFullExp['event']; ?>  
        
    $kpiTmp_<?php echo $this->subUniqId; ?>.on('keydown', 'input[type="text"]', function(e) {
                    
        var keyCode = (e.keyCode ? e.keyCode : e.which);

        if (keyCode == 38) { /*up*/

            var $this = $(this);
            var $row = $this.closest('tr');
            var $cell = $this.closest('td');
            var colIndex = $cell.index();
            var $prevRow = $row.prevAll('tr:not(.trnslt-groupname):first');

            if ($prevRow.length) {
                $prevRow.find('td:eq('+colIndex+') > input').focus().select();
                return e.preventDefault();
            }
        } else if (keyCode == 13 || keyCode == 40) { /*enter or down*/

            var $this = $(this);
            var $row = $this.closest('tr');
            var $cell = $this.closest('td');
            var colIndex = $cell.index();
            var $nextRow = $row.nextAll('tr:not(.trnslt-groupname):first');

            if ($nextRow.length) {
                $nextRow.find('td:eq('+colIndex+') > input').focus().select();
                return e.preventDefault();
            }
        }
    });
    
    $kpiTmp_<?php echo $this->subUniqId; ?>.find('input[data-auto-change="1"]').trigger('change');
    
    $kpiTmp_<?php echo $this->subUniqId; ?>.on('change', ".kpi-dtl-table > .tbody > .bp-detail-row input[type='text']:visible", function(){
        var $this = $(this);
        if (typeof $this.attr('data-prevent-change') !== 'undefined') {
            return;
        }
        
        dtlAggregateFunction_<?php echo $this->subUniqId; ?>();
    });   
    
    dtlAggregateFunction_<?php echo $this->subUniqId; ?>();
});

function dtlAggregateFunction_<?php echo $this->subUniqId; ?>() {
        
    if ($aggregate_<?php echo $this->subUniqId; ?>.length) {
        var $el = $aggregate_<?php echo $this->subUniqId; ?>, $len = $el.length, $i = 0;

        for ($i; $i < $len; $i++) { 
            var $row = $($el[$i]);
            var $funcName = $row.attr('data-aggregate');
            var $path = $row.attr('data-cell-path');
            var $table = $row.closest('table.kpi-dtl-table');
            var $gridBody = $table.find('> .tbody > .bp-detail-row:not(.removed-tr) > [data-cell-path="' + $path + '"]');
            var $footCell = $table.find('> tfoot > tr > [data-cell-path="' + $path + '"]');

            if ($funcName === 'sum') {
                if ($gridBody.eq(0).find('input[type="text"]').hasClass('bigdecimalInit')) {

                    var $sum = 0;
                    var $rows = $gridBody.find('input[type="hidden"][data-path*="_bigdecimal"]');
                    var $sumVal;

                    $rows.each(function(){
                        $sumVal = $(this).val();

                        if ($sumVal != '' && $sumVal != null) {
                            $sum += parseFloat($sumVal);
                        }
                    });
                } else {
                    var $sum = $gridBody.find('input[type="text"]').sum();
                }
                $footCell.autoNumeric('set', $sum);

            } else if ($funcName == 'avg') {

                var $avg = $table.find('> .tbody > .bp-detail-row:not(.removed-tr) > [data-cell-path="' + $path + '"] input[type="text"]').avg();
                $footCell.autoNumeric('set', $avg);

            } else if ($funcName == 'max') {

                var $max = $table.find('> .tbody > .bp-detail-row:not(.removed-tr) > [data-cell-path="' + $path + '"] input[type="text"]').max();
                $footCell.autoNumeric('set', $max);

            } else if ($funcName == 'min') {
                var $min = 0;
                $gridBody.each(function (index) {
                    if (typeof $(this).find('input[type="text"]').val() != 'undefined') {
                        var $cellVal = $(this).find('input[type="text"]').autoNumeric('get');
                        if ($cellVal != '' || Number($cellVal) > 0) {
                            $cellVal = Number($cellVal);
                            if (index === 0) {
                                $min = $cellVal;
                            }
                            if ($min > $cellVal) {
                                $min = $cellVal;
                            }
                        }
                    }
                });
                $footCell.autoNumeric('set', $min);
            }
        }
    }

    return;
}
function bpFullScriptsWithoutEvent_<?php echo $this->subUniqId; ?>(elem, groupPath, isAddMulti, isLastRow, multiMode) {
    var element = typeof elem === 'undefined' ? 'open' : elem; 
    var groupPath = typeof groupPath === 'undefined' ? '' : groupPath; 
    var isAddMulti = typeof isAddMulti === 'undefined' ? false : isAddMulti; 
    var isLastRow = typeof isLastRow === 'undefined' ? false : isLastRow; 
    var multiMode = typeof multiMode === 'undefined' ? '' : multiMode; 
    
    <?php echo $this->addonFullExp['withoutEvent']; ?> 
}
</script>

