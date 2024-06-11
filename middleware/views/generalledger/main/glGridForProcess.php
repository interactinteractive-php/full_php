<div id="<?php echo isset($this->glBpMainWindowId) ? $this->glBpMainWindowId : "glTemplateSectionProcess"; ?>" class="glTemplateSectionProcess" data-id="<?php echo isset($this->glBpMainWindowId) ? $this->glBpMainWindowId : "glTemplateSectionProcess"; ?>">
    <div class="col-md-12">
        <div class="row gl-parent-row">
            <?php
            $params = $this->paramList;
            $glBookNumberLabel = '';
            
            if (isset($this->templates)) {
                
                $glBookNumberLabel .= '<div class="ml-auto text-right">'; 
                $glBookNumberLabel .= Form::select(
                    array(
                        'name'     => 'glTemplateId', 
                        'class'    => 'form-control form-control-sm gl-template-change', 
                        'style'    => 'width: 200px; background-color: #40b0f1; color: #fff', 
                        'title'    => 'Журналын загвар', 
                        'data'     => $this->templates, 
                        'op_value' => 'id', 
                        'op_text'  => 'name', 
                        'text'     => 'notext', 
                        'value'    => issetParam($this->paramList['templateid'])
                    )
                );
                $glBookNumberLabel .= '</div>';
                
            } elseif (isset($params['templateid']) && $params['templateid']) {
                
                echo Form::hidden(array('name' => 'glTemplateId', 'value' => $params['templateid']));
            }
            
            $templateName = (isset($params['templatename']) && $params['templatename'] != '') ? 'Загвар: <span class="badge badge-info font-size-11 gl-template-name">'.$params['templatename'].'</span> ' : '';
            
            $glBookNumberLabel .= '<div class="ml-auto">';
            
            if (Config::getFromCache('ISUSESECONDARYRATE') == '1') {
                $numberFormat = '2';
                if(Config::getFromCache('ISSECONDARYRATEFRACTION')){
                    $numberFormat = Config::getFromCache('ISSECONDARYRATEFRACTION');
                }
                $glBookNumberLabel .= '<label class="mb0 mr-1 ml-2"><i class="fa fa-money" style="font-size: 14px;"></i></label>';
                $glBookNumberLabel .= '<label class="mb0"><input type="text" readonly class="form-control secondaryCurrencyCode" title="'.Lang::line('PL_99119911').'" style="height: 24px;width: 45px;background-color: #E4F0F9 !important;border-top-right-radius: 0;border-bottom-right-radius: 0;border-right-width: 0;" required="required" value="'.Ue::sessionSecondaryCurrencyCode().'"><input type="hidden" name="secondaryCurrencyId" value="'.Ue::sessionSecondaryCurrencyId().'"></label>';
                $glBookNumberLabel .= '<label class="mb0 mr-2"><input type="text" name="secondaryRate" class="form-control numberInit text-right" title="'.Lang::line('PL_232323').'"  data-mdec='.$numberFormat.' style="height: 24px;width: 150px;border-top-left-radius: 0;border-bottom-left-radius: 0" required="required" placeholder="Ханш"></label>';
            }
            
            if (!isset($this->isShowGlBookNumber) && isset($params['booknumber']) && $params['booknumber'] != '') {
                
                $glBookNumberLabel .= $templateName.' Журналын дугаар: <span class="badge badge-primary font-size-12">'.$params['booknumber'].'</span>';
                $glBookNumberLabel .= '<input type="checkbox" checked="checked" class="notuniform is-gl-customer-copy mr-1 display-none">
                    <input type="checkbox" checked="checked" class="notuniform is-gl-amount-calc mr-1 display-none">';
                
            } elseif (!isset($this->isNotAddAccount)) {
                
                if ($templateName) {
                    $glBookNumberLabel .= $templateName;
                }
                
                $glBookNumberLabel .= '
                    <label class="mb0"><input type="checkbox" checked="checked" class="notuniform is-gl-customer-copy mr-1"> '.Lang::lineDefault('gl_copy_customer', 'Харилцагч хуулах').'</label> 
                    <label class="mb0 ml-2"><input type="checkbox" checked="checked" class="notuniform is-gl-amount-calc mr-1"> '.Lang::lineDefault('gl_caculate_amt', 'Дүн бодох').'</label>';
                
            } else {
                
                if ($templateName) {
                    $glBookNumberLabel .= $templateName;
                }
                
                $glBookNumberLabel .= '<input type="checkbox" checked="checked" class="notuniform is-gl-customer-copy mr-1 display-none">
                    <input type="checkbox" checked="checked" class="notuniform is-gl-amount-calc mr-1 display-none">';
            }
            $glBookNumberLabel .= '</div>';
            
            if (!empty($params) && isset($params['generalledgerbookdtls']) && count($params['generalledgerbookdtls']) > 0) {
                    
                    if ($this->isFieldSet < 1) {
            ?>
                <fieldset class="mb15" data-initialized="1" id="meta_fieldset">
                    <legend>Журналын бичилт</legend>
                <?php
                    }
                    
                echo Form::hidden(array('name' => 'glbookId', 'value' => isset($params['id']) ? $params['id'] : ''));
                echo Form::hidden(array('name' => 'hidden_glbookDate', 'value' => $params['bookdate']));
                echo Form::hidden(array('name' => 'hidden_glbookNumber', 'value' => $params['booknumber']));
                echo Form::hidden(array('id' => 'gldescription', 'name' => 'hidden_gldescription', 'value' => $params['description']));
                echo Form::hidden(array('name' => 'glrelatedBookId', 'value' => isset($params['relatedbookid']) ? $params['relatedbookid'] : ''));
                echo Form::hidden(array('name' => 'glimportId', 'value' => isset($params['importid']) ? $params['importid'] : ''));
                echo Form::hidden(array('name' => 'glBookTypeId', 'value' => $params['booktypeid']));
                echo Form::hidden(array('name' => 'glIsComplete', 'value' => isset($params['iscomplete']) ? $params['iscomplete'] : ''));
                echo Form::hidden(array('name' => 'hidden_globject', 'value' => isset($params['objectid']) ? $params['objectid'] : ''));
                echo Form::hidden(array('name' => 'hidden_glcreatedUserId', 'value' => issetParam($params['createduserid'])));
                echo Form::hidden(array('name' => 'hidden_glcreatedDate', 'value' => issetParam($params['createddate'])));
            }
            
            if (isset($params['additionalvalues']) && $params['additionalvalues']) {
                echo Form::textArea(array('name' => 'gl_additionalValues', 'class' => 'd-none', 'value' => json_encode($params['additionalvalues'])));
            }
            
            if (isset($this->runSourceInputName)) {
                echo Form::hidden(array('name' => $this->runSourceInputName, 'value' => $this->runSourceInputValue));
            }
            ?>
                <div class="table-toolbar d-flex align-items-center">
                    <?php
                    if (!isset($this->isNotAddAccount)) {
                    ?>
                    <div class="input-group quick-item">
                        <div class="form-group-feedback form-group-feedback-left">
                            <?php echo Form::text(array('name' => 'glquickCode', 'id' => 'glquickCode', 'class' => 'form-control accountCodeMask', 'placeholder' => Lang::lineDefault('PL_20105', 'Дансны код'), 'tabindex' => 4)); ?>
                            <div class="form-control-feedback form-control-feedback-lg">
                                <i class="fa fa-search"></i>
                            </div>
                        </div>
                        <span class="input-group-append">
                            <?php echo Form::button(array('class' => 'btn green-meadow', 'tabindex' => 5, 'id' => 'accountSelectabledGridForMainId_'.$this->uniqId, 'value' => '<i class="icon-plus3 font-size-12"></i>', 'onclick' => "dataViewCustomSelectableGrid('".(isset($this->accountDvCode) && $this->accountDvCode ? $this->accountDvCode : Mdgl::$accountListDataViewCode)."', 'single', 'accountSelectabledGridForMain_".$this->uniqId."', '', this);")); ?>
                        </span>
                    </div>
                    <div class="input-group ml10" style="width: 80px;">
                        <span class="input-group-prepend">	
                            <button class="btn blue-hoki" type="button" tabindex="6" onclick="glAddRows_<?php echo $this->uniqId; ?>(this);"><i class="fa fa-sort-numeric-asc"></i></button>
                        </span>
                        <input type="text" class="form-control gl-add-emptyrows-input longInit text-center" tabindex="7" title="Мөрийн тоо" value="2">
                        <span class="input-group-append">
                            <button class="btn blue" type="button" title="Мөр нэмэх" tabindex="8" onclick="glAddEmptyRows_<?php echo $this->uniqId; ?>(this);"><i class="fa fa-sort-numeric-asc"></i></button>
                        </span>
                    </div>

                    <?php 
                        echo Form::button(array('class' => 'btn btn-xs green-meadow ml10', 'tabindex' => '9', 'value' => '<i class="fa fa-user"></i>', 'onclick' => "dataViewCustomGlSelectableGrid('".Mdgl::$customerApArListDataViewCode."', 'multi', 'apApSelectabledGridForMain_".$this->uniqId."', '', this, '', true, '', ".$this->uniqId.");")); 
                    }
                    echo $glBookNumberLabel; 
                    ?>
                </div>  
                <div id="fz-parent" class="freeze-overflow-xy-auto w-100">
                    <table class="table table-sm table-bordered table-hover gl-table-dtl bprocess-theme1 mb0" id="glDtl">
                        <thead id="header1">
                            <?php echo $this->header1; ?>
                        </thead>
                        <thead id="header2" class="hide">
                            <?php echo $this->header2; ?>
                        </thead>
                        <tbody>
                            <?php echo $this->gridBodyData; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>
                                    <input type="hidden" data-input-name="foot_sum_debitamountbase" value="0">
                                    <input type="hidden" data-input-name="foot_sum_debitamount" value="0">
                                    <input type="hidden" data-input-name="foot_sum_creditamountbase" value="0">
                                    <input type="hidden" data-input-name="foot_sum_creditamount" value="0">
                                </td>
                                <td></td>
                                <td></td>
                                <td class="glRowDescr"></td>
                                <td class="customPartner"></td>
                                <td class="glRowExpenseCenter"></td>
                                <?php if (Config::getFromCache('isGLDescrEnglish')) { ?>
                                    <td></td>
                                <?php } ?>                                
                                <td class="glRowCurrency"></td>
                                <td data-usebase="usebase" class="glRowRate"></td>
                                <td data-usebase="usebase" class="foot-sum-debitamountbase bigdecimalInit text-right font-weight-bold">0.00</td>
                                <td class="foot-sum-debitamount bigdecimalInit text-right font-weight-bold">0.00</td>
                                <td data-usebase="usebase" class="foot-sum-creditamountbase bigdecimalInit text-right font-weight-bold">0.00</td>
                                <td class="foot-sum-creditamount bigdecimalInit text-right font-weight-bold">0.00</td>
                                <td></td>
                                <?php if (Config::getFromCache('FIN_INCOMETAX_DEDUCTION') === '1') { ?>
                                    <td></td>
                                <?php } ?>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php
            if (!empty($params) && $this->isFieldSet < 1) {
            ?>
                </fieldset>     
            <?php
            }
            ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    var cashHandBank = ['20003', '20004'];
    var cashHandBankTypeCode = ['CASH_IN_TRANSIT', 'CASH_ON_BANK', 'CASH_ON_HAND'];
    var taxPayable = '40';
    var taxReceivable = '17';
    var glEntryWindowId_<?php echo $this->uniqId; ?> = '#glEntryWindow_<?php echo $this->uniqId; ?>';
    var glBpMainWindow_<?php echo $this->uniqId; ?> = '#<?php echo isset($this->glBpMainWindowId) ? $this->glBpMainWindowId : 'glTemplateSectionProcess'; ?>';
    var paramGLData_<?php echo $this->uniqId; ?> = JSON.parse('<?php if (isset($this->paramList['generalledgerbookdtls'])) { $paramData = $this->paramList; unset($paramData['generalledgerbookdtls']); if (isset($paramData['generalledgerbookoppdtls'])) { unset($paramData['generalledgerbookoppdtls']); } echo Json::encode($paramData); } else { echo Json::encode($this->paramList); } ?>');
    var accountAutoCompleteRequest = null;
    var chooseAccountAfterBp = true;
    var glAmountScale = '<?php echo $this->amountScale; ?>';
    var glIncomeTaxDeduction_<?php echo $this->uniqId; ?> = '<?php echo Config::getFromCache('FIN_INCOMETAX_DEDUCTION'); ?>';
    var $glLoadWindow_<?php echo $this->uniqId; ?> = $(glBpMainWindow_<?php echo $this->uniqId; ?>);
    var isIgnoreUseDetail_<?php echo $this->uniqId; ?> = <?php echo (isset($this->isIgnoreUseDetail) && $this->isIgnoreUseDetail) ? 'true' : 'false'; ?>;
    var defaultValueDimensions_<?php echo $this->uniqId; ?> = <?php echo isset($this->defaultValueDimensions) ? json_encode($this->defaultValueDimensions, JSON_UNESCAPED_UNICODE) : '{}'; ?>;
    
    $(function() {
        
        $(document.body).on('click' , '.gl-btn-group-dialog .dropdown-toggle', function(){
            var $self = $(this);
            var selfHeight = $self.parent().height() - 15;
            var selfWidth = $self.parent().width();
            var selfOffset = $self.offset();
            var selfOffsetRigth = $(document).width() - selfOffset.left - selfWidth;
            var $dropDown = $self.parent().find('ul');
            $dropDown.css({position:'fixed', top: selfOffset.top + selfHeight, left: 'auto', right: selfOffsetRigth, width: '180px'});
        });

        $(glEntryWindowId_<?php echo $this->uniqId; ?>).find('input[name="glbookDate"]').select();

        $(document).bind('keydown', 'Ctrl+M', function(e){
            $('#accountSelectabledGridForMainId_<?php echo $this->uniqId ?>').trigger('click');
            e.preventDefault();
            return false;
        });
        $(document).on('keydown', 'input, select, textarea, a, button', 'Ctrl+M', function(e){
            $('#accountSelectabledGridForMainId_<?php echo $this->uniqId ?>').trigger('click');
            e.preventDefault();
            return false;
        });        

        $(document).bind('keydown', 'Ctrl+Shift+Up Arrow', function(e){
            glAddEmptyRows_<?php echo $this->uniqId; ?>($(glEntryWindowId_<?php echo $this->uniqId; ?>).find('.gl-add-emptyrows-input'), '1');
            var $tableBody = $(glEntryWindowId_<?php echo $this->uniqId; ?>).find('#glDtl > tbody').first();
            var $lastRow = $tableBody.find('> tr:last');
            $lastRow.find('input:not([readonly="readonly"])').trigger('dblclick');
            e.preventDefault();
            return false;
        });
        $(document).on('keydown', 'input, select, textarea, a, button', 'Ctrl+Shift+Up Arrow', function(e){
            glAddEmptyRows_<?php echo $this->uniqId; ?>($(glEntryWindowId_<?php echo $this->uniqId; ?>).find('.gl-add-emptyrows-input'), '1');
            var $tableBody = $(glEntryWindowId_<?php echo $this->uniqId; ?>).find('#glDtl > tbody').first();
            var $lastRow = $tableBody.find('> tr:last');
            $lastRow.find('input:not([readonly="readonly"])').trigger('dblclick');            
            e.preventDefault();
            return false;
        });        
        
        Core.initAccountCodeMask($glLoadWindow_<?php echo $this->uniqId; ?>);
        
        $glLoadWindow_<?php echo $this->uniqId; ?>.find('.bigdecimalInit').autoNumeric('init', {aPad: true, mDec: 2, vMin: '-999999999999999999999999999999.999999999999999999999999999999', vMax: '999999999999999999999999999999.999999999999999999999999999999'});

        checkIsUseBase_<?php echo $this->uniqId; ?>($glLoadWindow_<?php echo $this->uniqId; ?>);
        checkRowDescriptionField_<?php echo $this->uniqId; ?>($glLoadWindow_<?php echo $this->uniqId; ?>);
        
        <?php
        if (isset($this->isNotAddAccount)) {
        ?>
        $glLoadWindow_<?php echo $this->uniqId; ?>.find('.gl-vat-deduction, .gl-incometax-deduction, .gl-row-remove').remove(); 
        <?php    
        }
        if (isset($this->paramList['generalledgerbookdtls']) && count($this->paramList['generalledgerbookdtls']) <= 300) {
        ?>
        checkIsUseGlDetail_<?php echo $this->uniqId; ?>();
        <?php
        }
        ?>
        
        checkIsComplete_<?php echo $this->uniqId; ?>();
       
        <?php
        if (isset($this->isNotButton) && !$this->isNotButton) {
        ?>
        $(glEntryWindowId_<?php echo $this->uniqId; ?>).find('input,textarea,select').attr('readonly', 'readonly');
        $(glEntryWindowId_<?php echo $this->uniqId; ?>).find('button,div,input[type="checkbox"]').attr('disabled', 'disabled');      
        <?php
        }
        ?>
        
        setTimeout(function() { glTableFreeze_<?php echo $this->uniqId; ?>(); }, 50);
        
        <?php
        if (!isset($this->drillDownParams) || (isset($this->drillDownParams) && count($this->drillDownParams) == 0)) {
        ?>
        $(glEntryWindowId_<?php echo $this->uniqId; ?>).on('changeDate', "#glbookDate", function(event) {
            var bookDate = $(this).val();
            var $glDtls = $('table#glDtl > tbody > tr', glBpMainWindow_<?php echo $this->uniqId; ?>);

            if ($('input[name="secondaryRate"]', glBpMainWindow_<?php echo $this->uniqId; ?>).length) {
                var secondaryRate = getAccountRate2_<?php echo $this->uniqId; ?>(bookDate, '', 'usd')
                $(glEntryWindowId_<?php echo $this->uniqId; ?>).find("input[name='secondaryRate']").autoNumeric('set', secondaryRate);
            }
            
            if ($glDtls.length && $('table#glDtl > thead#header2', glBpMainWindow_<?php echo $this->uniqId; ?>).is(':visible')) {
                                
                var e = jQuery.Event('keyup');
                
                e.keyCode = e.which = 50;
                        
                $glDtls.each(function() {
                    var $thisRow = $(this);
                    var currencyCode = $thisRow.find("input[name='gl_rate_currency[]']").val().toLowerCase();
                    var objectId = $thisRow.find("input[name='gl_objectId[]']").val();
                    var rowRate = $thisRow.find("input[name='gl_rate[]']").autoNumeric('get');
                    
                    if (currencyCode != '' && currencyCode != 'mnt' && (objectId == '20006' || objectId == '20007') && (rowRate == 0 || rowRate == '')) {
                        var accountId = $thisRow.find("input[name='gl_accountId[]']").val();
                        var rate = getAccountRate_<?php echo $this->uniqId; ?>(bookDate, accountId, currencyCode);
        
                        $thisRow.find("input[name='gl_rate[]']").autoNumeric('set', rate);
                        $thisRow.find("input[data-input-name='debitAmountBase'], input[data-input-name='creditAmountBase']").trigger(e);
                        $thisRow.find("input[name='defaultInvoiceBook[]']").val('');
                    }
                });
            }
        });
        if ($('input[name="secondaryRate"]', glBpMainWindow_<?php echo $this->uniqId; ?>).length && (!$('#glDtl > tbody > tr', glBpMainWindow_<?php echo $this->uniqId; ?>).length || $('input[name="glbookId"]', glBpMainWindow_<?php echo $this->uniqId; ?>).val() == '')) {
            var bookDate = $(glEntryWindowId_<?php echo $this->uniqId; ?>).find("#glbookDate").val();
            if (typeof bookDate === 'undefined') {
                bookDate = $("input[name='hidden_glbookDate']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
            }
            Core.initNumberInput($('input[name="secondaryRate"]', glBpMainWindow_<?php echo $this->uniqId; ?>).parent());
            var secondaryRate = getAccountRate2_<?php echo $this->uniqId; ?>(bookDate, '', 'usd');
            setTimeout(function() {
                $('input[name="secondaryRate"]', glBpMainWindow_<?php echo $this->uniqId; ?>).autoNumeric('set', secondaryRate);
            }, 100);
        } else if ($('input[name="secondaryRate"]', glBpMainWindow_<?php echo $this->uniqId; ?>).length && $('#glDtl > tbody > tr', glBpMainWindow_<?php echo $this->uniqId; ?>).length) {
            Core.initNumberInput($('input[name="secondaryRate"]', glBpMainWindow_<?php echo $this->uniqId; ?>).parent());
            setTimeout(function() {
                $('input[name="secondaryRate"]', glBpMainWindow_<?php echo $this->uniqId; ?>).autoNumeric('set', pureNumber($('#glDtl > tbody > tr', glBpMainWindow_<?php echo $this->uniqId; ?>).eq(0).find('input[name="gl_secondaryrate[]"]').val()));
                $('input.secondaryCurrencyCode', glBpMainWindow_<?php echo $this->uniqId; ?>).val($('#glDtl > tbody > tr', glBpMainWindow_<?php echo $this->uniqId; ?>).eq(0).find('input[name="gl_secondarycurrencyname[]"]').val());
                $('input[name="secondaryCurrencyId"]', glBpMainWindow_<?php echo $this->uniqId; ?>).val($('#glDtl > tbody > tr', glBpMainWindow_<?php echo $this->uniqId; ?>).eq(0).find('input[name="gl_secondarycurrencyid[]"]').val());
            }, 100);
        }        
        <?php
        }
        ?>
                
        $glLoadWindow_<?php echo $this->uniqId; ?>.on('click', 'table#glDtl > tbody > tr', function() {
            $('body').find("table#glDtl > tbody > tr.gl-selected-row").removeClass("gl-selected-row"); 
            $(this).addClass("gl-selected-row");
        });                
        
        $glLoadWindow_<?php echo $this->uniqId; ?>.on('click', '.is-ac-meta-empty', function() {
            var $this = $(this);
            if ($this.is(':checked')) {                
                if ($this.closest('tr').find('[required="required"]').length) {
                    $this.closest('tr').find('[required="required"]').attr('data-required', 1);
                }
                $this.closest('tr').find('[required="required"]').removeAttr('required');
            } else if($this.closest('tr').find('[data-required="1"]').length) {
                if ($this.closest('tr').find('.double-between-input').length) {
                    $this.closest('tr').find('.double-between-input').find('input[type=text]').attr('required', 'required');
                }
                $this.closest('tr').find("[data-path]").attr('required', 'required');  
            }
        });
        $glLoadWindow_<?php echo $this->uniqId; ?>.on('focus', '#glDtl > tbody > tr', function() {
            $('body').find("table#glDtl > tbody > tr.gl-selected-row").removeClass("gl-selected-row"); 
            $(this).addClass("gl-selected-row");
        });
        $glLoadWindow_<?php echo $this->uniqId; ?>.on('keydown', "table#glDtl > tbody > tr > td > input", function(e) {
            var keyCode = (e.keyCode ? e.keyCode : e.which);
            var $this = $(this);
            
            if (keyCode === 13) {
                e.preventDefault();
                e.stopPropagation();                
                if ($this.closest('td').next().hasClass('gl-action-column')) {
                    $(glBpMainWindow_<?php echo $this->uniqId; ?>).find('input#glquickCode').select();
                } else {
                    $this.closest('td').next().find("input[type=text]:not([readonly])").select();
                }
            }
        });
        
        $('#glDtl > tbody', glBpMainWindow_<?php echo $this->uniqId; ?>).on('keyup', 'input[type=text]:not([readonly],[disabled])', function(e) {
            var _this = $(this);
            var parent = _this.parent();
            var getIndex = parent.index();
            
            if (e.which === 37) { // left arrow
                parent.prev().children("input[type=text]").select();
            } else if (e.which === 39 || (e.which === 9 || e.shiftKey)) { // right arrow and tab
                parent.next().children("input[type=text]").select();
            } else if (e.which === 40) { // up arrow
                parent.parent().next().children("td:eq(" + getIndex + ")").children("input[type=text]").select();
            } else if (e.which === 38) { // down arrow
                parent.parent().prev().children("td:eq(" + getIndex + ")").children("input[type=text]").select();
            } else if (e.which === 13) { // enter
                parent.parent().next().children("td:eq(" + getIndex + ")").children("input[type=text]").select();
            } else if ((e.which === 46 || e.which === 8 
                        || e.which >= 48 || e.which <= 57) 
                        && (e.which >= 96 || e.which <= 105)) { //numbers and delete, backspace
                gridGlInputFormula_<?php echo $this->uniqId; ?>($(this), 'tworound');
            }
            calculateFooterSum_<?php echo $this->uniqId; ?>(_this);
        });
        
        var isdebitdefaultvaluevar = false;
        var iscreditdefaultvaluevar = false;
        $('#glDtl > tbody', glBpMainWindow_<?php echo $this->uniqId; ?>).on('change', 'input[data-input-name=debitAmount]:not([readonly],[disabled])', function() {
            var $tr = $(this).closest('tr');
            
            if (typeof $tr.attr('data-isdebitcreditdefaultvalue') !== 'undefined' && !isdebitdefaultvaluevar && pureNumber($(this).val()) > 0) {
                var $dialogName = 'dialog-expandedGlDtl';
                var $rowDialog = $(this).closest('tr').find('#' + $dialogName);
                if ($rowDialog.children().length) {
                    $rowDialog.empty().dialog('destroy').remove();
                }

                isdebitdefaultvaluevar = true;
                iscreditdefaultvaluevar = false;
                if ($tr.find('.gl-dtl-meta-btn:visible').length) {
                    $tr.find('.gl-dtl-meta-btn:visible').trigger('click');
                } else {                
                    expandGlDtl_<?php echo $this->uniqId; ?>(this);
                }
            } else if (pureNumber($(this).val()) === 0) {
                isdebitdefaultvaluevar = false;
            }
        });        
        
        $('#glDtl > tbody', glBpMainWindow_<?php echo $this->uniqId; ?>).on('change', 'input[data-input-name=creditAmount]:not([readonly],[disabled])', function() {
            var $tr = $(this).closest('tr');
            
            if (typeof $tr.attr('data-isdebitcreditdefaultvalue') !== 'undefined' && !iscreditdefaultvaluevar && pureNumber($(this).val()) > 0) {
                var $dialogName = 'dialog-expandedGlDtl';
                var $rowDialog = $(this).closest('tr').find('#' + $dialogName);
                if ($rowDialog.children().length) {
                    $rowDialog.empty().dialog('destroy').remove();
                }            

                iscreditdefaultvaluevar = true;
                isdebitdefaultvaluevar = false;
                if ($tr.find('.gl-dtl-meta-btn:visible').length) {
                    $tr.find('.gl-dtl-meta-btn:visible').trigger('click');
                } else {                
                    expandGlDtl_<?php echo $this->uniqId; ?>(this);
                }
            } else if (pureNumber($(this).val()) === 0) {
                iscreditdefaultvaluevar = false;
            }
        });        
        
        <?php
        if (isset($this->glRlPlEditModeInputsEnable)) {
        ?>
        $('#glDtl > tbody', glBpMainWindow_<?php echo $this->uniqId; ?>).on('change', 'input[type=text]:not([readonly],[disabled])', function() {
            var $row = $(this).closest('tr');
            $row.find('input[name="gl_invoiceBookId[]"], input[name="invoiceBookValue[]"]').val('');
        });
        <?php
        }
        ?>
        
        $('#glDtl > tbody', glBpMainWindow_<?php echo $this->uniqId; ?>).on('dblclick', 'input:not([readonly="readonly"])', function(e) {
            completeGlAmount_<?php echo $this->uniqId; ?>(this);
        });
        $('#glDtl > tbody', glBpMainWindow_<?php echo $this->uniqId; ?>).on('dblclick', 'input[name="gl_rowdescription[]"]', function(e) {
            completeGlDescription_<?php echo $this->uniqId; ?>(this);
        });
        $('#glDtl > tbody', glBpMainWindow_<?php echo $this->uniqId; ?>).on('keyup', 'input[name="gl_subid[]"]', function(e) {
            checkSubChange_<?php echo $this->uniqId; ?>($(this).closest("tr"));
        });
        $glLoadWindow_<?php echo $this->uniqId; ?>.on("focus", 'input#glquickCode:not(disabled, readonly)', function(e){
            var _this = $(this);
            var isHoverSelect = false;
            
            _this.autocomplete({
                minLength: 1,
                maxShowItems: 30,
                delay: 500,
                highlightClass: "lookup-ac-highlight", 
                appendTo: "body",
                position: {my : "left top", at: "left bottom", collision: "flip flip"}, 
                autoFocus: false,
                source: function(request, response) {
                    
                    if (accountAutoCompleteRequest != null) {
                        accountAutoCompleteRequest.abort();
                        accountAutoCompleteRequest = null;
                    }
            
                    accountAutoCompleteRequest = $.ajax({
                        type: 'post',
                        url: 'mdgl/filterAccountInfo',
                        dataType: "json",
                        data: {q: request.term, dvId: '<?php echo issetParam($this->accountDvId); ?>'},
                        success: function(data) {
                            response($.map(data, function(item) {
                                return {
                                    label: item.ACCOUNTCODE,
                                    name: item.ACCOUNTNAME,
                                    data: item
                                };
                            }));
                        }
                    });
                },
                focus: function(event, ui) {
                    if (typeof event.keyCode === 'undefined' || event.keyCode == 0) {
                        isHoverSelect = false;
                    } else {
                        if (event.keyCode == 38 || event.keyCode == 40) {
                            isHoverSelect = true;
                        }
                    }
                    return false;
                },
                open: function() {
                    //$(this).autocomplete('widget').zIndex(99999999999999);
                    return false;
                },
                close: function (event, ui){
                    $(this).autocomplete("option","appendTo","body"); 
                }, 
                select: function(event, ui){
                    var data = ui.item.data;
                    
                    if (isHoverSelect || event.originalEvent.originalEvent.type == 'click') {
                        addGlDtlWithAccountValue_<?php echo $this->uniqId; ?>(data, _this, 'autocomplete');
                    } else {
                        if (ui.item.label === _this.val()) {
                            addGlDtlWithAccountValue_<?php echo $this->uniqId; ?>(data, _this, 'autocomplete');
                        } else {
                            var origEvent = event;
                            
                            while (origEvent.originalEvent !== undefined){
                                origEvent = origEvent.originalEvent;
                            }

                            if (origEvent.type === 'click') {
                                var e = jQuery.Event("keydown");
                                e.keyCode = e.which = 13;
                                _this.trigger(e);
                            }
                            event.preventDefault();
                        }
                    }
            
                    _this.val('');          
                    return false;                    
                }
            }).autocomplete("instance")._renderItem = function(ul, item) {
                ul.addClass('lookup-ac-render');

                var re = new RegExp("(" + this.term + ")", "gi"),
                    cls = this.options.highlightClass,
                    template = "<span class='" + cls + "'>$1</span>",
                    label = item.label.replace(re, template);

                return $('<li>').append('<div class="lookup-ac-render-code">'+label+'</div><div class="lookup-ac-render-name">'+item.name+'</div>').appendTo(ul);
            };    
        });
        $glLoadWindow_<?php echo $this->uniqId; ?>.on("keydown", 'input#glquickCode:not(disabled, readonly)', function(e){
            var code = (e.keyCode ? e.keyCode : e.which);
            var $this = $(this);
            
            if (code === 13) {
                
                if ($this.data("ui-autocomplete")) {
                    $this.autocomplete("destroy");
                }   
                
                $.ajax({
                    type: 'post',
                    url: 'mdgl/getRowAccountInfo',
                    data: {q: $this.val(), dvId: '<?php echo issetParam($this->accountDvId); ?>'},
                    dataType: 'json',
                    async: false,
                    beforeSend: function () {
                        $this.addClass("spinner2");
                    },
                    success: function (data) {
                        addGlDtlWithAccountValue_<?php echo $this->uniqId; ?>(data, $this, 'autocomplete');
                        $this.val('').removeClass("spinner2");
                    }
                });
                
                return false;
                
            } else if (!$this.data("ui-autocomplete")) {
                $this.trigger('focus');
            }
        });
        $glLoadWindow_<?php echo $this->uniqId; ?>.on("focus", 'input[name="gl_accountCode[]"]', function(e){
            var _this = $(this);
            var tr = _this.closest("tr");   
            var isHoverSelect = false;
             
            _this.autocomplete({
                minLength: 1,
                maxShowItems: 30,
                delay: 500,
                highlightClass: "lookup-ac-highlight", 
                appendTo: "body",
                position: {my : "left top", at: "left bottom", collision: "flip flip"}, 
                autoFocus: false,
                source: function(request, response) {
                    
                    if (accountAutoCompleteRequest != null) {
                        accountAutoCompleteRequest.abort();
                        accountAutoCompleteRequest = null;
                    }
            
                    accountAutoCompleteRequest = $.ajax({
                        type: 'post',
                        url: 'mdgl/filterAccountInfo',
                        dataType: "json",
                        data: {
                            q: request.term,
                            filter: $(tr).find("input[name='gl_accountFilter[]']").val(), 
                            dvId: '<?php echo issetParam($this->accountDvId); ?>' 
                        },
                        success: function(data) {
                            response($.map(data, function(item) {
                                return {
                                    label: item.ACCOUNTCODE,
                                    name: item.ACCOUNTNAME,
                                    data: item
                                };
                            }));
                        }
                    });
                },
                focus: function(event, ui) {
                    if (typeof event.keyCode === 'undefined' || event.keyCode == 0) {
                        isHoverSelect = false;
                    } else {
                        if (event.keyCode == 38 || event.keyCode == 40) {
                            isHoverSelect = true;
                        }
                    }
                    return false;
                },
                open: function() {
                    //$(this).autocomplete('widget').zIndex(99999999999999);
                    return false;
                },
                close: function (event, ui){
                    $(this).autocomplete("option","appendTo","body"); 
                }, 
                select: function(event, ui){
                    var data = ui.item.data;   
                    
                    var bookDate = $("input[name='glbookDate']", glEntryWindowId_<?php echo $this->uniqId; ?>).val();
                    if (typeof bookDate === 'undefined') {
                        bookDate = $("input[name='hidden_glbookDate']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
                    }
                    
                    $(tr).find("input[name='gl_accountId[]'], input[name='gl_accountCode[]'], input[name='gl_accountName[]'], input[name='defaultInvoiceBook[]'], input[name='gl_processId[]'], input[name='gl_dtlId[]'], input[name='gl_description[]'], input#srcInvoiceBook").val('');
                    $(tr).find("input[name='gl_isEdited[]']").val('0');
                    
                    $(tr).find("input[name='gl_accountCode[]']").val(data.ACCOUNTCODE);
        
                    if (data !== false) {
                        if (isHoverSelect || event.originalEvent.originalEvent.type == 'click') {
                            changeRowAccount_<?php echo $this->uniqId; ?>(bookDate, tr, data);
                            var originalEvent = event;
                            
                            while (originalEvent) {
                                if (originalEvent.keyCode == 13) {
                                    originalEvent.stopPropagation();
                                }
                                
                                if (originalEvent == event.originalEvent) {
                                    break;
                                }    
                                originalEvent = event.originalEvent;
                            }
                            
                        } else {
                            if (ui.item.label === _this.val()) {
                                changeRowAccount_<?php echo $this->uniqId; ?>(bookDate, tr, data);
                                var originalEvent = event;
                                while (originalEvent) {
                                    if (originalEvent.keyCode == 13)
                                        originalEvent.stopPropagation();

                                    if (originalEvent == event.originalEvent)
                                        break;

                                    originalEvent = event.originalEvent;
                                }
                            } else {
                                var origEvent = event;

                                while (origEvent.originalEvent !== undefined){
                                    origEvent = origEvent.originalEvent;
                                }
                                
                                if (origEvent.type === 'click') {
                                    var e = jQuery.Event("keydown");
                                    e.keyCode = e.which = 13;
                                    _this.trigger(e, [true]);
                                }
                                event.preventDefault();
                            }
                        }
                    } else {
                        clearglDtlTr_<?php echo $this->uniqId; ?>(tr);
                    }        
                    return false;  
                } 
            }).autocomplete("instance")._renderItem = function(ul, item) {
                ul.addClass('lookup-ac-render');

                var re = new RegExp("(" + this.term + ")", "gi"),
                    cls = this.options.highlightClass,
                    template = "<span class='" + cls + "'>$1</span>",
                    label = item.label.replace(re, template);

                return $('<li>').append('<div class="lookup-ac-render-code">'+label+'</div><div class="lookup-ac-render-name">'+item.name+'</div>').appendTo(ul);
            };    
        });
        $glLoadWindow_<?php echo $this->uniqId; ?>.on("keydown", 'input[name="gl_accountCode[]"]', function(e){

            var code = (e.keyCode ? e.keyCode : e.which);
            var $this = $(this);
            var tr = $this.closest("tr");   
            
            var bookDate = $("input[name='glbookDate']", glEntryWindowId_<?php echo $this->uniqId; ?>).val();
            if (typeof bookDate === 'undefined') {
                bookDate = $("input[name='hidden_glbookDate']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
            }
  
            if (code === 13) {
                if ($this.data("ui-autocomplete")) {
                    $this.autocomplete("destroy");
                } 
                
                $.ajax({
                    type: 'post',
                    url: 'mdgl/getRowAccountInfo',
                    data: {
                        q: $this.val(), 
                        filter: tr.find("input[name='gl_accountFilter[]']").val(), 
                        dvId: '<?php echo issetParam($this->accountDvId); ?>' 
                    },
                    dataType: "json",
                    async: false,
                    beforeSend: function () {
                        $this.addClass('spinner2');
                    },
                    success: function (data) {
                        if (Object.keys(data).length) {
                            if ($.isArray(data)) {
                                clearglDtlTr_<?php echo $this->uniqId; ?>(tr);
                            } else {
                                changeRowAccount_<?php echo $this->uniqId; ?>(bookDate, tr, data);
                            }               
                        }
                        $this.removeClass('spinner2');
                    }
                });
                return false;
                
            } else if (!$this.data('ui-autocomplete')) {
                $this.trigger('focus');
            }
        });
        
        $glLoadWindow_<?php echo $this->uniqId; ?>.delegate('input[name="gl_accountCode[]"]:not([readonly], [disabled])', 'paste', function(e){
            var $start = $(this), source;

            if (window.clipboardData !== undefined) {
                source = window.clipboardData;
            } else {
                source = e.originalEvent.clipboardData;
            }
            var data = source.getData('Text');

            if (data.indexOf("\n") !== -1 && data.length) {

                var $rowCell = $start.closest('td'); 
                var $colIndex = $rowCell.index();
                var columns = data.split("\n");
                var i, columnsLength = columns.length, colAccount;
                var $row, accountData, $rowMeta, isFirstRow = false, $firstRow;
                
                bpBlockMessageStart('Loading...');
                
                setTimeout(function(){
                    var bookDate = $("input[name='glbookDate']", glEntryWindowId_<?php echo $this->uniqId; ?>).val();
                    if (typeof bookDate === 'undefined') {
                        bookDate = $("input[name='hidden_glbookDate']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
                    }

                    var accountResponse = $.ajax({
                        type: 'post',
                        url: 'mdgl/accountCodePaste',
                        data: {accountCodes: columns, bookDate: bookDate},
                        dataType: 'json',
                        async: false
                    });

                    var accountResult = accountResponse.responseJSON;

                    for (i = 0; i < columnsLength; i++) {

                        colAccount = $.trim(columns[i]);

                        if (colAccount) {

                            $row = $start.closest('tr');
                            $start.val(colAccount);

                            if (accountResult[i].hasOwnProperty('accountRow')) {
                                
                                accountData = accountResult[i]['accountRow'];
                                $rowMeta = $row.find('input[name="gl_metas[]"]');
                                
                                if (isIgnoreUseDetail_<?php echo $this->uniqId; ?>) {
                                    accountData.ISUSEDETAILBOOK = 0;
                                }

                                $row.find("input[name='defaultInvoiceBook[]'], input[name='gl_processId[]'], input#srcInvoiceBook, input[name='gl_keyId[]'], input[name='gl_invoiceBookId[]']").val('');
                                $row.find("input[name='gl_accountId[]']").val(accountData.ID);
                                $row.find("input[name='gl_accountName[]']").val(accountData.ACCOUNTNAME).attr('title', accountData.ACCOUNTNAME);
                                $row.find("input[name='gl_main_accounttypeid[]']").val(accountData.ACCOUNTTYPEID);
                                $row.find("input[name='gl_accounttypeCode[]']").val(accountData.ACCOUNTTYPECODE);
                                $row.find("input[name='gl_objectId[]']").val(accountData.OBJECTID);
                                $row.find("input[name='gl_useDetailBook[]']").val(accountData.ISUSEDETAILBOOK);
                                $row.find("input[name='gl_accountFilterConfig[]']").val('');
                                $row.find("input[name='gl_accountFilterConfigIsDimension[]']").val('');

                                $rowMeta.val('');
                                
                                if (accountData.hasOwnProperty('ACCOUNTFILTER') && accountData.ACCOUNTFILTER !== '') {
                                    $row.find("input[name='gl_accountFilterConfig[]']").val(accountData.ACCOUNTFILTER);
                                }                                
                                
                                if (accountData.hasOwnProperty('ISNULLDIMENSION') && accountData.ISNULLDIMENSION !== '') {
                                    $row.find("input[name='gl_accountFilterConfigIsDimension[]']").val(accountData.ISNULLDIMENSION);
                                }                                

                                if (accountData.hasOwnProperty('ECONOMICCLASSID') && accountData.ECONOMICCLASSID !== '' && accountData.ECONOMICCLASSID !== 'null' && accountData.ECONOMICCLASSID !== null) {
                                    var rowMetaObj = {};
                                    rowMetaObj['economicclassid'] = accountData.ECONOMICCLASSID;
                                    $rowMeta.val(JSON.stringify(rowMetaObj));
                                }

                                if (accountData.OBJECTID == '20006' || accountData.OBJECTID == '20007' || accountData.OBJECTID == '30004') {
                                    $row.find("input[name='gl_customerCode[]']").removeAttr('readonly');
                                    $row.find("input[name='gl_customerCode[]']").parent().find('button').removeAttr('disabled');
                                    $row.find("input[name='gl_customerName[]']").removeAttr('readonly');
                                } else {
                                    $row.find("input[name='gl_customerCode[]']").attr('readonly', 'readonly');
                                    $row.find("input[name='gl_customerCode[]']").parent().find('button').attr('disabled', 'disabled');
                                    $row.find("input[name='gl_customerName[]']").attr('readonly', 'readonly');
                                }    

                                $row.find("input[name='gl_rate_currency[]']").val(accountData.CURRENCYCODE);
                                $row.find("input[name='gl_rate[]']").autoNumeric('set', accountResult[i]['rate']);
                                $row.find("input[name='gl_isdebit[]']").val('');
                                $row.find('.glRowCurrency').text(accountData.CURRENCYCODE);

                                if (accountResult[i]['isOppMetaAttr']) {
                                    $row.attr('data-op-meta', accountResult[i]['isOppMetaAttr']);
                                }

                                $row.find('#detailedMeta, .gl-dtl-meta-btn').remove();

                                var $actionCell = $row.find('.gl-action-column');

                                if (accountResult[i]['isProcess']) {
                                    $actionCell.prepend('<div class="btn btn-xs blue" id="detailedMeta" title="Дэлгэрэнгүй" onclick="expandGlDtl_<?php echo $this->uniqId; ?>(this);">...</div>');
                                }

                                if (accountResult[i]['isMeta']) {
                                    $actionCell.prepend('<div class="btn btn-xs purple-plum gl-dtl-meta-btn" title="Үзүүлэлт" onclick="showDtlMeta_<?php echo $this->uniqId; ?>(this);">...</div>');
                                }
                            }
                            
                            if (isFirstRow == false) {
                                $firstRow = $row;
                                isFirstRow = true;
                            }

                            $start = $row.next('tr').find('td:eq('+$colIndex+') input[type=text]:visible:eq(0)');
                            if (!$start.length) {
                                break;  
                            }
                        }
                    }

                    checkIsUseBase_<?php echo $this->uniqId; ?>($row); 
                    checkAccountFilterConfig_<?php echo $this->uniqId; ?>($row, 'all');
                    
                    $firstRow.find('input[name="gl_rowdescription[]"]').focus();
                    bpBlockMessageStop();
                    
                }, 200);

                e.preventDefault();
            }
        });
        
        $glLoadWindow_<?php echo $this->uniqId; ?>.delegate('input[data-input-name]:not([readonly], [disabled])', 'paste', function(e){
            var $start = $(this), source;

            if (window.clipboardData !== undefined) {
                source = window.clipboardData;
            } else {
                source = e.originalEvent.clipboardData;
            }
            var data = source.getData('Text');

            if (data.indexOf("\n") !== -1 && data.length) {
                
                var $rowCell = $start.closest('td'); 
                var $colIndex = $rowCell.index();
                var columns = data.split("\n");
                var i, columnsLength = columns.length, colAmount;
                
                for (i = 0; i < columnsLength; i++) {
                    colAmount = $.trim(columns[i]);
                    if (colAmount) {
                        colAmount = colAmount.replace(/[,]/g, '');
                        $start.autoNumeric('set', colAmount);
                        $start.next("input[type=hidden]").val(colAmount);
                        $start = $start.closest('tr').next('tr').find('td:eq('+$colIndex+') input[type=text]:visible:eq(0)');
                        if (!$start.length) {
                            return false;  
                        }
                    }
                }

                e.preventDefault();
            }
        });
        
        $glLoadWindow_<?php echo $this->uniqId; ?>.on('focus', 'input[name="gl_customerCode[]"]', function(e) {
            var _this = $(this);
            var tr = _this.closest("tr");   
            var isHoverSelect = false;
            _this.autocomplete({
                minLength: 1,
                maxShowItems: 30,
                delay: 300,
                highlightClass: "lookup-ac-highlight", 
                appendTo: "body",
                position: {my : "left top", at: "left bottom", collision: "flip flip"}, 
                autoFocus: false,
                source: function(request, response) {
                    
                    if (accountAutoCompleteRequest != null) {
                        accountAutoCompleteRequest.abort();
                        accountAutoCompleteRequest = null;
                    }
            
                    accountAutoCompleteRequest = $.ajax({
                        type: 'post',
                        url: 'mdgl/autoCompleteByCustomerCode',
                        dataType: "json",
                        data: {code: request.term},
                        success: function(data) {
                            response($.map(data, function(item) {
                                return {
                                    label: item.CUSTOMER_CODE,
                                    name: item.CUSTOMER_NAME,
                                    data: item
                                };
                            }));
                        }
                    });
                },
                focus: function(event, ui) {
                    if (typeof event.keyCode === 'undefined' || event.keyCode == 0) {
                        isHoverSelect = false;
                    } else {
                        if (event.keyCode == 38 || event.keyCode == 40) {
                            isHoverSelect = true;
                        }
                    }
                    return false;
                },
                open: function() {
                    //$(this).autocomplete('widget').zIndex(99999999999999);
                    return false;
                },
                close: function (event, ui){
                    $(this).autocomplete("option","appendTo","body"); 
                }, 
                select: function(event, ui) {
                    var data = ui.item.data;   
                    if (data != false) {                   
                       if (isHoverSelect || event.originalEvent.originalEvent.type == 'click') {
                            $(tr).find("input[name='gl_customerId[]']").val(data.CUSTOMER_ID);
                            $(tr).find("input[name='gl_customerCode[]']").val(data.CUSTOMER_CODE);
                            $(tr).find("input[name='gl_customerName[]']").val(data.CUSTOMER_NAME);
                            $(tr).find("input[name='gl_keyId[]']").val('');
                        } else {
                            if (ui.item.label === _this.val()) {
                                $(tr).find("input[name='gl_customerId[]']").val(data.CUSTOMER_ID);
                                $(tr).find("input[name='gl_customerCode[]']").val(data.CUSTOMER_CODE);
                                $(tr).find("input[name='gl_customerName[]']").val(data.CUSTOMER_NAME);
                                $(tr).find("input[name='gl_keyId[]']").val('');
                            } else {
                                var origEvent = event;

                                while (origEvent.originalEvent !== undefined){
                                    origEvent = origEvent.originalEvent;
                                }

                                if (origEvent.type === 'click') {
                                    var e = jQuery.Event("keydown");
                                    e.keyCode = e.which = 13;
                                    _this.trigger(e);
                                }
                                event.preventDefault();
                            }
                        }
                   
                    }                               
                    return false;   
                }
            }).autocomplete("instance")._renderItem = function(ul, item) {
                ul.addClass('lookup-ac-render');

                var re = new RegExp("(" + this.term + ")", "gi"),
                    cls = this.options.highlightClass,
                    template = "<span class='" + cls + "'>$1</span>",
                    label = item.label.replace(re, template);

                return $('<li>').append('<div class="lookup-ac-render-code">'+label+'</div><div class="lookup-ac-render-name">'+item.name+'</div>').appendTo(ul);
            };
        });
        $glLoadWindow_<?php echo $this->uniqId; ?>.on('focus', 'input[name="gl_customerName[]"]', function(e) {
            var _this = $(this);
            var tr = _this.closest("tr");  
            var isHoverSelect = false;
            _this.autocomplete({
                minLength: 1,
                maxShowItems: 30,
                delay: 300,
                highlightClass: "lookup-ac-highlight", 
                appendTo: "body",
                position: {my : "left top", at: "left bottom", collision: "flip flip"}, 
                autoFocus: false,
                source: function(request, response) {
                    
                    if (accountAutoCompleteRequest != null) {
                        accountAutoCompleteRequest.abort();
                        accountAutoCompleteRequest = null;
                    }
            
                    accountAutoCompleteRequest = $.ajax({
                        type: 'post',
                        url: 'mdgl/autoCompleteByCustomerCode',
                        dataType: "json",
                        data: {name: request.term},
                        success: function(data) {
                            response($.map(data, function(item) {
                                return {
                                    label: item.CUSTOMER_CODE,
                                    name: item.CUSTOMER_NAME,
                                    data: item
                                };
                            }));
                        }
                    });
                },
                focus: function(event, ui) {
                    if (typeof event.keyCode === 'undefined' || event.keyCode == 0) {
                        isHoverSelect = false;
                    } else {
                        if (event.keyCode == 38 || event.keyCode == 40) {
                            isHoverSelect = true;
                        }
                    }
                    return false;
                },
                open: function() {
                    //$(this).autocomplete('widget').zIndex(99999999999999);
                    return false;
                },
                close: function (event, ui){
                    $(this).autocomplete("option","appendTo","body"); 
                }, 
                select: function(event, ui) {
                    var data = ui.item.data;   
                    if (data != false) {
                        if (isHoverSelect || event.originalEvent.originalEvent.type == 'click') {
                            $(tr).find("input[name='gl_customerId[]']").val(data.CUSTOMER_ID);
                            $(tr).find("input[name='gl_customerCode[]']").val(data.CUSTOMER_CODE);
                            $(tr).find("input[name='gl_customerName[]']").val(data.CUSTOMER_NAME);
                            $(tr).find("input[name='gl_keyId[]']").val('');
                        } else {
                            if (ui.item.label === _this.val()) {
                                $(tr).find("input[name='gl_customerId[]']").val(data.CUSTOMER_ID);
                                $(tr).find("input[name='gl_customerCode[]']").val(data.CUSTOMER_CODE);
                                $(tr).find("input[name='gl_customerName[]']").val(data.CUSTOMER_NAME);
                                $(tr).find("input[name='gl_keyId[]']").val('');
                            } else {
                                var origEvent = event;

                                while (origEvent.originalEvent !== undefined){
                                    origEvent = origEvent.originalEvent;
                                }

                                if (origEvent.type === 'click') {
                                    var e = jQuery.Event("keydown");
                                    e.keyCode = e.which = 13;
                                    _this.trigger(e);
                                }
                                event.preventDefault();
                            }
                        }       
                    }                                  
                    return false;   
                }
            }).autocomplete("instance")._renderItem = function(ul, item) {
                ul.addClass('lookup-ac-render');

                var re = new RegExp("(" + this.term + ")", "gi"),
                    cls = this.options.highlightClass,
                    template = "<span class='" + cls + "'>$1</span>",
                    label = item.label.replace(re, template);

                return $('<li>').append('<div class="lookup-ac-render-code">'+label+'</div><div class="lookup-ac-render-name">'+item.name+'</div>').appendTo(ul);
            };
        });
        $glLoadWindow_<?php echo $this->uniqId; ?>.on("keydown", 'input[name="gl_customerCode[]"]', function(e){
            var code = (e.keyCode ? e.keyCode : e.which);
            var _this = $(this);
            var tr = _this.closest("tr");  
            
            if (code === 13) {
                if (_this.data("ui-autocomplete")) {
                    _this.autocomplete("destroy");
                } 
                
                $.ajax({
                    type: 'post',
                    url: 'mdgl/getCustomerInfo',
                    data: {code: _this.val()},
                    dataType: "json",
                    async: false,
                    beforeSend: function () {
                        _this.addClass("spinner2");
                    },
                    success: function (data) {
                        if (!($.isArray(data))) {
                            $(tr).find("input[name='gl_customerId[]']").val(data.CUSTOMER_ID);
                            $(tr).find("input[name='gl_customerCode[]']").val(data.CUSTOMER_CODE);
                            $(tr).find("input[name='gl_customerName[]']").val(data.CUSTOMER_NAME);
                            $(tr).find("input[name='gl_keyId[]']").val('');
                        } else {
                            $(tr).find("input[name='gl_customerId[]']").val('');
                            $(tr).find("input[name='gl_customerCode[]']").val('');
                            $(tr).find("input[name='gl_customerName[]']").val('');
                            $(tr).find("input[name='gl_keyId[]']").val('');
                        }
                        _this.removeClass("spinner2");
                    }
                });
                
                /**
                 * Enter Key eer дараагийн TD-д шилжих
                 */
                if (_this.closest('td').next().find('select').length) {
                    _this.closest('td').next().find('select').select();
                } else if (_this.closest('td').next().nextAll('td:visible:first').length && typeof _this.closest('td').next().nextAll('td:visible:first').find('input[type="text"]').attr('readonly') === 'undefined') {
                    _this.closest('td').next().nextAll('td:visible:first').find('input[type="text"]').select();
                } else {
                    $(glBpMainWindow_<?php echo $this->uniqId; ?>).find('input#glquickCode').select();
                }
                
                return false;
            } else {
                if (!$(this).data("ui-autocomplete")) {
                    $(this).trigger('focus');
                }
            }
        });
        $glLoadWindow_<?php echo $this->uniqId; ?>.on("keydown", 'input[name="gl_customerName[]"]', function(e){
            var code = (e.keyCode ? e.keyCode : e.which);
            var _this = $(this);
            var tr = _this.closest("tr");  
            
            if (code === 13) {
                if (_this.data("ui-autocomplete")) {
                    _this.autocomplete("destroy");
                } 

                $.ajax({
                    type: 'post',
                    url: 'mdgl/getCustomerInfo',
                    data: {name: _this.val()},
                    dataType: "json",
                    async: false,
                    beforeSend: function () {
                        _this.addClass("spinner2");
                    },
                    success: function (data) {
                        if (!($.isArray(data))) {
                            $(tr).find("input[name='gl_customerId[]']").val(data.CUSTOMER_ID);
                            $(tr).find("input[name='gl_customerCode[]']").val(data.CUSTOMER_CODE);
                            $(tr).find("input[name='gl_customerName[]']").val(data.CUSTOMER_NAME);
                            $(tr).find("input[name='gl_keyId[]']").val('');
                        } else {
                            $(tr).find("input[name='gl_customerId[]'], input[name='gl_customerCode[]'], input[name='gl_customerName[]'], input[name='gl_keyId[]']").val('');
                        }
                        _this.removeClass("spinner2");
                    }
                });
                
                /**
                 * Enter Key eer дараагийн TD-д шилжих
                 */                
                if (_this.closest('td').next().find('select').length) {
                    _this.closest('td').next().find('select').select();
                } else if (_this.closest('td').next().nextAll('td:visible:first').length && typeof _this.closest('td').next().nextAll('td:visible:first').find('input[type="text"]').attr('readonly') === 'undefined') {
                    _this.closest('td').next().nextAll('td:visible:first').find('input[type="text"]').select();
                } else {
                    $(glBpMainWindow_<?php echo $this->uniqId; ?>).find('input#glquickCode').select();
                }            
                
                return false;
            } else {
                if (!$(this).data("ui-autocomplete")) {
                    $(this).trigger('focus');
                }
            }
        });
        $glLoadWindow_<?php echo $this->uniqId; ?>.on("change", 'select.gl-row-currency', function(e){
            var _this = $(this);
            var tr = _this.closest('tr');
            
            if (_this.val() != '') {
            
                var bookDate = $("input[name='glbookDate']", glEntryWindowId_<?php echo $this->uniqId; ?>).val();
                if (typeof bookDate === 'undefined') {
                   bookDate = $("input[name='hidden_glbookDate']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
                }

                var rate = getCurrencyRate_<?php echo $this->uniqId; ?>(bookDate, _this.val());
                
                tr.find("input[name='gl_rate[]']").autoNumeric('set', rate);
                tr.closest('table').find("th.glRowRate, td.glRowRate").show();
                
                tr.find("input[data-input-name='debitAmountBase'], input[data-input-name='creditAmountBase']").removeAttr('readonly');
                
            } else {
                tr.find("input[name='gl_rate[]']").autoNumeric('set', 1);
                tr.find("input[data-input-name='debitAmountBase'], input[data-input-name='creditAmountBase']").attr('readonly', 'readonly').autoNumeric('set', 0);
            }
            
            checkIsUseBase_<?php echo $this->uniqId; ?>(tr);
            
            setEqualizedAmount_<?php echo $this->uniqId; ?>(tr);
        });
        $glLoadWindow_<?php echo $this->uniqId; ?>.on('click', '.gl-vat-deduction', function(){
            
            var $this = $(this);
            if ($this.is(':checked')) {
                
                var $row = $this.closest('tr'), 
                    debitAmount = Number($row.find('input[name="gl_debitAmount[]"]').val()), 
                    creditAmount = Number($row.find('input[name="gl_creditAmount[]"]').val());
                
                if (debitAmount == 0 && creditAmount == 0) {
                    return false;
                }
                
                $this.closest('tbody').find('tr:last').find('td.gl-action-column').empty().append("<div class='btn btn-xs red gl-row-remove' title='Устгах' onclick='removeGlDtl_<?php echo $this->uniqId; ?>(this);'><i class='far fa-trash'></i></div>");
                
                var mainWindow = $this.closest('form'), 
                    subId = $row.find('input[name="gl_subid[]"]').val(), 
                    bookDate = $("input[name='glbookDate']", glEntryWindowId_<?php echo $this->uniqId; ?>).val(), 
                    bookDescription = $("#gldescription", glEntryWindowId_<?php echo $this->uniqId; ?>).val();
            
                if (typeof bookDate === 'undefined') {
                    bookDate = $("input[name='hidden_glbookDate']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
                    bookDescription = $("#gldescription", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
                }

                var accountCodeField = '<div class="input-group"><input type="hidden" name="gl_accountId[]"><input type="text" name="gl_accountCode[]" id="gl_accountCode" class="form-control form-control-sm text-center accountCodeMask"><span class="input-group-btn"><button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewCustomSelectableGrid(\'<?php echo Mdgl::$accountListDataViewCode; ?>\', \'single\', \'accountSelectabledGrid_<?php echo $this->uniqId; ?>\', \'\', this);"><i class="fa fa-search"></i></button></span></div>';
                var rate = 1, dtAmt = 0, ktAmt = 0, dtAmtFcy = 0, ktAmtFcy = 0;
                var customerId = '', customerCode = '', customerName = '';
                
                var isGlCustomerCopy = $glLoadWindow_<?php echo $this->uniqId; ?>.find('input.is-gl-customer-copy').is(':checked');
                
                if (isGlCustomerCopy) {
                    var savedCustomerId = $row.find('input[name="gl_customerId[]"]').val();

                    if (savedCustomerId != '') {
                        customerId = savedCustomerId; 
                        customerCode = $row.find('input[name="gl_customerCode[]"]').val();
                        customerName = htmlentities($row.find('input[name="gl_customerName[]"]').val()); 
                    }
                }

                var customerField = '<div class="input-group double-between-input">\n\
                                        <input type="hidden" name="gl_customerId[]" value="'+customerId+'">\n\
                                        <input type="text" id="gl_customerCode" name="gl_customerCode[]" value="'+customerCode+'" class="form-control form-control-sm text-center" title="'+customerCode+'" placeholder="<?php echo $this->lang->line('code_search'); ?>" style="width:80px;max-width:80px;">\n\
                                        <span class="input-group-btn">\n\
                                            <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick=\"dataViewCustomSelectableGrid(\'<?php echo Mdgl::$customerListDataViewCode; ?>\', \'single\', \'customerSelectabledGrid\', \'\', this);\"><i class="fa fa-search"></i></button>\n\
                                        </span>\n\
                                        <span class="input-group-btn">\n\
                                            <input type="text" id="gl_customerName" value="'+customerName+'" title="'+customerName+'" name="gl_customerName[]" class="form-control form-control-sm text-center" placeholder="<?php echo $this->lang->line('name_search'); ?>">\n\
                                        </span>\n\
                                    </div>';

                var currencyDropDown = '', baseReadonly = '', rateReadonly = '', expenseCenterField = '';

                <?php
                if (isset($this->isGlRateDisabled)) {
                    echo "rateReadonly = \"readonly='readonly'\";";
                }
                ?>
             
                if (debitAmount > creditAmount) {
                    dtAmt = 0;
                    ktAmt = glTwoRound(debitAmount / 1.1);
                } else {
                    dtAmt = glTwoRound(creditAmount / 1.1);
                    ktAmt = 0;
                }
                            
                var rowAttr = {
                    subId: subId, 
                    lastIndex: 1, 
                    accounttypeid: '', 
                    objectid: '', 
                    accounttypecode: '', 
                    isusedetailbook: '', 
                    currencycode: '', 
                    accountCodeField: accountCodeField, 
                    accountname: '', 
                    customerField: customerField, 
                    expenseCenterField: expenseCenterField, 
                    bookDescription: bookDescription, 
                    currencyDropDown: currencyDropDown, 
                    rate: rate, 
                    rateReadonly: rateReadonly, 
                    dtAmtFcy: dtAmtFcy, 
                    baseReadonly: baseReadonly, 
                    dtAmt: dtAmt, 
                    ktAmtFcy: ktAmtFcy, 
                    ktAmt: ktAmt, 
                    keyId: '', 
                    rowType: 'vat', 
                    metas: '', 
                    actions: '', 
                    isdebit: '', 
                    ismetas: ''
                };
                
                mainWindow.find('table#glDtl > tbody > tr.gl-selected-row').removeClass('gl-selected-row');
                
                var newRowHtml = glRowAppend_<?php echo $this->uniqId; ?>(rowAttr);
                
                if (debitAmount > creditAmount) {
                    dtAmt = 0;
                    ktAmt = glTwoRound(debitAmount - ktAmt);
                } else {
                    dtAmt = glTwoRound(creditAmount - dtAmt);
                    ktAmt = 0;
                }
                
                var rowSubAttr = {
                    subId: subId, 
                    lastIndex: 1, 
                    accounttypeid: '', 
                    objectid: '', 
                    accounttypecode: '', 
                    isusedetailbook: '', 
                    currencycode: '', 
                    accountCodeField: accountCodeField, 
                    accountname: '', 
                    customerField: customerField, 
                    expenseCenterField: expenseCenterField, 
                    bookDescription: bookDescription, 
                    currencyDropDown: currencyDropDown, 
                    rate: rate, 
                    rateReadonly: rateReadonly, 
                    dtAmtFcy: dtAmtFcy, 
                    baseReadonly: baseReadonly, 
                    dtAmt: dtAmt, 
                    ktAmtFcy: ktAmtFcy, 
                    ktAmt: ktAmt, 
                    keyId: '', 
                    rowType: 'vat', 
                    metas: '', 
                    actions: '', 
                    isdebit: '', 
                    ismetas: ''
                };
                
                newRowHtml += glRowAppend_<?php echo $this->uniqId; ?>(rowSubAttr);
                
                $row.after(newRowHtml);
                
                var $addedRows = mainWindow.find('table#glDtl > tbody > tr.gl-new-row');
                
                Core.initNumberInput($addedRows);
                Core.initAccountCodeMask($addedRows);
                
                $addedRows.removeClass('gl-new-row');
                
                mainWindow.find('table#glDtl > tbody > tr[data-sub-id]').each(function(i){
                    $(this).attr('data-row-index', i);
                });
                
                checkRowDescriptionField_<?php echo $this->uniqId; ?>($this);
                checkIsUseBase_<?php echo $this->uniqId; ?>($this); 

                glTableFreeze_<?php echo $this->uniqId; ?>();
                bpSetGlMetaRowIndex(glBpMainWindow_<?php echo $this->uniqId; ?>);
                
                var $secondNextAccount = $row.next('tr:eq(0)').next('tr:eq(0)').find("input[name='gl_accountCode[]']");
                var CONFIG_GL_VAT_DEDUCTION_DEBIT, CONFIG_GL_VAT_DEDUCTION_CREDIT;
                
                if (typeof $row.attr('data-account-departmentid') !== 'undefined' && $row.attr('data-account-departmentid')) {
                    var response = $.ajax({
                        type: 'post',
                        url: 'mdgl/getConfigGlDeduction/' + $row.attr('data-account-departmentid'),
                        dataType: 'json',
                        async: false
                    });

                    CONFIG_GL_VAT_DEDUCTION_DEBIT = response.responseJSON.CONFIG_GL_VAT_DEDUCTION_DEBIT;
                    CONFIG_GL_VAT_DEDUCTION_CREDIT = response.responseJSON.CONFIG_GL_VAT_DEDUCTION_CREDIT;
                } else {
                    CONFIG_GL_VAT_DEDUCTION_DEBIT = '<?php echo Config::getFromCache('CONFIG_GL_VAT_DEDUCTION_DEBIT'); ?>';
                    CONFIG_GL_VAT_DEDUCTION_CREDIT = '<?php echo Config::getFromCache('CONFIG_GL_VAT_DEDUCTION_CREDIT'); ?>';
                }
                
                if (dtAmt > 0) {
                    $secondNextAccount.val(CONFIG_GL_VAT_DEDUCTION_DEBIT);
                } else {
                    $secondNextAccount.val(CONFIG_GL_VAT_DEDUCTION_CREDIT);
                }
                
                if (CONFIG_GL_VAT_DEDUCTION_DEBIT && CONFIG_GL_VAT_DEDUCTION_CREDIT) {
                    $secondNextAccount.focus();
                    
                    setTimeout(function(){
                        var e = jQuery.Event('keydown');
                        e.keyCode = e.which = 13;
                        $secondNextAccount.trigger(e); 
                    }, 5);
                } else {
                    $row.next('tr:eq(0)').find("input[name='gl_accountCode[]']").focus();
                }
            }
        }); 
        $glLoadWindow_<?php echo $this->uniqId; ?>.on('click', '.gl-incometax-deduction', function(){
            
            var $this = $(this);
            if ($this.is(':checked')) {
                
                var $row = $this.closest('tr'), 
                    debitAmount = Number($row.find('input[name="gl_debitAmount[]"]').val()), 
                    creditAmount = Number($row.find('input[name="gl_creditAmount[]"]').val());
                
                if (debitAmount == 0 && creditAmount == 0) {
                    return false;
                }
                
                $this.closest('tbody').find('tr:last').find('td.gl-action-column').empty().append("<div class='btn btn-xs red gl-row-remove' title='Устгах' onclick='removeGlDtl_<?php echo $this->uniqId; ?>(this);'><i class='far fa-trash'></i></div>");
                
                var mainWindow = $this.closest('form'), 
                    subId = $row.find('input[name="gl_subid[]"]').val(), 
                    bookDate = $("input[name='glbookDate']", glEntryWindowId_<?php echo $this->uniqId; ?>).val(), 
                    bookDescription = $("#gldescription", glEntryWindowId_<?php echo $this->uniqId; ?>).val();
            
                if (typeof bookDate === 'undefined') {
                    bookDate = $("input[name='hidden_glbookDate']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
                    bookDescription = $("#gldescription", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
                }

                var accountCodeField = '<div class="input-group"><input type="hidden" name="gl_accountId[]"><input type="text" name="gl_accountCode[]" id="gl_accountCode" class="form-control form-control-sm text-center accountCodeMask"><span class="input-group-btn"><button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewCustomSelectableGrid(\'<?php echo Mdgl::$accountListDataViewCode; ?>\', \'single\', \'accountSelectabledGrid_<?php echo $this->uniqId; ?>\', \'\', this);"><i class="fa fa-search"></i></button></span></div>';
                var rate = 1, dtAmt = 0, ktAmt = 0, dtAmtFcy = 0, ktAmtFcy = 0;
                var customerId = '', customerCode = '', customerName = '';
                
                var isGlCustomerCopy = $glLoadWindow_<?php echo $this->uniqId; ?>.find('input.is-gl-customer-copy').is(':checked');
                
                if (isGlCustomerCopy) {
                    var savedCustomerId = $row.find('input[name="gl_customerId[]"]').val();

                    if (savedCustomerId != '') {
                        customerId = savedCustomerId; 
                        customerCode = $row.find('input[name="gl_customerCode[]"]').val();
                        customerName = htmlentities($row.find('input[name="gl_customerName[]"]').val()); 
                    }
                }

                var customerField = '<div class="input-group double-between-input">\n\
                                        <input type="hidden" name="gl_customerId[]" value="'+customerId+'">\n\
                                        <input type="text" id="gl_customerCode" name="gl_customerCode[]" value="'+customerCode+'" class="form-control form-control-sm text-center" title="'+customerCode+'" placeholder="<?php echo $this->lang->line('code_search'); ?>" style="width:80px;max-width:80px;">\n\
                                        <span class="input-group-btn">\n\
                                            <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick=\"dataViewCustomSelectableGrid(\'<?php echo Mdgl::$customerListDataViewCode; ?>\', \'single\', \'customerSelectabledGrid\', \'\', this);\"><i class="fa fa-search"></i></button>\n\
                                        </span>\n\
                                        <span class="input-group-btn">\n\
                                            <input type="text" id="gl_customerName" value="'+customerName+'" title="'+customerName+'" name="gl_customerName[]" class="form-control form-control-sm text-center" placeholder="<?php echo $this->lang->line('name_search'); ?>">\n\
                                        </span>\n\
                                    </div>';

                var currencyDropDown = '', baseReadonly = '', rateReadonly = '', expenseCenterField = '';

                <?php
                if (isset($this->isGlRateDisabled)) {
                    echo "rateReadonly = \"readonly='readonly'\";";
                }
                ?>
             
                if (debitAmount > creditAmount) {
                    dtAmt = 0;
                    ktAmt = glRound(debitAmount * 0.1);
                } else {
                    dtAmt = 0;
                    ktAmt = glRound(creditAmount * 100 / 90) - creditAmount;
                }
                            
                var rowAttr = {
                    subId: subId, 
                    lastIndex: 1, 
                    accounttypeid: '', 
                    objectid: '', 
                    accounttypecode: '', 
                    isusedetailbook: '', 
                    currencycode: '', 
                    accountCodeField: accountCodeField, 
                    accountname: '', 
                    customerField: customerField, 
                    expenseCenterField: expenseCenterField, 
                    bookDescription: bookDescription, 
                    currencyDropDown: currencyDropDown, 
                    rate: rate, 
                    rateReadonly: rateReadonly, 
                    dtAmtFcy: dtAmtFcy, 
                    baseReadonly: baseReadonly, 
                    dtAmt: dtAmt, 
                    ktAmtFcy: ktAmtFcy, 
                    ktAmt: ktAmt, 
                    keyId: '', 
                    rowType: 'vat', 
                    metas: '', 
                    actions: '', 
                    isdebit: '', 
                    ismetas: ''
                };
                
                mainWindow.find('table#glDtl > tbody > tr.gl-selected-row').removeClass('gl-selected-row');
                
                var newRowHtml = glRowAppend_<?php echo $this->uniqId; ?>(rowAttr);
                
                if (debitAmount > creditAmount) {
                    dtAmt = 0;
                    ktAmt = debitAmount - ktAmt;
                } else {
                    dtAmt = glRound(creditAmount * 100 / 90);
                    ktAmt = 0;
                }
                
                var rowSubAttr = {
                    subId: subId, 
                    lastIndex: 1, 
                    accounttypeid: '', 
                    objectid: '', 
                    accounttypecode: '', 
                    isusedetailbook: '', 
                    currencycode: '', 
                    accountCodeField: accountCodeField, 
                    accountname: '', 
                    customerField: customerField, 
                    expenseCenterField: expenseCenterField, 
                    bookDescription: bookDescription, 
                    currencyDropDown: currencyDropDown, 
                    rate: rate, 
                    rateReadonly: rateReadonly, 
                    dtAmtFcy: dtAmtFcy, 
                    baseReadonly: baseReadonly, 
                    dtAmt: dtAmt, 
                    ktAmtFcy: ktAmtFcy, 
                    ktAmt: ktAmt, 
                    keyId: '', 
                    rowType: 'vat', 
                    metas: '', 
                    actions: '', 
                    isdebit: '', 
                    ismetas: ''
                };
                
                newRowHtml += glRowAppend_<?php echo $this->uniqId; ?>(rowSubAttr);
                
                $row.after(newRowHtml);
                
                var $addedRows = mainWindow.find('table#glDtl > tbody > tr.gl-new-row');
                
                Core.initNumberInput($addedRows);
                Core.initAccountCodeMask($addedRows);
                
                $addedRows.removeClass('gl-new-row');
                
                mainWindow.find('table#glDtl > tbody > tr[data-sub-id]').each(function(i){
                    $(this).attr('data-row-index', i);
                });
                
                checkRowDescriptionField_<?php echo $this->uniqId; ?>($this);
                checkIsUseBase_<?php echo $this->uniqId; ?>($this); 

                glTableFreeze_<?php echo $this->uniqId; ?>();
                bpSetGlMetaRowIndex(glBpMainWindow_<?php echo $this->uniqId; ?>);
                
                <?php
                if (Config::getFromCache('CONFIG_GL_INCOMETAX_DEDUCTION')) {
                ?>
                    var $secondNextAccount = $row.next('tr:eq(0)').find("input[name='gl_accountCode[]']");
                    
                    $secondNextAccount.val('<?php echo Config::getFromCache('CONFIG_GL_INCOMETAX_DEDUCTION'); ?>');
                    
                    $secondNextAccount.focus();
                    
                    setTimeout(function(){
                        var e = jQuery.Event('keydown');
                        e.keyCode = e.which = 13;
                        $secondNextAccount.trigger(e); 
                    }, 5);
                <?php
                } else {
                ?>
                $row.next('tr:eq(0)').find("input[name='gl_accountCode[]']").focus();
                <?php
                }
                ?>               
            }
        }); 
        $glLoadWindow_<?php echo $this->uniqId; ?>.on('keydown', '.gl-add-emptyrows-input', function(e){
            var code = (e.keyCode ? e.keyCode : e.which);
            if (code == 13) {
                var $parent = $(this).closest('.input-group');
                $parent.find('button').click();
            }
        });
        
        <?php 
        if (isset($this->glTemplateExpression)) {
            echo $this->glTemplateExpression;
        } 
        ?>
        
        $glLoadWindow_<?php echo $this->uniqId; ?>.on('change', '.gl-template-change', function(){
            var $this = $(this);
            var templateId = $this.val();
            
            <?php
            if (!isset($this->dataViewId)) {
            ?>
            $.ajax({
                type: 'post',
                url: 'mdgl/getTemplate',
                data: $this.closest('form').not(".glTemplateSectionProcess input").serialize() + "&glBpMainWindowIdProcess=_&bpTabLength=1&uniqId=<?php echo $this->uniqId; ?>&glTemplateId="+templateId,
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function (data) {
                    PNotify.removeAll();
                    
                    if (data.status == 'success') {
                        
                        $glLoadWindow_<?php echo $this->uniqId; ?>.find('.gl-template-name').html($this.find('option:selected').text());
                        
                        var $html = $('<div />', {html: data.Html});
                        var $table = $html.find('#glDtl');
                        
                        $glLoadWindow_<?php echo $this->uniqId; ?>.find('#glDtl > tbody').empty().append($table.find('tbody').html());
                        
                        var $glDtlTbl = $glLoadWindow_<?php echo $this->uniqId; ?>.find('#glDtl > tbody');
                        Core.initAccountCodeMask($glDtlTbl);
        
                        $glDtlTbl.find('.bigdecimalInit').autoNumeric('init', {aPad: true, mDec: 2, vMin: '-999999999999999999999999999999.999999999999999999999999999999', vMax: '999999999999999999999999999999.999999999999999999999999999999'});

                        checkIsUseBase_<?php echo $this->uniqId; ?>($glDtlTbl);
                        checkRowDescriptionField_<?php echo $this->uniqId; ?>($glDtlTbl);

                        checkIsUseGlDetail_<?php echo $this->uniqId; ?>();
        
                    } else {
                        new PNotify({
                            title: 'Error',
                            text: data.text,
                            type: 'error',
                            addclass: pnotifyPosition,
                            sticker: false
                        });
                    }
                    Core.unblockUI();
                },
                error: function () { alert("Error"); }
            });
            
            <?php
            } elseif (isset($this->postJson)) {
            ?>
                        
            var postJson = <?php echo $this->postJson; ?>;
            postJson['glTemplateId'] = templateId;
            postJson['uniqId'] = <?php echo $this->uniqId; ?>;
            
            $.ajax({
                type: 'post',
                url: 'mdgl/popupConnectGL',
                data: postJson,
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function (data) {
                    PNotify.removeAll();
                    
                    if (data.status == 'success') {
                        
                        $glLoadWindow_<?php echo $this->uniqId; ?>.find('.gl-template-name').html($this.find('option:selected').text());
                        
                        var $html = $('<div />', {html: data.html});
                        var $table = $html.find('#glDtl');
                        
                        $glLoadWindow_<?php echo $this->uniqId; ?>.find('#glDtl > tbody').empty().append($table.find('tbody').html());
                        
                        var $glDtlTbl = $glLoadWindow_<?php echo $this->uniqId; ?>.find('#glDtl > tbody');
                        Core.initAccountCodeMask($glDtlTbl);
        
                        $glDtlTbl.find('.bigdecimalInit').autoNumeric('init', {aPad: true, mDec: 2, vMin: '-999999999999999999999999999999.999999999999999999999999999999', vMax: '999999999999999999999999999999.999999999999999999999999999999'});

                        checkIsUseBase_<?php echo $this->uniqId; ?>($glDtlTbl);
                        checkRowDescriptionField_<?php echo $this->uniqId; ?>($glDtlTbl);

                        checkIsUseGlDetail_<?php echo $this->uniqId; ?>();
        
                    } else {
                        new PNotify({
                            title: 'Error',
                            text: data.text,
                            type: 'error',
                            addclass: pnotifyPosition,
                            sticker: false
                        });
                    }
                    Core.unblockUI();
                },
                error: function () { alert("Error"); }
            });
            <?php
            }
            ?>            
        });
        
        $(glEntryWindowId_<?php echo $this->uniqId; ?>).on('click', '.glhdr-descr-to-dtl', function(){
            var descr = $("#gldescription", glEntryWindowId_<?php echo $this->uniqId; ?>).val();        
            $("input[name='gl_rowdescription[]']", glEntryWindowId_<?php echo $this->uniqId; ?>).val(descr);          
        });

        if ($('select[name="param[internalTransactionTypeId]"]').length && $('input[name="param[targetId]"]').length && '<?php echo Config::getFromCache('config_fin_internalTransactionTypeId'); ?>' == '1') {
            if ($('select[name="param[internalTransactionTypeId]"]').val() !== '' && $('input[name="param[targetId]"]').val() !== '') {
                var e = jQuery.Event("keydown");
                e.keyCode = e.which = 13;                
                $('table#glDtl > tbody > tr:last-child', glBpMainWindow_<?php echo $this->uniqId; ?>).find('input[name="gl_accountCode[]"]').trigger(e);
            }
        }
        
    });
    
    function accountSelectabledGridForMain_<?php echo $this->uniqId; ?>(metaDataCode, chooseType, elem, rows) {
        var row = rows[0];
        addGlDtlWithAccountValue_<?php echo $this->uniqId; ?>(row, elem, 'grid');
    }
    function accountSelectabledGrid_<?php echo $this->uniqId; ?>(metaDataCode, chooseType, elem, rows) {
        var bookDate = $("input[name='glbookDate']", glEntryWindowId_<?php echo $this->uniqId; ?>).val();
        if (typeof bookDate === 'undefined') {
           bookDate = $("input[name='hidden_glbookDate']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
        }
        var row = rows[0];
        var tr = $(elem).closest('tr');
        
        if ($(tr).find("input[name='gl_isGetLoad[]']").val() != '1') {
            $(tr).find("td:first-child").find("input[type='hidden']").val('');
        }
        
        if (isIgnoreUseDetail_<?php echo $this->uniqId; ?>) {
            row.isusedetailbook = 0;
        }
        
        $(tr).find("input[name='gl_accountId[]']").val(row.id);
        $(tr).find("input[name='gl_accountCode[]']").val(row.accountcode);
        $(tr).find("input[name='gl_accountName[]']").val(row.accountname).attr('title', row.accountname);
        $(tr).find("input[name='gl_main_accounttypeid[]']").val(row.accounttypeid);
        $(tr).find("input[name='gl_accounttypeCode[]']").val(row.accounttypecode);
        $(tr).find("input[name='gl_objectId[]']").val(row.objectid);
        $(tr).find("input[name='gl_useDetailBook[]']").val(row.isusedetailbook);
        
        checkIsUseDetail_<?php echo $this->uniqId; ?>(row.isusedetailbook, tr);
        
        var lowerCurrencyCode = (row.currencycode).toLowerCase();
        <?php
        if (isset($this->oppRate)) {
        ?>
        if (lowerCurrencyCode == '<?php echo $this->oppCurrencyCode; ?>') {
            var rate = '<?php echo $this->oppRate; ?>';
        } else {
            var rate = getAccountRate_<?php echo $this->uniqId; ?>(bookDate, row.id, lowerCurrencyCode);
        }    
        <?php
        } else {
        ?>
        var rate = getAccountRate_<?php echo $this->uniqId; ?>(bookDate, row.id, lowerCurrencyCode); 
        <?php
        }
        ?>
                
        $(tr).find("input[name='gl_rate[]']").autoNumeric('set', rate);
        $(tr).find("input[name='gl_rate_currency[]']").val(row.currencycode);
        $(tr).find("input[name='gl_isdebit[]']").val('');
        
        var isDebit = 1;
        var debit = Number($(tr).find("input[name='gl_debitAmount[]']").val());
        var credit = Number($(tr).find("input[name='gl_creditAmount[]']").val());
        if (credit > debit) {
            isDebit = 0;
        }
        
        var debitbase = (rate == '1') ? 0 : glRound(debit / rate);
        var creditbase = (rate == '1') ? 0 : glRound(credit / rate);
        
        $(tr).find("input[name='gl_isdebit[]']").val(isDebit);
        $(tr).find("input[name='gl_debitAmountBase[]']").val(debitbase);
        $(tr).find("input[name='gl_creditAmountBase[]']").val(creditbase);
        $(tr).find("input[data-input-name='debitAmountBase']").autoNumeric("set", debitbase);
        $(tr).find("input[data-input-name='creditAmountBase']").autoNumeric("set", creditbase);
        
        var trLast = $(elem).closest('tr');
        
        checkIsUseBase_<?php echo $this->uniqId; ?>(trLast);
        glRowExpand_<?php echo $this->uniqId; ?>(trLast, 'expandRemove', '');
        checkAccountFilterConfig_<?php echo $this->uniqId; ?>(tr);
    }
    function customerSelectabledGrid(metaDataCode, chooseType, elem, rows) {
        var row = rows[0], $tr = $(elem).closest('tr');
        $tr.find("input[name='gl_customerId[]']").val(row.id);
        $tr.find("input[name='gl_customerCode[]']").val(row.customercode);
        $tr.find("input[name='gl_customerName[]']").val(row.customername);
    }
    function gridGlInputFormula_<?php echo $this->uniqId; ?>(elem, complete) {
        
        if (typeof $(elem).attr('data-prevent-change') !== 'undefined'){return;}
        
        if ($(elem).hasAttr('data-input-name')) {
            
            var _thisName = elem.attr("data-input-name").replace(/[[]]/g, '');
            var $thisRow = $(elem).closest('tr');
            var glrate = Number($thisRow.find("input[name='gl_rate[]']").autoNumeric("get"));
            var isTwoRound = false;
            
            if (typeof complete == 'undefined') {
                
                var debit = Number($thisRow.find("input[data-input-name='debitAmount']").autoNumeric('get'));
                var credit = Number($thisRow.find("input[data-input-name='creditAmount']").autoNumeric('get'));
                var debitbase = Number($thisRow.find("input[data-input-name='debitAmountBase']").autoNumeric('get'));
                var creditbase = Number($thisRow.find("input[data-input-name='creditAmountBase']").autoNumeric('get'));
                
            } else {
                
                if (complete == 'tworound') {
                    var debit = Number($thisRow.find("input[data-input-name='debitAmount']").autoNumeric('get'));
                    var credit = Number($thisRow.find("input[data-input-name='creditAmount']").autoNumeric('get'));
                    var debitbase = Number($thisRow.find("input[data-input-name='debitAmountBase']").autoNumeric('get'));
                    var creditbase = Number($thisRow.find("input[data-input-name='creditAmountBase']").autoNumeric('get'));
                    isTwoRound = true;
                } else {
                    var debit = Number($thisRow.find("input[name='gl_debitAmount[]']").val());
                    var credit = Number($thisRow.find("input[name='gl_creditAmount[]']").val());
                    var debitbase = Number($thisRow.find("input[name='gl_debitAmountBase[]']").val());
                    var creditbase = Number($thisRow.find("input[name='gl_creditAmountBase[]']").val());
                }
            }
            
            if (_thisName == 'debitAmount') {
                
                if (isTwoRound) {
                    var realdebitbase = (glrate == '1') ? 0 : glTwoRound(debit / glrate);
                } else {
                    var realdebitbase = (glrate == '1') ? 0 : glRound(debit / glrate);
                }
                
                $thisRow.find("input[name='gl_debitAmount[]']").val(debit);
                $thisRow.find("input[name='gl_creditAmount[]']").val(0);
                $thisRow.find("input[name='gl_debitAmountBase[]']").val(realdebitbase);
                $thisRow.find("input[name='gl_creditAmountBase[]']").val(0);
                $thisRow.find("input[data-input-name='creditAmount']").autoNumeric("set", 0);
                $thisRow.find("input[data-input-name='debitAmountBase']").autoNumeric("set", realdebitbase);
                $thisRow.find("input[data-input-name='creditAmountBase']").autoNumeric("set", 0);
                
            } else if (_thisName == 'creditAmount') {
                
                if (isTwoRound) {
                    var realcreditbase = (glrate == '1') ? 0 : glTwoRound(credit / glrate);
                } else {
                    var realcreditbase = (glrate == '1') ? 0 : glRound(credit / glrate);
                }
            
                $thisRow.find("input[name='gl_creditAmount[]']").val(credit);
                $thisRow.find("input[name='gl_debitAmount[]']").val(0);
                $thisRow.find("input[name='gl_creditAmountBase[]']").val(realcreditbase);
                $thisRow.find("input[name='gl_debitAmountBase[]']").val(0);
                $thisRow.find("input[data-input-name='debitAmount']").autoNumeric("set", 0);
                $thisRow.find("input[data-input-name='creditAmountBase']").autoNumeric("set", realcreditbase);
                $thisRow.find("input[data-input-name='debitAmountBase']").autoNumeric("set", 0);
                
            } else if (_thisName == 'debitAmountBase') {
                
                if (isTwoRound) {
                    var realdebitAmount = glTwoRound(debitbase * glrate);
                } else {
                    var realdebitAmount = glRound(debitbase * glrate);
                }
                
                $thisRow.find("input[name='gl_debitAmountBase[]']").val(debitbase);
                $thisRow.find("input[name='gl_debitAmount[]']").val(realdebitAmount);
                $thisRow.find("input[name='gl_creditAmountBase[]']").val(0);
                $thisRow.find("input[name='gl_creditAmount[]']").val(0);
                $thisRow.find("input[data-input-name='debitAmount']").autoNumeric("set", realdebitAmount);
                $thisRow.find("input[data-input-name='creditAmountBase']").autoNumeric("set", 0);
                $thisRow.find("input[data-input-name='creditAmount']").autoNumeric("set", 0);
                
            } else if (_thisName == 'creditAmountBase') {
                
                if (isTwoRound) {
                    var realcreditAmount = glTwoRound(creditbase * glrate);
                } else {
                    var realcreditAmount = glRound(creditbase * glrate);
                }
            
                $thisRow.find("input[name='gl_creditAmountBase[]']").val(creditbase);
                $thisRow.find("input[name='gl_debitAmount[]']").val(0);
                $thisRow.find("input[name='gl_debitAmountBase[]']").val(0);
                $thisRow.find("input[name='gl_creditAmount[]']").val(realcreditAmount);
                $thisRow.find("input[data-input-name='debitAmount']").autoNumeric("set", 0);
                $thisRow.find("input[data-input-name='debitAmountBase']").autoNumeric("set", 0);
                $thisRow.find("input[data-input-name='creditAmount']").autoNumeric("set", realcreditAmount);
            }
            
            var isDebit = 1;
            var debit = Number($thisRow.find("input[data-input-name='debitAmount']").autoNumeric("get"));
            var credit = Number($thisRow.find("input[data-input-name='creditAmount']").autoNumeric("get"));
            
            if (credit > debit) {
                isDebit = 0;
            }
            
            $thisRow.find("input[name='gl_isdebit[]']").val(isDebit);
        }
    }
    function completeGlAmount_<?php echo $this->uniqId; ?>(elem) {
        if ($(elem).hasAttr('data-input-name')) {
            
            var _thisName = $(elem).attr("data-input-name").replace(/[[]]/g, '');
            var _thisRow = $(elem).closest("tr");
            var glrate = $(_thisRow).find("input[name='gl_rate[]']").autoNumeric("get");
            var subid = $(_thisRow).find("input[name='gl_subid[]']").val();
            var debitsum = $("#glDtl", glBpMainWindow_<?php echo $this->uniqId; ?>).find("input[data-input-name='foot_sum_debitamount']").val();
            var creditsum = $("#glDtl", glBpMainWindow_<?php echo $this->uniqId; ?>).find("input[data-input-name='foot_sum_creditamount']").val();
            
            if (debitsum != creditsum) {
                if (_thisName === 'debitAmount') {
                    
                    var creditamountsum = 0;
                    var debitamountsum = 0;
                    
                    $('#glDtl > tbody', glBpMainWindow_<?php echo $this->uniqId; ?>).find("> tr").each(function() {
                        
                        var _thisRow = $(this);
                        var creditamount = 0;
                        var debitamount = 0;
                        
                        if (typeof _thisRow.find("input[name='gl_creditAmount[]']").val() != 'undefined' && subid == _thisRow.find("input[name='gl_subid[]']").val()) {
                            creditamount = Number(_thisRow.find("input[name='gl_creditAmount[]']").val());
                            debitamount = Number(_thisRow.find("input[name='gl_debitAmount[]']").val());
                        }
                        creditamountsum = creditamountsum + creditamount;
                        debitamountsum = debitamountsum + debitamount;
                    });
                    
                    debitamountsum = debitamountsum - $(_thisRow).find("input[name='gl_debitAmount[]']").val();
                    creditamountsum = creditamountsum - $(_thisRow).find("input[name='gl_creditAmount[]']").val();
                    var amount = 0;
                    
                    if (creditamountsum > debitamountsum) {
                        amount = creditamountsum - debitamountsum;
                        $(_thisRow).find("input[name='gl_debitAmount[]']").val(amount);
                        $(_thisRow).find("input[data-input-name='debitAmount']").autoNumeric("set", amount);
                        $(_thisRow).find("input[name='gl_debitAmount[]']").val(amount);
                        $(_thisRow).find("input[data-input-name='debitAmount']").autoNumeric("set", amount);
                    } else {
                        amount = debitamountsum - creditamountsum;
                        $(_thisRow).find("input[name='gl_creditAmount[]']").val(amount);
                        $(_thisRow).find("input[data-input-name='creditAmount']").autoNumeric("set", amount);
                        $(_thisRow).find("input[name='gl_creditAmount[]']").val(amount);
                        $(_thisRow).find("input[data-input-name='creditAmount']").autoNumeric("set", amount);
                    }
                }
                if (_thisName === 'creditAmount') {
                    
                    var debitamountsum = 0;
                    var creditamountsum = 0;
                    
                    $('#glDtl > tbody', glBpMainWindow_<?php echo $this->uniqId; ?>).find("> tr").each(function() {
                        var _thisRow = $(this);
                        var debitamount = 0;
                        var creditamount = 0;
                        if (typeof _thisRow.find("input[name='gl_debitAmount[]']").val() != 'undefined' && subid == _thisRow.find("input[name='gl_subid[]']").val()) {
                            debitamount = Number(_thisRow.find("input[name='gl_debitAmount[]']").val());
                            creditamount = Number(_thisRow.find("input[name='gl_creditAmount[]']").val());
                        }
                        debitamountsum = debitamountsum + debitamount;
                        creditamountsum = creditamountsum + creditamount;
                    });
                    
                    creditamountsum = creditamountsum - $(_thisRow).find("input[name='gl_creditAmount[]']").val();
                    debitamountsum = debitamountsum - $(_thisRow).find("input[name='gl_debitAmount[]']").val();
                    var amount = 0;
                    
                    if (debitamountsum > creditamountsum) {
                        amount = debitamountsum - creditamountsum;
                        $(_thisRow).find("input[name='gl_creditAmount[]']").val(amount);
                        $(_thisRow).find("input[data-input-name='creditAmount']").autoNumeric("set", amount);
                        $(_thisRow).find("input[name='gl_creditAmount[]']").val(amount);
                        $(_thisRow).find("input[data-input-name='creditAmount']").autoNumeric("set", amount);
                    } else {
                        amount = creditamountsum - debitamountsum;
                        $(_thisRow).find("input[name='gl_debitAmount[]']").val(amount);
                        $(_thisRow).find("input[data-input-name='debitAmount']").autoNumeric("set", amount);
                        $(_thisRow).find("input[name='gl_debitAmount[]']").val(amount);
                        $(_thisRow).find("input[data-input-name='debitAmount']").autoNumeric("set", amount);
                    }
                }
                if (_thisName === 'debitAmountBase') {
                    
                    var creditamountsum = 0;
                    var debitamountsum = 0;
                    
                    $('#glDtl > tbody', glBpMainWindow_<?php echo $this->uniqId; ?>).find("> tr").each(function() {
                        var _thisRow = $(this);
                        var creditamount = 0;
                        var debitamount = 0;
                        if (typeof _thisRow.find("input[name='gl_creditAmount[]']").val() != 'undefined' && subid == _thisRow.find("input[name='gl_subid[]']").val()) {
                            creditamount = Number(_thisRow.find("input[name='gl_creditAmount[]']").val());
                            debitamount = Number(_thisRow.find("input[name='gl_debitAmount[]']").val());
                        }
                        creditamountsum = creditamountsum + creditamount;
                        debitamountsum = debitamountsum + debitamount;
                    });
                    
                    debitamountsum = debitamountsum - $(_thisRow).find("input[name='gl_debitAmount[]']").val();
                    creditamountsum = creditamountsum - $(_thisRow).find("input[name='gl_creditAmount[]']").val();
                    var amount = 0;
                    
                    if (creditamountsum > debitamountsum) {
                        amount = glRound((creditamountsum - debitamountsum) / glrate);
                        $(_thisRow).find("input[name='gl_debitAmount[]']").val(amount);
                        $(_thisRow).find("input[data-input-name='debitAmount']").autoNumeric("set", amount);
                        $(_thisRow).find("input[name='gl_debitAmount[]']").val(amount);
                        $(_thisRow).find("input[data-input-name='debitAmount']").autoNumeric("set", amount);
                    } else {
                        amount = glRound((debitamountsum - creditamountsum) / glrate);
                        $(_thisRow).find("input[name='gl_creditAmount[]']").val(amount);
                        $(_thisRow).find("input[data-input-name='creditAmount']").autoNumeric("set", amount);
                        $(_thisRow).find("input[name='gl_creditAmount[]']").val(amount);
                        $(_thisRow).find("input[data-input-name='creditAmount']").autoNumeric("set", amount);
                    }
                  
                }
                if (_thisName === 'creditAmountBase') {
                    
                    var debitamountsum = 0;
                    var creditamountsum = 0;
                    
                    $('#glDtl > tbody', glBpMainWindow_<?php echo $this->uniqId; ?>).find("> tr").each(function() {
                        var _thisRow = $(this);
                        var debitamount = 0;
                        var creditamount = 0;
                        if (typeof _thisRow.find("input[name='gl_debitAmount[]']").val() != 'undefined' && subid == _thisRow.find("input[name='gl_subid[]']").val()) {
                            debitamount = Number(_thisRow.find("input[name='gl_debitAmount[]']").val());
                            creditamount = Number(_thisRow.find("input[name='gl_creditAmount[]']").val());
                        }
                        debitamountsum = debitamountsum + debitamount;
                        creditamountsum = creditamountsum + creditamount;
                    });
                    
                    debitamountsum = debitamountsum - $(_thisRow).find("input[name='gl_debitAmount[]']").val();
                    creditamountsum = creditamountsum - $(_thisRow).find("input[name='gl_creditAmount[]']").val();
                    var amount = 0;
                    
                    if (debitamountsum > creditamountsum) {
                        amount = glRound((debitamountsum - creditamountsum) / glrate);
                        $(_thisRow).find("input[name='gl_creditAmount[]']").val(amount);
                        $(_thisRow).find("input[data-input-name='creditAmount']").autoNumeric("set", amount);
                        $(_thisRow).find("input[name='gl_creditAmount[]']").val(amount);
                        $(_thisRow).find("input[data-input-name='creditAmount']").autoNumeric("set", amount);
                    } else {
                        amount = glRound((creditamountsum - debitamountsum) / glrate);
                        $(_thisRow).find("input[name='gl_debitAmount[]']").val(amount);
                        $(_thisRow).find("input[data-input-name='debitAmount']").autoNumeric("set", amount);
                        $(_thisRow).find("input[name='gl_debitAmount[]']").val(amount);
                        $(_thisRow).find("input[data-input-name='debitAmount']").autoNumeric("set", amount);
                    }
                }
                gridGlInputFormula_<?php echo $this->uniqId; ?>($(elem), 'tworound');
                calculateFooterSum_<?php echo $this->uniqId; ?>(elem);
            }
        }
    }
    function completeGlDescription_<?php echo $this->uniqId; ?>(elem) {
        var bookdesc = $("#gldescription", glEntryWindowId_<?php echo $this->uniqId; ?>).val();
        if (typeof bookdesc === 'undefined') {
            var bookdesc = $("input[name='hidden_gldescription']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
        }
        $(elem).val(bookdesc);
    }
    function fillOpMeta_<?php echo $this->uniqId; ?>(rowEl, accountId, subId, isDebit) {
        <?php
        if (isset($this->isCashFlowSubCategoryId)) {
            echo "return 'cashFlowSubCategoryId';"."\n";
        }
        ?>
        var opMeta = '', isDebit = (isDebit == '1') ? '1' : '0';
        var $opMetaAttr = $('#glDtl tr[data-sub-id="'+subId+'"][data-op-meta]:not([data-op-meta=""]):eq(0)', glBpMainWindow_<?php echo $this->uniqId; ?>);
        var $opMetaAttrIsDebit = $opMetaAttr.find("input[name='gl_isdebit[]']").val();
        
        if (!rowEl.hasAttr('data-op-meta') || (rowEl.hasAttr('data-op-meta') && rowEl.attr('data-op-meta') == '')) {
            
            if ($opMetaAttr.length && $('#glDtl input[name="gl_isdebit[]"][value="'+$opMetaAttrIsDebit+'"]', glBpMainWindow_<?php echo $this->uniqId; ?>).length) {
                
                if (isDebit == '1' && isDebit == $opMetaAttrIsDebit) {
                    $opMetaAttrIsDebit = '0';
                } else if (isDebit == '0') {
                    $opMetaAttrIsDebit = '1';
                }
                                
                $('#glDtl > tbody > tr', glBpMainWindow_<?php echo $this->uniqId; ?>).each(function() {
                    var $thisRow = $(this);
                        
                    if ($thisRow.find("input[name='gl_isdebit[]']").val() == $opMetaAttrIsDebit  
                        && $thisRow.find("input[name='gl_accountId[]']").val() != accountId 
                        && $thisRow.find("input[name='gl_subid[]']").val() == subId 
                        && typeof $thisRow.attr('data-op-meta') !== 'undefined' && $thisRow.attr('data-op-meta') !== '') { 
                    
                        opMeta = $thisRow.attr('data-op-meta'); 
                    }
                });

            } else {
                $('#glDtl > tbody > tr', glBpMainWindow_<?php echo $this->uniqId; ?>).each(function() {
                    var $thisRow = $(this);

                    if ($thisRow.find("input[name='gl_accountId[]']").val() != accountId 
                        && $thisRow.find("input[name='gl_subid[]']").val() == subId 
                        && typeof $thisRow.attr('data-op-meta') !== 'undefined' && $thisRow.attr('data-op-meta') !== '') { 
                    
                        opMeta = $thisRow.attr('data-op-meta'); 
                    }
                });
            }
        }
        
        return opMeta;
    }
    function metaBtnByOpMeta_<?php echo $this->uniqId; ?>(rowEl) {
        
        var subId = rowEl.find("input[name='gl_subid[]']").val();
        var $opMetaAttr = $('#glDtl tr[data-sub-id="'+subId+'"][data-op-meta]:not([data-op-meta=""]):eq(0)', glBpMainWindow_<?php echo $this->uniqId; ?>);
        
        if ($opMetaAttr.length) {
            
            var isDebit = rowEl.find("input[name='gl_isdebit[]']").val();
            
            $('#glDtl > tbody > tr', glBpMainWindow_<?php echo $this->uniqId; ?>).each(function() {
                var $thisRow = $(this);

                if ($thisRow.find("input[name='gl_subid[]']").val() == subId && isDebit != $thisRow.find("input[name='gl_isdebit[]']").val() 
                    && (($thisRow.hasAttr('data-op-meta') && $thisRow.attr('data-op-meta') == '') || !$thisRow.hasAttr('data-op-meta'))) { 
                    var $actionCell = $thisRow.find("td.gl-action-column");
                    
                    if ($actionCell.find('.gl-dtl-meta-btn').length == 0) {
                        $actionCell.prepend("<div class='btn btn-xs purple-plum gl-dtl-meta-btn' title='Үзүүлэлт' onclick='showDtlMeta_<?php echo $this->uniqId; ?>(this, true);'>...</div>");
                    }
                }
            });
        }
                
        return;
    } 
    function checkAccountTypeId_<?php echo $this->uniqId; ?>(rowEl, accountId, subId, isDebit) {
        if ('<?php echo Config::getFromCache('IS_ECONOMIC_CLASS'); ?>' == '1') {
            return true;
        }
        return $('#glDtl input[name="gl_main_accounttypeid[]"][value="29"], #glDtl input[name="gl_main_accounttypeid[]"][value="30"], #glDtl input[name="gl_main_accounttypeid[]"][value="31"], #glDtl input[name="gl_main_accounttypeid[]"][value="32"]', glBpMainWindow_<?php echo $this->uniqId; ?>).length;
    }
    function checkSubChange_<?php echo $this->uniqId; ?>(changedTr){
        
        var rowAccountId = $(changedTr).find("input[name='gl_accountId[]']").val();
        
        if (rowAccountId == '') {
            return;
        }
        
        if ($(changedTr).find("input[name='gl_dtlId[]']").val() === '') {
            $(changedTr).find("input[name='gl_isdebit[]']").val(''); 
            $(changedTr).find("input[data-input-name='debitAmount']").autoNumeric('set', "0");
            $(changedTr).find("input[data-input-name='debitAmountBase']").autoNumeric('set', "0");
            $(changedTr).find("input[data-input-name='creditAmount']").autoNumeric('set', "0");
            $(changedTr).find("input[data-input-name='creditAmountBase']").autoNumeric('set', "0");  
            $(changedTr).find("input[name='gl_debitAmount[]']").val("0");
            $(changedTr).find("input[name='gl_debitAmountBase[]']").val("0");
            $(changedTr).find("input[name='gl_creditAmount[]']").val("0");
            $(changedTr).find("input[name='gl_creditAmountBase[]']").val("0");  
            $(changedTr).find("input[name='invoiceBookValue[]']").val("");  
            $(changedTr).find("input[name='defaultInvoiceBook[]']").val("");  
            
            var subid = $(changedTr).find("input[name='gl_subid[]']").val();
            var isDebit;
            
            if (subid !== '') {
                var isDebit = '';
                var isCredit = ''; 
                var totalProAmount = 0;
                var thisTr = '';
                
                $('#glDtl > tbody > tr', glBpMainWindow_<?php echo $this->uniqId; ?>).each(function() {
                    var _thisRow = $(this);
                    if (_thisRow.find("input[name='gl_subid[]']").val() == subid) { 
                        thisTr = this;
                        return false;
                    }
                });
                
                if (thisTr !== '') {
                    isDebit = $(thisTr).find("input[name='gl_isdebit[]']").val();
                    if (isDebit == '1') {
                        isCredit = '0';
                        totalProAmount = $(thisTr).find("input[name='gl_debitAmount[]']").val();
                    } else if (isDebit == '0') { 
                        isCredit = '1';
                        totalProAmount = $(thisTr).find("input[name='gl_creditAmount[]']").val();
                    }
                }
                
                var rate = $(changedTr).find("input[name='gl_visiblerate[]']").autoNumeric("get");
                var dtAmt = 0;
                var ktAmt = 0;
                var dtAmtFcy = 0;
                var ktAmtFcy = 0;
                
                if (isCredit == 1) {
                    var totalDt = 0;
                    $('table#glDtl > tbody > tr', glBpMainWindow_<?php echo $this->uniqId; ?>).each(function() {
                        var _thisRow = $(this);
                        var this_subid = _thisRow.find("input[name='gl_subid[]']").val();
                        if (typeof _thisRow.find("input[name='gl_debitAmount[]']").val() != 'undefined' && this_subid == $(thisTr).find("input[name='gl_subid[]']").val()) {
                            totalDt = totalDt + Number(_thisRow.find("input[name='gl_debitAmount[]']").val());
                        }
                    }); 
                    dtAmt = totalProAmount - totalDt;
                    dtAmtFcy = (rate === '1') ? 0 : glRound(dtAmt / rate);
                    
                } else if (isCredit === 0) {
                    
                    var totalKt = 0;
                    $('table#glDtl > tbody > tr', glBpMainWindow_<?php echo $this->uniqId; ?>).each(function() {
                        var _thisRow = $(this);
                        var this_subid = _thisRow.find("input[name='gl_subid[]']").val();
                        if (typeof _thisRow.find("input[name='gl_creditAmount[]']").val() != 'undefined' && this_subid == $(thisTr).find("input[name='gl_subid[]']").val()) {
                            totalKt = totalKt + Number(_thisRow.find("input[name='gl_creditAmount[]']").val());
                        }
                    }); 
                    ktAmt = totalProAmount - totalKt;
                    ktAmtFcy = (rate === '1') ? 0 : glRound(ktAmt / rate);
                }
                $(changedTr).find("input[name='isCheckedProDt[]']").val(isCredit);   
                $(changedTr).find("input[name='gl_debitAmount[]']").val(dtAmt);
                $(changedTr).find("input[name='gl_debitAmountBase[]']").val(dtAmtFcy);
                $(changedTr).find("input[name='gl_creditAmount[]']").val(ktAmt);
                $(changedTr).find("input[name='gl_creditAmountBase[]']").val(ktAmtFcy);  
                $(changedTr).find("input[data-input-name='debitAmount']").autoNumeric('set', dtAmt);
                $(changedTr).find("input[data-input-name='debitAmountBase']").autoNumeric('set', dtAmtFcy);
                $(changedTr).find("input[data-input-name='creditAmount']").autoNumeric('set', ktAmt);
                $(changedTr).find("input[data-input-name='creditAmountBase']").autoNumeric('set', ktAmtFcy);  
                
                glRowExpand_<?php echo $this->uniqId; ?>(changedTr, 'expandRemove', '');
                checkAccountFilterConfig_<?php echo $this->uniqId; ?>(changedTr, 'all');
            }
        } else {
            checkIsUseGlDetail_<?php echo $this->uniqId; ?>('expandRemove');
        }
    }
    function checkIsUseBase_<?php echo $this->uniqId; ?>(elem) {
        var $mainWindow = $(elem).closest('form');
        var $glDtl = $('#glDtl', $mainWindow);
        var isUseBase = false;
        
        if (
            ($('input[name*="gl_rate_currency"][value!="MNT"]', $glDtl).filter(function(){return this.value.length > 0}).length) || 
            ($("select.gl-row-currency", $glDtl).length && $('select.gl-row-currency option[value!=""]:selected', $glDtl).length)
            ) {
            isUseBase = true;
        }
        
        if (isUseBase) {
            $glDtl.find("thead#header2").removeClass('hide').show();
            $glDtl.find("thead#header1").hide();
            $glDtl.find("th.usebase, td.usebase, td[data-usebase='usebase']").css({display: ''});
        } else {
            $glDtl.find("thead#header2").addClass('hide').hide();
            $glDtl.find("thead#header1").show();
            $glDtl.find("th.usebase, td.usebase, td[data-usebase='usebase']").css({display: 'none'});
        }        
        calculateFooterSum_<?php echo $this->uniqId; ?>(elem);
        return;
    }
    function nonRequiredCashOnHand_<?php echo $this->uniqId; ?>(subid, accountid, tr) {    
        var countSubId = 0, countSubId2 = 0;
        $('#glDtl > tbody > tr', glBpMainWindow_<?php echo $this->uniqId; ?>).each(function() {
            var $thisRow = $(this);
            if ($thisRow.find("input[name='gl_subid[]']").val() == subid) { 
                countSubId++
                if (jQuery.inArray($thisRow.find("input[name='gl_accounttypeCode[]']").val(), cashHandBankTypeCode) != -1) {
                    countSubId2++
                }
            }
        });           

        if (countSubId > 1 && countSubId == countSubId2) {
			$('select[data-path="cashFlowSubCategoryId"]', glBpMainWindow_<?php echo $this->uniqId; ?>).removeAttr('required');		
            $('#glDtl > tbody > tr', glBpMainWindow_<?php echo $this->uniqId; ?>).each(function() {
                var $thisRow = $(this);
                if ($thisRow.find("input[name='gl_subid[]']").val() == subid) { 
                    $(this).find('input[name="gl_cashflowsubcategoryid[]"]').val(1);
                }
            });       
        } else {
            if ($('label[data-label-path="cashFlowSubCategoryId"]', glBpMainWindow_<?php echo $this->uniqId; ?>).find('span.required').length) {
                $('label[data-label-path="cashFlowSubCategoryId"]', glBpMainWindow_<?php echo $this->uniqId; ?>).attr('required', 'required');
            }		
            $('#glDtl > tbody > tr', glBpMainWindow_<?php echo $this->uniqId; ?>).each(function() {
                var $thisRow = $(this);
                if ($thisRow.find("input[name='gl_subid[]']").val() == subid) { 
                    $(this).find('input[name="gl_cashflowsubcategoryid[]"]').val(0);
                }
            });         
        }
    }
    function hideByAccountFilterConfig_<?php echo $this->uniqId; ?>(subid, accountid, tr) {    
        var subAccountFilters = '', accountFilterRow = [];
        $('#glDtl > tbody > tr', glBpMainWindow_<?php echo $this->uniqId; ?>).each(function() {
            var $thisRow = $(this);

            if ($thisRow.find("input[name='gl_subid[]']").val() == subid && $thisRow.find("input[name='gl_accountId[]']").val() != accountid) { 
                subAccountFilters += $thisRow.find("input[name='gl_main_accounttypeid[]']").val() + ',';
                if ($thisRow.find("input[name='gl_accountFilterConfig[]']").val()) {
                    accountFilterRow.push($thisRow);
                }
            }
        });         

        if ($(tr).find('#glExpandedWindow').length && $(tr).find('#glExpandedWindow').find("[data-col-path]").length) {
            var $accFilters = $(tr).find('#glExpandedWindow').find("[data-col-path]");            
            $accFilters.each(function() {
                var $colPath = $(this).attr('data-col-path');
                if ($colPath) {
                    $(this).closest('tr').hide();                    
                    $(this).closest('tr').find('.is-ac-meta-empty').prop('checked', true);                    
                }
            });
        }        
        
        if (subAccountFilters) {
            var accFilter = subAccountFilters.split(",");
            
            if ($(tr).find('#glExpandedWindow').length && $(tr).find('#glExpandedWindow').find("[data-col-path]").length) {
                var $accFilters = $(tr).find('#glExpandedWindow').find("[data-col-path]");
                
                $accFilters.each(function() {
                    var $colPath = $(this).attr('data-col-path').split(",");
                    var obj = undefined;
                    for (var cc = 0; cc < $colPath.length; cc++) {
                        if (typeof obj === 'undefined') {
                            obj = accFilter.find(o => o.trim() === $colPath[cc].trim());
                        }                        
                    }
                    if (typeof obj === 'undefined') {
                        $(this).closest('tr').hide();
                        $(this).closest('tr').find('.is-ac-meta-empty').prop('checked', true);
                    } else {
                        $(this).closest('tr').show();
                        $(this).closest('tr').find('.is-ac-meta-empty').prop('checked', false);
                    }                    
                });
            }
        }        
        
        // if (Object.keys(accountFilterRow).length) {
        //     for (var k = 0; k < accountFilterRow.length; k++) {
        //         subAccountFilters = '';
        //         var $trel = $(accountFilterRow[k]);
        //         $('#glDtl > tbody > tr', glBpMainWindow_<?php echo $this->uniqId; ?>).each(function() {
        //             var $thisRow = $(this);

        //             if ($thisRow.find("input[name='gl_subid[]']").val() == $trel.find("input[name='gl_subid[]']").val() && $thisRow.find("input[name='gl_accountId[]']").val() != $trel.find("input[name='gl_accountId[]']").val()) { 
        //                 subAccountFilters += $thisRow.find("input[name='gl_main_accounttypeid[]']").val() + ',';
        //             }
        //         });         

        //         if (subAccountFilters) {
        //             var accFilter = subAccountFilters.split(",");

        //             if ($trel.find('#glExpandedWindow').length && $trel.find('#glExpandedWindow').find("[data-col-path]").length) {
        //                 var $accFilters = $trel.find('#glExpandedWindow').find("[data-col-path]");

        //                 $accFilters.each(function() {
        //                     var $colPath = $(this).attr('data-col-path').split(",");
        //                     for (var cc = 0; cc < $colPath.length; cc++) {
        //                         var obj = accFilter.find(o => o.trim() === $colPath[cc].trim());
        //                         if (typeof obj === 'undefined') {
        //                             $(this).closest('tr').hide();
        //                         } else {
        //                             $(this).closest('tr').show();
        //                         }
        //                     }
        //                 });
        //             }
        //         }                     
        //     }
        // }
    }
    function checkAccountFilterConfig_<?php echo $this->uniqId; ?>(elem, subId) {
        var $rowEl = $(elem), prevSubId = '', prevSubId2 = '';
        var accTypeCode = $rowEl.find("input[name='gl_main_accounttypeid[]']").val();
        var accTypeSubId = $rowEl.find("input[name='gl_subid[]']").val();

        if (subId === 'all') {
            $('#glDtl > tbody > tr', glBpMainWindow_<?php echo $this->uniqId; ?>).each(function() {
                var subIdCtr = 0, subIdCtr2 = 0;
                prevSubId = $(this).find("input[name='gl_subid[]']").val();
                var rowaccTypeCode = $(this).find("input[name='gl_main_accounttypeid[]']").val();
                
                if ($(this).find("input[name='gl_main_accounttypeid[]']").val() != '') {
                    console.log('accTypeCode', accTypeCode);
                    console.log('rowaccTypeCode', rowaccTypeCode);

                    $('#glDtl > tbody > tr', glBpMainWindow_<?php echo $this->uniqId; ?>).each(function() {
                        var $thisRow = $(this);                        

                        if ($thisRow.find("input[name='gl_subid[]']").val() == prevSubId && $thisRow.find("input[name='gl_main_accounttypeid[]']").val() != '') {
                            subIdCtr++;
                        }

                        if ($thisRow.find("input[name='gl_subid[]']").val() == prevSubId && $thisRow.find("input[name='gl_accountFilterConfigIsDimension[]']").val() != '1' && $thisRow.find("input[name='gl_accountFilterConfig[]']").val() != '') { 
                            var $actionCell = $thisRow.find("td.gl-action-column");
                            var accFilter = $thisRow.find("input[name='gl_accountFilterConfig[]']").val().split(",");                               
                            subIdCtr2++;

                            if ($thisRow.find("input[name='gl_main_accounttypeid[]']").val() != rowaccTypeCode) {                            
                                var obj = accFilter.find(o => o.trim() === rowaccTypeCode);              
                                console.log('accFilter', accFilter);
                                console.log(obj);

                                if (typeof obj !== 'undefined' && $actionCell.find('.gl-dtl-meta-btn').length) {
                                    $actionCell.find('.gl-dtl-meta-btn').show();
                                } else if (typeof obj !== 'undefined' && $actionCell.find('#detailedMeta').length) {
                                    $actionCell.find('#detailedMeta').show();
                                } else if (typeof obj === 'undefined' && $actionCell.find('.gl-dtl-meta-btn').length) {
                                    $actionCell.find('.gl-dtl-meta-btn').hide();
                                } else if (typeof obj === 'undefined' && $actionCell.find('#detailedMeta').length) {
                                    $actionCell.find('#detailedMeta').hide();             
                                }      
                                hideByAccountFilterConfig_<?php echo $this->uniqId; ?>($thisRow.find("input[name='gl_subid[]']").val(), $thisRow.find("input[name='gl_accountId[]']").val(), $thisRow);
                            }     
                        }                                            
                    });     

                    console.log(subIdCtr);
                    console.log(subIdCtr2);
                    if (subIdCtr == 1 && subIdCtr2 != 0) {
                        var $actionCell = $(this).find("td.gl-action-column");
                        if ($actionCell.find('.gl-dtl-meta-btn').length) {
                            $actionCell.find('.gl-dtl-meta-btn').hide();
                            hideByAccountFilterConfig_<?php echo $this->uniqId; ?>($(this).find("input[name='gl_subid[]']").val(), $(this).find("input[name='gl_accountId[]']").val(), $(this));
                        } else if ($actionCell.find('#detailedMeta').length) {
                            $actionCell.find('#detailedMeta').hide();                
                        }
                    }

                    prevSubId2 = prevSubId;
                }
            });            
            return;
        }

        if (typeof subId !== 'undefined') {
            $('#glDtl > tbody > tr', glBpMainWindow_<?php echo $this->uniqId; ?>).each(function() {
                var $thisRow = $(this);

                if ($thisRow.find("input[name='gl_subid[]']").val() == subId) { 
                    $rowEl = $thisRow;
                }
            });           
        }
    
        if (!$rowEl.find("input[name='gl_subid[]']").length) return;
        
        var subId = $rowEl.find("input[name='gl_subid[]']").val();        
        
        $('#glDtl > tbody > tr', glBpMainWindow_<?php echo $this->uniqId; ?>).each(function() {
            var $thisRow = $(this);

            if ($thisRow.find("input[name='gl_subid[]']").val() == subId && $thisRow.find("input[name='gl_accountFilterConfigIsDimension[]']").val() != '1' && $thisRow.find("input[name='gl_accountFilterConfig[]']").val() != '') { 
                var $actionCell = $thisRow.find("td.gl-action-column");
                var accFilter = $thisRow.find("input[name='gl_accountFilterConfig[]']").val().split(",");  
                var obj = accFilter.find(o => o.trim() === accTypeCode);                

                if (typeof obj === 'undefined' && $actionCell.find('.gl-dtl-meta-btn').length) {
                    $actionCell.find('.gl-dtl-meta-btn').hide();
                    hideByAccountFilterConfig_<?php echo $this->uniqId; ?>($thisRow.find("input[name='gl_subid[]']").val(), $thisRow.find("input[name='gl_accountId[]']").val(), $thisRow);                    
                } else if (typeof obj === 'undefined' && $actionCell.find('#detailedMeta').length) {
                    $actionCell.find('#detailedMeta').hide();
                    hideByAccountFilterConfig_<?php echo $this->uniqId; ?>($thisRow.find("input[name='gl_subid[]']").val(), $thisRow.find("input[name='gl_accountId[]']").val(), $thisRow);
                } else if (typeof obj !== 'undefined' && $actionCell.find('.gl-dtl-meta-btn').length) {
                    $actionCell.find('.gl-dtl-meta-btn').show();
                    hideByAccountFilterConfig_<?php echo $this->uniqId; ?>($thisRow.find("input[name='gl_subid[]']").val(), $thisRow.find("input[name='gl_accountId[]']").val(), $thisRow);
                } else if (typeof obj !== 'undefined' && $actionCell.find('#detailedMeta').length) {
                    $actionCell.find('#detailedMeta').show();
                    hideByAccountFilterConfig_<?php echo $this->uniqId; ?>($thisRow.find("input[name='gl_subid[]']").val(), $thisRow.find("input[name='gl_accountId[]']").val(), $thisRow);
                }      
            }
        });
                
        return;        
    }
    function removeGlDtl_<?php echo $this->uniqId; ?>(elem) {
        var targetRow = $(elem).closest("tr");
        var targetBody = targetRow.closest('tbody');        
        var subId = targetRow.find("input[name='gl_subid[]']").val();
        targetRow.remove();
        checkIsUseBase_<?php echo $this->uniqId; ?>(elem);
        calculateFooterSum_<?php echo $this->uniqId; ?>(targetBody);
        refreshTrIndex_<?php echo $this->uniqId; ?>();
        bpSetGlMetaRowIndex(glBpMainWindow_<?php echo $this->uniqId; ?>);
        checkAccountFilterConfig_<?php echo $this->uniqId; ?>(elem, subId);
        return;
    }   
    function addGlDtlWithAccountValue_<?php echo $this->uniqId; ?>(data, elem, fromAutoComplete) {
        if (data.length == 0) {
            return;
        }
        var mainWindow = $(elem).closest('form');
        
        var bookDate = $("input[name='glbookDate']", glEntryWindowId_<?php echo $this->uniqId; ?>).val();
        var bookDescription = $("#gldescription", glEntryWindowId_<?php echo $this->uniqId; ?>).val();
        if (typeof bookDate === 'undefined') {
            bookDate = $("input[name='hidden_glbookDate']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
            bookDescription = $("#gldescription", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
        }

        var row = {};
        $.each(data, function(i, v) {
            row[i.toLowerCase()] = v;
        });
        
        if (row) {
            
            var $tableBody = mainWindow.find('#glDtl > tbody').first();
            var $lastRow = $tableBody.find('> tr:last');
            var lastIndex = $lastRow.index();
            var accountCodeField = '<div class="input-group"><input type="hidden" name="gl_accountId[]" value="' + row.id + '"><input type="text" name="gl_accountCode[]" id="gl_accountCode" class="form-control form-control-sm text-center accountCodeMask" value="' + row.accountcode + '"><span class="input-group-btn"><button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewCustomSelectableGrid(\'<?php echo Mdgl::$accountListDataViewCode; ?>\', \'single\', \'accountSelectabledGrid_<?php echo $this->uniqId; ?>\', \'\', this);"><i class="fa fa-search"></i></button></span></div>';
            var rate = getAccountRate_<?php echo $this->uniqId; ?>(bookDate, row.id, row.currencycode);
            var dtAmt = 0;
            var ktAmt = 0;
            var dtAmtFcy = 0;
            var ktAmtFcy = 0;
            var subId = 1;
            var addAfterRow = false;
            var $glSelectedRowElem = $tableBody.find('tr.gl-selected-row');
            var customerId = '', customerCode = '', customerName = '';
            var isGlCustomerCopy = $glLoadWindow_<?php echo $this->uniqId; ?>.find('input.is-gl-customer-copy').is(':checked');
            
            if ($glSelectedRowElem.length > 0) {
                var selectedRow = $glSelectedRowElem;
                subId = selectedRow.find("input[name='gl_subid[]']").val();
                addAfterRow = true;
            }
            
            if (row.hasOwnProperty('customerid') && row.customerid) {
                customerId = row.customerid; 
                customerCode = row.customercode; 
                customerName = row.customername;
            }
            
            if ($lastRow.length && isGlCustomerCopy) {
                var savedCustomerId = $lastRow.find('input[name="gl_customerId[]"]').val();
                
                if (savedCustomerId != '') {
                    customerId = savedCustomerId; 
                    customerCode = $lastRow.find('input[name="gl_customerCode[]"]').val();
                    customerName = htmlentities($lastRow.find('input[name="gl_customerName[]"]').val()); 
                }
            }
            
            if (isIgnoreUseDetail_<?php echo $this->uniqId; ?>) {
                row.isusedetailbook = 0;
            }
            
            var customerField = '<div class="input-group double-between-input">\n\
                                    <input type="hidden" name="gl_customerId[]" value="'+customerId+'">\n\
                                    <input type="text" id="gl_customerCode" value="'+customerCode+'" name="gl_customerCode[]" class="form-control form-control-sm text-center" title="'+customerCode+'" placeholder="<?php echo $this->lang->line('code_search'); ?>" style="width:80px;max-width:80px;">\n\
                                    <span class="input-group-btn">\n\
                                        <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick=\"dataViewCustomSelectableGrid(\'<?php echo Mdgl::$customerListDataViewCode; ?>\', \'single\', \'customerSelectabledGrid\', \'\', this);\"><i class="fa fa-search"></i></button>\n\
                                    </span>\n\
                                    <span class="input-group-btn">\n\
                                        <input type="text" id="gl_customerName" value="'+customerName+'" title="'+customerName+'" name="gl_customerName[]" class="form-control form-control-sm text-center" placeholder="<?php echo $this->lang->line('name_search'); ?>">\n\
                                    </span>\n\
                                </div>';
                                            
            var currencyDropDown = '', baseReadonly = '', rateReadonly = '', expenseCenterField = '';
            
            if (typeof row.currencycode !== 'undefined') {
                var currencyCode = row.currencycode;
                if (currencyCode.toLowerCase() == 'mnt' && row.isusedetailbook != '1') {
                    currencyDropDown = '<?php echo Form::select(array('class' => 'form-control form-control-sm no-padding gl-row-currency', 'data' => $this->currencyList, 'text'=>'---', 'op_value' => 'CURRENCY_ID', 'op_text' => 'CURRENCY_CODE', 'style'=>'width:50px')); ?>';
                    baseReadonly = " readonly='readonly'";
                    rateReadonly = " readonly='readonly'";
                } else {
                    currencyDropDown = currencyCode;
                }
            }
            
            <?php
            if (isset($this->isGlRateDisabled)) {
                echo "rateReadonly = \"readonly='readonly'\";";
            }
            ?>
            
            var rowAttr = {
                subId: subId, 
                lastIndex: lastIndex, 
                accounttypeid: row.accounttypeid, 
                objectid: row.objectid, 
                accounttypecode: row.accounttypecode, 
                isusedetailbook: row.isusedetailbook, 
                departmentid: row.departmentid, 
                currencycode: row.currencycode, 
                accountCodeField: accountCodeField, 
                accountname: row.accountname, 
                customerField: customerField, 
                expenseCenterField: expenseCenterField, 
                bookDescription: bookDescription, 
                currencyDropDown: currencyDropDown, 
                rate: rate, 
                rateReadonly: rateReadonly, 
                dtAmtFcy: dtAmtFcy, 
                baseReadonly: baseReadonly, 
                dtAmt: dtAmt, 
                ktAmtFcy: ktAmtFcy, 
                ktAmt: ktAmt, 
                keyId: '', 
                rowType: 'main', 
                metas: '', 
                actions: '', 
                isdebit: '', 
                ismetas: '',
                accountfilter: row.accountfilter ? row.accountfilter : '', 
                isnulldimension: row.isnulldimension ? row.isnulldimension : ''
            };
            var newRowHtml = glRowAppend_<?php echo $this->uniqId; ?>(rowAttr);
                                    
            $glSelectedRowElem.removeClass('gl-selected-row');
            
            $tableBody.append(newRowHtml);
            var tr = $tableBody.find('> tr:last-child');

            $tableBody.find('> tr.gl-new-row').removeClass('gl-new-row');
            
            checkRowDescriptionField_<?php echo $this->uniqId; ?>(elem);
            
            tr.find("input[name='gl_customerCode[]']:not([readonly],[disabled])").focus();
            
            Core.initNumberInput(tr);
            Core.initAccountCodeMask(tr);
            
            checkIsUseBase_<?php echo $this->uniqId; ?>(tr);
            checkIsUseDetail_<?php echo $this->uniqId; ?>(row.isusedetailbook, tr);
            
            glRowExpand_<?php echo $this->uniqId; ?>(tr, '', fromAutoComplete);     
            
            glTableFreeze_<?php echo $this->uniqId; ?>();
            bpSetGlMetaRowIndex(glBpMainWindow_<?php echo $this->uniqId; ?>);
            
            Core.unblockUI();
        }
    }
    function glRowAppend_<?php echo $this->uniqId; ?>(rowAttr) {
        
        var rowDescr = str_replace('"', '&quot;', rowAttr.bookDescription);
        
        var rowHtml = "<tr data-sub-id='"+rowAttr.subId+"' data-row-index='"+(rowAttr.lastIndex+1)+"' data-account-departmentid='"+(rowAttr.hasOwnProperty('departmentid') ? rowAttr.departmentid : '')+"' class='gl-selected-row gl-new-row'>\n\
            <td class='stretchInput middle text-center'>\n\
                <input type='text' name='gl_subid[]' id='gl_subid' class='form-control' style='text-align:center;' value='" + rowAttr.subId + "'>\n\
                <input type='hidden' name='gl_accounttypeId[]'>\n\
                <input type='hidden' name='gl_main_accounttypeid[]' value='" + rowAttr.accounttypeid + "'>\n\
                <input type='hidden' name='gl_objectId[]' value='" + rowAttr.objectid + "'>\n\
                <input type='hidden' name='gl_invoiceBookId[]'>\n\
                <input type='hidden' name='gl_description[]'>\n\
                <input type='hidden' name='gl_isdebit[]' value='"+rowAttr.isdebit+"'>\n\
                <input type='hidden' name='gl_accounttypeCode[]' value='" + rowAttr.accounttypecode + "'>\n\
                <input type='hidden' name='gl_accountFilterConfig[]' value='" + (rowAttr.accountfilter ? rowAttr.accountfilter : '') + "'>\n\
                <input type='hidden' name='gl_accountFilterConfigIsDimension[]' value='" + (rowAttr.isnulldimension ? rowAttr.isnulldimension : '') + "'>\n\
                <input type='hidden' name='gl_useDetailBook[]' value='" + rowAttr.isusedetailbook + "'>\n\
                <input type='hidden' name='invoiceBookValue[]'>\n\
                <input type='hidden' name='gl_dtlId[]'>\n\
                <input type='hidden' name='gl_accountFilter[]'>\n\
                <input type='hidden' name='gl_cashflowsubcategoryid[]'>\n\
                <input type='hidden' name='defaultInvoiceBook[]'>\n\
                <input type='hidden' name='gl_isEdited[]' value='0'>\n\
                <input type='hidden' name='gl_amountLock[]' value='0'>\n\
                <input type='hidden' name='gl_rowislock[]' value='0'>\n\
                <input type='hidden' name='gl_processId[]' value=''>\n\
                <input type='hidden' name='gl_ismetas[]' value='"+rowAttr.ismetas+"'>\n\
                <input type='hidden' name='gl_isGetLoad[]' value=''>\n\
                <input type='hidden' name='gl_metas[]' value='"+rowAttr.metas+"'>\n\
                <input type='hidden' name='gl_rate_currency[]' value='"+rowAttr.currencycode+"'>\n\
                <input type='hidden' name='gl_keyId[]' value='"+rowAttr.keyId+"'>\n\
            <td class='stretchInput middle text-center'>" + rowAttr.accountCodeField + "</td>\n\
            <td class='stretchInput middle text-center'><input type='text' name='gl_accountName[]' value='" + rowAttr.accountname + "' title='" + rowAttr.accountname + "' id='gl_accountName' class='form-control form-control-sm readonly-white-bg' readonly='readonly'></td>\n\
            <td class='stretchInput middle text-center customPartner'>"+rowAttr.customerField+"</td>\n\
            <td class='stretchInput middle text-center glRowExpenseCenter'>"+rowAttr.expenseCenterField+"</td>\n\
            <td class='stretchInput middle text-center glRowDescr'><input type='text' name='gl_rowdescription[]' value=\""+rowDescr+"\" id='gl_rowdescription' class='form-control form-control-sm'></td>\n\
            <td class='stretchInput middle text-center glRowDescr2<?php echo Config::getFromCache('isGLDescrEnglish') ? '' : ' hide' ?>'><input type='text' name='gl_rowdescription2[]' value=\"\" id='gl_rowdescription2' class='form-control form-control-sm'></td>\n\
            <td class='stretchInput middle text-center glRowCurrency'>"+rowAttr.currencyDropDown+"</td>\n\
            <td data-usebase='usebase' class='stretchInput middle text-center glRowRate'><input type='text' name='gl_rate[]' id='gl_visiblerate' value='" + rowAttr.rate + "' class='form-control form-control-sm bigdecimalInit text-right readonly-white-bg'"+rowAttr.rateReadonly+"></td>\n\
            <td data-usebase='usebase' class='stretchInput middle text-center'><input type='text' data-input-name='debitAmountBase' id='gl_debitAmountBase' class='form-control form-control-sm bigdecimalInit text-right readonly-white-bg' value='"+rowAttr.dtAmtFcy+"' data-v-min='0'"+rowAttr.baseReadonly+"><input type='hidden' name='gl_debitAmountBase[]' id='gl_debitAmountBase' value='"+rowAttr.dtAmtFcy+"'></td>\n\
            <td class='stretchInput middle text-center'><input type='text' data-input-name='debitAmount' id='gl_debitAmount' class='form-control form-control-sm bigdecimalInit text-right readonly-white-bg' value='"+rowAttr.dtAmt+"' data-v-min='0'><input type='hidden' name='gl_debitAmount[]' id='gl_debitAmount' value='"+rowAttr.dtAmt+"'></td>\n\
            <td data-usebase='usebase' class='stretchInput middle text-center'><input type='text' data-input-name='creditAmountBase' id='gl_creditAmountBase' class='form-control form-control-sm bigdecimalInit text-right readonly-white-bg' value='"+rowAttr.ktAmtFcy+"' data-v-min='0'"+rowAttr.baseReadonly+"><input type='hidden' name='gl_creditAmountBase[]' id='gl_creditAmountBase' value='"+rowAttr.ktAmtFcy+"'></td>\n\
            <td class='stretchInput middle text-center'><input type='text' data-input-name='creditAmount' id='gl_creditAmount' class='form-control form-control-sm bigdecimalInit text-right readonly-white-bg' value='"+rowAttr.ktAmt+"' data-v-min='0'><input type='hidden' name='gl_creditAmount[]' id='gl_creditAmount' value='"+rowAttr.ktAmt+"'></td>\n\
            <td class='middle text-center'><input type='checkbox' class='gl-vat-deduction notuniform' title='НӨАТ салгах эсэх'></td>\n\
            <td class='middle text-center"+(glIncomeTaxDeduction_<?php echo $this->uniqId; ?> === '1' ? '' : ' hide')+"'><input type='checkbox' class='gl-incometax-deduction notuniform' title='ХХАОТ салгах эсэх'></td>\n\
            <td class='middle text-right gl-action-column'>"+rowAttr.actions+"<div class='btn btn-xs red gl-row-remove' title='Устгах' onclick='removeGlDtl_<?php echo $this->uniqId; ?>(this);'><i class='far fa-trash'></i></div></td></tr>";
                
        return rowHtml;                           
    }
    function getAccountRate_<?php echo $this->uniqId; ?>(bookDate, accountId, currencyCode) {
        var rate = 1;
        if (currencyCode !== '' && currencyCode.toLowerCase() !== 'mnt') {
            $.ajax({
                type: 'post',
                url: 'mdgl/getRate',
                data: {accountId: accountId, date: bookDate},
                async: false,
                dataType: 'json',
                success: function(data) {
                    if (data.status === 'success') {
                        var rateResult = data.result;
                        rate = rateResult.result;
                    } else {
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Error',
                            text: data.message,
                            type: 'error',
                            sticker: false
                        });
                    }
                },
                error: function() {
                    alert("Error");
                }
            });
        }
        return rate;
    }
    function getAccountRate2_<?php echo $this->uniqId; ?>(bookDate, accountId, currencyCode) {
        var rate = 1;
        if (currencyCode !== '' && currencyCode.toLowerCase() !== 'mnt') {
            $.ajax({
                type: 'post',
                url: 'mdgl/getRate2',
                data: {date: bookDate},
                async: false,
                dataType: 'json',
                success: function(data) {
                    if (data.status === 'success') {
                        var rateResult = data.result;
                        rate = rateResult.result;
                    } else {
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Error',
                            text: data.message,
                            type: 'error',
                            sticker: false
                        });
                    }
                },
                error: function() {
                    alert("Error");
                }
            });
        }
        return rate;
    }
    function getCurrencyRate_<?php echo $this->uniqId; ?>(bookDate, currencyId) {
        var rate = 1;
        $.ajax({
            type: 'post',
            url: 'mdgl/getRateByCurrencyId',
            data: {currencyId: currencyId, date: bookDate},
            async: false,
            dataType: 'json',
            success: function(data) {
                if (data.status === 'success') {
                    var rateResult = data.result;
                    rate = rateResult.result;
                } else {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Error',
                        text: data.message,
                        type: 'error',
                        sticker: false
                    });
                }
            },
            error: function() {
                alert("Error");
            }
        });
        return rate;
    }
    function checkIsUseDetail_<?php echo $this->uniqId; ?>(isUseDtl, tr) {
        if ($(tr).find("select.gl-row-currency").length > 0 && $(tr).find("select.gl-row-currency").val() == '') {
            $(tr).find("input[data-input-name='debitAmountBase']").attr('readonly', 'readonly');
            $(tr).find("input[data-input-name='creditAmountBase']").attr('readonly', 'readonly');
            /*$(tr).find("input[name='gl_rate[]']").attr('readonly', 'readonly');*/
        } else {
            if (isUseDtl == '1' && $(tr).find("input[name='gl_objectId[]']").val() != '20007' 
                && $(tr).find("input[name='gl_objectId[]']").val() != '20006' 
                && $(tr).find("input[name='gl_objectId[]']").val() != '30004') {
            
                $(tr).find("input[data-input-name='debitAmount']").attr('readonly', 'readonly');
                $(tr).find("input[data-input-name='creditAmount']").attr('readonly', 'readonly');
                $(tr).find("input[data-input-name='debitAmountBase']").attr('readonly', 'readonly');
                $(tr).find("input[data-input-name='creditAmountBase']").attr('readonly', 'readonly');
                /*$(tr).find("input[name='gl_rate[]']").attr('readonly', 'readonly');*/
            } else {
                if ($(tr).find("input[name='gl_amountLock[]']").val() != '1' && $(tr).find("input[name='gl_rowislock[]']").val() != '1') {
                    $(tr).find("input[data-input-name='debitAmount']").removeAttr('readonly');
                    $(tr).find("input[data-input-name='creditAmount']").removeAttr('readonly');
                    $(tr).find("input[data-input-name='debitAmountBase']").removeAttr('readonly');
                    $(tr).find("input[data-input-name='creditAmountBase']").removeAttr('readonly');
                    
                    <?php
                    if (!isset($this->isGlRateDisabled)) {
                    ?>
                    $(tr).find("input[name='gl_rate[]']").removeAttr('readonly');
                    <?php
                    }
                    ?>
                }
            }
        }
    }
    function calculateFooterSum_<?php echo $this->uniqId; ?>(elem) {
        var creditamountsum = 0;
        var creditamountbasesum = 0;
        var debitamountsum = 0;
        var debitamountbasesum = 0;
        var mainWindow = $(elem).closest('form');
        
        $('#glDtl > tbody > tr', mainWindow).each(function() {
            var _thisRow = $(this);
            var debitamount = 0;
            if (typeof _thisRow.find("input[name='gl_debitAmount[]']").val() != 'undefined') {
                debitamount = Number(_thisRow.find("input[name='gl_debitAmount[]']").val());
            }
            var debitamountbase = 0;
            if (typeof _thisRow.find("input[name='gl_debitAmountBase[]']").val() != 'undefined') {
                debitamountbase = Number(_thisRow.find("input[name='gl_debitAmountBase[]']").val());
            }
            var creditamount = 0;
            if (typeof _thisRow.find("input[name='gl_creditAmount[]']").val() != 'undefined') {
                creditamount = Number(_thisRow.find("input[name='gl_creditAmount[]']").val());
            }
            var creditamountbase = 0;
            if (typeof _thisRow.find("input[name='gl_creditAmountBase[]']").val() != 'undefined') {
                creditamountbase = Number(_thisRow.find("input[name='gl_creditAmountBase[]']").val());
            }
            debitamountsum = debitamountsum + debitamount;
            debitamountbasesum = debitamountbasesum + debitamountbase;
            creditamountsum = creditamountsum + creditamount;
            creditamountbasesum = creditamountbasesum + creditamountbase;
        });
        $("#glDtl", mainWindow).find("td.foot-sum-debitamount").autoNumeric('set', debitamountsum.toFixed(6));
        $("#glDtl", mainWindow).find("td.foot-sum-debitamountbase").autoNumeric('set', debitamountbasesum.toFixed(6));
        $("#glDtl", mainWindow).find("td.foot-sum-creditamount").autoNumeric('set', creditamountsum.toFixed(6));
        $("#glDtl", mainWindow).find("td.foot-sum-creditamountbase").autoNumeric('set', creditamountbasesum.toFixed(6));
        $("#glDtl", mainWindow).find("input[data-input-name='foot_sum_debitamount']").val(debitamountsum.toFixed(6));
        $("#glDtl", mainWindow).find("input[data-input-name='foot_sum_debitamountbase']").val(debitamountbasesum.toFixed(6));
        $("#glDtl", mainWindow).find("input[data-input-name='foot_sum_creditamount']").val(creditamountsum.toFixed(6));
        $("#glDtl", mainWindow).find("input[data-input-name='foot_sum_creditamountbase']").val(creditamountbasesum.toFixed(6));
        
        if ($("#headerDebitTotal", mainWindow).length > 0) {
            $("#headerDebitTotal", mainWindow).text('<?php echo Lang::lineDefault('DT', 'ДТ'); ?>: ' + $("#glDtl", mainWindow).find("td.foot-sum-debitamount").text());
            $("#headerCreditTotal", mainWindow).text('<?php echo Lang::lineDefault('KT', 'КТ'); ?>: ' + $("#glDtl", mainWindow).find("td.foot-sum-creditamount").text());
        }
    }
    function glRowExpand_<?php echo $this->uniqId; ?>(tr, expandRemove, fromAutoComplete) {
        var $thisRow = $(tr);
        var dtlId = $thisRow.find('input[name="gl_dtlId[]"]').val();
        var isDetailBook = $thisRow.find("input[name='gl_useDetailBook[]']").val();
        var invoiceBookId = $thisRow.find("input[name='gl_invoiceBookId[]']").val();
        
        var appendRow = $thisRow.find("td.gl-action-column");
        var isProcess = false;
        
        var bookDate = $("input[name='glbookDate']", glEntryWindowId_<?php echo $this->uniqId; ?>).val();
        var glbookId = $("input[name='glbookId']", glEntryWindowId_<?php echo $this->uniqId; ?>).val();
        var glBookTypeId = $("input[name='glBookTypeId']", glEntryWindowId_<?php echo $this->uniqId; ?>).val();
        var glbookNumber = $("input[name='glbookNumber']", glEntryWindowId_<?php echo $this->uniqId; ?>).val();
        var gldescription = $("#gldescription", glEntryWindowId_<?php echo $this->uniqId; ?>).val();
        
        if (typeof bookDate === 'undefined') {
           bookDate = $("input[name='hidden_glbookDate']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
           glbookId = $("input[name='glbookId']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
           glBookTypeId = $("input[name='glBookTypeId']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
           glbookNumber = $("input[name='glbookNumber']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
           gldescription = $("#gldescription", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
        }
        
        var subId = $thisRow.find("input[name='gl_subid[]']").val();
        var accountId = $thisRow.find("input[name='gl_accountId[]']").val();
        var objectId = $thisRow.find("input[name='gl_objectId[]']").val();
        var isDebit = $thisRow.find("input[name='gl_isdebit[]']").val();
        var isEditMode = (dtlId != '') ? true : false;
        
        if (typeof $thisRow.attr('data-isdebitcreditdefaultvalue') !== 'undefined') {
            $thisRow.removeAttr('data-isdebitcreditdefaultvalue');
        }        
        
        var selectedRow = {
            'uniqId': '<?php echo $this->uniqId; ?>',
            'bookid': glbookId,
            'bookdate': bookDate,
            'booktypeid': glBookTypeId,
            'booknumber': glbookNumber,
            'description': gldescription,
            'accountid': accountId,
            'accountcode': $thisRow.find("input[name='gl_accountCode[]']").val(),
            'accountname': $thisRow.find("input[name='gl_accountName[]']").val(),
            'accounttypeid': $thisRow.find("input[name='gl_main_accounttypeid[]']").val(),
            'accounttypecode': $thisRow.find("input[name='gl_accounttypeCode[]']").val(),
            'objectid': objectId,
            'rowislock': $thisRow.find("input[name='gl_rowislock[]']").val(),
            'subid': subId,
            'debitamount': $thisRow.find("input[name='gl_debitAmount[]']").val(),
            'creditamount': $thisRow.find("input[name='gl_creditAmount[]']").val(),
            'rate': $thisRow.find("input[name='gl_rate[]']").autoNumeric("get"),
            'usedetail': isDetailBook,
            'invoices': $thisRow.find("input[name='invoiceBookValue[]']").val(),
            'defaultinvoices': $thisRow.find("input[name='defaultInvoiceBook[]']").val(),
            'isdebit': isDebit,
            'customerid': $thisRow.find("input[name='gl_customerId[]']").val(),
            'detailvalues': $thisRow.find("input[name='gl_metas[]']").val(),
            'dtlid': dtlId,
            'glMainWindowId': "#<?php echo isset($this->glBpMainWindowId) ? $this->glBpMainWindowId : "glTemplateSectionProcess"; ?>" 
        };
        
        if (dtlId != '' && isDetailBook == '1' && invoiceBookId != '') {
            
            var processId = $thisRow.find("input[name='gl_processId[]']").val();
            
            if (processId != '') {
                runBpGlAccountRow_<?php echo $this->uniqId; ?>(processId, tr, isDebit, 'defaultInvoiceBook', isEditMode);
            } else {
                $.ajax({
                    type: 'post',
                    url: 'mdgl/checkAccountBpLink',
                    data: selectedRow, 
                    dataType: 'json', 
                    async: false, 
                    success: function (checkData) {

                        /*if (isDebit == '1') {
                            var processId = checkData.DEBIT_PROCESS_ID;
                            if (isEditMode && checkData.DEBIT_EDIT_PROCESS_ID !== '') {
                                processId = checkData.DEBIT_EDIT_PROCESS_ID;
                            }
                        } else {
                            var processId = checkData.CREDIT_PROCESS_ID;
                            if (isEditMode && checkData.CREDIT_EDIT_PROCESS_ID !== '') {
                                processId = checkData.CREDIT_EDIT_PROCESS_ID;
                            }
                        }*/

                        runBpGlAccountRow_<?php echo $this->uniqId; ?>('', tr, isDebit, 'defaultInvoiceBook', isEditMode, checkData, selectedRow);
                    }
                });
            }

            return;                            
        } 
        
        var $dialogName = 'dialog-expandedGlDtl';
        
        if (expandRemove !== '') {
            $("#" + $dialogName, appendRow).find(".select2").select2("destroy");
            $("#" + $dialogName, appendRow).empty().dialog('destroy').remove();
            appendRow.find("#" + $dialogName).remove();
            appendRow.find("#detailedMeta").remove();
        }
        
        if (1 == <?php echo isset($this->isNotAccountProcess) ? '0' : '1'; ?> && (selectedRow.usedetail == '1' || selectedRow.usedetail == 'true') 
            || ($thisRow.find("input[name='invoiceBookValue[]']").val() == '' && $thisRow.find("input[name='defaultInvoiceBook[]']").val() != '')) {
            
            var opMeta = fillOpMeta_<?php echo $this->uniqId; ?>($thisRow, accountId, subId, isDebit);
            if (opMeta != '') {
                selectedRow['opMeta'] = opMeta;
            }
            
            if (checkAccountTypeId_<?php echo $this->uniqId; ?>($thisRow, accountId, subId, isDebit)) {
                selectedRow['checkAccountTypeId'] = 1;
            }
                
            $.ajax({
                type: 'post',
                url: 'mdgl/checkAccountBpLink',
                data: selectedRow, 
                dataType: 'json', 
                async: false, 
                success: function (checkData) {
                    
                    if (checkData.processButtons !== '') {
                        
                        isProcess = true;
                        chooseDebitOrCredit_<?php echo $this->uniqId; ?>(tr, checkData, selectedRow, appendRow, fromAutoComplete);
                        
                    } else {
                        
                        if (typeof checkData.DEBIT_PROCESS_ID !== 'undefined') {
                            
                            isProcess = true;

                            if ($thisRow.find("input[name='invoiceBookValue[]']").val() !== '') {

                                if (objectId == '20006' || objectId == '20007' || objectId == '20001' || objectId == '20005') {

                                    if ($thisRow.find("input[name='gl_isdebit[]']").val() == '1') {
                                        runBpGlAccountRow_<?php echo $this->uniqId; ?>(checkData.DEBIT_PROCESS_ID, tr, true, 'defaultInvoiceBook', isEditMode, checkData, selectedRow);
                                    } else {
                                        runBpGlAccountRow_<?php echo $this->uniqId; ?>(checkData.CREDIT_PROCESS_ID, tr, false, 'defaultInvoiceBook', isEditMode, checkData, selectedRow);
                                    }

                                } else {
                                    callDetailedItemsInDialog_<?php echo $this->uniqId; ?>(tr, appendRow, selectedRow, checkData, true);
                                }

                            } else if ((objectId == '20003' || objectId == '20004' || objectId == '30004') && $thisRow.find("input[name='defaultInvoiceBook[]']").val() !== '') {

                                var debitProcessId = checkData.DEBIT_PROCESS_ID;
                                var creditProcessId = checkData.CREDIT_PROCESS_ID;

                                if (isEditMode) {
                                    if (checkData.DEBIT_EDIT_PROCESS_ID !== '') {
                                        debitProcessId = checkData.DEBIT_EDIT_PROCESS_ID;
                                    }
                                    if (checkData.CREDIT_EDIT_PROCESS_ID !== '') {
                                        creditProcessId = checkData.CREDIT_EDIT_PROCESS_ID;
                                    }
                                }

                                if ($thisRow.find("input[name='gl_isdebit[]']").val() == '1') {
                                    runBpGlAccountRow_<?php echo $this->uniqId; ?>(debitProcessId, tr, true, 'defaultInvoiceBook', isEditMode, checkData, selectedRow);
                                } else if ($thisRow.find("input[name='gl_isdebit[]']").val() == '0') {
                                    runBpGlAccountRow_<?php echo $this->uniqId; ?>(creditProcessId, tr, false, 'defaultInvoiceBook', isEditMode, checkData, selectedRow);
                                } else {
                                    chooseDebitOrCredit_<?php echo $this->uniqId; ?>(tr, checkData, selectedRow, appendRow, fromAutoComplete);
                                }

                            } else {
                            
                                if ($thisRow.find("input[name='defaultInvoiceBook[]']").val() !== '') {
                                    if ($thisRow.find("input[name='gl_isdebit[]']").val() == '1') {
                                        runBpGlAccountRow_<?php echo $this->uniqId; ?>(checkData.DEBIT_PROCESS_ID, tr, true, 'defaultInvoiceBook', isEditMode, checkData, selectedRow);
                                    } else if ($thisRow.find("input[name='gl_isdebit[]']").val() == '0') {
                                        runBpGlAccountRow_<?php echo $this->uniqId; ?>(checkData.CREDIT_PROCESS_ID, tr, false, 'defaultInvoiceBook', isEditMode, checkData, selectedRow);
                                    } else{
                                        chooseDebitOrCredit_<?php echo $this->uniqId; ?>(tr, checkData, selectedRow, appendRow, fromAutoComplete);
                                    }
                                } else {
                                    chooseDebitOrCredit_<?php echo $this->uniqId; ?>(tr, checkData, selectedRow, appendRow, fromAutoComplete);
                                }

                                if ($thisRow.find("td.gl-action-column").find("div#detailedMeta").length == 0) {
                                    var dtlbuttonclass = '';
                                    if ($(glBpMainWindow_<?php echo $this->uniqId; ?>).find("input[name='glIsComplete']").val() == 'true' 
                                        || $(glBpMainWindow_<?php echo $this->uniqId; ?>).find("input[name='glIsComplete']").val() == '1' 
                                        || $thisRow.find("input[name='gl_rowislock[]']").val() == '1') {
                                        dtlbuttonclass = 'disabled';
                                    }
                                    
                                    if (dtlbuttonclass !== 'disabled') {
                                        $thisRow.find("td.gl-action-column").prepend("<div class='btn btn-xs blue' id='detailedMeta' title='Дэлгэрэнгүй' onclick='expandGlDtl_<?php echo $this->uniqId; ?>(this);'>...</div>");
                                    }
                                }
                            }
                        }
                    }
                    
                    accountMetas_<?php echo $this->uniqId; ?>(tr, checkData);
                }
            });
            
            if (isProcess) {
                bpSetGlMetaRowIndex(glBpMainWindow_<?php echo $this->uniqId; ?>);
                return;
            }
            
        } else {
            setEqualizedAmount_<?php echo $this->uniqId; ?>(tr);
            selectedRow.debitamount = $thisRow.find("input[name='gl_debitAmount[]']").val();
            selectedRow.creditamount = $thisRow.find("input[name='gl_creditAmount[]']").val();
        }
        
        /*var isButtonCreate = false;
        if (objectId === '20001' || $thisRow.find("input[name='gl_useDetailBook[]']").val() === '1') {
            isButtonCreate = undefined;
        }*/        
        callDetailedItemsInDialog_<?php echo $this->uniqId; ?>(tr, appendRow, selectedRow, false, false);
    }
    
    function accountMetas_<?php echo $this->uniqId; ?>(tr, checkData) {
        var $thisRow = $(tr).find("td.gl-action-column");
        
        if (typeof checkData.isDebitCreditDefaultValue !== 'undefined') {
            $(tr).attr('data-isdebitcreditdefaultvalue', '1');
        }
        
        if (typeof checkData.isMeta !== 'undefined') {
            if ($thisRow.find(".gl-dtl-meta-btn").length == 0) {
                
                if (checkData.hasOwnProperty('expenseCenterControl')) {
                    
                    $(tr).find("td.glRowExpenseCenter").html(checkData.expenseCenterControl);
                    
                    if (!checkData.hasOwnProperty('expenseCenterControlOnly')) {
                        if ($thisRow.find('#detailedMeta').length > 0 && $thisRow.find('.btn.red').length > 0) {
                            var $lastHeadCell = $("table#glDtl > thead > tr > th:last-child", glBpMainWindow_<?php echo $this->uniqId; ?>);
                            $lastHeadCell.css({'width': '111px', 'min-width': '84px'});
                        }
                        if ($thisRow.find('.gl-dtl-meta-btn').length == 0) {
                            $thisRow.prepend("<div class='btn btn-xs purple-plum gl-dtl-meta-btn' title='Үзүүлэлт' onclick='showDtlMeta_<?php echo $this->uniqId; ?>(this, true);'>...</div>");
                        }
                    }
                        
                } else {
                
                    $(tr).find("td.glRowExpenseCenter").empty();
                    
                    if ($thisRow.find('#detailedMeta').length > 0 && $thisRow.find('.btn.red').length > 0) {
                        var $lastHeadCell = $("table#glDtl > thead > tr > th:last-child", glBpMainWindow_<?php echo $this->uniqId; ?>);
                        $lastHeadCell.css({'width': '111px', 'min-width': '84px'});
                    }
                    if ($thisRow.find('.gl-dtl-meta-btn').length == 0) {
                        $thisRow.prepend("<div class='btn btn-xs purple-plum gl-dtl-meta-btn' title='Үзүүлэлт' onclick='showDtlMeta_<?php echo $this->uniqId; ?>(this, true);'>...</div>");
                    }
                }
            } 
        } else {
            $thisRow.find(".gl-dtl-meta-btn").remove();
            $(tr).find("td.glRowExpenseCenter").empty();
        }

        checkAccountFilterConfig_<?php echo $this->uniqId; ?>(tr, 'all');
        
        if (typeof checkData.isOppMeta !== 'undefined') {
            $(tr).attr('data-op-meta', checkData.isOppMeta);
            metaBtnByOpMeta_<?php echo $this->uniqId; ?>($(tr));
        }
        return;
    }
    function addBook_<?php echo $this->uniqId; ?>(tr){
        var dataViewClass = $("#bookGrid", tr).find("div:first").attr('class');
        var dataViewId = dataViewClass.split("-")[1];
        window['basketCommonSelectableDataGrid_' + dataViewId]();
    }
    
    function expandGlDtl_<?php echo $this->uniqId; ?>(elem) {
        var $tr = $(elem).closest('tr');
        $tr.closest('table').find('> tbody > tr.currentTRtarget').removeClass('currentTRtarget');
        $tr.addClass('currentTRtarget');        
        glRowExpand_<?php echo $this->uniqId; ?>($tr, '', '');
    }
    function showDtlMeta_<?php echo $this->uniqId; ?>(elem, isOnlyMeta) {
        var $tr = $(elem).closest('tr');
        var appendRow = $tr.find("td.gl-action-column");
        var $dialogName = 'dialog-expandedGlDtl';
        $tr.closest("table").find("tbody").find("tr").removeClass("currentTRtarget");
        $tr.addClass("currentTRtarget");  
        
        var isEditMode = ($tr.find("input[name='gl_dtlId[]']").val() !== '') ? true : false;
        var subid = $tr.find("input[name='gl_subid[]']").val();
        var accountid = $tr.find("input[name='gl_accountId[]']").val();
        
        var bookDate = $("input[name='glbookDate']", glEntryWindowId_<?php echo $this->uniqId; ?>).val();
        var glbookId = $("input[name='glbookId']", glEntryWindowId_<?php echo $this->uniqId; ?>).val();
        var glBookTypeId = $("input[name='glBookTypeId']", glEntryWindowId_<?php echo $this->uniqId; ?>).val();
        var glbookNumber = $("input[name='glbookNumber']", glEntryWindowId_<?php echo $this->uniqId; ?>).val();
        var gldescription = $("#gldescription", glEntryWindowId_<?php echo $this->uniqId; ?>).val();
        
        if (typeof bookDate === 'undefined') {
            bookDate = $("input[name='hidden_glbookDate']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
            glbookId = $("input[name='glbookId']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
            glBookTypeId = $("input[name='glBookTypeId']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
            glbookNumber = $("input[name='glbookNumber']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
            gldescription = $("#gldescription", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
        }

        var selectedRow = {
            'bookid': glbookId,
            'bookdate': bookDate,
            'booktypeid': glBookTypeId,
            'booknumber': glbookNumber,
            'description': gldescription,
            'accountid': $tr.find("input[name='gl_accountId[]']").val(),
            'accountcode': $tr.find("input[name='gl_accountCode[]']").val(),
            'accountname': $tr.find("input[name='gl_accountName[]']").val(),
            'accounttypeid': $tr.find("input[name='gl_main_accounttypeid[]']").val(),
            'accounttypecode': $tr.find("input[name='gl_accounttypeCode[]']").val(),
            'subid': $tr.find("input[name='gl_subid[]']").val(),
            'debitamount': $tr.find("input[name='gl_debitAmount[]']").val(), 
            'creditamount': $tr.find("input[name='gl_creditAmount[]']").val(), 
            'rate': $tr.find("input[name='gl_rate[]']").autoNumeric("get"), 
            'usedetail': $tr.find("input[name='gl_useDetailBook[]']").val(), 
            'objectid': $tr.find("input[name='gl_objectId[]']").val(), 
            'invoices': $tr.find("input[name='invoiceBookValue[]']").val(), 
            'defaultinvoices': $tr.find("input[name='defaultInvoiceBook[]']").val(), 
            'isdebit': $tr.find("input[name='gl_isdebit[]']").val(), 
            'customerid': $tr.find("input[name='gl_customerId[]']").val(), 
            'dtlid': $tr.find("input[name='gl_dtlId[]']").val(), 
            'detailvalues': $tr.find("input[name='gl_metas[]']").val(), 
            glMainWindowId: "#<?php echo isset($this->glBpMainWindowId) ? $this->glBpMainWindowId : "glTemplateSectionProcess"; ?>", 
            'isMetaDirectOpen': true 
        };
        
        callDetailedItemsInDialog_<?php echo $this->uniqId; ?>($tr, appendRow, selectedRow, false, isEditMode, false, isOnlyMeta);
    }
    function checkIsUseGlDetail_<?php echo $this->uniqId; ?>(expandRemove) {
        
        var paramGL = paramGLData_<?php echo $this->uniqId; ?>;
        var lastHeadCell = $("table#glDtl > thead > tr > th:last-child", glBpMainWindow_<?php echo $this->uniqId; ?>);
        var bookDate = $("input[name='glbookDate']", glEntryWindowId_<?php echo $this->uniqId; ?>).val();
        var glbookNumber = $("input[name='glbookNumber']", glEntryWindowId_<?php echo $this->uniqId; ?>).val();
        var gldescription = $("#gldescription", glEntryWindowId_<?php echo $this->uniqId; ?>).val();
        if (typeof bookDate === 'undefined') {
            bookDate = $("input[name='hidden_glbookDate']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
            glbookNumber = $("input[name='hidden_glbookNumber']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
            gldescription = $("input[name='hidden_gldescription']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
        }
        var glbookId = $("input[name='glbookId']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
        var glBookTypeId = $("input[name='glBookTypeId']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
        var $dialogName = 'dialog-expandedGlDtl';
                
        $("table#glDtl > tbody > tr:not([data-gl-row-load])", glBpMainWindow_<?php echo $this->uniqId; ?>).each(function(i, k) {
            var thisRow = $(this);
            var accountId = thisRow.find("input[name='gl_accountId[]']").val();
            var accountObjectId = thisRow.find("input[name='gl_objectId[]']").val();
            
            if (accountId !== '') {
                
                if (paramGL.hasOwnProperty('booktypeid') 
                    && '<?php echo Config::getFromCache('IS_ECONOMIC_CLASS'); ?>' != '1' 
                    && (paramGL.booktypeid == '42' || paramGL.booktypeid == '43' || paramGL.booktypeid == '44')
                    && (accountObjectId == '20003' || accountObjectId == '20004')) {
                    return true;
                }
        
                var subId = thisRow.find("input[name='gl_subid[]']").val();
                var appendRow = thisRow.find("td.gl-action-column");
                
                if (typeof expandRemove !== 'undefined') {
                    $("#" + $dialogName, appendRow).find(".select2").select2("destroy");
                    $("#" + $dialogName, appendRow).empty().dialog('destroy').remove();
                    appendRow.find("#" + $dialogName).remove();
                    appendRow.find("#detailedMeta").remove();
                }
                
                var isDebit = thisRow.find("input[name='gl_isdebit[]']").val();
                
                if (isDebit == '') {
                    var debit = Number(thisRow.find("input[name='gl_debitAmount[]']").val());
                    var credit = Number(thisRow.find("input[name='gl_creditAmount[]']").val());
                    if (credit > debit) {
                        isDebit = 0;
                    }
                }
                
                var selectedRow = {
                    'bookid': glbookId,
                    'booktypeid': glBookTypeId,
                    'bookdate': bookDate,
                    'booknumber': glbookNumber,
                    'description': gldescription,
                    'accountid': accountId,
                    'accountcode': thisRow.find("input[name='gl_accountCode[]']").val(),
                    'accountname': thisRow.find("input[name='gl_accountName[]']").val(),
                    'accounttypeid': thisRow.find("input[name='gl_main_accounttypeid[]']").val(),
                    'accounttypecode': thisRow.find("input[name='gl_accounttypeCode[]']").val(),
                    'subid': subId,
                    'debitamount': thisRow.find("input[name='gl_debitAmount[]']").val(),
                    'creditamount': thisRow.find("input[name='gl_creditAmount[]']").val(),
                    'rate': thisRow.find("input[name='gl_rate[]']").autoNumeric("get"),
                    'usedetail': thisRow.find("input[name='gl_useDetailBook[]']").val(),
                    'objectid': accountObjectId,
                    'invoices': thisRow.find("input[name='invoiceBookValue[]']").val(),
                    'defaultinvoices': thisRow.find("input[name='defaultInvoiceBook[]']").val(),
                    'isdebit': isDebit,
                    'customerid': thisRow.find("input[name='gl_customerId[]']").val(),
                    'dtlid': thisRow.find("input[name='gl_dtlId[]']").val(),
                    'detailvalues': thisRow.find("input[name='gl_metas[]']").val(), 
                    'glMainWindowId': "#<?php echo isset($this->glBpMainWindowId) ? $this->glBpMainWindowId : 'glTemplateSectionProcess'; ?>"
                };
                
                var opMeta = fillOpMeta_<?php echo $this->uniqId; ?>(thisRow, accountId, subId, isDebit);
                if (opMeta != '') {
                    selectedRow['opMeta'] = opMeta;
                }
                if (checkAccountTypeId_<?php echo $this->uniqId; ?>(thisRow, accountId, subId, isDebit)) {
                    selectedRow['checkAccountTypeId'] = 1;
                }
                
                $.ajax({
                    type: 'post',
                    url: 'mdgl/getAccountDtlMeta',
                    data: {selectedRow: selectedRow, paramData: paramGLData_<?php echo $this->uniqId; ?>},
                    dataType: 'json',
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function(data) {
                        
                        if (data.isemptymeta != '1' || (thisRow.find("input[name='invoiceBookValue[]']").val() == '' && thisRow.find("input[name='defaultInvoiceBook[]']").val() != '')) { 
                       
                            if (thisRow.find("td.gl-action-column").find("#detailedMeta").length == 0) {
                                
                                var dtlbuttonclass = '';
                                if ($(glBpMainWindow_<?php echo $this->uniqId; ?>).find("input[name='glIsComplete']").val() == 'true' 
                                    || $(glBpMainWindow_<?php echo $this->uniqId; ?>).find("input[name='glIsComplete']").val() == '1' 
                                    || thisRow.find("input[name='gl_rowislock[]']").val() == '1') {
                                    dtlbuttonclass = 'disabled';
                                }
                                
                                if (dtlbuttonclass !== 'disabled' && thisRow.find("input[name='gl_objectId[]']").val() !== '' && thisRow.find("input[name='gl_useDetailBook[]']").val() === '1') {
                                   
                                    if (thisRow.find("input[name='gl_objectId[]']").val() != '20006' && thisRow.find("input[name='gl_objectId[]']").val() != '20007') {
                                        thisRow.find("input[name='gl_accountCode[]']").prop('readonly', true).parent().find('button').prop('disabled', true); 
                                    }
                                    
                                    thisRow.find("td.gl-action-column").prepend("<div class='btn btn-xs blue' id='detailedMeta' title='Дэлгэрэнгүй' onclick='expandGlDtl_<?php echo $this->uniqId; ?>(this);'>...</div>");
                                    
                                } else if (thisRow.find("input[name='gl_objectId[]']").val() == '20001') {
                                    thisRow.find("td.gl-action-column").prepend("<div class='btn btn-xs blue' id='detailedMeta' title='Дэлгэрэнгүй' onclick='expandGlDtl_<?php echo $this->uniqId; ?>(this);'>...</div>");
                                }                               
                                    
                                var $html = $('<div />', {html: data.html});

                                if ($html.find("table tr").length > 0) {
                                    if (thisRow.find("td.gl-action-column").find('#detailedMeta').length > 0 && thisRow.find("td.gl-action-column").find('.btn.red').length > 0) {
                                        lastHeadCell.css({'width': '111px', 'min-width': '84px'});
                                    }
                                    if (thisRow.find('.gl-dtl-meta-btn').length == 0) {
                                        thisRow.find("td.gl-action-column").prepend("<div class='btn btn-xs purple-plum gl-dtl-meta-btn' title='Үзүүлэлт' onclick='showDtlMeta_<?php echo $this->uniqId; ?>(this);'>...</div>");
                                    }
                                }
                                
                                if (data.hasOwnProperty('accountData') && data.accountData && Object.keys(data.accountData).length) {
                                    thisRow.find("input[name='gl_accountFilterConfig[]']").val(data.accountData[0]['ACCOUNT_FILTER']);
                                    checkAccountFilterConfig_<?php echo $this->uniqId; ?>(thisRow, 'all');
                                }                                
                            }
                        }
                        
                        thisRow.find("input[name='gl_ismetas[]']").val(data.isemptymeta);
                        
                        if (data.hasOwnProperty('expenseCenterControl')) {
                            thisRow.find("td.glRowExpenseCenter").html(data.expenseCenterControl);
                        } else {
                            thisRow.find("td.glRowExpenseCenter").empty();
                        }
                        
                        if (data.hasOwnProperty('isUseOppAccount')) {
                            thisRow.attr('data-op-meta', data.isUseOppAccount);
                            metaBtnByOpMeta_<?php echo $this->uniqId; ?>(thisRow);
                        }
                        
                        bpSetGlMetaOneRowIndex(i, thisRow); 
                    
                        Core.unblockUI();
                    },
                    error: function() {
                        alert("Error");
                    }
                });
                
                thisRow.attr('data-gl-row-load', '1');
            }
        });
    }
    function checkIsComplete_<?php echo $this->uniqId; ?>(){
        var $glWindow = $(glBpMainWindow_<?php echo $this->uniqId; ?>);
        if ($glWindow.find("input[name='glIsComplete']").val() == 'true' 
            || $glWindow.find("input[name='glIsComplete']").val() == '1') {
        
            $glWindow.find("input,textarea,select").attr("readonly", "readonly");
            $glWindow.find("button,div,input[type='checkbox']").attr("disabled", "disabled");
        }
    }
    function invoiceSelectabledGridFillRows_<?php echo $this->uniqId; ?>(dataViewId, accountid, tr, $dialogName) {
    
        Core.blockUI({
            message: 'Түр хүлээнэ үү...',
            boxed: true
        }); 
        
        setTimeout(function() {
            var rows = $('#commonSelectableBasketDataGrid_'+dataViewId).datagrid('getRows'); 
            var currentRow = $(tr);
            var mainBody = currentRow.closest('tbody');

            for (var i = 0; i < rows.length; i++) {

                var row = rows[i];

                if (i == 0) {

                    if (Number(row.debitamount) > Number(row.creditamount)) {
                        var newDebit = row.debitamount;
                        var newDebitBase = row.debitamountbase;
                        var newCredit = 0;
                        var newCreditBase = 0;
                    } else {
                        var newDebit = 0;
                        var newDebitBase = 0;
                        var newCredit = row.creditamount;
                        var newCreditBase = row.creditamountbase;
                    }

                    currentRow.find("input[name='gl_debitAmount[]']").val(newDebit);
                    currentRow.find("input[name='gl_creditAmount[]']").val(newCredit);
                    currentRow.find("input[name='gl_debitAmountBase[]']").val(newDebitBase);
                    currentRow.find("input[name='gl_creditAmountBase[]']").val(newCreditBase);
                    currentRow.find("input[data-input-name='debitAmount']").autoNumeric('set', newDebit);
                    currentRow.find("input[data-input-name='creditAmount']").autoNumeric('set', newCredit);
                    currentRow.find("input[data-input-name='debitAmountBase']").autoNumeric('set', newDebitBase);
                    currentRow.find("input[data-input-name='creditAmountBase']").autoNumeric('set', newCreditBase);

                    currentRow.find("input[name*='gl_invoiceBookId[']").val(row.id);
                    currentRow.find("input[name='invoiceBookValue[]']").val(row.id);
                    currentRow.find("input[name='gl_rowdescription[]']").val(row.description);

                    if (row.hasOwnProperty('customerid') && (row.customerid != '' || row.customerid != null)) {
                        $.ajax({
                            type: 'post',
                            url: 'mdgl/autoCompleteByCustomerCode',
                            dataType: 'json',
                            async: false,
                            data: {id: row.customerid},
                            success: function (dataCrm) {
                                if (dataCrm != false) {
                                    currentRow.find("input[name='gl_customerId[]']").val(dataCrm[0].CUSTOMER_ID);
                                    currentRow.find("input[name='gl_customerCode[]']").val(dataCrm[0].CUSTOMER_CODE);
                                    currentRow.find("input[name='gl_customerName[]']").val(dataCrm[0].CUSTOMER_NAME);  
                                }          
                            }
                        });
                    }

                    currentRow.find('#detailedMeta').remove();
                    currentRow.find('input[type=text]:visible').attr('readonly', 'readonly');
                    currentRow.find('button').attr('disabled', 'disabled');

                    $("#" + $dialogName, tr).dialog('close');

                } else {

                    var clonedRow = currentRow.clone();

                    Core.initNumberInput(clonedRow);
                    Core.initAccountCodeMask(clonedRow);

                    if (Number(row.debitamount) > Number(row.creditamount)) {
                        var newDebit = row.debitamount;
                        var newDebitBase = row.debitamountbase;
                        var newCredit = 0;
                        var newCreditBase = 0;
                    } else {
                        var newDebit = 0;
                        var newDebitBase = 0;
                        var newCredit = row.creditamount;
                        var newCreditBase = row.creditamountbase;
                    }

                    clonedRow.find("input[name='gl_debitAmount[]']").val(newDebit);
                    clonedRow.find("input[name='gl_creditAmount[]']").val(newCredit);
                    clonedRow.find("input[name='gl_debitAmountBase[]']").val(newDebitBase);
                    clonedRow.find("input[name='gl_creditAmountBase[]']").val(newCreditBase);
                    clonedRow.find("input[data-input-name='debitAmount']").autoNumeric('set', newDebit);
                    clonedRow.find("input[data-input-name='creditAmount']").autoNumeric('set', newCredit);
                    clonedRow.find("input[data-input-name='debitAmountBase']").autoNumeric('set', newDebitBase);
                    clonedRow.find("input[data-input-name='creditAmountBase']").autoNumeric('set', newCreditBase);

                    clonedRow.find("input[name*='gl_invoiceBookId[']").val(row.id);
                    clonedRow.find("input[name='invoiceBookValue[]']").val(row.id);
                    clonedRow.find("input[name='gl_rowdescription[]']").val(row.description);

                    if (row.hasOwnProperty('customerid') && (row.customerid != '' || row.customerid != null)) {
                        $.ajax({
                            type: 'post',
                            url: 'mdgl/autoCompleteByCustomerCode',
                            dataType: 'json',
                            async: false,
                            data: {id: row.customerid}, 
                            success: function (dataCrm) {
                                if (dataCrm != false) {
                                    clonedRow.find("input[name='gl_customerId[]']").val(dataCrm[0].CUSTOMER_ID);
                                    clonedRow.find("input[name='gl_customerCode[]']").val(dataCrm[0].CUSTOMER_CODE);
                                    clonedRow.find("input[name='gl_customerName[]']").val(dataCrm[0].CUSTOMER_NAME);  
                                }          
                            }
                        });
                    }

                    clonedRow.find('input[type=text]:visible, select:visible').attr('readonly', 'readonly');
                    clonedRow.find('button').attr('disabled', 'disabled');

                    mainBody.append(clonedRow);
                }
            }

            glTableFreeze_<?php echo $this->uniqId; ?>();
            Core.unblockUI();
            $(glBpMainWindow_<?php echo $this->uniqId; ?>).find('input#glquickCode').focus();
        }, 10);
    }
    function invoiceSelectabledGrid(tr, dataViewId, accountid) {  
        var rows = $('#commonSelectableBasketDataGrid_'+dataViewId).datagrid('getRows');
        var invoiceIds = '';
        var invoiceNumbers = '';
        var invoiceTotalCreditAmount = 0;
        var invoiceTotalCreditAmountBase = 0;
        var invoiceTotalDebitAmount = 0;
        var invoiceTotalDebitAmountBase = 0;
        var invoiceTotalRate = 0;
        
        for (var i = 0; i < rows.length; i++) {
            invoiceTotalDebitAmount = invoiceTotalDebitAmount + parseFloat(rows[i].debitamount);
            invoiceTotalDebitAmountBase = invoiceTotalDebitAmountBase + parseFloat(rows[i].debitamountbase);
            invoiceTotalCreditAmount = invoiceTotalCreditAmount + parseFloat(rows[i].creditamount);
            invoiceTotalCreditAmountBase = invoiceTotalCreditAmountBase + parseFloat(rows[i].creditamountbase);
            invoiceTotalRate = invoiceTotalRate + parseFloat(rows[i].baserate);
            invoiceIds += rows[i].id + ',';
            invoiceNumbers += rows[i].booknumber + ',';
        }
        
        if (invoiceTotalDebitAmount > invoiceTotalCreditAmount) {
            $("input[name='book_debitamount[" + accountid + "]']", tr).val(invoiceTotalDebitAmount);
            $("input[name='book_debitamountbase[" + accountid + "]']", tr).val(invoiceTotalDebitAmountBase);
            $("input[name='book_creditamount[" + accountid + "]']", tr).val(0);
            $("input[name='book_creditamountbase[" + accountid + "]']", tr).val(0);
            $("input[name='book_rate[" + accountid + "]']", tr).val(invoiceTotalRate / rows.length);
        } else {
            $("input[name='book_creditamount[" + accountid + "]']", tr).val(invoiceTotalCreditAmount);
            $("input[name='book_creditamountbase[" + accountid + "]']", tr).val(invoiceTotalCreditAmountBase);
            $("input[name='book_debitamount[" + accountid + "]']", tr).val(0);
            $("input[name='book_debitamountbase[" + accountid + "]']", tr).val(0);
            $("input[name='book_rate[" + accountid + "]']", tr).val(invoiceTotalRate / rows.length);
        }
        if (rows.length == 1) {
            $("input[name='book_desc[" + accountid + "]']", tr).val(rows[0].description);     
        }   

        $("input[name='gl_invoiceBookId[]']", tr).val(invoiceIds.slice(0, -1));
    }
    function clearglDtlTr_<?php echo $this->uniqId; ?>(tr){
        $(tr).find("input:not([id='gl_subid'], [id='gl_debitamount'], [id='gl_creditamount'])").val('');
        /*$(tr).find("input[name='gl_accountId[]'], input[name='gl_accountCode[]'], input[name='gl_accountName[]'], input[name='defaultInvoiceBook[]'], input[name='gl_processId[]'], input[name='gl_dtlId[]'], input[name='gl_description[]']").val('');*/
        $(tr).find("input[name='gl_isEdited[]']").val('0');
        $(tr).find("input[name='gl_rate[]']").autoNumeric("set", 1);
        if ($(tr).find("td.gl-action-column").find("#detailedMeta").length > 0) {
           $(tr).find("td.gl-action-column").find("#detailedMeta").remove();
        }
        checkIsUseBase_<?php echo $this->uniqId; ?>(tr);
    }
    function refreshTrIndex_<?php echo $this->uniqId; ?>(){
        $("table#glDtl > tbody > tr", glBpMainWindow_<?php echo $this->uniqId; ?>).each(function(i) {
            $(this).attr('data-row-index', i);
        });
    }
    function runBpGlAccountRow_<?php echo $this->uniqId; ?>(metaDataId, tr, isDebit, setJsonField, isEditMode, checkData, selectedRow) {
        
        $('#dialog-chooseDebitOrCredit').dialog('close');
        
        var isEditMode = (typeof isEditMode !== 'undefined' ? isEditMode : false);
        
        if (isDebit == '1') {
            isDebit = true;
        } else if (isDebit == '0') {
            isDebit = false;
        }
        
        var bookDate = $("input[name='glbookDate']", glEntryWindowId_<?php echo $this->uniqId; ?>).val();
        if (typeof bookDate === 'undefined') {
            bookDate = $("input[name='hidden_glbookDate']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
        }
        
        var $rowElem = $(tr);
        var processUrl = 'mdwebservice/callMethodByMeta';
        var objectid = $rowElem.find("input[name='gl_objectId[]']").val();                                                                      
        var recordId = $rowElem.find("input[name*='gl_invoiceBookId[']").val();
        
        var debitAmountRow = Number($rowElem.find("input[name='gl_debitAmount[]']").val());
        var debitAmountBaseRow = Number($rowElem.find("input[name='gl_debitAmountBase[]']").val());
        var creditAmountRow = Number($rowElem.find("input[name='gl_creditAmount[]']").val());
        var creditAmountBaseRow = Number($rowElem.find("input[name='gl_creditAmountBase[]']").val());
        var rateCurrency = ($rowElem.find("input[name='gl_rate_currency[]']").val()).toLowerCase();
        var rowRate = $rowElem.find("input[name='gl_rate[]']").autoNumeric("get");
        
        if (rateCurrency == 'mnt') {
            debitAmountBaseRow = 0;
            creditAmountBaseRow = 0;
            rowRate = 1;
        }
        
        var debitAmount = debitAmountRow;
        var debitAmountBase = debitAmountBaseRow;
        var creditAmount = creditAmountRow;
        var creditAmountBase = creditAmountBaseRow;
        
        var fillJsonParam = $rowElem.find("input[name='"+setJsonField+"[]']").val(), addonJsonParam = {}, isSaveHide = '';
        
        if (recordId != '' && fillJsonParam == '' && isEditMode == true && (typeof checkData !== 'undefined') && (typeof selectedRow !== 'undefined')) {
            
            var fillJsonObj = {
                'recordId': recordId, 
                'checkData': checkData, 
                'selectedRow': selectedRow
            };
            
            fillJsonParam = JSON.stringify(fillJsonObj);
            
            processUrl = 'mdgl/callBpWindow';
            
        } else {
            
            if (objectid == '20006' || objectid == '20007') {
                
                var subId = $rowElem.find("input[name='gl_subid[]']").val();
                var sumCreditAmount = 0;
                var sumDebitAmount = 0;

                $('table#glDtl > tbody > tr', glBpMainWindow_<?php echo $this->uniqId; ?>).each(function() {
                    var $thisRow = $(this);
                    var this_subid = $thisRow.find("input[name='gl_subid[]']").val();
                    if (subId == this_subid) {
                        sumCreditAmount += Number($thisRow.find("input[name='gl_creditAmount[]']").val());
                        sumDebitAmount += Number($thisRow.find("input[name='gl_debitAmount[]']").val());
                    }
                }); 

                if (isDebit) {
                    
                    creditAmount = (sumCreditAmount - sumDebitAmount + debitAmount);
                    
                    if (creditAmount == 0) {
                        creditAmount = sumDebitAmount;
                    } 

                    if (creditAmount < 0) {
                        creditAmount = (creditAmount * -1);
                    }

                    creditAmountBase = debitAmountBase;
                    debitAmountBase = 0;
                    debitAmount = 0;

                } else {
                    
                    debitAmount = (sumDebitAmount - sumCreditAmount + creditAmount);
                    
                    if (debitAmount == 0) {
                        debitAmount = sumCreditAmount;
                    }

                    if (debitAmount < 0) {
                        debitAmount = (debitAmount * -1);
                    }
                    
                    debitAmountBase = creditAmountBase;
                    creditAmountBase = 0;
                    creditAmount = 0;
                }
            } 

            addonJsonParam = {
                'accountId': $rowElem.find("input[name='gl_accountId[]']").val(), 
                'customerId': $rowElem.find("input[name='gl_customerId[]']").val(),
                'debitAmount': debitAmount,
                'debitAmountBase': debitAmountBase,
                'creditAmount': creditAmount,
                'creditAmountBase': creditAmountBase,
                'bookDate': bookDate,
                'rate': rowRate 
            };

            if ($rowElem.find("input[name='gl_dtlId[]']").val() === '' || $rowElem.find("input[name='defaultInvoiceBook[]']").val() === '') {
                addonJsonParam['description'] = $rowElem.find("input[name='gl_rowdescription[]']").val();
            }

            if ($rowElem.find('input#srcInvoiceBook').length && $rowElem.find('input#srcInvoiceBook').val() !== '' && $rowElem.find("input[name='defaultInvoiceBook[]']").val() === '') {
                addonJsonParam['srcInvoiceBook'] = JSON.parse($rowElem.find('input#srcInvoiceBook').val());
            }
            
            if (paramGLData_<?php echo $this->uniqId; ?>.hasOwnProperty('targetid') && paramGLData_<?php echo $this->uniqId; ?>.targetid) {
                addonJsonParam['internalTargetId'] = paramGLData_<?php echo $this->uniqId; ?>.targetid;
            }

            if (isEditMode && (objectid === '20001' || objectid === '20003' || objectid === '20004' || objectid === '30004')) {
                addonJsonParam = {};
            }
        }
        
        if (objectid === '20001') {
            isSaveHide = 'hide';
        }
        
        var $dialogName = 'dialog-glBp-' + metaDataId;
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);
        
        $.ajax({
            type: 'post',
            url: processUrl,
            data: {
                metaDataId: metaDataId, 
                isDialog: true, 
                isSystemMeta: false, 
                fillJsonParam: fillJsonParam, 
                addonJsonParam: JSON.stringify(addonJsonParam),
                responseType: 'json', 
                callerType: 'generalledger', 
                openParams: '{"callerType":"generalledger"}', 
                recordId: recordId 
            },
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function (data) {
                
                if (data.hasOwnProperty('errorMsg')) {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Error',
                        text: data.errorMsg,
                        type: 'error',
                        sticker: false
                    });
                    return;
                }
                
                $dialog.empty().append(data.Html);
                
                var processForm = $("#wsForm", "#" + $dialogName);
                var processUniqId = processForm.parent().attr('data-bp-uniq-id');

                var buttons = [
                    {text: data.run_btn, class: 'btn green-meadow btn-sm bp-btn-save '+isSaveHide, click: function (e) {
                        if (window['processBeforeSave_'+processUniqId]($(e.target))) {     

                            processForm.validate({ 
                                ignore: '', 
                                highlight: function(element) {
                                    $(element).addClass('error');
                                    $(element).parent().addClass('error');
                                    if (processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                        processForm.find("div.tab-pane:hidden:has(.error)").each(function(index, tab){
                                            var tabId = $(tab).attr("id");
                                            processForm.find('a[href="#'+tabId+'"]').tab('show');
                                        });
                                    }
                                },
                                unhighlight: function(element) {
                                    $(element).removeClass('error');
                                    $(element).parent().removeClass('error');
                                },
                                errorPlacement: function(){} 
                            });
                            
                            var isValidPattern = initBusinessProcessMaskEvent(processForm);
                            
                            if (processForm.valid() && isValidPattern.length === 0) {
                                        
                                processForm.ajaxSubmit({
                                    type: 'post',
                                    url: 'mdwebservice/runProcess',
                                    dataType: 'json',
                                    beforeSend: function () {
                                        Core.blockUI({boxed: true, message: 'Түр хүлээнэ үү'});
                                    },
                                    success: function (responseData) {
                                        if (responseData.status === 'success') {
                                            
                                            var processMetaId = responseData.processId;
                                            var responseParam = responseData.paramData;
                                            var responseParam2 = responseParam;
                                            var description = '', rate = 1, debitAmount = 0, debitAmountBase = 0, creditAmount = 0, creditAmountBase = 0 , customercode = '', customername = '', responseRecBook;
                                            
                                            var isUseReceivableRate = '<?php echo Config::getFromCache('ISUSERECEIVABLERATE'); ?>';
                                            var isUsePayableRate = '<?php echo Config::getFromCache('ISUSEPAYABLERATE'); ?>';
                                            
                                            if (((responseParam.hasOwnProperty('receivableBookDtls') && responseParam.receivableBookDtls.length > 1 && isUseReceivableRate == '1') 
                                                || (responseParam.hasOwnProperty('payableBookDtls') && responseParam.payableBookDtls.length > 1 && isUsePayableRate == '1')) 
                                                && rateCurrency != 'mnt') {
                                                
                                                var dtlPath = '';
                                                if (objectid == '20006') {
                                                    dtlPath = 'payableBookDtls';
                                                } else if (objectid == '20007') {
                                                    dtlPath = 'receivableBookDtls';
                                                }                                                
                                                
                                                /*if (objectid == '20006' || objectid == '20007') {
                                                    $rowElem.closest('tbody').find('> tr').each(function(i, elemtr) {
                                                        if ($(elemtr).find('input[name="gl_objectId[]"]').val() == objectid && !$(elemtr).hasClass('currentTRtarget')) {
                                                            removeGlDtl_<?php echo $this->uniqId; ?>(elemtr);
                                                        }
                                                    });
                                                }*/
                                                
                                                var responseParam3 = responseParam[dtlPath], rbookInd = 0;
                                                
                                                for (var rbook = 0; rbook < responseParam3.length; rbook++) {
                                                    
                                                    debitAmount = 0; 
                                                    debitAmountBase = 0; 
                                                    creditAmount = 0;
                                                    creditAmountBase = 0;
                                                    
                                                    responseRecBook = objToChangeLowerKeys(responseParam3[rbook]);
                                                    delete responseParam2[dtlPath];
                                                    
                                                    if (responseRecBook.rowstate != 'removed') {
                                                        if (rbook === rbookInd) {
                                                            responseParam2[dtlPath] = [responseParam3[rbook]];
                                                            $rowElem.find("input[name='"+setJsonField+"[]']").val(JSON.stringify(responseParam2));

                                                            if (typeof responseRecBook.debitamount !== 'undefined') {
                                                                debitAmount = responseRecBook.debitamount;
                                                            }
                                                            if (typeof responseRecBook.debitamountbase !== 'undefined') {
                                                                debitAmountBase = responseRecBook.debitamountbase;
                                                            }
                                                            if (typeof responseRecBook.creditamount !== 'undefined') {
                                                                creditAmount = responseRecBook.creditamount;
                                                            }
                                                            if (typeof responseRecBook.creditamountbase !== 'undefined') {
                                                                creditAmountBase = responseRecBook.creditamountbase;
                                                            }
                                                            if (typeof responseRecBook.rate !== 'undefined') {
                                                                rate = responseRecBook.rate;
                                                            }

                                                            if (typeof responseRecBook.description !== 'undefined') {
                                                                description = responseRecBook.description;
                                                            } else {
                                                                if ($rowElem.find("input[name='gl_rowdescription[]']").val() == '') {
                                                                    var bookDescription = $("#gldescription", glEntryWindowId_<?php echo $this->uniqId; ?>).val();
                                                                    if (typeof bookDescription === 'undefined') {
                                                                        description = $("#gldescription", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
                                                                    }
                                                                }
                                                            }

                                                            if (typeof responseRecBook.customerid !== 'undefined') {
                                                                $rowElem.find("input[name='gl_customerId[]']").val(responseParam.customerId);
                                                            }

                                                            checkRate = Number(rate);
                                                            if (checkRate === 0 || checkRate === null) {
                                                                rate = 1;
                                                            }
                                                            $rowElem.find("input[name='gl_rate[]']").autoNumeric('set', rate);

                                                            $rowElem.find("input[name='gl_rowdescription[]']").val(description);
                                                            $rowElem.find("input[name='gl_customerCode[]']").val(customercode);
                                                            $rowElem.find("input[name='gl_customerName[]']").val(customername);
                                                            $rowElem.find("input[name='gl_isEdited[]']").val('1');
                                                            $rowElem.find("input[name='gl_processId[]']").val(processMetaId);

                                                            if (isDebit === false) {
                                                                $rowElem.find("input[name='gl_isdebit[]']").val('0');
                                                                $rowElem.find("input[name='gl_debitAmount[]']").val('0');
                                                                $rowElem.find("input[name='gl_debitAmountBase[]']").val('0');
                                                                $rowElem.find("input[name='gl_creditAmount[]']").val(creditAmount);
                                                                $rowElem.find("input[name='gl_creditAmountBase[]']").val(creditAmountBase);

                                                                $rowElem.find("input[data-input-name='debitAmount']").autoNumeric('set', 0);
                                                                $rowElem.find("input[data-input-name='debitAmountBase']").autoNumeric('set', 0);
                                                                $rowElem.find("input[data-input-name='creditAmount']").autoNumeric('set', creditAmount);
                                                                $rowElem.find("input[data-input-name='creditAmountBase']").autoNumeric('set', creditAmountBase);
                                                            } else {
                                                                $rowElem.find("input[name='gl_isdebit[]']").val('1');
                                                                $rowElem.find("input[name='gl_debitAmount[]']").val(debitAmount);
                                                                $rowElem.find("input[name='gl_debitAmountBase[]']").val(debitAmountBase);
                                                                $rowElem.find("input[name='gl_creditAmount[]']").val(0);
                                                                $rowElem.find("input[name='gl_creditAmountBase[]']").val(0);

                                                                $rowElem.find("input[data-input-name='debitAmount']").autoNumeric('set', debitAmount);
                                                                $rowElem.find("input[data-input-name='debitAmountBase']").autoNumeric('set', debitAmountBase);
                                                                $rowElem.find("input[data-input-name='creditAmount']").autoNumeric('set', 0);
                                                                $rowElem.find("input[data-input-name='creditAmountBase']").autoNumeric('set', 0);
                                                            }

                                                            if (typeof responseParam.accountId !== 'undefined' && responseParam.accountId != '' 
                                                                && responseParam.accountId != $rowElem.find("input[name='gl_accountId[]']").val()) { 
                                                                setAccountDvData_<?php echo $this->uniqId; ?>($rowElem, responseParam.accountId);
                                                            }

                                                            setCustomerDvData_<?php echo $this->uniqId; ?>(tr, responseParam.customerId);
                                                            calculateFooterSum_<?php echo $this->uniqId; ?>(tr);      

                                                        } else {

                                                            if (typeof responseRecBook.debitamount !== 'undefined') {
                                                                debitAmount = responseRecBook.debitamount;
                                                            }
                                                            if (typeof responseRecBook.debitamountbase !== 'undefined') {
                                                                debitAmountBase = responseRecBook.debitamountbase;
                                                            }
                                                            if (typeof responseRecBook.creditamount !== 'undefined') {
                                                                creditAmount = responseRecBook.creditamount;
                                                            }
                                                            if (typeof responseRecBook.creditamountbase !== 'undefined') {
                                                                creditAmountBase = responseRecBook.creditamountbase;
                                                            }
                                                            
                                                            if (Number(debitAmount) == 0 && Number(debitAmountBase) == 0 && Number(creditAmount) == 0 && Number(creditAmountBase) == 0) {
                                                                continue;
                                                            }
                                                            
                                                            var $rowElemClone = $rowElem.clone();
                                                            var mainBody = $rowElem.closest('tbody');
                                                            responseParam2[dtlPath] = [responseParam3[rbook]];
                                                            $rowElemClone.find("input[name='"+setJsonField+"[]']").val(JSON.stringify(responseParam2));           
                                                            $rowElemClone.removeClass("currentTRtarget").removeClass("gl-selected-row");

                                                            Core.initNumberInput($rowElemClone);
                                                            Core.initAccountCodeMask($rowElemClone);  
                                                            
                                                            if (typeof responseRecBook.rate !== 'undefined') {
                                                                rate = responseRecBook.rate;
                                                            }

                                                            if (typeof responseRecBook.description !== 'undefined') {
                                                                description = responseRecBook.description;
                                                            } else {
                                                                if ($rowElemClone.find("input[name='gl_rowdescription[]']").val() == '') {
                                                                    var bookDescription = $("#gldescription", glEntryWindowId_<?php echo $this->uniqId; ?>).val();
                                                                    if (typeof bookDescription === 'undefined') {
                                                                        description = $("#gldescription", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
                                                                    }
                                                                }
                                                            }

                                                            if (typeof responseParam.customerId !== 'undefined') {
                                                                $rowElemClone.find("input[name='gl_customerId[]']").val(responseParam.customerId);
                                                            }

                                                            checkRate = Number(rate);
                                                            if (checkRate === 0 || checkRate === null) {
                                                                rate = 1;
                                                            }
                                                            $rowElemClone.find("input[name='gl_rate[]']").autoNumeric('set', rate);

                                                            $rowElemClone.find("input[name='gl_rowdescription[]']").val(description);
                                                            $rowElemClone.find("input[name='gl_customerCode[]']").val(customercode);
                                                            $rowElemClone.find("input[name='gl_customerName[]']").val(customername);
                                                            $rowElemClone.find("input[name='gl_isEdited[]']").val('1');
                                                            $rowElemClone.find("input[name='gl_processId[]']").val(processMetaId);

                                                            if (isDebit === false) {
                                                                $rowElemClone.find("input[name='gl_isdebit[]']").val('0');
                                                                $rowElemClone.find("input[name='gl_debitAmount[]']").val('0');
                                                                $rowElemClone.find("input[name='gl_debitAmountBase[]']").val('0');
                                                                $rowElemClone.find("input[name='gl_creditAmount[]']").val(creditAmount);
                                                                $rowElemClone.find("input[name='gl_creditAmountBase[]']").val(creditAmountBase);

                                                                $rowElemClone.find("input[data-input-name='debitAmount']").autoNumeric('set', 0);
                                                                $rowElemClone.find("input[data-input-name='debitAmountBase']").autoNumeric('set', 0);
                                                                $rowElemClone.find("input[data-input-name='creditAmount']").autoNumeric('set', creditAmount);
                                                                $rowElemClone.find("input[data-input-name='creditAmountBase']").autoNumeric('set', creditAmountBase);
                                                            } else {
                                                                $rowElemClone.find("input[name='gl_isdebit[]']").val('1');
                                                                $rowElemClone.find("input[name='gl_debitAmount[]']").val(debitAmount);
                                                                $rowElemClone.find("input[name='gl_debitAmountBase[]']").val(debitAmountBase);
                                                                $rowElemClone.find("input[name='gl_creditAmount[]']").val(0);
                                                                $rowElemClone.find("input[name='gl_creditAmountBase[]']").val(0);

                                                                $rowElemClone.find("input[data-input-name='debitAmount']").autoNumeric('set', debitAmount);
                                                                $rowElemClone.find("input[data-input-name='debitAmountBase']").autoNumeric('set', debitAmountBase);
                                                                $rowElemClone.find("input[data-input-name='creditAmount']").autoNumeric('set', 0);
                                                                $rowElemClone.find("input[data-input-name='creditAmountBase']").autoNumeric('set', 0);
                                                            }

                                                            if (typeof responseParam.accountId !== 'undefined' && responseParam.accountId != '' 
                                                                && responseParam.accountId != $rowElemClone.find("input[name='gl_accountId[]']").val()) { 
                                                                setAccountDvData_<?php echo $this->uniqId; ?>($rowElemClone, responseParam.accountId);
                                                            }

                                                            $rowElemClone.insertAfter($rowElem);
                                                            setCustomerDvData_<?php echo $this->uniqId; ?>($rowElemClone, responseParam.customerId);
                                                            calculateFooterSum_<?php echo $this->uniqId; ?>($rowElemClone);                              
                                                        }
                                                        
                                                    } else {
                                                        rbookInd++;
                                                    }
                                                }
                                                refreshTrIndex_<?php echo $this->uniqId; ?>();
                                                
                                            } else {
                                                                                                
                                                $rowElem.find("input[name='"+setJsonField+"[]']").val(JSON.stringify(responseParam));

                                                if (typeof responseParam.debitAmount !== 'undefined') {
                                                    debitAmount = responseParam.debitAmount;
                                                }
                                                if (typeof responseParam.debitAmountBase !== 'undefined') {
                                                    debitAmountBase = responseParam.debitAmountBase;
                                                }
                                                if (typeof responseParam.creditAmount !== 'undefined') {
                                                    creditAmount = responseParam.creditAmount;
                                                }
                                                if (typeof responseParam.creditAmountBase !== 'undefined') {
                                                    creditAmountBase = responseParam.creditAmountBase;
                                                }
                                                if (typeof responseParam.rate !== 'undefined') {
                                                    rate = responseParam.rate;
                                                } else {
                                                    if (objectid == '20006') {
                                                        if (typeof responseParam.payableBookDtls[0].rate !== 'undefined') {
                                                            rate = responseParam.payableBookDtls[0].rate;
                                                        }
                                                    } else if (objectid == '20007') {
                                                        if (typeof responseParam.receivableBookDtls[0].rate !== 'undefined') {
                                                            rate = responseParam.receivableBookDtls[0].rate;
                                                        }
                                                    } else {
                                                        rate = rowRate;
                                                    }
                                                }

                                                if (typeof responseParam.description !== 'undefined') {
                                                    description = responseParam.description;
                                                } else {
                                                    if ($rowElem.find("input[name='gl_rowdescription[]']").val() == '') {
                                                        var bookDescription = $("#gldescription", glEntryWindowId_<?php echo $this->uniqId; ?>).val();
                                                        if (typeof bookDescription === 'undefined') {
                                                            description = $("#gldescription", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
                                                        }
                                                    }
                                                }

                                                if (typeof responseParam.customerId !== 'undefined') {
                                                    $rowElem.find("input[name='gl_customerId[]']").val(responseParam.customerId);
                                                }

                                                checkRate = Number(rate);
                                                if (checkRate === 0 || checkRate === null) {
                                                    rate = 1;
                                                }

                                                var isSetRate = false;

                                                if (objectid == '20007' && responseParam.hasOwnProperty('receivableBookDtls') && rateCurrency !== 'mnt') {
                                                    var receivableBkDtls = responseParam.receivableBookDtls;
                                                    if (receivableBkDtls.length === 1) {
                                                        var receivableBkDtlRow = responseParam.receivableBookDtls[0];
                                                        if (receivableBkDtlRow.hasOwnProperty('rate') && Number(receivableBkDtlRow.rate) > 1) {
                                                            var newRate = receivableBkDtlRow.rate;
                                                            if (newRate.toString().indexOf('.') !== -1) {
                                                                var parts = newRate.toString().split('.');
                                                                var ratePrecision = parts[1].length;
                                                                if (ratePrecision > 2) {
                                                                    var setOption = JSON.parse('{"mDec": '+ratePrecision+'}'), 
                                                                        $rateInput = $rowElem.find("input[name='gl_rate[]']");

                                                                    $rateInput.attr('data-mdec', ratePrecision+'.'+ratePrecision);
                                                                    $rateInput.autoNumeric('update', setOption);
                                                                    $rateInput.autoNumeric('set', rate);

                                                                    isSetRate = true;
                                                                }
                                                            }
                                                        }
                                                    }
                                                } 

                                                if (!isSetRate) {
                                                    $rowElem.find("input[name='gl_rate[]']").autoNumeric('set', rate);
                                                }

                                                $rowElem.find("input[name='gl_rowdescription[]']").val(description);
                                                $rowElem.find("input[name='gl_customerCode[]']").val(customercode);
                                                $rowElem.find("input[name='gl_customerName[]']").val(customername);
                                                $rowElem.find("input[name='gl_isEdited[]']").val('1');
                                                $rowElem.find("input[name='gl_processId[]']").val(processMetaId);

                                                if (isDebit == false) {
                                                    $rowElem.find("input[name='gl_isdebit[]']").val('0');
                                                    $rowElem.find("input[name='gl_debitAmount[]']").val('0');
                                                    $rowElem.find("input[name='gl_debitAmountBase[]']").val('0');
                                                    $rowElem.find("input[name='gl_creditAmount[]']").val(creditAmount);
                                                    $rowElem.find("input[name='gl_creditAmountBase[]']").val(creditAmountBase);

                                                    $rowElem.find("input[data-input-name='debitAmount']").autoNumeric('set', 0);
                                                    $rowElem.find("input[data-input-name='debitAmountBase']").autoNumeric('set', 0);
                                                    $rowElem.find("input[data-input-name='creditAmount']").autoNumeric('set', creditAmount);
                                                    $rowElem.find("input[data-input-name='creditAmountBase']").autoNumeric('set', creditAmountBase);
                                                } else {
                                                    $rowElem.find("input[name='gl_isdebit[]']").val('1');
                                                    $rowElem.find("input[name='gl_debitAmount[]']").val(debitAmount);
                                                    $rowElem.find("input[name='gl_debitAmountBase[]']").val(debitAmountBase);
                                                    $rowElem.find("input[name='gl_creditAmount[]']").val(0);
                                                    $rowElem.find("input[name='gl_creditAmountBase[]']").val(0);

                                                    $rowElem.find("input[data-input-name='debitAmount']").autoNumeric('set', debitAmount);
                                                    $rowElem.find("input[data-input-name='debitAmountBase']").autoNumeric('set', debitAmountBase);
                                                    $rowElem.find("input[data-input-name='creditAmount']").autoNumeric('set', 0);
                                                    $rowElem.find("input[data-input-name='creditAmountBase']").autoNumeric('set', 0);
                                                }

                                                if (typeof responseParam.accountId !== 'undefined' && responseParam.accountId != '' 
                                                    && responseParam.accountId != $rowElem.find("input[name='gl_accountId[]']").val()) { 
                                                    setAccountDvData_<?php echo $this->uniqId; ?>($rowElem, responseParam.accountId);
                                                }

                                                setCustomerDvData_<?php echo $this->uniqId; ?>(tr, responseParam.customerId);
                                                calculateFooterSum_<?php echo $this->uniqId; ?>(tr);
                                            }
                                            
                                            var showMetaPayableReceievable = '<?php echo Config::getFromCache('isShowMetaPayableReceievable'); ?>';
                                            if (((responseParam.hasOwnProperty('receivableBookDtls') && responseParam.receivableBookDtls.length) 
                                                || (responseParam.hasOwnProperty('payableBookDtls') && responseParam.payableBookDtls.length)) && showMetaPayableReceievable == '1') {
                                                if ($rowElem.find('.gl-dtl-meta-btn:visible').length) {
                                                    $rowElem.find('.gl-dtl-meta-btn:visible').trigger('click');
                                                }
                                            }

                                            $dialog.dialog('close');
                                        } 
                                        Core.unblockUI();
                                    },
                                    error: function () {
                                        alert("Error");
                                    }
                                });
                            }
                        }    
                    }},
                    {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ];

                var dialogWidth = data.dialogWidth, dialogHeight = data.dialogHeight;
                
                if (data.isDialogSize === 'auto') {
                    dialogWidth = 1350;
                    dialogHeight = 'auto';
                }

                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: dialogWidth,
                    height: dialogHeight,
                    modal: true,
                    closeOnEscape: (typeof isCloseOnEscape == 'undefined' ? true : isCloseOnEscape), 
                    open: function () {
                        if ($dialog.find("[data-path='isUsedGl']").length) {
                            $dialog.find("[data-label-path='isUsedGl'], [data-section-path='isUsedGl']").css({'display': 'none'});
                        }
                    },
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: buttons
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
                    }
                });
                if (data.dialogSize === 'fullscreen') {
                    $dialog.dialogExtend('maximize');
                }
                $dialog.dialog('open');
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            Core.initBPAjax($dialog);
            Core.unblockUI();
        });
        
        if ($rowElem.find("td.gl-action-column").find("div#detailedMeta").length == 0) {
            var dtlbuttonclass = '';
            if ($(glBpMainWindow_<?php echo $this->uniqId; ?>).find("input[name='glIsComplete']").val() == 'true' 
                || $(glBpMainWindow_<?php echo $this->uniqId; ?>).find("input[name='glIsComplete']").val() == '1' 
                || $rowElem.find("input[name='gl_rowislock[]']").val() == '1') {
                dtlbuttonclass = 'disabled';
            }
            
            if (dtlbuttonclass !== 'disabled') {
                $rowElem.find("td.gl-action-column").prepend("<div class='btn btn-xs blue' id='detailedMeta' title='Дэлгэрэнгүй' onclick='expandGlDtl_<?php echo $this->uniqId; ?>(this);'>...</div>");
            }
        }
    }
    function callDetailedItemsInDialog_<?php echo $this->uniqId; ?>(tr, appendRow, selectedRow, checkData, isEditMode, isButtonCreate, isOnlyMeta) {
        if (typeof isEditMode === 'undefined') {
            isEditMode = false;
        }
        if (typeof checkData === 'undefined') {
            checkData = false;
        }
        if (typeof isButtonCreate === 'undefined') {
            isButtonCreate = true;
        }
        if (typeof isOnlyMeta === 'undefined') {
            isOnlyMeta = false;
        }
        
        var $dialogName = 'dialog-expandedGlDtl';
        appendRow.append('<div id="' + $dialogName + '"></div>');
        var $rowDialog = $('#' + $dialogName, tr);
        
        if ($rowDialog.children().length === 0) {
                
            if (selectedRow['isdebit'] == '') {
                var debit = Number(selectedRow['debitamount']);
                var credit = Number(selectedRow['creditamount']);
                
                if (debit == 0 && credit == 0) {
                    selectedRow['isdebit'] = '';
                } else {
                    if (credit > debit) {
                        selectedRow['isdebit'] = 0;
                    } else {
                        selectedRow['isdebit'] = 1;
                    }
                }
            }
            
            var opMeta = fillOpMeta_<?php echo $this->uniqId; ?>($(tr), selectedRow['accountid'], selectedRow['subid'], selectedRow['isdebit']);
            if (opMeta !== '') {
                selectedRow['opMeta'] = opMeta;
            } else {
                selectedRow['opMeta'] = 'cashFlowSubCategoryId';
            }
            
            if (checkAccountTypeId_<?php echo $this->uniqId; ?>($(tr), selectedRow['accountid'], selectedRow['subid'], selectedRow['isdebit'])) {
                selectedRow['checkAccountTypeId'] = 1;
            }
            
            if (Object.keys(defaultValueDimensions_<?php echo $this->uniqId; ?>).length) {
                var detailvalues = (selectedRow['detailvalues'] != '' && selectedRow['detailvalues'] != null) ? JSON.parse(selectedRow['detailvalues']) : {};
                
                for (var d in defaultValueDimensions_<?php echo $this->uniqId; ?>) {
                    if (!detailvalues.hasOwnProperty(d) || (detailvalues.hasOwnProperty(d) && detailvalues[d] == '' && detailvalues[d] == null)) {
                        detailvalues[d] = defaultValueDimensions_<?php echo $this->uniqId; ?>[d];
                    }
                }
                
                selectedRow['detailvalues'] = JSON.stringify(detailvalues);
            }            
                
            $.ajax({
                type: 'post',
                url: 'mdgl/getAccountDtlMeta',
                data: {selectedRow: selectedRow, paramData: paramGLData_<?php echo $this->uniqId; ?>},
                async: false,
                dataType: 'json',
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function(data) {
                    if (data.isemptymeta !== '1') { // && data.isemptymeta !== true
                        
                        if (data.isDebitCreditDefaultValue === '1') {
                            $(tr).attr('data-isdebitcreditdefaultvalue', '1');
                        }
                                            
                        $rowDialog.empty().hide().append(data.html);
                        var hideBasketButton = 'hide';
                        
                        if (isOnlyMeta === false && selectedRow.usedetail === '1' && (selectedRow.objectid !== '20006' && selectedRow.objectid !== '20007')) {
                            
                            hideBasketButton = '';
                            
                            if ($("#bookGrid", tr).length > 0) {
                                
                                var accountid = $(tr).find("input[name='gl_accountId[]']").val();
                                var searchDefaultParam = '';
                                
                                if (isEditMode) {
                                    searchDefaultParam = 'param[filterIsConnectGlstring]=0&criteriaCondition[filterIsConnectGlstring]==&param[isdebit]='+selectedRow.isdebit+'&criteriaCondition[filterIsDebitString]==&param[filterIsDebitString]='+selectedRow.isdebit+'&param[accountid]='+accountid;
                                } else {
                                    searchDefaultParam = 'param[filterStartDate]=fiscalperiodstartdate&param[filterEndDate]=fiscalperiodenddate&param[filterIsConnectGlstring]=0&criteriaCondition[filterIsConnectGlstring]==&param[isdebit]='+selectedRow.isdebit+'&criteriaCondition[filterIsDebitString]==&param[filterIsDebitString]='+selectedRow.isdebit+'&param[accountid]='+accountid;
                                }

                                var metaData = '';

                                if (checkData) {
                                    metaData = checkData;
                                    metaData['IS_DEBIT'] = $(tr).find("input[name='gl_isdebit[]']").val();
                                } else {
                                    var metaDataCode = '';
                                }
                                var chooseType = 'multi';
                                
                                if (metaDataCode !== '' || metaData !== '') {
                                    $.ajax({
                                        type: 'post',
                                        async: false,
                                        url: 'mdmetadata/dataViewCustomSelectableGrid',
                                        data: {
                                            metaData: metaData,
                                            metaDataCode: metaDataCode,
                                            chooseType: chooseType,
                                            params: encodeURIComponent(searchDefaultParam),
                                            selectedRows: $("input[name='gl_invoiceBookId[]']", tr).serializeArray(),
                                            accountId: accountid,
                                            uniqId: data.uniqId
                                        },
                                        dataType: 'json',
                                        beforeSend: function() {
                                            Core.blockUI({
                                                message: 'Loading...',
                                                boxed: true
                                            });
                                        },
                                        success: function(data) {
                                            $("#bookGrid", tr).empty().append(data.Html);
                                            Core.unblockUI();
                                        },
                                        error: function() {
                                            alert("Error");
                                        }
                                    }).done(function() {        
                                        $("input[name*='gl_invoiceBookId[']", tr).val($("input#gl_invoiceBookId_popup", tr).val());
                                        Core.initDVAjax($("#bookGrid", tr));
                                    });
                                } 
                            }
                        }
                            
                        if (($("#bookGrid", tr).length > 0 && $("#bookGrid", tr).html() !== '') || $("#glExpandedWindow", tr).find("table:eq(0) tr").length > 0) {
                            var glMetaWidth = 520;
                            hideBasketButton = 'hide';
                            
                            if ($("#bookGrid", tr).html() !== '' && typeof $("#bookGrid", tr).html() !== 'undefined'){
                                glMetaWidth = 1350;
                                hideBasketButton = '';
                            }
                            /*if ($("#glExpandedWindow", tr).find("table:eq(0) tr:first-child > td").length > 2) {
                                glMetaWidth = 1200;
                            }*/
                            
                            /*if ($("div[id^='dialog-connectgl-']").length > 0) {
                                var _parent = $("div[id^='dialog-connectgl-']");
                                _parent.css('position', 'static');
                                _parent.find(glBpMainWindow_<?php echo $this->uniqId; ?>).children().css('position', 'static');
                                _parent.parent().css('overflow', 'inherit');
                            }*/
                            
                            if ($("div[id^='bp-window-']:visible").length > 0) {  
                                var _parent = $("div[id^='bp-window-']:visible").parent();
                                _parent.css('position', 'static');
                                _parent.find("div.center-sidebar").css('position', 'static');            
                                _parent.find(glBpMainWindow_<?php echo $this->uniqId; ?>).children().css('position', 'static');
                                _parent.parent().css('overflow', 'inherit');
                            }
    
                            $rowDialog.dialog({
                                appendTo: appendRow,
                                create: function(event, ui) {
                                    $(event.target).parent().css('position', 'fixed');
                                },                                
                                cache: false,
                                resizable: true,
                                draggable: false,
                                bgiframe: true,
                                autoOpen: false,
                                title: data.title,
                                async: false,
                                width: glMetaWidth,
                                height: 'auto', 
                                minHeight: 150,
                                modal: true,                           
                                open: function(){
                                    // $(event.target).dialog('widget')
                                    // .css({ position: 'fixed' })
                                    // .position({ my: 'center', at: 'center', of: window });

                                    if ($("div[id^='dialog-connectgl-']").length > 0) {
                                        
                                        var _parent = $("div[id^='dialog-connectgl-']");
                                        _parent.css('position', 'static');
                                        _parent.find(glBpMainWindow_<?php echo $this->uniqId; ?>).children().css('position', 'static');
                                        _parent.parent().css('overflow', 'inherit');
                                        
                                    } /*else if ($("div[id^='bp-window-']:visible").length > 0) {
                                        
                                        var _parent = $("div[id^='bp-window-']:visible").parent();
                                        _parent.css('position', 'static');
                                        _parent.find("div.center-sidebar").css('position', 'static');            
                                        _parent.find(glBpMainWindow_<?php echo $this->uniqId; ?>).children().css('position', 'static');
                                        _parent.parent().css('overflow', 'inherit');
                                    }*/ /*else {
                                        var _parent = $(glEntryWindowId_<?php echo $this->uniqId; ?>).parent();
                                        _parent.css('position', 'static');
                                        _parent.find(glEntryWindowId_<?php echo $this->uniqId; ?>).children().css('position', 'static');
                                        _parent.parent().css('overflow', 'inherit');
                                    }*/
                            
                                    if (glMetaWidth === 520) {
                                        $("#glExpandedWindow", tr).find('hr').remove();
                                    }
                                    $("#glExpandedWindow", tr).find("select.select2").each(function() {
                                        var thisSelect = $(this);
                                        if (!thisSelect.data('select2')) {
                                            thisSelect.select2({
                                                allowClear: true
                                            });
                                        }
                                    });
                                    if (hideBasketButton == 'hide') {
                                        /*$rowDialog.parent().find('.ui-dialog-titlebar-close').css({'display': 'none'});*/
                                        setTimeout(function(){
                                            var $emptyInputs = $rowDialog.find('input[type="text"]:visible:first').filter(function() { return this.value == ''; }); 
                                            $emptyInputs.eq(0).focus();
                                        }, 10);
                                    }
                                    
                                    var $isMetaDuplicateOpen = $rowDialog.find('input.is-ac-meta-duplicate:checked');
                                    
                                    if ($isMetaDuplicateOpen.length) {
                                        $isMetaDuplicateOpen.prop('checked', false);
                                        $isMetaDuplicateOpen.removeAttr('checked');
                                        $.uniform.update($isMetaDuplicateOpen);
                                    }
                                    
                                    $rowDialog.parent().find('button.bp-btn-save').on('keydown', function (event) {
                                        if (event.keyCode == 13) {
                                           $(this).click();
                                           return false;
                                        }
                                    });
                                },
                                close: function () {
                                    if ($("div[id^='dialog-connectgl-']").length > 0) {
                                        var _parent = $("div[id^='dialog-connectgl-']");
                                        _parent.parent().css('position', 'absolute');
                                    }    
                                },                                
                                buttons: [
                                    {text: 'Сагсанд нэмэх', class: 'btn btn-sm green-meadow float-left '+ hideBasketButton, click: function() {
                                        addBook_<?php echo $this->uniqId; ?>(tr);
                                    }},
                                    {text: data.save_btn, class: 'btn btn-sm green-meadow bp-btn-save', click: function() {
                                        PNotify.removeAll();
                                        
                                        var validDtl = true;
                                        $rowDialog.find('input,textarea,select').filter('[required="required"]').removeClass('error');
                                        
                                        $rowDialog.find('input,textarea,select').filter('[required="required"]').each(function(){
                                            if (($(this).attr('id') != 'accountId_displayField' && $(this).attr('id') != 'accountId_nameField') && $(this).val() == '') {
                                                $(this).addClass('error');  
                                                validDtl = false;
                                            }
                                        });
                                        
                                        if (validDtl) {

                                            if ($rowDialog.find("input#gl_invoiceBookId_popup").length > 0 
                                                && ($("#glPayable", tr).length == 0 && $("#glReceivable", tr).length == 0) && ($("#bookGrid", tr).find("div:first").length > 0)) {
                                                    
                                                var dataViewClass = $("#bookGrid", tr).find("div:first").attr('class');
                                                var dataViewId = dataViewClass.split('-');
                                                var rows = $('#commonSelectableBasketDataGrid_'+dataViewId[1]).datagrid('getRows');

                                                if (rows.length > 0) {

                                                    if (rows.length == 1) {

                                                        invoiceSelectabledGrid(tr, dataViewId[1], accountid);

                                                        var newDebit = $rowDialog.find("input[name='book_debitamount[" + accountid + "]']").val();
                                                        var newCredit = $rowDialog.find("input[name='book_creditamount[" + accountid + "]']").val();
                                                        var newDebitBase = $rowDialog.find("input[name='book_debitamountbase[" + accountid + "]']").val();
                                                        var newCreditBase = $rowDialog.find("input[name='book_creditamountbase[" + accountid + "]']").val();
                                                        var newRate = $rowDialog.find("input[name='book_rate[" + accountid + "]']").val();
                                                        var newDescription = $rowDialog.find("input[name='book_desc[" + accountid + "]']").val();

                                                        $(tr).find("input[name='invoiceBookValue[]']").val($rowDialog.find("input#gl_invoiceBookId_popup").val());
                                                        $(tr).find("input[name='gl_debitAmount[]']").val(newDebit);
                                                        $(tr).find("input[name='gl_creditAmount[]']").val(newCredit);
                                                        $(tr).find("input[name='gl_debitAmountBase[]']").val(newDebitBase);
                                                        $(tr).find("input[name='gl_creditAmountBase[]']").val(newCreditBase);
                                                        $(tr).find("input[data-input-name='debitAmount']").autoNumeric('set', newDebit);
                                                        $(tr).find("input[data-input-name='creditAmount']").autoNumeric('set', newCredit);
                                                        $(tr).find("input[data-input-name='debitAmountBase']").autoNumeric('set', newDebitBase);
                                                        $(tr).find("input[data-input-name='creditAmountBase']").autoNumeric('set', newCreditBase);
                                                        $(tr).find("input[name='gl_rate[]']").autoNumeric('set', newRate);
                                                        $(tr).find("input[name='gl_rowdescription[]']").val(newDescription);

                                                        if (newCredit > newDebit) {
                                                            $(tr).find("input[name='gl_isdebit[]']").val('0');
                                                        }

                                                    } else {
                                                        invoiceSelectabledGridFillRows_<?php echo $this->uniqId; ?>(dataViewId[1], accountid, tr, $dialogName);
                                                    }

                                                    checkIsUseDetail_<?php echo $this->uniqId; ?>($(tr).find("input[name='gl_useDetailBook[]']").val(), tr);
                                                    calculateFooterSum_<?php echo $this->uniqId; ?>(tr);

                                                    $rowDialog.dialog('close');

                                                } else {
                                                    new PNotify({
                                                        title: 'Error',
                                                        text: 'Баримт сонгогдоогүй байна',
                                                        type: 'error',
                                                        sticker: false
                                                    });
                                                }
                                                
                                            } else {
                                                
                                                var $isMetaDuplicate = $rowDialog.find('input.is-ac-meta-duplicate:checked');
                                                
                                                if ($isMetaDuplicate.length) {
                                                    var $accRow = $(tr), accTypeId = $accRow.find('input[name="gl_main_accounttypeid[]"]').val();    
                                                    
                                                    $('#glDtl > tbody > tr[data-sub-id]', glBpMainWindow_<?php echo $this->uniqId; ?>).each(function() {
                                                        var $cRow = $(this), childAccTypeId = $cRow.find('input[name="gl_main_accounttypeid[]"]').val();    
                                                        
                                                        if (childAccTypeId == accTypeId) {
                                                            
                                                            var $rowMeta = $cRow.find('input[name="gl_metas[]"]'), rowMetaVal = $rowMeta.val(), rowMetaObj = {};
                                                            if (rowMetaVal != '') {
                                                                rowMetaObj = JSON.parse(rowMetaVal);
                                                            }
                                                    
                                                            $isMetaDuplicate.each(function(){
                                                                var $dRow = $(this).closest('tr'), dPath = $dRow.attr('data-cell-path'), 
                                                                    $dPath = $dRow.find('[data-path="'+dPath+'"]'), 
                                                                    dValue = $dPath.val();
                                                                    
                                                                rowMetaObj[dPath.toLowerCase()] = dValue;
                                                                
                                                                var $rowPath = $cRow.find('[data-path="'+dPath+'"]');
                                                                
                                                                if ($rowPath.length) {
                                                                    if ($rowPath.hasClass('popupInit')) {
                                                                        var $rowParent = $rowPath.closest('.input-group');
                                                                        var $dParent = $dPath.closest('.input-group');
                                                                        var dCode = $dParent.find('.lookup-code-autocomplete').val();
                                                                        var dName = $dParent.find('.lookup-name-autocomplete').val();
                                                                        
                                                                        $rowParent.find('input[type="hidden"]').val(dValue);
                                                                        $rowParent.find('.lookup-code-autocomplete').val(dCode).attr('title', dCode);
                                                                        $rowParent.find('.lookup-name-autocomplete').val(dName).attr('title', dName);
                                                                        
                                                                    } else if ($rowPath.hasClass('select2')) {
                                                                        $rowPath.select2('val', dValue);
                                                                        $rowPath.find('option:selected').removeAttr('selected');
                                                                        $rowPath.find("option[value='"+dValue+"']").attr('selected', 'selected');
                                                                    }
                                                                }
                                                            });

                                                            $rowMeta.val(JSON.stringify(rowMetaObj));
                                                        }
                                                    });
                                                }

                                                var $isMetaCustomerId = $rowDialog.find('input[data-path="customerId"]');

                                                if ($isMetaCustomerId.length && !$("input[name='glbookId']", glEntryWindowId_<?php echo $this->uniqId; ?>).length) {
                                                    var $rowParent = $rowDialog.closest('tr');
                                                    var $rowParentInput = $isMetaCustomerId.closest('.input-group');
                                                    $rowParent.find('input[name="gl_customerId[]"]').val($rowParentInput.find('input[type="hidden"]').val());
                                                    $rowParent.find('input[name="gl_customerCode[]"]').val($rowParentInput.find('.lookup-code-autocomplete').val()).attr('title', $rowParentInput.find('.lookup-code-autocomplete').val());
                                                    $rowParent.find('input[name="gl_customerName[]"]').val($rowParentInput.find('.lookup-name-autocomplete').val()).attr('title', $rowParentInput.find('.lookup-name-autocomplete').val());                                                    
                                                }
                                                
                                                $rowDialog.dialog('close');
                                            }
                                            
                                        } else {
                                            new PNotify({
                                                title: 'Warning',
                                                text: 'Дэлгэрэнгүй үзүүлэлтийг бүрэн бөглөнө үү',
                                                type: 'warning',
                                                sticker: false
                                            });
                                        }
                                    }}, 
                                    {text: data.close_btn, class: 'btn btn-sm blue-hoki '+hideBasketButton, click: function() {
                                        $rowDialog.dialog('close');                                    
                                    }}
                                ]
                            }).dialogExtend({
                                "closable": true,
                                "maximizable": false,
                                "minimizable": false,
                                "collapsable": false,
                                "dblclick": "maximize",
                                "minimizeLocation": "left",
                                "icons": {
                                    "close": "ui-icon-circle-close",
                                    "maximize": "ui-icon-extlink",
                                    "minimize": "ui-icon-minus",
                                    "collapse": "ui-icon-triangle-1-s",
                                    "restore": "ui-icon-newwin"
                                }
                            });

                            if (hideBasketButton == 'hide') {
                                $rowDialog.dialog('option', 'closeOnEscape', false);
                            }
                            $rowDialog.parent().draggable({handle: '.ui-dialog-titlebar'});
                            
                            if (selectedRow.hasOwnProperty('isMetaDirectOpen') && selectedRow.isMetaDirectOpen) {
                                $rowDialog.dialog('open');
                            } else {
                                var isNotOpen = <?php echo Config::getFromCacheDefault('CONFIG_GL_SINGLE_META_NOTOPEN', null, 'false'); ?>;
                                if (isNotOpen) {
                                    var $getMetaInput = $rowDialog.find('[data-path]');
                                    if ($getMetaInput.length == 1 && $getMetaInput.val() != '') {
                                        console.log('not open');
                                    } else {
                                        $rowDialog.dialog('open');
                                    }
                                } else {
                                    var isNotOpenAllMeta = <?php echo Config::getFromCacheDefault('CONFIG_GL_ALL_META_NOTOPEN', null, 'false'); ?>;

                                    if (isNotOpenAllMeta) {

                                        var $emptyFields = $rowDialog.find('[data-path]').filter(function() {
                                            return $.trim($(this).val()) === '';
                                        });

                                        if (!$emptyFields.length) {
                                            console.log('not open');
                                        } else {
                                            $rowDialog.dialog('open');
                                        }
                                    } else {
                                        $rowDialog.dialog('open');
                                    }
                                }
                            }
                            
                            if (appendRow.find("div#detailedMeta").length === 0 && isButtonCreate) {
                                appendRow.prepend('<div class="btn btn-xs blue" id="detailedMeta" title="Дэлгэрэнгүй" onclick="expandGlDtl_<?php echo $this->uniqId; ?>(this);">...</div>');
                            }
                            
                        } else {
                            
                            $(tr).find("#"+$dialogName).remove();
                            
                            if ($(tr).find("select.gl-row-currency").length > 0 
                                && $(tr).find("select.gl-row-currency").val() != '' 
                                && $(tr).find("input[name='gl_amountLock[]']").val() != '1' 
                                && $(tr).find("input[name='gl_rowislock[]']").val() != '1') {
                            
                                $(tr).find("input[data-input-name='debitAmount']").removeAttr('readonly');
                                $(tr).find("input[data-input-name='creditAmount']").removeAttr('readonly');
                                $(tr).find("input[data-input-name='debitAmountBase']").removeAttr('readonly');
                                $(tr).find("input[data-input-name='creditAmountBase']").removeAttr('readonly');
                            }
                        }
                        
                    } else {
                        $(tr).find("td.gl-action-column").find("#"+$dialogName).remove();
                    }
                    
                    if (data.hasOwnProperty('expenseCenterControl')) {
                        $(tr).find("td.glRowExpenseCenter").html(data.expenseCenterControl);
                    } else {
                        $(tr).find("td.glRowExpenseCenter").empty();
                    }
                    
                    if (data.hasOwnProperty('isUseOppAccount')) {
                        $(tr).attr('data-op-meta', data.isUseOppAccount);
                        metaBtnByOpMeta_<?php echo $this->uniqId; ?>($(tr));
                    }
                    
                    bpSetGlMetaRowIndex(glBpMainWindow_<?php echo $this->uniqId; ?>);
                    hideByAccountFilterConfig_<?php echo $this->uniqId; ?>(selectedRow['subid'], selectedRow['accountid'], tr);
                    nonRequiredCashOnHand_<?php echo $this->uniqId; ?>(selectedRow['subid'], selectedRow['accountid'], tr);
                },
                error: function() {
                    alert("Error");
                }
            }).done(function() {
                Core.unblockUI();
            });            
        } else {
            $rowDialog.dialog('open');
            hideByAccountFilterConfig_<?php echo $this->uniqId; ?>(selectedRow['subid'], selectedRow['accountid'], tr);
            nonRequiredCashOnHand_<?php echo $this->uniqId; ?>(selectedRow['subid'], selectedRow['accountid'], tr);
        }
    }   
    function chooseDebitOrCredit_<?php echo $this->uniqId; ?>(tr, checkData, selectedRow, appendRow, fromAutoComplete){
        
        var choiceHtml = '<div class="row pl10 pr10">Таны сонгосон гүйлгээний төрлөөс хамаарч зохих баримтын цонх дуудагдана<input type="text" size="1" style="position:relative;top:-500px;"/></div>';
        
        var objectId = selectedRow.objectid;
        var hideDialogButton = '', debitHideButton = '', creditHideButton = '';
        
        if (objectId == '20007' || objectId == '20006') {
            hideDialogButton = 'hide';
            
            <?php
            if (Config::getFromCache('CONFIG_GL_IGNORE_ACC_AFTER_POPUP')) {
            ?>
            /*
             * Авлага өглөгийн данс сонгосны дараа процессын цонх дуудагдахгүй, 
             * харин хэрэглэгч өөрөө товч дээр дарсан үед дуудагдана 
             */
            if (fromAutoComplete !== '')
                return;
            <?php
            } 
            ?>
           
            if (selectedRow['debitamount'] > 0) {
                runBpGlAccountRow_<?php echo $this->uniqId; ?>(checkData.DEBIT_PROCESS_ID, tr, true, 'defaultInvoiceBook');
                return;
            } else if (selectedRow['creditamount'] > 0) {
                runBpGlAccountRow_<?php echo $this->uniqId; ?>(checkData.CREDIT_PROCESS_ID, tr, false, 'defaultInvoiceBook');
                return;
            }
            
            calculateFooterSum_<?php echo $this->uniqId; ?>(tr);
        } else {
            setEqualizedAmount_<?php echo $this->uniqId; ?>(tr);
        }
            
        var $curRow = $(tr);
        var invoiceBookId = $curRow.find('input[name="gl_invoiceBookId[]"]').val();
        var defaultInvoiceBook = $curRow.find('input[name="defaultInvoiceBook[]"]').val();

        if (invoiceBookId != '' && defaultInvoiceBook == '') {

            if (Number(selectedRow['debitamount']) > 0) {
                var isBpDebit = true;
            } else if (Number(selectedRow['creditamount']) > 0) {
                var isBpDebit = false;
            }
            
            runBpGlAccountRow_<?php echo $this->uniqId; ?>('', tr, isBpDebit, 'defaultInvoiceBook', true, checkData, selectedRow);
            return;
        }
        
        if (checkData.DATAVIEW_ID === null || checkData.DATAVIEW_ID == '') {
            hideDialogButton = ' hide';
        }
        
        if (checkData.DEBIT_PROCESS_ID === null || checkData.DEBIT_PROCESS_ID == '') {
            debitHideButton = ' hide';
        }
        
        if (checkData.CREDIT_PROCESS_ID === null || checkData.CREDIT_PROCESS_ID == '') {
            creditHideButton = ' hide';
        }
        
        var buttonHtml = '';
        var dialogWidth = 400;
        
        if (checkData.processButtons !== '') {
            hideDialogButton = '';
            debitHideButton = ' hide';
            creditHideButton = ' hide';
            dialogWidth = 500;
            
            buttonHtml = checkData.processButtons;
        }
        
        var $dialogName = 'dialog-chooseDebitOrCredit';
        if (!$('#'+$dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);
        
        $dialog.empty().append(choiceHtml);
        $dialog.dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Баримт', 
            width: dialogWidth, 
            minWidth: 300,
            height: "auto",
            modal: true,
            open: function () {
                /*$(this).closest('.ui-dialog').find('.ui-dialog-buttonpane button:eq(1)').focus();
                $(this).closest('.ui-dialog').find('.ui-dialog-buttonpane button:eq(0)').blur();*/ 
            }, 
            create: function() {
                $(this).closest(".ui-dialog").find(".ui-dialog-buttonset").append(buttonHtml);
            },
            close: function () {
                $dialog.empty().dialog('destroy').remove();
            },
            buttons: [
                {text: 'Баримт холбох', class: 'btn btn-sm btn-primary float-left'+hideDialogButton, click: function() {
                    $dialog.dialog('close');
            
                    selectedRow['debitamount'] = $curRow.find("input[name='gl_debitAmount[]']").val();
                    selectedRow['creditamount'] = $curRow.find("input[name='gl_creditAmount[]']").val();
                    
                    callDetailedItemsInDialog_<?php echo $this->uniqId; ?>(tr, appendRow, selectedRow, checkData, false);
                }},
                {text: 'Дебит', class: 'btn btn-sm btn-success' + debitHideButton, click: function() {
                    $dialog.dialog('close');
                    runBpGlAccountRow_<?php echo $this->uniqId; ?>(checkData.DEBIT_PROCESS_ID, tr, true, 'defaultInvoiceBook');
                }},
                {text: 'Кредит', class: 'btn btn-sm btn-danger' + creditHideButton, click: function() {  
                    $dialog.dialog('close');
                    runBpGlAccountRow_<?php echo $this->uniqId; ?>(checkData.CREDIT_PROCESS_ID, tr, false, 'defaultInvoiceBook'); 
                }}
            ]
        });
        $dialog.dialog('open');
    }
    function setAccountDvData_<?php echo $this->uniqId; ?>(tr, accountId) {
        $.ajax({
            type: 'post',
            url: 'mdgl/getAccountRowById',
            data: {accountId: accountId},
            dataType: 'json',
            async: false,
            success: function (data) {
                if (typeof data.ACCOUNT_ID !== 'undefined') {
                    tr.find("input[name='gl_accountId[]']").val(data.ACCOUNT_ID);
                    tr.find("input[name='gl_accountCode[]']").val(data.ACCOUNT_CODE);
                    tr.find("input[name='gl_accountName[]']").val(data.ACCOUNT_NAME).attr('title', data.ACCOUNT_NAME);
                    tr.find("input[name='gl_rate_currency[]']").val(data.CURRENCY_CODE);
                    checkIsUseBase_<?php echo $this->uniqId; ?>(tr);
                }          
            },
            error: function () {
                alert("Error");
            }
        });
    }
    function setCustomerDvData_<?php echo $this->uniqId; ?>(tr, customerid){
        $.ajax({
            type: 'post',
            url: 'mdgl/autoCompleteByCustomerCode',
            dataType: "json",
            async: false,
            data: {id: customerid},
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...', 
                    boxed: true
                });
            },
            success: function (data) {
                if (data != false) {
                    $(tr).find("input[name='gl_customerId[]']").val(data[0].CUSTOMER_ID);
                    $(tr).find("input[name='gl_customerCode[]']").val(data[0].CUSTOMER_CODE);
                    $(tr).find("input[name='gl_customerName[]']").val(data[0].CUSTOMER_NAME);  
                }          
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            Core.unblockUI();
        });
    }
    function checkRowDescriptionField_<?php echo $this->uniqId; ?>(init){
        var isShowDescrField = <?php echo Config::getFromCacheDefault('CONFIG_GL_ROW_DESC', null, 0); ?>;
        var isShowExpenseCenterField = <?php echo Config::getFromCacheDefault('CONFIG_GL_ROW_EXPENSE_CENTER', null, 0); ?>;
        var $mainWindow = $(init).closest('form');
        var $glDtl = $("#glDtl", $mainWindow);
        
        if (isShowDescrField) {
            $glDtl.find("th.glRowDescr, td.glRowDescr").css({'display': ''});
        } else {
            $glDtl.find("th.glRowDescr, td.glRowDescr").css({'display': 'none'});
        }
        if (isShowExpenseCenterField) {
            $glDtl.find("th.glRowExpenseCenter, td.glRowExpenseCenter").css({'display': ''});
        } else {
            $glDtl.find("th.glRowExpenseCenter, td.glRowExpenseCenter").css({'display': 'none'});
        }
        return;
    }
    function setEqualizedAmount_<?php echo $this->uniqId; ?>(tr, isDebit){
        var $thisRow = $(tr);
        var isGlAmountCopy = $glLoadWindow_<?php echo $this->uniqId; ?>.find('input.is-gl-amount-calc').is(':checked');
        
        if (isGlAmountCopy && $thisRow.find("input[name='gl_debitAmount[]']").val() == '0' 
            && $thisRow.find("input[name='gl_creditAmount[]']").val() == '0') {
            
            var dtAmt = 0, dtAmtFcy = 0, ktAmt = 0, ktAmtFcy = 0, totalDt = 0, totalKt = 0, isCredit = ''; 
            var subid = $thisRow.find("input[name='gl_subid[]']").val();
            var rate = $thisRow.find("input[name='gl_rate[]']").autoNumeric('get');
            
            $('table#glDtl > tbody > tr', glBpMainWindow_<?php echo $this->uniqId; ?>).each(function() {
                var _thisRow = $(this);
                var this_subid = _thisRow.find("input[name='gl_subid[]']").val();
                if (this_subid == subid && typeof _thisRow.find("input[name='gl_debitAmount[]']").val() != 'undefined') {
                    totalDt = totalDt + Number(_thisRow.find("input[name='gl_debitAmount[]']").val());
                    totalKt = totalKt + Number(_thisRow.find("input[name='gl_creditAmount[]']").val());
                }
            }); 
            
            if (totalDt > totalKt) {
               isCredit = 1; 
            } else {
               isCredit = 0; 
            }
            
            if (isCredit == 1) {
                ktAmt = totalDt - totalKt;
                ktAmtFcy = (rate == '1') ? 0 : glRound(ktAmt / rate);
                if (typeof isDebit !== 'undefined' && isDebit === true) {
                    isCredit = 1;
                    ktAmt = 0;
                    ktAmtFcy = 0;
                }
            } else if (isCredit == 0) {
                dtAmt = totalKt - totalDt;
                dtAmtFcy = (rate == '1') ? 0 : glRound(dtAmt / rate);
                if (typeof isDebit !== 'undefined' && isDebit === false) {
                    isCredit = 1;
                    dtAmt = 0;
                    dtAmtFcy = 0;
                }
            }
            
            $thisRow.find("input[name='gl_debitAmount[]']").val(dtAmt);
            $thisRow.find("input[name='gl_creditAmount[]']").val(ktAmt);
            $thisRow.find("input[name='gl_debitAmountBase[]']").val(dtAmtFcy);
            $thisRow.find("input[name='gl_creditAmountBase[]']").val(ktAmtFcy);
            $thisRow.find("input[data-input-name='debitAmount']").autoNumeric('set', dtAmt);
            $thisRow.find("input[data-input-name='creditAmount']").autoNumeric('set', ktAmt);
            $thisRow.find("input[data-input-name='debitAmountBase']").autoNumeric('set', dtAmtFcy);
            $thisRow.find("input[data-input-name='creditAmountBase']").autoNumeric('set', ktAmtFcy);
        }
        calculateFooterSum_<?php echo $this->uniqId; ?>(tr);
    }
    function changeRowAccount_<?php echo $this->uniqId; ?>(bookDate, tr, data){
        var $thisRow = $(tr);
        var $rowMeta = $thisRow.find('input[name="gl_metas[]"]');
        
        if (isIgnoreUseDetail_<?php echo $this->uniqId; ?>) {
            data.ISUSEDETAILBOOK = 0;
        }
        
        /**
         * @description Dans songoh uyd GL template-s default utga a awahgui bsn uchraas 
         * daraah gl_metas der irj bsn utgiig hooson bolgood bsniig ajillahgui bolgoloo
         * @who Anar /Finance PD/ 2020-11-03 12:24
         * @author Ulaankhuu Ts
         */
        if ($thisRow.find("input[name='gl_accountCode[]']").val() !== '') {
            $rowMeta.val('');
        }        
            
        $thisRow.find("input[name='defaultInvoiceBook[]'], input[name='gl_processId[]'], input#srcInvoiceBook, input[name='gl_keyId[]'], input[name='gl_invoiceBookId[]']").val('');
        $thisRow.find("input[name='gl_accountId[]']").val(data.ID);
        $thisRow.find("input[name='gl_accountCode[]']").val(data.ACCOUNTCODE);
        $thisRow.find("input[name='gl_accountName[]']").val(data.ACCOUNTNAME).attr('title', data.ACCOUNTNAME);
        $thisRow.find("input[name='gl_main_accounttypeid[]']").val(data.ACCOUNTTYPEID);
        $thisRow.find("input[name='gl_accounttypeCode[]']").val(data.ACCOUNTTYPECODE);
        $thisRow.find("input[name='gl_objectId[]']").val(data.OBJECTID);
        $thisRow.find("input[name='gl_useDetailBook[]']").val(data.ISUSEDETAILBOOK);        
        
        if (data.hasOwnProperty('ACCOUNTFILTER') && data.ACCOUNTFILTER !== '') {
            $thisRow.find("input[name='gl_accountFilterConfig[]']").val(data.ACCOUNTFILTER);
        }                                

        if (data.hasOwnProperty('ISNULLDIMENSION') && data.ISNULLDIMENSION !== '') {
            $thisRow.find("input[name='gl_accountFilterConfigIsDimension[]']").val(data.ISNULLDIMENSION);
        }          
        
        if (data.hasOwnProperty('ECONOMICCLASSID') && data.ECONOMICCLASSID !== '' && data.ECONOMICCLASSID !== 'null' && data.ECONOMICCLASSID !== null) {
            var rowMetaObj = {};
            rowMetaObj['economicclassid'] = data.ECONOMICCLASSID;
            $rowMeta.val(JSON.stringify(rowMetaObj));
        }

        if (data.hasOwnProperty('DEPARTMENTID') && data.DEPARTMENTID !== '') {
            $thisRow.attr('data-account-departmentid', data.DEPARTMENTID);
        }
        
        if (data.hasOwnProperty('CUSTOMERID') && data.CUSTOMERID) {
            $thisRow.find("input[name='gl_customerId[]']").val(data.CUSTOMERID);
            $thisRow.find("input[name='gl_customerCode[]']").val(data.CUSTOMERCODE);
            $thisRow.find("input[name='gl_customerName[]']").val(data.CUSTOMERNAME);
        }
        
        if (data.OBJECTID == '20006' || data.OBJECTID == '20007' || data.OBJECTID == '30004' || data.ISUSEDETAILBOOK == '0') {
            $thisRow.find("input[name='gl_customerCode[]']").removeAttr('readonly');
            $thisRow.find("input[name='gl_customerCode[]']").parent().find('button').removeAttr('disabled');
            $thisRow.find("input[name='gl_customerName[]']").removeAttr('readonly');
        } else {
            $thisRow.find("input[name='gl_customerCode[]']").attr('readonly', 'readonly');
            $thisRow.find("input[name='gl_customerCode[]']").parent().find('button').attr('disabled', 'disabled');
            $thisRow.find("input[name='gl_customerName[]']").attr('readonly', 'readonly');
        }                
        
        <?php
        if (isset($this->oppRate)) {
        ?>
        var lowerCurrencyCode = (data.CURRENCYCODE).toLowerCase();
        if (lowerCurrencyCode == '<?php echo $this->oppCurrencyCode; ?>') {
            var rate = '<?php echo $this->oppRate; ?>';
        } else {
            var rate = getAccountRate_<?php echo $this->uniqId; ?>(bookDate, data.ID, data.CURRENCYCODE);       
        }    
        <?php
        } else {
        ?>
        var rate = getAccountRate_<?php echo $this->uniqId; ?>(bookDate, data.ID, data.CURRENCYCODE);        
        <?php
        }
        ?>
        
        $thisRow.find("input[name='gl_rate_currency[]']").val(data.CURRENCYCODE);
        $thisRow.find("input[name='gl_rate[]']").autoNumeric("set", rate);
        $thisRow.find("input[name='gl_isdebit[]']").val('');
        var isDebit = 1;
        var debit = Number($thisRow.find("input[name='gl_debitAmount[]']").val());
        var credit = Number($thisRow.find("input[name='gl_creditAmount[]']").val());
        if (credit > debit) {
            isDebit = 0;
        } else if (credit == 0 && debit == 0) {
            isDebit = '';
        }
        var debitbase = (rate == '1') ? 0 : glRound(debit / rate);
        var creditbase = (rate == '1') ? 0 : glRound(credit / rate);
        
        /**
         * @description Данс сонгоход isDebit эсэхээ таньж чадахгүй байсан тул дараах өөрчлөлт орууллаа.
         * @author Ulaankhuu Ts
         */
        var isGlAmountCopy = $glLoadWindow_<?php echo $this->uniqId; ?>.find('input.is-gl-amount-calc').is(':checked');
        
        if (isGlAmountCopy && $thisRow.find("input[name='gl_debitAmount[]']").val() == '0' && $thisRow.find("input[name='gl_creditAmount[]']").val() == '0') {
            
            var totalDt = 0, totalKt = 0; 
            var subid = $thisRow.find("input[name='gl_subid[]']").val();
            
            $('table#glDtl > tbody > tr', glBpMainWindow_<?php echo $this->uniqId; ?>).each(function() {
                var _thisRow = $(this);
                var this_subid = _thisRow.find("input[name='gl_subid[]']").val();
                if (this_subid == subid && typeof _thisRow.find("input[name='gl_debitAmount[]']").val() != 'undefined') {
                    totalDt = totalDt + Number(_thisRow.find("input[name='gl_debitAmount[]']").val());
                    totalKt = totalKt + Number(_thisRow.find("input[name='gl_creditAmount[]']").val());
                }
            }); 
            
            if (totalDt > totalKt) { 
               isDebit = 0;
            }
        }        
        
        $thisRow.find("input[name='gl_isdebit[]']").val(isDebit);
        $thisRow.find("input[name='gl_debitAmountBase[]']").val(debitbase);
        $thisRow.find("input[name='gl_creditAmountBase[]']").val(creditbase);
        $thisRow.find("input[data-input-name='debitAmountBase']").autoNumeric('set', debitbase);
        $thisRow.find("input[data-input-name='creditAmountBase']").autoNumeric('set', creditbase);
        
        if ($thisRow.find("select.gl-row-currency").length) {
            if ($thisRow.find("input[name='gl_rate_currency[]']").val().toLowerCase() == 'usd') {
                $thisRow.find('select.gl-row-currency').remove();
                $thisRow.find('.glRowCurrency').text(data.CURRENCYCODE);
            } 
        } else {
            $thisRow.find('.glRowCurrency').text(data.CURRENCYCODE);
        }

        checkIsUseBase_<?php echo $this->uniqId; ?>(tr); 
        checkIsUseDetail_<?php echo $this->uniqId; ?>(data.ISUSEDETAILBOOK, tr);
        $thisRow.closest('table').find('> tbody > tr.currentTRtarget').removeClass('currentTRtarget');
        $thisRow.addClass('currentTRtarget');                              
        glRowExpand_<?php echo $this->uniqId; ?>(tr, 'expandRemove', 'autocomplete');        
        checkAccountFilterConfig_<?php echo $this->uniqId; ?>(tr, 'all');
        
        return false;
    }
    function bankChargeORBillRateFromGL_<?php echo $this->uniqId; ?>(type) {
        var checkCounter = 0, subIdCompare = '';
        
        $('table#glDtl > tbody > tr', glBpMainWindow_<?php echo $this->uniqId; ?>).each(function() {
            var $this = $(this), glSubId = $this.find('input[name="gl_subid[]"]').val();
            
            if (subIdCompare === '' || subIdCompare === glSubId) {
                if (($this.find('input[name="gl_objectId[]"]').val() == '20006' || $this.find('input[name="gl_objectId[]"]').val() == '20007') && $this.find('input[name="gl_creditAmount[]"]').val() > 0)
                    checkCounter++;
                else if ($this.find('input[name="gl_objectId[]"]').val() == '20003' && $this.find('input[name="gl_debitAmount[]"]').val() > 0)
                    checkCounter++;
            } else 
                checkCounter = 0;
            
            subIdCompare = glSubId;
            
            if (checkCounter === 2) {
                var $selectedRowParams = {
                    id: $this.find('input[name="gl_dtlId[]"]').val(),
                    customerid: $this.find('input[name="gl_customerId[]"]').val(),
                    customercode: $this.find('input[name="gl_customerCode[]"]').val(),
                    customername: $this.find('input[name="gl_customerName[]"]').val(),
                    accountid: $this.find('input[name="gl_accountId[]"]').val(),
                    accountcode: $this.find('input[name="gl_accountCode[]"]').val(),
                    accountname: $this.find('input[name="gl_accountName[]"]').val(),
                    bookdate: $this.closest('.glTemplateSectionProcess').find('input[name="hidden_glbookDate"]').val(),
                    booknumber: $this.closest('.glTemplateSectionProcess').find('input[name="hidden_glbookNumber"]').val(),
                    rate: $this.find('input[name="gl_rate[]"]').val(),
                    currencycode: $this.find('input[name="gl_rate_currency[]"]').val(),
                    filterstartdate: '<?php echo issetVar($this->drillDownParams['filterstartdate']); ?>',
                    filterenddate: '<?php echo issetVar($this->drillDownParams['filterenddate']); ?>'
                };
                if (type === 'bankcharge')
                    urlRedirectByDataView('', '', 'mdgl/bankCharge', '', '<?php echo $this->dataViewId ?>', 'runSource=popup', '', $selectedRowParams);
                else
                    urlRedirectByDataView('', '', 'billratefromgllist', '', '<?php echo $this->dataViewId ?>', 'runSource=popup&dialogView=fullscreen&filterstartdate=filterstartdate&filterenddate=filterenddate&currencycode=currencycode&accountid=accountid&customerid=customerid&booknumber=booknumber', '', $selectedRowParams);
                
                return;
            }
        });
        
        if (checkCounter < 2) {
            PNotify.removeAll();
            new PNotify({
                title: 'Анхааруулга',
                text: 'Тохирох данс олдсонгүй',
                type: 'info',
                sticker: false
            });         
        }
    } 
    function deleteGlRecord_<?php echo $this->uniqId; ?>(type, thisDialog) {
        var $dialogName = 'dialogconfirm-glremove-fromstatement';
        if (!$($dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }

        $("#" + $dialogName).empty().append("Та устгахдаа итгэлтэй байна уу?");
        $("#" + $dialogName).dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: "Сануулга",
            width: 350,
            height: 'auto',
            modal: true,
            close: function(){
                $("#" + $dialogName).empty().dialog('destroy').remove();
            },                        
            buttons: [
                {text: 'Тийм', class: 'btn btn-sm blue', click: function() {
                    $.ajax({
                        type: 'post',
                        url: 'mdgl/runDeleteGlBp',
                        data: {
                            id: '<?php echo issetVar($this->paramList['id']); ?>',
                            type: type
                        },
                        dataType: 'json',
                        success: function (data) {
                            PNotify.removeAll();
                            new PNotify({
                                title: data.status,
                                text: data.message,
                                type: data.status,
                                sticker: false
                            });

                            if (data.status === 'success') {
                                $('#' + thisDialog).dialog('close');
                                $('#dataview-statement-search-<?php echo issetVar($this->drillDownParams['statementId']); ?> form').find('button.dataview-statement-filter-btn').trigger('click');
                            }
                        }
                    });

                    $("#" + $dialogName).dialog('close');
                }},
                {text: 'Үгүй', class: 'btn btn-sm blue-hoki', click: function() {
                    $("#" + $dialogName).dialog('close');
                }}
            ]
        });
        $("#" + $dialogName).dialog('open');    
    } 
    function glTableFreeze_<?php echo $this->uniqId; ?>() {
        var $freezeParent = $('#fz-parent', glBpMainWindow_<?php echo $this->uniqId; ?>);
        $freezeParent.css('max-height', '500px');
        <?php
        if (isset($this->paramList['generalledgerbookdtls']) && count($this->paramList['generalledgerbookdtls']) > 50) {
        ?>
        $('table#glDtl', glBpMainWindow_<?php echo $this->uniqId; ?>).tableHeadFixer({'head': true, 'foot': true, 'left': 0, 'z-index': 9});
        <?php
        } else {
        ?>
        $('table#glDtl', glBpMainWindow_<?php echo $this->uniqId; ?>).tableHeadFixer({'head': true, 'foot': true, 'left': 3, 'z-index': 9});        
        <?php
        }
        ?>        
    }
    function bpSetGlMetaRowIndex(window) {
        var el = $('table#glDtl > tbody > tr', window);
        var len = el.length, i = 0;
        for (i; i < len; i++) { 
            var subElement = $(el[i]).find("input[name*='accountMeta['], select[name*='accountMeta[']");
            var slen = subElement.length, j = 0;
            
            for (j; j < slen; j++) { 
                var _inputThis = $(subElement[j]);
                var _inputName = _inputThis.attr('name');
                if (typeof _inputName !== 'undefined') {
                    _inputThis.attr('name', _inputName.replace(/^accountMeta(\[[0-9]+\])(.*)$/, 'accountMeta[' + i + ']$2'));
                }
            }
        }
        return;
    }
    function bpSetGlMetaOneRowIndex(index, row) {
        var $subElement = row.find("input[name*='accountMeta['], select[name*='accountMeta[']");
        var slen = $subElement.length, j = 0;

        for (j; j < slen; j++) { 
            var $inputThis = $($subElement[j]);
            var $inputName = $inputThis.attr('name');
            if (typeof $inputName !== 'undefined') {
                $inputThis.attr('name', $inputName.replace(/^accountMeta(\[[0-9]+\])(.*)$/, 'accountMeta[' + index + ']$2'));
            }
        }
        return;
    }
    function glAddEmptyRows_<?php echo $this->uniqId; ?>(elem, rowNum) {
        var $this = $(elem), $parent = $this.closest('.input-group'), 
            $input = $parent.find('input'), $rowNumber = Number($input.val());
            if (typeof rowNum !== 'undefined') {
                $rowNumber = rowNum;
            }
    
        if ($rowNumber > 0) {
            var $panel = $this.closest('.gl-parent-row'), $tableBody = $panel.find('#glDtl > tbody').first(), 
                $lastRow = $tableBody.find('> tr:last'), subId = 1, customerId = '', customerCode = '', customerName = '';
            
            if ($lastRow.length) {
                if (typeof rowNum !== 'undefined') {
                    subId = Number($lastRow.find('input[name="gl_subid[]"]').val());
                } else {
                    subId = Number($lastRow.find('input[name="gl_subid[]"]').val()) + 1;
                }
                
                var isGlCustomerCopy = $glLoadWindow_<?php echo $this->uniqId; ?>.find('input.is-gl-customer-copy').is(':checked');
                
                if (isGlCustomerCopy) {
                    var savedCustomerId = $lastRow.find('input[name="gl_customerId[]"]').val();

                    if (savedCustomerId != '') {
                        customerId = savedCustomerId; 
                        customerCode = $lastRow.find('input[name="gl_customerCode[]"]').val();
                        customerName = htmlentities($lastRow.find('input[name="gl_customerName[]"]').val()); 
                    }
                }
            }

            var mainWindow = $this.closest('form'), 
                bookDate = $("input[name='glbookDate']", glEntryWindowId_<?php echo $this->uniqId; ?>).val(), 
                bookDescription = $("#gldescription", glEntryWindowId_<?php echo $this->uniqId; ?>).val();

            if (typeof bookDate === 'undefined') {
                bookDate = $("input[name='hidden_glbookDate']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
                bookDescription = $("#gldescription", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
            }

            var accountCodeField = '<div class="input-group"><input type="hidden" name="gl_accountId[]"><input type="text" name="gl_accountCode[]" id="gl_accountCode" class="form-control form-control-sm text-center accountCodeMask"><span class="input-group-btn"><button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewCustomSelectableGrid(\'<?php echo Mdgl::$accountListDataViewCode; ?>\', \'single\', \'accountSelectabledGrid_<?php echo $this->uniqId; ?>\', \'\', this);"><i class="fa fa-search"></i></button></span></div>';
            var customerField = '<div class="input-group double-between-input">\n\
                                    <input type="hidden" name="gl_customerId[]" value="'+customerId+'">\n\
                                    <input type="text" id="gl_customerCode" name="gl_customerCode[]" value="'+customerCode+'" class="form-control form-control-sm text-center" title="" placeholder="<?php echo $this->lang->line('code_search'); ?>" style="width:80px;max-width:80px;">\n\
                                    <span class="input-group-btn">\n\
                                        <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick=\"dataViewCustomSelectableGrid(\'<?php echo Mdgl::$customerListDataViewCode; ?>\', \'single\', \'customerSelectabledGrid\', \'\', this);\"><i class="fa fa-search"></i></button>\n\
                                    </span>\n\
                                    <span class="input-group-btn">\n\
                                        <input type="text" id="gl_customerName" name="gl_customerName[]" value="'+customerName+'" class="form-control form-control-sm text-center" placeholder="<?php echo $this->lang->line('name_search'); ?>">\n\
                                    </span>\n\
                                </div>';

            var rate = 1, currencyDropDown = '', baseReadonly = '', rateReadonly = '', expenseCenterField = '';

            <?php
            if (isset($this->isGlRateDisabled)) {
                echo "rateReadonly = \"readonly='readonly'\";";
            }
            ?>
            
            var rowAttr = {
                subId: subId, 
                lastIndex: 1, 
                accounttypeid: '', 
                objectid: '', 
                accounttypecode: '', 
                isusedetailbook: '', 
                currencycode: '', 
                accountCodeField: accountCodeField, 
                accountname: '', 
                customerField: customerField, 
                expenseCenterField: expenseCenterField, 
                bookDescription: bookDescription, 
                currencyDropDown: currencyDropDown, 
                rate: rate, 
                rateReadonly: rateReadonly, 
                baseReadonly: baseReadonly, 
                dtAmtFcy: '', 
                dtAmt: '', 
                ktAmtFcy: '', 
                ktAmt: '', 
                keyId: '', 
                rowType: 'add', 
                metas: '', 
                actions: '', 
                isdebit: '', 
                ismetas: ''
            };

            mainWindow.find('table#glDtl > tbody > tr.gl-selected-row').removeClass('gl-selected-row');

            var newRowHtml = glRowAppend_<?php echo $this->uniqId; ?>(rowAttr);
            
            $tableBody.append(newRowHtml.repeat($rowNumber));
                
            var $addedRows = mainWindow.find('table#glDtl > tbody > tr.gl-new-row');

            Core.initNumberInput($addedRows);
            Core.initAccountCodeMask($addedRows);

            mainWindow.find('table#glDtl > tbody > tr[data-sub-id]').each(function(i){
                $(this).attr('data-row-index', i);
            });

            checkRowDescriptionField_<?php echo $this->uniqId; ?>($this);
            checkIsUseBase_<?php echo $this->uniqId; ?>($this); 

            glTableFreeze_<?php echo $this->uniqId; ?>();
            bpSetGlMetaRowIndex(glBpMainWindow_<?php echo $this->uniqId; ?>);
            
            $addedRows.first().find("input[name='gl_accountCode[]']:not([readonly],[disabled])").focus();
            $addedRows.removeClass('gl-new-row');
            
        } else {
            PNotify.removeAll();
            new PNotify({
                title: 'Warning',
                text: 'Та мөрийн тоог оруулна уу.',
                type: 'warning',
                sticker: false
            });
        }  
        return;
    }
    function apApSelectabledGridForMain_<?php echo $this->uniqId; ?>(metaDataCode, chooseType, elem, rows) {
        var $this = $(elem), apar = 0;
    
        if (rows) {
            
            var isGlCustomerCopy = $glLoadWindow_<?php echo $this->uniqId; ?>.find('input.is-gl-customer-copy').is(':checked');
            
            for (apar; apar < rows.length; apar++) {
                
                var $panel = $this.closest('.gl-parent-row'), $tableBody = $panel.find('#glDtl > tbody').first(), 
                    $lastRow = $tableBody.find('> tr:last'), subId = 1, customerId = '', customerCode = '', customerName = '';

                if ($lastRow.length) {
                    subId = Number($lastRow.find('input[name="gl_subid[]"]').val());
                }
                
                if (isGlCustomerCopy) {
                    customerId = rows[apar].customerid; 
                    customerCode = rows[apar].customercode;
                    customerName = rows[apar].customername; 
                }
            
                var mainWindow = $this.closest('form'), 
                    bookDate = $("input[name='glbookDate']", glEntryWindowId_<?php echo $this->uniqId; ?>).val(), 
                    bookDescription = $("#gldescription", glEntryWindowId_<?php echo $this->uniqId; ?>).val();

                if (typeof bookDate === 'undefined') {
                    bookDate = $("input[name='hidden_glbookDate']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
                    bookDescription = $("#gldescription", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
                }

                var accountCodeField = '<div class="input-group"><input type="hidden" name="gl_accountId[]" value="'+rows[apar].accountid+'"><input type="text" name="gl_accountCode[]" id="gl_accountCode" value="'+rows[apar].accountcode+'" class="form-control form-control-sm text-center accountCodeMask"><span class="input-group-btn"><button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewCustomSelectableGrid(\'<?php echo Mdgl::$accountListDataViewCode; ?>\', \'single\', \'accountSelectabledGrid_<?php echo $this->uniqId; ?>\', \'\', this);"><i class="fa fa-search"></i></button></span></div>';
                var customerField = '<div class="input-group double-between-input">\n\
                                        <input type="hidden" name="gl_customerId[]" value="'+customerId+'">\n\
                                        <input type="text" id="gl_customerCode" name="gl_customerCode[]" value="'+customerCode+'" class="form-control form-control-sm text-center" placeholder="<?php echo $this->lang->line('code_search'); ?>" style="width:80px;max-width:80px;">\n\
                                        <span class="input-group-btn">\n\
                                            <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick=\"dataViewCustomSelectableGrid(\'<?php echo Mdgl::$customerListDataViewCode; ?>\', \'single\', \'customerSelectabledGrid\', \'\', this);\"><i class="fa fa-search"></i></button>\n\
                                        </span>\n\
                                        <span class="input-group-btn">\n\
                                            <input type="text" id="gl_customerName" name="gl_customerName[]" value="'+customerName+'" class="form-control form-control-sm text-center" placeholder="<?php echo $this->lang->line('name_search'); ?>">\n\
                                        </span>\n\
                                    </div>';

                var rate = 1, currencyDropDown = '', baseReadonly = '', rateReadonly = '', expenseCenterField = '';

                <?php
                if (isset($this->isGlRateDisabled)) {
                    echo "rateReadonly = \"readonly='readonly'\";";
                }
                ?>

                var debitBase = rows[apar].debitamountbase;
                var debit = rows[apar].debitamount;
                var creditBase = rows[apar].creditamountbase;
                var credit = rows[apar].creditamount;

                if (debit < 0) {
                    creditBase = debitBase * (-1);
                    credit = debit * (-1);
                    debitBase = 0;
                    debit = 0;                      
                } else if (credit < 0) {
                    debitBase = creditBase * (-1);
                    debit = credit * (-1);      
                    creditBase = 0;
                    credit = 0;                                  
                }

                var rowAttr = {
                    subId: subId, 
                    lastIndex: 1, 
                    accounttypeid: '', 
                    objectid: '', 
                    accounttypecode: '', 
                    isusedetailbook: '', 
                    currencycode: rows[apar].currencycode, 
                    accountCodeField: accountCodeField, 
                    accountname: rows[apar].accountname, 
                    customerField: customerField, 
                    expenseCenterField: expenseCenterField, 
                    bookDescription: bookDescription, 
                    currencyDropDown: currencyDropDown, 
                    rate: rows[apar].rate, 
                    rateReadonly: rateReadonly, 
                    baseReadonly: baseReadonly, 
                    dtAmtFcy: debitBase, 
                    dtAmt: debit, 
                    ktAmtFcy: creditBase,
                    ktAmt: credit, 
                    keyId: rows[apar].keyid, 
                    rowType: 'add', 
                    metas: '', 
                    actions: '', 
                    isdebit: '', 
                    ismetas: ''
                };

                mainWindow.find('table#glDtl > tbody > tr.gl-selected-row').removeClass('gl-selected-row');

                var newRowHtml = glRowAppend_<?php echo $this->uniqId; ?>(rowAttr);

                $tableBody.append(newRowHtml);
            }
                
            var $addedRows = mainWindow.find('table#glDtl > tbody > tr.gl-new-row');

            Core.initNumberInput($addedRows);
            Core.initAccountCodeMask($addedRows);

            mainWindow.find('table#glDtl > tbody > tr[data-sub-id]').each(function(i){
                $(this).attr('data-row-index', i);
            });

            checkRowDescriptionField_<?php echo $this->uniqId; ?>($this);
            checkIsUseBase_<?php echo $this->uniqId; ?>($this); 

            glTableFreeze_<?php echo $this->uniqId; ?>();
            bpSetGlMetaRowIndex(glBpMainWindow_<?php echo $this->uniqId; ?>);
            
            $addedRows.first().find("input[name='gl_accountCode[]']:not([readonly],[disabled])").focus();
            $addedRows.removeClass('gl-new-row');
            
        } else {
            PNotify.removeAll();
            new PNotify({
                title: 'Warning',
                text: 'Мөр сонгоно уу.',
                type: 'warning',
                sticker: false
            });
        }  
        return;    
    }    
    
    function glRound(num) {
        return Number(Math.round(num+'e'+glAmountScale)+'e-'+glAmountScale);
    }
    function glTwoRound(num) {
        return Number(Math.round(num+'e2')+'e-2');
    }
    
    function glAddRows_<?php echo $this->uniqId; ?>(elem) {
        
        PNotify.removeAll();
        
        var $this = $(elem);
        
        $.ajax({
            type: 'post',
            url: 'mdgl/glAddRows', 
            data: {uniqId: '<?php echo $this->uniqId; ?>', rowCount: $this.closest('.input-group').find('input[type="text"]').val()}, 
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function(data) {

                if (data.status == 'success') {

                    var $dialogName = 'dialog-gl-addrows'; 
                    $('<div id="' + $dialogName + '"></div>').appendTo('body'); 
                    var $dialog = $('#' + $dialogName);

                    $dialog.empty().append(data.html);
                    $dialog.dialog({
                        cache: false,
                        resizable: false,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.title,
                        width: 600,
                        minWidth: 600, 
                        height: 'auto',
                        modal: true,
                        closeOnEscape: isCloseOnEscape, 
                        close: function() {
                            $dialog.empty().dialog('destroy').remove();
                        }, 
                        buttons: [
                            {text: data.insert_btn, class: 'btn btn-sm btn-primary', click: function() {
                                
                                var $form = $('#gl-multi-add-rows-form');
                                $form.validate({errorPlacement: function () {}});
                                
                                if ($form.valid()) {
                                    
                                    var $panel = $this.closest('.gl-parent-row'), $tableBody = $panel.find('#glDtl > tbody').first(), 
                                        $lastRow = $tableBody.find('> tr:last'), subId = $('#gmSubId').val(), customerId = '', customerCode = '', customerName = '', 
                                        $rowNumber = $('#gmRowCount').val();
                                
                                    var row = JSON.parse($('input[name="gmAccountId"]').attr('data-row-data'));    

                                    if ($lastRow.length) {

                                        var isGlCustomerCopy = $glLoadWindow_<?php echo $this->uniqId; ?>.find('input.is-gl-customer-copy').is(':checked');

                                        if (isGlCustomerCopy) {
                                            var savedCustomerId = $lastRow.find('input[name="gl_customerId[]"]').val();

                                            if (savedCustomerId != '') {
                                                customerId = savedCustomerId; 
                                                customerCode = $lastRow.find('input[name="gl_customerCode[]"]').val();
                                                customerName = htmlentities($lastRow.find('input[name="gl_customerName[]"]').val()); 
                                            }
                                        }
                                    }

                                    var mainWindow = $this.closest('form'), 
                                        bookDate = $("input[name='glbookDate']", glEntryWindowId_<?php echo $this->uniqId; ?>).val(), 
                                        bookDescription = $("#gldescription", glEntryWindowId_<?php echo $this->uniqId; ?>).val();

                                    if (typeof bookDate === 'undefined') {
                                        bookDate = $("input[name='hidden_glbookDate']", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
                                        bookDescription = $("#gldescription", glBpMainWindow_<?php echo $this->uniqId; ?>).val();
                                    }

                                    var accountCodeField = '<div class="input-group"><input type="hidden" name="gl_accountId[]" value="'+row.id+'"><input type="text" name="gl_accountCode[]" id="gl_accountCode" class="form-control form-control-sm text-center accountCodeMask" value="'+row.accountcode+'"><span class="input-group-btn"><button type="button" class="btn default btn-bordered mr0" onclick="dataViewCustomSelectableGrid(\'<?php echo Mdgl::$accountListDataViewCode; ?>\', \'single\', \'accountSelectabledGrid_<?php echo $this->uniqId; ?>\', \'\', this);"><i class="fa fa-search"></i></button></span></div>';
                                    var customerField = '<div class="input-group double-between-input">\n\
                                                            <input type="hidden" name="gl_customerId[]" value="'+customerId+'">\n\
                                                            <input type="text" id="gl_customerCode" name="gl_customerCode[]" value="'+customerCode+'" class="form-control form-control-sm text-center" title="" placeholder="<?php echo $this->lang->line('code_search'); ?>" style="width:80px;max-width:80px;">\n\
                                                            <span class="input-group-btn">\n\
                                                                <button type="button" class="btn default btn-bordered mr0" onclick=\"dataViewCustomSelectableGrid(\'<?php echo Mdgl::$customerListDataViewCode; ?>\', \'single\', \'customerSelectabledGrid\', \'\', this);\"><i class="fa fa-search"></i></button>\n\
                                                            </span>\n\
                                                            <span class="input-group-btn">\n\
                                                                <input type="text" id="gl_customerName" name="gl_customerName[]" value="'+customerName+'" class="form-control form-control-sm text-center" placeholder="<?php echo $this->lang->line('name_search'); ?>">\n\
                                                            </span>\n\
                                                        </div>';

                                    var rate = 1, currencyDropDown = row.currencycode, baseReadonly = '', rateReadonly = '', expenseCenterField = '', actions = '';

                                    <?php
                                    if (isset($this->isGlRateDisabled)) {
                                        echo "rateReadonly = \"readonly='readonly'\";";
                                    }
                                    ?>

                                    var rowAttr = {
                                        subId: subId, 
                                        lastIndex: 1, 
                                        accounttypeid: row.accounttypeid, 
                                        objectid: row.objectid, 
                                        accounttypecode: row.accounttypecode, 
                                        isusedetailbook: row.isusedetailbook, 
                                        currencycode: row.currencycode, 
                                        accountCodeField: accountCodeField, 
                                        accountname: row.accountname, 
                                        customerField: customerField, 
                                        expenseCenterField: expenseCenterField, 
                                        bookDescription: bookDescription, 
                                        currencyDropDown: currencyDropDown, 
                                        rate: rate, 
                                        rateReadonly: rateReadonly, 
                                        baseReadonly: baseReadonly, 
                                        departmentid: row.departmentid,
                                        dtAmtFcy: '', 
                                        dtAmt: '', 
                                        ktAmtFcy: '', 
                                        ktAmt: '', 
                                        keyId: '', 
                                        rowType: 'add', 
                                        metas: '', 
                                        actions: '', 
                                        isdebit: gmIsDebit, 
                                        ismetas: ''
                                    };
                                    
                                    if (gmIsDebit == '1') {
                                        rowAttr.dtAmt = $('#gmAmount').autoNumeric('get');
                                    } else {
                                        rowAttr.ktAmt = $('#gmAmount').autoNumeric('get');
                                    }
                                    
                                    var $subElement = $dialog.find("input[name*='accountMeta['], select[name*='accountMeta[']");
                                    var slen = $subElement.length, j = 0, rowMetaObj = {};
                                    
                                    if (slen) {
                                        var isMetaValue = false;
                                        for (j; j < slen; j++) { 
                                            var $inputThis = $($subElement[j]);
                                            var $inputName = $inputThis.attr('data-path');
                                            if (typeof $inputName !== 'undefined' && $inputThis.val() != '') {
                                                $inputName = ($inputName).toLowerCase();
                                                rowMetaObj[$inputName] = $inputThis.val();
                                                isMetaValue = true;
                                            }
                                        }
                                        
                                        if (isMetaValue) {
                                            rowAttr.metas = JSON.stringify(rowMetaObj);
                                            rowAttr.ismetas = 1;
                                            actions += "<div class='btn btn-xs purple-plum gl-dtl-meta-btn' title='Үзүүлэлт' onclick='showDtlMeta_<?php echo $this->uniqId; ?>(this);'>...</div>";
                                        }
                                    }
                                    
                                    if (rowAttr.isusedetailbook == '1') {
                                        actions += "<div class='btn btn-xs blue' id='detailedMeta' title='Дэлгэрэнгүй' onclick='expandGlDtl_<?php echo $this->uniqId; ?>(this);'>...</div>";
                                    }
                                    
                                    rowAttr.actions = actions;

                                    mainWindow.find('table#glDtl > tbody > tr.gl-selected-row').removeClass('gl-selected-row');

                                    var newRowHtml = glRowAppend_<?php echo $this->uniqId; ?>(rowAttr);

                                    $tableBody.append(newRowHtml.repeat($rowNumber));

                                    var $addedRows = mainWindow.find('table#glDtl > tbody > tr.gl-new-row');

                                    Core.initNumberInput($addedRows);
                                    Core.initAccountCodeMask($addedRows);

                                    mainWindow.find('table#glDtl > tbody > tr[data-sub-id]').each(function(i){
                                        $(this).attr('data-row-index', i);
                                    });

                                    checkRowDescriptionField_<?php echo $this->uniqId; ?>($this);
                                    checkIsUseBase_<?php echo $this->uniqId; ?>($this); 

                                    glTableFreeze_<?php echo $this->uniqId; ?>();
                                    bpSetGlMetaRowIndex(glBpMainWindow_<?php echo $this->uniqId; ?>);

                                    $addedRows.first().find("input[name='gl_accountCode[]']:not([readonly],[disabled])").focus();
                                    $addedRows.removeClass('gl-new-row');
                                    
                                    $dialog.dialog('close');
                                }
                            }}, 
                            {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                                $dialog.dialog('close');
                            }}
                        ]
                    }); 

                    Core.initLongInput($dialog);
                    Core.initNumberInput($dialog);
                    Core.initFieldSetCollapse($dialog);

                    $dialog.dialog('open');
                    
                } else {
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                }

                Core.unblockUI();
            }
        });
    }
    
    function objToChangeLowerKeys(obj) {
        var key, keys = Object.keys(obj);
        var n = keys.length;
        var newobj = {};
        while (n--) {
            key = keys[n];
            newobj[key.toLowerCase()] = obj[key];
        }
        return newobj;
    }
</script>

<style type="text/css">
.table.table-bordered thead > tr > th {
    border-bottom: solid 1px #ddd !important;
}
.ui-dialog-title {
    text-align: left !important;
}
.gl-btn-group-dialog .float-right > .dropdown-menu {
    right: auto;
}    
table#glDtl {
    border: 1px solid #ddd;
    border-collapse: separate;
    border-spacing: 0;
}
table#glDtl td, table#glDtl th {
    border: 1px solid #ddd;
}
table#glDtl tr td {
    border-right: 0;
}
table#glDtl tr td {
    border-top: 0;
} 
table#glDtl tr:last-child td {
    border-bottom: 0;
}
table#glDtl tr td:first-child, table#glDtl tr th:first-child {
    border-left: 0;
}   
table#glDtl tr th {
    border-right: 0;
}   
</style>    