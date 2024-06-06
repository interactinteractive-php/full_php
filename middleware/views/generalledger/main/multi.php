<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div id="<?php echo $this->uniqId; ?>">
    <div class="col-md-12 xs-form">
        <?php 
        echo Form::create(array('class' => 'form-horizontal', 'id' => 'multiGL-form', 'method' => 'post')); 
        
        if (isset(Mdgl::$getDefaultValues) && Mdgl::$getDefaultValues) {
            echo Form::textArea(array('name'=>'hidden_getDefaultValues','class'=>'d-none','value'=>json_encode(Mdgl::$getDefaultValues))); 
        }
        ?>

        <table class="table table-sm table-bordered table-hover gl-table-dtl bprocess-theme1 mb0" id="glDtl">
            <thead>
                <th style='width:20px;' class='text-center rowNumber'>Багц</th>
                <th style='width:170px;min-width:170px;' class='text-center'>Дансны код</th>
                <th style='width:100%;' class='text-center'>Дансны нэр</th>
                <th style='width:220px;min-width:220px;' class='text-center customPartner'>Харилцагч</th>
                <th class='text-center glRowExpenseCenter' style='min-width:200px;'><?php echo Lang::lineDefault('PL_1015', 'Хариуцлагын төв'); ?></th>                
                <th style='width:110px;min-width:110px;' class='text-center'>Дебит</th>
                <th style='width:110px;min-width:110px;' class='text-center'>Кредит</th>
                <th style='width:40px;min-width:40px;max-width:40px;'></th>
            </thead>
            <tbody>
            <?php
            $params = $this->paramList;
            $sumDebitAmount = $sumCreditAmount = 0;

            if (!empty($params) && isset($params['generalledgerbookdtls']) && count($params['generalledgerbookdtls']) > 0) {

                $j = 0;

                foreach ($params['generalledgerbookdtls'] as $glRow) {

                    if (!isset($glRow['isusedetail'])) {
                        $glRow['isusedetail'] = '0';
                    }

                    $attrArray = $accountAttrArray = $btnAttrDisabled = array();

                    $rowTrash = '';

                    $islock = 0;

                    if (isset($glRow['islock']) && ($glRow['islock'] == 'true' || $glRow['islock'] == '1')) {

                        $attrArray = array(
                            'readonly' => 'readonly'
                        );
                        $accountAttrArray = array(
                            'readonly' => 'readonly'
                        );
                        $btnAttrDisabled = array(
                            'disabled' => 'disabled'
                        );
                        $rowTrash = '';
                        $islock = 1;

                    } elseif ($glRow['isusedetail'] == '1' || $glRow['isusedetail'] == 'true') {
                        
                        $btnAttrDisabled = array(
                            'disabled' => 'disabled'
                        );            
                        $attrArray = array(
                            'readonly' => 'readonly'
                        );
                    }        

                    $isUseDetail = (isset($glRow['isusedetail']) ? (($glRow['isusedetail'] == 'true' || $glRow['isusedetail'] == '1') ? 1 : 0) : '');

                    if (isset($glRow['objectid']) == false) {
                        $glRow['objectid'] = null;
                    }

                    $dtlId = '';
                    if (isset($glRow['id'])) {
                        $dtlId = $glRow['id'];
                    }
                    if ($glRow['debitamount'] > $glRow['creditamount']) {
                        $glRow['isdebit'] = 1;
                    } else {
                        $glRow['isdebit'] = 0;
                    }

                    $lockamount = 0;
                    if (isset($glRow['islockamount']) && ($glRow['islockamount'] == 'true' || $glRow['islockamount'] == '1') && $islock != 1) {
                        $attrArray = array('readonly' => 'readonly');
                        $lockamount = 1;
                        unset($btnAttrDisabled['disabled']);  
                        unset($accountAttrArray['readonly']); 
                    }

                    if (($glRow['objectid'] == '20006' || $glRow['objectid'] == '20007') && $islock != 1) {
                        unset($attrArray['readonly']);        
                        unset($btnAttrDisabled['disabled']);        
                        unset($accountAttrArray['readonly']);        
                    }
                    
                    $customerCode = issetParam($glRow['customercode']);
                    $customerName = issetParam($glRow['customername']);
                    $expenseCenterField = '';

                    $customerField = "<div class='input-group double-between-input'>" .
                            Form::hidden(array('name' => 'gl_customerId[]', 'value'=>$glRow['customerid'])) .
                            Form::text(array('name' => 'gl_customerCode[]', 'id' => 'gl_customerCode', 'class' => 'form-control form-control-sm text-center', 'value'=>$customerCode, 'title'=>$customerCode, 'placeholder'=>$this->lang->line('code_search'), 'style'=>'width:80px;')) .
                            "<span class='input-group-btn'>" .
                            Form::button(array('class' => 'btn default btn-bordered form-control-sm mr0', 'onclick'=>'dataViewCustomSelectableGrid(\''.Mdgl::$customerListDataViewCode.'\', \'single\', \'customerSelectabledGrid\', \'\', this);', 'value' => '<i class="fa fa-search"></i>')) .
                            "</span>" .
                            "<span class='input-group-btn'>" . 
                            Form::text(array('name' => 'gl_customerName[]', 'id' => 'gl_customerName', 'class' => 'form-control form-control-sm text-center', 'value'=>$customerName, 'title'=>$customerName, 'placeholder'=>$this->lang->line('name_search'))) .
                            "</span>" .
                        "</div>";

                    if (Config::getFromCache('CONFIG_GL_ROW_EXPENSE_CENTER')) {
                        $expenseCenterField = "<div class='input-group double-between-input'>" .
                                Form::hidden(array('name' => 'gl_expenseCenterId[]', 'value'=>issetParam($glRow['expensecenterid']))) .
                                Form::text(array('name' => 'gl_expenseCenterCode[]', 'id' => 'gl_expenseCenterCode', 'class' => 'form-control form-control-sm text-center', 'value'=>'', 'title'=>'', 'placeholder'=>$this->lang->line('code_search'), 'style'=>'width:80px;')) .
                                "<span class='input-group-btn'>" .
                                Form::button(array('class' => 'btn default btn-bordered form-control-sm mr0', 'onclick'=>'dataViewCustomSelectableGrid(\'ORG_DEPARTMENT_EXPENSE_CENTER\', \'single\', \'customerSelectabledExpenseGrid\', \'\', this);', 'value' => '<i class="fa fa-search"></i>')) .
                                "</span>" .
                                "<span class='input-group-btn'>" . 
                                Form::text(array('name' => 'gl_expenseCenterName[]', 'id' => 'gl_expenseCenterName', 'class' => 'form-control form-control-sm text-center', 'value'=>'', 'title'=>'', 'placeholder'=>$this->lang->line('name_search'))) .
                                "</span>" .
                            "</div>";
                    }
        
                    $accountName = issetParam($glRow['accountname']);
                    
                    $accountCodeField = "<div class='input-group'>" .
                        Form::text(array_merge(array('name' => 'gl_accountCode[]', 'id' => 'gl_accountCode', 'class' => 'form-control form-control-sm accountCodeMask text-center', 'value' => (isset($glRow['accountcode']) ? $glRow['accountcode'] : '')), $accountAttrArray)) .
                        "<span class='input-group-btn'>" .
                        Form::hidden(array('name' => 'gl_accountId[]', 'value' => $glRow['accountid'])) .
                        Form::button(array_merge(array('class' => 'btn default btn-bordered form-control-sm mr0', 'value' => '<i class="fa fa-search"></i>', 'onclick' => "dataViewCustomSelectableGrid('fin_account_list', 'single', 'accountSelectabledGrid_".$this->uniqId."', '', this, '" . (isset($glRow['accountfilter']) ? $glRow['accountfilter'] : '') . "');"), $btnAttrDisabled)) .
                        "</span>" .
                    "</div>";
                    $accountNameField = Form::text(array_merge(array('name' => 'gl_accountName[]', 'id' => 'gl_accountName', 'class' => 'form-control form-control-sm readonly-white-bg', 'readonly' => 'readonly', 'value' => $accountName, 'title' => $accountName), $accountAttrArray));        
                    
                    if ($isOppMetaAttr = (new Mdgl())->getOppMetaByAccountId($glRow)) {
                        $oppMetaAttr = ' data-op-meta=\''.$isOppMetaAttr.'\'';
                    } else {
                        $oppMetaAttr = '';
                    }
            
                    $row = '';
                    $row .= "<tr data-row-index='" . $j . "'$oppMetaAttr>";
                    $row .= "<td class='stretchInput middle text-center'>" . Form::text(array_merge(array('name' => 'gl_subid[]', 'id' => 'gl_subid', 'class' => 'form-control readonly-white-bg', 'value' => $glRow['subid'], 'style' => "text-align:center;", 'readonly' => 'readonly'), $attrArray));
                    $row .= Form::hidden(array('name' => 'gl_accounttypeId[]', 'value' => (isset($glRow['accounttypeid']) ? $glRow['accounttypeid'] : '')));
                    $row .= Form::hidden(array('name' => 'gl_main_accounttypeid[]', 'value' => (isset($glRow['accounttypeid']) ? $glRow['accounttypeid'] : '')));
                    $row .= Form::hidden(array('name' => 'gl_objectId[]', 'value' => $glRow['objectid']));
                    $row .= Form::hidden(array('name' => 'gl_isdebit[]', 'value' => (isset($glRow['isdebit']) ? $glRow['isdebit'] : '')));
                    $row .= Form::hidden(array('name' => 'gl_accounttypeCode[]', 'value' => (isset($glRow['accounttypecode']) ? $glRow['accounttypecode'] : '')));
                    $row .= Form::hidden(array('name' => 'gl_useDetailBook[]', 'value' => $isUseDetail));
                    $row .= Form::hidden(array('name' => 'gl_accountFilter[]', 'value' => (isset($glRow['accountfilter']) ? $glRow['accountfilter'] : '')));
                    $row .= Form::hidden(array('name' => 'gl_cashflowsubcategoryid[]', 'value' => ''));
                    $row .= Form::hidden(array('name' => 'gl_isEdited[]', 'value' => '0'));
                    $row .= Form::hidden(array('name' => 'gl_amountLock[]', 'value' => $lockamount));
                    $row .= Form::hidden(array('name' => 'gl_rowislock[]', 'value' => $islock));
                    $row .= Form::hidden(array('name' => 'gl_processId[]', 'value' => (isset($glRow['processid']) ? $glRow['processid'] : '')));
                    $row .= Form::hidden(array('name' => 'gl_ismetas[]', 'value' => ''));
                    
                    $detailValues = array_diff_key($glRow, Mdgl::$glRowStaticKeys);
                    
                    $row .= Form::hidden(array('name' => 'gl_metas[]', 'value' => htmlentities(json_encode($detailValues), ENT_QUOTES, 'UTF-8')));
                    $row .= Form::hidden(array('name' => 'gl_isGetLoad[]', 'value' => '1'));

                    $row .= "</td>";
                    $row .= "<td class='stretchInput middle text-center'>" . $accountCodeField . "</td>";
                    $row .= "<td class='stretchInput middle text-center'>" . $accountNameField . "</td>";
                    $row .= "<td class='stretchInput middle text-center customPartner'>" . $customerField . "</td>";
                    $row .= "<td class='stretchInput middle text-center glRowExpenseCenter'>" . $expenseCenterField . "</td>";
                    if ($glRow['isdebit'] == 1) {
                        $row .= "<td class='middle text-right font-weight-bold' style='padding-right: 4px !important;'>".Number::formatMoney($glRow['debitamount'], true)."</td>";
                        $row .= "<td class='middle text-center'></td>";
                        
                        $sumDebitAmount += ($glRow['debitamount']) ? $glRow['debitamount'] : 0;
                    } else {
                        $row .= "<td class='middle text-center'></td>";
                        $row .= "<td class='middle text-right font-weight-bold' style='padding-right: 4px !important;'>".Number::formatMoney($glRow['creditamount'], true)."</td>";
                        
                        $sumCreditAmount += ($glRow['creditamount']) ? $glRow['creditamount'] : 0;
                    }
                    
                    $row .= "<td class='middle text-center gl-action-column'>" . $rowTrash . "</td>";
                    $row .= "</tr>";

                    echo $row;

                    $j++;
                }
            }
            ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4"></td>
                    <td class="glRowExpenseCenter"></td>
                    <td class="text-right font-weight-bold" style="padding: 4px 3px !important;"><?php echo Number::formatMoney($sumDebitAmount, true); ?></td>
                    <td class="text-right font-weight-bold" style="padding: 4px 3px !important;"><?php echo Number::formatMoney($sumCreditAmount, true); ?></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>  
        <?php 
        echo Form::hidden(array('name' => 'bookTypeId', 'value' => $this->bookTypeId));
        echo Form::hidden(array('name' => 'processId', 'value' => $this->processId));
        echo Form::hidden(array('name' => 'objectId', 'value' => $this->objectId));
        echo Form::close(); 
        ?>
    </div>
