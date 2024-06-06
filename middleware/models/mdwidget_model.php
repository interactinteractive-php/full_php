<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdwidget_Model extends Model {
    
    private static $getDataViewCommand = 'PL_MDVIEW_004';
            
    public function __construct() {
        parent::__construct();
    }
    
    public function getWidgetDataModel($metaDataId) {
        
        if ($metaDataId == 123) {
            
            $linkData = array(
                'META_DATA_ID'     => 123, 
                'TITLE'            => 'Donut Chart Title', 
                'WIDGET_CODE'      => 'chart_donut', 
                'GET_DATA_META_ID' => 999, 
                'CONFIG_JSON'      => '{"legend": {"maxHeight": 40, "scrollable": true}, "innerRadius": 100, "dataFields": {"value": "litres", "category": "country"}}'
            );
            
            //$getData = get process duudna
            
            $linkData['data'] = array(
                array(
                    'country' => 'Lithuania',
                    'litres' => 501.9
                ), 
                array(
                    'country' => 'Czech Republic',
                    'litres' => 301.9
                ), 
                array(
                    'country' => 'Ireland',
                    'litres' => 201.1
                )
            );
        }
        
        return $linkData;
    }
    
    public function exchangeRateModel($param) {
        
        $data = $this->db->GetAll("
            SELECT 
                BANK_DATE, 
                RATE 
            FROM FIN_RATE_ADJ 
            WHERE CURRENCY_ID = ".$param['currencyId']." 
                AND BANK_DATE BETWEEN TO_DATE('".$param['startDate']."') AND TO_DATE('".$param['endDate']."') 
            ORDER BY BANK_DATE");
        
        return $data;
    }
    
    public function getCurrencyNameModel($currencyId) {
        $currencyName = $this->db->GetOne("SELECT CURRENCY_NAME FROM REF_CURRENCY WHERE CURRENCY_ID = $currencyId");
        return $currencyName;
    }

    public function loadListProcessModel($getCode) {
        $param = $result = array();

        $dataResult = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, $getCode, $param);

        if ($dataResult['status'] == 'success') {            
            if (isset($dataResult['result'])) {
                $result =  $dataResult['result'];
            }            
        }

        return $result;
    }    
    
    public function loadListModel($systemMetaGroupId, $criteria = array(), $paging = array(), $isShowQuery = 0, $jsonAttr = array()) {
        
        $result = array();
        $param = array(
            'systemMetaGroupId' => $systemMetaGroupId, 
            'showQuery' => $isShowQuery, 
            'ignorePermission' => 1, 
            'criteria' => $criteria
        );
        
        if ($paging) {
            $param['paging'] = $paging;
        }
        
        // $dataResult = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, self::$getDataViewCommand, $param);
        // pa(Mddatamodel::$getRowDataViewCommand);
        // pa($param);
        $sessionValues = Session::get(SESSION_PREFIX.'sessionValues');
        
        $param['criteria'] = array_merge($criteria, array(
            'filterid' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionFiscalPeriodYearId()
                )
            )
        ));         
        
        if (issetParam($jsonAttr['getrowall'])) {
            $param['criteria'] = array(
                'id' => array(
                    array(
                        'operator' => '=',
                        'operand' => 1
                    )
                )
            );
            $dataResult = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getRowDataViewCommand, $param);
        } elseif (issetParam($jsonAttr['getrows'])) {
            $dataResult = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, Mddatamodel::$consolidateDataViewCommand, $param);
        } else {
            $dataResult = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, self::$getDataViewCommand, $param);
        }

        if ($dataResult['status'] === 'success') {
            
            if (isset($dataResult['result']['paging'])) {
                unset($dataResult['result']['paging']);
            }
            
            unset($dataResult['result']['aggregatecolumns']);
            
            $result = $dataResult['result'];
        }

        return $result;
    }    
    
    public function layoutParamConfigModel($param) {
        
        $data = $this->db->GetAll("
            SELECT 
                ID,
                LAYOUT_PARAM_MAP_ID,
                WIDGET_PARAM_NAME,
                META_PARAM_NAME,
                DEFAULT_VALUE,
                WIDGET_POSITION_CONFIGS
            FROM META_LAYOUT_PARAM_CONFIG 
            WHERE LAYOUT_PARAM_MAP_ID = ".$param['paramMapId']);
        
        $widgetArr = array();
        foreach ($data as $row) {
            $widgetArr[$row['WIDGET_PARAM_NAME']] = $row['META_PARAM_NAME'];
        }
        
        return $widgetArr;
    }    
    
    public function layoutListParamConfigModel($param) {
        
        $data = $this->db->GetAll("
            SELECT 
                ID,
                LAYOUT_PARAM_MAP_ID,
                WIDGET_PARAM_NAME,
                META_PARAM_NAME,
                DEFAULT_VALUE,
                WIDGET_POSITION_CONFIGS
            FROM META_LAYOUT_PARAM_CONFIG 
            WHERE LAYOUT_PARAM_MAP_ID = ".$param['paramMapId']);
        
        return $data;
    }    
    
    public function getHeaderLabelNameModel($dvId) {
        
        $data = $this->db->GetAll("
            SELECT 
                LOWER(FIELD_PATH) AS FIELD_PATH, 
                LABEL_NAME 
            FROM META_GROUP_CONFIG 
            WHERE MAIN_META_DATA_ID = $dvId 
                AND IS_SHOW = 1 
                AND IS_SELECT = 1 
            ORDER BY DISPLAY_ORDER ASC");
        
        return $data;
    }
 
    public function loadApartmentModel($criteria = array()) {
        
        $result = array();
        $id = Input::post('recordId');
        $param = array(
            'recordId' => $id, 
        );
        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'CorporateapartmentDV_004', $param);
        return $result;
        
    }   

    public function loadRateModel($criteria = array()) {
        
        $result = array();
        $id = Input::post('recordId');
        $param = array(
            'recordId' => $id, 
        );
        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'CorporateDV_004', $param);
        return $result;
    }   
    
    public function loadRateOfficeModel($criteria = array()) {
        
        $result = array();
        $id = Input::post('recordId');
        $param = array(
            'recordId' => $id,
        );
        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'officeGetDv_004', $param);
        return $result;
    }   

    public function loadRateHashaaModel($criteria = array()) {
        
        $result = array();
        $id = Input::post('recordId');
        $param = array(
            'recordId' => $id,
        );
        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'CorporateDVHashaa_004', $param);
        return $result;
    }   

    public function getTemplateDCModel($criteria = array()) {
        
        $param = array(
            'colId' => Input::post('recordId'), 
        );
        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'colIdGet_004', $param);
        return $result['result']['id'];
    }   

    public function saveTemplateDCModel() {
        
        $param = array(
            'id' => Input::post('kpiId'), 
            'indicator201' => Input::post('indicator201'), 
            'indicator200' => Input::post('indicator200'), 
            'indicator202' => Input::post('indicator202')
        );
        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'TEMPLATE_KPI_DM_MART_UPDATE_001', $param);            
        return $result;
    }   
    
    public function getProcessTitleByIdsModel($ids) {
        
        $bindParams = array();
        $inClause = '';

        foreach ($ids as $c => $commaVal) {

            if ($commaVal != '') {

                $inClauseParam = 'dvInParam'.$c;

                $inClause .= $this->db->Param($inClauseParam) . ', ';

                $bindParams = array($inClauseParam => $commaVal) + $bindParams;
            }
        }
        
        $inClause = rtrim($inClause, ', ');
                        
        // $data = $this->db->GetAll("
        //     SELECT 
        //         PL.META_DATA_ID, 
        //         " . $this->db->IfNull('PL.PROCESS_NAME', 'MD.META_DATA_NAME') . " AS META_DATA_NAME 
        //     FROM META_BUSINESS_PROCESS_LINK PL 
        //         INNER JOIN META_DATA MD ON MD.META_DATA_ID = PL.META_DATA_ID 
        //     WHERE PL.META_DATA_ID IN ($inClause)", 
        //     $bindParams 
        // );
        $data = $this->db->GetAll("
            SELECT 
                PL.META_DATA_ID, 
                " . $this->db->IfNull('PL.PROCESS_NAME', 'MD.META_DATA_NAME') . " AS META_DATA_NAME 
            FROM META_BUSINESS_PROCESS_LINK PL 
            INNER JOIN META_DATA MD ON MD.META_DATA_ID = PL.META_DATA_ID 
            INNER JOIN CONFIG_VALUE CV ON TO_CHAR(CV.CONFIG_VALUE) = TO_CHAR(MD.META_DATA_ID)
            INNER JOIN CONFIG C ON C.ID = CV.CONFIG_ID 
            WHERE PL.META_DATA_ID IN ($inClause)
            ORDER BY C.DISPLAY_ORDER ASC", 
            $bindParams 
        );
        
        return $data;
    }
    
    public function saveEcmOfficeVersion($key, $status, $targeturl, $targetchangeurl, $history, $users, $actions, $lastSaveDate, $isModified, $contentId) {
        $param = array(
            'key' => $key, 
            'status' => $status, 
            'url' => $targeturl, 
            'changeUrl' => $targetchangeurl, 
            'history' => $history, 
            'users' => $users, 
            'actions' => $actions, 
            'lastSaveDate' => $lastSaveDate, 
            'isModified' => $isModified, 
            'contentId' => $contentId, 
            'createdDate' => $createdDate, 
            'createdUserId' => $createdUserId
        );
        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'ECM_OFFICE_VERSION_DV_001', $param);            
        return $result;
    }

    public function getWidgetStandartListModel() {
        $qry = "SELECT 
                    MW.ID,
                    MW.CODE,
                    MW.NAME,
                    MW.STYLE_ATTR,
                    MW.PREVIEW_WEBIMAGE,
                    MW.PREVIEW_MOBILEIMAGE,
                    PW.NAME AS PARENT_NAME,
                    MW.POSITION_COUNT,
                    MW.WFM_STATUS_ID,
                    MW.WFM_DESCRIPTION,
                    WS.WFM_STATUS_NAME,
                    WS.WFM_STATUS_COLOR,
                    MW.COMPONENT_PATH,
                    CASE WHEN MW.TYPE_ID = 1 THEN 'Interface'
                        WHEN MW.TYPE_ID = 2 THEN 'Form'
                        WHEN MW.TYPE_ID = 3 THEN 'Field'
                            WHEN MW.TYPE_ID = 4 THEN 'Menu'
                            ElSE '' END AS TYPE_NAME 
                FROM META_WIDGET MW
                LEFT JOIN META_WIDGET PW ON MW.PARENT_ID = PW.ID
                LEFT JOIN META_WFM_STATUS WS ON MW.WFM_STATUS_ID = WS.ID
                WHERE MW.PARENT_ID IS NOT NULL 
                AND MW.CODE IN (
                'cloud_list_linechart',
                'cloudcard_003',
                'cloudcard_005',
                'cloud_list',
                'cloudcard_004',
                'cloudtimeline_list',
                'cloudcard_008',
                'cloudcard_006',
                'cloudcard_007'
            )";
        return $this->db->GetAll($qry);
    }

    public function getIndicatorWidgetModel($indicatorId) {
        $param = array(
            'filterIndicatorId' => $indicatorId, 
        );
        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'pageMapInfo_004', $param);            
        return issetParamArray($result['result']);
    }

    public function widgetListDataModel() {
        
        $param = array (
            'systemMetaGroupId' => '17090956046449',
            'showQuery' => 0, 
            'pagingWithoutAggregate' => 1, 
            'ignorePermission' => 1,  
            'criteria' => array()
        );      

        $dataResult = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

        unset($dataResult['result']['paging']);
        unset($dataResult['result']['aggregatecolumns']);

        return issetParamArray($dataResult['result']);
        
    }

    public function widgetListDataWithJsonModel() {
        
        $param = array(
            'systemMetaGroupId' => '17157492547493',
            'showQuery' => 0, 
            'pagingWithoutAggregate' => 1, 
            'ignorePermission' => 1,  
            'criteria' => array()
        );      
                
        $dataResult = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

        unset($dataResult['result']['paging']);
        unset($dataResult['result']['aggregatecolumns']);

        return issetParamArray($dataResult['result']);
        
    }
    
}