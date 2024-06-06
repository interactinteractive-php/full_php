<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdtemplate_model extends Model {
    
    private static $gfServiceAddress = GF_SERVICE_ADDRESS;
    
    public function __construct() {
        parent::__construct();
    }

    public function getReportTemplate($id, $isTemplateMetaId = false) {
        
        $idPh = $this->db->Param(0);
        $bindVars = array($this->db->addQ($id));
        
        if (Mdtemplate::$isKpiIndicator) {
            
            $sql = "
                SELECT 
                    ID, 
                    MAIN_INDICATOR_ID AS META_DATA_ID, 
                    DATA_INDICATOR_ID AS DATA_MODEL_ID, 
                    HTML_CONTENT, 
                    HTML_FILE_PATH,
                    HTML_HEADER_CONTENT,
                    HTML_FOOTER_CONTENT      
                FROM META_REPORT_TEMPLATE_LINK 
                WHERE ".($isTemplateMetaId ? "MAIN_INDICATOR_ID = $idPh" : "ID = $idPh");
            
        } else {
            $sql = "
                SELECT 
                    ID, 
                    META_DATA_ID, 
                    DATA_MODEL_ID, 
                    HTML_CONTENT, 
                    HTML_FILE_PATH,
                    HTML_HEADER_CONTENT,
                    HTML_FOOTER_CONTENT      
                FROM META_REPORT_TEMPLATE_LINK 
                WHERE ".($isTemplateMetaId ? "META_DATA_ID = $idPh" : "ID = $idPh");
        }
        
        return $this->db->GetRow($sql, $bindVars);
    }

    public function getReportTemplateByDataModel($metaDataId, $isProcess) {
        
        $metaDataIdPh = $this->db->Param(0);
        $bindVars = array($this->db->addQ($metaDataId));
        
        if (DB_DRIVER == 'oci8') {
            $orderBy = "ORDER BY TO_NUMBER(REGEXP_SUBSTR(LMD.META_DATA_NAME, '^[0-9]+')) ASC, TO_NUMBER(REGEXP_SUBSTR(LMD.META_DATA_NAME, '$[0-9]+')) ASC, LMD.META_DATA_NAME ASC";
        } else {
            $orderBy = 'ORDER BY LMD.META_DATA_NAME ASC';
        }
        
        if ($isProcess == 'true') {
            
            $data = $this->db->GetAll("
                SELECT 
                    REP.ID,
                    DTL.TEMPLATE_META_DATA_ID,
                    LMD.META_DATA_CODE,
                    LMD.META_DATA_NAME,
                    DTL.CRITERIA, 
                    CT.ID AS TEMPLATE_GROUP_ID, 
                    CT.NAME AS TEMPLATE_GROUP_NAME, 
                    null AS IS_DEFAULT,
                    REP.CONFIG_STR
                FROM META_PROCESS_TEMPLATE DTL    
                    INNER JOIN META_BUSINESS_PROCESS_LINK LINK ON LINK.META_DATA_ID = DTL.PROCESS_LINK_ID
                    INNER JOIN META_REPORT_TEMPLATE_LINK REP ON REP.META_DATA_ID = DTL.TEMPLATE_META_DATA_ID
                    INNER JOIN META_DATA LMD ON LMD.META_DATA_ID = REP.META_DATA_ID 
                    LEFT JOIN CUSTOMER_TEMPLATE_MAP CTM ON CTM.META_DATA_ID = REP.META_DATA_ID 
                    LEFT JOIN CUSTOMER_TEMPLATE CT ON CT.ID = CTM.TEMPLATE_ID  
                    LEFT JOIN ( 
                        SELECT 
                            MAX(SRC_META_DATA_ID) AS SRC_META_DATA_ID 
                        FROM CUSTOMER_USE_CHILD 
                        WHERE SRC_META_DATA_ID = $metaDataIdPh 
                            AND IS_USE = 1 
                            AND INDICATOR_ID IS NULL 
                    ) CC ON CC.SRC_META_DATA_ID = LINK.META_DATA_ID 
                    LEFT JOIN CUSTOMER_USE_CHILD CUC ON CUC.SRC_META_DATA_ID = LINK.META_DATA_ID 
                        AND CUC.TRG_META_DATA_ID = DTL.TEMPLATE_META_DATA_ID 
                        AND CUC.IS_USE = 1 
                WHERE LINK.META_DATA_ID = $metaDataIdPh 
                    AND (CC.SRC_META_DATA_ID IS NULL OR CUC.ID IS NOT NULL) 
                $orderBy", $bindVars);   
            
        } else {
            
            $data = $this->db->GetAll("
                SELECT 
                    REP.ID, 
                    DTL.TEMPLATE_META_DATA_ID, 
                    LMD.META_DATA_CODE, 
                    LMD.META_DATA_NAME, 
                    DTL.CRITERIA, 
                    CT.ID AS TEMPLATE_GROUP_ID, 
                    CT.NAME AS TEMPLATE_GROUP_NAME, 
                    DTL.IS_DEFAULT, 
                    REP.CONFIG_STR 
                FROM (
                        SELECT 
                            DTL.META_GROUP_LINK_ID, 
                            DTL.TEMPLATE_META_DATA_ID, 
                            DTL.CRITERIA, 
                            DTL.IS_DEFAULT 
                        FROM META_DM_TEMPLATE_DTL DTL 
                            INNER JOIN META_GROUP_LINK LINK ON LINK.ID = DTL.META_GROUP_LINK_ID 
                            LEFT JOIN ( 
                                SELECT 
                                    MAX(SRC_META_DATA_ID) AS SRC_META_DATA_ID 
                                FROM CUSTOMER_USE_CHILD 
                                WHERE SRC_META_DATA_ID = $metaDataIdPh 
                                    AND IS_USE = 1 
                                    AND INDICATOR_ID IS NULL 
                            ) CC ON CC.SRC_META_DATA_ID = LINK.META_DATA_ID 
                            LEFT JOIN CUSTOMER_USE_CHILD CUC ON CUC.SRC_META_DATA_ID = LINK.META_DATA_ID 
                                AND CUC.TRG_META_DATA_ID = DTL.TEMPLATE_META_DATA_ID 
                                AND CUC.IS_USE = 1 
                        WHERE LINK.META_DATA_ID = $metaDataIdPh 
                            AND (CC.SRC_META_DATA_ID IS NULL OR CUC.ID IS NOT NULL) 
                        GROUP BY 
                            DTL.META_GROUP_LINK_ID, 
                            DTL.TEMPLATE_META_DATA_ID, 
                            DTL.CRITERIA, 
                            DTL.IS_DEFAULT 
                    ) DTL 
                    INNER JOIN META_GROUP_LINK LINK ON LINK.ID = DTL.META_GROUP_LINK_ID 
                    INNER JOIN META_REPORT_TEMPLATE_LINK REP ON REP.META_DATA_ID = DTL.TEMPLATE_META_DATA_ID 
                    INNER JOIN META_DATA LMD ON LMD.META_DATA_ID = REP.META_DATA_ID 
                    LEFT JOIN CUSTOMER_TEMPLATE_MAP CTM ON CTM.META_DATA_ID = REP.META_DATA_ID 
                    LEFT JOIN CUSTOMER_TEMPLATE CT ON CT.ID = CTM.TEMPLATE_ID 
                WHERE LINK.META_DATA_ID = $metaDataIdPh $orderBy", $bindVars);
            
            if (Config::getFromCache('reportTemplatePermission') == '1') {
                foreach($data as $key => $row) {
                    if (self::checkReportTemplatePermissionModel($row['TEMPLATE_META_DATA_ID'])) {
                        unset($data[$key]);
                    }
                }
            }
        }
        
        return $data;
    }

    public function getReportTemplateByIdDataModel($metaDataId, $templateId) {
        
        $metaDataIdPh = $this->db->Param(0);
        $templateIdPh = $this->db->Param(1);

        if (strpos($templateId, ',') === false) {
            $bindVars = array($this->db->addQ($metaDataId), $this->db->addQ($templateId));
        } else {
            $bindVars = array($this->db->addQ($metaDataId));
        }
        
        if (DB_DRIVER == 'oci8') {
            $orderBy = "ORDER BY TO_NUMBER(REGEXP_SUBSTR(LMD.META_DATA_NAME, '^[0-9]+')) ASC, TO_NUMBER(REGEXP_SUBSTR(LMD.META_DATA_NAME, '$[0-9]+')) ASC, LMD.META_DATA_NAME ASC";
        } else {
            $orderBy = 'ORDER BY LMD.META_DATA_NAME ASC';
        }
        
        $data = $this->db->GetAll("
            SELECT 
                REP.ID,
                DTL.TEMPLATE_META_DATA_ID,
                LMD.META_DATA_CODE,
                LMD.META_DATA_NAME,
                DTL.CRITERIA, 
                CT.ID AS TEMPLATE_GROUP_ID, 
                CT.NAME AS TEMPLATE_GROUP_NAME, 
                null AS IS_DEFAULT,
                REP.CONFIG_STR
            FROM META_PROCESS_TEMPLATE DTL    
                INNER JOIN META_BUSINESS_PROCESS_LINK LINK ON LINK.META_DATA_ID = DTL.PROCESS_LINK_ID 
                INNER JOIN META_REPORT_TEMPLATE_LINK REP ON REP.META_DATA_ID = DTL.TEMPLATE_META_DATA_ID 
                INNER JOIN META_DATA LMD ON LMD.META_DATA_ID = REP.META_DATA_ID 
                LEFT JOIN CUSTOMER_TEMPLATE_MAP CTM ON CTM.META_DATA_ID = REP.META_DATA_ID 
                LEFT JOIN CUSTOMER_TEMPLATE CT ON CT.ID = CTM.TEMPLATE_ID  
                LEFT JOIN ( 
                    SELECT 
                        MAX(SRC_META_DATA_ID) AS SRC_META_DATA_ID 
                    FROM CUSTOMER_USE_CHILD 
                    WHERE SRC_META_DATA_ID = $metaDataIdPh 
                        AND IS_USE = 1 
                        AND INDICATOR_ID IS NULL 
                ) CC ON CC.SRC_META_DATA_ID = LINK.META_DATA_ID 
                LEFT JOIN CUSTOMER_USE_CHILD CUC ON CUC.SRC_META_DATA_ID = LINK.META_DATA_ID 
                    AND CUC.TRG_META_DATA_ID = DTL.TEMPLATE_META_DATA_ID  
                    AND CUC.IS_USE = 1 
            WHERE LINK.META_DATA_ID = $metaDataIdPh 
                AND (CC.SRC_META_DATA_ID IS NULL OR CUC.ID IS NOT NULL) 
                AND ". (strpos($templateId, ',') === false ? "DTL.TEMPLATE_META_DATA_ID = $templateIdPh " : "DTL.TEMPLATE_META_DATA_ID IN ($templateId) ") ." 
            " . $orderBy, $bindVars);
        
        return $data;
    }
    
    public function getReportTemplateKpiIndicatorModel($metaDataId) {
        
        $metaDataIdPh = $this->db->Param(0);
        $bindVars = array($this->db->addQ($metaDataId));
        
        $data = $this->db->GetAll("
            SELECT 
                T2.ID, 
                T1.ID AS TEMPLATE_META_DATA_ID,
                T1.CODE AS META_DATA_CODE,
                T1.NAME AS META_DATA_NAME,
                NULL AS CRITERIA, 
                CT.ID AS TEMPLATE_GROUP_ID, 
                CT.NAME AS TEMPLATE_GROUP_NAME, 
                NULL AS IS_DEFAULT,
                T2.CONFIG_STR
            FROM KPI_INDICATOR_INDICATOR_MAP T0 
                INNER JOIN KPI_INDICATOR T1 ON T1.ID = T0.TRG_INDICATOR_ID 
                    AND T1.KPI_TYPE_ID = 2011 
                INNER JOIN META_REPORT_TEMPLATE_LINK T2 ON T2.MAIN_INDICATOR_ID = T1.ID 
                    AND T2.DATA_INDICATOR_ID IS NOT NULL 
                LEFT JOIN CUSTOMER_TEMPLATE_MAP CTM ON CTM.META_DATA_ID = T1.ID 
                LEFT JOIN CUSTOMER_TEMPLATE CT ON CT.ID = CTM.TEMPLATE_ID 
                LEFT JOIN ( 
                    SELECT 
                        MAX(SRC_META_DATA_ID) AS SRC_META_DATA_ID 
                    FROM CUSTOMER_USE_CHILD 
                    WHERE SRC_META_DATA_ID = $metaDataIdPh 
                        AND IS_USE = 1 
                        AND INDICATOR_ID IS NULL 
                ) CC ON CC.SRC_META_DATA_ID = $metaDataIdPh 
                LEFT JOIN CUSTOMER_USE_CHILD CUC ON CUC.SRC_META_DATA_ID = $metaDataIdPh 
                    AND CUC.TRG_META_DATA_ID = T1.ID 
                    AND CUC.IS_USE = 1 
            WHERE T0.SRC_INDICATOR_ID = $metaDataIdPh 
                AND T0.SEMANTIC_TYPE_ID = 10000015 
                AND (CC.SRC_META_DATA_ID IS NULL OR CUC.ID IS NOT NULL) 
            ORDER BY T1.NAME ASC", $bindVars);   
        
        return $data;
    }

    public function getDataModelByTemplate($templateId, $isTemplateMetaId = false) {
        
        $templateIdPh = $this->db->Param(0);
        $bindVars = array($this->db->addQ(Input::param($templateId)));
        
        if (Mdtemplate::$isKpiIndicator) {
            
            $sql = "
                SELECT 
                    TL.ID, 
                    TL.MAIN_INDICATOR_ID AS META_DATA_ID, 
                    TL.DATA_INDICATOR_ID AS DATA_MODEL_ID, 
                    TL.HTML_CONTENT, 
                    TL.HTML_FILE_PATH, 
                    LOWER(TL.GET_MODE) AS GET_MODE, 
                    TL.DIRECTORY_ID, 
                    TL.PAGING_CONFIG, 
                    TL.PAGE_MARGIN_TOP, 
                    TL.PAGE_MARGIN_LEFT, 
                    TL.PAGE_MARGIN_RIGHT, 
                    TL.PAGE_MARGIN_BOTTOM, 
                    LOWER(TL.ARCHIVE_WFM_STATUS_CODE) AS ARCHIVE_WFM_STATUS_CODE, 
                    TL.UI_EXPRESSION, 
                    TL.IS_ARCHIVE, 
                    TL.IS_EMAIL, 
                    TL.IS_AUTO_ARCHIVE, 
                    TL.IS_TABLE_LAYOUT_FIXED, 
                    TL.IS_IGNORE_PRINT, 
                    TL.IS_IGNORE_EXCEL, 
                    TL.IS_IGNORE_PDF, 
                    TL.IS_IGNORE_WORD, 
                    TL.IS_PDF_SMART_SHRINKING, 
                    TL.IS_BLOCKCHAIN_VERIFY, 
                    TL.CONFIG_STR, 
                    MD.NAME AS META_DATA_NAME, 
                    (
                        SELECT 
                            COUNT(ID) 
                        FROM META_DM_TEMPLATE_DTL 
                        WHERE SRC_META_DATA_ID = TL.MAIN_INDICATOR_ID 
                            AND META_DATA_ID IS NOT NULL 
                    ) AS COUNT_SECOND_PRINT_TEMPLATE 
                FROM META_REPORT_TEMPLATE_LINK TL 
                    INNER JOIN KPI_INDICATOR MD ON MD.ID = TL.MAIN_INDICATOR_ID  
                WHERE ".($isTemplateMetaId ? " TL.MAIN_INDICATOR_ID = $templateIdPh" : " TL.ID = $templateIdPh");
            
        } else {
            
            $sql = "
                SELECT 
                    TL.ID, 
                    TL.META_DATA_ID, 
                    TL.DATA_MODEL_ID, 
                    TL.HTML_CONTENT, 
                    TL.HTML_FILE_PATH, 
                    LOWER(TL.GET_MODE) AS GET_MODE, 
                    TL.DIRECTORY_ID, 
                    MD.META_DATA_NAME, 
                    TL.PAGING_CONFIG, 
                    TL.PAGE_MARGIN_TOP, 
                    TL.PAGE_MARGIN_LEFT, 
                    TL.PAGE_MARGIN_RIGHT, 
                    TL.PAGE_MARGIN_BOTTOM, 
                    LOWER(TL.ARCHIVE_WFM_STATUS_CODE) AS ARCHIVE_WFM_STATUS_CODE, 
                    TL.UI_EXPRESSION, 
                    TL.IS_ARCHIVE, 
                    TL.IS_EMAIL, 
                    TL.IS_AUTO_ARCHIVE, 
                    TL.IS_TABLE_LAYOUT_FIXED, 
                    TL.IS_IGNORE_PRINT, 
                    TL.IS_IGNORE_EXCEL, 
                    TL.IS_IGNORE_PDF, 
                    TL.IS_IGNORE_WORD, 
                    TL.IS_PDF_SMART_SHRINKING, 
                    TL.IS_BLOCKCHAIN_VERIFY, 
                    TL.CONFIG_STR, 
                    (
                        SELECT 
                            COUNT(ID) 
                        FROM META_DM_TEMPLATE_DTL 
                        WHERE SRC_META_DATA_ID = TL.META_DATA_ID 
                            AND META_DATA_ID IS NOT NULL 
                    ) AS COUNT_SECOND_PRINT_TEMPLATE 
                FROM META_REPORT_TEMPLATE_LINK TL 
                    INNER JOIN META_DATA MD ON MD.META_DATA_ID = TL.META_DATA_ID 
                WHERE ".($isTemplateMetaId ? " TL.META_DATA_ID = $templateIdPh" : " TL.ID = $templateIdPh");
        }

        $row = $this->db->GetRow($sql, $bindVars);

        return $row;
    }

    public function getReportTemplateByMetaDataId($metaDataId) {
        
        $row = $this->db->GetRow("
            SELECT 
                ID, 
                META_DATA_ID, 
                DATA_MODEL_ID,  
                LOWER(GET_MODE) AS GET_MODE, 
                DIRECTORY_ID, 
                IS_TABLE_LAYOUT_FIXED, 
                HTML_FILE_PATH, 
                HTML_HEADER_CONTENT, 
                HTML_FOOTER_CONTENT, 
                PAGE_MARGIN_TOP, 
                PAGE_MARGIN_LEFT, 
                PAGE_MARGIN_RIGHT, 
                PAGE_MARGIN_BOTTOM, 
                ARCHIVE_WFM_STATUS_CODE 
            FROM META_REPORT_TEMPLATE_LINK 
            WHERE META_DATA_ID = ".$this->db->Param(0), 
            array($metaDataId)
        );
        
        return $row;
    }

    public function getTemplates($templateIds) {
        $result = $this->db->GetAll("
            SELECT 
                TEMP.ID, 
                MD.META_DATA_CODE,
                MD.META_DATA_NAME
            FROM META_REPORT_TEMPLATE_LINK TEMP
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = TEMP.META_DATA_ID
            WHERE MD.META_DATA_ID IN ($templateIds)");
        
        return $result;
    }

    public function getMetaTypeCodeByDataViewId($metaDataCode, $dataViewId) {
        return $this->db->GetOne("
            SELECT 
                DATA_TYPE AS META_TYPE_CODE 
            FROM META_GROUP_CONFIG  
            WHERE MAIN_META_DATA_ID = $dataViewId 
                AND LOWER(FIELD_PATH) LIKE '%" . (strpos($metaDataCode, '.') !== false ? '.' . strtolower($metaDataCode) : strtolower($metaDataCode)) . "'");
    }
    
    public function getTypeCodeDataViewModel($dataViewId) {
        
        if ($dataViewId) {
            
            if (Mdtemplate::$isKpiIndicator) {
                
                $data = $this->db->GetAll("
                    SELECT 
                        LOWER(KIIM.COLUMN_NAME_PATH) AS FIELD_PATH, 
                        KIIM.SHOW_TYPE AS META_TYPE_CODE, 
                        null AS LOOKUP_TYPE, 
                        KIIM.TRG_INDICATOR_ID AS LOOKUP_META_DATA_ID, 
                        null AS FRACTION_RANGE 
                    FROM KPI_INDICATOR_INDICATOR_MAP KIIM 
                        LEFT JOIN KPI_INDICATOR KI ON KIIM.TRG_INDICATOR_ID = KI.ID 
                        LEFT JOIN META_SEMANTIC_TYPE MST ON KIIM.SEMANTIC_TYPE_ID = MST.ID 
                    WHERE KIIM.MAIN_INDICATOR_ID = ".$this->db->Param(0)." 
                        AND ".$this->db->IfNull('KIIM.IS_INPUT', '0')." = 1 
                        AND KIIM.SHOW_TYPE NOT IN ('row', 'rows') 
                        AND KIIM.COLUMN_NAME IS NOT NULL 
                        AND KIIM.COLUMN_NAME <> 'ID' 
                    ORDER BY KIIM.ORDER_NUMBER ASC", 
                    [$dataViewId]
                );
                
            } else {
                
                $data = $this->db->GetAll("
                    SELECT 
                        LOWER(FIELD_PATH) AS FIELD_PATH,
                        DATA_TYPE AS META_TYPE_CODE, 
                        LOOKUP_TYPE,     
                        LOOKUP_META_DATA_ID, 
                        FRACTION_RANGE 
                    FROM META_GROUP_CONFIG 
                    WHERE COLUMN_NAME IS NOT NULL 
                        AND MAIN_META_DATA_ID = ".$this->db->Param(0), 
                    [$dataViewId]
                ); 
            }

            $array['rid'] = ''; 

            foreach ($data as $row) {

                if ($row['FRACTION_RANGE'] != '') {

                    $array[$row['FIELD_PATH']] = 'scale';
                    Mdtemplate::$dataViewColumnsTypeScale[$row['FIELD_PATH']] = $row['FRACTION_RANGE'];

                } else {
                    $array[$row['FIELD_PATH']] = $row['META_TYPE_CODE'];
                }
            }

            Mdtemplate::$dataViewColumnsType = $array;
            
        } else {
            $data = array();
        }

        return $data;
    }
    
    public function getTypeCodeTemplateModel($dataViewColumnsType, $column) {
        
        if (isset($dataViewColumnsType[$column])) {
            return $dataViewColumnsType[$column];
        }
        
        return 'string';
    }
    
    public function getRowData($mainMetaDataId, $selectedRowId) {

        $param = array(
            'systemMetaGroupId' => $mainMetaDataId,
            'showQuery' => 0, 
            'ignorePermission' => 1,  
            'criteria' => array(
                'id' => array(
                    array(
                        'operator' => '=',
                        'operand' => $selectedRowId
                    )
                )
            )
        );
        $result = $this->ws->runArrayResponse(Mddatamodel::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($result['status'] == 'success' && isset($result['result'][0])) {
            return $result['result'][0];
        }

        return null;  
    }

    public function getRowDataDtl($mainMetaDataId, $selectedRowData, $dmMetaDataId = null, $templateId = null) {
        
        if (Mdtemplate::$isKpiIndicator) {
            
            $this->load->model('mdform', 'middleware/models/');
            
            $_POST['fillSelectedRow'] = $selectedRowData;
            $_POST['param']['mainIndicatorId'] = $dmMetaDataId;
            $_POST['param']['crudIndicatorId'] = $templateId;
            $_POST['param']['indicatorId'] = $mainMetaDataId;
            $_POST['param']['GET_INDICATOR_ID'] = $mainMetaDataId;

            $resultData = $this->model->getDefaultFillDataModel($mainMetaDataId);
            $resultData = Arr::changeKeyLower($resultData);
            
            if (Mdtemplate::$mergeResponseData) {
                $resultData = array_merge($resultData, Mdtemplate::$mergeResponseData);
            }
            
            $this->load->model('mdtemplate', 'middleware/models/');
            
            return $resultData;
        }
        
        if (Input::postCheck('queryStrCriteria')) {
            
            parse_str(Input::post('queryStrCriteria'), $queryStrCriteria);
            
            foreach ($queryStrCriteria as $key => $val) {
                $paramCriteria[$key][] = array(
                    'operator' => '=',
                    'operand' => Input::param($val)
                );
            }
            
        } else {
            
            $isConfig = false;

            if ($templateId && $dmMetaDataId) {

                $configData = $this->db->GetAll("
                    SELECT 
                        LOWER(ST.SRC_PARAM_NAME) AS SRC_PARAM_NAME, 
                        LOWER(ST.TRG_PARAM_NAME) AS TRG_PARAM_NAME, 
                        ST.DEFAULT_VALUE 
                    FROM META_SRC_TRG_PARAM ST 
                        INNER JOIN META_REPORT_TEMPLATE_LINK TL ON TL.META_DATA_ID = ST.TRG_META_DATA_ID   
                    WHERE TL.META_DATA_ID = ".$this->db->Param(0)." 
                        AND ST.SRC_META_DATA_ID = ".$this->db->Param(1)." 
                        AND ST.TRG_PARAM_NAME IS NOT NULL", 
                    array($templateId, $dmMetaDataId)
                ); 

                if ($configData) {

                    foreach ($configData as $row) {

                        if (is_array($selectedRowData)) {

                            $val = (!empty($row['SRC_PARAM_NAME']) && isset($selectedRowData[$row['SRC_PARAM_NAME']])) ? $selectedRowData[$row['SRC_PARAM_NAME']] : $row['DEFAULT_VALUE'];

                        } else {
                            $val = $selectedRowData;
                        }

                        if (trim($val) != '') {
                            $paramCriteria[$row['TRG_PARAM_NAME']][] = array(
                                'operator' => '=',
                                'operand' => $val
                            );
                            $isConfig = true;
                        }
                    }
                }
            }

            if (!$isConfig) {

                if (is_array($selectedRowData)) {

                    if (isset($selectedRowData['param']) && !empty($selectedRowData['param'])) {

                        $paramLower = Arr::changeKeyLower($selectedRowData['param']);

                        if (isset($paramLower['id'])) {
                            $paramCriteria['id'][] = array(
                                'operator' => '=',
                                'operand' => Input::param($paramLower['id'])
                            );
                        }

                    } else {

                        if (array_key_exists(0, $selectedRowData)) {
                            $data = is_array($selectedRowData[0]) ? $selectedRowData[0] : $selectedRowData;
                        } else {
                            $data = $selectedRowData;
                        }

                        $data = array_change_key_case($data, CASE_LOWER); 

                        if (isset($data['id'])) {
                            $paramCriteria['id'][] = array(
                                'operator' => '=',
                                'operand' => Input::param($data['id'])
                            );
                        }
                    }

                } else {
                    $paramCriteria['id'][] = array(
                        'operator' => '=',
                        'operand' => $selectedRowData 
                    );
                }
            }
        }

        $param = array(
            'systemMetaGroupId' => $mainMetaDataId, 
            '_isTranslate' => 1, 
            'criteria' => isset($paramCriteria) ? $paramCriteria : '' 
        );
        
        $result = $this->ws->runArrayResponse(Mddatamodel::$gfServiceAddress, Mddatamodel::$getRowDataViewCommand, $param);
        
        if ($result['status'] == 'success' && isset($result['result'])) {
            $resultData = $result['result'];
            
            if (Mdtemplate::$mergeResponseData) {
                $resultData = array_merge($resultData, Mdtemplate::$mergeResponseData);
            }
            return $resultData;
        } else {
            @file_put_contents(BASEPATH.'log/service_response.log', json_encode($param).' 1 - '.json_encode($result, JSON_UNESCAPED_UNICODE));
        }

        return null;
    }        

    public function getRowConsolidateDataDtl($mainMetaDataId, $selectedRowData, $dmMetaDataId = null, $templateId = null) {

        if (!is_array($selectedRowData)) {
            return null;
        }
        
        if (Mdtemplate::$isKpiIndicator) {
            
            $this->load->model('mdform', 'middleware/models/');
            
            $_POST['fillSelectedRow'] = $selectedRowData;
            $_POST['param']['mainIndicatorId'] = $dmMetaDataId;
            $_POST['param']['crudIndicatorId'] = $templateId;
            $_POST['param']['indicatorId'] = $mainMetaDataId;
            $_POST['param']['GET_INDICATOR_ID'] = $mainMetaDataId;

            $resultData = $this->model->getDefaultFillDataModel($mainMetaDataId);
            $resultData = Arr::changeKeyLower($resultData);
            
            if (Mdtemplate::$mergeResponseData) {
                $resultData = array_merge($resultData, Mdtemplate::$mergeResponseData);
            }
            
            $this->load->model('mdtemplate', 'middleware/models/');
            
            return $resultData;
        }
        
        if (Mdtemplate::$getListCommand) {
            $command = Mdtemplate::$getListCommand;
            $glue = '|';
        } else {
            $glue = ',';
            $command = Mddatamodel::$consolidateDataViewCommand;
        }
            
        $isConfig = false;

        if ($templateId && $dmMetaDataId) {

            $configData = $this->db->GetAll("
                SELECT 
                    LOWER(ST.SRC_PARAM_NAME) AS SRC_PARAM_NAME, 
                    LOWER(ST.TRG_PARAM_NAME) AS TRG_PARAM_NAME, 
                    ST.DEFAULT_VALUE 
                FROM META_SRC_TRG_PARAM ST 
                    INNER JOIN META_REPORT_TEMPLATE_LINK TL ON TL.META_DATA_ID = ST.TRG_META_DATA_ID   
                WHERE TL.META_DATA_ID = ".$this->db->Param(0)." 
                    AND ST.SRC_META_DATA_ID = ".$this->db->Param(1)." 
                    AND ST.TRG_PARAM_NAME IS NOT NULL", 
                array($templateId, $dmMetaDataId)
            ); 

            if ($configData) {

                foreach ($configData as $row) {

                    if (is_array($selectedRowData)) {

                        if (isset($selectedRowData['param']) && !empty($selectedRowData['param'])) {

                            $paramLower = Arr::changeKeyLower($selectedRowData['param']);

                            $val = (!empty($row['SRC_PARAM_NAME']) && isset($paramLower[$row['SRC_PARAM_NAME']])) ? Arr::implode_key($glue, $selectedRowData, $row['SRC_PARAM_NAME'], true) : $row['DEFAULT_VALUE'];

                        } else {
                            $val = (!empty($row['SRC_PARAM_NAME']) && isset($selectedRowData[0][$row['SRC_PARAM_NAME']])) ? Arr::implode_key($glue, $selectedRowData, $row['SRC_PARAM_NAME'], true) : $row['DEFAULT_VALUE'];
                        }

                    } else {
                        $val = $selectedRowData;
                    }

                    if (trim($val) != '') {
                        $paramCriteria[$row['TRG_PARAM_NAME']][] = array(
                            'operator' => 'IN',
                            'operand' => $val
                        );
                    }
                }

                $isConfig = true;
            }
        }

        if (!$isConfig) {

            $paramCriteria['id'][] = array(
                'operator' => 'IN',
                'operand' => Input::param(Arr::implode_key(',', $selectedRowData, 'id', true)) 
            );
        }

        $param = array(
            'systemMetaGroupId' => $mainMetaDataId,
            'showQuery' => 1, 
            'criteria' => isset($paramCriteria) ? $paramCriteria : '' 
        );
        
        $result = $this->ws->runArrayResponse(Mddatamodel::$gfServiceAddress, $command, $param);

        if ($result['status'] == 'success' && isset($result['result'])) {
            return $result['result'];
        } 

        return null;
    }
    
    public function getRowParseJsonDataDtl($mainMetaDataId, $selectedRowData) {
        
        if (!is_array($selectedRowData)) {
            return null;
        }
        
        if (isset($selectedRowData[0]['parsesrctablename']) && isset($selectedRowData[0]['parsesrccolumnname']) && isset($selectedRowData[0]['parsesrctype'])) {
            
            $data = $this->db->GetOne("SELECT ". $selectedRowData[0]['parsesrccolumnname'] ." FROM ". $selectedRowData[0]['parsesrctablename'] ." WHERE ID = " . $selectedRowData[0]['id']);
            
            $resultData = Arr::changeKeyLower(json_decode($data, true));
            
            if ($resultData) {
                
                if (isset($selectedRowData[0]['parsejsonfilter']) && $selectedRowData[0]['parsejsonfilter']) {
                    
                    parse_str($selectedRowData[0]['parsejsonfilter'], $parseJsonFilter);
                    
                    if (isset($parseJsonFilter['sys_startdate']) && isset($parseJsonFilter['sys_enddate']) && isset($parseJsonFilter['yearcolname']) && isset($parseJsonFilter['monthcolname'])) {
                        includeLib('Array/arrch');
                        
                        $rowsPath = $parseJsonFilter['sys_rowspath'];
                        $list = $resultData['result'][$rowsPath];
                        $yearColName = $parseJsonFilter['yearcolname'];
                        $monthColName = $parseJsonFilter['monthcolname'];
                        
                        foreach ($list as $k => $row) {
                            $row['date'] = intval(Date::formatter($row[$yearColName].'-'.$row[$monthColName], 'Ym'));
                            $list[$k] = $row;
                        }
                        
                        $criteria = array();
                        
                        $startdate = $selectedRowData[0][$parseJsonFilter['sys_startdate']];
                        $enddate = $selectedRowData[0][$parseJsonFilter['sys_enddate']];
                        
                        $sys_startdate = Date::formatter($startdate, 'Ym');
                        $sys_enddate = Date::formatter($enddate, 'Ym');
                        
                        $criteria['where'][] = array('date', '>=', intval($sys_startdate));

                        $list = Arrch\Arrch::find($list, $criteria, 'all');
                        
                        $criteria = array();
                        $criteria['where'][] = array('date', '<=', intval($sys_enddate));
                        
                        $list = Arrch\Arrch::find($list, $criteria, 'all');
                        
                        $resultData['result'][$rowsPath] = array_values($list);
                    }
                }
                
                return $resultData;
            }
        }

        return null;
    }
    
    public function getRowDataById($mainMetaDataId, $selectedRowId) {

        $row = self::getMethodIdByMetaData($mainMetaDataId);

        $param = array(
            'id' => $selectedRowId
        );

        $result = $this->ws->caller($row['SERVICE_LANGUAGE_CODE'], $row['WS_URL'], $row['META_DATA_CODE'], 'return', $param);

        if ($result['status'] == 'success' && isset($result['result'])) {
            return $result['result'];
        }

        return null;
    }

    public function getDataViewColumnsModel($metaGroupId) {
        $data = $this->db->GetAll("
            SELECT 
                LOWER(FIELD_PATH) AS FIELD_PATH, 
                LABEL_NAME AS META_DATA_NAME 
            FROM META_GROUP_CONFIG 
            WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                AND PARENT_ID IS NULL 
                AND DATA_TYPE <> 'group' 
                AND (IS_SELECT = 1 OR EXPRESSION_STRING IS NOT NULL)", 
            array($metaGroupId)
        );

        return $data;
    }

    public function getMethodIdByMetaData($metaDataId) {
        $row = $this->db->GetRow("
            SELECT 
                MD.META_DATA_ID, 
                MD.META_DATA_CODE, 
                " . $this->db->IfNull("PL.PROCESS_NAME", "MD.META_DATA_NAME") . " AS META_DATA_NAME, 
                PL.INPUT_META_DATA_ID, 
                PL.WS_URL, 
                SL.SERVICE_LANGUAGE_CODE, 
                IMD.META_DATA_CODE AS INPUT_META_DATA_CODE, 
                PL.LABEL_WIDTH, 
                PL.COLUMN_COUNT, 
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
                PL.IS_ADDON_LOG, 
                PL.IS_ADDON_RELATION, 
                PL.IS_ADDON_WFM_LOG, 
                PL.IS_ADDON_WFM_LOG_TYPE, 
                PL.REF_META_GROUP_ID,
                PL.THEME_CODE,
                PL.SKIN
            FROM META_BUSINESS_PROCESS_LINK PL 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = PL.META_DATA_ID   
                LEFT JOIN WEB_SERVICE_LANGUAGE SL ON SL.SERVICE_LANGUAGE_ID = PL.SERVICE_LANGUAGE_ID 
                LEFT JOIN META_DATA IMD ON IMD.META_DATA_ID = PL.INPUT_META_DATA_ID 
            WHERE PL.META_DATA_ID = ".$this->db->Param(0), 
            array($metaDataId)
        );

        return $row;
    }

    public function savePrintConfigModel($dataViewId, $options) {
        
        $crmUserId = Session::get(SESSION_PREFIX . 'crmuserid');
        $userKeyId = $crmUserId ? $crmUserId : Ue::sessionUserKeyId();
        
        if (!empty($userKeyId)) {
            $prevRow = self::getPrintConfigRowByUserModel($dataViewId, $userKeyId);

            $configStr = $this->db->GetRow("SELECT ID, CONFIG_STR FROM META_GROUP_PRINT_USER WHERE DV_META_DATA_ID = ".$this->db->Param(0)." AND USER_ID = ".$this->db->Param(1), [$dataViewId, $userKeyId]);
            
            if (isset($options['templateMetaIds'])) {
                $tempMetaIds = $options['templateMetaIds'];
                unset($options['templateMetaIds']);
            
                if ($configStr) { 
                    $decodeTemplateConfig = json_decode($configStr['CONFIG_STR'], true);
                    $decodeTemplateConfig[$tempMetaIds] = $options;
                    $configJson = json_encode($decodeTemplateConfig);                    
                } else {
                    $configJson = json_encode([$tempMetaIds => $options], true);
                }
            } else {
                $configJson = json_encode($options);
            }
            
            if ($configStr) {      
                $this->db->UpdateClob('META_GROUP_PRINT_USER', 'CONFIG_STR', $configJson, 'ID = '.$configStr['ID']);
            } else {
                $data = [
                    'ID'                => getUID(), 
                    'DV_META_DATA_ID'   => $dataViewId, 
                    'USER_ID'           => $userKeyId, 
                    'PIVOT_TEMPLATE_ID' => issetParam($prevRow['PIVOT_TEMPLATE_ID'])
                ];
                $this->db->AutoExecute('META_GROUP_PRINT_USER', $data);
                $this->db->UpdateClob('META_GROUP_PRINT_USER', 'CONFIG_STR', $configJson, 'ID = '.$data['ID']);
            }
        }
        
        return true;
    }

    public function getPrintConfigByUserModel($dataViewId, $templates) {
        
        $crmUserId = Session::get(SESSION_PREFIX . 'crmuserid');
        $userKeyId = $crmUserId ? $crmUserId : Ue::sessionUserKeyId();

        $row = self::getPrintConfigRowByUserModel($dataViewId, $userKeyId);
        
        $templateMetaIds = '';
        foreach ($templates as $tempRow) {
            $templateMetaIds .= $tempRow['TEMPLATE_META_DATA_ID'] . '_';
        }
        $templateMetaIds = trim($templateMetaIds, '_');

        if ($row && $row['CONFIG_STR']) {
            $decodeTemplateConfig = json_decode($row['CONFIG_STR'], true);
            
            if (array_key_exists($templateMetaIds, $decodeTemplateConfig)) {
                return $decodeTemplateConfig[$templateMetaIds];
            }
            
            return $decodeTemplateConfig;
        }

        return array();
    }
    
    public function getPrintConfigRowByUserModel($dataViewId, $userKeyId) {
        if (!empty($userKeyId)) {
            return $this->db->GetRow("SELECT * FROM META_GROUP_PRINT_USER WHERE DV_META_DATA_ID = ".$this->db->Param(0)." AND USER_ID = ".$this->db->Param(1), array($dataViewId, $userKeyId));
        }
        return false;            
    }

    public function generateQrFields($templateId, $dataElement) {

        $data = $this->db->GetAll("
            SELECT 
                GC.LABEL_NAM, 
                LOWER(TQ.FIELD_NAME) AS FIELD_NAME     
            FROM META_REPORT_TEMPLATE_QRCODE TQ 
                INNER JOIN META_REPORT_TEMPLATE_LINK TL ON TL.META_DATA_ID = TQ.RT_META_DATA_ID 
                INNER JOIN META_GROUP_CONFIG GC ON GC.MAIN_META_DATA_ID = TL.DATA_MODEL_ID 
                    AND LOWER(TQ.FIELD_NAME) = LOWER(GC.FIELD_PATH) 
            WHERE TL.ID = ".$this->db->Param(0)." 
            ORDER BY TQ.ORDER_NUM ASC", array($templateId)
        );

        $string = null;

        if ($data) {
            foreach ($data as $row) {
                if (isset($dataElement[$row['FIELD_NAME']])) {
                    $string .= $row['LABEL_NAME'].':'.$dataElement[$row['FIELD_NAME']].';';
                }
            }
        }

        return $string;
    }

    public function sendMailModel($emailTo, $emailToCc, $emailToBcc, $emailSubject, $emailBody, $fileToSave, $isSendPdf, $isSendExcel, $isSendWord, $emailFileName) {
        
        $emailBody = html_entity_decode($emailBody);

        if (Input::postCheck('selectedRows')) {
            
            $dataViewId = Input::post('dataViewId');
            $selectedRows = Input::postNonTags('selectedRows');
            
            $this->load->model('mddatamodel', 'middleware/models/');
            
            $rowsHtml = $this->model->rowsToHtmlTable($dataViewId, $selectedRows);   
            $emailBody = empty($emailBody) ? '<br />'.$rowsHtml : $emailBody.'<br /><br />'.$rowsHtml;
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
        
        $emailToArr = array_map('trim', explode(';', rtrim($emailTo, ';')));
        $emailList = $emailToArr;
                
        if (!empty($emailToCc)) {
            
            $emailToCcArr = array_map('trim', explode(';', rtrim($emailToCc, ';')));
            array_push($emailList, $emailToCcArr);
        }

        if (!empty($emailToBcc)) {
            
            $emailToBccArr = array_map('trim', explode(';', rtrim($emailToBcc, ';')));
            array_push($emailList, $emailToBccArr);
        }
        
        foreach ($emailList as $email) {
                
            $email = trim($email);
            
            if ($email) {
                
                $mail->addAddress($email);

                if ($isSendPdf == '1') {
                    $mail->addAttachment($fileToSave . '.pdf', $emailFileName . '.pdf');
                }

                if ($isSendExcel == '1') {
                    $mail->addAttachment($fileToSave . '.xls', $emailFileName . '.xls');
                }

                if ($isSendWord == '1') {
                    $mail->addAttachment($fileToSave . '.doc', $emailFileName . '.doc');
                }

                if (isset($_FILES['file1']) && $_FILES['file1']['error'] == UPLOAD_ERR_OK) {
                    $mail->addAttachment($_FILES['file1']['tmp_name'], $_FILES['file1']['name']);
                }

                if (isset($_FILES['file2']) && $_FILES['file2']['error'] == UPLOAD_ERR_OK) {
                    $mail->addAttachment($_FILES['file2']['tmp_name'], $_FILES['file2']['name']);
                }

                if (!$mail->send()) {
                    $response = array('status' => 'error', 'message' => $mail->ErrorInfo);
                }
            }

            $mail->clearAllRecipients();
        }

        if ($response['status'] == 'success' && Input::isEmpty('emailSentParams') == false) {

            parse_str($_POST['emailSentParams'], $emailSentParams);

            $id = $emailSentParams['id'];
            $tableName = $emailSentParams['tableName'];
            $isSent = $emailSentParams['isSent'];
            $primaryField = $emailSentParams['primaryField'];
            $date = $emailSentParams['date'];

            $updateData = array(
                $isSent => 1, 
                $date => Date::currentDate('Y-m-d H:i:s') 
            );

            if (Str::upper($tableName) === 'EML_EMAIL_LOG') {
                $ipAddress   = get_client_ip();
                $userId      = Ue::sessionUserKeyId();
                $currentDate = Date::currentDate();

                foreach ($emailList as $emk => $email) {
                    $email = trim($email);                
                    $data = array(
                        'ID'          => getUIDAdd($emk), 
                        'EMAIL'       => $email, 
                        'ACTION_DATE' => $currentDate, 
                        'STATUS'      => '[PHP] sent', 
                        'FROM_IP'     => $ipAddress, 
                        'RECORD_ID'   => $id, 
                        'REF_STUCTURE_ID' => issetParam($emailSentParams['refStructureId']), 
                        'USER_ID'     => $userId
                    );
                    $this->db->AutoExecute('EML_EMAIL_LOG', $data);                
                }
            } else {
                $this->db->AutoExecute($tableName, $updateData, 'UPDATE', $primaryField.' = '.$id);
            }
            
            if ($isSendPdf == '1') {
                $attachFileId = getUID();
                $dataAttachFile = array(
                    'CONTENT_ID' => $attachFileId,
                    'FILE_NAME' => $emailFileName,
                    'PHYSICAL_PATH' => $fileToSave . '.pdf',
                    'FILE_EXTENSION' => 'pdf',
                    'FILE_SIZE' => filesize($fileToSave . '.pdf'),
                    'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s'), 
                    'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                    'IS_EMAIL' => '1'
                );

                $attachFile = $this->db->AutoExecute('ECM_CONTENT', $dataAttachFile);
                if ($attachFile) {
                    $dataMetaValue = array(
                        'ID' => getUID(),
                        'RECORD_ID' => $id,
                        'CONTENT_ID' => Input::param($attachFileId)
                    );
                    $this->db->AutoExecute('ECM_CONTENT_MAP', $dataMetaValue);
                }            
            }            
            if ($isSendExcel == '1') {
                $attachFileId = getUID();
                $dataAttachFile = array(
                    'CONTENT_ID' => $attachFileId,
                    'FILE_NAME' => $emailFileName,
                    'PHYSICAL_PATH' => $fileToSave . '.xls',
                    'FILE_EXTENSION' => 'xls',
                    'FILE_SIZE' => filesize($fileToSave . '.xls'),
                    'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s'), 
                    'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                    'IS_EMAIL' => '1'
                );

                $attachFile = $this->db->AutoExecute('ECM_CONTENT', $dataAttachFile);
                if ($attachFile) {
                    $dataMetaValue = array(
                        'ID' => getUID(),
                        'RECORD_ID' => $id,
                        'CONTENT_ID' => Input::param($attachFileId)
                    );
                    $this->db->AutoExecute('ECM_CONTENT_MAP', $dataMetaValue);
                }            
            }            
            if ($isSendWord == '1') {
                $attachFileId = getUID();
                $dataAttachFile = array(
                    'CONTENT_ID' => $attachFileId,
                    'FILE_NAME' => $emailFileName,
                    'PHYSICAL_PATH' => $fileToSave . '.doc',
                    'FILE_EXTENSION' => 'doc',
                    'FILE_SIZE' => filesize($fileToSave . '.doc'),
                    'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s'), 
                    'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                    'IS_EMAIL' => '1'
                );

                $attachFile = $this->db->AutoExecute('ECM_CONTENT', $dataAttachFile);
                if ($attachFile) {
                    $dataMetaValue = array(
                        'ID' => getUID(),
                        'RECORD_ID' => $id,
                        'CONTENT_ID' => Input::param($attachFileId)
                    );
                    $this->db->AutoExecute('ECM_CONTENT_MAP', $dataMetaValue);
                }            
            }       
            
            if (isset($_FILES['file1']) && $_FILES['file1']['error'] == UPLOAD_ERR_OK) {
                
                $fileToSave = UPLOADPATH . Mdwebservice::$uploadedPath . 'file_' . getUID();
                $fileExtension = strtolower(substr($_FILES['file1']['name'], strrpos($_FILES['file1']['name'], '.') + 1));
                file_put_contents($fileToSave . '.'.$fileExtension, file_get_contents($_FILES['file1']['tmp_name']));
                $attachFileId = getUID();
                
                $dataAttachFile = array(
                    'CONTENT_ID' => $attachFileId,
                    'FILE_NAME' => $_FILES['file1']['name'],
                    'PHYSICAL_PATH' => $fileToSave . '.'.$fileExtension,
                    'FILE_EXTENSION' => $fileExtension,
                    'FILE_SIZE' => filesize($fileToSave . '.'.$fileExtension),
                    'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s'), 
                    'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                    'IS_EMAIL' => '1'
                );

                $attachFile = $this->db->AutoExecute('ECM_CONTENT', $dataAttachFile);
                if ($attachFile) {
                    $dataMetaValue = array(
                        'ID' => getUID(),
                        'RECORD_ID' => $id,
                        'CONTENT_ID' => Input::param($attachFileId)
                    );
                    $this->db->AutoExecute('ECM_CONTENT_MAP', $dataMetaValue);
                }            
            }            
            
            if (isset($_FILES['file2']) && $_FILES['file2']['error'] == UPLOAD_ERR_OK) {
                $fileToSave = UPLOADPATH . Mdwebservice::$uploadedPath . 'file_' . getUID();
                $fileExtension = strtolower(substr($_FILES['file2']['name'], strrpos($_FILES['file2']['name'], '.') + 1));
                file_put_contents($fileToSave . '.'.$fileExtension, file_get_contents($_FILES['file2']['tmp_name']));
                $attachFileId = getUID();
                $dataAttachFile = array(
                    'CONTENT_ID' => $attachFileId,
                    'FILE_NAME' => $_FILES['file2']['name'],
                    'PHYSICAL_PATH' => $fileToSave . '.'.$fileExtension,
                    'FILE_EXTENSION' => $fileExtension,
                    'FILE_SIZE' => filesize($fileToSave . '.'.$fileExtension),
                    'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s'), 
                    'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                    'IS_EMAIL' => '1'
                );

                $attachFile = $this->db->AutoExecute('ECM_CONTENT', $dataAttachFile);
                if ($attachFile) {
                    $dataMetaValue = array(
                        'ID' => getUID(),
                        'RECORD_ID' => $id,
                        'CONTENT_ID' => Input::param($attachFileId)
                    );
                    $this->db->AutoExecute('ECM_CONTENT_MAP', $dataMetaValue);
                }            
            }      
            
        } else {
            @unlink($fileToSave . '.pdf');
            @unlink($fileToSave . '.xls');
            @unlink($fileToSave . '.doc');
        }

        return $response;
    }
    
    public function getEmployeeLastConfigModel($employeeId, $type) {
        $row = $this->db->GetRow("
            SELECT 
                ROW_NUMBER,
                PAGE_NUMBER,
                BOOK_TYPE_ID
            FROM HRM_EMP_SI_BOOK
            WHERE EMPLOYEE_ID = ".$this->db->Param(0)." 
                AND LOWER(TYPE) = ".$this->db->Param(1), 
            array($employeeId, $type)
        );
        
        if ($row) {
            return $row;
        }
        
        return array('ROW_NUMBER' => '0', 'PAGE_NUMBER' => '2', 'BOOK_TYPE_ID' => '');
    }         
    
    public function getNDDprintPositionModel($employeeId, $printOptions) {

        $isConfig = false;
        $nddYearCode = Input::param($printOptions['nddYearCode']);
        $bookTypeId = Input::param($printOptions['bookTypeId']);
        $type = Input::param($printOptions['type']);

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
            WHERE ID = " . $this->db->Param(0), 
            array($bookTypeId) 
        );

        $getRows = array();
        $startMonth = 1;
        $endMonth = 1;

        if (!$isConfig) {

            $row = $dataCustom;
            $startMonth = $printOptions['nddMonthPrev'];       
            $endMonth = $printOptions['nddMonthNext'];

            $existData = $this->db->GetOne("SELECT COUNT(ID) FROM HRM_EMP_SI_BOOK WHERE LOWER(TYPE) = '$type' AND EMPLOYEE_ID = " . $employeeId);

            if ($printOptions['nddMonthNext'] === '12') {
                $updateData = array(
                    'ROW_NUMBER' => 1, 
                    'PAGE_NUMBER' => ++$printOptions['nddPageNum']
                );
                if ($existData !== '0') 
                    $this->db->AutoExecute('HRM_EMP_SI_BOOK', $updateData, 'UPDATE', "LOWER(TYPE) = '$type' AND EMPLOYEE_ID = " . $employeeId);                    
            } else {
                $updateData = array(
                    'ROW_NUMBER' => ++$printOptions['nddMonthNext']
                );                    
                if ($existData !== '0') 
                    $this->db->AutoExecute('HRM_EMP_SI_BOOK', $updateData, 'UPDATE', "LOWER(TYPE) = '$type' AND EMPLOYEE_ID = " . $employeeId);
            }

            if ($existData === '0') {
                $insertData = array_merge($updateData, array(
                    'ID' => getUID(),
                    'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s'),
                    'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                    'EMPLOYEE_ID' => $employeeId, 
                    'TYPE' => $type 
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
                
                if ($row['ROW_NUMBER'] === '12') {
                    $this->db->AutoExecute('HRM_EMP_SI_BOOK', array('ROW_NUMBER' => 1, 'PAGE_NUMBER' => ++$row['ROW_NUMBER']), 'UPDATE', "LOWER(TYPE) = '$type' AND EMPLOYEE_ID = " . $employeeId);
                } else
                    $this->db->AutoExecute('HRM_EMP_SI_BOOK', array('ROW_NUMBER' => ++$row['ROW_NUMBER']), 'UPDATE', "LOWER(TYPE) = '$type' AND EMPLOYEE_ID = " . $employeeId);                    

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

            $nddData = $this->getEmployeeNDDdata($employeeId, $nddYearCode, $row['ROW_NUMBER']); 

            if (is_null($nddData))
                $nddData = array(
                    'f100' => '',
                    'f101' => '',
                    'f102' => '', 
                    'f103' => '', 
                    'f104' => ''
                );

            if ((int) $row['PAGE_NUMBER'] % 2 === 0) {
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
                'col3Data' => $nddData['f102'] == 0 || is_null($nddData['f102']) ? '' : Number::formatMoney(intval($nddData['f102'])), 
                'col4Data' => $nddData['f101'] == 0 || is_null($nddData['f103']) ? '' : Number::formatMoney(intval($nddData['f103'])),
                'col5Data' => $nddData['f102'] == 0 || is_null($nddData['f104']) ? '' : Number::formatMoney(intval($nddData['f104']))
            ));
        }

        return $getRows;
    }        
    
    private function getEmployeeNDDdata($employeeId, $year, $month) {
        $param = array(
            'systemMetaGroupId' => '1484788699181',
            'criteria' => array(
                'filterEmployeeId' => array(
                    array(
                        'operator' => '=',
                        'operand' => $employeeId
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
    
    public function getNDDYearModel() {
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
    
    public function getNDDBookTypeModel($type) {
        $data = $this->db->GetAll("SELECT ID, CODE, DESCRIPTION FROM HRM_SI_BOOK_TYPE WHERE LOWER(TYPE) = '$type' ORDER BY CODE ASC");
        return $data;
    }
    
    public function getTemplateFolderListModel($dataViewId, $folderId = null) {
        
        $data = $this->db->GetAll("
            SELECT
                ID, 
                NAME  
            FROM CUSTOMER_TEMPLATE 
            WHERE DATA_VIEW_ID = $dataViewId  
                ".($folderId ? 'AND ID <> '.$folderId : '')." 
            ORDER BY NAME ASC" 
        );
        
        return $data;
    }
    
    public function getReportTemplateListModel($metaDataId) {
        $data = $this->db->GetAll("
            SELECT
                DISTINCT REP_RESULT.*
            FROM
            (
                (
                    SELECT
                        MD.META_DATA_ID,
                        MD.META_DATA_CODE,
                        MD.META_DATA_NAME 
                    FROM META_REPORT_TEMPLATE_LINK REP
                        INNER JOIN META_DM_TEMPLATE_DTL DTL ON REP.META_DATA_ID = DTL.TEMPLATE_META_DATA_ID 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = REP.META_DATA_ID 
                    WHERE REP.DATA_MODEL_ID = $metaDataId 
                        AND MD.META_DATA_ID NOT IN (
                            SELECT 
                                TM.META_DATA_ID 
                            FROM CUSTOMER_TEMPLATE_MAP TM 
                                INNER JOIN CUSTOMER_TEMPLATE CT ON CT.DATA_VIEW_ID = $metaDataId AND CT.ID = TM.TEMPLATE_ID 
                        )
                ) 
                
                UNION ALL
              
                (
                    SELECT
                        DTL.TEMPLATE_META_DATA_ID AS META_DATA_ID,
                        LMD.META_DATA_CODE, 
                        LMD.META_DATA_NAME 
                    FROM META_DM_TEMPLATE_DTL DTL
                        INNER JOIN META_GROUP_LINK LINK ON LINK.ID = DTL.META_GROUP_LINK_ID
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = LINK.META_DATA_ID
                        INNER JOIN META_REPORT_TEMPLATE_LINK REP ON REP.META_DATA_ID = DTL.TEMPLATE_META_DATA_ID
                        INNER JOIN META_DATA LMD ON LMD.META_DATA_ID = REP.META_DATA_ID 
                    WHERE MD.META_DATA_ID = $metaDataId 
                        AND DTL.TEMPLATE_META_DATA_ID NOT IN (
                            SELECT 
                                TM.META_DATA_ID 
                            FROM CUSTOMER_TEMPLATE_MAP TM 
                                INNER JOIN CUSTOMER_TEMPLATE CT ON CT.DATA_VIEW_ID = $metaDataId AND CT.ID = TM.TEMPLATE_ID 
                        )
                )
            ) REP_RESULT 
            
            ORDER BY REP_RESULT.META_DATA_CODE, REP_RESULT.META_DATA_NAME ASC");
        
        return $data;
    }
    
    public function getParentFolderReportTemplateModel($dataViewId) {
        
        $data = $this->db->GetAll("
            SELECT
                ID, 
                NAME  
            FROM CUSTOMER_TEMPLATE 
            WHERE DATA_VIEW_ID = ".$this->db->Param(0)." 
                AND PARENT_ID IS NULL 
            ORDER BY NAME ASC", 
            array($dataViewId));
        
        return $data;
    }
    
    public function getChildFolderReportTemplateModel($dataViewId, $folderId) {
        
        $data = $this->db->GetAll("
            SELECT
                ID, 
                NAME  
            FROM CUSTOMER_TEMPLATE 
            WHERE DATA_VIEW_ID = ".$this->db->Param(0)." 
                AND PARENT_ID = ".$this->db->Param(1)." 
            ORDER BY NAME ASC", array($dataViewId, $folderId) 
        );
        
        return $data;
    }
    
    public function getChildReportTemplateListModel($metaDataId, $folderId) {
        $data = $this->db->GetAll("
            SELECT
                DISTINCT REP_RESULT.*
            FROM
            (
                (
                    SELECT
                        MD.META_DATA_ID,
                        MD.META_DATA_CODE,
                        MD.META_DATA_NAME 
                    FROM META_REPORT_TEMPLATE_LINK REP
                        INNER JOIN META_DM_TEMPLATE_DTL DTL ON REP.META_DATA_ID = DTL.TEMPLATE_META_DATA_ID 
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = REP.META_DATA_ID 
                        INNER JOIN CUSTOMER_TEMPLATE_MAP TM ON TM.META_DATA_ID = MD.META_DATA_ID  
                    WHERE REP.DATA_MODEL_ID = $metaDataId 
                        AND TM.TEMPLATE_ID = $folderId  
                ) 
                
                UNION ALL
              
                (
                    SELECT
                        DTL.TEMPLATE_META_DATA_ID AS META_DATA_ID,
                        LMD.META_DATA_CODE, 
                        LMD.META_DATA_NAME 
                    FROM META_DM_TEMPLATE_DTL DTL
                        INNER JOIN META_GROUP_LINK LINK ON LINK.ID = DTL.META_GROUP_LINK_ID
                        INNER JOIN META_DATA MD ON MD.META_DATA_ID = LINK.META_DATA_ID
                        INNER JOIN META_REPORT_TEMPLATE_LINK REP ON REP.META_DATA_ID = DTL.TEMPLATE_META_DATA_ID
                        INNER JOIN META_DATA LMD ON LMD.META_DATA_ID = REP.META_DATA_ID 
                        INNER JOIN CUSTOMER_TEMPLATE_MAP TM ON TM.META_DATA_ID = DTL.TEMPLATE_META_DATA_ID 
                    WHERE MD.META_DATA_ID = $metaDataId 
                        AND TM.TEMPLATE_ID = $folderId  
                )
            ) REP_RESULT 
            
            ORDER BY REP_RESULT.META_DATA_CODE, REP_RESULT.META_DATA_NAME ASC");
        
        return $data;
    }
    
    public function addTemplateFolderSaveModel() {
        
        try {
            
            $data = array(
                'ID' => getUID(), 
                'NAME' => Input::post('name'), 
                'DATA_VIEW_ID' => Input::numeric('metaDataId'), 
                'PARENT_ID' => Input::post('parentId'),
                'CREATED_USER_ID' => Ue::sessionUserKeyId(), 
                'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s')
            );
            
            $this->db->AutoExecute('CUSTOMER_TEMPLATE', $data);
            
            return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
            
        } catch (ADODB_Exception $ex) {
            return array('status' => 'error', 'message' => $ex->getMessage());
        }
    }
    
    public function getParentTemplateFolderModel($dataViewId, $folderId) {
        
        $row = $this->db->GetRow("SELECT PARENT_ID FROM CUSTOMER_TEMPLATE WHERE DATA_VIEW_ID = $dataViewId AND ID = $folderId");
        
        return ($row) ? $row : array('PARENT_ID' => null);
    }
    
    public function getTemplateFolderModel($folderId) {
        
        $row = $this->db->GetRow("SELECT NAME, PARENT_ID FROM CUSTOMER_TEMPLATE WHERE ID = $folderId");
        return $row;
    }
    
    public function getMetaReportTemplateRowModel($id, $dataViewId) {
        
        $row = $this->db->GetRow("
            SELECT
                MD.META_DATA_NAME, 
                TM.TEMPLATE_ID 
            FROM META_DATA MD 
                LEFT JOIN CUSTOMER_TEMPLATE_MAP TM ON TM.META_DATA_ID = MD.META_DATA_ID 
                LEFT JOIN CUSTOMER_TEMPLATE CT ON CT.ID = TM.TEMPLATE_ID AND CT.DATA_VIEW_ID = $dataViewId 
            WHERE MD.META_DATA_ID = $id" 
        );
        
        return $row;
    }
    
    public function editTemplateFolderSaveModel() {
        
        try {
                        
            $data = array(
                'NAME' => Input::post('name'), 
                'PARENT_ID' => Input::post('parentId')
            );

            $folderId = Input::post('folderId');
            $this->db->AutoExecute('CUSTOMER_TEMPLATE', $data, 'UPDATE', 'ID = '.$folderId);

            $getChildTemplates = $this->getChildReportTemplateListModel(Input::post('metaDataId'), $folderId);

            foreach ($getChildTemplates as $templateRow) {
                $this->db->Execute("DELETE FROM UM_META_PERMISSION WHERE META_DATA_ID = ".$templateRow['META_DATA_ID']." AND BATCH_NUMBER = 'rt'");
                
                if (Input::postCheck('rtUserId')) {
                    
                    $rtUserIds     = Input::post('rtUserId');
                    $rtUserIds     = array_unique($rtUserIds);
                    $currentDate   = Date::currentDate();
                    $sessionUserId = Ue::sessionUserKeyId();
                    
                    foreach ($rtUserIds as $ck => $rtUserId) {
                        if (!empty($rtUserId)) {
                            
                            $dataPermission = array(
                                'PERMISSION_ID'   => getUIDAdd($ck),
                                'META_DATA_ID'    => $templateRow['META_DATA_ID'],
                                'ACTION_ID'       => '300101010000002',
                                'USER_ID'         => $rtUserId,
                                'CREATED_DATE'    => $currentDate, 
                                'CREATED_USER_ID' => $sessionUserId, 
                                'BATCH_NUMBER'    => 'rt'
                            );
                            
                            $this->db->AutoExecute('UM_META_PERMISSION', $dataPermission);
                        }
                    }
                }            
            }

            $this->db->Execute("DELETE FROM UM_META_PERMISSION WHERE META_DATA_ID = ".$folderId." AND BATCH_NUMBER = 'rt'");

            if (Input::postCheck('rtUserId')) {
                $rtUserIds     = Input::post('rtUserId');
                $rtUserIds     = array_unique($rtUserIds);
                $currentDate   = Date::currentDate();
                $sessionUserId = Ue::sessionUserKeyId();
                
                foreach ($rtUserIds as $ck => $rtUserId) {
                    if (!empty($rtUserId)) {
                        
                        $dataPermission = array(
                            'PERMISSION_ID'   => getUIDAdd($ck),
                            'META_DATA_ID'    => $folderId,
                            'ACTION_ID'       => '300101010000002',
                            'USER_ID'         => $rtUserId,
                            'CREATED_DATE'    => $currentDate, 
                            'CREATED_USER_ID' => $sessionUserId, 
                            'BATCH_NUMBER'    => 'rt'
                        );
                        
                        $this->db->AutoExecute('UM_META_PERMISSION', $dataPermission);
                    }
                }            
            }            
            
            return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
            
        } catch (ADODB_Exception $ex) {
            return array('status' => 'error', 'message' => $ex->getMessage());
        }
    }
    
    public function deleteTemplateFolderModel() {
        
        try {
            
            $folderId = Input::post('id');
            
            $this->db->Execute('DELETE FROM CUSTOMER_TEMPLATE_MAP WHERE TEMPLATE_ID = '.$folderId);
            $this->db->Execute('UPDATE CUSTOMER_TEMPLATE SET PARENT_ID = null WHERE PARENT_ID = '.$folderId);
            $this->db->Execute('DELETE FROM CUSTOMER_TEMPLATE WHERE ID = '.$folderId);
            
            return array('status' => 'success', 'message' => Lang::line('msg_delete_success'));
            
        } catch (ADODB_Exception $ex) {
            return array('status' => 'error', 'message' => $ex->getMessage());
        }
    }
    
    public function editTemplateSaveModel() {
        
        try {
            
            $id = Input::post('id');
            $data = array(
                'META_DATA_NAME' => Input::post('name')
            );

            $this->db->AutoExecute('META_DATA', $data, 'UPDATE', 'META_DATA_ID = '.$id);
            
            $this->db->Execute('DELETE FROM CUSTOMER_TEMPLATE_MAP WHERE META_DATA_ID = '.$id);
            
            $dataMap = array(
                'ID'           => getUID(), 
                'TEMPLATE_ID'  => Input::post('templateId'), 
                'META_DATA_ID' => $id
            );
            
            $this->db->AutoExecute('CUSTOMER_TEMPLATE_MAP', $dataMap);
            
            $this->db->Execute("DELETE FROM UM_META_PERMISSION WHERE META_DATA_ID = $id AND BATCH_NUMBER = 'rt'");
            
            if (Input::postCheck('rtUserId')) {
                
                $rtUserIds     = Input::post('rtUserId');
                $currentDate   = Date::currentDate();
                $sessionUserId = Ue::sessionUserKeyId();
                
                foreach ($rtUserIds as $ck => $rtUserId) {
                    if (!empty($rtUserId)) {
                        
                        $dataPermission = array(
                            'PERMISSION_ID'   => getUIDAdd($ck),
                            'META_DATA_ID'    => $id,
                            'ACTION_ID'       => '300101010000002',
                            'USER_ID'         => $rtUserId,
                            'CREATED_DATE'    => $currentDate, 
                            'CREATED_USER_ID' => $sessionUserId, 
                            'BATCH_NUMBER'    => 'rt'
                        );
                        
                        $this->db->AutoExecute('UM_META_PERMISSION', $dataPermission);
                    }
                }
            }
            
            return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
            
        } catch (ADODB_Exception $ex) {
            return array('status' => 'error', 'message' => $ex->getMessage());
        }
    }
    
    public function editTemplateFileSaveModel() {
        
        try {
            
            $metaDataId   = Input::post('id');
            $htmlFilePath = UPLOADPATH . 'report_template/' . $metaDataId . '.html';
            
            $data = array(
                'PAGE_MARGIN_TOP'         => Str::remove_whitespace(Input::post('rtMarginTop')), 
                'PAGE_MARGIN_LEFT'        => Str::remove_whitespace(Input::post('rtMarginLeft')), 
                'PAGE_MARGIN_RIGHT'       => Str::remove_whitespace(Input::post('rtMarginRight')), 
                'PAGE_MARGIN_BOTTOM'      => Str::remove_whitespace(Input::post('rtMarginBottom')), 
                'ARCHIVE_WFM_STATUS_CODE' => Str::remove_whitespace(Input::post('rtArchiveWfmCode'))
            );
            
            if (file_put_contents($htmlFilePath, Input::postNonTags('tempEditor'))) {
                $data['HTML_FILE_PATH'] = $htmlFilePath;
            }

            $this->db->AutoExecute('META_REPORT_TEMPLATE_LINK', $data, 'UPDATE', 'META_DATA_ID = '.$metaDataId);
            
            if (Input::postCheck('tempHeaderEditor')) {
                $this->db->UpdateClob('META_REPORT_TEMPLATE_LINK', 'HTML_HEADER_CONTENT', Input::postWithDoubleSpace('tempHeaderEditor'), 'META_DATA_ID = '.$metaDataId);
            }
            
            if (Input::postCheck('tempFooterEditor')) {
                $this->db->UpdateClob('META_REPORT_TEMPLATE_LINK', 'HTML_FOOTER_CONTENT', Input::postWithDoubleSpace('tempFooterEditor'), 'META_DATA_ID = '.$metaDataId);                
            }
            
            return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
            
        } catch (ADODB_Exception $ex) {
            return array('status' => 'error', 'message' => $ex->getMessage());
        }
    }
    
    public function copyTemplateSaveModel() {
        
        try {
            
            $metaDataId = getUID();
            $sessionUserKeyId = Ue::sessionUserKeyId();
            $currentDate = Date::currentDate('Y-m-d H:i:s');
            $countCode = self::getSequenceReportTemplate();
            $seqCode = $countCode + 1;
            
            $data = array(
                'META_DATA_ID' => $metaDataId,
                'META_DATA_CODE' => 'userReportTemplate_'.$seqCode,
                'META_DATA_NAME' => Input::post('name'),
                'META_TYPE_ID' => Mdmetadata::$reportTemplateMetaTypeId,
                'IS_SYSTEM' => 1,
                'IS_ACTIVE' => 1, 
                'STATUS_ID' => 1, 
                'CREATED_USER_ID' => $sessionUserKeyId,
                'MODIFIED_USER_ID' => $sessionUserKeyId,
                'CREATED_DATE' => $currentDate, 
                'MODIFIED_DATE' => $currentDate 
            );
            $result = $this->db->AutoExecute('META_DATA', $data);
            
            if ($result) {
                
                $dataFolder = array(
                    'ID' => getUID(),
                    'FOLDER_ID' => 1465355267128864, 
                    'META_DATA_ID' => $metaDataId
                );
                $this->db->AutoExecute('META_DATA_FOLDER_MAP', $dataFolder);
                
                $oldMetaDataId = Input::post('id');
                
                $getMeta = self::getReportTemplateByMetaDataId($oldMetaDataId);
                
                if ($getMeta) {
                    
                    $dataLink = array(
                        'ID' => getUID(),
                        'META_DATA_ID' => $metaDataId, 
                        'DATA_MODEL_ID' => $getMeta['DATA_MODEL_ID'], 
                        'GET_MODE' => $getMeta['GET_MODE'], 
                        'DIRECTORY_ID' => $getMeta['DIRECTORY_ID'], 
                        'IS_TABLE_LAYOUT_FIXED' => $getMeta['IS_TABLE_LAYOUT_FIXED'], 
                        'PAGE_MARGIN_TOP' => $getMeta['PAGE_MARGIN_TOP'], 
                        'PAGE_MARGIN_LEFT' => $getMeta['PAGE_MARGIN_LEFT'], 
                        'PAGE_MARGIN_RIGHT' => $getMeta['PAGE_MARGIN_RIGHT'], 
                        'PAGE_MARGIN_BOTTOM' => $getMeta['PAGE_MARGIN_BOTTOM'], 
                        'ARCHIVE_WFM_STATUS_CODE' => $getMeta['ARCHIVE_WFM_STATUS_CODE'], 
                        'CREATED_USER_ID' => $sessionUserKeyId,
                        'MODIFIED_USER_ID' => $sessionUserKeyId,
                        'CREATED_DATE' => $currentDate, 
                        'MODIFIED_DATE' => $currentDate 
                    );
                    
                    $htmlFilePath = $getMeta['HTML_FILE_PATH'];
                    
                    if (file_exists($htmlFilePath)) {
                        
                        $newHtmlFilePath = UPLOADPATH.'report_template/'.$metaDataId.'.html';

                        if (copy($htmlFilePath, $newHtmlFilePath)) {
                            $dataLink['HTML_FILE_PATH'] = $newHtmlFilePath;
                        }
                    }
        
                    $this->db->AutoExecute('META_REPORT_TEMPLATE_LINK', $dataLink);
                    
                    $dataViewId = Input::numeric('metaDataId');
                    
                    $this->load->model('mdobject', 'middleware/models/');
                    $dvRow = $this->model->getDataViewConfigRowModel($dataViewId);

                    $getCriteria = $this->db->GetRow('SELECT CRITERIA FROM META_DM_TEMPLATE_DTL WHERE META_GROUP_LINK_ID = ' . $this->db->Param(0) . ' AND TEMPLATE_META_DATA_ID = ' . $this->db->Param(1), array($dvRow['ID'], $oldMetaDataId));
                    
                    $dataDtlLink = array(
                        'ID' => getUID(), 
                        'META_GROUP_LINK_ID' => $dvRow['ID'],
                        'TEMPLATE_META_DATA_ID' => $metaDataId, 
                        'CRITERIA' => $getCriteria['CRITERIA'],
                        'META_DATA_ID' => $dataViewId
                    );
                    $this->db->AutoExecute('META_DM_TEMPLATE_DTL', $dataDtlLink);
                    
                    if (Input::postCheck('templateId') && Input::isEmpty('templateId') == false) {
                        $dataTemplateMap = array(
                            'ID' => getUID(), 
                            'TEMPLATE_ID' => Input::post('templateId'), 
                            'META_DATA_ID' => $metaDataId
                        );
                        $this->db->AutoExecute('CUSTOMER_TEMPLATE_MAP', $dataTemplateMap);
                    }
                }
            }
            
            return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
            
        } catch (ADODB_Exception $ex) {
            return array('status' => 'error', 'message' => $ex->getMessage());
        }
    }
    
    public function getSequenceReportTemplate() {
        return $this->db->GetOne("SELECT COUNT(META_DATA_ID) AS M_COUNT FROM META_DATA WHERE LOWER(META_DATA_CODE) LIKE 'userreporttemplate_%'");
    }
    
    public function deleteDataViewTemplateModel() {
        
        $id = Input::post('id');
        
        if ($checkPermission = self::checkReportTemplatePermissionModel($id)) {
            
            return $checkPermission;
            
        } else {
            
            try {
            
                $this->db->Execute('DELETE FROM CUSTOMER_TEMPLATE_MAP WHERE META_DATA_ID = '.$id);
                $this->db->Execute('DELETE FROM META_DATA_FOLDER_MAP WHERE META_DATA_ID = '.$id);
                $this->db->Execute('DELETE FROM META_REPORT_TEMPLATE_LINK WHERE META_DATA_ID = '.$id);
                $this->db->Execute('DELETE FROM META_DM_TEMPLATE_DTL WHERE TEMPLATE_META_DATA_ID = '.$id);
                $this->db->Execute('DELETE FROM META_DATA WHERE META_DATA_ID = '.$id);

                return array('status' => 'success', 'message' => Lang::line('msg_delete_success'));

            } catch (ADODB_Exception $ex) {
                return array('status' => 'error', 'message' => $ex->getMessage());
            }
        }
    }
    
    public function getReportTemplateGroupingModel($linkId, $groupPath) {
        
        $data = $this->db->GetAll("
            SELECT
                LOWER(FIELD_PATH) AS FIELD_PATH, 
                GROUP_HEADER, 
                GROUP_FOOTER 
            FROM META_REPORT_TEMPLATE_GROUP 
            WHERE REPORT_TEMPLATE_LINK_ID = ".$this->db->Param(0)." 
                AND FIELD_PATH IS NOT NULL 
                AND LOWER(GROUP_PATH) = ".$this->db->Param(1)." 
            ORDER BY GROUP_ORDER ASC", array($linkId, strtolower($groupPath)) 
        );
        
        $array = array();
        
        if ($data) {
            
            foreach ($data as $k => $row) {
                
                $array[$k]['FIELD_PATH'] = $row['FIELD_PATH'];
                
                $hdrHtmlClean = Str::cleanOut($row['GROUP_HEADER']);
                $hdrHtml = phpQuery::newDocumentHTML($hdrHtmlClean);

                $array[$k]['GROUP_HEADER'] = $hdrHtml['table > tbody']->html();

                $ftrHtmlClean = Str::cleanOut($row['GROUP_FOOTER']);
                $ftrHtml = phpQuery::newDocumentHTML($ftrHtmlClean);

                $array[$k]['GROUP_FOOTER'] = $ftrHtml['table > tbody']->html();
            }
        }
        
        return $array;
    }
    
    public function getWfmNextStatusByRow($metaDataId, $selectedRow) {
        
        $param = array(
            'systemMetaGroupId' => $metaDataId,
            'showQuery' => 0, 
            'ignorePermission' => 1 
        );
        $param = array_merge($param, $selectedRow);
            
        $result = $this->ws->runResponse(self::$gfServiceAddress, 'GET_ROW_WFM_STATUS', $param);
        
        if ($result['status'] == 'success' && isset($result['result'])) {
            return $result['result'];
        }
        return array();
    }
    
    public function getLastModifiedHtmlData($row) {
        
        if (isset($row['id'])) {
            
            $recordId = $row['id'];
            
            $htmlPath = $this->db->GetRow("
                SELECT 
                    EC.THUMB_PHYSICAL_PATH 
                FROM ECM_CONTENT_MAP CM 
                    INNER JOIN ECM_CONTENT EC ON EC.CONTENT_ID = CM.CONTENT_ID 
                WHERE CM.RECORD_ID = $recordId 
                    AND EC.THUMB_PHYSICAL_PATH IS NOT NULL 
                ORDER BY CM.CREATED_DATE DESC");
            
            if (isset($htmlPath['THUMB_PHYSICAL_PATH']) && file_exists($htmlPath['THUMB_PHYSICAL_PATH'])) {
                return file_get_contents($htmlPath['THUMB_PHYSICAL_PATH']);
            }
        }
        
        return null;
    }
    
    public function checkReportTemplatePermissionModel($id) {
        
        $idPh = $this->db->Param(0);
        $bindVars = array($this->db->addQ($id));
        
        $count = $this->db->GetOne("SELECT COUNT(PERMISSION_ID) FROM UM_META_PERMISSION WHERE META_DATA_ID = $idPh AND BATCH_NUMBER = 'rt'", $bindVars);
        
        if ($count) {
            
            $sessionUserIdPh = $this->db->Param(1);
            $bindVars[] = $this->db->addQ(Ue::sessionUserId());
            
            $userCount = $this->db->GetOne("SELECT COUNT(PERMISSION_ID) FROM UM_META_PERMISSION WHERE META_DATA_ID = $idPh AND USER_ID = $sessionUserIdPh AND BATCH_NUMBER = 'rt'", $bindVars);
            
            if (!$userCount) {
                return array('status' => 'error', 'message' => 'Та засах эрхгүй байна!');
            }
        } 
        
        return null;
    }
    
    public function getReportTemplateUserPermissionModel($id) {
        
        $data = $this->db->GetAll("
            SELECT 
                VW.USER_ID, 
                VW.USERNAME, 
                VW.LAST_NAME, 
                VW.FIRST_NAME 
            FROM UM_META_PERMISSION UM 
                INNER JOIN VW_USER VW ON VW.USER_ID = UM.USER_ID 
            WHERE UM.META_DATA_ID = ".$this->db->Param(0)." 
                AND UM.BATCH_NUMBER = 'rt' 
            ORDER BY VW.FIRST_NAME ASC", array($id));
        
        return $data;
    }
    
    public function getReportTemplateFolderUserPermissionModel($folderId, $metaDataId) {
        
        $data = $this->db->GetAll("
            SELECT 
                VW.USER_ID, 
                VW.USERNAME, 
                VW.LAST_NAME, 
                VW.FIRST_NAME,
                MD.META_DATA_NAME 
            FROM UM_META_PERMISSION UM 
                INNER JOIN VW_USER VW ON VW.USER_ID = UM.USER_ID 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = UM.META_DATA_ID 
            WHERE UM.META_DATA_ID IN (
                SELECT
                    DISTINCT META_DATA_ID
                FROM
                (
                    (
                        SELECT
                            MD.META_DATA_ID,
                            MD.META_DATA_CODE,
                            MD.META_DATA_NAME 
                        FROM META_REPORT_TEMPLATE_LINK REP
                            INNER JOIN META_DM_TEMPLATE_DTL DTL ON REP.META_DATA_ID = DTL.TEMPLATE_META_DATA_ID 
                            INNER JOIN META_DATA MD ON MD.META_DATA_ID = REP.META_DATA_ID 
                            INNER JOIN CUSTOMER_TEMPLATE_MAP TM ON TM.META_DATA_ID = MD.META_DATA_ID  
                        WHERE REP.DATA_MODEL_ID = ".$this->db->Param(0)." 
                            AND TM.TEMPLATE_ID = ".$this->db->Param(1)."  
                    ) 
                    
                    UNION ALL
                
                    (
                        SELECT
                            DTL.TEMPLATE_META_DATA_ID AS META_DATA_ID,
                            LMD.META_DATA_CODE, 
                            LMD.META_DATA_NAME 
                        FROM META_DM_TEMPLATE_DTL DTL
                            INNER JOIN META_GROUP_LINK LINK ON LINK.ID = DTL.META_GROUP_LINK_ID
                            INNER JOIN META_DATA MD ON MD.META_DATA_ID = LINK.META_DATA_ID
                            INNER JOIN META_REPORT_TEMPLATE_LINK REP ON REP.META_DATA_ID = DTL.TEMPLATE_META_DATA_ID
                            INNER JOIN META_DATA LMD ON LMD.META_DATA_ID = REP.META_DATA_ID 
                            INNER JOIN CUSTOMER_TEMPLATE_MAP TM ON TM.META_DATA_ID = DTL.TEMPLATE_META_DATA_ID 
                        WHERE MD.META_DATA_ID = ".$this->db->Param(0)." 
                            AND TM.TEMPLATE_ID = ".$this->db->Param(1)."  
                    )
                ) REP_RESULT                 
            ) 
            AND UM.BATCH_NUMBER = 'rt' 
            ORDER BY VW.FIRST_NAME ASC", array($metaDataId, $folderId));
        
        return $data;
    }
    
    public function getShowColumnsByTemplateModel($groupPath, $dataModelId, $isGroup = false) {
        
        $dataModelIdPh = $this->db->Param(0);
        $groupPathPh   = $this->db->Param(1);
        
        $bindVars = array($dataModelId, $groupPath);
        
        $data = $this->db->GetAll("
            SELECT 
                LABEL_NAME, 
                LOWER(PARAM_NAME) AS PARAM_NAME, 
                DATA_TYPE, 
                ".$this->db->IfNull('COLUMN_WIDTH', "'80px'")." AS COLUMN_WIDTH, 
                BODY_ALIGN, 
                COLUMN_AGGREGATE, 
                FONT_SIZE, 
                IS_MERGE 
            FROM META_GROUP_CONFIG 
            WHERE MAIN_META_DATA_ID = $dataModelIdPh 
                AND IS_SHOW = 1 
                ".($isGroup ? '' : "AND DATA_TYPE <> 'group'")." 
                AND PARENT_ID = ( 
                    SELECT 
                        ID 
                    FROM META_GROUP_CONFIG 
                    WHERE MAIN_META_DATA_ID = $dataModelIdPh 
                        AND LOWER(FIELD_PATH) = $groupPathPh 
                )
            ORDER BY DISPLAY_ORDER ASC", $bindVars);
        
        return $data;
    }
    
    public function changeUserPrintOptionModel() {
        
        $crmUserId = Session::get(SESSION_PREFIX . 'crmuserid');
        $userKeyId = $crmUserId ? $crmUserId : Ue::sessionUserKeyId();
        
        if (!empty($userKeyId)) {
            
            $dataViewId = Input::numeric('metaDataId');
            
            if ($dataViewId) {
                
                $configStr = $this->db->GetRow("SELECT ID, CONFIG_STR FROM META_GROUP_PRINT_USER WHERE DV_META_DATA_ID = ".$this->db->Param(0)." AND USER_ID = ".$this->db->Param(1), array($dataViewId, $userKeyId));
                
                if (isset($configStr['CONFIG_STR'])) {
                    $configJson = str_replace('"isSettingsDialog":"1",', '"isSettingsDialog":"0",', $configStr['CONFIG_STR']);
                    $this->db->UpdateClob('META_GROUP_PRINT_USER', 'CONFIG_STR', $configJson, 'ID = '.$configStr['ID']);
                }
            }
        }
        
        return array('status' => 'success');
    }
    
    public function getSecondPrintTemplateModel($templateMetaId, $dataRow) {
        
        $data = $this->db->GetAll("
            SELECT 
                DTL.META_DATA_ID,  
                LMD.META_DATA_NAME, 
                DTL.CRITERIA, 
                DTL.IS_DEFAULT 
            FROM META_DM_TEMPLATE_DTL DTL 
                INNER JOIN META_REPORT_TEMPLATE_LINK REP ON REP.META_DATA_ID = DTL.META_DATA_ID 
                INNER JOIN META_DATA LMD ON LMD.META_DATA_ID = REP.META_DATA_ID 
            WHERE DTL.SRC_META_DATA_ID = ".$this->db->Param(0)." 
                AND DTL.META_DATA_ID IS NOT NULL 
            GROUP BY 
                DTL.META_DATA_ID,  
                LMD.META_DATA_NAME, 
                DTL.CRITERIA, 
                DTL.IS_DEFAULT", array($templateMetaId));
        
        foreach ($data as $row) {
            
            /*criteria шалгана*/
            
            $result[] = array(
                'id' => $row['META_DATA_ID'], 
                'name' => $row['META_DATA_NAME']
            );
        }
        
        return $result;
    } 

    public function getReportIdModel($dvId, $criteria = null) {
        
        $param = array(
            'systemMetaGroupId' => $dvId,
            'ignorePermission' => 1, 
            'showReport' => 1,
            'saveCriteria' => 1
        );

        if ($criteria) {
            $param = array_merge($param, $criteria);
        }

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($data['status'] == 'success' && isset($data['result'])) {
            return array('status' => 'success', 'reportId' => $data['result']);
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
        }
    }    

}