<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdgl_Model extends Model {
    
    private static $gfServiceAddress = GF_SERVICE_ADDRESS;
    
    public function __construct() {
        parent::__construct();
    }

    public function getGlEntryModel($id) {   
        
        $param = array(
            'id'            => $id, 
            'isWithInvoice' => 0
        );

        if (Input::isEmpty('glbookRecordType') === false) {
            $param['recordType'] = Input::post('glbookRecordType');
        }

        $result = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'GL_BOK_007', $param);

        return $result;
    }
    
    public function getTemplateModel($param) {
        
        if (Input::isEmpty('glTemplateId') == false) {
            $param['_templateId'] = Input::post('glTemplateId');
        }
        
        $result = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'getGlTemplate', $param);

        if ($result['status'] == 'success') {
            $result = array('status' => $result['status'], 'data' => $result['result']);
        } else {
            $result = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }

        return $result;
    }

    public function getTemplateByEditModeModel() {

        $param = array(
            'invoiceid' => Input::post('id'),
            'objectid' => Input::post('objectid')
        );

        if (Input::postCheck('isFromBook')) {
            $param['isFromBook'] = '1';
        }

        $result = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'GL_BOK_006', $param);

        return $result;
    }

    public function createGlEntryModel() {
            
        $bookDate = Input::postCheck('glbookDate') ? Input::post('glbookDate') : Input::post('hidden_glbookDate');
        $bookTypeId = Input::postCheck('glBookTypeId') ? Input::post('glBookTypeId') : '2';

        $generalLedgers = array();

        if (Input::postCheck('gl_accountId')) {
            
            $generalLedgersData = Input::post('gl_accountId');
            $isCheckInvoice = Config::getFromCache('IS_CHECK_GL_EMPTY_BOOK') == '1' ? true : false;
            $isCheckCustomer = Config::getFromCache('IS_CHECK_GL_CUSTOMER_REQUIRED') == '1' ? true : false;
            $isUseSecondaryRate = Config::getFromCache('ISUSESECONDARYRATE') == '1' ? true : false;
            $secondaryRate = $secondaryCurrencyId = '';
            
            if ($isUseSecondaryRate) {
                $secondaryRate = Number::decimal(Input::post('secondaryRate'));
                $secondaryCurrencyId = Input::post('secondaryCurrencyId');
            }
            
            $emptyInvoiceAccount = $emptyCustomerAccount = null;

            foreach ($generalLedgersData as $k => $accountId) {

                if ($accountId != '') {
                    
                    $accountCode = $_POST['gl_accountCode'][$k];
                    $rowObjectId = Input::param($_POST['gl_objectId'][$k]);
                    $currencyCode = strtolower(Input::param($_POST['gl_rate_currency'][$k]));
                    $customerId = Input::param($_POST['gl_customerId'][$k]);
                    $keyId = isset($_POST['gl_keyId']) ? Input::param($_POST['gl_keyId'][$k]) : '';

                    if ($currencyCode == 'mnt') {
                        $rate = 1;
                        $creditAmount = Number::decimal(Input::param($_POST['gl_creditAmount'][$k]));
                        $creditAmountBase = $creditAmount;
                        $debitAmount = Number::decimal(Input::param($_POST['gl_debitAmount'][$k]));
                        $debitAmountBase = $debitAmount;
                    } else {
                        $rate = Number::decimal(Input::param($_POST['gl_rate'][$k]));
                        $creditAmount = Number::decimal(Input::param($_POST['gl_creditAmount'][$k]));
                        $creditAmountBase = Number::decimal(Input::param($_POST['gl_creditAmountBase'][$k]));
                        $debitAmount = Number::decimal(Input::param($_POST['gl_debitAmount'][$k]));
                        $debitAmountBase = Number::decimal(Input::param($_POST['gl_debitAmountBase'][$k]));
                    }

                    $generalLedgers[$k] = array(
                        'objectId' => ($rowObjectId != '' ? $rowObjectId : '20000'),
                        'subid' => Input::param($_POST['gl_subid'][$k]),
                        'accountId' => $accountId,
                        'description' => Input::param($_POST['gl_rowdescription'][$k]),
                        'description2' => Input::param(issetParam($_POST['gl_rowdescription2'][$k])),
                        'refNumber' => Input::param(issetParam($_POST['gl_rowrefnumber'][$k])),
                        'rate' => $rate,
                        'creditAmount' => $creditAmount, 
                        'creditAmountBase' => $creditAmountBase, 
                        'debitAmount' => $debitAmount, 
                        'debitAmountBase' => $debitAmountBase, 
                        'customerId' => $customerId, 
                        'keyId' => $keyId,
                        'secondaryRate' => $secondaryRate,
                        'secondaryCurrencyId' => $secondaryCurrencyId,
                        'processId' => Input::param($_POST['gl_processId'][$k])
                    );

                    if (isset($_POST['gl_invoiceBookId'][$k])) {

                        $accbooks = Input::param($_POST['gl_invoiceBookId'][$k]);

                        if ($accbooks != '') {

                            $accountBook = array();
                            $books = explode(',', $accbooks);

                            foreach ($books as $key => $value) {
                                $value = trim($value);
                                if (!empty($value)) {
                                    $accountBook[$key] = array(
                                        'invoiceId' => $value,
                                        'objectId' => $rowObjectId 
                                    );
                                }
                            }
                            $generalLedgers[$k]['generalLedgerMaps'] = $accountBook;
                        }
                    }

                    if (isset($_POST['defaultInvoiceBook'][$k]) && $_POST['defaultInvoiceBook'][$k] != '') {
                        $defaultInvoices = json_decode($_POST['defaultInvoiceBook'][$k], true);
                        $generalLedgers[$k]['invoicebook'] = array_key_exists(0, $defaultInvoices) ? $defaultInvoices : array($defaultInvoices); 
                    }
                    
                    if ($isCheckInvoice) {
                        
                        if ($_POST['gl_useDetailBook'][$k] == '1' && empty($_POST['defaultInvoiceBook'][$k])) {
                            $emptyInvoiceAccount .= $accountCode.', ';
                        }
                    }
                    
                    if ($isCheckCustomer && $customerId == '') {
                        $emptyCustomerAccount .= $accountCode.', ';
                    }

                    if (issetParam($_POST['gl_cashflowsubcategoryid'][$k])) {
                        $generalLedgers[$k]['dimensionconfig'] = json_encode(['rows' => ['cashflowsubcategoryid' => 1]]);
                    }

                    if (isset($_POST['gl_metas'][$k]) && $_POST['gl_metas'][$k] != '') { 
                        $glMetas = json_decode(html_entity_decode($_POST['gl_metas'][$k], ENT_QUOTES, 'UTF-8'), true);
                        if (is_array($glMetas)) {
                            $generalLedgers[$k] = array_merge($generalLedgers[$k], $glMetas);
                        }
                    }

                    if (isset($_POST['accountMeta'][$k][$accountId])) {
                        $accountMetaDatas = Input::param($_POST['accountMeta'][$k][$accountId]);
                        $accountSegmentShortCode = $accountSegmentName = '';
                        $dimensionConfig = [];

                        foreach ($accountMetaDatas as $metaKey => $metaValue) {
                            if (strpos($metaKey, '_segmentCode') === false && strpos($metaKey, '_segmentSeparator') === false 
                                && strpos($metaKey, '_segmentReplaceValue') === false && strpos($metaKey, '_accEmptyDimension') === false) {

                                $generalLedgers[$k][$metaKey] = $metaValue;

                                if (array_key_exists($metaKey.'_segmentCode', $accountMetaDatas)) {

                                    $segCode = $accountMetaDatas[$metaKey.'_segmentCode'];
                                    $segSeparator = $accountMetaDatas[$metaKey.'_segmentSeparator'];

                                    if ($segCode) {
                                        $metaValueExp = explode('|', $accountMetaDatas[$metaKey.'_segmentCode']);
                                        $segmentCode = $metaValueExp[0];
                                        $segmentName = $metaValueExp[1];
                                        $generalLedgers[$k][$metaKey.'_segmentCode'] = $segmentCode;
                                        $generalLedgers[$k][$metaKey.'_segmentName'] = $segmentName;
                                        $accountSegmentName .= $segSeparator.$segmentName;
                                    } else {
                                        $segmentCode = $accountMetaDatas[$metaKey.'_segmentReplaceValue'];
                                        $segmentName = $segmentCode;
                                        $generalLedgers[$k][$metaKey.'_segmentCode'] = '';
                                        $generalLedgers[$k][$metaKey.'_segmentName'] = '';
                                    }

                                    $accountSegmentShortCode .= $segSeparator.$segmentCode;
                                }
                                
                                if (array_key_exists($metaKey.'_accEmptyDimension', $accountMetaDatas)) {
                                    $dimensionConfig[$metaKey] = 1;
                                    $generalLedgers[$k][$metaKey]= '';
                                    $generalLedgers[$k][$metaKey.'_segmentCode'] = '';
                                    $generalLedgers[$k][$metaKey.'_segmentName'] = '';                                    
                                }
                            }
                        }
                        
                        if ($dimensionConfig) {
                            $generalLedgers[$k]['dimensionconfig'] = json_encode(['rows' => $dimensionConfig]);
                        }

                        /*if ($accountSegmentShortCode) {
                            $generalLedgers[$k]['accountsegmentshortcode'] = $accountSegmentShortCode;
                        }

                        if ($accountSegmentName) {
                            $generalLedgers[$k]['accountsegmentname'] = $accountSegmentName;
                        }*/
                    }
                }
            }
            
            if ($emptyInvoiceAccount) {
                return array('status' => 'warning', 'message' => '('.rtrim($emptyInvoiceAccount, ', ').') дансан дээр баримт үүсгэх товчийг дарж үүсгэнэ үү');
            }
            
            if ($emptyCustomerAccount) {
                return array('status' => 'warning', 'message' => '('.rtrim($emptyCustomerAccount, ', ').') дансан дээр харилцагч сонгоно уу');
            }
        }

        $generalLedgerParams = array(
           'bookTypeId' => $bookTypeId,
           'bookDate' => $bookDate,
           'generalLedgerBookParams' => array(
                'bookTypeId' => $bookTypeId,
                'bookDate' => $bookDate,
                'bookNumber' => Input::postCheck('glbookNumber') ? Input::post('glbookNumber') : Input::post('hidden_glbookNumber'), 
                'objectId' => (Input::isEmpty('hidden_globject') == false) ? Input::post('hidden_globject') : '20000',
                'description' => Input::postCheck('gldescription') ? Input::post('gldescription') : (Input::post('cashgldescription') ? Input::post('cashgldescription') : Input::post('hidden_gldescription')),
                'description2' => Input::post('gldescription2'),
                'pfTranslationValue' => Input::post('gldescription_translation') ? '{"value":{"DESCRIPTION":'.html_entity_decode(Input::post('gldescription_translation'), ENT_QUOTES).'}}' : '',
                'generalLedgerBookDtls' => $generalLedgers
            )
        );

        if (Input::isEmpty('glbookId') == false) {
            $generalLedgerParams['generalLedgerBookParams']['id'] = Input::post('glbookId');
        }
        if (Input::isEmpty('glrelatedBookId') == false) {
            $generalLedgerParams['generalLedgerBookParams']['relatedbookid'] = Input::post('glrelatedBookId');
        }
        if (Input::isEmpty('hidden_glcreatedUserId') == false) {
            $generalLedgerParams['generalLedgerBookParams']['createdUserId'] = Input::post('hidden_glcreatedUserId');
        }
        if (Input::isEmpty('hidden_importId') == false) {
            $generalLedgerParams['generalLedgerBookParams']['importId'] = Input::post('hidden_importId');
        }
        
        $processCode = 'GL_BOK_008';
        
        if (Input::post('isFromBudget') == '1') {
            $processCode = 'BUDGET_GL_BOK_008';
        }

        if (Input::post('isglcopy') == '1') {
            unset($generalLedgerParams['generalLedgerBookParams']['id']);
            unset($generalLedgerParams['generalLedgerBookParams']['createdUserId']);
        }
        
        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, $processCode, $generalLedgerParams);

        if ($result['status'] == 'success') {
            
            $bookId = issetParam($result['result']['id']);
            $response = array('status' => 'success', 'message' => $this->lang->line('msg_save_success'), 'id' => $bookId);

            if (Input::postCheck('isSavePrint') && isset($result['result']['generalledgerbookdtls'])) {

                $generalLedgerBookDtls = $result['result']['generalledgerbookdtls'];

                $response = array_merge($response, self::printTemplateRowGL($bookId, $generalLedgerBookDtls));
                $response['dvId'] = Mdgl::$glMainDvId;
            }

            if ($bookId && isset($_FILES['bp_file'])) {
                
                $glStructureId = Config::getFromCache('GENERAL_LEDGER_BOOK_STRUCTURE_ID');
                
                if ($glStructureId) {
                    (new Mdwebservice())->saveBpAddOn($glStructureId, $bookId);
                }
            }

        } else {
            $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }

        return $response;
    }
        
    public function updateGlEntryModel() {
            
        $bookDate = Input::postCheck('glbookDate') ? Input::post('glbookDate') : Input::post('hidden_glbookDate');
        $bookTypeId = Input::postCheck('glBookTypeId') ? Input::post('glBookTypeId') : '2';
        $objectId = (Input::isEmpty('hidden_globject') == false) ? Input::post('hidden_globject') : '20000';

        $generalLedgers = array();

        if (Input::postCheck('gl_accountId')) {

            $generalLedgersData = Input::post('gl_accountId');
            $isCheckInvoice = Config::getFromCache('IS_CHECK_GL_EMPTY_BOOK') == '1' ? true : false;
            $isCheckCustomer = Config::getFromCache('IS_CHECK_GL_CUSTOMER_REQUIRED') == '1' ? true : false;
            $isUseSecondaryRate = Config::getFromCache('ISUSESECONDARYRATE') == '1' ? true : false;
            $emptyInvoiceAccount = $emptyCustomerAccount = null;
            $secondaryRate = $secondaryCurrencyId = '';
            
            if ($isUseSecondaryRate) {
                $secondaryRate = Number::decimal(Input::post('secondaryRate'));
                $secondaryCurrencyId = Input::post('secondaryCurrencyId');
            }

            foreach ($generalLedgersData as $k => $accountId) {

                if ($accountId != '') {
                    
                    $accountCode = $_POST['gl_accountCode'][$k];
                    $rowObjectId = Input::param($_POST['gl_objectId'][$k]);
                    $currencyCode = strtolower(Input::param($_POST['gl_rate_currency'][$k]));
                    $customerId = Input::param($_POST['gl_customerId'][$k]);
                    $keyId = isset($_POST['gl_keyId']) ? Input::param($_POST['gl_keyId'][$k]) : '';

                    if ($currencyCode == 'mnt') {
                        
                        $rate = Number::decimal(Input::param($_POST['gl_rate'][$k]));
                        $creditAmount = Number::decimal(Input::param($_POST['gl_creditAmount'][$k]));
                        $debitAmount = Number::decimal(Input::param($_POST['gl_debitAmount'][$k]));
                        
                        if ($rate == 1) {
                            $creditAmountBase = 0;
                            $debitAmountBase = 0;
                        } else {
                            $creditAmountBase = Number::decimal(Input::param($_POST['gl_creditAmountBase'][$k]));
                            $debitAmountBase = Number::decimal(Input::param($_POST['gl_debitAmountBase'][$k]));
                        }
                        
                    } else {
                        $rate = Number::decimal(Input::param($_POST['gl_rate'][$k]));
                        $creditAmount = Number::decimal(Input::param($_POST['gl_creditAmount'][$k]));
                        $creditAmountBase = Number::decimal(Input::param($_POST['gl_creditAmountBase'][$k]));
                        $debitAmount = Number::decimal(Input::param($_POST['gl_debitAmount'][$k]));
                        $debitAmountBase = Number::decimal(Input::param($_POST['gl_debitAmountBase'][$k]));
                    }

                    $generalLedgers[$k] = array(
                        'id' => Input::param($_POST['gl_dtlId'][$k]),
                        'objectId' => ($rowObjectId != '' ? $rowObjectId : '20000'),
                        'subid' => Input::param($_POST['gl_subid'][$k]),
                        'accountId' => $accountId,
                        'description' => Input::param($_POST['gl_rowdescription'][$k]),
                        'description2' => Input::param(issetParam($_POST['gl_rowdescription2'][$k])),
                        'refNumber' => Input::param(issetParam($_POST['gl_rowrefnumber'][$k])),
                        'rate' => $rate, 
                        'creditAmount' => $creditAmount,
                        'creditAmountBase' => $creditAmountBase,
                        'debitAmount' => $debitAmount, 
                        'debitAmountBase' => $debitAmountBase, 
                        'customerId' => $customerId, 
                        'secondaryRate' => $secondaryRate,
                        'secondaryCurrencyId' => $secondaryCurrencyId,                        
                        'processId' => Input::param($_POST['gl_processId'][$k]), 
                        'islockamount' => Input::param($_POST['gl_amountLock'][$k]), 
                        'islock' => Input::param($_POST['gl_rowislock'][$k]), 
                        'keyId' => $keyId
                    );

                    if (isset($_POST['gl_metas'][$k]) && $_POST['gl_metas'][$k] != '') { 
                        $glMetas = json_decode(html_entity_decode($_POST['gl_metas'][$k], ENT_QUOTES, 'UTF-8'), true);
                        if (is_array($glMetas)) {
                            $generalLedgers[$k] = array_merge($generalLedgers[$k], $glMetas);
                        }
                    }        

                    if (isset($_POST['accountMeta'][$k][$accountId])) {
                        $accountMetaDatas = Input::param($_POST['accountMeta'][$k][$accountId]);
                        $accountSegmentShortCode = $accountSegmentName = '';
                        $dimensionConfig = [];
                        unset($generalLedgers[$k]['dimensionconfig']);

                        foreach ($accountMetaDatas as $metaKey => $metaValue) {
                            if (strpos($metaKey, '_segmentCode') === false && strpos($metaKey, '_segmentSeparator') === false && strpos($metaKey, '_segmentReplaceValue') === false && strpos($metaKey, '_accEmptyDimension') === false) {

                                $generalLedgers[$k][$metaKey] = $metaValue;

                                if (array_key_exists($metaKey.'_segmentCode', $accountMetaDatas)) {

                                    $segCode = $accountMetaDatas[$metaKey.'_segmentCode'];
                                    $segSeparator = $accountMetaDatas[$metaKey.'_segmentSeparator'];

                                    if ($segCode) {
                                        $metaValueExp = explode('|', $accountMetaDatas[$metaKey.'_segmentCode']);
                                        $segmentCode = $metaValueExp[0];
                                        $segmentName = $metaValueExp[1];
                                        $generalLedgers[$k][$metaKey.'_segmentCode'] = $segmentCode;
                                        $generalLedgers[$k][$metaKey.'_segmentName'] = $segmentName;
                                        $accountSegmentName .= $segSeparator.$segmentName;
                                    } else {
                                        $segmentCode = $accountMetaDatas[$metaKey.'_segmentReplaceValue'];
                                        $segmentName = $segmentCode;
                                        $generalLedgers[$k][$metaKey.'_segmentCode'] = '';
                                        $generalLedgers[$k][$metaKey.'_segmentName'] = '';
                                    }

                                    $accountSegmentShortCode .= $segSeparator.$segmentCode;
                                }
                                
                                if (array_key_exists($metaKey.'_accEmptyDimension', $accountMetaDatas) && Config::getFromCache('IsAccountFilterFinancialRemoved')) {
                                    $dimensionConfig[$metaKey] = 1;
                                    $generalLedgers[$k][$metaKey]= '';
                                }                                
                            }
                        }
                        
                        if ($dimensionConfig) {
                            $generalLedgers[$k]['dimensionconfig'] = json_encode(['rows' => $dimensionConfig]);
                        }                        

                        if ($accountSegmentShortCode) {
                            $generalLedgers[$k]['accountsegmentshortcode'] = $accountSegmentShortCode;
                        }

                        if ($accountSegmentName) {
                            $generalLedgers[$k]['accountsegmentname'] = $accountSegmentName;
                        }
                    } elseif (Config::getFromCache('IsAccountFilterFinancialRemoved')) {                        
                        $dimConfig = json_decode($generalLedgers[$k]['dimensionconfig'], true);
                        $dimConfig = issetParam($dimConfig['rows']);

                        if (is_array($dimConfig)) {
                            $dimConfigKey = array_keys($dimConfig);
                            foreach ($dimConfigKey as $dimRow) {
                                if (array_key_exists($dimRow, $generalLedgers[$k])) {
                                    $generalLedgers[$k][$dimRow]= '';
                                }                                 
                            }                                 
                        }                              
                    }
                    
                    $isWithBook = false;
                    
                    if (isset($_POST['defaultInvoiceBook'][$k]) && $_POST['defaultInvoiceBook'][$k] != '') {

                        $defaultInvoices = json_decode($_POST['defaultInvoiceBook'][$k], true);
                        $generalLedgers[$k]['invoicebook'] = array_key_exists(0, $defaultInvoices) ? $defaultInvoices : array($defaultInvoices);
                        $isWithBook = true;
                        
                        /*if (($rowObjectId == '20003' || $rowObjectId == '20004') && Input::param($_POST['gl_isEdited'][$k]) == '0') {

                            $generalLedgers[$k]['generalLedgerMaps'] = array(
                                array(
                                    'invoiceId' => $generalLedgers[$k]['invoicebook'][0]['id'],
                                    'objectId' => $rowObjectId
                                )
                            );

                            /*unset($generalLedgers[$k]['invoicebook']);*/
                        //}
                    }
                    
                    if (isset($_POST['gl_invoiceBookId'][$k]) && $isWithBook == false) {

                        $accountBook = array();
                        $accbooks = Input::param($_POST['gl_invoiceBookId'][$k]);

                        if ($accbooks != '') {
                            
                            $books = explode(',', $accbooks);
                            
                            foreach ($books as $key => $value) {
                                $value = trim($value);
                                if (!empty($value)) {
                                    $accountBook[$key] = array(
                                        'invoiceId' => $value,
                                        'objectId' => $rowObjectId
                                    );
                                }
                            }
                            
                            $generalLedgers[$k]['generalLedgerMaps'] = $accountBook;
                        }
                    }
                    
                    if ($isCheckInvoice) {
                        
                        if ($_POST['gl_useDetailBook'][$k] == '1' && empty($_POST['defaultInvoiceBook'][$k])) {
                            $emptyInvoiceAccount .= $accountCode.', ';
                        }
                    }
                    
                    if ($isCheckCustomer && $customerId == '') {
                        $emptyCustomerAccount .= $accountCode.', ';
                    }
                }
            }
            
            if ($emptyInvoiceAccount) {
                return array('status' => 'info', 'message' => '('.rtrim($emptyInvoiceAccount, ', ').') дансан дээр баримт үүсгэх товчийг дарж үүсгэнэ үү');
            }
            
            if ($emptyCustomerAccount) {
                return array('status' => 'warning', 'message' => '('.rtrim($emptyCustomerAccount, ', ').') дансан дээр харилцагч сонгоно уу');
            }
        }

        $generalLedgerParams = array(
            'bookTypeId' => $bookTypeId,
            'objectId' => $objectId, 
            'bookDate' => $bookDate,
            'generalLedgerBookParams' => array(
                'id' => Input::post('glbookId'),
                'relatedbookid' => Input::post('glrelatedBookId'), 
                'importid' => Input::post('glimportId'), 
                'bookTypeId' => $bookTypeId,
                'objectId' => $objectId,
                'bookDate' => $bookDate,
                'bookNumber' => Input::postCheck('glbookNumber') ? Input::post('glbookNumber') : Input::post('hidden_glbookNumber'), 
                'description' => Input::postCheck('gldescription') ? Input::post('gldescription') : Input::post('hidden_gldescription'),
                'description2' => Input::post('gldescription2'),
                'pfTranslationValue' => Input::post('gldescription_translation') ? '{"value":{"DESCRIPTION":'.html_entity_decode(Input::post('gldescription_translation'), ENT_QUOTES).'}}' : '',
                'createdUserId' => Input::post('hidden_glcreatedUserId'),
                'createdDate' => Input::post('hidden_glcreatedDate'), 
                'generalLedgerBookDtls' => $generalLedgers
            )
        );
        
        if (Input::isEmpty('hidden_importId') == false) {
            $generalLedgerParams['generalLedgerBookParams']['importId'] = Input::post('hidden_importId');
        }
        
        $processCode = 'GL_BOK_008';
        
        if (Input::post('isFromBudget') == '1') {
            $processCode = 'BUDGET_GL_BOK_008';
        }

        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, $processCode, $generalLedgerParams);

        if ($result['status'] == 'success') {
            $response = array('status' => 'success', 'message' => Lang::line('msg_save_success'));
        } else {
            $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }

        return $response;
    }

    public function deleteGlEntryModel() {
        $param = array(
            'id' => Input::post('id')
        );
        $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'GL_BOK_005', $param);
        return $result;
    }

    public function deleteGlEntryWithBookModel() {
        $param = array(
            'id' => Input::post('id')
        );
        $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'GL_BOK_005', $param);
        return $result;
    }
    
    public function printTemplateRowGL($id, $generalLedgerBookDtls) {
            
        $response = $mainGlRow = $receivableGlRows = array();

        foreach ($generalLedgerBookDtls as $bookRow) {

            $bookRow['id'] = $id;
            $bookRow['objectcode'] = '';

            if (isset($bookRow['currencyname'])) {

                $currencyCode = strtolower($bookRow['currencyname']);

            } else {

                $_POST['accountId'] = $bookRow['accountid'];

                $accRow = self::getAccountRowByIdModel();
                $currencyCode = Arr::get($accRow, 'CURRENCY_CODE');
            }

            if ($currencyCode != 'mnt') {

                if (isset($bookRow['detailvalues'])) {
                    unset($bookRow['detailvalues']);
                }
                $mainGlRow = $bookRow;
            }

            if (isset($bookRow['objectid']) && $bookRow['objectid'] == '20007' && $bookRow['debitamount'] > 1) {

                if (isset($bookRow['detailvalues'])) {
                    unset($bookRow['detailvalues']);
                }

                $bookRow['objectcode'] = 'INV007';
                $receivableGlRows[] = $bookRow;
            }
        }

        if (count($mainGlRow) == 0) {
            if (isset($bookRow['detailvalues'])) {
                unset($bookRow['detailvalues']);
            }
            $mainGlRow = $bookRow;
        }

        if (count($receivableGlRows) > 0) {
            $response['receivableGlRows'] = $receivableGlRows;
            $mainGlRow = $receivableGlRows[0];
        }

        $response['mainGlRow'] = $mainGlRow;

        return $response;
    }

    public function filterAccountCodeModel($keyVal) {
        
        $sql = "
            SELECT 
                ACC.ACCOUNT_ID AS id, 
                ACC.ACCOUNT_CODE AS accountcode,
                ACC.ACCOUNT_NAME AS accountname,
                ACC.ACCOUNT_TYPE_ID AS accounttypeid,
                TYPE.OBJECT_ID AS objectid,
                TYPE.ACCOUNT_TYPE_CODE AS accounttypecode, 
                ACC.CURRENCY_ID AS currencyid,
                ACC.USE_DETAIL_BOOK AS isusedetailbook
            FROM VW_FIN_ACCOUNT ACC 
                INNER JOIN FIN_ACCOUNT_TYPE TYPE ON TYPE.ID = ACC.ACCOUNT_TYPE_ID 
            WHERE LOWER(ACC.ACCOUNT_CODE) LIKE '$keyVal%' OR LOWER(ACC.ACCOUNT_NAME) LIKE '$keyVal%'";
        
        $this->db->StartTrans(); 
        $this->db->Execute(Ue::createSessionInfo());
           
        $data = $this->db->SelectLimit($sql, 30, -1);
        
        $this->db->CompleteTrans();
         
        if (isset($data->_array)) {
            $data = Arr::changeKeyLower($data->_array);
            return $data;
        }
        return null;
    }

    public function filterDepartmentCodeModel($keyVal) {
        $sql = "
            SELECT DISTINCT EMPLOYEE_COUNT,
                POSITION_COUNT,
                OD.PARENT_ID,
                ODP.DEPARTMENT_NAME AS PARENT_DEPARTMENT_NAME,
                OD.DEPARTMENT_NAME,
                OD.DEPARTMENT_ID,
                OD.DEPARTMENT_CODE,
                EMAIL,
                ADDRESS,
                OD.CREATED_DATE,
                OD.CREATED_USER_ID,
                OD.MODIFIED_DATE,
                OD.MODIFIED_USER_ID,
                OFFICE_PHONE
              FROM ORG_DEPARTMENT OD
              LEFT JOIN ORG_DEPARTMENT_DTL ODD
              ON ODD.DEPARTMENT_ID=OD.DEPARTMENT_ID
              LEFT JOIN
                (SELECT OD.DEPARTMENT_ID,
                  COUNT(HEK.EMPLOYEE_KEY_ID) AS EMPLOYEE_COUNT
                FROM ORG_DEPARTMENT OD
                LEFT JOIN HRM_EMPLOYEE_KEY HEK
                ON HEK.DEPARTMENT_ID=OD.DEPARTMENT_ID
                AND HEK.IS_ACTIVE   =1
                GROUP BY OD.DEPARTMENT_ID
                ) T1 ON T1.DEPARTMENT_ID=OD.DEPARTMENT_ID
              LEFT JOIN
                (SELECT OD.DEPARTMENT_ID,
                  COUNT(HPK.POSITION_KEY_ID) AS POSITION_COUNT
                FROM ORG_DEPARTMENT OD
                LEFT JOIN HRM_POSITION_KEY HPK
                ON HPK.DEPARTMENT_ID=OD.DEPARTMENT_ID
                AND HPK.IS_ACTIVE   =1
                GROUP BY OD.DEPARTMENT_ID
                ) T2 ON T2.DEPARTMENT_ID=OD.DEPARTMENT_ID
              LEFT JOIN ORG_DEPARTMENT ODP
              ON ODP.DEPARTMENT_ID=OD.PARENT_ID
            WHERE LOWER(OD.DEPARTMENT_CODE) LIKE '$keyVal%' OR LOWER(OD.DEPARTMENT_NAME) LIKE '$keyVal%'";

        $data = $this->db->SelectLimit($sql, 30, -1);

        if (isset($data->_array)) {
            $data = Arr::changeKeyLower($data->_array);
            return $data;
        }
        return null;
    }
    
    public function getMetaByAccountTypeModel($selectedRow, $isOpMeta){
        
        try {
            
            if (Config::getFromCache('CONFIG_GL_ACCOUNT_PARENT_ID')) {
                
                /*$join = 'INNER JOIN FIN_ACCOUNT T2 ON (
                        CONF.ACCOUNT_ID = COALESCE(T2.PARENT_ID, T2.ACCOUNT_ID) 
                        OR CONF.ACCOUNT_TYPE_ID = T2.ACCOUNT_TYPE_ID 
                    ) '; */
                
                $join = 'INNER JOIN FIN_ACCOUNT T2 ON ((T2.PARENT_ID IS NOT NULL AND T2.PARENT_ID = CONF.ACCOUNT_ID) OR (T2.PARENT_ID IS NULL AND T2.ACCOUNT_ID = CONF.ACCOUNT_ID) OR T2.ACCOUNT_TYPE_ID = CONF.ACCOUNT_TYPE_ID) '; 
                
                $accountAlias = 'T2';
                
            } else {
                $join = '';
                $accountAlias = 'CONF';
            }
            
            $accountId = $selectedRow['accountid'];
            $accountTypeId = $selectedRow['accounttypeid'];
            $segmentJoin = '';
            
            if (Config::getFromCache('IS_CLOUD') == '1') {
                
                $sessionValues = Session::get(SESSION_PREFIX.'sessionValues');
            
                if ($sessionCompanyDepartmentId = issetParam($sessionValues['sessioncompanydepartmentid'])) {
                    $segmentJoin = " AND SC.COMPANY_DEPARTMENT_ID = $sessionCompanyDepartmentId "; 
                }
            }
            
            $sql = "
                SELECT 
                    0 AS GROUP_PARAM_CONFIG_TOTAL,
                    '' AS GROUP_CONFIG_PARAM_PATH,
                    '' AS GROUP_CONFIG_LOOKUP_PATH, 
                    '' AS GROUP_CONFIG_FIELD_PATH, 
                    '' AS GROUP_CONFIG_GROUP_PATH, 
                    null AS ATTRIBUTE_ID_COLUMN, 
                    null AS ATTRIBUTE_CODE_COLUMN, 
                    null AS ATTRIBUTE_NAME_COLUMN, 
                    MGC.FIELD_PATH AS PARAM_REAL_PATH, 
                    MGC.VISIBLE_CRITERIA,
                    '' AS IS_REFRESH,
                    CONF.DEFAULT_VALUE,
                    MGC.LOOKUP_TYPE,
                    MGC.LABEL_NAME,
                    MGC.CHOOSE_TYPE,
                    MGC.RECORD_TYPE,
                    MGC.VALUE_FIELD,
                    MGC.DISPLAY_FIELD,
                    MGC.IS_SHOW,
                    MGC.PARENT_ID, 
                    MGC.LOOKUP_META_DATA_ID, 
                    MGC.PARAM_NAME AS META_DATA_CODE, 
                    MGC.LABEL_NAME AS META_DATA_NAME, 
                    MGC.DATA_TYPE AS META_TYPE_CODE, 
                    MGC.FILE_EXTENSION, 
                    MGC.FRACTION_RANGE, 
                    MGC.MIN_VALUE, 
                    MGC.MAX_VALUE, 
                    LOWER(MGC.PARAM_NAME) AS LOWER_PARAM_NAME, 
                    REPLACE(MGC.FIELD_PATH, '.', '') AS NODOT_PARAM_REAL_PATH, 
                    MFP.PATTERN_TEXT,
                    MFP.PATTERN_NAME, 
                    MFP.GLOBE_MESSAGE,
                    MFP.IS_MASK, 
                    CONF.IS_REQUIRED, 
                    CONF.LOOKUP_CRITERIA, 
                    CONF.VALUE_CRITERIA, 
                    CONFL.IS_USE_OPP_ACCOUNT, 
                    CONF.ACCOUNT_FILTER, 
                    SC.ID AS SEGMENT_ID, 
                    SC.SEPRATOR_CHAR, 
                    SC.REPLACE_VALUE, 
                    ".$this->db->IfNull('MGC.PLACEHOLDER_NAME', 'MGC.LABEL_NAME')." AS PLACEHOLDER_NAME,
                    COALESCE(CONF.DEBIT_DEFAULT_VALUE, CONF.DEFAULT_VALUE) AS DEBIT_DEFAULT_VALUE,
                    COALESCE(CONF.CREDIT_DEFAULT_VALUE, CONF.DEFAULT_VALUE) AS CREDIT_DEFAULT_VALUE 
                FROM META_GROUP_CONFIG MGC 
                    INNER JOIN FIN_ACCOUNT_GL_CONFIG CONF ON LOWER(CONF.FIELD_PATH) = LOWER(MGC.FIELD_PATH) 
                        $join 
                    LEFT JOIN META_FIELD_PATTERN MFP ON MFP.PATTERN_ID = MGC.PATTERN_ID       
                    LEFT JOIN FIN_ACCOUNT_GL_CONFIG_DTL CONFL ON LOWER(CONFL.FIELD_PATH) = LOWER(CONF.FIELD_PATH) AND ".$this->db->IfNull('CONF.IS_CHOOSE_OPP', '0')." = 0
                    LEFT JOIN FIN_ACCOUNT_SEGMENT_CONFIG SC ON LOWER(SC.FIELD_PATH) = LOWER(CONF.FIELD_PATH) 
                        $segmentJoin 
                WHERE MGC.MAIN_META_DATA_ID = ".Mdgl::$glBookDtlGroupMetaDataId." 
                    AND MGC.PARENT_ID IS NULL 
                    AND MGC.DATA_TYPE <> 'group' 
                    AND (CONF.CONFIG_TYPE IS NULL OR CONF.CONFIG_TYPE <> 2)";
            
            $groupBy = ' GROUP BY 
                    MGC.FIELD_PATH, 
                    MGC.VISIBLE_CRITERIA,
                    CONF.DEFAULT_VALUE,
                    MGC.LOOKUP_TYPE,
                    MGC.LABEL_NAME,
                    MGC.CHOOSE_TYPE,
                    MGC.RECORD_TYPE,
                    MGC.VALUE_FIELD,
                    MGC.DISPLAY_FIELD,
                    MGC.IS_SHOW,
                    MGC.PARENT_ID, 
                    MGC.LOOKUP_META_DATA_ID, 
                    MGC.PARAM_NAME, 
                    MGC.LABEL_NAME, 
                    MGC.DATA_TYPE, 
                    MGC.FILE_EXTENSION, 
                    MGC.FRACTION_RANGE, 
                    MGC.MIN_VALUE, 
                    MGC.MAX_VALUE, 
                    MFP.PATTERN_TEXT,
                    MFP.PATTERN_NAME, 
                    MFP.GLOBE_MESSAGE,
                    MFP.IS_MASK, 
                    CONF.IS_REQUIRED, 
                    CONF.LOOKUP_CRITERIA, 
                    CONF.VALUE_CRITERIA, 
                    CONF.ACCOUNT_FILTER, 
                    CONFL.IS_USE_OPP_ACCOUNT, 
                    MGC.DISPLAY_ORDER, 
                    SC.ID, 
                    CONF.ORDER_NUMBER, 
                    SC.SEPRATOR_CHAR, 
                    SC.REPLACE_VALUE, 
                    MGC.PLACEHOLDER_NAME,
                    CONF.DEBIT_DEFAULT_VALUE,
                    CONF.CREDIT_DEFAULT_VALUE ';
            
            if ($isOpMeta && strtolower($isOpMeta) !== 'cashflowsubcategoryid') {

                $isOpMetaArr = explode('|', $isOpMeta);
                $opAccountId = $isOpMetaArr[0];
                $opCashFlowSubCategoryId = $isOpMetaArr[1];
                
                $result = $this->db->GetAll($sql." AND ((LOWER(MGC.FIELD_PATH) = LOWER('$opCashFlowSubCategoryId') 
                    AND ( 
                            $accountAlias.ACCOUNT_ID = $opAccountId 
                            OR $accountAlias.ACCOUNT_TYPE_ID = (SELECT ACCOUNT_TYPE_ID FROM FIN_ACCOUNT WHERE ACCOUNT_ID = $opAccountId) 
                            OR CONF.CO_A_GROUP_ID IN (SELECT CO_A_GROUP_ID FROM FIN_ACCOUNT_CO_A_GROUP_MAP WHERE ACCOUNT_ID = $opAccountId)     
                        )) 
                        OR CONF.ACCOUNT_ID = $accountId 
                        OR CONF.ACCOUNT_TYPE_ID = $accountTypeId 
                        OR CONF.CO_A_GROUP_ID IN (SELECT CO_A_GROUP_ID FROM FIN_ACCOUNT_CO_A_GROUP_MAP WHERE ACCOUNT_ID = $accountId)     
                        OR $accountAlias.ACCOUNT_ID = $accountId 
                        OR $accountAlias.ACCOUNT_TYPE_ID = $accountId 
                    ) $groupBy 
                    ORDER BY CONFL.IS_USE_OPP_ACCOUNT, MGC.DISPLAY_ORDER ASC");

                if ($result) {
                    
                    $result[0]['IS_USE_OPP_ACCOUNT'] = '0';

                    return $result;
                    
                } else {
                    
                    $opAccountTypeId = $this->db->GetOne("SELECT ACCOUNT_TYPE_ID FROM FIN_ACCOUNT WHERE ACCOUNT_ID = $opAccountId");
                    $result = $this->db->GetAll($sql." AND ((LOWER(MGC.FIELD_PATH) = LOWER('$opCashFlowSubCategoryId') AND CONF.ACCOUNT_TYPE_ID = $opAccountTypeId) OR CONF.ACCOUNT_TYPE_ID = $accountTypeId) $groupBy ORDER BY CONFL.IS_USE_OPP_ACCOUNT, MGC.DISPLAY_ORDER ASC");
                    
                    if ($result) {
                        $result[0]['IS_USE_OPP_ACCOUNT'] = '0';
                        return $result;
                    }
                }
            }
            
            if ($accountTypeId != '') {
                $result = $this->db->GetAll($sql." AND ($accountAlias.ACCOUNT_ID = $accountId OR CONF.ACCOUNT_TYPE_ID = $accountTypeId) $groupBy ORDER BY CONF.ORDER_NUMBER ASC, MGC.DISPLAY_ORDER ASC");
            } else {
                $result = $this->db->GetAll($sql." AND $accountAlias.ACCOUNT_ID = $accountId $groupBy ORDER BY CONF.ORDER_NUMBER ASC, MGC.DISPLAY_ORDER ASC");
            }            

            if ($result) {

                return $result;

            } elseif ($accountTypeId != '') {

                $result = $this->db->GetAll($sql." AND CONF.ACCOUNT_TYPE_ID = $accountTypeId $groupBy ORDER BY CONF.ORDER_NUMBER ASC, MGC.DISPLAY_ORDER ASC");

                if ($result) {
                    return $result;
                }
            }

            $result = $this->db->GetAll($sql." AND CONF.CO_A_GROUP_ID IN (SELECT CO_A_GROUP_ID FROM FIN_ACCOUNT_CO_A_GROUP_MAP WHERE ACCOUNT_ID = $accountId) ORDER BY CONF.ORDER_NUMBER ASC, MGC.DISPLAY_ORDER ASC");            

            if ($result) {
                return $result;
            }
            
        } catch(Exception $ex){      
            pa($ex);
            return array();
        }

        return array();
    }    
    
    public function getMetaByAccountTypeModelNew($selectedRow, $isOpMeta){
        
        try {
            
            if (Config::getFromCache('CONFIG_GL_ACCOUNT_PARENT_ID')) {
                
                /*$join = 'INNER JOIN FIN_ACCOUNT T2 ON (
                        CONF.ACCOUNT_ID = COALESCE(T2.PARENT_ID, T2.ACCOUNT_ID) 
                        OR CONF.ACCOUNT_TYPE_ID = T2.ACCOUNT_TYPE_ID 
                    ) '; */
                
                $join = 'INNER JOIN FIN_ACCOUNT T2 ON ((T2.PARENT_ID IS NOT NULL AND T2.PARENT_ID = CONF.ACCOUNT_ID) OR (T2.PARENT_ID IS NULL AND T2.ACCOUNT_ID = CONF.ACCOUNT_ID) OR T2.ACCOUNT_TYPE_ID = CONF.ACCOUNT_TYPE_ID) '; 
                
                $accountAlias = 'T2';
                
            } else {
                $join = '';
                $accountAlias = 'CONF';
            }
            
            $accountId = $selectedRow['accountid'];
            $accountTypeId = $selectedRow['accounttypeid'];
            $segmentJoin = '';
            
            if (Config::getFromCache('IS_CLOUD') == '1') {
                
                $sessionValues = Session::get(SESSION_PREFIX.'sessionValues');
            
                if ($sessionCompanyDepartmentId = issetParam($sessionValues['sessioncompanydepartmentid'])) {
                    $segmentJoin = " AND SC.COMPANY_DEPARTMENT_ID = $sessionCompanyDepartmentId "; 
                }
            }
            
            $sql = "
                SELECT 
                    0 AS GROUP_PARAM_CONFIG_TOTAL,
                    '' AS GROUP_CONFIG_PARAM_PATH,
                    '' AS GROUP_CONFIG_LOOKUP_PATH, 
                    '' AS GROUP_CONFIG_FIELD_PATH, 
                    '' AS GROUP_CONFIG_GROUP_PATH, 
                    null AS ATTRIBUTE_ID_COLUMN, 
                    null AS ATTRIBUTE_CODE_COLUMN, 
                    null AS ATTRIBUTE_NAME_COLUMN, 
                    MGC.FIELD_PATH AS PARAM_REAL_PATH, 
                    MGC.VISIBLE_CRITERIA,
                    '' AS IS_REFRESH,
                    CONF.DEFAULT_VALUE,
                    MGC.LOOKUP_TYPE,
                    MGC.LABEL_NAME,
                    MGC.CHOOSE_TYPE,
                    MGC.RECORD_TYPE,
                    MGC.VALUE_FIELD,
                    MGC.DISPLAY_FIELD,
                    MGC.IS_SHOW,
                    MGC.PARENT_ID, 
                    MGC.LOOKUP_META_DATA_ID, 
                    MGC.PARAM_NAME AS META_DATA_CODE, 
                    MGC.LABEL_NAME AS META_DATA_NAME, 
                    MGC.DATA_TYPE AS META_TYPE_CODE, 
                    MGC.FILE_EXTENSION, 
                    MGC.FRACTION_RANGE, 
                    MGC.MIN_VALUE, 
                    MGC.MAX_VALUE, 
                    LOWER(MGC.PARAM_NAME) AS LOWER_PARAM_NAME, 
                    REPLACE(MGC.FIELD_PATH, '.', '') AS NODOT_PARAM_REAL_PATH, 
                    MFP.PATTERN_TEXT,
                    MFP.PATTERN_NAME, 
                    MFP.GLOBE_MESSAGE,
                    MFP.IS_MASK, 
                    MAX(CONF.IS_REQUIRED) AS IS_REQUIRED, 
                    CONF.LOOKUP_CRITERIA, 
                    CONF.VALUE_CRITERIA, 
                    MAX(CONFL.IS_USE_OPP_ACCOUNT) AS IS_USE_OPP_ACCOUNT,
                    CONF.ACCOUNT_FILTER, 
                    SC.ID AS SEGMENT_ID, 
                    SC.SEPRATOR_CHAR, 
                    SC.REPLACE_VALUE, 
                    ".$this->db->IfNull('MGC.PLACEHOLDER_NAME', 'MGC.LABEL_NAME')." AS PLACEHOLDER_NAME,
                    MAX(COALESCE(CONF.DEBIT_DEFAULT_VALUE, CONF.DEFAULT_VALUE)) AS DEBIT_DEFAULT_VALUE,
                    MAX(COALESCE(CONF.CREDIT_DEFAULT_VALUE, CONF.DEFAULT_VALUE)) AS CREDIT_DEFAULT_VALUE 
                FROM META_GROUP_CONFIG MGC 
                    INNER JOIN FIN_ACCOUNT_GL_CONFIG CONF ON LOWER(CONF.FIELD_PATH) = LOWER(MGC.FIELD_PATH) 
                        $join 
                    LEFT JOIN META_FIELD_PATTERN MFP ON MFP.PATTERN_ID = MGC.PATTERN_ID       
                    LEFT JOIN FIN_ACCOUNT_GL_CONFIG_DTL CONFL ON LOWER(CONFL.FIELD_PATH) = LOWER(CONF.FIELD_PATH) AND ".$this->db->IfNull('CONF.IS_CHOOSE_OPP', '0')." = 0
                    LEFT JOIN FIN_ACCOUNT_SEGMENT_CONFIG SC ON LOWER(SC.FIELD_PATH) = LOWER(CONF.FIELD_PATH) 
                        $segmentJoin 
                WHERE MGC.MAIN_META_DATA_ID = ".Mdgl::$glBookDtlGroupMetaDataId." 
                    AND MGC.PARENT_ID IS NULL 
                    AND MGC.DATA_TYPE <> 'group' 
                    AND (CONF.CONFIG_TYPE IS NULL OR CONF.CONFIG_TYPE <> 2)";
            
            $groupBy = ' GROUP BY 
                    MGC.FIELD_PATH, 
                    MGC.VISIBLE_CRITERIA,
                    CONF.DEFAULT_VALUE,
                    MGC.LOOKUP_TYPE,
                    MGC.LABEL_NAME,
                    MGC.CHOOSE_TYPE,
                    MGC.RECORD_TYPE,
                    MGC.VALUE_FIELD,
                    MGC.DISPLAY_FIELD,
                    MGC.IS_SHOW,
                    MGC.PARENT_ID, 
                    MGC.LOOKUP_META_DATA_ID, 
                    MGC.PARAM_NAME, 
                    MGC.LABEL_NAME, 
                    MGC.DATA_TYPE, 
                    MGC.FILE_EXTENSION, 
                    MGC.FRACTION_RANGE, 
                    MGC.MIN_VALUE, 
                    MGC.MAX_VALUE, 
                    MFP.PATTERN_TEXT,
                    MFP.PATTERN_NAME, 
                    MFP.GLOBE_MESSAGE,
                    MFP.IS_MASK, 
                    CONF.LOOKUP_CRITERIA, 
                    CONF.VALUE_CRITERIA, 
                    CONF.ACCOUNT_FILTER, 
                    MGC.DISPLAY_ORDER, 
                    SC.ID, 
                    CONF.ORDER_NUMBER, 
                    SC.SEPRATOR_CHAR, 
                    SC.REPLACE_VALUE, 
                    MGC.PLACEHOLDER_NAME';
            
            if ($isOpMeta && strtolower($isOpMeta) !== 'cashflowsubcategoryid') {

                $isOpMetaArr = explode('|', $isOpMeta);
                $opAccountId = $isOpMetaArr[0];
                $opCashFlowSubCategoryId = $isOpMetaArr[1];
                
                $result = $this->db->GetAll($sql." AND ((LOWER(MGC.FIELD_PATH) = LOWER('$opCashFlowSubCategoryId') 
                    AND ( 
                            $accountAlias.ACCOUNT_ID = $opAccountId 
                            OR $accountAlias.ACCOUNT_TYPE_ID = (SELECT ACCOUNT_TYPE_ID FROM FIN_ACCOUNT WHERE ACCOUNT_ID = $opAccountId) 
                            OR CONF.CO_A_GROUP_ID IN (SELECT CO_A_GROUP_ID FROM FIN_ACCOUNT_CO_A_GROUP_MAP WHERE ACCOUNT_ID = $opAccountId)     
                        )) 
                        OR CONF.ACCOUNT_ID = $accountId 
                        OR CONF.ACCOUNT_TYPE_ID = $accountTypeId 
                        OR CONF.CO_A_GROUP_ID IN (SELECT CO_A_GROUP_ID FROM FIN_ACCOUNT_CO_A_GROUP_MAP WHERE ACCOUNT_ID = $accountId)     
                        OR $accountAlias.ACCOUNT_ID = $accountId 
                        OR $accountAlias.ACCOUNT_TYPE_ID = $accountId 
                    ) $groupBy 
                    ORDER BY MGC.DISPLAY_ORDER ASC");

                if ($result) {
                    
                    $result[0]['IS_USE_OPP_ACCOUNT'] = '0';

                    return $result;
                    
                } else {
                    
                    $opAccountTypeId = $this->db->GetOne("SELECT ACCOUNT_TYPE_ID FROM FIN_ACCOUNT WHERE ACCOUNT_ID = $opAccountId");
                    $result = $this->db->GetAll($sql." AND ((LOWER(MGC.FIELD_PATH) = LOWER('$opCashFlowSubCategoryId') AND CONF.ACCOUNT_TYPE_ID = $opAccountTypeId) OR CONF.ACCOUNT_TYPE_ID = $accountTypeId) $groupBy ORDER BY CONFL.IS_USE_OPP_ACCOUNT, MGC.DISPLAY_ORDER ASC");
                    
                    if ($result) {
                        $result[0]['IS_USE_OPP_ACCOUNT'] = '0';
                        return $result;
                    }
                }
            }
            
            if ($accountTypeId != '') {
                $result = $this->db->GetAll($sql." AND ($accountAlias.ACCOUNT_ID = $accountId OR CONF.ACCOUNT_TYPE_ID = $accountTypeId) $groupBy ORDER BY CONF.ORDER_NUMBER ASC, MGC.DISPLAY_ORDER ASC");
            } else {
                $result = $this->db->GetAll($sql." AND $accountAlias.ACCOUNT_ID = $accountId $groupBy ORDER BY CONF.ORDER_NUMBER ASC, MGC.DISPLAY_ORDER ASC");
            }

            if ($result) {

                return $result;

            } elseif ($accountTypeId != '') {

                $result = $this->db->GetAll($sql." AND CONF.ACCOUNT_TYPE_ID = $accountTypeId $groupBy ORDER BY CONF.ORDER_NUMBER ASC, MGC.DISPLAY_ORDER ASC");

                if ($result) {
                    return $result;
                }
            }

            $result = $this->db->GetAll($sql." AND CONF.CO_A_GROUP_ID IN (SELECT CO_A_GROUP_ID FROM FIN_ACCOUNT_CO_A_GROUP_MAP WHERE ACCOUNT_ID = $accountId) ORDER BY CONF.ORDER_NUMBER ASC, MGC.DISPLAY_ORDER ASC");

            if ($result) {
                return $result;
            }
            
        } catch(Exception $ex){      
            return array();
        }

        return array();
    }

    public function filterAccountInfoModel() {
            
        $cache = phpFastCache();
        $userId = Ue::sessionUserKeyId();
        $dvId = Input::numeric('dvId');
        $dvId = $dvId ? $dvId : Mdgl::$accountListDataViewId;
        
        $sql = $cache->get('finAccountDv_'.$dvId.'_'.$userId);

        if ($sql == null) {
            
            $this->load->model('mdmetadata', 'middleware/models/');

            $param = array(
                'systemMetaGroupId' => $dvId,
                'showQuery' => 1, 
                'ignorePermission' => 1 
            );

            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

            if ($data['status'] == 'success' && isset($data['result'])) {
                $sql = $data['result'];
                $cache->set('finAccountDv_'.$dvId.'_'.$userId, $sql, 86400);
            }
        }

        if ($sql) {

            $keyVal = Input::post('q');
            $accountfilter = Input::post('filter');
            $subcondition = '';

            if (!empty($accountfilter)) {

                $criteria = self::glfilterBuilder($accountfilter);
                $subcondition = ' AND (';

                foreach ($criteria as $val) {
                    $subcondition .= Str::lower($val['criteriaValue']).' '.$val['criteriaOperator']." '".$val['criteriaOperand']."' OR ";
                }

                $subcondition = rtrim($subcondition, 'OR ');
                $subcondition .= ') ';
            }

            $this->db->StartTrans(); 
            $this->db->Execute(Ue::createSessionInfo());

            $sqlData = $this->db->SelectLimit("SELECT * FROM ($sql) TEMP WHERE TEMP.ACCOUNTCODE LIKE '%$keyVal%' $subcondition ORDER BY TEMP.ACCOUNTCODE", 30, -1);

            $this->db->CompleteTrans();

            if (isset($sqlData->_array)) {
                return $sqlData->_array;
            }
        }

        return array();
    }

    public function getRowAccountInfoModel() {

        $cache = phpFastCache();
        $userId = Ue::sessionUserKeyId();
        $dvId = Input::numeric('dvId');
        $dvId = $dvId ? $dvId : Mdgl::$accountListDataViewId;
        
        $sql = $cache->get('finAccountDv_'.$dvId.'_'.$userId);

        if ($sql == null) {

            $param = array(
                'systemMetaGroupId' => $dvId,
                'showQuery' => 1, 
                'ignorePermission' => 1 
            );

            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

            if ($data['status'] == 'success' && isset($data['result'])) {
                $sql = $data['result'];
                $cache->set('finAccountDv_'.$dvId.'_'.$userId, $sql, 86400);
            }
        }

        if ($sql) {

            $keyVal = Input::post('q');
            $accountfilter = Input::post('filter');
            $subcondition = '';

            if (!empty($accountfilter)) {

                $criteria = self::glfilterBuilder($accountfilter);
                $subcondition = ' AND (';

                foreach ($criteria as $val) {
                    $subcondition .= Str::lower($val['criteriaValue'])." ".$val['criteriaOperator']." '".$val['criteriaOperand']."' OR ";
                }

                $subcondition = rtrim($subcondition, 'OR ');
                $subcondition .= ') ';
            }

            $this->db->StartTrans(); 
            $this->db->Execute(Ue::createSessionInfo());

            $row = $this->db->GetRow("SELECT * FROM ($sql) TEMP WHERE TEMP.ACCOUNTCODE = '$keyVal' $subcondition");

            $this->db->CompleteTrans();

            if ($row) {
                
                $crmRow = $this->db->GetRow("
                    SELECT 
                        CRM.CUSTOMER_ID AS CUSTOMERID, 
                        CRM.CUSTOMER_CODE AS CUSTOMERCODE, 
                        CRM.CUSTOMER_NAME AS CUSTOMERNAME 
                    FROM FIN_ACCOUNT_GL_CONFIG GC 
                        INNER JOIN VW_GL_CUSTOMER CRM ON CRM.CUSTOMER_ID = GC.DEFAULT_VALUE 
                    WHERE ( 
                            GC.ACCOUNT_ID = ".$this->db->Param(0)." 
                            OR 
                            GC.ACCOUNT_ID IN (SELECT PARENT_ID FROM FIN_ACCOUNT WHERE ACCOUNT_ID = ".$this->db->Param(0).") 
                        ) 
                        AND LOWER(GC.FIELD_PATH) = ".$this->db->Param(1), 
                    array($row['ACCOUNTID'], 'customerid') 
                );
                
                return array_merge($row, $crmRow);
            }
        }

        return array();
    }
    
    public function getMultiAccountModel($accountCodes) {
        
        $cache = phpFastCache();
        $userId = Ue::sessionUserKeyId();
        $dvId = Mdgl::$accountListDataViewId;
        
        $sql = $cache->get('finAccountDv_'.$dvId.'_'.$userId);

        if ($sql == null) {

            $param = array(
                'systemMetaGroupId' => $dvId,
                'showQuery' => 1, 
                'ignorePermission' => 1 
            );

            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

            if ($data['status'] == 'success' && isset($data['result'])) {
                $sql = $data['result'];
                $cache->set('finAccountDv_'.$dvId.'_'.$userId, $sql, 86400);
            }
        }
        
        if ($sql) {

            $this->db->StartTrans(); 
            $this->db->Execute(Ue::createSessionInfo());

            $data = $this->db->GetAll("SELECT * FROM ($sql) TEMP WHERE TEMP.ACCOUNTCODE IN ('".Arr::implode_r("','", $accountCodes, true)."')");

            $this->db->CompleteTrans();

            if ($data) {
                return $data;
            }
        }

        return array();
    }

    public function getDescriptionInfoModel() {
        $keyVal = Input::post('q');

        $row = $this->db->GetRow("
            SELECT 
                ID, 
                MESSAGE_CODE AS CODE, 
                MESSAGE_DESC_L AS NAME 
            FROM FIN_MESSAGE_INFO 
            WHERE MESSAGE_CODE = " . $this->db->Param(0), 
            array($keyVal)
        );

        if ($row) {
            return $row;
        }
        return array();
    }

    public function getCustomerInfoModel() {
        $subCondition = '';

        if (Input::postCheck('code')) {
            $keyVal = Input::post('code');
            $subCondition = " AND CUSTOMER_CODE = '$keyVal'";
        } else {
            $keyVal = Input::post('name'); 
            $subCondition = " AND CUSTOMER_NAME = '$keyVal'";
        }

        if (Config::getFromCache('IS_CLOUD') == '1') {
                
            $sessionValues = Session::get(SESSION_PREFIX.'sessionValues');
        
            if ($sessionCompanyDepartmentId = issetParam($sessionValues['sessioncompanydepartmentid'])) {
                $subCondition .= " AND DEPARTMENT_ID = $sessionCompanyDepartmentId "; 
            }
        }     

        $row = $this->db->GetRow("SELECT CUSTOMER_ID, CUSTOMER_CODE, CUSTOMER_NAME FROM VW_GL_CUSTOMER WHERE IS_ACTIVE = 1 $subCondition");

        if ($row) {
            return $row;
        }
        return array();
    }

    public function getExpenseCenterInfoModel() {
        $subCondition = '';

        if (Input::postCheck('code')) {
            $keyVal = Input::post('code');
            $subCondition = " WHERE ORG.DEPARTMENT_CODE = '$keyVal'";
        } else {
            $keyVal = Input::post('name'); 
            $subCondition = " WHERE ORG.DEPARTMENT_NAME = '$keyVal'";
        }

        $row = $this->db->GetRow("SELECT  ORG.*,  DTL.IS_EXPENSE_CENTER FROM  ORG_DEPARTMENT ORG  JOIN ORG_DEPARTMENT_DTL DTL ON DTL.DEPARTMENT_ID = ORG.DEPARTMENT_ID AND DTL.IS_COST_CENTER = 1 $subCondition");

        if ($row) {
            return $row;
        }
        return array();
    }

    public function filterDescriptionInfoModel() {
        $keyVal = Input::post('q');
        $sql = "SELECT ID, MESSAGE_CODE AS CODE, MESSAGE_DESC_L AS NAME FROM FIN_MESSAGE_INFO WHERE MESSAGE_CODE LIKE '$keyVal%'";
        $data = $this->db->SelectLimit($sql, 7, -1);

        if ($data && isset($data->_array)) {
            return $data->_array;
        }
        return null;
    }

    public function glfilterBuilder($accountfilter) {
        $array = $temparray = array();
        $temparray = json_decode(html_entity_decode($accountfilter), true);
        $j = 0;

        foreach ($temparray as $key => $paramval) {
            if (is_numeric($key)) {
                foreach ($paramval as $paramkey => $operandval) {
                    foreach ($operandval as $value) {
                        $array[$j]['criteriaValue'] = $paramkey;
                        $array[$j]['criteriaOperator'] = $value['operator'];
                        $array[$j]['criteriaOperand'] = $value['operand'];
                        $j++;
                    }
                    $j++;
                }
            } else {
                foreach ($paramval as $value) {
                    $array[$j]['criteriaValue'] = $key;
                    $array[$j]['criteriaOperator'] = $value['operator'];
                    $array[$j]['criteriaOperand'] = $value['operand'];
                    $j++;
                }
            }
            $j++;
        }
        return $array;
    }

    public function currencyListModel() {
        /*$result = $this->db->GetAll("
            SELECT
                CURRENCY_ID,
                CURRENCY_CODE,
                CURRENCY_NAME,
                RATE
            FROM REF_CURRENCY
            WHERE CURRENCY_CODE NOT LIKE 'MNT'");

        if ($result) {
            return $result;
        }
        return false;*/
        
        $param = array(
            'systemMetaGroupId' => '144435918212922',
            'showQuery' => 0, 
            'ignorePermission' => 1
        );

        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($result['status'] == 'success' && isset($result['result'][0])) {

            unset($result['result']['aggregatecolumns']);
            unset($result['result']['paging']);
            
            $data = $result['result'];
            $result = array();
            
            foreach ($data as $row) {
                
                $newRow = array(
                    'CURRENCY_ID' => $row['id'], 
                    'CURRENCY_CODE' => $row['currencycode'], 
                    'CURRENCY_NAME' => $row['currencyname']
                );
                $result[] = $newRow;
            }
            
            return $result;
        }
        
        return false;
    }

    public function getCurrencyRateModel() {
        
        $currencyId = Input::post('currencyId');
        $date = Input::post('date');
        
        if ($currencyId != null && $date != null) {
            
            $param = array(
                'currencyId' => $currencyId,
                'date' => $date
            );
            $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'RF_RTAC_004', $param);
            
            if ($result) {
                return $this->ws->getValue($result['result']);
            }
        }

        return false;
    }

    public function clearingTransListModel($bookDate) {
        $data = $this->db->GetAll("
        SELECT 
            AC.ACCOUNT_ID, 
            AC.ACCOUNT_CODE, 
            AC.ACCOUNT_NAME, 
            AC.ACCOUNT_TYPE_ID, 
            TP.ACCOUNT_TYPE_NAME, 
            AC.CURRENCY_ID, 
            CUR.CURRENCY_CODE,
            FAC.ACCOUNT_CLASS_ID,
        CASE
        WHEN GL.BALANCE >= 0 THEN
            ROUND(GL.BALANCE, 6)
        ELSE
            0
        END AS
            DEBIT_AMOUNT,
            CASE
        WHEN GL.BALANCE < 0 THEN
              ROUND(ABS(GL.BALANCE), 6)
        ELSE
            0
        END AS
            CREDIT_AMOUNT FROM FIN_ACCOUNT AC INNER JOIN FIN_ACCOUNT_TYPE TP ON TP.ID = AC.ACCOUNT_TYPE_ID
        INNER JOIN FIN_ACCOUNT_CLASS FAC ON FAC.ACCOUNT_CLASS_ID = TP.ACCOUNT_CLASS_ID
        INNER JOIN REF_CURRENCY CUR ON CUR.CURRENCY_ID = AC.CURRENCY_ID INNER JOIN
            (SELECT 
                GL.ACCOUNT_ID,
                SUM(GL.DEBIT_AMOUNT - GL.CREDIT_AMOUNT) AS BALANCE
            FROM FIN_GENERAL_LEDGER GL
            INNER JOIN FIN_GENERAL_LEDGER_BOOK BK ON BK.ID = GL.GENERAL_LEDGER_BOOK_ID
            WHERE BK.BOOK_DATE <= TO_DATE('$bookDate', 'yyyy-mm-dd')
            GROUP BY GL.ACCOUNT_ID
            ) GL ON GL.ACCOUNT_ID = AC.ACCOUNT_ID LEFT JOIN
            ( SELECT DISTINCT GL.ACCOUNT_ID
            FROM FIN_GENERAL_LEDGER_BOOK BOOK
            INNER JOIN FIN_GENERAL_LEDGER GL
            ON GL.GENERAL_LEDGER_BOOK_ID = BOOK.ID
            WHERE BOOK.BOOK_TYPE_ID      = 17
            AND BOOK.BOOK_DATE          >= TO_DATE('$bookDate', 'yyyy-mm-dd')
            ) MRG ON MRG.ACCOUNT_ID      = GL.ACCOUNT_ID WHERE FAC.ACCOUNT_CLASS_ID = 200101010000003 AND  GL.BALANCE <> 0 AND MRG.ACCOUNT_ID IS NULL ORDER BY AC.ACCOUNT_CODE");
        (Array) $array = array();
        $oldCurrencyId = 0;
        $rate = 1;
        foreach ($data as $k => $row) {
            $array[$k]['ACCOUNT_ID'] = $row['ACCOUNT_ID'];
            $array[$k]['ACCOUNT_CODE'] = $row['ACCOUNT_CODE'];
            $array[$k]['ACCOUNT_NAME'] = $row['ACCOUNT_NAME'];
            $array[$k]['ACCOUNTTYPE_ID'] = $row['ACCOUNT_TYPE_ID'];
            $array[$k]['CURRENCY_ID'] = $row['CURRENCY_ID'];
            $array[$k]['CURRENCY_CODE'] = $row['CURRENCY_CODE'];
            $array[$k]['ACCOUNT_TYPE_NAME'] = $row['ACCOUNT_TYPE_NAME'];

            if (strpos($row['DEBIT_AMOUNT'], ".") === false) {
                $array[$k]['DEBIT_AMOUNT'] = $row['DEBIT_AMOUNT'];
                $array[$k]['CRYPT_DEBIT_AMOUNT'] = Crypt::encrypt($row['DEBIT_AMOUNT'], 'glDebit');
            } else {
                $debitExpArr = explode(".", $row['DEBIT_AMOUNT']);
                if ($debitExpArr[0] == "") {
                    $array[$k]['DEBIT_AMOUNT'] = "0" . $row['DEBIT_AMOUNT'];
                    $array[$k]['CRYPT_DEBIT_AMOUNT'] = Crypt::encrypt("0" . $row['DEBIT_AMOUNT'], 'glDebit');
                } else {
                    $array[$k]['DEBIT_AMOUNT'] = $row['DEBIT_AMOUNT'];
                    $array[$k]['CRYPT_DEBIT_AMOUNT'] = Crypt::encrypt($row['DEBIT_AMOUNT'], 'glDebit');
                }
            }
            if (strpos($row['CREDIT_AMOUNT'], ".") === false) {
                $array[$k]['CREDIT_AMOUNT'] = $row['CREDIT_AMOUNT'];
                $array[$k]['CRYPT_CREDIT_AMOUNT'] = Crypt::encrypt($row['CREDIT_AMOUNT'], 'glCredit');
            } else {
                $creditExpArr = explode(".", $row['CREDIT_AMOUNT']);
                if ($creditExpArr[0] == "") {
                    $array[$k]['CREDIT_AMOUNT'] = "0" . $row['CREDIT_AMOUNT'];
                    $array[$k]['CRYPT_CREDIT_AMOUNT'] = Crypt::encrypt("0" . $row['CREDIT_AMOUNT'], 'glCredit');
                } else {
                    $array[$k]['CREDIT_AMOUNT'] = $row['CREDIT_AMOUNT'];
                    $array[$k]['CRYPT_CREDIT_AMOUNT'] = Crypt::encrypt($row['CREDIT_AMOUNT'], 'glCredit');
                }
            }

            $oldCurrencyId = $row['CURRENCY_ID'];
        }

        return $array;
    }

    public function newClearingTransListModel() {
        
        $params = array(
            'objectId' => '20000',  
            'bookTypeId' => Input::post('filterBookTypeId', '17'), 
            'bookNumber' => Input::post('clearingbookNumber'),
            'bookDate' => Input::post('clearingTransDate'), 
            'incomeOutcomeAccountId' => Input::post('incomeOutcomeAccountId'), 
            'extAccountId' => Input::post('extAccountId'), 
            'filterDepartmentId' => Input::post('filterDepartmentId'), 
            'filterEndDate' => Input::post('clearingTransDate')
        );
        
        if (Input::postCheck('clEconomicSourceId')) {
            $params['economicSourceId'] = Input::post('clEconomicSourceId');
        }
        
        $result = self::getTemplateModel($params);

        if ($result['status'] == 'success') {
        
            if (isset($result['data']['generalledgerbookdtls'])) {
                
                $arrResult = array();
                
                foreach ($result['data']['generalledgerbookdtls'] as $k => $r) {
                    array_push($arrResult, $r);
                }
                
                $response = array('status' => 'success', 'rows' => $arrResult);
                
            } else {
                $response = array('status' => 'error', 'message' => 'No data!');
            }
            
        } else {
            $response = array('status' => 'error', 'message' => $result['message']);
        }
        
        return $response;
    }

    public function saveClearingTransModel() {
        
        $generalLedgersData = Input::post('accountId');
        
        if (!$generalLedgersData) {
            return array('status' => 'error', 'message' => 'Дансны мэдээлэл олдсонгүй!');
        }
        
        $bookDate = Input::post('clearingTransDate');
        $departmentId = Input::post('filterDepartmentId');    
        
        $checkParam = array(
            'bookDate' => $bookDate,
            'departmentId' => $departmentId
        );
        $checkResult = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'finCheckBalance', $checkParam);

        if ($checkResult['status'] != 'success') {
            $checkResultMessage = $this->ws->getResponseMessage($checkResult);
            if ($checkResultMessage != '') {
                return array('status' => 'error', 'message' => $checkResultMessage);
            }
        }
        
        $bookNumber = Input::post('clearingbookNumber');
        $bookTypeId = Input::post('filterBookTypeId', '17');
        $description = 'Түр дансны хаалт'; //Input::post('clearingTransDescription');
        $generalLedgers = $accountBooks = array();
        $incomeOutcomeAccountId = Input::post('incomeOutcomeAccountId');
        $extAccountId = Input::post('extAccountId');
        $economicSourceId = Input::post('clEconomicSourceId');
        //$incomeOutcomeCurrencyId = Input::post('incomeOutcomeCurrencyId');
        
        $rate = 1;

        foreach ($generalLedgersData as $k => $accountId) {
            
            $credit = Number::decimal($_POST['credit'][$k]);
            $debit = Number::decimal($_POST['debit'][$k]);

            $generalLedgers[$k] = array(
                'objectId' => '20000',
                'subid' => Input::param($_POST['subId'][$k]),
                'accountId' => $accountId,
                'bookTypeId' => $bookTypeId,
                'description' => $description,
                'rate' => $rate,
                'creditAmount' => $credit,
                'debitAmount' => $debit,
                'customerId' => ''
            );
        }

        if ($economicSourceId == '1001') {
            $economicAccountId = '2000206';
        } elseif ($economicSourceId == '1002') {
            $economicAccountId = '1464108254835';
        }

        $generalLedgerParams = array(
            'bookTypeId' => $bookTypeId, 
            'objectId' => '20000',
            'bookDate' => $bookDate,
            'incomeOutcomeAccountId' => $incomeOutcomeAccountId,
            'clearingbookNumber' => $bookNumber,
            'extAccountId' => $extAccountId, 
            'generalLedgerBookParams' => array(
                'id' => getUID(),
                'bookTypeId' => $bookTypeId,
                'objectId' => '20000',
                'bookDate' => $bookDate,
                'bookNumber' => $bookNumber,
                'description' => $description, 
                'generalLedgerBookDtls' => $generalLedgers
            )                
        );

        $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'GL_BOK_008', $generalLedgerParams);

        if ($result['status'] == 'success') {
            return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
    }

    public function getOneCurrencyRateModel($currencyId, $bookDate) {
        $param = array(
            'currencyId' => $currencyId,
            'date' => $bookDate
        );
        $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'getBankRate', $param);

        if ($data['status'] == 'success') {
            return array('status' => 'success', 'rate' => $this->ws->getValue($data['result']));
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
        }
    }

    public function getAutoNumberModel($bookTypeId) {
        $param = array(
            'objectId' => 40002,
            'departmentId' => Ue::sessionUserKeyDepartmentId(),
            'bookTypeId' => $bookTypeId
        );

        return $this->ws->runResponse(GF_SERVICE_ADDRESS, 'FIN_AUNUM_004', $param);
    }

    public function autoCompleteByCustomerCodeModel() {
            
        $subCondition = null;

        if (Input::postCheck('code') && Input::post('code') != '') {
            $subCondition = " AND LOWER(CUSTOMER_CODE) LIKE LOWER('".Input::post('code')."%')";
        } elseif (Input::postCheck('name') && Input::post('name') != '') {
            $subCondition = " AND LOWER(CUSTOMER_NAME) LIKE LOWER('".Input::post('name')."%')";
        } elseif (Input::postCheck('id') && Input::post('id') != '') {
            $subCondition = " AND CUSTOMER_ID = '".Input::post('id')."'";
        }
        
        if ($subCondition) {
            $subCondition .= Config::getFromCache('IS_CLOUD') === '1' ? " AND DEPARTMENT_ID = '".Ue::sessionUserKeyDepartmentId()."'" : '';
                    
            $sql = "
                SELECT 
                    CUSTOMER_ID, 
                    CUSTOMER_CODE, 
                    CUSTOMER_NAME 
                FROM VW_GL_CUSTOMER 
                WHERE IS_ACTIVE = 1 $subCondition";

            $data = $this->db->SelectLimit($sql, 30, -1);

            if (isset($data->_array)) {
                return $data->_array;
            }
        }
        return array();
    }

    public function autoCompleteByExpenseCodeModel() {
            
        $subCondition = null;

        if (Input::postCheck('code') && Input::post('code') != '') {
            $subCondition = " WHERE LOWER(ORG.DEPARTMENT_CODE) LIKE LOWER('".Input::post('code')."%')";
        } elseif (Input::postCheck('name') && Input::post('name') != '') {
            $subCondition = " WHERE LOWER(ORG.DEPARTMENT_NAME) LIKE LOWER('".Input::post('name')."%')";
        } elseif (Input::postCheck('id') && Input::post('id') != '') {
            $subCondition = " WHERE ORG.DEPARTMENT_ID = '".Input::post('id')."'";
        }
        
        if ($subCondition) {
            $sql = "
            SELECT  ORG.*,  
                DTL.IS_EXPENSE_CENTER 
            FROM  ORG_DEPARTMENT ORG  
            JOIN ORG_DEPARTMENT_DTL DTL ON DTL.DEPARTMENT_ID = ORG.DEPARTMENT_ID AND DTL.IS_COST_CENTER = 1 
            $subCondition";

            $data = $this->db->SelectLimit($sql, 30, -1);

            if (isset($data->_array)) {
                return $data->_array;
            }
        }
        return array();
    }

    public function getAccountRowByIdModel(){
            
        $accountId = Input::post('accountId');

        $row = $this->db->GetRow("
            SELECT 
                ACC.ACCOUNT_ID,
                ACC.ACCOUNT_CODE,
                ACC.ACCOUNT_NAME, 
                LOWER(CURR.CURRENCY_CODE) AS CURRENCY_CODE   
            FROM FIN_ACCOUNT ACC 
                LEFT JOIN REF_CURRENCY CURR ON CURR.CURRENCY_ID = ACC.CURRENCY_ID
            WHERE ACC.ACCOUNT_ID = " . $this->db->Param(0), 
            array($accountId)
        );

        if ($row) {
            return $row;
        }
        return array();
    }

    public function getRateForAccountModel() {
        
        $param = array(
            'accountId' => Input::post('accountId'),
            'date' => Date::formatter(Input::post('date'), 'Y-m-d')
        );
        $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'RF_RTAC_004', $param);

        if ($result['status'] == 'success') {   
            return array(
                'status' => $result['status'],
                'result' => $result['result']
            );
        } else {
            return array(
                'status' => $result['status'],
                'message' => $this->ws->getResponseMessage($result)
            );
        }
    }

    public function getRateByCurrencyIdModel() {

        $param = array(
            'currencyId' => Input::post('currencyId'),
            'date' => Date::formatter(Input::post('date'), 'Y-m-d')
        );
        $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'getBankRate', $param);

        if ($result['status'] == 'success' && isset($result['result'])) {   
            return array(
                'status' => $result['status'],
                'result' => $result['result']
            );
        } else {
            return array(
                'status' => $result['status'],
                'message' => $this->ws->getResponseMessage($result)
            );
        }
    }

    public function getDescriptionModel($description) {
        $description = Str::lower($description);
        $result = $this->db->GetRow("SELECT MESSAGE_CODE FROM FIN_MESSAGE_INFO WHERE LOWER(MESSAGE_DESC_L) = " . $this->db->Param(0), array($description));
        return $result;
    }

    public function getAccountModel($accountId){
        $result = $this->db->GetAll("
            SELECT 
                ACC.ACCOUNT_ID,
                ACC.ACCOUNT_CODE,
                ACC.ACCOUNT_NAME,
                ACC.ACCOUNT_TYPE_ID,
                ACC.USE_DETAIL_BOOK,
                TYPE.OBJECT_ID, 
                TYPE.ACCOUNT_TYPE_CODE 
            FROM FIN_ACCOUNT ACC
                INNER JOIN FIN_ACCOUNT_TYPE TYPE ON ACC.ACCOUNT_TYPE_ID = TYPE.ID
            WHERE ACC.ACCOUNT_ID IN ($accountId)"
        );
        if ($result) {
            return $result;
        }
        return false;
    }

    public function checkAccountBpLinkModel($postData) {
            
        if (!empty($postData['objectid'])) {
            $row = $this->db->GetRow("
                SELECT  
                    DEBIT_PROCESS_ID, 
                    CREDIT_PROCESS_ID, 
                    DATAVIEW_ID, 
                    DEBIT_EDIT_PROCESS_ID,
                    CREDIT_EDIT_PROCESS_ID
                FROM FIN_ACCOUNT_TYPE_BP_CONFIG 
                WHERE OBJECT_ID = ".$this->db->Param(0), 
                array(Input::param($postData['objectid']))
            ); 

        } else {
            $row = $this->db->GetRow("
                SELECT  
                    DEBIT_PROCESS_ID, 
                    CREDIT_PROCESS_ID, 
                    DATAVIEW_ID,
                    DEBIT_EDIT_PROCESS_ID, 
                    CREDIT_EDIT_PROCESS_ID 
                FROM FIN_ACCOUNT_TYPE_BP_CONFIG 
                WHERE ACCOUNT_TYPE_ID = ".$this->db->Param(0), 
                array(Input::param($postData['accounttypeid']))
            ); 
        }

        $row['processButtons'] = '';

        /*if (isset($row['DATAVIEW_ID']) && (
            $row['DATAVIEW_ID'] != '' && $row['DEBIT_PROCESS_ID'] == '' && 
            $row['CREDIT_PROCESS_ID'] == '' && $row['DEBIT_EDIT_PROCESS_ID'] == '' && 
            $row['CREDIT_EDIT_PROCESS_ID'] == '')) {

            $processButtons = self::glProcessCommandModel($row['DATAVIEW_ID'], Input::param($postData['uniqId']), false);

            if ($processButtons) {
                $row['processButtons'] = $processButtons;
            }
        }*/
        
        $confCount = 0;

        if (Config::getFromCache('CONFIG_GL_ALL_ACC_META') && $confRow = self::isUseMeta($postData['accountid'], $postData['accounttypeid'])) {
            
            $confCount = $confRow['CONF_COUNT'];
            
            if ($confRow['DEBIT_DEFAULT_VALUE'] || $confRow['CREDIT_DEFAULT_VALUE']) {
                $row['isDebitCreditDefaultValue'] = '1';
            }     
            
            if (($confCount > 0 && $confRow['DTL_COUNT'] == 0) || ($confCount > 1 && $confRow['DTL_COUNT'] == 1)) {
                
                $row['isMeta'] = true;
                
                if (Config::getFromCache('CONFIG_GL_ROW_EXPENSE_CENTER')) {
                    $result = self::checkAccountRowBpMetaModel($postData, 1);

                    if ($result['expenseCenterControl']) {
                        $row['expenseCenterControl'] = $result['expenseCenterControl'];
                        if ($confCount == '1') {
                            $row['expenseCenterControlOnly'] = 1;
                        }
                    }
                }
            }
            if ($confRow['DTL_COUNT'] > 0) {
                $row['isOppMeta'] = $postData['accountid'].'|cashFlowSubCategoryId';
            }
        }

        if (isset($postData['opMeta'])) {
            $row['isMeta'] = true;
        }
        
        if (isset($row['isMeta']) && $confCount == 1 && isset($row['isOppMeta'])) {
            unset($row['isMeta']);
        }

        return $row;
    }
    
    public function isUseMeta($accountId, $accountTypeId) {
        
        if (Config::getFromCache('CONFIG_GL_ACCOUNT_PARENT_ID')) {
            
            $join = 'INNER JOIN FIN_ACCOUNT T2 ON (
                    CONF.ACCOUNT_ID = COALESCE(T2.PARENT_ID, T2.ACCOUNT_ID) 
                    OR CONF.ACCOUNT_TYPE_ID = T2.ACCOUNT_TYPE_ID 
                ) '; 
            $accountAlias = 'T2';
            
        } else {
            $join = '';
            $accountAlias = 'CONF';
        }
        
        if ($accountTypeId) {
            $where = "($accountAlias.ACCOUNT_ID = $accountId OR CONF.ACCOUNT_TYPE_ID = $accountTypeId)";
        } else {
            $where = "$accountAlias.ACCOUNT_ID = $accountId";
        }
        
        $row = $this->db->GetRow("
            SELECT 
                COUNT(CONF.ID) AS CONF_COUNT, 
                COUNT(DTL.ID) AS DTL_COUNT,
                COUNT(CONF.DEBIT_DEFAULT_VALUE) AS DEBIT_DEFAULT_VALUE,
                COUNT(CONF.CREDIT_DEFAULT_VALUE) AS CREDIT_DEFAULT_VALUE       
            FROM FIN_ACCOUNT_GL_CONFIG CONF 
                $join 
                LEFT JOIN FIN_ACCOUNT_GL_CONFIG_DTL DTL ON LOWER(DTL.FIELD_PATH) = LOWER(CONF.FIELD_PATH) AND DTL.IS_USE_OPP_ACCOUNT = 1 
            WHERE $where AND (CONF.CONFIG_TYPE IS NULL OR CONF.CONFIG_TYPE <> 2)");

        if ($row) {
            return $row;
        } 

        return false;
    }    
        
    public function isUseMetaNew($accountId, $accountTypeId) {
        
        if (Config::getFromCache('CONFIG_GL_ACCOUNT_PARENT_ID')) {
            
            $join = 'INNER JOIN FIN_ACCOUNT T2 ON (
                    CONF.ACCOUNT_ID = COALESCE(T2.PARENT_ID, T2.ACCOUNT_ID) 
                    OR CONF.ACCOUNT_TYPE_ID = T2.ACCOUNT_TYPE_ID 
                ) '; 
            $accountAlias = 'T2';
            
        } else {
            $join = '';
            $accountAlias = 'CONF';
        }
        
        if ($accountTypeId) {
            $where = "($accountAlias.ACCOUNT_ID = $accountId OR CONF.ACCOUNT_TYPE_ID = $accountTypeId)";
        } else {
            $where = "$accountAlias.ACCOUNT_ID = $accountId";
        }
        
        $row = $this->db->GetRow("
            SELECT 
                COUNT(CONF.ID) AS CONF_COUNT, 
                COUNT(DTL.ID) AS DTL_COUNT,
                COUNT(CONF.DEBIT_DEFAULT_VALUE) AS DEBIT_DEFAULT_VALUE,
                COUNT(CONF.CREDIT_DEFAULT_VALUE) AS CREDIT_DEFAULT_VALUE       
            FROM FIN_ACCOUNT_GL_CONFIG CONF 
                $join 
                LEFT JOIN FIN_ACCOUNT_GL_CONFIG_DTL DTL ON LOWER(DTL.FIELD_PATH) = LOWER(CONF.FIELD_PATH) AND DTL.IS_USE_OPP_ACCOUNT = 1 AND ".$this->db->IfNull('CONF.IS_CHOOSE_OPP', '0')." = 0
            WHERE $where AND (CONF.CONFIG_TYPE IS NULL OR CONF.CONFIG_TYPE <> 2)");

        if ($row) {
            return $row;
        } 

        return false;
    }

    public function glProcessCommandModel($metaDataId, $uniqId, $checkPopup) {

        (Array) $commandFunction = array();
        (String) $cmdBtn = null;
        (Array) $commandContext = array();
        (Array) $commandSort = array();
        (Boolean) $isDataViewLifeCycle = false;

        $this->load->model('mdobject', 'middleware/models/');

        $getAccessProcess = $this->model->getAccessProcess($metaDataId);

        if ($getAccessProcess) {

            (Array) $dataViewProcess = $this->model->getDataViewProcess($metaDataId, $getAccessProcess);

            if ($dataViewProcess) {

                (Array) $dataViewTransferProcess = $this->model->getDataViewTransferProcess($metaDataId, $isDataViewLifeCycle, $getAccessProcess);
                (Array) $dataViewBatch = $this->model->getDataViewBatchGroupBy($metaDataId);

                (Array) $batchProcess = array();        

                if ($dataViewBatch && $dataViewProcess) {
                    foreach ($dataViewBatch as $batch) {

                        $batchBtn = '';

                        if ($batch['IS_DROPDOWN'] == '1') {

                            if (!($checkPopup && $batch['IS_SHOW_POPUP'] != '1')) {

                                $batchBtn .= '<div class="btn-group">';
                                $batchBtn .= '<button class="btn ' . (isset($batch['BUTTON_STYLE']) ? $batch['BUTTON_STYLE'] : 'btn-secondary') . ' btn-circle btn-sm dropdown-toggle" type="button" data-toggle="dropdown">';
                                $batchBtn .= (($batch['ICON_NAME'] != "") ? '<i class="icon-plus3 font-size-12"></i> ' : '') . Lang::line($batch['BATCH_NAME']);
                                $batchBtn .= '</button>';
                                $batchBtn .= '<ul class="dropdown-menu float-right" role="menu">';

                                foreach ($dataViewProcess as $row) {

                                    if (!($checkPopup && $row['IS_SHOW_POPUP'] != '1') && $row['BATCH_NUMBER'] == $batch['BATCH_NUMBER']) {

                                        $batchBtn .= '<li>';

                                        if (empty($row['CRITERIA'])) {

                                            $batchBtn .= Html::anchor(
                                                'javascript:;', ((!empty($row['ICON_NAME'])) ? '<i class="fa ' . $row['ICON_NAME'] . '"></i> ' : '') . Lang::line($row['PROCESS_NAME']), array(
                                                'onclick' => 'runBpGlAccountRow_'.$uniqId.'(\''.$row['PROCESS_META_DATA_ID'].'\', $(\'body\').find(\'table#glDtl > tbody > tr.gl-selected-row\'), true, \'defaultInvoiceBook\');'
                                                ), true
                                            );
                                        } else {
                                            array_push($commandContext, array(
                                                    'PROCESS_META_DATA_ID' => $row['PROCESS_META_DATA_ID'], 
                                                    'PROCESS_NAME' => $row['PROCESS_NAME'],
                                                    'metaTypeId' => $row['META_TYPE_ID'],
                                                    'standartAction' => 'processCriteria',
                                                    'ICON_NAME' => $row['ICON_NAME'], 
                                                    'passPath' => $row['PASSWORD_PATH'], 
                                                    'ORDER_NUM' => $row['ORDER_NUM'] 
                                                )
                                            );
                                        }
                                        $batchBtn .= '</li>';

                                        $batchProcess[$row['PROCESS_META_DATA_ID']] = $row['PROCESS_META_DATA_ID'];
                                    }
                                }

                                $batchBtn .= '</ul>';
                                $batchBtn .= '</div>';

                                $commandSort[$batch['BATCH_NUMBER']] = $batchBtn;
                            }

                        } else {

                            if (!($checkPopup && $batch['IS_SHOW_POPUP'] != '1')) {

                                foreach ($dataViewProcess as $row) {

                                    if ($row['BATCH_NUMBER'] == $batch['BATCH_NUMBER']) {
                                        $batchProcess[$row['PROCESS_META_DATA_ID']] = $row['PROCESS_META_DATA_ID'];
                                        if (count($commandContext) > 0) {
                                            foreach ($commandContext as $ckey => $crow) {
                                                if (isset($crow['PROCESS_META_DATA_ID']) && $crow['PROCESS_META_DATA_ID'] == $row['PROCESS_META_DATA_ID']) {
                                                    unset($commandContext[$ckey]);
                                                }
                                            }
                                        }
                                    }
                                }
                                array_push($commandContext, array(
                                        'PROCESS_NAME' => $batch['BATCH_NAME'],
                                        'batchNumber' => $batch['BATCH_NUMBER'],
                                        'standartAction' => 'criteria',
                                        'ICON_NAME' => $batch['ICON_NAME'], 
                                        'ORDER_NUM' => $batch['BATCH_NUMBER']
                                    )
                                );

                                $commandSort[$batch['BATCH_NUMBER']] = $batchBtn;
                            }
                        }
                    }
                }

                if ($dataViewProcess) {

                    foreach ($dataViewProcess as $row) {
                        if (!in_array($row['PROCESS_META_DATA_ID'], $batchProcess) && !($checkPopup && $row['IS_SHOW_POPUP'] != '1')) {                            

                            if (empty($row['CRITERIA']) && empty($row['POST_PARAM'])) {

                                $commandSort[$row['ORDER_NUM']] = Html::anchor(
                                    'javascript:;', (($row['ICON_NAME'] != "") ? '<i class="fa ' . $row['ICON_NAME'] . '"></i> ' : '') . Lang::line($row['PROCESS_NAME']), array(
                                        'class' => 'btn ' . (isset($row['BUTTON_STYLE']) ? $row['BUTTON_STYLE'] : 'btn-secondary') . ' btn-circle btn-sm',
                                        'title' => !empty($row['ICON_PROCESS_NAME']) ? $row['ICON_PROCESS_NAME'] : $row['META_DATA_NAME'],
                                        'onclick' => 'transferProcessAction(\'' . $row['PASSWORD_PATH'] . '\', \'' . $metaDataId . '\', \'' . $row['PROCESS_META_DATA_ID'] . '\', \'' . $row['META_TYPE_ID'] . '\', \'toolbar\', this, {callerType: \'generalledger\'});'
                                    ), true
                                );

                            } else {

                                array_push($commandContext, array(
                                        'PROCESS_META_DATA_ID' => $row['PROCESS_META_DATA_ID'], 
                                        'PROCESS_NAME' => $row['PROCESS_NAME'],
                                        'metaTypeId' => $row['META_TYPE_ID'],
                                        'standartAction' => 'processCriteria',
                                        'ICON_NAME' => $row['ICON_NAME'], 
                                        'passPath' => $row['PASSWORD_PATH'], 
                                        'ORDER_NUM' => $row['ORDER_NUM']
                                    )
                                );
                            }
                        }
                    }
                }

                ksort($commandSort);

                if (!empty($commandSort)) {
                    $cmdBtn .= '<div class="btn-group btn-group-devided gl-btn-group-dialog">';
                    $cmdBtn .= implode('', $commandSort);
                    $cmdBtn .= '</div>';
                }
            }
        }

        return $cmdBtn;
    }

