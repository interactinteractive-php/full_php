<?php if(!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');
    
class Appmarket_Model extends Model {

    private static $gfServiceAddress = GF_SERVICE_ADDRESS;
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getChildMenuListModel($menuMetaDataId) {
        $param = array(
            'userId' => Ue::sessionUserKeyId(),
            'menuId' => $menuMetaDataId
        );
        
        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'child_menus', $param);
        
        if ($data['status'] == 'success' && isset($data['result'])) {
            return array('status' => 'success', 'menuData' => $data['result']);
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
        }
    }
    
    public function startupMeta() {
        $get = $this->db->GetRow("SELECT * FROM UM_META_BLOCK WHERE USER_ID = ".$this->db->Param(0), array(Ue::sessionUserKeyId()));
        return $get;
    }    
    
    public function startupMeta2() {
        $get = $this->db->GetRow("SELECT * FROM UM_META_BLOCK WHERE IS_ALL_USER = ".$this->db->Param(0), array(1));
        return $get;
    }    
    
    public function startupUser() {
        $get = $this->db->GetRow("SELECT * FROM UM_USER WHERE USER_ID = ".$this->db->Param(0), array(Ue::sessionUserKeyId()));
        return $get;
    }
    
    public function getDataviewResultModel($dvId, $limit = 15) {

        $param = array(
            'systemMetaGroupId' => $dvId,
            'showQuery' => 0,
            'ignorePermission' => 1,
            'paging' => array (
                'offset' => 1,
                'pageSize' => $limit
            )            
        );

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result']) && isset($data['result'][0])) {
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);
            return $data['result'];
        } else {
            return null;
        }
    }    
    
    public function getModuleInfoModel($id) {

        $prm = array(
            'id' => $id,
            'filterUserId' => Ue::sessionUserKeyId(),
        );
        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'appMarketProductDetailGet_004', $prm);
        
        if (isset($data['result'])) {
            return $data['result'];
        } else {
            return null;
        }
    }    
    
    public function saveToBasketModel($id, $itemId, $basketTotalAmount, $price) {

        $prm = array(
            'id' => $id,
            'total' => ($basketTotalAmount + $price),
            'smBasketDtl' => [[
                'itemId' => $itemId,
                'unitPrice' => $price,
                'lineTotalPrice' => $price,
            ]],
        );
        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'smBasketBook_001', $prm);
        
        return $data;
    }    
    
    public function getTreeDataByValue($dataViewId, $structureMetaDataId, $parentId, $criteria) {
        
        $this->load->model('mddatamodel', 'middleware/models/');

        $result = $noOrderdvids = array();
        
        $getCodeNameFieldName = $this->model->getCodeNameFieldNameModel($dataViewId);
        
        $idField     = $getCodeNameFieldName['id'];
        $codeField   = $getCodeNameFieldName['code'];
        $nameField   = $getCodeNameFieldName['name'];
        $parentField = $getCodeNameFieldName['parent'];
        
        $param = array(
            'systemMetaGroupId' => $dataViewId,
            'showQuery'         => 0,
            'isShowAggregate'   => 0,
            'ignorePermission'  => 1, 
            'treeGrid'          => 1
        );
        
        if (Config::isCode('isTreeNoOrderDvids')) {
            $noOrderdvids = explode(',', Config::getFromCache('isTreeNoOrderDvids'));
        } 
        
        if ($parentId == '#') {
            $param['criteria'][$parentField][] = array(
                'operator' => 'IS NULL',
                'operand' => ''
            );
        } else {
            $param['criteria'][$parentField][] = array(
                'operator' => '=',
                'operand' => $parentId
            );
        }
        
        if ($criteria) {
            $param['criteria'] = [];
            $param['criteria'][$criteria['path']][] = array(
                'operator' => '=',
                'operand' => $criteria['value'] 
            );
        }

        $data = $this->ws->runArrayResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] == 'success' && isset($data['result'])) {

            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $treeData = $data['result'];
            
            if ($treeData) {
                
                $k = 0;

                foreach ($treeData as $tree) {
                    
                    $isChildRecordCount = (issetParam($tree['children']) ? true : false);

                    $result[$k]['id'] = $tree[$idField];
                    $result[$k]['text'] = $tree[$nameField];
                    $result[$k]['rowdata'] = $tree;
                    $result[$k]['children'] = $isChildRecordCount;

                    if (issetParam($tree['icon'])) {
                        $result[$k]['icon'] = $tree['icon'];
                    }

                    if ($isChildRecordCount) {
                        $result[$k]['state']['opened'] = false;
                        $result[$k]['children'] = $this->getTreeDataByDeepValue($dataViewId, $structureMetaDataId, $tree['children']);
                    }
                    
                    if (issetParam($tree['isselected']) == '1') {
                        $result[$k]['state']['selected'] = true;
                    }
                    
                    $k++;
                }
            }
        } 
        
        return $result;
    }    
    
    public function getTreeDataByDeepValue($dataViewId, $structureMetaDataId, $treeData) {
        
        $this->load->model('mddatamodel', 'middleware/models/');

        $result = $noOrderdvids = array();
        
        $getCodeNameFieldName = $this->model->getCodeNameFieldNameModel($dataViewId);
        
        $idField     = $getCodeNameFieldName['id'];
        $codeField   = $getCodeNameFieldName['code'];
        $nameField   = $getCodeNameFieldName['name'];
        $parentField = $getCodeNameFieldName['parent'];
        $k = 0;
        
        foreach ($treeData as $tree) {

            $isChildRecordCount = (issetParam($tree['children']) ? true : false);

            $result[$k]['id'] = $tree[$idField];
            $result[$k]['text'] = $tree[$nameField];
            $result[$k]['rowdata'] = $tree;
            $result[$k]['children'] = $isChildRecordCount;

            if (issetParam($tree['icon'])) {
                $result[$k]['icon'] = $tree['icon'];
            }

            if ($isChildRecordCount) {
                $result[$k]['state']['opened'] = false;
                $result[$k]['children'] = $this->getTreeDataByDeepValue($dataViewId, $structureMetaDataId, $tree['children']);
            }

            if (issetParam($tree['isselected']) == '1') {
                $result[$k]['state']['selected'] = true;
            }

            $k++;
        }
        
        return $result;
    }

    public function getBasketModel() {

        $prm = array(
            'filterUserId' => Ue::sessionUserKeyId(),
        );
        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'smBasketBookGet_004', $prm);
        
        if (isset($data['result'])) {
            return $data['result'];
        } else {
            return null;
        }
    }     
    
    public function getQrCodeImg($data, $height = '150px') {
        
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
    
    public function qPayGetInvoiceQrModel($params) {
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'qpay_v2_createInvoice_simple', $params);

        if ($result['status'] == 'success') {
            return array('status' => 'success', 'qrcode' => self::getQrCodeImg($result['result']['qr_image'], '250px'), 'traceNo' => $result['result']['invoice_id']);
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

}