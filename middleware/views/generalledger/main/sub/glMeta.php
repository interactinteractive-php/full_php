<div id="glExpandedWindow">
    <div class="col-md-12">
        <div class="row">
            <div class="form-body xs-form popup-parent-tag" id="gl_meta_row_<?php echo $this->uniqId; ?>">
                <table class="table table-sm table-no-bordered mb0" style="margin-top: -5px; table-layout: fixed !important">  
                    <?php
                    $segmentSeparator = '';
                    if (isset($this->metaRows) && !empty($this->metaRows)) {
                        
                        foreach ($this->metaRows as $value) {
                            
                            $lowerPath = strtolower($value['path']);
                            
                            if ($lowerPath == 'vatattrsubcategoryid') {
                                
                                if (!isset($taxPayableData)) {
                                    $taxPayableData = (new Mdgl())->getTaxMetaValuesToGrid(0, $this->selectedRow['isdebit']);
                                    $taxReceivableData = (new Mdgl())->getTaxMetaValuesToGrid(1, $this->selectedRow['isdebit']);
                                }

                                $taxdata = $valueArray = array();
                                $isVatAttr = false;
                                $accountTypeCode = strtolower($this->selectedRow['accounttypecode']);

                                if ($this->selectedRow['accounttypeid'] == Mdgl::$taxPayable || substr_count($accountTypeCode, 'payable')) {
                                    $taxdata = $taxPayableData;
                                    $isVatAttr = true;
                                    $valueArray = array('value' => defined('CONFIG_GL_PAYABLE_DEFAULT_VALUE') ? CONFIG_GL_PAYABLE_DEFAULT_VALUE : '');
                                } elseif ($this->selectedRow['accounttypeid'] == Mdgl::$taxReceivable || substr_count($accountTypeCode, 'receivable')) {
                                    $taxdata = $taxReceivableData;
                                    $isVatAttr = true;
                                    $valueArray = array('value' => defined('CONFIG_GL_RECEIVABLE_DEFAULT_VALUE') ? CONFIG_GL_RECEIVABLE_DEFAULT_VALUE : '');
                                }
                                
                                if (Config::getFromCache('CONFIG_GL_VAT_META_VALIDATE_IGNORE') && $isVatAttr == false) {
                                    
                                    if ($this->selectedRow['isdebit'] == 1) {
                                        $taxdata = $taxReceivableData;
                                    } else {
                                        $taxdata = $taxPayableData;
                                    }
                                }

                                if (!array_key_exists('vatattrsubcategoryid', $this->detailvalues)) {
                                    $valueArray = array('value' => $this->detailvalues['vatattrsubcategoryid']);
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
                                }

                                if ($this->selectedRow['isdebit'] == 1) {
                                    $data = $this->cashFlowDebitData;
                                } else {
                                    $data = $this->cashFlowCreditData;
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
                                            'class' => 'form-control form-control-sm select2',
                                            'data' => $data,
                                            'op_value' => 'CASH_FLOW_SUB_CATEGORY_ID',
                                            'op_text' => 'CODE|-|NAME'
                                        ), 
                                        $valueArray
                                    )
                                );
                            }

                            if ($value['isRequired'] == '1') {
                                $required = '<span class="required" aria-required="true">*</span>';
                            } else {
                                $required = '';
                            }
                            
                            if ($value['segmentId']) {
                                
                                echo '<tr data-cell-path="'.$value['path'].'" data-segment-row="1">';
                                    echo '<td class="text-right middle" style="width: 28%"><label data-label-path="'.$value['path'].'">'.$required.$value['label'].':</label></td>'. 
                                         '<td class="middle" style="width: 72%">';
                                    echo $value['input'];
                                    echo '<input type="hidden" name="accountMeta['.$value['rowIndex'].']['.$value['accountId'].']['.$lowerPath.'_segmentCode]" data-segment-code="'.$value['path'].'">';
                                    echo '<input type="hidden" name="accountMeta['.$value['rowIndex'].']['.$value['accountId'].']['.$lowerPath.'_segmentSeparator]" value="'.$value['separatorChar'].'">';
                                    echo '<input type="hidden" name="accountMeta['.$value['rowIndex'].']['.$value['accountId'].']['.$lowerPath.'_segmentReplaceValue]" value="'.$value['replaceValue'].'">';
                                    echo '</td>';
                                echo '</tr>';  
                                
                                $segmentSeparator .= $value['separatorChar'].'<span data-st-path="'.$value['path'].'" class="gl-segment-part">'.str_repeat('_', strlen($value['replaceValue'])).'</span>';
                                
                            } else {
                                echo '<tr data-cell-path="'.$value['path'].'">';
                                    echo '<td class="text-right middle" style="width: 28%"><label data-label-path="'.$value['path'].'">'.$required.$value['label'].':</label></td>'. 
                                         '<td class="middle" style="width: 72%">'.$value['input'].'</td>';
                                echo '</tr>';  
                            }
                        }
                        
                        if ($segmentSeparator) {
                            echo '<tr>
                                    <td class="text-right pt5">Данс/dimension:</td>
                                    <td class="text-left pt5"><span style="font-weight: bold; font-size: 14px;">'.$this->selectedRow['accountcode'].'</span>'.$segmentSeparator.'</td>
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
<?php
if (isset($isFullExpression)) {
?>
<script type="text/javascript">
var $row_<?php echo $this->uniqId; ?> = $('div#gl_meta_row_<?php echo $this->uniqId; ?>');

$(function() {
    var $jthis = 'open';

    <?php echo $this->accountFullScripts; ?>
});    
</script>    
<?php
}
?>