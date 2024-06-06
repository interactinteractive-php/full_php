<?php
$params = $this->paramList;

if (isset($params['generalledgerbookdtls']) && ($dtlCount = count($params['generalledgerbookdtls'])) > 0) {
    
    $mdgl = new Mdgl();
    
    if ($dtlCount < 500) {
        $isMultiRows = false;
    } else {
        $gl = &getInstance();
        $gl->load->model('mdgl', 'middleware/models/'); 
        $isMultiRows = true;
    }
    
    $j = 0;
    
    $bookTypeId = isset($params['booktypeid']) ? $params['booktypeid'] : '';
    
    $row = '';
    $currencyDropDown = Form::select(array('class' => 'form-control form-control-sm no-padding gl-row-currency', 'data' => $this->currencyList, 'text'=>'---', 'op_value' => 'CURRENCY_ID', 'op_text' => 'CURRENCY_CODE', 'style'=>'width:50px;max-width:50px;'));
    
    $rateAttr = array();
    $glIncomeTaxDeduction = Config::getFromCache('FIN_INCOMETAX_DEDUCTION');
    $gisUseAccountType = Config::getFromCache('ISUSEACCOUNTTYPEUGLUPDATE');
    $isGLDescrEnglish = Config::getFromCache('isGLDescrEnglish');
    $isGLRefNumber = Config::getFromCache('ISSHOWGLDTLREFNUMBER');
    
    if (isset($this->isGlRateDisabled)) {
        $rateAttr = array('readonly' => 'readonly');
    }
    
    foreach ($params['generalledgerbookdtls'] as $glRow) {
        
        if (!isset($glRow['isusedetail'])) {
            $glRow['isusedetail'] = '0';
        }
        
        if ($glRow['accountid'] == '') {
            $glRow['isusedetail'] = '0';
        }

        if ($gisUseAccountType == '1') {
            unset($glRow['accountfilter']);
        }
        
        $isUseDetail = (isset($glRow['isusedetail']) ? (($glRow['isusedetail'] == 'true' || $glRow['isusedetail'] == '1') ? 1 : 0) : '');
        $currencyCode = (isset($glRow['currencyname']) ? $glRow['currencyname'] : '');
        
        $attrArray = $accountAttrArray = $btnAttrDisabled = $crmBtnAttrDisabled = $crmAttr = $baseAmountAttr = array();

        $rowTrash = '<div class="btn btn-xs red gl-row-remove" title="Устгах" onclick="removeGlDtl_'.$this->uniqId.'(this);"><i class="far fa-trash"></i></div>';
        
        $invoiceIds = '';
        
        if (isset($glRow['generalledgermaps'])) {
            
            foreach ($glRow['generalledgermaps'] as $invoice) {
                $invoiceIds .= $invoice['invoiceid'] . ',';
            }

            $invoiceIds = rtrim($invoiceIds, ',');
            $glRow['objectid'] = isset($invoice['objectid']) ? $invoice['objectid'] : null;

            if ($isUseDetail != 1 && substr($invoiceIds, 0, 3) == 'vid') {
                $attrArray = array(
                    'readonly' => 'readonly'
                );          
                $crmBtnAttrDisabled = array(
                    'disabled' => 'disabled'
                );
                $crmAttr = array(
                    'readonly' => 'readonly'
                );
            }
        }
        
        if (isset($glRow['objectid']) == false) {
            $glRow['objectid'] = null;
        }
        
        $islock = 0;

        if (isset($glRow['islock']) && ($glRow['islock'] == 'true' || $glRow['islock'] == '1') && $glRow['objectid'] !== '20006' && $glRow['objectid'] !== '20007') {
            
            $attrArray = array(
                'readonly' => 'readonly'
            );
            $accountAttrArray = array(
                'readonly' => 'readonly'
            );
            $btnAttrDisabled = array(
                'disabled' => 'disabled'
            );
            $crmBtnAttrDisabled = array(
                'disabled' => 'disabled'
            );
            $crmAttr = array(
                'readonly' => 'readonly'
            );
            
            $rowTrash = '';
            $islock = 1;
            
        } elseif ($isUseDetail == 1) {           
            $crmBtnAttrDisabled = array(
                'disabled' => 'disabled'
            );
            $crmAttr = array(
                'readonly' => 'readonly'
            );
            $attrArray = array(
                'readonly' => 'readonly'
            );
        }        

        $defaultInvoiceBooks = '';
        
        if (isset($glRow['invoicebook'])) {
            if ($glRow['objectid'] == '20006' || $glRow['objectid'] == '20007') {
                $defaultInvoiceBooks = htmlentities(json_encode(array_key_exists(0, $glRow['invoicebook']) && count($glRow['invoicebook']) == 1 ? $glRow['invoicebook'][0] : $glRow['invoicebook'], JSON_UNESCAPED_UNICODE));
            } else {
                $defaultInvoiceBooks = htmlentities(json_encode($glRow['invoicebook'], JSON_UNESCAPED_UNICODE));
            }
        }

        if (isset($glRow['customerid']) && $glRow['customerid'] != '') {
            
            if (isset($glRow['customercode'])) {
                $glRow['customercode'] = htmlentities($glRow['customercode'], ENT_QUOTES, 'UTF-8');
                $glRow['customername'] = htmlentities($glRow['customername'], ENT_QUOTES, 'UTF-8');
            } else {
                $glRow['customercode'] = '';
                $glRow['customername'] = '';
            }

        } elseif (isset($glRow['invoicebook'][0]['customerid']) && $glRow['invoicebook'][0]['customerid'] != '') {
            
            $glRow['customerid'] = $glRow['invoicebook'][0]['customerid'];

            $crmRow = $mdgl->getCustomerRow($glRow['customerid']);
            $glRow['customercode'] = htmlentities($crmRow['CUSTOMER_CODE'], ENT_QUOTES, 'UTF-8');
            $glRow['customername'] = htmlentities($crmRow['CUSTOMER_NAME'], ENT_QUOTES, 'UTF-8');
            
        } else {
            $glRow['customercode'] = '';
            $glRow['customername'] = '';
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

        $rowCurrencyDropDown = $currencyCode;

        $lockamount = 0;
        if (isset($glRow['islockamount']) && ($glRow['islockamount'] == 'true' || $glRow['islockamount'] == '1') && $islock != 1 && $glRow['objectid'] !== '20006' && $glRow['objectid'] !== '20007') {
            $attrArray = array('readonly' => 'readonly');
            $lockamount = 1;
            unset($btnAttrDisabled['disabled']);  
            unset($accountAttrArray['readonly']); 
            unset($crmBtnAttrDisabled['disabled']);  
            unset($crmAttr['readonly']);  
        }

        if ($lockamount == 1 || $islock == 1) {
            $rowTrash = '';
        }

        if (($glRow['objectid'] == '20006' || $glRow['objectid'] == '20007' || $glRow['objectid'] == '30004') && $islock != 1) {
            unset($attrArray['readonly']);        
            unset($btnAttrDisabled['disabled']);        
            unset($accountAttrArray['readonly']);        
            unset($crmBtnAttrDisabled['disabled']);  
            unset($crmAttr['readonly']);  
        }
        
        if (isset($this->isEditMode) && $this->isEditMode && ($glRow['objectid'] == '20006' || $glRow['objectid'] == '20007') && $isUseDetail == 1 && !isset($this->glRlPlEditModeInputsEnable)) {
            $attrArray = array(
                'readonly' => 'readonly'
            );
            $crmBtnAttrDisabled = array(
                'disabled' => 'disabled'
            );
            $crmAttr = array(
                'readonly' => 'readonly'
            );
        }
        
        if ($currencyCode == 'MNT') {
            $baseAmountAttr = array(
                'readonly' => 'readonly'
            );
        }
        
        $accountName = isset($glRow['accountname']) ? $glRow['accountname'] : '';
        $customerCode = isset($glRow['customercode']) ? $glRow['customercode'] : '';
        $customerName = isset($glRow['customername']) ? $glRow['customername'] : '';
        
        $customerField = "<div class='input-group double-between-input'>" .
                            Form::hidden(array('name' => 'gl_customerId[]', 'value'=>$glRow['customerid'])) .
                            Form::text(array_merge(array('name' => 'gl_customerCode[]', 'id' => 'gl_customerCode', 'class' => 'form-control form-control-sm text-center', 'value'=>$customerCode, 'title'=>$customerCode, 'placeholder'=>$this->lang->line('code_search'), 'style'=>'width:80px;max-width:80px;'), $crmAttr)) .
                            "<span class='input-group-btn'>" .
                            Form::button(array_merge(array('class' => 'btn default btn-bordered form-control-sm mr0', 'onclick'=>'dataViewCustomSelectableGrid(\''.Mdgl::$customerListDataViewCode.'\', \'single\', \'customerSelectabledGrid\', \'\', this);', 'value' => '<i class="fa fa-search"></i>'), $attrArray, $crmBtnAttrDisabled)) .
                            "</span>" .
                            "<span class='input-group-btn'>" . 
                            Form::text(array_merge(array('name' => 'gl_customerName[]', 'id' => 'gl_customerName', 'class' => 'form-control form-control-sm text-center', 'value'=>$customerName, 'title'=>$customerName, 'placeholder'=>$this->lang->line('name_search')), $crmAttr)) .
                            "</span>" .
                        "</div>";
        
        $rowIsMetas = $oppMetaAttr = '';
        
        if ($isMultiRows) {
            
            $glRow['rowislock'] = $islock;
            $glRow['usedetail'] = $isUseDetail;
            $glRow['headerBookTypeId'] = $bookTypeId;
            
            $rowCheckRow = $mdgl->checkAccountRowBpMeta($glRow, $j);
            
            if ($rowCheckRow['isProcess']) {
                $rowTrash = "<div class='btn btn-xs blue' id='detailedMeta' title='Дэлгэрэнгүй' onclick='expandGlDtl_".$this->uniqId."(this);'>...</div>" . $rowTrash;

                if ($glRow['objectid'] != '20006' && $glRow['objectid'] != '20007') {
                    $accountAttrArray = array(
                        'readonly' => 'readonly'
                    );
                    $btnAttrDisabled = array(
                        'disabled' => 'disabled'
                    );
                }
            }
            
            if ($rowCheckRow['isMeta']) {
                $rowTrash = "<div class='btn btn-xs purple-plum gl-dtl-meta-btn' title='Үзүүлэлт' onclick='showDtlMeta_".$this->uniqId."(this);'>...</div>" . $rowTrash;
                $rowIsMetas = 1;
            }
            
            $expenseCenterControl = $rowCheckRow['expenseCenterControl'];
            
        } else {
            $expenseCenterControl = '';
            
            if ($isOppMetaAttr = $mdgl->getOppMetaByAccountId($glRow)) {
                $oppMetaAttr = ' data-op-meta=\''.$isOppMetaAttr.'\'';
            } 
        }
        
        $accountCodeField = "<div class='input-group'>" .
            Form::text(array_merge(array('name' => 'gl_accountCode[]', 'id' => 'gl_accountCode', 'class' => 'form-control form-control-sm accountCodeMask text-center', 'value' => (isset($glRow['accountcode']) ? $glRow['accountcode'] : '')), $accountAttrArray)) .
            "<span class='input-group-btn'>" .
            Form::hidden(array('name' => 'gl_accountId[]', 'value' => $glRow['accountid'])) .
            Form::button(array_merge(array('class' => 'btn default btn-bordered form-control-sm mr0', 'value' => '<i class="fa fa-search"></i>', 'onclick' => "dataViewCustomSelectableGrid('fin_account_list', 'single', 'accountSelectabledGrid_".$this->uniqId."', '', this, '" . (isset($glRow['accountfilter']) ? htmlentities($glRow['accountfilter'], ENT_QUOTES, 'UTF-8') : '') . "');"), $btnAttrDisabled)) .
            "</span>" .
        "</div>";
        
        $accountNameField = Form::text(array_merge(array('name' => 'gl_accountName[]', 'id' => 'gl_accountName', 'class' => 'form-control form-control-sm readonly-white-bg', 'readonly' => 'readonly', 'value' => $accountName, 'title' => $accountName), $accountAttrArray));        
        
        $row .= "<tr data-sub-id='".$glRow['subid']."' data-row-index='$j'$oppMetaAttr>";
        $row .= "<td class='stretchInput middle text-center'>" . Form::text(array_merge(array('name' => 'gl_subid[]', 'id' => 'gl_subid', 'class' => 'form-control readonly-white-bg', 'value' => $glRow['subid'], 'style' => "text-align:center;"), $attrArray));
        $row .= Form::hidden(array('name' => 'gl_accounttypeId[]', 'value' => (isset($glRow['accounttypeid']) ? $glRow['accounttypeid'] : '')));
        $row .= Form::hidden(array('name' => 'gl_main_accounttypeid[]', 'value' => (isset($glRow['accounttypeid']) ? $glRow['accounttypeid'] : '')));
        $row .= Form::hidden(array('name' => 'gl_objectId[]', 'value' => $glRow['objectid']));
        $row .= Form::hidden(array('name' => 'gl_invoiceBookId[]', 'value' => $invoiceIds));
        $row .= Form::hidden(array('name' => 'gl_description[]', 'value' => $glRow['description']));
        $row .= Form::hidden(array('name' => 'gl_isdebit[]', 'value' => (isset($glRow['isdebit']) ? $glRow['isdebit'] : '')));
        $row .= Form::hidden(array('name' => 'gl_accounttypeCode[]', 'value' => (isset($glRow['accounttypecode']) ? $glRow['accounttypecode'] : '')));
        $row .= Form::hidden(array('name' => 'gl_useDetailBook[]', 'value' => $isUseDetail));
        $row .= Form::hidden(array('name' => 'invoiceBookValue[]', 'value' => $invoiceIds));
        $row .= Form::hidden(array('name' => 'gl_dtlId[]', 'value' => $dtlId));
        $row .= Form::hidden(array('name' => 'gl_accountFilter[]', 'value' => (isset($glRow['accountfilter']) ? $glRow['accountfilter'] : '')));
        $row .= Form::hidden(array('name' => 'gl_cashflowsubcategoryid[]', 'value' => ''));
        $row .= Form::hidden(array('name' => 'gl_accountFilterConfig[]', 'value' => ''));
        $row .= Form::hidden(array('name' => 'gl_accountFilterConfigIsDimension[]', 'value' => ''));
        $row .= Form::hidden(array('name' => 'defaultInvoiceBook[]', 'value' => $defaultInvoiceBooks));
        $row .= Form::hidden(array('name' => 'gl_rate_currency[]', 'value' => $currencyCode));
        $row .= Form::hidden(array('name' => 'gl_isEdited[]', 'value' => '0'));
        $row .= Form::hidden(array('name' => 'gl_amountLock[]', 'value' => $lockamount));
        $row .= Form::hidden(array('name' => 'gl_rowislock[]', 'value' => $islock));
        $row .= Form::hidden(array('name' => 'gl_processId[]', 'value' => (isset($glRow['processid']) ? $glRow['processid'] : '')));
        $row .= Form::hidden(array('name' => 'gl_ismetas[]', 'value' => $rowIsMetas));
        $row .= Form::hidden(array('name' => 'gl_keyId[]', 'value' => (isset($glRow['keyid']) ? $glRow['keyid'] : '')));
        $row .= Form::hidden(array('name' => 'gl_secondaryrate[]', 'value' => (isset($glRow['secondaryrate']) ? $glRow['secondaryrate'] : '')));
        $row .= Form::hidden(array('name' => 'gl_secondarycurrencyname[]', 'value' => (isset($glRow['secondarycurrencyname']) ? $glRow['secondarycurrencyname'] : '')));
        $row .= Form::hidden(array('name' => 'gl_secondarycurrencyid[]', 'value' => (isset($glRow['secondarycurrencyid']) ? $glRow['secondarycurrencyid'] : '')));
        
        $detailValues = array_diff_key($glRow, Mdgl::$glRowStaticKeys);
        
        $row .= Form::hidden(array('name' => 'gl_metas[]', 'value' => htmlentities(json_encode($detailValues, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8')));
        $row .= Form::hidden(array('name' => 'gl_isGetLoad[]', 'value' => '1'));
        
        if (isset($glRow['srcinvoicebook'])) {
            $row .= Form::hidden(array('id' => 'srcInvoiceBook', 'value' => htmlentities(json_encode($glRow['srcinvoicebook'], JSON_UNESCAPED_UNICODE))));
        }
        
        $glRow['debitamountbase']  = Number::bigNumberFormat($glRow['debitamountbase'], $this->amountScale);
        $glRow['debitamount']      = Number::bigNumberFormat($glRow['debitamount'], $this->amountScale);
        $glRow['creditamountbase'] = Number::bigNumberFormat($glRow['creditamountbase'], $this->amountScale);
        $glRow['creditamount']     = Number::bigNumberFormat($glRow['creditamount'], $this->amountScale);
        
        $row .= "</td>";
        $row .= "<td class='stretchInput middle text-center'>" . $accountCodeField . "</td>";
        $row .= "<td class='stretchInput middle text-center'>" . $accountNameField . "</td>";
        $row .= "<td class='stretchInput middle text-center customPartner'>" . $customerField . "</td>";
        $row .= "<td class='stretchInput middle text-center glRowExpenseCenter'>".$expenseCenterControl."</td>";
        $row .= "<td class='stretchInput middle text-center glRowDescr'>" . Form::text(array_merge(array('name' => 'gl_rowdescription[]', 'id' => 'gl_rowdescription', 'class' => 'form-control form-control-sm readonly-white-bg', 'value' => $glRow['description'], 'title' => $glRow['description']), $attrArray)) . "</td>";
        if ($isGLDescrEnglish) {
            $row .= "<td class='stretchInput middle text-center glRowDescr2'>" . Form::text(array_merge(array('name' => 'gl_rowdescription2[]', 'id' => 'gl_rowdescription2', 'class' => 'form-control form-control-sm readonly-white-bg', 'value' => issetParam($glRow['description2']), 'title' => issetParam($glRow['description2'])), $attrArray)) . "</td>";            
        }
        if ($isGLRefNumber) {
            $row .= "<td class='stretchInput middle text-center glRowRefNumber'>" . Form::text(array_merge(array('name' => 'gl_rowrefnumber[]', 'id' => 'gl_rowrefnumber', 'class' => 'form-control form-control-sm readonly-white-bg', 'value' => issetParam($glRow['refnumber']), 'title' => issetParam($glRow['refnumber'])), $attrArray)) . "</td>";            
        }
        $row .= "<td class='stretchInput middle text-center glRowCurrency'>".$rowCurrencyDropDown."</td>";
        $row .= "<td data-usebase='usebase' class='stretchInput middle text-center glRowRate'>" . Form::text(array_merge(array('name' => 'gl_rate[]', 'class' => 'form-control form-control-sm bigdecimalInit readonly-white-bg', 'value' => $glRow['rate']), array_merge($attrArray, $rateAttr, $baseAmountAttr))) . "</td>";
        $row .= "<td data-usebase='usebase' class='stretchInput middle text-center'>" . Form::text(array_merge(array('data-input-name' => 'debitAmountBase', 'id' => 'gl_debitAmountBase', 'class' => 'form-control form-control-sm bigdecimalInit readonly-white-bg', 'data-v-min' => 0, 'value' => $glRow['debitamountbase']), $attrArray, $baseAmountAttr)) .Form::hidden(array('name' => 'gl_debitAmountBase[]', 'id' => 'gl_debitamountBase', 'value'=>$glRow['debitamountbase'])). "</td>";
        $row .= "<td class='stretchInput middle text-center'>" . Form::text(array_merge(array('data-input-name' => 'debitAmount', 'id' => 'gl_debitAmount', 'class' => 'form-control form-control-sm bigdecimalInit readonly-white-bg', 'data-v-min' => 0, 'value' => $glRow['debitamount']), $attrArray)) .Form::hidden(array('name' => 'gl_debitAmount[]', 'id' => 'gl_debitAmount', 'value'=>$glRow['debitamount'])). "</td>";
        $row .= "<td data-usebase='usebase' class='stretchInput middle text-center'>" . Form::text(array_merge(array('data-input-name' => 'creditAmountBase', 'id' => 'gl_creditAmountBase', 'class' => 'form-control form-control-sm bigdecimalInit readonly-white-bg', 'data-v-min' => 0, 'value' => $glRow['creditamountbase']), $attrArray, $baseAmountAttr)) .Form::hidden(array('name' => 'gl_creditAmountBase[]', 'id' => 'gl_creditAmountBase', 'value'=>$glRow['creditamountbase'])). "</td>";
        $row .= "<td class='stretchInput middle text-center'>" . Form::text(array_merge(array('data-input-name' => 'creditAmount', 'id' => 'gl_creditAmount', 'class' => 'form-control form-control-sm bigdecimalInit readonly-white-bg', 'data-v-min' => 0, 'value' => $glRow['creditamount']), $attrArray)) .Form::hidden(array('name' => 'gl_creditAmount[]', 'id' => 'gl_creditAmount', 'value'=>$glRow['creditamount'])). "</td>";
        $row .= "<td class='middle text-center'><input type='checkbox' class='notuniform gl-vat-deduction' title='НӨАТ салгах эсэх'></td>";
        $row .= "<td class='middle text-center" . ($glIncomeTaxDeduction === '1' ? '' : ' hide') . "'><input type='checkbox' class='notuniform gl-incometax-deduction' title='ХХАОТ салгах эсэх'></td>";
        $row .= "<td class='middle text-right gl-action-column'>" . $rowTrash . "</td>";
        $row .= "</tr>";
        
        $j++;
    }
    
    echo $row;
}