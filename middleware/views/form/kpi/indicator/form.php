<?php if (issetParam($this->headerLogo) || issetParam($this->headerTitle)) { ?>
<div class="kpi-form-page-header">
    <div class="kpi-form-page-header-child">
        <?php
        if (issetParam($this->headerTitle)) {
            echo '<div class="kpi-form-page-header-text">'. $this->headerTitle .'</div>';
            $titleClass = 'kpi-form-page-title';
        }
        
        if (issetParam($this->headerLogo) && file_exists($this->headerLogo)) {
            echo '<img src="'.$this->headerLogo.'" class="kpi-form-page-logo">';
            $titleClass = 'kpi-form-page-title';
        } ?>
        <h1 class="kpi-form-page-title"><?php echo Str::nlTobr($this->title); ?></h1>
    </div>
</div>
<style type="text/css">
    .kpi-form-page-header {
        margin: -10px -15px;
        padding-top: 11px;
        padding-bottom: 20px;

        .kpi-form-page-header-child {
            position: relative;
            width: 100%;
            min-height: max-content;
            padding: 20px;
            
            .kpi-form-page-title {
                margin-top: 10px;
                text-align: center;
                font-size: 20px;
                font-weight: bold;
                line-height: 28px;
                margin-bottom: 20px;
                margin-left: auto;
                margin-right: auto;
                max-width: 500px;
            }

            .kpi-form-page-header-text {
                position: relative;
                max-height: 70px;
                top: 20px;
                right: 20px;
                text-align: right;
                line-height: 14px;
            }

            .kpi-form-page-logo {
                position: absolute;
                max-width: 150px;
                max-height: 70px;
                top: 20px;
                left: 20px;
            }
        }
        
    }
</style>
<?php 
} 

if (issetParam($this->renderComponentsBanner) === '1') {
    echo '<div class="col-md-12 center-sidebar metaverse-banner">';
        echo issetParam($this->showBanner);
    echo '<div class="processs-main-content">';
} 
if (Input::postCheck('endSessionLogStatusCombo')) {
    echo '<div class="my-2">'. html_entity_decode(Input::post('endSessionLogStatusCombo'), ENT_QUOTES).'</div>';
}
?>
<div class="kpi-ind-tmplt-section" id="kpi-<?php echo $this->uniqId; ?>" data-process-id="<?php echo $this->indicatorId; ?>" data-bp-uniq-id="<?php echo $this->uniqId; ?>">
    <div class="row" style="background-color: #fff;border-bottom-left-radius: .75rem;border-bottom-right-radius: .75rem;">
    <?php
    if (!Input::numeric('isIgnoreRunButton')) {
        
        if ($this->isKpiIndicatorRender == '1' && ($this->kpiTypeId == '1191' || $this->kpiTypeId == '2009')) {
    ?>
    <div class="text-right mb-2">
        <button type="button" class="btn btn-sm btn-circle btn-success bpMainSaveButton bp-btn-save" onclick="saveKpiIndicatorForm(this, '<?php echo $this->uniqId; ?>', '<?php echo $this->indicatorId; ?>');">
            <i class="icon-checkmark-circle2"></i> Ажиллуулах
        </button>
    </div>
    <?php
        }
        if ($this->kpiTypeId == '1043') { 
    ?>
        <div class="text-right mb-2">
            <button type="button" class="btn btn-sm btn-circle btn-success bpMainSaveButton bp-btn-save" onclick="runKpiIndicatorInternalQuery(this, '<?php echo $this->uniqId; ?>', '<?php echo $this->indicatorId; ?>');">
                <i class="icon-checkmark-circle2"></i> Ажиллуулах
            </button>
        </div>
    <?php
        }    
    }
    
    $isMainForm = $this->form ? true : false;
    $isComponentRenderType = (isset($this->components) && $this->components && $this->componentRenderType == 'tab');
    $isTopTabRenderShow = (isset(Mdform::$topTabRenderShow) && Mdform::$topTabRenderShow);
    
    if ($isMainForm 
            && (
                $isComponentRenderType 
                || $isTopTabRenderShow 
                || (isset($this->addonTabs['tabStart']) && $this->addonTabs['tabStart'])
            ) 
        ) { 
    ?>
    
    <div class="bp-tabs tabbable-line mv-main-tabs">
        <ul class="nav nav-tabs">
            
            <li class="nav-item">
                <a href="#maintab_<?php echo $this->uniqId; ?>" class="nav-link active" data-toggle="tab" aria-expanded="false">
                    <?php echo $this->mainTabName; ?>
                </a>
            </li>
            
            <?php
            if ($isTopTabRenderShow) {
                $t = 1;
                foreach (Mdform::$topTabRenderShow as $topTabName => $topTabContent) {
            ?>
            <li class="nav-item">
                <a href="#toptab_<?php echo $this->uniqId.'_'.$t; ?>" class="nav-link" data-toggle="tab" aria-expanded="false">
                    <?php echo $topTabName; ?>
                </a>
            </li>
            <?php
                $t ++;
                }
            }
            
            if ($isComponentRenderType) {
            ?>
            <li class="nav-item">
                <a href="#relationtab_<?php echo $this->uniqId; ?>" class="nav-link" data-toggle="tab" aria-expanded="false">Холбоос</a>
            </li>
            <?php
            }
            
            if (isset($this->addonTabs['tabStart']) && $this->addonTabs['tabStart']) {
                echo $this->addonTabs['tabStart'];
            }
            ?>
        </ul>
        
        <div class="tab-content">
            
            <div class="tab-pane active" id="maintab_<?php echo $this->uniqId; ?>">
                <div class="row">
                    <?php echo $this->form; ?>
                </div>
            </div>
            
            <?php
            if ($isTopTabRenderShow) {
                $t = 1;
                foreach (Mdform::$topTabRenderShow as $topTabName => $topTabContent) {
            ?>
            <div class="tab-pane" id="toptab_<?php echo $this->uniqId.'_'.$t; ?>">
                <?php echo implode('', $topTabContent); ?>
            </div>
            <?php
                $t ++;
                }
            }
            
            if ($isComponentRenderType) {
            ?>
            <div class="tab-pane" id="relationtab_<?php echo $this->uniqId; ?>">
                <?php echo $this->recordMapRender; ?>
            </div>
            <?php
            }

            if (isset($this->addonTabs['tabEnd']) && $this->addonTabs['tabEnd']) {
                echo $this->addonTabs['tabEnd'];
            }
            ?>
        </div>
    </div>
            
    <?php    
    } elseif (!$isMainForm && $isTopTabRenderShow) {
        
        foreach (Mdform::$topTabRenderShow as $topTabName => $topTabContent) {
            echo implode('', $topTabContent);
        }
        
    } else {
        
        echo $this->form; 
        echo $this->recordMapRender;
    }
    
    echo Form::hidden(array('name' => Mdform::$mvPathPrefix.'kpiMainIndicatorId'.Mdform::$mvPathSuffix, 'value' => $this->indicatorId));
    echo Form::hidden(array('name' => Mdform::$mvPathPrefix.'kpiStructureIndicatorId'.Mdform::$mvPathSuffix, 'value' => $this->structureIndicatorId));
    echo Form::hidden(array('name' => Mdform::$mvPathPrefix.'kpiTblId'.Mdform::$mvPathSuffix, 'value' => Mdform::$firstTplId));
    echo Form::hidden(array('name' => Mdform::$mvPathPrefix.'kpiTblIdField'.Mdform::$mvPathSuffix, 'value' => Mdform::$inputId));
    echo Form::hidden(array('name' => Mdform::$mvPathPrefix.'kpiDataTblName'.Mdform::$mvPathSuffix, 'value' => $this->dataTableName));
    echo Form::hidden(array('name' => Mdform::$mvPathPrefix.'kpiNamePattern'.Mdform::$mvPathSuffix, 'value' => $this->namePattern));
    echo Form::hidden(array('name' => Mdform::$mvPathPrefix.'kpiCrudIndicatorId'.Mdform::$mvPathSuffix, 'value' => $this->crudIndicatorId));
    echo Form::hidden(array('name' => Mdform::$mvPathPrefix.'kpiActionType'.Mdform::$mvPathSuffix, 'value' => $this->actionType));
    echo $this->standardHiddenFields;
    echo implode('', Mdform::$headerHiddenControl);
    ?>
