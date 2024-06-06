<div id="glExpandedWindow">
    <div class="col-md-12">
        <div class="row">
            <div class="form-body xs-form" id="gl_meta_row_<?php echo $this->uniqId; ?>">
                <?php
                if ($this->selectedRow['usedetail'] == '1' || $this->selectedRow['usedetail'] == 'true') {
                    
                    echo '<input type="hidden" id="book_debitamount" name=book_debitamount[' . $this->selectedRow['accountid'] . ']>'
                    . '<input type="hidden" id="book_creditamount" name=book_creditamount[' . $this->selectedRow['accountid'] . ']>'
                    . '<input type="hidden" id="book_debitamountbase" name=book_debitamountbase[' . $this->selectedRow['accountid'] . ']>'
                    . '<input type="hidden" id="book_creditamountbase" name=book_creditamountbase[' . $this->selectedRow['accountid'] . ']>'
                    . '<input type="hidden" id="book_rate" name=book_rate[' . $this->selectedRow['accountid'] . ']>'
                    . '<input type="hidden" id="book_desc" name=book_desc[' . $this->selectedRow['accountid'] . ']>';
                    
                    $invoiceValueId = '';
                    
                    if ($this->selectedRow['invoices'] != '') {
                        $invoiceValueId = $this->selectedRow['invoices'];
                    }
                    
                    echo '<input type="hidden" id="gl_invoiceBookId_popup" value="' . $invoiceValueId . '">';
                    
                    if ($this->selectedRow['objectid'] == '20007') {                                    
                        if (isset($this->receivableDtl)) {
                            echo $this->receivableDtl;
                        }
                    } elseif ($this->selectedRow['objectid'] == '20006') {
                        if (isset($this->payableDtl)) {
                            echo $this->payableDtl;
                        }
                    } else {
                        if (substr($this->selectedRow['invoices'], 0, 3) != 'vid') {
                ?>          
                <div id="bookGrid"></div>
                <hr />
                <?php
                        }
                    }
                }           
                ?>
                <table class="table table-sm table-no-bordered" style="margin-top: -5px; table-layout: fixed !important">  
                    <?php
                    $segmentSeparator = '';
                    if (isset($this->metaRows) && !empty($this->metaRows)) {
                        
                        $accountTypeCode = strtolower($this->selectedRow['accounttypecode']);
                        $isDuplicateCheckbox = '<td></td>';
                        $isEmptyDimensionCheckbox = '<td></td>';
                        
                        if (Config::getFromCache('CONFIG_GL_META_DUPLICATE')) {
                            $isDuplicateCheckbox = '<td style="width: 21px; vertical-align: middle"><input type="checkbox" class="is-ac-meta-duplicate" title="Ижил төрөлтэй дансны үзүүлэлт ижилсүүлэх"></td>';
                        }                        
                        
                        foreach ($this->metaRows as $value) {
                            
                            $lowerPath = strtolower($value['path']);                 
                            
                            $dimcheck = '';
                            $dimensionConfig = issetParam($this->detailvalues['dimensionconfig']) ? json_decode($this->detailvalues['dimensionconfig'], true) : [];
                            $dimensionConfig = issetParam($dimensionConfig['rows']);
                            if (is_array($dimensionConfig) && array_key_exists($lowerPath, $dimensionConfig)) {
                                $dimcheck = ' checked';
                            }                       
                            $isEmptyDimensionCheckbox = '<td style="width: 21px; vertical-align: middle"><input type="checkbox"'.$dimcheck.' name="accountMeta[0][' . $this->selectedRow['accountid'] . ']['.$lowerPath.'_accEmptyDimension]" value="1" class="is-ac-meta-empty d-none" title="Дансны үзүүлэлт цэвэрлэх"></td>';
                            
                            if ($lowerPath == 'vatattrsubcategoryid') {
                                
                                if (!isset($taxPayableData)) {
                                    $taxPayableData = (new Mdgl())->getTaxMetaValuesToGrid(0, $this->selectedRow['isdebit']);
                                    $taxReceivableData = (new Mdgl())->getTaxMetaValuesToGrid(1, $this->selectedRow['isdebit']);
                                }

                                $taxdata = $valueArray = array();
                                $isVatAttr = false;
                                
                                if ($this->selectedRow['accounttypeid'] == Mdgl::$taxPayable || substr_count($accountTypeCode, 'payable')) {
                                    $taxdata = $taxPayableData;
                                    $isVatAttr = true;
                                    $valueArray = array('value' => defined('CONFIG_GL_PAYABLE_DEFAULT_VALUE') ? CONFIG_GL_PAYABLE_DEFAULT_VALUE : '');
                                } elseif ($this->selectedRow['accounttypeid'] == Mdgl::$taxReceivable || substr_count($accountTypeCode, 'receivable')) {
                                    $taxdata = $taxReceivableData;
                                    $isVatAttr = true;
                                    $valueArray = array('value' => defined('CONFIG_GL_RECEIVABLE_DEFAULT_VALUE') ? CONFIG_GL_RECEIVABLE_DEFAULT_VALUE : '');
                                }
                                
                                if (isset($this->detailvalues['vatattrsubcategoryid'])) {
                                    $valueArray = array('value' => $this->detailvalues['vatattrsubcategoryid']);
                                }
                                
                                if (Config::getFromCache('CONFIG_GL_VAT_META_VALIDATE_IGNORE') && $isVatAttr == false) {
                                    
                                    if ($this->selectedRow['isdebit'] == 1) {
                                        $taxdata = $taxReceivableData;
                                    } else {
                                        $taxdata = $taxPayableData;
                                    }
                                }
                                
                                if ((is_array($this->detailvalues) && !array_key_exists('vatattrsubcategoryid', $this->detailvalues)) || !is_array($this->detailvalues)) {
                                    $valueArray = array('value' => $value['defaultValue']);
                                }

                                if ($value['isRequired'] == '1') {
                                    $valueArray['required'] = 'required';
                                }

                                $value['input'] = Form::select(
                                    array_merge(
                                        array(
                                            'name' => 'accountMeta[0][' . $this->selectedRow['accountid'] . '][vatattrsubcategoryid]',
                                            'id' => 'gl_vatAttrId[]',
                                            'data-path' => $value['path'], 
                                            'data-col-path' => $value['accountFilter'], 
                                            'class' => 'form-control form-control-sm select2',
                                            'data' => $taxdata,     
                                            'op_value' => 'VAT_ATTR_SUB_CATEGORY_ID',
                                            'op_text' => 'CODE|-|NAME'
                                        ), 
                                        $valueArray
                                    )
                                );
                                
                            } elseif ($lowerPath == 'cashflowsubcategoryid') {

                                $valueArray = array();

                                if (isset($this->detailvalues['cashflowsubcategoryid'])) {
                                    $valueArray = array('value' => $this->detailvalues['cashflowsubcategoryid']);
                                } elseif (isset($value['defaultValue'])) {
                                    $valueArray = array('value' => $value['defaultValue']);
                                }

                                if ($this->selectedRow['isdebit'] == 1) {
                                    $data = $this->cashFlowDebitData;
                                    /**
                                     * Default value der is_credit uzuulelt songoson tul eniig nemj push hiiw
                                     * @date 2024-02-08 12:27
                                     * @author Ulaankhuu
                                     */
                                    if ($valueArray) {
                                        $dataPush = (new Mdgl)->getCashMetaValuesToGridById($valueArray['value'], 1);
                                        if ($dataPush) {
                                            array_push($data, $dataPush);
                                        }                                    
                                    }                                    
                                } else {
                                    $data = $this->cashFlowCreditData;
                                    /**
                                     * Default value der is_debit uzuulelt songoson tul eniig nemj push hiiw
                                     * @date 2024-02-08 12:27
                                     * @author Ulaankhuu
                                     */                                    
                                    if ($valueArray) {
                                        $dataPush = (new Mdgl)->getCashMetaValuesToGridById($valueArray['value'], 0);
                                        if ($dataPush) {
                                            array_push($data, $dataPush);
                                        }
                                    }
                                }

                                if ($value['isRequired'] == '1') {
                                    $valueArray['required'] = 'required';
                                }

                                $value['input'] = Form::select(
                                    array_merge(
                                         array(
                                            'name' => 'accountMeta[0]['.$this->selectedRow['accountid'].'][cashflowsubcategoryid]',
                                            'id' => 'gl_cashFlowId', 
                                            'data-path' => $value['path'], 
                                            'data-col-path' => $value['accountFilter'], 
                                            'class' => 'form-control form-control-sm select2',
                                            'data' => $data,
                                            'op_value' => 'CASH_FLOW_SUB_CATEGORY_ID',
                                            'op_text' => 'CODE|-|NAME'
                                        ), 
                                        $valueArray
                                    )
                                );
                            }

                            $attrRequired = '';
                            if ($value['isRequired'] == '1') {
                                $required = '<span class="required" aria-required="true">*</span>';
                                if (issetParam($dimcheck)) {
                                    $required = '';
                                    $attrRequired = ' data-required="1"';
                                    $value['input'] = str_replace('required="required"', '', $value['input']);
                                }                                
                            } else {
                                $required = '';
                            }
                            
                            $value['input'] = str_replace('data-path="receivableKeyId"', 'data-path="receivableKeyId" readonly="readonly"', $value['input']);
                            $value['input'] = str_replace('data-path="scheduleId"', 'data-path="scheduleId" readonly="readonly"', $value['input']);
                            
                            if ($value['segmentId']) {
                                
                                echo '<tr data-cell-path="'.$value['path'].'" data-segment-row="1">';
                                    echo '<td class="text-right middle" style="width: 28%"><label data-label-path="'.$value['path'].'">'.$required.$value['label'].':</label></td>'. 
                                         '<td class="middle"'.$attrRequired.' style="width: 72%">';
                                    echo $value['input'];
                                    echo '<input type="hidden" name="accountMeta['.$value['rowIndex'].']['.$value['accountId'].']['.$lowerPath.'_segmentCode]" data-segment-code="'.$value['path'].'">';
                                    echo '<input type="hidden" name="accountMeta['.$value['rowIndex'].']['.$value['accountId'].']['.$lowerPath.'_segmentSeparator]" value="'.$value['separatorChar'].'">';
                                    echo '<input type="hidden" name="accountMeta['.$value['rowIndex'].']['.$value['accountId'].']['.$lowerPath.'_segmentReplaceValue]" value="'.$value['replaceValue'].'">';
                                    echo '</td>';
                                    echo '<td></td>';
                                    echo $isEmptyDimensionCheckbox;
                                echo '</tr>';  
                                
                                $segmentSeparator .= $value['separatorChar'].'<span data-st-path="'.$value['path'].'" class="gl-segment-part">'.str_repeat('_', strlen($value['replaceValue'])).'</span>';
                                
                            } else {
                                echo '<tr data-cell-path="'.$value['path'].'">';
                                    echo '<td class="text-right middle" style="width: 28%"><label data-label-path="'.$value['path'].'">'.$required.$value['label'].':</label></td>'. 
                                         '<td class="middle"'.$attrRequired.' style="width: 72%">'.$value['input'].'</td>';
                                    echo $isDuplicateCheckbox;
                                    echo $isEmptyDimensionCheckbox;
                                echo '</tr>';  
                            }
                        }
                        
                        if ($segmentSeparator) {
                            echo '<tr>
                                    <td class="text-right pt5">Данс/dimension:</td>
                                    <td class="text-left pt5"><span style="font-weight: bold; font-size: 14px;">'.$this->selectedRow['accountcode'].'</span>'.$segmentSeparator.'</td>
                                    <td></td>
                                </tr>';  
                        }
                    }
                    
                    if (isset($this->accountFullScripts)) {
                        $isFullExpression = true;
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var $row_<?php echo $this->uniqId; ?> = $('div#gl_meta_row_<?php echo $this->uniqId; ?>');

    Core.initNumberInput($row_<?php echo $this->uniqId; ?>);
    Core.initDateInput($row_<?php echo $this->uniqId; ?>);
    Core.initLongInput($row_<?php echo $this->uniqId; ?>);
    Core.initSelect2WidthAutoFalse($row_<?php echo $this->uniqId; ?>);
    Core.initUniform($row_<?php echo $this->uniqId; ?>);
    Core.initRegexMaskInput($row_<?php echo $this->uniqId; ?>);
    Core.initAccountCodeMask($row_<?php echo $this->uniqId; ?>);
    Core.initStoreKeeperKeyCodeMask($row_<?php echo $this->uniqId; ?>);
        
    $(function() {
        
        $row_<?php echo $this->uniqId; ?>.on('change', 'select.linked-combo', function() {
            var $this = $(this), 
                _outParam = $this.attr('data-out-param'), 
                _outParamSplit = _outParam.split('|');
            for (var i = 0; i < _outParamSplit.length; i++) {
                var selfParam = _outParamSplit[i], 
                    $cellSelect = $row_<?php echo $this->uniqId; ?>.find("[data-path='" + selfParam + "']");
                if ($cellSelect.length) {
                    var _inParam = $cellSelect.attr('data-in-param'), 
                        _inParamSplit = _inParam.split('|'), 
                        _inParams = '';
                    for (var j = 0; j < _inParamSplit.length; j++) {
                        var $lastCombo = $row_<?php echo $this->uniqId; ?>.find("[data-path='" + _inParamSplit[j] + "']");
                        if ($lastCombo.length && $lastCombo.val() !== '') {
                            _inParams += _inParamSplit[j] + '=' + encodeURIComponent($lastCombo.val()) + '&';
                        }
                    }
                }
                if (_inParams !== '') {
                    $.ajax({
                        type: 'post',
                        url: 'mdobject/bpLinkedCombo',
                        data: {inputMetaDataId: '<?php echo $this->inputMetaDataId; ?>', selfParam: selfParam, inputParams: _inParams},
                        dataType: 'json',
                        beforeSend: function() {
                            Core.blockUI({
                                animate: true
                            });
                        },
                        success: function(dataStr) {
                            
                            $cellSelect.select2('val', '');
                            $cellSelect.select2('enable');
                            $("option:gt(0)", $cellSelect).remove();
                            var comboData = dataStr[selfParam];
                            
                            $.each(comboData, function() {
                                $cellSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME));
                            });
                            
                            Core.initSelect2($cellSelect);
                            Core.unblockUI();
                        },
                        error: function() {
                            alert("Error");
                        }
                    });
                } else {
                    $cellSelect.select2('val', '');
                    $cellSelect.select2('disable');
                    $("option:gt(0)", $cellSelect).remove();
                    Core.initSelect2($cellSelect);
                }
            }
        });
        $row_<?php echo $this->uniqId; ?>.on('change', 'input.linked-combo', function() {
            var $this = $(this), 
                _outParam = $this.attr('data-out-param'), 
                _outParamSplit = _outParam.split('|');
                
            for (var i = 0; i < _outParamSplit.length; i++) {
                var selfParam = _outParamSplit[i], 
                    $cellSelect = $row_<?php echo $this->uniqId; ?>.find("[data-path='" + selfParam + "']");
                    
                if ($cellSelect.length) {
                    var _inParam = $cellSelect.attr('data-in-param'), 
                        _inParamSplit = _inParam.split('|'), 
                        _inParams = '';
                    for (var j = 0; j < _inParamSplit.length; j++) {
                        var $lastCombo = $row_<?php echo $this->uniqId; ?>.find("[data-path='" + _inParamSplit[j] + "']");
                        if ($lastCombo.length && $lastCombo.val() !== '') {
                            _inParams += _inParamSplit[j] + '=' + encodeURIComponent($lastCombo.val()) + '&';
                        }
                    }
                }

                if (_inParams !== '') {
                    
                    $.ajax({
                        type: 'post',
                        url: 'mdobject/bpLinkedCombo',
                        data: {inputMetaDataId: '<?php echo $this->inputMetaDataId; ?>', selfParam: selfParam, inputParams: _inParams},
                        dataType: 'json',
                        beforeSend: function() {
                            Core.blockUI({
                                animate: true
                            });
                        },
                        success: function(dataStr) {
                            
                            $cellSelect.select2('val', '');
                            $cellSelect.select2('enable');
                            $("option:gt(0)", $cellSelect).remove();
                            var comboData = dataStr[selfParam];
                            
                            $.each(comboData, function() {
                                $cellSelect.append($("<option />").val(this.META_VALUE_ID).text(this.META_VALUE_NAME));
                            });
                            
                            Core.initSelect2($cellSelect);
                            Core.unblockUI();
                        },
                        error: function() {
                            alert("Error");
                        }
                    });
                } else {
                    $cellSelect.select2('val', '');
                    $cellSelect.select2('disable');
                    $("option:gt(0)", $cellSelect).remove();
                    Core.initSelect2($cellSelect);
                }
            }
        });
        
        $row_<?php echo $this->uniqId; ?>.on('change', 'input.popupInit', function(){
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
                
                $row_<?php echo $this->uniqId; ?>.find('span[data-st-path="'+segmentPath+'"]').text(segCode);
            }
            
            if ($nextRow.length) {
                $nextRow.find('input:visible:first').focus().select();
            } else {
                $parentCell.closest('.ui-dialog').find('button.bp-btn-save').focus();
            }
        });
        
        $row_<?php echo $this->uniqId; ?>.on('change', 'select.select2', function(){
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
                
                $row_<?php echo $this->uniqId; ?>.find('span[data-st-path="'+segmentPath+'"]').text(segCode);
            }
            
            if ($nextRow.length) {
                $nextRow.find('input:visible:first').focus().select();
            } else {
                $parentCell.closest('.ui-dialog').find('button.bp-btn-save').focus();
            }
        });
        
        $row_<?php echo $this->uniqId; ?>.on('change', 'input.stringInit', function(){
            var $this = $(this),  
                $parentCell = $this.closest('td'), 
                $segmentInput = $parentCell.find('input[data-segment-code]'), 
                segmentPath = $segmentInput.attr('data-segment-code'), 
                $parentRow = $this.closest('tr'), 
                segCode = '', 
                $nextRow = $parentRow.next('tr[data-cell-path]:visible:eq(0)');
            
            if ($segmentInput.length) { 
                var selectedValue = $this.val();
                if (selectedValue != '') { 
                    segCode = selectedValue;
                    $segmentInput.val(selectedValue);
                } else {
                    segCode = '__';
                    $segmentInput.val('');
                }
                
                $row_<?php echo $this->uniqId; ?>.find('span[data-st-path="'+segmentPath+'"]').text(segCode);
            }
            
            if ($nextRow.length) {
                $nextRow.find('input:visible:first').focus().select();
            } 
        });
        
        <?php
        if ($segmentSeparator) {
        ?>
        
        $row_<?php echo $this->uniqId; ?>.find('tr[data-segment-row="1"]').each(function(){
            var $segmentRow = $(this), segmentPath = $segmentRow.attr('data-cell-path');
            
            if ($segmentRow.find('input.popupInit').length) {
                
                var segId = $segmentRow.find('input.popupInit').val();
                
                if (segId) {
                    var segCode = $segmentRow.find('input.lookup-code-autocomplete').val(), 
                        segName = $segmentRow.find('input.lookup-name-autocomplete').val(), 
                        $segmentInput = $segmentRow.find('input[data-segment-code]');

                    $segmentInput.val(segCode+'|'+segName);
                    $row_<?php echo $this->uniqId; ?>.find('span[data-st-path="'+segmentPath+'"]').text(segCode);
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
                    $row_<?php echo $this->uniqId; ?>.find('span[data-st-path="'+segmentPath+'"]').text(segCode);
                }
                
            } else if ($segmentRow.find('input.stringInit').length) {
                
                var segId = $segmentRow.find('input.stringInit').val();
                
                if (segId) {
                    var $segmentInput = $segmentRow.find('input[data-segment-code]');
                    $segmentInput.val(segId);
                    $row_<?php echo $this->uniqId; ?>.find('span[data-st-path="'+segmentPath+'"]').text(segId);
                }
            }
            
        });
        
        <?php
        }
        if (isset($isFullExpression)) {
        ?>
        var $jthis = 'open';
        
        $row_<?php echo $this->uniqId; ?>.on('keyup paste cut', 'input.bigdecimalInit', function(e){
            var code = e.keyCode || e.which;
            if (code == 9 || code == 27 || code == 37 || code == 38 || code == 39 || code == 40) return false;
            var $this = $(this);
            $this.next('input[type=hidden]').val($this.val().replace(/[,]/g, ''));
        });
        $row_<?php echo $this->uniqId; ?>.on('keydown', 'input.bigdecimalInit', function(e){
            var code = e.keyCode || e.which;
            if (code == 9 || code == 13 || code == 38 || code == 40) {
                var $this = $(this), $thisNext = $this.next('input[type=hidden]'), $thisVal = $this.val();
                if ($thisVal !== $thisNext.val()) {
                    $thisNext.val($thisVal.replace(/[,]/g, ''));
                }
            }
        });

        <?php
        echo $this->accountFullScripts;
        }
        ?>
    });
</script>