</div>    

<script type="text/javascript">
var cashHandBank = ['20003', '20004'];
var taxPayable = '40';
var taxReceivable = '17';
var glBpMainWindow_<?php echo $this->uniqId; ?> = '#<?php echo $this->uniqId; ?>';
var paramGLData_<?php echo $this->uniqId; ?> = JSON.parse('<?php echo Json::encode($params); ?>');
var accountAutoCompleteRequest = null;
var isShowExpenseCenterField = <?php echo Config::getFromCacheDefault('CONFIG_GL_ROW_EXPENSE_CENTER', null, 0); ?>;

$(function(){
    Core.initAccountCodeMask($(glBpMainWindow_<?php echo $this->uniqId; ?>));
    
    checkIsUseGlDetail_<?php echo $this->uniqId; ?>();

    var $glDtl = $("#glDtl", glBpMainWindow_<?php echo $this->uniqId; ?>);
    if (isShowExpenseCenterField) {
        $glDtl.find("th.glRowExpenseCenter, td.glRowExpenseCenter").css({'display': ''});
    } else {
        $glDtl.find("th.glRowExpenseCenter, td.glRowExpenseCenter").css({'display': 'none'});
    }    
    
    $(glBpMainWindow_<?php echo $this->uniqId; ?>).on('click', 'table#glDtl > tbody > tr', function() {
        $('body').find("table#glDtl > tbody > tr.gl-selected-row").removeClass("gl-selected-row"); 
        $(this).addClass("gl-selected-row");
    });
    $(glBpMainWindow_<?php echo $this->uniqId; ?>).on('focus', '#glDtl > tbody > tr', function() {
        $('body').find("table#glDtl > tbody > tr.gl-selected-row").removeClass("gl-selected-row"); 
        $(this).addClass("gl-selected-row");
    });
    $(glBpMainWindow_<?php echo $this->uniqId; ?>).on("focus", 'input[name="gl_accountCode[]"]', function(e){
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
                        filter: $(tr).find("input[name='gl_accountFilter[]']").val()
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
                $(this).autocomplete('widget').zIndex(99999999999999);
                return false;
            },
            close: function (event, ui){
                $(this).autocomplete("option","appendTo","body"); 
            }, 
            select: function(event, ui){
                var data = ui.item.data;   

                $(tr).find("input[name='gl_accountId[]'], input[name='gl_accountCode[]'], input[name='gl_accountName[]'], input[name='gl_processId[]']").val('');
                $(tr).find("input[name='gl_isEdited[]']").val('0');
                $(tr).find("input[name='gl_accountCode[]']").val(data.ACCOUNTCODE);

                if (data !== false) {
                    if (isHoverSelect || event.originalEvent.originalEvent.type == 'click') {
                        changeRowAccount_<?php echo $this->uniqId; ?>(tr, data);
                        var originalEvent = event;
                        while (originalEvent) {
                            if (originalEvent.keyCode == 13)
                                originalEvent.stopPropagation();

                            if (originalEvent == event.originalEvent)
                                break;

                            originalEvent = event.originalEvent;
                        }
                    } else {
                        if (ui.item.label === _this.val()) {
                            changeRowAccount_<?php echo $this->uniqId; ?>(tr, data);
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
    $(glBpMainWindow_<?php echo $this->uniqId; ?>).on("keydown", 'input[name="gl_accountCode[]"]', function(e){

        var code = (e.keyCode ? e.keyCode : e.which);
        var _this = $(this);
        var tr = _this.closest("tr");   

        if (code === 13) {
            if (_this.data("ui-autocomplete")) {
                _this.autocomplete("destroy");
            } 

            $.ajax({
                type: 'post',
                url: 'mdgl/getRowAccountInfo',
                data: {q: _this.val()},
                dataType: "json",
                async: false,
                beforeSend: function () {
                    _this.addClass("spinner2");
                },
                success: function (data) {
                    if (Object.keys(data).length) {
                        if ($.isArray(data)) {
                            clearglDtlTr_<?php echo $this->uniqId; ?>(tr);
                        } else {
                            changeRowAccount_<?php echo $this->uniqId; ?>(tr, data);
                        }               
                    }
                    _this.removeClass("spinner2");
                }
            });
            return false;
        } else {
            if (!$(this).data("ui-autocomplete")) {
                $(this).trigger('focus');
            }
        }
    });
    $(glBpMainWindow_<?php echo $this->uniqId; ?>).on('focus', 'input[name="gl_customerCode[]"]', function(e) {
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
                $(this).autocomplete('widget').zIndex(99999999999999);
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
                    } else {
                        if (ui.item.label === _this.val()) {
                            $(tr).find("input[name='gl_customerId[]']").val(data.CUSTOMER_ID);
                            $(tr).find("input[name='gl_customerCode[]']").val(data.CUSTOMER_CODE);
                            $(tr).find("input[name='gl_customerName[]']").val(data.CUSTOMER_NAME);
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
    $(glBpMainWindow_<?php echo $this->uniqId; ?>).on('focus', 'input[name="gl_customerName[]"]', function(e) {
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
                $(this).autocomplete('widget').zIndex(99999999999999);
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
                    } else {
                        if (ui.item.label === _this.val()) {
                            $(tr).find("input[name='gl_customerId[]']").val(data.CUSTOMER_ID);
                            $(tr).find("input[name='gl_customerCode[]']").val(data.CUSTOMER_CODE);
                            $(tr).find("input[name='gl_customerName[]']").val(data.CUSTOMER_NAME);
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
    $(glBpMainWindow_<?php echo $this->uniqId; ?>).on("keydown", 'input[name="gl_customerCode[]"]', function(e){
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
                    } else {
                        $(tr).find("input[name='gl_customerId[]']").val('');
                        $(tr).find("input[name='gl_customerCode[]']").val('');
                        $(tr).find("input[name='gl_customerName[]']").val('');
                    }
                    _this.removeClass("spinner2");
                }
            });

            if (_this.closest('td').next().find('select').length) {
                _this.closest('td').next().find('select').select();
            } else if (_this.closest('td').next().nextAll('td:visible:first').length && typeof _this.closest('td').next().nextAll('td:visible:first').find('input[type="text"]').attr('readonly') === 'undefined') {
                _this.closest('td').next().nextAll('td:visible:first').find('input[type="text"]').select();
            } 

            return false;
        } else {
            if (!$(this).data("ui-autocomplete")) {
                $(this).trigger('focus');
            }
        }
    });
    $(glBpMainWindow_<?php echo $this->uniqId; ?>).on("keydown", 'input[name="gl_customerName[]"]', function(e){
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
                    } else {
                        $(tr).find("input[name='gl_customerId[]']").val('');
                        $(tr).find("input[name='gl_customerCode[]']").val('');
                        $(tr).find("input[name='gl_customerName[]']").val('');
                    }
                    _this.removeClass("spinner2");
                }
            });
               
            if (_this.closest('td').next().find('select').length) {
                _this.closest('td').next().find('select').select();
            } else if (_this.closest('td').next().nextAll('td:visible:first').length && typeof _this.closest('td').next().nextAll('td:visible:first').find('input[type="text"]').attr('readonly') === 'undefined') {
                _this.closest('td').next().nextAll('td:visible:first').find('input[type="text"]').select();
            }          

            return false;
        } else {
            if (!$(this).data("ui-autocomplete")) {
                $(this).trigger('focus');
            }
        }
    });
    $(glBpMainWindow_<?php echo $this->uniqId; ?>).on('focus', 'input[name="gl_expenseCenterCode[]"]', function(e) {
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
                    url: 'mdgl/autoCompleteByExpenseCode',
                    dataType: "json",
                    data: {code: request.term},
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.DEPARTMENT_CODE,
                                name: item.DEPARTMENT_NAME,
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
                $(this).autocomplete('widget').zIndex(99999999999999);
                return false;
            },
            close: function (event, ui){
                $(this).autocomplete("option","appendTo","body"); 
            }, 
            select: function(event, ui) {
                var data = ui.item.data;   
                if (data != false) {                   
                   if (isHoverSelect || event.originalEvent.originalEvent.type == 'click') {
                        $(tr).find("input[name='gl_expenseCenterId[]']").val(data.DEPARTMENT_ID);
                        $(tr).find("input[name='gl_expenseCenterCode[]']").val(data.DEPARTMENT_CODE);
                        $(tr).find("input[name='gl_expenseCenterName[]']").val(data.DEPARTMENT_NAME);
                    } else {
                        if (ui.item.label === _this.val()) {
                            $(tr).find("input[name='gl_expenseCenterId[]']").val(data.DEPARTMENT_ID);
                            $(tr).find("input[name='gl_expenseCenterCode[]']").val(data.DEPARTMENT_CODE);
                            $(tr).find("input[name='gl_expenseCenterName[]']").val(data.DEPARTMENT_NAME);
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
    $(glBpMainWindow_<?php echo $this->uniqId; ?>).on('focus', 'input[name="gl_expenseCenterName[]"]', function(e) {
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
                    url: 'mdgl/autoCompleteByExpenseCode',
                    dataType: "json",
                    data: {name: request.term},
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.DEPARTMENT_CODE,
                                name: item.DEPARTMENT_NAME,
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
                $(this).autocomplete('widget').zIndex(99999999999999);
                return false;
            },
            close: function (event, ui){
                $(this).autocomplete("option","appendTo","body"); 
            }, 
            select: function(event, ui) {
                var data = ui.item.data;   
                if (data != false) {
                    if (isHoverSelect || event.originalEvent.originalEvent.type == 'click') {
                        $(tr).find("input[name='gl_expenseCenterId[]']").val(data.DEPARTMENT_ID);
                        $(tr).find("input[name='gl_expenseCenterCode[]']").val(data.DEPARTMENT_CODE);
                        $(tr).find("input[name='gl_expenseCenterName[]']").val(data.DEPARTMENT_NAME);
                    } else {
                        if (ui.item.label === _this.val()) {
                            $(tr).find("input[name='gl_expenseCenterId[]']").val(data.DEPARTMENT_ID);
                            $(tr).find("input[name='gl_expenseCenterCode[]']").val(data.DEPARTMENT_CODE);
                            $(tr).find("input[name='gl_expenseCenterName[]']").val(data.DEPARTMENT_NAME);
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
    $(glBpMainWindow_<?php echo $this->uniqId; ?>).on("keydown", 'input[name="gl_expenseCenterCode[]"]', function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var _this = $(this);
        var tr = _this.closest("tr");  

        if (code === 13) {
            if (_this.data("ui-autocomplete")) {
                _this.autocomplete("destroy");
            } 

            $.ajax({
                type: 'post',
                url: 'mdgl/getExpenseCenterInfo',
                data: {code: _this.val()},
                dataType: "json",
                async: false,
                beforeSend: function () {
                    _this.addClass("spinner2");
                },
                success: function (data) {
                    if (!($.isArray(data))) {
                        $(tr).find("input[name='gl_expenseCenterId[]']").val(data.DEPARTMENT_ID);
                        $(tr).find("input[name='gl_expenseCenterCode[]']").val(data.DEPARTMENT_CODE);
                        $(tr).find("input[name='gl_expenseCenterName[]']").val(data.DEPARTMENT_NAME);
                    } else {
                        $(tr).find("input[name='gl_expenseCenterId[]']").val('');
                        $(tr).find("input[name='gl_expenseCenterCode[]']").val('');
                        $(tr).find("input[name='gl_expenseCenterName[]']").val('');
                    }
                    _this.removeClass("spinner2");
                }
            });

            if (_this.closest('td').next().find('select').length) {
                _this.closest('td').next().find('select').select();
            } else if (_this.closest('td').next().nextAll('td:visible:first').length && typeof _this.closest('td').next().nextAll('td:visible:first').find('input[type="text"]').attr('readonly') === 'undefined') {
                _this.closest('td').next().nextAll('td:visible:first').find('input[type="text"]').select();
            } 

            return false;
        } else {
            if (!$(this).data("ui-autocomplete")) {
                $(this).trigger('focus');
            }
        }
    });
    $(glBpMainWindow_<?php echo $this->uniqId; ?>).on("keydown", 'input[name="gl_expenseCenterName[]"]', function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var _this = $(this);
        var tr = _this.closest("tr");  

        if (code === 13) {
            if (_this.data("ui-autocomplete")) {
                _this.autocomplete("destroy");
            } 

            $.ajax({
                type: 'post',
                url: 'mdgl/getExpenseCenterInfo',
                data: {name: _this.val()},
                dataType: "json",
                async: false,
                beforeSend: function () {
                    _this.addClass("spinner2");
                },
                success: function (data) {
                    if (!($.isArray(data))) {
                        $(tr).find("input[name='gl_expenseCenterId[]']").val(data.DEPARTMENT_ID);
                        $(tr).find("input[name='gl_expenseCenterCode[]']").val(data.DEPARTMENT_CODE);
                        $(tr).find("input[name='gl_expenseCenterName[]']").val(data.DEPARTMENT_NAME);
                    } else {
                        $(tr).find("input[name='gl_expenseCenterId[]']").val('');
                        $(tr).find("input[name='gl_expenseCenterCode[]']").val('');
                        $(tr).find("input[name='gl_expenseCenterName[]']").val('');
                    }
                    _this.removeClass("spinner2");
                }
            });
               
            if (_this.closest('td').next().find('select').length) {
                _this.closest('td').next().find('select').select();
            } else if (_this.closest('td').next().nextAll('td:visible:first').length && typeof _this.closest('td').next().nextAll('td:visible:first').find('input[type="text"]').attr('readonly') === 'undefined') {
                _this.closest('td').next().nextAll('td:visible:first').find('input[type="text"]').select();
            }          

            return false;
        } else {
            if (!$(this).data("ui-autocomplete")) {
                $(this).trigger('focus');
            }
        }
    });
});  
function changeRowAccount_<?php echo $this->uniqId; ?>(tr, data){
    $(tr).find("input[name='gl_accountId[]']").val(data.ID);
    $(tr).find("input[name='gl_accountCode[]']").val(data.ACCOUNTCODE);
    $(tr).find("input[name='gl_accountName[]']").val(data.ACCOUNTNAME).attr('title', data.ACCOUNTNAME);
    $(tr).find("input[name='gl_main_accounttypeid[]']").val(data.ACCOUNTTYPEID);
    $(tr).find("input[name='gl_accounttypeCode[]']").val(data.ACCOUNTTYPECODE);
    $(tr).find("input[name='gl_objectId[]']").val(data.OBJECTID);
    $(tr).find("input[name='gl_useDetailBook[]']").val(data.ISUSEDETAILBOOK);             

    tr.closest("table").find("tbody").find("tr").removeClass("currentTRtarget");
    tr.addClass("currentTRtarget");                         
    
    showDtlMeta_<?php echo $this->uniqId; ?>(tr);    

    return false;
}
function clearglDtlTr_<?php echo $this->uniqId; ?>(tr){
    $(tr).find("input:not([id='gl_subid'], [id='gl_debitamount'], [id='gl_creditamount'])").val('');
    $(tr).find("input[name='gl_isEdited[]']").val('0');
    if ($(tr).find("td.gl-action-column").find("#detailedMeta").length > 0) {
        $(tr).find("td.gl-action-column").find("#detailedMeta").remove();
    }
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
function checkIsUseGlDetail_<?php echo $this->uniqId; ?>() {
        
    var paramGL = paramGLData_<?php echo $this->uniqId; ?>;

    if (paramGL.hasOwnProperty('booktypeid') && (paramGL.booktypeid == '42' || paramGL.booktypeid == '43')) {
        return;
    }

    $("table#glDtl > tbody > tr", glBpMainWindow_<?php echo $this->uniqId; ?>).each(function(i, k) {
        var $thisRow = $(this);
        var accountId = $thisRow.find("input[name='gl_accountId[]']").val();
        
        if (accountId !== '') {
            var subid = $thisRow.find("input[name='gl_subid[]']").val();
            var isDebit = $thisRow.find("input[name='gl_isdebit[]']").val();

            var selectedRow = {
                'accountid': accountId,
                'accountcode': $thisRow.find("input[name='gl_accountCode[]']").val(),
                'accountname': $thisRow.find("input[name='gl_accountName[]']").val(),
                'accounttypeid': $thisRow.find("input[name='gl_main_accounttypeid[]']").val(),
                'accounttypecode': $thisRow.find("input[name='gl_accounttypeCode[]']").val(),
                'usedetail': $thisRow.find("input[name='gl_useDetailBook[]']").val(),
                'objectid': $thisRow.find("input[name='gl_objectId[]']").val(),
                'isdebit': isDebit,
                'detailvalues': $thisRow.find("input[name='gl_metas[]']").val()
            };
            
            var opMeta = fillOpMeta_<?php echo $this->uniqId; ?>($thisRow, accountId, subid, isDebit);
            if (opMeta != '') {
                selectedRow['opMeta'] = opMeta;
            }

            $.ajax({
                type: 'post',
                url: 'mdgl/getAccountMeta',
                data: {selectedRow: selectedRow, paramData: paramGLData_<?php echo $this->uniqId; ?>},
                dataType: 'json',
                beforeSend: function() {
                    Core.blockUI({
                        message: 'Loading...',
                        boxed: true
                    });
                },
                success: function(data) {
                    
                    if (data.isemptymeta !== '1') { 
                        $thisRow.find("td.gl-action-column").html("<div class='btn btn-xs purple-plum gl-dtl-meta-btn' title='Үзүүлэлт' onclick='showDtlMeta_<?php echo $this->uniqId; ?>(this);'>...</div>");
                    } 

                    $thisRow.find("input[name='gl_ismetas[]']").val(data.isemptymeta);
                    
                    if (data.hasOwnProperty('isUseOppAccount')) {
                        $thisRow.attr('data-op-meta', data.isUseOppAccount);
                    }
                        
                    bpSetGlMetaOneRowIndex(i, $thisRow); 

                    Core.unblockUI();
                },
                error: function() {
                    alert("Error");
                }
            });
        }
    });
}
function showDtlMeta_<?php echo $this->uniqId; ?>(elem) {
    var tr = $(elem).closest('tr');
    var appendRow = tr.find("td.gl-action-column");
    tr.closest("table").find("tbody").find("tr").removeClass("currentTRtarget");
    tr.addClass("currentTRtarget");  

    var subid = tr.find("input[name='gl_subid[]']").val();
    var accountid = tr.find("input[name='gl_accountId[]']").val();

    var selectedRow = {
        'accountid': accountid,
        'accountcode': tr.find("input[name='gl_accountCode[]']").val(),
        'accountname': tr.find("input[name='gl_accountName[]']").val(),
        'accounttypeid': tr.find("input[name='gl_main_accounttypeid[]']").val(),
        'accounttypecode': tr.find("input[name='gl_accounttypeCode[]']").val(),
        'usedetail': tr.find("input[name='gl_useDetailBook[]']").val(), 
        'objectid': tr.find("input[name='gl_objectId[]']").val(), 
        'isdebit': tr.find("input[name='gl_isdebit[]']").val(), 
        'detailvalues': tr.find("input[name='gl_metas[]']").val()
    };
    
    var $dialogName = 'dialog-gl-row-metas';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $rowDialog = $('#' + $dialogName);
    
    if (appendRow.find('#gl-metas-clone').length === 0) {
        
        var opMeta = fillOpMeta_<?php echo $this->uniqId; ?>(tr, accountid, subid, selectedRow['isdebit']);
        if (opMeta != '') {
            selectedRow['opMeta'] = opMeta;
        }
                
        appendRow.append('<div id="gl-metas-clone" class="hide"></div>');
        
        $.ajax({
            type: 'post',
            url: 'mdgl/getAccountMeta',
            data: {selectedRow: selectedRow, paramData: paramGLData_<?php echo $this->uniqId; ?>},
            async : false,
            dataType: "json",
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function(data) {
                if (data.isemptymeta !== '1') { 
                    
                    appendRow.find('.gl-dtl-meta-btn').remove();
                    appendRow.append("<div class='btn btn-xs purple-plum gl-dtl-meta-btn' title='Үзүүлэлт' onclick='showDtlMeta_<?php echo $this->uniqId; ?>(this);'>...</div>");
                    
                    appendRow.find('#gl-metas-clone').html(data.html);

                    if ($("#glExpandedWindow", tr).find("table:eq(0) tr").length > 0) {
                        
                        bpSetGlMetaRowIndex(glBpMainWindow_<?php echo $this->uniqId; ?>);
                        
                        var glMetaWidth = 500;
                        if ($("#glExpandedWindow", tr).find("table:eq(0) tr:first-child > td").length > 2) {
                            glMetaWidth = 1200;
                        }
                        
                        var metasCloned = appendRow.find('#gl-metas-clone').children().clone();
                        
                        $rowDialog.html(metasCloned);

                        $rowDialog.dialog({
                            cache: false,
                            resizable: true,
                            bgiframe: true,
                            autoOpen: false,
                            title: data.title,
                            width: glMetaWidth, 
                            height: 'auto', 
                            minHeight: 150, 
                            modal: true, 
                            closeOnEscape: false, 
                            open: function(){
                                if (glMetaWidth === 500) {
                                    $("#glExpandedWindow", tr).find('hr').remove();
                                }
                                
                                $('.ui-dialog:last').css('z-index', 104);
                                $('.ui-widget-overlay:last').css('z-index', 103);
                                //$("#" + $dialogName).parent().find('.ui-dialog-titlebar-close').css({'display': 'none'});
                                
                                setTimeout(function(){
                                    $rowDialog.find('input[type="text"]:visible:first').focus();
                                    nonRequiredCashOnHand_<?php echo $this->uniqId; ?>(tr.find("input[name='gl_subid[]']").val());
                                }, 10);
                                
                                $rowDialog.parent().find('button.bp-btn-save').on('keydown', function (event) {
                                    if (event.keyCode == 13) {
                                       $(this).click();
                                       return false;
                                    }
                                });
                                
                                $rowDialog.on('change', 'input.popupInit', function(){
                                    var $this = $(this), $parent = $this.closest('.meta-autocomplete-wrap'), 
                                        $parentCell = $this.closest('td'), 
                                        $segmentInput = $parentCell.find('input[data-segment-code]'), 
                                        segmentPath = $segmentInput.attr('data-segment-code'), 
                                        $parentRow = $this.closest('tr'), 
                                        segCode = '', segName = '', 
                                        $nextRow = $parentRow.next('tr[data-cell-path]:visible:eq(0)');

                                    if ($segmentInput.length) {       
                                        if ($parent.find('input[type="hidden"]').val() != '') {
                                            segCode = $parent.find('input.lookup-code-autocomplete').val(), 
                                            segName = $parent.find('input.lookup-name-autocomplete').val();

                                            $segmentInput.val(segCode+'|'+segName);
                                        } else {
                                            segCode = '__';
                                            $segmentInput.val('');
                                        }

                                        $rowDialog.find('span[data-st-path="'+segmentPath+'"]').text(segCode);
                                    }

                                    if ($nextRow.length) {
                                        $nextRow.find('input:visible:first').focus().select();
                                    } else {
                                        $parentCell.closest('.ui-dialog').find('button.bp-btn-save').focus();
                                    }
                                });
                                
                                $rowDialog.on('change', 'select.select2', function(){
                                    var $this = $(this),  
                                        $parentCell = $this.closest('td'), 
                                        $segmentInput = $parentCell.find('input[data-segment-code]'), 
                                        segmentPath = $segmentInput.attr('data-segment-code'), 
                                        $parentRow = $this.closest('tr'), 
                                        segCode = '', segName = '', 
                                        $nextRow = $parentRow.next('tr[data-cell-path]:visible:eq(0)');

                                    if ($segmentInput.length) { 
                                        if ($this.val() != '') { 
                                            var selectedValue = $this.find('option:selected').text();
                                            var selectedValueArr = selectedValue.split('-');
                                            segCode = selectedValueArr[0];
                                            segName = selectedValueArr[1];

                                            $segmentInput.val(segCode+'|'+segName);
                                        } else {
                                            segCode = '__';
                                            $segmentInput.val('');
                                        }

                                        $rowDialog.find('span[data-st-path="'+segmentPath+'"]').text(segCode);
                                    }

                                    if ($nextRow.length) {
                                        $nextRow.find('input:visible:first').focus().select();
                                    } else {
                                        $parentCell.closest('.ui-dialog').find('button.bp-btn-save').focus();
                                    }
                                });
                                
                                $rowDialog.find('tr[data-segment-row="1"]').each(function(){
                                    var $segmentRow = $(this), segmentPath = $segmentRow.attr('data-cell-path');
                                    
                                    if ($segmentRow.find('input.popupInit').length) {
                
                                        var segId = $segmentRow.find('input.popupInit').val();

                                        if (segId) {
                                            var segCode = $segmentRow.find('input.lookup-code-autocomplete').val(), 
                                                segName = $segmentRow.find('input.lookup-name-autocomplete').val(), 
                                                $segmentInput = $segmentRow.find('input[data-segment-code]');

                                            $segmentInput.val(segCode+'|'+segName);
                                            $rowDialog.find('span[data-st-path="'+segmentPath+'"]').text(segCode);
                                        }

                                    } else if ($segmentRow.find('select.select2').length) {

                                        var segId = $segmentRow.find('select.select2').val();

                                        if (segId) {
                                            var selectedValue = $segmentRow.find('select.select2').find('option:selected').text();
                                            var selectedValueArr = selectedValue.split('-');
                                            var segCode = selectedValueArr[0], 
                                                segName = selectedValueArr[1], 
                                                $segmentInput = $segmentRow.find('input[data-segment-code]');

                                            $segmentInput.val(segCode+'|'+segName);
                                            $rowDialog.find('span[data-st-path="'+segmentPath+'"]').text(segCode);
                                        }
                                    }
                                });
                            },
                            close: function () { 
                                
                                var metasCloneElement = $rowDialog.children();
                                var dropDowns = metasCloneElement.find('select.select2');
                                
                                dropDowns.each(function(){
                                    var $sThis = $(this);
                                    var $sVal = $sThis.val();
                                    $sThis.find('option:selected').removeAttr('selected');
                                    $sThis.find("option[value='"+$sVal+"']").attr('selected', 'selected');
                                });
                                
                                dropDowns.select2('destroy');
                                var metasCloned = metasCloneElement.clone();
                                
                                appendRow.find('#gl-metas-clone').html(metasCloned);
                                
                                $rowDialog.empty().dialog('destroy').remove();
                            },                                
                            buttons: [
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
                                        $rowDialog.dialog('close');
                                    } else {
                                        new PNotify({
                                            title: 'Warning',
                                            text: 'Дэлгэрэнгүй үзүүлэлтийг бүрэн бөглөнө үү',
                                            type: 'warning',
                                            sticker: false
                                        });
                                    }
                                }}
                            ]
                        });
                        $rowDialog.dialog('open');

                    } else {
                        $(tr).find("#gl-metas-clone").remove();
                    }

                } else {
                    $(tr).find("#gl-metas-clone").remove();
                }
            },
            error: function() {
                alert("Error");
            }
            
        }).done(function() {

            Core.initDateInput($rowDialog);
            Core.initNumberInput($rowDialog);
            Core.initLongInput($rowDialog);
            Core.initUniform($rowDialog);
            Core.initRegexMaskInput($rowDialog);
            Core.initAccountCodeMask($rowDialog);
            Core.initSelect2WidthAutoFalse($rowDialog);

            Core.unblockUI();
        });   
        
    } else {
        
        var $glRowElement = $("#" + $dialogName);
        var metasCloned = appendRow.find('#gl-metas-clone').children().clone();
                        
        $glRowElement.html(metasCloned).promise().done(function(){
            
            var glMetaWidth = 500;
            if ($("#glExpandedWindow", tr).find("table:eq(0) tr:first-child > td").length > 2) {
                glMetaWidth = 1350;
            }
                        
            $glRowElement.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Дэлгэрэнгүй',
                width: glMetaWidth, 
                height: 'auto', 
                minHeight: 150, 
                modal: true, 
                closeOnEscape: false, 
                open: function(){
                    if (glMetaWidth === 500) {
                        $("#glExpandedWindow", tr).find('hr').remove();
                    }
                    
                    $('.ui-dialog:last').css('z-index', 104);
                    $('.ui-widget-overlay:last').css('z-index', 103);
                    //$glRowElement.parent().find('.ui-dialog-titlebar-close').css({'display': 'none'});
                    
                    setTimeout(function(){
                        $glRowElement.find('input[type="text"]:visible:first').focus();
                    }, 10);
                    
                    $glRowElement.parent().find('button.bp-btn-save').on('keydown', function (event) {
                        if (event.keyCode == 13) {
                           $(this).click();
                           return false;
                        }
                    });
                    
                    $glRowElement.on('change', 'input.popupInit', function(){
                        var $this = $(this), $parent = $this.closest('.meta-autocomplete-wrap'), 
                            $parentCell = $this.closest('td'), 
                            $segmentInput = $parentCell.find('input[data-segment-code]'), 
                            segmentPath = $segmentInput.attr('data-segment-code'), 
                            $parentRow = $this.closest('tr'), 
                            segCode = '', segName = '', 
                            $nextRow = $parentRow.next('tr[data-cell-path]:visible:eq(0)');

                        if ($segmentInput.length) {       
                            if ($parent.find('input[type="hidden"]').val() != '') {
                                segCode = $parent.find('input.lookup-code-autocomplete').val(), 
                                segName = $parent.find('input.lookup-name-autocomplete').val();

                                $segmentInput.val(segCode+'|'+segName);
                            } else {
                                segCode = '__';
                                $segmentInput.val('');
                            }

                            $glRowElement.find('span[data-st-path="'+segmentPath+'"]').text(segCode);
                        }

                        if ($nextRow.length) {
                            $nextRow.find('input:visible:first').focus().select();
                        } else {
                            $parentCell.closest('.ui-dialog').find('button.bp-btn-save').focus();
                        }
                    });
                    
                    $glRowElement.on('change', 'select.select2', function(){
                        var $this = $(this),  
                            $parentCell = $this.closest('td'), 
                            $segmentInput = $parentCell.find('input[data-segment-code]'), 
                            segmentPath = $segmentInput.attr('data-segment-code'), 
                            $parentRow = $this.closest('tr'), 
                            segCode = '', segName = '', 
                            $nextRow = $parentRow.next('tr[data-cell-path]:visible:eq(0)');

                        if ($segmentInput.length) { 
                            if ($this.val() != '') { 
                                var selectedValue = $this.find('option:selected').text();
                                var selectedValueArr = selectedValue.split('-');
                                segCode = selectedValueArr[0];
                                segName = selectedValueArr[1];

                                $segmentInput.val(segCode+'|'+segName);
                            } else {
                                segCode = '__';
                                $segmentInput.val('');
                            }

                            $glRowElement.find('span[data-st-path="'+segmentPath+'"]').text(segCode);
                        }

                        if ($nextRow.length) {
                            $nextRow.find('input:visible:first').focus().select();
                        } else {
                            $parentCell.closest('.ui-dialog').find('button.bp-btn-save').focus();
                        }
                    });

                    $glRowElement.find('tr[data-segment-row="1"]').each(function(){
                        var $segmentRow = $(this), segmentPath = $segmentRow.attr('data-cell-path');
                        
                        if ($segmentRow.find('input.popupInit').length) {
                
                            var segId = $segmentRow.find('input.popupInit').val();

                            if (segId) {
                                var segCode = $segmentRow.find('input.lookup-code-autocomplete').val(), 
                                    segName = $segmentRow.find('input.lookup-name-autocomplete').val(), 
                                    $segmentInput = $segmentRow.find('input[data-segment-code]');

                                $segmentInput.val(segCode+'|'+segName);
                                $glRowElement.find('span[data-st-path="'+segmentPath+'"]').text(segCode);
                            }

                        } else if ($segmentRow.find('select.select2').length) {

                            var segId = $segmentRow.find('select.select2').val();

                            if (segId) {
                                var selectedValue = $segmentRow.find('select.select2').find('option:selected').text();
                                var selectedValueArr = selectedValue.split('-');
                                var segCode = selectedValueArr[0], 
                                    segName = selectedValueArr[1], 
                                    $segmentInput = $segmentRow.find('input[data-segment-code]');

                                $segmentInput.val(segCode+'|'+segName);
                                $glRowElement.find('span[data-st-path="'+segmentPath+'"]').text(segCode);
                            }
                        }
                    });
                },
                close: function () { 
                    
                    var metasCloneElement = $("#" + $dialogName).children();
                    var dropDowns = metasCloneElement.find('select.select2');
                    
                    dropDowns.each(function(){
                        var $sThis = $(this);
                        var $sVal = $sThis.val();
                        $sThis.find('option[selected="selected"]').removeAttr('selected');
                        $sThis.find("option[value='"+$sVal+"']").attr('selected', 'selected');
                    });
                    
                    dropDowns.select2('destroy');
                    var metasCloned = metasCloneElement.clone();

                    appendRow.find('#gl-metas-clone').html(metasCloned);

                    $glRowElement.empty().dialog('destroy').remove();
                },                                
                buttons: [
                    {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow bp-btn-save', click: function() {
                        PNotify.removeAll();

                        var validDtl = true;
                        $glRowElement.find('input,textarea,select').filter('[required="required"]').removeClass('error');

                        $glRowElement.find('input,textarea,select').filter('[required="required"]').each(function(){
                            if (($(this).attr('id') != 'accountId_displayField' && $(this).attr('id') != 'accountId_nameField') && $(this).val() == '') {
                                $(this).addClass('error');  
                                validDtl = false;
                            }
                        });

                        if (validDtl) {
                            $glRowElement.dialog('close');
                        } else {
                            new PNotify({
                                title: 'Warning',
                                text: 'Дэлгэрэнгүй үзүүлэлтийг бүрэн бөглөнө үү!',
                                type: 'warning',
                                sticker: false
                            });
                        }
                    }}
                ]
            });

            Core.initDateInput($glRowElement);
            Core.initNumberInput($glRowElement);
            Core.initLongInput($glRowElement);
            Core.initSelect2WidthAutoFalse($glRowElement);
            Core.initUniform($glRowElement);
            Core.initRegexMaskInput($glRowElement);
            Core.initAccountCodeMask($glRowElement);
            
            $glRowElement.dialog('open');
        });
    }
}
function nonRequiredCashOnHand_<?php echo $this->uniqId; ?>(subid) {    
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
        $('select[data-path="cashFlowSubCategoryId"]', '#dialog-gl-row-metas').removeAttr('required');		
        $('#glDtl > tbody > tr', glBpMainWindow_<?php echo $this->uniqId; ?>).each(function() {
            var $thisRow = $(this);
            if ($thisRow.find("input[name='gl_subid[]']").val() == subid) { 
                $(this).find('input[name="gl_cashflowsubcategoryid[]"]').val(1);
            }
        });       
    } else {
        if ($('label[data-label-path="cashFlowSubCategoryId"]', '#dialog-gl-row-metas').find('span.required').length) {
            $('label[data-label-path="cashFlowSubCategoryId"]', '#dialog-gl-row-metas').attr('required', 'required');
        }		
        $('#glDtl > tbody > tr', glBpMainWindow_<?php echo $this->uniqId; ?>).each(function() {
            var $thisRow = $(this);
            if ($thisRow.find("input[name='gl_subid[]']").val() == subid) { 
                $(this).find('input[name="gl_cashflowsubcategoryid[]"]').val(0);
            }
        });         
    }
}
function fillOpMeta_<?php echo $this->uniqId; ?>(rowEl, accountId, subId, isDebit) {
    var opMeta = '', isDebit = (isDebit == '1') ? '1' : '0';
    var $opMetaAttr = $('#glDtl tr[data-sub-id="'+subId+'"][data-op-meta]:not([data-op-meta=""]):eq(0)', glBpMainWindow_<?php echo $this->uniqId; ?>);
    var $opMetaAttrIsDebit = $opMetaAttr.find("input[name='gl_isdebit[]']").val();

    if (!rowEl.hasAttr('data-op-meta') || (rowEl.hasAttr('data-op-meta') && rowEl.attr('data-op-meta') == '')) {

        if ($opMetaAttr.length && $('#glDtl input[name="gl_isdebit[]"][value="'+$opMetaAttrIsDebit+'"]', glBpMainWindow_<?php echo $this->uniqId; ?>).length > 1) {

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
function bpSetGlMetaRowIndex(window) {
    var $el = $('table#glDtl > tbody > tr', window), len = $el.length, i = 0;
    for (i; i < len; i++) { 
        var $subElement = $($el[i]).find("input[name*='accountMeta['], select[name*='accountMeta[']"), 
            slen = $subElement.length, j = 0;

        for (j; j < slen; j++) { 
            var $inputThis = $($subElement[j]), _inputName = $inputThis.attr('name');
        
            if (typeof _inputName !== 'undefined') {
                $inputThis.attr('name', _inputName.replace(/^accountMeta(\[[0-9]+\])(.*)$/, 'accountMeta[' + i + ']$2'));
            }
        }
    }
    return;
}
function accountSelectabledGrid_<?php echo $this->uniqId; ?>(metaDataCode, chooseType, elem, rows) {
    var row = rows[0], $tr = $(elem).closest('tr');

    $tr.find("input[name='gl_accountId[]']").val(row.id);
    $tr.find("input[name='gl_accountCode[]']").val(row.accountcode);
    $tr.find("input[name='gl_accountName[]']").val(row.accountname).attr('title', row.accountname);
    $tr.find("input[name='gl_main_accounttypeid[]']").val(row.accounttypeid);
    $tr.find("input[name='gl_accounttypeCode[]']").val(row.accounttypecode);
    $tr.find("input[name='gl_objectId[]']").val(row.objectid);
    $tr.find("input[name='gl_useDetailBook[]']").val(row.isusedetailbook);

    showDtlMeta_<?php echo $this->uniqId; ?>($tr);
}
function customerSelectabledGrid(metaDataCode, chooseType, elem, rows) {
    var row = rows[0], $tr = $(elem).closest('tr');
    $tr.find("input[name='gl_customerId[]']").val(row.id);
    $tr.find("input[name='gl_customerCode[]']").val(row.customercode).attr('title', row.customercode);
    $tr.find("input[name='gl_customerName[]']").val(row.customername).attr('title', row.customername);
}
function customerSelectabledExpenseGrid(metaDataCode, chooseType, elem, rows) {
    var row = rows[0], $tr = $(elem).closest('tr');
    $tr.find("input[name='gl_expenseCenterId[]']").val(row.id);
    $tr.find("input[name='gl_expenseCenterCode[]']").val(row.departmentcode).attr('title', row.departmentcode);
    $tr.find("input[name='gl_expenseCenterName[]']").val(row.departmentname).attr('title', row.departmentname);
}
</script>

<style type="text/css">    
#glDtl > tbody > tr > td > .ui-dialog-title {
    text-align: left !important;
}    
</style>