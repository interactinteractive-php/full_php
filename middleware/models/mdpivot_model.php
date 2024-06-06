<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');
    
class Mdpivot_Model extends Model {

    private static $gfServiceAddress = GF_SERVICE_ADDRESS;

    public function __construct() {
        parent::__construct();
    }        

    public function getDmReportRow($reportModelId) {

        $row = $this->db->GetRow("
            SELECT 
                REPORT_MODEL_ID, 
                REPORT_MODEL_NAME, 
                DM_META_DATA_ID, 
                PROCESS_META_DATA_ID, 
                COMMAND_NAME 
            FROM DM_REPORT_MODEL 
            WHERE REPORT_MODEL_ID = $reportModelId");

        return $row;
    }

    public function getDmReportModels() {

        $data = $this->db->GetAll("
            SELECT 
                REPORT_MODEL_ID, 
                REPORT_MODEL_NAME 
            FROM DM_REPORT_MODEL 
            WHERE IS_ACTIVE = 1 
                AND DM_META_DATA_ID IS NOT NULL 
            ORDER BY REPORT_MODEL_NAME ASC");

        return $data;
    }

    public function getDmReportModelFields($reportModelId) {

        $data = $this->db->GetAll("
            SELECT 
                ".$this->db->IfNull('MF.LABEL_NAME', 'GC.LABEL_NAME')." AS LABEL_NAME, 
                GC.SIDEBAR_NAME,     
                LOWER(GC.FIELD_PATH) AS FIELD_PATH, 
                GC.DATA_TYPE AS META_TYPE_CODE 
            FROM META_GROUP_CONFIG GC 
                INNER JOIN DM_REPORT_MODEL RM ON RM.REPORT_MODEL_ID = $reportModelId AND RM.DM_META_DATA_ID = GC.MAIN_META_DATA_ID 
                LEFT JOIN DM_REPORT_MODEL_FIELDS MF ON MF.REPORT_MODEL_ID = RM.REPORT_MODEL_ID AND LOWER(MF.FIELD_NAME) = LOWER(GC.FIELD_PATH) 
            WHERE RM.REPORT_MODEL_ID = $reportModelId 
                AND GC.PARENT_ID IS NULL 
                AND ((GC.IS_SELECT = 1 AND GC.IS_SHOW = 1) OR (GC.IS_CRITERIA = 1)) 
                AND GC.DATA_TYPE <> 'group'  
            ORDER BY GC.DISPLAY_ORDER ASC");

        return $data;
    }

    public function getDmDataModelFields($dataViewId) {

        $data = $this->db->GetAll("
            SELECT 
                LABEL_NAME, 
                SIDEBAR_NAME,      
                LOWER(FIELD_PATH) AS FIELD_PATH, 
                DATA_TYPE AS META_TYPE_CODE 
            FROM META_GROUP_CONFIG  
            WHERE MAIN_META_DATA_ID = $dataViewId 
                AND PARENT_ID IS NULL
                AND ((IS_SELECT = 1 AND IS_SHOW = 1) OR (IS_CRITERIA = 1)) 
                AND DATA_TYPE <> 'group' 
            ORDER BY DISPLAY_ORDER ASC");

        return $data;
    }

    public function getDmReportModelFilterFields($reportModelId, $filters = null) {

        $where = '';
        $join = 'INNER JOIN DM_REPORT_MODEL_FILTER MM ON LOWER(MM.FIELD_NAME) = LOWER(GC.FIELD_PATH) AND MM.REPORT_MODEL_ID = RM.REPORT_MODEL_ID ';
        $orderBy = 'ORDER BY MM.VIEW_ORDER ASC';

        if ($filters) {

            $imploder = $orderByCase = $join = '';

            foreach ($filters as $f => $fv) {
                $imploder .= "'$fv', ";
                $orderByCase .= "WHEN '$fv' THEN ".(++$f)." ";
            }

            $where = "AND LOWER(GC.FIELD_PATH) IN (".rtrim($imploder, ', ').") ";

            $orderBy = "ORDER BY 
                    CASE LOWER(GC.FIELD_PATH)  
                    $orderByCase 
                    END ASC";
        }

        $data = $this->db->GetAll("
            SELECT 
                GC.DATA_TYPE AS META_TYPE_CODE, 
                0 AS GROUP_PARAM_CONFIG_TOTAL, 
                (
                    SELECT 
                        ".$this->db->listAgg('PARAM_PATH', '|', 'PARAM_PATH')."  
                    FROM META_GROUP_PARAM_CONFIG 
                    WHERE GROUP_META_DATA_ID = GC.MAIN_META_DATA_ID   
                        AND LOOKUP_META_DATA_ID IS NOT NULL 
                        AND (IS_GROUP = 0 OR IS_GROUP IS NULL) 
                        AND LOWER(FIELD_PATH) = LOWER(GC.FIELD_PATH) 
                ) AS GROUP_CONFIG_PARAM_PATH, 
                (
                    SELECT 
                        ".$this->db->listAgg('PARAM_META_DATA_CODE', '|', 'PARAM_PATH')."  
                    FROM META_GROUP_PARAM_CONFIG 
                    WHERE GROUP_META_DATA_ID = GC.MAIN_META_DATA_ID  
                        AND LOOKUP_META_DATA_ID IS NOT NULL 
                        AND (IS_GROUP = 0 OR IS_GROUP IS NULL) 
                        AND LOWER(FIELD_PATH) = LOWER(GC.FIELD_PATH) 
                ) AS GROUP_CONFIG_LOOKUP_PATH, 
                (
                    SELECT 
                        ".$this->db->listAgg('FIELD_PATH', '|', 'FIELD_PATH')."  
                    FROM META_GROUP_PARAM_CONFIG 
                    WHERE GROUP_META_DATA_ID = GC.MAIN_META_DATA_ID  
                        AND LOOKUP_META_DATA_ID IS NOT NULL 
                        AND (IS_GROUP = 0 OR IS_GROUP IS NULL) 
                        AND LOWER(PARAM_PATH) = LOWER(GC.FIELD_PATH) 
                ) AS GROUP_CONFIG_FIELD_PATH, 
                '' AS GROUP_CONFIG_GROUP_PATH, 
                LOWER(GC.PARAM_NAME) AS META_DATA_CODE, 
                GC.FIELD_PATH, 
                " . $this->db->IfNull('MF.LABEL_NAME', 'GC.LABEL_NAME') . " AS META_DATA_NAME, 
                null AS ATTRIBUTE_ID_COLUMN, 
                null AS ATTRIBUTE_CODE_COLUMN, 
                null AS ATTRIBUTE_NAME_COLUMN, 
                '1' AS IS_SHOW, 
                GC.IS_REQUIRED, 
                GC.DEFAULT_VALUE,  
                GC.RECORD_TYPE, 
                GC.LOOKUP_META_DATA_ID, 
                LMD.META_TYPE_ID AS LOOKUP_META_TYPE_ID, 
                GC.LOOKUP_TYPE, 
                GC.CHOOSE_TYPE, 
                GC.DISPLAY_FIELD, 
                GC.VALUE_FIELD, 
                LOWER(GC.FIELD_PATH) AS PARAM_REAL_PATH, 
                GC.TAB_NAME,
                GC.SIDEBAR_NAME, 
                GC.FEATURE_NUM, 
                GC.IS_SAVE, 
                GC.FILE_EXTENSION,
                MFP.PATTERN_TEXT,
                MFP.PATTERN_NAME, 
                MFP.GLOBE_MESSAGE,
                MFP.IS_MASK,
                GC.COLUMN_WIDTH,
                GC.MAIN_META_DATA_ID, 
                GC.ID, 
                GC.IS_MANDATORY_CRITERIA,
                '' AS IS_REFRESH, 
                GC.FRACTION_RANGE,
                GC.SEARCH_GROUPING_NAME, 
                GC.MIN_VALUE, 
                GC.MAX_VALUE, 
                ".$this->db->IfNull('GC.PLACEHOLDER_NAME', 'GC.LABEL_NAME')." AS PLACEHOLDER_NAME 
            FROM META_GROUP_CONFIG GC 
                INNER JOIN DM_REPORT_MODEL RM ON RM.REPORT_MODEL_ID = $reportModelId AND RM.DM_META_DATA_ID = GC.MAIN_META_DATA_ID 
                $join 
                LEFT JOIN META_DATA LMD ON LMD.META_DATA_ID = GC.LOOKUP_META_DATA_ID 
                LEFT JOIN META_FIELD_PATTERN MFP ON MFP.PATTERN_ID = GC.PATTERN_ID      
                LEFT JOIN DM_REPORT_MODEL_FIELDS MF ON MF.REPORT_MODEL_ID = RM.REPORT_MODEL_ID AND LOWER(MF.FIELD_NAME) = LOWER(GC.FIELD_PATH) 
            WHERE RM.REPORT_MODEL_ID = $reportModelId 
                AND GC.DATA_TYPE <> 'group'  
                $where     
            $orderBy");

        if ($filters) {

            if (Input::isEmpty('defaultCriteriaData') == false) {
                parse_str($_POST['defaultCriteriaData'], $criteriaParam);
                $criteriaParam = $criteriaParam['param'];
            }

            $array = array();

            foreach ($filters as $field) {
                foreach ($data as $row) {
                    if ($field == Str::lower($row['PARAM_REAL_PATH'])) {
                        if (isset($criteriaParam) && array_key_exists($field, $criteriaParam)) {
                            $row['DEFAULT_VALUE'] = $criteriaParam[$field];
                        }
                        $array[] = $row;
                    }
                }
            }

            $data = $array;
        }

        return $data;
    }

    public function getDmDataViewModelFilterFields($dataViewId, $filters = null) {

        $where = '';
        $join = '';
        $orderBy = '';

        if ($filters) {

            $imploder = $orderByCase = '';

            foreach ($filters as $f => $fv) {
                $imploder .= "'$fv', ";
                $orderByCase .= "WHEN '$fv' THEN ".(++$f)." ";
            }

            $where = "AND LOWER(GC.FIELD_PATH) IN(".rtrim($imploder, ', ').") ";

            $orderBy = "ORDER BY 
                    CASE LOWER(GC.FIELD_PATH)  
                    $orderByCase 
                    END ASC";
        } else {
            return null;
        }

        $data = $this->db->GetAll("
            SELECT 
                GC.DATA_TYPE AS META_TYPE_CODE, 
                0 AS GROUP_PARAM_CONFIG_TOTAL, 
                (
                    SELECT 
                        ".$this->db->listAgg('PARAM_PATH', '|', 'PARAM_PATH')."  
                    FROM META_GROUP_PARAM_CONFIG 
                    WHERE GROUP_META_DATA_ID = GC.MAIN_META_DATA_ID   
                        AND LOOKUP_META_DATA_ID IS NOT NULL 
                        AND (IS_GROUP = 0 OR IS_GROUP IS NULL) 
                        AND LOWER(FIELD_PATH) = LOWER(GC.FIELD_PATH) 
                ) AS GROUP_CONFIG_PARAM_PATH, 
                (
                    SELECT 
                        ".$this->db->listAgg('PARAM_META_DATA_CODE', '|', 'PARAM_PATH')."  
                    FROM META_GROUP_PARAM_CONFIG  
                    WHERE GROUP_META_DATA_ID = GC.MAIN_META_DATA_ID  
                        AND LOOKUP_META_DATA_ID IS NOT NULL 
                        AND (IS_GROUP = 0 OR IS_GROUP IS NULL) 
                        AND LOWER(FIELD_PATH) = LOWER(GC.FIELD_PATH) 
                ) AS GROUP_CONFIG_LOOKUP_PATH, 
                (
                    SELECT 
                        ".$this->db->listAgg('FIELD_PATH', '|', 'FIELD_PATH')."  
                    FROM META_GROUP_PARAM_CONFIG 
                    WHERE GROUP_META_DATA_ID = GC.MAIN_META_DATA_ID  
                        AND LOOKUP_META_DATA_ID IS NOT NULL 
                        AND (IS_GROUP = 0 OR IS_GROUP IS NULL) 
                        AND LOWER(PARAM_PATH) = LOWER(GC.FIELD_PATH) 
                ) AS GROUP_CONFIG_FIELD_PATH, 
                '' AS GROUP_CONFIG_GROUP_PATH,  
                LOWER(GC.PARAM_NAME) AS META_DATA_CODE, 
                GC.FIELD_PATH, 
                GC.LABEL_NAME AS META_DATA_NAME,  
                null AS ATTRIBUTE_ID_COLUMN, 
                null AS ATTRIBUTE_CODE_COLUMN, 
                null AS ATTRIBUTE_NAME_COLUMN, 
                '1' AS IS_SHOW, 
                GC.IS_REQUIRED, 
                GC.DEFAULT_VALUE,  
                GC.RECORD_TYPE, 
                GC.LOOKUP_META_DATA_ID, 
                LMD.META_TYPE_ID AS LOOKUP_META_TYPE_ID, 
                GC.LOOKUP_TYPE, 
                GC.CHOOSE_TYPE, 
                GC.DISPLAY_FIELD, 
                GC.VALUE_FIELD, 
                LOWER(GC.FIELD_PATH) AS PARAM_REAL_PATH, 
                GC.TAB_NAME,
                GC.SIDEBAR_NAME, 
                GC.FEATURE_NUM, 
                GC.IS_SAVE, 
                GC.FILE_EXTENSION,
                MFP.PATTERN_TEXT,
                MFP.PATTERN_NAME, 
                MFP.GLOBE_MESSAGE,
                MFP.IS_MASK,
                GC.COLUMN_WIDTH,
                GC.MAIN_META_DATA_ID, 
                GC.ID, 
                GC.IS_MANDATORY_CRITERIA,
                '' AS IS_REFRESH, 
                GC.FRACTION_RANGE,
                GC.SEARCH_GROUPING_NAME, 
                GC.MIN_VALUE, 
                GC.MAX_VALUE, 
                ".$this->db->IfNull('GC.PLACEHOLDER_NAME', 'GC.LABEL_NAME')." AS PLACEHOLDER_NAME 
            FROM META_GROUP_CONFIG GC 
                $join 
                LEFT JOIN META_DATA LMD ON LMD.META_DATA_ID = GC.LOOKUP_META_DATA_ID 
                LEFT JOIN META_FIELD_PATTERN MFP ON MFP.PATTERN_ID = GC.PATTERN_ID           
            WHERE GC.MAIN_META_DATA_ID = $dataViewId 
                AND GC.DATA_TYPE <> 'group'  
                $where     
            $orderBy");

        if ($filters) {

            if (Input::isEmpty('defaultCriteriaData') == false) {
                parse_str($_POST['defaultCriteriaData'], $criteriaParam);
                $criteriaParam = $criteriaParam['param'];
            }

            $array = array();

            foreach ($filters as $field) {
                foreach ($data as $row) {
                    if ($field == strtolower($row['PARAM_REAL_PATH'])) {
                        if (isset($criteriaParam) && array_key_exists($field, $criteriaParam)) {
                            $row['DEFAULT_VALUE'] = $criteriaParam[$field];
                        }
                        $array[] = $row;
                    }
                }
            }

            $data = $array;
        }

        return $data;
    }

    public function getDmReportModelColumnFields($reportModelId) {

        $data = $this->db->GetAll("
            SELECT 
                ".$this->db->IfNull('MF.LABEL_NAME', 'GC.LABEL_NAME')." AS LABEL_NAME, 
                LOWER(GC.FIELD_PATH) AS FIELD_PATH, 
                GC.DATA_TYPE AS META_TYPE_CODE 
            FROM META_GROUP_CONFIG GC 
                INNER JOIN DM_REPORT_MODEL RM ON RM.REPORT_MODEL_ID = $reportModelId AND RM.DM_META_DATA_ID = GC.MAIN_META_DATA_ID 
                INNER JOIN DM_REPORT_MODEL_COLUMN MM ON LOWER(MM.FIELD_NAME) = LOWER(GC.FIELD_PATH) AND MM.REPORT_MODEL_ID = RM.REPORT_MODEL_ID 
                LEFT JOIN DM_REPORT_MODEL_FIELDS MF ON MF.REPORT_MODEL_ID = RM.REPORT_MODEL_ID AND LOWER(MF.FIELD_NAME) = LOWER(GC.FIELD_PATH) 
            WHERE RM.REPORT_MODEL_ID = $reportModelId 
                AND GC.DATA_TYPE <> 'group' 
            ORDER BY MM.VIEW_ORDER ASC");

        return $data;
    }

    public function getDmReportModelRowFields($reportModelId) {

        $data = $this->db->GetAll("
            SELECT 
                ".$this->db->IfNull('MF.LABEL_NAME', 'GC.LABEL_NAME')." AS LABEL_NAME, 
                LOWER(GC.FIELD_PATH) AS FIELD_PATH, 
                GC.DATA_TYPE AS META_TYPE_CODE 
            FROM META_GROUP_CONFIG GC 
                INNER JOIN DM_REPORT_MODEL RM ON RM.REPORT_MODEL_ID = $reportModelId AND RM.DM_META_DATA_ID = GC.MAIN_META_DATA_ID 
                INNER JOIN DM_REPORT_MODEL_ROW MM ON LOWER(MM.FIELD_NAME) = LOWER(GC.FIELD_PATH) AND MM.REPORT_MODEL_ID = RM.REPORT_MODEL_ID 
                LEFT JOIN DM_REPORT_MODEL_FIELDS MF ON MF.REPORT_MODEL_ID = RM.REPORT_MODEL_ID AND LOWER(MF.FIELD_NAME) = LOWER(GC.FIELD_PATH) 
            WHERE RM.REPORT_MODEL_ID = $reportModelId 
                AND GC.DATA_TYPE <> 'group' 
            ORDER BY MM.VIEW_ORDER ASC");

        return $data;
    }

    public function getDmReportModelValueFields($reportModelId) {

        $data = $this->db->GetAll("
            SELECT 
                ".$this->db->IfNull('MF.LABEL_NAME', 'GC.LABEL_NAME')." AS LABEL_NAME, 
                LOWER(GC.FIELD_PATH) AS FIELD_PATH, 
                GC.DATA_TYPE AS META_TYPE_CODE, 
                LOWER(MM.AGGREGATE_NAME) AS AGGREGATE_NAME     
            FROM META_GROUP_CONFIG GC 
                INNER JOIN DM_REPORT_MODEL RM ON RM.REPORT_MODEL_ID = $reportModelId AND RM.DM_META_DATA_ID = GC.MAIN_META_DATA_ID 
                INNER JOIN DM_REPORT_MODEL_FACT MM ON LOWER(MM.FIELD_NAME) = LOWER(GC.FIELD_PATH) AND MM.REPORT_MODEL_ID = RM.REPORT_MODEL_ID 
                LEFT JOIN DM_REPORT_MODEL_FIELDS MF ON MF.REPORT_MODEL_ID = RM.REPORT_MODEL_ID AND LOWER(MF.FIELD_NAME) = LOWER(GC.FIELD_PATH) 
            WHERE RM.REPORT_MODEL_ID = $reportModelId 
                AND GC.DATA_TYPE <> 'group'  
            ORDER BY MM.VIEW_ORDER ASC");

        return $data;
    }

    public function getRMDataViewListModels() {
        $data = $this->db->GetAll("
            SELECT 
                META_DATA_ID, 
                META_DATA_NAME  
            FROM META_DATA 
            WHERE IS_ACTIVE = 1 
                AND LOWER(META_DATA_CODE) LIKE 'dmart_%'
            ORDER BY META_DATA_NAME ASC");

        return $data;
    }

    public function createPivotGridSaveModel() {

        try {

            $reportModelId = getUID();
            parse_str(Input::post('param'), $param);
            $userKeyId = Ue::sessionUserKeyId();

            $data = array(
                'REPORT_MODEL_ID' => $reportModelId, 
                'REPORT_MODEL_NAME' => Input::param($param['reportModelName']),
                'DM_META_DATA_ID' => Input::param($param['dataSourceId']),
                'IS_ACTIVE' => 1, 
                'CREATED_USER_ID' => $userKeyId, 
                'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s')
            );

            $result = $this->db->AutoExecute('DM_REPORT_MODEL', $data);

            if ($result) {

                if (isset($param['categoryId']) && !empty($param['categoryId'])) {

                    $dataCategory = array(
                        'ID' => getUID(), 
                        'REPORT_MODEL_ID' => $reportModelId,
                        'CATEGORY_ID' => Input::param($param['categoryId'])
                    );

                    $this->db->AutoExecute('DM_REPORT_MODEL_CATEGORY', $dataCategory);
                }

                if (Input::postCheck('filters')) {
                    foreach ($_POST['filters'] as $f => $filter) {

                        $dataFilter = array(
                            'REPORT_MODEL_FILTER_ID' => getUID(), 
                            'REPORT_MODEL_ID' => $reportModelId, 
                            'FIELD_NAME' => Input::param($filter), 
                            'VIEW_ORDER' => ($f + 1), 
                            'CREATED_USER_ID' => $userKeyId, 
                            'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s')
                        );
                        $this->db->AutoExecute('DM_REPORT_MODEL_FILTER', $dataFilter);
                    }
                }

                if (Input::postCheck('columns')) {
                    foreach ($_POST['columns'] as $c => $column) {

                        $dataColumn = array(
                            'REPORT_MODEL_COLUMN_ID' => getUID(), 
                            'REPORT_MODEL_ID' => $reportModelId, 
                            'FIELD_NAME' => Input::param($column),
                            'IS_VISIBLE' => 1, 
                            'VIEW_ORDER' => ($c + 1), 
                            'CREATED_USER_ID' => $userKeyId, 
                            'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s')
                        );
                        $this->db->AutoExecute('DM_REPORT_MODEL_COLUMN', $dataColumn);
                    }
                }

                if (Input::postCheck('rows')) {
                    foreach ($_POST['rows'] as $r => $row) {

                        $dataRow = array(
                            'REPORT_MODEL_ROW_ID' => getUID(), 
                            'REPORT_MODEL_ID' => $reportModelId, 
                            'FIELD_NAME' => Input::param($row), 
                            'IS_VISIBLE' => 1, 
                            'VIEW_ORDER' => ($r + 1), 
                            'CREATED_USER_ID' => $userKeyId, 
                            'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s')
                        );
                        $this->db->AutoExecute('DM_REPORT_MODEL_ROW', $dataRow);
                    }
                }

                if (Input::postCheck('values')) {
                    $v = 0;
                    foreach ($_POST['values'] as $vk => $value) {

                        $dataFact = array(
                            'REPORT_MODEL_FACT_ID' => getUID(), 
                            'REPORT_MODEL_ID' => $reportModelId, 
                            'FIELD_NAME' => Input::param($vk),
                            'IS_VISIBLE' => 1, 
                            'VIEW_ORDER' => ++$v, 
                            'CREATED_USER_ID' => $userKeyId, 
                            'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s'), 
                            'AGGREGATE_NAME' => Input::param($value)
                        );
                        $this->db->AutoExecute('DM_REPORT_MODEL_FACT', $dataFact);
                    }
                }
                
                if (isset($param['field'])) {
                    
                    foreach ($param['field'] as $vkf => $valuef) {
                        
                        $dataField = array(
                            'ID' => getUID(), 
                            'REPORT_MODEL_ID' => $reportModelId, 
                            'FIELD_NAME' => Input::param($vkf),
                            'LABEL_NAME' => Input::param($valuef)
                        );
                        $this->db->AutoExecute('DM_REPORT_MODEL_FIELDS', $dataField);
                    }
                }

                $response = array('status' => 'success', 'message' => Lang::line('msg_save_success'));
            } 

        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => 'Error - createPivotGridSaveModel');
        }

        return $response;
    }

    public function editPivotGridSaveModel() {

        try {

            parse_str(Input::post('param'), $param);
            $reportModelId = Input::paramNum($param['reportModelId']);
            $userKeyId = Ue::sessionUserKeyId();

            $data = array(
                'REPORT_MODEL_NAME' => Input::param($param['reportModelName']),
                'MODIFIED_USER_ID' => $userKeyId, 
                'MODIFIED_DATE' => Date::currentDate('Y-m-d H:i:s')
            );

            $result = $this->db->AutoExecute('DM_REPORT_MODEL', $data, 'UPDATE', 'REPORT_MODEL_ID = '.$reportModelId);

            if ($result) {

                $this->db->Execute("DELETE FROM DM_REPORT_MODEL_FILTER WHERE REPORT_MODEL_ID = $reportModelId");

                if (Input::postCheck('filters')) {
                    foreach ($_POST['filters'] as $f => $filter) {

                        $dataFilter = array(
                            'REPORT_MODEL_FILTER_ID' => getUID(), 
                            'REPORT_MODEL_ID' => $reportModelId, 
                            'FIELD_NAME' => Input::param($filter), 
                            'VIEW_ORDER' => ($f + 1), 
                            'CREATED_USER_ID' => $userKeyId, 
                            'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s')
                        );
                        $this->db->AutoExecute('DM_REPORT_MODEL_FILTER', $dataFilter);
                    }
                }

                $this->db->Execute("DELETE FROM DM_REPORT_MODEL_COLUMN WHERE REPORT_MODEL_ID = $reportModelId");

                if (Input::postCheck('columns')) {
                    foreach ($_POST['columns'] as $c => $column) {

                        $dataColumn = array(
                            'REPORT_MODEL_COLUMN_ID' => getUID(), 
                            'REPORT_MODEL_ID' => $reportModelId, 
                            'FIELD_NAME' => Input::param($column),
                            'IS_VISIBLE' => 1, 
                            'VIEW_ORDER' => ($c + 1), 
                            'CREATED_USER_ID' => $userKeyId, 
                            'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s')
                        );
                        $this->db->AutoExecute('DM_REPORT_MODEL_COLUMN', $dataColumn);
                    }
                }

                $this->db->Execute("DELETE FROM DM_REPORT_MODEL_ROW WHERE REPORT_MODEL_ID = $reportModelId");

                if (Input::postCheck('rows')) {
                    foreach ($_POST['rows'] as $r => $row) {

                        $dataRow = array(
                            'REPORT_MODEL_ROW_ID' => getUID(), 
                            'REPORT_MODEL_ID' => $reportModelId, 
                            'FIELD_NAME' => Input::param($row), 
                            'IS_VISIBLE' => 1, 
                            'VIEW_ORDER' => ($r + 1), 
                            'CREATED_USER_ID' => $userKeyId, 
                            'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s')
                        );
                        $this->db->AutoExecute('DM_REPORT_MODEL_ROW', $dataRow);
                    }
                }

                $this->db->Execute("DELETE FROM DM_REPORT_MODEL_FACT WHERE REPORT_MODEL_ID = $reportModelId");

                if (Input::postCheck('values')) {
                    $v = 0;
                    foreach ($_POST['values'] as $vk => $value) {

                        $dataFact = array(
                            'REPORT_MODEL_FACT_ID' => getUID(), 
                            'REPORT_MODEL_ID' => $reportModelId, 
                            'FIELD_NAME' => Input::param($vk),
                            'IS_VISIBLE' => 1, 
                            'VIEW_ORDER' => ++$v, 
                            'CREATED_USER_ID' => $userKeyId, 
                            'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s'), 
                            'AGGREGATE_NAME' => Input::param($value)
                        );
                        $this->db->AutoExecute('DM_REPORT_MODEL_FACT', $dataFact);
                    }
                }
                
                if (isset($param['field'])) {
                    
                    $keyCols = array('REPORT_MODEL_ID', 'FIELD_NAME');
                    
                    foreach ($param['field'] as $vkf => $valuef) {
                        
                        $dataField = array(
                            'ID' => getUID(), 
                            'REPORT_MODEL_ID' => $reportModelId, 
                            'FIELD_NAME' => Input::param($vkf),
                            'LABEL_NAME' => Input::param($valuef)
                        );
                        $this->db->Replace('DM_REPORT_MODEL_FIELDS', $dataField, $keyCols, $autoquote = true);
                    }
                }

                $response = array('status' => 'success', 'message' => Lang::line('msg_save_success'));
            } 

        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => 'Error - editPivotGridSaveModel');
        }

