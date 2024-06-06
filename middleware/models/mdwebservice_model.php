<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdwebservice_Model extends Model {

    private static $gfServiceAddress = GF_SERVICE_ADDRESS;

    public function __construct() {
        parent::__construct();
    }

    public function getMethodIdByMetaDataModel($metaDataId) {
            
        $cache = phpFastCache();

        $row = $cache->get('bpConfig_' . $metaDataId);

        if ($row == null) {
            
            $metaDataIdPh = $this->db->Param(0);
            $bindVars = array($this->db->addQ($metaDataId));
        
            $row = $this->db->GetRow("
                SELECT 
                    MD.META_DATA_ID, 
                    MD.META_DATA_CODE, 
                    " . $this->db->IfNull('PL.PROCESS_NAME', 'MD.META_DATA_NAME') . " AS META_DATA_NAME, 
                    PL.INPUT_META_DATA_ID, 
                    PL.WS_URL, 
                    PL.CLASS_NAME, 
                    SL.SERVICE_LANGUAGE_CODE, 
                    IMD.META_DATA_CODE AS INPUT_META_DATA_CODE, 
                    PL.LABEL_WIDTH, 
                    PL.COLUMN_COUNT, 
                    PL.TAB_COLUMN_COUNT, 
                    PL.IS_TREEVIEW, 
                    PL.WINDOW_TYPE, 
                    PL.WINDOW_SIZE, 
                    PL.WINDOW_WIDTH, 
                    PL.WINDOW_HEIGHT,
                    PL.THEME, 
                    PL.SUB_TYPE, 
                    PL.ACTION_TYPE,
                    PL.ACTION_BTN, 
                    PL.IS_ADDON_PHOTO, 
                    PL.IS_ADDON_FILE, 
                    PL.IS_ADDON_COMMENT, 
                    PL.IS_ADDON_COMMENT_TYPE, 
                    PL.IS_ADDON_LOG, 
                    PL.IS_ADDON_RELATION, 
                    PL.IS_ADDON_WFM_LOG, 
                    PL.IS_ADDON_WFM_LOG_TYPE, 
                    PL.IS_BPMN_TOOL, 
                    PL.IS_ADDON_MV_RELATION, 
                    PL.REF_META_GROUP_ID, 
                    PL.SYSTEM_META_GROUP_ID, 
                    PL.THEME_CODE,
                    PL.SKIN,
                    PL.GETDATA_PROCESS_ID,  
                    DFG.META_DATA_CODE AS GETDATA_PROCESS_CODE, 
                    LOWER(PL.RUN_MODE) AS RUN_MODE, 
                    PL.HELP_CONTENT_ID, 
                    PL.IS_SHOW_PREVNEXT, 
                    PL.IS_UNLIMITED, 
                    PL.IS_WIDGET, 
                    (
                        SELECT
                            COUNT(TEMPLATE_META_DATA_ID)
                        FROM META_PROCESS_TEMPLATE     
                        WHERE PROCESS_LINK_ID = $metaDataIdPh 
                    ) AS COUNT_PROCESS_TEMPLATE, 
                    (
                        SELECT 
                            COUNT(ID)  
                        FROM META_BUSINESS_PROCESS_TEMPLATE 
                        WHERE META_DATA_ID = $metaDataIdPh AND IS_ACTIVE = 1 
                    ) AS COUNT_TEMPLATE,  
                    (
                        SELECT 
                            COUNT(MPCM.ID) 
                        FROM META_PROCESS_CONTENT_MAP MPCM
                            INNER JOIN META_PROCESS_CONTENT MPC ON MPCM.CONTENT_ID = MPC.CONTENT_ID
                        WHERE MPCM.MAIN_META_DATA_ID = $metaDataIdPh 
                    ) AS COUNT_BANNER,
                    (
                        SELECT 
                            COUNT(ID)
                        FROM META_CALENDAR_LINK
                        WHERE LINK_META_DATA_ID = $metaDataIdPh
                    ) AS COUNT_CALENDAR_LINK, 
                    (
                        SELECT
                            COUNT(1) 
                        FROM CUSTOMER_USE_CHILD T0 
                            INNER JOIN KPI_INDICATOR T1 ON T1.ID = T0.INDICATOR_ID 
                        WHERE T0.SRC_META_DATA_ID = $metaDataIdPh 
                            AND T0.IS_USE = 1 
                    ) AS IS_MV_ADDON_INFO, 
                    PL.WORKIN_TYPE, 
                    MW.WS_SERVER_NAME, 
                    BPT.HTML_FILE_PATH AS HTML_TEMPLATE_FILE, 
                    BL.LAYOUT_CODE, 
                    BL.OTHER_ATTR AS LAYOUT_OTHER_ATTR, 
                    PL.IS_TOOLS_BTN, 
                    PL.COMMENT_STRUCTURE_ID, 
                    RMD.META_DATA_CODE AS PREVIEW_REPORT_TEMPLATE_CODE, 
                    RMD.META_DATA_ID AS PREVIEW_REPORT_TEMPLATE_ID 
                FROM META_BUSINESS_PROCESS_LINK PL 
                    INNER JOIN META_DATA MD ON MD.META_DATA_ID = PL.META_DATA_ID 
                    LEFT JOIN WEB_SERVICE_LANGUAGE SL ON SL.SERVICE_LANGUAGE_ID = PL.SERVICE_LANGUAGE_ID 
                    LEFT JOIN META_DATA IMD ON IMD.META_DATA_ID = PL.INPUT_META_DATA_ID 
                    LEFT JOIN META_DATA DFG ON DFG.META_DATA_ID = PL.GETDATA_PROCESS_ID 
                    LEFT JOIN CUSTOMER_META_WS MW ON MW.SRC_META_DATA_ID = PL.META_DATA_ID 
                        AND MW.TRG_META_DATA_ID IS NULL 
                        AND MW.WS_URL IS NULL 
                        AND MW.WS_SERVER_NAME IS NOT NULL 
                    LEFT JOIN META_BUSINESS_PROCESS_TEMPLATE BPT ON BPT.META_DATA_ID = PL.META_DATA_ID 
                        AND BPT.IS_ACTIVE = 1 
                        AND BPT.IS_DEFAULT = 1 
                    LEFT JOIN (
                        SELECT 
                            BL.LAYOUT_CODE, 
                            BL.META_DATA_ID, 
                            BL.OTHER_ATTR 
                        FROM META_BP_LAYOUT_HDR BL 
                            INNER JOIN CUSTOMER_BP_LAYOUT CBL ON CBL.LAYOUT_ID = BL.ID 
                        WHERE BL.META_DATA_ID = $metaDataIdPh 
                            AND CBL.IS_DEFAULT = 1 
                    ) BL ON BL.META_DATA_ID = PL.META_DATA_ID 
                    LEFT JOIN CUSTOMER_TEMPLATE_MAP CTM ON CTM.META_DATA_ID = PL.META_DATA_ID 
                    LEFT JOIN META_REPORT_TEMPLATE_LINK MRTL ON MRTL.META_DATA_ID = CTM.TEMPLATE_ID 
                    LEFT JOIN META_DATA RMD ON RMD.META_DATA_ID = MRTL.META_DATA_ID 
                WHERE PL.META_DATA_ID = $metaDataIdPh", $bindVars);    

            $cache->set('bpConfig_' . $metaDataId, $row, Mdwebservice::$expressionCacheTime);
        }
        
        if (Input::post('bpTemplateId')) {
            
            $bpTemplateIdPh = $this->db->Param(0);
            $tempBindVars = array($this->db->addQ(Input::post('bpTemplateId')));
            
            $row['HTML_TEMPLATE_FILE'] = $this->db->GetOne("SELECT HTML_FILE_PATH FROM META_BUSINESS_PROCESS_TEMPLATE WHERE ID = $bpTemplateIdPh AND IS_ACTIVE = 1 AND IS_DEFAULT = 1", $tempBindVars);
        } 
        
        if (Input::post('processActionType') == 'log') {
            
            if (Mdwebservice::$bpActionType != 'removedlog') {
                Mdwebservice::$isLogViewMode = true;
            }
            
            $row['ACTION_TYPE'] = 'view';
            $row['IS_ADDON_PHOTO'] = null;
            $row['IS_ADDON_FILE'] = null;
            $row['IS_ADDON_COMMENT'] = null;
            $row['IS_ADDON_COMMENT_TYPE'] = null;
            $row['IS_ADDON_LOG'] = null;
            $row['IS_ADDON_RELATION'] = null;
            $row['IS_ADDON_WFM_LOG'] = null;
            $row['IS_ADDON_WFM_LOG_TYPE'] = null;
            $row['IS_ADDON_MV_RELATION'] = null;
        }
        
        return $row;
    }

    public function getMethodExpressionModel($metaDataId) {
        
        $metaDataIdPh = $this->db->Param(0);
        $bindVars = array($this->db->addQ($metaDataId));
            
        $row = $this->db->GetRow("
            SELECT 
                ED.EVENT_EXPRESSION_STRING, 
                ED.LOAD_EXPRESSION_STRING, 
                ED.VAR_FNC_EXPRESSION_STRING, 
                ED.SAVE_EXPRESSION_STRING, 
                PL.IS_SAVE_VIEW_LOG 
            FROM META_BUSINESS_PROCESS_LINK PL 
                INNER JOIN META_BP_EXPRESSION_DTL ED ON ED.BP_LINK_ID = PL.ID 
                INNER JOIN CUSTOMER_BP_EXP_CONFIG EX ON EX.EXP_DTL_ID = ED.ID 
            WHERE PL.META_DATA_ID = $metaDataIdPh 
                AND EX.IS_DEFAULT = 1", $bindVars);

        if (!$row) {
            $row = $this->db->GetRow("
                SELECT 
                    LOAD_EXPRESSION_STRING,
                    EVENT_EXPRESSION_STRING, 
                    VAR_FNC_EXPRESSION_STRING, 
                    SAVE_EXPRESSION_STRING, 
                    IS_SAVE_VIEW_LOG 
                FROM META_BUSINESS_PROCESS_LINK  
                WHERE META_DATA_ID = $metaDataIdPh", $bindVars);
        }

        if (Mdexpression::$precisionScalePath) {
            
            $eventSetScale = $withoutEventSetScale = $varFncSetScale = '';
            
            foreach (Mdexpression::$precisionScalePath as $precisionScaleSetPath => $precisionScaleGetPath) {
                
                $getGroupPathArr = explode('.', $precisionScaleSetPath);
                $getGroupPath = $getGroupPathArr[0];
                
                $eventSetScale .= '['.$precisionScaleGetPath.'].change(){' . "\n";
                    $eventSetScale .= 'setFieldPrecisionScale(\''.$precisionScaleSetPath.'\', \''.$precisionScaleGetPath.'\'); ' . "\n";
                $eventSetScale .= '};' . "\n";
                
                $withoutEventSetScale .= 'if (groupPath == \''.$getGroupPath.'\') {' . "\n";
                    $withoutEventSetScale .= 'setFieldPrecisionScale(\''.$precisionScaleSetPath.'\', \''.$precisionScaleGetPath.'\'); ' . "\n";
                $withoutEventSetScale .= '}' . "\n";
                
                $varFncSetScale .= 'function pfSetPrecisionScaleFromBp(element){' . "\n";
                    $varFncSetScale .= 'setFieldPrecisionScale(\''.$precisionScaleSetPath.'\', \''.$precisionScaleGetPath.'\'); ' . "\n";
                $varFncSetScale .= '}' . "\n";
                $varFncSetScale .= 'repeatFunction(\''.$getGroupPath.'\', \'pfSetPrecisionScaleFromBp\'); ' . "\n";
            }
            
            $row['EVENT_EXPRESSION_STRING'] = $eventSetScale . $row['EVENT_EXPRESSION_STRING'];
            $row['LOAD_EXPRESSION_STRING'] = $withoutEventSetScale . $row['LOAD_EXPRESSION_STRING'];
            $row['VAR_FNC_EXPRESSION_STRING'] = $varFncSetScale . $row['VAR_FNC_EXPRESSION_STRING'];
        }
        
        if ($row['IS_SAVE_VIEW_LOG'] == '1') {
            
            $row['EVENT_EXPRESSION_STRING'] = $row['EVENT_EXPRESSION_STRING'] . "\n" . Mdexpression::viewLogExpression($metaDataId);
            $row['VAR_FNC_EXPRESSION_STRING'] .= "\n" . Mdexpression::viewLogBeforeUnloadExpression($metaDataId);
        }
        
        return $row;
    }

    public function buildTreeParamModel($uniqId, $bpMetaDataId, $metaDataName, $paramName, $recordType, $parentId, $fillParamData, $subTree = '', $arg = array(), $isButton = 1, $colCount, $rowIndex = 0) {
        
        $self = new self();
        
        if ($subTree !== '') {
            return $self->buildTreeParamTwoModel($uniqId, $bpMetaDataId, $paramName, $recordType, $parentId, $fillParamData, $subTree, $isButton, $colCount, $rowIndex, $arg);
        }
        
        $table = '';
        
        if ($recordType == 'rows') {

            if (issetParam($arg['isTab']) == 'tab') {
                $table .= $self->buildTreeParamTwoModel($uniqId, $bpMetaDataId, $paramName, 'rows', $parentId, $fillParamData, '', '', $rowIndex, $arg);
            } else {
                if ($isButton == '1') {
                    $table = '<a href="javascript:;" onclick="paramTreePopup(this, ' . $uniqId . ', \'div#bp-window-' . $bpMetaDataId . ':visible\');" class="hide-tbl btn btn-sm purple-plum bp-btn-subdtl" style="width:35px" title="' . Lang::line($metaDataName) . '" data-b-path="'.$paramName.'">...</a>';
                    $table .= '<input type="hidden" data-path="' . $paramName . '" value="' . $paramName . '">';
                    $table .= '<div class="param-tree-container hide">';
                    $table .= $self->buildTreeParamTwoModel($uniqId, $bpMetaDataId, $paramName, 'rows', $parentId, $fillParamData, '', '', $rowIndex, $arg);
                    $table .= '</div>';
                } else {
                    $table .= '<div class="param-tree-container">';
                    $table .= $self->buildTreeParamTwoModel($uniqId, $bpMetaDataId, $paramName, 'rows', $parentId, $fillParamData, '', '', $rowIndex, $arg);
                    $table .= '</div>';
                }
            }
            
        } elseif ($recordType == 'row') {
            
            if (issetParam($arg['isTab']) == 'tab') {
                $table .= $self->buildTreeParamTwoModel($uniqId, $bpMetaDataId, $paramName, 'row', $parentId, $fillParamData, '', $colCount, $rowIndex, $arg);
            } else {
                if (issetParam($arg['parentRecordType']) == 'rows') {
                    if ($isButton == '1') {
                        $table = '<a href="javascript:;" onclick="paramTreePopup(this, ' . $uniqId . ', \'div#bp-window-' . $bpMetaDataId . ':visible\');" class="hide-tbl btn btn-sm purple-plum bp-btn-subdtl" style="width:35px" title="' . Lang::line($metaDataName) . '" data-b-path="'.$paramName.'">...</a> ';
                        $table .= '<input type="hidden" data-path="' . $paramName . '" value="' . $paramName . '">';
                        $table .= '<div class="param-tree-container hide">';
                        $table .= $self->buildTreeParamTwoModel($uniqId, $bpMetaDataId, $paramName, 'row', $parentId, $fillParamData, '', $colCount, $rowIndex, $arg);
                        $table .= '</div>';
                    } else {
                        $table .= '<div class="param-tree-container">';
                        $table .= $self->buildTreeParamTwoModel($uniqId, $bpMetaDataId, $paramName, 'row', $parentId, $fillParamData, '', $colCount, $rowIndex, $arg);
                        $table .= '</div>';
                    }
                } else {
                    $table = !empty($metaDataName) ? '<p class="meta_description"><i class="fa fa-info-circle"></i> '.Lang::line($metaDataName).'</p>' : '';
                    
                    if (isset($arg['htmlcontent'])) {
                        $table = $self->buildTreeParamTwoModel($uniqId, $bpMetaDataId, $paramName, 'row', $parentId, $fillParamData, '', $colCount, $rowIndex, $arg);
                    } else {
                        $table .= $self->buildTreeParamTwoModel($uniqId, $bpMetaDataId, $paramName, 'row', $parentId, $fillParamData, '', $colCount, $rowIndex, $arg);
                    }
                }
            }
        }

        return $table;
    }

    public function buildTreeParamViewModel($bpMetaDataId, $metaDataName, $paramName, $recordType, $parentId, $fillParamData, $subTree = '', $arg = array(), $isButton = 1, $colCount, $rowIndex = 0) {
        
        if ($subTree != '') {
            return self::buildTreeParamTwoViewModel($bpMetaDataId, $paramName, $recordType, $parentId, $fillParamData, $subTree, $isButton, $colCount, $rowIndex, $arg);
        }
        
        $table = '';

        if ($recordType == 'rows') {
            if (issetParam($arg['isTab']) == 'tab') {
                $table .= self::buildTreeParamTwoViewModel($bpMetaDataId, $paramName, 'rows', $parentId, $fillParamData, '', '', $rowIndex, $arg);
            } else {
                if ($isButton == '1') {
                    $table = '<a href="javascript:;" onclick="paramTreePopup(this, ' . $bpMetaDataId . ', \'div#bp-window-' . $bpMetaDataId . ':visible\');" class="hide-tbl btn btn-sm purple-plum bp-btn-subdtl" style="width:35px" title="' . Lang::line($metaDataName) . '" data-b-path="'.$paramName.'">';
                    $table .= '...';
                    $table .= '</a>';
                    $table .= '<input type="hidden" data-path="' . $paramName . '" value="' . $paramName . '">';
                    $table .= '<div class="param-tree-container hide">';
                    $table .= self::buildTreeParamTwoViewModel($bpMetaDataId, $paramName, 'rows', $parentId, $fillParamData, '', '', $rowIndex, $arg);
                    $table .= '</div>';
                } else {
                    $table .= '<div class="param-tree-container">';
                    $table .= self::buildTreeParamTwoViewModel($bpMetaDataId, $paramName, 'rows', $parentId, $fillParamData, '', '', $rowIndex, $arg);
                    $table .= '</div>';
                }
            }
        } elseif ($recordType == 'row') {
            if (issetParam($arg['isTab']) == 'tab') {
                $table .= self::buildTreeParamTwoViewModel($bpMetaDataId, $paramName, 'row', $parentId, $fillParamData, '', $colCount, $rowIndex, $arg);
            } else {
                if (issetParam($arg['parentRecordType']) == 'rows') {
                    if ($isButton == '1') {
                        $table = '<a href="javascript:;" onclick="paramTreePopup(this, ' . $bpMetaDataId . ', \'div#bp-window-' . $bpMetaDataId . ':visible\');" class="hide-tbl btn btn-sm purple-plum bp-btn-subdtl" style="width:35px" title="' . Lang::line($metaDataName) . '" data-b-path="'.$paramName.'">';
                        $table .= '...';
                        $table .= '</a> ';
                        $table .= '<input type="hidden" data-path="' . $paramName . '" value="' . $paramName . '">';
                        $table .= '<div class="param-tree-container hide">';
                        $table .= self::buildTreeParamTwoViewModel($bpMetaDataId, $paramName, 'row', $parentId, $fillParamData, '', $colCount, $rowIndex, $arg);
                        $table .= '</div>';
                    } else {
                        $table .= '<div class="param-tree-container">';
                        $table .= self::buildTreeParamTwoViewModel($bpMetaDataId, $paramName, 'row', $parentId, $fillParamData, '', $colCount, $rowIndex, $arg);
                        $table .= '</div>';
                    }
                } else {
                    //$table = '<p class="meta_description"><i class="fa fa-info-circle"></i> ' . Lang::line($metaDataName) . '</p>';
                    $arg['metadataName'] = Lang::line($metaDataName);
                    $table = self::buildTreeParamTwoViewModel($bpMetaDataId, $paramName, 'row', $parentId, $fillParamData, '', $colCount, $rowIndex, $arg);
                }
            }
        }

        return $table;
    }
    
    public function getProcessParamsData($bpMetaDataId, $parentId = null, $where = null) {
        
        $bpMetaDataIdPh = $this->db->Param(0);
        $bindVars = [$this->db->addQ($bpMetaDataId)];
        
        $data = $this->db->GetAll("
            SELECT 
                INP.* 
            FROM 
            (
                SELECT 
                    (
                        SELECT 
                            COUNT(ID) AS COUNT_PARAM 
                        FROM META_GROUP_PARAM_CONFIG 
                        WHERE MAIN_PROCESS_META_DATA_ID = $bpMetaDataIdPh 
                            AND LOWER(FIELD_PATH) = LOWER(PAL.PARAM_REAL_PATH) 
                            AND (IS_GROUP = 0 OR IS_GROUP IS NULL) 
                    ) AS GROUP_PARAM_CONFIG_TOTAL, 
                    (
                        SELECT 
                            ".$this->db->listAgg('PARAM_PATH', '|', 'PARAM_PATH')." 
                        FROM META_GROUP_PARAM_CONFIG 
                        WHERE MAIN_PROCESS_META_DATA_ID = $bpMetaDataIdPh 
                            AND LOOKUP_META_DATA_ID IS NOT NULL 
                            AND (IS_GROUP = 0 OR IS_GROUP IS NULL) 
                            AND LOWER(FIELD_PATH) = LOWER(PAL.PARAM_REAL_PATH) 
                    ) AS GROUP_CONFIG_PARAM_PATH,
                    (
                        SELECT 
                            ".$this->db->listAgg('PARAM_META_DATA_CODE', '|', 'PARAM_PATH')."  
                        FROM META_GROUP_PARAM_CONFIG  
                        WHERE MAIN_PROCESS_META_DATA_ID = $bpMetaDataIdPh  
                            AND LOOKUP_META_DATA_ID IS NOT NULL 
                            AND (IS_GROUP = 0 OR IS_GROUP IS NULL) 
                            AND LOWER(FIELD_PATH) = LOWER(PAL.PARAM_REAL_PATH)    
                    ) AS GROUP_CONFIG_LOOKUP_PATH, 
                    (
                        SELECT 
                            ".$this->db->listAgg('FIELD_PATH', '|', 'FIELD_PATH')."  
                        FROM META_GROUP_PARAM_CONFIG 
                        WHERE MAIN_PROCESS_META_DATA_ID = $bpMetaDataIdPh 
                            AND LOOKUP_META_DATA_ID IS NOT NULL 
                            AND (IS_GROUP = 0 OR IS_GROUP IS NULL OR IS_KEY_LOOKUP = 1) 
                            AND LOWER(PARAM_PATH) = LOWER(PAL.PARAM_REAL_PATH) 
                    ) AS GROUP_CONFIG_FIELD_PATH, 
                    (
                        SELECT 
                            ".$this->db->listAgg('FIELD_PATH', '|', 'FIELD_PATH')."  
                        FROM META_GROUP_PARAM_CONFIG 
                        WHERE MAIN_PROCESS_META_DATA_ID = $bpMetaDataIdPh  
                            AND LOOKUP_META_DATA_ID IS NOT NULL 
                            AND IS_GROUP = 1  
                            AND (IS_KEY_LOOKUP = 0 OR IS_KEY_LOOKUP IS NULL) 
                            AND LOWER(PARAM_PATH) = LOWER(PAL.PARAM_REAL_PATH) 
                    ) AS GROUP_CONFIG_GROUP_PATH, 
                    (
                        SELECT 
                            ".$this->db->listAgg('PARAM_PATH', '|', 'PARAM_PATH')."  
                        FROM META_GROUP_PARAM_CONFIG 
                        WHERE MAIN_PROCESS_META_DATA_ID = $bpMetaDataIdPh  
                            AND LOOKUP_META_DATA_ID IS NOT NULL 
                            AND IS_GROUP = 1
                            AND IS_KEY_LOOKUP = 1
                            AND LOWER(FIELD_PATH) = LOWER(PAL.PARAM_REAL_PATH) 
                    ) AS GROUP_CONFIG_PARAM_PATH_GROUP, 
                    (
                        SELECT 
                            ".$this->db->listAgg('PARAM_META_DATA_CODE', '|', 'PARAM_PATH')."  
                        FROM META_GROUP_PARAM_CONFIG 
                        WHERE MAIN_PROCESS_META_DATA_ID = $bpMetaDataIdPh  
                            AND LOOKUP_META_DATA_ID IS NOT NULL 
                            AND IS_GROUP = 1
                            AND IS_KEY_LOOKUP = 1
                            AND LOWER(FIELD_PATH) = LOWER(PAL.PARAM_REAL_PATH) 
                    ) AS GROUP_CONFIG_FIELD_PATH_GROUP, 
                    (
                        SELECT 
                            COUNT(ID) 
                        FROM META_PROCESS_LOOKUP_MAP 
                        WHERE PROCESS_META_DATA_ID = $bpMetaDataIdPh 
                            AND LOWER(FIELD_PATH) = LOWER(PAL.PARAM_REAL_PATH) 
                            AND (IS_KEY_LOOKUP = 0 OR IS_KEY_LOOKUP IS NULL)
                    ) AS IS_MULTI_ADD_ROW, 
                    (
                        SELECT 
                            COUNT(ID) 
                        FROM META_PROCESS_LOOKUP_MAP 
                        WHERE PROCESS_META_DATA_ID = $bpMetaDataIdPh 
                            AND LOWER(FIELD_PATH) = LOWER(PAL.PARAM_REAL_PATH) 
                            AND IS_KEY_LOOKUP = 1
                    ) AS IS_MULTI_ADD_ROW_KEY, 
                    (
                        SELECT 
                            COUNT(ID) 
                        FROM META_DATA_SEQUENCE_CONFIG 
                        WHERE META_DATA_ID = $bpMetaDataIdPh 
                            AND LOWER(PARAM_NAME) = LOWER(PAL.PARAM_REAL_PATH)
                    ) AS IS_AUTO_NUMBER, 
                    '' AS DESCRIPTION,
                    null AS ATTRIBUTE_ID_COLUMN, 
                    null AS ATTRIBUTE_CODE_COLUMN, 
                    null AS ATTRIBUTE_NAME_COLUMN, 
                    PAL.LOOKUP_META_DATA_ID, 
                    PAL.LOOKUP_KEY_META_DATA_ID, 
                    PAL.ID,
                    PAL.PARENT_ID,
                    PAL.PARAM_NAME AS META_DATA_CODE, 
                    LOWER(PAL.PARAM_NAME) AS LOWER_PARAM_NAME, 
                    PAL.IS_FIRST_ROW, 
                    PAL.RECORD_TYPE, 
                    PAL.MIN_VALUE, 
                    PAL.MAX_VALUE, 
                    PAL.DISPLAY_FIELD, 
                    PAL.VALUE_FIELD, 
                    PAL.PARAM_REAL_PATH, 
                    LOWER(PAL.PARAM_REAL_PATH) AS LOWER_PARAM_REAL_PATH, 
                    REPLACE(PAL.PARAM_REAL_PATH, '.', '') AS NODOT_PARAM_REAL_PATH, 
                    LOWER(PAL.DATA_TYPE) AS META_TYPE_CODE, 
                    LOWER(PAL.COLUMN_AGGREGATE) AS COLUMN_AGGREGATE,
                    PAL.COLUMN_WIDTH, 
                    PAL.SEPARATOR_TYPE,
                    " . $this->db->IfNull('PAL.IS_BUTTON', '1') . " AS IS_BUTTON, 
                    " . $this->db->IfNull('PAL.COLUMN_COUNT', '1') . " AS COLUMN_COUNT,
                    PAL.IS_SHOW_ADD,
                    PAL.IS_SHOW_DELETE,
                    PAL.IS_SHOW_MULTIPLE,
                    PAL.IS_REFRESH, 
                    PAL.FRACTION_RANGE, 
                    PAL.IS_SAVE, 
                    PAL.FEATURE_NUM, 
                    PAL.GROUPING_NAME, 
                    PAL.FILE_EXTENSION, 
                    MFP.PATTERN_TEXT, 
                    MFP.PATTERN_NAME, 
                    MFP.GLOBE_MESSAGE, 
                    MFP.IS_MASK, 
                    PAL.THEME_POSITION_NO, 
                    PAL.DTL_THEME, 
                    MW.CODE AS WIDGET_CODE, 
                    PAL.PAGING_CONFIG, 
                    PAL.IS_USER_CONFIG, 
                    PAL.IS_EXCEL_EXPORT, 
                    PAL.IS_EXCEL_IMPORT, 
                    PAL.DETAIL_MODIFY_MODE, 
                    PAL.TAB_INDEX, 
                    PAL.ICON_NAME, 
                    PAL.RENDER_TYPE, 
                    
                    ".$this->db->IfNull('CF.LOOKUP_TYPE', 'PAL.LOOKUP_TYPE')." AS LOOKUP_TYPE,
                    ".$this->db->IfNull('CF.CHOOSE_TYPE', 'PAL.CHOOSE_TYPE')." AS CHOOSE_TYPE, 
                    ".$this->db->IfNull('CF.IS_SHOW', 'PAL.IS_SHOW')." AS IS_SHOW, 
                    ".$this->db->IfNull('CF.IS_REQUIRED', 'PAL.IS_REQUIRED')." AS IS_REQUIRED, 
                    ".$this->db->IfNull('CF.LABEL_NAME', $this->db->IfNull('BLP.LABEL_NAME', 'PAL.LABEL_NAME'))." AS META_DATA_NAME, 
                    ".$this->db->IfNull('CF.DEFAULT_VALUE', 'PAL.DEFAULT_VALUE')." AS DEFAULT_VALUE, 
                    ".$this->db->IfNull('CF.DISPLAY_ORDER', 'PAL.ORDER_NUMBER')." AS ORDER_NUMBER, 
                    ".$this->db->IfNull('PAL.PLACEHOLDER_NAME', $this->db->IfNull('CF.LABEL_NAME', $this->db->IfNull('BLP.LABEL_NAME', 'PAL.LABEL_NAME')))." AS PLACEHOLDER_NAME, 
                    
                    CASE 
                        WHEN ".$this->db->IfNull('CF.IS_SHOW', 'PAL.IS_SHOW')." = 1 
                        THEN PAL.SIDEBAR_NAME 
                    ELSE NULL END AS SIDEBAR_NAME, 
                        
                    CASE 
                        WHEN CF.TAB_NAME = 'pf_no_tab' 
                        THEN NULL 
                    ELSE ".$this->db->IfNull('CF.TAB_NAME', 'PAL.TAB_NAME')." END AS TAB_NAME, 
                    
                    CASE 
                        WHEN GC.IS_TRANSLATE = 1 AND GC.COLUMN_NAME IS NOT NULL 
                        THEN 1 
                    ELSE 0 END AS IS_TRANSLATE, 
                    UPPER(GC.COLUMN_NAME) AS COLUMN_NAME, 
                    
                    PAL.MORE_META_DATA_ID, 
                    PAL.DTL_BUTTON_NAME, 
                    PAL.IS_THUMBNAIL, 
                    BLP.SECTION_CODE AS LAYOUT_SECTION_CODE, 
                    BLP.DISPLAY_ORDER AS LAYOUT_DISPLAY_ORDER, 
                    BLP.TAB_NAME AS LAYOUT_TAB_NAME, 
                    BLP.OTHER_ATTR AS LAYOUT_OTHER_ATTR, 
                    CASE 
                        WHEN BLP.OTHER_ATTR IS NOT NULL 
                        THEN 1 
                    ELSE 0 END AS IS_LAYOUT_OTHER_ATTR, 
                    PAL.JSON_CONFIG, 
                    CASE 
                        WHEN PAL.JSON_CONFIG IS NOT NULL 
                        THEN 1 
                    ELSE 0 END AS IS_JSON_CONFIG, 
                    BLP.WIDGET_CODE AS LAYOUT_WIDGET_CODE, 
                    GC.EXPRESSION_STRING, 
                    PAL.IS_PATH_DISPLAY_ORDER, 
                    PAL.WIZARD_STEP 
                FROM META_PROCESS_PARAM_ATTR_LINK PAL 
                    LEFT JOIN META_FIELD_PATTERN MFP ON MFP.PATTERN_ID = PAL.PATTERN_ID 
                    LEFT JOIN CUSTOMER_DV_FIELD CF ON CF.META_DATA_ID = PAL.PROCESS_META_DATA_ID 
                        AND LOWER(CF.FIELD_PATH) = LOWER(PAL.PARAM_REAL_PATH) 
                    LEFT JOIN (
                        SELECT 
                            BLP.SECTION_CODE, 
                            BLP.PARAM_REAL_PATH, 
                            BLP.DISPLAY_ORDER, 
                            BLP.TAB_NAME, 
                            BLP.LABEL_NAME, 
                            BLP.OTHER_ATTR, 
                            BL.META_DATA_ID, 
                            MW.CODE AS WIDGET_CODE 
                        FROM META_BP_LAYOUT_HDR BL 
                            INNER JOIN CUSTOMER_BP_LAYOUT CBL ON CBL.LAYOUT_ID = BL.ID 
                            INNER JOIN META_BP_LAYOUT_PARAM BLP ON BLP.HEADER_ID = CBL.LAYOUT_ID 
                            LEFT JOIN META_WIDGET MW ON MW.ID = BLP.WIDGET_ID 
                        WHERE BL.META_DATA_ID = $bpMetaDataIdPh 
                            AND CBL.IS_DEFAULT = 1 
                    ) BLP ON BLP.META_DATA_ID = PAL.PROCESS_META_DATA_ID 
                        AND LOWER(BLP.PARAM_REAL_PATH) = LOWER(PAL.PARAM_REAL_PATH) 
                    INNER JOIN META_BUSINESS_PROCESS_LINK BP ON BP.META_DATA_ID = PAL.PROCESS_META_DATA_ID 
                    LEFT JOIN META_GROUP_CONFIG GC ON GC.MAIN_META_DATA_ID = BP.SYSTEM_META_GROUP_ID 
                        AND LOWER(GC.FIELD_PATH) = LOWER(PAL.PARAM_REAL_PATH) 
                    LEFT JOIN META_WIDGET MW ON MW.ID = PAL.DTL_THEME 
                WHERE PAL.PROCESS_META_DATA_ID = $bpMetaDataIdPh 
                    AND PAL.IS_INPUT = 1 
                    ".($parentId ? "AND PAL.PARENT_ID = $parentId" : '')." 
                    $where 
            ) INP 
            ORDER BY 
                (CASE WHEN (INP.RECORD_TYPE IS NULL OR INP.IS_PATH_DISPLAY_ORDER = 1) THEN 0 ELSE 1 END) ASC, 
                INP.IS_SHOW DESC, 
                INP.LAYOUT_DISPLAY_ORDER ASC, 
                INP.ORDER_NUMBER ASC", 
            $bindVars);
        
        if ($data) {
            
            if (Lang::isUseMultiLang()) {
                
                $isTranslateProcess = helperSumFieldBp($data, 'IS_TRANSLATE');

                if ($isTranslateProcess) {

                    $path = 'pfTranslationValue';
                    $lowerPath = strtolower($path);
                    $row = $data[0]; 
                    $realPath = $row['PARAM_REAL_PATH'];

                    if (strpos($realPath, '.') !== false) {
                        $realPath = substr($row['PARAM_REAL_PATH'], 0, strrpos($row['PARAM_REAL_PATH'], '.'));
                        $realPath = $realPath.'.'.$path;
                    } else {
                        $realPath = $path;
                    }

                    foreach ($data as $k => $dataRow) {
                        if ($dataRow['LOWER_PARAM_NAME'] == $lowerPath) {
                            unset($data[$k]);
                            break;
                        }
                    }

                    $data[] = [
                        'ID' => '',
                        'PARENT_ID' => '',
                        'LAYOUT_SECTION_CODE' => '', 
                        'DEFAULT_VALUE' => '', 
                        'RECORD_TYPE' => '', 
                        'IS_SHOW_ADD' => '', 
                        'IS_SHOW_DELETE' => '', 
                        'IS_SHOW_MULTIPLE' => '', 
                        'IS_FIRST_ROW' => '', 
                        'META_DATA_NAME' => '', 
                        'IS_REQUIRED' => '', 
                        'COLUMN_AGGREGATE' => '', 
                        'SIDEBAR_NAME' => '', 
                        'LOOKUP_TYPE' => '', 
                        'COLUMN_WIDTH' => '', 

                        'IS_SHOW' => 0, 
                        'META_DATA_CODE' => $path, 
                        'LOWER_PARAM_NAME' => $lowerPath, 
                        'PARAM_REAL_PATH' => $realPath, 
                        'LOWER_PARAM_REAL_PATH' => strtolower($realPath), 
                        'NODOT_PARAM_REAL_PATH' => str_replace('.', '', $realPath), 
                        'META_TYPE_CODE' => 'pftranslationvalue', 
                        'GROUPING_NAME' => '', 
                        'FRACTION_RANGE' => '', 
                        'LAYOUT_OTHER_ATTR' => '', 
                        'JSON_CONFIG' => ''
                    ];
                }
            }
            
            $isLayoutOtherAttr = helperSumFieldBp($data, 'IS_LAYOUT_OTHER_ATTR');
            $isJsonConfig = helperSumFieldBp($data, 'IS_JSON_CONFIG');
            
            if ($isLayoutOtherAttr || $isJsonConfig) {
                
                $arr = $data; 
                $data = [];
                
                foreach ($arr as $row) {
                    
                    if ($row['LAYOUT_OTHER_ATTR']) {
                        $row['LAYOUT_OTHER_ATTR'] = @json_decode($row['LAYOUT_OTHER_ATTR'], true);
                    } else {
                        $row['LAYOUT_OTHER_ATTR'] = null;
                    }
                    
                    if ($row['JSON_CONFIG']) {
                        $row['JSON_CONFIG'] = @json_decode($row['JSON_CONFIG'], true);
                        
                        $fieldCriteria = self::fieldCriteria($row['JSON_CONFIG']);                        
                        if (!$fieldCriteria) {
                            $row['IS_SHOW'] = '0';
                            $row['IS_REQUIRED'] = '0';
                        }
                        
                        if ($precisionScaleGetPath = issetParam($row['JSON_CONFIG']['precisionScale'])) {
                            Mdexpression::$precisionScalePath[$row['PARAM_REAL_PATH']] = $precisionScaleGetPath;
                        }
                    } else {
                        $row['JSON_CONFIG'] = null;
                    }
                    
                    $data[] = $row;
                }
            }
        }

        return $data;
    }

    public function buildTreeParamTwoModel($uniqId, $bpMetaDataId, $paramName, $recordType, $parentId, $fillParamData, $subTree = '', $colCount, $rowIndex = 0, $funcArg = array()) {
        
        $cache = phpFastCache();

        $data = $cache->get('bpDetail_' . $bpMetaDataId.'_'.$parentId);

        if ($data == null) {
            if (!Mdwebservice::$isGroupRender) {
                $data = self::getProcessParamsData($bpMetaDataId, $parentId);
            } else {
                $data = self::getDVProcessParamsData($bpMetaDataId, $parentId);
            }
            $cache->set('bpDetail_' . $bpMetaDataId.'_'.$parentId, $data, Mdwebservice::$expressionCacheTime);
        }
        
        $table = '';
        $tableCell = '';
        $tableCellAfter = '';
        $isAddRowBtn = '';
        $isMultipleAddRowBtn = '';
        $gridBodySubTree = '';
        $gridBodyTreeWrap = '';
        $gridFoot = '';
        $gridFootBody = '';
        $isAggregate = false;
        $aggregateClass = '';
        $depthLevelRowArr = array();
        $gridTabContentHeader = '';
        $gridTabContentBody = '';
        $isTab = false;
        $gridClass = '';

        // <editor-fold defaultstate="collapsed" desc="Proccess ROW && ROWS">
        if ($recordType == 'rows') {
            $tableCell = '<td class="text-center middle stretchInput"><span>1</span><input type="hidden" name="param[' . $paramName . '.rowCount][0][]"/></td>';
            if ($subTree !== '') {
                $gridBodyTreeWrap = '<div class="hideTreeFolderMetaGroupClass hide" id="end group path">';
                $gridBodyTreeWrap .= '<p class="meta_description"><i class="fa fa-info-circle"></i> end group path</p>';
            }
            $table = '<table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-table-subdtl bprocess-theme1" data-table-path="'.$paramName.'" data-table-path-lower="'. Str::lower($paramName).'" data-row-id="'.$parentId.'">';
            $table .= '<thead>';
            $table .= '<tr>';
            $table .= '<th style="width:15px;">№</th>';
            $gridFootBody = '<td></td>';
            $ii = 0;

            foreach ($data as $rk => $row) {

                $foodAmount = '';
                $aggregateClass = '';

                if ($row['COLUMN_AGGREGATE'] != '') {
                    $isAggregate = true;
                    $foodAmount = '0.00';
                    $aggregateClass = 'aggregate-' . $row['COLUMN_AGGREGATE'];
                }
                $hideClass = '';

                if ($row['IS_SHOW'] != '1') {
                    $hideClass = " display-none";
                }
                
                if ($row['META_TYPE_CODE'] == 'group') {

                    $sub_arg = array(
                        'parentRecordType' => 'rows'
                    );
                    if ($row['RECORD_TYPE'] == 'row' && $row['IS_BUTTON'] != '1') {

                        $additionalHeader = '';
                        $additionalBody = '';
                        $additionalFooter = '';
                        $additionalRow = (new Mdwebservice())->renderAdditionalRowHeader($bpMetaDataId, $row['ID']);

                        foreach ($additionalRow as $key => $rowVal) {
                            $hideClassSub = '';
                            if ($rowVal['IS_SHOW'] != '1') {
                                $hideClassSub = " display-none";
                            }
                            $foodAmountSub = '';
                            $aggregateClassSub = '';
                            if ($rowVal['COLUMN_AGGREGATE'] != '') {
                                $foodAmountSub = '0.00';
                                $aggregateClassSub = 'aggregate-' . $rowVal['COLUMN_AGGREGATE'];
                            }
                            $additionalHeader .= '<th class="' . $hideClassSub . ' '.$rowVal['NODOT_PARAM_REAL_PATH'].'" data-row-path="' . $paramName . "." . $row['META_DATA_CODE'] . '" data-cell-path="' . $rowVal['PARAM_REAL_PATH'] . '" ' . ($rowVal['COLUMN_AGGREGATE'] != '' ? 'data-aggregate="' . $rowVal['COLUMN_AGGREGATE'] . '"' : '') . '>' . Lang::line($rowVal['META_DATA_NAME']) . '</th>';
                            $additionalBody .= '<td data-row-path="' . $paramName . "." . $row['META_DATA_CODE'] . '" data-cell-path="' . $rowVal['PARAM_REAL_PATH'] . '" class="stretchInput ' . $aggregateClassSub . ' ' . $hideClassSub . '">';
                            $additionalBody .= Mdwebservice::renderParamControl($bpMetaDataId, $rowVal, "param[" . $rowVal['PARAM_REAL_PATH'] . "][0][]", $paramName . "." . $rowVal['META_DATA_CODE'], null);
                            $additionalBody .= '</td>';
                            $additionalFooter .= '<td data-cell-path="' . $rowVal['PARAM_REAL_PATH'] . '" data-row-path="' . $paramName . "." . $row['META_DATA_CODE'] . '" class="text-right bigdecimalInit ' . $hideClassSub . '">' . $foodAmountSub . '</td>';
                        }
                        
                        $table = $table . $additionalHeader;
                        $tableCell .= $additionalBody;
                        $gridFootBody .= $additionalFooter;
                        
                    } else {
                        
                        ++$ii;
                        $isTab = true;
                        $sub_arg['isShowAdd'] = $row['IS_SHOW_ADD'];
                        $sub_arg['isShowDelete'] = $row['IS_SHOW_DELETE'];
                        $sub_arg['isShowMultiple'] = $row['IS_SHOW_MULTIPLE'];                   
                        $sub_arg['isFirstRow'] = $row['IS_FIRST_ROW'];                   
                        $sub_arg['isTab'] = 'tab';
                        
                        $gridTabActive = '';
                        if ($ii == 1)
                            $gridTabActive = ' active';

                        $gridTabContentHeader .= '<li class="nav-item ' . $hideClass . '" data-li-path="' . $paramName . '.' . $row['META_DATA_CODE'] . '">';
                        $gridTabContentHeader .= '<a href="#' . str_replace('.', '_', $paramName) . '_' . $row['META_DATA_CODE'] . '" class="nav-link ' . $gridTabActive . '" data-toggle="tab">' . Lang::line($row['META_DATA_NAME']) . '</a>';
                        $gridTabContentHeader .= '</li>';
                        $gridTabContentBody .= '<div class="tab-pane in' . $hideClass . $gridTabActive . '" id="' . str_replace('.', '_', $paramName) . '_' . $row['META_DATA_CODE'] . '" data-section-path="' . $paramName . '.' . $row['META_DATA_CODE'] . '">';
                        $gridTabContentBody .= self::buildTreeParamModel($uniqId, $bpMetaDataId, $row['META_DATA_NAME'], $paramName . '.' . $row['META_DATA_CODE'], $row['RECORD_TYPE'], $row['ID'], $fillParamData, '', $sub_arg, $row['IS_BUTTON'], $row['COLUMN_COUNT']);
                        $gridTabContentBody .= '</div>';
                    }
                } else {
                    $table .= '<th data-cell-path="' . $paramName . '.' . $row['META_DATA_CODE'] . '" class="' . $row['NODOT_PARAM_REAL_PATH'] . ' ' . $hideClass . '" ' . ($row['COLUMN_AGGREGATE'] != '' ? 'data-aggregate="' . $row['COLUMN_AGGREGATE'] . '"' : '') . '>' . Lang::line($row['META_DATA_NAME']) . '</th>';
                    $tableCell .= '<td data-cell-path="' . $paramName . '.' . $row['META_DATA_CODE'] . '" class="' . $row['NODOT_PARAM_REAL_PATH'] . ' middle stretchInput' . $hideClass . ' ' . $aggregateClass . '"> ';
                    $tableCell .= Mdwebservice::renderParamControl(
                        $bpMetaDataId, $row, "param[" . $paramName . '.' . $row['META_DATA_CODE'] . "][0][]", $paramName . '.' . $row['META_DATA_CODE'], array(), 'removeSelect2'
                    );
                    $tableCell .= '</td>';
                    $gridFootBody .= '<td data-cell-path="' . $paramName . '.' . $row['META_DATA_CODE'] . '" class="text-right bigdecimalInit ' . $hideClass . '">' . $foodAmount . '</td>';

                    $gridClass .= Mdwebservice::fieldDetailStyleClass($row, $row['NODOT_PARAM_REAL_PATH'], 'bp-window-' . $bpMetaDataId);                       
                }
            }

            $isAddRowHead = '';
            
            if (issetParam($funcArg['isShowDelete']) == '1') {
                $isAddRowHead = '<th style="width:40px"></th>';
            }

            /**
             * @description Depth Level(2 ба түүнээс дээш) үед 3 цэгтэй товчийг нэг болгож зурж байна.
             * @date    2016-01-12
             * @author  Ulaankhuu Ts
             */
            if ($isTab) {
                $isAddRowHead .= '<th></th>';
                $gridFootBody .= "<td></td>";
                $tableCell .= '<td class="text-center stretchInput middle">';
                $tableCell .= '<a href="javascript:;" onclick="paramTreePopup(this, ' . getUID() . ', \'div#bp-window-' . $bpMetaDataId . ':visible\');" class="hide-tbl btn btn-sm purple-plum bp-btn-subdtl" style="width:35px" title="Дэлгэрэнгүй" data-b-path="'.$paramName.'">...</a> ';
                $tableCell .= '<div class="param-tree-container-tab param-tree-container hide">';
                $tableCell .= '<div class="tabbable-line">
                                    <ul class="nav nav-tabs">' . $gridTabContentHeader . '</ul>
                                    <div class="tab-content">
                                    ' . $gridTabContentBody . '
                                    </div>
                                </div>';
                $tableCell .= '</div>';
                $tableCell .= '</td>';
            }

            $isAddRowBody = '';
            if (issetParam($funcArg['isShowDelete']) == '1') {
                $isAddRowBody = '<td class="text-center stretchInput middle" style="width:50px;"><a href="javascript:;" class="btn red btn-xs bp-remove-row" title="'.Lang::line('META_00002').'"><i class="fa fa-trash"></i></a></td>';
            }

            $tableCell .= $isAddRowBody;
            $table .= $isAddRowHead;

            if (issetParam($funcArg['isShowAdd']) == '1') {
                $cryptHtmlRow = Crypt::encrypt(Str::remove_doublewhitespace(str_replace(array("\r\n", "\n", "\r"), '', '<tr class="bp-detail-row">' . $tableCell . '</tr>')));
                $isAddRowBtn = Form::button(array('data-action-path' => $paramName, 'class' => 'btn btn-xs green-meadow ml0 bp-add-one-row bp-subdtl-addrow float-left my-1', 'value' => '<i class="icon-plus3 font-size-12"></i> ' . Lang::line('addRow'), 'onclick' => 'bpAddDtlRow_'.$uniqId.'(this, \'' . $cryptHtmlRow . '\');'));
            }       

            if (issetParam($funcArg['groupKeyLookupMeta']) != '' && issetParam($funcArg['isShowMultipleKeyMap']) != '0' && issetParam($funcArg['isShowMultipleKeyMap']) != '') {
                $paramsMultiRowsBtn = array(
                    'methodId' => $bpMetaDataId,
                    'id' => issetParam($funcArg['id']),
                    'code' => issetParam($funcArg['code']),
                    'groupKeyLookupMeta' => issetParam($funcArg['groupKeyLookupMeta']),
                    'paramPath' => issetParam($funcArg['paramPath']),
                    'groupConfigParamPath' => issetParam($funcArg['groupConfigParamPath']),
                    'groupConfigLookupPath' => issetParam($funcArg['groupConfigLookupPath']), 
                    'isFirstRow' => issetParam($funcArg['isFirstRow'])
                );
                $isMultipleAddRowBtn = self::addMultipleRowsBtn($uniqId, $paramsMultiRowsBtn);
                $isMultipleAddRowBtn .= '<div class="mt10 clearfix"></div>';
            }
            $isAddRowBtn = $isMultipleAddRowBtn == '' ? $isAddRowBtn . '<div class="clearfix"></div>' : $isAddRowBtn;

            $table .= '</tr>';
            $table .= '</thead>';

            $gridBody = '<tr class="bp-detail-row">';
            $gridBody .= $tableCell;
            $gridBody .= '</tr>';

            $gridFoot = '<tr>';
            if (issetParam($funcArg['isShowDelete']) == '1') {
                $gridFoot .= $gridFootBody . '<td></td>';
            } else {
                $gridFoot .= $gridFootBody;
            }
            $gridFoot .= '</tr>';
            
            if ($funcArg['isFirstRow'] == '1') {
                $detailView = true;
            }
        
        } else {
            
            $sub_arg = array(
                'parentRecordType' => 'row'
            );
            if ($subTree !== '') {
                $gridBodyTreeWrap = '<div class="hideTreeFolderMetaGroupClass hide" id="' . $paramName . '">';
                $gridBodyTreeWrap .= '<p class="meta_description"><i class="fa fa-info-circle"></i> <!--end zasna--></p>';
            }
            $table = '<table class="table table-sm table-no-bordered" style="background-color: transparent;">';
            
            foreach ($data as $row) {
                $sub_arg['isShowAdd'] = $row['IS_SHOW_ADD'];
                $sub_arg['isShowDelete'] = $row['IS_SHOW_DELETE'];
                $sub_arg['isShowMultiple'] = $row['IS_SHOW_MULTIPLE'];      
                $sub_arg['isFirstRow'] = $row['IS_FIRST_ROW'];

                $tableCellAfter .= '<tr>';
                if ($row['META_TYPE_CODE'] == 'group') {
                    if ($row['IS_SHOW'] == '1') {

                        if ($subTree == '') {
                            $tableCellAfter .= '<td class="text-right middle float-left" data-cell-path="' . $paramName . "." . $row['META_DATA_CODE'] . '" style="width: 18%">';
                            $labelAttr = array(
                                'text' => Lang::line($row['META_DATA_NAME']),
                            );
                            if ($row['IS_REQUIRED'] == '1') {
                                $labelAttr = array_merge($labelAttr, array('required' => 'required'));
                            }
                            $tableCellAfter .= Form::label($labelAttr);
                            $tableCellAfter .= '</td>';
                            $tableCellAfter .= '<td data-cell-path="' . $paramName . "." . $row['META_DATA_CODE'] . '" style="width: 32%" class="middle float-left">';
                            $tableCellAfter .= self::buildTreeParamModel($uniqId, $bpMetaDataId, $row['META_DATA_NAME'], $paramName . "." . $row['META_DATA_CODE'], $row['RECORD_TYPE'], $row['ID'], $fillParamData, '', $sub_arg, 1, issetParam($row['COLUMN_COUNT']), $rowIndex);
                            $tableCellAfter .= '</td>';                
                        } else {
                            $gridBodySubTree .= self::buildTreeParamModel($uniqId, $bpMetaDataId, $row['META_DATA_NAME'], $paramName . "." . $row['META_DATA_CODE'], $row['RECORD_TYPE'], $row['ID'], $fillParamData, 'subTree', $sub_arg, 1, issetParam($row['COLUMN_COUNT']), $rowIndex);
                        }
                    }
                } else {
                    array_push($depthLevelRowArr, $row);                   
                }
                $tableCellAfter .= '</tr>';
            }                           
            
            if (isset($funcArg['htmlcontent'])) {
                return (new Mdwebservice())->renderDepthLevelAddDtlRow($bpMetaDataId, $depthLevelRowArr, $paramName, $colCount, $rowIndex, $funcArg);
            } else {
                $key = strtolower($paramName);
                $explodeKey = explode('.', $key);
                $lastKey = array_pop($explodeKey);
                $tableCell .= (new Mdwebservice())->renderDepthLevelAddDtlRow($bpMetaDataId, $depthLevelRowArr, $paramName, $colCount, $rowIndex, $funcArg, isset($fillParamData[$lastKey]) ? $fillParamData[$lastKey] : array());
            }
            
            $tableCell .= $tableCellAfter;
            $gridBody = $tableCell;
        }
        // </editor-fold>
        
        $gridBodyData = '';

        // <editor-fold defaultstate="collapsed" desc="Процесс засах горим">
        if ($fillParamData) {
            $key = strtolower($paramName);
            $explodeKey = explode('.', $key);
            $lastKey = array_pop($explodeKey);
            
            if (isset($fillParamData[$lastKey])) {

                $depthLevelRowArr = array();
                $gridBody = '';

                if (isset($fillParamData[$lastKey][0])) {
                    foreach ($fillParamData[$lastKey] as $rk => $rowData) {

                        if ($recordType == 'rows') {
                            $arg['joinstrRows'] = issetParam($funcArg['joinstrRows']) . $recordType;

                            if (issetParam($funcArg['parentRecordType']) == 'rows' || issetParam($funcArg['parentRecordType']) == 'row') {
                                $rowIndex = $rowIndex;
                            } else {
                                $rowIndex = $rk;
                            }
                            $arg['parentRowIndex'] = $rk;

                            $gridBodyData .= '<tr class="bp-detail-row saved-bp-row">';
                            if ($arg['joinstrRows'] == 'rowsrows') {
                                $gridBodyData .= '<td class="text-center middle stretchInput"><span>' . ($rk + 1) . '</span><input type="hidden" name="param[' . $paramName . '.rowCount][' . $rowIndex . '][' . $funcArg['parentRowIndex'] . '][]"/></td>';
                            } else {
                                $gridBodyData .= '<td class="text-center middle stretchInput"><span>' . ($rk + 1) . '</span><input type="hidden" name="param[' . $paramName . '.rowCount][' . $rowIndex . '][]"/></td>';
                            }
                            $isRowStateChild = false;
                            $totalColumnCountChild = count($data);

                            foreach ($data as $rowIndexNum => $row) {                                    
                                $arg['isShowAdd'] = $row['IS_SHOW_ADD'];
                                $arg['isShowDelete'] = $row['IS_SHOW_DELETE'];
                                $arg['isShowMultiple'] = $row['IS_SHOW_MULTIPLE'];      
                                $arg['isFirstRow'] = $row['IS_FIRST_ROW'];
                                $arg['parentRecordType'] = 'rows';                                 
                                $hideClass = '';

                                if ($row['IS_SHOW'] != '1') {
                                    $hideClass = " display-none";
                                }

                                if ($row['META_TYPE_CODE'] == 'group') {
                                    if ($row['RECORD_TYPE'] == 'row' && $row['IS_BUTTON'] != '1') {
                                        $additionalBody = '';
                                        $additionalRow = (new Mdwebservice())->renderAdditionalRowHeader($bpMetaDataId, $row['ID']);                                        
                                        $additionalMetaCode = strtolower($row['META_DATA_CODE']);
                                        $rowData = isset($rowData[$additionalMetaCode]) ? $rowData[$additionalMetaCode] : $rowData;

                                        foreach ($additionalRow as $key => $rowVal) {                  
                                            $hideClassSub = '';
                                            if ($rowVal['IS_SHOW'] != '1') {
                                                $hideClassSub = " display-none";
                                            }
                                            $additionalBody .= '<td data-cell-path="' . $rowVal['PARAM_REAL_PATH'] . '"  class="middle stretchInput text-center ' . issetParam($aggregateClassSub) . ' ' . $hideClassSub . '">';
                                                $additionalBody .= Mdwebservice::renderParamControl($bpMetaDataId, $rowVal, "param[" . $rowVal['PARAM_REAL_PATH'] . "][" . $rowIndex . "][]", $paramName . '.' . $rowVal['META_DATA_CODE'], $rowData, 'removeSelect2');
                                            $additionalBody .= '</td>';
                                            $gridClass .= Mdwebservice::fieldDetailStyleClass($rowVal, $rowVal['NODOT_PARAM_REAL_PATH'], 'bp-window-' . $bpMetaDataId); 
                                            //var_dump($gridClass);die;
                                        }
                                        $gridBodyData .= $additionalBody;

                                    } else {                                    
                                        $gridBodyData .= '<td class="' . $hideClass . '" data-cell-path="' . $paramName . '.' . $row['META_DATA_CODE'] . '">';
                                        if ($row['IS_SHOW'] == '1') {
                                            $gridBodyData .= self::buildTreeParamModel($uniqId, $bpMetaDataId, $row['META_DATA_NAME'], $paramName . '.' . $row['META_DATA_CODE'], $row['RECORD_TYPE'], $row['ID'], $rowData, '', $arg, $row['IS_BUTTON'], $row['COLUMN_COUNT'], $rowIndex);
                                        }
                                        $gridBodyData .= '</td>';
                                    }
                                } else {

                                    $gridBodyData .= '<td data-cell-path="' . $paramName . '.' . $row['META_DATA_CODE'] . '" class="middle stretchInput text-center ' . $hideClass . '">';

                                    if ($arg['joinstrRows'] == 'rowsrows') {                                        
                                        $gridBodyData .= Mdwebservice::renderParamControl(
                                            $bpMetaDataId, $row, "param[" . $paramName . '.' . $row['META_DATA_CODE'] . "][" . $rowIndex . "][" . $funcArg['parentRowIndex'] . "][]", $paramName . '.' . $row['META_DATA_CODE'], $rowData, 'removeSelect2'
                                        );
                                    } else {
                                        $gridBodyData .= Mdwebservice::renderParamControl(
                                            $bpMetaDataId, $row, "param[" . $paramName . '.' . $row['META_DATA_CODE'] . "][" . $rowIndex . "][]", $paramName . '.' . $row['META_DATA_CODE'], $rowData, 'removeSelect2'
                                        );
                                    }

                                    $gridBodyData .= '</td>';

                                    $gridClass .= Mdwebservice::fieldDetailStyleClass($row, $row['NODOT_PARAM_REAL_PATH'], 'bp-window-' . $bpMetaDataId); 
                                }

                                if (strtolower($row['META_DATA_CODE']) == 'rowstate') {
                                    $isRowStateChild = true;
                                }
                                if (!$isRowStateChild && ($totalColumnCountChild == ($rowIndexNum + 1))) {
                                    $gridBodyData .= '<td data-cell-path="' . $paramName . '.rowState" class="display-none">';
                                    $gridBodyData .= '<input type="hidden" name="param[' . $paramName . '.rowState]['.$rowIndex.'][]" data-path="' . $paramName . '.rowState" data-field-name="rowState" value="unchanged">';
                                    $gridBodyData .= '</td>';
                                }                                    
                            }
                            if (issetParam($funcArg['isShowDelete']) == '1') {
                                $gridBodyData .= '<td class="text-center middle stretchInput" style="width:50px;"><a href="javascript:;" class="btn red btn-xs bp-remove-row" title="'.Lang::line('delete_btn').'"><i class="fa fa-trash"></i></a></td>';
                            }
                            $gridBodyData .= '</tr>';
                            
                        } else {
                            
                            $gridBodyData .= '<tr class="saved-bp-row">';   
                            
                            foreach ($data as $row) {                                    
                                $arg['isShowAdd'] = $row['IS_SHOW_ADD'];
                                $arg['isShowDelete'] = $row['IS_SHOW_DELETE'];
                                $arg['isShowMultiple'] = $row['IS_SHOW_MULTIPLE'];  
                                $arg['isFirstRow'] = $row['IS_FIRST_ROW'];
                                $arg['parentRecordType'] = 'row';

                                if ($row['IS_SHOW'] == '1') {

                                    if ($row['META_TYPE_CODE'] == 'group') {

                                        $gridBodyData .= '<td class="text-right middle float-left" data-cell-path="' . $paramName . '.' . $row['META_DATA_CODE'] . '" style="width: 18%">';
                                        $labelAttr = array(
                                            'text' => Lang::line($row['META_DATA_NAME']),
                                        );
                                        if ($row['IS_REQUIRED'] == '1') {
                                            $labelAttr = array_merge($labelAttr, array('required' => 'required'));
                                        }
                                        $gridBodyData .= Form::label($labelAttr);
                                        $gridBodyData .= '</td>';
                                        $gridBodyData .= '<td data-cell-path="' . $paramName . '.' . $row['META_DATA_CODE'] . '" style="width: 32%" class="middle float-left">';
                                        $gridBodyData .= self::buildTreeParamModel($uniqId, $bpMetaDataId, $row['META_DATA_NAME'], $paramName . '.' . $row['META_DATA_CODE'], $row['RECORD_TYPE'], $row['ID'], $rowData, '', $arg, 1, $row['COLUMN_COUNT'], $rowIndex);
                                        $gridBodyData .= '</td>';
                                        $gridBodyData .= '</td>';

                                    } else {

                                        $gridBodyData .= '<td class="text-right middle float-left" data-cell-path="' . $paramName . '.' . $row['META_DATA_CODE'] . '" style="width: 18%">';
                                        $labelAttr = array(
                                            'text' => Lang::line($row['META_DATA_NAME']),
                                            'for' => "param[" . $paramName . '.' . $row['META_DATA_CODE'] . "][$rowIndex][]",
                                        );
                                        if ($row['IS_REQUIRED'] == '1') {
                                            $labelAttr = array_merge($labelAttr, array('required' => 'required'));
                                        }
                                        $gridBodyData .= Form::label($labelAttr);
                                        $gridBodyData .= '</td>';
                                        $gridBodyData .= '<td data-cell-path="' . $paramName . '.' . $row['META_DATA_CODE'] . '" style="width: 32%" class="middle float-left">';
                                        $gridBodyData .= Mdwebservice::renderParamControl(
                                            $bpMetaDataId, $row, "param[" . $paramName . '.' . $row['META_DATA_CODE'] . "][$rowIndex][]", $paramName . '.' . $row['META_DATA_CODE'], $rowData, 'removeSelect2'
                                        );
                                        $gridBodyData .= '</td>';
                                    }
                                }
                            }
                            if (issetParam($funcArg['isShowDelete']) == '1') {
                                $gridBodyData .= '<td class="text-center stretchInput middle" style="width:50px;"><a href="javascript:;" class="btn red btn-xs bp-remove-row" title="'.Lang::line('delete_btn').'"><i class="fa fa-trash"></i></a></td>';
                            }
                            $gridBodyData .= '</tr>';
                        }
                    }
                } else {
                    
                    if ($recordType == 'rows') {
                        $gridBodyData .= '<tr class="bp-detail-row">';
                        $gridBodyData .= '<td class="text-center middle stretchInput"><span>1</span><input type="hidden" name="param[' . $paramName . '.rowCount][0][]"/></td>';
                        foreach ($data as $rk => $row) {
                            $arg['isShowAdd'] = $row['IS_SHOW_ADD'];
                            $arg['isShowDelete'] = $row['IS_SHOW_DELETE'];
                            $arg['isShowMultiple'] = $row['IS_SHOW_MULTIPLE'];  
                            $arg['isFirstRow'] = $row['IS_FIRST_ROW'];
                            $arg['parentRecordType'] = 'rows';          
                            $hideClass = '';

                            if ($row['IS_SHOW'] != '1') {
                                $hideClass = " display-none";
                            }

                            if ($row['META_TYPE_CODE'] == 'group') {

                                $gridBodyData .= '<td class="' . $hideClass . '" data-cell-path="' . $paramName . '.' . $row['META_DATA_CODE'] . '">';
                                if ($row['IS_SHOW'] == '1') {
                                    $gridBodyData .= self::buildTreeParamModel($uniqId, $bpMetaDataId, $row['META_DATA_NAME'], $paramName . '.' . $row['META_DATA_CODE'], $row['RECORD_TYPE'], $row['ID'], $fillParamData[$lastKey], '', $arg, 1, $row['COLUMN_COUNT'], $rowIndex);
                                }
                                $gridBodyData .= '</td>';
                            } else {

                                $gridBodyData .= '<td data-cell-path="' . $paramName . "." . $row['META_DATA_CODE'] . '" class="middle stretchInput' . $hideClass . '">';
                                $gridBodyData .= Mdwebservice::renderParamControl(
                                    $bpMetaDataId, $row, "param[" . $paramName . '.' . $row['META_DATA_CODE'] . "][$rowIndex][]", $paramName . '.' . $row['META_DATA_CODE'], $fillParamData[$lastKey], 'removeSelect2'
                                );
                                $gridBodyData .= '</td>';

                                $gridClass .= Mdwebservice::fieldDetailStyleClass($row, $row['NODOT_PARAM_REAL_PATH'], 'bp-window-' . $bpMetaDataId);                
                            }
                        }
                        if (issetParam($funcArg['isShowDelete']) == '1') {
                            $gridBodyData .= '<td class="text-center stretchInput middle" style="width:50px;"><a href="javascript:;" class="btn red btn-xs bp-remove-row" title="'.Lang::line('delete_btn').'"><i class="fa fa-trash"></i></a></td>';
                        }
                        $gridBodyData .= '</tr>';
                    } else {
                        
                        foreach ($data as $row) {
                            $arg['isShowAdd'] = $row['IS_SHOW_ADD'];
                            $arg['isShowDelete'] = $row['IS_SHOW_DELETE'];
                            $arg['isShowMultiple'] = $row['IS_SHOW_MULTIPLE'];    
                            $arg['isFirstRow'] = $row['IS_FIRST_ROW'];
                            $arg['parentRecordType'] = 'row';    
                            
                            if ($row['IS_SHOW'] != '1') {
                                $hideClass = ' class="display-none"';
                            } else {
                                $hideClass = '';
                            }

                            $gridBodyData .= '<tr'.$hideClass.'>';

                            if ($row['META_TYPE_CODE'] == 'group') {
                                
                                $gridBodyData .= '<td class="text-right middle float-left" data-cell-path="' . $paramName . "." . $row['META_DATA_CODE'] . '" style="width: 18%">';
                                $labelAttr = array(
                                    'text' => Lang::line($row['META_DATA_NAME']),
                                );
                                if ($row['IS_REQUIRED'] == '1') {
                                    $labelAttr = array_merge($labelAttr, array('required' => 'required'));
                                }
                                $gridBodyData .= Form::label($labelAttr);
                                $gridBodyData .= '</td>';
                                $gridBodyData .= '<td data-cell-path="' . $paramName . '.' . $row['META_DATA_CODE'] . '" style="width: 32%" class="middle float-left">';
                                $gridBodyData .= self::buildTreeParamModel($uniqId, $bpMetaDataId, $row['META_DATA_NAME'], $paramName . '.' . $row['META_DATA_CODE'], $row['RECORD_TYPE'], $row['ID'], $fillParamData[$lastKey], '', $arg, 1, issetParam($row['COLUMN_COUNT']), $rowIndex);
                                $gridBodyData .= '</td>';
                                $gridBodyData .= '</td>';
                                
                            } else {                                        
                                
                                array_push($depthLevelRowArr, $row);
                            }

                            $gridBodyData .= '</tr>';
                        }
                        $gridBodyData .= (new Mdwebservice())->renderDepthLevelAddDtlRow($bpMetaDataId, $depthLevelRowArr, $paramName, $colCount, $rowIndex, $funcArg, $fillParamData[$lastKey]);
                    }
                }
                
                $detailView = false;
            }
        }
        // </editor-fold>
            
        $table .= '<tbody class="tbody">';
        $table .= (($recordType == 'row' || (isset($detailView) && $detailView)) ? $gridBody : '') . $gridBodyData;
        $table .= '</tbody>';
        $table .= '<tfoot>' . ($isAggregate == true ? '' . $gridFoot /* . $htmlGridFoot */ : '') . '</tfoot>';
        $table .= '</table>';
        $table .= '<style type="text/css">#bp-window-' . $bpMetaDataId . ' table.bprocess-table-subdtl{table-layout: fixed !important; max-width: ' . Mdwebservice::$tableWidth . 'px !important;} ' . $gridClass . '</style>';
        if ($subTree !== '') {
            $gridBodyTreeWrap .= $isAddRowBtn . $isMultipleAddRowBtn . $table;
            $gridBodyTreeWrap .= '</div>';
            $gridBodyTreeWrap .= $gridBodySubTree;
        } else {
            $gridBodyTreeWrap = $isAddRowBtn . $isMultipleAddRowBtn . $table;
        }

        return $gridBodyTreeWrap;
    }

    public function buildTreeParamTwoViewModel($bpMetaDataId, $paramName, $recordType, $parentId, $fillParamData, $subTree = '', $colCount, $rowIndex = 0, $funcArg = array()) {

        $cache = phpFastCache();

        $data = $cache->get('bpDetail_' . $bpMetaDataId.'_'.$parentId);

        if ($data == null) {
            $data = self::getProcessParamsData($bpMetaDataId, $parentId);
            $cache->set('bpDetail_' . $bpMetaDataId.'_'.$parentId, $data, Mdwebservice::$expressionCacheTime);
        }
        
        $table = '';
        $tableCell = '';
        $tableCellAfter = '';
        $isAddRowBtn = '';
        $gridBodySubTree = '';
        $gridBodyTreeWrap = '';
        $gridFoot = '';
        $gridFootBody = '';
        $isAggregate = false;
        $aggregateClass = '';
        $depthLevelRowArr = array();
        $gridTabContentHeader = '';
        $gridTabContentBody = '';
        $isTab = false;
        $gridClass = '';

        // <editor-fold defaultstate="collapsed" desc="Proccess ROW && ROWS">
        if ($recordType == 'rows') {

            $tableCell = '<td class="text-center middle stretchInput"><span>1</span><input type="hidden" name="param[' . $paramName . '.rowCount][0][]"/></td>';
            if ($subTree !== '') {
                $gridBodyTreeWrap = '<div class="hideTreeFolderMetaGroupClass hide" id="' . $paramName . '">';
                $gridBodyTreeWrap .= '<p class="meta_description"><i class="fa fa-info-circle"></i> <!--end zasna--></p>';
            }
            $table = '<table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-table-subdtl bprocess-theme1">';
            $table .= '<thead>';
            $table .= '<tr>';
            $table .= '<th style="width:15px;">№</th>';
            $gridFootBody = '<td></td>';
            $ii = 0;

            foreach ($data as $row) {
                $foodAmount = '';
                $aggregateClass = '';
                if ($row['COLUMN_AGGREGATE'] != '') {
                    $isAggregate = true;
                    $foodAmount = '0.00';
                    $aggregateClass = 'aggregate-' . $row['COLUMN_AGGREGATE'];
                }
                $hideClass = "";
                if ($row['IS_SHOW'] != '1') {
                    $hideClass = " display-none";
                }
                //$table .= '<th class="'.$hideClass.'" data-cell-path="'.$paramName.".".$row['META_DATA_CODE'].'" '.($row['COLUMN_AGGREGATE']!= '' ? 'data-aggregate="' . $row['COLUMN_AGGREGATE'] . '"' : '').'>'.$row['META_DATA_NAME'].'</th>';
                if ($row['META_TYPE_CODE'] == 'group') {
                    
                    $sub_arg = array(
                        'parentRecordType' => 'rows'
                    );
                    
                    if ($row['RECORD_TYPE'] == 'row' && $row['IS_BUTTON'] != '1') {
                        
                        $additionalHeader = '';
                        $additionalBody = '';
                        $additionalFooter = '';
                        $additionalRow = (new Mdwebservice())->renderAdditionalRowHeader($bpMetaDataId, $row['ID']);
                        
                        foreach ($additionalRow as $key => $rowVal) {
                            $hideClassSub = '';
                            if ($rowVal['IS_SHOW'] != '1') {
                                $hideClassSub = " display-none";
                            }
                            $foodAmountSub = '';
                            $aggregateClassSub = '';
                            if ($rowVal['COLUMN_AGGREGATE'] != '') {
                                $foodAmountSub = '0.00';
                                $aggregateClassSub = 'aggregate-' . $rowVal['COLUMN_AGGREGATE'];
                            }
                            $additionalHeader .= '<th class="' . $hideClassSub . '" data-row-path="' . $paramName . "." . $row['META_DATA_CODE'] . '" data-cell-path="' . $rowVal['PARAM_REAL_PATH'] . '" ' . ($rowVal['COLUMN_AGGREGATE'] != '' ? 'data-aggregate="' . $rowVal['COLUMN_AGGREGATE'] . '"' : '') . '>' . Lang::line($rowVal['META_DATA_NAME']) . '</th>';
                            $additionalFooter .= '<td data-cell-path="' . $rowVal['PARAM_REAL_PATH'] . '" data-row-path="' . $paramName . "." . $row['META_DATA_CODE'] . '" class="text-right bigdecimalInit ' . $hideClassSub . '">' . $foodAmountSub . '</td>';
                        }
                        $table = $table . $additionalHeader;
                        $gridFootBody .= $additionalFooter;
                    }
                } else {
                    $table .= '<th data-cell-path="' . $row['PARAM_REAL_PATH'] . '" class="' . $row['NODOT_PARAM_REAL_PATH'] . ' ' . $hideClass . '" ' . ($row['COLUMN_AGGREGATE'] != '' ? 'data-aggregate="' . $row['COLUMN_AGGREGATE'] . '"' : '') . '>' . Lang::line($row['META_DATA_NAME']) . '</th>';
                    $gridFootBody .= '<td data-cell-path="' . $row['PARAM_REAL_PATH'] . '" class="text-right bigdecimalInit ' . $hideClass . '">' . $foodAmount . '</td>';
                    
                    $gridClass .= Mdwebservice::fieldDetailStyleClassView($row, $row['NODOT_PARAM_REAL_PATH'], 'bp-window-' . $bpMetaDataId);                    
                }
            }

            $isAddRowHead = '';
            if ($isTab) {
                $isAddRowHead .= '<th></th>';
                $gridFootBody .= "<td></td>";
            }

            $table .= $isAddRowHead;
            $table .= '</tr>';
            $table .= '</thead>';

            $gridFoot = '<tr>';
            $gridFoot .= $gridFootBody . '<td></td>';
            $gridFoot .= '</tr>';
        } else {
            $table = '<table class="table table-sm table-no-bordered bprocess-table-row" style="background-color: transparent">';
            $table .= '<thead>';
            $table .= '<tr><td colspan="2" style="padding: 6px;"><span class="meta_description"><i class="fa fa-info-circle"></i> '.issetParam($funcArg['metadataName']).'</span></td>';
            $table .= '</thead>';
        }
        // </editor-fold>

        $gridBody = $gridBodyData = '';

        // <editor-fold defaultstate="collapsed" desc="Процесс засах горим">
        if ($fillParamData) {
            
            $key = strtolower($paramName);
            $explodeKey = explode('.', $key);
            $lastKey = array_pop($explodeKey);
            
            if (isset($fillParamData[$lastKey])) {
                
                if (isset($fillParamData[$lastKey][0])) {
                    foreach ($fillParamData[$lastKey] as $rk => $rowData) {
                        if ($recordType == 'rows') {
                            if (issetParam($funcArg['parentRecordType']) == 'rows') {
                                $rowIndex = $rowIndex;
                            } else {
                                $rowIndex = $rk;
                            }
                            $gridBodyData .= '<tr class="bp-detail-row saved-bp-row">';
                            $gridBodyData .= '<td class="text-center middle stretchInput"><span>' . ($rk + 1) . '</span><input type="hidden" name="param[' . $paramName . '.rowCount][' . $rowIndex . '][]"/></td>';
                            $isRowStateChild = false;
                            $totalColumnCountChild = count($data);

                            foreach ($data as $rowIndexNum => $row) {
                                $arg['isShowAdd'] = $row['IS_SHOW_ADD'];
                                $arg['isShowDelete'] = $row['IS_SHOW_DELETE'];
                                $arg['isShowMultiple'] = $row['IS_SHOW_MULTIPLE'];         
                                $arg['isFirstRow'] = $row['IS_FIRST_ROW'];
                                $hideClass = '';

                                if ($row['IS_SHOW'] != '1') {
                                    $hideClass = ' display-none';
                                }

                                if ($row['META_TYPE_CODE'] == 'group') {
                                    if ($row['RECORD_TYPE'] == 'row') {
                                        $additionalBody = '';
                                        $additionalRow = (new Mdwebservice())->renderAdditionalRowHeader($bpMetaDataId, $row['ID']);
                                        foreach ($additionalRow as $key => $rowVal) {
                                            $hideClassSub = '';
                                            if ($rowVal['IS_SHOW'] != '1') {
                                                $hideClassSub = " display-none";
                                            }
                                            $additionalBody .= '<td data-cell-path="' . $rowVal['PARAM_REAL_PATH'] . '"  class="middle stretchInput ' . issetParam($aggregateClassSub) . ' ' . $hideClassSub . '">';
                                            if ($rowVal['IS_SHOW'] == '1') {
                                                $additionalBody .= Mdwebservice::renderViewParamControl($bpMetaDataId, $rowVal, "param[" . $rowVal['PARAM_REAL_PATH'] . "][" . $rowIndex . "][]", $paramName . "." . $rowVal['META_DATA_CODE'], $rowData, 'removeSelect2');
                                            }
                                            $additionalBody .= '</td>';
                                        }
                                        $gridBodyData .= $additionalBody;
                                    } else {
                                        $gridBodyData .= '<td class="' . $hideClass . '" data-cell-path="' . $paramName . "." . $row['META_DATA_CODE'] . '">';
                                        if ($row['IS_SHOW'] == '1') {
                                            $gridBodyData .= self::buildTreeParamViewModel($bpMetaDataId, $row['META_DATA_NAME'], $paramName . "." . $row['META_DATA_CODE'], $row['RECORD_TYPE'], $row['ID'], $rowData, '', $arg, $row['IS_BUTTON'], $row['COLUMN_COUNT'], $rowIndex);
                                        }
                                        $gridBodyData .= '</td>';
                                    }
                                } else {

                                    $gridBodyData .= '<td data-cell-path="' . $paramName . "." . $row['META_DATA_CODE'] . '" class="middle stretchInput' . $hideClass . '">';
                                    $gridBodyData .= Mdwebservice::renderViewParamControl(
                                        $bpMetaDataId, $row, "param[" . $paramName . "." . $row['META_DATA_CODE'] . "][" . $rowIndex . "][]", $paramName . "." . $row['META_DATA_CODE'], $rowData, 'removeSelect2'
                                    );
                                    $gridBodyData .= '</td>';

                                    if(!empty($row['COLUMN_WIDTH'])) {
                                        $gridClass .= '
                                                .' . str_replace(".", "", $paramName) . $row['META_DATA_CODE'] . '{
                                                    ' . ' width:' . $row['COLUMN_WIDTH'] . ' !important;' . ' 
                                                } 
                                            ';
                                    }                                         
                                }

                                if (strtolower($row['META_DATA_CODE']) == 'rowstate') {
                                    $isRowStateChild = true;
                                }
                                if (!$isRowStateChild && ($totalColumnCountChild == ($rowIndexNum + 1))) {
                                    $gridBodyData .= '<td data-cell-path="' . $paramName . '.rowState" class="display-none">';
                                    $gridBodyData .= Mdwebservice::renderViewParamControl($bpMetaDataId, array_merge($row, array('PARAM_REAL_PATH' => $paramName . ".rowState", 'DEFAULT_VALUE' => 'UNCHANGED', 'IS_SHOW' => '0')), "param[" . $paramName . ".rowState][$rowIndex][]", $paramName . ".rowState", $rowData);
                                    $gridBodyData .= '</td>';
                                }                                    
                            }
                            $gridBodyData .= '</tr>';
                            
                        } else {
                            
                            $gridBodyData .= '<tr class="saved-bp-row">';
                            
                            foreach ($data as $row) {
                                $arg['isShowAdd'] = $row['IS_SHOW_ADD'];
                                $arg['isShowDelete'] = $row['IS_SHOW_DELETE'];
                                $arg['isShowMultiple'] = $row['IS_SHOW_MULTIPLE'];  
                                $arg['isFirstRow'] = $row['IS_FIRST_ROW'];

                                if ($row['IS_SHOW'] == '1') {

                                    if ($row['META_TYPE_CODE'] == 'group') {

                                        $gridBodyData .= '<td class="text-right middle float-left" data-cell-path="' . $paramName . "." . $row['META_DATA_CODE'] . '" style="width: 18%">';
                                        $labelAttr = array(
                                            'text' => Lang::line($row['META_DATA_NAME']),
                                        );
                                        if ($row['IS_REQUIRED'] == '1') {
                                            $labelAttr = array_merge($labelAttr, array('required' => 'required'));
                                        }
                                        $gridBodyData .= Form::label($labelAttr);
                                        $gridBodyData .= '</td>';
                                        $gridBodyData .= '<td data-cell-path="' . $paramName . "." . $row['META_DATA_CODE'] . '" style="width: 32%" class="middle float-left">';
                                        $gridBodyData .= self::buildTreeParamViewModel($bpMetaDataId, $row['META_DATA_NAME'], $paramName . "." . $row['META_DATA_CODE'], $row['RECORD_TYPE'], $row['ID'], $rowData, '', $arg, 1, $row['COLUMN_COUNT'], $rowIndex);
                                        $gridBodyData .= '</td>';
                                        $gridBodyData .= '</td>';
                                    } else {

                                        $gridBodyData .= '<td class="text-right middle float-left" data-cell-path="' . $paramName . "." . $row['META_DATA_CODE'] . '" style="width: 18%">';
                                        $labelAttr = array(
                                            'text' => Lang::line($row['META_DATA_NAME']),
                                            'for' => "param[" . $paramName . "." . $row['META_DATA_CODE'] . "][$rowIndex][]",
                                        );
                                        if ($row['IS_REQUIRED'] == '1') {
                                            $labelAttr = array_merge($labelAttr, array('required' => 'required'));
                                        }
                                        $gridBodyData .= Form::label($labelAttr);
                                        $gridBodyData .= '</td>';
                                        $gridBodyData .= '<td data-cell-path="' . $paramName . "." . $row['META_DATA_CODE'] . '" style="width: 32%" class="middle float-left">';
                                        $gridBodyData .= Mdwebservice::renderViewParamControl(
                                            $bpMetaDataId, $row, "param[" . $paramName . "." . $row['META_DATA_CODE'] . "][$rowIndex][]", $paramName . "." . $row['META_DATA_CODE'], $rowData, 'removeSelect2'
                                        );
                                        $gridBodyData .= '</td>';
                                    }
                                }
                            }
                            $gridBodyData .= '</tr>';
                        }
                    }
                } else {
                    if ($recordType == 'rows') {
                        $gridBodyData .= '<tr class="bp-detail-row">';
                        $gridBodyData .= '<td class="text-center middle stretchInput"><span>1</span><input type="hidden" name="param[' . $paramName . '.rowCount][0][]"/></td>';
                        foreach ($data as $row) {
                            $arg['isShowAdd'] = $row['IS_SHOW_ADD'];
                            $arg['isShowDelete'] = $row['IS_SHOW_DELETE'];
                            $arg['isShowMultiple'] = $row['IS_SHOW_MULTIPLE'];  
                            $arg['isFirstRow'] = $row['IS_FIRST_ROW'];
                            $hideClass = '';

                            if ($row['IS_SHOW'] != '1') {
                                $hideClass = ' display-none';
                            }

                            if ($row['META_TYPE_CODE'] == 'group') {

                                $gridBodyData .= '<td class="' . $hideClass . '" data-cell-path="' . $paramName . "." . $row['META_DATA_CODE'] . '">';
                                if ($row['IS_SHOW'] == '1') {
                                    $gridBodyData .= self::buildTreeParamViewModel($bpMetaDataId, $row['META_DATA_NAME'], $paramName . "." . $row['META_DATA_CODE'], $row['RECORD_TYPE'], $row['ID'], $fillParamData[$lastKey], '', $arg, 1, $row['COLUMN_COUNT'], $rowIndex);
                                }
                                $gridBodyData .= '</td>';
                                
                            } else {

                                $gridBodyData .= '<td data-cell-path="' . $paramName . "." . $row['META_DATA_CODE'] . '" class="middle stretchInput' . $hideClass . '">';
                                $gridBodyData .= Mdwebservice::renderViewParamControl(
                                    $bpMetaDataId, $row, "param[" . $paramName . "." . $row['META_DATA_CODE'] . "][$rowIndex][]", $paramName . "." . $row['META_DATA_CODE'], $fillParamData[$lastKey], 'removeSelect2'
                                );
                                $gridBodyData .= '</td>';

                                if (!empty($row['COLUMN_WIDTH'])) {
                                    $gridClass .= '
                                            .' . str_replace(".", "", $paramName) . $row['META_DATA_CODE'] . '{
                                                ' . ' width:' . $row['COLUMN_WIDTH'] . ' !important;' . ' 
                                            } 
                                        ';
                                }                                     
                            }
                        }
                        $gridBodyData .= '</tr>';
                        
                    } else {

                        foreach ($data as $row) {
                            $arg['isShowAdd'] = $row['IS_SHOW_ADD'];
                            $arg['isShowDelete'] = $row['IS_SHOW_DELETE'];
                            $arg['isShowMultiple'] = $row['IS_SHOW_MULTIPLE'];    
                            $arg['isFirstRow'] = $row['IS_FIRST_ROW'];

                            $gridBodyData .= '<tr>';
                            if ($row['IS_SHOW'] == '1') {

                                if ($row['META_TYPE_CODE'] == 'group') {

                                    $gridBodyData .= '<td class="text-right middle" data-cell-path="' . $paramName . "." . $row['META_DATA_CODE'] . '" style="width: 40%">';
                                    $labelAttr = array(
                                        'text' => Lang::line($row['META_DATA_NAME']),
                                    );
                                    if ($row['IS_REQUIRED'] == '1') {
                                        $labelAttr = array_merge($labelAttr, array('required' => 'required'));
                                    }
                                    $gridBodyData .= Form::label($labelAttr);
                                    $gridBodyData .= '</td>';
                                    $gridBodyData .= '<td data-cell-path="' . $paramName . "." . $row['META_DATA_CODE'] . '" style="width: 60%" class="">';
                                    $gridBodyData .= self::buildTreeParamViewModel($bpMetaDataId, $row['META_DATA_NAME'], $paramName . "." . $row['META_DATA_CODE'], $row['RECORD_TYPE'], $row['ID'], $fillParamData[$lastKey], '', $arg, 1, issetParam($row['COLUMN_COUNT']), $rowIndex);
                                    $gridBodyData .= '</td>';
                                    $gridBodyData .= '</td>';

                                } else {

                                    array_push($depthLevelRowArr, $row);

                                    if (empty($colCount)) {
                                        $gridBodyData .= '<td class="text-right middle" data-cell-path="' . $paramName . "." . $row['META_DATA_CODE'] . '" style="width: 40%;border-left: 0px solid #CCC !important;">';
                                        $labelAttr = array(
                                            'text' => Lang::line($row['META_DATA_NAME']),
                                            'for' => "param[" . $paramName . "." . $row['META_DATA_CODE'] . "][$rowIndex][]",
                                            'data-label-path' => $paramName . "." . $row['META_DATA_CODE']
                                        );
                                        if ($row['IS_REQUIRED'] == '1') {
                                            $labelAttr = array_merge($labelAttr, array('required' => 'required'));
                                        }
                                        $gridBodyData .= Form::label($labelAttr);
                                        $gridBodyData .= '</td>';
                                        $gridBodyData .= '<td data-cell-path="' . $paramName . "." . $row['META_DATA_CODE'] . '" style="width: 60%" class="">';
                                        $gridBodyData .= Mdwebservice::renderViewParamControl(
                                            $bpMetaDataId, $row, "param[" . $paramName . "." . $row['META_DATA_CODE'] . "][$rowIndex][]", $paramName . "." . $row['META_DATA_CODE'], $fillParamData[$lastKey], 'removeSelect2'
                                        );
                                        $gridBodyData .= '</td>';
                                    }
                                }
                            }
                            $gridBodyData .= '</tr>';
                        }
                        if (!empty($colCount)) {
                            $gridBodyData .= (new Mdwebservice())->renderViewDepthLevelAddDtlRow($bpMetaDataId, $depthLevelRowArr, $paramName, $colCount, $rowIndex, $funcArg, $fillParamData[$lastKey]);
                        }
                    }
                }
            }
        }
        // </editor-fold>

        $table .= '<tbody class="tbody">';
        $table .= ($recordType == 'row' ? $gridBody : '') . $gridBodyData;
        $table .= '</tbody>';
        $table .= '<tfoot>' . ($isAggregate == true ? '' . $gridFoot /* . $htmlGridFoot */ : '') . '</tfoot>';
        $table .= '</table>';
        $table .= '<style type="text/css">' . $gridClass . '</style>';

        if ($subTree !== '') {
            $gridBodyTreeWrap .= $isAddRowBtn . $table;
            $gridBodyTreeWrap .= '</div>';
            $gridBodyTreeWrap .= $gridBodySubTree;
        } else {
            $gridBodyTreeWrap = $isAddRowBtn . $table;
        }

        return $gridBodyTreeWrap;
    }

    public function renderAdditionalRowHeaderModel($bpMetaDataId, $parentId) {

        $cache = phpFastCache();

        $data = $cache->get('bpAdnlHeader_'.$bpMetaDataId.'_'.$parentId);

        if ($data == null) {
            if (!Mdwebservice::$isGroupRender) {
                $data = self::getProcessParamsData($bpMetaDataId, $parentId);
            } else {
                $data = self::getDVProcessParamsData($bpMetaDataId, $parentId);
            }
            $cache->set('bpAdnlHeader_'.$bpMetaDataId.'_'.$parentId, $data, Mdwebservice::$expressionCacheTime);
        }

        return $data;
    }
    
    public function groupParamsDataModel($processMetaDataId, $parentId = null, $where = null) {
        if (empty($processMetaDataId)) {
            return false;
        }

        $cache = phpFastCache();

        $data = $cache->get('bpDetail_'.$processMetaDataId.'_'.$parentId);

        if ($data == null) {
            $data = self::getProcessParamsData($processMetaDataId, $parentId, $where);
            $cache->set('bpDetail_'.$processMetaDataId.'_'.$parentId, $data, Mdwebservice::$expressionCacheTime);
        }

        return $data;
    }
    
    public function onlyShowGroupParamsDataModel($processMetaDataId, $parentId) {
        if (empty($parentId)) {
            return false;
        }

        $cache = phpFastCache();

        $data = $cache->get('bpDetail_'.$processMetaDataId.'_'.$parentId);

        if ($data == null) {
            $data = self::getProcessParamsData($processMetaDataId, $parentId);
            $cache->set('bpDetail_'.$processMetaDataId.'_'.$parentId, $data, Mdwebservice::$expressionCacheTime);
        }

        return $data;
    }

    public function onlyRowGroupTreeModel($processMetaDataId, $parentId, &$treeViewGroup) {
        if (empty($parentId)) {
            return false;
        }

        $cache = phpFastCache();

        $data = $cache->get('bpDetail_' . $processMetaDataId.'_'.$parentId);

        if ($data == null) {
            $data = self::getProcessParamsData($processMetaDataId, $parentId);
            $cache->set('bpDetail_' . $processMetaDataId.'_'.$parentId, $data, Mdwebservice::$expressionCacheTime);
        }

        if (isset($data)) {
            
            foreach ($data as $val) {
                
                if ($val['RECORD_TYPE'] == 'rows') {
                    
                    $treeViewGroup[] = array(
                        'GROUP_ID' => $val['ID'],
                        'GROUP_NAME' => $val['META_DATA_NAME'],
                        'PARAM_PATH' => $val['PARAM_REAL_PATH'],
                        'PARENT_ID' => $parentId
                    );
                    
                } elseif ($val['RECORD_TYPE'] == 'row') {
                    
                    $treeViewGroup[] = array(
                        'GROUP_ID' => $val['ID'],
                        'GROUP_NAME' => $val['META_DATA_NAME'],
                        'PARAM_PATH' => $val['PARAM_REAL_PATH'],
                        'PARENT_ID' => $parentId
                    );
                    
                    self::onlyRowGroupTreeModel($processMetaDataId, $val['ID'], $treeViewGroup);
                }
            }
        }
    }
    
    public function getShowInputParams($processMetaDataId, $isTreeView = null) {
        if (empty($processMetaDataId)) {
            return false;
        }

        $cache = phpFastCache();
        $bpParams = $cache->get('bpHeader_' . $processMetaDataId);
        $bpAutoNumber = $cache->get('bpAutoNumberConfig_' . $processMetaDataId);
        
        if ($bpAutoNumber == null) {
            $bpAutoNumber = $this->getAllProcessInputParamAutoNumberAddon($processMetaDataId);
            $cache->set('bpAutoNumberConfig_' . $processMetaDataId, $bpAutoNumber, Mdwebservice::$expressionCacheTime);
        }

        if ($bpParams == null) {

            $data = self::getProcessParamsData($processMetaDataId, null, ' AND PAL.PARENT_ID IS NULL');

            if ($data) {
                
                $isHasDtlTheme = $isLayout = $isIgnorePhotoTab = $isIgnoreFileTab = $isIgnoreCommentTab = 0;
                $array = $arrayDtl = $treeViewGroup = $pagerConfig = [];

                $array[0]['name'] = 'general_info';
                $array[0]['type'] = 'header';
                $array[0]['description'] = 'general_info';
                $array[0]['code'] = 'staticCode';

                $treeViewGroup[] = array(
                    'GROUP_ID' => $processMetaDataId, 
                    'GROUP_NAME' => 'general_info', 
                    'PARENT_ID' => ''
                );

                foreach ($data as $k => $row) {
                    
                    if ($row['META_TYPE_CODE'] != 'group') {
                        
                        if (array_key_exists($row['LOWER_PARAM_REAL_PATH'], $bpAutoNumber)) {
                            $row['DEFAULT_VALUE'] = $this->getAutoNumberServiceModel($processMetaDataId, $row['LOWER_PARAM_REAL_PATH']);
                        }
                        
                        $array[0]['data'][] = $row;
                        
                    } else {
                        array_push($arrayDtl, $row);
                    }
                    
                    if ($row['LAYOUT_SECTION_CODE']) {
                        $isLayout = 1;
                    }
                }

                if (count($arrayDtl) > 0) {
                    
                    $n = 1;

                    foreach ($arrayDtl as $child) {

                        if ($isTreeView && empty($child['SIDEBAR_NAME']) && $child['IS_SHOW'] == '1') {
                            $treeViewGroup[] = array(
                                'GROUP_ID' => $child['ID'],
                                'GROUP_NAME' => $child['META_DATA_NAME'],
                                'PARAM_PATH' => $child['PARAM_REAL_PATH'],
                                'PARENT_ID' => $processMetaDataId
                            );
                        }

                        $array[$n]['id'] = $child['ID'];
                        $array[$n]['name'] = $child['META_DATA_NAME'];
                        $array[$n]['code'] = $child['META_DATA_CODE'];
                        $array[$n]['recordtype'] = $child['RECORD_TYPE'];
                        $array[$n]['type'] = 'detail';
                        $array[$n]['tabName'] = $child['TAB_NAME'];
                        $array[$n]['isShow'] = $child['IS_SHOW'];
                        $array[$n]['isSave'] = $child['IS_SAVE'];
                        $array[$n]['description'] = $child['DESCRIPTION'];
                        $array[$n]['sidebarName'] = $child['SIDEBAR_NAME'];
                        $array[$n]['dataType'] = $child['META_TYPE_CODE'];
                        $array[$n]['attrLinkId'] = $child['ID'];
                        $array[$n]['isRequired'] = $child['IS_REQUIRED'];
                        $array[$n]['isFirstRow'] = $child['IS_FIRST_ROW'];
                        $array[$n]['isShowAdd'] = $child['IS_SHOW_ADD'];
                        $array[$n]['isShowDelete'] = $child['IS_SHOW_DELETE'];
                        $array[$n]['isShowMultiple'] = $child['IS_SHOW_MULTIPLE'];
                        $array[$n]['isShowMultipleMap'] = $child['IS_MULTI_ADD_ROW'];
                        $array[$n]['isShowMultipleKeyMap'] = $child['IS_MULTI_ADD_ROW_KEY'];
                        $array[$n]['groupLookupMeta'] = $child['LOOKUP_META_DATA_ID'];
                        $array[$n]['groupKeyLookupMeta'] = $child['LOOKUP_KEY_META_DATA_ID'];
                        $array[$n]['groupingName'] = $child['GROUPING_NAME'];
                        $array[$n]['paramPath'] = $child['PARAM_REAL_PATH'];
                        $array[$n]['columnCount'] = $child['COLUMN_COUNT'];
                        $array[$n]['columnWidth'] = $child['COLUMN_WIDTH'];
                        $array[$n]['isRefresh'] = $child['IS_REFRESH'];
                        $array[$n]['groupConfigParamPath'] = $child['GROUP_CONFIG_PARAM_PATH_GROUP'];
                        $array[$n]['groupConfigLookupPath'] = $child['GROUP_CONFIG_FIELD_PATH_GROUP'];
                        $array[$n]['isExcelExport'] = $child['IS_EXCEL_EXPORT'];
                        $array[$n]['isExcelImport'] = $child['IS_EXCEL_IMPORT'];
                        $array[$n]['detailModifyMode'] = $child['DETAIL_MODIFY_MODE'];
                        $array[$n]['dtlTheme'] = $child['DTL_THEME'];
                        $array[$n]['themePosition'] = $child['THEME_POSITION_NO'];
                        $array[$n]['widgetCode'] = $child['WIDGET_CODE'];
                        $array[$n]['lowerParamName'] = $child['LOWER_PARAM_NAME'];
                        $array[$n]['layoutSectionCode'] = $child['LAYOUT_SECTION_CODE'];
                        $array[$n]['layoutTabName'] = $child['LAYOUT_TAB_NAME'];
                        $array[$n]['layoutDisplayOrder'] = $child['LAYOUT_DISPLAY_ORDER'];
                        $array[$n]['layoutOtherAttr'] = $child['LAYOUT_OTHER_ATTR'];
                        $array[$n]['jsonConfig'] = $child['JSON_CONFIG'];
                        $array[$n]['wizardStep'] = $child['WIZARD_STEP'];
                        $array[$n]['isPivotColumns'] = '';
                        
                        if (!$isHasDtlTheme) {
                            if ($child['DTL_THEME'] && ($child['DTL_THEME'] != '14' && $child['DTL_THEME'] != '16')) {
                                $isHasDtlTheme = $child['DTL_THEME'];
                            } else {
                                $isHasDtlTheme = 0;
                            }
                        }
                        
                        if ($child['PAGING_CONFIG'] != '') {
                            
                            parse_str(strtolower($child['PAGING_CONFIG']), $pagerConfigArr);
                            
                            $pagerConfig[strtolower($child['PARAM_REAL_PATH'])] = array(
                                'configStr' => $child['PAGING_CONFIG'], 
                                'configArr' => $pagerConfigArr, 
                                'aggregateColumns' => self::getProcessAggregateColumnsModel($processMetaDataId, $child['PARAM_REAL_PATH'])
                            );
                            
                            $array[$n]['pagingConfig'] = $pagerConfigArr;
                            
                        } else {
                            $array[$n]['pagingConfig'] = null;
                        }
                        
                        $isColumnUserConfig = self::isDetailColumnUserConfig($processMetaDataId, $child['PARAM_REAL_PATH']);
                        
                        if ($isColumnUserConfig) {
                            $array[$n]['columnUserConfig'] = 1; 
                        }
                        
                        if ($child['LOWER_PARAM_NAME'] == 'pfprocessphotowidget' 
                            || $child['LOWER_PARAM_NAME'] == 'pfprocessfilewidget' 
                            || $child['LOWER_PARAM_NAME'] == 'pfprocesscommentwidget') {
                            
                            $array[$n]['widgetCode'] = $child['LOWER_PARAM_NAME'];
                            $array[$n]['data'] = array('META_TYPE_CODE' => $child['LOWER_PARAM_NAME']);
                            
                            if ($child['LOWER_PARAM_NAME'] == 'pfprocessphotowidget') {
                                $isIgnorePhotoTab = 1;
                            } elseif ($child['LOWER_PARAM_NAME'] == 'pfprocessfilewidget') {
                                $isIgnoreFileTab = 1;
                            } elseif ($child['LOWER_PARAM_NAME'] == 'pfprocesscommentwidget') {
                                $isIgnoreCommentTab = 1;
                            }
                            
                        } else {
                            
                            $childData = self::onlyShowGroupParamsDataModel($processMetaDataId, $child['ID']);
                        
                            if ($childData) {
                                $array[$n]['data'] = self::setProcessParamsDataModel($childData);
                            }

                            if ($isTreeView && $child['RECORD_TYPE'] == 'row') {
                                self::onlyRowGroupTreeModel($processMetaDataId, $child['ID'], $treeViewGroup);
                            }
                        }

                        $n++;
                    }
                }

                $bpParams = array(
                    'renderData'    => $array,
                    'treeData'      => $treeViewGroup, 
                    'isHasDtlTheme' => $isHasDtlTheme, 
                    'isLayout'      => $isLayout, 
                    'pagerConfig'   => $pagerConfig, 
                    'isIgnorePhotoTab'   => $isIgnorePhotoTab, 
                    'isIgnoreFileTab'    => $isIgnoreFileTab, 
                    'isIgnoreCommentTab' => $isIgnoreCommentTab
                );
                
                if (isset($pagerConfigArr)) {
                    $bpParams['isHasDtlTheme'] = 0;
                }
                
                $cache->set('bpHeader_' . $processMetaDataId, $bpParams, Mdwebservice::$expressionCacheTime);

                return $bpParams;

            } else {
                return array('renderData' => array());
            }
            
        } elseif ($bpAutoNumber) {
            
            $bpParamRow = $bpParams['renderData'][0]['data'];
            $arrayAuto = array();
            
            foreach ($bpParamRow as $k => $row) {
                if ($row['META_TYPE_CODE'] != 'group') {

                    if (array_key_exists($row['LOWER_PARAM_REAL_PATH'], $bpAutoNumber)) {
                        $row['DEFAULT_VALUE'] = $this->getAutoNumberServiceModel($processMetaDataId, $row['LOWER_PARAM_REAL_PATH']);
                    }

                    $arrayAuto[] = $row;
                }
            }            
            $bpParams['renderData'][0]['data'] = $arrayAuto;
        }

        return $bpParams;
    }
    
    public function setProcessParamsDataModel($childData) {
        return $childData;
    }
    
    public function inputALLParamsModel($processMetaDataId) {
        if (empty($processMetaDataId)) {
            return false;
        }

        $cache = phpFastCache();

        $data = $cache->get('bpAllInput_'.$processMetaDataId);

        if ($data == null) {
            $data = self::getProcessParamsData($processMetaDataId);
            
            if ($data) {
                $array = array();
                
                foreach ($data as $row) {
                    $array[$row['LOWER_PARAM_REAL_PATH']] = array(
                        'dataType'   => $row['META_TYPE_CODE'], 
                        'recordType' => $row['RECORD_TYPE']
                    );
                }
                
                $data = $array;
            }
            
            $cache->set('bpAllInput_'.$processMetaDataId, $array, Mdwebservice::$expressionCacheTime);
        }

        return $data;
    }
    
    public function getProcessAggregateColumnsModel($processMetaDataId, $groupPath) {
        
        $data = $this->db->GetAll("
            SELECT 
                PARAM_REAL_PATH, 
                PARAM_NAME, 
                DATA_TYPE, 
                COLUMN_AGGREGATE 
            FROM META_PROCESS_PARAM_ATTR_LINK 
            WHERE PROCESS_META_DATA_ID = $processMetaDataId  
                AND IS_INPUT = 1 
                AND IS_SHOW = 1 
                AND COLUMN_AGGREGATE IS NOT NULL 
                AND PARAM_REAL_PATH LIKE '$groupPath.%' 
                AND (LENGTH(PARAM_REAL_PATH) - LENGTH(REPLACE(PARAM_REAL_PATH, '.', ''))) = 1     
                AND DATA_TYPE IN ('bigdecimal', 'number', 'integer', 'decimal', 'long') 
            ORDER BY ORDER_NUMBER ASC");

        return $data;
    }
    
    public function isDetailColumnUserConfig($processMetaDataId, $groupPath) {
        
        $row = $this->db->GetRow("
            SELECT 
                PARAM_REAL_PATH, 
                PARAM_NAME, 
                DATA_TYPE, 
                COLUMN_AGGREGATE 
            FROM META_PROCESS_PARAM_ATTR_LINK 
            WHERE PROCESS_META_DATA_ID = $processMetaDataId 
                AND IS_INPUT = 1 
                AND IS_SHOW = 1 
                AND IS_USER_CONFIG = 1 
                AND PARAM_REAL_PATH LIKE '$groupPath.%' 
                AND (
                    (LENGTH(PARAM_REAL_PATH) - LENGTH(REPLACE(PARAM_REAL_PATH, '.', ''))) = 1 
                    OR 
                    (LENGTH(PARAM_REAL_PATH) - LENGTH(REPLACE(PARAM_REAL_PATH, '.', ''))) = 2 
                )
            ORDER BY ORDER_NUMBER ASC");
        
        if ($row) {
            return true;
        }
        return false;
    }

    public function getTransferDataParams($dmMetaDataId, $processMetaDataId) {
        
        $cache = phpFastCache();
        
        $getParams = $cache->get('dvGetParams_'.$dmMetaDataId.'_'.$processMetaDataId);
        
        if ($getParams == null) {
            
            $dmMetaDataIdPh      = $this->db->Param(0);
            $processMetaDataIdPh = $this->db->Param(1);
            
            $getParams = $this->db->GetAll("
                SELECT 
                    LOWER(TP.VIEW_FIELD_PATH) AS VIEW_FIELD_PATH, 
                    TP.INPUT_PARAM_PATH, 
                    TP.DEFAULT_VALUE, 
                    PAL.DATA_TYPE 
                FROM META_DM_TRANSFER_PROCESS TP 
                    LEFT JOIN META_PROCESS_PARAM_ATTR_LINK PAL ON PAL.PROCESS_META_DATA_ID = TP.GET_META_DATA_ID 
                        AND PAL.IS_INPUT = 1 
                        AND LOWER(PAL.PARAM_REAL_PATH) = LOWER(TP.INPUT_PARAM_PATH) 
                WHERE TP.MAIN_META_DATA_ID = $dmMetaDataIdPh 
                    AND TP.PROCESS_META_DATA_ID = $processMetaDataIdPh 
                    AND TP.INPUT_PARAM_PATH IS NOT NULL 
                    AND (TP.VIEW_FIELD_PATH IS NOT NULL OR TP.DEFAULT_VALUE IS NOT NULL) 
                GROUP BY 
                    TP.VIEW_FIELD_PATH, 
                    TP.INPUT_PARAM_PATH, 
                    TP.DEFAULT_VALUE, 
                    PAL.DATA_TYPE", 
                array($dmMetaDataId, $processMetaDataId)
            );
            
            $cache->set('dvGetParams_'.$dmMetaDataId.'_'.$processMetaDataId, $getParams, Mdwebservice::$expressionCacheTime);
        }

        return $getParams;
    }
    
    public function getRunDataDvModel($dmMetaDataId, $processMetaDataId) {
        
        $cache = phpFastCache();
        $getProcess = $cache->get('dvGet_'.$dmMetaDataId.'_'.$processMetaDataId);
        
        if ($getProcess == null) {
            
            $dmMetaDataIdPh      = $this->db->Param(0);
            $processMetaDataIdPh = $this->db->Param(1);
            
            $getProcess = $this->db->GetRow("
                SELECT 
                    MD.META_DATA_CODE AS COMMAND_NAME,  
                    TP.GET_META_DATA_ID, 
                    SL.SERVICE_LANGUAGE_CODE, 
                    LOWER(PL.SUB_TYPE) AS SUB_TYPE, 
                    LOWER(PL.ACTION_TYPE) AS ACTION_TYPE, 
                    MW.WS_URL 
                FROM META_DM_TRANSFER_PROCESS TP 
                    INNER JOIN META_BUSINESS_PROCESS_LINK PL ON PL.META_DATA_ID = TP.GET_META_DATA_ID 
                    INNER JOIN META_DATA MD ON MD.META_DATA_ID = PL.META_DATA_ID 
                    LEFT JOIN WEB_SERVICE_LANGUAGE SL ON SL.SERVICE_LANGUAGE_ID = PL.SERVICE_LANGUAGE_ID 
                    LEFT JOIN CUSTOMER_META_WS MW ON MW.SRC_META_DATA_ID = $dmMetaDataIdPh 
                        AND MW.TRG_META_DATA_ID = TP.GET_META_DATA_ID 
                WHERE TP.MAIN_META_DATA_ID = $dmMetaDataIdPh 
                    AND TP.PROCESS_META_DATA_ID = $processMetaDataIdPh 
                GROUP BY 
                    MD.META_DATA_CODE,  
                    TP.GET_META_DATA_ID, 
                    SL.SERVICE_LANGUAGE_CODE, 
                    MW.WS_URL, 
                    PL.SUB_TYPE,
                    PL.ACTION_TYPE", array($dmMetaDataId, $processMetaDataId));
            
            $cache->set('dvGet_'.$dmMetaDataId.'_'.$processMetaDataId, $getProcess, Mdwebservice::$expressionCacheTime);
        }
        
        return $getProcess;
    }

    public function getRunDataProcessModel($dmMetaDataId, $processMetaDataId, $selectedRowData, $workSpaceId = null, $workSpaceParams = null) {
        
        $getProcess = self::getRunDataDvModel($dmMetaDataId, $processMetaDataId);
        
        if (isset($getProcess['GET_META_DATA_ID'])) {

            if ($getProcess['SUB_TYPE'] == 'internal' && $getProcess['ACTION_TYPE'] == 'get') {

                $isEmpty = false;
                $paramDefaultCriteria = array();

                $getTransferDataParamsData = self::getTransferDataParams($dmMetaDataId, $processMetaDataId);
                
                foreach ($getTransferDataParamsData as $inputField) {

                    if (isset($selectedRowData[$inputField['VIEW_FIELD_PATH']])) {

                        if ($inputField['DATA_TYPE'] == 'date') {
                            $value = Date::formatter($selectedRowData[$inputField['VIEW_FIELD_PATH']], 'Y-m-d');
                        } else {
                            $value = $selectedRowData[$inputField['VIEW_FIELD_PATH']];
                        }

                        $paramDefaultCriteria[$inputField['INPUT_PARAM_PATH']][] = array(
                            'operator' => '=',
                            'operand' => $value 
                        );
                        $isEmpty = true;

                    } elseif ($inputField['DEFAULT_VALUE'] !== '') {

                        $paramDefaultCriteria[$inputField['INPUT_PARAM_PATH']][] = array(
                            'operator' => '=',
                            'operand' => $inputField['DEFAULT_VALUE']
                        );
                        $isEmpty = true;
                    }
                }

                if ($workSpaceId && $workSpaceParams) {

                    $getWorkSpaceParamMap = self::getWorkSpaceParamMap($getProcess['GET_META_DATA_ID'], $workSpaceId);

                    if ($getWorkSpaceParamMap) {
                        parse_str($workSpaceParams, $workSpaceParamArray);

                        foreach ($getWorkSpaceParamMap as $workSpaceParam) {
                            
                            if (isset($workSpaceParamArray['workSpaceParam'][$workSpaceParam['FIELD_PATH']]) 
                                && $workSpaceParamArray['workSpaceParam'][$workSpaceParam['FIELD_PATH']] != '') {
                                
                                $paramDefaultCriteria[$workSpaceParam['PARAM_PATH']][] = array(
                                    'operator' => '=',
                                    'operand' => $workSpaceParamArray['workSpaceParam'][$workSpaceParam['FIELD_PATH']]
                                );
                                $isEmpty = true;
                            }
                        }
                    }
                }

                if ($isEmpty) {

                    $param = array(
                        'processId' => $processMetaDataId, 
                        'criteria' => $paramDefaultCriteria
                    );
                    
                    if (Mdwebservice::$bpActionType == 'view') {
                        $param['_isTranslate'] = 1;
                    }

                    $result = $this->ws->run('array', $getProcess['COMMAND_NAME'], $param, $getProcess['WS_URL']);
                    
                    if ($result['status'] == 'success' && isset($result['result'])) {
                        
                        $resultData = $result['result'];
                        
                        return $resultData;
                        
                    } else {
                        
                        $writeLog = "1 \r\nInput param = ".json_encode($param)."\r\nOutput param = ".json_encode($result, JSON_UNESCAPED_UNICODE)."\r\nDate = ".Date::currentDate('Y-m-d H:i:s')."\r\n ==================== \r\n";
                        @file_put_contents(BASEPATH.'log/service_response.log', $writeLog, FILE_APPEND);
                    }
                }

            } else {

                $param = array();
                $paramList = self::groupParamsDataModel($getProcess['GET_META_DATA_ID'], null, ' AND PAL.PARENT_ID IS NULL');

                foreach ($paramList as $input) {
                    
                    $typeCode = $input['META_TYPE_CODE'];
                    
                    if ($typeCode != 'group') {
                        
                        $paramCode = strtolower($input['META_DATA_CODE']);

                        if ($typeCode == 'boolean') {
                            if (isset($selectedRowData[$paramCode])) {
                                $param[$paramCode] = '1';
                            } else {
                                $param[$paramCode] = $this->ws->convertDeParamType(Mdmetadata::setDefaultValue($input['DEFAULT_VALUE']), $typeCode);
                            }
                        } else {
                            if ($rowKey = self::getMapDVGetBusinessProcessParam($dmMetaDataId, $processMetaDataId, $getProcess['GET_META_DATA_ID'], $input['PARAM_REAL_PATH'])) {
                                $param[$paramCode] = $this->ws->convertDeParamType($selectedRowData[strtolower($rowKey)], $typeCode);
                            } else {
                                $param[$paramCode] = $this->ws->convertDeParamType(Mdmetadata::setDefaultValue($input['DEFAULT_VALUE']), $typeCode);
                            }
                        }
                    }
                }

                if ($workSpaceId && $workSpaceParams) {

                    $getWorkSpaceParamMap = self::getWorkSpaceParamMap($getProcess['GET_META_DATA_ID'], $workSpaceId);

                    if ($getWorkSpaceParamMap) {
                        parse_str($workSpaceParams, $workSpaceParamArray);

                        foreach ($getWorkSpaceParamMap as $workSpaceParam) {
                            if (isset($workSpaceParamArray['workSpaceParam'][$workSpaceParam['FIELD_PATH']]) 
                                && $workSpaceParamArray['workSpaceParam'][$workSpaceParam['FIELD_PATH']] != '') {

                                $param[$workSpaceParam['PARAM_PATH']] = $workSpaceParamArray['workSpaceParam'][$workSpaceParam['FIELD_PATH']];
                            }
                        }
                    }
                }
                
                if (Mdwebservice::$bpActionType == 'view') {
                    $param['_isTranslate'] = 1;
                }

                $result = $this->ws->run('array', $getProcess['COMMAND_NAME'], $param, $getProcess['WS_URL']);
                
                if ($result['status'] == 'success' && isset($result['result'])) {
                    return $result['result'];
                }
            }
        }

        return null;
    }

    public function getLifeCycleRelationParamModel($doBpId, $doLcDtlId, $entityId, $sourceId) {
        $param = array(
            'doLcDtlId' => $doLcDtlId,
            'doBpId' => $doBpId,
            'parameters' => array(
                'systemMetaGroupId' => $entityId,
                'criteria' => array(
                    'id' => array(
                        array(
                            'operator' => '=',
                            'operand' => $sourceId
                        )
                    )
                )
            )
        );
        $result = $this->ws->runSerializeResponse(Mdwebservice::$gfServiceAddress, 'PL_PP_001', $param);

        if ($result['status'] == 'success' && isset($result['result'])) {
            return $result['result'];
        }

        return null;
    }

    public function getMapDVGetBusinessProcessParam($dmMetaDataId, $processMetaDataId, $getProcessId, $paramRealPath, $isBasket = false) {
        $andWhere = ' AND BASKET_PATH IS NULL';
        if ($isBasket) {
            $andWhere = ' AND BASKET_PATH IS NOT NULL';
        }
        
        $row = $this->db->GetRow("
            SELECT 
                VIEW_FIELD_PATH  
            FROM META_DM_TRANSFER_PROCESS 
            WHERE MAIN_META_DATA_ID = $dmMetaDataId 
                AND PROCESS_META_DATA_ID = $processMetaDataId 
                AND GET_META_DATA_ID = $getProcessId 
                AND LOWER(INPUT_PARAM_PATH) = '" . strtolower($paramRealPath) . "' $andWhere");

        if ($row) {
            return $row['VIEW_FIELD_PATH'];
        }
        return null;
    }

    public function getMapDVBusinessProcessParam($dmMetaDataId, $processMetaDataId, $paramRealPath) {
        $row = $this->db->GetRow("
            SELECT 
                VIEW_FIELD_PATH  
            FROM META_DM_TRANSFER_PROCESS 
            WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                AND PROCESS_META_DATA_ID = ".$this->db->Param(1)." 
                AND LOWER(INPUT_PARAM_PATH) = " . $this->db->Param(2), 
            array($dmMetaDataId, $processMetaDataId, strtolower($paramRealPath))
        );

        if ($row) {
            return $row['VIEW_FIELD_PATH'];
        }
        return false;
    }

    public function getConsolidateDataProcessModel($dmMetaDataId, $processMetaDataId, $selectedRows) {
        
        $dmMetaDataIdPh      = $this->db->Param(0);
        $processMetaDataIdPh = $this->db->Param(1);
        
        $getProcess = $this->db->GetRow("
            SELECT 
                MD.META_DATA_CODE AS COMMAND_NAME,  
                TP.GET_META_DATA_ID, 
                SL.SERVICE_LANGUAGE_CODE, 
                PL.WS_URL, 
                LOWER(PL.SUB_TYPE) AS SUB_TYPE, 
                LOWER(PL.ACTION_TYPE) AS ACTION_TYPE  
            FROM META_DM_TRANSFER_PROCESS TP 
                INNER JOIN META_BUSINESS_PROCESS_LINK PL ON PL.META_DATA_ID = TP.GET_META_DATA_ID 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = PL.META_DATA_ID 
                LEFT JOIN WEB_SERVICE_LANGUAGE SL ON SL.SERVICE_LANGUAGE_ID = PL.SERVICE_LANGUAGE_ID 
            WHERE TP.MAIN_META_DATA_ID = $dmMetaDataIdPh  
                AND TP.PROCESS_META_DATA_ID = $processMetaDataIdPh 
            GROUP BY 
                MD.META_DATA_CODE,  
                TP.GET_META_DATA_ID, 
                SL.SERVICE_LANGUAGE_CODE, 
                PL.WS_URL, 
                PL.SUB_TYPE, 
                PL.ACTION_TYPE", array($dmMetaDataId, $processMetaDataId));

        if (isset($getProcess['GET_META_DATA_ID'])) {

            $isEmpty = false;
            $paramDefaultCriteria = array();

            $getTransferDataParamsData = self::getTransferDataParams($dmMetaDataId, $processMetaDataId);

            if ($getTransferDataParamsData) {

                $selectedRowData = $selectedRows[0];

                foreach ($getTransferDataParamsData as $inputField) {

                    if (isset($selectedRowData[$inputField['VIEW_FIELD_PATH']])) {
                        
                        if ($inputField['DEFAULT_VALUE'] == 'uniq-equal') {
                            
                            $paramDefaultCriteria[$inputField['INPUT_PARAM_PATH']][] = array(
                                'operator' => '=', 
                                'operand' => Input::param($selectedRows[0][$inputField['VIEW_FIELD_PATH']]) 
                            );
                            
                        } else {
                            
                            $paramDefaultCriteria[$inputField['INPUT_PARAM_PATH']][] = array(
                                'operator' => 'IN',
                                'operand' => Input::param(Arr::implode_key(',', $selectedRows, $inputField['VIEW_FIELD_PATH'], true)) 
                            );
                        }
                        
                        $isEmpty = true;

                    } elseif ($inputField['DEFAULT_VALUE'] !== '') {

                        $paramDefaultCriteria[$inputField['INPUT_PARAM_PATH']][] = array(
                            'operator' => '=',
                            'operand' => $inputField['DEFAULT_VALUE']
                        );
                        
                        $isEmpty = true;
                    }
                }

            } else {

                $paramDefaultCriteria = array(
                    'id' => array(
                        array(
                            'operator' => 'IN',
                            'operand' => Input::param(Arr::implode_key(',', $selectedRows, 'id', true))
                        )
                    )
                );

                $isEmpty = true;
            }

            if ($isEmpty) {

                $param = array(
                    'processId' => $processMetaDataId, 
                    'criteria' => $paramDefaultCriteria
                );
                
                if (Mdwebservice::$bpActionType == 'view') {
                    $param['_isTranslate'] = 1;
                }

                $result = $this->ws->caller($getProcess['SERVICE_LANGUAGE_CODE'], $getProcess['WS_URL'], $getProcess['COMMAND_NAME'], 'return', $param, 'array');

                if ($result['status'] == 'success' && isset($result['result'])) {
                    if (isset($result['result'][0])) {
                        return $result['result'][0];
                    }
                    return $result['result'];
                }
            }
        }

        return null;
    }
    
    public function showBannerModel($metaDataId, $positionType, $isShow, $data = array()) {
        $html = '';

        if ($isShow) {
            if (!$data) {
                $data = $this->db->GetAll("
                    SELECT 
                        MPCM.WEB_URL,
                        MPCM.URL_TARGET,
                        LOWER(MPCM.POSITION_TYPE) AS POSITION_TYPE, 
                        MPC.CONTENT_DATA, 
                        MPC.VIDEO_URL, 
                        LOWER(MPC.CONTENT_TYPE) AS CONTENT_TYPE
                    FROM META_PROCESS_CONTENT_MAP MPCM
                        INNER JOIN META_PROCESS_CONTENT MPC ON MPCM.CONTENT_ID = MPC.CONTENT_ID
                    WHERE MPCM.MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                        AND LOWER(MPCM.POSITION_TYPE) = ".$this->db->Param(1)." 
                    ORDER BY MPCM.ORDER_NUM ASC", array($metaDataId, $positionType));
            }

            if ($data) {

                $jsonConfig = array();
                foreach ($data as $key => $row) {
                    if (issetParam($row['JSON_CONFIG'])) {
                        $jsonConfig = json_decode($row['JSON_CONFIG'], true);
                    }
                }

                $attrStyle = 'style="';
                if (issetParamArray($jsonConfig)) {
                    foreach ($jsonConfig as $key => $row) {
                        $attrStyle .= $key . ': ' . $row . '; ';
                    }
                }
                $attrStyle .= '"';

                $html .= '<div class="bp-banner banner-position-' . $positionType . '" '. $attrStyle .'>';
                $html .= '<div class="bp-banner-spacer">';
                $html .= '<div class="bp-banner-wrap">';

                if (count($data) > 1) {

                    $bannerItem = '';
                    $bannerNav = '';

                    foreach ($data as $k => $row) {

                        $webUrl = 'javascript:;';
                        $urlTarget = '_self';

                        $active = "";
                        if ($k == 0) {
                            $active = "active";
                        }

                        if ($row['WEB_URL'] != '') {
                            $webUrl = $row['WEB_URL'];
                            $urlTarget = $row['URL_TARGET'];
                        }

                        $bannerPath = 'assets/custom/addon/img/process_content/photo/' . $row['CONTENT_DATA'];
                        $bannerPath = (strpos($row['CONTENT_DATA'], UPLOADPATH) !== false) ? $row['CONTENT_DATA'] : $bannerPath;
                        
                        $bannerItem .= '<div class="' . $active . ' item">
                                        <a href="' . $webUrl . '" target="' . $urlTarget . '"><img src="' . $bannerPath . '" class="img-fluid"></a>';
                        $bannerItem .= '</div>';
                        $bannerNav .= '<li data-target="#bp-carousel-' . $metaDataId . '" data-slide-to="' . $k . '" class="' . $active . '"></li>';
                    }

                    $html .= '<div id="bp-carousel-' . $metaDataId . '" class="carousel image-carousel slide">
                        <div class="carousel-inner">
                            ' . $bannerItem . '
                        </div>
                        <a class="carousel-control left" href="#bp-carousel-' . $metaDataId . '" data-slide="prev">
                            <i class="m-icon-big-swapleft m-icon-white"></i>
                        </a>
                        <a class="carousel-control right" href="#bp-carousel-' . $metaDataId . '" data-slide="next">
                            <i class="m-icon-big-swapright m-icon-white"></i>
                        </a>
                        <ol class="carousel-indicators">
                            ' . $bannerNav . '
                        </ol>
                    </div>';

                } else {

                    $webUrl = 'javascript:;';
                    $urlTarget = '_self';

                    if ($data[0]['WEB_URL'] != '') {
                        $webUrl = $data[0]['WEB_URL'];
                        $urlTarget = $data[0]['URL_TARGET'];
                    }
                    if ($data[0]['CONTENT_TYPE'] == 'photo') {

                        $bannerPath = 'assets/custom/addon/img/process_content/photo/' . $data[0]['CONTENT_DATA'];
                        $bannerPath = (strpos($data[0]['CONTENT_DATA'], UPLOADPATH) !== false) ? $data[0]['CONTENT_DATA'] : $bannerPath;

                        $html .= '<a href="' . $webUrl . '" target="' . $urlTarget . '"><img src="' . $bannerPath . '"></a>';
                    }
                }

                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
            }
        }

        return $html;
    }

    public function getProcessLookupParamFieldMap($processMetaDataId, $parentId, $paramRealPath) {
        
        $processMetaDataIdPh = $this->db->Param(0);
        $parentIdPh          = $this->db->Param(1);
        $paramRealPathPh     = $this->db->Param(2);
        
        $bindVars = array(
            $this->db->addQ($processMetaDataId), 
            $this->db->addQ($parentId), 
            $this->db->addQ(strtolower($paramRealPath))
        );
        
        $data = $this->db->GetAll("
            SELECT 
                LOWER(LM.PARAM_FIELD_PATH) AS PARAM_FIELD_PATH, 
                LOWER(LM.LOOKUP_FIELD_PATH) AS LOOKUP_FIELD_PATH, 
                PAL.IS_SHOW, 
                PAL.LOOKUP_META_DATA_ID, 
                PAL.LOOKUP_TYPE, 
                PAL.CHOOSE_TYPE 
            FROM META_PROCESS_LOOKUP_MAP LM 
                INNER JOIN META_PROCESS_PARAM_ATTR_LINK PAL ON PAL.PROCESS_META_DATA_ID = $processMetaDataIdPh 
                    AND PAL.IS_INPUT = 1 
                    AND PAL.PARENT_ID = $parentIdPh 
                    AND LOWER(PAL.PARAM_NAME) = LOWER(PARAM_FIELD_PATH)    
            WHERE LM.PROCESS_META_DATA_ID = $processMetaDataIdPh 
                AND LOWER(LM.FIELD_PATH) = $paramRealPathPh  
                AND (LM.IS_KEY_LOOKUP = 0 OR LM.IS_KEY_LOOKUP IS NULL)", $bindVars);

        return $data;
    }

    public function getProcessKeyLookupParamFieldMap($processMetaDataId, $paramRealPath) {
        
        $data = $this->db->GetAll("
            SELECT 
                LOWER(PARAM_FIELD_PATH) AS PARAM_FIELD_PATH, 
                LOWER(LOOKUP_FIELD_PATH) AS LOOKUP_FIELD_PATH 
            FROM META_PROCESS_LOOKUP_MAP 
            WHERE PROCESS_META_DATA_ID = ".$this->db->Param(0)." 
                AND LOWER(FIELD_PATH) = ".$this->db->Param(1)." 
                AND IS_KEY_LOOKUP = 1", 
            array($processMetaDataId, strtolower($paramRealPath))
        );

        return $data;
    }
    
    public function getProcessLookupParamFields($processMetaDataId, $rowId) {
        
        $processMetaDataIdPh = $this->db->Param(0);
        $rowIdPh = $this->db->Param(1);
        
        $data = $this->db->GetAll("
            SELECT 
                PAL.LOOKUP_META_DATA_ID, 
                LOWER(PAL.PARAM_NAME) AS PARAM_NAME, 
                (
                    SELECT 
                        ".$this->db->listAgg('PARAM_PATH', '|', 'PARAM_PATH')."  
                    FROM META_GROUP_PARAM_CONFIG 
                    WHERE MAIN_PROCESS_META_DATA_ID = $processMetaDataIdPh  
                        AND LOOKUP_META_DATA_ID IS NOT NULL 
                        AND (IS_GROUP = 0 OR IS_GROUP IS NULL) 
                        AND LOWER(FIELD_PATH) = LOWER(PAL.PARAM_REAL_PATH)  
                ) AS GROUP_CONFIG_PARAM_PATH,
                (
                    SELECT 
                        ".$this->db->listAgg('PARAM_META_DATA_CODE', '|', 'PARAM_PATH')."  
                    FROM META_GROUP_PARAM_CONFIG  
                    WHERE MAIN_PROCESS_META_DATA_ID = $processMetaDataIdPh  
                        AND LOOKUP_META_DATA_ID IS NOT NULL 
                        AND (IS_GROUP = 0 OR IS_GROUP IS NULL) 
                        AND LOWER(FIELD_PATH) = LOWER(PAL.PARAM_REAL_PATH)    
                ) AS GROUP_CONFIG_LOOKUP_PATH, 
                LOWER(PAL.LOOKUP_TYPE) AS LOOKUP_TYPE, 
                LOWER(PAL.DISPLAY_FIELD) AS DISPLAY_FIELD, 
                LOWER(PAL.VALUE_FIELD) AS VALUE_FIELD 
            FROM META_PROCESS_PARAM_ATTR_LINK PAL 
            WHERE PAL.PROCESS_META_DATA_ID = $processMetaDataIdPh 
                AND PAL.PARENT_ID = $rowIdPh 
                AND PAL.IS_SHOW = 1 
                AND PAL.LOOKUP_META_DATA_ID IS NOT NULL 
                AND PAL.RECORD_TYPE IS NULL 
                AND PAL.LOOKUP_TYPE IS NOT NULL 
                AND PAL.CHOOSE_TYPE IS NOT NULL", 
            array($processMetaDataId, $rowId)
        ); 
        
        $array = array();
        
        if ($data) {
            foreach ($data as $row) {
                $array[$row['PARAM_NAME']] = array(
                    'lookupMetaDataId' => $row['LOOKUP_META_DATA_ID'], 
                    'lookupType' => $row['LOOKUP_TYPE'], 
                    'displayField' => $row['DISPLAY_FIELD'], 
                    'valueField' => $row['VALUE_FIELD'], 
                    'groupConfigParamPath' => $row['GROUP_CONFIG_PARAM_PATH'], 
                    'groupConfigLookupPath' => $row['GROUP_CONFIG_LOOKUP_PATH'] 
                );
            }
        }

        return $array;
    }
    
    public function getLookupRowDatas($lookupFieldConfig, $headerParamsArr, $selectedRows, $lookupField, $fieldResult = 'id') {
        
        $array = $paramCriteria = array();
        
        if (isset($selectedRows[0]) && is_array($selectedRows[0]) && !array_key_exists($lookupField, $selectedRows[0])) {
            return $array;
        }
        
        if (count($selectedRows) > 1) {
            $isMulti = true;
            $criteriaValue = Arr::implode_key(',', $selectedRows, $lookupField, true);
        } else {
            $criteriaValue = issetParam($selectedRows[0][$lookupField]);
        }
        
        if ($criteriaValue === '' || $criteriaValue === null) {
            return $array;
        }
        
        $this->load->model('mddatamodel', 'middleware/models/');
        
        $lookupMetaDataId = $lookupFieldConfig['lookupMetaDataId'];
        
        $param = array(
            'systemMetaGroupId' => $lookupMetaDataId,
            'showQuery' => 0, 
            'ignorePermission' => 1 
        );
        
        $getIdCodeName = $this->model->getCodeNameFieldNameModel($lookupMetaDataId);
        
        if ($lookupFieldConfig['lookupType'] == 'combo') {
            
            $id = ($lookupFieldConfig['valueField']) ? $lookupFieldConfig['valueField'] : ($getIdCodeName['id'] ? $getIdCodeName['id'] : 'id');
            $name = ($lookupFieldConfig['displayField']) ? $lookupFieldConfig['displayField'] : ($getIdCodeName['name'] ? $getIdCodeName['name'] : 'name');
            $code = $getIdCodeName['code'] ? $getIdCodeName['code'] : $name;
            
        } else {
            
            $id = $getIdCodeName['id'] ? $getIdCodeName['id'] : 'id';
            $code = $getIdCodeName['code'] ? $getIdCodeName['code'] : $id;
            $name = $getIdCodeName['name'] ? $getIdCodeName['name'] : $code;
        }
        
        if ($fieldResult == 'id') {
            
            if (isset($isMulti)) {
                $paramCriteria[$id][] = array(
                    'operator' => 'IN', 
                    'operand'  => $criteriaValue 
                );
            } else {
                $paramCriteria[$id][] = array(
                    'operator' => '=', 
                    'operand'  => $criteriaValue  
                );
            }
            
        } elseif ($fieldResult == 'name') {
            
            if (isset($isMulti)) {
                $paramCriteria[$name][] = array(
                    'operator' => 'IN', 
                    'operand'  => $criteriaValue 
                );
            } else {
                $paramCriteria[$name][] = array(
                    'operator' => '=', 
                    'operand'  => $criteriaValue  
                );
            }
            
        } else {
            
            if (isset($isMulti)) {
                $paramCriteria[$code][] = array(
                    'operator' => 'IN', 
                    'operand'  => $criteriaValue 
                );
            } else {
                $paramCriteria[$code][] = array(
                    'operator' => '=', 
                    'operand'  => $criteriaValue  
                );
            }
        }
        
        $groupConfigLookupPathArr = explode('|', $lookupFieldConfig['groupConfigLookupPath']);
        $groupConfigParamPathArr  = explode('|', $lookupFieldConfig['groupConfigParamPath']);
        
        if ($groupConfigLookupPathArr && $groupConfigParamPathArr) {
            
            foreach ($groupConfigLookupPathArr as $k => $groupConfigLookupPath) {

                $processField = isset($groupConfigParamPathArr[$k]) ? strtolower($groupConfigParamPathArr[$k]) : '';

                if (isset($headerParamsArr[$processField])) {
                    $paramCriteria[$groupConfigLookupPath][] = array(
                        'operator' => '=',
                        'operand'  => $headerParamsArr[$processField]
                    );
                }
            }
        }
        
        $param['criteria'] = $paramCriteria;
        
        $data = $this->ws->runArrayResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($data['status'] == 'success' && isset($data['result'])) {
            
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);
            
            $result = $data['result'];
            
            if ($fieldResult == 'id') {
                
                foreach ($result as $row) {
                
                    $array[$row[$id]] = array(
                        'id'      => $row[$id], 
                        'code'    => $row[$code], 
                        'name'    => $row[$name], 
                        'rowdata' => $row
                    );
                }
                
            } elseif ($fieldResult == 'name') {
                
                foreach ($result as $row) {
                
                    $array[Str::lower($row[$name])] = array(
                        'id'      => $row[$id], 
                        'code'    => $row[$code], 
                        'name'    => $row[$name], 
                        'rowdata' => $row
                    );
                }
                
            } else {
                
                foreach ($result as $row) {
                
                    $array[Str::lower($row[$code])] = array(
                        'id'      => $row[$id], 
                        'code'    => $row[$code], 
                        'name'    => $row[$name], 
                        'rowdata' => $row
                    );
                }
            }
        }
        
        $this->load->model('mdwebservice', 'middleware/models/');
        
        return $array;
    }
    
    public function generateDataProcessLookupMapModel($postData) {

        $processMetaDataId = Input::param($postData['processMetaDataId']);
        $parentId = $postData['parentId'];
        $paramRealPath = Input::param($postData['paramRealPath']);
        $selectedRows = $postData['selectedRows'];

        $array = $lookupRowDatas = array();
        $paramFieldData = self::getProcessLookupParamFieldMap($processMetaDataId, $parentId, $paramRealPath);        
        $lookupFieldData = self::getProcessLookupParamFields($processMetaDataId, $parentId);
        
        if (isset($postData['pageSize'])) {
            
            $groupPath = $postData['groupPath'];
            $groupFieldPathLower = strtolower($groupPath);
            
            $lookupFieldData = self::getProcessLookupParamFields($processMetaDataId, $postData['rowId']);
            
            $detailHtmlData = Mdcommon::getArrayProcessDetailParamsArray($processMetaDataId, $postData['rowId'], $postData['uniqId'], $postData['pageSize'], $groupPath, $groupFieldPathLower);
            $rowData = $detailHtmlData['rowData'];
            
            $headerParamsArr = isset($postData['headerData']) ? $postData['headerData'] : array();
            $lookupRowDatas = array();
            
            if ($lookupFieldData) {
                foreach ($paramFieldData as $field) {
                    if (isset($lookupFieldData[$field['PARAM_FIELD_PATH']])) {
                        $lookupRowDatas[$field['PARAM_FIELD_PATH']] = self::getLookupRowDatas($lookupFieldData[$field['PARAM_FIELD_PATH']], $headerParamsArr, $selectedRows, $field['LOOKUP_FIELD_PATH']);
                    }
                }
            }
            
            $fields = '';
            
            foreach ($paramFieldData as $field) {
                
                if (isset($lookupFieldData[$field['PARAM_FIELD_PATH']])) {
                    $fields .= '$arr[\''.$field['PARAM_FIELD_PATH'].'\'] = issetParam($lookupRowDatas[\''.$field['PARAM_FIELD_PATH'].'\'][$field[\''.$field['LOOKUP_FIELD_PATH'].'\']]); ';
                } else {
                    $fields .= '$arr[\''.$field['PARAM_FIELD_PATH'].'\'] = $field[\''.$field['LOOKUP_FIELD_PATH'].'\']; ';
                }
                
                if (array_key_exists($field['PARAM_FIELD_PATH'], $rowData)) {
                    unset($rowData[$field['PARAM_FIELD_PATH']]);
                }
            }
            
            foreach ($rowData as $rk => $rv) {
                if (is_array($rv)) {
                    foreach ($rv as $rvKey => $rvVal) {
                        $fields .= '$arr[\''.$rk.'\'][\''.$rvKey.'\'] = \''.$rvVal.'\'; ';
                    }
                } else {
                    $fields .= '$arr[\''.$rk.'\'] = \''.$rv.'\'; ';
                }
            }
            
            if (isset($postData['ignoreCriteriaRows']) && isset($postData['cacheStr'])) {
                
                $cacheStr = $postData['cacheStr'];
                $ignoreCriteriaRows = explode('==', strtolower($postData['ignoreCriteriaRows']));
                $processPath = $ignoreCriteriaRows[0];
                $dvPath = $ignoreCriteriaRows[1];
                
                if (array_key_exists($dvPath, $selectedRows[0])) {
                    foreach ($selectedRows as $rowKey => $selectedRow) {
                        if (strpos($cacheStr, "'$processPath' => '".$selectedRow[$dvPath]."',") !== false) {
                            unset($selectedRows[$rowKey]);
                        }
                    }
                }
            }

            eval('$array = array_map(function($field) use ($lookupRowDatas) { '.$fields.' return $arr; }, $selectedRows);');
            
        } else {
            
            if (!isset($postData['headerData']) && isset($postData['headerParams'])) {
                parse_str($postData['headerParams'], $headerParamsArr);
                $postData['headerData'] = isset($headerParamsArr['param']) ? Arr::changeKeyLower($headerParamsArr['param']) : array();
            }
            
            if ($lookupFieldData && isset($postData['headerData'])) {
                    
                if (is_array($postData['headerData'])) {
                    $arr = $postData['headerData'];
                } else {
                    parse_str($postData['headerData'], $arr);
                }

                $headerParamsArr = isset($arr['param']) ? $arr['param'] : $arr;
                
                foreach ($paramFieldData as $field) {
                    if (isset($lookupFieldData[$field['PARAM_FIELD_PATH']])) {
                        $lookupIdCodeNameRowData = self::getLookupRowDatas($lookupFieldData[$field['PARAM_FIELD_PATH']], $headerParamsArr, $selectedRows, $field['LOOKUP_FIELD_PATH']);
                        $lookupRowDatas[$field['PARAM_FIELD_PATH']] = $lookupIdCodeNameRowData;
                    }
                }
            }
            
            $fields = 'array(';
            
            foreach ($paramFieldData as $field) {
                if (isset($lookupFieldData[$field['PARAM_FIELD_PATH']])) {
                    $fields .= '\''.$field['PARAM_FIELD_PATH'].'\' => issetParam($lookupRowDatas[\''.$field['PARAM_FIELD_PATH'].'\'][$field[\''.$field['LOOKUP_FIELD_PATH'].'\']]), ';
                } else {
                    $fields .= '\''.$field['PARAM_FIELD_PATH'].'\' => issetParam($field[\''.$field['LOOKUP_FIELD_PATH'].'\']), ';
                }
            }
            
            $fields .= ')';

            eval('$array = array_map(function($field) use ($lookupRowDatas) { return '.$fields.'; }, $selectedRows);');
        }

        return array(strtolower($paramRealPath) => $array);
    }

    public function generateDataProcessKeyLookupMapModel($postData) {

        $processMetaDataId = Input::param($postData['processMetaDataId']);
        $paramRealPath = Input::param($postData['paramRealPath']);
        $selectedRows = isset($postData['selectedRows']) ? $postData['selectedRows'] : array();

        $array = $lookupRowDatas = array();
        
        $paramFieldData = self::getProcessKeyLookupParamFieldMap($processMetaDataId, $paramRealPath);
        $lookupFieldData = self::getProcessLookupParamFields($processMetaDataId, $postData['rowId']);
        
        if (isset($postData['pageSize'])) {
            
            $groupPath = $postData['groupPath'];
            $groupFieldPathLower = strtolower($groupPath);
            
            $detailHtmlData = Mdcommon::getArrayProcessDetailParamsArray($processMetaDataId, $postData['rowId'], $postData['uniqId'], $postData['pageSize'], $groupPath, $groupFieldPathLower);
            $rowData = $detailHtmlData['rowData'];
            
            if ($lookupFieldData) {
                
                parse_str($_POST['headerParams'], $headerParamsArr);
                $headerParamsArr = isset($headerParamsArr['param']) ? Arr::changeKeyLower($headerParamsArr['param']) : array();
            
                foreach ($paramFieldData as $field) {
                    if (isset($lookupFieldData[$field['PARAM_FIELD_PATH']])) {
                        $lookupIdCodeNameRowData = self::getLookupRowDatas($lookupFieldData[$field['PARAM_FIELD_PATH']], $headerParamsArr, $selectedRows, $field['LOOKUP_FIELD_PATH']);
                        //if ($lookupIdCodeNameRowData) {
                            $lookupRowDatas[$field['PARAM_FIELD_PATH']] = $lookupIdCodeNameRowData;
                        //}
                    }
                }
            }
            
            $fields = '';
            
            foreach ($paramFieldData as $field) {
                
                if (isset($lookupFieldData[$field['PARAM_FIELD_PATH']])) {
                    $fields .= '$arr[\''.$field['PARAM_FIELD_PATH'].'\'] = issetParam($lookupRowDatas[\''.$field['PARAM_FIELD_PATH'].'\'][$field[\''.$field['LOOKUP_FIELD_PATH'].'\']]); ';
                } else {
                    $fields .= '$arr[\''.$field['PARAM_FIELD_PATH'].'\'] = $field[\''.$field['LOOKUP_FIELD_PATH'].'\']; ';
                }
                
                if (array_key_exists($field['PARAM_FIELD_PATH'], $rowData)) {
                    unset($rowData[$field['PARAM_FIELD_PATH']]);
                }
            }
            
            foreach ($rowData as $rk => $rv) {
                if (is_array($rv)) continue;
                
                $fields .= '$arr[\''.$rk.'\'] = \''.$rv.'\'; ';
            }
            
            if (isset($postData['ignoreCriteriaRows']) && isset($postData['cacheStr'])) {
                
                $cacheStr = Str::remove_doublewhitespace(Str::removeNL($postData['cacheStr']));
                $ignoreCriteriaRows = explode('==', strtolower($postData['ignoreCriteriaRows']));
                $processPath = $ignoreCriteriaRows[0];
                $dvPath = $ignoreCriteriaRows[1];
                
                if (array_key_exists($dvPath, $selectedRows[0])) {
                    foreach ($selectedRows as $rowKey => $selectedRow) {
                        
                        if (strpos($cacheStr, "'$processPath' => '".$selectedRow[$dvPath]."',") !== false 
                            || strpos($cacheStr, "'$processPath' => array ( 'id' => '".$selectedRow[$dvPath]."',") !== false) {
                            unset($selectedRows[$rowKey]);
                        }
                    }
                }
            }

            eval('$array = array_map(function($field) use ($lookupRowDatas) { '.$fields.' return $arr; }, $selectedRows);');
            
        } else {
            
            if ($lookupFieldData) {
                
                parse_str($_POST['headerParams'], $headerParamsArr);
                $headerParamsArr = isset($headerParamsArr['param']) ? Arr::changeKeyLower($headerParamsArr['param']) : array();
            
                foreach ($paramFieldData as $field) {
                    if (isset($lookupFieldData[$field['PARAM_FIELD_PATH']])) {
                        $lookupIdCodeNameRowData = self::getLookupRowDatas($lookupFieldData[$field['PARAM_FIELD_PATH']], $headerParamsArr, $selectedRows, $field['LOOKUP_FIELD_PATH']);
                        $lookupRowDatas[$field['PARAM_FIELD_PATH']] = $lookupIdCodeNameRowData;
                    }
                }
            }
            
            $fields = 'array(';
            
            foreach ($paramFieldData as $field) {
                if (isset($lookupFieldData[$field['PARAM_FIELD_PATH']])) {
                    $fields .= '\''.$field['PARAM_FIELD_PATH'].'\' => issetParam($lookupRowDatas[\''.$field['PARAM_FIELD_PATH'].'\'][$field[\''.$field['LOOKUP_FIELD_PATH'].'\']]), ';
                } else {
                    $fields .= '\''.$field['PARAM_FIELD_PATH'].'\' => issetParam($field[\''.$field['LOOKUP_FIELD_PATH'].'\']), ';
                }
            }
            
            $fields .= ')';
        
            eval('$array = array_map(function($field) use ($lookupRowDatas) { return '.$fields.'; }, $selectedRows);');
        }

        return array(strtolower($paramRealPath) => $array);
    }

    public function isFirstLevelProcessModel($processMetaDataId, $paramRealPath) {
        
        $processMetaDataIdPh = $this->db->Param(0);
        $paramRealPathPh     = $this->db->Param(1);
        
        $bindVars = array($this->db->addQ($processMetaDataId), $this->db->addQ(strtolower($paramRealPath)));
        
        $row = $this->db->GetRow("
            SELECT 
                PAL.ID, 
                PAL.IS_SHOW_ADD, 
                PAL.IS_SHOW_DELETE, 
                PAL.IS_SHOW_MULTIPLE, 
                PAL.DTL_THEME, 
                PAL.JSON_CONFIG,
                MW.CODE AS WIDGET_CODE 
            FROM META_PROCESS_PARAM_ATTR_LINK PAL 
                LEFT JOIN META_WIDGET MW ON MW.ID = PAL.DTL_THEME 
            WHERE PAL.PROCESS_META_DATA_ID = $processMetaDataIdPh 
                AND PAL.IS_INPUT = 1 
                AND PAL.PARENT_ID IS NULL 
                AND LOWER(PAL.PARAM_REAL_PATH) = $paramRealPathPh", $bindVars);

        if ($row) {
            return $row;
        }
        return false;
    }

    public function isDepthLevelProcessModel($processMetaDataId, $paramRealPath) {
        
        $processMetaDataIdPh = $this->db->Param(0);
        $paramRealPathPh     = $this->db->Param(1);
        
        $bindVars = array(
            $this->db->addQ($processMetaDataId), 
            $this->db->addQ(strtolower($paramRealPath))
        );
        
        $row = $this->db->GetRow("
            SELECT 
                PAL.ID,
                PAL.IS_SHOW_ADD,
                PAL.IS_SHOW_DELETE,
                PAL.IS_SHOW_MULTIPLE, 
                PAL.DTL_THEME, 
                PAL.JSON_CONFIG, 
                MW.CODE AS WIDGET_CODE 
            FROM META_PROCESS_PARAM_ATTR_LINK PAL 
                LEFT JOIN META_WIDGET MW ON MW.ID = PAL.DTL_THEME 
            WHERE PAL.PROCESS_META_DATA_ID = $processMetaDataIdPh
                AND PAL.IS_INPUT = 1 
                AND LOWER(PAL.PARAM_REAL_PATH) = $paramRealPathPh", $bindVars);

        if ($row) {
            return $row;
        }
        return false;
    }

    public function bpLinkedGroupModel($processMetaDataId, $parentId, $groupFieldPath, $headerData, $postData) {
        
        $isResult = false;
        $ignoreDvFilter = Input::numeric('ignoreDvFilter');
        
        if (!$ignoreDvFilter) {
            
            $processMetaDataIdPh = $this->db->Param(0);
            $groupFieldPathPh    = $this->db->Param(1);

            $bindVars = array($this->db->addQ($processMetaDataId), $this->db->addQ(strtolower($groupFieldPath)));

            $data = $this->db->GetAll("
                SELECT 
                    LOWER(PARAM_PATH) AS PARAM_PATH, 
                    DEFAULT_VALUE, 
                    LOOKUP_META_DATA_ID, 
                    PARAM_META_DATA_CODE 
                FROM META_GROUP_PARAM_CONFIG 
                WHERE MAIN_PROCESS_META_DATA_ID = $processMetaDataIdPh 
                    AND IS_GROUP = 1 
                    AND (IS_KEY_LOOKUP = 0 OR IS_KEY_LOOKUP IS NULL) 
                    AND LOOKUP_META_DATA_ID IS NOT NULL 
                    AND LOWER(FIELD_PATH) = $groupFieldPathPh", $bindVars);

            if ($data) {

                if (is_array($headerData)) {
                    $headerDataArr = $headerData;
                } else {
                    $headerData = str_replace('¶', 'param', $headerData);
                    parse_str(urldecode($headerData), $headerDataArr);
                }

                $headerDataArr = isset($headerDataArr['param']) ? Arr::changeKeyLower($headerDataArr['param']) : $headerDataArr;

                $lookupMetaDataId = $data[0]['LOOKUP_META_DATA_ID'];

                $paramFilter = array();

                foreach ($data as $row) {

                    $value = '';
                    $isValue = false;

                    if (isset($headerDataArr[$row['PARAM_PATH']])) {

                        if (is_array($headerDataArr[$row['PARAM_PATH']])) {

                            $value = Arr::implode_r(',', $headerDataArr[$row['PARAM_PATH']], true);

                        } else {
                            $value = trim($headerDataArr[$row['PARAM_PATH']]);
                        }

                        if ($value == '' && (trim($row['DEFAULT_VALUE']) == 'nullval' || trim($row['DEFAULT_VALUE']) == 'nulval')) {
                            $isValue = true;
                        } 

                    } else {

                        if ($row['DEFAULT_VALUE'] != '') {
                            $value = trim($row['DEFAULT_VALUE']);
                            if ($value == 'nullval') {
                                $value = '';
                                $isValue = true;
                            }
                        }
                    }

                    if ($value != '' || $isValue) {

                        $operator = '=';

                        if (strpos($value, ',') !== false) {
                            $operator = 'IN';
                        }

                        $paramFilter[$row['PARAM_META_DATA_CODE']][] = array(
                            'operator' => $operator,
                            'operand' => $value
                        );
                    }
                }

                if (count($paramFilter) > 0) {

                    $param = array(
                        'systemMetaGroupId' => $lookupMetaDataId,
                        'showQuery' => 0, 
                        'ignorePermission' => 1,  
                        'lookupGrid' => 1, 
                        'criteria' => $paramFilter
                    );

                    $this->load->model('mdobject', 'middleware/models/');
                    $dataGridOptionData = $this->model->getDVGridOptionsModel($lookupMetaDataId);

                    if (isset($dataGridOptionData['SORTNAME']) && $dataGridOptionData['SORTNAME'] != '') {
                        $param['paging']['sortColumnNames'] = array(
                            $dataGridOptionData['SORTNAME'] => array(
                                'sortType' => $dataGridOptionData['SORTORDER']
                            )
                        );
                    } 

                    $dataResult = $this->ws->runArrayResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

                    if ($dataResult['status'] == 'success' && isset($dataResult['result'])) {

                        unset($dataResult['result']['aggregatecolumns']);

                        if (array_key_exists('paging', $dataResult['result'])) {
                            unset($dataResult['result']['paging']);
                        }

                        $isResult = true;
                    }
                }
            }
            
        } else {
            
            $lookupMetaDataId = Input::numeric('lookupMetaId');
            
            if ($lookupMetaDataId) {
                
                $param = array(
                    'systemMetaGroupId' => $lookupMetaDataId,
                    'showQuery' => 0, 
                    'ignorePermission' => 1,  
                    'lookupGrid' => 1
                );

                $this->load->model('mdobject', 'middleware/models/');
                $dataGridOptionData = $this->model->getDVGridOptionsModel($lookupMetaDataId);

                if (isset($dataGridOptionData['SORTNAME']) && $dataGridOptionData['SORTNAME'] != '') {
                    $param['paging']['sortColumnNames'] = array(
                        $dataGridOptionData['SORTNAME'] => array(
                            'sortType' => $dataGridOptionData['SORTORDER']
                        )
                    );
                } 

                $dataResult = $this->ws->runArrayResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

                if ($dataResult['status'] == 'success' && isset($dataResult['result'])) {

                    unset($dataResult['result']['aggregatecolumns']);

                    if (array_key_exists('paging', $dataResult['result'])) {
                        unset($dataResult['result']['paging']);
                    }

                    $isResult = true;
                }
            }
        }
        
        if ($isResult) {
            
            if (is_array($headerData)) {
                $headerDataArr = $headerData;
            } else {
                $headerData = str_replace('¶', 'param', $headerData);
                parse_str(urldecode($headerData), $headerDataArr);
            }

            $headerDataArr = isset($headerDataArr['param']) ? Arr::changeKeyLower($headerDataArr['param']) : $headerDataArr;
                
            $responseData = array_merge($postData, 
                array(
                    'lookupMetaDataId' => $lookupMetaDataId,
                    'processMetaDataId' => $processMetaDataId,
                    'parentId' => $parentId, 
                    'paramRealPath' => $groupFieldPath,
                    'selectedRows' => $dataResult['result'], 
                    'headerData' => $headerDataArr
                )
            );

            return self::generateDataProcessLookupMapModel($responseData);
        }

        return false;
    }

    public function fillGroupByDvModel($dataViewId, $groupPath, $inputParamsData, $mappingParamsData) {

        $paramFilter = array();

        if ($inputParamsData) {
            
            foreach ($inputParamsData as $row) {
                
                $value = issetParam($row['value']);
                
                if ($value != '') {
                    
                    if (is_array($value)) {
                        $paramFilter[$row['inputPath']][] = array(
                            'operator' => 'IN',
                            'operand' => Arr::implode_r(',', $value, true)
                        );
                    } else {
                        $paramFilter[$row['inputPath']][] = array(
                            'operator' => '=',
                            'operand' => $value
                        );
                    }
                }
            }
        }

        $param = array(
            'systemMetaGroupId' => $dataViewId,
            'showQuery' => 0, 
            'ignorePermission' => 1,  
            'lookupGrid' => 1, 
            'criteria' => $paramFilter
        );
        
        $dataResult = $this->ws->runArrayResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
        
        if ($dataResult['status'] == 'success' && isset($dataResult['result'])) {

            unset($dataResult['result']['aggregatecolumns']);
            unset($dataResult['result']['paging']);

            if (!empty($dataResult['result'])) {

                $result = $dataResult['result'];
                $array = array();

                foreach ($result as $k => $row) {

                    foreach ($mappingParamsData as $map) {
                        
                        if (isset($map['processPath'])) {
                            
                            $explode = explode('.', $map['processPath']);
                            $processPath = array_pop($explode);
                            $array[$k][$processPath] = issetParam($row[$map['dataviewPath']]);
                        
                        } else {
                            return null;
                        }
                    }
                }

                return array(strtolower($groupPath) => $array);
            }
        }

        return null;
    }
    
    public function fillGroupByDataObjModel($groupPath, $dataObj, $mappingParamsData) {

        $array = array();
        $loop = '';
        
        foreach ($mappingParamsData as $map) {
            
            $explode = explode('.', $map['processPath']);
            $processPath = array_pop($explode);
            $dataviewPath = $map['dataviewPath'];
            
            if (strpos($dataviewPath, '.') !== false) {
                
                $dataviewPathArr = explode('.', $dataviewPath);
                $bracketsKey = '';

                foreach ($dataviewPathArr as $rk => $rv) {
                    $bracketsKey .= '[\''.$rv.'\']';
                }
                
                $setValue = '$row' . $bracketsKey;
                                    
            } else {
                $setValue = '$row[\''.$dataviewPath.'\']';
            }
            
            if (strpos($dataviewPath, '(') !== false) {
                preg_match_all('/\((.*?)\)/i', $dataviewPath, $dvPathMatches);
                
                if (count($dvPathMatches[0]) > 0) {
                    foreach ($dvPathMatches[1] as $ek => $ev) {
                        
                        $setValue = str_replace($dvPathMatches[0][$ek], '$row[\''.$dataviewPath.'\']', $dataviewPath);
                        
                        if (strpos($ev, 'dateformat') !== false) {
                            
                            preg_match_all('/dateformat\[(.*?)\]/i', $ev, $dvFormatMatches);
                            
                            if (count($dvFormatMatches[0]) > 0) {
                                
                                foreach ($dvFormatMatches[1] as $em => $mv) {
                                    $dataviewPath = str_replace('()', '', str_replace($ev, '', $dataviewPath));
                                    $setValue = str_replace($dataviewPath, 'Date::formatter($row[\''.$dataviewPath.'\'], \''.$mv.'\')', $dataviewPath);
                                    $setValue = str_replace('y-m-d h:i:s', 'Y-m-d H:i:s', $setValue);
                                }
                            }
                        }
                    }
                }
            }
            
            $loop .= '$array[$k][\''.$processPath.'\'] = '.$setValue.'; ';
        }
        
        if ($dataObj) {
            $firstRow = $dataObj[0];
            $firstKey = array_key_first($firstRow);
            if (strtoupper($firstKey) == $firstKey) {
                $dataObj = Arr::changeKeyLower($dataObj);
            }
        }
            
        foreach ($dataObj as $k => $row) {
            eval($loop);
        }

        return array(strtolower($groupPath) => $array);
    }

    public function renderAddModeBpTab($uniqId, $row, $checkList = null, $selectedRowData = array(), $dmMetaDataId = null, $theme = null) {

        $tabHtml = $tabEndHtml = $tabStart = $tabEnd = $commentAddin = $tabcontentStyle = '';
        
        if ($row['REF_META_GROUP_ID'] != '') {
            
            Mdwebservice::$refStructureId = $row['REF_META_GROUP_ID'];
            
            if (issetParam($row['IS_MV_ADDON_INFO'])) {
            
                $tabHtml .= '<li class="nav-item"><a href="#bp_mv_addoninfo_tab_'.$uniqId.'" data-toggle="tab" class="nav-link" onclick="renderAddModeBpTab(\''.$uniqId.'\', \''.$row['REF_META_GROUP_ID'].'\', \'mv_addon_info\', this);" data-required="0" data-addon-type="mv_addon_info">Нэмэлт мэдээлэл</a></li>';
                $tabEndHtml .= '<div class="tab-pane" id="bp_mv_addoninfo_tab_'.$uniqId.'"></div>';
            }
            
            if ($row['IS_ADDON_PHOTO'] && issetParam($row['isIgnorePhotoTab']) != '1') {
                $tabHtml .= '<li class="nav-item"><a href="#bp_photo_tab_'.$uniqId.'" data-toggle="tab" class="nav-link" onclick="renderAddModeBpTab(\''.$uniqId.'\', \'' . $row['REF_META_GROUP_ID'] . '\', \'photo\', this);" data-required="' . $row['IS_ADDON_PHOTO'] . '" data-addon-type="photo">'
                        . ($row['IS_ADDON_PHOTO'] == '2' ? '<span class="required">*</span>' : '') . Lang::eitherOne('bpTabPhoto_'.$row['META_DATA_ID'], 'photo') . '</a></li>';
                $tabEndHtml .= '<div class="tab-pane" id="bp_photo_tab_'.$uniqId.'"></div>';
            }
            
            if ($row['IS_ADDON_FILE'] && issetParam($row['isIgnoreFileTab']) != '1') {
                $tabHtml .= '<li class="nav-item"><a href="#bp_file_tab_'.$uniqId.'" data-toggle="tab" class="nav-link" onclick="renderAddModeBpTab(\''.$uniqId.'\', \'' . $row['REF_META_GROUP_ID'] . '\', \'file\', this);" data-required="' . $row['IS_ADDON_FILE'] . '" data-addon-type="file">'
                        . ($row['IS_ADDON_FILE'] == '2' ? '<span class="required">*</span>' : '') . Lang::eitherOne('bpTabFile_'.$row['META_DATA_ID'], 'file') . '</a></li>';
                $tabEndHtml .= '<div class="tab-pane" id="bp_file_tab_'.$uniqId.'"></div>';
            }
            
            if ($row['IS_ADDON_COMMENT'] && issetParam($row['isIgnoreCommentTab']) != '1') {
                
                if (issetParam($row['IS_ADDON_COMMENT_TYPE'])) {
                    
                    switch ($row['IS_ADDON_COMMENT_TYPE']) {
                        case 'right':
                            $tabcontentStyle = 'style="width: 60%; float: left;"';
                            $commentAddin = 'style="width: 40%; padding-left: 5px;"';
                            break;
                        case 'left':
                            $tabcontentStyle = 'style="width: 60%; float: right;"';
                            $commentAddin = 'style="width: 40%; padding-right: 5px;"';
                            break;
                            
                        default:
                            $commentAddin = '';
                            break;
                    }
                    
                    $tabHtml .= '<input type="hidden"  />';
                    $tabEndHtml .= '<script>jQuery(document).ready(function () { renderAddModeBpTab(\''.$uniqId.'\', \'' . $row['REF_META_GROUP_ID'] . '\', \'commentbtm\', this); });</script> <fieldset class="collapsible"  '. $commentAddin .'> <legend>'. ($row['IS_ADDON_COMMENT'] == '2' ? '<span class="required">*</span>' : '') . Lang::eitherOne('bpTabComment_'.$row['META_DATA_ID'], 'comment') . ' '.(!empty($addOnCount) ? $addOnCount : '') .'</legend>';
                    $tabEndHtml .= '<div class="bp_comment_tab_'.$uniqId.'"></div></fieldset>';
                    
                } else {
                    $tabHtml .= '<li class="nav-item"><a href="#bp_comment_tab_'.$uniqId.'" data-toggle="tab" class="nav-link" onclick="renderAddModeBpTab(\''.$uniqId.'\', \'' . $row['REF_META_GROUP_ID'] . '\', \'comment\', this);" data-required="' . $row['IS_ADDON_COMMENT'] . '" data-addon-type="comment">'
                            . ($row['IS_ADDON_COMMENT'] == '2' ? '<span class="required">*</span>' : '') . Lang::eitherOne('bpTabComment_'.$row['META_DATA_ID'], 'comment') . '</a></li>';
                    $tabEndHtml .= '<div class="tab-pane" id="bp_comment_tab_'.$uniqId.'"></div>';
                }
            }
            
            if ($row['IS_ADDON_RELATION']) {
                $tabHtml .= '<li class="nav-item"><a href="#bp_relation_tab_'.$uniqId.'" data-toggle="tab" class="nav-link" onclick="renderAddModeBpTab(\''.$uniqId.'\', \'' . $row['REF_META_GROUP_ID'] . '\', \'relation\', this);" data-required="' . $row['IS_ADDON_RELATION'] . '" data-addon-type="relation">'
                        . ($row['IS_ADDON_RELATION'] == '2' ? '<span class="required">*</span>' : '') . Lang::line('relation') . '</a></li>';
                $tabEndHtml .= '<div class="tab-pane" id="bp_relation_tab_'.$uniqId.'"></div>';
            }
            
            if (issetParam($row['IS_ADDON_MV_RELATION'])) {
                
                $tabHtml .= '<li class="nav-item"><a href="#bp_mv_relation_tab_'.$uniqId.'" data-toggle="tab" class="nav-link" onclick="renderAddModeBpTab(\''.$uniqId.'\', \'' . $row['REF_META_GROUP_ID'] . '\', \'mv_relation\', this);" data-required="0" data-addon-type="mv_relation">'
                         . Lang::line('ea_meta_0012') . '</a></li>';
                $tabEndHtml .= '<div class="tab-pane" id="bp_mv_relation_tab_'.$uniqId.'"></div>';
            }
            
            if (isset($checkList['checkListTabName'])) {
                $tabHtml .= '<li class="nav-item"><a href="#bp_checklist_tab_'.$uniqId.'" data-toggle="tab" class="nav-link">' . Lang::line($checkList['checkListTabName']) . '</a></li>';
                $tabEndHtml .= '<div class="tab-pane" id="bp_checklist_tab_'.$uniqId.'">'.$checkList['checkListTabContent'].'</div>';
            }
        }

        if ($row['IS_ADDON_WFM_LOG_TYPE'] !== 'bottom' && $row['IS_ADDON_WFM_LOG'] == '1' && count($selectedRowData) > 0) {
            $selectedRow = array_key_exists(0, $selectedRowData) ? $selectedRowData[0] : $selectedRowData;
            if (isset($selectedRow['wfmstatusid'])) {
                $tabHtml .= '<li class="nav-item"><a href="#bp_wfmlog_tab_'.$uniqId.'" data-toggle="tab" class="nav-link" onclick="renderAddModeBpTab(\'' . $uniqId . '\', \'' . $row['REF_META_GROUP_ID'] . '\', \'wfmlog\', this);" data-selectedrow="'. Arr::encode($selectedRow) .'" data-required="' . $row['IS_ADDON_WFM_LOG'] . '" data-addon-type="wfmlog">'. Lang::line('wfmlog') . '</a></li>';
                $tabEndHtml .= '<div class="tab-pane" id="bp_wfmlog_tab_'.$uniqId.'"></div>';
            }
        } elseif ($row['IS_ADDON_WFM_LOG_TYPE'] == 'bottom' && $row['IS_ADDON_WFM_LOG'] == '1' && count($selectedRowData) > 0) {
            $selectedRow = array_key_exists(0, $selectedRowData) ? $selectedRowData[0] : $selectedRowData;
            $tabEndHtml .= '<script>jQuery(document).ready(function () { renderAddModeBpTab(\'' . $uniqId . '\', \'' . $row['REF_META_GROUP_ID'] . '\', \'wfmlogBtm\', this, \''. Arr::encode($selectedRow) .'\', \''. $dmMetaDataId .'\'); });</script> <fieldset class="collapsible"> <legend>'. Lang::line('wfmlog') .'</legend>';
            $tabEndHtml .= '<div class="bp_wfmlogs_tab_'.$uniqId.'"></div></fieldset>';
        }

        if ($tabHtml != '') {
            $tabStart = '<div class="tabbable-line" >
                        <ul class="nav nav-tabs bp-addon-tab">
                            <li class="nav-item">
                                <a href="#bp_main_tab_'.$uniqId.'" class="nav-link active" data-toggle="tab">' . Lang::line('base') . '</a>
                            </li>
                            ' . $tabHtml . '
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="bp_main_tab_'.$uniqId.'"  '. $tabcontentStyle .'>';

            $tabEnd = '</div><!-- tab -->
                        ' . $tabEndHtml . '
                        </div><!-- tab -->
                        </div><!-- tab -->';
        }

        if ($theme) {
            return array('tabStartNoBase' => $tabHtml, 'tabEndNoBase' => $tabEndHtml);
        }

        return array('tabStart' => $tabStart, 'tabEnd' => $tabEnd);
    }

    public function renderEditModeBpTab($uniqId, $row, $sourceId, $selectedRowData = array(), $dmMetaDataId = null) {

        $tabHtml = $tabEndHtml = $tabStart = $tabEnd = $photoCount = $fileCount = $commentCount = '';
        $refStructureId = $row['REF_META_GROUP_ID'];

        if ($refStructureId != '') {
            
            Mdwebservice::$refStructureId = $refStructureId;
            $actionType = $row['ACTION_TYPE'];
            
            if (issetParam($row['IS_MV_ADDON_INFO'])) {
            
                $tabHtml .= '<li class="nav-item"><a href="#bp_mv_addoninfo_tab_'.$uniqId.'" data-toggle="tab" class="nav-link" onclick="renderEditModeBpTab(\''.$uniqId.'\', \''.$row['REF_META_GROUP_ID'].'\', \''.$sourceId.'\', \'mv_addon_info\', this, \''.$dmMetaDataId.'\');" data-required="0" data-addon-type="mv_addon_info" data-actiontype="'.$actionType.'">Нэмэлт мэдээлэл</a></li>';
                $tabEndHtml .= '<div class="tab-pane" id="bp_mv_addoninfo_tab_'.$uniqId.'"></div>';
            }
            
            if ($row['IS_ADDON_PHOTO'] && issetParam($row['isIgnorePhotoTab']) != '1') {
                
                $addOnCount = self::getMetaDataValueCount($refStructureId, $sourceId, 'photo');

                $tabHtml .= '<li class="nav-item"><a href="#bp_photo_tab_'.$uniqId.'" data-toggle="tab" class="nav-link" onclick="renderEditModeBpTab(\''.$uniqId.'\', \''.$row['REF_META_GROUP_ID'].'\', \''.$sourceId.'\', \'photo\', this, \''.$dmMetaDataId.'\');" data-required="' . $row['IS_ADDON_PHOTO'] . '" data-addon-type="photo" data-actiontype="'.$actionType.'">'
                        . ($row['IS_ADDON_PHOTO'] == '2' ? '<span class="required">*</span>' : '') . Lang::eitherOne('bpTabPhoto_'.$row['META_DATA_ID'], 'photo') . ' '.(!empty($addOnCount) ? $addOnCount : '').'</a></li>';
                $tabEndHtml .= '<div class="tab-pane" id="bp_photo_tab_'.$uniqId.'"></div>';
            }
            
            if ($row['IS_ADDON_FILE'] && issetParam($row['isIgnoreFileTab']) != '1') {
                
                $addOnCount = self::getMetaDataValueCount($refStructureId, $sourceId, 'file');

                $tabHtml .= '<li class="nav-item"><a href="#bp_file_tab_'.$uniqId.'" data-toggle="tab" class="nav-link" onclick="renderEditModeBpTab(\'' . $uniqId . '\', \'' . $row['REF_META_GROUP_ID'] . '\', \'' . $sourceId . '\', \'file\', this, \''. $dmMetaDataId . '\');" data-required="' . $row['IS_ADDON_FILE'] . '" data-addon-type="file" data-actiontype="'.$actionType.'">'
                        . ($row['IS_ADDON_FILE'] == '2' ? '<span class="required">*</span>' : '') . Lang::eitherOne('bpTabFile_'.$row['META_DATA_ID'], 'file') . ' '.(!empty($addOnCount) ? $addOnCount : '').'</a></li>';
                $tabEndHtml .= '<div class="tab-pane" id="bp_file_tab_'.$uniqId.'"></div>';
            }
            
            if ($row['IS_ADDON_COMMENT'] && issetParam($row['isIgnoreCommentTab']) != '1') {
                
                $selectedRow = array_key_exists(0, $selectedRowData) ? $selectedRowData[0] : $selectedRowData;
                $addOnCount = self::getMetaDataValueCount($refStructureId, $sourceId, 'comment');
                
                if (issetParam($row['IS_ADDON_COMMENT_TYPE'])) {

                    $tabHtml .= '<input type="hidden"/>';
                    $tabEndHtml .= '<fieldset class="collapsible"><legend>'. ($row['IS_ADDON_COMMENT'] == '2' ? '<span class="required">*</span>' : '') . Lang::eitherOne('bpTabComment_'.$row['META_DATA_ID'], 'comment') . ' '.(!empty($addOnCount) ? $addOnCount : '') .'</legend>';
                    $tabEndHtml .= '<div class="bp_comment_tab_'.$uniqId.'">'.(new Mdwebservice())->renderEditModeBpCommentTab($uniqId, $row['META_DATA_ID'], $row['REF_META_GROUP_ID'], $sourceId).'</div></fieldset>';
        
                } else {
                    $tabHtml .= '<li class="nav-item"><a href="#bp_comment_tab_'.$uniqId.'" data-toggle="tab" class="nav-link" onclick="renderEditModeBpTab(\'' . $uniqId . '\', \'' . $row['REF_META_GROUP_ID'] . '\', \'' . $sourceId . '\', \'comment\', this, \''. $dmMetaDataId . '\');" data-required="' . $row['IS_ADDON_COMMENT'] . '" data-addon-type="comment" data-actiontype="'.$actionType.'">'
                            . ($row['IS_ADDON_COMMENT'] == '2' ? '<span class="required">*</span>' : '') . Lang::eitherOne('bpTabComment_'.$row['META_DATA_ID'], 'comment') . ' '.(!empty($addOnCount) ? $addOnCount : '').'</a></li>';
                    $tabEndHtml .= '<div class="tab-pane" id="bp_comment_tab_'.$uniqId.'"></div>';
                }
            }
            
            if ($row['IS_ADDON_RELATION']) {
                
                $addOnCount = self::getMetaDataValueCount($refStructureId, $sourceId, 'relation');

                $tabHtml .= '<li class="nav-item"><a href="#bp_relation_tab_'.$uniqId.'" data-toggle="tab" class="nav-link" onclick="renderEditModeBpTab(\'' . $uniqId . '\', \'' . $row['REF_META_GROUP_ID'] . '\', \'' . $sourceId . '\', \'relation\', this, \''. $dmMetaDataId . '\');" data-required="' . $row['IS_ADDON_RELATION'] . '" data-addon-type="relation">'
                        . ($row['IS_ADDON_RELATION'] == '2' ? '<span class="required">*</span>' : '') . Lang::line('relation') . ' '.(!empty($addOnCount) ? $addOnCount : '').'</a></li>';
                $tabEndHtml .= '<div class="tab-pane" id="bp_relation_tab_'.$uniqId.'"></div>';
            }
            
            if (issetParam($row['IS_ADDON_MV_RELATION'])) {
                
                $tabHtml .= '<li class="nav-item"><a href="#bp_mv_relation_tab_'.$uniqId.'" data-toggle="tab" class="nav-link" onclick="renderEditModeBpTab(\'' . $uniqId . '\', \'' . $row['REF_META_GROUP_ID'] . '\', \'' . $sourceId . '\', \'mv_relation\', this, \''. $dmMetaDataId . '\');" data-required="0" data-addon-type="mv_relation">'
                         . Lang::line('ea_meta_0012') . '</a></li>';
                $tabEndHtml .= '<div class="tab-pane" id="bp_mv_relation_tab_'.$uniqId.'"></div>';
            }
        }

        if ($row['IS_ADDON_WFM_LOG_TYPE'] != 'bottom' && $row['IS_ADDON_WFM_LOG'] == '1') {

            $selectedRow = array_key_exists(0, $selectedRowData) ? $selectedRowData[0] : $selectedRowData;

            if (isset($selectedRow['wfmstatusid'])) {
                $tabHtml .= '<li class="nav-item"><a href="#bp_wfmlog_tab_'.$uniqId.'" data-toggle="tab" class="nav-link" onclick="renderEditModeBpTab(\'' . $uniqId . '\', \'' . $refStructureId . '\', \'' . $sourceId . '\', \'wfmlog\', this, \''. $dmMetaDataId . '\');" data-selectedrow="'. Arr::encode($selectedRow) .'" data-required="' . $row['IS_ADDON_WFM_LOG'] . '" data-addon-type="wfmlog">'. Lang::line('wfmlog') . ' </a></li>';
                $tabEndHtml .= '<div class="tab-pane" id="bp_wfmlog_tab_'.$uniqId.'"></div>';
            }
        } else {
            if ($row['IS_ADDON_WFM_LOG_TYPE'] == 'bottom' && $row['IS_ADDON_WFM_LOG'] == '1') {

                $selectedRow = array_key_exists(0, $selectedRowData) ? $selectedRowData[0] : $selectedRowData;

                if (isset($selectedRow['wfmstatusid'])) {
                    $tabHtml .= '<input type="hidden"  />';
                    $tabEndHtml .= '<script>jQuery(document).ready(function () { renderAddModeBpTab(\'' . $uniqId . '\', \'' . $refStructureId . '\', \'wfmlogBtm\', this, \''. Arr::encode($selectedRow) .'\', \''. $dmMetaDataId .'\'); });</script> <fieldset class="collapsible"> <legend>'. Lang::line('wfmlog') .'</legend>';
                    $tabEndHtml .= '<div class="bp_wfmlogs_tab_'.$uniqId.'"></div></fieldset>';
                }
            }
        }

        if (issetParam($row['IS_BPMN_TOOL']) == '1') {
            $tabHtml .= '<li class="nav-item"><a href="#bp_bpmntool_tab_'.$uniqId.'" data-toggle="tab" class="nav-link" onclick="renderEditModeBpTab(\'' . $uniqId . '\', \'' . $refStructureId . '\', \'' . $sourceId . '\', \'bpmntool\', this, \''. $dmMetaDataId . '\');" data-selectedrow="" data-required="" data-addon-type="wfmlog">BPMN </a></li>';
            $tabEndHtml .= '<div class="tab-pane" id="bp_bpmntool_tab_'.$uniqId.'"></div>';
        }        

        if ($tabHtml != '') {
            $tabStart = '<div class="tabbable-line tab-not-padding-top" >
                        <ul class="nav nav-tabs bp-addon-tab">
                            <li class="nav-item">
                                <a href="#bp_main_tab_'.$uniqId.'" class="nav-link active" data-toggle="tab">' . Lang::line('base') . '</a>
                            </li>
                            ' . $tabHtml . '
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="bp_main_tab_'.$uniqId.'">';

            $tabEnd = '</div><!-- tab -->
                        ' . $tabEndHtml . '
                        </div><!-- tab -->
                        </div><!-- tab -->';
        }

        return array('tabStart' => $tabStart, 'tabEnd' => $tabEnd);
    }
    
    public function getMetaDataValueCount($metaDataId = 0, $metaValueId = 0, $type = 'photo') {
        
        $data = '0';
        $id1Ph = $this->db->Param(0);
        $id2Ph = $this->db->Param(1);
        
        if ($type == 'photo') {
            
            $data = $this->db->GetOne("
                SELECT 
                    COUNT(FA.CONTENT_ID)
                FROM ECM_CONTENT FA 
                    INNER JOIN ECM_CONTENT_MAP MP ON MP.CONTENT_ID = FA.CONTENT_ID 
                WHERE MP.REF_STRUCTURE_ID = $id1Ph  
                    AND MP.RECORD_ID = $id2Ph 
                    AND FA.IS_PHOTO = 1", array($metaDataId, $metaValueId));
            
        } elseif ($type == 'file') {
            
            $data = $this->db->GetOne("
                SELECT 
                    COUNT(FA.CONTENT_ID) 
                FROM ECM_CONTENT FA 
                    INNER JOIN ECM_CONTENT_MAP MP ON MP.CONTENT_ID = FA.CONTENT_ID 
                WHERE MP.REF_STRUCTURE_ID = $id1Ph 
                    AND (MP.RECORD_ID = $id2Ph OR MP.MAIN_RECORD_ID = $id2Ph) 
                    AND FA.IS_PHOTO = 0 
                    AND (FA.TYPE_ID <> 4001 OR FA.TYPE_ID IS NULL)", array($metaDataId, $metaValueId));
            
        } elseif ($type == 'comment') {
            
            $data = $this->db->GetOne("
                SELECT 
                    COUNT(ID) 
                FROM ECM_COMMENT 
                WHERE REF_STRUCTURE_ID = $id1Ph 
                    AND RECORD_ID = $id2Ph 
                    AND IS_DELETED = 0", array($metaDataId, $metaValueId));
            
        } elseif ($type == 'relation') {
            $data = 0;
        }
        
        if ($data != '0') {
            return ' <span data-file-count="'.$data.'">('.$data.')</span>';
        }
        return '';
    }
    
    public function getMetaDataValueFilesModel($metaDataId = 0, $metaValueId = 0) {
        
        if ($metaValueId == '') {
            $metaValueId = 0;
        }
        
        $metaDataIdPh  = $this->db->Param(0);
        $metaValueIdPh = $this->db->Param(1);
        
        $bindVars = array($this->db->addQ($metaDataId), $this->db->addQ($metaValueId));
        
        $data = $this->db->GetAll("
            SELECT 
                CO.CONTENT_ID AS ATTACH_ID, 
                CO.FILE_NAME AS ATTACH_NAME, 
                CO.PHYSICAL_PATH AS ATTACH, 
                CO.FILE_EXTENSION, 
                CO.FILE_SIZE, 
                CO.IS_EMAIL, 
                '' AS SYSTEM_URL, 
                EMP.PICTURE, 
                CO.THUMB_PHYSICAL_PATH 
            FROM ECM_CONTENT CO 
                INNER JOIN ECM_CONTENT_MAP MP ON MP.CONTENT_ID = CO.CONTENT_ID 
                LEFT JOIN UM_USER UM ON UM.USER_ID = CO.CREATED_USER_ID 
                LEFT JOIN UM_SYSTEM_USER US ON US.USER_ID = UM.SYSTEM_USER_ID 
                LEFT JOIN VW_EMPLOYEE EMP ON EMP.PERSON_ID = US.PERSON_ID 
            WHERE MP.REF_STRUCTURE_ID = $metaDataIdPh 
                AND MP.RECORD_ID = $metaValueIdPh 
                AND CO.IS_PHOTO = 0 
                AND (CO.TYPE_ID <> 4001 OR CO.TYPE_ID IS NULL) 
            GROUP BY 
                CO.CONTENT_ID, 
                CO.FILE_NAME, 
                CO.PHYSICAL_PATH, 
                CO.FILE_EXTENSION, 
                CO.FILE_SIZE, 
                CO.IS_EMAIL, 
                EMP.PICTURE, 
                CO.THUMB_PHYSICAL_PATH, 
                MP.ORDER_NUM     
            ORDER BY MP.ORDER_NUM", 
            $bindVars     
        );

        return $data;
    }
    
    public function getMetaDataValuePhotosModel($metaDataId = 0, $metaValueId = 0) {
        
        $metaDataIdPh  = $this->db->Param(0);
        $metaValueIdPh = $this->db->Param(1);
        
        $bindVars = array($this->db->addQ($metaDataId), $this->db->addQ($metaValueId));
        
        $data = $this->db->GetAll("
            SELECT 
                CO.CONTENT_ID AS ATTACH_ID, 
                CO.FILE_NAME AS ATTACH_NAME, 
                CO.PHYSICAL_PATH AS ATTACH, 
                CO.THUMB_PHYSICAL_PATH AS ATTACH_THUMB, 
                CO.FILE_EXTENSION, 
                CO.FILE_SIZE,
                CO.IS_EMAIL,
                '' AS SYSTEM_URL, 
                '' AS TRG_TAG_ID,
                '' AS TRG_TAG_IDC,
                MP.IS_MAIN,
                T0.FOLDER_NAME,
                T0.FOLDER_ID
            FROM ECM_CONTENT CO 
                INNER JOIN ECM_CONTENT_MAP MP ON MP.CONTENT_ID = CO.CONTENT_ID 
                LEFT JOIN (
                    SELECT 
                        T2.NAME AS FOLDER_NAME,
                        T2.ID AS FOLDER_ID,
                        T2.PARENT_ID,
                        T1.CONTENT_ID
                    FROM ECM_DIRECTORY_MAP T0 
                        LEFT JOIN ECM_CONTENT_DIRECTORY T1 ON T0.DIRECTORY_ID = T1.DIRECTORY_ID
                        LEFT JOIN ECM_DIRECTORY T2 ON T1.DIRECTORY_ID = T2.ID
                    WHERE T0.RECORD_ID = $metaValueIdPh 
                        AND T0.REF_STRUCTURE_ID = $metaDataIdPh
                ) T0 ON CO.CONTENT_ID = T0.CONTENT_ID
            WHERE MP.REF_STRUCTURE_ID = $metaDataIdPh  
                AND MP.RECORD_ID = $metaValueIdPh  
                AND t0.FOLDER_ID IS NULL
                AND CO.IS_PHOTO = 1 
            GROUP BY     
                CO.CONTENT_ID, 
                CO.FILE_NAME, 
                CO.PHYSICAL_PATH, 
                CO.THUMB_PHYSICAL_PATH, 
                CO.FILE_EXTENSION, 
                CO.FILE_SIZE,
                CO.IS_EMAIL,
                MP.IS_MAIN,
                T0.FOLDER_NAME,
                T0.FOLDER_ID, 
                MP.ORDER_NUM 
            ORDER BY MP.ORDER_NUM", 
        $bindVars);
        
        return $data;
    }
    
    public function getMetaDataValuePhotosFolderModel($metaDataId = 0, $metaValueId = 0) {
        $metaDataIdPh  = $this->db->Param(0);
        $metaValueIdPh = $this->db->Param(1);
        
        $bindVars = array($this->db->addQ($metaDataId), $this->db->addQ($metaValueId));
        
        $data = $this->db->GetAll("
            SELECT 
                DISTINCT
                T2.NAME AS FOLDER_NAME,
                T2.ID AS FOLDER_ID,
                T2.PARENT_ID
            FROM ECM_DIRECTORY_MAP T0 
                LEFT JOIN ECM_DIRECTORY T2 ON T0.DIRECTORY_ID = T2.ID
            WHERE T0.RECORD_ID = $metaValueIdPh 
                AND T0.REF_STRUCTURE_ID = $metaDataIdPh 
                AND T2.PARENT_ID IS NULL", 
        $bindVars);
        
        return $data;
    }

    public function renderBpTabDeletePhotoModel() {
        
        $metaDataId = Input::numeric('metaDataId');
        $metaValueId = Input::post('metaValueId');
        $attachId = Input::post('attachId');

        $row = $this->db->GetRow("SELECT CONTENT_ID FROM ECM_CONTENT_MAP WHERE REF_STRUCTURE_ID = $metaDataId AND (RECORD_ID = $metaValueId OR MAIN_RECORD_ID = $metaValueId) AND CONTENT_ID = $attachId");
        
        if (count($row) > 0) {
            
            $r = $this->db->GetRow("SELECT PHYSICAL_PATH, THUMB_PHYSICAL_PATH FROM ECM_CONTENT WHERE CONTENT_ID = " . $row['CONTENT_ID']);
            
            if (count($r) > 0) {
                if (is_file($r['PHYSICAL_PATH']))
                    unlink($r['PHYSICAL_PATH']);
                if (is_file($r['THUMB_PHYSICAL_PATH']))
                    unlink($r['THUMB_PHYSICAL_PATH']);
            }
            
            $this->db->Execute("DELETE FROM ECM_CONTENT_MAP WHERE REF_STRUCTURE_ID = $metaDataId AND (RECORD_ID = $metaValueId OR MAIN_RECORD_ID = $metaValueId) AND CONTENT_ID = $attachId");
            $this->db->Execute("DELETE FROM ECM_CONTENT WHERE CONTENT_ID = " . $row['CONTENT_ID']);
            
            return array('status' => 'success', 'message' => Lang::line('msg_delete_success'));
        }
        
        return array('status' => 'error', 'message' => Lang::line('msg_delete_error'));
    }

    public function renderBpTabDeletePhotoFolderModel() {
        $postData = Input::postData();
        
        $pqry = "WITH RECURSIVE a AS (
                    SELECT t.* FROM (
                        SELECT 
                            DISTINCT
                            t0.ID ,
                            t2.ID AS MAP_ID ,
                            t0.PARENT_ID
                        FROM
                            ECM_DIRECTORY T0
                            LEFT JOIN ECM_CONTENT_DIRECTORY T1 ON T0.ID = T1.DIRECTORY_ID
                            LEFT JOIN ECM_DIRECTORY_MAP T2 ON T0.ID = T2.DIRECTORY_ID
                    ) t
                    WHERE t.ID = ". $this->db->Param(0) ."
                UNION ALL
                    SELECT d.* FROM (
                        SELECT 
                            DISTINCT
                            t0.ID ,
                            t2.ID AS MAP_ID ,
                            t0.PARENT_ID
                        FROM
                            ECM_DIRECTORY T0
                        LEFT JOIN ECM_CONTENT_DIRECTORY T1 ON T0.ID = T1.DIRECTORY_ID
                        LEFT JOIN ECM_DIRECTORY_MAP T2 ON T0.ID = T2.DIRECTORY_ID
                    ) d
                    JOIN a ON a.id = d.parent_id 
                )
                SELECT DISTINCT ID, MAP_ID, PARENT_ID FROM a ORDER BY a.ID DESC";
        
        $qry = "SELECT 
                    DISTINCT 
                    t2.ID AS MAP_ID
                FROM ECM_DIRECTORY T0
                LEFT JOIN ECM_CONTENT_DIRECTORY T1 ON T0.ID = T1.DIRECTORY_ID
                LEFT JOIN ECM_DIRECTORY_MAP T2 ON T0.ID = T2.DIRECTORY_ID
                START WITH T0.ID = ". $this->db->Param(0) ."
                CONNECT BY PRIOR T0.ID = T0.PARENT_ID 
                ORDER BY T2.ID DESC";
        
        if (DB_DRIVER == 'postgres9') {
            $dataListMap = $this->db->GetAll($pqry, array($postData['folderId']));
        } else {
            $dataListMap = $this->db->GetAll($qry, array($postData['folderId']));
        }
        
        foreach ($dataListMap as $key => $row) {
            if ($row['MAP_ID']) {
                $this->db->Execute("DELETE FROM ECM_DIRECTORY_MAP WHERE ID = '". $row['MAP_ID'] ."'");
            }
        }
        
        $pqry = "WITH RECURSIVE a AS (
                    SELECT t.* FROM (
                        SELECT 
                            DISTINCT
                            t0.ID ,
                            t1.CONTENT_ID ,
                            t0.PARENT_ID
                        FROM
                            ECM_DIRECTORY T0
                            LEFT JOIN ECM_CONTENT_DIRECTORY T1 ON T0.ID = T1.DIRECTORY_ID
                    ) t
                    WHERE t.ID = ". $this->db->Param(0) ."
                UNION ALL
                    SELECT d.* FROM (
                        SELECT 
                            DISTINCT
                            t0.ID ,
                            t1.CONTENT_ID ,
                            t0.PARENT_ID
                        FROM
                            ECM_DIRECTORY T0
                        LEFT JOIN ECM_CONTENT_DIRECTORY T1 ON T0.ID = T1.DIRECTORY_ID
                    ) d
                    JOIN a ON a.id = d.parent_id 
                )
                SELECT DISTINCT ID, CONTENT_ID, PARENT_ID FROM a ORDER BY a.ID DESC";
        
        $qry = "SELECT 
                    DISTINCT 
                    T0.ID ,
                    T1.CONTENT_ID
                FROM ECM_DIRECTORY T0
                LEFT JOIN ECM_CONTENT_DIRECTORY T1 ON T0.ID = T1.DIRECTORY_ID
                START WITH T0.ID = ". $this->db->Param(0) ."
                CONNECT BY PRIOR T0.ID = T0.PARENT_ID ORDER BY T0.ID DESC";
                                        
        if (DB_DRIVER == 'postgres9') {
            $dataList = $this->db->GetAll($pqry, array($postData['folderId']));
        } else {
            $dataList = $this->db->GetAll($qry, array($postData['folderId']));
        }
        
        foreach ($dataList as $key => $row) {
            if ($row['CONTENT_ID']) {
                $this->db->Execute("DELETE FROM ECM_CONTENT_DIRECTORY WHERE CONTENT_ID = '". $row['CONTENT_ID'] ."'");
                $this->db->Execute("DELETE FROM ECM_CONTENT_MAP WHERE CONTENT_ID = '". $row['CONTENT_ID'] ."'");
                $this->db->Execute("DELETE FROM ECM_CONTENT WHERE CONTENT_ID = '". $row['CONTENT_ID'] ."'");
                $this->db->Execute("DELETE FROM ECM_DIRECTORY WHERE ID = '". $row['ID'] ."'");
            } else {
                $this->db->Execute("DELETE FROM ECM_DIRECTORY WHERE ID = '". $row['ID'] ."'");
            }
        }
        
        return array('status' => 'success', 'message' => Lang::line('msg_delete_success'));
    }

    public function renderBpTabDeleteFileModel() {
        
        try {
            
            $metaDataId = Input::numeric('metaDataId');
            $metaValueId = Input::numeric('metaValueId');
            $attachId = Input::numeric('attachId');
            
            $idPh1 = $this->db->Param(0);
            $idPh2 = $this->db->Param(1);
            $idPh3 = $this->db->Param(2);
            
            $row = $this->db->GetRow("
                SELECT 
                    CONTENT_ID 
                FROM ECM_CONTENT_MAP 
                WHERE REF_STRUCTURE_ID = $idPh1 
                    AND (RECORD_ID = $idPh2 OR MAIN_RECORD_ID = $idPh2) 
                    AND CONTENT_ID = $idPh3", 
                array($metaDataId, $metaValueId, $attachId)
            );

            if (count($row) > 0) {
                
                $r = $this->db->GetAll("
                    SELECT 
                        EC.PHYSICAL_PATH, 
                        EC.THUMB_PHYSICAL_PATH, 
                        EC.MIDDLE_PHYSICAL_PATH 
                    FROM ECM_CONTENT EC
                        INNER JOIN ECM_CONTENT_MAP CM ON CM.CONTENT_ID = EC.CONTENT_ID 
                    WHERE EC.CONTENT_ID = $idPh1", 
                    array($row['CONTENT_ID'])
                );
                
                $contentCount = count($r);
                
                $this->db->Execute("
                    DELETE 
                    FROM ECM_CONTENT_MAP 
                    WHERE REF_STRUCTURE_ID = $idPh1 
                        AND (RECORD_ID = $idPh2 OR MAIN_RECORD_ID = $idPh2) 
                        AND CONTENT_ID = $idPh3", 
                    array($metaDataId, $metaValueId, $attachId)
                );
                
                if ($contentCount == 1) {
                    
                    if (is_file($r[0]['PHYSICAL_PATH'])) {
                        unlink($r[0]['PHYSICAL_PATH']);
                    }
                    if (is_file($r[0]['THUMB_PHYSICAL_PATH'])) {
                        unlink($r[0]['THUMB_PHYSICAL_PATH']);
                    }
                    if (is_file($r[0]['MIDDLE_PHYSICAL_PATH'])) {
                        unlink($r[0]['MIDDLE_PHYSICAL_PATH']);
                    }
                    
                    $this->db->Execute("DELETE FROM ECM_CONTENT WHERE CONTENT_ID = $idPh1", array($row['CONTENT_ID']));
                }

                return array('status' => 'success', 'message' => Lang::line('msg_delete_success'));
            }

            return array('status' => 'error', 'message' => Lang::line('msg_delete_error'));
            
        } catch (Exception $ex) {
            return array('status' => 'error', 'message' => $ex->getMessage());
        }
    }

    public function getMetaDataValueOnePhotoModel($metaDataId = 0, $metaValueId = 0, $attachId = 0) {
        $data = $this->db->GetRow("
            SELECT 
                CO.CONTENT_ID AS ATTACH_ID, 
                CO.FILE_NAME AS ATTACH_NAME, 
                CO.PHYSICAL_PATH AS ATTACH, 
                CO.THUMB_PHYSICAL_PATH AS ATTACH_THUMB, 
                CO.FILE_EXTENSION, 
                CO.FILE_SIZE,
                CO.IS_EMAIL,
                '' AS SYSTEM_URL, 
                MP.IS_MAIN,
                CO.CREATED_DATE
            FROM ECM_CONTENT CO 
                INNER JOIN ECM_CONTENT_MAP MP ON MP.CONTENT_ID = CO.CONTENT_ID 
            WHERE MP.REF_STRUCTURE_ID = $metaDataId  
                AND MP.RECORD_ID = $metaValueId 
                AND CO.CONTENT_ID = $attachId 
                AND IS_PHOTO = 1"
        );
        return $data;
    }

    public function getMetaDataValueOneFileModel($metaDataId = 0, $metaValueId = 0, $attachId = 0) {
        $data = $this->db->GetRow("
            SELECT 
                FA.CONTENT_ID AS ATTACH_ID, 
                FA.FILE_NAME AS ATTACH_NAME, 
                FA.PHYSICAL_PATH AS ATTACH, 
                FA.THUMB_PHYSICAL_PATH AS ATTACH_THUMB, 
                FA.FILE_EXTENSION, 
                FA.FILE_SIZE,
                FA.IS_EMAIL,
                FA.CREATED_DATE,
                '' AS SYSTEM_URL
            FROM ECM_CONTENT FA 
                INNER JOIN ECM_CONTENT_MAP MP ON MP.CONTENT_ID = FA.CONTENT_ID 
            WHERE MP.REF_STRUCTURE_ID = $metaDataId  
                AND MP.RECORD_ID = $metaValueId
                AND MP.CONTENT_ID = $attachId 
                AND IS_PHOTO = 0"
        );
        
        return $data;
    }

    public function getWorkSpaceParamMap($getProcessId, $workSpaceId) {
        $data = $this->db->GetAll("
            SELECT 
                LOWER(FIELD_PATH) AS FIELD_PATH, 
                LOWER(PARAM_PATH) AS PARAM_PATH   
            FROM META_WORKSPACE_PARAM_MAP 
            WHERE WORKSPACE_META_ID = ".$this->db->Param(0)." 
                AND TARGET_META_ID = ".$this->db->Param(1)." 
                AND IS_TARGET = 1", 
            array($workSpaceId, $getProcessId));

        return $data;
    }

    public function getRunDefaultGetDataProcessModel($processMetaDataId, $workSpaceId = null, $workSpaceParams = null, $recordId = null, $workSpaceParamsType = null) {
        
        $processPh = $this->db->Param(0);
        
        $row = $this->db->GetRow("
            SELECT 
                MD.META_DATA_CODE AS COMMAND_NAME, 
                DG.WS_URL, 
                SL.SERVICE_LANGUAGE_CODE, 
                DG.SUB_TYPE, 
                DG.ACTION_TYPE, 
                PL.GETDATA_PROCESS_ID 
            FROM META_BUSINESS_PROCESS_LINK PL 
                INNER JOIN META_BUSINESS_PROCESS_LINK DG ON DG.META_DATA_ID = PL.GETDATA_PROCESS_ID 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = DG.META_DATA_ID 
                LEFT JOIN WEB_SERVICE_LANGUAGE SL ON SL.SERVICE_LANGUAGE_ID = DG.SERVICE_LANGUAGE_ID 
            WHERE PL.META_DATA_ID = $processPh", array($processMetaDataId));
        
        if ($row) {
            if ($workSpaceId && $workSpaceParams) {

                $getWorkSpaceParamMap = self::getWorkSpaceParamMap($row['GETDATA_PROCESS_ID'], $workSpaceId);
                
                if ($getWorkSpaceParamMap) {
                    
                    if ($workSpaceParamsType == 'array') {
                        $workSpaceParamArray = Arr::decode($workSpaceParams);
                    } else {
                        parse_str($workSpaceParams, $workSpaceParamArray);
                    }
                    
                    $workSpaceParamArray = Arr::changeKeyLower($workSpaceParamArray);

                    if ($row['SUB_TYPE'] == 'internal' && $row['ACTION_TYPE'] == 'get') {

                        $isParam = false;

                        foreach ($getWorkSpaceParamMap as $workSpaceParam) {

                            $fieldPath = strtolower($workSpaceParam['FIELD_PATH']);
                            $paramPath = strtolower($workSpaceParam['PARAM_PATH']);

                            if (isset($workSpaceParamArray['workspaceparam'][$fieldPath]) && $workSpaceParamArray['workspaceparam'][$fieldPath] != '') {

                                $paramDefaultCriteria[$paramPath][] = array(
                                    'operator' => '=',
                                    'operand' => $workSpaceParamArray['workspaceparam'][$fieldPath]
                                );
                                $isParam = true;

                            } elseif (isset($workSpaceParamArray[$paramPath]) && $workSpaceParamArray[$paramPath] != '') {

                                $paramDefaultCriteria[$paramPath][] = array(
                                    'operator' => '=',
                                    'operand' => $workSpaceParamArray[$paramPath]
                                );
                                $isParam = true;
                            }
                        }

                        if ($isParam) {
                            
                            $param = array(
                                'processId' => $processMetaDataId, 
                                'criteria' => $paramDefaultCriteria
                            );
                            
                            $result = $this->ws->caller($row['SERVICE_LANGUAGE_CODE'], $row['WS_URL'], $row['COMMAND_NAME'], 'return', $param, 'array');
                            
                            if ($result['status'] == 'success' && isset($result['result'])) {
                                return $result['result'];
                            }
                        }

                    } else {

                        $isParam = false;

                        foreach ($getWorkSpaceParamMap as $workSpaceParam) {

                            $fieldPath = strtolower($workSpaceParam['FIELD_PATH']);
                            $paramPath = strtolower($workSpaceParam['PARAM_PATH']);

                            if (isset($workSpaceParamArray['workspaceparam'][$fieldPath]) && $workSpaceParamArray['workspaceparam'][$fieldPath] != '') {

                                $param[$paramPath] = $workSpaceParamArray['workspaceparam'][$fieldPath];
                                $isParam = true;
                            }
                        }

                        if ($isParam) {
                            $result = $this->ws->caller($row['SERVICE_LANGUAGE_CODE'], $row['WS_URL'], $row['COMMAND_NAME'], 'return', $param, 'array');

                            if ($result['status'] == 'success' && isset($result['result'])) {
                                return $result['result'];
                            }
                        }
                    }
                }

            } else {

                if ($recordId) {

                    if ($row['SUB_TYPE'] == 'internal' && $row['ACTION_TYPE'] == 'get') {

                        $param = array(
                            'processId' => $processMetaDataId, 
                            'criteria' => array(
                                'id' => array(
                                    array(
                                        'operator' => '=',
                                        'operand' => $recordId
                                    )
                                )
                            )
                        );

                        $result = $this->ws->caller($row['SERVICE_LANGUAGE_CODE'], $row['WS_URL'], $row['COMMAND_NAME'], 'return', $param, 'array');

                        if ($result['status'] == 'success' && isset($result['result'])) {
                            return $result['result'];
                        }

                    } else {

                        $param['id'] = $recordId;

                        $result = $this->ws->caller($row['SERVICE_LANGUAGE_CODE'], $row['WS_URL'], $row['COMMAND_NAME'], 'return', $param, 'array');

                        if ($result['status'] == 'success' && isset($result['result'])) {
                            return $result['result'];
                        }
                    }

                } else {
                    
                    if (Input::isEmpty('fillDataParams') == false || Input::isEmpty('defaultGetParams') == false) {
                        
                        if (Input::isEmpty('fillDataParams') == false) {
                            parse_str($_POST['fillDataParams'], $addonParseParam);
                        } else {
                            parse_str($_POST['defaultGetParams'], $addonParseParam);
                        }
                        
                        $addonParseParamLower = Arr::changeKeyLower($addonParseParam);

                        if (isset($addonParseParamLower['defaultgetpf'])) {
                            
                            unset($addonParseParamLower['defaultgetpf']);
                            
                            if ($row['SUB_TYPE'] == 'internal' && $row['ACTION_TYPE'] == 'get') {

                                $paramDefaultCriteria = array();

                                foreach ($addonParseParamLower as $k => $val) {
                                    $paramDefaultCriteria[$k][] = array(
                                        'operator' => '=',
                                        'operand' => Input::param($val)
                                    );
                                }

                                $param = array(
                                    'processId' => $processMetaDataId, 
                                    'criteria' => $paramDefaultCriteria
                                );

                                $result = $this->ws->caller($row['SERVICE_LANGUAGE_CODE'], $row['WS_URL'], $row['COMMAND_NAME'], 'return', $param, 'array');
                                
                                if ($result['status'] == 'success' && isset($result['result'])) {
                                    return $result['result'];
                                }
                                
                            } else {
                                
                                $param = array();
                                
                                foreach ($addonParseParamLower as $k => $val) {
                                    $param[$k] = Input::param($val);
                                }

                                $result = $this->ws->caller($row['SERVICE_LANGUAGE_CODE'], $row['WS_URL'], $row['COMMAND_NAME'], 'return', $param, 'array');

                                if ($result['status'] == 'success' && isset($result['result'])) {
                                    return $result['result'];
                                }
                            }
                        }
                    }

                    $data = $this->db->GetAll("
                        SELECT 
                            PARAM_CODE, 
                            DEFAULT_VALUE 
                        FROM META_PROCESS_DEFAULT_GET  
                        WHERE PROCESS_META_DATA_ID = $processPh  
                            AND DEFAULT_VALUE IS NOT NULL 
                            AND PARAM_CODE IS NOT NULL", array($processMetaDataId));
                    
                    if ($data) {
                        
                        if ($row['SUB_TYPE'] == 'internal' && $row['ACTION_TYPE'] == 'get') {

                            $paramDefaultCriteria = $param = array();
                            
                            foreach ($data as $val) {

                                $defaultValue = Mdmetadata::setDefaultValue($val['DEFAULT_VALUE']);

                                if ($defaultValue) {
                                    $paramDefaultCriteria[$val['PARAM_CODE']][] = array(
                                        'operator' => '=',
                                        'operand' => $defaultValue
                                    );
                                }
                            }

                            $param = array(
                                'processId' => $processMetaDataId, 
                                'criteria' => $paramDefaultCriteria
                            );

                            $result = $this->ws->caller($row['SERVICE_LANGUAGE_CODE'], $row['WS_URL'], $row['COMMAND_NAME'], 'return', $param, 'array');
                            
                            if ($result['status'] == 'success' && isset($result['result'])) {
                                return $result['result'];
                            }

                        } else {

                            $param = array();

                            foreach ($data as $val) {
                                $defaultValue = Mdmetadata::setDefaultValue($val['DEFAULT_VALUE']);
                                if ($defaultValue) {
                                    $param[$val['PARAM_CODE']] = $defaultValue;
                                }
                            }

                            $result = $this->ws->caller($row['SERVICE_LANGUAGE_CODE'], $row['WS_URL'], $row['COMMAND_NAME'], 'return', $param, 'array');

                            if ($result['status'] == 'success' && isset($result['result'])) {
                                return $result['result'];
                            }
                        }
                    }
                }
            }
        }

        return null;
    }
    
    public function getRunKpiIndicatorGetDataProcessModel($processMetaDataId) {
        
        $response = array();
        
        try {
            
            $kpiIndicatorMapConfig = Input::post('kpiIndicatorMapConfig');
            $mapId = issetParam($kpiIndicatorMapConfig['mapId']);
            
            if (is_numeric($mapId)) {
                
                $idPh1 = $this->db->Param(0);
                $idPh2 = $this->db->Param(1);
                
                $mapList = $this->db->GetAll("
                    SELECT 
                        T1.META_DATA_ID, 
                        T0.TRG_META_DATA_PATH, 
                        T0.DEFAULT_VALUE 
                    FROM KPI_INDICATOR_INDICATOR_MAP T0 
                        INNER JOIN META_DATA T1 ON T1.META_DATA_ID = T0.LOOKUP_META_DATA_ID
                    WHERE T0.SRC_INDICATOR_MAP_ID = $idPh1 
                        AND T0.SEMANTIC_TYPE_ID = $idPh2", 
                    [$mapId, Mdform::$semanticTypes['checkListParamMap']]
                );
                
                if ($mapList) {
                    
                    $getMetaId = $mapList[0]['META_DATA_ID'];
                    
                    $row = $this->db->GetRow("
                        SELECT 
                            MD.META_DATA_CODE AS COMMAND_NAME, 
                            DG.WS_URL, 
                            SL.SERVICE_LANGUAGE_CODE, 
                            DG.SUB_TYPE, 
                            DG.ACTION_TYPE 
                        FROM META_BUSINESS_PROCESS_LINK DG 
                            INNER JOIN META_DATA MD ON MD.META_DATA_ID = DG.META_DATA_ID 
                            LEFT JOIN WEB_SERVICE_LANGUAGE SL ON SL.SERVICE_LANGUAGE_ID = DG.SERVICE_LANGUAGE_ID 
                        WHERE DG.META_DATA_ID = $idPh1", [$getMetaId]);
                    
                    if ($row) {
                        $inputParams = [];
                        
                        foreach ($mapList as $map) {
                            $defaultValue = Mdmetadata::setDefaultValue($map['DEFAULT_VALUE']);
                            $inputParams['criteria'][$map['TRG_META_DATA_PATH']] = [['operator' => '=', 'operand' => $defaultValue]];
                            $inputParams['param'][$map['TRG_META_DATA_PATH']] = $defaultValue;
                        }
                        
                        if ($row['SUB_TYPE'] == 'internal' && $row['ACTION_TYPE'] == 'get') {

                            $param = array(
                                'processId' => $processMetaDataId, 
                                'criteria' => $inputParams['criteria']
                            );

                            $result = $this->ws->caller($row['SERVICE_LANGUAGE_CODE'], $row['WS_URL'], $row['COMMAND_NAME'], 'return', $param, 'array');
                            
                            if ($result['status'] == 'success' && isset($result['result'])) {
                                $response = $result['result'];
                            }

                        } else {

                            $result = $this->ws->caller($row['SERVICE_LANGUAGE_CODE'], $row['WS_URL'], $row['COMMAND_NAME'], 'return', $inputParams['param'], 'array');

                            if ($result['status'] == 'success' && isset($result['result'])) {
                                $response = $result['result'];
                            }
                        }
                    }
                }
            }
        
        } catch (Exception $ex) { }
        
        return $response;
    }
    
    public function getProcessValueByModel($processMetaDataId, $totalCount, $count, $paramRealPath) {

        if (($totalCount > 0 && $count > 0) && $totalCount === $count) {

            $paramRealPath = strtolower($paramRealPath);

            $row = $this->db->GetRow("
                SELECT 
                    LOWER(PAL.PROCESS_GET_PARAM_PATH) AS PROCESS_GET_PARAM_PATH, 
                    PL.WS_URL, 
                    PL.SUB_TYPE, 
                    PL.ACTION_TYPE, 
                    MD.META_DATA_CODE AS COMMAND_NAME, 
                    WS.SERVICE_LANGUAGE_CODE 
                FROM META_PROCESS_PARAM_ATTR_LINK PAL 
                    INNER JOIN META_BUSINESS_PROCESS_LINK PL ON PL.META_DATA_ID = PAL.GET_PROCESS_META_DATA_ID 
                    INNER JOIN META_DATA MD ON MD.META_DATA_ID = PAL.GET_PROCESS_META_DATA_ID 
                    LEFT JOIN WEB_SERVICE_LANGUAGE WS ON WS.SERVICE_LANGUAGE_ID = PL.SERVICE_LANGUAGE_ID 
                WHERE LOWER(PAL.PARAM_REAL_PATH) = '$paramRealPath' 
                    AND PAL.GET_PROCESS_META_DATA_ID IS NOT NULL 
                    AND PAL.PROCESS_META_DATA_ID = $processMetaDataId");

            if ($row && $row['SUB_TYPE'] == 'external') {

                $param = array();

                $paramData = $this->db->GetAll("
                    SELECT 
                        DEFAULT_VALUE, 
                        PARAM_META_DATA_CODE 
                    FROM META_GROUP_PARAM_CONFIG  
                    WHERE LOWER(FIELD_PATH) = '$paramRealPath' 
                        AND MAIN_PROCESS_META_DATA_ID = $processMetaDataId 
                        AND IS_GROUP = 0");

                foreach ($paramData as $paramRow) {
                    $param[$paramRow['PARAM_META_DATA_CODE']] = Mdmetadata::setDefaultValue($paramRow['DEFAULT_VALUE']);
                }

                $result = $this->ws->caller($row['SERVICE_LANGUAGE_CODE'], $row['WS_URL'], $row['COMMAND_NAME'], 'return', $param, 'serialize');

                if ($result['status'] == 'success' && isset($result['result']) && isset($result['result'][$row['PROCESS_GET_PARAM_PATH']])) {
                    return $result['result'][$row['PROCESS_GET_PARAM_PATH']];
                }
            }
        }

        return false;
    }

    public function getWorkSpaceParamMapModel($processMetaDataId, $workSpaceId, $workSpaceParams, $paramList = null) {

        $data = $this->db->GetAll("
            SELECT 
                FIELD_PATH, 
                PARAM_PATH
            FROM META_WORKSPACE_PARAM_MAP 
            WHERE WORKSPACE_META_ID = ".$this->db->Param(0)." 
                AND TARGET_META_ID = ".$this->db->Param(1)."  
                AND IS_TARGET = 1 
            GROUP BY 
                FIELD_PATH, 
                PARAM_PATH", array($workSpaceId, $processMetaDataId));
        
        if ($data) {
            
            $response = array();
            $isResponse = false;

            foreach ($data as $row) {
                $lowerKey = strtolower($row['FIELD_PATH']);

                if (isset($workSpaceParams[$lowerKey])) {
                    $row['PARAM_PATH'] = strtolower($row['PARAM_PATH']);

                    if (Mdwebservice::checkIsHeaderParam($row['PARAM_PATH'])) {
                        
                        $response[$row['PARAM_PATH']] = $workSpaceParams[$lowerKey];
                        
                    } elseif ($paramList) {
                        
                        $groupExplodeKey = explode('.', $row['PARAM_PATH']);
                        $groupFirstKey = $groupExplodeKey[0];

                        array_map(function($val) use ($groupFirstKey, $groupExplodeKey, $workSpaceParams, $lowerKey, &$response) {
                            if ($val['code'] == $groupFirstKey && $val['recordtype'] == 'rows') {
                                $response[$groupFirstKey][] = array($groupExplodeKey[1] => $workSpaceParams[$lowerKey]);
                            } elseif ($val['code'] == $groupFirstKey && $val['recordtype'] == 'row') 
                                $response[$groupFirstKey] = array($groupExplodeKey[1] => $workSpaceParams[$lowerKey]);
                        }, $paramList);
                    }
                    $isResponse = true;
                }
            }

            if ($isResponse) {
                return $response;
            }
        }

        return null;
    }

    public function getProcessFieldMapModel($metaDataId) {
        $data = array();
        
        $result = $this->db->GetAll("
            SELECT 
                THEME_FIELD, 
                PROCESS_FIELD,
                IS_LABEL,
                TAB_NAME
            FROM META_PROCESS_THEME_FIELD_MAP
            WHERE META_DATA_ID = $metaDataId 
            ORDER BY TAB_NAME ASC");

        if (count($result) > 0) {
            foreach ($result as $key => $row) {
                if ($row['TAB_NAME'] == null) {
                    array_push($data, $row);
                    unset($result[$key]);
                }
            }
            return array('header' => $data, 'detial' => $result);
        }
        
        return array();
    }

    public function getMetaTypeId($metaDataId) {
        return $this->db->GetRow("SELECT META_TYPE_ID FROM META_DATA WHERE META_DATA_ID = ".$this->db->Param(0), array($metaDataId));
    }
    
    public function getProcessConfigByCode($processCode, $isId = false) {

        $cache = phpFastCache();
        
        $processCode = strtolower($processCode);

        $data = $cache->get('bpRunProcess_' . $processCode);

        if ($data == null) {
            
            if ($isId) {
                $where = 'MD.META_DATA_ID';
            } else {
                $where = 'LOWER(MD.META_DATA_CODE)';
            }
            
            $data = $this->db->GetRow("
                SELECT 
                    MD.META_DATA_CODE AS COMMAND_NAME, 
                    BP.WS_URL, 
                    SL.SERVICE_LANGUAGE_CODE, 
                    LOWER(BP.SUB_TYPE) AS SUB_TYPE, 
                    LOWER(BP.ACTION_TYPE) AS ACTION_TYPE, 
                    BP.REF_META_GROUP_ID, 
                    BP.WORKIN_TYPE,
                    BP.CLASS_NAME,
                    BP.METHOD_NAME, 
                    BP.META_DATA_ID 
                FROM META_BUSINESS_PROCESS_LINK BP 
                    INNER JOIN META_DATA MD ON MD.META_DATA_ID = BP.META_DATA_ID 
                    LEFT JOIN WEB_SERVICE_LANGUAGE SL ON SL.SERVICE_LANGUAGE_ID = BP.SERVICE_LANGUAGE_ID 
                WHERE $where = ".$this->db->Param(0), array($processCode));

            $cache->set('bpRunProcess_' . $processCode, $data, Mdwebservice::$expressionCacheTime);
        }

        return $data;
    }

    public function runProcessValueModel($postData) {
        
        if (isset($postData['processCode']) && isset($postData['responsePath'])) {
            
            $processCode = $postData['processCode'];
            $responsePath = strtolower($postData['responsePath']);

            $getProcess = self::getProcessConfigByCode($processCode);

            if ($getProcess) {

                if ($getProcess['SUB_TYPE'] == 'internal' && $getProcess['ACTION_TYPE'] == 'get') {

                    $isEmpty = false;
                    $paramCriteria = array();

                    foreach ($postData['paramData'] as $inputField) {
                        if ($inputField['value'] != '') {
                            $paramCriteria[$inputField['inputPath']][] = array(
                                'operator' => '=',
                                'operand' => is_array($inputField['value']) ? Arr::implode_r(',', $inputField['value'], true) : $inputField['value']
                            );
                            $isEmpty = true;
                        } 
                    }

                    if ($isEmpty) {
                        $param['criteria'] = $paramCriteria;

                        $result = $this->ws->caller($getProcess['SERVICE_LANGUAGE_CODE'], $getProcess['WS_URL'], $getProcess['COMMAND_NAME'], 'return', $param, 'serialize');

                        if ($result['status'] == 'success' && isset($result['result'])) {
                            if (isset($result['result'][$responsePath])) {
                                return $result['result'][$responsePath];
                            } elseif (isset($result[$responsePath])) {
                                return $result[$responsePath];                           
                            }
                        }
                    }

                } else {

                    $isEmpty = false;
                    $param = array();

                    foreach ($postData['paramData'] as $inputField) {
                        if (issetParam($inputField['value']) != '') {
                            $value = $inputField['value'];
                            if (is_array($value) && count($value) == 1 && isset($value[0])) {
                                $value = $value[0];
                            }
                            $param[$inputField['inputPath']] = $value;
                            $isEmpty = true;
                        }
                    }

                    if ($isEmpty) {
                        $result = $this->ws->caller($getProcess['SERVICE_LANGUAGE_CODE'], $getProcess['WS_URL'], $getProcess['COMMAND_NAME'], 'return', $param, 'serialize');

                        if ($result['status'] == 'success' && isset($result['result'][$responsePath])) {
                            return $result['result'][$responsePath];
                        }
                    }
                }
            }
        }

        return null;
    }

    public function execProcessModel($postData) {
        
        if ($processId = issetVar($postData['processId'])) {
            
            $process = self::getProcessConfigByCode($processId, true);
            
        } else {
            $processCode = Input::param($postData['processCode']);
            $process = self::getProcessConfigByCode($processCode);
        }

        if ($process) {
            
            if (isset($process['META_DATA_ID'])) {
                $inputParam = self::inputALLParamsModel($process['META_DATA_ID']);
            }
            
            $isEmpty = false;
            $param = [];

            foreach ($postData['paramData'] as $inputField) {
                
                $path = $inputField['inputPath'];
                
                if (is_array($inputField['value'])) {
                    
                    if (strpos($path, '.') !== false) {
                        Arr::set($param, $path, $inputField['value']); 
                    } else {
                        $param[$path] = $inputField['value'];
                    }
                    
                    continue;
                }
                
                if ($inputField['value'] != '') {
                    
                    $pathLower = strtolower($path);
                    $value = Mdmetadata::setDefaultValue($inputField['value']);
                    $dataType = issetParam($inputParam[$pathLower]['dataType']);
                    
                    if ($dataType == 'time') {
                        $value = '1999-01-01 '.$value.':00';
                    }
                    
                    if (strpos($path, '.') !== false) {
                        
                        $pathSplit = explode('.', $path);
                        
                        if (count($pathSplit) == 2) {
                            
                            $groupPath = $pathSplit[0];
                            $groupPathLower = strtolower($groupPath);
                            $fieldPath = $pathSplit[1];

                            if (issetParam($inputParam[$groupPathLower]['recordType']) == 'row') {
                                $param[$groupPath][$fieldPath] = $value;
                            } else {
                                $param[$groupPath][$fieldPath] = $value;
                            }
                        
                        } else {
                            
                            $bracketsKey = '';

                            foreach ($pathSplit as $rk => $rv) {
                                $bracketsKey .= '[\''.$rv.'\']';
                            }
                            
                            eval('$param'.$bracketsKey.' = \''.$value.'\';');
                        }
                        
                    } elseif ($dataType == 'base64_to_file') {
                        $fileData = @base64_decode($value);
                        
                        if ($fileData) {
                            $f = finfo_open();
                            $mimeType = finfo_buffer($f, $fileData, FILEINFO_MIME_TYPE);

                            if ($mimeType == 'text/plain') {
                                return array('status' => 'error', 'message' => 'Wrong content type!');
                            } else {
                                $fileExtension = mimeToExt($mimeType);

                                $filePath = Mdwebservice::bpUploadGetPath();
                                $fileUrl = $filePath.getUID().'.'.$fileExtension;

                                file_put_contents($fileUrl, $fileData);
                                
                                $param[$path] = $fileUrl;
                            }

                        } else {
                            $param[$path] = null;
                        }
                                
                    } else {
                        $param[$path] = $value;
                    }
                    
                    $isEmpty = true;
                } 
            }

            if ($isEmpty) {

                WebService::$addonHeaderParam['windowSessionId'] = getUID();
                $result = $this->ws->caller($process['SERVICE_LANGUAGE_CODE'], $process['WS_URL'], $process['COMMAND_NAME'], 'return', $param, 'serialize');
                
                if (isset($result['status']) && $result['status'] == 'success' && isset($result['result'])) {
                    Mdwebservice::$responseData = $result['result'];
                    self::afterRunAdditionalProcessModel($process['META_DATA_ID'], $process['COMMAND_NAME']);
                }
                
                return $result;
            }
        }

        return null;
    }

    public function getProcessParamModel($postData) {
        
        $processCode = $postData['processCode'];
        $getProcess = self::getProcessConfigByCode($processCode);
        
        if ($getProcess) {

            if ($getProcess['SUB_TYPE'] == 'internal' && $getProcess['ACTION_TYPE'] == 'get') {

                $isEmpty = true;
                $paramCriteria = array();

                foreach ($postData['paramData'] as $inputField) {
                    if ($inputField['value'] != '') {
                        $paramCriteria[$inputField['inputPath']][] = array(
                            'operator' => '=',
                            'operand' => is_array($inputField['value']) ? Arr::implode_r(',', $inputField['value'], true) : $inputField['value']
                        );
                        $isEmpty = false;
                    } 
                }

                if ($isEmpty == false) {

                    $param['criteria'] = $paramCriteria;
                    
                    $result = $this->ws->caller($getProcess['SERVICE_LANGUAGE_CODE'], $getProcess['WS_URL'], $getProcess['COMMAND_NAME'], 'return', $param, 'serialize');
                    
                    if ($result['status'] == 'success' && isset($result['result'])) {
                        return $result['result'];
                    } else {
                        if (Input::postCheck('isShowErrorMsg') && $result['status'] == 'error') {
                            return array('exceptionStatus' => 'error', 'message' => $this->ws->getResponseMessage($result));
                        }
                    }
                }

            } else {

                $isEmpty = true;
                $param = array();

                foreach ($postData['paramData'] as $inputField) {
                    if ($inputField['value'] != '') {
                        $param[$inputField['inputPath']] = $inputField['value'];
                        $isEmpty = false;
                    } 
                }
                
                if ($isEmpty == false) {
                    
                    if ($getProcess['WORKIN_TYPE'] == 'xyp') {
                        
                        $pParam = array();
                        foreach ($param as $key => $row) {

                            $pData = array();
                            $explodeArr = explode('.', $key);
                            $reverseArr = array_reverse($explodeArr);

                            foreach ($reverseArr as $pKey => $sRow) {
                                $tempArr = array();
                                $temp = (sizeof($pData) == 0) ? $row : $pData;
                                $tempArr[$sRow] = $temp;
                                $pData = $tempArr;
                            }

                            $pParam = array_merge_recursive($pParam, $pData);
                        }

                        $this->load->model('Mdintegration', 'middleware/models/');
                        $result = $this->model->callXypService($getProcess, $pParam);

                        if ($result['status'] == 'success' && isset($result['data']['result'])) {
                            return $result['data']['result'];
                        }
                            
                    } else {
                        
                        $result = $this->ws->caller($getProcess['SERVICE_LANGUAGE_CODE'], $getProcess['WS_URL'], $getProcess['COMMAND_NAME'], 'return', $param, 'serialize');
                            
                        if ($result['status'] == 'success' && isset($result['result'])) {
                            return $result['result'];
                        } else {
                            if (Input::postCheck('isShowErrorMsg') && $result['status'] == 'error') {
                                return array('exceptionStatus' => 'error', 'message' => $this->ws->getResponseMessage($result));
                            }
                        }
                    }
                }
            }
        }

        return null;
    }
    
    private function getEmployeeNDDdata($mainMetaDataId, $employeeKeyId, $year, $month) {
        $param = array(
            'systemMetaGroupId' => $mainMetaDataId,
            'criteria' => array(
                'filterEmployeeKeyId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $employeeKeyId
                    )
                ),
                'loanMonth' => array(
                    array(
                        'operator' => '=',
                        'operand' => $month
                    )
                ),
                'year' => array(
                    array(
                        'operator' => '=',
                        'operand' => $year 
                    )
                )
            )
        );

        $result = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getRowDataViewCommand, $param);

        if ($result['status'] == 'success' && isset($result['result'])) {
            return $result['result'];
        }    

        return null;
    }

    public function getNDDprintPositionModel($employeeKeyId, $metaDataId, $printOptions) {

        $isConfig = false;
        $nddYear = Input::param($printOptions['nddYear']);
        $nddYearCode = Input::param($printOptions['nddYearCode']);
        $bookTypeId = Input::param($printOptions['bookTypeId']);

        $dataCustom = $this->db->GetRow("
            SELECT
                '".Input::param($printOptions['nddPageNum'])."' AS PAGE_NUMBER,
                ROW_HEIGHT, 
                COL1_WIDTH, 
                COL2_WIDTH, 
                COL3_WIDTH,
                TOP,
                LEFT_EVEN,
                LEFT_ODD,
                YEARCODE,
                COALESCE(LEFT_TABLE, 0) AS LEFT_TABLE,
                COALESCE(TOP_TABLE, 0) AS TOP_TABLE,
                COALESCE(CENTER, 0) AS CENTER,
                COALESCE(TABLE_WIDTH, 0) AS TABLE_WIDTH              
            FROM HRM_SI_BOOK_TYPE
            WHERE ID = " . $bookTypeId
        );

        $getRows = array();
        $startMonth = 1;
        $endMonth = 1;

        if (!$isConfig) {

            $row = $dataCustom;
            $startMonth = $printOptions['nddMonthPrev'];       
            $endMonth = $printOptions['nddMonthNext'];

            $existData = $this->db->GetOne("SELECT COUNT(ID) FROM HRM_EMP_SI_BOOK WHERE EMPLOYEE_KEY_ID = " . $employeeKeyId);

            if ($printOptions['nddMonthNext'] == '12') {
                $updateData = array(
                    'ROW_NUMBER' => 1, 
                    'PAGE_NUMBER' => ++$printOptions['nddPageNum']
                );
                if ($existData !== '0') 
                    $this->db->AutoExecute('HRM_EMP_SI_BOOK', $updateData, 'UPDATE', "EMPLOYEE_KEY_ID = " . $employeeKeyId);                    
            } else {
                $updateData = array(
                    'ROW_NUMBER' => ++$printOptions['nddMonthNext']
                );                    
                if ($existData !== '0') 
                    $this->db->AutoExecute('HRM_EMP_SI_BOOK', $updateData, 'UPDATE', "EMPLOYEE_KEY_ID = " . $employeeKeyId);
            }

            if ($existData == '0') {
                $insertData = array_merge($updateData, array(
                    'ID' => getUID(),
                    'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s'),
                    'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                    'EMPLOYEE_KEY_ID' => $employeeKeyId,
                ));
                $insertData['PAGE_NUMBER'] = isset($insertData['PAGE_NUMBER']) ? $insertData['PAGE_NUMBER'] : $printOptions['nddPageNum'];
                $this->db->AutoExecute('HRM_EMP_SI_BOOK', $insertData);
            }                

            $getRows['previewSize'] = array(
                'left' => $dataCustom['LEFT_TABLE'],
                'top' => $dataCustom['TOP_TABLE'],
                'between' => $dataCustom['CENTER'],
                'head_height' => $dataCustom['TOP'] - $dataCustom['TOP_TABLE'],
                'col1Width' => $dataCustom['COL1_WIDTH'],
                'col2Width' => $dataCustom['COL2_WIDTH'],
                'col3Width' => $dataCustom['COL3_WIDTH'],                    
                'width' => $dataCustom['TABLE_WIDTH'],
                'rowHeight' => $dataCustom['ROW_HEIGHT'], 
                'height' => ($dataCustom['ROW_HEIGHT'] * 12 + $dataCustom['TOP'] - $dataCustom['TOP_TABLE'])
            );                
        } else {
            if ($row) {
                if ($row['ROW_NUMBER'] == '12') {
                    $this->db->AutoExecute('HRM_EMP_SI_BOOK', array('ROW_NUMBER' => 1, 'PAGE_NUMBER' => ++$row['ROW_NUMBER']), 'UPDATE', "EMPLOYEE_KEY_ID = " . $employeeKeyId);
                } else
                    $this->db->AutoExecute('HRM_EMP_SI_BOOK', array('ROW_NUMBER' => ++$row['ROW_NUMBER']), 'UPDATE', "EMPLOYEE_KEY_ID = " . $employeeKeyId);                    

                $getRows['previewSize'] = array(
                    'left' => $row['LEFT_TABLE'],
                    'top' => $row['TOP_TABLE'],
                    'between' => $row['CENTER'],
                    'head_height' => $row['TOP'] - $row['TOP_TABLE'],
                    'col1Width' => $row['COL1_WIDTH'],
                    'col2Width' => $row['COL2_WIDTH'],
                    'col3Width' => $row['COL3_WIDTH'],
                    'width' => $row['TABLE_WIDTH'], 
                    'rowHeight' => $dataCustom['ROW_HEIGHT'], 
                    'height' => ($row['ROW_HEIGHT'] * 12 + $row['TOP'] - $row['TOP_TABLE'])
                );        
            } else
                return false;
        }

        for ($ii = $startMonth; $ii <= $endMonth; $ii++) {

            if (!$isConfig) {
                $row['ROW_NUMBER'] = $ii;
            }

            $nddData = $this->getEmployeeNDDdata($metaDataId, $employeeKeyId, $nddYearCode, $row['ROW_NUMBER']); 

            if (is_null($nddData))
                $nddData = array(
                    'f100' => '',
                    'f101' => '',
                    'f102' => ''
                );

            if ((int) $row['PAGE_NUMBER'] % 2 == 0) {
                $colOneLeft = $row['LEFT_EVEN'];
                $colTwoLeft = $row['LEFT_EVEN'] + $row['COL1_WIDTH'];
                $colThreeLeft = $row['LEFT_EVEN'] + $row['COL1_WIDTH'] + $row['COL2_WIDTH'];                            
            } else {
                $colOneLeft = $row['LEFT_ODD'];
                $colTwoLeft = $row['LEFT_ODD'] + $row['COL1_WIDTH'];
                $colThreeLeft = $row['LEFT_ODD'] + $row['COL1_WIDTH'] + $row['COL2_WIDTH'];
            }

            $top = round($row['TOP'] + ($row['ROW_HEIGHT'] * $row['ROW_NUMBER'] - $row['ROW_HEIGHT']), 2);

            array_push($getRows, array(
                'colOneLeft' => $colOneLeft,
                'colTwoLeft' => $colTwoLeft,
                'colThreeLeft' => $colThreeLeft,
                'top' => $top,
                'col1Data' => $nddData['f100'] == 0 || is_null($nddData['f100']) ? '' : Number::formatMoney(intval($nddData['f100'])),
                'col2Data' => $nddData['f101'] == 0 || is_null($nddData['f101']) ? '' : Number::formatMoney(intval($nddData['f101'])),
                'col3Data' => $nddData['f102'] == 0 || is_null($nddData['f102']) ? '' : Number::formatMoney(intval($nddData['f102']))
            ));
        }

        return $getRows;
    }        

    public function getEmployeePrintConfigModel($employeeKeyId) {
        $data = $this->db->GetRow("
            SELECT 
                ID,
                ROW_NUMBER,
                PAGE_NUMBER,
                BOOK_TYPE_ID
            FROM HRM_EMP_SI_BOOK
            WHERE EMPLOYEE_KEY_ID = " . $employeeKeyId
        );

        return $data;
    }        

    public function getNDDprintPositionCheckModel($employeeKeyId, $printOptions) {
        $data = $this->db->GetRow("
            SELECT 
                ID,
                ROW_NUMBER,
                PAGE_NUMBER,
                BOOK_TYPE_ID
            FROM HRM_EMP_SI_BOOK
            WHERE EMPLOYEE_KEY_ID = " . $employeeKeyId . " AND (BOOK_TYPE_ID != " . $printOptions['nddYear'] . " OR PAGE_NUMBER != " . $printOptions['nddPageNum'] . ")"
        );

        return $data;
    }        

    public function getEmployeeNameModel($employeeKeyId) {
        $data = $this->db->GetOne("
            SELECT 
                SUBSTR(BP.LAST_NAME, 0, 1) || '.' || BP.FIRST_NAME
            FROM HRM_EMPLOYEE_KEY EK
                INNER JOIN HRM_EMPLOYEE EM ON EM.EMPLOYEE_ID = EK.EMPLOYEE_ID
                INNER JOIN BASE_PERSON BP ON BP.PERSON_ID = EM.PERSON_ID
            WHERE EK.EMPLOYEE_KEY_ID = " . $employeeKeyId
        );

        return $data;
    }        

    public function getNDDprintYearModel() {
        return array(
            array(
                'id' => '2016',
                'code' => '2016'
            ), 
            array(
                'id' => '2017',
                'code' => '2017'
            ),
            array(
                'id' => '2018',
                'code' => '2018'
            ),
            array(
                'id' => '2019',
                'code' => '2019'
            ),
            array(
                'id' => '2020',
                'code' => '2020'
            ),
            array(
                'id' => '2021',
                'code' => '2021'
            ),
            array(
                'id' => '2022',
                'code' => '2022'
            ),
            array(
                'id' => '2023',
                'code' => '2023'
            ),
            array(
                'id' => '2024',
                'code' => '2024'
            ),
            array(
                'id' => '2025',
                'code' => '2025'
            ),
        );
    }

    public function getDmProcessDtlModel($dmMetaDataId, $processId) {
        
        $cache = phpFastCache();
        $dataRow = $cache->get('dvAutoMap_'.$dmMetaDataId.'_'.$processId);
        
        if ($dataRow == null) {
            
            $dataRow = $this->db->GetRow("
                SELECT 
                    IS_AUTO_MAP, 
                    AUTO_MAP_SRC, 
                    AUTO_MAP_ON_DELETE, 
                    AUTO_MAP_ON_UPDATE, 
                    LOWER(AUTO_MAP_SRC_PATH) AS AUTO_MAP_SRC_PATH, 
                    LOWER(AUTO_MAP_SRC_TABLE_NAME) AS AUTO_MAP_SRC_TABLE_NAME, 
                    AUTO_MAP_DELETE_PROCESS_ID, 
                    AUTO_MAP_DATAVIEW_ID, 
                    AUTO_MAP_NAME_PATTERN, 
                    AUTO_MAP_TRG_NAME_PATTERN 
                FROM META_DM_PROCESS_DTL  
                WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                    AND PROCESS_META_DATA_ID = ".$this->db->Param(1), 
                array($dmMetaDataId, $processId)
            ); 

            if ($dataRow) {
                $cache->set('dvAutoMap_'.$dmMetaDataId.'_'.$processId, $dataRow, Mdwebservice::$expressionCacheTime);
            }
        }

        return $dataRow;
    }        

    public function getBpTemplateDropDownListModel($processId, $bpTemplateId = null) {
            
        if ($bpTemplateId) {

            $rowTemp = $this->db->GetRow("
                SELECT 
                    ID, 
                    HTML_FILE_PATH, 
                    CONTENT_ID,
                    CONFIRM_TYPE,
                    CONTROL_DESIGN
                FROM META_BUSINESS_PROCESS_TEMPLATE 
                WHERE ID = $bpTemplateId 
                    AND HTML_FILE_PATH IS NOT NULL");

            if ($rowTemp) {
                return array('id' => $rowTemp['ID'], 'htmlTemplate' => $rowTemp['HTML_FILE_PATH'], 'confirmType' => $rowTemp['CONFIRM_TYPE'], 'contentId' => $rowTemp['CONTENT_ID'], 'controlDesign' => $rowTemp['CONTROL_DESIGN'], 'dropdown' => '');
            }
        }

        $html = array();
        $data = $this->db->GetAll("
            SELECT 
                ID, 
                TEMPLATE_CODE, 
                TEMPLATE_NAME, 
                IS_DEFAULT, 
                CONTROL_DESIGN,
                HTML_FILE_PATH, 
                CONTENT_ID 
            FROM META_BUSINESS_PROCESS_TEMPLATE 
            WHERE META_DATA_ID = $processId 
                AND IS_ACTIVE = 1 
                AND HTML_FILE_PATH IS NOT NULL");

        if (count($data) > 1) {

            $attr = array(
                'class' => 'form-control select2 form-control-sm bp-template-id',
                'id' => 'bpTemplateId_'.$processId, 
                'data' => $data,
                'op_value' => 'ID', 
                'op_text' => 'TEMPLATE_CODE| |-| |TEMPLATE_NAME', 
                'style' => 'width: 100%'
            );

            foreach ($data as $row) {

                if ($bpTemplateId) {

                    if ($row['ID'] == $bpTemplateId) {
                        $attr['value'] = $row['ID'];
                        $html['controlDesign'] = $row['CONTROL_DESIGN'];
                        $html['htmlTemplate'] = $row['HTML_FILE_PATH'];
                        $html['contentId'] = $row['CONTENT_ID'];
                        $html['id'] = $row['ID'];
                    }

                } else {
                    if ($row['IS_DEFAULT'] == '1') {
                        $attr['value'] = $row['ID'];
                        $html['controlDesign'] = $row['CONTROL_DESIGN'];
                        $html['htmlTemplate'] = $row['HTML_FILE_PATH'];
                        $html['contentId'] = $row['CONTENT_ID'];
                        $html['id'] = $row['ID'];
                    }
                }
            }

            if ($bpTemplateId) {
                $html['dropdown'] = '';
            } else {
                $html['dropdown'] = Form::select($attr).'<br /><br />';
            }

        } else {
            $html['dropdown'] = '';
            $html['controlDesign'] = $data[0]['CONTROL_DESIGN'];
            $html['htmlTemplate'] = $data[0]['HTML_FILE_PATH'];
            $html['id'] = $data[0]['ID'];
            $html['contentId'] = $data[0]['CONTENT_ID'];
        }

        return $html;
    }

    public function getBPTemplateWidgetsByIdModel($id, $methodId) {
        
        if (Config::getFromCache('isBpTemplateNormalMode')) {
            return null;
        }
        
        $cache = phpFastCache();
        $dataRow = null; //$cache->get('ntTemplateWidgetsById_' . $id . '_' . $methodId);
        
        if ($dataRow == null) {
            $data = $this->db->GetAll("
                SELECT  
                    TC.WIDGET_CODE,
                    NT.TAG,
                    TC.PATH,
                    TC.PATH_AS,
                    TC.IS_ADD_BUTTON AS BTN,
                    ".$this->db->IfNull('TC.IS_ADD_FOLLOW', '1')." AS IS_ADD_FOLLOW,
                    NT.BODY,
                    TC.EXPRESSION,
                    TC.EXPRESSION_DTL,
                    TC.EXPRESSION_DTL_KEY,
                    TC.TAXONOMY_ID,
                    TC.IS_MULTI, 
                    TC.IS_HIGHLIGHT , 
                    TC.IS_COPY_BUTTON  
                FROM NTR_TAXONOMY_CONFIG TC 
                    LEFT JOIN NTR_TAXONOMY NT ON NT.ID = TC.TAXONOMY_ID
                WHERE TC.TEMPLATE_ID = $id
                ORDER BY TC.WIDGET_CODE"); 

            if ($data) {
                $dataRow = Arr::groupByArray($data, 'WIDGET_CODE');
                $cache->set('ntTemplateWidgetsById_' . $id . '_' . $methodId, $dataRow, Mdwebservice::$expressionCacheTime);
            }
        }
        
        return $dataRow;
    }

    public function getBPTemplateWidgetsExpByIdModel($id, $groupedData, $methodId) {
        $cache = phpFastCache();
        $dataRow = $cache->get('ntTemplateWidgetsExpById_' . $id . '_' . $methodId);
        
        if ($dataRow == null) {
            
            $dataRow = array();
            
            if ($groupedData) {
            
                foreach ($groupedData as $k => $v) {
                    $dataExp = $this->db->GetAll("
                        SELECT 
                            NT.*, 
                            TC.PATH 
                        FROM NTR_TAXONOMY_CONFIG TC
                            INNER JOIN NTR_TAXONOMY_WIDGET NT ON NT.TAXONOMY_CONFIG_ID = TC.ID
                        WHERE TC.TEMPLATE_ID = $id AND TC.WIDGET_CODE = '" . $k . "'");

                    $dataRow[$k] = $dataExp;
                }
            }
            
            $cache->set('ntTemplateWidgetsExpById_' . $id . '_' . $methodId, $dataRow, Mdwebservice::$expressionCacheTime);
        }

        return $dataRow;
    }

    public function billPrintModel() {

        $htmlContent = file_get_contents(BASEPATH . 'middleware/views/webservice/addon/widget/bill_template/person.html');

        $bookNumber = Input::post('bookNumber');        
        $billNumber = Input::post('billNumber');     
        $billPrice = Input::post('billPrice');
        $billPaid = Input::post('billPaid');
        $billChange = Input::post('billChange');

        $uniqNumber = '000000000038001160801291673119742';
        $qrData = '11670877665124642102565330940568318052787303610494947034756400207686728602464942847158944115997679284300888956996450729328512205468098950820498579445130746507212108516133540240099338731307810027711453292509273345959666061452096698516317799970031361232114961236841283071094707072178161487932877715118997333722942125767';

        $itemList = '<tr>
            <td style="white-space: nowrap;overflow: hidden;font-family: Tahoma; font-size: 15px; font-weight: normal; padding: 1px 0; text-align: left;">
                <span style="font-family: Tahoma; ">Ерөнхий итгэмжлэл</span>
            </td>
            <td style="font-family: Tahoma; font-size: 15px; font-weight: normal; padding: 1px 0; text-align: right;">
                2,000
            </td>
            <td style="font-family: Tahoma; font-size: 15px; font-weight: normal; padding: 1px 0; text-align: right;">
                1
            </td>
            <td style="font-family: Tahoma; font-size: 15px; font-weight: normal; padding: 1px 0; text-align: right;">
                2,000
            </td>
        </tr>';

        $searchReplace = array(
            '{companyName}', 
            '{ddtd}', 
            '{date}', 
            '{vatNumber}', 
            '{bookNumber}', 
            '{invoiceNumber}', 
            '{itemList}', 
            '{totalAmount}', 
            '{style}', 
            '{noVatAmount}', 
            '{vatAmount}', 
            '{paidAmount}', 
            '{cardAmount}', 
            '{changeAmount}', 
            '{lottery}', 
            '{qrCode}', 
            '{barCode}', 
            '{barCodeText}'
        );
        $replaced = array(
            'Монголын Нотариатчдын Танхим', 
            $uniqNumber, 
            Date::currentDate('Y/m/d H:i:s'), 
            '5456879', 
            $bookNumber, 
            $billNumber, 
            $itemList, 
            Number::formatMoney($billPrice), 
            '', 
            Number::formatMoney($billPrice), 
            Number::formatMoney($billPrice), 
            Number::formatMoney($billPaid), 
            Number::formatMoney(0), 
            Number::formatMoney($billChange), 
            'NT 89102546', 
            Mdwebservice::invoiceQrCode($qrData), 
            '<img src="' . URL . 'mdwebservice/invoiceBarCode/' . $uniqNumber . '/40/horizontal/code25">', 
            $uniqNumber 
        );

        $printData = str_replace($searchReplace, $replaced, $htmlContent);

        return $printData;
    }

    public function getMainWfmProcessModel($dmMetaDataId, $processMetaDataId) {
        
        $cache = phpFastCache();
        $bps = $cache->get('dvBps_'.$dmMetaDataId);

        if ($bps == null) {
            
            $bps = array();
            $data = $this->db->GetAll("
                SELECT 
                    IS_MAIN, 
                    IS_WORKFLOW, 
                    PROCESS_META_DATA_ID 
                FROM META_DM_PROCESS_DTL 
                WHERE MAIN_META_DATA_ID = $dmMetaDataId 
                    AND (IS_MAIN = 1 OR IS_WORKFLOW = 1) 
                ORDER BY ORDER_NUM ASC"); 
            
            if ($data) {
                foreach ($data as $row) {
                    $bps[$row['PROCESS_META_DATA_ID']] = array(
                        'isMain' => $row['IS_MAIN'], 
                        'isWorkflow' => $row['IS_WORKFLOW'] 
                    );
                }
            }
            
            $cache->set('dvBps_'.$dmMetaDataId, $bps, Mdwebservice::$expressionCacheTime);
        }
        
        if (isset($bps[$processMetaDataId])) {
            return $bps[$processMetaDataId];
        }
        return array('isMain' => 0, 'isWorkflow' => 0);
    }

    public function getMainProcessByCreateModel($dmMetaDataId) {
        return $this->db->GetRow("SELECT PROCESS_META_DATA_ID FROM META_DM_PROCESS_DTL WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." AND IS_MAIN = 1 ORDER BY ORDER_NUM", array($dmMetaDataId));
    }

    public function getProcessActiveCheckListModel($processMetaDataId) {
        $data = $this->db->GetAll("
            SELECT 
                CT.PROCESS_CHECKLIST_ID,  
                PC.NAME, 
                CT.IS_MANDATORY, 
                CT.PROCESS_META_DATA_ID, 
                CT.WIDGET_CODE, 
                CT.RENDER_TYPE, 
                CT.ID AS MAP_ID, 
                PC.GROUP_ID 
            FROM META_PROCESS_CHECKLIST_TEMP CT 
                INNER JOIN META_PROCESS_CHECKLIST PC ON PC.ID = CT.PROCESS_CHECKLIST_ID 
            WHERE CT.PROCESS_ID = $processMetaDataId 
                AND CT.IS_ACTIVE = 1 
            ORDER BY PC.ORDER_NUM ASC");

        return $data;
    }

    public function addMultipleRowsBtn($uniqId, $params) {
        $addMultiRowsBtn = '<div class="input-group quick-item-process float-left bp-add-ac-row my-1" data-action-path="' . $params['code'] . '">';
        $addMultiRowsBtn .= '<div class="input-icon">';
        $addMultiRowsBtn .= '<i class="fa fa-search"></i>';
        $addMultiRowsBtn .= Form::text(
            array(
                'name' => '',
                'id' => '',
                'class' => 'form-control form-control-sm lookup-code-hard-autocomplete',
                'style' => 'padding-left:25px !important; border-color: #d2dae2;',
                'data-processid' => $params['methodId'],
                'data-lookupid' => $params['groupKeyLookupMeta'],
                'data-path' => $params['paramPath'],
                'data-in-param' => $params['groupConfigParamPath'],
                'data-in-lookup-param' => $params['groupConfigLookupPath']
            )
        );
        $addMultiRowsBtn .= '</div>';
        $addMultiRowsBtn .= '<span class="input-group-btn">';
        $addMultiRowsBtn .= Form::button(array('data-action-path' => $params['code'], 'class' => 'btn btn-xs green-meadow bp-group-save',
                            'value' => '<i class="icon-plus3 font-size-12"></i>', 'onclick' => 'bpAddMainMultiRow_' . $uniqId . '(this, \'' . $params['methodId'] . '\', \'' . $params['groupKeyLookupMeta'] . '\', \'\', \'' . $params['paramPath'] . '\', \'autocomplete\');'));
        $addMultiRowsBtn .= '</span>';
        $addMultiRowsBtn .= '</div>';           

        return $addMultiRowsBtn;
    }      

    public function getTaxonamyByTagModel($tag) {
        $cache = phpFastCache();
        $dataRow = $cache->get('ntTaxonamyByTag_' . $tag);
        if ($dataRow == null) {
            $dataRow = $this->db->GetRow("SELECT ID, TAG, BODY, '' AS EXPRESSION, '' AS WIDGET_CODE FROM NTR_TAXONOMY WHERE UPPER(TAG) = '" . Str::upper(Input::param($tag)) . "'");
            
            $cache->set('ntTaxonamyByTag_' . $tag, $dataRow, Mdwebservice::$expressionCacheTime);
        }
        return $dataRow;
    }

    public function getTaxonamyKeyByTagModel($tag) {
        $cache = phpFastCache();
        $dataRow = $cache->get('ntTaxonamyKeyByTag_' . $tag);
        if ($dataRow == null) {
            $dataRow = $this->db->GetRow("SELECT ID, TAG, BODY, '' AS EXPRESSION, '' AS WIDGET_CODE FROM NTR_TAXONOMY WHERE UPPER(TAG) = '" . Str::upper(Input::param($tag)) . "'");
            
            $cache->set('ntTaxonamyKeyByTag_' . $tag, $dataRow, Mdwebservice::$expressionCacheTime);
        }
        return $dataRow;
    }

    public function searchMetaGroupProcessModel($meta, $paramList) {
        foreach ($paramList as $k => $row) {
            if ($row['type'] == 'detail' && Str::lower($row['code']) == Str::lower($meta)) {
                return $row;
            }
        }

        return false;
    }
    
    public function searchMetaHeaderGroupProcessModel($paramList) {
        foreach ($paramList as $k => $row) {
            if ($row['type'] == 'header') {
                return $row;
            }
        }

        return false;
    }
    
    public function getTaxonamyConfigByTagModel($tag, $bpTemplateId) {
        $cache = phpFastCache();
        $dataRow = $cache->get('ntTaxonamyConfigByTag_' . $tag . '_' . $bpTemplateId);
        if ($dataRow == null) {
            $dataRow = $this->db->GetRow("SELECT 
                                                NT.ID, 
                                                NT.TAG, 
                                                NT.BODY, 
                                                NTC.PATH, 
                                                NTC.EXPRESSION, 
                                                NTC.WIDGET_CODE
                                            FROM NTR_TAXONOMY NT
                                            INNER JOIN NTR_TAXONOMY_CONFIG NTC ON NTC.TAXONOMY_ID = NT.ID
                                            WHERE UPPER(NT.TAG) = '" . Str::upper(Input::param($tag)) . "' AND NTC.TEMPLATE_ID = " . $bpTemplateId);
            
            $cache->set('ntTaxonamyConfigByTag_' . $tag . '_' . $bpTemplateId, $dataRow, Mdwebservice::$expressionCacheTime);
        }
        return $dataRow;
    }

    public function getTaxonamyConfigByPathModel($path, $metaAs, $bpTemplateId) {
        
        if (strlen($path) < 40) {
            
            $cache = phpFastCache();
            $dataRow = $cache->get('ntTaxonamyConfigByPath_' . $path . '_' . $metaAs . '_' . $bpTemplateId);
            
            if ($dataRow == null) {
                $dataRow = $this->db->GetRow("
                    SELECT 
                        NT.ID, 
                        NT.TAG, 
                        NT.BODY, 
                        NTC.ID AS CONFIG_ID,
                        NTC.PATH,
                        NTC.PATH_AS,
                        NTC.EXPRESSION,
                        NTC.IS_PICTURE,
                        NTC.IS_ADD_BUTTON, 
                        NTC.IS_COPY_BUTTON ,
                        ".$this->db->IfNull('NTC.IS_ADD_FOLLOW', '1')." AS IS_ADD_FOLLOW,
                        NTC.WIDGET_CODE
                    FROM NTR_TAXONOMY NT
                        LEFT JOIN NTR_TAXONOMY_CONFIG NTC ON NTC.TAXONOMY_ID = NT.ID
                    WHERE UPPER(NTC.PATH) = " . $this->db->Param(0) . " 
                        AND UPPER(NTC.PATH_AS) = " . $this->db->Param(1) . " 
                        AND NTC.TEMPLATE_ID = " . $this->db->Param(2), 
                    array(Str::upper(Input::param($path)), Str::upper(Input::param($metaAs)), $bpTemplateId)
                );

                $cache->set('ntTaxonamyConfigByPath_' . $path . '_' . $metaAs . '_' . $bpTemplateId, $dataRow, Mdwebservice::$expressionCacheTime);
            }
        } else {
            $dataRow = array();
        }
        
        return $dataRow;
    }

    public function getTaxonamyConfigByTemplateIdModel($bpTemplateId, $methodId) {
        $cache = phpFastCache();
        $dataRow = $cache->get('ntTaxonamyConfigByTemplateId_' . $bpTemplateId . '_' . $methodId);
        if ($dataRow == null) {
            $dataRow = $this->db->GetAll("SELECT 
                                            NTC.PATH, 
                                            NTC.EXPRESSION, 
                                            NTC.TAXONOMY_ID
                                        FROM NTR_TAXONOMY_CONFIG NTC
                                        WHERE NTC.TEMPLATE_ID = " . $bpTemplateId);
            
            $cache->set('ntTaxonamyConfigByTemplateId_' . $bpTemplateId . '_' . $methodId, $dataRow, Mdwebservice::$expressionCacheTime);
        }
        return $dataRow;
    }
    
    public function getBpTemplateMapDataModel($bpTemplateId, $uniqId, $metaDataId, $dmMetaDataId, $methodRow, $isEditMode) {
        $tabHtml = $tabEndHtml = $tabStart = $tabEnd = $ticket = '';
        $helpContentId = ($methodRow['HELP_CONTENT_ID']) ? $methodRow['HELP_CONTENT_ID'] : '1'; 
        $bpTemplateData = self::getBpTemplateMapData($bpTemplateId);
        
        if ($bpTemplateData) {
            $ticket = '1';
            
            $bpMainData = $this->db->GetRow("SELECT  
                                                    T1.ID, 
                                                    T1.TEMPLATE_NAME, 
                                                    T1.TEMPLATE_CODE,
                                                    T1.META_DATA_ID,
                                                    CASE WHEN T1.ID = $bpTemplateId THEN 1 ELSE 0 END AS IS_MAIN
                                                FROM META_BP_TEMPLATE_FOLDER_MAP T0
                                                INNER JOIN META_BUSINESS_PROCESS_TEMPLATE T1 ON T0.TEMPLATE_ID = T1.ID
                                                WHERE T0.TEMPLATE_ID = $bpTemplateId");
            
            foreach ($bpTemplateData as $row) {
                $tabHtml .= '<li style="margin-left: 7px;" class="nav-item hidden" bp-trg-meta="1" trg-metadataid = "'. $row['META_DATA_ID'] . '_' . $row['TEMPLATE_CODE'] .'"><a href="#bp_template_tab_'. $row['ID'] .'" data-bptemplate-tab="'. $uniqId .'" data-toggle="tab" onclick="renderAddBpTab(\''.$row['ID'].'\', \''. $row['META_DATA_ID'] .'\', \''.$dmMetaDataId.'\', \''.$uniqId.'\', this);" trg-metadataid = "'. $row['META_DATA_ID'] .'" data-required="' . $row['ID'] . '" bptab-status="close" class="nav-link">'
                        . ($row['ID'] == '2' ? '<span class="required">*</span>' : '') . $row['TEMPLATE_NAME']
                        .'</a></li>';
                $tabEndHtml .= '<div class="tab-pane" id="bp_template_tab_'. $row['ID'] .'" is-bp-trg-meta="1" data-metadata-id="'. $row['ID'] .'"></div>';
            }
            
            if ($tabHtml != '') {
                $tabStart = '<div class="meta-toolbar">';
                
                if (defined('CONFIG_TOP_MENU') && CONFIG_TOP_MENU) { 
                    $tabStart = '<div class="meta-toolbar meta-toolbar-'. $metaDataId .'">';
                }
                
                $tabStart .= '<span>' . Lang::line($methodRow['META_DATA_NAME']) . '</span>';
                    $tabStart .= '<div class="ml-auto">
                                    ' . Form::button(
                                        array(
                                            'class' => 'btn btn-info btn-circle btn-sm float-left mr5',
                                            'value' => 'Тусламж',
                                            'onclick' => 'getHelpContent('. $helpContentId .');'
                                        ), (!is_null($helpContentId) ? true : false)
                                        ) . html_tag('button', array(
                                                'type' => 'button', 
                                                'class' => 'btn btn-sm btn-circle btn-success bpMainSaveButton',
                                                'onclick' => 'runBpTemplateMap(this, \''.$dmMetaDataId.'\', \''.$uniqId.'\', '.json_encode($isEditMode).', undefined, qrGenerateProcessAfterSave);', 
                                                'data-dm-id' => $dmMetaDataId 
                                            ), 
                                            '<i class="fa fa-save"></i> Хадгалах'
                                        ) . '
                                </div>
                        </div>
            <div class="hide mt10" id="boot-fileinput-error-wrap"></div>';
                
                
                $tabStart .= '
                            <div class="tabbable-line bpTemplatemap bpTemplatemap-'. $metaDataId .'">
                                <ul class="nav nav-tabs bp-addon-tab mainbp-window-'. $metaDataId .'">
                                    <li class="main-tab nav-item" bp-trg-meta="0">
                                        <a href="#bp_main_tab_'.$uniqId.'" bp-templateid="'. $bpTemplateId .'" data-bptemplate-tab="'. $uniqId .'" main-bptemplate-tab="'. $dmMetaDataId .'" src-metadataid="'. $metaDataId .'" data-toggle="tab" bptab-status="open" class="nav-link active">' . $bpMainData['TEMPLATE_NAME'] 
                                            . '<span class="maintab"><i class="icon-plus3 font-size-12"></i></span>'
                                        . '</a>
                                    </li>
                                    ' . $tabHtml . '
                                </ul>
                            <div class="tab-content bpTemplatemap-'. $uniqId .'" is-bp-trg-meta="0">
                                <div class="tab-pane active" id="bp_main_tab_'.$uniqId.'">';

                $tabEnd = '</div><!-- tab -->
                            ' . $tabEndHtml . '
                            </div><!-- tab -->
                            </div><!-- tab -->';
            }
        } else {
            $ticket = '0';
        }
        
        $bpCriteria = $this->db->GetAll("SELECT ID, SRC_PARAM_NAME, TRG_META_DATA_ID, TRG_PARAM_NAME, DEFAULT_VALUE, MAIN_SRC_PARAM_NAME FROM META_SRC_TRG_PARAM WHERE SRC_META_DATA_ID = $metaDataId AND SRC_PARAM_NAME IS NOT NULL AND TRG_PARAM_NAME IS NOT NULL AND TRG_META_DATA_ID IS NOT NULL");
        $mergedPath = $this->db->GetAll("SELECT MAIN_SRC_PARAM_NAME FROM META_SRC_TRG_PARAM WHERE SRC_META_DATA_ID = $metaDataId AND SRC_PARAM_NAME IS NOT NULL AND TRG_PARAM_NAME IS NOT NULL AND TRG_META_DATA_ID IS NOT NULL AND MAIN_SRC_PARAM_NAME IS NOT NULL GROUP BY MAIN_SRC_PARAM_NAME ORDER BY MAIN_SRC_PARAM_NAME");
        
        return array('mainTab' => array('tabStart' => $tabStart, 'tabEnd' => $tabEnd, 'ticket' => $ticket), 'criteria' => $bpCriteria, 'mergedpath' => $mergedPath);
    }
    
    public function getBpTemplateMapData($bpTemplateId, $notInId = '0') {
        return  $this->db->GetAll("SELECT  
                                        T1.ID, 
                                        T1.TEMPLATE_NAME, 
                                        T1.TEMPLATE_CODE,
                                        T1.META_DATA_ID,
                                        CASE WHEN T1.ID = $bpTemplateId THEN 1 ELSE 0 END AS IS_MAIN
                                    FROM META_BP_TEMPLATE_FOLDER_MAP T0
                                    INNER JOIN META_BUSINESS_PROCESS_TEMPLATE T1 ON T0.TEMPLATE_ID = T1.ID
                                    WHERE T0.TEMPLATE_ID IN (SELECT TRG_TEMPLATE_ID FROM META_BP_TEMPLATE_MAP WHERE SRC_TEMPLATE_ID = $bpTemplateId) AND T1.META_DATA_ID NOT IN ($notInId)");
    }
    
    public function bpDetailColumnsModel($processId, $parentId, $groupPath) {
        
        loadPhpFastCache();
        $cache = phpFastCache();
        
        $bpDtl = $cache->get('bpDtlUserFields_'.$processId.'_'.$parentId);

        if ($bpDtl == null) {
            
            $idPh1 = $this->db->Param(0);
            $idPh2 = $this->db->Param(1);
            
            $bpDtl = $this->db->GetAll("
                SELECT 
                    DD.PARAM_PATH AS PARAM_REAL_PATH, 
                    DD.LABEL_NAME 
                FROM CUSTOMER_BP_DETAIL_DEFAULT DD 
                    INNER JOIN META_PROCESS_PARAM_ATTR_LINK PAL ON PAL.PROCESS_META_DATA_ID = $idPh1 
                        AND PAL.IS_INPUT = 1 
                        AND PAL.IS_SHOW = 1 
                        AND LOWER(PAL.PARAM_REAL_PATH) = LOWER(DD.PARAM_PATH) 
                WHERE DD.PROCESS_META_DATA_ID = $idPh1 
                    AND LOWER(DD.GROUP_PATH) = $idPh2 
                ORDER BY DD.ORDER_NUM ASC", 
                array($processId, strtolower($groupPath))
            );
            
            if (!$bpDtl) {
                
                $bpDtl = $this->db->GetAll("
                    SELECT 
                        PARAM_REAL_PATH, 
                        LABEL_NAME, 
                        DATA_TYPE 
                    FROM META_PROCESS_PARAM_ATTR_LINK 
                    WHERE PROCESS_META_DATA_ID = $idPh1 
                        AND IS_INPUT = 1 
                        AND IS_SHOW = 1 
                        AND IS_USER_CONFIG = 1 
                        AND PARAM_REAL_PATH LIKE '$groupPath.%' 
                        AND (
                            (LENGTH(PARAM_REAL_PATH) - LENGTH(REPLACE(PARAM_REAL_PATH, '.', ''))) = 1 
                            OR 
                            (LENGTH(PARAM_REAL_PATH) - LENGTH(REPLACE(PARAM_REAL_PATH, '.', ''))) = 2 
                        )
                    ORDER BY ORDER_NUMBER ASC", 
                    array($processId)
                );
                
                /*$bpDtl = $this->db->GetAll("
                    SELECT 
                        PARAM_REAL_PATH, 
                        LABEL_NAME, 
                        RECORD_TYPE 
                    FROM META_PROCESS_PARAM_ATTR_LINK 
                    WHERE PROCESS_META_DATA_ID = $idPh1 
                        AND PARENT_ID = $idPh2 
                        AND IS_SHOW = 1 
                        AND IS_USER_CONFIG = 1 
                    ORDER BY ORDER_NUMBER ASC", 
                    array($processId, $parentId)
                );*/
            }
            
            $cache->set('bpDtlUserFields_'.$processId.'_'.$parentId, $bpDtl, Mdwebservice::$expressionCacheTime);
        }
        
        $array['data'] = $bpDtl;
        $array['userConfig'] = self::bpDetailColumnsUserModel($processId, $groupPath);
        
        return $array;
    }
    
    public function bpDetailColumnsUserModel($processId, $groupPath) {
        
        $array = array();
        $userId = Ue::sessionUserId();
        $groupPath = strtolower($groupPath);
        
        $idPh1 = $this->db->Param(0);
        $idPh2 = $this->db->Param(1);
        $idPh3 = $this->db->Param(2);
            
        $data = $this->db->GetAll("
            SELECT 
                LOWER(PARAM_PATH) AS PARAM_PATH, 
                LABEL_NAME, 
                IS_SHOW 
            FROM CUSTOMER_BP_DETAIL_CONFIG 
            WHERE PROCESS_META_DATA_ID = $idPh1 
                AND USER_ID = $idPh2 
                AND LOWER(GROUP_PATH) = $idPh3 
            ORDER BY ORDER_NUM ASC", 
            array($processId, $userId, $groupPath)
        ); 
        
        if ($data) {
            foreach ($data as $row) {
                $array[$row['PARAM_PATH']] = $row['IS_SHOW'];
            }
        }
        
        return $array;
    }
    
    public function detailUserOptionSaveModel() {
        
        if (Input::postCheck('userConfigHidden')) {
            
            $userConfigHidden = $_POST['userConfigHidden'];
            $userId = Ue::sessionUserId();
            $processId = Input::numeric('metaDataId');
            $groupId = Input::post('groupId');
            $groupPath = Input::post('groupPath');
            $userConfigs = isset($_POST['userConfig']) ? $_POST['userConfig'] : array();
            
            $this->db->Execute("
                DELETE 
                FROM CUSTOMER_BP_DETAIL_CONFIG 
                WHERE PROCESS_META_DATA_ID = ".$this->db->Param(0)." 
                    AND USER_ID = ".$this->db->Param(1)." 
                    AND LOWER(GROUP_PATH) = ".$this->db->Param(2), 
                array($processId, $userId, strtolower($groupPath))
            );
            
            $showFields = $hideFields = array();
            $i = 1;
            
            foreach ($userConfigHidden as $k => $v) {
                
                $isShow = 0;
                if (isset($userConfigs[$k])) {
                    $showFields[] = $k;
                    $isShow = 1;
                } else {
                    $hideFields[] = $k;
                }
                
                $data = array(
                    'ID' => getUID(), 
                    'USER_ID' => $userId, 
                    'PROCESS_META_DATA_ID' => $processId, 
                    'PARAM_PATH' => $k, 
                    'ORDER_NUM' => $i, 
                    'IS_SHOW' => $isShow, 
                    'GROUP_PATH' => $groupPath
                );
                $this->db->AutoExecute('CUSTOMER_BP_DETAIL_CONFIG', $data);
                $i++;
            }
            
            $userBpDtl = array('showFields' => implode(',', $showFields), 'hideFields' => implode(',', $hideFields));
            
            $tmp_dir = Mdcommon::getCacheDirectory();
            $userFiles = glob($tmp_dir."/*/us/userBpDtl_".$groupId."_".$userId.".txt");
            
            foreach ($userFiles as $userFile) {
                @unlink($userFile);
            }
        
            loadPhpFastCache();
            $cache = phpFastCache();
            $cache->set('userBpDtl_'.$groupId.'_'.$userId, $userBpDtl, Mdwebservice::$expressionCacheTime);
            
            return $userBpDtl;
            
        } else {
            return null;
        }
    }
    
    public function getDetailUserConfigModel($processId, $groupId, $groupPath) {
        
        $userId = Ue::sessionUserId();
        $cache = phpFastCache();
        $userBpDtl = $cache->get('userBpDtl_'.$groupId.'_'.$userId);

        if ($userBpDtl == null) {
            
            $groupPath = Str::lower($groupPath);
            $processIdPh = $this->db->Param(0);
            $userIdPh = $this->db->Param(1);
            $groupPathPh = $this->db->Param(2);
            
            $data = $this->db->GetAll("
                SELECT 
                    PARAM_PATH, 
                    IS_SHOW 
                FROM CUSTOMER_BP_DETAIL_CONFIG 
                WHERE PROCESS_META_DATA_ID = $processIdPh 
                    AND USER_ID = $userIdPh 
                    AND LOWER(GROUP_PATH) = $groupPathPh 
                ORDER BY ORDER_NUM ASC", array($processId, $userId, $groupPath));

            if (!$data) {
                $data = $this->db->GetAll("
                    SELECT 
                        PARAM_REAL_PATH AS PARAM_PATH, 
                        1 AS IS_SHOW 
                    FROM META_PROCESS_PARAM_ATTR_LINK 
                    WHERE PROCESS_META_DATA_ID = $processIdPh 
                        AND IS_INPUT = 1 
                        AND PARENT_ID = $userIdPh 
                        AND IS_USER_CONFIG = 1   
                        AND IS_SHOW = 1 
                    ORDER BY ORDER_NUMBER ASC", array($processId, $groupId));
            }
            
            $showFields = $hideFields = array();
                
            foreach ($data as $row) {
                if ($row['IS_SHOW'] == '1') {
                    $showFields[] = $row['PARAM_PATH'];
                } else {
                    $hideFields[] = $row['PARAM_PATH'];
                }
            }

            $userBpDtl = array('showFields' => implode(',', $showFields), 'hideFields' => implode(',', $hideFields));
        
            $cache->set('userBpDtl_'.$groupId.'_'.$userId, $userBpDtl, Mdwebservice::$expressionCacheTime);
        }
        
        return $userBpDtl;
    }
    
    public function getTagDataModel($ids = '') {
        return array();
    }
    
    public function getDimensionIdByTemplateIdModel($templateId) {
        $dimensionId = $this->db->GetOne("
            SELECT 
                DIMENSION_ID 
            FROM KPI_TEMPLATE_DIMENSION 
            WHERE TEMPLATE_ID = ".$this->db->Param(0)." 
                AND IS_MAIN = ".$this->db->Param(1), 
            array($templateId, 1)
        );
        
        return $dimensionId;
    }
    
    public function bpGetProcessParamXypModel($postData = array(), $citizenData = array()) {
        (Array) $response = array('message' => Lang::line('msg_error'), 'status' => 'error');
        
        try {
            $processCode = $postData['processCode'];
            $getProcess = self::getProcessConfigByCode($processCode);
            $signature = self::getSignatureKey();

            $sessionUserId = Ue::sessionUserId();

            $operatorData = $this->db->GetRow("SELECT 
                                                    t1.FILE_PATH, 
                                                    lower(t3.STATE_REG_NUMBER) AS STATE_REG_NUMBER, 
                                                    t3.LAST_NAME, 
                                                    t3.FIRST_NAME
                                                FROM UM_USER t0
                                                    INNER JOIN um_user_finger t1 ON t0.USER_ID = t1.USER_ID
                                                    INNER JOIN um_system_user t2 ON t0.SYSTEM_USER_ID = t2.USER_ID
                                                    INNER JOIN base_person t3 ON t2.PERSON_ID = t3.PERSON_ID
                                                WHERE t2.USER_ID = $sessionUserId");
            if (!isset($operatorData['STATE_REG_NUMBER']) && !isset($operatorData['FILE_PATH'])) {
                echo json_encode(array('message' => 'Үйлчилгээг үзүүлэгч ажилтны <strong>РЕГИСТРИЙН ДУГААР</strong><br> эсвэл <br><strong>АЖИЛТНЫ ХУРУУНЫ ХЭЭНИЙ ЗУРАГ</strong> олдсонгүй', 'status' => 'error'));
                die;
            }
            if (!file_exists($operatorData['FILE_PATH'])) {
                echo json_encode(array('message' => 'Үйлчилгээг үзүүлэгч ажилтны <strong>ХЭЭГЭЭ БҮРТГҮҮЛЭЭГҮЙ<strong> байна', 'status' => 'error'));
                die;
            }
            $param = array(
                "auth" => array(
                    "citizen" => array(
                        "regnum" => $citizenData['stateRegNumber'],     // Иргэний регистрийн дугаар
                        "fingerprint" => file_get_contents($citizenData['filePath']) // Иргэний хурууны хээний зураг. 310x310 харьцаатай PNG өртгөлтэй
                    ),
                    "operator" => array(
                        "regnum" => $operatorData['STATE_REG_NUMBER'],     // Үйлчилгээг үзүүлэгч ажилтны регистрийн дугаар
                        "fingerprint" => file_get_contents($operatorData['FILE_PATH']) // Үйлчилгээг үзүүлэгч ажилтны хурууны хээний зураг. 310x310 харьцаатай PNG өртгөлтэй
                    ),
                ),
                "regnum" => $citizenData['stateRegNumber'],
                "civilId" => ""                 // Иргэний регистрийн дугаар
            );

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

            $result = $this->ws->callSoapClient($getProcess['WS_URL'], $getProcess['CLASS_NAME'], array('request' => $param), $soapOption);
            (Array) $resultConvert = Arr::objectToArray($result);
            
            if (isset($resultConvert['return']['resultCode']) && $resultConvert['return']['resultCode'] != '0') {
                $response = array('message' => $resultConvert['return']['resultMessage'], 'status' => 'error');
            } else {
                unlink($citizenData['filePath']);
                $response = array('message' => $resultConvert['return']['resultMessage'], 'status' => 'success', 'data' => Arr::changeKeyLower($resultConvert['return']), 'dataReturn' => $resultConvert);
            }

        } catch (Exception $ex) {
            $response = array('message' => $ex->message, 'status' => 'error');
        }
        
        return $response;
    }
    
    public function getSignatureKey() {

        $pkey = file_get_contents(UPLOADPATH . 'xyp/notary.key');
        $accessToken = '36eb096dcffccb3777c5a4136f0628e7';

        $timestamp = time();

        openssl_sign($accessToken . "." . $timestamp, $signature, $pkey, OPENSSL_ALGO_SHA256);

        return array(
            'accessToken' => $accessToken,
            'timeStamp' => $timestamp,
            'signature' => base64_encode($signature),
        );
    }  
    
    public function getExistsKpiRelationModel($srcTemplateId, $srcObjectId, $trgTemplateId, $trgObjectId, $indicatorId) {
                                    
        $id = $this->db->GetOne("
            SELECT 
                ID  
            FROM EA_RELATION 
            WHERE SRC_TEMPLATE_ID = ".$this->db->Param(0)." 
                AND SRC_OBJECT_ID = ".$this->db->Param(1)." 
                AND TRG_TEMPLATE_ID = ".$this->db->Param(2)." 
                AND TRG_OBJECT_ID = ".$this->db->Param(3)." 
                AND ".$this->db->IfNull('SCENARIO_ID', '0')." = ".$this->db->Param(4)." 
                AND INDICATOR_ID = ".$this->db->Param(5), 
            array($srcTemplateId, $srcObjectId, $trgTemplateId, $trgObjectId, Ue::sessionScenarioId(), $indicatorId)
        );
        
        return $id;
    }
    
    public function getExistsEaRelationModel($srcTemplateId, $srcObjectId, $indicatorId) {
                                    
        $id = $this->db->GetOne("
            SELECT 
                ID  
            FROM EA_RELATION 
            WHERE SRC_TEMPLATE_ID = ".$this->db->Param(0)." 
                AND SRC_OBJECT_ID = ".$this->db->Param(1)." 
                AND ".$this->db->IfNull('SCENARIO_ID', '0')." = ".$this->db->Param(2)." 
                AND INDICATOR_ID = ".$this->db->Param(3), 
            array($srcTemplateId, $srcObjectId, Ue::sessionScenarioId(), $indicatorId)
        );
        
        return $id;
    }
    
    public function getExistsKpiDmMartModel($recordId, $templateId, $defaultTplId = null) {
        
        if ($defaultTplId) {
            
            $id = $this->db->GetOne("
                SELECT 
                    ID  
                FROM KPI_DM_MART  
                WHERE RECORD_ID = ".$this->db->Param(0)." 
                    AND TEMPLATE_ID = ".$this->db->Param(1)." 
                    AND DEFAULT_TEMPLATE_ID = ".$this->db->Param(2)." 
                    AND ".$this->db->IfNull('SCENARIO_ID', '0')." = ".$this->db->Param(3)." 
                    AND TRG_TEMPLATE_ID IS NULL", 
                array($recordId, $templateId, $defaultTplId, Ue::sessionScenarioId()) 
            );
            
        } else {
            
            $id = $this->db->GetOne("
                SELECT 
                    ID  
                FROM KPI_DM_MART  
                WHERE RECORD_ID = ".$this->db->Param(0)." 
                    AND TEMPLATE_ID = ".$this->db->Param(1)." 
                    AND ".$this->db->IfNull('SCENARIO_ID', '0')." = ".$this->db->Param(2)." 
                    AND DEFAULT_TEMPLATE_ID IS NULL 
                    AND TRG_TEMPLATE_ID IS NULL", 
                array($recordId, $templateId, Ue::sessionScenarioId()) 
            );
        }
        
        return $id;
    }
    
    public function getAllProcessInputParamAutoNumberAddon($processMetaDataId) {

        $rows = $this->db->GetAll("
            SELECT
                META_DATA_ID,
                PARAM_NAME,
                CODE_FORMAT,
                SEQUENCE_FORMAT,
                IS_UNIQUE
            FROM META_DATA_SEQUENCE_CONFIG 
            WHERE META_DATA_ID = ".$this->db->Param(0),
            array($processMetaDataId)
        );

        if ($rows) {
            return Arr::groupByArrayLower($rows, 'PARAM_NAME');
        }
            
        return array();
    }        
    
    public function getMetaDataModel($metaDataId, $getFolder = false) {
        
        $metaDataIdPh = $this->db->Param(0);
        $bindVars = array($this->db->addQ($metaDataId));
        
        $row = $this->db->GetRow("
            SELECT 
                META_DATA_ID, 
                META_DATA_CODE, 
                META_DATA_NAME, 
                META_TYPE_ID,
                DESCRIPTION
            FROM META_DATA  
            WHERE META_DATA_ID = $metaDataIdPh", $bindVars);

        if ($getFolder && $row) {
            $folder = $this->db->GetRow("SELECT FOLDER_ID FROM META_DATA_FOLDER_MAP WHERE META_DATA_ID = $metaDataIdPh", $bindVars);
            if ($folder) {
                $row = array_merge($row, $folder);
            }
        }

        return $row;
    }    
    
    public function getAutoNumberServiceModel($metaId, $paramPath) {
        
        $getMetaCode = $this->getMetaDataModel($metaId);
        
        $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, 'GET_SEQUENCED_FIELD_VALUES', array('commandName' => issetParam($getMetaCode['META_DATA_CODE']), 'paramName' => $paramPath));
        
        if ($result['status'] == 'success') {
            return $result['result'][$paramPath];
        } 
        return '';
    }        
    
    public function getDVProcessHeaderConfigModel($metaDataId) {
            
        $cache = phpFastCache();

        $row = $cache->get('bpConfig_' . $metaDataId);

        if ($row == null) {
            
            $metaDataIdPh = $this->db->Param(0);
            $bindVars = array($this->db->addQ($metaDataId));
        
            $row = $this->db->GetRow("
                SELECT 
                    MD.META_DATA_ID, 
                    MD.META_DATA_CODE, 
                    " . $this->db->IfNull('PL.LIST_NAME', 'MD.META_DATA_NAME') . " AS META_DATA_NAME,  
                    PL.LABEL_WIDTH, 
                    PL.COLUMN_COUNT, 
                    2 AS TAB_COLUMN_COUNT, 
                    null AS REF_META_GROUP_ID, 
                    PL.WINDOW_TYPE, 
                    PL.WINDOW_SIZE, 
                    PL.WINDOW_WIDTH, 
                    PL.WINDOW_HEIGHT,
                    null AS IS_ADDON_PHOTO, 
                    null AS IS_ADDON_FILE, 
                    null AS IS_ADDON_COMMENT, 
                    null AS IS_ADDON_LOG, 
                    null AS IS_ADDON_RELATION, 
                    null AS IS_ADDON_WFM_LOG, 
                    null AS IS_ADDON_WFM_LOG_TYPE, 
                    null AS IS_BPMN_TOOL, 
                    null AS SKIN, 
                    null AS THEME
                FROM META_GROUP_LINK PL 
                    INNER JOIN META_DATA MD ON MD.META_DATA_ID = PL.META_DATA_ID   
                WHERE PL.META_DATA_ID = $metaDataIdPh", $bindVars);    

            $cache->set('bpConfig_' . $metaDataId, $row, Mdwebservice::$expressionCacheTime);
        }
        
        return $row;
    }
    
    public function getDVProcessInputParams($processMetaDataId) {

        $cache = phpFastCache();
        $bpParams = $cache->get('bpHeader_' . $processMetaDataId);

        if ($bpParams == null) {

            $data = self::getDVProcessParamsData($processMetaDataId, null, ' AND PAL.PARENT_ID IS NULL');

            if ($data) {
                
                $array = $arrayDtl = array();

                $array[0]['name'] = 'general_info';
                $array[0]['type'] = 'header';
                $array[0]['description'] = 'general_info';
                $array[0]['code'] = 'staticCode';

                foreach ($data as $k => $row) {
                    if ($row['META_TYPE_CODE'] != 'group') {
                        $array[0]['data'][] = $row;
                    } else {
                        array_push($arrayDtl, $row);
                    }
                }

                if (count($arrayDtl) > 0) {
                    
                    $n = 1;

                    foreach ($arrayDtl as $child) {

                        $array[$n]['id'] = $child['ID'];
                        $array[$n]['name'] = $child['META_DATA_NAME'];
                        $array[$n]['code'] = $child['META_DATA_CODE'];
                        $array[$n]['recordtype'] = $child['RECORD_TYPE'];
                        $array[$n]['type'] = 'detail';
                        $array[$n]['tabName'] = $child['TAB_NAME'];
                        $array[$n]['isShow'] = $child['IS_SHOW'];
                        $array[$n]['isSave'] = $child['IS_SAVE'];
                        $array[$n]['description'] = $child['DESCRIPTION'];
                        $array[$n]['sidebarName'] = $child['SIDEBAR_NAME'];
                        $array[$n]['dataType'] = $child['META_TYPE_CODE'];
                        $array[$n]['attrLinkId'] = $child['ID'];
                        $array[$n]['isRequired'] = $child['IS_REQUIRED'];
                        $array[$n]['isFirstRow'] = $child['IS_FIRST_ROW'];
                        $array[$n]['isShowAdd'] = $child['IS_SHOW_ADD'];
                        $array[$n]['isShowDelete'] = $child['IS_SHOW_DELETE'];
                        $array[$n]['isShowMultiple'] = $child['IS_SHOW_MULTIPLE'];
                        $array[$n]['isShowMultipleMap'] = $child['IS_MULTI_ADD_ROW'];
                        $array[$n]['isShowMultipleKeyMap'] = $child['IS_MULTI_ADD_ROW_KEY'];
                        $array[$n]['groupLookupMeta'] = $child['LOOKUP_META_DATA_ID'];
                        $array[$n]['groupKeyLookupMeta'] = $child['LOOKUP_KEY_META_DATA_ID'];
                        $array[$n]['groupingName'] = $child['GROUPING_NAME'];
                        $array[$n]['paramPath'] = $child['PARAM_REAL_PATH'];
                        $array[$n]['columnCount'] = $child['COLUMN_COUNT'];
                        $array[$n]['columnWidth'] = $child['COLUMN_WIDTH'];
                        $array[$n]['isRefresh'] = $child['IS_REFRESH'];
                        $array[$n]['groupConfigParamPath'] = $child['GROUP_CONFIG_PARAM_PATH_GROUP'];
                        $array[$n]['groupConfigLookupPath'] = $child['GROUP_CONFIG_FIELD_PATH_GROUP'];
                        $array[$n]['isExcelExport'] = $child['IS_EXCEL_EXPORT'];
                        $array[$n]['isExcelImport'] = $child['IS_EXCEL_IMPORT'];
                        $array[$n]['detailModifyMode'] = $child['DETAIL_MODIFY_MODE'];
                        $array[$n]['dtlTheme'] = $child['DTL_THEME'];
                        $array[$n]['themePosition'] = $child['THEME_POSITION_NO'];
                        $array[$n]['isPivotColumns'] = '';
                        $array[$n]['pagingConfig'] = null;
                        
                        $childData = self::DVProcessOnlyShowParamsDataModel($processMetaDataId, $child['ID']);
                        
                        if ($childData) {
                            $array[$n]['data'] = $childData;
                        }

                        $n++;
                    }
                }

                $bpParams = array(
                    'renderData' => $array
                );
                
                $cache->set('bpHeader_' . $processMetaDataId, $bpParams, Mdwebservice::$expressionCacheTime);

                return $bpParams;

            } else {
                return null;
            }
        }

        return $bpParams;
    }
    
    public function getDVProcessParamsData($bpMetaDataId, $parentId = '', $where = '') {
        
        $bpMetaDataIdPh = $this->db->Param(0);
        $bindVars = array($this->db->addQ($bpMetaDataId));
        
        $data = $this->db->GetAll("
            SELECT 
                null AS GROUP_PARAM_CONFIG_TOTAL, 
                null AS GROUP_CONFIG_PARAM_PATH,
                null AS GROUP_CONFIG_LOOKUP_PATH, 
                null AS GROUP_CONFIG_FIELD_PATH, 
                null AS GROUP_CONFIG_GROUP_PATH, 
                null AS GROUP_CONFIG_PARAM_PATH_GROUP, 
                null AS GROUP_CONFIG_FIELD_PATH_GROUP, 
                null AS IS_MULTI_ADD_ROW, 
                null AS IS_MULTI_ADD_ROW_KEY, 
                null AS IS_AUTO_NUMBER, 
                null AS DESCRIPTION,
                null AS ATTRIBUTE_ID_COLUMN, 
                null AS ATTRIBUTE_CODE_COLUMN, 
                null AS ATTRIBUTE_NAME_COLUMN, 
                PAL.LOOKUP_META_DATA_ID, 
                PAL.LOOKUP_KEY_META_DATA_ID, 
                PAL.ID,
                PAL.PARENT_ID,
                PAL.PARAM_NAME AS META_DATA_CODE, 
                PAL.LABEL_NAME AS META_DATA_NAME, 
                LOWER(PAL.PARAM_NAME) AS LOWER_PARAM_NAME, 
                PAL.IS_SHOW, 
                PAL.IS_REQUIRED, 
                null AS IS_FIRST_ROW, 
                PAL.DEFAULT_VALUE,  
                PAL.RECORD_TYPE, 
                PAL.LOOKUP_TYPE, 
                PAL.CHOOSE_TYPE, 
                PAL.MIN_VALUE, 
                PAL.MAX_VALUE, 
                PAL.DISPLAY_FIELD, 
                PAL.VALUE_FIELD, 
                PAL.FIELD_PATH AS PARAM_REAL_PATH, 
                LOWER(PAL.FIELD_PATH) AS LOWER_PARAM_REAL_PATH, 
                REPLACE(PAL.FIELD_PATH, '.', '') AS NODOT_PARAM_REAL_PATH, 
                LOWER(PAL.DATA_TYPE) AS META_TYPE_CODE, 
                LOWER(PAL.COLUMN_AGGREGATE) AS COLUMN_AGGREGATE, 
                ".$this->db->IfNull('PAL.IS_BUTTON', '1')." AS IS_BUTTON, 
                ".$this->db->IfNull('PAL.PLACEHOLDER_NAME', 'PAL.LABEL_NAME')." AS PLACEHOLDER_NAME, 
                PAL.COLUMN_WIDTH, 
                PAL.SEPARATOR_TYPE,
                1 AS COLUMN_COUNT,
                PAL.IS_BUTTON,
                1 AS IS_SHOW_ADD,
                1 AS IS_SHOW_DELETE,
                0 AS IS_SHOW_MULTIPLE,
                0 AS IS_REFRESH, 
                PAL.FRACTION_RANGE, 
                PAL.TAB_NAME, 
                PAL.IS_SAVE, 
                PAL.FEATURE_NUM, 
                PAL.SIDEBAR_NAME, 
                null AS GROUPING_NAME, 
                PAL.FILE_EXTENSION, 
                MFP.PATTERN_TEXT, 
                MFP.PATTERN_NAME, 
                MFP.GLOBE_MESSAGE, 
                MFP.IS_MASK, 
                null AS THEME_POSITION_NO, 
                null AS DTL_THEME, 
                null AS PAGING_CONFIG, 
                null AS IS_USER_CONFIG, 
                null AS IS_EXCEL_EXPORT, 
                null AS IS_EXCEL_IMPORT, 
                null AS DETAIL_MODIFY_MODE, 
                null AS ICON_NAME, 
                null AS MORE_META_DATA_ID 
            FROM META_GROUP_CONFIG PAL 
                LEFT JOIN META_FIELD_PATTERN MFP ON MFP.PATTERN_ID = PAL.PATTERN_ID     
            WHERE PAL.MAIN_META_DATA_ID = $bpMetaDataIdPh  
                ".($parentId ? "AND PAL.PARENT_ID = $parentId" : '')." 
                $where     
            ORDER BY PAL.IS_SHOW DESC, PAL.DISPLAY_ORDER ASC", $bindVars);
        
        return $data;
    }
    
    public function DVProcessOnlyShowParamsDataModel($processMetaDataId, $parentId) {
        if (empty($parentId)) {
            return false;
        }

        $cache = phpFastCache();

        $data = $cache->get('bpDetail_'.$processMetaDataId.'_'.$parentId);

        if ($data == null) {
            $data = self::getDVProcessParamsData($processMetaDataId, $parentId);
            $cache->set('bpDetail_'.$processMetaDataId.'_'.$parentId, $data, Mdwebservice::$expressionCacheTime);
        }

        return $data;
    }
    
    public function getProcessTemplateModel($bpId) {
        
        $param = array('id' => Input::numeric('dataTemplateId'));
        
        $result = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'getProcessTemplate', $param);

        if ($result['status'] == 'success') {
            
            $resultData = $result['result'];
            $defaultValues = self::getOnlyDefaultValueInputsModel($bpId);
            
            if ($defaultValues) {
                foreach ($defaultValues as $row) {
                    
                    $lowerPath = strtolower($row['PARAM_REAL_PATH']);
                    
                    if (strpos($lowerPath, '.') === false && isset($resultData[$lowerPath])) {
                        $resultData[$lowerPath] = Mdmetadata::setDefaultValue($row['DEFAULT_VALUE']);
                    }
                }
            }
            
            return $resultData;
        } 

        return null;
    }
    
    public function saveModeBpFileFolderModel() {
        
        $code = Str::randName();
        $data = array(
            'ID' => getUID(),
            'CODE' => $code,
            'NAME' => $code,
            'CREATED_DATE' => Date::currentDate(),
            'CREATED_USER_ID' => Ue::sessionUserKeyId(),
            'CHECK_RW' => '1'
        );
        
        if (Input::post('parentid')) {
            $data['PARENT_ID'] = Input::post('parentid');
        }
        
        $result = $this->db->AutoExecute('ECM_DIRECTORY', $data);
        
        if ($result) {
            if (Input::postCheck('srcId')) {
                $dataDirMap = array(
                    'ID'               => getUID(),
                    'REF_STRUCTURE_ID' => Input::post('refStructureId'),
                    'RECORD_ID'        => Input::post('srcId'),
                    'DIRECTORY_ID'     => $data['ID'],
                    'CREATED_DATE'     => Date::currentDate(),
                    'CREATED_USER_ID'  => Ue::sessionUserKeyId(),
                    'ORDER_NUM'        => 0
                );

                $this->db->AutoExecute('ECM_DIRECTORY_MAP', $dataDirMap);
                $this->db->AutoExecute('ECM_DIRECTORY', array('CHECK_RW' => '0'), 'UPDATE', "ID = '". $data['ID'] ."'");
            }
            
            return $data;
        } else {
            return array('ID' => '');
        }
    }
    
    public function updateModeBpFileFolderModel() {
        $data = array('NAME' => Input::post('folderName'));
        $result = $this->db->AutoExecute('ECM_DIRECTORY', array('NAME' => Input::post('folderName')), 'UPDATE', "ID = '". Input::post('folderId') ."'");
        return array('result' => $result);
    }
    
    public function getChildBpFileFolderModel() {
        
        $folderData = $itemList = array();
        
        try {
            
            $id = Input::numeric('id');
            $folderData = $this->db->GetAll("SELECT ID, NAME FROM ECM_DIRECTORY WHERE PARENT_ID = ".$this->db->Param(0), array($id));

            if ($sourceId = Input::numeric('sourceId')) {
                
                $refStructureId = Input::numeric('refStructureId');
                
                $itemList = $this->db->GetAll("
                    SELECT 
                        CO.CONTENT_ID AS ATTACH_ID, 
                        CO.FILE_NAME AS ATTACH_NAME, 
                        CO.PHYSICAL_PATH AS ATTACH, 
                        CO.THUMB_PHYSICAL_PATH AS ATTACH_THUMB, 
                        CO.FILE_EXTENSION, 
                        CO.FILE_SIZE,
                        CO.IS_EMAIL,
                        '' AS SYSTEM_URL, 
                        '' AS TRG_TAG_ID,
                        '' AS TRG_TAG_IDC,
                        MP.IS_MAIN,
                        T0.FOLDER_NAME,
                        T0.FOLDER_ID
                    FROM ECM_CONTENT CO 
                        INNER JOIN ECM_CONTENT_MAP MP ON MP.CONTENT_ID = CO.CONTENT_ID 
                        INNER JOIN (
                            SELECT 
                                DISTINCT
                                T2.NAME AS FOLDER_NAME,
                                T2.ID AS FOLDER_ID,
                                T2.PARENT_ID,
                                T1.CONTENT_ID
                            FROM ECM_DIRECTORY_MAP T0 
                                INNER JOIN ECM_CONTENT_DIRECTORY T1 ON T0.DIRECTORY_ID = T1.DIRECTORY_ID
                                INNER JOIN ECM_DIRECTORY T2 ON T1.DIRECTORY_ID = T2.ID
                            WHERE T0.RECORD_ID = $sourceId 
                                AND T0.REF_STRUCTURE_ID = $refStructureId 
                        ) T0 ON CO.CONTENT_ID = T0.CONTENT_ID
                    WHERE MP.REF_STRUCTURE_ID = $refStructureId 
                        AND MP.RECORD_ID = $sourceId  
                        AND t0.FOLDER_ID = $id  
                        AND IS_PHOTO = 1");
            }
        
        } catch (Exception $ex) {}
        
        return array('folderData' => $folderData, 'item' => $itemList);
    }
    
    public function getSectionsConfigByProcessIdModel($bpId) {
        
        $cache = phpFastCache();

        $arr = $cache->get('bpLayoutSections_' . $bpId);

        if ($arr == null) {
            
            $arr = array();
            
            $data = $this->db->GetAll("
                 SELECT 
                    LS.ID, 
                    LS.CODE, 
                    LS.TITLE, 
                    LS.COLUMN_COUNT, 
                    LS.BORDER_STYLE, 
                    LS.BACKGROUND_STYLE, 
                    LS.LABEL_POSITION, 
                    LS.WIDTH, 
                    LS.OTHER_ATTR, 
                    MW.CODE AS WIDGET_CODE 
                FROM META_BP_LAYOUT_SECTION LS 
                    INNER JOIN META_BP_LAYOUT_HDR BL ON BL.ID = LS.HEADER_ID 
                    INNER JOIN CUSTOMER_BP_LAYOUT CBL ON CBL.LAYOUT_ID = BL.ID 
                    LEFT JOIN META_WIDGET MW ON MW.ID = LS.WIDGET_ID 
                WHERE BL.META_DATA_ID = ".$this->db->Param(0)." 
                    AND CBL.IS_DEFAULT = 1 
                ORDER BY LS.CODE ASC", array($bpId));
            
            foreach ($data as $row) {
                
                $arr[$row['CODE']] = array(
                    'id'              => $row['ID'], 
                    'title'           => $row['TITLE'], 
                    'columnCount'     => $row['COLUMN_COUNT'], 
                    'borderStyle'     => $row['BORDER_STYLE'], 
                    'backgroundStyle' => $row['BACKGROUND_STYLE'], 
                    'labelPosition'   => $row['LABEL_POSITION'], 
                    'widgetCode'      => $row['WIDGET_CODE'], 
                    'width'           => Str::remove_whitespace($row['WIDTH'])
                );
                
                if ($row['OTHER_ATTR']) {
                    $arr[$row['CODE']]['otherAttr'] = json_decode($row['OTHER_ATTR'], true);
                }
            }
        
            $cache->set('bpLayoutSections_' . $bpId, $arr, Mdwebservice::$expressionCacheTime);
        }
        
        return $arr;
    }
    
    public function getOnlyDefaultValueInputsModel($bpId) {
        
        $cache = phpFastCache();
        $data = $cache->get('bpOnlyDfltValFlds_' . $bpId);

        if ($data == null) {
            
            $data = $this->db->GetAll("
                 SELECT 
                    PARAM_REAL_PATH, 
                    DEFAULT_VALUE 
                FROM META_PROCESS_PARAM_ATTR_LINK 
                WHERE PROCESS_META_DATA_ID = ".$this->db->Param(0)." 
                    AND IS_INPUT = 1 
                    AND DEFAULT_VALUE IS NOT NULL 
                ORDER BY ORDER_NUMBER ASC", array($bpId));
        
            $cache->set('bpOnlyDfltValFlds_' . $bpId, $data, Mdwebservice::$expressionCacheTime);
        }
        
        return $data;
    }
    
    public function getBpTemplateSaveHtml($selectedRowData, $uniqId, $methodId) {
        $result = '';
        return $result;
    }
    
    public function getEcmFindRowIdsModel($sourceId, $pfEcmFindChildTable, $pfEcmFindChildColName, $pfEcmFindChildSelectName) {
        
        try {
            
            $result = array();
            $data = $this->db->GetAll("SELECT $pfEcmFindChildSelectName AS ID FROM $pfEcmFindChildTable WHERE $pfEcmFindChildColName = ".$this->db->Param(0), array($sourceId));
            
            if ($data) {
                foreach ($data as $row) {
                    $result[] = $row['ID'];
                }
            }
            
        } catch (Exception $ex) {
            $result = array();
        }
        
        return $result;
    }
    
    public function getFieldEcmContentModel($refStructureId, $hdrId, $paramRealPath) {
        
        $row = $this->db->GetRow("
            SELECT 
                CO.CONTENT_ID, 
                CO.FILE_NAME, 
                CO.PHYSICAL_PATH, 
                CO.FILE_EXTENSION, 
                MP.ID AS MAP_ID 
            FROM ECM_CONTENT CO 
                INNER JOIN ECM_CONTENT_MAP MP ON MP.CONTENT_ID = CO.CONTENT_ID 
            WHERE MP.REF_STRUCTURE_ID = ".$this->db->Param(0)." 
                AND MP.RECORD_ID = ".$this->db->Param(1)." 
                AND MP.TAG_CODE = ".$this->db->Param(2)."     
                AND CO.TYPE_ID = 4001", 
            array($refStructureId, $hdrId, $paramRealPath)     
        );
        
        return $row;
    }
    
    public function afterRunAdditionalProcessModel($processId, $processCode) {
        
        $mvProcessIds = array(
            '16413659216321', '16413780044111', '16424366404971', 
            '16424366405551', '16424911282141', '16705727269419', 
            '16425125580661', '16660589496259', '17091132313599', 
            '17091133076179'
        );
        
        if (in_array($processId, $mvProcessIds) && isset(Mdwebservice::$responseData['jsonid'])) {
            
            try {
            
                WebService::$addonHeaderParam['windowSessionId'] = null;
                $param = array('filterId' => Mdwebservice::$responseData['jsonid']);

                $result = $this->ws->runSerializeResponse(Mdwebservice::$gfServiceAddress, 'getMetaVerseFullInfo_004', $param);

                if ($result['status'] == 'success' && isset($result['result'])) {
                    
                    $json = json_encode($result['result'], JSON_UNESCAPED_UNICODE);
                    $this->db->UpdateClob('KPI_INDICATOR', 'JSON_CONFIG', $json, 'ID = '.$param['filterId']);
                    
                    return true;
                }

            } catch (Exception $ex) { }
        }
        
        return false;
    }
    
    public function fieldCriteria($jsonConfig) {
        if ($jsonConfig && array_key_exists('fieildcriteria', $jsonConfig)) {
            
            $fieldCriteria = $jsonConfig['fieildcriteria'];
            
            $fieldCriteria = str_replace('sessioncompanysizeid', Mdmetadata::getDefaultValue('sessioncompanysizeid'), $fieldCriteria);
            $fieldCriteria = str_replace('sessionuserkeyid', Mdmetadata::getDefaultValue('sessionuserkeyid'), $fieldCriteria);
            $fieldCriteria = str_replace('sessionuserkeydepartmentid', Mdmetadata::getDefaultValue('sessionuserkeydepartmentid'), $fieldCriteria);
            
            return eval('return ' . $fieldCriteria . ';');            
        } else {
            return true;
        }
    }
    
}