// <editor-fold defaultstate="collapsed" desc="noat, mongon uzuulelt">
    public function getTaxMetaValuesModel($type, $isDebit) {
        if ($isDebit) {
            $data = $this->db->GetAll("
                SELECT
                    CODE, 
                    NAME, 
                    VAT_ATTR_CATEGORY_ID, 
                    VAT_ATTR_SUB_CATEGORY_ID, 
                    IS_DEBIT
                FROM FIN_VAT_ATTR_SUB_CATEGORY 
                WHERE (IS_DEBIT = $type OR IS_DEBIT = 2) 
                ORDER BY CODE ASC");
        } else {
            $data = $this->db->GetAll("
                SELECT
                    CODE, 
                    NAME, 
                    VAT_ATTR_CATEGORY_ID, 
                    VAT_ATTR_SUB_CATEGORY_ID, 
                    IS_DEBIT
                FROM FIN_VAT_ATTR_SUB_CATEGORY
                ORDER BY CODE ASC");            
        }

        if ($data) {
            $dataList = Arr::naturalsort($data, array('CODE'));
            return $dataList;
        }
        return null;
    }

    public function getCashMetaValuesModel($type) {
        
        if ($type == 'all') {
            $where = '';
        } else {
            $where = "WHERE $type IS NULL 
                OR (
                    $type = 1 AND ((IS_DEBIT = 1 AND IS_CREDIT = 0) OR (IS_DEBIT = 1 AND IS_CREDIT = 1))
                )
                OR (
                    $type = 0 AND ((IS_CREDIT = 1 AND IS_DEBIT = 0) OR (IS_CREDIT = 1 AND IS_DEBIT = 1))
                )";
        }
        
        $data = $this->db->GetAll("
            SELECT
                CASH_FLOW_CATEGORY_ID,
                CASH_FLOW_SUB_CATEGORY_ID,
                CODE,
                NAME,
                IS_DEBIT
            FROM FIN_CASH_FLOW_SUB_CATEGORY 
            $where 
            ORDER BY CODE ASC");

        if ($data) {
            $dataList = Arr::naturalsort($data, array('CODE'));
            return $dataList;
        }
        return null;
    }
// </editor-fold>

    public function customerBillModel($param){
        $startDate = $param['fromDate'];
        $endDate = $param['toDate'];
        $accountId = !empty($param['accountId']) ? $param['accountId'] : 'null';
        $currencyId = !empty($param['currencyId']) ? $param['currencyId'] : 'null';
        $customerId = !empty($param['customerId']) ? $param['customerId'] : 'null';
        
        if (isset($param['departmentId'])) {
            $departmentId = Input::param($param['departmentId']);
        } else {
            $sessionUserKeyDepartmentId = Ue::sessionUserKeyDepartmentId();
            $departmentId = $sessionUserKeyDepartmentId ? $sessionUserKeyDepartmentId : Ue::sessionDepartmentId();
        }

        try {
            $this->db->StartTrans(); 
            $this->db->Execute(Ue::createSessionInfo());
            
            if (Config::getFromCache('CONFIG_GL_BILLRATE_HDR_RATE')) {
                $result = $this->db->GetAll("
                    SELECT * 
                    FROM TABLE(FNC_APAR_REVAL_LIST_KEY('$endDate', $accountId, $currencyId, $customerId, '$departmentId'))"
                );
            } else {
                $result = $this->db->GetAll("
                    SELECT * 
                    FROM TABLE(FNC_APAR_REVAL_LIST('$startDate', '$endDate', $accountId, $currencyId, $customerId, '$departmentId'))"
                );
            }

            $this->db->CompleteTrans();        
            
        } catch (ADODB_Exception $ex) {
            $response = array('status' => 'error', 'text' => $ex->getMessage(), 'title' => 'Сануулга');
            return $response;
        }

        if (empty($result)) {
            $response = array('status' => 'warning', 'text' => 'Өгөгдөл олдсонгүй.', 'title' => 'Сануулга');
            return $response;
        }
        $response = array('status' => 'success', 'text' => 'Success.', 'title' => 'Success', 'getRows' => $result);

        return $response;
    }
    
    public function customerBill2Model($param){
        $startDate = $param['fromDate'];
        $endDate = $param['toDate'];
        $accountId = !empty($param['accountId']) ? $param['accountId'] : 'null';
        $currencyId = !empty($param['currencyId']) ? $param['currencyId'] : 'null';
        $customerId = !empty($param['customerId']) ? $param['customerId'] : 'null';
        $departmentId = Ue::sessionUserKeyDepartmentId() ? Ue::sessionUserKeyDepartmentId() : Ue::sessionDepartmentId();

        try {
            $this->db->StartTrans(); 
            $this->db->Execute(Ue::createSessionInfo());

            $result = $this->db->GetAll("
                SELECT * 
                FROM TABLE(FNC_APAR_REVAL_LIST_KEY('$endDate', $accountId, $currencyId, $customerId, '$departmentId'))"
            );

            $this->db->CompleteTrans();            
        } catch (ADODB_Exception $ex) {
            $response = array('status' => 'error', 'text' => $ex->getMessage(), 'title' => 'Сануулга');
            return $response;
        }

        if (empty($result)) {
            $response = array('status' => 'warning', 'text' => 'Өгөгдөл олдсонгүй.', 'title' => 'Сануулга');
            return $response;
        }
        $response = array('status' => 'success', 'text' => 'Success.', 'title' => 'Success', 'getRows' => $result);

        return $response;
    }           

    public function bankRangeCustomerBillModel($param) {

        $page = Input::postCheck('page') ? Input::post('page') : 1;
        $rows = Input::postCheck('rows') ? Input::post('rows') : 10;
        $offset = ($page - 1) * $rows;

        $condition = '';
        $subCondition = '';
        $result = $footerArr = array();

        $sortField = 'BOOK_DATE';
        $sortOrder = 'DESC';
        if (Input::postCheck('sort') && Input::postCheck('order')) {
            $sortField = Input::post('sort');
            $sortOrder = Input::post('order');
        }


        $startDate = $param['fromDate'];
        $endDate = $param['toDate'];
        $accountId = !empty($param['accountId']) ? $param['accountId'] : 'null';
        $currencyId = !empty($param['currencyId']) ? $param['currencyId'] : 'null';
        $customerId = !empty($param['customerId']) ? $param['customerId'] : 'null';
        $departmentId = Ue::sessionUserKeyDepartmentId() ? Ue::sessionUserKeyDepartmentId() : Ue::sessionDepartmentId();

        $rowCount = $this->db->GetRow("SELECT 
                                    COUNT(KEY_ID) AS ROW_COUNT,
                                    SUM(RATE) AS TOTAL_RATE,
                                    SUM(BEGIN_AMOUNT_BASE) AS TOTAL_BEGIN_AMOUNT_BASE,
                                    SUM(END_AMOUNT_BASE) AS TOTAL_END_AMOUNT_BASE,
                                    SUM(DEBIT_AMOUNT_BASE) AS TOTAL_BASE_DEBIT_AMOUNT,
                                    SUM(DEBIT_AMOUNT) AS TOTAL_DEBIT_AMOUNT,
                                    SUM(CREDIT_AMOUNT_BASE) AS TOTAL_BASE_CREDIT_AMOUNT,
                                    SUM(CREDIT_AMOUNT) AS TOTAL_CREDIT_AMOUNT  
                                FROM 
                                    TABLE(FNC_APAR_LIST_WITH_OPP_ACC(TO_DATE('$startDate', 'YYYY-MM-DD'), TO_DATE('$endDate', 'YYYY-MM-DD'), $accountId, $currencyId, $customerId, '$departmentId'))");
        $result['total'] = $rowCount['ROW_COUNT'];

        $selectList = "SELECT 
                            * 
                        FROM TABLE(FNC_APAR_LIST_WITH_OPP_ACC(TO_DATE('$startDate', 'YYYY-MM-DD'), TO_DATE('$endDate', 'YYYY-MM-DD'), $accountId, $currencyId, $customerId, '$departmentId')) 
                        ORDER BY $sortField $sortOrder"; 

        $rs = $this->db->GetAll($selectList, $rows, $offset);
        
        if ($rs) {
            $result['rows'] = $rs;
            $result['log'] = array('message' => '', 'status' => 'success', 'title' => 'Сануулга');
        } else {
            $result['rows'] = array();
            $result['log'] = array('message' => 'Өгөгдөл олдсонгүй.', 'status' => 'warning', 'title' => 'Сануулга');
        }

        $footer = array(
            'DEBIT_AMOUNT_BASE' => $rowCount['TOTAL_BASE_DEBIT_AMOUNT'],
            'RATE' => '',
            'BEGIN_AMOUNT_BASE' => '',
            'DEBIT_AMOUNT' => $rowCount['TOTAL_DEBIT_AMOUNT'],
            'CREDIT_AMOUNT_BASE' => $rowCount['TOTAL_BASE_CREDIT_AMOUNT'],
            'CREDIT_AMOUNT' => $rowCount['TOTAL_CREDIT_AMOUNT'],
            'END_AMOUNT_BASE' => ''
        );
        array_push($footerArr, $footer);
        $result['footer'] = $footerArr;

        if (empty($result)) {
            $response = array('text' => 'Өгөгдөл олдсонгүй.', 'status' => 'warning', 'title' => 'Сануулга');
            return $response;
        }
        return $result;
    }

    public function customerBillDetailModel($keyId){
        $result = $this->db->GetAll("
            SELECT 
                HDR.BOOK_DATE, 
                HDR.BOOK_NUMBER, 
                HDR.DESCRIPTION, 
                AR.DEBIT_AMOUNT, 
                AR.DEBIT_AMOUNT_BASE, 
                AR.CREDIT_AMOUNT, 
                AR.CREDIT_AMOUNT_BASE,
                AR.RATE,
                AR.RECEIVABLE_KEY_ID,
                CR.TRG_ACCOUNT_ID,
                CR.TRG_ACCOUNT_CODE,
                CR.TRG_ACCOUNT_NAME,
                CUS.CUSTOMER_ID,
                CUS.CUSTOMER_CODE,
                CUS.CUSTOMER_NAME        
            FROM AR_RECEIVABLE AR
                INNER JOIN AR_RECEIVABLE_HDR HDR ON HDR.ID = AR.RECEIVABLE_HDR_ID
                INNER JOIN AR_RECEIVABLE_KEY K ON K.ID = AR.RECEIVABLE_KEY_ID
                INNER JOIN CRM_CUSTOMER CUS ON CUS.CUSTOMER_ID = K.CUSTOMER_ID
                INNER JOIN FIN_GENERAL_LEDGER_MAP M ON M.INVOICE_ID = HDR.ID
                INNER JOIN VW_GENERAL_LEDGER_CROSS CR ON CR.SRC_ID = M.GENERAL_LEDGER_ID
            WHERE K.ID = ".$this->db->Param(0)." 
            ORDER BY HDR.BOOK_DATE", 
            array($keyId) 
        );

        return $result;
    }

    public function saveBillRateModel() {

        $bookDate = Input::postCheck('glbookDate') ? Input::post('glbookDate') : Input::post('hidden_glbookDate');

        $generalLedgers = array();

        if (Input::postCheck('gl_accountId')) {
            $generalLedgersData = Input::post('gl_accountId');

            foreach ($generalLedgersData as $k => $accountId) {
                if ($accountId != '') {

                    $currencyCode = strtolower(Input::param($_POST['gl_rate_currency'][$k]));

                    if ($currencyCode == 'mnt') {
                        $rate = 1;
                        $creditAmount = Number::decimal(Input::param($_POST['gl_creditAmount'][$k]));
                        $creditAmountBase = $creditAmount;
                        $debitAmount = Number::decimal(Input::param($_POST['gl_debitAmount'][$k]));
                        $debitAmountBase = $debitAmount;
                    } else {
                        $rate = Number::decimal(Input::param($_POST['gl_rate'][$k]));
                        $creditAmount = Number::decimal(Input::param($_POST['gl_creditAmount'][$k]));
                        $creditAmountBase = Number::decimal(Input::param($_POST['gl_creditAmountBase'][$k]));
                        $debitAmount = Number::decimal(Input::param($_POST['gl_debitAmount'][$k]));
                        $debitAmountBase = Number::decimal(Input::param($_POST['gl_debitAmountBase'][$k]));
                    }

                    $generalLedgers[$k] = array(
                        'subid' => Input::param($_POST['gl_subid'][$k]),
                        'accountId' => $accountId,
                        'description' => Input::param($_POST['gl_rowdescription'][$k]),
                        'rate' => $rate,
                        'creditAmount' => $creditAmount,
                        'creditAmountBase' => $creditAmountBase,
                        'debitAmount' => $debitAmount,
                        'debitAmountBase' => $debitAmountBase,
                        'customerId' => Input::param($_POST['gl_customerId'][$k])
                    );

                    if (isset($_POST['gl_invoiceBookId'][$k])) {
                        $accountBook = array();
                        $accbooks = Input::param($_POST['gl_invoiceBookId'][$k]);
                        
                        if ($accbooks != '') {
                            
                            $books = explode(',', $accbooks);
                            
                            foreach ($books as $key => $value) {
                                
                                $value = trim($value);
                                
                                if (!empty($value)) {
                                    $accountBook[$key] = array(
                                        'invoiceId' => $value,
                                        'objectId' => Input::param($_POST['gl_objectId'][$k])
                                    );
                                }
                            }
                            $generalLedgers[$k]['generalLedgerMaps'] = $accountBook;
                        }
                    }

                    if (isset($_POST['defaultInvoiceBook'][$k]) && $_POST['defaultInvoiceBook'][$k] != '') {
                        $defaultInvoices = json_decode($_POST['defaultInvoiceBook'][$k], true);
                        $generalLedgers[$k]['invoicebook'] = array_key_exists(0, $defaultInvoices) ? $defaultInvoices : array($defaultInvoices);  
                    }

                    if (isset($_POST['gl_metas'][$k]) && $_POST['gl_metas'][$k] != '') { 
                        $glMetas = json_decode(html_entity_decode($_POST['gl_metas'][$k], ENT_QUOTES, 'UTF-8'), true);
                        if (is_array($glMetas)) {
                            $generalLedgers[$k] = array_merge($generalLedgers[$k], $glMetas);
                        }
                    }

                    if (isset($_POST['accountMeta'][$k][$accountId])) {
                        $accountMetaDatas = Input::param($_POST['accountMeta'][$k][$accountId]);
                        $accountSegmentShortCode = $accountSegmentName = '';

                        foreach ($accountMetaDatas as $metaKey => $metaValue) {
                            if (strpos($metaKey, '_segmentCode') === false && strpos($metaKey, '_segmentSeparator') === false && strpos($metaKey, '_segmentReplaceValue') === false) {

                                $generalLedgers[$k][$metaKey] = $metaValue;

                                if (array_key_exists($metaKey.'_segmentCode', $accountMetaDatas)) {

                                    $segCode = $accountMetaDatas[$metaKey.'_segmentCode'];
                                    $segSeparator = $accountMetaDatas[$metaKey.'_segmentSeparator'];

                                    if ($segCode) {
                                        $metaValueExp = explode('|', $accountMetaDatas[$metaKey.'_segmentCode']);
                                        $segmentCode = $metaValueExp[0];
                                        $segmentName = $metaValueExp[1];
                                        $generalLedgers[$k][$metaKey.'_segmentCode'] = $segmentCode;
                                        $generalLedgers[$k][$metaKey.'_segmentName'] = $segmentName;
                                        $accountSegmentName .= $segSeparator.$segmentName;
                                    } else {
                                        $segmentCode = $accountMetaDatas[$metaKey.'_segmentReplaceValue'];
                                        $segmentName = $segmentCode;
                                        $generalLedgers[$k][$metaKey.'_segmentCode'] = '';
                                        $generalLedgers[$k][$metaKey.'_segmentName'] = '';
                                    }

                                    $accountSegmentShortCode .= $segSeparator.$segmentCode;
                                }
                            }
                        }

                        if ($accountSegmentShortCode) {
                            $generalLedgers[$k]['accountsegmentshortcode'] = $accountSegmentShortCode;
                        }

                        if ($accountSegmentName) {
                            $generalLedgers[$k]['accountsegmentname'] = $accountSegmentName;
                        }
                    }
                }
            }
        }

        $bookTypeId = Input::postCheck('glBookTypeId') ? Input::post('glBookTypeId') : '2';

        $generalLedgerParams = array(
           'bookTypeId' => $bookTypeId,
           'bookDate' => $bookDate,
           'generalLedgerBookParams'=> array(
                'bookTypeId' => $bookTypeId,
                'bookDate' => $bookDate,
                'bookNumber' => Input::postCheck('hidden_glbookNumber') ? Input::post('hidden_glbookNumber') : Input::post('glbookNumber'), 
                'objectId' => (Input::isEmpty('hidden_globject') == false) ? Input::post('hidden_globject') : '20000',
                'description' => Input::postCheck('gldescription') ? Input::post('gldescription') : Input::post('hidden_gldescription'),
                'description2' => Input::post('gldescription2'),
                'generalLedgerBookDtls' => $generalLedgers
            )
        );
        
        $command = Config::getFromCache('FIN_GLINSERT_DATE') == '1' ? 'GL_BOK_008' : 'GL_BOK_010';
        $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, $command, $generalLedgerParams);

        if ($result['status'] == 'success') {
            $response = array('status' => 'success', 'message' => Lang::line('msg_save_success'));
        } else {
            $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }

        return $response;
    }

    public function getCustomerRowModel($customerId) {
        return $this->db->GetRow("SELECT CUSTOMER_ID, CUSTOMER_CODE, CUSTOMER_NAME FROM VW_GL_CUSTOMER WHERE CUSTOMER_ID = ".$this->db->Param(0), array($customerId));
    }

    public function getGlEntryFromParamConfigModel($connectType) {

        $processId   = Input::post('processMetaDataId');
        $dataViewId  = Input::post('dataViewId');
        $selectedRow = $_POST['selectedRow'];

        $data = $this->db->GetAll("
            SELECT 
                LOWER(VIEW_FIELD_PATH) AS VIEW_FIELD_PATH, 
                LOWER(INPUT_PARAM_PATH) AS INPUT_PARAM_PATH, 
                DEFAULT_VALUE 
            FROM META_DM_TRANSFER_PROCESS 
            WHERE MAIN_META_DATA_ID = " . $this->db->Param(0) . " 
                AND PROCESS_META_DATA_ID = " . $this->db->Param(1), 
            array($dataViewId, $processId)
        );

        if ($data && is_array($selectedRow)) {

            $param = array();
            
            if (Input::postCheck('isMulti') == false) {
                
                if (!array_key_exists(1, $selectedRow)) {

                    foreach ($data as $row) {

                        if ($row['DEFAULT_VALUE'] != '') {

                            $param[$row['INPUT_PARAM_PATH']] = $row['DEFAULT_VALUE'];
                            Mdgl::$getDefaultValues[$row['INPUT_PARAM_PATH']] = $row['DEFAULT_VALUE'];

                        } elseif (isset($selectedRow[0][$row['VIEW_FIELD_PATH']])) {   

                            $param[$row['INPUT_PARAM_PATH']] = $selectedRow[0][$row['VIEW_FIELD_PATH']];
                        }
                    }

                } else {

                    foreach ($data as $row) {

                        if ($row['DEFAULT_VALUE'] != '') {

                            $param[$row['INPUT_PARAM_PATH']] = $row['DEFAULT_VALUE'];
                            Mdgl::$getDefaultValues[$row['INPUT_PARAM_PATH']] = $row['DEFAULT_VALUE'];

                        } elseif (isset($selectedRow[0][$row['VIEW_FIELD_PATH']]) && $row['VIEW_FIELD_PATH'] == 'accountid') {   

                            $param['accountIds'] = explode(',', Input::param(Arr::implodeKeyNonUniq(',', $selectedRow, $row['VIEW_FIELD_PATH'], true)));
                            
                        } elseif (isset($selectedRow[0][$row['VIEW_FIELD_PATH']]) && $row['VIEW_FIELD_PATH'] == 'customerid') {   

                            $param['customerIds'] = explode(',', Input::param(Arr::implodeKeyNonUniq(',', $selectedRow, $row['VIEW_FIELD_PATH'], true)));
                            
                        } elseif (isset($selectedRow[0][$row['VIEW_FIELD_PATH']])) {   

                            $param[$row['INPUT_PARAM_PATH']] = explode(',', Input::param(Arr::implodeKeyNonUniq(',', $selectedRow, $row['VIEW_FIELD_PATH'], true)));
                        }
                    }
                }
                
            } else {
                
                foreach ($data as $row) {

                    if ($row['DEFAULT_VALUE'] != '') {

                        $param[$row['INPUT_PARAM_PATH']] = $row['DEFAULT_VALUE'];
                        Mdgl::$getDefaultValues[$row['INPUT_PARAM_PATH']] = $row['DEFAULT_VALUE'];

                    } elseif (isset($selectedRow[0][$row['VIEW_FIELD_PATH']])) {   
                        
                        if ($row['VIEW_FIELD_PATH'] == 'id') {
                            
                            $param[$row['INPUT_PARAM_PATH']] = Input::param(Arr::implodeKeyNonUniq(',', $selectedRow, $row['VIEW_FIELD_PATH'], true));
                            
                        } elseif ($row['VIEW_FIELD_PATH'] == 'accountid') {
                            
                            $param['accountIds'] = Input::param(Arr::implodeKeyNonUniq(',', $selectedRow, $row['VIEW_FIELD_PATH'], true));
                            
                        } elseif ($row['VIEW_FIELD_PATH'] == 'customerid') {
                            
                            $param['customerIds'] = Input::param(Arr::implodeKeyNonUniq(',', $selectedRow, $row['VIEW_FIELD_PATH'], true));
                            
                        } else {
                            $param[$row['INPUT_PARAM_PATH']] = $selectedRow[0][$row['VIEW_FIELD_PATH']];
                        }
                    }
                }
            }
            
            if (!isset($param['processid'])) {
                $param['rows'] = $selectedRow;
            }
            
            if (Input::post('isMulti') == '1') {
                $param['isMulti'] = '1';
                $param['totalDebitAmount'] = Input::post('totalDebitAmount');
                $param['totalCreditAmount'] = Input::post('totalCreditAmount');               
            }    
            
            if (Input::isEmpty('glTemplateId') == false) {
                $param['_templateId'] = Input::post('glTemplateId');
            }
            
            $configWsUrl = Config::getFromCache('heavyServiceAddress');
        
            if ($configWsUrl && @file_get_contents($configWsUrl, false, stream_context_create(array('http' => array('timeout' => 2))))) {
                $serviceAddress = $configWsUrl;
            } else {
                $serviceAddress = self::$gfServiceAddress;
            }

            $result = $this->ws->runResponse($serviceAddress, 'getTemplateFromListBP', $param);
            
            return $result;
        }

        return array('status' => 'error', 'message' => 'Error', 'text' => 'Тохиргоог гүйцэд хийнэ үү');
    }
    
    public function getMultiConnectGLModel($connectType) {

        $processId    = Input::post('processMetaDataId');
        $dataViewId   = Input::post('dataViewId');
        $selectedRows = $_POST['selectedRow'];

        $data = $this->db->GetAll("
            SELECT 
                LOWER(VIEW_FIELD_PATH) AS VIEW_FIELD_PATH, 
                LOWER(INPUT_PARAM_PATH) AS INPUT_PARAM_PATH, 
                DEFAULT_VALUE 
            FROM META_DM_TRANSFER_PROCESS 
            WHERE MAIN_META_DATA_ID = " . $this->db->Param(0) . " 
                AND PROCESS_META_DATA_ID = " . $this->db->Param(1), 
            array($dataViewId, $processId) 
        );

        if ($data && is_array($selectedRows)) {
            
            $processId = $objectId = null;
            $param = array();
            $selectedRow = $selectedRows[0];
            
            foreach ($data as $row) {

                if ($row['DEFAULT_VALUE'] != '') {
                    
                    $lowerPath = strtolower($row['INPUT_PARAM_PATH']);
                    $param[$lowerPath] = $row['DEFAULT_VALUE'];
                    
                    if ($lowerPath == 'processid') {
                        $processId = $row['DEFAULT_VALUE'];
                    } elseif ($lowerPath == 'objectid') {
                        $objectId = $row['DEFAULT_VALUE'];
                    }
                    
                    Mdgl::$getDefaultValues[$lowerPath] = $row['DEFAULT_VALUE'];

                } elseif (isset($selectedRow['id']) && $row['VIEW_FIELD_PATH'] == 'id' && $connectType == 1) {
                            
                    $param['id'] = Input::param(Arr::implodeKeyNonUniq(',', $selectedRows, 'id', true));

                } elseif (isset($selectedRow[$row['VIEW_FIELD_PATH']])) {   

                    $param[$row['INPUT_PARAM_PATH']] = $selectedRow[$row['VIEW_FIELD_PATH']];

                } 
            }
            
            if (Input::post('isMulti') == '1') {
                
                $param['isMulti'] = '1';
                $param['totalDebitAmount'] = Input::post('totalDebitAmount');
                $param['totalCreditAmount'] = Input::post('totalCreditAmount');                
            }
            
            if (!isset($processId)) {
                $param['rows'] = $selectedRows;
            }
            
            $configWsUrl = Config::getFromCache('heavyServiceAddress');
        
            if ($configWsUrl && @file_get_contents($configWsUrl, false, stream_context_create(array('http' => array('timeout' => 2))))) {
                $serviceAddress = $configWsUrl;
            } else {
                $serviceAddress = self::$gfServiceAddress;
            }

            $result = $this->ws->runResponse($serviceAddress, 'getTemplateFromListBP', $param);
            
            $result['processId'] = $processId;
            $result['objectId'] = $objectId;
            $result['bookTypeId'] = $selectedRow['booktypeid'];
            
            return $result;
        }

        return array('status' => 'error', 'message' => 'Error', 'text' => 'Тохиргоо хийнэ үү');
    }

    public function defaultFillPathModel() {
        
        $metaDataId = $this->db->GetOne("SELECT meta_data_id FROM meta_data WHERE lower(meta_data_code) = lower('REVENUE_EXPENSE_END_BALANCE')");
        $fillPath = $this->db->GetAll("SELECT FIELD_PATH, PARAM_NAME, DEFAULT_VALUE FROM META_GROUP_CONFIG WHERE MAIN_META_DATA_ID = $metaDataId AND IS_CRITERIA = 1");
        $fillParam = array();
        
        if ($fillPath) {
            
            foreach ($fillPath as $dk => $dvalue) {
                
                $defaultValue = Str::lower($dvalue['DEFAULT_VALUE']);
                $fieldPath = Str::lower($dvalue['FIELD_PATH']);

                switch ($defaultValue) {
                    case 'sysdate':
                        $fillParam[$fieldPath] = Date::currentDate('Y-m-d');
                        break;
                    case 'sessionuserkeydepartmentid':
                        $sessionUserKeyDepartment = $this->db->GetRow("
                            SELECT 
                                ORG.DEPARTMENT_ID,
                                ORG.DEPARTMENT_NAME,
                                ORG.DEPARTMENT_CODE
                            FROM UM_USER UM 
                                INNER JOIN ORG_DEPARTMENT ORG ON ORG.DEPARTMENT_ID = UM.DEPARTMENT_ID 
                            WHERE UM.USER_ID = ".Ue::sessionUserKeyId());

                        if ($sessionUserKeyDepartment) {
                            $fillParam[$fieldPath] =  $sessionUserKeyDepartment['DEPARTMENT_ID'];
                            $fillParam[$fieldPath.'_displayfield'] = $sessionUserKeyDepartment['DEPARTMENT_CODE'];
                            $fillParam[$fieldPath.'_namefield'] = $sessionUserKeyDepartment['DEPARTMENT_NAME'];
                        }
                        else {
                            $fillParam[$fieldPath] =  '';
                            $fillParam[$fieldPath.'_namefield'] = '';
                            $fillParam[$fieldPath.'_displayfield'] = '';
                        }

                        break;
                    default:
                        $fillParam[$fieldPath] = $defaultValue;
                        break;
                }
            }
        }
        
        return $fillParam;
    }

    public function callBankRangeGlEntryModel() {

        parse_str($_POST['param'], $params);
        
        $param = array(
            'bookTypeId' => Input::post('bookTypeId'),
            'bookDate' => Input::param($params['bookDate']),
            'bookNumber' => Input::param($params['bookNumber']),
            'objectId' => '20000',
            'accountId' => Input::param($_POST['selectedRow']['accountid']),
            'customerId' => Input::param($params['customerId_valueField']),
            'description' => Input::param($params['description']),
            'rate' => Number::decimal(Input::param($params['rate'])),
            'amount' => Number::decimal(Input::param($params['amount'])),
            'amountBase' => Number::decimal(Input::param($params['amountBase']))
        );

        $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'getGlTemplate', $param);

        if ($result['status'] == 'success') {
            $result = array('status' => $result['status'], 'data' => $result['result']);
        } else {
            $result = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }

        return $result;
    }

    public function getCurrencyIdByCodeModel($code = null) {
            
        if ($code) {
            $row = $this->db->GetRow("SELECT CURRENCY_ID FROM REF_CURRENCY WHERE LOWER(CURRENCY_CODE) = ".$this->db->Param(0), array(strtolower($code)));

            if ($row) {
                return $row['CURRENCY_ID'];
            }
        }
        return null;
    }

    public function runDeleteGlBpModel($id, $type) {
        $param = array(
            'id' => $id
        );
        $procesCode = $type === 'withdoc' ? 'generalLedgerBookParams_005' : 'generalLedgerBookParams_0055';

        $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, $procesCode, $param);

        if ($data['status'] == 'success') {
            return array('status' => 'success', 'message' => 'Амжилттай устгагдлаа.');
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
        }
    }  

    public function fiscalPeriodDepartmentCloseServiceModel() {
        
        $_POST['isHierarchy'] = '1';
        $res = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'closeOrOpen', Input::postData());                

        if ($res['status'] === 'error') {
            return $response = array('text' => 'Алдаа гарлаа: ' . $res['text'], 'status' => 'error');
        }

        return $response = array('status' => 'success', 'text' => 'Амжилттай хадгалагдлаа');
    }     
    
    public function saveMultiGlEntryModel() { 

        $generalLedgers = array();

        if (Input::postCheck('gl_accountId')) {
            
            $generalLedgersData = Input::post('gl_accountId');

            foreach ($generalLedgersData as $k => $accountId) {

                if ($accountId != '') {

                    $rowObjectId = Input::param($_POST['gl_objectId'][$k]);
                    $customerId = Input::param($_POST['gl_customerId'][$k]);
                    $expenseCenterId = Input::param(issetParam($_POST['gl_expenseCenterId'][$k]));
                    $currencyCode = strtolower(issetParam($_POST['gl_rate_currency'][$k]));

                    if ($currencyCode == 'mnt') {
                        $rate = 1;
                    } else {
                        $rate = Number::decimal(issetParam($_POST['gl_rate'][$k]));
                    }
                    
                    $generalLedgers[$k] = array(
                        'objectId' => ($rowObjectId != '' ? $rowObjectId : '20000'),
                        'subid' => Input::param($_POST['gl_subid'][$k]),
                        'accountId' => $accountId,
                        'processId' => Input::param($_POST['gl_processId'][$k]), 
                        'customerId' => $customerId, 
                        'expenseCenterId' => $expenseCenterId, 
                        'description' => issetParam($_POST['gl_rowdescription'][$k]), 
                        'rate' => $rate 
                    );
                    
                    if (issetParam($_POST['gl_cashflowsubcategoryid'][$k])) {
                        $generalLedgers[$k]['dimensionconfig'] = json_encode(['rows' => ['cashflowsubcategoryid' => 1]]);
                    }                    

                    if (isset($_POST['gl_metas'][$k]) && $_POST['gl_metas'][$k] != '') { 
                        $glMetas = json_decode(html_entity_decode($_POST['gl_metas'][$k], ENT_QUOTES, 'UTF-8'), true);
                        if (is_array($glMetas)) {
                            $generalLedgers[$k] = array_merge($generalLedgers[$k], $glMetas);
                        }
                    }

                    if (isset($_POST['accountMeta'][$k][$accountId])) {
                        $accountMetaDatas = Input::param($_POST['accountMeta'][$k][$accountId]);
                        $accountSegmentShortCode = $accountSegmentName = '';

                        foreach ($accountMetaDatas as $metaKey => $metaValue) {
                            if (strpos($metaKey, '_segmentCode') === false && strpos($metaKey, '_segmentSeparator') === false && strpos($metaKey, '_segmentReplaceValue') === false) {

                                $generalLedgers[$k][$metaKey] = $metaValue;

                                if (array_key_exists($metaKey.'_segmentCode', $accountMetaDatas)) {

                                    $segCode = $accountMetaDatas[$metaKey.'_segmentCode'];
                                    $segSeparator = $accountMetaDatas[$metaKey.'_segmentSeparator'];

                                    if ($segCode) {
                                        $metaValueExp = explode('|', $accountMetaDatas[$metaKey.'_segmentCode']);
                                        $segmentCode = $metaValueExp[0];
                                        $segmentName = $metaValueExp[1];
                                        $generalLedgers[$k][$metaKey.'_segmentCode'] = $segmentCode;
                                        $generalLedgers[$k][$metaKey.'_segmentName'] = $segmentName;
                                        $accountSegmentName .= $segSeparator.$segmentName;
                                    } else {
                                        $segmentCode = $accountMetaDatas[$metaKey.'_segmentReplaceValue'];
                                        $segmentName = $segmentCode;
                                        $generalLedgers[$k][$metaKey.'_segmentCode'] = '';
                                        $generalLedgers[$k][$metaKey.'_segmentName'] = '';
                                    }

                                    $accountSegmentShortCode .= $segSeparator.$segmentCode;
                                }
                            }
                        }

                        if ($accountSegmentShortCode) {
                            $generalLedgers[$k]['accountsegmentshortcode'] = $accountSegmentShortCode;
                        }

                        if ($accountSegmentName) {
                            $generalLedgers[$k]['accountsegmentname'] = $accountSegmentName;
                        }
                    }
                }
            }
        }
        
        $selectedRows = json_decode(Input::postNonTags('selectedRows'), true);
        $connectType = Input::post('glConnectType');
        
        $processMetaDataId = Input::post('processMetaDataId');
        $dataViewId = Input::post('dataViewId');
        $firstRow = $selectedRows[0];
        
        $idPath = self::getGlConnectIdsInputModel($dataViewId, $processMetaDataId);
        
        if (array_key_exists($idPath, $firstRow)) {
            $ids = Arr::implodeKeyNonUniq(',', $selectedRows, $idPath, true);
        } else {
            $ids = Arr::implodeKeyNonUniq(',', $selectedRows, 'id', true);
        }
        
        $glHeader = array(
            'id'         => $ids, 
            'bookTypeId' => Input::post('bookTypeId'), 
            'objectId'   => Input::post('objectId'), 
            'processId'  => Input::post('processId'), 
            'isOneBook'  => 0
        );
        
        $generalLedgerParams = $glHeader;
        $generalLedgerParams['generalLedgerBookDtls'] = $generalLedgers;
        
        if ($connectType == '3') {
            $generalLedgerParams['isOneBook']   = 1;
            $generalLedgerParams['bookDate']    = Input::postCheck('glbookDate') ? Input::post('glbookDate') : Input::post('hidden_glbookDate');
            $generalLedgerParams['description'] = Input::postCheck('gldescription') ? Input::post('gldescription') : Input::post('hidden_gldescription');
            $generalLedgerParams['description2'] = Input::post('gldescription2');
        }
        
        if ($connectType == '2' || $connectType == '3') {
            $generalLedgerParams['rows'] = $selectedRows;
        }
        
        if (Input::isEmpty('glTemplateId') == false) {
            $generalLedgerParams['_templateId'] = Input::post('glTemplateId');
        }
        
        $configWsUrl = null; //Config::getFromCache('heavyServiceAddress');
        
        if ($configWsUrl && @file_get_contents($configWsUrl)) {
            $serviceAddress = $configWsUrl;
        } else {
            $serviceAddress = self::$gfServiceAddress;
        }
        
        if (Input::isEmpty('hidden_getDefaultValues') == false) {
            
            $getDefaultValues = json_decode($_POST['hidden_getDefaultValues'], true);
            
            if (is_countable($getDefaultValues) && count($getDefaultValues)) {
                
                $getDefaultValues = Arr::changeKeyLower($getDefaultValues);
                $glHeader = Arr::changeKeyLower($glHeader);
                
                foreach ($getDefaultValues as $key => $val) {
                    if (!array_key_exists($key, $glHeader)) {
                        $generalLedgerParams[$key] = $val;
                    }
                }
            }
        }
        
        $result = $this->ws->runSerializeResponse($serviceAddress, 'saveTemplateFromListBP', $generalLedgerParams);

        if ($result['status'] == 'success') {
            
            if (array_key_exists('result', $result) && is_countable($result['result']) && count($result['result']) > 0) {
                $response = array('status' => 'info', 'message' => 'Доорхи алдаа гарлаа!', 'resultList' => $result['result']);
            } else {
                $response = array('status' => 'success', 'message' => $this->lang->line('msg_save_success'));
            }
            
        } else {
            $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }

        return $response;
    }
    
    public function checkAccountRowBpMetaModel($row, $k) {
        
        $isMeta = $isProcess = false;
        $expenseCenterControl = '';
        
        if (isset(Mdgl::$loadAccount[$row['accountid']])) {
            
            $result    = Mdgl::$loadAccount[$row['accountid']]['result'];
            $isMeta    = Mdgl::$loadAccount[$row['accountid']]['isMeta'];
            $isProcess = Mdgl::$loadAccount[$row['accountid']]['isProcess'];
            
        } else {
            
            if (Config::getFromCache('CONFIG_GL_ACCOUNT_PARENT_ID')) {
            
                $join = 'INNER JOIN FIN_ACCOUNT T2 ON (
                        CONF.ACCOUNT_ID = COALESCE(T2.PARENT_ID, T2.ACCOUNT_ID) 
                        OR CONF.ACCOUNT_TYPE_ID = T2.ACCOUNT_TYPE_ID 
                    ) '; 
                $accountAlias = 'T2';

            } else {
                $join = '';
                $accountAlias = 'CONF';
            }
        
            $sql = "SELECT LOWER(CONF.FIELD_PATH) AS FIELD_PATH, CONF.IS_REQUIRED FROM FIN_ACCOUNT_GL_CONFIG CONF ".$join;

            $result = $this->db->GetAll($sql." WHERE $accountAlias.ACCOUNT_ID = ".$row['accountid']." AND (CONF.CONFIG_TYPE IS NULL OR CONF.CONFIG_TYPE <> 2)");

            if ($result) {

                $isMeta = true;

            } elseif (isset($row['accounttypeid']) && $row['accounttypeid'] != '') {

                $result = $this->db->GetAll($sql." WHERE CONF.ACCOUNT_TYPE_ID = ".$row['accounttypeid']." AND (CONF.CONFIG_TYPE IS NULL OR CONF.CONFIG_TYPE <> 2)");

                if ($result) {
                    $isMeta = true;
                }
            }

            if ($isMeta == false) {

                $result = $this->db->GetAll($sql." WHERE (CONF.CONFIG_TYPE IS NULL OR CONF.CONFIG_TYPE <> 2) AND CONF.CO_A_GROUP_ID IN (SELECT CO_A_GROUP_ID FROM FIN_ACCOUNT_CO_A_GROUP_MAP WHERE ACCOUNT_ID = ".$row['accountid'].")");

                if ($result) {
                    $isMeta = true;
                }
            }

            if ($row['usedetail'] == '1' && $row['rowislock'] != '1' && $row['objectid'] != '') {
                $isProcess = true;
            }
            
            Mdgl::$loadAccount[$row['accountid']]['result'] = $result;
            Mdgl::$loadAccount[$row['accountid']]['isMeta'] = $isMeta;
            Mdgl::$loadAccount[$row['accountid']]['isProcess'] = $isProcess;
        }
        
        if (Config::getFromCache('CONFIG_GL_ROW_EXPENSE_CENTER') && $isMeta) {
            
            foreach ($result as $resultRow) {
                
                if ($resultRow['FIELD_PATH'] == Mdgl::$expenseCenterMetaDataCode) { 
                    $controlConfig = array('GROUP_PARAM_CONFIG_TOTAL' => '0', 'GROUP_CONFIG_PARAM_PATH' => NULL, 'GROUP_CONFIG_LOOKUP_PATH' => NULL, 'GROUP_CONFIG_PARAM_PATH_GROUP' => NULL, 'GROUP_CONFIG_FIELD_PATH_GROUP' => NULL, 'GROUP_CONFIG_FIELD_PATH' => NULL, 'GROUP_CONFIG_GROUP_PATH' => NULL, 'IS_MULTI_ADD_ROW' => '0', 'IS_MULTI_ADD_ROW_KEY' => '0', 'META_DATA_CODE' => 'expenseCenterId', 'LOWER_PARAM_NAME' => 'expensecenterid', 'META_DATA_NAME' => 'Хариуцлагын төв', 'LABEL_NAME' => 'Хариуцлагын төв', 'DESCRIPTION' => NULL, 'ATTRIBUTE_ID_COLUMN' => NULL, 'ATTRIBUTE_CODE_COLUMN' => NULL, 'ATTRIBUTE_NAME_COLUMN' => NULL, 'IS_SHOW' => '1', 'IS_REQUIRED' => $resultRow['IS_REQUIRED'], 'DEFAULT_VALUE' => NULL, 'RECORD_TYPE' => NULL, 'LOOKUP_META_DATA_ID' => '1462253560044', 'LOOKUP_TYPE' => 'popup', 'CHOOSE_TYPE' => 'single', 'DISPLAY_FIELD' => NULL, 'VALUE_FIELD' => NULL, 'ID' => '1485492847774873', 'PARENT_ID' => NULL, 'PARAM_REAL_PATH' => 'expenseCenterId', 'NODOT_PARAM_REAL_PATH' => 'expenseCenterId', 'META_TYPE_CODE' => 'long', 'TAB_NAME' => NULL, 'SIDEBAR_NAME' => NULL, 'FEATURE_NUM' => NULL, 'IS_SAVE' => NULL, 'FILE_EXTENSION' => NULL, 'PATTERN_TEXT' => NULL, 'PATTERN_NAME' => NULL, 'GLOBE_MESSAGE' => NULL, 'IS_MASK' => NULL, 'COLUMN_WIDTH' => NULL, 'COLUMN_AGGREGATE' => NULL, 'SEPARATOR_TYPE' => NULL, 'GROUP_LOOKUP_META_DATA_ID' => NULL, 'IS_BUTTON' => '1', 'COLUMN_COUNT' => NULL, 'MAX_VALUE' => NULL, 'MIN_VALUE' => NULL, 'IS_SHOW_ADD' => NULL, 'IS_SHOW_DELETE' => NULL, 'IS_SHOW_MULTIPLE' => NULL, 'LOOKUP_KEY_META_DATA_ID' => NULL, 'IS_REFRESH' => '0', 'FRACTION_RANGE' => NULL, 'GROUPING_NAME' => NULL, 'SEGMENT_ID' => null, 'SEPRATOR_CHAR' => null, 'REPLACE_VALUE' => null, 'PLACEHOLDER_NAME' => null);
                    $expenseCenterControlArr = Mdgl::visibleReplacerForGl($row, $controlConfig, $row, $k);
                    $expenseCenterControl = $expenseCenterControlArr['input'];
                    break;
                }
            }
        }
        
        return array('isMeta' => $isMeta, 'isProcess' => $isProcess, 'expenseCenterControl' => $expenseCenterControl);
    }
    
    public function getAccountFullExpressionModel($accountId) {
        
        $accountIdPh = $this->db->Param(0);
        
        $exp = $this->db->GetOne("
            SELECT 
                FULL_EXPRESSION 
            FROM FIN_ACCOUNT_GL_CONFIG_EXP 
            WHERE ACCOUNT_ID = $accountIdPh 
                OR CO_A_GROUP_ID IN ( 
                    SELECT 
                        CO_A_GROUP_ID 
                    FROM FIN_ACCOUNT_CO_A_GROUP_MAP 
                    WHERE ACCOUNT_ID = $accountIdPh  
                )", 
            array($accountId)
        );
        
        return $exp;
    }
    
    public function getAccountSegmentListModel() {
        
        $dataViewId = Mdgl::$glBookDtlGroupMetaDataId;
        $dataViewIdPh = $this->db->Param(0);
        
        $data = $this->db->GetAll("
            SELECT 
                0 AS GROUP_PARAM_CONFIG_TOTAL,
                (
                    SELECT 
                        ".$this->db->listAgg('PARAM_PATH', '|', 'PARAM_PATH')."  
                    FROM META_GROUP_PARAM_CONFIG 
                    WHERE GROUP_META_DATA_ID = $dataViewIdPh   
                        AND LOOKUP_META_DATA_ID IS NOT NULL 
                        AND (IS_GROUP = 0 OR IS_GROUP IS NULL) 
                        AND LOWER(FIELD_PATH) = LOWER(MGC.FIELD_PATH) 
                ) AS GROUP_CONFIG_PARAM_PATH, 
                (
                    SELECT 
                        ".$this->db->listAgg('PARAM_META_DATA_CODE', '|', 'PARAM_PATH')."  
                    FROM META_GROUP_PARAM_CONFIG  
                    WHERE GROUP_META_DATA_ID = $dataViewIdPh  
                        AND LOOKUP_META_DATA_ID IS NOT NULL 
                        AND (IS_GROUP = 0 OR IS_GROUP IS NULL) 
                        AND LOWER(FIELD_PATH) = LOWER(MGC.FIELD_PATH) 
                ) AS GROUP_CONFIG_LOOKUP_PATH, 
                (
                    SELECT 
                        ".$this->db->listAgg('FIELD_PATH', '|', 'FIELD_PATH')."  
                    FROM META_GROUP_PARAM_CONFIG 
                    WHERE GROUP_META_DATA_ID = $dataViewIdPh  
                        AND LOOKUP_META_DATA_ID IS NOT NULL 
                        AND (IS_GROUP = 0 OR IS_GROUP IS NULL) 
                        AND LOWER(PARAM_PATH) = LOWER(MGC.FIELD_PATH) 
                ) AS GROUP_CONFIG_FIELD_PATH, 
                '' AS GROUP_CONFIG_GROUP_PATH, 
                null AS ATTRIBUTE_ID_COLUMN, 
                null AS ATTRIBUTE_CODE_COLUMN, 
                null AS ATTRIBUTE_NAME_COLUMN, 
                MGC.FIELD_PATH AS PARAM_REAL_PATH, 
                MGC.VISIBLE_CRITERIA,
                '' AS IS_REFRESH,
                '' AS DEFAULT_VALUE,
                MGC.LOOKUP_TYPE,
                MGC.LABEL_NAME,
                MGC.CHOOSE_TYPE,
                MGC.RECORD_TYPE,
                MGC.VALUE_FIELD,
                MGC.DISPLAY_FIELD,
                MGC.IS_SHOW,
                MGC.PARENT_ID, 
                MGC.LOOKUP_META_DATA_ID, 
                MGC.PARAM_NAME AS META_DATA_CODE, 
                MGC.LABEL_NAME AS META_DATA_NAME, 
                MGC.DATA_TYPE AS META_TYPE_CODE, 
                MGC.FILE_EXTENSION, 
                MGC.FRACTION_RANGE, 
                MGC.MIN_VALUE, 
                MGC.MAX_VALUE, 
                MFP.PATTERN_TEXT,
                MFP.PATTERN_NAME, 
                MFP.GLOBE_MESSAGE,
                MFP.IS_MASK, 
                0 AS IS_REQUIRED, 
                SC.ID AS SEGMENT_ID, 
                SC.SEPRATOR_CHAR, 
                SC.REPLACE_VALUE, 
                ".$this->db->IfNull('MGC.PLACEHOLDER_NAME', 'MGC.LABEL_NAME')." AS PLACEHOLDER_NAME 
            FROM META_GROUP_CONFIG MGC 
                INNER JOIN FIN_ACCOUNT_SEGMENT_CONFIG SC ON LOWER(SC.FIELD_PATH) = LOWER(MGC.FIELD_PATH) 
                LEFT JOIN META_FIELD_PATTERN MFP ON MFP.PATTERN_ID = MGC.PATTERN_ID 
            WHERE MGC.MAIN_META_DATA_ID = $dataViewIdPh  
                AND MGC.PARENT_ID IS NULL 
                AND MGC.DATA_TYPE <> 'group' 
            ORDER BY SC.ORDER_NUMBER ASC", array($dataViewId));
        
        return $data;
    }
    
    public function pushBpParamsModel() {
        
        $param       = json_decode($_POST['fillJsonParam'], true);
        $checkData   = $param['checkData'];
        $selectedRow = $param['selectedRow'];
        $recordId    = $param['recordId'];
        $isDebit     = $selectedRow['isdebit'];
        $objectId    = $selectedRow['objectid'];
        
        if (isset($checkData['DATAVIEW_ID']) && $checkData['DATAVIEW_ID'] != '') {
            
            $dvId = $checkData['DATAVIEW_ID'];
            
            if ($objectId == '20001') {
                $actionType = 'view';
            } else {
                $actionType = 'update';
            }
            
            $processList = $this->db->GetAll("
                WITH CONFIG_BP AS
                (
                    SELECT
                        T0.PROCESS_META_DATA_ID,
                        T2.GET_META_DATA_ID,
                        MD.META_DATA_CODE AS GET_META_DATA_CODE, 
                        T0.CRITERIA,
                        T0.BATCH_NUMBER
                    FROM META_DM_PROCESS_DTL T0
                        INNER JOIN META_DATA T1 ON T0.PROCESS_META_DATA_ID = T1.META_DATA_ID AND T1.META_TYPE_ID = 200101010000011 
                        INNER JOIN META_BUSINESS_PROCESS_LINK BP ON BP.META_DATA_ID = T1.META_DATA_ID AND BP.ACTION_TYPE = '$actionType' 
                        INNER JOIN META_DM_TRANSFER_PROCESS T2 ON T0.MAIN_META_DATA_ID = T2.MAIN_META_DATA_ID AND T0.PROCESS_META_DATA_ID = T2.PROCESS_META_DATA_ID 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = T2.GET_META_DATA_ID 
                    WHERE T0.MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                        AND T2.GET_META_DATA_ID IS NOT NULL
                    GROUP BY
                        T0.PROCESS_META_DATA_ID,
                        T2.GET_META_DATA_ID,
                        T0.CRITERIA,
                        T0.BATCH_NUMBER, 
                        MD.META_DATA_CODE 
                )
                SELECT
                    CONFIG_BP.PROCESS_META_DATA_ID,
                    CONFIG_BP.GET_META_DATA_ID,
                    CONFIG_BP.GET_META_DATA_CODE, 
                    CONFIG_BP.CRITERIA 
                FROM CONFIG_BP 
                WHERE 
                    (SELECT MIN(BATCH_NUMBER) FROM CONFIG_BP) IS NULL OR CONFIG_BP.BATCH_NUMBER IN (SELECT MIN(BATCH_NUMBER) FROM CONFIG_BP)
                GROUP BY 
                    CONFIG_BP.PROCESS_META_DATA_ID,
                    CONFIG_BP.GET_META_DATA_ID,
                    CONFIG_BP.GET_META_DATA_CODE,
                    CONFIG_BP.CRITERIA", array($dvId));
            
            if ($processList) {
                
                if (count($processList) > 1) {
                    
                    $param = array(
                        'systemMetaGroupId' => $dvId,
                        'showQuery' => 0, 
                        'ignorePermission' => 1,
                        'criteria' => array(
                            'id' => array(
                                array(
                                    'operator' => '=',
                                    'operand' => $recordId
                                )
                            )
                        )
                    );

                    $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

                    if ($result['status'] == 'success' && isset($result['result'][0])) {

                        unset($result['result']['aggregatecolumns']);
                        unset($result['result']['paging']);
                        
                        $row = $result['result'][0];

                        if (isset($row['pfnextstatuscolumn'])) {
                            unset($row['pfnextstatuscolumn']);
                        }

                        foreach ($processList as $process) {

                            $rules = Str::lower(trim($process['CRITERIA']));

                            if ($rules) {
                                
                                foreach ($row as $sk => $sv) {
                                    if (is_string($sv) && strpos($sv, "'") === false) {
                                        $sv = "'".Str::lower($sv)."'";
                                    } elseif (is_null($sv)) {
                                        $sv = "''";
                                    }
                                    $rules = preg_replace('/\b'.$sk.'\b/u', $sv, $rules);
                                }
                                
                                $rules = Mdmetadata::defaultKeywordReplacer($rules);
                                $rules = Mdmetadata::criteriaMethodReplacer($rules);

                                if (eval(sprintf('return (%s);', $rules))) {
                                    $processId = $process['PROCESS_META_DATA_ID'];

                                    if ($process['GET_META_DATA_ID']) {
                                        $getProcessId = $process['GET_META_DATA_ID'];
                                    }

                                    break;
                                }
                            }
                        }
                    }
                    
                } else {
                    $processId = $processList[0]['PROCESS_META_DATA_ID'];

                    if ($processList[0]['GET_META_DATA_ID']) {
                        $getProcessId = $processList[0]['GET_META_DATA_ID'];
                    }
                }
            }  
        } 
        
        if (!isset($processId)) {
            
            if ($isDebit == '1') {

                if (isset($checkData['DEBIT_EDIT_PROCESS_ID'])) {
                    $processId = $checkData['DEBIT_EDIT_PROCESS_ID'];
                } elseif (isset($checkData['DEBIT_PROCESS_ID'])) {
                    $processId = $checkData['DEBIT_PROCESS_ID'];
                }

            } else {

                if (isset($checkData['CREDIT_EDIT_PROCESS_ID'])) {
                    $processId = $checkData['CREDIT_EDIT_PROCESS_ID'];
                } elseif (isset($checkData['CREDIT_PROCESS_ID'])) {
                    $processId = $checkData['CREDIT_PROCESS_ID'];
                }
            }
        }
        
        if (isset($processId)) {
            
            if (!isset($getProcessId)) {
                
                $this->load->model('mdwebservice', 'middleware/models/');
                $bpRow = $this->model->getMethodIdByMetaDataModel($processId);
            
                $getProcessId = $bpRow['GETDATA_PROCESS_ID'];
            } 
            
            if ($objectId == '20001') { 
                $commandCode = '_itemGet';
            } elseif ($objectId == '20003') { 
                $commandCode = '_bankGet';
            } elseif ($objectId == '20004') { 
                $commandCode = '_cashGet';
            } elseif ($objectId == '20005') { 
                $commandCode = '_assetGet'; 
            } elseif ($objectId == '20006') { 
                $commandCode = '_payableGet'; 
            } elseif ($objectId == '20007') { 
                $commandCode = '_receivableGet'; 
            } 

            $param = array(
                'processId'    => $processId, 
                'getProcessId' => $getProcessId, 
                'id'           => $recordId
            );
            
            if (!$getProcessId) {
                
                if (isset($bpRow['GETDATA_PROCESS_CODE'])) {
                    $getProcessCode = $bpRow['GETDATA_PROCESS_CODE'];
                } else {
                    if ($objectId == '20001') { 
                        $getProcessCode = 'sdmdv058_004';
                    } elseif ($objectId == '20003') { 
                        $getProcessCode = 'cmInvBankDV_004';
                    } elseif ($objectId == '20004') { 
                        $getProcessCode = 'cmInvCashDV_004';
                    } elseif ($objectId == '20005') { 
                        $getProcessCode = 'FA_ASSET_BOOK_DV_004'; 
                    } elseif ($objectId == '20006') { 
                        $getProcessCode = 'AP_PAYABLE_HDR_DV_004'; 
                    } elseif ($objectId == '20007') { 
                        $getProcessCode = 'AR_RECEIVABLE_HDR_DV_004'; 
                    } 
                }
                
                $param['defaultProcessName'] = $getProcessCode;
            }

            $result = $this->ws->run('array', $commandCode, $param);

            if ($result['status'] == 'success' && isset($result['result'])) {
                $_POST['isFillArrayPostParam'] = 1;
                $_POST['fillJsonParam'] = $result['result'];
            }
            
            $_POST['metaDataId'] = $processId;
        }
        
        return true;
    }
    
    public function getDepartmentInfoByIdModel($id = null) {
        
        if (!$id) {
            $id = Ue::sessionUserKeyDepartmentId();
        }
        
        if ($id) {
            $row = $this->db->GetRow("SELECT DEPARTMENT_ID, DEPARTMENT_CODE, DEPARTMENT_NAME FROM ORG_DEPARTMENT WHERE DEPARTMENT_ID = ".$this->db->Param(0), array($id));
            return $row;
        }
        
        return array();
    }
    
    public function checkTransactionPermissionModel($bookId) {
        
        $check = Config::getFromCache('IS_USE_GL_TRANSACTION_PERMISSION');
        
        if ($check == '1') {
            
            $userId = Ue::sessionUserKeyId();
            $idPh = $this->db->Param(0); 
            
            $countPermission = (int) $this->db->GetOne("SELECT COUNT(ID) AS COUNT_ROW FROM UM_TRANSACTION_PERMISSION WHERE USER_ID = $idPh AND LOWER(PERMISSION_CODE) = 'all'", array($userId));

            if ($countPermission > 0) {
                return true;
            } else {
                
                $countPermission = (int) $this->db->GetOne("SELECT COUNT(ID) AS COUNT_ROW FROM UM_TRANSACTION_PERMISSION WHERE USER_ID = $idPh AND LOWER(PERMISSION_CODE) = 'own'", array($userId));
                
                if ($countPermission > 0) {
                    
                    $glCreatedUserId = $this->db->GetOne("SELECT CREATED_USER_ID FROM FIN_GENERAL_LEDGER_BOOK WHERE ID = $idPh", array($bookId));
                    
                    if ($glCreatedUserId == $userId) {
                        return true;
                    }
                }
            }
            
        } else {
            return true;
        }
        
        return false;
    }
    
    public function getGlConnectIdsInputModel($dvId, $processId) {
        
        $row = $this->db->GetRow("
            SELECT 
                LOWER(VIEW_FIELD_PATH) AS VIEW_FIELD_PATH  
            FROM META_DM_TRANSFER_PROCESS 
            WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                AND PROCESS_META_DATA_ID = ".$this->db->Param(1)."  
                AND INPUT_PARAM_PATH = ".$this->db->Param(2), 
            array($dvId, $processId, 'id'));
        
        if (isset($row['VIEW_FIELD_PATH'])) {
            return $row['VIEW_FIELD_PATH'];
        } else {
            return 'id';
        }
    }
    
    public function getGlEntryFromBudgetModel() {   
        
        $result = null;
        
        if (Input::isEmpty('rowStr') == false) {
            
            parse_str(Input::post('rowStr'), $rowArr);
        
            if (isset($rowArr['glBookId']) && $rowArr['glBookId']) {

                $result = self::getGlEntryModel(Input::param($rowArr['glBookId']));
                $result['isEdit'] = true;

            } else {

                $param = array(
                    'rowKey'         => Input::param($rowArr['rowKey']),
                    'activityKeyId'  => Input::param($rowArr['activityKeyId']),
                    'fiscalPeriodId' => Input::param($rowArr['fiscalPeriodId'])
                );

                $result = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'getBudgetGL', $param);
                $result['isEdit'] = false;
            }
            
        } elseif (Input::isEmpty('paramData') == false) {
            
            $param = Input::post('paramData');
            
            unset($param['dataViewId']);
            unset($param['dataViewCode']);
            unset($param['folderId']);
            
            $result = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'getTrailBalanceGL', $param);
            $result['isEdit'] = true;
            $result['isDvReload'] = true;
        }

        return $result;
    }
    
    public function checkCalculateRateModel() {
        
        $param = array(
            'departmentId' => Input::post('filterDepartmentId'), 
            'bookDate'     => Input::post('currencyRateDate'), 
            'currencyId'   => Input::post('currencyId')
        );
        
        $result = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'finCheckCalculateRate', $param);

        if ($result['status'] == 'success') {
            $result = array('status' => 'success');
        } else {
            $result = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }

        return $result;
    }

    public function getRate2ForAccountModel() {
        
        $param = array(
            'currencyId' => Ue::sessionSecondaryCurrencyId(),
            'fromCurrencyId' => Ue::sessionPrimaryCurrencyId(),
            'date' => Date::formatter(Input::post('date'), 'Y-m-d')
        );
        $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'RF_RTAC_005', $param);

        if ($result['status'] == 'success') {   
            return array(
                'status' => $result['status'],
                'result' => $result['result']
            );
        } else {
            return array(
                'status' => $result['status'],
                'message' => $this->ws->getResponseMessage($result)
            );
        }
    }    
    
    public function getGLAllDimensionPaths() {
        
        $data = $this->db->GetAll("SELECT LOWER(FIELD_PATH) AS FIELD_PATH FROM FIN_ACCOUNT_SEGMENT_CONFIG WHERE FIELD_PATH IS NOT NULL GROUP BY FIELD_PATH");
        $arr = array();
        
        foreach ($data as $row) {
            $arr[$row['FIELD_PATH']] = 1;
        }
        
        return $arr;
    }
    
    public function getCashMetaValuesByIdModel($id, $type) {
        
        $row = $this->db->GetRow("
            SELECT 
                CASH_FLOW_CATEGORY_ID,
                CASH_FLOW_SUB_CATEGORY_ID,
                CODE,
                NAME,
                IS_DEBIT
            FROM FIN_CASH_FLOW_SUB_CATEGORY 
            WHERE IS_DEBIT != ".$this->db->Param(0)." AND CASH_FLOW_SUB_CATEGORY_ID = ".$this->db->Param(1), 
            array($type, $id)
        );        
        
        return $row;
    }    

}