</div>
</div>
<?php
if (issetParam($this->renderComponentsBanner) === '1') {
        echo '</div>';
    echo '</div>';
} 
if (Mdform::$addRowsTemplate) {
    $addRowsTemplate = implode('', Mdform::$addRowsTemplate);
    $addRowsTemplate = str_replace('type="text/template"', 'type="text/template" data-uniqid="'.$this->uniqId.'"', $addRowsTemplate);
    echo $addRowsTemplate;
}
?>
<script type="text/javascript">
var $kpiTmp_<?php echo $this->uniqId; ?> = $('#kpi-<?php echo $this->uniqId; ?>');
var bp_window_<?php echo $this->uniqId; ?> = $kpiTmp_<?php echo $this->uniqId; ?>;
var isEditMode_<?php echo $this->uniqId; ?> = <?php echo ((Mdform::$firstTplId) ? 'true' : 'false'); ?>;
var $aggregate_<?php echo $this->uniqId; ?> = bp_window_<?php echo $this->uniqId; ?>.find('.kpi-dtl-table:not(.bprocess-table-subdtl, [data-pager="true"]) > thead > tr > th[data-aggregate]:not([data-aggregate=""])');
var textEditorDefaultStyle = "<?php echo html_entity_decode(Config::getFromCache('textEditorDefaultStyle'), ENT_QUOTES, 'UTF-8'); ?>";

if ($kpiTmp_<?php echo $this->uniqId; ?>.find("th[data-merge-cell='true']:eq(0)").length) {
    $kpiTmp_<?php echo $this->uniqId; ?>.find("table > thead:has(th[data-merge-cell='true'])").each(function() {
        $(this).TableSpan('horizontal');
    });
}

if ($kpiTmp_<?php echo $this->uniqId; ?>.find("td[data-merge-cell='true']:eq(0)").length) {
    $kpiTmp_<?php echo $this->uniqId; ?>.find("table > tbody:has(td[data-merge-cell='true'])").each(function() {
        $(this).TableSpan('verticalstatement').TableSpan('horizontalstatement');
    });
}