        return $response;
    }
    
    public function dataViewByProcessModel() {
        
        $param = array();
        $commandName = Input::post('commandName');
        
        if (Input::postCheck('defaultCriteriaData')) {

            parse_str(Input::post('defaultCriteriaData'), $defaultCriteriaData);

            if (isset($defaultCriteriaData['param'])) {
                
                $defaultCriteriaParam = $defaultCriteriaData['param'];

                foreach ($defaultCriteriaParam as $defParam => $defParamVal) {

                    if (is_array($defParamVal)) {

                        $defParamVals = Arr::implode_r(',', $defParamVal, true);

                        if ($defParamVals != '') {
                            $param[$defParam] = $defParamVals;
                        }

                    } else {
                        $param[$defParam] = Input::param(trim($defParamVal));
                    }
                }
            }
        } 
        
        $param['reportModelId'] = Input::post('reportModelId');
        
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, $commandName, $param);
        
        return $result['result'];
    }
    
    public function getRpPivotTemplateListModel($dvId) {
        $data = $this->db->GetAll("SELECT ID, NAME FROM RP_TEMPLATE_HEADER WHERE MAIN_META_DATA_ID = $dvId ORDER BY CREATED_DATE ASC");
        return $data;
    }
    
    public function saveLastChangeTemplateIdModel() {
        
        $this->load->model('mdtemplate', 'middleware/models/');
        
        $dvId       = Input::post('dvId');
        $templateId = Input::post('templateId');
        $userKeyId  = Ue::sessionUserKeyId();
        
        $userConfig = $this->model->getPrintConfigRowByUserModel($dvId, $userKeyId);
        
        if ($userConfig) {
            
            $this->db->AutoExecute('META_GROUP_PRINT_USER', array('PIVOT_TEMPLATE_ID' => $templateId), 'UPDATE', 'ID = '.$userConfig['ID']);
            
        } else {
            
            $data = array(
                'ID'                => getUID(), 
                'DV_META_DATA_ID'   => $dvId, 
                'USER_ID'           => $userKeyId, 
                'CONFIG_STR'        => null, 
                'PIVOT_TEMPLATE_ID' => $templateId
            );
            
            $this->db->AutoExecute('META_GROUP_PRINT_USER', $data);
        }
        
        return true;
    }
    
    public function kpiIndicatorToPivotConfigModel($row, $sql, $indicatorColumns) {
        
        try {
            
            $indicatorId = $row['ID'];
            $newId = getUID();
            
            $data = array(
                'ID'                => $newId, 
                'MAIN_META_DATA_ID' => $indicatorId, 
                'NAME'              => Lang::line($row['NAME']), 
                'SESSION_ID'        => Ue::appUserSessionId(), 
                'CREATED_USER_ID'   => Ue::sessionUserKeyId(), 
                'CREATED_DATE'      => Date::currentDate('Y-m-d H:i:s')
            );
            
            $result = $this->db->AutoExecute('RP_DESIGN_HEADER', $data);
            
            if ($result) {
                
                $sql = Str::remove_doublewhitespace(trim($sql));
                $this->db->UpdateClob('RP_DESIGN_HEADER', 'QUERY_STRING', $sql, 'ID = '.$newId);
                
                $beforeDate = Date::beforeDate('Y-m-d', '-2 days');
        
                $this->db->Execute("DELETE FROM RP_DESIGN_HEADER WHERE CREATED_DATE < ".$this->db->ToDate("'$beforeDate'", 'YYYY-MM-DD'));
                $this->db->Execute('DELETE FROM RP_DESIGN_COLUMN WHERE MAIN_META_DATA_ID = '.$this->db->Param(0), array($indicatorId));
                
                foreach ($indicatorColumns as $c => $col) {
                    
                    if ($col['IS_RENDER'] == '1') {
                        
                        $showType = $col['SHOW_TYPE'];
                        $columnName = ($showType == 'combo') ? $col['COLUMN_NAME'] . '_DESC' : $col['COLUMN_NAME'];

                        if ($showType == 'text' || $showType == 'combo') {
                            $showType = 'string';
                        } elseif ($showType == 'decimal') {
                            $showType = 'bigdecimal';
                        }

                        $data = array(
                            'ID'                => getUIDAdd($c), 
                            'MAIN_META_DATA_ID' => $indicatorId, 
                            'PARAM_PATH'        => $columnName, 
                            'LABEL_NAME'        => Lang::line($col['LABEL_NAME']), 
                            'DATA_TYPE'         => $showType, 
                            'ORDER_NUMBER'      => $c + 1
                        );
                        $this->db->AutoExecute('RP_DESIGN_COLUMN', $data);
                    }
                }
                
                $result = array('status' => 'success', 'reportId' => $newId, 'dvId' => $indicatorId);
            } else {
                $result = array('status' => 'error', 'message' => 'Unkhown error!');
            }
            
        } catch (Exception $ex) {
            $result = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $result;
    }

}