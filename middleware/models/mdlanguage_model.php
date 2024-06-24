<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdlanguage_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function getGlobeCountModel() {
        return $this->db->GetOne("SELECT COUNT(CODE) AS ROW_COUNT FROM GLOBE_DICTIONARY");
    }

    public function getActiveLanguageModel() {
        
        $data = $this->db->GetAll("
            SELECT 
                LANGUAGE_CODE, 
                SHORT_CODE, 
                LANGUAGE_NAME 
            FROM REF_LANGUAGE 
            WHERE IS_ACTIVE = 1 
                AND SHORT_CODE IS NOT NULL 
            ORDER BY DISPLAY_ORDER ASC");
        
        $this->load->model('mdupgrade', 'middleware/models/');
        
        $checkFields = $this->model->getObjectFields('GLOBE_DICTIONARY');
        $arr = array();
        
        foreach ($data as $row) {
            foreach ($checkFields as $c => $checkField) {
                if ($checkField['name'] == strtoupper($row['LANGUAGE_CODE'])) {
                    $arr[] = $row;
                    unset($checkFields[$c]);
                    break;
                }
            }
        }
        
        return $arr;
    }

    public function getLanguageTextByColumnModel($columnName) {
        return $this->db->GetAll("SELECT LOWER(CODE) AS CODE, TRIM($columnName) AS GLOBE_TEXT FROM GLOBE_DICTIONARY WHERE $columnName IS NOT NULL OR $columnName != ''");
    }

    public function generateLanguageFileModel() {
        
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        try {
            
            ini_set('default_socket_timeout', 2);
                
            $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'reload_language');
            
            $langSuffix = Lang::getSuffix();
            $activeLanguage = self::getActiveLanguageModel();
            $uid = getUID();
        
            foreach ($activeLanguage as $lang) {

                $langDir = 'lang/'.$lang['SHORT_CODE'];

                $path = realpath($langDir);
                
                if ($path == false && !is_dir($path)) {
                    mkdir($langDir, 0777);
                }
                
                $langFile = $langDir.'/main_lang_'.$uid.'.ini';
                $langJSFile = $langDir.'/main_lang_'.$uid.'.js';

                if (file_exists($langFile) && !is_writable($langFile)) {
                    chmod($langFile, 0777);
                }
                
                if (file_exists($langJSFile) && !is_writable($langJSFile)) {
                    chmod($langJSFile, 0777);
                }

                $fp = fopen($langFile, 'w');
                $fpjs = fopen($langJSFile, 'w');

                $content = '';
                $contentjs = 'var jsGlobeDictionary = {';

                $languageTextData = self::getLanguageTextByColumnModel($lang['LANGUAGE_CODE']);

                foreach ($languageTextData as $text) {
                    
                    $code      = str_replace("'", '', $text['CODE']);
                    $code      = str_replace("\n", ' ', $code);
                    $globeText = str_replace("\n", ' ', Str::doubleQuoteToHtmlChar($text['GLOBE_TEXT']));
                    
                    $content .= 'lang[\''.$code.'\']='.$globeText."\n";
                    $contentjs .= '\''.$code.'\':"'.$globeText.'",';
                }
                
                $contentjs .= '};';

                fwrite($fp, $content);
                fwrite($fpjs, $contentjs);
                fclose($fp);
                fclose($fpjs);
                
                if (file_exists($langDir.'/main_lang'.$langSuffix.'.ini')) {
                    unlink($langDir.'/main_lang'.$langSuffix.'.ini');
                    unlink($langDir.'/main_lang'.$langSuffix.'.js');
                }
                
                rename($langFile, $langDir.'/main_lang'.$langSuffix.'.ini');
                rename($langJSFile, $langDir.'/main_lang'.$langSuffix.'.js');
            } 

            return array('status' => 'success', 'message' => Lang::line('msg_save_success'));

        } catch (Exception $ex) {

            return array('status' => 'error', 'message' => $ex->getMessage());
        }
    }

    public function getLanguageMainDataGridModel($pagination = true) {

        $page = Input::post('page', 1);
        $rows = Input::post('rows', 10);
        $offset = ($page - 1) * $rows;
        $subCondition = '';
        $result = array();

        if (Input::postCheck('filterRules')) {

            $filterRules = json_decode(Str::cp1251_utf8($_POST['filterRules']), true);

            foreach ($filterRules as $rule) {

                $field = $rule['field'];
                $value = Input::param(Str::lower($rule['value']));

                if (!empty($value)) {
                    $subCondition .= " AND (LOWER($field) LIKE '%$value%')";
                }
            }

        } elseif ($page == 1 && Input::isEmpty('code') == false) {

            $checkCode = $this->db->GetRow("SELECT CODE FROM GLOBE_DICTIONARY WHERE LOWER(CODE) = ".$this->db->Param(0), array(Str::lower(Input::post('code'))));

            if ($checkCode) {
                $subCondition .= " AND LOWER(CODE) = '".Str::lower(Input::post('code'))."'";
            }
        }

        $sortField = 'CODE';
        $sortOrder = 'ASC';

        if (Input::postCheck('sort') && Input::postCheck('order')) {
            $sortField = Input::post('sort');
            $sortOrder = Input::post('order');
        }

        $selectCount = "
            SELECT 
                COUNT(GD.ID) AS ROW_COUNT
            FROM GLOBE_DICTIONARY GD
            WHERE GD.CODE IS NOT NULL $subCondition";

        $selectList = "
            SELECT 
                GD.ID, 
                GD.CODE, 
                GD.MONGOLIAN, 
                GD.ENGLISH, 
                GD.RUSSIAN, 
                GT.TYPE_NAME, 
                GG.GROUP_NAME 
            FROM GLOBE_DICTIONARY GD 
                LEFT JOIN GLOBE_TYPE GT ON GT.TYPE_ID = GD.TYPE_ID 
                LEFT JOIN GLOBE_GROUP GG ON GD.GROUP_ID = GG.GROUP_ID 
            WHERE GD.CODE IS NOT NULL $subCondition 
                ORDER BY $sortField $sortOrder";

        $rowCount = $this->db->GetRow($selectCount);
        
        $result['total'] = $rowCount['ROW_COUNT'];
        $result['rows'] = array();

        $rs = $this->db->SelectLimit($selectList, $rows, $offset);

        if (isset($rs->_array)) {
            $result['rows'] = $rs->_array;
        }

        return $result;
    }
    
    public function getMetaDictionaryModel() {
        
        $this->load->model('mdmetadata', 'middleware/models/');
        
        $response   = array('status' => 'error', 'message' => 'not metadata');
        $metaDataId = Input::numeric('metaDataId');
        
        if ($metaDataId && $metaRow = $this->model->getMetaDataModel($metaDataId)) {
            
            $metaTypeId       = $metaRow['META_TYPE_ID'];
            $metaDataCode     = $metaRow['META_DATA_CODE'];
            $langList         = self::getActiveLanguageModel();
            $selectLangColumn = '';
            
            foreach ($langList as $langRow) {
                
                if (strtolower($langRow['LANGUAGE_CODE']) != 'mongolian') {
                    $selectLangColumn .= 'GL.' . $langRow['LANGUAGE_CODE'] . ', ';
                }
            }
                    
            $metaDataIdPh = $this->db->Param(0);
            
            if ($metaTypeId == Mdmetadata::$businessProcessMetaTypeId) {
                
                $sql = "
                SELECT 
                    GL.GROUP_NAME, 
                    GL.PATH_NAME, 
                    GL.LABEL_NAME, 
                    GL.GLOBE_CODE, 
                    $selectLangColumn 
                    GL.MONGOLIAN  
                FROM (
                    SELECT 
                        'processName' AS GROUP_NAME, 
                        'processName' AS PATH_NAME, 
                        PL.PROCESS_NAME AS LABEL_NAME, 
                        GLL.CODE AS GLOBE_CODE, 
                        ".$this->db->IfNull('GL.MONGOLIAN', $this->db->IfNull('PL.PROCESS_NAME', 'MD.META_DATA_NAME'))." AS MONGOLIAN, 
                        $selectLangColumn     
                        1 AS FIRST_ORDER, 
                        1 AS SECOND_ORDER  
                    FROM META_BUSINESS_PROCESS_LINK PL 
                        LEFT JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.PROCESS_NAME) 
                        LEFT JOIN GLOBE_DICTIONARY GLL ON GLL.META_DATA_ID = PL.META_DATA_ID 
                            AND LOWER(GLL.CODE) = LOWER(PL.PROCESS_NAME) 
                        LEFT JOIN META_DATA MD ON MD.META_DATA_ID = PL.META_DATA_ID   
                    WHERE PL.META_DATA_ID = $metaDataIdPh 

                    UNION ALL 

                    SELECT 
                        'tabName' AS GROUP_NAME, 
                        'tabName' AS PATH_NAME, 
                        PL.TAB_NAME AS LABEL_NAME, 
                        GLL.CODE AS GLOBE_CODE, 
                        ".$this->db->IfNull('GL.MONGOLIAN', 'PL.TAB_NAME')." AS MONGOLIAN, 
                        $selectLangColumn     
                        2 AS FIRST_ORDER, 
                        2 AS SECOND_ORDER 
                    FROM META_PROCESS_PARAM_ATTR_LINK PL
                        LEFT JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.TAB_NAME) 
                        LEFT JOIN GLOBE_DICTIONARY GLL ON GLL.META_DATA_ID = PL.PROCESS_META_DATA_ID 
                            AND LOWER(GLL.CODE) = LOWER(PL.TAB_NAME) 
                    WHERE PL.PROCESS_META_DATA_ID = $metaDataIdPh 
                        AND PL.IS_INPUT = 1 
                        AND PL.IS_SHOW = 1 
                        AND PL.TAB_NAME IS NOT NULL 
                    GROUP BY 
                        PL.TAB_NAME, 
                        GL.MONGOLIAN, 
                        $selectLangColumn  
                        GLL.CODE    

                    UNION ALL 
                    
                    SELECT 
                        'sidebarName' AS GROUP_NAME, 
                        'sidebarName' AS PATH_NAME, 
                        PL.SIDEBAR_NAME AS LABEL_NAME,  
                        GLL.CODE AS GLOBE_CODE, 
                        ".$this->db->IfNull('GL.MONGOLIAN', 'PL.SIDEBAR_NAME')." AS MONGOLIAN, 
                        $selectLangColumn  
                        3 AS FIRST_ORDER, 
                        3 AS SECOND_ORDER  
                    FROM META_PROCESS_PARAM_ATTR_LINK PL 
                        LEFT JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.SIDEBAR_NAME) 
                        LEFT JOIN GLOBE_DICTIONARY GLL ON GLL.META_DATA_ID = PL.PROCESS_META_DATA_ID 
                            AND LOWER(GLL.CODE) = LOWER(PL.SIDEBAR_NAME) 
                    WHERE PL.PROCESS_META_DATA_ID = $metaDataIdPh 
                        AND PL.IS_INPUT = 1 
                        AND PL.IS_SHOW = 1 
                        AND PL.SIDEBAR_NAME IS NOT NULL 
                    GROUP BY 
                        PL.SIDEBAR_NAME, 
                        GL.MONGOLIAN, 
                        $selectLangColumn  
                        GLL.CODE        
                    
                    UNION ALL 

                    SELECT 
                        'parameter' AS GROUP_NAME, 
                        PL.PARAM_REAL_PATH AS PATH_NAME, 
                        PL.LABEL_NAME, 
                        GLL.CODE AS GLOBE_CODE, 
                        ".$this->db->IfNull('GL.MONGOLIAN', 'PL.LABEL_NAME')." AS MONGOLIAN, 
                        $selectLangColumn  
                        4 AS FIRST_ORDER, 
                        PL.ORDER_NUMBER AS SECOND_ORDER  
                    FROM META_PROCESS_PARAM_ATTR_LINK PL 
                        LEFT JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.LABEL_NAME) 
                        LEFT JOIN GLOBE_DICTIONARY GLL ON GLL.META_DATA_ID = PL.PROCESS_META_DATA_ID 
                            AND LOWER(GLL.CODE) = LOWER(PL.LABEL_NAME) 
                    WHERE PL.PROCESS_META_DATA_ID = $metaDataIdPh 
                        AND PL.IS_INPUT = 1 
                        AND PL.IS_SHOW = 1 
                        AND PL.LABEL_NAME IS NOT NULL 
                ) GL 
                ORDER BY GL.FIRST_ORDER ASC, GL.SECOND_ORDER ASC";
                
            } elseif ($metaTypeId == Mdmetadata::$metaGroupMetaTypeId) {
                
                $sql = "
                SELECT 
                    GL.GROUP_NAME, 
                    GL.PATH_NAME, 
                    GL.LABEL_NAME, 
                    GL.GLOBE_CODE, 
                    $selectLangColumn 
                    GL.MONGOLIAN  
                FROM (
                    SELECT 
                        'listName' AS GROUP_NAME, 
                        'listName' AS PATH_NAME, 
                        PL.LIST_NAME AS LABEL_NAME, 
                        GLL.CODE AS GLOBE_CODE, 
                        ".$this->db->IfNull('GL.MONGOLIAN', $this->db->IfNull('PL.LIST_NAME', 'MD.META_DATA_NAME'))." AS MONGOLIAN, 
                        $selectLangColumn     
                        1 AS FIRST_ORDER, 
                        1 AS SECOND_ORDER  
                    FROM META_GROUP_LINK PL 
                        LEFT JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.LIST_NAME) 
                        LEFT JOIN GLOBE_DICTIONARY GLL ON GLL.META_DATA_ID = PL.META_DATA_ID 
                            AND LOWER(GLL.CODE) = LOWER(PL.LIST_NAME) 
                        LEFT JOIN META_DATA MD ON MD.META_DATA_ID = PL.META_DATA_ID   
                    WHERE PL.META_DATA_ID = $metaDataIdPh    
                        
                    UNION ALL
                    
                    SELECT 
                        'listMenuName' AS GROUP_NAME, 
                        'listMenuName' AS PATH_NAME, 
                        PL.LIST_MENU_NAME AS LABEL_NAME, 
                        GLL.CODE AS GLOBE_CODE, 
                        ".$this->db->IfNull('GL.MONGOLIAN', $this->db->IfNull('PL.LIST_MENU_NAME', 'MD.META_DATA_NAME'))." AS MONGOLIAN, 
                        $selectLangColumn     
                        2 AS FIRST_ORDER, 
                        2 AS SECOND_ORDER  
                    FROM META_GROUP_LINK PL 
                        LEFT JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.LIST_MENU_NAME) 
                        LEFT JOIN GLOBE_DICTIONARY GLL ON GLL.META_DATA_ID = PL.META_DATA_ID 
                            AND LOWER(GLL.CODE) = LOWER(PL.LIST_MENU_NAME) 
                        LEFT JOIN META_DATA MD ON MD.META_DATA_ID = PL.META_DATA_ID   
                    WHERE PL.META_DATA_ID = $metaDataIdPh 
                        AND PL.LIST_MENU_NAME IS NOT NULL    

                    UNION ALL 
                    
                    SELECT 
                        'mergeName' AS GROUP_NAME, 
                        'mergeName' AS PATH_NAME, 
                        PL.SIDEBAR_NAME AS LABEL_NAME,  
                        GLL.CODE AS GLOBE_CODE, 
                        ".$this->db->IfNull('GL.MONGOLIAN', 'PL.SIDEBAR_NAME')." AS MONGOLIAN, 
                        $selectLangColumn  
                        3 AS FIRST_ORDER, 
                        3 AS SECOND_ORDER  
                    FROM META_GROUP_CONFIG PL 
                        LEFT JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.SIDEBAR_NAME) 
                        LEFT JOIN GLOBE_DICTIONARY GLL ON GLL.META_DATA_ID = PL.MAIN_META_DATA_ID 
                            AND LOWER(GLL.CODE) = LOWER(PL.SIDEBAR_NAME) 
                    WHERE PL.MAIN_META_DATA_ID = $metaDataIdPh 
                        AND PL.IS_SELECT = 1 
                        AND (PL.IS_SHOW = 1 OR PL.IS_SHOW_BASKET = 1)  
                        AND PL.PARENT_ID IS NULL 
                        AND PL.SIDEBAR_NAME IS NOT NULL 
                    GROUP BY 
                        PL.SIDEBAR_NAME, 
                        GL.MONGOLIAN, 
                        $selectLangColumn  
                        GLL.CODE            
                    
                    UNION ALL 

                    SELECT 
                        'searchGroupName' AS GROUP_NAME, 
                        'searchGroupName' AS PATH_NAME, 
                        PL.SEARCH_GROUPING_NAME AS LABEL_NAME,  
                        GLL.CODE AS GLOBE_CODE, 
                        ".$this->db->IfNull('GL.MONGOLIAN', 'PL.SEARCH_GROUPING_NAME')." AS MONGOLIAN, 
                        $selectLangColumn  
                        4 AS FIRST_ORDER, 
                        4 AS SECOND_ORDER  
                    FROM META_GROUP_CONFIG PL 
                        LEFT JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.SEARCH_GROUPING_NAME) 
                        LEFT JOIN GLOBE_DICTIONARY GLL ON GLL.META_DATA_ID = PL.MAIN_META_DATA_ID 
                            AND LOWER(GLL.CODE) = LOWER(PL.SEARCH_GROUPING_NAME) 
                    WHERE PL.MAIN_META_DATA_ID = $metaDataIdPh 
                        AND (PL.IS_CRITERIA = 1 OR PL.IS_UM_CRITERIA = 1) 
                        AND PL.PARENT_ID IS NULL 
                        AND PL.SEARCH_GROUPING_NAME IS NOT NULL 
                    GROUP BY 
                        PL.SEARCH_GROUPING_NAME, 
                        GL.MONGOLIAN, 
                        $selectLangColumn  
                        GLL.CODE 
                    
                    UNION ALL 

                    SELECT 
                        'batchName' AS GROUP_NAME, 
                        'batchName' AS PATH_NAME, 
                        PL.BATCH_NAME AS LABEL_NAME, 
                        GLL.CODE AS GLOBE_CODE, 
                        ".$this->db->IfNull('GL.MONGOLIAN', 'PL.BATCH_NAME')." AS MONGOLIAN, 
                        $selectLangColumn  
                        5 AS FIRST_ORDER, 
                        PL.BATCH_NUMBER AS SECOND_ORDER 
                    FROM META_DM_PROCESS_BATCH PL 
                        INNER JOIN META_DM_PROCESS_DTL DTL ON DTL.MAIN_META_DATA_ID = PL.MAIN_META_DATA_ID 
                            AND DTL.BATCH_NUMBER = PL.BATCH_NUMBER 
                        LEFT JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.BATCH_NAME) 
                        LEFT JOIN GLOBE_DICTIONARY GLL ON GLL.META_DATA_ID = PL.MAIN_META_DATA_ID 
                            AND LOWER(GLL.CODE) = LOWER(PL.BATCH_NAME) 
                    WHERE PL.MAIN_META_DATA_ID = $metaDataIdPh 
                        AND PL.BATCH_NAME IS NOT NULL 
                    GROUP BY 
                        PL.BATCH_NAME, 
                        GL.MONGOLIAN, 
                        $selectLangColumn  
                        GLL.CODE, 
                        PL.BATCH_NUMBER 
                    
                    UNION ALL 

                    SELECT 
                        'listProcessName' AS GROUP_NAME, 
                        'listProcessName' AS PATH_NAME, 
                        PL.PROCESS_NAME AS LABEL_NAME,  
                        GLL.CODE AS GLOBE_CODE, 
                        ".$this->db->IfNull('GL.MONGOLIAN', 'PL.PROCESS_NAME')." AS MONGOLIAN, 
                        $selectLangColumn  
                        6 AS FIRST_ORDER, 
                        PL.ORDER_NUM AS SECOND_ORDER  
                    FROM META_DM_PROCESS_DTL PL 
                        LEFT JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.PROCESS_NAME) 
                        LEFT JOIN GLOBE_DICTIONARY GLL ON GLL.META_DATA_ID = PL.MAIN_META_DATA_ID 
                            AND LOWER(GLL.CODE) = LOWER(PL.PROCESS_NAME) 
                    WHERE PL.MAIN_META_DATA_ID = $metaDataIdPh 
                        AND PL.PROCESS_NAME IS NOT NULL 
                    GROUP BY 
                        PL.PROCESS_NAME, 
                        GL.MONGOLIAN, 
                        $selectLangColumn  
                        GLL.CODE, 
                        PL.ORDER_NUM 
                        
                    UNION ALL 
                    
                    SELECT 
                        'columns' AS GROUP_NAME, 
                        PL.FIELD_PATH AS PATH_NAME, 
                        PL.LABEL_NAME, 
                        GLL.CODE AS GLOBE_CODE, 
                        ".$this->db->IfNull('GL.MONGOLIAN', 'PL.LABEL_NAME')." AS MONGOLIAN, 
                        $selectLangColumn  
                        7 AS FIRST_ORDER, 
                        PL.DISPLAY_ORDER AS SECOND_ORDER  
                    FROM META_GROUP_CONFIG PL 
                        LEFT JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.LABEL_NAME) 
                        LEFT JOIN GLOBE_DICTIONARY GLL ON GLL.META_DATA_ID = PL.MAIN_META_DATA_ID 
                            AND LOWER(GLL.CODE) = LOWER(PL.LABEL_NAME) 
                    WHERE PL.MAIN_META_DATA_ID = $metaDataIdPh 
                        AND PL.PARENT_ID IS NULL 
                        AND PL.LABEL_NAME IS NOT NULL 
                ) GL 
                ORDER BY GL.FIRST_ORDER ASC, GL.SECOND_ORDER ASC";
            }
            
            $data = $this->db->GetAll($sql, array($metaDataId));
            
            if ($data) {
                $response = array(
                    'status'       => 'success', 
                    'metaTypeId'   => $metaTypeId, 
                    'metaDataCode' => $metaDataCode, 
                    'langList'     => $langList, 
                    'list'         => $data
                );
            }  
        } 
        
        return $response;
    }
    
    public function saveMetaTranslationModel() {
        
        $metaDataId = Input::numeric('metaDataId');
        $metaTypeId = Input::numeric('metaTypeId');
        
        if ($metaDataId && $metaTypeId) {
            
            $metaDataCode = Input::post('metaDataCode');
            $postData     = Input::postData();
            $param        = $postData['param'];
            $langList     = self::getActiveLanguageModel();
            
            $isCustomerServer = Config::getFromCacheDefault('isCustomerServer', null, 0);
            
            try {
                
                if ($metaTypeId == Mdmetadata::$businessProcessMetaTypeId) {

                    foreach ($param as $key => $val) {

                        if ($key == 'processName') {

                            $processNameRow = $val['processName'];
                            $globeCode      = $processNameRow['globeCode'][0];

                            if ($globeCode) {

                                $data = array();

                                foreach ($langList as $langRow) {
                                    $data[$langRow['LANGUAGE_CODE']] = $processNameRow[$langRow['LANGUAGE_CODE']][0];
                                }

                                $this->db->AutoExecute('GLOBE_DICTIONARY', $data, 'UPDATE', "CODE = '$globeCode'");

                            } else {

                                $uid = getUID();

                                $data = array(
                                    'ID'           => $uid, 
                                    'CODE'         => $metaDataId . '_' . $uid, 
                                    'TYPE_ID'      => 1006, 
                                    'GROUP_ID'     => 1009, 
                                    'CREATED_DATE' => Date::currentDate(), 
                                    'META_DATA_ID' => $metaDataId, 
                                    'IS_CUSTOM'    => $isCustomerServer
                                );

                                foreach ($langList as $langRow) {
                                    $data[$langRow['LANGUAGE_CODE']] = $processNameRow[$langRow['LANGUAGE_CODE']][0];
                                }

                                $this->db->AutoExecute('GLOBE_DICTIONARY', $data);
                                $this->db->AutoExecute('META_BUSINESS_PROCESS_LINK', array('PROCESS_NAME' => $data['CODE']), 'UPDATE', 'META_DATA_ID = ' . $metaDataId);
                            }

                        } elseif ($key == 'tabName') {

                            $tabRows       = $val['tabName'];
                            $tabGlobeCodes = $tabRows['globeCode'];

                            foreach ($tabGlobeCodes as $tkey => $tval) {

                                if ($tval) {

                                    $data = array();

                                    foreach ($langList as $langRow) {
                                        $data[$langRow['LANGUAGE_CODE']] = $tabRows[$langRow['LANGUAGE_CODE']][$tkey];
                                    }

                                    $this->db->AutoExecute('GLOBE_DICTIONARY', $data, 'UPDATE', "CODE = '$tval'");

                                } else {

                                    $uid = getUID();

                                    $data = array(
                                        'ID'           => $uid, 
                                        'CODE'         => $metaDataId . '_' . $uid, 
                                        'TYPE_ID'      => 1006, 
                                        'GROUP_ID'     => 1009, 
                                        'CREATED_DATE' => Date::currentDate(), 
                                        'META_DATA_ID' => $metaDataId, 
                                        'IS_CUSTOM'    => $isCustomerServer
                                    );

                                    foreach ($langList as $langRow) {
                                        $data[$langRow['LANGUAGE_CODE']] = $tabRows[$langRow['LANGUAGE_CODE']][$tkey];
                                    }

                                    $this->db->AutoExecute('GLOBE_DICTIONARY', $data);

                                    $this->db->AutoExecute('META_PROCESS_PARAM_ATTR_LINK', 
                                        array('TAB_NAME' => $data['CODE']), 
                                        'UPDATE', 
                                        "PROCESS_META_DATA_ID = $metaDataId AND IS_INPUT = 1 AND TAB_NAME = '".$tabRows['labelName'][$tkey]."'"
                                    );
                                }
                            }

                        } elseif ($key == 'sidebarName') {

                            $sidebarRows       = $val['sidebarName'];
                            $sidebarGlobeCodes = $sidebarRows['globeCode'];

                            foreach ($sidebarGlobeCodes as $skey => $sval) {

                                if ($sval) {

                                    $data = array();

                                    foreach ($langList as $langRow) {
                                        $data[$langRow['LANGUAGE_CODE']] = $sidebarRows[$langRow['LANGUAGE_CODE']][$skey];
                                    }

                                    $this->db->AutoExecute('GLOBE_DICTIONARY', $data, 'UPDATE', "CODE = '$sval'");

                                } else {

                                    $uid = getUID();

                                    $data = array(
                                        'ID'           => $uid, 
                                        'CODE'         => $metaDataId . '_' . $uid, 
                                        'TYPE_ID'      => 1006, 
                                        'GROUP_ID'     => 1009, 
                                        'CREATED_DATE' => Date::currentDate(), 
                                        'META_DATA_ID' => $metaDataId, 
                                        'IS_CUSTOM'    => $isCustomerServer
                                    );

                                    foreach ($langList as $langRow) {
                                        $data[$langRow['LANGUAGE_CODE']] = $sidebarRows[$langRow['LANGUAGE_CODE']][$skey];
                                    }

                                    $this->db->AutoExecute('GLOBE_DICTIONARY', $data);

                                    $this->db->AutoExecute('META_PROCESS_PARAM_ATTR_LINK', 
                                        array('SIDEBAR_NAME' => $data['CODE']), 
                                        'UPDATE', 
                                        "PROCESS_META_DATA_ID = $metaDataId AND IS_INPUT = 1 AND SIDEBAR_NAME = '".$sidebarRows['labelName'][$skey]."'"
                                    );
                                }
                            }

                        } elseif ($key == 'parameter') {

                            $parameterRows = $val;

                            foreach ($parameterRows as $path => $pathVal) {

                                $pathGlobeCode = $pathVal['globeCode'][0];

                                if ($pathGlobeCode) {

                                    $data = array();

                                    foreach ($langList as $langRow) {
                                        $data[$langRow['LANGUAGE_CODE']] = $pathVal[$langRow['LANGUAGE_CODE']][0];
                                    }

                                    $this->db->AutoExecute('GLOBE_DICTIONARY', $data, 'UPDATE', "CODE = '$pathGlobeCode'");

                                } else {

                                    $uid = getUID();

                                    $data = array(
                                        'ID'           => $uid, 
                                        'CODE'         => $metaDataId . '_' . $uid, 
                                        'TYPE_ID'      => 1006, 
                                        'GROUP_ID'     => 1009, 
                                        'CREATED_DATE' => Date::currentDate(), 
                                        'META_DATA_ID' => $metaDataId, 
                                        'IS_CUSTOM'    => $isCustomerServer
                                    );

                                    foreach ($langList as $langRow) {
                                        $data[$langRow['LANGUAGE_CODE']] = $pathVal[$langRow['LANGUAGE_CODE']][0];
                                    }

                                    $this->db->AutoExecute('GLOBE_DICTIONARY', $data);

                                    $this->db->AutoExecute('META_PROCESS_PARAM_ATTR_LINK', 
                                        array('LABEL_NAME' => $data['CODE']), 
                                        'UPDATE', 
                                        "PROCESS_META_DATA_ID = $metaDataId AND IS_INPUT = 1 AND PARAM_REAL_PATH = '".$path."'"
                                    );
                                }
                            }
                        }
                    }

                    $response = array('status' => 'success', 'message' => Lang::line('msg_save_success') . '<br />Та процессийн цонхийг хаагаад дахин нээнэ үү!');
                    
                    (new Mdmeta())->bpParamsClearCache($metaDataId, $metaDataCode, false);
            
                } elseif ($metaTypeId == Mdmetadata::$metaGroupMetaTypeId) {
                    
                    foreach ($param as $key => $val) {

                        if ($key == 'listName') {

                            $listNameRow = $val['listName'];
                            $globeCode   = $listNameRow['globeCode'][0];

                            if ($globeCode) {

                                $data = array();

                                foreach ($langList as $langRow) {
                                    $data[$langRow['LANGUAGE_CODE']] = $listNameRow[$langRow['LANGUAGE_CODE']][0];
                                }

                                $this->db->AutoExecute('GLOBE_DICTIONARY', $data, 'UPDATE', "CODE = '$globeCode'");

                            } else {

                                $uid = getUID();

                                $data = array(
                                    'ID'           => $uid, 
                                    'CODE'         => $metaDataId . '_' . $uid, 
                                    'TYPE_ID'      => 1006, 
                                    'GROUP_ID'     => 1009, 
                                    'CREATED_DATE' => Date::currentDate(), 
                                    'META_DATA_ID' => $metaDataId, 
                                    'IS_CUSTOM'    => $isCustomerServer
                                );

                                foreach ($langList as $langRow) {
                                    $data[$langRow['LANGUAGE_CODE']] = $listNameRow[$langRow['LANGUAGE_CODE']][0];
                                }

                                $this->db->AutoExecute('GLOBE_DICTIONARY', $data);
                                $this->db->AutoExecute('META_GROUP_LINK', array('LIST_NAME' => $data['CODE']), 'UPDATE', 'META_DATA_ID = ' . $metaDataId);
                            }

                        } elseif ($key == 'listMenuName') {

                            $listMenuNameRow = $val['listMenuName'];
                            $globeCode       = $listMenuNameRow['globeCode'][0];

                            if ($globeCode) {

                                $data = array();

                                foreach ($langList as $langRow) {
                                    $data[$langRow['LANGUAGE_CODE']] = $listMenuNameRow[$langRow['LANGUAGE_CODE']][0];
                                }

                                $this->db->AutoExecute('GLOBE_DICTIONARY', $data, 'UPDATE', "CODE = '$globeCode'");

                            } else {

                                $uid = getUID();

                                $data = array(
                                    'ID'           => $uid, 
                                    'CODE'         => $metaDataId . '_' . $uid, 
                                    'TYPE_ID'      => 1006, 
                                    'GROUP_ID'     => 1009, 
                                    'CREATED_DATE' => Date::currentDate(), 
                                    'META_DATA_ID' => $metaDataId, 
                                    'IS_CUSTOM'    => $isCustomerServer
                                );

                                foreach ($langList as $langRow) {
                                    $data[$langRow['LANGUAGE_CODE']] = $listMenuNameRow[$langRow['LANGUAGE_CODE']][0];
                                }

                                $this->db->AutoExecute('GLOBE_DICTIONARY', $data);
                                $this->db->AutoExecute('META_GROUP_LINK', array('LIST_MENU_NAME' => $data['CODE']), 'UPDATE', 'META_DATA_ID = ' . $metaDataId);
                            }

                        } elseif ($key == 'mergeName') {

                            $mergeRows       = $val['mergeName'];
                            $mergeGlobeCodes = $mergeRows['globeCode'];

                            foreach ($mergeGlobeCodes as $mkey => $mval) {

                                if ($mval) {

                                    $data = array();

                                    foreach ($langList as $langRow) {
                                        $data[$langRow['LANGUAGE_CODE']] = $mergeRows[$langRow['LANGUAGE_CODE']][$mkey];
                                    }

                                    $this->db->AutoExecute('GLOBE_DICTIONARY', $data, 'UPDATE', "CODE = '$mval'");

                                } else {

                                    $uid = getUID();

                                    $data = array(
                                        'ID'           => $uid, 
                                        'CODE'         => $metaDataId . '_' . $uid, 
                                        'TYPE_ID'      => 1006, 
                                        'GROUP_ID'     => 1009, 
                                        'CREATED_DATE' => Date::currentDate(), 
                                        'META_DATA_ID' => $metaDataId, 
                                        'IS_CUSTOM'    => $isCustomerServer
                                    );

                                    foreach ($langList as $langRow) {
                                        $data[$langRow['LANGUAGE_CODE']] = $mergeRows[$langRow['LANGUAGE_CODE']][$mkey];
                                    }

                                    $this->db->AutoExecute('GLOBE_DICTIONARY', $data);

                                    $this->db->AutoExecute('META_GROUP_CONFIG', 
                                        array('SIDEBAR_NAME' => $data['CODE']), 
                                        'UPDATE', 
                                        "MAIN_META_DATA_ID = $metaDataId AND PARENT_ID IS NULL AND SIDEBAR_NAME = '".$mergeRows['labelName'][$mkey]."'"
                                    );
                                }
                            }

                        } elseif ($key == 'searchGroupName') {

                            $searchGroupNameRows       = $val['searchGroupName'];
                            $searchGroupNameGlobeCodes = $searchGroupNameRows['globeCode'];

                            foreach ($searchGroupNameGlobeCodes as $skey => $sval) {

                                if ($sval) {

                                    $data = array();

                                    foreach ($langList as $langRow) {
                                        $data[$langRow['LANGUAGE_CODE']] = $searchGroupNameRows[$langRow['LANGUAGE_CODE']][$skey];
                                    }

                                    $this->db->AutoExecute('GLOBE_DICTIONARY', $data, 'UPDATE', "CODE = '$sval'");

                                } else {

                                    $uid = getUID();

                                    $data = array(
                                        'ID'           => $uid, 
                                        'CODE'         => $metaDataId . '_' . $uid, 
                                        'TYPE_ID'      => 1006, 
                                        'GROUP_ID'     => 1009, 
                                        'CREATED_DATE' => Date::currentDate(), 
                                        'META_DATA_ID' => $metaDataId, 
                                        'IS_CUSTOM'    => $isCustomerServer
                                    );

                                    foreach ($langList as $langRow) {
                                        $data[$langRow['LANGUAGE_CODE']] = $searchGroupNameRows[$langRow['LANGUAGE_CODE']][$skey];
                                    }

                                    $this->db->AutoExecute('GLOBE_DICTIONARY', $data);

                                    $this->db->AutoExecute('META_GROUP_CONFIG', 
                                        array('SEARCH_GROUPING_NAME' => $data['CODE']), 
                                        'UPDATE', 
                                        "MAIN_META_DATA_ID = $metaDataId AND PARENT_ID IS NULL AND SEARCH_GROUPING_NAME = '".$searchGroupNameRows['labelName'][$skey]."'"
                                    );
                                }
                            }

                        } elseif ($key == 'batchName') {

                            $batchNameRows       = $val['batchName'];
                            $batchNameGlobeCodes = $batchNameRows['globeCode'];

                            foreach ($batchNameGlobeCodes as $bkey => $bval) {

                                if ($bval) {

                                    $data = array();

                                    foreach ($langList as $langRow) {
                                        $data[$langRow['LANGUAGE_CODE']] = $batchNameRows[$langRow['LANGUAGE_CODE']][$bkey];
                                    }

                                    $this->db->AutoExecute('GLOBE_DICTIONARY', $data, 'UPDATE', "CODE = '$bval'");

                                } else {

                                    $uid = getUID();

                                    $data = array(
                                        'ID'           => $uid, 
                                        'CODE'         => $metaDataId . '_' . $uid, 
                                        'TYPE_ID'      => 1006, 
                                        'GROUP_ID'     => 1009, 
                                        'CREATED_DATE' => Date::currentDate(), 
                                        'META_DATA_ID' => $metaDataId, 
                                        'IS_CUSTOM'    => $isCustomerServer
                                    );

                                    foreach ($langList as $langRow) {
                                        $data[$langRow['LANGUAGE_CODE']] = $batchNameRows[$langRow['LANGUAGE_CODE']][$bkey];
                                    }

                                    $this->db->AutoExecute('GLOBE_DICTIONARY', $data);

                                    $this->db->AutoExecute('META_DM_PROCESS_BATCH', 
                                        array('BATCH_NAME' => $data['CODE']), 
                                        'UPDATE', 
                                        "MAIN_META_DATA_ID = $metaDataId AND BATCH_NAME = '".$batchNameRows['labelName'][$bkey]."'"
                                    );
                                }
                            }

                        } elseif ($key == 'listProcessName') {

                            $listProcessNameRows       = $val['listProcessName'];
                            $listProcessNameGlobeCodes = $listProcessNameRows['globeCode'];

                            foreach ($listProcessNameGlobeCodes as $lkey => $lval) {

                                if ($lval) {

                                    $data = array();

                                    foreach ($langList as $langRow) {
                                        $data[$langRow['LANGUAGE_CODE']] = $listProcessNameRows[$langRow['LANGUAGE_CODE']][$lkey];
                                    }

                                    $this->db->AutoExecute('GLOBE_DICTIONARY', $data, 'UPDATE', "CODE = '$lval'");

                                } else {

                                    $uid = getUID();

                                    $data = array(
                                        'ID'           => $uid, 
                                        'CODE'         => $metaDataId . '_' . $uid, 
                                        'TYPE_ID'      => 1006, 
                                        'GROUP_ID'     => 1009, 
                                        'CREATED_DATE' => Date::currentDate(), 
                                        'META_DATA_ID' => $metaDataId, 
                                        'IS_CUSTOM'    => $isCustomerServer
                                    );

                                    foreach ($langList as $langRow) {
                                        $data[$langRow['LANGUAGE_CODE']] = $listProcessNameRows[$langRow['LANGUAGE_CODE']][$lkey];
                                    }

                                    $this->db->AutoExecute('GLOBE_DICTIONARY', $data);

                                    $this->db->AutoExecute('META_DM_PROCESS_DTL', 
                                        array('PROCESS_NAME' => $data['CODE']), 
                                        'UPDATE', 
                                        "MAIN_META_DATA_ID = $metaDataId AND PROCESS_NAME = '".$listProcessNameRows['labelName'][$lkey]."'"
                                    );
                                }
                            }

                        } elseif ($key == 'columns') {

                            $columnsRows = $val;

                            foreach ($columnsRows as $path => $pathVal) {

                                $pathGlobeCode = $pathVal['globeCode'][0];

                                if ($pathGlobeCode) {

                                    $data = array();

                                    foreach ($langList as $langRow) {
                                        $data[$langRow['LANGUAGE_CODE']] = $pathVal[$langRow['LANGUAGE_CODE']][0];
                                    }

                                    $this->db->AutoExecute('GLOBE_DICTIONARY', $data, 'UPDATE', "CODE = '$pathGlobeCode'");

                                } else {

                                    $uid = getUID();

                                    $data = array(
                                        'ID'           => $uid, 
                                        'CODE'         => $metaDataId . '_' . $uid, 
                                        'TYPE_ID'      => 1006, 
                                        'GROUP_ID'     => 1009, 
                                        'CREATED_DATE' => Date::currentDate(), 
                                        'META_DATA_ID' => $metaDataId, 
                                        'IS_CUSTOM'    => $isCustomerServer
                                    );

                                    foreach ($langList as $langRow) {
                                        $data[$langRow['LANGUAGE_CODE']] = $pathVal[$langRow['LANGUAGE_CODE']][0];
                                    }

                                    $this->db->AutoExecute('GLOBE_DICTIONARY', $data);

                                    $this->db->AutoExecute('META_GROUP_CONFIG', 
                                        array('LABEL_NAME' => $data['CODE']), 
                                        'UPDATE', 
                                        "MAIN_META_DATA_ID = $metaDataId AND PARENT_ID IS NULL AND FIELD_PATH = '".$path."'"
                                    );
                                }
                            }
                        }
                    }

                    $response = array('status' => 'success', 'message' => Lang::line('msg_save_success') . '<br />Та жагсаалтыг хаагаад дахин нээнэ үү!');
                    
                    (new Mdmeta())->dvCacheClearByMetaId($metaDataId);
                    
                }
                
            } catch (ADODB_Exception $ex) {
                
                $response = array('status' => 'error', 'message' => $ex->getMessage());
            }
            
        } else {
            $response = array('status' => 'error', 'message' => 'Wrong');
        }
        
        if ($response['status'] == 'success') {
            
            self::generateLanguageFileModel();
            
            if ($isCustomerServer == '1') {

                $configFields = array(
                    'ID'           => getUID(), 
                    'META_DATA_ID' => $metaDataId
                );

                $this->db->Replace('CUSTOMER_META_TRANSLATE', $configFields, array('META_DATA_ID'));
            }
        }
        
        return $response;
    }
    
    public function getMenuMetaDictionaryModel() {
        
        $moduleId = Input::numeric('metaDataId');
        $langList = self::getActiveLanguageModel();
        
        $selectLangColumn = '';
            
        foreach ($langList as $langRow) {

            if (strtolower($langRow['LANGUAGE_CODE']) != 'mongolian') {
                $selectLangColumn .= 'GL.' . $langRow['LANGUAGE_CODE'] . ', ';
            }
        }

        $metaDataIdPh = $this->db->Param(0);
            
        $data = $this->db->GetAll("
            SELECT 
                PL.META_DATA_ID AS TRG_META_DATA_ID, 
                GLL.CODE AS GLOBE_CODE, 
                ".$this->db->IfNull('GL.MONGOLIAN', $this->db->IfNull('PL.GLOBE_CODE', 'MD.META_DATA_NAME'))." AS MONGOLIAN, 
                $selectLangColumn 
                0 AS ORDER_LEVEL 
            FROM META_MENU_LINK PL 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = PL.META_DATA_ID 
                LEFT JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.GLOBE_CODE) 
                LEFT JOIN GLOBE_DICTIONARY GLL ON GLL.META_DATA_ID = PL.META_DATA_ID 
                    AND LOWER(GLL.CODE) = LOWER(PL.GLOBE_CODE) 
            WHERE PL.META_DATA_ID = $metaDataIdPh 

            UNION ALL 

            SELECT  
                MM.TRG_META_DATA_ID, 
                GLL.CODE AS GLOBE_CODE,  
                ".$this->db->IfNull('GL.MONGOLIAN', $this->db->IfNull('PL.GLOBE_CODE', 'MD.META_DATA_NAME'))." AS MONGOLIAN, 
                $selectLangColumn 
                LEVEL AS ORDER_LEVEL 
            FROM META_META_MAP MM  
                INNER JOIN META_MENU_LINK PL ON PL.META_DATA_ID = MM.TRG_META_DATA_ID 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = PL.META_DATA_ID 
                LEFT JOIN GLOBE_DICTIONARY GL ON LOWER(GL.CODE) = LOWER(PL.GLOBE_CODE) 
                LEFT JOIN GLOBE_DICTIONARY GLL ON GLL.META_DATA_ID = $metaDataIdPh  
                    AND LOWER(GLL.CODE) = LOWER(PL.GLOBE_CODE) 
            START WITH MM.SRC_META_DATA_ID = $metaDataIdPh 
            CONNECT BY NOCYCLE MM.SRC_META_DATA_ID = PRIOR MM.TRG_META_DATA_ID", 
            array($moduleId)
        );
        
        if ($data) {
            return array('status' => 'success', 'langList' => $langList, 'list' => $data);
        } else {
            return array('status' => 'error', 'message' => 'Empty!');
        }
    }
    
    public function saveMenuMetaTranslationModel() {
        
        $moduleId = Input::numeric('moduleId');
        
        if ($moduleId) {
            
            $postData = Input::postData();
            $param    = $postData['param'];
            $langList = self::getActiveLanguageModel();
            $currentDate = Date::currentDate();
            
            $isCustomerServer = Config::getFromCacheDefault('isCustomerServer', null, 0);
            
            try {

                foreach ($param as $metaId => $val) {
                    
                    $globeCode = $val['globeCode'];

                    if ($globeCode) {

                        $data = array();

                        foreach ($langList as $langRow) {
                            $data[$langRow['LANGUAGE_CODE']] = $val[$langRow['LANGUAGE_CODE']];
                        }

                        $this->db->AutoExecute('GLOBE_DICTIONARY', $data, 'UPDATE', "CODE = '$globeCode'");

                    } else {

                        $uid = getUID();

                        $data = array(
                            'ID'           => $uid, 
                            'CODE'         => $moduleId . '_' . $uid, 
                            'TYPE_ID'      => 1006, 
                            'GROUP_ID'     => 1009, 
                            'CREATED_DATE' => $currentDate, 
                            'META_DATA_ID' => $moduleId, 
                            'IS_CUSTOM'    => $isCustomerServer
                        );

                        foreach ($langList as $langRow) {
                            $data[$langRow['LANGUAGE_CODE']] = $val[$langRow['LANGUAGE_CODE']];
                        }

                        $this->db->AutoExecute('GLOBE_DICTIONARY', $data);
                        $this->db->AutoExecute('META_MENU_LINK', array('GLOBE_CODE' => $data['CODE']), 'UPDATE', 'META_DATA_ID = ' . $metaId);
                        
                        if ($isCustomerServer == '1') {

                            $configFields = array(
                                'ID'           => getUID(), 
                                'META_DATA_ID' => $moduleId
                            );

                            $this->db->Replace('CUSTOMER_META_TRANSLATE', $configFields, array('META_DATA_ID'));
                        }
                    }
                }
                
                $response = array('status' => 'success', 'message' => Lang::line('msg_save_success') . '<br />Та веб броузероо сэргээнэ үү!');

            } catch (ADODB_Exception $ex) {

                $response = array('status' => 'error', 'message' => $ex->getMessage());
            }

            if ($response['status'] == 'success') {
                
                self::generateLanguageFileModel();
                
                $tmp_dir = Mdcommon::getCacheDirectory();
                $userKeyId = Ue::sessionUserKeyId();

                $leftMenuFiles = glob($tmp_dir."/*/le/leftmenu_".$userKeyId."_*.txt");
                foreach ($leftMenuFiles as $leftMenuFile) {
                    @unlink($leftMenuFile);
                }
                $topMenuFiles = glob($tmp_dir."/*/to/topmenu_".$userKeyId.".txt");
                foreach ($topMenuFiles as $topMenuFile) {
                    @unlink($topMenuFile);
                }
                $topMenuFiles = glob($tmp_dir."/*/to/topmenumodule_".$userKeyId.".txt");
                foreach ($topMenuFiles as $topMenuFile) {
                    @unlink($topMenuFile);
                }
                $appMenuFiles = glob($tmp_dir."/*/ap/appmenu_".$userKeyId.".txt");
                foreach ($appMenuFiles as $appMenuFile) {
                    @unlink($appMenuFile);
                }
                $appSubMenuFiles = glob($tmp_dir."/*/ap/appmenu_".$userKeyId."_*.txt");
                foreach ($appSubMenuFiles as $appSubMenuFile) {
                    @unlink($appSubMenuFile);
                }
            }
            
        } else {
            $response = array('status' => 'error', 'message' => 'Wrong');
        }
        
        return $response;
    }

}