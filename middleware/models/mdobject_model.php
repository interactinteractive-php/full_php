<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdobject_Model extends Model {

    private static $gfServiceAddress = GF_SERVICE_ADDRESS;
    private static $panelOpenDataRows = array();

    public function __construct() {
        parent::__construct();
    }

    public function getDVGridOptionsModel($dataViewId, $isNotUserLoad = false) {

        $cache = phpFastCache();
        $result = $cache->get('dvGridOption_'.$dataViewId);

        if ($result == null) {
            
            $dvMetaIdPh = $this->db->Param(0);
            
            if (empty($dataViewId)) {
                $result = array_change_key_case(Mdobject::gridDefaultOptions(), CASE_UPPER);
            } else {

                $row = $this->db->GetRow("SELECT MGGO.*, ".$this->db->IfNull('MGGO.SHOWTOOLBAR', '1')." AS SHOWTOOLBAR FROM META_GROUP_GRID_OPTIONS MGGO WHERE MGGO.MAIN_META_DATA_ID = $dvMetaIdPh", array($dataViewId));

                if ($row) {
                    
                    $row['MERGECELLSKEYFIELD'] = strtolower($row['MERGECELLSKEYFIELD']);
                    
                    if ($row['GROUPFIELD']) {
                        
                        $fieldStyle = $this->db->GetRow("
                            SELECT 
                                LABEL_NAME, 
                                TEXT_COLOR, 
                                TEXT_TRANSFORM, 
                                TEXT_WEIGHT 
                            FROM META_GROUP_CONFIG 
                            WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                                AND IS_SELECT = 1 
                                AND FIELD_PATH = ".$this->db->Param(1), 
                                
                            array($dataViewId, $row['GROUPFIELD'])
                        );
                        
                        if ($fieldStyle) {
                            
                            $textStyle = null;
                            
                            if ($fieldStyle['TEXT_COLOR']) {
                                $textStyle .= 'color:'.$fieldStyle['TEXT_COLOR'].';';
                            }
                            
                            if ($fieldStyle['TEXT_TRANSFORM']) {
                                $textStyle .= 'text-transform:'.$fieldStyle['TEXT_COLOR'].';';
                            }
                            
                            if ($fieldStyle['TEXT_WEIGHT']) {
                                $textStyle .= 'font-weight:'.$fieldStyle['TEXT_WEIGHT'].';';
                            }
                            
                            if ($textStyle) {
                                $row['GROUPFIELDSTYLER'] = $textStyle;
                            }
                        }
                        
                        if ($row['GROUPFIELDUSER'] == 'true') {
                            $row['GROUPFIELDLABELNAME'] = $fieldStyle['LABEL_NAME'];
                        }
                    }
                    
                    $result = $row;
                    
                } else {
                    $result = array_change_key_case(Mdobject::gridDefaultOptions(), CASE_UPPER);
                }
            }
            
            $configRow = self::getDataViewConfigRowModel($dataViewId); 
            
            $result['BEFORE_SERVICE']   = issetDefaultVal($configRow['BEFORE_SERVICE'], null);
            $result['IS_USE_RESULT']    = issetDefaultVal($configRow['IS_USE_RESULT'], null);
            $result['WS_URL']           = issetDefaultVal($configRow['WS_URL'], null);
            $result['DV_WS_URL']        = issetDefaultVal($configRow['DV_WS_URL'], null);
            $result['QS_META_DATA_ID']  = issetDefaultVal($configRow['QS_META_DATA_ID'], null);
            $result['IS_FILTER_LOG']    = issetDefaultVal($configRow['IS_FILTER_LOG'], null);
            $result['IS_PARENT_FILTER'] = issetDefaultVal($configRow['IS_PARENT_FILTER'], null);
            $result['IS_CRYPTED_FIELD'] = issetDefaultVal($configRow['IS_CRYPTED_FIELD'], null);
            $result['multiLayoutTypes'] = (isset($configRow['dataViewLayoutTypes']) && $configRow['dataViewLayoutTypes']) ? true : false;
            
            $isIgnoreFirstLoad = $this->db->GetOne("SELECT ID FROM CUSTOMER_DV_IGNORE_LOAD WHERE DV_META_DATA_ID = $dvMetaIdPh AND IS_ACTIVE = 1", array($dataViewId));
            $aggregateAliasPath = $this->db->GetAll("SELECT LOWER(FIELD_PATH) AS FIELD_PATH, LOWER(AGGREGATE_ALIAS_PATH) AS AGGREGATE_ALIAS_PATH FROM META_GROUP_CONFIG WHERE MAIN_META_DATA_ID = $dvMetaIdPh AND COLUMN_AGGREGATE IS NOT NULL AND AGGREGATE_ALIAS_PATH IS NOT NULL", array($dataViewId));
            
            $result['aggregateAliasPath'] = $aggregateAliasPath;
            
            if ($isIgnoreFirstLoad) {
                $result['isIgnoreFirstLoad'] = true;
            } else {
                $result['isIgnoreFirstLoad'] = false;
            }
            
            if ($result['DRILLDBLCLICKROW'] == 'true') {
                $result['DRILL_CLICK_FNC'] = self::drillClickJsFunctionModel($configRow['META_DATA_CODE'], $dataViewId);
            }

            $cache->set('dvGridOption_'.$dataViewId, $result, Mdwebservice::$expressionCacheTime);
        }
        
        if (!array_key_exists('INLINEEDIT', $result)) {
            $result['INLINEEDIT'] = null;
        }
        
        if (!array_key_exists('DRILLDBLCLICKROW', $result)) {
            $result['DRILLDBLCLICKROW'] = null;
        }
        
        if (!array_key_exists('GROUPFIELD', $result)) {
            $result['GROUPFIELD'] = null;
        }
        
        if (!$isNotUserLoad && isset($result['multiLayoutTypes']) && $result['multiLayoutTypes']) {
        
            $userConfig = self::getDataViewConfigUserModel($dataViewId);

            if (isset($userConfig['DEFAULTVIEWER']) && $userConfig['DEFAULTVIEWER']) {
                $result['DETAULTVIEWER'] = $userConfig['DEFAULTVIEWER'];
            }
        }

        return $result;
    }
    
    public function getDataViewConfigRowModel($metaDataId) {

        $cache = phpFastCache();

        $row = $cache->get('dvConfig_' . $metaDataId);
        
        if ($row == null) {
            
            $metaDataIdPh = $this->db->Param(0);
            
            $row = $this->db->GetRow("
                SELECT 
                    GL.ID, 
                    ".$this->db->IfNull("GL.LIST_NAME", "MD.META_DATA_NAME")." AS LIST_NAME, 
                    MFM.FOLDER_ID, 
                    (
                        SELECT 
                            COUNT(MDL.META_DATA_ID) 
                        FROM META_DASHBOARD_LINK MDL
                            LEFT JOIN META_GROUP_LINK MGL ON MDL.PROCESS_META_DATA_ID = MGL.META_DATA_ID
                        WHERE MDL.PROCESS_META_DATA_ID = $metaDataIdPh OR MGL.REF_META_GROUP_ID = $metaDataIdPh
                    ) AS COUNT_DASHBOARD_LINK, 
                    GL.SEARCH_TYPE,  
                    GL.FORM_CONTROL,  
                    (
                        SELECT 
                            COUNT(DTL.TEMPLATE_META_DATA_ID) 
                        FROM META_DM_TEMPLATE_DTL DTL    
                            INNER JOIN META_GROUP_LINK LINK ON LINK.ID = DTL.META_GROUP_LINK_ID 
                        WHERE LINK.META_DATA_ID = $metaDataIdPh
                    ) AS COUNT_REPORT_TEMPLATE, 
                    (
                        SELECT 
                            COUNT(DTL.STATEMENT_META_DATA_ID) 
                        FROM META_DM_STATEMENT_DTL DTL    
                            INNER JOIN META_GROUP_LINK LINK ON LINK.ID = DTL.META_GROUP_LINK_ID 
                        WHERE LINK.META_DATA_ID = $metaDataIdPh 
                    ) AS COUNT_STATEMENT_TEMPLATE, 
                    (
                        SELECT 
                            COUNT(MMM.ID)  
                        FROM META_GROUP_CONFIG MMM
                        WHERE MMM.MAIN_META_DATA_ID = $metaDataIdPh 
                            AND (MMM.DATA_TYPE = 'coordinate' OR MMM.DATA_TYPE = 'region')
                    ) AS COUNT_GOOGLE_MAP, 
                    (
                        SELECT 
                            COUNT(ID)  
                        FROM META_GROUP_CONFIG 
                        WHERE MAIN_META_DATA_ID = $metaDataIdPh 
                            AND LOWER(FIELD_PATH) = 'rowcolor' 
                    ) AS COUNT_ROWCOLOR, 
                    (
                        SELECT 
                            COUNT(ID)  
                        FROM META_GROUP_CONFIG 
                        WHERE MAIN_META_DATA_ID = $metaDataIdPh 
                            AND LOWER(FIELD_PATH) = 'textcolor' 
                    ) AS COUNT_TEXTCOLOR, 
                    (
                        SELECT 
                            COUNT(ID) 
                        FROM META_GROUP_CONFIG  
                        WHERE MAIN_META_DATA_ID = $metaDataIdPh 
                            AND PARENT_ID IS NULL 
                            AND IS_COUNTCARD = 1 
                    ) AS COUNT_CARD, 
                    (
                        SELECT 
                            COUNT(ID) 
                        FROM META_GROUP_CONFIG 
                        WHERE MAIN_META_DATA_ID = $metaDataIdPh 
                            AND COLUMN_NAME IS NOT NULL 
                            AND IS_SELECT = 1 
                            AND IS_SHOW = 1 
                            AND IS_CRYPTED = 1 
                    ) AS IS_CRYPTED_FIELD, 
                    (
                        SELECT
                            SUM(REP_RESULT_COUNT.COUNT_REP)
                        FROM (
                            (
                                SELECT
                                    COUNT(REP.ID) COUNT_REP
                                FROM META_REPORT_TEMPLATE_LINK REP
                                    LEFT JOIN META_DM_TEMPLATE_DTL DTL ON REP.META_DATA_ID = DTL.TEMPLATE_META_DATA_ID
                                    LEFT JOIN META_DATA MD ON MD.META_DATA_ID = REP.META_DATA_ID
                                WHERE MD.IS_ACTIVE = 1
                                    AND (MD.IS_AUTO_CREATED = 0 OR MD.IS_AUTO_CREATED IS NULL)
                                    AND MD.IS_SYSTEM = 1
                                    AND REP.DATA_MODEL_ID = $metaDataIdPh
                            )
                            UNION ALL
                            (
                                SELECT
                                    COUNT(REP.ID) COUNT_REP
                                FROM META_DM_TEMPLATE_DTL DTL
                                    INNER JOIN META_GROUP_LINK LINK ON LINK.ID = DTL.META_GROUP_LINK_ID
                                    INNER JOIN META_DATA MD ON MD.META_DATA_ID = LINK.META_DATA_ID
                                    INNER JOIN META_REPORT_TEMPLATE_LINK REP ON REP.META_DATA_ID = DTL.TEMPLATE_META_DATA_ID
                                    INNER JOIN META_DATA LMD ON LMD.META_DATA_ID = REP.META_DATA_ID
                                WHERE MD.META_DATA_ID = $metaDataIdPh
                            )
                        ) REP_RESULT_COUNT 
                    ) AS C_REPORT_TEMPLATE, 
                    GL.LAYOUT_META_DATA_ID, 
                    MD.META_DATA_CODE, 
                    GL.REF_STRUCTURE_ID, 
                    GL.IS_ALL_NOT_SEARCH ,
                    GL.IS_USE_RT_CONFIG, 
                    GL.IS_USE_WFM_CONFIG,
                    GL.IS_USE_SIDEBAR,
                    GL.IS_USE_QUICKSEARCH,
                    GL.IS_EXPORT_TEXT, 
                    GL.BUTTON_BAR_STYLE,
                    GL.REFRESH_TIMER,
                    GL.M_CRITERIA_COL_COUNT,
                    GL.M_GROUP_CRITERIA_COL_COUNT,
                    GL.USE_BASKET, 
                    GL.IS_LOOKUP_BY_THEME, 
                    GL.IS_COUNTCARD_OPEN, 
                    (
                        SELECT 
                            COUNT(MCL.ID)
                        FROM META_CALENDAR_LINK MCL 
                        WHERE MCL.TRG_META_DATA_ID = $metaDataIdPh
                    ) AS COUNT_CALENDAR_SEE, 
                    (
                        SELECT 
                            MAX(MCL.META_DATA_ID)
                        FROM META_CALENDAR_LINK MCL
                        WHERE MCL.TRG_META_DATA_ID = $metaDataIdPh
                    ) AS CALENDAR_META_DATA_ID, 
                    (
                        SELECT 
                            COUNT(ID) 
                        FROM CUSTOMER_DV_FIELD 
                        WHERE META_DATA_ID = $metaDataIdPh 
                            AND IS_ACTIVE = 1 
                            AND (IS_IGNORE_TREE_GROUP IS NULL OR IS_IGNORE_TREE_GROUP = 0)
                    ) AS COUNT_CUSTOMER_FIELD, 
                    (
                        SELECT 
                            SUBGRIDEXCEL  
                        FROM META_GROUP_GRID_OPTIONS 
                        WHERE MAIN_META_DATA_ID = $metaDataIdPh 
                    ) AS SUBGRIDEXCEL, 
                    CASE 
                        WHEN (
                            SELECT 
                                COUNT(ID) 
                            FROM KNOWLEDGE_META_DATA_MAP 
                            WHERE META_DATA_ID = $metaDataIdPh 
                        ) > 0 
                        THEN 1 
                    ELSE 0 END AS IS_KNOWLEDGE, 
                    MW.WS_URL, 
                    GL.CALCULATE_PROCESS_ID, 
                    GL.BEFORE_SERVICE, 
                    GL.IS_USE_RESULT, 
                    GL.IS_PRINT_COPIES, 
                    GL.QS_META_DATA_ID, 
                    GL.IS_IGNORE_EXCEL_EXPORT, 
                    GL.IS_USE_DATAMART, 
                    GL.LIST_MENU_NAME, 
                    GL.SHOW_POSITION, 
                    GL.IS_CRITERIA_ALWAYS_OPEN, 
                    GL.IS_ENTER_FILTER, 
                    GL.IS_FILTER_LOG, 
                    GL.PANEL_TYPE, 
                    GL.IS_IGNORE_WFM_HISTORY, 
                    GL.IS_DIRECT_PRINT, 
                    GL.IS_CLEAR_DRILL_CRITERIA, 
                    GL.DATA_LEGEND_DV_ID, 
                    GL.IS_PARENT_FILTER, 
                    GL.IS_USE_SEMANTIC, 
                    GL.IS_USE_BUTTON_MAP,
                    MGL.LAYOUT_TYPE, 
                    P_XSL_XPRT.ID AS IS_EXCEL_EXPORT_BTN, 
                    P_INV_PRNT.ID AS IS_INVOICE_PRINT_BTN, 
                    GL.WINDOW_HEIGHT, 
                    GL.WINDOW_WIDTH, 
                    GL.WINDOW_SIZE, 
                    GL.IS_SHOW_FILTER_TEMPLATE, 
                    GL.IS_FIRST_COL_FILTER, 
                    GL.COLOR_SCHEMA, 
                    GL.IS_IGNORE_CLEAR_FILTER, 
                    GL.WS_URL AS DV_WS_URL, 
                    MD.DESCRIPTION, 
                    GL.DRAG_ROWS_RUN_PROCESS_ID 
                FROM META_GROUP_LINK GL 
                    INNER JOIN META_DATA MD ON MD.META_DATA_ID = GL.META_DATA_ID 
                    LEFT JOIN META_DATA_FOLDER_MAP MFM ON MFM.META_DATA_ID = GL.META_DATA_ID 
                    LEFT JOIN CUSTOMER_META_WS MW ON MW.SRC_META_DATA_ID = GL.META_DATA_ID 
                        AND MW.TRG_META_DATA_ID IS NULL  
                    LEFT JOIN META_GROUP_GRID_LAYOUT MGL ON GL.META_DATA_ID = MGL.MAIN_META_DATA_ID 
                    LEFT JOIN META_DM_PROCESS_DTL P_XSL_XPRT ON P_XSL_XPRT.MAIN_META_DATA_ID = GL.META_DATA_ID 
                        AND P_XSL_XPRT.PROCESS_META_DATA_ID = 1552530510019085 
                    LEFT JOIN META_DM_PROCESS_DTL P_INV_PRNT ON P_INV_PRNT.MAIN_META_DATA_ID = GL.META_DATA_ID 
                        AND P_INV_PRNT.PROCESS_META_DATA_ID = 1557903549031430     
                WHERE GL.META_DATA_ID = $metaDataIdPh", array($metaDataId));
            
            if (!$row) {
                echo 'No dvId: ' . $metaDataId; exit;
            }

            $row['idField']             = self::getStandartFieldModel($metaDataId, 'meta_value_id');
            $row['codeField']           = self::getStandartFieldModel($metaDataId, 'meta_value_code');
            $row['nameField']           = self::getStandartFieldModel($metaDataId, 'meta_value_name');
            $row['parentField']         = self::getStandartFieldModel($metaDataId, 'parent_id');
            $row['uniqueField']         = self::getStandartFieldModel($metaDataId, 'unique_id');
            
            $row['TREE_GRID']           = self::getIsTreeGridModel($metaDataId); 
            $row['COUNT_WFM_WORKFLOW']  = self::isDataViewWorkFlow($metaDataId);
            
            $row['dataViewLayoutTypes'] = self::getDataViewLayoutTypesModel($metaDataId);
            $row['subgrid']             = self::isSubDataView($metaDataId, $row['SUBGRIDEXCEL']);
            $row['checklist']           = self::isRefStructureCheckList($metaDataId, $row['REF_STRUCTURE_ID']);
            $row['subQuery']            = self::getSubQueryModel($row['ID']);
            $row['mainProcess']         = self::getMainProcessModel($metaDataId);
            $row['reportTemplate']      = self::getDataViewReportTemplatesModel($metaDataId);
            $row['treeCategoryList']    = self::getTreeCategoryList($metaDataId);
            $row['columnShowCriteria']  = self::getColumnShowCriteriaModel($metaDataId);

            $cache->set('dvConfig_'.$metaDataId, $row, Mdwebservice::$expressionCacheTime);
        }

        return $row;
    }
    
    public function getDataViewConfigUserModel($dataViewId) {
        
        $sessionUserId = Ue::sessionUserId();
        
        if ($sessionUserId) {
        
            $cache = phpFastCache();
            $row = $cache->get('dvUserConfig_'.$dataViewId.'_'.$sessionUserId);

            if ($row == null) {

                $row = $this->db->GetRow("
                    SELECT * 
                    FROM CUSTOMER_META_USER_CONFIG 
                    WHERE META_DATA_ID = ".$this->db->Param(0)." 
                        AND USER_ID = ".$this->db->Param(1), 
                    array($dataViewId, $sessionUserId)
                );

                $cache->set('dvUserConfig_'.$dataViewId.'_'.$sessionUserId, $row, Mdwebservice::$expressionCacheTime);
            }

            return $row;
        } else {
            return null;
        }
    }
    
    public function drillClickJsFunctionModel($metaDataCode, $dataViewId) {
        
        $hdrData = $this->db->GetAll("
            SELECT 
                MDD.ID, 
                MDD.LINK_META_DATA_ID, 
                MDD.CRITERIA, 
                MDD.SHOW_TYPE, 
                LOWER(MT.META_TYPE_CODE) AS META_TYPE_CODE,
                MDD.DIALOG_WIDTH,
                MDD.DIALOG_HEIGHT
            FROM META_GROUP_LINK MGL 
                INNER JOIN META_DM_DRILLDOWN_DTL MDD ON MDD.MAIN_GROUP_LINK_ID = MGL.ID 
                INNER JOIN META_DATA MDA ON MDD.LINK_META_DATA_ID = MDA.META_DATA_ID 
                INNER JOIN META_TYPE MT ON MDA.META_TYPE_ID = MT.META_TYPE_ID   
            WHERE MGL.META_DATA_ID = ".$this->db->Param(0), 
            array($dataViewId) 
        );
        
        $click = null;
        
        if ($hdrData) {
            
            $linkMetaCount = count($hdrData);
            $sourceParam = $metaTypeCode = $linkMetaDataId = $criteria = $dialogWidth = $dialogHeight = '';
            $isnewTab = 'false';
            
            if ($hdrData[0]['SHOW_TYPE']) {
                $showType = explode(',', strtolower($hdrData[0]['SHOW_TYPE']));
                if ($showType[0] == 'tab') {
                    $isnewTab = 'true';
                } elseif (isset($showType[0]) && $showType[0]) {
                    $isnewTab = "'".$showType[0]."'";
                }
            }
            
            foreach ($hdrData as $hdr) {
                
                $dtlSourceParam  = '';
                $metaTypeCode   .= $hdr['META_TYPE_CODE'] . ',';
                $linkMetaDataId .= $hdr['LINK_META_DATA_ID'] . ',';
                $criteria       .= $hdr['CRITERIA'] . ',';
                $dialogWidth    .= $hdr['DIALOG_WIDTH'] . ',';
                $dialogHeight   .= $hdr['DIALOG_HEIGHT'] . ',';
                
                $dtlData = $this->db->GetAll("
                    SELECT 
                        LOWER(SRC_PARAM) AS SRC_PARAM, 
                        LOWER(TRG_PARAM) AS TRG_PARAM, 
                        DEFAULT_VALUE 
                    FROM META_DM_DRILLDOWN_PARAM  
                    WHERE DM_DRILLDOWN_DTL_ID = ".$this->db->Param(0), 
                    array($hdr['ID']) 
                );
                
                if ($dtlData) {
                    
                    foreach ($dtlData as $dtl) {
                        
                        if ($dtl['DEFAULT_VALUE']) {
                            
                            if ($dtl['TRG_PARAM'] && $dtl['DEFAULT_VALUE']) {
                                $dtlSourceParam .= ($dtl['TRG_PARAM']) ? $dtl['TRG_PARAM'] . '=' . $dtl['DEFAULT_VALUE'] . '&' : '';
                            } else {
                                $dtlSourceParam .= $dtl['DEFAULT_VALUE'] . '&';
                            }
                            
                        } else {
                            $dtlSourceParam .= ($dtl['TRG_PARAM']) ? $dtl['TRG_PARAM'] . "='+ row." . $dtl['SRC_PARAM'] ." +'&" : '';
                        }
                    }
                }
                
                $sourceParam .= rtrim($dtlSourceParam, '&') . ',';
            }
            
            $criteria = str_replace(array('&lt;', '&gt;'), array('<', '>'), $criteria);
            
            $click = "gridDrillDownLink(this, '$metaDataCode', '". rtrim($metaTypeCode, ',') ."', $linkMetaCount, '". str_replace("'", "\\\\\'", rtrim($criteria, ',')) ."', '$dataViewId', 'id', '". rtrim($linkMetaDataId, ',') ."', '".rtrim(rtrim($sourceParam, '&'), ',')."', $isnewTab, undefined, '". $dialogWidth ."', '". $dialogHeight ."');";
        }
        
        return $click;
    }

    public function getLinkedComboMetaGroup($inputMetaDataId, $selfParam, $isProcess = false) {
        
        $bindVars = array($inputMetaDataId, strtolower($selfParam));
        
        if ($isProcess) {
            $row = $this->db->GetRow("
                SELECT 
                    PAL.LOOKUP_META_DATA_ID, 
                    PAL.PARAM_REAL_PATH AS FIELD_PATH, 
                    MD.META_TYPE_ID AS LOOKUP_META_TYPE_ID 
                FROM META_PROCESS_PARAM_ATTR_LINK PAL 
                    INNER JOIN META_DATA MD ON MD.META_DATA_ID = PAL.LOOKUP_META_DATA_ID 
                WHERE PAL.PROCESS_META_DATA_ID = ".$this->db->Param(0)." 
                    AND LOWER(PAL.PARAM_REAL_PATH) = ".$this->db->Param(1), $bindVars);
        } else {
            $row = $this->db->GetRow("
                SELECT 
                    GC.LOOKUP_META_DATA_ID,
                    GC.FIELD_PATH, 
                    MD.META_TYPE_ID AS LOOKUP_META_TYPE_ID 
                FROM META_GROUP_CONFIG GC 
                    INNER JOIN META_DATA MD ON MD.META_DATA_ID = GC.LOOKUP_META_DATA_ID 
                WHERE GC.MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                    AND LOWER(GC.FIELD_PATH) = ".$this->db->Param(1), $bindVars);
        }

        return $row;
    }

    public function responseDataForProcessLinkedCombo($processMetaDataId, $metaDataId, $selfParam, $inputParamArr) {
        
        $param = array(
            'systemMetaGroupId' => $metaDataId,
            'showQuery' => 0, 
            'ignorePermission' => 1 
        );
        $paramCriteria = $array = array();

        $data = self::getGroupParamConfigByProcess($processMetaDataId, $selfParam);

        foreach ($data as $row) {
            
            $criteriaValue = $row['DEFAULT_VALUE'];
            
            if (isset($inputParamArr[$row['PARAM_PATH']])) {
                $criteriaValue = $inputParamArr[$row['PARAM_PATH']];
            }

            $paramCriteria[$row['FIELD_NAME']][] = !empty($criteriaValue) ?
                array(
                    'operator' => strpos($criteriaValue, '%2C') !== false ? 'in' : '=',
                    'operand' => urldecode(strpos($criteriaValue, '%2C') !== false ? str_replace('%2C', ",", $criteriaValue) : $criteriaValue)
                ) :
                array(
                    'operator' => 'IS NULL',
                    'operand' => $criteriaValue
                );
        }
        $param['criteria'] = $paramCriteria;

        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($result['status'] == 'success' && isset($result['result'])) {
            
            $row = $this->db->GetRow("
                SELECT 
                    LOWER(DISPLAY_FIELD) AS DISPLAY_FIELD, 
                    LOWER(VALUE_FIELD) AS VALUE_FIELD 
                FROM META_PROCESS_PARAM_ATTR_LINK 
                WHERE PROCESS_META_DATA_ID = ".$this->db->Param(0)." 
                    AND IS_INPUT = 1 
                    AND LOWER(PARAM_REAL_PATH) = ".$this->db->Param(1), 
                array($processMetaDataId, strtolower($selfParam))
            );
            
            if ($row) {
                
                $valueField = $row['VALUE_FIELD'];
                $displayField = $row['DISPLAY_FIELD'];
                
                unset($result['result']['aggregatecolumns']);
                unset($result['result']['paging']);

                foreach ($result['result'] as $k => $v) {
                    $array[$k]['META_VALUE_ID'] = $v[$valueField];
                    $array[$k]['META_VALUE_CODE'] = $v[$displayField];
                    $array[$k]['META_VALUE_NAME'] = $v[$displayField];
                    $array[$k]['ROW_DATA'] = $v;
                }
            }
        }
        
        return array($selfParam => $array);
    }
    
    public function responseDataForMetaGroupLinkedCombo($inputMetaDataId, $metaDataId, $selfParam, $inputParamArr) {
        
        $param = array(
            'systemMetaGroupId' => $metaDataId,
            'showQuery' => 0, 
            'ignorePermission' => 1 
        );
        $paramCriteria = $array = array();

        $data = self::getGroupParamConfigByDataView($inputMetaDataId, $selfParam);

        foreach ($data as $row) {
            
            $criteriaValue = $row['DEFAULT_VALUE'];
            
            if (isset($inputParamArr[$row['PARAM_PATH']])) {
                $criteriaValue = $inputParamArr[$row['PARAM_PATH']];
            }

            $paramCriteria[$row['FIELD_NAME']][] = !empty($criteriaValue) ?
                array(
                    'operator' => '=',
                    'operand' => $criteriaValue
                ) :
                array(
                    'operator' => 'IS NULL',
                    'operand' => $criteriaValue
                );
        }
        $param['criteria'] = $paramCriteria;

        $result = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($result['status'] == 'success' && isset($result['result'][0])) {
            
            $row = $this->db->GetRow("
                SELECT 
                    LOWER(DISPLAY_FIELD) AS DISPLAY_FIELD, 
                    LOWER(VALUE_FIELD) AS VALUE_FIELD 
                FROM META_GROUP_CONFIG  
                WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)."  
                    AND LOWER(FIELD_PATH) = ".$this->db->Param(1), 
                array($inputMetaDataId, strtolower($selfParam))
            );
            
            if ($row) {
                
                $valueField = $row['VALUE_FIELD'];
                $displayField = $row['DISPLAY_FIELD'];
                
                unset($result['result']['aggregatecolumns']);
                unset($result['result']['paging']);
                
                $firstRow = $result['result'][0];
                
                if (array_key_exists($valueField, $firstRow) && array_key_exists($displayField, $firstRow)) {

                    foreach ($result['result'] as $k => $v) {
                        $array[$k]['META_VALUE_ID'] = $v[$valueField];
                        $array[$k]['META_VALUE_CODE'] = $v[$displayField];
                        $array[$k]['META_VALUE_NAME'] = $v[$displayField];
                        $array[$k]['ROW_DATA'] = $v;
                    }
                }
            }
        }
        
        return array($selfParam => $array);
    }

    public function getGroupParamConfigByDataView($inputMetaDataId, $selfParam) {
        
        $selfParamLower = strtolower($selfParam);
        $idPh1 = $this->db->Param(0);
        $idPh2 = $this->db->Param(1);
        
        $data = $this->db->GetAll("
            SELECT 
                PARAM_PATH, 
                DEFAULT_VALUE, 
                PARAM_META_DATA_CODE AS FIELD_NAME  
            FROM META_GROUP_PARAM_CONFIG PC 
            WHERE GROUP_META_DATA_ID = $idPh1 
                AND LOWER(FIELD_PATH) = $idPh2 
                AND (IS_GROUP = 0 OR IS_GROUP IS NULL)", 
            array($inputMetaDataId, $selfParamLower)
        );

        if (count($data) == 0) {
            
            $data = $this->db->GetAll("
                SELECT 
                    PARAM_PATH, 
                    DEFAULT_VALUE, 
                    PARAM_META_DATA_CODE AS FIELD_NAME  
                FROM META_GROUP_PARAM_CONFIG 
                WHERE GROUP_META_DATA_ID = $idPh1 
                    AND LOWER(FIELD_PATH) = $idPh2 
                    AND IS_GROUP = 1", 
                array($inputMetaDataId, $selfParamLower)
            );                
        }

        return $data;
    }
    
    public function getGroupParamConfigByProcess($processMetaDataId, $selfParam) {
        
        $selfParamLower = strtolower($selfParam);
        
        $data = $this->db->GetAll("
            SELECT 
                PARAM_PATH, 
                DEFAULT_VALUE, 
                PARAM_META_DATA_CODE AS FIELD_NAME  
            FROM META_GROUP_PARAM_CONFIG PC 
            WHERE MAIN_PROCESS_META_DATA_ID = $processMetaDataId 
                AND LOWER(FIELD_PATH) = '$selfParamLower' 
                AND (IS_GROUP = 0 OR IS_GROUP IS NULL)");

        if (count($data) == 0) {
            
            $data = $this->db->GetAll("
                SELECT 
                    PARAM_PATH, 
                    DEFAULT_VALUE, 
                    PARAM_META_DATA_CODE AS FIELD_NAME  
                FROM META_GROUP_PARAM_CONFIG  
                WHERE MAIN_PROCESS_META_DATA_ID = $processMetaDataId 
                    AND LOWER(FIELD_PATH) = '$selfParamLower' 
                    AND IS_GROUP = 1");                
        }

        return $data;
    }

    public function getMetaDataObjectTypeRowByCodeModel($code) {
        $row = $this->db->GetRow("
            SELECT 
                MD.META_DATA_ID, 
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME 
            FROM META_DATA MD 
                INNER JOIN META_GROUP_LINK GL ON GL.META_DATA_ID = MD.META_DATA_ID 
            WHERE LOWER(MD.META_DATA_CODE) = ".$this->db->Param(0)." 
                AND MD.IS_ACTIVE = 1 
                AND LOWER(GL.GROUP_TYPE) IN ('tablestructure', 'dataview')", 
            array($code));

        return $row;
    }

    public function getDataViewGridHeaderModel($metaDataId, $condition = '1 = 1', $showType = 1, $isPopupWindow = false, $isBasketWindow = false) {

        if ($showType == 1) {
            $whereStr = ' AND GC.IS_SHOW = 1 AND (GC.IS_SIDEBAR != 1 OR GC.IS_SIDEBAR IS NULL) ';
        } elseif ($showType == 2) {
            $whereStr = ' AND GC.IS_SIDEBAR = 1 ';
        } elseif ($showType == 3) {
            $whereStr = ' AND GC.IS_SELECT = 1 ';
        }
        
        $crmUserId = Session::get(SESSION_PREFIX . 'crmuserid');
        $userId = $crmUserId ? $crmUserId : Ue::sessionUserId();

        if ($isPopupWindow) {
            $basketDataCheck = self::getBasketDataViewGridHeaderModel($metaDataId, $condition);

            if (count($basketDataCheck) > 0) {
                return $basketDataCheck;
            }
        }
        
        if ($isBasketWindow) {
            $basketDataCheck = self::getBasketDataViewGridHeaderModel($metaDataId, $condition, 'AND GC.IS_BASKET = 1', "GC.IS_BASKET AS IS_SHOW, '' AS GROUP_CONFIG_GROUP_PATH , '' AS GROUP_PARAM_CONFIG_TOTAL, GC.IS_REQUIRED, LOWER(GC.FIELD_PATH) AS PARAM_REAL_PATH, GC.DEFAULT_VALUE, ");
            
            if (count($basketDataCheck) > 0) {
                return $basketDataCheck;
            }
        }
        
        $gridOption = self::getDVGridOptionsModel($metaDataId);
        
        $metaDataIdPh = $this->db->Param(0);
        $userIdPh = $this->db->Param(1);
        
        $data = $this->db->GetAll("
            SELECT 
                ".$this->db->IfNull('CK.HEADER_NAME', 'GC.LABEL_NAME')." AS LABEL_NAME, 
                GC.FIELD_NAME, 
                GC.FIELD_PATH, 
                GC.SIDEBAR_NAME, 
                GC.META_TYPE_CODE, 
                GC.COLUMN_WIDTH, 
                GC.TEXT_WEIGHT, 
                GC.TEXT_COLOR, 
                GC.HEADER_ALIGN, 
                GC.BODY_ALIGN, 
                GC.TEXT_TRANSFORM, 
                GC.BG_COLOR, 
                GC.FONT_SIZE, 
                GC.MAX_VALUE,
                '' AS COLUMN_AGGREGATE, 
                '' AS IS_BOLD, 
                GC.ID,
                GC.IS_MERGE,
                GC.IS_BASKET_EDIT,
                GC.FRACTION_RANGE,
                GC.EXCEL_COLUMN_WIDTH,
                GC.EXCEL_ROTATE, 
                GC.IS_IGNORE_EXCEL, 
                GC.INLINE_PROCESS_ID,
                GC.LOOKUP_META_DATA_ID,
                GC.DEFAULT_VALUE, 
                GC.IS_DEFAULT_FREEZE, 
                GC.RENDER_TYPE, 
                GC.MAIN_META_DATA_ID, 
                GC.COLUMN_NAME, 
                CH.COUNTT AS USER_CONFIG_COUNT, 
                CK.PARAM_WIDTH,
                0 AS DV_COLSPAN, 
                1 AS IS_SHOW, 
                MDD.COUNTT AS DRILLDOWN_COLUMN, 
                MDD.IGNORE_DRILL_META, 
                MDD.LINK_META_DATA_ID, 
                MDD.DRILLDOWN_META_TYPE_CODE,
                MDD.CRITERIA,
                ROW_NUMBER() OVER (ORDER BY ".$this->db->IfNull('CK.ORDER_NUM', 'GC.DISPLAY_ORDER').") AS ORDER_NUM
            FROM  
                (
                    SELECT 
                        ".$this->db->IfNull('DF.LABEL_NAME', 'GC.LABEL_NAME')." AS LABEL_NAME, 
                        LOWER(GC.COLUMN_NAME) AS FIELD_NAME,
                        LOWER(GC.FIELD_PATH) AS FIELD_PATH, 
                        GC.DATA_TYPE AS META_TYPE_CODE, 
                        GC.COLUMN_WIDTH, 
                        GC.SIDEBAR_NAME,
                        GC.TEXT_WEIGHT, 
                        GC.TEXT_COLOR, 
                        GC.HEADER_ALIGN, 
                        GC.BODY_ALIGN, 
                        GC.TEXT_TRANSFORM, 
                        GC.BG_COLOR, 
                        GC.FONT_SIZE, 
                        GC.MAX_VALUE,
                        '' AS COLUMN_AGGREGATE, 
                        '' AS IS_BOLD, 
                        GC.ID,
                        GC.IS_MERGE,
                        GC.IS_BASKET_EDIT,
                        GC.FRACTION_RANGE,
                        GC.EXCEL_COLUMN_WIDTH,
                        GC.EXCEL_ROTATE, 
                        GC.IS_IGNORE_EXCEL, 
                        GC.INLINE_PROCESS_ID,
                        GC.LOOKUP_META_DATA_ID,
                        GC.DEFAULT_VALUE, 
                        GC.IS_FREEZE AS IS_DEFAULT_FREEZE, 
                        GC.RENDER_TYPE, 
                        GC.MAIN_META_DATA_ID, 
                        GC.COLUMN_NAME, 
                        1 AS IS_SHOW, 
                        ".$this->db->IfNull('DF.DISPLAY_ORDER', 'GC.DISPLAY_ORDER')." AS DISPLAY_ORDER  
                    FROM META_GROUP_CONFIG GC 
                        LEFT JOIN CUSTOMER_DV_FIELD DF ON DF.META_DATA_ID = GC.MAIN_META_DATA_ID 
                            AND LOWER(DF.FIELD_PATH) = LOWER(GC.FIELD_PATH) 
                            AND DF.IS_ACTIVE = 1 
                    WHERE GC.MAIN_META_DATA_ID = $metaDataIdPh 
                        AND ".$this->db->IfNull('DF.IS_SHOW', 'GC.IS_SHOW')." = 1  
                        AND GC.IS_SELECT = 1 
                        AND GC.PARENT_ID IS NULL 
                        AND GC.DATA_TYPE <> 'group' 
                        AND (
                            DF.ID IS NULL 
                            OR 
                            (DF.IS_SHOW = 1 AND GC.IS_CRITERIA = 1 AND GC.COLUMN_NAME IS NOT NULL) 
                            OR 
                            (DF.IS_SHOW = 1 AND GC.IS_SHOW = 0 AND GC.COLUMN_NAME IS NOT NULL) 
                            OR 
                            (DF.IS_SHOW = 1 AND GC.IS_SHOW = 1 AND GC.COLUMN_NAME IS NOT NULL) 
                            OR 
                            (DF.IS_SHOW = 1 AND DF.IS_CRITERIA = 1) 
                        )
                        AND (GC.IS_SIDEBAR != 1 OR GC.IS_SIDEBAR IS NULL) 
                ) GC 
                LEFT JOIN (
                    SELECT 
                        MAIN_META_DATA_ID, 
                        ORDER_NUM, 
                        PARAM_NAME, 
                        ". (isset($gridOption['ONRESIZECOLUMN']) && $gridOption['ONRESIZECOLUMN'] == 'true' ? 'PARAM_WIDTH' : '\'\'') ." AS PARAM_WIDTH, 
                        IS_SHOW, 
                        IS_FREEZE, 
                        HEADER_NAME 
                    FROM META_GROUP_CONFIG_USER 
                    WHERE USER_ID = $userIdPh 
                        AND MAIN_META_DATA_ID = $metaDataIdPh 
                    GROUP BY 
                        MAIN_META_DATA_ID, 
                        ORDER_NUM, 
                        PARAM_NAME, 
                        IS_SHOW, 
                        IS_FREEZE, 
                        HEADER_NAME, 
                        PARAM_WIDTH
                ) CK ON CK.MAIN_META_DATA_ID = GC.MAIN_META_DATA_ID 
                    AND LOWER(CK.PARAM_NAME) = LOWER(GC.FIELD_PATH) 
                LEFT JOIN (
                    SELECT 
                        COUNT(MAIN_META_DATA_ID) AS COUNTT, 
                        MAIN_META_DATA_ID 
                    FROM META_GROUP_CONFIG_USER 
                    WHERE USER_ID = $userIdPh 
                        AND IS_SHOW = 1 
                        AND MAIN_META_DATA_ID = $metaDataIdPh 
                    GROUP BY MAIN_META_DATA_ID
                ) CH ON GC.MAIN_META_DATA_ID = CH.MAIN_META_DATA_ID                    
                LEFT JOIN (
                    SELECT  
                        ".$this->db->listAgg('MDD.LINK_META_DATA_ID', ',', 'MDD.LINK_META_DATA_ID, MDD.CRITERIA')." AS LINK_META_DATA_ID,
                        LOWER(".$this->db->listAgg('MDD.CRITERIA', ',', 'MDD.LINK_META_DATA_ID, MDD.CRITERIA').") AS CRITERIA,
                        COUNT(MDD.LINK_META_DATA_ID) AS COUNTT,
                        MDD.MAIN_GROUP_LINK_PARAM,
                        ". $this->db->ifNull('MDD.IGNORE_DRILL_META', '\'$@$\'') . " AS IGNORE_DRILL_META,  
                        LOWER(".$this->db->listAgg('MT.META_TYPE_CODE', ',', 'MDD.LINK_META_DATA_ID, MDD.CRITERIA').") AS DRILLDOWN_META_TYPE_CODE
                    FROM META_GROUP_LINK MGL
                        INNER JOIN META_DM_DRILLDOWN_DTL MDD ON MDD.MAIN_GROUP_LINK_ID = MGL.ID
                        INNER JOIN META_DATA MDA ON MDD.LINK_META_DATA_ID = MDA.META_DATA_ID
                        INNER JOIN META_TYPE MT ON MDA.META_TYPE_ID = MT.META_TYPE_ID
                    WHERE MGL.META_DATA_ID = $metaDataIdPh
                    GROUP BY MDD.MAIN_GROUP_LINK_PARAM, MDD.IGNORE_DRILL_META 
                ) MDD ON LOWER(MDD.MAIN_GROUP_LINK_PARAM) = LOWER(GC.FIELD_PATH) 
            WHERE GC.MAIN_META_DATA_ID = $metaDataIdPh 
                AND ".$this->db->IfNull('CK.IS_SHOW', 'GC.IS_SHOW')." = 1 
                AND $condition 
            ORDER BY 
                ".$this->db->IfNull('CK.ORDER_NUM', 'GC.DISPLAY_ORDER')." ASC", 
            array($metaDataId, $userId)
        );

        return $data;
    }

    public function getBasketDataViewGridHeaderModel($metaDataId, $condition = '1 = 1', $basketCondition = 'AND GC.IS_SHOW_BASKET = 1', $column = 'CASE WHEN CH.COUNTT > 0 THEN CK.IS_SHOW ELSE GC.IS_SHOW_BASKET END AS IS_SHOW,') {
        
        $dvConfig = self::getDataViewConfigRowModel($metaDataId); 
        $crmUserId = Session::get(SESSION_PREFIX . 'crmuserid');
        $userId = $crmUserId ? $crmUserId : Ue::sessionUserId();
        
        $metaDataIdPh = $this->db->Param(0);
        $userIdPh = $this->db->Param(1);
        
        if (isset($dvConfig['COUNT_CUSTOMER_FIELD']) && $dvConfig['COUNT_CUSTOMER_FIELD'] > 0) { 
            
            $customerField = $this->db->IfNull('DF.LABEL_NAME', $this->db->IfNull('CK.HEADER_NAME', 'GC.LABEL_NAME'))." AS LABEL_NAME, 
                        CASE WHEN CH.COUNTT > 0 THEN CK.ORDER_NUM ELSE DF.DISPLAY_ORDER END AS ORDER_NUM, 
                        CASE WHEN CK.IS_FREEZE IS NULL THEN CK.ORDER_NUM ELSE DF.DISPLAY_ORDER END AS IS_FREEZE, ";
            $customerJoin = 'INNER JOIN CUSTOMER_DV_FIELD DF ON GC.MAIN_META_DATA_ID = DF.META_DATA_ID AND LOWER(DF.FIELD_PATH) = LOWER(GC.FIELD_PATH) AND DF.IS_ACTIVE = 1';
            
        } else {
            $customerField = $this->db->IfNull('CK.HEADER_NAME', 'GC.LABEL_NAME')." AS LABEL_NAME, 
                        CASE WHEN CH.COUNTT > 0 THEN CK.ORDER_NUM ELSE GC.DISPLAY_ORDER END AS ORDER_NUM, 
                        CASE WHEN CK.IS_FREEZE IS NULL THEN CK.ORDER_NUM ELSE GC.DISPLAY_ORDER END AS IS_FREEZE, "; 
            $customerJoin = '';
        }
        
        $data = $this->db->GetAll("
            SELECT * FROM (
                SELECT DISTINCT 
                    $customerField 
                    LOWER(GC.COLUMN_NAME) AS FIELD_NAME,
                    LOWER(GC.FIELD_PATH) AS FIELD_PATH, 
                    '' AS IS_REFRESH,
                    GC.DATA_TYPE AS META_TYPE_CODE, 
                    GC.COLUMN_WIDTH, 
                    GC.SIDEBAR_NAME,
                    GC.TEXT_WEIGHT, 
                    GC.TEXT_COLOR, 
                    GC.HEADER_ALIGN, 
                    GC.BODY_ALIGN, 
                    GC.TEXT_TRANSFORM, 
                    GC.BG_COLOR, 
                    GC.FONT_SIZE, 
                    GC.MAX_VALUE,  
                    '' AS COLUMN_AGGREGATE, 
                    '' AS IS_BOLD, 
                    GC.ID,
                    GC.IS_MERGE,
                    GC.IS_BASKET_EDIT, 
                    GC.FRACTION_RANGE, 
                    $column 
                    MDD.COUNTT AS DRILLDOWN_COLUMN, 
                    MDD.LINK_META_DATA_ID, 
                    MDD.DRILLDOWN_META_TYPE_CODE,
                    MDD.CRITERIA,
                    CASE WHEN TEM.IS_COLSPAN IS NULL THEN 0 ELSE TEM.IS_COLSPAN END AS DV_COLSPAN,
                    0 AS USER_CONFIG_COUNT, 
                    0 AS IS_DEFAULT_FREEZE, 
                    GC.RENDER_TYPE 
                FROM META_GROUP_CONFIG GC 
                    $customerJoin 
                    LEFT JOIN (
                        SELECT COUNT(MAIN_META_DATA_ID) AS COUNTT, MAIN_META_DATA_ID, PARAM_NAME FROM META_GROUP_CONFIG_USER WHERE USER_ID = $userIdPh AND IS_SHOW = 1 AND MAIN_META_DATA_ID = $metaDataIdPh GROUP BY MAIN_META_DATA_ID, PARAM_NAME
                    ) CH ON GC.MAIN_META_DATA_ID = CH.MAIN_META_DATA_ID
                    LEFT JOIN (
                        SELECT MAIN_META_DATA_ID, ORDER_NUM, PARAM_NAME, IS_SHOW, IS_FREEZE, HEADER_NAME FROM META_GROUP_CONFIG_USER WHERE USER_ID = $userIdPh AND MAIN_META_DATA_ID = $metaDataIdPh GROUP BY MAIN_META_DATA_ID, ORDER_NUM, PARAM_NAME, IS_SHOW, IS_FREEZE, HEADER_NAME 
                    ) CK ON GC.MAIN_META_DATA_ID = CK.MAIN_META_DATA_ID AND LOWER(GC.FIELD_PATH) = LOWER(CK.PARAM_NAME) 
                    LEFT JOIN (
                        SELECT    
                            ".$this->db->listAgg('MDD.LINK_META_DATA_ID', ',', 'MDD.LINK_META_DATA_ID, MDD.CRITERIA')." AS LINK_META_DATA_ID,
                            LOWER(".$this->db->listAgg('MDD.CRITERIA', ',', 'MDD.LINK_META_DATA_ID, MDD.CRITERIA').") AS CRITERIA,
                            COUNT(MDD.LINK_META_DATA_ID) AS COUNTT,
                            MDD.MAIN_GROUP_LINK_PARAM,
                            LOWER(MT.META_TYPE_CODE) AS DRILLDOWN_META_TYPE_CODE
                        FROM META_GROUP_LINK MGL
                            INNER JOIN META_DM_DRILLDOWN_DTL MDD ON MDD.MAIN_GROUP_LINK_ID = MGL.ID
                            INNER JOIN META_DATA MDA ON MDD.LINK_META_DATA_ID = MDA.META_DATA_ID
                            INNER JOIN META_TYPE MT ON MDA.META_TYPE_ID = MT.META_TYPE_ID
                        WHERE MGL.META_DATA_ID = $metaDataIdPh 
                        GROUP BY MDD.MAIN_GROUP_LINK_PARAM, MT.META_TYPE_CODE
                    ) MDD ON LOWER(GC.FIELD_PATH) = LOWER(MDD.MAIN_GROUP_LINK_PARAM)
                    LEFT JOIN (
                        SELECT 
                            COUNT(GC.SIDEBAR_NAME) AS IS_COLSPAN, 
                            GC.SIDEBAR_NAME, 
                            GC.MAIN_META_DATA_ID 
                        FROM META_GROUP_CONFIG GC 
                        $customerJoin 
                        WHERE GC.IS_SHOW = 1 
                            AND GC.IS_SELECT = 1 
                            AND GC.MAIN_META_DATA_ID = $metaDataIdPh 
                            $basketCondition     
                        GROUP BY GC.MAIN_META_DATA_ID, GC.SIDEBAR_NAME 
                    ) TEM ON GC.MAIN_META_DATA_ID = TEM.MAIN_META_DATA_ID AND GC.SIDEBAR_NAME = TEM.SIDEBAR_NAME
                WHERE GC.MAIN_META_DATA_ID = $metaDataIdPh 
                    AND GC.PARENT_ID IS NULL
                    AND GC.IS_SELECT = 1 
                    $basketCondition
                    AND GC.DATA_TYPE <> 'group'  
            ) TEMP WHERE $condition 
            ORDER BY ORDER_NUM ASC", array($metaDataId, $userId));

        return $data;
    }

    public function getSidebarDataviewListModel($metaDataId) {
        
        $data = $this->db->GetAll("
            SELECT 
                GC.TRG_META_DATA_ID,
                " . $this->db->IfNull("GLC.LIST_NAME", "MD.META_DATA_NAME") . " AS META_DATA_NAME, 
                MGR.SRC_PARAM_PATH
            FROM META_GROUP_LINK GL 
                INNER JOIN META_GROUP_CONFIG GC ON GL.META_DATA_ID = GC.MAIN_META_DATA_ID
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = GC.TRG_META_DATA_ID
                INNER JOIN META_GROUP_LINK GLC ON MD.META_DATA_ID = GLC.META_DATA_ID
                INNER JOIN META_META_MAP MM ON MM.SRC_META_DATA_ID = GC.MAIN_META_DATA_ID AND MM.TRG_META_DATA_ID = GC.TRG_META_DATA_ID
                INNER JOIN META_GROUP_RELATION MGR ON GC.TRG_META_DATA_ID = MGR.TRG_META_GROUP_ID AND GL.META_DATA_ID = MGR.MAIN_META_DATA_ID
            WHERE GC.MAIN_META_DATA_ID = ".$this->db->Param(0)."
                AND GC.IS_SIDEBAR = 1
                AND MD.META_TYPE_ID = 200101010000016
            ORDER BY MM.ORDER_NUM ASC", array($metaDataId));

        if (!is_null($data)) {
            
            $response = array();
            
            foreach ($data as $value) {
                $response[] = array(
                    'TRG_META_DATA_ID' => $value['TRG_META_DATA_ID'],
                    'META_DATA_NAME'   => $this->lang->line($value['META_DATA_NAME']),
                    'SRC_PARAM_PATH'   => $value['SRC_PARAM_PATH'],
                );
            }

            $data = $response;
        }

        return $data;
    }

    public function getDataViewGridAllFieldsModel($metaDataId) {
        
        $metaDataIdPh = $this->db->Param(0);
        $userIdPh     = $this->db->Param(1);
        
        $crmUserId = Session::get(SESSION_PREFIX . 'crmuserid');
        $userId = $crmUserId ? $crmUserId : Ue::sessionUserId();
        
        $data = $this->db->GetAll("
            SELECT 
                T.* 
            FROM (
                SELECT 
                    GC.ID, 
                    ".$this->db->IfNull('CK.HEADER_NAME', 'GC.LABEL_NAME')." AS LABEL_NAME, 
                    GC.SIDEBAR_NAME, 
                    CK.HEADER_NAME,     
                    LOWER(GC.FIELD_PATH) AS FIELD_PATH, 
                    GC.DATA_TYPE AS META_TYPE_CODE, 
                    GC.COLUMN_WIDTH, 
                    GC.TEXT_WEIGHT, 
                    GC.TEXT_COLOR, 
                    GC.HEADER_ALIGN, 
                    GC.BODY_ALIGN, 
                    GC.TEXT_TRANSFORM, 
                    '' AS COLUMN_AGGREGATE, 
                    '' AS IS_BOLD, 
                    CASE WHEN CK.IS_SHOW IS NULL THEN 0 ELSE CK.IS_SHOW END AS IS_SHOW,
                    CASE WHEN CK.IS_FREEZE IS NULL THEN 0 ELSE CK.IS_FREEZE END AS IS_FREEZE,
                    CASE WHEN CK.IS_CRITERIA IS NULL THEN 0 ELSE CK.IS_CRITERIA END AS IS_CRITERIA,
                    CASE WHEN CK.ORDER_NUM IS NULL THEN GC.DISPLAY_ORDER ELSE CK.ORDER_NUM END AS ORDER_NUM, 
                    CK.MAIN_META_DATA_ID 
                FROM META_GROUP_CONFIG GC 
                    LEFT JOIN CUSTOMER_DV_FIELD DF ON GC.MAIN_META_DATA_ID = DF.META_DATA_ID 
                        AND LOWER(DF.FIELD_PATH) = LOWER(GC.FIELD_PATH) 
                        AND DF.IS_ACTIVE = 1 
                    LEFT JOIN (
                        SELECT 
                            MAIN_META_DATA_ID, 
                            ORDER_NUM, 
                            PARAM_NAME, 
                            IS_SHOW, 
                            IS_FREEZE, 
                            IS_CRITERIA, 
                            HEADER_NAME 
                        FROM META_GROUP_CONFIG_USER 
                        WHERE USER_ID = $userIdPh  
                            AND MAIN_META_DATA_ID = $metaDataIdPh 
                        GROUP BY 
                            MAIN_META_DATA_ID, 
                            ORDER_NUM, 
                            PARAM_NAME, 
                            IS_SHOW, 
                            IS_FREEZE, 
                            IS_CRITERIA, 
                            HEADER_NAME 
                    ) CK ON GC.MAIN_META_DATA_ID = CK.MAIN_META_DATA_ID AND LOWER(GC.FIELD_PATH) = LOWER(CK.PARAM_NAME) 
                WHERE GC.MAIN_META_DATA_ID = $metaDataIdPh 
                    AND GC.PARENT_ID IS NULL 
                    AND GC.IS_SELECT = 1 
                    AND ".$this->db->IfNull('DF.IS_SHOW', 'GC.IS_SHOW')." = 1 
                    AND GC.DATA_TYPE <> 'group' 
            ) T 
            ORDER BY T.ORDER_NUM ASC", array($metaDataId, $userId));

        return $data;
    }

    public function getDataViewGridBodyDataModel($metaDataId) {
        $data = $this->db->GetAll("
            SELECT 
                LOWER(FIELD_PATH) AS FIELD_NAME, 
                FIELD_PATH AS META_DATA_CODE, 
                LABEL_NAME AS META_DATA_NAME 
            FROM META_GROUP_CONFIG  
            WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                AND IS_SELECT = 1 
                AND PARENT_ID IS NULL 
                AND DATA_TYPE <> 'group'  
            ORDER BY DISPLAY_ORDER ASC", array($metaDataId));

        return $data;
    }
    
    public function getExplorerDrillDownLinkModel($metaDataId, $metaDataCode) {
        
        $result = array();
        $gridData = self::getDataViewGridHeaderModel($metaDataId, '1 = 1', 1);
        
        foreach ($gridData as $row) {
            
            if ($row['DRILLDOWN_COLUMN'] > 0) {
                    
                $linkMetaData = $this->db->GetRow("
                    SELECT 
                        COUNT(MDD.LINK_META_DATA_ID) AS CLINK_META_DATA_ID,
                        ".$this->db->listAgg('MDD.LINK_META_DATA_ID', ',', 'MDD.LINK_META_DATA_ID, MDD.CRITERIA')." AS LINK_META_DATA_ID, 
                        ".$this->db->listAgg('MDD.CRITERIA', ',', 'MDD.LINK_META_DATA_ID, MDD.CRITERIA')." AS CRITERIA,
                        ".$this->db->listAgg('MDD.DIALOG_WIDTH', ',', 'MDD.LINK_META_DATA_ID, MDD.DIALOG_WIDTH')." AS DIALOG_WIDTH,
                        ".$this->db->listAgg('MDD.DIALOG_HEIGHT', ',', 'MDD.LINK_META_DATA_ID, MDD.DIALOG_HEIGHT')." AS DIALOG_HEIGHT
                    FROM META_DM_DRILLDOWN_DTL MDD
                    WHERE MDD.MAIN_GROUP_LINK_ID = (SELECT ID FROM META_GROUP_LINK WHERE META_DATA_ID = $metaDataId)
                        AND LOWER(MDD.MAIN_GROUP_LINK_PARAM) = LOWER('". $row['FIELD_PATH'] ."')"); 

                if ($row['DRILLDOWN_META_TYPE_CODE'] == 'process') {

                    if (!isset($processCommand)) {
                        $processCommand = self::dataViewProcessCommandModel($metaDataId, '', false);
                        $processCommandLink = $processCommand['commandFunction'];
                    }

                } else {
                    
                    $isnewTab = 'false';

                    $link_metatypecode = $link_linkmetadataid = $link_linkcriteria = $link_dialogWidth = $link_dialogHeight = '';
                    $clinkMetadataId = 0;
                    $sourceParam  = '';
                
                    $ddown = self::getDrillDownMetaDataModel($metaDataId, $row['FIELD_PATH']);

                    if ($ddown) {
                        
                        $sizeDrillDownArray = count($ddown);

                        $link_metatypecode = $ddown[0]['META_TYPE_CODE'];
                        $link_linkmetadataid = $ddown[0]['LINK_META_DATA_ID'];
                        $link_linkcriteria = Str::nlToSpace($ddown[0]['CRITERIA']);
                        $link_dialogWidth = Str::nlToSpace($ddown[0]['DIALOG_WIDTH']);
                        $link_dialogHeight = Str::nlToSpace($ddown[0]['DIALOG_HEIGHT']);
                        $clinkMetadataId = 1;

                        if ($ddown[0]['DEFAULT_VALUE']) { 
                            $sourceParam .= ($ddown[0]['TRG_PARAM']) ? $ddown[0]['TRG_PARAM'].'='. $ddown[0]['DEFAULT_VALUE'] : '';
                        } else {
                            $sourceParam .= ($ddown[0]['TRG_PARAM']) ? $ddown[0]['TRG_PARAM'].'=$\'.$recordRow[$\''.$ddown[0]['SRC_PARAM'].'$\'].$\'' : '';
                        }

                        if ($sizeDrillDownArray > 1) {
                            $linkDrillDown = self::drilldownParams($ddown);              

                            $link_linkmetadataid = $linkDrillDown['LINK_METADATAID'];
                            $link_linkcriteria = Str::nlToSpace($linkDrillDown['LINK_CRITERIA']);
                            $link_metatypecode = $linkDrillDown['LINK_METATYPECODE'];
                            $clinkMetadataId = $linkDrillDown['LINK_COUNT'];
                            $sourceParam = $linkDrillDown['LINK_PARAM'];

                            $link_dialogWidth = $linkDrillDown['DIALOG_WIDTH'];
                            $link_dialogHeight = $linkDrillDown['DIALOG_HEIGHT'];
                        }             

                        if (isset($ddown[0]['SHOW_TYPE'])) {
                            $showType = explode(',', strtolower($ddown[0]['SHOW_TYPE']));
                            if (isset($showType[0])) {
                                if ($showType[0] == 'tab') {
                                    $isnewTab = 'true';
                                } elseif ($showType[0]) {
                                    $isnewTab = "\'".$showType[0]."\'";
                                }
                            }
                        }
                    }
                }

                if ($row['DRILLDOWN_META_TYPE_CODE'] == 'process') {
                    
                    $linkStyle = $processCommandLink['buttonStyle'];
                    $drillLink = "drillDownTransferProcessAction('".$processCommandLink['functionName']."', '". $linkMetaData['CLINK_META_DATA_ID'] ."',  '". $linkMetaData['CRITERIA'] ."', '', '". $processCommandLink['metaDataId'] ."', '". $linkMetaData['LINK_META_DATA_ID'] ."', '". $processCommandLink['type'] ."', '', ". $processCommandLink['element'] .", {callerType: '".$metaDataCode."', isDrillDown: true})";

                } else {
                    $linkStyle = 'default';
                    $drillLink = "gridDrillDownLink(this, '$metaDataCode', '". $link_metatypecode ."', '". $clinkMetadataId ."', '". $link_linkcriteria ."', '". $metaDataId ."', '". $row['FIELD_PATH'] ."', '". $link_linkmetadataid ."', '". $sourceParam ."', $isnewTab, undefined, '". $link_dialogWidth ."', '". $link_dialogHeight ."')";
                }

                $result[$row['FIELD_PATH']] = array(
                    'label' => Lang::line($row['LABEL_NAME']), 
                    'link' => $drillLink, 
                    'linkStyle' => $linkStyle
                );
            }
        }
        
        return $result;
    }

    public function renderClickRowFunction($metaDataId, $dataViewLayoutTypes) {

        if ($dataViewLayoutTypes['explorer']['CLICK_ROW_FUNCTION'] == '') {
            $clickFunction = 'explorerSideBar_' . $metaDataId . '(elem);';
        } else {
            $clickFunction = $dataViewLayoutTypes['explorer']['CLICK_ROW_FUNCTION'];
        }
        
        $name1 = issetParam($dataViewLayoutTypes['explorer']['fields']['name1']);
        
        if (!$name1) {
            $name1 = issetParam($dataViewLayoutTypes['explorer']['fields']['title']);
        }
        
        $ddown = self::getDrillDownMetaDataModel($metaDataId, $name1);
        
        if ($ddown) {

            $sourceParam = '';
            $sizeDrillDownArray = count($ddown);

            $link_metatypecode = $ddown[0]['META_TYPE_CODE'];
            $link_linkmetadataid = $ddown[0]['LINK_META_DATA_ID'];
            $link_linkcriteria = $ddown[0]['CRITERIA'];
            $link_dialogWidth = $ddown[0]['DIALOG_WIDTH'];
            $link_dialogHeight = $ddown[0]['DIALOG_HEIGHT'];
            
            $clinkMetadataId = 1;
            if ($ddown[0]['DEFAULT_VALUE']) {
                $sourceParam = ($ddown[0]['TRG_PARAM']) ? $ddown[0]['TRG_PARAM']."=". $ddown[0]['DEFAULT_VALUE'] : '';
            } else {
                $sourceParam = ($ddown[0]['TRG_PARAM']) ? $ddown[0]['TRG_PARAM']."='+ r.". $ddown[0]['SRC_PARAM'] ." +'" : '';
            }

            if ($sizeDrillDownArray > 1) {
                $linkDrillDown = self::drilldownParams($ddown);              

                $link_linkmetadataid = $linkDrillDown['LINK_METADATAID'];
                $link_linkcriteria = Str::nlToSpace($linkDrillDown['LINK_CRITERIA']);
                $link_metatypecode = $linkDrillDown['LINK_METATYPECODE'];
                $clinkMetadataId = $linkDrillDown['LINK_COUNT'];
                $sourceParam = $linkDrillDown['LINK_PARAM'];
            }               

            $clickFunction = "var rowData = _this.attr('data-row-data'); var r = JSON.parse(rowData);";
            if ($link_metatypecode == 'process') {
                $processCommand = self::dataViewProcessCommandModel($metaDataId, '', false);
                if (isset($processCommand['commandFunction']) && $processCommand['commandFunction']) {
                    $processCommandLink = $processCommand['commandFunction'];
                    $clickFunction .= "drillDownTransferProcessAction('" . $processCommandLink['functionName'] . "', '" . $clinkMetadataId . "', '" . $link_linkcriteria . "', '', '" . $processCommandLink['metaDataId'] . "', '" . $link_linkmetadataid . "', '" . $processCommandLink['type'] . "', '', _this, {isDrillDown: true});";
                }
                
            } else {
                $isnewTab = 'true';
                if (isset($ddown[0]['SHOW_TYPE'])) {
                    $showType = strtolower($ddown[0]['SHOW_TYPE']);
                    if ($showType == 'tab') {
                        $isnewTab = 'true';
                    } elseif ($showType) {
                        $isnewTab = "'".$showType."'";
                    }
                }
                $clickFunction .= " var _elementD = (typeof elem !== 'undefined') ? elem : this; gridDrillDownLink(_elementD, '', '" . $link_metatypecode . "', '" . $clinkMetadataId . "', '" . str_replace("'", "\'", $link_linkcriteria) . "', '" . $metaDataId . "', '" . $name1 . "', '" . $link_linkmetadataid . "', '" . $sourceParam . "', $isnewTab, undefined, '". $link_dialogWidth ."', '". $link_dialogHeight ."');";
            }
        }

        return $clickFunction;
    }

    public function renderDataViewGridCache($metaDataId, $metaDataCode, $refStructureId) {
        $cache = phpFastCache();
        $data = $cache->get('dvSubgrid_'.$metaDataId);

        if ($data == null) {
            $data = self::renderDataViewGridModel($metaDataId, $metaDataCode, $refStructureId);
            $cache->set('dvSubgrid_'.$metaDataId, $data, Mdwebservice::$expressionCacheTime);
        }

        return $data;
    }

    public function selfRenderDataViewGroupMergeModel($gridData) {

        $sidebar_array = $colspanRender = $colspan_array = $groupRowSpanArray = $rowspan_array = array();

        foreach ($gridData as $key => $row) {
            if ($row['SIDEBAR_NAME'] != '') {
                $sidebar_array[$row['SIDEBAR_NAME']][$key] = $row;
            }
        }
        
        if ($sidebar_array) {
            
            foreach ($sidebar_array as $sidebarRows) {
                
                $sidebarCount = count($sidebarRows);
                
                foreach ($sidebarRows as $rowKey => $row) {
                    
                    $gridData[$rowKey]['DV_COLSPAN'] = $sidebarCount;
                    $colspanRender[$row['ID']] = true;
                    
                    $row['DV_COLSPAN'] = $sidebarCount;
                    array_push($colspan_array, $row);
                }
            }
        }

        if ($colspanRender) {
            $groupRowSpanArray = Arr::groupByArray($colspan_array, 'SIDEBAR_NAME');
        }        

        return array('gridData' => $gridData, 'colspanRender' => $colspanRender, 'groupRowSpanArray' => $groupRowSpanArray);
    }

    public function dataviewCustomerConfigModel($metaDataId) {

        $metaDataIdPh = $this->db->Param(0);
        $userIdPh = $this->db->Param(1);
        
        $crmUserId = Session::get(SESSION_PREFIX . 'crmuserid');
        $userId = $crmUserId ? $crmUserId : Ue::sessionUserId();
        
        $row = $this->db->GetRow("
            SELECT 
                CASE WHEN fre.MAIN_META_DATA_ID IS NULL THEN 0 ELSE 1 END AS IS_FREEZE,
                CASE WHEN ishow.CC IS NULL THEN 0 ELSE ishow.CC END AS IS_SHOW,
                CASE WHEN fre.ORDER_NUM IS NULL THEN 0 ELSE fre.ORDER_NUM END AS ORDER_NUM
            FROM META_GROUP_LINK GL
                LEFT JOIN (
                    SELECT 
                        ORDER_NUM, MAIN_META_DATA_ID
                    FROM META_GROUP_CONFIG_USER 
                    WHERE MAIN_META_DATA_ID = $metaDataIdPh AND USER_ID = $userIdPh AND IS_FREEZE = 1
                ) fre ON GL.META_DATA_ID = fre.MAIN_META_DATA_ID
                LEFT JOIN (
                    SELECT 
                        COUNT(DISTINCT MAIN_META_DATA_ID) AS CC, MAIN_META_DATA_ID
                    FROM META_GROUP_CONFIG_USER 
                    WHERE MAIN_META_DATA_ID = $metaDataIdPh AND USER_ID = $userIdPh AND IS_SHOW = 1
                    GROUP BY MAIN_META_DATA_ID
                ) ishow ON GL.META_DATA_ID = ishow.MAIN_META_DATA_ID 
            WHERE GL.META_DATA_ID = $metaDataIdPh", array($metaDataId, $userId));
        
        return $row;
    }

    public function drilldownParams($drilldownRows) {
        
        $array_linkmetadataid_cc = $array_linkcriteria = $array_metatypecode = $array_dialogWidth = 
        $array_dialogHeight = $array_linkmetadataid = $array_param = [];
        $clinkMetadataId = -1;
        
        foreach ($drilldownRows as $key => $dvalue) {
            if (!in_array($dvalue['LINK_META_DATA_ID'] . '_' . $dvalue['CRITERIA'], $array_linkmetadataid_cc)) {
                array_push($array_linkmetadataid_cc, $dvalue['LINK_META_DATA_ID'] . '_' . $dvalue['CRITERIA']);
                array_push($array_linkmetadataid, $dvalue['LINK_META_DATA_ID']);
                array_push($array_linkcriteria, $dvalue['CRITERIA']);
                array_push($array_dialogWidth, $dvalue['DIALOG_WIDTH']);
                array_push($array_dialogHeight, $dvalue['DIALOG_HEIGHT']);
                array_push($array_metatypecode, $dvalue['META_TYPE_CODE']);
                
                $clinkMetadataId++;
                if ($dvalue['DEFAULT_VALUE']) {
                    $array_param[$clinkMetadataId] = ($dvalue['TRG_PARAM']) ? $dvalue['TRG_PARAM']."=". $dvalue['DEFAULT_VALUE'] : '';
                } else {
                    $array_param[$clinkMetadataId] = ($dvalue['TRG_PARAM']) ? $dvalue['TRG_PARAM']."='+r.". $dvalue['SRC_PARAM']."+'" : '';
                }                 
            } else {
                if ($dvalue['DEFAULT_VALUE']) {
                    $array_param[$clinkMetadataId] .= ($dvalue['TRG_PARAM']) ? "&".$dvalue['TRG_PARAM']."=". $dvalue['DEFAULT_VALUE'] : '';
                } else {
                    $array_param[$clinkMetadataId] .= ($dvalue['TRG_PARAM']) ? "&".$dvalue['TRG_PARAM']."='+r.". $dvalue['SRC_PARAM']."+'" : '';
                }                
            }                       
        }
        
        $clinkMetadataId++;
       
        $link_linkmetadataid = Arr::implode_r(',', $array_linkmetadataid, true);
        $link_dialogHeight = Arr::implode_r(',', $array_dialogHeight, true);
        $link_dialogWidth = Arr::implode_r(',', $array_dialogWidth, true);
        $link_linkcriteria = Arr::implode_r(',', $array_linkcriteria, true);
        $link_metatypecode = Arr::implode_r(',', $array_metatypecode, true);
        $link_param = '';
        foreach ($array_param as $a) {
            $link_param .= strval($a) . ',';
        }
        if ($link_param && strpos($link_param, ',') !== false) {
            $link_param = rtrim($link_param, ',');
        }

        return array('LINK_METADATAID' => $link_linkmetadataid, 'DIALOG_HEIGHT' => $link_dialogHeight, 'DIALOG_WIDTH' => $link_dialogWidth, 'LINK_CRITERIA' => $link_linkcriteria, 'LINK_METATYPECODE' => $link_metatypecode, 'LINK_COUNT' => $clinkMetadataId, 'LINK_PARAM' => $link_param);
    }
    
    public function renderDataViewGridModel($metaDataId, $metaDataCode, $refStructureId = '', $isPopupWindow = false, $isBasketWindow = false) {
//        return self::renderDataViewGridModelNew($metaDataId, $metaDataCode, $refStructureId, $isPopupWindow, $isBasketWindow);
        if (Config::getFromCache('is_dev')) {
            return self::renderDataViewGridModelNew($metaDataId, $metaDataCode, $refStructureId, $isPopupWindow, $isBasketWindow);
        }
        $gridData = self::getDataViewGridHeaderModel($metaDataId, '1 = 1', 1, $isPopupWindow, $isBasketWindow);
        $getGridData = self::selfRenderDataViewGroupMergeModel($gridData);
        
        $array_needle = $arraytemp = $array_temp = $array = $getInlineEditMapDataGroup = $getProcessParamsGroup = array();
        
        $gridData = $getGridData['gridData'];
        $colspanRender = $getGridData['colspanRender'];
        $groupRowSpanArray = $getGridData['groupRowSpanArray'];
        
        if ($gridData) {

            $gridOption = self::getDVGridOptionsModel($metaDataId);
            
            if (issetParam($gridOption['ISPIVOTQUERY']) == 'true' && issetParam($gridOption['HEADERMENUTYPE']) == 'work5day') {
                
                $currentDate = Date::currentDate('Y-m-d');
                $gridDataTemp = $gridDataTemp1 = array();
                
                foreach ($gridData as $row) {
                    switch ($row['LABEL_NAME']) {
                        case '1':
                            $row['LABEL_NAME'] = Date::addWorkingDays('m/d', '-', $currentDate, 5);
                            $row['LABEL_NAME'] = $row['LABEL_NAME'] . ' ('. Date::format('D', $row['LABEL_NAME']) .')';
                            array_push($gridDataTemp, $row);
                            break;
                        case '2':
                            $row['LABEL_NAME'] = Date::addWorkingDays('m/d', '-', $currentDate, 4);
                            $row['LABEL_NAME'] = $row['LABEL_NAME'] . ' ('. Date::format('D', $row['LABEL_NAME']) .')';
                            array_push($gridDataTemp, $row);
                            break;
                        case '3':
                            $row['LABEL_NAME'] = Date::addWorkingDays('m/d', '-', $currentDate, 3);
                            $row['LABEL_NAME'] = $row['LABEL_NAME'] . ' ('. Date::format('D', $row['LABEL_NAME']) .')';
                            array_push($gridDataTemp, $row);
                            break;
                        case '4':
                            $row['LABEL_NAME'] = Date::addWorkingDays('m/d', '-', $currentDate, 2);
                            $row['LABEL_NAME'] = $row['LABEL_NAME'] . ' ('. Date::format('D', $row['LABEL_NAME']) .')';
                            array_push($gridDataTemp, $row);
                            break;
                        case '5':
                            $row['LABEL_NAME'] = Date::addWorkingDays('m/d', '-', $currentDate, 1);
                            $row['LABEL_NAME'] = $row['LABEL_NAME'] . ' ('. Date::format('D', $row['LABEL_NAME']) .')';
                            array_push($gridDataTemp, $row);
                            break;
                        default :
                            array_push($gridDataTemp1, $row);
                            break;
                    }
                }
                
                $gridData = array_merge($gridDataTemp1, Arr::sortBy('LABEL_NAME', $gridDataTemp));
            }
            
            $isDrill = ($gridOption['DRILLDBLCLICKROW'] == 'true' ? false : true);
                    
            if ($gridOption['INLINEEDIT'] == 'true') {
                $getInlineEditMapData = self::getInlineEditMapConfig($metaDataId);
                
                if ($getInlineEditMapData) {
                    $getInlineEditMapDataGroup = Arr::groupByArrayOnlyRow($getInlineEditMapData, 'SRC_PARAM_PATH', 'TRG_PARAM_PATH');
                    $getProcessParamsGroup = Arr::groupByArrayLower((new Mdwebservice())->groupParamsData($getInlineEditMapData[0]['PROCESS_META_DATA_ID']), 'META_DATA_CODE');
                    $this->load->model('mdobject', 'middleware/models/');
                }
            }

            $header = '['; 
            $freeze = '[';
            $colspanRow = ''; 
            $freezeRow = ''; 
            $rowspan = '';
            
            if ($colspanRender) {
                $rowspan = 'rowspan:2,';
            }
            
            if (isset($gridOption['SHOWCHECKBOX']) && $gridOption['SHOWCHECKBOX'] == 'true') {
                $freeze .= "{field: 'ck', rowspan:1, checkbox: true },";
            }

            $filterDateInit = $filterDateTimeInit = $filterTimeInit = $filterBigDecimalInit = $filterNumberInit = $filterCenterInit = $isMergeColumn = array();

            if ($gridData[0]['USER_CONFIG_COUNT']) {
                $dataviewCustomerCfg = self::dataviewCustomerConfigModel($metaDataId);
            } else {
                $isDefaultFreeze = helperSumFieldBp($gridData, 'IS_DEFAULT_FREEZE');
                if ($isDefaultFreeze) {
                    $i = 1;
                    foreach ($gridData as $freezeDefaultRow) {
                        if ($freezeDefaultRow['IS_DEFAULT_FREEZE'] == '1') {
                            $dataviewCustomerCfg = array('IS_SHOW' => 1, 'IS_FREEZE' => 1, 'ORDER_NUM' => $i);
                            break;
                        }
                        $i++;
                    }
                } else {
                    $dataviewCustomerCfg = array('IS_SHOW' => 0, 'IS_FREEZE' => 0, 'ORDER_NUM' => 0);
                }
            }

            $num = 1;
            $freezeNumOrder = 0;
            $isGroupFieldUser = false;
            
            if ($isPopupWindow || $isBasketWindow) {
                $freeze .= "{field:'action', $rowspan title:'', sortable:false, width:40, align:'center'},";
            }
            
            if (issetParam($gridOption['GROUPFIELDUSER']) == 'true') {
                $groupField = strtolower($gridOption['GROUPFIELD']);
                $isGroupFieldUser = true;
            }
            
            foreach ($gridData as $row) {
                
                $rowspan = '';
                $colTicket = false;

                if ($colspanRender && isset($groupRowSpanArray[$row['SIDEBAR_NAME']]) && !in_array($row['SIDEBAR_NAME'], $array_needle)) {
                    $colTicket = true;
                    array_push($array_needle, $row['SIDEBAR_NAME']);
                }
                
                if ($row['DRILLDOWN_COLUMN'] > 0) {
                    $ddown = self::getDrillDownMetaDataModel($metaDataId, $row['FIELD_PATH']);     
                } else {
                    $ddown = null;
                }
                
                $isnewTab = 'false';

                $link_metatypecode = $link_linkmetadataid = $link_linkcriteria = $link_dialogWidth = 
                $link_dialogHeight = $link_passPath = $sourceParam = '';
                $clinkMetadataId = 0;
                
                if ($ddown) {
                    
                    $sizeDrillDownArray = count($ddown);
                    
                    if ($sizeDrillDownArray > 1) {
                        
                        $linkDrillDown = self::drilldownParams($ddown);              

                        $link_metatypecode = $linkDrillDown['LINK_METATYPECODE'];
                        $link_linkmetadataid = $linkDrillDown['LINK_METADATAID'];
                        $link_linkcriteria = Str::nlToSpace($linkDrillDown['LINK_CRITERIA']);
                        $link_dialogWidth = $linkDrillDown['DIALOG_WIDTH'];
                        $link_dialogHeight = $linkDrillDown['DIALOG_HEIGHT'];
                        $clinkMetadataId = $linkDrillDown['LINK_COUNT'];
                        $sourceParam = $linkDrillDown['LINK_PARAM'];
                        
                    } else {
                        
                        $link_metatypecode = $ddown[0]['META_TYPE_CODE'];
                        $link_linkmetadataid = $ddown[0]['LINK_META_DATA_ID'];
                        $link_linkcriteria = Str::nlToSpace($ddown[0]['CRITERIA']);
                        $link_dialogWidth = Str::nlToSpace($ddown[0]['DIALOG_WIDTH']);
                        $link_dialogHeight = Str::nlToSpace($ddown[0]['DIALOG_HEIGHT']);
                        $link_passPath = $ddown[0]['PASSWORD_PATH'];
                        $clinkMetadataId = 1;

                        if ($ddown[0]['DEFAULT_VALUE']) {
                            $sourceParam .= ($ddown[0]['TRG_PARAM']) ? $ddown[0]['TRG_PARAM']."=". $ddown[0]['DEFAULT_VALUE'] : '';
                        } else {
                            $sourceParam .= ($ddown[0]['TRG_PARAM']) ? $ddown[0]['TRG_PARAM']."='+ r.". $ddown[0]['SRC_PARAM'] ." +'" : '';
                        }
                    } 
                    
                    if (isset($ddown[0]['SHOW_TYPE'])) {
                        $showType = explode(',', strtolower($ddown[0]['SHOW_TYPE']));
                        if (isset($showType[0])) {
                            if ($showType[0] == 'tab') {
                                $isnewTab = 'true';
                            } elseif ($showType[0]) {
                                $isnewTab = "\'".$showType[0]."\'";
                            }
                        }
                    }
                }
                
                $width = ((empty($row['COLUMN_WIDTH'])) ? "width: '150'," : ((isset($row['PARAM_WIDTH']) && !empty($row['PARAM_WIDTH'])) ? "width: '" . $row['PARAM_WIDTH'] . "'," : "width: '" . $row['COLUMN_WIDTH'] . "',"));
                $cellStyle = '';
                $cellFormatter = '';
                $cellEditor = '';
                $fixedColumn = 'fixed: true,';
                $headerAlign = "halign: 'center',";
                $bodyAlign = "align: 'left',";                    

                if ($row['DRILLDOWN_COLUMN'] > 0) {
                    
                    $linkMetaData = $this->db->GetRow("
                        SELECT 
                            COUNT(MDD.LINK_META_DATA_ID) AS CLINK_META_DATA_ID,
                            ".$this->db->listAgg('MDD.LINK_META_DATA_ID', ',', 'MDD.LINK_META_DATA_ID, MDD.CRITERIA')." AS LINK_META_DATA_ID, 
                            ".$this->db->listAgg('MDD.CRITERIA', ',', 'MDD.LINK_META_DATA_ID, MDD.CRITERIA')." AS CRITERIA,
                            ".$this->db->listAgg('MDD.DIALOG_WIDTH', ',', 'MDD.LINK_META_DATA_ID, MDD.DIALOG_WIDTH')." AS DIALOG_WIDTH,
                            ".$this->db->listAgg('MDD.DIALOG_HEIGHT', ',', 'MDD.LINK_META_DATA_ID, MDD.DIALOG_HEIGHT')." AS DIALOG_HEIGHT
                        FROM META_DM_DRILLDOWN_DTL MDD
                        WHERE MDD.MAIN_GROUP_LINK_ID = (SELECT ID FROM META_GROUP_LINK WHERE META_DATA_ID = ".$this->db->Param(0).")
                            AND LOWER(MDD.MAIN_GROUP_LINK_PARAM) = LOWER(".$this->db->Param(1).")", 
                        array($metaDataId, $row['FIELD_PATH'])
                    ); 
                    
                    $linkMetaData['CRITERIA'] = str_replace("'", "\\\\\'", Str::nlToSpace($linkMetaData['CRITERIA']));
                    
                    if ($row['DRILLDOWN_META_TYPE_CODE'] == 'process' && !isset($processCommand)) {
                        
                        $processCommand = self::dataViewProcessCommandModel($metaDataId, '', false, false, '', true);
                        $processCommandLink = $processCommand['commandFunction'];
                    }
                }

                if (isset($row['INLINE_PROCESS_ID']) && !empty($row['INLINE_PROCESS_ID'])) {
                    
                    $cellFormatter = "formatter: function(v, r, i) {
                        var comboDisable = typeof r.combostatusdisable !== 'undefined' && r.combostatusdisable == 1 ? 'disabled' : '';
                        if (v) {
                            return '<select '+comboDisable+' class=\"form-control form-control-sm dataview-select2-$metaDataId dataview-select2 select2\" data-process-id=".$row['INLINE_PROCESS_ID']." data-lookup-id=".$row['LOOKUP_META_DATA_ID']." data-edit-value='+v+'><option value='+v+'>'+r.combostatusname+'</option></select>';
                        } else {
                            return '<select '+comboDisable+' class=\"form-control form-control-sm dataview-select2-$metaDataId dataview-select2 select2\" data-process-id=".$row['INLINE_PROCESS_ID']." data-lookup-id=".$row['LOOKUP_META_DATA_ID']." data-edit-value=\'\'><option value>- Сонгох -</option></select>';
                        }                            
                    },";
                    
                } elseif ($row['META_TYPE_CODE'] == 'date') {
                    
                    if ($row['DRILLDOWN_COLUMN'] > 0) {
                        if ($row['DRILLDOWN_META_TYPE_CODE'] == 'process') {
                            $cellFormatter = "formatter: function(v, r, i) { if (typeof v !== 'undefined' && v != null) return '<a href=\"javascript:;\" onclick=\"drillDownTransferProcessAction(\'".$processCommandLink['functionName']."\', \'". $linkMetaData['CLINK_META_DATA_ID'] ."\',  \'". $linkMetaData['CRITERIA'] ."\',  \'\', \'". $processCommandLink['metaDataId'] ."\', \'". $linkMetaData['LINK_META_DATA_ID'] ."\', \'". $processCommandLink['type'] ."\', \'\', ". $processCommandLink['element'] .", {callerType: \'".$metaDataCode."\', isDrillDown: true, drillDownPath: \'".$row['FIELD_PATH']."\'})\">'+dateFormatter('Y-m-d', v)+'</a>';},";
                        } else {
                            $cellFormatter = "formatter: function(v, r, i) {
                                if (v) {
                                    return '<a href=\"javascript:;\" onclick=\"gridDrillDownLink(this, \'$metaDataCode\', \'". $link_metatypecode ."\', \'". $clinkMetadataId ."\', \'". $link_linkcriteria ."\', \'". $metaDataId ."\', \'". $row['FIELD_PATH'] ."\', \'". $link_linkmetadataid ."\', \'". $sourceParam ."\', $isnewTab, undefined,  \'". $link_dialogWidth ."\', \'". $link_dialogHeight ."\' )\">'+dateFormatter('Y-m-d', v) + '</a>';
                                } else {
                                    return '';
                                }
                            },";
                        }
                    } else { 
                        $cellFormatter = "formatter: function(v, r, i) {return dateFormatter('Y-m-d', v);},";
                    }
                    
                    $filterDateInit[] = "input[name=" . $row['FIELD_PATH'] . "]";
                    $bodyAlign = "align: 'center',";

                } elseif ($row['META_TYPE_CODE'] == 'datetime') {
                    
                    if ($row['DRILLDOWN_COLUMN'] > 0) {
                        if ($row['DRILLDOWN_META_TYPE_CODE'] == 'process') {
                            $cellFormatter = "formatter: function(v, r, i) {return '<a href=\"javascript:;\" onclick=\"drillDownTransferProcessAction(\'".$processCommandLink['functionName']."\', \'". $linkMetaData['CLINK_META_DATA_ID'] ."\',  \'". $linkMetaData['CRITERIA'] ."\',  \'\', \'". $processCommandLink['metaDataId'] ."\', \'". $linkMetaData['LINK_META_DATA_ID'] ."\', \'". $processCommandLink['type'] ."\', \'\', ". $processCommandLink['element'] .", {callerType: \'".$metaDataCode."\', isDrillDown: true, drillDownPath: \'".$row['FIELD_PATH']."\'})\">'+ dateFormatter('Y-m-d H:i:s', v) + '</a>';},";
                        } else {
                            $cellFormatter = "formatter: function(v, r, i) {
                                if (v)
                                    return '<a href=\"javascript:;\" onclick=\"gridDrillDownLink(this, \'$metaDataCode\', \'". $link_metatypecode ."\', \'". $clinkMetadataId ."\', \'". $link_linkcriteria ."\', \'". $metaDataId ."\', \'". $row['FIELD_PATH'] ."\', \'". $link_linkmetadataid ."\',  \'". $sourceParam ."\', $isnewTab, undefined,  \'". $link_dialogWidth ."\',  \'". $link_dialogHeight ."\')\">'+ dateFormatter('Y-m-d H:i:s', v) + '</a>';
                                else
                                    return '';
                            },";
                        }
                    } else { 
                        $cellFormatter = "formatter: function(v, r, i) {return dateFormatter('Y-m-d H:i:s', v);},";
                    }
                    
                    $filterDateTimeInit[] = "input[name=" . $row['FIELD_PATH'] . "]";
                    $bodyAlign = "align: 'center',";

                } elseif ($row['META_TYPE_CODE'] == 'bigdecimal') {
                    
                    if ($row['DRILLDOWN_COLUMN'] > 0) {
                        
                        if ($row['DRILLDOWN_META_TYPE_CODE'] == 'process') {
                            
                            if (isset($processCommandLink['functionName'])) {
                                $cellFormatter = "formatter: function(v, r, i) {
                                    if (v) {
                                        return '<a href=\"javascript:;\" onclick=\"drillDownTransferProcessAction(\'".$processCommandLink['functionName']."\', \'". $linkMetaData['CLINK_META_DATA_ID'] ."\',  \'". $linkMetaData['CRITERIA'] ."\', \'\', \'". $processCommandLink['metaDataId'] ."\', \'". $linkMetaData['LINK_META_DATA_ID'] ."\', \'". $processCommandLink['type'] ."\', \'\', ". $processCommandLink['element'] .", {callerType: \'".$metaDataCode."\', isDrillDown: true, drillDownPath: \'".$row['FIELD_PATH']."\'})\">'+number_format(v, 2, '.', ',')+'</a>';
                                    } else {
                                        return '';
                                    }
                                },";
                            }
                            
                        } else {
                            $cellFormatter = "formatter: function(v, r, i) {
                                if (v) {
                                    return '<a href=\"javascript:;\" onclick=\"gridDrillDownLink(this, \'$metaDataCode\', \'". $link_metatypecode ."\', \'". $clinkMetadataId ."\', \'". $link_linkcriteria ."\', \'". $metaDataId ."\', \'". $row['FIELD_PATH'] ."\', \'". $link_linkmetadataid ."\', \'". $sourceParam ."\', $isnewTab, undefined,  \'". $link_dialogWidth ."\',  \'". $link_dialogHeight ."\')\">'+number_format(v, ". (!is_null($row['FRACTION_RANGE']) ? $row['FRACTION_RANGE'] : '2' )  .", '.', ',')+'</a>';
                                } else {
                                    return '';
                                } 
                            },";
                        }
                        
                    } elseif (!is_null($row['FRACTION_RANGE'])) {
                        
                        $cellFormatter = "formatter: function(v, r, i) {
                            return gridScaleAmountField(v, '".$row['FRACTION_RANGE']."'); 
                        },";
                        
                    } else {
                        $cellFormatter = 'formatter: gridAmountField,';
                    }
                    
                    if ($gridOption['INLINEEDIT'] == 'true') {
                        $cellEditor = 'editor: {type: "textbox", options: {}},';
                    }                    
                    $filterBigDecimalInit[] = "input[name=" . $row['FIELD_PATH'] . "]";
                    $bodyAlign = "align: 'right',";

                } elseif ($row['META_TYPE_CODE'] == 'bigdecimal_null') {
                    
                    if ($row['DRILLDOWN_COLUMN'] > 0) {
                        if ($row['DRILLDOWN_META_TYPE_CODE'] == 'process') {
                            $cellFormatter = "formatter: function(v, r, i) {
                                if (v) {
                                    return '<a href=\"javascript:;\" onclick=\"drillDownTransferProcessAction(\'".$processCommandLink['functionName']."\', \'". $linkMetaData['CLINK_META_DATA_ID'] ."\',  \'". $linkMetaData['CRITERIA'] ."\', \'\', \'". $processCommandLink['metaDataId'] ."\', \'". $linkMetaData['LINK_META_DATA_ID'] ."\', \'". $processCommandLink['type'] ."\', \'\', ". $processCommandLink['element'] .", {callerType: \'".$metaDataCode."\', isDrillDown: true, drillDownPath: \'".$row['FIELD_PATH']."\'})\">'+number_format(v, 2, '.', ',')+'</a>';
                                } else {
                                    return '';
                                }
                            },";
                        } else {
                            $cellFormatter = "formatter: function(v, r, i) {
                                if (v) {
                                    return '<a href=\"javascript:;\" onclick=\"gridDrillDownLink(this, \'$metaDataCode\', \'". $link_metatypecode ."\', \'". $clinkMetadataId ."\', \'". $link_linkcriteria ."\', \'". $metaDataId ."\', \'". $row['FIELD_PATH'] ."\', \'". $link_linkmetadataid ."\', \'". $sourceParam ."\', $isnewTab, undefined,  \'". $link_dialogWidth ."\',  \'". $link_dialogHeight ."\')\">'+number_format(v, 2, '.', ',')+'</a>';
                                } else {
                                    return '';
                                } 
                            },";
                        }
                    } elseif (!is_null($row['FRACTION_RANGE'])) {
                        
                        $cellFormatter = "formatter: function(v, r, i) {
                            return gridScaleAmountNullField(v, '".$row['FRACTION_RANGE']."'); 
                        },";
                        
                    } else {
                        $cellFormatter = 'formatter: gridAmountNullField,';
                    }
                    
                    $filterBigDecimalInit[] = "input[name=" . $row['FIELD_PATH'] . "]";
                    $bodyAlign = "align: 'right',";

                } elseif ($row['META_TYPE_CODE'] == 'time') {

                    $cellFormatter = "formatter: function(v, r, i) {return dateFormatter('H:i', v);},";
                    $filterTimeInit[] = "input[name=" . $row['FIELD_PATH'] . "]";
                    $bodyAlign = "align: 'center',";

                } elseif ($row['META_TYPE_CODE'] == 'file_tab_view') {

                    $cellFormatter = 'formatter: gridFileTabViewField,';
                    $bodyAlign = "align: 'center',";

                } elseif ($row['FIELD_PATH'] == 'filename') {

                    $cellFormatter = "formatter: function(v, r, i) {
                        if (v) {
                            if (typeof r.physicalpath !== 'undefined') {
                                var physicalpath = r.physicalpath;
                                if (physicalpath !== '' && physicalpath !== null && physicalpath !== 'null') {    
                                    return dataViewFileView(v, r, '$metaDataId', '$refStructureId');
                                } else {
                                    return '<a href=\"javascript:;\" onclick=\"gridDrillDownLink(this, \'$metaDataCode\', \'". $link_metatypecode ."\', \'". $clinkMetadataId ."\', \'". $link_linkcriteria ."\', \'". $metaDataId ."\', \'". $row['FIELD_PATH'] ."\', \'". $link_linkmetadataid ."\', \'". $sourceParam ."\', $isnewTab, undefined,  \'". $link_dialogWidth ."\',  \'". $link_dialogHeight ."\')\"><span class=\"\">'+ v +'</span></a>';
                                }
                            } else {
                                return '<a href=\"javascript:;\" onclick=\"gridDrillDownLink(this, \'$metaDataCode\', \'". $link_metatypecode ."\', \'". $clinkMetadataId ."\', \'". $link_linkcriteria ."\', \'". $metaDataId ."\', \'". $row['FIELD_PATH'] ."\', \'". $link_linkmetadataid ."\', \'". $sourceParam ."\', $isnewTab, undefined,  \'". $link_dialogWidth ."\',  \'". $link_dialogHeight ."\')\"><span class=\"\">'+ v +'</span></a>';
                            }
                        } else {
                            return '';
                        } 
                    },";

                } elseif ($row['FIELD_PATH'] == 'downloadfile') {

                    $cellFormatter = "formatter: function(v, r, i) {
                        if (v) {
                            return '<a href=\"mdobject/downloadFile?fDownload=1&file=' + v.replace(URL_APP, '') + '\" target=\"_blank\"><i class=\"fa fa-download\"></i> Татах</a>';
                        } 
                        return '';
                    },";

                } elseif ($row['FIELD_PATH'] == 'wfmstatusname') {

                    $cellFormatter = "formatter: function(v, r, i) {return dataViewWfmStatusName(v, r, i, '$metaDataId', '$refStructureId');},";

                } elseif ($row['FIELD_PATH'] == 'pfnextstatuscolumn') {

                    $cellFormatter = "formatter: function(v, r, i) {return dataViewWfmStatusButtons(v, r, i, '$metaDataId', '$refStructureId', '$metaDataCode');},";

                } elseif ($row['FIELD_PATH'] == 'pfnextstatuscolumnjson') {

                    $cellFormatter = "formatter: function(v, r, i) {return dvWfmStatusButtonsByJson(v, r, i, '$metaDataId', '$metaDataCode');},";

                } elseif ($row['FIELD_PATH'] == 'base64download') {

                    $cellFormatter = "formatter: function(v, r, i) {return dataViewBase64DownloadLink(v, r, i, '$metaDataId', '$refStructureId');},";

                } elseif ($row['META_TYPE_CODE'] == 'file' || $row['META_TYPE_CODE'] == 'file_icon') {
                    
                    if ($row['DRILLDOWN_COLUMN'] > 0) {
                        if ($row['DRILLDOWN_META_TYPE_CODE'] == 'process') {
                            $batchNumber = issetParam($processCommandLink['processMetaDataId']);

                            if (isset($processCommandLink['functionName'])) {
                                $cellFormatter = "formatter: function(v, r, i) {if (typeof v !== 'undefined' && v != null) return '<a href=\"javascript:;\" onclick=\"drillDownTransferProcessAction(\'".$processCommandLink['functionName']."\', \'". $linkMetaData['CLINK_META_DATA_ID'] ."\', \'". $linkMetaData['CRITERIA'] ."\', \'\', \'". $processCommandLink['metaDataId'] ."\', \'". $linkMetaData['LINK_META_DATA_ID'] ."\', \'". $processCommandLink['type'] ."\', \'\', ". $processCommandLink['element'] .", {callerType: \'".$metaDataCode."\', isDrillDown: true, drillDownPath: \'".$row['FIELD_PATH']."\'});\">'+ v + '</a>';},";
                            }
                        } else {
                            $cellFormatter = "formatter: function(v, r, i, c) {
                                    if (v) {
                                        if (typeof c !== 'undefined') {
                                            return '<a href=\"javascript:;\" onclick=\"gridDrillDownLink(this, \'$metaDataCode\', \'". $link_metatypecode ."\', \'". $clinkMetadataId ."\', \'". $link_linkcriteria ."\', \'". $metaDataId ."\', \'". $row['FIELD_PATH'] ."\', \'". $link_linkmetadataid ."\', \'". $sourceParam ."\', $isnewTab, undefined, \'". $link_dialogWidth ."\', \'". $link_dialogHeight ."\')\">'+ v + '</a>';
                                        } else {
                                            return '<a href=\"javascript:;\" onclick=\"gridDrillDownLink(this, \'$metaDataCode\', \'". $link_metatypecode ."\', \'". $clinkMetadataId ."\', \'". $link_linkcriteria ."\', \'". $metaDataId ."\', \'". $row['FIELD_PATH'] ."\', \'". $link_linkmetadataid ."\', \'". $sourceParam ."\', $isnewTab, undefined, \'". $link_dialogWidth ."\', \'". $link_dialogHeight ."\')\">".$this->lang->line('more')."</a>';
                                        }
                                    } else {
                                        return '';
                                    }
                                },";
                        }
                    } else if ($renderType = issetParam($row['RENDER_TYPE'])) {                    
                        $cellFormatter = "formatter: function(v, r, i, c) {
                            return gridFileColumnRenderType(this, '$metaDataId', '$renderType', '".$row['MAX_VALUE']."', v, r, i, c);
                        },";
                    } else {
                        $cellFormatter = ($row['META_TYPE_CODE'] == 'file_icon') ? 'formatter: gridFileOnlyIconField,' : 'formatter: gridFileField,';
                    }

                    $bodyAlign = "align: 'center',";

                } elseif ($row['META_TYPE_CODE'] == 'number') {
                    
                    if ($row['DRILLDOWN_COLUMN'] > 0) {
                        
                        if ($row['DRILLDOWN_META_TYPE_CODE'] == 'process') {
                            
                            if (isset($processCommandLink['functionName'])) {
                                $cellFormatter = "formatter: function(v, r, i) {
                                        if (v) {
                                            return '<a href=\"javascript:;\" onclick=\"drillDownTransferProcessAction(\'".$processCommandLink['functionName']."\', \'". $linkMetaData['CLINK_META_DATA_ID'] ."\', \'". $linkMetaData['CRITERIA'] ."\', \'\', \'". $processCommandLink['metaDataId'] ."\', \'". $linkMetaData['LINK_META_DATA_ID'] ."\', \'". $processCommandLink['type'] ."\', \'\', ". $processCommandLink['element'] .", {callerType: \'".$metaDataCode."\', isDrillDown: true, drillDownPath: \'".$row['FIELD_PATH']."\'})\">'+ v + '</a>';
                                        } else {
                                            return '';
                                        }
                                    },";
                            }
                            
                        } else {
                            $cellFormatter = "formatter: function(v, r, i) {
                                if (v) {
                                    return '<a href=\"javascript:;\" onclick=\"gridDrillDownLink(this, \'$metaDataCode\', \'". $link_metatypecode ."\', \'". $clinkMetadataId ."\', \'". $link_linkcriteria ."\', \'". $metaDataId ."\', \'". $row['FIELD_PATH'] ."\', \'". $link_linkmetadataid ."\', \'". $sourceParam ."\', $isnewTab, undefined, \'". $link_dialogWidth ."\', \'". $link_dialogHeight ."\')\">'+ v + '</a>';
                                } else {
                                    return '';
                                } 
                            },";
                        }
                    }

                    if ($gridOption['INLINEEDIT'] == 'true') {
                        $cellEditor = 'editor: {type: "textbox", options: {}},';
                    }                    
                    
                    $filterNumberInit[] = "input[name=" . $row['FIELD_PATH'] . "]";
                    $bodyAlign = "align: 'right',";
                    
                } elseif ($row['META_TYPE_CODE'] == 'long' || $row['META_TYPE_CODE'] == 'integer') {
                    
                    if ($row['DRILLDOWN_COLUMN'] > 0) {
                        
                        if ($row['DRILLDOWN_META_TYPE_CODE'] == 'process') {
                            
                            if (isset($processCommandLink['functionName'])) {
                                $cellFormatter = "formatter: function(v, r, i) {
                                        if (v) {
                                            return '<a href=\"javascript:;\" onclick=\"drillDownTransferProcessAction(\'".$processCommandLink['functionName']."\', \'". $linkMetaData['CLINK_META_DATA_ID'] ."\', \'". $linkMetaData['CRITERIA'] ."\', \'\', \'". $processCommandLink['metaDataId'] ."\', \'". $linkMetaData['LINK_META_DATA_ID'] ."\', \'". $processCommandLink['type'] ."\', \'\', ". $processCommandLink['element'] .", {callerType: \'".$metaDataCode."\', isDrillDown: true, drillDownPath: \'".$row['FIELD_PATH']."\'})\">' + ((v != '' && v != null) ? number_format(v, 0, '.', '') : '') + '</a>';
                                        } else {
                                            return '';
                                        }
                                    },";
                            }
                            
                        } else {
                            $cellFormatter = "formatter: function(v, r, i) {
                                if (v) {
                                    return '<a href=\"javascript:;\" onclick=\"gridDrillDownLink(this, \'$metaDataCode\', \'". $link_metatypecode ."\', \'". $clinkMetadataId ."\', \'". $link_linkcriteria ."\', \'". $metaDataId ."\', \'". $row['FIELD_PATH'] ."\', \'". $link_linkmetadataid ."\', \'". $sourceParam ."\', $isnewTab, undefined, \'". $link_dialogWidth ."\', \'". $link_dialogHeight ."\')\">' + ((v != '' && v != null) ? number_format(v, 0, '.', '') : '') + '</a>';
                                } else {
                                    return '';
                                } 
                            },";
                        }
                        
                    } else {
                        $cellFormatter = "formatter: function(v, r, i) {
                            return (v != '' && v != null) ? number_format(v, 0, '.', '') : ''; 
                        },";
                    }
                    
                    $filterNumberInit[] = "input[name=" . $row['FIELD_PATH'] . "]";
                    $bodyAlign = "align: 'right',";
                    
                } elseif ($row['META_TYPE_CODE'] == 'boolean') {
                    
                    $cellFormatter = 'formatter: gridBooleanField,';
                    $bodyAlign = "align: 'center',";
                    $headerAlign = "halign: 'center',";
                    
                    if ($gridOption['INLINEEDIT'] == 'true') {
                        if (isset($getInlineEditMapDataGroup[$row['FIELD_PATH']])) {
                            $getMapParamName = $getInlineEditMapDataGroup[$row['FIELD_PATH']];
                            
                            if (isset($getProcessParamsGroup[$getMapParamName])) {
                                $cellEditor = 'editor: {type: "checkbox",options: {
                                    on: \'1\',
                                    off: \'0\'
                                }},';                        
                            }
                        }
                    }
                    
                } elseif ($row['META_TYPE_CODE'] == 'password') {
                    
                    $cellFormatter = 'formatter: gridPasswordField,';
                    $bodyAlign = "align: 'center',";
                    $headerAlign = "halign: 'center',";
                    
                } elseif ($row['META_TYPE_CODE'] == 'star') {
                    
                    $cellFormatter = 'formatter: gridStarField,';
                    $bodyAlign = "align: 'center',";
                    $headerAlign = "halign: 'center',";
                    
                } elseif ($row['META_TYPE_CODE'] == 'decimal_to_time') {
                    
                    $cellFormatter = 'formatter: gridNumberToTime,';
                    
                } elseif ($row['META_TYPE_CODE'] == 'html_decode') {
                    
                    $cellFormatter = 'formatter: gridHtmlDecode,';
                    
                } elseif ($row['META_TYPE_CODE'] == 'url') {
                    
                    $cellFormatter = "formatter: function(v, r, i) { return '<a href=\"'+ v + '\" target=\"_blank\">'+ v + '</a>'; },";
                    
                } else {
                    
                    if ($gridOption['INLINEEDIT'] == 'true') {
                        
                        if (isset($getInlineEditMapDataGroup[$row['FIELD_PATH']])) {
                            $getMapParamName = $getInlineEditMapDataGroup[$row['FIELD_PATH']];
                            
                            if (isset($getProcessParamsGroup[$getMapParamName])) {
                                $getProcParam = $getProcessParamsGroup[$getMapParamName];
                                
                                if ($getProcParam['row']['LOOKUP_TYPE'] != '' && $getProcParam['row']['LOOKUP_META_DATA_ID'] != '') {
                                    $lookupTypeLower = $getProcParam['row']['LOOKUP_TYPE'];
                                    
                                    if ($lookupTypeLower == 'combo') {
                                        $cellEditor = 'editor: {type: "combobox",options: {
                                            valueField: "META_VALUE_ID",
                                            textField: "META_VALUE_NAME",
                                            panelHeight: \'auto\',
                                            editable: false,
                                            url: "mdobject/dataViewInlineEditCombo/'.$getProcParam['row']['LOOKUP_META_DATA_ID'].'/'.$getProcParam['row']['VALUE_FIELD'].'/'.$getProcParam['row']['DISPLAY_FIELD'].'",
                                            required: '.$getProcParam['row']['IS_REQUIRED'].',
                                            onLoadSuccess: function(rows){
                                                var getComboVal = $(this).combobox(\'getValue\').trim().toLowerCase();
                                                for (var ri = 0; ri < rows.length; ri++) {
                                                    if (rows[ri][\'META_VALUE_NAME\'].trim().toLowerCase() == getComboVal) {
                                                        $(this).combobox(\'setValue\', rows[ri][\'META_VALUE_ID\']);
                                                    }
                                                }
                                                return;
                                            }                                                
                                          }},';
                                    }
                                    
                                } else {
                                    $cellEditor = 'editor: {type: "textbox", options: {required: '.$getProcParam['row']['IS_REQUIRED'].'}},';
                                }
                            }
                        }
                    }              
                    
                    if ($row['DRILLDOWN_COLUMN'] > 0) {
                        
                        if (isset($row['IGNORE_DRILL_META']) && $row['IGNORE_DRILL_META'] && $row['IGNORE_DRILL_META'] != '$@$' && Input::isEmpty('processMetaDataId') == false && strpos($row['IGNORE_DRILL_META'], Input::post('processMetaDataId')) !== false) {
                            
                            $cellFormatter = '';

                        } else {
                            
                            if ($row['DRILLDOWN_META_TYPE_CODE'] == 'process') {
                                
                                if (isset($processCommandLink['functionName'])) {
                                    $cellFormatter = "formatter: function(v, r, i) {if (typeof v !== 'undefined' && v != null) return '<a href=\"javascript:;\" onclick=\"drillDownTransferProcessAction(\'".$processCommandLink['functionName']."\', \'". $linkMetaData['CLINK_META_DATA_ID'] ."\', \'". $linkMetaData['CRITERIA'] ."\', \'\', \'". $processCommandLink['metaDataId'] ."\', \'". $linkMetaData['LINK_META_DATA_ID'] ."\', \'". $processCommandLink['type'] ."\', \'\', ". $processCommandLink['element'] .", {callerType: \'".$metaDataCode."\', isDrillDown: true, drillDownPath: \'".$row['FIELD_PATH']."\'});\">'+ gridHtmlDecode(v) +'</a>';},";
                                } 

                            } else {
                                $cellFormatter = "formatter: function(v, r, i) {
                                    if (v) {
                                        return '<a href=\"javascript:;\" onclick=\"gridDrillDownLink(this, \'$metaDataCode\', \'". $link_metatypecode ."\', \'". $clinkMetadataId ."\', \'". str_replace("'", "\\\\\'", $link_linkcriteria) ."\', \'". $metaDataId ."\', \'". $row['FIELD_PATH'] ."\', \'". $link_linkmetadataid ."\', \'". $sourceParam ."\', $isnewTab, undefined, \'". $link_dialogWidth ."\', \'". $link_dialogHeight ."\');\" data-p-path=\"". $link_passPath ."\">'+ v +'</a>';
                                    } else {
                                        return '';
                                    }
                                },";
                            }
                        }
                    }
                }

                if ($row['TEXT_WEIGHT'] != '') {
                    $cellStyle .= 'font-weight:' . $row['TEXT_WEIGHT'] . ';';
                }

                if ($row['TEXT_COLOR'] != '') {
                    $cellStyle .= 'color:' . $row['TEXT_COLOR'] . ';';
                }

                if ($row['TEXT_TRANSFORM'] != '') {
                    $cellStyle .= 'text-transform:' . $row['TEXT_TRANSFORM'] . ';';
                }
                
                if ($row['BG_COLOR'] != '') {
                    $cellStyle .= "background-color: '+(r.hasOwnProperty('".$row['FIELD_PATH']."_bgcolor') ? r.".$row['FIELD_PATH']."_bgcolor : '".$row['BG_COLOR']."')+';";
                }
                
                if ($row['FONT_SIZE'] != '') {
                    $cellStyle .= 'font-size:' . $row['FONT_SIZE'] . ';';
                }

                if (!empty($cellStyle)) {
                    $cellStyle = "styler: function(v, r, i) {return '$cellStyle';},";
                }

                if ($row['HEADER_ALIGN'] != '') {
                    $headerAlign = "halign: '" . $row['HEADER_ALIGN'] . "',";
                }

                if ($row['BODY_ALIGN'] != '') {
                    $bodyAlign = "align: '" . $row['BODY_ALIGN'] . "',";
                    
                    if ($row['BODY_ALIGN'] == 'center') {
                        $filterCenterInit[] = "input[name=" . $row['FIELD_PATH'] . "]";
                    }
                }

                if ($colspanRender) {
                    $rowspan = 'rowspan:2,';
                }
                
                $ticket = false;
                
                if ($colspanRender && isset($groupRowSpanArray[$row['SIDEBAR_NAME']]) && in_array($row, $groupRowSpanArray[$row['SIDEBAR_NAME']]['rows'])) {
                    
                    if (in_array($row['SIDEBAR_NAME'], $array_needle) && $colTicket) {
                        
                        $freezeNumOrder = $freezeNumOrder + $row['DV_COLSPAN'];
                        $sidebarName = Lang::line($row['SIDEBAR_NAME']);
                        
                        if ($num <= (int) $dataviewCustomerCfg['ORDER_NUM']) {
                            
                            if ($dataviewCustomerCfg['ORDER_NUM'] < $freezeNumOrder) {
                                
                                $rowspan1 = (int) $dataviewCustomerCfg['ORDER_NUM'] - (int) $row['DV_COLSPAN'];
                                if (0 > $rowspan1) {
                                    $rowspan2 = 0 - $rowspan1;
                                    $rowspan1 = (int) $row['DV_COLSPAN'] - $rowspan2;
                                } else {
                                    $rowspan2 = (int) $freezeNumOrder - $dataviewCustomerCfg['ORDER_NUM'];
                                }
                                
                                $freeze .= "{title:'". self::dataGridTitleReplacer($sidebarName) ."',colspan:". $rowspan1 ."},";
                                $header .= "{title:'". self::dataGridTitleReplacer($sidebarName) ."',colspan:". $rowspan2 ."},";
                            } else {
                                $checkNum = $num - 1;
                                if ($num === (int) $dataviewCustomerCfg['ORDER_NUM'] && $checkNum < (int) $dataviewCustomerCfg['ORDER_NUM']) {
                                    
                                    $rowspan1 = (int) $dataviewCustomerCfg['ORDER_NUM'] - (int) $checkNum;
                                    $rowspan2 = (int) $row['DV_COLSPAN'] - $rowspan1;
                                    if ($row['DV_COLSPAN'] < $rowspan1 && $rowspan2 > 0) {
                                        $freeze .= "{title:'". self::dataGridTitleReplacer($sidebarName) ."',colspan:". $row['DV_COLSPAN'] ."},";
                                    } else {
                                        $freeze .= "{title:'". self::dataGridTitleReplacer($sidebarName) ."',colspan:". $rowspan1 ."},";
                                        if ($rowspan2 > 0)
                                            $header .= "{title:'". self::dataGridTitleReplacer($sidebarName) ."',colspan:". $rowspan2 ."},";
                                    }
                                } else {
                                    
                                    if ($num < $dataviewCustomerCfg['ORDER_NUM']) {
                                        $rowspan1 = (int) $dataviewCustomerCfg['ORDER_NUM'] - (int) $num;
                                        $rowspan2 = (int) $row['DV_COLSPAN'] - $rowspan1;
                                        if ($row['DV_COLSPAN'] <= $rowspan1 || $row['DV_COLSPAN'] == $dataviewCustomerCfg['ORDER_NUM']) {
                                            $freeze .= "{title:'". self::dataGridTitleReplacer($sidebarName) ."',colspan:". $row['DV_COLSPAN'] ."},";
                                        } else {
                                            $freeze .= "{title:'". self::dataGridTitleReplacer($sidebarName) ."',colspan:". $rowspan1 ."},";
                                            $header .= "{title:'". self::dataGridTitleReplacer($sidebarName) ."',colspan:". $rowspan2 ."},";
                                            $ticket = true;
                                        }
                                    } else {
                                        $freeze .= "{title:'". self::dataGridTitleReplacer($sidebarName) ."',colspan:". $row['DV_COLSPAN'] ."},";
                                    }
                                }
                            }
                            
                        } else {
                            
                            $dataviewCustomerCfg['ORDER_NUM'] = '0';
                            $header .= "{title:'". self::dataGridTitleReplacer($sidebarName) ."',colspan:". $row['DV_COLSPAN'] ."},";
                        }
                    }
                    
                    if ($num <= (int) $dataviewCustomerCfg['ORDER_NUM'] && !$ticket) {
                        $freezeRow  .= "{field:'" . $row['FIELD_PATH'] . "',title:'" . self::dataGridTitleReplacer(Lang::line($row['LABEL_NAME'])) . "',sortable:true," . $fixedColumn . $width . $cellStyle . $headerAlign . $bodyAlign . $cellFormatter . $cellEditor . "},";
                    } else {
                        $colspanRow .= "{field:'" . $row['FIELD_PATH'] . "',title:'" . self::dataGridTitleReplacer(Lang::line($row['LABEL_NAME'])) . "',sortable:true," . $fixedColumn . $width . $cellStyle . $headerAlign . $bodyAlign . $cellFormatter . $cellEditor . "},";
                    }
                    
                    if ($row['IS_SHOW'] == '1' && $row['IS_MERGE'] == '1') {
                        array_push($isMergeColumn, $row['FIELD_PATH']);
                    }
                    
                } else {
                    
                    if ($row['IS_SHOW'] == '1') {

                        if ($row['IS_MERGE'] == '1') {
                            array_push($isMergeColumn, $row['FIELD_PATH']);
                        }

                        if ($dataviewCustomerCfg['IS_SHOW'] == '1' && $dataviewCustomerCfg['IS_FREEZE'] == '1') {
                            if ($num <= (int) $dataviewCustomerCfg['ORDER_NUM']) {

                                if (!isset($colspanRender[$row['ID']])) {
                                    $rowspan = 'rowspan:1,';
                                }

                                $freeze .= "{field:'" . $row['FIELD_PATH'] . "',title:'" . self::dataGridTitleReplacer(Lang::line($row['LABEL_NAME'])) . "',sortable:true," . $fixedColumn . $width . $cellStyle . $headerAlign . $bodyAlign . $cellFormatter . $cellEditor . $rowspan . "},";

                            } else {
                                $header .= "{field:'" . $row['FIELD_PATH'] . "',title:'" . self::dataGridTitleReplacer(Lang::line($row['LABEL_NAME'])) . "',sortable:true," . $fixedColumn . $width . $cellStyle . $headerAlign . $bodyAlign . $cellFormatter . $cellEditor . $rowspan . "},";
                            }

                        } else {
                            $header .= "{field:'" . $row['FIELD_PATH'] . "',title:'" . self::dataGridTitleReplacer(Lang::line($row['LABEL_NAME'])) . "',sortable:true," . $fixedColumn . $width . $cellStyle . $headerAlign . $bodyAlign . $cellFormatter . $cellEditor . $rowspan . "},";
                        }  

                    } else {
                        $header .= "{field:'" . $row['FIELD_PATH'] . "',title:'" . self::dataGridTitleReplacer(Lang::line($row['LABEL_NAME'])) . "', hidden: true, sortable:true," . $fixedColumn . $width . $cellStyle . $headerAlign . $bodyAlign . $cellFormatter . $cellEditor . $rowspan . "},";
                    }
                }
                $num++;
                
                if ($isGroupFieldUser && $groupField && $groupField == $row['FIELD_PATH']) {
                    $isExistsGroupField = true;
                }
            }
            
            if (Mdobject::$pfKpiTemplateDynamicColumn) {
                
                $kpiColumns = self::getKpiTemplateColumns(Mdobject::$pfKpiTemplateDynamicColumn);
                
                foreach ($kpiColumns as $kpiRow) {
                    
                    $labelName = Lang::line($kpiRow['INDICATOR_NAME']);
                    $showType = $kpiRow['SHOW_TYPE'];
                    $factWidth = $kpiRow['FACT_WIDTH'];
                    $cellStyle = '';
                    $cellFormatter = '';
                    $cellEditor = '';
                    $width = "width: '150',";
                    $fixedColumn = 'fixed: true,';
                    $headerAlign = "halign: 'center',";
                    $bodyAlign = "align: 'left',";
                    
                    if ($showType == 'decimal' || $showType == 'bigdecimal' || $showType == 'number' || $showType == 'long' || $showType == 'integer') {
                        $bodyAlign = "align: 'right',";
                    }
                    
                    if ($showType == 'decimal' || $showType == 'bigdecimal') {
                        $cellFormatter = 'formatter: gridAmountNullField,';
                    }
                    
                    if ($factWidth != '') {
                        $width = "width: '$factWidth',";
                    }
                
                    $header .= "{field:'".$kpiRow['INDICATOR_ID'].'_kpi_'.$kpiRow['PARAM_PATH']."',title:'" . self::dataGridTitleReplacer($labelName) . "',sortable:true," . $fixedColumn . $width . $cellStyle . $headerAlign . $bodyAlign . $cellFormatter . $cellEditor . $rowspan . "},";
                }
            }

            $header .= ']';
            $freeze .= ']';

            if ($colspanRender) {
                if ($freezeRow) {
                    $freezeRow = ',['. $freezeRow . ']';
                }
                if ($colspanRow) {
                    $colspanRow = ',['. $colspanRow . ']';
                }
            }

            $array = array_merge($array, array(
                    'header' => '['. $header . $colspanRow . ']',
                    'freeze' => '['. $freeze . $freezeRow . ']',
                    'isMergeColumn' => $isMergeColumn
                )
            );
            
            if (count($filterCenterInit) > 0) {
                $array = array_merge($array, array(
                    'filterCenterInit' => '$panelView.find(".datagrid-htable").find(".datagrid-filter-row").find("' . implode(",", $filterCenterInit) . '").addClass("text-center");'
                    )
                );
            }

            if (count($filterDateInit) > 0) {
                $array = array_merge($array, array(
                    'filterDateInit' => '$panelView.find(".datagrid-htable").find(".datagrid-filter-row").find("' . implode(",", $filterDateInit) . '").addClass("dateMaskInit text-center");'
                    )
                );
            }

            if (count($filterTimeInit) > 0) {
                $array = array_merge($array, array(
                    'filterTimeInit' => '$panelView.find(".datagrid-htable").find(".datagrid-filter-row").find("' . implode(",", $filterTimeInit) . '").addClass("timeMaskInit text-center");'
                    )
                );
            }

            if (count($filterDateTimeInit) > 0) {
                $array = array_merge($array, array(
                    'filterDateTimeInit' => '$panelView.find(".datagrid-htable").find(".datagrid-filter-row").find("' . implode(",", $filterDateTimeInit) . '").addClass("dateMinuteMaskInit");'
                    )
                );
            }

            if (count($filterBigDecimalInit) > 0) {
                $array = array_merge($array, array(
                    'filterBigDecimalInit' => '$panelView.find(".datagrid-htable").find(".datagrid-filter-row").find("' . implode(",", $filterBigDecimalInit) . '").addClass("bigdecimalInit");'
                    )
                );
            }

            if (count($filterNumberInit) > 0) {
                $array = array_merge($array, array(
                    'filterNumberInit' => '$panelView.find(".datagrid-htable").find(".datagrid-filter-row").find("' . implode(",", $filterNumberInit) . '").addClass("longInit");'
                    )
                );
            }
            
            if ($isGroupFieldUser) {
                
                if (!isset($isExistsGroupField) && issetParam($gridOption['GROUPFIELDLABELNAME'])) {
                    
                    $groupFieldRow = array(array(
                        'LABEL_NAME' => $gridOption['GROUPFIELDLABELNAME'], 
                        'FIELD_PATH' => $groupField
                    ));
                    
                    $gridData = array_merge($groupFieldRow, $gridData);
                }
                
                Mdobject::$onlyShowColumns = $gridData;
            }
        }

        return $array;
    }    

    public function renderDataViewGridModelNew($metaDataId, $metaDataCode, $refStructureId = '', $isPopupWindow = false, $isBasketWindow = false) {

        $gridData = self::getDataViewGridHeaderModel($metaDataId, '1 = 1', 1, $isPopupWindow, $isBasketWindow);
        
        $array_needle = $arraytemp = $array_temp = $array = $getInlineEditMapDataGroup = $getProcessParamsGroup = array();
        
        $colspanRender = [];
        $groupRowSpanArray = [];
        
        if ($gridData) {

            $gridOption = self::getDVGridOptionsModel($metaDataId);
            
            if (issetParam($gridOption['ISPIVOTQUERY']) == 'true' && issetParam($gridOption['HEADERMENUTYPE']) == 'work5day') {
                
                $currentDate = Date::currentDate('Y-m-d');
                $gridDataTemp = $gridDataTemp1 = array();
                
                foreach ($gridData as $row) {
                    switch ($row['LABEL_NAME']) {
                        case '1':
                            $row['LABEL_NAME'] = Date::addWorkingDays('m/d', '-', $currentDate, 5);
                            $row['LABEL_NAME'] = $row['LABEL_NAME'] . ' ('. Date::format('D', $row['LABEL_NAME']) .')';
                            array_push($gridDataTemp, $row);
                            break;
                        case '2':
                            $row['LABEL_NAME'] = Date::addWorkingDays('m/d', '-', $currentDate, 4);
                            $row['LABEL_NAME'] = $row['LABEL_NAME'] . ' ('. Date::format('D', $row['LABEL_NAME']) .')';
                            array_push($gridDataTemp, $row);
                            break;
                        case '3':
                            $row['LABEL_NAME'] = Date::addWorkingDays('m/d', '-', $currentDate, 3);
                            $row['LABEL_NAME'] = $row['LABEL_NAME'] . ' ('. Date::format('D', $row['LABEL_NAME']) .')';
                            array_push($gridDataTemp, $row);
                            break;
                        case '4':
                            $row['LABEL_NAME'] = Date::addWorkingDays('m/d', '-', $currentDate, 2);
                            $row['LABEL_NAME'] = $row['LABEL_NAME'] . ' ('. Date::format('D', $row['LABEL_NAME']) .')';
                            array_push($gridDataTemp, $row);
                            break;
                        case '5':
                            $row['LABEL_NAME'] = Date::addWorkingDays('m/d', '-', $currentDate, 1);
                            $row['LABEL_NAME'] = $row['LABEL_NAME'] . ' ('. Date::format('D', $row['LABEL_NAME']) .')';
                            array_push($gridDataTemp, $row);
                            break;
                        default :
                            array_push($gridDataTemp1, $row);
                            break;
                    }
                }
                
                $gridData = array_merge($gridDataTemp1, Arr::sortBy('LABEL_NAME', $gridDataTemp));
            }
            
            $isDrill = ($gridOption['DRILLDBLCLICKROW'] == 'true' ? false : true);
                    
            if ($gridOption['INLINEEDIT'] == 'true') {
                $getInlineEditMapData = self::getInlineEditMapConfig($metaDataId);
                
                if ($getInlineEditMapData) {
                    $getInlineEditMapDataGroup = Arr::groupByArrayOnlyRow($getInlineEditMapData, 'SRC_PARAM_PATH', 'TRG_PARAM_PATH');
                    $getProcessParamsGroup = Arr::groupByArrayLower(Mdwebservice::groupParamsData($getInlineEditMapData[0]['PROCESS_META_DATA_ID']), 'META_DATA_CODE');
                    $this->load->model('mdobject', 'middleware/models/');
                }
            }

            $isCkCol = false;
            $header = '[';             
            $freeze = '[';
            $num = 1;
            $freezeRow = ''; 
            // $colspanRow = ''; 
            
//            if (isset($gridOption['SHOWCHECKBOX']) && $gridOption['SHOWCHECKBOX'] == 'true') {
//                $freeze .= "{field: 'ck', rowspan:1, checkbox: true },";
//            }

            $filterDateInit = $filterDateTimeInit = $filterTimeInit = $filterBigDecimalInit = $filterNumberInit = $filterCenterInit = $isMergeColumn = array();

            $dataviewCustomerCfg['ORDER_NUM'] = 0;
            if ($gridData[0]['USER_CONFIG_COUNT']) {
                $dataviewCustomerCfg = self::dataviewCustomerConfigModel($metaDataId);
            } 
            
            if (!$dataviewCustomerCfg['ORDER_NUM']) {
                $isDefaultFreeze = helperSumFieldBp($gridData, 'IS_DEFAULT_FREEZE');
                if ($isDefaultFreeze) {
                    $i = 1;
                    foreach ($gridData as $freezeDefaultRow) {
                        if ($freezeDefaultRow['IS_DEFAULT_FREEZE'] == '1') {
                            $dataviewCustomerCfg = array('IS_SHOW' => 1, 'IS_FREEZE' => 1, 'ORDER_NUM' => $i);
                            break;
                        }
                        $i++;
                    }
                } else {
                    $dataviewCustomerCfg = array('IS_SHOW' => 0, 'IS_FREEZE' => 0, 'ORDER_NUM' => 0);
                }
            }

            $freezeNumOrder = 0;
            $isGroupFieldUser = false;            
            
            if (issetParam($gridOption['GROUPFIELDUSER']) == 'true') {
                $groupField = strtolower($gridOption['GROUPFIELD']);
                $isGroupFieldUser = true;
            }
            
            $mergedGridData = self::resolveHtmlTableMergeHeader($gridData, $metaDataId, $dataviewCustomerCfg);
            $mergeCount = count($mergedGridData);
            
            $rowspan = '';
            
            if ($mergeCount) {
                $rowspan = 'rowspan:'.$mergeCount.',';
            }                   

            foreach ($mergedGridData as $mergeIndex => $mergeRow) {                
                $freezeArr = [];
                $header .= '[';
                $freezeRow .= '[';

                if (!$isCkCol && isset($gridOption['SHOWCHECKBOX']) && $gridOption['SHOWCHECKBOX'] == 'true') {
                    $freezeArr[0] = "{field: 'ck', rowspan:".($mergeCount-1).", checkbox: true },";
                    $isCkCol = true;
                }                
                
                foreach ($mergeRow as $mrowIndex => $row) {
                    $freezeRowCheck = '';
                    
                    if (isset($row['MERGE_LABEL_NAME'])) {                        
                        
                        if ($dataviewCustomerCfg['IS_SHOW'] == '1' && $dataviewCustomerCfg['IS_FREEZE'] == '1') {
                            if ($row['ORDER_NUM'] <= (int) $dataviewCustomerCfg['ORDER_NUM']) {
                                $freezeRowCheck = "{title:'". self::dataGridTitleReplacer(Lang::line($row['MERGE_LABEL_NAME'])) ."',colspan:". $row['_COLSPAN'] ."},";
                                $freezeArr[$row['ORDER_NUM']] = "{title:'". self::dataGridTitleReplacer(Lang::line($row['MERGE_LABEL_NAME'])) ."',colspan:". $row['_COLSPAN'] ."},";
                            }
                        }
                                                
                        if ($freezeRowCheck) {
                        } else {
                            $header .= "{title:'". self::dataGridTitleReplacer(Lang::line($row['MERGE_LABEL_NAME'])) ."',colspan:". $row['_COLSPAN'] ."},";
                        }
                        continue;
                        
                    }
                
                    $rowspan = '';

                    if ($isDrill) {
                        $ddown = self::getDrillDownMetaDataModel($metaDataId, $row['FIELD_PATH']);             
                    } else {
                        $row['DRILLDOWN_COLUMN'] = 0;
                        $ddown = null;
                    }

                    $isnewTab = 'false';

                    $link_metatypecode = $link_linkmetadataid = $link_linkcriteria = $link_dialogWidth = 
                    $link_dialogHeight = $link_passPath = $sourceParam = '';
                    $clinkMetadataId = 0;

                    if ($ddown) {

                        $sizeDrillDownArray = count($ddown);

                        if ($sizeDrillDownArray > 1) {

                            $linkDrillDown = self::drilldownParams($ddown);              

                            $link_metatypecode = $linkDrillDown['LINK_METATYPECODE'];
                            $link_linkmetadataid = $linkDrillDown['LINK_METADATAID'];
                            $link_linkcriteria = Str::nlToSpace($linkDrillDown['LINK_CRITERIA']);
                            $link_dialogWidth = $linkDrillDown['DIALOG_WIDTH'];
                            $link_dialogHeight = $linkDrillDown['DIALOG_HEIGHT'];
                            $clinkMetadataId = $linkDrillDown['LINK_COUNT'];
                            $sourceParam = $linkDrillDown['LINK_PARAM'];

                        } else {

                            $link_metatypecode = $ddown[0]['META_TYPE_CODE'];
                            $link_linkmetadataid = $ddown[0]['LINK_META_DATA_ID'];
                            $link_linkcriteria = Str::nlToSpace($ddown[0]['CRITERIA']);
                            $link_dialogWidth = Str::nlToSpace($ddown[0]['DIALOG_WIDTH']);
                            $link_dialogHeight = Str::nlToSpace($ddown[0]['DIALOG_HEIGHT']);
                            $link_passPath = $ddown[0]['PASSWORD_PATH'];
                            $clinkMetadataId = 1;

                            if ($ddown[0]['DEFAULT_VALUE']) {
                                $sourceParam .= ($ddown[0]['TRG_PARAM']) ? $ddown[0]['TRG_PARAM']."=". $ddown[0]['DEFAULT_VALUE'] : '';
                            } else {
                                $sourceParam .= ($ddown[0]['TRG_PARAM']) ? $ddown[0]['TRG_PARAM']."='+ r.". $ddown[0]['SRC_PARAM'] ." +'" : '';
                            }
                        } 

                        if (isset($ddown[0]['SHOW_TYPE'])) {
                            $showType = explode(',', strtolower($ddown[0]['SHOW_TYPE']));
                            if (isset($showType[0])) {
                                if ($showType[0] == 'tab') {
                                    $isnewTab = 'true';
                                } elseif ($showType[0]) {
                                    $isnewTab = "\'".$showType[0]."\'";
                                }
                            }
                        }
                    }

                    $width = ((empty($row['COLUMN_WIDTH'])) ? "width: '150'," : ((isset($row['PARAM_WIDTH']) && !empty($row['PARAM_WIDTH'])) ? "width: '" . $row['PARAM_WIDTH'] . "'," : "width: '" . $row['COLUMN_WIDTH'] . "',"));
                    $cellStyle = '';
                    $cellFormatter = '';
                    $cellEditor = '';
                    $fixedColumn = 'fixed: true,';
                    $headerAlign = "halign: 'center',";
                    $bodyAlign = "align: 'left',";                    

                    if ($row['DRILLDOWN_COLUMN'] > 0) {

                        $linkMetaData = $this->db->GetRow("
                            SELECT 
                                COUNT(MDD.LINK_META_DATA_ID) AS CLINK_META_DATA_ID,
                                ".$this->db->listAgg('MDD.LINK_META_DATA_ID', ',', 'MDD.LINK_META_DATA_ID, MDD.CRITERIA')." AS LINK_META_DATA_ID, 
                                ".$this->db->listAgg('MDD.CRITERIA', ',', 'MDD.LINK_META_DATA_ID, MDD.CRITERIA')." AS CRITERIA,
                                ".$this->db->listAgg('MDD.DIALOG_WIDTH', ',', 'MDD.LINK_META_DATA_ID, MDD.DIALOG_WIDTH')." AS DIALOG_WIDTH,
                                ".$this->db->listAgg('MDD.DIALOG_HEIGHT', ',', 'MDD.LINK_META_DATA_ID, MDD.DIALOG_HEIGHT')." AS DIALOG_HEIGHT
                            FROM META_DM_DRILLDOWN_DTL MDD
                            WHERE MDD.MAIN_GROUP_LINK_ID = (SELECT ID FROM META_GROUP_LINK WHERE META_DATA_ID = $metaDataId)
                                AND LOWER(MDD.MAIN_GROUP_LINK_PARAM) = LOWER('". $row['FIELD_PATH'] ."')"); 

                        $linkMetaData['CRITERIA'] = str_replace("'", "\\\\\'", Str::nlToSpace($linkMetaData['CRITERIA']));

                        if ($row['DRILLDOWN_META_TYPE_CODE'] == 'process' && !isset($processCommand)) {

                            $processCommand = self::dataViewProcessCommandModel($metaDataId, '', false);
                            $processCommandLink = $processCommand['commandFunction'];
                        }
                    }

                    if (isset($row['INLINE_PROCESS_ID']) && !empty($row['INLINE_PROCESS_ID'])) {

                        $cellFormatter = "formatter: function(v, r, i) {
                            var comboDisable = typeof r.combostatusdisable !== 'undefined' && r.combostatusdisable == 1 ? 'disabled' : '';
                            if (v) {
                                return '<select '+comboDisable+' class=\"form-control form-control-sm dataview-select2-$metaDataId dataview-select2 select2\" data-process-id=".$row['INLINE_PROCESS_ID']." data-lookup-id=".$row['LOOKUP_META_DATA_ID']." data-edit-value='+v+'><option value='+v+'>'+r.combostatusname+'</option></select>';
                            } else {
                                return '<select '+comboDisable+' class=\"form-control form-control-sm dataview-select2-$metaDataId dataview-select2 select2\" data-process-id=".$row['INLINE_PROCESS_ID']." data-lookup-id=".$row['LOOKUP_META_DATA_ID']." data-edit-value=\'\'><option value>- Сонгох -</option></select>';
                            }                            
                        },";

                    } elseif ($row['META_TYPE_CODE'] == 'date') {

                        if ($row['DRILLDOWN_COLUMN'] > 0) {
                            if ($row['DRILLDOWN_META_TYPE_CODE'] == 'process') {
                                $cellFormatter = "formatter: function(v, r, i) { if (typeof v !== 'undefined' && v != null) return '<a href=\"javascript:;\" onclick=\"drillDownTransferProcessAction(\'".$processCommandLink['functionName']."\', \'". $linkMetaData['CLINK_META_DATA_ID'] ."\',  \'". $linkMetaData['CRITERIA'] ."\',  \'\', \'". $processCommandLink['metaDataId'] ."\', \'". $linkMetaData['LINK_META_DATA_ID'] ."\', \'". $processCommandLink['type'] ."\', \'\', ". $processCommandLink['element'] .", {callerType: \'".$metaDataCode."\', isDrillDown: true, drillDownPath: \'".$row['FIELD_PATH']."\'})\">'+dateFormatter('Y-m-d', v)+'</a>';},";
                            } else {
                                $cellFormatter = "formatter: function(v, r, i) {
                                    if (v) {
                                        return '<a href=\"javascript:;\" onclick=\"gridDrillDownLink(this, \'$metaDataCode\', \'". $link_metatypecode ."\', \'". $clinkMetadataId ."\', \'". $link_linkcriteria ."\', \'". $metaDataId ."\', \'". $row['FIELD_PATH'] ."\', \'". $link_linkmetadataid ."\', \'". $sourceParam ."\', $isnewTab, undefined,  \'". $link_dialogWidth ."\', \'". $link_dialogHeight ."\' )\">'+dateFormatter('Y-m-d', v) + '</a>';
                                    } else {
                                        return '';
                                    }
                                },";
                            }
                        } else { 
                            $cellFormatter = "formatter: function(v, r, i) {return dateFormatter('Y-m-d', v);},";
                        }

                        $filterDateInit[] = "input[name=" . $row['FIELD_PATH'] . "]";
                        $bodyAlign = "align: 'center',";

                    } elseif ($row['META_TYPE_CODE'] == 'datetime') {

                        if ($row['DRILLDOWN_COLUMN'] > 0) {
                            if ($row['DRILLDOWN_META_TYPE_CODE'] == 'process') {
                                $cellFormatter = "formatter: function(v, r, i) {return '<a href=\"javascript:;\" onclick=\"drillDownTransferProcessAction(\'".$processCommandLink['functionName']."\', \'". $linkMetaData['CLINK_META_DATA_ID'] ."\',  \'". $linkMetaData['CRITERIA'] ."\',  \'\', \'". $processCommandLink['metaDataId'] ."\', \'". $linkMetaData['LINK_META_DATA_ID'] ."\', \'". $processCommandLink['type'] ."\', \'\', ". $processCommandLink['element'] .", {callerType: \'".$metaDataCode."\', isDrillDown: true, drillDownPath: \'".$row['FIELD_PATH']."\'})\">'+ dateFormatter('Y-m-d H:i:s', v) + '</a>';},";
                            } else {
                                $cellFormatter = "formatter: function(v, r, i) {
                                    if (v)
                                        return '<a href=\"javascript:;\" onclick=\"gridDrillDownLink(this, \'$metaDataCode\', \'". $link_metatypecode ."\', \'". $clinkMetadataId ."\', \'". $link_linkcriteria ."\', \'". $metaDataId ."\', \'". $row['FIELD_PATH'] ."\', \'". $link_linkmetadataid ."\',  \'". $sourceParam ."\', $isnewTab, undefined,  \'". $link_dialogWidth ."\',  \'". $link_dialogHeight ."\')\">'+ dateFormatter('Y-m-d H:i:s', v) + '</a>';
                                    else
                                        return '';
                                },";
                            }
                        } else { 
                            $cellFormatter = "formatter: function(v, r, i) {return dateFormatter('Y-m-d H:i:s', v);},";
                        }

                        $filterDateTimeInit[] = "input[name=" . $row['FIELD_PATH'] . "]";
                        $bodyAlign = "align: 'center',";

                    } elseif ($row['META_TYPE_CODE'] == 'bigdecimal') {

                        if ($row['DRILLDOWN_COLUMN'] > 0) {

                            if ($row['DRILLDOWN_META_TYPE_CODE'] == 'process') {

                                if (isset($processCommandLink['functionName'])) {
                                    $cellFormatter = "formatter: function(v, r, i) {
                                        if (v) {
                                            return '<a href=\"javascript:;\" onclick=\"drillDownTransferProcessAction(\'".$processCommandLink['functionName']."\', \'". $linkMetaData['CLINK_META_DATA_ID'] ."\',  \'". $linkMetaData['CRITERIA'] ."\', \'\', \'". $processCommandLink['metaDataId'] ."\', \'". $linkMetaData['LINK_META_DATA_ID'] ."\', \'". $processCommandLink['type'] ."\', \'\', ". $processCommandLink['element'] .", {callerType: \'".$metaDataCode."\', isDrillDown: true, drillDownPath: \'".$row['FIELD_PATH']."\'})\">'+number_format(v, 2, '.', ',')+'</a>';
                                        } else {
                                            return '';
                                        }
                                    },";
                                }

                            } else {
                                $cellFormatter = "formatter: function(v, r, i) {
                                    if (v) {
                                        return '<a href=\"javascript:;\" onclick=\"gridDrillDownLink(this, \'$metaDataCode\', \'". $link_metatypecode ."\', \'". $clinkMetadataId ."\', \'". $link_linkcriteria ."\', \'". $metaDataId ."\', \'". $row['FIELD_PATH'] ."\', \'". $link_linkmetadataid ."\', \'". $sourceParam ."\', $isnewTab, undefined,  \'". $link_dialogWidth ."\',  \'". $link_dialogHeight ."\')\">'+number_format(v, ". (!is_null($row['FRACTION_RANGE']) ? $row['FRACTION_RANGE'] : '2' )  .", '.', ',')+'</a>';
                                    } else {
                                        return '';
                                    } 
                                },";
                            }

                        } elseif (!is_null($row['FRACTION_RANGE'])) {

                            $cellFormatter = "formatter: function(v, r, i) {
                                return gridScaleAmountField(v, '".$row['FRACTION_RANGE']."'); 
                            },";

                        } else {
                            $cellFormatter = 'formatter: gridAmountField,';
                        }

                        if ($gridOption['INLINEEDIT'] == 'true') {
                            $cellEditor = 'editor: {type: "textbox", options: {}},';
                        }                    
                        $filterBigDecimalInit[] = "input[name=" . $row['FIELD_PATH'] . "]";
                        $bodyAlign = "align: 'right',";

                    } elseif ($row['META_TYPE_CODE'] == 'bigdecimal_null') {

                        if ($row['DRILLDOWN_COLUMN'] > 0) {
                            if ($row['DRILLDOWN_META_TYPE_CODE'] == 'process') {
                                $cellFormatter = "formatter: function(v, r, i) {
                                    if (v) {
                                        return '<a href=\"javascript:;\" onclick=\"drillDownTransferProcessAction(\'".$processCommandLink['functionName']."\', \'". $linkMetaData['CLINK_META_DATA_ID'] ."\',  \'". $linkMetaData['CRITERIA'] ."\', \'\', \'". $processCommandLink['metaDataId'] ."\', \'". $linkMetaData['LINK_META_DATA_ID'] ."\', \'". $processCommandLink['type'] ."\', \'\', ". $processCommandLink['element'] .", {callerType: \'".$metaDataCode."\', isDrillDown: true, drillDownPath: \'".$row['FIELD_PATH']."\'})\">'+number_format(v, 2, '.', ',')+'</a>';
                                    } else {
                                        return '';
                                    }
                                },";
                            } else {
                                $cellFormatter = "formatter: function(v, r, i) {
                                    if (v) {
                                        return '<a href=\"javascript:;\" onclick=\"gridDrillDownLink(this, \'$metaDataCode\', \'". $link_metatypecode ."\', \'". $clinkMetadataId ."\', \'". $link_linkcriteria ."\', \'". $metaDataId ."\', \'". $row['FIELD_PATH'] ."\', \'". $link_linkmetadataid ."\', \'". $sourceParam ."\', $isnewTab, undefined,  \'". $link_dialogWidth ."\',  \'". $link_dialogHeight ."\')\">'+number_format(v, 2, '.', ',')+'</a>';
                                    } else {
                                        return '';
                                    } 
                                },";
                            }
                        } elseif (!is_null($row['FRACTION_RANGE'])) {

                            $cellFormatter = "formatter: function(v, r, i) {
                                return gridScaleAmountNullField(v, '".$row['FRACTION_RANGE']."'); 
                            },";

                        } else {
                            $cellFormatter = 'formatter: gridAmountNullField,';
                        }

                        $filterBigDecimalInit[] = "input[name=" . $row['FIELD_PATH'] . "]";
                        $bodyAlign = "align: 'right',";

                    } elseif ($row['META_TYPE_CODE'] == 'time') {

                        $cellFormatter = "formatter: function(v, r, i) {return dateFormatter('H:i', v);},";
                        $filterTimeInit[] = "input[name=" . $row['FIELD_PATH'] . "]";
                        $bodyAlign = "align: 'center',";

                    } elseif ($row['META_TYPE_CODE'] == 'file_tab_view') {

                        $cellFormatter = 'formatter: gridFileTabViewField,';
                        $bodyAlign = "align: 'center',";

                    } elseif ($row['FIELD_PATH'] == 'filename') {

                        $cellFormatter = "formatter: function(v, r, i) {
                            if (v) {
                                if (typeof r.physicalpath !== 'undefined') {
                                    var physicalpath = r.physicalpath;
                                    if (physicalpath !== '' && physicalpath !== null && physicalpath !== 'null') {    
                                        return dataViewFileView(v, r, '$metaDataId', '$refStructureId');
                                    } else {
                                        return '<a href=\"javascript:;\" onclick=\"gridDrillDownLink(this, \'$metaDataCode\', \'". $link_metatypecode ."\', \'". $clinkMetadataId ."\', \'". $link_linkcriteria ."\', \'". $metaDataId ."\', \'". $row['FIELD_PATH'] ."\', \'". $link_linkmetadataid ."\', \'". $sourceParam ."\', $isnewTab, undefined,  \'". $link_dialogWidth ."\',  \'". $link_dialogHeight ."\')\"><span class=\"\">'+ v +'</span></a>';
                                    }
                                } else {
                                    return '<a href=\"javascript:;\" onclick=\"gridDrillDownLink(this, \'$metaDataCode\', \'". $link_metatypecode ."\', \'". $clinkMetadataId ."\', \'". $link_linkcriteria ."\', \'". $metaDataId ."\', \'". $row['FIELD_PATH'] ."\', \'". $link_linkmetadataid ."\', \'". $sourceParam ."\', $isnewTab, undefined,  \'". $link_dialogWidth ."\',  \'". $link_dialogHeight ."\')\"><span class=\"\">'+ v +'</span></a>';
                                }
                            } else {
                                return '';
                            } 
                        },";

                    } elseif ($row['FIELD_PATH'] == 'downloadfile') {

                        $cellFormatter = "formatter: function(v, r, i) {
                            if (v) {
                                return '<a href=\"mdobject/downloadFile?fDownload=1&file=' + v.replace(URL_APP, '') + '\" target=\"_blank\"><i class=\"fa fa-download\"></i> Татах</a>';
                            } 
                            return '';
                        },";

                    } elseif ($row['FIELD_PATH'] == 'wfmstatusname') {

                        $cellFormatter = "formatter: function(v, r, i) {return dataViewWfmStatusName(v, r, i, '$metaDataId', '$refStructureId');},";

                    } elseif ($row['FIELD_PATH'] == 'pfnextstatuscolumn') {

                        $cellFormatter = "formatter: function(v, r, i) {return dataViewWfmStatusButtons(v, r, i, '$metaDataId', '$refStructureId', '$metaDataCode');},";

                    } elseif ($row['FIELD_PATH'] == 'pfnextstatuscolumnjson') {

                        $cellFormatter = "formatter: function(v, r, i) {return dvWfmStatusButtonsByJson(v, r, i, '$metaDataId', '$metaDataCode');},";

                    } elseif ($row['FIELD_PATH'] == 'base64download') {

                        $cellFormatter = "formatter: function(v, r, i) {return dataViewBase64DownloadLink(v, r, i, '$metaDataId', '$refStructureId');},";

                    } elseif ($row['META_TYPE_CODE'] == 'file' || $row['META_TYPE_CODE'] == 'file_icon') {

                        if ($renderType = issetParam($row['RENDER_TYPE'])) {                    
                            
                            $fncName = "gridFileColumnRenderType(this, '$metaDataId', '$renderType', '".$row['MAX_VALUE']."', v, r, i, c)";
                            
                            if ($row['DRILLDOWN_COLUMN'] > 0) {
                                
                                $fncName = str_replace('c)', 'c, {isIgnoreLink: true})', $fncName);
                                
                                if ($row['DRILLDOWN_META_TYPE_CODE'] == 'process') {

                                    if (isset($processCommandLink['functionName'])) {
                                        $cellFormatter = "formatter: function(v, r, i, c) { if (typeof v !== 'undefined' && v != null) return '<a href=\"javascript:;\" onclick=\"drillDownTransferProcessAction(\'".$processCommandLink['functionName']."\', \'". $linkMetaData['CLINK_META_DATA_ID'] ."\', \'". $linkMetaData['CRITERIA'] ."\', \'\', \'". $processCommandLink['metaDataId'] ."\', \'". $linkMetaData['LINK_META_DATA_ID'] ."\', \'". $processCommandLink['type'] ."\', \'\', ". $processCommandLink['element'] .", {callerType: \'".$metaDataCode."\', isDrillDown: true, drillDownPath: \'".$row['FIELD_PATH']."\'});\">'+ $fncName +'</a>';},";
                                    } 

                                } else {
                                    $cellFormatter = "formatter: function(v, r, i, c) {
                                        if (v) {
                                            return '<a href=\"javascript:;\" onclick=\"gridDrillDownLink(this, \'$metaDataCode\', \'". $link_metatypecode ."\', \'". $clinkMetadataId ."\', \'". str_replace("'", "\\\\\'", $link_linkcriteria) ."\', \'". $metaDataId ."\', \'". $row['FIELD_PATH'] ."\', \'". $link_linkmetadataid ."\', \'". $sourceParam ."\', $isnewTab, undefined, \'". $link_dialogWidth ."\', \'". $link_dialogHeight ."\');\" data-p-path=\"". $link_passPath ."\">'+ $fncName +'</a>';
                                        } else {
                                            return '';
                                        }
                                    },";
                                } 
                                
                            } else {
                                $cellFormatter = "formatter: function(v, r, i, c) {
                                    return $fncName;
                                },";
                            }
                            
                        } elseif ($row['DRILLDOWN_COLUMN'] > 0) {
                            
                            if ($row['DRILLDOWN_META_TYPE_CODE'] == 'process') {

                                if (isset($processCommandLink['functionName'])) {
                                    $cellFormatter = "formatter: function(v, r, i) {if (typeof v !== 'undefined' && v != null) return '<a href=\"javascript:;\" onclick=\"drillDownTransferProcessAction(\'".$processCommandLink['functionName']."\', \'". $linkMetaData['CLINK_META_DATA_ID'] ."\', \'". $linkMetaData['CRITERIA'] ."\', \'\', \'". $processCommandLink['metaDataId'] ."\', \'". $linkMetaData['LINK_META_DATA_ID'] ."\', \'". $processCommandLink['type'] ."\', \'\', ". $processCommandLink['element'] .", {callerType: \'".$metaDataCode."\', isDrillDown: true, drillDownPath: \'".$row['FIELD_PATH']."\'});\">'+ v + '</a>';},";
                                }
                            } else {
                                $cellFormatter = "formatter: function(v, r, i, c) {
                                        if (v) {
                                            if (typeof c !== 'undefined') {
                                                return '<a href=\"javascript:;\" onclick=\"gridDrillDownLink(this, \'$metaDataCode\', \'". $link_metatypecode ."\', \'". $clinkMetadataId ."\', \'". $link_linkcriteria ."\', \'". $metaDataId ."\', \'". $row['FIELD_PATH'] ."\', \'". $link_linkmetadataid ."\', \'". $sourceParam ."\', $isnewTab, undefined, \'". $link_dialogWidth ."\', \'". $link_dialogHeight ."\')\">'+ v + '</a>';
                                            } else {
                                                return '<a href=\"javascript:;\" onclick=\"gridDrillDownLink(this, \'$metaDataCode\', \'". $link_metatypecode ."\', \'". $clinkMetadataId ."\', \'". $link_linkcriteria ."\', \'". $metaDataId ."\', \'". $row['FIELD_PATH'] ."\', \'". $link_linkmetadataid ."\', \'". $sourceParam ."\', $isnewTab, undefined, \'". $link_dialogWidth ."\', \'". $link_dialogHeight ."\')\">".$this->lang->line('more')."</a>';
                                            }
                                        } else {
                                            return '';
                                        }
                                    },";
                            }
                        } else {
                            $cellFormatter = ($row['META_TYPE_CODE'] == 'file_icon') ? 'formatter: gridFileOnlyIconField,' : 'formatter: gridFileField,';
                        }

                        $bodyAlign = "align: 'center',";

                    } elseif ($row['META_TYPE_CODE'] == 'number') {

                        if ($row['DRILLDOWN_COLUMN'] > 0) {

                            if ($row['DRILLDOWN_META_TYPE_CODE'] == 'process') {

                                if (isset($processCommandLink['functionName'])) {
                                    $cellFormatter = "formatter: function(v, r, i) {
                                            if (v) {
                                                return '<a href=\"javascript:;\" onclick=\"drillDownTransferProcessAction(\'".$processCommandLink['functionName']."\', \'". $linkMetaData['CLINK_META_DATA_ID'] ."\', \'". $linkMetaData['CRITERIA'] ."\', \'\', \'". $processCommandLink['metaDataId'] ."\', \'". $linkMetaData['LINK_META_DATA_ID'] ."\', \'". $processCommandLink['type'] ."\', \'\', ". $processCommandLink['element'] .", {callerType: \'".$metaDataCode."\', isDrillDown: true, drillDownPath: \'".$row['FIELD_PATH']."\'})\">'+ v + '</a>';
                                            } else {
                                                return '';
                                            }
                                        },";
                                }

                            } else {
                                $cellFormatter = "formatter: function(v, r, i) {
                                    if (v) {
                                        return '<a href=\"javascript:;\" onclick=\"gridDrillDownLink(this, \'$metaDataCode\', \'". $link_metatypecode ."\', \'". $clinkMetadataId ."\', \'". $link_linkcriteria ."\', \'". $metaDataId ."\', \'". $row['FIELD_PATH'] ."\', \'". $link_linkmetadataid ."\', \'". $sourceParam ."\', $isnewTab, undefined, \'". $link_dialogWidth ."\', \'". $link_dialogHeight ."\')\">'+ v + '</a>';
                                    } else {
                                        return '';
                                    } 
                                },";
                            }
                        }

                        if ($gridOption['INLINEEDIT'] == 'true') {
                            $cellEditor = 'editor: {type: "textbox", options: {}},';
                        }                    

                        $filterNumberInit[] = "input[name=" . $row['FIELD_PATH'] . "]";
                        $bodyAlign = "align: 'right',";

                    } elseif ($row['META_TYPE_CODE'] == 'long' || $row['META_TYPE_CODE'] == 'integer') {

                        if ($row['DRILLDOWN_COLUMN'] > 0) {

                            if ($row['DRILLDOWN_META_TYPE_CODE'] == 'process') {

                                if (isset($processCommandLink['functionName'])) {
                                    $cellFormatter = "formatter: function(v, r, i) {
                                            if (v) {
                                                return '<a href=\"javascript:;\" onclick=\"drillDownTransferProcessAction(\'".$processCommandLink['functionName']."\', \'". $linkMetaData['CLINK_META_DATA_ID'] ."\', \'". $linkMetaData['CRITERIA'] ."\', \'\', \'". $processCommandLink['metaDataId'] ."\', \'". $linkMetaData['LINK_META_DATA_ID'] ."\', \'". $processCommandLink['type'] ."\', \'\', ". $processCommandLink['element'] .", {callerType: \'".$metaDataCode."\', isDrillDown: true, drillDownPath: \'".$row['FIELD_PATH']."\'})\">' + ((v != '' && v != null) ? number_format(v, 0, '.', '') : '') + '</a>';
                                            } else {
                                                return '';
                                            }
                                        },";
                                }

                            } else {
                                $cellFormatter = "formatter: function(v, r, i) {
                                    if (v) {
                                        return '<a href=\"javascript:;\" onclick=\"gridDrillDownLink(this, \'$metaDataCode\', \'". $link_metatypecode ."\', \'". $clinkMetadataId ."\', \'". $link_linkcriteria ."\', \'". $metaDataId ."\', \'". $row['FIELD_PATH'] ."\', \'". $link_linkmetadataid ."\', \'". $sourceParam ."\', $isnewTab, undefined, \'". $link_dialogWidth ."\', \'". $link_dialogHeight ."\')\">' + ((v != '' && v != null) ? number_format(v, 0, '.', '') : '') + '</a>';
                                    } else {
                                        return '';
                                    } 
                                },";
                            }

                        } else {
                            $cellFormatter = "formatter: function(v, r, i) {
                                return (v != '' && v != null) ? number_format(v, 0, '.', '') : ''; 
                            },";
                        }

                        $filterNumberInit[] = "input[name=" . $row['FIELD_PATH'] . "]";
                        $bodyAlign = "align: 'right',";

                    } elseif ($row['META_TYPE_CODE'] == 'boolean') {

                        $cellFormatter = 'formatter: gridBooleanField,';
                        $bodyAlign = "align: 'center',";
                        $headerAlign = "halign: 'center',";

                        if ($gridOption['INLINEEDIT'] == 'true') {
                            if (isset($getInlineEditMapDataGroup[$row['FIELD_PATH']])) {
                                $getMapParamName = $getInlineEditMapDataGroup[$row['FIELD_PATH']];

                                if (isset($getProcessParamsGroup[$getMapParamName])) {
                                    $cellEditor = 'editor: {type: "checkbox",options: {
                                        on: \'1\',
                                        off: \'0\'
                                    }},';                        
                                }
                            }
                        }

                    } elseif ($row['META_TYPE_CODE'] == 'password') {

                        $cellFormatter = 'formatter: gridPasswordField,';
                        $bodyAlign = "align: 'center',";
                        $headerAlign = "halign: 'center',";

                    } elseif ($row['META_TYPE_CODE'] == 'star') {

                        $cellFormatter = 'formatter: gridStarField,';
                        $bodyAlign = "align: 'center',";
                        $headerAlign = "halign: 'center',";

                    } elseif ($row['META_TYPE_CODE'] == 'decimal_to_time') {

                        $cellFormatter = 'formatter: gridNumberToTime,';

                    } elseif ($row['META_TYPE_CODE'] == 'html_decode') {

                        $cellFormatter = 'formatter: gridHtmlDecode,';

                    } elseif ($row['META_TYPE_CODE'] == 'url') {

                        $cellFormatter = "formatter: function(v, r, i) { return '<a href=\"'+ v + '\" target=\"_blank\">'+ v + '</a>'; },";

                    } else {

                        if ($gridOption['INLINEEDIT'] == 'true') {

                            if (isset($getInlineEditMapDataGroup[$row['FIELD_PATH']])) {
                                $getMapParamName = $getInlineEditMapDataGroup[$row['FIELD_PATH']];

                                if (isset($getProcessParamsGroup[$getMapParamName])) {
                                    $getProcParam = $getProcessParamsGroup[$getMapParamName];

                                    if ($getProcParam['row']['LOOKUP_TYPE'] != '' && $getProcParam['row']['LOOKUP_META_DATA_ID'] != '') {
                                        $lookupTypeLower = $getProcParam['row']['LOOKUP_TYPE'];

                                        if ($lookupTypeLower == 'combo') {
                                            $cellEditor = 'editor: {type: "combobox",options: {
                                                valueField: "META_VALUE_ID",
                                                textField: "META_VALUE_NAME",
                                                panelHeight: \'auto\',
                                                editable: false,
                                                url: "mdobject/dataViewInlineEditCombo/'.$getProcParam['row']['LOOKUP_META_DATA_ID'].'/'.$getProcParam['row']['VALUE_FIELD'].'/'.$getProcParam['row']['DISPLAY_FIELD'].'",
                                                required: '.$getProcParam['row']['IS_REQUIRED'].',
                                                onLoadSuccess: function(rows){
                                                    var getComboVal = $(this).combobox(\'getValue\').trim().toLowerCase();
                                                    for (var ri = 0; ri < rows.length; ri++) {
                                                        if (rows[ri][\'META_VALUE_NAME\'].trim().toLowerCase() == getComboVal) {
                                                            $(this).combobox(\'setValue\', rows[ri][\'META_VALUE_ID\']);
                                                        }
                                                    }
                                                    return;
                                                }                                                
                                              }},';
                                        }

                                    } else {
                                        $cellEditor = 'editor: {type: "textbox", options: {required: '.$getProcParam['row']['IS_REQUIRED'].'}},';
                                    }
                                }
                            }
                        }              

                        if ($row['DRILLDOWN_COLUMN'] > 0) {

                            if (isset($row['IGNORE_DRILL_META']) && $row['IGNORE_DRILL_META'] && $row['IGNORE_DRILL_META'] != '$@$' && Input::isEmpty('processMetaDataId') == false && strpos($row['IGNORE_DRILL_META'], Input::post('processMetaDataId')) !== false) {

                                $cellFormatter = '';

                            } else {

                                if ($row['DRILLDOWN_META_TYPE_CODE'] == 'process') {

                                    if (isset($processCommandLink['functionName'])) {
                                        $cellFormatter = "formatter: function(v, r, i) {if (typeof v !== 'undefined' && v != null) return '<a href=\"javascript:;\" onclick=\"drillDownTransferProcessAction(\'".$processCommandLink['functionName']."\', \'". $linkMetaData['CLINK_META_DATA_ID'] ."\', \'". $linkMetaData['CRITERIA'] ."\', \'\', \'". $processCommandLink['metaDataId'] ."\', \'". $linkMetaData['LINK_META_DATA_ID'] ."\', \'". $processCommandLink['type'] ."\', \'\', ". $processCommandLink['element'] .", {callerType: \'".$metaDataCode."\', isDrillDown: true, drillDownPath: \'".$row['FIELD_PATH']."\'});\">'+ gridHtmlDecode(v) +'</a>';},";
                                    } 

                                } else {
                                    $cellFormatter = "formatter: function(v, r, i) {
                                        if (v) {
                                            return '<a href=\"javascript:;\" onclick=\"gridDrillDownLink(this, \'$metaDataCode\', \'". $link_metatypecode ."\', \'". $clinkMetadataId ."\', \'". str_replace("'", "\\\\\'", $link_linkcriteria) ."\', \'". $metaDataId ."\', \'". $row['FIELD_PATH'] ."\', \'". $link_linkmetadataid ."\', \'". $sourceParam ."\', $isnewTab, undefined, \'". $link_dialogWidth ."\', \'". $link_dialogHeight ."\');\" data-p-path=\"". $link_passPath ."\">'+ v +'</a>';
                                        } else {
                                            return '';
                                        }
                                    },";
                                }
                            }
                        }
                    }

                    if ($row['TEXT_WEIGHT'] != '') {
                        $cellStyle .= 'font-weight:' . $row['TEXT_WEIGHT'] . ';';
                    }

                    if ($row['TEXT_COLOR'] != '') {
                        $cellStyle .= 'color:' . $row['TEXT_COLOR'] . ';';
                    }

                    if ($row['TEXT_TRANSFORM'] != '') {
                        $cellStyle .= 'text-transform:' . $row['TEXT_TRANSFORM'] . ';';
                    }

                    if ($row['BG_COLOR'] != '') {
                        $cellStyle .= "background-color: '+(r.hasOwnProperty('".$row['FIELD_PATH']."_bgcolor') ? r.".$row['FIELD_PATH']."_bgcolor : '".$row['BG_COLOR']."')+';";
                    }

                    if ($row['FONT_SIZE'] != '') {
                        $cellStyle .= 'font-size:' . $row['FONT_SIZE'] . ';';
                    }

                    if (!empty($cellStyle)) {
                        $cellStyle = "styler: function(v, r, i) {return '$cellStyle';},";
                    }

                    if ($row['HEADER_ALIGN'] != '') {
                        $headerAlign = "halign: '" . $row['HEADER_ALIGN'] . "',";
                    }

                    if ($row['BODY_ALIGN'] != '') {
                        $bodyAlign = "align: '" . $row['BODY_ALIGN'] . "',";

                        if ($row['BODY_ALIGN'] == 'center') {
                            $filterCenterInit[] = "input[name=" . $row['FIELD_PATH'] . "]";
                        }
                    }

                    $ticket = false;
                    
                        if (isset($row['_COLSPAN'])) {
                            $rowspan .= 'colspan:'.$row['_COLSPAN'].',';
                        }
                        if (isset($row['_ROWSPAN'])) {
                            $rowspan .= 'rowspan:'.$row['_ROWSPAN'].',';
                        }                    

                        if ($row['IS_SHOW'] == '1') {

                            if ($row['IS_MERGE'] == '1') {
                                array_push($isMergeColumn, $row['FIELD_PATH']);
                            }

                            if ($dataviewCustomerCfg['IS_SHOW'] == '1' && $dataviewCustomerCfg['IS_FREEZE'] == '1') {
                                $rowspanFreeze = '';
                                if (isset($row['_COLSPAN'])) {
                                    $rowspanFreeze .= 'colspan:'.$row['_COLSPAN'].',';
                                }
                                if (isset($row['_ROWSPAN'])) {
                                    $rowspanFreeze .= 'rowspan:'.($row['_ROWSPAN']-1).',';
                                }
                                //if ((int) $row['ORDER_NUM'] <= (int) $dataviewCustomerCfg['ORDER_NUM']) {
                                if ($row['ORDER_NUM'] <= (int) $dataviewCustomerCfg['ORDER_NUM']) {

//                                    if (isset($row['_ROWSPAN'])) {
//                                        $rowspan .= 'rowspan:'.$row['_ROWSPAN'].',';
//                                    }                                            
                                    //$freeze .= "{field:'" . $row['FIELD_PATH'] . "',title:'" . self::dataGridTitleReplacer(Lang::line($row['LABEL_NAME'])) . "',sortable:true," . $fixedColumn . $width . $cellStyle . $headerAlign . $bodyAlign . $cellFormatter . $cellEditor . "},";
                                    $freezeArr[$row['ORDER_NUM']] = "{field:'" . $row['FIELD_PATH'] . "',title:'" . self::dataGridTitleReplacer(Lang::line($row['LABEL_NAME'])) . "',sortable:true," . $fixedColumn . $width . $cellStyle . $headerAlign . $bodyAlign . $cellFormatter . $cellEditor . $rowspanFreeze . "},";

                                } else {
                                    $header .= "{field:'" . $row['FIELD_PATH'] . "',title:'" . self::dataGridTitleReplacer(Lang::line($row['LABEL_NAME'])) . "',sortable:true," . $fixedColumn . $width . $cellStyle . $headerAlign . $bodyAlign . $cellFormatter . $cellEditor . $rowspan . "},";
                                }

                            } else {
                                $header .= "{field:'" . $row['FIELD_PATH'] . "',title:'" . self::dataGridTitleReplacer(Lang::line($row['LABEL_NAME'])) . "',sortable:true," . $fixedColumn . $width . $cellStyle . $headerAlign . $bodyAlign . $cellFormatter . $cellEditor . $rowspan . "},";
                            }  

                        } else {
                            $header .= "{field:'" . $row['FIELD_PATH'] . "',title:'" . self::dataGridTitleReplacer(Lang::line($row['LABEL_NAME'])) . "', hidden: true, sortable:true," . $fixedColumn . $width . $cellStyle . $headerAlign . $bodyAlign . $cellFormatter . $cellEditor . $rowspan . "},";
                        }
                    // }
                    $num++;

                    if ($isGroupFieldUser && $groupField && $groupField == $row['FIELD_PATH']) {
                        $isExistsGroupField = true;
                    }
                }            
                
                $header .= '],';                
                ksort($freezeArr);
                if (!$mergeIndex) {
                    if ($isPopupWindow || $isBasketWindow) {
                        $freezeRow .= "{field:'action', $rowspan title:'', sortable:false, width:40, align:'center'},";
                    }     
                }
                $freezeRow .= implode("", $freezeArr);
                $freezeRow .= '],';                
            }
            
            if (Mdobject::$pfKpiTemplateDynamicColumn) {
                
                $kpiColumns = self::getKpiTemplateColumns(Mdobject::$pfKpiTemplateDynamicColumn);
                
                foreach ($kpiColumns as $kpiRow) {
                    
                    $labelName = Lang::line($kpiRow['INDICATOR_NAME']);
                    $showType = $kpiRow['SHOW_TYPE'];
                    $factWidth = $kpiRow['FACT_WIDTH'];
                    $cellStyle = '';
                    $cellFormatter = '';
                    $cellEditor = '';
                    $width = "width: '150',";
                    $fixedColumn = 'fixed: true,';
                    $headerAlign = "halign: 'center',";
                    $bodyAlign = "align: 'left',";
                    
                    if ($showType == 'decimal' || $showType == 'bigdecimal' || $showType == 'number' || $showType == 'long' || $showType == 'integer') {
                        $bodyAlign = "align: 'right',";
                    }
                    
                    if ($showType == 'decimal' || $showType == 'bigdecimal') {
                        $cellFormatter = 'formatter: gridAmountNullField,';
                    }
                    
                    if ($factWidth != '') {
                        $width = "width: '$factWidth',";
                    }
                
                    $header .= "{field:'".$kpiRow['INDICATOR_ID'].'_kpi_'.$kpiRow['PARAM_PATH']."',title:'" . self::dataGridTitleReplacer($labelName) . "',sortable:true," . $fixedColumn . $width . $cellStyle . $headerAlign . $bodyAlign . $cellFormatter . $cellEditor . $rowspan . "},";
                }
            }
            $header = rtrim($header, ',');
            $header .= ']';            
            
            $freezeRow = str_replace('♥♥♥', '', $freezeRow);
            $freezeRow = str_replace(',[]', '', $freezeRow);
            $array = array_merge($array, array(
                    'header' => $header,
                    'freeze' => '[' . $freezeRow . ']',
//                    'freeze' => "[[{
//                            field: 'ck',
//                            rowspan: 2,
//                            checkbox: true,
//                        }, {
//                            field: 'code',
//                            title: 'Код',
//                            sortable: true,
//                            fixed: true,
//                            width: '150',
//                            halign: 'center',
//                            align: 'left',
//                            rowspan: 2,
//                        }, {
//                            title: 'AAA',
//                            colspan: 1
//                        }],[{
//                            field: 'name',
//                            title: 'Нэр ddd',
//                            sortable: true,
//                            fixed: true,
//                            width: '150',
//                            halign: 'center',
//                            align: 'left',
//                        }]]",
                    'isMergeColumn' => $isMergeColumn
                )
            );
            
            if (count($filterCenterInit) > 0) {
                $array = array_merge($array, array(
                    'filterCenterInit' => '$panelView.find(".datagrid-htable").find(".datagrid-filter-row").find("' . implode(",", $filterCenterInit) . '").addClass("text-center");'
                    )
                );
            }

            if (count($filterDateInit) > 0) {
                $array = array_merge($array, array(
                    'filterDateInit' => '$panelView.find(".datagrid-htable").find(".datagrid-filter-row").find("' . implode(",", $filterDateInit) . '").addClass("dateMaskInit text-center");'
                    )
                );
            }

            if (count($filterTimeInit) > 0) {
                $array = array_merge($array, array(
                    'filterTimeInit' => '$panelView.find(".datagrid-htable").find(".datagrid-filter-row").find("' . implode(",", $filterTimeInit) . '").addClass("timeMaskInit text-center");'
                    )
                );
            }

            if (count($filterDateTimeInit) > 0) {
                $array = array_merge($array, array(
                    'filterDateTimeInit' => '$panelView.find(".datagrid-htable").find(".datagrid-filter-row").find("' . implode(",", $filterDateTimeInit) . '").addClass("dateMinuteMaskInit");'
                    )
                );
            }

            if (count($filterBigDecimalInit) > 0) {
                $array = array_merge($array, array(
                    'filterBigDecimalInit' => '$panelView.find(".datagrid-htable").find(".datagrid-filter-row").find("' . implode(",", $filterBigDecimalInit) . '").addClass("bigdecimalInit");'
                    )
                );
            }

            if (count($filterNumberInit) > 0) {
                $array = array_merge($array, array(
                    'filterNumberInit' => '$panelView.find(".datagrid-htable").find(".datagrid-filter-row").find("' . implode(",", $filterNumberInit) . '").addClass("longInit");'
                    )
                );
            }
            
            if ($isGroupFieldUser) {
                
                if (!isset($isExistsGroupField) && issetParam($gridOption['GROUPFIELDLABELNAME'])) {
                    
                    $groupFieldRow = array(array(
                        'LABEL_NAME' => $gridOption['GROUPFIELDLABELNAME'], 
                        'FIELD_PATH' => $groupField
                    ));
                    
                    $gridData = array_merge($groupFieldRow, $gridData);
                }
                
                Mdobject::$onlyShowColumns = $gridData;
            }
        }

        return $array;
    }
    
    public function dataGridTitleReplacer($title) {
        
        if ($title == 'star_5/5') {
            return '<ul class="nav navbar-nav star-rating not-click"><li><i class="icon-star-full2" style="color: orange;"></i></li><li><i class="icon-star-full2" style="color: orange;"></i></li><li><i class="icon-star-full2" style="color: orange;"></i></li><li><i class="icon-star-full2" style="color: orange;"></i></li><li><i class="icon-star-full2" style="color: orange;"></i></li></ul>';
        } elseif ($title == 'star_4/5') {
            return '<ul class="nav navbar-nav star-rating not-click"><li><i class="icon-star-full2" style="color: orange;"></i></li><li><i class="icon-star-full2" style="color: orange;"></i></li><li><i class="icon-star-full2" style="color: orange;"></i></li><li><i class="icon-star-full2" style="color: orange;"></i></li><li><i class="icon-star-empty3" style="color: #ccc;"></i></li></ul>';
        } elseif ($title == 'star_3/5') {
            return '<ul class="nav navbar-nav star-rating not-click"><li><i class="icon-star-full2" style="color: orange;"></i></li><li><i class="icon-star-full2" style="color: orange;"></i></li><li><i class="icon-star-full2" style="color: orange;"></i></li><li><i class="icon-star-empty3" style="color: #ccc;"></i></li><li><i class="icon-star-empty3" style="color: #ccc;"></i></li></ul>';
        } elseif ($title == 'star_2/5') {
            return '<ul class="nav navbar-nav star-rating not-click"><li><i class="icon-star-full2" style="color: orange;"></i></li><li><i class="icon-star-full2" style="color: orange;"></i></li><li><i class="icon-star-empty3" style="color: #ccc;"></i></li><li><i class="icon-star-empty3" style="color: #ccc;"></i></li><li><i class="icon-star-empty3" style="color: #ccc;"></i></li></ul>';
        } elseif ($title == 'star_1/5') {
            return '<ul class="nav navbar-nav star-rating not-click"><li><i class="icon-star-full2" style="color: orange;"></i></li><li><i class="icon-star-empty3" style="color: #ccc;"></i></li><li><i class="icon-star-empty3" style="color: #ccc;"></i></li><li><i class="icon-star-empty3" style="color: #ccc;"></i></li><li><i class="icon-star-empty3" style="color: #ccc;"></i></li></ul>';
        } elseif ($title == 'star_0/5') {
            return '<ul class="nav navbar-nav star-rating not-click"><li><i class="icon-star-empty3" style="color: #ccc;"></i></li><li><i class="icon-star-empty3" style="color: #ccc;"></i></li><li><i class="icon-star-empty3" style="color: #ccc;"></i></li><li><i class="icon-star-empty3" style="color: #ccc;"></i></li><li><i class="icon-star-empty3" style="color: #ccc;"></i></li></ul>';
        }
        
        return str_replace("'", "\'", $title);
    }
    
    public function getKpiTemplateColumns($templateId) {
        
        $data = $this->db->GetAll("
            SELECT 
                DF.TEMPLATE_DTL_ID, 
                DF.TEMPLATE_FACT_ID, 
                DF.SHOW_TYPE, 
                DF.LOOKUP_META_DATA_ID, 
                TF.PARAM_PATH, 
                KI.ID || '_kpi_' || TF.PARAM_PATH AS FIELD_PATH, 
                KI.NAME AS LABEL_NAME, 
                TF.LABEL_NAME AS FACT_LABEL_NAME, 
                DF.FACT_WIDTH, 
                KI.ID AS INDICATOR_ID, 
                KI.NAME AS INDICATOR_NAME, 
                null AS SIDEBAR_NAME, 
                DF.SHOW_TYPE AS META_TYPE_CODE, 
                null AS EXCEL_COLUMN_WIDTH, 
                null AS EXCEL_ROTATE 
            FROM KPI_TEMPLATE_DTL_FACT DF 
                INNER JOIN KPI_TEMPLATE_DTL TD ON TD.ID = DF.TEMPLATE_DTL_ID 
                INNER JOIN KPI_TEMPLATE_FACT TF ON TF.ID = DF.TEMPLATE_FACT_ID 
                INNER JOIN KPI_INDICATOR KI ON KI.ID = TD.INDICATOR_ID
            WHERE TD.TEMPLATE_ID = ".$this->db->Param(0)." 
            ORDER BY TD.ORDER_NUM ASC", array($templateId));
        
        return $data;
    }
    
    public function getKpiTemplateColumnsPushData($columns, $ids) {
        
        $arr = $lookupData = $lookupIds = array();
        $data = $this->db->GetAll("SELECT * FROM KPI_DM_DTL WHERE BOOK_ID IN ($ids)");
        
        foreach ($data as $row) {
            
            $indicatorId = $row['INDICATOR_ID'];
            $bookId = $row['BOOK_ID'];
            
            foreach ($columns as $column) {
                
                if ($indicatorId == $column['INDICATOR_ID']) {
                    
                    $showType         = $column['SHOW_TYPE'];
                    $lookupMetaDataId = $column['LOOKUP_META_DATA_ID'];
                    $paramPath        = $column['PARAM_PATH'];
                    $factValue        = issetParam($row[strtoupper($paramPath)]);
                    $keyPath          = $indicatorId.'_kpi_'.$paramPath;
                    
                    if (($showType == 'combo' || $showType == 'multicombo') && $lookupMetaDataId && $factValue != '') {
                        
                        $factValueIds = '';
                        $factValueArr = array_unique(explode(',', $factValue));
                        
                        foreach ($factValueArr as $factValueId) {
                            $factValueIds .= "[lookupId_$lookupMetaDataId][rowId_$factValueId]";
                        }
                        
                        $lookupData[$bookId][$keyPath] = $factValueIds;
                        $lookupIds[$lookupMetaDataId][] = implode(',', $factValueArr);
                        
                    } else {
                        $arr[$bookId][$keyPath] = $factValue;
                    }
                    
                    break;
                }
            }
        }
        
        if ($lookupIds) {
            
            foreach ($lookupIds as $lookupId => $ids) {
                
                $lookupAttributes = self::getDataViewMetaValueAttributes(null, null, $lookupId);
                $lookupIdField = strtolower($lookupAttributes['id']);
                
                $subParam = array(
                    'systemMetaGroupId' => $lookupId,
                    'showQuery'         => 0, 
                    'isShowAggregate'   => 0, 
                    'ignorePermission'  => 1, 
                    'criteria' => array(
                        $lookupIdField => array(
                            array(
                                'operator' => 'IN', 
                                'operand' => implode(',', array_unique(explode(',', Arr::implode_r(',', $ids, true))))
                            )
                        )
                    )
                );

                $subData = $this->ws->runArrayResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $subParam);
                
                if (isset($subData['result'][0])) {
                    
                    unset($subData['result']['paging']);
                    unset($subData['result']['aggregatecolumns']);
                    
                    $lookupDataJson = json_encode($lookupData, JSON_UNESCAPED_UNICODE);
                    $lookupNameField = strtolower($lookupAttributes['name']);
                    $rows = $subData['result'];
                    
                    foreach ($rows as $row) {
                        
                        $idRow = $row[$lookupIdField];
                        $nameRow = $row[$lookupNameField];
                        
                        $lookupDataJson = str_replace("[lookupId_$lookupId][rowId_$idRow]", "$nameRow, ", $lookupDataJson);
                    }
                }
            }
            
            if ($lookupDataJson) {
                
                $lookupDataJson = str_replace(', "', '"', $lookupDataJson);
                $lookupDataArr = json_decode($lookupDataJson, true);
                
                foreach ($lookupDataArr as $bookId => $lookupDataRow) {
                    
                    foreach ($lookupDataRow as $lookupDataKey => $lookupDataVal) {
                        $arr[$bookId][$lookupDataKey] = $lookupDataVal;
                    }
                }
            }
        }
        
        return $arr;
    }
    
    public function getDataViewGridCriteriaRowModel($metaDataId, $fieldPath) {
        
        $cache = phpFastCache();

        $data = $cache->get('dvPath_'.$metaDataId);
        $lowerFieldPath = strtolower($fieldPath);

        if ($data == null) {
            
            $rows = $this->db->GetAll("
                SELECT 
                    LOWER(FIELD_PATH) AS FIELD_PATH, 
                    LOWER(DATA_TYPE) AS META_TYPE_CODE, 
                    LOWER(DEFAULT_OPERATOR) AS DEFAULT_OPERATOR, 
                    " . $this->db->IfNull('LABEL_NAME', 'FIELD_PATH') . " AS LABEL_NAME, 
                    LOWER(DEFAULT_VALUE) AS DEFAULT_VALUE, 
                    ICON_NAME, 
                    COLUMN_WIDTH 
                FROM META_GROUP_CONFIG 
                WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                ORDER BY DISPLAY_ORDER ASC", array($metaDataId)); 
            
            $data = array();
            
            if ($rows) {
                foreach ($rows as $row) {
                    $data[$row['FIELD_PATH']] = array(
                        'META_TYPE_CODE'   => $row['META_TYPE_CODE'], 
                        'DEFAULT_OPERATOR' => $row['DEFAULT_OPERATOR'], 
                        'LABEL_NAME'       => $row['LABEL_NAME'], 
                        'DEFAULT_VALUE'    => $row['DEFAULT_VALUE'], 
                        'ICON_NAME'        => $row['ICON_NAME'], 
                        'COLUMN_WIDTH'     => $row['COLUMN_WIDTH']
                    );
                }
            }
            
            $cache->set('dvPath_'.$metaDataId, $data, Mdwebservice::$expressionCacheTime);
        }
        
        if ($lowerFieldPath == 'pf_all_field') {
            return $data;
        }
        
        if (isset($data[$lowerFieldPath])) {
            return $data[$lowerFieldPath];
        }

        return array('META_TYPE_CODE' => 'string', 'DEFAULT_OPERATOR' => '');
    }

    public function dataViewDataGridModel($pagination = true, $metaDataId = null, $metaCriteria = array()) {
        
        if (Input::postCheck('ignoreFirstLoad') && Input::post('ignoreFirstLoad') == 'true') {
            return array('status' => 'ignoreFirstLoad', 'rows' => array(), 'total' => 0);
        }
        
        $result = array();

        $page = Input::numeric('page', 1);
        $rows = Input::numeric('rows', 500);

        $metaDataId = ($metaDataId) ? $metaDataId : Input::numeric('metaDataId');
        
        $workSpaceId = Input::numeric('workSpaceId');
        $workSpaceParams = (Input::postCheck('workSpaceParams')) ? Input::post('workSpaceParams') : '';
        $gridOption = self::getDVGridOptionsModel($metaDataId);

        if ($gridOption['PAGINATION'] == 'false') {
            $pagination = false;
        }

        $param = array(
            'systemMetaGroupId' => $metaDataId,
            'showQuery' => 1,
            'paging' => array(
                'offset' => $page,
                'pageSize' => $rows
            )
        );
        
        if (Mddatamodel::$ignorePermission || Input::postCheck('subUniqId') || Input::numeric('ignorePermission') == 1) {
            
            $param['ignorePermission'] = 1;
            
            if (Input::isEmpty('srcDataViewId') == false) {
                $isSubgrid = true;
                $gridOption = self::getDVGridOptionsModel(Input::numeric('srcDataViewId'));
            }
            
        } elseif (Config::getFromCache('isIgnorePermission')) {
            $param['ignorePermission'] = 1;
        }
        
        /*if (Input::numeric('isClickFilter') == 1) {
            $param['__isUseReport'] = 1;
        }*/
        
        if (Input::isEmpty('subQueryId') == false) {
            $param['subQueryId'] = Input::numeric('subQueryId');
        }
        
        if (Input::isEmpty('filterColumn') == false) {
            $param['filterColumn'] = Input::post('filterColumn');
        }
            
        if ($metaCriteria) {
            
            $param['criteria'] = $metaCriteria;    
            
        } else {

            if (Input::isEmpty('sort') == false && Input::isEmpty('order') == false) {
                
                $sortField = Input::post('sort');
                $sortOrder = Input::post('order');

                if (strpos($sortField, ',') === false) {
                    
                    if ($sortOrder != 'asc' && $sortOrder != 'desc') {
                        $sortOrder = 'asc';
                    }
                    
                    $param['paging']['sortColumnNames'] = array(
                        $sortField => array(
                            'sortType' => $sortOrder
                        )
                    );
                    
                } else {
                    
                    $sortFieldArr = explode(',', $sortField);
                    $sortOrderArr = explode(',', $sortOrder);
                    
                    foreach ($sortFieldArr as $sortK => $sortF) {
                        
                        if ($sortOrderArr[$sortK] != 'asc' && $sortOrderArr[$sortK] != 'desc') {
                            $sortOrderArr[$sortK] = 'asc';
                        }
                    
                        $sortColumnNames[$sortF] = array('sortType' => $sortOrderArr[$sortK]);
                    }
                    
                    $param['paging']['sortColumnNames'] = $sortColumnNames;
                }
            }

            if (Input::postCheck('sortFields')) {
                
                parse_str(Input::post('sortFields'), $sortFields);
                
                if (count($sortFields) > 0) {
                    
                    foreach ($sortFields as $sortKey => $sortType) {
                        
                        if ($sortKey != '') {
                            if ($sortType != 'asc' && $sortType != 'desc') {
                                $sortType = 'asc';
                            }

                            $param['paging']['sortColumnNames'] = array(
                                $sortKey => array(
                                    'sortType' => $sortType
                                )
                            );
                        }
                    }
                }
            }
            
            $isParentChildResolve = false;

            if (Input::postCheck('filterRules')) {
                $filterRules = json_decode(Str::cp1251_utf8($_POST['filterRules']), true);

                if (is_countable($filterRules) && count($filterRules) > 0) {

                    $paramFilter = array();

                    foreach ($filterRules as $rule) {

                        $field = $rule['field'];
                        $value = Input::param(trim($rule['value']));

                        $getTypeCode = self::getDataViewGridCriteriaRowModel($metaDataId, $field);
                        $getTypeCodeLower = (isset($getTypeCode['META_TYPE_CODE']) ? $getTypeCode['META_TYPE_CODE'] : '');

                        if ($getTypeCodeLower == 'date' || $getTypeCodeLower == 'datetime') {

                            $value = str_replace(
                                array('____-__-__', '___-__-__', '__-__-__', '_-__-__', '-__-__', '-__', '_', '__:__', ':__'), '', $value
                            );
                            $value = trim(rtrim($value, ':'));

                        } elseif ($getTypeCodeLower == 'bigdecimal') {

                            $value = str_replace('.00', '', Number::decimal($value));

                        } elseif ($field == 'accountcode') {

                            $value = trim(str_replace('_', '', str_replace('_-_', '', $value)));
                        }

                        $paramFilter[$field][] = array(
                            'operator' => 'LIKE',
                            'operand' => Str::filterLikePos($value, '*', 'b')
                        );
                    }

                    if (isset($param['criteria'])) {
                        $param['criteria'] = array_merge($param['criteria'], $paramFilter);
                    } else {
                        $param['criteria'] = $paramFilter;
                    }
                    
                    $isParentChildResolve = true; 
                    $isColumnFilter = true;
                }
            }

            if ((Input::postCheck('columnFilterData') || Input::postCheck('searchData')) && Input::post('searchDataLinkedPopup') !== 'OK') {
                
                if (Input::postCheck('columnFilterData')) {
                    parse_str(Input::post('columnFilterData'), $columnFilterData);
                } else {
                    parse_str(Input::post('searchData'), $columnFilterData);
                }

                if (is_countable($columnFilterData) && count($columnFilterData) > 0) {
                    $paramFilter = array();

                    foreach ($columnFilterData as $pk => $pv) {

                        $value = Input::param(trim($pv));

                        if ($value != '' && substr($pk, 0, 9) != 'TYPE_CODE') {

                            $getTypeCode = self::getDataViewGridCriteriaRowModel($metaDataId, $pk);
                            $getTypeCodeLower = $getTypeCode['META_TYPE_CODE'];

                            if ($getTypeCodeLower == 'date' || $getTypeCodeLower == 'datetime') {

                                $value = str_replace(
                                    array('____-__-__', '___-__-__', '__-__-__', '_-__-__', '-__-__', '-__', '_', '__:__', ':__'), '', $value
                                );

                            } elseif ($getTypeCodeLower == 'bigdecimal') {

                                $value = str_replace('.00', '', Number::decimal($value));
                            }

                            $paramFilter[$pk][] = array(
                                'operator' => 'LIKE',
                                'operand' => '%' . $value . '%'
                            );
                        }
                    }

                    if (isset($param['criteria'])) {
                        $param['criteria'] = array_merge($param['criteria'], $paramFilter);
                    } else {
                        $param['criteria'] = $paramFilter;
                    }
                }
            }

            if (Input::postCheck('cardFilterData') || Input::postCheck('searchDataLinkedPopup')) {

                parse_str(Input::post('cardFilterData'), $cardFilterData);

                if (Input::post('searchDataLinkedPopup') == 'OK' && count($cardFilterData) == 0) {
                    
                    $getInputProcess = self::getBPInputMetaId(Input::post('processMetaDataId'));
                    $paramCriteriaPopup = array();
                    
                    parse_str(Input::post('searchData'), $popupFilterData);
                    
                    if ($getInputProcess) {
                        
                        if (isset($getInputProcess['PROCESS_META_DATA_ID'])) {
                            $data = self::getGroupParamConfigByProcess($getInputProcess['PROCESS_META_DATA_ID'], Input::post('paramRealPath'));
                        } else {
                            $data = self::getGroupParamConfigByDataView($getInputProcess['GROUP_META_DATA_ID'], Input::post('paramRealPath'));
                        }

                        if ($data) {

                            foreach ($data as $row) {
                                
                                if (isset($param['criteria'][strtolower($row['FIELD_NAME'])])) {
                                    continue;
                                }
                                
                                $criteriaValue = Mdmetadata::setDefaultValue($row['DEFAULT_VALUE']);
                                
                                if (isset($popupFilterData[$row['FIELD_NAME']])) {
                                    $criteriaValue = $popupFilterData[$row['FIELD_NAME']];
                                }
                                
                                if (is_array($criteriaValue)) {
                                    
                                    $paramCriteriaPopup[$row['FIELD_NAME']][] = array(
                                        'operator' => 'IN', 
                                        'operand' => Arr::implode_r(',', $criteriaValue, true)
                                    );
                                    
                                } elseif ($criteriaValue == 'nullval' || $criteriaValue == 'nulval') {
                                    
                                    $paramCriteriaPopup[$row['FIELD_NAME']][] = array(
                                        'operator' => '=', 
                                        'operand' => ''
                                    );
                                    
                                } elseif ($criteriaValue != '') {
                                    
                                    if ($criteriaValue != 'null') {
                                        $paramCriteriaPopup[$row['FIELD_NAME']][] = array(
                                            'operator' => '=',
                                            'operand' => $criteriaValue
                                        );
                                    }
                                }
                            }         
                            
                            if ($paramCriteriaPopup) {
                                $paramCriteriaPopup = Arr::changeKeyLower($paramCriteriaPopup);
                                
                                if (isset($param['criteria'])) {
                                    $param['criteria'] = array_merge($param['criteria'], $paramCriteriaPopup);
                                } else {
                                    $param['criteria'] = $paramCriteriaPopup;
                                }
                            }
                            
                        } elseif ($popupFilterData) {
                            
                            foreach ($popupFilterData as $k => $v) {
                                
                                if (isset($param['criteria'][strtolower($k)])) {
                                    continue;
                                }
                                        
                                if (is_array($v)) {
                                    
                                    $paramCriteriaPopup[$k][] = array(
                                        'operator' => 'IN', 
                                        'operand' => Arr::implode_r(',', $v, true)
                                    );
                                    
                                } elseif ($v != 'null') {
                                    
                                    if (strpos($v, '^') === false) {
                                        
                                        $paramCriteriaPopup[$k][] = ($v != '' && $v !== '') ?
                                            array(
                                                'operator' => '=',
                                                'operand' => $v
                                            ) :
                                            array(
                                                'operator' => 'IS NULL',
                                                'operand' => ''
                                            );
                                        
                                    } else {
                                        $paramCriteriaPopup[$k][] = array(
                                            'operator' => 'IN', 
                                            'operand' => Arr::implode_r(',', explode('^', $v), true)
                                        );
                                    }
                                }
                            }
                            
                            if ($paramCriteriaPopup) {
                                if (isset($param['criteria'])) {
                                    $param['criteria'] = array_merge($param['criteria'], $paramCriteriaPopup);
                                } else {
                                    $param['criteria'] = $paramCriteriaPopup;
                                }
                            }
                        }
                        
                    } elseif (count($popupFilterData)) {
                        
                        foreach ($popupFilterData as $k => $v) {
                            
                            if ($v != '') {
                                
                                if (isset($param['criteria'][strtolower($k)])) {
                                    continue;
                                }
                                
                                if (is_array($v)) {
                                    
                                    $paramCriteriaPopup[$k][] = array(
                                        'operator' => 'IN',
                                        'operand' => Arr::implode_r(',', $v, true)
                                    );
                                    
                                } else {
                                    
                                    $paramCriteriaPopup[$k][] = array(
                                        'operator' => '=',
                                        'operand' => $v
                                    );
                                }
                            }
                        }
                        
                        if ($paramCriteriaPopup) {
                            if (isset($param['criteria'])) {
                                $param['criteria'] = array_merge($param['criteria'], $paramCriteriaPopup);
                            } else {
                                $param['criteria'] = $paramCriteriaPopup;
                            }
                        }
                    }

                } elseif (count($cardFilterData) > 0) {
                    $paramCriteria = array();
                    
                    foreach ($cardFilterData as $key => $val) {
                        
                        $cardFilterVal = ($val != '' && $val !== '') ?
                            array( 
                                'operator' => '=',
                                'operand' => $val
                            ) : 
                            array(
                                'operator' => 'IS NULL',
                                'operand' => ''
                            );
                        
                        if (isset($_POST['ignoreRecursive'][$key])) {
                            $cardFilterVal['ignoreRecursive'] = 1;
                            $cardFilterKeyTmp = $key;
                        }
                        
                        $paramCriteria[$key][] = $cardFilterVal;
                    }
                    
                    if (isset($param['criteria'])) {
                        $param['criteria'] = array_merge($param['criteria'], $paramCriteria);
                    } else {
                        $param['criteria'] = $paramCriteria;
                    }
                }
                
                $isParentChildResolve = true;
            }
            
            if (Input::postCheck('glAccountCriteriaData')) {
                $glCriteria = Input::post('glAccountCriteriaData');
                $glParamCriteria = array();
                $glParamRawCriteriaData = self::convertGlCriteriaValue($glCriteria);

                foreach ($glParamRawCriteriaData as $glparam) {
                    $glParamCriteria[$glparam['criteriaValue']][] = array(
                        'operator' => $glparam['criteriaOperator'],
                        'operand' => $glparam['criteriaOperand']
                    );
                }
                if (count($glParamCriteria) > 0) {
                    if (isset($param['criteria'])) {
                        $param['criteria'] = array_merge($param['criteria'], $glParamCriteria);
                    } else {
                        $param['criteria'] = $glParamCriteria;
                    }
                } 
            }

            if (Input::postCheck('processMetaDataId') && Input::postCheck('paramRealPath')) {
                $processMetaDataId = Input::post('processMetaDataId');
                $paramRealPath = Input::post('paramRealPath');

                if ($processMetaDataId != '' && $paramRealPath != '') {
                    $paramDefaultCriteria = array();
                    $defaultData = Mdwebservice::getParamDefaultValues($processMetaDataId, $paramRealPath, $metaDataId);

                    foreach ($defaultData as $dVal) {
                        $paramDefaultCriteria['id'][] = array(
                            'operator' => '=',
                            'operand' => $dVal['VALUE_ID']
                        );
                    }

                    if (isset($param['criteria'])) {
                        $param['criteria'] = array_merge($param['criteria'], $paramDefaultCriteria);
                    } else {
                        $param['criteria'] = $paramDefaultCriteria;
                    }
                }
            }

            $paramFilter = array();

            if (Input::postCheck('drillDownDefaultCriteria') && !empty($_POST['drillDownDefaultCriteria'])) {
                
                $drillDown = @json_decode(str_replace("&quot;", "\"", $_POST['drillDownDefaultCriteria']), true);
                
                if (is_array($drillDown)) {
                    
                    if (isset($param['criteria'])) {
                        $criteriaLowerCase = Arr::changeKeyLower($param['criteria']);
                    }

                    foreach ($drillDown as $drillKey => $drillValue) {
                        $uriOperator = ($drillKey == 'dtlstatusid') ? 'like' : '=';

                        if (!isset($criteriaLowerCase) || (isset($criteriaLowerCase) && !isset($criteriaLowerCase[$drillKey]))) {
                            if (!is_array($drillValue)) {
                                if (strrpos($drillValue, ',')) {
                                    $uriV = explode(',', $drillValue);

                                    foreach ($uriV as $uriK) {
                                        $uriOperand = ($drillKey == 'dtlstatusid') ? '%'.Input::param($uriK).'%' : Input::param($uriK);
                                        $paramFilter[$drillKey][] = array(
                                            'operator' => $uriOperator,
                                            'operand' => $uriOperand
                                        );
                                    }

                                } else {
                                    $uriOperand = ($uriOperator == 'like') ? '%'.$drillValue.'%' : $drillValue;
                                    $paramFilter[$drillKey][] = array('operator' => $uriOperator, 'operand' => $uriOperand);
                                }

                            } else {
                                $paramFilter[$drillKey] = $drillValue;
                            }
                        }
                    }

                    if ($paramFilter) {
                        if (isset($param['criteria'])) {
                            $param['criteria'] = array_merge($param['criteria'], $paramFilter);
                        } else {
                            $param['criteria'] = $paramFilter;
                        }   

                        $isParentChildResolve = true;
                    }
                }
            }

            if (Input::postCheck('dvDefaultCriteria') && !empty($_POST['dvDefaultCriteria'])) {
                
                $criteria = @json_decode(Str::cp1251_utf8(html_entity_decode($_POST['dvDefaultCriteria'], ENT_QUOTES, 'UTF-8')), true);
                
                if (is_array($criteria)) {
                
                    foreach ($criteria as $key => $value) {
                        $paramFilter[$key][] = array('operator' => '=', 'operand' => $value);
                    }

                    if ($paramFilter) {
                        if (isset($param['criteria'])) {
                            $param['criteria'] = array_merge($param['criteria'], $paramFilter);
                        } else {
                            $param['criteria'] = $paramFilter;
                        }   
                    }
                }
            }
            
            if (Input::isEmpty('uriParams') == false) {

                $uriParamsArray = @json_decode(str_replace("&quot;", "\"", Input::post('uriParams')), true);
                $paramUriParamsCriteria = array();
                
                if (!$uriParamsArray) {
                    parse_str(urldecode(Input::post('uriParams')), $uriParamsArray);
                }

                if ($uriParamsArray) {
                    foreach ($uriParamsArray as $uriKey => $uriVal) {
                        $uriKey = Str::lower($uriKey);
                        
                        if (is_array($uriVal)) {
                            
                            $paramUriParamsCriteria[$uriKey][] = array(
                                'operator' => 'IN',
                                'operand' => Arr::implode_r(',', $uriVal, true)
                            );

                        } elseif ($uriVal != '') {
                            $paramUriParamsCriteria[$uriKey][] = array(
                                'operator' => '=',
                                'operand' => Input::param($uriVal)
                            );
                        }
                    }
                }
                
                if (isset($param['criteria'])) {
                    $param['criteria'] = array_merge($param['criteria'], $paramUriParamsCriteria);
                } else {
                    $param['criteria'] = $paramUriParamsCriteria;
                }
            }
            
            if (Input::postCheck('defaultCriteriaData')) {

                parse_str(Input::post('defaultCriteriaData'), $defaultCriteriaData);
                
                if (isset($defaultCriteriaData['param'])) {
                    
                    $defaultCriteriaParam = $defaultCriteriaData['param'];

                    if (isset($defaultCriteriaData['criteriaCondition'])) {
                        $defaultCriteriaCondition = $defaultCriteriaData['criteriaCondition'];
                        $defaultCondition = '1';
                    } else {
                        $defaultCriteriaCondition = 'LIKE';
                        $defaultCondition = '0';
                    }
                    
                    $paramDefaultCriteria = array();

                    foreach ($defaultCriteriaParam as $defParam => $defParamVal) {
                        
                        $fieldLower = strtolower($defParam);
                        $operator = ($defaultCondition == '0') ? $defaultCriteriaCondition : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : 'like');
                        
                        if (is_array($defParamVal)) {

                            if ($operator == '!=' || $operator == '=') {

                                $defParamVals = Arr::implode_r(',', $defParamVal, true);

                                if ($defParamVals != '') {
                                    $paramDefaultCriteria[$fieldLower][] = array(
                                        'operator' => ($operator == '!=' ? 'NOT IN' : 'IN'),
                                        'operand' => $defParamVals
                                    );
                                }
                                
                            } else {
                                
                                foreach ($defParamVal as $paramVal) {
                                    if ($paramVal == '@empty@') {
                                        $paramDefaultCriteria[$fieldLower][] = array(
                                            'operator' => 'IS NULL',
                                            'operand' => ''
                                        );                                
                                    } elseif ($paramVal != '') {
                                        $paramDefaultCriteria[$fieldLower][] = array(
                                            'operator' => $operator,
                                            'operand' => $paramVal
                                        );
                                    }
                                }
                            }

                        } else {

                            $defParamVal = Input::param(trim($defParamVal));
                            $defParamVal = Mdmetadata::setDefaultValue($defParamVal);
                            $mandatoryCriteria = isset($defaultCriteriaData['mandatoryCriteria'][$defParam]) ? '1' : '0';
                            
                            if ($defParamVal == '@empty@') {
                                $paramDefaultCriteria[$fieldLower][] = array(
                                    'operator' => 'IS NULL',
                                    'operand' => ''
                                );                                
                            } elseif ($defParamVal != '' || $mandatoryCriteria == '1') {

                                $getTypeCode = self::getDataViewGridCriteriaRowModel($metaDataId, $defParam);
                                $getTypeCodeLower = $getTypeCode['META_TYPE_CODE'];
                                $defaultOperator = issetParam($getTypeCode['DEFAULT_OPERATOR']);
                                $defaultValue = issetParam($getTypeCode['DEFAULT_VALUE']);
                                
                                if ($defaultCondition == '0' && $defaultOperator == 'userlike') {
                                    
                                    if (strpos($defParamVal, '%') === false) {
                                        $operator = '=';
                                    } else {
                                        $operator = 'like';
                                    }
                                    
                                    $defParamValue = $defParamVal;
                                    
                                } else {
                                    $defParamValue = (strtolower($operator) == 'like') ? '%'.$defParamVal.'%' : $defParamVal; 
                                }
                                
                                if ($getTypeCodeLower == 'date' || $getTypeCodeLower == 'datetime') {

                                    $defParamVal = str_replace(
                                        array('____-__-__', '___-__-__', '__-__-__', '_-__-__', '-__-__', '-__', '_', '__:__', ':__'), '', $defParamVal
                                    );

                                    $operator = ($defaultCondition === '0') ? '=' : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : '='); 
                                    $defParamValue = $defParamVal;

                                } elseif ($getTypeCodeLower == 'long' || $getTypeCodeLower == 'integer') {

                                    $operator = ($defaultCondition === '0') ? '=' : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : '='); 
                                    $defParamValue = $defParamVal;

                                } elseif ($getTypeCodeLower == 'bigdecimal' || $getTypeCodeLower == 'number') {

                                    $defParamVal = Number::decimal($defParamVal);

                                } elseif ($getTypeCodeLower == 'boolean') {

                                    $operator = '=';
                                    $defParamValue = $defParamVal;
                                }

                                if ($defParam == 'booktypename') {
                                    $operator = ($defaultCondition == '0') ? '!=' : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : '!='); 
                                    $defParamValue = $defParamVal;
                                }

                                if ($defParam == 'accountCode' || $defParam == 'filterAccountCode') {
                                    $defParamValue = trim(str_replace('_', '', str_replace('_-_', '', $defParamValue)));
                                }

                                if ($operator == 'start') {
                                    $operator = 'like';
                                    $defParamValue = $defParamValue.'%';
                                } elseif ($operator == 'end') {
                                    $operator = 'like';
                                    $defParamValue = '%'.$defParamValue;
                                }
                                
                                if ($defaultValue == 'filteroptionalval' && $defParamValue == '') {
                                    continue;
                                }

                                if ($defParamValue != 'null') {
                                    $paramDefaultCriteria[$fieldLower][] = array(
                                        'operator' => $operator,
                                        'operand' => ($defParamValue) ? $defParamValue : '0'
                                    );
                                }
                            }
                        }   
                    }

                    if (isset($param['criteria'])) {
                        if (isset($cardFilterKeyTmp) && isset($param['criteria'][$cardFilterKeyTmp]) && count($param['criteria']) > 1) {
                            unset($param['criteria'][$cardFilterKeyTmp]);
                        }
                        $param['criteria'] = array_merge($param['criteria'], $paramDefaultCriteria);
                    } else {
                        $param['criteria'] = $paramDefaultCriteria;
                    }
                    
                    if (isset($defaultCriteriaData['isSaveCriteriaTemplate'])) {
                        $param['isSaveCriteriaTemplate'] = '1';
                        $param['criteriaTemplateName'] = $defaultCriteriaData['criteriaTemplateName'];
                        $param['criteriaTemplateDescription'] = $defaultCriteriaData['criteriaTemplateDescription'];
                        
                        if (isset($defaultCriteriaData['isReturnCriteriaTemplateId'])) {
                            $param['isReturnCriteriaTemplateId'] = '1';
                        }
                    }
                    
                    if (isset($defaultCriteriaData['criteriaKpi'])) {
                        foreach ($defaultCriteriaData['criteriaKpi'] as $criteriaKpiField => $criteriaKpiJson) {
                            $param['criteria'][$criteriaKpiField]['kpi'] = json_decode($criteriaKpiJson, true);
                        }
                    }
                }
                
                if (isset($defaultCriteriaData['idWithComma'])) {
            
                    includeLib('Compress/Compression');
                    $idWithComma = $defaultCriteriaData['idWithComma'];

                    foreach ($idWithComma as $commaKey => $commaVal) {
                        $param['criteria'][$commaKey][] = array(
                            'operator' => 'IN',
                            'operand' => Compression::gzinflate(Input::param($commaVal))
                        );
                    }
                }
                
                if (isset($defaultCriteriaData['lookupSuggestedValues'])) {
                    $lookupSuggestedValues = $defaultCriteriaData['lookupSuggestedValues'];
                    foreach ($lookupSuggestedValues as $lookupSuggestedValKey => $suggestedValues) {
                        $lookupSuggestedValKey = strtolower($lookupSuggestedValKey);
                        if (!isset($param['criteria'][$lookupSuggestedValKey])) {
                            $param['criteria'][$lookupSuggestedValKey][] = array(
                                'operator' => 'IN',
                                'operand' =>$suggestedValues
                            );
                        }
                    }
                }
            }

            if (!empty($workSpaceId) && !empty($workSpaceParams)) {
                
                $getWorkSpaceDvParamMap = self::getWorkSpaceDvParamMap($metaDataId, $workSpaceId);
                
                $workSpaceParams = str_replace('&amp;amp;', '&', $workSpaceParams);
                $workSpaceParams = str_replace('&amp;workSpaceParam%', '&workSpaceParam%', $workSpaceParams);
                $workSpaceParams = str_replace('&amp;isFlow=', '&isFlow=', $workSpaceParams);
                parse_str($workSpaceParams, $workSpaceParamArray);

                $isResponse = false;

                if ($getWorkSpaceDvParamMap && is_array($workSpaceParamArray)) {

                    if (isset($workSpaceParamArray['workSpaceParam'])) {

                        $getWorkSpaceParam = $workSpaceParamArray['workSpaceParam'];
                        $paramWorkSpaceCriteria = array();

                        foreach ($getWorkSpaceDvParamMap as $wsRow) {
                            $lowerKey = strtolower($wsRow['FIELD_PATH']);

                            if (isset($getWorkSpaceParam[$lowerKey])) {
                                $paramWorkSpaceCriteria[$wsRow['PARAM_PATH']][] = ($getWorkSpaceParam[$lowerKey] != '' && $getWorkSpaceParam[$lowerKey] !== '') ? 
                                    array(
                                        'operator' => '=',
                                        'operand' => $getWorkSpaceParam[$lowerKey]
                                    ) :
                                    array(
                                        'operator' => 'IS NULL',
                                        'operand' => ''
                                    );
                                $isResponse = true;
                            }
                        }
                    }

                    foreach ($workSpaceParamArray as $wsKey => $wsVal) {

                        if (!is_array($wsVal)) {

                            $paramWorkSpaceCriteria[$wsKey][] = ($wsVal != '' && $wsVal !== '') ? 
                                array(
                                    'operator' => '=',
                                    'operand' => $wsVal
                                ) :
                                array(
                                    'operator' => 'IS NULL',
                                    'operand' => ''
                                );
                            $isResponse = true;
                        } 
                    }
                } 

                if ($isResponse) {
                    if (isset($param['criteria'])) {
                        $param['criteria'] = array_merge($param['criteria'], $paramWorkSpaceCriteria);
                    } else {
                        $param['criteria'] = $paramWorkSpaceCriteria;
                    }

                    $isParentChildResolve = true;
                }
            }
            
            if (Input::isEmpty('treeConfigs') == false && $pagination) {

                parse_str(Input::post('treeConfigs'), $treeConfigs);
                
                if (isset($treeConfigs['parent'])) {
                    
                    $isParentFilter = issetParam($gridOption['IS_PARENT_FILTER']);
                    $paramTreeCriteria = array();
                    
                    if (Input::isEmpty('id') == false) {

                        $paramTreeCriteria[$treeConfigs['parent']][] = array(
                            'operator' => '=',
                            'operand' => Input::post('id')
                        );

                        if (isset($param['paging'])) {
                            unset($param['paging']);
                        }        

                        if (isset($param['criteria']) && !isset($param['criteria']['acceptchildcriteria']) && $isParentFilter != '1') {
                            unset($param['criteria']);
                        }        

                    } elseif (Input::numeric('isIgnoreParentIsNull') != 1) {

                        if (!isset($param['criteria']) 
                            || (isset($param['criteria']) && empty($param['criteria'])) 
                            || ($isParentFilter == '1' && !isset($isColumnFilter)) 
                            || Input::numeric('isParentFilter') == 1) {

                            $paramTreeCriteria[$treeConfigs['parent']][] = array(
                                'operator' => 'IS NULL',
                                'operand' => ''
                            );
                        }
                    }
                    
                    if ($paramTreeCriteria) {
                        if (isset($param['criteria'])) {
                            $param['criteria'] = array_merge($param['criteria'], $paramTreeCriteria);
                        } else {
                            $param['criteria'] = $paramTreeCriteria;
                        }
                    }
                }
            }   
            
            if (defined('CONFIG_SCHOOL_SEMISTER') && CONFIG_SCHOOL_SEMISTER && Ue::sessionSemisterYear()) {
                if (isset($param['criteria'])) {
                    $param['criteria'] = array_merge($param['criteria'], array(
                            'tempsemisterplanid' => array(
                                array(
                                    'operator' => '=',
                                    'operand'  => Ue::sessionSemisterYear()
                                )
                            ),
                        )
                    );
                } else {
                    $param['criteria'] = array('semisterplanid' => array(
                            array(
                                'operator' => '=', 
                                'operand'  => Ue::sessionSemisterYear()
                            )
                        ),
                    );
                }
            }
            
            if (Config::getFromCache('CONFIG_ACCOUNT_SEGMENT') && Input::postCheck('accountSegmentFullCode')) {
                
                $accountSegmentFilter = array();
                $accountSegmentFullCode = $_POST['accountSegmentFullCode'];

                foreach ($accountSegmentFullCode as $accSgmtPath => $accSgmtVal) {
                    $accountSegmentFilter[$accSgmtPath.'SegmentCode'][] = array(
                        'operator' => 'like',
                        'operand' => $accSgmtVal
                    );
                }

                if (isset($param['criteria'])) {
                    $param['criteria'] = array_merge($param['criteria'], $accountSegmentFilter);
                } else {
                    $param['criteria'] = $accountSegmentFilter;
                }
            }
            
            if ($kpiIndicatorMapConfig = Input::post('kpiIndicatorMapConfig')) {
                $kpiIndicatorSetCriteria = self::setKpiIndicatorCriteriaModel($metaDataId, $kpiIndicatorMapConfig);
                if (isset($kpiIndicatorSetCriteria['criteria'])) {
                    if (isset($param['criteria'])) {
                        $param['criteria'] = array_merge($param['criteria'], $kpiIndicatorSetCriteria['criteria']);
                    } else {
                        $param['criteria'] = $kpiIndicatorSetCriteria['criteria'];
                    }
                }
            }
        }
        
        if (Input::postCheck('isPivot')) {

            $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);                

            if ($data['status'] == 'success' && isset($data['result'])) {

                try {

                    $sql = $data['result'];

                    $this->db->StartTrans(); 
                    $this->db->Execute(Ue::createSessionInfo());

                    $sqlData = $this->db->SelectLimit($sql, 50000, -1);

                    $this->db->CompleteTrans();

                    $rowsData = array();

                    if (isset($sqlData->_array)) {
                        $rowsData = Arr::changeKeyLower($sqlData->_array);
                    }

                    $result = $rowsData;
                    
                } catch (ADODB_Exception $ex) {
                    $result = array('status' => 'error', 'message' => $ex->getMessage());
                }

            } else {
                $result = array('status' => 'error', 'message' => $this->ws->getResponseMessage($data), 'rows' => array(), 'total' => 0);
            }

        } else {
            
            if (!$pagination) {
                
                $param['showQuery'] = '0';
                $param['isShowAggregate'] = '1';

                if (Input::isEmpty('treeGrid') == false || Input::isEmpty('treeConfigs') == false) {
                    $param['treeGrid'] = '1';
                }
                
                if (isset($param['paging']['offset'])) { 
                    unset($param['paging']['offset']); 
                    unset($param['paging']['pageSize']); 
                }
                
                if (isset($param['criteria']['pfKpiTemplateDynamicColumn'][0]['operand'])) {
                    Mdobject::$pfKpiTemplateDynamicColumn = Input::param($param['criteria']['pfKpiTemplateDynamicColumn'][0]['operand']);
                }
                
                if ((issetParam($gridOption['IS_CRYPTED_FIELD']) || issetParam($gridOption['IS_USE_RESULT']) || Mdobject::$pfKpiTemplateDynamicColumn) 
                        && !Input::postCheck('isShowPivot')) {
                    
                    $data = self::getDataViewPagingAllRowsModel($metaDataId, Input::numeric('total'), $param);
                    
                } else {
                
                    if (Input::postCheck('isResponseSql')) {

                        $param['showQuery'] = '1';

                        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

                        if ($data['status'] == 'success' && isset($data['result'])) {
                            
                            $result = array('status' => 'success', 'sql' => $data['result']);
                            
                            if (Input::postCheck('isResponseCriteria')) {
                                $result['criteria'] = isset($param) ? (isset($param['criteria']) ? $param['criteria'] : array()) : array();
                            }
                            
                            return $result;
                        } else {
                            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data), 'rows' => array(), 'total' => 0);
                        }

                    } elseif (Input::postCheck('isExportExcel')) {

                        if (isset($param['showQuery'])) { unset($param['showQuery']); }
                        if (isset($param['treeGrid'])) { unset($param['treeGrid']); }

                        $param['ignorePermission'] = '1';
                        $param['showQuery'] = '1';
                        //$param['exportExcel'] = '1';

                        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

                        if ($data['status'] == 'success' && isset($data['result'])) {
                            
                            $sql = self::getDataViewExcelColumnsSqlModel($metaDataId, $data['result']);
                            
                            return array('status' => 'success', 'sql' => $sql);
                        } else {
                            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data), 'rows' => array(), 'total' => 0);
                        }

                    } elseif (Input::postCheck('isShowPivot')) {

                        if (isset($param['showQuery'])) { unset($param['showQuery']); }
                        if (isset($param['treeGrid'])) { unset($param['treeGrid']); }

                        $param['ignorePermission'] = '1';
                        $param['showPivot'] = '1';

                        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

                        if ($data['status'] == 'success' && isset($data['result'])) {
                            return array('status' => 'success', 'reportId' => $data['result'], 'dvId' => $metaDataId);
                        } else {
                            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
                        }
                    }

                    $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
                }
                
                if ($data['status'] == 'success' && isset($data['result'])) {
					
                    $result['total'] = (isset($data['result']['paging']) ? $data['result']['paging']['totalcount'] : 0);

                    unset($data['result']['paging']);

                    if (isset($data['result']['aggregatecolumns']) && $data['result']['aggregatecolumns']) {
                        $result['footer'] = array($data['result']['aggregatecolumns']);
                    }
                    
                    unset($data['result']['aggregatecolumns']);
                    
                    if (isset($param['treeGrid']) && isset($data['result'][0])) {
                            
                        foreach ($data['result'] as $rowIndex => $rowData) {

                            if (isset($rowData['_state'])) {
                                $data['result'][$rowIndex]['state'] = (isset($rowData['_rowstate']) && isset($rowData['childrecordcount'])) ? $rowData['_rowstate'] : $rowData['_state'];
                            } else {
                                $data['result'][$rowIndex]['state'] = isset($rowData['childrecordcount']) ? 'closed' : 'open';
                            }
                            
                            if (isset($rowData['children'])) {
                                $data['result'][$rowIndex]['children'] = self::treegridChildrenFixRows($rowData['children']);
                            }
                        }
                        
                        if (array_key_exists('iconcolorcode', $data['result'][0])) {
                            $data['result'] = Arr::multiDimensionalChangeKeyName($data['result'], 'iconcolorcode', 'iconCls');
                        }
                    }
					
                    $result['rows'] = $data['result'];
                    $result['status'] = 'success';

                    if (Input::postCheck('isResponseCriteria')) {
                        $result['criteria'] = isset($param) ? (isset($param['criteria']) ? $param['criteria'] : array()) : array();
                    }

                } else {
                    $result = array('status' => 'error', 'message' => $this->ws->getResponseMessage($data), 'rows' => array(), 'total' => 0);
                }

            } else {

                $param['showQuery'] = '0';

                if (isset($treeConfigs)) {
                    $param['treeGrid'] = '1';
                }
                
                $param['pagingWithoutAggregate'] = Input::numeric('pagingWithoutAggregate');

                if (issetParam($gridOption['DV_WS_URL']) == 'bugfixServiceAddress') {
                    
                    ini_set('max_execution_time', 10);
                    ini_set('default_socket_timeout', 10);
                    
                    if (!isset($isSubgrid)) {
                        $param['systemMetaGroupId'] = '1498128719613';
                    }
                    
                    $data = Mdupgrade::getBugfixDataByCommand('list', $param);
                    
                } else {
                    $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param); 
                }

                if ($data['status'] == 'success' && isset($data['result'])) {
                    
                    if (isset($param['isReturnCriteriaTemplateId']) && $param['isReturnCriteriaTemplateId'] == '1') {
                        if (isset($data['result']['criteriatemplateid'])) {
                            return array('status' => 'success', 'id' => $data['result']['criteriatemplateid']);
                        } else {
                            return array('status' => 'error', 'message' => 'criteriaTemplateId олдсонгүй!');
                        }
                    }
                    
                    $result['total'] = (isset($data['result']['paging']['totalcount']) ? $data['result']['paging']['totalcount'] : 0);

                    unset($data['result']['paging']);

                    if (isset($data['result']['aggregatecolumns']) && $data['result']['aggregatecolumns']) {
                        
                        $aggregateColumns = $data['result']['aggregatecolumns'];
                        
                        if (isset($gridOption['aggregateAliasPath']) && $gridOption['aggregateAliasPath']) {
                            
                            $aggregateAliasPaths = $gridOption['aggregateAliasPath'];
                            
                            foreach ($aggregateAliasPaths as $aggregateAliasPath) {
                                if (isset($aggregateColumns[$aggregateAliasPath['FIELD_PATH']])) {
                                    $aggregateColumns[$aggregateAliasPath['FIELD_PATH']] = $aggregateColumns[$aggregateAliasPath['AGGREGATE_ALIAS_PATH']];
                                }
                            }
                        }
                        
                        $result['footer'] = array($aggregateColumns);
                    }
                    
                    unset($data['result']['aggregatecolumns']);
                    
                    if (isset($data['result'][0]['langline'])) {
                        array_walk($data['result'], function(&$value) {  
                            $value['langline'] = Lang::line($value['langline']);
                        }); 
                    }
                        
                    if (isset($treeConfigs)) {
                        
                        if (isset($data['result'][0])) {
                            
                            foreach ($data['result'] as $rowIndex => $rowData) {
                                
                                if (isset($rowData['_state'])) {
                                    $data['result'][$rowIndex]['state'] = (isset($rowData['_rowstate']) && isset($rowData['childrecordcount'])) ? $rowData['_rowstate'] : $rowData['_state'];
                                } else {
                                    $data['result'][$rowIndex]['state'] = isset($rowData['childrecordcount']) ? (isset($rowData['_rowstate']) ? $rowData['_rowstate'] : 'closed') : 'open';
                                }
                                
                                if (isset($rowData['children'])) {
                                    $data['result'][$rowIndex]['children'] = self::treegridChildrenFixRows($rowData['children']);
                                }
                            }
                            
                            if (array_key_exists('iconcolorcode', $data['result'][0])) {
                                $data['result'] = Arr::multiDimensionalChangeKeyName($data['result'], 'iconcolorcode', 'iconCls');
                            }
                        }
                            
                        if (Input::postCheck('id')) {

                            unset($data['total']);
                            $result = $data['result'];

                        } else {
                            $result['rows'] = $data['result'];
                            $result['status'] = 'success';
                        }

                    } else {

                        $result['rows'] = $data['result'];
                        $result['status'] = 'success';
                    }
                    
                    if (isset($result['rows'][0]) && $pfKpiTemplateDynamicColumn = issetParam($param['criteria']['pfKpiTemplateDynamicColumn'][0]['operand'])) {

                        $kpiColumns = self::getKpiTemplateColumns($pfKpiTemplateDynamicColumn);
                        $kpiColumnsData = self::getKpiTemplateColumnsPushData($kpiColumns, Arr::implode_key(',', $result['rows'], 'id', true));
                        
                        foreach ($kpiColumnsData as $recordId => $kpiColumnsRow) {
                            
                            foreach ($result['rows'] as $k => $row) {
                                
                                if ($recordId == $row['id']) {
                                    $result['rows'][$k] = array_merge($row, $kpiColumnsRow);
                                    break;
                                }
                            }
                        }
                    }
                    
                    if (issetParam($gridOption['IS_FILTER_LOG']) == '1' && $param['pagingWithoutAggregate'] == 2 && !isset($defaultCriteriaData['isFilterReset'])) {
                        
                        $filterLogResult = self::saveDvFilterDataModel($param, $result['total']);
                        
                        if (isset($filterLogResult['logId'])) {
                            
                            $filterLogId = $filterLogResult['logId'];
                            
                            array_walk($result['rows'], function(&$value) use (&$filterLogId) {  
                                $value['filterlogid'] = $filterLogId;
                            }); 
                        }
                    }

                } else {
                    $result = array('status' => 'error', 'message' => $this->ws->getResponseMessage($data), 'rows' => array(), 'total' => 0);
                }
            }
        }

        return $result;
    }
    
    public function getDataViewPagingAllRowsModel($metaDataId, $total, $param) {
        
        unset($param['paging']);
        
        $param['showQuery'] = 0;
        $param['pagingWithoutAggregate'] = 1;
        
        $size = 200;
        $pages = ceil($total / $size);
        $rows = array();
        $headerDatas = self::getDataViewGridHeaderModel($metaDataId, "(IS_IGNORE_EXCEL IS NULL OR IS_IGNORE_EXCEL = 0) AND META_TYPE_CODE <> 'file'");
        $rowLoop = '';
        
        foreach ($headerDatas as $headerData) {
            $fieldPath = $headerData['FIELD_PATH'];
            $rowLoop .= '$rowData[\''.$fieldPath.'\'] = $row[\''.$fieldPath.'\']; ';
        }
        
        if (Mdobject::$pfKpiTemplateDynamicColumn) {
            
            $kpiColumns = self::getKpiTemplateColumns(Mdobject::$pfKpiTemplateDynamicColumn);
            
            if (!$kpiColumns) {
                Mdobject::$pfKpiTemplateDynamicColumn = null;
            }
        }
        
        for ($p = 1; $p <= $pages; $p++) {
            
            $param['paging'] = array(
                'offset' => $p,
                'pageSize' => $size
            );
            
            $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
            
            if ($data['status'] == 'success' && isset($data['result'])) {
                
                unset($data['result']['paging']);
                unset($data['result']['aggregatecolumns']);
                
                $result = $data['result'];
                
                if (Mdobject::$pfKpiTemplateDynamicColumn) {
                    
                    $kpiColumnsData = self::getKpiTemplateColumnsPushData($kpiColumns, Arr::implode_key(',', $result, 'id', true));
                }
                
                foreach ($result as $row) {
                    $rowData = array();
                    eval($rowLoop);
                    
                    if (Mdobject::$pfKpiTemplateDynamicColumn) {
                        
                        foreach ($kpiColumnsData as $recordId => $kpiColumnsRow) {

                            if ($recordId == $row['id']) {
                                $rowData = $rowData + $kpiColumnsRow;
                                break;
                            }
                        }
                    }
                    
                    $rows[] = $rowData;
                }
                
            } else {
                return array('status' => 'error', 'text' => $this->ws->getResponseMessage($data));
            }
        }
        
        return array('status' => 'success', 'result' => $rows);
    }
    
    public function setKpiIndicatorCriteriaModel($metaDataId, $kpiIndicatorMapConfig) {
        $response = array();
        
        try {
            $kpiIndicatorMapConfig = @json_decode(html_entity_decode($kpiIndicatorMapConfig), true);
            $mapId = issetParam($kpiIndicatorMapConfig['mapId']);
            
            if (is_numeric($mapId)) {
                
                $idPh1 = $this->db->Param(0);
                $idPh2 = $this->db->Param(1);
                
                $mapList = $this->db->GetAll("
                    SELECT 
                        T0.TRG_META_DATA_PATH, 
                        T0.DEFAULT_VALUE 
                    FROM KPI_INDICATOR_INDICATOR_MAP T0 
                    WHERE T0.SRC_INDICATOR_MAP_ID = $idPh1 
                        AND T0.SEMANTIC_TYPE_ID = $idPh2", 
                    array($mapId, Mdform::$semanticTypes['checkListParamMap'])
                );
                
                if ($mapList) {
                    foreach ($mapList as $map) {
                        $defaultValue = Mdmetadata::setDefaultValue($map['DEFAULT_VALUE']);
                        $response['criteria'][$map['TRG_META_DATA_PATH']] = array(array('operator' => '=', 'operand' => $defaultValue));
                    }
                }
            }
            
        } catch (Exception $ex) { }
        
        return $response;
    }
    
    public function getDataViewExcelColumnsSqlModel($metaDataId, $query) {
        
        $excelCriteria = "(IS_IGNORE_EXCEL IS NULL OR IS_IGNORE_EXCEL = 0) AND META_TYPE_CODE <> 'file'";
        $excelCriteria .= " AND ((FIELD_PATH <> 'wfmstatusname' AND COLUMN_NAME IS NOT NULL) OR (FIELD_PATH = 'wfmstatusname'))"; 
        
        $headerDatas = self::getDataViewGridHeaderModel($metaDataId, $excelCriteria);
        $columns = '';
        
        foreach ($headerDatas as $headerData) {
            $columns .= 'PDD."'.strtoupper($headerData['FIELD_PATH']).'", ';
        }
        
        $columns = trim($columns);
        $columns = rtrim($columns, ',');
        
        $sql = 'SELECT '.$columns.' FROM ('.$query.') PDD';
        
        return $sql;
    }
    
    public function treegridChildrenFixRows($children = array()) {
        
        foreach ($children as $rowIndex => $rowData) {
                                
            if (isset($rowData['_state'])) {
                $children[$rowIndex]['state'] = (isset($rowData['_rowstate']) && isset($rowData['childrecordcount'])) ? $rowData['_rowstate'] : $rowData['_state'];
            } else {
                $children[$rowIndex]['state'] = isset($rowData['childrecordcount']) ? (isset($rowData['_rowstate']) ? $rowData['_rowstate'] : 'closed') : 'open';
            }

            if (isset($rowData['children'])) {
                $children[$rowIndex]['children'] = self::treegridChildrenFixRows($rowData['children']);
            }
        }
        
        return $children;
    }
    
    public function saveDvFilterDataModel($param, $rowCount) {
        
        $sessionUserKeyId = Ue::sessionUserKeyId();
        
        if ($sessionUserKeyId) {
            
            $dvId       = $param['systemMetaGroupId'];
            $logColumns = self::getDVFilterLogColumnsModel($dvId);
            
            if ($logColumns) {
                
                try {
                    
                    $this->load->model('mdmetadata', 'middleware/models/');
                    
                    $logId = getUID();
                    
                    $data = array(
                        'ID'              => $logId, 
                        'DV_META_DATA_ID' => $dvId, 
                        'CREATED_USER_ID' => $sessionUserKeyId, 
                        'CREATED_DATE'    => Date::currentDate(), 
                        'ROW_COUNT'       => $rowCount, 
                        'IP_ADDRESS'      => get_client_ip()
                    );
                    
                    if (isset($param['criteria']) && count($param['criteria'])) {
                        
                        $criteria = Arr::changeKeyLower($param['criteria']);
                    
                        foreach ($logColumns as $logColumn) {

                            if (isset($criteria[$logColumn['FIELD_PATH']])) {

                                $criteriaValue  = '';
                                $criteriaArrays = $criteria[$logColumn['FIELD_PATH']];

                                foreach ($criteriaArrays as $criteriaArr) {

                                    $criteriaOperator = $criteriaArr['operator'];
                                    $criteriaValue    = $criteriaArr['operand'];

                                    if ($criteriaOperator == 'like' || $criteriaOperator == 'start' || $criteriaOperator == 'end') {
                                        $criteriaValue = str_replace('%', '', $criteriaValue);
                                    }

                                    if ($logColumn['LOOKUP_META_DATA_ID'] != '' && $logColumn['LOOKUP_TYPE'] != '') {

                                        $arr = array(
                                            'META_DATA_ID'          => $logColumn['LOOKUP_META_DATA_ID'],
                                            'ATTRIBUTE_ID_COLUMN'   => $logColumn['VALUE_FIELD'],
                                            'ATTRIBUTE_NAME_COLUMN' => $logColumn['DISPLAY_FIELD'],
                                            'PARAM_REAL_PATH'       => $logColumn['FIELD_PATH'],
                                            'PROCESS_META_DATA_ID'  => $dvId,
                                            'CHOOSE_TYPE'           => $logColumn['CHOOSE_TYPE']
                                        );
                                        $inputParamArr = array();
                                        
                                        if (issetParam($logColumn['GROUP_CONFIG_PARAM_PATH']) != '' && issetParam($logColumn['GROUP_CONFIG_LOOKUP_PATH']) != '') {
                                            $groupParamPaths = explode('|', $logColumn['GROUP_CONFIG_PARAM_PATH']);
                                            $groupLookupPaths = explode('|', $logColumn['GROUP_CONFIG_LOOKUP_PATH']);
                                            
                                            foreach ($groupParamPaths as $p => $groupParamPath) {
                                                
                                                if ($pathCriteria = issetVar($criteria[strtolower($groupParamPath)])) {
                                                    $inputParamArr[$groupLookupPaths[$p]] = $pathCriteria[0];
                                                }
                                            }
                                        }

                                        $valueList = $this->model->getSingleMetaDataValuesByDataViewModel($arr, $criteriaValue, $inputParamArr);

                                        if ($valueList) {
                                            $criteriaValue = Arr::implode_key(', ', $valueList, 'META_VALUE_NAME', true);
                                        }
                                    }

                                    $criteriaValue .= ', ';
                                }

                                $criteriaValue = rtrim($criteriaValue, ', ');

                                $data[$logColumn['LOG_COLUMN_NAME']] = $criteriaValue;
                            }
                        }
                    }

                    $this->db->AutoExecute('CUSTOMER_DV_FILTER_DATA', $data);

                    $result = array('status' => 'success', 'logId' => $logId);

                } catch (ADODB_Exception $ex) {
                    $result = array('status' => 'error', 'message' => $ex->getMessage());
                }
                
            } else {
                $result = array('status' => 'error', 'message' => 'Not log columns!');
            }
            
        } else {
            $result = array('status' => 'error', 'message' => 'Session UserKey Id!');
        }
        
        return $result;
    }
    
    public function getDVFilterLogColumnsModel($dvId) {
        
        $cache = phpFastCache();

        $data = $cache->get('dvLogColumns_' . $dvId);
        
        if ($data == null) {

            $data = $this->db->GetAll("
                SELECT 
                    LOWER(GC.FIELD_PATH) AS FIELD_PATH, 
                    GC.LOG_COLUMN_NAME, 
                    GC.ICON_NAME, 
                    GC.DATA_TYPE, 
                    GC.LOOKUP_META_DATA_ID, 
                    GC.LOOKUP_TYPE, 
                    GC.CHOOSE_TYPE, 
                    GC.DISPLAY_FIELD, 
                    GC.VALUE_FIELD, 
                    (
                        SELECT 
                            ".$this->db->listAgg('PARAM_PATH', '|', 'PARAM_PATH')."  
                        FROM META_GROUP_PARAM_CONFIG 
                        WHERE GROUP_META_DATA_ID = ".$this->db->Param(0)."   
                            AND LOOKUP_META_DATA_ID IS NOT NULL 
                            AND (IS_GROUP = 0 OR IS_GROUP IS NULL) 
                            AND LOWER(FIELD_PATH) = LOWER(GC.FIELD_PATH) 
                    ) AS GROUP_CONFIG_PARAM_PATH, 
                    (
                        SELECT 
                            ".$this->db->listAgg('PARAM_META_DATA_CODE', '|', 'PARAM_PATH')."  
                        FROM META_GROUP_PARAM_CONFIG  
                        WHERE GROUP_META_DATA_ID = ".$this->db->Param(0)."  
                            AND LOOKUP_META_DATA_ID IS NOT NULL 
                            AND (IS_GROUP = 0 OR IS_GROUP IS NULL) 
                            AND LOWER(FIELD_PATH) = LOWER(GC.FIELD_PATH) 
                    ) AS GROUP_CONFIG_LOOKUP_PATH 
                FROM META_GROUP_CONFIG GC 
                WHERE GC.MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                    AND GC.LOG_COLUMN_NAME IS NOT NULL 
                ORDER BY GC.DISPLAY_ORDER ASC", array($dvId));

            $cache->set('dvLogColumns_'.$dvId, $data, Mdwebservice::$expressionCacheTime);
        }

        return $data;
    }

    public function convertGlCriteriaValue($rawData) {
        
        $array = $temparray = array();
        $temparray = json_decode(html_entity_decode($rawData), true);
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

    public function getBPInputMetaId($metaDataId) {
        
        $idPh = $this->db->Param(0);
        
        $row = $this->db->GetRow("
            SELECT 
                META_DATA_ID AS PROCESS_META_DATA_ID 
            FROM META_BUSINESS_PROCESS_LINK 
            WHERE META_DATA_ID = $idPh", array($metaDataId));

        if ($row) {
            return $row;
        } else {
            $row = $this->db->GetRow("
                SELECT 
                    META_DATA_ID AS GROUP_META_DATA_ID 
                FROM META_GROUP_LINK 
                WHERE META_DATA_ID = $idPh", array($metaDataId)); 
            if ($row) {
                return $row;
            }
        }  

        return false;
    }

    public function isGoogleMapButtonModel($metaDataId) {
        $coordinateField = $this->db->GetRow("
            SELECT 
                MMM.TRG_META_DATA_ID 
            FROM META_META_MAP MMM
                INNER JOIN META_FIELD_LINK MFL ON MMM.TRG_META_DATA_ID = MFL.META_DATA_ID
            WHERE MMM.SRC_META_DATA_ID = ".$this->db->Param(0)." 
                AND MFL.DATA_TYPE = 'coordinate'", array($metaDataId));

        if ($coordinateField) {
            return true;
        }   
        return false;
    }

    public function googleMapDataGridModel() {
        
        $metaDataId = Input::numeric('metaDataId');
        $googleMapData = array('metaDataId' => $metaDataId);
        
        $coordinateField = $this->db->GetAll("
            SELECT 
                MM.FIELD_PATH AS META_DATA_CODE, 
                MM.DATA_TYPE 
            FROM META_GROUP_CONFIG MM 
            WHERE MM.MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                AND MM.DATA_TYPE IN ('coordinate', 'region')", 
            array($metaDataId)
        );
        
        if ($coordinateField) {
            
            $result = self::dataViewDataGridModel(false);
            $attributes = self::getDataViewMetaValueAttributes(null, null, $metaDataId);
            
            $id = isset($attributes['id']) ? strtolower($attributes['id']) : null;
            $code = isset($attributes['code']) ? strtolower($attributes['code']) : null;
            $name = isset($attributes['name']) ? strtolower($attributes['name']) : null;
            
            if (isset($result['rows'][0]['pfgmapgroupfield'])) {
                $isGrouping = true;
            }
            
            foreach ($coordinateField as $coor) {
                
                if ($coor['DATA_TYPE'] == 'coordinate') {
                    
                    $gMap = array();
                    $i = 0;

                    foreach ($result['rows'] as $key => $row) {
                        if (isset($row[$coor['META_DATA_CODE']])) {
                            
                            $coorinate = trim($row[$coor['META_DATA_CODE']], '|');

                            if (strlen($coorinate) > 0) {
                                $delimiterChar = (strpos($coorinate, ',') !== false) ? ',' : '|';
                                $coordinate = explode($delimiterChar, $coorinate);
                                
                                if (count($coordinate) === 2) {
                                    
                                    $lat = trim($coordinate[1]);
                                    $lng = trim($coordinate[0]);
                                    
                                    if ((float) $lat > (float) $lng) {
                                        $latTmp = $lat;
                                        $lat = $lng;
                                        $lng = $latTmp;
                                    }
                                    
                                    $gMap[$i]['lat'] = $lat;
                                    $gMap[$i]['lng'] = $lng;
                                    $gMap[$i]['actionMetaDataId'] = issetParam($row['actionmetadataid']);
                                    $gMap[$i]['actionMetaTypeId'] = issetParam($row['actionmetatypeid']);
                                    $gMap[$i]['id'] = issetParam($row['id']);
                                    $gMap[$i]['metaGoogleMapLinkId'] = true;
                                    $gMap[$i]['rowData'] = json_encode($row, JSON_UNESCAPED_UNICODE);
                                    $gMap[$i]['markerColor'] = (isset($row['markercolor']) ? $row['markercolor'] : 'FF0000');
                                    
                                    if (array_key_exists($id, $row)) {
                                        $gMap[$i]['META_VALUE_ID'] = $row[$id];
                                    }
                                    if (array_key_exists($code, $row)) {
                                        $gMap[$i]['META_VALUE_CODE'] = $row[$code];
                                    }
                                    if (array_key_exists($name, $row)) {
                                        $gMap[$i]['META_VALUE_NAME'] = $row[$name];
                                    }
                                    
                                    if (isset($isGrouping)) {
                                        $gMap[$i]['groupField'] = $row['pfgmapgroupfield'];
                                    }
                                    
                                    $i++;
                                }
                            }
                        }
                    }
                    
                    $googleMapData['coordinate'] = array(
                        array(
                            'META_DATA_ID' => $metaDataId,
                            'IS_DYNAMIC' => 0, 
                            'DRAW_TYPE' => 'MARKER', 
                            'COLOR' => (isset($row['markercolor']) ? $row['markercolor'] : 'FF0000'), 
                            'ICON' => 'marker',
                            'META_GOOGLE_MAP_LINK_ID' => false,
                            'isGrouping' => isset($isGrouping),
                            'GMAPDATA' => $gMap
                        )
                    );
                }
                
                if ($coor['DATA_TYPE'] == 'region') {
                    
                    $gMap = array();
                    $i = 0;

                    foreach ($result['rows'] as $key => $row) {
                        if (isset($row[$coor['META_DATA_CODE']])) {
                            $gMap[$i] = json_decode(str_replace("&quot;", "\"", $row[$coor['META_DATA_CODE']]));
                            $gMap[$i]->desc = $row[$name];
                            $i++;
                        }
                    }
                    
                    $googleMapData['region'] = $gMap;
                }
            }
        }
        
        return $googleMapData;
    }

    public function getWorkSpaceDvParamMap($dmMetaDataId, $workSpaceId) {
        $data = $this->db->GetAll("
            SELECT 
                FIELD_PATH, 
                PARAM_PATH 
            FROM META_WORKSPACE_PARAM_MAP 
            WHERE WORKSPACE_META_ID = ".$this->db->Param(0)." 
                AND TARGET_META_ID = ".$this->db->Param(1)." 
                AND IS_TARGET = 1", 
            array($workSpaceId, $dmMetaDataId)
        );

        return $data;
    }

    public function getDataViewBatchGroupBy($metaDataId, $accessProcess) {
        
        $join = $andWhere = '';
        
        if (Input::isEmpty('workSpaceId') == false && $workSpaceId = Input::numeric('workSpaceId')) {
                
            $join = "LEFT JOIN META_DM_PROCESS_IGNORE DPI ON DPI.MAIN_META_DATA_ID = PRO.MAIN_META_DATA_ID 
                AND DPI.TRG_META_DATA_ID = $workSpaceId 
                AND DPI.PROCESS_META_DATA_ID = PRO.PROCESS_META_DATA_ID ";

            $andWhere .= ' AND DPI.ID IS NULL ';
        }
        
        if (Input::isEmpty('runSrcMetaId') == false && $runSrcMetaId = Input::numeric('runSrcMetaId')) {
                
            $join .= "LEFT JOIN META_DM_PROCESS_IGNORE DPIS ON DPIS.MAIN_META_DATA_ID = PRO.MAIN_META_DATA_ID 
                AND DPIS.TRG_META_DATA_ID = $runSrcMetaId 
                AND DPIS.PROCESS_META_DATA_ID = PRO.PROCESS_META_DATA_ID ";

            $andWhere .= ' AND DPIS.ID IS NULL ';
        }
        
        $join .= "LEFT JOIN META_DM_PROCESS_IGNORE DPISN ON DPISN.MAIN_META_DATA_ID = PRO.MAIN_META_DATA_ID 
            AND DPISN.TRG_META_DATA_ID IS NULL  
            AND DPISN.PROCESS_META_DATA_ID = PRO.PROCESS_META_DATA_ID ";

        $andWhere .= ' AND DPISN.ID IS NULL ';
        
        $data = $this->db->GetAll("
            SELECT 
                PB.BATCH_NUMBER, 
                PB.BATCH_NAME, 
                PB.ICON_NAME, 
                PB.IS_DROPDOWN, 
                PB.BUTTON_STYLE, 
                PB.IS_SHOW_POPUP 
            FROM META_DM_PROCESS_BATCH PB 
                INNER JOIN META_DM_PROCESS_DTL PRO ON PRO.BATCH_NUMBER = PB.BATCH_NUMBER 
                    AND PRO.MAIN_META_DATA_ID = PB.MAIN_META_DATA_ID 
                $join     
            WHERE PB.MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                $andWhere 
                AND PRO.PROCESS_META_DATA_ID IN (".Arr::implode_key(',', $accessProcess, 'processid', true).")
            GROUP BY 
                PB.BATCH_NUMBER, 
                PB.BATCH_NAME, 
                PB.ICON_NAME, 
                PB.IS_DROPDOWN, 
                PB.BUTTON_STYLE, 
                PB.IS_SHOW_POPUP 
            ORDER BY PB.BATCH_NUMBER ASC", array($metaDataId));

        return $data;
    }

    public function getAccessProcess($metaDataId, $isBasket = false) {
        
        $cache = phpFastCache();
        
        $userKeyId   = Ue::sessionUserKeyId();
        $isBasketInt = (int) $isBasket;

        $dataResult = $cache->get('dvUser_'.$userKeyId.'_'.$metaDataId.'_'.$isBasketInt);
        
        if ($dataResult == null) {
            
            $param = array(
                'userId'     => $userKeyId,
                'metaDataId' => $metaDataId,
                'isBasket'   => $isBasket
            );

            if (Input::isEmpty('proxyId') == false) {
                $param['proxyMetaDataId'] = Input::post('proxyId');
            }

            $data = $this->ws->runResponse(self::$gfServiceAddress, 'meta_process_dtl', $param);

            if ($data['status'] == 'success' && isset($data['result'][0]['processid'])) {
                $dataResult = $data['result'];
                $cache->set('dvUser_'.$userKeyId.'_'.$metaDataId.'_'.$isBasketInt, $dataResult, Mdwebservice::$expressionCacheTime);
            }
        }

        return $dataResult;
    }

    public function dataViewProcessCommandModel($metaDataId, $metaDataCode, $checkPopup, $isBasket = false, $dataviewUniqId = '', $isColumnDrill = false) {
        
        $commandFunction = $commandContext = $commandSort = $commandPositionSort = $openDefaultBp = $commandAddMeta = array();
        $isDataViewLifeCycle = $isShowRowSelect = false;
        $cmdBtn = '';

        $getAccessProcess = self::getAccessProcess($metaDataId, $isBasket);
        
        if ($getAccessProcess) {
            
            $andWhere = !$isColumnDrill ? 'AND (PRO.IS_WORKFLOW = 0 OR PRO.IS_WORKFLOW IS NULL) ' : '';
                    
            if ($isBasket) {
                $andWhere .= 'AND PRO.IS_SHOW_BASKET = 1';
            }

            $dataViewProcess = self::getDataViewProcess($metaDataId, $getAccessProcess, $andWhere);
            
            if ($dataViewProcess) {

                $batchProcess = $deleteProcess = array();  
                $dataViewBatch = self::getDataViewBatchGroupBy($metaDataId, $getAccessProcess);

                if ($dataViewBatch && !$isBasket) {

                    foreach ($dataViewBatch as $batch) {

                        $batchBtn = '';
                        $batchTopBtn = '';

                        if ($batch['IS_DROPDOWN'] == '1') {

                            if (!($checkPopup && $batch['IS_SHOW_POPUP'] != '1')) {

                                $batchBtn .= '<div class="btn-group dv-buttons-batch">';
                                $batchBtn .= '<button class="btn ' . (isset($batch['BUTTON_STYLE']) ? $batch['BUTTON_STYLE'] : 'btn-secondary') . ' btn-circle btn-sm dropdown-toggle" type="button" data-toggle="dropdown">';
                                $batchBtn .= (($batch['ICON_NAME'] != '') ? '<i class="far ' . $batch['ICON_NAME'] . '"></i> ' : '<i class="icon-plus3 font-size-12"></i> ') . Lang::line($batch['BATCH_NAME']);
                                $batchBtn .= '</button>';
                                $batchBtn .= '<ul class="dropdown-menu" role="menu">';
                                $batchTopBtn = $batchBtn;

                                foreach ($dataViewProcess as $row) {

                                    if (!($checkPopup && $row['IS_SHOW_POPUP'] != '1') && $row['BATCH_NUMBER'] == $batch['BATCH_NUMBER']) {
                                        
                                        $addonClass = '';
                                        $actionType = $row['ACTION_TYPE'];

                                        if ($actionType == '' && !$row['COUNT_GET'] && $row['META_TYPE_ID'] == Mdmetadata::$businessProcessMetaTypeId) {
                                            $actionType = 'insert';
                                        } elseif ($row['COUNT_GET'] && $actionType == 'insert' && $row['META_TYPE_ID'] == Mdmetadata::$businessProcessMetaTypeId) {
                                            $actionType = 'update';
                                        }                   
                                        
                                        if ($row['IS_SHOW_ROWSELECT'] == '1') {
                                            $isShowRowSelect = true;
                                            $addonClass = ' class="d-none dv-bp-btn-visible"';
                                        }

                                        $batchBtn .= '<li'.$addonClass.'>';
                                        $batchTopBtn .= '<li'.$addonClass.'>';

                                        if (empty($row['CRITERIA'])) {
                                            $batchBtn .= Html::anchor(
                                                'javascript:;', (($row['ICON_NAME'] != '') ? '<i class="far ' . $row['ICON_NAME'] . '"></i> ' : '') . Lang::line($row['PROCESS_NAME']), array(
                                                'onclick' => 'transferProcessAction(\'' . $row['PASSWORD_PATH'] . '\', \'' . $metaDataId . '\', \'' . $row['PROCESS_META_DATA_ID'] . '\', \'' . $row['META_TYPE_ID'] . '\', \'toolbar\', this, {callerType: \''.$metaDataCode.'\'}, undefined, undefined, undefined, undefined, \''. $dataviewUniqId .'\');', 
                                                'title' => Lang::line($row['ICON_PROCESS_NAME']),
                                                'data-actiontype' => $actionType,         
                                                'data-advanced-criteria' => $row['ADVANCED_CRITERIA']   
                                                ), true
                                            );
                                            $commandFunction = array(
                                                'functionName' => 'transferProcessAction',
                                                'metaDataId' => $metaDataId,
                                                'processMetaDataId' => $row['PROCESS_META_DATA_ID'],
                                                'metaTypeId' => $row['META_TYPE_ID'],
                                                'type' => 'toolbar',
                                                'element' => 'this', 
                                                'passPath' => $row['PASSWORD_PATH'] 
                                            );
                                            
                                            if (!empty($row['SHOW_POSITION'])) {
                                                $batchTopBtn .= Html::anchor(
                                                    'javascript:;', (($row['ICON_NAME'] != '') ? '<i class="far ' . $row['ICON_NAME'] . '"></i> ' : '') . Lang::line($row['PROCESS_NAME']), array(
                                                    'onclick' => 'transferProcessAction(\'' . $row['PASSWORD_PATH'] . '\', \'' . $metaDataId . '\', \'' . $row['PROCESS_META_DATA_ID'] . '\', \'' . $row['META_TYPE_ID'] . '\', \'toolbar\', this, {callerType: \''.$metaDataCode.'\'}, undefined, undefined, undefined, undefined, \''. $dataviewUniqId .'\');', 
                                                    'title' => Lang::line($row['ICON_PROCESS_NAME']),
                                                    'data-actiontype' => $actionType,         
                                                    'data-advanced-criteria' => $row['ADVANCED_CRITERIA']   
                                                    ), true
                                                );          
                                            }                                            

                                            if ($row['COUNT_GET'] != '0' || $row['POST_PARAM'] != '') {
                                                array_push($commandContext, array(
                                                        'PROCESS_META_DATA_ID' => $row['PROCESS_META_DATA_ID'], 
                                                        'PROCESS_NAME' => $row['PROCESS_NAME'],
                                                        'META_TYPE_ID' => $row['META_TYPE_ID'],
                                                        'STANDART_ACTION' => 'processCriteria',
                                                        'ICON_NAME' => $row['ICON_NAME'], 
                                                        'BUTTON_STYLE' => issetParam($row['BUTTON_STYLE']),
                                                        'PASSWORD_PATH' => $row['PASSWORD_PATH'], 
                                                        'ORDER_NUM' => $row['ORDER_NUM'], 
                                                        'CRITERIA' => $row['CRITERIA'],
                                                        'ADVANCED_CRITERIA' => $row['ADVANCED_CRITERIA']    
                                                    )
                                                );
                                            } else {
                                                array_push($commandAddMeta, array(
                                                        'PROCESS_META_DATA_ID' => $row['PROCESS_META_DATA_ID'], 
                                                        'PROCESS_NAME' => $row['PROCESS_NAME'],
                                                        'META_TYPE_ID' => $row['META_TYPE_ID'],
                                                        'STANDART_ACTION' => 'processCriteria',
                                                        'ICON_NAME' => $row['ICON_NAME'], 
                                                        'PASSWORD_PATH' => $row['PASSWORD_PATH'], 
                                                        'ORDER_NUM' => $row['ORDER_NUM'], 
                                                        'CRITERIA' => $row['CRITERIA'],
                                                        'ADVANCED_CRITERIA' => $row['ADVANCED_CRITERIA'], 
                                                        'IS_MAIN' => $row['IS_MAIN']
                                                    )
                                                );
                                            }

                                            if ($row['ACTION_TYPE'] == 'delete') {
                                                $deleteProcess[] = array(
                                                    'id' => $row['ID'], 
                                                    'processId' => $row['PROCESS_META_DATA_ID'], 
                                                    'processName' => Lang::line($row['PROCESS_NAME']), 
                                                    'icon' => $row['ICON_NAME'],
                                                    'criteria' => $row['CRITERIA']
                                                );
                                            }
                                            
                                            if ($row['IS_BP_OPEN_DEFAULT'] == '1') {
                                                array_push($openDefaultBp, array(
                                                    'fn' => 'transferProcessAction(\'' . $row['PASSWORD_PATH'] . '\', \'' . $metaDataId . '\', \'' . $row['PROCESS_META_DATA_ID'] . '\', \'' . $row['META_TYPE_ID'] . '\', \'toolbar\', this, {callerType: \''.$metaDataCode.'\'}, undefined, undefined, undefined, undefined, \''. $dataviewUniqId .'\');',
                                                    'action' => $actionType
                                                ));
                                            }                                            

                                        } else {
                                            
                                            $batchBtn .= Html::anchor(
                                                'javascript:;', ((!empty($row['ICON_NAME'])) ? '<i class="far ' . $row['ICON_NAME'] . '"></i> ' : '') . Lang::line($row['PROCESS_NAME']), array(
                                                'onclick' => 'transferProcessAction(\'' . $row['PASSWORD_PATH'] . '\', \'' . $metaDataId . '\', \'' . $row['PROCESS_META_DATA_ID'] . '\', \'' . $row['META_TYPE_ID'] . '\', \'processCriteria\', this, {callerType: \''.$metaDataCode.'\'}, undefined, undefined, undefined, undefined, \''. $dataviewUniqId .'\');', 
                                                'title' => Lang::line($row['ICON_PROCESS_NAME']), 
                                                'data-actiontype' => $actionType,     
                                                'data-dvbtn-processcode' => $row['META_DATA_CODE'], 
                                                'data-simple-criteria' => $row['CRITERIA'],
                                                'data-advanced-criteria' => $row['ADVANCED_CRITERIA'],
                                                ), true
                                            );
                                            $commandFunction = array(
                                                'functionName' => 'transferProcessAction',
                                                'metaDataId' => $metaDataId,
                                                'processMetaDataId' => $row['PROCESS_META_DATA_ID'],
                                                'metaTypeId' => $row['META_TYPE_ID'],
                                                'type' => 'processCriteria',
                                                'element' => 'this',
                                                'passPath' => $row['PASSWORD_PATH']
                                            );
                                            if (!empty($row['SHOW_POSITION'])) {
                                                $batchTopBtn .= Html::anchor(
                                                    'javascript:;', ((!empty($row['ICON_NAME'])) ? '<i class="far ' . $row['ICON_NAME'] . '"></i> ' : '') . Lang::line($row['PROCESS_NAME']), array(
                                                    'onclick' => 'transferProcessAction(\'' . $row['PASSWORD_PATH'] . '\', \'' . $metaDataId . '\', \'' . $row['PROCESS_META_DATA_ID'] . '\', \'' . $row['META_TYPE_ID'] . '\', \'processCriteria\', this, {callerType: \''.$metaDataCode.'\'}, undefined, undefined, undefined, undefined, \''. $dataviewUniqId .'\');', 
                                                    'title' => Lang::line($row['ICON_PROCESS_NAME']), 
                                                    'data-actiontype' => $actionType,     
                                                    'data-dvbtn-processcode' => $row['META_DATA_CODE'], 
                                                    'data-advanced-criteria' => $row['ADVANCED_CRITERIA'],
                                                    ), true
                                                ); 
                                            }                                              
                                            array_push($commandContext, array(
                                                    'PROCESS_META_DATA_ID' => $row['PROCESS_META_DATA_ID'], 
                                                    'PROCESS_NAME' => $row['PROCESS_NAME'],
                                                    'META_TYPE_ID' => $row['META_TYPE_ID'],
                                                    'STANDART_ACTION' => 'processCriteria',
                                                    'ICON_NAME' => $row['ICON_NAME'], 
                                                    'BUTTON_STYLE' => issetParam($row['BUTTON_STYLE']), 
                                                    'PASSWORD_PATH' => $row['PASSWORD_PATH'], 
                                                    'ORDER_NUM' => $row['ORDER_NUM'], 
                                                    'CRITERIA' => $row['CRITERIA'],
                                                    'ADVANCED_CRITERIA' => $row['ADVANCED_CRITERIA']    	
                                                )
                                            );

                                            if ($row['ACTION_TYPE'] == 'delete') {
                                                $deleteProcess[] = array(
                                                    'id' => $row['ID'], 
                                                    'processId' => $row['PROCESS_META_DATA_ID'], 
                                                    'processName' => Lang::line($row['PROCESS_NAME']), 
                                                    'icon' => $row['ICON_NAME'],
                                                    'criteria' => $row['CRITERIA']
                                                );
                                            }
                                            
                                            if ($row['IS_BP_OPEN_DEFAULT'] == '1') {
                                                array_push($openDefaultBp, array(
                                                    'fn' => 'transferProcessAction(\'' . $row['PASSWORD_PATH'] . '\', \'' . $metaDataId . '\', \'' . $row['PROCESS_META_DATA_ID'] . '\', \'' . $row['META_TYPE_ID'] . '\', \'processCriteria\', this, {callerType: \''.$metaDataCode.'\'}, undefined, undefined, undefined, undefined, \''. $dataviewUniqId .'\');',
                                                    'action' => $actionType
                                                ));
                                            }                                            
                                        }
                                        
                                        $batchBtn .= '</li>';
                                        $batchTopBtn .= '</li>';

                                        $batchProcess[$row['PROCESS_META_DATA_ID']] = $row['PROCESS_META_DATA_ID'];
                                    }
                                }

                                $batchBtn .= '</ul>';
                                $batchBtn .= '</div>';
                                $batchTopBtn .= '</ul>';
                                $batchTopBtn .= '</div>';

                                $commandSort[$batch['BATCH_NUMBER'].'.0'] = $batchBtn;
                                $commandPositionSort[$batch['BATCH_NUMBER'].'.0'] = array(
                                    'position' => 'top',
                                    'html' => $batchTopBtn
                                );
                            }

                        } else {

                            if (!($checkPopup && $batch['IS_SHOW_POPUP'] != '1')) {
                                
                                $isDeleteProcess = false;
                                
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

                                        if ($row['ACTION_TYPE'] == 'delete') {
                                            $deleteProcess[] = array(
                                                'id' => $row['ID'], 
                                                'processId' => $row['PROCESS_META_DATA_ID'], 
                                                'processName' => Lang::line($row['PROCESS_NAME']), 
                                                'icon' => $row['ICON_NAME'],
                                                'criteria' => $row['CRITERIA'] 
                                            );
                                            
                                            $isDeleteProcess = true;
                                        }
                                    }
                                    
                                    $commandFunction = array(
                                        'functionName' => 'transferProcessAction',
                                        'metaDataId' => $metaDataId,
                                        'processMetaDataId' => $row['PROCESS_META_DATA_ID'],
                                        'metaTypeId' => $row['META_TYPE_ID'],
                                        'type' => 'toolbar',
                                        'element' => 'this', 
                                        'passPath' => $row['PASSWORD_PATH'] 
                                    );                                    
                                }
                                
                                $batchBtn .= Html::anchor(
                                    'javascript:;', (($batch['ICON_NAME'] != '') ? '<i class="far ' . $batch['ICON_NAME'] . '"></i> ' : '') . Lang::line($batch['BATCH_NAME']), array(
                                        'class' => 'btn ' . (isset($batch['BUTTON_STYLE']) ? $batch['BUTTON_STYLE'] : 'btn-secondary') . ' btn-circle btn-sm',
                                        'onclick' => 'transferProcessCriteria(\'' . $metaDataId . '\', \'' . $batch['BATCH_NUMBER'] . '\', \'toolbar\', this, {callerType: \''.$metaDataCode.'\'}, \''. $dataviewUniqId .'\');', 
                                        'data-actiontype' => ($isDeleteProcess ? 'delete' : 'action'),
                                        'data-advanced-criteria' => $row['ADVANCED_CRITERIA'] 
                                    ), true
                                );

                                if (empty($commandFunction)) {
                                    $commandFunction = array(
                                        'functionName' => 'transferProcessCriteria',
                                        'metaDataId' => $metaDataId,
                                        'processMetaDataId' => $batch['BATCH_NUMBER'],
                                        'type' => 'toolbar',
                                        'element' => 'this',
                                    );                           
                                }
                                
                                array_push($commandContext, array(
                                        'PROCESS_NAME' => $batch['BATCH_NAME'],
                                        'BATCH_NUMBER' => $batch['BATCH_NUMBER'],
                                        'STANDART_ACTION' => 'criteria',
                                        'BUTTON_STYLE' => issetParam($row['BUTTON_STYLE']), 
                                        'ICON_NAME' => $batch['ICON_NAME'], 
                                        'ORDER_NUM' => $batch['BATCH_NUMBER'], 
                                        'CRITERIA' => $row['CRITERIA'],
                                        'ADVANCED_CRITERIA' => $row['ADVANCED_CRITERIA']        
                                    )
                                );

                                $commandSort[$batch['BATCH_NUMBER'].'.0'] = $batchBtn;
                            }
                        }
                    }
                }
                         
                foreach ($dataViewProcess as $k => $row) {
                    
                    if (!in_array($row['PROCESS_META_DATA_ID'], $batchProcess)) {
                        
                        if (!($checkPopup && $row['IS_SHOW_POPUP'] != '1')) {
                            
                            if ($row['META_DATA_CODE'] == 'pfChangeWfmStatus') {
                                $commandSort[$k] = '<!--changewfmstatus-->';
                                continue;
                            }
                            
                            if ($row['META_DATA_CODE'] == 'pfExcelExportButton') {
                                $commandSort[$k] = '<!--excelexportbutton-->';
                                continue;
                            }
                            
                            if ($row['META_DATA_CODE'] == 'pfInvoicePrintButton') {
                                $commandSort[$k] = '<!--invoiceprintbutton-->';
                                continue;
                            }
                            
                            if ($row['META_DATA_CODE'] == 'pfAddEditLogView' || $row['META_DATA_CODE'] == 'pfRemovedLogView' || $row['META_DATA_CODE'] == 'pfRestoreDeletedData') {
                                
                                $lowerCode = strtolower($row['META_DATA_CODE']);
                                
                                $commandSort[$row['META_DATA_CODE']] = '<!--'.$lowerCode.'-->';

                                if ($row['SHOW_POSITION']) {
                                    $commandPositionSort[$k] = array(
                                        'position' => $row['SHOW_POSITION'],
                                        'html' => '<!--'.$lowerCode.'-->',
                                    );      
                                }
                                
                                continue;
                            }
                            
                            $addonClass = '';
                            $actionType = $row['ACTION_TYPE'];

                            if ($actionType == '' && !$row['COUNT_GET'] && $row['META_TYPE_ID'] == Mdmetadata::$businessProcessMetaTypeId) {
                                $actionType = 'insert';
                            } elseif ($actionType == 'delete' && $row['COUNT_GET'] && $row['META_TYPE_ID'] == Mdmetadata::$businessProcessMetaTypeId) {
                                $actionType = 'delete';
                            } elseif (
                                ($row['COUNT_GET'] && $actionType == 'insert' && $row['META_TYPE_ID'] == Mdmetadata::$businessProcessMetaTypeId)  || 
                                ($row['COUNT_GET'] && $row['META_TYPE_ID'] == Mdmetadata::$businessProcessMetaTypeId)) {
                                $actionType = 'update';
                            } 
                            
                            if ($row['IS_SHOW_ROWSELECT'] == '1') {
                                $isShowRowSelect = true;
                                $addonClass = ' d-none dv-bp-btn-visible';
                            }
                                
                            if ($row['IS_BP_OPEN'] == '1') {
                                $addonClass .= ' is-open-bp-'.$metaDataId;
                            }                            
                            
                            if ($row['META_TYPE_ID'] == Mdmetadata::$taskFlowMetaTypeId) {
                                
                                $whereFrom = (empty($row['CRITERIA']) ? 'toolbar' : 'processCriteria');
                                
                                $dropdownBtn = '<div class="btn-group dv-buttons-batch">';
                                    $dropdownBtn .= '<button class="btn ' . (isset($row['BUTTON_STYLE']) ? $row['BUTTON_STYLE'] : 'btn-secondary') . ' btn-circle btn-sm dropdown-toggle" type="button" data-toggle="dropdown">';
                                    $dropdownBtn .= (($row['ICON_NAME'] != '') ? '<i class="far ' . $row['ICON_NAME'] . '"></i> ' : '<i class="icon-plus3 font-size-12"></i> ') . Lang::line($row['PROCESS_NAME']);
                                    $dropdownBtn .= '</button>';
                                    $dropdownBtn .= '<ul class="dropdown-menu" role="menu">';

                                        $dropdownBtn .= '<li class="'.$addonClass.'">';
                                        
                                        $dropdownBtn .= Html::anchor(
                                            'javascript:;', '<i class="far fa-plus"></i> ' . Lang::line('add_btn'), array(
                                            'onclick' => 'transferProcessAction(\'' . $row['PASSWORD_PATH'] . '\', \'' . $metaDataId . '\', \'' . $row['PROCESS_META_DATA_ID'] . '\', \'' . $row['META_TYPE_ID'] . '\', \''.$whereFrom.'\', this, {callerType: \''.$metaDataCode.'\'}, undefined, undefined, undefined, undefined, \''. $dataviewUniqId .'\');', 
                                            'data-actiontype' => $actionType,         
                                            'data-advanced-criteria' => $row['ADVANCED_CRITERIA'],
                                            'data-dvbtn-processcode' => $row['META_DATA_CODE']  
                                            ), true
                                        );
                                        $dropdownBtn .= Html::anchor(
                                            'javascript:;', '<i class="far fa-arrow-from-left"></i> ' . Lang::line('continue_btn'), array(
                                            'onclick' => 'transferProcessAction(\'' . $row['PASSWORD_PATH'] . '\', \'' . $metaDataId . '\', \'' . $row['PROCESS_META_DATA_ID'] . '\', \'' . $row['META_TYPE_ID'] . '\', \'processCriteria\', this, {callerType: \''.$metaDataCode.'\'}, undefined, undefined, undefined, undefined, \''. $dataviewUniqId .'\');', 
                                            'data-actiontype' => $actionType,         
                                            'data-advanced-criteria' => $row['ADVANCED_CRITERIA'], 
                                            'data-dvbtn-processcode' => $row['META_DATA_CODE']     
                                            ), true
                                        );
                                        $dropdownBtn .= Html::anchor(
                                            'javascript:;', '<i class="far fa-history"></i> ' . Lang::line('Түүх харах'), array(
                                            'onclick' => 'viewTaskFlowLog(this, \''.$metaDataId.'\', \''.$metaDataCode.'\', \''.$dataviewUniqId.'\', \''.$row['PROCESS_META_DATA_ID'].'\', \''.$row['META_DATA_CODE'].'\');'  
                                            ), true
                                        );
                                        
                                        $commandFunction = array(
                                            'functionName' => 'transferProcessAction',
                                            'metaDataId' => $metaDataId,
                                            'processMetaDataId' => $row['PROCESS_META_DATA_ID'],
                                            'metaTypeId' => $row['META_TYPE_ID'],
                                            'type' => $whereFrom,
                                            'element' => 'this', 
                                            'passPath' => $row['PASSWORD_PATH'], 
                                            'buttonStyle' => $row['BUTTON_STYLE'], 
                                            'iconName' => $row['ICON_NAME']
                                        );
                                            
                                        $dropdownBtn .= '</li>';
                                        
                                    $dropdownBtn .= '</ul>';
                                $dropdownBtn .= '</div>';
                                
                                $commandSort[$k] = $dropdownBtn;
                                
                                continue;
                            }
                            
                            if ($row['IS_CONTEXT_MENU'] == '1') {
                                
                                array_push($commandContext, array(
                                        'PROCESS_META_DATA_ID' => $row['PROCESS_META_DATA_ID'], 
                                        'PROCESS_NAME' => $row['PROCESS_NAME'],
                                        'META_TYPE_ID' => $row['META_TYPE_ID'],
                                        'STANDART_ACTION' => 'processCriteria',
                                        'ICON_NAME' => $row['ICON_NAME'], 
                                        'PASSWORD_PATH' => $row['PASSWORD_PATH'], 
                                        'BUTTON_STYLE' => issetParam($row['BUTTON_STYLE']), 
                                        'ORDER_NUM' => $row['ORDER_NUM'], 
                                        'CRITERIA' => $row['CRITERIA'],
                                        'ADVANCED_CRITERIA' => $row['ADVANCED_CRITERIA'], 
                                        'IS_MAIN' => $row['IS_MAIN']
                                    )
                                );
                                
                                continue;
                            }
                            
                            if (empty($row['CRITERIA']) && empty($row['POST_PARAM']) && ($row['ICON_NAME'] != '' || $row['PROCESS_NAME'] != '')) {
                                
                                $commandAnchor = Html::anchor(
                                    'javascript:;', (($row['ICON_NAME'] != '') ? '<i class="far ' . $row['ICON_NAME'] . '" style="color:' . $row['ICON_COLOR'] . '"></i> ' : '') . Lang::line($row['PROCESS_NAME']), array(
                                        'class' => 'btn ' . (isset($row['BUTTON_STYLE']) ? $row['BUTTON_STYLE'] : 'btn-secondary') . ' btn-circle btn-sm' . $addonClass,
                                        'title' => !empty($row['ICON_PROCESS_NAME']) ? Lang::line($row['ICON_PROCESS_NAME']) : Lang::line($row['META_DATA_NAME']),
                                        'data-advanced-criteria' => $row['ADVANCED_CRITERIA'],
                                        'data-simple-criteria' => $row['CRITERIA'],
                                        'onclick' => 'transferProcessAction(\'' . $row['PASSWORD_PATH'] . '\', \'' . $metaDataId . '\', \'' . $row['PROCESS_META_DATA_ID'] . '\', \'' . $row['META_TYPE_ID'] . '\', \'toolbar\', this, {callerType: \''.$metaDataCode.'\'}, undefined, undefined, undefined, undefined, \''. $dataviewUniqId .'\');', 
                                        'data-actiontype' => $actionType,                                        
                                        'data-dvbtn-processcode' => $row['META_DATA_CODE'], 
                                        'data-ismain' => $row['IS_MAIN']
                                    ), true
                                );
                                
                                $commandSort[$k] = $commandAnchor;

                                if (!empty($row['SHOW_POSITION'])) {

                                    $commandPositionSort[$k] = array(
                                        'position' => $row['SHOW_POSITION'],
                                        'html' => $commandAnchor
                                    );
                                }

                                $commandFunction = array(
                                    'functionName' => 'transferProcessAction',
                                    'metaDataId' => $metaDataId,
                                    'processMetaDataId' => $row['PROCESS_META_DATA_ID'],
                                    'metaTypeId' => $row['META_TYPE_ID'],
                                    'type' => 'toolbar',
                                    'element' => 'this',
                                    'passPath' => $row['PASSWORD_PATH'], 
                                    'buttonStyle' => $row['BUTTON_STYLE'], 
                                    'iconName' => $row['ICON_NAME']
                                );

                                if ($row['COUNT_GET'] != '0' || $row['POST_PARAM'] != '') {
                                    array_push($commandContext, array(
                                            'PROCESS_META_DATA_ID' => $row['PROCESS_META_DATA_ID'], 
                                            'PROCESS_NAME' => $row['PROCESS_NAME'],
                                            'META_TYPE_ID' => $row['META_TYPE_ID'],
                                            'STANDART_ACTION' => 'processCriteria',
                                            'ICON_NAME' => $row['ICON_NAME'], 
                                            'PASSWORD_PATH' => $row['PASSWORD_PATH'], 
                                            'BUTTON_STYLE' => issetParam($row['BUTTON_STYLE']), 
                                            'ORDER_NUM' => $row['ORDER_NUM'], 
                                            'CRITERIA' => $row['CRITERIA'],
                                            'ADVANCED_CRITERIA' => $row['ADVANCED_CRITERIA'], 
                                            'IS_MAIN' => $row['IS_MAIN']
                                        )
                                    );
                                } else {
                                    array_push($commandAddMeta, array(
                                            'PROCESS_META_DATA_ID' => $row['PROCESS_META_DATA_ID'], 
                                            'PROCESS_NAME' => $row['PROCESS_NAME'],
                                            'META_TYPE_ID' => $row['META_TYPE_ID'],
                                            'STANDART_ACTION' => 'processCriteria',
                                            'ICON_NAME' => $row['ICON_NAME'], 
                                            'PASSWORD_PATH' => $row['PASSWORD_PATH'], 
                                            'ORDER_NUM' => $row['ORDER_NUM'], 
                                            'CRITERIA' => $row['CRITERIA'],
                                            'ADVANCED_CRITERIA' => $row['ADVANCED_CRITERIA'], 
                                            'IS_MAIN' => $row['IS_MAIN']
                                        )
                                    );
                                }
                                
                                if ($row['IS_BP_OPEN_DEFAULT'] == '1') {
                                    array_push($openDefaultBp, array(
                                        'fn' => 'transferProcessAction(\'' . $row['PASSWORD_PATH'] . '\', \'' . $metaDataId . '\', \'' . $row['PROCESS_META_DATA_ID'] . '\', \'' . $row['META_TYPE_ID'] . '\', \'toolbar\', this, {callerType: \''.$metaDataCode.'\'}, undefined, undefined, undefined, undefined, \''. $dataviewUniqId .'\');',
                                        'action' => $actionType
                                    ));
                                }                                

                            } elseif ($row['ICON_NAME'] != '' || $row['PROCESS_NAME'] != '') {
                                
                                $commandSort[$k] = Html::anchor(
                                    'javascript:;', (($row['ICON_NAME'] != '') ? '<i class="far ' . $row['ICON_NAME'] . '"></i> ' : '') . Lang::line($row['PROCESS_NAME']), array(
                                        'class' => 'btn ' . (isset($row['BUTTON_STYLE']) ? $row['BUTTON_STYLE'] : 'btn-secondary') . ' btn-circle btn-sm' . $addonClass,
                                        'title' => !empty($row['ICON_PROCESS_NAME']) ? Lang::line($row['ICON_PROCESS_NAME']) : Lang::line($row['META_DATA_NAME']),
                                        'onclick' => 'transferProcessAction(\'' . $row['PASSWORD_PATH'] . '\', \'' . $metaDataId . '\', \'' . $row['PROCESS_META_DATA_ID'] . '\', \'' . $row['META_TYPE_ID'] . '\', \'processCriteria\', this, {callerType: \''.$metaDataCode.'\'}, undefined, undefined, undefined, undefined, \''. $dataviewUniqId .'\');', 
                                        'data-actiontype' => $actionType,
                                        'data-advanced-criteria' => $row['ADVANCED_CRITERIA'],
                                        'data-simple-criteria' => $row['CRITERIA'],
                                        'data-dvbtn-processcode' => $row['META_DATA_CODE'], 
                                        'data-dvbtn-position' => $row['SHOW_POSITION'], 
                                        'data-ismain' => $row['IS_MAIN']
                                    ), true
                                );
                                if (!empty($row['SHOW_POSITION'])) {

                                    $commandPositionSort[$k] = array(
                                        'position' => $row['SHOW_POSITION'],
                                        'html' => Html::anchor(
                                            'javascript:;', (($row['ICON_NAME'] != '') ? '<i class="far ' . $row['ICON_NAME'] . '"></i> ' : '') . Lang::line($row['PROCESS_NAME']), array(
                                                'class' => 'btn ' . (isset($row['BUTTON_STYLE']) ? $row['BUTTON_STYLE'] : 'btn-secondary') . ' btn-circle btn-sm' . $addonClass,
                                                'title' => !empty($row['ICON_PROCESS_NAME']) ? Lang::line($row['ICON_PROCESS_NAME']) : Lang::line($row['META_DATA_NAME']),
                                                'onclick' => 'transferProcessAction(\'' . $row['PASSWORD_PATH'] . '\', \'' . $metaDataId . '\', \'' . $row['PROCESS_META_DATA_ID'] . '\', \'' . $row['META_TYPE_ID'] . '\', \'processCriteria\', this, {callerType: \''.$metaDataCode.'\'}, undefined, undefined, undefined, undefined, \''. $dataviewUniqId .'\');', 
                                                'data-actiontype' => $actionType,
                                                'data-advanced-criteria' => $row['ADVANCED_CRITERIA'],
                                                'data-simple-criteria' => $row['CRITERIA'],
                                                'data-dvbtn-processcode' => $row['META_DATA_CODE'], 
                                                'data-ismain' => $row['IS_MAIN']
                                            ), true
                                        )
                                    );      
                                }                          

                                $commandFunction = array(
                                    'functionName' => 'transferProcessAction',
                                    'metaDataId' => $metaDataId,
                                    'processMetaDataId' => $row['PROCESS_META_DATA_ID'],
                                    'metaTypeId' => $row['META_TYPE_ID'],
                                    'type' => 'processCriteria',
                                    'element' => 'this', 
                                    'passPath' => $row['PASSWORD_PATH'], 
                                    'buttonStyle' => $row['BUTTON_STYLE'], 
                                    'iconName' => $row['ICON_NAME']
                                );

                                array_push($commandContext, array(
                                        'PROCESS_META_DATA_ID' => $row['PROCESS_META_DATA_ID'], 
                                        'PROCESS_NAME' => $row['PROCESS_NAME'],
                                        'META_TYPE_ID' => $row['META_TYPE_ID'],
                                        'STANDART_ACTION' => 'processCriteria',
                                        'ICON_NAME' => $row['ICON_NAME'], 
                                        'BUTTON_STYLE' => issetParam($row['BUTTON_STYLE']), 
                                        'PASSWORD_PATH' => $row['PASSWORD_PATH'], 
                                        'ORDER_NUM' => $row['ORDER_NUM'], 
                                        'CRITERIA' => $row['CRITERIA'],
                                        'ADVANCED_CRITERIA' => $row['ADVANCED_CRITERIA'], 
                                        'IS_MAIN' => $row['IS_MAIN']
                                    )
                                );
                                
                                if ($row['IS_BP_OPEN_DEFAULT'] == '1') {
                                    array_push($openDefaultBp, array(
                                        'fn' => 'transferProcessAction(\'' . $row['PASSWORD_PATH'] . '\', \'' . $metaDataId . '\', \'' . $row['PROCESS_META_DATA_ID'] . '\', \'' . $row['META_TYPE_ID'] . '\', \'processCriteria\', this, {callerType: \''.$metaDataCode.'\'}, undefined, undefined, undefined, undefined, \''. $dataviewUniqId .'\');',
                                        'action' => $actionType
                                    ));
                                }                  
                                
                            } elseif ($row['IS_BP_OPEN'] == '1' && $row['IS_BP_OPEN_DEFAULT'] == '1') {
                                
                                array_push($openDefaultBp, array(
                                    'fn' => 'transferProcessAction(\'' . $row['PASSWORD_PATH'] . '\', \'' . $metaDataId . '\', \'' . $row['PROCESS_META_DATA_ID'] . '\', \'' . $row['META_TYPE_ID'] . '\', \'processCriteria\', this, {callerType: \''.$metaDataCode.'\'}, undefined, undefined, undefined, undefined, \''. $dataviewUniqId .'\');',
                                    'action' => $actionType
                                ));
                            }
                        }

                        if ($row['ACTION_TYPE'] == 'delete') {
                            $deleteProcess[] = array(
                                'id' => $row['ID'], 
                                'processId' => $row['PROCESS_META_DATA_ID'], 
                                'processName' => Lang::line($row['PROCESS_NAME']), 
                                'icon' => $row['ICON_NAME'],
                                'criteria' => (isset($row['CRITERIA']) && $row['CRITERIA']) ? $row['CRITERIA'] : ''
                            );
                        }
                    }
                }
                
                ksort($commandSort, SORT_NATURAL);
                ksort($commandPositionSort, SORT_NATURAL);
                
                if (!empty($commandSort)) {
                    
                    $cmdBtn .= '<div class="btn-group btn-group-devided" data-deleteprocess="'.htmlentities(json_encode($deleteProcess), ENT_QUOTES, 'UTF-8').'"><!--startbutton-->';
                    $cmdBtn .= implode('', $commandSort);                    
                    
                    $cmdBtn .= '<!--endbutton--></div>';
                }
                
                if (!empty($openDefaultBp)) {

                    foreach ($openDefaultBp as $rowBtn) {
                        $cmdBtn .= Html::anchor(
                            'javascript:;', '', array(
                                'class' => 'hidden is-open-bp-default-'.$metaDataId,
                                'onclick' => $rowBtn['fn'],
                                'data-actiontype' => $rowBtn['action']
                            ), true
                        );
                    }

                    $openDefaultBp = '1';

                } else {
                    $openDefaultBp = '';
                }                
            }
            
            if ($cmdBtn === '' && !$commandFunction) {
                $dataViewWorkFlowProcess = self::getDataViewProcess($metaDataId, $getAccessProcess, '');

                if ($dataViewWorkFlowProcess) {
                    foreach ($dataViewWorkFlowProcess as $row) {
                        
                        if (empty($row['CRITERIA']) && empty($row['POST_PARAM']) && ($row['ICON_NAME'] != '' || $row['PROCESS_NAME'] != '')) {
                            
                            $commandFunction = array(
                                'functionName' => 'transferProcessAction',
                                'metaDataId' => $metaDataId,
                                'processMetaDataId' => $row['PROCESS_META_DATA_ID'],
                                'metaTypeId' => $row['META_TYPE_ID'],
                                'type' => 'toolbar',
                                'element' => 'this',
                                'passPath' => $row['PASSWORD_PATH'], 
                                'buttonStyle' => $row['BUTTON_STYLE'], 
                                'iconName' => $row['ICON_NAME']
                            );

                        } elseif ($row['ICON_NAME'] != '' || $row['PROCESS_NAME'] != '') {

                            $commandFunction = array(
                                'functionName' => 'transferProcessAction',
                                'metaDataId' => $metaDataId,
                                'processMetaDataId' => $row['PROCESS_META_DATA_ID'],
                                'metaTypeId' => $row['META_TYPE_ID'],
                                'type' => 'processCriteria',
                                'element' => 'this', 
                                'passPath' => $row['PASSWORD_PATH'], 
                                'buttonStyle' => $row['BUTTON_STYLE'], 
                                'iconName' => $row['ICON_NAME']
                            );
                        }
                    }
                }
            }
        }

        $resultData = array(
            'commandBtn'         => $cmdBtn, 
            'commandFunction'    => $commandFunction, 
            'commandContext'     => $commandContext, 
            'commandAddMeta'     => $commandAddMeta, 
            'isLifeCycle'        => $isDataViewLifeCycle, 
            'isBpOpen'           => $openDefaultBp, 
            'commandBtnPosition' => $commandPositionSort, 
            'isShowRowSelect'    => $isShowRowSelect
        );

        return $resultData;
    }

    public function dataViewSingleProcessCommandModel($metaDataId, $processId, $isEdit){
        $commandBtn = $cmdBtn = '';

        if (is_array($processId)) {
            $uniqId = Input::postCheck('uniqId') ? Input::post('uniqId') : $metaDataId;

            if (!$isEdit) {
                foreach ($processId as $proId) {
                    $row = self::getButtonByProcessId($metaDataId, $proId);
                    if ($row) {
                        $commandBtn .= Html::anchor(
                            'javascript:;', (($row['ICON_NAME'] != "") ? '<i class="far ' . $row['ICON_NAME'] . '"></i> ' : '') . Lang::line($row['PROCESS_NAME']), array(
                                'class' => 'btn ' . (isset($row['BUTTON_STYLE']) ? $row['BUTTON_STYLE'] : 'btn-secondary') . ' btn-circle btn-sm',
                                'title' => !empty($row['ICON_PROCESS_NAME']) ? $row['ICON_PROCESS_NAME'] : $row['META_DATA_NAME'],
                                'onclick' => 'glInvoiceProcessAction(\'' . $metaDataId . '\', \'' . $uniqId . '\', \'' . $row['PROCESS_META_DATA_ID'] . '\', \''.$isEdit.'\', this);'
                            ), true
                        );
                    }
                }
            } else {
                $commandBtn .= Html::anchor(
                    'javascript:;', '<i class="far fa-pencil-square"></i> ' . Lang::line('edit'), array(
                        'class' => 'btn btn-warning' . ' btn-circle btn-sm',
                        'title' => Lang::line('edit'),
                        'onclick' => 'glInvoiceProcessAction(\'' . $metaDataId . '\', \'' . $uniqId . '\', \''. htmlentities(json_encode($processId)).'\', \''.$isEdit.'\', this);'
                    ), true
                );
            }

        } else {
            $row = self::getButtonByProcessId($metaDataId, $processId);
            if ($row) {
                $uniqId = Input::postCheck('uniqId') ? Input::post('uniqId') : $metaDataId;
                $commandBtn .= Html::anchor(
                    'javascript:;', (($row['ICON_NAME'] != "") ? '<i class="far ' . $row['ICON_NAME'] . '"></i> ' : '') . Lang::line($row['PROCESS_NAME']), array(
                        'class' => 'btn ' . (isset($row['BUTTON_STYLE']) ? $row['BUTTON_STYLE'] : 'btn-secondary') . ' btn-circle btn-sm',
                        'title' => !empty($row['ICON_PROCESS_NAME']) ? $row['ICON_PROCESS_NAME'] : $row['META_DATA_NAME'],
                        'onclick' => 'glInvoiceProcessAction(\'' . $metaDataId . '\', \'' . $uniqId . '\', \'' . $row['PROCESS_META_DATA_ID'] . '\', \''.$isEdit.'\', this);'
                    ), true
                );
            }
        }

        if ($commandBtn != '') {
            $cmdBtn .= '<div class="btn-group btn-group-devided">';
            $cmdBtn .= $commandBtn;
            $cmdBtn .= '</div>';
        }

        return $cmdBtn;
    }

    public function getDataViewProcess($metaDataId, $accessProcess, $andWhere = 'AND (PRO.IS_WORKFLOW = 0 OR PRO.IS_WORKFLOW IS NULL) ') {
        
        $processIds = Arr::implode_key(',', $accessProcess, 'processid', true);
        
        if (!$processIds) {
            return null;
        }
        
        $join = '';
        
        if (Input::isEmpty('workSpaceId') == false && $workSpaceId = Input::numeric('workSpaceId')) {
                
            $join = "LEFT JOIN META_DM_PROCESS_IGNORE DPI ON DPI.MAIN_META_DATA_ID = PRO.MAIN_META_DATA_ID 
                AND DPI.TRG_META_DATA_ID = $workSpaceId 
                AND DPI.PROCESS_META_DATA_ID = PRO.PROCESS_META_DATA_ID ";

            $andWhere .= ' AND DPI.ID IS NULL ';
        }
        
        if (Input::isEmpty('runSrcMetaId') == false && $runSrcMetaId = Input::numeric('runSrcMetaId')) {
                
            $join .= "LEFT JOIN META_DM_PROCESS_IGNORE DPIS ON DPIS.MAIN_META_DATA_ID = PRO.MAIN_META_DATA_ID 
                AND DPIS.TRG_META_DATA_ID = $runSrcMetaId 
                AND DPIS.PROCESS_META_DATA_ID = PRO.PROCESS_META_DATA_ID ";

            $andWhere .= ' AND DPIS.ID IS NULL ';
        }
        
        $join .= "LEFT JOIN META_DM_PROCESS_IGNORE DPISN ON DPISN.MAIN_META_DATA_ID = PRO.MAIN_META_DATA_ID 
            AND DPISN.TRG_META_DATA_ID IS NULL  
            AND DPISN.PROCESS_META_DATA_ID = PRO.PROCESS_META_DATA_ID ";

        $andWhere .= ' AND DPISN.ID IS NULL ';
        
        $data = $this->db->GetAll("
            SELECT 
                PRO.ID, 
                PRO.PROCESS_META_DATA_ID, 
                PRO.PROCESS_NAME, 
                PRO.ICON_NAME, 
                PRO.BATCH_NUMBER,
                PRO.BUTTON_STYLE, 
                PRO.IS_SHOW_POPUP, 
                TRIM(PRO.CRITERIA) AS CRITERIA, 
                TRIM(PRO.ADVANCED_CRITERIA) AS ADVANCED_CRITERIA, 
                MD.META_DATA_CODE,
                MD.META_DATA_NAME,
                BPL.PROCESS_NAME AS ICON_PROCESS_NAME, 
                MD.META_TYPE_ID, 
                PRO.PASSWORD_PATH, 
                PRO.POST_PARAM, 
                PRO.ORDER_NUM, 
                (
                    SELECT 
                        COUNT(ID)   
                    FROM META_DM_TRANSFER_PROCESS TP 
                    WHERE TP.MAIN_META_DATA_ID = $metaDataId 
                        AND TP.PROCESS_META_DATA_ID = PRO.PROCESS_META_DATA_ID 
                ) AS COUNT_GET, 
                BPL.ACTION_TYPE,
                PRO.IS_BP_OPEN,
                PRO.ICON_COLOR,
                PRO.IS_BP_OPEN_DEFAULT, 
                PRO.SHOW_POSITION, 
                PRO.IS_SHOW_ROWSELECT, 
                PRO.IS_USE_PROCESS_TOOLBAR, 
                PRO.IS_PROCESS_TOOLBAR, 
                PRO.IS_MAIN, 
                PRO.IS_CONTEXT_MENU 
            FROM META_DM_PROCESS_DTL PRO 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = PRO.PROCESS_META_DATA_ID 
                LEFT JOIN META_BUSINESS_PROCESS_LINK BPL ON BPL.META_DATA_ID = MD.META_DATA_ID 
                $join 
            WHERE PRO.MAIN_META_DATA_ID = $metaDataId 
                AND (PRO.PROCESS_NAME IS NOT NULL OR (PRO.ICON_NAME IS NOT NULL AND PRO.ICON_NAME <> 'fa-') OR (PRO.IS_BP_OPEN = 1 AND PRO.IS_BP_OPEN_DEFAULT = 1)) 
                $andWhere
                AND PRO.PROCESS_META_DATA_ID IN ($processIds) 
            ORDER BY PRO.ORDER_NUM ASC");

        return $data;
    }

    public function getDataViewProcessCriteria($metaDataId, $accessProcess) {
        
        $processIds = Arr::implode_key(',', $accessProcess, 'processid', true);
        
        if ($processIds) {
            
            $data = $this->db->GetAll("
                SELECT 
                    TRIM(PRO.ADVANCED_CRITERIA) AS ADVANCED_CRITERIA
                FROM META_DM_PROCESS_DTL PRO
                    INNER JOIN META_DATA MD ON PRO.PROCESS_META_DATA_ID = MD.META_DATA_ID                    
                WHERE PRO.MAIN_META_DATA_ID = $metaDataId 
                    AND PRO.IS_WORKFLOW = 1
                    AND PRO.PROCESS_META_DATA_ID IN ($processIds) 
                ORDER BY PRO.ORDER_NUM ASC");
            
            return $data;
        } 
        
        return null;
    }

    public function getButtonByProcessId($metaDataId, $processId) {
        
        if ($metaDataId && $processId) {
            
            $data = $this->db->GetRow("
                SELECT 
                    PRO.PROCESS_META_DATA_ID, 
                    PRO.PROCESS_NAME, 
                    PRO.ICON_NAME, 
                    PRO.BATCH_NUMBER,
                    PRO.BUTTON_STYLE ,
                    PRO.IS_SHOW_POPUP, 
                    TRIM(PRO.CRITERIA) AS CRITERIA, 
                    MD.META_DATA_CODE,
                    MD.META_DATA_NAME,
                    BPL.PROCESS_NAME AS ICON_PROCESS_NAME, 
                    MD.META_TYPE_ID 
                FROM META_DM_PROCESS_DTL PRO
                    INNER JOIN META_DATA MD ON PRO.PROCESS_META_DATA_ID = MD.META_DATA_ID
                    LEFT JOIN META_BUSINESS_PROCESS_LINK BPL ON BPL.META_DATA_ID = MD.META_DATA_ID
                WHERE PRO.MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                   AND PRO.PROCESS_META_DATA_ID = ".$this->db->Param(1)." 
                ORDER BY PRO.ORDER_NUM ASC", array($metaDataId, $processId));

            return $data;
        }
        
        return null;
    }     

    public function getDataViewTransferProcess($metaDataId, $accessProcess) {
        $data = $this->db->GetAll("
            SELECT 
                PD.PROCESS_META_DATA_ID, 
                PD.PROCESS_NAME, 
                PD.ICON_NAME, 
                PD.ORDER_NUM, 
                PD.PASSWORD_PATH, 
                MD.META_TYPE_ID 
            FROM META_DM_PROCESS_DTL PD 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = PD.PROCESS_META_DATA_ID
                INNER JOIN META_DM_TRANSFER_PROCESS TP ON TP.MAIN_META_DATA_ID = PD.MAIN_META_DATA_ID AND TP.PROCESS_META_DATA_ID = PD.PROCESS_META_DATA_ID 
            WHERE PD.MAIN_META_DATA_ID = $metaDataId 
                AND PD.PROCESS_META_DATA_ID IN (".Arr::implode_key(',', $accessProcess, 'processid', true).") 
            ORDER BY PD.ORDER_NUM ASC");

        return $data;
    }
    
    public function getBpActionType($mainMetaDataIdPh, $processMetaDataIdPh, $bindVars) {
        
        $cache = phpFastCache();
        $row = $cache->get('dvBpActionType_'.$bindVars[0].'_'.$bindVars[1]);

        if ($row == null) {
            
            $row = $this->db->GetRow("
                SELECT 
                    PL.SUB_TYPE, 
                    PL.ACTION_TYPE, 
                    PL.METHOD_NAME, 
                    GBP.SUB_TYPE AS GET_SUB_TYPE, 
                    GBP.ACTION_TYPE AS GET_ACTION_TYPE, 
                    PD.IS_MULTI, 
                    PD.IS_CONFIRM, 
                    PD.POST_PARAM, 
                    PD.GET_PARAM, 
                    PD.IS_ROW_UPDATE, 
                    PD.ADVANCED_CRITERIA, 
                    " . $this->db->IfNull('PD.IS_BP_OPEN', '0') . " AS IS_BP_OPEN, 
                    MD.META_TYPE_ID 
                FROM META_DATA MD 
                    LEFT JOIN META_BUSINESS_PROCESS_LINK PL ON PL.META_DATA_ID = MD.META_DATA_ID 
                    LEFT JOIN META_DM_TRANSFER_PROCESS TP ON TP.MAIN_META_DATA_ID = $mainMetaDataIdPh 
                        AND TP.PROCESS_META_DATA_ID = PL.META_DATA_ID  
                    LEFT JOIN META_DM_PROCESS_DTL PD ON PD.MAIN_META_DATA_ID = $mainMetaDataIdPh 
                        AND PD.PROCESS_META_DATA_ID = $processMetaDataIdPh     
                    LEFT JOIN META_BUSINESS_PROCESS_LINK GBP ON GBP.META_DATA_ID = TP.GET_META_DATA_ID     
                WHERE MD.META_DATA_ID = $processMetaDataIdPh", $bindVars);
            
            $cache->set('dvBpActionType_'.$bindVars[0].'_'.$bindVars[1], $row, Mdwebservice::$expressionCacheTime);
        }
        
        return $row;
    }
    
    public function isGetDataProcess($mainMetaDataIdPh, $processMetaDataIdPh, $bindVars) {
        
        $cache = phpFastCache();
        $row = $cache->get('dvIsGetDataProcess_'.$bindVars[0].'_'.$bindVars[1]);
        
        if ($row == null) {
            
            $row = $this->db->GetRow("
                SELECT 
                    TP.ID, 
                    TP.GET_META_DATA_ID, 
                    PD.IS_MULTI, 
                    PD.IS_CONFIRM
                FROM META_DM_TRANSFER_PROCESS TP 
                    INNER JOIN META_DM_PROCESS_DTL PD ON PD.MAIN_META_DATA_ID = TP.MAIN_META_DATA_ID 
                        AND PD.PROCESS_META_DATA_ID = TP.PROCESS_META_DATA_ID 
                WHERE TP.MAIN_META_DATA_ID = $mainMetaDataIdPh 
                    AND TP.PROCESS_META_DATA_ID = $processMetaDataIdPh", $bindVars);
            
            $cache->set('dvIsGetDataProcess_'.$bindVars[0].'_'.$bindVars[1], $row, Mdwebservice::$expressionCacheTime);
        } 
        
        return $row;
    }
    
    public function getProcessActionType($getBpId) {
        
        $cache = phpFastCache();
        $row = $cache->get('bpActionType_'.$getBpId);
        
        if ($row == null) {
            $row = $this->db->GetRow("SELECT SUB_TYPE, ACTION_TYPE FROM META_BUSINESS_PROCESS_LINK WHERE META_DATA_ID = " . $this->db->Param(0), array($getBpId));
            $cache->set('bpActionType_'.$getBpId, $row, Mdwebservice::$expressionCacheTime);
        }
        
        return $row;
    }

    public function checkProcessActionModel($mainMetaDataId, $processMetaDataId, $metaTypeId, $selectedRow, $checkCriteria = true, $checkAdvancedCriteria = false) {
        
        $mainMetaDataIdPh    = $this->db->Param(0);
        $processMetaDataIdPh = $this->db->Param(1);

        $bindVars = array($mainMetaDataId, $processMetaDataId);
     
        if ($metaTypeId == Mdmetadata::$bookmarkMetaTypeId || $metaTypeId == Mdmetadata::$taskFlowMetaTypeId) {

            $response = array();                

            if (!empty($selectedRow) && $checkCriteria) {
                
                $getCriteria = $this->db->GetRow("
                    SELECT 
                        CRITERIA, 
                        PROCESS_NAME 
                    FROM META_DM_PROCESS_DTL 
                    WHERE MAIN_META_DATA_ID = $mainMetaDataIdPh 
                        AND PROCESS_META_DATA_ID = $processMetaDataIdPh 
                        AND (CRITERIA IS NOT NULL OR CRITERIA != '')", $bindVars);

                if ($getCriteria) {
                    
                    $explodedRules = explode('#', $getCriteria['CRITERIA']);
                    $headerRules = Str::lower(trim($explodedRules[0]));
                    
                    if (!array_key_exists(0, $selectedRow)) {
                        $selectedRow = array($selectedRow);
                    }
                    
                    foreach ($selectedRow as $rows) {
                        
                        if (isset($rows['children'])) {
                            unset($rows['children']);
                        }
                
                        if (isset($rows['pfnextstatuscolumn'])) {
                            unset($rows['pfnextstatuscolumn']);
                        }
                        
                        $rules = $headerRules;
                        
                        foreach ($rows as $sk => $sv) {

                            if (!is_array($sv)) {
                                
                                if (is_string($sv) && strpos($sv, "'") === false) {
                                    $sv = "'".Str::lower($sv)."'";
                                } elseif (is_null($sv)) {
                                    $sv = "''";
                                }
                                
                                $sk = ($sk == '' ? 'tmpkey' : $sk);

                                $rules = preg_replace('/\b'.$sk.'\b/u', $sv, $rules);
                            }
                        }

                        $rules = Mdmetadata::defaultKeywordReplacer($rules);
                        $rules = Mdmetadata::criteriaMethodReplacer($rules);

                        if (trim($rules) != '') {
                            ob_start();
                            if (!Mdcommon::expressionEvalFixWithReturn($rules)) {
                                $processNoAccessResult = array('processNoAccess' => true, 'processName' => Lang::line($getCriteria['PROCESS_NAME']));
                                if (isset($explodedRules[1])) {                                    
                                    $processNoAccessResult = array_merge($processNoAccessResult, array('processNoAccessMsg' => Lang::line($explodedRules[1])));
                                }
                                $response = $processNoAccessResult;
                            }
                            $rules = ob_get_contents();
                            ob_end_clean();
                        }   
                    }
                }
            } 
            
            if ($metaTypeId == Mdmetadata::$bookmarkMetaTypeId) {
                
                $getActionType = $this->db->GetRow("
                    SELECT 
                        BL.BOOKMARK_URL, 
                        BL.TARGET, 
                        PD.POST_PARAM, 
                        PD.GET_PARAM, 
                        TP.ID 
                    FROM META_BOOKMARK_LINKS BL 
                        LEFT JOIN META_DM_PROCESS_DTL PD ON PD.PROCESS_META_DATA_ID = BL.META_DATA_ID 
                            AND PD.MAIN_META_DATA_ID = $mainMetaDataIdPh
                        LEFT JOIN META_DM_TRANSFER_PROCESS TP ON PD.MAIN_META_DATA_ID = TP.MAIN_META_DATA_ID 
                            AND PD.PROCESS_META_DATA_ID = TP.PROCESS_META_DATA_ID 
                    WHERE BL.META_DATA_ID = $processMetaDataIdPh", $bindVars);
                
                $response['url'] = $getActionType['BOOKMARK_URL'];
                $response['urlTarget'] = $getActionType['TARGET'];
                $response['postParam'] = $getActionType['POST_PARAM'];
                $response['getParam'] = $getActionType['GET_PARAM'];
                $response['isGetData'] = ($getActionType['ID'] ? true : (!empty($getActionType['POST_PARAM']) ? true : false));
                
            } else {
                
                $getActionType = $this->db->GetRow("
                    SELECT 
                        TP.ID, 
                        PD.POST_PARAM, 
                        PD.GET_PARAM, 
                        PD.IS_ROW_UPDATE 
                    FROM META_DM_PROCESS_DTL PD 
                        LEFT JOIN META_DM_TRANSFER_PROCESS TP ON PD.MAIN_META_DATA_ID = TP.MAIN_META_DATA_ID 
                            AND PD.PROCESS_META_DATA_ID = TP.PROCESS_META_DATA_ID 
                    WHERE PD.MAIN_META_DATA_ID = $mainMetaDataIdPh 
                        AND PD.PROCESS_META_DATA_ID = $processMetaDataIdPh", $bindVars);
                
                $response['isTaskFlow'] = true;
                $response['postParam'] = $getActionType['POST_PARAM'];
                $response['getParam'] = $getActionType['GET_PARAM'];
                $response['isRowReload'] = $getActionType['IS_ROW_UPDATE'];
                $response['isGetData'] = ($getActionType['ID'] ? true : (!empty($getActionType['POST_PARAM']) ? true : false));
            }
            
            return array_merge(array('isMulti' => false, 'isConfirm' => false), $response);

        } else {
            
            $getActionType = self::getBpActionType($mainMetaDataIdPh, $processMetaDataIdPh, $bindVars);
           
            if ($getActionType) {

                $response = array();        

                if (!empty($selectedRow)) {
                    
                    if ($checkAdvancedCriteria && issetParam($getActionType['ADVANCED_CRITERIA'])) {
                        
                        return self::checkProcessAdvancedCriteriaModel($mainMetaDataId, $processMetaDataId, $metaTypeId, $selectedRow, Input::param($getActionType['ADVANCED_CRITERIA']));
                        
                    } elseif ($checkCriteria) {
                        
                        $getCriteria = $this->db->GetRow("
                            SELECT 
                                CRITERIA, 
                                PROCESS_NAME 
                            FROM META_DM_PROCESS_DTL 
                            WHERE MAIN_META_DATA_ID = $mainMetaDataIdPh 
                                AND PROCESS_META_DATA_ID = $processMetaDataIdPh 
                                AND (CRITERIA IS NOT NULL OR CRITERIA != '')", $bindVars);

                        if ($getCriteria) {

                            $explodedRules = explode('#', $getCriteria['CRITERIA']);
                            $headerRules = Str::lower(trim($explodedRules[0]));

                            if (!array_key_exists(0, $selectedRow)) {
                                $selectedRow = array($selectedRow);
                            }

                            foreach ($selectedRow as $rows) {

                                if (isset($rows['children'])) {
                                    unset($rows['children']);
                                }

                                if (isset($rows['pfnextstatuscolumn'])) {
                                    unset($rows['pfnextstatuscolumn']);
                                }

                                $rules = $headerRules;

                                foreach ($rows as $sk => $sv) {

                                    if (!is_array($sv)) {
                                        if (is_string($sv) && strpos($sv, "'") === false) {
                                            $sv = "'".Str::lower($sv)."'";
                                        } elseif (is_null($sv)) {
                                            $sv = "''";
                                        }
                                        
                                        $sk = ($sk == '' ? 'tmpkey' : $sk);

                                        $rules = preg_replace('/\b'.$sk.'\b/u', $sv, $rules);
                                    }
                                }

                                $rules = Mdmetadata::defaultKeywordReplacer($rules);
                                $rules = Mdmetadata::criteriaMethodReplacer($rules);

                                ob_start();

                                if ($rules != '' && !Mdcommon::expressionEvalFixWithReturn($rules)) {

                                    $processNoAccessResult = array('processNoAccess' => true, 'processName' => Lang::line($getCriteria['PROCESS_NAME']));

                                    if (isset($explodedRules[1])) {
                                        $processNoAccessResult = array_merge($processNoAccessResult, array('processNoAccessMsg' => Lang::line($explodedRules[1])));
                                    }

                                    $response = $processNoAccessResult;
                                }

                                $error = ob_get_clean();

                                if ($error != '' && !isset($response['processNoAccessMsg'])) {
                                    $response['processNoAccessMsg'] = Mdcommon::parseCodeErrorMsg($error);
                                }
                            }
                        }
                    }
                } 
                
                if (issetParam($getActionType['META_TYPE_ID']) == Mdmetadata::$workSpaceMetaTypeId) {
                    
                    $row = $this->db->GetRow("
                        SELECT 
                            WL.ID, 
                            WL.ACTION_TYPE 
                        FROM META_WORKSPACE_LINK WL 
                            INNER JOIN META_WORKSPACE_PARAM_MAP PM ON PM.WORKSPACE_META_ID = WL.META_DATA_ID 
                        WHERE WL.META_DATA_ID = $processMetaDataIdPh 
                            AND PM.TARGET_META_ID = $mainMetaDataIdPh 
                            AND PM.IS_TARGET = 0", $bindVars);

                    if ($row && $row['ACTION_TYPE'] == 'edit') {
                        return array_merge(array('isWorkSpace' => true, 'isGetData' => true, 'isMulti' => false, 'isConfirm' => false), $response);
                    }
                    
                    return array_merge(array('isWorkSpace' => true, 'isGetData' => false, 'isMulti' => false, 'isConfirm' => false), $response);
                }

                if ($getActionType['POST_PARAM'] != '') {
                    
                    $response['postParam'] = $getActionType['POST_PARAM'];
                    
                } elseif ($getActionType['GET_PARAM'] != '') {
                    
                    $response['getParam'] = $getActionType['GET_PARAM'];
                }
                
                $response['isBpOpen'] = $getActionType['IS_BP_OPEN'];

                if (($getActionType['GET_SUB_TYPE'] == 'internal' || $getActionType['GET_SUB_TYPE'] == 'external') && $getActionType['GET_ACTION_TYPE'] == 'consolidate') {

                    if ($getActionType['IS_MULTI'] == '1' && $getActionType['IS_CONFIRM'] == '1') {
                        return array('isGetData' => false, 'isMulti' => true, 'isConfirm' => true);
                    }

                    return array_merge(array('isGetConsolidate' => true, 'isGetData' => true, 'isMulti' => false, 'isConfirm' => false), $response);

                } elseif ($getActionType['SUB_TYPE'] == 'internal' && $getActionType['ACTION_TYPE'] == 'delete') {
                    
                    $response['isRowReload'] = $getActionType['IS_ROW_UPDATE'];
                    
                    return array_merge(array('isGetData' => false, 'isMulti' => true, 'isConfirm' => true), $response);

                } elseif ($getActionType['GET_SUB_TYPE'] && $getActionType['SUB_TYPE'] == 'internal' && $getActionType['ACTION_TYPE'] == 'update') {
                    
                    $response['isRowReload'] = $getActionType['IS_ROW_UPDATE'];
                    
                    if ($getActionType['IS_MULTI'] == '1' && $getActionType['IS_CONFIRM'] == '1') {
                        return array_merge(array('isGetData' => false, 'isMulti' => true, 'isConfirm' => true), $response);
                    } else {
                        return array_merge(array('isGetData' => true, 'isMulti' => false, 'isConfirm' => false, 'isInternalUpdate' => true), $response);
                    }

                } elseif ($getActionType['IS_MULTI'] == '1' && $getActionType['IS_CONFIRM'] == '1' 
                    && $getActionType['SUB_TYPE'] == 'internal' && $getActionType['ACTION_TYPE'] == 'update') { 
                    
                    $response['isRowReload'] = $getActionType['IS_ROW_UPDATE'];
                    
                    return array_merge(array('isGetData' => false, 'isMulti' => true, 'isConfirm' => true), $response);

                } elseif ($getActionType['SUB_TYPE'] == 'external' && $getActionType['ACTION_TYPE'] == 'console') {
                    
                    return array_merge(array('isGetData' => true, 'isMulti' => false, 'isConfirm' => false, 'isInternalUpdate' => true), $response);

                } elseif ($getActionType['SUB_TYPE'] == 'internal' && $getActionType['ACTION_TYPE'] == 'view') {

                    return array_merge(array('isGetData' => true, 'isMulti' => false, 'isConfirm' => false), $response);

                } else {

                    $isGetDataProcess = self::isGetDataProcess($mainMetaDataIdPh, $processMetaDataIdPh, $bindVars);

                    $result = array('isGetData' => false, 'isMulti' => false, 'isConfirm' => false);

                    if (!$isGetDataProcess) {

                        return array_merge($result, $response);

                    } else {

                        if (empty($isGetDataProcess['GET_META_DATA_ID'])) {
                            $result = array_merge($result, array('isGetData' => false));
                        } else {
                            $result = array_merge($result, array('isGetData' => true));

                            $getProcessActionType = self::getProcessActionType($isGetDataProcess['GET_META_DATA_ID']);

                            if (issetParam($getProcessActionType['SUB_TYPE']) == 'internal' && $getProcessActionType['ACTION_TYPE'] == 'consolidate') {
                                $result = array_merge($result, array('isGetConsolidate' => true, 'isMulti' => true));
                            }
                        }

                        if ($isGetDataProcess['IS_MULTI'] == '1') {
                            $result = array_merge($result, array('isMulti' => true));
                        }

                        if ($isGetDataProcess['IS_CONFIRM'] == '1') {
                            $result = array_merge($result, array('isConfirm' => true));
                        }
                        
                        /*if ($getActionType['ACTION_TYPE'] == '' 
                                && $isGetDataProcess['IS_CONFIRM'] == '1' 
                                && array_key_exists('METHOD_NAME', $getActionType) 
                                && $getActionType['METHOD_NAME'] == '') {
                            
                            $result['isConfirm'] = false;
                        }*/

                        return array_merge($result, $response);
                    }
                }

            } else {

                $getCriteria = $this->db->GetRow("
                    SELECT 
                        CRITERIA, 
                        PROCESS_NAME 
                    FROM META_DM_PROCESS_DTL 
                    WHERE MAIN_META_DATA_ID = $mainMetaDataIdPh 
                        AND PROCESS_META_DATA_ID = $processMetaDataIdPh 
                        AND (CRITERIA IS NOT NULL OR CRITERIA != '')", $bindVars);

                if ($getCriteria) {
                    
                    $explodedRules = explode('#', $getCriteria['CRITERIA']);
                    $headerRules = Str::lower(trim($explodedRules[0]));
                    
                    if (!array_key_exists(0, $selectedRow)) {
                        $selectedRow = array($selectedRow);
                    }
                    
                    foreach ($selectedRow as $rows) {
                        
                        if (isset($rows['children'])) {
                            unset($rows['children']);
                        }
                
                        if (isset($rows['pfnextstatuscolumn'])) {
                            unset($rows['pfnextstatuscolumn']);
                        }
                        
                        $rules = $headerRules;
                        
                        foreach ($rows as $sk => $sv) {
                            
                            if (!is_array($sv)) {
                                if (is_string($sv) && strpos($sv, "'") === false) {
                                    $sv = "'".Str::lower($sv)."'";
                                } elseif (is_null($sv)) {
                                    $sv = "''";
                                } 
                                
                                $sk = ($sk == '' ? 'tmpkey' : $sk);

                                $rules = preg_replace('/\b'.$sk.'\b/u', $sv, $rules);
                            }
                        }

                        $rules = Mdmetadata::defaultKeywordReplacer($rules);
                        $rules = Mdmetadata::criteriaMethodReplacer($rules);

                        if ($rules != '' && !Mdcommon::expressionEvalFixWithReturn($rules)) {
                            
                            $processNoAccessResult = array('processNoAccess' => true, 'processName' => Lang::line($getCriteria['PROCESS_NAME']));
                            
                            if (isset($explodedRules[1])) {
                                $processNoAccessResult = array_merge($processNoAccessResult, array('processNoAccessMsg' => Lang::line($explodedRules[1])));
                            }
                            
                            return $processNoAccessResult;
                        }
                    }
                }
                
                $row = $this->db->GetRow("
                    SELECT 
                        WL.ID, 
                        WL.ACTION_TYPE 
                    FROM META_WORKSPACE_LINK WL 
                        INNER JOIN META_WORKSPACE_PARAM_MAP PM ON PM.WORKSPACE_META_ID = WL.META_DATA_ID 
                    WHERE WL.META_DATA_ID = $processMetaDataIdPh 
                        AND PM.TARGET_META_ID = $mainMetaDataIdPh 
                        AND PM.IS_TARGET = 0", $bindVars);

                if ($row && $row['ACTION_TYPE'] == 'edit') {
                    return array('isWorkSpace' => true, 'isGetData' => true, 'isMulti' => false, 'isConfirm' => false);
                }
                
                return array('isWorkSpace' => true, 'isGetData' => false, 'isMulti' => false, 'isConfirm' => false);
            }
        }
    }
    
    public function checkProcessAdvancedCriteriaModel($mainMetaDataId, $processMetaDataId, $metaTypeId, $selectedRow, $advancedCriteria) {
        
        $mainMetaDataIdPh    = $this->db->Param(0);
        $processMetaDataIdPh = $this->db->Param(1);

        $bindVars = array($mainMetaDataId, $processMetaDataId);
        
        $getActionType = self::getBpActionType($mainMetaDataIdPh, $processMetaDataIdPh, $bindVars);
        $advancedCriteria = ($getActionType && array_key_exists('ADVANCED_CRITERIA', $getActionType)) ? $getActionType['ADVANCED_CRITERIA'] : $advancedCriteria;
        
        if ($advancedCriteria != '') {
            
            if (strpos($advancedCriteria, 'equal=') !== false) {
                
                $splitedCriteria = explode('&&', $advancedCriteria);
                $isSame = true;

                foreach ($splitedCriteria as $aCriteria) {

                    $splitedAdvCriteria = explode('#', $aCriteria);
                    $tmpParamPath = trim(str_replace('equal=', '', Str::lower($splitedAdvCriteria[0])));

                    if (isset($selectedRow[0][$tmpParamPath])) {
                        foreach ($selectedRow as $sv) {
                            if ($selectedRow[0][$tmpParamPath] != $sv[$tmpParamPath]) {
                                $isSame = false;
                                break;
                            }
                        }
                        if (!$isSame) {
                            break;
                        }
                    } else {
                        $isSame = false;
                        $splitedAdvCriteria[1] = 'Процессийн set criteria config тохиргоо буруу хийгдсэн!';
                        break;
                    }
                }

                if (!$isSame) {
                    $result = array(
                        'advancedCriteriaText' => isset($splitedAdvCriteria[1]) ? Lang::line($splitedAdvCriteria[1]) : 'empty'
                    );
                } else {
                    $result = self::checkProcessActionModel($mainMetaDataId, $processMetaDataId, $metaTypeId, $selectedRow);
                }

            } else {

                $advancedCriteria = Str::lower(trim($advancedCriteria));

                if ($selectedRow) {

                    foreach ($selectedRow as $rows) {
                        $rules = $advancedCriteria;

                        if (isset($rows['children'])) {
                            unset($rows['children']);
                        }

                        if (isset($rows['pfnextstatuscolumn'])) {
                            unset($rows['pfnextstatuscolumn']);
                        }

                        foreach ($rows as $sk => $sv) {
                            if (is_string($sv) && strpos($sv, "'") === false) {
                                $sv = "'" . Str::lower($sv) . "'";
                            } elseif (is_null($sv)) {
                                $sv = "''";
                            }

                            $sk = ($sk == '' ? 'tmpkey' : $sk);

                            $rules = preg_replace('/\b' . $sk . '\b/u', $sv, $rules);
                        }

                        $rules = html_entity_decode(Mdmetadata::defaultKeywordReplacer($rules), ENT_QUOTES, 'UTF-8');
                        $rules .= 'return true;';

                        if (trim($rules) != '') {
                            $rulesResult = Mdcommon::expressionEvalFix($rules);
                            if ($rulesResult !== true) {
                                $result = array(
                                    'advancedCriteriaText' => Str::firstUpper(Lang::line($rulesResult))
                                );
                                break;
                            }
                        }
                    }
                }

                if (empty($result)) {
                    $result = self::checkProcessActionModel($mainMetaDataId, $processMetaDataId, $metaTypeId, null, false);
                }
            }
            
        } else {
            $result = array('advancedCriteriaText' => 'empty');
        }

        return $result;
    }

    public function checkLifeCycleActionModel($mainMetaDataId, $selectedRow) {

        $param = array(
            'dataModelId' => $mainMetaDataId,
            'values' => $selectedRow
        );
        $data = $this->ws->runResponse(self::$gfServiceAddress, 'PL_GET_LCBOOK_ID_004', $param);

        if ($data['status'] == 'success') {
            
            if (isset($data['result'])) {    
                return array('status' => 'success', 'id' => $this->ws->getValue($data['result']));
            }
            
            return array('status' => 'error', 'message' => 'LifeCycle тохируулаагүй байна.');
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
        }
    }

    public function getDataModelHeaderDataModel($dataModelId, $sourceId) {
        
        $result = array();
        $param = array(
            'systemMetaGroupId' => $dataModelId,
            'criteria' => array(
                'id' => array(
                    array(
                        'operator' => '=',
                        'operand' => $sourceId
                    )
                )
            )
        );

        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($data['status'] == 'success' && isset($data['result'])) {

            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);
            $result = $data['result'][0];
        }

        return array('rowData' => $result, 'featureRowNum' => self::getDataViewFeatureField($dataModelId));
    }

    public function getDataViewByCriteriaModel($dataModelId, $criteria, $idField = null, $fieldType = null) {

        $param = array(
            'systemMetaGroupId' => $dataModelId,
            'showQuery' => 0, 
            'ignorePermission' => 1,  
            'criteria' => $criteria
        );

        if (Mdmetadata::$isProcessParamValues) {
            $paramDefaultCriteria = array();
            $defaultData = Mdwebservice::getParamDefaultValues(Mdwebservice::$processMetaDataId, Mdwebservice::$paramRealPath, $dataModelId);

            $idField = ($idField ? $idField : self::getDataViewMetaValueId($dataModelId));

            foreach ($defaultData as $dVal) {
                $paramDefaultCriteria[($idField ? $idField : 'id')][] = array(
                    'operator' => '=',
                    'operand' => $dVal['VALUE_ID'] 
                );
            }

            $param['criteria'] = array_merge($param['criteria'], $paramDefaultCriteria);
        }
        
        $dvConfig = self::getDVGridOptionsModel($dataModelId);
        
        if (isset($dvConfig['QS_META_DATA_ID'])) { // === QuickSearch DV
            
            $paramQS = $param;
            $paramQS['showQuery'] = 0; 
            $paramQS['systemMetaGroupId'] = $dvConfig['QS_META_DATA_ID'];
            
            $qsAttributes = self::getDataViewMetaValueAttributes(null, null, $dvConfig['QS_META_DATA_ID']);
            $mainAttributes = self::getDataViewMetaValueAttributes(null, null, $dataModelId);
            
            $qsIdField = strtolower($qsAttributes['id']);
            $mainIdField = strtolower($mainAttributes['id']);
                
            if ($fieldType == 'id') {
                
                unset($paramQS['criteria'][$mainIdField]);
                $paramQS['criteria'][$qsIdField][] = array('operator' => '=', 'operand' => $param['criteria'][$mainIdField][0]['operand']);
                
            } elseif ($fieldType == 'code') {
                
                $qsCodeField = strtolower($qsAttributes['code']);
                $mainCodeField = strtolower($mainAttributes['code']);
                
                unset($paramQS['criteria'][$mainCodeField]);
                
                $paramQS['criteria'][$qsCodeField][] = array('operator' => '=', 'operand' => $param['criteria'][$mainCodeField][0]['operand']);
                
            } elseif ($fieldType == 'name') {
                
                $qsNameField = strtolower($qsAttributes['name']);
                $mainNameField = strtolower($mainAttributes['name']);
                
                unset($paramQS['criteria'][$mainNameField]);
                
                $paramQS['criteria'][$qsNameField][] = array('operator' => '=', 'operand' => $param['criteria'][$mainNameField][0]['operand']);
            }
            
            $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $paramQS);
            
            if ($data['status'] == 'success' && isset($data['result']) && isset($data['result'][0])) {
                
                unset($data['result']['paging']);
                unset($data['result']['aggregatecolumns']);
                
                $firstRow = $data['result'][0];
                
                $param['criteria'][$mainIdField] = array(array('operator' => '=', 'operand' => $firstRow[$qsIdField]));
            }
        }
        
        $param['criteria']['filterStartDate'] = array(array(
            'operator' => '=',
            'operand' => Date::currentDate('Y').'-01-01'
        )); 
        $param['criteria']['filterEndDate'] = array(array(
            'operator' => '=',
            'operand' => Date::currentDate('Y-m-d')
        ));
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] == 'success' && isset($data['result']) && isset($data['result'][0])) {
            
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);
            
            return $data['result'][0];
        }

        return false;
    }

    public function getRowsDataViewByCriteriaModel($dataModelId, $criteria, $idField, $codeField, $nameField, $where, $isQuickSearchDv = false) {

        $param = array(
            'systemMetaGroupId' => $dataModelId,
            'showQuery' => 0, 
            'ignorePermission' => 1,  
            'paging' => array(
                'offset' => 1,
                'pageSize' => 30
            ), 
            'criteria' => $criteria
        );

        if (Mdmetadata::$isProcessParamValues) {
            $paramDefaultCriteria = array();
            $defaultData = Mdwebservice::getParamDefaultValues(Mdwebservice::$processMetaDataId, Mdwebservice::$paramRealPath, $dataModelId);

            foreach ($defaultData as $dVal) {
                $paramDefaultCriteria[($idField ? $idField : 'id')][] = array(
                    'operator' => '=',
                    'operand' => $dVal['VALUE_ID'] 
                );
            }

            $param['criteria'] = array_merge($param['criteria'], $paramDefaultCriteria);
        }
        
        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($data['status'] == 'success' && isset($data['result']) && isset($data['result'][0])) {
            
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);
            
            $rowsData = $data['result'];
            $data = array();
            
            $idField = strtolower($idField);
            $codeField = $codeField ? strtolower($codeField) : $idField;
            $nameField = $nameField ? strtolower($nameField) : $codeField;

            if (is_null($where)) {
                
                $firstRow = issetParamArray($rowsData[0]);
                
                if (array_key_exists('breadcrumbname', $firstRow)) {
                    
                    foreach ($rowsData as $row) {
                        $name = html_entity_decode($row[$idField].'|'.$row[$codeField].'|'.$row[$nameField].'|'.$row['breadcrumbname'], ENT_QUOTES, 'UTF-8');
                        array_push($data, $name);	
                    }
                    
                } else {
                    foreach ($rowsData as $row) {
                        $name = html_entity_decode($row[$idField].'|'.$row[$codeField].'|'.$row[$nameField], ENT_QUOTES, 'UTF-8');
                        array_push($data, $name);	
                    }
                }

            } else {

                if ($isQuickSearchDv) {

                    foreach ($rowsData as $row) {
                        $name = html_entity_decode($row[$idField].'|'.$row[$codeField].'|'.$row[$nameField], ENT_QUOTES, 'UTF-8');
                        array_push($data, array('codeName' => $name, 'row' => '', 'isQs' => 1));	
                    }

                } else {
                    foreach ($rowsData as $row) {
                        $name = html_entity_decode($row[$idField].'|'.$row[$codeField].'|'.$row[$nameField], ENT_QUOTES, 'UTF-8');
                        array_push($data, array('codeName' => $name, 'row' => $row));	
                    }
                }
            }

            return $data;
        }

        return array();
    }

    public function getRowIndexDataViewByCriteriaModel($processMetaDataId, $paramRealPath, $dataModelId, $idField, $codeField, $nameField, $rowIndex, $criteria) {

        $param = array(
            'systemMetaGroupId' => $dataModelId,
            'showQuery' => 1, 
            'ignorePermission' => 1,  
            'criteria' => $criteria
        );

        if (Mdmetadata::$isProcessParamValues) {
            $paramDefaultCriteria = array();
            $defaultData = Mdwebservice::getParamDefaultValues(Mdwebservice::$processMetaDataId, Mdwebservice::$paramRealPath, $dataModelId);

            foreach ($defaultData as $dVal) {
                $paramDefaultCriteria[($idField ? $idField : 'id')][] = array(
                    'operator' => '=',
                    'operand' => $dVal['VALUE_ID']
                );
            }

            $param['criteria'] = array_merge($param['criteria'], $paramDefaultCriteria);
        }

        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($data['status'] == 'success' && isset($data['result'])) {

            $this->db->StartTrans(); 
            $this->db->Execute(Ue::createSessionInfo());
            
            $sqlResult = $this->db->SelectLimit($data['result'], 1, --$rowIndex);
            
            $this->db->CompleteTrans();

            if ($sqlResult && isset($sqlResult->_array)) {

                $data = array();
                $sqlResultData = $sqlResult->_array;
                $rowsData = Arr::changeKeyLower($sqlResultData[0]);

                $idField = strtolower($idField);
                $codeField = strtolower($codeField);
                $nameField = strtolower($nameField);

                $this->load->model('mdmetadata', 'middleware/models/');
                $controlsData = $this->model->getLookupCloneFieldModel($processMetaDataId, $paramRealPath);

                $row = array(
                    'META_VALUE_ID' => ($idField ? $rowsData[$idField] : (isset($rowsData['id']) ? $rowsData['id'] : '')),
                    'META_VALUE_CODE' => (isset($rowsData[$codeField]) ? $rowsData[$codeField] : ''),
                    'META_VALUE_NAME' => (isset($rowsData[$nameField]) ? $rowsData[$nameField] : ''), 
                    'controlsData' => $controlsData, 
                    'rowData' => $rowsData
                );

                return $row;
            }
        }

        return array();
    }

    public function getDataViewFeatureField($dataModelId) {
        $data = $this->db->GetAll("
            SELECT 
                LABEL_NAME AS META_DATA_NAME, 
                DATA_TYPE AS META_TYPE_CODE, 
                LOWER(FIELD_PATH) AS FIELD_PATH, 
                FEATURE_NUM  
            FROM META_GROUP_CONFIG  
            WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                AND PARENT_ID IS NULL 
                AND DATA_TYPE <> 'group'  
            ORDER BY FEATURE_NUM ASC", array($dataModelId));

        return $data;
    }

    public function getDvProcessInfoModel($mainMetaDataId, $processMetaDataId) {
        
        $mainMetaDataIdPh    = $this->db->Param(0);
        $processMetaDataIdPh = $this->db->Param(1);

        $bindVars = [$this->db->addQ($mainMetaDataId), $this->db->addQ($processMetaDataId)];
        
        $row = $this->db->GetRow("
            SELECT 
                ".$this->db->IfNull('PD.PROCESS_NAME', $this->db->IfNull('PL.PROCESS_NAME', 'MD.META_DATA_NAME'))." AS PROCESS_NAME, 
                PD.CONFIRM_MESSAGE     
            FROM META_DM_PROCESS_DTL PD 
                INNER JOIN META_BUSINESS_PROCESS_LINK PL ON PL.META_DATA_ID = PD.PROCESS_META_DATA_ID 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = PD.PROCESS_META_DATA_ID 
            WHERE PD.MAIN_META_DATA_ID = $mainMetaDataIdPh 
                AND PD.PROCESS_META_DATA_ID = $processMetaDataIdPh", $bindVars);
        
        if ($row) {
            
            if ($row['CONFIRM_MESSAGE'] != '' && Input::postCheck('rows') && count($_POST['rows']) > 0) {
                
                $message = $row['CONFIRM_MESSAGE'];
                $row     = (array_key_exists(0, $_POST['rows']) ? $_POST['rows'][0] : $_POST['rows']);
                
                if (strpos($message, 'getDataViewColumnVal(') !== false) {
                    
                    preg_match_all('/getDataViewColumnVal\((.*?)\)/i', $message, $getDataViewColumnVal);

                    if (count($getDataViewColumnVal[0]) > 0) {
                        
                        foreach ($getDataViewColumnVal[1] as $ek => $ev) {

                            $evArr = explode(',', $ev);
                            
                            $dvCode = strtolower(trim($evArr[0]));
                            $paramMap = strtolower(trim($evArr[1]));
                            $columnName = strtolower(trim($evArr[2]));
                            
                            $dvValue = self::getDataViewColumnValueModel($dvCode, $paramMap, $columnName, $row);

                            $message = str_replace($getDataViewColumnVal[0][$ek], $dvValue, $message);
                        }
                    }
                }
                
                if (strpos($message, '.val()') !== false) {
                    preg_match_all('/\[([^\]]*)\].val\(\)/', $message, $valPaths);
                    
                    if (isset($valPaths[1][0])) {
                        foreach ($valPaths[1] as $valKey => $valPath) {
                            $valPath = strtolower($valPath);
                            $message = str_replace($valPaths[0][$valKey], issetParam($row[$valPath]), $message);
                        }
                    }
                }
                
            } else {
                
                $processName = '<strong>'.Str::upper(Lang::line($row['PROCESS_NAME'])).'</strong>';
                $message = 'Та ('.$processName.') үйлдэлийг хийхдээ итгэлтэй байна уу?';
                
                $message = Lang::lineVar('dv_process_confirm_message', ['processName' => $processName], $message);
            }
            
        } else {
            $message = 'PROCESS';
        }

        return $message;
    }
    
    public function getDataViewColumnValueModel($dvCode, $paramMap, $columnName, $row) {
        
        $this->load->model('mdexpression', 'middleware/models/');
        $divIdBySelect = $this->model->getProcessIdByCodeModel($dvCode);
        
        $param = array(
            'systemMetaGroupId' => $divIdBySelect,
            'showQuery' => 0, 
            'ignorePermission' => 1
        );
        
        if ($paramMap) {
            
            $paramCriteria = array();
            $paramMapExplode = explode('|', $paramMap);
            
            foreach ($paramMapExplode as $k => $paramRow) {
                
                $paramRowArr = explode('=', $paramRow);
                
                $paramCriteria[$paramRowArr[0]][] = array(
                    'operator'  => '=',
                    'operand'   => $row[$paramRowArr[1]]
                );
            }
            
            $param['criteria'] = $paramCriteria;
        }

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result']) && isset($data['result'][0][$columnName])) {
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);
            return $data['result'][0][$columnName];
        } else {
            return null;
        }
    }

    public function getDataViewCountCardModel($metaDataId) {
        $data = $this->db->GetAll("
            SELECT 
                ID, 
                LABEL_NAME AS META_DATA_NAME, 
                LOWER(FIELD_PATH) AS FIELD_PATH, 
                DATA_TYPE AS META_TYPE_CODE, 
                COUNTCARD_THEME, 
                LOWER(COUNTCARD_SELECTION) AS COUNTCARD_SELECTION ,
                JSON_CONFIG
            FROM META_GROUP_CONFIG 
            WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                AND IS_COUNTCARD = 1 
                AND PARENT_ID IS NULL 
                AND DATA_TYPE <> 'group' 
            ORDER BY DISPLAY_ORDER ASC", array($metaDataId));

        return $data;
    }

    public function getCountCardDataModel($metaDataId, $fieldPath, $jsonConfig = '') {
        
        $param = array(
            'systemMetaGroupId' => $metaDataId,
            'groupParamPath' => $fieldPath, 
        );
        
        if (issetParam($jsonConfig)) {
            $jsonArr = json_decode(str_replace("&quot;", "\"", $jsonConfig), true);
            if (issetParam($jsonArr['aggregateField']) && issetParam($jsonArr['aggregateFunction'])) {
                $param['aggregateField'] = Str::lower($jsonArr['aggregateField']);
                $param['aggregateFunction'] = Str::upper($jsonArr['aggregateFunction']);
            }
        }

        $workSpaceId = Input::numeric('workSpaceId');
        $workSpaceParams = (Input::postCheck('workSpaceParams')) ? Input::post('workSpaceParams') : '';        
        
        if (Input::postCheck('defaultCriteriaData') && Input::isEmpty('defaultCriteriaData') == false) {

            parse_str(Input::post('defaultCriteriaData'), $defaultCriteriaData);
            
            if (isset($defaultCriteriaData['param'])) {
                
                if (isset($defaultCriteriaData['criteriaCondition'])) {
                    $defaultCriteriaCondition = $defaultCriteriaData['criteriaCondition'];
                    $defaultCondition = '1';
                } else {
                    $defaultCriteriaCondition = 'like';
                    $defaultCondition = '0';
                }
                
                $defaultCriteriaParam = $defaultCriteriaData['param'];

                foreach ($defaultCriteriaParam as $defParam => $defParamVal) {

                    $defParamVal = Input::param($defParamVal);

                    if ($defParamVal) {
                        
                        if (isset($defaultCriteriaCondition[$defParam])) {
                            $operator = $defaultCriteriaCondition[$defParam];
                        } elseif (is_string($defaultCriteriaCondition) && $defaultCriteriaCondition == 'like') {
                            $operator = 'like';
                        } else {
                            $operator = 'between';
                        }
                            
                        if (is_array($defParamVal)) {
                            
                            if ($operator == 'between') {
                                $paramDefaultCriteria[$defParam][] = array(
                                    'operator' => $operator,
                                    'operand' => $defParamVal[0].' AND '.$defParamVal[1]
                                );
                            } else {
                                $defParamVals = Arr::implode_r(',', $defParamVal, true);

                                if ($defParamVals != '') {
                                    $paramDefaultCriteria[$defParam][] = array(
                                        'operator' => ($operator == '!=' ? 'NOT IN' : 'IN'),
                                        'operand' => $defParamVals
                                    );
                                }
                            }
                        } else {           
                                
                            $getTypeCode = self::getDataViewGridCriteriaRowModel($metaDataId, $defParam);
                            $getTypeCodeLower = $getTypeCode['META_TYPE_CODE'];

                            if ($getTypeCodeLower == 'date' || $getTypeCodeLower == 'datetime') {

                                $defParamVal = str_replace(
                                    array('____-__-__', '___-__-__', '__-__-__', '_-__-__', '-__-__', '-__', '_', '__:__', ':__'), '', $defParamVal
                                );

                                $operator = ($defaultCondition === '0') ? '=' : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : '='); 

                            } elseif ($getTypeCodeLower == 'long' || $getTypeCodeLower == 'integer') {

                                $operator = ($defaultCondition === '0') ? '=' : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : '='); 

                            } elseif ($getTypeCodeLower == 'bigdecimal' || $getTypeCodeLower == 'bigdecimal_null' || $getTypeCodeLower == 'number') {

                                $defParamVal = Number::decimal($defParamVal);

                            } elseif ($getTypeCodeLower == 'boolean') {

                                $operator = '=';
                            }

                            $paramDefaultCriteria[$defParam][] = array(
                                'operator' => $operator,
                                'operand' => ($operator == 'like') ? '%'.$defParamVal.'%' : $defParamVal 
                            );
                        }
                    }
                }

                if (isset($paramDefaultCriteria)) {
                    if (isset($param['criteria'])) {
                        $param['criteria'] = array_merge($param['criteria'], $paramDefaultCriteria);
                    } else {
                        $param['criteria'] = $paramDefaultCriteria;
                    }
                }
            }
        }
        
        if (!empty($workSpaceId) && !empty($workSpaceParams)) {

            $getWorkSpaceDvParamMap = self::getWorkSpaceDvParamMap($metaDataId, $workSpaceId);

            $workSpaceParams = str_replace('&amp;workSpaceParam%', '&workSpaceParam%', $workSpaceParams);
            parse_str($workSpaceParams, $workSpaceParamArray);

            $isResponse = false;

            if ($getWorkSpaceDvParamMap) {

                if (isset($workSpaceParamArray['workSpaceParam'])) {

                    $getWorkSpaceParam = $workSpaceParamArray['workSpaceParam'];
                    $paramWorkSpaceCriteria = array();

                    foreach ($getWorkSpaceDvParamMap as $wsRow) {
                        $lowerKey = strtolower($wsRow['FIELD_PATH']);

                        if (isset($getWorkSpaceParam[$lowerKey])) {
                            $paramWorkSpaceCriteria[$wsRow['PARAM_PATH']][] = !empty($getWorkSpaceParam[$lowerKey]) ? 
                                array(
                                    'operator' => '=',
                                    'operand' => $getWorkSpaceParam[$lowerKey]
                                ) :
                                array(
                                    'operator' => 'IS NULL',
                                    'operand' => ''
                                );
                            $isResponse = true;
                        }
                    }
                }

                foreach ($workSpaceParamArray as $wsKey => $wsVal) {

                    if (!is_array($wsVal)) {

                        $paramWorkSpaceCriteria[$wsKey][] = !empty($wsVal) ? 
                            array(
                                'operator' => '=',
                                'operand' => $wsVal
                            ) :
                            array(
                                'operator' => 'IS NULL',
                                'operand' => ''
                            );
                        $isResponse = true;
                    } 
                }

            } else {

                foreach ($workSpaceParamArray as $wsKey => $wsVal) {
                    if (is_array($wsVal)) {

                        foreach ($wsVal as $x => $paramVal) {
                            if ($paramVal != '') {
                                $paramWorkSpaceCriteria[$wsKey][] = array(
                                    'operator' => '=',
                                    'operand' => $paramVal 
                                );
                                $isResponse = true;
                            }
                        }

                    } else {

                        $paramWorkSpaceCriteria[$wsKey][] = array(
                            'operator' => '=',
                            'operand' => $wsVal 
                        );
                        $isResponse = true;
                    }
                }
            }

            if ($isResponse) {
                if (isset($param['criteria'])) {
                    $param['criteria'] = array_merge($param['criteria'], $paramWorkSpaceCriteria);
                } else {
                    $param['criteria'] = $paramWorkSpaceCriteria;
                }

                $isParentChildResolve = true;
            }
        }   
        
        $paramFilter = array();

        if (Input::postCheck('drillDownDefaultCriteria') && !empty($_POST['drillDownDefaultCriteria'])) {

            $drillDown = json_decode(str_replace("&quot;", "\"", $_POST['drillDownDefaultCriteria']), true);

            if (isset($param['criteria'])) {
                $criteriaLowerCase = Arr::changeKeyLower($param['criteria']);
            }

            foreach ($drillDown as $drillKey => $drillValue) {
                $uriOperator = ($drillKey == 'dtlstatusid') ? 'like' : '=';

                if (!isset($criteriaLowerCase) || (isset($criteriaLowerCase) && !isset($criteriaLowerCase[$drillKey]))) {
                    if (!is_array($drillValue)) {
                        if (strrpos($drillValue, ',')) {
                            $uriV = explode(',', $drillValue);

                            foreach ($uriV as $uriK) {
                                $uriOperand = ($drillKey == 'dtlstatusid') ? '%'.Input::param($uriK).'%' : Input::param($uriK);
                                $paramFilter[$drillKey][] = array(
                                    'operator' => $uriOperator,
                                    'operand' => $uriOperand
                                );
                            }

                        } else {
                            $uriOperand = ($uriOperator == 'like') ? '%'.$drillValue.'%' : $drillValue;
                            $paramFilter[$drillKey][] = array('operator' => $uriOperator, 'operand' => $uriOperand);
                        }

                    } else {
                        $paramFilter[$drillKey] = $drillValue;
                    }
                }
            }

            if ($paramFilter) {
                if (isset($param['criteria'])) {
                    $param['criteria'] = array_merge($param['criteria'], $paramFilter);
                } else {
                    $param['criteria'] = $paramFilter;
                }   

                $isParentChildResolve = true;
            }
        }
        
        $result = $this->ws->runResponse(self::$gfServiceAddress, 'PL_MDMVIEWGROUPCOUNT_004', $param);
        if ($result['status'] == 'success') {
            return (isset($result['result']) ? $result['result'] : false);
        }

        return false;
    }

    public function runConfirmLoopProcessModel($mainMetaDataId, $processMetaDataId, $selectedRows) {
        $this->load->model('mdwebservice', 'middleware/models/');
        
        $dvIdPh = $this->db->Param(0);
        $processIdPh = $this->db->Param(1);
        
        $getActionType = $this->db->GetRow("
            SELECT 
                MD.META_DATA_CODE, 
                BL.SUB_TYPE, 
                BL.ACTION_TYPE, 
                PD.PROCESS_NAME, 
                BL.INPUT_META_DATA_ID, 
                BL.WS_URL, 
                SL.SERVICE_LANGUAGE_CODE, 
                PD.IS_RUN_LOOP 
            FROM META_BUSINESS_PROCESS_LINK BL 
                INNER JOIN META_DM_PROCESS_DTL PD ON PD.MAIN_META_DATA_ID = $dvIdPh 
                    AND PD.PROCESS_META_DATA_ID = $processIdPh 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = BL.META_DATA_ID     
                LEFT JOIN WEB_SERVICE_LANGUAGE SL ON SL.SERVICE_LANGUAGE_ID = BL.SERVICE_LANGUAGE_ID 
            WHERE BL.META_DATA_ID = $processIdPh", array($mainMetaDataId, $processMetaDataId));
        
        if (!array_key_exists(0, $selectedRows)) {
            $selectedRows = array($selectedRows);
        } 
                
        if ($getActionType['SUB_TYPE'] == 'internal' && $getActionType['ACTION_TYPE'] == 'delete') {

            $getTransferDataParamsData = $this->model->getTransferDataParams($mainMetaDataId, $processMetaDataId);
            
            if ($getTransferDataParamsData) {
                
                $commandName = $getActionType['META_DATA_CODE'];
                $paramDefaultCriteria = $param = array();
            
                if (count($selectedRows) == 1) {

                    foreach ($getTransferDataParamsData as $inputField) {

                        $viewFieldPath = strtolower($inputField['VIEW_FIELD_PATH']);

                        if (isset($selectedRows[0][$viewFieldPath])) {
                            $valueId = $selectedRows[0][$viewFieldPath];
                        } elseif ($inputField['DEFAULT_VALUE'] !== '') {
                            $valueId = $inputField['DEFAULT_VALUE'];
                        }

                        $param[$inputField['INPUT_PARAM_PATH']] = $valueId;
                    }

                } else {
                    
                    if ($getActionType['IS_RUN_LOOP'] == '1') {
                        
                        $loopParam = array();
                        
                        foreach ($selectedRows as $row) {
                            
                            $param = array();
                            
                            foreach ($getTransferDataParamsData as $inputField) {

                                $viewFieldPath = strtolower($inputField['VIEW_FIELD_PATH']);

                                if (isset($row[$viewFieldPath])) {
                                    $param[$inputField['INPUT_PARAM_PATH']] = Input::param($row[$viewFieldPath]);
                                } elseif ($inputField['DEFAULT_VALUE'] !== '') {
                                    $param[$inputField['INPUT_PARAM_PATH']] = $inputField['DEFAULT_VALUE'];
                                }
                            }
                            
                            if (count($param)) {
                                $loopParam[] = $param;
                            }
                        }
                        
                        if (count($loopParam) == 0) {
                            return array('status' => 'error', 'message' => 'Дамжуулах параметр олдсонгүй!');
                        }
                        
                        $commandName = 'PL_LOOP_011';
                        
                        $param = array(
                            'commandName' => $getActionType['META_DATA_CODE'],
                            'source' => $loopParam
                        );
                        
                    } else {
                        
                        foreach ($getTransferDataParamsData as $inputField) {

                            $viewFieldPath = strtolower($inputField['VIEW_FIELD_PATH']);

                            if (isset($selectedRows[0][$viewFieldPath])) {
                                $paramDefaultCriteria[$inputField['INPUT_PARAM_PATH']][] = array(
                                    'operator' => 'IN',
                                    'operand' => Input::param(Arr::implode_key(',', $selectedRows, $viewFieldPath, true))
                                );
                            } elseif ($inputField['DEFAULT_VALUE'] !== '') {
                                $paramDefaultCriteria[$inputField['INPUT_PARAM_PATH']][] = array(
                                    'operator' => '=',
                                    'operand' => $inputField['DEFAULT_VALUE']
                                );
                            }
                        }

                        $param['criteria'] = $paramDefaultCriteria;
                    }
                }

                $result = $this->ws->caller($getActionType['SERVICE_LANGUAGE_CODE'], $getActionType['WS_URL'], $commandName, 'return', $param);
                
                if ($result['status'] == 'success') {
                    
                    if ($wfmStatusParams = issetParam($selectedRows[0]['wfmStatusParams'])) {
                    
                        parse_str($wfmStatusParams, $wfmStatusArr);
                        
                        if (isset($wfmStatusArr['refStructureId'])) {
                            
                            $refStructureId = Input::param($wfmStatusArr['refStructureId']);
                            $newStatusId = Input::param($wfmStatusArr['statusId']);

                            foreach ($selectedRows as $selectedRow) {

                                if (isset($_POST)) {
                                    unset($_POST);
                                }

                                $_POST['newWfmStatusid'] = $newStatusId;
                                $_POST['metaDataId'] = $refStructureId;
                                $_POST['dataRow'] = array('id' => Input::param($selectedRow['id']), 'wfmStatusId' => Input::param($selectedRow['wfmstatusid']));
                                $_POST['description'] = '';

                                self::setRowWfmStatusModel();
                            }
                        }
                    }
                    
                    return array('status' => 'success', 'message' => '(<b>' . Lang::line($getActionType['PROCESS_NAME']) . '</b>) үйлдэл амжилттай боллоо.');
                } else {
                    return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
                }
                
            } else {
                return array('status' => 'error', 'message' => 'Параметр тохируулаагүй байна.');
            }

        } else {

            $runProcess = $this->db->GetRow("
                SELECT 
                    MD.META_DATA_CODE AS COMMAND_NAME,  
                    PL.INPUT_META_DATA_ID, 
                    SL.SERVICE_LANGUAGE_CODE, 
                    PL.WS_URL, 
                    PD.PROCESS_META_DATA_ID, 
                    PD.PROCESS_NAME 
                FROM META_DM_PROCESS_DTL PD 
                    INNER JOIN META_BUSINESS_PROCESS_LINK PL ON PL.META_DATA_ID = PD.PROCESS_META_DATA_ID  
                    INNER JOIN META_DATA MD ON MD.META_DATA_ID = PL.META_DATA_ID 
                    LEFT JOIN WEB_SERVICE_LANGUAGE SL ON SL.SERVICE_LANGUAGE_ID = PL.SERVICE_LANGUAGE_ID 
                    LEFT JOIN META_DM_TRANSFER_PROCESS TP ON TP.MAIN_META_DATA_ID = PD.MAIN_META_DATA_ID 
                        AND TP.PROCESS_META_DATA_ID = PD.PROCESS_META_DATA_ID 
                WHERE PD.MAIN_META_DATA_ID = $dvIdPh 
                    AND PD.PROCESS_META_DATA_ID = $processIdPh", array($mainMetaDataId, $processMetaDataId));

            if ($selectedRows && $runProcess) {

                if ($getActionType['ACTION_TYPE'] == 'console') {
                    
                    $param = array();
                    $getTransferDataParamsData = $this->model->getTransferDataParams($mainMetaDataId, $processMetaDataId);

                    foreach ($selectedRows as $k => $row) {
                        foreach ($getTransferDataParamsData as $inputField) {
                            
                            if ($inputField['DEFAULT_VALUE'] != '') {
                                $param[$k][$inputField['INPUT_PARAM_PATH']] = $inputField['DEFAULT_VALUE'];
                            } else {
                                $viewFieldPath = strtolower($inputField['VIEW_FIELD_PATH']);
                                $param[$k][$inputField['INPUT_PARAM_PATH']] = $row[$viewFieldPath];
                            }
                        }
                    }
                    
                    foreach ($getTransferDataParamsData as $inputField) {
                        
                        $viewFieldPath = strtolower($inputField['VIEW_FIELD_PATH']);
                        
                        if ($viewFieldPath) {
                            $paramCriteria[$inputField['INPUT_PARAM_PATH']][] = array(
                                'operator' => 'IN',
                                'operand' => Arr::implode_r(',', Arr::groupByArrayOnlyKey($selectedRows, $viewFieldPath), true)
                            );
                        } else {
                            $paramCriteria[$inputField['INPUT_PARAM_PATH']][] = array(
                                'operator' => '=',
                                'operand' => $inputField['DEFAULT_VALUE']
                            );
                        }
                    }
                    
                    $param['criteria'] = $paramCriteria;

                    $result = $this->ws->runResponse(self::$gfServiceAddress, $runProcess['COMMAND_NAME'], $param);

                    return array('status' => $result['status'], 'message' => $this->ws->getResponseMessage($result));
                }

                $loopParam = array();
                $paramList = $this->model->groupParamsDataModel($runProcess['PROCESS_META_DATA_ID'], null, ' AND PAL.PARENT_ID IS NULL');

                foreach ($selectedRows as $row) {

                    $param = array();

                    foreach ($paramList as $input) {
                        
                        $typeCode = $input['META_TYPE_CODE'];
                        
                        if ($typeCode != 'group') {

                            $metaDataCode = $input['LOWER_PARAM_NAME'];

                            if ($typeCode == 'boolean') {
                                if (isset($row[$metaDataCode])) {
                                    $param[$metaDataCode] = '1';
                                } else {
                                    $param[$metaDataCode] = $this->ws->convertDeParamType(Mdmetadata::setDefaultValue($input['DEFAULT_VALUE']), $typeCode);
                                }
                            } else {
                                if ($rowKey = $this->model->getMapDVBusinessProcessParam($mainMetaDataId, $processMetaDataId, $input['PARAM_REAL_PATH'])) {
                                    $param[$metaDataCode] = $this->ws->convertDeParamType($row[strtolower($rowKey)], $typeCode);
                                } else {
                                    if (isset($row[$metaDataCode])) {
                                        $param[$metaDataCode] = $row[$metaDataCode];
                                    } else {
                                        $param[$metaDataCode] = $this->ws->convertDeParamType(Mdmetadata::setDefaultValue($input['DEFAULT_VALUE']), $typeCode);
                                    }
                                }
                            }
                        }
                    }
                    
                    if (isset($param['isClosed']) && $param['isClosed'] == '1') {
                        $param['isClosed'] = 0;                    
                    }
                    
                    $loopParam[] = $param;
                }

                $lastParam = array(
                    'commandName' => $runProcess['COMMAND_NAME'],
                    'source' => $loopParam
                );

                $result = $this->ws->runResponse(self::$gfServiceAddress, 'PL_LOOP_011', $lastParam);

                if ($result['status'] == 'success') {
                    
                    if ($wfmStatusParams = issetParam($selectedRows[0]['wfmStatusParams'])) {
                    
                        parse_str($wfmStatusParams, $wfmStatusArr);
                        
                        if (isset($wfmStatusArr['refStructureId'])) {
                            
                            $refStructureId = Input::param($wfmStatusArr['refStructureId']);
                            $newStatusId = Input::param($wfmStatusArr['statusId']);

                            foreach ($selectedRows as $selectedRow) {

                                if (isset($_POST)) {
                                    unset($_POST);
                                }

                                $_POST['newWfmStatusid'] = $newStatusId;
                                $_POST['metaDataId'] = $refStructureId;
                                $_POST['dataRow'] = array('id' => Input::param($selectedRow['id']), 'wfmStatusId' => Input::param($selectedRow['wfmstatusid']));
                                $_POST['description'] = '';

                                self::setRowWfmStatusModel();
                            }
                        }
                    }
                
                    return array('status' => 'success', 'message' => '(<strong>' . $this->lang->line($runProcess['PROCESS_NAME']) . '</strong>) үйлдэл амжилттай боллоо.');
                } else {
                    return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
                }
            }
        }

        return array('status' => 'error', 'message' => 'Алдаа гарлаа!');
    }

    public function runConfirmOneLoopProcessModel($dmMetaDataId, $processMetaDataId, $selectedRowData) {
        $this->load->model('mdwebservice', 'middleware/models/');

        $runProcess = $this->db->GetRow("
            SELECT 
                MD.META_DATA_CODE AS COMMAND_NAME,  
                PL.INPUT_META_DATA_ID, 
                SL.SERVICE_LANGUAGE_CODE, 
                PL.WS_URL, 
                TP.PROCESS_META_DATA_ID, 
                ".$this->db->IfNull('PD.PROCESS_NAME', $this->db->IfNull('PL.PROCESS_NAME', 'MD.META_DATA_NAME'))." AS PROCESS_NAME, 
                MW.WS_SERVER_NAME 
            FROM META_DM_TRANSFER_PROCESS TP 
                INNER JOIN META_BUSINESS_PROCESS_LINK PL ON PL.META_DATA_ID = TP.PROCESS_META_DATA_ID  
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = PL.META_DATA_ID 
                INNER JOIN META_DM_PROCESS_DTL PD ON PD.MAIN_META_DATA_ID = TP.MAIN_META_DATA_ID 
                    AND PD.PROCESS_META_DATA_ID = TP.PROCESS_META_DATA_ID 
                LEFT JOIN WEB_SERVICE_LANGUAGE SL ON SL.SERVICE_LANGUAGE_ID = PL.SERVICE_LANGUAGE_ID 
                LEFT JOIN CUSTOMER_META_WS MW ON MW.SRC_META_DATA_ID = PL.META_DATA_ID 
                    AND MW.TRG_META_DATA_ID IS NULL 
                    AND MW.WS_URL IS NULL 
                    AND MW.WS_SERVER_NAME IS NOT NULL 
            WHERE TP.MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                AND TP.PROCESS_META_DATA_ID = ".$this->db->Param(1), 
            array($dmMetaDataId, $processMetaDataId));
        
        $param = array();
        $paramList = $this->model->groupParamsDataModel($runProcess['PROCESS_META_DATA_ID'], null, ' AND PAL.PARENT_ID IS NULL');

        foreach ($paramList as $input) {
            
            $typeCode = strtolower($input['META_TYPE_CODE']);
            
            if ($typeCode != 'group') {

                if ($typeCode == 'boolean') {
                    
                    if (isset($selectedRowData[strtolower($input['META_DATA_CODE'])])) {
                        $param[$input['META_DATA_CODE']] = '1';
                    } else {
                        $param[$input['META_DATA_CODE']] = $this->ws->convertDeParamType(Mdmetadata::setDefaultValue($input['DEFAULT_VALUE']), $typeCode);
                    }
                    
                } else {
                    
                    if ($rowKey = $this->model->getMapDVBusinessProcessParam($dmMetaDataId, $processMetaDataId, $input['PARAM_REAL_PATH'])) {
                        $param[$input['META_DATA_CODE']] = $this->ws->convertDeParamType($selectedRowData[strtolower($rowKey)], $typeCode);
                    } else {
                        $param[$input['META_DATA_CODE']] = $this->ws->convertDeParamType(Mdmetadata::setDefaultValue($input['DEFAULT_VALUE']), $typeCode);
                    }
                }
            }
        }
        
        $wsServerName = $runProcess['WS_SERVER_NAME'];
        $configWsUrl  = Config::getFromCache($wsServerName);
        
        if ($configWsUrl && @file_get_contents($configWsUrl, false, stream_context_create(array('http' => array('timeout' => 2))))) {
            $result = $this->ws->runSerializeResponse($configWsUrl, $runProcess['COMMAND_NAME'], $param);
        } else {
            $result = $this->ws->caller($runProcess['SERVICE_LANGUAGE_CODE'], $runProcess['WS_URL'], $runProcess['COMMAND_NAME'], 'return', $param);
        }

        if ($result['status'] == 'success') {
            return array('status' => 'success', 'message' => '(<strong>'.$this->lang->line($runProcess['PROCESS_NAME']).'</strong>) үйлдэл амжилттай боллоо.');
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
    }

    public function getTreeCategoryList($dataViewId) {
        $result = $this->db->GetAll("
            SELECT 
                MGG.REF_STRUCTURE_ID AS ID, 
                MD.META_DATA_NAME AS NAME, 
                MGG.PARAM_NAME AS FILTER_FIELD 
            FROM META_GROUP_CONFIG MGG 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = MGG.REF_STRUCTURE_ID 
                LEFT JOIN CUSTOMER_DV_FIELD CF ON CF.META_DATA_ID = MGG.MAIN_META_DATA_ID 
                    AND LOWER(CF.FIELD_PATH) = LOWER(MGG.FIELD_PATH) 
            WHERE MGG.MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                AND MGG.IS_GROUP = 1 
                AND (CF.IS_IGNORE_TREE_GROUP IS NULL OR CF.IS_IGNORE_TREE_GROUP = 0) 
            ORDER BY MGG.DISPLAY_ORDER ASC", array($dataViewId));

        $categoryList = array();
        $filterFieldList = array();

        if ($result) {
            foreach ($result AS $k => $row) {
                $categoryList[$k]['ID'] = $row['ID'];
                $categoryList[$k]['NAME'] = $row['NAME'];
                $filterFieldList[$row['ID']] = $row['FILTER_FIELD'];
            }
        } else {
            return false;
        }

        return array('CATEGORY_LIST' => $categoryList, 'FILTER_FIELD' => $filterFieldList);
    }

    public function getStructureDefaultValues($dataViewId, $structureMetaDataId) {

        $row = $this->db->GetRow("
            SELECT 
                MGG.FIELD_PATH 
            FROM META_GROUP_CONFIG MGG 
                LEFT JOIN CUSTOMER_DV_FIELD CF ON CF.META_DATA_ID = MGG.MAIN_META_DATA_ID 
                    AND LOWER(CF.FIELD_PATH) = LOWER(MGG.FIELD_PATH) 
            WHERE MGG.MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                AND MGG.REF_STRUCTURE_ID = ".$this->db->Param(1)." 
                AND MGG.IS_GROUP = 1 
                AND (CF.IS_IGNORE_TREE_GROUP IS NULL OR CF.IS_IGNORE_TREE_GROUP = 0)", array($dataViewId, $structureMetaDataId)
        );

        if ($row) {
            $isCheck = Mdwebservice::checkParamDefaultValues($dataViewId, $row['FIELD_PATH'], $structureMetaDataId);
            if ($isCheck) {
                return Mdwebservice::getParamDefaultValues($dataViewId, $row['FIELD_PATH'], $structureMetaDataId);
            }
        }

        return null;
    }
    
    public function getDVMainQueriesModel($metaDataId) {

        $cache = phpFastCache();

        $data = $cache->get('dvMainQueries_'.$metaDataId);

        if ($data == null) {
            $data = $this->db->GetRow("SELECT TABLE_NAME, POSTGRE_SQL, MS_SQL FROM META_GROUP_LINK WHERE META_DATA_ID = ".$this->db->Param(0), array($metaDataId));
            $cache->set('dvMainQueries_'.$metaDataId, $data, Mdwebservice::$expressionCacheTime);
        }

        return $data;
    }

    public function getTreeDataByValue($dataViewId, $structureMetaDataId, $parentId) {
        
        $this->load->model('mddatamodel', 'middleware/models/');

        $result = $noOrderdvids = array();
        
        $getCodeNameFieldName = $this->model->getCodeNameFieldNameModel($structureMetaDataId);
        
        $idField     = $getCodeNameFieldName['id'];
        $codeField   = $getCodeNameFieldName['code'];
        $nameField   = $getCodeNameFieldName['name'];
        $parentField = $getCodeNameFieldName['parent'];
        
        $param = array(
            'systemMetaGroupId' => $structureMetaDataId,
            'showQuery'         => 0,
            'isShowAggregate'   => 0,
            'ignorePermission'  => 1, 
            'treeGrid'          => 1
        );
        
        if (Config::isCode('isTreeNoOrderDvids')) {
            $noOrderdvids = explode(',', Config::getFromCache('isTreeNoOrderDvids'));
        } 
        
        if ($codeField && !in_array($structureMetaDataId, $noOrderdvids)) {
            $param['paging']['sortColumnNames'] = array(
                $codeField => array(
                    'sortType' => 'asc'
                )
            );
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
        
        if ($values = self::getStructureDefaultValues($dataViewId, $structureMetaDataId)) {
            $param['criteria'][$idField][] = array(
                'operator' => 'IN',
                'operand' => Arr::implode_key(',', $values, 'VALUE_ID', true) 
            );
        }
        
        $data = $this->ws->runArrayResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] == 'success' && isset($data['result'])) {

            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);

            $treeData = $data['result'];
            
            if ($treeData) {
                
                $k = 0;
            
                if ($parentId == '#') {
                    $result[$k]['id'] = 'all';
                    $result[$k]['text'] = Lang::line('All');
                    $result[$k]['children'] = false;
                    $k++;
                }

                foreach ($treeData as $tree) {
                    
                    $isChildRecordCount = (isset($tree['childrecordcount']) ? true : false);
                    
                    $result[$k]['id'] = $tree[$idField];
                    $result[$k]['text'] = $tree[$nameField];
                    $result[$k]['rowdata'] = $tree;
                    $result[$k]['children'] = $isChildRecordCount;
                    
                    if (issetParam($tree['icon'])) {
                        $result[$k]['icon'] = $tree['icon'] . ' text-orange-300';
                    }
                    
                    if (issetParam($tree['isopen']) == '1' && $isChildRecordCount) {
                        $result[$k]['state']['opened'] = true;
                        $result[$k]['children'] = $this->getTreeDataByValue($dataViewId, $structureMetaDataId, $tree[$idField]);
                    }
                    
                    if (issetParam($tree['isselected']) == '1') {
                        $result[$k]['state']['selected'] = true;
                    }
                    
                    $k++;
                }

                if ($parentId == '#') {
                    $result[$k]['id'] = 'null';
                    $result[$k]['text'] = Lang::line('MET_99990646');
                    $result[$k]['children'] = false;
                }
            }
        } 
        
        return $result;
    }

    public function getDataViewMetaValueAttributes($processId, $paramRealPath, $dataViewId) {

        $cache = phpFastCache();

        if (empty($paramRealPath) && empty($processId)) {
            $arr = $cache->get('dvAttributes_'.$dataViewId.'_');
        } else {
            $paramRealPathHash = md5(strtolower($paramRealPath));
            $arr = $cache->get('dvAttributes_'.$dataViewId.'_'.$processId.'_'.$paramRealPathHash);
        }

        if ($arr == null) {
            
            global $db;
            $arr = array();
            $idPh = $db->Param(0);
            
            $idField = $db->GetOne("
                SELECT 
                    FIELD_PATH 
                FROM META_GROUP_CONFIG 
                WHERE MAIN_META_DATA_ID = $idPh  
                    AND IS_SELECT = 1 
                    AND INPUT_NAME = 'META_VALUE_ID' 
                    AND (COLUMN_NAME IS NOT NULL OR EXPRESSION_STRING IS NOT NULL)", array($dataViewId));

            $codeField = $db->GetOne("
                SELECT 
                    FIELD_PATH 
                FROM META_GROUP_CONFIG 
                WHERE MAIN_META_DATA_ID = $idPh  
                    AND IS_SELECT = 1 
                    AND INPUT_NAME = 'META_VALUE_CODE' 
                    AND (COLUMN_NAME IS NOT NULL OR EXPRESSION_STRING IS NOT NULL)", array($dataViewId));

            $nameField = $db->GetOne("
                SELECT 
                    FIELD_PATH 
                FROM META_GROUP_CONFIG 
                WHERE MAIN_META_DATA_ID = $idPh  
                    AND IS_SELECT = 1 
                    AND INPUT_NAME = 'META_VALUE_NAME' 
                    AND (COLUMN_NAME IS NOT NULL OR EXPRESSION_STRING IS NOT NULL)", array($dataViewId));

            if ($idField) {
                $arr['id'] = $idField;
            }
            if ($codeField) {
                $arr['code'] = $codeField;
            }
            if ($nameField) {
                $arr['name'] = $nameField;
            }
            
            if (Mdwebservice::checkParamDefaultValues($processId, $paramRealPath, $dataViewId)) {
                $arr['isDefaultValues'] = true;
            }

            if (empty($paramRealPath) && empty($processId)) {
                $cache->set('dvAttributes_'.$dataViewId.'_', $arr, Mdwebservice::$expressionCacheTime);
            } else {
                $cache->set('dvAttributes_'.$dataViewId.'_'.$processId.'_'.$paramRealPathHash, $arr, Mdwebservice::$expressionCacheTime);
            }
        }

        return $arr;
    }

    public function getDataViewMetaValueId($dataViewId) {
        $idField = $this->db->GetOne("
            SELECT 
                FIELD_PATH 
            FROM META_GROUP_CONFIG 
            WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                AND IS_SELECT = 1 
                AND INPUT_NAME = 'META_VALUE_ID' 
                AND (COLUMN_NAME IS NOT NULL OR EXPRESSION_STRING IS NOT NULL)", array($dataViewId));

        if ($idField) {
            return $idField;
        }
        return false;
    }

    public function getDataViewMetaValueCode($dataViewId) {
        $codeField = $this->db->GetOne("
            SELECT 
                FIELD_PATH 
            FROM META_GROUP_CONFIG 
            WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)."  
                AND IS_SELECT = 1 
                AND INPUT_NAME = 'META_VALUE_CODE'  
                AND (COLUMN_NAME IS NOT NULL OR EXPRESSION_STRING IS NOT NULL)", array($dataViewId));

        if ($codeField) {
            return $codeField;
        }
        return false;
    }

    public function getDataViewMetaValueName($dataViewId) {
        $nameField = $this->db->GetOne("
            SELECT 
                FIELD_PATH 
            FROM META_GROUP_CONFIG 
            WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)."  
                AND IS_SELECT = 1 
                AND INPUT_NAME = 'META_VALUE_NAME' 
                AND (COLUMN_NAME IS NOT NULL OR EXPRESSION_STRING IS NOT NULL)", array($dataViewId));

        if ($nameField) {
            return $nameField;
        }
        return false;
    }

    public function dataViewHeaderDataModel($dataViewId) {

        $cache = phpFastCache();

        $crmUserId = Session::get(SESSION_PREFIX . 'crmuserid');
        $userId = $crmUserId ? $crmUserId : Ue::sessionUserId();
        
        $data = $cache->get('dvHdrData_' . $dataViewId . '_' . $userId);

        if ($data == null) {
            
            $idPh1 = $this->db->Param(0);
            $idPh2 = $this->db->Param(1);
            
            $sql = "
                SELECT 
                    0 AS GROUP_PARAM_CONFIG_TOTAL, 
                    (
                        SELECT 
                            ".$this->db->listAgg('PARAM_PATH', '|', 'PARAM_PATH')."  
                        FROM META_GROUP_PARAM_CONFIG 
                        WHERE GROUP_META_DATA_ID = $idPh1 
                            AND LOOKUP_META_DATA_ID IS NOT NULL 
                            AND (IS_GROUP = 0 OR IS_GROUP IS NULL) 
                            AND LOWER(FIELD_PATH) = LOWER(GC.FIELD_PATH) 
                    ) AS GROUP_CONFIG_PARAM_PATH, 
                    (
                        SELECT 
                            ".$this->db->listAgg('PARAM_META_DATA_CODE', '|', 'PARAM_PATH')."  
                        FROM META_GROUP_PARAM_CONFIG  
                        WHERE GROUP_META_DATA_ID = $idPh1 
                            AND LOOKUP_META_DATA_ID IS NOT NULL 
                            AND (IS_GROUP = 0 OR IS_GROUP IS NULL) 
                            AND LOWER(FIELD_PATH) = LOWER(GC.FIELD_PATH) 
                    ) AS GROUP_CONFIG_LOOKUP_PATH, 
                    (
                        SELECT 
                            ".$this->db->listAgg('FIELD_PATH', '|', 'FIELD_PATH')."  
                        FROM META_GROUP_PARAM_CONFIG 
                        WHERE GROUP_META_DATA_ID = $idPh1 
                            AND LOOKUP_META_DATA_ID IS NOT NULL 
                            AND (IS_GROUP = 0 OR IS_GROUP IS NULL) 
                            AND LOWER(PARAM_PATH) = LOWER(GC.FIELD_PATH) 
                    ) AS GROUP_CONFIG_FIELD_PATH, 
                    '' AS GROUP_CONFIG_GROUP_PATH, 
                    GC.PARAM_NAME AS META_DATA_CODE, 
                    GC.LABEL_NAME AS META_DATA_NAME,  
                    LOWER(GC.PARAM_NAME) AS LOWER_PARAM_NAME, 
                    REPLACE(GC.FIELD_PATH, '.', '') AS NODOT_PARAM_REAL_PATH, 
                    null AS ATTRIBUTE_ID_COLUMN, 
                    null AS ATTRIBUTE_CODE_COLUMN, 
                    null AS ATTRIBUTE_NAME_COLUMN, 
                    1 AS IS_SHOW,  
                    GC.IS_REQUIRED, 
                    GC.DEFAULT_VALUE,  
                    GC.RECORD_TYPE, 
                    GC.LOOKUP_META_DATA_ID, 
                    GC.LOOKUP_META_DATA_ID AS LOOKUP_KEY_META_DATA_ID, 
                    LMD.META_TYPE_ID AS LOOKUP_META_TYPE_ID, 
                    GC.LOOKUP_TYPE, 
                    GC.CHOOSE_TYPE, 
                    GC.DISPLAY_FIELD, 
                    GC.VALUE_FIELD, 
                    GC.FIELD_PATH AS PARAM_REAL_PATH, 
                    GC.DATA_TYPE AS META_TYPE_CODE, 
                    GC.TAB_NAME,
                    GC.SIDEBAR_NAME, 
                    GC.FEATURE_NUM, 
                    GC.IS_SAVE, 
                    GC.FILE_EXTENSION,
                    GC.MIN_VALUE, 
                    GC.MAX_VALUE, 
                    MFP.PATTERN_TEXT,
                    MFP.PATTERN_NAME, 
                    MFP.GLOBE_MESSAGE,
                    MFP.IS_MASK,
                    GC.COLUMN_WIDTH,
                    GC.MAIN_META_DATA_ID, 
                    GC.ID, 
                    GC.PARENT_ID, 
                    GC.IS_MANDATORY_CRITERIA, 
                    '' AS IS_REFRESH, 
                    '' AS TAB_INDEX, 
                    GC.FRACTION_RANGE,
                    GC.SEARCH_GROUPING_NAME, 
                    GC.IS_CRITERIA_SHOW_BASKET, 
                    ". $this->db->IfNull('GC.IS_ADVANCED', '0') ." AS IS_ADVANCED, 
                    GC.IS_COUNTCARD, 
                    GC.DEFAULT_OPERATOR, 
                    GC.IS_KPI_CRITERIA, 
                    GC.IS_PASS_FILTER, 
                    GC.REF_STRUCTURE_ID,                     
                    GC.IS_NOT_SHOW_CRITERIA,
                    GC.RENDER_TYPE,
                    ".$this->db->IfNull('GC.PLACEHOLDER_NAME', 'GC.LABEL_NAME')." AS PLACEHOLDER_NAME, 
                    (
                        SELECT 
                            COUNT(ID) 
                        FROM CUSTOMER_DV_FIELD 
                        WHERE META_DATA_ID = $idPh1 
                            AND IS_ACTIVE = 1 
                            AND (IS_IGNORE_TREE_GROUP IS NULL OR IS_IGNORE_TREE_GROUP = 0) 
                    ) AS COUNT_CUSTOMER 
                FROM META_GROUP_CONFIG GC 
                    LEFT JOIN META_DATA LMD ON LMD.META_DATA_ID = GC.LOOKUP_META_DATA_ID 
                    LEFT JOIN META_FIELD_PATTERN MFP ON MFP.PATTERN_ID = GC.PATTERN_ID 
                    LEFT JOIN META_GROUP_CONFIG_USER GCU ON GC.MAIN_META_DATA_ID = GCU.MAIN_META_DATA_ID 
                        AND LOWER(GCU.PARAM_NAME) = LOWER(GC.FIELD_PATH) 
                        AND GCU.USER_ID = $idPh2 
                WHERE GC.MAIN_META_DATA_ID = $idPh1 
                    AND (GC.IS_CRITERIA = 1 OR GCU.IS_CRITERIA = 1)
                ORDER BY GC.SECOND_DISPLAY_ORDER ASC, GC.DISPLAY_ORDER ASC";
            
            $data = $this->db->GetAll($sql, array($dataViewId, $userId));

            if (isset($data[0]['COUNT_CUSTOMER']) && $data[0]['COUNT_CUSTOMER'] > 0) {
                
                $sql = str_replace('GC.DEFAULT_VALUE,', $this->db->IfNull('DF.DEFAULT_VALUE', 'GC.DEFAULT_VALUE').' AS DEFAULT_VALUE,', $sql); 
                $sql = str_replace('GC.IS_MANDATORY_CRITERIA,', 'DF.IS_MANDATORY_CRITERIA,', $sql); 
                $sql = str_replace('GC.LABEL_NAME AS META_DATA_NAME,', $this->db->IfNull('DF.LABEL_NAME', 'GC.LABEL_NAME').' AS META_DATA_NAME,', $sql);
                $sql = str_replace('GC.IS_REQUIRED,', $this->db->IfNull('DF.IS_REQUIRED', 'GC.IS_REQUIRED').' AS IS_REQUIRED,', $sql); 
                $sql = str_replace('WHERE GC.MAIN_META_DATA_ID', 'LEFT JOIN CUSTOMER_DV_FIELD DF ON GC.MAIN_META_DATA_ID = DF.META_DATA_ID AND LOWER(DF.FIELD_PATH) = LOWER(GC.FIELD_PATH) AND DF.IS_ACTIVE = 1 WHERE GC.MAIN_META_DATA_ID ', $sql);                
                $sql = str_replace('GC.DISPLAY_ORDER ASC', $this->db->IfNull('DF.DISPLAY_ORDER', 'GC.DISPLAY_ORDER').' ASC ', $sql);
                $sql = str_replace('AND (GC.IS_CRITERIA = 1', 'AND (((GC.IS_CRITERIA = 1 AND (DF.ID IS NULL OR DF.IS_SHOW = 1)) OR (DF.IS_CRITERIA = 1 AND DF.IS_SHOW = 1) OR DF.IS_CRITERIA = 1)', $sql);            
                  
                $data = $this->db->GetAll($sql, array($dataViewId, $userId));                
            }
            
            $cache->set('dvHdrData_' . $dataViewId . '_' . $userId, $data, Mdwebservice::$expressionCacheTime);
        }

        return $data;
    }

    public function dataViewHeaderDataUmCriteriaModel($dataViewId) {

        $cache = phpFastCache();

        $data = $cache->get('dvHdrDataUmCriteria_' . $dataViewId);

        if ($data == null) {
            
            $idPh = $this->db->Param(0);
            
            $data = $this->db->GetAll("
                SELECT 
                    0 AS GROUP_PARAM_CONFIG_TOTAL, 
                    (
                        SELECT 
                            ".$this->db->listAgg('PARAM_PATH', '|', 'PARAM_PATH')."  
                        FROM META_GROUP_PARAM_CONFIG 
                        WHERE GROUP_META_DATA_ID = $idPh    
                            AND LOOKUP_META_DATA_ID IS NOT NULL 
                            AND (IS_GROUP = 0 OR IS_GROUP IS NULL) 
                            AND LOWER(FIELD_PATH) = LOWER(GC.FIELD_PATH) 
                    ) AS GROUP_CONFIG_PARAM_PATH, 
                    (
                        SELECT 
                            ".$this->db->listAgg('PARAM_META_DATA_CODE', '|', 'PARAM_PATH')."  
                        FROM META_GROUP_PARAM_CONFIG 
                        WHERE GROUP_META_DATA_ID = $idPh   
                            AND LOOKUP_META_DATA_ID IS NOT NULL 
                            AND (IS_GROUP = 0 OR IS_GROUP IS NULL) 
                            AND LOWER(FIELD_PATH) = LOWER(GC.FIELD_PATH) 
                    ) AS GROUP_CONFIG_LOOKUP_PATH, 
                    (
                        SELECT 
                            ".$this->db->listAgg('FIELD_PATH', '|', 'FIELD_PATH')."  
                        FROM META_GROUP_PARAM_CONFIG 
                        WHERE GROUP_META_DATA_ID = $idPh   
                            AND LOOKUP_META_DATA_ID IS NOT NULL 
                            AND (IS_GROUP = 0 OR IS_GROUP IS NULL) 
                            AND LOWER(PARAM_PATH) = LOWER(GC.FIELD_PATH) 
                    ) AS GROUP_CONFIG_FIELD_PATH, 
                    '' AS GROUP_CONFIG_GROUP_PATH, 
                    null AS ATTRIBUTE_ID_COLUMN, 
                    null AS ATTRIBUTE_CODE_COLUMN, 
                    null AS ATTRIBUTE_NAME_COLUMN, 
                    GC.ID, 
                    GC.PARENT_ID, 
                    GC.PARAM_NAME AS META_DATA_CODE, 
                    GC.LABEL_NAME AS META_DATA_NAME,  
                    LOWER(GC.PARAM_NAME) AS LOWER_PARAM_NAME, 
                    REPLACE(GC.FIELD_PATH, '.', '') AS NODOT_PARAM_REAL_PATH, 
                    GC.IS_SHOW, 
                    GC.IS_REQUIRED, 
                    GC.DEFAULT_VALUE,  
                    GC.RECORD_TYPE, 
                    GC.LOOKUP_META_DATA_ID, 
                    LMD.META_TYPE_ID AS LOOKUP_META_TYPE_ID, 
                    GC.LOOKUP_TYPE, 
                    GC.CHOOSE_TYPE, 
                    GC.DISPLAY_FIELD, 
                    GC.VALUE_FIELD, 
                    GC.FIELD_PATH AS PARAM_REAL_PATH, 
                    GC.DATA_TYPE AS META_TYPE_CODE, 
                    GC.TAB_NAME,
                    GC.SIDEBAR_NAME, 
                    GC.FEATURE_NUM, 
                    GC.IS_SAVE, 
                    GC.FILE_EXTENSION, 
                    GC.MIN_VALUE, 
                    GC.MAX_VALUE, 
                    MFP.PATTERN_TEXT,
                    MFP.PATTERN_NAME, 
                    MFP.GLOBE_MESSAGE,
                    MFP.IS_MASK,
                    GC.COLUMN_WIDTH,
                    GC.MAIN_META_DATA_ID, 
                    GC.IS_MANDATORY_CRITERIA,
                    '' AS IS_REFRESH, 
                    GC.FRACTION_RANGE, 
                    GC.SEARCH_GROUPING_NAME, 
                    GC.DEFAULT_OPERATOR, 
                    ".$this->db->IfNull('GC.PLACEHOLDER_NAME', 'GC.LABEL_NAME')." AS PLACEHOLDER_NAME, 
                    ".$this->db->IfNull('GC.IS_ADVANCED', '0')." AS IS_ADVANCED
                FROM META_GROUP_CONFIG GC 
                    LEFT JOIN META_DATA LMD ON LMD.META_DATA_ID = GC.LOOKUP_META_DATA_ID 
                    LEFT JOIN META_FIELD_PATTERN MFP ON MFP.PATTERN_ID = GC.PATTERN_ID        
                WHERE GC.MAIN_META_DATA_ID = $idPh 
                    AND GC.IS_UM_CRITERIA = 1
                ORDER BY GC.DISPLAY_ORDER ASC", array($dataViewId));

            $cache->set('dvHdrDataUmCriteria_' . $dataViewId, $data, Mdwebservice::$expressionCacheTime);
        }

        return $data;
    }        

    public function getPackageChildMetasModel($srcId) {

        $subWsMapQuery = '';
        $subWsMapSelectQuery = '';
        if (!Input::isEmpty('workSpaceId') && (Input::post('workSpaceId') == '1559633026082903' || Input::post('workSpaceId') == '1563934488152396' || Input::post('workSpaceId') == '1565750489867295')) {
            $subWsMapSelectQuery = ',WS.PARAM_PATH';
            $subWsMapQuery = ' LEFT JOIN (SELECT 
                    LOWER(PM.FIELD_PATH) AS FIELD_PATH, 
                    LOWER(PM.PARAM_PATH) AS PARAM_PATH, 
                    PM.TARGET_META_ID
                FROM META_WORKSPACE_PARAM_MAP PM
                WHERE PM.WORKSPACE_META_ID = ' . Input::post('workSpaceId') .
                ') WS ON WS.TARGET_META_ID = MD.META_DATA_ID ';
        }

        $data = $this->db->GetAll("
            SELECT 
                MD.META_DATA_ID, 
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME, 
                MD.META_TYPE_ID,  
                LOWER(MT.META_TYPE_CODE) AS META_TYPE_CODE,
                COUNT(MDD.MAIN_META_DATA_ID) AS OPEN_BP_COUNT
                $subWsMapSelectQuery
            FROM META_META_MAP DM 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = DM.TRG_META_DATA_ID  
                LEFT JOIN META_TYPE MT ON MT.META_TYPE_ID = MD.META_TYPE_ID 
                LEFT JOIN CUSTOMER_USE_CHILD CC ON CC.SRC_META_DATA_ID = DM.SRC_META_DATA_ID 
                    AND CC.TRG_META_DATA_ID = DM.TRG_META_DATA_ID
                LEFT JOIN META_DM_PROCESS_DTL MDD ON MDD.MAIN_META_DATA_ID = MD.META_DATA_ID 
                    AND MDD.IS_BP_OPEN = 1
                $subWsMapQuery
            WHERE DM.SRC_META_DATA_ID = ".$this->db->Param(0)." 
                AND MD.IS_ACTIVE = 1 
                AND (CC.IS_USE IS NULL OR CC.IS_USE = 1) 
            GROUP BY MD.META_DATA_ID,
              MD.META_DATA_CODE,
              MD.META_DATA_NAME,
              MD.META_TYPE_ID,
              MT.META_TYPE_CODE,
              DM.ORDER_NUM          
              $subWsMapSelectQuery      
            ORDER BY DM.ORDER_NUM ASC", array($srcId));

        return $data;
    }

    public function getPackageChildMetasPermissionModel($srcId) {

        $this->db->StartTrans(); 
        $this->db->Execute(Ue::createSessionInfo());        

        $data = $this->db->GetAll("
            SELECT 
                MD.META_DATA_ID, 
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME, 
                MD.META_TYPE_ID, 
                LOWER(MT.META_TYPE_CODE) AS META_TYPE_CODE,
                COUNT(MDD.MAIN_META_DATA_ID) AS OPEN_BP_COUNT
            FROM VW_META_DATA TRG_MD 
                INNER JOIN META_META_MAP DM ON TRG_MD.META_DATA_ID = DM.TRG_META_DATA_ID 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = DM.TRG_META_DATA_ID  
                LEFT JOIN META_TYPE MT ON MT.META_TYPE_ID = MD.META_TYPE_ID 
                LEFT JOIN CUSTOMER_USE_CHILD CC ON CC.SRC_META_DATA_ID = DM.SRC_META_DATA_ID 
                    AND CC.TRG_META_DATA_ID = DM.TRG_META_DATA_ID 
                LEFT JOIN META_DM_PROCESS_DTL MDD ON MDD.MAIN_META_DATA_ID = MD.META_DATA_ID 
                    AND MDD.IS_BP_OPEN = 1 
            WHERE DM.SRC_META_DATA_ID = ".$this->db->Param(0)." 
                AND MD.IS_ACTIVE = 1 
                AND (CC.IS_USE IS NULL OR CC.IS_USE = 1) 
            GROUP BY 
                MD.META_DATA_ID, 
                MD.META_DATA_CODE,
                MD.META_DATA_NAME,
                MD.META_TYPE_ID,
                MT.META_TYPE_CODE,
                DM.ORDER_NUM                
            ORDER BY DM.ORDER_NUM ASC", array($srcId));

        $this->db->CompleteTrans();   

        return $data;
    }

    public function getNameByTypeModel($metaDataId, $metaTypeId, $metaDataName = '') {

        if ($metaTypeId == Mdmetadata::$metaGroupMetaTypeId) {

            return $this->db->GetOne("
                SELECT 
                   ".$this->db->IfNull("GL.LIST_NAME", "MD.META_DATA_NAME")." AS META_DATA_NAME 
                FROM META_DATA MD 
                    LEFT JOIN META_GROUP_LINK GL ON GL.META_DATA_ID = MD.META_DATA_ID 
                WHERE MD.META_DATA_ID = ".$this->db->Param(0), array($metaDataId));

        } elseif ($metaTypeId == Mdmetadata::$businessProcessMetaTypeId) {

            return $this->db->GetOne("
                SELECT 
                   ".$this->db->IfNull("PL.PROCESS_NAME", "MD.META_DATA_NAME")." AS META_DATA_NAME 
                FROM META_DATA MD 
                    LEFT JOIN META_BUSINESS_PROCESS_LINK PL ON PL.META_DATA_ID = MD.META_DATA_ID 
                WHERE MD.META_DATA_ID = ".$this->db->Param(0), array($metaDataId));

        } elseif ($metaTypeId == Mdmetadata::$statementMetaTypeId) {

            return $this->db->GetOne("
                SELECT 
                   ".$this->db->IfNull("PL.REPORT_NAME", "MD.META_DATA_NAME")." AS META_DATA_NAME 
                FROM META_DATA MD 
                    LEFT JOIN META_STATEMENT_LINK PL ON PL.META_DATA_ID = MD.META_DATA_ID 
                WHERE MD.META_DATA_ID = ".$this->db->Param(0), array($metaDataId));
        }

        return $metaDataName;
    }

    public function getDataViewCriteriaTypeModel($metaDataId) {
        return $this->db->GetRow("SELECT SEARCH_TYPE FROM META_GROUP_LINK WHERE META_DATA_ID = ".$this->db->Param(0), array($metaDataId));
    }

    public function saveMetaGroupConfigUserModel() {
        
        try {
        
            $metaDataId = Input::numeric('metaDataId');
            $crmUserId = Session::get(SESSION_PREFIX . 'crmuserid');
            $userId = $crmUserId ? $crmUserId : Ue::sessionUserId();
            $param = Input::postData();
            $ticket = false;

            if (isset($param['width'])) {

                $tick = true;
                $configUser = $this->db->GetAll("SELECT * FROM META_GROUP_CONFIG_USER WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." AND USER_ID = ".$this->db->Param(1)." ORDER BY ORDER_NUM ASC", array($metaDataId, $userId));

                if ($configUser) {
                    
                    $groupData = Arr::groupByArrayOnlyRow($configUser, 'PARAM_NAME', false);
                    
                    if (isset($groupData[$param['field']])) {
                        $tick = false;
                        $this->db->AutoExecute('META_GROUP_CONFIG_USER', array('PARAM_WIDTH' => $param['width']), 'UPDATE', "ID = ". $groupData[$param['field']]['ID']);
                    }
                }

                if ($tick) {

                    $this->resetDataViewUserConfigModel($metaDataId, $userId);

                    $fields = self::getDataViewGridAllFieldsModel($metaDataId);
                    $groupData = Arr::groupByArrayOnlyRow($fields, 'FIELD_PATH', false);
                    $order = 1;

                    foreach ($groupData as $key => $fieldRow) {
                        $data = array(
                            'ID'                => getUID(),
                            'USER_ID'           => $userId,
                            'MAIN_META_DATA_ID' => $metaDataId,
                            'PARAM_NAME'        => $key,
                            'IS_SHOW'           => '1',
                            'ORDER_NUM'         => $order,
                            'IS_FREEZE'         => '0',
                            'HEADER_NAME'       => '',
                            'PARAM_WIDTH'       => $param['field'] == $key ? $param['width'] : '',
                        );
                        $this->db->AutoExecute('META_GROUP_CONFIG_USER', $data);
                        $order++;
                    }
                }

            } else {

                $this->resetDataViewUserConfigModel($metaDataId, $userId);

                if (in_array('1', Input::param($param['CONFIG_ORDER']))) {
                    $ticket = true;
                }

                foreach ($param['CONFIG_ORDER'] as $key => $order) {

                    $paramName = strtolower(Input::param($param['FIELD_PATH'][$key]));

                    $data = array(
                        'ID'                => getUID(),
                        'USER_ID'           => $userId,
                        'MAIN_META_DATA_ID' => $metaDataId,
                        'PARAM_NAME'        => $paramName,
                        'IS_SHOW'           => Input::param($param['IS_SHOW'][$key]),
                        'ORDER_NUM'         => Input::param($param['CONFIG_ORDER'][$key]),
                        'IS_FREEZE'         => Input::param($param['IS_FREEZE'][$key]),
                        'IS_CRITERIA'       => Input::param($param['IS_CRITERIA'][$key]),
                        'HEADER_NAME'       => Input::param($param['headerName'][$key]),
                    );
                    $this->db->AutoExecute('META_GROUP_CONFIG_USER', $data);
                }
            }
            
            $tmp_dir = Mdcommon::getCacheDirectory();
            $dvUserConfigs = glob($tmp_dir."/*/dv/dvUserConfigMergeCols2_".$metaDataId."_".$userId.".txt");
            foreach ($dvUserConfigs as $dvUserConfig) {
                @unlink($dvUserConfig);
            }            
            (new Mdmeta())->dataViewCacheClear($tmp_dir, $metaDataId);

            return array('status' => 'success', 'message' => $this->lang->line('msg_save_success'), 'objectValueViewType' => '');
        
        } catch (Exception $ex) {
            return array('status' => 'error', 'message' => $ex->getMessage(), 'objectValueViewType' => '');
        }
    }
    
    public function resetDataViewUserConfigModel($dvId, $userId) {
        
        try {
            $this->db->Execute("DELETE FROM META_GROUP_CONFIG_USER WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." AND USER_ID = ".$this->db->Param(1), array($dvId, $userId));
            return array('status' => 'success', 'message' => $this->lang->line('dv_config_reset_success'));
        } catch (Exception $ex) {
            return array('status' => 'error', 'message' => $ex->getMessage());
        }
    }

    public function getDrillDownMetaDataModel($metaDataId = null, $fieldPath = null) {

        $metaDataId = ($metaDataId) ? $metaDataId : Input::numeric('metaDataId');
        $fieldPath = ($fieldPath) ? $fieldPath : Input::post('fieldPath');
        
        if ($metaDataId == '' || $fieldPath == '') {
            return array();
        }
        
        $fieldPath = strtolower($fieldPath);
        $cacheName = 'dv_'.$metaDataId.'_fdrill_'.md5($fieldPath);
        
        $cache = phpFastCache();
        $data = $cache->get($cacheName);
        
        if ($data == null) {
            $data = $this->db->GetAll("
                SELECT 
                    MGL.ID,
                    MGL.META_DATA_ID,
                    MDD.LINK_META_DATA_ID, 
                    MDD.CRITERIA,
                    MDD.DIALOG_WIDTH,
                    MDD.DIALOG_HEIGHT,
                    MDD.SHOW_TYPE,
                    MDD.MAIN_GROUP_LINK_PARAM,
                    MT.META_TYPE_NAME, 
                    LOWER(MT.META_TYPE_CODE) AS META_TYPE_CODE,
                    MDDP.DEFAULT_VALUE,
                    LOWER(MDDP.SRC_PARAM) AS SRC_PARAM,
                    LOWER(MDDP.TRG_PARAM) AS TRG_PARAM, 
                    LOWER(DMD.PASSWORD_PATH) AS PASSWORD_PATH 
                FROM META_GROUP_LINK MGL 
                    INNER JOIN META_DM_DRILLDOWN_DTL MDD ON MDD.MAIN_GROUP_LINK_ID = MGL.ID 
                    INNER JOIN META_DATA MDA ON MDA.META_DATA_ID = MDD.LINK_META_DATA_ID 
                    INNER JOIN META_TYPE MT ON MT.META_TYPE_ID = MDA.META_TYPE_ID 
                    LEFT JOIN META_DM_DRILLDOWN_PARAM MDDP ON MDDP.DM_DRILLDOWN_DTL_ID = MDD.ID 
                    LEFT JOIN META_DM_PROCESS_DTL DMD ON MGL.META_DATA_ID = DMD.MAIN_META_DATA_ID 
                        AND DMD.PROCESS_META_DATA_ID = MDD.LINK_META_DATA_ID 
                WHERE MGL.META_DATA_ID = ".$this->db->Param(0)." 
                    AND LOWER(MDD.MAIN_GROUP_LINK_PARAM) = ".$this->db->Param(1), 
                array($metaDataId, $fieldPath) 
            );
            $cache->set($cacheName, $data, Mdwebservice::$expressionCacheTime);
        }

        return $data;
    }

    public function getDrillDownPostModel() {
        $response = array();
        if (Input::postCheck('drillDownTargedParam')) {
            $response = array('trgParam' => Input::post('drillDownTargedParam'), 'srcParam' => Input::post('drillDownSearchParam'));
        }
        return $response;
    }

    public function getWorkflowNextStatusModel($metaDataId = null, $selectedRow = array(), $resultType = '1') {
        
        $operation = 'GET_ROW_WFM_STATUS';
        
        if ($metaDataId && $selectedRow) {
            
            $param = array(
                'systemMetaGroupId' => $metaDataId,
                'showQuery' => 0, 
                'ignorePermission' => 1 
            );
            
            if (is_array($selectedRow) && array_key_exists(0, $selectedRow) && count($selectedRow) == 1) {
                $param = array_merge($param, $selectedRow[0]);
            } elseif (is_array($selectedRow)) {
                $param = array_merge($param, $selectedRow);
            }
            
        } else {
            
            $metaDataId = Input::numeric('metaDataId');
            $param = array(
                'systemMetaGroupId' => $metaDataId,
                'showQuery' => 0, 
                'ignorePermission' => 1 
            );
            
            if (Input::numeric('isIndicator') == 1) {
                
                $indicatorId = $this->getStructureIndicatorIdModel($param['systemMetaGroupId']);
                
                $param['refMetaGroupId'] = $indicatorId;
                $param['isIndicator'] = 1;
                
                unset($param['systemMetaGroupId']);
            }

            if (Input::isEmpty('dataRow') == false) {
                
                $dataRow = $_POST['dataRow'];
                
                if (Input::isEmpty('isManyRows') === false) {
                    $param['selectedRows'] = $dataRow;
                    $operation = 'GET_ROWS_WFM_STATUS';
                } else {
                    $param = array_merge($param, $dataRow);
                }
            }
        }
        
        $result = $this->ws->runResponse(self::$gfServiceAddress, $operation, $param);

        if ($resultType == '1') {
            if ($result['status'] == 'success') {

                if (!is_null($result['result'])) {

                    foreach ($result['result'] as $key => $processStatus) {

                        $getAccessProcess = array(array('processid' => $processStatus['wfmstatusprocessid']));                        
                        $advancedCriteria = self::getDataViewProcessCriteria($metaDataId, $getAccessProcess);

                        if (isset($advancedCriteria[0])){ 
                            $result['result'][$key]['advancedCriteria'] = $advancedCriteria[0]['ADVANCED_CRITERIA'];
                        }
                    }
                }

                $response = array(
                    'status'     => 'success',
                    'data'       => $result['result'],
                    'datastatus' => true, 
                    'isShowMsgNotNextStatus' => Config::getFromCache('IS_SHOW_MSG_WFM_NOT_NEXTSTATUS')
                );
                
                if ($operation == 'GET_ROWS_WFM_STATUS') {
                    $currentWfmStatusId = issetParam($param['selectedRows'][0]['wfmstatusid']);
                } else {
                    $currentWfmStatusId = issetParam($param['wfmstatusid']);
                }
                
                if ($currentWfmStatusId) {
                
                    $getUseAssignRuleId = $this->getUseAssignRuleIdModel($currentWfmStatusId);

                    if (is_array($getUseAssignRuleId)) {
                        $response['getUseAssignRuleId'] = $getUseAssignRuleId['DEFAULT_RULE_ID'];
                    }
                }

            } else {
                $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
            }
        } else {
            $response = $result;
        }

        return $response;
    }

    public function setRowWfmStatusModel() {
        
        if (Input::isEmpty('newWfmStatusid') === false) {
            
            parse_str(urldecode(Input::post('desctiption')), $description);
            
            $currentDate = Date::currentDate();
            $operation = 'SET_ROW_WFM_STATUS';
            
            $param = array(
                'systemMetaGroupId' => Input::numeric('metaDataId')
            );

            if (Input::postCheck('dataRow') && !Input::isEmpty('dataRow')) {
                
                $dataRow = Input::post('dataRow');
                
                if (Input::post('isMany') == '1') {
                    $param['selectedRows'] = $dataRow;
                    $operation = 'SET_ROWS_WFM_STATUS';
                } else {
                    if (isset($dataRow['pfnextstatuscolumn'])) {
                        unset($dataRow['pfnextstatuscolumn']);
                    }
                    
                    if ($newWfmStatusName = Input::post('newWfmStatusName')) {
                        $dataRow['newwfmstatusname'] = $newWfmStatusName;
                    }
                    
                    $param = array_merge($param, $dataRow);
                }
            }

            $param['newWfmDescription'] = Input::post('description');
            $param['newWfmStatusId'] = Input::post('newWfmStatusid');
            
            if (Input::isEmpty('nextProcessId') === false) {
                $param['nextProcessId'] = Input::post('nextProcessId');
            }

            if (Input::isEmpty('signerParams') == false) {
                $signerParams = Input::post('signerParams');
                $param['guid'] = isset($signerParams['monpassUid']) ? Input::param($signerParams['monpassUid']) : '';
                $param['contentHash'] = Input::param($signerParams['plainText']);
                $param['cipherText'] = Input::param($signerParams['cyphertext']);
            }
            
            if (Input::isEmpty('autoassignedusers') === false) {
                $param['autoAssignedUsers'] = Input::post('autoassignedusers');
            }
            
            if (isset($description['assigmentUserId'])) {

                $assignedUsers = $description['assigmentUserId'];
                $assignedUsersData = array();
                $assignedUserId = Ue::sessionUserKeyId();

                foreach ($assignedUsers as $k => $assignedUser) {

                    $assignedUsersData[] = array(
                        'userId' => Input::param($assignedUser), 
                        'isNeedSign' => isset($description['isNeedSign'][$assignedUser]) ? (isset($description['isNeedSign'][$assignedUser]) ? 1 : 2) : 0, 
                        'dueDate' => Input::param($description['dueDate'][$k]),                            
                        'assignedDate' => $currentDate,  
                        'assignedUserId' => $assignedUserId,
                        'userStatusId' => $param['newWfmStatusId'], 
                        'wfmStatusId' => $param['wfmstatusid']
                    );
                }

                $param['assignedUsers'] = $assignedUsersData;
            }
            
            if (Input::numeric('isIndicator') == 1) {
                
                $indicatorId = $this->getStructureIndicatorIdModel($param['systemMetaGroupId']);
                
                $param['refMetaGroupId'] = $indicatorId;
                $param['isIndicator'] = 1;
                
                unset($param['systemMetaGroupId']);
            }
                        
            $result = $this->ws->runResponse(self::$gfServiceAddress, $operation, $param);

            if ($result['status'] == 'success') {

                if (!empty($_FILES) && isset($_FILES['workflowFiles']) && isset($result['result'])) {
                    $fileData = $_FILES['workflowFiles'];
    
                    foreach ($fileData['name'] as $key => $fileRow) {
                        if (is_uploaded_file($fileData['tmp_name'][$key])) {
    
                            $newFileName   = 'workflowFile_' . getUID() . '_' . $key;
                            $fileExtension = strtolower(substr($fileRow, strrpos($fileRow, '.') + 1));
                            $fileName      = $newFileName . '.' . $fileExtension;
                            $filePath      = UPLOADPATH . 'process/';
                            
                            FileUpload::SetFileName($fileName);
                            FileUpload::SetTempName($fileData['tmp_name'][$key]);
                            FileUpload::SetUploadDirectory($filePath);
                            FileUpload::SetValidExtensions(explode(',', Config::getFromCache('CONFIG_FILE_EXT')));
                            FileUpload::SetMaximumFileSize(FileUpload::GetConfigFileMaxSize());
                            $uploadResult = FileUpload::UploadFile();
    
                            if ($uploadResult) {

                                $contentData = array(
                                    'CONTENT_ID' => getUID(),
                                    'FILE_NAME' => $fileRow,
                                    'PHYSICAL_PATH' => $filePath . $fileName,
                                    'FILE_SIZE' => $fileData['size'][$key],
                                    'FILE_EXTENSION' => $fileExtension,
                                    'CREATED_DATE' => $currentDate, 
                                    'IS_PHOTO' => 0
                                );
                                $this->db->AutoExecute('ECM_CONTENT', $contentData);                                

                                $contentDataMap = array(
                                    'ID' => getUID(),
                                    'CONTENT_ID' => $contentData['CONTENT_ID'],
                                    'REF_STRUCTURE_ID' => $result['result']['refstructureid'],
                                    'RECORD_ID' => $result['result']['id'],
                                    'CREATED_DATE' => $currentDate
                                );
                                
                                if ($recordId = issetParam($param['id'])) {
                                    $contentDataMap['MAIN_RECORD_ID'] = $recordId;
                                }
                                
                                $this->db->AutoExecute('ECM_CONTENT_MAP', $contentDataMap);                                
                            }
                        }
                    }
                }

                $response = array(
                    'status' => 'success', 
                    'message' => $this->lang->line('msg_save_success') 
                );
                
            } else {
                $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
            }

            return $response;
        }
        
        if (Input::isEmpty('metaDataId') === false && Input::isEmpty('recordId') === false && Input::postCheck('order')) {
            
            $sessionUserId = Ue::sessionUserKeyId();
            $currentDate   = Date::currentDate();
            $recordId      = Input::numeric('recordId');
            $wfmStatusId   = Input::numeric('wfmStatusId');
            $hdrRuleId     = Input::numeric('ruleId');
            $waitTime      = Input::numeric('waitTime');
            $waitStatusId  = Input::numeric('waitStatusId');
            $assignedUsers = array();
            $order         = $_POST['order'];

            $param = array(
                'systemMetaGroupId' => Input::numeric('metaDataId'), 
                'wfmStatusId'       => $wfmStatusId, 
                'id'                => $recordId
            );
            
            if (Input::numeric('isIndicator') == 1) {
                
                $indicatorId = $this->getStructureIndicatorIdModel($param['systemMetaGroupId']);
                
                $param['refMetaGroupId'] = $indicatorId;
                $param['isIndicator'] = 1;
                
                unset($param['systemMetaGroupId']);
            }
            
            if (Input::postCheck('selectedRow')) {
                $selectedRow = json_decode(Input::postNonTags('selectedRow'), true);
                $param = array_merge($param, $selectedRow);
            }

            foreach ($order as $key => $row) {

                $dueDate    = isset($_POST['dueDate']) ? Input::param($_POST['dueDate'][$key]) : '';
                $descassign = isset($_POST['descriptionAssign']) ? Input::param($_POST['descriptionAssign'][$key]) : '';

                if ($dueDate) {
                    $dueDate = $dueDate.' '.Input::param($_POST['dueTime'][$key]).':00';
                }

                $orderNumber     = Input::param($row);
                $assigmentUserId = Input::param($_POST['assigmentUserId'][$key]);
                $isEdit          = Input::param(issetParam($_POST['isEdit'][$assigmentUserId]));
                $weight          = ($isEdit == '2') ? Input::param(issetParam($_POST['weight'][$key])) : '';
                
                $ruleId = $hdrRuleId ? $hdrRuleId : 1;
                if ($weight) {
                    $allRuleId = $ruleId;
                }
                
                $assignedRow = array(
                    'userId'         => $assigmentUserId, 
                    'dueDate'        => $dueDate, 
                    'assignedDate'   => $currentDate, 
                    'assignedUserId' => $sessionUserId, 
                    'description'    => $descassign, 
                    'wfmStatusId'    => $wfmStatusId, 
                    'ruleId'         => $ruleId, 
                    'waittime'       => $waitTime, 
                    'waitstatusid'   => $waitStatusId, 
                    'isNeedSign'     => Input::param(issetParam($_POST['lock'][$key])), 
                    'orderNumber'    => $orderNumber, 
                    'weight'         => $weight, 
                    'isEdit'         => $isEdit, 
                    'isTransfered'   => 0
                );

                if (isset($_POST['wfmAssingmentId'][$key])) {

                    $assignedUsers[] = $assignedRow;

                    $assignedRow['id'] = Input::param($_POST['wfmAssingmentId'][$key]);
                    $assignedRow['userId'] = $this->db->GetOne('SELECT USER_ID FROM META_WFM_ASSIGNMENT WHERE ID = '.$this->db->Param(0), array($assignedRow['id']));
                    $assignedRow['isTransfered'] = 1;

                    unset($assignedRow['assignedUserId']);
                    unset($assignedRow['ruleId']);
                    unset($assignedRow['dueDate']);
                    unset($assignedRow['description']);
                    unset($assignedRow['assignedDate']);
                    unset($assignedRow['wfmStatusId']);
                }

                $assignedUsers[] = $assignedRow;
            }
            
            if (isset($allRuleId)) {
                foreach ($assignedUsers as $ak => $assignedUserRow) {
                    if (isset($assignedUserRow['ruleId'])) {
                        $assignedUsers[$ak]['ruleId'] = $allRuleId;
                    }
                }
            }

            $param['assignedUsers'] = $assignedUsers;

            $result = $this->ws->runResponse(self::$gfServiceAddress, 'SET_ROW_WFM_STATUS', $param);

            if ($result['status'] == 'success') {
                $response = array('status' => 'success', 'message' => $this->lang->line('msg_save_success'));
            } else {
                $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
            }

            return $response;
        }
        
        return array('status' => 'warning', 'message' => $this->lang->line('msg_save_error'), 'no' => 'false');
    }

    public function getRowWfmStatusLogModel($metaDataId, $dataRow = array(), $isIgnoreNextUsers = 0) {

        $param = array(
            'systemMetaGroupId' => $metaDataId,
            'showQuery' => 0, 
            'ignorePermission' => 1, 
            /*'isShowAll' => 1*/
        );
        
        if (Input::isEmpty('processId') == false) {
            $param['processId'] = Input::post('processId');
        }
        
        $paramFilters = array(
            'isFilterAdmin'     => '1', 
            'isIgnoreNextUsers' => $isIgnoreNextUsers 
        );

        if ($dataRow) {
            foreach ($dataRow as $path => $row) {
                $paramFilters[$path] = $row;
            }
        } else {
            
            if (Input::postCheck('dataRow') && !Input::isEmpty('dataRow')) {
                
                $dataRow = Input::post('dataRow');
                
                foreach ($dataRow as $path => $row) {
                    $paramFilters[$path] = $row;
                }
                
                if (issetParam($dataRow['iskpiindicator']) == '1') {
                    $_POST['isIndicator'] = 1;
                }
            }

            if (Input::postCheck('selectedRowData') && !Input::isEmpty('selectedRowData')) {
                $selectedRowData = Input::post('selectedRowData');
                $selectedRowData = Arr::decode($selectedRowData);
                
                $param = array_merge($param, $selectedRowData);
            }
        }

        $param = array_merge($param, $paramFilters);
        
        if (!isset($param['wfmstatusid']) && Input::isEmpty('wfmStatusId') == false) {
            $param['wfmStatusId'] = Input::numeric('wfmStatusId');
        }
        
        if (Input::numeric('isIndicator') == 1) {
            
            $indicatorId = $this->getStructureIndicatorIdModel($param['systemMetaGroupId']);
            
            $param['refMetaGroupId'] = $indicatorId;
            $param['isIndicator'] = 1;

            unset($param['systemMetaGroupId']);
        }
        
        $statusLog = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'GET_ROW_WFM_STATUS_LOG', $param);

        if (isset($statusLog['status']) && $statusLog['status'] == 'success') {
            $response = array(
                'status'    => 'success',
                'data'      => isset($statusLog['wfm']) ? $statusLog['wfm'] : array(),
                'datastatus'=> isset($statusLog['wfm'][1]['wfmstatusid']) ? true : false
            );
        } else {
            $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($statusLog));
        }

        return $response;
    }
    
    public function getStartWfmStatusModel($metaDataId, $row) {
        
        $param = array(
            'systemMetaGroupId' => $metaDataId,
            'showQuery' => 0, 
            'ignorePermission' => 1
        );
        
        $param = array_merge($param, $row);
        
        if (issetParam($row['isIndicator']) == '1') {
                
            $param['refStructureId'] = $param['systemMetaGroupId'];
            $param['isIndicator'] = 1;

            unset($param['systemMetaGroupId']);
        }
        
        $status = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'get_start_wfm_status', $param);
        
        if ($status['status'] == 'success' && isset($status['result']) && $status['result']) {
            return $status['result'];
        }
        
        return null;
    }

    public function getIsTreeGridModel($dataViewId) {
        
        $data = $this->db->GetAll("
            SELECT 
                LOWER(INPUT_NAME) AS INPUT_NAME,   
                LOWER(FIELD_PATH) AS FIELD_PATH 
            FROM META_GROUP_CONFIG 
            WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                AND IS_SELECT = 1 
                AND (INPUT_NAME = 'META_VALUE_ID' 
                    OR INPUT_NAME = 'META_VALUE_CODE' 
                    OR INPUT_NAME = 'META_VALUE_NAME' 
                    OR INPUT_NAME = 'PARENT_ID')", 
            array($dataViewId)
        ); 

        $isId = $isName = $isParent = false;
        $isIdName = $isNameName = $isParentName = '';

        foreach ($data as $row) {
            if ($row['INPUT_NAME'] == 'meta_value_id') {
                $isId = true;
                $isIdName = $row['FIELD_PATH'];
            }
            if ($row['INPUT_NAME'] == 'meta_value_name') {
                $isName = true;
                $isNameName = $row['FIELD_PATH'];
            }
            if ($row['INPUT_NAME'] == 'parent_id') {
                $isParent = true;
                $isParentName = $row['FIELD_PATH'];
            }
        }

        if ($isId && $isName && $isParent) {
            return "id=$isIdName&name=$isNameName&parent=$isParentName";
        }

        return null;
    }

    public function getDataViewClassModel($hiddenFields, $dataViewCriteriaType, $isCheckDataViewHeaderData, $dataViewHeaderData, $isTree) {
        $response = '';
        if (isset($hiddenFields) && $hiddenFields == '0') {
            if ($isTree) {
                if (($dataViewCriteriaType == 'left static') && $isCheckDataViewHeaderData){
                    $response = 'col-md-9';
                }  
            } else {
                if ($dataViewCriteriaType == 'left static' && !empty($dataViewHeaderData['data']) && $isCheckDataViewHeaderData) { 
                    $response = 'col-md-9';
                } 
            }
        } 
        return $response;
    }
    
    public function getColumnShowCriteriaModel($dvId) {
        
        $data = $this->db->GetAll("
            SELECT 
                LOWER(FIELD_PATH) AS FIELD_PATH, 
                STYLE_CRITERIA AS CRITERIA 
            FROM META_GROUP_CONFIG 
            WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                AND PARENT_ID IS NULL 
                AND STYLE_CRITERIA IS NOT NULL", array($dvId));
        
        return $data;
        
        /*return array(
            array(
                'column' => 'messagedescl',
                'criteria' => "startDate == '2021-11-09'"
            ), 
            array(
                'column' => 'bankaccountcode', 
                'criteria' => "messageCode == '001'"
            )
        );*/
    }
    
    public function getDataViewReportTemplatesModel($dvId) {
        $data = $this->db->GetAll("
            SELECT 
                REP.ID, 
                DTL.TEMPLATE_META_DATA_ID, 
                LMD.META_DATA_CODE, 
                LMD.META_DATA_NAME, 
                DTL.CRITERIA, 
                DTL.IS_DEFAULT 
            FROM META_DM_TEMPLATE_DTL DTL 
                INNER JOIN META_GROUP_LINK LINK ON LINK.ID = DTL.META_GROUP_LINK_ID 
                INNER JOIN META_REPORT_TEMPLATE_LINK REP ON REP.META_DATA_ID = DTL.TEMPLATE_META_DATA_ID 
                INNER JOIN META_DATA LMD ON LMD.META_DATA_ID = REP.META_DATA_ID 
            WHERE LINK.META_DATA_ID = ".$this->db->Param(0), array($dvId));
        
        return $data;
    }
    
    public function getSubQueryModel($linkId) {
        $data = $this->db->GetAll("SELECT ID, CODE, GLOBE_CODE FROM META_GROUP_SUB_QUERY WHERE META_GROUP_LINK_ID = ".$this->db->Param(0), array($linkId)); 
        return $data;
    }
    
    public function getStandartFieldModel($dataViewId, $typeCode) {
        
        $dataViewIdPh = $this->db->Param(0);
        $typeCodePh   = $this->db->Param(1);

        $bindVars = array($this->db->addQ($dataViewId), $this->db->addQ($typeCode));
        
        $field = $this->db->GetOne("
            SELECT 
                FIELD_PATH 
            FROM META_GROUP_CONFIG 
            WHERE MAIN_META_DATA_ID = $dataViewIdPh 
                AND IS_SELECT = 1 
                AND LOWER(INPUT_NAME) = $typeCodePh 
                AND (COLUMN_NAME IS NOT NULL OR EXPRESSION_STRING IS NOT NULL)", $bindVars); 

        if ($field) {
            return strtolower($field);
        }
        return null;
    }

    public function isRefStructureCheckList($dataViewId, $refStructureId) {

        $checkListCriteria = '';

        if ($refStructureId) {

            $data = $this->db->GetAll("
                SELECT
                    CT.NAME, 
                    BC.CRITERIA, 
                    BC.TEMPLATE_ID 
                FROM META_BP_CHECKLIST_CRITERIA BC 
                    INNER JOIN META_BP_CHECKLIST_TEMPLATE CT ON CT.ID = BC.TEMPLATE_ID 
                WHERE BC.REF_STRUCTURE_ID = ".$this->db->Param(0), 
                array($refStructureId)
            );

            if ($data) {

                if (count($data) == 1 && empty($data[0]['CRITERIA'])) {

                    $checkListCriteria .= "showPfCheckListButton(this, '".$data[0]['NAME']."', '".$data[0]['TEMPLATE_ID']."', '".$dataViewId."', '".$refStructureId."');";

                } else {

                    $checkListCriteria .= " var isStructureCheckListTempId = ''; ";

                    $getAllFields = self::getDataViewGridBodyDataModel($dataViewId);

                    foreach ($data as $k => $row) {

                        $rules = Str::lower($row['CRITERIA']);

                        if (is_null($rules)) {

                            $rules = '1 == 1';

                        } else {

                            foreach ($getAllFields as $field) {
                                $rules = preg_replace('/\b'.$field['FIELD_NAME'].'\b/u', 'row.'.$field['FIELD_NAME'], $rules);
                            }

                            if (strpos($rules, 'match(') !== false) {
                                
                                preg_match_all('/match\((.*?)\)/i', $rules, $matches);

                                if (count($matches[0]) > 0) {

                                    foreach ($matches[1] as $ek => $ev) {

                                        $evArr = explode(',', $ev);
                                        $matchValues = explode('|', trim($evArr[1]));
                                        $regexValues = '';

                                        foreach ($matchValues as $matchValue) {
                                            $regexValues .= $evArr[0].".indexOf('".$matchValue."') != -1 || ";
                                        }

                                        $diffs = rtrim($regexValues, '|| ');
                                        $rules = str_replace($matches[0][$ek], $diffs, $rules);
                                    }
                                }
                            }

                            $rules = Mdmetadata::defaultKeywordReplacer($rules);
                            $rules = str_replace('indexof(', 'indexOf(', $rules);
                        }

                        $checkListCriteria .= "if ($rules) {";
                        $checkListCriteria .= " isStructureCheckListTempId += '".$row['TEMPLATE_ID'].",'; "; 
                        $checkListCriteria .= '} ';
                    }

                    $checkListCriteria .= " if (isStructureCheckListTempId) { showPfCheckListButton(this, '', rtrim(isStructureCheckListTempId, ','), '".$dataViewId."', '".$refStructureId."'); }";
                    $checkListCriteria .= " else { hidePfCheckListButton(this, '".$dataViewId."'); } isStructureCheckListTempId = ''; ";
                }
            }
        }

        return $checkListCriteria;
    }

    public function isSubDataView($dataViewId, $subgridExcel = null) {

        $subGrid = '';

        $data = $this->db->GetAll("
            SELECT  
                MDDM.TRG_META_DATA_ID, 
                CASE MDDM.IS_SHOW WHEN 1 THEN ".$this->db->IfNull("GL.LIST_NAME", "MD.META_DATA_NAME")."
                ELSE '' END AS META_DATA_NAME, 
                MDDM.CRITERIA, 
                LOWER(MDDM.PARAMS) AS PARAMS 
            FROM META_DM_DM_MAP MDDM 
                INNER JOIN META_DATA MD ON MDDM.TRG_META_DATA_ID = MD.META_DATA_ID
                LEFT JOIN META_GROUP_LINK GL ON MD.META_DATA_ID = GL.META_DATA_ID
            WHERE MDDM.SRC_META_DATA_ID = ".$this->db->Param(0)." 
                AND MDDM.TRG_META_DATA_ID IS NOT NULL 
            ORDER BY MDDM.ORDER_NUMBER ASC", 
            array($dataViewId)
        );

        if ($data) {

            $subGrid = 'detailFormatter: function(index, row) {
                            return \'<div class="dv-subgrid"></div>\';
                        },
                        onExpandRow: function(index, row) { 
                            var $thisSubGrid = $(this);
                            var $ddv = $thisSubGrid.datagrid(\'getRowDetail\', index).find(\'div.dv-subgrid\');'; 
            
            if (count($data) == 1) {
                
                $subGrid .= 'renderDataViewSubGrid($ddv, '.$dataViewId.', [{id: '.$data[0]['TRG_META_DATA_ID'].', name: \''.Lang::line($data[0]['META_DATA_NAME']).'\', params: \''.$data[0]['PARAMS'].'\'}], row, index, \''.$subgridExcel.'\');';
                
            } else {
                
                $getAllFields = self::getDataViewGridBodyDataModel($dataViewId);

                $haveCriteria = false;
                $subGridParams = '[';
                
                foreach ($data as $k => $row) {
                    if (!is_null($row['CRITERIA']) && $row['CRITERIA'] != '') {
                        $haveCriteria = true;
                        break;
                    } else {
                        $subGridParams .= '{id: '.$row['TRG_META_DATA_ID'].', name: \''.Lang::line($row['META_DATA_NAME']).'\', params: \''.$row['PARAMS'] . '\'}, ';
                    }
                }
                $subGridParams .= ']';

                if ($haveCriteria) {
                    
                    foreach ($data as $k => $row) {
                        
                        $rules = Str::lower($row['CRITERIA']);
                        
                        foreach ($getAllFields as $field) {
                            $rules = preg_replace('/\b'.$field['FIELD_NAME'].'\b/u', 'row.'.$field['FIELD_NAME'], $rules);
                        }

                        $rules = Mdmetadata::defaultKeywordReplacer($rules);

                        if ($k == 0) {
                            $subGrid .= "if ($rules) {";
                            $subGrid .= 'renderDataViewSubGrid($ddv, '.$dataViewId.', [{id: '.$row['TRG_META_DATA_ID'].', name: \''.Lang::line($row['META_DATA_NAME']).'\', params: \''.$row['PARAMS'].'\'}], row, index, \''.$subgridExcel.'\');';
                            $subGrid .= '} ';
                        } else {
                            $subGrid .= "else if ($rules) {";
                            $subGrid .= 'renderDataViewSubGrid($ddv, '.$dataViewId.', [{id: '.$row['TRG_META_DATA_ID'].', name: \''.Lang::line($row['META_DATA_NAME']).'\', params: \''.$row['PARAMS'].'\'}], row, index, \''.$subgridExcel.'\');';
                            $subGrid .= '} ';
                        }
                    }

                    $subGrid .= 'else { $ddv.html(\'Тохирох үр дүн олдсонгүй\'); } ';
                } else {
                    $subGrid .= 'renderDataViewSubGrid($ddv, \''.$dataViewId.'\', '.$subGridParams.', row, index, \''.$subgridExcel.'\');';
                } 
            }

            $subGrid .= 'subgridSetHeight(this, index); },';

            $subGrid .= 'onCollapseRow: function(index, row){';
            $subGrid .= 'subgridSetHeight(this, index); },';
        }

        return $subGrid;
    }

    public function isDataViewWorkFlow($dataViewId) {
        $param = array(
            'systemMetaGroupId' => $dataViewId
        );

        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'get_list_wfm_status', $param);

        if ($result['status'] == 'success' && isset($result['result'])) {
            $value = $this->ws->getValue($result['result']);

            if ($value == 'true') {
                return 1;
            }
        }
        return 0;
    }

    public function getDataViewLayoutTypesModel($dataViewId) {
        
        $idPh = $this->db->Param(0);
        
        $data = $this->db->GetAll("
            SELECT 
                ID, 
                LAYOUT_TYPE, 
                DEFAULT_IMAGE, 
                IMAGE_ROOT_PATH, 
                CLICK_ROW_FUNCTION, 
                LAYOUT_THEME 
            FROM META_GROUP_GRID_LAYOUT 
            WHERE MAIN_META_DATA_ID = $idPh 
                AND LAYOUT_TYPE IS NOT NULL", array($dataViewId));

        $result = null;

        if ($data) {
            
            foreach ($data as $row) {

                $dataSub = $this->db->GetAll("
                    SELECT 
                        POSITION_NAME, 
                        FIELD_PATH, 
                        LABEL_NAME, 
                        PROCESS_META_DATA_ID 
                    FROM META_GROUP_GRID_LAYOUT_DTL  
                    WHERE HEADER_ID = $idPh  
                        AND POSITION_NAME IS NOT NULL 
                        AND FIELD_PATH IS NOT NULL", array($row['ID'])); 

                if ($dataSub) {

                    $result[$row['LAYOUT_TYPE']] = array(
                        'LAYOUT_TYPE'        => $row['LAYOUT_TYPE'], 
                        'DEFAULT_IMAGE'      => $row['DEFAULT_IMAGE'], 
                        'IMAGE_ROOT_PATH'    => $row['IMAGE_ROOT_PATH'], 
                        'CLICK_ROW_FUNCTION' => $row['CLICK_ROW_FUNCTION'], 
                        'LAYOUT_THEME'       => $row['LAYOUT_THEME'] 
                    );

                    foreach ($dataSub as $rowSub) {
                        
                        $result[$row['LAYOUT_TYPE']]['fields'][$rowSub['POSITION_NAME']] = $rowSub['FIELD_PATH'];
                        $result[$row['LAYOUT_TYPE']]['fields']['__linkid'][$rowSub['POSITION_NAME']] = $rowSub['PROCESS_META_DATA_ID'];
                        
                        if ($rowSub['LABEL_NAME']) {
                            $result[$row['LAYOUT_TYPE']]['fields'][$rowSub['POSITION_NAME'].'_labelname'] = $rowSub['LABEL_NAME'];
                        }
                    }
                }
            }
        }

        return $result;
    }

    public function getWfmStatusAssignmentModel($wfmStatusId, $refStructureId, $rowId) {

        $data = array();

        if ($refStructureId != '' && $rowId != '' && $wfmStatusId != '') {
            
            $refStructureIdPh = $this->db->Param(0);
            $rowIdPh = $this->db->Param(1);
            $wfmStatusIdPh = $this->db->Param(2);
            
            $data = $this->db->GetAll("
                WITH TEMP_LIST AS (
                SELECT 
                    UU.USER_ID,
                    HE.EMPLOYEE_CODE||'-'||SUBSTR(BP.LAST_NAME, 1, 1)
                    ||'.'
                    ||BP.FIRST_NAME AS EMPLOYEE_NAME,
                    HP.POSITION_NAME,
                    OD.DEPARTMENT_NAME,
                    HEK.WORK_START_DATE,
                    HEK.WORK_END_DATE,
                    ".$this->db->IfNull("BP.PICTURE", "HE.PICTURE")." AS PICTURE 
                FROM UM_USER UU
                    INNER JOIN UM_SYSTEM_USER USU  ON UU.SYSTEM_USER_ID=USU.USER_ID
                    INNER JOIN BASE_PERSON BP  ON BP.PERSON_ID=USU.PERSON_ID
                    LEFT JOIN HRM_EMPLOYEE HE  ON BP.PERSON_ID=HE.PERSON_ID
                    LEFT JOIN HRM_EMPLOYEE_KEY HEK  ON HE.EMPLOYEE_ID = HEK.EMPLOYEE_ID
                    LEFT JOIN HRM_POSITION_KEY HPK  ON HPK.POSITION_KEY_ID=HEK.POSITION_KEY_ID
                    LEFT JOIN HRM_POSITION HP  ON HP.POSITION_ID=HPK.POSITION_ID
                    LEFT JOIN ORG_DEPARTMENT OD  ON OD.DEPARTMENT_ID=HEK.DEPARTMENT_ID
                )
                SELECT 
                    MWA.USER_ID,
                    TEMP1.EMPLOYEE_NAME,
                    MWS.WFM_STATUS_NAME,
                    MWS.WFM_STATUS_COLOR,
                    MWS.IS_NEED_SIGN,
                    TEMP1.PICTURE,
                    TO_CHAR(MWA.DUE_DATE, 'YYYY/MM/DD')       AS DUE_DATE,
                    TO_CHAR(MWA.DUE_DATE, 'HH24:MI:SS')       AS DUE_TIME,
                    TO_CHAR(MWA.DUE_DATE, 'Day')              AS DUE_DAY,
                    TO_CHAR(MWA.ASSIGNED_DATE, 'YYYY/MM/DD')  AS ASSIGNED_DATE,
                    TO_CHAR(MWA.ASSIGNED_DATE, ' HH24:MI:SS') AS ASSIGNED_TIME,
                    TO_CHAR(MWA.ASSIGNED_DATE, 'Day')         AS ASSIGNED_DAY,
                    TEMP1.POSITION_NAME,
                    TEMP1.DEPARTMENT_NAME,
                    WSS.WFM_STATUS_NAME  AS WFM_STATUS_NAME_S,
                    WSS.WFM_STATUS_COLOR AS WFM_STATUS_COLOR_S,
                    MWA.ASSIGNED_USER_ID,
                    TEMP2.EMPLOYEE_NAME   AS ASSIGN_EMPLOYEE_NAME,
                    TEMP2.POSITION_NAME   AS ASSIGN_POSITION_NAME,
                    TEMP2.DEPARTMENT_NAME AS ASSIGN_DEPARTMENT_NAME
                FROM META_WFM_ASSIGNMENT MWA 
                    INNER JOIN TEMP_LIST TEMP1 ON MWA.USER_ID = TEMP1.USER_ID AND CASE WHEN TEMP1.WORK_START_DATE IS NOT NULL AND TEMP1.WORK_START_DATE IS NOT NULL AND MWA.ASSIGNED_DATE BETWEEN TEMP1.WORK_START_DATE AND TEMP1.WORK_END_DATE THEN 1  WHEN TEMP1.WORK_START_DATE IS NOT NULL AND TEMP1.WORK_START_DATE IS NULL AND  MWA.ASSIGNED_DATE BETWEEN TEMP1.WORK_START_DATE AND MWA.ASSIGNED_DATE THEN 1  WHEN  TEMP1.WORK_START_DATE IS  NULL AND TEMP1.WORK_START_DATE IS  NULL THEN 1 ELSE 0 END = 1
                    INNER JOIN TEMP_LIST TEMP2 ON MWA.ASSIGNED_USER_ID = TEMP2.USER_ID AND CASE WHEN TEMP2.WORK_START_DATE IS NOT NULL AND TEMP2.WORK_START_DATE IS NOT NULL AND MWA.ASSIGNED_DATE BETWEEN TEMP2.WORK_START_DATE AND TEMP2.WORK_END_DATE THEN 1  WHEN TEMP2.WORK_START_DATE IS NOT NULL AND TEMP2.WORK_START_DATE IS NULL AND  MWA.ASSIGNED_DATE BETWEEN TEMP1.WORK_START_DATE AND MWA.ASSIGNED_DATE THEN 1  WHEN  TEMP2.WORK_START_DATE IS  NULL AND TEMP2.WORK_START_DATE IS  NULL THEN 1 ELSE 0 END = 1
                    LEFT JOIN META_WFM_STATUS MWS ON MWS.ID = MWA.USER_STATUS_ID 
                    INNER JOIN META_WFM_STATUS WSS ON WSS.ID = MWA.WFM_STATUS_ID 
                WHERE MWA.REF_STRUCTURE_ID = $refStructureIdPh 
                    AND MWA.RECORD_ID = $rowIdPh 
                    AND MWA.WFM_STATUS_ID = $wfmStatusIdPh 
            ORDER BY MWA.ASSIGNED_DATE DESC", array($refStructureId, $rowId, $wfmStatusId)); 

            if (empty($data)) {
                $data = $this->db->GetAll("
                    SELECT 
                        WF.USER_ID,
                        UM.SYSTEM_USER_ID,
                        BP.LAST_NAME,
                        BP.FIRST_NAME,
                        WSS.WFM_STATUS_NAME AS WFM_STATUS_NAME,
                        WSS.WFM_STATUS_COLOR AS WFM_STATUS_COLOR,
                        '' AS DUE_DATE,
                        WF.IS_NEED_SIGN,
                        WSS.WFM_STATUS_NAME  AS WFM_STATUS_NAME_S,
                        WSS.WFM_STATUS_COLOR AS WFM_STATUS_COLOR_S,
                        '' AS CREATED_DATE
                    FROM META_WFM_STATUS_ASSIGNMENT WF 
                        INNER JOIN UM_USER UM ON UM.USER_ID = WF.USER_ID 
                        INNER JOIN UM_SYSTEM_USER US ON US.USER_ID = UM.SYSTEM_USER_ID 
                        INNER JOIN BASE_PERSON BP ON BP.PERSON_ID = US.PERSON_ID 
                        LEFT JOIN HRM_EMPLOYEE EMP ON EMP.PERSON_ID = BP.PERSON_ID 
                        LEFT JOIN META_WFM_STATUS WSS ON WSS.ID = WF.WFM_STATUS_ID 
                    WHERE WF.WFM_STATUS_ID = $refStructureIdPh 
                    GROUP BY WF.USER_ID, 
                        UM.SYSTEM_USER_ID, 
                        BP.LAST_NAME, 
                        BP.FIRST_NAME, 
                        WF.IS_NEED_SIGN, 
                        WSS.WFM_STATUS_NAME, 
                        WSS.WFM_STATUS_COLOR 
                    ORDER BY BP.FIRST_NAME", array($wfmStatusId));        
            }
        }

        return $data;
    }

    public function getWfmLogLastCreatedModel($refStructureId, $rowId, $wfmStatusId) {

        if ($refStructureId) {

            $userRow = $this->db->GetRow("
                SELECT 
                    CREATED_USER_ID, 
                    CREATED_DATE 
                FROM META_WFM_LOG 
                WHERE REF_STRUCTURE_ID = $refStructureId 
                    AND RECORD_ID = $rowId 
                ORDER BY CREATED_DATE DESC"); 

            $lastDueDate = array();

            if ($wfmStatusId) {

                $lastDueDate = $this->db->GetRow("
                    SELECT 
                        MAX(WF.DUE_DATE) AS DUE_DATE  
                    FROM META_WFM_ASSIGNMENT WF 
                    WHERE WF.REF_STRUCTURE_ID = $refStructureId 
                        AND WF.RECORD_ID = $rowId 
                        AND WF.WFM_STATUS_ID = $wfmStatusId"); 
            }

            if ($userRow) {

                $row = $this->db->GetRow("
                    SELECT 
                        UM.USER_ID, 
                        BP.LAST_NAME, 
                        BP.FIRST_NAME, 
                        EMP.PICTURE
                    FROM UM_USER UM 
                        INNER JOIN UM_SYSTEM_USER US ON US.USER_ID = UM.SYSTEM_USER_ID 
                        INNER JOIN BASE_PERSON BP ON BP.PERSON_ID = US.PERSON_ID 
                        LEFT JOIN HRM_EMPLOYEE EMP ON EMP.PERSON_ID = BP.PERSON_ID 
                    WHERE UM.USER_ID = ".$userRow['CREATED_USER_ID']); 

                if ($row) {

                    if ($lastDueDate) {
                        return array_merge($userRow, array_merge($row, $lastDueDate));
                    } else {
                        return array_merge($userRow, $row);
                    }
                }

            } else {

                $metaValueIdColumnName = $this->db->GetOne("SELECT COLUMN_NAME FROM META_GROUP_CONFIG WHERE MAIN_META_DATA_ID = $refStructureId AND PARENT_ID IS NULL AND INPUT_NAME = 'META_VALUE_ID'");

                if ($metaValueIdColumnName) {

                    $tableName = $this->db->GetOne("SELECT TABLE_NAME FROM META_GROUP_LINK WHERE META_DATA_ID = $refStructureId");
                    $tableName = Mdmetadata::objectNameDeCompress($tableName);
                    $tableName = (strlen($tableName) > 30 ? '('.$tableName.')' : $tableName);

                    $row = $this->db->GetRow("
                        SELECT 
                            UM.USER_ID, 
                            BP.LAST_NAME, 
                            BP.FIRST_NAME, 
                            EMP.PICTURE, 
                            RT.CREATED_DATE 
                        FROM $tableName RT 
                            INNER JOIN UM_USER UM ON UM.USER_ID = RT.CREATED_USER_ID 
                            INNER JOIN UM_SYSTEM_USER US ON US.USER_ID = UM.SYSTEM_USER_ID 
                            INNER JOIN BASE_PERSON BP ON BP.PERSON_ID = US.PERSON_ID 
                            LEFT JOIN HRM_EMPLOYEE EMP ON EMP.PERSON_ID = BP.PERSON_ID 
                        WHERE RT.$metaValueIdColumnName = $rowId 
                        GROUP BY UM.USER_ID, 
                            BP.LAST_NAME, 
                            BP.FIRST_NAME, 
                            EMP.PICTURE, 
                            RT.CREATED_DATE"); 

                    if ($row) {
                        return $row;
                    }
                }
            }
        }

        return false;
    }

    public function getRefStructureListModel($refStructureId, $folderId) {
        
        $dvQueries = self::getDVMainQueriesModel($refStructureId);
        
        if (strpos(DB_DRIVER, 'postgre') !== false && $dvQueries['POSTGRE_SQL']) {
            $tableName = $dvQueries['POSTGRE_SQL'];
        } elseif (strpos(DB_DRIVER, 'mssql') !== false && $dvQueries['MS_SQL']) {
            $tableName = $dvQueries['MS_SQL'];
        } else {
            $tableName = $dvQueries['TABLE_NAME'];
        }
        
        $this->load->model('mddatamodel', 'middleware/models/');
        $getCodeNameFieldName = $this->model->getCodeNameFieldNameModel($refStructureId);
        
        $metaValueIdColumnName = $getCodeNameFieldName['idColumnName'];
        $metaValueCodeColumnName = $getCodeNameFieldName['codeColumnName'];
        $metaValueNameColumnName = $getCodeNameFieldName['nameColumnName'];
        $parentIdColumnName = $getCodeNameFieldName['parentColumnName'];
        $valueCriteria = $this->db->GetAll("SELECT COLUMN_NAME, VALUE_CRITERIA FROM META_GROUP_CONFIG WHERE MAIN_META_DATA_ID = $refStructureId AND PARENT_ID IS NULL AND TRIM(VALUE_CRITERIA) IS NOT NULL AND COLUMN_NAME IS NOT NULL AND LENGTH(VALUE_CRITERIA) > 0");
        
        $this->load->model('mdobject', 'middleware/models/');
        
        if (!$metaValueIdColumnName || !$metaValueNameColumnName) {
            return null;
        }

        $tableName = Mdmetadata::objectNameDeCompress($tableName);
        $tableName = (strlen($tableName) > 30 ? '('.$tableName.')' : $tableName);
        $tableName = str_ireplace(array(':sessionuserkeyid', '[sessionuserkeyid]', '[sessionuserid]', ':sessionuserid'), Ue::sessionUserKeyId(), $tableName);
        $tableName = str_ireplace(array(':languagecode', '[languagecode]'), "'".Info::getLanguageShortName()."'", $tableName);
        $tableName = str_ireplace(array(':filterassetgroupid', ':filterstorekeeperkeyid', ':filterassetkeeperkeyid', ':filteritemcategoryid', ':filterdepartmentid', ':filterassetlocationid'), 'NULL', $tableName);

        if (!$parentIdColumnName) {
            $parentIdColumnName = "''";
            $countSelect = '0';
        } else {
            $countSelect = "(SELECT COUNT($metaValueIdColumnName) 
                     FROM $tableName TT
                     WHERE TT.$parentIdColumnName = AA.$metaValueIdColumnName) ";
        }

        $where = '1 = 1';

        if (empty($folderId)) {
            if ($parentIdColumnName != "''") {
                $where .= " AND " . $parentIdColumnName . " IS NULL ";
            }
            if (!empty($valueCriteria)) {
                foreach ($valueCriteria as $row) {
                    if (strpos($row['VALUE_CRITERIA'], '[') === false) {
                        $replacedValueCriteria = $row['VALUE_CRITERIA'];
                    } else {
                        $replacedValueCriteria = Mdmetadata::defaultKeywordReplacer($row['VALUE_CRITERIA']);
                        $replacedValueCriteria = str_replace(array('[', ']'), '', $replacedValueCriteria);
                    }
                    $where .= " AND " . $row['COLUMN_NAME'] . " " . $replacedValueCriteria;
                }                
            }
        } else {
            $where .= " AND LOWER(" . $parentIdColumnName . ") = '" . Str::lower($folderId) . "'";
        }
        
        if (strpos(DB_DRIVER, 'postgre') !== false) {
            $orderBy = "ORDER BY SUBSTRING(META_VALUE_CODE, '^[0-9]+')::int ASC, SUBSTRING(META_VALUE_CODE, '[^0-9]*$') ASC, META_VALUE_CODE ASC";
        } else {
            $orderBy = "ORDER BY TO_NUMBER(REGEXP_SUBSTR(META_VALUE_CODE,'^[0-9]+')) ASC, TO_NUMBER(REGEXP_SUBSTR(META_VALUE_CODE,'$[0-9]+')) ASC, META_VALUE_CODE ASC";
        }

        $treeData = $this->db->GetAll(
            "SELECT * 
                FROM (
                    SELECT 
                        $metaValueIdColumnName AS META_VALUE_ID, 
                        ".(($metaValueCodeColumnName) ? $metaValueCodeColumnName : $metaValueNameColumnName)." AS META_VALUE_CODE, 
                        $metaValueNameColumnName AS META_VALUE_NAME, 
                        $parentIdColumnName AS PARENT_ID,  
                        $countSelect AS CHILD_COUNT 
                    FROM $tableName AA 
                    WHERE $where 
                ) TEMP 
            $orderBy");

        return $treeData;
    }

    public function getDataViewRecordListModel($dataViewId, $filtedField, $filtedFieldValue) {

        $_POST['metaDataId'] = $dataViewId;
        $_POST['treeGrid']   = 1;
        $_POST['rows']       = 10000;

        if (!empty($filtedField)) {
            $_POST['ignoreRecursive'][$filtedField] = 1;
            $_POST['cardFilterData'] = $filtedField . '=' . $filtedFieldValue;
        }
        
        $result = self::dataViewDataGridModel(true);

        if (isset($result['status']) && $result['status'] == 'success' && isset($result['rows'])) {
            
            return $result['rows'];
            
        } elseif (isset($result[0])) {
            
            return $result;
        } else {
            return array('status' => 'error', 'message' => $result['message']);
        }
    }

    public function getParentFolderByExplorerModel($refStructureId, $folderId) {
        
        $this->load->model('mddatamodel', 'middleware/models/');
        $getCodeNameFieldName = $this->model->getCodeNameFieldNameModel($refStructureId);
                
        $tableName = $this->db->GetOne("SELECT TABLE_NAME FROM META_GROUP_LINK WHERE META_DATA_ID = $refStructureId");
        $metaValueIdColumnName = $getCodeNameFieldName['idColumnName'];
        $parentIdColumnName = $getCodeNameFieldName['parentColumnName'];

        if (!$metaValueIdColumnName || !$parentIdColumnName) {
            return null;
        }

        $tableName = Mdmetadata::objectNameDeCompress($tableName);
        $tableName = (strlen($tableName) > 30 ? '('.$tableName.')' : $tableName);
        $tableName = str_ireplace(array(':sessionuserkeyid', '[sessionuserkeyid]', '[sessionuserid]', ':sessionuserid'), Ue::sessionUserKeyId(), $tableName);
        $tableName = str_ireplace(array(':filterassetgroupid', ':filterstorekeeperkeyid', ':filterassetkeeperkeyid', ':filteritemcategoryid', ':filterdepartmentid', ':filterassetlocationid'), 'NULL', $tableName);

        $row = $this->db->GetRow(
            "SELECT 
                AA.$parentIdColumnName AS PARENT_ID 
            FROM $tableName AA 
            WHERE AA.$metaValueIdColumnName = $folderId");

        return $row;
    }

    public function buildOrgChartDataSource($fields, $recordList, $idField, $parentField, $name1, $name2, $depth = 0, $parent = 0) {

        $dataSource = '';

        foreach ($recordList as $k => $val) {

            if (!array_find_val($recordList, $idField, $val[$parentField])) {
                $val[$parentField] = 0;
            }

            if ($val[$parentField] == $parent) {  

                $rowJson = htmlentities(json_encode($val), ENT_QUOTES, 'UTF-8'); 

                if ($depth == 0) {

                    if ($parent == 0) {
                        $dataSource .= "{'name': '".$val[$name1]."',"; 
                        $dataSource .= "'title': '".$val[$name2]."',"; 
                        $dataSource .= '\'rowdata\': "'.$rowJson.'",'; 
                        $dataSource .= "'children': [".self::buildOrgChartDataSource($fields, $recordList, $idField, $parentField, $name1, $name2, $depth + 1, $val[$idField])."]};"; 
                    }

                } else {

                    $dataSource .= "{'name': '".$val[$name1]."', 'title': '".$val[$name2]."', 'rowdata': '".$rowJson."'"; 

                    if ($childDataSource = self::buildOrgChartDataSource($fields, $recordList, $idField, $parentField, $name1, $name2, $depth + 1, $val[$idField])) {
                        $dataSource .= ", 'children': [".$childDataSource."]"; 
                    }

                    $dataSource .= "},";
                }
            }
        }

        return $dataSource;
    }

    public function buildMindChartDataSource($fields, $recordList, $idField, $parentField, $name1, $name2, $depth = 0, $parent = 0) {

        $dataSource = '';

        foreach ($recordList as $k => $val) {

            if (!array_find_val($recordList, $idField, $val[$parentField])) {
                $val[$parentField] = 0;
            }

            if ($val[$parentField] == $parent) {  

                $rowJson = htmlentities(json_encode($val), ENT_QUOTES, 'UTF-8'); 

                if ($depth == 0) {

                    if ($parent == 0) {
                        
                        $dataSource .= '{"topic": "'.htmlentities($val[$name1], ENT_QUOTES, 'UTF-8').'",'; 
                        $dataSource .= '"id": "'.$val[$idField].'",'; 
                        
                        if (isset($val['color'])) {
                            $dataSource .= '"background-color": "'.$val['color'].'",'; 
                        }
                        
                        $dataSource .= '"rowdata": "'.$rowJson.'",'; 
                        $dataSource .= "'children': [".self::buildMindChartDataSource($fields, $recordList, $idField, $parentField, $name1, $name2, $depth + 1, $val[$idField])."]},"; 
                    }

                } else {

                    $expand = true;
                    
                    if ($depth >= 2) {
                        $expand = false;
                    }

                    $dataSource .= '{"topic": "'.htmlentities($val[$name1], ENT_QUOTES, 'UTF-8').'", '; 
                    
                    if (isset($val['color'])) {
                        $dataSource .= '"background-color": "'.$val['color'].'", '; 
                    }
                        
                    $dataSource .= '"id": "'.$val[$idField].'", "rowdata": "'.$rowJson.'", "expanded": "'.$expand.'"'; 

                    if ($childDataSource = self::buildMindChartDataSource($fields, $recordList, $idField, $parentField, $name1, $name2, $depth + 1, $val[$idField])) {
                        $dataSource .= ', "children": ['.$childDataSource.']'; 
                    }

                    $dataSource .= '},';
                }
            }
        }

        return $dataSource;
    }

    public function getInlineEditMapConfig($metaDataId) {
        $rows = $this->db->GetAll("
            SELECT 
                MAIN_META_DATA_ID,
                PROCESS_META_DATA_ID,
                LOWER(SRC_PARAM_PATH) AS SRC_PARAM_PATH,
                LOWER(TRG_PARAM_PATH) AS TRG_PARAM_PATH
            FROM META_DM_ROW_PROCESS_PARAM
            WHERE MAIN_META_DATA_ID = $metaDataId"
        );

        return $rows;
    }

    public function buildGanttChartDataSource($configRow, $recordList) {

        $dataSource = array();

        if (isset($recordList[0])) {

            $id = strtolower($configRow['dataViewLayoutTypes']['explorer']['fields']['id']);
            $text = strtolower($configRow['dataViewLayoutTypes']['explorer']['fields']['text']);
            $name1 = strtolower($configRow['dataViewLayoutTypes']['explorer']['fields']['name1']);
            $name3 = strtolower($configRow['dataViewLayoutTypes']['explorer']['fields']['name3']);
            $name5 = strtolower($configRow['dataViewLayoutTypes']['explorer']['fields']['name5']);
            $name6 = strtolower(issetParam($configRow['dataViewLayoutTypes']['explorer']['fields']['name6']));
            $name7 = strtolower(issetParam($configRow['dataViewLayoutTypes']['explorer']['fields']['name7']));
            $start_date = strtolower($configRow['dataViewLayoutTypes']['explorer']['fields']['start_date']);
            $end_date = strtolower(issetParam($configRow['dataViewLayoutTypes']['explorer']['fields']['end_date']));
            $duration = strtolower(issetParam($configRow['dataViewLayoutTypes']['explorer']['fields']['duration']));
            $progress = strtolower(issetParam($configRow['dataViewLayoutTypes']['explorer']['fields']['progress']));
            $parent = strtolower(issetParam($configRow['dataViewLayoutTypes']['explorer']['fields']['parent']));
            $color = strtolower(issetParam($configRow['dataViewLayoutTypes']['explorer']['fields']['color']));
            $renderSplit = strtolower(issetParam($configRow['dataViewLayoutTypes']['explorer']['fields']['render_split']));
            $bar_height = strtolower(issetParam($configRow['dataViewLayoutTypes']['explorer']['fields']['bar_height']));

            $k = 0;

            foreach ($recordList as $row) {

                if (!is_null($row[$name1])) {

                    $dataSource[$k]['id'] = $row[$id];
                    $dataSource[$k]['text'] = $row[$text] ? $row[$text] : 'Тодорхойгүй';
                    $dataSource[$k]['taskname'] = $row[$name1];
                    $dataSource[$k]['start_date'] = Date::formatter($row[$start_date], 'd-m-Y');
                    $dataSource[$k]['color'] = ($color && isset($row[$color])) ? $row[$color] : (isset($row['wfmstatuscolor']) ? $row['wfmstatuscolor'] : '#448aff'); 
                    //$dataSource[$k]['textColor'] = isset($row['wfmstatuscolor']) ? $row['wfmstatuscolor'] : '#000'; 
                    $dataSource[$k]['open'] = (isset($row['_rowstate']) && $row['_rowstate'] == 'closed') ? false : true; 
                    
                    if ($name3) {
                        $dataSource[$k][$name3] = $row[$name3]; 
                    }
                    
                    if ($end_date) {
                        $dataSource[$k]['end_date'] = Date::formatter($row[$end_date], 'd-m-Y') . ' 23:59:59';
                    } else {
                        $dataSource[$k]['duration'] = $row[$duration] ? $row[$duration] : 1; 
                    }
                    
                    if ($progress) {
                        $dataSource[$k]['progress'] = $row[$progress]; 
                    }
                    
                    if ($parent) {
                        $dataSource[$k]['parent'] = $row[$parent]; 
                    }
                    
                    if ($bar_height && issetParam($row[$bar_height]) != '') {
                        $dataSource[$k]['bar_height'] = $row[$bar_height]; 
                    }
                    
                    if ($name5) {
                        $dataSource[$k][$name5] = $row[$name5]; 
                    }
                    
                    if ($name6) {
                        $dataSource[$k][$name6] = $row[$name6]; 
                    }
                    
                    if ($name7) {
                        $dataSource[$k][$name7] = $row[$name7]; 
                    }
                    
                    if ($renderSplit && $row[$renderSplit]) {
                        $dataSource[$k]['render'] = $row[$renderSplit]; 
                    }
                    
                    if (isset($row['childrecordcount']) && $row['childrecordcount']) {
                        $dataSource[$k]['$has_child'] = $row['childrecordcount']; 
                        
                        if (isset($row['children'])) {
                            unset($row['children']);
                        }
                        
                    } elseif (isset($row['children']) && is_array($row['children']) && $childCount = count($row['children'])) {
                        $dataSource[$k]['$has_child'] = $childCount; 
                        unset($row['children']);
                    }
                    
                    $dataSource[$k]['row'] = $row; 
                    
                    $k++;
                }
            }
        }

        return $dataSource;
    }

    public function getWfmStatusLogDataModel($wfmStatusId, $refStructureId, $rowId) {
        return $this->db->GetAll("SELECT tem.*
                                    FROM (
                                      SELECT DISTINCT ws.WFM_STATUS_NAME,
                                        ws.WFM_STATUS_COLOR,
                                        ws.ID AS WFM_STATUS_ID,
                                        ws.WFM_STATUS_CODE,
                                        wl.WFM_DESCRIPTION,
                                        wl.CREATED_DATE,
                                        ve.DEPARTMENT_NAME,
                                        ve.POSITION_NAME,
                                        ve.EMPLOYEE_PICTURE,
                                        SUBSTR(ve.LAST_NAME, 1, 1)
                                        ||'.'
                                        ||ve.FIRST_NAME AS EMPLOYEE_NAME,
                                        tem.EMPLOYEE_NAME AS SIGNED_EMPLOYEE_NAME,
                                        tem.DEPARTMENT_NAME AS SIGNED_DEPARTMENT_NAME,
                                        tem.POSITION_NAME AS SIGNED_POSITION_NAME,
                                        tem.STATUS_NAME AS SIGNED_STATUS_NAME,
                                        tem.EMPLOYEE_PICTURE AS SIGNED_EMPLOYEE_PICTURE,
                                        TO_CHAR(WA.DUE_DATE, 'YYYY/MM/DD') AS DUE_DATE ,
                                        TO_CHAR(WA.DUE_DATE, 'HH24:MI:SS') AS DUE_TIME,
                                        TO_CHAR(WA.DUE_DATE, 'Day') AS DUE_DAY,
                                        WA.ASSIGNED_DATE,
                                        TO_CHAR(WA.ASSIGNED_DATE, ' HH24:MI:SS') AS ASSIGNED_TIME,
                                        TO_CHAR(WA.ASSIGNED_DATE, 'Day') AS ASSIGNED_DAY,
                                        TO_CHAR(wl.CREATED_DATE, 'YYYY/MM/DD') AS CASSIGNED_DATE ,
                                        TO_CHAR(wl.CREATED_DATE, ' HH24:MI:SS') AS CASSIGNED_TIME,
                                        TO_CHAR(wl.CREATED_DATE, 'Day') AS CASSIGNED_DAY
                                      FROM META_WFM_LOG wl
                                      INNER JOIN META_WFM_ASSIGNMENT WA ON wl.RECORD_ID = WA.RECORD_ID AND wl.WFM_STATUS_ID = WA.WFM_STATUS_ID AND WA.ASSIGNED_USER_ID = wl.CREATED_USER_ID AND wl.REF_STRUCTURE_ID = WA.REF_STRUCTURE_ID
                                      INNER JOIN META_WFM_STATUS ws ON wl.WFM_STATUS_ID = ws.ID
                                      INNER JOIN um_user uu ON wl.CREATED_USER_ID = uu.USER_ID
                                      INNER JOIN um_system_user su ON uu.SYSTEM_USER_ID = su.USER_ID
                                      INNER JOIN VIEW_EMPLOYEE ve ON su.PERSON_ID = ve.PERSON_ID
                                      INNER JOIN (SELECT 
                                          SUBSTR(BP.LAST_NAME, 1, 1)||'.'||BP.FIRST_NAME AS EMPLOYEE_NAME,
                                          VE.POSITION_NAME, VE.STATUS_NAME, 
                                          VE.DEPARTMENT_CODE, 
                                          VE.DEPARTMENT_NAME,
                                          UM.USER_ID,
                                          ve.EMPLOYEE_PICTURE
                                        FROM UM_USER UM 
                                        INNER JOIN UM_SYSTEM_USER US ON US.USER_ID = UM.SYSTEM_USER_ID 
                                        INNER JOIN BASE_PERSON BP ON BP.PERSON_ID = US.PERSON_ID 
                                        INNER JOIN HRM_EMPLOYEE EMP ON EMP.PERSON_ID = BP.PERSON_ID 
                                        INNER JOIN (
                                          SELECT 
                                            MAX(POSITION_ID) AS POSITION_ID, EMPLOYEE_ID
                                          FROM VIEW_EMPLOYEE
                                          GROUP BY EMPLOYEE_ID
                                        ) TEM1 ON EMP.EMPLOYEE_ID = TEM1.EMPLOYEE_ID 
                                        INNER JOIN VIEW_EMPLOYEE VE ON EMP.EMPLOYEE_ID = VE.EMPLOYEE_ID AND VE.POSITION_ID = TEM1.POSITION_ID
                                        WHERE VE.IS_ACTIVE = 1 AND EMP.IS_ACTIVE = 1
                                       ) tem ON WA.USER_ID = tem.USER_ID
                                    WHERE wl.RECORD_ID      = $rowId
                                    AND wl.REF_STRUCTURE_ID = $refStructureId
                                    AND wl.WFM_STATUS_ID = $wfmStatusId
                                    ) tem
                                    ORDER BY CREATED_DATE DESC");
    }

    public function getFolderRecord($refStructureId, $value) {
        
        $this->load->model('mddatamodel', 'middleware/models/');
        $getCodeNameFieldName = $this->model->getCodeNameFieldNameModel($refStructureId);
        
        $metaValueIdColumnName = strtolower($getCodeNameFieldName['idColumnName']);

        return self::getDataViewRecordListModel($refStructureId, $metaValueIdColumnName, $value);
    }

    public function getDvExportTemplateHtml($dataViewId) {

        $row = $this->db->GetRow("SELECT HEADER_HTML, FOOTER_HTML FROM CUSTOMER_DV_HDR_FTR WHERE META_DATA_ID = ".$this->db->Param(0), array($dataViewId));

        if ($row) {

            $response = array();

            if (trim(Str::cleanOut($row['HEADER_HTML'])) != '') {
                $response['header'] = $row['HEADER_HTML'];
            }
            if (trim(Str::cleanOut($row['FOOTER_HTML'])) != '') {
                $response['footer'] = $row['FOOTER_HTML'];
            }

            if (count($response) > 0) {
                return $response;
            }
        }

        return false;
    }

    public function getOnlyHeaderFieldsByKey($dataViewId) {

        $data = $this->db->GetAll("
            SELECT 
                LABEL_NAME, 
                LOWER(FIELD_PATH) AS FIELD_PATH, 
                LOOKUP_META_DATA_ID, 
                LOOKUP_TYPE 
            FROM META_GROUP_CONFIG  
            WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                AND PARENT_ID IS NULL", 
            [$dataViewId]
        );

        $array = [];

        foreach ($data as $row) {
            $array[$row['FIELD_PATH']] = $row;
        }

        return $array;
    }

    public function getCriteriaValue($metaDataId, $criteriaKey, $criteria, $pathRow) {

        $value= '';

        foreach ($criteria as $row) {
            $value .= str_replace('%', '', $row['operand']).', ';
        }

        return rtrim($value, ', ');
    }

    public function checkIsSemanticsProcess($metaDataId, $rowId, $wfmStatusId) {
        
        if (!is_null($metaDataId) && !is_null($rowId) && !is_null($wfmStatusId)) {
            
            $param = array(
                'systemMetaGroupId' => $metaDataId,
                'id' => $rowId,
                'wfmStatusId' => $wfmStatusId,
            );

            $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'get_semantics_process', $param);

            if (isset($result['status']) && $result['status'] == 'success' && !is_null($result['result'])) {
                return $result['result'];
            }
        }
        
        return false;
    }
    
    public function getWfmRuleDataModel() {
        return $this->db->GetAll("SELECT * FROM META_WFM_RULE");
    }
    
    public function getUseAssignModel($wfmStatusId) {
        $result = $this->db->GetRow("SELECT IS_USERDEF_ASSIGN FROM META_WFM_STATUS WHERE ID = ".$this->db->Param(0), array($wfmStatusId));
        
        if (isset($result['IS_USERDEF_ASSIGN']) && $result['IS_USERDEF_ASSIGN'] == '1') {
            return '1';
        } else {
            return '0';
        }
    }
    
    public function getUseAssignRuleIdModel($wfmStatusId) {
        $row = $this->db->GetRow("SELECT DEFAULT_RULE_ID FROM META_WFM_STATUS WHERE ID = ".$this->db->Param(0)." AND IS_USERDEF_ASSIGN = 1", array($wfmStatusId));
        
        if ($row) {
            return $row;
        } 
        
        return null;
    }
    
    public function getRowWfmStatusModel($wfmStatusId) {
        $result = $this->db->GetRow("SELECT IS_DESC_REQUIRED, IS_HIDE_NEXT_USER, IS_HIDE_FILE, IS_FILE_PREVIEW, DEFAULT_RULE_ID FROM META_WFM_STATUS WHERE ID = ".$this->db->Param(0), array($wfmStatusId));
        
        if ($result) {
            return $result;
        } else {
            return array('IS_DESC_REQUIRED' => '', 'IS_HIDE_NEXT_USER' => '', 'IS_HIDE_FILE' => '');
        }
    }
    
    public function getStructureIndicatorIdModel($indicatorId) {
        $id = $this->db->GetOne("SELECT STRUCTURE_INDICATOR_ID FROM KPI_INDICATOR WHERE ID = ".$this->db->Param(0), array($indicatorId));
        return $id ? $id : $indicatorId;
    }
    
    public function writeDvTextFileModel($fileName, $exportText) {
        
        $textSavePath = UPLOADPATH . 'ttum/';
        
        $files = glob($textSavePath . '*');
        $now   = time();
        $day   = 7;

        foreach ($files as $file) {
            if (is_file($file) && ($now - filemtime($file) >= 60 * 60 * 24 * $day)) {
                @unlink($file);
            } 
        }
        
        $textSavePathFile = $textSavePath . $fileName;
            
        if (file_put_contents($textSavePathFile, $exportText)) {
            
            $data = array(
                'ID'              => getUID(), 
                'FILE_NAME'       => $fileName, 
                'CREATED_DATE'    => Date::currentDate('Y-m-d H:i:s'), 
                'CREATED_USER_ID' => Ue::sessionUserKeyId()
            );

            $this->db->AutoExecute('FIN_FILE_LOG', $data);
        }
        
        return true;
    }
    
    public function getGridLayoutPathModel($metaDataId) {
        return $this->db->GetAll("
            SELECT
                T0.LAYOUT_THEME,
                T1.*
            FROM META_GROUP_GRID_LAYOUT T0
                INNER JOIN META_GROUP_GRID_LAYOUT_DTL T1 ON T0.ID = T1.HEADER_ID 
            WHERE T0.MAIN_META_DATA_ID = ".$this->db->Param(0), array($metaDataId));
    }
    
    public function getGridLayoutModel($metaDataId) {
        $getRow = $this->db->GetRow("SELECT T0.* FROM META_GROUP_GRID_LAYOUT T0 WHERE T0.MAIN_META_DATA_ID = $metaDataId");
        
        if ($getRow) {
            return $getRow;
        }
        
        return array('LAYOUT_TYPE' => '');
    }
    
    public function dataViewInlineEditProcessdModel($metaDataId, $paramActionType = '') {

        $commandContextResult = array(
            'status' => 'error',
            'message' => 'Ажиллуулах процесс олдсонгүй!'
        );
        $commandContext = array();

        $getAccessProcess = self::getAccessProcess($metaDataId, false);
        
        if ($getAccessProcess) {

            $dataViewProcess = self::getDataViewInlineEditProcess($metaDataId, $getAccessProcess);

            if ($dataViewProcess) {
                
                foreach ($dataViewProcess as $row) {
                            
                    if ($row['META_DATA_CODE'] == 'pfChangeWfmStatus') {
                        $commandSort[$row['ORDER_NUM']] = '<!--changewfmstatus-->';
                        continue;
                    }

                    $actionType = $row['ACTION_TYPE'];

                    if ($actionType == '' && !$row['COUNT_GET'] && $row['META_TYPE_ID'] == Mdmetadata::$businessProcessMetaTypeId) {
                        $actionType = 'insert';
                    } elseif ($actionType == 'delete' && $row['COUNT_GET'] && $row['META_TYPE_ID'] == Mdmetadata::$businessProcessMetaTypeId) {
                        $actionType = 'delete';
                    } elseif (
                        ($row['COUNT_GET'] && $actionType == 'insert' && $row['META_TYPE_ID'] == Mdmetadata::$businessProcessMetaTypeId) 
                        || 
                        ($row['COUNT_GET'] && $row['META_TYPE_ID'] == Mdmetadata::$businessProcessMetaTypeId)) {
                        $actionType = 'update';

                        if ($row['COUNT_GET'] > 1) {
                            $commandContextResult = array(
                                'status' => 'error',
                                'message' => 'Засах горимд олон процесс олдлоо. Зөвхөн нэг процесс сонгож тохируулна уу!'
                            );                                
                            return;
                        }
                    } 

                    if ($actionType === $paramActionType) {
                        $commandContext = array(
                            'PROCESS_META_DATA_ID' => $row['PROCESS_META_DATA_ID'], 
                            'PROCESS_NAME' => $row['PROCESS_NAME'],
                            'META_TYPE_ID' => $row['META_TYPE_ID'],
                            'ACTION_TYPE' => $actionType
                        );
                    }
                }
                
                if (!empty($commandContext)) {
                    $commandContextResult = array_merge($commandContext, array(
                        'status' => 'success'
                    ));                    
                }
                
            }
        }

        return $commandContextResult;
    }        

    public function getDataViewInlineEditProcess($metaDataId, $accessProcess, $andWhere = 'AND (PRO.IS_WORKFLOW = 0 OR PRO.IS_WORKFLOW IS NULL) ') {
        
        $join = '';
        
        if (Input::isEmpty('workSpaceId') == false && $workSpaceId = Input::numeric('workSpaceId')) {
                
            $join = "LEFT JOIN META_DM_PROCESS_IGNORE DPI ON DPI.MAIN_META_DATA_ID = PRO.MAIN_META_DATA_ID 
                AND DPI.TRG_META_DATA_ID = $workSpaceId 
                AND DPI.PROCESS_META_DATA_ID = PRO.PROCESS_META_DATA_ID ";

            $andWhere .= ' AND DPI.ID IS NULL ';
        }
        
        if (Input::isEmpty('runSrcMetaId') == false && $runSrcMetaId = Input::post('runSrcMetaId')) {
                
            $join .= "LEFT JOIN META_DM_PROCESS_IGNORE DPIS ON DPIS.MAIN_META_DATA_ID = PRO.MAIN_META_DATA_ID 
                AND DPIS.TRG_META_DATA_ID = $runSrcMetaId 
                AND DPIS.PROCESS_META_DATA_ID = PRO.PROCESS_META_DATA_ID ";

            $andWhere .= ' AND DPIS.ID IS NULL ';
        }
        
        $processIds = Arr::implode_key(',', $accessProcess, 'processid', true);
        
        $data = $this->db->GetAll("
            SELECT 
                DISTINCT
                PRO.ID, 
                PRO.PROCESS_META_DATA_ID, 
                PRO.PROCESS_NAME, 
                PRO.ICON_NAME, 
                PRO.BATCH_NUMBER,
                PRO.BUTTON_STYLE ,
                PRO.IS_SHOW_POPUP, 
                TRIM(PRO.CRITERIA) AS CRITERIA, 
                TRIM(PRO.ADVANCED_CRITERIA) AS ADVANCED_CRITERIA, 
                MD.META_DATA_CODE,
                MD.META_DATA_NAME,
                BPL.PROCESS_NAME AS ICON_PROCESS_NAME, 
                MD.META_TYPE_ID, 
                PRO.PASSWORD_PATH, 
                PRO.POST_PARAM, 
                PRO.ORDER_NUM, 
                (
                    SELECT 
                        COUNT(ID)   
                    FROM META_DM_TRANSFER_PROCESS TP 
                    WHERE TP.MAIN_META_DATA_ID = $metaDataId AND TP.PROCESS_META_DATA_ID = PRO.PROCESS_META_DATA_ID 
                ) AS COUNT_GET, 
                BPL.ACTION_TYPE,
                PRO.IS_BP_OPEN,
                PRO.ICON_COLOR,
                PRO.IS_BP_OPEN_DEFAULT, 
                PRO.SHOW_POSITION, 
                PRO.IS_SHOW_ROWSELECT, 
                PRO.IS_USE_PROCESS_TOOLBAR, 
                PRO.IS_PROCESS_TOOLBAR, 
                PRO.IS_MAIN 
            FROM META_DM_PROCESS_DTL PRO 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = PRO.PROCESS_META_DATA_ID 
                INNER JOIN META_DM_ROW_PROCESS_PARAM MDR ON MDR.PROCESS_META_DATA_ID = PRO.PROCESS_META_DATA_ID 
                LEFT JOIN META_BUSINESS_PROCESS_LINK BPL ON BPL.META_DATA_ID = MD.META_DATA_ID 
                $join 
            WHERE PRO.MAIN_META_DATA_ID = $metaDataId AND MDR.MAIN_META_DATA_ID = $metaDataId 
                AND (PRO.PROCESS_NAME IS NOT NULL OR (PRO.ICON_NAME IS NOT NULL AND PRO.ICON_NAME <> 'fa-') OR (PRO.IS_BP_OPEN = 1 AND PRO.IS_BP_OPEN_DEFAULT = 1)) 
                $andWhere
                AND PRO.PROCESS_META_DATA_ID IN ($processIds) 
            ORDER BY PRO.ORDER_NUM ASC");

        return $data;
    }    
    
    public function getTaskBoardColumnListModel($groupId, $record, $workSpaceParams) {
        
        $gid = ($record && isset($record[0][$groupId])) ? $record[0][$groupId] : $groupId;
        $param = array(
            'systemMetaGroupId' => $gid,
            'ignorePermission' => 1, 
            'showQuery' => 0
        );

        if (!empty($workSpaceParams)) {
            $workSpaceParams = str_replace('&amp;workSpaceParam%', '&workSpaceParam%', $workSpaceParams);
            parse_str($workSpaceParams, $workSpaceParamArray);

            // foreach ($workSpaceParamArray as $wsKey => $wsVal) {
            //     if (is_array($wsVal)) {

            //         foreach ($wsVal as $x => $paramVal) {
            //             if ($paramVal != '') {
            //                 $paramWorkSpaceCriteria[$wsKey][] = array(
            //                     'operator' => '=',
            //                     'operand' => $paramVal 
            //                 );
            //             }
            //         }

            //     } else {

            //         $paramWorkSpaceCriteria[$wsKey][] = array(
            //             'operator' => '=',
            //             'operand' => $wsVal 
            //         );
            //     }
            // }        
            $paramCriteria['domainid'][] = array(
                'operator' => '=',
                'operand' => $workSpaceParamArray['workSpaceParam']['id']
            );
            $param['criteria'] = $paramCriteria;
        }        

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
    
    public function getLifecycleHierarchyCardModel($dataViewId, $rows, $idField, $nameField, $parentField, $depth = 0, $parent = 0) {
        
        if ($rows) {
        
            $firstKey = key($rows); 
            $firstRow = $rows[$firstKey];

            if (!array_key_exists($idField, $firstRow) || !array_key_exists($nameField, $firstRow) || !array_key_exists($parentField, $firstRow)) {
                return null;
            }

            $card = array();

            foreach ($rows as $k => $row) {

                if (!array_find_val($rows, $idField, $row[$parentField])) {
                    $row[$parentField] = 0;
                }

                if ($row[$parentField] == $parent) { 

                    $isChild = array_find_val($rows, $parentField, $row[$idField]);

                    if ($depth == 0) {

                        $card[] = '
                            <div class="col-4">
                                <div class="card border-0 shadow-none mb0">
                                    <div class="card-header bg-dark" style="background-color: '.issetParam($row['columncolor']).'!important">
                                        <h6 class="card-title font-weight-bold text-white">'.$row[$nameField].'</h6>
                                    </div>
                                    <div class="card-body p-2 row ml0 mr0" style="background-color: '.issetParam($row['rowcolor']).'!important">';

                    } elseif (issetParam($row['ischild']) != '1') {

                        $card[] = '
                            <div class="card col-md-12" style="background-color: '.issetParam($row['rowcolor']).'!important">
                                <div class="card-header bg-dark" style="background-color: '.issetParam($row['columncolor']).'!important">
                                    <h6 class="card-title font-weight-bold text-white">'.$row[$nameField].'</h6>
                                </div>
                                <div class="card-body px-2 mb-2">
                                    <div class="row px-1">';

                    } 

                    if ($depth != 0 && issetParam($row['ischild']) == '1') {

                        $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');

                        $card[] = '
                            <div class="col-4 mt-2">
                                <div class="sub-card card-header bg-grey-300 dv-selection-item" data-row-data="'.$rowJson.'" style="background-color: '.issetParam($row['rowcolor']).'!important" onclick="clickItem_'.$dataViewId.'(this);" title="'.$row[$nameField].'">
                                    <h6 class="card-title">'.$row[$nameField].'</h6>
                                </div>
                            </div>';
                    }

                    if ($isChild) {
                        $card[] = $this->getLifecycleHierarchyCardModel($dataViewId, $rows, $idField, $nameField, $parentField, $depth + 1, $row['id']);
                    }

                    if ($depth == 0 || ($depth != 0 && issetParam($row['ischild']) != '1')) {
                        $card[] = '</div></div></div>';
                    } 
                }
            }

            return implode('', $card);
            
        } else {
            return null;
        }
    }

    public function getChildDataviewDataModel($metaId) {
        return $this->db->GetAll("
            SELECT 
                MMP.*, 
                " . $this->db->IfNull("MGL.LIST_NAME", "MD.META_DATA_NAME") . " AS META_DATA_NAME, 
                MGL.SHOW_POSITION
            FROM META_META_MAP MMP 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = MMP.TRG_META_DATA_ID
                INNER JOIN META_GROUP_LINK MGL ON MGL.META_DATA_ID = MMP.TRG_META_DATA_ID
            WHERE MMP.SRC_META_DATA_ID = ".$this->db->Param(0)." 
            ORDER BY MMP.ORDER_NUM", array($metaId));
    }

    public function getEcommerceCountModel($param, $title, $currentMeta) {
        $resultArr = array();

        foreach ($param as $row) {
            $paramService = array(
                'systemMetaGroupId' => $row['TRG_META_DATA_ID'],
                'showQuery' => 0,
                'paging' => array(
                    'offset' => 1,
                    'pageSize' => 1000000
                )                
            );
            $criteriaArr = array();

            $row['permission'] = 0;

            if ($getCriteria = $this->dataViewHeaderDataModel($row['TRG_META_DATA_ID'])) {
                foreach($getCriteria as $loop) {
                    if (!empty($loop['DEFAULT_VALUE'])) {
                        $criteriaArr[$loop['META_DATA_CODE']][] = array(
                            'operator' => '=',
                            'operand' => Mdmetadata::setDefaultValue($loop['DEFAULT_VALUE'])
                        );
                    }
                }
            }
            
            if ($criteriaArr) {
                $paramService['criteria'] = $criteriaArr;
            }

            $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $paramService);
            
            if ($result['status'] === 'success') {
                $row['count'] = $result['result']['paging']['totalcount'];
            } else {

                if (strpos($result['text'], 'жагсаалт тохируулаагүй байна') === false) {
                    $row['count'] = 0;
                    $row['permission'] = 0;
                } else {
                    $row['count'] = 0;
                    $row['permission'] = 1;
                }
            }

            array_push($resultArr, $row);
        }
        
        return $resultArr;
    }    

    public function getTreeDataViewByValue($dataViewId, $structureMetaDataId, $parentId, $where = '1 = 1', $depth = 0, $selectedValue = '', $k2 = false) {
        
        $dataViewId = Input::param($dataViewId);
        $structureMetaDataId = Input::param($structureMetaDataId);

        $dvQueries = self::getDVMainQueriesModel($structureMetaDataId);
        
        if (strpos(DB_DRIVER, 'postgre') !== false && $dvQueries['POSTGRE_SQL']) {
            $tableName = $dvQueries['POSTGRE_SQL'];
        } elseif (strpos(DB_DRIVER, 'mssql') !== false && $dvQueries['MS_SQL']) {
            $tableName = $dvQueries['MS_SQL'];
        } else {
            $tableName = $dvQueries['TABLE_NAME'];
        }
        
        $this->load->model('mddatamodel', 'middleware/models/');
        $getCodeNameFieldName = $this->model->getCodeNameFieldNameModel($structureMetaDataId);
        
        $metaValueIdColumnName = $getCodeNameFieldName['idColumnName'];
        $metaValueCodeColumnName = $getCodeNameFieldName['codeColumnName'];
        $metaValueNameColumnName = $getCodeNameFieldName['nameColumnName'];
        $parentIdColumnName = $getCodeNameFieldName['parentColumnName'];
        
        $valueCriteria = $this->db->GetAll("SELECT COLUMN_NAME, VALUE_CRITERIA FROM META_GROUP_CONFIG WHERE MAIN_META_DATA_ID = $structureMetaDataId AND PARENT_ID IS NULL AND TRIM(VALUE_CRITERIA) IS NOT NULL AND COLUMN_NAME IS NOT NULL AND LENGTH(VALUE_CRITERIA) > 0");
        
        if (!$metaValueIdColumnName || !$metaValueNameColumnName) {
            return null;
        }

        $langCode = $this->lang->getCode();
        $tableName = Mdmetadata::objectNameDeCompress($tableName);
        $tableName = (strlen($tableName) > 30 ? '('.$tableName.')' : $tableName);
        $tableName = str_ireplace(array(':langcode'), "'".$langCode."'", $tableName);
        $tableName = str_ireplace(array(':sessionuserkeyid', '[sessionuserkeyid]', '[sessionuserid]', ':sessionuserid'), Ue::sessionUserKeyId(), $tableName);
        $tableName = str_ireplace(array(':filterassetgroupid', ':filterstorekeeperkeyid', ':filterassetkeeperkeyid', ':filteritemcategoryid', ':filterdepartmentid', ':filterassetlocationid', ':$filterstoreid'), 'NULL', $tableName);
        $tableName = str_ireplace(array(':sessiondepartmentid'), Ue::sessionUserKeyDepartmentId(), $tableName);
        
        if (!$parentIdColumnName) {
            $parentIdColumnName = "''";
            $countSelect = '0';
        } else {
            $countSelect = "(SELECT COUNT($metaValueIdColumnName) 
                FROM $tableName TT
                WHERE TT.$parentIdColumnName = AA.$metaValueIdColumnName) ";
        }

        $result = array();

        if ($parentId == '#') {
            if ($parentIdColumnName != "''") {
                $where .= ' AND ' . $parentIdColumnName . ' IS NULL ';
            }
            if (!empty($valueCriteria)) {
                foreach ($valueCriteria as $row) {
                    if (strpos($row['VALUE_CRITERIA'], '[') === false) {
                        $replacedValueCriteria = $row['VALUE_CRITERIA'];
                    } else {
                        $replacedValueCriteria = Mdmetadata::defaultKeywordReplacer($row['VALUE_CRITERIA']);
                        $replacedValueCriteria = str_replace(array('[', ']'), '', $replacedValueCriteria);
                    }
                    $where .= ' AND ' . $row['COLUMN_NAME'] . ' ' . $replacedValueCriteria;
                }                
            }
        } else {
            $where .= ' AND LOWER(' . $parentIdColumnName . ") = '" . Str::lower($parentId) . "'";
        }

        if (Input::postCheck('drillDownDefaultCriteria') && !empty($_POST['drillDownDefaultCriteria'])) {
            $drillDown = json_decode(str_replace("&quot;", "\"", $_POST['drillDownDefaultCriteria']), true);

            if (isset($drillDown['tree_view_selected_id'])) {
                $selectedValue = $drillDown['tree_view_selected_id'];
            }
            
            foreach ($drillDown as $drillKey => $drillValue) {
                if ($drillKey !== 'tree_view_selected_id' && !empty($drillValue)) {
                    $where .= " AND " . $drillKey . "='".$drillValue."'";
                }
            }
        }        
        
        if (Input::isEmpty('uriParams') == false) {
            $uriParamsArray = json_decode(str_replace("&quot;", "\"", Input::post('uriParams')), true);
            foreach ($uriParamsArray as $drillKey => $drillValue) {
                $where .= " AND " . $drillKey . "='".$drillValue."'";
            }            
        }

        if ($values = self::getStructureDefaultValues($dataViewId, $structureMetaDataId)) {
            $where .= " AND $metaValueIdColumnName IN (".Arr::implode_key(',', $values, 'VALUE_ID', true).")";
        }
        
        if (strpos(DB_DRIVER, 'postgre') !== false) {
            $orderBy = "ORDER BY SUBSTRING(META_VALUE_CODE, '^[0-9]+')::int ASC, SUBSTRING(META_VALUE_CODE, '[^0-9]*$') ASC, META_VALUE_CODE ASC";
        } else {
            $orderBy = "ORDER BY TO_NUMBER(REGEXP_SUBSTR(META_VALUE_CODE,'^[0-9]+')) ASC, TO_NUMBER(REGEXP_SUBSTR(META_VALUE_CODE,'$[0-9]+')) ASC, META_VALUE_CODE ASC";
        }

        $reqType = issetParam($_REQUEST['type']);
        
        $this->db->StartTrans(); 
        $this->db->Execute(Ue::createSessionInfo());

        $q = '';
        if (isset($_REQUEST['q']) && !empty($_REQUEST['q'])) {
            $q = " WHERE UPPER(TEMP.META_VALUE_NAME) LIKE UPPER('%" . Input::param($_REQUEST['q']) . "%')";
            $where = '1 = 1';
        }
        
        $treeData = $this->db->GetAll(
            "SELECT * 
            FROM (
                SELECT 
                    $metaValueIdColumnName AS META_VALUE_ID, 
                    ".(($metaValueCodeColumnName) ? $metaValueCodeColumnName : $metaValueNameColumnName)." AS META_VALUE_CODE, 
                    $metaValueNameColumnName AS META_VALUE_NAME, 
                    $parentIdColumnName AS PARENT_ID,  
                    $countSelect AS CHILD_COUNT,
                    AA.* 
                FROM $tableName AA 
                WHERE $where 
            ) TEMP 
            $q
            ".$orderBy);      
        
        $this->db->CompleteTrans();
        
        if ($treeData) {
            $k = 0;

            foreach ($treeData as $tree) {
                $icon = '';
                $result[$k]['id'] = $tree['META_VALUE_ID'];
                $result[$k]['text'] = $icon . '<span ' . (isset($tree['ROW_COLOR']) && !empty($tree['ROW_COLOR']) ? 'style="background-color:'.$tree['ROW_COLOR'].'"' : '') . '>'.$tree['META_VALUE_NAME'].'</span>' . (isset($tree['ROW_COUNT']) && !empty($tree['ROW_COUNT']) ? '<span class="count-selective-task-treeview">'.$tree['ROW_COUNT'].'</span>' : '');
                if (isset($tree['ICON'])) {
                    $result[$k]['icon'] = $tree['ICON'];
                }
//                if (empty($selectedValue) && !$k2) {
//                    $result[$k]['state']['selected'] = true;
//                    $k2 = true;
//                } else
                if ($selectedValue == $tree['META_VALUE_ID']) {
                    $result[$k]['state']['selected'] = true;
                }
                $result[$k]['rowdata'] = $tree;
                unset($_POST['drillDownDefaultCriteria']);
                unset($_POST['uriParams']);

                if (isset($treeData[0]['PARENT_TREE_ONLY'])) {
                    $result[$k]['children'] = $tree['CHILD_COUNT'] > 0 ? true : false;
                    $result[0]['state'] = array('opened'=> true);
                } else {
                    if (isset($tree['TREE_LEVEL'])) {
                        $result[$k]['state']['opened'] = true;
                    }     
                    $result[$k]['children'] = $tree['CHILD_COUNT'] > 0 ? $this->getTreeDataViewByValue($dataViewId, $structureMetaDataId, $tree['META_VALUE_ID'], '1 = 1', $depth++, $selectedValue, $k2) : false;
                    if($result[$k]['children'] && !$k2 && $selectedValue = '') {
                        $result[$k]['children'][0]['state']['selected'] = true;
                        $k2 = true;
                    }
                }
                
                $k++;
            }
            $depth++;
        }
                
        return $result;
    }

    public function selectedRowConvertArrayTheme($metaDataId, $primaryField, $params = array()) {
        if (!Input::isEmpty('selectedRowData') && is_array($_POST['selectedRowData'])) {

            $selectedData = $_POST['selectedRowData'];
            
            if (is_countable($selectedData) && count($selectedData) == 1 && $selectedData[0]['value'] == '') {
                return array();
            }
            
            $ids = array();
            
            foreach ($selectedData as $k => $v) {
                if (!empty($v['value'])) {
                    $ids[] = Input::param($v['value']);
                }
            }

            $param = array(
                'systemMetaGroupId' => $metaDataId,
                'showQuery' => 0, 
                'ignorePermission' => 1,  
                'criteria' => array(
                    $primaryField => array(
                        array(
                            'operator' => 'IN',
                            'operand' => implode(',', $ids)
                        )
                    )
                )
            );
            
            if ($params) {
            
                foreach ($params as $key => $val) {
                    
                    if ($val != '' && $key != 'autoSearch') {
                        
                        $param['criteria'][$key][] = array(
                            'operator' => '=',
                            'operand' => $val
                        );
                    }
                }
            }

            $data = $this->ws->runArrayResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
            
            if ($data['status'] == 'success' && isset($data['result'])) {

                unset($data['result']['paging']);
                unset($data['result']['aggregatecolumns']);

                return $data['result'];
            }
        }

        return array();
    }    
    
    public function getMainProcessModel($metaDataId) {
        
        $data = $this->db->GetAll("
            SELECT 
                HDR.PROCESS_META_DATA_ID, 
                DTL.GET_META_DATA_ID, 
                BP.ACTION_TYPE, 
                MT.META_TYPE_ID, 
                LOWER(MT.META_TYPE_CODE) AS META_TYPE_CODE, 
                HDR.CRITERIA, 
                HDR.IS_MAIN, 
                HDR.PROCESS_NAME AS BUTTON_NAME, 
                ".$this->db->IfNull('HDR.PROCESS_NAME', $this->db->IfNull('BP.PROCESS_NAME', 'MD.META_DATA_NAME'))." AS PROCESS_NAME, 
                HDR.ICON_NAME, 
                HDR.BUTTON_STYLE, 
                HDR.IS_CONFIRM, 
                HDR.IS_CONTEXT_MENU, 
                BPD.ACTION_TYPE AS GET_BP_ACTION_TYPE 
            FROM META_DM_PROCESS_DTL HDR 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = HDR.PROCESS_META_DATA_ID 
                LEFT JOIN META_DM_TRANSFER_PROCESS DTL ON DTL.MAIN_META_DATA_ID = HDR.MAIN_META_DATA_ID 
                    AND DTL.PROCESS_META_DATA_ID = HDR.PROCESS_META_DATA_ID 
                LEFT JOIN META_BUSINESS_PROCESS_LINK BP ON BP.META_DATA_ID = HDR.PROCESS_META_DATA_ID 
                LEFT JOIN META_TYPE MT ON MT.META_TYPE_ID = MD.META_TYPE_ID 
                LEFT JOIN META_BUSINESS_PROCESS_LINK BPD ON BPD.META_DATA_ID = DTL.GET_META_DATA_ID 
            WHERE HDR.MAIN_META_DATA_ID = ".$this->db->Param(0)." 
            GROUP BY 
                HDR.PROCESS_META_DATA_ID, 
                DTL.GET_META_DATA_ID, 
                BP.ACTION_TYPE, 
                MT.META_TYPE_ID, 
                MT.META_TYPE_CODE, 
                HDR.CRITERIA, 
                HDR.IS_MAIN, 
                HDR.PROCESS_NAME, 
                BP.PROCESS_NAME, 
                MD.META_DATA_NAME, 
                HDR.ICON_NAME, 
                HDR.BUTTON_STYLE, 
                HDR.ORDER_NUM, 
                HDR.IS_CONFIRM, 
                HDR.IS_CONTEXT_MENU, 
                BPD.ACTION_TYPE 
            ORDER BY HDR.ORDER_NUM ASC", array($metaDataId));
        
        return $data;
    }
    
    public function getPanelDataListModel($dvId, $isConfig = false) {
        
        if ($dvId) {
            
            $param = array(
                'systemMetaGroupId' => $dvId,
                'showQuery'         => 0, 
                'isShowAggregate'   => 0
            );
            
            if (Input::isEmpty('subQueryId') == false && $subQueryId = Input::numeric('subQueryId')) {
                $param['subQueryId'] = $subQueryId;
            }
            
            if (Input::isEmpty('drillDownDefaultCriteria') == false) {
                $_POST['topFilter'] = 1;
                $_POST['criteria'] = Input::post('drillDownDefaultCriteria');
            }
        
            if (($isConfig || Input::postCheck('topFilter')) && Input::postCheck('criteria')) {
                
                parse_str(Input::post('criteria'), $criteria);
                
                if (is_array($criteria)) {
                    
                    foreach ($criteria as $key => $val) {
                        if ($val == 'isnull') {
                            $param['criteria'][$key][] = array(
                                'operator' => 'IS NULL',
                                'operand' => ''
                            );
                        } elseif ($val != '') {
                            $param['criteria'][$key][] = array(
                                'operator' => '=',
                                'operand' => $val
                            );
                        }
                    }
                }
            }
            
            if (($isConfig && Input::postCheck('params')) || (Input::post('formFilter') == '1' && Input::postCheck('params'))) {

                parse_str(Input::post('params'), $defaultCriteriaData);
                
                if (isset($defaultCriteriaData['param'])) {
                    
                    $defaultCriteriaParam = $defaultCriteriaData['param'];

                    if (isset($defaultCriteriaData['criteriaCondition'])) {
                        $defaultCriteriaCondition = $defaultCriteriaData['criteriaCondition'];
                        $defaultCondition = '1';
                    } else {
                        $defaultCriteriaCondition = 'LIKE';
                        $defaultCondition = '0';
                    }
                    
                    $paramDefaultCriteria = array();

                    foreach ($defaultCriteriaParam as $defParam => $defParamVal) {
                        
                        $fieldLower = strtolower($defParam);
                        $operator = ($defaultCondition == '0') ? $defaultCriteriaCondition : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : 'like');
                        
                        if (is_array($defParamVal)) {

                            if ($operator == '!=' || $operator == '=') {

                                $defParamVals = Arr::implode_r(',', $defParamVal, true);

                                if ($defParamVals != '') {
                                    $paramDefaultCriteria[$fieldLower][] = array(
                                        'operator' => ($operator == '!=' ? 'NOT IN' : 'IN'),
                                        'operand' => $defParamVals
                                    );
                                }
                                
                            } else {
                                
                                foreach ($defParamVal as $paramVal) {
                                    if ($paramVal != '') {
                                        $paramDefaultCriteria[$fieldLower][] = array(
                                            'operator' => $operator,
                                            'operand' => $paramVal
                                        );
                                    }
                                }
                            }

                        } else {

                            $defParamVal = Input::param(trim($defParamVal));
                            $defParamVal = Mdmetadata::setDefaultValue($defParamVal);
                            $mandatoryCriteria = isset($defaultCriteriaData['mandatoryCriteria'][$defParam]) ? '1' : '0';
                            
                            if ($defParamVal != '' || $mandatoryCriteria == '1') {

                                $getTypeCode = self::getDataViewGridCriteriaRowModel($dvId, $defParam);
                                $getTypeCodeLower = $getTypeCode['META_TYPE_CODE'];
                                
                                if ($defaultCondition == '0' && issetParam($getTypeCode['DEFAULT_OPERATOR']) == 'userlike') {
                                    
                                    if (strpos($defParamVal, '%') === false) {
                                        $operator = '=';
                                    } else {
                                        $operator = 'like';
                                    }
                                    
                                    $defParamValue = $defParamVal;
                                    
                                } else {
                                    $defParamValue = (strtolower($operator) == 'like') ? '%'.$defParamVal.'%' : $defParamVal; 
                                }
                                
                                if ($getTypeCodeLower == 'date' || $getTypeCodeLower == 'datetime') {

                                    $defParamVal = str_replace(
                                        array('____-__-__', '___-__-__', '__-__-__', '_-__-__', '-__-__', '-__', '_', '__:__', ':__'), '', $defParamVal
                                    );

                                    $operator = ($defaultCondition === '0') ? '=' : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : '='); 
                                    $defParamValue = $defParamVal;

                                } elseif ($getTypeCodeLower == 'long' || $getTypeCodeLower == 'integer') {

                                    $operator = ($defaultCondition === '0') ? '=' : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : '='); 
                                    $defParamValue = $defParamVal;

                                } elseif ($getTypeCodeLower == 'bigdecimal' || $getTypeCodeLower == 'number') {

                                    $defParamVal = Number::decimal($defParamVal);

                                } elseif ($getTypeCodeLower == 'boolean') {

                                    $operator = '=';
                                    $defParamValue = $defParamVal;
                                }

                                if ($defParam == 'booktypename') {
                                    $operator = ($defaultCondition == '0') ? '!=' : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : '!='); 
                                    $defParamValue = $defParamVal;
                                }

                                if ($defParam == 'accountCode' || $defParam == 'filterAccountCode') {
                                    $defParamValue = trim(str_replace('_', '', str_replace('_-_', '', $defParamValue)));
                                }

                                if ($operator == 'start') {
                                    $operator = 'like';
                                    $defParamValue = $defParamValue.'%';
                                } elseif ($operator == 'end') {
                                    $operator = 'like';
                                    $defParamValue = '%'.$defParamValue;
                                }

                                if ($defParamValue != 'null') {
                                    $paramDefaultCriteria[$fieldLower][] = array(
                                        'operator' => $operator,
                                        'operand' => ($defParamValue) ? $defParamValue : '0'
                                    );
                                }
                            }
                        }   
                    }

                    if (isset($param['criteria'])) {
                        $param['criteria'] = array_merge($param['criteria'], $paramDefaultCriteria);
                    } else {
                        $param['criteria'] = $paramDefaultCriteria;
                    }
                }
            }

            $configRow = self::getDataViewConfigRowModel($dvId);

            if ($configRow['TREE_GRID']) {

                $param['treeGrid'] = 1;
                $parentField = $configRow['parentField'];
                $isParentFilter = issetParam($configRow['IS_PARENT_FILTER']);

                $paramTreeCriteria = array();

                if (Input::isEmpty('id') == false) {

                    $paramTreeCriteria[$parentField][] = array(
                        'operator' => '=',
                        'operand' => Input::post('id')
                    );      
                    
                    if (Input::postCheck('criteria')) {
                        parse_str(Input::post('criteria'), $criteria);
                        if (isset($criteria['filterVal']) && $criteria['filterVal'] != '') {
                            $paramTreeCriteria['filterName'][] = array(
                                'operator' => '=',
                                'operand' => Input::param($criteria['filterVal'])
                            );  
                        }
                    }
                    
                    if (isset($param['criteria']) && $isParentFilter != '1') {
                        unset($param['criteria']);
                    }

                } else {
                    
                    if (!isset($param['criteria']) 
                        || (isset($param['criteria']) && empty($param['criteria'])) 
                        || $isParentFilter == '1') {
                        
                        $paramTreeCriteria[$parentField][] = array(
                            'operator' => 'IS NULL',
                            'operand' => ''
                        );
                    }
                }

                if (isset($param['criteria'])) {
                    $param['criteria'] = array_merge($param['criteria'], $paramTreeCriteria);
                } else {
                    $param['criteria'] = $paramTreeCriteria;
                }
            }
            
            $data = $this->ws->runArrayResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
            
            if ($data['status'] == 'success' && isset($data['result'])) {

                unset($data['result']['paging']);
                unset($data['result']['aggregatecolumns']);
                
                $menuList = $data['result'];
                
                if (!$isConfig && isset($menuList[0]['firstlistmenuopendvid']) && $menuList[0]['firstlistmenuopendvid'] && Input::get('pdfid') && !self::$panelOpenDataRows) {
                    
                    $firstListMenuOpenDvId = $menuList[0]['firstlistmenuopendvid'];
                    $panelDvFirstId = Input::get('pdfid');
                    
                    $subParam = array(
                        'systemMetaGroupId' => $firstListMenuOpenDvId,
                        'showQuery'         => 0, 
                        'isShowAggregate'   => 0, 
                        'ignorePermission'  => 1, 
                        'criteria' => array(
                            'filterId' => array(
                                array(
                                    'operator' => '=', 
                                    'operand' => $panelDvFirstId 
                                )
                            )
                        )
                    );
                    
                    $subData = $this->ws->runArrayResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $subParam);
                    
                    if ($subData['status'] == 'success' && isset($subData['result'][0])) {
                        unset($subData['result']['aggregatecolumns']);
                        self::$panelOpenDataRows = $subData['result'];
                    }
                }
                
                if (!$isConfig) {
                    
                    $menuArr = array();
                    
                    if ($menuList && Input::isEmpty('filterMenuTreeIds') == false) {
                        
                        $filterMenuTreeIds = Input::post('filterMenuTreeIds');
                        $filterMenuTreeIdsCount = count($filterMenuTreeIds);
                        
                        foreach ($menuList as $m => $menu) {
                            
                            $f = 1;
                            
                            foreach ($filterMenuTreeIds as $filterMenuTreeId) {
                                
                                if ($filterMenuTreeIdsCount > 1 && $filterMenuTreeIdsCount > $f && $filterMenuTreeId['id'] == $menu['id']) {
                                    
                                    $_POST['params'] = 'criteriaCondition[filterScenarioId]==&param[filterScenarioId]=' . Ue::sessionScenarioId();
                                    $_POST['id'] = $filterMenuTreeId['id'];
                                    
                                    unset($_POST['filterMenuTreeIds'][$f]);
                                    
                                    $subMenu = self::getPanelDataListModel($dvId, true);
                                    
                                    $menu['childs'] = $subMenu['rows'];
                                }
                                
                                $f++;
                            }
                            
                            $menuArr[] = $menu;
                        }
                        
                    } elseif (self::$panelOpenDataRows) {
                        
                        $panelOpenDataRowsCount = count(self::$panelOpenDataRows);
                        
                        foreach ($menuList as $m => $menu) {
                            
                            if ($panelOpenDataRowsCount == 1) {
                                
                                $arrayKey = key(self::$panelOpenDataRows);
                                
                                if ($menu['id'] == self::$panelOpenDataRows[$arrayKey]['id']) {
                                    $menu['_clickrow'] = 1;
                                }
                                
                            } else {
                                
                                foreach (self::$panelOpenDataRows as $p => $openDataRow) {

                                    if ($menu['id'] == $openDataRow['id']) {
                                        
                                        unset(self::$panelOpenDataRows[$p]);
                                        $_POST['id'] = $openDataRow['id'];

                                        $subMenu = self::getPanelDataListModel($dvId, false);

                                        if ($subMenu) {
                                            $menu['childs'] = $subMenu;
                                        }

                                        break;
                                    } 
                                }
                            }
                            
                            $menuArr[] = $menu;
                        }
                        
                    } else {
                        $menuArr = $menuList;
                    }
                    
                    return $menuArr;
                    
                } else {
                    return array(
                        'status' => 'success', 
                        'rows' => $menuList, 
                        'fieldConfig' => array(
                            'id'       => $configRow['idField'], 
                            'parent'   => $configRow['parentField'], 
                            'name'     => $configRow['nameField'], 
                            'listName' => $configRow['LIST_NAME']
                        ), 
                        'mainProcess' => $configRow['mainProcess']
                    );
                }
                
            } elseif ($data['status'] == 'error') {
                
                return array('status' => 'error', 'message' => $data['text']);
            }
        }
        
        return array();
    }
    
    public function dataviewSavedCriteriaModel($metaDataId, $criteria = true) {
        
        $id1Ph = $this->db->Param(0);
        $id2Ph = $this->db->Param(1);
        
        if ($criteria) {
            
            $sql = "
                SELECT 
                    ID, 
                    NAME, 
                    DESCRIPTION, 
                    META_DATA_ID, 
                    CRITERIA 
                FROM META_GROUP_CRITERIA_TEMPLATE 
                WHERE META_DATA_ID = $id1Ph 
                    AND CREATED_USER_ID = $id2Ph";
        } else {
            
            $sql = "
                SELECT 
                    ID, 
                    NAME, 
                    DESCRIPTION, 
                    META_DATA_ID, 
                    CRITERIA 
                FROM META_GROUP_CRITERIA_TEMPLATE 
                WHERE META_DATA_ID = $id1Ph 
                    AND CREATED_USER_ID = $id2Ph";
        }
        
        return $this->db->GetAll($sql, array($metaDataId, Ue::sessionUserKeyId()));
    }
    
    public function dataviewSavedRowCriteriaModel($id) {
        return $this->db->GetRow("SELECT ID, NAME, DESCRIPTION, META_DATA_ID, CRITERIA FROM META_GROUP_CRITERIA_TEMPLATE WHERE ID = " . $this->db->Param(0), array($id));
    }
    
    public function dataviewDeleteCriteriaModel($id) {
        $this->db->Execute("DELETE FROM META_GROUP_CRITERIA_TEMPLATE WHERE ID = ".$this->db->Param(0), array($id));
        return ['success' => true];
    }
    
    public function getDataLegendModel($dataModelId) {
        
        $result = array();
        $param = array(
            'systemMetaGroupId' => $dataModelId,
            'showQuery' => 0, 
            'ignorePermission' => 1            
        );

        $data = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($data['status'] == 'success' && isset($data['result'])) {

            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);
            $result = $data['result'];
        }

        return $result;
    }    
    
    public function getPanelDataTreeListModel($dvId, $parentId) {
        
        if ($dvId) {
            
            $param = array(
                'systemMetaGroupId' => $dvId,
                'showQuery'         => 0, 
                'isShowAggregate'   => 0, 
                'ignorePermission'  => 1 
            );
            
            if (Input::postCheck('criteria')) {
                
                parse_str(Input::post('criteria'), $criteria);
                
                if (is_array($criteria)) {
                    
                    foreach ($criteria as $key => $val) {
                        $param['criteria'][strtolower($key)][] = array(
                            'operator' => '=',
                            'operand' => $val
                        );
                    }
                }
            }
                        
            if (Input::postCheck('params')) {

                parse_str(Input::post('params'), $defaultCriteriaData);
                
                if (isset($defaultCriteriaData['param'])) {
                    
                    $defaultCriteriaParam = $defaultCriteriaData['param'];

                    if (isset($defaultCriteriaData['criteriaCondition'])) {
                        $defaultCriteriaCondition = $defaultCriteriaData['criteriaCondition'];
                        $defaultCondition = '1';
                    } else {
                        $defaultCriteriaCondition = 'LIKE';
                        $defaultCondition = '0';
                    }
                    
                    $paramDefaultCriteria = array();

                    foreach ($defaultCriteriaParam as $defParam => $defParamVal) {
                        
                        $fieldLower = strtolower($defParam);
                        $operator = ($defaultCondition == '0') ? $defaultCriteriaCondition : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : 'like');
                        
                        if (is_array($defParamVal)) {

                            if ($operator == '!=' || $operator == '=') {

                                $defParamVals = Arr::implode_r(',', $defParamVal, true);

                                if ($defParamVals != '') {
                                    $paramDefaultCriteria[$fieldLower][] = array(
                                        'operator' => ($operator == '!=' ? 'NOT IN' : 'IN'),
                                        'operand' => $defParamVals
                                    );
                                }
                                
                            } else {
                                
                                foreach ($defParamVal as $paramVal) {
                                    if ($paramVal != '') {
                                        $paramDefaultCriteria[$fieldLower][] = array(
                                            'operator' => $operator,
                                            'operand' => $paramVal
                                        );
                                    }
                                }
                            }

                        } else {

                            $defParamVal = Input::param(trim($defParamVal));
                            $defParamVal = Mdmetadata::setDefaultValue($defParamVal);
                            $mandatoryCriteria = isset($defaultCriteriaData['mandatoryCriteria'][$defParam]) ? '1' : '0';
                            
                            if ($defParamVal != '' || $mandatoryCriteria == '1') {

                                $getTypeCode = self::getDataViewGridCriteriaRowModel($dvId, $defParam);
                                $getTypeCodeLower = $getTypeCode['META_TYPE_CODE'];
                                
                                if ($defaultCondition == '0' && issetParam($getTypeCode['DEFAULT_OPERATOR']) == 'userlike') {
                                    
                                    if (strpos($defParamVal, '%') === false) {
                                        $operator = '=';
                                    } else {
                                        $operator = 'like';
                                    }
                                    
                                    $defParamValue = $defParamVal;
                                    
                                } else {
                                    $defParamValue = (strtolower($operator) == 'like') ? '%'.$defParamVal.'%' : $defParamVal; 
                                }
                                
                                if ($getTypeCodeLower == 'date' || $getTypeCodeLower == 'datetime') {

                                    $defParamVal = str_replace(
                                        array('____-__-__', '___-__-__', '__-__-__', '_-__-__', '-__-__', '-__', '_', '__:__', ':__'), '', $defParamVal
                                    );

                                    $operator = ($defaultCondition === '0') ? '=' : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : '='); 
                                    $defParamValue = $defParamVal;

                                } elseif ($getTypeCodeLower == 'long' || $getTypeCodeLower == 'integer') {

                                    $operator = ($defaultCondition === '0') ? '=' : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : '='); 
                                    $defParamValue = $defParamVal;

                                } elseif ($getTypeCodeLower == 'bigdecimal' || $getTypeCodeLower == 'number') {

                                    $defParamVal = Number::decimal($defParamVal);

                                } elseif ($getTypeCodeLower == 'boolean') {

                                    $operator = '=';
                                    $defParamValue = $defParamVal;
                                }

                                if ($defParam == 'booktypename') {
                                    $operator = ($defaultCondition == '0') ? '!=' : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : '!='); 
                                    $defParamValue = $defParamVal;
                                }

                                if ($defParam == 'accountCode' || $defParam == 'filterAccountCode') {
                                    $defParamValue = trim(str_replace('_', '', str_replace('_-_', '', $defParamValue)));
                                }

                                if ($operator == 'start') {
                                    $operator = 'like';
                                    $defParamValue = $defParamValue.'%';
                                } elseif ($operator == 'end') {
                                    $operator = 'like';
                                    $defParamValue = '%'.$defParamValue;
                                }

                                if ($defParamValue != 'null') {
                                    $paramDefaultCriteria[$fieldLower][] = array(
                                        'operator' => $operator,
                                        'operand' => ($defParamValue) ? $defParamValue : '0'
                                    );
                                }
                            }
                        }   
                    }

                    if (isset($param['criteria'])) {
                        $param['criteria'] = array_merge($param['criteria'], $paramDefaultCriteria);
                    } else {
                        $param['criteria'] = $paramDefaultCriteria;
                    }
                    
                    if (isset($defaultCriteriaData['isIgnoreParentNull'])) {
                        $isIgnoreParentNull = 1;
                    }
                    
                    if (count($param['criteria']) == 1 
                        && isset($param['criteria']['filterscenarioid']) 
                        && isset($defaultCriteriaData['isIgnoreParentNull'])) {
                        
                        unset($defaultCriteriaData['isIgnoreParentNull']);
                    }
                }
            }

            $configRow = self::getDataViewConfigRowModel($dvId);

            if ($configRow['TREE_GRID']) {

                $param['treeGrid'] = 1;
                $parentField = $configRow['parentField'];
                $isParentFilter = issetParam($configRow['IS_PARENT_FILTER']);

                $paramTreeCriteria = array();

                if ($parentId !== '#') {

                    $paramTreeCriteria[$parentField][] = array(
                        'operator' => '=',
                        'operand' => $parentId
                    );      

                    // Omnh sidebariin criteria-g damjuulah shaardlgatai bolsn
                    // DV der Is parent filter check hiiged uzsn blhgui bsn tul comment bolgow.
                    // Huuchin ajlaad bsn ni filter path ni ijil bsn bolhoor ajlaad bsn bna
                    // 2023-08-24 15:50
                    // if (isset($param['criteria']) && $isParentFilter != '1') {
                    //     unset($param['criteria']);
                    // }        

                } else {

                    if ((!isset($param['criteria']) 
                        || (isset($param['criteria']) && empty($param['criteria'])) 
                        || $isParentFilter == '1') && !isset($defaultCriteriaData['isIgnoreParentNull'])) {
                        
                        $paramTreeCriteria[$parentField][] = array(
                            'operator' => 'IS NULL',
                            'operand' => ''
                        );
                    }
                }

                if (isset($param['criteria'])) {
                    $param['criteria'] = array_merge($param['criteria'], $paramTreeCriteria);
                } else {
                    $param['criteria'] = $paramTreeCriteria;
                }
                
                if (isset($param['criteria']['filterVal']) && isset($configRow['nameField'])) {
                    unset($param['criteria']['filterVal']);
                    $param['criteria'][$configRow['nameField']][] = array(
                        'operator' => 'like',
                        'operand' => '%'.$val.'%'
                    );
                }
            }
            
            $data = $this->ws->runArrayResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
            
            if ($data['status'] == 'success' && isset($data['result'])) {

                unset($data['result']['paging']);
                unset($data['result']['aggregatecolumns']);
                
                $mainProcess = $configRow['mainProcess'];
                $treeData = $data['result'];
                $gridOption = $this->getDVGridOptionsModel($dvId);
                $rows = array();
                
                if (Input::numeric('isCheckProcessPermission') == 1 && $mainProcess) {
                    
                    $accessProcess = self::getAccessProcess($dvId);
                    
                    if ($accessProcess) {
                        
                        foreach ($mainProcess as $p => $mainProcessRow) {
                            $isAccessProcess = false;
                            foreach ($accessProcess as $accessProcessRow) {
                                if ($accessProcessRow['processid'] == $mainProcessRow['PROCESS_META_DATA_ID']) {
                                    $isAccessProcess = true;
                                    break;
                                }
                            }
                            
                            if (!$isAccessProcess) {
                                unset($mainProcess[$p]);
                            }
                        }
                        
                        if ($mainProcess) {
                            $mainProcess = array_values($mainProcess);
                        }
                        
                    } else {
                        $mainProcess = array();
                    }
                }

                if ($treeData) {
                    
                    $k = $v = $d = $otherProcessIndex = 0;
                    $viewProcessId = $viewMetaType = $deleteProcessId = $addProcessId = $removeButton = 
                    $criterias = $deleteCriterias = $otherProcess = $reportTemplate = $contextMenuProcess = '';
                    
                    $dvCode = $configRow['META_DATA_CODE'];
                    $countReportTemplate = $configRow['COUNT_REPORT_TEMPLATE'];
                    
                    $sessionUserId = Ue::sessionUserId();
                    $sessionUserKeyId = Ue::sessionUserKeyId();
                    
                    if ($mainProcess) {
                        
                        if (array_key_exists('IS_MAIN', $mainProcess[0])) {
                            
                            foreach ($mainProcess as $proRow) {
                                
                                if (issetParam($proRow['IS_CONTEXT_MENU']) == 1) {
                                    
                                    $contextMenuProcess .= $proRow['PROCESS_META_DATA_ID'].'$$'.$proRow['META_TYPE_ID'].'$$'.$proRow['CRITERIA'].'$$'.$proRow['PROCESS_NAME'].'$$'.$proRow['ICON_NAME'].'$$'.$proRow['ACTION_TYPE'].'$$'.$proRow['IS_CONFIRM'].'@@';
                                    
                                    continue;
                                }
                                
                                if ($proRow['IS_MAIN'] == 1) {
                                    
                                    if ($proRow['GET_META_DATA_ID'] == null 
                                        && $proRow['META_TYPE_CODE'] == 'process' 
                                        && $proRow['ACTION_TYPE'] == 'insert') {

                                        $addProcessId = $proRow['PROCESS_META_DATA_ID'];

                                    } elseif ($proRow['GET_META_DATA_ID']  
                                        && $proRow['META_TYPE_CODE'] == 'process' 
                                        && $proRow['ACTION_TYPE'] == 'delete') {

                                        $deleteProcessId = $proRow['PROCESS_META_DATA_ID'];

                                        $deleteCriterias .= $deleteProcessId.'$$'.$proRow['CRITERIA'].'@@';

                                        $d++;

                                    } elseif ($proRow['GET_META_DATA_ID']) {

                                        $viewProcessId = $proRow['PROCESS_META_DATA_ID'];
                                        $viewMetaType = $proRow['META_TYPE_CODE'];

                                        $criterias .= $viewProcessId.'$$'.$viewMetaType.'$$'.$proRow['CRITERIA'].'@@';

                                        $v++;
                                    } 
                                    
                                } else {
                                    
                                    if (($proRow['GET_META_DATA_ID'] == null  
                                        && $proRow['META_TYPE_CODE'] == 'process' 
                                        && $proRow['ACTION_TYPE'] == 'insert' 
                                        && $proRow['CRITERIA']) || $proRow['GET_META_DATA_ID']) {
                                        
                                        if ($proRow['META_TYPE_CODE'] == 'process' && $proRow['ACTION_TYPE'] == 'delete') {
                                            $proRow['META_TYPE_CODE'] = 'delete';
                                        } elseif (issetParam($proRow['GET_BP_ACTION_TYPE']) == 'consolidate') {
                                            $proRow['META_TYPE_CODE'] = 'consolidate';
                                        } 
                                        
                                        $otherProcess .= $proRow['PROCESS_META_DATA_ID'].'$$'.$proRow['META_TYPE_CODE'].'$$'.$proRow['CRITERIA'].'$$'.$proRow['BUTTON_NAME'].'$$'.$proRow['PROCESS_NAME'].'$$'.$proRow['ICON_NAME'].'$$'.$proRow['BUTTON_STYLE'].'$$'.issetParam($proRow['IS_CONFIRM']).'@@';

                                        $otherProcessIndex++;
                                        
                                    } elseif ($proRow['META_TYPE_CODE'] == 'bookmark') {
                                        
                                        $otherProcess .= $proRow['PROCESS_META_DATA_ID'].'$$'.$proRow['META_TYPE_CODE'].'$$'.$proRow['CRITERIA'].'$$'.$proRow['BUTTON_NAME'].'$$'.$proRow['PROCESS_NAME'].'$$'.$proRow['ICON_NAME'].'$$'.$proRow['BUTTON_STYLE'].'$$'.issetParam($proRow['IS_CONFIRM']).'@@';

                                        $otherProcessIndex++;
                                    }
                                }
                            }
                            
                            $otherProcess = rtrim($otherProcess, '@@');
                            
                        } else {
                            
                            foreach ($mainProcess as $proRow) {
                            
                                if ($proRow['GET_META_DATA_ID'] == null  
                                    && $proRow['META_TYPE_CODE'] == 'process' 
                                    && $proRow['ACTION_TYPE'] == 'insert') {

                                    $addProcessId = $proRow['PROCESS_META_DATA_ID'];

                                } elseif ($proRow['GET_META_DATA_ID']  
                                    && $proRow['META_TYPE_CODE'] == 'process' 
                                    && $proRow['ACTION_TYPE'] == 'delete') {

                                    $deleteProcessId = $proRow['PROCESS_META_DATA_ID'];

                                    $deleteCriterias .= $deleteProcessId.'$$'.issetParam($proRow['CRITERIA']).'@@';

                                    $d++;

                                } elseif ($proRow['GET_META_DATA_ID']) {

                                    $viewProcessId = $proRow['PROCESS_META_DATA_ID'];
                                    $viewMetaType = $proRow['META_TYPE_CODE'];

                                    $criterias .= $viewProcessId.'$$'.$viewMetaType.'$$'.issetParam($proRow['CRITERIA']).'@@';

                                    $v++;
                                }
                            }
                        }
                        
                        $criterias = rtrim($criterias, '@@');
                        $deleteCriterias = rtrim($deleteCriterias, '@@');
                        $contextMenuProcess = rtrim($contextMenuProcess, '@@');
                    }
                    
                    if ($deleteProcessId) {
                        $removeButton = '<div class="removeButton">'.
                            '<button type="button" class="btn trash-btn-hide" data-deleteactionbtn="1" data-processcount="'.$d.'" data-criterias="'.$deleteCriterias.'">'.
                                '<i class="fa fa-trash"></i>'.
                            '</button>'.
                        '</div>';
                    }
                    
                    if ($countReportTemplate && isset($configRow['reportTemplate'])) {
                        $reportTemplate = htmlentities(json_encode($configRow['reportTemplate']), ENT_QUOTES, 'UTF-8');
                    }
                    
                    $secondListMenuOpenDvId = Input::numeric('secondListMenuOpenDvId');
                    $panelDvSecondId = Input::numeric('panelDvSecondId');
                    
                    if ($secondListMenuOpenDvId && $panelDvSecondId) {

                        $subParam = array(
                            'systemMetaGroupId' => $secondListMenuOpenDvId,
                            'showQuery'         => 0, 
                            'isShowAggregate'   => 0, 
                            'ignorePermission'  => 1, 
                            'criteria' => array(
                                'filterId' => array(
                                    array(
                                        'operator' => '=', 
                                        'operand' => $panelDvSecondId 
                                    )
                                )
                            )
                        );

                        $subData = $this->ws->runArrayResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $subParam);

                        if ($subData['status'] == 'success' && isset($subData['result'][0])) {
                            unset($subData['result']['aggregatecolumns']);
                            self::$panelOpenDataRows = $subData['result'];
                        }
                        
                        unset($_POST['secondListMenuOpenDvId']);
                        unset($_POST['panelDvSecondId']);
                    }

                    foreach ($treeData as $key => $tree) {
                        
                        $tree['sessionuserid'] = $sessionUserId;
                        $tree['sessionuserkeyid'] = $sessionUserKeyId;
                        
                        $isChild = (isset($tree['childrecordcount']) || isset($tree['children'])) ? true : false;
                        $icon = $count = $descr = '';
                        
                        if ($gridOption['VIEWTHEME'] == 'jeasyuiTheme1') {
                            
                            $iconColorStyle = $iconColorClass = '';
                            $count = issetParam($tree['count']);
                            $physicalPath = issetParam($tree['physicalpath']);
                            
                            if (issetParam($tree['iconcolor'])) {
                                $iconColorStyle = ' style="background-color: '.$tree['iconcolor'].'"';
                                $iconColorClass = ' ' . $tree['iconcolor'];
                            }
                            
                            if ($count != '') {
                                $count = '<span class="p-row-count">'.issetParam($tree['count']).'</span>';
                            }
                            
                            $descr = '<span class="p-row-descr mt-1">'.issetParam($tree['description']).'</span>';
                            
                            if ($physicalPath) {
                                
                                $icon = '<img src="'.$physicalPath.'" onerror="onUserImgError(this);" class="rounded-circle mr-2" style="width: 40px;height: 40px;"/>';
                                
                            } elseif (issetParam($tree['icon'])) {

                                if ($isChild) {
                                    $rows[$k]['li_attr'] = array('class' => 'jstree-custom-folder-icon' . $iconColorClass);
                                } else {
                                    $icon = '<div class="mr7"><span class="p-row-icon-circle"'.$iconColorStyle.'><i class="'.$tree['icon'].'"></i></span></div>';
                                }
                                
                            } else {
                                
                                $icon = '<div class="mr-2"><span class="p-row-icon-circle"'.$iconColorStyle.'>'.Str::utf8_substr($tree[$configRow['nameField']], 0, 1).'</span></div>';
                            }   
                            
                            $text = '<span><div class="d-flex align-items-center">' . $icon . '<div class="nameField"><div class="d-flex justify-content-between"><span class="p-row-title">'.$tree[$configRow['nameField']].'</span>'.$count.'</div>'.$descr.'</div></div></span>'.$removeButton;
                            
                        } else {
                            
                            if (issetParam($tree['icon'])) {
                            
                                $iconColorStyle = $iconColorClass = '';

                                if (issetParam($tree['iconcolor'])) {
                                    $iconColorStyle = ' style="color: '.$tree['iconcolor'].'"';
                                    $iconColorClass = ' ' . $tree['iconcolor'];
                                }

                                if ($isChild) {
                                    $rows[$k]['li_attr'] = array('class' => 'jstree-custom-folder-icon' . $iconColorClass);
                                } else {
                                    $icon = '<div class="mr5"><i class="'.$tree['icon'].'"'.$iconColorStyle.'></i></div> ';
                                }
                            }   
                        
                            $text = '<span><div class="d-flex">' . $icon . '<div class="nameField"><span class="p-row-title">'.$tree[$configRow['nameField']].'</span></div></div></span>'.$removeButton;
                        }
                        
                        $rows[$k]['id'] = $tree[$configRow['idField']];
                        $rows[$k]['text'] = '<span data-second-id="' . $tree[$configRow['idField']] . '" style="padding:0;" data-secondprocessid="'.$viewProcessId.'" data-secondprocessidcount="'.$v.'" data-criterias="'.$criterias.'" data-othercriterias="'.$otherProcess.'" data-secondtypecode="'.$viewMetaType.'" data-countrt="'.$countReportTemplate.'" data-rtmplts="'.$reportTemplate.'" class="media d-flex mt0 pt5" data-rowdata="'. htmlentities(str_replace('&quot;', '\\&quot;', json_encode($tree, JSON_UNESCAPED_UNICODE)), ENT_QUOTES, 'UTF-8').'" data-buttonbarstyle="'.$configRow['BUTTON_BAR_STYLE'].'" data-dvcode="'.$dvCode.'" data-contextmenu="'.$contextMenuProcess.'">'.$text.'</span>';
                        $rows[$k]['children'] = $isChild;
                        
                        $parentIdOpen = $parentId;
                        
                        if (Input::isEmpty('filterObjectDtl') == false) {
                            
                            $filterObjectDtl = Input::post('filterObjectDtl');
                            $filterObjectDtlCount = count($filterObjectDtl);
                            
                            foreach ($filterObjectDtl as $o => $objectRow) {
                                
                                $f = 1;
                                
                                if ($filterObjectDtlCount > 1 && $filterObjectDtlCount > $f && $rows[$k]['id'] == $objectRow['id']) {
                                    
                                    $tree['isopen'] = 1;
                                    $parentIdOpen = $objectRow['id'];
                                    
                                    unset($_POST['filterObjectDtl'][$o]);
                                    
                                    break;
                                }
                                
                                $f++;
                            }
                            
                            if ($filterObjectDtlCount == 1) {
                                $filterObjectDtlEnd = end($filterObjectDtl);
                            }
                            
                        } elseif (self::$panelOpenDataRows) {
                        
                            $panelOpenDataRowsCount = count(self::$panelOpenDataRows);

                            if ($panelOpenDataRowsCount == 1) {

                                $arrayKey = key(self::$panelOpenDataRows);

                                if ($tree['id'] == self::$panelOpenDataRows[$arrayKey]['id']) {
                                    $tree['isselected'] = 1;
                                }

                            } else {

                                foreach (self::$panelOpenDataRows as $p => $openDataRow) {

                                    if ($tree['id'] == $openDataRow['id']) {

                                        unset(self::$panelOpenDataRows[$p]);
                                        
                                        $parentIdOpen = $openDataRow['id'];
                                        $tree['isopen'] = 1;

                                        break;
                                    } 
                                }
                            }
                        }
                        
                        if (issetParam($tree['isopen']) == '1') {
                            $rows[$k]['state']['opened'] = true;
                            $childRows = $this->getPanelDataTreeListModel($dvId, $parentIdOpen);
                            $rows[$k]['children'] = $childRows['rows'];
                        }

                        if (issetParam($tree['isselected']) == '1' || (isset($filterObjectDtlEnd) && $filterObjectDtlEnd['id'] == $rows[$k]['id'])) {
                            $rows[$k]['state']['selected'] = true;
                        }

                        $k++;
                    } 
                    
                    if ($configRow['COUNT_WFM_WORKFLOW'] != '0' && $configRow['IS_USE_WFM_CONFIG'] == '1') {
                        $result['isWorkflow'] = 1;
                    }
                } 
                
                $result['rows'] = $rows;
                
                if ($parentId == '#' && !isset($isIgnoreParentNull)) {
                    
                    $getFilterParams = $this->dataViewHeaderDataModel($dvId);

                    if ($getFilterParams && count($getFilterParams) > 1) {
                        $result['popupSearch'] = 1;
                    }
                    
                    $result['mainProcess'] = $mainProcess;
                    $result['gridoption'] = $gridOption;
                } 
                
                return $result;
                
            } elseif ($data['status'] == 'error') {
                
                return array('status' => 'error', 'message' => $data['text']);
            }
        }
        
        return array();
    }    

    public function getPackageLinkModel($metaDataId) {
        $row = $this->db->GetRow("
            SELECT 
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME, 
                PL.RENDER_TYPE, 
                PL.IS_IGNORE_MAIN_TITLE, 
                PL.TAB_BACKGROUND_COLOR,
                PL.SPLIT_COLUMN,
                PL.PACKAGE_CLASS,
                PL.IS_CHECK_PERMISSION,
                PL.DEFAULT_META_ID,
                PL.IS_IGNORE_PACKAGE_TITLE,
                PL.IS_FILTER_BTN_SHOW,
                PL.COUNT_META_DATA_ID,
                PL.IS_REFRESH, 
                PL.IS_EXPORT, 
                MDD.META_TYPE_ID AS DEFAULT_META_TYPE_ID 
            FROM META_DATA MD 
                LEFT JOIN META_PACKAGE_LINK PL ON PL.META_DATA_ID = MD.META_DATA_ID 
                LEFT JOIN META_DATA MDD ON MDD.META_DATA_ID = PL.DEFAULT_META_ID 
            WHERE MD.META_DATA_ID = ".$this->db->Param(0), array($metaDataId) 
        );

        return $row;
    }    

    public function getPolygonListModel() {
        $param = array(
            'systemMetaGroupId' => Input::post('metaDataId'),
            'showQuery' => 0, 
            'ignorePermission' => 1 
        );
        
        $result = $this->ws->runResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($result['status'] == 'success' && isset($result['result'])) {

            unset($result['result']['paging']);
            unset($result['result']['aggregatecolumns']);

            return $result['result'];
            $response = array('status' => 'success', 'data' => $result['result']);
        } else {
            $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }

        return $response;
    }    
    
    public function getOnlyShowColumnsModel($dataViewId) {
        
        $cache = phpFastCache();
        $data = $cache->get('dvOnlyShowColumns_'.$dataViewId);
        
        if ($data == null) {
            
            $data = $this->db->GetAll("
                SELECT 
                    ".$this->db->IfNull('LABEL_NAME', 'FIELD_PATH')." AS LABEL_NAME, 
                    LOWER(FIELD_PATH) AS FIELD_PATH, 
                    DATA_TYPE AS META_TYPE_CODE, 
                    COLUMN_WIDTH, 
                    BODY_ALIGN,
                    LABEL_NAME AS LABELNAME, 
                    ICON_NAME 
                FROM META_GROUP_CONFIG 
                WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                    AND PARENT_ID IS NULL 
                    AND IS_SELECT = 1 
                    AND IS_SHOW = 1 
                    AND COLUMN_NAME IS NOT NULL 
                ORDER BY DISPLAY_ORDER ASC", array($dataViewId));
            
            $cache->set('dvOnlyShowColumns_'.$dataViewId, $data, Mdwebservice::$expressionCacheTime);
        }

        return $data;
    }
    
    public function getMetaTypeLinkDataModel($metaDataId) {
        
        $row = $this->db->GetRow("
            SELECT 
                MD.META_TYPE_ID,
                BL.BOOKMARK_URL 
            FROM META_DATA MD 
                LEFT JOIN META_BOOKMARK_LINKS BL ON BL.META_DATA_ID = MD.META_DATA_ID 
            WHERE MD.META_DATA_ID = ".$this->db->Param(0), 
            array($metaDataId)
        );
        
        return $row;
    }
    
    public function getDataViewGridHeaderDashboardModel($metaDataId, $condition = '1 = 1', $showType = 1, $isPopupWindow = false, $isBasketWindow = false) {

        if ($showType == 1) {
            $whereStr = ' AND (GC.IS_SIDEBAR != 1 OR GC.IS_SIDEBAR IS NULL) ';
        } elseif ($showType == 2) {
            $whereStr = ' AND GC.IS_SIDEBAR = 1 ';
        } elseif ($showType == 3) {
            $whereStr = ' AND GC.IS_SELECT = 1 ';
        }

        $crmUserId = Session::get(SESSION_PREFIX . 'crmuserid');
        $userId = $crmUserId ? $crmUserId : Ue::sessionUserId();

        if ($isPopupWindow) {
            $basketDataCheck = self::getBasketDataViewGridHeaderModel($metaDataId, $condition);

            if (count($basketDataCheck) > 0) {
                return $basketDataCheck;
            }
        }
        
        if ($isBasketWindow) {
            $basketDataCheck = self::getBasketDataViewGridHeaderModel($metaDataId, $condition, 'AND GC.IS_BASKET = 1', "GC.IS_BASKET AS IS_SHOW, '' AS GROUP_CONFIG_GROUP_PATH , '' AS GROUP_PARAM_CONFIG_TOTAL, GC.IS_REQUIRED, LOWER(GC.FIELD_PATH) AS PARAM_REAL_PATH, GC.DEFAULT_VALUE, ");
            
            if (count($basketDataCheck) > 0) {
                return $basketDataCheck;
            }
        }
        
        $dvConfig = self::getDataViewConfigRowModel($metaDataId); 
        
        $metaDataIdPh = $this->db->Param(0);
        $userIdPh = $this->db->Param(1);
        
        if (isset($dvConfig['COUNT_CUSTOMER_FIELD']) && $dvConfig['COUNT_CUSTOMER_FIELD'] > 0) { 
            
            $customerField = $this->db->IfNull('DF.LABEL_NAME', $this->db->IfNull('CK.HEADER_NAME', 'GC.LABEL_NAME'))." AS LABEL_NAME, 
                        CASE WHEN CH.COUNTT > 0 THEN CK.ORDER_NUM ELSE DF.DISPLAY_ORDER END AS ORDER_NUM, 
                        CASE WHEN CK.IS_FREEZE IS NULL THEN CK.ORDER_NUM ELSE DF.DISPLAY_ORDER END AS IS_FREEZE, ";
            $customerJoin = 'INNER JOIN CUSTOMER_DV_FIELD DF ON GC.MAIN_META_DATA_ID = DF.META_DATA_ID AND LOWER(DF.FIELD_PATH) = LOWER(GC.FIELD_PATH) AND DF.IS_ACTIVE = 1';
            
        } else {
            $customerField = $this->db->IfNull('CK.HEADER_NAME', 'GC.LABEL_NAME')." AS LABEL_NAME, 
                        CASE WHEN CH.COUNTT > 0 THEN CK.ORDER_NUM ELSE GC.DISPLAY_ORDER END AS ORDER_NUM, 
                        CASE WHEN CK.IS_FREEZE IS NULL THEN CK.ORDER_NUM ELSE GC.DISPLAY_ORDER END AS IS_FREEZE, "; 
            $customerJoin = '';
        }
        
        $gridOption = self::getDVGridOptionsModel($metaDataId);
        
        $data = $this->db->GetAll("
            SELECT * FROM (
                SELECT 
                    DISTINCT 
                    $customerField 
                    LOWER(GC.COLUMN_NAME) AS FIELD_NAME,
                    LOWER(GC.FIELD_PATH) AS FIELD_PATH, 
                    GC.DATA_TYPE AS META_TYPE_CODE, 
                    GC.COLUMN_WIDTH, 
                    GC.SIDEBAR_NAME,
                    GC.TEXT_WEIGHT, 
                    GC.TEXT_COLOR, 
                    GC.HEADER_ALIGN, 
                    GC.BODY_ALIGN, 
                    GC.TEXT_TRANSFORM, 
                    GC.MAX_VALUE,
                    '' AS COLUMN_AGGREGATE, 
                    '' AS IS_BOLD, 
                    GC.ID,
                    GC.IS_MERGE,
                    GC.IS_BASKET_EDIT,
                    GC.FRACTION_RANGE,
                    GC.EXCEL_COLUMN_WIDTH,
                    GC.EXCEL_ROTATE, 
                    GC.IS_IGNORE_EXCEL, 
                    CASE WHEN CH.COUNTT > 0 THEN CK.IS_SHOW ELSE GC.IS_SHOW END AS IS_SHOW, 
                    MDD.COUNTT AS DRILLDOWN_COLUMN, 
                    MDD.LINK_META_DATA_ID, 
                    MDD.DRILLDOWN_META_TYPE_CODE,
                    MDD.CRITERIA,
                    CASE WHEN TEM.IS_COLSPAN IS NULL THEN 0 ELSE TEM.IS_COLSPAN END AS DV_COLSPAN,
                    GC.INLINE_PROCESS_ID,
                    GC.LOOKUP_META_DATA_ID,
                    CK.PARAM_WIDTH
                FROM META_GROUP_CONFIG GC 
                    $customerJoin 
                    LEFT JOIN (
                        SELECT COUNT(MAIN_META_DATA_ID) AS COUNTT, MAIN_META_DATA_ID, PARAM_NAME FROM META_GROUP_CONFIG_USER WHERE USER_ID = $userIdPh AND IS_SHOW = 1 AND MAIN_META_DATA_ID = $metaDataIdPh GROUP BY MAIN_META_DATA_ID, PARAM_NAME
                    ) CH ON GC.MAIN_META_DATA_ID = CH.MAIN_META_DATA_ID
                    LEFT JOIN (
                        SELECT MAIN_META_DATA_ID, ORDER_NUM, PARAM_NAME, ". (isset($gridOption['ONRESIZECOLUMN']) && $gridOption['ONRESIZECOLUMN'] == 'true' ? 'PARAM_WIDTH' : '\'\'') ." AS PARAM_WIDTH, IS_SHOW, IS_FREEZE, HEADER_NAME FROM META_GROUP_CONFIG_USER WHERE USER_ID = $userIdPh AND MAIN_META_DATA_ID = $metaDataIdPh GROUP BY MAIN_META_DATA_ID, ORDER_NUM, PARAM_NAME, IS_SHOW, IS_FREEZE, HEADER_NAME, PARAM_WIDTH
                    ) CK ON GC.MAIN_META_DATA_ID = CK.MAIN_META_DATA_ID AND LOWER(GC.FIELD_PATH) = LOWER(CK.PARAM_NAME) 
                    LEFT JOIN (
                        SELECT  
                            ".$this->db->listAgg('MDD.LINK_META_DATA_ID', ',', 'MDD.LINK_META_DATA_ID, MDD.CRITERIA')." AS LINK_META_DATA_ID,
                            LOWER(".$this->db->listAgg('MDD.CRITERIA', ',', 'MDD.LINK_META_DATA_ID, MDD.CRITERIA').") AS CRITERIA,
                            COUNT(MDD.LINK_META_DATA_ID) AS COUNTT,
                            MDD.MAIN_GROUP_LINK_PARAM,
                            LOWER(".$this->db->listAgg('MT.META_TYPE_CODE', ',', 'MDD.LINK_META_DATA_ID, MDD.CRITERIA').") AS DRILLDOWN_META_TYPE_CODE
                        FROM META_GROUP_LINK MGL
                            INNER JOIN META_DM_DRILLDOWN_DTL MDD ON MDD.MAIN_GROUP_LINK_ID = MGL.ID
                            INNER JOIN META_DATA MDA ON MDD.LINK_META_DATA_ID = MDA.META_DATA_ID
                            INNER JOIN META_TYPE MT ON MDA.META_TYPE_ID = MT.META_TYPE_ID
                        WHERE MGL.META_DATA_ID = $metaDataIdPh
                        GROUP BY MDD.MAIN_GROUP_LINK_PARAM
                    ) MDD ON LOWER(GC.FIELD_PATH) = LOWER(MDD.MAIN_GROUP_LINK_PARAM)
                    LEFT JOIN (
                        SELECT
                            CASE
                                WHEN MAX(CH.COUNTT) > 0
                                THEN COUNT(TEM.SIDEBAR_NAME)
                                ELSE COUNT(GC.SIDEBAR_NAME)
                            END AS IS_COLSPAN,
                            GC.SIDEBAR_NAME,
                            GC.MAIN_META_DATA_ID
                        FROM META_GROUP_CONFIG GC 
                        $customerJoin 
                        LEFT JOIN (
                            SELECT 
                                COUNT(MAIN_META_DATA_ID) AS COUNTT,
                                MAIN_META_DATA_ID,
                                PARAM_NAME
                            FROM META_GROUP_CONFIG_USER
                            WHERE USER_ID = $userIdPh 
                                AND IS_SHOW = 1 
                                AND MAIN_META_DATA_ID = $metaDataIdPh 
                            GROUP BY MAIN_META_DATA_ID, PARAM_NAME
                        ) CH ON GC.MAIN_META_DATA_ID = CH.MAIN_META_DATA_ID AND LOWER(GC.FIELD_PATH) = LOWER(CH.PARAM_NAME)
                        LEFT JOIN (
                            SELECT 
                                GC.SIDEBAR_NAME,
                                GC.MAIN_META_DATA_ID,
                                CK.PARAM_NAME
                            FROM META_GROUP_CONFIG GC 
                            $customerJoin 
                            INNER JOIN (
                                SELECT 
                                    MAIN_META_DATA_ID,
                                    ORDER_NUM,
                                    PARAM_NAME,
                                    IS_SHOW,
                                    IS_FREEZE,
                                    HEADER_NAME
                                FROM META_GROUP_CONFIG_USER
                                WHERE USER_ID = $userIdPh 
                                    AND MAIN_META_DATA_ID = $metaDataIdPh
                                GROUP BY MAIN_META_DATA_ID, ORDER_NUM, PARAM_NAME, IS_SHOW, IS_FREEZE, HEADER_NAME
                            ) CK ON GC.MAIN_META_DATA_ID = CK.MAIN_META_DATA_ID AND LOWER(GC.FIELD_PATH) = LOWER(CK.PARAM_NAME)
                            WHERE CK.IS_SHOW = 1 AND GC.IS_SELECT = 1 
                        ) TEM ON GC.MAIN_META_DATA_ID = CH.MAIN_META_DATA_ID AND LOWER(GC.FIELD_PATH) = LOWER(TEM.PARAM_NAME)
                        WHERE GC.MAIN_META_DATA_ID = $metaDataIdPh 
                            AND GC.IS_SELECT = 1 
                            AND GC.PARENT_ID IS NULL 
                            AND GC.SIDEBAR_NAME IS NOT NULL 
                        GROUP BY GC.MAIN_META_DATA_ID, GC.SIDEBAR_NAME 
                    ) TEM ON GC.MAIN_META_DATA_ID = TEM.MAIN_META_DATA_ID AND GC.SIDEBAR_NAME = TEM.SIDEBAR_NAME 
                WHERE GC.MAIN_META_DATA_ID = $metaDataIdPh 
                    AND GC.IS_SELECT = 1 
                    AND GC.PARENT_ID IS NULL 
                    AND GC.DATA_TYPE <> 'group'  
                    $whereStr 
            ) TEMP WHERE $condition
            ORDER BY ORDER_NUM ASC", array($metaDataId, $userId));

        return $data;
    }

    public function saveDataViewConfigUserModel($metaDataId, $viewType) {
        
        try {
            
            $keyCols = array('META_DATA_ID', 'USER_ID');

            $fields = array(
                'ID'            => getUID(), 
                'META_DATA_ID'  => $metaDataId,
                'USER_ID'       => Ue::sessionUserId(), 
                'DEFAULTVIEWER' => $viewType
            );
            $this->db->Replace('CUSTOMER_META_USER_CONFIG', $fields, $keyCols, true);
            
            $tmp_dir = Mdcommon::getCacheDirectory();
            $dvUserConfigs = glob($tmp_dir."/*/dv/dvUserConfig_".$metaDataId."_".$fields['USER_ID'].".txt");
            
            foreach ($dvUserConfigs as $dvUserConfig) {
                @unlink($dvUserConfig);
            }
            
            $response = array('status' => 'success');
            
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }
    
    public function bpToolbarCommandModel($metaDataId, $processId, $selectedRow = array()) {

        $commandBtn = $isEdit = $cmdBtn = '';
        $data = self::getToolbarButtonByProcessId($metaDataId, $processId);

        if ($data) {
            
            foreach ($data as $key => $row) {
                $uniqId = Input::postCheck('uniqId') ? Input::post('uniqId') : $metaDataId;
                $metaDataCode = $row['META_DATA_CODE'];
                
                $commandFunction = array (
                    'functionName' => 'transferProcessAction',
                    'metaDataId' => $metaDataId,
                    'processMetaDataId' => $row['PROCESS_META_DATA_ID'],
                    'metaTypeId' => $row['META_TYPE_ID'],
                    'type' => 'toolbar',
                    'element' => 'this',
                    'passPath' => issetParam($row['PASSWORD_PATH']), 
                    'buttonStyle' => $row['BUTTON_STYLE'], 
                    'iconName' => $row['ICON_NAME']
                );

                $linkMetaData = $this->db->GetRow("SELECT 
                                                        MDD.ID,
                                                        MGL.META_DATA_ID,
                                                        MDD.LINK_META_DATA_ID, 
                                                        MDD.CRITERIA,
                                                        MDD.DIALOG_WIDTH,
                                                        MDD.DIALOG_HEIGHT,
                                                        MDD.SHOW_TYPE,
                                                        MDD.MAIN_GROUP_LINK_PARAM,
                                                        MT.META_TYPE_NAME, 
                                                        LOWER(MT.META_TYPE_CODE) AS META_TYPE_CODE,
                                                        MDDP.DEFAULT_VALUE,
                                                        LOWER(MDDP.SRC_PARAM) AS SRC_PARAM,
                                                        LOWER(MDDP.TRG_PARAM) AS TRG_PARAM
                                                    FROM META_GROUP_LINK MGL
                                                        INNER JOIN META_DM_DRILLDOWN_DTL MDD ON MDD.MAIN_GROUP_LINK_ID = MGL.ID
                                                        INNER JOIN META_DATA MDA ON MDD.LINK_META_DATA_ID = MDA.META_DATA_ID
                                                        INNER JOIN META_TYPE MT ON MDA.META_TYPE_ID = MT.META_TYPE_ID
                                                        LEFT JOIN META_DM_DRILLDOWN_PARAM MDDP ON MDDP.DM_DRILLDOWN_DTL_ID = MDD.ID
                                                    WHERE MGL.META_DATA_ID =  ".$this->db->Param(0)."  AND MDD.LINK_META_DATA_ID = ".$this->db->Param(1), array($metaDataId, $row['PROCESS_META_DATA_ID']));
                if ($linkMetaData) {
                    if ($linkMetaData['META_TYPE_CODE'] == 'process') {
                        
                        $commandBtn .= Html::anchor(
                            'javascript:;', (($row['ICON_NAME'] != "") ? '<i class="far ' . $row['ICON_NAME'] . '"></i> ' : '') . Lang::line($row['PROCESS_NAME']), array(
                                'class' => 'btn ' . (isset($row['BUTTON_STYLE']) ? $row['BUTTON_STYLE'] : 'btn-secondary') . ' btn-circle btn-sm mr-1',
                                'title' => !empty($row['ICON_PROCESS_NAME']) ? $row['ICON_PROCESS_NAME'] : $row['META_DATA_NAME'],
                                'onclick' => 'drillDownTransferProcessAction(\''.$commandFunction['functionName'].'\', \'1\', \''. $linkMetaData['CRITERIA'] .'\', \'\', \''. $commandFunction['metaDataId'] .'\', \''. $linkMetaData['LINK_META_DATA_ID'] .'\', \''. $commandFunction['type'] .'\', \'\', '. $commandFunction['element'] .', {callerType: \''.$metaDataCode.'\', isDrillDown: true});'
                            ), true
                        );
                    } else {
                        /* $cellFormatter = "formatter: function(v, r, i) {
                            if (v) {
                                return '<a href=\"javascript:;\" onclick=\"gridDrillDownLink(this, \'$metaDataCode\', \'". $link_metatypecode ."\', \'". $clinkMetadataId ."\', \'". str_replace("'", "\\\\\'", $link_linkcriteria) ."\', \'". $metaDataId ."\', \'". $row['FIELD_PATH'] ."\', \'". $link_linkmetadataid ."\', \'". $sourceParam ."\', $isnewTab, undefined, \'". $link_dialogWidth ."\', \'". $link_dialogHeight ."\')\">'+ v + '</a>';
                            } else {
                                return '';
                            }
                        },"; */

                        $sourceParam =  $dtlSourceParam  = '';
                        $dtlData = $this->db->GetAll("
                            SELECT 
                                LOWER(SRC_PARAM) AS SRC_PARAM, 
                                LOWER(TRG_PARAM) AS TRG_PARAM, 
                                DEFAULT_VALUE 
                            FROM META_DM_DRILLDOWN_PARAM  
                            WHERE DM_DRILLDOWN_DTL_ID = ".$this->db->Param(0), 
                            array($linkMetaData['ID']) 
                        );
                        
                        if ($dtlData) {
                            
                            foreach ($dtlData as $dtl) {
                                
                                if ($dtl['DEFAULT_VALUE']) {
                                    
                                    if ($dtl['TRG_PARAM'] && $dtl['DEFAULT_VALUE']) {
                                        $dtlSourceParam .= ($dtl['TRG_PARAM']) ? $dtl['TRG_PARAM'] . '=' . $dtl['DEFAULT_VALUE'] . '&' : '';
                                    } else {
                                        $dtlSourceParam .= $dtl['DEFAULT_VALUE'] . '&';
                                    }
                                    
                                } else {
                                    $dtlSourceParam .= ($dtl['TRG_PARAM']) ? $dtl['TRG_PARAM'] . "=" . $selectedRow[$dtl['SRC_PARAM']] ."&" : '';
                                }
                            }
                        }
                        
                        $sourceParam .= rtrim($dtlSourceParam, '&');
                        $commandBtn .= Html::anchor(
                            'javascript:;', (($row['ICON_NAME'] != "") ? '<i class="far ' . $row['ICON_NAME'] . '"></i> ' : '') . Lang::line($row['PROCESS_NAME']), array(
                                'class' => 'btn ' . (isset($row['BUTTON_STYLE']) ? $row['BUTTON_STYLE'] : 'btn-secondary') . ' btn-circle btn-sm mr-1',
                                'title' => !empty($row['ICON_PROCESS_NAME']) ? $row['ICON_PROCESS_NAME'] : $row['META_DATA_NAME'],
                                'onclick' => 'gridDrillDownLink(this, \''.$metaDataCode.'\', \''. $row['META_TYPE_ID'] .'\', \'1\', \''. str_replace("'", "\\\\\'", $linkMetaData['CRITERIA']) .'\', \''. $metaDataId .'\', \''. issetParam($row['FIELD_PATH']) .'\', \''. $linkMetaData['LINK_META_DATA_ID'] .'\', \''. $sourceParam .'\');'
                            ), true
                        );
                    }
                }
                
            }
        }

        if ($commandBtn != '') {
            $cmdBtn .= '<div class="btn-group btn-group-devided">';
                $cmdBtn .= '<table class="basictable">';
                    $cmdBtn .= '<tbody>';
                        $cmdBtn .= '<tr data-rowdata="'. htmlentities(json_encode(array($selectedRow)), ENT_QUOTES, 'UTF-8') .'">';
                            $cmdBtn .= '<td>';
                                $cmdBtn .= $commandBtn;
                            $cmdBtn .= '</td>';
                        $cmdBtn .= '</tr>';
                    $cmdBtn .= '</tbody>';
                $cmdBtn .= '</table>';
            $cmdBtn .= '</div>';
        }

        return $cmdBtn;
    }

    
    public function getToolbarButtonByProcessId($metaDataId, $processId) {
        
        if ($metaDataId && $processId) {
            $checkToolbar = $this->db->GetRow("
                                        SELECT 
                                            IS_PROCESS_TOOLBAR 
                                        FROM META_DM_PROCESS_DTL 
                                        WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                                            AND PROCESS_META_DATA_ID = ".$this->db->Param(1), array($metaDataId, $processId));

            if (issetParam($checkToolbar['IS_PROCESS_TOOLBAR']) === '1') {
                $data = $this->db->GetAll("
                    SELECT 
                        PRO.PROCESS_META_DATA_ID, 
                        PRO.PROCESS_NAME, 
                        PRO.ICON_NAME, 
                        PRO.BATCH_NUMBER,
                        PRO.BUTTON_STYLE ,
                        PRO.IS_SHOW_POPUP, 
                        TRIM(PRO.CRITERIA) AS CRITERIA, 
                        MD.META_DATA_CODE,
                        MD.META_DATA_NAME,
                        BPL.PROCESS_NAME AS ICON_PROCESS_NAME, 
                        MD.META_TYPE_ID 
                    FROM META_DM_PROCESS_DTL PRO
                        INNER JOIN META_DATA MD ON PRO.PROCESS_META_DATA_ID = MD.META_DATA_ID
                        LEFT JOIN META_BUSINESS_PROCESS_LINK BPL ON BPL.META_DATA_ID = MD.META_DATA_ID
                    WHERE PRO.MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                       AND PRO.PROCESS_META_DATA_ID <> ".$this->db->Param(1)." 
                       AND PRO.IS_USE_PROCESS_TOOLBAR = 1
                    ORDER BY PRO.ORDER_NUM ASC", array($metaDataId, $processId));
    
                return $data;
            }
        }
        
        return null;
    }

    public function freezeSplitter($data, $dataviewCustomerCfg) {
        $isSeperator = false;
        foreach ($data as $key => $row) {
            if ($row['ORDER_NUM'] == $dataviewCustomerCfg['ORDER_NUM']) {
                if (isset($data[$key+1])) {
                    $earr = explode('^', $row['SIDEBAR_NAME']);
                    foreach ($earr as $rrr) {
                        if ($rrr && strpos($data[$key+1]['SIDEBAR_NAME'], trim($rrr)) !== false) {
                            $isSeperator = true;
                        }    
                    }
                } 
                if ($isSeperator) {
                    if (strpos($row['SIDEBAR_NAME'], '^') === false) {
                        $data[$key]['SIDEBAR_NAME'] = $row['SIDEBAR_NAME'].'♥♥♥';
                    } else {
                        $data[$key]['SIDEBAR_NAME'] = preg_replace('/(.*?)\^/', '$1♥♥♥^', $row['SIDEBAR_NAME']) . '♥♥♥';
                    }
                }
            }
        }
        return $data;
    }
    
    public function resolveHtmlTableMergeHeader($data, $dataViewId, $dataviewCustomerCfg = [], $fieldPath = 'FIELD_PATH') {
        $sessionUserId = Ue::sessionUserId();
        
        $cache = phpFastCache();
        $row = $cache->get('dvUserConfigMergeCols2_'.$dataViewId.'_'.$sessionUserId);
        $row = null;

        if ($row == null) {
            
            $data = array_map(function($val){ $val['SIDEBAR_NAME'] = rtrim(ltrim(trim($val['SIDEBAR_NAME']), '^'), '^'); return $val; }, $data);            
            
            if (issetParam($dataviewCustomerCfg['ORDER_NUM'])) {
                $data = self::freezeSplitter($data, $dataviewCustomerCfg);
            }
            
            if (!$data) {
                return [];
            }
            
            $dataGroupByName = Arr::groupByArray($data, 'SIDEBAR_NAME');

            if (count($dataGroupByName['']['rows']) === count($data)) {
                $resultMerge = [$dataGroupByName['']['rows']];
                $cache->set('dvUserConfigMergeCols2_'.$dataViewId.'_'.$sessionUserId, $resultMerge, Mdwebservice::$expressionCacheTime);
                
                return $resultMerge;
            }
            
            if (count($dataGroupByName['']['rows'])) {
                $rowsData_ = $dataGroupByName['']['rows'];
                if (isset($rowsData_[0]['ORDER_NUM'])) {
                    $orderNum = $rowsData_[0]['ORDER_NUM'];
                    foreach ($rowsData_ as $index => $_data) {
                        if ($orderNum != $_data['ORDER_NUM']) {
                            $resetIndex = '♠'.$index;
                        }
                        if (isset($resetIndex)) {
                            $dataGroupByName[$resetIndex]['rows'][] = $_data;
                            $dataGroupByName[$resetIndex]['row'] = $_data;
                            unset($dataGroupByName['']['rows'][$index]);
                        }
                        $orderNum = $_data['ORDER_NUM'] + 1;
                    }
                }
            }

            $groupCount = self::resolveHtmlTableMultiLevelMergeHeader($dataGroupByName);

            $resultMergeData = $workedGroupCount = $sideBarNameArr = [];        
            $indexMerge = $indexMergeEmp = $levelIndex = $levelIndex2 = 0;
            $keyTemp = '';
            
            foreach ($data as $keyy => $roww) {
                foreach ($dataGroupByName as $key => $row) {
                    if ($row['row'][$fieldPath] == $roww[$fieldPath]) {
                        if ($key == $roww['SIDEBAR_NAME'] && !array_key_exists($key, $sideBarNameArr)) {
                            $sideBarNameArr[$key] = true;
                            foreach ($row['rows'] as $key2 => $row2) {
                                if ($key && $key != $keyTemp) {
                                    $earr = explode('^', $key);
                                    $cc = count($earr);
                                    $earrPrev = explode('^', $keyTemp);

                                    foreach ($earr as $eakey => $eaval) {
                                        $eaval = trim($eaval);
                                        if (trim(issetParam($earrPrev[$eakey])) != $eaval && !isset($workedGroupCount[$key][$eaval])) {
                                            $resultMergeData[$eakey][$indexMerge] = $row2;
                                            $resultMergeData[$eakey][$indexMerge]['MERGE_LABEL_NAME'] = $eaval;
                                            $resultMergeData[$eakey][$indexMerge]['ORDER_NUM'] = issetParam($row2['ORDER_NUM']);
                                            $resultMergeData[$eakey][$indexMerge]['_COLSPAN'] = count($row['rows']) + $groupCount[$key][$eaval];                    
                                            $levelIndex2 = $eakey;
                                            $levelIndex = $levelIndex2 + 1;
                                            if ($groupCount[$key][$eaval]) {
                                                $workedGroupCount[$key][$eaval] = $cc;
                                            }
                                        } 
                                    }
                                    $indexMerge++;
                                    $keyTemp = $key;
                                }           
                                //if ($row2[$fieldPath] == $roww[$fieldPath]) {
                                    if (!empty($key)) {
                                        $resultMergeData[$cc][$indexMerge] = $row2;
                                        if (isset($earr)) {
                                            $_rowspan = 0;
                                            $_rowspan = $groupCount['_maxSeperateCount'] - count($earr);                                
                                            if ($_rowspan > 0) {
                                                $resultMergeData[$cc][$indexMerge]['_ROWSPAN'] = $_rowspan + 1;
                                            }                          
                                        }
                                    } else {
                                        $resultMergeData[0][$indexMerge] = $row2;
                                        $resultMergeData[0][$indexMerge]['_ROWSPAN'] = $groupCount['_maxSeperateCount'] + 1;
                                    }
                                    $indexMerge++;
                                //}
                            }                     
                        } elseif (strpos($key, '♠') !== false && !array_key_exists($key, $sideBarNameArr)) {
                            $sideBarNameArr[$key] = true;
                            foreach ($row['rows'] as $key2 => $row2) {
                                $resultMergeData[0][$indexMerge] = $row2;
                                $resultMergeData[0][$indexMerge]['_ROWSPAN'] = $groupCount['_maxSeperateCount'] + 1;                        
                                $indexMerge++;
                            }
                        }
                    }
                }
            }

            $cache->set('dvUserConfigMergeCols2_'.$dataViewId.'_'.$sessionUserId, $resultMergeData, Mdwebservice::$expressionCacheTime);
            return $resultMergeData;
            
        } else {
            return $row;
        }
    }     
    
    public function resolveHtmlTableMultiLevelMergeHeader($dataGroupByName) {
        $splitCount = [];
        $maxSeperateCount = 0;
        $keyTemp = '';
        
        foreach ($dataGroupByName as $key => $row) {
            if ($key) {
                $earr = explode('^', $key);
                $cc = count($earr);
                
                foreach ($earr as $eakey => $eaval) {
                    $eaval = trim($eaval);
                    if ($keyTemp) {
                        $earrPrev = explode('^', $keyTemp);
                        if (trim(issetParam($earrPrev[$eakey])) == $eaval) {
                            $splitCount[$keyTemp][$eaval] = isset($splitCount[$keyTemp][$eaval]) ? $splitCount[$keyTemp][$eaval] + 1 : 0;
                        } else {
                            $splitCount[$key][$eaval] = isset($splitCount[$key][$eaval]) ? $splitCount[$key][$eaval] + 1 : 0;
                        }
                    } else {
                        $splitCount[$key][$eaval] = isset($splitCount[$key][$eaval]) ? $splitCount[$key][$eaval] + 1 : 0;
                    }
                }
                                
                if ($cc > $maxSeperateCount) {
                    $maxSeperateCount = $cc;
                }
            }
            $keyTemp = $key;
        }
        
        $splitCount['_maxSeperateCount'] = $maxSeperateCount;
        return $splitCount;
    }
    
    public function cacheDataViewConfigUserMergeHeaderColumnModel($dataViewId, $cacheData) {
        
        $sessionUserId = Ue::sessionUserKeyId();
        
        $cache = phpFastCache();
        $row = $cache->get('dvUserConfigMergeCols2_'.$dataViewId.'_'.$sessionUserId);

        if ($row == null) {

            $row = $this->db->GetRow("
                SELECT * 
                FROM CUSTOMER_META_USER_CONFIG 
                WHERE META_DATA_ID = ".$this->db->Param(0)." 
                    AND USER_ID = ".$this->db->Param(1), 
                array($dataViewId, $sessionUserId)
            );

            $cache->set('dvUserConfigMergeCols2_'.$dataViewId.'_'.$sessionUserId, $row, Mdwebservice::$expressionCacheTime);
        }

        return $row;
    }    
    
    public function getDataViewAggregateColumnsModel($dvId) {
        
        $data = $this->db->GetAll("
            SELECT 
                COLUMN_AGGREGATE, 
                FIELD_PATH 
            FROM META_GROUP_CONFIG 
            WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                AND COLUMN_AGGREGATE IS NOT NULL 
                AND COLUMN_NAME IS NOT NULL 
                AND IS_SELECT = 1 
                AND PARENT_ID IS NULL 
                AND DATA_TYPE <> 'group'", array($dvId));
        
        return $data;
    }
    
    public function draggableRowsToFilterModel() {
        
        $metaDataId = Input::numeric('metaDataId');
        $headerId = Input::numeric('headerId');
        $dtlIds = Input::post('dtlIds');
        $result = ['status' => 'error', 'message' => 'Invalid parameters!'];
        
        if ($metaDataId && $headerId && $dtlIds) {
            
            $configRow = self::getDataViewConfigRowModel($metaDataId);
            
            if ($processId = issetParam($configRow['DRAG_ROWS_RUN_PROCESS_ID'])) {
                
                $this->load->model('mdwebservice', 'middleware/models/');
                $bpRow = $this->model->getMethodIdByMetaDataModel($processId);
                
                if ($bpRow) {
                    $param = [
                        'sourceId' => $headerId, 
                        'targetIds' => $dtlIds 
                    ];
                    $result = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, $bpRow['META_DATA_CODE'], $param);

                    if ($result['status'] == 'success') {
                        $result = ['status' => 'success'];
                    } else {
                        $result = ['status' => 'error', 'message' => $this->ws->getResponseMessage($result)];
                    }
                }
            }
        }
        
        return $result;
    }
    
}
