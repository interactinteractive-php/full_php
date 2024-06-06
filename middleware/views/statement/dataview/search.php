<?php 
if (isset($this->isSearchForm) && $this->isSearchForm) {
?>
<div class="col-md-12 xs-form main-action-meta" id="dataview-statement-search-<?php echo $this->metaDataId; ?>" data-meta-type="statement" data-process-id="<?php echo $this->metaDataId; ?>">
    <?php
    if (!isset($this->isBlank)) {
    ?>
    <fieldset class="collapsible mt10 mb10">
        <legend><?php echo $this->lang->line('filter'); ?></legend>
        <form class="form-horizontal" method="post" id="dataview-search-form">
            <div class="row mr0">      
                <?php
                
                if (isset($this->dataViewSearchData['visible'])) {
                    
                    $detect_md_12 = 0;
                    $fieldCount = count($this->dataViewSearchData['visible']);
                    
                    if ($fieldCount > 9) {
                        $array = array_chunk($this->dataViewSearchData['visible'], 4);
                    } else {
                        $array = array_chunk($this->dataViewSearchData['visible'], 3);
                    }
                    
                    foreach ($array as $dataViewSearchData) {
                        
                        $detect_md_12++;
                        echo '<div class="col-md-4">';
                        
                        foreach ($dataViewSearchData as $param) {
                ?>
                    <div class="form-group row fom-row">
                        <?php 
                        if (!array_key_exists(0, $param)) {
                        
                            $labelArr = array(
                                'text' => $this->lang->line($param['META_DATA_NAME']),
                                'for' => 'param['.$param['META_DATA_CODE'].']',
                                'class' => 'col-form-label col-md-4'
                            );
                            if ($param['IS_REQUIRED'] == '1') {
                                $labelArr['required'] = 'required'; 
                            }
                            if ($param['LOOKUP_META_DATA_ID'] != '' && $param['LOOKUP_TYPE'] == 'combo' && $param['CHOOSE_TYPE'] != 'singlealways') {
                                $param['CHOOSE_TYPE'] = 'multi';
                            }
                            echo Form::label($labelArr); 
                            
                            $ignoreDisable = '';
                            if (isset($_POST['dvMap']) && !isset($_POST['dvMap'][strtolower($param['META_DATA_CODE'])])) {
                                $ignoreDisable = ' ignore-disable-control';
                            }
                        ?>
                        <div class="col-md-8 pr0<?php echo $ignoreDisable; ?>">
                            <?php
                            if (Config::getFromCache('CONFIG_ACCOUNT_SEGMENT')) {
                                
                                $lowerPath = strtolower($param['META_DATA_CODE']);
                                
                                if (in_array($lowerPath, Mdgl::$segmentAccountPath)) {
                            ?>
                            <div class="input-group input-group-criteria">
                                <?php
                                echo Mdcommon::criteriaCondidion(
                                    $param,     
                                    Mdwebservice::renderParamControl($this->dataViewId, $param, 'param['.$param['META_DATA_CODE'].']', $param['META_DATA_CODE'], $this->fillParamData)
                                );
                                ?>
                                <span class="input-group-btn">
                                    <button type="button" class="btn default btn-bordered" tabindex="-1" title="Dimension сонгох" onclick="accountSegmentCriteria(this);"><i class="fa fa-navicon"></i></button>
                                </span>
                            </div>
                            <?php
                                } else {
                                    echo Mdcommon::criteriaCondidion(
                                        $param,     
                                        Mdwebservice::renderParamControl($this->dataViewId, $param, 'param['.$param['META_DATA_CODE'].']', $param['META_DATA_CODE'], $this->fillParamData)
                                    );
                                }
                            } else {
                                echo Mdcommon::criteriaCondidion(
                                    $param,     
                                    Mdwebservice::renderParamControl($this->dataViewId, $param, 'param['.$param['META_DATA_CODE'].']', $param['META_DATA_CODE'], $this->fillParamData)
                                );
                            }
                            ?>  
                        </div>
                        <?php
                        } else {
                            
                            $metaDataCode = $isRequired = '';
                            
                            if (array_key_exists(0, $param)) {
                                $metaDataCode = $param[0]['META_DATA_CODE'];
                                $isRequired = $param[0]['IS_REQUIRED'];
                            } elseif (array_key_exists(1, $param)) {
                                $metaDataCode = $param[1]['META_DATA_CODE'];
                                $isRequired = $param[1]['IS_REQUIRED'];
                            }
                            
                            $labelArr = array(
                                'text' => $this->lang->eitherOne('date_'.$this->metaDataId, 'date'),
                                'for' => 'param['.$metaDataCode.']',
                                'class' => 'col-form-label col-md-4'
                            );
                            if ($isRequired == '1') {
                                $labelArr['required'] = 'required'; 
                            }
                            echo Form::label($labelArr); 
                            
                            echo '<div class="col-md-8 pr0 date-float-left">';
                            if (array_key_exists(0, $param)) {
                                echo Mdwebservice::renderParamControl($this->dataViewId, $param[0], 'param['.$param[0]['META_DATA_CODE'].']', $param[0]['META_DATA_CODE'], $this->fillParamData); 
                            }
                            if (array_key_exists(1, $param)) {
                                echo html_tag('div', array('class' => 'float-left pt5 pb5'), '<i class="icon-dash font-size-12"></i>');
                                echo Mdwebservice::renderParamControl($this->dataViewId, $param[1], 'param['.$param[1]['META_DATA_CODE'].']', $param[1]['META_DATA_CODE'], $this->fillParamData); 
                            }
                            echo '</div>';
                        }
                        ?>
                    </div>    
            <?php
                    }
                    echo '</div>';
                    
                    if ($detect_md_12 === 3) {
                        $detect_md_12 = 0;
                        echo '<div class="clearfix w-100"></div>';
                    }
                }
            }
            
            if (isset($this->dataViewSearchData['hidden'])) {
                foreach ($this->dataViewSearchData['hidden'] as $hidden) {
                    echo Mdwebservice::renderParamControl($this->dataViewId, $hidden, 'param['.$hidden['META_DATA_CODE'].']', $hidden['META_DATA_CODE'], $this->fillParamData); 
                }
            }
            ?>
            </div>
            <div class="row st-btns-row">
                <div class="col-12 text-right">
                    <?php 
                    if (isset($this->isChooseReportFrame) && $this->isChooseReportFrame) {
                        echo Form::select(
                            array(
                                'class' => 'form-control form-control-sm select2 mr20 rp-reportframe-combo', 
                                'style' => 'width: 140px', 
                                'data' => array(
                                    array(
                                        'id' => 'native', 
                                        'text' => 'Native'
                                    ), 
                                    array(
                                        'id' => 'devexpress', 
                                        'text' => 'DevExpress'
                                    )
                                ), 
                                'op_value' => 'id', 
                                'op_text' => 'text', 
                                'text' => 'notext', 
                                'value' => 'devexpress'
                            )
                        );
                    }
                    
                    if (isset($this->reportLayoutTemplateList)) {
                        echo Form::select(
                            array(
                                'class' => 'form-control form-control-sm select2 mr20 rp-template-combo', 
                                'style' => 'width: 200px', 
                                'text' => '- Загвар сонгох -', 
                                'data' => $this->reportLayoutTemplateList, 
                                'op_value' => 'TRG_META_DATA_ID', 
                                'op_text' => 'REPORT_NAME'
                            )
                        );
                    }
                    
                    if ($this->row['PROCESS_META_DATA_ID'] != '') {
                        echo Form::button(
                            array(
                                'class' => 'btn btn-sm btn-circle blue-madison dataview-statement-calculate-btn', 
                                'value' => '<i class="fa fa-calculator"></i> '.$this->lang->line('calculate')
                            )
                        ) . ' '; 
                    }
                    echo Form::button(
                        array(
                            'class' => 'btn btn-sm btn-circle blue-madison dataview-statement-filter-btn', 
                            'data-layout-id' => issetParam($this->reportLayoutId), 
                            'value' => '<i class="fa fa-search"></i> '.$this->lang->line('do_filter')
                        )
                    ) . ' ';  
                    if ($this->row['IS_SHOW_DV_BTN'] == '1') {
                        echo Form::button(
                            array(
                                'class' => 'btn btn-sm btn-circle blue-madison dataview-statement-dv-btn', 
                                'value' => $this->lang->line('list_view'), 
                                'data-dv-id' => $this->row['DATA_VIEW_ID']
                            )
                        ) . ' '; 
                    } 
                    if ($this->row['DASHBOARD_META_ID'] != '') {
                        echo Form::button(
                            array(
                                'class' => 'btn btn-sm btn-circle blue-madison dataview-statement-dashboard-btn', 
                                'value' => '<i class="fa fa-dashboard"></i> Дашбоард',
                                'onclick' => "callMetaTypeStatement('".$this->row['DASHBOARD_META_ID']."', '".$this->metaDataId."');"
                            )
                        ) . ' '; 
                    }
                    if (isset($this->isUserGroupingButton) && $this->isUserGroupingButton && !isset($this->reportLayoutId)) {
                        echo Form::button(
                            array(
                                'class' => 'btn btn-sm btn-circle purple-plum dataview-statement-grouping-btn', 
                                'value' => '<i class="fa fa-align-left"></i> '.Lang::lineDefault('1494916800801_1692694188895187', 'Бүлэглэх')
                            )
                        ) . ' '; 
                    }
                    echo Form::button(
                        array(
                            'class' => 'btn btn-sm btn-circle default dataview-statement-filter-reset-btn', 
                            'value' => $this->lang->line('clear_btn')
                        ),
                        $this->isClearButton     
                    ); 
                    ?>
                </div>    
            </div>  
            <?php 
            echo Form::hidden(array('name' => 'dataViewId', 'value' => $this->dataViewId)); 
            echo Form::hidden(array('name' => 'statementId', 'value' => $this->metaDataId)); 
            echo Form::hidden(array('name' => 'expandDataViewId', 'value' => $this->row['GROUP_DATA_VIEW_ID'])); 
            echo Form::hidden(array('id' => 'processMetaId', 'value' => $this->row['PROCESS_META_DATA_ID'])); 
            ?>
            <div id="dialog-st-grouping-config-<?php echo $this->metaDataId; ?>"></div>
        </form>        
    </fieldset>
    
    <?php
    } else {
    ?>
    <form class="form-horizontal d-none" method="post" id="dataview-search-form">
        <?php 
        echo Form::button(
            array(
                'class' => 'btn btn-sm btn-circle blue-madison dataview-statement-filter-btn', 
                'data-layout-id' => issetParam($this->reportLayoutId), 
                'value' => '<i class="fa fa-search"></i> Шүүх'
            )
        );
        echo Form::hidden(array('name' => 'dataViewId', 'value' => $this->dataViewId)); 
        echo Form::hidden(array('name' => 'statementId', 'value' => $this->metaDataId)); 
        echo Form::hidden(array('id' => 'processMetaId', 'value' => $this->row['PROCESS_META_DATA_ID'])); 
        ?>
    </form>
    <?php
    }
    ?>
</div>
<div class="clearfix w-100"></div>

<script type="text/javascript">
var dataview_statement_search_<?php echo $this->metaDataId.$this->dataViewId; ?> = $("div#dataview-statement-search-<?php echo $this->metaDataId; ?>"); 
var $st_search_<?php echo $this->metaDataId; ?> = dataview_statement_search_<?php echo $this->metaDataId.$this->dataViewId; ?>.find('#dataview-search-form');
var statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?> = false; 
var statement_mergecell_<?php echo $this->metaDataId.$this->dataViewId; ?> = false; 

$(function() {
    
    $st_search_<?php echo $this->metaDataId; ?>.on('keyup paste cut', 'input.bigdecimalInit:visible', function(e){
        var code = e.keyCode || e.which;
        if (code == 9 || code == 27 || code == 37 || code == 38 || code == 39 || code == 40) return false;
        var $this = $(this);
        var $thisVal = $this.val() != '' ? $this.val().replace(/[,]/g, '') : '';
        $this.next("input[type=hidden]").val($thisVal);
    });
        
    $st_search_<?php echo $this->metaDataId; ?>.on('keydown', "input[type='text'][class]:visible, input[type='checkbox']:not([data-isdisabled])", function(e){
        var keyCode = (e.keyCode ? e.keyCode : e.which);
        var $this = $(this);
        
        if (keyCode === 13) { /* enter */
            
            var $form = $this.closest('form');
            var $formInput = $form.find('input:visible:not([data-isdisabled], [readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled, input.meta-name-autocomplete, .select2-focusser)');
            var $cellIndex = $formInput.index($this);
            
            if ($formInput.length == ($cellIndex + 1)) {
                $formInput.eq(0).focus().select();
            } else {
                if ($formInput.eq($cellIndex + 1).length) {
                    $formInput.eq($cellIndex + 1).focus().select();
                }
            }
            
            e.preventDefault();
        }
    });    
    
    <?php
    if (isset($this->isSearchFormDisabled) && $this->isSearchFormDisabled) {
    ?>
    setTimeout(function(){
        $st_search_<?php echo $this->metaDataId; ?>.find('input[type=text]:not([data-path="filterStartDate"],[data-path="filterEndDate"],[data-path="filterstartdate"],[data-path="filterenddate"],[data-path="startDate"],[data-path="endDate"],[data-path="startdate"],[data-path="enddate"]), textarea, input[type=checkbox]').attr({'readonly': 'readonly', 'tabindex': '-1'});        
        $st_search_<?php echo $this->metaDataId; ?>.find('select.select2').select2('readonly', true); 
        $st_search_<?php echo $this->metaDataId; ?>.find('.ignore-disable-control select.select2').select2('readonly', false); 
        $st_search_<?php echo $this->metaDataId; ?>.find('.ignore-disable-control input[type="checkbox"], .ignore-disable-control input[type="text"]').removeAttr('readonly tabindex'); 
        
        <?php
        if (isset($this->isIframe)) {
        ?>
        bpBlockMessageStart('Тайлан бэлдэж байна...');
        
        $('#statement-form-<?php echo $this->metaDataId; ?>').find('iframe[data-default-url]').load(function(){
            bpBlockMessageStop();
        });
        <?php
        } else {
        ?>
        statementStyleResolver_<?php echo $this->metaDataId; ?>(1);
        <?php
        }
        ?>
        
    }, 100);
    <?php 
    } else {
        if (isset($this->isDrill) && $this->isDrill) {
    ?>
    setTimeout(function(){
        $st_search_<?php echo $this->metaDataId; ?>.find('input[type=text][readonly]').removeAttr('readonly tabindex');    
        statementStyleResolver_<?php echo $this->metaDataId; ?>(1);
    }, 100);      
    <?php    
        } else {
    ?>
    $st_search_<?php echo $this->metaDataId; ?>.find('input[type=text][readonly]').removeAttr('readonly tabindex');          
    <?php        
        }
    }
    ?>
            
    $('button.dataview-statement-filter-btn', dataview_statement_search_<?php echo $this->metaDataId.$this->dataViewId; ?>).on('click', function() {
        
        var $thisFilter = $(this);
        var layoutId = $thisFilter.attr('data-layout-id');
        
        var $form = $('#dataview-search-form', dataview_statement_search_<?php echo $this->metaDataId.$this->dataViewId; ?>);
        $form.validate({errorPlacement: function () {}});
        var select2MultiValidate = true;
        
        $form.find('select.select2').each(function(){
            var $this = $(this);
            if (typeof $this.attr('required') !== 'undefined' && typeof $this.attr('multiple') !== 'undefined' && $this.val() === null) {
                $this.addClass('error');
                select2MultiValidate = false;
            } else if (typeof $this.attr('required') !== 'undefined' && typeof $this.attr('multiple') !== 'undefined' && $this.val() !== null) {
                $this.removeClass('error');
                select2MultiValidate = true;
            }
        });
        
        var printCopiesParams = '';
        if ($form.find('input[data-print-copies]').length) {
            $form.find('input[data-print-copies]').each(function(){
                var $pcField = $(this);
                printCopiesParams += '&printCopies['+$pcField.attr('data-path')+']['+$pcField.val()+']='+$pcField.attr('data-print-copies');
            });
        }
        
        if ($form.valid() && select2MultiValidate) {
            
            <?php
            if (Config::getFromCache('is_dev')) {
            ?>
            var $reportFrameCombo = dataview_statement_search_<?php echo $this->metaDataId.$this->dataViewId; ?>.find('select.rp-reportframe-combo');
            if ($reportFrameCombo.length && $reportFrameCombo.val() == 'native') {
                layoutId = '';
            }
            <?php
            }
            ?>
                            
            if (layoutId == '') {
                
                $.ajax({
                    type: 'post',
                    url: 'mdstatement/renderDataModelByFilter',
                    data: $form.serialize()+printCopiesParams<?php echo (Mdstatement::$isPivotView ? "+'&isKpiIndicator=1'" : ''); if(isset($this->pageProperties)){ ?>+'&pageProperties=<?php echo Json::encode($this->pageProperties); ?>'<?php } ?>,
                    dataType: 'json', 
                    beforeSend: function () {
                        Core.blockUI({message: 'Тайлан бэлдэж байна...', boxed: true});
                    },
                    success: function (data) {
                        PNotify.removeAll();

                        if (data.status == 'success') {

                            var $statementContent = $('#statement-form-<?php echo $this->metaDataId; ?>').find('div.report-preview-print')[0];
                            $statementContent.innerHTML = data.htmlData;
                            
                            statementStyleResolver_<?php echo $this->metaDataId; ?>(data.childCount, data.freezeNumberOfColumn);
                            
                        } else {
                            new PNotify({
                                title: data.status,
                                text: data.message,
                                type: data.status,
                                sticker: false, 
                                hide: true,  
                                delay: 1000000000
                            });
                        }
                        Core.unblockUI();
                    },
                    error: function () {
                        alert("Error");
                        Core.unblockUI();
                    }
                }).done(function () {
                    $('.removeColGroup').find('colgroup').remove();
                });
                
            } else {
            
                var $iframe = $('#statement-form-<?php echo $this->metaDataId; ?>').find('iframe[data-default-url]');
                var defaultUrl = $iframe.attr('data-default-url');
                var layoutId = $iframe.attr('data-layout-id');
                
                if ($form.find('select.rp-template-combo').length && $form.find('select.rp-template-combo').val() != '') {
                    layoutId = $form.find('select.rp-template-combo').val() + '&comboLayoutId=1';
                }
                
                $.ajax({
                    type: 'post',
                    url: 'mdstatement/iframeReportFilter',
                    data: $form.serialize() + '&layoutId=' + layoutId,
                    dataType: 'json', 
                    beforeSend: function () {
                        bpBlockMessageStart('Тайлан бэлдэж байна...');
                    },
                    success: function (data) {
                        PNotify.removeAll();

                        if (data.status == 'success') {                        
                            
                            var $statementForm = $('#statement-form-<?php echo $this->metaDataId; ?>');
                            var buildUrl = defaultUrl + '&reportid=' + data.reportId + '&layoutId=' + layoutId;
                            
                            if (data.hasOwnProperty('expandReportId')) {
                                buildUrl += '&subReportIds=' + data.expandReportId;
                            }
                            
                            if (data.hasOwnProperty('langCode') && data.langCode) {
                                buildUrl += '&langCode=' + data.langCode;
                            }
                            
                            $statementForm.find('.st-iframe-fullscreen-btn').hide();
                            
                            $iframe.attr('src', buildUrl);
                            
                            $iframe.on('load', function () {
                                $statementForm.find('.st-iframe-fullscreen-btn').show();
                                bpBlockMessageStop();
                            });
                            
                        } else {
                            new PNotify({
                                title: data.status,
                                text: data.message,
                                type: data.status,
                                sticker: false, 
                                hide: true,  
                                delay: 1000000000
                            });
                        }
                        Core.unblockUI();
                    },
                    error: function () {
                        alert('Error');
                        Core.unblockUI();
                    }
                });
            }
        }
    });
    
    var $stDefaultVal_<?php echo $this->metaDataId; ?> = $('#dataview-search-form', dataview_statement_search_<?php echo $this->metaDataId.$this->dataViewId; ?>).find('input.fin-fiscalperiod-startdate, input.fin-fiscalperiod-enddate');
    
    if ($stDefaultVal_<?php echo $this->metaDataId; ?>.length) {
        $stDefaultVal_<?php echo $this->metaDataId; ?>.each(function() {
            var $thisFilter = $(this);
            var val = $thisFilter.val();
            if (val != '') {
                $thisFilter.attr('data-default-val', val);
            }
        });
    }
        
    $('button.dataview-statement-filter-reset-btn', dataview_statement_search_<?php echo $this->metaDataId.$this->dataViewId; ?>).on('click', function() {
        var $this = $(this);
        var $thisForm = $this.closest('form');
        var $thisFormDefaultVal = $thisForm.find('[data-default-val]');

        $thisForm.find('input[type=text], input[type=hidden], select, textarea').not("input[name='dataViewId'], input[name='statementId'], select[name*='criteriaCondition[']").val('');
        $thisForm.find('select.select2').select2('val', '');
        $thisForm.find('.btn.removebtn[data-lookupid]').hide();
        $thisForm.find('.btn[data-lookupid][data-choosetype][data-idfield][onclick]').text('..');
        
        if ($thisFormDefaultVal.length) {
            $thisFormDefaultVal.each(function() {
                var $thisFilter = $(this);
                $thisFilter.val($thisFilter.attr('data-default-val'));
            });
        }
    });
    $('button.dataview-statement-calculate-btn', dataview_statement_search_<?php echo $this->metaDataId.$this->dataViewId; ?>).on('click', function() {
        var $this = $(this);
        var $form = $this.closest('form');
        
        var postData = {
            processMetaId: $form.find("#processMetaId").val(),
            filterStartDate: $form.find("input[data-path='filterStartDate']").val(),
            filterEndDate: $form.find("input[data-path='filterEndDate']").val(),
            departmentId: $form.find("[data-path='filterDepartmentId']").val(), 
            tableName: $form.find("[data-path='tableName']").val(), 
            isIntegration: $form.find("[data-path='isIntegration']").length ? ($form.find("[data-path='isIntegration']").is(':checked') ? '1' : '0') : ($form.find("[data-path='isintegration']").is(':checked') ? '1' : '0'), 
            statementId: $form.find("input[name='statementId']").val(), 
            params: $form.serialize() 
        };
        
        if ($form.find("[data-path='ignoreDepartmentIds']").length) {
            
            var ignoreIds = '';
            
            $form.find("[data-path='ignoreDepartmentIds']").each(function(){
                var $ignoreId = $(this);
                if ($ignoreId.val() !== '') {
                    ignoreIds += $ignoreId.val() + ',';
                }
            });
            
            if (ignoreIds !== '') {
                postData['ignoreDepartmentIds'] = rtrim(ignoreIds, ',');
            }
        }
        
        $.ajax({
            type: 'post',
            url: 'mdstatement/processRun',
            data: postData, 
            dataType: 'json', 
            beforeSend: function () {
                Core.blockUI({
                    message: '<?php echo $this->lang->line('process_running'); ?>',
                    boxed: true
                });
            },
            success: function (data) {
                PNotify.removeAll();
                
                if (data.status == 'success') {
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                } else {
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false, 
                        hide: true,  
                        delay: 1000000000
                    });
                }
                
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        });
    });    
    $('button.dataview-statement-dv-btn', dataview_statement_search_<?php echo $this->metaDataId.$this->dataViewId; ?>).on('click', function() {
        
        $('#dataview-search-form', dataview_statement_search_<?php echo $this->metaDataId.$this->dataViewId; ?>).validate({errorPlacement: function () {}});
        var select2MultiValidate = true;
        
        $('select.select2', '#dataview-search-form', dataview_statement_search_<?php echo $this->metaDataId.$this->dataViewId; ?>).each(function(){
            var $this = $(this);
            if (typeof $this.attr('required') !== 'undefined' && typeof $this.attr('multiple') !== 'undefined' && $this.val() === null) {
                $this.addClass('error');
                select2MultiValidate = false;
            } else if (typeof $this.attr('required') !== 'undefined' && typeof $this.attr('multiple') !== 'undefined' && $this.val() !== null) {
                $this.removeClass('error');
                select2MultiValidate = true;
            }
        });
        
        if ($('#dataview-search-form', dataview_statement_search_<?php echo $this->metaDataId.$this->dataViewId; ?>).valid() && select2MultiValidate) {
            var $this = $(this);
            var $dvId = $this.attr('data-dv-id');
            
            $.ajax({
                type: 'post',
                url: 'mdobject/dataview/'+ $dvId +'/0/json',
                data: {
                    defaultCriteriaParams: $this.closest('form#dataview-search-form').serialize()
                }, 
                dataType: 'json', 
                beforeSend: function () {
                    Core.blockUI({
                        message: 'Loading...',
                        boxed: true
                    });
                },
                success: function (data) {
                    
                    var $dialogName = 'dialog-st-dv-<?php echo $this->metaDataId.$this->dataViewId; ?>';
                    if (!$("#" + $dialogName).length) {
                        $('<div id="' + $dialogName + '"></div>').appendTo('body');
                    }
                    var $dialog = $('#' + $dialogName);
                    
                    $dialog.empty().append(data.Html);
                    $dialog.dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.Title, 
                        width: 1000,
                        height: 'auto',
                        modal: true, 
                        closeOnEscape: isCloseOnEscape, 
                        close: function () {
                            $dialog.empty().dialog('destroy').remove();
                        },
                        buttons: [
                            {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                                $dialog.empty().dialog('destroy').remove();
                            }}
                        ]
                    }).dialogExtend({
                        "closable": true,
                        "maximizable": true, 
                        "minimizable": true,
                        "collapsable": true,
                        "dblclick": "maximize",
                        "minimizeLocation": "left",
                        "icons": {
                            "close": "ui-icon-circle-close",
                            "maximize": "ui-icon-extlink",
                            "minimize": "ui-icon-minus",
                            "collapse": "ui-icon-triangle-1-s",
                            "restore": "ui-icon-newwin"
                        }, 
                        maximize : function () {
                            //$('#objectdatagrid-'+ dataHtml.metaDataId).datagrid('resize');
                        }, 
                        restore : function () {
                            //$('#objectdatagrid-'+ dataHtml.metaDataId).datagrid('resize');
                        }
                    });

                    $dialog.dialog('open');
                    $dialog.dialogExtend("maximize");
                    
                    Core.unblockUI();
                },
                error: function () {
                    alert("Error");
                }
            });
        }
    });
    $('button.dataview-statement-grouping-btn', dataview_statement_search_<?php echo $this->metaDataId.$this->dataViewId; ?>).on('click', function() {
    
        var $dialogName = 'dialog-st-grouping-config-<?php echo $this->metaDataId; ?>'; 
        
        if ($('#' + $dialogName).children().length > 0) {
            var $dialog = $('#' + $dialogName);
            $dialog.dialog({
                appendTo: 'div#dataview-statement-search-<?php echo $this->metaDataId; ?> #dataview-search-form',
                cache: false,
                resizable: false,
                draggable: false,
                bgiframe: true,
                autoOpen: false,
                title: 'Бүлэглэх тохиргоо',
                width: 400,
                minWidth: 400,
                height: 'auto',
                modal: true, 
                closeOnEscape: isCloseOnEscape, 
                buttons: [
                    {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow', click: function () {
                        $.ajax({
                            type: 'post',
                            url: 'mdstatement/groupingUserOptionSave', 
                            data: $dialog.find('input').serialize()+'&statementId=<?php echo $this->metaDataId; ?>', 
                            dataType: 'json', 
                            beforeSend: function() {
                                Core.blockUI({
                                    boxed : true,
                                    message: 'Loading...'
                                });  
                            }, 
                            success: function() {
                                Core.unblockUI();
                                $dialog.dialog('close');
                            }
                        }); 
                    }},
                    {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            
        } else {
            var $dialog = $('#' + $dialogName);

            $.ajax({
                type: 'post',
                url: 'mdstatement/groupingUserOption',
                data: {statementId: '<?php echo $this->metaDataId; ?>'},
                dataType: 'json',
                beforeSend: function() {
                    Core.blockUI({
                        message: 'Loading...',
                        boxed: true
                    });
                },
                success: function(data) {
                    $dialog.empty().append(data.html);
                    $dialog.dialog({
                        appendTo: 'div#dataview-statement-search-<?php echo $this->metaDataId; ?> #dataview-search-form',
                        cache: false,
                        resizable: false,
                        draggable: false,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.title,
                        width: 400,
                        height: 'auto',
                        modal: true, 
                        closeOnEscape: isCloseOnEscape, 
                        buttons: [
                            {text: data.save_btn, class: 'btn btn-sm green-meadow', click: function() {
                                $.ajax({
                                    type: 'post',
                                    url: 'mdstatement/groupingUserOptionSave', 
                                    data: $dialog.find('input').serialize()+'&statementId=<?php echo $this->metaDataId; ?>', 
                                    dataType: 'json', 
                                    beforeSend: function() {
                                        Core.blockUI({
                                            boxed : true,
                                            message: 'Loading...'
                                        });  
                                    }, 
                                    success: function() {
                                        Core.unblockUI();
                                        $dialog.dialog('close');
                                    }
                                }); 
                            }}, 
                            {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                                $dialog.empty().dialog('destroy');
                            }}
                        ]
                    });
                    $dialog.dialog('open');
                    Core.unblockUI();
                },
                error: function() {
                    alert('Error');
                }
            }).done(function() {
                Core.initUniform($dialog);
            });
        }
    });
    
    $('select.rp-reportframe-combo', dataview_statement_search_<?php echo $this->metaDataId.$this->dataViewId; ?>).on('change', function() {
        var thisVal = $(this).val(), 
            $rpTemplateCombo = dataview_statement_search_<?php echo $this->metaDataId.$this->dataViewId; ?>.find('.rp-template-combo'), 
            $statementIframe = $('#statement-form-<?php echo $this->metaDataId; ?>').find('div.st-iframe-parent'), 
            $statementNative = $('#statement-form-<?php echo $this->metaDataId; ?>').find('div.st-native-parent');
            
        if (thisVal == 'native') {
            
            if ($statementNative.length) {
                
                $rpTemplateCombo.hide();
                $statementIframe.hide();
                $statementNative.show();
                        
            } else {
                $.ajax({
                    type: 'post',
                    url: 'mdstatement/dataModelReportViewer/<?php echo $this->metaDataId; ?>',
                    data: {ignoreIframe: 1},
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function(data) {

                        var $html = $('<div />', {html: data});

                        $rpTemplateCombo.hide();
                        $statementIframe.hide();

                        $statementIframe.after('<div class="st-native-parent">'+$html.find('.statement-preview').html()+'</div>');

                        Core.unblockUI();
                    }
                });
            }
            
        } else {
            $rpTemplateCombo.show();
            $statementNative.hide();
            $statementIframe.show();
        }
    });
    
    dataview_statement_search_<?php echo $this->metaDataId.$this->dataViewId; ?>.on('change', 'select.linked-combo', function () {
        var _this = $(this);
        var _outParam = _this.attr("data-out-param");

        var _outParamSplit = _outParam.split("|");
        for (var i = 0; i < _outParamSplit.length; i++) {
            
            var selfParam = _outParamSplit[i];
            var _cellSelect = dataview_statement_search_<?php echo $this->metaDataId.$this->dataViewId; ?>.find("select[data-path='" + selfParam + "']");

            if (_cellSelect.length) {
                var _inParam = _cellSelect.attr("data-in-param");
                var _inParamSplit = _inParam.split('|');
                var _inParams = '';
                
                for (var j = 0; j < _inParamSplit.length; j++) {
                    var _lastCombo = dataview_statement_search_<?php echo $this->metaDataId.$this->dataViewId; ?>.find("[data-path='" + _inParamSplit[j] + "']");
                    
                    if (_lastCombo.length) {
                        if (_lastCombo.prop('tagName') == 'SELECT' && _lastCombo.prop('multiple') && _lastCombo.val()) {
                            _inParams += _inParamSplit[j] + '=' + _lastCombo.val().toString() + '&';
                        } else if (_lastCombo.val() != '') {
                            _inParams += _inParamSplit[j] + '=' + _lastCombo.val() + '&';
                        }
                    }
                }
            }

            if (_inParams !== '') {
                $.ajax({
                    type: 'post',
                    url: 'mdobject/bpLinkedCombo',
                    data: {inputMetaDataId: '<?php echo $this->dataViewId; ?>', selfParam: selfParam, inputParams: _inParams},
                    dataType: 'json',
                    async: false,
                    beforeSend: function () {
                        Core.blockUI({
                            animate: true
                        });
                    },
                    success: function (dataStr) {
                        if (_cellSelect.hasClass('select2')) {
                            _cellSelect.select2('val', '');
                            _cellSelect.select2('readonly', false).select2('enable');
                        } else {
                            _cellSelect.val('');
                            _cellSelect.removeAttr('disabled readonly');
                            _cellSelect.parent().find('input, button').removeAttr('disabled readonly');
                        }
                        
                        if (_cellSelect.is('[multiple]')) {
                            $('option', _cellSelect).remove();
                        } else {
                            $('option:gt(0)', _cellSelect).remove();
                        }
                        
                        var comboData = dataStr[selfParam];
                        
                        _cellSelect.addClass('data-combo-set');
                        
                        $.each(comboData, function () {
                            _cellSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME));
                        });
                        
                        Core.initSelect2(_cellSelect.parent());
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
                Core.initSelect2(_cellSelect.parent());
            }
        }
    });
    dataview_statement_search_<?php echo $this->metaDataId.$this->dataViewId; ?>.on('change', 'input.linked-combo', function(){
        var _this = $(this);
        var _outParam = _this.attr('data-out-param');
        var _outParamSplit = _outParam.split('|');

        for (var i = 0; i < _outParamSplit.length; i++) {
            var selfParam = _outParamSplit[i];
            var _cellSelect = dataview_statement_search_<?php echo $this->metaDataId.$this->dataViewId; ?>.find("select[data-path='" + selfParam + "']");

            if (_cellSelect.length === 0) {
                var _cellInp = dataview_statement_search_<?php echo $this->metaDataId.$this->dataViewId; ?>.find("input[data-path='" + selfParam + "']");

                if (_this.val().length > 0 && _cellInp.length > 0) {
                    _cellInp.closest('.meta-autocomplete-wrap').find('input').removeAttr('readonly disabled');
                    _cellInp.parent().find('button').removeAttr('disabled');
                }

            } else {

                var _inParams = '';

                if (_cellSelect.length) {
                    if (typeof _cellSelect.attr("data-in-param") !== 'undefined' && _cellSelect.attr("data-in-param") !== '') {
                        var _inParam = _cellSelect.attr("data-in-param");
                        var _inParamSplit = _inParam.split("|");

                        for (var j = 0; j < _inParamSplit.length; j++) {
                            var _lastCombo = dataview_statement_search_<?php echo $this->metaDataId.$this->dataViewId; ?>.find("input[data-path='" + _inParamSplit[j] + "']");
                            if (_lastCombo.length && _lastCombo.val() !== '') {
                                _inParams += _inParamSplit[j] + '=' + encodeURIComponent(_lastCombo.val()) + '&';
                            } else {
                                var _lastCombo = dataview_statement_search_<?php echo $this->metaDataId.$this->dataViewId; ?>.find("input[data-path='" + _inParamSplit[j] + "']");
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
    <?php
    if (isset($this->autoSearch) && $this->autoSearch && !Mdstatement::$isKpiIndicator) {
    ?>
    Core.blockUI({message: 'Loading...', boxed: true});       
    setTimeout(function() {
        $('button.dataview-statement-filter-btn', dataview_statement_search_<?php echo $this->metaDataId.$this->dataViewId; ?>).trigger('click'); 
    }, 100); 
    <?php      
    }
    ?>
    
    dvFilterDateCheckInterval(dataview_statement_search_<?php echo $this->metaDataId.$this->dataViewId; ?>); 
    
    <?php
    if (isset($this->popupSearch)) { 
    ?>
    var $dialogName = 'st-popup-form-<?php echo $this->metaDataId; ?>';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);   
    
    $dialog.append($('script[data-template="st-popup-form-<?php echo $this->metaDataId; ?>"]').text());
    $dialog.dialog({
        cache: false,
        resizable: false,
        bgiframe: true,
        autoOpen: false,
        title: plang.get('do_filter'),
        width: 500, 
        height: "auto",
        modal: true,
        close: function () {
            $dialog.empty().dialog('destroy').remove();
        },
        buttons: [
            {text: plang.get('do_filter'), class: 'btn green-meadow btn-sm', click: function () {
                
                var $validForm = $dialog.find('form');
                $validForm.validate({errorPlacement: function () {}});
                
                if ($validForm.valid()) {
                    
                    var $form = $st_search_<?php echo $this->metaDataId; ?>;
                
                    $dialog.find('[data-path]').each(function() {
                        
                        var $this  = $(this);
                        var path   = $this.attr('data-path');
                        var $field = $form.find('[data-path="'+path+'"]');

                        if ($this.hasClass('popupInit')) {

                            var $parent = $this.closest('.meta-autocomplete-wrap');
                            var $parentField = $field.closest('.meta-autocomplete-wrap');

                            $parentField.find('.lookup-code-autocomplete').val($parent.find('.lookup-code-autocomplete').val());
                            $parentField.find('.lookup-name-autocomplete').val($parent.find('.lookup-name-autocomplete').val());
                            $field.val($this.val());

                        } else if ($this.hasClass('dateInit')) {
                            
                            $field.datepicker('update', $this.val());
                            
                        } else if ($this.hasClass('bigdecimalInit') 
                            || $this.hasClass('numberInit') 
                            || $this.hasClass('longInit') 
                            || $this.hasClass('decimalInit') 
                            || $this.hasClass('integerInit')) {
                            
                            $field.autoNumeric('set', $this.autoNumeric('get'));
                            
                        } else if ($this.hasClass('select2')) {
                            
                            var selectedVal = $this.val();
                            
                            if (selectedVal) {
                                $field.trigger('select2-opening', [true]);
                                $field.select2('val', selectedVal);
                            } else {
                                $field.select2('val', '');
                            }
                            
                        } else {
                            $field.val($this.val());
                        }
                    });    

                    $dialog.dialog('close');

                    $('button.dataview-statement-filter-btn', dataview_statement_search_<?php echo $this->metaDataId.$this->dataViewId; ?>).click();
                }
            }},
            {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $dialog.dialog('close');
            }}
        ]
    });
    Core.initAjax($dialog);
    
    setTimeout(function(){
        $dialog.dialog('open');
        dvFilterDateCheckInterval($dialog); 
    }, 1);
    <?php
    }
    
    if (isset($fieldCount) && Config::getFromCache('isStatementButtonResize') == '1') {
    ?>
    
    setTimeout(function(){
        var fieldCount = <?php echo $fieldCount; ?>;
        var $stBtnsRow = $st_search_<?php echo $this->metaDataId; ?>.find('.st-btns-row');

        if (fieldCount < 4) {
            
            var $stBtnsCol = $stBtnsRow.find('.col-12');
            var $stBtns = $stBtnsCol.children().clone(true);
            
            $stBtns.addClass('mr5');
            $stBtnsCol.remove();

            $stBtnsRow.append('<div class="col-md-4 offset-md-0"><div class="row"><div class="col-md-8 offset-md-4 st-clone-btns"></div></div></div>');

            var $cloneBtns = $stBtnsRow.find('.st-clone-btns');
            $cloneBtns.append($stBtns);

        } else if (fieldCount < 7) {

            var $stBtnsCol = $stBtnsRow.find('.col-12');
            var $stBtns = $stBtnsCol.children().clone(true);
            var mTop = (fieldCount < 6) ? ' style="margin-top: -31px;"' : '';
            
            $stBtns.addClass('mr5');
            $stBtnsCol.remove();

            $stBtnsRow.append('<div class="col-md-4 offset-md-4"'+mTop+'><div class="row"><div class="col-md-8 offset-md-4 st-clone-btns pl5"></div></div></div>');

            var $cloneBtns = $stBtnsRow.find('.st-clone-btns');
            $cloneBtns.append($stBtns);
        }
    }, 1);
    
    <?php
    }
    ?>
});
</script>
<?php 
if (isset($this->popupSearch)) { 
?>
<script type="text/template" data-template="st-popup-form-<?php echo $this->metaDataId; ?>">
    <?php echo $this->popupSearch; ?>
</script>
<?php
    }
} elseif (isset($this->isDrill) && $this->isDrill) {
?>
<script type="text/javascript">
var statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?> = false; 
var statement_mergecell_<?php echo $this->metaDataId.$this->dataViewId; ?> = false; 
statementStyleResolver_<?php echo $this->metaDataId; ?>(1);      
</script>
<?php 
} 
?>
<script type="text/javascript">
function statementStyleResolver_<?php echo $this->metaDataId; ?>(childCount, freezeNumber) {
    var $statementWindow = $('div#statement-form-<?php echo $this->metaDataId; ?>').find('.report-preview-container');

    if ($statementWindow.find("table > tbody > tr > td[data-merge-cell='true']:eq(0)").length > 0) {
        statement_mergecell_<?php echo $this->metaDataId.$this->dataViewId; ?> = true;
        $statementWindow.find("table > tbody:has(td[data-merge-cell='true'])").each(function(){
            $(this).TableSpan('verticalstatement').TableSpan('horizontalstatement');
        });
    }
    
    if ($statementWindow.find("table > tbody > tr > td[data-vertical-merge-cell='true']:eq(0)").length > 0) {
        statement_mergecell_<?php echo $this->metaDataId.$this->dataViewId; ?> = true;
        $statementWindow.find("table > tbody:has(td[data-vertical-merge-cell='true'])").each(function(){
            $(this).TableSpan('verticalstatement');
        });
    }

    $statementWindow.find('table:has(thead)').each(function() {

        var $table = $(this);
        var $thead = $table.find('thead');
        var headRowsLength = $thead.find('> tr').length;
        
        if (headRowsLength === 2) {
            
            $table.find('colgroup').remove();
            
            var _colgroup = '<colgroup>\n';
            var regex = /width:(.*?)\;/g;
            var _colspan = 0;                                   

            $thead.find('tr:first-child').find('th, td').each(function(){
                var $td = $(this);                                       

                if (typeof $td.attr('colspan') !== 'undefined') {
                    if ($td.attr('style').match(regex) !== null) {
                        var strWidth = $td.attr('style').match(regex);
                        var strToNum = strWidth[0].match(/\d/g), colsWidtSum = 0;
                        strToNum = Number(strToNum.join(''));
                        
                        var _colspanStart = _colspan, currentColspan = Number($td.attr('colspan'));
                        _colspan += currentColspan;
                        var secondtr = $thead.find('tr:last-child').find('th, td');

                        for (var i = _colspanStart; i < _colspan; i++) {
                            if (typeof secondtr[i] !== 'undefined') {
                                var getWidth = secondtr[i].style.cssText.match(regex);

                                if (getWidth !== null && typeof getWidth[0] !== 'undefined') {
                                    var strToChildNum = getWidth[0].match(/\d/g);
                                    colsWidtSum += Number(strToChildNum.join(''));                                                            
                                    currentColspan--;
                                }
                            }
                        }

                        var equalWidth = (strToNum - colsWidtSum) / currentColspan;

                        for (var i = _colspanStart; i < _colspan; i++) {
                            if (typeof secondtr[i] !== 'undefined') {
                                var getWidth = secondtr[i].style.cssText.match(regex);

                                if (getWidth !== null && typeof getWidth[0] !== 'undefined') {
                                    _colgroup += '<col style="' + getWidth[0] + '">\n';
                                } else {
                                    equalWidth = equalWidth > 500 ? 100 : equalWidth;
                                    _colgroup += '<col style="width:' + equalWidth + 'px">\n';
                                }
                            }
                        }

                    } else {

                        var _colspanStart = _colspan;
                        _colspan += Number($td.attr('colspan'));
                        var secondtr = $thead.find('tr:last-child').find('th, td');

                        for (var i = _colspanStart; i < _colspan; i++) {
                            if (typeof secondtr[i] !== 'undefined') {
                                var getWidth = secondtr[i].style.cssText.match(regex);
                                if (getWidth !== null && typeof getWidth[0] !== 'undefined')
                                    _colgroup += '<col style="' + getWidth[0] + '">\n';
                            }
                        }
                    }
                    /*var _colspanStart = _colspan;
                    _colspan += Number($td.attr('colspan'));
                    var secondtr = $thead.find('tr:last-child').find('th, td');

                    for (var i = _colspanStart; i < _colspan; i++) {
                        if (typeof secondtr[i] !== 'undefined') {
                            var getWidth = secondtr[i].style.cssText.match(regex);
                            if (getWidth !== null && typeof getWidth[0] !== 'undefined')
                                _colgroup += '<col style="' + getWidth[0] + '">\n';
                        }
                    }*/
                } else {

                    if ($td) {
                        try {
                            var getWidth = $td.attr('style').match(regex);
                            if (getWidth !== null && typeof getWidth[0] !== 'undefined') {
                                _colgroup += '<col style="' + getWidth[0] + '">\n';
                                /*_colspan++;*/
                            }
                        } catch(e) { }
                    }
                }

            });
            _colgroup += '</colgroup>';
            $thead.closest('table').prepend(_colgroup);
            
        } else if (headRowsLength === 3) {
            
            $table.find('colgroup').remove();
            
            var _colgroup = '<colgroup>\n';
            var regex = /width:(.*?)\;/g;
            var _colspan = 0, _colspan_level2 = 0;
        
            var firsttr = $thead.find('tr:first-child').find('th, td'),
                secondtr = $thead.find('tr:nth-child(2)').find('th, td'),
                thirdtr = $thead.find('tr:last-child').find('th, td');

            firsttr.each(function(){
                var $td = $(this);

                if (typeof $td.attr('colspan') !== 'undefined') {
                    var _colspanStart2 = _colspan_level2, currentColspan2 = Number($td.attr('colspan'));
                    _colspan_level2 += currentColspan2;

                    for (var ii = _colspanStart2; ii < _colspan_level2; ii++) {
                        var $td2 = $(secondtr[ii]);

                        if ($td2.length && (typeof $td2.attr('colspan') !== 'undefined' || typeof $td2.attr('rowspan') === 'undefined')) {                            
                            
                            var td2ColsResolver = typeof $td2.attr('colspan') === 'undefined' ? 1 : Number($td2.attr('colspan'));
                            
                            if ($td2[0].style.cssText.match(regex) !== null) {
                            
                                var strWidth = $td2[0].style.cssText.match(regex);
                                var strToNum = strWidth[0].match(/\d/g), colsWidtSum = 0;
                                strToNum = Number(strToNum.join(''));

                                var _colspanStart = _colspan, currentColspan = td2ColsResolver;
                                _colspan += currentColspan;                                

                                for (var i = _colspanStart; i < _colspan; i++) {
                                    if (typeof thirdtr[i] !== 'undefined') {
                                        var getWidth = thirdtr[i].style.cssText.match(regex);

                                        if (getWidth !== null && typeof getWidth[0] !== 'undefined') {
                                            var strToChildNum = getWidth[0].match(/\d/g);
                                            if (strToChildNum) {
                                                colsWidtSum += Number(strToChildNum.join(''));
                                                currentColspan--;
                                            }
                                        }
                                    }
                                }

                                var equalWidth = (strToNum - colsWidtSum) / currentColspan;

                                for (var i = _colspanStart; i < _colspan; i++) {
                                    if (typeof thirdtr[i] !== 'undefined') {
                                        var getWidth = thirdtr[i].style.cssText.match(regex);

                                        if (getWidth !== null && typeof getWidth[0] !== 'undefined') {
                                            _colgroup += '<col style="' + getWidth[0] + '">\n';
                                        } else {
                                            _colgroup += '<col style="width:' + equalWidth + 'px">\n';
                                        }
                                    }
                                }

                            } else {
                                
                                var _colspanStart = _colspan;
                                
                                if (td2ColsResolver > 1) {
                                    _colspan += td2ColsResolver - 1;
                                } else {
                                    _colspan += td2ColsResolver;
                                }

                                for (var i = _colspanStart; i < _colspan; i++) {
                                    if (typeof thirdtr[i] !== 'undefined') {
                                        var getWidth = thirdtr[i].style.cssText.match(regex);
                                        
                                        if (getWidth !== null && typeof getWidth[0] !== 'undefined') {
                                            _colgroup += '<col style="' + getWidth[0] + '">\n';
                                        } else {
                                            _colgroup += '<col style="width:' + $(thirdtr[i]).width() + 'px;">\n';
                                        }
                                    }
                                }
                            }

                        } else if (typeof $td2.attr('rowspan') !== 'undefined') {
                        
                            var getWidth = $td2.attr('style').match(regex);
                                
                            if (getWidth !== null && typeof getWidth[0] !== 'undefined') {
                                _colgroup += '<col style="' + getWidth[0] + '">\n';         
                            } else {
                                _colgroup += '<col style="width:' + $td2.width() + 'px;">\n';
                            }
                        }
                    }

                } else {

                    if ($td) {
                        try {
                            var getWidth = $td.attr('style').match(regex);
                            if (getWidth !== null && typeof getWidth[0] !== 'undefined') {
                                _colgroup += '<col style="' + getWidth[0] + '">\n';
                            }
                        } catch(e) {}
                    }
                }

            });
            _colgroup += '</colgroup>';
            $thead.closest('table').prepend(_colgroup);
        }
    });
    
    if ($statementWindow.find("table > thead > tr > th[data-merge-cell='true']:eq(0)").length > 0) {
        $statementWindow.find("table > thead:has(th[data-merge-cell='true'])").each(function(){
            $(this).TableSpan('horizontalstatementhead');
        });
    }
                        
    if ($statementWindow.find('.right-rotate').length > 0) {
        $statementWindow.find('.right-rotate').each(function(){
            var $this = $(this), wspace = $this.html().replace('&nbsp;', ' '),
            characterSplit = wspace.replace(/\s/g, '#').match(/.{1,1}/g), 
            tdHeigth = $this.closest('td').height() || $this.closest('th').height();
            var charWidth = 0, $parent = $this.parent();

            for (var i = 0; i < characterSplit.length; i++) {
                $parent.append('<span class="hide characterSplit">'+characterSplit[i]+'</span>');
                charWidth += $parent.find('span.characterSplit').width();
                $parent.find('span.characterSplit').remove();

                if (tdHeigth <= charWidth) {
                    var splitCharArr = wspace.match(new RegExp('.{1,' + (++i) + '}', 'g'));
                    $this.empty();
                    for (var ii = 0; ii < splitCharArr.length; ii++) {
                        $this.append('<span>' + splitCharArr[ii] + '</span>');
                    }
                    break;
                }
            }                                 
        });
    }
    if ($statementWindow.find('.left-rotate').length > 0) {
        $statementWindow.find('.left-rotate').each(function() {
            var $this = $(this), wspace = $this.html().replace('&nbsp;', ' '),
                characterSplit = wspace.replace(/\s/g, '#').match(/.{1,1}/g),
                tdHeigth = $this.closest('td').height() || $this.closest('th').height();
            var charWidth = 0, $parent = $this.parent();

            for (var i = 0; i < characterSplit.length; i++) {
                $parent.append('<span class="hide characterSplit">'+characterSplit[i]+'</span>');
                charWidth += $parent.find('span.characterSplit').width();
                $parent.find('span.characterSplit').remove();

                if (tdHeigth <= charWidth) {
                    var splitCharArr = wspace.match(new RegExp('.{1,' + (++i) + '}', 'g'));
                    $this.empty();
                    for (var ii = 0; ii < splitCharArr.length; ii++) {
                        $this.append('<span>' + splitCharArr[ii] + '</span>');
                    }
                    break;
                }
            }       
        });
    }
    
    if (childCount == 1) {
        
        var $previewOrientation = $statementWindow.find('div:eq(0)');
        
        if ($statementWindow.width() >= $previewOrientation.width() && $statementWindow.find('table:has(thead):not(.floatThead-table, .no-freeze):eq(0) > thead > tr').length < 3) {
            
            statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?> = true; 
            statementHeaderFreeze($statementWindow, freezeNumber);

            setTimeout(function() {
                var $table = $statementWindow.find('table:has(thead):not(.floatThead-table, .no-freeze):eq(0)');
                $table.trigger('reflow');
            }, 10);
        }
    }
}    
</script>
