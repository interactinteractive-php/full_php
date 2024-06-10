<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdpos_Model extends Model {
    
    private static $gfServiceAddress = GF_SERVICE_ADDRESS;
    private static $billNumByUserKeyIdDvId = 1519374507943081; //pos_bill_number_list
    private static $cashierInfoByEmpIdDvId = 1519727688104550; //storePosEmp
    private static $storeInfoByUserIdDvId = 1527058447865785; //sdmOrderSession
    private static $bankInfoByStoreIdDvId = 1519877141403197; //pos_bank_list
    private static $getItemProcessCode = 'POS_ITEM_LIST_GET_004';
    private static $getItemProcessCodeNoGift = 'pharmacyPosItemListGet_004';
    private static $voucherListDvId = 1521434731340310; //pos_vaucher_list
    private static $getVoucherListDvId = 1521442582511966; //pos_check_voucher_list
    private static $getBonusCardListDvId = 1521715716667141; //get_loyalty_card_discount_percent
    private static $getItemListDvId = 1521704147750383; //pos_autocomplete_item_list
    private static $getServiceListDvId = 1522322157379237; //pos_services_list
    private static $refNumberCallCount = 0;
    private static $receivedVoucherSerialNumber = array();
    private static $emdWsUrl = 'https://ws.emd.gov.mn/';
    private static $isSpliteCustomerSales = false;
    private static $isBonusCard = false;

    public function __construct() {
        parent::__construct();
    }
    
    public function getPosInfoModel($storeId = null, $posId = null, $cashierId = null) {
        
        $sessionEmployeeId = Ue::sessionEmployeeId();
        
        $param = array(
            'systemMetaGroupId' => self::$cashierInfoByEmpIdDvId,
            'showQuery' => 0,
            'ignorePermission' => 1, 
            'criteria' => array(
                'employeeId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $sessionEmployeeId
                    )
                )
            )
        );
        
        if ($storeId) {
            $param['criteria']['storeId'][] = array('operator' => '=', 'operand' => $storeId);
        }
        
        if ($posId) {
            $param['criteria']['cashRegisterId'][] = array('operator' => '=', 'operand' => $posId);
        }
        
        if ($cashierId) {
            $param['criteria']['cashierId'][] = array('operator' => '=', 'operand' => $cashierId);
        }

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($cashierId && isset($data['result']) && isset($data['result'][0])) {
            return $data['result'][0];
        }
       
        return $data;
    }
    
    public function setPOSSessionModel($posv3 = false) {
                
        if (Session::isCheck(SESSION_PREFIX.'cashierId')) {
            return array('status' => 'success'); 
        }
        
        $data = self::getPosInfoModel();
        
        if (isset($data['result']) && isset($data['result'][0])) {
            
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);
            
            $rowsCount = count($data['result']);
            
            if ($rowsCount == 1) {
                
                $cashierInfo = $data['result'][0];
                
            } elseif ($rowsCount > 1 && Session::isCheck(SESSION_PREFIX.'cashierId') == false) {
                
                return array('status' => 'chooseCashier', 'data' => $data['result']);   
            }
        }

        if (isset($cashierInfo)) {
            if ($cashierInfo['isclosed'] === '1') {
                return array('status' => 'error', 'message' => Lang::line('isClosedPos'));
            }
            return self::setSessionPosByRow($cashierInfo, $posv3);
        } else {
            return array('status' => 'error', 'message' => Lang::line('POS_0057'));
        }
    }
    
    public function setSessionPosByRow($cashierInfo, $posv3 = false) {

        $sessionEmployeeId = Ue::sessionEmployeeId();
        
        $param = array(
            'systemMetaGroupId' => self::$cashierInfoByEmpIdDvId,
            'showQuery' => 0,
            'ignorePermission' => 1, 
            'criteria' => array(
                'employeeId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $sessionEmployeeId
                    )
                )
            )
        );        
        if ($cashierInfo["storeid"]) {
            $param['criteria']['storeId'][] = array('operator' => '=', 'operand' => $cashierInfo["storeid"]);
        }        
        if ($cashierInfo["cashregisterid"]) {
            $param['criteria']['cashRegisterId'][] = array('operator' => '=', 'operand' => $cashierInfo["cashregisterid"]);
        }        
        if ($cashierInfo["cashierid"]) {
            $param['criteria']['cashierId'][] = array('operator' => '=', 'operand' => $cashierInfo["cashierid"]);
        }

        // pa($param);
        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getRowDataViewCommand, $param);
        
        if (isset($data['result'])) {
            $cashierInfo = $data['result'];

            if (issetParam($cashierInfo["ipterminaldtl"])) {
                $terminalPos = Arr::groupByArrayOnlyRow($cashierInfo["ipterminaldtl"], "bankcode", "terminalid");
                Session::set(SESSION_PREFIX.'ipterminals', $terminalPos);                
            }
        }        
        
        $sessionUserKeyDepartmentId = Ue::sessionUserKeyDepartmentId();
        $departmentId = $sessionUserKeyDepartmentId ? $sessionUserKeyDepartmentId : Ue::sessionDepartmentId();
        
        $orgRow = $this->db->GetRow("   
            SELECT
                DEPARTMENT_ID 
            FROM ORG_DEPARTMENT 
            WHERE VATSP_NUMBER IS NOT NULL 
                AND ROWNUM = 1 
            START WITH DEPARTMENT_ID = $departmentId   
            CONNECT BY PRIOR PARENT_ID = DEPARTMENT_ID");
        
        if (isset($orgRow['DEPARTMENT_ID']) && $orgRow['DEPARTMENT_ID']) {
            $departmentId = $orgRow['DEPARTMENT_ID'];
        }

        $organizationId = issetParam($cashierInfo['vatnumber']) ? $cashierInfo['vatnumber'] : Config::get('OrganizationID', 'departmentId='.$departmentId.';');

        Session::set(SESSION_PREFIX.'vatNumber', $organizationId);
        Session::set(SESSION_PREFIX.'storeCode', $cashierInfo['storecode']);
        Session::set(SESSION_PREFIX.'storeName', $cashierInfo['storename']);
        Session::set(SESSION_PREFIX.'cashRegisterCode', $cashierInfo['poscode']);
        Session::set(SESSION_PREFIX.'cashRegisterName', $cashierInfo['posname']);
        Session::set(SESSION_PREFIX.'messageText', issetParam($cashierInfo['messagetext']));
        Session::set(SESSION_PREFIX.'isConfirmSaleDate', issetParam($cashierInfo['isconfirmsaledate']));
        Session::set(SESSION_PREFIX.'posAdminpassword', issetParam($cashierInfo['adminpassword']));        
        
        if (issetParam($cashierInfo['isnotsendvatsp']) == '1' || issetParam($cashierInfo['isbasketonly']) == '1') {
            
            Session::set(SESSION_PREFIX.'isNotSendVatsp', '1');
            
            if (issetParam($cashierInfo['isbasketonly']) == '1') {
                Session::set(SESSION_PREFIX.'isBasketOnly', '1');
            }
            
        } elseif (!$posv3) {
            
            $posApiPath = $organizationId.'\\'.$cashierInfo['storecode'].'\\'.$cashierInfo['poscode'];

            $checkApiParam = array(
                'function' => 'getinformation', 
                'vatNumber' => $posApiPath
            );

            $response = $this->ws->redirectPost(Mdpos::getPosApiServiceAddr(), $checkApiParam);

            if ($response == 'null') {
                return array('status' => 'error', 'message' => $this->lang->line('POS_0077') . ' '.$posApiPath);
            }
        }
        
        $isUseCandy = issetParam($cashierInfo['isusecandy']);
        
        if (defined('CONFIG_POS_PAYMENT_CANDY') && CONFIG_POS_PAYMENT_CANDY) {
            
            $candyToken = issetParam($cashierInfo['candytoken']);
            
            /*if ($isUseCandy == '1' && !$candyToken) {
                
                return array('status' => 'error', 'message' => 'Monpay хэрэглэнэ гэсэн тохиргоотой байгаа боловч Token тохируулаагүй байна.');
            
            } else*/
            if ($isUseCandy == '1') {
                
                Session::set(SESSION_PREFIX.'posCandyToken', $candyToken);
            }
            
            Session::set(SESSION_PREFIX.'posIsUseCandy', $isUseCandy);
            
        } else {
            Session::set(SESSION_PREFIX.'posIsUseCandy', '0');
        }
        
        if (defined('CONFIG_POS_PAYMENT_REDPOINT') && CONFIG_POS_PAYMENT_REDPOINT) {
            
            $redpointMerchantNo = issetParam($cashierInfo['redpointmerchantno']);
            $redpointTerminalNo = issetParam($cashierInfo['redpointterminalno']);
            
            if ($redpointMerchantNo && $redpointTerminalNo) {
                
                $redPointToken = issetParam($cashierInfo['redpointtoken']);
                
                Session::set(SESSION_PREFIX.'posRedpointMerchantNo', $redpointMerchantNo);
                Session::set(SESSION_PREFIX.'posRedpointTerminalNo', $redpointTerminalNo);
                Session::set(SESSION_PREFIX.'posRedPointToken', $redPointToken);
                
                $redPointInfo = self::getRedPointTerminalSettings();

                if ($redPointInfo['status'] == 'success') {
                    
                    if ($redPointInfo['award'] == 'YES') {
                        Session::set(SESSION_PREFIX.'posRedPointIsAward', '1');
                    }

                    if ($redPointInfo['offer_redemption'] == 'YES') {
                        Session::set(SESSION_PREFIX.'posRedPointIsItems', '1');
                    }
                    
                }  
            }
        }

        $posHeaderName  = Config::get('POS_HEADER_NAME', 'departmentId='.$departmentId.';');
        $posContactInfo = Config::get('POS_CONTACT_INFO', 'departmentId='.$departmentId.';');
        $posLogo        = Config::get('POS_LOGO', 'departmentId='.$departmentId.';');
        
        Session::set(SESSION_PREFIX.'storeId', $cashierInfo['storeid']);
        Session::set(SESSION_PREFIX.'cashierId', $cashierInfo['cashierid']);
        Session::set(SESSION_PREFIX.'cashRegisterId', $cashierInfo['cashregisterid']);

        Session::set(SESSION_PREFIX.'cashierName', $cashierInfo['cashiername']);
        Session::set(SESSION_PREFIX.'cashierCode', issetParam($cashierInfo['cashiercode']));

        $posHeaderName = issetParam($cashierInfo['posbillprintname']) != '' ? $cashierInfo['posbillprintname'] : $posHeaderName;
        
        Session::set(SESSION_PREFIX.'posHeaderName', $posHeaderName);
        Session::set(SESSION_PREFIX.'posContactInfo', $posContactInfo);
        Session::set(SESSION_PREFIX.'posDistrictCode', $cashierInfo['posapidistrictcode']);
        Session::set(SESSION_PREFIX.'posUseIpTerminal', issetParam($cashierInfo['ipterminaldtl']) ? "1" : "0");
        Session::set(SESSION_PREFIX.'posSocialPayTerminal', issetParam($cashierInfo['socialpayterminalid']));
        Session::set(SESSION_PREFIX.'posTypeCode', issetParam($cashierInfo['typecode']));
        Session::set(SESSION_PREFIX.'isEditBasketPrice', issetParam($cashierInfo['iseditbasketprice']));
        Session::set(SESSION_PREFIX.'posActiveLogin', '0');

        Session::set(SESSION_PREFIX.'posIsService', $cashierInfo['isservice']);
        
        if (Config::getFromCache('CONFIG_POS_HEALTHRECIPE')) {
            Session::set(SESSION_PREFIX.'posEmdClientId', $cashierInfo['clientid']);
            Session::set(SESSION_PREFIX.'posEmdClientSecret', $cashierInfo['clientsecret']);
        }
        
        if (issetParam($cashierInfo['poslogo']) != '' && (file_exists($cashierInfo['poslogo']) || file_exists('storage/uploads/files/logos/' . $cashierInfo['poslogo']))) {
            Session::set(SESSION_PREFIX.'posLogo', $cashierInfo['poslogo']);
        } elseif ($posLogo && file_exists('storage/uploads/files/logos/' . $posLogo)) {
            Session::set(SESSION_PREFIX.'posLogo', 'storage/uploads/files/logos/' . $posLogo);
        }

        return array('status' => 'success');
    }
    
    public function getBankListModel() {
        
        //$cache = phpFastCache(); 
        //$data = $cache->get('getPosBankList');               
        $data = null;               
        
        $cashRegisterId = Session::get(SESSION_PREFIX.'cashRegisterId');
        $storeId = Session::get(SESSION_PREFIX.'storeId');
        $param = array(
            'systemMetaGroupId' => self::$bankInfoByStoreIdDvId,
            'ignorePermission' => 1, 
            'showQuery' => 0,
            'criteria' => array(
                'storeId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $storeId
                    )
                ),
                'cashRegisterId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $cashRegisterId
                    )
                )
            )
        );

        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($result['result']) && isset($result['result'][0])) {
            unset($result['result']['aggregatecolumns']);
            unset($result['result']['paging']);
            $data = $result['result'];
        } else {
            $data = null;
        }
            
        return $data;
    }
    
    public function getBankListCardModel() {
        
        //$cache = phpFastCache(); 
        //$data = $cache->get('getPosBankList');               
        $data = null;               
        
        $cashRegisterId = Session::get(SESSION_PREFIX.'cashRegisterId');
        $storeId = Session::get(SESSION_PREFIX.'storeId');
        $param = array(
            'systemMetaGroupId' => Config::getFromCache('POS_CARD_BANK_LIST') ? Config::getFromCache('POS_CARD_BANK_LIST') : self::$bankInfoByStoreIdDvId,
            'ignorePermission' => 1, 
            'showQuery' => 0,
            'criteria' => array(
                'storeId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $storeId
                    )
                ),
                'cashRegisterId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $cashRegisterId
                    )
                )
            )
        );

        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($result['result']) && isset($result['result'][0])) {
            unset($result['result']['aggregatecolumns']);
            unset($result['result']['paging']);
            $data = $result['result'];
        } else {
            $data = null;
        }
            
        return $data;
    }
    
    public function getBankListTransferModel() {
        
        //$cache = phpFastCache(); 
        //$data = $cache->get('getPosBankList');               
        $data = null;               
        
        $cashRegisterId = Session::get(SESSION_PREFIX.'cashRegisterId');
        $storeId = Session::get(SESSION_PREFIX.'storeId');
        $param = array(
            'systemMetaGroupId' => Config::getFromCache('POS_TRANSFER_BANK_LIST') ? Config::getFromCache('POS_TRANSFER_BANK_LIST') : self::$bankInfoByStoreIdDvId,
            'ignorePermission' => 1, 
            'showQuery' => 0,
            'criteria' => array(
                'storeId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $storeId
                    )
                ),
                'cashRegisterId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $cashRegisterId
                    )
                )
            )
        );

        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($result['result']) && isset($result['result'][0])) {
            unset($result['result']['aggregatecolumns']);
            unset($result['result']['paging']);
            $data = $result['result'];
        } else {
            $data = null;
        }
            
        return $data;
    }

    public function getBillNumModel() {
        
        $sessionUserKeyId = Ue::sessionUserKeyId();
        
        $param = array(
            'systemMetaGroupId' => self::$billNumByUserKeyIdDvId,
            'ignorePermission' => 1, 
            'showQuery' => 0,
            'criteria' => array(
                'sessionUserKeyId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $sessionUserKeyId
                    )
                ),
                'cashRegisterId' => array(
                    array(
                        'operator' => '=',
                        'operand' => Session::get(SESSION_PREFIX.'cashRegisterId')
                    )
                )
            )
        );

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result']) && isset($data['result'][0])) {
            return $data['result'][0]['billnumber'];
        } else {
            return 1;
        }
    }
    
    public function getItemByCodeModel() {
        
        $itemCode = Str::lower(Input::post('code'));
        $itemId = Str::lower(Input::post('itemId'));
        
        if ($itemCode) {
            $itemCodePh = $this->db->Param(0);
            $bindVars = array($this->db->addQ($itemCode));

            $segmentJoin = '1 = 1';

            if (Config::getFromCache('IS_CLOUD') == '1') {
                
                $sessionValues = Session::get(SESSION_PREFIX.'sessionValues');
            
                if ($sessionCompanyDepartmentId = issetParam($sessionValues['sessioncompanydepartmentid'])) {
                    $segmentJoin = "II.COMPANY_DEPARTMENT_ID = $sessionCompanyDepartmentId "; 
                }
            }            

            $itemRow = $this->db->GetRow("
                SELECT 
                    II.ITEM_ID, 
                    COALESCE(IIB.BARCODE, II.L0_BARCODE, II.L1_BARCODE, II.L2_BARCODE) AS BARCODE 
                FROM IM_ITEM II 
                    LEFT JOIN IM_ITEM_BARCODE IIB ON II.ITEM_ID = IIB.ITEM_ID 
                WHERE (LOWER(II.ITEM_CODE) = $itemCodePh 
                    OR II.L0_BARCODE = $itemCodePh 
                    OR II.L1_BARCODE = $itemCodePh 
                    OR II.L2_BARCODE = $itemCodePh 
                    OR IIB.BARCODE = $itemCodePh) AND $segmentJoin", $bindVars);

            $itemId = issetParam($itemRow['ITEM_ID']);
        } else {
            $itemId = $itemId;
        }
        
        if (!$itemId) {
            return array();
        }
        
        $storeId        = Session::get(SESSION_PREFIX.'storeId');
        $cashRegisterId = Session::get(SESSION_PREFIX.'cashRegisterId');
        
        $param = array(
            'itemId'         => $itemId, 
            'storeId'        => $storeId, 
            'vipLockerId'    => Input::post('vipLockerId'), 
            'customerId'     => Input::post('lockerCustomerId'), 
            'cashRegisterId' => $cashRegisterId
        );
        
        if (Input::isEmpty('packageIdItem') == false) {
            $param['packageItemId'] = Input::post('packageIdItem');
        }
        
        if (Input::isEmpty('empCustomerId') == false) {
            
            $param['customerId'] = Input::post('empCustomerId');
            $getProcessCode      = 'GET_ITEM_DISCOUNT_BY_CUSTOMER_004';
            
        } else {
            
            if (Config::getFromCache('CONFIG_POS_ITEM_GET')) {
            
                if (Config::getFromCache('CONFIG_POS_ITEM_GET') == 'nogift') {
                    
                    $isCheckEndQty = true;
                    $isReceiptNumber = Input::post('isReceiptNumber');

                    if ($isReceiptNumber == 'true') {

                        $regNumber = Input::post('receiptRegNumber');
                        $age       = getAgeFromRegNumber($regNumber);
						
                        $cashierInfo = null;
                        $sessionEmployeeId = Ue::sessionEmployeeId();        
                        $param222 = array(
                                'systemMetaGroupId' => self::$cashierInfoByEmpIdDvId,
                                'showQuery' => 0,
                                'ignorePermission' => 1, 
                                'criteria' => array(
                                        'employeeId' => array(
                                                array(
                                                        'operator' => '=',
                                                        'operand' => $sessionEmployeeId
                                                )
                                        )
                                )
                        );        
                        $data222 = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param222);

                        if (isset($data222['result']) && isset($data222['result'][0])) {
                                $cashierInfo = $data222['result'][0];
                        }
                        if ($cashierInfo) {
                                $getToken = self::emdGetToken($cashierInfo['clientid'], $cashierInfo['clientsecret']);
                        } else {
                                $getToken = self::emdGetToken();
                        }							

                        $access_token = $getToken['access_token'];
                        $getCheck100Person = self::emdCheck100Person($access_token, $regNumber);

                        if ($getCheck100Person['code'] == '200') {							
                            $param['age'] = $age;
                        }   

                        $param['tbltType'] = ($age > 5) ? 1 : 0;

                        $receiptDetails = Input::post('receiptDetails');

                        if (isset($receiptDetails['receiptDetails'])) {

                            $param['tbltIds'] = Arr::implode_key(',', $receiptDetails['receiptDetails'], 'tbltId', true);
                        }

                        $getProcessCode = 'imDiscountDrugGet_004';

                    } else {
                        $getProcessCode = self::$getItemProcessCodeNoGift;
                    }
                    
                } else {
                    $getProcessCode = Config::getFromCache('CONFIG_POS_ITEM_GET');
                }

            } else {
                $getProcessCode = self::$getItemProcessCode;
            }
        }
        
        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, $getProcessCode, $param);
        
        if ($data['status'] == 'success' && isset($data['result'])) {
            
            $result = $data['result'];
            
            if (isset($isCheckEndQty)) {
                
                if (isset($result['posimitemkeylist']) && count($result['posimitemkeylist']) == 1) {
                    
                    $firstRow = $result['posimitemkeylist'][0];

                    if ($firstRow['endqty'] == 0) {
                        
                        return array(
                            'status'  => 'noendqty', 
                            'message' => $this->lang->lineVar('POS_0078', array('serialnumber' => $firstRow['serialnumber'], 'itemname' => $result['itemname'])), 
                            'itemId'  => $itemId  
                        );
                    }
                    
                } elseif (!isset($result['posimitemkeylist'])) {
                    
                    return array(
                        'status'  => 'noendqty', 
                        'message' => $this->lang->lineVar('POS_0079', array('itemname' => $result['itemname'])), 
                        'itemId'  => $itemId  
                    );
                }
            }
            
            if (array_key_exists('tablerulelist', $result)) {
                
                $ruleList = $result['tablerulelist'];
                $policyList = $result['tablepolicylist'];

                unset($result['tablerulelist']);
                unset($result['tablepolicylist']);
                
            } else {
                $ruleList = $policyList = null;
            }
            
            $itemKeys = array_map(function($val){ return is_null($val) ? '' : $val; }, $result);
            
            $itemKeys['tablerulelist'] = $ruleList;
            $itemKeys['tablepolicylist'] = $policyList;
            $itemKeys['getProcessCode'] = $getProcessCode;
            
            /*if (isset($itemRow['BARCODE']) && $itemRow['BARCODE']) {
                $itemKeys['barcode'] = $itemRow['BARCODE'];
            }*/
            
            return $itemKeys;
            
        } else {
            return array();
        }
    }
    
    public function getVouckerListModel($couponTypeId, $amount, $percent, $storeId) {
        
        $param = array(
            'systemMetaGroupId' => self::$voucherListDvId,
            'ignorePermission' => 1, 
            'showQuery' => 0,
            'criteria' => array(
                'filterCouponTypeId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $couponTypeId
                    )
                ), 
                'filterAmount' => array(
                    array(
                        'operator' => '=',
                        'operand' => $amount
                    )
                ), 
                'filterPercent' => array(
                    array(
                        'operator' => '=',
                        'operand' => $percent
                    )
                ), 
                'filterStoreId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $storeId
                    )
                )
            )
        );
        
        if (is_countable(self::$receivedVoucherSerialNumber) && count(self::$receivedVoucherSerialNumber) > 0) {
            
            $param['criteria']['serialnumber'] = array(
                array(
                    'operator' => 'NOT IN',
                    'operand' => Arr::implode_r(',', self::$receivedVoucherSerialNumber, true)
                )
            );
        }

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result']) && isset($data['result'][0])) {
            
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);
            
            self::$receivedVoucherSerialNumber[] = $data['result'][0]['serialnumber'];
                    
            return $data['result'][0];
        } else {
            return false;
        }
    }
    
    public function getOrganizationInfoModel() {
        
        $regNumber = Str::upper(Input::post('regNumber'));
        
        try {
            
            $regNumber = urlencode($regNumber);
            
            if ($posGetMerchantRegnoUrl = Config::getFromCache('posGetMerchantRegnoUrl')) {
                $url = $posGetMerchantRegnoUrl . $regNumber;
            } else {
                $url = 'https://info.ebarimt.mn/rest/merchant/info?regno=' . $regNumber;
            }

            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json;charset=UTF-8'));
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $str = curl_exec($ch);
            if (curl_errno($ch)) {
                return json_encode(array('name' => '', 'message' => 'Ebarimt-тай холбогдож чадсангүй.<br>URL: '.$url.' <br>Error:'.curl_error($ch)));
            }            
            curl_close($ch); 
            
            if (strpos($str, 'vatpayer') === false) {
                $str = '{"name": "", "vatpayer": true, "message": "Татвараас мэдээлэл шалгахад алдаа гарлаа. Та татвар төлөгчийн нэрийг гараас оруулна уу."}';
            }

            return $str;
            
        } catch (Exception $ex) {
            return json_encode(array('name' => '', 'message' => $ex->getMessage()));
        }
    }
    
    public function isLotteryGenerate($totalAmount, $storeId) {
        
        $param = array(
            'systemMetaGroupId' => '1543420106935051',
            'ignorePermission' => 1, 
            'showQuery' => 0,
            'criteria' => array(
                'lineTotalAmount' => array(
                    array(
                        'operator' => '=',
                        'operand' => $totalAmount
                    )
                    ),
                'storeId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $storeId
                    )
                )
            )
        );

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result']) && isset($data['result'][0]['lotteryqty']) && $data['result'][0]['lotteryqty'] > 0) {
            return true;
        } 
        
        return false;
    }
    
    public function printAskLoyaltyPointModel() {
        
        if (Input::postCheck('payAmount') == false) {
            $response = array('status' => 'warning', 'message' => 'Төлбөр төлөлтийн мэдээлэл олдсонгүй. Та дахин оролдоно уу.');
            return $response;
        }
        
        $discountAmount   = Number::decimal(Input::post('discountAmount'));
        $totalAmount      = Number::decimal(Input::post('payAmount'));
        $voucherAmount    = Number::decimal(Input::post('voucherAmount'));
        $bonusCardAmount  = Number::decimal(Input::post('bonusCardAmount'));
        $totalBonusAmount = $voucherAmount + $bonusCardAmount;
        $subTotal         = $totalAmount + $discountAmount;
        
        if ($totalBonusAmount > 0) {
            
            if ($totalBonusAmount >= $totalAmount) {
                
                $response = array('status' => 'directprint', 'message' => '100% хөнгөлөлттэй тул урамшуулал олгохгүй.');
                return $response;
                
            } else {
                $discountAmount = $discountAmount + $totalBonusAmount;  
                $totalAmount    = $subTotal - $discountAmount;
            }
        }
        
        $param = array(
            'systemMetaGroupId' => '1550133513914129',
            'ignorePermission' => 1, 
            'showQuery' => 0,
            'criteria' => array(
                'filterStoreId' => array(
                    array(
                        'operator' => '=',
                        'operand' => Session::get(SESSION_PREFIX.'storeId')
                    )
                ), 
                'filterAmount' => array(
                    array(
                        'operator' => '=',
                        'operand' => $totalAmount
                    )
                )
            )
        );
        
        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if (isset($data['result']) && isset($data['result'][0])) {
            
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);
            
            $row = $data['result'][0];
            
            $percent = $row['discountpercent'] ? $row['discountpercent'] : 0;
            $amount  = $row['discountamount'] ? $row['discountamount'] : 0;
            
            if ($percent > 0) {
                
                return array(
                    'status'      => 'success', 
                    'candyAmount' => number_format(($percent / 100) * $totalAmount, 2, '.', ''), 
                    'redPoint'    => floor($totalAmount / 100)
                );
                
            } elseif ($amount > 0) {
                
                return array(
                    'status'      => 'success', 
                    'candyAmount' => $amount, 
                    'redPoint'    => floor($totalAmount / 100)
                );
            }
        }
        
        return array('status' => 'directprint', 'message' => 'Урамшуулалын мэдээлэл олдсонгүй.');
    }
    
    public function getCandyToken() {
        
        //$token = '2b71af573da51d253b770ba775a34fdd1f21772282d69582a265b23f39ecd71f2ba8f593f3a7eb983e7e156b3dd4361ab9bd8736aa3e2145205e0091ae23ed63';
        //$token = '8563c379fa64ac7042d8c265e57f8e1b40cca1099ea1a38ee31abd10e9b3f2c0a77a04393513b1e1d6ed39182fd4f72883a5ca7e0881da791e12d5df866cbd32';
        //$token = Config::getFromCache('POS_CANDY_TOKEN');
        
        $token = Session::get(SESSION_PREFIX.'posCandyToken');
        
        return $token;
    }
    
   
    
    public function candyUserCheckModel($paymentData) {
        
        $token = self::getCandyToken();
        
        if (!$token) {
            return array('status' => 'error', 'message' => 'Monpay: No token');
        }
        
        $candyType   = Input::param($paymentData['loyaltyCandyTypeCode']);
        $candyNumber = Input::param($paymentData['loyaltyCandyNumber']);
        
        $params = '<request>'
            . '<customer>'.$candyNumber.'</customer>' 
            . '<customer.system>'.$candyType.'</customer.system>' 
        . '</request>';
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.candy.mn/resource/partner/v1/customer',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POST => true, 
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_HEADER => false, 
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $token,
                'Content-Type: application/xml'
            )
        ));       

        $response = curl_exec($curl);       
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            $result = array('status' => 'error', 'message' => 'Монпэй: ' . $err);
        } else {
            
            $xmlArray = Xml::createArray($response);
            
            if (isset($xmlArray['response']['code'])) {
                
                $responseCode = $xmlArray['response']['code'];
                
                if ($responseCode == '0') {
                    
                    $result = array(
                        'status'      => 'success', 
                        'candyType'   => $candyType, 
                        'candyNumber' => $candyNumber, 
                        'candyAmount' => Number::decimal(Input::param($paymentData['loyaltyCandyAmount']))
                    );
                    
                } else {
                    $result = array('status' => 'info', 'message' => 'Монпэй: ' . issetParam($xmlArray['response']['info']));
                }
                
            } else {
                $result = array('status' => 'info', 'message' => 'Монпэй: Хариу тодорхой бус байна!');
            }
        }
        
        return $result;
    }
    
    public function redPointUserCheckModel($paymentData) {
        
        $redPointType   = Input::param($paymentData['loyaltyRedPointTypeCode']);
        $redPointNumber = Input::param($paymentData['loyaltyRedPointNumber']);
        
        $result = array(
            'status'         => 'success', 
            'redPointType'   => $redPointType, 
            'redPointNumber' => $redPointNumber, 
            'redPointAmount' => Number::decimal(Input::param($paymentData['loyaltyRedPointAmount']))
        );
        
        return $result;
    }

    public function spliteCustomerSalesModel() {
        $tempPost = $_POST;        
        $tempItemData = array();
        $tempMerchantItemData = array();
        $tempPaymentData = array();
        $tempPostPart = array();

        parse_str($tempPost['paymentData'], $paymentData);
        parse_str($tempPost['itemData'], $itemData);

        //@file_put_contents('log/pos_payment_data.txt', Date::currentDate().' '.json_encode($paymentData)."\n\n", FILE_APPEND);

        $itemIds = $itemData['itemId'];
        $merchantItems = array();
        $merchantRegisters = array();
        $merchantHeader = array();
        $sumTotal = 0; $stocks = '';              
        
        foreach ($itemIds as $k => $itemId) {
            
            $salePrice      = $itemData['salePrice'][$k];
            $itemQty        = Number::decimal($itemData['quantity'][$k]);
            $totalPrice     = $itemData['totalPrice'][$k];
            $unitAmount     = $salePrice;
            $lineTotalAmount= $totalPrice;
            
            $isVat          = $itemData['isVat'][$k];
            $vatPercent     = $itemData['vatPercent'][$k];
            $noVatPrice     = $itemData['noVatPrice'][$k];
            
            $isDiscount         = $itemData['isDiscount'][$k];
            $discountPercent    = $itemData['discountPercent'][$k];
            $dtlDiscountPercent = $discountPercent;
            $dtlDiscountAmount  = $itemData['discountAmount'][$k];
            $unitReceivable     = $itemData['unitReceivable'][$k];
            $unitReceivable     = $unitReceivable ? number_format($unitReceivable * $itemQty, 2, '.', '') : '';
            $maxPrice           = $itemData['maxPrice'][$k];
            $unitDiscount       = 0;
            $dtlUnitDiscount    = 0;
            $lineTotalDiscount  = 0;
            
            $isDelivery     = $itemData['isDelivery'][$k];
            $employeeId     = $itemData['employeeId'][$k];
            $sectionId     = isset($itemData['sectionId']) ? $itemData['sectionId'][$k] : '';            
            
            $discountEmployeeId   = $itemData['discountEmployeeId'][$k];
            $discountTypeId       = $itemData['discountTypeId'][$k];
            $discountDescription  = $itemData['discountDescription'][$k];
            $storeWarehouseId     = $itemData['storeWarehouseId'][$k];
            $deliveryWarehouseId  = $itemData['deliveryWarehouseId'][$k];
            $itemPrintCopies      = issetParam($itemData['printCopies'][$k]);
            
            $printUnitAmount      = $unitAmount;
            $printLineTotalAmount = $lineTotalAmount;
            
            $isCityTax      = $itemData['isCityTax'][$k];
            $cityTax        = ($isCityTax == '1' ? $itemData['cityTax'][$k] : 0);
            $lineTotalCityTaxAmount = $itemData['lineTotalCityTax'][$k];
            $lineTotalVat = $itemData['lineTotalVat'][$k];
        
            if ($lineTotalAmount > 0) {                
                
                $itemName    = '';
                $measureCode = '';
                
                $stocks .= "{
                }, ";  
                
                if (isset($itemData['stateRegNumber'])) {
                    
                    $merchantId = issetParam($itemData['stateRegNumber'][$k]) ? $itemData['stateRegNumber'][$k] : Mdpos::$eVatNumber;
                    $stateRegNumber = Str::upper($merchantId);
                    $stateRegNumberReal = $stateRegNumber;
                    
                    preg_match("/[ФЦУЖЭНГШҮЗКЪЙЫБӨАХРОЛДПЯЧЁСМИТЬВЮЕЩ]{2}[0-9]{8}$/", $stateRegNumber, $validRegNo);
                    
                    if ($validRegNo) {
                        $stateRegNumber = self::posApiCallFunction('toReg::'.self::apiStringReplace($stateRegNumber));
                        $merchantRegisters[$stateRegNumber] = $stateRegNumberReal;
                    }                    
                    
                    if (!isset(${"stocks2".$merchantId})) {
                        ${"stocks2".$merchantId} = '';
                        ${"merchantRegisterNo2".$merchantId} = $stateRegNumber;
                        ${"merchantTotalAmount2".$merchantId} = 0;
                    }
                    
                    ${"merchantTotalAmount2".$merchantId} += $lineTotalAmount;
                    
                    if (!in_array($merchantId, $merchantHeader) && $merchantId) {
                        if ($paymentData['bonusCardAmount'] && $merchantId == '5228697') {
                            self::$isBonusCard = true;
                        }
                        array_push($merchantHeader, $merchantId);
                    }
                    
                    ${"stocks2".$merchantId} .= "{
                    }, ";  
                }
            }
        }           

        //dd($itemData);

        if ($merchantHeader) {
            $_POST = array();
            $result = array();
            $resultPrintData = '';
            self::$isSpliteCustomerSales = true;

            foreach ($merchantHeader as $merchant) {
                $tempItemData = array('tempTotalprice' => 0);
                $orgcashregistercode = '';
                $orgstorecode = '';
                $posHeaderName = '';
                $posLogo = '';
                $storeId = '';
                $cashRegisterId = '';
                $discountAmt = $lineTotalCity = $lineTotalVt = 0;

                foreach ($itemData['stateRegNumber'] as $merkey => $merrow) {
                    $merrow = $merrow ? $merrow : Mdpos::$eVatNumber;
                    if ($merchant === $merrow) {                    

                        foreach ($itemData as $ikey => $irow) {
                            if (is_array($irow)) {
                                $tempItemData[$ikey][] = issetParam($irow[$merkey]);
                                if ($ikey === 'totalPrice') {
                                    $tempItemData['tempTotalprice'] += $itemData['totalPrice'][$merkey];
                                    if ($itemData['unitDiscount'][$merkey]) {
                                        $tempItemData['tempTotalprice'] -= $itemData['unitDiscount'][$merkey] * $itemData['quantity'][$merkey];
                                    }
                                }
                                if ($ikey === 'orgCashRegisterCode') {
                                    $orgcashregistercode = $itemData['orgCashRegisterCode'][$merkey] ? $itemData['orgCashRegisterCode'][$merkey] : Mdpos::$eCashRegisterCode;
                                }
                                if ($ikey === 'orgStoreCode') {
                                    $orgstorecode = $itemData['orgStoreCode'][$merkey] ? $itemData['orgStoreCode'][$merkey] : Mdpos::$eStoreCode;
                                }
                                if ($ikey === 'orgPosHeaderName') {
                                    $posHeaderName = $itemData['orgPosHeaderName'][$merkey] ? $itemData['orgPosHeaderName'][$merkey] : Mdpos::$posHeaderName;
                                }
                                if ($ikey === 'orgPosLogo') {
                                    $posLogo = $itemData['orgPosLogo'][$merkey] ? $itemData['orgPosLogo'][$merkey] : Mdpos::$posLogo;
                                }
                                if ($ikey === 'storeId') {
                                    $storeId = $itemData['storeId'][$merkey] ? $itemData['storeId'][$merkey] : Mdpos::$storeId;
                                }
                                if ($ikey === 'cashRegisterId') {
                                    $cashRegisterId = $itemData['cashRegisterId'][$merkey] ? $itemData['cashRegisterId'][$merkey] : Mdpos::$cashRegisterId;
                                }
                                if ($ikey === 'unitDiscount' && $itemData['unitDiscount'][$merkey]) {
                                    $discountAmt += $itemData['unitDiscount'][$merkey] * $itemData['quantity'][$merkey];
                                }
                                
                                if ($ikey === 'lineTotalCityTax' && $itemData['lineTotalCityTax'][$merkey]) {
                                    $lineTotalCity += $itemData['lineTotalCityTax'][$merkey];
                                }
                                
                                if ($ikey === 'lineTotalVat' && $itemData['lineTotalVat'][$merkey]) {
                                    $lineTotalVt += $itemData['lineTotalVat'][$merkey];
                                }                                
                            }
                        }    
                        $tempMerchantItemData[$merchant] = $tempItemData;
                        $tempMerchantItemData[$merchant]['orgcashregistercode'] = $orgcashregistercode;
                        $tempMerchantItemData[$merchant]['orgstorecode'] = $orgstorecode;
                        $tempMerchantItemData[$merchant]['vatnumber'] = $merrow;
                        $tempMerchantItemData[$merchant]['orgposheadername'] = $posHeaderName;
                        $tempMerchantItemData[$merchant]['orgposlogo'] = $posLogo;
                        $tempMerchantItemData[$merchant]['storeId'] = $storeId;
                        $tempMerchantItemData[$merchant]['cashRegisterId'] = $cashRegisterId;
                        $tempMerchantItemData[$merchant]['discountAmount2'] = $discountAmt;
                        $tempMerchantItemData[$merchant]['lineTotalCity'] = $lineTotalCity;
                        $tempMerchantItemData[$merchant]['lineTotalVt'] = $lineTotalVt;
                    }    
                }                      
                
                $sumTotal += $tempMerchantItemData[$merchant]['tempTotalprice'];
            }

            $paymentAmoutKeys = array();
            $paymentAmoutKeys['cashAmount'] = $paymentData['cashAmount'];
            $paymentAmoutKeys['bankAmountDtl'] = $paymentData['bankAmountDtl'];
            $paymentAmoutKeys['posSocialpayAmt'] = $paymentData['posSocialpayAmt'];
            $paymentAmoutKeys['posqpayAmt'] = $paymentData['posqpayAmt'];
            $paymentAmoutKeys['voucher2DtlAmount'] = $paymentData['voucher2DtlAmount'];
            $paymentAmoutKeys['voucherDtlAmount'] = $paymentData['voucherDtlAmount'];
            $paymentAmoutKeys['accountTransferAmountDtl'] = $paymentData['accountTransferAmountDtl'];
            $paymentAmoutKeys['posRecievableAmtDtl'] = $paymentData['posRecievableAmtDtl'];
            $paymentAmoutKeys['posMobileNetAmt'] = $paymentData['posMobileNetAmt'];
            $paymentAmoutKeys['posOtherAmt'] = $paymentData['posOtherAmt'];
            $paymentAmoutKeys['posTcardAmt'] = $paymentData['posTcardAmt'];
            $paymentAmoutKeys['posShoppyAmt'] = $paymentData['posShoppyAmt'];
            $paymentAmoutKeys['posGlmtRewardAmt'] = $paymentData['posGlmtRewardAmt'];
            $paymentAmoutKeys['posSocialpayrewardAmt'] = $paymentData['posSocialpayrewardAmt'];
            $paymentAmoutKeys['posEmpLoanAmt'] = $paymentData['posEmpLoanAmt'];
            $paymentAmoutKeys['posLocalExpenseAmt'] = $paymentData['posLocalExpenseAmt'];
            $paymentAmoutKeys['upointAmountDtl'] = $paymentData['upointAmountDtl'];
            $paymentAmoutKeys['posBarterAmt'] = $paymentData['posBarterAmt'];

            $initValues = array (
                'posBillType' => $paymentData['posBillType'],
                'orgRegNumber' => $paymentData['orgRegNumber'],
                'orgName' => $paymentData['orgName'],
                'orgVatPayer' => $paymentData['orgVatPayer'],
                'payAmount' => '',
                'cashAmount' => '',
                'bankAmount' => '',
                'bankAmount2' => '0',
                'bankTerminalConfirmCode' => '',
                'posSocialpayAmt' => '',
                'posSocialpayPhoneNumber' => '',
                'posSocialpayUID' => '',
                'posSocialpayApprovalCode' => '',
                'posSocialpayCardNumber' => '',
                'posSocialpayTerminal' => '',
                'voucherAmount' => '0',
                'voucher2Amount' => $paymentData['voucher2Amount'],
                'bonusCardAmount' => '',
                'posAccountTransferAmt' => '0',
                'posMobileNetAmt' => '',
                'posOtherAmt' => '',
                'posSocialpayrewardAmt' => '',
                'posGlmtRewardAmt' => '',
                'posShoppyAmt' => '',
                'posTcardAmt' => '',
                'posMobileNetBankId' => '',
                'prePyamentDtlAmount' => '',
                'prePaymentCustomerId' => '',
                'prePaymentCustomerId_displayField' => '',
                'prePaymentCustomerId_nameField' => '',
                'prePaymentAmount' => '',
                'posLeasingAmt' => '',
                'posLeasingBankId' => '',
                'posCandyAmt' => '',
                'posCandyCouponAmt' => '',
                'posBarterAmt' => '',
                'posEmpLoanAmt' => '',
                'posEmdAmt' => '',
                'posEmdInsuredAmt' => '',
                'posLocalExpenseAmt' => '',
                'posCertificateExpenseAmt' => '',
                'posWarrantyRepairAmt' => '',
                'posDeliveryAmt' => '',
                'posLendMnAmt' => '',
                'posRecievableAmt' => '',
                'posLiciengExpenseAmt' => '',
                'paidAmount' => '535,050',
                'changeAmount' => '0',
                'posPrintCopies' => $paymentData['posPrintCopies'],
                'cardBeginAmountCoupon' => '',
                'cardEndAmountCoupon' => '',
                'invInfoInvoiceNumber' => '',
                'invInfoBookNumber' => '',
                'invInfoCustomerLastName' => '',
                'invInfoCustomerName' => '',
                'invInfoCustomerRegNumber' => '',
                'invInfoPhoneNumber' => '',
                'invInfoTransactionValue' => $paymentData['invInfoTransactionValue'],
                'serviceCustomerId' => $paymentData['serviceCustomerId'],
                'serviceCustomerId_displayField' => $paymentData['serviceCustomerId_displayField'],
                'serviceCustomerId_nameField' => $paymentData['serviceCustomerId_nameField'],
                'newServiceCustomerJson' => '',
                'coordinate' => '',
                'what3words' => '',
                'recipientName' => $paymentData['recipientName'],
                'cityId' => '',
                'detailAddress' => '',
                'descriptionAddress' => '',
                'phone1' => '',
                'phone2' => '',
                'dueDate' => $paymentData['dueDate'],
                'timeZoneId' => '',
                'cardNumber' => $paymentData['cardNumber'],
                'cardMemberShipId' => $paymentData['cardMemberShipId'],
                'cardId' => '',
                'cardPinCode' => '',
                'cardBeginAmount' => $paymentData['cardBeginAmount'],
                'cardDiscountPercentAmount' => $paymentData['cardDiscountPercentAmount'],
                'cardDiscountPercent' => $paymentData['cardDiscountPercent'],
                'cardEndAmount' => $paymentData['cardEndAmount'],
                'cardPayPercentAmount' => '0',
                'newCardCustomerJson' => '',
                'upointPayAmount' => issetParam($paymentData['upointPayAmount']),
                'posUpointAmt' => issetParam($paymentData['posUpointAmt']),
                'upointCardNumber' => issetParam($paymentData['upointCardNumber']),
                'upointCardId' => issetParam($paymentData['upointCardId']),
                'upointMobile' => issetParam($paymentData['upointMobile']),
                'upointCardPinCode' => issetParam($paymentData['upointCardPinCode']),
                'upointBalance' => issetParam($paymentData['upointBalance']),          
                'localCustomerPhone' => '',
                'giftPaymentJson' => '',
                'vatAmount' => '',
                'cityTaxAmount' => '',
                'discountAmount' => '0',
                'invoiceId' => '',
              );            

            foreach ($merchantHeader as $merchant) {                
                $tempPaymentData[$merchant] = $initValues;                
                $customerBillTotal = $tempMerchantItemData[$merchant]['tempTotalprice'];                
                $tempPaymentData[$merchant]['payAmount'] = self::posAmount($customerBillTotal);

                if (self::$isBonusCard && $merchant == '5228697') {
                    $tempPaymentData[$merchant]['bonusCardAmount'] = self::posAmount(Number::decimal($paymentData['bonusCardAmount']));
                    $customerBillTotal = $customerBillTotal - Number::decimal($paymentData['bonusCardAmount']);
                }
                if ($merchant != '5228697') {
                    $tempPaymentData[$merchant]['bonusCardAmount'] = '';
                    //$tempPaymentData[$merchant]['cardMemberShipId'] = '';
                    $tempPaymentData[$merchant]['cardBeginAmount'] = '';
                    //$tempPaymentData[$merchant]['cardDiscountPercentAmount'] = '';
                    $tempPaymentData[$merchant]['cardDiscountPercent'] = '';
                    $tempPaymentData[$merchant]['cardEndAmount'] = '';
                    $tempPaymentData[$merchant]['cardPayPercentAmount'] = '';
                }                   

                foreach ($paymentAmoutKeys as $pkey => $pamount) {
                    if ($pkey === 'upointAmountDtl') {
                        foreach ($pamount as $pckey => $pcamount) { 
                            $tempPaymentData[$merchant]['upointTypeCodeDtl'][] = $paymentData['upointTypeCodeDtl'][$pckey];
                            $tempPaymentData[$merchant]['upointDetectedNumberDtl'][] = $paymentData['upointDetectedNumberDtl'][$pckey];
                            $tempPaymentData[$merchant]['upointTransactionIdDtl'][] = $paymentData['upointTransactionIdDtl'][$pckey];

                            if ($customerBillTotalTemp >= 0) {
                                $tempPaymentData[$merchant][$pkey][] = $pcamount;
                                $customerBillTotal = $customerBillTotal - Number::decimal($pcamount);
                                unset($paymentAmoutKeys[$pkey][$pckey]);   
                            } else {
                                $paymentAmoutKeys[$pkey][$pckey] = $customerBillTotalTemp * -1;
                                $tempPaymentData[$merchant][$pkey][] = $customerBillTotal;      
                                $customerBillTotal = 0;        
                            }                                          
                        }    

                    } else { 
                                        
                        if ($customerBillTotal !== 0 && $pamount) {
                            if (!is_array($pamount)) {
                                $customerBillTotalTemp = $customerBillTotal - Number::decimal($pamount);

                                if ($customerBillTotalTemp >= 0) {
                                    $customerBillTotal = $customerBillTotal - Number::decimal($pamount);                            
                                    $tempPaymentData[$merchant][$pkey] = $pamount;
                                    unset($paymentAmoutKeys[$pkey]);
                                } else {
                                    $paymentAmoutKeys[$pkey] = $customerBillTotalTemp * -1;
                                    $tempPaymentData[$merchant][$pkey] = $customerBillTotal;
                                    $customerBillTotal = 0;
                                }

                                if ($pkey === 'posSocialpayAmt') {
                                    $tempPaymentData[$merchant]['posSocialpayUID'] = $paymentData['posSocialpayUID'];
                                    $tempPaymentData[$merchant]['posSocialpayApprovalCode'] = $paymentData['posSocialpayApprovalCode'];
                                    $tempPaymentData[$merchant]['posSocialpayCardNumber'] = $paymentData['posSocialpayCardNumber'];
                                    $tempPaymentData[$merchant]['posSocialpayTerminal'] = $paymentData['posSocialpayTerminal'];
                                }

                                if ($pkey === 'posqpayAmt') {
                                    $tempPaymentData[$merchant]['qpay_traceNo'] = $paymentData['qpay_traceNo'];
                                }

                            } else {
                                $dtlPaymentSum = 0;
                                foreach ($pamount as $pckey => $pcamount) { 
                                    if ($customerBillTotal !== 0 && $pcamount) {

                                        $customerBillTotalTemp = $customerBillTotal - Number::decimal($pcamount);

                                        if ($customerBillTotalTemp >= 0) {
                                            $dtlPaymentSum += Number::decimal($pcamount);
                                            $tempPaymentData[$merchant][$pkey][] = $pcamount;
                                            $customerBillTotal = $customerBillTotal - Number::decimal($pcamount);
                                            unset($paymentAmoutKeys[$pkey][$pckey]);   
                                        } else {
                                            $paymentAmoutKeys[$pkey][$pckey] = $customerBillTotalTemp * -1;
                                            $tempPaymentData[$merchant][$pkey][] = $customerBillTotal;      
                                            $dtlPaymentSum += $customerBillTotal;
                                            $customerBillTotal = 0;        
                                        }         

                                        if ($pkey === 'bankAmountDtl') {         
                                            $tempPaymentData[$merchant]['deviceRrn'][] = $paymentData['deviceRrn'][$pckey];
                                            $tempPaymentData[$merchant]['deviceTraceNo'][] = $paymentData['deviceTraceNo'][$pckey];
                                            $tempPaymentData[$merchant]['devicePan'][] = $paymentData['devicePan'][$pckey];
                                            $tempPaymentData[$merchant]['deviceAuthcode'][] = $paymentData['deviceAuthcode'][$pckey];
                                            $tempPaymentData[$merchant]['deviceTerminalId'][] = $paymentData['deviceTerminalId'][$pckey];
                                            $tempPaymentData[$merchant]['posBankIdDtl'][] = $paymentData['posBankIdDtl'][$pckey];                                        
                                            $tempPaymentData[$merchant]['bankAmount'] = $dtlPaymentSum;
                                        }

                                        if ($pkey === 'voucher2DtlAmount') {
                                            $tempPaymentData[$merchant]['voucher2DtlSerialNumber'][] = $paymentData['voucher2DtlSerialNumber'][$pckey];
                                            $tempPaymentData[$merchant]['voucher2DtlId'][] = $paymentData['voucher2DtlId'][$pckey];
                                            $tempPaymentData[$merchant]['voucher2TypeId'][] = $paymentData['voucher2TypeId'][$pckey];                                        
                                            $tempPaymentData[$merchant]['voucher2Amount'] = $dtlPaymentSum;
                                        }

                                        if ($pkey === 'voucherDtlAmount') {        
                                            $tempPaymentData[$merchant]['voucherDtlSerialNumber'][] = $paymentData['voucherDtlSerialNumber'][$pckey];
                                            $tempPaymentData[$merchant]['voucherDtlId'][] = $paymentData['voucherDtlId'][$pckey];
                                            $tempPaymentData[$merchant]['voucherTypeId'][] = $paymentData['voucherTypeId'][$pckey];                                        
                                            $tempPaymentData[$merchant]['voucherAmount'] = $dtlPaymentSum;
                                        }

                                        if ($pkey === 'accountTransferAmountDtl') {          
                                            $tempPaymentData[$merchant]['accountTransferBankIdDtl'][] = $paymentData['accountTransferBankIdDtl'][$pckey];                                    
                                            $tempPaymentData[$merchant]['accountTransferBillingIdDtl'][] = $paymentData['accountTransferBillingIdDtl'][$pckey];                                    
                                            $tempPaymentData[$merchant]['accountTransferDescrDtl'][] = $paymentData['accountTransferDescrDtl'][$pckey];                                    
                                            $tempPaymentData[$merchant]['posAccountTransferAmt'] = $dtlPaymentSum;
                                        }                                  

                                        if ($pkey === 'posRecievableAmtDtl') {          
                                            $tempPaymentData[$merchant]['recievableCustomerId'][] = $paymentData['recievableCustomerId'][$pckey];
                                            $tempPaymentData[$merchant]['posRecievableAmt'] = $dtlPaymentSum;
                                        }                                  
                                    }
                                }
                            }
                        }
                    }
                }
                $tempPaymentData[$merchant]['discountAmount'] = $tempMerchantItemData[$merchant]['discountAmount2'];
                $tempPaymentData[$merchant]['cityTaxAmount'] = $tempMerchantItemData[$merchant]['lineTotalCity'];
                $tempPaymentData[$merchant]['vatAmount'] = $tempMerchantItemData[$merchant]['lineTotalVt'];
            }

            foreach ($merchantHeader as $merchant) {
                $tempPost['paymentData'] = http_build_query($tempPaymentData[$merchant]);
                $tempPost['itemData'] = http_build_query($tempMerchantItemData[$merchant]);

                Mdpos::$eVatNumber = $tempMerchantItemData[$merchant]['vatnumber'];
                Mdpos::$eStoreCode = $tempMerchantItemData[$merchant]['orgstorecode'];
                Mdpos::$eCashRegisterCode = $tempMerchantItemData[$merchant]['orgcashregistercode'];        
                Mdpos::$posHeaderName = $tempMerchantItemData[$merchant]['orgposheadername'];        
                Mdpos::$posLogo = $tempMerchantItemData[$merchant]['orgposlogo'];        
                Mdpos::$storeId = $tempMerchantItemData[$merchant]['storeId'];        
                Mdpos::$cashRegisterId = $tempMerchantItemData[$merchant]['cashRegisterId'];        
                Mdpos::$posVatPayerNo = Mdpos::$eVatNumber;
                Mdpos::$posVatPayerName = Mdpos::$posHeaderName;

                $_POST = $tempPost;
                $result = self::billPrintModel();
                if ($result['status'] === 'success') {
                    $resultPrintData .= $result['printData'];
                }
            }   

            if ($result['status'] === 'success') {
                $result['printData'] = $resultPrintData;                            
            }

            return $result;
        } else {
            return self::billPrintModel();
        }

    }    
    
    public function billPrintModel($returnType = '') {
        
        if (Input::postCheck('paymentData') == false) {
            $response = array('status' => 'warning', 'message' => 'Төлбөр төлөлтийн мэдээлэл олдсонгүй. Та дахин оролдоно уу.');
            return $response;
        }
        
        if (empty(Mdpos::$storeId)) {
            $response = array('status' => 'warning', 'message' => 'Буруу хандалт хийсэн байна. Та хөтөчөө REFRESH хийгээд дахин оролдоно уу.');
            return $response;
        }
        
        parse_str($_POST['paymentData'], $paymentData);        
        
        if (isset($paymentData['loyaltyTypeCode']) && $loyaltyTypeCode = $paymentData['loyaltyTypeCode']) {
            
            if ($loyaltyTypeCode == 'candy') {
                
                $candyUserCheck = self::candyUserCheckModel($paymentData);
                
                if ($candyUserCheck['status'] != 'success') {
                    return $candyUserCheck;
                } 
                
            } elseif ($loyaltyTypeCode == 'redpoint') {
                
                $redPointUserCheck = self::redPointUserCheckModel($paymentData);
                
                if ($redPointUserCheck['status'] != 'success') {
                    return $redPointUserCheck;
                } 
            }
        }                
        
        $sPrefix        = SESSION_PREFIX;
        
        $posTypeCode    = Session::get($sPrefix.'posTypeCode');
        $storeId        = Mdpos::$storeId;
        $cashierId      = Session::get($sPrefix.'cashierId');
        $cashRegisterId = Mdpos::$cashRegisterId;
        $isServicePos   = Session::get($sPrefix.'posIsService');
        
        $refNumber      = self::getPosInvoiceRefNumber($storeId, $cashRegisterId);
        $invoiceNumber  = self::getBillNumModel();
        $currentDate    = Date::currentDate('Y-m-d H:i:s');
        
        parse_str($_POST['itemData'], $itemData);
        
        $vatAmount              = Number::decimal($paymentData['vatAmount']);
        $cityTaxAmount          = Number::decimal($paymentData['cityTaxAmount']);
        $discountAmount         = Number::decimal($paymentData['discountAmount']);
        $itemDiscountAmount     = $discountAmount;
        
        $totalAmount            = Number::decimal($paymentData['payAmount']);
        $cashAmount             = Number::decimal($paymentData['cashAmount']);
        $bankAmount             = Number::decimal($paymentData['bankAmount']);
        $voucherAmount          = Number::decimal($paymentData['voucherAmount']);
        $voucher2Amount         = Number::decimal($paymentData['voucher2Amount']);
        $certificateExpenseAmt  = Number::decimal($paymentData['posCertificateExpenseAmt']);        
        $warrantyRepairAmt      = Number::decimal($paymentData['posWarrantyRepairAmt']);        
        
        $bonusCardAmount        = Number::decimal($paymentData['bonusCardAmount']);
        $bonusCardMemberShipId  = issetParam($paymentData['cardMemberShipId']);

        $discountActivityAmount = Number::decimal(issetParam($paymentData['discountActivityAmount']));
        $discountActivityAmount2 = $discountActivityAmount;
        $insuranceAmount = Number::decimal(issetParam($paymentData['insuranceAmount']));
        
        $totalBonusAmount       = $voucherAmount + $bonusCardAmount + $certificateExpenseAmt + $insuranceAmount;
        if (issetParam($paymentData['cardDiscountType']) == '-' && $paymentData['cardDiscountPercentAmount'] > 0) {
            $discountAmount += Number::decimal($paymentData['cardDiscountPercentAmount']);
        }        
        $subTotal               = $totalAmount + $discountAmount;
        $printTotalAmount       = ($discountAmount < 0) ? $totalAmount : $subTotal;
        $remainderCoupon        = Config::getFromCache('CONFIG_POS_IS_USE_REMAINDER_COUPON');
        
        if (
            defined('CONFIG_POS_GENERATE_LOTTERY_NUMBER') && CONFIG_POS_GENERATE_LOTTERY_NUMBER 
            && self::isLotteryGenerate($totalAmount, $storeId) 
            && (
                issetVar($paymentData['invInfoCustomerName']) == '' 
                || issetVar($paymentData['invInfoCustomerLastName']) == '' 
                || issetVar($paymentData['invInfoPhoneNumber']) == '' 
                || issetVar($paymentData['invInfoCustomerRegNumber']) == '' 
                )
            ) {
                
            $response = array('status' => 'warning', 'message' => 'Сугалаа гарах учир Харилцагчийн овог, нэр, регистр, утсыг заавал бөглөнө үү!');
            return $response;
        }
        
        $accountTransferAmt     = Number::decimal($paymentData['posAccountTransferAmt']);
        $mobileNetAmt           = Number::decimal($paymentData['posMobileNetAmt']);
        $posOtherAmt            = Number::decimal($paymentData['posOtherAmt']);
        $posTcardAmt            = Number::decimal($paymentData['posTcardAmt']);
        $posShoppyAmt           = Number::decimal($paymentData['posShoppyAmt']);
        $posGlmtRewardAmt       = Number::decimal($paymentData['posGlmtRewardAmt']);
        $posSocialpayrewardAmt  = Number::decimal($paymentData['posSocialpayrewardAmt']);
        $prePaymentAmount       = Number::decimal($paymentData['prePaymentAmount']);
        $barterAmt              = Number::decimal($paymentData['posBarterAmt']);
        $leasingAmt             = Number::decimal($paymentData['posLeasingAmt']);
        $empLoanAmt             = Number::decimal($paymentData['posEmpLoanAmt']);
        $localExpenseAmt        = Number::decimal($paymentData['posLocalExpenseAmt']);        
        $posSocialpayAmt        = Number::decimal($paymentData['posSocialpayAmt']);
        $emdAmount              = Number::decimal($paymentData['posEmdAmt']);
        $candyAmount            = Number::decimal(issetParam($paymentData['posCandyAmt']));
        $qpayAmount             = Number::decimal(issetParam($paymentData['posqpayAmt']));
        $tokipayAmount          = Number::decimal(issetParam($paymentData['postokipayAmt']));
        $upointAmount           = Number::decimal(issetParam($paymentData['posUpointAmt']));
        $candyCouponAmount      = Number::decimal(issetParam($paymentData['posCandyCouponAmt']));
        $deliveryAmount         = Number::decimal(issetParam($paymentData['posDeliveryAmt']));
        $lendMnAmount           = Number::decimal(issetParam($paymentData['posLendMnAmt']));
        $posRecievableAmt       = Number::decimal(issetParam($paymentData['posRecievableAmt']));
        $posLiciengExpenseAmt   = Number::decimal(issetParam($paymentData['posLiciengExpenseAmt']));
        
        $changeAmount           = $paymentData['changeAmount'];
        $changeAmount           = ($changeAmount ? Number::decimal($changeAmount) : 0);
        $serialText             = Input::post('serialText');
        
        $nonCashAmount          = 0;
        $totalItemCount         = 0;
        $generateVaucherAmt     = 0;
        $isPut                  = true;
        $isVatCalc              = true;
        $posBillType            = $paymentData['posBillType'];
        $showCustomerName       = Input::isEmpty('empCustomerName') ? '' : Input::post('empCustomerName');
        $isUserPosV3            = Config::getFromCache('IS_USE_POSAPI_V3');
        
        if (($upointAmount > 0 || issetParam($paymentData['upointBalance'])) && $returnType != 'typeReduce') {
            $resultUpoint = $this->upointPaymentTransaction($paymentData, $upointAmount, getUID(), $currentDate, $paymentData['upointPayAmount'], $cashAmount, $itemData);
            if ($resultUpoint['status'] === 'warning') {
                return $resultUpoint;
            }
            $upointTotalPoint = $resultUpoint['data']['total_point'];
        }
        
        if ($showCustomerName) {
            $showCustomerName = '<tr><td style="text-align: left; vertical-align: top; width: 50%">Харилцагч: '.$showCustomerName.'</td></tr>';
        }
        
        if ($totalAmount == $lendMnAmount 
            && $storeId != '1515722911251' && $storeId != '1515722911369' && $storeId != '1515722911402' 
            && $storeId != '1515722911434' && $storeId != '1515722911503') {
            
            $totalBonusAmount = number_format((10 / 100) * $lendMnAmount, 2, '.', '');
            $lendMnAmount = $lendMnAmount - $totalBonusAmount;
        }

        if ($leasingAmt > 0 && Config::getFromCache('CONFIG_POS_PAYMENT_LEASING_IS_VAT') != 1) {
            $isVatCalc = false;
            $vatAmount = 0;            
        }
        
        $isDirectBonusAmount = false;
        if ($totalBonusAmount > 0) {

            foreach ($itemData['itemId'] as $k => $itemId) {
                if (issetParam($itemData['isNotUseBonusCard'][$k]) === '0') {                
                    $isDirectBonusAmount = true;
                }
            }
            
            if ($totalBonusAmount > $totalAmount) {
                
                /**
                 * Hongololt ni toloh dungees ih bsn ch hongoloh ystoi gsn shaardlga der undeslj zaslaa.
                 * @PM Solongo
                 * @author Ulaankhuu.Ts
                 * @date 2020-01-20
                 *
                 * $totalBonusAmount = 0;
                 */
                $discountAmount   = $totalAmount;
                $dtlBonusPercent  = 100;
                $totalAmount      = 0;
                $isPut            = false;
                $billTitle        = Lang::line('POS_0080');
                
            } else {
                $dtlBonusPercent = $totalBonusAmount / $totalAmount * 100;
                $discountAmount  = $discountAmount + $totalBonusAmount;  
                $totalAmount     = $subTotal - $discountAmount;
                $vatAmount       = $totalAmount - number_format($totalAmount / 1.1, 2, '.', '');
            }
        }
        
        if ($posBillType == 'organization') {
            $orgVatPayer = isset($paymentData['orgVatPayer']) ? $paymentData['orgVatPayer'] : '';
            
            if ($orgVatPayer == 'false') {
                $isVatCalc = false;
                $vatAmount = 0;
            }
        }       
        
        $params = array(
            'bookTypeId'        => 9, 
            'invoiceNumber'     => $invoiceNumber, 
            'refNumber'         => $refNumber, 
            'invoiceDate'       => $currentDate, 
            'createdDateTime'   => $currentDate, 
            'storeId'           => $storeId,
            'cashRegisterId'    => $cashRegisterId,
            'createdCashierId'  => $cashierId, 
            'totalCityTaxAmount'=> $cityTaxAmount, 
            'subTotal'          => $subTotal, 
            'discount'          => $discountAmount, 
            'vat'               => $vatAmount, 
            'total'             => $totalAmount, 
            'changeAmount'      => $changeAmount, 
            'customerEmail'     => issetParam($paymentData['lotteryEmail']),
            'locationId'        => Input::post('locationId'),
            'salesPersonId'     => Input::post('waiterId'),
            'customerContactPhone'=> issetParam($paymentData['localCustomerPhone']),
            'wfmStatusId'       => '1505964291977811', 
            'customerId'        => '',
            'parentSalesInvoiceId'=> Input::post('parentSalesInvoiceId'),
            'customerNo'        => Str::upper(issetParam($paymentData['orgRegNumber']))
        );

        if ($returnType == 'typeChange') {
            unset($_POST['returnInvoiceId']);
        }
        
        if (Input::isEmpty('returnInvoiceId') == false) {
            $params['id'] = Input::post('returnInvoiceId');
        }
        
        if (Input::isEmpty('returnInvoiceIdReduce') == false) {            
            $params['id'] = Input::post('returnInvoiceIdReduce');
        }

        if (isset($itemData['contractId'])) {
            $params['contractId'] = $itemData['contractId'][0];
        }      
         
        $getInvoiceDate = $this->getDateCashierModel();
        
        if (is_array($getInvoiceDate['result']) && isset($getInvoiceDate['result']['bookdate'])) {
            $params['invoiceDate'] = $getInvoiceDate['result']['bookdate'];
        }
        
        $serviceCustomerId = null;
        
        if (issetParam($paymentData['serviceCustomerId']) != '') {
            
            $params['customerId'] = $paymentData['serviceCustomerId'];
            
        } elseif (issetParam($paymentData['newServiceCustomerJson']) != '') {
            
            $serviceCustomerResult = self::createServiceCustomer($paymentData['newServiceCustomerJson']);
                
            if ($serviceCustomerResult['status'] != 'success') {
                
                $response = array('status' => 'warning', 'message' => Lang::line('POS_0081') . ' (CRM). '.$serviceCustomerResult['message']);
                return $response;
                
            } elseif (isset($serviceCustomerResult['customerId'])) {
                
                $serviceCustomerId    = $serviceCustomerResult['customerId'];
                $params['customerId'] = $serviceCustomerResult['customerId'];
            }
        } 
        
        if (Input::isEmpty('empCustomerId') == false) {
            $serviceCustomerId = Input::post('empCustomerId');
            $params['customerId'] = Input::post('empCustomerId');
        } elseif (isset($itemData['customerId']) && issetParam($itemData['customerId'][0])) {
            $params['customerId'] = $itemData['customerId'][0];
        }
        $params['customerId'] = $params['customerId'] == 'undefined' ? '' : $params['customerId'];
        
        $headerParams = $params;
        
        $paramsDtl = $paymentDtl = $voucherDtl = $voucherUsedDtl = $deliveryDtl = $noDeliveryDtl = $serviceDtl = $couponKeyDtl = $atBankBillingIds = array();
        $itemPrintList = $stocks = $giftList = $paymentDetail = $discountDetail = $talonInvoiceId = ''; 
        $isPackageItem = false; $merchantHeader = array(); $isPackagedItem = false;
        
        $paymentDtlTemplate = self::paymentDetailTemplate();
        
        if (Config::getFromCache('CONFIG_POS_HEALTHRECIPE')) {
            $itemPrintRenderFncName = 'generatePharmacyItemRow';
        } else {
            $itemPrintRenderFncName = 'generateItemRow';
        }
        
        $sumTotal = $itemPrintCopiesLast = $sumVatAmount = $sumCityTax = $sumLineTotalBonusAmount = 0;
        $itemIds = $itemData['itemId'];
        $merchantItems = array();
        $merchantRegisters = array();
        $metaDmRowsDtl = array();
        $basketInvoiceId = Input::numeric('basketInvoiceId');
        $upointTotalPointTemp = isset($upointTotalPoint) ? $upointTotalPoint : 0;
        
        // <editor-fold defaultstate="collapsed" desc="Item">
        foreach ($itemIds as $k => $itemId) {
            
            if (issetParam($itemData['packageName'][$k])) {
                if ($itemData['packageName'][$k] != $isPackagedItem) {
                    $itemPrintList .= self::generatePackageItemRow2($itemData['packageName'][$k]);
                }
                $isPackagedItem = $itemData['packageName'][$k];
            } elseif ($isPackagedItem) {
                $itemPrintList .= self::generatePackageItemRow2('Багцгүй');
            }
            
            $itemQty        = Number::decimal($itemData['quantity'][$k]);
            $salePrice      = $itemData['salePrice'][$k];
            $totalPrice     = $itemData['totalPrice'][$k];
            $unitAmount     = $salePrice;
            $lineTotalAmount= $totalPrice;
            
            $isVat          = !$isVatCalc ? 0 : $itemData['isVat'][$k];
            $vatPercent     = $itemData['vatPercent'][$k];
            $noVatPrice     = $itemData['noVatPrice'][$k];
            
            $isDiscount         = $itemData['isDiscount'][$k];
            $discountPercent    = $itemData['discountPercent'][$k];
            $dtlDiscountPercent = $discountPercent;
            $dtlDiscountAmount  = $itemData['discountAmount'][$k];
            $unitReceivable     = $itemData['unitReceivable'][$k];
            $unitReceivable     = $unitReceivable ? number_format($unitReceivable * $itemQty, 2, '.', '') : '';
            $maxPrice           = $itemData['maxPrice'][$k];
            $unitDiscount       = 0;
            $dtlUnitDiscount    = 0;
            $lineTotalDiscount  = 0;
            
            $isDelivery     = $itemData['isDelivery'][$k];
            $employeeId     = $itemData['employeeId'][$k];
            $sectionId     = isset($itemData['sectionId']) ? $itemData['sectionId'][$k] : '';            
            
            $discountEmployeeId   = $itemData['discountEmployeeId'][$k];
            $discountTypeId       = $itemData['discountTypeId'][$k];
            $discountDescription  = $itemData['discountDescription'][$k];
            $storeWarehouseId     = $itemData['storeWarehouseId'][$k];
            $deliveryWarehouseId  = $itemData['deliveryWarehouseId'][$k];
            $itemPrintCopies      = issetParam($itemData['printCopies'][$k]);
            
            $printUnitAmount      = $unitAmount;
            $printLineTotalAmount = $lineTotalAmount;
            
            $isCityTax      = $itemData['isCityTax'][$k];
            $cityTax        = ($isCityTax == '1' ? $itemData['cityTax'][$k] : 0);
            $lineTotalCityTaxAmount = $itemData['lineTotalCityTax'][$k];
            $lineTotalVat = $itemData['lineTotalVat'][$k];

            if ($posTypeCode != '3' && $posTypeCode != '4') {
                if (isset($itemData['salesOrderDetailId']) && issetParam($itemData['salesOrderDetailId'][$k])) {
                    array_push($metaDmRowsDtl, array('trgRecordId' => $itemData['salesOrderDetailId'][$k]));
                }
            }
            
            if ($isVat == '1' && $isDiscount != '1') {
                
                /*if ($isCityTax == '1') {                    
                    $unitVat = number_format($itemData['cityTax'][$k], 6, '.', '');                    
                } else {
                    $unitVat = number_format($salePrice - $noVatPrice, 6, '.', '');
                }*/
                $unitVat = number_format($salePrice - $noVatPrice, 2, '.', '');
                
            } elseif ($isVat == '1' && $isDiscount == '1') {
                
                $unitDiscount = $itemData['unitDiscount'][$k];
                $unitAmount   = $dtlDiscountAmount;
                
                /*if ($isCityTax == '1') {
                    
                    $unitVat = number_format($itemData['cityTax'][$k], 6, '.', '');
                    
                } else {
                    
                    $unitVat = number_format($unitAmount - $noVatPrice, 6, '.', '');
                    
                }*/
                $unitVat = number_format($unitAmount - $noVatPrice, 2, '.', '');
                
                $lineTotalAmount = $unitAmount * $itemQty;
                $dtlUnitDiscount = $unitDiscount;
                
                if ($unitDiscount > 0) {
                    $lineTotalDiscount = $unitDiscount * $itemQty;
                } else {
                    $unitDiscount      = 0;
                    $discountPercent   = 0;
                    $lineTotalDiscount = 0;
                    
                    $printUnitAmount      = $dtlDiscountAmount;
                    $printLineTotalAmount = $lineTotalAmount;
                }
                
            } else {
                
                if ($isDiscount == '1') {
                    
                    $unitVat        = 0;
                    $lineTotalVat   = 0;
                    $unitAmount     = $dtlDiscountAmount;
                    
                    $unitDiscount      = $itemData['unitDiscount'][$k];
                    $lineTotalDiscount = $unitDiscount * $itemQty;
                    $lineTotalAmount   = $unitAmount * $itemQty;
                    $dtlUnitDiscount   = $unitDiscount;
                    
                } else {
                    $unitVat = $lineTotalVat = 0;
                }
            }
            
            $itemCode       = $itemData['itemCode'][$k];
            $printItemName  = $itemData['itemName'][$k];
            $serialNumber   = $itemData['serialNumber'][$k];
            $itemKeyId      = issetParam($itemData['itemKeyId'][$k]);
            $barCode        = $itemData['barCode'][$k];
            $barCode        = ($barCode ? $barCode : '132456789');
            
            $isJob          = $itemData['isJob'][$k];
            $jobId          = '';
            $dtlCouponKeyId = '';
            $discountId = isset($itemData['discountId']) ? (isset($itemData['discountId'][$k]) ? $itemData['discountId'][$k] : '') : '';
            
            if ($isJob == '1') {
                
                $jobId  = $itemId;
                $itemId = '';
                
                if ($isDelivery == '1' && $isServicePos != '1') {
                    
                    $serviceDtl[] = array(
                        'jobId'   => $jobId, 
                        'RPR_KEY' => array(
                            'customerId'      => $headerParams['customerId'], 
                            'jobid'           => $jobId, 
                            'purchaseStoreId' => $storeId
                        )
                    );
                }
                
                $isDelivery = 0;
                
            } elseif ($isJob == '2') {
                
                $dtlCouponKeyId = $itemId;
                $jobId          = '';
                $itemId         = '';
                $isDelivery     = 0;
                
                $couponKeyDtl[] = array(
                    'couponKeyId' => $dtlCouponKeyId, 
                    'rowIndex'    => $k
                );
            }
            
            if ($totalBonusAmount > 0) {
                
                if ($totalAmount > 0) {
                    
                    if ($itemData['isNotUseBonusCard'][$k] === '0') {

                        $totalBonusAmount2 = $totalBonusAmount;
                        $unitDiscount      = number_format($totalBonusAmount / $itemQty, 2, '.', '');
                        $dtlDiscountAmount = $unitAmount - $unitDiscount;

                        if ($dtlDiscountAmount < 0) {
                            $totalBonusAmount = $dtlDiscountAmount * $itemQty * (-1);
                            $dtlDiscountAmount = 0;
                            $unitDiscount = $unitAmount;
                            $totalBonusAmount2 = $lineTotalAmount;
                        } else if ($lineTotalAmount >= $totalBonusAmount) {
                            $totalBonusAmount2 = $totalBonusAmount;
                            $totalBonusAmount = 0;
                        } else {
                            $totalBonusAmount = $dtlDiscountAmount * $itemQty;
                            $totalBonusAmount2 = $totalBonusAmount;
                        }

                        $unitAmount     = $dtlDiscountAmount;
                        $noVatPrice     = $dtlDiscountAmount;                        

                        $lineTotalDiscount = $totalBonusAmount2;
                        $lineTotalAmount   = $dtlDiscountAmount * $itemQty;
                        $lineTotalVat = 0;

                        if ($isVat == '1' && $isCityTax == '1') {
                            $noVatPrice = number_format($dtlDiscountAmount - ($dtlDiscountAmount / 11.1), 2, '.', '');
                            $lineTotalVat = number_format($lineTotalAmount / 11.1, 2, '.', '');
                        } else if ($isVat == '1') {
                            $noVatPrice = number_format($dtlDiscountAmount - ($dtlDiscountAmount / 11), 2, '.', '');
                            $lineTotalVat = number_format($lineTotalAmount / 11, 2, '.', '');                            
                        }                            
                        $unitVat        = number_format($dtlDiscountAmount - $noVatPrice, 2, '.', '');                                                
                        $unitVat        = $unitVat >= 0 ? $unitVat : 0;                                                

                    } elseif (!$isDirectBonusAmount && isset($dtlBonusPercent)) {

                        $unitDiscount      = ($dtlBonusPercent / 100) * $unitAmount;
                        $dtlDiscountAmount = $unitAmount - $unitDiscount;

                        $unitAmount     = $dtlDiscountAmount;
                        $lineTotalDiscount = $unitDiscount * $itemQty;
                        $lineTotalAmount   = $dtlDiscountAmount * $itemQty;
                        $noVatPrice     = $dtlDiscountAmount;
                        $lineTotalVat   = 0;

                        if ($isVat == '1' && $isCityTax == '1') {
                            $noVatPrice = number_format($dtlDiscountAmount - ($dtlDiscountAmount / 11.1), 2, '.', '');
                            $lineTotalVat = number_format($lineTotalAmount / 11.1, 2, '.', '');
                        } else if ($isVat == '1') {
                            $noVatPrice = number_format($dtlDiscountAmount - ($dtlDiscountAmount / 11), 2, '.', '');
                            $lineTotalVat = number_format($lineTotalAmount / 11, 2, '.', '');                            
                        }                

                        $unitVat        = number_format($dtlDiscountAmount - $noVatPrice, 2, '.', '');
                        $unitVat        = $unitVat >= 0 ? $unitVat : 0;     
                    }
                    
                } else {
                                        
                    $unitVat           = 0;
                    $lineTotalVat      = 0;
                    $unitAmount        = 0;
                    $lineTotalAmount   = 0;
                    
                    $unitDiscount      = $salePrice;
                    $lineTotalDiscount = $unitDiscount * $itemQty;
                }
            }
            
            if ($discountActivityAmount > 0) {
                
                if ($totalAmount > 0) {
                    $lineTotalAmount2 = $lineTotalAmount;

                    $totalBonusAmount2 = $discountActivityAmount;
                    $unitDiscount      = number_format($discountActivityAmount / $itemQty, 2, '.', '');
                    $dtlDiscountAmount = $unitAmount - $unitDiscount;

                    if ($dtlDiscountAmount < 0) {
                        $discountActivityAmount = $dtlDiscountAmount * $itemQty * (-1);
                        $dtlDiscountAmount = 0;
                        $unitDiscount = $unitAmount;
                        $totalBonusAmount2 = $lineTotalAmount2;
                    } else if ($lineTotalAmount2 >= $discountActivityAmount) {
                        $totalBonusAmount2 = $discountActivityAmount;
                        $discountActivityAmount = 0;
                    } else {
                        $discountActivityAmount = $dtlDiscountAmount * $itemQty;
                        $totalBonusAmount2 = $discountActivityAmount;
                    }

                    $unitAmount2 = $dtlDiscountAmount;
                    $noVatPrice     = $dtlDiscountAmount;                        

                    $lineTotalDiscount = $totalBonusAmount2;
                    $lineTotalAmount2   = $dtlDiscountAmount * $itemQty;
                    $lineTotalVat = 0;

                    if ($isVat == '1' && $isCityTax == '1') {
                        $noVatPrice = number_format($dtlDiscountAmount - ($dtlDiscountAmount / 11.1), 2, '.', '');
                        $lineTotalVat = number_format($lineTotalAmount2 / 11.1, 2, '.', '');
                    } else if ($isVat == '1') {
                        $noVatPrice = number_format($dtlDiscountAmount - ($dtlDiscountAmount / 11), 2, '.', '');
                        $lineTotalVat = number_format($lineTotalAmount2 / 11, 2, '.', '');                            
                    }                            
                    $unitVat        = number_format($dtlDiscountAmount - $noVatPrice, 2, '.', '');                                                
                    $unitVat        = $unitVat >= 0 ? $unitVat : 0;          
                    
                } else {
                                        
                    $unitVat           = 0;
                    $lineTotalVat      = 0;
                    $unitAmount        = 0;
                    $lineTotalAmount   = 0;
                    
                    $unitDiscount      = $salePrice;
                    $lineTotalDiscount = $unitDiscount * $itemQty;
                }
            }
            
            if ($isVatCalc == false) {
                $unitAmount = $unitAmount - $unitVat;
                $lineTotalAmount = $lineTotalAmount - $lineTotalVat;
                        
                $unitVat = 0;
                $lineTotalVat = 0;
                
                $printUnitAmount = $unitAmount;
                $printLineTotalAmount = $lineTotalAmount; 
            }
            
            $sumVatAmount += $lineTotalVat;
            $sumCityTax += $lineTotalCityTaxAmount;
            $lineTotalVat = number_format($lineTotalVat, 2, '.', '');
            
            $paramsDtl[$k] = array(
                'itemId'                 => $itemId, 
                'jobId'                  => $jobId, 
                'invoiceQty'             => $itemQty, 
                
                'unitPrice'              => $salePrice,
                'lineTotalPrice'         => $totalPrice,  
                'unitAmount'             => $unitAmount, 
                'lineTotalAmount'        => $lineTotalAmount,  
                'lineTotalCityTaxAmount' => $lineTotalCityTaxAmount,  
                
                'isVat'                  => $isVat, 
                'percentVat'             => $vatPercent, 
                'unitVat'                => $unitVat, 
                'lineTotalVat'           => $lineTotalVat,
                
                'percentDiscount'        => $discountPercent, 
                'unitDiscount'           => $unitDiscount, 
                'lineTotalDiscount'      => $lineTotalDiscount, 
                'unitReceivable'         => $unitReceivable, 
                
                'discountPercent'        => $dtlDiscountPercent, 
                'discountAmount'         => $dtlUnitDiscount, 
                
                'isDelivery'             => $isDelivery,  
                'employeeId'             => $employeeId, 
                'discountEmployeeId'     => $discountEmployeeId ? $discountEmployeeId : issetParam($itemData['editPriceEmployeeId'][$k]),
                'discountTypeId'         => $discountTypeId, 
                'description'            => $discountDescription, 
                'serialNumber'           => $serialNumber, 
                'itemKeyId'              => $itemKeyId, 
                'isRemoved'              => 0, 
                
                'itemCode'               => $itemCode, 
                'itemName'               => $printItemName, 
                'barCode'                => $barCode, 
                'couponKeyId'            => $dtlCouponKeyId,
                'discountId'             => $discountId,
                'sectionId'              => $sectionId,
                'lineTotalBonusAmount'   => Number::decimal(issetParam($itemData['lineTotalBonusAmount'][$k])),
                'parentId'               => isset($itemData['parentInvoiceDtlId']) ? $itemData['parentInvoiceDtlId'][$k] : "",
                'refSalePrice'           => isset($itemData['refSalePrice']) ? issetParam($itemData['refSalePrice'][$k]) : "",
                'packageId'              => issetParam($itemData['packageId'][$k]),
                'salesPersonId'          => Input::post('waiterId'),                
            );

            if (Input::isEmpty('returnInvoiceId') == false) {
                $paramsDtl[$k]['id'] = $itemData['id'][$k];
            }                                    

            if (Input::isEmpty('returnInvoiceIdReduce') == false) {
                $paramsDtl[$k]['id'] = $itemData['id'][$k];
            }                                    
            
            $dtlSalesOrderId = (isset($itemData['salesOrderId']) ? (isset($itemData['salesOrderId'][$k]) ? $itemData['salesOrderId'][$k] : '') : '');
            if ($dtlSalesOrderId && ($posTypeCode == '3' || $posTypeCode == '4') && Input::post('restPosEventType') != 'splitCalculate') {
                
                $salesOrderDetails = $this->getSDMSalesOrderDetailsModel($dtlSalesOrderId, $paramsDtl[$k]['itemId'], '', issetParam($itemData['customerIdSaved'][$k]));
                
                if ($salesOrderDetails) {
                    
                    foreach ($salesOrderDetails as $salesOrderDetail) {
                        $metaDmRowsDtl[] = array('trgRecordId' => $salesOrderDetail['salesorderdetailid']);
                    }
                }
            }                  
            
            $sumTotal += $lineTotalAmount;
            $sumLineTotalBonusAmount += $paramsDtl[$k]['lineTotalBonusAmount'] ? $paramsDtl[$k]['lineTotalBonusAmount'] : 0;
            
            if ($lineTotalAmount > 0) {
                
                $itemName    = self::apiStringReplace($printItemName);
                $measureCode = self::convertCyrillicMongolia($itemData['measureCode'][$k]);

                $taxLineTotal = $lineTotalAmount;
                $taxUnitAmount = $unitAmount;
                $taxlineVat = $lineTotalVat;

                if (isset($upointTotalPoint) && isset($itemData['isCalcUPoint']) && $itemData['isCalcUPoint'][$k] == '1') {
                    $taxLineTotal = $taxLineTotal - $upointTotalPointTemp;
                    if ($taxLineTotal < 0) {
                        $taxLineTotal = 0;
                        $taxUnitAmount = 0;
                        $taxlineVat = 0;
                        $upointTotalPointTemp = $taxLineTotal * (-1);
                    } else {
                        $taxUnitAmount = $taxLineTotal / $itemQty;
                        $taxlineVat = $taxLineTotal - number_format($taxLineTotal / 1.1, 2, '.', '');
                    }
                }

                if (isset($unitAmount2) && isset($lineTotalAmount2)) {
                    $taxLineTotal = $lineTotalAmount2;
                    $taxUnitAmount = $unitAmount2;
                    $printUnitAmount = $unitAmount2;
                    $printLineTotalAmount = $lineTotalAmount2;
                }

                $stocks .= "{
                    'code': '" . $itemCode . "',
                    'name': '" . $itemName . "',
                    'measureUnit': '" . $measureCode . "',
                    'qty': '" . sprintf("%.2f", $itemQty) . "',
                    'unitPrice': '" . sprintf("%.2f", $taxUnitAmount) . "',
                    'totalAmount': '" . sprintf("%.2f", $taxLineTotal) . "',
                    'cityTax': '" . sprintf("%.2f", $lineTotalCityTaxAmount) . "',
                    'vat': '" . sprintf("%.2f", $taxlineVat) . "',
                    'barCode': '" . $barCode . "'
                }, ";  
                
                if (isset($itemData['merchantId']) && Config::getFromCache('POS_IS_USE_MULTI_BILL_NOT_PACKAGE_POLICY') !== '1') {
                    
                    $merchantId = (issetParam($itemData['merchantId'][$k])) ? issetParam($itemData['merchantId'][$k]) : '_1';
                    $stateRegNumber = Str::upper(issetParam($itemData['stateRegNumber'][$k]));
                    $stateRegNumberReal = $stateRegNumber;
                    
                    preg_match("/[ФЦУЖЭНГШҮЗКЪЙЫБӨАХРОЛДПЯЧЁСМИТЬВЮЕЩ]{2}[0-9]{8}$/", $stateRegNumber, $validRegNo);
                    
                    if ($validRegNo) {
                        $stateRegNumber = self::posApiCallFunction('toReg::'.self::apiStringReplace($stateRegNumber));
                        $merchantRegisters[$stateRegNumber] = $stateRegNumberReal;
                    }
                    
                    if ($merchantId !== '_1') {
                        $isPackageItem = true;
                    }
                    
                    if (!isset(${"stocks".$merchantId})) {
                        ${"stocks".$merchantId} = '';
                        ${"merchantInternalId".$merchantId} = $itemData['internalId'][$k];
                        ${"merchantRegisterNo".$merchantId} = $stateRegNumber;
                        ${"merchantTotalAmount".$merchantId} = 0;
                        ${"merchantTotalCityTax".$merchantId} = 0;
                    }
                    
                    ${"merchantTotalAmount".$merchantId} += $lineTotalAmount;
                    ${"merchantTotalCityTax".$merchantId} += $lineTotalCityTaxAmount;
                    
                    if (!in_array($merchantId, $merchantHeader)) {
                        array_push($merchantHeader, $merchantId);
                    }
                    
                    ${"stocks".$merchantId} .= "{
                        'code': '" . $itemCode . "',
                        'name': '" . $itemName . "',
                        'measureUnit': '" . $measureCode . "',
                        'qty': '" . sprintf("%.2f", $itemQty) . "',
                        'unitPrice': '" . sprintf("%.2f", $unitAmount) . "',
                        'totalAmount': '" . sprintf("%.2f", $lineTotalAmount) . "',
                        'cityTax': '" . sprintf("%.2f", $lineTotalCityTaxAmount) . "',
                        'vat': '" . sprintf("%.2f", $lineTotalVat) . "',
                        'barCode': '" . $barCode . "'
                    }, ";  
                    
                    array_push($merchantItems, array(
                        'cityTax'        => $cityTax, 
                        'itemName'       => $printItemName, 
                        'salePrice'      => $printUnitAmount, 
                        'itemQty'        => $itemQty, 
                        'totalPrice'     => $printLineTotalAmount, 
                        'unitReceivable' => $unitReceivable, 
                        'maxPrice'       => $maxPrice, 
                        'isDelivery'     => $isDelivery,
                        'merchantId'     => $merchantId,
                        'registerNo'     => $stateRegNumber
                    ));
                }
            }            
            
            $row = array(
                'cityTax'        => $cityTax, 
                'itemName'       => $printItemName, 
                'salePrice'      => $printUnitAmount, 
                'itemQty'        => $itemQty, 
                'totalPrice'     => $printLineTotalAmount, 
                'unitReceivable' => $unitReceivable, 
                'maxPrice'       => $maxPrice, 
                'isDelivery'     => $isDelivery
            );
            if (Input::isEmpty('returnInvoiceId') == false) {
                if ($row['totalPrice']) {
                    $itemPrintList .= self::{$itemPrintRenderFncName}($row);
                }
            } else {
                $itemPrintList .= self::{$itemPrintRenderFncName}($row);
            }         
            
            $totalItemCount += $itemQty;
            $giftJsonStr    = trim($itemData['giftJson'][$k]);
            
            if ($giftJsonStr != '') {
                
                $itemPackageList = $itemGiftList = array();
                $giftJsonArray = json_decode(html_entity_decode($giftJsonStr, ENT_NOQUOTES, 'UTF-8'), true);
                
                foreach ($giftJsonArray as $giftJsonRow) {
                    
                    if (isset($giftJsonRow['onlyPolicyId'])) {

                        $itemPackageList[] = array(
                            'packageDtlId'      => '',
                            'qty'               => $itemQty, 
                            'discountPolicyId'  => $giftJsonRow['onlyPolicyId'], 
                            'isDelivery'        => ''
                        );                        

                    } else {

                        $giftJsonRow['isDelivery'] = isset($giftJsonRow['isDelivery']) ? $giftJsonRow['isDelivery'] : 0;
                        $giftJsonRow['refSalePrice'] = isset($giftJsonRow['refsaleprice']) ? $giftJsonRow['refsaleprice'] : '';
                        $giftJsonRow['invoiceqty'] = Number::decimal($itemQty);
                        $giftJsonRowMerge          = array();
                        
                        $itemPackageList[] = array(
                            'packageDtlId'      => $giftJsonRow['packagedtlid'],
                            'qty'               => $itemQty, 
                            'discountPolicyId'  => issetParam($giftJsonRow['policyid']), 
                            'isDelivery'        => $giftJsonRow['isDelivery']
                        );
                        
                        if ($giftJsonRow['coupontypeid'] == 1 || $giftJsonRow['coupontypeid'] == 5 || $giftJsonRow['coupontypeid'] == 6) {
                            
                            for ($ii = 0; $ii < $giftJsonRow['invoiceqty']; $ii++) {
                                array_push($voucherDtl, array(
                                    'typeId'    => $giftJsonRow['coupontypeid'], 
                                    'amount'    => $giftJsonRow['couponamount'], 
                                    'percent'   => issetParam($giftJsonRow['couponpercent']), 
                                    'imageFile' => issetParam($giftJsonRow['imagefile']), 
                                    'percentamount' => issetParam($giftJsonRow['couponpercentamount']), 
                                    'name'          => $giftJsonRow['coupontypename'], 
                                    'rowIndex'      => $k
                                ));
                            }                              
                        }
                        
                        $totalItemCount += $itemQty;
                        
                        if ($giftJsonRow['coupontypeid'] == '') {
                            
                            $giftJsonRow['isgift']     = 1;
                            $giftJsonRow['employeeId'] = $employeeId; 
                            $giftJsonRow['itemid']     = $giftJsonRow['promotionitemid']; 
                            $giftJsonRow['jobid']      = $giftJsonRow['jobid']; 
                            
                            if ($giftJsonRow['isservice'] == 1 && $giftJsonRow['jobid'] != '') {
                                
                                $serviceDtl[] = array(
                                    'jobId' => $giftJsonRow['jobid'], 
                                    'RPR_KEY' => array(
                                        'customerId' => $headerParams['customerId'], 
                                        'jobid' => $giftJsonRow['jobid'], 
                                        'purchaseStoreId' => $storeId
                                    )
                                );
                            }
                            
                            $itemGiftPrice = $giftJsonRow['saleprice'];
                            $itemGiftUnitPrice = $giftJsonRow['saleprice'];
                            
                            if ($itemGiftPrice > 0 && ($giftJsonRow['discountamount'] > 0 || $giftJsonRow['discountpercent'] > 0)) {
                                
                                $giftDiscountAmount = $itemGiftPrice;
                                    
                                if ($giftJsonRow['discountamount'] > 0) {

                                    $giftDiscountAmount = $giftJsonRow['discountamount'];

                                } elseif ($giftJsonRow['discountpercent'] > 0) {

                                    $giftDiscount = ($giftJsonRow['discountpercent'] / 100) * $itemGiftPrice;
                                    $giftDiscountAmount = $giftDiscount;
                                }
                                                        
                                $itemGiftPrice       = $itemGiftPrice - $giftDiscountAmount;
                                $giftLineTotalAmount = $itemGiftPrice * $itemQty;
                                $giftLineTotalVat    = number_format(($giftLineTotalAmount - ($giftLineTotalAmount / 1.1)), 2, '.', '');
                                
                                if ($totalBonusAmount > 0) {
                    
                                    $giftUnitDiscount  = ($dtlBonusPercent / 100) * $itemGiftPrice;
                                    $giftDtlDiscountAmount = $itemGiftPrice - $giftUnitDiscount;

                                    $itemGiftPrice   = $giftDtlDiscountAmount;
                                    $giftNoVatPrice  = number_format($giftDtlDiscountAmount / 1.1, 2, '.', '');
                                    $giftUnitVat     = number_format($giftDtlDiscountAmount - $giftNoVatPrice, 2, '.', '');

                                    $giftLineTotalDiscount = $giftUnitDiscount * $itemQty;
                                    $giftLineTotalAmount = $giftDtlDiscountAmount * $itemQty;

                                    $giftLineTotalVat = $giftLineTotalAmount - $giftLineTotalAmount / 1.1;
                                    
                                } else {
                                    $giftLineTotalDiscount = 0;
                                    $giftUnitVat = number_format($itemGiftPrice - (number_format($itemGiftPrice / 1.1, 2, '.', '')), 2, '.', '');
                                }
                                
                                if ($isVatCalc == false || issetParam($giftJsonRow['isvat']) == 0) {
                                    if ($params["total"] > $itemGiftPrice) {
                                        $params["total"] = $params["total"] - $itemGiftPrice;
                                    }

                                    // $itemGiftPrice = $itemGiftPrice - $giftUnitVat;
                                    // $giftLineTotalAmount = $giftLineTotalAmount - $giftLineTotalVat;                                
                                    
                                    $giftUnitVat = 0;
                                    $giftLineTotalVat = 0;

                                }
                
                                $stocks .= "{
                                    'code': '" . self::apiStringReplace($giftJsonRow['itemcode']) . "',
                                    'name': '" . self::apiStringReplace($giftJsonRow['itemname']) . "',
                                    'measureUnit': '" . self::convertCyrillicMongolia('ш') . "',
                                    'qty': '" . sprintf("%.2f", $itemQty) . "',
                                    'unitPrice': '" . sprintf("%.2f", $itemGiftPrice) . "',
                                    'totalAmount': '" . sprintf("%.2f", $giftLineTotalAmount) . "',
                                    'cityTax': '0.00',
                                    'vat': '" . sprintf("%.2f", $giftLineTotalVat) . "',
                                    'barCode': '" . $giftJsonRow['barcode'] . "'
                                }, ";  
                                
                                $row = array(
                                    'cityTax'        => '', 
                                    'itemName'       => $giftJsonRow['itemname'], 
                                    'salePrice'      => $itemGiftPrice, 
                                    'itemQty'        => $itemQty, 
                                    'totalPrice'     => $giftLineTotalAmount, 
                                    'unitReceivable' => 0, 
                                    'maxPrice'       => 0, 
                                    'isDelivery'     => $giftJsonRow['isDelivery']
                                );
                                if (Input::isEmpty('returnInvoiceId') == false) {
                                    if ($row['totalPrice']) {
                                        $itemPrintList .= self::{$itemPrintRenderFncName}($row);
                                    }
                                } else {
                                    $itemPrintList .= self::{$itemPrintRenderFncName}($row);
                                }     
                                
                                $giftJsonRow['saleprice'] = $itemGiftUnitPrice;
                                $giftJsonRow['unitPrice'] = $itemGiftUnitPrice;
                                $giftJsonRow['lineTotalPrice'] = $itemGiftUnitPrice * $itemQty;
                                
                                $giftJsonRow['unitAmount'] = $itemGiftPrice;
                                $giftJsonRow['lineTotalAmount'] = $giftLineTotalAmount;
                    
                                $giftJsonRow['percentVat'] = 10;
                                $giftJsonRow['unitVat'] = $giftUnitVat;
                                $giftJsonRow['lineTotalVat'] = number_format($giftJsonRow['unitVat'] * $itemQty, 2, '.', '');
                                
                                $giftJsonRow['percentDiscount'] = 0;
                                $giftJsonRow['unitDiscount'] = 0;
                                $giftJsonRow['lineTotalDiscount'] = $giftLineTotalDiscount;
                                
                                $giftJsonRowMerge['invoiceqty'] = 1;
                                $giftJsonRowMerge['unitPrice'] = $itemGiftPrice;
                                $giftJsonRowMerge['lineTotalPrice'] = $itemGiftPrice;
                                
                                $giftJsonRowMerge['unitAmount'] = $itemGiftPrice;
                                $giftJsonRowMerge['lineTotalAmount'] = $itemGiftPrice;
                    
                                $giftJsonRowMerge['percentVat'] = 10;
                                $giftJsonRowMerge['unitVat'] = number_format($itemGiftPrice - (number_format($itemGiftPrice / 1.1, 2, '.', '')), 2, '.', '');
                                $giftJsonRowMerge['lineTotalVat'] = $giftJsonRowMerge['unitVat'];
                                
                                $sumTotal += $giftLineTotalAmount;
                                
                            } else {
                                $giftList .= self::giftPrintRow($giftJsonRow);
                            }
                            
                            if ($giftJsonRow['jobid'] == '') {
                                
                                if ($itemQty > 1) {
                                    
                                    $giftJsonRowMerge['invoiceqty'] = 1;
                                    
                                    if ($giftJsonRow['isDelivery'] == 1 && $isServicePos != '1') {
                                        
                                        $giftJsonRow['warehouseId'] = $deliveryWarehouseId; 
                                        $giftJsonRowLoop = array_merge($giftJsonRow, $giftJsonRowMerge);
                                        
                                        for ($gt = 0; $gt < $itemQty; $gt++) {
                                            $deliveryDtl[] = $giftJsonRowLoop;
                                        }
                                        
                                    } else {
                                        $giftJsonRow['warehouseId'] = $storeWarehouseId; 
                                        $giftJsonRowLoop = array_merge($giftJsonRow, $giftJsonRowMerge);
                                        
                                        for ($gt = 0; $gt < $itemQty; $gt++) {
                                            $noDeliveryDtl[] = $giftJsonRowLoop;
                                        }
                                    }
                                    
                                } else {
                                    if ($giftJsonRow['isDelivery'] == 1 && $isServicePos != '1') {
                                        $giftJsonRow['warehouseId'] = $deliveryWarehouseId; 
                                        $deliveryDtl[] = $giftJsonRow;
                                    } else {
                                        $giftJsonRow['warehouseId'] = $storeWarehouseId; 
                                        $noDeliveryDtl[] = $giftJsonRow;
                                    }
                                }
                            }
                            
                            $itemGiftList[] = $giftJsonRow;
                            
                        } else {
                            $giftList .= self::giftPrintRow($giftJsonRow);
                        }
                    }
                }
                
                $paramsDtl[$k]['SDM_SALES_ORDER_ITEM_PACKAGE'] = $itemPackageList;
                $paramsDtl[$k]['POS_SM_SALES_INVOICE_DETAIL'] = $itemGiftList;
            }
            
            if ($isJob == '0' || $isJob == '') {
                
                if ($isDelivery == 1) {

                    $paramsDtl[$k]['warehouseId'] = $deliveryWarehouseId;
                    $deliveryDtl[] = $paramsDtl[$k];

                } else {
                    $paramsDtl[$k]['warehouseId'] = $storeWarehouseId;
                    $noDeliveryDtl[] = $paramsDtl[$k];
                }
            }
            
            if ($itemPrintCopies && $itemPrintCopies > $itemPrintCopiesLast) {
                $itemPrintCopiesLast = $itemPrintCopies;
            }
        }   
        // </editor-fold>

        if (Config::getFromCache('POS_IS_USE_MULTI_BILL_NOT_PACKAGE_POLICY') === '1') {
            $params['totalCityTaxAmount'] = $sumCityTax;
            $cityTaxAmount = $sumCityTax;
        }        
        
        //        if ($totalAmount != $sumTotal) {
        //            return array('status' => 'amounterror', 'message' => '');
        //        }
        
        $params['POS_SM_SALES_INVOICE_DETAIL'] = $paramsDtl;
        
        if ($cityTaxAmount > 0) {
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0082'), self::posAmount($cityTaxAmount)), $paymentDtlTemplate);
        }
        
        // <editor-fold defaultstate="collapsed" desc="Payment">
        if ($cashAmount > 0) {
            $saveCashAmt = $cashAmount - $changeAmount;
            
            $paymentDtl[] = array(
                'paymentTypeId' => 1, 
                'amount'        => $saveCashAmt
            );
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0083'), self::posAmount($cashAmount)), $paymentDtlTemplate);
            
            $generateVaucherAmt += $saveCashAmt;
        }
        
        $infoIpTerminal = '';
        
        if ($bankAmount > 0) {
            
            $bankAmountDtl = $paymentData['bankAmountDtl'];
            
            foreach ($bankAmountDtl as $b => $bankDtlAmount) {
                
                $bankId         = $paymentData['posBankIdDtl'][$b];
                $bankDtlAmount  = Number::decimal($bankDtlAmount);
                        
                if ($bankId != '' && $bankDtlAmount > 0) {
                    
                    $paymentDtl[] = array(
                        'paymentTypeId' => 2, 
                        'bankId'        => $bankId,
                        'bankCardNumber' => $paymentData['devicePan'][$b],
                        'confirmCode' => $paymentData['deviceAuthcode'][$b],
                        'cardRegisterNumber' => $paymentData['deviceRrn'][$b],
                        'terminalNumber' => $paymentData['deviceTerminalId'][$b],
                        'traceNo' => $paymentData['deviceTraceNo'][$b],
                        'amount'        => $bankDtlAmount
                    );
                    
                    $isBankCardPaid = true;
                    $infoIpTerminal .= '<tr>
                        <td>
                            <table border="0" width="100%" style="width: 100%; table-layout: fixed; font-family: Tahoma; font-size: 9px; font-weight: normal;">
                                <thead>
                                    <tr>
                                        <td style="text-align: left; padding: 0; width: 33%;">Карт</td>
                                        <td style="text-align: left; padding: 0; width: 17%;">З/код</td>
                                        <td style="text-align: left; padding: 0; width: 30%;">RRN</td>
                                        <td style="text-align: left; padding: 0; width: 20%;">Терминал</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="text-align: left; padding: 0; width: 33%;">'.$paymentData['devicePan'][$b].'</td>
                                        <td style="text-align: left; padding: 0; width: 17%;">'.$paymentData['deviceAuthcode'][$b].'</td>
                                        <td style="text-align: left; padding: 0; width: 30%;">'.$paymentData['deviceRrn'][$b].'</td>
                                        <td style="text-align: left; padding: 0; width: 20%;">'.$paymentData['deviceTerminalId'][$b].'</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>';
                }
            }
            
            $nonCashAmount += $bankAmount;
            $generateVaucherAmt += $bankAmount;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0084'), self::posAmount($bankAmount)), $paymentDtlTemplate);
        }
        
        if ($itemDiscountAmount > 0) {
            $discountDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0085'), self::posAmount($itemDiscountAmount)), $paymentDtlTemplate);
        }
        
        $voucherSerialNumber = '';
        
        if ($voucherAmount > 0) {
            
            $voucherDtlAmount = $paymentData['voucherDtlAmount'];
            
            foreach ($voucherDtlAmount as $v => $voucherAmountDtl) {
                
                $voucherId           = $paymentData['voucherDtlId'][$v];
                $voucherTypeId       = $paymentData['voucherTypeId'][$v];
                $voucherAmountDtl    = Number::decimal($voucherAmountDtl);
                $voucherSerialNumber = $paymentData['voucherDtlSerialNumber'][$v];
                
                if ($voucherId != '' && $voucherAmountDtl > 0 && $voucherSerialNumber != '') {
                    
                    $voucherUsedDtl[] = array(
                        'id' => $voucherId
                    );
                    
                    if ($voucherTypeId == 1) {
                        $voucherPaymentTypeId = 10;
                        $voucherTypeName = Lang::line('POS_0086');
                    } elseif ($voucherTypeId == 2) {
                        $voucherPaymentTypeId = 9;
                        $voucherTypeName = Lang::line('POS_0087');
                    } elseif ($voucherTypeId == 4) {
                        $voucherPaymentTypeId = 11;
                        $voucherTypeName = Lang::line('POS_0044');
                    } else {
                        $voucherPaymentTypeId = 10;
                        $voucherTypeName = Lang::line('POS_0088');
                    }
                    
                    $paymentDtl[] = array(
                        'paymentTypeId' => $voucherPaymentTypeId, 
                        'amount'        => $voucherAmountDtl
                    );
                    
                    $discountDetail .= str_replace(array('{labelName}', '{amount}'), array($voucherTypeName, self::posAmount($voucherAmountDtl)), $paymentDtlTemplate);
                }
            }
            
            //$nonCashAmount += $voucherAmount;
        }   
        
        $voucherSerialNumber = '';
        
        if ($voucher2Amount >= 0 && isset($paymentData['voucher2DtlAmount'])) {
            $voucherDtlAmount    = $paymentData['voucher2DtlAmount'];

            foreach ($voucherDtlAmount as $v => $voucherAmountDtl) {
                $voucherId           = $paymentData['voucher2DtlId'][$v];
                $voucherTypeId       = $paymentData['voucher2TypeId'][$v];
                $voucherSerialNumber = $paymentData['voucher2DtlSerialNumber'][$v];
                $voucherAmountDtl = Number::decimal($voucherAmountDtl);
                
                //if ($voucherId != '' && $voucherAmountDtl >= 0 && $voucherSerialNumber != '') {
                if ($voucherId != '' && $voucherAmountDtl >= 0) {
                    
                    $paymentDtl[] = array(
                        'paymentTypeId' => 34, 
                        'amount'        => $voucherAmountDtl
                    );

                    // $params['couponKeyId'] = $voucherId;
                    // $params['outAmt'] = $voucher2Amount;

                    $voucherTypeName = Lang::line('POS_0214');
                    $discountDetail .= str_replace(array('{labelName}', '{amount}'), array($voucherTypeName, self::posAmount($voucherAmountDtl)), $paymentDtlTemplate);
                    
                    // $params['LOY_PAYMENT_BOOK_MAP_STORE'][] = array(
                    //     'LOY_PAYMENT_BOOK_STORE' => array(
                    //         'LOY_PAYMENT_DTL_STORE' => array(
                    //             'outAmt'      => $voucherAmountDtl, 
                    //             'couponKeyId' => $voucherId 
                    //         )
                    //     )
                    // );
                    
                    $nonCashAmount += $voucherAmountDtl;
                
                    $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array($voucherTypeName, self::posAmount($voucherAmountDtl)), $paymentDtlTemplate);
                    
                    $discountDetail .= str_replace(array('{labelName}', '{amount}'), array('Эхний үлдэгдэл', self::posAmount($paymentData['cardBeginAmountCoupon'])), $paymentDtlTemplate);
                    $discountDetail .= str_replace(array('{labelName}', '{amount}'), array('Эцсийн үлдэгдэл', self::posAmount($paymentData['cardEndAmountCoupon'])), $paymentDtlTemplate);
                }
            }
        }

        if ($discountActivityAmount2 > 0) {
                
            $paymentDtl[] = array(
                'paymentTypeId' => 45, 
                'customerId' => $paymentData['discountActivityCustomerId'], 
                'amount'        => $discountActivityAmount2
            );
            
            $discountDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('SM_0001'), self::posAmount($discountActivityAmount2)), $paymentDtlTemplate);
        }        

        if ($insuranceAmount > 0) {
                
            $paymentDtl[] = array(
                'paymentTypeId' => 42, 
                'amount'        => $insuranceAmount
            );
            
            $discountDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0218'), self::posAmount($insuranceAmount)), $paymentDtlTemplate);
        }        
        
        if ($bonusCardMemberShipId != '') {
            
            $bonusCardMemberShipId          = $paymentData['cardMemberShipId'];
            $bonusCardNumber                = Input::param($paymentData['cardNumber']);
            $bonusCardDiscountPercent       = $paymentData['cardDiscountPercent'];
            $bonusCardBeginAmount           = Number::decimal($paymentData['cardBeginAmount']);
            $bonusCardDiscountPercentAmount = Number::decimal($paymentData['cardDiscountPercentAmount']);
            $bonusCardEndAmount             = Number::decimal($paymentData['cardEndAmount']);
            
            if ($bonusCardAmount > 0) {
                
                $paymentDtl[] = array(
                    'paymentTypeId' => 12, 
                    'amount'        => $bonusCardAmount, 
                    'membershipId'  => $bonusCardMemberShipId
                );
                
                //$nonCashAmount += $bonusCardAmount;
                
                $discountDetail .= str_replace(array('{labelName}', '{amount}'), array('Хөнгөлөлтийн карт', self::posAmount($bonusCardAmount)), $paymentDtlTemplate);
            }
            
            if (($bonusCardDiscountPercentAmount > 0 && issetParam($paymentData['cardDiscountType']) == '+') || $bonusCardAmount > 0) {
                
                /*$params['LOY_PAYMENT_BOOK_MAP_STORE'][] = array(
                    'LOY_PAYMENT_BOOK_STORE' => array(
                        'bookDate'   => $currentDate, 
                        'bookNumber' => self::getPosInvoiceNumber('1489109738291'), 
                        'LOY_PAYMENT_DTL_STORE' => array(
                            'membershipId' => $bonusCardMemberShipId, 
                            'inBonusAmt'   => $bonusCardDiscountPercentAmount,  
                            'outBonusAmt'  => $bonusCardAmount, 
                            'description'  => 'pos'
                        )
                    )
                );*/

                /*$params['LOY_PAYMENT_BOOK_MAP_STORE']['LOY_PAYMENT_BOOK_STORE']['bookDate']   = $currentDate;
                $params['LOY_PAYMENT_BOOK_MAP_STORE']['LOY_PAYMENT_BOOK_STORE']['bookNumber'] = self::getPosInvoiceNumber('1489109738291');
                
                $params['LOY_PAYMENT_BOOK_MAP_STORE']['LOY_PAYMENT_BOOK_STORE']['LOY_PAYMENT_DTL_STORE']['membershipId'] = $bonusCardMemberShipId;
                $params['LOY_PAYMENT_BOOK_MAP_STORE']['LOY_PAYMENT_BOOK_STORE']['LOY_PAYMENT_DTL_STORE']['inBonusAmt']   = $bonusCardDiscountPercentAmount;
                $params['LOY_PAYMENT_BOOK_MAP_STORE']['LOY_PAYMENT_BOOK_STORE']['LOY_PAYMENT_DTL_STORE']['outBonusAmt']  = $bonusCardAmount;
                $params['LOY_PAYMENT_BOOK_MAP_STORE']['LOY_PAYMENT_BOOK_STORE']['LOY_PAYMENT_DTL_STORE']['description']  = 'pos';*/
            }
            
            if ($bonusCardBeginAmount < 1) {
                $bonusCardEndAmount = $bonusCardDiscountPercentAmount;
            }
            
            $params['cardId'] = $paymentData['cardId'];
            
        } else {
            $bonusCardNumber                = '00000000';
            $bonusCardDiscountPercent       = '0';
            $bonusCardBeginAmount           = '0';
            $bonusCardDiscountPercentAmount = '0';
            $bonusCardEndAmount             = '0';
        }
        
        /*if (Input::isEmpty('basketInvoiceId') == false && $getOrderBook = $this->getInvoiceByIdModel(array('id' => Input::post('basketInvoiceId'), 'typeid' => '1'))) {
            if ($getOrderBook['status'] == 'success' && $getOrderBook['data'] && isset($getOrderBook['data']['loy_payment_book'])) {
                foreach ($getOrderBook['data']['loy_payment_book'] as $brow) {
                    $params['LOY_PAYMENT_BOOK_MAP_STORE'][] = array(
                        'LOY_PAYMENT_BOOK_STORE' => array(
                            'bookDate'   => $currentDate, 
                            'bookNumber' => self::getPosInvoiceNumber('1489109738291'), 
                            'LOY_PAYMENT_DTL_STORE' => array(
                                'membershipId' => $brow['membershipid'], 
                                'inBonusAmt'   => $brow['inamt'],  
                                'outBonusAmt'  => '', 
                                'customerid'  => $brow['customerid'], 
                                'description'  => 'pos'
                            )
                        )
                    );      
                }
            }
        }*/
        
        if ($accountTransferAmt > 0) {
            
            $isInvInfo = true;
            
            if (isset($paymentData['accountTransferAmountDtl'])) {
                
                $accountTransferAmountDtl = $paymentData['accountTransferAmountDtl'];
            
                foreach ($accountTransferAmountDtl as $t => $accountTransferDtlAmount) {

                    $accountTransferBankId    = $paymentData['accountTransferBankIdDtl'][$t];
                    $accountTransferDtlAmount = Number::decimal($accountTransferDtlAmount);

                    if ($accountTransferDtlAmount > 0 && $accountTransferBankId != '') {

                        $paymentDtl[] = array(
                            'paymentTypeId' => 4, 
                            'bankId'        => $accountTransferBankId, 
                            'amount'        => $accountTransferDtlAmount, 
                            'bankBillingId' => $paymentData['accountTransferBillingIdDtl'][$t], 
                            'description'   => $paymentData['accountTransferDescrDtl'][$t]
                        );
                        
                        if (isset($paymentData['accountTransferBillingIdDtl'][$t]) && $paymentData['accountTransferBillingIdDtl'][$t]) {
                            $atBankBillingIds[] = $paymentData['accountTransferBillingIdDtl'][$t];
                        }
                    }
                }
                
            } else {
                $paymentDtl[] = array(
                    'paymentTypeId' => 4, 
                    'bankId'        => $paymentData['posAccountTransferBankId'],
                    'amount'        => $accountTransferAmt
                );
            }
            
            $nonCashAmount += $accountTransferAmt;
            $generateVaucherAmt += $accountTransferAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0089'), self::posAmount($accountTransferAmt)), $paymentDtlTemplate);
        }
        
        if ($mobileNetAmt > 0) {
            
            $isInvInfo = true;
            
            $paymentDtl[] = array(
                'paymentTypeId' => 3, 
                'bankId'        => $paymentData['posMobileNetBankId'],
                'amount'        => $mobileNetAmt
            );
            
            $nonCashAmount += $mobileNetAmt;
            $generateVaucherAmt += $mobileNetAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0090'), self::posAmount($mobileNetAmt)), $paymentDtlTemplate);
        }
        
        if ($posOtherAmt > 0) {
            
            $isInvInfo = true;
            
            $paymentDtl[] = array(
                'paymentTypeId' => 43, 
                'amount'        => $posOtherAmt
            );
            
            $nonCashAmount += $posOtherAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0219'), self::posAmount($posOtherAmt)), $paymentDtlTemplate);
        }
        
        if ($posTcardAmt > 0) {
            
            $isInvInfo = true;
            
            $paymentDtl[] = array(
                'paymentTypeId' => 51, 
                'amount'        => $posTcardAmt
            );
            
            $nonCashAmount += $posTcardAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0931'), self::posAmount($posTcardAmt)), $paymentDtlTemplate);
        }
        
        if ($posShoppyAmt > 0) {
            
            $isInvInfo = true;
            
            $paymentDtl[] = array(
                'paymentTypeId' => 52, 
                'amount'        => $posShoppyAmt
            );
            
            $nonCashAmount += $posShoppyAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0932'), self::posAmount($posShoppyAmt)), $paymentDtlTemplate);
        }
        
        if ($posGlmtRewardAmt > 0) {
            
            $isInvInfo = true;
            
            $paymentDtl[] = array(
                'paymentTypeId' => 53, 
                'amount'        => $posGlmtRewardAmt
            );
            
            $nonCashAmount += $posGlmtRewardAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0933'), self::posAmount($posGlmtRewardAmt)), $paymentDtlTemplate);
        }
        
        if ($posSocialpayrewardAmt > 0) {
            
            $isInvInfo = true;
            
            $paymentDtl[] = array(
                'paymentTypeId' => 54, 
                'amount'        => $posSocialpayrewardAmt
            );
            
            $nonCashAmount += $posSocialpayrewardAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0934'), self::posAmount($posSocialpayrewardAmt)), $paymentDtlTemplate);
        }
        
        if ($prePaymentAmount > 0) {
            
            $isInvInfo = true;
            
            $paymentDtl[] = array(
                'paymentTypeId'  => 20, 
                'extTransactionId'=> $paymentData['prePaymentCustomerId'],
                'amount'         => $prePaymentAmount
            );
            
            $nonCashAmount += $prePaymentAmount;
            $generateVaucherAmt += $prePaymentAmount;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_GLOBE_PREPAYMENT'), self::posAmount($prePaymentAmount)), $paymentDtlTemplate);
        }
        
        if ($barterAmt > 0) {
            
            $isInvInfo = true;
            
            $paymentDtl[] = array(
                'paymentTypeId' => 5, 
                'amount'        => $barterAmt
            );
            
            $nonCashAmount += $barterAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0091'), self::posAmount($barterAmt)), $paymentDtlTemplate);
        }
        
        if ($leasingAmt > 0) {
            
            if (Config::getFromCache('CONFIG_POS_PAYMENT_LEASING_IS_VAT') != 1) {
                $billTitle = Lang::line('POS_0092');
                $isPut = false;
                $isInvInfo = true;
            }
            
            $paymentDtl[] = array(
                'paymentTypeId' => 6, 
                'bankId'        => $paymentData['posLeasingBankId'],
                'amount'        => $leasingAmt
            );
            
            $nonCashAmount += $leasingAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0092'), self::posAmount($leasingAmt)), $paymentDtlTemplate);
        }
        
        if ($empLoanAmt > 0) {
            
            $isInvInfo = true;
            
            $paymentDtl[] = array(
                'paymentTypeId' => 7, 
                'amount'        => $empLoanAmt
            );
            
            $nonCashAmount += $empLoanAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0093'), self::posAmount($empLoanAmt)), $paymentDtlTemplate);
        }
        
        if ($localExpenseAmt > 0) {
            
            $billTitle = Lang::line('POS_0094');
            $isPut = false;
            
            $params['localExpenseType']   = 1001;
            $params['localExpenseAmount'] = $localExpenseAmt;
            
            $paymentDtl[] = array(
                'paymentTypeId' => 13, 
                'amount'        => $localExpenseAmt,
                'customerId' => $paymentData['localExpenseCustomerId'],
            );
            
            $nonCashAmount += $localExpenseAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0094'), self::posAmount($localExpenseAmt)), $paymentDtlTemplate);
            
            $params = self::setZeroVatAmount($params);
        }
        
        if ($posLiciengExpenseAmt > 0) {

            $isInvInfo = true;
            
            $paymentDtl[] = array(
                'paymentTypeId' => 33, 
                'amount'        => $posLiciengExpenseAmt
            );
            
            $nonCashAmount += $posLiciengExpenseAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array('ХЭРЭГЛЭЭНИЙ ЛИЗИНГ', self::posAmount($posLiciengExpenseAmt)), $paymentDtlTemplate);
            
            $params = self::setZeroVatAmount($params);
        }
        
        if ($posSocialpayAmt > 0) {
            
            $paymentDtl[] = array(
                'paymentTypeId' => 19, 
                'extTransactionId'  => $paymentData['posSocialpayUID'],
                'confirmCode'   => $paymentData['posSocialpayApprovalCode'],
                'bankCardNumber'=> $paymentData['posSocialpayCardNumber'],
                'terminalNumber'=> $paymentData['posSocialpayTerminal'],
                'amount'        => $posSocialpayAmt
            );
            
            $infoIpTerminal .= '<tr>
                <td>
                    <table border="0" width="100%" style="width: 100%; table-layout: fixed; font-family: Tahoma; font-size: 9px; font-weight: normal;">
                        <thead>
                            <tr>
                                <td style="text-align: left; padding: 0; width: 50%;">Карт</td>
                                <td style="text-align: left; padding: 0; width: 25%;">З/код</td>
                                <td style="text-align: left; padding: 0; width: 25%;">Терминал</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align: left; padding: 0; width: 50%;">'.$paymentData['posSocialpayCardNumber'].'</td>
                                <td style="text-align: left; padding: 0; width: 25%;">'.$paymentData['posSocialpayApprovalCode'].'</td>
                                <td style="text-align: left; padding: 0; width: 25%;">'.$paymentData['posSocialpayTerminal'].'</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>';            
            
            $nonCashAmount += $posSocialpayAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array('Social Pay', self::posAmount($posSocialpayAmt)), $paymentDtlTemplate);
        }
        
        if ($certificateExpenseAmt > 0) {
            
            if ($sumTotal == $certificateExpenseAmt) {
                $isPut = false;
            }
            
            $billTitle = 'ЭРХИЙН БИЧИГ';
            
            $paymentDtl[] = array(
                'paymentTypeId' => 9, 
                'amount'        => $certificateExpenseAmt
            );
            
            $nonCashAmount += $certificateExpenseAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array('Эрхийн бичиг', self::posAmount($certificateExpenseAmt)), $paymentDtlTemplate);
            
            $params = self::setZeroVatAmount($params);
        }
        
        if ($emdAmount > 0) {
            
            $paymentDtl[] = array(
                'paymentTypeId' => 14, 
                'amount'        => $emdAmount
            );
            
            $nonCashAmount += $emdAmount;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0095'), self::posAmount($emdAmount)), $paymentDtlTemplate);
        }
        
        if ($warrantyRepairAmt > 0) {
            
            $paymentDtl[] = array(
                'paymentTypeId' => 46, 
                'amount'        => $warrantyRepairAmt
            );
            
            $nonCashAmount += $warrantyRepairAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0221'), self::posAmount($warrantyRepairAmt)), $paymentDtlTemplate);
        }
        
        if ($deliveryAmount > 0) {
            
            $paymentDtl[] = array(
                'paymentTypeId' => 17, 
                'amount'        => $deliveryAmount
            );
            
            $nonCashAmount += $deliveryAmount;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array('Үлдэгдэл төлбөр', self::posAmount($deliveryAmount)), $paymentDtlTemplate);
        }
        
        if ($candyAmount > 0) {
            
            $candyAmountDtl = $paymentData['candyAmountDtl'];
            
            foreach ($candyAmountDtl as $c => $candyDtlAmount) {
                
                $candyTypeCode  = $paymentData['candyTypeCodeDtl'][$c];
                $candyNumber    = $paymentData['candyDetectedNumberDtl'][$c];
                $candyTransactionId = $paymentData['candyTransactionIdDtl'][$c];
                $candyDtlAmount     = Number::decimal($candyDtlAmount);
                        
                if ($candyTypeCode && $candyDtlAmount > 0) {
                    
                    $paymentDtl[] = array(
                        'paymentTypeId' => 15, 
                        'amount'        => $candyDtlAmount, 
                        'candyTypeCode' => $candyTypeCode,
                        'candyNumber'   => '',
                        'candyTransactionId' => '',
                    );
                    
                    $candyLabelName = '';
                    if ($candyTypeCode == 'qrcodegenerate' && $candyTypeCode == 'qrcoderead') {
                        $candyLabelName = '(QR код)';
                    }
                    
                    $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array('Монпэй'.$candyLabelName, self::posAmount($candyDtlAmount)), $paymentDtlTemplate);
                }
            }
            
            $nonCashAmount += $candyAmount;
        }
        
        if ($qpayAmount > 0) {
            
            $paymentDtl[] = array(
                'paymentTypeId' => 40, 
                'amount'        => $qpayAmount,
                'extTransactionId' => $paymentData['qpay_traceNo'],
            );

            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array('Qpay', self::posAmount($qpayAmount)), $paymentDtlTemplate);
            
            $nonCashAmount += $qpayAmount;
        }
        
        if ($tokipayAmount > 0) {
            
            $paymentDtl[] = array(
                'paymentTypeId' => 56, 
                'amount'        => $tokipayAmount,
                'extTransactionId' => $paymentData['tokipay_traceNo'],
            );

            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array('Tokipay', self::posAmount($tokipayAmount)), $paymentDtlTemplate);
            
            $nonCashAmount += $tokipayAmount;
        }
        
        $upointPart = '';
        if ($upointAmount > 0 || issetParam($paymentData['upointBalance'])) {
            
            $upointAmountDtl = $paymentData['upointAmountDtl'];
            $params['phonenumber'] = $paymentData['upointMobile'];
            
            if ($returnType == 'typeReduce') {
                
                $posUpointReturnResult = Input::post('posUpointReturnResult');
                if ($posUpointReturnResult) {            
                    $posUpointReturnResult = json_decode(html_entity_decode($posUpointReturnResult, ENT_QUOTES, 'UTF-8'), true);
                    //$upointPart = $this->upointTableRender(Input::post('upointDetectedNumberDtl'), $paymentData['upointBalance'], $posUpointReturnResult['refund_bonus_amount'], $posUpointReturnResult['point_balance'], $posUpointReturnResult['point_balance'] - $paymentData['upointBalance']);
                    $upointPart = $this->upointTableRender(Input::post('upointDetectedNumberDtl'), $paymentData['upointBalance'], $posUpointReturnResult['refund_bonus_amount'], $posUpointReturnResult['point_balance'], 0);
                }                                 
                
                foreach ($upointAmountDtl as $c => $upointDtlAmount) {                
                    $upointDtlAmount     = Number::decimal($upointDtlAmount);


                    $paymentDtl[] = array(
                        'paymentTypeId'    => 36, 
                        'amount'           => $upointDtlAmount,
                        'outAmt'           => $upointDtlAmount,
                        'extTransactionId' => $posUpointReturnResult['receipt_id'],
                        'calcAmount'       => Number::decimal($paymentData['upointPayAmount']),
                        'bankCardNumber'   => $paymentData['upointCardNumber'],
                        'inAmt'            => $posUpointReturnResult['point_balance'],
                    );

                }                     
                
            } else {
                
                foreach ($upointAmountDtl as $c => $upointDtlAmount) {                
                    $upointDtlAmount     = Number::decimal($upointDtlAmount);


                    $paymentDtl[] = array(
                        'paymentTypeId'    => 36, 
                        'amount'           => $upointDtlAmount,
                        'outAmt'           => $upointDtlAmount,
                        'extTransactionId' => $resultUpoint['data']['receipt_id'],
                        'calcAmount'       => Number::decimal($paymentData['upointPayAmount']),
                        'bankCardNumber'   => $paymentData['upointCardNumber'],
                        'inAmt'            => $resultUpoint['data']['total_point'],
                    );

                    $upointPart = $this->upointTableRender($paymentData['upointCardNumber'], $paymentData['upointBalance'], $upointAmount, $resultUpoint['data']['point_balance'], $resultUpoint['data']['total_point']);                    
                }     
                
            }
            
            $nonCashAmount += $upointAmount;
        }
        
        if ($candyCouponAmount > 0) {
            
            $candyAmountDtl = $paymentData['candyCouponAmountDtl'];
            
            foreach ($candyAmountDtl as $c => $candyDtlAmount) {
                
                $candyTypeCode  = $paymentData['candyCouponTypeCodeDtl'][$c];
                $candyNumber    = $paymentData['candyCouponDetectedNumberDtl'][$c];
                $candyTransactionId = $paymentData['candyCouponTransactionIdDtl'][$c];
                $candyDtlAmount     = Number::decimal($candyDtlAmount);
                        
                if ($candyNumber && $candyDtlAmount > 0) {
                    
                    $candyLabelName = '';
                    $paymentDtl[] = array(
                        'paymentTypeId' => 35, 
                        'amount'        => $candyDtlAmount, 
                        'candyTypeCode' => 'coupon',
                        'candyCouponCode' => $candyNumber,
                        'candyNumber'   => '',
                        'candyTransactionId' => '',
                    );                    
                    $candyLabelName = '('.$candyNumber.')';
                    
                    $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array('Монпэй купон', self::posAmount($candyDtlAmount)), $paymentDtlTemplate);
                }
            }
            
            $nonCashAmount += $candyAmount;
        }
        
        if ($lendMnAmount > 0) {
            
            $paymentDtl[] = array(
                'paymentTypeId' => 18, 
                'amount'        => $lendMnAmount
            );
            
            $nonCashAmount += $lendMnAmount;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array('LendMN', self::posAmount($lendMnAmount)), $paymentDtlTemplate);
        }
        
        if ($posRecievableAmt > 0) {

            if (isset($paymentData['posRecievableAmtDtl'])) {
                
                $accountTransferAmountDtl = $paymentData['posRecievableAmtDtl'];
            
                foreach ($accountTransferAmountDtl as $t => $accountTransferDtlAmount) {

                    $accountTransferDtlAmount = Number::decimal($accountTransferDtlAmount);

                    if ($accountTransferDtlAmount > 0) {
                        $paymentDtl[] = array(
                            'paymentTypeId' => 22, 
                            'customerId'    => $paymentData['recievableCustomerId'][$t], 
                            'amount'        => $accountTransferDtlAmount, 
                        );
                    }
                }
                
            } else {
                $paymentDtl[] = array(
                    'paymentTypeId' => 22, 
                    'customerId' => Input::post('recievableCustomerId'), 
                    'amount'        => $posRecievableAmt
                );
            }
            
            $nonCashAmount += $posRecievableAmt;            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0217'), self::posAmount($posRecievableAmt)), $paymentDtlTemplate);
        }
        // </editor-fold>
        
        if ($changeAmount > 0) {
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0096'), self::posAmount($changeAmount)), $paymentDtlTemplate);
        }
        
        $talonInvoiceId = $paymentData['invoiceId'];
        
        if ($posTypeCode == '3' || $posTypeCode == '4') {  
            
        } else {
            
            if ($paymentData['invoiceId'] != '') {
                $params['META_DM_RECORD_MAP']['trgRecordId'] = $talonInvoiceId;
            } elseif (Input::isEmpty('basketInvoiceId') == false) {
                $params['META_DM_RECORD_MAP']['trgRecordId'] = Input::post('basketInvoiceId');
            }
        }

        $params['metaDmRowsDtl'] = $metaDmRowsDtl;
        
        if (array_key_exists('invInfoInvoiceNumber', $paymentData)) {
            
            $params['refInvoiceNumber']    = Input::param($paymentData['invInfoInvoiceNumber']);
            $params['refBookNumber']       = Input::param($paymentData['invInfoBookNumber']);
            $params['customerName']        = Input::param($paymentData['invInfoCustomerName']);
            $params['customerLastName']    = Input::param($paymentData['invInfoCustomerLastName']);
            $params['customerRegNumber']   = Input::param($paymentData['invInfoCustomerRegNumber']);
            $params['phoneNumber']         = Input::param($paymentData['invInfoPhoneNumber']);
            $params['email']               = issetParam($paymentData['invInfoEmail']);
            $params['description']         = Input::param($paymentData['invInfoTransactionValue']);
            $params['customerBankId']      = issetVar($paymentData['customerBankId']);
            $params['customerBankAccount'] = issetVar($paymentData['customerBankAccount']);
        }
        
        $params['SM_SALES_PAYMENT_DV'] = $paymentDtl;
         
        if ($sumTotal > 0 && $vatAmount > 0) {
            
            $totalAmount = $params['total'];
            
            if (issetParam($paymentData['cardDiscountType']) == '-' && $paymentData['cardDiscountPercentAmount'] > 0) {
                $vatAmount = $params['total'] - number_format($params['total'] / 1.1, 2, '.', '');
            } elseif ($cityTaxAmount && $sumVatAmount) {
                $vatAmount = $sumVatAmount;
            } else {
                $vatAmount = $totalAmount - number_format($totalAmount / 1.1, 2, '.', '');
            }
            if ($discountActivityAmount2 > 0) {
                $totalAmount22     = $totalAmount - $discountActivityAmount2;
                $totalAmount22     = $totalAmount22 < 0 ? 0 : $totalAmount22;
                $vatAmount = $totalAmount22 - number_format($totalAmount22 / 1.1, 2, '.', '');
            }            
            
            $params['total'] = $totalAmount;
            $params['vat']   = $vatAmount; 
        }        

        $lockerCode = '';
        
        if (isset($_POST['lockerId'])) {
            $lockInfo = explode('_', $_POST['lockerId']);
            $params['lockerId'] = $lockInfo[0];
            $lockerCode = $lockInfo[1];
        }
        
        /*
         * Give gift related by amount
         */
        $getPosCtrlParam = array(
            'storeId' => $storeId,
            'total' => $totalAmount,
        );
        
        $getPosCtrl = new Mdpos();
        $getPosCtrlResult = $getPosCtrl->giftByItemPaymentRender($getPosCtrlParam);
        
        $giftJsonPaymentParam = issetParam($paymentData['giftPaymentJson']);
        
        if ($getPosCtrlResult['gift'] && empty($giftJsonPaymentParam)) {
            $getPosCtrlResult['status'] = 'success';
            return $getPosCtrlResult;
        }

        if (isset($upointTotalPoint)) {
            $params['vat'] = ($totalAmount - $upointTotalPoint) - number_format(($totalAmount - $upointTotalPoint) / 1.1, 2, '.', '');
        }        

        $giftList .= $this->giftSavePayment($giftJsonPaymentParam, $params, $voucherDtl, $totalItemCount, $sumTotal, $stocks, $isVatCalc, '', $itemPrintRenderFncName, $itemPrintList, $storeWarehouseId, issetParam($dtlBonusPercent), $totalBonusAmount, $serviceDtl, $headerParams, $storeId);
         
        if (Input::isEmpty('returnInvoiceId') == false) {
            $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'SALES_RETURN_DV_002', $params);
        } else {
            $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'POS_SM_SALES_INVOICE_HEADER_001', $params);
        }
        
        if ($result['status'] == 'success') {
            
            $invoiceResult = $result['result'];
            $invoiceId     = $invoiceResult['id'];

            if ($voucher2Amount >= 0 && isset($paymentData['voucher2DtlAmount'])) {                
                $voucherDtlAmount    = $paymentData['voucher2DtlAmount'];
    
                foreach ($voucherDtlAmount as $v => $voucherAmountDtl) {
                    $voucherId           = $paymentData['voucher2DtlId'][$v];
                    $voucherTypeId       = $paymentData['voucher2TypeId'][$v];
                    $voucherSerialNumber = $paymentData['voucher2DtlSerialNumber'][$v];
                    $voucherAmountDtl = Number::decimal($voucherAmountDtl);
                    
                    if ($voucherId != '' && $voucherAmountDtl >= 0 && $voucherSerialNumber != '') {                        
                        $bonusOutParams = array(
                            'salesInvoiceId' => $invoiceId,
                            'description'  => 'Урьдчилсан борлуулалтаас Баримтын дугаар:' . $refNumber,
                            'LOY_PAYMENT_DTL_IN' => array(
                                'outAmt'  => $voucherAmountDtl,
                                'couponKeyId'  => $voucherId,
                            )
                        );
                        $this->ws->runSerializeResponse(self::$gfServiceAddress, 'LOY_PAYMENT_BOOK_OUT_BONUS', $bonusOutParams);                         
                    }
                }
            }            

            if ($bonusCardDiscountPercentAmount > 0) {
                $bonusInParams = array(
                    'salesInvoiceId' => $invoiceId,
                    'description'  => 'Худалдан авалтаас нэмэгдсэн бонус Баримтын дугаар:' . $refNumber,
                    'LOY_PAYMENT_DTL_IN' => array(
                        'membershipId' => $bonusCardMemberShipId, 
                        'inBonusAmt'   => $sumLineTotalBonusAmount ? $sumLineTotalBonusAmount : $bonusCardDiscountPercentAmount
                    )
                );
                $this->ws->runSerializeResponse(self::$gfServiceAddress, 'LOY_PAYMENT_BOOK_BONUS', $bonusInParams);
            }

            if ($bonusCardAmount > 0) {
                $bonusOutParams = array(
                    'salesInvoiceId' => $invoiceId,
                    'description'  => 'Бонусаас төлбөр хийв Баримтын дугаар:' . $refNumber,
                    'LOY_PAYMENT_DTL_IN' => array(
                        'membershipId' => $bonusCardMemberShipId, 
                        'outBonusAmt'  => $bonusCardAmount
                    )
                );
                $this->ws->runSerializeResponse(self::$gfServiceAddress, 'LOY_PAYMENT_BOOK_OUT_BONUS', $bonusOutParams);            
            }
            
            $bonusCardCustomerResult = self::createBonusCardCustomer(issetParam($paymentData['newCardCustomerJson']));
                
            if ($bonusCardCustomerResult['status'] != 'success') {
                
                if (!array_key_exists("id", $params)) {
                    self::deleteSalesInvoice($invoiceId, $bonusCardCustomerResult, $serviceCustomerId);
                }
                
                $response = array('status' => 'warning', 'message' => Lang::line('POS_0081') . ' (CARD). '.$bonusCardCustomerResult['message']);

                return $response;
            }

            $sdmDeliveryResult = self::createSdmDelivery($invoiceId, $headerParams, $paymentData, $deliveryDtl, $noDeliveryDtl);

            if ($sdmDeliveryResult['status'] != 'success') {
                
                if (!array_key_exists("id", $params)) {
                    self::deleteSalesInvoice($invoiceId, $bonusCardCustomerResult, $serviceCustomerId);
                }

                $response = array('status' => 'warning', 'message' => Lang::line('POS_0097') . ' '.$sdmDeliveryResult['message']);

                return $response;
            }
            
            if (Config::getFromCache('POS_PAY_LEFT_SIDE_SHOW_LEAD')) {
                self::createSejim($invoiceId, $headerParams, $paymentData);
            }

            $serviceResult = self::createToService($invoiceId, $headerParams, $paymentData, $serviceDtl);
            
            if ($serviceResult['status'] != 'success') {
                
                if (!array_key_exists("id", $params)) {
                    self::deleteSalesInvoice($invoiceId, $bonusCardCustomerResult, $serviceCustomerId);
                }

                $response = array('status' => 'warning', 'message' => Lang::line('POS_0098') . ' '.$serviceResult['message']);

                return $response;
            }
            
            if ($stocks == '' || $totalAmount == 0 || $totalAmount < 0) {
                $isPut = false;
                $billTitle = Lang::line('POS_0080');
            }
            
            $langCode       = Lang::getCode();
            $printType      = Config::getFromCache('CONFIG_POS_PRINT_TYPE');
            $sessionPosLogo = Mdpos::$posLogo;
            $posLogo        = ($sessionPosLogo ? $sessionPosLogo : 'pos-logo.png');
            $voucherContent = '';
            $dirPath        = '';
        
            if ($langCode != 'mn') {
                $dirPath = '/en';
            }
            
            if ($voucherDtl) {
                
                $voucherPath = '';
                
                if (Config::getFromCache('CONFIG_POS_VOUCHER_PATH')) {
                    $voucherPath = Config::getFromCache('CONFIG_POS_VOUCHER_PATH') . '/';
                }

                $voucherTemplate      = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.'/voucher/'.$voucherPath.'template1.html');
                $voucherTemplate2     = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.'/voucher/'.$voucherPath.'template2.html');
                $voucherCashTemplate  = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.'/voucher/'.$voucherPath.'cash.html');
                $voucherOtherTemplate = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.'/voucher/'.$voucherPath.'other.html');
                
                $activedVouchers = array();
                
                foreach ($voucherDtl as $voucherRow) {
                    
                    $getVoucker = self::getVouckerListModel($voucherRow['typeId'], $voucherRow['amount'], $voucherRow['percent'], $storeId);

                    if ($getVoucker) {

                        $voucherDurationDay = $getVoucker['durationday'];
                        $voucherExpireDate  = issetParam($getVoucker['expireddate']) ? $getVoucker['expireddate'] : Date::weekdayAfter('Y-m-d H:i:s', $currentDate, '+'.$voucherDurationDay.' days');
                        $voucherAmount = ($voucherRow['percentamount'] ? $voucherRow['percentamount'] : $getVoucker['amount']);
                        
                        $voucherReplacing = array(
                            '{voucherName}'         => $voucherRow['name'], 
                            '{voucherAmount}'       => self::posAmount($voucherAmount), 
                            '{voucherPureAmount}'   => $voucherRow['imageFile'], 
                            '{refNumber}'           => $refNumber,
                            '{voucherSerialNumber}' => $getVoucker['serialnumber'],
                            '{voucherYear}'         => Date::formatter($voucherExpireDate, 'Y'), 
                            '{voucherMonth}'        => Date::formatter($voucherExpireDate, 'm'), 
                            '{voucherDay}'          => Date::formatter($voucherExpireDate, 'd')
                        );
                        
                        if (($voucherRow['typeId'] == 1 || $voucherRow['typeId'] == 5) && !empty($voucherRow['imageFile'])) { 
                            
                            $voucherContent .= strtr($voucherOtherTemplate, $voucherReplacing);
                            
                        } elseif ($voucherRow['typeId'] == 6 && empty($voucherRow['imageFile'])) {
            
                            $voucherReplacing['{bankName}'] = self::getPosBankNameById(issetParam($params['customerBankId']));
                            $voucherReplacing['{bankAccountNumber}'] = issetParam($params['customerBankAccount']);
                            
                            $voucherContent .= strtr($voucherCashTemplate, $voucherReplacing);
                            
                        } else {
                            if (empty($voucherRow['imageFile'])) {
                                $voucherContent .= strtr($voucherTemplate2, $voucherReplacing);                                
                            } else {
                                $voucherContent .= strtr($voucherTemplate, $voucherReplacing);
                            }
                        }

                        $updateVoucherParams = array(
                            'id'                => $getVoucker['id'], 
                            'activationDate'    => $currentDate, 
                            'expiredDate'       => $voucherExpireDate, 
                            'durationDay'       => $voucherDurationDay, 
                            'amount'            => $voucherAmount, 
                            'percent'           => '', 
                            'activatedSalesInvoiceDtlId' => $invoiceResult['pos_sm_sales_invoice_detail'][$voucherRow['rowIndex']]['id']
                        );

                        self::updateVoucherByAmount($updateVoucherParams);
                        
                        $activedVouchers[] = $updateVoucherParams;
                        
                    } else {
                        
                        if (count($activedVouchers) > 0) {
                            foreach ($activedVouchers as $av => $activedVoucher) {
                                self::updateVoucherInactive($activedVoucher);
                            }
                        }
                        
                        if (!array_key_exists("id", $params)) {
                            self::deleteSalesInvoice($invoiceId, $bonusCardCustomerResult, $serviceCustomerId);
                        }
                        
                        $response = array(
                            'status' => 'warning', 
                            'message' => Lang::lineVar('POS_0099', array('vaucher' => number_format($voucherRow['amount'], 2, '.', ',')))
                        );

                        return $response;
                    }
                }
            }
            
            if ($couponKeyDtl) {
                
                $couponExpireCurrentDate = Date::currentDate('Y-m-d') . ' 23:59:59';
                        
                foreach ($couponKeyDtl as $couponKeyDtlRow) {
                    
                    if ($couponDurationDay = self::getCouponDurationDay($couponKeyDtlRow['couponKeyId'])) {
                        
                        $couponExpireDate  = Date::weekdayAfter('Y-m-d H:i:s', $couponExpireCurrentDate, '+'.$couponDurationDay.' days');

                        $updateCouponParams = array(
                            'id'                => $couponKeyDtlRow['couponKeyId'], 
                            'activationDate'    => $currentDate, 
                            'expiredDate'       => $couponExpireDate, 
                            'durationDay'       => $couponDurationDay, 
                            'activatedSalesInvoiceDtlId' => $invoiceResult['pos_sm_sales_invoice_detail'][$couponKeyDtlRow['rowIndex']]['id']
                        );

                        self::updateVoucher($updateCouponParams);
                    }
                }
            }
            
            /* Дотоод зардал үед */
            
            if ($isPut == false) { 
                
                $vatNumber       = Session::get($sPrefix.'vatNumber');
                $storeName       = Session::get($sPrefix.'storeName');
                $cashCode        = Session::get($sPrefix.'cashRegisterCode');
                $cashierName     = Session::get($sPrefix.'cashierName');
                $contactInfo     = Session::get($sPrefix.'posContactInfo');
                $topTitle        = Mdpos::$posHeaderName;
                $printCopies     = (int) $paymentData['posPrintCopies'];
                $billTitle       = (isset($billTitle) ? $billTitle : (Str::upper($this->lang->line('POS_0094')) === 'ДОТООД ЗАРДАЛ' ? 'ДОТООД ЗАРДАЛ' : 'ЭРХИЙН БИЧИГ'));
                $salesPersonCode = self::getSalesPersonCode($itemData);
                
                $templateContent = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.$dirPath.'/local/expense.html');
                $templateContentEmail = $templateContent;
                
                $discountPart    = '';

                if (Input::isEmpty('multipleLockerId') == false) {
                    $lockerIds = Input::post('multipleLockerId');
                    parse_str($lockerIds, $lockerIdsArray);
                    $lockerCode = '';

                    foreach ($lockerIdsArray['multipleLockerId'] as $locker) {
                        $lockInfo = explode('_', $locker);
                        $lockerCode .= $lockInfo[1] . ',';
                    } 
                    
                    $this->actionMultipleLocker($lockerIds, $invoiceId);
                }                
                
                if ($discountAmount > 0 || $discountDetail) {
                    
                    $discountPartTemplate = self::discountPartTemplate($voucherSerialNumber);
                    
                    $discountPart = str_replace(
                        array('{totalDiscount}',                '{discount-detail}'), 
                        array(self::posAmount($discountAmount),  $discountDetail), 
                        $discountPartTemplate
                    );
                }
                
                if ($voucherUsedDtl) {
                    
                    foreach ($voucherUsedDtl as $voucherUsedRow) {
                        
                        $updateVoucherUsedParams = array(
                            'id'                        => $voucherUsedRow['id'], 
                            'usedDate'                  => $currentDate, 
                            'usedStoreId'               => $storeId, 
                            'usedCashierId'             => $cashierId, 
                            'usedSalesInvoiceBookId'    => $invoiceId 
                        );

                        self::updateVoucherUsed($updateVoucherUsedParams);
                    }
                }
                
                $replacing = array(
                    '{poslogo}'         => $posLogo,
                    '{companyName}'     => $topTitle,
                    '{title}'           => $billTitle, 
                    '{vatNumber}'       => Mdpos::$posVatPayerNo,
                    '{vatName}'         => Mdpos::$posVatPayerName,
                    '{contactInfo}'     => $contactInfo,
                    '{date}'            => Date::formatter($currentDate, 'Y/m/d'),
                    '{time}'            => Date::formatter($currentDate, 'H:i:s'),
                    '{refNumber}'       => $refNumber,
                    '{invoiceNumber}'   => $invoiceNumber,
                    '{storeName}'       => $storeName,
                    '{cashierName}'     => $cashierName,
                    '{cashCode}'        => $cashCode, 
                    '{salesPersonCode}' => $salesPersonCode, 
                    '{salesWaiter}'     => Input::post('waiterText', ''), 
                    '{orderTime}'       => Input::post('posEshopOrderTime', ''), 
                    '{itemList}'        => $itemPrintList,
                    '{totalAmount}'     => self::posAmount($printTotalAmount),
                    '{payAmount}'       => self::posAmount($totalAmount),
                    '{vatAmount}'       => self::posAmount($vatAmount),
                    '{discountPart}'    => $discountPart,
                    '{upointPart}'      => $upointPart,
                    '{totalItemCount}'  => self::posAmount($totalItemCount), 
                    '{payment-detail}'  => $paymentDetail,
                    '{lockerCode}'      => self::printLockerCode($lockerCode),
                    '{serialText}'      => self::printSerialText($serialText),
                    '{info-ipterminal}' => '',
                    '{showCustomerName}'=> $showCustomerName
                );
                
                $promotionContent = self::getBillPromotionModel($invoiceId);

                $templateContent = str_replace('{promotion}', $promotionContent, $templateContent);                
                
                $internalContent = strtr($templateContent, $replacing);
                        
                if ($printCopies > 1) {
                    $internalContent = str_repeat($internalContent, $printCopies);
                }
                
                $cssContent       = Mdpos::getPrintCss();
                $newInvoiceNumber = self::getBillNumModel();
                $res = array('status' => 'success', 'css' => $cssContent, 'printData' => $internalContent, 'billNumber' => $newInvoiceNumber, 'invoiceId' => $invoiceId);
                
                $getLiftPrint = $this->getLiftPrintModel($invoiceId);
                
                if ($getLiftPrint) {
                    $res['liftdata'] = $getLiftPrint;
                }                
                
                self::updateTalonInvoiceUsed($talonInvoiceId);
                if (Input::isEmpty('basketInvoiceId') == false) {
                    $res['basketCount'] = $this->deleteBasketInvoiceModel();
                }                
                
                return $res;
            }
            
            $vatNumber      = Session::get($sPrefix.'vatNumber');
            $districtCode   = Session::get($sPrefix.'posDistrictCode');
            $cashCode       = Session::get($sPrefix.'cashRegisterCode');
            $isNotSendVatsp = (Session::get($sPrefix.'isNotSendVatsp') == '1' ? true : false);
            $reportMonth    = '';
            $customerNo     = '';
            $orgRegNumber   = '';
            $orgName        = '';
            $billIdSuffix   = '';
            $taxType        = 1;
            
            if ($isNotSendVatsp == false) {
            
                if ($posBillType == 'person') {

                    $billType   = 1;
                    $title      = Lang::line('POS_0100');

                } elseif ($posBillType == 'organization') {

                    $billType       = 3;
                    $title          = Lang::line('POS_0101');

                    $orgRegNumber   = Str::upper(Input::param($paymentData['orgRegNumber']));
                    $customerNo     = self::convertCyrillicMongolia($orgRegNumber); //'0000039';
                    $orgName        = $paymentData['orgName'];
                    $taxType        = $isVatCalc ? 1 : 2;

                } else {
                    $billType       = 5;
                    $title          = Lang::line('POS_0102');
                }

                if (Config::getFromCache('CONFIG_POS_HEALTHRECIPE')) {

                    $isReceiptNumber = Input::post('isReceiptNumber');

                    if ($isReceiptNumber == 'true' && isset($_POST['drugPrescription'])) {

                        $prescriptionArr = $_POST['drugPrescription'];
                        $billIdSuffix = $prescriptionArr['receiptNumber'];
                    }
                }

                $posApiCashAmount = $cashAmount - $changeAmount + $totalBonusAmount;
                $posApiNonCashAmount = $nonCashAmount - $totalBonusAmount;

                if ($posApiNonCashAmount < 0) {
                    $posApiCashAmount = $posApiCashAmount - ($posApiNonCashAmount * -1);
                    $posApiNonCashAmount = 0;
                }

                if ($posApiCashAmount < 0) {
                    $posApiCashAmount = 0;
                    $posApiNonCashAmount = $posApiNonCashAmount - ($posApiCashAmount * -1);
                }

                if ($totalAmount < $posApiNonCashAmount) {
                    $posApiNonCashAmount = $posApiNonCashAmount - $changeAmount;
                }

                if ($isPackageItem) {
                    $merchantPackage = '';
                    
                    $timeMin = Date::currentDate('H');
                    if ($merchantHeader) {
                        foreach ($merchantHeader as $merchant) {
                            $merchantPackage .= "{
                                'amount': '" . sprintf("%.2f", ${"merchantTotalAmount".$merchant}) . "',
                                'vat': '" . sprintf("%.2f", ${"merchantTotalAmount".$merchant} - number_format(${"merchantTotalAmount".$merchant} / 1.1, 2, '.', '')) . "',
                                'cashAmount': '" . sprintf("%.2f", ${"merchantTotalAmount".$merchant}) . "',
                                'nonCashAmount': '0.00',
                                'cityTax': '" . sprintf("%.2f", ${"merchantTotalCityTax".$merchant}) . "',
                                'districtCode': '" . $districtCode . "',
                                'posNo': '',
                                'reportMonth': '" . $reportMonth . "',
                                'customerNo': '" . $customerNo . "',
                                'billType': '" . $billType . "',
                                'taxType': '" . $taxType . "',
                                'billIdSuffix': '',
                                'returnBillId': '',
                                'internalId': '" . ${"merchantInternalId".$merchant} . "',
                                'registerNo': '" . ${"merchantRegisterNo".$merchant} . "',
                                'stocks': [
                                    " . rtrim(${"stocks".$merchant}, ', ') . "
                                ]
                            }, ";
                        }
                    }
                    
                    $jsonParam = "{
                        'group': true,
                        'vat': '" . sprintf("%.2f", $vatAmount) . "',
                        'amount': '" . sprintf("%.2f", $totalAmount) . "',
                        'billType': '" . $billType . "',
                        'billIdSuffix': '" . $timeMin . str_shuffle(str_shuffle(substr((time() * rand()), 0, 4))) . "',
                        'posNo': '" . $cashCode . "',
                        'returnBillId': '" . (Input::isEmpty('returnInvoiceId') == false ? Input::post('returnInvoiceBillId') : '') . "',
                        'bills': [
                            " . rtrim($merchantPackage, ', ') . "
                        ]
                    }";
                } else {

                    $taxTotalAmount = $totalAmount;
                    if (isset($upointTotalPoint)) {
                        $totalAmount = $totalAmount - $upointTotalPoint;
                        $taxTotalAmount = $totalAmount;
                        $vatAmount = $taxTotalAmount - number_format($taxTotalAmount / 1.1, 2, '.', '');
                    }

                    $jsonParam = "{
                        'amount': '" . sprintf("%.2f", $taxTotalAmount) . "',
                        'vat': '" . sprintf("%.2f", $vatAmount) . "',
                        'cashAmount': '" . sprintf("%.2f", $taxTotalAmount) . "',
                        'nonCashAmount': '0.00',
                        'cityTax': '" . sprintf("%.2f", $cityTaxAmount) . "',
                        'districtCode': '" . $districtCode . "',
                        'posNo': '" . $cashCode . "',
                        'reportMonth': '" . $reportMonth . "',
                        'customerNo': '" . $customerNo . "',
                        'billType': '" . $billType . "',
                        'taxType': '" . $taxType . "',
                        'billIdSuffix': '" . $billIdSuffix . "',
                        'returnBillId': '" . (Input::isEmpty('returnInvoiceId') == false ? Input::post('returnInvoiceBillId') : '') . "',
                        'stocks': [
                            " . rtrim($stocks, ', ') . "
                        ]
                    }";
                }

                $jsonParam = Str::remove_doublewhitespace(Str::removeNL($jsonParam));

                if ($isUserPosV3) {                                

                    $taxInvParam = array(
                        'id' => $invoiceId,
                        'invoiceId' => $basketInvoiceId
                    );
                    
                    if (isset($paymentData['reportmonth'])) {
                        $prevMonth = date('Y-m', strtotime(date('Y-m')." -1 month")) . '-25';
                        $taxInvParam['reportMonth'] = $prevMonth;
                    }
                    
                    $posApiArray = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'posMainSalesInvoiceSendTax', $taxInvParam);                
                    
                    if (isset($posApiArray['result']) && $posApiArray['result']['status'] === 'SUCCESS') {
                        $posApiArray = $posApiArray['result'];
                        $billId      = issetParam($posApiArray['id']);                                            
                        $posApiArray['qrData'] = $posApiArray['qrdata'];
                        $posApiArray['merchantId'] = '';
                        $posApiArray['warningMsg'] = '';
                        $posApiArray['success'] = $posApiArray['status'];
                    } else {
                        $billId = null;
                        $posApiArray['warningMsg'] = issetParam($posApiArray['result']['message']) ? $posApiArray['result']['message'] : 'posMainSalesInvoiceSendTax хариу ирсэнгүй!';
                    }
                } else {
                    $posApiArray = self::posApiFunction($jsonParam);
                    $billId      = isset($posApiArray['billId']) ? $posApiArray['billId'] : null;                
                }                
                
            } else {
                
                $billId = '1';
                $posApiArray = array('date' => null);
            }
            
            if ($billId) {
                
                $storeName       = Session::get($sPrefix.'storeName');
                $cashierName     = Session::get($sPrefix.'cashierName');
                $contactInfo     = Session::get($sPrefix.'posContactInfo');
                $topTitle        = Mdpos::$posHeaderName;
                $messageText     = Session::get($sPrefix.'messageText') ? Session::get($sPrefix.'messageText') : '';
                $printCopies     = (int) $paymentData['posPrintCopies'];
                $salesPersonCode = self::getSalesPersonCode($itemData);
                $putDate         = $posApiArray['date'] ? $posApiArray['date'] : $currentDate;
                
                $discountPart    = '';
                $loyaltyPart     = '';                

                if (Input::isEmpty('multipleLockerId') == false) {
                    
                    $lockerIds = Input::post('multipleLockerId');
                    parse_str($lockerIds, $lockerIdsArray);
                    $lockerCode = '';

                    foreach ($lockerIdsArray['multipleLockerId'] as $locker) {
                        $lockInfo = explode('_', $locker);
                        $lockerCode .= $lockInfo[1] . ',';
                    }                    
                    
                    $this->actionMultipleLocker($lockerIds, $invoiceId);
                }
                
                if ($discountAmount > 0 || $discountDetail) {
                    
                    $discountPartTemplate = self::discountPartTemplate($voucherSerialNumber);
                    
                    $discountPart = str_replace(
                        array('{totalDiscount}',                '{discount-detail}'), 
                        array(self::posAmount($discountAmount),  $discountDetail), 
                        $discountPartTemplate
                    );
                }
                
                if ($voucherUsedDtl) {
                    
                    foreach ($voucherUsedDtl as $voucherUsedRow) {
                        
                        $updateVoucherUsedParams = array(
                            'id'                        => $voucherUsedRow['id'], 
                            'usedDate'                  => $currentDate, 
                            'usedStoreId'               => $storeId, 
                            'usedCashierId'             => $cashierId, 
                            'usedSalesInvoiceBookId'    => $invoiceId 
                        );

                        self::updateVoucherUsed($updateVoucherUsedParams);
                    }
                }
                
                /* Flower */
                if (defined('CONFIG_POS_ADDITIONAL_VOUCHER') && CONFIG_POS_ADDITIONAL_VOUCHER) {

                    $additionalVoucherList = self::getAdditionalVoucherListModel($storeId, $totalAmount);

                    if ($additionalVoucherList) {

                        $addiVoucherTemplate = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.'/voucher/template1.html');

                        foreach ($additionalVoucherList as $additionalVoucherRow) {

                            $voucherDurationDay = $additionalVoucherRow['durationday'];
                            $voucherExpireDate  = Date::weekdayAfter('Y-m-d H:i:s', $currentDate, '+'.$voucherDurationDay.' days');

                            $voucherReplacing = array(
                                '{voucherName}'         => 'ВАУЧЕР', 
                                '{voucherAmount}'       => self::posAmount($additionalVoucherRow['amount']), 
                                '{voucherPureAmount}'   => $additionalVoucherRow['amount'],
                                '{voucherSerialNumber}' => $additionalVoucherRow['serialnumber'],
                                '{refNumber}'           => $refNumber,
                                '{voucherYear}'         => Date::formatter($voucherExpireDate, 'Y'), 
                                '{voucherMonth}'        => Date::formatter($voucherExpireDate, 'm'), 
                                '{voucherDay}'          => Date::formatter($voucherExpireDate, 'd')
                            );

                            $voucherContent .= strtr($addiVoucherTemplate, $voucherReplacing);

                            if (isset($additionalVoucherParams)) {

                                $additionalVoucherParams[] = array(
                                    'id'                => $additionalVoucherRow['id'], 
                                    'activationDate'    => $currentDate, 
                                    'expiredDate'       => $voucherExpireDate, 
                                    'durationDay'       => $voucherDurationDay, 
                                    'activatedSalesInvoiceId' => $invoiceId
                                );

                            } else {

                                $additionalVoucherParams = array(
                                    array(
                                        'id'                => $additionalVoucherRow['id'], 
                                        'activationDate'    => $currentDate, 
                                        'expiredDate'       => $voucherExpireDate, 
                                        'durationDay'       => $voucherDurationDay, 
                                        'activatedSalesInvoiceId' => $invoiceId
                                    )
                                );
                            }
                        }
                    }
                }
                
                if (isset($additionalVoucherParams)) {
                    
                    self::updateAdditionalVoucherActived($additionalVoucherParams);
                }
                
                if (defined('CONFIG_POS_AUTO_GENERATE_VAUCHER') && CONFIG_POS_AUTO_GENERATE_VAUCHER && $generateVaucherAmt > 0) {
                    
                    $voucherContent .= self::posAutoGenerateVaucher($invoiceId, $generateVaucherAmt, $printType);
                }
                
                if (Config::getFromCache('CONFIG_POS_GIFT')) {
                    
                    $voucherContent .= self::posAddionalGift($refNumber, $paymentData, $printType);
                }
                
                if ($isNotSendVatsp == false) {
                    
                    if ($isPackageItem) {
                        $merchantItems = Arr::groupByArray($merchantItems, 'registerNo');

                        if ($posApiArray['bills']) {
                            $itemPrintList = '';
                            $firstPackage = true;

                            foreach ($posApiArray['bills'] as $bkey => $bill) {                                                                
                                
                                $ebillNo = $bill['registerNo'];
                                
                                if (isset($merchantItems[$ebillNo])) {
                                    
                                    $itemPrintList .= self::generatePackageItemRow(array(), $bill['billId'], $firstPackage);
                                    $firstPackage = false;
                                    
                                    foreach ($merchantItems[$ebillNo]['rows'] as $merRow) {
                                        $itemPrintList .= self::generatePackageItemRow($merRow);
                                    }
                                    
                                    $billResultParams = array(
                                        'BILL_ID'          => $bill['billId'], 
                                        'SALES_INVOICE_ID' => $invoiceId, 
                                        'MERCHANT_ID'      => isset($merchantRegisters[$ebillNo]) ? $merchantRegisters[$ebillNo] : $ebillNo, 
                                        'VAT_DATE'         => $putDate, 
                                        'SUCCESS'          => '', 
                                        'WARNING_MSG'      => '',  
                                        'SEND_JSON'        => '', 
                                        'STORE_ID'         => $storeId, 
                                        'CUSTOMER_NUMBER'  => '', 
                                        'CUSTOMER_NAME'    => ''
                                    );
                                    self::createBillResultData($billResultParams); 
                                }
                            }
                        }
                    }                    
                
                    if ($billType == 1) { // Хувь хүн

                        $templateContent   = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.$dirPath.'/person/single.html');
                        $qrLotteryTemplate = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.$dirPath.'/person/qrcode-lottery.html');

                        if (isset($upointTotalPoint) && !$isPackageItem) {
                            $qrLotteryTemplate = str_replace('{payAmount}', self::posAmount($totalAmount), $qrLotteryTemplate);
                        }

                        $promotionContent = self::getBillPromotionModel($invoiceId);

                        $templateContent = str_replace('{promotion}', $promotionContent, $templateContent);

                        $templateContent = str_replace('{qrCodeLottery}', $qrLotteryTemplate, $templateContent);

                        $lotteryContent = self::printLotteryPriceInterval($invoiceId, $printType, issetParam($paymentData['invInfoPhoneNumber']), issetVar($paymentData['invInfoCustomerLastName']), issetVar($paymentData['invInfoCustomerName']));

                        $templateContent = str_replace('{lotterypart}', $lotteryContent, $templateContent);

                        $lottery         = $posApiArray['lottery'];
                        $qrData          = $posApiArray['qrData'];

                        $replacing = array(
                            '{poslogo}'         => $posLogo,
                            '{companyName}'     => $topTitle,
                            '{title}'           => '', 
                            '{vatNumber}'       => Mdpos::$posVatPayerNo,
                            '{vatName}'         => Mdpos::$posVatPayerName,
                            '{contactInfo}'     => $contactInfo,
                            '{ddtd}'            => $billId,
                            '{date}'            => Date::formatter($putDate, 'Y/m/d'),
                            '{time}'            => Date::formatter($putDate, 'H:i:s'),
                            '{refNumber}'       => $refNumber,
                            '{invoiceNumber}'   => $invoiceNumber,
                            '{storeName}'       => $storeName,
                            '{cashierName}'     => $cashierName,
                            '{cashCode}'        => $cashCode, 
                            '{salesPersonCode}' => $salesPersonCode, 
                            '{salesWaiter}'     => Input::post('waiterText', ''),
                            '{orderTime}'       => Input::post('posEshopOrderTime', ''), 
                            '{itemList}'        => $itemPrintList,
                            '{totalAmount}'     => self::posAmount($printTotalAmount - $discountActivityAmount2),
                            '{payAmount}'       => self::posAmount(isset($upointTotalPoint) && !$isPackageItem ? $totalAmount + $upointTotalPoint : $totalAmount - $discountActivityAmount2),
                            '{vatAmount}'       => self::posAmount($vatAmount),
                            '{discountPart}'    => $discountPart,
                            '{lottery}'         => $lottery,
                            '{qrCode}'          => self::getQrCodeImg($qrData),
                            '{giftList}'        => self::giftTableRender($giftList), 
                            '{upointPart}'      => $upointPart, 
                            '{totalItemCount}'  => self::posAmount($totalItemCount), 

                            '{bonusCardNumber}'         => $bonusCardNumber, 
                            '{bonusCardDiscountPercent}'=> $bonusCardDiscountPercent,
                            '{bonusCardBeginAmount}'    => self::posAmount($bonusCardBeginAmount), 
                            '{bonusCardDiffAmount}'     => self::posAmount($bonusCardAmount), 
                            '{bonusCardPlusAmount}'     => self::posAmount($bonusCardDiscountPercentAmount), 
                            '{bonusCardEndAmount}'      => self::posAmount($bonusCardEndAmount), 
                            '{payment-detail}'          => $paymentDetail,
                            '{lockerCode}'              => self::printLockerCode($lockerCode),
                            '{serialText}'              => self::printSerialText($serialText),
                            '{localPhoneNumber}'        => self::printLocalCustomerPhone(issetParam($paymentData['localCustomerPhone'])),
                            '{localmargin}'             => Config::getFromCacheDefault('CONFIG_POS_LOCAL_BILL_MARGIN', null, '0'),
                            '{info-ipterminal}'         => $infoIpTerminal,
                            '{messageText}'             => $messageText,
                            '{showCustomerName}'        => $showCustomerName
                        );
                        /**
                         * Fix Qrcode set header
                         */
                        header('Content-Type: text/html');                        

                        $templateContent .= $voucherContent;
                        
                        if ($itemPrintCopiesLast) {
                            
                            $printCopies = $itemPrintCopiesLast;
                            
                        } elseif (Config::getFromCache('CONFIG_POS_BANKCARD_COPIES_COUNT') 
                            && isset($isBankCardPaid) && $printCopies < Config::getFromCache('CONFIG_POS_BANKCARD_COPIES_COUNT')) {

                            $printCopies = Config::getFromCache('CONFIG_POS_BANKCARD_COPIES_COUNT');
                        }
                        $templateContentEmail = $templateContent;
                            
                        if ($printCopies) {

                            $internalContent = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.$dirPath.'/person/internal.html');

                            $internalContent = str_replace('{title}', Lang::line('POS_0103'), $internalContent);
                            $internalContent = str_replace('{lotterypart}', '', $internalContent);
                            $internalContent = str_replace('{info-ipterminal}', $infoIpTerminal, $internalContent);

                            $internalContent = strtr($internalContent, $replacing);
                                
                            if ($printCopies > 1) {
                                $internalContent = str_repeat($internalContent, $printCopies);
                            }

                            $templateContent .= $internalContent;
                        }

                    } elseif ($billType == 3) { // Байгууллага

                        $templateContent  = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.$dirPath.'/organization/single.html');

                        $promotionContent = self::getBillPromotionModel($invoiceId);
                        $templateContent  = str_replace('{promotion}', $promotionContent, $templateContent);

                        $lotteryContent   = self::printLotteryPriceInterval($invoiceId, $printType, issetParam($paymentData['invInfoPhoneNumber']), issetVar($paymentData['invInfoCustomerLastName']), issetVar($paymentData['invInfoCustomerName']));

                        $templateContent  = str_replace('{lotterypart}', $lotteryContent, $templateContent);

                        $qrData           = $posApiArray['qrData'];

                        $replacing = array(
                            '{poslogo}'         => $posLogo,
                            '{companyName}'     => $topTitle,
                            '{title}'           => '', 
                            '{vatNumber}'       => Mdpos::$posVatPayerNo,
                            '{vatName}'         => Mdpos::$posVatPayerName,
                            '{contactInfo}'     => $contactInfo,
                            '{ddtd}'            => $billId,
                            '{date}'            => Date::formatter($putDate, 'Y/m/d'),
                            '{time}'            => Date::formatter($putDate, 'H:i:s'),
                            '{refNumber}'       => $refNumber,
                            '{invoiceNumber}'   => $invoiceNumber,
                            '{storeName}'       => $storeName,
                            '{cashierName}'     => $cashierName,
                            '{cashCode}'        => $cashCode, 
                            '{salesPersonCode}' => $salesPersonCode, 
                            '{salesWaiter}'     => Input::post('waiterText', ''),
                            '{orderTime}'       => Input::post('posEshopOrderTime', ''), 
                            '{itemList}'        => $itemPrintList,
                            '{totalAmount}'     => self::posAmount($printTotalAmount - $discountActivityAmount2),
                            '{payAmount}'       => self::posAmount(isset($upointTotalPoint) && !$isPackageItem ? $totalAmount + $upointTotalPoint : $totalAmount - $discountActivityAmount2),
                            '{vatAmount}'       => self::posAmount($vatAmount),
                            '{discountPart}'    => $discountPart,
                            '{customerNumber}'  => $orgRegNumber, 
                            '{customerName}'    => $orgName, 
                            '{qrCode}'          => self::getQrCodeImg($qrData),
                            '{giftList}'        => self::giftTableRender($giftList), 
                            '{upointPart}'      => $upointPart,
                            '{totalItemCount}'  => self::posAmount($totalItemCount), 

                            '{bonusCardNumber}'         => $bonusCardNumber, 
                            '{bonusCardDiscountPercent}'=> $bonusCardDiscountPercent,
                            '{bonusCardBeginAmount}'    => self::posAmount($bonusCardBeginAmount), 
                            '{bonusCardDiffAmount}'     => self::posAmount($bonusCardAmount), 
                            '{bonusCardPlusAmount}'     => self::posAmount($bonusCardDiscountPercentAmount), 
                            '{bonusCardEndAmount}'      => self::posAmount($bonusCardEndAmount), 
                            '{lockerCode}'              => self::printLockerCode($lockerCode),
                            '{serialText}'              => self::printSerialText($serialText),
                            '{localPhoneNumber}'        => self::printLocalCustomerPhone(issetParam($paymentData['localCustomerPhone'])),
                            '{localmargin}'             => Config::getFromCacheDefault('CONFIG_POS_LOCAL_BILL_MARGIN', null, '0'),                            
                            '{payment-detail}'          => $paymentDetail,
                            '{info-ipterminal}'         => $infoIpTerminal,
                            '{messageText}'             => $messageText,
                            '{showCustomerName}'        => $showCustomerName
                        );
                        /**
                         * Fix Qrcode set header
                         */
                        //header('Content-Type: text/html');                        

                        $templateContent .= $voucherContent;
                        
                        if ($itemPrintCopiesLast) {
                            
                            $printCopies = $itemPrintCopiesLast;
                            
                        } elseif (Config::getFromCache('CONFIG_POS_BANKCARD_COPIES_COUNT') 
                            && isset($isBankCardPaid) && $printCopies < Config::getFromCache('CONFIG_POS_BANKCARD_COPIES_COUNT')) {

                            $printCopies = Config::getFromCache('CONFIG_POS_BANKCARD_COPIES_COUNT');
                        }
                        $templateContentEmail = $templateContent;
                            
                        if ($printCopies) {

                            $internalContent = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.$dirPath.'/organization/internal.html');

                            $internalContent = str_replace('{title}', Lang::line('POS_0103'), $internalContent);
                            $internalContent = str_replace('{lotterypart}', '', $internalContent);
                            $internalContent = str_replace('{info-ipterminal}', $infoIpTerminal, $internalContent);

                            $internalContent = strtr($internalContent, $replacing);
                            
                            if ($printCopies > 1) {
                                $internalContent = str_repeat($internalContent, $printCopies);
                            }

                            $templateContent .= $internalContent;
                        }
                    }

                    $templateContent = strtr($templateContent, $replacing);
                    $templateContentEmail = strtr($templateContentEmail, $replacing);

                    $billResultParams = array(
                        'BILL_ID'          => $billId, 
                        'SALES_INVOICE_ID' => $invoiceId, 
                        'MERCHANT_ID'      => $posApiArray['merchantId'], 
                        'VAT_DATE'         => $putDate, 
                        'SUCCESS'          => $posApiArray['success'], 
                        'WARNING_MSG'      => $posApiArray['warningMsg'],  
                        'SEND_JSON'        => $jsonParam, 
                        'STORE_ID'         => $storeId, 
                        'CUSTOMER_NUMBER'  => $orgRegNumber, 
                        'CUSTOMER_NAME'    => $orgName,
                        'TOTAL'            => isset($taxTotalAmount) ? $taxTotalAmount : $totalAmount
                    );
                    if ($isPackageItem) {
                        $billResultParams['IS_ROOT_PACKAGE'] = '1';
                    }

                    self::createBillResultData($billResultParams); 

                    if ($billIdSuffix != '') {

                        $recipeResult     = self::createPrescription($invoiceId, $billId, $posApiArray['date'], $totalAmount, $vatAmount, $emdAmount, $paramsDtl);
                        $recipeHeaderInfo = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/pharmacy/person/recipeHeaderInfo.html');

                        $recipeReplacing = array(
                            '{recipeCipherCode}'    => $recipeResult['cipherCode'], 
                            '{recipePatientName}'   => $recipeResult['patientLastName'].' '.$recipeResult['patientFirstName'], 
                            '{recipePatientRegNo}'  => $recipeResult['patientRegNo'], 
                            '{recipeReceiptNumber}' => $recipeResult['receiptNumber']
                        );

                        $recipeHeaderInfo = strtr($recipeHeaderInfo, $recipeReplacing);
                        $templateContent  = str_replace('{recipeHeaderInfo}', $recipeHeaderInfo, $templateContent);

                    } elseif (isset($isReceiptNumber)) {

                        $templateContent = str_replace('{recipeHeaderInfo}', '', $templateContent);
                    }

                    if (isset($candyUserCheck)) {
                        $loyaltyPart = self::candyUserToSend($invoiceId, $printType, $candyUserCheck);
                    }

                    if (isset($redPointUserCheck)) {
                        $loyaltyPart = self::redPointUserToSend($invoiceId, $printType, $redPointUserCheck, $totalAmount);
                    }

                    $discountCard = '';
                    if ($bonusCardAmount > 0 || $bonusCardMemberShipId != '') {
                        $discountCard = strtr(self::discountCardTemplate(), $replacing);
                    }

                    $templateContent = str_replace('{discountCard}', $discountCard, $templateContent);
                    $templateContent = str_replace('{loyaltyPart}', $loyaltyPart, $templateContent);
                    $cssContent      = Mdpos::getPrintCss();

                    self::updateSalesInvoiceHeaderCardId($invoiceId, $bonusCardCustomerResult);

                    if ($basketInvoiceId && $dareporttemplateid = Config::getFromCache('POS_BILL_PRINT_ADD_DELIVERY_ADDRESS')) {
                        $rrrHtml .= '<div>'.(new Mdtemplate())->getTemplateByArguments($dareporttemplateid, 'selfDvId', array('salesorderid' => $basketInvoiceId)).'</div>';
                        $templateContent = str_replace('{renderreporttemplate}', $rrrHtml, $templateContent);
                    } else {
                        $templateContent = str_replace('{renderreporttemplate}', '', $templateContent);
                    }
                
                } else {
                    
                    $templateContent = (new Mdtemplate())->getTemplateByArguments('1553591243602142', '1522115383994585', array('id' => $invoiceId));
                    $templateContent .= '<div style="page-break-after: always;"></div>';
                    
                    $templateContent = str_repeat($templateContent, $printCopies);
                    
                    $templateContent .= $voucherContent;
                    
                    $cssContent      = file_get_contents('assets/custom/css/print/reportPrint.css');
                }
                
                self::updateTalonInvoiceUsed($talonInvoiceId);
                self::updateBankBillingUsed($atBankBillingIds);
                
                $newInvoiceNumber = self::getBillNumModel();
                
                $response = array('status' => 'success', 'billNumber' => $newInvoiceNumber);
                
                if (Input::isEmpty('basketInvoiceId') == false) {
                    $response['basketCount'] = $this->deleteBasketInvoiceModel();
                }
                
                if ($posBillType == 'organization') {
                    self::posOrganizationToCrm($orgRegNumber, $orgName);
                }
                
                $response['css']        = $cssContent;
                $response['invoiceId']  = $invoiceId;
                $response['printData']  = $templateContent;
                
                if (isset($paymentData['isLotterySendEmail'])) {
                    Mdtemplate::$mergeResponseData = array(
                        'lotterynumber' => $posApiArray['lottery'],
                        'qrcode' => self::getQrCodeImg($posApiArray['qrData'])
                    );                    
                    $this->sendMailModel($paymentData['lotteryEmail'], $invoiceId);
                }
                
                $getLiftPrint = $this->getLiftPrintModel($invoiceId);
                
                if ($getLiftPrint) {
                    $response['liftdata'] = $getLiftPrint;
                }
                
            } else {
                
                if (isset($sdmDeliveryResult['deliveryBookIds']) && !empty($sdmDeliveryResult['deliveryBookIds'])) {
                    
                    foreach ($sdmDeliveryResult['deliveryBookIds'] as $dk => $deliveryBookId) {
                        $this->ws->runResponse(self::$gfServiceAddress, 'POS_SDM_DELIVERY_005', array('id' => $deliveryBookId));
                    }
                }
                
                if (!array_key_exists("id", $params)) {
                    self::deleteSalesInvoice($invoiceId, $bonusCardCustomerResult, $serviceCustomerId);
                }
                
                $warningMsg = isset($posApiArray['warningMsg']) ? $posApiArray['warningMsg'] : (isset($posApiArray['message']) ? $posApiArray['message'] : 'PosApi алдаа: NULL');
                
                if ($isUserPosV3) {
                    $response = array('status' => 'warning', 'message' => $warningMsg);
                } else {
                    $response = array('status' => 'warning', 'message' => self::apiStringReplace($warningMsg, true));
                }
            }

        } else {
            $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
        
        return $response;
    }

    public function getSDMSalesOrderDetailsModel($id = '', $itemId = '', $customerId = '', $guestName = '') {
        $param = array(
            'systemMetaGroupId' => '1652850365641707',
            'showQuery' => 0,
            'ignorePermission' => 1, 
            'criteria' => array(
                'salesOrderId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $id 
                    )
                ),
                'itemId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $itemId 
                    )
                ),
                'customerId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $customerId 
                    )
                ),                    
                'guestName' => array(
                    array(
                        'operator' => '=',
                        'operand' => $guestName 
                    )
                )                    
            )
        );        

        if (!empty($itemId) && empty($customerId)) {
            $param['criteria']['customerId'] = array(
                array(
                    'operator' => 'IS NULL',
                    'operand' => '' 
                )
            );
        }
        
        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result']) && isset($data['result'][0])) {
            unset($data['result']['aggregatecolumns']);
            return $data['result'];
        }
        
        return null;
    }
    
    public function deleteBasketInvoiceModel() {

        $prm = array('id' => Input::post('basketInvoiceId'));
        $this->ws->runSerializeResponse(self::$gfServiceAddress, 'SDM_ORDER_BOOK_UPDATE_1_001', $prm);
        
        return self::getBasketOrderBookCountModel();
    }
    
    public function posOrganizationToCrm($orgRegNumber, $orgName) {
        
        if (Config::getFromCache('isPosOrganizationToCrm')) {
            
            $prm = array(
                'positionName' => $orgRegNumber, 
                'customerName' => $orgName
            );

            $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'POS_TTD_CUSTOMER_001', $prm);

            if ($result['status'] == 'success') {
                $result = array('status' => $result['status']);
            } else {
                $result = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
                @file_put_contents('log/posOrgToCrm.log', $result['message'] . "\n", FILE_APPEND);
            }

            return $result;
        }
        
        return true;
    }
    
    public function getCouponDurationDay($couponKeyId) {

        $param = array(
            'systemMetaGroupId' => '1526990314988',
            'showQuery' => 0,
            'ignorePermission' => 1, 
            'criteria' => array(
                'filterCouponKeyId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $couponKeyId
                    )
                )
            )
        );

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result']) && isset($data['result'][0])) {
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);
            return $data['result'][0]['durationday'];
        } else {
            return null;
        }
    }
    
    public function createBillResultData($params) {
        
        try {
            
            $id       = getUID();
            $sendJson = $params['SEND_JSON'];
            
            $params['ID']         = $id;
            $params['IS_REMOVED'] = 0;
            $params['MACADDRESS'] = null;
            $params['SEND_JSON']  = null;

            $result = $this->db->AutoExecute('SM_BILL_RESULT_DATA', $params);

            if ($result) {
                $this->db->UpdateClob('SM_BILL_RESULT_DATA', 'SEND_JSON', $sendJson, 'ID = '.$id);
            }
            
            return true;
            
        } catch (ADODB_Exception $ex) {
            file_put_contents('log/pos_bill.log', $ex->getMessage(), FILE_APPEND);
            return false;
        }
    }
    
    public function createBillResultDataRePrint($params) {
        
        try {
            
            $this->db->AutoExecute('SM_BILL_RESULT_DATA', array('IS_REMOVED' => 1), 'UPDATE', 'SALES_INVOICE_ID = '.$params['SALES_INVOICE_ID']);
            
            $id       = getUID();
            $sendJson = $params['SEND_JSON'];
            
            $params['ID']         = $id;
            $params['IS_REMOVED'] = 0;
            $params['MACADDRESS'] = null;
            $params['SEND_JSON']  = null;

            $result = $this->db->AutoExecute('SM_BILL_RESULT_DATA', $params);

            if ($result) {
                $this->db->UpdateClob('SM_BILL_RESULT_DATA', 'SEND_JSON', $sendJson, 'ID = '.$id);
            }
            
            return true;
            
        } catch (ADODB_Exception $ex) {
            file_put_contents('log/pos_bill.log', $ex->getMessage(), FILE_APPEND);
            return false;
        }
    }
    
    public function billTypeChangePrintModel2($returnInvoiceId) {
        $returnResult = $this->billTypeCancelPrintModel($returnInvoiceId);
        if ($returnResult['status'] == 'success') {
            return $this->billPrintModel('typeChange');
        }
        return $returnResult;
    }
    
    public function billTypeChangePrintModel($returnInvoiceId) {
        
        $sPrefix        = SESSION_PREFIX;
        $currentDate    = Date::currentDate('Y-m-d H:i:s');
        
        $storeId        = Session::get($sPrefix.'storeId');
        
        parse_str($_POST['paymentData'], $paymentData);
        parse_str($_POST['itemData'], $itemData);
        
        $returnBillId           = Input::post('returnInvoiceBillId');
        $invoiceNumber          = Input::post('returnInvoiceNumber');
        $refNumber              = Input::post('returnInvoiceRefNumber');
        
        $vatAmount              = Number::decimal($paymentData['vatAmount']);
        $cityTaxAmount          = Number::decimal($paymentData['cityTaxAmount']);
        $discountAmount         = Number::decimal($paymentData['discountAmount']);
        
        $totalAmount            = Number::decimal($paymentData['payAmount']);
        $cashAmount             = Number::decimal($paymentData['cashAmount']);
        $bankAmount             = Number::decimal($paymentData['bankAmount']);
        $voucherAmount          = Number::decimal($paymentData['voucherAmount']);
        
        $bonusCardAmount        = Number::decimal($paymentData['bonusCardAmount']);
        $bonusCardMemberShipId  = $paymentData['cardMemberShipId'];
        
        $accountTransferAmt     = Number::decimal($paymentData['posAccountTransferAmt']);
        $mobileNetAmt           = Number::decimal($paymentData['posMobileNetAmt']);
        $barterAmt              = Number::decimal($paymentData['posBarterAmt']);
        $leasingAmt             = Number::decimal($paymentData['posLeasingAmt']);
        $empLoanAmt             = Number::decimal($paymentData['posEmpLoanAmt']);
        
        $changeAmount           = Number::decimal($paymentData['changeAmount']);
        $changeAmount           = ($changeAmount ? Number::decimal($changeAmount) : 0);
        $noVatAmount            = $totalAmount - $vatAmount;
        $nonCashAmount          = 0;
        $totalItemCount         = 0;
        $paymentDtl = $voucherDtl = $voucherUsedDtl = $deliveryDtl = $noDeliveryDtl = array();
        $itemPrintList = $stocks = $giftList = $paymentDetail = '';
        
        $paymentDtlTemplate = self::paymentDetailTemplate();
        
        $itemIds = $itemData['itemId'];
        
        foreach ($itemIds as $k => $itemId) {
            
            $itemQty        = Number::decimal($itemData['quantity'][$k]);
            $salePrice      = $itemData['salePrice'][$k];
            $totalPrice     = $itemData['totalPrice'][$k];
            $unitAmount     = $salePrice;
            $lineTotalAmount= $totalPrice;
            
            $isVat          = $itemData['isVat'][$k];
            $vatPercent     = $itemData['vatPercent'][$k];
            $noVatPrice     = $itemData['noVatPrice'][$k];
            
            $isDiscount         = $itemData['isDiscount'][$k];
            $discountPercent    = $itemData['discountPercent'][$k];
            $discountAmount     = $itemData['discountAmount'][$k];
            $unitDiscount       = 0;
            $lineTotalDiscount  = 0;
            
            if ($isVat == '1' && $isDiscount != '1') {
                
                $unitVat        = number_format($salePrice - $noVatPrice, 2, '.', '');
                $lineTotalVat   = number_format($unitVat * $itemQty, 2, '.', '');
                
            } elseif ($isVat == '1' && $isDiscount == '1') {
                
                $unitAmount     = $discountAmount;
                $noVatPrice     = number_format($discountAmount / 1.1, 2, '.', '');
                $unitVat        = number_format($discountAmount - $noVatPrice, 2, '.', '');
                $lineTotalVat   = number_format($unitVat * $itemQty, 2, '.', '');
                
                $unitDiscount       = $itemData['unitDiscount'][$k];
                $lineTotalDiscount  = $itemData['totalDiscount'][$k];
                $lineTotalAmount    = $lineTotalDiscount;
                
            } else {
                
                if ($isDiscount == '1') {
                    
                    $unitVat        = 0;
                    $lineTotalVat   = 0;
                    $unitAmount     = $discountAmount;
                    
                    $unitDiscount       = $itemData['unitDiscount'][$k];
                    $lineTotalDiscount  = $itemData['totalDiscount'][$k];
                    $lineTotalAmount    = $lineTotalDiscount;
                    
                } else {
                    $unitVat = $lineTotalVat = 0;
                }
            }
            
            $isCityTax      = $itemData['isCityTax'][$k];
            $cityTax        = ($isCityTax == '1' ? $itemData['cityTax'][$k] : 0);
            
            $isJob          = $itemData['isJob'][$k];
            $jobId          = '';
            
            if ($isJob == '1') {
                
                $jobId  = $itemId;
                $itemId = '';
            }
            
            $itemCode       = $itemData['itemCode'][$k];
            $itemName       = Str::doubleQuoteToSingleQuote($itemData['itemName'][$k]); 
            $measureCode    = $itemData['measureCode'][$k];
            $barCode        = $itemData['barCode'][$k];
            $barCode        = ($barCode ? $barCode : '132456789');
            $giftJsonStr    = $itemData['giftJson'][$k];
            
            $stocks .= "{
                'code': '" . $itemCode . "',
                'name': '" . $itemName . "',
                'measureUnit': '" . $measureCode . "',
                'qty': '" . sprintf("%.2f", $itemQty) . "',
                'unitPrice': '" . sprintf("%.2f", $unitAmount) . "',
                'totalAmount': '" . sprintf("%.2f", $lineTotalAmount) . "',
                'cityTax': '" . sprintf("%.2f", $cityTax) . "',
                'vat': '" . sprintf("%.2f", $lineTotalVat) . "',
                'barCode': '" . $barCode . "'
            }, ";  
            
            $row = array(
                'cityTax'       => '', 
                'itemName'      => $itemName, 
                'salePrice'     => $unitAmount, 
                'itemQty'       => $itemQty, 
                'totalPrice'    => $lineTotalAmount
            );
            $itemPrintList .= self::generateItemRow($row);
            
            $totalItemCount += $itemQty;
            
            if ($giftJsonStr != '') {
                
                $itemGiftList = array();
                $giftJsonArray = json_decode(html_entity_decode($giftJsonStr), true);
                
                foreach ($giftJsonArray as $giftJsonRow) {
                    
                    $giftList .= self::giftPrintRow($giftJsonRow);
                    
                    $totalItemCount += 1;
                }
            }
        }
        
        if ($bankAmount > 0) {
            
            $nonCashAmount += $bankAmount;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0084'), self::posAmount($bankAmount)), $paymentDtlTemplate);
        }
        
        if ($voucherAmount > 0) {
            
            $nonCashAmount += $voucherAmount;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0088'), self::posAmount($voucherAmount)), $paymentDtlTemplate);
        }
        
        if ($bonusCardAmount > 0) {
                
            $nonCashAmount += $bonusCardAmount;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array('Хөнгөлөлтийн карт', self::posAmount($bonusCardAmount)), $paymentDtlTemplate);
        }
        
        if ($bonusCardMemberShipId != '') {
            
            $bonusCardMemberShipId          = $paymentData['cardMemberShipId'];
            $bonusCardNumber                = Input::param($paymentData['cardNumber']);
            $bonusCardDiscountPercent       = $paymentData['cardDiscountPercent'];
            $bonusCardBeginAmount           = Number::decimal($paymentData['cardBeginAmount']);
            $bonusCardDiscountPercentAmount = Number::decimal($paymentData['cardDiscountPercentAmount']);
            $bonusCardEndAmount             = Number::decimal($paymentData['cardEndAmount']);
            
            if ($bonusCardBeginAmount < 1) {
                $bonusCardEndAmount = $bonusCardDiscountPercentAmount;
            }
            
        } else {
            $bonusCardNumber                = '00000000';
            $bonusCardDiscountPercent       = '0';
            $bonusCardBeginAmount           = '0';
            $bonusCardDiscountPercentAmount = '0';
            $bonusCardEndAmount             = '0';
        }
        
        if ($accountTransferAmt > 0) {
            
            $isInvInfo = true;
            $nonCashAmount += $accountTransferAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0089'), self::posAmount($accountTransferAmt)), $paymentDtlTemplate);
        }
        
        if ($mobileNetAmt > 0) {
            
            $isInvInfo = true;
            $nonCashAmount += $mobileNetAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0090'), self::posAmount($mobileNetAmt)), $paymentDtlTemplate);
        }
        
        if ($barterAmt > 0) {
            
            $isInvInfo = true;
            $nonCashAmount += $barterAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0091'), self::posAmount($barterAmt)), $paymentDtlTemplate);
        }
        
        if ($leasingAmt > 0) {
            
            $isInvInfo = true;
            $nonCashAmount += $leasingAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0092'), self::posAmount($leasingAmt)), $paymentDtlTemplate);
        }
        
        if ($empLoanAmt > 0) {
            
            $isInvInfo = true;
            $nonCashAmount += $empLoanAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array('Ажилчидын зээл', self::posAmount($empLoanAmt)), $paymentDtlTemplate);
        }
        
        if ($changeAmount > 0) {
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0096'), self::posAmount($changeAmount)), $paymentDtlTemplate);
        }
        
        $vatNumber      = Session::get($sPrefix.'vatNumber');
        $districtCode   = Session::get($sPrefix.'posDistrictCode');
        $posBillType    = $paymentData['posBillType'];
        $isDouble       = true;

        $cashCode       = Session::get($sPrefix.'cashRegisterCode');
        $reportMonth    = '';
        $customerNo     = '';
        $orgRegNumber   = '';
        $orgName        = '';

        if ($posBillType == 'person') {

            $billType   = 1;
            $title      = Lang::line('POS_0100');

        } elseif ($posBillType == 'organization') {

            $billType       = 3;
            $title          = Lang::line('POS_0101');

            $orgRegNumber   = $paymentData['orgRegNumber'];
            $customerNo     = $orgRegNumber; //'0000039';
            $orgName        = $paymentData['orgName'];

        } else {
            $billType       = 5;
            $title          = Lang::line('POS_0102');
        }

        $jsonParam = "{
            'amount': '" . sprintf("%.2f", $totalAmount) . "',
            'vat': '" . sprintf("%.2f", $vatAmount) . "',
            'cashAmount': '" . sprintf("%.2f", ($cashAmount - $changeAmount)) . "',
            'nonCashAmount': '" . sprintf("%.2f", $nonCashAmount) . "',
            'cityTax': '" . sprintf("%.2f", $cityTaxAmount) . "',
            'districtCode': '" . $districtCode . "',
            'posNo': '" . $cashCode . "',
            'reportMonth': '". $reportMonth ."',
            'customerNo': '" . $customerNo . "',
            'billType': '" . $billType . "',
            'taxType': '1',
            'billIdSuffix': '',
            'returnBillId': '" . $returnBillId . "',
            'stocks': [
                " . rtrim($stocks, ', ') . "
            ]
        }";

        $posApiArray    = self::posApiFunction($jsonParam);
        
        $warningMsg     = $posApiArray['warningMsg'];
        $billId         = isset($posApiArray['billId']) ? $posApiArray['billId'] : null;
        
        if ($billId) {
            
            $voucherContent = '';
                
            $storeName      = Session::get($sPrefix.'storeName');
            $cashierName    = Session::get($sPrefix.'cashierName');
            $contactInfo    = Session::get($sPrefix.'posContactInfo');
            $topTitle       = Session::get($sPrefix.'posHeaderName');
            $printCopies    = (int) $paymentData['posPrintCopies'];
            $salesPersonCode= self::getSalesPersonCode($itemData);
            $printType      = Config::getFromCache('CONFIG_POS_PRINT_TYPE');

            if ($billType == 1) { // Хувь хүн

                $templateContent = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.'/person/single.html');
                $templateContent = str_replace('{qrCodeLottery}', '', $templateContent);
                    
                $lottery         = $posApiArray['lottery'];
                $qrData          = $posApiArray['qrData'];

                $replacing = array(
                    '{companyName}'     => $topTitle,
                    '{title}'           => '', 
                    '{vatNumber}'       => $vatNumber,
                    '{contactInfo}'     => $contactInfo,
                    '{ddtd}'            => $billId,
                    '{date}'            => Date::formatter($currentDate, 'Y/m/d'),
                    '{time}'            => Date::formatter($currentDate, 'H:i:s'),
                    '{refNumber}'       => $refNumber,
                    '{invoiceNumber}'   => $invoiceNumber,
                    '{storeName}'       => $storeName,
                    '{cashierName}'     => $cashierName,
                    '{cashCode}'        => $cashCode, 
                    '{salesPersonCode}' => $salesPersonCode, 
                    '{salesWaiter}'     => Input::post('waiterText', ''),
                    '{orderTime}'       => Input::post('posEshopOrderTime', ''), 
                    '{itemList}'        => $itemPrintList,
                    '{totalAmount}'     => self::posAmount($totalAmount),
                    '{noVatAmount}'     => self::posAmount($noVatAmount),
                    '{vatAmount}'       => self::posAmount($vatAmount),
                    '{paidAmount}'      => self::posAmount($cashAmount),
                    '{changeAmount}'    => self::posAmount($changeAmount),
                    '{cardAmount}'      => self::posAmount($bankAmount),
                    '{lottery}'         => $lottery,
                    '{giftList}'        => self::giftTableRender($giftList), 
                    '{totalItemCount}'  => $totalItemCount, 

                    '{bonusCardNumber}'         => $bonusCardNumber, 
                    '{bonusCardDiscountPercent}'=> $bonusCardDiscountPercent,
                    '{bonusCardBeginAmount}'    => self::posAmount($bonusCardBeginAmount), 
                    '{bonusCardDiffAmount}'     => self::posAmount($bonusCardAmount), 
                    '{bonusCardPlusAmount}'     => self::posAmount($bonusCardDiscountPercentAmount), 
                    '{bonusCardEndAmount}'      => self::posAmount($bonusCardEndAmount), 
                    '{payment-detail}'          => $paymentDetail
                );

                $templateContent .= $voucherContent;

                if ($isDouble) {

                    $internalContent = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.'/person/internal.html');
                    $internalContent = strtr($internalContent, $replacing);

                    if ($printCopies > 1) {
                        $internalContent = str_repeat($internalContent, $printCopies);
                    }

                    $templateContent .= $internalContent;
                }

            } elseif ($billType == 3) { // Байгууллага

                $templateContent = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.'/organization/single.html');
                $qrData          = $posApiArray['qrData'];
                
                $replacing = array(
                    '{companyName}'     => $topTitle,
                    '{title}'           => '', 
                    '{vatNumber}'       => $vatNumber,
                    '{contactInfo}'     => $contactInfo,
                    '{ddtd}'            => $billId,
                    '{date}'            => Date::formatter($currentDate, 'Y/m/d'),
                    '{time}'            => Date::formatter($currentDate, 'H:i:s'),
                    '{refNumber}'       => $refNumber,
                    '{invoiceNumber}'   => $invoiceNumber,
                    '{storeName}'       => $storeName,
                    '{cashierName}'     => $cashierName,
                    '{cashCode}'        => $cashCode, 
                    '{salesPersonCode}' => $salesPersonCode, 
                    '{salesWaiter}'     => Input::post('waiterText', ''),
                    '{orderTime}'       => Input::post('posEshopOrderTime', ''), 
                    '{itemList}'        => $itemPrintList,
                    '{totalAmount}'     => self::posAmount($totalAmount),
                    '{noVatAmount}'     => self::posAmount($noVatAmount),
                    '{vatAmount}'       => self::posAmount($vatAmount),
                    '{paidAmount}'      => self::posAmount($cashAmount),
                    '{changeAmount}'    => self::posAmount($changeAmount),
                    '{cardAmount}'      => self::posAmount($bankAmount),
                    '{customerNumber}'  => $orgRegNumber, 
                    '{customerName}'    => $orgName, 
                    '{qrCode}'          => self::getQrCodeImg($qrData),
                    '{giftList}'        => self::giftTableRender($giftList), 
                    '{totalItemCount}'  => $totalItemCount, 

                    '{bonusCardNumber}'         => $bonusCardNumber, 
                    '{bonusCardDiscountPercent}'=> $bonusCardDiscountPercent,
                    '{bonusCardBeginAmount}'    => self::posAmount($bonusCardBeginAmount), 
                    '{bonusCardDiffAmount}'     => self::posAmount($bonusCardAmount), 
                    '{bonusCardPlusAmount}'     => self::posAmount($bonusCardDiscountPercentAmount), 
                    '{bonusCardEndAmount}'      => self::posAmount($bonusCardEndAmount), 
                    '{payment-detail}'          => $paymentDetail
                );

                $templateContent .= $voucherContent;

                if ($isDouble) {

                    $internalContent = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.'/organization/internal.html');
                    $internalContent = strtr($internalContent, $replacing);

                    if ($printCopies > 1) {
                        $internalContent = str_repeat($internalContent, $printCopies);
                    }

                    $templateContent .= $internalContent;
                }
            }

            $templateContent = strtr($templateContent, $replacing);

            $billResultParams = array(
                'billid'            => $billId, 
                'salesInvoiceId'    => $returnInvoiceId, 
                'merchantId'        => $posApiArray['merchantId'], 
                'vatDate'           => $posApiArray['date'], 
                'success'           => $posApiArray['success'], 
                'warningMsg'        => $warningMsg,  
                'sendJson'          => $jsonParam, 
                'storeId'           => $storeId, 
                'customerNumber'    => $orgRegNumber, 
                'customerName'      => $orgName, 
                'isRemoved'         => 0, 
                'macaddress'        => ''
            );
            
            $this->db->AutoExecute('SM_BILL_RESULT_DATA', array('IS_REMOVED' => 1), 'UPDATE', 'SALES_INVOICE_ID = '.$returnInvoiceId);

            $billDataResult = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'POS_SM_BILL_RESULT_DATA_001', $billResultParams);

            if ($billDataResult['status'] == 'error') {
                file_put_contents('log/pos_bill.log', $billDataResult['text'], FILE_APPEND);
            }
            
            $cssContent       = Mdpos::getPrintCss();
            $newInvoiceNumber = self::getBillNumModel();
            
            $response = array('status' => 'success', 'css' => $cssContent, 'printData' => $templateContent, 'billNumber' => $newInvoiceNumber);

        } else {
            
            if (!$warningMsg) {
                $warningMsg = $posApiArray['message'];
            }

            $response = array('status' => 'warning', 'message' => $warningMsg);
        }
        
        return $response;
    }
    
    public function billTypeCancelPrintModel($returnInvoiceId) {
        
        $getInvoiceDate = $this->getDateCashierModel();
        $sPrefix        = SESSION_PREFIX;
        $currentDate    = Date::currentDate('Y-m-d H:i:s');
        
        $returnBillId   = Input::post('returnInvoiceBillId');
        $billDate       = Input::post('returnInvoiceBillDate');
        $isGL           = Input::post('returnInvoiceIsGL');
        $isTodayReturn  = false;
        $isUserPosV3    = Config::getFromCache('IS_USE_POSAPI_V3');
        
        if ($isGL != '1' && Date::formatter($billDate, 'Y-m-d') == Date::currentDate('Y-m-d')) {
            $isTodayReturn = true;
        }
        
        if (is_array($getInvoiceDate['result']) && $getInvoiceDate['result']['bookdate']) {
            $getSavedDate = $getInvoiceDate['result']['bookdate'];
            if ($isGL != '1' && Date::formatter($billDate, 'Y-m-d') == $getSavedDate) {
                $isTodayReturn = true;
            }            
        }                   
        
        if (Config::getFromCache('CONFIG_POS_IS_ONLY_USE_PREV_DAY_RETURN_PROCESS') == 1) {
            $isTodayReturn  = false;
        }
        
        $isNotSendVatsp = (Session::get($sPrefix.'isNotSendVatsp') == '1' ? true : false);
        
        if ($isTodayReturn == true || $isNotSendVatsp == true) {
            
            $checkStatusCount = $this->db->GetOne("
                SELECT 
                    COUNT(H.SALES_INVOICE_ID) AS DTL_COUNT 
                FROM SM_SALES_INVOICE_HEADER H 
                    INNER JOIN META_DM_RECORD_MAP MP ON H.SALES_INVOICE_ID = MP.SRC_RECORD_ID 
                    INNER JOIN SDM_ORDER_BOOK B ON MP.TRG_RECORD_ID = B.SALES_ORDER_ID 
                    INNER JOIN SDM_SALES_ORDER_ITEM_DTL D ON B.SALES_ORDER_ID = D.SALES_ORDER_ID 
                WHERE MP.SEMANTIC_TYPE_ID = 305 
                    AND D.WFM_STATUS_ID NOT IN (1522650058246696, 1525274396937960)  
                    AND H.SALES_INVOICE_ID = $returnInvoiceId     
                GROUP BY H.SALES_INVOICE_ID
            ");
            
            if ($checkStatusCount && $checkStatusCount > 0) {
                
                $wfmStatusId = self::getInvoiceWfmStatusId($returnInvoiceId);
                
                if ($wfmStatusId != '1524480473421331' && Config::getFromCacheDefault('CONFIG_POS_IS_NOT_CHECK_WFM_STATUS_ON_RETURN', null, '1')) {
                    return array('status' => 'warning', 'message' => Lang::line('POS_0104'));
                }
            }
            
            if ($returnBillId) {

                if ($isUserPosV3) {
                    $taxInvParam = array(
                        'id' => $returnInvoiceId,
                        'date' => $billDate
                    );
                    $posApiArray = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'posMainSalesInvoiceReturnTax_V1', $taxInvParam);                
                    $posApiArray['success'] = true;
                    
                } else {
                
                    $jsonParam = "{
                        'returnBillId': '" . $returnBillId . "',
                        'date': '" . str_replace(':', '=', $billDate) . "'
                    }";

                    $posApiArray = self::posApiReturnBillFunction($jsonParam);
                }
                
            } else {
                $posApiArray['success'] = true;
            }

            if (isset($posApiArray['success'])) {
                
                $sessionUserKeyId = Session::get($sPrefix.'userkeyid');
                
                parse_str($_POST['paymentData'], $paymentData);

                $returnResult = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'SALES_RETURN_DV_002', array('id' => $returnInvoiceId));
                $returnDeleteResult = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'DELETE_PAYMENT_BOOK_MAP_005', array('salesInvioceId' => $returnInvoiceId));

                if ($returnResult['status'] == 'error') {
                    file_put_contents('log/pos_failed_return.txt', $returnInvoiceId .' - '. $returnResult['text'] . "\n", FILE_APPEND);
                    return array('status' => 'warning', 'message' => $returnResult['text']);
                }                

                if ($returnDeleteResult['status'] == 'error') {
                    file_put_contents('log/pos_failed_return.txt', $returnInvoiceId .' - '. $returnDeleteResult['text'] . "\n", FILE_APPEND);
                    return array('status' => 'warning', 'message' => $returnDeleteResult['text']);
                }
                
                /*$invoiceHdrData = array(
                    'BOOK_TYPE_ID'  => 201, 
                    'SUB_TOTAL'     => 0, 
                    'DISCOUNT'      => 0, 
                    'VAT'           => 0, 
                    'TOTAL'         => 0, 
                    'CHANGE_AMOUNT' => 0,
                    'TOTAL_CITY_TAX_AMOUNT' => 0, 

                    'IS_REMOVED'            => 1, 
                    'REMOVED_USER_ID'       => $sessionUserKeyId, 
                    'REMOVED_DATE_TIME'     => $currentDate, 
                    'WFM_STATUS_ID'         => '1505964292313312'
                );
                
                if (is_array($getInvoiceDate['result']) && $getInvoiceDate['result']['bookdate']) {
                    $invoiceHdrData['INVOICE_DATE'] = $getInvoiceDate['result']['bookdate'];
                }                
                
                if ($invInfoCustomerName = issetVar($paymentData['invInfoCustomerName'])) {
                    $invoiceHdrData['CUSTOMER_NAME'] = $invInfoCustomerName;
                }
                
                if ($invInfoCustomerLastName = issetVar($paymentData['invInfoCustomerLastName'])) {
                    $invoiceHdrData['CUSTOMER_LAST_NAME'] = $invInfoCustomerLastName;
                }
                
                if ($invInfoCustomerRegNumber = issetVar($paymentData['invInfoCustomerRegNumber'])) {
                    $invoiceHdrData['CUSTOMER_REG_NUMBER'] = $invInfoCustomerRegNumber;
                }
                
                if ($invInfoPhoneNumber = issetVar($paymentData['invInfoPhoneNumber'])) {
                    $invoiceHdrData['PHONE_NUMBER'] = $invInfoPhoneNumber;
                }

                $this->db->AutoExecute('SM_SALES_INVOICE_HEADER', $invoiceHdrData, 'UPDATE', 'SALES_INVOICE_ID = '.$returnInvoiceId);

                $invoiceDtlData = array(
                    'INVOICE_QTY'           => 0, 
                    'LINE_TOTAL_PRICE'      => 0, 
                    'PERCENT_DISCOUNT'      => 0, 
                    'UNIT_DISCOUNT'         => 0, 
                    'LINE_TOTAL_DISCOUNT'   => 0, 
                    'UNIT_VAT'              => 0, 
                    'LINE_TOTAL_VAT'        => 0, 
                    'UNIT_AMOUNT'           => 0, 
                    'LINE_TOTAL_AMOUNT'     => 0, 
                    'BONUS_PERCENT'         => 0, 
                    'TOTAL_SALES'           => 0,  

                    'LINE_TOTAL_BONUS_AMOUNT'    => 0, 
                    'LINE_TOTAL_CITY_TAX_AMOUNT' => 0, 

                    'IS_REMOVED'            => 1, 
                    'IS_MODIFIED'           => 1, 

                    'REMOVED_USER_ID'       => $sessionUserKeyId, 
                    'REMOVED_DATE_TIME'     => $currentDate, 
                    'WFM_STATUS_ID'         => '1505444237818750'
                );

                $this->db->AutoExecute('SM_SALES_INVOICE_DETAIL', $invoiceDtlData, 'UPDATE', 'SALES_INVOICE_ID = '.$returnInvoiceId);
                $this->db->AutoExecute('SM_SALES_PAYMENT', array('AMOUNT' => 0), 'UPDATE', 'SALES_INVOICE_ID = '.$returnInvoiceId);
                
                $this->db->Execute("
                    UPDATE 
                        SDM_SALES_ORDER_ITEM_DTL
                    SET WFM_STATUS_ID = 1527470816012878, ORDER_QTY = 0  
                    WHERE
                        SALES_ORDER_ID IN 
                        (
                            SELECT 
                                M.TRG_RECORD_ID 
                            FROM 
                                SM_SALES_INVOICE_HEADER H 
                            INNER JOIN META_DM_RECORD_MAP M ON H.SALES_INVOICE_ID = M.SRC_RECORD_ID 
                            WHERE M.SEMANTIC_TYPE_ID = 305 AND H.SALES_INVOICE_ID = $returnInvoiceId 
                        )
                ");
                
                $this->db->Execute("DELETE FROM LOY_PAYMENT_DTL WHERE BOOK_ID IN (SELECT B.ID FROM LOY_PAYMENT_BOOK_MAP M INNER JOIN LOY_PAYMENT_BOOK B ON M.PAYMENT_BOOK_ID = B.ID WHERE M.SALES_INVIOCE_ID = $returnInvoiceId)");
                $this->db->Execute("DELETE FROM LOY_PAYMENT_BOOK WHERE ID IN (SELECT M.PAYMENT_BOOK_ID FROM LOY_PAYMENT_BOOK_MAP M WHERE M.SALES_INVIOCE_ID = $returnInvoiceId)");
                $this->db->Execute("DELETE FROM LOY_PAYMENT_BOOK_MAP WHERE SALES_INVIOCE_ID = $returnInvoiceId");
                
                $this->db->Execute("UPDATE LOY_COUPON_KEY SET WFM_STATUS_ID = 1519887844646862, USED_DATE = null, USED_STORE_ID = null, USED_CASHIER_ID = null, USED_SALES_INVOICE_BOOK_ID = null WHERE USED_SALES_INVOICE_BOOK_ID = $returnInvoiceId");
                
                if (defined('CONFIG_POS_GENERATE_LOTTERY_NUMBER') && CONFIG_POS_GENERATE_LOTTERY_NUMBER) {
                    
                    $this->db->AutoExecute('LOY_LOTTERY', array('WFM_STATUS_ID' => 1543415068723825), 'UPDATE', 'SALES_INVOICE_ID = '.$returnInvoiceId);
                }*/
                
                $cssContent = $templateContent = '';
                $newInvoiceNumber = self::getBillNumModel();
                
                $response = array('status' => 'success', 'css' => $cssContent, 'printData' => $templateContent, 'billNumber' => $newInvoiceNumber, 'message' => Lang::line('POS_0105'));
            
            } else {
                $response = array('status' => 'warning', 'message' => 'POS Error: ' . self::apiStringReplace($posApiArray['message'], true));
            }
            
        } else {
            
            $wfmStatusId = self::getInvoiceWfmStatusId($returnInvoiceId);
            
            if ($wfmStatusId == '1524480473421331' || !Config::getFromCacheDefault('CONFIG_POS_IS_NOT_CHECK_WFM_STATUS_ON_RETURN', null, '1')) {
            
                if ($returnBillId) {
                    
                    if ($isUserPosV3) {
                        $taxInvParam = array(
                            'id' => $returnInvoiceId,
                            'date' => $billDate
                        );
                        $posApiArray = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'posMainSalesInvoiceReturnTax_V1', $taxInvParam);                
                        $posApiArray['success'] = true;
                        
                    } else {

                        $jsonParam = "{
                            'returnBillId': '" . $returnBillId . "',
                            'date': '" . str_replace(':', '=', $billDate) . "'
                        }";

                        $posApiArray = self::posApiReturnBillFunction($jsonParam);
                    }
                    
                } else {
                    $posApiArray['success'] = true;
                }
                    
                if (isset($posApiArray['success'])) {
                    
                    $invoiceHdrData = array();
                    parse_str($_POST['paymentData'], $paymentData);
                    
                    if ($invInfoCustomerName = issetVar($paymentData['invInfoCustomerName'])) {
                        $invoiceHdrData['CUSTOMER_NAME'] = $invInfoCustomerName;
                    }

                    if ($invInfoCustomerLastName = issetVar($paymentData['invInfoCustomerLastName'])) {
                        $invoiceHdrData['CUSTOMER_LAST_NAME'] = $invInfoCustomerLastName;
                    }

                    if ($invInfoCustomerRegNumber = issetVar($paymentData['invInfoCustomerRegNumber'])) {
                        $invoiceHdrData['CUSTOMER_REG_NUMBER'] = $invInfoCustomerRegNumber;
                    }

                    if ($invInfoPhoneNumber = issetVar($paymentData['invInfoPhoneNumber'])) {
                        $invoiceHdrData['PHONE_NUMBER'] = $invInfoPhoneNumber;
                    }
                    
                    if (count($invoiceHdrData)) {
                        $this->db->AutoExecute('SM_SALES_INVOICE_HEADER', $invoiceHdrData, 'UPDATE', 'SALES_INVOICE_ID = '.$returnInvoiceId);
                    }

                    $returnResult = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'SALES_INVOICE_RETURN_001', array('id' => $returnInvoiceId));

                    if ($returnResult['status'] == 'error') {
                        file_put_contents('log/pos_failed_return.txt', $returnInvoiceId .' - '. $returnResult['text'] . "\n", FILE_APPEND);
                        return array('status' => 'warning', 'message' => $returnResult['text']);
                    }
                    
                    $this->db->Execute("UPDATE LOY_COUPON_KEY SET WFM_STATUS_ID = 1519887844646862, USED_DATE = null, USED_STORE_ID = null, USED_CASHIER_ID = null, USED_SALES_INVOICE_BOOK_ID = null WHERE USED_SALES_INVOICE_BOOK_ID = $returnInvoiceId");
                    
                    if (defined('CONFIG_POS_GENERATE_LOTTERY_NUMBER') && CONFIG_POS_GENERATE_LOTTERY_NUMBER) {
                        
                        $this->db->AutoExecute('LOY_LOTTERY', array('WFM_STATUS_ID' => 1543415068723825), 'UPDATE', 'SALES_INVOICE_ID = '.$returnInvoiceId);
                    }
                
                    $cssContent = $templateContent = '';
                    $newInvoiceNumber = self::getBillNumModel();

                    $response = array('status' => 'success', 'css' => $cssContent, 'printData' => $templateContent, 'billNumber' => $newInvoiceNumber, 'message' => Lang::line('POS_0105'));
                    
                } else {
                    $response = array('status' => 'warning', 'message' => self::apiStringReplace($posApiArray['message'], true));
                }
                
            } else {
                $response = array('status' => 'warning', 'message' => Lang::line('POS_0106'));
            }
        }
        
        if (Config::getFromCache('CONFIG_POS_HEALTHRECIPE') 
            && Input::isEmpty('returnInvoiceReceiptNumber') == false && $response['status'] == 'success') {
            
            $getToken = self::emdGetToken();

            if (isset($getToken['access_token'])) {
                
                $getEmdReturn = self::emdReturn($getToken['access_token'], $returnBillId);

                if (isset($getEmdReturn['msg']) && $getEmdReturn['msg'] == 'success' 
                    && ($getEmdReturn['code'] == '200' || $getEmdReturn['code'] == '300')) {
                    
                    $this->db->AutoExecute('SM_SALES_INVOICE_PRESCRIPTION', 
                        array(
                            'IS_REMOVED'   => 1, 
                            'REMOVED_DATE' => Date::currentDate('Y-m-d H:i:s')
                        ), 
                        'UPDATE', 'SALES_INVOICE_ID = '.$returnInvoiceId
                    );
                }
            }
        }
        
        if ($response['status'] == 'success') {
            
            $candyRedPointData = $this->db->GetAll("
                SELECT 
                    CANDY_NUMBER, 
                    CANDY_TRANSACTION_ID, 
                    PAYMENT_TYPE_ID
                FROM SM_SALES_PAYMENT 
                WHERE SALES_INVOICE_ID = $returnInvoiceId 
                    AND CANDY_TRANSACTION_ID IS NOT NULL 
                    AND IN_AMT IS NOT NULL");
            
            if ($candyRedPointData) {
                
                foreach ($candyRedPointData as $crRow) {
                    
                    if ($crRow['PAYMENT_TYPE_ID'] == '16') {
                        self::redPointCancel($crRow['CANDY_TRANSACTION_ID']);
                    }
                }
            }
        }
        
        if ($response['status'] == 'success' && Config::getFromCache('CONFIG_POS_TALON_RETURN_PRINT')) {
                
            $printData = self::printReturnInvoiceModel($returnInvoiceId);
            
            if ($printData['status'] == 'success') {
                $response['css']       = Mdpos::getPrintCss();
                $response['printData'] = $printData['printData'];
            }
        }
        
        return $response;
    }

    public function billTypeCancelPrintModel2($returnInvoiceId) {
        
        $getInvoiceDate = $this->getDateCashierModel();
        $sPrefix        = SESSION_PREFIX;
        $currentDate    = Date::currentDate('Y-m-d H:i:s');
        
        $returnBillId   = Input::post('returnInvoiceBillId');
        $billDate       = Input::post('returnInvoiceBillDate');
        $isGL           = Input::post('returnInvoiceIsGL');
        $isTodayReturn  = false;
        
        if ($isGL != '1' && Date::formatter($billDate, 'Y-m-d') == Date::currentDate('Y-m-d')) {
            $isTodayReturn = true;
        }
        
        if (is_array($getInvoiceDate['result']) && $getInvoiceDate['result']['bookdate']) {
            $getSavedDate = $getInvoiceDate['result']['bookdate'];
            if ($isGL != '1' && Date::formatter($billDate, 'Y-m-d') == $getSavedDate) {
                $isTodayReturn = true;
            }            
        }                   
        
        if (Config::getFromCache('CONFIG_POS_IS_ONLY_USE_PREV_DAY_RETURN_PROCESS') == 1) {
            $isTodayReturn  = false;
        }
        
        $isNotSendVatsp = (Session::get($sPrefix.'isNotSendVatsp') == '1' ? true : false);
        
        // if ($isNotSendVatsp == true) {
            
            $checkStatusCount = $this->db->GetOne("
                SELECT 
                    COUNT(H.SALES_INVOICE_ID) AS DTL_COUNT 
                FROM SM_SALES_INVOICE_HEADER H 
                    INNER JOIN META_DM_RECORD_MAP MP ON H.SALES_INVOICE_ID = MP.SRC_RECORD_ID 
                    INNER JOIN SDM_ORDER_BOOK B ON MP.TRG_RECORD_ID = B.SALES_ORDER_ID 
                    INNER JOIN SDM_SALES_ORDER_ITEM_DTL D ON B.SALES_ORDER_ID = D.SALES_ORDER_ID 
                WHERE MP.SEMANTIC_TYPE_ID = 305 
                    AND D.WFM_STATUS_ID NOT IN (1522650058246696, 1525274396937960)  
                    AND H.SALES_INVOICE_ID = $returnInvoiceId     
                GROUP BY H.SALES_INVOICE_ID
            ");
            
            if ($checkStatusCount && $checkStatusCount > 0) {
                
                $wfmStatusId = self::getInvoiceWfmStatusId($returnInvoiceId);
                
                if ($wfmStatusId != '1524480473421331' && Config::getFromCacheDefault('CONFIG_POS_IS_NOT_CHECK_WFM_STATUS_ON_RETURN', null, '1')) {
                    return array('status' => 'warning', 'message' => Lang::line('POS_0104'));
                }
            }
            
            if ($returnBillId) {
                
                $jsonParam = "{
                    'returnBillId': '" . $returnBillId . "',
                    'date': '" . str_replace(':', '=', $billDate) . "'
                }";

                $posApiArray = self::posApiReturnBillFunction($jsonParam);
                
            } else {
                $posApiArray['success'] = true;
            }

            if (isset($posApiArray['success'])) {
                
                $sessionUserKeyId = Session::get($sPrefix.'userkeyid');
                
                parse_str($_POST['paymentData'], $paymentData);

                $returnResult = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'SALES_RETURN_DV_002', array('id' => $returnInvoiceId));
                $returnDeleteResult = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'DELETE_PAYMENT_BOOK_MAP_005', array('salesInvioceId' => $returnInvoiceId));

                if ($returnResult['status'] == 'error') {
                    file_put_contents('log/pos_failed_return.txt', $returnInvoiceId .' - '. $returnResult['text'] . "\n", FILE_APPEND);
                    return array('status' => 'warning', 'message' => $returnResult['text']);
                }                

                if ($returnDeleteResult['status'] == 'error') {
                    file_put_contents('log/pos_failed_return.txt', $returnInvoiceId .' - '. $returnDeleteResult['text'] . "\n", FILE_APPEND);
                    return array('status' => 'warning', 'message' => $returnDeleteResult['text']);
                }
                
                $cssContent = $templateContent = '';
                $newInvoiceNumber = self::getBillNumModel();
                
                $response = array('status' => 'success', 'css' => $cssContent, 'printData' => $templateContent, 'billNumber' => $newInvoiceNumber, 'message' => Lang::line('POS_0105'));
            
            } else {
                $response = array('status' => 'warning', 'message' => 'POS Error: ' . self::apiStringReplace($posApiArray['message'], true));
            }
            
        // }
        
        if (Config::getFromCache('CONFIG_POS_HEALTHRECIPE') 
            && Input::isEmpty('returnInvoiceReceiptNumber') == false && $response['status'] == 'success') {
            
            $getToken = self::emdGetToken();

            if (isset($getToken['access_token'])) {
                
                $getEmdReturn = self::emdReturn($getToken['access_token'], $returnBillId);

                if (isset($getEmdReturn['msg']) && $getEmdReturn['msg'] == 'success' 
                    && ($getEmdReturn['code'] == '200' || $getEmdReturn['code'] == '300')) {
                    
                    $this->db->AutoExecute('SM_SALES_INVOICE_PRESCRIPTION', 
                        array(
                            'IS_REMOVED'   => 1, 
                            'REMOVED_DATE' => Date::currentDate('Y-m-d H:i:s')
                        ), 
                        'UPDATE', 'SALES_INVOICE_ID = '.$returnInvoiceId
                    );
                }
            }
        }
        
        if ($response['status'] == 'success') {
            
            $candyRedPointData = $this->db->GetAll("
                SELECT 
                    CANDY_NUMBER, 
                    CANDY_TRANSACTION_ID, 
                    PAYMENT_TYPE_ID
                FROM SM_SALES_PAYMENT 
                WHERE SALES_INVOICE_ID = $returnInvoiceId 
                    AND CANDY_TRANSACTION_ID IS NOT NULL 
                    AND IN_AMT IS NOT NULL");
            
            if ($candyRedPointData) {
                
                foreach ($candyRedPointData as $crRow) {
                    
                    if ($crRow['PAYMENT_TYPE_ID'] == '16') {
                        self::redPointCancel($crRow['CANDY_TRANSACTION_ID']);
                    }
                }
            }
        }
        
        if ($response['status'] == 'success' && Config::getFromCache('CONFIG_POS_TALON_RETURN_PRINT')) {
                
            $printData = self::printReturnInvoiceModel($returnInvoiceId);
            
            if ($printData['status'] == 'success') {
                $response['css']       = Mdpos::getPrintCss();
                $response['printData'] = $printData['printData'];
            }
        }
        
        return $response;
    }    

    public function billTypeCancelPrintModel3($returnInvoiceId) {
        
        $getInvoiceDate = $this->getDateCashierModel();
        $sPrefix        = SESSION_PREFIX;
        $currentDate    = Date::currentDate('Y-m-d H:i:s');
        
        $returnBillId   = Input::post('returnInvoiceBillId');
        $billDate       = Input::post('returnInvoiceBillDate');
        $isGL           = Input::post('returnInvoiceIsGL');
        $isTodayReturn  = false;
        
        if ($isGL != '1' && Date::formatter($billDate, 'Y-m-d') == Date::currentDate('Y-m-d')) {
            $isTodayReturn = true;
        }
        
        if (is_array($getInvoiceDate['result']) && $getInvoiceDate['result']['bookdate']) {
            $getSavedDate = $getInvoiceDate['result']['bookdate'];
            if ($isGL != '1' && Date::formatter($billDate, 'Y-m-d') == $getSavedDate) {
                $isTodayReturn = true;
            }            
        }                   
        
        if (Config::getFromCache('CONFIG_POS_IS_ONLY_USE_PREV_DAY_RETURN_PROCESS') == 1) {
            $isTodayReturn  = false;
        }
        
        $isNotSendVatsp = (Session::get($sPrefix.'isNotSendVatsp') == '1' ? true : false);
        
        $wfmStatusId = self::getInvoiceWfmStatusId($returnInvoiceId);
        
        if ($wfmStatusId == '1524480473421331' || !Config::getFromCacheDefault('CONFIG_POS_IS_NOT_CHECK_WFM_STATUS_ON_RETURN', null, '1')) {
        
            if ($returnBillId) {
                
                $jsonParam = "{
                    'returnBillId': '" . $returnBillId . "',
                    'date': '" . str_replace(':', '=', $billDate) . "'
                }";

                $posApiArray = self::posApiReturnBillFunction($jsonParam);
                
            } else {
                $posApiArray['success'] = true;
            }
                
            if (isset($posApiArray['success'])) {
                
                $invoiceHdrData = array();
                parse_str($_POST['paymentData'], $paymentData);
                
                if ($invInfoCustomerName = issetVar($paymentData['invInfoCustomerName'])) {
                    $invoiceHdrData['CUSTOMER_NAME'] = $invInfoCustomerName;
                }

                if ($invInfoCustomerLastName = issetVar($paymentData['invInfoCustomerLastName'])) {
                    $invoiceHdrData['CUSTOMER_LAST_NAME'] = $invInfoCustomerLastName;
                }

                if ($invInfoCustomerRegNumber = issetVar($paymentData['invInfoCustomerRegNumber'])) {
                    $invoiceHdrData['CUSTOMER_REG_NUMBER'] = $invInfoCustomerRegNumber;
                }

                if ($invInfoPhoneNumber = issetVar($paymentData['invInfoPhoneNumber'])) {
                    $invoiceHdrData['PHONE_NUMBER'] = $invInfoPhoneNumber;
                }
                
                if (count($invoiceHdrData)) {
                    $this->db->AutoExecute('SM_SALES_INVOICE_HEADER', $invoiceHdrData, 'UPDATE', 'SALES_INVOICE_ID = '.$returnInvoiceId);
                }

                $returnResult = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'SALES_INVOICE_RETURN_001', array('id' => $returnInvoiceId));

                if ($returnResult['status'] == 'error') {
                    file_put_contents('log/pos_failed_return.txt', $returnInvoiceId .' - '. $returnResult['text'] . "\n", FILE_APPEND);
                    return array('status' => 'warning', 'message' => $returnResult['text']);
                }
                
                $this->db->Execute("UPDATE LOY_COUPON_KEY SET WFM_STATUS_ID = 1519887844646862, USED_DATE = null, USED_STORE_ID = null, USED_CASHIER_ID = null, USED_SALES_INVOICE_BOOK_ID = null WHERE USED_SALES_INVOICE_BOOK_ID = $returnInvoiceId");
                
                if (defined('CONFIG_POS_GENERATE_LOTTERY_NUMBER') && CONFIG_POS_GENERATE_LOTTERY_NUMBER) {
                    
                    $this->db->AutoExecute('LOY_LOTTERY', array('WFM_STATUS_ID' => 1543415068723825), 'UPDATE', 'SALES_INVOICE_ID = '.$returnInvoiceId);
                }
            
                $cssContent = $templateContent = '';
                $newInvoiceNumber = self::getBillNumModel();

                $response = array('status' => 'success', 'css' => $cssContent, 'printData' => $templateContent, 'billNumber' => $newInvoiceNumber, 'message' => Lang::line('POS_0105'));
                
            } else {
                $response = array('status' => 'warning', 'message' => self::apiStringReplace($posApiArray['message'], true));
            }
            
        } else {
            $response = array('status' => 'warning', 'message' => Lang::line('POS_0106'));
        }
        
        if (Config::getFromCache('CONFIG_POS_HEALTHRECIPE') 
            && Input::isEmpty('returnInvoiceReceiptNumber') == false && $response['status'] == 'success') {
            
            $getToken = self::emdGetToken();

            if (isset($getToken['access_token'])) {
                
                $getEmdReturn = self::emdReturn($getToken['access_token'], $returnBillId);

                if (isset($getEmdReturn['msg']) && $getEmdReturn['msg'] == 'success' 
                    && ($getEmdReturn['code'] == '200' || $getEmdReturn['code'] == '300')) {
                    
                    $this->db->AutoExecute('SM_SALES_INVOICE_PRESCRIPTION', 
                        array(
                            'IS_REMOVED'   => 1, 
                            'REMOVED_DATE' => Date::currentDate('Y-m-d H:i:s')
                        ), 
                        'UPDATE', 'SALES_INVOICE_ID = '.$returnInvoiceId
                    );
                }
            }
        }
        
        if ($response['status'] == 'success') {
            
            $candyRedPointData = $this->db->GetAll("
                SELECT 
                    CANDY_NUMBER, 
                    CANDY_TRANSACTION_ID, 
                    PAYMENT_TYPE_ID
                FROM SM_SALES_PAYMENT 
                WHERE SALES_INVOICE_ID = $returnInvoiceId 
                    AND CANDY_TRANSACTION_ID IS NOT NULL 
                    AND IN_AMT IS NOT NULL");
            
            if ($candyRedPointData) {
                
                foreach ($candyRedPointData as $crRow) {
                    
                    if ($crRow['PAYMENT_TYPE_ID'] == '16') {
                        self::redPointCancel($crRow['CANDY_TRANSACTION_ID']);
                    }
                }
            }
        }
        
        if ($response['status'] == 'success' && Config::getFromCache('CONFIG_POS_TALON_RETURN_PRINT')) {
                
            $printData = self::printReturnInvoiceModel($returnInvoiceId);
            
            if ($printData['status'] == 'success') {
                $response['css']       = Mdpos::getPrintCss();
                $response['printData'] = $printData['printData'];
            }
        }
        
        return $response;
    }    
    
    public function billTypeReducePrintModel($returnInvoiceId) {
        
        $sPrefix        = SESSION_PREFIX;
        
        $refNumber      = self::getPosInvoiceNumber('1522725721739');
        $invoiceNumber  = self::getBillNumModel();
        $currentDate    = Date::currentDate('Y-m-d H:i:s');
        
        $storeId        = Session::get($sPrefix.'storeId');
        $cashierId      = Session::get($sPrefix.'cashierId');
        $cashRegisterId = Session::get($sPrefix.'cashRegisterId');
        
        parse_str($_POST['paymentData'], $paymentData);
        parse_str($_POST['itemData'], $itemData);
        
        $vatAmount              = Number::decimal($paymentData['vatAmount']);
        $cityTaxAmount          = Number::decimal($paymentData['cityTaxAmount']);
        $discountAmount         = Number::decimal($paymentData['discountAmount']);
        
        $totalAmount            = Number::decimal($paymentData['payAmount']);
        $cashAmount             = Number::decimal($paymentData['cashAmount']);
        $bankAmount             = Number::decimal($paymentData['bankAmount']);
        $voucherAmount          = Number::decimal($paymentData['voucherAmount']);
        
        $bonusCardAmount        = Number::decimal($paymentData['bonusCardAmount']);
        $bonusCardMemberShipId  = $paymentData['cardMemberShipId'];
        
        $accountTransferAmt     = Number::decimal($paymentData['posAccountTransferAmt']);
        $mobileNetAmt           = Number::decimal($paymentData['posMobileNetAmt']);
        $barterAmt              = Number::decimal($paymentData['posBarterAmt']);
        $leasingAmt             = Number::decimal($paymentData['posLeasingAmt']);
        $empLoanAmt             = Number::decimal($paymentData['posEmpLoanAmt']);
        
        $changeAmount           = Number::decimal($paymentData['changeAmount']);
        $noVatAmount            = $totalAmount - $vatAmount;
        $nonCashAmount          = 0;
        $totalItemCount         = 0;
        
        $params = array(
            'bookTypeId'        => 9, 
            'invoiceNumber'     => $invoiceNumber, 
            'invoiceDate'       => $currentDate, 
            'createdDateTime'   => $currentDate, 
            'storeId'           => $storeId,
            'cashRegisterId'    => $cashRegisterId,
            'createdCashierId'  => $cashierId, 
            'totalCityTaxAmount'=> $cityTaxAmount, 
            'subTotal'          => $totalAmount, 
            'discount'          => $discountAmount, 
            'vat'               => $vatAmount, 
            'total'             => $totalAmount, 
            'refNumber'         => $refNumber,              
            'wfmStatusId'       => '1505964291977811'
        );
        
        $headerParams = $params;
        
        $paramsDtl = $paymentDtl = $voucherDtl = $voucherUsedDtl = $deliveryDtl = $noDeliveryDtl = array();
        $itemPrintList = $stocks = $giftList = '';
        
        $itemIds = $itemData['itemId'];
        
        foreach ($itemIds as $k => $itemId) {
            
            $itemQty        = Number::decimal($itemData['quantity'][$k]);
            $salePrice      = $itemData['salePrice'][$k];
            $totalPrice     = $itemData['totalPrice'][$k];
            $unitAmount     = $salePrice;
            $lineTotalAmount= $totalPrice;
            
            $isVat          = $itemData['isVat'][$k];
            $vatPercent     = $itemData['vatPercent'][$k];
            $noVatPrice     = $itemData['noVatPrice'][$k];
            
            $isDiscount         = $itemData['isDiscount'][$k];
            $discountPercent    = $itemData['discountPercent'][$k];
            $discountAmount     = $itemData['discountAmount'][$k];
            $unitDiscount       = 0;
            $lineTotalDiscount  = 0;
            
            $isDelivery     = $itemData['isDelivery'][$k];
            $employeeId     = $itemData['employeeId'][$k];
            
            $storeWarehouseId       = $itemData['storeWarehouseId'][$k];
            $deliveryWarehouseId    = $itemData['deliveryWarehouseId'][$k];
            
            if ($isVat == '1' && $isDiscount != '1') {
                
                $unitVat        = number_format($salePrice - $noVatPrice, 2, '.', '');
                $lineTotalVat   = number_format($unitVat * $itemQty, 2, '.', '');
                
            } elseif ($isVat == '1' && $isDiscount == '1') {
                
                $unitAmount     = $discountAmount;
                $noVatPrice     = number_format($discountAmount / 1.1, 2, '.', '');
                $unitVat        = number_format($discountAmount - $noVatPrice, 2, '.', '');
                $lineTotalVat   = number_format($unitVat * $itemQty, 2, '.', '');
                
                $unitDiscount       = $itemData['unitDiscount'][$k];
                $lineTotalDiscount  = $itemData['totalDiscount'][$k];
                $lineTotalAmount    = $lineTotalDiscount;
                
            } else {
                
                if ($isDiscount == '1') {
                    
                    $unitVat        = 0;
                    $lineTotalVat   = 0;
                    $unitAmount     = $discountAmount;
                    
                    $unitDiscount       = $itemData['unitDiscount'][$k];
                    $lineTotalDiscount  = $itemData['totalDiscount'][$k];
                    $lineTotalAmount    = $lineTotalDiscount;
                    
                } else {
                    $unitVat = $lineTotalVat = 0;
                }
            }
            
            $isCityTax      = $itemData['isCityTax'][$k];
            $cityTax        = ($isCityTax == '1' ? $itemData['cityTax'][$k] : 0);
            
            $isJob          = $itemData['isJob'][$k];
            $isOperating    = isset($itemData['isOperating']) ? $itemData['isOperating'][$k] : '';
            $jobId          = '';
            
            if ($isJob == '1') {
                
                $jobId = $itemId;
                $itemId = '';
            }
            
            $paramsDtl[$k] = array(
                'itemId'                    => $itemId, 
                'jobId'                     => $jobId, 
                'lineTotalCityTaxAmount'    => $cityTax,  
                'invoiceQty'                => $itemQty, 
                
                'unitPrice'                 => $salePrice,
                'lineTotalPrice'            => $totalPrice,  
                'unitAmount'                => $unitAmount, 
                'lineTotalAmount'           => $lineTotalAmount,  
                
                'percentVat'                => $vatPercent, 
                'unitVat'                   => $unitVat, 
                'lineTotalVat'              => $lineTotalVat,
                
                'percentDiscount'           => $discountPercent, 
                'unitDiscount'              => $unitDiscount, 
                'lineTotalDiscount'         => $lineTotalDiscount, 
                
                'isDelivery'                => $isDelivery,  
                'employeeId'                => $employeeId, 
                'isRemoved'                 => 0
            );
            
            $itemCode       = $itemData['itemCode'][$k];
            $itemName       = Str::doubleQuoteToSingleQuote($itemData['itemName'][$k]); 
            $measureCode    = $itemData['measureCode'][$k];
            $barCode        = $itemData['barCode'][$k];
            $barCode        = ($barCode && $barCode != 'null' ? $barCode : '132456789');
            $giftJsonStr    = $itemData['giftJson'][$k];
            
            $stocks .= "{
                'code': '" . $itemCode . "',
                'name': '" . $itemName . "',
                'measureUnit': '" . $measureCode . "',
                'qty': '" . sprintf("%.2f", $itemQty) . "',
                'unitPrice': '" . sprintf("%.2f", $unitAmount) . "',
                'totalAmount': '" . sprintf("%.2f", $lineTotalAmount) . "',
                'cityTax': '" . sprintf("%.2f", $cityTax) . "',
                'vat': '" . sprintf("%.2f", $lineTotalVat) . "',
                'barCode': '" . $barCode . "'
            }, ";  
            
            $row = array(
                'cityTax'       => '', 
                'itemName'      => $itemName, 
                'salePrice'     => $unitAmount, 
                'itemQty'       => $itemQty, 
                'totalPrice'    => $lineTotalAmount
            );
            $itemPrintList .= self::generateItemRow($row);
            
            $totalItemCount += $itemQty;
            
            if ($giftJsonStr != '') {
                
                $itemPackageList = $itemGiftList = array();
                $giftJsonArray = json_decode(html_entity_decode($giftJsonStr), true);
                
                foreach ($giftJsonArray as $giftJsonRow) {
                    
                    $itemPackageList[] = array(
                        'packageDtlId'      => $giftJsonRow['packagedtlid'],
                        'qty'               => 1, 
                        'discountPolicyId'  => $giftJsonRow['policyid']
                    );
                    
                    if ($giftJsonRow['coupontypeid'] == 1) {
                        
                        $voucherDtl[] = array(
                            'amount'    => $giftJsonRow['couponamount'], 
                            'name'      => $giftJsonRow['coupontypename'], 
                            'rowIndex'  => $k
                        );
                    }
                    
                    $giftList .= self::giftPrintRow($giftJsonRow);
                    
                    $totalItemCount += 1;
                    
                    if ($giftJsonRow['coupontypeid'] == '') {
                        
                        $giftJsonRow['isgift']      = 1;
                        $giftJsonRow['invoiceqty']  = 1;
                        $giftJsonRow['employeeId']  = $employeeId; 
                        $giftJsonRow['itemid']      = $giftJsonRow['promotionitemid']; 
                        
                        if ($isDelivery == 1) {
                            $giftJsonRow['warehouseId'] = $deliveryWarehouseId; 
                        } else {
                            $giftJsonRow['warehouseId'] = $storeWarehouseId; 
                        }
                        
                        $itemGiftList[] = $giftJsonRow;
                    }
                }
                
                $paramsDtl[$k]['SDM_SALES_ORDER_ITEM_PACKAGE'] = $itemPackageList;
                $paramsDtl[$k]['POS_SM_SALES_INVOICE_DETAIL'] = $itemGiftList;
            }
            
            if ($isJob != '1' && $isOperating != '1') {
                
                if ($isDelivery == 1) {

                    $paramsDtl[$k]['warehouseId'] = $deliveryWarehouseId;
                    $deliveryDtl[] = $paramsDtl[$k];

                } else {

                    $paramsDtl[$k]['warehouseId'] = $storeWarehouseId;
                    $noDeliveryDtl[] = $paramsDtl[$k];
                }
            }
        }
        
        $params['POS_SM_SALES_INVOICE_DETAIL'] = $paramsDtl;
        
        if ($cashAmount > 0) {
            
            $paymentDtl[] = array(
                'paymentTypeId' => 1, 
                'amount'        => $cashAmount
            );
        }
        
        if ($bankAmount > 0) {
            
            $bankAmountDtl = $paymentData['bankAmountDtl'];
            
            foreach ($bankAmountDtl as $b => $bankDtlAmount) {
                
                $bankId         = $paymentData['posBankIdDtl'][$b];
                $bankDtlAmount  = Number::decimal($bankDtlAmount);
                        
                if ($bankId != '' && $bankDtlAmount > 0) {
                    
                    $paymentDtl[] = array(
                        'paymentTypeId' => 2, 
                        'bankId'        => $bankId,
                        'amount'        => $bankDtlAmount
                    );
                }
            }
            
            $nonCashAmount += $bankAmount;
        }
        
        if ($voucherAmount > 0) {
            
            $voucherDtlAmount = $paymentData['voucherDtlAmount'];
            
            foreach ($voucherDtlAmount as $v => $voucherAmountDtl) {
                
                $voucherId              = $paymentData['voucherDtlId'][$v];
                $voucherTypeId          = $paymentData['voucherTypeId'][$v];
                $voucherAmountDtl       = Number::decimal($voucherAmountDtl);
                $voucherSerialNumber    = $paymentData['voucherDtlSerialNumber'][$v];
                
                if ($voucherId != '' && $voucherAmountDtl > 0 && $voucherSerialNumber != '') {
                    
                    $paymentDtl[] = array(
                        'paymentTypeId' => 10, 
                        'amount'        => $voucherAmountDtl
                    );
                    
                    $voucherUsedDtl[] = array(
                        'id' => $voucherId
                    );
                }
            }
            
            $nonCashAmount += $voucherAmount;
        }
        
        if ($bonusCardMemberShipId != '') {
            
            $bonusCardMemberShipId          = $paymentData['cardMemberShipId'];
            $bonusCardNumber                = Input::param($paymentData['cardNumber']);
            $bonusCardDiscountPercent       = $paymentData['cardDiscountPercent'];
            $bonusCardBeginAmount           = Number::decimal($paymentData['cardBeginAmount']);
            $bonusCardDiscountPercentAmount = Number::decimal($paymentData['cardDiscountPercentAmount']);
            $bonusCardEndAmount             = Number::decimal($paymentData['cardEndAmount']);
            
            if ($bonusCardAmount > 0) {
                
                $params['cardId'] = $paymentData['cardId'];
                
                $paymentDtl[] = array(
                    'paymentTypeId' => 12, 
                    'amount'        => $bonusCardAmount, 
                    'membershipId'  => $bonusCardMemberShipId
                );
                
                $nonCashAmount += $bonusCardAmount;
            }
            
            if ($bonusCardDiscountPercentAmount > 0 || $bonusCardAmount > 0) {
                
                $params['LOY_PAYMENT_BOOK_MAP_STORE']['LOY_PAYMENT_BOOK_STORE']['bookDate']   = $currentDate;
                $params['LOY_PAYMENT_BOOK_MAP_STORE']['LOY_PAYMENT_BOOK_STORE']['bookNumber'] = self::getPosInvoiceNumber('1489109738291');
                
                $params['LOY_PAYMENT_BOOK_MAP_STORE']['LOY_PAYMENT_BOOK_STORE']['LOY_PAYMENT_DTL_STORE']['membershipId'] = $bonusCardMemberShipId;
                $params['LOY_PAYMENT_BOOK_MAP_STORE']['LOY_PAYMENT_BOOK_STORE']['LOY_PAYMENT_DTL_STORE']['inBonusAmt']   = $bonusCardDiscountPercentAmount;
                $params['LOY_PAYMENT_BOOK_MAP_STORE']['LOY_PAYMENT_BOOK_STORE']['LOY_PAYMENT_DTL_STORE']['outBonusAmt']  = $bonusCardAmount;
            }
            
            if ($bonusCardBeginAmount < 1) {
                $bonusCardEndAmount = $bonusCardDiscountPercentAmount;
            }
            
        } else {
            $bonusCardNumber                = '00000000';
            $bonusCardDiscountPercent       = '0';
            $bonusCardBeginAmount           = '0';
            $bonusCardDiscountPercentAmount = '0';
            $bonusCardEndAmount             = '0';
        }
        
        if ($accountTransferAmt > 0) {
            
            $isInvInfo = true;
            
            $paymentDtl[] = array(
                'paymentTypeId' => 4, 
                'bankId'        => $paymentData['posAccountTransferBankId'],
                'amount'        => $accountTransferAmt
            );
            
            $nonCashAmount += $accountTransferAmt;
        }
        
        if ($mobileNetAmt > 0) {
            
            $isInvInfo = true;
            
            $paymentDtl[] = array(
                'paymentTypeId' => 3, 
                'bankId'        => $paymentData['posMobileNetBankId'],
                'amount'        => $mobileNetAmt
            );
            
            $nonCashAmount += $mobileNetAmt;
        }
        
        if ($barterAmt > 0) {
            
            $isInvInfo = true;
            
            $paymentDtl[] = array(
                'paymentTypeId' => 5, 
                'amount'        => $barterAmt
            );
            
            $nonCashAmount += $barterAmt;
        }
        
        if ($leasingAmt > 0) {
            
            $isInvInfo = true;
            
            $paymentDtl[] = array(
                'paymentTypeId' => 6, 
                'bankId'        => $paymentData['posLeasingBankId'],
                'amount'        => $leasingAmt
            );
            
            $nonCashAmount += $leasingAmt;
        }
        
        if ($empLoanAmt > 0) {
            
            $isInvInfo = true;
            
            $paymentDtl[] = array(
                'paymentTypeId' => 7, 
                'amount'        => $empLoanAmt
            );
            
            $nonCashAmount += $empLoanAmt;
        }
        
        if ($paymentData['invoiceId'] != '') {
            $params['META_DM_RECORD_MAP']['trgRecordId'] = $paymentData['invoiceId'];
        }
        
        if ($paymentData['serviceCustomerId'] != '') {
            
            $params['customerId'] = $paymentData['serviceCustomerId'];
            
        } elseif ($paymentData['newServiceCustomerJson'] != '') {
            
            $serviceCustomerResult = self::createServiceCustomer($paymentData['newServiceCustomerJson']);
                
            if ($serviceCustomerResult['status'] != 'success') {
                
                $response = array('status' => 'warning', 'message' => Lang::line('POS_0081') . ' (CRM). '.$serviceCustomerResult['message']);
                return $response;
                
            } elseif (isset($serviceCustomerResult['customerId'])) {
                
                $params['customerId'] = $serviceCustomerResult['customerId'];
            }
        }
        
        if (isset($isInvInfo)) {
            $params['refInvoiceNumber'] = Input::param($paymentData['invInfoInvoiceNumber']);
            $params['refBookNumber']    = Input::param($paymentData['invInfoBookNumber']);
            $params['customerName']     = Input::param($paymentData['invInfoCustomerName']);
            $params['customerRegNumber']= Input::param($paymentData['invInfoCustomerRegNumber']);
            $params['phoneNumber']      = Input::param($paymentData['invInfoPhoneNumber']);
            $params['description']      = Input::param($paymentData['invInfoTransactionValue']);
        }
        
        $params['SM_SALES_PAYMENT_DV'] = $paymentDtl;
        
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'POS_SM_SALES_INVOICE_HEADER_001', $params);
        
        if ($result['status'] == 'success') {
            
            $cssContent     = file_get_contents(BASEPATH . 'assets/custom/css/pos/print.css');
            
            $invoiceResult  = $result['result'];
            $invoiceId      = $invoiceResult['id'];
            
            $bonusCardCustomerResult = self::createBonusCardCustomer($paymentData['newCardCustomerJson']);
                
            if ($bonusCardCustomerResult['status'] != 'success') {

                $this->ws->runResponse(self::$gfServiceAddress, 'POS_SM_SALES_INVOICE_HEADER_005', array('id' => $invoiceId));

                $response = array('status' => 'warning', 'message' => Lang::line('POS_0081') . ' (CARD). '.$bonusCardCustomerResult['message']);

                return $response;
            }

            $sdmDeliveryResult = self::createSdmDelivery($invoiceId, $headerParams, $paymentData, $deliveryDtl, $noDeliveryDtl);

            if ($sdmDeliveryResult['status'] != 'success') {
                
                $this->ws->runResponse(self::$gfServiceAddress, 'POS_SM_SALES_INVOICE_HEADER_005', array('id' => $invoiceId));
                
                if (isset($bonusCardCustomerResult['membershipId']) && !empty($bonusCardCustomerResult['membershipId'])) {
                    
                    $this->ws->runResponse(self::$gfServiceAddress, 'POS_LOY_LOYALTY_MEMBERSHIP_005', array('id' => $bonusCardCustomerResult['membershipId']));
                }

                $response = array('status' => 'warning', 'message' => Lang::line('POS_0097') . ' '.$sdmDeliveryResult['message']);

                return $response;
            }
            
            $voucherContent = '';
            
            if ($voucherDtl) {

                $voucherTemplate = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/voucher/template1.html');
                $activedVouchers = array();
                
                foreach ($voucherDtl as $voucherRow) {

                    $getVoucker = self::getVouckerListModel(1, $voucherRow['amount'], $storeId);

                    if ($getVoucker) {

                        $voucherDurationDay = $getVoucker['durationday'];
                        $voucherExpireDate  = Date::weekdayAfter('Y-m-d H:i:s', $currentDate, '+'.$voucherDurationDay.' days');

                        $voucherReplacing = array(
                            '{voucherAmount}'       => self::posAmount($getVoucker['amount']), 
                            '{voucherPureAmount}'   => $getVoucker['amount'], 
                            '{voucherSerialNumber}' => $getVoucker['serialnumber'],
                            '{refNumber}'           => $refNumber,
                            '{voucherYear}'         => Date::formatter($voucherExpireDate, 'Y'), 
                            '{voucherMonth}'        => Date::formatter($voucherExpireDate, 'm'), 
                            '{voucherDay}'          => Date::formatter($voucherExpireDate, 'd')
                        );

                        $voucherContent .= strtr($voucherTemplate, $voucherReplacing);

                        $updateVoucherParams = array(
                            'id'                => $getVoucker['id'], 
                            'activationDate'    => $currentDate, 
                            'expiredDate'       => $voucherExpireDate, 
                            'activatedSalesInvoiceDtlId' => $invoiceResult['pos_sm_sales_invoice_detail'][$voucherRow['rowIndex']]['id']
                        );

                        self::updateVoucher($updateVoucherParams);
                        
                        $activedVouchers[] = $updateVoucherParams;
                        
                    } else {
                        
                        if (count($activedVouchers) > 0) {
                            foreach ($activedVouchers as $av => $activedVoucher) {
                                self::updateVoucherInactive($activedVoucher);
                            }
                        }
                        
                        $this->ws->runResponse(self::$gfServiceAddress, 'POS_SM_SALES_INVOICE_HEADER_005', array('id' => $invoiceId));
                        
                        $response = array(
                            'status' => 'warning', 
                            'message' => Lang::lineVar('POS_0099', array('vaucher' => number_format($voucherRow['amount'], 2, '.', ',')))
                        );

                        return $response;
                    }
                }
            }
            
            $vatNumber      = Session::get($sPrefix.'vatNumber');
            $districtCode   = Session::get($sPrefix.'posDistrictCode');
            $posBillType    = $paymentData['posBillType'];
            $isDouble       = true;
            
            $cashCode       = Session::get($sPrefix.'cashRegisterCode');
            $reportMonth    = '';
            $customerNo     = '';
            $orgRegNumber   = '';
            $orgName        = '';
            
            if ($posBillType == 'person') {
                
                $billType   = 1;
                $title      = Lang::line('POS_0100');
                
            } elseif ($posBillType == 'organization') {
                
                $billType       = 3;
                $title          = Lang::line('POS_0101');
                
                $orgRegNumber   = $paymentData['orgRegNumber'];
                $customerNo     = '0000039'; //$orgRegNumber;
                $orgName        = $paymentData['orgName'];
                
            } else {
                $billType       = 5;
                $title          = Lang::line('POS_0102');
            }
        
            $jsonParam = "{
                'amount': '" . sprintf("%.2f", $totalAmount) . "',
                'vat': '" . sprintf("%.2f", $vatAmount) . "',
                'cashAmount': '" . sprintf("%.2f", ($cashAmount - $changeAmount)) . "',
                'nonCashAmount': '" . sprintf("%.2f", $nonCashAmount) . "',
                'cityTax': '" . sprintf("%.2f", $cityTaxAmount) . "',
                'districtCode': '" . $districtCode . "',
                'posNo': '" . $cashCode . "',
                'reportMonth': '" . $reportMonth . "',
                'customerNo': '" . $customerNo . "',
                'billType': '" . $billType . "',
                'taxType': '1',
                'billIdSuffix': '',
                'returnBillId': '',
                'stocks': [
                    " . rtrim($stocks, ', ') . "
                ]
            }";
            
            $posApiArray    = self::posApiFunction($jsonParam);
            
            $warningMsg     = $posApiArray['warningMsg'];
            $billId         = isset($posApiArray['billId']) ? $posApiArray['billId'] : null;
            
            if ($billId) {
                
                $storeName      = Session::get($sPrefix.'storeName');
                $cashierName    = Session::get($sPrefix.'cashierName');
                $contactInfo    = Session::get($sPrefix.'posContactInfo');
                $topTitle       = Session::get($sPrefix.'posHeaderName');
                $printCopies    = (int) $paymentData['posPrintCopies'];
                $salesPersonCode= self::getSalesPersonCode($itemData);
                
                if ($voucherUsedDtl) {
                    
                    foreach ($voucherUsedDtl as $voucherUsedRow) {
                        
                        $updateVoucherUsedParams = array(
                            'id'                     => $voucherUsedRow['id'], 
                            'usedDate'               => $currentDate, 
                            'usedStoreId'            => $storeId, 
                            'usedCashierId'          => $cashierId, 
                            'usedSalesInvoiceBookId' => $invoiceId 
                        );

                        self::updateVoucherUsed($updateVoucherUsedParams);
                    }
                }
                
                if ($billType == 1) { // Хувь хүн
                    
                    $templateContent    = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/person/single.html');
                    $qrLotteryTemplate  = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/person/qrcode-lottery.html');
                    
                    $templateContent = str_replace('{qrCodeLottery}', $qrLotteryTemplate, $templateContent);
                    
                    $lottery         = $posApiArray['lottery'];
                    $qrData          = $posApiArray['qrData'];
                    
                    $replacing = array(
                        '{companyName}'     => $topTitle,
                        '{title}'           => '', 
                        '{vatNumber}'       => $vatNumber,
                        '{contactInfo}'     => $contactInfo,
                        '{ddtd}'            => $billId,
                        '{date}'            => Date::formatter($currentDate, 'Y/m/d'),
                        '{time}'            => Date::formatter($currentDate, 'H:i:s'),
                        '{refNumber}'       => $refNumber,
                        '{invoiceNumber}'   => $invoiceNumber,
                        '{storeName}'       => $storeName,
                        '{cashierName}'     => $cashierName,
                        '{cashCode}'        => $cashCode, 
                        '{salesPersonCode}' => $salesPersonCode, 
                        '{salesWaiter}'     => Input::post('waiterText', ''),
                        '{orderTime}'       => Input::post('posEshopOrderTime', ''), 
                        '{itemList}'        => $itemPrintList,
                        '{totalAmount}'     => self::posAmount($totalAmount),
                        '{noVatAmount}'     => self::posAmount($noVatAmount),
                        '{vatAmount}'       => self::posAmount($vatAmount),
                        '{paidAmount}'      => self::posAmount($cashAmount),
                        '{changeAmount}'    => self::posAmount($changeAmount),
                        '{cardAmount}'      => self::posAmount($bankAmount),
                        '{lottery}'         => $lottery,
                        '{qrCode}'          => self::getQrCodeImg($qrData),
                        //'{barCode}'         => self::getBarCodeImg($billId),
                        '{giftList}'        => self::giftTableRender($giftList), 
                        '{totalItemCount}'  => $totalItemCount, 
                        
                        '{bonusCardNumber}'         => $bonusCardNumber, 
                        '{bonusCardDiscountPercent}'=> $bonusCardDiscountPercent,
                        '{bonusCardBeginAmount}'    => self::posAmount($bonusCardBeginAmount), 
                        '{bonusCardDiffAmount}'     => self::posAmount($bonusCardAmount), 
                        '{bonusCardPlusAmount}'     => self::posAmount($bonusCardDiscountPercentAmount), 
                        '{bonusCardEndAmount}'      => self::posAmount($bonusCardEndAmount)
                    );
                    
                    $templateContent .= $voucherContent;
                    
                    if ($isDouble) {
                        
                        $internalContent = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/person/internal.html');
                        $internalContent = strtr($internalContent, $replacing);
                        
                        if ($printCopies > 1) {
                            $internalContent = str_repeat($internalContent, $printCopies);
                        }
                        
                        $templateContent .= $internalContent;
                    }
                    
                } elseif ($billType == 3) { // Байгууллага
                    
                    $templateContent = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/organization/single.html');
                    $qrData          = $posApiArray['qrData'];
                    
                    $replacing = array(
                        '{companyName}'     => $topTitle,
                        '{title}'           => '', 
                        '{vatNumber}'       => $vatNumber,
                        '{contactInfo}'     => $contactInfo,
                        '{ddtd}'            => $billId,
                        '{date}'            => Date::formatter($currentDate, 'Y/m/d'),
                        '{time}'            => Date::formatter($currentDate, 'H:i:s'),
                        '{refNumber}'       => $refNumber,
                        '{invoiceNumber}'   => $invoiceNumber,
                        '{storeName}'       => $storeName,
                        '{cashierName}'     => $cashierName,
                        '{cashCode}'        => $cashCode, 
                        '{salesPersonCode}' => $salesPersonCode, 
                        '{salesWaiter}'     => Input::post('waiterText', ''),
                        '{orderTime}'       => Input::post('posEshopOrderTime', ''), 
                        '{itemList}'        => $itemPrintList,
                        '{totalAmount}'     => self::posAmount($totalAmount),
                        '{noVatAmount}'     => self::posAmount($noVatAmount),
                        '{vatAmount}'       => self::posAmount($vatAmount),
                        '{paidAmount}'      => self::posAmount($cashAmount),
                        '{changeAmount}'    => self::posAmount($changeAmount),
                        '{cardAmount}'      => self::posAmount($bankAmount),
                        '{customerNumber}'  => $orgRegNumber, 
                        '{customerName}'    => $orgName, 
                        '{qrCode}'          => self::getQrCodeImg($qrData),
                        '{giftList}'        => self::giftTableRender($giftList), 
                        '{totalItemCount}'  => $totalItemCount, 
                        
                        '{bonusCardNumber}'         => $bonusCardNumber, 
                        '{bonusCardDiscountPercent}'=> $bonusCardDiscountPercent,
                        '{bonusCardBeginAmount}'    => self::posAmount($bonusCardBeginAmount), 
                        '{bonusCardDiffAmount}'     => self::posAmount($bonusCardAmount), 
                        '{bonusCardPlusAmount}'     => self::posAmount($bonusCardDiscountPercentAmount), 
                        '{bonusCardEndAmount}'      => self::posAmount($bonusCardEndAmount)
                    );
                    
                    $templateContent .= $voucherContent;
                    
                    if ($isDouble) {
                        
                        $internalContent = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/organization/internal.html');
                        $internalContent = strtr($internalContent, $replacing);
                        
                        if ($printCopies > 1) {
                            $internalContent = str_repeat($internalContent, $printCopies);
                        }
                        
                        $templateContent .= $internalContent;
                    }
                }

                $templateContent = strtr($templateContent, $replacing);
                
                $billResultParams = array(
                    'billid'            => $billId, 
                    'salesInvoiceId'    => $invoiceId, 
                    'merchantId'        => $posApiArray['merchantId'], 
                    'vatDate'           => $posApiArray['date'], 
                    'success'           => $posApiArray['success'], 
                    'warningMsg'        => $warningMsg,  
                    'sendJson'          => $jsonParam, 
                    'storeId'           => $storeId, 
                    'customerNumber'    => $orgRegNumber, 
                    'customerName'      => $orgName, 
                    'isRemoved'         => 0, 
                    'macaddress'        => ''
                );
                
                $billDataResult = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'POS_SM_BILL_RESULT_DATA_001', $billResultParams);
                
                if ($billDataResult['status'] == 'error') {
                    file_put_contents('log/pos_bill.log', $billDataResult['text'], FILE_APPEND);
                }
                
                $newInvoiceNumber = self::getBillNumModel();
                $response = array('status' => 'success', 'css' => $cssContent, 'printData' => $templateContent, 'billNumber' => $newInvoiceNumber);
                
            } else {
                
                $this->ws->runResponse(self::$gfServiceAddress, 'POS_SM_SALES_INVOICE_HEADER_005', array('id' => $invoiceId));
                
                if (isset($sdmDeliveryResult['deliveryBookIds']) && !empty($sdmDeliveryResult['deliveryBookIds'])) {
                    
                    foreach ($sdmDeliveryResult['deliveryBookIds'] as $dk => $deliveryBookId) {
                        $this->ws->runResponse(self::$gfServiceAddress, 'POS_SDM_DELIVERY_005', array('id' => $deliveryBookId));
                    }
                }
                
                if (isset($bonusCardCustomerResult['membershipId']) && !empty($bonusCardCustomerResult['membershipId'])) {
                    
                    $this->ws->runResponse(self::$gfServiceAddress, 'POS_LOY_LOYALTY_MEMBERSHIP_005', array('id' => $bonusCardCustomerResult['membershipId']));
                }
                
                if (!$warningMsg) {
                    $warningMsg = $posApiArray['message'];
                }
                
                $response = array('status' => 'warning', 'message' => $warningMsg);
            }

        } else {
            $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
        
        return $response;
    }
    
    public function updateVoucher($params) {
        
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'ACTIVATION_VOUCHER_DV_001', $params);
        
        if ($result['status'] == 'success') {
            return true;
        } 
        return false;
    }
    
    public function updateVoucherByAmount($params) {
        
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'UPDATE_ACTIVATION_VOUCHER_DV_001', $params);
        
        if ($result['status'] == 'success') {
            return true;
        } 
        return false;
    }
    
    public function updateVoucherUsed($params) {
        
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'USED_VOUCHER_DV_001', $params);
        
        if ($result['status'] == 'success') {
            return true;
        } 
        return false;
    }
    
    public function updateVoucherInactive($params) {
        
        $params['activationDate'] = '';
        $params['expiredDate'] = '';
        $params['activatedSalesInvoiceDtlId'] = '';
        
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'ACTIVATION_VOUCHER_DV_001', $params);
        
        if ($result['status'] == 'success') {
            return true;
        } 
        return false;
    }
    
    public function updateAdditionalVoucherActived($params) {
        
        foreach ($params as $param) {
            $this->ws->runSerializeResponse(self::$gfServiceAddress, 'OTHER_ACTIVATION_VOUCHER_DV_001', $param);
        }
        
        return true;
    }
    
    public function createBonusCardCustomer($jsonStr) {
        
        if ($jsonStr != '') {
            
            $params = json_decode($jsonStr, true);
            
            $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'POS_LOY_LOYALTY_MEMBERSHIP_001', $params);

            if ($result['status'] == 'success') {
                
                $response = array(
                    'status'        => 'success', 
                    'membershipId'  => $result['result']['id'], 
                    'cardCustomerId'=> $result['result']['crm_customer']['id']
                );
                
                if (isset($params['selectedCustomerId']) && $params['selectedCustomerId'] != '') {
                    $response['selectedCustomerId'] = $params['selectedCustomerId'];
                }
                
                if (isset($params['cardId']) && $params['cardId'] != '') {
                    $response['cardId'] = $params['cardId'];
                }
                
                return $response;
                
            } else {
                return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
            }
        }
        
        return array('status' => 'success');
    }
    
    public function createServiceCustomer($jsonStr) {
        
        if ($jsonStr != '') {
            
            $params = json_decode($jsonStr, true);
            
            $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'CRM_CUSTOMER_LIST_001', $params);

            if ($result['status'] == 'success') {
                return array('status' => 'success', 'customerId' => $result['result']['id']);
            } else {
                return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
            }
        }
        
        return array('status' => 'success');
    }
    
    public function createSdmDelivery($invoiceId, $headerParams, $paymentData, $deliveryDtl, $noDeliveryDtl) {
        
        if (Config::getFromCache('CONFIG_POS_DELIVERY')) {
            $deliveryDtlCount   = count($deliveryDtl);
            $noDeliveryDtlCount = count($noDeliveryDtl);

            if ($deliveryDtlCount > 0 || $noDeliveryDtlCount > 0) {

                unset($headerParams['bookTypeId']);
                unset($headerParams['wfmStatusId']);

                $deliveryBookIds = array();
                $params = $headerParams;

                $params['META_DM_RECORD_MAP'] = array('srcRecordId' => $invoiceId);

                if ($noDeliveryDtlCount > 0) {

                    $params['invoiceNumber'] = self::getPosInvoiceNumber('1522725636915');

                    $renameNoDeliveryDtl = array();

                    foreach ($noDeliveryDtl as $noDeliveryDtlRow) {

                        if (issetParam($noDeliveryDtlRow['isoperating']) != '1') {
                            if (isset($noDeliveryDtlRow['POS_SM_SALES_INVOICE_DETAIL'])) {
                                unset($noDeliveryDtlRow['POS_SM_SALES_INVOICE_DETAIL']);
                            }

                            if (isset($noDeliveryDtlRow['SM_SALES_PAYMENT_DV'])) {
                                unset($noDeliveryDtlRow['SM_SALES_PAYMENT_DV']);
                            }

                            $renameNoDeliveryDtl[] = $noDeliveryDtlRow;
                        }
                    }

                    $params['POS_SDM_SALES_ORDER_ITEM_DTL'] = $renameNoDeliveryDtl;

                    $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'POS_SDM_DELIVERY_001', $params);

                    if ($result['status'] == 'success') {
                        $deliveryBookIds[] = $result['result']['id'];
                    }
                }

                if ($deliveryDtlCount > 0) {

                    $params['invoiceNumber'] = self::getPosInvoiceNumber('1522725636915');

                    $renameDeliveryDtl = array();

                    foreach ($deliveryDtl as $deliveryDtlRow) {

                        if (issetParam($deliveryDtlRow['isoperating']) != '1') {
                            if (isset($deliveryDtlRow['POS_SM_SALES_INVOICE_DETAIL'])) {

                                //$deliveryDtlRow['POS_SDM_SALES_ORDER_ITEM_DTL'] = $deliveryDtlRow['POS_SM_SALES_INVOICE_DETAIL'];

                                unset($deliveryDtlRow['POS_SM_SALES_INVOICE_DETAIL']);
                            }

                            if (isset($deliveryDtlRow['SM_SALES_PAYMENT_DV'])) {
                                unset($deliveryDtlRow['SM_SALES_PAYMENT_DV']);
                            }

                            $renameDeliveryDtl[] = $deliveryDtlRow;
                        }
                    }

                    $params['POS_SDM_SALES_ORDER_ITEM_DTL'] = $renameDeliveryDtl;

                    $params['NEXT_SDM_DELIVERY_BOOK'] = array(
                        'cityId'        => $paymentData['cityId'], 
                        'districtId'    => $paymentData['districtId'], 
                        'cityStreetId'  => $paymentData['streetId'], 
                        'what3words'    => $paymentData['what3words'], 
                        'coordinate'    => issetParam($paymentData['coordinate']), 
                        'timeRangeId'   => $paymentData['timeZoneId'], 
                        'contactName'   => $paymentData['recipientName'], 
                        'address'       => $paymentData['detailAddress'], 
                        'phoneNumber1'  => $paymentData['phone1'], 
                        'phoneNumber2'  => $paymentData['phone2'], 
                        'dueDate'       => $paymentData['dueDate'], 
                        'description'   => $paymentData['descriptionAddress'] 
                    );

                    $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'POS_SDM_DELIVERY_001', $params);

                    if ($result['status'] == 'success') {
                        $deliveryBookIds[] = $result['result']['id'];
                    }
                }

                if ($result['status'] == 'success') {
                    return array('status' => 'success', 'deliveryBookIds' => $deliveryBookIds);
                } else {
                    return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
                }
            }
        }
        
        return array('status' => 'success');
    }
    
    public function createToService($invoiceId, $headerParams, $paymentData, $serviceDtl) {

        if (count($serviceDtl) > 0) {
            
            $concatAddress = $paymentData['detailAddress'].'|'.$paymentData['descriptionAddress'].'|'.$paymentData['recipientName'].'|'.$paymentData['phone1'].'|'.$paymentData['phone2'].'|'.$paymentData['dueDate'];
            
            $params = array(
                'bookNumber' => self::getPosInvoiceNumber('1515406460786'), 
                'customerId' => $headerParams['customerId'], 
                'cityId'     => $paymentData['cityId'], 
                'districtId' => $paymentData['districtId'], 
                'streetId'   => $paymentData['streetId'], 
                'address'    => $concatAddress, 
                'RPR_DTL'    => $serviceDtl, 
                'META_DM_RECORD_MAP' => array(
                    'trgRecordId' => $invoiceId
                )
            );

            $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'POS_TO_SERVICE_PROCESS', $params);

            if ($result['status'] == 'success') {
                return array('status' => 'success');
            } else {
                return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
            }
            
        } else {
            return array('status' => 'success');
        }
    }
    
    public function createSejim($invoiceId, $headerParams, $paymentData) {
        $params = array(
            'id'           => $paymentData['sejimId'], 
            'lastName'     => $paymentData['sejimLastName'], 
            'firstName'    => $paymentData['sejimFirstName'], 
            'phoneNumber'  => $paymentData['sejimPhoneNumber'], 
            'email'        => $paymentData['sejimEmail'], 
            'ageRange'     => $paymentData['sejimAgeRange'], 
            'genderId'     => $paymentData['sejimGenderId'], 
            'salesInvoiceId'=> $invoiceId, 
            'stLeadAndSalesMap_DV' => array(
                'srcRecordId ' => $invoiceId
            )
        );

        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, Config::getFromCache('POS_PAY_LEFT_SIDE_SHOW_LEAD'), $params);

        if ($result['status'] == 'success') {
            return array('status' => 'success');
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
    }
    
    public function getPosInvoiceNumber($objectId, $attr = array()) {
        
        $param = array(
            'objectId' => $objectId
        );
        
        if ($attr) {
            $param = array_merge($param, $attr);
        }

        $result = $this->ws->runResponse(self::$gfServiceAddress, 'CRM_AUTONUMBER_BP', $param);
        
        return $this->ws->getValue($result['result']);
    }
    
    public function getPosInvoiceRefNumber($storeId, $cashRegisterId) {
        
        $param = array(
            'objectId'       => '1522725721739', 
            'storeId'        => $storeId, 
            'cashRegisterId' => $cashRegisterId
        );

        $result = $this->ws->runResponse(self::$gfServiceAddress, 'CRM_AUTONUMBER_BP', $param);
        
        $refNumber = $this->ws->getValue($result['result']);
        
        if ($refNumber) {
            return $refNumber;
        } else {
            self::$refNumberCallCount++;
            
            if (self::$refNumberCallCount <= 3) {
                sleep(1);
                return self::getPosInvoiceRefNumber($storeId, $cashRegisterId);
            } else {
                return getUID();
            }
        }
    }
    
    public function getInvoiceWfmStatusId($invoiceId) {
        $wfmStatusId = $this->db->GetOne("SELECT WFM_STATUS_ID FROM SM_SALES_INVOICE_HEADER WHERE SALES_INVOICE_ID = $invoiceId");
        return $wfmStatusId;
    }
    
    public function updateSalesInvoiceHeaderCardId($invoiceId, $bonusCardCustomerResult) {
        
        if (isset($bonusCardCustomerResult['cardId']) && $bonusCardCustomerResult['cardId'] != '') {
            $this->db->AutoExecute('SM_SALES_INVOICE_HEADER', array('CARD_ID' => $bonusCardCustomerResult['cardId']), 'UPDATE', 'SALES_INVOICE_ID = '.$invoiceId);
        }
        
        return true;
    }
    
    public function updateTalonInvoiceUsed($invoiceId) {
        
        if ($invoiceId != '') {
            $this->db->AutoExecute('SDM_ORDER_BOOK', array('IS_USED' => 1), 'UPDATE', 'SALES_ORDER_ID = '.$invoiceId);
        }
        
        return true;
    }
    
    public function updateBankBillingUsed($atBankBillingIds) {
        
        if ($atBankBillingIds && count($atBankBillingIds)) {
            $this->db->AutoExecute('CM_BANK_BILLING', array('IS_USE_POS' => 1), 'UPDATE', 'ID IN ('.Arr::implode_r(',', $atBankBillingIds, true).')');
        }
        
        return true;
    }

    public function deleteSalesInvoice($invoiceId, $bonusCardCustomerResult, $serviceCustomerId) {
        
        try {
            $this->db->Execute("DELETE FROM LOY_PAYMENT_DTL WHERE BOOK_ID IN (SELECT B.ID FROM LOY_PAYMENT_BOOK_MAP M INNER JOIN LOY_PAYMENT_BOOK B ON M.PAYMENT_BOOK_ID = B.ID WHERE M.SALES_INVIOCE_ID = $invoiceId)");
            $this->db->Execute("DELETE FROM LOY_PAYMENT_BOOK WHERE ID IN (SELECT M.PAYMENT_BOOK_ID FROM LOY_PAYMENT_BOOK_MAP M WHERE M.SALES_INVIOCE_ID = $invoiceId)");
            
            if (isset($serviceCustomerId) && $serviceCustomerId != '') {
                $this->db->Execute("DELETE FROM CRM_CUSTOMER_DTL WHERE CUSTOMER_ID = $serviceCustomerId");
                $this->db->Execute("DELETE FROM CRM_CUSTOMER_ADDRESS WHERE CUSTOMER_ID = $serviceCustomerId");
                $this->db->Execute("DELETE FROM CRM_CUSTOMER WHERE CUSTOMER_ID = $serviceCustomerId");
            }
            
            if (isset($bonusCardCustomerResult['membershipId']) && !empty($bonusCardCustomerResult['membershipId'])) {
                        
                $cardMembershipId = $bonusCardCustomerResult['membershipId'];

                if (isset($bonusCardCustomerResult['selectedCustomerId']) && $bonusCardCustomerResult['selectedCustomerId'] != '') {
                    $this->db->Execute("DELETE FROM LOY_LOYALTY_MEMBERSHIP WHERE MEMBERSHIP_ID = $cardMembershipId");
                } else {

                    $cardCustomerId = $bonusCardCustomerResult['cardCustomerId'];
                    
                    $this->db->Execute("DELETE FROM LOY_LOYALTY_MEMBERSHIP WHERE MEMBERSHIP_ID = $cardMembershipId");
                    $this->db->Execute("DELETE FROM CRM_CUSTOMER_DTL WHERE CUSTOMER_ID = $cardCustomerId");
                    $this->db->Execute("DELETE FROM CRM_CUSTOMER_ADDRESS WHERE CUSTOMER_ID = $cardCustomerId");
                    $this->db->Execute("DELETE FROM CRM_CUSTOMER WHERE CUSTOMER_ID = $cardCustomerId");
                }

                //$this->ws->runResponse(self::$gfServiceAddress, 'POS_LOY_LOYALTY_MEMBERSHIP_005', array('id' => $bonusCardCustomerResult['membershipId']));
            }
            
            $this->db->Execute("
                DELETE 
                FROM SDM_SALES_ORDER_ITEM_DTL 
                WHERE SALES_ORDER_ID IN (
                    SELECT 
                        M.TRG_RECORD_ID
                    FROM META_DM_RECORD_MAP M
                        INNER JOIN SM_SALES_INVOICE_HEADER H ON M.SRC_RECORD_ID = H.SALES_INVOICE_ID
                    WHERE M.SEMANTIC_TYPE_ID = 305 AND H.SALES_INVOICE_ID = $invoiceId 
                )");
            
            $this->db->Execute("
                DELETE 
                FROM SDM_ORDER_BOOK 
                WHERE SALES_ORDER_ID IN (
                    SELECT 
                        M.TRG_RECORD_ID
                    FROM META_DM_RECORD_MAP M 
                        INNER JOIN SM_SALES_INVOICE_HEADER H ON M.SRC_RECORD_ID = H.SALES_INVOICE_ID
                    WHERE M.SEMANTIC_TYPE_ID = 305 AND H.SALES_INVOICE_ID = $invoiceId 
                )");
            
            $this->db->Execute("DELETE FROM META_DM_RECORD_MAP WHERE SEMANTIC_TYPE_ID = 304 AND SRC_RECORD_ID = $invoiceId");
            
            $this->ws->runResponse(self::$gfServiceAddress, 'POS_SM_SALES_INVOICE_HEADER_005', array('id' => $invoiceId));
        } catch (ADODB_Exception $ex) {
            return true;
        } catch (Exception $e) {
            return true;
        }        
        
        return true;
    }
    
    public function posApiFunction($jsonParam) {
        
        $vatNumber = Mdpos::$eVatNumber.'\\'.Mdpos::$eStoreCode.'\\'.Mdpos::$eCashRegisterCode;

        $data = array(
            'function'  => 'put', 
            'vatNumber' => $vatNumber, 
            'jsonParam' => $jsonParam
        );
        $response = $this->ws->redirectPost(Mdpos::getPosApiServiceAddr(), $data);
        
        return json_decode($response, true);
    }
    
    public function posApiReturnBillFunction($jsonParam) {
        
        $sPrefix = SESSION_PREFIX;
        $vatNumber = Session::get($sPrefix.'vatNumber').'\\'.Session::get($sPrefix.'storeCode').'\\'.Session::get($sPrefix.'cashRegisterCode');
        
        $data = array(
            'function'  => 'returnBill', 
            'vatNumber' => $vatNumber, 
            'jsonParam' => $jsonParam
        );
        $response = $this->ws->redirectPost(Mdpos::getPosApiServiceAddr(), $data);
        
        return json_decode($response, true);
    }
    
    public function posApiCallFunction($jsonParam) {
        
        $sPrefix = SESSION_PREFIX;
        $vatNumber = Session::get($sPrefix.'vatNumber').'\\'.Session::get($sPrefix.'storeCode').'\\'.Session::get($sPrefix.'cashRegisterCode');
        
        $data = array(
            'function'  => 'callfunction', 
            'vatNumber' => $vatNumber, 
            'jsonParam' => $jsonParam
        );
        $response = $this->ws->redirectPost(Mdpos::getPosApiServiceAddr(), $data);
        $response = str_replace('"', '', $response);
        
        return $response;
    }    
    
    public function getSalesPersonCode($itemData) {
        
        $result = '';
        
        if (isset($itemData['employeeId_displayField'])) {
            $array = array();
            
            foreach ($itemData['employeeId_displayField'] as $k => $v) {
                if ($v != '') {
                    $array[$v] = $v;
                }
            }
            
            if ($array) {
                $result = Lang::line('POS_0161') . ': '.implode(', ', $array);
            }
        }
        
        return $result;
    }
    
    public function generateItemRow($arr = array()) {
        
        //overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        
        $deliveryIcon = ($arr['isDelivery'] == '1' ? '<img src="assets/core/global/img/ico/truck.gif" style="width: 15px"> ' : '');
        //$cityTaxLine  = $arr['cityTax'] > 0 ? 'text-decoration: underline;' : '';        
        $cityTaxLine  = '';        
        
        $row = '<tr>
            <td style="font-family: Tahoma; font-size: 10px; font-weight: normal; padding: 3px 0; text-align: left; line-height: 12px; vertical-align: bottom;'.$cityTaxLine.'">
                ' . $deliveryIcon . $arr['itemName'] . '
            </td>
            <td style="font-family: Tahoma; font-size: 12px; font-weight: normal; padding: 3px 0; text-align: right; vertical-align: bottom">
                ' . self::posAmount($arr['salePrice']) . '
            </td>
            <td style="font-family: Tahoma; font-size: 12px; font-weight: normal; padding: 3px 0; text-align: right; vertical-align: bottom">
                ' . self::posAmount($arr['itemQty']) . '
            </td>
            <td style="font-family: Tahoma; font-size: 12px; font-weight: normal; padding: 3px 0; text-align: right; vertical-align: bottom">
                ' . self::posAmount($arr['totalPrice']) . '
            </td>
        </tr>';
        
        return $row;
    }
    
    public function generatePackageItemRow2($groupName) {
        
        $row = '<tr>
            <td colspan="4" style="font-family: Tahoma; font-size: 10px; font-weight: bold; border-top:1px #000 dashed; border-bottom:1px #000 dashed; padding: 3px 0; text-align: left; line-height: 12px; vertical-align: bottom;">
                ' . $groupName . '
            </td>
        </tr>';
        
        return $row;
    }
    
    public function generatePackageItemRow($arr = array(), $groupName = null, $firstPackage = true) {    
        
        if ($groupName) {
            $row = '<tr>
                <td colspan="4" style="font-family: Tahoma; font-size: 10px; font-weight: normal; border-top:1px #000 dashed; border-bottom:1px #000 dashed; padding: 3px 0; text-align: left; line-height: 12px; vertical-align: bottom;">
                    ДДТД: ' . $groupName . '
                </td>
            </tr>';
            if (!$firstPackage) {
                $row .= '<tr>
                    <td style="width: 100%; border-top:1px #000 dashed; border-bottom:1px #000 dashed; font-family: Tahoma; font-size: 12px; font-weight: 600; padding: 2px 0; text-align: left;">
                        Барааны нэр
                    </td>
                    <td style="width: 65px; border-top:1px #000 dashed; border-bottom:1px #000 dashed; font-family: Tahoma; font-size: 12px; font-weight: 600; padding: 2px 0; text-align: right;">
                        Үнэ
                    </td>
                    <td style="width: 35px; border-top:1px #000 dashed; border-bottom:1px #000 dashed; font-family: Tahoma; font-size: 12px; font-weight: 600; padding: 2px 0; text-align: right;">
                        Тоо
                    </td>
                    <td style="width: 85px; border-top:1px #000 dashed; border-bottom:1px #000 dashed; font-family: Tahoma; font-size: 12px; font-weight: 600; padding: 2px 0; text-align: right;">
                        Дүн
                    </td>
                </tr>';            
            }
            
        } else {
            
            $deliveryIcon = ($arr['isDelivery'] == '1' ? '<img src="assets/core/global/img/ico/truck.gif" style="width: 15px"> ' : '');
            $cityTaxLine  = $arr['cityTax'] > 0 ? 'text-decoration: underline;' : '';    
        
            $row = '<tr>
                <td style="font-family: Tahoma; font-size: 10px; font-weight: normal; padding: 3px 0; text-align: left; line-height: 12px; vertical-align: bottom;'.$cityTaxLine.'">
                    ' . $deliveryIcon . $arr['itemName'] . '
                </td>
                <td style="font-family: Tahoma; font-size: 12px; font-weight: normal; padding: 3px 0; text-align: right; vertical-align: bottom">
                    ' . self::posAmount($arr['salePrice']) . '
                </td>
                <td style="font-family: Tahoma; font-size: 12px; font-weight: normal; padding: 3px 0; text-align: right; vertical-align: bottom">
                    ' . self::posAmount($arr['itemQty']) . '
                </td>
                <td style="font-family: Tahoma; font-size: 12px; font-weight: normal; padding: 3px 0; text-align: right; vertical-align: bottom">
                    ' . self::posAmount($arr['totalPrice']) . '
                </td>
            </tr>';
        }
        
        return $row;
    }
    
    public function generatePharmacyItemRow($arr = array()) {
        
        $row = '
        <tr>
            <td style="font-family: Tahoma; border-top: 1px #000 dashed; font-size: 12px; font-weight: normal; padding: 3px 0; text-align: left; line-height: 12px; vertical-align: top" colspan="5">
                '.$arr['itemName'].'
            </td>
        </tr>
        <tr>
            <td style="font-family: Tahoma; font-size: 12px; font-weight: normal; padding: 3px 0; text-align: left; vertical-align: top">
                ' . self::posAmount($arr['salePrice']) . '
            </td>
            <td style="font-family: Tahoma; font-size: 12px; font-weight: normal; padding: 3px 0; text-align: center; vertical-align: top">
                ' . self::posQty($arr['itemQty']) . '
            </td>
            <td style="font-family: Tahoma; font-size: 12px; font-weight: normal; padding: 3px 0; text-align: right; vertical-align: top">
                ' . self::posAmount($arr['totalPrice']) . '
            </td>
            <td style="font-family: Tahoma; font-size: 12px; font-weight: normal; padding: 3px 0; text-align: right; vertical-align: top">
                ' . self::posAmount($arr['unitReceivable']) . '
            </td>
            <td style="font-family: Tahoma; font-size: 12px; font-weight: normal; padding: 3px 0; text-align: right; vertical-align: top">
                ' . self::posAmount($arr['totalPrice'] - $arr['unitReceivable']) . '
            </td>
        </tr>';
        
        return $row;
    }
    
    public function getQrCodeImg($data, $height = '150px') {
        
        if($data == ''){return '';}
        
        includeLib('QRCode/qrlib');
        
        ob_start();
            
        QRcode::png($data, false, 'L', 6, 0);
        $imageData = ob_get_contents();

        ob_end_clean();        
        
        return '<img src="data:image/png;base64,'.base64_encode($imageData).'" style="height: '.$height.'">';
    }
    
    public function getQpayQrCodeImg($data, $height = '150px') {
        
        if($data == ''){return '';}
        
//        includeLib('QRCode/qrlib');
//        
//        ob_start();
//            
//        QRcode::png($data, false, 'L', 6, 0);
//        $imageData = ob_get_contents();
//
//        ob_end_clean();        
        
        return '<img src="data:image/png;base64,'.$data.'" style="height: '.$height.'">';
    }        
    
    public function getBarCodeImg($data) {
        
        if($data == ''){return '';}
        
        includeLib('Barcode/barcode');
        
        $barcode = new Barcode();

        return '<img src="'.$barcode->generate($data, '40', 'horizontal', 'code25', 'base64').'">';
    }
    
    public function getAdditionalVoucherListModel($storeId, $totalAmount) {
        
        $param = array(
            'systemMetaGroupId' => '1549880836624334',
            'ignorePermission' => 1, 
            'showQuery' => 0,
            'criteria' => array(
                'filterStoreId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $storeId
                    )
                ), 
                'lineTotalAmount' => array(
                    array(
                        'operator' => '=',
                        'operand' => $totalAmount
                    )
                )
            )
        );

        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($result['result']) && isset($result['result'][0])) {
            unset($result['result']['aggregatecolumns']);
            unset($result['result']['paging']);
            $data = $result['result'];
        } else {
            $data = null;
        }
            
        return $data;
    }
    
    public function getVoucherBySerialNumberModel() {
        
        $param = array(
            'systemMetaGroupId' => self::$getVoucherListDvId,
            'ignorePermission' => 1, 
            'showQuery' => 0,
            'criteria' => array(
                'filterSerialNumber' => array(
                    array(
                        'operator' => '=',
                        'operand' => Input::post('serialNumber')
                    )
                )
            )
        );
        
        if (Input::isEmpty('filterItemIds') == false) { 
            $param['criteria']['filterItemIds'][] = array('operator' => 'IN', 'operand' => Input::post('filterItemIds'));
        }
        
        if (Input::isEmpty('filterCustomerId') == false) { 
            $param['criteria']['filterCustomerId'][] = array('operator' => '=', 'operand' => Input::post('filterCustomerId'));
        }

        if (Input::isEmpty('storeId') == false) { 
            $param['criteria']['storeId'][] = array('operator' => '=', 'operand' => Input::post('storeId'));
        }

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result']) && isset($data['result'][0])) {
            
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);
            
            $row = $data['result'][0];

            $currentDate = Date::currentDate('Y-m-d');
            
            if ($row['useddate'] != '') {
                
                $response = array('status' => 'info', 'message' => Lang::lineVar('POS_0107', array('useddate' => $row['useddate'])));
                
            } elseif ($row['activationdate'] == '') {
                    
                $response = array('status' => 'info', 'message' => Lang::line('POS_0108'));

            } elseif ($row['expireddate'] == '') {
                    
                $response = array('status' => 'info', 'message' => Lang::line('POS_0109'));

            } else {
                
                if (Date::formatter($row['expireddate'], 'Y-m-d') < $currentDate) {
                    
                    $response = array('status' => 'info', 'message' => Lang::lineVar('POS_0110', array('expireddate' => $row['expireddate'])));
                    
                } else {
                    
                    $response = array(
                        'status'          => 'success', 
                        'amount'          => $row['amount'], 
                        'discountPercent' => $row['discountpercent'], 
                        'id'              => $row['id'], 
                        'typeId'          => $row['coupontypeid'], 
                        'beginamount'     => issetParamZero($row['beginamount']), 
                        'customername'    => issetParam($row['customername']), 
                        'lastname'        => issetParam($row['lastname']), 
                        'customerid'      => issetParam($row['customerid']), 
                        'customercode'    => issetParam($row['customercode']), 
                        'stateregnumber'  => issetParam($row['stateregnumber'])
                    );
                    
                    if (!$response['amount'] && !$response['discountPercent'] && Config::getFromCache('IS_POS_COUPON_AMOUNT_APPROVE_NEGATIVE_VALUE') !== '1') {
                        $response = array('status' => 'info', 'message' => Lang::line('POS_0723'));
                    } elseif ($response['amount'] < 0 && Config::getFromCache('IS_POS_COUPON_AMOUNT_APPROVE_NEGATIVE_VALUE') !== '1') {
                        $response = array('status' => 'info', 'message' => Lang::line('POS_0723'));
                    }
                }
            }
            
        } else {
            $response = array('status' => 'info', 'message' => Lang::line('POS_0111'));
        }
        
        return $response;
    }
    
    public function giftTableRender($giftList) {
        
        $html = '';
        
        if ($giftList != '') {
            
            $html = '
            <tr>
                <td style="padding: 0; border-top:1px #000 solid;">
                    <div style="height: 5px"></div>
                    <table border="0" width="100%" style="width: 100%; table-layout: fixed; font-family: Tahoma; font-size: 12px;">
                        <tbody>
                            <tr>
                                <td style="text-align: left; width: 100%; border-bottom:1px #000 dashed; font-weight: 600; padding: 2px 0;">'.Lang::line('POS_0037').'</td>
                                <td style="text-align: right; width: 35px; border-bottom:1px #000 dashed; font-weight: 600; padding: 2px 0;">Тоо</td>
                                <td style="text-align: right; width: 85px; border-bottom:1px #000 dashed; padding: 2px 0"></td>
                            </tr>
                            '.$giftList.'
                        </tbody>
                    </table>
                </td>
            </tr>';
        }
        
        return $html;
    }
    
    public function giftPrintRow($giftJsonRow) {
        
        $itemName = $giftJsonRow['itemname'];

        if ($giftJsonRow['coupontypename']) {
            
            $percentAmount = issetParam($giftJsonRow['couponpercentamount']);
            $amount        = $percentAmount ? $percentAmount : $giftJsonRow['couponamount'];
            $itemName      = $giftJsonRow['coupontypename'].' - '.self::posAmount($amount).'₮';
            
        } elseif ($itemName == '') {
            
            $itemName = $giftJsonRow['jobname'];
        }
        
        $deliveryIcon = ($giftJsonRow['isDelivery'] == '1' ? '<img src="assets/core/global/img/ico/truck.gif" style="width: 15px"> ' : '');
                                                    
        $html = '<tr>';
        $html .= '<td style="text-align: left; padding: 3px 0; font-size: 10px; line-height: 12px; vertical-align: bottom">'.$deliveryIcon.$itemName.'</td>';
        $html .= '<td style="text-align: right; padding: 3px 0; vertical-align: bottom">'.$giftJsonRow['invoiceqty'].'</td>';
        $html .= '<td style="padding: 3px 0;"></td>';
        $html .= '</tr>';
        
        return $html;
    }
    

    public function upointTableRender($cardNumber, $balance, $point, $lastBalance, $addPoint) {
        
        $html = '
        <tr>
            <td style="padding: 0; border-top: 1px #000 solid; color: #ffffff"></td>
        </tr>            
        <tr>
            <td style="padding: 0;">
                <div style="height: 5px"></div>
                <table border="0" width="100%" style="width: 100%; table-layout: fixed; font-family: Tahoma; font-size: 12px;">
                    <tbody>
                        <tr>                                                        
                            <td style="padding: 0;font-weight: 600; text-align: right;width:100px">U-point карт №</td>
                            <td colspan="2" style="padding: 0;text-align: left;width:200px"> '.substr_replace($cardNumber, '********', 4, 8).'</td>
                            <td style="text-align: right; padding: 0; width: 100%;"><img src="assets/core/global/img/ico/upoint.png" style="width: 60px"></td>
                        </tr>
                        <tr>
                            <td style="text-align: right; padding: 0; width: 100%;">Эхний үлдэгдэл</td>
                            <td style="padding: 0;text-align: right;width:100px">'.self::posAmount($balance).'</td>
                            <td style="text-align: right; padding: 0; width: 100%;">Хасагдах</td>
                            <td style="padding: 0;text-align: right;width:100px">-'.self::posAmount($point).'</td>
                        </tr>
                        <tr>
                            <td style="text-align: right; padding: 0; width: 100%;">Нэмэгдэх</td>
                            <td style="padding: 0;text-align: right;width:100px">+'.self::posAmount($addPoint).'</td>
                            <td style="text-align: right; padding: 0; width: 100%;">Эцсийн үлдэгдэл</td>
                            <td style="padding: 0;text-align: right;width:100px">'.self::posAmount($lastBalance).'</td>
                        </tr>
                    </tbody>
                </table>
                <div style="height: 5px"></div>
            </td>
        </tr>';
        
        return $html;
    }    
    
    public function paymentDetailTemplate() {
        $template = '<tr>
            <td style="text-align: right; padding: 0; width: 100%;">{labelName}:</td>
            <td style="text-align: right; padding: 0; width: 85px;">{amount}</td>
        </tr>';
        
        return $template;
    }
    
    public function getCardNumberModel() {
        
        $param = array(
            'systemMetaGroupId' => self::$getBonusCardListDvId,
            'ignorePermission' => 1, 
            'showQuery' => 0,
            'criteria' => array(
                'cardNumber' => array(
                    array(
                        'operator' => '=',
                        'operand' => Input::post('cardNumber')
                    )
                ), 
                'phoneNumber' => array(
                    array(
                        'operator' => '=',
                        'operand' => Input::post('cardPhoneNumber')
                    )
                ),
                'customerId' => array(
                    array(
                        'operator' => '=',
                        'operand' => Input::post('customerId')
                    )
                )
            )
        );

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result']) && isset($data['result'][0])) {
            
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);
            
            $row     = $data['result'][0];
            $pinCode = Input::post('pinCode');
            
            if ($row['isactive'] == '1') {
                
                if ($pinCode == $row['pincode'] || Input::post('customerId')) {
                
                    $response = $row;
                    $response['status'] = 'success';

                } else {
                    $response = array('status' => 'warning', 'message' => Lang::line('POS_0112'));
                }
                
            } else {
                $response = array('status' => 'warning', 'message' => Lang::line('POS_0113'));
            }

        } else {
            $response = array('status' => 'warning', 'message' => Lang::line('POS_0114'));
        }
        
        return $response;
    }
    
    public function getInvoiceByIdModel($row) {
        
        $posTypeCode = Session::get(SESSION_PREFIX.'posTypeCode');
        $storeId = Session::get(SESSION_PREFIX.'storeId');
        
        $param = array(
            'id'      => issetParam($row['id']), 
            'storeId' => $storeId,
            //'customerId' => issetParam($row['customerId'])
        );
        // if (empty($row['id']) && $posTypeCode == '3') {
        //     return array('status' => 'success', 'data' => '');
        // }        
        
        if ($row['typeid'] == '1' || $row['typeid'] == '2') {
            
            $getCode = 'SDM_SALES_ORDER_POS_GET_004';
            
            if ($posTypeCode == '3') {
                $getCode = 'SDM_SALES_ORDER_POS_AGG_GET_004';
                $param['filterGuestName'] = issetParam($row['customername']);
            }
            
        } elseif ($row['typeid'] == '3' || $row['typeid'] == '4') {
            $getCode = 'SERVICE_PAYMENT_GET_004';
        } elseif ($row['typeid'] == '5') {
            $param = array(
                'keycode'         => $row['keycode'],
                'storeId'         => $storeId
            );
            
            if (Session::get(SESSION_PREFIX.'isBasketOnly') == '1') {
                $param['cashRegisterId'] = Session::get(SESSION_PREFIX.'cashRegisterId');
            }
            
            $getCode = 'SDM_SALES_ORDER_POS_GET_SIMPLE_004';
        } elseif ($row['typeid'] == '11') {
            unset($param['id']);
            $param['locationId'] = $row['id'];
            $getCode = 'LOCATION_ORDER_GET_004';
        } elseif ($row['typeid'] == '12') {
            unset($param['id']);
            $param['qrCode'] = $row['qrcode'];
            $getCode = 'SDM_SALES_ORDER_POS_GET_004_QR';
        } else {
            $getCode = 'FIN_INVOICE_PAYMENT_GET_004';
        }
        
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, $getCode, $param);

        if ($result['status'] == 'success') {
            
            if (isset($result['result'])) {
                $getResults = $result['result'];
                return array('status' => 'success', 'data' => $getResults);
            } else {
                return array('status' => 'error', 'message' => Lang::line('POS_0115'));
            }
            
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
    }
    
    public function getContractByIdModel($row) {
        
        $param = array('id' => $row['id']);
        
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'fitConContractPOS_GET_004', $param);

        if ($result['status'] == 'success') {
            
            if (isset($result['result'])) {
                return array('status' => 'success', 'data' => $result['result']);
            } else {
                return array('status' => 'error', 'message' => $this->lang->line('POS_0115'));
            }
            
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
    }
    
    public function getItemListModel() {
        
        $systemMetaGroupId = self::$getItemListDvId;
        
        $page = Input::postCheck('page') ? Input::post('page') : 1;
        $rows = Input::postCheck('rows') ? Input::post('rows') : 10;
        
        $storeId        = Session::get(SESSION_PREFIX.'storeId');
        $cashRegisterId = Session::get(SESSION_PREFIX.'cashRegisterId');
        
        if (defined('CONFIG_POS_ITEM_LIST_DV_ID') && CONFIG_POS_ITEM_LIST_DV_ID) {
            $systemMetaGroupId = CONFIG_POS_ITEM_LIST_DV_ID;
        } 
        
        $param = array(
            'systemMetaGroupId' => $systemMetaGroupId,
            'ignorePermission' => 1, 
            'showQuery' => 0, 
            'paging' => array(
                'offset' => $page,
                'pageSize' => $rows
            ), 
            'criteria' => array(
                'storeId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $storeId
                    )
                ), 
                'cashRegisterId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $cashRegisterId
                    )
                ),
                'customerId' => array(
                    array(
                        'operator' => '=',
                        'operand' => Input::post('customerId')
                    )
                )
            )
        );
        
        if (Input::postCheck('sort') && Input::postCheck('order')) {
            
            $sortField = Input::post('sort');
            $sortOrder = Input::post('order');

            if (strpos($sortField, ',') === false) {
                $param['paging']['sortColumnNames'] = array(
                    $sortField => array(
                        'sortType' => $sortOrder
                    )
                );
            } else {
                $sortFieldArr = explode(',', $sortField);
                $sortOrderArr = explode(',', $sortOrder);
                foreach ($sortFieldArr as $sortK => $sortF) {
                    $sortColumnNames[$sortF] = array('sortType' => $sortOrderArr[$sortK]);
                }
                $param['paging']['sortColumnNames'] = $sortColumnNames;
            }
        }
        
        if (Input::isEmpty('q') == false) {
            
            $value = Input::post('q');
            
            $paramFilter['filterItemName'][] = array(
                'operator' => 'like',
                'operand' => '%'.$value.'%'
            );
            
            $param['criteria'] = array_merge($param['criteria'], $paramFilter);
        }
        
        if (Input::isEmpty('tbltIds') == false) {
            
            $tbltIds = Input::post('tbltIds');
            
            $paramFilter['tbltIds'][] = array(
                'operator' => 'IN', 
                'operand' => $tbltIds 
            );
            
            $param['systemMetaGroupId'] = '1548137459640';
            $param['criteria'] = array_merge($param['criteria'], $paramFilter);
        }
        
        if (Input::isEmpty('isSpecialUse') == false) {
            
            $tbltIds = Input::post('isSpecialUse');
            
            $paramFilter['isspecialuse'][] = array(
                'operator' => '=', 
                'operand' => $tbltIds 
            );
            
            $param['criteria'] = array_merge($param['criteria'], $paramFilter);
        }
        
        if (Input::isEmpty('filterid') == false) {
            
            $tbltIds = Input::post('filterid');
            
            $paramFilter['filterid'][] = array(
                'operator' => '=', 
                'operand' => $tbltIds 
            );
            
            $param['criteria'] = array_merge($param['criteria'], $paramFilter);
        }

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result']) && isset($data['result'])) {
            
            unset($data['result']['aggregatecolumns']);
            
            $result['total'] = (isset($data['result']['paging']) ? $data['result']['paging']['totalcount'] : 0);
            unset($data['result']['paging']);
            
            $result['rows'] = $data['result'];
            $result['status'] = 'success';
            
            return $result;
            
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data), 'rows' => array(), 'total' => 0);
        }
    }
    
    public function getServiceListModel() {
        
        $page    = Input::postCheck('page') ? Input::post('page') : 1;
        $rows    = Input::postCheck('rows') ? Input::post('rows') : 10;
        $storeId = Session::get(SESSION_PREFIX.'storeId');
        
        $param = array(
            'systemMetaGroupId' => self::$getServiceListDvId,
            'ignorePermission' => 1, 
            'showQuery' => 0, 
            'paging' => array(
                'offset' => $page,
                'pageSize' => $rows
            ), 
            'criteria' => array(
                'storeId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $storeId
                    )
                )
            )
        );
        
        if (Input::postCheck('sort') && Input::postCheck('order')) {
            $sortField = Input::post('sort');
            $sortOrder = Input::post('order');

            if (strpos($sortField, ',') === false) {
                $param['paging']['sortColumnNames'] = array(
                    $sortField => array(
                        'sortType' => $sortOrder
                    )
                );
            } else {
                $sortFieldArr = explode(',', $sortField);
                $sortOrderArr = explode(',', $sortOrder);
                foreach ($sortFieldArr as $sortK => $sortF) {
                    $sortColumnNames[$sortF] = array('sortType' => $sortOrderArr[$sortK]);
                }
                $param['paging']['sortColumnNames'] = $sortColumnNames;
            }
        }
        
        if (Input::isEmpty('q') == false) {
            
            $value = Input::post('q');
            
            $paramFilter['filterJobName'][] = array(
                'operator' => 'like',
                'operand' => '%'.$value.'%'
            );

            $param['criteria'] = array_merge($param['criteria'], $paramFilter);
        }

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result']) && isset($data['result'])) {
            
            unset($data['result']['aggregatecolumns']);
            
            $result['total'] = (isset($data['result']['paging']) ? $data['result']['paging']['totalcount'] : 0);
            unset($data['result']['paging']);
            
            $result['rows'] = $data['result'];
            $result['status'] = 'success';
            
            return $result;
            
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data), 'rows' => array(), 'total' => 0);
        }
    }
    
    public function getInvoiceDataModel($bookId) {
        
        $param = array(
            'processId' => 1519803449821, 
            'criteria' => array(
                'id' => array(
                    array(
                        'operator' => '=',
                        'operand' => $bookId
                    )
                )
            )
        );
        
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'POS_SM_SALES_INVOICE_HEADER_004', $param);

        if ($result['status'] == 'success' && isset($result['result'])) {
            return $result['result'];
        } else {
            return false;
        }
    }
    
    public function getHeaderDataFromInvoiceData($invoiceId, $invoiceData) {
        
        $row = $this->db->GetRow("
            SELECT 
                RD.BILL_ID, 
                RD.CUSTOMER_NUMBER, 
                RD.CUSTOMER_NAME, 
                RD.VAT_DATE, 
                IH.DISCOUNT, 
                IH.PHONE_NUMBER, 
                IH.CREATED_DATE 
            FROM SM_SALES_INVOICE_HEADER IH  
                INNER JOIN SM_BILL_RESULT_DATA RD ON IH.SALES_INVOICE_ID = RD.SALES_INVOICE_ID 
                    AND (RD.IS_REMOVED IS NULL OR RD.IS_REMOVED = 0) AND IS_ROOT_PACKAGE = 1
            WHERE IH.SALES_INVOICE_ID = $invoiceId 
        ");
        
        if (empty($row)) {
            $row = $this->db->GetRow("
                SELECT 
                    RD.BILL_ID, 
                    RD.CUSTOMER_NUMBER, 
                    RD.CUSTOMER_NAME, 
                    RD.VAT_DATE, 
                    IH.DISCOUNT, 
                    IH.PHONE_NUMBER,
                    IH.CREATED_DATE 
                FROM SM_SALES_INVOICE_HEADER IH  
                    LEFT JOIN SM_BILL_RESULT_DATA RD ON IH.SALES_INVOICE_ID = RD.SALES_INVOICE_ID 
                        AND (RD.IS_REMOVED IS NULL OR RD.IS_REMOVED = 0)
                WHERE IH.SALES_INVOICE_ID = $invoiceId 
            ");            
        }
        
        $array = array(
            'billid'        => $row['BILL_ID'], 
            'invoicenumber' => $invoiceData['invoicenumber'], 
            'refnumber'     => $invoiceData['refnumber'], 
            'payAmount'     => $invoiceData['subtotal'], 
            'vat'           => $invoiceData['vat'], 
            'phoneNumber'   => $row['PHONE_NUMBER'], 
            'billType'      => ($row['CUSTOMER_NUMBER'] != '' && $row['CUSTOMER_NAME'] != '') ? 'organization' : 'person', 
            'orgNumber'     => ($row['CUSTOMER_NUMBER'] != '') ? $row['CUSTOMER_NUMBER'] : '',  
            'orgName'       => ($row['CUSTOMER_NAME'] != '') ? $row['CUSTOMER_NAME'] : '', 
            'vatdate'       => ($row['VAT_DATE'] != '') ? $row['VAT_DATE'] : $row['CREATED_DATE'], 
            'cashAmount'    => '',
            
            'discountAmount'        => ($row['DISCOUNT'] ? $row['DISCOUNT'] : 0),
            'bonusCardAmount'       => '', 
            'discountActivityAmount'=> '', 
            'insuranceAmount'       => '', 
            'socialAmount'          => '', 
            'tcardAmount'           => '', 
            'shoppyAmount'          => '', 
            'glmtRewardAmount'      => '', 
            'socialPayRewardAmount' => '', 
            'posTaxInvoiceAmount'   => '', 
            'couponAmount'          => 0, 
            'bankList'              => array(), 
            'accountTransferList'   => array(), 
            'recievableAmountList'  => array(), 
            'couponList'            => array(), 
            'coupon2List'           => array(), 
            'mobileNetAmount'       => array(),
            'prePaymentAmount'      => array(),
            'accountTransferAmount' => array(),
            'barterAmount'          => '',
            'leasingAmount'         => array(),
            'empLoanAmount'         => '', 
            'upointAmount'          => '', 
            'recievableAmount'      => '', 
            'emdAmount'             => 0
        );
        
        $paymentDtl = $invoiceData['sm_sales_payment_dv'];
        
        if ($paymentDtl) {
            
            $couponAmount = 0;
            
            foreach ($paymentDtl as $row) {
            
                if ($row['paymenttypeid'] == '1') {

                    $array['cashAmount'] = $row['amount'];

                } elseif ($row['paymenttypeid'] == '2') {

                    $array['bankList'][] = array(
                        'bankid' => $row['bankid'], 
                        'amount' => $row['amount'],
                        'confirmcode' => issetParam($row['confirmcode']),
                        'traceno' => issetParam($row['traceno']),
                        'cardregisternumber' => ($row['bankid'] == 400000 || $row['bankid'] == 500000) ? issetParam($row['traceno']) : issetParam($row['cardregisternumber']),
                        'terminalnumber' => issetParam($row['terminalnumber']),
                    );

                } elseif ($row['paymenttypeid'] == '3') {

                    $array['mobileNetAmount'] = array(
                        'bankid' => $row['bankid'], 
                        'amount' => $row['amount']
                    );

                } elseif ($row['paymenttypeid'] == '4') {
                    
                    $array['accountTransferList'][] = array(
                        'bankid' => $row['bankid'], 
                        'amount' => $row['amount']
                    );
                    
                    /*$array['accountTransferAmount'] = array(
                        'bankid' => $row['bankid'], 
                        'amount' => $row['amount']
                    );*/

                } elseif ($row['paymenttypeid'] == '5') {

                    $array['barterAmount'] = $row['amount'];

                } elseif ($row['paymenttypeid'] == '51') {

                    $array['tcardAmount'] = $row['amount'];

                } elseif ($row['paymenttypeid'] == '52') {

                    $array['shoppyAmount'] = $row['amount'];

                } elseif ($row['paymenttypeid'] == '53') {

                    $array['glmtRewardAmount'] = $row['amount'];

                } elseif ($row['paymenttypeid'] == '54') {

                    $array['socialPayRewardAmount'] = $row['amount'];

                } elseif ($row['paymenttypeid'] == '57') {

                    $array['posTaxInvoiceAmount'] = $row['amount'];

                } elseif ($row['paymenttypeid'] == '6') {

                    $array['leasingAmount'] = array(
                        'bankid' => $row['bankid'], 
                        'amount' => $row['amount']
                    );

                } elseif ($row['paymenttypeid'] == '7') {

                    $array['empLoanAmount'] = $row['amount'];

                } elseif ($row['paymenttypeid'] == '9') {

                    $array['couponList'][] = array(
                        'name'   => Lang::line('POS_0087'), 
                        'amount' => $row['amount']
                    );
                    
                    $couponAmount += issetParamZero($row['amount']);

                } elseif ($row['paymenttypeid'] == '10') {

                    $array['couponList'][] = array(
                        'name'   => Lang::line('POS_0086'), 
                        'amount' => $row['amount']
                    );
                    
                    $couponAmount += issetParamZero($row['amount']);

                } elseif ($row['paymenttypeid'] == '11') {

                    $array['couponList'][] = array(
                        'name'   => Lang::line('POS_0044'), 
                        'amount' => $row['amount']
                    );
                    
                    $couponAmount += issetParamZero($row['amount']);

                } elseif ($row['paymenttypeid'] == '34') {

                    $array['coupon2List'][] = array(
                        'name'   => Lang::line('POS_0214'), 
                        'voucher2Amount' => $row['amount']
                    );

                } elseif ($row['paymenttypeid'] == '36') {

                    $array['upointAmount'] = array(
                        'upointAmount' => $row['amount'],
                        'intamt' => $row['inamt'],
                        'exttransactionid' => $row['exttransactionid'],
                        'calcAmount'       => $row['calcamount'],
                        'bankcardnumber'   => $row['bankcardnumber'],
                    );

                } elseif ($row['paymenttypeid'] == '22') {

                    $getExtra = new Mddatamodel();
                    $getRowExtra = $getExtra->getIdCodeName(Config::getFromCacheDefault('CONFIG_POS_PAYMENT_RECEIVABLE', null, 0), $row['customerid']);

                    $array['recievableAmountList'][] = array(
                        'recievableAmount' => $row['amount'],
                        'code' => $getRowExtra['code'], 
                        'name' => $getRowExtra['name'],                         
                        'customerId' => $row['customerid']
                    );

                } elseif ($row['paymenttypeid'] == '12') {

                    $array['bonusCardAmount'] = $row['amount'];

                } elseif ($row['paymenttypeid'] == '45') {

                    $array['discountActivityAmount'] = $row['amount'];

                } elseif ($row['paymenttypeid'] == '42') {

                    $array['insuranceAmount'] = $row['amount'];

                } elseif ($row['paymenttypeid'] == '19') {

                    $array['socialAmount'] = array(
                        'bankid' => $row['exttransactionid'], 
                        'confirmcode' => $row['confirmcode'], 
                        'bankcardnumber' => $row['bankcardnumber'], 
                        'terminalnumber' => $row['terminalnumber'], 
                        'amount' => $row['amount']
                    );                    

                } elseif ($row['paymenttypeid'] == '14') {

                    $array['emdAmount'] = $row['amount'];

                } elseif ($row['paymenttypeid'] == '20') {

                    if (issetParam($row['exttransactionid'])) {

                        $getExtra = new Mddatamodel();
                        $getRowExtra = $getExtra->getIdCodeName('1579598747132', $row['exttransactionid']);

                        $array['prePaymentAmount'] = array(
                            'extransactionId' => $row['exttransactionid'], 
                            'code' => $getRowExtra['code'], 
                            'name' => $getRowExtra['name'], 
                            'amount' => $row['amount']
                        );
                    } else {
                        $array['prePaymentAmount'] = array(
                            'extransactionId' => '', 
                            'code' => '', 
                            'name' => '', 
                            'amount' => $row['amount']
                        );                        
                    }
                }
            }
            
            $array['couponAmount'] = $couponAmount;
            
        } else {
            $array['payAmount'] = 0;
        }
        
        return $array;
    }
    
    public function getItemDetailFromInvoiceData($invoiceData) {
        
        $array = array();
        $detail = $invoiceData['pos_sm_sales_invoice_detail'];

        foreach ($detail as $k => $row) {
            
            if (isset($row['jobid']['id'])) {
                
                $array[$k]['id'] = $row['jobid']['id'];
                $array[$k]['itemcode'] = $row['jobid']['code'];
                $array[$k]['itemname'] = $row['jobid']['name'];
                $array[$k]['jobid'] = $row['jobid']['id'];
                
                $array[$k]['measureid'] = '1411110353030';
                $array[$k]['measurecode'] = 'ш';
                $array[$k]['barcode'] = $row['jobid']['rowdata']['taxcode'];
                
            } else {
                $array[$k]['id'] = $row['itemid']['id'];
                $array[$k]['itemcode'] = $row['itemid']['code'];
                $array[$k]['itemname'] = $row['itemid']['name'];
                $array[$k]['jobid'] = null;
                
                $array[$k]['measureid'] = $row['itemid']['rowdata']['measureid'];
                $array[$k]['measurecode'] = $row['itemid']['rowdata']['measurecode'];
                
                if ($row['barcode'] == '') {
                    $array[$k]['barcode'] = $row['itemid']['rowdata']['taxcode'];
                } else {
                    $array[$k]['barcode'] = $row['barcode'];
                }
            }
            if( isset($row['citytax']) && isset($row['iscitytax']) ) { 
                $array[$k]['iscitytax'] = $row['iscitytax'];
                $array[$k]['citytax'] = $row['citytax'];
            }else{
                $array[$k]['iscitytax'] = '';
                $array[$k]['citytax'] = '';
            }

            $array[$k]['citytaxpercent'] = '';
            $array[$k]['editid'] = $row['id'];
            
            if ($row['percentvat'] != '') {
                $array[$k]['isvat'] = '1';
                $array[$k]['vatpercent'] = $row['percentvat'];
                $array[$k]['vatprice'] = $row['unitprice'];
                $array[$k]['novatprice'] = number_format($row['unitprice'] / 1.1, 2, '.', '');
            } else {
                $array[$k]['isvat'] = '0';
                $array[$k]['vatpercent'] = '';
                $array[$k]['vatprice'] = '';
                $array[$k]['novatprice'] = $row['unitprice'];
            }
            
            $array[$k]['invoiceqty'] = $row['invoiceqty'];
            $array[$k]['saleprice'] = $row['unitprice'];
            $array[$k]['linetotalamount'] = $row['linetotalamount'];
            $array[$k]['linetotalprice'] = $row['linetotalprice'];
            $array[$k]['iscalcupoint'] = issetParam($row['iscalcupoint']);
            
            if (array_key_exists('percentdiscount', $row) && $row['unitdiscount'] > 0 
                && array_key_exists('discountpercent', $row) && $row['discountamount'] > 0) {
                
                $array[$k]['percentdiscount'] = $row['percentdiscount'];
                $array[$k]['discountamount'] = $row['unitamount'];
                
                if ($row['unitdiscount'] != $row['discountamount']) {
                    $array[$k]['unitdiscount'] = $row['unitdiscount'] - ($row['discountamount'] * $row['invoiceqty']);
                } else {
                    $array[$k]['unitdiscount'] = $row['unitdiscount'];
                }
                
            } elseif (array_key_exists('discountpercent', $row) && $row['discountamount'] > 0) {
                
                $array[$k]['percentdiscount'] = $row['discountpercent'];
                $array[$k]['unitdiscount'] = $row['discountamount'];
                $array[$k]['discountamount'] = $row['unitprice'] - $row['discountamount'];
                $array[$k]['linetotalamount'] = $row['linetotalprice'] - ($row['discountamount'] * $array[$k]['invoiceqty']);
                
            } elseif (array_key_exists('discountpercent', $row) && $row['discountamount'] < 0) {
                
                $array[$k]['percentdiscount'] = $row['discountpercent'];
                $array[$k]['unitdiscount'] = $row['discountamount'];
                $array[$k]['discountamount'] = $row['discountamount'];
                
            } else {
                $array[$k]['percentdiscount'] = $row['percentdiscount'];
                $array[$k]['unitdiscount'] = $row['unitdiscount'];
                $array[$k]['discountamount'] = $row['unitamount'];
            }
            
            $array[$k]['unitreceivable'] = $row['unitreceivable'];
            $array[$k]['serialnumber'] = $row['serialnumber'];
            
            if (isset($row['employeeid']['id'])) {
                $array[$k]['employeeid'] = $row['employeeid']['id'];
                $array[$k]['salespersoncode'] = $row['employeeid']['code'];
                $array[$k]['salespersonname'] = $row['employeeid']['name'];
            } else {
                $array[$k]['employeeid'] = null;
                $array[$k]['salespersoncode'] = null;
                $array[$k]['salespersonname'] = null;
            }
            
            $array[$k]['storewarehouseid'] = null;
            $array[$k]['deliverywarehouseid'] = null;
            $array[$k]['isdelivery'] = $row['isdelivery'];
        }
        
        return $array;
    }
    
    public function posApiSendDataByStoreModel() {
        
        $storeId = Input::post('storeId');
        
        $data = $this->db->GetAll("
            SELECT 
                SS.DEPARTMENT_ID, 
                SS.CODE AS STORE_CODE,
                CR.CODE AS POS_CODE, 
                CR.NAME AS POS_NAME, 
                SCR.CASH_REGISTER_ID AS POS_ID 
            FROM SM_STORE_CASH_REGISTER SCR 
                INNER JOIN SM_STORE SS ON SS.STORE_ID = SCR.STORE_ID 
                INNER JOIN SM_CASH_REGISTER CR ON CR.CASH_REGISTER_ID = SCR.CASH_REGISTER_ID 
            WHERE SS.DEPARTMENT_ID IS NOT NULL AND SCR.STORE_ID = $storeId");
        
        if ($data) {
            
            $message = '';
            $sessionUserKeyId = Ue::sessionUserKeyId();
            $departmentId = $data[0]['DEPARTMENT_ID'];
            
            $orgRow = $this->db->GetRow("
                SELECT
                    DEPARTMENT_ID 
                FROM ORG_DEPARTMENT 
                WHERE VATSP_NUMBER IS NOT NULL 
                    AND ROWNUM = 1 
                START WITH DEPARTMENT_ID = $departmentId   
                CONNECT BY PRIOR PARENT_ID = DEPARTMENT_ID");

            if (isset($orgRow['DEPARTMENT_ID']) && $orgRow['DEPARTMENT_ID']) {
                $departmentId = $orgRow['DEPARTMENT_ID'];
            }
            
            $organizationId = Session::get(SESSION_PREFIX.'vatNumber');
            
            foreach ($data as $row) {
                
                $successStatus = $successMsg = '';
                $posApiPath = $organizationId.'\\'.$row['STORE_CODE'].'\\'.$row['POS_CODE'];

                $response = $this->ws->redirectPost(Mdpos::getPosApiServiceAddr(), array('function' => 'checkapi', 'vatNumber' => $posApiPath));

                if ($response == 'null') {
                    
                    $successStatus = 'false';
                    $successMsg = Lang::line('POS_0077');
                    $message .= Lang::lineVar('POS_0116', array('pos_code' => $row['POS_CODE'], 'pos_name' => $row['POS_NAME'])) . $successMsg.'<br />';
                    
                } else {
                    
                    $sendDataResponse = $this->ws->redirectPost(Mdpos::getPosApiServiceAddr(), array('function' => 'senddata', 'vatNumber' => $posApiPath));
                    $sendDataArray = json_decode($sendDataResponse, true);
                    
                    if ($sendDataArray['status'] == 'success') {
                        $successStatus = 'true';
                        $message .= Lang::lineVar('POS_0117', array('pos_code' => $row['POS_CODE'], 'pos_name' => $row['POS_NAME']));
                    } else {
                        $successStatus = 'false';
                        $successMsg = $sendDataArray['errorcode'].' '.$sendDataArray['message'];
                        $message .= Lang::lineVar('POS_0118', array('pos_code' => $row['POS_CODE'], 'pos_name' => $row['POS_NAME'])) . $successMsg.'<br />';
                    }
                }
                
                $insertLogData = array(
                    'ID'                => getUID(), 
                    'STORE_ID'          => $storeId, 
                    'CASH_REGISTER_ID'  => $row['POS_ID'], 
                    'CREATED_USER_ID'   => $sessionUserKeyId, 
                    'CREATED_DATE'      => Date::currentDate('Y-m-d H:i:s'), 
                    'SUCCESS'           => $successStatus, 
                    'RESULT_JSON'       => $successMsg
                );

                $this->db->AutoExecute('SM_STORE_POSAPI_LOG', $insertLogData);
            }
            
            $response = array('status' => 'info', 'message' => $message);
            
        } else {
            $response = array('status' => 'info', 'message' => Lang::line('POS_0119'));
        }
        
        return $response;
    }
    
    public function discountPartTemplate($voucherSerialNumber = '') {
        
        if (Config::getFromCache('CONFIG_POS_IS_USE_REMAINDER_COUPON')) {
            $template = '<tr>
                    <td style="border-top: 1px #000 dashed; border-bottom: 1px #000 dashed; font-family: Tahoma; font-size: 12px; padding: 10px 0 5px 0">
                        <strong>'.Lang::line('POS_0214').' </strong>'.($voucherSerialNumber ? ' - ' . $voucherSerialNumber : '').'
                    </td>
                </tr>	
                <tr>
                    <td style="border-top:1px #000 dashed; padding: 4px 0;">
                        <table border="0" width="100%" style="width: 100%; table-layout: fixed; font-family: Tahoma; font-size: 12px; font-weight: normal;">
                            <tbody>

                                {discount-detail}

                            </tbody>
                        </table>
                    </td>
                </tr>';            
        } else {
            $template = '<tr>
                    <td style="border-top: 1px #000 dashed; border-bottom: 1px #000 dashed; font-family: Tahoma; font-size: 12px; padding: 10px 0 5px 0">
                        <strong>'.Lang::line('POS_0085').'</strong>
                    </td>
                </tr>	
                <tr>
                    <td style="border-top:1px #000 dashed; padding: 4px 0;">
                        <table border="0" width="100%" style="width: 100%; table-layout: fixed; font-family: Tahoma; font-size: 12px; font-weight: normal;">
                            <tbody>

                                {discount-detail}

                                <tr>
                                    <td style="text-align: right; padding: 0; width: 100%; font-weight: bold">'.Lang::line('POS_0120').':</td>
                                    <td style="text-align: right; padding: 0; width: 85px; font-weight: bold">{totalDiscount}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>';
        }
        
        return $template;
    }
    
    public function discountCardTemplate() {        
        $template = '<tr>
            <td style="border-top: 1px #000 dashed; font-family: Tahoma; font-size: 12px; padding: 10px 0 5px 0">
                <strong>Хөнгөлөлтийн карт:</strong> {bonusCardNumber}
            </td>
        </tr>	
        <tr>
            <td style="border-top: 1px #000 dashed; padding: 4px 0;">
                <table border="0" width="100%" style="width: 100%; table-layout: fixed; font-family: Tahoma; font-size: 12px;">
                    <tbody>
                        <tr>
                            <td style="text-align: right; padding: 0; width: 100%">Эхний үлдэгдэл:</td>
                            <td style="text-align: right; padding: 0; width: 85px">{bonusCardBeginAmount}</td>
                        </tr>
                        <tr>
                            <td style="text-align: right; padding: 0;">Хасагдах:</td>
                            <td style="text-align: right; padding: 0;">{bonusCardDiffAmount}</td>
                        </tr>
                        <tr>
                            <td style="text-align: right; padding: 0;">Нэмэгдсэн:</td>
                            <td style="text-align: right; padding: 0;">{bonusCardPlusAmount}</td>
                        </tr>
                        <tr>
                            <td style="text-align: right; padding: 0;">Эцсийн үлдэгдэл:</td>
                            <td style="text-align: right; padding: 0;">{bonusCardEndAmount}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>';
        
        return $template;
    }
    
    public function posAmount($number) {
        $number = str_replace(',', '', $number);

        if (is_numeric($number)) {
            $number = number_format($number, 2, '.', ',');
            $number = rtrim(rtrim(rtrim($number,'0'),'0'),'.');
        } else {
            $number = 0;
        }
        
        return $number; 
    }
    
    public function posQty($number) {
        $number = str_replace(',', '', $number);

        if (is_numeric($number)) {
            $number = number_format($number, 3, '.', ',');
            $number = rtrim(rtrim(rtrim($number,'0'),'0'),'.');
        } else {
            $number = 0;
        }
        
        return $number; 
    }
    
    public function convertCyrillicMongolia($str, $reverse = false) {

        $replacing = array(
            'А' => 'M01N',
            'Б' => 'M02N',
            'В' => 'M03N',
            'Г' => 'M04N',
            'Д' => 'M05N',
            'Е' => 'M06N',
            'Ё' => 'M07N',
            'Ж' => 'M08N',
            'З' => 'M09N',
            'И' => 'M10N',
            'Й' => 'M11N',
            'К' => 'M12N',
            'Л' => 'M13N',
            'М' => 'M14N',
            'Н' => 'M15N',
            'О' => 'M16N',
            'Ө' => 'M17N',
            'П' => 'M18N',
            'Р' => 'M19N',
            'С' => 'M20N',
            'Т' => 'M21N',
            'У' => 'M22N',
            'Ү' => 'M23N',
            'Ф' => 'M24N',
            'Х' => 'M25N',
            'Ц' => 'M26N',
            'Ч' => 'M27N',
            'Ш' => 'M28N',
            'Щ' => 'M29N',
            'Ь' => 'M30N',
            'Ъ' => 'M31N',
            'Э' => 'M32N',
            'Ю' => 'M33N',
            'Я' => 'M34N',
            'Ы' => 'M35N', 

            'а' => 'm01n',
            'б' => 'm02n',
            'в' => 'm03n',
            'г' => 'm04n',
            'д' => 'm05n',
            'е' => 'm06n',
            'ё' => 'm07n',
            'ж' => 'm08n',
            'з' => 'm09n',
            'и' => 'm10n',
            'й' => 'm11n',
            'к' => 'm12n',
            'л' => 'm13n',
            'м' => 'm14n',
            'н' => 'm15n',
            'о' => 'm16n',
            'ө' => 'm17n',
            'п' => 'm18n',
            'р' => 'm19n',
            'с' => 'm20n',
            'т' => 'm21n',
            'у' => 'm22n',
            'ү' => 'm23n',
            'ф' => 'm24n',
            'х' => 'm25n',
            'ц' => 'm26n',
            'ч' => 'm27n',
            'ш' => 'm28n',
            'щ' => 'm29n',
            'ь' => 'm30n',
            'ъ' => 'm31n',
            'э' => 'm32n',
            'ю' => 'm33n',
            'я' => 'm34n',
            'ы' => 'm35n'
        );
        
        if ($reverse) {
            $replacing = array_flip($replacing);
        }

        $result = strtr($str, $replacing);

        return $result;
    }
    
    public function apiStringReplace($str, $reverse = false) {
        
        $str = str_replace('S21C', 'S 21C', $str);
        
        $search = array(
            '~', '`', '!', '@', '#', '$',  
            '%', '^', '&', '*', '(', ')',  
            '-', '_', '+', '{', '}', '[',
            ']', '|', '\\', '/', ';', '<', 
            '>', '?', ':', '"', "'" 
        );
        $replace = array(
            'S01C', 'S02C', 'S03C', 'S04C',
            'S05C', 'S06C', 'S07C', 'S08C',
            'S09C', 'S10C', 'S11C', 'S12C',
            'S13C', 'S14C', 'S15C', 'S16C',
            'S17C', 'S18C', 'S19C', 'S20C',
            'S21C', 'S22C', 'S23C', 'S24C',
            'S25C', 'S26C', '=', "", ""
        );
        
        if ($reverse) {
            $str = str_replace($replace, $search, $str);
        } else {
            $str = str_replace($search, $replace, $str);
        }
        $str = self::convertCyrillicMongolia($str, $reverse);
        $str = preg_replace('/[^A-Za-z0-9\- ]/', '', $str);
        
        return $str;
    }


    // EMD GET&SEND DATA
    public function getEmdInvoiceData($sales_invoice_id) {
        $result = $this->db->GetAll("SELECT 
            DTL.*, 
            IM.ITEM_NAME, 
            DD.TBLTPACKINGCNT 
        FROM SM_SALES_INVOICE_DETAIL DTL 
            INNER JOIN IM_ITEM IM ON IM.ITEM_ID = DTL.PRODUCT_ID   
            INNER JOIN IM_DISCOUNT_DRUG DD ON DD.ID = IM.OLD_ITEM_CODE 
        WHERE DTL.SALES_INVOICE_ID = " . $sales_invoice_id);

        return $result;
    }


    public function getEmdInvoiceHeaderData(){
        $result = $this->db->GetAll("SELECT 
            HDR.SALES_INVOICE_ID, 
            BR.BILL_ID, 
            BR.VAT_DATE, 
            HDR.TOTAL, 
            HDR.VAT, 
            SP.AMOUNT AS EMD_AMOUNT, 
            HDR.PRESCRIPTION_NUMBER, 
            SS.CLIENT_ID, 
            SS.CLIENT_SECRET 
        FROM SM_SALES_INVOICE_HEADER HDR 
            INNER JOIN SM_SALES_INVOICE_PRESCRIPTION PRE ON PRE.SALES_INVOICE_ID = HDR.SALES_INVOICE_ID AND PRE.IS_SENT = 0 
            INNER JOIN SM_BILL_RESULT_DATA BR ON BR.SALES_INVOICE_ID = HDR.SALES_INVOICE_ID 
            INNER JOIN SM_SALES_PAYMENT SP ON SP.SALES_INVOICE_ID = HDR.SALES_INVOICE_ID AND SP.PAYMENT_TYPE_ID = 14 
            INNER JOIN SM_STORE SS ON SS.STORE_ID = HDR.STORE_ID 
        WHERE HDR.STORE_ID IN (1522206140674, 1522206140678) 
            AND (HDR.IS_REMOVED = 0 OR HDR.IS_REMOVED IS NULL) 
            AND HDR.PRESCRIPTION_NUMBER IS NOT NULL 
            AND HDR.CREATED_DATE BETWEEN TO_DATE('2018-09-01', 'YYYY-MM-DD') AND TO_DATE('2018-09-30', 'YYYY-MM-DD')");

        return $result;
    }


    // EMD GET&SEND END 


    
    public function getReceiptNumberModel() {
        $getToken = self::emdGetToken();
        if (isset($getToken['access_token'])) {
            
            $accessToken   = $getToken['access_token'];
            $receiptNumber = Input::post('receiptNumber');
            $regNumber     = Input::post('regNumber');
            
            $getReceiptData = self::emdCheckNumber($accessToken, $receiptNumber, $regNumber);
            
            if (isset($getReceiptData['status'])) {
                
                $response = array('status' => 'success', 'data' => $getReceiptData);
                
            } else {
                $response = array('status' => 'warning', 'message' => Lang::line('POS_0121'));
            }
            
        } else {
            
            if (isset($getToken['error'])) {
                $response = array('status' => 'error', 'message' => $getToken['error'].' - '.$getToken['error_description']);
            } else {
                $response = array('status' => 'error', 'message' => 'Эрүүл мэндийн цахим системээс хариу ирсэнгүй!');
            }
        }
        
        return $response;
    }
    
    public function emdGetToken($clientId = null, $clientSecret = null) {
        
        if (!$clientId) {
            $sPrefix      = SESSION_PREFIX;
            $clientId     = Session::get($sPrefix.'posEmdClientId');
            $clientSecret = Session::get($sPrefix.'posEmdClientSecret');
        }
                
        $url = self::$emdWsUrl.'oauth/token';
        $ch = curl_init($url);
        $auth = "Authorization: Basic VV9mZl05Qmp5WlhMbUcmZHcmOlo3JHtFenlyRDRheUN9RkxkJg==";
        $data = array('grant_type' => 'password', 'username' => $clientId, 'password' => $clientSecret);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array($auth, 'Content-Type: application/x-www-form-urlencoded;charset=UTF-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        $str = curl_exec($ch);
        curl_close($ch); 

        return json_decode($str, true);
    }
    
    public function emdCheckNumber($accessToken, $receiptNumber, $regNumber = '') {
        
        if ($regNumber) {
            $url = self::$emdWsUrl.'receipt/checkNumberBy?access_token='.$accessToken.'&receiptNumber='.$receiptNumber.'&regNo='.$regNumber;
        } else {
            $url = self::$emdWsUrl.'receipt/checkNumber?access_token='.$accessToken.'&receiptNumber='.$receiptNumber;
        }

        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json;charset=UTF-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $str = curl_exec($ch);
        
        curl_close($ch); 

        return json_decode($str, true);
    }
    
    public function emdSendData($accessToken, $dataParams) {
                
        $url = self::$emdWsUrl.'ebarimt/send?access_token='.$accessToken;

        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json;charset=UTF-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataParams));

        $str = curl_exec($ch);
        curl_close($ch); 

        return json_decode($str, true);
    }

    public function emdReturn($accessToken, $returnBillId) {
                
        $url = self::$emdWsUrl.'ebarimt/return?access_token='.$accessToken.'&posRno='.$returnBillId;
        
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json;charset=UTF-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $str = curl_exec($ch);
        curl_close($ch); 

        return json_decode($str, true);
    }
    
    public function createPrescription($invoiceId, $billId, $salesDate, $totalAmount, $vatAmount, $emdAmount, $paramsDtl) {
        
        $isReceiptNumber = Input::post('isReceiptNumber');
        
        if ($isReceiptNumber == 'true' && isset($_POST['drugPrescription'])) {
            
            $isEmdSent    = 0;  
            $errorMessage = '';
            $prescription = $_POST['drugPrescription'];
            
            $this->db->AutoExecute(
                'SM_SALES_INVOICE_HEADER', 
                array('PRESCRIPTION_NUMBER' => $prescription['receiptNumber']), 
                'UPDATE', 
                'SALES_INVOICE_ID = '.$invoiceId
            );
            
            $getToken       = self::emdGetToken();
            $ebarimtDetails = array();
            
            $dataParams = array(
                'posRno'        => $billId, 
                'salesDate'     => strtotime($salesDate).'000', 
                'status'        => 1, 
                'vatAmt'        => sprintf("%.2f", $vatAmount), 
                //'insAmt'        => sprintf("%.2f", $emdAmount), 
                //'totalAmt'      => sprintf("%.2f", ($totalAmount - $emdAmount)), 
                'insAmt'        => '', 
                'totalAmt'      => '', 
                'netAmt'        => sprintf("%.2f", $totalAmount), 
                'receiptNumber' => $prescription['receiptNumber']
            );
            
            if ($receiptId = issetParam($prescription['id'])) {
                $dataParams['receiptId'] = $receiptId;
            }
            
            $sumInsAmt = $sumTotalAmt = 0;
            
            foreach ($paramsDtl as $item) {
                
                $emdItemRow = $this->db->GetRow("
                    SELECT 
                        DDD.TBLTPACKINGCNT, 
                        DDD.PACKGROUP, 
                        DDD.ID 
                    FROM IM_ITEM IM 
                        INNER JOIN IM_ITEM_DISCOUNT_DRUG DD ON DD.ITEM_ID = IM.ITEM_ID 
                        INNER JOIN IM_DISCOUNT_DRUG DDD ON DDD.ID = DD.DISCOUNT_DRUG_ID 
                    WHERE IM.ITEM_ID = ".$item['itemId']);
                
                if ($emdItemRow) {
                    $packingCnt = $emdItemRow['TBLTPACKINGCNT'] > 0 ? $emdItemRow['TBLTPACKINGCNT'] : 1;
                } else {
                    $packingCnt = 1;
                }
                
                $insAmt   = $item['unitReceivable'];
                $totalAmt = number_format($item['lineTotalAmount'] - $insAmt, 2, '.', '');
                        
                $detailsRow = array(
                    'barCode'     => $item['barCode'], 
                    'productName' => $item['itemName'], 
                    'quantity'    => number_format($item['invoiceQty'] * $packingCnt, 0, '.', ''), 
                    'insAmt'      => number_format($insAmt, 0, '.', ''), 
                    'totalAmt'    => number_format($totalAmt, 0, '.', ''),
                    'price'       => $item['unitAmount']
                );
                
                $sumInsAmt   += $detailsRow['insAmt'];
                $sumTotalAmt += $detailsRow['totalAmt'];
                
                if ($emdItemRow && $emdItemRow['ID'] && isset($prescription['receiptDetails'])) {
                    
                    $receiptDetails = $prescription['receiptDetails'];
                    
                    foreach ($receiptDetails as $rek => $receiptDetailRow) {
                        
                        if (isset($receiptDetailRow['id']) && isset($receiptDetailRow['tbltId']) && $emdItemRow['ID'] == $receiptDetailRow['tbltId']) {
                            $detailsRow['detailId'] = $receiptDetailRow['id'];
                            $detailsRow['tbltId'] = $emdItemRow['ID'];
                            $detailsRow['packGroup'] = $emdItemRow['PACKGROUP'];
                            unset($receiptDetails[$rek]);
                            break;
                        }
                    }
                    
                    if (!isset($detailsRow['detailId'])) {
                        
                        $emdItemList = $this->db->GetAll("
                            SELECT 
                                ID,
                                PACKGROUP
                            FROM IM_DISCOUNT_DRUG 
                            WHERE SYS_GROUP_ID IN (
                                SELECT  
                                    DDD.SYS_GROUP_ID 
                                FROM IM_ITEM_DISCOUNT_DRUG DD 
                                    INNER JOIN IM_DISCOUNT_DRUG DDD ON DDD.ID = DD.DISCOUNT_DRUG_ID OR DDD.SYS_GROUP_ID = DD.SYS_GROUP_ID 
                                WHERE DD.ITEM_ID = ".$item['itemId']."   
                                GROUP BY DDD.SYS_GROUP_ID)");
                        
                        $emdPackItem = $this->db->GetRow("
                            SELECT
                                DDD.*
                           FROM
                            IM_ITEM_DISCOUNT_DRUG DD
                            INNER JOIN IM_DISCOUNT_DRUG DDD ON DDD.ID = DD.DISCOUNT_DRUG_ID OR DDD.SYS_GROUP_ID = DD.SYS_GROUP_ID
                            INNER JOIN IM_ITEM II ON DD.ITEM_ID = II.ITEM_ID
                            INNER JOIN IM_ITEM_BARCODE IB ON II.ITEM_ID = IB.ITEM_ID AND DDD.TBLTBARCODE = IB.BARCODE
                           WHERE
                            DD.ITEM_ID = ".$item['itemId']);
                        
                        if ($emdItemList) {
                            
                            foreach ($emdItemList as $emdImRow) {
                                
                                foreach ($receiptDetails as $rek => $receiptDetailRow) {
                        
                                    if (isset($receiptDetailRow['id']) && isset($receiptDetailRow['tbltId']) && $emdImRow['ID'] == $receiptDetailRow['tbltId']) {
                                        $detailsRow['detailId'] = $receiptDetailRow['id'];
                                        $detailsRow['tbltId'] = $emdPackItem['ID'];
                                        $detailsRow['packGroup'] = $emdImRow['PACKGROUP'];                                        
                                        unset($receiptDetails[$rek]);
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
                
                $ebarimtDetails[] = $detailsRow;
            }
            
            $dataParams['insAmt']   = number_format($sumInsAmt, 0, '.', '');
            $dataParams['totalAmt'] = sprintf("%.2f", $sumTotalAmt);
            $dataParams['detCnt'] = count($ebarimtDetails);
            
            $dataParams['ebarimtDetails'] = $ebarimtDetails;
        
            if (isset($getToken['access_token'])) {

                $accessToken = $getToken['access_token'];
                $getSendData = self::emdSendData($accessToken, $dataParams);

                if (isset($getSendData['msg']) && isset($getSendData['code']) && $getSendData['code'] == '200') {

                    $isEmdSent = 1;  

                } else {
                    
                    $isEmdSent = 0;
                    
                    if (isset($getSendData['error_description'])) {
                        $errorMessage = 'Send: '.(isset($getSendData['error']) ? $getSendData['error'] : 'null').' - '.$getSendData['error_description'];
                    } else {
                        $errorMessage = 'Send: Response Null';
                    }
                }

            } else {
                
                $isEmdSent = 0;  
                
                if (isset($getToken['error_description'])) {
                    $errorMessage = 'Get Token: '.(isset($getToken['error']) ? $getToken['error'] : 'null').' - '.$getToken['error_description'];
                } else {
                    $errorMessage = 'Get Token: Response Null';
                }
            }
            
            $headerParams = array(
                'cipherCode'        => $prescription['cipherCode'], 
                'patientLastName'   => $prescription['patientLastName'], 
                'patientFirstName'  => $prescription['patientFirstName'], 
                'patientRegNo'      => $prescription['patientRegNo'], 
                'receiptNumber'     => $prescription['receiptNumber']
            );
            
            /*$params = array(
                'salesInvoiceId'    => $invoiceId, 
                
                'hosOfficeName'     => $prescription['hosOfficeName'], 
                'hosSubOffName'     => $prescription['hosSubOffName'], 
                'tbltCount'         => $prescription['tbltCount'], 
                'hosName'           => $prescription['hosName'], 
                'receiptType'       => $prescription['receiptType'], 
                'receiptDiag'       => $prescription['receiptDiag'], 
                'status'            => $prescription['status'], 
                
                'receiptDate'       => date('Y-m-d H:i:s', substr($prescription['receiptDate'], 0, 10)), 
                'receiptExpireDate' => date('Y-m-d H:i:s', substr($prescription['receiptExpireDate'], 0, 10)), 
                'receiptPrintedDate'=> date('Y-m-d H:i:s', substr($prescription['receiptPrintedDate'], 0, 10)), 
                
                'isSent'            => $isEmdSent, 
                'errorMsg'          => $errorMessage, 
                'sendJson'          => json_encode($dataParams, JSON_UNESCAPED_UNICODE)
            );
            
            $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'smSalesInvoicePrescriptionDV_001', array_merge($headerParams, $params));*/
            
            $dbParams = array(
                'ID'                   => getUID(), 
                'SALES_INVOICE_ID'     => $invoiceId, 
                'RECEIPT_ID'           => issetParam($prescription['id']), 
                'RECEIPT_NUMBER'       => $prescription['receiptNumber'], 
                'RECEIPT_DATE'         => date('Y-m-d H:i:s', substr($prescription['receiptDate'], 0, 10)), 
                'RECEIPT_TYPE'         => $prescription['receiptType'], 
                'RECEIPT_DIAG'         => $prescription['receiptDiag'], 
                'RECEIPT_EXPIRE_DATE'  => date('Y-m-d H:i:s', substr($prescription['receiptExpireDate'], 0, 10)), 
                'RECEIPT_PRINTED_DATE' => date('Y-m-d H:i:s', substr($prescription['receiptPrintedDate'], 0, 10)), 
                'STATUS'               => $prescription['status'], 
                'HOS_OFFICE_NAME'      => $prescription['hosOfficeName'], 
                'HOS_SUB_OFF_NAME'     => $prescription['hosSubOffName'], 
                'CIPHER_CODE'          => $prescription['cipherCode'], 
                'TBLT_COUNT'           => $prescription['tbltCount'], 
                'HOS_NAME'             => $prescription['hosName'], 
                'PATIENT_LAST_NAME'    => $prescription['patientLastName'], 
                'PATIENT_FIRST_NAME'   => $prescription['patientFirstName'], 
                'PATIENT_REG_NO'       => $prescription['patientRegNo'], 
                'ERROR_MSG'            => $errorMessage, 
                'IS_SENT'              => $isEmdSent
            );
            
            $dbInsert = $this->db->AutoExecute('SM_SALES_INVOICE_PRESCRIPTION', $dbParams);
            
            if ($dbInsert) {
                
                $this->db->UpdateClob('SM_SALES_INVOICE_PRESCRIPTION', 'SEND_JSON', json_encode($dataParams, JSON_UNESCAPED_UNICODE), 'ID = '.$dbParams['ID']);
                
                if (isset($prescription['receiptDetails'])) {
                    
                    $this->db->UpdateClob('SM_SALES_INVOICE_PRESCRIPTION', 'DETAIL_JSON', json_encode($prescription['receiptDetails'], JSON_UNESCAPED_UNICODE), 'ID = '.$dbParams['ID']);
                }
            }

            return $headerParams;
        }
        
        return false;
    }
    
    public function emdFindAll($accessToken, $page = 1, $size = 50) {
        
        $url = self::$emdWsUrl.'tablet/findAll?access_token='.$accessToken.'&page='.$page.'&size='.$size;
        $ch  = curl_init($url);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json;charset=UTF-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array('access_token' => $accessToken, 'page' => $page, 'size' => $size)));

        $str = curl_exec($ch);
        curl_close($ch); 

        return json_decode($str, true);
    }
    
    public function emdCheck100Person($accessToken, $regNo) {
        
        $url = self::$emdWsUrl.'tablet/check100person?access_token='.$accessToken.'&regno='.$regNo;
        $ch  = curl_init($url);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json;charset=UTF-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $str = curl_exec($ch);
        curl_close($ch); 

        return json_decode($str, true);
    }
    
    public function posDiscountDrugImportModel() {

        $cashierInfo = null;
        $sessionEmployeeId = Ue::sessionEmployeeId();        
        $param = array(
            'systemMetaGroupId' => self::$cashierInfoByEmpIdDvId,
            'showQuery' => 0,
            'ignorePermission' => 1, 
            'criteria' => array(
                'employeeId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $sessionEmployeeId
                    )
                )
            )
        );        
        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if (isset($data['result']) && isset($data['result'][0])) {
            $cashierInfo = $data['result'][0];
        }
        if ($cashierInfo) {
            $getToken = self::emdGetToken($cashierInfo['clientid'], $cashierInfo['clientsecret']);
        } else {
            $getToken = self::emdGetToken();
        }
        
        if (isset($getToken['access_token'])) {
            
            $access_token = $getToken['access_token'];
            $strArr = self::emdFindAll($access_token, 1, 2000);
            
            if (isset($strArr['data'])) {

                $this->db->Execute('DELETE FROM IM_DISCOUNT_DRUG');
                
                //$total    = $strArr['total'];
                $itemList = $strArr['data'];
                
                $userId      = Ue::sessionUserKeyId();
                $currentDate = Date::currentDate();

                foreach ($itemList as $row) {
                    
                    if ($row['status'] != 0) {
                        
                        $insertParam = array(
                            'ID'              => $row['id'], 
                            'TBLTNAMEMON'     => $row['tbltNameMon'], 
                            'TBLTNAMEINTER'   => $row['tbltNameInter'], 
                            'TBLTNAMESALES'   => $row['tbltNameSales'], 
                            'TBLTTYPE'        => $row['tbltType'], 
                            'TBLTSIZEUNIT'    => $row['tbltSizeUnit'], 
                            'TBLTSIZEMIXTURE' => $row['tbltSizeMixture'], 
                            'TBLTMANUFACTURE' => $row['tbltManufacture'], 
                            'TBLTCOUNTRYID'   => $row['tbltCountryId'], 
                            'TBLTBARCODE'     => $row['tbltBarCode'], 
                            'TBLTLIFEDATE'    => $row['tbltLifeDate'], 
                            'TBLTISDISCOUNT'  => $row['tbltIsDiscount'], 
                            'STATUS'          => $row['status'], 
                            'TBLTDISCOUNTPERC'=> $row['tbltDiscountPerc'], 
                            'TBLTPACKINGCNT'  => $row['tbltPackingCnt'], 
                            'TBLTMAXPRICE'    => $row['tbltMaxPrice'], 
                            'TBLTDISCOUNTAMT' => $row['tbltDiscountAmt'], 
                            'TBLTTYPENAME'    => $row['tbltTypeName'], 
                            'TBLTGROUP'       => $row['tbltGroup'], 
                            'TBLTDIAGNOSIS'   => $row['tbltDiagnosis'], 
                            'TBLTMAXDAY'      => $row['tbltMaxDay'], 
                            'DESCRIPTION'     => $row['description'], 
                            'GROUPCODE'       => $row['groupCode'], 
                            'COSTVALUE'       => $row['costValue'], 
                            'TBLTREGCODE'     => $row['tbltRegCode'],
                            'PACKGROUP'       => $row['packGroup'],
                            'TBLTUNITPRICE'   => $row['tbltUnitPrice'],
                            'TBLTUNITDISAMT'  => $row['tbltUnitDisAmt'],
                            'TBLTUPDATEDESC'  => $row['tbltUpdateDesc'],
                            'TBLTUPDATEDATE'  => $row['tbltUpdateDate'],
                            'TBLTBCODE'       => $row['tbltBCode'],
                            'TBLTSCODE'       => $row['tbltSCode'],
                            'TBLTCODE'        => $row['tbltCode'],	                            
                            'CREATED_USER_ID' => $userId, 
                            'CREATED_DATE'    => $currentDate  
                        );

                        $this->db->AutoExecute('IM_DISCOUNT_DRUG', $insertParam);
                    }
                }

                $response = array('status' => 'success', 'message' => Lang::line('POS_0123'));

            } else {
                $response = array('status' => 'error', 'message' => $strArr['error_description']);
            }
            
        } else {
            $response = array('status' => 'error', 'message' => $getToken['error_description']);
        }
        
        return $response;
    }

    public function posDiscountDrugImportViewModel() {

        $cashierInfo = null;
        $sessionEmployeeId = Ue::sessionEmployeeId();        
        $param = array(
            'systemMetaGroupId' => self::$cashierInfoByEmpIdDvId,
            'showQuery' => 0,
            'ignorePermission' => 1, 
            'criteria' => array(
                'employeeId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $sessionEmployeeId
                    )
                )
            )
        );        
        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if (isset($data['result']) && isset($data['result'][0])) {
            $cashierInfo = $data['result'][0];
        }
        if ($cashierInfo) {
            $getToken = self::emdGetToken($cashierInfo['clientid'], $cashierInfo['clientsecret']);
        } else {
            $getToken = self::emdGetToken();
        }
        
        if (isset($getToken['access_token'])) {
            
            $access_token = $getToken['access_token'];
            $strArr = self::emdFindAll($access_token, 1, 2000);
            
            if (isset($strArr['data'])) {

                $itemList = $strArr['data'];
                
                $userId      = Ue::sessionUserKeyId();
                $currentDate = Date::currentDate();
                $insertParam = array();

                foreach ($itemList as $row) {
                    
                    if ($row['status'] != 0) {
                        
                        $insertParam[] = $row;

                    }
                }

                $response = array('status' => 'success', 'data' => $insertParam);

            } else {
                $response = array('status' => 'error', 'message' => $strArr['error_description']);
            }
            
        } else {
            $response = array('status' => 'error', 'message' => $getToken['error_description']);
        }
        
        return $response;
    }    
    
    public function defaultPrintCopies() {
        
        $result = array(
            array(
                'active' => '', 
                'checked' => ''
            ), 
            array(
                'active' => '', 
                'checked' => ''
            ), 
            array(
                'active' => '', 
                'checked' => ''
            ), 
            array(
                'active' => '', 
                'checked' => ''
            ), 
            array(
                'active' => '', 
                'checked' => ''
            )
        );
        
        $result[Config::getFromCache('CONFIG_POS_PRINT_COPIES_COUNT')] = array('active' => ' active', 'checked' => ' checked="checked"');
        
        return $result;
    }
    
    public function emdReturnUrlModel($returnBillId) {
        
        $getToken = self::emdGetToken();

        if (isset($getToken['access_token'])) {

            $getEmdReturn = self::emdReturn($getToken['access_token'], $returnBillId);

            return $getEmdReturn;
            
        } else {
            return $getToken;
        }
    }
    
    public function emdCheckPosRnoUrlModel($returnBillId) {
        
        $getToken = self::emdGetToken();

        if (isset($getToken['access_token'])) {

            $getEmdReturn = self::emdCheckPosRno($getToken['access_token'], $returnBillId);

            return $getEmdReturn;
            
        } else {
            return $getToken;
        }
    }
    
    public function emdCheckPosRno($accessToken, $returnBillId) {
                
        $url = self::$emdWsUrl.'ebarimt/checkPosRno?access_token='.$accessToken.'&posRno='.$returnBillId;
        
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json;charset=UTF-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $str = curl_exec($ch);
        curl_close($ch); 

        return json_decode($str, true);
    }
    
    public function emdBatch($accessToken, $dataParams) {
                
        $url = self::$emdWsUrl.'ebarimt/batch?access_token='.$accessToken;

        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json;charset=UTF-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataParams);

        $str = curl_exec($ch);
        $err = curl_error($ch);
        
        curl_close($ch); 
        
        if ($err) {
            return array('status' => 'error', 'code' => 'curl', 'msg' => $err);
        } else {
            return json_decode($str, true);
        }
    }
    
    public function getNotVatCustomerListModel() {
        $data = $this->db->GetAll("SELECT CUSTOMER_CODE, CUSTOMER_NAME, POSITION_NAME FROM CRM_CUSTOMER WHERE IS_VAT_RELEASED = 1 ORDER BY CUSTOMER_NAME ASC");
        return $data;
    }
    
    public function getDiscountTypeListModel() {
        $data = $this->db->GetAll("SELECT ID, NAME, IS_PLUS FROM SM_DISCOUNT_TYPE");
        return $data;
    }
    
    public function setPOSOrderSessionModel() {
        
        $sessionUserKeyId = Ue::sessionUserKeyId();
        
        $param = array(
            'systemMetaGroupId' => self::$storeInfoByUserIdDvId,
            'showQuery' => 0,
            'ignorePermission' => 1, 
            'criteria' => array(
                'filterUserId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $sessionUserKeyId
                    )
                )
            )
        );

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result']) && isset($data['result'][0])) {
            $storeInfo = $data['result'][0];
        }

        if (isset($storeInfo)) {
                        
            Session::set(SESSION_PREFIX.'storeId', $storeInfo['storeid']);
            Session::set(SESSION_PREFIX.'storeCode', $storeInfo['code']);
            Session::set(SESSION_PREFIX.'storeName', $storeInfo['name']);
            Session::set(SESSION_PREFIX.'isEditBasketPrice', issetParam($storeInfo['iseditbasketprice']));
            // Session::set(SESSION_PREFIX.'isEditBasketPrice', '1');
            
            return array('status' => 'success');
            
        } else {
            return array('status' => 'error', 'message' => Lang::line('POS_0073'));
        }
    }
    
    public function orderSaveModel() {
        
        parse_str($_POST['paymentData'], $paymentData);
        parse_str($_POST['itemData'], $itemData);
        
        if (isset($_POST['kitchenData'])) {
            parse_str($_POST['kitchenData'], $kitchenData);
        } else {
            $kitchenData = array();
        }
        
        $sPrefix       = SESSION_PREFIX;
        $storeId       = Session::get($sPrefix.'storeId');
        $orderTypeId   = '';
        $recipientName = '';
        $recipientLastName = '';
        $recipientRegisterNum = '';
        $cityId        = '';
        $isBasket      = false;
        $isTempInvoice = false;
        $isBasketSelected = false; 
        $posTypeCode = Session::get(SESSION_PREFIX.'posTypeCode');
        
        if (isset($paymentData['isBasket']) && $paymentData['isBasket'] == '1') {
            $isBasket = true;
            $orderTypeId = 204;
        } else {
            $orderTypeId   = issetParam($paymentData['orderTypeId']);
            $cityId        = issetParam($paymentData['cityId']);
            $recipientName = issetParam($paymentData['recipientName']);
            $recipientLastName = issetParam($paymentData['recipientLastName']);
            $recipientRegisterNum = issetParam($paymentData['recipientRegisterNum']);
        }
        $orderTypeId = $orderTypeId ? $orderTypeId : '204';
        
        if (isset($paymentData['isBasketSelected']) && $paymentData['isBasketSelected'] == '1') {
            $isBasketSelected = true; 
        }
        
        $currentDate    = Date::currentDate('Y-m-d H:i:s');
        
        $totalAmount    = Number::decimal($paymentData['payAmount']);
        $vatAmount      = Number::decimal($paymentData['vatAmount']);
        $cityTaxAmount  = Number::decimal($paymentData['cityTaxAmount']);
        $discountAmount = Number::decimal($paymentData['discountAmount']);
        $subTotal       = $totalAmount + $discountAmount;
        
        if (isset($paymentData['phoneNumber'])) {
            $phoneNumber = $paymentData['phoneNumber'];
        } else {
            $phoneNumber = '';
        }

        $lockerId = '';
        if (isset($paymentData['lockerId']) && $paymentData['lockerId']) {
            $lockerId = explode('_', $paymentData['lockerId']);
            $lockerId = $lockerId[0];
        }
            
        $params = array(
            'bookTypeId'           => $orderTypeId, 
            'invoiceDate'          => $currentDate, 
            'createdDateTime'      => $currentDate, 
            'storeId'              => $storeId,
            'totalCityTaxAmount'   => $cityTaxAmount, 
            'subTotal'             => $subTotal, 
            'discount'             => $discountAmount, 
            'vat'                  => $vatAmount, 
            'cardId'               => $lockerId, 
            'total'                => $totalAmount, 
            'deliveryContactPhone' => $phoneNumber, 
            'deliveryContactName'  => $recipientName, 
            'deliveryContactLastname' => $recipientLastName, 
            'deliveryRegisterNum'  => $recipientRegisterNum, 
            'locationId'           => issetParam($paymentData['deskId']), 
            'salesPersonId'        => issetParam($paymentData['waiterId']),
            'description'          => issetParam($paymentData['description'])
        );
        
        if ($orderTypeId == 91) {
            $params['bankAccountId'] = $paymentData['bankAccountId'];
            $params['invoiceTypeId'] = $paymentData['invoiceTypeIdForm'];
            $params['dueDate']       = $paymentData['expireDate'].':01';
        }
        
        if (isset($paymentData['customerId'])) {
            $params['customerId'] = $paymentData['customerId'];
            if ($posTypeCode === '3') {
                $params['customerId'] = '';
            }
            $isTempInvoice = true;
        }
        if ($lockerId) {
            $isTempInvoice = true;
        }
        
        $paramsDtl = array();
        $itemIds   = $itemData['itemId'];
        $cashRegisterId = Session::get(SESSION_PREFIX.'cashRegisterId');
        $storeIdSession = Session::get(SESSION_PREFIX.'storeId');
        $orderData = Input::post('orderData');
        $resetOrderDtl = Input::post('resetOrderDtl');
        
        if ($orderData && array_key_exists('salesorderdetailid', $orderData['data']['pos_item_list_get'][0])) {
            $orderData = Arr::groupByArray($orderData['data']['pos_item_list_get'], 'salesorderdetailid');
        }
        
        foreach ($itemIds as $k => $itemId) {
            
            $itemQty        = Number::decimal($itemData['quantity'][$k]);
            $salePrice      = $itemData['salePrice'][$k];
            $salePriceInput = isset($itemData['salePriceInput']) ? Number::decimal($itemData['salePriceInput'][$k]) : 0;
            $totalPrice     = $salePrice * $itemQty;
            $totalAmount1   = $itemData['totalPrice'][$k];
            $unitAmount     = $salePriceInput > 0 ? $salePriceInput : $salePrice;
            $lineTotalAmount= $salePriceInput > 0 ? $totalAmount1 : $totalPrice;
            
            $isVat          = $itemData['isVat'][$k];            
            $noVatPrice     = $itemData['noVatPrice'][$k];
            
            $isDiscount         = $itemData['isDiscount'][$k];
            $discountPercent    = $itemData['discountPercent'][$k];
            $dtlDiscountPercent = $discountPercent;
            $dtlDiscountAmount  = $itemData['discountAmount'][$k];
            $unitReceivable     = $itemData['unitReceivable'][$k];
            $unitDiscount       = 0;
            $dtlUnitDiscount    = 0;
            $lineTotalDiscount  = 0;
            
            $isDelivery     = $itemData['isDelivery'][$k];
            $employeeId     = $itemData['employeeId'][$k];
            $sectionId     = isset($itemData['sectionId']) ? $itemData['sectionId'][$k] : '';
            
            $discountEmployeeId   = $itemData['discountEmployeeId'][$k];
            $discountTypeId       = isset($itemData['discountTypeId'][$k]) ? $itemData['discountTypeId'][$k] : '';
            $storeWarehouseId     = $itemData['storeWarehouseId'][$k];
            $deliveryWarehouseId  = $itemData['deliveryWarehouseId'][$k];
            
            $isCityTax      = $itemData['isCityTax'][$k];
            $cityTax        = ($isCityTax == '1' ? $itemData['cityTax'][$k] : 0);
            $lineTotalCityTaxAmount = $itemData['lineTotalCityTax'][$k];
            $lineTotalVat = $itemData['lineTotalVat'][$k];
            $salesorderdetailid = isset($itemData['salesorderdetailid']) ? (isset($itemData['salesorderdetailid'][$k]) ? $itemData['salesorderdetailid'][$k] : '') : '';
            $vatPercent     = $isCityTax == '1' ? '1.11' : ($isVat == '1' ? '1.1' : '0');
            
            if ($isVat == '1' && $isDiscount != '1') {
                
                /*if ($isCityTax == '1') {                    
                    $unitVat = number_format($itemData['cityTax'][$k], 6, '.', '');                    
                } else {
                    $unitVat = number_format($salePrice - $noVatPrice, 6, '.', '');
                }*/
                $unitVat = number_format($salePrice - $noVatPrice, 6, '.', '');
                
            } elseif ($isVat == '1' && $isDiscount == '1') {
                
                $unitDiscount = $itemData['unitDiscount'][$k];
                $unitAmount   = $dtlDiscountAmount;
                
                /*if ($isCityTax == '1') {
                    
                    $unitVat = number_format($itemData['cityTax'][$k], 6, '.', '');
                    
                } else {
                    
                    $unitVat = number_format($salePrice - $noVatPrice, 6, '.', '');
                    
                }*/
                $unitVat = number_format($unitAmount - $noVatPrice, 6, '.', '');
                
                $lineTotalAmount = $unitAmount * $itemQty;
                $dtlUnitDiscount = $itemData['discountAmount'][$k];
                
                if ($unitDiscount > 0) {
                    $lineTotalDiscount = $unitDiscount * $itemQty;
                } else {
                    $unitDiscount      = 0;
                    $discountPercent   = 0;
                    $lineTotalDiscount = 0;
                    
                    $printUnitAmount      = $dtlDiscountAmount;
                    $printLineTotalAmount = $lineTotalAmount;
                }
                
            } else {
                
                if ($isDiscount == '1') {
                    
                    $unitVat        = 0;
                    $lineTotalVat   = 0;
                    $unitAmount     = $dtlDiscountAmount;
                    
                    $unitDiscount      = $itemData['unitDiscount'][$k];
                    $lineTotalDiscount = $unitDiscount * $itemQty;
                    $lineTotalAmount = $unitAmount * $itemQty;
                    $dtlUnitDiscount = $itemData['discountAmount'][$k];
                    
                } else {
                    $unitVat = $lineTotalVat = 0;
                }
            }
            
            $itemCode       = $itemData['itemCode'][$k];
            $printItemName  = $itemData['itemName'][$k];
            $cashierId      = isset($itemData['cashRegisterId']) && issetParam($itemData['cashRegisterId'][$k]) != '' ? $itemData['cashRegisterId'][$k] : $cashRegisterId;
            $storeId        = isset($itemData['storeId']) && issetParam($itemData['storeId'][$k]) != '' ? $itemData['storeId'][$k] : $storeIdSession;
            $serialNumber   = $itemData['serialNumber'][$k];
            $barCode        = $itemData['barCode'][$k];
            $barCode        = ($barCode ? $barCode : '132456789');
            
            $isJob          = $itemData['isJob'][$k];
            $jobId          = '';
            $dtlCouponKeyId = '';
            
            /*$lineCustomerId = isset($itemData['customerId']) && issetParam($itemData['customerId'][$k]) ? $itemData['customerId'][$k] : issetParam($paymentData['customerId']);*/
            $lineCustomerId = issetParam($itemData['customerId'][$k]);
            
            if ($isJob == '1') {
                
                $jobId      = $itemId;
                $itemId     = '';
                $isDelivery = 0;
                
            } elseif ($isJob == '2') {
                
                $dtlCouponKeyId = $itemId;
                $jobId          = '';
                $itemId         = '';
                $isDelivery     = 0;
            }
            
            $paramsDtl[$k] = array(
                'itemid'                 => $itemId, 
                'jobid'                  => $jobId, 
                'invoiceqty'             => $itemQty, 
                'invoiceqtytemp'         => $itemQty, 
                
                'unitprice'              => $salePrice,
                'linetotalprice'         => $totalPrice,  
                'unitamount'             => $unitAmount, 
                'linetotalamount'        => $lineTotalAmount,  
                'iscitytax'              => $isCityTax,  
                'citytax'                => $cityTax,  
                'linetotalcitytaxamount' => $lineTotalCityTaxAmount,  
                
                'isvat'                  => $isVat, 
                'percentvat'             => $vatPercent, 
                'unitvat'                => $unitVat, 
                'linetotalvat'           => $lineTotalVat,
                
                'percentdiscount'        => $discountPercent, 
                'unitdiscount'           => $unitDiscount, 
                'linetotaldiscount'      => $lineTotalDiscount, 
                'unitreceivable'         => $unitReceivable, 
                
                'discountpercent'        => $dtlDiscountPercent, 
                'discountamount'         => $dtlUnitDiscount, 
                
                'isdelivery'             => $isDelivery,  
                'employeeid'             => $employeeId, 
                'discounttypeid'         => $discountTypeId, 
                'serialnumber'           => $serialNumber, 
                'isremoved'              => 0, 
                
                'itemcode'               => $itemCode, 
                'itemname'               => $printItemName, 
                'barcode'                => $barCode, 
                'couponkeyid'            => $dtlCouponKeyId,
                'sectionid'              => $sectionId,                
                'cashregisterid'         => $cashierId,
                'storeid'                => $storeId,
                'salesorderdetailid'     => $salesorderdetailid,
                'id'                     => $salesorderdetailid,
                'lineTotalBonusAmount'   => (issetParam($itemData['lineTotalBonusAmount'][$k]) ? Number::decimal($itemData['lineTotalBonusAmount'][$k]) : '0'),
                'unitBonusAmount'        => issetParamZero($itemData['unitBonusAmount'][$k]),
                'unitBonusPercent'       => issetParamZero($itemData['unitBonusPercent'][$k]),
                'discountEmployeeId'     => $discountEmployeeId ? $discountEmployeeId : $itemData['editPriceEmployeeId'][$k],
                'customerId'             => $lineCustomerId,
                'description'            => issetParam($kitchenData['foodDescription']) ? issetParam($kitchenData['foodDescription'][$itemId]) : issetParam($itemData['returnDescription'][$k]),
                'ordernumber'            => issetParam($kitchenData['foodNumber']) ? issetParam($kitchenData['foodNumber'][$itemId]) : '',
                'customeridsaved'        => issetParam($itemData['customerIdSaved'][$k]),
                'guestname'              => issetParam($itemData['guestName'][$k]),
                'salesPersonId'          => issetParam($itemData['salesPersonId'][$k]),
            );
            
            if ($orderData && $salesorderdetailid && array_key_exists($salesorderdetailid, $orderData)) {
                
                if ($orderData[$salesorderdetailid]['row']['invoiceqty'] < $paramsDtl[$k]['invoiceqty']) {
                    
                    $paramsDtl[$k]['invoiceqty'] = $orderData[$salesorderdetailid]['row']['invoiceqty'];
                    $calcRow = $this->posCalcRow($paramsDtl[$k]);
                    $paramsDtl[$k] = $calcRow;
                }
                
            } elseif ($resetOrderDtl != 'resetOrderDtl' && isset($paymentData['basketInvoiceId']) && empty($paymentData['basketInvoiceId'])) {
                if ($posTypeCode === '3') {
                    $paramsDtl[$k]['isPrint'] = '1';
                }
            }
            
            $giftJsonStr = trim($itemData['giftJson'][$k]);
            
            if ($giftJsonStr != '') {
                
                $itemPackageList = $itemGiftList = array();
                $giftJsonArray = json_decode(html_entity_decode($giftJsonStr), true);
                
                foreach ($giftJsonArray as $giftJsonRow) {
                    
                    $giftJsonRow['isDelivery'] = isset($giftJsonRow['isDelivery']) ? $giftJsonRow['isDelivery'] : 0;
                    $giftJsonRow['invoiceqty'] = $itemQty;
                    $giftJsonRowMerge          = array();
                    
                    $itemPackageList[] = array(
                        'packageDtlId'     => issetParam($giftJsonRow['packagedtlid']),
                        'qty'              => $itemQty, 
                        'discountPolicyId' => issetParam($giftJsonRow['policyid']), 
                        'isDelivery'       => issetParam($giftJsonRow['isDelivery'])
                    );
                    
                    if (issetParam($giftJsonRow['coupontypeid']) == '') {
                        
                        $giftJsonRow['isgift']     = 1;
                        $giftJsonRow['employeeId'] = $employeeId; 
                        $giftJsonRow['itemid']     = issetParam($giftJsonRow['promotionitemid']); 
                        $giftJsonRow['jobid']      = issetParam($giftJsonRow['jobid']); 
                        
                        $itemGiftPrice = issetParamZero($giftJsonRow['saleprice']);
                        
                        if ($itemGiftPrice > 0 && ($giftJsonRow['discountamount'] > 0 || $giftJsonRow['discountpercent'] > 0)) {
                            
                            $giftDiscountAmount = $itemGiftPrice;
                                
                            if ($giftJsonRow['discountamount'] > 0) {

                                $giftDiscountAmount = $giftJsonRow['discountamount'];

                            } elseif ($giftJsonRow['discountpercent'] > 0) {

                                $giftDiscount = ($giftJsonRow['discountpercent'] / 100) * $itemGiftPrice;
                                $giftDiscountAmount = $itemGiftPrice - $giftDiscount;
                            }
                                                    
                            $itemGiftPrice       = $itemGiftPrice - $giftDiscountAmount;
                            $giftLineTotalAmount = $itemGiftPrice * $itemQty;
                            
                            $giftLineTotalDiscount = 0;
                            $giftUnitVat = number_format($itemGiftPrice - (number_format($itemGiftPrice / 1.1, 2, '.', '')), 2, '.', '');
                            
                            $giftJsonRow['saleprice'] = $itemGiftPrice;
                            $giftJsonRow['unitPrice'] = $itemGiftPrice;
                            $giftJsonRow['lineTotalPrice'] = $giftLineTotalAmount;
                            
                            $giftJsonRow['unitAmount'] = $itemGiftPrice;
                            $giftJsonRow['lineTotalAmount'] = $giftLineTotalAmount;
                
                            $giftJsonRow['percentVat'] = 10;
                            $giftJsonRow['unitVat'] = $giftUnitVat;
                            $giftJsonRow['lineTotalVat'] = number_format($giftJsonRow['unitVat'] * $itemQty, 2, '.', '');
                            
                            $giftJsonRow['percentDiscount'] = 0;
                            $giftJsonRow['unitDiscount'] = 0;
                            $giftJsonRow['lineTotalDiscount'] = $giftLineTotalDiscount;
                            
                            $giftJsonRowMerge['invoiceqty'] = 1;
                            $giftJsonRowMerge['unitPrice'] = $itemGiftPrice;
                            $giftJsonRowMerge['lineTotalPrice'] = $itemGiftPrice;
                            
                            $giftJsonRowMerge['unitAmount'] = $itemGiftPrice;
                            $giftJsonRowMerge['lineTotalAmount'] = $itemGiftPrice;
                
                            $giftJsonRowMerge['percentVat'] = 10;
                            $giftJsonRowMerge['unitVat'] = number_format($itemGiftPrice - (number_format($itemGiftPrice / 1.1, 2, '.', '')), 2, '.', '');
                            $giftJsonRowMerge['lineTotalVat'] = $giftJsonRowMerge['unitVat'];
                            
                        }
                        
                        if ($giftJsonRow['jobid'] == '') {
                            
                            if ($giftJsonRow['isDelivery'] == 1) {
                                $giftJsonRow['warehouseId'] = $deliveryWarehouseId; 
                            } else {
                                $giftJsonRow['warehouseId'] = $storeWarehouseId; 
                            }
                            
                            $giftJsonRow['deliveryWarehouseId'] = $deliveryWarehouseId;
                            $giftJsonRow['storeWarehouseId']    = $storeWarehouseId;
                        }
                        
                        $itemGiftList[] = $giftJsonRow;
                    } 
                }
                
                $paramsDtl[$k]['SDM_SALES_ORDER_ITEM_PACKAGE'] = $itemPackageList;
                $paramsDtl[$k]['POS_SDM_SALES_ORDER_ITEM_DTL'] = $itemGiftList;
            }
            
            if ($isJob == '0' || $isJob == '') {
                
                if ($isDelivery == 1) {
                    $paramsDtl[$k]['warehouseId'] = $deliveryWarehouseId;
                } else {
                    $paramsDtl[$k]['warehouseId'] = $storeWarehouseId;
                }
                
                $paramsDtl[$k]['deliveryWarehouseId'] = $deliveryWarehouseId;
                $paramsDtl[$k]['storeWarehouseId']    = $storeWarehouseId;
            }
        }
        
        if ($orderData) {

            $paramsss = array(
                'ID'               => getUID(), 
                'WEB_SERVICE_NAME' => 'OLD ORDER DATA', 
                'WEB_SERVICE_URL'  => '', 
                'CREATED_DATE'     => Date::currentDate(), 
                'USER_ID'          => Ue::sessionUserKeyId()
            );            
            $this->db->AutoExecute('SYSINT_SERVICE_METHOD_LOG', $paramsss);
            $this->db->UpdateClob('SYSINT_SERVICE_METHOD_LOG', 'REQUEST_STRING', json_encode($orderData), 'ID = '.$paramsss['ID']);             

            $paramsss = array(
                'ID'               => getUID(), 
                'WEB_SERVICE_NAME' => 'NEW ORDER DATA', 
                'WEB_SERVICE_URL'  => '', 
                'CREATED_DATE'     => Date::currentDate(), 
                'USER_ID'          => Ue::sessionUserKeyId()
            );            
            $this->db->AutoExecute('SYSINT_SERVICE_METHOD_LOG', $paramsss);
            $this->db->UpdateClob('SYSINT_SERVICE_METHOD_LOG', 'REQUEST_STRING', json_encode($paramsDtl), 'ID = '.$paramsss['ID']);             

            /*foreach ($paramsDtl as $orow) {
                if ($orow['salesorderdetailid'] && array_key_exists($orow['salesorderdetailid'], $orderData)) {

                    if ($orderData[$orow['salesorderdetailid']]['row']['invoiceqty'] < $orow['invoiceqtytemp']) {
                        $qty = $orow['invoiceqtytemp'] - $orderData[$orow['salesorderdetailid']]['row']['invoiceqty'];

                        $orow['isPrint'] = '1';
                        $orow['invoiceQty'] = $qty;
                        unset($orow['salesorderdetailid']);
                        unset($orow['id']);
                        $calcRow = $this->posCalcRow($orow);
                        array_push($paramsDtl, $calcRow);
                    }
                }
            }*/         
        }                
      
        $params['POS_SDM_SALES_ORDER_ITEM_DTL'] = $paramsDtl;
        
        if ($cityId) {
            $params['NEXT_SDM_DELIVERY_BOOK'] = array(
                'cityId'       => $cityId, 
                'districtId'   => issetParam($paymentData['districtId']), 
                'cityStreetId' => issetParam($paymentData['streetId']), 
                'contactName'  => $recipientName, 
                'address'      => $paymentData['detailAddress'], 
                'coordinate'   => $paymentData['coordinate'], 
                'what3words'   => $paymentData['what3words'], 
                'phoneNumber1' => $phoneNumber, 
                'dueDate'      => $paymentData['dueDate'].':01'
            );
        }
        
        if ($posTypeCode != '3') {
            
            if (Input::postcheck('selectedOrderId')) {
                $prevInfo = array('id' => Input::post('selectedOrderId'));
            } else {

                if ($isBasket == false) {

                    $guestName = issetParam($paymentData['guestName']);
                    $prevInfo = self::getAddressInfoByPhoneModel($phoneNumber, issetParam($paymentData['deskId']), $params['customerId'], $guestName);

                    if ($posTypeCode === '4' && isset($prevInfo['data']) && count($prevInfo['data']) > 1) {
                        jsonResponse($prevInfo);
                    } elseif ($posTypeCode === '4' && isset($prevInfo['data'])) {
                        $prevInfo = $prevInfo['data'][0];
                    }

                } elseif ($lockerId) {
                    $prevInfo = null;

                    if ($isTempInvoice) {
                        $prevInfo = self::getTempInvoiceByLockerIdModel($lockerId);
                    }            
                } else {

                    $prevInfo = null;

                    if ($isTempInvoice) {
                        $prevInfo = self::getTempInvoiceByCustomerIdModel($params['customerId']);
                    }
                }
            }
        
            if (isset($paymentData['invoiceId']) && $paymentData['invoiceId']) {

                $salesOrderId = $paymentData['invoiceId'];

                $prevInfo['id'] = $salesOrderId;
                $prevInfo['total'] = 0;

                $finInvoiceId = $this->db->GetOne("
                    SELECT 
                        F.INVOICE_ID  
                    FROM META_DM_RECORD_MAP MP 
                        INNER JOIN SDM_ORDER_BOOK B ON MP.SRC_RECORD_ID = B.SALES_ORDER_ID 
                        INNER JOIN FIN_INVOICE F ON MP.TRG_RECORD_ID = F.INVOICE_ID
                    WHERE B.SALES_ORDER_ID = $salesOrderId"); 

                if ($finInvoiceId) {

                    $finInvData = array(
                        'BANK_ACCOUNT_ID' => $paymentData['bankAccountId'], 
                        'INVOICE_TYPE_ID' => $paymentData['invoiceTypeIdForm'], 
                        'DUE_DATE'        => $paymentData['expireDate'].':01', 
                        'DESCRIPTION'     => $paymentData['description'], 
                        'TOTAL_PRICE'     => $totalAmount, 
                        'TOTAL_AMOUNT'    => $totalAmount, 
                        'TOTAL_VAT'       => $totalAmount - number_format($totalAmount / 1.1, 2, '.', ''), 
                        'MODIFIED_DATE'   => $currentDate, 
                        'PHONE_NUMBER'    => $phoneNumber, 
                        'ADDRESS'         => $paymentData['detailAddress']
                    );

                    $this->db->AutoExecute('FIN_INVOICE', $finInvData, 'UPDATE', 'INVOICE_ID = '.$finInvoiceId);
                }

                $salesOrderData = array(
                    'DUE_DATE'               => $paymentData['dueDate'].':01', 
                    'DESCRIPTION'            => $paymentData['description'], 
                    'SUBTOTAL'               => $totalAmount, 
                    'TOTAL'                  => $totalAmount, 
                    'CARD_ID'                => $lockerId, 
                    'VAT'                    => $totalAmount - number_format($totalAmount / 1.1, 2, '.', ''), 
                    'MODIFIED_DATE'          => $currentDate, 
                    'DELIVERY_CONTACT_PHONE' => $phoneNumber, 
                    'DELIVERY_ADDRESS'       => $paymentData['detailAddress'], 
                    'DELIVERY_CITY_ID'       => $cityId, 
                    'DELIVERY_DISTRICT_ID'   => issetParam($paymentData['districtId']), 
                    'DELIVERY_STREET_ID'     => issetParam($paymentData['streetId']), 
                    'DELIVERY_CONTACT_NAME'  => $recipientName, 
                    'DELIVERY_CONTACT_LASTNAME' => $recipientLastName, 
                    'DELIVERY_REGISTER_NUM'  => $recipientRegisterNum
                );

                $this->db->AutoExecute('SDM_ORDER_BOOK', $salesOrderData, 'UPDATE', 'SALES_ORDER_ID = '.$salesOrderId);

                $this->db->Execute("DELETE FROM SDM_SALES_ORDER_ITEM_PACKAGE WHERE SALES_ORDER_DETAIL_ID IN (SELECT SALES_ORDER_DETAIL_IDFROM SDM_SALES_ORDER_ITEM_DTL WHERE SALES_ORDER_ID = $salesOrderId)");
                $this->db->Execute("DELETE FROM SDM_SALES_ORDER_ITEM_DTL WHERE SALES_ORDER_ID = $salesOrderId");
            }
        }
        
        if (($posTypeCode === '3' || $posTypeCode === '4') && isset($paymentData['basketInvoiceId']) && !empty($paymentData['basketInvoiceId'])) {
            
            $params['id']    = $paymentData['basketInvoiceId'];
            $params['total'] = $totalAmount;
            $params['locationId'] = $paymentData['deskId'];
                    
            unset($params['invoiceNumber']);
            unset($params['invoiceDate']);
            unset($params['createdDateTime']);
            
            $processCode = 'POS_SALES_PERSON_DTL_ORDER_001';
            
            if ($resetOrderDtl != 'resetOrderDtl') {

                $salesOrderDetails = $this->getSDMSalesOrderDetailsModel($params['id']);

                if ($salesOrderDetails) {

                    $items = $params['POS_SDM_SALES_ORDER_ITEM_DTL'];
                    $prevItems = $salesOrderDetails;

                    $newItems = $deleteDtls = array();

                    foreach ($items as $item) {

                        $itemid = $item['itemid'];
                        $crmsavedid = $item['customeridsaved'];
                        $crmid = $item['guestname'];
                        $qty = $item['invoiceqtytemp'];
                        $qtySum = $qtyCusSum = 0;

                        foreach ($prevItems as $prevItem) {

                            if ($itemid == $prevItem['itemid'] && $crmid == $prevItem['customername']) {

                                $qtySum += $prevItem['orderqty'];

                            } elseif ($itemid == $prevItem['itemid'] && $crmsavedid == $prevItem['customername'] && $crmid != $prevItem['customername']) {
                                $qtyCusSum += $prevItem['orderqty'];
                            }
                        }

                        if ($posTypeCode === '3') {
                            $item['isPrint'] = '1';
                        }
                        /**
                         * Change customer || add customer
                         */
                        if ($qtyCusSum) {
                            if ($qtyCusSum != $qty) {
                                $itemTemp = $item;
                                $itemTemp['invoiceqty'] = $qty - $qtyCusSum;
                                $item['invoiceqty'] = $qty - $itemTemp['invoiceqty'];
                                $newItems[] = $itemTemp;
                            }
                            $item['isPrint'] = '0';
                        }

                        if ($qtySum) {

                            if ($qtySum != $qty) {
                                $item['invoiceqty'] = $qty - $qtySum;
                                $newItems[] = $item;
                            }
                            
                        } else {
                            $newItems[] = $item;
                        }
                    }

                    foreach ($prevItems as $prevItem) {
                        
                        if (!$prevItem['orderqty']) {
                            continue;
                        }
                        
                        $isDelete = true;

                        foreach ($items as $item) {
                            
                            $crmid = $item['guestname'];
                            
                            if ($item['itemid'] == $prevItem['itemid'] && $crmid == $prevItem['customername']) {
                                $isDelete = false;
                            }
                        }

                        if ($isDelete) {
                            $deleteDtls[] = $prevItem;
                        }
                    }
                    
                    if ($deleteDtls) {
                        foreach ($deleteDtls as $deleteDtl) {
                            $this->ws->runSerializeResponse(self::$gfServiceAddress, 'DELETE_SDM_SALES_ORDER_ITEM_DTL_DV_005', array('id' => $deleteDtl['salesorderdetailid']));
                        }
                    }

                    $newOrderItems = [];
                    
                    if ($newItems) {
                        
                        $newOrderItems['pos_sdm_sales_order_item_dtl'] = [];
                        foreach ($newItems as $n => $newItem) {
							
                            $customerId = (issetParam($newItem['customerId'])) ? $newItem['customerId'] : issetParam($newItem['customerid']);

                            unset($newItem['customerId']);
                            unset($newItem['customerid']);
							
                            $newItems[$n] = $this->posCalcRow($newItem);
                            $newItems[$n]['customerId'] = $customerId;
                            if ($posTypeCode === '3') {
                                $newItems[$n]['isPrint'] = $newItem['isPrint'];
                            }

                            if (issetParam($newItem['isPrint']) == '1') {
                                $newItems[$n]['orderQty'] = $newItems[$n]['invoiceqty'];
                                array_push($newOrderItems['pos_sdm_sales_order_item_dtl'], $newItems[$n]);
                            }
                        }
                        
                        $params['POS_SDM_SALES_ORDER_ITEM_DTL'] = $newItems;
                        
                        $this->ws->runSerializeResponse(self::$gfServiceAddress, 'POS_SALES_PERSON_DTL_ORDER_001', $params);

                        self::insertActionLog('POS_SALES_PERSON_DTL_ORDER_001['.$params['id'].'] - PRINTED ORDER DATA NEW', $params, "");
                    }
                    
                    $isNewUpdate = true;
                }
            }
            
            /*if ($resetOrderDtl == 'resetOrderDtl') {
                $this->ws->runSerializeResponse(self::$gfServiceAddress, 'UPDATE_SSOID_IS_PRINT_004', array('id' => $params['id']));
                $isNewUpdate = true;
            }*/
            
        } elseif (isset($prevInfo) && $prevInfo && Input::post('isInv') != 1) {
            
            $params['id']    = $prevInfo['id'];
            $params['total'] = $totalAmount;
                    
            unset($params['invoiceNumber']);
            unset($params['invoiceDate']);
            unset($params['createdDateTime']);
            
            $processCode = 'POS_SALES_PERSON_DTL_ORDER_001';
            
            if (isset($paymentData['basketInvoiceId']) && !empty($paymentData['basketInvoiceId'])) {
                $prm = array('salesOrderId' => $prevInfo['id']);
                $this->ws->runSerializeResponse(self::$gfServiceAddress, 'DELETE_SDM_SALES_ORDER_ITEM_DTL_DV_005', $prm);

                $paramsss = array(
                    'ID'               => getUID(), 
                    'WEB_SERVICE_NAME' => 'DELETE ORDER DATA - DELETE_SDM_SALES_ORDER_ITEM_DTL_DV_005 - '.$prevInfo['id'], 
                    'WEB_SERVICE_URL'  => '', 
                    'CREATED_DATE'     => Date::currentDate(), 
                    'USER_ID'          => Ue::sessionUserKeyId()
                );            
                $this->db->AutoExecute('SYSINT_SERVICE_METHOD_LOG', $paramsss);
                $this->db->UpdateClob('SYSINT_SERVICE_METHOD_LOG', 'REQUEST_STRING', json_encode($prm), 'ID = '.$paramsss['ID']);      
            }            
            
        } else {
            
            $invoiceNumber = self::getPosInvoiceNumber('1522946993342', array('bookTypeId' => $orderTypeId, 'storeId' => $storeId));
            $params['invoiceNumber'] = $invoiceNumber;
            
            $processCode = 'POS_SALES_PERSON_ORDER_001';
        }

        $paramsss = array(
            'ID'               => getUID(), 
            'WEB_SERVICE_NAME' => 'PRINTED ORDER DATA', 
            'WEB_SERVICE_URL'  => '', 
            'CREATED_DATE'     => Date::currentDate(), 
            'USER_ID'          => Ue::sessionUserKeyId()
        );            
        $this->db->AutoExecute('SYSINT_SERVICE_METHOD_LOG', $paramsss);
        $this->db->UpdateClob('SYSINT_SERVICE_METHOD_LOG', 'REQUEST_STRING', json_encode($paramsDtl), 'ID = '.$paramsss['ID']);            

        if (isset($isNewUpdate)) {
            $result = array('status' => 'success', 'result' => array_merge(array('id' => $params['id']), $newOrderItems));
        } else {
            $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, $processCode, $params);        
        }
        
        if ($result['status'] == 'success') {                        
            
            $response = array('status' => 'success', 'message' => 'Амжилттай хадгалагдлаа.', 'id' => $result['result']['id'], 'orderTypeId' => $orderTypeId, 'orderData' => $result['result']);
            self::insertActionLog($processCode.'['.$result['result']['id'].']-Online_POS Save Order', $params, $result);
            
            $response['basketCount'] = self::getBasketOrderBookCountModel($storeId);
            
            if (Config::getFromCache('IS_USE_POSAPI_V3')) {                                

                $taxInvParam = array(
                    'id' => $result['result']['id']
                );
                $posApiArray = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'posMainInvoiceSendTax', $taxInvParam);                

                if (isset($posApiArray['result']) && $posApiArray['result']['status'] === 'SUCCESS') {
                } else {
                    $posApiArray['warningMsg'] = issetParam($posApiArray['result']['message']) ? $posApiArray['result']['message'] : 'posMainInvoiceSendTax хариу ирсэнгүй!';
                    $response = array('status' => 'error', 'message' => $posApiArray['warningMsg'], 'id' => $result['result']['id'], 'orderTypeId' => $orderTypeId, 'orderData' => $result['result']);
                }
            }            
            
        } else {
            self::insertActionLog($processCode.'-Online_POS Save Order Error', $params, $result);
            $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
        
        return $response;
    }
    
    public function getBasketOrderBookCountModel($storeId = null) {
        
        $posTypeCode = Session::get(SESSION_PREFIX.'posTypeCode');
        $tempInvoiceDvId = Config::get('POS_CLOSE_CHECK_DATAVIEW') ? Config::get('POS_CLOSE_CHECK_DATAVIEW') : Config::get('CONFIG_POS_TEMP_INVOICE_DVID', 'postype='.$posTypeCode);
        $tempInvoiceDvId = $tempInvoiceDvId ? $tempInvoiceDvId : '1529014380513';        
        $criteria = array(
            'storeId' => array(
                array(
                    'operator' => '=',
                    'operand' => Session::get(SESSION_PREFIX.'storeId')
                )
            )
        );
        if ($posTypeCode == 3 || $posTypeCode == 4) {
            $criteria['cashRegisterId'] = array(
                array(
                    'operator' => '=',
                    'operand' => Session::get(SESSION_PREFIX.'cashRegisterId')
                )
            );
        }
        $param = array(
            'systemMetaGroupId' => $tempInvoiceDvId,
            'showQuery' => 0,
            'ignorePermission' => 1, 
            'criteria' => $criteria
        );
        
        if (Input::postCheck('customerIdFromSidebar')) {
            $param['criteria']['customerId'][] = array('operator' => '=', 'operand' => Input::post('customerIdFromSidebar'));
        }        

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result']) && isset($data['result'][0])) {
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);            
            return count($data['result']);
        } else {
            return '0';
        }
    }
    
    public function getBasketLockerOrderBookCountModel($lockerId) {
        $currentDate = Date::currentDate('Y-m-d');
        
        $sql = "SELECT COUNT(SALES_ORDER_ID) 
               FROM SDM_ORDER_BOOK
               WHERE BOOK_TYPE_ID = 204 AND (IS_USED IS NULL OR IS_USED = 0) AND CARD_ID = $lockerId AND ".$this->db->SQLDate('Y-m-d', 'CREATED_DATE')." = '$currentDate'";
        
        return $this->db->GetOne($sql);
    }
    
    public function getInvoiceTypeListModel() {
        
        $param = array(
            'systemMetaGroupId' => '1463453144617229',
            'ignorePermission' => 1, 
            'showQuery' => 0
        );

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result']) && isset($data['result'][0])) {
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);
            return $data['result'];
        } else {
            return array();
        }
    }
    
    public function getInvoiceTypeList2Model() {
        
        $param = array(
            'systemMetaGroupId' => '164983650531510',
            'ignorePermission' => 1, 
            'showQuery' => 0
        );

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result']) && isset($data['result'][0])) {
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);
            return $data['result'];
        } else {
            return array();
        }
    }
    
    public function getAddressInfoByInvoiceIdModel($invoiceId) {
        
        $param = array(
            'systemMetaGroupId' => '1527135895812594',
            'showQuery' => 0,
            'ignorePermission' => 1, 
            'criteria' => array(
                'salesOrderId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $invoiceId
                    )
                )
            )
        );

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result']) && isset($data['result'][0])) {
            return $data['result'][0];
        }
        
        return null;
    }
    
    public function getAddressInfoByPhoneModel($phoneNumber, $deskId, $customerId, $guestName) {
        
        $param = array(
            'systemMetaGroupId' => '1527159973154766',
            'showQuery' => 0,
            'ignorePermission' => 1, 
            'criteria' => array(
                'phoneNumber' => array(
                    array(
                        'operator' => '=',
                        'operand' => $phoneNumber
                    )
                ),
                'customerId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $customerId
                    )
                ),
            )
        );
        if (Session::get(SESSION_PREFIX.'posTypeCode') === '3') {        
            unset($param['criteria']['customerId']);
            $param['criteria']['filterLocationId'] = array(
                array(
                    'operator' => '=',
                    'operand' => $deskId
                )                
            );
        }
        if (Session::get(SESSION_PREFIX.'posTypeCode') === '4') {        
            $param['criteria']['cashRegisterId'] = array(
                array(
                    'operator' => '=',
                    'operand' => Session::get(SESSION_PREFIX.'cashRegisterId')
                )                
            );
            $param['criteria']['filterGuestName'] = array(
                array(
                    'operator' => '=',
                    'operand' => $guestName
                )                
            );
        }

        //dd($param);
        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        //dd($data);

        if (isset($data['result']) && isset($data['result'][0])) {
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);                
//            if (Session::get(SESSION_PREFIX.'posTypeCode') === '4' && count($data['result']) > 1) {
//                return array(
//                    'chooseorder' => true,
//                    'data' => $data['result']
//                );
//            } else {
//            }
            return $data['result'][0];
        }
        
        return array();
    }
    
    public function getTempInvoiceByCustomerIdModel($customerId) {
        
        /*$param = array(
            'systemMetaGroupId' => '1527159973154766',
            'showQuery' => 0,
            'ignorePermission' => 1, 
            'criteria' => array(
                'filterCustomerId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $customerId
                    )
                )
            )
        );

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result']) && isset($data['result'][0])) {
            return $data['result'][0];
        }*/
        
        $currentDate = Date::currentDate('Y-m-d');
        $row = null;
        
        if ($posTypeCode === '4') {
            
            $row = $this->db->GetRow("
                SELECT 
                    SOB.SALES_ORDER_ID AS ID, 
                    SOB.SUBTOTAL, 
                    SOB.DISCOUNT, 
                    SOB.VAT, 
                    SOB.TOTAL,
                    SOD.CASH_REGISTER_ID
                FROM SDM_ORDER_BOOK SOB
                INNER JOIN ( SELECT SALES_ORDER_ID, CASH_REGISRE_ID FROM SDM_SALES_ORDER_ITEM_DTL GROUP BY SALES_ORDER_ID, CASH_REGISRE_ID  ) SOD ON SOB.SALES_ORDER_ID = SOD.SALES_ORDER_ID
                WHERE SOB.BOOK_TYPE_ID = 204 
                    AND SOB.CUSTOMER_ID = $customerId 
                    AND (NVL(SOB.IS_USED, 0) IS NULL OR IS_USED = 0) 
                    AND ".$this->db->SQLDate('Y-m-d', 'CREATED_DATE')." = '$currentDate'");

            if ($row) {
                $row = Arr::changeKeyLower($row);
            }            
            
        } else {
        
            $row = $this->db->GetRow("
                SELECT 
                    SALES_ORDER_ID AS ID, 
                    SUBTOTAL, 
                    DISCOUNT, 
                    VAT, 
                    TOTAL 
                FROM SDM_ORDER_BOOK 
                WHERE BOOK_TYPE_ID = 204 
                    AND CUSTOMER_ID = $customerId 
                    AND (IS_USED IS NULL OR IS_USED = 0) 
                    AND ".$this->db->SQLDate('Y-m-d', 'CREATED_DATE')." = '$currentDate'");

            if ($row) {
                $row = Arr::changeKeyLower($row);
            }
        }
        
        return $row;
    }
    
    public function getTempInvoiceByLockerIdModel($salesOrderId) {
        
        if (empty($salesOrderId)) {
            return '';
        }
        
        $row = $this->db->GetRow("
            SELECT 
                SALES_ORDER_ID AS ID, 
                SUBTOTAL, 
                DISCOUNT, 
                VAT, 
                TOTAL 
            FROM SDM_ORDER_BOOK 
            WHERE BOOK_TYPE_ID = 204 AND (IS_USED IS NULL OR IS_USED = 0) 
                AND CARD_ID = $salesOrderId");
        
        if ($row) {
            $row = Arr::changeKeyLower($row);
        }
        return $row;
    }
    
    public function getPaymentTypeIdByInvoiceTypeIdModel($invoiceTypeId) {
        
        $paymentTypeId = $this->db->GetOne("SELECT PAYMENT_TYPE_ID FROM SM_PAYMENT_TYPE_DTL WHERE INVOICE_TYPE_ID = $invoiceTypeId");
        
        return $paymentTypeId;
    }
    
    public function getBillPromotionModel($invoiceId = '') {
        
        $content = '';
        loadPhpQuery();
        
        if (Config::getFromCache('CONFIG_POS_BILL_PROMOTION')) {
            
            $param = array(
                'systemMetaGroupId' => '1527133671131775',
                'showQuery' => 0,
                'ignorePermission' => 1, 
                'criteria' => array(
                    'storeId' => array(
                        array(
                            'operator' => '=',
                            'operand' => Session::get(SESSION_PREFIX.'storeId')
                        )
                    ),
                    'id' => array(
                        array(
                            'operator' => '=',
                            'operand' => $invoiceId
                        )
                    )
                )
            );

            $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

            if (isset($data['result']) && isset($data['result'][0])) {
                
                unset($data['result']['aggregatecolumns']);
                unset($data['result']['paging']);
                
                $pageBreak = '<div style="page-break-after: always;"></div>';
                $promotion = $data['result'];
                
                foreach ($promotion as $row) {
                    $promotionText = $row['promotiontext'];
                    $picture       = $row['picture'];                    
                    $isCut         = $row['iscut'];
                    $isContent     = false;
                    $rowContent    = '';
                    
                    if ($row['reporttemplateid']) {
                        $rowContent = (new Mdtemplate())->getTemplateByArguments($row['reporttemplateid'], 'selfDvId', array('id' => $invoiceId));                
                        
                        if ($row['reporttemplateid'] === '1603761550087') {
                            $tableHtml = '';
                            $detailHtml = phpQuery::newDocumentHTML($rowContent);
                            $tbody = $detailHtml->find('tbody');     
                            $tbodylen = $detailHtml->find('tbody')->length();
                            for($i = 0; $i <= $tbodylen; $i++) {
                                $tableHtml .= '<table style="width:100%"><tbody>';
                                $tableHtml .= $detailHtml->find('tbody:eq('.$i.')')->html();
                                $tableHtml .= '</tbody></table><div style="page-break-after: always;"></div>';
                            }                            
                            $rowContent = $tableHtml;
                        }

                        if ($isCut == '1') {
                            $content .= $pageBreak.$rowContent;
                        } else {
                            $content .= $rowContent;
                        }

                    } else {

                        if ($promotionText != '') {
                            $rowContent .= html_entity_decode($promotionText);
                            $isContent = true;
                        }
                        
                        if ($picture != '' && file_exists($picture)) {
                            $rowContent .= '<img src="'.$picture.'" style="max-width: 100%;height: auto;vertical-align: middle;">';
                            $isContent = true;
                        }
                        
                        if ($isContent) {
                            if ($isCut == '1') {
                                $content .= $pageBreak.$rowContent;
                            } else {
                                $content .= $rowContent;
                            }
                        }                        
                    }
                }
            }
        }
        
        return $content;
    }
    
    public function setZeroVatAmount($params) {
        
        $params['vat'] = 0;
        $details = $params['POS_SM_SALES_INVOICE_DETAIL'];
        
        foreach ($details as $k => $d) {
            $params['POS_SM_SALES_INVOICE_DETAIL'][$k]['isVat'] = 0;
            $params['POS_SM_SALES_INVOICE_DETAIL'][$k]['percentVat'] = 0;
            $params['POS_SM_SALES_INVOICE_DETAIL'][$k]['unitVat'] = 0;
            $params['POS_SM_SALES_INVOICE_DETAIL'][$k]['lineTotalVat'] = 0;
        }
                
        return $params;
    }
    
    public function getInvoiceHeaderInfoByIdModel($id) {
        
        /*$param = array(
            'systemMetaGroupId' => '1526908302883',
            'showQuery' => 0,
            'ignorePermission' => 1, 
            'criteria' => array(
                'id' => array(
                    array(
                        'operator' => '=',
                        'operand' => $id
                    )
                )
            )
        );

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result']) && isset($data['result'][0])) {
            return $data['result'][0];
        }*/
        
        $row = $this->db->GetRow("
            SELECT
                B.BOOK_TYPE_ID AS BOOKTYPEID, 
                B.DELIVERY_CONTACT_PHONE AS DELIVERYCONTACTPHONE, 
                F.BANK_ACCOUNT_ID AS BANKACCOUNTID, 
                CB.BANK_ACCOUNT_CODE AS BANKACCOUNTCODE, 
                CB.BANK_ACCOUNT_DESC_L AS BANKACCOUNTNAME, 
                F.INVOICE_TYPE_ID AS INVOICETYPEID, 
                F.DUE_DATE AS EXPIREDATE, 
                B.DELIVERY_CONTACT_LASTNAME AS DELIVERYCONTACTLASTNAME, 
                B.DELIVERY_CONTACT_NAME AS DELIVERYCONTACTNAME, 
                B.DELIVERY_REGISTER_NUM AS DELIVERYREGISTERNUM, 
                B.DELIVERY_CITY_ID AS CITYID, 
                B.DELIVERY_DISTRICT_ID AS DISTRICTID, 
                B.DELIVERY_STREET_ID AS STREETID, 
                B.DELIVERY_ADDRESS AS DETAILADDRESS, 
                B.DUE_DATE AS DUEDATE, 
                B.DESCRIPTION 
            FROM META_DM_RECORD_MAP MP
                INNER JOIN SDM_ORDER_BOOK B ON MP.SRC_RECORD_ID = B.SALES_ORDER_ID
                LEFT JOIN FIN_INVOICE F ON MP.TRG_RECORD_ID = F.INVOICE_ID 
                LEFT JOIN CM_BANK_ACCOUNT CB ON CB.ID = F.BANK_ACCOUNT_ID 
            WHERE B.SALES_ORDER_ID = $id");
        
        if ($row) {
            return Arr::changeKeyLower($row);
        }
        
        return array();
    }
    
    public function printInvoiceModel() {
        
        $invoiceId = Input::post('id');
        
        if ($invoiceId) {
            
            $invData = self::getInvoiceMainDataModel($invoiceId);

            $hdr     = $invData['header'];
            $dtl     = $invData['detail'];
            $payment = $invData['payment'];
            
            if (!$dtl) {
                return array('status' => 'warning', 'message' => 'Баримтын дэлгэрэнгүй мэдээлэл олдсонгүй!');
            }
            
            $departmentId = $hdr['DEPARTMENT_ID'];            
            $billId       = $hdr['BILL_ID']; 
            
            $noLotteryNumber = Input::post('noLotteryNumber');
            
            if ($noLotteryNumber == '0') {
                $isLotteryNumber = true;
            } else {
                $isLotteryNumber = false;
            }
            
            if ($billId) {
                $isLotteryNumber = false;
            }
            
            if ($isLotteryNumber) {
                
                $sessionUserKeyDepartmentId = Ue::sessionUserKeyDepartmentId();
                $departmentId = $sessionUserKeyDepartmentId ? $sessionUserKeyDepartmentId : Ue::sessionDepartmentId();
        
                $orgRow = $this->db->GetRow("
                    SELECT
                        DEPARTMENT_ID 
                    FROM ORG_DEPARTMENT 
                    WHERE VATSP_NUMBER IS NOT NULL 
                        AND ROWNUM = 1 
                    START WITH DEPARTMENT_ID = ".$this->db->Param(0)."   
                    CONNECT BY PRIOR PARENT_ID = DEPARTMENT_ID", array($departmentId));

                if (isset($orgRow['DEPARTMENT_ID']) && $orgRow['DEPARTMENT_ID']) {
                    $departmentId = $orgRow['DEPARTMENT_ID'];
                }
            }
                
            $topTitle       = Session::get(SESSION_PREFIX.'posHeaderName');
            $vatNumber       = Session::get(SESSION_PREFIX.'vatNumber');
            $contactInfo     = Config::get('POS_CONTACT_INFO', 'departmentId='.$departmentId.';');
            $sessionPosLogo = Session::get(SESSION_PREFIX.'posLogo');
            $posLogo = ($sessionPosLogo ? $sessionPosLogo : 'pos-logo.png');
            
            if ($posLogo && (file_exists('storage/uploads/files/logos/' . $posLogo) || file_exists($posLogo))) {
                $posLogo = $posLogo;
            } else {
                $posLogo = 'pos-logo.png';
            }
            
            $storeName       = $hdr['STORE_NAME'];
            $cashCode        = $hdr['CASH_CODE'];
            $cashierName     = $hdr['CASHIER_NAME'];
            $billTitle       = Lang::line('POS_0080');
            $printType       = Config::getFromCache('CONFIG_POS_PRINT_TYPE');
            $invoiceNumber   = $hdr['INVOICE_NUMBER'];
            $refNumber       = $hdr['REF_NUMBER'];
            $createdDate     = $hdr['CREATED_DATE'];
            $customerNumber  = $hdr['CUSTOMER_NUMBER']; 
            $customerName    = $hdr['CUSTOMER_NAME']; 
                    
            $vatAmount   = $hdr['VAT'];
            $payAmount   = $hdr['TOTAL'];
            $cityTax     = $hdr['TOTAL_CITY_TAX_AMOUNT'];
            $totalAmount = $hdr['SUB_TOTAL'];
            $itemCount   = 0;
            
            $itemPrintList = $salesPersonCode = $giftList = $paymentDetail = $discountPart = $lottery = $qrData = '';
            $paymentDtlTemplate = self::paymentDetailTemplate();
            
            if ($dtl) {
                
                $stocks = '';
                
                if (Config::getFromCache('CONFIG_POS_HEALTHRECIPE')) {
                    $itemPrintRenderFncName = 'generatePharmacyItemRow';
                } else {
                    $itemPrintRenderFncName = 'generateItemRow';
                }
        
                foreach ($dtl as $dtlRow) {
                    
                    if ($dtlRow['IS_GIFT'] == '1') {
                        
                        $giftRow = array(
                            'itemname'       => $dtlRow['ITEM_NAME'], 
                            'coupontypename' => '', 
                            'isDelivery'     => $dtlRow['IS_DELIVERY'], 
                            'invoiceqty'     => $dtlRow['INVOICE_QTY']
                        );
                        
                        $giftList .= self::giftPrintRow($giftRow);
                        
                    } else {
                        $row = array(
                            'cityTax'        => $dtlRow['LINE_TOTAL_CITY_TAX_AMOUNT'], 
                            'itemName'       => $dtlRow['ITEM_NAME'], 
                            'salePrice'      => $dtlRow['UNIT_PRICE'], 
                            'itemQty'        => $dtlRow['INVOICE_QTY'], 
                            'totalPrice'     => $dtlRow['LINE_TOTAL_AMOUNT'], 
                            'unitReceivable' => $dtlRow['UNIT_RECEIVABLE'], 
                            'maxPrice'       => '', 
                            'isDelivery'     => $dtlRow['IS_DELIVERY']
                        );
                        $itemPrintList .= self::{$itemPrintRenderFncName}($row);

                        if ($dtlRow['SALES_PERSON_CODE']) {
                            $salesPersonCode .= $dtlRow['SALES_PERSON_CODE'].', ';
                        }
                        
                        if ($isLotteryNumber) {
                            
                            $itemName    = self::apiStringReplace($dtlRow['ITEM_NAME']);
                            $measureCode = self::convertCyrillicMongolia('ш');

                            $stocks .= "{
                                'code': '" . $dtlRow['ITEM_CODE'] . "',
                                'name': '" . $itemName . "',
                                'measureUnit': '" . $measureCode . "',
                                'qty': '" . sprintf("%.2f", $dtlRow['INVOICE_QTY']) . "',
                                'unitPrice': '" . sprintf("%.2f", $dtlRow['UNIT_AMOUNT']) . "',
                                'totalAmount': '" . sprintf("%.2f", $dtlRow['LINE_TOTAL_AMOUNT']) . "',
                                'cityTax': '" . sprintf("%.2f", $dtlRow['LINE_TOTAL_CITY_TAX_AMOUNT']) . "',
                                'vat': '" . sprintf("%.2f", $dtlRow['LINE_TOTAL_VAT']) . "',
                                'barCode': '" . $dtlRow['BARCODE'] . "'
                            }, ";  
                        }
                    }
                }
                
                $salesPersonCode = rtrim($salesPersonCode, ', ');
                $itemCount       = count($dtl);
            }
            
            if ($payment) {
                
                if ($hdr['TOTAL_CITY_TAX_AMOUNT'] > 0) {
                    $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0082'), self::posAmount($hdr['TOTAL_CITY_TAX_AMOUNT'])), $paymentDtlTemplate);
                }
        
                foreach ($payment as $paymentRow) {
                    $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array($paymentRow['PAYMENT_TYPE_NAME'], self::posAmount($paymentRow['AMOUNT'])), $paymentDtlTemplate);
                }
            }
            
            if ($hdr['CUSTOMER_NUMBER']) {
                $posBillType = 'organization';
            } else {
                $posBillType = 'person';
            }
            
            if ($isLotteryNumber) {
                
                $customerNo   = $hdr['CUSTOMER_REG_NUMBER'];
                $taxType      = 1;
                $orgName      = '';
                $orgRegNumber = '';
                
                if ($posBillType == 'person') {

                    $billType   = 1;
                    $title      = Lang::line('POS_0100');

                } elseif ($posBillType == 'organization') {

                    $billType       = 3;
                    $title          = Lang::line('POS_0101');

                    $orgRegNumber   = Str::upper($customerNo);
                    $customerNo     = self::convertCyrillicMongolia($orgRegNumber); //'0000039';
                    $orgName        = $orgRegNumber;
                }
                
                $jsonParam = "{
                    'amount': '" . sprintf("%.2f", $payAmount) . "',
                    'vat': '" . sprintf("%.2f", $vatAmount) . "',
                    'cashAmount': '0.00',
                    'nonCashAmount': '" . sprintf("%.2f", $payAmount) . "',
                    'cityTax': '" . sprintf("%.2f", $cityTax) . "',
                    'districtCode': '" . $hdr['POSAPI_DISTRICT_CODE'] . "',
                    'posNo': '" . $cashCode . "',
                    'reportMonth': '',
                    'customerNo': '" . $customerNo . "',
                    'billType': '" . $billType . "',
                    'taxType': '" . $taxType . "',
                    'billIdSuffix': '',
                    'returnBillId': '',
                    'stocks': [
                        " . rtrim($stocks, ', ') . "
                    ]
                }";
                
                $jsonParam = Str::remove_doublewhitespace(Str::removeNL($jsonParam));

                Mdpos::$eVatNumber = $vatNumber;
                Mdpos::$eStoreCode = $hdr['STORE_CODE'];
                Mdpos::$eCashRegisterCode = $cashCode;                

                $posApiArray = self::posApiFunction($jsonParam);
                $billId      = isset($posApiArray['billId']) ? $posApiArray['billId'] : null;
                
                if ($billId) {
                    
                    $createdDate = $posApiArray['date'] ? $posApiArray['date'] : Date::currentDate();
                    $lottery     = $posApiArray['lottery'];
                    $qrData      = $posApiArray['qrData'];
                    
                    $billResultParams = array(
                        'BILL_ID'          => $billId, 
                        'SALES_INVOICE_ID' => $invoiceId, 
                        'MERCHANT_ID'      => $posApiArray['merchantId'], 
                        'VAT_DATE'         => $createdDate, 
                        'SUCCESS'          => $posApiArray['success'], 
                        'WARNING_MSG'      => $posApiArray['warningMsg'],  
                        'SEND_JSON'        => $jsonParam, 
                        'STORE_ID'         => $hdr['STORE_ID'], 
                        'CUSTOMER_NUMBER'  => $orgRegNumber, 
                        'CUSTOMER_NAME'    => $orgName
                    );

                    self::createBillResultData($billResultParams); 
                    
                } else {
                    
                    $warningMsg = isset($posApiArray['warningMsg']) ? $posApiArray['warningMsg'] : (isset($posApiArray['message']) ? $posApiArray['message'] : 'PosApi алдаа: NULL');
                    
                    return array('status' => 'warning', 'message' => $warningMsg);
                }
                
                $templateContent = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.'/'.$posBillType.'/single.html');
                
                if ($posBillType == 'person') {
                    $qrLotteryTemplate = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.'/'.$posBillType.'/qrcode-lottery.html');
                    $templateContent = str_replace('{qrCodeLottery}', $qrLotteryTemplate, $templateContent);
                }
                
            } else {
                
                $templateContent = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.'/'.$posBillType.'/single.html');
            }
            
            if ($hdr['RECEIPT_NUMBER']) {

                $recipeHeaderInfo = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/pharmacy/person/recipeHeaderInfo.html');

                $recipeReplacing = array(
                    '{recipeCipherCode}'    => $hdr['CIPHER_CODE'], 
                    '{recipePatientName}'   => $hdr['PATIENT_LAST_NAME'].' '.$hdr['PATIENT_FIRST_NAME'], 
                    '{recipePatientRegNo}'  => $hdr['PATIENT_REG_NO'], 
                    '{recipeReceiptNumber}' => $hdr['RECEIPT_NUMBER']
                );

                $recipeHeaderInfo = strtr($recipeHeaderInfo, $recipeReplacing);
                $templateContent  = str_replace('{recipeHeaderInfo}', $recipeHeaderInfo, $templateContent);

            } else {
                $templateContent = str_replace('{recipeHeaderInfo}', '', $templateContent);
            }

            $this->view->getApiNameInfo = json_decode($this->getOrganizationInfoModel(), true);   
            $this->view->getApiInfo = json_decode((new Mdpos())->getInformation(true), true);

            $replacing = array(
                '{poslogo}'         => $posLogo,
                '{companyName}'     => $topTitle,
                '{title}'           => $billTitle, 
                '{vatNumber}'       => $this->view->getApiInfo['registerNo'],
                '{vatName}'         => issetParam($this->view->getApiNameInfo['name']),
                '{contactInfo}'     => $contactInfo,
                '{date}'            => Date::formatter($createdDate, 'Y/m/d'),
                '{time}'            => Date::formatter($createdDate, 'H:i:s'),
                '{refNumber}'       => $refNumber,
                '{invoiceNumber}'   => $invoiceNumber,
                '{storeName}'       => $storeName,
                '{cashierName}'     => $cashierName,
                '{cashCode}'        => $cashCode, 
                '{salesPersonCode}' => $salesPersonCode, 
                '{salesWaiter}'     => Input::post('waiterText', ''),
                '{orderTime}'       => Input::post('posEshopOrderTime', ''), 
                '{itemList}'        => $itemPrintList,
                '{totalAmount}'     => self::posAmount($totalAmount),
                '{payAmount}'       => self::posAmount($payAmount),
                '{vatAmount}'       => self::posAmount($vatAmount),
                '{discountPart}'    => $discountPart,
                '{totalItemCount}'  => self::posAmount($itemCount), 
                '{giftList}'        => self::giftTableRender($giftList), 
                '{payment-detail}'  => $paymentDetail,
                '{lottery}'         => $lottery,
                '{qrCode}'          => self::getQrCodeImg($qrData),
                '{ddtd}'            => $billId, 
                '{customerNumber}'  => $customerNumber, 
                '{customerName}'    => $customerName, 
                '{loyaltyPart}'     => '', 
                '{upointPart}'     => '', 
                '{promotion}'       => '', 
                '{qrCodeLottery}'   => '', 
                '{lockerCode}'      => '', 
                '{serialText}'      => '', 
                '{info-ipterminal}' => '', 
                '{messageText}'     => '',                 
                '{showCustomerName}'=> '',                 
                    
                '{bonusCardNumber}'         => '', 
                '{bonusCardDiscountPercent}'=> '',
                '{bonusCardBeginAmount}'    => '', 
                '{bonusCardDiffAmount}'     => '', 
                '{bonusCardPlusAmount}'     => '', 
                '{bonusCardEndAmount}'      => '',
                '{renderreporttemplate}'    => '',
                '{discountCard}'            => ''
            );

            $internalContent = strtr($templateContent, $replacing);
            
            if ($hdr['LOTTERY_COUNT'] == '0') {
                $lotteryContent = self::printLotteryPriceInterval($invoiceId, $printType, issetParam($hdr['PHONE_NUMBER']));
            } else {
                $lotteryContent = '';
            }
                    
            $internalContent = str_replace('{lotterypart}', $lotteryContent, $internalContent);
            
            $result = array('status' => 'success', 'printData' => $internalContent);
            
        } else {
            $result = array('status' => 'error', 'message' => Lang::line('POS_0125'));
        }
        
        return $result;
    }
    
    public function printInvoiceResponseModel($isMulit = false) {
        
        $invoiceId = $isMulit ? $isMulit['loypaymentbookmap']['salesinvoiceheader']['id'] : Input::post('id');
        
        if ($invoiceId) {

            $invoiceIdPh = $this->db->Param(0);
            $bindVars = array($this->db->addQ($invoiceId));            
            $invData = $isMulit ? $isMulit['loypaymentbookmap']['salesinvoiceheader'] : Input::post('responseData');
        
            $hdr     = $invData;
            $dtl     = $invData['sm_invoice_dtl_dv'];

            $payment = $this->db->GetAll("
            SELECT 
                SP.AMOUNT, 
                PT.PAYMENT_TYPE_NAME 
            FROM SM_SALES_PAYMENT SP 
                INNER JOIN SM_PAYMENT_TYPE PT ON PT.PAYMENT_TYPE_ID = SP.PAYMENT_TYPE_ID
            WHERE SP.SALES_INVOICE_ID = $invoiceIdPh", $bindVars);
            
            if (!$dtl) {
                return array('status' => 'warning', 'message' => 'Баримтын дэлгэрэнгүй мэдээлэл олдсонгүй!');
            }
            
            $departmentId = issetParam($hdr['departmentid']);            
            $billId       = ''; 
            
            $noLotteryNumber = Input::post('noLotteryNumber');
            
            if ($noLotteryNumber == '0') {
                $isLotteryNumber = true;
            } else {
                $isLotteryNumber = false;
            }
            
            if ($billId) {
                $isLotteryNumber = false;
            }
            
            if ($isLotteryNumber) {
                
                $sessionUserKeyDepartmentId = Ue::sessionUserKeyDepartmentId();
                $departmentId = $sessionUserKeyDepartmentId ? $sessionUserKeyDepartmentId : Ue::sessionDepartmentId();
        
                $orgRow = $this->db->GetRow("
                    SELECT
                        DEPARTMENT_ID 
                    FROM ORG_DEPARTMENT 
                    WHERE VATSP_NUMBER IS NOT NULL 
                        AND ROWNUM = 1 
                    START WITH DEPARTMENT_ID = ".$this->db->Param(0)."   
                    CONNECT BY PRIOR PARENT_ID = DEPARTMENT_ID", array($departmentId));

                if (isset($orgRow['DEPARTMENT_ID']) && $orgRow['DEPARTMENT_ID']) {
                    $departmentId = $orgRow['DEPARTMENT_ID'];
                }
            }
                
            $topTitle        = $hdr['posbillprintname'] ? $hdr['posbillprintname'] : Config::getFromCache('POS_HEADER_NAME');
            $vatNumber       = $hdr['vatnumber'];
            $contactInfo     = Config::get('POS_CONTACT_INFO', 'departmentId='.$departmentId.';');
            $posLogo         = issetParam($hdr['poslogo']);
            
            if ($posLogo && file_exists($posLogo)) {
                $posLogo = $posLogo;
            } else {
                $posLogo = 'pos-logo.png';
            }
            
            $storeName       = $hdr['storename'];
            $cashCode        = $hdr['cashcode'];
            $cashierName     = $hdr['cashiername'];
            $billTitle       = Lang::line('POS_0080');
            $printType       = Config::getFromCache('CONFIG_POS_PRINT_TYPE');
            $invoiceNumber   = $hdr['invoicenumber'];
            $refNumber       = $hdr['refnumber'];
            $createdDate     = $hdr['createddatetime'];
            $customerNumber  = $hdr['customerregnumber']; 
            $customerName    = $hdr['customername']; 
                    
            $vatAmount   = $hdr['vat'];
            $payAmount   = $hdr['total'];
            $cityTax     = $hdr['citytax'];
            $totalAmount = $hdr['subtotal'];
            $itemCount   = 0;
            $paymentUpdateDtl = [];
            
            $itemPrintList = $salesPersonCode = $giftList = $paymentDetail = $discountPart = $lottery = $qrData = '';
            $paymentDtlTemplate = self::paymentDetailTemplate();
            
            if ($dtl) {
                
                $stocks = '';
                
                if (Config::getFromCache('CONFIG_POS_HEALTHRECIPE')) {
                    $itemPrintRenderFncName = 'generatePharmacyItemRow';
                } else {
                    $itemPrintRenderFncName = 'generateItemRow';
                }
        
                foreach ($dtl as $dtlRow) {
                    
                    if (issetParam($dtlRow['isgit']) == '1') {
                        
                        $giftRow = array(
                            'itemname'       => $dtlRow['itemname'], 
                            'coupontypename' => '', 
                            'isDelivery'     => issetParam($dtlRow['isdelivery']), 
                            'invoiceqty'     => $dtlRow['invoiceqty']
                        );
                        
                        $giftList .= self::giftPrintRow($giftRow);
                        
                    } else {
                        $row = array(
                            'cityTax'        => $dtlRow['linetotalcitytaxamount'], 
                            'itemName'       => $dtlRow['itemname'], 
                            'salePrice'      => $dtlRow['unitprice'], 
                            'itemQty'        => $dtlRow['invoiceqty'], 
                            'totalPrice'     => $dtlRow['totalamount'], 
                            'unitReceivable' => issetParam($dtlRow['unitreceivable']), 
                            'maxPrice'       => '', 
                            'isDelivery'     => issetParam($dtlRow['isdelivery'])
                        );                
                        $itemPrintList .= self::{$itemPrintRenderFncName}($row);

                        if (issetParam($dtlRow['salespersoncode'])) {
                            $salesPersonCode .= $dtlRow['salespersoncode'].', ';
                        }

                        $calcRow = $this->posCalcRow($dtlRow);

                        $paymentUpdateDtl[] = array(
                            'id' => $dtlRow['id'],
                            'lineTotalCityTaxAmount' => $calcRow['linetotalcitytax'],
                            'unitVat' => $calcRow['linetotalvat'] / $dtlRow['invoiceqty'],
                            'lineTotalVat' => $calcRow['linetotalvat'],
                        );
                        
                        if ($isLotteryNumber) {
                            
                            $itemName    = self::apiStringReplace($dtlRow['itemname']);
                            $measureCode = self::convertCyrillicMongolia('ш');                            

                            $stocks .= "{
                                'code': '" . $dtlRow['itemcode'] . "',
                                'name': '" . $itemName . "',
                                'measureUnit': '" . $measureCode . "',
                                'qty': '" . sprintf("%.2f", $dtlRow['invoiceqty']) . "',
                                'unitPrice': '" . sprintf("%.2f", $dtlRow['unitprice']) . "',
                                'totalAmount': '" . sprintf("%.2f", $dtlRow['totalamount']) . "',
                                'cityTax': '" . sprintf("%.2f", $calcRow['linetotalcitytax']) . "',
                                'vat': '" . sprintf("%.2f", $calcRow['linetotalvat']) . "',                                
                                'barCode': '" . $dtlRow['barcode'] . "'
                            }, ";
                        }
                    }
                }
                
                $salesPersonCode = rtrim($salesPersonCode, ', ');
                $itemCount       = count($dtl);
            }

            if ($hdr['customername']) {
                $posBillType = 'organization';
            } else {
                $posBillType = 'person';
            }
            $customerNo   = $hdr['customerregnumber'];
            
            if (Input::post('returnTypeInvoice') == 'typeSalesPayment') {
                parse_str($_POST['paymentData'], $paymentData);        
                
                $posBillType            = $paymentData['posBillType'];
                $orgRegNumber           = Str::upper(Input::param($paymentData['orgRegNumber']));
                $customerNo             = self::convertCyrillicMongolia($orgRegNumber); //'0000039';
                $changeAmount           = Number::decimal($paymentData['changeAmount']);
                $vatAmount              = Number::decimal($paymentData['vatAmount']);
                $cityTaxAmount          = Number::decimal($paymentData['cityTaxAmount']);
                $discountAmount         = Number::decimal($paymentData['discountAmount']);
                $itemDiscountAmount     = $discountAmount;
                
                $totalAmount            = Number::decimal($paymentData['payAmount']);
                $cashAmount             = Number::decimal($paymentData['cashAmount']);
                $bankAmount             = Number::decimal($paymentData['bankAmount']);
                $voucherAmount          = Number::decimal($paymentData['voucherAmount']);
                $voucher2Amount         = Number::decimal($paymentData['voucher2Amount']);
                $certificateExpenseAmt  = Number::decimal($paymentData['posCertificateExpenseAmt']);        
                $warrantyRepairAmt      = Number::decimal($paymentData['posWarrantyRepairAmt']);        
                
                $bonusCardAmount        = Number::decimal($paymentData['bonusCardAmount']);
                $bonusCardMemberShipId  = issetParam($paymentData['cardMemberShipId']);
        
                $discountActivityAmount = Number::decimal(issetParam($paymentData['discountActivityAmount']));
                $discountActivityAmount2 = $discountActivityAmount;
                $insuranceAmount = Number::decimal(issetParam($paymentData['insuranceAmount']));
                
                $totalBonusAmount       = $voucherAmount + $bonusCardAmount + $certificateExpenseAmt + $insuranceAmount;
                if (issetParam($paymentData['cardDiscountType']) == '-' && $paymentData['cardDiscountPercentAmount'] > 0) {
                    $discountAmount += Number::decimal($paymentData['cardDiscountPercentAmount']);
                }        
                $subTotal               = $totalAmount + $discountAmount;
                $printTotalAmount       = ($discountAmount < 0) ? $totalAmount : $subTotal;
                
                $accountTransferAmt     = Number::decimal($paymentData['posAccountTransferAmt']);
                $mobileNetAmt           = Number::decimal($paymentData['posMobileNetAmt']);
                $posOtherAmt            = Number::decimal($paymentData['posOtherAmt']);
                $prePaymentAmount       = Number::decimal($paymentData['prePaymentAmount']);
                $barterAmt              = Number::decimal($paymentData['posBarterAmt']);
                $leasingAmt             = Number::decimal($paymentData['posLeasingAmt']);
                $empLoanAmt             = Number::decimal($paymentData['posEmpLoanAmt']);
                $localExpenseAmt        = Number::decimal($paymentData['posLocalExpenseAmt']);        
                $posSocialpayAmt        = Number::decimal($paymentData['posSocialpayAmt']);
                $emdAmount              = Number::decimal($paymentData['posEmdAmt']);
                $candyAmount            = Number::decimal(issetParam($paymentData['posCandyAmt']));
                $upointAmount           = Number::decimal(issetParam($paymentData['posUpointAmt']));
                $candyCouponAmount      = Number::decimal(issetParam($paymentData['posCandyCouponAmt']));
                $deliveryAmount         = Number::decimal(issetParam($paymentData['posDeliveryAmt']));
                $lendMnAmount           = Number::decimal(issetParam($paymentData['posLendMnAmt']));
                $posRecievableAmt       = Number::decimal(issetParam($paymentData['posRecievableAmt']));
                $posLiciengExpenseAmt   = Number::decimal(issetParam($paymentData['posLiciengExpenseAmt']));           
                $generateVaucherAmt = 0;     

                if ($cityTaxAmount > 0) {
            
                    $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0082'), self::posAmount($cityTaxAmount)), $paymentDtlTemplate);
                }
                
                if ($cashAmount > 0) {
                    
                    $saveCashAmt = $cashAmount - $changeAmount;
                    
                    $paymentDtl[] = array(
                        'paymentTypeId' => 1, 
                        'amount'        => $saveCashAmt
                    );
                    
                    $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0083'), self::posAmount($cashAmount)), $paymentDtlTemplate);
                    
                    $generateVaucherAmt += $saveCashAmt;
                }
                
                $infoIpTerminal = '';
                
                if ($bankAmount > 0) {
                    
                    $bankAmountDtl = $paymentData['bankAmountDtl'];
                    
                    foreach ($bankAmountDtl as $b => $bankDtlAmount) {
                        
                        $bankId         = $paymentData['posBankIdDtl'][$b];
                        $bankDtlAmount  = Number::decimal($bankDtlAmount);
                                
                        if ($bankId != '' && $bankDtlAmount > 0) {
                            
                            $paymentDtl[] = array(
                                'paymentTypeId' => 2, 
                                'bankId'        => $bankId,
                                'bankCardNumber' => $paymentData['devicePan'][$b],
                                'confirmCode' => $paymentData['deviceAuthcode'][$b],
                                'cardRegisterNumber' => $paymentData['deviceRrn'][$b],
                                'terminalNumber' => $paymentData['deviceTerminalId'][$b],
                                'traceNo' => $paymentData['deviceTraceNo'][$b],
                                'amount'        => $bankDtlAmount
                            );
                            
                            $isBankCardPaid = true;
                            $infoIpTerminal .= '<tr>
                                <td>
                                    <table border="0" width="100%" style="width: 100%; table-layout: fixed; font-family: Tahoma; font-size: 9px; font-weight: normal;">
                                        <thead>
                                            <tr>
                                                <td style="text-align: left; padding: 0; width: 33%;">Карт</td>
                                                <td style="text-align: left; padding: 0; width: 17%;">З/код</td>
                                                <td style="text-align: left; padding: 0; width: 30%;">RRN</td>
                                                <td style="text-align: left; padding: 0; width: 20%;">Терминал</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="text-align: left; padding: 0; width: 33%;">'.$paymentData['devicePan'][$b].'</td>
                                                <td style="text-align: left; padding: 0; width: 17%;">'.$paymentData['deviceAuthcode'][$b].'</td>
                                                <td style="text-align: left; padding: 0; width: 30%;">'.$paymentData['deviceRrn'][$b].'</td>
                                                <td style="text-align: left; padding: 0; width: 20%;">'.$paymentData['deviceTerminalId'][$b].'</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>';
                        }
                    }
                    
                    $nonCashAmount += $bankAmount;
                    $generateVaucherAmt += $bankAmount;
                    
                    $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0084'), self::posAmount($bankAmount)), $paymentDtlTemplate);
                }
                
                if ($itemDiscountAmount > 0) {
                    $discountDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0085'), self::posAmount($itemDiscountAmount)), $paymentDtlTemplate);
                }
                
                $voucherSerialNumber = '';
                
                if ($voucherAmount > 0) {
                    
                    $voucherDtlAmount = $paymentData['voucherDtlAmount'];
                    
                    foreach ($voucherDtlAmount as $v => $voucherAmountDtl) {
                        
                        $voucherId           = $paymentData['voucherDtlId'][$v];
                        $voucherTypeId       = $paymentData['voucherTypeId'][$v];
                        $voucherAmountDtl    = Number::decimal($voucherAmountDtl);
                        $voucherSerialNumber = $paymentData['voucherDtlSerialNumber'][$v];
                        
                        if ($voucherId != '' && $voucherAmountDtl > 0 && $voucherSerialNumber != '') {
                            
                            $voucherUsedDtl[] = array(
                                'id' => $voucherId
                            );
                            
                            if ($voucherTypeId == 1) {
                                $voucherPaymentTypeId = 10;
                                $voucherTypeName = Lang::line('POS_0086');
                            } elseif ($voucherTypeId == 2) {
                                $voucherPaymentTypeId = 9;
                                $voucherTypeName = Lang::line('POS_0087');
                            } elseif ($voucherTypeId == 4) {
                                $voucherPaymentTypeId = 11;
                                $voucherTypeName = Lang::line('POS_0044');
                            } else {
                                $voucherPaymentTypeId = 10;
                                $voucherTypeName = Lang::line('POS_0088');
                            }
                            
                            $paymentDtl[] = array(
                                'paymentTypeId' => $voucherPaymentTypeId, 
                                'amount'        => $voucherAmountDtl
                            );
                            
                            $discountDetail .= str_replace(array('{labelName}', '{amount}'), array($voucherTypeName, self::posAmount($voucherAmountDtl)), $paymentDtlTemplate);
                        }
                    }
                    
                    //$nonCashAmount += $voucherAmount;
                }   
                
                $voucherSerialNumber = '';
                
                if ($voucher2Amount >= 0 && isset($paymentData['voucher2DtlAmount'])) {
                    
                    $voucherDtlAmount    = $paymentData['voucher2DtlAmount'];
        
                    foreach ($voucherDtlAmount as $v => $voucherAmountDtl) {
                        $voucherId           = $paymentData['voucher2DtlId'][$v];
                        $voucherTypeId       = $paymentData['voucher2TypeId'][$v];
                        $voucherSerialNumber = $paymentData['voucher2DtlSerialNumber'][$v];
                        $voucherAmountDtl = Number::decimal($voucherAmountDtl);
                        
                        //if ($voucherId != '' && $voucherAmountDtl >= 0 && $voucherSerialNumber != '') {
                        if ($voucherId != '' && $voucherAmountDtl >= 0) {
                            
                            $paymentDtl[] = array(
                                'paymentTypeId' => 34, 
                                'amount'        => $voucherAmountDtl
                            );
        
                            // $params['couponKeyId'] = $voucherId;
                            // $params['outAmt'] = $voucher2Amount;
        
                            $voucherTypeName = Lang::line('POS_0214');
                            $discountDetail .= str_replace(array('{labelName}', '{amount}'), array($voucherTypeName, self::posAmount($voucherAmountDtl)), $paymentDtlTemplate);
                            
                            // $params['LOY_PAYMENT_BOOK_MAP_STORE'][] = array(
                            //     'LOY_PAYMENT_BOOK_STORE' => array(
                            //         'LOY_PAYMENT_DTL_STORE' => array(
                            //             'outAmt'      => $voucherAmountDtl, 
                            //             'couponKeyId' => $voucherId 
                            //         )
                            //     )
                            // );
                            
                            $nonCashAmount += $voucherAmountDtl;
                        
                            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array($voucherTypeName, self::posAmount($voucherAmountDtl)), $paymentDtlTemplate);
                            
                            $discountDetail .= str_replace(array('{labelName}', '{amount}'), array('Эхний үлдэгдэл', self::posAmount($paymentData['cardBeginAmountCoupon'])), $paymentDtlTemplate);
                            $discountDetail .= str_replace(array('{labelName}', '{amount}'), array('Эцсийн үлдэгдэл', self::posAmount($paymentData['cardEndAmountCoupon'])), $paymentDtlTemplate);
                        }
                    }
                }
        
                if ($discountActivityAmount2 > 0) {
                        
                    $paymentDtl[] = array(
                        'paymentTypeId' => 45, 
                        'customerId' => $paymentData['discountActivityCustomerId'], 
                        'amount'        => $discountActivityAmount2
                    );
                    
                    $discountDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('SM_0001'), self::posAmount($discountActivityAmount2)), $paymentDtlTemplate);
                }        
        
                if ($insuranceAmount > 0) {
                        
                    $paymentDtl[] = array(
                        'paymentTypeId' => 42, 
                        'amount'        => $insuranceAmount
                    );
                    
                    $discountDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0218'), self::posAmount($insuranceAmount)), $paymentDtlTemplate);
                }        
                
                if ($bonusCardMemberShipId != '') {
                    
                    $bonusCardMemberShipId          = $paymentData['cardMemberShipId'];
                    $bonusCardNumber                = Input::param($paymentData['cardNumber']);
                    $bonusCardDiscountPercent       = $paymentData['cardDiscountPercent'];
                    $bonusCardBeginAmount           = Number::decimal($paymentData['cardBeginAmount']);
                    $bonusCardDiscountPercentAmount = Number::decimal($paymentData['cardDiscountPercentAmount']);
                    $bonusCardEndAmount             = Number::decimal($paymentData['cardEndAmount']);
                    
                    if ($bonusCardAmount > 0) {
                        
                        $paymentDtl[] = array(
                            'paymentTypeId' => 12, 
                            'amount'        => $bonusCardAmount, 
                            'membershipId'  => $bonusCardMemberShipId
                        );
                        
                        //$nonCashAmount += $bonusCardAmount;
                        
                        $discountDetail .= str_replace(array('{labelName}', '{amount}'), array('Хөнгөлөлтийн карт', self::posAmount($bonusCardAmount)), $paymentDtlTemplate);
                    }
                    
                    if (($bonusCardDiscountPercentAmount > 0 && issetParam($paymentData['cardDiscountType']) == '+') || $bonusCardAmount > 0) {
                        
                        /*$params['LOY_PAYMENT_BOOK_MAP_STORE'][] = array(
                            'LOY_PAYMENT_BOOK_STORE' => array(
                                'bookDate'   => $currentDate, 
                                'bookNumber' => self::getPosInvoiceNumber('1489109738291'), 
                                'LOY_PAYMENT_DTL_STORE' => array(
                                    'membershipId' => $bonusCardMemberShipId, 
                                    'inBonusAmt'   => $bonusCardDiscountPercentAmount,  
                                    'outBonusAmt'  => $bonusCardAmount, 
                                    'description'  => 'pos'
                                )
                            )
                        );*/
        
                        /*$params['LOY_PAYMENT_BOOK_MAP_STORE']['LOY_PAYMENT_BOOK_STORE']['bookDate']   = $currentDate;
                        $params['LOY_PAYMENT_BOOK_MAP_STORE']['LOY_PAYMENT_BOOK_STORE']['bookNumber'] = self::getPosInvoiceNumber('1489109738291');
                        
                        $params['LOY_PAYMENT_BOOK_MAP_STORE']['LOY_PAYMENT_BOOK_STORE']['LOY_PAYMENT_DTL_STORE']['membershipId'] = $bonusCardMemberShipId;
                        $params['LOY_PAYMENT_BOOK_MAP_STORE']['LOY_PAYMENT_BOOK_STORE']['LOY_PAYMENT_DTL_STORE']['inBonusAmt']   = $bonusCardDiscountPercentAmount;
                        $params['LOY_PAYMENT_BOOK_MAP_STORE']['LOY_PAYMENT_BOOK_STORE']['LOY_PAYMENT_DTL_STORE']['outBonusAmt']  = $bonusCardAmount;
                        $params['LOY_PAYMENT_BOOK_MAP_STORE']['LOY_PAYMENT_BOOK_STORE']['LOY_PAYMENT_DTL_STORE']['description']  = 'pos';*/
                    }
                    
                    if ($bonusCardBeginAmount < 1) {
                        $bonusCardEndAmount = $bonusCardDiscountPercentAmount;
                    }
                    
                    $params['cardId'] = $paymentData['cardId'];
                    
                } else {
                    $bonusCardNumber                = '00000000';
                    $bonusCardDiscountPercent       = '0';
                    $bonusCardBeginAmount           = '0';
                    $bonusCardDiscountPercentAmount = '0';
                    $bonusCardEndAmount             = '0';
                }
                
                /*if (Input::isEmpty('basketInvoiceId') == false && $getOrderBook = $this->getInvoiceByIdModel(array('id' => Input::post('basketInvoiceId'), 'typeid' => '1'))) {
                    if ($getOrderBook['status'] == 'success' && $getOrderBook['data'] && isset($getOrderBook['data']['loy_payment_book'])) {
                        foreach ($getOrderBook['data']['loy_payment_book'] as $brow) {
                            $params['LOY_PAYMENT_BOOK_MAP_STORE'][] = array(
                                'LOY_PAYMENT_BOOK_STORE' => array(
                                    'bookDate'   => $currentDate, 
                                    'bookNumber' => self::getPosInvoiceNumber('1489109738291'), 
                                    'LOY_PAYMENT_DTL_STORE' => array(
                                        'membershipId' => $brow['membershipid'], 
                                        'inBonusAmt'   => $brow['inamt'],  
                                        'outBonusAmt'  => '', 
                                        'customerid'  => $brow['customerid'], 
                                        'description'  => 'pos'
                                    )
                                )
                            );      
                        }
                    }
                }*/
                
                if ($accountTransferAmt > 0) {
                    
                    $isInvInfo = true;
                    
                    if (isset($paymentData['accountTransferAmountDtl'])) {
                        
                        $accountTransferAmountDtl = $paymentData['accountTransferAmountDtl'];
                    
                        foreach ($accountTransferAmountDtl as $t => $accountTransferDtlAmount) {
        
                            $accountTransferBankId    = $paymentData['accountTransferBankIdDtl'][$t];
                            $accountTransferDtlAmount = Number::decimal($accountTransferDtlAmount);
        
                            if ($accountTransferDtlAmount > 0 && $accountTransferBankId != '') {
        
                                $paymentDtl[] = array(
                                    'paymentTypeId' => 4, 
                                    'bankId'        => $accountTransferBankId, 
                                    'amount'        => $accountTransferDtlAmount, 
                                    'bankBillingId' => $paymentData['accountTransferBillingIdDtl'][$t], 
                                    'description'   => $paymentData['accountTransferDescrDtl'][$t]
                                );
                                
                                if (isset($paymentData['accountTransferBillingIdDtl'][$t]) && $paymentData['accountTransferBillingIdDtl'][$t]) {
                                    $atBankBillingIds[] = $paymentData['accountTransferBillingIdDtl'][$t];
                                }
                            }
                        }
                        
                    } else {
                        $paymentDtl[] = array(
                            'paymentTypeId' => 4, 
                            'bankId'        => $paymentData['posAccountTransferBankId'],
                            'amount'        => $accountTransferAmt
                        );
                    }
                    
                    $nonCashAmount += $accountTransferAmt;
                    $generateVaucherAmt += $accountTransferAmt;
                    
                    $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0089'), self::posAmount($accountTransferAmt)), $paymentDtlTemplate);
                }
                
                if ($mobileNetAmt > 0) {
                    
                    $isInvInfo = true;
                    
                    $paymentDtl[] = array(
                        'paymentTypeId' => 3, 
                        'bankId'        => $paymentData['posMobileNetBankId'],
                        'amount'        => $mobileNetAmt
                    );
                    
                    $nonCashAmount += $mobileNetAmt;
                    $generateVaucherAmt += $mobileNetAmt;
                    
                    $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0090'), self::posAmount($mobileNetAmt)), $paymentDtlTemplate);
                }
                
                if ($posOtherAmt > 0) {
                    
                    $isInvInfo = true;
                    
                    $paymentDtl[] = array(
                        'paymentTypeId' => 43, 
                        'amount'        => $posOtherAmt
                    );
                    
                    $nonCashAmount += $posOtherAmt;
                    
                    $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0219'), self::posAmount($posOtherAmt)), $paymentDtlTemplate);
                }
                
                if ($prePaymentAmount > 0) {
                    
                    $isInvInfo = true;
                    
                    $paymentDtl[] = array(
                        'paymentTypeId'  => 20, 
                        'extTransactionId'=> $paymentData['prePaymentCustomerId'],
                        'amount'         => $prePaymentAmount
                    );
                    
                    $nonCashAmount += $prePaymentAmount;
                    $generateVaucherAmt += $prePaymentAmount;
                    
                    $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_GLOBE_PREPAYMENT'), self::posAmount($prePaymentAmount)), $paymentDtlTemplate);
                }
                
                if ($barterAmt > 0) {
                    
                    $isInvInfo = true;
                    
                    $paymentDtl[] = array(
                        'paymentTypeId' => 5, 
                        'amount'        => $barterAmt
                    );
                    
                    $nonCashAmount += $barterAmt;
                    
                    $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0091'), self::posAmount($barterAmt)), $paymentDtlTemplate);
                }
                
                if ($leasingAmt > 0) {
                    
                    $isPut = false;
                    $isInvInfo = true;
                    
                    $paymentDtl[] = array(
                        'paymentTypeId' => 6, 
                        'bankId'        => $paymentData['posLeasingBankId'],
                        'amount'        => $leasingAmt
                    );
                    
                    $nonCashAmount += $leasingAmt;
                    
                    $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0092'), self::posAmount($leasingAmt)), $paymentDtlTemplate);
                }
                
                if ($empLoanAmt > 0) {
                    
                    $isInvInfo = true;
                    
                    $paymentDtl[] = array(
                        'paymentTypeId' => 7, 
                        'amount'        => $empLoanAmt
                    );
                    
                    $nonCashAmount += $empLoanAmt;
                    
                    $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0093'), self::posAmount($empLoanAmt)), $paymentDtlTemplate);
                }
                
                if ($localExpenseAmt > 0) {
                    
                    $isPut = false;
                    
                    $params['localExpenseType']   = 1001;
                    $params['localExpenseAmount'] = $localExpenseAmt;
                    
                    $paymentDtl[] = array(
                        'paymentTypeId' => 13, 
                        'amount'        => $localExpenseAmt
                    );
                    
                    $nonCashAmount += $localExpenseAmt;
                    
                    $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0094'), self::posAmount($localExpenseAmt)), $paymentDtlTemplate);
                    
                    $params = self::setZeroVatAmount($params);
                }
                
                if ($posLiciengExpenseAmt > 0) {
        
                    $isInvInfo = true;
                    
                    $paymentDtl[] = array(
                        'paymentTypeId' => 33, 
                        'amount'        => $posLiciengExpenseAmt
                    );
                    
                    $nonCashAmount += $posLiciengExpenseAmt;
                    
                    $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array('ХЭРЭГЛЭЭНИЙ ЛИЗИНГ', self::posAmount($posLiciengExpenseAmt)), $paymentDtlTemplate);
                    
                    $params = self::setZeroVatAmount($params);
                }
                
                if ($posSocialpayAmt > 0) {
                    
                    $paymentDtl[] = array(
                        'paymentTypeId' => 19, 
                        'extTransactionId'  => $paymentData['posSocialpayUID'],
                        'confirmCode'   => $paymentData['posSocialpayApprovalCode'],
                        'bankCardNumber'=> $paymentData['posSocialpayCardNumber'],
                        'terminalNumber'=> $paymentData['posSocialpayTerminal'],
                        'amount'        => $posSocialpayAmt
                    );
                    
                    $infoIpTerminal .= '<tr>
                        <td>
                            <table border="0" width="100%" style="width: 100%; table-layout: fixed; font-family: Tahoma; font-size: 9px; font-weight: normal;">
                                <thead>
                                    <tr>
                                        <td style="text-align: left; padding: 0; width: 50%;">Карт</td>
                                        <td style="text-align: left; padding: 0; width: 25%;">З/код</td>
                                        <td style="text-align: left; padding: 0; width: 25%;">Терминал</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="text-align: left; padding: 0; width: 50%;">'.$paymentData['posSocialpayCardNumber'].'</td>
                                        <td style="text-align: left; padding: 0; width: 25%;">'.$paymentData['posSocialpayApprovalCode'].'</td>
                                        <td style="text-align: left; padding: 0; width: 25%;">'.$paymentData['posSocialpayTerminal'].'</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>';            
                    
                    $nonCashAmount += $posSocialpayAmt;
                    
                    $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array('Social Pay', self::posAmount($posSocialpayAmt)), $paymentDtlTemplate);
                }
                
                if ($certificateExpenseAmt > 0) {
                    
                    if ($sumTotal == $certificateExpenseAmt) {
                        $isPut = false;
                    }
                    
                    $billTitle = 'ЭРХИЙН БИЧИГ';
                    
                    $paymentDtl[] = array(
                        'paymentTypeId' => 9, 
                        'amount'        => $certificateExpenseAmt
                    );
                    
                    $nonCashAmount += $certificateExpenseAmt;
                    
                    $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array('Эрхийн бичиг', self::posAmount($certificateExpenseAmt)), $paymentDtlTemplate);
                    
                    $params = self::setZeroVatAmount($params);
                }
                
                if ($emdAmount > 0) {
                    
                    $paymentDtl[] = array(
                        'paymentTypeId' => 14, 
                        'amount'        => $emdAmount
                    );
                    
                    $nonCashAmount += $emdAmount;
                    
                    $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0095'), self::posAmount($emdAmount)), $paymentDtlTemplate);
                }
                
                if ($warrantyRepairAmt > 0) {
                    
                    $paymentDtl[] = array(
                        'paymentTypeId' => 46, 
                        'amount'        => $warrantyRepairAmt
                    );
                    
                    $nonCashAmount += $warrantyRepairAmt;
                    
                    $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0221'), self::posAmount($warrantyRepairAmt)), $paymentDtlTemplate);
                }
                
                if ($deliveryAmount > 0) {
                    
                    $paymentDtl[] = array(
                        'paymentTypeId' => 17, 
                        'amount'        => $deliveryAmount
                    );
                    
                    $nonCashAmount += $deliveryAmount;
                    
                    $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array('Үлдэгдэл төлбөр', self::posAmount($deliveryAmount)), $paymentDtlTemplate);
                }
                
                if ($candyAmount > 0) {
                    
                    $candyAmountDtl = $paymentData['candyAmountDtl'];
                    
                    foreach ($candyAmountDtl as $c => $candyDtlAmount) {
                        
                        $candyTypeCode  = $paymentData['candyTypeCodeDtl'][$c];
                        $candyNumber    = $paymentData['candyDetectedNumberDtl'][$c];
                        $candyTransactionId = $paymentData['candyTransactionIdDtl'][$c];
                        $candyDtlAmount     = Number::decimal($candyDtlAmount);
                                
                        if ($candyTypeCode && $candyDtlAmount > 0) {
                            
                            $paymentDtl[] = array(
                                'paymentTypeId' => 15, 
                                'amount'        => $candyDtlAmount, 
                                'candyTypeCode' => $candyTypeCode,
                                'candyNumber'   => '',
                                'candyTransactionId' => '',
                            );
                            
                            $candyLabelName = '';
                            if ($candyTypeCode == 'qrcodegenerate' && $candyTypeCode == 'qrcoderead') {
                                $candyLabelName = '(QR код)';
                            }
                            
                            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array('Монпэй'.$candyLabelName, self::posAmount($candyDtlAmount)), $paymentDtlTemplate);
                        }
                    }
                    
                    $nonCashAmount += $candyAmount;
                }
                
                $upointPart = '';
                if ($upointAmount > 0 || issetParam($paymentData['upointBalance'])) {
                    
                    $upointAmountDtl = $paymentData['upointAmountDtl'];
                    $params['phonenumber'] = $paymentData['upointMobile'];
                    
                    if ($returnType == 'typeReduce') {
                        
                        $posUpointReturnResult = Input::post('posUpointReturnResult');
                        if ($posUpointReturnResult) {            
                            $posUpointReturnResult = json_decode(html_entity_decode($posUpointReturnResult, ENT_QUOTES, 'UTF-8'), true);
                            //$upointPart = $this->upointTableRender(Input::post('upointDetectedNumberDtl'), $paymentData['upointBalance'], $posUpointReturnResult['refund_bonus_amount'], $posUpointReturnResult['point_balance'], $posUpointReturnResult['point_balance'] - $paymentData['upointBalance']);
                            $upointPart = $this->upointTableRender(Input::post('upointDetectedNumberDtl'), $paymentData['upointBalance'], $posUpointReturnResult['refund_bonus_amount'], $posUpointReturnResult['point_balance'], 0);
                        }                                 
                        
                        foreach ($upointAmountDtl as $c => $upointDtlAmount) {                
                            $upointDtlAmount     = Number::decimal($upointDtlAmount);
        
        
                            $paymentDtl[] = array(
                                'paymentTypeId'    => 36, 
                                'amount'           => $upointDtlAmount,
                                'outAmt'           => $upointDtlAmount,
                                'extTransactionId' => $posUpointReturnResult['receipt_id'],
                                'calcAmount'       => Number::decimal($paymentData['upointPayAmount']),
                                'bankCardNumber'   => $paymentData['upointCardNumber'],
                                'inAmt'            => $posUpointReturnResult['point_balance'],
                            );
        
                        }                     
                        
                    } else {
                        
                        foreach ($upointAmountDtl as $c => $upointDtlAmount) {                
                            $upointDtlAmount     = Number::decimal($upointDtlAmount);
        
        
                            $paymentDtl[] = array(
                                'paymentTypeId'    => 36, 
                                'amount'           => $upointDtlAmount,
                                'outAmt'           => $upointDtlAmount,
                                'extTransactionId' => $resultUpoint['data']['receipt_id'],
                                'calcAmount'       => Number::decimal($paymentData['upointPayAmount']),
                                'bankCardNumber'   => $paymentData['upointCardNumber'],
                                'inAmt'            => $resultUpoint['data']['total_point'],
                            );
        
                            $upointPart = $this->upointTableRender($paymentData['upointCardNumber'], $paymentData['upointBalance'], $upointAmount, $resultUpoint['data']['point_balance'], $resultUpoint['data']['total_point']);                    
                        }     
                        
                    }
                    
                    $nonCashAmount += $upointAmount;
                }
                
                if ($candyCouponAmount > 0) {
                    
                    $candyAmountDtl = $paymentData['candyCouponAmountDtl'];
                    
                    foreach ($candyAmountDtl as $c => $candyDtlAmount) {
                        
                        $candyTypeCode  = $paymentData['candyCouponTypeCodeDtl'][$c];
                        $candyNumber    = $paymentData['candyCouponDetectedNumberDtl'][$c];
                        $candyTransactionId = $paymentData['candyCouponTransactionIdDtl'][$c];
                        $candyDtlAmount     = Number::decimal($candyDtlAmount);
                                
                        if ($candyNumber && $candyDtlAmount > 0) {
                            
                            $candyLabelName = '';
                            $paymentDtl[] = array(
                                'paymentTypeId' => 35, 
                                'amount'        => $candyDtlAmount, 
                                'candyTypeCode' => 'coupon',
                                'candyCouponCode' => $candyNumber,
                                'candyNumber'   => '',
                                'candyTransactionId' => '',
                            );                    
                            $candyLabelName = '('.$candyNumber.')';
                            
                            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array('Монпэй купон', self::posAmount($candyDtlAmount)), $paymentDtlTemplate);
                        }
                    }
                    
                    $nonCashAmount += $candyAmount;
                }
                
                if ($lendMnAmount > 0) {
                    
                    $paymentDtl[] = array(
                        'paymentTypeId' => 18, 
                        'amount'        => $lendMnAmount
                    );
                    
                    $nonCashAmount += $lendMnAmount;
                    
                    $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array('LendMN', self::posAmount($lendMnAmount)), $paymentDtlTemplate);
                }
                
                if ($posRecievableAmt > 0) {
        
                    if (isset($paymentData['posRecievableAmtDtl'])) {
                        
                        $accountTransferAmountDtl = $paymentData['posRecievableAmtDtl'];
                    
                        foreach ($accountTransferAmountDtl as $t => $accountTransferDtlAmount) {
        
                            $accountTransferDtlAmount = Number::decimal($accountTransferDtlAmount);
        
                            if ($accountTransferDtlAmount > 0) {
                                $paymentDtl[] = array(
                                    'paymentTypeId' => 22, 
                                    'customerId'    => $paymentData['recievableCustomerId'][$t], 
                                    'amount'        => $accountTransferDtlAmount, 
                                );
                            }
                        }
                        
                    } else {
                        $paymentDtl[] = array(
                            'paymentTypeId' => 22, 
                            'customerId' => Input::post('recievableCustomerId'), 
                            'amount'        => $posRecievableAmt
                        );
                    }
                    
                    $nonCashAmount += $posRecievableAmt;            
                    $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0217'), self::posAmount($posRecievableAmt)), $paymentDtlTemplate);
                }

            } elseif ($payment) {
                
                if (issetParam($hdr['linetotalcitytaxamount']) > 0) {
                    $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0082'), self::posAmount($hdr['linetotalcitytaxamount'])), $paymentDtlTemplate);
                }
        
                foreach ($payment as $paymentRow) {
                    $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array($paymentRow['PAYMENT_TYPE_NAME'], self::posAmount($paymentRow['AMOUNT'])), $paymentDtlTemplate);
                }
            }        
            
            if ($isLotteryNumber) {
                                
                $taxType      = 1;
                $orgName      = '';
                $orgRegNumber = '';
                
                if ($posBillType == 'person') {

                    $billType   = 1;
                    $title      = Lang::line('POS_0100');

                } elseif ($posBillType == 'organization') {

                    $billType       = 3;
                    $title          = Lang::line('POS_0101');

                    $orgRegNumber   = Str::upper($customerNo);
                    $customerNo     = self::convertCyrillicMongolia($orgRegNumber); //'0000039';
                    $orgName        = $orgRegNumber;
                }
                
                $jsonParam = "{
                    'amount': '" . sprintf("%.2f", $payAmount) . "',
                    'vat': '" . sprintf("%.2f", $vatAmount) . "',
                    'cashAmount': '0.00',
                    'nonCashAmount': '" . sprintf("%.2f", $payAmount) . "',
                    'cityTax': '" . sprintf("%.2f", $cityTax) . "',
                    'districtCode': '" . $hdr['posapidistrictcode'] . "',
                    'posNo': '" . $cashCode . "',
                    'reportMonth': '',
                    'customerNo': '" . $customerNo . "',
                    'billType': '" . $billType . "',
                    'taxType': '" . $taxType . "',
                    'billIdSuffix': '',
                    'returnBillId': '',
                    'stocks': [
                        " . rtrim($stocks, ', ') . "
                    ]
                }";
                
                $jsonParam = Str::remove_doublewhitespace(Str::removeNL($jsonParam));

                Mdpos::$eVatNumber = $vatNumber;
                Mdpos::$eStoreCode = $hdr['storecode'];
                Mdpos::$eCashRegisterCode = $cashCode;               

                $posApiArray = self::posApiFunction($jsonParam);
                $billId      = isset($posApiArray['billId']) ? $posApiArray['billId'] : null;
                
                if ($billId) {
                    
                    $createdDate = $posApiArray['date'] ? $posApiArray['date'] : Date::currentDate();
                    $lottery     = $posApiArray['lottery'];
                    $qrData      = $posApiArray['qrData'];
                    
                    $billResultParams = array(
                        'BILL_ID'          => $billId, 
                        'SALES_INVOICE_ID' => $invoiceId, 
                        'MERCHANT_ID'      => $posApiArray['merchantId'], 
                        'VAT_DATE'         => $createdDate, 
                        'SUCCESS'          => $posApiArray['success'], 
                        'WARNING_MSG'      => $posApiArray['warningMsg'],  
                        'SEND_JSON'        => $jsonParam, 
                        'STORE_ID'         => $hdr['storeid'], 
                        'CUSTOMER_NUMBER'  => $orgRegNumber, 
                        'TOTAL'            => $payAmount,
                        'CUSTOMER_NAME'    => $orgName
                    );

                    self::createBillResultData($billResultParams); 

                    $posApiPath = $vatNumber.'\\'.$hdr['storecode'].'\\'.$cashCode;
                    $this->ws->redirectPost(Mdpos::getPosApiServiceAddr(), array('function' => 'senddata', 'vatNumber' => $posApiPath));       
                    
                    if (!$isMulit) {
                        $paymentTypeDtl['id'] = $invoiceId;
                        $paymentTypeDtl['vat'] = $vatAmount;
                        $paymentTypeDtl['invoiceDate'] = Date::currentDate('Y-m-d');
                        $paymentTypeDtl['localExpenseType'] = null;
                        $paymentTypeDtl['localExpenseAmount'] = null;
                        $paymentTypeDtl['totalCityTaxAmount'] = $cityTax;                    
                        foreach ($paymentDtl as $keyptype => $rowptype) {
                            $paymentDtl[$keyptype]['salesInvoiceId'] = $invoiceId; 
                        }
                        $paymentTypeDtl['SM_SALES_PAYMENT_DV'] = $paymentDtl;
                        $paymentTypeDtl['POS_SM_SALES_INVOICE_DETAIL'] = $paymentUpdateDtl;

                        $this->ws->runResponse(self::$gfServiceAddress, 'SM_SALES_PAYMENT_UPDATE', $paymentTypeDtl);                    

                        $paymentTypeDtl['billId'] = $billId;
                        $paymentTypeDtl['posApiPath'] = $posApiPath;

                        self::insertActionLog('typeSalesPayment['.$invoiceId.']', $paymentTypeDtl, $invData);
                    }
                    
                } else {
                    
                    $warningMsg = isset($posApiArray['warningMsg']) ? $posApiArray['warningMsg'] : (isset($posApiArray['message']) ? $posApiArray['message'] : 'PosApi алдаа: NULL');
                    
                    return array('status' => 'warning', 'message' => self::apiStringReplace($warningMsg, true));
                }
                
                $templateContent = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.'/'.$posBillType.'/single.html');
                
                if ($posBillType == 'person') {
                    $qrLotteryTemplate = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.'/'.$posBillType.'/qrcode-lottery.html');
                    $templateContent = str_replace('{qrCodeLottery}', $qrLotteryTemplate, $templateContent);
                }
                
            } else {
                
                $templateContent = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.'/'.$posBillType.'/single.html');
            }
            
            if (issetParam($hdr['recipereceiptnumber'])) {

                $recipeHeaderInfo = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/pharmacy/person/recipeHeaderInfo.html');

                $recipeReplacing = array(
                    '{recipeCipherCode}'    => $hdr['recipeciphercode'], 
                    '{recipePatientName}'   => $hdr['recipepatientname'], 
                    '{recipePatientRegNo}'  => $hdr['recipepatientregno'], 
                    '{recipeReceiptNumber}' => $hdr['recipereceiptnumber']
                );

                $recipeHeaderInfo = strtr($recipeHeaderInfo, $recipeReplacing);
                $templateContent  = str_replace('{recipeHeaderInfo}', $recipeHeaderInfo, $templateContent);

            } else {
                $templateContent = str_replace('{recipeHeaderInfo}', '', $templateContent);
            }

            $replacing = array(
                '{poslogo}'         => $posLogo
            );
            $templateContent = strtr($templateContent, $replacing);
            $site_url = URL;
            $templateContent = preg_replace('/(<img.*?src=")(?!http)(.*">)/', "$1$site_url$2", $templateContent);            
            
            $replacing = array(
                '{companyName}'     => $topTitle,
                '{title}'           => $billTitle, 
                '{vatNumber}'       => $vatNumber,
                '{vatName}'         => $topTitle,
                '{contactInfo}'     => $contactInfo,
                '{date}'            => Date::formatter($createdDate, 'Y/m/d'),
                '{time}'            => Date::formatter($createdDate, 'H:i:s'),
                '{refNumber}'       => $refNumber,
                '{invoiceNumber}'   => $invoiceNumber,
                '{storeName}'       => $storeName,
                '{cashierName}'     => $cashierName,
                '{cashCode}'        => $cashCode, 
                '{salesPersonCode}' => $salesPersonCode, 
                '{salesWaiter}'     => Input::post('waiterText', ''),
                '{orderTime}'       => Input::post('posEshopOrderTime', ''), 
                '{itemList}'        => $itemPrintList,
                '{totalAmount}'     => self::posAmount($totalAmount),
                '{payAmount}'       => self::posAmount($payAmount),
                '{vatAmount}'       => self::posAmount($vatAmount),
                '{discountPart}'    => $discountPart,
                '{totalItemCount}'  => self::posAmount($itemCount), 
                '{giftList}'        => self::giftTableRender($giftList), 
                '{payment-detail}'  => $paymentDetail,
                '{lottery}'         => $lottery,
                '{qrCode}'          => self::getQrCodeImg($qrData),
                '{ddtd}'            => $billId, 
                '{customerNumber}'  => $customerNumber, 
                '{customerName}'    => $customerName, 
                '{loyaltyPart}'     => '', 
                '{upointPart}'      => '', 
                '{promotion}'       => '', 
                '{qrCodeLottery}'   => '', 
                '{lockerCode}'      => '', 
                '{discountCard}'    => '', 
                '{serialText}'      => '', 
                '{info-ipterminal}' => '', 
                '{messageText}'     => '', 
                    
                '{bonusCardNumber}'         => '', 
                '{bonusCardDiscountPercent}'=> '',
                '{bonusCardBeginAmount}'    => '', 
                '{bonusCardDiffAmount}'     => '', 
                '{bonusCardPlusAmount}'     => '', 
                '{bonusCardEndAmount}'      => ''
            );
            /**
             * Fix Qrcode set header
             */            

            $internalContent = strtr($templateContent, $replacing);
            
            if (issetParam($hdr['lotterycount']) == '0') {
                $lotteryContent = self::printLotteryPriceInterval($invoiceId, $printType, issetParam($hdr['phonenumber']));
            } else {
                $lotteryContent = '';
            }
                    
            $internalContent = str_replace('{lotterypart}', $lotteryContent, $internalContent);
            $cssContent       = Mdpos::getPrintCss();
            
            $newInvoiceNumber = self::getBillNumModel();
            $result = array(
                'status' => 'success', 
                'css' => $cssContent,
                'printData' => $internalContent, 
                'lottery' => $lottery, 
                'billNumber' => $newInvoiceNumber,
                'qrcode' => self::getQrCodeImg($qrData)
            );

            header('Content-Type: text/html');
            
        } else {
            $result = array('status' => 'error', 'message' => Lang::line('POS_0125'));
        }
        
        return $result;
    }
    
    public function printReportTemplateInvoiceResponseModel($isMulit = false) {
        
        $invoiceId = $isMulit ? $isMulit['loypaymentbookmap']['salesinvoiceheader']['id'] : Input::post('id');
        
        if ($invoiceId) {

            $invoiceIdPh = $this->db->Param(0);
            $bindVars = array($this->db->addQ($invoiceId));            
            $invData = $isMulit ? $isMulit['loypaymentbookmap']['salesinvoiceheader'] : Input::post('responseData');
        
            $hdr     = $invData;
            $dtl     = $invData['sm_invoice_dtl_dv'];

            $payment = $this->db->GetAll("
            SELECT 
                SP.AMOUNT, 
                PT.PAYMENT_TYPE_NAME 
            FROM SM_SALES_PAYMENT SP 
                INNER JOIN SM_PAYMENT_TYPE PT ON PT.PAYMENT_TYPE_ID = SP.PAYMENT_TYPE_ID
            WHERE SP.SALES_INVOICE_ID = $invoiceIdPh", $bindVars);
            
            if (!$dtl) {
                return array('status' => 'warning', 'message' => 'Баримтын дэлгэрэнгүй мэдээлэл олдсонгүй!');
            }
            
            $departmentId = issetParam($hdr['departmentid']);            
            $billId       = ''; 
            
            $noLotteryNumber = Input::post('noLotteryNumber');
            
            if ($noLotteryNumber == '0') {
                $isLotteryNumber = true;
            } else {
                $isLotteryNumber = false;
            }
            
            if ($billId) {
                $isLotteryNumber = false;
            }
            
            if ($isLotteryNumber) {
                
                $sessionUserKeyDepartmentId = Ue::sessionUserKeyDepartmentId();
                $departmentId = $sessionUserKeyDepartmentId ? $sessionUserKeyDepartmentId : Ue::sessionDepartmentId();
        
                $orgRow = $this->db->GetRow("
                    SELECT
                        DEPARTMENT_ID 
                    FROM ORG_DEPARTMENT 
                    WHERE VATSP_NUMBER IS NOT NULL 
                        AND ROWNUM = 1 
                    START WITH DEPARTMENT_ID = ".$this->db->Param(0)."   
                    CONNECT BY PRIOR PARENT_ID = DEPARTMENT_ID", array($departmentId));

                if (isset($orgRow['DEPARTMENT_ID']) && $orgRow['DEPARTMENT_ID']) {
                    $departmentId = $orgRow['DEPARTMENT_ID'];
                }
            }
                
            $topTitle        = Config::getFromCache('POS_HEADER_NAME');
            $vatNumber       = $hdr['vatnumber'];
            $contactInfo     = Config::get('POS_CONTACT_INFO', 'departmentId='.$departmentId.';');
            $posLogo         = $hdr['poslogo'];
            
            if ($posLogo && file_exists($posLogo)) {
                $posLogo = $posLogo;
            } else {
                $posLogo = 'pos-logo.png';
            }
            
            $storeName       = $hdr['storename'];
            $cashCode        = $hdr['cashcode'];
            $cashierName     = $hdr['cashiername'];
            $billTitle       = Lang::line('POS_0080');
            $printType       = Config::getFromCache('CONFIG_POS_PRINT_TYPE');
            $invoiceNumber   = $hdr['invoicenumber'];
            $refNumber       = $hdr['refnumber'];
            $createdDate     = $hdr['createddatetime'];
            $customerNumber  = $hdr['customerregnumber']; 
            $customerName    = $hdr['customername']; 
                    
            $vatAmount   = $hdr['vat'];
            $payAmount   = $hdr['total'];
            $cityTax     = $hdr['citytax'];
            $totalAmount = $hdr['subtotal'];
            $itemCount   = 0;
            
            $itemPrintList = $salesPersonCode = $giftList = $paymentDetail = $discountPart = $lottery = $qrData = '';
            $paymentDtlTemplate = self::paymentDetailTemplate();
            
            if ($dtl) {
                
                $stocks = '';
                
                if (Config::getFromCache('CONFIG_POS_HEALTHRECIPE')) {
                    $itemPrintRenderFncName = 'generatePharmacyItemRow';
                } else {
                    $itemPrintRenderFncName = 'generateItemRow';
                }
        
                foreach ($dtl as $dtlRow) {
                    
                    if (issetParam($dtlRow['isgit']) == '1') {
                        
                        $giftRow = array(
                            'itemname'       => $dtlRow['itemname'], 
                            'coupontypename' => '', 
                            'isDelivery'     => issetParam($dtlRow['isdelivery']), 
                            'invoiceqty'     => $dtlRow['invoiceqty']
                        );
                        
                        $giftList .= self::giftPrintRow($giftRow);
                        
                    } else {
                        $row = array(
                            'cityTax'        => $dtlRow['linetotalcitytaxamount'], 
                            'itemName'       => $dtlRow['itemname'], 
                            'salePrice'      => $dtlRow['unitprice'], 
                            'itemQty'        => $dtlRow['invoiceqty'], 
                            'totalPrice'     => $dtlRow['totalamount'], 
                            'unitReceivable' => issetParam($dtlRow['unitreceivable']), 
                            'maxPrice'       => '', 
                            'isDelivery'     => issetParam($dtlRow['isdelivery'])
                        );
                        $itemPrintList .= self::{$itemPrintRenderFncName}($row);

                        if (issetParam($dtlRow['salespersoncode'])) {
                            $salesPersonCode .= $dtlRow['salespersoncode'].', ';
                        }
                        
                        if ($isLotteryNumber) {
                            
                            $itemName    = self::apiStringReplace($dtlRow['itemname']);
                            $measureCode = self::convertCyrillicMongolia('ш');

                            $stocks .= "{
                                'code': '" . $dtlRow['itemcode'] . "',
                                'name': '" . $itemName . "',
                                'measureUnit': '" . $measureCode . "',
                                'qty': '" . sprintf("%.2f", $dtlRow['invoiceqty']) . "',
                                'unitPrice': '" . sprintf("%.2f", $dtlRow['unitprice']) . "',
                                'totalAmount': '" . sprintf("%.2f", $dtlRow['totalamount']) . "',
                                'cityTax': '" . sprintf("%.2f", $dtlRow['linetotalcitytaxamount']) . "',
                                'vat': '" . sprintf("%.2f", $dtlRow['linetotalvatamount']) . "',                                
                                'barCode': '" . $dtlRow['barcode'] . "'
                            }, ";
                        }
                    }
                }
                
                $salesPersonCode = rtrim($salesPersonCode, ', ');
                $itemCount       = count($dtl);
            }
            
            if ($payment) {
                
                if (issetParam($hdr['linetotalcitytaxamount']) > 0) {
                    $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0082'), self::posAmount($hdr['linetotalcitytaxamount'])), $paymentDtlTemplate);
                }
        
                foreach ($payment as $paymentRow) {
                    $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array($paymentRow['PAYMENT_TYPE_NAME'], self::posAmount($paymentRow['AMOUNT'])), $paymentDtlTemplate);
                }
            }
            
            if ($hdr['customername']) {
                $posBillType = 'organization';
            } else {
                $posBillType = 'person';
            }
            
            if ($isLotteryNumber) {
                
                $customerNo   = $hdr['customerregnumber'];
                $taxType      = 1;
                $orgName      = '';
                $orgRegNumber = '';
                
                if ($posBillType == 'person') {

                    $billType   = 1;
                    $title      = Lang::line('POS_0100');

                } elseif ($posBillType == 'organization') {

                    $billType       = 3;
                    $title          = Lang::line('POS_0101');

                    $orgRegNumber   = Str::upper($customerNo);
                    $customerNo     = self::convertCyrillicMongolia($orgRegNumber); //'0000039';
                    $orgName        = $orgRegNumber;
                }
                
                $jsonParam = "{
                    'amount': '" . sprintf("%.2f", $payAmount) . "',
                    'vat': '" . sprintf("%.2f", $vatAmount) . "',
                    'cashAmount': '0.00',
                    'nonCashAmount': '" . sprintf("%.2f", $payAmount) . "',
                    'cityTax': '" . sprintf("%.2f", $cityTax) . "',
                    'districtCode': '" . $hdr['posapidistrictcode'] . "',
                    'posNo': '" . $cashCode . "',
                    'reportMonth': '',
                    'customerNo': '" . $customerNo . "',
                    'billType': '" . $billType . "',
                    'taxType': '" . $taxType . "',
                    'billIdSuffix': '',
                    'returnBillId': '',
                    'stocks': [
                        " . rtrim($stocks, ', ') . "
                    ]
                }";
                
                $jsonParam = Str::remove_doublewhitespace(Str::removeNL($jsonParam));

                Mdpos::$eVatNumber = $vatNumber;
                Mdpos::$eStoreCode = $hdr['storecode'];
                Mdpos::$eCashRegisterCode = $cashCode;                                

                $posApiArray = self::posApiFunction($jsonParam);
                $billId      = isset($posApiArray['billId']) ? $posApiArray['billId'] : null;
                
                if ($billId) {
                    
                    $createdDate = $posApiArray['date'] ? $posApiArray['date'] : Date::currentDate();
                    $lottery     = $posApiArray['lottery'];
                    $qrData      = $posApiArray['qrData'];
                    
                    $billResultParams = array(
                        'BILL_ID'          => $billId, 
                        'SALES_INVOICE_ID' => $invoiceId, 
                        'MERCHANT_ID'      => $posApiArray['merchantId'], 
                        'VAT_DATE'         => $createdDate, 
                        'SUCCESS'          => $posApiArray['success'], 
                        'WARNING_MSG'      => $posApiArray['warningMsg'],  
                        'SEND_JSON'        => $jsonParam, 
                        'IS_LOTTERY_SENT_BY_MAIL' => '1', 
                        'STORE_ID'         => $hdr['storeid'], 
                        'CUSTOMER_NUMBER'  => $orgRegNumber, 
                        'CUSTOMER_NAME'    => $orgName
                    );

                    self::createBillResultData($billResultParams); 

                    $posApiPath = $vatNumber.'\\'.$hdr['storecode'].'\\'.$cashCode;
                    $this->ws->redirectPost(Mdpos::getPosApiServiceAddr(), array('function' => 'senddata', 'vatNumber' => $posApiPath));                    
                    
                } else {
                    
                    $warningMsg = isset($posApiArray['warningMsg']) ? $posApiArray['warningMsg'] : (isset($posApiArray['message']) ? $posApiArray['message'] : 'PosApi алдаа: NULL');
                    
                    return array('status' => 'warning', 'message' => self::apiStringReplace($warningMsg, true));
                }
                
                $templateContent = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.'/'.$posBillType.'/single.html');
                
                if ($posBillType == 'person') {
                    $qrLotteryTemplate = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.'/'.$posBillType.'/qrcode-lottery.html');
                    $templateContent = str_replace('{qrCodeLottery}', $qrLotteryTemplate, $templateContent);
                }
                
            } else {
                
                $templateContent = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.'/'.$posBillType.'/single.html');
            }
            
            if (issetParam($hdr['recipereceiptnumber'])) {

                $recipeHeaderInfo = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/pharmacy/person/recipeHeaderInfo.html');

                $recipeReplacing = array(
                    '{recipeCipherCode}'    => $hdr['recipeciphercode'], 
                    '{recipePatientName}'   => $hdr['recipepatientname'], 
                    '{recipePatientRegNo}'  => $hdr['recipepatientregno'], 
                    '{recipeReceiptNumber}' => $hdr['recipereceiptnumber']
                );

                $recipeHeaderInfo = strtr($recipeHeaderInfo, $recipeReplacing);
                $templateContent  = str_replace('{recipeHeaderInfo}', $recipeHeaderInfo, $templateContent);

            } else {
                $templateContent = str_replace('{recipeHeaderInfo}', '', $templateContent);
            }

            $replacing = array(
                '{poslogo}'         => $posLogo
            );
            $templateContent = strtr($templateContent, $replacing);
            $site_url = URL;
            $templateContent = preg_replace('/(<img.*?src=")(?!http)(.*">)/', "$1$site_url$2", $templateContent);            
            
            $replacing = array(
                '{companyName}'     => $topTitle,
                '{title}'           => $billTitle, 
                '{vatNumber}'       => $vatNumber,
                '{vatName}'         => $hdr['vatname'],
                '{contactInfo}'     => $contactInfo,
                '{date}'            => Date::formatter($createdDate, 'Y/m/d'),
                '{time}'            => Date::formatter($createdDate, 'H:i:s'),
                '{refNumber}'       => $refNumber,
                '{invoiceNumber}'   => $invoiceNumber,
                '{storeName}'       => $storeName,
                '{cashierName}'     => $cashierName,
                '{cashCode}'        => $cashCode, 
                '{salesPersonCode}' => $salesPersonCode, 
                '{salesWaiter}'     => Input::post('waiterText', ''),
                '{orderTime}'       => Input::post('posEshopOrderTime', ''), 
                '{itemList}'        => $itemPrintList,
                '{totalAmount}'     => self::posAmount($totalAmount),
                '{payAmount}'       => self::posAmount($payAmount),
                '{vatAmount}'       => self::posAmount($vatAmount),
                '{discountPart}'    => $discountPart,
                '{totalItemCount}'  => self::posAmount($itemCount), 
                '{giftList}'        => self::giftTableRender($giftList), 
                '{payment-detail}'  => $paymentDetail,
                '{lottery}'         => $lottery,
                '{qrCode}'          => self::getQrCodeImg($qrData),
                '{ddtd}'            => $billId, 
                '{customerNumber}'  => $customerNumber, 
                '{customerName}'    => $customerName, 
                '{loyaltyPart}'     => '', 
                '{upointPart}'      => '', 
                '{promotion}'       => '', 
                '{qrCodeLottery}'   => '', 
                '{lockerCode}'      => '', 
                '{discountCard}'    => '', 
                '{serialText}'      => '', 
                '{info-ipterminal}' => '', 
                '{messageText}'     => '', 
                    
                '{bonusCardNumber}'         => '', 
                '{bonusCardDiscountPercent}'=> '',
                '{bonusCardBeginAmount}'    => '', 
                '{bonusCardDiffAmount}'     => '', 
                '{bonusCardPlusAmount}'     => '', 
                '{bonusCardEndAmount}'      => ''
            );
            /**
             * Fix Qrcode set header
             */
            header('Content-Type: text/html');

            $internalContent = strtr($templateContent, $replacing);
            
            if (issetParam($hdr['lotterycount']) == '0') {
                $lotteryContent = self::printLotteryPriceInterval($invoiceId, $printType, issetParam($hdr['phonenumber']));
            } else {
                $lotteryContent = '';
            }
                    
            $internalContent = str_replace('{lotterypart}', $lotteryContent, $internalContent);
            $internalContent = str_replace('<td style="width: 100%;', '<td style="width: 200px;', $internalContent);
            $internalContent = str_replace('<td style="text-align: right; padding: 0; width: 100%;">', '<td style="text-align: right; padding: 0; width: 260px;">', $internalContent);
            
            $result = array('status' => 'success', 'printData' => $internalContent);
            
        } else {
            $result = array('status' => 'error', 'message' => Lang::line('POS_0125'));
        }
        
        return $result;
    }
    
    public function mailInvoiceResponseModel() {
        $invData = $_POST['responseData']['loypaymentbook'];
        $response = array('status' => 'success', 'message' => 'Мэйл амжилттай илгээгдлээ.');
                
        foreach ($invData as $row) {
            $result = $this->printInvoiceResponseModel($row);            
            if ($result['status'] == 'success') {
                $response = $this->sendMailAttachModel($row['toemailaddress'], $row['loypaymentbookmap']['salesinvoiceheader']['ccemailaddress'], $result['printData'], $row['loypaymentbookmap']['salesinvoiceheader']['id']);
            } else {
                $response = $result;
            }
        }
        return $response;
    }
    
    public function sendMailAttachModel($emailTo, $emailCc, $emailBody, $invId) {
        $emailSubject = Config::getFromCacheDefault('POS_SEND_LOTTERY_BY_MAIL_SUBJECT', null, 'Veritech ERP');                            
        
        $emailBody = '<div style="width:450px">' . html_entity_decode($emailBody) . '</div>';
        
        $htmlContent = $emailBody;

        $uniqFileName = 'file_' . getUID();
        $emailFileName = $uniqFileName;
        $orientation = 'portrait';

        $fileToSave = UPLOADPATH . Mdwebservice::$uploadedPath . $uniqFileName;

        $css = '<style type="text/css">';
        $css .= Mdpreview::printCss('statementPdf');
        $css .= '</style>';

        /*$css = '<link href="'.URL.'assets/custom/css/components-rounded.min.css" id="style_components" rel="stylesheet" type="text/css"/>
        <link href="'.URL.'assets/custom/css/print/snappyPrint.min.css" rel="stylesheet" type="text/css"/>';*/

        includeLib('PDF/Pdf');

        $_POST['isIgnoreFooter'] = 1;

        $pdf = Pdf::createSnappyPdf(($orientation == 'portrait' ? 'Portrait' : 'Landscape'), 'A4');

        Pdf::generateFromHtml($pdf, $css . $htmlContent, $fileToSave);

        $filePdfExist = file_exists(BASEPATH . $fileToSave . '.pdf');
        
        if (!$filePdfExist) {
            return array('status' => 'error', 'message' => 'Мэйл илгээхэд алдаа гарлаа. /PDF файл үүссэнгүй/');
        }

        $emailBodyContent = file_get_contents('middleware/views/metadata/dataview/form/email_templates/selectionRows.html');
        $emailBodyContent = str_replace('{htmlTable}', $emailBody, $emailBodyContent);
            
        includeLib('Mail/PHPMailer/v2/PHPMailerAutoload');

        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        
        if (!defined('SMTP_USER')) {
                
            $mail->SMTPAuth = false;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

        } else {
            $mail->SMTPAuth = (defined('SMTP_AUTH') ? SMTP_AUTH : true);
            
            if ($mail->SMTPAuth) {
                $mail->Username = SMTP_USER; 
                $mail->Password = SMTP_PASS; 
            } else {
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
            }
        }
        
        if (defined('SMTP_SSL_VERIFY') && !SMTP_SSL_VERIFY) {
            
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
        }
        
        $mail->SMTPSecure = (defined('SMTP_SECURE') ? SMTP_SECURE : false);
        $mail->Host = SMTP_HOST;
        if (defined('SMTP_HOSTNAME') && SMTP_HOSTNAME) {
            $mail->Hostname = SMTP_HOSTNAME;
        }        
        $mail->Port = SMTP_PORT;
        $mail->setFrom(EMAIL_FROM, EMAIL_FROM_NAME);
        $mail->Subject = $emailSubject;
        $mail->isHTML(true);
        $mail->msgHTML($emailBodyContent);
        $mail->AltBody = 'Veritech ERP - ' . $emailSubject;
        
        $response = array('status' => 'success', 'message' => Lang::line('msg_mail_success'));
                
        $email = trim($emailTo);
        $emailCc = trim($emailCc);

        if ($email) {

            $mail->addAddress($email);
            $mail->addAttachment($fileToSave . '.pdf', $emailFileName . '.pdf');

            if (!$mail->send()) {
                $response = array('status' => 'error', 'message' => $mail->ErrorInfo);
            }
            $mail->clearAllRecipients();
        }

        if ($emailCc) {

            $mail->addAddress($emailCc);
            $mail->addAttachment($fileToSave . '.pdf', $emailFileName . '.pdf');

            if (!$mail->send()) {
                $response = array('status' => 'error', 'message' => $mail->ErrorInfo);
            }
            $mail->clearAllRecipients();
        }


        $ipAddress   = get_client_ip();
        $userId      = Ue::sessionUserId();            
        $currentDate = Date::currentDate();        
        
        if ($response['status'] == 'success') {                
            $data = array(
                'ID'          => getUID(), 
                'EMAIL'       => $email, 
                'ACTION_DATE' => $currentDate, 
                'STATUS'      => 'sent', 
                'FROM_IP'     => $ipAddress, 
                'RECORD_ID'   => $invId, 
                'USER_ID'     => $userId
            );

            $this->db->AutoExecute('EML_EMAIL_LOG', $data);
        } else {
            $data = array(
                'ID'          => getUID(), 
                'EMAIL'       => $email, 
                'ACTION_DATE' => $currentDate, 
                'STATUS'      => 'error', 
                'FROM_IP'     => $ipAddress, 
                'RECORD_ID'   => $invId, 
                'USER_ID'     => $userId
            );

            $this->db->AutoExecute('EML_EMAIL_LOG', $data);            
        }

        @unlink($fileToSave . '.pdf');

        return $response;
    }        
    
    public function printLotteryPriceInterval($salesInvoiceId, $printType, $phoneNumber = '', $lastName = '', $firstName = '') {
        
        $html = '';
        
        if (defined('CONFIG_POS_GENERATE_LOTTERY_NUMBER') && CONFIG_POS_GENERATE_LOTTERY_NUMBER) {
            
            try {
            
                $procedure = $this->db->PrepareSP('BEGIN PRC_LOTTERY_CREATE(:P_SALES_INVOICE_ID); END;');

                $this->db->InParameter($procedure, $salesInvoiceId, 'P_SALES_INVOICE_ID');                        
                $this->db->Execute($procedure);

                $data = $this->db->GetAll("SELECT 
                        L.CODE,
                        P.TOP_PICTURE,
                        P.BOTTOM_PICTURE
                    FROM LOY_LOTTERY L
                    INNER JOIN LOY_LOYALTY_PROGRAM P ON L.PROGRAM_ID = P.LOYALTY_PROGRAM_ID
                    WHERE L.SALES_INVOICE_ID = $salesInvoiceId
                    ORDER BY L.CREATED_DATE ASC"
                );

                if ($data) {
                    
                    $topPic = '';
                    $bottomPic = '';
                    $lotteryLoop = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.'/lottery/price_interval_loop.html');

                    $appendLotteryNumber = $appendLotteryLoop = null;

                    foreach ($data as $row) {

                        $lotteryNumber = $row['CODE'];
                        $topPic = $row['TOP_PICTURE'];
                        $bottomPic = $row['BOTTOM_PICTURE'];

                        if ($lotteryNumber) {
                            
                            $appendLotteryNumber .= $lotteryNumber . '<br />';
                            $appendLotteryLoop .= str_replace('{lotteryNumber}', $lotteryNumber, $lotteryLoop);
                        }
                    }

                    if ($appendLotteryNumber) {

                        $appendLotteryNumber = rtrim($appendLotteryNumber, '<br />');

                        $lotteryTemplate = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.'/lottery/price_interval.html');

                        $html = str_replace('{phoneNumber}', $phoneNumber, $lotteryTemplate);
                        $html = str_replace('{lastName}', $lastName, $html);
                        $html = str_replace('{firstName}', $firstName, $html);
                        $html = str_replace('{lotteryNumbers}', $appendLotteryNumber, $html);
                        $html = str_replace('{lotteryNumbersLoop}', $appendLotteryLoop, $html);
                        $html = str_replace('{topPic}', $topPic, $html);
                        $html = str_replace('{bottomPic}', $bottomPic, $html);
                    }
                }

            } catch (ADODB_Exception $ex) {

                file_put_contents('log/pos_lottery_prc.log', $ex->getMessage().' salesInvoiceId='.$salesInvoiceId."\n", FILE_APPEND);

                $html = '';
            }
        }
        
        return $html;
    }
    
    public function posAutoGenerateVaucher($invoiceId, $generateVaucherAmt, $printType) {
        
        $storeId = Session::get(SESSION_PREFIX.'storeId');
        
        if ($storeId != '1515722911251' && $storeId != '1515722911369' && $storeId != '1515722911402' 
            && $storeId != '1515722911434' && $storeId != '1515722911503') {
            
            $generateVaucherAmt = number_format((10 / 100) * $generateVaucherAmt, 2, '.', '');
            $voucherDurationDay = 90;
            $currentDate        = Date::currentDate();
            $voucherExpireDate  = Date::weekdayAfter('Y-m-d H:i:s', $currentDate, '+'.$voucherDurationDay.' days');

            $params = array(
                'amount'         => $generateVaucherAmt, 
                'activationDate' => $currentDate,
                'durationDay'    => $voucherDurationDay,
                'expiredDate'    => $voucherExpireDate,
                'storeId'        => $storeId,
                'cashierId'      => Session::get(SESSION_PREFIX.'cashierId'),
                'activatedSalesInvoiceId' => $invoiceId
            );

            $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'LOY_COUPON_KEY_DV_001', $params);

            if ($result['status'] == 'success' && isset($result['result']['serialnumber'])) {

                $voucherTemplate = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.'/voucher/template1.html');

                $voucherReplacing = array(
                    '{voucherAmount}'       => self::posAmount($generateVaucherAmt), 
                    '{voucherSerialNumber}' => $result['result']['serialnumber'],
                    '{voucherYear}'         => Date::formatter($voucherExpireDate, 'Y'), 
                    '{voucherMonth}'        => Date::formatter($voucherExpireDate, 'm'), 
                    '{voucherDay}'          => Date::formatter($voucherExpireDate, 'd')
                );

                $voucherContent = strtr($voucherTemplate, $voucherReplacing);

                return $voucherContent;
            } 
        }
        
        return null;
    }
    
    public function getInvoiceInfoByInvoiceIdModel($salesOrderId) {
        
        try {
            $row = $this->db->GetRow("
                SELECT 
                    AA.DELIVERY_CONTACT_NAME, 
                    AA.DELIVERY_CONTACT_PHONE, 
                    AA.DELIVERY_CONTACT_LASTNAME, 
                    AA.DELIVERY_REGISTER_NUM,
                    AA.CUSTOMER_ID,
                    BB.CUSTOMER_CODE, 
                    BB.CUSTOMER_NAME 
                FROM SDM_ORDER_BOOK AA
                LEFT JOIN CRM_CUSTOMER BB ON BB.CUSTOMER_ID = AA.CUSTOMER_ID
                WHERE AA.SALES_ORDER_ID = $salesOrderId");
            
            return $row;
        } catch (ADODB_Exception $ex) {
            return false;
        }
    }
    
    public function getCustomerInfoByRegNumberModel() {
        
        $param = array(
            'systemMetaGroupId' => '1522946988985',
            'showQuery' => 0,
            'ignorePermission' => 1, 
            'criteria' => array(
                'positionName' => array(
                    array(
                        'operator' => '=',
                        'operand' => Input::post('regNumber')
                    )
                )
            )
        );

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result']) && isset($data['result'][0])) {
            return $data['result'][0];
        }
        
        return array();
    }
    
    public function quickItemModel($meta) {
        
        $cashRegisterId = Session::get(SESSION_PREFIX.'cashRegisterId');
        $storeId = Session::get(SESSION_PREFIX.'storeId');
        $param = array(
            'systemMetaGroupId' => $meta,
            'showQuery' => 0,
            'ignorePermission' => 1,
            'criteria' => array(
                'storeId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $storeId
                    )
                ),
                'cashRegisterId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $cashRegisterId
                    )
                )
            )            
        );

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result']) && isset($data['result'][0])) {
            return $data['result'];
        }
        
        return array();
    }
    
    public function searchAccountStatementModel() {
        
        $bankId = Input::post('bankId');
        $amount = Input::post('amount');
        $descr  = Str::lower(Str::remove_doublewhitespace(Input::post('descr')));
        
        $storeId = Session::get(SESSION_PREFIX.'storeId');
        
        try {
            
            $accountList = $this->db->GetAll("
                SELECT 
                    RB.BANK_ID, 
                    RB.BANK_NAME, 
                    CB.BANK_ACCOUNT_NUMBER, 
                    SB.STORE_ID, 
                    CB.DEPARTMENT_ID   
                FROM SM_STORE_BANK_ACCOUNT SB 
                    INNER JOIN CM_BANK_ACCOUNT CB ON CB.ID = SB.BANK_ACCOUNT_ID 
                    INNER JOIN REF_BANK RB ON RB.BANK_ID = CB.BANK_ID 
                WHERE SB.STORE_ID = $storeId 
                    AND CB.CURRENCY_ID = 11337947158805 
                    AND CB.BANK_ID = $bankId 
                    AND CB.DEPARTMENT_ID IS NOT NULL 
                    AND CB.BANK_ACCOUNT_NUMBER IS NOT NULL");

            if ($accountList) {

                $startDate      = date('Y-m') . '-01';
                $endDate        = Date::currentDate('Y-m-d');

                $accountNumbers = "'" . Arr::implode_key("','", $accountList, 'BANK_ACCOUNT_NUMBER', true) . "'";

                $row = self::getCmBankBillingRow($bankId, $accountNumbers, $startDate, $endDate, $amount, $descr);

                if ($row) {
                    $result = array('status' => 'success', 'billingId' => $row['ID']);
                } else {
                    
                    if ($bankId == '500000') {
                        
                        $this->load->model('mdintegration', 'middleware/models/');
                    
                        foreach ($accountList as $accountRow) {

                            $param = array(
                                'account'      => $accountRow['BANK_ACCOUNT_NUMBER'], 
                                'from'         => $startDate, 
                                'to'           => $endDate, 
                                'departmentId' => $accountRow['DEPARTMENT_ID']
                            );

                            $this->model->khaanBankImportStatement(array(), $param);
                        }

                        $rowSecond = self::getCmBankBillingRow($bankId, $accountNumbers, $startDate, $endDate, $amount, $descr);
                        
                    } else {
                        $rowSecond = null;
                    }
                    
                    if ($rowSecond) {
                        $result = array('status' => 'success', 'billingId' => $rowSecond['ID']);
                    } else {
                        $result = array('status' => 'error', 'message' => 'Хуулга олдсонгүй. Та нягтлантай холбогдоно уу!');
                    }
                }

            } else {
                $result = array('status' => 'error', 'message' => 'Дэлгүүр дээрхи дансны тохиргоо олдсонгүй!');
            }
            
        } catch (Exception $ex) {
            
            $result = array('status' => 'error', 'message' => $ex->getMessage());
        } 
        
        return $result;
    }
    
    public function getCmBankBillingRow($bankId, $accountNumbers, $startDate, $endDate, $amount, $descr) {
        
        $row = $this->db->GetRow("
            SELECT 
                ID 
            FROM CM_BANK_BILLING 
            WHERE BANK_ID = $bankId 
                AND AMOUNT > 0 
                AND DESCRIPTION IS NOT NULL 
                AND IS_USE_POS IS NULL 
                AND BILL_DATE IS NOT NULL 
                AND BILL_DATE BETWEEN ".$this->db->ToDate("'$startDate'", 'YYYY-MM-DD')." AND ".$this->db->ToDate("'$endDate 23:59:59'", 'YYYY-MM-DD HH24:MI:SS')." 
                AND ACCOUNT IN ($accountNumbers)     
                AND AMOUNT = $amount 
                AND LOWER(REGEXP_REPLACE(DESCRIPTION, ' +', ' ')) = '$descr' 
            ORDER BY BILL_DATE ASC");
        
        return $row;
    }
    
    public function getCmBankBillingRows($bankId, $accountNumbers, $startDate, $endDate, $amount = '', $descr = '', $statementId = '', $billingId = '') {
        
        if ($statementId) {
            
            $data = $this->db->GetAll("
                SELECT 
                    ID, 
                    BILL_DATE, 
                    ACCOUNT, 
                    JOURNAL_ID, 
                    AMOUNT, 
                    DESCRIPTION 
                FROM CM_BANK_BILLING 
                WHERE BANK_ID = $bankId 
                    AND AMOUNT > 0 
                    AND IS_USE_POS IS NULL 
                    AND ACCOUNT IN ($accountNumbers) 
                    AND LOWER(JOURNAL_ID) = '$statementId' 
                    ".($billingId ? " AND ID NOT IN ($billingId)" : "")."
                ORDER BY BILL_DATE ASC");
            
        } else {
            
            $data = $this->db->GetAll("
                SELECT 
                    ID, 
                    BILL_DATE, 
                    ACCOUNT, 
                    JOURNAL_ID, 
                    AMOUNT, 
                    DESCRIPTION  
                FROM CM_BANK_BILLING 
                WHERE BANK_ID = $bankId 
                    AND AMOUNT > 0 
                    AND DESCRIPTION IS NOT NULL 
                    AND IS_USE_POS IS NULL 
                    AND BILL_DATE IS NOT NULL 
                    AND BILL_DATE BETWEEN ".$this->db->ToDate("'$startDate'", 'YYYY-MM-DD')." AND ".$this->db->ToDate("'$endDate 23:59:59'", 'YYYY-MM-DD HH24:MI:SS')." 
                    AND ACCOUNT IN ($accountNumbers)     
                    AND AMOUNT = $amount 
                    AND LOWER(REGEXP_REPLACE(DESCRIPTION, ' +', ' ')) LIKE '%$descr%' 
                    ".($billingId ? " AND ID NOT IN ($billingId)" : "")."
                ORDER BY BILL_DATE ASC");
        }
        
        return $data;
    }
    
    public function filterAccountStatementModel() {
        
        $bankId = Input::post('bankId');
        parse_str(Input::post('billingId'), $billingId);
        if ($billingId) {
            $billingId = Arr::implode_r(',', $billingId['accountTransferBillingIdDtl'], true);
        }
        $amount = Input::post('amount');
        $descr  = Str::lower(Str::remove_doublewhitespace(Input::post('descr')));
        $statementId = Str::lower(Input::post('statementId'));
        
        $storeId = Session::get(SESSION_PREFIX.'storeId');
        
        try {
            
            $accountList = $this->db->GetAll("
                SELECT 
                    RB.BANK_ID, 
                    RB.BANK_NAME, 
                    CB.BANK_ACCOUNT_NUMBER, 
                    SB.STORE_ID, 
                    CB.DEPARTMENT_ID   
                FROM SM_STORE_BANK_ACCOUNT SB 
                    INNER JOIN CM_BANK_ACCOUNT CB ON CB.ID = SB.BANK_ACCOUNT_ID 
                    INNER JOIN REF_BANK RB ON RB.BANK_ID = CB.BANK_ID 
                WHERE SB.STORE_ID = $storeId 
                    AND CB.CURRENCY_ID = 11337947158805 
                    AND CB.BANK_ID = $bankId 
                    AND CB.DEPARTMENT_ID IS NOT NULL 
                    AND CB.BANK_ACCOUNT_NUMBER IS NOT NULL");

            if ($accountList) {

                $startDate      = date('Y-m') . '-01';
                $endDate        = Date::currentDate('Y-m-d');

                $accountNumbers = "'" . Arr::implode_key("','", $accountList, 'BANK_ACCOUNT_NUMBER', true) . "'";

                $rows = self::getCmBankBillingRows($bankId, $accountNumbers, $startDate, $endDate, $amount, $descr, $statementId, $billingId);

                if ($rows) {
                    
                    $result = array('status' => 'success', 'billingRows' => $rows);
                    
                } else {
                    
                    if ($bankId == '500000') {
                        
                        $this->load->model('mdintegration', 'middleware/models/');
                    
                        foreach ($accountList as $accountRow) {

                            $param = array(
                                'account'      => $accountRow['BANK_ACCOUNT_NUMBER'], 
                                'from'         => $startDate, 
                                'to'           => $endDate, 
                                'departmentid' => $accountRow['DEPARTMENT_ID']
                            );

                            $this->model->khaanBankImportStatement(array(), $param);
                        }

                        $rowsSecond = self::getCmBankBillingRows($bankId, $accountNumbers, $startDate, $endDate, $amount, $descr, $statementId, $billingId);
                        
                    } elseif ($bankId == '150000') {
                        
                        $this->load->model('mdintegration', 'middleware/models/');
                    
                        foreach ($accountList as $accountRow) {

                            $param = array(
                                'account' => $accountRow['BANK_ACCOUNT_NUMBER'], 
                                'from'    => $startDate, 
                                'to'      => $endDate
                            );

                            $this->model->golomtBankImportStatement(array(), $param);
                        }

                        $rowsSecond = self::getCmBankBillingRows($bankId, $accountNumbers, $startDate, $endDate, $amount, $descr, $statementId, $billingId);
                        
                    } else {
                        $rowsSecond = null;
                    }
                    
                    if ($rowsSecond) {
                        $result = array('status' => 'success', 'billingRows' => $rowsSecond);
                    } else {
                        $result = array('status' => 'error', 'message' => 'Хуулга олдсонгүй. Та нягтлантай холбогдоно уу!');
                    }
                }

            } else {
                $result = array('status' => 'error', 'message' => 'Дэлгүүр дээрхи дансны тохиргоо олдсонгүй!');
            }
            
        } catch (Exception $ex) {
            
            $result = array('status' => 'error', 'message' => $ex->getMessage());
        } 
        
        return $result;
    }
    
    // candy  integration start 
    public function getCandyUserPass() {
        $userConfig = Config::get('POS_CANDY_USER_PASS', 'cashRegister='.Session::get(SESSION_PREFIX.'cashRegisterCode').';');
        $userPass = explode("#_#", $userConfig);
        $param = array(
            'username' => $userPass[0], 
            'password' =>$userPass[1]
        );
        if ($userConfig) {
            return $param;
        }
        
        return null;
    }

    public function accountIdMerch() {
        
        //base64_encode('Test_MF:5084794');

        $userConfig = Config::get('POS_CANDY_USER_PASS', 'cashRegister='.Session::get(SESSION_PREFIX.'cashRegisterCode').';');
        $userPass = explode("#_#", $userConfig);

        /*$url = 'https://wallet.candy.mn/rest/branch/login';
       
        $usernamePass= self::getCandyUserPass();

        $params = array(
            'username' => $userPass[0], 
            'password' =>$userPass[1]
        );

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POST => true, 
            CURLOPT_POSTFIELDS => json_encode($params),
            CURLOPT_HEADER => false, 
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept-Language: mn',
            )
        ));    
        
        $response = curl_exec($curl);       
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $token = array('status' => 'error', 'message' => $err);
        }else {
            $jsonArray = json_decode($response, true);
            $accountIdMerch  = issetParam($jsonArray['result']['branch']['accountIdMerch']);
        }  
        */
        $accountIdMerch = Config::get('POS_MONPAY_ACCOUNT_ID', 'cashRegister='.Session::get(SESSION_PREFIX.'cashRegisterCode').';');
        $accountId = $userPass[0].':'.$accountIdMerch;

        //test1133:5370922, test_mendee:4843141
        if ($accountId) {
            return base64_encode($accountId);
        }
        
        return null;
    }

    public function candySendTanCodeModel() {
        
        $token = self::getCandyToken();
        
        if (!$token) {
            return array('status' => 'error', 'message' => 'Monpay: No token');
        }
        
        $phoneNumber = Input::post('phoneNumber');
        $amount      = Input::post('amount');
        
        $params = '<request>'
            . '<customer system="ISDN">'.$phoneNumber.'</customer>'
            . '<amount>'.$amount.'</amount>'
            . '<description>veritech online pos test</description>'
        . '</request>';
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.candy.mn/resource/partner/v1/sell',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POST => true, 
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_HEADER => false, 
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $token,
                'Content-Type: application/xml'
            )
        ));       

        $response = curl_exec($curl);       
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            $result = array('status' => 'error', 'message' => $err);
        } else {
            
            $xmlArray = Xml::createArray($response);
            
            if (isset($xmlArray['response']['code'])) {
                
                $responseCode = $xmlArray['response']['code'];
                
                if ($responseCode == '0') {
                    $result = array('status' => 'success', 'message' => 'Хэрэглэгчийн утсанд ТАН КОД-ыг амжилттай илгээлээ.');
                } else {
                    $result = array('status' => 'error', 'message' => issetParam($xmlArray['response']['info']));
                }
                
            } else {
                $result = array('status' => 'error', 'message' => 'Хариу тодорхой бус байна!');
            }
        }
        
        return $result;
    }
    
    public function candyConfirmTanCodeModel() {
        
        $token = self::getCandyToken();
        
        if (!$token) {
            return array('status' => 'error', 'message' => 'Monpay: No token');
        }
        
        $phoneNumber = Input::post('phoneNumber');
        $amount      = Input::post('amount');
        $tanCode     = Input::post('tanCode');
        
        $params = '<request>'
            . '<customer system="ISDN">'.$phoneNumber.'</customer>'
            . '<amount>'.$amount.'</amount>'
            . '<tancode>'.$tanCode.'</tancode>'
            . '<description>veritech online pos test</description>'
        . '</request>';
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.candy.mn/resource/partner/v1/sellconfirm',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POST => true, 
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_HEADER => false, 
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $token,
                'Content-Type: application/xml'
            )
        ));    

        $response = curl_exec($curl);       
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            $result = array('status' => 'error', 'message' => $err);
        } else {
            
            $xmlArray = Xml::createArray($response);
            
            if (isset($xmlArray['response']['code'])) {
                
                $responseCode = $xmlArray['response']['code'];
                
                if ($responseCode == '0') {
                    $result = array('status' => 'success', 'transactionId' => issetParam($xmlArray['response']['transactionId']), 'message' => 'Монпэй-р амжилттай төлбөр төлөгдлөө.');
                } else {
                    $result = array('status' => 'error', 'message' => issetParam($xmlArray['response']['info']));
                }
                
            } else {
                $result = array('status' => 'error', 'message' => 'Хариу тодорхой бус байна!');
            }
        }
        
        return $result;
    }
    
    public function candyConfirmPinCodeModel() {
        
        $token = self::getCandyToken();
        
        if (!$token) {
            return array('status' => 'error', 'message' => 'Monpay: No token');
        }
        
        $cardNumber = Input::post('cardNumber');
        $typeCode   = Input::post('typeCode');
        $amount     = Input::post('amount');
        $pinCode    = Input::post('pinCode');
        
        $params = '<request>'
            . '<customer system="'.$typeCode.'">'.$cardNumber.'</customer>'
            . '<amount>'.$amount.'</amount>'
            . '<pin>'.$pinCode.'</pin>'
            . '<description>veritech online pos test</description>'
        . '</request>';
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.candy.mn/resource/partner/v1/sellcard',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POST => true, 
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_HEADER => false, 
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $token,
                'Content-Type: application/xml'
            )
        ));    

        $response = curl_exec($curl);       
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            $result = array('status' => 'error', 'message' => $err);
        } else {
            
            $xmlArray = Xml::createArray($response);
            
            if (isset($xmlArray['response']['code'])) {
                
                $responseCode = $xmlArray['response']['code'];
                
                if ($responseCode == '0') {
                    $result = array('status' => 'success', 'transactionId' => issetParam($xmlArray['response']['transactionId']), 'message' => 'Монпэй-р амжилттай төлбөр төлөгдлөө.');
                } else {
                    $result = array('status' => 'error', 'message' => issetParam($xmlArray['response']['info']));
                }
                
            } else {
                $result = array('status' => 'error', 'message' => 'Хариу тодорхой бус байна!');
            }
        }
        
        return $result;
    }
    
    public function candyNewTokenModel() {

        $url = 'https://wallet.candy.mn/rest/branch/login';
        $userPass = self::getCandyUserPass();
        
        if (!$userPass) {
            return array(
                'status' => 'error', 
                'message' => 'Monpay: Тохиргоо хийгээгүй байна! /Config::get(POS_CANDY_USER_PASS, cashRegister='.Session::get(SESSION_PREFIX.'cashRegisterCode').')/'
            );
        }
         
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POST => true, 
            CURLOPT_POSTFIELDS => json_encode($userPass),
            CURLOPT_HEADER => false, 
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept-Language: mn',
            )
        ));    
        
        $response = curl_exec($curl);       
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $token = array('status' => 'error', 'message' => $err);
        }else {
            $jsonArray = json_decode($response, true);
            if (isset($jsonArray['result']['token'])) {
                $token = $jsonArray['result']['token'];
            } else {
                $token = array('status' => 'error', 'message' => $jsonArray['info'] . ' /' . $jsonArray['result'] . '/');
            }
        }
     
        return $token;
    }

    public function candySendCoupenModel($coupen = '') {

        $token = self::candyNewTokenModel();

        if (is_array($token)) {
            return $token;
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://wallet.candy.mn/rest/branch/coupon/check?couponCode='.$coupen.'',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HEADER => false, 
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token,
                'Accept-Language: mn'
            )
        )); 
        $response = curl_exec($curl);       
        $err = curl_error($curl);
        
        curl_close($curl);

        if ($err) {
            $result = array('status' => 'error', 'message' => $err);
        } else {
            
            $jsonArray = json_decode($response, true);
         
            if (isset($jsonArray['code'])) {
                
                $responseCode = $jsonArray['code'];
                
                if ($responseCode == '0') {
                    $curl1 = curl_init();
                    curl_setopt_array($curl1, array(
                        CURLOPT_URL => 'https://wallet.candy.mn/rest/branch/coupon/scan?couponCode='.$coupen.'',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                        CURLOPT_HEADER => false, 
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json',
                            'Authorization: Bearer ' . $token,
                            'Accept-Language: mn'
                        )
                    )); 
 
                    $responseScan = curl_exec($curl1);       
                    $jsonArrayScan = json_decode($responseScan, true);

                    if ($jsonArrayScan['code'] == '0') {
                        $result = array('status' => 'success', 'message' => issetParam($jsonArrayScan['result']), 'info' => issetParam($jsonArrayScan['info']));
                    } else {
                        $result = array('status' => 'error', 'message' => issetParam($jsonArrayScan['info']));
                    }

                } else {
                    $result = array('status' => 'error', 'message' => issetParam($jsonArray['info']));
                }
                
            } else {
                $result = array('status' => 'error', 'message' => 'Хариу тодорхой бус байна!');
            }
        }
       
        return $result;
    }

    public function candycashbackModel() {

        $token = self::candyNewTokenModel();

        if (is_array($token)) {
            return $token;
        }
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://wallet.candy.mn/rest/branch/rewardphone',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HEADER => false, 
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Bearer ' . $token,
                'Accept-Language: mn'
            )
        )); 
        $response = curl_exec($curl);       
        $err = curl_error($curl);
        
        curl_close($curl);

        if ($err) {
            $result = array('status' => 'error', 'message' => $err);
        } else {
            
            $jsonArray = json_decode($response, true);
         
            if (isset($jsonArray['code'])) {
                
                $responseCode = $jsonArray['code'];
                
                if ($responseCode == '0') {
                    $result = array(
                        'status' => 'success', 
                        'message' => issetParam($jsonArray['result']), 
                        'info' => issetParam($jsonArray['info']));
                } else {
                    $result = array('status' => 'error', 'message' => issetParam($jsonArray['info']));
                }
                
            } else {
                $result = array('status' => 'error', 'message' => 'Хариу тодорхой бус байна!');
            }
        }
       
        return $result;
    }

    public function candyCashBackActionModel() {

        $userPass = self::accountIdMerch();
        
        if (!$userPass) {
            return array('status' => 'error', 'message' => 'Monpay: Тохиргоо хийгээгүй байна! /Config::get(POS_CANDY_USER_PASS, cashRegister='.Session::get(SESSION_PREFIX.'cashRegisterCode').')/');
        }

        $token = self::candyNewTokenModel();

        $phone = Input::post('cphone');
        $action = Input::post('caction');

        $params = array(
            'action' =>$action,
            'rewardPhone' => $phone, 
        );

        if (is_array($token)) {
            return $token;
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://wallet.candy.mn/rest/branch/rewardphone/action',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POST => true, 
            CURLOPT_POSTFIELDS => json_encode($params),
            CURLOPT_HEADER => false, 
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Bearer ' . $token,
                'Accept-Language: mn'
            )
        )); 
        $response = curl_exec($curl);       
        $err = curl_error($curl);
        
        curl_close($curl);

        if ($err) {
            $result = array('status' => 'error', 'message' => $err);
        } else {
            
            $jsonArray = json_decode($response, true);
         
            if (isset($jsonArray['code'])) {
                
                $responseCode = $jsonArray['code'];
                
                if ($responseCode == '0') {
                    $result = array(
                        'status' => 'success', 
                        'message' => issetParam($jsonArray['result']), 
                        'info' => issetParam($jsonArray['info']));
                } else {
                    $result = array('status' => 'error', 'message' => issetParam($jsonArray['info']));
                }
                
            } else {
                $result = array('status' => 'error', 'message' => 'Хариу тодорхой бус байна!');
            }
        }
       
        return $result;

    }

    public function candyGenerateQrCodeModel() {
        
        $userPass = self::accountIdMerch();
        
        if (!$userPass) {
            return array('status' => 'error', 'message' => 'Monpay: Тохиргоо хийгээгүй байна! /Config::get(POS_CANDY_USER_PASS, cashRegister='.Session::get(SESSION_PREFIX.'cashRegisterCode').')/');
        }
        
        $amount = Input::post('amount');
        
        $params = array(
            'amount' => $amount, 
            'displayName' => 'Veritech', 
            'generateUuid' => true
        );
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://wallet.candy.mn/rest/branch/qrpurchase/generate',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POST => true, 
            CURLOPT_POSTFIELDS => json_encode($params),
            CURLOPT_HEADER => false, 
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . $userPass,
                'Content-Type: application/json'
            )
        ));    

        $response = curl_exec($curl);       
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            $result = array('status' => 'error', 'message' => $err);
        } else {
            
            $jsonArray = json_decode($response, true);
            
            if (isset($jsonArray['code'])) {
                
                $responseCode = $jsonArray['code'];
                
                if ($responseCode == '0') {
                    $result = array('status' => 'success', 'uuid' => issetParam($jsonArray['result']['uuid']), 'qrcode' => issetParam($jsonArray['result']['qrcode']));
                } else {
                    $result = array('status' => 'error', 'message' => issetParam($jsonArray['info']));
                }
                
            } else {
                $result = array('status' => 'error', 'message' => 'Хариу тодорхой бус байна!');
            }
        }
        
        return $result;
    }
    
    public function candyCheckQrCodeModel() {
        
        $userPass = self::accountIdMerch();
        
        if (!$userPass) {
            return array('status' => 'error', 'message' => 'Monpay: Тохиргоо хийгээгүй байна! /Config::get(POS_CANDY_USER_PASS, cashRegister='.Session::get(SESSION_PREFIX.'cashRegisterCode').')/');
        }
        
        $uuid = Input::post('uuid');
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://wallet.candy.mn/rest/branch/qrpurchase/check?uuid=' . $uuid,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HEADER => false, 
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . $userPass,
                'Content-Type: application/json'
            )
        ));    

        $response = curl_exec($curl);       
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            $result = array('status' => 'error', 'message' => $err);
        } else {
            
            $jsonArray = json_decode($response, true);
            
            if (isset($jsonArray['code'])) {
                
                $responseCode = $jsonArray['code'];
                
                if ($responseCode == '0') {
                    $result = array('status' => 'success', 'message' => 'Monpay-р амжилттай төлбөр төлөгдлөө.');
                } else {
                    $result = array('status' => 'error', 'message' => issetParam($jsonArray['info']));
                }
                
            } else {
                $result = array('status' => 'error', 'message' => 'Хариу тодорхой бус байна!');
            }
        }
        
        return $result;
    }
    
    public function candyChargeQrCodeModel() {
        
        $userPass = self::accountIdMerch();
        
        if (!$userPass) {
            return array('status' => 'error', 'message' => 'Monpay: Тохиргоо хийгээгүй байна! /Config::get(POS_CANDY_USER_PASS, cashRegister='.Session::get(SESSION_PREFIX.'cashRegisterCode').')/');
        }
        
        $amount = Input::post('amount');
        $params = array(
            'userPresentedQr' => Input::post('qrCode'),
            'generateUuid' => true,
            'amount' => $amount, 
            'displayName' => 'Veritech',             
        );
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://wallet.candy.mn/rest/branch/qrpurchase/generate',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POST => true, 
            CURLOPT_POSTFIELDS => json_encode($params),
            CURLOPT_HEADER => false, 
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . $userPass,
                'Content-Type: application/json'
            )
        ));    

        $response = curl_exec($curl);       
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            $result = array('status' => 'error', 'message' => $err);
        } else {
            
            $jsonArray = json_decode($response, true);
            
            if (isset($jsonArray['code'])) {
                
                $responseCode = $jsonArray['code'];
                
                if ($responseCode == '0') {
                    
                    $result = $this->candyCheckQrCodeModel();
                    $result = array(
                        'status'        => 'success', 
                        'uuid'          => $jsonArray['result']['uuid'],
                        'message'       => 'Monpay-р амжилттай төлбөр төлөгдлөө.'
                    );
                    
                } else {
                    $result = array('status' => 'error', 'message' => issetParam($jsonArray['info']));
                }
                
            } else {
                $result = array('status' => 'error', 'message' => 'Хариу тодорхой бус байна!');
            }
        }
        
        return $result;
    }
    
    public function candyUserToSend($invoiceId, $printType, $candyUserCheck) {
        
        $token = self::getCandyToken();
        
        if (!$token) {
            return null;
        }
        
        sleep(1);
        
        $candyType   = $candyUserCheck['candyType'];
        $candyNumber = $candyUserCheck['candyNumber'];
        $candyAmount = $candyUserCheck['candyAmount'];
        
        $params = '<request>'
            . '<customer system="'.$candyType.'">'.$candyNumber.'</customer>'
            . '<amount>'.$candyAmount.'</amount>'
            . '<description>veritech online pos test</description>'
        . '</request>';
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.candy.mn/resource/partner/v1/reward',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POST => true, 
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_HEADER => false, 
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $token,
                'Content-Type: application/xml'
            )
        ));    

        $response = curl_exec($curl); 
        $err = curl_error($curl);
        
        curl_close($curl);
        
        $result = null;
        
        if ($err) {
            $result = null; //array('status' => 'error', 'message' => $err);
        } else {
            
            if (Json::isJson($response)) {
                $xmlArray = Json::decode($response);
            } else {
                $xmlArray = Xml::createArray($response);
            }
            
            if (isset($xmlArray['response']['code'])) {
                
                $responseCode = $xmlArray['response']['code'];
                
                if ($responseCode == '0') {
                    
                    $transactionId = issetParam($xmlArray['response']['transactionId']);
                    
                    $insertData = array(
                        'SALES_PAYMENT_ID'     => getUID(), 
                        'SALES_INVOICE_ID'     => $invoiceId, 
                        'PAYMENT_TYPE_ID'      => 15, 
                        'IN_AMT'               => $candyAmount, 
                        'CANDY_TYPE_CODE'      => $candyType, 
                        'CANDY_NUMBER'         => $candyNumber, 
                        'CANDY_TRANSACTION_ID' => $transactionId
                    );
                    
                    $dbResult = $this->db->AutoExecute('SM_SALES_PAYMENT', $insertData);
                    
                    if ($dbResult) {
                        
                        $templateContent = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.'/person/loyaltyPart.html');
                        
                        $replacing = array(
                            '{loyaltyLabelName}' => 'Монпэй урамшуулал /'.self::getCandyCodeToName($candyType).' - '.substr($candyNumber, 0, strlen($candyNumber) - 4).'****/', 
                            '{loyaltyAmount}'    => '+'.self::posAmount($candyAmount)
                        );

                        $result = strtr($templateContent, $replacing);
                    }
                    
                } else {
                    file_put_contents('log/candy-reward.txt', $invoiceId.' '.$response . "\n", FILE_APPEND);
                    $result = null; //array('status' => 'error', 'message' => issetParam($xmlArray['response']['info']));
                }
                
            } else {
                file_put_contents('log/candy-reward.txt', $invoiceId.' '.$response . "\n", FILE_APPEND);
                $result = null; //array('status' => 'error', 'message' => 'Хариу тодорхой бус байна!');
            }
        }
        
        return $result;
    }

    public function getCandyCodeToName($code) {
        $arr = array(
            'ISDN' => 'Утасны дугаар', 
            'CARDID' => 'Картын дугаар', 
            'NFCID' => 'NFC кардын №', 
            'LOYALTYID' => 'Дансны дугаар'
        );
        return $arr[$code];
    }

    // candy  integration end 
    

    public function getRedPointMerchantTerminalNo() {
        
        return array(
            'merchant_no' => Session::get(SESSION_PREFIX.'posRedpointMerchantNo'), 
            'terminal_no' => Session::get(SESSION_PREFIX.'posRedpointTerminalNo')
        );
    }
    
    public function getRedPointToken() {
        
        //$token = '5f8c7e3668b338c2957de9ab4ddb22da9eb93048429cebb646160264f7f41644';
        $token = Session::get(SESSION_PREFIX.'posRedPointToken');
        
        return $token;
    }
    
    public function redPointUserToSend($invoiceId, $printType, $redPointUserCheck, $totalAmount) {
        
        $token = self::getRedPointToken();
        
        if (!$token) {
            return null;
        }
        
        $redPointNumber = $redPointUserCheck['redPointNumber'];
        
        $params = self::getRedPointMerchantTerminalNo();
        
        $params['customer_id'] = $redPointNumber;
        $params['amount'] = $totalAmount;
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.minu.mn/petroapi/merchant/createTransaction',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POST => true, 
            CURLOPT_POSTFIELDS => json_encode($params),
            CURLOPT_HEADER => false, 
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json', 
                'Auth-Token: Basic '.$token
            )
        ));    

        $response = curl_exec($curl); 
        $err = curl_error($curl);
        
        curl_close($curl);
        
        $result = null;
        
        if ($err) {
            $result = null; //array('status' => 'error', 'message' => $err);
        } else {
            
            $arr = json_decode($response, true);
            
            if (issetParam($arr['status']) == 'SUCCESS') {
                
                $data = $arr['data'];
                
                if (isset($data['txn_id'])) {
                    
                    $transactionId = $data['txn_id'];
                    
                    $insertData = array(
                        'SALES_PAYMENT_ID'     => getUID(), 
                        'SALES_INVOICE_ID'     => $invoiceId, 
                        'PAYMENT_TYPE_ID'      => 16, 
                        'IN_AMT'               => $redPointUserCheck['redPointAmount'], 
                        'CANDY_TYPE_CODE'      => $redPointUserCheck['redPointType'], 
                        'CANDY_NUMBER'         => $redPointNumber, 
                        'CANDY_TRANSACTION_ID' => $transactionId
                    );

                    $dbResult = $this->db->AutoExecute('SM_SALES_PAYMENT', $insertData);

                    if ($dbResult) {

                        $templateContent = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.'/person/loyaltyPart.html');

                        $replacing = array(
                            '{loyaltyLabelName}' => 'RedPoint урамшуулал /'.self::getCandyCodeToName($redPointUserCheck['redPointType']).' - ****'.substr($redPointNumber, 4, 50).'/', 
                            '{loyaltyAmount}'    => '+'.self::posAmount($redPointUserCheck['redPointAmount'])
                        );

                        $result = strtr($templateContent, $replacing);
                    }
                }
            }
        }
        
        return $result;
    }
    
    public function getRedPointTerminalSettings() {
        
        $token = self::getRedPointToken();
        
        if (!$token) {
            return array('status' => 'error', 'message' => 'RedPoint: No Token');
        }
        
        $params = self::getRedPointMerchantTerminalNo();
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.minu.mn/petroapi/merchant/terminalsettings',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POST => true, 
            CURLOPT_POSTFIELDS => json_encode($params),
            CURLOPT_HEADER => false, 
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json', 
                'Auth-Token: Basic '.$token
            )
        ));    

        $response = curl_exec($curl); 
        $err = curl_error($curl);
        
        curl_close($curl);
        
        $result = array('status' => 'error', 'message' => 'RedPoint: No settings');
        
        if ($err) {
            $result = array('status' => 'error', 'message' => $err);
        } else {
            
            $arr = json_decode($response, true);
            
            if (issetParam($arr['status']) == 'SUCCESS' && isset($arr['data'])) {
                
                $data = $arr['data'];
                
                if (isset($data['terminals'])) {
                    
                    $terminals = $data['terminals'];
                    
                    if ($terminals) {
                        
                        foreach ($terminals as $terminal) {

                            if ($terminal['terminal_no'] == $params['terminal_no']) {
                                $result = array(
                                    'status'           => 'success', 
                                    'rate'             => $data['redpoint_rate'], 
                                    'redemption'       => $terminal['redpoint_redemption'], 
                                    'award'            => $terminal['redpoint_award'], 
                                    'offer_redemption' => $terminal['offer_redemption']
                                );
                                break;
                            }
                        }
                    }
                }
            }
        }
        
        return $result;
    }
    
    public function getRedPointBalanceModel() {
        
        $token = self::getRedPointToken();
        
        if (!$token) {
            return array('status' => 'error', 'message' => 'RedPoint: No Token');
        }
        
        $number = Input::post('number');
        $params = self::getRedPointMerchantTerminalNo();
        
        $params['customer_id'] = $number;
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.minu.mn/petroapi/merchant/presale',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POST => true, 
            CURLOPT_POSTFIELDS => json_encode($params),
            CURLOPT_HEADER => false, 
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json', 
                'Auth-Token: Basic '.$token
            )
        ));    

        $response = curl_exec($curl); 
        $err = curl_error($curl);
        
        curl_close($curl);
        
        $result = array('status' => 'error', 'message' => 'Not found');
        
        if ($err) {
            $result = array('status' => 'error', 'message' => $err);
        } else {
            
            $arr = json_decode($response, true);
            
            if (issetParam($arr['status']) == 'SUCCESS' && isset($arr['data']['balance'])) {
                $result = array('status' => 'success', 'balance' => $arr['data']['balance']);
            }
        }
        
        return $result;
    }
    
    public function redPointItemsModel() {
        
        $token = self::getRedPointToken();
        
        if (!$token) {
            return array('status' => 'error', 'message' => 'RedPoint: No Token');
        }
        
        $params = self::getRedPointMerchantTerminalNo();
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.minu.mn/petroapi/merchant/terminalsettings',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POST => true, 
            CURLOPT_POSTFIELDS => json_encode($params),
            CURLOPT_HEADER => false, 
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json', 
                'Auth-Token: Basic '.$token
            )
        ));    

        $response = curl_exec($curl); 
        $err = curl_error($curl);
        
        curl_close($curl);
        
        $result = array('status' => 'error', 'message' => 'Not found');
        
        if ($err) {
            $result = array('status' => 'error', 'message' => $err);
        } else {
            
            $arr = json_decode($response, true);
            
            if (issetParam($arr['status']) == 'SUCCESS' && isset($arr['data'])) {
                
                $data   = $arr['data'];
                $result = array('status' => 'error', 'message' => 'RedPoint бараа олдсонгүй');
                
                if (isset($data['offers'])) {
                    
                    $offers = $data['offers'];
                    
                    if ($offers) {
                        
                        $result = array(
                            'status' => 'success', 
                            'rate'   => $data['redpoint_rate'], 
                            'items'  => $offers
                        );
                    }
                }
            }
        }
        
        return $result;
    }
    
    public function redPointCancel($transactionId) {
        
        $token = self::getRedPointToken();
        
        if (!$token) {
            return array('status' => 'error', 'message' => 'RedPoint: No Token');
        }
        
        $params = array('txn_id' => $transactionId);
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.minu.mn/petroapi/merchant/cancelTransaction',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POST => true, 
            CURLOPT_POSTFIELDS => json_encode($params),
            CURLOPT_HEADER => false, 
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json', 
                'Auth-Token: Basic '.$token
            )
        ));    

        $response = curl_exec($curl); 
        $err = curl_error($curl);
        
        curl_close($curl);
        
        $result = array('status' => 'error', 'message' => 'Not found');
        
        if ($err) {
            $result = array('status' => 'error', 'message' => $err);
        } else {
            
            $arr = json_decode($response, true);
            
            if (issetParam($arr['status']) == 'SUCCESS' && isset($arr['data']['balance'])) {
                $result = array('status' => 'success', 'balance' => $arr['data']['balance']);
            }
        }
        
        return $result;
    }
    
    public function getItemsByCodeModel() {
        
        $param = array(
            'systemMetaGroupId' => '1551084109568',
            'showQuery' => 0,
            'ignorePermission' => 1, 
            'criteria' => array(
                'storeId' => array(
                    array(
                        'operator' => '=',
                        'operand' => Session::get(SESSION_PREFIX.'storeId')
                    )
                ), 
                'filterItemCode' => array(
                    array(
                        'operator' => 'IN',
                        'operand' => Arr::implode_r(',', Input::post('redPointItemCode'), true)
                    )
                )
            )
        );

        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($result['result']) && isset($result['result'][0])) {
            
            unset($result['result']['aggregatecolumns']);
            unset($result['result']['paging']);
            
            $data = array('status' => 'success', 'data' => $result['result']);
        } else {
            $data = array('status' => 'error', 'message' => 'Empty');
        }
        
        return $data;
    }
    
    public function getPosBankNameById($bankId) {
        
        if ($bankId) {
            
            global $db;
            
            return $db->GetOne("SELECT BANK_NAME FROM REF_BANK WHERE BANK_ID = $bankId");
        } else {
            return null;
        }
    }
    
    public function printLockerCode($lockerCode) {
        
        if ($lockerCode != '') { 
            $fontSizeLocker = '18';

            if (strpos($lockerCode, ',') !== false) {
                $fontSizeLocker = '15';
                $lockerCode = rtrim($lockerCode, ',');
            }

            $html = '<tr>
                <td style="padding: 5px 0 0 0;">                
                    <table border="0" width="100%" style="width: 100%; table-layout: fixed; font-family: Tahoma; font-size: 12px;">
                        <tbody>
                            <tr>
                                <td style="text-align: left; width: 120px; padding: 0;">Түлхүүрийн дугаар:</td>
                                <td style="text-align: left; font-size: '.$fontSizeLocker.'px;"><strong>'.$lockerCode.'</strong></td>
                            </tr>
                        </tbody>	
                    </table>
                </td>    
            </tr>';
        } else {
            $html = '';
        }
        
        return $html;
    }
    
    public function printSerialText($serialText) {
        
        if ($serialText != '') { 
            $html = '<tr>
                <td style="padding: 5px 0 0 0;">                
                    <table border="0" width="100%" style="width: 100%; table-layout: fixed; font-family: Tahoma; font-size: 12px;">
                        <tbody>
                            <tr>
                                <td style="text-align: left; width: 120px; padding: 0;">Сериал текст:</td>
                                <td style="text-align: left; font-size: 15px;"><strong>'.$serialText.'</strong></td>
                            </tr>
                        </tbody>	
                    </table>
                </td>    
            </tr>';
        } else {
            $html = '';
        }
        
        return $html;
    }
    
    public function printLocalCustomerPhone($serialText) {
        
        if ($serialText != '') { 
            $html = '<tr>
                <td style="padding: 5px 0 0 0;">                
                    <table border="0" width="100%" style="width: 100%; table-layout: fixed; font-family: Tahoma; font-size: 12px;">
                        <tbody>
                            <tr>
                                <td style="text-align: left; width: 100px; padding: 0;">Утасны дугаар:</td>
                                <td style="text-align: left; font-size: 15px;">'.$serialText.'</td>
                            </tr>
                        </tbody>	
                    </table>
                </td>    
            </tr>';
        } else {
            $html = '';
        }
        
        return $html;
    }
    
    public function checkTalonListPassModel() {
        
        $pass = Input::post('talonListPass');
        
        if ($pass == Session::get(SESSION_PREFIX.'posAdminpassword')) {
            $result = array('status' => 'success');
        } else {
            $result = array('status' => 'error', 'message' => 'Нууц үг буруу байна.');
        }
        
        return $result;
    }
    
    public function saveBankNotesModel($bankNotes, $invoiceId = null) {
        
        if ($bankNotes != '') {
            
            try {
                
                parse_str($bankNotes, $bankNotesArray);
            
                $storeId        = Session::get(SESSION_PREFIX.'storeId');
                $cashRegisterId = Session::get(SESSION_PREFIX.'cashRegisterId');
                $cashierId      = Session::get(SESSION_PREFIX.'cashierId');
                $createdUserId  = Ue::sessionUserKeyId();
                $createdDate    = Date::currentDate('Y-m-d');
                
                $getInvoiceDate = $this->getDateCashierModel();
                
                if (is_array($getInvoiceDate['result']) && $getInvoiceDate['result']['bookdate']) {
                    $createdDate = $getInvoiceDate['result']['bookdate'];
                }                

                if (count($bankNotesArray['banknote'])) {
                    
                    if ($bankNotesArray['bankNoteTypeId']) {
                        $this->db->Execute("DELETE FROM SM_BANKNOTES WHERE LOCAL_COST IS NULL AND TYPE_ID = ".$bankNotesArray['bankNoteTypeId']." AND STORE_ID = $storeId AND CASH_REGISTER_ID = $cashRegisterId AND CREATED_USER_ID = $createdUserId AND CREATED_CASHIER_ID = $cashierId AND TRUNC(CREATED_DATE) = '$createdDate'");
                    } else {
                        $this->db->Execute("DELETE FROM SM_BANKNOTES WHERE LOCAL_COST IS NULL AND (TYPE_ID = 2 OR TYPE_ID IS NULL) AND STORE_ID = $storeId AND CASH_REGISTER_ID = $cashRegisterId AND CREATED_USER_ID = $createdUserId AND CREATED_CASHIER_ID = $cashierId AND TRUNC(CREATED_DATE) = '$createdDate'");
                    }

                    foreach ($bankNotesArray['banknote'] as $banknote => $qty) {

                        if ($qty) {

                            $data = array(
                                'ID'                 => getUID(), 
                                'STORE_ID'           => $storeId, 
                                'CASH_REGISTER_ID'   => $cashRegisterId, 
                                'SALES_INVOICE_ID'   => $invoiceId, 
                                'CREATED_CASHIER_ID' => $cashierId, 
                                'CREATED_USER_ID'    => $createdUserId, 
                                'CREATED_DATE'       => $createdDate, 
                                'BANKNOTE'           => $banknote, 
                                'QUANTITY'           => $qty,
                                'TYPE_ID'            => ($bankNotesArray['bankNoteTypeId'] ? $bankNotesArray['bankNoteTypeId'] : '2')
                            );
                            $this->db->AutoExecute('SM_BANKNOTES', $data);
                        }
                    }
                }

                if (isset($bankNotesArray['localCost']) && $bankNotesArray['localCost']) {
                    $this->db->Execute("DELETE FROM SM_BANKNOTES WHERE LOCAL_COST IS NOT NULL AND STORE_ID = $storeId AND CASH_REGISTER_ID = $cashRegisterId AND CREATED_USER_ID = $createdUserId AND CREATED_CASHIER_ID = $cashierId AND TRUNC(CREATED_DATE) = '$createdDate'");

                    $data = array(
                        'ID'                 => getUID(), 
                        'STORE_ID'           => $storeId, 
                        'CASH_REGISTER_ID'   => $cashRegisterId, 
                        'SALES_INVOICE_ID'   => '', 
                        'CREATED_CASHIER_ID' => $cashierId, 
                        'CREATED_USER_ID'    => $createdUserId, 
                        'CREATED_DATE'       => $createdDate, 
                        'LOCAL_COST'         => Number::decimal($bankNotesArray['localCost'])
                    );

                    $this->db->AutoExecute('SM_BANKNOTES', $data);                
                }
                
                $response = array('status' => 'success', 'message' => $this->lang->line('msg_save_success'));
                
            } catch (ADODB_Exception $ex) {
                $response = array('status' => 'error', 'message' => $ex->getMessage());
            }
        }
        
        return $response;
    }
    
    public function getPosBankNotesModel() {
        
        $cashRegisterId = Session::get(SESSION_PREFIX.'cashRegisterId');
        $cashierId = Session::get(SESSION_PREFIX.'cashierId');
        $currentDate = Date::currentDate('Y-m-d');
        $storeId = Session::get(SESSION_PREFIX.'storeId');
        $array = array();
        
        $getInvoiceDate = $this->getDateCashierModel();
        if (is_array($getInvoiceDate['result']) && $getInvoiceDate['result']['bookdate']) {
            $currentDate = $getInvoiceDate['result']['bookdate'];
        }            
        
        $data = $this->db->GetAll("
            SELECT 
                BANKNOTE, 
                SUM(QUANTITY) AS QUANTITY
            FROM SM_BANKNOTES 
            WHERE STORE_ID = ".$this->db->Param(3)." AND CASH_REGISTER_ID = ".$this->db->Param(0)." AND CREATED_CASHIER_ID = ".$this->db->Param(2)." 
                AND ".$this->db->SQLDate('Y-m-d', 'CREATED_DATE')." = ".$this->db->Param(1)."     
            GROUP BY BANKNOTE 
            ORDER BY BANKNOTE DESC", array($cashRegisterId, $currentDate, $cashierId, $storeId)); 
        
        $dataCost = $this->db->GetOne("
            SELECT 
                LOCAL_COST
            FROM SM_BANKNOTES 
            WHERE STORE_ID = ".$this->db->Param(3)." AND CASH_REGISTER_ID = ".$this->db->Param(0)." AND CREATED_CASHIER_ID = ".$this->db->Param(2)." AND LOCAL_COST IS NOT NULL 
                AND ".$this->db->SQLDate('Y-m-d', 'CREATED_DATE')." = ".$this->db->Param(1), array($cashRegisterId, $currentDate, $cashierId, $storeId)); 

        if ($data) {
            foreach ($data as $row) {
                $array[$row['BANKNOTE']] = $row['QUANTITY'];
            }
        }
        
        if ($dataCost) {
            $array['localCost'] = $dataCost;
        }
        
        return $array;
    }
    
    public function printReturnInvoiceModel($invoiceId) {    
            
        $invData = self::getInvoiceMainDataModel($invoiceId);
        
        $hdr     = $invData['header'];
        $dtl     = $invData['detail'];
        $payment = $invData['payment'];
        
        $departmentId = $hdr['DEPARTMENT_ID'];            
        $billId       = $hdr['BILL_ID']; 

        $topTitle       = Session::get(SESSION_PREFIX.'posHeaderName');
        $vatNumber       = Session::get(SESSION_PREFIX.'vatNumber');
        $contactInfo     = Config::get('POS_CONTACT_INFO', 'departmentId='.$departmentId.';');
        $sessionPosLogo = Session::get(SESSION_PREFIX.'posLogo');
        $posLogo        = ($sessionPosLogo ? $sessionPosLogo : 'pos-logo.png');

        $storeName       = $hdr['STORE_NAME'];
        $cashCode        = $hdr['CASH_CODE'];
        $cashierName     = $hdr['CASHIER_NAME'];
        $billTitle       = 'БАРИМТ ХҮЧИНГҮЙ БОЛГОСОН';
        $printType       = Config::getFromCache('CONFIG_POS_PRINT_TYPE');
        $invoiceNumber   = $hdr['INVOICE_NUMBER'];
        $refNumber       = $hdr['REF_NUMBER'];
        $createdDate     = $hdr['CREATED_DATE'];
        $customerNumber  = $hdr['CUSTOMER_NUMBER']; 
        $customerName    = $hdr['CUSTOMER_NAME']; 

        $vatAmount   = $hdr['VAT'];
        $payAmount   = $hdr['TOTAL'];
        $cityTax     = $hdr['TOTAL_CITY_TAX_AMOUNT'];
        $totalAmount = $hdr['SUB_TOTAL'];
        $itemCount   = 0;
        $upointPart  = '';

        $itemPrintList = $salesPersonCode = $giftList = $paymentDetail = $discountPart = $lottery = $qrData = '';
        $paymentDtlTemplate = self::paymentDetailTemplate();

        if ($dtl) {

            if (Config::getFromCache('CONFIG_POS_HEALTHRECIPE')) {
                $itemPrintRenderFncName = 'generatePharmacyItemRow';
            } else {
                $itemPrintRenderFncName = 'generateItemRow';
            }

            foreach ($dtl as $dtlRow) {

                if ($dtlRow['IS_GIFT'] == '1') {

                    $giftRow = array(
                        'itemname'       => $dtlRow['ITEM_NAME'], 
                        'coupontypename' => '', 
                        'isDelivery'     => $dtlRow['IS_DELIVERY'], 
                        'invoiceqty'     => $dtlRow['INVOICE_QTY']
                    );

                    $giftList .= self::giftPrintRow($giftRow);

                } else {
                    $row = array(
                        'cityTax'        => $dtlRow['LINE_TOTAL_CITY_TAX_AMOUNT'], 
                        'itemName'       => $dtlRow['ITEM_NAME'], 
                        'salePrice'      => $dtlRow['UNIT_PRICE'], 
                        'itemQty'        => $dtlRow['INVOICE_QTY'], 
                        'totalPrice'     => $dtlRow['LINE_TOTAL_AMOUNT'], 
                        'unitReceivable' => $dtlRow['UNIT_RECEIVABLE'], 
                        'maxPrice'       => '', 
                        'isDelivery'     => $dtlRow['IS_DELIVERY']
                    );
                    $itemPrintList .= self::{$itemPrintRenderFncName}($row);

                    if ($dtlRow['SALES_PERSON_CODE']) {
                        $salesPersonCode .= $dtlRow['SALES_PERSON_CODE'].', ';
                    }
                }
            }

            $salesPersonCode = rtrim($salesPersonCode, ', ');
            $itemCount       = count($dtl);
        }

        if ($payment) {

            if ($hdr['TOTAL_CITY_TAX_AMOUNT'] > 0) {
                $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0082'), self::posAmount($hdr['TOTAL_CITY_TAX_AMOUNT'])), $paymentDtlTemplate);
            }

            foreach ($payment as $paymentRow) {
                $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array($paymentRow['PAYMENT_TYPE_NAME'], self::posAmount($paymentRow['AMOUNT'])), $paymentDtlTemplate);
            }
        }

        if ($hdr['CUSTOMER_NUMBER']) {
            $posBillType = 'organization';
        } else {
            $posBillType = 'person';
        }

        $templateContent = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.'/local/return.html');

        if ($hdr['RECEIPT_NUMBER']) {

            $recipeHeaderInfo = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/pharmacy/person/recipeHeaderInfo.html');

            $recipeReplacing = array(
                '{recipeCipherCode}'    => $hdr['CIPHER_CODE'], 
                '{recipePatientName}'   => $hdr['PATIENT_LAST_NAME'].' '.$hdr['PATIENT_FIRST_NAME'], 
                '{recipePatientRegNo}'  => $hdr['PATIENT_REG_NO'], 
                '{recipeReceiptNumber}' => $hdr['RECEIPT_NUMBER']
            );

            $recipeHeaderInfo = strtr($recipeHeaderInfo, $recipeReplacing);
            $templateContent  = str_replace('{recipeHeaderInfo}', $recipeHeaderInfo, $templateContent);

        } else {
            $templateContent = str_replace('{recipeHeaderInfo}', '', $templateContent);
        }
        
        $posUpointReturnResult = Input::post('posUpointReturnResult');
        if ($posUpointReturnResult) {            
            parse_str($_POST['paymentData'], $paymentData);
            $posUpointReturnResult = json_decode(html_entity_decode($posUpointReturnResult, ENT_QUOTES, 'UTF-8'), true);
            $upointPart = $this->upointTableRender(Input::post('upointDetectedNumberDtl'), $paymentData['upointBalance'], $posUpointReturnResult['refund_bonus_amount'], $posUpointReturnResult['point_balance'], 0);
        }        

        $replacing = array(
            '{poslogo}'         => $posLogo,
            '{companyName}'     => $topTitle,
            '{title}'           => $billTitle,
            '{vatNumber}'       => Mdpos::$posVatPayerNo,
            '{vatName}'         => Mdpos::$posVatPayerName,            
            '{contactInfo}'     => $contactInfo,
            '{date}'            => Date::formatter($createdDate, 'Y/m/d') . '<br/>Буц.Огноо: ' . Date::currentDate('Y/m/d'),
            '{time}'            => Date::formatter($createdDate, 'H:i:s') . '<br/>Буц.Цаг: ' . Date::currentDate('H:i:s'),
            '{refNumber}'       => $refNumber,
            '{invoiceNumber}'   => $invoiceNumber,
            '{storeName}'       => $storeName,
            '{cashierName}'     => $cashierName,
            '{cashCode}'        => $cashCode, 
            '{salesPersonCode}' => $salesPersonCode, 
            '{salesWaiter}'     => Input::post('waiterText', ''),
            '{orderTime}'       => Input::post('posEshopOrderTime', ''), 
            '{itemList}'        => $itemPrintList,
            '{totalAmount}'     => self::posAmount($totalAmount),
            '{payAmount}'       => self::posAmount($payAmount),
            '{vatAmount}'       => self::posAmount($vatAmount),
            '{discountPart}'    => $discountPart,
            '{totalItemCount}'  => self::posAmount($itemCount), 
            '{giftList}'        => self::giftTableRender($giftList), 
            '{payment-detail}'  => $paymentDetail,
            '{lottery}'         => $lottery,
            '{qrCode}'          => self::getQrCodeImg($qrData),
            '{ddtd}'            => $billId, 
            '{customerNumber}'  => $customerNumber, 
            '{customerName}'    => $customerName, 
            '{loyaltyPart}'     => '', 
            '{upointPart}'     => $upointPart, 
            '{promotion}'       => '', 
            '{qrCodeLottery}'   => '', 
            '{lockerCode}'      => '', 
            '{serialText}'      => '', 

            '{bonusCardNumber}'         => '', 
            '{bonusCardDiscountPercent}'=> '',
            '{bonusCardBeginAmount}'    => '', 
            '{bonusCardDiffAmount}'     => '', 
            '{bonusCardPlusAmount}'     => '', 
            '{bonusCardEndAmount}'      => ''
        );

        $internalContent = strtr($templateContent, $replacing);

        $lotteryContent = '';

        $internalContent = str_replace('{lotterypart}', $lotteryContent, $internalContent);        

        $result = array('status' => 'success', 'printData' => $internalContent);
        
        return $result;
    }
    
    public function getInvoiceMainDataModel($invoiceId) {
        
        $invoiceIdPh = $this->db->Param(0);
        $bindVars = array($this->db->addQ($invoiceId));

        $hdr = $this->db->GetRow("
            SELECT 
                HDR.INVOICE_NUMBER, 
                HDR.REF_NUMBER, 
                HDR.SUB_TOTAL, 
                HDR.TOTAL, 
                HDR.DISCOUNT, 
                HDR.VAT, 
                HDR.TOTAL_CITY_TAX_AMOUNT, 
                HDR.CREATED_DATE, 
                HDR.STORE_ID, 
                SS.CODE AS STORE_CODE, 
                SS.NAME AS STORE_NAME, 
                SC.NAME AS CASHIER_NAME, 
                SCR.CODE AS CASH_CODE,  
                BR.BILL_ID, 
                BR.CUSTOMER_NAME, 
                BR.CUSTOMER_NUMBER, 
                HDR.CUSTOMER_REG_NUMBER, 
                HDR.CREATED_USER_ID, 
                UM.DEPARTMENT_ID, 
                SIP.RECEIPT_NUMBER, 
                SIP.CIPHER_CODE, 
                SIP.PATIENT_LAST_NAME, 
                SIP.PATIENT_FIRST_NAME, 
                SIP.PATIENT_REG_NO, 
                HDR.PHONE_NUMBER, 
                SSD.POSAPI_DISTRICT_CODE, 
                (
                    SELECT COUNT(ID) FROM LOY_LOTTERY WHERE SALES_INVOICE_ID = $invoiceId AND CODE IS NOT NULL 
                ) AS LOTTERY_COUNT 
            FROM SM_SALES_INVOICE_HEADER HDR 
                INNER JOIN SM_STORE SS ON SS.STORE_ID = HDR.STORE_ID 
                INNER JOIN SM_STORE_DTL SSD ON SSD.STORE_ID = HDR.STORE_ID 
                INNER JOIN SM_CASHIER SC ON SC.CASHIER_ID = HDR.CREATED_CASHIER_ID 
                INNER JOIN SM_CASH_REGISTER SCR ON SCR.CASH_REGISTER_ID = HDR.CASH_REGISTER_ID 
                LEFT JOIN UM_USER UM ON UM.USER_ID = HDR.CREATED_USER_ID 
                LEFT JOIN SM_BILL_RESULT_DATA BR ON BR.SALES_INVOICE_ID = HDR.SALES_INVOICE_ID 
                LEFT JOIN SM_SALES_INVOICE_PRESCRIPTION SIP ON SIP.SALES_INVOICE_ID = HDR.SALES_INVOICE_ID 
            WHERE HDR.SALES_INVOICE_ID = $invoiceIdPh", $bindVars);

        $dtl = $this->db->GetAll("
            SELECT  
                CASE 
                    WHEN DTL.PRODUCT_ID IS NULL
                    THEN MJ.JOB_NAME 
                    ELSE IM.ITEM_NAME 
                END ITEM_NAME, 
                CASE 
                    WHEN DTL.PRODUCT_ID IS NULL
                    THEN MJ.JOB_CODE 
                    ELSE IM.ITEM_CODE 
                END ITEM_CODE, 
                SC.SALESPERSON_CODE AS SALES_PERSON_CODE, 
                DTL.IS_DELIVERY, 
                DTL.UNIT_PRICE, 
                DTL.UNIT_AMOUNT, 
                DTL.IS_GIFT, 
                DTL.INVOICE_QTY, 
                DTL.LINE_TOTAL_VAT, 
                DTL.LINE_TOTAL_AMOUNT, 
                DTL.LINE_TOTAL_CITY_TAX_AMOUNT, 
                DTL.PERCENT_DISCOUNT, 
                DTL.UNIT_RECEIVABLE, 
                DTL.BARCODE 
            FROM SM_SALES_INVOICE_DETAIL DTL 
                LEFT JOIN IM_ITEM IM ON IM.ITEM_ID = DTL.PRODUCT_ID 
                LEFT JOIN MES_JOB MJ ON MJ.JOB_ID = DTL.JOB_ID 
                LEFT JOIN SDM_SALESPERSON SC ON SC.EMPLOYEE_ID = DTL.EMPLOYEE_ID 
            WHERE DTL.SALES_INVOICE_ID = $invoiceIdPh", $bindVars);

        $payment = $this->db->GetAll("
            SELECT 
                SP.AMOUNT, 
                PT.PAYMENT_TYPE_NAME 
            FROM SM_SALES_PAYMENT SP 
                INNER JOIN SM_PAYMENT_TYPE PT ON PT.PAYMENT_TYPE_ID = SP.PAYMENT_TYPE_ID
            WHERE SP.SALES_INVOICE_ID = $invoiceIdPh", $bindVars);
        
        return array('header' => $hdr, 'detail' => $dtl, 'payment' => $payment);
    }
    
    public function orderSaveNotSendVatModel() {
        
        parse_str($_POST['paymentData'], $paymentData);
        parse_str($_POST['itemData'], $itemData);
        
        $sPrefix       = SESSION_PREFIX;
        $storeId       = Session::get($sPrefix.'storeId');
        $orderTypeId   = '';
        $recipientName = '';
        $recipientLastName = '';
        $recipientRegisterNum = '';
        $cityId        = '';
        $isBasket      = false;
        $isTempInvoice = false;
        $isBasketSelected = false; 
        
        if (isset($paymentData['isBasket']) && $paymentData['isBasket'] == '1') {
            $isBasket = true;
            $orderTypeId = 204;
        } else {
            $orderTypeId   = $paymentData['orderTypeId'];
            $cityId        = $paymentData['cityId'];
            $recipientName = $paymentData['recipientName'];
            $recipientLastName = $paymentData['recipientLastName'];
            $recipientRegisterNum = $paymentData['recipientRegisterNum'];
        }
        
        if (isset($paymentData['isBasketSelected']) && $paymentData['isBasketSelected'] == '1') {
            $isBasketSelected = true; 
        }
        
        $currentDate    = Date::currentDate('Y-m-d H:i:s');
        
        $totalAmount    = Number::decimal($paymentData['payAmount']);
        $vatAmount      = Number::decimal($paymentData['vatAmount']);
        $cityTaxAmount  = Number::decimal($paymentData['cityTaxAmount']);
        $discountAmount = Number::decimal($paymentData['discountAmount']);
        $subTotal       = $totalAmount + $discountAmount;
        
        if (isset($paymentData['phoneNumber'])) {
            $phoneNumber = $paymentData['phoneNumber'];
        } else {
            $phoneNumber = '';
        }

        $lockerCode = '';
        $lockerId = '';
        if (isset($paymentData['lockerId']) && $paymentData['lockerId']) {
            $lockerExp = explode('_', $paymentData['lockerId']);
            $lockerId = $lockerExp[0];
            $lockerCode = $lockerExp[1];
        }
            
        $params = array(
            'bookTypeId'           => $orderTypeId, 
            'invoiceDate'          => $currentDate, 
            'createdDateTime'      => $currentDate, 
            'storeId'              => $storeId,
            'totalCityTaxAmount'   => $cityTaxAmount, 
            'subTotal'             => $subTotal, 
            'discount'             => $discountAmount, 
            'vat'                  => $vatAmount, 
            'cardId'               => $lockerId, 
            'id'                   => $paymentData['basketInvoiceId'], 
            'total'                => $totalAmount, 
            'deliveryContactPhone' => $phoneNumber, 
            'deliveryContactName'  => $recipientName, 
            'deliveryContactLastname' => $recipientLastName, 
            'deliveryRegisterNum'  => $recipientRegisterNum, 
            'description'          => issetParam($paymentData['description'])
        );
        
        if ($orderTypeId == 91) {
            $params['bankAccountId'] = $paymentData['bankAccountId'];
            $params['invoiceTypeId'] = $paymentData['invoiceTypeId'];
            $params['dueDate']       = $paymentData['expireDate'].':01';
        }
        
        if (isset($paymentData['customerId'])) {
            $params['customerId'] = $paymentData['customerId'];
            $isTempInvoice = true;
        }
        if ($lockerId) {
            $isTempInvoice = true;
        }
        
        $paramsDtl = array();
        $itemIds   = $itemData['itemId'];
        $itemPrintList = '';
        $totalItemCount = 0;
        $printTotalAmount = 0;
        $maxPrice = 0;
        $cashRegisterId = Session::get(SESSION_PREFIX.'cashRegisterId');
        $totalAmount = 0;
        $vatAmount = 0;
        $waitTotalAmount = 0;
        
        foreach ($itemIds as $k => $itemId) {            
            
            $itemQty        = Number::decimal($itemData['quantity'][$k]);
            $salePrice      = $itemData['salePrice'][$k];
            $totalPrice     = $itemData['totalPrice'][$k];
            $totalAmount   += $totalPrice;
            $unitAmount     = $salePrice;
            $lineTotalAmount= $totalPrice;                        
            
            $printLineTotalAmount=$lineTotalAmount;
            $isVat          = $itemData['isVat'][$k];
            $vatPercent     = $itemData['vatPercent'][$k];
            $noVatPrice     = $itemData['noVatPrice'][$k];
            
            $isDiscount         = $itemData['isDiscount'][$k];
            $discountPercent    = empty($itemData['discountPercent'][$k]) ? 0 : $itemData['discountPercent'][$k];
            $dtlDiscountPercent = $discountPercent;
            $dtlDiscountAmount  = $itemData['discountAmount'][$k];
            $unitReceivable     = $itemData['unitReceivable'][$k];
            $unitDiscount       = 0;
            $dtlUnitDiscount    = 0;
            $lineTotalDiscount  = 0;
            
            $isDelivery     = $itemData['isDelivery'][$k];
            $employeeId     = $itemData['employeeId'][$k];
            $sectionId     = isset($itemData['sectionId']) ? $itemData['sectionId'][$k] : '';
            
            $discountEmployeeId   = $itemData['discountEmployeeId'][$k];
            $discountTypeId       = isset($itemData['discountTypeId'][$k]) ? $itemData['discountTypeId'][$k] : '';
            $storeWarehouseId     = $itemData['storeWarehouseId'][$k];
            $deliveryWarehouseId  = $itemData['deliveryWarehouseId'][$k];
            
            $isCityTax      = $itemData['isCityTax'][$k];
            $cityTax        = ($isCityTax == '1' ? $itemData['cityTax'][$k] : 0);
            $lineTotalCityTaxAmount = 0;
            
            if ($isVat == '1' && $isDiscount != '1') {
                
                if ($isCityTax == '1') {
                    $unitVat = number_format($itemData['vatTax'][$k], 2, '.', '');
                    $lineTotalCityTaxAmount = $cityTax * $itemQty;
                } else {
                    $unitVat = number_format($salePrice - $noVatPrice, 2, '.', '');
                }
                
                $lineTotalVat = number_format($unitVat * $itemQty, 2, '.', '');
                
            } elseif ($isVat == '1' && $isDiscount == '1') {
                
                $unitAmount = $dtlDiscountAmount;
                $noVatPrice = number_format($dtlDiscountAmount / 1.1, 2, '.', '');
                
                if ($isCityTax == '1') {
                    $unitVat = number_format($unitAmount / 11.1, 2, '.', '');
                    $cityTax = number_format($unitAmount / 111, 2, '.', '');
                    $lineTotalCityTaxAmount = $cityTax * $itemQty;
                } else {
                    $unitVat = number_format($dtlDiscountAmount - $noVatPrice, 2, '.', '');
                }
                
                $lineTotalVat   = number_format($unitVat * $itemQty, 2, '.', '');
                
                $unitDiscount      = $itemData['unitDiscount'][$k];
                $lineTotalDiscount = $unitDiscount * $itemQty;
                $lineTotalAmount = $unitAmount * $itemQty;
                $dtlUnitDiscount   = $unitDiscount;
                
            } else {
                
                if ($isDiscount == '1') {
                    
                    $unitVat        = 0;
                    $lineTotalVat   = 0;
                    $unitAmount     = $dtlDiscountAmount;
                    
                    $unitDiscount       = $itemData['unitDiscount'][$k];
                    $lineTotalDiscount  = $unitDiscount * $itemQty;
                    $lineTotalAmount = $unitAmount * $itemQty;
                    $dtlUnitDiscount    = $unitDiscount;
                    
                } else {
                    $unitVat = $lineTotalVat = 0;
                }
            }
            
            $itemCode       = $itemData['itemCode'][$k];
            $printItemName  = $itemData['itemName'][$k];
            $serialNumber   = $itemData['serialNumber'][$k];
            $barCode        = $itemData['barCode'][$k];
            $barCode        = ($barCode ? $barCode : '132456789');
            
            $isJob          = $itemData['isJob'][$k];
            $jobId          = '';
            $dtlCouponKeyId = '';
            
            if ($isJob == '1') {
                
                $jobId      = $itemId;
                $itemId     = '';
                $isDelivery = 0;
                
            } elseif ($isJob == '2') {
                
                $dtlCouponKeyId = $itemId;
                $jobId          = '';
                $itemId         = '';
                $isDelivery     = 0;
            }
            
            $vatAmount += $lineTotalVat;
            $paramsDtl[$k] = array(
                'itemId'                 => $itemId, 
                'jobId'                  => $jobId, 
                'invoiceQty'             => $itemQty, 
                
                'unitPrice'              => $salePrice,
                'lineTotalPrice'         => $totalPrice,  
                'unitAmount'             => $unitAmount, 
                'lineTotalAmount'        => $lineTotalAmount,  
                'isCityTax'              => $isCityTax,  
                'cityTax'                => $cityTax,  
                'lineTotalCityTaxAmount' => $lineTotalCityTaxAmount,  
                
                'isVat'                  => $isVat, 
                'percentVat'             => $vatPercent, 
                'unitVat'                => $unitVat, 
                'lineTotalVat'           => $lineTotalVat,
                
                'percentDiscount'        => $discountPercent, 
                'unitDiscount'           => $unitDiscount, 
                'lineTotalDiscount'      => $lineTotalDiscount, 
                'unitReceivable'         => $unitReceivable, 
                
                'discountPercent'        => $dtlDiscountPercent, 
                'discountAmount'         => $dtlUnitDiscount, 
                
                'isDelivery'             => $isDelivery,  
                'employeeId'             => $employeeId, 
                'discountEmployeeId'     => $discountEmployeeId, 
                'discountTypeId'         => $discountTypeId, 
                'serialNumber'           => $serialNumber, 
                'isRemoved'              => 0, 
                
                'itemCode'               => $itemCode, 
                'itemName'               => $printItemName, 
                'barCode'                => $barCode, 
                'couponKeyId'            => $dtlCouponKeyId,
                'sectionId'              => $sectionId,
                'cashRegisterId'         => $cashRegisterId,
                'salesOrderId'           => $paymentData['basketInvoiceId']
            );
            
            $giftJsonStr = trim($itemData['giftJson'][$k]);
            
            if ($giftJsonStr != '') {
                
                $itemPackageList = $itemGiftList = array();
                $giftJsonArray = json_decode(html_entity_decode($giftJsonStr), true);
                
                foreach ($giftJsonArray as $giftJsonRow) {
                    
                    $giftJsonRow['isDelivery'] = isset($giftJsonRow['isDelivery']) ? $giftJsonRow['isDelivery'] : 0;
                    $giftJsonRow['invoiceqty'] = $itemQty;
                    $giftJsonRowMerge          = array();
                    
                    $itemPackageList[] = array(
                        'packageDtlId'     => $giftJsonRow['packagedtlid'],
                        'qty'              => $itemQty, 
                        'discountPolicyId' => $giftJsonRow['policyid'], 
                        'isDelivery'       => $giftJsonRow['isDelivery']
                    );
                    
                    if ($giftJsonRow['coupontypeid'] == '') {
                        
                        $giftJsonRow['isgift']     = 1;
                        $giftJsonRow['employeeId'] = $employeeId; 
                        $giftJsonRow['itemid']     = $giftJsonRow['promotionitemid']; 
                        $giftJsonRow['jobid']      = $giftJsonRow['jobid']; 
                        
                        $itemGiftPrice = $giftJsonRow['saleprice'];
                        
                        if ($itemGiftPrice > 0 && ($giftJsonRow['discountamount'] > 0 || $giftJsonRow['discountpercent'] > 0)) {
                            
                            $giftDiscountAmount = $itemGiftPrice;
                                
                            if ($giftJsonRow['discountamount'] > 0) {

                                $giftDiscountAmount = $giftJsonRow['discountamount'];

                            } elseif ($giftJsonRow['discountpercent'] > 0) {

                                $giftDiscount = ($giftJsonRow['discountpercent'] / 100) * $itemGiftPrice;
                                $giftDiscountAmount = $itemGiftPrice - $giftDiscount;
                            }
                                                    
                            $itemGiftPrice       = $itemGiftPrice - $giftDiscountAmount;
                            $giftLineTotalAmount = $itemGiftPrice * $itemQty;
                            
                            $giftLineTotalDiscount = 0;
                            $giftUnitVat = number_format($itemGiftPrice - (number_format($itemGiftPrice / 1.1, 2, '.', '')), 2, '.', '');
                            
                            $giftJsonRow['saleprice'] = $itemGiftPrice;
                            $giftJsonRow['unitPrice'] = $itemGiftPrice;
                            $giftJsonRow['lineTotalPrice'] = $giftLineTotalAmount;
                            
                            $giftJsonRow['unitAmount'] = $itemGiftPrice;
                            $giftJsonRow['lineTotalAmount'] = $giftLineTotalAmount;
                
                            $giftJsonRow['percentVat'] = 10;
                            $giftJsonRow['unitVat'] = $giftUnitVat;
                            $giftJsonRow['lineTotalVat'] = number_format($giftJsonRow['unitVat'] * $itemQty, 2, '.', '');
                            
                            $giftJsonRow['percentDiscount'] = 0;
                            $giftJsonRow['unitDiscount'] = 0;
                            $giftJsonRow['lineTotalDiscount'] = $giftLineTotalDiscount;
                            
                            $giftJsonRowMerge['invoiceqty'] = 1;
                            $giftJsonRowMerge['unitPrice'] = $itemGiftPrice;
                            $giftJsonRowMerge['lineTotalPrice'] = $itemGiftPrice;
                            
                            $giftJsonRowMerge['unitAmount'] = $itemGiftPrice;
                            $giftJsonRowMerge['lineTotalAmount'] = $itemGiftPrice;
                
                            $giftJsonRowMerge['percentVat'] = 10;
                            $giftJsonRowMerge['unitVat'] = number_format($itemGiftPrice - (number_format($itemGiftPrice / 1.1, 2, '.', '')), 2, '.', '');
                            $giftJsonRowMerge['lineTotalVat'] = $giftJsonRowMerge['unitVat'];
                            
                        }
                        
                        if ($giftJsonRow['jobid'] == '') {
                            
                            if ($giftJsonRow['isDelivery'] == 1) {
                                $giftJsonRow['warehouseId'] = $deliveryWarehouseId; 
                            } else {
                                $giftJsonRow['warehouseId'] = $storeWarehouseId; 
                            }
                            
                            $giftJsonRow['deliveryWarehouseId'] = $deliveryWarehouseId;
                            $giftJsonRow['storeWarehouseId']    = $storeWarehouseId;
                        }
                        
                        $itemGiftList[] = $giftJsonRow;
                    } 
                }
                
                $paramsDtl[$k]['SDM_SALES_ORDER_ITEM_PACKAGE'] = $itemPackageList;
                $paramsDtl[$k]['POS_SDM_SALES_ORDER_ITEM_DTL'] = $itemGiftList;
            }
            
            if ($isJob == '0' || $isJob == '') {
                
                if ($isDelivery == 1) {
                    $paramsDtl[$k]['warehouseId'] = $deliveryWarehouseId;
                } else {
                    $paramsDtl[$k]['warehouseId'] = $storeWarehouseId;
                }
                
                $paramsDtl[$k]['deliveryWarehouseId'] = $deliveryWarehouseId;
                $paramsDtl[$k]['storeWarehouseId']    = $storeWarehouseId;
            }
            
            $printUnitAmount      = $unitAmount;
            $waitQty = isset($itemData['savedQuantity']) && isset($itemData['savedQuantity'][$k]) ? $itemData['savedQuantity'][$k] : $itemQty;
            
            if (isset($itemData['salesOrderDetailId'][$k])) {
                $paramsDtl[$k]['id'] = $itemData['salesOrderDetailId'][$k];
            }
            
            if (!isset($itemData['salesOrderDetailId'][$k])) {
                $totalItemCount += $itemQty;
                $row = array(
                    'cityTax'       => '', 
                    'isDelivery'    => '',
                    'itemName'      => $printItemName, 
                    'salePrice'     => $printUnitAmount, 
                    'itemQty'       => $itemQty, 
                    'totalPrice'    => $printUnitAmount * $itemQty
                );                
                $waitTotalAmount += $row['totalPrice'];
                $itemPrintList .= self::generateItemRow($row);       
            } elseif ($waitQty < $itemQty) {                
                $waitQty = $itemQty - $waitQty;
                $totalItemCount += $waitQty;
                $row = array(
                    'cityTax'       => '', 
                    'isDelivery'    => '',
                    'itemName'      => $printItemName, 
                    'salePrice'     => $printUnitAmount, 
                    'itemQty'       => $waitQty, 
                    'totalPrice'    => $printUnitAmount * $waitQty
                );                
                $waitTotalAmount += $row['totalPrice'];
                $itemPrintList .= self::generateItemRow($row);                 
            }
        }
        if ($waitTotalAmount === 0) {
            $waitTotalAmount = $totalAmount;
        }
        $param['total'] = $totalAmount;
        $param['vat'] = $vatAmount;
        $param['subTotal'] = $totalAmount;
        
        $params['POS_SDM_SALES_ORDER_ITEM_DTL'] = $paramsDtl;
        
        if ($cityId) {
            $params['NEXT_SDM_DELIVERY_BOOK'] = array(
                'cityId'       => $cityId, 
                'districtId'   => issetParam($paymentData['districtId']), 
                'cityStreetId' => issetParam($paymentData['streetId']), 
                'contactName'  => $recipientName, 
                'address'      => $paymentData['detailAddress'], 
                'phoneNumber1' => $phoneNumber, 
                'dueDate'      => $paymentData['dueDate'].':01'
            );
        }
        
        if ($isBasket == false) {
            
            $prevInfo = self::getAddressInfoByPhoneModel($phoneNumber);
            
        } elseif ($lockerId) {
            $prevInfo = null;
            
            if ($isTempInvoice && issetParam($paymentData['lockerOrderId'])) {
                // $prevInfo = self::getTempInvoiceByLockerIdModel($lockerId);
                $prevInfo['id'] = issetParam($paymentData['lockerOrderId']);
            }      
        } else {
            
            $prevInfo = null;
            
            if ($isTempInvoice) {
                $prevInfo = self::getTempInvoiceByCustomerIdModel($params['customerId']);
            }
        }
        
        if ($prevInfo) {
            
            $params['id']    = $prevInfo['id'];
            $params['total'] = $prevInfo['total'] + $totalAmount;
                    
            unset($params['invoiceNumber']);
            unset($params['invoiceDate']);
            unset($params['createdDateTime']);
            
            $processCode = 'POS_SALES_PERSON_DTL_ORDER_001';
            
//            if ($isTempInvoice && $prevInfo) {
//                $prm = array('salesOrderId' => $paymentData['basketInvoiceId']);
//                $prm['cashRegisterId'] = Session::get(SESSION_PREFIX.'cashRegisterId');
//                $this->ws->runSerializeResponse(self::$gfServiceAddress, 'DELETE_SDM_SALES_ORDER_ITEM_DTL_DV_005', $prm);
//            }
            
        } else {
            
            $invoiceNumber = self::getPosInvoiceNumber('1522946993342', array('bookTypeId' => $orderTypeId, 'storeId' => $storeId));
            $params['invoiceNumber'] = $invoiceNumber;
            
            $processCode = 'POS_SALES_PERSON_ORDER_001';
        }

        if ($windowSessionId = issetParam($paymentData['windowSessionId'])) {
            WebService::$addonHeaderParam['windowSessionId'] = $windowSessionId;
        }        
        
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, $processCode, $params);
        
        if ($result['status'] == 'success') {
            
            $vatNumber       = Session::get($sPrefix.'vatNumber');
            $storeName       = Session::get($sPrefix.'storeName');
            $cashCode        = Session::get($sPrefix.'cashRegisterCode');
            $cashierName     = Session::get($sPrefix.'cashierName');
            $contactInfo     = Config::getFromCache('POS_BASKET_CONFIG_INFO');
            $topTitle        = Config::getFromCache('POS_BASKET_HEADER_NAME');
            $billTitle       = (isset($billTitle) ? $billTitle : 'ДОТООД ТАЛОН');
            $salesPersonCode = self::getSalesPersonCode($itemData);

            $templateContent = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.Config::getFromCache('CONFIG_POS_PRINT_TYPE').'/local/expense2.html');

            $discountPart    = '';
            $cashRegisterId = Session::get($sPrefix.'cashRegisterId');
            $refNumber       = self::getPosInvoiceRefNumber($storeId, $cashRegisterId);

            WebService::$addonHeaderParam['windowSessionId'] = "";
            $replacing = array(
                '{companyName}'     => $topTitle,
                '{title}'           => $billTitle, 
                '{vatNumber}'       => $vatNumber,
                '{contactInfo}'     => $contactInfo,
                '{date}'            => Date::formatter($currentDate, 'Y/m/d'),
                '{time}'            => Date::formatter($currentDate, 'H:i:s'),
                '{refNumber}'       => $refNumber,
                '{invoiceNumber}'   => self::getBillNumModel(),
                '{storeName}'       => $storeName,
                '{cashierName}'     => $cashierName,
                '{cashCode}'        => $cashCode, 
                '{salesPersonCode}' => $salesPersonCode, 
                '{salesWaiter}'     => Input::post('waiterText', ''),
                '{orderTime}'       => Input::post('posEshopOrderTime', ''), 
                '{itemList}'        => $itemPrintList,
                '{totalAmount}'     => self::posAmount($waitTotalAmount),
                '{payAmount}'       => '',
                '{vatAmount}'       => '',
                '{discountPart}'    => $discountPart,
                '{totalItemCount}'  => self::posAmount($totalItemCount), 
                '{payment-detail}'  => '',
                '{serialText}'  => '',
                '{lockerCode}'      => self::printLockerCode($lockerCode)
            );

            $internalContent = strtr($templateContent, $replacing);            
            
            $response = array('status' => 'success', 'message' => 'Амжилттай хадгалагдлаа.', 'printData' => $internalContent, 'id' => $result['result']['id'], 'orderTypeId' => $orderTypeId);
            
            $response['basketCount'] = self::getBasketOrderBookCountModel($storeId);
            
        } else {
            $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
        
        return $response;
    }

    public function billTypeReduce3PrintModel($returnInvoiceId) {
        unset($_POST['returnInvoiceId']);
        $billDate       = Input::post('returnInvoiceBillDate');
        $isTodayReturn = false;
        
        if (Date::formatter($billDate, 'Y-m-d') == Date::currentDate('Y-m-d')) {
            $isTodayReturn = true;
        }  

        if ($isTodayReturn) {
            $_POST['returnInvoiceIdReduce'] = $returnInvoiceId;
        } else {
            $_POST['parentSalesInvoiceId'] = $returnInvoiceId;
        }

        $billResult = $this->billPrintModel();
        
        return $billResult;
    }

    public function billTypeReduce2PrintModel($returnInvoiceId) {
        
        if (Input::postCheck('paymentData') == false) {
            $response = array('status' => 'warning', 'message' => 'Төлбөр төлөлтийн мэдээлэл олдсонгүй. Та дахин оролдоно уу.');
            return $response;
        }
        
        $returnBillId   = Input::post('returnInvoiceBillId');
        $billDate       = Input::post('returnInvoiceBillDate');        
        $isTodayReturn = false;
        
        if (Date::formatter($billDate, 'Y-m-d') == Date::currentDate('Y-m-d')) {
            $isTodayReturn = true;
        }        
        
        /*if ($returnBillId) {

            $jsonParam = "{
                'returnBillId': '" . $returnBillId . "',
                'date': '" . str_replace(':', '=', $billDate) . "'
            }";

            $posApiArray = self::posApiReturnBillFunction($jsonParam);

        } else {
            $posApiArray['success'] = true;
        }

        if (isset($posApiArray['success'])) {
            if ($isTodayReturn) {
                $this->todayUpdateBill();
            } else {
                $this->ancientUpdateBill();
            }
        }*/
        
        if ($isTodayReturn) {
            $this->todayUpdateBill($returnBillId);
        } else {
            $this->ancientUpdateBill();
        }        
        
    }

    private function todayUpdateBill() {
        parse_str($_POST['paymentData'], $paymentData);
        
        $sPrefix        = SESSION_PREFIX;
        
        $storeId        = Session::get($sPrefix.'storeId');
        $cashierId      = Session::get($sPrefix.'cashierId');
        $cashRegisterId = Session::get($sPrefix.'cashRegisterId');
        $isServicePos   = Session::get($sPrefix.'posIsService');
        
        $refNumber      = self::getPosInvoiceRefNumber($storeId, $cashRegisterId);
        $invoiceNumber  = self::getBillNumModel();
        $currentDate    = Date::currentDate('Y-m-d H:i:s');
        
        parse_str($_POST['itemData'], $itemData);
        
        $vatAmount              = Number::decimal($paymentData['vatAmount']);
        $cityTaxAmount          = Number::decimal($paymentData['cityTaxAmount']);
        $discountAmount         = Number::decimal($paymentData['discountAmount']);
        $itemDiscountAmount     = $discountAmount;
        
        $totalAmount            = Number::decimal($paymentData['payAmount']);
        $cashAmount             = Number::decimal($paymentData['cashAmount']);
        $bankAmount             = Number::decimal($paymentData['bankAmount']);
        $voucherAmount          = Number::decimal($paymentData['voucherAmount']);
        
        $bonusCardAmount        = Number::decimal($paymentData['bonusCardAmount']);
        
        $totalBonusAmount       = $voucherAmount + $bonusCardAmount;
        $subTotal               = $totalAmount + $discountAmount;
        $printTotalAmount       = ($discountAmount < 0) ? $totalAmount : $subTotal;        
        $accountTransferAmt     = Number::decimal($paymentData['posAccountTransferAmt']);
        $mobileNetAmt           = Number::decimal($paymentData['posMobileNetAmt']);
        $barterAmt              = Number::decimal($paymentData['posBarterAmt']);
        $leasingAmt             = Number::decimal($paymentData['posLeasingAmt']);
        $empLoanAmt             = Number::decimal($paymentData['posEmpLoanAmt']);
        $localExpenseAmt        = Number::decimal($paymentData['posLocalExpenseAmt']);
        $emdAmount              = Number::decimal($paymentData['posEmdAmt']);
        $candyAmount            = Number::decimal(issetParam($paymentData['posCandyAmt']));
        $deliveryAmount         = Number::decimal(issetParam($paymentData['posDeliveryAmt']));
        $lendMnAmount           = Number::decimal(issetParam($paymentData['posLendMnAmt']));
        
        $changeAmount           = $paymentData['changeAmount'];
        $changeAmount           = ($changeAmount ? Number::decimal($changeAmount) : 0);
        
        $nonCashAmount          = 0;
        $totalItemCount         = 0;
        $generateVaucherAmt     = 0;
        $isVatCalc              = true;
        $posBillType            = $paymentData['posBillType'];

        $vatNumber      = Session::get($sPrefix.'vatNumber');
        $districtCode   = Session::get($sPrefix.'posDistrictCode');
        $cashCode       = Session::get($sPrefix.'cashRegisterCode');
        $isNotSendVatsp = (Session::get($sPrefix.'isNotSendVatsp') == '1' ? true : false);
        $reportMonth    = '';
        $customerNo     = '';
        $orgRegNumber   = '';
        $orgName        = '';
        $billIdSuffix   = '';
        $taxType        = 1;
        
        $params = array(
            'bookTypeId'        => 9, 
            'invoiceNumber'     => $invoiceNumber, 
            'refNumber'         => $refNumber, 
            'invoiceDate'       => $currentDate, 
            'createdDateTime'   => $currentDate, 
            'storeId'           => $storeId,
            'cashRegisterId'    => $cashRegisterId,
            'createdCashierId'  => $cashierId, 
            'totalCityTaxAmount'=> $cityTaxAmount, 
            'subTotal'          => $subTotal, 
            'discount'          => $discountAmount, 
            'vat'               => $vatAmount, 
            'total'             => $totalAmount, 
            'changeAmount'      => $changeAmount, 
            'wfmStatusId'       => '1505964291977811', 
            'customerId'        => ''
        );
        
        $serviceCustomerId = null;
        
        if (issetParam($paymentData['serviceCustomerId']) != '') {
            
            $params['customerId'] = $paymentData['serviceCustomerId'];
            
        } elseif (issetParam($paymentData['newServiceCustomerJson']) != '') {
            
            $serviceCustomerResult = self::createServiceCustomer($paymentData['newServiceCustomerJson']);
                
            if ($serviceCustomerResult['status'] != 'success') {
                
                $response = array('status' => 'warning', 'message' => Lang::line('POS_0081') . ' (CRM). '.$serviceCustomerResult['message']);
                return $response;
                
            } elseif (isset($serviceCustomerResult['customerId'])) {
                
                $serviceCustomerId    = $serviceCustomerResult['customerId'];
                $params['customerId'] = $serviceCustomerResult['customerId'];
            }
        }        
        
        $headerParams = $params;
        
        $paramsDtl = $paymentDtl = $voucherDtl = $voucherUsedDtl = $deliveryDtl = $noDeliveryDtl = $serviceDtl = $couponKeyDtl = $atBankBillingIds = array();
        $itemPrintList = $stocks = $giftList = $paymentDetail = $discountDetail = $talonInvoiceId = '';
        
        $paymentDtlTemplate = self::paymentDetailTemplate();
        
        if (Config::getFromCache('CONFIG_POS_HEALTHRECIPE')) {
            $itemPrintRenderFncName = 'generatePharmacyItemRow';
        } else {
            $itemPrintRenderFncName = 'generateItemRow';
        }
        
        $sumTotal = $itemPrintCopiesLast = 0;
        $itemIds = $itemData['itemId'];
        
        foreach ($itemIds as $k => $itemId) {
            
            $itemQty        = Number::decimal($itemData['quantity'][$k]);
            $salePrice      = $itemData['salePrice'][$k];
            $totalPrice     = $itemData['totalPrice'][$k];
            $unitAmount     = $salePrice;
            $lineTotalAmount= $totalPrice;
            
            $isVat          = $itemData['isVat'][$k];
            $vatPercent     = $itemData['vatPercent'][$k];
            $noVatPrice     = $itemData['noVatPrice'][$k];
            
            $isDiscount         = $itemData['isDiscount'][$k];
            $discountPercent    = $itemData['discountPercent'][$k];
            $dtlDiscountPercent = $discountPercent;
            $dtlDiscountAmount  = $itemData['discountAmount'][$k];
            $unitReceivable     = $itemData['unitReceivable'][$k];
            $unitReceivable     = $unitReceivable ? number_format($unitReceivable * $itemQty, 2, '.', '') : '';
            $maxPrice           = $itemData['maxPrice'][$k];
            $unitDiscount       = 0;
            $dtlUnitDiscount    = 0;
            $lineTotalDiscount  = 0;
            
            $isDelivery     = $itemData['isDelivery'][$k];
            $employeeId     = $itemData['employeeId'][$k];
            $sectionId     = isset($itemData['sectionId']) ? $itemData['sectionId'][$k] : '';
            
            $discountEmployeeId   = $itemData['discountEmployeeId'][$k];
            $discountTypeId       = $itemData['discountTypeId'][$k];
            $discountDescription  = $itemData['discountDescription'][$k];
            $storeWarehouseId     = $itemData['storeWarehouseId'][$k];
            $deliveryWarehouseId  = $itemData['deliveryWarehouseId'][$k];
            $itemPrintCopies      = issetParam($itemData['printCopies'][$k]);
            
            $printUnitAmount      = $unitAmount;
            $printLineTotalAmount = $lineTotalAmount;
            
            $isCityTax      = $itemData['isCityTax'][$k];
            $cityTax        = ($isCityTax == '1' ? $itemData['cityTax'][$k] : 0);
            $lineTotalCityTaxAmount = 0;
            
            if ($isVat == '1' && $isDiscount != '1') {
                
                if ($isCityTax == '1') {
                    $unitVat = number_format($salePrice - $noVatPrice - $cityTax, 2, '.', '');
                    $lineTotalCityTaxAmount = number_format($cityTax * $itemQty, 2, '.', '');
                } else {
                    $unitVat = number_format($salePrice - $noVatPrice, 2, '.', '');
                }
                
                $lineTotalVat = number_format($unitVat * $itemQty, 2, '.', '');
                
            } elseif ($isVat == '1' && $isDiscount == '1') {
                
                $unitDiscount = $itemData['unitDiscount'][$k];
                $unitAmount   = $dtlDiscountAmount;
                $noVatPrice   = number_format($dtlDiscountAmount / 1.1, 2, '.', '');
                
                if ($isCityTax == '1') {
                    $unitVat = number_format($dtlDiscountAmount - $noVatPrice - $cityTax, 2, '.', '');
                    $lineTotalCityTaxAmount = number_format($cityTax * $itemQty, 2, '.', '');
                } else {
                    $unitVat = number_format($dtlDiscountAmount - $noVatPrice, 2, '.', '');
                }
                
                $lineTotalVat = number_format($unitVat * $itemQty, 2, '.', '');
                $lineTotalAmount = $itemData['totalDiscount'][$k];
                $dtlUnitDiscount = $unitDiscount;
                
                if ($unitDiscount > 0) {
                    $lineTotalDiscount = $unitDiscount * $itemQty;
                } else {
                    $unitDiscount      = 0;
                    $discountPercent   = 0;
                    $lineTotalDiscount = 0;
                    
                    $printUnitAmount      = $dtlDiscountAmount;
                    $printLineTotalAmount = $lineTotalAmount;
                }
                
            } else {
                
                if ($isDiscount == '1') {
                    
                    $unitVat        = 0;
                    $lineTotalVat   = 0;
                    $unitAmount     = $dtlDiscountAmount;
                    
                    $unitDiscount      = $itemData['unitDiscount'][$k];
                    $lineTotalDiscount = $unitDiscount * $itemQty;
                    $lineTotalAmount   = $itemData['totalDiscount'][$k];
                    $dtlUnitDiscount   = $unitDiscount;
                    
                } else {
                    $unitVat = $lineTotalVat = 0;
                }
            }
            
            $itemCode       = $itemData['itemCode'][$k];
            $printItemName  = $itemData['itemName'][$k];
            $serialNumber   = $itemData['serialNumber'][$k];
            $itemKeyId      = issetParam($itemData['itemKeyId'][$k]);
            $barCode        = $itemData['barCode'][$k];
            $barCode        = ($barCode ? $barCode : '132456789');
            
            $isJob          = $itemData['isJob'][$k];
            $jobId          = '';
            $dtlCouponKeyId = '';
            
            if ($isJob == '1') {
                
                $jobId  = $itemId;
                $itemId = '';
                
                if ($isDelivery == '1' && $isServicePos != '1') {
                    
                    $serviceDtl[] = array(
                        'jobId'   => $jobId, 
                        'RPR_KEY' => array(
                            'customerId'      => $headerParams['customerId'], 
                            'jobid'           => $jobId, 
                            'purchaseStoreId' => $storeId
                        )
                    );
                }
                
                $isDelivery = 0;
                
            } elseif ($isJob == '2') {
                
                $dtlCouponKeyId = $itemId;
                $jobId          = '';
                $itemId         = '';
                $isDelivery     = 0;
                
                $couponKeyDtl[] = array(
                    'couponKeyId' => $dtlCouponKeyId, 
                    'rowIndex'    => $k
                );
            }
            
            if ($totalBonusAmount > 0) {
                
                if ($totalAmount > 0) {
                    
                    $unitDiscount      = ($dtlBonusPercent / 100) * $unitAmount;
                    $dtlDiscountAmount = $unitAmount - $unitDiscount;

                    $unitAmount     = $dtlDiscountAmount;
                    $noVatPrice     = number_format($dtlDiscountAmount / 1.1, 2, '.', '');
                    $unitVat        = number_format($dtlDiscountAmount - $noVatPrice, 2, '.', '');

                    $lineTotalDiscount = $unitDiscount * $itemQty;
                    $lineTotalAmount   = $dtlDiscountAmount * $itemQty;

                    $lineTotalVat   = $lineTotalAmount - $lineTotalAmount / 1.1;
                    
                } else {
                                        
                    $unitVat           = 0;
                    $lineTotalVat      = 0;
                    $unitAmount        = 0;
                    $lineTotalAmount   = 0;
                    
                    $unitDiscount      = $salePrice;
                    $lineTotalDiscount = $unitDiscount * $itemQty;
                }
            }
            
            if ($isVatCalc == false) {
                $unitAmount = $unitAmount - $unitVat;
                $lineTotalAmount = $lineTotalAmount - $lineTotalVat;
                        
                $unitVat = 0;
                $lineTotalVat = 0;
                
                $printUnitAmount = $unitAmount;
                $printLineTotalAmount = $lineTotalAmount; 
            }
            
            $paramsDtl[$k] = array(
                'itemId'                 => $itemId, 
                'jobId'                  => $jobId, 
                'invoiceQty'             => Number::decimal($itemQty), 
                
                'unitPrice'              => $salePrice,
                'lineTotalPrice'         => $totalPrice,  
                'unitAmount'             => $unitAmount, 
                'lineTotalAmount'        => $lineTotalAmount,  
                'lineTotalCityTaxAmount' => $lineTotalCityTaxAmount,  
                
                'isVat'                  => $isVat, 
                'percentVat'             => $vatPercent, 
                'unitVat'                => $unitVat, 
                'lineTotalVat'           => $lineTotalVat,
                
                'percentDiscount'        => $discountPercent, 
                'unitDiscount'           => $unitDiscount, 
                'lineTotalDiscount'      => $lineTotalDiscount, 
                'unitReceivable'         => $unitReceivable, 
                
                'discountPercent'        => $dtlDiscountPercent, 
                'discountAmount'         => $dtlUnitDiscount, 
                
                'isDelivery'             => $isDelivery,  
                'employeeId'             => $employeeId, 
                'discountEmployeeId'     => $discountEmployeeId, 
                'discountTypeId'         => $discountTypeId, 
                'description'            => $discountDescription, 
                'serialNumber'           => $serialNumber, 
                'itemKeyId'              => $itemKeyId, 
                'isRemoved'              => 0, 
                
                'itemCode'               => $itemCode, 
                'itemName'               => $printItemName, 
                'barCode'                => $barCode, 
                'couponKeyId'            => $dtlCouponKeyId,
                'sectionId'            => $sectionId
            );
            
            $sumTotal += $lineTotalAmount;
            
            if ($lineTotalAmount > 0) {
                
                $itemName    = self::apiStringReplace($printItemName);
                $measureCode = self::convertCyrillicMongolia($itemData['measureCode'][$k]);
                
                $stocks .= "{
                    'code': '" . $itemCode . "',
                    'name': '" . $itemName . "',
                    'measureUnit': '" . $measureCode . "',
                    'qty': '" . sprintf("%.2f", $itemQty) . "',
                    'unitPrice': '" . sprintf("%.2f", $unitAmount) . "',
                    'totalAmount': '" . sprintf("%.2f", $lineTotalAmount) . "',
                    'cityTax': '" . sprintf("%.2f", $lineTotalCityTaxAmount) . "',
                    'vat': '" . sprintf("%.2f", $lineTotalVat) . "',
                    'barCode': '" . $barCode . "'
                }, ";  
            }
            
            $row = array(
                'cityTax'        => $cityTax, 
                'itemName'       => $printItemName, 
                'salePrice'      => $printUnitAmount, 
                'itemQty'        => $itemQty, 
                'totalPrice'     => $printLineTotalAmount, 
                'unitReceivable' => $unitReceivable, 
                'maxPrice'       => $maxPrice, 
                'isDelivery'     => $isDelivery
            );
            $itemPrintList .= self::{$itemPrintRenderFncName}($row);
            
            $totalItemCount += $itemQty;
            $giftJsonStr    = trim($itemData['giftJson'][$k]);
            
            if ($giftJsonStr != '') {
                
                $itemPackageList = $itemGiftList = array();
                $giftJsonArray = json_decode(html_entity_decode($giftJsonStr, ENT_NOQUOTES, 'UTF-8'), true);
                
                foreach ($giftJsonArray as $giftJsonRow) {
                    
                    $giftJsonRow['isDelivery'] = isset($giftJsonRow['isDelivery']) ? $giftJsonRow['isDelivery'] : 0;
                    $giftJsonRow['invoiceqty'] = Number::decimal($itemQty);
                    $giftJsonRowMerge          = array();
                    
                    $itemPackageList[] = array(
                        'packageDtlId'      => $giftJsonRow['packagedtlid'],
                        'qty'               => $itemQty, 
                        'discountPolicyId'  => $giftJsonRow['policyid'], 
                        'isDelivery'        => $giftJsonRow['isDelivery']
                    );
                    
                    if ($giftJsonRow['coupontypeid'] == 1 || $giftJsonRow['coupontypeid'] == 5 || $giftJsonRow['coupontypeid'] == 6) {
                        
                        for ($ii = 0; $ii < $giftJsonRow['invoiceqty']; $ii++) {
                            array_push($voucherDtl, array(
                                'typeId'    => $giftJsonRow['coupontypeid'], 
                                'amount'    => $giftJsonRow['couponamount'], 
                                'percent'   => issetParam($giftJsonRow['couponpercent']), 
                                'percentamount' => issetParam($giftJsonRow['couponpercentamount']), 
                                'name'          => $giftJsonRow['coupontypename'], 
                                'rowIndex'      => $k
                            ));
                        }                        
                    }
                    
                    $totalItemCount += $itemQty;
                    
                    if ($giftJsonRow['coupontypeid'] == '') {
                        
                        $giftJsonRow['isgift']     = 1;
                        $giftJsonRow['employeeId'] = $employeeId; 
                        $giftJsonRow['itemid']     = $giftJsonRow['promotionitemid']; 
                        $giftJsonRow['jobid']      = $giftJsonRow['jobid']; 
                        
                        if ($giftJsonRow['isservice'] == 1 && $giftJsonRow['jobid'] != '') {
                            
                            $serviceDtl[] = array(
                                'jobId' => $giftJsonRow['jobid'], 
                                'RPR_KEY' => array(
                                    'customerId' => $headerParams['customerId'], 
                                    'jobid' => $giftJsonRow['jobid'], 
                                    'purchaseStoreId' => $storeId
                                )
                            );
                        }
                        
                        $itemGiftPrice = $giftJsonRow['saleprice'];
                        
                        if ($itemGiftPrice > 0 && ($giftJsonRow['discountamount'] > 0 || $giftJsonRow['discountpercent'] > 0)) {
                            
                            $giftDiscountAmount = $itemGiftPrice;
                                
                            if ($giftJsonRow['discountamount'] > 0) {

                                $giftDiscountAmount = $giftJsonRow['discountamount'];

                            } elseif ($giftJsonRow['discountpercent'] > 0) {

                                $giftDiscount = ($giftJsonRow['discountpercent'] / 100) * $itemGiftPrice;
                                $giftDiscountAmount = $itemGiftPrice - $giftDiscount;
                            }
                                                    
                            $itemGiftPrice       = $itemGiftPrice - $giftDiscountAmount;
                            $giftLineTotalAmount = $itemGiftPrice * $itemQty;
                            $giftLineTotalVat    = number_format(($giftLineTotalAmount - ($giftLineTotalAmount / 1.1)), 2, '.', '');
                            
                            if ($totalBonusAmount > 0) {
                
                                $giftUnitDiscount  = ($dtlBonusPercent / 100) * $itemGiftPrice;
                                $giftDtlDiscountAmount = $itemGiftPrice - $giftUnitDiscount;

                                $itemGiftPrice   = $giftDtlDiscountAmount;
                                $giftNoVatPrice  = number_format($giftDtlDiscountAmount / 1.1, 2, '.', '');
                                $giftUnitVat     = number_format($giftDtlDiscountAmount - $giftNoVatPrice, 2, '.', '');

                                $giftLineTotalDiscount = $giftUnitDiscount * $itemQty;
                                $giftLineTotalAmount = $giftDtlDiscountAmount * $itemQty;

                                $giftLineTotalVat = $giftLineTotalAmount - $giftLineTotalAmount / 1.1;
                                
                            } else {
                                $giftLineTotalDiscount = 0;
                                $giftUnitVat = number_format($itemGiftPrice - (number_format($itemGiftPrice / 1.1, 2, '.', '')), 2, '.', '');
                            }
                            
                            if ($isVatCalc == false) {
                                $itemGiftPrice = $itemGiftPrice - $giftUnitVat;
                                $giftLineTotalAmount = $giftLineTotalAmount - $giftLineTotalVat;

                                $giftUnitVat = 0;
                                $giftLineTotalVat = 0;
                            }
            
                            $stocks .= "{
                                'code': '" . self::apiStringReplace($giftJsonRow['itemcode']) . "',
                                'name': '" . self::apiStringReplace($giftJsonRow['itemname']) . "',
                                'measureUnit': '" . self::convertCyrillicMongolia('ш') . "',
                                'qty': '" . sprintf("%.2f", $itemQty) . "',
                                'unitPrice': '" . sprintf("%.2f", $itemGiftPrice) . "',
                                'totalAmount': '" . sprintf("%.2f", $giftLineTotalAmount) . "',
                                'cityTax': '0.00',
                                'vat': '" . sprintf("%.2f", $giftLineTotalVat) . "',
                                'barCode': '" . $giftJsonRow['barcode'] . "'
                            }, ";  
                            
                            $row = array(
                                'cityTax'        => '', 
                                'itemName'       => $giftJsonRow['itemname'], 
                                'salePrice'      => $itemGiftPrice, 
                                'itemQty'        => $itemQty, 
                                'totalPrice'     => $giftLineTotalAmount, 
                                'unitReceivable' => 0, 
                                'maxPrice'       => 0, 
                                'isDelivery'     => $giftJsonRow['isDelivery']
                            );
                            $itemPrintList .= self::{$itemPrintRenderFncName}($row);
                            
                            $giftJsonRow['saleprice'] = $itemGiftPrice;
                            $giftJsonRow['unitPrice'] = $itemGiftPrice;
                            $giftJsonRow['lineTotalPrice'] = $giftLineTotalAmount;
                            
                            $giftJsonRow['unitAmount'] = $itemGiftPrice;
                            $giftJsonRow['lineTotalAmount'] = $giftLineTotalAmount;
                
                            $giftJsonRow['percentVat'] = 10;
                            $giftJsonRow['unitVat'] = $giftUnitVat;
                            $giftJsonRow['lineTotalVat'] = number_format($giftJsonRow['unitVat'] * $itemQty, 2, '.', '');
                            
                            $giftJsonRow['percentDiscount'] = 0;
                            $giftJsonRow['unitDiscount'] = 0;
                            $giftJsonRow['lineTotalDiscount'] = $giftLineTotalDiscount;
                            
                            $giftJsonRowMerge['invoiceqty'] = 1;
                            $giftJsonRowMerge['unitPrice'] = $itemGiftPrice;
                            $giftJsonRowMerge['lineTotalPrice'] = $itemGiftPrice;
                            
                            $giftJsonRowMerge['unitAmount'] = $itemGiftPrice;
                            $giftJsonRowMerge['lineTotalAmount'] = $itemGiftPrice;
                
                            $giftJsonRowMerge['percentVat'] = 10;
                            $giftJsonRowMerge['unitVat'] = number_format($itemGiftPrice - (number_format($itemGiftPrice / 1.1, 2, '.', '')), 2, '.', '');
                            $giftJsonRowMerge['lineTotalVat'] = $giftJsonRowMerge['unitVat'];
                            
                            $sumTotal += $giftLineTotalAmount;
                            
                        } else {
                            $giftList .= self::giftPrintRow($giftJsonRow);
                        }
                        
                        if ($giftJsonRow['jobid'] == '') {
                            
                            if ($itemQty > 1) {
                                
                                $giftJsonRowMerge['invoiceqty'] = 1;
                                
                                if ($giftJsonRow['isDelivery'] == 1 && $isServicePos != '1') {
                                    
                                    $giftJsonRow['warehouseId'] = $deliveryWarehouseId; 
                                    $giftJsonRowLoop = array_merge($giftJsonRow, $giftJsonRowMerge);
                                    
                                    for ($gt = 0; $gt < $itemQty; $gt++) {
                                        $deliveryDtl[] = $giftJsonRowLoop;
                                    }
                                    
                                } else {
                                    $giftJsonRow['warehouseId'] = $storeWarehouseId; 
                                    $giftJsonRowLoop = array_merge($giftJsonRow, $giftJsonRowMerge);
                                    
                                    for ($gt = 0; $gt < $itemQty; $gt++) {
                                        $noDeliveryDtl[] = $giftJsonRowLoop;
                                    }
                                }
                                
                            } else {
                                if ($giftJsonRow['isDelivery'] == 1 && $isServicePos != '1') {
                                    $giftJsonRow['warehouseId'] = $deliveryWarehouseId; 
                                    $deliveryDtl[] = $giftJsonRow;
                                } else {
                                    $giftJsonRow['warehouseId'] = $storeWarehouseId; 
                                    $noDeliveryDtl[] = $giftJsonRow;
                                }
                            }
                        }
                        
                        $itemGiftList[] = $giftJsonRow;
                        
                    } else {
                        $giftList .= self::giftPrintRow($giftJsonRow);
                    }
                }
                
                $paramsDtl[$k]['SDM_SALES_ORDER_ITEM_PACKAGE'] = $itemPackageList;
                $paramsDtl[$k]['POS_SM_SALES_INVOICE_DETAIL'] = $itemGiftList;
            }
            
            if ($isJob == '0' || $isJob == '') {
                
                if ($isDelivery == 1) {

                    $paramsDtl[$k]['warehouseId'] = $deliveryWarehouseId;
                    $deliveryDtl[] = $paramsDtl[$k];

                } else {
                    $paramsDtl[$k]['warehouseId'] = $storeWarehouseId;
                    $noDeliveryDtl[] = $paramsDtl[$k];
                }
            }
            
            if ($itemPrintCopies && $itemPrintCopies > $itemPrintCopiesLast) {
                $itemPrintCopiesLast = $itemPrintCopies;
            }
        }        
        
        $params['POS_SM_SALES_INVOICE_DETAIL'] = $paramsDtl;
        
        if ($cityTaxAmount > 0) {
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0082'), self::posAmount($cityTaxAmount)), $paymentDtlTemplate);
        }
        
        if ($cashAmount > 0) {
            
            $saveCashAmt = $cashAmount - $changeAmount;
            
            $paymentDtl[] = array(
                'paymentTypeId' => 1, 
                'amount'        => $saveCashAmt
            );
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0083'), self::posAmount($cashAmount)), $paymentDtlTemplate);
            
            $generateVaucherAmt += $saveCashAmt;
        }
        
        if ($bankAmount > 0) {
            
            $bankAmountDtl = $paymentData['bankAmountDtl'];
            
            foreach ($bankAmountDtl as $b => $bankDtlAmount) {
                
                $bankId         = $paymentData['posBankIdDtl'][$b];
                $bankDtlAmount  = Number::decimal($bankDtlAmount);
                        
                if ($bankId != '' && $bankDtlAmount > 0) {
                    
                    $paymentDtl[] = array(
                        'paymentTypeId' => 2, 
                        'bankId'        => $bankId,
                        'amount'        => $bankDtlAmount
                    );
                    
                    $isBankCardPaid = true;
                }
            }
            
            $nonCashAmount += $bankAmount;
            $generateVaucherAmt += $bankAmount;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0084'), self::posAmount($bankAmount)), $paymentDtlTemplate);
        }
        
        if ($itemDiscountAmount > 0) {
            $discountDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0085'), self::posAmount($itemDiscountAmount)), $paymentDtlTemplate);
        }
        
        if ($voucherAmount > 0) {
            
            $voucherDtlAmount = $paymentData['voucherDtlAmount'];
            
            foreach ($voucherDtlAmount as $v => $voucherAmountDtl) {
                
                $voucherId           = $paymentData['voucherDtlId'][$v];
                $voucherTypeId       = $paymentData['voucherTypeId'][$v];
                $voucherAmountDtl    = Number::decimal($voucherAmountDtl);
                $voucherSerialNumber = $paymentData['voucherDtlSerialNumber'][$v];
                
                if ($voucherId != '' && $voucherAmountDtl > 0 && $voucherSerialNumber != '') {
                    
                    $voucherUsedDtl[] = array(
                        'id' => $voucherId
                    );
                    
                    if ($voucherTypeId == 1) {
                        $voucherPaymentTypeId = 10;
                        $voucherTypeName = Lang::line('POS_0086');
                    } elseif ($voucherTypeId == 2) {
                        $voucherPaymentTypeId = 9;
                        $voucherTypeName = Lang::line('POS_0087');
                    } elseif ($voucherTypeId == 4) {
                        $voucherPaymentTypeId = 11;
                        $voucherTypeName = Lang::line('POS_0044');
                    } else {
                        $voucherPaymentTypeId = 10;
                        $voucherTypeName = Lang::line('POS_0088');
                    }
                    
                    $paymentDtl[] = array(
                        'paymentTypeId' => $voucherPaymentTypeId, 
                        'amount'        => $voucherAmountDtl
                    );
                    
                    $discountDetail .= str_replace(array('{labelName}', '{amount}'), array($voucherTypeName, self::posAmount($voucherAmountDtl)), $paymentDtlTemplate);
                }
            }
            
            //$nonCashAmount += $voucherAmount;
        }
        
        $bonusCardNumber                = '00000000';
        $bonusCardDiscountPercent       = '0';
        $bonusCardBeginAmount           = '0';
        $bonusCardDiscountPercentAmount = '0';
        $bonusCardEndAmount             = '0';
        
        if ($accountTransferAmt > 0) {
            
            $isInvInfo = true;
            
            if (isset($paymentData['accountTransferAmountDtl'])) {
                
                $accountTransferAmountDtl = $paymentData['accountTransferAmountDtl'];
            
                foreach ($accountTransferAmountDtl as $t => $accountTransferDtlAmount) {

                    $accountTransferBankId    = $paymentData['accountTransferBankIdDtl'][$t];
                    $accountTransferDtlAmount = Number::decimal($accountTransferDtlAmount);

                    if ($accountTransferDtlAmount > 0 && $accountTransferBankId != '') {

                        $paymentDtl[] = array(
                            'paymentTypeId' => 4, 
                            'bankId'        => $accountTransferBankId, 
                            'amount'        => $accountTransferDtlAmount, 
                            'bankBillingId' => $paymentData['accountTransferBillingIdDtl'][$t], 
                            'description'   => $paymentData['accountTransferDescrDtl'][$t]
                        );
                        
                        if (isset($paymentData['accountTransferBillingIdDtl'][$t]) && $paymentData['accountTransferBillingIdDtl'][$t]) {
                            $atBankBillingIds[] = $paymentData['accountTransferBillingIdDtl'][$t];
                        }
                    }
                }
                
            } else {
                $paymentDtl[] = array(
                    'paymentTypeId' => 4, 
                    'bankId'        => $paymentData['posAccountTransferBankId'],
                    'amount'        => $accountTransferAmt
                );
            }
            
            $nonCashAmount += $accountTransferAmt;
            $generateVaucherAmt += $accountTransferAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0089'), self::posAmount($accountTransferAmt)), $paymentDtlTemplate);
        }
        
        if ($mobileNetAmt > 0) {
            
            $isInvInfo = true;
            
            $paymentDtl[] = array(
                'paymentTypeId' => 3, 
                'bankId'        => $paymentData['posMobileNetBankId'],
                'amount'        => $mobileNetAmt
            );
            
            $nonCashAmount += $mobileNetAmt;
            $generateVaucherAmt += $mobileNetAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0090'), self::posAmount($mobileNetAmt)), $paymentDtlTemplate);
        }
        
        if ($barterAmt > 0) {
            
            $isInvInfo = true;
            
            $paymentDtl[] = array(
                'paymentTypeId' => 5, 
                'amount'        => $barterAmt
            );
            
            $nonCashAmount += $barterAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0091'), self::posAmount($barterAmt)), $paymentDtlTemplate);
        }
        
        if ($leasingAmt > 0) {
            
            $isInvInfo = true;
            
            $paymentDtl[] = array(
                'paymentTypeId' => 6, 
                'bankId'        => $paymentData['posLeasingBankId'],
                'amount'        => $leasingAmt
            );
            
            $nonCashAmount += $leasingAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0092'), self::posAmount($leasingAmt)), $paymentDtlTemplate);
        }
        
        if ($empLoanAmt > 0) {
            
            $isInvInfo = true;
            
            $paymentDtl[] = array(
                'paymentTypeId' => 7, 
                'amount'        => $empLoanAmt
            );
            
            $nonCashAmount += $empLoanAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0093'), self::posAmount($empLoanAmt)), $paymentDtlTemplate);
        }
        
        if ($localExpenseAmt > 0) {
            
            $isPut = false;
            
            $params['localExpenseType']   = 1001;
            $params['localExpenseAmount'] = $localExpenseAmt;
            
            $paymentDtl[] = array(
                'paymentTypeId' => 13, 
                'amount'        => $localExpenseAmt
            );
            
            $nonCashAmount += $localExpenseAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0094'), self::posAmount($localExpenseAmt)), $paymentDtlTemplate);
            
            $params = self::setZeroVatAmount($params);
        }
        
        if ($emdAmount > 0) {
            
            $paymentDtl[] = array(
                'paymentTypeId' => 14, 
                'amount'        => $emdAmount
            );
            
            $nonCashAmount += $emdAmount;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0095'), self::posAmount($emdAmount)), $paymentDtlTemplate);
        }
        
        if ($deliveryAmount > 0) {
            
            $paymentDtl[] = array(
                'paymentTypeId' => 17, 
                'amount'        => $deliveryAmount
            );
            
            $nonCashAmount += $deliveryAmount;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array('Үлдэгдэл төлбөр', self::posAmount($deliveryAmount)), $paymentDtlTemplate);
        }
        
        if ($candyAmount > 0) {
            
            $candyAmountDtl = $paymentData['candyAmountDtl'];
            
            foreach ($candyAmountDtl as $c => $candyDtlAmount) {
                
                $candyTypeCode  = $paymentData['candyTypeCodeDtl'][$c];
                $candyNumber    = $paymentData['candyDetectedNumberDtl'][$c];
                $candyTransactionId = $paymentData['candyTransactionIdDtl'][$c];
                $candyDtlAmount     = Number::decimal($candyDtlAmount);
                        
                if ($candyTypeCode && $candyNumber && $candyTransactionId && $candyDtlAmount > 0) {
                    
                    $paymentDtl[] = array(
                        'paymentTypeId' => 15, 
                        'amount'        => $candyDtlAmount, 
                        'candyTypeCode' => $candyTypeCode,
                        'candyNumber'   => $candyNumber,
                        'candyTransactionId' => $candyTransactionId,
                    );
                    
                    if ($candyTypeCode == 'qrcodegenerate' && $candyTypeCode == 'qrcoderead') {
                        $candyLabelName = 'QR код';
                    } else {
                        $candyLabelName = $candyNumber;
                    }
                    
                    $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array('Monpay', self::posAmount($candyDtlAmount)), $paymentDtlTemplate);
                }
            }
            
            $nonCashAmount += $candyAmount;
        }
        
        if ($lendMnAmount > 0) {
            
            $paymentDtl[] = array(
                'paymentTypeId' => 18, 
                'amount'        => $lendMnAmount
            );
            
            $nonCashAmount += $lendMnAmount;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array('LendMN', self::posAmount($lendMnAmount)), $paymentDtlTemplate);
        }
        
        if ($changeAmount > 0) {
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0096'), self::posAmount($changeAmount)), $paymentDtlTemplate);
        }
        
        if ($paymentData['invoiceId'] != '') {
            $talonInvoiceId = $paymentData['invoiceId'];
            $params['META_DM_RECORD_MAP']['trgRecordId'] = $talonInvoiceId;
        }
        
        //if (isset($isInvInfo)) {
        if (array_key_exists('invInfoInvoiceNumber', $paymentData)) {
            
            $params['refInvoiceNumber']    = Input::param($paymentData['invInfoInvoiceNumber']);
            $params['refBookNumber']       = Input::param($paymentData['invInfoBookNumber']);
            $params['customerName']        = Input::param($paymentData['invInfoCustomerName']);
            $params['customerLastName']    = Input::param($paymentData['invInfoCustomerLastName']);
            $params['customerRegNumber']   = Input::param($paymentData['invInfoCustomerRegNumber']);
            $params['phoneNumber']         = Input::param($paymentData['invInfoPhoneNumber']);
            $params['description']         = Input::param($paymentData['invInfoTransactionValue']);
            $params['customerBankId']      = issetVar($paymentData['customerBankId']);
            $params['customerBankAccount'] = issetVar($paymentData['customerBankAccount']);
        }
        
        $params['SM_SALES_PAYMENT_DV'] = $paymentDtl;
        
        if ($sumTotal > 0 && $vatAmount > 0) {
            
            $totalAmount = $sumTotal;
            $vatAmount   = $totalAmount - number_format($totalAmount / 1.1, 2, '.', '');
                    
            $params['total'] = $totalAmount;
            $params['vat']   = $vatAmount;
        }

        $lockerCode = '';
        if (isset($_POST['lockerId'])) {
            $lockInfo = explode('_', $_POST['lockerId']);
            $params['lockerId'] = $lockInfo[0];
            $lockerCode = $lockInfo[1];
        }        

        if ($isNotSendVatsp == false) {

            if ($posBillType == 'person') {

                $billType   = 1;
                $title      = Lang::line('POS_0100');

            } elseif ($posBillType == 'organization') {

                $billType       = 3;
                $title          = Lang::line('POS_0101');

                $orgRegNumber   = Str::upper(Input::param($paymentData['orgRegNumber']));
                $customerNo     = self::convertCyrillicMongolia($orgRegNumber); //'0000039';
                $orgName        = $paymentData['orgName'];
                $taxType        = $isVatCalc ? 1 : 2;

            } else {
                $billType       = 5;
                $title          = Lang::line('POS_0102');
            }

            if (Config::getFromCache('CONFIG_POS_HEALTHRECIPE')) {

                $isReceiptNumber = Input::post('isReceiptNumber');

                if ($isReceiptNumber == 'true' && isset($_POST['drugPrescription'])) {

                    $prescriptionArr = $_POST['drugPrescription'];
                    $billIdSuffix = $prescriptionArr['receiptNumber'];
                }
            }

            $posApiCashAmount = $cashAmount - $changeAmount + $totalBonusAmount;
            $posApiNonCashAmount = $nonCashAmount - $totalBonusAmount;

            if ($posApiNonCashAmount < 0) {
                $posApiCashAmount = $posApiCashAmount - ($posApiNonCashAmount * -1);
                $posApiNonCashAmount = 0;
            }

            if ($posApiCashAmount < 0) {
                $posApiCashAmount = 0;
                $posApiNonCashAmount = $posApiNonCashAmount - ($posApiCashAmount * -1);
            }

            if ($totalAmount < $posApiNonCashAmount) {
                $posApiNonCashAmount = $posApiNonCashAmount - $changeAmount;
            }

            $jsonParam = "{
                'amount': '" . sprintf("%.2f", $totalAmount) . "',
                'vat': '" . sprintf("%.2f", $vatAmount) . "',
                'cashAmount': '" . sprintf("%.2f", $posApiCashAmount) . "',
                'nonCashAmount': '" . sprintf("%.2f", $posApiNonCashAmount) . "',
                'cityTax': '" . sprintf("%.2f", $cityTaxAmount) . "',
                'districtCode': '" . $districtCode . "',
                'posNo': '" . $cashCode . "',
                'reportMonth': '" . $reportMonth . "',
                'customerNo': '" . $customerNo . "',
                'billType': '" . $billType . "',
                'taxType': '" . $taxType . "',
                'billIdSuffix': '" . $billIdSuffix . "',
                'returnBillId': '',
                'stocks': [
                    " . rtrim($stocks, ', ') . "
                ]
            }";

            $jsonParam = Str::remove_doublewhitespace(Str::removeNL($jsonParam));

            $posApiArray = self::posApiFunction($jsonParam);
            $billId      = isset($posApiArray['billId']) ? $posApiArray['billId'] : null;

        } else {

            $billId = '1';
            $posApiArray = array('date' => null);
        }        
    }
    
    private function ancientUpdateBill() {
        parse_str($_POST['paymentData'], $paymentData);
        
        $sPrefix        = SESSION_PREFIX;
        
        $storeId        = Session::get($sPrefix.'storeId');
        $cashierId      = Session::get($sPrefix.'cashierId');
        $cashRegisterId = Session::get($sPrefix.'cashRegisterId');
        $isServicePos   = Session::get($sPrefix.'posIsService');
        
        $refNumber      = self::getPosInvoiceRefNumber($storeId, $cashRegisterId);
        $invoiceNumber  = self::getBillNumModel();
        $currentDate    = Date::currentDate('Y-m-d H:i:s');
        
        parse_str($_POST['itemData'], $itemData);
        
        $vatAmount              = Number::decimal($paymentData['vatAmount']);
        $cityTaxAmount          = Number::decimal($paymentData['cityTaxAmount']);
        $discountAmount         = Number::decimal($paymentData['discountAmount']);
        $itemDiscountAmount     = $discountAmount;
        
        $totalAmount            = Number::decimal($paymentData['payAmount']);
        $cashAmount             = Number::decimal($paymentData['cashAmount']);
        $bankAmount             = Number::decimal($paymentData['bankAmount']);
        $voucherAmount          = Number::decimal($paymentData['voucherAmount']);
        
        $bonusCardAmount        = Number::decimal($paymentData['bonusCardAmount']);
        
        $totalBonusAmount       = $voucherAmount + $bonusCardAmount;
        $subTotal               = $totalAmount + $discountAmount;
        $printTotalAmount       = ($discountAmount < 0) ? $totalAmount : $subTotal;        
        $accountTransferAmt     = Number::decimal($paymentData['posAccountTransferAmt']);
        $mobileNetAmt           = Number::decimal($paymentData['posMobileNetAmt']);
        $barterAmt              = Number::decimal($paymentData['posBarterAmt']);
        $leasingAmt             = Number::decimal($paymentData['posLeasingAmt']);
        $empLoanAmt             = Number::decimal($paymentData['posEmpLoanAmt']);
        $localExpenseAmt        = Number::decimal($paymentData['posLocalExpenseAmt']);
        $emdAmount              = Number::decimal($paymentData['posEmdAmt']);
        $candyAmount            = Number::decimal(issetParam($paymentData['posCandyAmt']));
        $deliveryAmount         = Number::decimal(issetParam($paymentData['posDeliveryAmt']));
        $lendMnAmount           = Number::decimal(issetParam($paymentData['posLendMnAmt']));
        
        $changeAmount           = $paymentData['changeAmount'];
        $changeAmount           = ($changeAmount ? Number::decimal($changeAmount) : 0);
        
        $nonCashAmount          = 0;
        $totalItemCount         = 0;
        $generateVaucherAmt     = 0;
        $isPut                  = true;
        $isVatCalc              = true;
        $posBillType            = $paymentData['posBillType'];
        
        if ($totalAmount == $lendMnAmount 
            && $storeId != '1515722911251' && $storeId != '1515722911369' && $storeId != '1515722911402' 
            && $storeId != '1515722911434' && $storeId != '1515722911503') {
            
            $totalBonusAmount = number_format((10 / 100) * $lendMnAmount, 2, '.', '');
            $lendMnAmount = $lendMnAmount - $totalBonusAmount;
        }
        
        if ($totalBonusAmount > 0) {
            
            if ($totalBonusAmount > $totalAmount) {
                
                $totalBonusAmount = 0;
                $discountAmount   = $totalAmount;
                $isPut            = false;
                $billTitle        = Lang::line('POS_0080');
                
            } else {
                $dtlBonusPercent = $totalBonusAmount / $totalAmount * 100;
                $discountAmount  = $discountAmount + $totalBonusAmount;  
                $totalAmount     = $subTotal - $discountAmount;
                $vatAmount       = $totalAmount - number_format($totalAmount / 1.1, 2, '.', '');
            }
        }
        
        if ($posBillType == 'organization') {
            $orgVatPayer = isset($paymentData['orgVatPayer']) ? $paymentData['orgVatPayer'] : '';
            
            if ($orgVatPayer == 'false') {
                $isVatCalc = false;
                $vatAmount = 0;
            }
        }
        
        $params = array(
            'bookTypeId'        => 9, 
            'invoiceNumber'     => $invoiceNumber, 
            'refNumber'         => $refNumber, 
            'invoiceDate'       => $currentDate, 
            'createdDateTime'   => $currentDate, 
            'storeId'           => $storeId,
            'cashRegisterId'    => $cashRegisterId,
            'createdCashierId'  => $cashierId, 
            'totalCityTaxAmount'=> $cityTaxAmount, 
            'subTotal'          => $subTotal, 
            'discount'          => $discountAmount, 
            'vat'               => $vatAmount, 
            'total'             => $totalAmount, 
            'changeAmount'      => $changeAmount, 
            'wfmStatusId'       => '1505964291977811', 
            'customerId'        => ''
        );
        
        $serviceCustomerId = null;
        
        if ($paymentData['serviceCustomerId'] != '') {
            
            $params['customerId'] = $paymentData['serviceCustomerId'];
            
        } elseif ($paymentData['newServiceCustomerJson'] != '') {
            
            $serviceCustomerResult = self::createServiceCustomer($paymentData['newServiceCustomerJson']);
                
            if ($serviceCustomerResult['status'] != 'success') {
                
                $response = array('status' => 'warning', 'message' => Lang::line('POS_0081') . ' (CRM). '.$serviceCustomerResult['message']);
                return $response;
                
            } elseif (isset($serviceCustomerResult['customerId'])) {
                
                $serviceCustomerId    = $serviceCustomerResult['customerId'];
                $params['customerId'] = $serviceCustomerResult['customerId'];
            }
        } 
        
        if (Input::isEmpty('empCustomerId') == false) {
            $params['customerId'] = Input::post('empCustomerId');
        }
        
        $headerParams = $params;
        
        $paramsDtl = $paymentDtl = $voucherDtl = $voucherUsedDtl = $deliveryDtl = $noDeliveryDtl = $serviceDtl = $couponKeyDtl = $atBankBillingIds = array();
        $itemPrintList = $stocks = $giftList = $paymentDetail = $discountDetail = $talonInvoiceId = '';
        
        $paymentDtlTemplate = self::paymentDetailTemplate();
        
        if (Config::getFromCache('CONFIG_POS_HEALTHRECIPE')) {
            $itemPrintRenderFncName = 'generatePharmacyItemRow';
        } else {
            $itemPrintRenderFncName = 'generateItemRow';
        }
        
        $sumTotal = $itemPrintCopiesLast = 0;
        $itemIds = $itemData['itemId'];
        
        foreach ($itemIds as $k => $itemId) {
            
            $itemQty        = Number::decimal($itemData['quantity'][$k]);
            $salePrice      = $itemData['salePrice'][$k];
            $totalPrice     = $itemData['totalPrice'][$k];
            $unitAmount     = $salePrice;
            $lineTotalAmount= $totalPrice;
            
            $isVat          = $itemData['isVat'][$k];
            $vatPercent     = $itemData['vatPercent'][$k];
            $noVatPrice     = $itemData['noVatPrice'][$k];
            
            $isDiscount         = $itemData['isDiscount'][$k];
            $discountPercent    = $itemData['discountPercent'][$k];
            $dtlDiscountPercent = $discountPercent;
            $dtlDiscountAmount  = $itemData['discountAmount'][$k];
            $unitReceivable     = $itemData['unitReceivable'][$k];
            $unitReceivable     = $unitReceivable ? number_format($unitReceivable * $itemQty, 2, '.', '') : '';
            $maxPrice           = $itemData['maxPrice'][$k];
            $unitDiscount       = 0;
            $dtlUnitDiscount    = 0;
            $lineTotalDiscount  = 0;
            
            $isDelivery     = $itemData['isDelivery'][$k];
            $employeeId     = $itemData['employeeId'][$k];
            $sectionId     = isset($itemData['sectionId']) ? $itemData['sectionId'][$k] : '';
            
            $discountEmployeeId   = $itemData['discountEmployeeId'][$k];
            $discountTypeId       = $itemData['discountTypeId'][$k];
            $discountDescription  = $itemData['discountDescription'][$k];
            $storeWarehouseId     = $itemData['storeWarehouseId'][$k];
            $deliveryWarehouseId  = $itemData['deliveryWarehouseId'][$k];
            $itemPrintCopies      = issetParam($itemData['printCopies'][$k]);
            
            $printUnitAmount      = $unitAmount;
            $printLineTotalAmount = $lineTotalAmount;
            
            $isCityTax      = $itemData['isCityTax'][$k];
            $cityTax        = ($isCityTax == '1' ? $itemData['cityTax'][$k] : 0);
            $lineTotalCityTaxAmount = 0;
            
            if ($isVat == '1' && $isDiscount != '1') {
                
                if ($isCityTax == '1') {
                    $unitVat = number_format($salePrice - $noVatPrice - $cityTax, 2, '.', '');
                    $lineTotalCityTaxAmount = number_format($cityTax * $itemQty, 2, '.', '');
                } else {
                    $unitVat = number_format($salePrice - $noVatPrice, 2, '.', '');
                }
                
                $lineTotalVat = number_format($unitVat * $itemQty, 2, '.', '');
                
            } elseif ($isVat == '1' && $isDiscount == '1') {
                
                $unitDiscount = $itemData['unitDiscount'][$k];
                $unitAmount   = $dtlDiscountAmount;
                $noVatPrice   = number_format($dtlDiscountAmount / 1.1, 2, '.', '');
                
                if ($isCityTax == '1') {
                    $unitVat = number_format($dtlDiscountAmount - $noVatPrice - $cityTax, 2, '.', '');
                    $lineTotalCityTaxAmount = number_format($cityTax * $itemQty, 2, '.', '');
                } else {
                    $unitVat = number_format($dtlDiscountAmount - $noVatPrice, 2, '.', '');
                }
                
                $lineTotalVat = number_format($unitVat * $itemQty, 2, '.', '');
                $lineTotalAmount = $itemData['totalDiscount'][$k];
                $dtlUnitDiscount = $unitDiscount;
                
                if ($unitDiscount > 0) {
                    $lineTotalDiscount = $unitDiscount * $itemQty;
                } else {
                    $unitDiscount      = 0;
                    $discountPercent   = 0;
                    $lineTotalDiscount = 0;
                    
                    $printUnitAmount      = $dtlDiscountAmount;
                    $printLineTotalAmount = $lineTotalAmount;
                }
                
            } else {
                
                if ($isDiscount == '1') {
                    
                    $unitVat        = 0;
                    $lineTotalVat   = 0;
                    $unitAmount     = $dtlDiscountAmount;
                    
                    $unitDiscount      = $itemData['unitDiscount'][$k];
                    $lineTotalDiscount = $unitDiscount * $itemQty;
                    $lineTotalAmount   = $itemData['totalDiscount'][$k];
                    $dtlUnitDiscount   = $unitDiscount;
                    
                } else {
                    $unitVat = $lineTotalVat = 0;
                }
            }
            
            $itemCode       = $itemData['itemCode'][$k];
            $printItemName  = $itemData['itemName'][$k];
            $serialNumber   = $itemData['serialNumber'][$k];
            $itemKeyId      = issetParam($itemData['itemKeyId'][$k]);
            $barCode        = $itemData['barCode'][$k];
            $barCode        = ($barCode ? $barCode : '132456789');
            
            $isJob          = $itemData['isJob'][$k];
            $jobId          = '';
            $dtlCouponKeyId = '';
            
            if ($isJob == '1') {
                
                $jobId  = $itemId;
                $itemId = '';
                
                if ($isDelivery == '1' && $isServicePos != '1') {
                    
                    $serviceDtl[] = array(
                        'jobId'   => $jobId, 
                        'RPR_KEY' => array(
                            'customerId'      => $headerParams['customerId'], 
                            'jobid'           => $jobId, 
                            'purchaseStoreId' => $storeId
                        )
                    );
                }
                
                $isDelivery = 0;
                
            } elseif ($isJob == '2') {
                
                $dtlCouponKeyId = $itemId;
                $jobId          = '';
                $itemId         = '';
                $isDelivery     = 0;
                
                $couponKeyDtl[] = array(
                    'couponKeyId' => $dtlCouponKeyId, 
                    'rowIndex'    => $k
                );
            }
            
            if ($totalBonusAmount > 0) {
                
                if ($totalAmount > 0) {
                    
                    $unitDiscount      = ($dtlBonusPercent / 100) * $unitAmount;
                    $dtlDiscountAmount = $unitAmount - $unitDiscount;

                    $unitAmount     = $dtlDiscountAmount;
                    $noVatPrice     = number_format($dtlDiscountAmount / 1.1, 2, '.', '');
                    $unitVat        = number_format($dtlDiscountAmount - $noVatPrice, 2, '.', '');

                    $lineTotalDiscount = $unitDiscount * $itemQty;
                    $lineTotalAmount   = $dtlDiscountAmount * $itemQty;

                    $lineTotalVat   = $lineTotalAmount - $lineTotalAmount / 1.1;
                    
                } else {
                                        
                    $unitVat           = 0;
                    $lineTotalVat      = 0;
                    $unitAmount        = 0;
                    $lineTotalAmount   = 0;
                    
                    $unitDiscount      = $salePrice;
                    $lineTotalDiscount = $unitDiscount * $itemQty;
                }
            }
            
            if ($isVatCalc == false) {
                $unitAmount = $unitAmount - $unitVat;
                $lineTotalAmount = $lineTotalAmount - $lineTotalVat;
                        
                $unitVat = 0;
                $lineTotalVat = 0;
                
                $printUnitAmount = $unitAmount;
                $printLineTotalAmount = $lineTotalAmount; 
            }
            
            $paramsDtl[$k] = array(
                'itemId'                 => $itemId, 
                'jobId'                  => $jobId, 
                'invoiceQty'             => Number::decimal($itemQty), 
                
                'unitPrice'              => $salePrice,
                'lineTotalPrice'         => $totalPrice,  
                'unitAmount'             => $unitAmount, 
                'lineTotalAmount'        => $lineTotalAmount,  
                'lineTotalCityTaxAmount' => $lineTotalCityTaxAmount,  
                
                'isVat'                  => $isVat, 
                'percentVat'             => $vatPercent, 
                'unitVat'                => $unitVat, 
                'lineTotalVat'           => $lineTotalVat,
                
                'percentDiscount'        => $discountPercent, 
                'unitDiscount'           => $unitDiscount, 
                'lineTotalDiscount'      => $lineTotalDiscount, 
                'unitReceivable'         => $unitReceivable, 
                
                'discountPercent'        => $dtlDiscountPercent, 
                'discountAmount'         => $dtlUnitDiscount, 
                
                'isDelivery'             => $isDelivery,  
                'employeeId'             => $employeeId, 
                'discountEmployeeId'     => $discountEmployeeId, 
                'discountTypeId'         => $discountTypeId, 
                'description'            => $discountDescription, 
                'serialNumber'           => $serialNumber, 
                'itemKeyId'              => $itemKeyId, 
                'isRemoved'              => 0, 
                
                'itemCode'               => $itemCode, 
                'itemName'               => $printItemName, 
                'barCode'                => $barCode, 
                'couponKeyId'            => $dtlCouponKeyId,
                'sectionId'            => $sectionId
            );
            
            $sumTotal += $lineTotalAmount;
            
            if ($lineTotalAmount > 0) {
                
                $itemName    = self::apiStringReplace($printItemName);
                $measureCode = self::convertCyrillicMongolia($itemData['measureCode'][$k]);
                
                $stocks .= "{
                    'code': '" . $itemCode . "',
                    'name': '" . $itemName . "',
                    'measureUnit': '" . $measureCode . "',
                    'qty': '" . sprintf("%.2f", $itemQty) . "',
                    'unitPrice': '" . sprintf("%.2f", $unitAmount) . "',
                    'totalAmount': '" . sprintf("%.2f", $lineTotalAmount) . "',
                    'cityTax': '" . sprintf("%.2f", $lineTotalCityTaxAmount) . "',
                    'vat': '" . sprintf("%.2f", $lineTotalVat) . "',
                    'barCode': '" . $barCode . "'
                }, ";  
            }
            
            $row = array(
                'cityTax'        => $cityTax, 
                'itemName'       => $printItemName, 
                'salePrice'      => $printUnitAmount, 
                'itemQty'        => $itemQty, 
                'totalPrice'     => $printLineTotalAmount, 
                'unitReceivable' => $unitReceivable, 
                'maxPrice'       => $maxPrice, 
                'isDelivery'     => $isDelivery
            );
            $itemPrintList .= self::{$itemPrintRenderFncName}($row);
            
            $totalItemCount += $itemQty;
            $giftJsonStr    = trim($itemData['giftJson'][$k]);
            
            if ($giftJsonStr != '') {
                
                $itemPackageList = $itemGiftList = array();
                $giftJsonArray = json_decode(html_entity_decode($giftJsonStr, ENT_NOQUOTES, 'UTF-8'), true);
                
                foreach ($giftJsonArray as $giftJsonRow) {
                    
                    $giftJsonRow['isDelivery'] = isset($giftJsonRow['isDelivery']) ? $giftJsonRow['isDelivery'] : 0;
                    $giftJsonRow['invoiceqty'] = Number::decimal($itemQty);
                    $giftJsonRowMerge          = array();
                    
                    $itemPackageList[] = array(
                        'packageDtlId'      => $giftJsonRow['packagedtlid'],
                        'qty'               => $itemQty, 
                        'discountPolicyId'  => $giftJsonRow['policyid'], 
                        'isDelivery'        => $giftJsonRow['isDelivery']
                    );
                    
                    if ($giftJsonRow['coupontypeid'] == 1 || $giftJsonRow['coupontypeid'] == 5 || $giftJsonRow['coupontypeid'] == 6) {
                        
                        $voucherDtl[] = array(
                            'typeId'    => $giftJsonRow['coupontypeid'], 
                            'amount'    => $giftJsonRow['couponamount'], 
                            'percent'   => issetParam($giftJsonRow['couponpercent']), 
                            'percentamount' => issetParam($giftJsonRow['couponpercentamount']), 
                            'name'          => $giftJsonRow['coupontypename'], 
                            'rowIndex'      => $k
                        );
                    }
                    
                    $totalItemCount += $itemQty;
                    
                    if ($giftJsonRow['coupontypeid'] == '') {
                        
                        $giftJsonRow['isgift']     = 1;
                        $giftJsonRow['employeeId'] = $employeeId; 
                        $giftJsonRow['itemid']     = $giftJsonRow['promotionitemid']; 
                        $giftJsonRow['jobid']      = $giftJsonRow['jobid']; 
                        
                        if ($giftJsonRow['isservice'] == 1 && $giftJsonRow['jobid'] != '') {
                            
                            $serviceDtl[] = array(
                                'jobId' => $giftJsonRow['jobid'], 
                                'RPR_KEY' => array(
                                    'customerId' => $headerParams['customerId'], 
                                    'jobid' => $giftJsonRow['jobid'], 
                                    'purchaseStoreId' => $storeId
                                )
                            );
                        }
                        
                        $itemGiftPrice = $giftJsonRow['saleprice'];
                        
                        if ($itemGiftPrice > 0 && ($giftJsonRow['discountamount'] > 0 || $giftJsonRow['discountpercent'] > 0)) {
                            
                            $giftDiscountAmount = $itemGiftPrice;
                                
                            if ($giftJsonRow['discountamount'] > 0) {

                                $giftDiscountAmount = $giftJsonRow['discountamount'];

                            } elseif ($giftJsonRow['discountpercent'] > 0) {

                                $giftDiscount = ($giftJsonRow['discountpercent'] / 100) * $itemGiftPrice;
                                $giftDiscountAmount = $itemGiftPrice - $giftDiscount;
                            }
                                                    
                            $itemGiftPrice       = $itemGiftPrice - $giftDiscountAmount;
                            $giftLineTotalAmount = $itemGiftPrice * $itemQty;
                            $giftLineTotalVat    = number_format(($giftLineTotalAmount - ($giftLineTotalAmount / 1.1)), 2, '.', '');
                            
                            if ($totalBonusAmount > 0) {
                
                                $giftUnitDiscount  = ($dtlBonusPercent / 100) * $itemGiftPrice;
                                $giftDtlDiscountAmount = $itemGiftPrice - $giftUnitDiscount;

                                $itemGiftPrice   = $giftDtlDiscountAmount;
                                $giftNoVatPrice  = number_format($giftDtlDiscountAmount / 1.1, 2, '.', '');
                                $giftUnitVat     = number_format($giftDtlDiscountAmount - $giftNoVatPrice, 2, '.', '');

                                $giftLineTotalDiscount = $giftUnitDiscount * $itemQty;
                                $giftLineTotalAmount = $giftDtlDiscountAmount * $itemQty;

                                $giftLineTotalVat = $giftLineTotalAmount - $giftLineTotalAmount / 1.1;
                                
                            } else {
                                $giftLineTotalDiscount = 0;
                                $giftUnitVat = number_format($itemGiftPrice - (number_format($itemGiftPrice / 1.1, 2, '.', '')), 2, '.', '');
                            }
                            
                            if ($isVatCalc == false) {
                                $itemGiftPrice = $itemGiftPrice - $giftUnitVat;
                                $giftLineTotalAmount = $giftLineTotalAmount - $giftLineTotalVat;

                                $giftUnitVat = 0;
                                $giftLineTotalVat = 0;
                            }
            
                            $stocks .= "{
                                'code': '" . self::apiStringReplace($giftJsonRow['itemcode']) . "',
                                'name': '" . self::apiStringReplace($giftJsonRow['itemname']) . "',
                                'measureUnit': '" . self::convertCyrillicMongolia('ш') . "',
                                'qty': '" . sprintf("%.2f", $itemQty) . "',
                                'unitPrice': '" . sprintf("%.2f", $itemGiftPrice) . "',
                                'totalAmount': '" . sprintf("%.2f", $giftLineTotalAmount) . "',
                                'cityTax': '0.00',
                                'vat': '" . sprintf("%.2f", $giftLineTotalVat) . "',
                                'barCode': '" . $giftJsonRow['barcode'] . "'
                            }, ";  
                            
                            $row = array(
                                'cityTax'        => '', 
                                'itemName'       => $giftJsonRow['itemname'], 
                                'salePrice'      => $itemGiftPrice, 
                                'itemQty'        => $itemQty, 
                                'totalPrice'     => $giftLineTotalAmount, 
                                'unitReceivable' => 0, 
                                'maxPrice'       => 0, 
                                'isDelivery'     => $giftJsonRow['isDelivery']
                            );
                            $itemPrintList .= self::{$itemPrintRenderFncName}($row);
                            
                            $giftJsonRow['saleprice'] = $itemGiftPrice;
                            $giftJsonRow['unitPrice'] = $itemGiftPrice;
                            $giftJsonRow['lineTotalPrice'] = $giftLineTotalAmount;
                            
                            $giftJsonRow['unitAmount'] = $itemGiftPrice;
                            $giftJsonRow['lineTotalAmount'] = $giftLineTotalAmount;
                
                            $giftJsonRow['percentVat'] = 10;
                            $giftJsonRow['unitVat'] = $giftUnitVat;
                            $giftJsonRow['lineTotalVat'] = number_format($giftJsonRow['unitVat'] * $itemQty, 2, '.', '');
                            
                            $giftJsonRow['percentDiscount'] = 0;
                            $giftJsonRow['unitDiscount'] = 0;
                            $giftJsonRow['lineTotalDiscount'] = $giftLineTotalDiscount;
                            
                            $giftJsonRowMerge['invoiceqty'] = 1;
                            $giftJsonRowMerge['unitPrice'] = $itemGiftPrice;
                            $giftJsonRowMerge['lineTotalPrice'] = $itemGiftPrice;
                            
                            $giftJsonRowMerge['unitAmount'] = $itemGiftPrice;
                            $giftJsonRowMerge['lineTotalAmount'] = $itemGiftPrice;
                
                            $giftJsonRowMerge['percentVat'] = 10;
                            $giftJsonRowMerge['unitVat'] = number_format($itemGiftPrice - (number_format($itemGiftPrice / 1.1, 2, '.', '')), 2, '.', '');
                            $giftJsonRowMerge['lineTotalVat'] = $giftJsonRowMerge['unitVat'];
                            
                            $sumTotal += $giftLineTotalAmount;
                            
                        } else {
                            $giftList .= self::giftPrintRow($giftJsonRow);
                        }
                        
                        if ($giftJsonRow['jobid'] == '') {
                            
                            if ($itemQty > 1) {
                                
                                $giftJsonRowMerge['invoiceqty'] = 1;
                                
                                if ($giftJsonRow['isDelivery'] == 1 && $isServicePos != '1') {
                                    
                                    $giftJsonRow['warehouseId'] = $deliveryWarehouseId; 
                                    $giftJsonRowLoop = array_merge($giftJsonRow, $giftJsonRowMerge);
                                    
                                    for ($gt = 0; $gt < $itemQty; $gt++) {
                                        $deliveryDtl[] = $giftJsonRowLoop;
                                    }
                                    
                                } else {
                                    $giftJsonRow['warehouseId'] = $storeWarehouseId; 
                                    $giftJsonRowLoop = array_merge($giftJsonRow, $giftJsonRowMerge);
                                    
                                    for ($gt = 0; $gt < $itemQty; $gt++) {
                                        $noDeliveryDtl[] = $giftJsonRowLoop;
                                    }
                                }
                                
                            } else {
                                if ($giftJsonRow['isDelivery'] == 1 && $isServicePos != '1') {
                                    $giftJsonRow['warehouseId'] = $deliveryWarehouseId; 
                                    $deliveryDtl[] = $giftJsonRow;
                                } else {
                                    $giftJsonRow['warehouseId'] = $storeWarehouseId; 
                                    $noDeliveryDtl[] = $giftJsonRow;
                                }
                            }
                        }
                        
                        $itemGiftList[] = $giftJsonRow;
                        
                    } else {
                        $giftList .= self::giftPrintRow($giftJsonRow);
                    }
                }
                
                $paramsDtl[$k]['SDM_SALES_ORDER_ITEM_PACKAGE'] = $itemPackageList;
                $paramsDtl[$k]['POS_SM_SALES_INVOICE_DETAIL'] = $itemGiftList;
            }
            
            if ($isJob == '0' || $isJob == '') {
                
                if ($isDelivery == 1) {

                    $paramsDtl[$k]['warehouseId'] = $deliveryWarehouseId;
                    $deliveryDtl[] = $paramsDtl[$k];

                } else {
                    $paramsDtl[$k]['warehouseId'] = $storeWarehouseId;
                    $noDeliveryDtl[] = $paramsDtl[$k];
                }
            }
            
            if ($itemPrintCopies && $itemPrintCopies > $itemPrintCopiesLast) {
                $itemPrintCopiesLast = $itemPrintCopies;
            }
        }
        
        $params['POS_SM_SALES_INVOICE_DETAIL'] = $paramsDtl;
        
        if ($cityTaxAmount > 0) {
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0082'), self::posAmount($cityTaxAmount)), $paymentDtlTemplate);
        }
        
        if ($cashAmount > 0) {
            
            $saveCashAmt = $cashAmount - $changeAmount;
            
            $paymentDtl[] = array(
                'paymentTypeId' => 1, 
                'amount'        => $saveCashAmt
            );
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0083'), self::posAmount($cashAmount)), $paymentDtlTemplate);
            
            $generateVaucherAmt += $saveCashAmt;
        }
        
        if ($bankAmount > 0) {
            
            $bankAmountDtl = $paymentData['bankAmountDtl'];
            
            foreach ($bankAmountDtl as $b => $bankDtlAmount) {
                
                $bankId         = $paymentData['posBankIdDtl'][$b];
                $bankDtlAmount  = Number::decimal($bankDtlAmount);
                        
                if ($bankId != '' && $bankDtlAmount > 0) {
                    
                    $paymentDtl[] = array(
                        'paymentTypeId' => 2, 
                        'bankId'        => $bankId,
                        'amount'        => $bankDtlAmount
                    );
                    
                    $isBankCardPaid = true;
                }
            }
            
            $nonCashAmount += $bankAmount;
            $generateVaucherAmt += $bankAmount;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0084'), self::posAmount($bankAmount)), $paymentDtlTemplate);
        }
        
        if ($itemDiscountAmount > 0) {
            $discountDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0085'), self::posAmount($itemDiscountAmount)), $paymentDtlTemplate);
        }
        
        if ($voucherAmount > 0) {
            
            $voucherDtlAmount = $paymentData['voucherDtlAmount'];
            
            foreach ($voucherDtlAmount as $v => $voucherAmountDtl) {
                
                $voucherId           = $paymentData['voucherDtlId'][$v];
                $voucherTypeId       = $paymentData['voucherTypeId'][$v];
                $voucherAmountDtl    = Number::decimal($voucherAmountDtl);
                $voucherSerialNumber = $paymentData['voucherDtlSerialNumber'][$v];
                
                if ($voucherId != '' && $voucherAmountDtl > 0 && $voucherSerialNumber != '') {
                    
                    $voucherUsedDtl[] = array(
                        'id' => $voucherId
                    );
                    
                    if ($voucherTypeId == 1) {
                        $voucherPaymentTypeId = 10;
                        $voucherTypeName = Lang::line('POS_0086');
                    } elseif ($voucherTypeId == 2) {
                        $voucherPaymentTypeId = 9;
                        $voucherTypeName = Lang::line('POS_0087');
                    } elseif ($voucherTypeId == 4) {
                        $voucherPaymentTypeId = 11;
                        $voucherTypeName = Lang::line('POS_0044');
                    } else {
                        $voucherPaymentTypeId = 10;
                        $voucherTypeName = Lang::line('POS_0088');
                    }
                    
                    $paymentDtl[] = array(
                        'paymentTypeId' => $voucherPaymentTypeId, 
                        'amount'        => $voucherAmountDtl
                    );
                    
                    $discountDetail .= str_replace(array('{labelName}', '{amount}'), array($voucherTypeName, self::posAmount($voucherAmountDtl)), $paymentDtlTemplate);
                }
            }
            
            //$nonCashAmount += $voucherAmount;
        }
        
        $bonusCardNumber                = '00000000';
        $bonusCardDiscountPercent       = '0';
        $bonusCardBeginAmount           = '0';
        $bonusCardDiscountPercentAmount = '0';
        $bonusCardEndAmount             = '0';
        
        if ($accountTransferAmt > 0) {
            
            $isInvInfo = true;
            
            if (isset($paymentData['accountTransferAmountDtl'])) {
                
                $accountTransferAmountDtl = $paymentData['accountTransferAmountDtl'];
            
                foreach ($accountTransferAmountDtl as $t => $accountTransferDtlAmount) {

                    $accountTransferBankId    = $paymentData['accountTransferBankIdDtl'][$t];
                    $accountTransferDtlAmount = Number::decimal($accountTransferDtlAmount);

                    if ($accountTransferDtlAmount > 0 && $accountTransferBankId != '') {

                        $paymentDtl[] = array(
                            'paymentTypeId' => 4, 
                            'bankId'        => $accountTransferBankId, 
                            'amount'        => $accountTransferDtlAmount, 
                            'bankBillingId' => $paymentData['accountTransferBillingIdDtl'][$t], 
                            'description'   => $paymentData['accountTransferDescrDtl'][$t]
                        );
                        
                        if (isset($paymentData['accountTransferBillingIdDtl'][$t]) && $paymentData['accountTransferBillingIdDtl'][$t]) {
                            $atBankBillingIds[] = $paymentData['accountTransferBillingIdDtl'][$t];
                        }
                    }
                }
                
            } else {
                $paymentDtl[] = array(
                    'paymentTypeId' => 4, 
                    'bankId'        => $paymentData['posAccountTransferBankId'],
                    'amount'        => $accountTransferAmt
                );
            }
            
            $nonCashAmount += $accountTransferAmt;
            $generateVaucherAmt += $accountTransferAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0089'), self::posAmount($accountTransferAmt)), $paymentDtlTemplate);
        }
        
        if ($mobileNetAmt > 0) {
            
            $isInvInfo = true;
            
            $paymentDtl[] = array(
                'paymentTypeId' => 3, 
                'bankId'        => $paymentData['posMobileNetBankId'],
                'amount'        => $mobileNetAmt
            );
            
            $nonCashAmount += $mobileNetAmt;
            $generateVaucherAmt += $mobileNetAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0090'), self::posAmount($mobileNetAmt)), $paymentDtlTemplate);
        }
        
        if ($barterAmt > 0) {
            
            $isInvInfo = true;
            
            $paymentDtl[] = array(
                'paymentTypeId' => 5, 
                'amount'        => $barterAmt
            );
            
            $nonCashAmount += $barterAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0091'), self::posAmount($barterAmt)), $paymentDtlTemplate);
        }
        
        if ($leasingAmt > 0) {
            
            $isInvInfo = true;
            
            $paymentDtl[] = array(
                'paymentTypeId' => 6, 
                'bankId'        => $paymentData['posLeasingBankId'],
                'amount'        => $leasingAmt
            );
            
            $nonCashAmount += $leasingAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0092'), self::posAmount($leasingAmt)), $paymentDtlTemplate);
        }
        
        if ($empLoanAmt > 0) {
            
            $isInvInfo = true;
            
            $paymentDtl[] = array(
                'paymentTypeId' => 7, 
                'amount'        => $empLoanAmt
            );
            
            $nonCashAmount += $empLoanAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0093'), self::posAmount($empLoanAmt)), $paymentDtlTemplate);
        }
        
        if ($localExpenseAmt > 0) {
            
            $isPut = false;
            
            $params['localExpenseType']   = 1001;
            $params['localExpenseAmount'] = $localExpenseAmt;
            
            $paymentDtl[] = array(
                'paymentTypeId' => 13, 
                'amount'        => $localExpenseAmt
            );
            
            $nonCashAmount += $localExpenseAmt;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0094'), self::posAmount($localExpenseAmt)), $paymentDtlTemplate);
            
            $params = self::setZeroVatAmount($params);
        }
        
        if ($emdAmount > 0) {
            
            $paymentDtl[] = array(
                'paymentTypeId' => 14, 
                'amount'        => $emdAmount
            );
            
            $nonCashAmount += $emdAmount;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0095'), self::posAmount($emdAmount)), $paymentDtlTemplate);
        }
        
        if ($deliveryAmount > 0) {
            
            $paymentDtl[] = array(
                'paymentTypeId' => 17, 
                'amount'        => $deliveryAmount
            );
            
            $nonCashAmount += $deliveryAmount;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array('Үлдэгдэл төлбөр', self::posAmount($deliveryAmount)), $paymentDtlTemplate);
        }
        
        if ($candyAmount > 0) {
            
            $candyAmountDtl = $paymentData['candyAmountDtl'];
            
            foreach ($candyAmountDtl as $c => $candyDtlAmount) {
                
                $candyTypeCode  = $paymentData['candyTypeCodeDtl'][$c];
                $candyNumber    = $paymentData['candyDetectedNumberDtl'][$c];
                $candyTransactionId = $paymentData['candyTransactionIdDtl'][$c];
                $candyDtlAmount     = Number::decimal($candyDtlAmount);
                        
                if ($candyTypeCode && $candyNumber && $candyTransactionId && $candyDtlAmount > 0) {
                    
                    $paymentDtl[] = array(
                        'paymentTypeId' => 15, 
                        'amount'        => $candyDtlAmount, 
                        'candyTypeCode' => $candyTypeCode,
                        'candyNumber'   => $candyNumber,
                        'candyTransactionId' => $candyTransactionId,
                    );
                    
                    if ($candyTypeCode == 'qrcodegenerate' && $candyTypeCode == 'qrcoderead') {
                        $candyLabelName = 'QR код';
                    } else {
                        $candyLabelName = $candyNumber;
                    }
                    
                    $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array('Monpay ('.$candyLabelName.')', self::posAmount($candyDtlAmount)), $paymentDtlTemplate);
                }
            }
            
            $nonCashAmount += $candyAmount;
        }
        
        if ($lendMnAmount > 0) {
            
            $paymentDtl[] = array(
                'paymentTypeId' => 18, 
                'amount'        => $lendMnAmount
            );
            
            $nonCashAmount += $lendMnAmount;
            
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array('LendMN', self::posAmount($lendMnAmount)), $paymentDtlTemplate);
        }
        
        if ($changeAmount > 0) {
            $paymentDetail .= str_replace(array('{labelName}', '{amount}'), array(Lang::line('POS_0096'), self::posAmount($changeAmount)), $paymentDtlTemplate);
        }
        
        if ($paymentData['invoiceId'] != '') {
            $talonInvoiceId = $paymentData['invoiceId'];
            $params['META_DM_RECORD_MAP']['trgRecordId'] = $talonInvoiceId;
        }
        
        //if (isset($isInvInfo)) {
        if (array_key_exists('invInfoInvoiceNumber', $paymentData)) {
            
            $params['refInvoiceNumber']    = Input::param($paymentData['invInfoInvoiceNumber']);
            $params['refBookNumber']       = Input::param($paymentData['invInfoBookNumber']);
            $params['customerName']        = Input::param($paymentData['invInfoCustomerName']);
            $params['customerLastName']    = Input::param($paymentData['invInfoCustomerLastName']);
            $params['customerRegNumber']   = Input::param($paymentData['invInfoCustomerRegNumber']);
            $params['phoneNumber']         = Input::param($paymentData['invInfoPhoneNumber']);
            $params['description']         = Input::param($paymentData['invInfoTransactionValue']);
            $params['customerBankId']      = issetVar($paymentData['customerBankId']);
            $params['customerBankAccount'] = issetVar($paymentData['customerBankAccount']);
        }
        
        $params['SM_SALES_PAYMENT_DV'] = $paymentDtl;
        
        if ($sumTotal > 0 && $vatAmount > 0) {
            
            $totalAmount = $sumTotal;
            $vatAmount   = $totalAmount - number_format($totalAmount / 1.1, 2, '.', '');
                    
            $params['total'] = $totalAmount;
            $params['vat']   = $vatAmount;
        }

        $lockerCode = '';
        if (isset($_POST['lockerId'])) {
            $lockInfo = explode('_', $_POST['lockerId']);
            $params['lockerId'] = $lockInfo[0];
            $lockerCode = $lockInfo[1];
        }
        
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'POS_SM_SALES_INVOICE_HEADER_001', $params);
        
        if ($result['status'] == 'success') {
            
            $invoiceResult = $result['result'];
            $invoiceId     = $invoiceResult['id'];
            
            $bonusCardCustomerResult = self::createBonusCardCustomer($paymentData['newCardCustomerJson']);
                
            if ($bonusCardCustomerResult['status'] != 'success') {
                
                self::deleteSalesInvoice($invoiceId, $bonusCardCustomerResult, $serviceCustomerId);
                
                $response = array('status' => 'warning', 'message' => Lang::line('POS_0081') . ' (CARD). '.$bonusCardCustomerResult['message']);

                return $response;
            }

            $sdmDeliveryResult = self::createSdmDelivery($invoiceId, $headerParams, $paymentData, $deliveryDtl, $noDeliveryDtl);

            if ($sdmDeliveryResult['status'] != 'success') {
                
                self::deleteSalesInvoice($invoiceId, $bonusCardCustomerResult, $serviceCustomerId);

                $response = array('status' => 'warning', 'message' => Lang::line('POS_0097') . ' '.$sdmDeliveryResult['message']);

                return $response;
            }
            
            $serviceResult = self::createToService($invoiceId, $headerParams, $paymentData, $serviceDtl);
            
            if ($serviceResult['status'] != 'success') {
                
                self::deleteSalesInvoice($invoiceId, $bonusCardCustomerResult, $serviceCustomerId);

                $response = array('status' => 'warning', 'message' => Lang::line('POS_0098') . ' '.$serviceResult['message']);

                return $response;
            }
            
            if ($stocks == '' || $totalAmount == 0 || $totalAmount < 0) {
                $isPut = false;
                $billTitle = Lang::line('POS_0080');
            }
            
            $langCode       = Lang::getCode();
            $printType      = Config::getFromCache('CONFIG_POS_PRINT_TYPE');
            $sessionPosLogo = Session::get(SESSION_PREFIX.'posLogo');
            $posLogo        = ($sessionPosLogo ? $sessionPosLogo : 'pos-logo.png');
            $voucherContent = '';
            $dirPath        = '';
        
            if ($langCode != 'mn') {
                $dirPath = '/en';
            }
            
            if ($voucherDtl) {
                
                $voucherPath = '';
                
                if (Config::getFromCache('CONFIG_POS_VOUCHER_PATH')) {
                    $voucherPath = Config::getFromCache('CONFIG_POS_VOUCHER_PATH') . '/';
                }

                $voucherTemplate      = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.'/voucher/'.$voucherPath.'template1.html');
                $voucherCashTemplate  = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.'/voucher/'.$voucherPath.'cash.html');
                $voucherOtherTemplate = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.'/voucher/'.$voucherPath.'other.html');
                
                $activedVouchers = array();
                
                foreach ($voucherDtl as $voucherRow) {
                    
                    $getVoucker = self::getVouckerListModel($voucherRow['typeId'], $voucherRow['amount'], $voucherRow['percent'], $storeId);

                    if ($getVoucker) {

                        $voucherDurationDay = $getVoucker['durationday'];
                        $voucherExpireDate  = Date::weekdayAfter('Y-m-d H:i:s', $currentDate, '+'.$voucherDurationDay.' days');
                        $voucherAmount = ($voucherRow['percentamount'] ? $voucherRow['percentamount'] : $getVoucker['amount']);
                        
                        $voucherReplacing = array(
                            '{voucherName}'         => $voucherRow['name'], 
                            '{voucherAmount}'       => self::posAmount($voucherAmount), 
                            '{voucherPureAmount}'   => $voucherAmount, 
                            '{voucherSerialNumber}' => $getVoucker['serialnumber'],
                            '{refNumber}'           => $refNumber,
                            '{voucherYear}'         => Date::formatter($voucherExpireDate, 'Y'), 
                            '{voucherMonth}'        => Date::formatter($voucherExpireDate, 'm'), 
                            '{voucherDay}'          => Date::formatter($voucherExpireDate, 'd')
                        );
                        
                        if ($voucherRow['typeId'] == 5) { 
                            
                            $voucherContent .= strtr($voucherOtherTemplate, $voucherReplacing);
                            
                        } elseif ($voucherRow['typeId'] == 6) {
            
                            $voucherReplacing['{bankName}'] = self::getPosBankNameById(issetParam($params['customerBankId']));
                            $voucherReplacing['{bankAccountNumber}'] = issetParam($params['customerBankAccount']);
                            
                            $voucherContent .= strtr($voucherCashTemplate, $voucherReplacing);
                            
                        } else {
                            $voucherContent .= strtr($voucherTemplate, $voucherReplacing);
                        }

                        $updateVoucherParams = array(
                            'id'                => $getVoucker['id'], 
                            'activationDate'    => $currentDate, 
                            'expiredDate'       => $voucherExpireDate, 
                            'durationDay'       => $voucherDurationDay, 
                            'amount'            => $voucherAmount, 
                            'percent'           => '', 
                            'activatedSalesInvoiceDtlId' => $invoiceResult['pos_sm_sales_invoice_detail'][$voucherRow['rowIndex']]['id']
                        );

                        self::updateVoucherByAmount($updateVoucherParams);
                        
                        $activedVouchers[] = $updateVoucherParams;
                        
                    } else {
                        
                        if (count($activedVouchers) > 0) {
                            foreach ($activedVouchers as $av => $activedVoucher) {
                                self::updateVoucherInactive($activedVoucher);
                            }
                        }
                        
                        self::deleteSalesInvoice($invoiceId, $bonusCardCustomerResult, $serviceCustomerId);
                        
                        $response = array(
                            'status' => 'warning', 
                            'message' => Lang::lineVar('POS_0099', array('vaucher' => number_format($voucherRow['amount'], 2, '.', ',')))
                        );

                        return $response;
                    }
                }
            }
            
            if ($couponKeyDtl) {
                
                foreach ($couponKeyDtl as $couponKeyDtlRow) {
                    
                    if ($couponDurationDay = self::getCouponDurationDay($couponKeyDtlRow['couponKeyId'])) {
                        
                        $couponExpireDate  = Date::weekdayAfter('Y-m-d H:i:s', $currentDate, '+'.$couponDurationDay.' days');

                        $updateCouponParams = array(
                            'id'                => $couponKeyDtlRow['couponKeyId'], 
                            'activationDate'    => $currentDate, 
                            'expiredDate'       => $couponExpireDate, 
                            'durationDay'       => $couponDurationDay, 
                            'activatedSalesInvoiceDtlId' => $invoiceResult['pos_sm_sales_invoice_detail'][$couponKeyDtlRow['rowIndex']]['id']
                        );

                        self::updateVoucher($updateCouponParams);
                    }
                }
            }
            
            /* Дотоод зардал үед */
            
            if ($isPut == false) { 
                
                $vatNumber       = Session::get($sPrefix.'vatNumber');
                $storeName       = Session::get($sPrefix.'storeName');
                $cashCode        = Session::get($sPrefix.'cashRegisterCode');
                $cashierName     = Session::get($sPrefix.'cashierName');
                $contactInfo     = Session::get($sPrefix.'posContactInfo');
                $topTitle        = Session::get($sPrefix.'posHeaderName');
                $printCopies     = (int) $paymentData['posPrintCopies'];
                $billTitle       = (isset($billTitle) ? $billTitle : 'ДОТООД ЗАРДАЛ');
                $salesPersonCode = self::getSalesPersonCode($itemData);
                
                $templateContent = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.$dirPath.'/local/expense.html');
                
                $discountPart    = '';
                
                if ($discountAmount > 0) {
                    
                    $discountPartTemplate = self::discountPartTemplate();
                    
                    $discountPart = str_replace(
                        array('{totalDiscount}',                '{discount-detail}'), 
                        array(self::posAmount($discountAmount),  $discountDetail), 
                        $discountPartTemplate
                    );
                }
                
                if ($voucherUsedDtl) {
                    
                    foreach ($voucherUsedDtl as $voucherUsedRow) {
                        
                        $updateVoucherUsedParams = array(
                            'id'                        => $voucherUsedRow['id'], 
                            'usedDate'                  => $currentDate, 
                            'usedStoreId'               => $storeId, 
                            'usedCashierId'             => $cashierId, 
                            'usedSalesInvoiceBookId'    => $invoiceId 
                        );

                        self::updateVoucherUsed($updateVoucherUsedParams);
                    }
                }
                
                $replacing = array(
                    '{poslogo}'         => $posLogo,
                    '{companyName}'     => $topTitle,
                    '{title}'           => $billTitle, 
                    '{vatNumber}'       => $vatNumber,
                    '{contactInfo}'     => $contactInfo,
                    '{date}'            => Date::formatter($currentDate, 'Y/m/d'),
                    '{time}'            => Date::formatter($currentDate, 'H:i:s'),
                    '{refNumber}'       => $refNumber,
                    '{invoiceNumber}'   => $invoiceNumber,
                    '{storeName}'       => $storeName,
                    '{cashierName}'     => $cashierName,
                    '{cashCode}'        => $cashCode, 
                    '{salesPersonCode}' => $salesPersonCode, 
                    '{salesWaiter}'     => Input::post('waiterText', ''),
                    '{orderTime}'       => Input::post('posEshopOrderTime', ''), 
                    '{itemList}'        => $itemPrintList,
                    '{totalAmount}'     => self::posAmount($printTotalAmount),
                    '{payAmount}'       => self::posAmount($totalAmount),
                    '{vatAmount}'       => self::posAmount($vatAmount),
                    '{discountPart}'    => $discountPart,
                    '{totalItemCount}'  => self::posAmount($totalItemCount), 
                    '{payment-detail}'  => $paymentDetail
                );
                
                $internalContent = strtr($templateContent, $replacing);
                        
                if ($printCopies > 1) {
                    $internalContent = str_repeat($internalContent, $printCopies);
                }
                
                $cssContent       = Mdpos::getPrintCss();
                $newInvoiceNumber = self::getBillNumModel();
                
                return array('status' => 'success', 'css' => $cssContent, 'printData' => $internalContent, 'billNumber' => $newInvoiceNumber);
            }
            
            $vatNumber      = Session::get($sPrefix.'vatNumber');
            $districtCode   = Session::get($sPrefix.'posDistrictCode');
            $cashCode       = Session::get($sPrefix.'cashRegisterCode');
            $isNotSendVatsp = (Session::get($sPrefix.'isNotSendVatsp') == '1' ? true : false);
            $reportMonth    = '';
            $customerNo     = '';
            $orgRegNumber   = '';
            $orgName        = '';
            $billIdSuffix   = '';
            $taxType        = 1;
            
            if ($isNotSendVatsp == false) {
            
                if ($posBillType == 'person') {

                    $billType   = 1;
                    $title      = Lang::line('POS_0100');

                } elseif ($posBillType == 'organization') {

                    $billType       = 3;
                    $title          = Lang::line('POS_0101');

                    $orgRegNumber   = Str::upper(Input::param($paymentData['orgRegNumber']));
                    $customerNo     = self::convertCyrillicMongolia($orgRegNumber); //'0000039';
                    $orgName        = $paymentData['orgName'];
                    $taxType        = $isVatCalc ? 1 : 2;

                } else {
                    $billType       = 5;
                    $title          = Lang::line('POS_0102');
                }

                if (Config::getFromCache('CONFIG_POS_HEALTHRECIPE')) {

                    $isReceiptNumber = Input::post('isReceiptNumber');

                    if ($isReceiptNumber == 'true' && isset($_POST['drugPrescription'])) {

                        $prescriptionArr = $_POST['drugPrescription'];
                        $billIdSuffix = $prescriptionArr['receiptNumber'];
                    }
                }

                $posApiCashAmount = $cashAmount - $changeAmount + $totalBonusAmount;
                $posApiNonCashAmount = $nonCashAmount - $totalBonusAmount;

                if ($posApiNonCashAmount < 0) {
                    $posApiCashAmount = $posApiCashAmount - ($posApiNonCashAmount * -1);
                    $posApiNonCashAmount = 0;
                }

                if ($posApiCashAmount < 0) {
                    $posApiCashAmount = 0;
                    $posApiNonCashAmount = $posApiNonCashAmount - ($posApiCashAmount * -1);
                }

                if ($totalAmount < $posApiNonCashAmount) {
                    $posApiNonCashAmount = $posApiNonCashAmount - $changeAmount;
                }

                $jsonParam = "{
                    'amount': '" . sprintf("%.2f", $totalAmount) . "',
                    'vat': '" . sprintf("%.2f", $vatAmount) . "',
                    'cashAmount': '" . sprintf("%.2f", $posApiCashAmount) . "',
                    'nonCashAmount': '" . sprintf("%.2f", $posApiNonCashAmount) . "',
                    'cityTax': '" . sprintf("%.2f", $cityTaxAmount) . "',
                    'districtCode': '" . $districtCode . "',
                    'posNo': '" . $cashCode . "',
                    'reportMonth': '" . $reportMonth . "',
                    'customerNo': '" . $customerNo . "',
                    'billType': '" . $billType . "',
                    'taxType': '" . $taxType . "',
                    'billIdSuffix': '" . $billIdSuffix . "',
                    'returnBillId': '',
                    'stocks': [
                        " . rtrim($stocks, ', ') . "
                    ]
                }";

                $jsonParam = Str::remove_doublewhitespace(Str::removeNL($jsonParam));

                $posApiArray = self::posApiFunction($jsonParam);
                $billId      = isset($posApiArray['billId']) ? $posApiArray['billId'] : null;
                
            } else {
                
                $billId = '1';
                $posApiArray = array('date' => null);
            }
            
            if ($billId) {
                
                $storeName       = Session::get($sPrefix.'storeName');
                $cashierName     = Session::get($sPrefix.'cashierName');
                $contactInfo     = Session::get($sPrefix.'posContactInfo');
                $topTitle        = Session::get($sPrefix.'posHeaderName');
                $printCopies     = (int) $paymentData['posPrintCopies'];
                $salesPersonCode = self::getSalesPersonCode($itemData);
                $putDate         = $posApiArray['date'] ? $posApiArray['date'] : $currentDate;
                
                $discountPart    = '';
                $loyaltyPart     = '';
                
                if ($discountAmount > 0) {
                    
                    $discountPartTemplate = self::discountPartTemplate();
                    
                    $discountPart = str_replace(
                        array('{totalDiscount}',                '{discount-detail}'), 
                        array(self::posAmount($discountAmount),  $discountDetail), 
                        $discountPartTemplate
                    );
                }
                
                if ($voucherUsedDtl) {
                    
                    foreach ($voucherUsedDtl as $voucherUsedRow) {
                        
                        $updateVoucherUsedParams = array(
                            'id'                        => $voucherUsedRow['id'], 
                            'usedDate'                  => $currentDate, 
                            'usedStoreId'               => $storeId, 
                            'usedCashierId'             => $cashierId, 
                            'usedSalesInvoiceBookId'    => $invoiceId 
                        );

                        self::updateVoucherUsed($updateVoucherUsedParams);
                    }
                }
                
                /* Flower */
                if (defined('CONFIG_POS_ADDITIONAL_VOUCHER') && CONFIG_POS_ADDITIONAL_VOUCHER) { 

                    $additionalVoucherList = self::getAdditionalVoucherListModel($storeId, $totalAmount);

                    if ($additionalVoucherList) {

                        $addiVoucherTemplate = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.'/voucher/template1.html');

                        foreach ($additionalVoucherList as $additionalVoucherRow) {

                            $voucherDurationDay = $additionalVoucherRow['durationday'];
                            $voucherExpireDate  = Date::weekdayAfter('Y-m-d H:i:s', $currentDate, '+'.$voucherDurationDay.' days');
 
                            $voucherReplacing = array(
                                '{voucherName}'         => 'ВАУЧЕР', 
                                '{voucherAmount}'       => self::posAmount($additionalVoucherRow['amount']), 
                                '{voucherPureAmount}'   => $additionalVoucherRow['amount'], 
                                '{voucherSerialNumber}' => $additionalVoucherRow['serialnumber'],
                                '{refNumber}'           => $refNumber,
                                '{voucherYear}'         => Date::formatter($voucherExpireDate, 'Y'), 
                                '{voucherMonth}'        => Date::formatter($voucherExpireDate, 'm'), 
                                '{voucherDay}'          => Date::formatter($voucherExpireDate, 'd')
                            );

                            $voucherContent .= strtr($addiVoucherTemplate, $voucherReplacing);

                            if (isset($additionalVoucherParams)) {

                                $additionalVoucherParams[] = array(
                                    'id'                => $additionalVoucherRow['id'], 
                                    'activationDate'    => $currentDate, 
                                    'expiredDate'       => $voucherExpireDate, 
                                    'durationDay'       => $voucherDurationDay, 
                                    'activatedSalesInvoiceId' => $invoiceId
                                );

                            } else {

                                $additionalVoucherParams = array(
                                    array(
                                        'id'                => $additionalVoucherRow['id'], 
                                        'activationDate'    => $currentDate, 
                                        'expiredDate'       => $voucherExpireDate, 
                                        'durationDay'       => $voucherDurationDay, 
                                        'activatedSalesInvoiceId' => $invoiceId
                                    )
                                );
                            }
                        }
                    }
                }
                
                if (isset($additionalVoucherParams)) {
                    
                    self::updateAdditionalVoucherActived($additionalVoucherParams);
                }
                
                if (defined('CONFIG_POS_AUTO_GENERATE_VAUCHER') && CONFIG_POS_AUTO_GENERATE_VAUCHER && $generateVaucherAmt > 0) {
                    
                    $voucherContent .= self::posAutoGenerateVaucher($invoiceId, $generateVaucherAmt, $printType);
                }
                
                if ($isNotSendVatsp == false) {
                
                    if ($billType == 1) { // Хувь хүн

                        $templateContent   = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.$dirPath.'/person/single.html');
                        $qrLotteryTemplate = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.$dirPath.'/person/qrcode-lottery.html');

                        $promotionContent = self::getBillPromotionModel();

                        $templateContent = str_replace('{promotion}', $promotionContent, $templateContent);
                        $templateContent = str_replace('{qrCodeLottery}', $qrLotteryTemplate, $templateContent);

                        $lotteryContent = self::printLotteryPriceInterval($invoiceId, $printType, issetParam($params['phoneNumber']));

                        $templateContent = str_replace('{lotterypart}', $lotteryContent, $templateContent);

                        $lottery         = $posApiArray['lottery'];
                        $qrData          = $posApiArray['qrData'];

                        $replacing = array(
                            '{poslogo}'         => $posLogo,
                            '{companyName}'     => $topTitle,
                            '{title}'           => '', 
                            '{vatNumber}'       => $vatNumber,
                            '{contactInfo}'     => $contactInfo,
                            '{ddtd}'            => $billId,
                            '{date}'            => Date::formatter($putDate, 'Y/m/d'),
                            '{time}'            => Date::formatter($putDate, 'H:i:s'),
                            '{refNumber}'       => $refNumber,
                            '{invoiceNumber}'   => $invoiceNumber,
                            '{storeName}'       => $storeName,
                            '{cashierName}'     => $cashierName,
                            '{cashCode}'        => $cashCode, 
                            '{salesPersonCode}' => $salesPersonCode, 
                            '{salesWaiter}'     => Input::post('waiterText', ''),
                            '{orderTime}'       => Input::post('posEshopOrderTime', ''), 
                            '{itemList}'        => $itemPrintList,
                            '{totalAmount}'     => self::posAmount($printTotalAmount),
                            '{payAmount}'       => self::posAmount($totalAmount),
                            '{vatAmount}'       => self::posAmount($vatAmount),
                            '{discountPart}'    => $discountPart,
                            '{lottery}'         => $lottery,
                            '{qrCode}'          => self::getQrCodeImg($qrData),
                            '{giftList}'        => self::giftTableRender($giftList), 
                            '{totalItemCount}'  => self::posAmount($totalItemCount), 

                            '{bonusCardNumber}'         => $bonusCardNumber, 
                            '{bonusCardDiscountPercent}'=> $bonusCardDiscountPercent,
                            '{bonusCardBeginAmount}'    => self::posAmount($bonusCardBeginAmount), 
                            '{bonusCardDiffAmount}'     => self::posAmount($bonusCardAmount), 
                            '{bonusCardPlusAmount}'     => self::posAmount($bonusCardDiscountPercentAmount), 
                            '{bonusCardEndAmount}'      => self::posAmount($bonusCardEndAmount), 
                            '{payment-detail}'          => $paymentDetail,
                            '{lockerCode}'              => self::printLockerCode($lockerCode)
                        );

                        $templateContent .= $voucherContent;
                        
                        if ($itemPrintCopiesLast) {
                            
                            $printCopies = $itemPrintCopiesLast;
                            
                        } elseif (Config::getFromCache('CONFIG_POS_BANKCARD_COPIES_COUNT') 
                            && isset($isBankCardPaid) && $printCopies < Config::getFromCache('CONFIG_POS_BANKCARD_COPIES_COUNT')) {

                            $printCopies = Config::getFromCache('CONFIG_POS_BANKCARD_COPIES_COUNT');
                        }
                            
                        if ($printCopies) {

                            $internalContent = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.$dirPath.'/person/internal.html');

                            $internalContent = str_replace('{title}', Lang::line('POS_0103'), $internalContent);
                            $internalContent = str_replace('{lotterypart}', '', $internalContent);

                            $internalContent = strtr($internalContent, $replacing);
                                
                            if ($printCopies > 1) {
                                $internalContent = str_repeat($internalContent, $printCopies);
                            }

                            $templateContent .= $internalContent;
                        }

                    } elseif ($billType == 3) { // Байгууллага

                        $templateContent  = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.$dirPath.'/organization/single.html');

                        $promotionContent = self::getBillPromotionModel();
                        $templateContent  = str_replace('{promotion}', $promotionContent, $templateContent);

                        $lotteryContent   = self::printLotteryPriceInterval($invoiceId, $printType, issetParam($params['phoneNumber']));

                        $templateContent  = str_replace('{lotterypart}', $lotteryContent, $templateContent);

                        $qrData           = $posApiArray['qrData'];

                        $replacing = array(
                            '{poslogo}'         => $posLogo,
                            '{companyName}'     => $topTitle,
                            '{title}'           => '', 
                            '{vatNumber}'       => $vatNumber,
                            '{contactInfo}'     => $contactInfo,
                            '{ddtd}'            => $billId,
                            '{date}'            => Date::formatter($putDate, 'Y/m/d'),
                            '{time}'            => Date::formatter($putDate, 'H:i:s'),
                            '{refNumber}'       => $refNumber,
                            '{invoiceNumber}'   => $invoiceNumber,
                            '{storeName}'       => $storeName,
                            '{cashierName}'     => $cashierName,
                            '{cashCode}'        => $cashCode, 
                            '{salesPersonCode}' => $salesPersonCode, 
                            '{salesWaiter}'     => Input::post('waiterText', ''),
                            '{orderTime}'       => Input::post('posEshopOrderTime', ''), 
                            '{itemList}'        => $itemPrintList,
                            '{totalAmount}'     => self::posAmount($printTotalAmount),
                            '{payAmount}'       => self::posAmount($totalAmount),
                            '{vatAmount}'       => self::posAmount($vatAmount),
                            '{discountPart}'    => $discountPart,
                            '{customerNumber}'  => $orgRegNumber, 
                            '{customerName}'    => $orgName, 
                            '{qrCode}'          => self::getQrCodeImg($qrData),
                            '{giftList}'        => self::giftTableRender($giftList), 
                            '{totalItemCount}'  => self::posAmount($totalItemCount), 

                            '{bonusCardNumber}'         => $bonusCardNumber, 
                            '{bonusCardDiscountPercent}'=> $bonusCardDiscountPercent,
                            '{bonusCardBeginAmount}'    => self::posAmount($bonusCardBeginAmount), 
                            '{bonusCardDiffAmount}'     => self::posAmount($bonusCardAmount), 
                            '{bonusCardPlusAmount}'     => self::posAmount($bonusCardDiscountPercentAmount), 
                            '{bonusCardEndAmount}'      => self::posAmount($bonusCardEndAmount), 
                            '{payment-detail}'          => $paymentDetail
                        );

                        $templateContent .= $voucherContent;
                        
                        if ($itemPrintCopiesLast) {
                            
                            $printCopies = $itemPrintCopiesLast;
                            
                        } elseif (Config::getFromCache('CONFIG_POS_BANKCARD_COPIES_COUNT') 
                            && isset($isBankCardPaid) && $printCopies < Config::getFromCache('CONFIG_POS_BANKCARD_COPIES_COUNT')) {

                            $printCopies = Config::getFromCache('CONFIG_POS_BANKCARD_COPIES_COUNT');
                        }
                            
                        if ($printCopies) {

                            $internalContent = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.$dirPath.'/organization/internal.html');

                            $internalContent = str_replace('{title}', Lang::line('POS_0103'), $internalContent);
                            $internalContent = str_replace('{lotterypart}', '', $internalContent);

                            $internalContent = strtr($internalContent, $replacing);
                            
                            if ($printCopies > 1) {
                                $internalContent = str_repeat($internalContent, $printCopies);
                            }

                            $templateContent .= $internalContent;
                        }
                    }

                    $templateContent = strtr($templateContent, $replacing);

                    $billResultParams = array(
                        'BILL_ID'          => $billId, 
                        'SALES_INVOICE_ID' => $invoiceId, 
                        'MERCHANT_ID'      => $posApiArray['merchantId'], 
                        'VAT_DATE'         => $putDate, 
                        'SUCCESS'          => $posApiArray['success'], 
                        'WARNING_MSG'      => $posApiArray['warningMsg'],  
                        'SEND_JSON'        => $jsonParam, 
                        'STORE_ID'         => $storeId, 
                        'CUSTOMER_NUMBER'  => $orgRegNumber, 
                        'CUSTOMER_NAME'    => $orgName
                    );

                    self::createBillResultData($billResultParams); 

                    if ($billIdSuffix != '') {

                        $recipeResult     = self::createPrescription($invoiceId, $billId, $posApiArray['date'], $totalAmount, $vatAmount, $emdAmount, $paramsDtl);
                        $recipeHeaderInfo = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/pharmacy/person/recipeHeaderInfo.html');

                        $recipeReplacing = array(
                            '{recipeCipherCode}'    => $recipeResult['cipherCode'], 
                            '{recipePatientName}'   => $recipeResult['patientLastName'].' '.$recipeResult['patientFirstName'], 
                            '{recipePatientRegNo}'  => $recipeResult['patientRegNo'], 
                            '{recipeReceiptNumber}' => $recipeResult['receiptNumber']
                        );

                        $recipeHeaderInfo = strtr($recipeHeaderInfo, $recipeReplacing);
                        $templateContent  = str_replace('{recipeHeaderInfo}', $recipeHeaderInfo, $templateContent);

                    } elseif (isset($isReceiptNumber)) {

                        $templateContent = str_replace('{recipeHeaderInfo}', '', $templateContent);
                    }

                    if (isset($candyUserCheck)) {
                        $loyaltyPart = self::candyUserToSend($invoiceId, $printType, $candyUserCheck);
                    }

                    if (isset($redPointUserCheck)) {
                        $loyaltyPart = self::redPointUserToSend($invoiceId, $printType, $redPointUserCheck, $totalAmount);
                    }

                    $templateContent = str_replace('{loyaltyPart}', $loyaltyPart, $templateContent);
                    $cssContent      = Mdpos::getPrintCss();

                    self::updateSalesInvoiceHeaderCardId($invoiceId, $bonusCardCustomerResult);
                
                } else {
                    
                    $templateContent = (new Mdtemplate())->getTemplateByArguments('1553591243602142', '1522115383994585', array('id' => $invoiceId));
                    $templateContent .= '<div style="page-break-after: always;"></div>';
                    
                    $templateContent = str_repeat($templateContent, $printCopies);
                    
                    $templateContent .= $voucherContent;
                    
                    $cssContent      = file_get_contents('assets/custom/css/print/reportPrint.css');
                }
                
                self::updateTalonInvoiceUsed($talonInvoiceId);
                self::updateBankBillingUsed($atBankBillingIds);
                
                $newInvoiceNumber = self::getBillNumModel();
                
                $response = array('status' => 'success', 'billNumber' => $newInvoiceNumber);
                
                if (Input::isEmpty('basketInvoiceId') == false) {
                    $response['basketCount'] = $this->deleteBasketInvoiceModel();
                }
                
                $response['css']        = $cssContent;
                $response['printData']  = $templateContent;
                
            } else {
                
                if (isset($sdmDeliveryResult['deliveryBookIds']) && !empty($sdmDeliveryResult['deliveryBookIds'])) {
                    
                    foreach ($sdmDeliveryResult['deliveryBookIds'] as $dk => $deliveryBookId) {
                        $this->ws->runResponse(self::$gfServiceAddress, 'POS_SDM_DELIVERY_005', array('id' => $deliveryBookId));
                    }
                }
                
                self::deleteSalesInvoice($invoiceId, $bonusCardCustomerResult, $serviceCustomerId);
                
                $warningMsg = isset($posApiArray['warningMsg']) ? $posApiArray['warningMsg'] : (isset($posApiArray['message']) ? $posApiArray['message'] : 'PosApi алдаа: NULL');
                
                $response = array('status' => 'warning', 'message' => $warningMsg);
            }

        } else {
            $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
        
        return $response;        
    }
    
    public function socialPaySendInvoiceModel($params) {     
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'socialSendInvoice', $params);

        if ($result['status'] == 'success' && isset($result['result']['invoiceid']) && !empty(isset($result['result']['invoiceid']))) {
            return array('status' => 'success', 'message' => $result['result']['invoiceid']);
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
    }
    
    public function socialPayCheckInvoiceModel($params) {     
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'checkInvoiceSocialPay', $params);

        if ($result['status'] == 'success') {
            return array('status' => 'success', 'message' => $result['result']['response']);
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
    }
    
    public function socialPayGetInvoiceQrModel($params) {
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'getInvoiceQrSocialPay', $params);

        if ($result['status'] == 'success') {
            return array('status' => 'success', 'message' => array('qr' => self::getQrCodeImg($result['result']['response']['desc'], '250px'), 'invoiceid' => $result['result']['invoiceid']));
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
    }
    
    public function socialPaySetlementModel($params) {
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'settlementSocialPay', $params);

        if ($result['status'] == 'success') {
            return array('status' => 'success', 'message' => $result['result']);
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
    }
    
    public function socialPayCancelInvoiceModel($params) {     
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'socialCancelInvoice', $params);

        if ($result['status'] == 'success') {
            return array('status' => 'success', 'message' => 'Success');
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
    }    
    
    public function upointCancelInvoiceModel($params) {     
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'upoint_return_transaction', $params);

        if ($result['status'] == 'success') {
            return array('status' => 'success', 'message' => 'Success', 'data' => $result['result']);
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
    }    
    
    public function getPrevBankNotesModel() {
        
        $storeId        = Session::get(SESSION_PREFIX.'storeId');
        $cashRegisterId = Session::get(SESSION_PREFIX.'cashRegisterId');
        $cashierId      = Session::get(SESSION_PREFIX.'cashierId');

        $row = $this->db->GetRow("
            SELECT 
                CASHIER_START_DATE, 
                CASHIER_END_DATE
            FROM SM_BANKNOTES 
            WHERE STORE_ID = ".$this->db->Param(0)." 
                AND CASH_REGISTER_ID = ".$this->db->Param(1)." 
                AND CREATED_CASHIER_ID = ".$this->db->Param(2)." 
            ORDER BY CASHIER_END_DATE DESC", 
            array($storeId, $cashRegisterId, $cashierId)
        );
        
        return $row;    
    }
    
    public function saveDateCashierModel($params, $bookDate = null) {     
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'onlinePosRotationTime_DV_001', $params);

        if ($result['status'] == 'success') {
            
            $response = array('status' => 'success', 'message' => 'Success');
            
            if ($posDayClosePrintReportMetaId = Config::getFromCache('posDayClosePrintReportMetaId')) {
                
                if ($bookDate) {
                    $cashRegisterId = Session::get(SESSION_PREFIX.'cashRegisterId');
                    $getMeta = (new Mdmetadata())->getMetaData($posDayClosePrintReportMetaId);                    

                    if (Str::upper($getMeta['META_TYPE_CODE']) == 'REPORT_TEMPLATE') {
                        $response['report'] = (new Mdtemplate())->getTemplateByArguments($posDayClosePrintReportMetaId, 'selfDvId', 
                            array('id' => $cashRegisterId, 'filterinvoicedate' => $bookDate, 'cashierId' => Session::get(SESSION_PREFIX.'cashierId'))
                        );
                    } else {

                        $_POST['dataViewId']  = '1620917831911061';
                        $_POST['statementId'] = $posDayClosePrintReportMetaId;
                        $_POST['param']['bookDate'] = $bookDate;
                        $_POST['param']['cashierId'] = Session::get(SESSION_PREFIX.'cashierId');
                        $response['report'] = (new Mdstatement())->renderDataModelByFilter(true);
                    }

                    $response['css'] = file_get_contents('assets/custom/css/print/reportPrint.css');
                }
            }
                
            return $response;
            
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
    }       
    
    public function getDateCashierModel() { 
        
        $getInvoiceDateP = array(
            'cashRegisterId' => Session::get(SESSION_PREFIX.'cashRegisterId'),
            'cashierId' => Session::get(SESSION_PREFIX.'cashierId'),
        );       
        if (Session::get(SESSION_PREFIX.'selectedDateCashier')) {
            $getInvoiceDateP['filterDate'] = Session::get(SESSION_PREFIX.'selectedDateCashier');
        }
        
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'onlinePosRotationTime_GET_004', $getInvoiceDateP);

        if ($result['status'] == 'success') {
            return array('status' => 'success', 'result' => $result['result']);
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
    }       
    
    public function posAddionalGift($refNumber, $paymentData, $printType) {
        
        $giftTemplate = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.'/gift/template1.html');
        $giftContent = '';

        if (isset($paymentData['pressureApparat'])) {
            $giftReplacing = array(
                '{refNumber}'           => $refNumber, 
                '{date}'                => Date::currentDate('Y-m-d'),
                '{storeName}'           => Session::get(SESSION_PREFIX.'storeName'),
                '{giftPhoto}'           => 'pressureApparat'
            );

            $giftContent .= strtr($giftTemplate, $giftReplacing);
        }

        if (isset($paymentData['mixer'])) {
            $giftReplacing = array(
                '{refNumber}'           => $refNumber, 
                '{date}'                => Date::currentDate('Y-m-d'),
                '{storeName}'           => Session::get(SESSION_PREFIX.'storeName'),                
                '{giftPhoto}'           => 'mixer'
            );

            $giftContent .= strtr($giftTemplate, $giftReplacing);
        }

        if (isset($paymentData['airCleaner'])) {
            $giftReplacing = array(
                '{refNumber}'           => $refNumber, 
                '{date}'                => Date::currentDate('Y-m-d'),
                '{storeName}'           => Session::get(SESSION_PREFIX.'storeName'),                
                '{giftPhoto}'           => 'airCleaner'
            );

            $giftContent .= strtr($giftTemplate, $giftReplacing);
        }

        return $giftContent;
    }    
    
    public function getItemsForLottery($id) {
        
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'VATSP_LOTTERY_GET_004', array('id' => $id));
        
        if ($result['status'] == 'success') {
            return $result['result'];
        } 
        return false;
    }    
    
    public function giftByItemPaymentModel($param) {
        
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'HDR_BILL_DISCOUNT_DV_004', $param);
        
        if ($result['status'] == 'success') {
            return $result['result'];
        } 
        return false;
    }    
    
    private function giftSavePayment($giftJsonString, &$paramsDtl, &$voucherDtl, &$totalItemCount, &$sumTotal, &$stocks, $isVatCalc, $employeeId, $itemPrintRenderFncName, &$itemPrintList, $dtlBonusPercent, $totalBonusAmount, &$serviceDtl, $headerParams, $storeId) {
        $giftJsonStr    = trim($giftJsonString);
        $giftListPayment = '';
        $itemQtyGift = $totalItemCount;

        if ($giftJsonStr != '') {

            $itemPackageList = $itemGiftList = array();
            $giftJsonArray = json_decode(html_entity_decode($giftJsonStr, ENT_NOQUOTES, 'UTF-8'), true);

            foreach ($giftJsonArray as $giftJsonRow) {

                $giftJsonRow['isDelivery'] = isset($giftJsonRow['isDelivery']) ? $giftJsonRow['isDelivery'] : 0;
                $giftJsonRow['invoiceqty'] = Number::decimal($totalItemCount);
                $giftJsonRowMerge          = array();

                $itemPackageList[] = array(
                    'packageDtlId'      => $giftJsonRow['packagedtlid'],
                    'qty'               => $totalItemCount, 
                    'discountPolicyId'  => $giftJsonRow['policyid'], 
                    'isDelivery'        => $giftJsonRow['isDelivery']
                );

                if ($giftJsonRow['coupontypeid'] == 1 || $giftJsonRow['coupontypeid'] == 5 || $giftJsonRow['coupontypeid'] == 6) {

                    for ($ii = 0; $ii < $giftJsonRow['invoiceqty']; $ii++) {
                        array_push($voucherDtl, array(
                            'typeId'    => $giftJsonRow['coupontypeid'], 
                            'amount'    => $giftJsonRow['couponamount'], 
                            'percent'   => issetParam($giftJsonRow['couponpercent']), 
                            'imageFile' => issetParam($giftJsonRow['imagefile']), 
                            'percentamount' => issetParam($giftJsonRow['couponpercentamount']), 
                            'name'          => $giftJsonRow['coupontypename'], 
                            'rowIndex'      => $ii
                        ));
                    }                              
                }
                
                $totalItemCount += 1;

                if ($giftJsonRow['coupontypeid'] == '') {

                    $giftJsonRow['isgift']     = 1;
                    $giftJsonRow['employeeId'] = $employeeId; 
                    $giftJsonRow['itemid']     = $giftJsonRow['promotionitemid']; 
                    $giftJsonRow['jobid']      = $giftJsonRow['jobid']; 

                    if ($giftJsonRow['isservice'] == 1 && $giftJsonRow['jobid'] != '') {

                        $serviceDtl[] = array(
                            'jobId' => $giftJsonRow['jobid'], 
                            'RPR_KEY' => array(
                                'customerId' => $headerParams['customerId'], 
                                'jobid' => $giftJsonRow['jobid'], 
                                'purchaseStoreId' => $storeId
                            )
                        );
                    }

                    $itemGiftPrice = $giftJsonRow['saleprice'];

                    if ($itemGiftPrice > 0 && ($giftJsonRow['discountamount'] > 0 || $giftJsonRow['discountpercent'] > 0)) {

                        $giftDiscountAmount = $itemGiftPrice;

                        if ($giftJsonRow['discountamount'] > 0) {

                            $giftDiscountAmount = $giftJsonRow['discountamount'];

                        } elseif ($giftJsonRow['discountpercent'] > 0) {

                            $giftDiscount = ($giftJsonRow['discountpercent'] / 100) * $itemGiftPrice;
                            $giftDiscountAmount = $itemGiftPrice - $giftDiscount;
                        }

                        $itemGiftPrice       = $itemGiftPrice - $giftDiscountAmount;
                        $giftLineTotalAmount = $itemGiftPrice * $itemQtyGift;
                        $giftLineTotalVat    = number_format(($giftLineTotalAmount - ($giftLineTotalAmount / 1.1)), 2, '.', '');

                        if ($totalBonusAmount > 0) {

                            $giftUnitDiscount  = ($dtlBonusPercent / 100) * $itemGiftPrice;
                            $giftDtlDiscountAmount = $itemGiftPrice - $giftUnitDiscount;

                            $itemGiftPrice   = $giftDtlDiscountAmount;
                            $giftNoVatPrice  = number_format($giftDtlDiscountAmount / 1.1, 2, '.', '');
                            $giftUnitVat     = number_format($giftDtlDiscountAmount - $giftNoVatPrice, 2, '.', '');

                            $giftLineTotalDiscount = $giftUnitDiscount * $itemQtyGift;
                            $giftLineTotalAmount = $giftDtlDiscountAmount * $itemQtyGift;

                            $giftLineTotalVat = $giftLineTotalAmount - $giftLineTotalAmount / 1.1;

                        } else {
                            $giftLineTotalDiscount = 0;
                            $giftUnitVat = number_format($itemGiftPrice - (number_format($itemGiftPrice / 1.1, 2, '.', '')), 2, '.', '');
                        }

                        if ($isVatCalc == false) {
                            $itemGiftPrice = $itemGiftPrice - $giftUnitVat;
                            $giftLineTotalAmount = $giftLineTotalAmount - $giftLineTotalVat;

                            $giftUnitVat = 0;
                            $giftLineTotalVat = 0;
                        }

                        $stocks .= "{
                            'code': '" . self::apiStringReplace($giftJsonRow['itemcode']) . "',
                            'name': '" . self::apiStringReplace($giftJsonRow['itemname']) . "',
                            'measureUnit': '" . self::convertCyrillicMongolia('ш') . "',
                            'qty': '" . sprintf("%.2f", $itemQtyGift) . "',
                            'unitPrice': '" . sprintf("%.2f", $itemGiftPrice) . "',
                            'totalAmount': '" . sprintf("%.2f", $giftLineTotalAmount) . "',
                            'cityTax': '0.00',
                            'vat': '" . sprintf("%.2f", $giftLineTotalVat) . "',
                            'barCode': '" . $giftJsonRow['barcode'] . "'
                        }, ";  

                        $row = array(
                            'cityTax'        => '', 
                            'itemName'       => $giftJsonRow['itemname'], 
                            'salePrice'      => $itemGiftPrice, 
                            'itemQty'        => $itemQtyGift, 
                            'totalPrice'     => $giftLineTotalAmount, 
                            'unitReceivable' => 0, 
                            'maxPrice'       => 0, 
                            'isDelivery'     => $giftJsonRow['isDelivery']
                        );
                        $itemPrintList .= self::{$itemPrintRenderFncName}($row);

                        $giftJsonRow['saleprice'] = $itemGiftPrice;
                        $giftJsonRow['unitPrice'] = $itemGiftPrice;
                        $giftJsonRow['lineTotalPrice'] = $giftLineTotalAmount;

                        $giftJsonRow['unitAmount'] = $itemGiftPrice;
                        $giftJsonRow['lineTotalAmount'] = $giftLineTotalAmount;

                        $giftJsonRow['percentVat'] = 10;
                        $giftJsonRow['unitVat'] = $giftUnitVat;
                        $giftJsonRow['lineTotalVat'] = number_format($giftJsonRow['unitVat'] * $itemQtyGift, 2, '.', '');

                        $giftJsonRow['percentDiscount'] = 0;
                        $giftJsonRow['unitDiscount'] = 0;
                        $giftJsonRow['lineTotalDiscount'] = $giftLineTotalDiscount;

                        $giftJsonRowMerge['invoiceqty'] = 1;
                        $giftJsonRowMerge['unitPrice'] = $itemGiftPrice;
                        $giftJsonRowMerge['lineTotalPrice'] = $itemGiftPrice;

                        $giftJsonRowMerge['unitAmount'] = $itemGiftPrice;
                        $giftJsonRowMerge['lineTotalAmount'] = $itemGiftPrice;

                        $giftJsonRowMerge['percentVat'] = 10;
                        $giftJsonRowMerge['unitVat'] = number_format($itemGiftPrice - (number_format($itemGiftPrice / 1.1, 2, '.', '')), 2, '.', '');
                        $giftJsonRowMerge['lineTotalVat'] = $giftJsonRowMerge['unitVat'];

                        $sumTotal += $giftLineTotalAmount;

                    } else {
                        $giftListPayment .= self::giftPrintRow($giftJsonRow);
                    }

                    /*if ($giftJsonRow['jobid'] == '') {

                        if ($itemQtyGift > 1) {

                            $giftJsonRowMerge['invoiceqty'] = 1;

                            if ($giftJsonRow['isDelivery'] == 1 && $isServicePos != '1') {

                                $giftJsonRow['warehouseId'] = ''; 
                                $giftJsonRowLoop = array_merge($giftJsonRow, $giftJsonRowMerge);

                                for ($gt = 0; $gt < $itemQtyGift; $gt++) {
                                    $deliveryDtl[] = $giftJsonRowLoop;
                                }

                            } else {
                                $giftJsonRow['warehouseId'] = ''; 
                                $giftJsonRowLoop = array_merge($giftJsonRow, $giftJsonRowMerge);

                                for ($gt = 0; $gt < $itemQtyGift; $gt++) {
                                    $noDeliveryDtl[] = $giftJsonRowLoop;
                                }
                            }

                        } else {
                            if ($giftJsonRow['isDelivery'] == 1 && $isServicePos != '1') {
                                $giftJsonRow['warehouseId'] = ''; 
                                $deliveryDtl[] = $giftJsonRow;
                            } else {
                                $giftJsonRow['warehouseId'] = ''; 
                                $noDeliveryDtl[] = $giftJsonRow;
                            }
                        }
                    }*/

                    $itemGiftList[] = $giftJsonRow;

                } else {                
                    $giftListPayment .= self::giftPrintRow($giftJsonRow);
                }
            }

            $paramsDtl['SDM_SALES_ORDER_ITEM_PACKAGE'] = $itemPackageList;
            $paramsDtl['POS_SM_SALES_INVOICE_DETAIL'] = $itemGiftList;
        }

        return $giftListPayment;
    }

    public function getMatrixDiscoundModel($param) {
        
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'DISCOUNT_BY_MATRICE_004', $param);
        
        if ($result['status'] == 'success') {
            return $result['result'];
        } 
        return false;
    }     

    public function getCardNumberByPhoneNumberModel() {
        
        $param = array(
            'systemMetaGroupId' => self::$getBonusCardListDvId,
            'ignorePermission' => 1, 
            'showQuery' => 0,
            'criteria' => array(
                'phoneNumber' => array(
                    array(
                        'operator' => '=',
                        'operand' => Input::post('cardPhoneNumber')
                    )
                )
            )
        );

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result']) && isset($data['result'][0])) {
            
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);
            
            if (count($data['result']) === 1) {
                $row     = $data['result'][0];
                
                if ($row['isactive'] == '1') {
                    
                    $response = $row;
                    $response['status'] = 'success';
                    
                } else {
                    $response = array('status' => 'warning', 'message' => Lang::line('POS_0113'));
                }

            } else {
                $response = array('status' => 'warning', 'message' => 'Энэ дугаар дээр олон карт үүссэн байна!');
            }

        } else {
            $response = array('status' => 'warning', 'message' => Lang::line('POS_0114'));
        }
        
        return $response;
    }
    
    private function actionMultipleLocker($lockerIds, $invoiceId) {
        parse_str($lockerIds, $lockerIdsArray);

        foreach ($lockerIdsArray['multipleLockerId'] as $locker) {
            $lockInfo = explode('_', $locker);
            $lockId = $lockInfo[0];
            $lockCode = $lockInfo[1];

            $param = array(
                'filterCardId' => $lockId,
                'salesInvoiceId' => $invoiceId
            );
            $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'GET_SALES_DATA_FOR_LOCKER_004', $param);
            
            if ($result['status'] == 'success') {
                $result2 = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'fitLoyMemberBook_DV_0012', array(
                    'booknumber' => issetVar($result['result']['invoicenumber']),
                    'cardId' => $lockId,
                    'itemId' => $result['result']['itemid'],
                    'commandName' => $result['result']['type'],
                    'endDateLoy' => $result['result']['durationtime'],
                    'id' => $result['result']['memberbookid'],
                    'url' => $result['result']['command'],
                    'warningDate' => $result['result']['warningtime'],
                ));

                if ($result2['status'] == 'success') {

                    $this->ws->runSerializeResponse(self::$gfServiceAddress, 'fitMetaDmRecordMap_DV_001_01', array(
                        'srcRecordId' => $invoiceId,
                        'trgRecordId' => $result2['result']['id']
                    ));                    
                }
            } 
        }
    }
    
    public function sendMailModel($emailTo, $invId) {
        $emailSubject = Config::getFromCacheDefault('POS_SEND_LOTTERY_BY_MAIL_SUBJECT', null, 'Veritech ERP');                            
        
        $emailBody = (new Mdtemplate())->getTemplateByArguments('1603246065786', 'selfDvId', array('id' => $invId));
        $emailBody = '<div style="width:450px">' . html_entity_decode($emailBody) . '</div>';

        $emailBodyContent = file_get_contents('middleware/views/metadata/dataview/form/email_templates/selectionRows.html');
        $emailBodyContent = str_replace('{htmlTable}', $emailBody, $emailBodyContent);
            
        includeLib('Mail/PHPMailer/v2/PHPMailerAutoload');

        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        
        if (!defined('SMTP_USER')) {
                
            $mail->SMTPAuth = false;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

        } else {
            $mail->SMTPAuth = (defined('SMTP_AUTH') ? SMTP_AUTH : true);
            
            if ($mail->SMTPAuth) {
                $mail->Username = SMTP_USER; 
                $mail->Password = SMTP_PASS; 
            } else {
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
            }
        }
        
        $mail->SMTPSecure = (defined('SMTP_SECURE') ? SMTP_SECURE : false);
        $mail->Host = SMTP_HOST;
        if (defined('SMTP_HOSTNAME') && SMTP_HOSTNAME) {
            $mail->Hostname = SMTP_HOSTNAME;
        }        
        $mail->Port = SMTP_PORT;
        $mail->setFrom(EMAIL_FROM, EMAIL_FROM_NAME);
        $mail->Subject = $emailSubject;
        $mail->isHTML(true);
        $mail->msgHTML($emailBodyContent);
        $mail->AltBody = 'Veritech ERP - ' . $emailSubject;
        
        $response = array('status' => 'success', 'message' => Lang::line('msg_mail_success'));
                
        $email = trim($emailTo);

        if ($email) {

            $mail->addAddress($email);

            if (!$mail->send()) {
                $response = array('status' => 'error', 'message' => $mail->ErrorInfo);
            }
        }

        $mail->clearAllRecipients();

        $ipAddress   = get_client_ip();
        $userId      = Ue::sessionUserId();            
        $currentDate = Date::currentDate();        
        
        if ($response['status'] == 'success') {                
            $data = array(
                'ID'          => getUID(), 
                'EMAIL'       => $email, 
                'ACTION_DATE' => $currentDate, 
                'STATUS'      => 'sent', 
                'FROM_IP'     => $ipAddress, 
                'RECORD_ID'   => $invId, 
                'USER_ID'     => $userId
            );

            $this->db->AutoExecute('EML_EMAIL_LOG', $data);
        } else {
            $data = array(
                'ID'          => getUID(), 
                'EMAIL'       => $email, 
                'ACTION_DATE' => $currentDate, 
                'STATUS'      => 'error', 
                'FROM_IP'     => $ipAddress, 
                'RECORD_ID'   => $invId, 
                'USER_ID'     => $userId
            );

            $this->db->AutoExecute('EML_EMAIL_LOG', $data);            
        }

        return $response;
    }    
    
    function upointCheckInfoModel($params = '') {
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'upoint_check_info', $params);

        if ($result['status'] == 'success') {
            return array('status' => 'success', 'data' => $result['result']);
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }        
    }
    
    function upointPaymentTransaction($paymentData, $upointAmount, $refNumber, $currentDate, $upointCalcAmount, $cashAmount, $itemData) {
        $paramsU = array(
            'terminal_id' => Session::get(SESSION_PREFIX.'cashRegisterCode'),
            'card_number' => $paymentData['upointCardNumber'],
            'mobile' => $paymentData['upointMobile'],
            'date' => Date::formatter($currentDate, 'Y/m/d H:i:s'),
            'bill_number' => $refNumber,
            'spend_amount' => Number::decimal($upointAmount),
            'bonus_amount' => Number::decimal($upointCalcAmount),
            'total_amount' => Number::decimal($upointCalcAmount) + Number::decimal($upointAmount),
            'cash_amount' => Number::decimal($cashAmount),
        );

        $items = [];
        if ($itemData) {
            foreach ($itemData['itemId'] as $k => $itemId) {
                if (isset($itemData['isCalcUPoint']) && $itemData['isCalcUPoint'][$k] == '1') {
                    array_push($items, [
                        'code' => $itemData['itemCode'][$k],
                        'name' => $itemData['itemName'][$k],
                        'quantity' => $itemData['quantity'][$k],
                        'price' => $itemData['salePrice'][$k],
                        'unit' => $itemData['measureCode'][$k],
                        'total_price' => $itemData['totalPrice'][$k],
                    ]);
                }
            }
            if ($items) {
                $paramsU['items'] = $items;
            }
        }
        
        $bankAmount = Number::decimal($paymentData['bankAmount']);
        if ($bankAmount > 0) {
            $bankAmountDtl = $paymentData['bankAmountDtl'];
            $paramsUBank = array();
            
            foreach ($bankAmountDtl as $b => $bankDtlAmount) {
                
                $bankId         = $paymentData['posBankIdDtl'][$b];
                $bankCode = '';
                $bankDtlAmount  = Number::decimal($bankDtlAmount);
                $param = array(
                    'systemMetaGroupId' => self::$bankInfoByStoreIdDvId,
                    'ignorePermission' => 1, 
                    'showQuery' => 0,
                    'criteria' => array(
                        'bankid' => array(
                            array(
                                'operator' => '=',
                                'operand' => $bankId
                            )
                        )
                    )
                );

                $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

                if (isset($result['result']) && isset($result['result'][0])) {
                    unset($result['result']['aggregatecolumns']);
                    unset($result['result']['paging']);
                    $bankCode = $result['result'][0]['upointbankcode'];
                }       
                        
                if ($bankId != '' && $bankDtlAmount > 0) {
                    
                    $paramsUBank[] = array(
                        'bank_code'        => $bankCode,
                        'non_cash_amount'  => $bankDtlAmount
                    );
                }
            }
            $paramsU['bank'] = $paramsUBank;
        }
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'upoint_process_transaction', $paramsU);

        if ($result['status'] == 'success') {
            return array('status' => 'success', 'data' => $result['result']);
        } else {
            return array('status' => 'warning', 'message' => '<strong>Upoint</strong> ' . $this->ws->getResponseMessage($result));
        }    
    }
    
    public function getLeftSidebarListModel() {
        
        $data = null;
        $param = array(
            'systemMetaGroupId' => '1607662311533867',
            'ignorePermission' => 1, 
            'showQuery' => 0      
        );

        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($result['result']) && isset($result['result'][0])) {
            unset($result['result']['aggregatecolumns']);
            unset($result['result']['paging']);
            $data = $result['result'];
        } else {
            $data = null;
        }
            
        return $data;
    }    
    
    public function getLiftPrintModel($invoiceId) {
                
        $data = null;
        $param = array(
            'systemMetaGroupId' => '1608107268754817',
            'ignorePermission' => 1, 
            'showQuery' => 0,
            'criteria' => array(
                'salesInvoiceId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $invoiceId
                    )
                )
            )                  
        );

        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($result['result']) && isset($result['result'][0])) {
            unset($result['result']['aggregatecolumns']);
            unset($result['result']['paging']);
            $data = $result['result'];
        } else {
            $data = null;
        }
            
        return $data;
    }
    
    public function saveLocationImageModel() {
        $this->db->AutoExecute('WH_LOCATION', array('REGION' => Input::post('location')), 'UPDATE', 'LOCATION_ID = '.Input::post('id'));
        return array('status' => 'success');
    }
    
    public function deleteTableLocationModel() {
        $this->db->AutoExecute('WH_LOCATION', array('REGION' => NULL), 'UPDATE', 'LOCATION_ID = '.Input::post('id'));
        return array('status' => 'success');
    }
    
    public function getInvoiceByEshopModel($qr) {
        
        $param = array(
            'qrCode'      => $qr
        );
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'sdmOrderQrCodeScan_GET_004', $param);

        if ($result['status'] == 'success') {
            
            if (isset($result['result'])) {
                return array('status' => 'success', 'data' => $result['result']);
            } else {
                return array('status' => 'error', 'message' => Lang::line('POS_0115'));
            }
            
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
    }

    public function changeTableRestModel() {
        
        $prm = array(
            'id' => Input::param($_POST['firstTable']['salesorderid']), 
            'oldLocationId' => Input::param($_POST['firstTable']['id']),
            'locationId' => Input::param($_POST['secondTable']['id']),
        );

        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'CHANGE_TABLE_DV_002', $prm);

        self::insertActionLog('CHANGE_TABLE_DV_002['.Input::param($_POST['firstTable']['salesorderid']).']-Online_POS Shiree solih', $prm, $result);        

        if ($result['status'] == 'success') {
            $result = array('status' => $result['status']);
        } else {
            $result = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }

        return $result;
    }    

    public function returnTableRestModel() {
        
        $prm = array(
            'id' => Input::post('id')
        );

        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'SDM_ORDER_BOOK_DV_005', $prm);

        self::insertActionLog('SDM_ORDER_BOOK_DV_005['.$prm['id'].']-Online_POS Shiree ustgah', $prm, $result);

        if ($result['status'] == 'success') {
            $result = array('status' => $result['status']);
        } else {
            $result = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }

        return $result;
    }    

    public function nextBillTableRestModel() {
        
        $prm = array(
            'id' => Input::post('id'),
            'isPending' => '1',
            'deliveryContactName' => Input::post('name'),
            'description' => Input::post('description'),
        );

        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'UPDATE_SOB_IS_PENDING_002', $prm);

        self::insertActionLog('UPDATE_SOB_IS_PENDING_002['.$prm['id'].']-Online_POS Daraa tootsoo', $prm, $result);        

        if ($result['status'] == 'success') {
            $result = array('status' => $result['status']);
        } else {
            $result = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }

        return $result;
    }    
    
    public function posCalcRow($row) {
        $row = Arr::changeKeyLower($row);
        $iscitytax   = $row['iscitytax'];
        $isvat       = $row['isvat'];
        $isdiscount  = $row['unitdiscount'] ? '1' : '0';
        $unitdiscount = $row['unitdiscount']; 
        $qty         = $row['invoiceqty']; 
        $saleprice   = $row['unitprice']; 
        $totalprice  = $qty * $saleprice;

        if ($isdiscount == '1') {
            if ($unitdiscount <= $saleprice) {
                $saleprice = $saleprice - $unitdiscount;
                $row['totaldiscount'] = $saleprice * $qty;
            }
        }

        $totalprice  = $qty * $saleprice;

        if ($iscitytax == '1') {
            $row['citytax'] = number_format($saleprice / 112 * 2, 6, '.', '');
            $row['unitcitytax'] = number_format($saleprice / 112 * 2, 6, '.', '');
            $row['linetotalcitytax'] = number_format($totalprice / 112 * 2, 6, '.', '');
            $row['linetotalcitytaxamount'] = number_format($totalprice / 112 * 2, 6, '.', '');
        }

        if ($isvat == '1' && $iscitytax == '1') {
            $row['novatprice'] = number_format($saleprice - ($saleprice / 11.2), 6, '.', '');
            $row['linetotalvat'] = number_format($totalprice / 11.2, 6, '.', '');
        } else if ($isvat == '1') {
            $row['novatprice'] = number_format($saleprice - ($saleprice / 11), 6, '.', '');
            $row['linetotalvat'] = number_format($totalprice / 11, 6, '.', '');
        }    

        $row['linetotalprice'] = $totalprice;  
        $row['linetotalamount'] = number_format($saleprice * $qty, 6, '.', '');  

        return $row;
    }    
    
    public function mergeTableRestModel() {
        
        $prm = array(
            'salesOrderId' => Input::post('idsString') . ($_POST['secondTable']['salesorderid'] ? ','.$_POST['secondTable']['salesorderid'] : ''),
            'salespersonId' => Input::post('waiterId'),
            'locationId' => Input::param($_POST['secondTable']['id']),
        );

        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'AGGREGATE_RES_ORDERS', $prm);

        self::insertActionLog('AGGREGATE_RES_ORDERS['.$prm['salesOrderId'].']-Online_POS Shiree niiluuleh', $prm, $result);

        if ($result['status'] == 'success') {
            $result = array('status' => $result['status']);
        } else {
            $result = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }

        return $result;
    }       
    
    public function splitCalculateRestModel() {
        
        parse_str($_POST['serialize'], $formData);
        $orderData = $_POST['data']['orderData'];
        $getItemList = $_POST['data']['orderData']['data']['pos_item_list_get'];
        $spliteItems = array();
        $totalAmt = 0;

        $prm = array(
            'id' => Input::post('id')
        );
        
        foreach ($getItemList as $key => $row) {
            if ($formData['splitInput'][$key]) {
                $row['invoiceqty'] = $formData['splitInput'][$key];
                $row['invoiceqty2'] = -$formData['splitInput'][$key];
                $row['approvedQty'] = $formData['splitInput'][$key];
                $row['unitprice'] = $row['saleprice'];
                $row['unitamount'] = $row['saleprice'];
                //$row['parentId'] = $row['salesorderdetailid'];
                $row['itemid'] = $row['id'];
                unset($row['id']);
                unset($row['salesorderdetailid']);
                $calcedRow = $this->posCalcRow($row);
                $totalAmt += $calcedRow['linetotalamount'];
                array_push($spliteItems, $calcedRow);
            }
        }

        $prm = array(
            'totalAmount' => $totalAmt,
            'cashRegisterId' => Session::get(SESSION_PREFIX.'cashRegisterId')
        );
        $resultServiceCharge = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'posCheckServiceCharge_DV_004', $prm);
        if ($resultServiceCharge['status'] == 'success') {
            if (isset($resultServiceCharge['result']['pos_item_list_get'])) {
                array_push($spliteItems, $this->posCalcRow($resultServiceCharge['result']['pos_item_list_get'][0]));
            }
        }

        $orderData['data']['pos_item_list_get'] = $spliteItems;
        $orderData['islastsplit'] = $formData['isLastCalcSplit'];        

        //$result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'SDM_SALES_ORDER_ITEM_DTL_MAIN_DV_001', $prm);

        $result = array('status' => 'success', 'data' => $orderData);

        return $result;
    }       
    
    public function pieceCalculateRestModel() {
        
        parse_str($_POST['serialize'], $formData);
        $orderData = $_POST['data']['orderData'];
        $getItemList = $_POST['data']['orderData']['data']['pos_item_list_get'];
        $spliteItemsFrom = array();
        $spliteItemsTo = array();
        $fromTotal = 0;
        $fromSubTotal = 0;
        $fromVat = 0;
        $fromCityTax = 0;
        $toTotal = 0;
        $toSubTotal = 0;
        $toVat = 0;
        $toCityTax = 0;
        
        foreach ($getItemList as $key => $row) {
            if ($formData['splitInput'][$key]) {
                $invoiceqty = $row['invoiceqty'];
                $row['invoiceqty'] = $formData['splitInput'][$key];
                $row['unitprice'] = $row['saleprice'];
                $row['itemid'] = $row['id'];
                unset($row['id']);
                
                $calcRow = $this->posCalcRow($row);
                $toSubTotal += $calcRow['linetotalprice'];
                $toTotal += $calcRow['linetotalamount'];
                $toVat += $calcRow['vatprice'] - $calcRow['novatprice'];
                $calcRow['unitVat'] = $calcRow['vatprice'] - $calcRow['novatprice'];
                $calcRow['unitAmount'] = $row['saleprice'];
                $toCityTax += $calcRow['citytax'] ? $calcRow['citytax'] : 0;
                array_push($spliteItemsTo, $calcRow);
                
                if ($formData['splitInput'][$key] < $invoiceqty) {
                    $row['invoiceqty'] = $invoiceqty - $formData['splitInput'][$key];

                    $calcRow = $this->posCalcRow($row);
                    $fromSubTotal += $calcRow['linetotalprice'];
                    $fromTotal += $calcRow['linetotalamount'];
                    $fromVat += $calcRow['vatprice'] - $calcRow['novatprice'];
                    $calcRow['unitVat'] = $calcRow['vatprice'] - $calcRow['novatprice'];
                    $calcRow['unitAmount'] = $row['saleprice'];
                    $fromCityTax += $calcRow['citytax'] ? $calcRow['citytax'] : 0;
                    array_push($spliteItemsFrom, $calcRow);                    
                }
            } else {
                $row['unitprice'] = $row['saleprice'];
                $calcRow = $this->posCalcRow($row);
                $fromSubTotal += $calcRow['linetotalprice'];
                $fromTotal += $calcRow['linetotalamount'];
                $fromVat += $calcRow['vatprice'] - $calcRow['novatprice'];
                $calcRow['unitVat'] = $calcRow['vatprice'] - $calcRow['novatprice'];
                $calcRow['unitAmount'] = $row['saleprice'];
                $fromCityTax += $calcRow['citytax'] ? $calcRow['citytax'] : 0;
                $calcRow['itemid'] = $row['id'];
                array_push($spliteItemsFrom, $calcRow);                   
            }
        }
        
        $currentDate = Date::currentDate();        
        $storeId = Session::get(SESSION_PREFIX.'storeId');
        
        $paramsFrom = array(
            'salesOrderId'         => Input::post('salesorderid'),
            'salesPersonId'        => issetParam($orderData['data']['salespersonid']),
            'totalCityTaxAmount'   => $fromCityTax, 
            'subTotal'             => $fromSubTotal, 
            'discount'             => 0, 
            'vat'                  => $fromVat, 
            'total'                => $fromTotal, 
            'dtl'                  => $spliteItemsFrom
        );        
        $paramsTo = array(
            'salesOrderId'         => '',
            'bookTypeId'           => '204', 
            'invoiceDate'          => $currentDate, 
            'createdDateTime'      => $currentDate, 
            'storeId'              => $storeId,
            'totalCityTaxAmount'   => $toCityTax, 
            'subTotal'             => $toSubTotal, 
            'discount'             => 0, 
            'vat'                  => $toVat, 
            'total'                => $toTotal, 
            'locationId'           => '', 
            'salesPersonId'        => '',
            'dtl'                  => $spliteItemsTo
        );        
        
        $prm = array(
            'from' => $paramsFrom,
            'to' => $paramsTo,
        );

        return $prm;
    }       
    
    public function splitCalculateSaveRestModel() {
        $dtlDataArr = [];
        $dtlData = $_POST['data']['data']['pos_item_list_get'];

        foreach($dtlData as $row) {
            $row['linetotalamount'] = -$row['linetotalamount'];
            $row['linetotalprice'] = -$row['linetotalprice'];
            $row['unitprice'] = -$row['unitprice'];
            $row['unitamount'] = -$row['unitamount'];
            array_push($dtlDataArr, $row);
        }

        $params = array(
            'id' => Input::post('id'),
            'dtl' => $_POST['data']['data']['pos_item_list_get'],
        );
        
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'SDM_ORDER_BOOK_TMP_POS_DV_001', $params);

        self::insertActionLog('SDM_ORDER_BOOK_TMP_POS_DV_001['.$params['id'].']-Online_POS Tootsoo salgah', $params, $result);

        if ($result['status'] == 'success') {

            if ($result['result']['dtl']) {
                foreach ($result['result']['dtl'] as $deleteDtl) {
                    $this->ws->runSerializeResponse(self::$gfServiceAddress, 'POS_META_DM_RECORD_MAP_MAIN_DV_001', array('srcRecordId' => Input::post('invoiceId'), 'trgRecordId' => $deleteDtl['id']));
                }               
            }               

            $result = array('status' => $result['status']);
        } else {
            $result = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }              

        return $result;
    }       
    
    public function pieceCalculateSaveRestModel() {
        $postdata = $_POST['data'];
        $postdata['to']['salesOrderId'] = $_POST['selectedRow']['salesorderid'];
        $postdata['to']['invoiceNumber'] = self::getPosInvoiceNumber('1522946993342', array('bookTypeId' => '204', 'storeId' => Session::get(SESSION_PREFIX.'storeId')));;
        $postdata['to']['locationId'] = $_POST['selectedRow']['id'];
        // dd($postdata);
        // echo json_encode($postdata); die('sdfds');        
        
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'POS_SALES_PERSON_ORDER_MERGE_001', $postdata);

        self::insertActionLog('POS_SALES_PERSON_ORDER_MERGE_001-Online_POS Shiree hesegchilj niiluuleh', $postdata, $result);

        if ($result['status'] == 'success') {
            $result = array('status' => $result['status']);
        } else {
            $result = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }        

        return $result;
    }       
    
    public function customerDiscountModel() {
        parse_str($_POST['itemData'], $itemData);

        if (empty($itemData)) return null;
        
        $itemId = $itemData['itemId'];
        $itemIds = $sectionIds = $noSectionId = '';
        
        foreach ($itemId as $k => $itemId) {
            if ($itemData['sectionId'][$k])
                $itemIds .= $itemId . '-' . $itemData['sectionId'][$k] . '|';
            else
                $noSectionId .= $itemData['itemCode'][$k] . ',';
        }        

        $cashRegisterId = Session::get(SESSION_PREFIX.'cashRegisterId');
        $storeId = Session::get(SESSION_PREFIX.'storeId');        
        
        $param = array(
            'systemMetaGroupId' => '1616381856220017',
            'showQuery' => 0,
            'ignorePermission' => 1, 
            'criteria' => array(
                'customerId' => array(
                    array(
                        'operator' => '=',
                        'operand' => Input::post('customerId')
                    )
                ),
                'filterIds' => array(
                    array(
                        'operator' => 'IN',
                        'operand' => rtrim($itemIds, ',')
                    )
                ),
                'storeId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $storeId
                    )
                ),
                'cashRegisterId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $cashRegisterId
                    )
                )
            )
        );

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result']) && isset($data['result'][0])) {
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);
            return array('data' => $data['result'], 'noSectionId' => $noSectionId);
        } else {
            return null;
        }
    }       

    public function kitchenIsPrintModel() {
        
        $dtl = array();
        $prm = array(
            'id' => Input::post('id')
        );

        foreach($_POST['pos_sdm_sales_order_item_dtl'] as $row) {
            array_push($dtl, array(
                'id' => $row['id'],
                'salesOrderId' => $prm['id'],
                'isPrint' => ''
            ));
        }
        $prm['SDM_SALES_ORDER_ITEM_DTL'] = $dtl;

        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'UPDATE_SSOID_IS_PRINT_002', $prm);

        return $result;
    }        

    public function insertActionLog($processCode, $param, $response) {
        $id = getUID();
        $params = array(
            'ID'               => $id, 
            'WEB_SERVICE_NAME' => $processCode, 
            'WEB_SERVICE_URL'  => '', 
            'CREATED_DATE'     => Date::currentDate(), 
            'USER_ID'          => Ue::sessionUserKeyId()
        );

        $this->db->AutoExecute('SYSINT_SERVICE_METHOD_LOG', $params);
        $this->db->UpdateClob('SYSINT_SERVICE_METHOD_LOG', 'REQUEST_STRING', json_encode($param), 'ID = '.$id);             
        $this->db->UpdateClob('SYSINT_SERVICE_METHOD_LOG', 'RESPONSE_STRING', json_encode($response), 'ID = '.$id);             
    }

    public function getLimitBonusAmountModel() {
        return $this->db->GetAll("
            SELECT 
                VAL.CONFIG_VALUE,
                VAL.CRITERIA  
            FROM CONFIG CFG 
                INNER JOIN CONFIG_VALUE VAL ON VAL.CONFIG_ID = CFG.ID 
            WHERE CFG.CODE = 'POS_BONUS_MAX_CALC_AMOUNT'");        
    }

    public function paymentTypeLocalExpModel() {
        return $this->db->GetRow("
            SELECT 
                VAL.CONFIG_VALUE,
                VAL.CRITERIA  
            FROM CONFIG CFG 
                INNER JOIN CONFIG_VALUE VAL ON VAL.CONFIG_ID = CFG.ID 
            WHERE CFG.CODE = 'POS_IS_DISABLE_PAYMENT_LOCALEXPENSE'");        
    }

    public function deleteDetailOrderItemModel() {
        $param = array(
            'systemMetaGroupId' => '1652850365641707',
            'showQuery' => 0,
            'ignorePermission' => 1, 
            'criteria' => array(
                'salesOrderId' => array(
                    array(
                        'operator' => '=',
                        'operand' => Input::post('id')
                    )
                ),                
                'itemId' => array(
                    array(
                        'operator' => '=',
                        'operand' => Input::post('itemId') 
                    )
                ),
                'customerId' => array(
                    array(
                        'operator' => '=',
                        'operand' => Input::post('customerId') 
                    )
                )
            )
        );        
        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        $employeeId = Input::post('employeeId');

        if (isset($data['result']) && isset($data['result'][0])) {
            unset($data['result']['aggregatecolumns']);
            $deleteRows = $data['result'];
            foreach ($deleteRows as $deleteDtl) {
                $this->ws->runSerializeResponse(self::$gfServiceAddress, 'DELETE_SDM_SALES_ORDER_ITEM_DTL_DV_002', array('id' => $deleteDtl['salesorderdetailid'], 'employeeId' => $employeeId));
                $this->ws->runSerializeResponse(self::$gfServiceAddress, 'DELETE_SDM_SALES_ORDER_ITEM_DTL_DV_005', array('id' => $deleteDtl['salesorderdetailid']));
            }            
            return ['status' => 'success'];
        }
        
        return null;
    }

    public function resendCreatePrescriptionExcelModel() {
        return '';
        $emdData = $this->db->GetAll("SELECT * FROM EMD_TABLET ORDER BY RECEIPT_NUMBER"); 
        $emdDiscountData = $this->db->GetAll("SELECT * FROM IM_DISCOUNT_DRUG");

        $this->db->BeginTrans();

        foreach ($emdData as $emdRow) {

            $TBLT_ID = '';
            $rows = $this->db->GetAll("
                SELECT * FROM SM_SALES_INVOICE_PRESCRIPTION WHERE RECEIPT_NUMBER = ".$this->db->Param(0), 
                array($emdRow['RECEIPT_NUMBER'])
            );
            
            foreach ($rows as $row) { 
                $sendJson = json_decode($row['SEND_JSON'], true);
                $dtlJson = json_decode($row['DETAIL_JSON'], true);

                foreach ($sendJson['ebarimtDetails'] as $srow) {
                    if ($emdRow['BAR_CODE'] == $srow['barCode']) {
                        foreach ($emdDiscountData as $drow) {
                            if ($srow['barCode'] == $drow['TBLTBARCODE']) {
                                $TBLT_ID = $drow['ID'];
                            }
                        }
                    }
                }

                $updateQry = "UPDATE EMD_TABLET SET TBLT_ID = '".$TBLT_ID."' WHERE ID = ".$emdRow['ID'];
                $this->db->Execute($updateQry);                     
            }
        }

        $this->db->CommitTrans();
        return 'Success';
    }	 

    public function resendCreatePrescriptionModel($id) {
        /*$emdData = $this->db->GetAll("
            SELECT * 
            FROM SM_SALES_INVOICE_PRESCRIPTION 
            WHERE SALES_INVOICE_ID = $id"
        );*/ 
        /*$emdData = $this->db->GetAll("
             SELECT * 
             FROM SM_SALES_INVOICE_PRESCRIPTION 
             WHERE TO_CHAR(RECEIPT_DATE, 'YYYY-MM-DD') BETWEEN '2022-10-17' AND '2022-10-20' 
             ORDER BY RECEIPT_DATE"
        );*/ 
		$emdData = $this->db->GetAll("
            SELECT AA.*, BB.CREATED_DATE_TIME, CC.CLIENT_ID, CC.CLIENT_SECRET
            FROM SM_SALES_INVOICE_PRESCRIPTION AA
            INNER JOIN SM_SALES_INVOICE_HEADER BB ON BB.SALES_INVOICE_ID = AA.SALES_INVOICE_ID
            LEFT JOIN SM_STORE CC ON CC.STORE_ID = BB.STORE_ID
            WHERE AA.PREVIOUS_ID IS NOT NULL
            ORDER BY BB.CREATED_DATE_TIME");		
        // $emdDiscountData = $this->db->GetAll("SELECT * FROM IM_DISCOUNT_DRUG");
        
        // $cashierInfo = null;
        // $sessionEmployeeId = Ue::sessionEmployeeId();        
        // $param = array(
        //     'systemMetaGroupId' => self::$cashierInfoByEmpIdDvId,
        //     'showQuery' => 0,
        //     'ignorePermission' => 1, 
        //     'criteria' => array(
        //         'employeeId' => array(
        //             array(
        //                 'operator' => '=',
        //                 'operand' => $sessionEmployeeId
        //             )
        //         )
        //     )
        // );        
        // $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);        
        
        // if (isset($data['result']) && isset($data['result'][0])) {
        //     $cashierInfo = $data['result'][0];
        // }        
        
        $this->db->BeginTrans();

        foreach ($emdData as $emdRowKey => $emdRow) {
            $getToken = self::emdGetToken($emdRow['CLIENT_ID'], $emdRow['CLIENT_SECRET']);
            if (isset($getToken['access_token'])) {
                $TBLT_ID = '';            
                $sendJson = json_decode($emdRow['SEND_JSON'], true);
                $sum = 0;

                //$sendJson['insAmt'] = number_format($sendJson['insAmt'], 0, '.', '');
                foreach ($sendJson['ebarimtDetails'] as $skey => $srow) {
                    $sum += number_format($sendJson['ebarimtDetails'][$skey]['insAmt'], 0, '.', '');
                    $sendJson['ebarimtDetails'][$skey]['insAmt'] = number_format($sendJson['ebarimtDetails'][$skey]['insAmt'], 0, '.', '');				
                    // foreach ($emdDiscountData as $drow) {
                    //     if ($srow['barCode'] == $drow['TBLTBARCODE']) {
                    //         $TBLT_ID = $drow['ID'];
                    //         $sendJson['ebarimtDetails'][$skey]['tbltId'] = $TBLT_ID;
                    //     }
                    // }
                }
                            
                /*foreach ($sendJson['ebarimtDetails'] as $skey => $srow) {
                    $sum += number_format($sendJson['ebarimtDetails'][$skey]['insAmt'], 0, '.', '');
                    $sendJson['ebarimtDetails'][$skey]['insAmt'] = number_format($sendJson['ebarimtDetails'][$skey]['insAmt'], 0, '.', '');
                }*/		
                $sendJson['insAmt'] = $sum;			
                
                $accessToken = $getToken['access_token'];
                $dataParams = $sendJson;
                $getSendData = self::emdSendData($accessToken, $dataParams);            
                
                $errorMessage = 'ok';
                if (isset($getSendData['msg']) && isset($getSendData['code']) && $getSendData['code'] == '200') {
                } else {

                    if (isset($getSendData['error_description'])) {
                        $errorMessage = 'Send: '.(isset($getSendData['error']) ? $getSendData['error'] : 'null').' - '.$getSendData['error_description'];
                    } else {
                        $errorMessage = 'Send: Response Null';
                    }
                }            
                $updateQry = "UPDATE SM_SALES_INVOICE_PRESCRIPTION SET PREVIOUS_ID = 2, ERROR_MSG = '$errorMessage' WHERE ID = ".$emdRow['ID'];
                $this->db->UpdateClob('SM_SALES_INVOICE_PRESCRIPTION', 'SEND_JSON', json_encode($dataParams, JSON_UNESCAPED_UNICODE), 'ID = '.$emdRow['ID']);
                $this->db->Execute($updateQry);  
            }
        }

        $this->db->CommitTrans();
        return 'Success';
    }
    
    public function qPayGetInvoiceQrModel($params) {
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'qpay_v2_createInvoice_simple', $params);

        if ($result['status'] == 'success') {
            return array('status' => 'success', 'qrcode' => self::getQpayQrCodeImg($result['result']['qr_image'], '250px'), 'traceNo' => $result['result']['invoice_id']);
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
    }    
    
    public function tokiPayGetInvoiceQrModel($params) {
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'tokiPayGenerateQrAPI', $params);

        if ($result['status'] == 'success') {
            return array('status' => 'success', 'qrcode' => self::getQrCodeImg($result['result']['data']['requestid'], '250px'), 'traceNo' => $result['result']['data']['requestid']);
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
    }    
    
    public function qpayCheckQrCodeModel($params) {
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'qpay_v2_checkPayment', $params);

        if ($result['status'] == 'success') {
            if ($result['result']['count']) {
                return array('status' => 'success', 'message' => 'Successfully');
            } else {
                return array('status' => 'error', 'message' => 'Waiting');
            }
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
    }    
    
    public function tokipayCheckQrCodeModel($params) {
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'tokiPayGetPaymentStatusAPI', $params);

        if ($result['status'] == 'success') {
            if ($result['result']['data']['status'] == 'COMPLETED') {
                return array('status' => 'success', 'message' => 'Successfully');
            } else {
                return array('status' => 'error', 'message' => 'Waiting');
            }
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
    }    
    
    public function test__ss() {
        
        $prm = array(
            'imageWidth' => 1200,
            'imageHeight' => 900,
            'url' => 'dashboard/renderByQryStr/1468832348610969'
        );

        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'getUrlToImage', $prm);

        return $result;
    }         
    
}
