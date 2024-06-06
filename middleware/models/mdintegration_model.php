<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdintegration_model extends Model {
    
    private static $bankIdKhan = '500000';
    private static $isSaveLogId = false;
    private static $isClearing = false;
    private static $isCheckTransactionId = false;
    private static $saveLogId = null;
    private static $bankBillingCreatedUserId = null;
    private static $departmentId = null;
    private static $userNamePassword = null;
    private static $invoiceRecordIds = array();
    
    public function __construct() {
        parent::__construct();
        self::setKhanBankId();
    }
    
    public function setKhanBankId() {
        
        if ($bankId = Config::getFromCache('CONFIG_BANK_ID_KHAN')) {
            self::$bankIdKhan = $bankId;
        }
        
        return self::$bankIdKhan;
    }

    public function saveBilling($data) {

        try {
            
            $data['CREATED_USER_ID'] = self::$bankBillingCreatedUserId ? self::$bankBillingCreatedUserId : Ue::sessionUserKeyId(); 
            $data['CREATED_DATE']    = Date::currentDate();
            
            if (isset($data['DESCRIPTION'])) {
                $data['DESCRIPTION'] = $data['DESCRIPTION'];
            }
            
            if (self::$isClearing) {
                $tableName = 'CM_BANK_BILLING_CLEARING';
            } else {
                $tableName = 'CM_BANK_BILLING';
            }
            
            $this->db->AutoExecute($tableName, $data);

            $response = array('status' => 'success');

        } catch (ADODB_Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }

        return $response;
    }

    public function callBankService($processRow, $param) {
        
        // GolomtBank
        if ($processRow['WS_URL'] == 'https://middlecert:8080/') { 
            
            if ($processRow['CLASS_NAME'] == 'transfer') {
                
                return self::golomtBankTransfer($processRow, $param);
                
            } elseif ($processRow['CLASS_NAME'] == 'batch_transfer_inquiry') {
                
                return self::golomtBankBatchTransferInquiry($processRow, Arr::changeKeyLower($param));
                
            } elseif ($processRow['CLASS_NAME'] == 'statement') {
                
                set_time_limit(0);
                
                return self::golomtBankImportStatement($processRow, Arr::changeKeyLower($param));
            }            
        
        // KhanBank
        } elseif ($processRow['WS_URL'] == 'https://doob.world:6444/') { 
            
            if ($processRow['CLASS_NAME'] == 'v1/transfer/') {
                
                return self::khaanBankTransfer($processRow, $param);
                
            } elseif ($processRow['CLASS_NAME'] == 'v1/statement/') {
                
                set_time_limit(0);
                
                return self::khaanBankImportStatement($processRow, Arr::changeKeyLower($param));
            }
            
        } else {
            if ($processRow['WS_URL'] == 'https://localhost:8446/') {
                return self::golomtBankTransferV2($processRow, $param);
            } 
        }
        
    }
    
    public function golomtBankTransfer($processRow, $param) {
        
        try {
            
            $errorDesc = '';
            
            if (isset($param['invBankBookToBankMultiGet_dtl'])) {
                
                if (issetParam($param['departmentId'])) {
                    $departmentId = $param['departmentId'];
                } else {
                    $sessionUserKeyDepartmentId = Ue::sessionUserKeyDepartmentId();
                    $departmentId               = $sessionUserKeyDepartmentId ? $sessionUserKeyDepartmentId : Ue::sessionDepartmentId();
                }
                
                $bankUrl = Config::get('GOLOMT_BANK_API_URL', 'departmentId='.$departmentId.';');
                
                if ($bankUrl) {
                    $url = $bankUrl;
                } else {
                    $subUrl = Config::getFromCache('GOLOMT_BANK_SUB_URL');
                    $url    = $processRow['WS_URL'] . $subUrl;
                }
                
                $paramDtl   = $param['invBankBookToBankMultiGet_dtl'];
                $detailData = self::golomtGenerateDetailXml($paramDtl);
                
                $detailCount = $detailData['count'];
                
                if ($detailCount == 0) {
                    
                    $resultData = array();
                    $resultData['status']    = 'error';
                    $resultData['data']      = $param;
                    $resultData['result']    = '';
                    $resultData['text']      = 'Бүгд илгээгдсэн гүйлгээ байна.';
                    $resultData['errorCode'] = 'empty';
                    
                    return array('text' => 'error', 'data' => $resultData, 'message' => $resultData['text'], 'status' => $resultData['status']);
                }
                
                $currentDate = Date::currentDate('Y-m-d-H-i-s');
                $detailXml   = $detailData['xml'];
                $ids         = rtrim($detailData['id'], ',');
                $TxsCdId     = $detailData['TxsCdId'];
                $idIndexed   = $detailData['idIndexed'];
                $MsgId       = getUID();
            
                $OrganizationAnyBIC      = Config::get('OrganizationAnyBIC', 'departmentId='.$departmentId.';');
                $OrganizationVascoNumber = Config::get('OrganizationVascoNumber', 'departmentId='.$departmentId.';');
                
                if (issetParam($param['requestDate'])) {
                    $requestDate = $param['requestDate'] . Date::currentDate('\TH:i:s');
                } else {
                    $requestDate = Date::currentDate('Y-m-d\TH:i:s');
                }
                
                $params = '<?xml version="1.0" encoding="UTF-8"?>
                            <Document>
                                <GrpHdr>
                                    <MsgId>'. $MsgId .'</MsgId>
                                    <CreDtTm>'. $requestDate .'</CreDtTm>
                                    <TxsCd>'. $TxsCdId .'</TxsCd>
                                    <NbOfTxs>'. $detailCount .'</NbOfTxs>
                                    <CtrlSum>'. $detailData['sumAmount'] .'</CtrlSum>
                                    <InitgPty>
                                        <Id>
                                            <OrgId>
                                                <AnyBIC>'.$OrganizationAnyBIC.'</AnyBIC>
                                            </OrgId>
                                        </Id>
                                    </InitgPty>
                                    <Crdtl>
                                        <Lang>0</Lang>
                                        <LoginID>'.$OrganizationVascoNumber.'</LoginID>
                                        <RoleID>1</RoleID>
                                        <Pwds>
                                            <PwdType>3</PwdType>
                                            <Pwd>'. $param['Pwd'] .'</Pwd>
                                        </Pwds>
                                    </Crdtl>
                                </GrpHdr>
                                <PmtInf>
                                    <NbOfTxs>'. $detailCount .'</NbOfTxs>
                                    <CtrlSum>'. $detailData['sumAmount'] .'</CtrlSum>
                                    <ForT>F</ForT>
                                    <Dbtr>
                                        <Nm>'. $param['debitorName'] .'</Nm>
                                    </Dbtr>
                                    <DbtrAcct>
                                        <Id>
                                            <IBAN>'. $param['debitorIban'] .'</IBAN>
                                        </Id>
                                        <Ccy>'. $param['debitorCurrency'] .'</Ccy>
                                    </DbtrAcct>
                                    '.$detailXml.' 
                                </PmtInf>
                            </Document>';
                
                self::$isSaveLogId = true;
                self::createServiceMethodLog($url, 'golomtbankTransfer', $params, null, true);
                
                $this->load->model('mdobject', 'middleware/models/');
                
                $paramDtl[0]['wfmStatusId'] = '1580371792638938';
                
                foreach ($idIndexed as $rId => $rw) {
                            
                    $wfmAttrs = array(
                        array(
                            'newWfmStatusid' => '1580371792638938', 
                            'metaDataId'     => $rw['refStructureId'], 
                            'dataRow'        => array('id' => $rw['recordId'], 'wfmStatusId' => $rw['wfmStatusId']), 
                            'description'    => 'Дахин шалгах'
                        )
                    );
                    
                    $idIndexed[$rId]['wfmStatusId'] = '1580371792638938';

                    $changeStatusResult = self::setWfmStatusByBank($wfmAttrs);
                    
                    if (issetParam($changeStatusResult['status']) == 'error') {
                        
                        $resultData = array();
                        $resultData['status']    = 'error';
                        $resultData['data']      = $param;
                        $resultData['result']    = '';
                        $resultData['text']      = $changeStatusResult['message'];
                        $resultData['errorCode'] = 'empty';
                    
                        return array('text' => 'error', 'data' => $resultData, 'message' => $changeStatusResult['message'], 'status' => 'error');
                    }
                }
                
                $ch = curl_init();

                curl_setopt_array($ch, array(
                    CURLOPT_URL => $url,
                    CURLOPT_SSL_VERIFYHOST => 0, 
                    CURLOPT_SSL_VERIFYPEER => 0, 
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POST => true, 
                    CURLOPT_POSTFIELDS => $params,
                    CURLOPT_HEADER => false, 
                    CURLOPT_TIMEOUT => 180, //seconds
                    CURLOPT_HTTPHEADER => array(
                        'Cache-Control: no-cache',
                        'Content-Type: application/xml'
                    )
                ));       

                $result = curl_exec($ch);       
                $err = curl_error($ch);
                $errNo = curl_errno($ch);

                curl_close($ch);

                if ($err) { 
                    
                    self::createServiceMethodLog($url, 'golomtbankTransfer', null, $errNo.'-'.$err, true);
                    
                    if ($errNo == 28) { 
                        throw new Exception('Банкнаас хариу ирэхгүй удсан тул та банкны систем рүү орж гүйлгээг шалгана уу!'); 
                    } else {
                        throw new Exception('Банктай холбогдож чадсангүй!'); 
                    }
                } 
                
                self::createServiceMethodLog($url, 'golomtbankTransfer', null, $result, true);
                
                $resultData = array();
                $resultIntegration = Xml::createArray($result);
                $xmlResponseStatus = issetParam($resultIntegration['Document']['Header']['ResponseHeader']['Status']);
                
                if ($TxsCdId == '2003') {
                    
                    foreach ($idIndexed as $rId => $rw) {
                        
                        $cmBankTblName = (issetParam($rw['isInvoice']) == '1') ? 'CM_BANK_BILLING_BOOK' : 'CM_INV_BANK';
                        
                        $this->db->AutoExecute($cmBankTblName, array('MESSAGE_ID' => $MsgId), 'UPDATE', 'ID = '.$rId);
                    }
                    
                    if ($xmlResponseStatus == 'SUCCESS') {
                        
                        $resultData['data']   = issetParam($resultIntegration['Document']['Header']['ResponseHeader']);
                        $resultData['result'] = $resultData['data'];
                        $resultData['status'] = 'success';
                        $resultData['text']   = 'Амжилттай багцын гүйлгээг хүлээн авлаа. Гүйлгээний хариуг багцын лавлагаагаар шалгана уу.';
                    
                    } else {
                        
                        $resultData['status']    = 'error';
                        $resultData['data']      = issetParam($resultIntegration['Document']['Header']['ResponseHeader']);
                        $resultData['result']    = $resultData['data'];
                        $resultData['text']      = issetDefaultVal($resultIntegration['Document']['Body']['BatchTrn']['responsemsg'], Lang::line('msg_result_error'));
                        $resultData['errorCode'] = issetParam($resultIntegration['Document']['Body']['BatchTrn']['responsecode']);
                    }
                    
                } elseif ($detailCount == 1 && $xmlResponseStatus == 'SUCCESS') {
                    
                    $ResponseTrnId = issetParam($resultIntegration['Document']['Body']['TrnAddRs']['TrnIdentifier']['TrnId']);
                    $cmBankTblName = (issetParam($paramDtl[0]['isInvoice']) == '1') ? 'CM_BANK_BILLING_BOOK' : 'CM_INV_BANK';
                    
                    if ($TxsCdId == '1002' && $ResponseTrnId != '') {
                        
                        $this->db->AutoExecute($cmBankTblName, array('IS_TRANSFER' => 1, 'BANK_ERROR_CODE' => null, 'BANK_ERROR_MESSAGE' => null, 'TRANSACTION_ID' => $ResponseTrnId), 'UPDATE', 'ID = '.$ids);

                        $resultData['data']   = issetParam($resultIntegration['Document']['Header']['ResponseHeader']);
                        $resultData['result'] = $resultData['data'];
                        $resultData['status'] = 'success';
                        $resultData['text']   = 'Гүйлгээ амжилттай хийгдлээ.';

                        $wfmAttrs = array(
                            array(
                                'newWfmStatusid' => '1534844795469197', 
                                'metaDataId'     => $paramDtl[0]['refStructureId'], 
                                'dataRow'        => array('id' => $paramDtl[0]['recordId'], 'wfmStatusId' => $paramDtl[0]['wfmStatusId']), 
                                'description'    => $resultData['text']
                            )
                        );
                        
                        if ($paramDtl[0]['invoiceId']) {
                            
                            if (strpos($paramDtl[0]['invoiceId'], ',') === false) {
                                
                                $wfmAttrs[] = array(
                                    'newWfmStatusid' => issetDefaultVal($paramDtl[0]['iNewWfmStatusId'], '1529649557722966'), 
                                    'metaDataId'     => $paramDtl[0]['iRefStructureId'], 
                                    'dataRow'        => array('id' => $paramDtl[0]['invoiceId'], 'wfmStatusId' => $paramDtl[0]['iWfmStatusId']), 
                                    'description'    => $resultData['text']
                                );
                                
                            } else {
                                
                                $invoiceIds = explode(',', $paramDtl[0]['invoiceId']);
                                
                                foreach ($invoiceIds as $invId) {
                                    
                                    $invId = trim($invId);
                                    
                                    if ($invId) {
                                        
                                        $wfmAttrs[] = array(
                                            'newWfmStatusid' => issetDefaultVal($paramDtl[0]['iNewWfmStatusId'], '1529649557722966'), 
                                            'metaDataId'     => $paramDtl[0]['iRefStructureId'], 
                                            'dataRow'        => array('id' => $invId, 'wfmStatusId' => $paramDtl[0]['iWfmStatusId']), 
                                            'description'    => $resultData['text']
                                        );
                                    }
                                }
                            }
                        }

                        self::setWfmStatusByBank($wfmAttrs);
                        
                    } else {
                        
                        if (isset($resultIntegration['Document']['Body']['TrnAddRs']['TrnIdentifier'][0])) {
                            $detailResponseStatus = issetParam($resultIntegration['Document']['Body']['TrnAddRs']['TrnIdentifier'][0]['Status']);
                            $TrnId = issetDefaultVal($resultIntegration['Document']['Body']['TrnAddRs']['TrnIdentifier'][0]['TrnId'], null);
                        } else {
                            $detailResponseStatus = issetParam($resultIntegration['Document']['Body']['TrnAddRs']['TrnIdentifier']['Status']);
                            $TrnId = issetDefaultVal($resultIntegration['Document']['Body']['TrnAddRs']['TrnIdentifier']['TrnId'], null);
                        }
                        
                        if ($detailResponseStatus == 'false') {

                            $xmlErrorMsg = issetParam($resultIntegration['Document']['Body']['TrnAddRs']['TrnIdentifier'][0]['Error']);
                            
                            $this->db->AutoExecute($cmBankTblName, array('IS_TRANSFER' => 0, 'BANK_ERROR_CODE' => null, 'BANK_ERROR_MESSAGE' => $xmlErrorMsg, 'TRANSACTION_ID' => $TrnId), 'UPDATE', 'ID = '.$ids);

                            $resultData['data']   = issetParam($resultIntegration['Document']['Header']['ResponseHeader']);
                            $resultData['result'] = $resultData['data'];
                            $resultData['status'] = 'error';
                            $resultData['text']   = ($xmlErrorMsg ? $xmlErrorMsg : 'Гүйлгээ амжилтгүй боллоо.');

                            $wfmAttrs = array(
                                array(
                                    'newWfmStatusid' => '1534844795726298', 
                                    'metaDataId'     => $paramDtl[0]['refStructureId'], 
                                    'dataRow'        => array('id' => $paramDtl[0]['recordId'], 'wfmStatusId' => $paramDtl[0]['wfmStatusId']), 
                                    'description'    => $xmlErrorMsg
                                )
                            );
                            
                            if ($paramDtl[0]['invoiceId']) {
                            
                                if (strpos($paramDtl[0]['invoiceId'], ',') === false) {

                                    $wfmAttrs[] = array(
                                        'newWfmStatusid' => '1533536203222884', 
                                        'metaDataId'     => $paramDtl[0]['iRefStructureId'], 
                                        'dataRow'        => array('id' => $paramDtl[0]['invoiceId'], 'wfmStatusId' => $paramDtl[0]['iWfmStatusId']), 
                                        'description'    => $xmlErrorMsg
                                    );

                                } else {

                                    $invoiceIds = explode(',', $paramDtl[0]['invoiceId']);

                                    foreach ($invoiceIds as $invId) {

                                        $invId = trim($invId);

                                        if ($invId) {

                                            $wfmAttrs[] = array(
                                                'newWfmStatusid' => '1533536203222884', 
                                                'metaDataId'     => $paramDtl[0]['iRefStructureId'], 
                                                'dataRow'        => array('id' => $invId, 'wfmStatusId' => $paramDtl[0]['iWfmStatusId']), 
                                                'description'    => $xmlErrorMsg
                                            );
                                        }
                                    }
                                }
                            }

                            self::setWfmStatusByBank($wfmAttrs);

                        } else {
                            
                            $this->db->AutoExecute($cmBankTblName, array('IS_TRANSFER' => 1, 'BANK_ERROR_CODE' => null, 'BANK_ERROR_MESSAGE' => null, 'TRANSACTION_ID' => $TrnId), 'UPDATE', 'ID = '.$ids);

                            $resultData['data']   = issetParam($resultIntegration['Document']['Header']['ResponseHeader']);
                            $resultData['result'] = $resultData['data'];
                            $resultData['status'] = 'success';
                            $resultData['text']   = 'Гүйлгээ амжилттай хийгдлээ.';

                            $wfmAttrs = array(
                                array(
                                    'newWfmStatusid' => '1534844795469197', 
                                    'metaDataId'     => $paramDtl[0]['refStructureId'], 
                                    'dataRow'        => array('id' => $paramDtl[0]['recordId'], 'wfmStatusId' => $paramDtl[0]['wfmStatusId']), 
                                    'description'    => $resultData['text']
                                )
                            );
                            
                            if ($paramDtl[0]['invoiceId']) {
                            
                                if (strpos($paramDtl[0]['invoiceId'], ',') === false) {

                                    $wfmAttrs[] = array(
                                        'newWfmStatusid' => issetDefaultVal($paramDtl[0]['iNewWfmStatusId'], '1529649557722966'), 
                                        'metaDataId'     => $paramDtl[0]['iRefStructureId'], 
                                        'dataRow'        => array('id' => $paramDtl[0]['invoiceId'], 'wfmStatusId' => $paramDtl[0]['iWfmStatusId']), 
                                        'description'    => $resultData['text']
                                    );

                                } else {

                                    $invoiceIds = explode(',', $paramDtl[0]['invoiceId']);

                                    foreach ($invoiceIds as $invId) {

                                        $invId = trim($invId);

                                        if ($invId) {
                                            
                                            $wfmAttrs[] = array(
                                                'newWfmStatusid' => issetDefaultVal($paramDtl[0]['iNewWfmStatusId'], '1529649557722966'), 
                                                'metaDataId'     => $paramDtl[0]['iRefStructureId'], 
                                                'dataRow'        => array('id' => $invId, 'wfmStatusId' => $paramDtl[0]['iWfmStatusId']), 
                                                'description'    => $resultData['text']
                                            );
                                        }
                                    }
                                }
                            }

                            self::setWfmStatusByBank($wfmAttrs);
                        }
                    }
                    
                } elseif ($detailCount > 1 && $xmlResponseStatus == 'SUCCESS') {
                    
                    $detailResponses = $resultIntegration['Document']['Body']['TrnAddRs']['TrnIdentifier'];
                    $successCount = $failedCount = 0;
                    
                    foreach ($detailResponses as $detailResponse) {
                        
                        $cmBankTblName = (issetParam($idIndexed[$detailResponse['CdtrId']]['isInvoice']) == '1') ? 'CM_BANK_BILLING_BOOK' : 'CM_INV_BANK';
                        
                        if ($detailResponse['Status'] == 'true') {
                            
                            $this->db->AutoExecute($cmBankTblName, array('IS_TRANSFER' => 1, 'BANK_ERROR_CODE' => null, 'BANK_ERROR_MESSAGE' => null, 'TRANSACTION_ID' => issetDefaultVal($detailResponse['TrnId'], null)), 'UPDATE', 'ID = '.$detailResponse['CdtrId']);
                            
                            $successCount++;
                            
                            $wfmAttrs = array(
                                array(
                                    'newWfmStatusid' => '1534844795469197', 
                                    'metaDataId'     => $idIndexed[$detailResponse['CdtrId']]['refStructureId'], 
                                    'dataRow'        => array('id' => $idIndexed[$detailResponse['CdtrId']]['recordId'], 'wfmStatusId' => $idIndexed[$detailResponse['CdtrId']]['wfmStatusId']), 
                                    'description'    => 'Гүйлгээ амжилттай хийгдлээ'
                                )
                            );
                            
                            if ($idIndexed[$detailResponse['CdtrId']]['invoiceId']) {
                            
                                if (strpos($idIndexed[$detailResponse['CdtrId']]['invoiceId'], ',') === false) {

                                    $wfmAttrs[] = array(
                                        'newWfmStatusid' => issetDefaultVal($idIndexed[$detailResponse['CdtrId']]['iNewWfmStatusId'], '1529649557722966'), 
                                        'metaDataId'     => $idIndexed[$detailResponse['CdtrId']]['iRefStructureId'], 
                                        'dataRow'        => array('id' => $idIndexed[$detailResponse['CdtrId']]['invoiceId'], 'wfmStatusId' => $idIndexed[$detailResponse['CdtrId']]['iWfmStatusId']), 
                                        'description'    => 'Гүйлгээ амжилттай хийгдлээ'
                                    );

                                } else {

                                    $invoiceIds = explode(',', $idIndexed[$detailResponse['CdtrId']]['invoiceId']);

                                    foreach ($invoiceIds as $invId) {

                                        $invId = trim($invId);

                                        if ($invId) {
                                            
                                            $wfmAttrs[] = array(
                                                'newWfmStatusid' => issetDefaultVal($idIndexed[$detailResponse['CdtrId']]['iNewWfmStatusId'], '1529649557722966'), 
                                                'metaDataId'     => $idIndexed[$detailResponse['CdtrId']]['iRefStructureId'], 
                                                'dataRow'        => array('id' => $invId, 'wfmStatusId' => $idIndexed[$detailResponse['CdtrId']]['iWfmStatusId']), 
                                                'description'    => 'Гүйлгээ амжилттай хийгдлээ'
                                            );
                                        }
                                    }
                                }
                            }

                            self::setWfmStatusByBank($wfmAttrs);
                        
                        } else {
                            
                            $this->db->AutoExecute($cmBankTblName, array('IS_TRANSFER' => 0, 'BANK_ERROR_CODE' => null, 'BANK_ERROR_MESSAGE' => $detailResponse['Error'], 'TRANSACTION_ID' => issetDefaultVal($detailResponse['TrnId'], null)), 'UPDATE', 'ID = '.$detailResponse['CdtrId']);
                            $failedCount++;
                            
                            $wfmAttrs = array(
                                array(
                                    'newWfmStatusid' => '1534844795726298', 
                                    'metaDataId'     => $idIndexed[$detailResponse['CdtrId']]['refStructureId'], 
                                    'dataRow'        => array('id' => $idIndexed[$detailResponse['CdtrId']]['recordId'], 'wfmStatusId' => $idIndexed[$detailResponse['CdtrId']]['wfmStatusId']), 
                                    'description'    => $detailResponse['Error']
                                )
                            );
                            
                            if ($idIndexed[$detailResponse['CdtrId']]['invoiceId']) {
                            
                                if (strpos($idIndexed[$detailResponse['CdtrId']]['invoiceId'], ',') === false) {

                                    $wfmAttrs[] = array(
                                        'newWfmStatusid' => '1533536203222884', 
                                        'metaDataId'     => $idIndexed[$detailResponse['CdtrId']]['iRefStructureId'], 
                                        'dataRow'        => array('id' => $idIndexed[$detailResponse['CdtrId']]['invoiceId'], 'wfmStatusId' => $idIndexed[$detailResponse['CdtrId']]['iWfmStatusId']), 
                                        'description'    => $detailResponse['Error']
                                    );

                                } else {

                                    $invoiceIds = explode(',', $idIndexed[$detailResponse['CdtrId']]['invoiceId']);

                                    foreach ($invoiceIds as $invId) {

                                        $invId = trim($invId);

                                        if ($invId) {
                                            
                                            $wfmAttrs[] = array(
                                                'newWfmStatusid' => '1533536203222884', 
                                                'metaDataId'     => $idIndexed[$detailResponse['CdtrId']]['iRefStructureId'], 
                                                'dataRow'        => array('id' => $invId, 'wfmStatusId' => $idIndexed[$detailResponse['CdtrId']]['iWfmStatusId']), 
                                                'description'    => $detailResponse['Error']
                                            );
                                        }
                                    }
                                }
                            }
                            
                            self::setWfmStatusByBank($wfmAttrs);
                        }
                    }
                    
                    $resultData['data']   = issetParam($resultIntegration['Document']['Header']['ResponseHeader']);
                    $resultData['result'] = $resultData['data'];
                    $resultData['status'] = 'success';
                    $resultData['text']   = "Нийт <strong>$detailCount</strong> гүйлгээнээс<br /><strong>$successCount</strong> амжилттай <strong>$failedCount</strong> амжилтгүй.";

                } else {

                    $resultData['status']    = 'error';
                    $resultData['data']      = issetParam($resultIntegration['Document']['Header']['ResponseHeader']);
                    $resultData['result']    = $resultData['data'];
                    $resultData['text']      = (isset($resultIntegration['Document']['Body']['Error']['ErrorDetail']['ErrorDesc']) && $resultIntegration['Document']['Body']['Error']['ErrorDetail']['ErrorDesc']) ? $resultIntegration['Document']['Body']['Error']['ErrorDetail']['ErrorDesc'] : Lang::line('msg_result_error');
                    $resultData['errorCode'] = issetParam($resultIntegration['Document']['Body']['Error']['ErrorDetail']['ErrorCode']);
                    
                    if ($resultData['errorCode'] != 'INB0001') {
                        
                        $cmBankTblName = (issetParam($paramDtl[0]['isInvoice']) == '1') ? 'CM_BANK_BILLING_BOOK' : 'CM_INV_BANK';
                        
                        $this->db->AutoExecute($cmBankTblName, array('BANK_ERROR_CODE' => $resultData['errorCode'], 'BANK_ERROR_MESSAGE' => $resultData['text']), 'UPDATE', 'ID IN ('.$ids.')');

                        foreach ($paramDtl as $dtl) {

                            $wfmAttrs = array(
                                array(
                                    'newWfmStatusid' => '1534844795726298', 
                                    'metaDataId'     => $dtl['refStructureId'], 
                                    'dataRow'        => array('id' => $dtl['recordId'], 'wfmStatusId' => $dtl['wfmStatusId']), 
                                    'description'    => $resultData['text']
                                )
                            );

                            if ($dtl['invoiceId']) {

                                if (strpos($dtl['invoiceId'], ',') === false) {

                                    $wfmAttrs[] = array(
                                        'newWfmStatusid' => '1533536203222884', 
                                        'metaDataId'     => $dtl['iRefStructureId'], 
                                        'dataRow'        => array('id' => $dtl['invoiceId'], 'wfmStatusId' => $dtl['iWfmStatusId']), 
                                        'description'    => $resultData['text']
                                    );

                                } else {

                                    $invoiceIds = explode(',', $dtl['invoiceId']);

                                    foreach ($invoiceIds as $invId) {

                                        $invId = trim($invId);

                                        if ($invId) {

                                            $wfmAttrs[] = array(
                                                'newWfmStatusid' => '1533536203222884', 
                                                'metaDataId'     => $dtl['iRefStructureId'], 
                                                'dataRow'        => array('id' => $invId, 'wfmStatusId' => $dtl['iWfmStatusId']), 
                                                'description'    => $resultData['text']
                                            );
                                        }
                                    }
                                }
                            }

                            self::setWfmStatusByBank($wfmAttrs);
                        }
                    }
                }
            
            } else {
                
                $errorDesc = 'error';
                $resultData['status']    = 'error';
                $resultData['data']      = $param;
                $resultData['result']    = '';
                $resultData['text']      = 'Detail empty!';
                $resultData['errorCode'] = 'empty';
            }

        } catch (Exception $ex) {
            
            $errorDesc = 'error';
            
            $resultData['data'] = array();
            $resultData['text'] = $ex->getMessage();
            $resultData['status'] = 'error';
        }
        
        return array('text' => $errorDesc, 'data' => $resultData, 'message' => $resultData['text'], 'status' => $resultData['status']);
    }
    
    public function golomtGenerateDetailXml($param) {
        
        $rowCount = $sumAmount = $allEmptyCount = $allNotEmptyCount = $allSwiftCount = 0;
        $detail = $id = '';
        $idIndexedArr = array();
        
        foreach ($param as $row) {
            
            $isInvoice = (issetParam($row['isInvoice']) == '1') ? true : false;
            $recordId = $row['recordId'];
            
            if ($isInvoice) {

                $isTransfered = self::isTransferedCmBankBillingBook($recordId);

            } else {

                $isTransfered = self::isTransferedCmInvBank($recordId);
            }
            
            if ($isTransfered == false) {
                
                $detail .= '<CdtTrfTxInf>
                    <CdtrId>'. $recordId .'</CdtrId>
                    <Amt>
                        <InstdAmt>'. $row['amount'] .'</InstdAmt>
                        <InstdCcy>'. (issetParam($row['InstdCcy']) ? $row['InstdCcy'] : $row['creditorCurrency']) .'</InstdCcy>
                    </Amt>
                    <Cdtr>
                        <Nm>'. $row['creditorName'] .'</Nm>
                    </Cdtr>
                    <CdtrAcct>
                        <Id>
                            <IBAN>'. $row['creditorIban'] .'</IBAN>
                        </Id>
                        <Ccy>'. $row['creditorCurrency'] .'</Ccy>
                    </CdtrAcct>
                    <CdtrAgt>
                        <FinInstnId>
                            <BICFI>'. $row['bicfi'] .'</BICFI>
                            <BankCode>'. issetParam($row['bankcode']) .'</BankCode>
                            <BnkName>'. issetParam($row['bnkname']) .'</BnkName>
                            <PrpsCode>'. issetParam($row['prpscode']) .'</PrpsCode>
                            <BenAddr>'. issetParam($row['benaddr']) .'</BenAddr>
                            <ChrgOpt>'. issetParam($row['chrgopt']) .'</ChrgOpt>
                            <CntryCode>'. issetParam($row['cntrycode']) .'</CntryCode>
                        </FinInstnId>
                    </CdtrAgt>
                    <RmtInf>
                        <AddtlRmtInf>'. $row['description'] .'</AddtlRmtInf>
                    </RmtInf>
                </CdtTrfTxInf>';

                $id .= $recordId.',';

                if ($row['bicfi'] == 'GMT') {
                    $allEmptyCount++;
                }

                if ($row['bicfi'] != 'GMT') {
                    $allNotEmptyCount++;
                }

                if (issetParam($row['isSwift']) == '1') {
                    $allSwiftCount++;
                }

                $idIndexedArr[$recordId] = $row;
                
                $sumAmount += $row['amount'];
                
                $rowCount++;
                
                self::$invoiceRecordIds[$recordId] = $isInvoice ? 'CM_BANK_BILLING_BOOK' : 'CM_INV_BANK';
            }
        }
        
        if ($rowCount == 1 && $rowCount == $allSwiftCount) {
            $TxsCdId = '1007';
        } elseif ($rowCount == 1 && $rowCount == $allEmptyCount) {
            $TxsCdId = '1001';
        } elseif ($rowCount == 1 && $rowCount == $allNotEmptyCount) {
            $TxsCdId = '1002';
        } elseif ($rowCount <= 10) {
            $TxsCdId = '1003';
        } else {
            $TxsCdId = '2003';
        }
        
        return array('xml' => $detail, 'id' => $id, 'count' => $rowCount, 'sumAmount' => $sumAmount, 'TxsCdId' => $TxsCdId, 'idIndexed' => $idIndexedArr);
    }
    
    public function golomtBankBatchTransferInquiry($processRow, $param) {
        
        if (issetParam($param['departmentid'])) {
            $departmentId = $param['departmentid'];
        } else {
            $sessionUserKeyDepartmentId = Ue::sessionUserKeyDepartmentId();
            $departmentId               = $sessionUserKeyDepartmentId ? $sessionUserKeyDepartmentId : Ue::sessionDepartmentId();
        }
        
        $bankUrl = Config::get('GOLOMT_BANK_API_URL', 'departmentId='.$departmentId.';');
            
        if ($bankUrl) {
            
            $url = $bankUrl;
            
        } else {
            $subUrl = Config::getFromCache('GOLOMT_BANK_SUB_URL');
            $url    = $processRow['WS_URL'] . $subUrl;
        }

        $OrganizationAnyBIC        = Config::get('OrganizationAnyBIC', 'departmentId='.$departmentId.';');
        $OrganizationGolomtLoginId = Config::get('OrganizationGolomtLoginId', 'departmentId='.$departmentId.';');      
        
        $MsgId = $param['messageid'];
        
        $xmlParams = '<?xml version="1.0" encoding="UTF-8"?><Document><GrpHdr>
                <MsgId>'. getUID() .'</MsgId>
                <CreDtTm>'. Date::currentDate('Y-m-d\TH:i:s') .'</CreDtTm>
                <TxsCd>2005</TxsCd>
                <InitgPty>
                    <Id>
                        <OrgId>
                            <AnyBIC>'.$OrganizationAnyBIC.'</AnyBIC>
                        </OrgId>
                    </Id>
                </InitgPty>
                <Crdtl>
                    <Lang>0</Lang>
                    <LoginID>'.$OrganizationGolomtLoginId.'</LoginID>
                    <RoleID>1</RoleID>
                    <Pwds>
                        <PwdType>1</PwdType>
                        <Pwd></Pwd>
                    </Pwds>
                </Crdtl>
            </GrpHdr>
            <EnqInf>
                <MsgId>'.$MsgId.'</MsgId>
                <Type>1</Type>
            </EnqInf>
        </Document>';
        
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYHOST => 0, 
            CURLOPT_SSL_VERIFYPEER => 0, 
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POST => true, 
            CURLOPT_POSTFIELDS => $xmlParams,
            CURLOPT_HEADER => false, 
            CURLOPT_TIMEOUT => 180, //seconds
            CURLOPT_HTTPHEADER => array(
                'Cache-Control: no-cache',
                'Content-Type: application/xml'
            )
        ));      
        
        $output = curl_exec($ch);
        $err = curl_error($ch);
        
        curl_close($ch);

        self::createServiceMethodLog($url, 'golomtBankBatchTransferInquiry', $xmlParams, $output, true);
        
        if ($err) {
            
            $response = array('status' => 'error', 'code' => 'curl', 'message' => $err);
            
        } else {
            
            $batchTranTagName = (strpos($output, 'BacthTran') !== false) ? 'BacthTran' : 'BatchTran';
            
            $resultArray = Xml::createArray($output);
            $responseStatus = issetParam($resultArray['Document']['Header']['ResponseHeader']['Status']);
            
            if ($responseStatus == 'SUCCESS') {
            
                if (isset($resultArray['Document']['Tran'])) {
                    
                    $wfmAttrs = array();
                    
                    $refStructureId     = $param['refstructureid'];
                    $successWfmStatusId = $param['successstatusid'];
                    $errorWfmStatusId   = $param['unsuccessstatusid'];
                    
                    $iRefStructureId     = $param['irefstructureid'];
                    $iSuccessWfmStatusId = $param['isuccessstatusid'];
                    $iErrorWfmStatusId   = $param['iunsuccessstatusid'];
                    
                    $transactions       = $resultArray['Document']['Tran'];
                    
                    if (!array_key_exists(0, $transactions)) {
                        $transactions = array($transactions);
                    }
                    
                    foreach ($transactions as $row) {
                        
                        $TranStatus = issetParam($row['TranStatus']);
                        $CdtrId     = $row['CdtrId'];
                        $invRow     = $this->db->GetRow("
                            SELECT 
                                T0.ID, 
                                T0.WFM_STATUS_ID, 
                                T0.IS_TRANSFER, 
                                T1.SRC_RECORD_ID AS INVOICE_ID, 
                                T2.WFM_STATUS_ID AS I_WFM_STATUS_ID 
                            FROM CM_INV_BANK T0 
                                LEFT JOIN META_DM_RECORD_MAP T1 ON T1.TRG_RECORD_ID = T0.ID 
                                    AND T1.SRC_TABLE_NAME = 'FIN_INVOICE' 
                                    AND T1.TRG_TABLE_NAME = 'CM_INV_BANK' 
                                LEFT JOIN FIN_INVOICE T2 ON T2.INVOICE_ID = T1.SRC_RECORD_ID     
                            WHERE T0.ID = ".$this->db->Param(0), array($CdtrId));
                        
                        if (isset($invRow['ID'])) {
                            
                            if ($TranStatus == 'S') {

                                if ($invRow['IS_TRANSFER'] != '1') {

                                    $NtryRef = $row['NtryRef'];

                                    $this->db->AutoExecute('CM_INV_BANK', array('IS_TRANSFER' => 1, 'BANK_ERROR_CODE' => null, 'BANK_ERROR_MESSAGE' => null, 'TRANSACTION_ID' => $NtryRef), 'UPDATE', 'ID = '.$CdtrId);

                                    $wfmAttrs[] = array(
                                        'newWfmStatusid' => $successWfmStatusId, 
                                        'metaDataId'     => $refStructureId, 
                                        'dataRow'        => array('id' => $CdtrId, 'wfmStatusId' => $invRow['WFM_STATUS_ID']), 
                                        'description'    => 'Гүйлгээ амжилттай хийгдлээ'
                                    );
                                    
                                    if ($invRow['INVOICE_ID']) {
                                        
                                        $wfmAttrs[] = array(
                                            'newWfmStatusid' => $iSuccessWfmStatusId, 
                                            'metaDataId'     => $iRefStructureId, 
                                            'dataRow'        => array('id' => $invRow['INVOICE_ID'], 'wfmStatusId' => $invRow['I_WFM_STATUS_ID']), 
                                            'description'    => 'Гүйлгээ амжилттай хийгдлээ'
                                        );
                                    }
                                }

                            } else {
                                
                                $NtryRef = $row['NtryRef'];
                                $this->db->AutoExecute('CM_INV_BANK', array('IS_TRANSFER' => 0, 'BANK_ERROR_CODE' => null, 'BANK_ERROR_MESSAGE' => $NtryRef, 'TRANSACTION_ID' => null), 'UPDATE', 'ID = '.$CdtrId);
                                
                                $wfmAttrs[] = array(
                                    'newWfmStatusid' => $errorWfmStatusId, 
                                    'metaDataId'     => $refStructureId, 
                                    'dataRow'        => array('id' => $CdtrId, 'wfmStatusId' => $invRow['WFM_STATUS_ID']), 
                                    'description'    => $NtryRef
                                );
                                
                                if ($invRow['INVOICE_ID']) {
                                        
                                    $wfmAttrs[] = array(
                                        'newWfmStatusid' => $iErrorWfmStatusId, 
                                        'metaDataId'     => $iRefStructureId, 
                                        'dataRow'        => array('id' => $invRow['INVOICE_ID'], 'wfmStatusId' => $invRow['I_WFM_STATUS_ID']), 
                                        'description'    => $NtryRef
                                    );
                                }
                            }
                        }
                    }
                    
                    if ($wfmAttrs) {
                        $this->load->model('mdobject', 'middleware/models/');
                        self::setWfmStatusByBank($wfmAttrs);
                    }
                    
                    $response = array('status' => 'success', 'message' => 'Гүйлгээг амжилттай лавлалаа.', 'text' => 'Гүйлгээг амжилттай лавлалаа.', 'data' => $param);
                    
                } else {
                    $message = 'Лавлагаа олдсонгүй';
                    $response = array('status' => 'error', 'message' => $message, 'text' => $message, 'data' => $param);
                }
                
            } else {
                
                if (isset($resultArray['Document']['Body']['Error']['ErrorDetail']['ErrorDesc'])) {
                    $message = $resultArray['Document']['Body']['Error']['ErrorDetail']['ErrorDesc'];
                } else {
                    $message = issetDefaultVal($resultArray['Document']['Body']['BatchTrn']['responsemsg'], 'Хариу олдсонгүй');
                }
                
                $response = array('status' => 'error', 'message' => $message, 'text' => $message, 'data' => $param);
            }
        }
        
        return $response;
    }
    
    public function isTransferedCmInvBank($invId) {

        if ($invId) {
            
            global $db;
            
            $row = $db->GetRow("SELECT ID FROM CM_INV_BANK WHERE ID = ".$db->Param(0)." AND IS_TRANSFER = 1", array($invId));
            
            if (isset($row['ID'])) {
                return true;
            } else {
                return false;
            }
        }
        
        return true;
    }
    
    public function isTransferedCmBankBillingBook($invId) {

        if ($invId) {
            
            global $db;
            
            $row = $db->GetRow("SELECT ID FROM CM_BANK_BILLING_BOOK WHERE ID = ".$db->Param(0)." AND IS_TRANSFER = 1", array($invId));
            
            if (isset($row['ID'])) {
                return true;
            } else {
                return false;
            }
        }
        
        return true;
    }
    
    public function golomtBankImportStatement($processRow, $param) {
        
        if (issetParam($param['departmentid'])) {
            $departmentId = $param['departmentid'];
        } else {
            $sessionUserKeyDepartmentId = Ue::sessionUserKeyDepartmentId();
            $departmentId               = $sessionUserKeyDepartmentId ? $sessionUserKeyDepartmentId : Ue::sessionDepartmentId();
        }
        
        self::$isClearing = issetParam($param['isclearing']) == '1' ? true : false;
        
        $bankUrl = Config::get('GOLOMT_BANK_API_URL', 'departmentId='.$departmentId.';');
            
        if ($bankUrl) {    
            $url = $bankUrl;
        } else {
            $subUrl = Config::getFromCache('GOLOMT_BANK_SUB_URL');
            $url    = $processRow['WS_URL'] . $subUrl;
        }

        $OrganizationAnyBIC        = Config::get('OrganizationAnyBIC', 'departmentId='.$departmentId.';');
        $OrganizationGolomtLoginId = Config::get('OrganizationGolomtLoginId', 'departmentId='.$departmentId.';');      
        
        $accountCode = $param['account'];
        
        $xmlParams = '<?xml version="1.0" encoding="UTF-8"?>
        <Document>
            <GrpHdr>
                <MsgId>'. getUID() .'</MsgId>
                <CreDtTm>'. Date::currentDate('Y-m-d\TH:i:s') .'</CreDtTm>
                <TxsCd>6004</TxsCd>
                <InitgPty>
                    <Id>
                        <OrgId>
                            <AnyBIC>'.$OrganizationAnyBIC.'</AnyBIC>
                        </OrgId>
                    </Id>
                </InitgPty>
                <Crdtl>
                    <Lang>0</Lang>
                    <LoginID>'.$OrganizationGolomtLoginId.'</LoginID>
                    <RoleID>1</RoleID>
                    <Pwds>
                        <PwdType>1</PwdType>
                        <Pwd>1</Pwd>
                    </Pwds>
                </Crdtl>
            </GrpHdr>
            <EnqInf>
                <IBAN>'.$accountCode.'</IBAN>
                <Ccy>MNT</Ccy>
                <FrDt>'.$param['from'].'</FrDt>
                <ToDt>'.$param['to'].'</ToDt>
                <JrNo></JrNo>
                <StNum>1</StNum>
                <EndNum>2000</EndNum>
            </EnqInf>
        </Document>';
        
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cache-Control: no-cache', 'Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlParams);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        $output = curl_exec($ch);
        $err = curl_error($ch);
        
        curl_close($ch);

        self::createServiceMethodLog($url, 'golomtbankStatements', $xmlParams, $output, true);
        
        if ($err) {
            
            $response = array('status' => 'error', 'code' => 'curl', 'message' => $err);
            
        } else {
            
            $resultArray = Xml::createArray($output);
            
            if (isset($resultArray['Document']['Header']['ResponseHeader']['Status']) 
                && strtolower($resultArray['Document']['Header']['ResponseHeader']['Status']) == 'success') {
            
                if (isset($resultArray['Document']['EnqRsp']['Ntry'])) {
                    
                    $statementDetails = $resultArray['Document']['EnqRsp']['Ntry'];
                    
                    if (!array_key_exists(0, $statementDetails)) {
                        $statementDetails = array($statementDetails);
                    }
                    
                    $totalTrans = count($statementDetails);
                    
                    self::$bankBillingCreatedUserId = issetParam($param['setcreateduserid']);
                    
                    $importedIds = array();
                    $importedTransactions = $alreadyTransactions = 0;
                    $bankId = Mdintegration::getBankId('golomt');
                    
                    $isBankBillCheckDiff     = issetParam($param['isbankbillcheckdiff']);
                    $economicClass           = self::getEconomicClassCode($accountCode);
                    $debitEconomicClassCode  = $economicClass['debitEconomicClassCode'];
                    $creditEconomicClassCode = $economicClass['creditEconomicClassCode'];
                    
                    foreach ($statementDetails as $row) {
                        
                        self::$isCheckTransactionId = false;
                        
                        $journalId = $row['NtryRef'];
                        $billDate  = date('Y-m-d H:i:s', strtotime($row['TxPostedDt']));
                        $descr     = issetParam($row['TxAddInf']) ? Str::remove_doublewhitespace(Str::remove_whitespace_feed($row['TxAddInf'])) : 'no description';
                        $txnType   = $row['txnType'];
                        
                        if ($txnType == '2') {
                            $amount = number_format($row['Amt'], 6, '.', ''); 
                        } elseif ($txnType == '1') {
                            $amount = number_format($row['Amt'] * -1, 6, '.', ''); 
                        } else {
                            $amount = 0;
                        }
                        
                        if ($amount < 0) {
                            self::$isCheckTransactionId = true;
                        }
                        
                        $isAlreadyCreated = self::isAlreadyCreatedByJournalId($accountCode, $journalId, null, $billDate, $descr, $isBankBillCheckDiff, $amount);
                        
                        if ($isAlreadyCreated == false) {

                            $data = array(
                                'ID'          => getUID(),
                                'BANK_ID'     => $bankId,
                                'BILL_DATE'   => $billDate,
                                'ACCOUNT'     => $accountCode,
                                'JOURNAL_ID'  => $journalId,
                                'DESCRIPTION' => $descr, 
                                'RATE'        => $row['TxRt'], 
                                'IS_AUTO'     => 1
                            );
                            
                            if ($amount > 0) {
                            
                                $data['AMOUNT']        = $amount;
                                $data['CREDIT_AMOUNT'] = 0;
                                $data['IS_DEBIT']      = 1;

                                $data['ECONOMIC_CLASS_CODE'] = $debitEconomicClassCode;

                            } elseif ($amount < 0) {

                                $data['CREDIT_AMOUNT'] = number_format($amount * -1, 6, '.', '');
                                $data['AMOUNT']        = 0;
                                $data['IS_DEBIT']      = 0;

                                $data['ECONOMIC_CLASS_CODE'] = $creditEconomicClassCode;

                            } else {
                                $data['CREDIT_AMOUNT'] = 0;
                                $data['AMOUNT']        = 0;
                            }

                            if ($related_Account = issetParam($row['CtAcct'])) {
                                $data['RELATED_ACCOUNT'] = $related_Account;
                            }

                            $result = self::saveBilling($data);

                            if ($result['status'] == 'success') {
                                
                                if (self::$isClearing == false) {
                                    $importedIds[] = array('billId' => $data['ID']);
                                }
                                
                                $importedTransactions++;
                            } else {
                                $response = array('status' => 'error', 'message' => $result['message'], 'text' => $result['message'], 'data' => $param);
                                return $response;
                            }
                            
                        } else {
                            $alreadyTransactions++;
                        }
                    }
                    
                    if ($importedTransactions > 0) {
                    
                        if ($alreadyTransactions > 0) {
                            $message = "Нийт $totalTrans хуулгаас $importedTransactions хуулга амжилттай, $alreadyTransactions хуулга өмнө татагдсан байна.";
                        } else {
                            $message = "Нийт $totalTrans хуулгаас $importedTransactions хуулга амжилттай татагдлаа.";
                        }
                        
                        self::connectBillingToBankBook($importedIds);

                        $response = array('status' => 'success', 'message' => $message, 'text' => $message, 'data' => $param);

                    } else {

                        $message = $totalTrans . ' тооны хуулга бүгд өмнө нь татагдсан байна.';
                        $response = array('status' => 'error', 'message' => $message, 'text' => $message, 'data' => $param);
                    }
                    
                } else {
                    $message = 'Хуулга олдсонгүй';
                    $response = array('status' => 'error', 'message' => $message, 'text' => $message, 'data' => $param);
                }
                
            } else {
                
                if (isset($resultArray['Document']['Header']['ResponseHeader']['Status']) 
                    && strtolower($resultArray['Document']['Header']['ResponseHeader']['Status']) == 'failed') {
                    
                    $message = $resultArray['Document']['Body']['Error']['ErrorDetail']['ErrorDesc'];
                    
                } else {
                    $message = 'Хуулга олдсонгүй';
                }
                
                $response = array('status' => 'error', 'message' => $message, 'text' => $message, 'data' => $param);
            }
        }
        
        return $response;
    }
    
    public function khaanBankTransfer($processRow, $param) {
        
        try {
            
            $errorDesc = '';
            
            if (isset($param['invBankBookToBankMultiGet_dtl'])) {
                
                $url      = $processRow['WS_URL'] . $processRow['CLASS_NAME'];
                $paramDtl = $param['invBankBookToBankMultiGet_dtl'];
                
                unset($param['invBankBookToBankMultiGet_dtl']);
                
                $headerParam  = $param;
                $detailCount  = count($paramDtl);
                $successCount = $failedCount = 0;
                $selectWfmRow = array();
                
                $this->load->model('mdobject', 'middleware/models/');
                
                foreach ($paramDtl as $row) {
                    
                    $isInvoice = (issetParam($row['isInvoice']) == '1') ? true : false;
                    
                    if ($isInvoice) {
                        
                        $isTransfered = self::isTransferedCmBankBillingBook($row['transferId']);
                        
                    } else {
                        
                        $isTransfered = self::isTransferedCmInvBank($row['transferId']);
                    }
                    
                    if ($isTransfered == false) {
                        
                        self::$invoiceRecordIds[$row['transferId']] = $isInvoice ? 'CM_BANK_BILLING_BOOK' : 'CM_INV_BANK';
                        $detailResult = self::khaanDetailTransfer($url, $row, $headerParam);
                    
                        if ($detailResult['status'] == 'success') {

                            if ($isInvoice) {
                                
                                $this->db->AutoExecute('CM_BANK_BILLING_BOOK', array('IS_TRANSFER' => 1, 'BANK_ERROR_CODE' => null, 'BANK_ERROR_MESSAGE' => null), 'UPDATE', 'ID = '.$row['transferId']);
                            
                            } else {
                                $this->db->AutoExecute('CM_INV_BANK', array('IS_TRANSFER' => 1, 'BANK_ERROR_CODE' => null, 'BANK_ERROR_MESSAGE' => null), 'UPDATE', 'ID = '.$row['transferId']);
                            }

                            $successCount++;

                            $wfmAttrs = array(
                                array(
                                    'newWfmStatusid' => '1534844795469197', 
                                    'metaDataId'     => $row['refStructureId'], 
                                    'dataRow'        => array('id' => $row['transferId'], 'wfmStatusId' => $row['wfmStatusId']), 
                                    'description'    => 'Гүйлгээ амжилттай хийгдлээ'
                                )
                            );

                            if ($row['iRefStructureId'] && $row['invoiceId'] && $row['iWfmStatusId']) {
                                $wfmAttrs[] = array(
                                    'newWfmStatusid' => '1529649557722966', 
                                    'metaDataId'     => $row['iRefStructureId'], 
                                    'dataRow'        => array('id' => $row['invoiceId'], 'wfmStatusId' => $row['iWfmStatusId']), 
                                    'description'    => 'Гүйлгээ амжилттай хийгдлээ'
                                );
                            }

                            self::setWfmStatusByBank($wfmAttrs);

                            if (isset($param['setRowWfmStatus'][0])) {

                                $setRowWfmRows = $param['setRowWfmStatus'];

                                foreach ($setRowWfmRows as $setRowWfmRow) {

                                    if (isset($setRowWfmRow['trgrecordid']) && $row['transferId'] == $setRowWfmRow['trgrecordid']) {
                                        $selectWfmRow[] = array(
                                            'metaDataId'     => $setRowWfmRow['systemMetaGroupId'], 
                                            'newWfmStatusid' => $setRowWfmRow['newWfmStatusId'], 
                                            'dataRow'        => array('id' => $setRowWfmRow['id'], 'wfmStatusId' => $setRowWfmRow['wfmstatusid']), 
                                            'description'    => 'Done'
                                        );
                                    }
                                }
                            }

                        } else {

                            if ($isInvoice) {
                                $this->db->AutoExecute('CM_BANK_BILLING_BOOK', array('IS_TRANSFER' => 0, 'BANK_ERROR_CODE' => null, 'BANK_ERROR_MESSAGE' => $detailResult['message']), 'UPDATE', 'ID = '.$row['transferId']);
                            } else {
                                $this->db->AutoExecute('CM_INV_BANK', array('IS_TRANSFER' => 0, 'BANK_ERROR_CODE' => null, 'BANK_ERROR_MESSAGE' => $detailResult['message']), 'UPDATE', 'ID = '.$row['transferId']);
                            }

                            $failedCount++;

                            $wfmAttrs = array(
                                array(
                                    'newWfmStatusid' => '1534844795726298', 
                                    'metaDataId'     => $row['refStructureId'], 
                                    'dataRow'        => array('id' => $row['transferId'], 'wfmStatusId' => $row['wfmStatusId']), 
                                    'description'    => $detailResult['message']
                                )
                            );

                            if ($row['iRefStructureId'] && $row['invoiceId'] && $row['iWfmStatusId']) {
                                $wfmAttrs[] = array(
                                    'newWfmStatusid' => '1533536203222884', 
                                    'metaDataId'     => $row['iRefStructureId'], 
                                    'dataRow'        => array('id' => $row['invoiceId'], 'wfmStatusId' => $row['iWfmStatusId']), 
                                    'description'    => $detailResult['message']
                                );
                            }

                            self::setWfmStatusByBank($wfmAttrs);
                        }
                        
                    } else {
                        $failedCount++;
                    }
                    
                }
                
                if ($detailCount == 1) {
                    
                    if ($successCount == 1) {
                        $resultData['data']   = $headerParam;
                        $resultData['result'] = $resultData['data'];
                        $resultData['status'] = 'success';
                        $resultData['text']   = 'Гүйлгээ амжилттай хийгдлээ.';
                    } else {
                        $resultData['data']   = $headerParam;
                        $resultData['result'] = $resultData['data'];
                        $resultData['status'] = 'error';
                        $resultData['text']   = 'Гүйлгээ амжилтгүй боллоо.<br />' . $detailResult['message'];
                    }
                    
                } else {
                    $resultData['data']   = $headerParam;
                    $resultData['result'] = $resultData['data'];
                    $resultData['status'] = 'success';
                    $resultData['text']   = "Нийт <strong>$detailCount</strong> гүйлгээнээс<br /><strong>$successCount</strong> амжилттай <strong>$failedCount</strong> амжилтгүй.";
                }
                
                if ($selectWfmRow) {
                    self::setWfmStatusByBank($selectWfmRow);
                }
                
            } else {
                
                $errorDesc = 'error';
                $resultData['status']    = 'error';
                $resultData['data']      = $param;
                $resultData['result']    = '';
                $resultData['text']      = 'Detail empty!';
                $resultData['errorCode'] = 'empty';
            }
            
        } catch (Exception $ex) {
            
            $errorDesc = 'error';
            
            $resultData['data'] = array();
            $resultData['text'] = $ex->getMessage();
            $resultData['status'] = 'error';
        }
        
        return array('text' => $errorDesc, 'data' => $resultData, 'message' => $resultData['text'], 'status' => $resultData['status']);
    }
    
    public function khaanDetailTransfer($url, $row, $header) {
        
        // $isDomestic -> Банк доторх эсэх
        
        $isDomestic = empty($row['toBank']) ? true : false;
        $apiUrl     = Config::getFromCache('bankIntegrationAPIURLKhaan');
        $useMonpass = Config::getFromCache('bankIntegrationUseMonpassKhaan');
        
        if ($isDomestic) {
            
            $url = $apiUrl . 'transfer/domestic';
            
            $data = array(
                'transferId'   => $row['transferId'],
                'fromAccount'  => $row['fromAccount'],
                'toAccount'    => $row['toAccount'],
                'amount'       => $row['amount'],
                'description'  => $row['description'],
                'currency'     => $row['currency']
            );
            
        } else {
            
            $url = $apiUrl . 'transfer/interbank';
            
            $data = array(
                'transferId'    => $row['transferId'],
                'fromAccount'   => $row['fromAccount'],
                'toAccount'     => $row['toAccount'],
                'toCurrency'    => $row['toCurrency'],
                'toAccountName' => $row['toAccountName'],
                'toBank'        => $row['toBank'],
                'amount'        => $row['amount'],
                'description'   => $row['description'],
                'currency'      => $row['mainCurrency']
            ); 
        }
        
        if ($purpose = issetParam($row['purpose'])) {
            $data['purpose'] = $purpose;
        }
        
        if (isset($header['loginName']) && isset($header['tranPassword']) && $header['loginName'] && $header['tranPassword']) {
            
            $data['loginName'] = trim($header['loginName']);
            
            if (issetParam($header['isUseToken']) == '1') {
                $data['tranPassword'] = trim($header['tranPassword']);
            } else {
                $data['tranPassword'] = base64_encode(trim($header['tranPassword']));
            }
        }
        
        if ($useMonpass && isset($row['sign1contentHash']) && isset($row['sign1cipherText']) && isset($row['sign1guid'])) {
            $data['sign']['sign1contentHash'] = $row['sign1contentHash'];
            $data['sign']['sign1cipherText'] = $row['sign1cipherText'];
            $data['sign']['sign1guid'] = $row['sign1guid'];
        }

        if ($useMonpass && isset($row['sign2contentHash']) && isset($row['sign2cipherText']) && isset($row['sign2guid'])) {
            $data['sign']['sign2contentHash'] = $row['sign2contentHash'];
            $data['sign']['sign2cipherText'] = $row['sign2cipherText'];
            $data['sign']['sign2guid'] = $row['sign2guid'];
        }

        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
        
        $getToken = self::getKhaanBankAccessToken(issetParam($row['departmentId']));
        
        if (isset($getToken['error'])) {
            return array('status' => 'error', 'code' => 'curl', 'message' => $getToken['message']);
        }
        
        $curl = curl_init();
        
        $opts = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $jsonData, 
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $getToken",
                "Cache-Control: no-cache",
                "Content-Type: application/json"
            )
        );
        
        if ($url) {
            $port = parse_url($url, PHP_URL_PORT);
            
            if ($port) {
                $opts[CURLOPT_PORT] = $port;
            }
        }
        
        curl_setopt_array($curl, $opts);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        
        if ($err) {
            
            $response = $err;
            $result = array('status' => 'error', 'code' => 'curl', 'message' => $err);
            
        } else {
            
            $result = json_decode($response, true);

            if (isset($result['code'])) {
                $result = array('status' => 'error', 'code' => $result['code'], 'message' => $result['message']);
            } else {
                $result = array('status' => 'success', 'message' => 'success');
            }
        }
        
        $jsonData = json_decode($jsonData, true);
        
        if (isset($jsonData['tranPassword'])) {
            $jsonData['tranPassword'] = null;
        }

        $jsonData['API_PARAMS'] = array(
            'token' => $getToken, 
            'departmentId' => self::$departmentId, 
            'userNamePassword' => '******' . substr(self::$userNamePassword, 6, 200)
        );
        $jsonData = json_encode($jsonData, JSON_UNESCAPED_UNICODE);
        
        self::createServiceMethodLog($url, 'khanbankTransfer', $jsonData, $response);
        
        return $result;
    }
    
    public function khaanBankImportStatement($processRow, $param) {
        
        self::$isClearing = issetParam($param['isclearing']) == '1' ? true : false;
        
        $result = self::khaanBankGetStatement($param);

        if ($result['status'] == 'success') {
            
            $data = $result['data'];
            
            if (isset($data['transactions'])) {
                
                self::$bankBillingCreatedUserId = issetParam($param['setcreateduserid']);
                
                self::clearBillingClearingTbl();
                
                $transactions = $data['transactions'];
                $accountCode  = $param['account'];
                $totalTrans   = count($transactions);
                
                $isBankBillCheckDiff     = issetParam($param['isbankbillcheckdiff']);
                $economicClass           = self::getEconomicClassCode($accountCode);
                $debitEconomicClassCode  = $economicClass['debitEconomicClassCode'];
                $creditEconomicClassCode = $economicClass['creditEconomicClassCode'];
                
                $importedIds = array();
                $importedTransactions = 0;
                $alreadyTransactions  = 0;

                foreach ($transactions as $trans) {
                    
                    $journalId = $trans['journal'];
                    $recordId  = $trans['record'];
                    $transTime = $trans['time'];
                    $amount    = number_format($trans['amount'], 6, '.', ''); 
                    $descr     = issetParam($trans['description']) ? Str::remove_doublewhitespace(Str::remove_whitespace_feed($trans['description'])) : 'no description';
                    
                    if ($transTime) {
                        if (strlen($transTime) == 1) {
                            $transTime = '0'. $transTime . ':00:01';
                        } else {
                            $transTime = Arr::implode_r(':', str_split(substr($transTime, 0, 6), 2), true);
                        }
                    } else {
                        $transTime = '23:59:59';
                    }
                    
                    $billDate         = $trans['tranDate'] . ' ' . $transTime;
                    $isAlreadyCreated = self::isAlreadyCreatedByJournalId($accountCode, $journalId, $recordId, $billDate, $descr, $isBankBillCheckDiff, $amount);
                    
                    if ($isAlreadyCreated == false) { 
                        
                        $data = array(
                            'ID'          => getUID(),
                            'BANK_ID'     => self::$bankIdKhan,
                            'BILL_DATE'   => $billDate,
                            'TYPE'        => null,
                            'ACCOUNT'     => $accountCode,
                            'JOURNAL_ID'  => $journalId,
                            'IMPORT_ID'   => $recordId, 
                            'DESCRIPTION' => $descr, 
                            'RATE'        => 1, 
                            'IS_AUTO'     => 1 
                        );
                        
                        if ($amount > 0) {
                            
                            $data['AMOUNT']        = $amount;
                            $data['CREDIT_AMOUNT'] = 0;
                            $data['IS_DEBIT']      = 1;
                            
                            $data['ECONOMIC_CLASS_CODE'] = $debitEconomicClassCode;
                            
                        } elseif ($amount < 0) {
                            
                            $data['CREDIT_AMOUNT'] = number_format($amount * -1, 6, '.', '');
                            $data['AMOUNT']        = 0;
                            $data['IS_DEBIT']      = 0;
                            
                            $data['ECONOMIC_CLASS_CODE'] = $creditEconomicClassCode;
                            
                        } else {
                            $data['CREDIT_AMOUNT'] = 0;
                            $data['AMOUNT']        = 0;
                        }
                        
                        if ($relatedAccount = issetParam($trans['relatedAccount'])) {
                            $data['RELATED_ACCOUNT'] = $relatedAccount;
                        }

                        $result = self::saveBilling($data);
                        
                        if ($result['status'] == 'success') {
                            
                            if (self::$isClearing == false) {
                                $importedIds[] = array('billId' => $data['ID']);
                            }
                            
                            $importedTransactions++;
                        }
                        
                    } else {
                        $alreadyTransactions++;
                    }
                }
                
                if ($importedTransactions > 0) {
                    
                    if ($alreadyTransactions > 0) {
                        $message = "Нийт $totalTrans хуулгаас $importedTransactions хуулга амжилттай, $alreadyTransactions хуулга өмнө татагдсан байна.";
                    } else {
                        $message = "Нийт $totalTrans хуулгаас $importedTransactions хуулга амжилттай татагдлаа.";
                    }
                    
                    self::connectBillingToBankBook($importedIds);
                    
                    $response = array('status' => 'success', 'message' => $message, 'text' => $message, 'data' => $param);
                    
                } else {
                    
                    $message = $totalTrans . ' тооны хуулга бүгд өмнө нь татагдсан байна.';
                    $response = array('status' => 'error', 'message' => $message, 'text' => $message, 'data' => $param);
                }
                
            } else {
                $message = 'Хуулга ирсэнгүй';
                $response = array('status' => 'error', 'message' => $message, 'text' => $message, 'data' => $param);
            }
            
        } else {
            $response = array('status' => 'error', 'message' => $result['message'], 'text' => $result['message'], 'data' => $param);
        }
        
        return $response;
    }
    
    public function connectBillingToBankBook($importedIds) {
        
        if (is_countable($importedIds) && count($importedIds)) {
            
            if (Config::getFromCache('isAutoConnectBillingToBankBook') == '1') {

                $param = array(
                    'dtl' => $importedIds
                );
                $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'connectBillingToBankBookLoop', $param);

                if ($result['status'] == 'success') {
                    $response = array('status' => 'success');
                } else {
                    $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
                }

            } else {
                $response = array('status' => 'error', 'message' => 'Тохиргоо хийгдээгүй байна. /isAutoConnectBillingToBankBook/');
            }
            
        } else {
            $response = array('status' => 'error', 'message' => 'Imported ids is null');
        }
        
        return $response;
    }
    
    public function khaanBankGetStatement($param) {
        
        $getToken = self::getKhaanBankAccessToken(issetParam($param['departmentid']));
        
        $account = $param['account'];
        $page    = '0';
        $size    = 1000;
        $apiUrl  = Config::getFromCache('bankIntegrationAPIURLKhaan');
        
        $inputStr = "statements/$account?page=$page&size=$size";
        $dateFilter = $lastImportId = '';
        
        if (isset($param['from']) && $param['from'] && isset($param['to']) && $param['to']) {
            
            $from = str_replace('-', '', $param['from']);
            $to   = str_replace('-', '', $param['to']);
            
            $dateFilter = "&from=$from&to=$to";
            $inputStr  .= $dateFilter;
        }
        
        if (isset($param['lastimportid']) && $param['lastimportid']) {
            
            $lastImportId = '&record=' . $param['lastimportid'];
            $inputStr .= $lastImportId;
        }
        
        $url = $apiUrl . $inputStr;
        
        if (isset($getToken['error'])) {
            self::createServiceMethodLog($url, 'khanbankStatements', $inputStr, $getToken['message'], false);
            return array('status' => 'error', 'code' => 'curl', 'message' => $getToken['message']);
        }
        
        $opts = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $getToken",
                "Cache-Control: no-cache",
                "Content-Type: application/json"
            )
        );
        
        if ($apiUrl) {
            $port = parse_url($apiUrl, PHP_URL_PORT);
            
            if ($port) {
                $opts[CURLOPT_PORT] = $port;
            }
        }
        
        $curl = curl_init();
        
        curl_setopt_array($curl, $opts);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            self::createServiceMethodLog($url, 'khanbankStatements', $inputStr, $err, false);
            $result = array('status' => 'error', 'code' => 'curl', 'message' => 'CURL: ' . $err);
            $response = $err;
        } else {
            
            $result = json_decode($response, true);

            if (isset($result['code'])) {
                
                $result = array('status' => 'error', 'code' => $result['code'], 'message' => $result['message']);
                
            } elseif (isset($result['fault'])) {
                
                $result = array('status' => 'error', 'code' => $result['fault']['detail']['errorcode'], 'message' => $result['fault']['faultstring']);
                
            } else {
                
                if (isset($result['total']['count']) && is_numeric($result['total']['count']) && $result['total']['count'] > $size) {
                    
                    $transactions = $result['transactions'];
                    $pages        = ceil($result['total']['count'] / $size) - 1;
                    
                    for ($p = 1; $p <= $pages; $p++) {
                        
                        $loopInputStr = "statements/$account?page=$p&size=$size" . $dateFilter . $lastImportId;
                        $loopUrl      = $apiUrl . $loopInputStr;
                        $getStatement = self::getKhanbankStatementByCurl($loopUrl, $apiUrl, $getToken);
                        
                        if ($getStatement['status'] == 'success') {
                            
                            $transactions = array_merge($transactions, $getStatement['data']);
                            
                            self::createServiceMethodLog($loopUrl, 'khanbankStatements', $loopInputStr, json_encode($getStatement['data']), true);
                            
                        } else {
                            
                            self::createServiceMethodLog($loopUrl, 'khanbankStatements', $loopInputStr, json_encode($getStatement), true);
                            
                            return $getStatement;
                        }
                    }
                    
                    $result['transactions'] = $transactions;
                }
                
                $result = array('status' => 'success', 'data' => $result);
            }
        }
        
        self::createServiceMethodLog($url, 'khanbankStatements', $inputStr, $response, true);
        
        return $result;
    }
    
    public function getKhanbankStatementByCurl($url, $apiUrl, $getToken) {
        $opts = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $getToken",
                "Cache-Control: no-cache",
                "Content-Type: application/json"
            )
        );
        
        if ($apiUrl) {
            $port = parse_url($apiUrl, PHP_URL_PORT);
            
            if ($port) {
                $opts[CURLOPT_PORT] = $port;
            }
        }
        
        $curl = curl_init();
        
        curl_setopt_array($curl, $opts);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        
        if ($err) {
            $result = array('status' => 'error', 'code' => 'curl', 'message' => $err);
        } else {
            
            $result = json_decode($response, true);

            if (isset($result['code'])) {
                
                $result = array('status' => 'error', 'code' => $result['code'], 'message' => $result['message']);
                
            } elseif (isset($result['fault'])) {
                
                $result = array('status' => 'error', 'code' => $result['fault']['detail']['errorcode'], 'message' => $result['fault']['faultstring']);
                
            } else {
                
                $result = array('status' => 'success', 'data' => $result['transactions']);
            }
        }
        
        return $result;
    }
    
    public function getKhaanBankAccessToken($departmentId) {
        
        $getTokenUrl = Config::getFromCache('bankIntegrationGetTokenCustomURLKhaan');
        
        if ($getTokenUrl) {
            
            $authToken = Config::getFromCache('bankIntegrationGetTokenCustomURLKhaanAuthToken');
            $curl = curl_init();

            $opts = array(
                CURLOPT_URL => $getTokenUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer $authToken",
                    "Content-Type: application/json; charset=utf-8"
                )
            );

            if ($getTokenUrl) {
                $port = parse_url($getTokenUrl, PHP_URL_PORT);

                if ($port) {
                    $opts[CURLOPT_PORT] = $port;
                }
            }

            curl_setopt_array($curl, $opts);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                
                return array('error' => true, 'message' => 'GetToken: cURL Error - ' . $err);
                
            } else {
                
                $jsonDecode = json_decode($response, true);

                if (isset($jsonDecode['isSuccess']) 
                    && ($jsonDecode['isSuccess'] == true || $jsonDecode['isSuccess'] == 'true') 
                    && isset($jsonDecode['accessToken'])) {

                    return $jsonDecode['accessToken'];

                } elseif (isset($jsonDecode['resultMessage'])) {

                    return array('error' => true, 'message' => 'GetToken: '. $jsonDecode['resultMessage']);

                } elseif (isset($jsonDecode['message'])) {
                
                    return array('error' => true, 'message' => 'GetToken: '. $jsonDecode['message']);

                } else {
                    return array('error' => true, 'message' => 'GetToken: Unknown error ' .$response);
                }
            }
            
        } else {
            
            //'https://doob.world:6444/v1/auth/token?grant_type=client_credentials'

            $apiUrl = Config::getFromCache('bankIntegrationAPIURLKhaan');
            $url    = $apiUrl . 'auth/token?grant_type=client_credentials';

            if ($departmentId) {
                $userNamePassword = Config::get('bankIntegrationUsernamePWDKhaan', 'departmentId='.$departmentId.';');
            } else {
                $userNamePassword = Config::get('bankIntegrationUsernamePWDKhaan');
            }
            
            self::$departmentId = $departmentId;
            self::$userNamePassword = $userNamePassword;

            $opts = array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_HTTPHEADER => array(
                    "Cache-Control: no-cache",
                    "Content-Type: application/x-www-form-urlencoded",
                    "Content-Length: 0"
                )
            );

            if ($url) {
                $port = parse_url($url, PHP_URL_PORT);

                if ($port) {
                    $opts[CURLOPT_PORT] = $port;
                }
            }

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_USERPWD, $userNamePassword);
            curl_setopt_array($curl, $opts);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                
                self::createServiceMethodLog($url, 'khanbankGetToken', $userNamePassword, 'GetToken: '.$err);
                
                return array('error' => true, 'message' => 'GetToken: '.$err);
            } else {
                $jsonDecode = json_decode($response, true);

                if (isset($jsonDecode['access_token'])) {

                    return $jsonDecode['access_token'];

                } elseif (isset($jsonDecode['message'])) {
                    
                    self::createServiceMethodLog($url, 'khanbankGetToken', $userNamePassword, 'GetToken: '.$jsonDecode['message']);

                    return array('error' => true, 'message' => 'GetToken: '.$jsonDecode['message']);

                } elseif (isset($jsonDecode['resultMessage'])) {
                    
                    self::createServiceMethodLog($url, 'khanbankGetToken', $userNamePassword, 'GetToken: '. $jsonDecode['resultMessage']);
                    
                    return array('error' => true, 'message' => 'GetToken: '. $jsonDecode['resultMessage']);

                } else {
                    
                    self::createServiceMethodLog($url, 'khanbankGetToken', $userNamePassword, 'GetToken: Unknown error ' .$response);
                    
                    return array('error' => true, 'message' => 'GetToken: Unknown error ' .$response);
                }
            }
        }
    }
    
    public function clearBillingClearingTbl() {
        
        /*if (self::$isClearing) {
            $this->db->Execute("DELETE FROM CM_BANK_BILLING_CLEARING");
        }*/
        
        return true;
    }
    
    public function getBankAccountBalanceModel() {
        
        $result = array();
        
        $bankCode    = Input::post('bankCode');
        $bankAccount = Input::post('bankAccount');
        
        if ($bankCode && $bankAccount) {
            
            $departmentId = Input::post('departmentId');
            
            if ($bankCode == 'khaanbank') {
                
                $result = self::getKhanBankAccountBalance($departmentId, $bankAccount);
                
            } elseif ($bankCode == 'golomtbank') {
                
                $result = self::getGolomtBankAccountBalance($departmentId, $bankAccount);
            }
        }
        
        return $result;
    }
    
    public function getKhanBankAccountBalance($departmentId, $bankAccount) {
        
        $getToken = self::getKhaanBankAccessToken($departmentId);
                
        if (isset($getToken['error'])) {
            return array('status' => 'error', 'message' => $getToken['message']);
        }

        $apiUrl = Config::getFromCache('bankIntegrationAPIURLKhaan');

        $curl = curl_init();

        $opts = array(
            CURLOPT_URL => $apiUrl . 'accounts/'.$bankAccount.'/balance',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $getToken",
                "Cache-Control: no-cache", 
                "Content-Type: application/json"
            )
        );

        if ($apiUrl) {
            $port = parse_url($apiUrl, PHP_URL_PORT);

            if ($port) {
                $opts[CURLOPT_PORT] = $port;
            }
        }

        curl_setopt_array($curl, $opts);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $result = array("cURL Error: " . $err);
        } else {
            $result = json_decode($response, true);

            if (isset($result['account'])) {
                $result = $result['account'];
            }
        }
        
        return $result;
    }
    
    public function getGolomtBankAccountBalance($departmentId, $bankAccount) {
        
        $url = Config::get('GOLOMT_BANK_API_URL', 'departmentId='.$departmentId.';');
                
        //$url = 'https://localhost:8446/rpc/interactive';

        $OrganizationAnyBIC        = Config::get('OrganizationAnyBIC', 'departmentId='.$departmentId.';'); //'interactive';
        $OrganizationGolomtLoginId = Config::get('OrganizationGolomtLoginId', 'departmentId='.$departmentId.';'); //'interactive';
        
        //$param['account'] = '1105048753';         
        
        $xmlParams = '<?xml version="1.0" encoding="UTF-8"?>
        <Document>
            <GrpHdr>
                <MsgId>'. getUID() .'</MsgId>
                <CreDtTm>'. Date::currentDate('Y-m-d\TH:i:s') .'</CreDtTm>
                <TxsCd>5003</TxsCd>
                <InitgPty>
                    <Id>
                        <OrgId>
                            <AnyBIC>'.$OrganizationAnyBIC.'</AnyBIC>
                        </OrgId>
                    </Id>
                </InitgPty>
                <Crdtl>
                    <Lang>0</Lang>
                    <LoginID>'.$OrganizationGolomtLoginId.'</LoginID>
                    <RoleID>1</RoleID>
                    <Pwds>
                        <PwdType>1</PwdType>
                        <Pwd>1</Pwd>
                    </Pwds>
                </Crdtl>
            </GrpHdr>
            <EnqInf>
                <IBAN>'.$bankAccount.'</IBAN>
                <Ccy>MNT</Ccy>
            </EnqInf>
        </Document>';
        
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cache-Control: no-cache', 'Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlParams);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        $output = curl_exec($ch);
        $err = curl_error($ch);
        
        curl_close($ch);
        
        if ($err) {
            
            $response = array('status' => 'error', 'code' => 'curl', 'message' => $err);
            
        } else {
            
            $resultArray = Xml::createArray($output);
            $response = array('status' => 'error');
            
            if (isset($resultArray['Document']['Header']['ResponseHeader']['Status']) 
               && strtolower($resultArray['Document']['Header']['ResponseHeader']['Status']) == 'success') {

                if (isset($resultArray['Document']['EnqRsp'])) {

                    $response = array(
                        'status'           => 'success', 
                        'balance'          => str_replace(',', '', $resultArray['Document']['EnqRsp']['Bal']), 
                        'avalaibleBalance' => str_replace(',', '', $resultArray['Document']['EnqRsp']['ABal'])
                    );
                }
                
            } elseif (isset($resultArray['Document']['Header']['ResponseHeader']['Status']) 
               && strtolower($resultArray['Document']['Header']['ResponseHeader']['Status']) == 'failed') {

                $response = array(
                    'status'  => 'error', 
                    'code'    => 'curl', 
                    'message' => $resultArray['Document']['Body']['Error']['ErrorDetail']['ErrorDesc'] . ' - ' . $url
                );
            }
        }
        
        return $response;
    }
    
    public function getBankAccountInfoModel() {
        
        $result = array();
        
        $bankCode    = Input::post('bankCode');
        $bankAccount = Input::post('bankAccount');
        
        if ($bankCode && $bankAccount) {
            
            if ($bankCode == 'khaanbank') {
                $result = self::getKhanBankAccountInfo(null, $bankAccount);   
            }
        }
        
        return $result;
    }
    
    public function getKhanBankAccountInfo($departmentId, $bankAccount) {
        
        $getToken = self::getKhaanBankAccessToken($departmentId);
                
        if (isset($getToken['error'])) {
            return array('status' => 'error', 'message' => $getToken['message']);
        }

        $apiUrl = Config::getFromCache('bankIntegrationAPIURLKhaan');

        $curl = curl_init();

        $opts = array(
            CURLOPT_URL => $apiUrl . 'accounts/'.$bankAccount.'/name?bank=050000',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $getToken",
                "Cache-Control: no-cache", 
                "Content-Type: application/json"
            )
        );

        if ($apiUrl) {
            $port = parse_url($apiUrl, PHP_URL_PORT);

            if ($port) {
                $opts[CURLOPT_PORT] = $port;
            }
        }

        curl_setopt_array($curl, $opts);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $result = array("cURL Error: " . $err);
        } else {
            $result = json_decode($response, true);
        }
        
        return $result;
    }
    
    public function getBankTransactionStatementModel() {
        
        $result = array();
        
        $bankCode = Input::post('bankCode');
        
        if ($bankCode) {
            
            $departmentId = Input::post('departmentId');
                    
            if ($bankCode == 'golomtbank') {
                
                $transDate = Input::post('transDate');
                $transId = Input::post('transId');
                
                $result = self::getGolomtBankTransactionStatement($departmentId, $transDate, $transId);
            }
        }
        
        return $result;
    }
    
    public function getGolomtBankTransactionStatement($departmentId, $transDate, $transId) {
        
        $url = Config::get('GOLOMT_BANK_API_URL', 'departmentId='.$departmentId.';');

        $OrganizationAnyBIC        = Config::get('OrganizationAnyBIC', 'departmentId='.$departmentId.';');
        $OrganizationGolomtLoginId = Config::get('OrganizationGolomtLoginId', 'departmentId='.$departmentId.';');
        
        $xmlParams = '<?xml version="1.0" encoding="UTF-8"?>
        <Document>
            <GrpHdr>
                <MsgId>'. getUID() .'</MsgId>
                <CreDtTm>'. Date::currentDate('Y-m-d\TH:i:s') .'</CreDtTm>
                <TxsCd>5014</TxsCd>
                <InitgPty>
                    <Id>
                        <OrgId>
                            <AnyBIC>'.$OrganizationAnyBIC.'</AnyBIC>
                        </OrgId>
                    </Id>
                </InitgPty>
                <Crdtl>
                    <Lang>0</Lang>
                    <LoginID>'.$OrganizationGolomtLoginId.'</LoginID>
                    <Pwds>
                        <Pwd>1</Pwd>
                        <PwdType></PwdType>
                    </Pwds>
                    <RoleID>1</RoleID>
                </Crdtl>
            </GrpHdr>
            <TranStatement>
                <Date>'.$transDate.'</Date>
                <TranID>'.$transId.'</TranID>
            </TranStatement>
        </Document>';
        
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cache-Control: no-cache', 'Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlParams);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        $output = curl_exec($ch);
        $err = curl_error($ch);
        
        curl_close($ch);
        
        if ($err) {
            
            $response = array('status' => 'error', 'code' => 'curl', 'message' => $err);
            
        } else {
            
            $response = array('status' => 'error');
            
            if (@simplexml_load_string($output)) {
                
                $resultArray = Xml::createArray($output);

                if (isset($resultArray['Document']['Header']['ResponseHeader']['Status']) 
                   && strtolower($resultArray['Document']['Header']['ResponseHeader']['Status']) == 'failed') {

                    $response = array(
                        'status'  => 'error', 
                        'code'    => 'curl', 
                        'message' => $resultArray['Document']['Body']['Error']['ErrorDetail']['ErrorDesc'] . ' - ' . $url
                    );
                }
                
            } else {
                
                $cacheTmpDir = Mdcommon::getCacheDirectory();
                $cacheDir = $cacheTmpDir.'/statement_pdf';
                
                if (!is_dir($cacheDir)) {

                    mkdir($cacheDir, 0777);

                } else {

                    $currentHour = (int) Date::currentDate('H');

                    /* Оройны 17 цагаас 19 цагийн хооронд шалгаж өмнө нь үүссэн файлуудыг устгана */
                    if ($currentHour >= 17 && $currentHour <= 19) { 

                        $files = glob($cacheDir.'/*');
                        $now   = time();
                        $day   = 0.5;

                        foreach ($files as $file) {
                            if (is_file($file) && ($now - filemtime($file) >= 60 * 60 * 24 * $day)) {
                                @unlink($file);
                            } 
                        }
                    }
                }
        
                $filePath = 'cache/statement_pdf/' . getUID(). '.pdf';
        
                $isCreate = @file_put_contents($filePath, $output);
                
                if ($isCreate) {
                    $response = array('status' => 'success', 'filePath' => URL . $filePath);
                } else {
                    $response = array('status' => 'error', 'message' => 'Файл үүсгэж чадсангүй!');
                }
            }
        }
        
        return $response;
    }
    
    public function setWfmStatusByBank($wfmAttrs) {
        
        foreach ($wfmAttrs as $wfmAttr) {
                
            if (isset($_POST)) {
                unset($_POST);
            }
            
            $wfmAttr['dataRow']['skipCheckRow'] = 1;

            $_POST['newWfmStatusid'] = $wfmAttr['newWfmStatusid'];
            $_POST['metaDataId'] = $wfmAttr['metaDataId'];
            $_POST['dataRow'] = $wfmAttr['dataRow'];
            $_POST['description'] = $wfmAttr['description'];

            $response = $this->model->setRowWfmStatusModel();
        }
        
        return $response;
    }

    public function isAlreadyCreatedByJournalId($accountCode, $journalId, $recordId = null, $billDate, $descr = '', $isBankBillCheckDiff, $amount) {
        
        if (self::$isClearing) {
            return false;
        }
        
        /*if ($amount > 0) {
            $amount = $amount;
            $amountCol = 'AMOUNT';
        } elseif ($amount < 0) {
            $amount = number_format($amount * -1, 6, '.', '');
            $amountCol = 'CREDIT_AMOUNT';
        } else {
            $amount = 0;
            $amountCol = 'AMOUNT';
        }*/
        
        if (self::$isCheckTransactionId) {
            $checkCount = $this->db->GetOne("SELECT COUNT(1) FROM CM_INV_BANK WHERE TRANSACTION_ID = ".$this->db->Param(0), [$journalId]);
            if ($checkCount) {
                return true;
            }
        }
        
        $where = null;
        
        if ($amount < 0) {
            $amount = number_format($amount * -1, 6, '.', '');
            $where .= " AND CREDIT_AMOUNT = $amount";
        }
            
        if ($isBankBillCheckDiff == '1') {
        
            $row = $this->db->GetRow("
                SELECT 
                    ID 
                FROM CM_BANK_BILLING 
                WHERE ACCOUNT = ".$this->db->Param(0)." 
                    AND JOURNAL_ID = ".$this->db->Param(1)." 
                    AND ".$this->db->SQLDate('Y-m-d H:i:s', 'BILL_DATE')." = ".$this->db->Param(2) . $where,
                array($accountCode, $journalId, $billDate)
            );
            
        } else {
            
            $where .= ($recordId) ? " AND IMPORT_ID = $recordId" : '';
        
            $row = $this->db->GetRow("
                SELECT 
                    ID 
                FROM CM_BANK_BILLING 
                WHERE ACCOUNT = ".$this->db->Param(0)." 
                    AND JOURNAL_ID = ".$this->db->Param(1)." 
                    AND ".$this->db->SQLDate('Y-m-d H:i:s', 'BILL_DATE')." = ".$this->db->Param(2) . $where,
                array($accountCode, $journalId, $billDate)
            );
        }
            
        if (isset($row['ID'])) {
            return true;
        } else {
            return false;
        }
    }
    
    public function createServiceMethodLog($url, $serviceName, $requestData = null, $responseData = null, $isClob = false) {
        
        if (self::$isClearing && $isClob) {
            return array('status' => 'error', 'message' => 'Account is clearing!');
        }
        
        try {
            
            if (!self::$saveLogId) {
                
                $id = getUID();
                
                if (self::$isSaveLogId) {
                    self::$saveLogId = $id;
                }
            
                $params = array(
                    'ID'               => $id, 
                    'WEB_SERVICE_NAME' => $serviceName, 
                    'WEB_SERVICE_URL'  => $url, 
                    'CREATED_DATE'     => Date::currentDate(), 
                    'USER_ID'          => Ue::sessionUserKeyId()
                );

                if (!$isClob) {
                    $params['REQUEST_STRING']  = $requestData;
                    $params['RESPONSE_STRING'] = $responseData;
                }

                $this->db->AutoExecute('SYSINT_SERVICE_METHOD_LOG', $params);
                
                if (self::$invoiceRecordIds) {
                    
                    $i = 0;
                    
                    foreach (self::$invoiceRecordIds as $invoiceRecordId => $srcTableName) {
                        
                        $mapData = array(
                            'ID'              => getUIDAdd($i), 
                            'SRC_TABLE_NAME'  => $srcTableName, 
                            'SRC_RECORD_ID'   => $invoiceRecordId, 
                            'TRG_TABLE_NAME'  => 'SYSINT_SERVICE_METHOD_LOG', 
                            'TRG_RECORD_ID'   => $id, 
                            'CREATED_USER_ID' => $params['USER_ID'], 
                            'CREATED_DATE'    => Date::currentDate()
                        );    
                        $this->db->AutoExecute('META_DM_RECORD_MAP', $mapData);
                        
                        $i ++;
                    }
                    
                    self::$invoiceRecordIds = array();
                }
                
            } else {
                $id = self::$saveLogId;
            }
            
            if ($isClob) {
                
                if ($requestData) {
                    $this->db->UpdateClob('SYSINT_SERVICE_METHOD_LOG', 'REQUEST_STRING', $requestData, 'ID = '.$id);
                }
                
                if ($responseData) {
                    $this->db->UpdateClob('SYSINT_SERVICE_METHOD_LOG', 'RESPONSE_STRING', $responseData, 'ID = '.$id);
                }
            }
            
            $beforeDate = Date::beforeDate('Y-m-d', '-2 month');
        
            $this->db->Execute("DELETE FROM SYSINT_SERVICE_METHOD_LOG WHERE WEB_SERVICE_NAME IN ('golomtbankTransfer', 'golomtbankStatements', 'khanbankTransfer', 'khanbankStatements') AND CREATED_DATE < ".$this->db->ToDate("'$beforeDate'", 'YYYY-MM-DD'));

            $response = array('status' => 'success');

        } catch (ADODB_Exception $ex) {
            
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }

        return $response;
    }
    
    public function callFlmService($param, $url = 'https://202.55.191.198/DERF_WS/DERF.asmx/SetRegister') {
        
        if (isset($param['responseMethod'])) {
            unset($param['responseMethod']);
        }
        if (isset($param['response']['responseMethod'])) {
            unset($param['response']['responseMethod']);
        }
        if (isset($param['response']['message'])) {
            unset($param['response']['message']);
        }
        
        try {
            $opts = array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_SSL_VERIFYHOST => 0, 
                CURLOPT_SSL_VERIFYPEER => 0, 
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "p_json=" . json_encode($param),
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/x-www-form-urlencoded"
                ),
            );
            
            if ($url != "http://202.55.191.198/DERF_WS/DERF.asmx/SetRegister") {
                array_walk_recursive($param, 'changeRemoveHtmltagArray');
            }

            $curl = curl_init();

            curl_setopt_array($curl, $opts);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                $response = $err;
                $result = array('status' => 'error', 'code' => 'curl', 'message' => $err);
            } else {
                $result = array('status' => 'success', 'code' => 'curl', 'message' => $response);
            }
            
            $id = getUID();
            $params = array(
                'ID'               => $id, 
                'WEB_SERVICE_NAME' => "FLM", 
                'WEB_SERVICE_URL'  => $url, 
                'CREATED_DATE'     => Date::currentDate(), 
                'USER_ID'          => Ue::sessionUserKeyId()
            );

            $this->db->AutoExecute('SYSINT_SERVICE_METHOD_LOG', $params);

            $requestData  = json_encode($param);
            $responseData = $response;

            $this->db->UpdateClob('SYSINT_SERVICE_METHOD_LOG', 'REQUEST_STRING', $requestData, 'ID = '.$id);
            $this->db->UpdateClob('SYSINT_SERVICE_METHOD_LOG', 'RESPONSE_STRING', $responseData, 'ID = '.$id);

        } catch (Exception $ex) {
            $result = array('status' => 'error', 'code' => 'curl', 'message' => $ex);
        }
        
        return $result;
    }
    
    public function saveMobicomIntegration($data) {

        try {
            
            $result = $this->db->AutoExecute('TM_TASK', $data);

            if ($result) {
                $response = array('status' => 'success');
            }

        } catch (ADODB_Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }

        return $response;
    }
    
    public function callXypService($processRow, $param, $recursive = true, $timeStamp = '') {

        try {
            $outPutParam = $headerParam = $param;
            $errorDesc = '';
            
            if ($recursive) {
                array_walk_recursive($param, 'changeValueArray');
            }
            
            $signature = self::getSignatureKey($timeStamp, issetParam($param['departmentId']));
            
            $soapOption = array(
                'trace' => 1,
                'exceptions' => 1,
                'soap_version' => SOAP_1_1,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'features' => SOAP_SINGLE_ELEMENT_ARRAYS | SOAP_USE_XSI_ARRAY_TYPE,
                'stream_context' => stream_context_create(array(// self signed ssl verify hiih heseg
                    'ssl' => array (
                        'verify_peer' => false, // ayulgui baidliin uudnees verify_peer utgiig true bailgah heregtei bolno
                        'allow_self_signed' => true
                    ),
                    'http' => array(
                        'header' =>
                        "accessToken: " . $signature['accessToken'] . "\r\n" .
                        "timeStamp: " . $signature['timeStamp'] . "\r\n" .
                        "signature: " . $signature['signature'] . "\r\n"
                    ),
                ))
            );
            $result = $this->ws->callSoapClient($processRow['WS_URL'], $processRow['CLASS_NAME'], array('request' => $param), $soapOption);
            $resultData = Arr::objectToArray($result);
            
            $methodId = getUID();
            $currentDate = Date::currentDate();
            $userId = Ue::sessionUserKeyId(); 
            $year = Date::currentDate('Y');
                
            if (isset($resultData['return']['response'])) {
                
                array_walk_recursive($outPutParam, 'changeValueBase');
                array_walk_recursive($resultData, 'changeValueBase');
                
                $resultData['return']['response']['createdDate'] = $currentDate;
                $resultData['data']   = $headerParam;
                
                $resultData['result'] = self::changeKeyRecursive($resultData['return']['response']);
                $resultData['status'] = 'success';
                $resultData['text']   = isset($resultData['return']['resultMessage']) ? $resultData['return']['resultMessage'] : 'success'; 
                
                $data = array(
                    'ID' => $methodId,
                    'WEB_SERVICE_NAME' => $processRow['CLASS_NAME'],
                    'WEB_SERVICE_URL' => $processRow['WS_URL'],
                    'CREATED_DATE' => $currentDate,
                    'USER_ID' => $userId,
                    'IP_ADDRESS' => get_client_ip(),
                    'IS_SUCCESSFUL' => '1'
                );

                if (Config::getFromCache('USE_SAVE_LOG_YEAR') === '1') {
                    $resultLog = $this->db->AutoExecute('SYSINT_SERVICE_METHOD_LOG_' . $year, $data);
                } else {
                    $resultLog = $this->db->AutoExecute('SYSINT_SERVICE_METHOD_LOG', $data);
                }
                
                if ($resultLog) {
                    
                    $resultData['result']['logid'] = $methodId;
                    
                    if (Config::getFromCache('USE_SAVE_LOG_YEAR') === '1') {
                        $this->db->UpdateClob('SYSINT_SERVICE_METHOD_LOG_' . $year, 'REQUEST_STRING', json_encode($outPutParam), 'ID = '.$methodId);
                        $this->db->UpdateClob('SYSINT_SERVICE_METHOD_LOG_' . $year, 'RESPONSE_STRING', json_encode($resultData), 'ID = '.$methodId);
                    } else {
                        $this->db->UpdateClob('SYSINT_SERVICE_METHOD_LOG', 'REQUEST_STRING', json_encode($outPutParam), 'ID = '.$methodId);
                        $this->db->UpdateClob('SYSINT_SERVICE_METHOD_LOG', 'RESPONSE_STRING', json_encode($resultData), 'ID = '.$methodId);
                    }
                    
                    $dataDtl = array(
                        'ID' => getUID(),
                        'LOG_ID' => $methodId,
                        'REG_NUM' => isset($param['citizenRegnum']) ? $param['citizenRegnum'] : issetDefaultVal($outPutParam['regnum'], ''),
                        'CITIZEN_REG_NUM' => isset($param['citizenRegnum']) ? $param['citizenRegnum'] : issetDefaultVal($outPutParam['regnum'], ''),
                        'CITIZEN_FINGER' => isset($param['citizenFingerPrint']) ? base64_encode($param['citizenFingerPrint']) : '',
                        'OPERATOR_REG_NUM' => isset($param['operatorRegnum']) ? $param['operatorRegnum'] : '',
                        'OPERATOR_FINGER' => isset($param['operatorFingerPrint']) ? base64_encode($param['operatorFingerPrint']) : '',
                        'LEGAL_ENTITY_NUMBER' => isset($param['legalEntityNumber']) ? $param['legalEntityNumber'] : '',
                        'PROPERTY_NUMBER' => isset($param['propertyNumber']) ? $param['propertyNumber'] : '',
                    );
                    
                    if (Config::getFromCache('USE_SAVE_LOG_YEAR') === '1') {
                        $resultLog = $this->db->AutoExecute('SYSINT_SVC_METHOD_LOG_DTL_' . $year, $dataDtl);
                    } else {
                        $this->db->AutoExecute('SYSINT_SERVICE_METHOD_LOG_DTL', $dataDtl);
                    }
                    
                }
                
            } else {
                
                array_walk_recursive($outPutParam, 'changeValueBase');
                
                if ($resultData) {
                    array_walk_recursive($resultData, 'changeValueBase');
                }
                
                $data = array( 
                    'ID' => $methodId,
                    'WEB_SERVICE_NAME' => $processRow['CLASS_NAME'],
                    'WEB_SERVICE_URL' => $processRow['WS_URL'],
                    'CREATED_DATE' => $currentDate,
                    'USER_ID' => $userId,
                    'IP_ADDRESS' => get_client_ip(),
                    'IS_SUCCESSFUL' => '0'
                );

                if (Config::getFromCache('USE_SAVE_LOG_YEAR') === '1') {
                    $resultLog = $this->db->AutoExecute('SYSINT_SERVICE_METHOD_LOG_' . $year, $data);
                } else {
                    $resultLog = $this->db->AutoExecute('SYSINT_SERVICE_METHOD_LOG', $data);
                }
                                
                if ($resultLog) {
                    if (Config::getFromCache('USE_SAVE_LOG_YEAR') === '1') {
                        $this->db->UpdateClob('SYSINT_SERVICE_METHOD_LOG_' . $year, 'REQUEST_STRING', json_encode($outPutParam), 'ID = '.$methodId);
                        $this->db->UpdateClob('SYSINT_SERVICE_METHOD_LOG_' . $year, 'RESPONSE_STRING', json_encode($resultData), 'ID = '.$methodId);
                    } else {
                        $this->db->UpdateClob('SYSINT_SERVICE_METHOD_LOG', 'REQUEST_STRING', json_encode($outPutParam), 'ID = '.$methodId);
                        $this->db->UpdateClob('SYSINT_SERVICE_METHOD_LOG', 'RESPONSE_STRING', json_encode($resultData), 'ID = '.$methodId);
                    }
                }
                
                $errorDesc = 'error';
                $resultData['status']    = 'error';
                $resultData['data']      = $resultData;
                $resultData['result']    = '';
                $resultData['text']      = isset($resultData['return']['resultMessage']) ? $resultData['return']['resultMessage'] : 'Data empty!';
                $resultData['errorCode'] = 'empty';
            }
        } catch (Exception $ex) {
            
            $errorDesc = 'error';
            
            $resultData['data'] = array();
            $resultData['text'] = $ex->getMessage();
            $resultData['status'] = 'error';
        }
        
        return array('text' => $errorDesc, 'data' => Arr::changeKeyLower($resultData), 'message' => $resultData['text'], 'status' => $resultData['status']);
    }
    
    public function changeKeyRecursive($data) {
        if ($data && is_array($data)) {
            foreach ($data as $key => $row) {
                if (is_array($row)) {
                    if (!isset($row[0])) {
                        $row = self::changeKeyRecursive($row);
                        $data[$key] = array($row);
                    } else {
                        $temp = array();
                        foreach ($data[$key] as $skey => $svalue) {
                            $svalue = self::changeKeyRecursive($svalue);
                            array_push($temp, $svalue);
                        }

                        $data[$key] = $temp;
                    }
                }
            }

        }
        return $data;
    }
    
    public function getSignatureKey($timeStamp = '', $departmentId = '') {
        $pkey = '';
        
        if ($departmentId) {
            
            $keyStr = Config::get('XYP_TOKEN_KEY_STR', 'departmentId='.$departmentId.';');
            
            if ($keyStr) {
                $pkey = $keyStr;
            } else {
                $fileUrl = Config::get('XYP_TOKEN_FILE_URL', 'departmentId='.$departmentId.';');
                if ($fileUrl) {
                    $pkey = file_get_contents(Config::getFromCacheDefault('UPLOADPATH', null, '') . $fileUrl);
                }
            }

            $accessToken = Config::get('XYP_TOKEN_ACCESS_TOKEN', 'departmentId='.$departmentId.';');
            
        } else {
            
            if (Config::getFromCache('XYP_TOKEN_KEY_STR')){
                $pkey = Config::getFromCache('XYP_TOKEN_KEY_STR');
            } else {
                if (Config::getFromCache('XYP_TOKEN_FILE_URL')) {
                    $pkey = file_get_contents(Config::getFromCacheDefault('UPLOADPATH', null, '') . Config::getFromCacheDefault('XYP_TOKEN_FILE_URL', null, ''));
                }
            }
            
            $accessToken = Config::getFromCache('XYP_TOKEN_ACCESS_TOKEN');
        }

        $timestamp = ($timeStamp) ? $timeStamp : time();
        openssl_sign($accessToken . '.' . $timestamp, $signature, $pkey, OPENSSL_ALGO_SHA256);

        return array(
            'accessToken' => $accessToken,
            'timeStamp'   => $timestamp,
            'signature'   => base64_encode($signature)
        );
    }
    
    public function golomtBankTransferV2($processRow, $param) {
        
        try {
            
            $errorDesc = '';
                
            $url = $processRow['WS_URL'].$processRow['CLASS_NAME'];
            
            $data = array('total_stud' => 500);
            $xml_data = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Document></Document>');

            Arr::array_to_xml($param, $xml_data);

            $params = $xml_data->asXML();
            $currentDate = Date::currentDate('Y-m-d-H-i-s');
            $detailCount = 1;
            $ids = 1;
            $paramDtl = array();
            /*
            $paramDtl   = $param['invBankBookToBankMultiGet_dtl'];
            $detailData = self::golomtGenerateDetailXml($paramDtl);*/
/*
            $currentDate = Date::currentDate('Y-m-d-H-i-s');
            $detailXml   = $detailData['xml'];
            $ids         = rtrim($detailData['id'], ',');
            $TxsCdId     = $detailData['TxsCdId'];
            $idIndexed   = $detailData['idIndexed'];*/
/*
            $sessionUserKeyDepartmentId = Ue::sessionUserKeyDepartmentId();
            $departmentId = $sessionUserKeyDepartmentId ? $sessionUserKeyDepartmentId : Ue::sessionDepartmentId();

            $OrganizationAnyBIC      = Config::get('OrganizationAnyBIC', 'departmentId='.$departmentId.';');
            $OrganizationVascoNumber = Config::get('OrganizationVascoNumber', 'departmentId='.$departmentId.';');

            var_dump($OrganizationAnyBIC);
            var_dump($OrganizationVascoNumber);
            die;

            $detailCount = $detailData['count'];

            $params = '<?xml version="1.0" encoding="UTF-8"?>
                        <Document>
                            <GrpHdr>
                                <MsgId>'. getUID() .'</MsgId>
                                <CreDtTm>'. Date::currentDate('Y-m-d\TH:i:s') .'</CreDtTm>
                                <TxsCd>'. $TxsCdId .'</TxsCd>
                                <NbOfTxs>'. $detailCount .'</NbOfTxs>
                                <CtrlSum>'. $param['amount'] .'</CtrlSum>
                                <InitgPty>
                                    <Id>
                                        <OrgId>
                                            <AnyBIC>'.$OrganizationAnyBIC.'</AnyBIC>
                                        </OrgId>
                                    </Id>
                                </InitgPty>
                                <Crdtl>
                                    <Lang>0</Lang>
                                    <LoginID>'.$OrganizationVascoNumber.'</LoginID>
                                    <RoleID>1</RoleID>
                                    <Pwds>
                                        <PwdType>3</PwdType>
                                        <Pwd>'. $param['Pwd'] .'</Pwd>
                                    </Pwds>
                                </Crdtl>
                            </GrpHdr>
                            <PmtInf>
                                <NbOfTxs>'. $detailCount .'</NbOfTxs>
                                <CtrlSum>'. $param['amount'] .'</CtrlSum>
                                <ForT>F</ForT>
                                <Dbtr>
                                    <Nm>'. $param['debitorName'] .'</Nm>
                                </Dbtr>
                                <DbtrAcct>
                                    <Id>
                                        <IBAN>'. $param['debitorIban'] .'</IBAN>
                                    </Id>
                                    <Ccy>'. $param['debitorCurrency'] .'</Ccy>
                                </DbtrAcct>
                                '.$detailXml.' 
                            </PmtInf>
                        </Document>';

*/
            file_put_contents('log/bank_input_'.$currentDate.'.xml', $params);

            $httpPost = array(
                'header' => array(
                    'Content-type: ' . $processRow['SERVICE_LANGUAGE_CODE'],
                    //'Authorization: Basic MnRJRGhya0RaTFAzbEN1azVwU29xNzVyR0NvYmx3V2g6czlhRTJ2MDRMenp5WEpScQ==',
                    'Content-Length: ' . strlen($params)
                ),
                'method'  => 'POST',
                'content' => $params,
                'curl_verify_ssl_peer' => false,
                'curl_verify_ssl_host' => false
            );

            $fp = @fopen($url, 'r', false, stream_context_create(
                    array(
                        'ssl' => array(
                            'verify_peer'       => false, // ayulgui baidliin uudnees verify_peer utgiig true bailgah heregtei bolno
                            'allow_self_signed' => true
                        ),
                        'http' => $httpPost
                    )
                )
            );

            if (!$fp) {
                @fclose($fp);
                throw new Exception('Банктай холбогдож чадсангүй!'); 
            }

            $result = stream_get_contents($fp);
            fclose($fp);

            $resultData = array();

            file_put_contents('log/bank_response_'.$currentDate.'.xml', $result); 

            $resultIntegration = Xml::createArray($result);
            $xmlResponseStatus = issetParam($resultIntegration['Document']['Header']['ResponseHeader']['Status']);

            $this->load->model('mdobject', 'middleware/models/');

            if ($detailCount == 1 && $xmlResponseStatus == 'SUCCESS') {

                $ResponseTrnId = issetParam($resultIntegration['Document']['Body']['TrnAddRs']['TrnIdentifier']['TrnId']);

                if ($TxsCdId == '1002' && $ResponseTrnId != '') {

                    $this->db->AutoExecute('CM_INV_BANK', array('IS_TRANSFER' => 1, 'BANK_ERROR_CODE' => null, 'BANK_ERROR_MESSAGE' => null), 'UPDATE', 'ID = '.$ids);

                    $resultData['data']   = issetParam($resultIntegration['Document']['Header']['ResponseHeader']);
                    $resultData['result'] = $resultData['data'];
                    $resultData['status'] = 'success';
                    $resultData['text']   = 'Гүйлгээ амжилттай хийгдлээ.';

                    $wfmAttrs = array(
                        array(
                            'newWfmStatusid' => '1534844795469197', 
                            'metaDataId'     => $paramDtl[0]['refStructureId'], 
                            'dataRow'        => array('id' => $paramDtl[0]['recordId'], 'wfmStatusId' => $paramDtl[0]['wfmStatusId']), 
                            'description'    => $resultData['text']
                        ), 
                        array(
                            'newWfmStatusid' => '1529649557722966', 
                            'metaDataId'     => $paramDtl[0]['iRefStructureId'], 
                            'dataRow'        => array('id' => $paramDtl[0]['invoiceId'], 'wfmStatusId' => $paramDtl[0]['iWfmStatusId']), 
                            'description'    => $resultData['text']
                        )
                    );

                    self::setWfmStatusByBank($wfmAttrs);

                } else {

                    $detailResponseStatus = issetParam($resultIntegration['Document']['Body']['TrnAddRs']['TrnIdentifier'][0]['Status']);

                    if ($detailResponseStatus == 'false') {

                        $xmlErrorMsg = issetParam($resultIntegration['Document']['Body']['TrnAddRs']['TrnIdentifier'][0]['Error']);

                        $this->db->AutoExecute('CM_INV_BANK', array('IS_TRANSFER' => 0, 'BANK_ERROR_CODE' => null, 'BANK_ERROR_MESSAGE' => $xmlErrorMsg), 'UPDATE', 'ID = '.$ids);

                        $resultData['data']   = issetParam($resultIntegration['Document']['Header']['ResponseHeader']);
                        $resultData['result'] = $resultData['data'];
                        $resultData['status'] = 'error';
                        $resultData['text']   = ($xmlErrorMsg ? $xmlErrorMsg : 'Гүйлгээ амжилтгүй боллоо.');

                        $wfmAttrs = array(
                            array(
                                'newWfmStatusid' => '1534844795726298', 
                                'metaDataId'     => $paramDtl[0]['refStructureId'], 
                                'dataRow'        => array('id' => $paramDtl[0]['recordId'], 'wfmStatusId' => $paramDtl[0]['wfmStatusId']), 
                                'description'    => $xmlErrorMsg
                            ), 
                            array(
                                'newWfmStatusid' => '1533536203222884', 
                                'metaDataId'     => $paramDtl[0]['iRefStructureId'], 
                                'dataRow'        => array('id' => $paramDtl[0]['invoiceId'], 'wfmStatusId' => $paramDtl[0]['iWfmStatusId']), 
                                'description'    => $xmlErrorMsg
                            )
                        );

                        self::setWfmStatusByBank($wfmAttrs);

                    } else {

                        $this->db->AutoExecute('CM_INV_BANK', array('IS_TRANSFER' => 1, 'BANK_ERROR_CODE' => null, 'BANK_ERROR_MESSAGE' => null), 'UPDATE', 'ID = '.$ids);

                        $resultData['data']   = issetParam($resultIntegration['Document']['Header']['ResponseHeader']);
                        $resultData['result'] = $resultData['data'];
                        $resultData['status'] = 'success';
                        $resultData['text']   = 'Гүйлгээ амжилттай хийгдлээ.';

                        $wfmAttrs = array(
                            array(
                                'newWfmStatusid' => '1534844795469197', 
                                'metaDataId'     => $paramDtl[0]['refStructureId'], 
                                'dataRow'        => array('id' => $paramDtl[0]['recordId'], 'wfmStatusId' => $paramDtl[0]['wfmStatusId']), 
                                'description'    => $resultData['text']
                            ), 
                            array(
                                'newWfmStatusid' => '1529649557722966', 
                                'metaDataId'     => $paramDtl[0]['iRefStructureId'], 
                                'dataRow'        => array('id' => $paramDtl[0]['invoiceId'], 'wfmStatusId' => $paramDtl[0]['iWfmStatusId']), 
                                'description'    => $resultData['text']
                            )
                        );

                        self::setWfmStatusByBank($wfmAttrs);
                    }
                }

            } elseif ($detailCount > 1 && $xmlResponseStatus == 'SUCCESS') {

                $detailResponses = $resultIntegration['Document']['Body']['TrnAddRs']['TrnIdentifier'];
                $successCount = $failedCount = 0;

                foreach ($detailResponses as $detailResponse) {

                    if ($detailResponse['Status'] == 'true') {

                        $this->db->AutoExecute('CM_INV_BANK', array('IS_TRANSFER' => 1, 'BANK_ERROR_CODE' => null, 'BANK_ERROR_MESSAGE' => null), 'UPDATE', 'ID = '.$detailResponse['CdtrId']);
                        $successCount++;

                        $wfmAttrs = array(
                            array(
                                'newWfmStatusid' => '1534844795469197', 
                                'metaDataId'     => $idIndexed[$detailResponse['CdtrId']]['refStructureId'], 
                                'dataRow'        => array('id' => $idIndexed[$detailResponse['CdtrId']]['recordId'], 'wfmStatusId' => $idIndexed[$detailResponse['CdtrId']]['wfmStatusId']), 
                                'description'    => 'Гүйлгээ амжилттай хийгдлээ'
                            ), 
                            array(
                                'newWfmStatusid' => '1529649557722966', 
                                'metaDataId'     => $idIndexed[$detailResponse['CdtrId']]['iRefStructureId'], 
                                'dataRow'        => array('id' => $idIndexed[$detailResponse['CdtrId']]['invoiceId'], 'wfmStatusId' => $idIndexed[$detailResponse['CdtrId']]['iWfmStatusId']), 
                                'description'    => 'Гүйлгээ амжилттай хийгдлээ'
                            )
                        );

                        self::setWfmStatusByBank($wfmAttrs);

                    } else {

                        $this->db->AutoExecute('CM_INV_BANK', array('IS_TRANSFER' => 0, 'BANK_ERROR_CODE' => null, 'BANK_ERROR_MESSAGE' => $detailResponse['Error']), 'UPDATE', 'ID = '.$detailResponse['CdtrId']);
                        $failedCount++;

                        $wfmAttrs = array(
                            array(
                                'newWfmStatusid' => '1534844795726298', 
                                'metaDataId'     => $idIndexed[$detailResponse['CdtrId']]['refStructureId'], 
                                'dataRow'        => array('id' => $idIndexed[$detailResponse['CdtrId']]['recordId'], 'wfmStatusId' => $idIndexed[$detailResponse['CdtrId']]['wfmStatusId']), 
                                'description'    => $detailResponse['Error']
                            ), 
                            array(
                                'newWfmStatusid' => '1533536203222884', 
                                'metaDataId'     => $idIndexed[$detailResponse['CdtrId']]['iRefStructureId'], 
                                'dataRow'        => array('id' => $idIndexed[$detailResponse['CdtrId']]['invoiceId'], 'wfmStatusId' => $idIndexed[$detailResponse['CdtrId']]['iWfmStatusId']), 
                                'description'    => $detailResponse['Error']
                            )
                        );

                        self::setWfmStatusByBank($wfmAttrs);
                    }
                }

                $resultData['data']   = issetParam($resultIntegration['Document']['Header']['ResponseHeader']);
                $resultData['result'] = $resultData['data'];
                $resultData['status'] = 'success';
                $resultData['text']   = "Нийт <strong>$detailCount</strong> гүйлгээнээс<br /><strong>$successCount</strong> амжилттай <strong>$failedCount</strong> амжилтгүй.";

            } else {

                $resultData['status']    = 'error';
                $resultData['data']      = issetParam($resultIntegration['Document']['Header']['ResponseHeader']);
                $resultData['result']    = $resultData['data'];
                $resultData['text']      = (isset($resultIntegration['Document']['Body']['Error']['ErrorDetail']['ErrorDesc']) && $resultIntegration['Document']['Body']['Error']['ErrorDetail']['ErrorDesc']) ? $resultIntegration['Document']['Body']['Error']['ErrorDetail']['ErrorDesc'] : Lang::line('msg_result_error');
                $resultData['errorCode'] = issetParam($resultIntegration['Document']['Body']['Error']['ErrorDetail']['ErrorCode']);

                $this->db->AutoExecute('CM_INV_BANK', array('BANK_ERROR_CODE' => $resultData['errorCode'], 'BANK_ERROR_MESSAGE' => $resultData['text']), 'UPDATE', 'ID IN ('.$ids.')');
                if ($paramDtl) {
                    foreach ($paramDtl as $dtl) {
                        $wfmAttrs = array(
                            array(
                                'newWfmStatusid' => '1534844795726298', 
                                'metaDataId'     => $dtl['refStructureId'], 
                                'dataRow'        => array('id' => $dtl['recordId'], 'wfmStatusId' => $dtl['wfmStatusId']), 
                                'description'    => $resultData['text']
                            ), 
                            array(
                                'newWfmStatusid' => '1533536203222884', 
                                'metaDataId'     => $dtl['iRefStructureId'], 
                                'dataRow'        => array('id' => $dtl['invoiceId'], 'wfmStatusId' => $dtl['iWfmStatusId']), 
                                'description'    => $resultData['text']
                            )
                        );

                        self::setWfmStatusByBank($wfmAttrs);
                    }
                }
            }


        } catch (Exception $ex) {
            
            $errorDesc = 'error';
            
            $resultData['data'] = array();
            $resultData['text'] = $ex->getMessage();
            $resultData['status'] = 'error';
        }
        
        return array('text' => $errorDesc, 'data' => $resultData, 'message' => $resultData['text'], 'status' => $resultData['status']);
    }
    
    public function getLastImportIdModel($bankId, $accountCode, $billDate = null) {
        
        $bankIdPh      = $this->db->Param(0);
        $accountCodePh = $this->db->Param(1);
        $where         = null;
        
        $bindVars = array($this->db->addQ($bankId), $this->db->addQ($accountCode));
        
        if ($billDate) {
            $billDatePh = $this->db->Param(2);
            $bindVars[] = $billDate;
            $where = ' AND '.$this->db->SQLDate('Y-m-d', 'BILL_DATE').' = '.$billDatePh;
        }
        
        $importId = $this->db->GetOne("
            SELECT 
                MAX(IMPORT_ID) 
            FROM CM_BANK_BILLING 
            WHERE BANK_ID = $bankIdPh 
                AND ACCOUNT = $accountCodePh 
                AND IS_AUTO = 1  
                AND IMPORT_ID IS NOT NULL 
                AND JOURNAL_ID IS NOT NULL" . $where, $bindVars);
        
        return $importId;
    }
    
    public function getEconomicClassCode($accountCode) {
        
        $row = $this->db->GetRow("
            SELECT 
                FED.CODE AS DEBIT_ECONOMIC_CLASS_CODE, 
                FEC.CODE AS CREDIT_ECONOMIC_CLASS_CODE 
            FROM CM_BANK_ACCOUNT CBA 
                LEFT JOIN FIN_ECONOMIC_CLASS FED ON FED.ID = CBA.DEBIT_ECONOMIC_CLASS_ID 
                LEFT JOIN FIN_ECONOMIC_CLASS FEC ON FEC.ID = CBA.CREDIT_ECONOMIC_CLASS_ID 
            WHERE CBA.BANK_ACCOUNT_NUMBER = ".$this->db->Param(0), 
            array($accountCode) 
        );
        
        return array(
            'debitEconomicClassCode' => issetParam($row['DEBIT_ECONOMIC_CLASS_CODE']), 
            'creditEconomicClassCode' => issetParam($row['CREDIT_ECONOMIC_CLASS_CODE'])
        );
    }
    
    public function getForecast5day($cityName = 'Улаанбаатар') {
        $currentDate = Date::currentDate('y_m_d');
        
        $cache = phpFastCache();
        $data = $cache->get('bpForecast5day_'.$currentDate);

        if ($data == null) {

            $url = 'http://tsag-agaar.gov.mn/forecast_xml';
            $result = file_get_contents($url);
            $data = Xml::createArray($result);
            
            if ($data) {
                $cache->set('bpForecast5day_'.$currentDate, $data, '144000000');
            }
        }
        
        (Array) $mainData = array();
        if (isset($data['xml']['forecast5day'])) {
            foreach ($data['xml']['forecast5day'] as $key => $row) {
                if (isset($row['city']) && $row['city'] === $cityName && isset($row['data']['weather'])) {
                    foreach ($row['data']['weather'] as $row) {
                        $row['filepath']= self::getweatherFileIcon($row['phenoIdDay']);
                        array_push($mainData, $row);
                    }
                    
                }
            }
        }
        
        return $mainData;
    }
    
    public function getweatherFileIcon($id = null) {
        $data = array ( 2 => array ( 'id' => '2', 'name' => 'Цэлмэг', 'filepath' => 'assets/custom/img/weather/weather-01.png', ), 3 => array ( 'id' => '3', 'name' => 'Үүлэрхэг', 'filepath' => 'assets/custom/img/weather/weather-02.png', ), 5 => array ( 'id' => '5', 'name' => 'Багавтар үүлтэй', 'filepath' => 'assets/custom/img/weather/weather-02.png', ), 7 => array ( 'id' => '7', 'name' => 'Багавтар үүлтэй', 'filepath' => 'assets/custom/img/weather/weather-02.png', ), 9 => array ( 'id' => '9', 'name' => 'Үүлшинэ', 'filepath' => 'assets/custom/img/weather/weather-03.png', ), 10 => array ( 'id' => '10', 'name' => 'Үүлшинэ', 'filepath' => 'assets/custom/img/weather/weather-03.png', ), 20 => array ( 'id' => '20', 'name' => 'Үүл багаснa', 'filepath' => 'assets/custom/img/weather/weather-02.png', ), 23 => array ( 'id' => '23', 'name' => 'Ялимгүй цас', 'filepath' => 'assets/custom/img/weather/weather-04.png', ), 24 => array ( 'id' => '24', 'name' => 'Ялимгүй цас', 'filepath' => 'assets/custom/img/weather/weather-04.png', ), 27 => array ( 'id' => '27', 'name' => 'Ялимгүй хур тунадас', 'filepath' => 'assets/custom/img/weather/weather-04.png', ), 28 => array ( 'id' => '28', 'name' => 'Ялимгүй хур тунадас', 'filepath' => 'assets/custom/img/weather/weather-04.png', ), 60 => array ( 'id' => '60', 'name' => 'Бага зэргийн бороо', 'filepath' => 'assets/custom/img/weather/weather-06.png', ), 61 => array ( 'id' => '61', 'name' => 'Бороо', 'filepath' => 'assets/custom/img/weather/weather-06.png', ), 63 => array ( 'id' => '63', 'name' => 'Их бороо', 'filepath' => 'assets/custom/img/weather/weather-06.png', ), 65 => array ( 'id' => '65', 'name' => 'Хур тунадас', 'filepath' => 'assets/custom/img/weather/weather-06.png', ), 66 => array ( 'id' => '66', 'name' => 'Их хур тунадас', 'filepath' => 'assets/custom/img/weather/weather-06.png', ), 67 => array ( 'id' => '67', 'name' => 'Аадар их хур тунадас', 'filepath' => 'assets/custom/img/weather/weather-06.png', ), 68 => array ( 'id' => '68', 'name' => 'Их усархаг бороо', 'filepath' => 'assets/custom/img/weather/weather-06.png', ), 71 => array ( 'id' => '71', 'name' => 'Цас', 'filepath' => 'assets/custom/img/weather/weather-08.png', ), 73 => array ( 'id' => '73', 'name' => 'Их цас', 'filepath' => 'assets/custom/img/weather/weather-08.png', ), 75 => array ( 'id' => '75', 'name' => 'Аадар их цас', 'filepath' => 'assets/custom/img/weather/weather-08.png', ), 80 => array ( 'id' => '80', 'name' => 'Бага зэргийн аадар', 'filepath' => 'assets/custom/img/weather/weather-05.png', ), 81 => array ( 'id' => '81', 'name' => 'Бага зэргийн аадар', 'filepath' => 'assets/custom/img/weather/weather-05.png', ), 82 => array ( 'id' => '82', 'name' => 'Аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png', ), 83 => array ( 'id' => '83', 'name' => 'Аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png', ), 84 => array ( 'id' => '84', 'name' => 'Усархаг аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png', ), 85 => array ( 'id' => '85', 'name' => 'Усархаг аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png', ), 86 => array ( 'id' => '86', 'name' => 'Усархаг ширүүн аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png', ), 87 => array ( 'id' => '87', 'name' => 'Усархаг ширүүн аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png', ), 90 => array ( 'id' => '90', 'name' => 'Аянга цахилгаантай бага зэргийн аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png', ), 91 => array ( 'id' => '91', 'name' => 'Аянга цахилгаантай бага зэргийн аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png', ), 92 => array ( 'id' => '92', 'name' => 'Аянга цахилгаантай аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png', ), 93 => array ( 'id' => '93', 'name' => 'Аянга цахилгаантай аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png', ), 94 => array ( 'id' => '94', 'name' => 'Аянга цахилгаантай усархаг аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png', ), 95 => array ( 'id' => '95', 'name' => 'Аянга цахилгаантай усархаг аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png', ), 96 => array ( 'id' => '96', 'name' => 'Аянга цахилгаантай усархаг ширүүн аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png', ), 97 => array ( 'id' => '97', 'name' => 'Аянга цахилгаантай усархаг ширүүн аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png', ), );
        $response = $data;
        
        if ($id) {
            $response = isset($data[$id]) ? $data[$id]['filepath'] : 'assets/custom/img/weather/weather-01.png';
        } 
        
        return $response;
    }
    
    public function callZmsService($processRow, $param) {
        
        try {
            
            $outPutParam = $headerParam = $param;
            $errorDesc = '';
            array_walk_recursive($param, 'changeValueArray');

            $soapOption = array(
                'trace' => 1,
                'exceptions' => 1,
                'soap_version' => SOAP_1_1,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'features' => SOAP_SINGLE_ELEMENT_ARRAYS | SOAP_USE_XSI_ARRAY_TYPE,
                'stream_context' => stream_context_create(array(
                    'ssl' => array(
                        'verify_peer' => false, 
                        'allow_self_signed' => true
                    )
                ))
            );
            
            $getInfoDetailedParam = array('arg0' => '', 'arg1' => $param['regid'], 'arg2' => $param['cid']);
            $getInfoDetailedResult = $this->ws->callSoapClient($processRow['WS_URL'], 'getInfoDetailed', $getInfoDetailedParam, $soapOption);

            if ($param['cid'] == null && $param['regid'] == null) {
                
                $data = null;
                $returnData['return']['resultMessage'] = 'Дээрх утганд тохирох зээлдэгчийн мэдээлэл олдсонгүй';
                
            } elseif ($getInfoDetailedResult->return == 'TIMEOUT') {
                
                $returnData['data'] = null;
                $returnData['return']['resultMessage'] = 'Таталт хийх явцад алдаа гарлаа. Та түр хүлээгээд дахин оролдоно уу';
                
            } elseif ($getInfoDetailedResult->return == 'nosession') {
                
                $returnData['data'] = null;
                $returnData['return']['resultMessage'] = 'Админд хандаж холболт хийлгэсэний дараа дахин оролдоно уу';
                
            } else {

                $getLoanInfoParam = array('arg0' => $param['cid']);

                $getCustDetailsListResult = $this->ws->callSoapClient($processRow['WS_URL'], 'getCustDetailsList', $getLoanInfoParam, $soapOption);
                $getLoanInfoResult = $this->ws->callSoapClient($processRow['WS_URL'], 'getLoanInfo', $getLoanInfoParam, $soapOption);

                $staffinfoData = array('arg0' => $param['cid'], 'arg1' => 'orgrelationinfo');
                $staffinfoResult = $this->ws->callSoapClient($processRow['WS_URL'], 'getRelatedInfo', $staffinfoData, $soapOption);

                if (!empty($getLoanInfoResult->return)) {
                    $a1 = json_encode($getLoanInfoResult->return);
                } else {
                    $a1 = null;
                }
                
                if (!empty($getCustDetailsListResult->return)) {
                    $a2 = json_encode($getCustDetailsListResult->return);
                } else {
                    $a2 = null;
                }
                
                if (!empty($staffinfoResult->return)) {
                    $a3 = json_encode($staffinfoResult);
                } else { 
                    $a3 = null;
                }
                
                if (!empty($getCustInfo->return)) {
                    $d = ($getCustInfo->return);
                    $data0 = $d[0];
                    $dataItem = $data0->item;
                    $item0 = $dataItem[0];
                    $sessionCode = $item0->field20;
                    $a3 = $sessionCode;
                } else {
                    $a3 = null;
                }

                $data['cid'] = $param['cid'];
                $data['getInfoDetailed'] = $getInfoDetailedResult->return;
                $data['getLoanInfo'] = $a1;
                $data['getCustDetails'] = $a2;
                $data['staffinfoResult'] = $a3;
                $returnData['return']['resultMessage'] = 'Хүсэлт амжилттай';
            }
            
            $returnData['return']['response'] = $data;

            $resultData = Arr::objectToArray($returnData);

            $methodId = getUID();
            $currentDate = Date::currentDate();
            $userId = Ue::sessionUserKeyId();
            
            if (isset($resultData['return']['response'])) {
                
                array_walk_recursive($outPutParam, 'changeValueBase');
                array_walk_recursive($resultData, 'changeValueBase');

                $resultData['return']['response']['createdDate'] = $currentDate;
                $resultData['data']   = $headerParam;
                $resultData['result'] = $resultData['return']['response'];
                $resultData['status'] = 'success';
                $resultData['text']   = $resultData['return']['resultMessage'];

                $data = array(
                    'ID' => $methodId,
                    'WEB_SERVICE_NAME' => $processRow['CLASS_NAME'],
                    'WEB_SERVICE_URL' => $processRow['WS_URL'],
                    'CREATED_DATE' => $currentDate,
                    'USER_ID' => $userId,
                    'IS_SUCCESSFUL' => '1'
                );
                $resultLog = $this->db->AutoExecute('SYSINT_SERVICE_METHOD_LOG', $data);

                if ($resultLog) {

                    $this->db->UpdateClob('SYSINT_SERVICE_METHOD_LOG', 'REQUEST_STRING', json_encode($outPutParam), 'ID = '.$methodId);
                    $this->db->UpdateClob('SYSINT_SERVICE_METHOD_LOG', 'RESPONSE_STRING', json_encode($resultData), 'ID = '.$methodId);

                    $dataDtl = array(
                        'ID' => getUID(),
                        'LOG_ID' => $methodId,
                        'REG_NUM' => isset($param['arg2']) ? $param['arg2'] : '',
                        'CITIZEN_REG_NUM' => isset($param['arg2']) ? $param['arg2'] : '',
                        'CITIZEN_FINGER' => isset($param['citizenFingerPrint']) ? base64_encode($param['citizenFingerPrint']) : '',
                        'OPERATOR_REG_NUM' => isset($param['operatorRegnum']) ? $param['operatorRegnum'] : '',
                        'OPERATOR_FINGER' => isset($param['operatorFingerPrint']) ? base64_encode($param['operatorFingerPrint']) : '',
                        'LEGAL_ENTITY_NUMBER' => isset($param['legalEntityNumber']) ? $param['legalEntityNumber'] : '',
                        'PROPERTY_NUMBER' => isset($param['propertyNumber']) ? $param['propertyNumber'] : '',
                    );

                    $this->db->AutoExecute('SYSINT_SERVICE_METHOD_LOG_DTL', $dataDtl);
                }
                
            } else {
                
                array_walk_recursive($outPutParam, 'changeValueBase');
                
                if ($resultData) {
                    array_walk_recursive($resultData, 'changeValueBase');
                }

                $data = array(
                    'ID' => $methodId,
                    'WEB_SERVICE_NAME' => $processRow['CLASS_NAME'],
                    'WEB_SERVICE_URL' => $processRow['WS_URL'],
                    'CREATED_DATE' => $currentDate,
                    'USER_ID' => $userId,
                    'IS_SUCCESSFUL' => '0'
                );
                $resultLog = $this->db->AutoExecute('SYSINT_SERVICE_METHOD_LOG', $data);

                if ($resultLog) {
                    $this->db->UpdateClob('SYSINT_SERVICE_METHOD_LOG', 'REQUEST_STRING', json_encode($outPutParam), 'ID = '.$methodId);
                    $this->db->UpdateClob('SYSINT_SERVICE_METHOD_LOG', 'RESPONSE_STRING', json_encode($resultData), 'ID = '.$methodId);
                }

                $errorDesc = 'error';
                $resultData['status']    = 'error';
                $resultData['data']      = $resultData;
                $resultData['result']    = '';
                $resultData['text']      = isset($resultData['return']['resultMessage']) ? $resultData['return']['resultMessage'] : 'Data empty!';
                $resultData['errorCode'] = 'empty';
            }

        } catch (Exception $ex) {

            $errorDesc = 'error';

            $resultData['data'] = array();
            $resultData['text'] = $ex->getMessage();
            $resultData['status'] = 'error';
        }

        return array('text' => $errorDesc, 'data' => $resultData, 'message' => $resultData['text'], 'status' => $resultData['status']);
    }
    
}
