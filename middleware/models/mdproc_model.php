<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdproc_Model extends Model {

    public function __construct() {
        parent::__construct();
    }
    
    public function getProcIndicatorListModel() {
        
        $param = array(
            'systemMetaGroupId' => '1551410695298713',
            'ignorePermission' => 1, 
            'showQuery' => 0
        );

        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

        if (isset($result['result']) && isset($result['result'][0])) {
            unset($result['result']['aggregatecolumns']);
            unset($result['result']['paging']);
            $data = $result['result'];
        } else {
            $data = null;
        }
            
        return $data;
    }
    
    public function getProcCustomerListModel($id) {
        
        $param = array(
            'orderBookId' => $id
        );

        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'SUPPLIER_COLUMN_GET_DV_004', $param);

        if ($result['status'] == 'success') {
            $data = $result['result']['compare_customer_column'];
        } else {
            $data = null;
        }
        
        return $data;
    }
    
    public function getProcCustomerViewListModel($id) {
        
        $param = array(
            'id' => $id
        );

        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'extApprovedUserGet_004', $param);

        if ($result['status'] == 'success') {
            $data = $result['result']['approveduserlist'];
        } else {
            $data = null;
        }
        
        return $data;
    }
    
    public function getProcCustomerItemListModel($id) {
        
        $param = array(
            'headerId' => $id
        );

        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, Config::getFromCacheDefault('COMPARISON_ADD_CHANGE_PRICE', null, 'PRICE_COMPARISON_GET_004'), $param);

        if ($result['status'] == 'success') {
            $data = $result['result'];
        } else {
            $data = null;
        }
            
        return $data;
    }
    
    public function getProcWfmIdModel($id) {
        
        $param = array(
            'headerId' => $id
        );

        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'EXT_PRICE_COMPARISON_ORDER_TYPE_STATUS_GET_004', $param);

        if ($result['status'] == 'success') {
            $data = $result['result']['refstatusid'];
        } else {
            $data = null;
        }
            
        return $data;
    }
    
    public function saveModel() {
        $postData = Input::postData();
        $itemDtl = $compKpi = $tempSupplierId = [];
        $totalAmount = 0;
        $kpiPostData = isset($postData['param']) ? $postData['param'] : array();
        
        foreach ($postData['itemid'] as $keyTemp => $row) {
            $supplierDtl = [];
            $key = $postData['indexKey'][$keyTemp];
            $itemDtl[$key]['id'] = isset($postData['itemRecordId']) ? $postData['itemRecordId'][$key] : '';
            $itemDtl[$key]['itemId'] = $row;
            $itemDtl[$key]['qty'] = $postData['qty'][$key];
            $itemDtl[$key]['description'] = $postData['description'][$key];
            $itemDtl[$key]['beginPrice'] = $postData['beginPrice'][$key];
            $itemDtl[$key]['typeId'] = $postData['typeId'][$key];
            
            if (array_key_exists('supplierId'.$key, $postData)) {
                foreach ($postData['supplierId'.$key] as $dtlKey => $dtl) {
                    
                    if ($key == 0) {
                        
                        if (isset($postData['paymentDtlTypeName'.$dtl])) {
                            $paymentDtl[$dtl] = array();

                            foreach ($postData['paymentDtlTypeName'.$dtl] as $payKey => $pay) {
                                array_push($paymentDtl[$dtl], array(
                                    'typeName' => $pay,
                                    'id' => isset($postData['paymentDtlRecordId'.$dtl]) ? $postData['paymentDtlRecordId'.$dtl][$payKey] : '',
                                    'percent' => $postData['paymentDtlPercent'.$dtl][$payKey],
                                    'currencyName' => $postData['paymentDtlCurrencyName'.$dtl][$payKey],
                                    'dim1' => $postData['paymentDtlDim1'.$dtl][$payKey],
                                    'currencyId' => $postData['paymentDtlCurrencyId'.$dtl][$payKey]
                                ));
                            }
                        }
                        
                        array_push($tempSupplierId, $dtl);
                    }
                    
                    $kpiDtlData = $postData['kpidmdtl'.$key][$dtlKey];
                    $kpiDmDtl = array();
                    
                    if ($kpiDtlData) {
                    
                        $kpiDtlData = json_decode($postData['kpidmdtl'.$key][$dtlKey], true);
                        foreach ($kpiDtlData as $kpiKey => $kpi) {
                            array_push($kpiDmDtl, array(
                                'id' => $kpi['id'],
                                'bookid' => $kpi['bookid'],
                                'indicatorid' => $kpi['indicatorid'],
                                'templateDtlId' => $kpi['templatedtlid'],
                                'fact1' => $kpi['fact1'],
                                'fact2' => $kpi['fact2'],
                                'fact3' => $kpi['fact3'],
                                'fact4' => $kpi['fact4'],
                                'fact5' => $kpi['fact5'],
                                'fact6' => $kpi['fact6'],
                                'fact7' => $kpi['fact7'],
                                'fact8' => $kpi['fact8'],
                                'fact9' => $kpi['fact9'],
                                'fact10' => $kpi['fact10']
                            ));
                        }
                    }
                    
                    $supplierTotalPrice = empty($postData['supplierTotalPrice'.$key][$dtlKey]) ? 0 : $postData['supplierTotalPrice'.$key][$dtlKey];
                    
                    if (!empty($postData['discountPercent'.$key][$dtlKey])) {
                        $discountAmount = $postData['discountPercent'.$key][$dtlKey] * $supplierTotalPrice / 100;
                        $supplierTotalPrice = $supplierTotalPrice - $discountAmount;
                    } elseif (!empty($postData['discountAmount'.$key][$dtlKey])) {
                        $supplierTotalPrice = $supplierTotalPrice - $postData['discountAmount'.$key][$dtlKey];
                    }
                    
                    array_push($supplierDtl, array(
                        'id' => isset($postData['supplierRecordId'.$key]) ? $postData['supplierRecordId'.$key][$dtlKey] : '',
                        'supplierId' => $dtl,
                        'currencyId' => $postData['supplierCurrencyId'.$key][$dtlKey],
                        'isSelected' => $postData['supplierSelected'.$key][$dtlKey],
                        'totalPrice' => $supplierTotalPrice,
                        'totalAmountBase' => $supplierTotalPrice * $postData['rate'.$key][$dtlKey],
                        'unitPrice' => $supplierTotalPrice,
                        'isVat' => $postData['isVat'.$key][$dtlKey],
                        'customCost' => $postData['customCost'.$key][$dtlKey],
                        'indicatorId' => $postData['indicatorId'.$key][$dtlKey],
                        'rate' => $postData['rate'.$key][$dtlKey],
                        'qty' => $postData['qty'.$key][$dtlKey],
                        'rfqId' => $postData['rfqId'.$key][$dtlKey],
                        'paymentDtl' => issetParam($paymentDtl[$dtl]),
                        'kpiDmDtl' => $kpiDmDtl
                    ));                    
                    
                    if ($postData['supplierSelected'.$key][$dtlKey] === '1') {
                        $itemDtl[$key]['supplierId'] = $dtl;
                        $itemDtl[$key]['dueDate'] = $postData['dueDate'.$key][$dtlKey];
                        $itemDtl[$key]['guaranteeMonth'] = $postData['guaranteeMonth'.$key][$dtlKey];
                        $itemDtl[$key]['discountPercent'] = $postData['discountPercent'.$key][$dtlKey];
                        $itemDtl[$key]['discountAmount'] = $postData['discountAmount'.$key][$dtlKey];
                        $itemDtl[$key]['dtlId'] = $postData['dtlId'.$key][$dtlKey];
                        $itemDtl[$key]['unitPrice'] = $supplierTotalPrice;
                        $itemDtl[$key]['totalAmountBase'] = $itemDtl[$key]['unitPrice'] * $postData['qty'][$key] * $postData['rate'.$key][$dtlKey];
                        $itemDtl[$key]['totalAmount'] = $itemDtl[$key]['unitPrice'] * $postData['qty'][$key];
                        $itemDtl[$key]['isVat'] = $postData['isVat'.$key][$dtlKey];
                        $itemDtl[$key]['currencyId'] = $postData['supplierCurrencyId'.$key][$dtlKey];
                        $totalAmount += $itemDtl[$key]['totalAmountBase'];
                    }
                    
                    foreach ($postData['indicatorId'] as $rowIkey => $rowIndicator) {
                        if ($rowIndicator === '1551340381857') continue;
                        
                        if (!empty($postData['indicatorValue'][$rowIkey])) {
                            array_push($supplierDtl, array(
                                'supplierId' => $dtl,
                                'id' => issetParam($postData['supplierRecordId'.$rowIndicator.'_'.$key.'_'.$dtl.$dtlKey]),
                                'pointDescription' => Input::param($postData['supplierDescription'.$rowIndicator.'_'.$key.'_'.$dtl.$dtlKey]),
                                'point' => $postData['supplierPoint'.$rowIndicator.'_'.$key.'_'.$dtl.$dtlKey],
                                'indicatorId' => $rowIndicator
                            ));                    
                        }
                    }
                }
            }
            
            $itemDtl[$key]['COMPARISON_DTL'] = $supplierDtl;
        }
        
        foreach ($postData['indicatorId'] as $key => $row) {
            $compKpi[$key]['id'] = $postData['indicatorIdValue'][$key];
            $compKpi[$key]['indicatorId'] = $row;
            $compKpi[$key]['percent'] = $postData['indicatorValue'][$key];
            $compKpi[$key]['score'] = $postData['indicatorPointValue'][$key];
            foreach ($tempSupplierId as $tempSuplierKey => $tempSuplier) {
                $compKpi[$key]['EXT_COMPARISON_KPI_DTL'][$tempSuplierKey] = array(
                    'supplierId' => $tempSuplier,
                    'id' => issetParam($postData['indicatorPercentValueId'.$tempSuplier.$tempSuplierKey.'_'.$row]),
                    'percent' => issetParam($postData['indicatorPercentValue'.$tempSuplier.$tempSuplierKey.'_'.$row])
                );
            }
        }    

        $param = array(
            'totalAmount' => $totalAmount,
            'bookTypeId' => '40000018',
            'description' => $postData['headerDescription'],
            'descriptionMore' => $postData['headerMoreDescription'],
            'departmentId' => $postData['departmentId'],
            'wfmStatusId' => $postData['wfmStatusId'],
            'ordertypeid' => $postData['ordertypeid'],
            'isforeign' => $postData['isforeign'],
            'EXT_PRICE_COMPARISON_DTL' => $itemDtl,
            'EXT_COMPARISON_KPI' => $compKpi,
            'META_DM_RECORD_MAP' => array(
                'id' => issetParam($postData['rfRecordId']),
                'srcRecordId' => $postData['rfId'],
                'srcTableName' => 'INT_ORDER_BOOK',
                'trgTableName' => 'EXT_PRICE_COMPARISON'
            )
        );
        
        $id = issetParam($postData['id']);
        
        if ($id) {
            $param['id'] = Input::param($postData['id']);
            $param['bookNumber'] = Input::param($postData['bookNumber']);
            $param['bookDate'] = Input::param($postData['bookDate']);
        } else {
            $param['bookNumber'] = $this->getAutoNumber();
            $param['bookDate'] = Date::currentDate();
            $param['createdUserId'] = Ue::sessionUserKeyId();
        }

        if (isset($postData['windowSessionId'])) {
            WebService::$addonHeaderParam['windowSessionId'] = Input::param($postData['windowSessionId']);
        }        

        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'PPRICE_COMPARISON_001', $param);
      
        if ($result['status'] == 'success') {
            
            $this->saveFiles($result['result']['id']);
            if (!isset($postData['id'])) {
                $paramWfm = array(
                    'id' => $postData['rfId'],
                    'wfmStatusId' => '1526869726271711',
                    'META_WFM_LOG' => array(
                        'refStructureId' => '1524564607899',
                        'recordId' => $postData['rfId'],
                        'wfmStatusId' => '1526869726271711',
                        'createdDate' => Date::currentDate(),
                        'createdUserId' => issetParam($postData['id']) ? $postData['createduserid'] : Ue::sessionUserKeyId(),
                     )
                );

                WebService::$addonHeaderParam['windowSessionId'] = getUID();

                $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'UPDATESTATUSRFQ_001', $paramWfm);
            }
            
            
            $data = array(
                'status' => 'success',
                'message' => 'Амжилттай хадгалагдлаа.'
            );
        } else {
            $data = array(
                'status' => 'error',
                'message' => $result['text']
            );
        }
              
        return $data;
    }
    
    private function getAutoNumber() {
        $param = array(
            'objectId' => '1526537379596'
        );
        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'CRM_AUTONUMBER_BP', $param);
        
        if ($result['status'] === 'success') {
            return $result['result']['result'];
        }
        return '';
    }
    
    private function saveFiles($sourceId) {
        if (isset($_FILES['procFiles'])) {

            $file_arr = Arr::arrayFiles($_FILES['procFiles']);
            $file_arr = Arr::groupByArray($file_arr, 'name');
            $currentDate      = Date::currentDate();
            $sessionUserKeyId = Ue::sessionUserKeyId();            
            $refStructureId = '1526524155528';

            if ($file_arr) {

                $file_path = UPLOADPATH . 'process/';
                $index = 0;

                foreach ($file_arr as $f => $file) {

                    if (isset($file_arr[$f]['row']['name']) && $file_arr[$f]['row']['name'] != '' && $file_arr[$f]['row']['size'] != null) {

                        $newFileName = 'fileproc_' . getUID() . $index;
                        $fileExtension = strtolower(substr($file_arr[$f]['row']['name'], strrpos($file_arr[$f]['row']['name'], '.') + 1));
                        $fileName = $newFileName . '.' . $fileExtension;
                        $index++;

                        FileUpload::SetFileName($fileName);
                        FileUpload::SetTempName($file_arr[$f]['row']['tmp_name']);
                        FileUpload::SetUploadDirectory($file_path);
                        FileUpload::SetValidExtensions(explode(',', Config::getFromCache('CONFIG_FILE_EXT').',msg'));
                        FileUpload::SetMaximumFileSize(FileUpload::GetConfigFileMaxSize()); //10mb
                        $uploadResult = FileUpload::UploadFile();

                        if ($uploadResult) {

                            $contentId = getUID();
                            $dataContent = array(
                                'CONTENT_ID'      => $contentId,
                                'FILE_NAME'       => $file_arr[$f]['row']['name'],
                                'PHYSICAL_PATH'   => $file_path . $fileName,
                                'FILE_EXTENSION'  => $fileExtension,
                                'FILE_SIZE'       => $file_arr[$f]['row']['size'],
                                'CREATED_USER_ID' => $sessionUserKeyId,
                                'CREATED_DATE'    => $currentDate,
                                'IS_EMAIL'        => '',
                                'IS_PHOTO'        => ''
                            );
                            $dataContentFile = $this->db->AutoExecute('ECM_CONTENT', $dataContent);

                            if ($dataContentFile) {
                                $dataContentMap = array(
                                    'ID'               => getUID(),
                                    'REF_STRUCTURE_ID' => $refStructureId,
                                    'RECORD_ID'        => $sourceId,
                                    'CONTENT_ID'       => $contentId,
                                    'ORDER_NUM'        => ($index + 1)
                                );
                                $this->db->AutoExecute('ECM_CONTENT_MAP', $dataContentMap);
                            }
                        }
                    }
                }
            }
        }        
    }
    
    public function getRowProcModel($id) {
        
        $param = array(
            'id' => $id
        );

        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, Config::getFromCacheDefault('COMPARISON_EDIT_CHANGE_PRICE', null, 'EXT_PRICE_COMPARISON_VIEW_GET_0041'), $param);
        
        if ($result['status'] == 'success') {
            $data = $result['result'];
        } else {
            $data = null;
        }
            
        return $data;
    }    
    
    public function getRowViewProcModel($id) {
        
        $param = array(
            'id' => $id
        );

        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'EXT_COMPARISON_VIEW_GET_004', $param);

        if ($result['status'] == 'success') {
            $data = $result['result'];
        } else {
            $data = null;
        }
            
        return $data;
    }    
    
    public function getRowsProcFileModel($id) {
        $metaDataIdPh  = $this->db->Param(0);
        $metaValueIdPh = $this->db->Param(1);
        
        $bindVars = array($this->db->addQ('1526524155528'), $this->db->addQ($id));
        
        $data = $this->db->GetAll("
            SELECT 
                CO.CONTENT_ID AS ATTACH_ID, 
                CO.FILE_NAME AS ATTACH_NAME, 
                CO.PHYSICAL_PATH AS ATTACH, 
                CO.FILE_EXTENSION, 
                CO.FILE_SIZE, 
                CO.IS_EMAIL, 
                '' AS SYSTEM_URL 
            FROM ECM_CONTENT CO 
                INNER JOIN ECM_CONTENT_MAP MP ON MP.CONTENT_ID = CO.CONTENT_ID 
            WHERE MP.REF_STRUCTURE_ID = $metaDataIdPh 
                AND MP.RECORD_ID = $metaValueIdPh 
                AND IS_PHOTO = 0 
            ORDER BY MP.ORDER_NUM", 
            $bindVars     
        );

        return $data;
    }    
    
    public function getRolesModel() {
        $userIdIdPh = $this->db->Param(0);
        $bindVars = array($this->db->addQ(Ue::sessionUserId()));

        $data = $this->db->GetAll("
            SELECT
                ROLE.ROLE_ID, ROLE.ROLE_CODE, ROLE.ROLE_NAME
              FROM UM_SYSTEM_USER US
                INNER JOIN UM_USER U ON US.USER_ID = U.SYSTEM_USER_ID
                INNER JOIN UM_USER_ROLE UR ON U.USER_ID = UR.USER_ID
                INNER JOIN UM_ROLE ROLE ON UR.ROLE_ID = ROLE.ROLE_ID
            WHERE U.IS_ACTIVE = 1 
                  AND UR.IS_ACTIVE = 1 
                  AND US.USER_ID = $userIdIdPh", 
            $bindVars     
        );

        return $data;
    }    
    
    public function getProcWfmStatusIdModel($id, $orderTypeId) {
        
        $param = array(
            'departmentId' => $id,
            'wfmWorkflowId' => '1529289320914138',
            'bookTypeId' => '40000018'
        );
        $param = array_merge($param, $orderTypeId);

        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'GET_WORKFLOW', $param);

        if ($result['status'] == 'success') {
            $data = $result['result'] ? $result['result']['wfmstatusid'] : null;
        } else {
            $data = null;
        }
            
        return $data;
    }    
    
    public function getProcSupplierListModel($id) {
        
        $param = array(
            'systemMetaGroupId' => '1686559300816676',
            'ignorePermission' => 1, 
            'showQuery' => 0,
            'criteria' => array(
                'id' => array(
                    array(
                        'operator' => '=',
                        'operand' => $id
                    )
                )
            )            
        );

        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

        if (isset($result['result']) && isset($result['result'][0])) {
            unset($result['result']['aggregatecolumns']);
            unset($result['result']['paging']);
            $data = $result['result'];
        } else {
            $data = null;
        }
            
        return $data;
    }    
    
}