Core.initNumberInput($kpiTmp_<?php echo $this->uniqId; ?>);
Core.initLongInput($kpiTmp_<?php echo $this->uniqId; ?>);
Core.initAmountInput($kpiTmp_<?php echo $this->uniqId; ?>);
Core.initDateInput($kpiTmp_<?php echo $this->uniqId; ?>);
Core.initDateTimeInput($kpiTmp_<?php echo $this->uniqId; ?>);
Core.initSelect2($kpiTmp_<?php echo $this->uniqId; ?>);
Core.initUniform($kpiTmp_<?php echo $this->uniqId; ?>);
Core.initDateMinuteInput($kpiTmp_<?php echo $this->uniqId; ?>);
Core.initTimeInput($kpiTmp_<?php echo $this->uniqId; ?>);
Core.initTextareaAutoHeight($kpiTmp_<?php echo $this->uniqId; ?>);
Core.initRegexMaskInput($kpiTmp_<?php echo $this->uniqId; ?>);
Core.initTinymceEditor($kpiTmp_<?php echo $this->uniqId; ?>);
Core.initFieldSetCollapse($kpiTmp_<?php echo $this->uniqId; ?>);
Core.initIconPicker($kpiTmp_<?php echo $this->uniqId; ?>);
Core.initTimerInput($kpiTmp_<?php echo $this->uniqId; ?>);

if (typeof isKpiIndicatorScript === 'undefined') {
    $.cachedScript('<?php echo autoVersion('middleware/assets/js/addon/indicator.js'); ?>', {async: false});
}

function saveKpiIndicatorForm(elem, uniqId, indicatorId) {
    saveKpiIndicatorFormInit(elem, uniqId, indicatorId);
}

function runKpiIndicatorInternalQuery(elem, uniqId, indicatorId) {
    runKpiIndicatorInternalQueryInit(elem, uniqId, indicatorId);
}

function renderAddModeIndicatorTab(uniqId, indicatorId, type, elem) {
    renderAddModeIndicatorTabInit(uniqId, indicatorId, type, elem);
}

<?php echo $this->fullExp['varFnc']; ?>   

