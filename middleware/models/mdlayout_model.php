<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdlayout_Model extends Model {
    private static $getDataViewCommand = 'PL_MDVIEW_004';
    public function __construct() {
        parent::__construct();
    }

    public function getOnlyContentMetaModel() {
        $data = $this->db->GetAll("
            SELECT 
                META_DATA_ID,
                META_DATA_NAME
            FROM META_DATA 
            WHERE META_TYPE_ID = " . Mdmetadata::$contentMetaTypeId . " 
            AND IS_ACTIVE = 1     
            ORDER BY META_DATA_NAME ASC");

        return $data;
    }

    public function getLayoutListModel() {
        $data = $this->db->GetAll("
            SELECT 
                LAYOUT_ID,
                LAYOUT_CODE, 
                LAYOUT_NAME  
            FROM META_CONTENT_LAYOUT 
            ORDER BY LAYOUT_NAME ASC");

        return $data;
    }

    public function getLayoutCellModel($layoutId) {
        $layoutId = Security::sanitize($layoutId);
        $data = $this->db->GetAll("
                SELECT 
                    CELL_ID,
                    ROW_ID, 
                    COL_ID   
                FROM META_CONTENT_LAYOUT_CELL 
                WHERE LAYOUT_ID = $layoutId 
                    AND IS_USE = 1
                ORDER BY CELL_ID ASC");

        return $data;
    }

    public function getLayoutAllCellDataModel($layoutId, $metaDataId) {
        $layoutId = Security::sanitize($layoutId);
        $metaDataId = Security::sanitize($metaDataId);
        $data = $this->db->GetAll("
            SELECT 
                LC.CELL_ID,
                LC.ROW_ID, 
                LC.COL_ID, 
                LC.IS_MERGE, 
                LC.IS_USE, 
                LC.BORDER_TOP, 
                LC.BORDER_LEFT, 
                LC.BORDER_BOTTOM, 
                LC.BORDER_RIGHT, 
                LC.CAPTION, 
                LC.WIDTH, 
                LC.HEIGHT, 
                LC.BORDER_COLOR,
                LC.BG_COLOR,
                LC.ALIGN,
                LC.VALIGN,
                CM.META_DATA_ID 
            FROM META_CONTENT_LAYOUT_CELL LC 
            LEFT JOIN META_CONTENT_MAP CM ON CM.CELL_ID = LC.CELL_ID AND CM.LAYOUT_ID = LC.LAYOUT_ID AND CM.SRC_META_DATA_ID = $metaDataId 
            WHERE LC.LAYOUT_ID = $layoutId 
            ORDER BY LC.CELL_ID ASC");

        return $data;
    }

    public function getLayoutAllCellModel($layoutId) {
        $layoutId = Security::sanitize($layoutId);
        $data = $this->db->GetAll("
                SELECT 
                    CELL_ID,
                    ROW_ID, 
                    COL_ID, 
                    IS_MERGE, 
                    IS_USE,
                    WIDTH, 
                    HEIGHT
                FROM META_CONTENT_LAYOUT_CELL 
                WHERE LAYOUT_ID = $layoutId 
                ORDER BY CELL_ID ASC");

        return $data;
    }

    public function getLayoutRowModel($layoutId) {
        $layoutId = Security::sanitize($layoutId);
        $data = $this->db->GetAll("
                SELECT 
                    ROW_ID, 
                    HEIGHT
                FROM META_CONTENT_LAYOUT_ROW 
                WHERE LAYOUT_ID = $layoutId 
                ORDER BY ROW_ID ASC");

        return $data;
    }

    public function getLayoutColModel($layoutId) {
        $layoutId = Security::sanitize($layoutId);
        $data = $this->db->GetAll("
                SELECT 
                    COL_ID, 
                    WIDTH
                FROM META_CONTENT_LAYOUT_COL 
                WHERE LAYOUT_ID = $layoutId 
                ORDER BY COL_ID ASC");

        return $data;
    }

    public function getLayoutMergeModel($layoutId) {
        $layoutId = Security::sanitize($layoutId);
        $data = $this->db->GetAll("
                SELECT 
                    MERGE_ID, 
                    START_CELL_ID
                FROM META_CONTENT_LAYOUT_MERGE 
                WHERE LAYOUT_ID = $layoutId 
                ORDER BY MERGE_ID ASC");

        return $data;
    }

    public function getLayoutMergeCellModel($layoutId) {
        $layoutId = Security::sanitize($layoutId);
        $data = $this->db->GetAll("
                SELECT 
                    MC.MERGE_ID, 
                    MC.CELL_ID, 
                    LC.ROW_ID, 
                    LC.COL_ID
                FROM META_CONTENT_LT_MERGE_CELL MC 
                INNER JOIN META_CONTENT_LAYOUT_CELL LC ON LC.CELL_ID = MC.CELL_ID AND LC.LAYOUT_ID = MC.LAYOUT_ID 
                WHERE MC.LAYOUT_ID = $layoutId 
                ORDER BY MC.MERGE_ID ASC");

        return $data;
    }

    public function getLayoutCellSavedModel($layoutId, $metaDataId) {
        $layoutId = Security::sanitize($layoutId);
        $metaDataId = Security::sanitize($metaDataId);
        $data = $this->db->GetAll("
                SELECT 
                    LC.CELL_ID,
                    LC.ROW_ID, 
                    LC.COL_ID, 
                    CM.META_DATA_ID 
                FROM META_CONTENT_LAYOUT_CELL LC 
                LEFT JOIN META_CONTENT_MAP CM ON CM.LAYOUT_ID = LC.LAYOUT_ID AND CM.SRC_META_DATA_ID = $metaDataId AND LC.CELL_ID = CM.CELL_ID 
                WHERE LC.LAYOUT_ID = $layoutId 
                    AND LC.IS_USE = 1
                ORDER BY LC.CELL_ID ASC");

        return $data;
    }

    public function getLayoutMetaDataModel($metaDataId) {
        $metaDataId = Security::sanitize($metaDataId);
        $row = $this->db->GetRow("
            SELECT 
                MD.META_DATA_NAME, 
                MD.META_DATA_ID, 
                MD.META_DATA_CODE, 
                CT.LAYOUT_ID, 
                CT.LAYOUT_NAME, 
                CT.LAYOUT_CODE,
                CT.ROW_COUNT, 
                CT.COL_COUNT, 
                CT.BG_COLOR, 
                CT.BG_IMAGE, 
                CT.BORDER_WIDTH
            FROM META_CONTENT_LINK CL  
            INNER JOIN META_DATA MD ON CL.META_DATA_ID = MD.META_DATA_ID 
            INNER JOIN META_CONTENT_LAYOUT CT ON CT.LAYOUT_ID = CL.LAYOUT_ID 
            WHERE CL.META_DATA_ID = $metaDataId AND CL.IS_DEFAULT = 1");

        return $row;
    }

    public function getMetaTypeIdModel($metaDataId) {
        $metaDataId = Security::sanitize($metaDataId);
        return $this->db->GetOne("SELECT META_TYPE_ID FROM META_DATA WHERE META_DATA_ID = $metaDataId");
    }

    public function findMetaIdByBoth($metaDataIdCode) {
        $metaDataIdCode = Input::param($metaDataIdCode);

        $findByCode = $this->db->GetOne("SELECT META_DATA_ID FROM META_DATA WHERE LOWER(META_DATA_CODE) = LOWER('$metaDataIdCode')");
        if ($findByCode == null) {
            return $this->db->GetOne("SELECT META_DATA_ID FROM META_DATA WHERE META_DATA_ID = '$metaDataIdCode'");
        } else {
            return $findByCode;
        }
    }

    public function getLayoutDataModel($layoutId) {
      $layoutId = Input::param($layoutId);
      $qry = "SELECT 
                CT.LAYOUT_NAME AS  META_DATA_NAME, 
                CT.LAYOUT_CODE AS META_DATA_CODE, 
                CT.LAYOUT_ID, 
                CT.LAYOUT_NAME, 
                CT.LAYOUT_CODE,
                CT.ROW_COUNT, 
                CT.COL_COUNT, 
                CT.BG_COLOR, 
                CT.BG_IMAGE, 
                CT.BORDER_WIDTH
              FROM META_CONTENT_LAYOUT CT 
              WHERE CT.LAYOUT_ID = $layoutId";
      $row = $this->db->GetRow($qry);
      return $row;
    }

    public function getLayoutACellDataModel($layoutId) {
        $layoutId = Security::sanitize($layoutId);
        $data = $this->db->GetAll("
            SELECT 
                LC.CELL_ID,
                LC.ROW_ID, 
                LC.COL_ID, 
                LC.IS_MERGE, 
                LC.IS_USE, 
                LC.BORDER_TOP, 
                LC.BORDER_LEFT, 
                LC.BORDER_BOTTOM, 
                LC.BORDER_RIGHT, 
                LC.CAPTION, 
                LC.WIDTH, 
                LC.HEIGHT, 
                LC.BORDER_COLOR,
                LC.BG_COLOR,
                LC.ALIGN,
                LC.VALIGN, LC.LAYOUT_ID AS META_DATA_ID
            FROM META_CONTENT_LAYOUT_CELL LC 
            WHERE LC.LAYOUT_ID = $layoutId 
            ORDER BY LC.CELL_ID ASC");

        return $data;
    }  

    public function getDataListModel($id) {
        $param = array(
            'systemMetaGroupId' => $id,
            'showQuery' => 0, 
            'ignorePermission' => 1
        );
        
        $resultArr = array();
        
        $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] === 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $resultArr = $data['result'];
        }
        
        return $resultArr;
    }
    
    public function getHeaderLegendDataModel($templateId, $processId, $params = '') {
        
        $processData = $this->db->GetRow("SELECT META_DATA_CODE FROM META_DATA WHERE META_DATA_ID = " . $processId);
        
        if (isset($processData['META_DATA_CODE']) && $processData['META_DATA_CODE']) {
            
            if ($params) {
                $param = $params;
            } else {
                $param = array('templateId' => $templateId);
            }
            
            $result = $this->ws->caller('WSDL-DE', GF_SERVICE_ADDRESS,  $processData['META_DATA_CODE'], 'return', $param, 'serialize');
            
            if (isset($result['result']) && $result['result']) {
                unset($result['result']['aggregatecolumns']);
                return $result['result'];
            }
        }
        
        return array();
    }
    
    public function getBodyDataModel($id, $headerData, $processId, $addParam = array()) {

        $processData = $this->db->GetRow("SELECT META_DATA_CODE FROM META_DATA WHERE META_DATA_ID = ".$this->db->Param(0), array($processId));
        
        if (isset($processData['META_DATA_CODE']) && $processData['META_DATA_CODE']) {
            
            $templateDataArr = Arr::sort2d($headerData, 'ordernum', 'asc');
            $templateIds = array();
            
            if ($templateDataArr) {
                foreach ($templateDataArr as $row) {
                    $temp['id'] = $row['id'];
                    $temp['criteria'] = $row['criteria'];
                    $temp['colour'] = $row['color'];
                    array_push($templateIds, $temp);
                }
            }
            
            if (Input::postCheck('treeTemplate')) {
                $param = $addParam;
            } else {
                $param = array (
                    'id' => $id,
                    'templateIds' => $templateIds
                );
                
                if ($addParam) {
                    foreach ($addParam as $key => $row) {
                        $param[$key] = $row;
                    }
                }
            }
            
            $result = $this->ws->caller('WSDL-DE', GF_SERVICE_ADDRESS,  $processData['META_DATA_CODE'], 'return', $param, 'json');
            
            if (isset($result['result']['result']) && $result['result']['result']) {
                
                $json = preg_replace('/\r|\n/', '', trim($result['result']['result']));
                $jsonDecode = json_decode($json, true);
                
                if (Input::post('chartType') == 'streelayout') {
                    
                    includeLib('Compress/Compression');

                    $data = array(
                        'relation' => json_decode(Compression::decompress($jsonDecode['relation']), true), 
                        'node'     => json_decode(Compression::decompress($jsonDecode['node']), true)
                    );
                    
                } else {
                    $data = $jsonDecode;
                }
              
                return array('data' => $data, 'msg' => isset($result['text']) ? $result['text'] : '');
                
            } else {
                if (isset($result['text']) && $result['text']) {
                    return array('data' => array(), 'msg' => isset($result['text']) ? $result['text'] : '');
                }
            }
        }
        
        return array();
    }
    
    public function getBodyData1Model($id, $headerData, $processId, $criteria = '') {
        
        $processData = $this->db->GetRow("SELECT META_DATA_CODE FROM META_DATA WHERE META_DATA_ID = " . $processId);
        
        if (isset($processData['META_DATA_CODE']) && $processData['META_DATA_CODE']) {
            
            $templateIds = array();
            $temp['id'] = $id;
            $temp['criteria'] = '';
            $temp['colour'] = '';            
            
            $criStr = '';
            
            if ($criteria) {
                foreach ($criteria['eapath'] as $row) {
                    if ($id == $criteria[$row.'_relatedtemplateid'] && !empty($criteria[$row.'_value'])) {
                        $criStr .= str_replace(':value', $criteria[$row.'_value'], $criteria[$row.'_criteria']) . ' AND ';
                    }
                }
            }
            
            $temp['criteria'] = rtrim($criStr, ' AND ');
            
            array_push($templateIds, $temp);
            $param = array (
                'templateIds' => $templateIds
            );            
            
            $result = $this->ws->caller('WSDL-DE', GF_SERVICE_ADDRESS,  $processData['META_DATA_CODE'], 'return', $param, 'serialize');
            
            if (isset($result['result']['result']) && $result['result']['result']) {
                return array('data' => json_decode($result['result']['result'], true), 'msg' => isset($result['text']) ? $result['text'] : '');
            } else {
                if (isset($result['text']) && $result['text']) {
                    return array('data' => array(), 'msg' => isset($result['text']) ? $result['text'] : '');
                }
            }
        }
        
        return array();
    }
    
    public function getMatrixDataModel($processId, $params = '') {
        
        $processData = $this->db->GetRow("SELECT META_DATA_CODE FROM META_DATA WHERE META_DATA_ID = " . $processId);
        
        if (isset($processData['META_DATA_CODE']) && $processData['META_DATA_CODE']) {
            $result = $this->ws->caller('WSDL-DE', GF_SERVICE_ADDRESS,  $processData['META_DATA_CODE'], 'return', $params, 'serialize');
            
            if (isset($result['result']) && $result['result']) {
                unset($result['result']['aggregatecolumns']);
                return $result['result'];
            }
        }
        
        return array();
    }
    
    public function getFormDataModel($id) {
        $resultArr = array();
        $param = array('id' => $id);
        
        $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'GETFOOTPRINTCONFIG', $param);
        
        if ($data['status'] === 'success' && isset($data['result'])) {
            $resultArr = $data['result'];
        }
        
        return $resultArr;
    }    
    
    public function getfootPrintDetailDataModel($id) {
        $resultArr = array();
        $param = array('id' => $id);
        
        $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'getPivotData', $param);
        
        if ($data['status'] === 'success' && isset($data['result'])) {
            $resultArr = $data['result'];
        }
        
        return $resultArr;
    }    
    
    public function saveRelationD3Tree($postData) {
        $param = array (
            'indicatorCode' => 'link',
            'indicatorName' => 'link',
            'isShow' => '0',
            'name2' => 'link',
            'isRequired' => '0',
            'showType' => 'object',
            'isMultiple' => '0',
            'templateId' => $postData['srcTemplateId'],
            'code' => 'R07560',
            'columnName' => 'INDICATOR1',
            'relatedObjectId' => $postData['trgTemplateId'],
            'orderNum' => '1',
            'kpiTemplateDtlFact' => array('showType' => 'object'),
            'kpiIndicator' => array(
                'id' => null,
                'code' => 'link',
                'name' => 'link',
                'isActive' => '1',
                'showType' => 'object',
                'name2' => 'link',
            ),
            'kpiIndicatorValue' => array(),
            'kpiTemplateMap' => array(
                array(
                    'trgTemplateId' => $postData['trgTemplateId'],
                )
            )
        );
        $result = $this->ws->caller('WSDL-DE', GF_SERVICE_ADDRESS, 'eaObjTypeIndicatorDv_0010', 'return', $param, 'serialize');
        
        return $result;
    }
    
    public function getHeaderLegendDvModel($id) {
        $param = array(
            'systemMetaGroupId' => $id,
            'showQuery' => '0',
            'criteria' => array(),
            'paging' => array(),
        );

        $result = array();

        $dataResult = WebService::runResponse(GF_SERVICE_ADDRESS, 'PL_MDVIEW_004', $param);

        if ($dataResult['status'] === 'success') {
            unset($dataResult['result']['paging']);
            unset($dataResult['result']['aggregatecolumns']);
            $result = $dataResult['result'];
        }

        return $result;
    }
    /* portal home start */

    public function loadListModel($systemMetaGroupId, $criteria = array(), $paging = array(), $isShowQuery = 0) {
        
        $result = array();
        $param = array(
            'systemMetaGroupId' => $systemMetaGroupId, 
            'showQuery' => $isShowQuery, 
            'ignorePermission' => 1, 
            'criteria' => $criteria,
            'paging' => $paging
        );
        
        $dataResult = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, self::$getDataViewCommand, $param);

        if ($dataResult['status'] === 'success') {
            
            if (isset($dataResult['result']['paging'])) {
                unset($dataResult['result']['paging']);
            }
            
            unset($dataResult['result']['aggregatecolumns']);
            
            $result = $dataResult['result'];
        }

        return $result;
    }
    
    public function filterData($addDate = '', $useFilterStartDate = null) {
        $currentDate = Date::currentDate('Y-m-d');
        
        $data = array(
            'filterEndDate' => array(
                array(
                    'operator' => '=',
                    'operand' => ($addDate) ? Date::formatter($currentDate, 'Y-m') . $addDate : $currentDate
                )
            ),
            'sessionDepartmentId' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionDepartmentId()
                )
            ),
            'filterDepartmentId' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionDepartmentId()
                )
            ),
            'sessionUserId' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionUserId()
                )
            ),
            'filterUserId' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionUserId()
                )
            ),
            'sessionUserKeyId' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionUserKeyId()
                )
            ),
            'filterNextWfmUserId' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionUserKeyId()
                )
            ),
        );
        
        if ($useFilterStartDate) {
            $data['filterstartdate'] = array(
                array(
                    'operator' => '=',
                    'operand' => Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days')
                )
            );
        }
        
        return $data;
    }

    public function fncRunDataview($dataviewId, $field = "", $operand = "=", $operator = "", $paramFilter = "", $sortField = 'createddate', $sortK = 'desc', $iscriteriaOnly = "0", $pagination = false, $pageSize = false) {
        if (!$dataviewId) {
            return array();
        }
        
        if ($iscriteriaOnly) {
            $criteria = $paramFilter;
        } else {
            $criteria = array(
                            $field => array(
                                array(
                                    'operator' => $operand,
                                    'operand' => ($operand == 'like') ? '%'.$operator.'%' : $operator
                                )
                            )
                        );
            
            if ($paramFilter) {
                foreach ($paramFilter as $key => $param) {
                    $criteria[$key] = $param;
                }
            }
        }
        
        includeLib('Utils/Functions');
        
        $paging = array();
        
        if ($pagination || $pageSize) {
            $paging = array(
                'offset' => Input::post('offset') ? Input::post('offset') : '1',
                'pageSize' => $pageSize ? $pageSize : '50'
            );
        }
        
        if ($sortField) {
            $sortColumnNames[$sortField] = array('sortType' => $sortK);
            $paging['sortColumnNames'] = $sortColumnNames;
        }
        
        $data = Functions::runDataViewWithoutLogin($dataviewId, $criteria, '0', $paging);
        
        (Array) $response = array();
        if ($pagination) {
            $response = $data;
        } elseif (isset($data['result']) && $data['result']) {
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);
            $response = $data['result'];
        }
        
        return $response;
    }

    public function dashboardLayoutAgentDataModel($layoutPosition = '', $postData = array(), $request = '0', $agent = '0') {
        
       
        $currentDate = Date::currentDate('Y-m-d');
        
        $paramFilter = array(
            'filterSessionUserId' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionUserId()
                )
            ),
            'filterSessionUserKeyId' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionUserKeyId()
                )
            ),
            'filterSessionEmployeeId' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionEmployeeId()
                )
            ),                 
        );
        $paramFilter1 = self::filterData('-30');
        $paramFilter2 = self::filterData('', 1);

        (Array) $dashboardArr = array();
        
        $dashboardArr['pos_0_dvid'] = ($layoutPosition   === '' || $layoutPosition == 'pos00') ? "1585127775592" : '';      //  shuurhai medee
        $dashboardArr['pos_1_dvid'] = (($layoutPosition  === '' || $layoutPosition == 'pos01') && $request == '0') ? "1568362202338" : '';      //  ХҮЛЭЭГДЭЖ БУЙ БИЧИГ
        $dashboardArr['pos_2_dvid'] = (($layoutPosition  === '' || $layoutPosition == 'pos02') && $request == '0') ? "1572351189226" : '';      //  ГҮЙЦЭТГЭГЧЭЭР /АЖЛУУД/
        $dashboardArr['pos_3_dvid'] = (($layoutPosition  === '' || $layoutPosition == 'pos03') && $request == '0') ? "1572350684801" : '';      //  ЦАГИЙН ДЭЛГЭРЭНГҮЙ БАЛАНС
        $dashboardArr['pos_4_dvid'] = (($layoutPosition  === '' || $layoutPosition == 'pos04') && $request == '0') ? "1464244142438861" : '';   //  төрсөн өдөрийн dv
        $dashboardArr['pos_5_dvid'] = ($layoutPosition   === '' || $layoutPosition == 'pos05') ? "1571474482320" : '';      //  bichig barimt '1586404271676'
        $dashboardArr['pos_6_dvid'] = ($layoutPosition   === '' || $layoutPosition == 'pos06') ? "1564710586209" : '';      //  minii ajil
        $dashboardArr['pos_7_dvid'] = ($layoutPosition   === '' || $layoutPosition == 'pos07') ? "1577260423821868" : '';      //   Ажлын байрны зар
        $dashboardArr['pos_8_dvid'] = ($layoutPosition   === '' || $layoutPosition == 'pos08') ? "1600064001320535" : '';      //  нийт цагийн хүсэлт
        $dashboardArr['pos_10_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos10') && $request == '0') ? "1586404271492" : '';     //  zurgiin tsomog
        $dashboardArr['pos_11_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos11') && $agent == '0') ? "1587095552879524" : '';  //  Ажилтны явцын хяналт
        $dashboardArr['pos_12_dvid'] = ($layoutPosition  === '' || $layoutPosition == 'pos12') ? "1548219753368" : '';     //  Мэдээ мэдээлэл
        
        $dashboardArr['pos_0']  = $this->fncRunDataview($dashboardArr['pos_0_dvid'], "isurgent", "=", '1', array(), 'createddate', 'desc', '0', false, '3');
        $dashboardArr['pos_7']  = $this->fncRunDataview($dashboardArr['pos_7_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "1");
        $dashboardArr['pos_8']  = $this->fncRunDataview($dashboardArr['pos_8_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "1");
        $dashboardArr['pos_12'] = $this->fncRunDataview($dashboardArr['pos_12_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, 'createddate', 'desc', '1');
        
        return $dashboardArr;
    }

    public function dashboardLayoutDataModel($layoutPosition = '', $postData = array(), $request = '0', $agent = '0') {
        
       
        $currentDate = Date::currentDate('Y-m-d');
        
        $paramFilter = self::filterData();
        $paramFilter1 = self::filterData('-30');
        $paramFilter2 = self::filterData('', 1);

        (Array) $dashboardArr = array();
        
        $dashboardArr['pos_0_dvid'] = ($layoutPosition   === '' || $layoutPosition == 'pos00') ? "1585127775592" : '';      //  shuurhai medee
        $dashboardArr['pos_1_dvid'] = (($layoutPosition  === '' || $layoutPosition == 'pos01') && $request == '0') ? "1568362202338" : '';      //  ХҮЛЭЭГДЭЖ БУЙ БИЧИГ
        $dashboardArr['pos_2_dvid'] = (($layoutPosition  === '' || $layoutPosition == 'pos02') && $request == '0') ? "1572351189226" : '';      //  ГҮЙЦЭТГЭГЧЭЭР /АЖЛУУД/
        $dashboardArr['pos_3_dvid'] = (($layoutPosition  === '' || $layoutPosition == 'pos03') && $request == '0') ? "1572350684801" : '';      //  ЦАГИЙН ДЭЛГЭРЭНГҮЙ БАЛАНС
        $dashboardArr['pos_4_dvid'] = (($layoutPosition  === '' || $layoutPosition == 'pos04') && $request == '0') ? "1464244142438861" : '';   //  төрсөн өдөрийн dv
        $dashboardArr['pos_5_dvid'] = ($layoutPosition   === '' || $layoutPosition == 'pos05') ? "1571474482320" : '';      //  bichig barimt '1586404271676'
        $dashboardArr['pos_6_dvid'] = ($layoutPosition   === '' || $layoutPosition == 'pos06') ? "1564710586209" : '';      //  minii ajil
        $dashboardArr['pos_7_dvid'] = ($layoutPosition   === '' || $layoutPosition == 'pos07') ? "1577260423821868" : '';      //   Ажлын байрны зар
        $dashboardArr['pos_8_dvid'] = ($layoutPosition   === '' || $layoutPosition == 'pos08') ? "1591583278520" : '';      //  нийт цагийн хүсэлт
        $dashboardArr['pos_9_dvid'] = ($layoutPosition   === '' || $layoutPosition == 'pos09') ? "1591583278368" : '';      //  шинэ цагийн хүсэлт 
        $dashboardArr['pos_10_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos10') && $request == '0') ? "1586404271492" : '';     //  zurgiin tsomog
        $dashboardArr['pos_11_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos11') && $agent == '0') ? "1587095552879524" : '';  //  Ажилтны явцын хяналт
        $dashboardArr['pos_12_dvid'] = ($layoutPosition  === '' || $layoutPosition == 'pos12') ? "1548219753368" : '';     //  Мэдээ мэдээлэл
        $dashboardArr['pos_13_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos13') && $request == '0') ? "1586494409900" : '';     //  баннер
        $dashboardArr['pos_14_dvid'] = ($layoutPosition  === '' || $layoutPosition == 'pos14') ? "1585127775592" : '';  // Эвент календарь
        $dashboardArr['pos_15_dvid'] = ($layoutPosition  === '' || $layoutPosition == 'pos15') ? "1568362804484" : '';     //  Хүний нөөцийн хүсэлт
        
        $dashboardArr['pos_0']  = $this->fncRunDataview($dashboardArr['pos_0_dvid'], "isurgent", "=", '1', array(), 'createddate', 'desc', '0', false, '3');
        $dashboardArr['pos_1']  = $this->fncRunDataview($dashboardArr['pos_1_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
        $dashboardArr['pos_2']  = $this->fncRunDataview($dashboardArr['pos_2_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
        $dashboardArr['pos_3']  = $this->fncRunDataview($dashboardArr['pos_3_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), self::filterData('-1'), "", "", "0");
        $dashboardArr['pos_4']  = $this->fncRunDataview($dashboardArr['pos_4_dvid'], "birthdate", "=", Date::formatter($currentDate, 'm-d'), array(), "", "", "0");
        $dashboardArr['pos_5']  = $this->fncRunDataview($dashboardArr['pos_5_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
        $dashboardArr['pos_6']  = $this->fncRunDataview($dashboardArr['pos_6_dvid'], "filterStartDate", "=", Date::formatter($currentDate, 'Y-m') . '-01', $paramFilter1, "", "", "0", false, '10');
        $dashboardArr['pos_7']  = $this->fncRunDataview($dashboardArr['pos_7_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
        $dashboardArr['pos_8']  = $this->fncRunDataview($dashboardArr['pos_8_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
        $dashboardArr['pos_9']  = $this->fncRunDataview($dashboardArr['pos_9_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
        $dashboardArr['pos_10'] = $this->fncRunDataview($dashboardArr['pos_10_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
        $dashboardArr['pos_11'] = $this->fncRunDataview($dashboardArr['pos_11_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
        $dashboardArr['pos_12'] = $this->fncRunDataview($dashboardArr['pos_12_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, 'createddate', 'desc', '0');
        $dashboardArr['pos_13'] = $this->fncRunDataview($dashboardArr['pos_13_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, 'createddate', 'desc', '0', false, '5');
        
        $filterdate = (($layoutPosition === '' || $layoutPosition == 'pos14') && isset($postData['date'])) ? $postData['date'] : $currentDate;
        
        $dashboardArr['pos_14'] = $this->fncRunDataview($dashboardArr['pos_14_dvid'], "startdate", "=", $filterdate, $paramFilter2, 'createddate', 'desc', '0');
        $dashboardArr['pos_15'] = $this->fncRunDataview($dashboardArr['pos_15_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, 'createddate', 'desc', '0', false, '10');
        
        return $dashboardArr;
    }
    /* portal home end */
}
