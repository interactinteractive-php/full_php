<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdexpression_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function getMetaTypeCode($processId, $fieldPath, $isProcess = true) {

        $fieldPathLower = strtolower($fieldPath);

        if ($isProcess) {

            if (isset(Mdexpression::$multiPathConfig[$fieldPathLower])) {

                $row = Mdexpression::$multiPathConfig[$fieldPathLower];

                return array(
                    'type' => $row['META_TYPE_CODE'], 
                    'CHOOSE_TYPE' => $row['CHOOSE_TYPE'],
                    'LOOKUP_TYPE' => $row['LOOKUP_TYPE'], 
                    'LOOKUP_META_DATA_ID' => $row['LOOKUP_META_DATA_ID'], 
                    'parentId' => $row['PARENT_ID'], 
                    'sidebarName' => $row['SIDEBAR_NAME'], 
                    'isShow' => $row['IS_SHOW'], 
                    'IS_TRANSLATE' => $row['IS_TRANSLATE'], 
                    'JSON_CONFIG' => $row['JSON_CONFIG'], 
                    'ABILITY_TOGGLE' => $row['ABILITY_TOGGLE']
                );
            }

            $row = $this->db->GetRow("
                SELECT 
                    PAL.LOOKUP_META_DATA_ID, 
                    PAL.DATA_TYPE AS META_TYPE_CODE, 
                    PAL.PARENT_ID, 
                    PAL.SIDEBAR_NAME, 
                    CASE 
                        WHEN GC.IS_TRANSLATE = 1 AND GC.COLUMN_NAME IS NOT NULL 
                        THEN 1 
                    ELSE 0 END AS IS_TRANSLATE, 
                    PAL.JSON_CONFIG, 
                    ".$this->db->IfNull('CF.IS_SHOW', 'PAL.IS_SHOW')." AS IS_SHOW, 
                    ".$this->db->IfNull('CF.CHOOSE_TYPE', 'PAL.CHOOSE_TYPE')." AS CHOOSE_TYPE, 
                    ".$this->db->IfNull('CF.LOOKUP_TYPE', 'PAL.LOOKUP_TYPE')." AS LOOKUP_TYPE, 
                    CF.ABILITY_TOGGLE 
                FROM META_PROCESS_PARAM_ATTR_LINK PAL 
                    LEFT JOIN CUSTOMER_DV_FIELD CF ON CF.META_DATA_ID = PAL.PROCESS_META_DATA_ID 
                        AND CF.FIELD_PATH = PAL.PARAM_REAL_PATH 
                    INNER JOIN META_BUSINESS_PROCESS_LINK BP ON BP.META_DATA_ID = PAL.PROCESS_META_DATA_ID 
                    LEFT JOIN META_GROUP_CONFIG GC ON GC.MAIN_META_DATA_ID = BP.SYSTEM_META_GROUP_ID 
                        AND LOWER(GC.FIELD_PATH) = LOWER(PAL.PARAM_REAL_PATH) 
                WHERE PAL.PROCESS_META_DATA_ID = ".$this->db->Param(0)." 
                    AND PAL.IS_INPUT = 1  
                    AND LOWER(PAL.PARAM_REAL_PATH) = ".$this->db->Param(1), 
                array($processId, $fieldPathLower)
            );

        } else {
            $row = $this->db->GetRow("
                SELECT 
                    CHOOSE_TYPE, 
                    LOOKUP_TYPE, 
                    LOOKUP_META_DATA_ID, 
                    DATA_TYPE AS META_TYPE_CODE, 
                    PARENT_ID, 
                    SIDEBAR_NAME, 
                    IS_SHOW, 
                    0 AS IS_TRANSLATE,
                    NULL AS JSON_CONFIG, 
                    NULL AS ABILITY_TOGGLE 
                FROM META_GROUP_CONFIG 
                WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                    AND LOWER(FIELD_PATH) = ".$this->db->Param(1), 
                array($processId, $fieldPathLower) 
            ); 
        }

        if ($row) {
            
            if ($row['JSON_CONFIG']) {
                $row['JSON_CONFIG'] = @json_decode($row['JSON_CONFIG'], true);
            } else {
                $row['JSON_CONFIG'] = null;
            }
            
            return array(
                'type' => strtolower($row['META_TYPE_CODE']), 
                'CHOOSE_TYPE' => $row['CHOOSE_TYPE'],
                'LOOKUP_TYPE' => $row['LOOKUP_TYPE'], 
                'LOOKUP_META_DATA_ID' => $row['LOOKUP_META_DATA_ID'], 
                'parentId' => $row['PARENT_ID'], 
                'sidebarName' => $row['SIDEBAR_NAME'], 
                'isShow' => $row['IS_SHOW'], 
                'IS_TRANSLATE' => $row['IS_TRANSLATE'], 
                'JSON_CONFIG' => $row['JSON_CONFIG'], 
                'ABILITY_TOGGLE' => $row['ABILITY_TOGGLE']
            );
        }
        
        return array(
            'type' => 'string', 
            'CHOOSE_TYPE' => '', 
            'LOOKUP_TYPE' => '', 
            'LOOKUP_META_DATA_ID' => '', 
            'parentId' => '', 
            'sidebarName' => '', 
            'isShow' => '', 
            'IS_TRANSLATE' => '', 
            'JSON_CONFIG' => '', 
            'ABILITY_TOGGLE' => ''
        );
    }

    public function setMultiPathConfigModel($processId) {
        
        $processIdPh = $this->db->Param(0);
        $bindVars = array($this->db->addQ($processId));
            
        if (Mdexpression::$isFromMetaGroup) {
            
            $data = $this->db->GetAll("
                SELECT 
                    CHOOSE_TYPE, 
                    LOOKUP_TYPE, 
                    LOOKUP_META_DATA_ID, 
                    DATA_TYPE AS META_TYPE_CODE, 
                    PARENT_ID, 
                    SIDEBAR_NAME, 
                    IS_SHOW, 
                    LOWER(FIELD_PATH) AS PARAM_REAL_PATH, 
                    0 AS IS_TRANSLATE, 
                    NULL AS JSON_CONFIG, 
                    NULL AS ABILITY_TOGGLE 
                FROM META_GROUP_CONFIG     
                WHERE MAIN_META_DATA_ID = $processIdPh 
                    AND DATA_TYPE IS NOT NULL", $bindVars); 
            
        } else {
            
            $data = $this->db->GetAll("
                SELECT 
                    PAL.LOOKUP_META_DATA_ID, 
                    PAL.DATA_TYPE AS META_TYPE_CODE, 
                    PAL.PARENT_ID, 
                    PAL.SIDEBAR_NAME, 
                    PAL.PARAM_REAL_PATH, 
                    CASE 
                        WHEN GC.IS_TRANSLATE = 1 AND GC.COLUMN_NAME IS NOT NULL  
                        THEN 1 
                    ELSE 0 END AS IS_TRANSLATE, 
                    PAL.JSON_CONFIG, 
                    ".$this->db->IfNull('CF.IS_SHOW', 'PAL.IS_SHOW')." AS IS_SHOW,  
                    ".$this->db->IfNull('CF.CHOOSE_TYPE', 'PAL.CHOOSE_TYPE')." AS CHOOSE_TYPE, 
                    ".$this->db->IfNull('CF.LOOKUP_TYPE', 'PAL.LOOKUP_TYPE')." AS LOOKUP_TYPE, 
                    CF.ABILITY_TOGGLE 
                FROM META_PROCESS_PARAM_ATTR_LINK PAL 
                    LEFT JOIN CUSTOMER_DV_FIELD CF ON CF.META_DATA_ID = PAL.PROCESS_META_DATA_ID 
                        AND CF.FIELD_PATH = PAL.PARAM_REAL_PATH 
                    INNER JOIN META_BUSINESS_PROCESS_LINK BP ON BP.META_DATA_ID = PAL.PROCESS_META_DATA_ID 
                    LEFT JOIN META_GROUP_CONFIG GC ON GC.MAIN_META_DATA_ID = BP.SYSTEM_META_GROUP_ID 
                        AND LOWER(GC.FIELD_PATH) = LOWER(PAL.PARAM_REAL_PATH) 
                WHERE PAL.PROCESS_META_DATA_ID = $processIdPh 
                    AND PAL.IS_INPUT = 1", $bindVars); 
        }
        
        $array = array();

        if ($data) {
            foreach ($data as $row) {
                
                if ($row['JSON_CONFIG']) {
                    $row['JSON_CONFIG'] = @json_decode($row['JSON_CONFIG'], true);
                } else {
                    $row['JSON_CONFIG'] = null;
                }
                
                $paramRealPath = $row['PARAM_REAL_PATH'];
                $paramRealPathLower = strtolower($paramRealPath);
                
                $row['PARAM_REAL_PATH'] = $paramRealPathLower;
                $array[$row['PARAM_REAL_PATH']] = $row;
                
                if ($row['ABILITY_TOGGLE'] == 'disable' || $row['ABILITY_TOGGLE'] == 'enable') {
                    
                    if (strpos($paramRealPath, '.') === false) {
                        Mdexpression::$enableDisable[$row['ABILITY_TOGGLE']]['header'][] = array('fullPath' => $paramRealPath);
                    } else {
                        $paramRealPathArr = explode('.', $paramRealPath);
                        $groupPath = $paramRealPathArr[0];
                        Mdexpression::$enableDisable[$row['ABILITY_TOGGLE']]['detail'][] = array('groupPath' => $groupPath, 'fullPath' => $paramRealPath);
                    }
                }
            }
        }

        return $array;
    }

    public function getProcessIdByCodeModel($code) {
        return $this->db->GetOne("SELECT META_DATA_ID FROM META_DATA WHERE META_TYPE_ID = 200101010000011 AND LOWER(META_DATA_CODE) = ".$this->db->Param(0), array($code));
    }
    
    public function getMetaGroupIdByCodeModel($code) {
        return $this->db->GetOne("SELECT META_DATA_ID FROM META_DATA WHERE META_TYPE_ID = 200101010000016 AND LOWER(META_DATA_CODE) = ".$this->db->Param(0), array($code));
    }
    
    public function getWorkspaceIdByCodeModel($code) {
        return $this->db->GetRow("SELECT META_DATA_ID, META_DATA_NAME FROM META_DATA WHERE META_TYPE_ID = 200101010000034 AND LOWER(META_DATA_CODE) = ".$this->db->Param(0), array($code));
    }
    
    public function getStatementIdByCodeModel($code) {
        return $this->db->GetOne("SELECT META_DATA_ID FROM META_DATA WHERE META_TYPE_ID = 200101010000035 AND LOWER(META_DATA_CODE) = ".$this->db->Param(0), array($code));
    }
    
    public function getKpiTempIdByCodeModel($code) {
        return $this->db->GetOne("SELECT ID FROM KPI_TEMPLATE WHERE LOWER(CODE) = ".$this->db->Param(0), array($code));
    }
    
    public function getKpiIndicatorIdByCodeModel($code) {
        return $this->db->GetOne("SELECT ID FROM KPI_INDICATOR WHERE LOWER(CODE) = ".$this->db->Param(0), array($code));
    }
    
    public function getCacheExpressionModel($processId) {
        
        $cache = phpFastCache();
        $cacheExpression = $cache->get('processFullExpressionCache_' . $processId);
        
        if ($cacheExpression == null) {
            
            $processIdPh = $this->db->Param(0);
            $bindVars = array($processId);
            
            $data = $this->db->GetAll("
                SELECT 
                    LOWER(CV.RUN_MODE) AS RUN_MODE, 
                    LOWER(CV.GROUP_PATH) AS GROUP_PATH, 
                    LOWER(CV.CODE) AS CODE, 
                    CV.EXPRESSION_STRING 
                FROM META_BUSINESS_PROCESS_LINK PL 
                    INNER JOIN META_BP_EXPRESSION_DTL ED ON ED.BP_LINK_ID = PL.ID 
                    INNER JOIN CUSTOMER_BP_EXP_CONFIG EX ON EX.EXP_DTL_ID = ED.ID 
                    INNER JOIN META_BP_EXP_CACHE_VERSION CV ON CV.VERSION_ID = ED.ID 
                WHERE PL.META_DATA_ID = $processIdPh 
                    AND EX.IS_DEFAULT = 1", $bindVars);

            if (!$data) {

                $data = $this->db->GetAll("
                    SELECT 
                        LOWER(EC.RUN_MODE) AS RUN_MODE, 
                        LOWER(EC.GROUP_PATH) AS GROUP_PATH, 
                        LOWER(EC.CODE) AS CODE, 
                        EC.EXPRESSION_STRING 
                    FROM META_BP_EXP_CACHE EC 
                        INNER JOIN META_BUSINESS_PROCESS_LINK PL ON PL.ID = EC.BP_LINK_ID 
                    WHERE PL.META_DATA_ID = $processIdPh", $bindVars);
            } 
            
            $cacheExpression = array();
            
            if ($data) {

                foreach ($data as $row) {
                    
                    $expression = (new Mdexpression())->processCacheExpression($row['EXPRESSION_STRING'], $processId, $row['GROUP_PATH'].'.', $row['RUN_MODE']);
                    $expression = html_entity_decode($expression);
                    
                    if ($row['RUN_MODE'] == 'function') {
                        
                        $cacheExpression[$row['RUN_MODE'].'_'.$row['CODE']] = $expression;
                        
                    } elseif ($row['RUN_MODE'] == 'load_first' || $row['RUN_MODE'] == 'add_row' || $row['RUN_MODE'] == 'add_multi') {
                        
                        $cacheExpression[$row['RUN_MODE'].'_'.$row['GROUP_PATH']] = $expression;
                        
                    } elseif ($row['RUN_MODE'] == 'before_save') {
                        
                        $cacheExpression[$row['RUN_MODE']] = $expression;
                        
                    } elseif ($row['RUN_MODE'] == 'before_save_rows') {
                        
                        $cacheExpression[$row['RUN_MODE']][$row['GROUP_PATH']] = $expression;
                    } 
                    
                }
            }
            
            $cache->set('processFullExpressionCache_' . $processId, $cacheExpression, Mdwebservice::$expressionCacheTime);
        }
        
        return $cacheExpression;
    }
    
    public function getCacheGroupPathByExpCodeModel($processId, $cacheExpCode) {
        
        $processIdPh    = $this->db->Param(0);
        $cacheExpCodePh = $this->db->Param(1);

        $bindVars = array($this->db->addQ($processId), $this->db->addQ($cacheExpCode));
            
        $groupPath = $this->db->GetOne("
            SELECT 
                CV.GROUP_PATH 
            FROM META_BUSINESS_PROCESS_LINK PL 
                INNER JOIN META_BP_EXPRESSION_DTL ED ON ED.BP_LINK_ID = PL.ID 
                INNER JOIN CUSTOMER_BP_EXP_CONFIG EX ON EX.EXP_DTL_ID = ED.ID 
                INNER JOIN META_BP_EXP_CACHE_VERSION CV ON CV.VERSION_ID = ED.ID 
            WHERE PL.META_DATA_ID = $processIdPh 
                AND EX.IS_DEFAULT = 1 
                AND CV.RUN_MODE = 'function' 
                AND LOWER(CV.CODE) = $cacheExpCodePh", $bindVars);
        
        if (!$groupPath) {

            $groupPath = $this->db->GetOne("
                SELECT 
                    EC.GROUP_PATH 
                FROM META_BP_EXP_CACHE EC 
                    INNER JOIN META_BUSINESS_PROCESS_LINK PL ON PL.ID = EC.BP_LINK_ID 
                WHERE PL.META_DATA_ID = $processIdPh 
                    AND EC.RUN_MODE = 'function' 
                    AND LOWER(EC.CODE) = $cacheExpCodePh", $bindVars);
        } 
            
        return $groupPath;
    }

    public function microRunDbQueryModel($fncName, $fncParam) {
        return $this->db->GetOne("SELECT TEXT_VALUE FROM TABLE($fncName('$fncParam'))");
    }    

    public function microRunDbProcedureModel($fncName, $fncParam) {
        try {
            $procedure = $this->db->PrepareSP('BEGIN '.$fncName.'(:P_JSON); END;');

            $this->db->InParameter($procedure, $fncParam, 'P_JSON');                        
            $this->db->Execute($procedure);

            return true;            
        } catch (ADODB_Exception $ex) {
            dd($ex);
            return false;
        }        
    }    

}