$(function() {
    
    $kpiTmp_<?php echo $this->uniqId; ?>.on('keyup paste cut', 'input.kpiDecimalInit', function(e){
        var code = e.keyCode || e.which;
        if (code == 9 || code == 13 || code == 27 || code == 37 || code == 38 || code == 39 || code == 40) return false;
        var $this = $(this);
        $this.next('input[type=hidden]').val($this.val().replace(/[,]/g, ''));
    });
    
    $kpiTmp_<?php echo $this->uniqId; ?>.on('keydown', 'input.kpiDecimalInit', function(e){
        var code = e.keyCode || e.which;
        if (code == 9 || code == 13 || code == 38 || code == 40) {
            var $this = $(this), $thisNext = $this.next('input[type=hidden]'), $thisVal = $this.val();
            if ($thisVal !== $thisNext.val()) {
                $thisNext.val($thisVal.replace(/[,]/g, ''));
            }
        }
    });
    
    $kpiTmp_<?php echo $this->uniqId; ?>.on('click', 'a[href^="#sectiongid"]', function (event) {
        event.preventDefault();
        var $this = $(this);

        if ($this.closest(".ui-dialog-content").length) {
            $('.ui-dialog-content').animate({
                scrollTop: $($this.attr('href')).offset().top - 20
            }, 500);            
        } else {
            $('html, body').animate({
                scrollTop: $($this.attr('href')).offset().top - 20
            }, 500);
        }
    });    
    
    $kpiTmp_<?php echo $this->uniqId; ?>.on('change', 'select.select2[data-out-param]:not([data-row-data])', function() {
        
        var $this = $(this), outParam = $this.attr('data-out-param');
        var $form = $this.closest('form');
        var _outParamSplit = outParam.split(',');
        
        for (var i = 0; i < _outParamSplit.length; i++) {
            
            var selfParam = _outParamSplit[i];
            var $cellSelect = $form.find("select[data-path='" + selfParam + "']");
            
            if ($cellSelect.length) {
                var inParam = $cellSelect.attr('data-in-param');
                var _inParamSplit = inParam.split(',');
                var isEmpty = false;
                var postCriteria = '';
                
                for (var p = 0; p < _inParamSplit.length; p++) {
                    
                    var inSelfParam = _inParamSplit[p];
                    var $inCellSelect = $form.find("select[data-path='" + inSelfParam + "']");
                    
                    if ($inCellSelect.length == 0 || ($inCellSelect.length && $inCellSelect.val() == '')) {
                        isEmpty = true;
                    } else if ($inCellSelect.length && $inCellSelect.val() != '') {
                        postCriteria += '&criteria' + (p + 1) + '=' + encodeURIComponent($inCellSelect.val());
                    }
                }
                
                if (isEmpty == false) {
                    
                    var lookupCriteria = $cellSelect.attr('data-live-search') + postCriteria;
                    
                    $.ajax({
                        type: 'post',
                        url: 'mdform/kpiLinkedCombo',
                        data: {lookupMetaDataId: $cellSelect.attr('data-metadataid'), lookupCriteria: lookupCriteria},
                        dataType: 'json',
                        async: false,
                        success: function (dataStr) {

                            $cellSelect.select2('val', '');
                            $cellSelect.select2('readonly', false).select2('enable');
                            $cellSelect.find('option:gt(0)').remove();
                    
                            $.each(dataStr.data, function (i, r) {
                                $cellSelect.append($("<option />").val(r[dataStr.id]).text(r[dataStr.name]).attr('data-row-data', r.rowData));
                            });
                            
                            if (dataStr.hasOwnProperty('data-name')) {
                                $cellSelect.attr('data-name', dataStr['data-name']);
                            }

                            Core.initSelect2($cellSelect);
                            Core.unblockUI();
                        },
                        error: function () { alert('Error'); Core.unblockUI(); }
                    });
                }
            }
        }
    });
    
    $kpiTmp_<?php echo $this->uniqId; ?>.on('change', "select.linked-combo, select[data-criteria-param], input.popupInit.linked-combo", function(e, isTrigger){
        var $this = $(this), attrToJson = '';

        if ((isTrigger === true && isTrigger !== 'linked-combo') || ($this.hasClass('linked-combo-worked') && isTrigger === 'EDIT')) {
            return;
        }
        
        if (typeof $this.attr('data-criteria-param') !== 'undefined' && $this.attr('data-criteria-param') !== '' && $this.val() != '' && typeof e.val !== 'undefined') {
            return;
        }

        if (isTrigger === 'EDIT') {
            $this.addClass('linked-combo-worked');
        }

        var _outParam = $this.attr('data-out-param');     

        if ($this.closest('.kpi-dtl-table').length) {
            
            var $thisRow = $this.closest('.bp-detail-row');
            
            if (typeof _outParam !== 'undefined') {
                var _outParamSplit = _outParam.split('|');
            } else {
                var _outParamSplit = [$this.attr('data-path')];
            }

            for (var i = 0; i < _outParamSplit.length; i++) {
                var selfParam = _outParamSplit[i];

                $thisRow.find('[data-cell-path]').each(function () {

                    var $thisCell = $(this);
                    var $cellSelect = $thisCell.find('select[data-path="' + selfParam + '"]');

                    if ($cellSelect.length) {

                        var _inParams = '';

                        if (typeof $cellSelect.attr("data-in-param") !== 'undefined' && $cellSelect.attr("data-in-param") !== '') {
                            var _inParam = $cellSelect.attr("data-in-param");
                            var $thisChildRow = $thisCell.closest(".bp-detail-row");
                            var _inParamSplit = _inParam.split("|");
                            var _inLookupParam = $cellSelect.attr('data-in-lookup-param').split('|');

                            for (var j = 0; j < _inParamSplit.length; j++) {
                                $thisChildRow.find("[data-cell-path]").each(function () {
                                    var $thisChildCell = $(this);
                                    var _lastCombo = $thisChildCell.find("[data-path='" + _inParamSplit[j] + "']");
                                    if (_lastCombo.length && _lastCombo.val() !== '') {
                                        _inParams += _inLookupParam[j] + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                                    }
                                });
                            }
                        }

                        if (typeof $cellSelect.attr("data-criteria-param") !== 'undefined' && $cellSelect.attr("data-criteria-param") !== '') {
                            var _inParam = $cellSelect.attr("data-criteria-param");
                            var $thisChildRow = $thisCell.closest(".bp-detail-row");
                            var _inParamSplit = _inParam.split("|");

                            for (var j = 0; j < _inParamSplit.length; j++) {
                                var fieldPathArr = _inParamSplit[j].split("@");
                                var fieldPath = fieldPathArr[0];
                                var inputPath = fieldPathArr[1];

                                $thisChildRow.find("[data-cell-path]").each(function () {
                                    var $thisChildCell = $(this);
                                    var _lastCombo = $thisChildCell.find("[data-path='" + fieldPath + "']");
                                    if (_lastCombo.length && _lastCombo.val() !== '') {
                                        _inParams += inputPath + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                                    }
                                });
                            }
                        }

                        if (_inParams !== '') {
                            
                            attrToJson = JSON.parse($cellSelect.attr('data-row-data'));
                            
                            $.ajax({
                                type: 'post',
                                url: 'mdform/kpiIndicatorLinkedCombo',
                                data: {
                                    processMetaDataId: '<?php echo $this->indicatorId; ?>', 
                                    selfParam: selfParam, 
                                    inputParams: _inParams, 
                                    jsonAttr: attrToJson,
                                    requestType: 'linkedCombo'
                                },
                                dataType: 'json',
                                async: false,
                                beforeSend: function () {
                                    Core.blockUI({animate: true});
                                },
                                success: function (dataStr) {

                                    var isWithPopupCombo = false;

                                    if ($cellSelect.hasClass('select2')) {
                                        $cellSelect.select2('val', '');
                                        $cellSelect.select2('readonly', false).select2('enable');

                                        if ($cellSelect.hasClass('bp-field-with-popup-combo')) {
                                            var $withPopupComboParent = $cellSelect.closest('.input-group');
                                            $withPopupComboParent.find('button').removeAttr('disabled');
                                            isWithPopupCombo = true;
                                        }
                                    } else {
                                        $cellSelect.val('');
                                        $cellSelect.removeAttr('disabled readonly');
                                        $cellSelect.parent().find('input, button').removeAttr('disabled readonly');
                                    }

                                    if ($cellSelect.is('[multiple]')) {
                                        $cellSelect.find('option').remove();
                                    } else {
                                        $cellSelect.find('option:gt(0)').remove();
                                    }

                                    var comboData = (selfParam in dataStr) ? dataStr[selfParam] : dataStr;
                                    $cellSelect.addClass("data-combo-set");

                                    $.each(comboData, function () {
                                        if (isEditMode_<?php echo $this->uniqId; ?>) {
                                            if ($cellSelect.attr("data-edit-value") == this.META_VALUE_ID) {
                                                $cellSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME).attr("selected", "selected").attr("data-row-data", JSON.stringify(this.ROW_DATA)));
                                            } else {
                                                $cellSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME).attr("data-row-data", JSON.stringify(this.ROW_DATA)));
                                            }
                                        } else
                                            $cellSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME).attr("data-row-data", JSON.stringify(this.ROW_DATA)));
                                    });

                                    if (typeof $this.attr('data-criteria-param') === 'undefined') {
                                        $cellSelect.trigger('change');
                                    }

                                    if (isWithPopupCombo) {
                                        var count = $cellSelect.find('option:selected').length;
                                        if (count > 0) {
                                            $withPopupComboParent.find('button:eq(0)').text(count);
                                            $withPopupComboParent.find('button:eq(1)').show();
                                        } else {
                                            $withPopupComboParent.find('button:eq(0)').text('..');
                                            $withPopupComboParent.find('button:eq(1)').hide();
                                        }
                                    }

                                    Core.initSelect2($cellSelect);
                                    Core.unblockUI();
                                },
                                error: function () {
                                    alert("Error");
                                }
                            });
                        } else {
                            $cellSelect.select2('val', '');
                            $cellSelect.select2('disable');

                            if ($cellSelect.is('[multiple]')) {
                                $cellSelect.find('option').remove();
                            } else {
                                $cellSelect.find('option:gt(0)').remove();
                            }

                            Core.initSelect2($cellSelect);

                            if ($cellSelect.hasClass('bp-field-with-popup-combo')) {
                                var $parent = $cellSelect.closest('.input-group');
                                $parent.find('button:not(.removebtn)').attr('disabled', 'disabled').text('..');
                                $parent.find('button.removebtn').hide();
                            }
                        }
                    }
                });
            }
            
        } else {

            if ($this.hasAttr('data-criteria-param') && $this.attr('data-criteria-param') != '') {
                return;
            }

            var _outParamSplit = _outParam.split('|');

            try {
                for (var i = 0; i < _outParamSplit.length; i++) {

                    var selfParam = _outParamSplit[i];
                    var _inParams = '';
                    var $cellSelect = $kpiTmp_<?php echo $this->uniqId; ?>.find("select[data-path='" + selfParam + "'], input[type='radio'][data-path='" + selfParam + "']");

                    if ($cellSelect.length === 0) {
                        var $cellInp = $kpiTmp_<?php echo $this->uniqId; ?>.find("input[data-path='" + selfParam + "']");

                        if ($this.val().length > 0 && $cellInp.length > 0) {
                            if ($cellInp.closest('.kpi-dtl-table').length && $cellInp.attr('data-edit-value') === undefined) {
                                if (isTrigger === undefined) {
                                    $cellInp = $cellInp.closest(".kpi-dtl-table").find(".bp-detail-row").find("input[data-path='" + selfParam + "']");
                                } else {
                                    $cellInp = $cellInp.closest(".kpi-dtl-table").find(".bp-detail-row:last-child").find("input[data-path='" + selfParam + "']");
                                }
                            }
                            $cellInp.closest(".meta-autocomplete-wrap").find("input").removeAttr("readonly disabled");
                            $cellInp.parent().find("button").removeAttr("disabled");
                        }

                    } else {                    

                        var isLinkedRadio = false;

                        if ($cellSelect.closest('.kpi-dtl-table').length && $cellSelect.attr("data-edit-value") === undefined) {
                            if (isTrigger === undefined) {
                                $cellSelect = $cellSelect.closest(".kpi-dtl-table").find(".bp-detail-row").find("select[data-path='" + selfParam + "']");
                            } else {
                                $cellSelect = $cellSelect.closest(".kpi-dtl-table").find(".bp-detail-row:last-child").find("select[data-path='" + selfParam + "']");
                            }
                        }

                        if ($cellSelect.is(':radio')) {

                            var $radioParent = $cellSelect.closest('.radio-list');
                            var _inParam = $radioParent.attr('data-in-param');
                            var _inParamSplit = _inParam.split('|');

                            for (var j = 0; j < _inParamSplit.length; j++) {
                                var _lastCombo = $kpiTmp_<?php echo $this->uniqId; ?>.find("[data-path='" + _inParamSplit[j] + "']");
                                if (_lastCombo.length && _lastCombo.val() !== '') {
                                    _inParams += _inParamSplit[j] + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                                }
                            }

                            isLinkedRadio = true;

                        } else {

                            if ($cellSelect.length && typeof $cellSelect.attr("data-in-param") !== 'undefined' && $cellSelect.attr("data-in-param") !== '') {
                                var _inParam = $cellSelect.attr('data-in-param');
                                var _inLookupParam = $cellSelect.attr('data-in-lookup-param').split('|');
                                var _inParamSplit = _inParam.split('|');

                                for (var j = 0; j < _inParamSplit.length; j++) {
                                    var _lastCombo = $kpiTmp_<?php echo $this->uniqId; ?>.find("[data-path='" + _inParamSplit[j] + "']");
                                    if (_lastCombo.length && _lastCombo.val() !== '') {
                                        if (_lastCombo.is('[multiple]')) {
                                            _inParams += _lastCombo.find(':selected').map(function(i, el) { return _inLookupParam[j] + '[]=' + $(el).val() + '&'; }).get().join('');
                                        } else {
                                            _inParams += _inLookupParam[j] + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                                        }
                                    }
                                }
                            }
                        }

                        if (_inParams !== '') {
                        
                            attrToJson = JSON.parse($cellSelect.attr('data-row-data'));
                            
                            $.ajax({
                                type: 'post',
                                url: 'mdform/kpiIndicatorLinkedCombo',
                                data: {
                                    processMetaDataId: '<?php echo $this->indicatorId; ?>', 
                                    selfParam: selfParam, 
                                    inputParams: _inParams, 
                                    jsonAttr: attrToJson, 
                                    requestType: 'linkedCombo'
                                },
                                dataType: 'json',
                                async: false,
                                beforeSend: function () {
                                    Core.blockUI({animate: true});
                                },
                                success: function (dataStr) {

                                    var comboData = (selfParam in dataStr) ? dataStr[selfParam] : dataStr;

                                    if (isLinkedRadio == false) {

                                        var isWithPopupCombo = false;

                                        if ($cellSelect.hasClass('select2')) {
                                            $cellSelect.select2('val', '');
                                            $cellSelect.select2('readonly', false).select2('enable');

                                            if ($cellSelect.hasClass('bp-field-with-popup-combo')) {
                                                var $withPopupComboParent = $cellSelect.closest('.input-group');
                                                $withPopupComboParent.find('button').removeAttr('disabled');
                                                isWithPopupCombo = true;
                                            }
                                        } else {
                                            $cellSelect.val('');
                                            $cellSelect.removeAttr('disabled readonly');
                                        }

                                        if ($cellSelect.is('[multiple]')) {
                                            $cellSelect.find('option').remove();
                                        } else {
                                            $cellSelect.find('option:gt(0)').remove();
                                        }

                                        $cellSelect.addClass('data-combo-set');
                                        
                                        if (comboData.hasOwnProperty(0)) {

                                            if (isEditMode_<?php echo $this->uniqId; ?>) {
                                                $cellSelect.each(function(){
                                                    var _thisSelect = $(this);
                                                    $.each(comboData, function () {
                                                        if (_thisSelect.attr("data-edit-value") == this.META_VALUE_ID) {
                                                            _thisSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME).attr("selected", "selected").attr("data-row-data", JSON.stringify(this.ROW_DATA)));
                                                        } else {
                                                            _thisSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME).attr("data-row-data", JSON.stringify(this.ROW_DATA)));
                                                        }
                                                    });
                                                });
                                            } else { 
                                                $.each(comboData, function () {
                                                    $cellSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME).attr("data-row-data", JSON.stringify(this.ROW_DATA)));
                                                });
                                            }
                                        }

                                        if (isWithPopupCombo) {
                                            var count = $cellSelect.find('option:selected').length;
                                            if (count > 0) {
                                                $withPopupComboParent.find('button:eq(0)').text(count);
                                                $withPopupComboParent.find('button:eq(1)').show();
                                            } else {
                                                $withPopupComboParent.find('button:eq(0)').text('..');
                                                $withPopupComboParent.find('button:eq(1)').hide();
                                            }
                                        }

                                        $cellSelect.trigger('change');
                                        Core.initSelect2($cellSelect);

                                    } else {

                                        var radioList = '';

                                        $.each(comboData, function () {
                                            radioList += '<label class="radio-inline">';
                                            radioList += '<input type="radio" name="param['+selfParam+']" data-path="'+selfParam+'" value="'+this.META_VALUE_ID+'"> '+this.META_VALUE_NAME;
                                            radioList += '</label>';
                                        });

                                        $radioParent.empty().append(radioList);

                                        Core.initUniform($radioParent);
                                    }

                                    Core.unblockUI();
                                },
                                error: function () { alert('Error'); Core.unblockUI(); }
                            });

                        } else {

                            if (isLinkedRadio == false) {
                                $cellSelect.select2('val', '');
                                $cellSelect.select2('disable');

                                if ($cellSelect.is('[multiple]')) {
                                    $cellSelect.find('option').remove();
                                } else {
                                    $cellSelect.find('option:gt(0)').remove();
                                }

                                Core.initSelect2($cellSelect);

                                if ($cellSelect.hasClass('bp-field-with-popup-combo')) {
                                    var $parent = $cellSelect.closest('.input-group');
                                    $parent.find('button:not(.removebtn)').attr('disabled', 'disabled').text('..');
                                    $parent.find('button.removebtn').hide();
                                }
                            }
                        }
                    }
                }
            } catch(err) {
                console.log('Комбоны хамаарал алдааны мэдээлэл: ' + err);
                Core.unblockUI();
            }
        }
    });
        
    $kpiTmp_<?php echo $this->uniqId; ?>.on('change', 'select.select2', function() {
        
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
                    if ($this.is('[multiple]')) {
                        var selectedValue = $this.find('option:selected');
                        if (selectedValue.length) {
                            var result = new Array();
                            selectedValue.each(function() {
                                result.push($(this).text());
                            });
                            descName = result.join(', ');
                        } 
                    } else {
                        descName = $this.find('option:selected').text();
                    }
                }
            }
            $descName.val(descName);
        }
    });
    
    $kpiTmp_<?php echo $this->uniqId; ?>.on('change', 'input.booleanInit', function() {
        var $this = $(this), $parent = $this.closest('.checker'), $hidden = $parent.find('input[type="hidden"]'), 
            checkName = $this.attr('name');
        
        $hidden.remove();
        
        if ($this.is(':checked')) {
            $parent.append('<input type="hidden" name="'+checkName+'" value="1">');
        } else {
            $parent.append('<input type="hidden" name="'+checkName+'" value="0">');
        }
    });
    
    $kpiTmp_<?php echo $this->uniqId; ?>.on('mvChange', 'select.select2', function() {
        
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
    
    $kpiTmp_<?php echo $this->uniqId; ?>.on('change', 'input.md-radio', function() {

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
    
    <?php echo $this->scripts; ?>
    
    bpFullScriptsWithoutEvent_<?php echo $this->uniqId; ?>();
    
    <?php echo $this->fullExp['event']; ?>  

    <?php echo $this->flowchartscripts; ?>
        
    $kpiTmp_<?php echo $this->uniqId; ?>.delegate('table.kpi-dtl-table > tbody > tr > td input.kpiDecimalInit:not([readonly], [disabled])', 'paste', function(e){
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
    
    $kpiTmp_<?php echo $this->uniqId; ?>.on('keydown', 'input[type="text"]', function(e) {
                    
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
    
    $kpiTmp_<?php echo $this->uniqId; ?>.find('input[data-auto-change="1"]').trigger('change');
    
    $kpiTmp_<?php echo $this->uniqId; ?>.on('change', ".kpi-dtl-table > .tbody > .bp-detail-row input[type='text']:visible", function(){
        var $this = $(this);
        if (typeof $this.attr('data-prevent-change') !== 'undefined') {
            return;
        }
        
        dtlAggregateFunction_<?php echo $this->uniqId; ?>();
    });   
    
    $kpiTmp_<?php echo $this->uniqId; ?>.on('click', '.bp-tabs > ul > li > a', function () {
        var $this = $(this), $target = $kpiTmp_<?php echo $this->uniqId; ?>.find($this.attr('href'));
        Core.initTextareaAutoHeight($target);
    });
    
    <?php
    if (Mdform::$isRowsReplacePath) {
    ?>
    $kpiTmp_<?php echo $this->uniqId; ?>.on('customEventHtmlClickToEdit', '.kpi-dtl-table[data-replace-path]:not([data-replace-path=""]) > .tbody > .bp-detail-row .texteditor_clicktoeditInit[contenteditable="true"]', function(){
        var groupPath = $(this).closest('table[data-replace-path]').attr('data-table-path');
        rowsDtlPathReplacer_<?php echo $this->uniqId; ?>(groupPath);
    }); 
    $kpiTmp_<?php echo $this->uniqId; ?>.on('focusout', '.kpi-dtl-table[data-replace-path]:not([data-replace-path=""]) > .tbody > .bp-detail-row .texteditor_clicktoeditInit[contenteditable="true"]', function(){
        var groupPath = $(this).closest('table[data-replace-path]').attr('data-table-path');
        rowsDtlPathReplacer_<?php echo $this->uniqId; ?>(groupPath);
    }); 
    $kpiTmp_<?php echo $this->uniqId; ?>.on('change', 'input:not(.popupInit, .bigdecimalInit), select, textarea', function() {
        eventDelay(function() {
            rowsDtlPathReplacer_<?php echo $this->uniqId; ?>();
        }, 100);
    });
    $kpiTmp_<?php echo $this->uniqId; ?>.on('change', 'input.popupInit', function() {
        eventDelay(function() {
            rowsDtlPathReplacer_<?php echo $this->uniqId; ?>();
        }, 600);
    });
    $kpiTmp_<?php echo $this->uniqId; ?>.on('change', 'input.bigdecimalInit', function() {
        rowsDtlPathReplacer_<?php echo $this->uniqId; ?>();
    });
    <?php
    }
    ?>
        
    //bpDetailFreezeAll($kpiTmp_<?php echo $this->uniqId; ?>);    
    
    dtlAggregateFunction_<?php echo $this->uniqId; ?>();
    
    Core.initTextareaAutoHeight(bp_window_<?php echo $this->uniqId; ?>);
    
    if ($(".mv-checklist-section-sidebar").length) {
        setTimeout(function() {
            $(".mv-checklist-section-sidebar").stick_in_parent({
                offset_top: 55
            });
        }, 300);    
    }       
});

function rowsDtlPathReplacer_<?php echo $this->uniqId; ?>(groupPath) {
    <?php
    if (Mdform::$isRowsReplacePath) {
    ?>
    if (typeof groupPath != 'undefined') {
        var $dtlTbl = $kpiTmp_<?php echo $this->uniqId; ?>.find('table[data-replace-path][data-table-path="'+groupPath+'"]:not([data-replace-path=""])');
    } else {
        var $dtlTbl = $kpiTmp_<?php echo $this->uniqId; ?>.find('table[data-replace-path]:not([data-replace-path=""])');
    }
    
    if ($dtlTbl.length) {
        
        var $header = $kpiTmp_<?php echo $this->uniqId; ?>.find('.kpi-hdr-table').find('[data-path]'), headerData = {};
        var $detail = $kpiTmp_<?php echo $this->uniqId; ?>.find('.kpi-dtl-table'), dtlData = {};

        $header.each(function() {
            var $headerElem = $(this), headerVal = $headerElem.val();
            if (headerVal != '' && headerVal != null) {
                if ($headerElem.hasClass('select2')) {
                    headerVal = $("option:selected", $headerElem).text();
                } 
                headerData[$headerElem.attr('data-col-path')] = headerVal;
            }
        });

        $detail.each(function() {
            var $selftr = $(this);
            var $dtltr = $selftr.find('> tbody > tr');
            dtlData[$selftr.attr('data-table-path')] = [];
            
            if ($dtltr.length) {
                $dtltr.each(function() {
                    var $headerTemp = $(this).find('[data-path]'), headerDataTemp = {};
                    
                    $headerTemp.each(function() {
                        var $headerElem = $(this), headerVal = $headerElem.val();
                        if (headerVal != '' && headerVal != null) {
                            if ($headerElem.hasClass('select2')) {
                                headerVal = $("option:selected", $headerElem).text();
                            } 
                            if ($headerElem.attr('data-col-path')) {
                                headerDataTemp[$headerElem.attr('data-col-path')] = headerVal;
                            }
                        }
                    });
                    dtlData[$selftr.attr('data-table-path')].push(headerDataTemp);
                });
            }
        });
        
        $dtlTbl.each(function() {
            var $tbl = $(this), $tblRows = $tbl.find('> tbody > tr');
            
            if ($tblRows.length) {
                var replacePath = $tbl.attr('data-replace-path'), replacePathArr = replacePath.split('path');
                var startTag = replacePathArr[0], endTag = replacePathArr[1];
                
                $tblRows.each(function() {
                    var $row = $(this), $htmlClickToEdit = $row.find('.texteditor_clicktoeditInit');
                    if ($htmlClickToEdit.length) {
                        
                        $htmlClickToEdit.each(function() {
                            
                            var $control = $(this), controlHtml = $control.html();
                            
                            if (controlHtml != '' && controlHtml != null) {
                                
                                for (var c in headerData) {
                                    var cVal = headerData[c];
                                    var searchMask = startTag + c + endTag;
                                    
                                    if (controlHtml.indexOf('data-replace-tag="'+c+'"') !== -1) {
                                        var $html = $('<div />', {html: controlHtml});
                                        $html.find('[data-replace-tag="'+c+'"]').html(cVal);
                                        controlHtml = $html.html();
                                    }
                                    
                                    controlHtml = str_ireplace(searchMask, '<span data-replace-tag="'+c+'" class="mv_html_clicktoedit_tag" style="background-color:#d7d7d7;font-weight:bold;padding:2px;">' + cVal + '</span>', controlHtml);
                                }
                                
                                for (var c in dtlData) {
                                    var cVal = dtlData[c];                                    
                                    
                                    if (controlHtml.indexOf('id="'+c+'"') !== -1 && Object.keys(cVal).length) {
                                        var $html = $('<div />', {html: controlHtml});
                                        $html.find('#'+c+' > tbody > tr:eq(0) > td').show();
                                        var $getTableTr = $html.find('#'+c+' > tbody > tr:eq(0)').html();
                                        var $getTableTrTemp = $getTableTr;
                                        $html.find('#'+c+' > tbody > tr:eq(0) > td').hide();
                                        var $getTableTrTemp2 = $html.find('#'+c+' > tbody > tr:eq(0)').html();
                                        var $getTableTrHtml = '';
                                        $html.find('#'+c+' > tbody').empty();
                                        
                                        for (var l = 0; l < cVal.length; l++) {
                                            $getTableTr = $getTableTrTemp;
                                            for (var cc in cVal[l]) {
                                                var searchMask = startTag + c + '.' + cc + endTag;
                                                $getTableTr = str_ireplace(searchMask, '<span data-replace-tag="'+c+'" class="mv_html_clicktoedit_tag" style="background-color:#d7d7d7;font-weight:bold;padding:2px;">' + cVal[l][cc] + '</span>', $getTableTr);
                                            }
                                            $getTableTrHtml += '<tr>'+$getTableTr+'</tr>';
                                        }
                                        
                                        $html.find('#'+c+' > tbody').append('<tr>'+$getTableTrTemp2+'</tr>'+$getTableTrHtml);
                                        controlHtml = $html.html();
                                    }
                                }
                                
                                $control.html(controlHtml);
                                $control.next('textarea').val(controlHtml);
                            }
                        });
                    }
                });
            }
        });
    }
    <?php
    }
    ?>
}
        
function dtlAggregateFunction_<?php echo $this->uniqId; ?>() {
        
    if ($aggregate_<?php echo $this->uniqId; ?>.length) {
        var $el = $aggregate_<?php echo $this->uniqId; ?>, $len = $el.length, $i = 0;

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
    
function kpiIndicatorBeforeSave_<?php echo $this->uniqId; ?>(thisButton) {
    PNotify.removeAll();

    <?php echo issetParam($this->fullExp['beforeSave']); ?> 

    return true;
}
function kpiIndicatorAfterSave_<?php echo $this->uniqId; ?>(thisButton, responseStatus, responseData) {
        
    <?php echo issetParam($this->fullExp['afterSave']); ?> 

    return true;
}
function bpFullScriptsWithoutEvent_<?php echo $this->uniqId; ?>(elem, groupPath, isAddMulti, isLastRow, multiMode) {
    var element = typeof elem === 'undefined' ? 'open' : elem; 
    var groupPath = typeof groupPath === 'undefined' ? '' : groupPath; 
    var isAddMulti = typeof isAddMulti === 'undefined' ? false : isAddMulti; 
    var isLastRow = typeof isLastRow === 'undefined' ? false : isLastRow; 
    var multiMode = typeof multiMode === 'undefined' ? '' : multiMode; 
    
    <?php echo issetParam($this->fullExp['withoutEvent']); ?> 
}
</script>