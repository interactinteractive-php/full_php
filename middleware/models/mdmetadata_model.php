<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdmetadata_Model extends Model {

    private static $gfServiceAddress = GF_SERVICE_ADDRESS;
    private static $folderDatas = array();
    public static $folderTreeDatas = array();
    private static $pathChangeExpData = array();
    private static $isPathChangeExpData = false;
    private static $k = 0;
    private static $t = 0;

    public function __construct() {
        parent::__construct();
    }

    public function getMetaDataModel($metaDataId, $getFolder = false) {
        
        $metaDataIdPh = $this->db->Param(0);
        $bindVars = array($this->db->addQ($metaDataId));
        
        $row = $this->db->GetRow("
            SELECT 
                MD.META_DATA_ID, 
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME, 
                MD.META_TYPE_ID, 
                MT.META_TYPE_CODE, 
                MD.DESCRIPTION, 
                MD.COPY_COUNT 
            FROM META_DATA MD 
                LEFT JOIN META_TYPE MT ON MT.META_TYPE_ID = MD.META_TYPE_ID 
            WHERE MD.META_DATA_ID = $metaDataIdPh", $bindVars);

        if ($getFolder && $row) {
            $folder = $this->db->GetRow("SELECT FOLDER_ID FROM META_DATA_FOLDER_MAP WHERE META_DATA_ID = $metaDataIdPh", $bindVars);
            if ($folder) {
                $row = array_merge($row, $folder);
            }
        }

        return $row;
    }

    public function getMetaTypeById($metaDataId) {
        return $this->db->GetOne("SELECT META_TYPE_ID FROM META_DATA WHERE META_DATA_ID = " . $this->db->Param(0), array($metaDataId));
    }

    public function getMetaDataByCodeModel($code) {
        return $this->db->GetRow("
            SELECT 
                MD.META_DATA_ID, 
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME, 
                MD.META_TYPE_ID, 
                MT.META_TYPE_CODE
            FROM META_DATA MD 
                LEFT JOIN META_TYPE MT ON MT.META_TYPE_ID = MD.META_TYPE_ID 
            WHERE LOWER(MD.META_DATA_CODE) = ".$this->db->Param(0), 
            array(Str::lower($code))
        );
    }

    public function getFolderRowModel($folderId) {
        if (empty($folderId)) {
            return false;
        }
        return $this->db->GetRow("SELECT FOLDER_ID, FOLDER_CODE, FOLDER_NAME FROM FVM_FOLDER WHERE FOLDER_ID = ".$this->db->Param(0), array($folderId));
    }

    public function getMetaDataIdByCodeModel($code) {
        return $this->db->GetOne("SELECT META_DATA_ID FROM META_DATA WHERE LOWER(META_DATA_CODE) = ".$this->db->Param(0), array(Str::lower($code)));
    }

    public function getMetaDataValuesByDataViewModel($row, $requestType = '', $inputParamStr = array()) {

        $attrId = strtolower($row['ATTRIBUTE_ID_COLUMN']);
        $array = $paramCriteria = array();
        $param = array(
            'systemMetaGroupId' => $row['META_DATA_ID'],
            'showQuery' => 0, 
            'ignorePermission' => 1 
        );

        if (Mdmetadata::$isProcessParamValues) {
            
            $defaultData = Mdwebservice::getParamDefaultValues(Mdwebservice::$processMetaDataId, Mdwebservice::$paramRealPath, $row['META_DATA_ID']);
            $idField = $attrId ? $attrId : 'id';
            
            foreach ($defaultData as $dVal) {
                $paramCriteria[$idField][] = array(
                    'operator' => '=',
                    'operand' => $dVal['VALUE_ID'] 
                );
            }
        }

        if ($requestType == 'linkedCombo') {
            
            parse_str(urldecode($inputParamStr), $inputParamArr);
            
            foreach ($inputParamArr as $key => $rowVal) {
                
                if (is_array($rowVal)) {
                    $paramCriteria[$key][] = array(
                        'operator' => 'IN', 
                        'operand' => Arr::implode_r(',', $rowVal, true) 
                    );
                } else {
                    $paramCriteria[$key][] = array(
                        'operator' => '=',
                        'operand' => $rowVal
                    );
                }
            }               
        }

        $paramCriteria['filterStartDate'] = array(
            array(
                'operator' => '=',
                'operand' =>  Ue::sessionFiscalPeriodStartDate()
            )                     
        );
        $paramCriteria['filterEndDate'] = array(
            array(
                'operator' => '=',
                'operand' =>  Ue::sessionFiscalPeriodEndDate()
            )                     
        );
        
        if (count($paramCriteria) > 0) {
            $param['criteria'] = $paramCriteria;
        }

        $dataViewValues = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($dataViewValues['status'] === 'success' && isset($dataViewValues['result'])) {
            
            unset($dataViewValues['result']['aggregatecolumns']);
            unset($dataViewValues['result']['paging']);
            
            $sqlData = $dataViewValues['result'];
            
            if (!isset($sqlData[0])) {
                $sqlData = array($sqlData);
            }
            
            if ($sqlData && array_key_exists($attrId, $sqlData[0])) {
                
                $attrName = strtolower($row['ATTRIBUTE_NAME_COLUMN']);
                
                if (!array_key_exists($attrName, $sqlData[0])) {
                    $attrName = $attrId;
                }
                
                foreach ($sqlData as $key => $value) {
                    $array[$key]['META_VALUE_ID']   = $value[$attrId];
                    $array[$key]['META_VALUE_NAME'] = $value[$attrName];
                    $array[$key]['ROW_DATA']        = $value;
                }
            }
        } 
        
        return $array;
    }

    public function getSingleMetaDataValuesByDataViewModel($row, $rowId, $inputParamArr = array()) {

        $attrId = strtolower($row['ATTRIBUTE_ID_COLUMN']);
        $attrName = strtolower($row['ATTRIBUTE_NAME_COLUMN']);

        if (isset($row['CHOOSE_TYPE']) && ($row['CHOOSE_TYPE'] == 'multi' || $row['CHOOSE_TYPE'] == 'multicomma')) {
            $operandValue = array(
                'operator' => 'IN',
                'operand' => $rowId 
            );
        } else {
            $operandValue = array(
                'operator' => '=',
                'operand' => html_entity_decode($rowId, ENT_QUOTES)
            );
        }

        $array = array();
        $param = array(
            'systemMetaGroupId' => $row['META_DATA_ID'],
            'showQuery' => 0, 
            'ignorePermission' => 1,  
            'criteria' => array(
                $attrId => array(
                    $operandValue
                )
            )
        );

        if (Mdmetadata::$isProcessParamValues) {

            $paramCriteria = array();
            $defaultData = Mdwebservice::getParamDefaultValues(Mdwebservice::$processMetaDataId, Mdwebservice::$paramRealPath, $row['META_DATA_ID']);

            foreach ($defaultData as $dVal) {
                $paramCriteria['id'][] = array(
                    'operator' => '=',
                    'operand' => $dVal['VALUE_ID']
                );
            }
            
            $param['criteria'] = array_merge($param['criteria'], $paramCriteria);
        }

        if (!empty($inputParamArr)) {
            
            $inputParamCriteria = array();
            
            foreach ($inputParamArr as $k => $v) {
                
                if (isset($v['operator'])) {
                    $inputParamCriteria[$k][] = array(
                        'operator' => $v['operator'],
                        'operand' => Input::param($v['operand'])
                    );
                } else {
                    $inputParamCriteria[$k][] = array(
                        'operator' => '=',
                        'operand' => Input::param($v)
                    );
                }
            }
            
            $param['criteria'] = array_merge($param['criteria'], $inputParamCriteria);
        }

        $dataViewValues = $this->ws->runArrayResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);            
        
        if (isset($dataViewValues['result']) && isset($dataViewValues['result'][0])) {
            
            unset($dataViewValues['result']['aggregatecolumns']);
            unset($dataViewValues['result']['paging']);
            
            $rows = $dataViewValues['result'];
            $firstRow = $rows[0];
                
            if ($attrId && array_key_exists($attrId, $firstRow) && $attrName && array_key_exists($attrName, $firstRow)) {
                foreach ($rows as $key => $value) {
                    $array[$key]['META_VALUE_ID'] = htmlentities($value[$attrId], ENT_QUOTES, 'UTF-8');
                    $array[$key]['META_VALUE_NAME'] = $value[$attrName];
                    $array[$key]['rowData'] = htmlentities(str_replace('&quot;', '\\&quot;', json_encode($value, JSON_UNESCAPED_UNICODE)), ENT_QUOTES, 'UTF-8');
                }
            }
        } 
        
        return $array;
    }

    public function getMetaTypeListByAddModeModel($typeIds = null) {
        
        $where = null;
        
        if ($typeIds) {
            $where = "AND META_TYPE_ID IN ($typeIds)";
        }
        
        if (Ue::sessionIsUseFolderPermission()) {
            
            $where .= 'AND META_TYPE_CODE NOT IN (
                SELECT 
                    IGNORE_META_TYPE 
                FROM FVM_FOLDER_USER_PERMISSION 
                WHERE USER_ID = '.Ue::sessionUserKeyId().' 
                    AND IGNORE_META_TYPE IS NOT NULL
            )';
        } 
        
        $data = $this->db->GetAll("
            SELECT 
                META_TYPE_ID, 
                META_TYPE_NAME, 
                META_TYPE_CODE 
            FROM META_TYPE 
            WHERE IS_ACTIVE = 1 
                $where 
            ORDER BY META_TYPE_NAME ASC");
        
        return $data;
    }
    
    public function getMetaTypeListModel($typeIds = null) {
        
        $where = null;
        
        if ($typeIds) {
            $where = "AND META_TYPE_ID IN ($typeIds)";
        }
        
        $data = $this->db->GetAll("
            SELECT 
                META_TYPE_ID, 
                META_TYPE_NAME, 
                META_TYPE_CODE 
            FROM META_TYPE 
            WHERE IS_ACTIVE = 1 
                $where 
            ORDER BY META_TYPE_NAME ASC");
        
        return $data;
    }

    public function getMetaTypeProcessListModel() {
        $data = $this->db->GetAll("
            SELECT 
                META_DATA_ID, 
                META_DATA_NAME, 
                META_DATA_CODE 
            FROM META_DATA 
            WHERE META_TYPE_ID IN (" . Mdmetadata::$businessProcessMetaTypeId . ", " . Mdmetadata::$expressionMetaTypeId . ") 
                AND IS_ACTIVE = 1 
            ORDER BY META_DATA_NAME ASC");

        return $data;
    }
    
    public function getProcessSubTypeListByAddModeModel() {
        $arrs = array(
            array(
                'code' => 'external', 
                'name' => 'External'
            ),
            array(
                'code' => 'internal', 
                'name' => 'Internal'
            ), 
            array(
                'code' => 'interface', 
                'name' => 'Interface'
            ),
            array(
                'code' => 'endtoend', 
                'name' => 'End to end'
            )            
        );
        
        $arrs = self::checkRemoveMetaSubType($arrs);
        
        return $arrs;
    }
    
    public function getGroupSubTypeListByAddModeModel() {
        $arrs = array(
            array(
                'code' => 'parameter', 
                'name' => 'Parameter'
            ),
            array(
                'code' => 'dataview', 
                'name' => 'DataView'
            ),
            array(
                'code' => 'tablestructure', 
                'name' => 'TableStructure'
            )
        );
        
        $arrs = self::checkRemoveMetaSubType($arrs);
        
        return $arrs;
    }
    
    public function checkRemoveMetaSubType($arrs) {
        
        if (Ue::sessionIsUseFolderPermission()) {
            
            $data = $this->db->GetAll('
                SELECT 
                    IGNORE_META_TYPE 
                FROM FVM_FOLDER_USER_PERMISSION 
                WHERE USER_ID = '.$this->db->Param(0).' 
                    AND IGNORE_META_TYPE IS NOT NULL 
                GROUP BY IGNORE_META_TYPE', 
                array(Ue::sessionUserKeyId())
            );
            
            if ($data) {
                foreach ($arrs as $k => $arr) {
                    foreach ($data as $row) {
                        if ($arr['code'] == $row['IGNORE_META_TYPE']) {
                            unset($arrs[$k]);
                        }
                    }
                }
            }
        } 
        
        return $arrs;
    }

    public function getMetaDataCodeModel($metaDataId) {
        return $metaDataId ? $this->db->GetOne("SELECT META_DATA_CODE FROM META_DATA WHERE META_DATA_ID = ".$this->db->Param(0), array($metaDataId)) : null;
    }

    public function getParentFolderListBySystem() {
        
        $join = null;

        if (Ue::sessionIsUseFolderPermission()) {
            
            $sessionUserKeyId = Ue::sessionUserKeyId();
            
            if ($sessionUserKeyId != 1) {
                //$join = 'INNER JOIN FVM_FOLDER_USER_PERMISSION FB ON FB.FOLDER_ID = FF.FOLDER_ID AND FB.USER_ID = '.$sessionUserKeyId;
                $join = 'INNER JOIN (
                            SELECT 
                                FF.FOLDER_ID 
                            FROM FVM_FOLDER FF 
                            WHERE FF.PARENT_FOLDER_ID IS NULL 
                                START WITH FF.FOLDER_ID IN ( 
                                    SELECT 
                                        COALESCE(FF.FOLDER_ID, MF.FOLDER_ID) AS FOLDER_ID 
                                    FROM FVM_FOLDER_USER_PERMISSION FF 
                                        LEFT JOIN META_DATA_FOLDER_MAP MF ON MF.META_DATA_ID = FF.META_DATA_ID 
                                    WHERE FF.USER_ID = '.$sessionUserKeyId.' 
                                    GROUP BY FF.FOLDER_ID, MF.FOLDER_ID 
                                ) 
                                CONNECT BY NOCYCLE FF.FOLDER_ID = PRIOR FF.PARENT_FOLDER_ID 
                            GROUP BY FF.FOLDER_ID 
                        ) FB ON FB.FOLDER_ID = FF.FOLDER_ID';
            }
        }
        
        $data = $this->db->GetAll("
            SELECT 
                FF.FOLDER_ID, 
                FF.FOLDER_CODE, 
                FF.FOLDER_NAME, 
                FF.CREATED_DATE, 
                FF.IS_ACTIVE,
                " . $this->db->IfNull('BP.FIRST_NAME', 'UM.USERNAME') . " AS CREATED_PERSON_NAME 
            FROM FVM_FOLDER FF 
                $join 
                LEFT JOIN UM_SYSTEM_USER UM ON UM.USER_ID = FF.CREATED_USER_ID 
                LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = UM.PERSON_ID 
            WHERE FF.PARENT_FOLDER_ID IS NULL 
                AND FF.FOLDER_TYPE = 'STATIC' 
            ORDER BY FF.FOLDER_NAME ASC");

        if ($data) {
            $data = Arr::sortBy('FOLDER_NAME', $data, 'asc');
            return $data;
        }
        
        return null;
    }

    public function getNotFolderMetaListBySystem() {

        if (!Ue::sessionIsUseFolderPermission()) { 
            
            $page = Input::post('page', 1);
            $rows = 100;
            $offset = ($page - 1) * $rows;
            
            $data = $this->db->SelectLimit("
                SELECT 
                    MD.META_DATA_ID, 
                    MD.META_DATA_CODE, 
                    MD.META_DATA_NAME, 
                    MD.CREATED_DATE, 
                    MD.META_TYPE_ID, 
                    LOWER(MT.META_TYPE_CODE) AS META_TYPE_CODE, 
                    " . $this->db->IfNull('BP.FIRST_NAME', 'UM.USERNAME') . " AS CREATED_PERSON_NAME, 
                    DI.META_ICON_CODE, 
                    BL.BOOKMARK_URL, 
                    BL.TARGET AS BOOKMARK_TARGET, 
                    RL.REPORT_MODEL_ID, 
                    '' AS FOLDER_ID, 
                    GL.GROUP_TYPE, 
                    MD.IS_ACTIVE
                FROM 
                    (
                        SELECT 
                            MDD.META_DATA_ID, 
                            MDD.META_DATA_CODE, 
                            MDD.META_DATA_NAME, 
                            MDD.META_TYPE_ID, 
                            MDD.IS_ACTIVE,  
                            MDD.CREATED_USER_ID, 
                            MDD.CREATED_DATE, 
                            MDD.META_ICON_ID 
                        FROM META_DATA MDD 
                            LEFT JOIN META_DATA_FOLDER_MAP FMM ON FMM.META_DATA_ID = MDD.META_DATA_ID 
                        WHERE FMM.FOLDER_ID IS NULL 
                    ) MD 
                    INNER JOIN META_TYPE MT ON MT.META_TYPE_ID = MD.META_TYPE_ID 
                    LEFT JOIN UM_SYSTEM_USER UM ON UM.USER_ID = MD.CREATED_USER_ID 
                    LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = UM.PERSON_ID 
                    LEFT JOIN META_DATA_ICON DI ON DI.META_ICON_ID = MD.META_ICON_ID 
                    LEFT JOIN META_BOOKMARK_LINKS BL ON BL.META_DATA_ID = MD.META_DATA_ID 
                    LEFT JOIN META_REPORT_LINK RL ON RL.META_DATA_ID = MD.META_DATA_ID 
                    LEFT JOIN META_GROUP_LINK GL ON GL.META_DATA_ID = MD.META_DATA_ID 
                ORDER BY 
                    MD.META_DATA_CODE, 
                    MD.META_DATA_NAME 
                ASC", $rows, $offset);

            if (isset($data->_array)) {
                return $data->_array;
            }
        }
        
        return null;
    }

    public function getNotFolderMetaListByType($typeIds) {
        $typeIds = Input::param($typeIds);
        $data = $this->db->GetAll("
            SELECT 
                " . ((DB_DRIVER == 'oci8') ? '/*+ ORDERED */' : '') . "
                MD.META_DATA_ID, 
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME, 
                MD.CREATED_DATE, 
                MD.META_TYPE_ID,
                LOWER(MT.META_TYPE_CODE) AS META_TYPE_CODE, 
                BP.FIRST_NAME, 
                UM.USERNAME, 
                DI.META_ICON_CODE, 
                BL.BOOKMARK_URL, 
                BL.TARGET AS BOOKMARK_TARGET, 
                RL.REPORT_MODEL_ID 
            FROM META_DATA MD 
                INNER JOIN META_TYPE MT ON MT.META_TYPE_ID = MD.META_TYPE_ID 
                LEFT JOIN UM_SYSTEM_USER UM ON UM.USER_ID = MD.CREATED_USER_ID 
                LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = UM.PERSON_ID 
                LEFT JOIN META_DATA_ICON DI ON DI.META_ICON_ID = MD.META_ICON_ID 
                LEFT JOIN META_BOOKMARK_LINKS BL ON BL.META_DATA_ID = MD.META_DATA_ID 
                LEFT JOIN META_REPORT_LINK RL ON RL.META_DATA_ID = MD.META_DATA_ID 
            WHERE 
                NOT EXISTS (SELECT MDFM.META_DATA_ID FROM META_DATA_FOLDER_MAP MDFM WHERE MDFM.META_DATA_ID = MD.META_DATA_ID) 
                AND MD.IS_ACTIVE = 1 
                AND MD.META_TYPE_ID IN ($typeIds) 
            ORDER BY 
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME 
            ASC");

        if ($data) {
            return $data;
        }
        return null;
    }

    public static function getCrumbs($this_fol_id, $flarn, $keep_fol_id) {
        
        global $db;
        
        if (!isset($this_fol_id)) {
            $this_fol_id = '0';
        }
        
        $folderIdPh = $db->Param(0);
        $bindVars = array($db->addQ($this_fol_id));
        
        $val = $db->GetRow("SELECT FOLDER_ID, PARENT_FOLDER_ID, FOLDER_NAME FROM FVM_FOLDER WHERE FOLDER_ID = $folderIdPh", $bindVars);
        $html = '';

        if ($val) {

            $cat_id_array[$flarn] = $val['FOLDER_ID'];

            if ($cat_id_array[$flarn] > 0) {
                
                $cat_parent_id_array[$flarn] = $val['PARENT_FOLDER_ID'];
                $cat_name_array[$flarn] = $val['FOLDER_NAME'];
            
                $next = $flarn + 1;
                $html .= self::getCrumbs($cat_parent_id_array[$flarn], $next, $keep_fol_id);

                $html .= '<a href="javascript:;" onclick="childRecordView(\'' . $cat_id_array[$flarn] . '\', \'folder\');" data-folder-id="' . $cat_id_array[$flarn] . '" class="breadcrumb-item py-1" tabindex="-1">' . $cat_name_array[$flarn] . '</a>';
            }
        }

        return $html;
    }

    public function getParentFolderBySystem($folderId) {
        
        $folderIdPh = $this->db->Param(0);
        $bindVars = array($this->db->addQ($folderId));
        
        $row = $this->db->GetRow("SELECT PARENT_FOLDER_ID FROM FVM_FOLDER WHERE FOLDER_ID = $folderIdPh", $bindVars);
        
        return $row;
    }

    public function getChildFolderListBySystem($folderId) {
        
        $parentFolderId = $this->db->Param(0);
        $join = null;
        
        if (Ue::sessionIsUseFolderPermission()) {
            
            $sessionUserKeyId = Ue::sessionUserKeyId();
            
            if ($sessionUserKeyId != 1) {
                $join = "INNER JOIN ( 
                    
                            SELECT 
                                FF.FOLDER_ID  
                            FROM (
                                
                                SELECT 
                                    FF.FOLDER_ID  
                                FROM FVM_FOLDER_USER_PERMISSION FFP 
                                    INNER JOIN FVM_FOLDER FF ON FF.PARENT_FOLDER_ID = FFP.FOLDER_ID 
                                WHERE FF.PARENT_FOLDER_ID = $parentFolderId 
                                    AND FFP.USER_ID = $sessionUserKeyId  
                                GROUP BY FF.FOLDER_ID 
                                
                                UNION 
                                
                                SELECT 
                                    FF.FOLDER_ID 
                                FROM FVM_FOLDER FF 
                                WHERE FF.PARENT_FOLDER_ID = $parentFolderId  
                                    START WITH FF.FOLDER_ID IN ( 
                                        SELECT 
                                            FOLDER_ID 
                                        FROM FVM_FOLDER_USER_PERMISSION 
                                        WHERE USER_ID = $sessionUserKeyId  
                                        GROUP BY FOLDER_ID  
                                    ) 
                                    CONNECT BY NOCYCLE FF.PARENT_FOLDER_ID = PRIOR FF.FOLDER_ID 
                                
                                UNION 
                                
                                SELECT 
                                    FF.FOLDER_ID 
                                FROM FVM_FOLDER FF 
                                WHERE FF.PARENT_FOLDER_ID = $parentFolderId  
                                    START WITH FF.FOLDER_ID IN ( 
                                        SELECT 
                                            COALESCE(MF.FOLDER_ID, FF.FOLDER_ID) AS FOLDER_ID   
                                        FROM FVM_FOLDER_USER_PERMISSION FF 
                                            LEFT JOIN META_DATA_FOLDER_MAP MF ON MF.META_DATA_ID = FF.META_DATA_ID 
                                        WHERE FF.USER_ID = $sessionUserKeyId  
                                        GROUP BY MF.FOLDER_ID, FF.FOLDER_ID  
                                    ) 
                                    CONNECT BY NOCYCLE FF.FOLDER_ID = PRIOR FF.PARENT_FOLDER_ID 
                            ) FF 
                            GROUP BY FF.FOLDER_ID 
                        ) FB ON FB.FOLDER_ID = FF.FOLDER_ID";
            }
        }
        
        $data = $this->db->GetAll("
            SELECT 
                FF.FOLDER_ID, 
                FF.FOLDER_CODE, 
                FF.FOLDER_NAME, 
                FF.CREATED_DATE, 
                FF.IS_ACTIVE, 
                " . $this->db->IfNull('BP.FIRST_NAME', 'UM.USERNAME') . " AS CREATED_PERSON_NAME 
            FROM FVM_FOLDER FF 
                $join 
                LEFT JOIN UM_SYSTEM_USER UM ON UM.USER_ID = FF.CREATED_USER_ID 
                LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = UM.PERSON_ID 
            WHERE FF.PARENT_FOLDER_ID = $parentFolderId 
                AND FF.FOLDER_TYPE = 'STATIC' 
            ORDER BY FF.FOLDER_NAME ASC", 
            array($folderId) 
        );

        if ($data) {
            $data = Arr::sortBy('FOLDER_NAME', $data, 'asc');
            return $data;
        }
        
        return null;
    }

    public function getFolderWithMetaListBySystem($folderId) {
        
        $page = Input::post('page', 1);
        $rows = 100;
        $offset = ($page - 1) * $rows;
        $folderIdPh = $this->db->Param(0);
        $join = null;
        
        if (Ue::sessionIsUseFolderPermission()) {
            $sessionUserKeyId = Ue::sessionUserKeyId();
            
            if ($sessionUserKeyId != 1) {
                $join = "INNER JOIN ( 
                    
                            SELECT 
                                FF.META_DATA_ID 
                            FROM (
                                
                                SELECT 
                                    FF.META_DATA_ID  
                                FROM FVM_FOLDER_USER_PERMISSION FFP 
                                    INNER JOIN META_DATA_FOLDER_MAP FF ON FF.FOLDER_ID = FFP.FOLDER_ID 
                                WHERE FFP.FOLDER_ID = $folderIdPh 
                                    AND FFP.USER_ID = $sessionUserKeyId 
                                GROUP BY FF.META_DATA_ID  
                                
                                UNION 
                                
                                SELECT 
                                    META_DATA_ID 
                                FROM META_DATA_FOLDER_MAP 
                                WHERE FOLDER_ID IN ( 
                                    SELECT 
                                        FF.FOLDER_ID 
                                    FROM FVM_FOLDER FF 
                                    WHERE FF.FOLDER_ID = $folderIdPh 
                                        START WITH FF.FOLDER_ID IN ( 
                                            SELECT 
                                                FOLDER_ID 
                                            FROM FVM_FOLDER_USER_PERMISSION 
                                            WHERE FOLDER_ID IS NOT NULL 
                                                AND USER_ID = $sessionUserKeyId 
                                            GROUP BY FOLDER_ID 
                                        ) 
                                        CONNECT BY NOCYCLE FF.PARENT_FOLDER_ID = PRIOR FF.FOLDER_ID 
                                    GROUP BY FF.FOLDER_ID 
                                )
                                
                                UNION 
                                
                                SELECT 
                                    META_DATA_ID    
                                FROM FVM_FOLDER_USER_PERMISSION FF 
                                WHERE META_DATA_ID IS NOT NULL 
                                    AND USER_ID = $sessionUserKeyId 
                                GROUP BY META_DATA_ID 
                            ) FF 
                            GROUP BY FF.META_DATA_ID 
                        ) FB ON FB.META_DATA_ID = DM.META_DATA_ID";
            }
        }
            
        $data = $this->db->SelectLimit("
            SELECT 
                MD.META_DATA_ID, 
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME,
                MD.META_TYPE_ID,
                LOWER(MT.META_TYPE_CODE) AS META_TYPE_CODE, 
                MD.CREATED_DATE, 
                " . $this->db->IfNull('BP.FIRST_NAME', 'UM.USERNAME') . " AS CREATED_PERSON_NAME, 
                DI.META_ICON_CODE, 
                BL.BOOKMARK_URL, 
                BL.TARGET AS BOOKMARK_TARGET, 
                RL.REPORT_MODEL_ID, 
                DM.FOLDER_ID, 
                GL.GROUP_TYPE,  
                MD.IS_ACTIVE,
                DL.REF_STRUCTURE_ID
            FROM META_DATA MD 
                INNER JOIN META_DATA_FOLDER_MAP DM ON DM.META_DATA_ID = MD.META_DATA_ID 
                $join 
                INNER JOIN META_TYPE MT ON MT.META_TYPE_ID = MD.META_TYPE_ID 
                LEFT JOIN UM_SYSTEM_USER UM ON UM.USER_ID = MD.CREATED_USER_ID 
                LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = UM.PERSON_ID 
                LEFT JOIN META_DATA_ICON DI ON DI.META_ICON_ID = MD.META_ICON_ID  
                LEFT JOIN META_BOOKMARK_LINKS BL ON BL.META_DATA_ID = MD.META_DATA_ID 
                LEFT JOIN META_REPORT_LINK RL ON RL.META_DATA_ID = MD.META_DATA_ID 
                LEFT JOIN META_GROUP_LINK GL ON GL.META_DATA_ID = MD.META_DATA_ID 
                LEFT JOIN META_DATAMART_LINK DL ON DL.META_DATA_ID = MD.META_DATA_ID 
            WHERE DM.FOLDER_ID = $folderIdPh   
            ORDER BY 
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME 
            ASC", $rows, $offset, array($folderId));

        if (isset($data->_array)) {
            return $data->_array;
        }
        return null;
    }

    public function getFolderWithMetaListByType($folderId, $typeIds) {
        $typeIds = Input::param($typeIds);
        $data = $this->db->GetAll("
            SELECT 
                MD.META_DATA_ID, 
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME,
                MD.META_TYPE_ID, 
                LOWER(MT.META_TYPE_CODE) AS META_TYPE_CODE, 
                MD.CREATED_DATE, 
                BP.FIRST_NAME, 
                UM.USERNAME, 
                DI.META_ICON_CODE, 
                BL.BOOKMARK_URL, 
                BL.TARGET AS BOOKMARK_TARGET, 
                RL.REPORT_MODEL_ID,
                MD.IS_ACTIVE
            FROM META_DATA MD 
                INNER JOIN META_DATA_FOLDER_MAP DM ON DM.META_DATA_ID = MD.META_DATA_ID 
                INNER JOIN META_TYPE MT ON MT.META_TYPE_ID = MD.META_TYPE_ID 
                LEFT JOIN UM_SYSTEM_USER UM ON UM.USER_ID = MD.CREATED_USER_ID 
                LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = UM.PERSON_ID 
                LEFT JOIN META_DATA_ICON DI ON DI.META_ICON_ID = MD.META_ICON_ID  
                LEFT JOIN META_BOOKMARK_LINKS BL ON BL.META_DATA_ID = MD.META_DATA_ID 
                LEFT JOIN META_REPORT_LINK RL ON RL.META_DATA_ID = MD.META_DATA_ID 
            WHERE DM.FOLDER_ID = $folderId  
                AND MD.META_TYPE_ID IN ($typeIds) 
            ORDER BY 
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME 
            ASC");

        if ($data) {
            return $data;
        }
        return null;
    }

    public function childFolderSystemModel() {

        $parent = Input::param($_REQUEST['parent']);
        $result = array();
        
        $parentFolderIdPh = $this->db->Param(0);
        
        if ($parent == '#') {
            
            $join = '';
            
            if (Ue::sessionIsUseFolderPermission()) {
            
                $sessionUserKeyId = Ue::sessionUserKeyId();

                if ($sessionUserKeyId != 1) {
                    $join = 'INNER JOIN FVM_FOLDER_USER_PERMISSION FB ON FB.FOLDER_ID = FM.FOLDER_ID AND FB.USER_ID = '.$sessionUserKeyId;
                }
            }
            
            $data = $this->db->GetAll("
                SELECT  
                    FM.FOLDER_ID, 
                    FM.FOLDER_NAME 
                FROM FVM_FOLDER FM 
                    $join 
                WHERE FM.IS_ACTIVE = 1 
                    AND FM.PARENT_FOLDER_ID IS NULL 
                    AND FM.FOLDER_TYPE = 'STATIC' 
                ORDER BY FM.FOLDER_NAME ASC"
            );
            
            foreach ($data as $k => $row) {
                
                $result[$k] = array(
                    'id' => $row['FOLDER_ID'],
                    'text' => $row['FOLDER_NAME'],
                    'type' => 'root'
                );
                
                $checkChild = $this->db->GetAll("
                    SELECT  
                        FOLDER_ID 
                    FROM FVM_FOLDER 
                    WHERE IS_ACTIVE = 1 
                        AND PARENT_FOLDER_ID = $parentFolderIdPh    
                        AND FOLDER_TYPE = 'STATIC'", 
                    array($row['FOLDER_ID']) 
                );
                
                if ($checkChild) {
                    $result[$k] = array_merge($result[$k], array('children' => true));
                }
            }
            
        } else {
            
            $data = $this->db->GetAll("
                SELECT  
                    FOLDER_ID, 
                    FOLDER_NAME 
                FROM FVM_FOLDER 
                WHERE IS_ACTIVE = 1 
                    AND PARENT_FOLDER_ID = $parentFolderIdPh  
                    AND FOLDER_TYPE = 'STATIC' 
                ORDER BY FOLDER_NAME ASC", 
                array($parent) 
            );
            
            foreach ($data as $k => $row) {
                
                $result[$k] = array(
                    'id' => $row['FOLDER_ID'],
                    'text' => $row['FOLDER_NAME']
                );
                
                $checkChild = $this->db->GetAll("
                    SELECT  
                        FOLDER_ID 
                    FROM FVM_FOLDER 
                    WHERE IS_ACTIVE = 1 
                        AND PARENT_FOLDER_ID = $parentFolderIdPh  
                        AND FOLDER_TYPE = 'STATIC'", 
                    array($row['FOLDER_ID']) 
                );
                
                if ($checkChild) {
                    $result[$k] = array_merge($result[$k], array('children' => true));
                }
            }
        }

        $dataList = Arr::sortBy('text', $result, 'asc');
        
        return $dataList;
    }

    public function hasChildFolder($folderId) {
        
        $row = $this->db->GetRow("
            SELECT 
                FOLDER_ID  
            FROM FVM_FOLDER 
            WHERE PARENT_FOLDER_ID = ".$this->db->Param(0)."  
                AND IS_ACTIVE = 1 
                AND FOLDER_TYPE = 'STATIC'", 
            array($folderId)
        );

        if (isset($row['FOLDER_ID'])) {
            return true;
        }
        return false;
    }

    public function getChildFolder($folderId) {
        
        $data = $this->db->GetAll("
            SELECT 
                FF.FOLDER_ID,
                FF.FOLDER_NAME, 
                FF.PARENT_FOLDER_ID AS PARENT_ID 
            FROM FVM_FOLDER FF 
            WHERE FF.IS_ACTIVE = 1 
                AND FF.PARENT_FOLDER_ID = ".$this->db->Param(0)."   
                AND FF.FOLDER_TYPE = 'STATIC' 
            GROUP BY FF.FOLDER_ID, FF.FOLDER_NAME, FF.PARENT_FOLDER_ID", 
            array($folderId) 
        );

        foreach ($data as $row) {
            self::$folderDatas[self::$k]['FOLDER_ID'] = $row['FOLDER_ID'];
            self::$folderDatas[self::$k]['FOLDER_NAME'] = $row['FOLDER_NAME'];
            self::$folderDatas[self::$k]['PARENT_ID'] = $row['PARENT_ID'];
            self::$folderDatas[self::$k]['ROW_TYPE'] = 'folder';
            self::$k++;

            if (self::hasChildFolder($row['FOLDER_ID'])) {
                self::getChildFolder($row['FOLDER_ID']);
            }
        }
        return self::$folderDatas;
    }
    
    public function saveMetaChangingLog($metaDataId) {

        $logData = array(
            'ID' => getUID(), 
            'META_DATA_ID' => $metaDataId, 
            'CREATED_USER_ID' => Ue::sessionUserId(), 
            'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s')
        );

        $this->db->AutoExecute('META_DATA_CHANGING_LOG', $logData);

        return true;
    }

    public function createMetaSystemModuleModel() {
        
        if (!Mdmeta::isAddMetaAccess()) {
            return array('status' => 'error', 'message' => 'Мета нэмэх боломжгүй!');
        }
        
        $metaDataCode = Input::post('metaDataCode');

        if (self::checkMetaDataCodeModel($metaDataCode)) {
            return array('status' => 'error', 'message' => 'Үзүүлэлтийн код давхардаж байна.', 'fieldName' => 'metaDataCode');
        }
        
        $metaTypeId = Input::post('META_TYPE_ID');
        
        if ($metaTypeId == Mdmetadata::$businessProcessMetaTypeId) {
            
            $methodName = Input::post('methodName');
            $sessionUserId = Ue::sessionUserId();
            
            if (in_array(strtolower($methodName), Mdmetadata::$ignoreMethodNames) && $sessionUserId != '144617860666271' && $sessionUserId != '1453998999913') {
                return array('status' => 'error', 'message' => 'Үүсэх боломжгүй процесс байна! Та бүтээгдэхүүн хөгжүүлэлтийн хэлтэсийн захиралд хандана уу.');
            }
        }
        
        $metaDataId = getUID();
        $sessionUserKeyId = Ue::sessionUserKeyId();
        $currentDate = Date::currentDate('Y-m-d H:i:s');

        $data = array(
            'META_DATA_ID' => $metaDataId,
            'META_DATA_CODE' => $metaDataCode,
            'META_DATA_NAME' => Input::post('metaDataName'),
            'DESCRIPTION' => Input::post('description'),
            'META_TYPE_ID' => $metaTypeId,
            'CREATED_USER_ID' => $sessionUserKeyId,
            'MODIFIED_USER_ID' => $sessionUserKeyId,
            'CREATED_DATE' => $currentDate, 
            'MODIFIED_DATE' => $currentDate, 
            'IS_SYSTEM' => 1,
            'IS_ACTIVE' => 1, 
            'META_ICON_ID' => Input::numeric('metaIconId'),
            'HELP_CONTENT_ID' => Input::numeric('helpContentId'),
            'ICON_NAME' => Input::post('metaIconName'),
            'STATUS_ID' => 1 
        );
        $result = $this->db->AutoExecute('META_DATA', $data);

        if ($result) {

            $folderId = null;
            if (Input::postCheck('folderId')) {
                $folderDatas = Input::post('folderId');
                foreach ($folderDatas as $folderId) {
                    if (!empty($folderId)) {
                        $dataFolder = array(
                            'ID' => getUID(),
                            'FOLDER_ID' => $folderId,
                            'META_DATA_ID' => $metaDataId
                        );
                        $this->db->AutoExecute('META_DATA_FOLDER_MAP', $dataFolder);
                    }
                }
            }
            
            if (Input::postCheck('tagId')) {
                foreach ($_POST['tagId'] as $tagId) {
                    if (!empty($tagId)) {
                        $dataTag = array(
                            'ID' => getUID(),
                            'TAG_ID' => $tagId,
                            'META_DATA_ID' => $metaDataId
                        );
                        $this->db->AutoExecute('META_TAG_MAP', $dataTag);
                    }
                }
            }

            if (Input::postCheck('childMetaDataId')) {
                foreach ($_POST['childMetaDataId'] as $ck => $childMetaDataId) {
                    if (!empty($childMetaDataId)) {
                        $dataChildData = array(
                            'ID' => getUID(),
                            'SRC_META_DATA_ID' => $metaDataId,
                            'TRG_META_DATA_ID' => $childMetaDataId,
                            'ORDER_NUM' => ($ck + 1),
                            'PARAM_CODE' => $childMetaDataId
                        );
                        $this->db->AutoExecute('META_META_MAP', $dataChildData);
                    }
                }
            }

            if (Input::postCheck('childMetaBugFixId')) {
                $childMetaBugFixIds = Input::post('childMetaBugFixId');

                foreach ($childMetaBugFixIds as $bf => $childMetaBugFixId) {
                    if (!empty($childMetaBugFixId)) {

                        $dataBugFix = array(
                            'ID' => getUIDAdd($bf), 
                            'META_BUG_FIXING_ID' => $childMetaBugFixId, 
                            'META_DATA_ID' => $metaDataId, 
                            'TYPE_ID' => 1
                        );

                        $this->db->AutoExecute('META_BUG_FIXING_DTL', $dataBugFix);
                    }
                }
            }
            
            if ($metaTypeId == Mdmetadata::$proxyMetaTypeId && Input::postCheck('proxyChildMetaDataId')) { // Proxy
                    
                $isDefaultMap = Input::post('isDefaultMap');

                foreach ($_POST['proxyChildMetaDataId'] as $ck => $proxyChildMetaDataId) {
                    if (!empty($childMetaDataId)) {
                        $dataChildData = array(
                            'ID' => getUID(),
                            'SRC_META_DATA_ID' => $metaDataId,
                            'TRG_META_DATA_ID' => $proxyChildMetaDataId,
                            'IS_DEFAULT' => ($proxyChildMetaDataId == $isDefaultMap) ? 1 : 0, 
                            'ORDER_NUM' => ($ck + 1) 
                        );
                        $this->db->AutoExecute('META_PROXY_MAP', $dataChildData);
                    }
                }
            }

            if ($metaTypeId == Mdmetadata::$bookmarkMetaTypeId) {
                $dataObject = array(
                    'META_BOOKMARK_LINK_ID' => getUID(),
                    'META_DATA_ID' => $metaDataId,
                    'BOOKMARK_URL' => Input::post('SYS_BOOKMARK_NAME'),
                    'TARGET' => Input::post('SYS_BOOKMARK_TARGET')
                );
                $this->db->AutoExecute('META_BOOKMARK_LINKS', $dataObject);
            }

            if ($metaTypeId == Mdmetadata::$businessProcessMetaTypeId) {

                $inputMetaDataId = Input::post('inputMetaDataId');
                
                $dataBusinessProcess = array(
                    'ID' => $metaDataId,
                    'META_DATA_ID' => $metaDataId,
                    'CLASS_NAME' => Input::post('className'),
                    'METHOD_NAME' => $methodName,
                    'INPUT_META_DATA_ID' => $inputMetaDataId,
                    'OUTPUT_META_DATA_ID' => Input::post('outputMetaDataId'),
                    'SERVICE_LANGUAGE_ID' => Input::post('serviceLanguageId'),
                    'WS_URL' => Input::post('wsUrl'),
                    'SUB_TYPE' => Input::post('bp_process_type')
                );
                
                if (Input::post('bp_process_type') == 'external') {
                    $dataBusinessProcess['ACTION_TYPE'] = Input::post('external_action_type');
                } else {

                    $action_type = Input::post('action_type');
                    $action_type = ($action_type == 'duplicate' ? 'get' : $action_type);
                    
                    $dataBusinessProcess['ACTION_TYPE'] = $action_type;
                    $dataBusinessProcess['SYSTEM_META_GROUP_ID'] = Input::post('systemMetaGroupId');
                }
                
                $dataBusinessProcess['THEME'] = Input::post('groupTheme');
                $dataBusinessProcess['PROCESS_NAME'] = Input::post('processName');
                $dataBusinessProcess['ACTION_BTN'] = Input::post('methodActionBtn');
                $dataBusinessProcess['COLUMN_COUNT'] = Input::post('columnCount');
                $dataBusinessProcess['TAB_COLUMN_COUNT'] = Input::post('tabColumnCount');
                $dataBusinessProcess['LABEL_WIDTH'] = Input::post('labelWidth');
                $dataBusinessProcess['WINDOW_HEIGHT'] = Input::post('windowHeight');
                $dataBusinessProcess['WINDOW_SIZE'] = Input::post('windowSize');
                $dataBusinessProcess['WINDOW_TYPE'] = Input::post('windowType');
                $dataBusinessProcess['WINDOW_WIDTH'] = Input::post('windowWidth');
                $dataBusinessProcess['IS_TREEVIEW'] = Input::postCheck('isTreeview') ? 1 : null;
                $dataBusinessProcess['IS_ADDON_PHOTO'] = Input::postCheck('isAddOnPhotoRequired') ? 2 : (Input::postCheck('isAddOnPhoto') ? 1 : null);
                $dataBusinessProcess['IS_ADDON_FILE'] = Input::postCheck('isAddOnFileRequired') ? 2 : (Input::postCheck('isAddOnFile') ? 1 : null);
                $dataBusinessProcess['IS_ADDON_COMMENT'] = Input::postCheck('isAddOnCommentRequired') ? 2 : (Input::postCheck('isAddOnComment') ? 1 : null);
                $dataBusinessProcess['IS_ADDON_COMMENT_TYPE'] = Input::post('isAddOnCommentType');
                $dataBusinessProcess['IS_ADDON_LOG'] = Input::postCheck('isAddOnLogRequired') ? 2 : (Input::postCheck('isAddOnLog') ? 1 : null);
                $dataBusinessProcess['IS_ADDON_RELATION'] = Input::postCheck('isAddonRelationRequired') ? 2 : (Input::postCheck('isAddonRelation') ? 1 : null);
                $dataBusinessProcess['IS_ADDON_WFM_LOG'] = Input::postCheck('isAddonWfmLog') ? 1 : null;
                $dataBusinessProcess['IS_ADDON_WFM_LOG_TYPE'] = Input::post('isAddonWfmLogType');
                $dataBusinessProcess['IS_ADDON_MV_RELATION'] = Input::postCheck('isAddonMvRelationRequired') ? 2 : (Input::postCheck('isAddonMvRelation') ? 1 : null);
                $dataBusinessProcess['REF_META_GROUP_ID'] = Input::post('refMetaGroupId');
                $dataBusinessProcess['SKIN'] = Input::post('skin');
                $dataBusinessProcess['RUN_MODE'] = Input::post('runMode');
                $dataBusinessProcess['IS_SHOW_PREVNEXT'] = Input::postCheck('isShowPrevNext') ? 1 : null;
                $dataBusinessProcess['IS_WIDGET'] = Input::postCheck('isWidget') ? 1 : null;
                $dataBusinessProcess['IS_TOOLS_BTN'] = Input::postCheck('isToolsBtn') ? 1 : null;
                $dataBusinessProcess['IS_SAVE_VIEW_LOG'] = Input::postCheck('isSaveViewLog') ? 1 : null;
                $dataBusinessProcess['MOBILE_THEME'] = Input::post('mobileTheme');
                $dataBusinessProcess['WORKIN_TYPE'] = Input::post('workinType');
                $dataBusinessProcess['IS_RULE'] = Input::post('isRule');
                $dataBusinessProcess['IS_OFFLINE_MODE'] = Input::post('isOfflineMode');
                $dataBusinessProcess['JSON_CONFIG'] = Input::post('jsonConfig');

                $processLinkResult = $this->db->AutoExecute('META_BUSINESS_PROCESS_LINK', $dataBusinessProcess);

                if ($processLinkResult) {
                    self::saveBpInputParamsModel($metaDataId);
                    self::saveBpOutputParamsModel($metaDataId);
                }
            }
            
            if ($metaTypeId == Mdmetadata::$taskFlowMetaTypeId) {
                
                $dataTaskFlow = array(
                    'ID' => $metaDataId,
                    'META_DATA_ID' => $metaDataId
                );
                
                $bpLinkResult = $this->db->AutoExecute('META_BUSINESS_PROCESS_LINK', $dataTaskFlow);
                
                if ($bpLinkResult) {
                    self::saveBpInputParamsModel($metaDataId);
                    self::saveBpOutputParamsModel($metaDataId);
                }
            }

            if ($metaTypeId == Mdmetadata::$dashboardMetaTypeId && Input::isEmpty('chartId') == false) {
                $dataDashboardLink = array(
                    'ID' => getUID(),
                    'META_DATA_ID' => $metaDataId,
                    'CHART_ID' => Input::post('chartId')
                );
                $this->db->AutoExecute('META_DASHBOARD_LINK', $dataDashboardLink);
            }

            if ($metaTypeId == Mdmetadata::$reportMetaTypeId && Input::isEmpty('REPORT_MODEL_ID') == false) {
                $dataReportLink = array(
                    'ID' => getUID(),
                    'META_DATA_ID' => $metaDataId,
                    'REPORT_MODEL_ID' => Input::post('REPORT_MODEL_ID')
                );
                $this->db->AutoExecute('META_REPORT_LINK', $dataReportLink);
            }

            if ($metaTypeId == Mdmetadata::$fieldMetaTypeId && Input::isEmpty('dataType') == false) {
                $dataFieldLink = array(
                    'ID' => getUID(),
                    'META_DATA_ID' => $metaDataId,
                    'DATA_TYPE' => Input::post('dataType'),
                    'IS_SHOW' => ((Input::postCheck('isShow')) ? 1 : 0),
                    'IS_REQUIRED' => ((Input::postCheck('isRequired')) ? 1 : 0),
                    'MIN_VALUE' => (($_POST['minValue'] == '0') ? '0' : Input::post('minValue')),
                    'MAX_VALUE' => Input::post('maxValue'),
                    'DEFAULT_VALUE' => (($_POST['defaultValue'] == '0') ? '0' : Input::post('defaultValue')),
                    'PATTERN_ID' => Input::post('patternId'),
                    'FILE_EXTENSION' => Input::post('fieldFileExtension')
                );
                if (Input::isEmpty('lookupType') == false) {
                    $dataFieldLink['LOOKUP_META_DATA_ID'] = Input::post('lookupMetaDataId');
                    $dataFieldLink['LOOKUP_TYPE'] = Input::post('lookupType');
                    $dataFieldLink['DISPLAY_FIELD'] = Input::post('displayField');
                    $dataFieldLink['VALUE_FIELD'] = Input::post('valueField');
                    $dataFieldLink['CHOOSE_TYPE'] = Input::post('chooseType');
                }
                $this->db->AutoExecute('META_FIELD_LINK', $dataFieldLink);
            }

            if ($metaTypeId == Mdmetadata::$menuMetaTypeId) {

                $dataMenuLink = array(
                    'ID' => getUID(),
                    'META_DATA_ID' => $metaDataId,
                    'MENU_POSITION' => Input::post('menuPosition'),
                    'MENU_ALIGN' => Input::post('menuAlign'),
                    'MENU_THEME' => Input::post('menuTheme'),
                    'MENU_TOOLTIP' => Input::post('menuTooltip'),
                    'ACTION_META_DATA_ID' => Input::post('menuActionMetaDataId'),
                    'VIEW_META_DATA_ID' => Input::post('viewMetaDataId'),
                    'COUNT_META_DATA_ID' => Input::post('menuCountMetaDataId'),
                    'WEB_URL' => Input::post('webUrl'),
                    'URL_TARGET' => Input::post('urlTarget'),
                    'ICON_NAME' => Input::post('menuIconName'),
                    'VIEW_TYPE' => Input::post('viewType'),
                    'IS_SHOW_CARD' => Input::post('isShowCard'),
                    'IS_CONTENT_UI' => Input::post('isContentUi'),
                    'IS_MODULE_SIDEBAR' => Input::post('isModuleSidebar'),
                    'IS_DEFAULT_OPEN' => Input::post('isDefaultOpen'),
                    'IS_OFFLINE_MODE' => Input::post('isOfflineMode'),
                    'GLOBE_CODE' => Input::post('globeCode'),
                    'MENU_CODE' => Input::post('menuCode')
                );
                if (isset($_FILES['menuPhotoName']) && $_FILES['menuPhotoName']['name'] != '') {
                    $newMenuPhotoName = 'metamenu_' . $metaDataId;
                    $menuPhotoExtension = strtolower(substr($_FILES['menuPhotoName']['name'], strrpos($_FILES['menuPhotoName']['name'], '.') + 1));
                    $menuPhotoName = $newMenuPhotoName . '.' . $menuPhotoExtension;
                    Upload::$File = $_FILES['menuPhotoName'];
                    Upload::$method = 0;
                    Upload::$SavePath = UPLOADPATH . 'meta/menu/';
                    Upload::$NewWidth = 186;
                    Upload::$NewName = $newMenuPhotoName;
                    Upload::$OverWrite = true;
                    $menuPhotoUploadError = Upload::UploadFile();
                    if ($menuPhotoUploadError == '') {
                        $dataMenuLink['PHOTO_NAME'] = UPLOADPATH . 'meta/menu/' . $menuPhotoName;
                    }
                }
                $this->db->AutoExecute('META_MENU_LINK', $dataMenuLink);
            }

            if ($metaTypeId == Mdmetadata::$calendarMetaTypeId) {
                $dataCalendarLink = array(
                    'ID' => getUID(),
                    'META_DATA_ID' => $metaDataId,
                    'TRG_META_DATA_ID' => Input::post('targetMetaDataId'),
                    'LINK_META_DATA_ID' => Input::post('linkMetaDataId'),
                    'TITLE' => Input::post('calendarTitle'),
                    'WIDTH' => Input::post('calendarWidth'),
                    'HEIGHT' => Input::post('calendarHeight'),
                    'TEXT_FONT_SIZE' => Input::post('textFontSize'),
                    'COLUMN_PARAM_PATH' => Input::post('columnParamPath'),
                    'START_PARAM_PATH' => Input::post('startDatePath'),
                    'END_PARAM_PATH' => Input::post('endDatePath'),
                    'COLOR_PARAM_PATH' => Input::post('colorPath'),
                    'FILTER_GROUP_PARAM_PATH' => Input::post('filterGroupPath'),
                    'DEFAULT_INTERVAL_ID' => Input::post('defaultIntervalId'),
                    'CREATED_DATE' => Date::currentDate(),
                    'CREATED_USER_ID' => $sessionUserKeyId,
                );
                $this->db->AutoExecute('META_CALENDAR_LINK', $dataCalendarLink);
            }

            if ($metaTypeId == Mdmetadata::$donutMetaTypeId) {
                $processId = Input::post('processId');
                $dataDonutLink = array(
                    'DONUT_ID' => getUID(),
                    'META_DATA_ID' => $metaDataId,
                    'INFO' => Input::post('metaDataName'),
                    'TEXT' => Input::post('text'),
                    'URL' => Input::post('url'),
                    'DIMENSION' => Input::post('dimension'),
                    'FONTSIZE' => Input::post('fontsize'),
                    'WIDTH' => Input::post('width'),
                    'FGCOLOR' => Input::post('fgcolor'),
                    'BGCOLOR' => Input::post('bgcolor'),
                    'FILL' => Input::post('fill'),
                    'META_BUSINESS_PROCESS_LINK_ID' => $processId
                );
                $this->db->AutoExecute('META_DONUT', $dataDonutLink);
            }

            if ($metaTypeId == Mdmetadata::$cardMetaTypeId) {
                $processId = Input::post('processId');
                $dataCardLink = array(
                    'CARD_ID' => getUID(),
                    'META_DATA_ID' => $metaDataId,
                    'TEXT' => Input::post('text'),
                    'TEXT_ALIGN' => Input::post('textAlign'),
                    'URL' => Input::post('url'),
                    'IS_SHOW_URL' => Input::post('isShowUrl'),
                    'TEXT_CSS' => Input::post('textCss'),
                    'WIDTH' => Input::post('width'),
                    'HEIGHT' => Input::post('height'),
                    'BGCOLOR' => Input::post('bgcolor'),
                    'ADDCLASS' => Input::post('addclass'),
                    'FONT_ICON' => Input::post('fontIcon'),
                    'IS_SEE' => Input::post('isSee'),
                    'DATA_VIEW_ID' => Input::post('dataViewId'),
                    'VIEW_NAME' => Input::post('viewName'),
                    'PROCESS_META_DATA_ID' => $processId,
                    'CHART_DATA_VIEW_ID' => Input::post('chartDataViewId'),
                    'CHART_TYPE' => Input::post('chartType'),
                    'COLUMN_NAME' => Input::post('dataViewColumnName'),
                    'AGGREGATE_NAME' => Input::post('setColumnAggregate')
                );
                $this->db->AutoExecute('META_CARD', $dataCardLink);
            }

            if ($metaTypeId == Mdmetadata::$reportTemplateMetaTypeId) {
                $reportTemplate = array(
                    'ID' => getUID(),
                    'META_DATA_ID' => $metaDataId,
                    'DATA_MODEL_ID' => Input::post('metaGroupId'),
                    'DIRECTORY_ID' => Input::post('directoryId'), 
                    'PAGING_CONFIG' => Input::post('pagingConfig'), 
                    'PAGE_MARGIN_TOP' => Str::remove_whitespace(Input::post('pageMarginTop')),
                    'PAGE_MARGIN_LEFT' => Str::remove_whitespace(Input::post('pageMarginLeft')),
                    'PAGE_MARGIN_RIGHT' => Str::remove_whitespace(Input::post('pageMarginRight')),
                    'PAGE_MARGIN_BOTTOM' => Str::remove_whitespace(Input::post('pageMarginBottom')), 
                    'ARCHIVE_WFM_STATUS_CODE' => Input::post('archiveWfmStatusCode'),  
                    'IS_REPORT' => Input::postCheck('isReport') ? 1 : null, 
                    'IS_ARCHIVE' => Input::postCheck('isArchive') ? 1 : null, 
                    'IS_AUTO_ARCHIVE' => Input::postCheck('isAutoArchive') ? 1 : null, 
                    'IS_EMAIL' => Input::postCheck('isEmail') ? 1 : null, 
                    'IS_TABLE_LAYOUT_FIXED' => Input::postCheck('isTableLayoutFixed') ? 1 : null, 
                    'IS_IGNORE_PRINT' => Input::postCheck('isIgnorePrint') ? 1 : null, 
                    'IS_IGNORE_EXCEL' => Input::postCheck('isIgnoreExcel') ? 1 : null, 
                    'IS_IGNORE_PDF' => Input::postCheck('isIgnorePdf') ? 1 : null,
                    'IS_IGNORE_WORD' => Input::postCheck('isIgnoreWord') ? 1 : null,
                    'IS_BLOCKCHAIN_VERIFY' => Input::postCheck('isBlockChainVerify') ? 1 : null
                );
                $htmlFilePath = UPLOADPATH . 'report_template/' . $metaDataId . '.html';
                
                if (file_put_contents($htmlFilePath, Input::postNonTags('htmlContent'))) {
                    $reportTemplate['HTML_FILE_PATH'] = $htmlFilePath;
                }
                
                $result = $this->db->AutoExecute('META_REPORT_TEMPLATE_LINK', $reportTemplate);
                
                if ($result) {
                    $this->db->UpdateClob('META_REPORT_TEMPLATE_LINK', 'CONFIG_STR', Input::post('configStr'), 'ID = '.$reportTemplate['ID']);
                }
            }

            if ($metaTypeId == Mdmetadata::$diagramMetaTypeId) {
                // XML Төрөлтэй юм хадгалах болсон тул түр зуур шууд POST с утга авав. 
                if (isset($_POST['text'])) {
                    $text = str_replace("\r", '', $_POST['text']);
                    $text = str_replace("\n", '', $text);
                    $text = str_replace("  ", '', $text);
                } else {
                    $text = null;
                }
                $processId = Input::post('processId');
                if (is_array(Input::post('yaxis'))) {
                    $yAxis = implode(",", Input::post('yaxis'));
                } else {
                    $yAxis = Input::post('yaxis');
                }
                $dataDiagramLink = array(
                    'ID' => getUID(),
                    'META_DATA_ID' => $metaDataId,
                    'PROCESS_META_DATA_ID' => $processId,
                    'DASHBOARD_TYPE' => Input::post('dashboardType'),
                    'DIAGRAM_TYPE' => Input::post('chartType'),
                    'DIAGRAM_THEME' => Input::post('chartTheme'),
                    'IS_USE_META' => ((Input::postCheck('isUseMeta')) ? 1 : 0),
                    'IS_USE_LEGEND' => ((Input::postCheck('isUseLegend')) ? 1 : 0),
                    'IS_INLINE_LEGEND' => ((Input::postCheck('isInlineLegend')) ? 1 : 0),
                    'IS_USE_CRITERIA' => ((Input::postCheck('isUseCriteria')) ? 1 : 0),
                    'IS_USE_GRAPH' => ((Input::postCheck('isuseGraph')) ? 1 : 0),
                    'IS_USE_LIST' => ((Input::postCheck('isuseList')) ? 1 : 0),
                    'LEGEND_POSITION' => Input::post('chartLegendPos'),
                    'LEGEND_FORMAT' => Input::post('legendFormat'),
                    'MINIMUM_VALUE' => Input::post('valueAxesMin'),
                    'MAXIMUM_VALUE' => Input::post('valueAxesMax'),
                    'COLOR_FIELD' => Input::post('colorField'),
                    'TEXT' => $text,
                    'WIDTH' => (Input::post('width') == '') ? Input::post('width') : Input::post('width'),
                    'HEIGHT' => (Input::post('height') == '') ? Input::post('height') : Input::post('height'),
                    'IS_SHOW_TITLE' => (int) Input::post('isShowTitle'),
                    'IS_VIEW_DATAGRID' => (int) Input::post('isViewDataGrid'),                    
                    'TITLE' => Input::post('chartTitle'),
                    'IS_MULTIPLE' => (int) Input::post('isMultiple'),
                    'IS_SHOW_LABEL' => (int) Input::post('isShowLabel'),
                    'IS_DATA_LABEL' => (int) Input::post('isDataLabel'),
                    'LABEL_STEP' => (int) Input::post('labelStep'),
                    'IS_SHOW_EXPORT' => (int) Input::post('isShowExport'),
                    'IS_X_LABEL' => (int) Input::post('isXLabel'),
                    'IS_Y_LABEL' => (int) Input::post('isYLabel'),
                    'IS_BACKGROUND' => (int) Input::post('isBackground'),
                    'IS_LITTLE' => (int) Input::post('isLittle'),
                    'THEME' => (Input::post('theme') == '') ? Input::post('light') : Input::post('theme'),
                    'XAXIS' => Input::post('xaxis'),
                    'YAXIS' => $yAxis,
                    'XAXISGROUP' => Input::post('xaxisGroup'),
                    'YAXISGROUP' => Input::post('yaxisGroup'),
                    'X_LABEL_ROTATION' => Input::post('xLabelRotation'),
                    'CREATED_USER_ID' => Ue::sessionUserId(),
                    'IS_MULTIPLE_PROCESS' => '0',
                    'XAXIS2' => '',
                    'YAXIS2' => '',
                    'XAXIS3' => '',
                    'YAXIS3' => '',
                    'XAXIS4' => '',
                    'YAXIS4' => '',
                    'PROCESS_META_DATA_ID2' => '',
                    'PROCESS_META_DATA_ID3' => '',
                    'PROCESS_META_DATA_ID4' => '',
                    'VALUE_AXIS_TITLE' => Input::post('valueAxisTitle'),
                    'CATEGORY_AXIS_TITLE' => Input::post('categoryAxisTitle'),
                    'LABEL_TEXT_SUBSTR' => Input::post('labelTextSubstr'),
                    'TEMPLATE_WIDTH' => Input::post('templateWidth'),
                    'COLOR' => Input::post('chartColor'),
                    'COLOR2' => Input::post('chartColor2'),
                    'CREATED_DATE' => Date::currentDate()
                );

                if (Input::postCheck('isMultipleProcess') && $_POST['isMultipleProcess'] === '1') {
                    $dataDiagramLink['IS_MULTIPLE_PROCESS'] = Input::post('isMultipleProcess');
                    $dataDiagramLink['XAXIS2'] = (isset($_POST['xaxis2'])) ? Input::post('xaxis2') : '';
                    $dataDiagramLink['YAXIS2'] = (isset($_POST['yaxis2'])) ? Input::post('yaxis2') : '';
                    $dataDiagramLink['XAXIS3'] = (isset($_POST['xaxis3'])) ? Input::post('xaxis3') : '';
                    $dataDiagramLink['YAXIS3'] = (isset($_POST['yaxis3'])) ? Input::post('yaxis3') : '';
                    $dataDiagramLink['XAXIS4'] = (isset($_POST['xaxis4'])) ? Input::post('xaxis4') : '';
                    $dataDiagramLink['YAXIS4'] = (isset($_POST['yaxis4'])) ? Input::post('yaxis4') : '';
                    $dataDiagramLink['PROCESS_META_DATA_ID2'] = (isset($_POST['processId2'])) ? Input::post('processId2') : '';
                    $dataDiagramLink['PROCESS_META_DATA_ID3'] = (isset($_POST['processId3'])) ? Input::post('processId3') : '';
                    $dataDiagramLink['PROCESS_META_DATA_ID4'] = (isset($_POST['processId4'])) ? Input::post('processId4') : '';
                }

                $this->db->AutoExecute('META_DASHBOARD_LINK', $dataDiagramLink);
                
                $addonSettings = array(
                    'criteriaPosition' => Input::post('chartCriteriaPostion'),
                    'value' => Input::post('pie_charts_bullets_value'),
                    'title' => Input::post('pie_charts_bullets_title'),
                );
                $this->db->UpdateClob('META_DASHBOARD_LINK', 'ADDON_SETTINGS', json_encode($addonSettings), 'META_DATA_ID = '.$metaDataId);
            }

            if ($metaTypeId == Mdmetadata::$metaGroupMetaTypeId) {
                if (Input::isEmpty('groupType') == false) {
                    $dataGroupLink = array(
                        'ID' => getUID(),
                        'META_DATA_ID' => $metaDataId,
                        'LABEL_POSITION' => Input::post('labelPosition'),
                        'COLUMN_COUNT' => Input::post('columnCount'),
                        'LABEL_WIDTH' => Input::post('labelWidth'),
                        'SEARCH_TYPE' => Input::post('searchType'),
                        'FORM_CONTROL' => Input::post('formControl'),
                        'GROUP_TYPE' => Input::post('groupType'),
                        'IS_TREEVIEW' => (Input::postCheck('isTreeview')) ? 1 : null,
                        'WINDOW_TYPE' => Input::post('windowType'),
                        'WINDOW_SIZE' => Input::post('windowSize'),
                        'WINDOW_WIDTH' => Input::post('windowWidth'),
                        'WINDOW_HEIGHT' => Input::post('windowHeight'),
                        'LIST_NAME' => Input::post('listName'),
                        'IS_SKIP_UNIQUE_ERROR' => Input::post('isSkipUniqueError'), 
                        'IS_NOT_GROUPBY' => (Input::postCheck('isNotGroupBy')) ? 1 : null, 
                        'IS_ALL_NOT_SEARCH' => (Input::postCheck('isAllNotSearch')) ? 1 : null,
                        'IS_USE_RT_CONFIG' => (Input::postCheck('isUseRtConfig')) ? 1 : null,
                        'IS_USE_WFM_CONFIG' => (Input::postCheck('isUseWorkFlow')) ? 1 : null,
                        'IS_USE_SIDEBAR' => (Input::postCheck('isUseSidebar')) ? 1 : null,
                        'IS_USE_QUICKSEARCH' => (Input::postCheck('isUseQuickSearch')) ? 1 : null,
                        'IS_USE_RESULT' => (Input::postCheck('isUseResult')) ? 1 : null,
                        'IS_EXPORT_TEXT' => (Input::postCheck('isExportText')) ? 1 : null,
                        'BUTTON_BAR_STYLE' => Input::post('buttonBarStyle'), 
                        'REFRESH_TIMER' => Input::post('refreshTimer'), 
                        'M_CRITERIA_COL_COUNT' => Input::post('criteriaColCount'), 
                        'M_GROUP_CRITERIA_COL_COUNT' => Input::post('criteriaGroupColCount'), 
                        'USE_BASKET' => (Input::postCheck('useBasket')) ? 1 : null, 
                        'IS_COUNTCARD_OPEN' => (Input::postCheck('isCountCartOpen')) ? 1 : null, 
                        'CALCULATE_PROCESS_ID' => Input::numeric('calculateProcessId'), 
                        'QS_META_DATA_ID' => Input::numeric('quickSearchDvId'), 
                        'DATA_LEGEND_DV_ID' => Input::numeric('legendDvId'), 
                        'RULE_PROCESS_ID' => Input::numeric('ruleProcessId'), 
                        'IS_IGNORE_EXCEL_EXPORT' => (Input::postCheck('isIgnoreExcelExport')) ? 1 : null, 
                        'IS_USE_DATAMART' => (Input::postCheck('isUseDataMart')) ? 1 : null, 
                        'IS_CRITERIA_ALWAYS_OPEN' => (Input::postCheck('isCriteriaAlwaysOpen')) ? 1 : null, 
                        'IS_ENTER_FILTER' => (Input::postCheck('isEnterFilter')) ? 1 : null, 
                        'IS_FILTER_LOG' => (Input::postCheck('isFilterLog')) ? 1 : null,
                        'IS_IGNORE_SORTING' => (Input::postCheck('isIgnoreSorting')) ? 1 : null,
                        'IS_IGNORE_WFM_HISTORY' => (Input::postCheck('isIgnoreWfmHistory')) ? 1 : null,
                        'IS_DIRECT_PRINT' => (Input::postCheck('isDirectPrint')) ? 1 : null,
                        'LIST_MENU_NAME' => Input::post('listMenuName'), 
                        'SHOW_POSITION' => Input::post('showPosition'), 
                        'IS_LOOKUP_BY_THEME' => (Input::postCheck('lookupTheme')) ? 1 : null, 
                        'EXTERNAL_META_DATA_ID' => Input::post('externalMetaDataId'), 
                        'WS_URL' => Input::post('wsUrl'), 
                        'PANEL_TYPE' => Input::post('panelType'), 
                        'IS_PARENT_FILTER' => Input::postCheck('isParentFilter') ? 1 : null, 
                        'IS_USE_SEMANTIC' => Input::postCheck('isUseSemantic') ? 1 : null,
                        'IS_USE_BUTTON_MAP' => Input::postCheck('isUseButtonMap') ? 1 : null, 
                        'IS_USE_COMPANY_DEPARTMENT_ID' => Input::postCheck('isUseCompanyDepartmentId') ? 1 : null, 
                        'IS_SHOW_FILTER_TEMPLATE' => Input::postCheck('isShowFilterTemplate') ? 1 : null, 
                        'IS_FIRST_COL_FILTER' => Input::postCheck('isFirstColFilter') ? 1 : null
                    );
                    $groupLinkResult = $this->db->AutoExecute('META_GROUP_LINK', $dataGroupLink);

                    $this->db->UpdateClob('META_GROUP_LINK', 'TABLE_NAME', (new Mdmetadata())->objectNameCompress(Str::htmlCharToDoubleQuote($_POST['tableName'])), 'META_DATA_ID = '.$metaDataId);
                    $this->db->UpdateClob('META_GROUP_LINK', 'POSTGRE_SQL', (new Mdmetadata())->objectNameCompress(Str::htmlCharToDoubleQuote($_POST['postgreSql'])), 'META_DATA_ID = '.$metaDataId);
                    $this->db->UpdateClob('META_GROUP_LINK', 'MS_SQL', (new Mdmetadata())->objectNameCompress(Str::htmlCharToDoubleQuote($_POST['msSql'])), 'META_DATA_ID = '.$metaDataId);
                }
                
                if ($groupLinkResult) {
                    self::saveGroupParamsModel($metaDataId);
                }
    
                if (Input::postCheck('gridProperties')) {
                    $gridPropertiesData = $_POST['gridProperties'];
                    $gridOptionData = array(
                        'OPTION_ID' => getUID(),
                        'MAIN_META_DATA_ID' => $metaDataId
                    );
                    foreach ($gridPropertiesData as $gridOptionKey => $gridOptionVal) {
                        $gridOptionData[strtoupper($gridOptionKey)] = Input::param($gridOptionVal);
                    }
                    $this->db->AutoExecute('META_GROUP_GRID_OPTIONS', $gridOptionData);
                }
            }

            if ($metaTypeId == Mdmetadata::$workSpaceMetaTypeId) {
                $dataWorkSpaceLink = array(
                    'ID' => getUID(),
                    'META_DATA_ID' => Input::param($metaDataId),
                    'MENU_META_DATA_ID' => Input::post('menuActionMetaDataId'),
                    'SUBMENU_META_DATA_ID' => Input::post('menuQuickMetaDataId'),
                    'GROUP_META_DATA_ID' => Input::post('groupMetaDataId'),
                    'THEME_CODE' => Input::post('themeCode'),
                    'DEFAULT_MENU_ID' => Input::post('defaultMenuId'),
                    'WINDOW_HEIGHT' => Input::post('windowHeight'),
                    'WINDOW_SIZE' => Input::post('windowSize'),
                    'WINDOW_TYPE' => Input::post('windowType'),
                    'WINDOW_WIDTH' => Input::post('windowWidth'),
                    'IS_FLOW' => Input::post('isFlow'),
                    'USE_TOOLTIP' => Input::post('useTooltip'),
                    'USE_PICTURE' => Input::postCheck('usePic') ? 1 : 0,
                    'USE_COVER_PICTURE' => Input::postCheck('useCoverPic') ? 1 : 0,
                    'USE_LEFT_SIDE' => Input::postCheck('useLeftSide') ? 1 : 0,
                    'USE_MENU' => Input::postCheck('isUseMenu') ? 1 : 0, 
                    'CHECK_MODIFIED_CATCH' => Input::postCheck('checkModifiedCatch') ? 1 : 0, 
                    'ACTION_TYPE' => Input::post('actionType'), 
                    'MOBILE_THEME' => Input::post('mobileTheme')
                );

                $this->db->AutoExecute('META_WORKSPACE_LINK', $dataWorkSpaceLink);
            }

            if ($metaTypeId == Mdmetadata::$statementMetaTypeId) {
                $reportType = Input::post('reportType');
                $statementLinkData = array(
                    'ID' => getUID(),
                    'META_DATA_ID' => Input::param($metaDataId),
                    'REPORT_NAME' => Input::post('reportName'),
                    'REPORT_TYPE' => $reportType,
                    'DATA_VIEW_ID' => Input::post('dataViewId'),
                    'PAGE_SIZE' => Input::post('pageSize'),
                    'PAGE_ORIENTATION' => Input::post('pageOrientation'),
                    'PAGE_MARGIN_TOP' => Input::post('pageMarginTop'),
                    'PAGE_MARGIN_LEFT' => Input::post('pageMarginLeft'),
                    'PAGE_MARGIN_RIGHT' => Input::post('pageMarginRight'),
                    'PAGE_MARGIN_BOTTOM' => Input::post('pageMarginBottom'),
                    'PAGE_HEIGHT' => Input::post('pageHeight'),
                    'PAGE_WIDTH' => Input::post('pageWidth'), 
                    'FONT_FAMILY' => Input::post('fontFamily'), 
                    'RENDER_TYPE' => Input::post('renderType'), 
                    'IS_ARCHIVE' => Input::postCheck('isArchive') ? 1 : null, 
                    'IS_HDR_REPEAT_PAGE' => Input::postCheck('isHdrRepeatPage') ? 1 : null,
                    'IS_NOT_PAGE_BREAK' => Input::postCheck('isNotPageBreak') ? 1 : null,
                    'IS_BLANK' => Input::postCheck('isBlank') ? 1 : null,
                    'IS_SHOW_DV_BTN' => Input::postCheck('isShowDvBtn') ? 1 : null,
                    'IS_USE_SELF_DV' => Input::postCheck('isUseSelfDv') ? 1 : null,
                    'IS_AUTO_FILTER' => Input::postCheck('isAutoFilter') ? 1 : null,
                    'IS_GROUP_MERGE' => Input::postCheck('isGroupMerge') ? 1 : null,
                    'IS_TIMETABLE' => Input::postCheck('isTimetable') ? 1 : null,
                    'IS_EXPORT_NO_FOOTER' => Input::postCheck('isExportNoFooter') ? 1 : null,
                    'PROCESS_META_DATA_ID' => Input::post('calcProcessId')
                );

                $this->db->AutoExecute('META_STATEMENT_LINK', $statementLinkData);

                $this->db->UpdateClob('META_STATEMENT_LINK', 'REPORT_HEADER', Input::postWithDoubleSpace('reportHeader'), 'META_DATA_ID = '.$metaDataId);
                $this->db->UpdateClob('META_STATEMENT_LINK', 'PAGE_HEADER', Input::postWithDoubleSpace('pageHeader'), 'META_DATA_ID = '.$metaDataId);
                $this->db->UpdateClob('META_STATEMENT_LINK', 'REPORT_DETAIL', Input::postWithDoubleSpace('reportDetail'), 'META_DATA_ID = '.$metaDataId);
                $this->db->UpdateClob('META_STATEMENT_LINK', 'PAGE_FOOTER', Input::postWithDoubleSpace('pageFooter'), 'META_DATA_ID = '.$metaDataId);
                $this->db->UpdateClob('META_STATEMENT_LINK', 'REPORT_FOOTER', Input::postWithDoubleSpace('reportFooter'), 'META_DATA_ID = '.$metaDataId);

                if (Input::isEmpty('groupFieldPath') == false) {
                    $groupFieldPathData = Input::post('groupFieldPath');

                    foreach ($groupFieldPathData as $skr => $groupFieldPath) {
                        $stateLinkId = getUID();
                        $statementLinkGroupData = array(
                            'ID' => $stateLinkId,
                            'META_DATA_ID' => Input::param($metaDataId),
                            'GROUP_FIELD_PATH' => Input::param($groupFieldPath),
                            'GROUP_ORDER' => Input::param($_POST['groupOrderNum'][$skr]), 
                            'META_STATEMENT_LINK_ID' => $statementLinkData['ID']
                        );
                        $this->db->AutoExecute('META_STATEMENT_LINK_GROUP', $statementLinkGroupData);

                        $this->db->UpdateClob('META_STATEMENT_LINK_GROUP', 'GROUP_HEADER', Input::paramWithDoubleSpace($_POST['groupHeader'][$skr]), 'ID = '.$stateLinkId);
                        $this->db->UpdateClob('META_STATEMENT_LINK_GROUP', 'GROUP_FOOTER', Input::paramWithDoubleSpace($_POST['groupFooter'][$skr]), 'ID = '.$stateLinkId);
                    }
                }
            }

            if ($metaTypeId == Mdmetadata::$packageMetaTypeId) {
                
                $packageLinkData = array(
                    'ID' => getUID(),
                    'META_DATA_ID' => Input::param($metaDataId),
                    'RENDER_TYPE' => Input::post('renderType'), 
                    'IS_IGNORE_MAIN_TITLE' => Input::postCheck('isIgnoreMainTitle') ? 1 : null, 
                    'MOBILE_THEME' => Input::post('mobileTheme'), 
                    'SPLIT_COLUMN' => Input::post('split_column'), 
                    'PACKAGE_CLASS' => Input::post('package_class'), 
                    'IS_IGNORE_PACKAGE_TITLE' => Input::postCheck('isIgnorePackageTitle') ? 1 : null,
                    'IS_FILTER_BTN_SHOW' => Input::postCheck('isFilterShowButton') ? 1 : null,
                    'COUNT_META_DATA_ID' => Input::post('countMetaDataId'),
                    'TAB_BACKGROUND_COLOR' => Input::post('tabBackgroundColor'), 
                    'IS_CHECK_PERMISSION' => Input::postCheck('isPermission') ? 1 : null, 
                    'IS_REFRESH' => Input::postCheck('isRefresh') ? 1 : null
                );

                $this->db->AutoExecute('META_PACKAGE_LINK', $packageLinkData);
            }

            if ($metaTypeId == Mdmetadata::$layoutMetaTypeId) {

                $layoutLinkId = getUID();
                $dataLayoutMapLink = array(
                    'ID' => $layoutLinkId,
                    'META_DATA_ID' => Input::param($metaDataId),
                    'THEME_CODE' => Input::post('themeCode'),
                    'REFRESH_TIMER' => Input::post('refreshTimerLayout'),
                    'USE_BORDER' => Input::postCheck('useBorder') ? 1 : 0,
                    'CRITERIA_DATA_VIEW_ID' => Input::post('dataViewIdLayout'),
                    'IS_HIDE_BUTTON' => Input::postCheck('isHideButton') ? 1 : 0,
                );
                $this->db->AutoExecute('META_LAYOUT_LINK', $dataLayoutMapLink);

                foreach ($_POST['layoutPath'] as $k => $row) {
                    $layoutParamMapId = getUID();
                    $dataLayoutParam = array(
                        'ID' => $layoutParamMapId,
                        'LAYOUT_PATH' => Input::param($row),
                        'BP_META_DATA_ID' => Input::param($_POST['bpMetaDataId'][$k]),
                        'META_LAYOUT_LINK_ID' => $layoutLinkId,
                        'ORDER_NUM' => Input::param($_POST['orderNum'][$k])
                    );
                    $this->db->AutoExecute('META_LAYOUT_PARAM_MAP', $dataLayoutParam);                     
                }
            }
            
            if ($metaTypeId == Mdmetadata::$bpmMetaTypeId) {
                
                $bpmLinkData = array(
                    'ID' => getUID(),
                    'META_DATA_ID' => $metaDataId
                );

                $this->db->AutoExecute('META_BPM_LINK', $bpmLinkData);
                
                $graphXml = Input::postNonTags('graphXml');
                
                if ($graphXml) {
                    $this->db->UpdateClob('META_BPM_LINK', 'GRAPH_XML', $graphXml, 'META_DATA_ID = '.$metaDataId);
                }
            }

            if (isset($_FILES['meta_file'])) {
                $file_arr = Arr::arrayFiles($_FILES['meta_file']);
                $fileData = Input::post('meta_file_name');
                foreach ($fileData as $f => $file) {
                    if ($file_arr[$f]['name'] != '') {
                        $newFileName = "file_" . getUID() . $f;
                        $fileExtension = strtolower(substr($file_arr[$f]['name'], strrpos($file_arr[$f]['name'], '.') + 1));
                        $fileName = $newFileName . "." . $fileExtension;
                        FileUpload::SetFileName($fileName);
                        FileUpload::SetTempName($file_arr[$f]['tmp_name']);
                        FileUpload::SetUploadDirectory(UPLOADPATH . "meta/file/");
                        FileUpload::SetValidExtensions(explode(",", Config::getFromCache('CONFIG_FILE_EXT')));
                        FileUpload::SetMaximumFileSize(10485760); //10mb
                        $uploadResult = FileUpload::UploadFile();

                        if ($uploadResult) {
                            $attachFileId = getUID();
                            $dataAttachFile = array(
                                'ATTACH_ID' => $attachFileId,
                                'ATTACH_NAME' => ((empty($file)) ? $file_arr[$f]['name'] : $file),
                                'ATTACH' => UPLOADPATH . "meta/file/" . $fileName,
                                'FILE_EXTENSION' => $fileExtension,
                                'FILE_SIZE' => $file_arr[$f]['size'],
                                'CREATED_USER_ID' => Ue::sessionUserKeyId()
                            );
                            $attachFile = $this->db->AutoExecute('FILE_ATTACH', $dataAttachFile);
                            if ($attachFile) {
                                $dataMetaFile = array(
                                    'META_DATA_ID' => $metaDataId,
                                    'ATTACH_ID' => $attachFileId
                                );
                                $this->db->AutoExecute('META_DATA_ATTACH', $dataMetaFile);
                            }
                        }
                    }
                }
            }

            return array('folderId' => $folderId, 'metaDataId' => $metaDataId);
        }

        return false;
    }
    
    public function updateMetaSystemModuleModel() {
        
        try {
        
            $metaDataId = Input::numeric('metaDataId');
        
            if (!$metaDataId) {
                return array('status' => 'error', 'message' => 'Invalid id!');
            }

            $sessionUserId = Ue::sessionUserId();
            $metaTypeId = Input::numeric('META_TYPE_ID', Input::numeric('meta_type_id'));

            $checkLock = self::checkMetaLock($metaDataId, $sessionUserId, $metaTypeId);

            if ($checkLock) {
                return $checkLock;
            }

            if (Input::postCheck('metaDataCode')) {

                $metaDataCode = Input::post('metaDataCode');

                if (self::checkMetaDataCodeByUpdateModel($metaDataId, $metaDataCode)) {
                    return array('status' => 'error', 'message' => 'Үзүүлэлтийн код давхардаж байна.', 'fieldName' => 'metaDataCode');
                }

            } else {
                return array('status' => 'error', 'message' => 'Буруу хандалт байна.');
            }

            if ($metaTypeId == Mdmetadata::$businessProcessMetaTypeId) {

                $methodName = Input::post('methodName');
                $oldMethodName = Input::post('oldMethodName');

                if (!in_array(strtolower($oldMethodName), Mdmetadata::$ignoreMethodNames) && in_array(strtolower($methodName), Mdmetadata::$ignoreMethodNames) && $sessionUserId != '144617860666271' && $sessionUserId != '1453998999913') {
                    return array('status' => 'error', 'message' => 'Засах боломжгүй процесс байна! Та бүтээгдэхүүн хөгжүүлэлтийн хэлтэсийн захиралд хандана уу.');
                }
            }

            if (!self::checkIgnoreMetaTypeModel($metaTypeId, $metaDataId)) {
                return array('status' => 'error', 'message' => 'Танд эрх олгогдоогүй байна.');
            }

            $data = array(
                'META_DATA_NAME' => Input::post('META_DATA_NAME'),
                'DESCRIPTION' => Input::post('DESCRIPTION'),
                'META_TYPE_ID' => $metaTypeId,
                'META_ICON_ID' => Input::numeric('metaIconId'),
                'HELP_CONTENT_ID' => Input::numeric('helpContentId'),
                'ICON_NAME' => Input::post('metaIconName'),
                'MODIFIED_USER_ID' => $sessionUserId,
                'MODIFIED_DATE' => Date::currentDate('Y-m-d H:i:s')
            );

            if (isset($metaDataCode)) {
                $data['META_DATA_CODE'] = $metaDataCode;
            }

            $result = $this->db->AutoExecute('META_DATA', $data, 'UPDATE', 'META_DATA_ID = ' . $metaDataId);

            if ($result) {

                $oldMetaTypeId = Input::numeric('oldMetaTypeId', $metaTypeId);
                $folderId = null;

                if (Input::post('isFolderManage') == '1') {

                    self::clearMetaFolderMap($metaDataId);

                    if (Input::postCheck('folderId')) {
                        foreach ($_POST['folderId'] as $folderId) {
                            if (!empty($folderId)) {
                                $dataFolder = array(
                                    'ID' => getUID(),
                                    'FOLDER_ID' => Input::param($folderId),
                                    'META_DATA_ID' => $metaDataId
                                );
                                $this->db->AutoExecute('META_DATA_FOLDER_MAP', $dataFolder);
                            }
                        }
                    }

                } elseif (Input::postCheck('folderId')) {
                    $folderIds = Input::post('folderId');
                    $folderId = end($folderIds);
                }

                if (Input::post('isTagsManage') == '1') {

                    self::clearMetaTagMap($metaDataId);

                    if (Input::postCheck('tagId')) {
                        foreach ($_POST['tagId'] as $tagId) {
                            if (!empty($tagId)) {
                                $dataTag = array(
                                    'ID' => getUID(),
                                    'TAG_ID' => Input::param($tagId),
                                    'META_DATA_ID' => $metaDataId
                                );
                                $this->db->AutoExecute('META_TAG_MAP', $dataTag);
                            }
                        }
                    }
                }

                if (Input::post('isChildMetaManage') == '1') {

                    self::clearMetaMetaMap($metaDataId);

                    if (Input::postCheck('childMetaDataId')) {
                        $childMetaDatas = Input::post('childMetaDataId');

                        foreach ($childMetaDatas as $ck => $childMetaDataId) {
                            if (!empty($childMetaDataId)) {

                                $dataChildData = array(
                                    'ID' => getUID(),
                                    'SRC_META_DATA_ID' => $metaDataId,
                                    'TRG_META_DATA_ID' => $childMetaDataId,
                                    'ORDER_NUM' => ($ck + 1),
                                    'PARAM_CODE' => $childMetaDataId
                                );

                                if (isset($_POST['mapSecondOrderNum'][$ck])) {
                                    $dataChildData['SECOND_ORDER_NUM'] = Input::param($_POST['mapSecondOrderNum'][$ck]);
                                }

                                $this->db->AutoExecute('META_META_MAP', $dataChildData);
                            }
                        }
                    }
                }

                if (Input::post('isMetaBugFixManage') == '1') {

                    self::clearMetaBugFixDtl($metaDataId);

                    if (Input::postCheck('childMetaBugFixId')) {
                        $childMetaBugFixIds = Input::post('childMetaBugFixId');

                        foreach ($childMetaBugFixIds as $bf => $childMetaBugFixId) {
                            if (!empty($childMetaBugFixId)) {

                                $dataBugFix = array(
                                    'ID' => getUIDAdd($bf),
                                    'META_BUG_FIXING_ID' => $childMetaBugFixId,
                                    'META_DATA_ID' => $metaDataId, 
                                    'TYPE_ID' => 1
                                );

                                $this->db->AutoExecute('META_BUG_FIXING_DTL', $dataBugFix);
                            }
                        }
                    }
                }

                if ($metaTypeId == Mdmetadata::$proxyMetaTypeId) {

                    self::clearMetaProxyMap($metaDataId);

                    if (Input::postCheck('proxyChildMetaDataId')) {

                        $proxyChildMetaDatas = Input::post('proxyChildMetaDataId');
                        $isDefaultMap = Input::post('isDefaultMap');

                        foreach ($proxyChildMetaDatas as $ck => $proxyChildMetaDataId) {
                            if (!empty($proxyChildMetaDataId)) {
                                $dataChildData = array(
                                    'ID' => getUID(),
                                    'SRC_META_DATA_ID' => $metaDataId,
                                    'TRG_META_DATA_ID' => $proxyChildMetaDataId, 
                                    'IS_DEFAULT' => ($proxyChildMetaDataId == $isDefaultMap) ? 1 : 0, 
                                    'ORDER_NUM' => ($ck + 1)
                                );
                                $this->db->AutoExecute('META_PROXY_MAP', $dataChildData);
                            }
                        }
                    }
                } 

                if ($metaTypeId == Mdmetadata::$bookmarkMetaTypeId) {
                    $dataObject = array(
                        'META_BOOKMARK_LINK_ID' => getUID(),
                        'META_DATA_ID' => $metaDataId,
                        'BOOKMARK_URL' => Input::post('SYS_BOOKMARK_NAME'),
                        'TARGET' => Input::post('SYS_BOOKMARK_TARGET')
                    );
                    if (self::isExistsMetaLink('META_BOOKMARK_LINKS', 'META_DATA_ID', $metaDataId, 'META_BOOKMARK_LINK_ID')) {
                        unset($dataObject['META_BOOKMARK_LINK_ID']);
                        $this->db->AutoExecute('META_BOOKMARK_LINKS', $dataObject, 'UPDATE', 'META_DATA_ID = ' . $metaDataId);
                    } else {
                        $this->db->AutoExecute('META_BOOKMARK_LINKS', $dataObject);
                    }
                } 

                if ($metaTypeId == Mdmetadata::$businessProcessMetaTypeId) {

                    $inputMetaDataId = Input::post('inputMetaDataId');

                    $dataBusinessProcess = array(
                        'ID' => $metaDataId,
                        'META_DATA_ID' => $metaDataId,
                        'CLASS_NAME' => Input::post('className'),
                        'METHOD_NAME' => $methodName,
                        'INPUT_META_DATA_ID' => $inputMetaDataId,
                        'OUTPUT_META_DATA_ID' => Input::post('outputMetaDataId'),
                        'SERVICE_LANGUAGE_ID' => Input::post('serviceLanguageId'),
                        'WS_URL' => Input::post('wsUrl'),
                        'SUB_TYPE' => Input::post('bp_process_type')
                    );

                    if (Input::post('bp_process_type') == 'external') {
                        $dataBusinessProcess['ACTION_TYPE'] = Input::post('external_action_type');
                    } else {

                        $action_type = Input::post('action_type');
                        $action_type = ($action_type == 'duplicate' ? 'get' : $action_type);

                        $dataBusinessProcess['ACTION_TYPE'] = $action_type;
                        $dataBusinessProcess['SYSTEM_META_GROUP_ID'] = Input::post('systemMetaGroupId');
                    }

                    $dataBusinessProcess['THEME'] = Input::post('groupTheme');
                    $dataBusinessProcess['PROCESS_NAME'] = Input::post('processName');
                    $dataBusinessProcess['ACTION_BTN'] = Input::post('methodActionBtn');
                    $dataBusinessProcess['COLUMN_COUNT'] = Input::post('columnCount');
                    $dataBusinessProcess['TAB_COLUMN_COUNT'] = Input::post('tabColumnCount');
                    $dataBusinessProcess['LABEL_WIDTH'] = Input::post('labelWidth');
                    $dataBusinessProcess['WINDOW_HEIGHT'] = Input::post('windowHeight');
                    $dataBusinessProcess['WINDOW_SIZE'] = Input::post('windowSize');
                    $dataBusinessProcess['WINDOW_TYPE'] = Input::post('windowType');
                    $dataBusinessProcess['WINDOW_WIDTH'] = Input::post('windowWidth');
                    $dataBusinessProcess['IS_TREEVIEW'] = Input::postCheck('isTreeview') ? 1 : null;
                    $dataBusinessProcess['IS_ADDON_PHOTO'] = Input::postCheck('isAddOnPhotoRequired') ? 2 : (Input::postCheck('isAddOnPhoto') ? 1 : null);
                    $dataBusinessProcess['IS_ADDON_FILE'] = Input::postCheck('isAddOnFileRequired') ? 2 : (Input::postCheck('isAddOnFile') ? 1 : null);
                    $dataBusinessProcess['IS_ADDON_COMMENT'] = Input::postCheck('isAddOnCommentRequired') ? 2 : (Input::postCheck('isAddOnComment') ? 1 : null);
                    $dataBusinessProcess['IS_ADDON_COMMENT_TYPE'] = Input::post('isAddOnCommentType');
                    $dataBusinessProcess['IS_ADDON_LOG'] = Input::postCheck('isAddOnLogRequired') ? 2 : (Input::postCheck('isAddOnLog') ? 1 : null);
                    $dataBusinessProcess['IS_ADDON_RELATION'] = Input::postCheck('isAddonRelationRequired') ? 2 : (Input::postCheck('isAddonRelation') ? 1 : null);
                    $dataBusinessProcess['IS_ADDON_MV_RELATION'] = Input::postCheck('isAddonMvRelationRequired') ? 2 : (Input::postCheck('isAddonMvRelation') ? 1 : null);
                    $dataBusinessProcess['IS_ADDON_WFM_LOG'] = Input::postCheck('isAddonWfmLog') ? 1 : null;
                    $dataBusinessProcess['IS_ADDON_WFM_LOG_TYPE'] = Input::post('isAddonWfmLogType');
                    $dataBusinessProcess['REF_META_GROUP_ID'] = Input::post('refMetaGroupId');
                    $dataBusinessProcess['GETDATA_PROCESS_ID'] = Input::post('getDataProcessId');
                    $dataBusinessProcess['THEME_CODE'] = Input::post('themeCode');
                    $dataBusinessProcess['SKIN'] = Input::post('skin');
                    $dataBusinessProcess['RUN_MODE'] = Input::post('runMode');
                    $dataBusinessProcess['IS_SHOW_PREVNEXT'] = Input::postCheck('isShowPrevNext') ? 1 : null;
                    $dataBusinessProcess['IS_WIDGET'] = Input::postCheck('isWidget') ? 1 : null;
                    $dataBusinessProcess['IS_TOOLS_BTN'] = Input::postCheck('isToolsBtn') ? 1 : null;
                    $dataBusinessProcess['IS_BPMN_TOOL'] = Input::postCheck('isBpmnTool') ? 1 : null;
                    $dataBusinessProcess['IS_SAVE_VIEW_LOG'] = Input::postCheck('isSaveViewLog') ? 1 : null;
                    $dataBusinessProcess['MOBILE_THEME'] = Input::post('mobileTheme');
                    $dataBusinessProcess['WORKIN_TYPE'] = Input::post('workinType');
                    $dataBusinessProcess['IS_RULE'] = Input::post('isRule');
                    $dataBusinessProcess['IS_OFFLINE_MODE'] = Input::post('isOfflineMode');
                    $dataBusinessProcess['JSON_CONFIG'] = Input::post('jsonConfig');

                    if (self::isExistsMetaLink('META_BUSINESS_PROCESS_LINK', 'META_DATA_ID', $metaDataId)) {
                        unset($dataBusinessProcess['ID']);
                        $processLinkResult = $this->db->AutoExecute('META_BUSINESS_PROCESS_LINK', $dataBusinessProcess, 'UPDATE', 'META_DATA_ID = ' . $metaDataId);
                    } else {
                        $processLinkResult = $this->db->AutoExecute('META_BUSINESS_PROCESS_LINK', $dataBusinessProcess);
                    }

                    if (Input::postCheck('fullExpressionString_set')) {
                        $expressionConfig = $this->getBPFullExpressionDefaultVersionModel($metaDataId);

                        if ($expressionConfig) {

                            $this->db->UpdateClob('META_BP_EXPRESSION_DTL', 'EVENT_EXPRESSION_STRING', Input::paramWithDoubleSpace($_POST['fullExpressionString_set']), 'ID = ' . $expressionConfig['CONFIG_ID']); 
                            $this->db->UpdateClob('META_BP_EXPRESSION_DTL', 'LOAD_EXPRESSION_STRING', Input::paramWithDoubleSpace($_POST['fullExpressionOpenCriteria_set']), 'ID = ' . $expressionConfig['CONFIG_ID']); 
                            $this->db->UpdateClob('META_BP_EXPRESSION_DTL', 'VAR_FNC_EXPRESSION_STRING', Input::paramWithDoubleSpace($_POST['fullExpressionStringVarFnc_set']), 'ID = ' . $expressionConfig['CONFIG_ID']); 

                            $afterSave = '';

                            if (!empty($_POST['fullExpressionStringAfterSave_set'])) {
                                $afterSave = "\n".'startAfterSave '.Input::paramWithDoubleSpace($_POST['fullExpressionStringAfterSave_set']).' endAfterSave';
                            }

                            $this->db->UpdateClob('META_BP_EXPRESSION_DTL', 'SAVE_EXPRESSION_STRING', Input::paramWithDoubleSpace($_POST['fullExpressionStringSave_set']).$afterSave, 'ID = ' . $expressionConfig['CONFIG_ID']); 

                            $cacheExpTableName = 'META_BP_EXP_CACHE_VERSION';

                        } else {

                            $this->db->UpdateClob('META_BUSINESS_PROCESS_LINK', 'EVENT_EXPRESSION_STRING', Input::paramWithDoubleSpace($_POST['fullExpressionString_set']), 'META_DATA_ID = ' . $metaDataId); 
                            $this->db->UpdateClob('META_BUSINESS_PROCESS_LINK', 'LOAD_EXPRESSION_STRING', Input::paramWithDoubleSpace($_POST['fullExpressionOpenCriteria_set']), 'META_DATA_ID = ' . $metaDataId); 
                            $this->db->UpdateClob('META_BUSINESS_PROCESS_LINK', 'VAR_FNC_EXPRESSION_STRING', Input::paramWithDoubleSpace($_POST['fullExpressionStringVarFnc_set']), 'META_DATA_ID = ' . $metaDataId); 

                            $afterSave = '';

                            if (!empty($_POST['fullExpressionStringAfterSave_set'])) {
                                $afterSave = "\n".'startAfterSave '.Input::paramWithDoubleSpace($_POST['fullExpressionStringAfterSave_set']).' endAfterSave';
                            }

                            $this->db->UpdateClob('META_BUSINESS_PROCESS_LINK', 'SAVE_EXPRESSION_STRING', Input::paramWithDoubleSpace($_POST['fullExpressionStringSave_set']).$afterSave, 'META_DATA_ID = ' . $metaDataId);                         

                            $cacheExpTableName = 'META_BP_EXP_CACHE';
                        }

                        if (Input::postCheck('cacheId')) {

                            $cacheIdData = $_POST['cacheId'];
                            $currentDate = Date::currentDate('Y-m-d H:i:s');
                            $sessionUserKeyId = Ue::sessionUserKeyId();

                            if ($cacheExpTableName == 'META_BP_EXP_CACHE') {
                                $bpLinkId = $this->db->GetOne("SELECT ID FROM META_BUSINESS_PROCESS_LINK WHERE META_DATA_ID = ".$metaDataId);
                            }

                            foreach ($cacheIdData as $k => $v) {

                                if ($v == '') {

                                    $cacheId = getUID();
                                    $cacheData = array(
                                        'ID' => $cacheId, 
                                        'RUN_MODE' => Input::param($_POST['cacheRunMode'][$k]), 
                                        'GROUP_PATH' => Input::param($_POST['cacheGroupPath'][$k]), 
                                        'CODE' => Input::param($_POST['cacheCode'][$k]), 
                                        'DESCRIPTION' => Input::param($_POST['cacheDescr'][$k]),  
                                        'CREATED_USER_ID' => $sessionUserKeyId, 
                                        'CREATED_DATE' => $currentDate
                                    );
                                    if ($cacheExpTableName == 'META_BP_EXP_CACHE') {
                                        $cacheData['BP_LINK_ID'] = $bpLinkId;
                                    } else {
                                        $cacheData['VERSION_ID'] = $expressionConfig['CONFIG_ID'];
                                    }
                                    $cacheResult = $this->db->AutoExecute($cacheExpTableName, $cacheData);

                                } else {

                                    $cacheId = $v;

                                    if (Input::param($_POST['cacheRowDelete'][$k]) == 'deleted') {

                                        $this->db->Execute("DELETE FROM $cacheExpTableName WHERE ID = $cacheId");
                                        $cacheResult = false;

                                    } else {

                                        $cacheData = array(
                                            'RUN_MODE' => Input::param($_POST['cacheRunMode'][$k]), 
                                            'GROUP_PATH' => Input::param($_POST['cacheGroupPath'][$k]), 
                                            'CODE' => Input::param($_POST['cacheCode'][$k]), 
                                            'DESCRIPTION' => Input::param($_POST['cacheDescr'][$k])
                                        );

                                        $cacheResult = $this->db->AutoExecute($cacheExpTableName, $cacheData, 'UPDATE', 'ID = '.$cacheId);
                                    }
                                }

                                if ($cacheResult) {
                                    $this->db->UpdateClob($cacheExpTableName, 'EXPRESSION_STRING', Input::paramWithDoubleSpace($_POST['cacheExpression'][$k]), 'ID = ' . $cacheId); 
                                }
                            }
                        }

                        (new Mdmeta())->bpFullExpressionUseProcess($metaDataId);
                    }

                    if ($processLinkResult) {

                        self::saveBpInputParamsModel($metaDataId);
                        self::saveBpOutputParamsModel($metaDataId);

                        if (Input::postCheck('saveGetDataProcessParam')) {
                            self::clearMetaBusinessProcessDefaultGetProcessMap($metaDataId);

                            if (Input::postCheck('getDataProcessParamCode')) {
                                $getDataProcessParamData = Input::post('getDataProcessParamCode');
                                foreach ($getDataProcessParamData as $dgv => $dgRow) {
                                    $dataProcessDefaultGetLink = array(
                                        'ID' => getUIDAdd($dgv),
                                        'PROCESS_META_DATA_ID' => $metaDataId,
                                        'GETDATA_PROCESS_ID' => $dataBusinessProcess['GETDATA_PROCESS_ID'],
                                        'PARAM_CODE' => Input::param($dgRow),
                                        'DEFAULT_VALUE' => Input::param($_POST['getDataProcessDefaultValue'][$dgv])
                                    );
                                    $this->db->AutoExecute('META_PROCESS_DEFAULT_GET', $dataProcessDefaultGetLink);
                                }
                            }
                        }
                    }

                    (new Mdmeta())->bpParamsClearCache($metaDataId, (isset($metaDataCode) ? $metaDataCode : self::getMetaDataCodeModel($metaDataId)), true); 
                } 

                if ($metaTypeId == Mdmetadata::$taskFlowMetaTypeId) {

                    $dataTaskFlow = array(
                        'ID' => $metaDataId,
                        'META_DATA_ID' => $metaDataId
                    );

                    if (self::isExistsMetaLink('META_BUSINESS_PROCESS_LINK', 'META_DATA_ID', $metaDataId)) {
                        unset($dataTaskFlow['ID']);
                        $bpLinkResult = $this->db->AutoExecute('META_BUSINESS_PROCESS_LINK', $dataTaskFlow, 'UPDATE', 'META_DATA_ID = ' . $metaDataId);
                    } else {
                        $bpLinkResult = $this->db->AutoExecute('META_BUSINESS_PROCESS_LINK', $dataTaskFlow);
                    }

                    if ($bpLinkResult) {
                        self::saveBpInputParamsModel($metaDataId);
                        self::saveBpOutputParamsModel($metaDataId);
                    }
                }

                if ($metaTypeId == Mdmetadata::$dashboardMetaTypeId) {
                    if (Input::isEmpty('chartId') == false) {
                        $dataDashboardLink = array(
                            'ID' => getUID(),
                            'META_DATA_ID' => $metaDataId,
                            'CHART_ID' => Input::post('chartId')
                        );
                        if (self::isExistsMetaLink('META_DASHBOARD_LINK', 'META_DATA_ID', $metaDataId)) {
                            unset($dataDashboardLink['ID']);
                            $this->db->AutoExecute('META_DASHBOARD_LINK', $dataDashboardLink, 'UPDATE', 'META_DATA_ID = ' . $metaDataId);
                        } else {
                            $this->db->AutoExecute('META_DASHBOARD_LINK', $dataDashboardLink);
                        }
                    }
                } 

                if ($metaTypeId == Mdmetadata::$reportMetaTypeId) {

                    if (Input::isEmpty('REPORT_MODEL_ID') == false) {
                        $dataReportLink = array(
                            'ID' => getUID(),
                            'META_DATA_ID' => $metaDataId,
                            'REPORT_MODEL_ID' => Input::post('REPORT_MODEL_ID')
                        );
                        if (self::isExistsMetaLink('META_REPORT_LINK', 'META_DATA_ID', $metaDataId)) {
                            unset($dataReportLink['ID']);
                            $this->db->AutoExecute('META_REPORT_LINK', $dataReportLink, 'UPDATE', 'META_DATA_ID = ' . $metaDataId);
                        } else {
                            $this->db->AutoExecute('META_REPORT_LINK', $dataReportLink);
                        }
                    }
                } 

                if ($metaTypeId == Mdmetadata::$fieldMetaTypeId) {

                    if (Input::isEmpty('dataType') == false) {

                        $dataFieldLink = array(
                            'ID' => getUID(),
                            'META_DATA_ID' => $metaDataId,
                            'DATA_TYPE' => Input::post('dataType'),
                            'IS_SHOW' => ((Input::postCheck('isShow')) ? 1 : 0),
                            'IS_REQUIRED' => ((Input::postCheck('isRequired')) ? 1 : 0),
                            'MIN_VALUE' => (($_POST['minValue'] == '0') ? '0' : Input::post('minValue')),
                            'MAX_VALUE' => Input::post('maxValue'),
                            'DEFAULT_VALUE' => (($_POST['defaultValue'] == '0') ? '0' : Input::post('defaultValue')),
                            'PATTERN_ID' => Input::post('patternId'),
                            'FILE_EXTENSION' => Input::post('fieldFileExtension')
                        );

                        if (Input::isEmpty('lookupType') == false) {
                            $dataFieldLink['LOOKUP_META_DATA_ID'] = Input::post('lookupMetaDataId');
                            $dataFieldLink['LOOKUP_TYPE'] = Input::post('lookupType');
                            $dataFieldLink['DISPLAY_FIELD'] = Input::post('displayField');
                            $dataFieldLink['VALUE_FIELD'] = Input::post('valueField');
                            $dataFieldLink['CHOOSE_TYPE'] = Input::post('chooseType');
                        }

                        if (self::isExistsMetaLink('META_FIELD_LINK', 'META_DATA_ID', $metaDataId)) {
                            unset($dataFieldLink['ID']);
                            $this->db->AutoExecute('META_FIELD_LINK', $dataFieldLink, 'UPDATE', 'META_DATA_ID = ' . $metaDataId);
                        } else {
                            $this->db->AutoExecute('META_FIELD_LINK', $dataFieldLink);
                        }
                    }
                } 

                if ($metaTypeId == Mdmetadata::$menuMetaTypeId) {

                    $dataMenuLink = array(
                        'ID' => getUID(),
                        'META_DATA_ID' => $metaDataId,
                        'MENU_POSITION' => Input::post('menuPosition'),
                        'MENU_ALIGN' => Input::post('menuAlign'),
                        'MENU_THEME' => Input::post('menuTheme'),
                        'MENU_TOOLTIP' => Input::post('menuTooltip'),
                        'ACTION_META_DATA_ID' => Input::post('menuActionMetaDataId'),
                        'VIEW_META_DATA_ID' => Input::post('viewMetaDataId'),
                        'COUNT_META_DATA_ID' => Input::post('menuCountMetaDataId'),
                        'WEB_URL' => Input::post('webUrl'),
                        'URL_TARGET' => Input::post('urlTarget'),
                        'ICON_NAME' => Input::post('menuIconName'),
                        'PHOTO_NAME' => Input::post('oldMenuPhotoName'),
                        'BG_PHOTO_NAME' => Input::post('oldMenuBgPhotoName'),
                        'MENU_COLOR' => Input::post('menuColor'),
                        'VIEW_TYPE' => Input::post('viewType'),
                        'IS_SHOW_CARD' => Input::post('isShowCard'),
                        'IS_MONPASS_KEY' => Input::post('isMonpassKey'),
                        'IS_CONTENT_UI' => Input::post('isContentUi'),
                        'IS_MODULE_SIDEBAR' => Input::post('isModuleSidebar'),
                        'IS_DEFAULT_OPEN' => Input::post('isDefaultOpen'),
                        'IS_OFFLINE_MODE' => Input::post('isOfflineMode'),
                        'GLOBE_CODE' => Input::post('globeCode'),
                        'MENU_CODE' => Input::post('menuCode')
                    );

                    if (isset($_FILES['menuPhotoName']) && $_FILES["menuPhotoName"]['name'] != '') {

                        $newMenuPhotoName = 'metamenu_' . $metaDataId . '_' . getUID();
                        $menuPhotoExtension = strtolower(substr($_FILES['menuPhotoName']['name'], strrpos($_FILES['menuPhotoName']['name'], '.') + 1));
                        $menuPhotoName = $newMenuPhotoName . '.' . $menuPhotoExtension;
                        FileUpload::SetFileName($menuPhotoName);
                        FileUpload::SetTempName($_FILES['menuPhotoName']['tmp_name']);
                        FileUpload::SetUploadDirectory(UPLOADPATH . 'meta/menu/');
                        FileUpload::SetValidExtensions(explode(',', Config::getFromCache('CONFIG_IMG_EXT')));
                        FileUpload::SetMaximumFileSize(10485760); //10mb
                        $menuPhotoUploadResult = FileUpload::UploadFile();

                        if ($menuPhotoUploadResult) {
                            $dataMenuLink['PHOTO_NAME'] = UPLOADPATH . 'meta/menu/' . $menuPhotoName;
                        }
                    }

                    if (isset($_FILES['menuBgPhotoName']) && $_FILES["menuBgPhotoName"]['name'] != '') {

                        $newMenuPhotoName = 'metamenu_' . $metaDataId . '_' . getUID();
                        $menuPhotoExtension = strtolower(substr($_FILES['menuBgPhotoName']['name'], strrpos($_FILES['menuBgPhotoName']['name'], '.') + 1));
                        $menuPhotoName = $newMenuPhotoName . '.' . $menuPhotoExtension;
                        FileUpload::SetFileName($menuPhotoName);
                        FileUpload::SetTempName($_FILES['menuBgPhotoName']['tmp_name']);
                        FileUpload::SetUploadDirectory(UPLOADPATH . 'meta/menu/');
                        FileUpload::SetValidExtensions(explode(',', Config::getFromCache('CONFIG_IMG_EXT')));
                        FileUpload::SetMaximumFileSize(10485760); //10mb
                        $menuPhotoUploadResult = FileUpload::UploadFile();

                        if ($menuPhotoUploadResult) {
                            $dataMenuLink['BG_PHOTO_NAME'] = UPLOADPATH . 'meta/menu/' . $menuPhotoName;
                        }
                    }

                    if (self::isExistsMetaLink('META_MENU_LINK', 'META_DATA_ID', $metaDataId, 'META_DATA_ID')) {
                        unset($dataMenuLink['ID']);
                        $this->db->AutoExecute('META_MENU_LINK', $dataMenuLink, 'UPDATE', 'META_DATA_ID = ' . $metaDataId);
                    } else {
                        $this->db->AutoExecute('META_MENU_LINK', $dataMenuLink);
                    }
                } 

                if ($metaTypeId == Mdmetadata::$calendarMetaTypeId) {
                    $dataCalendarLink = array(                        
                        'META_DATA_ID' => $metaDataId,
                        'TRG_META_DATA_ID' => Input::post('targetMetaDataId'),
                        'LINK_META_DATA_ID' => Input::post('linkMetaDataId'),
                        'TITLE' => Input::post('calendarTitle'),
                        'WIDTH' => Input::post('calendarWidth'),
                        'HEIGHT' => Input::post('calendarHeight'),
                        'TEXT_FONT_SIZE' => Input::post('textFontSize'),
                        'COLUMN_PARAM_PATH' => Input::post('columnParamPath'),
                        'START_PARAM_PATH' => Input::post('startDatePath'),
                        'END_PARAM_PATH' => Input::post('endDatePath'),     
                        'COLOR_PARAM_PATH' => Input::post('colorPath'),
                        'FILTER_GROUP_PARAM_PATH' => Input::post('filterGroupPath'),
                        'DEFAULT_INTERVAL_ID' => Input::post('defaultIntervalId'),                        
                    );
                    if (self::isExistsMetaLink('META_CALENDAR_LINK', 'META_DATA_ID', $metaDataId)) {
                        $dataCalendarLink = array_merge($dataCalendarLink, array(
                            'MODIFIED_DATE' => Date::currentDate(),
                            'MODIFIED_USER_ID' => Ue::sessionUserKeyId(),
                        ));
                        $this->db->AutoExecute('META_CALENDAR_LINK', $dataCalendarLink, 'UPDATE', 'META_DATA_ID = ' . $metaDataId);
                    } else {
                        $dataCalendarLink = array_merge($dataCalendarLink, array(
                            'ID' => getUID(),
                            'CREATED_DATE' => Date::currentDate(),
                            'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                        ));
                        $this->db->AutoExecute('META_CALENDAR_LINK', $dataCalendarLink);
                    }
                } 

                if ($metaTypeId == Mdmetadata::$contentMetaTypeId) {

                    if (Input::isEmpty('layoutId') == false) {
                        $layoutId = Input::post('layoutId');

                        if (isset($_POST['contentCellId'])) {
                            self::clearContentMapLinkMap($metaDataId);
                            $cellData = $_POST['contentCellId'];
                            foreach ($cellData as $c => $v) {
                                if (!empty($v[0])) {
                                    $dataContentMap = array(
                                        'MAP_ID' => getUID(),
                                        'META_DATA_ID' => $v[0],
                                        'CELL_ID' => $c,
                                        'LAYOUT_ID' => $layoutId,
                                        'SRC_META_DATA_ID' => $metaDataId
                                    );
                                    $this->db->AutoExecute('META_CONTENT_MAP', $dataContentMap);
                                }
                            }
                        }
                        $dataContentLink = array(
                            'ID' => getUID(),
                            'META_DATA_ID' => $metaDataId,
                            'LAYOUT_ID' => $layoutId,
                            'IS_DEFAULT' => 1
                        );
                        if (self::isExistsMetaLink('META_CONTENT_LINK', 'META_DATA_ID', $metaDataId)) {
                            unset($dataContentLink['ID']);
                            $this->db->AutoExecute('META_CONTENT_LINK', $dataContentLink, 'UPDATE', 'META_DATA_ID = ' . $metaDataId);
                        } else {
                            $this->db->AutoExecute('META_CONTENT_LINK', $dataContentLink);
                        }
                    }
                } 

                if ($metaTypeId == Mdmetadata::$donutMetaTypeId) {
                    $processId = Input::post('processId');
                    $dataDonutLink = array(
                        'DONUT_ID' => getUID(),
                        'META_DATA_ID' => $metaDataId,
                        'INFO' => Input::post('META_DATA_NAME'),
                        'TEXT' => Input::post('text'),
                        'URL' => Input::post('url'),
                        'DIMENSION' => Input::post('dimension'),
                        'FONTSIZE' => Input::post('fontsize'),
                        'WIDTH' => Input::post('width'),
                        'FGCOLOR' => Input::post('fgcolor'),
                        'BGCOLOR' => Input::post('bgcolor'),
                        'FILL' => Input::post('fill'),
                        'META_BUSINESS_PROCESS_LINK_ID' => $processId
                    );
                    if (self::isExistsMetaLink('META_DONUT', 'META_DATA_ID', $metaDataId)) {
                        unset($dataDonutLink['DONUT_ID']);
                        $this->db->AutoExecute('META_DONUT', $dataDonutLink, 'UPDATE', 'META_DATA_ID = ' . $metaDataId);
                    } else {
                        $this->db->AutoExecute('META_DONUT', $dataDonutLink);
                    }
                } 

                if ($metaTypeId == Mdmetadata::$cardMetaTypeId) {
                    $processId = Input::post('processId');
                    $dataCardLink = array(
                        'CARD_ID' => getUID(),
                        'META_DATA_ID' => $metaDataId,
                        'TEXT' => Input::post('text'),
                        'TEXT_ALIGN' => Input::post('textAlign'),
                        'URL' => Input::post('url'),
                        'IS_SHOW_URL' => Input::post('isShowUrl'),
                        'TEXT_CSS' => Input::post('textCss'),
                        'WIDTH' => Input::post('width'),
                        'HEIGHT' => Input::post('height'),
                        'BGCOLOR' => Input::post('bgcolor'),
                        'ADDCLASS' => Input::post('addclass'),
                        'FONT_ICON' => Input::post('fontIcon'),
                        'IS_SEE' => Input::post('isSee'),
                        'DATA_VIEW_ID' => Input::post('dataViewId'),
                        'VIEW_NAME' => Input::post('viewName'),
                        'PROCESS_META_DATA_ID' => $processId,
                        'CHART_DATA_VIEW_ID' => Input::post('chartDataViewId'),
                        'CHART_TYPE' => Input::post('chartType'),
                        'COLUMN_NAME'     => Input::post('dataViewColumnName'),
                        'AGGREGATE_NAME'  => Input::post('setColumnAggregate'),
                    );
                    if (self::isExistsMetaLink('META_CARD', 'META_DATA_ID', $metaDataId, 'CARD_ID')) {
                        unset($dataCardLink['CARD_ID']);
                        $this->db->AutoExecute('META_CARD', $dataCardLink, 'UPDATE', 'META_DATA_ID = ' . $metaDataId);
                    } else {
                        $this->db->AutoExecute('META_CARD', $dataCardLink);
                    }
                } 

                if ($metaTypeId == Mdmetadata::$reportTemplateMetaTypeId) {
                    $reportTemplate = array(
                        'ID' => getUID(),
                        'META_DATA_ID' => $metaDataId, 
                        'DATA_MODEL_ID' => Input::post('metaGroupId'), 
                        'GET_MODE' => Input::post('getMode'), 
                        'DIRECTORY_ID' => Input::post('directoryId'),
                        'PAGING_CONFIG' => Input::post('pagingConfig'), 
                        'PAGE_MARGIN_TOP' => Str::remove_whitespace(Input::post('pageMarginTop')),
                        'PAGE_MARGIN_LEFT' => Str::remove_whitespace(Input::post('pageMarginLeft')),
                        'PAGE_MARGIN_RIGHT' => Str::remove_whitespace(Input::post('pageMarginRight')),
                        'PAGE_MARGIN_BOTTOM' => Str::remove_whitespace(Input::post('pageMarginBottom')), 
                        'ARCHIVE_WFM_STATUS_CODE' => Input::post('archiveWfmStatusCode'), 
                        'IS_REPORT' => Input::postCheck('isReport') ? 1 : null, 
                        'IS_ARCHIVE' => Input::postCheck('isArchive') ? 1 : null, 
                        'IS_AUTO_ARCHIVE' => Input::postCheck('isAutoArchive') ? 1 : null, 
                        'IS_EMAIL' => Input::postCheck('isEmail') ? 1 : null, 
                        'IS_TABLE_LAYOUT_FIXED' => Input::postCheck('isTableLayoutFixed') ? 1 : null, 
                        'IS_IGNORE_PRINT' => Input::postCheck('isIgnorePrint') ? 1 : null,
                        'IS_IGNORE_EXCEL' => Input::postCheck('isIgnoreExcel') ? 1 : null,
                        'IS_IGNORE_PDF' => Input::postCheck('isIgnorePdf') ? 1 : null,
                        'IS_IGNORE_WORD' => Input::postCheck('isIgnoreWord') ? 1 : null, 
                        'IS_BLOCKCHAIN_VERIFY' => Input::postCheck('isBlockChainVerify') ? 1 : null
                    );

                    if (Input::postCheck('htmlContent')) {
                        $htmlFilePath = UPLOADPATH . 'report_template/' . $metaDataId . '.html';
                        if (file_put_contents($htmlFilePath, Input::postNonTags('htmlContent'))) {
                            $reportTemplate['HTML_FILE_PATH'] = $htmlFilePath;
                        }
                    }

                    if (self::isExistsMetaLink('META_REPORT_TEMPLATE_LINK', 'META_DATA_ID', $metaDataId)) {
                        unset($reportTemplate['ID']);
                        $result = $this->db->AutoExecute('META_REPORT_TEMPLATE_LINK', $reportTemplate, 'UPDATE', 'META_DATA_ID = ' . $metaDataId);              
                        if ($result) {
                            $this->db->UpdateClob('META_REPORT_TEMPLATE_LINK', 'CONFIG_STR', Input::post('configStr'), 'META_DATA_ID = '.$metaDataId);
                        }                          
                    } else {
                        $result = $this->db->AutoExecute('META_REPORT_TEMPLATE_LINK', $reportTemplate);
                        if ($result) {
                            $this->db->UpdateClob('META_REPORT_TEMPLATE_LINK', 'CONFIG_STR', Input::post('configStr'), 'ID = '.$reportTemplate['ID']);
                        } 
                    }

                    if (Input::postCheck('htmlHeaderContent')) {
                        $this->db->UpdateClob('META_REPORT_TEMPLATE_LINK', 'HTML_HEADER_CONTENT', Input::postWithDoubleSpace('htmlHeaderContent'), 'META_DATA_ID = '.$metaDataId);
                    }
                    if (Input::postCheck('htmlFooterContent')) {
                        $this->db->UpdateClob('META_REPORT_TEMPLATE_LINK', 'HTML_FOOTER_CONTENT', Input::postWithDoubleSpace('htmlFooterContent'), 'META_DATA_ID = '.$metaDataId);                
                    }
                    if (Input::postCheck('reportTemplateUIExpression')) {
                        $this->db->UpdateClob('META_REPORT_TEMPLATE_LINK', 'UI_EXPRESSION', Input::paramWithDoubleSpace($_POST['reportTemplateUIExpression']), 'META_DATA_ID = '.$metaDataId);                
                    }  
                } 

                if ($metaTypeId == Mdmetadata::$diagramMetaTypeId) {
                    $this->updateDiagramSettings($metaDataId);
                } 

                if ($metaTypeId == Mdmetadata::$metaGroupMetaTypeId) {
                    if (Input::isEmpty('groupType') == false) {

                        $isCustom = Input::postCheck('isCustom');

                        $dataGroupLink = array(
                            'ID' => getUID(),
                            'META_DATA_ID' => $metaDataId,
                            'LABEL_POSITION' => Input::post('labelPosition'),
                            'COLUMN_COUNT' => Input::post('columnCount'),
                            'LABEL_WIDTH' => Input::post('labelWidth'),
                            'SEARCH_TYPE' => Input::post('searchType'),
                            'GROUP_TYPE' => Input::post('groupType'),
                            'IS_TREEVIEW' => (Input::postCheck('isTreeview')) ? 1 : null,
                            'WINDOW_TYPE' => Input::post('windowType'),
                            'WINDOW_SIZE' => Input::post('windowSize'),
                            'WINDOW_WIDTH' => Input::post('windowWidth'),
                            'WINDOW_HEIGHT' => Input::post('windowHeight'),
                            'REF_META_GROUP_ID' => Input::post('repMetaGroupId'),
                            'REF_STRUCTURE_ID' => Input::post('repStructureId'),
                            'IS_ENTITY' => (Input::postCheck('isEntity')) ? 1 : null,
                            'LIST_NAME' => Input::post('listName'),                        
                            'IS_SKIP_UNIQUE_ERROR' => Input::post('isSkipUniqueError'), 
                            'IS_NOT_GROUPBY' => (Input::postCheck('isNotGroupBy')) ? 1 : null, 
                            'IS_ALL_NOT_SEARCH' => (Input::postCheck('isAllNotSearch')) ? 1 : null,
                            'IS_USE_RT_CONFIG' => (Input::postCheck('isUseRtConfig')) ? 1 : null,
                            'IS_USE_WFM_CONFIG' => (Input::postCheck('isUseWorkFlow')) ? 1 : null,
                            'IS_USE_SIDEBAR' => (Input::postCheck('isUseSidebar')) ? 1 : null, 
                            'IS_USE_QUICKSEARCH' => (Input::postCheck('isUseQuickSearch')) ? 1 : null, 
                            'IS_USE_RESULT' => (Input::postCheck('isUseResult')) ? 1 : null, 
                            'IS_EXPORT_TEXT' => (Input::postCheck('isExportText')) ? 1 : null, 
                            'BUTTON_BAR_STYLE' => Input::post('buttonBarStyle'), 
                            'REFRESH_TIMER' => Input::post('refreshTimer'), 
                            'M_CRITERIA_COL_COUNT' => Input::post('criteriaColCount'), 
                            'M_GROUP_CRITERIA_COL_COUNT' => Input::post('criteriaGroupColCount'),
                            'USE_BASKET' => (Input::postCheck('useBasket')) ? 1 : null, 
                            'IS_COUNTCARD_OPEN' => (Input::postCheck('isCountCartOpen')) ? 1 : null, 
                            'CALCULATE_PROCESS_ID' => Input::numeric('calculateProcessId'), 
                            'QS_META_DATA_ID' => Input::numeric('quickSearchDvId'), 
                            'DATA_LEGEND_DV_ID' => Input::numeric('legendDvId'), 
                            'LAYOUT_META_DATA_ID' => Input::numeric('layoutMetaId'),
                            'RULE_PROCESS_ID' => Input::numeric('ruleProcessId'), 
                            'IS_IGNORE_EXCEL_EXPORT' => (Input::postCheck('isIgnoreExcelExport')) ? 1 : null, 
                            'IS_USE_DATAMART' => (Input::postCheck('isUseDataMart')) ? 1 : null, 
                            'IS_CRITERIA_ALWAYS_OPEN' => (Input::postCheck('isCriteriaAlwaysOpen')) ? 1 : null, 
                            'IS_ENTER_FILTER' => (Input::postCheck('isEnterFilter')) ? 1 : null, 
                            'IS_FILTER_LOG' => (Input::postCheck('isFilterLog')) ? 1 : null,
                            'IS_IGNORE_SORTING' => (Input::postCheck('isIgnoreSorting')) ? 1 : null,
                            'IS_IGNORE_WFM_HISTORY' => (Input::postCheck('isIgnoreWfmHistory')) ? 1 : null,
                            'IS_DIRECT_PRINT' => (Input::postCheck('isDirectPrint')) ? 1 : null,
                            'IS_CLEAR_DRILL_CRITERIA' => (Input::postCheck('isClearDrillCriteria')) ? 1 : null,
                            'LIST_MENU_NAME' => Input::post('listMenuName'),
                            'SHOW_POSITION' => Input::post('showPosition'),
                            'IS_LOOKUP_BY_THEME' => (Input::postCheck('lookupTheme')) ? 1 : null, 
                            'EXTERNAL_META_DATA_ID' => Input::post('externalMetaDataId'), 
                            'WS_URL' => Input::post('wsUrl'), 
                            'PANEL_TYPE' => Input::post('panelType'), 
                            'IS_PARENT_FILTER' => Input::postCheck('isParentFilter') ? 1 : null, 
                            'IS_USE_SEMANTIC' => Input::postCheck('isUseSemantic') ? 1 : null,
                            'IS_USE_BUTTON_MAP' => Input::postCheck('isUseButtonMap') ? 1 : null, 
                            'IS_GMAP_USERLOCATION' => Input::postCheck('isGmapUserLocation') ? 1 : null, 
                            'IS_USE_COMPANY_DEPARTMENT_ID' => Input::postCheck('isUseCompanyDepartmentId') ? 1 : null, 
                            'IS_SHOW_FILTER_TEMPLATE' => Input::postCheck('isShowFilterTemplate') ? 1 : null, 
                            'IS_IGNORE_CLEAR_FILTER' => Input::postCheck('isIgnoreClearFilter') ? 1 : null, 
                            'IS_FIRST_COL_FILTER' => Input::postCheck('isFirstColFilter') ? 1 : null, 
                            'IS_CUSTOM' => $isCustom ? 1 : null, 
                            'CLASS_NAME' => $isCustom ? Input::post('className') : null, 
                            'METHOD_NAME' => $isCustom ? Input::post('methodName') : null, 
                            'FORM_CONTROL' => Input::post('formControl'),
                            'COLOR_SCHEMA' => Input::post('colorSchema'), 
                            'DRAG_ROWS_RUN_PROCESS_ID' => Input::numeric('dragRowsRunProcessId')
                        );

                        if (self::isExistsMetaLink('META_GROUP_LINK', 'META_DATA_ID', $metaDataId)) {
                            unset($dataGroupLink['ID']);
                            $groupLinkResult = $this->db->AutoExecute('META_GROUP_LINK', $dataGroupLink, 'UPDATE', 'META_DATA_ID = ' . $metaDataId);
                        } else {
                            $groupLinkResult = $this->db->AutoExecute('META_GROUP_LINK', $dataGroupLink);
                        }

                        $this->db->UpdateClob('META_GROUP_LINK', 'TABLE_NAME', (new Mdmetadata())->objectNameCompress(Str::htmlCharToDoubleQuote($_POST['tableName'])), 'META_DATA_ID = '.$metaDataId);
                        $this->db->UpdateClob('META_GROUP_LINK', 'POSTGRE_SQL', (new Mdmetadata())->objectNameCompress(Str::htmlCharToDoubleQuote($_POST['postgreSql'])), 'META_DATA_ID = '.$metaDataId);
                        $this->db->UpdateClob('META_GROUP_LINK', 'MS_SQL', (new Mdmetadata())->objectNameCompress(Str::htmlCharToDoubleQuote($_POST['msSql'])), 'META_DATA_ID = '.$metaDataId);

                        if (Input::isEmpty('dvSubQueryLoad') == false) {
                            $groupLinkId = Input::post('groupLinkId');

                            self::clearGroupSubQueryByLinkId($groupLinkId);

                            if (Input::postCheck('dvSubSqlTitle')) {
                                $dvSubSqlTitleData = Input::post('dvSubSqlTitle');

                                foreach ($dvSubSqlTitleData as $sgr => $dvSubSqlTitle) {

                                    $subQueryLinkId = getUID();

                                    $dvSubQueryData = array(
                                        'ID' => $subQueryLinkId,
                                        'CODE' => Input::param($_POST['dvSubSqlCode'][$sgr]),
                                        'GLOBE_CODE' => Input::param($dvSubSqlTitle),
                                        'DESCRIPTION' => Input::param($_POST['dvSubSqlDescr'][$sgr]),
                                        'META_GROUP_LINK_ID' => $groupLinkId
                                    );
                                    $this->db->AutoExecute('META_GROUP_SUB_QUERY', $dvSubQueryData);

                                    $this->db->UpdateClob('META_GROUP_SUB_QUERY', 'TABLE_NAME', (new Mdmetadata())->objectNameCompress(Str::htmlCharToDoubleQuote($_POST['dvSubSqlTableName'][$sgr])), 'ID = '.$subQueryLinkId);
                                }
                            }
                        }

                    }

                    if ($groupLinkResult) {
                        self::saveGroupParamsModel($metaDataId);
                    }

                    if (Input::postCheck('saveDataModelProcessDtl')) {

                        self::clearGroupProcessDetailMap($metaDataId);
                        self::clearDataViewProcessBatch($metaDataId);

                        if (Input::postCheck('groupProcessDtlMetaId')) {
                            $groupProcessDtlMetaIdData = $_POST['groupProcessDtlMetaId'];

                            foreach ($groupProcessDtlMetaIdData as $pdk => $pdkRow) {

                                if (empty($pdkRow)) { continue; }

                                $groupProcessDtlCriteria = Str::htmltotext(
                                    Str::htmlCharToDoubleQuote(
                                        Str::removeBr(
                                            Str::remove_doublewhitespace(
                                                Str::nlToSpace(
                                                    Str::cp1251_utf8(
                                                        $_POST['groupProcessDtlCriteria'][$pdk][0]
                                                    )
                                                )
                                            )
                                        )
                                    )
                                );
                                $groupProcessDtlAdvancedCriteria = Str::htmltotext(
                                    Str::htmlCharToDoubleQuote(
                                        Str::removeBr(
                                            Str::remove_doublewhitespace(
                                                Str::nlToSpace(
                                                    Str::cp1251_utf8(
                                                        $_POST['groupProcessDtlAdvancedCriteria'][$pdk][0]
                                                    )
                                                )
                                            )
                                        )
                                    )
                                );
                                $groupProcessDtlConfirmMsg = Str::cp1251_utf8($_POST['groupProcessDtlConfirmMsg'][$pdk][0]);

                                $dataProcessDtl = array(
                                    'ID' => getUID(),
                                    'MAIN_META_DATA_ID' => $metaDataId,
                                    'PROCESS_META_DATA_ID' => $pdk,
                                    'IS_MULTI' => ((isset($_POST['groupProcessDtlIsMulti'][$pdk])) ? 1 : 0),
                                    'PROCESS_NAME' => Input::param($_POST['groupProcessDtlProcessName'][$pdk][0]),
                                    'ICON_NAME' => Input::param($_POST['groupProcessDtlIconName'][$pdk][0]),
                                    'ORDER_NUM' => Input::param($_POST['groupProcessDtlOrderNum'][$pdk][0]),
                                    'IS_CONFIRM' => ((isset($_POST['groupProcessDtlIsConfirm'][$pdk])) ? 1 : 0),
                                    'BATCH_NUMBER' => Input::param($_POST['groupProcessDtlBatchNum'][$pdk][0]),
                                    'IS_SHOW_POPUP' => ((isset($_POST['groupProcessDtlIsShowPopup'][$pdk])) ? 1 : 0),
                                    'IS_WORKFLOW' => ((isset($_POST['groupProcessDtlIsWorkFlow'][$pdk])) ? 1 : 0),
                                    'IS_MAIN' => ((isset($_POST['groupProcessDtlIsMain'][$pdk])) ? 1 : 0),
                                    'IS_SHOW_BASKET' => ((isset($_POST['groupProcessDtlIsBasket'][$pdk])) ? 1 : 0),
                                    'BUTTON_STYLE' => ((isset($_POST['groupProcessDtlColor'][$pdk])) ? Input::param($_POST['groupProcessDtlColor'][$pdk][0]) : null),
                                    'CRITERIA' => $groupProcessDtlCriteria,
                                    'ADVANCED_CRITERIA' => $groupProcessDtlAdvancedCriteria,
                                    'CONFIRM_MESSAGE' => $groupProcessDtlConfirmMsg,
                                    'PASSWORD_PATH' => issetVar($_POST['groupProcessDtlPasswordPath'][$pdk][0]), 
                                    'IS_BP_OPEN' => issetVar($_POST['groupProcessDtlOpenBP'][$pdk][0]), 
                                    'IS_BP_OPEN_DEFAULT' => issetVar($_POST['groupProcessDtlOpenBPdefault'][$pdk][0]), 
                                    'ICON_COLOR' => issetVar($_POST['groupProcessDtlIconColor'][$pdk][0]), 
                                    'SHOW_POSITION' => issetVar($_POST['groupProcessShowPosition'][$pdk][0]), 
                                    'POST_PARAM' => Input::param($_POST['groupProcessDtlPostParam'][$pdk][0]),
                                    'GET_PARAM' => Input::param($_POST['groupProcessDtlGetParam'][$pdk][0]), 
                                    'IS_ROW_UPDATE' => isset($_POST['groupProcessDtlIsRowUpdate'][$pdk]) ? 1 : null, 
                                    'IS_SHOW_ROWSELECT' => isset($_POST['groupProcessDtlIsShowRowSelect'][$pdk]) ? Input::param($_POST['groupProcessDtlIsShowRowSelect'][$pdk][0]) : null, 
                                    'IS_USE_PROCESS_TOOLBAR' => isset($_POST['groupProcessDtlUseProcessToolbar'][$pdk]) ? Input::param($_POST['groupProcessDtlUseProcessToolbar'][$pdk][0]) : null, 
                                    'IS_PROCESS_TOOLBAR' => isset($_POST['groupProcessDtlProcessToolbar'][$pdk]) ? Input::param($_POST['groupProcessDtlProcessToolbar'][$pdk][0]) : null, 
                                    'IS_CONTEXT_MENU' => isset($_POST['groupProcessDtlIsContextMenu'][$pdk]) ? Input::param($_POST['groupProcessDtlIsContextMenu'][$pdk][0]) : null, 
                                    'IS_RUN_LOOP' => isset($_POST['groupProcessDtlIsRunLoop'][$pdk]) ? Input::param($_POST['groupProcessDtlIsRunLoop'][$pdk][0]) : null 
                                );

                                if (isset($_POST['groupProcessDtlTransferAutoMapValue'][$pdk][0])) {
                                    $dataProcessDtl['IS_AUTO_MAP'] = Input::param($_POST['groupProcessDtlTransferAutoMapValue'][$pdk][0]);
                                    $dataProcessDtl['AUTO_MAP_SRC'] = Input::param($_POST['groupProcessDtlTransferAutoMapSrcValue'][$pdk][0]);
                                    $dataProcessDtl['AUTO_MAP_ON_DELETE'] = empty($_POST['groupProcessDtlTransferAutoMapValue'][$pdk][0]) ? null : Input::param($_POST['groupProcessDtlTransferAutoMapOnDeleteValue'][$pdk][0]);
                                    $dataProcessDtl['AUTO_MAP_ON_UPDATE'] = empty($_POST['groupProcessDtlTransferAutoMapValue'][$pdk][0]) ? null : Input::param($_POST['groupProcessDtlTransferAutoMapOnUpdateValue'][$pdk][0]);
                                    $dataProcessDtl['AUTO_MAP_SRC_PATH'] = empty($_POST['groupProcessDtlTransferAutoMapSrcPath'][$pdk][0]) ? null : Input::param($_POST['groupProcessDtlTransferAutoMapSrcPath'][$pdk][0]);
                                    $dataProcessDtl['AUTO_MAP_SRC_TABLE_NAME'] = empty($_POST['groupProcessDtlTransferAutoMapTableName'][$pdk][0]) ? null : Input::param($_POST['groupProcessDtlTransferAutoMapTableName'][$pdk][0]);
                                    $dataProcessDtl['AUTO_MAP_DELETE_PROCESS_ID'] = empty($_POST['groupProcessDtlTransferAutoMapValue'][$pdk][0]) ? null : Input::param($_POST['groupProcessDtlTransferDeleteMetaId'][$pdk][0]);
                                    $dataProcessDtl['AUTO_MAP_DATAVIEW_ID'] = issetParam($_POST['groupProcessDtlTransferListMetaId'][$pdk][0]);
                                    $dataProcessDtl['AUTO_MAP_NAME_PATTERN'] = issetParam($_POST['groupProcessDtlTransferPattern'][$pdk][0]);
                                    $dataProcessDtl['AUTO_MAP_TRG_NAME_PATTERN'] = issetParam($_POST['groupProcessDtlTransferTrgPattern'][$pdk][0]);
                                }
                                $resultProcessDtl = $this->db->AutoExecute('META_DM_PROCESS_DTL', $dataProcessDtl);

                                if ($resultProcessDtl) {
                                    if (isset($_POST['groupProcessDtlTransferGetMetaId'][$pdk])) {
                                        $groupProcessDtlTransferGetMetaIdData = $_POST['groupProcessDtlTransferGetMetaId'][$pdk];
                                        foreach ($groupProcessDtlTransferGetMetaIdData as $dtlTk => $dtlTkRow) {
                                            $dataProcessDtlTransfer = array(
                                                'ID' => getUID(),
                                                'MAIN_META_DATA_ID' => $metaDataId,
                                                'PROCESS_META_DATA_ID' => $pdk,
                                                'VIEW_FIELD_PATH' => Input::param($_POST['groupProcessDtlTransferViewPath'][$pdk][$dtlTk]),
                                                'GET_META_DATA_ID' => Input::param($_POST['groupProcessDtlTransferGetMetaId'][$pdk][$dtlTk]),
                                                'INPUT_PARAM_PATH' => Input::param($_POST['groupProcessDtlTransferParamPath'][$pdk][$dtlTk]), 
                                                'DEFAULT_VALUE' => Input::param($_POST['groupProcessDtlTransferDefaultValue'][$pdk][$dtlTk])
                                            );
                                            $this->db->AutoExecute('META_DM_TRANSFER_PROCESS', $dataProcessDtlTransfer);
                                        }
                                    }
                                    if (isset($_POST['groupProcessDtlBasketTransferParamPath'][$pdk]) && !empty($_POST['groupProcessDtlBasketTransferParamPath'][$pdk])) {
                                        $groupProcessDtlBasketTransferParamPath = $_POST['groupProcessDtlBasketTransferParamPath'][$pdk];
                                        foreach ($groupProcessDtlBasketTransferParamPath as $dtlTk => $dtlTkRow) {
                                            $dataProcessDtlBasketTransfer = array(
                                                'ID' => getUID(),
                                                'MAIN_META_DATA_ID' => $metaDataId,
                                                'PROCESS_META_DATA_ID' => $pdk,
                                                'VIEW_FIELD_PATH' => Input::param($_POST['groupProcessDtlBasketTransferViewPath'][$pdk][$dtlTk]),
                                                'BASKET_PATH' => Input::param($_POST['groupProcessDtlBasketTransferParamPath'][$pdk][$dtlTk]), 
                                                'BASKET_INPUTPATH' => Input::param($_POST['groupProcessDtlBasketTransferDefaultValue'][$pdk][$dtlTk])
                                            );
                                            $this->db->AutoExecute('META_DM_TRANSFER_PROCESS', $dataProcessDtlBasketTransfer);
                                        }
                                    }
                                }
                            }
                        }

                        if (Input::postCheck('batchNumber')) {
                            $batchNumberData = Input::post('batchNumber');
                            foreach ($batchNumberData as $bk => $bRow) {
                                if ($bRow != '') {
                                    $dmProcessBatch = array(
                                        'ID' => getUID(),
                                        'MAIN_META_DATA_ID' => $metaDataId,
                                        'BATCH_NUMBER' => Input::param($bRow),
                                        'BATCH_NAME' => Input::param($_POST['batchNumberName'][$bk]),
                                        'ICON_NAME' => Input::param($_POST['batchNumberIcon'][$bk]),
                                        'IS_DROPDOWN' => Input::param($_POST['batchNumberIsDrop'][$bk]),
                                        'IS_SHOW_POPUP' => Input::param($_POST['batchNumberIsShowPopup'][$bk]),
                                        'BUTTON_STYLE' => Input::param($_POST['batchNumberColor'][$bk])
                                    );
                                    $this->db->AutoExecute('META_DM_PROCESS_BATCH', $dmProcessBatch);
                                }
                            }
                        }
                    }

                    if (Input::postCheck('groupProcessDtlTransferProcessParamPath')) {
                        self::clearGroupProcessDetailRowMap($metaDataId);

                        $groupProcessDtlMetaIdData = $_POST['groupProcessDtlTransferProcessParamPath'];

                        foreach ($groupProcessDtlMetaIdData as $pdk => $pdkRow) {

                            if (isset($_POST['groupProcessDtlTransferProcessParamPath'][$pdk])) {
                                $groupProcessDtlTransferGetMetaIdData = $_POST['groupProcessDtlTransferProcessParamPath'][$pdk];
                                foreach ($groupProcessDtlTransferGetMetaIdData as $dtlTk => $dtlTkRow) {
                                    $dataProcessDtlTransfer = array(
                                        'ID' => getUID(),
                                        'MAIN_META_DATA_ID' => $metaDataId,
                                        'PROCESS_META_DATA_ID' => $pdk,
                                        'SRC_PARAM_PATH' => Input::param($_POST['groupProcessDtlTransferDataViewPath'][$pdk][$dtlTk]),
                                        'TRG_PARAM_PATH' => Input::param($dtlTkRow)
                                    );
                                    $this->db->AutoExecute('META_DM_ROW_PROCESS_PARAM', $dataProcessDtlTransfer);
                                }
                            }                        

                        }
                    }

                    if (Input::postCheck('gridProperties')) {
                        self::clearDataViewGridOptions($metaDataId);
                        $gridPropertiesData = $_POST['gridProperties'];
                        $gridOptionData = array(
                            'OPTION_ID' => getUID(),
                            'MAIN_META_DATA_ID' => $metaDataId
                        );
                        foreach ($gridPropertiesData as $gridOptionKey => $gridOptionVal) {
                            $gridOptionData[strtoupper($gridOptionKey)] = Input::param($gridOptionVal);
                        }
                        $this->db->AutoExecute('META_GROUP_GRID_OPTIONS', $gridOptionData);
                    }

                    if (Input::postCheck('dvExportHeader')) {
                        self::clearDataViewExportHeaderFooter($metaDataId);

                        $expHdrFtr = array(
                            'ID' => getUID(),
                            'META_DATA_ID' => $metaDataId, 
                            'HEADER_HTML' => Input::postNonTags('dvExportHeader'), 
                            'FOOTER_HTML' => Input::postNonTags('dvExportFooter')
                        );
                        $this->db->AutoExecute('CUSTOMER_DV_HDR_FTR', $expHdrFtr);
                    }

                    (new Mdmeta())->dvCacheClearByMetaId($metaDataId);
                } 

                if ($metaTypeId == Mdmetadata::$workSpaceMetaTypeId) {
                    $dataWorkSpaceLink = array(
                        'ID' => getUID(),
                        'META_DATA_ID' => Input::param($metaDataId),
                        'MENU_META_DATA_ID' => Input::post('menuActionMetaDataId'),
                        'SUBMENU_META_DATA_ID' => Input::post('menuQuickMetaDataId'),
                        'GROUP_META_DATA_ID' => Input::post('groupMetaDataId'),
                        'THEME_CODE' => Input::post('themeCode'),
                        'DEFAULT_MENU_ID' => Input::post('defaultMenuId'),
                        'WINDOW_HEIGHT' => Input::post('windowHeight'),
                        'WINDOW_SIZE' => Input::post('windowSize'),
                        'WINDOW_TYPE' => Input::post('windowType'),
                        'WINDOW_WIDTH' => Input::post('windowWidth'),
                        'IS_FLOW' => Input::post('isFlow'),
                        'USE_TOOLTIP' => Input::post('useTooltip'), 
                        'USE_PICTURE' => Input::postCheck('usePic') ? 1 : 0, 
                        'USE_COVER_PICTURE' => Input::postCheck('useCoverPic') ? 1 : 0, 
                        'USE_LEFT_SIDE' => Input::postCheck('useLeftSide') ? 1 : 0, 
                        'IS_LAST_VISIT_MENU' => Input::postCheck('isLastVisitMenu') ? 1 : 0, 
                        'USE_MENU' => Input::postCheck('isUseMenu') ? 1 : 0, 
                        'CHECK_MODIFIED_CATCH' => Input::postCheck('checkModifiedCatch') ? 1 : 0, 
                        'ACTION_TYPE' => Input::post('actionType'), 
                        'MOBILE_THEME' => Input::post('mobileTheme'), 
                        'ROW_DATAVIEW_ID' => Input::post('rowMetaDataId')
                    );

                    if (self::isExistsMetaLink('META_WORKSPACE_LINK', 'META_DATA_ID', $metaDataId)) {
                        unset($dataWorkSpaceLink['ID']);
                        $this->db->AutoExecute('META_WORKSPACE_LINK', $dataWorkSpaceLink, 'UPDATE', 'META_DATA_ID = ' . $metaDataId);
                    } else {
                        $this->db->AutoExecute('META_WORKSPACE_LINK', $dataWorkSpaceLink);
                    }
                } 

                if ($metaTypeId == Mdmetadata::$statementMetaTypeId) {

                    $reportType = Input::post('reportType');
                    $statementLinkData = array(
                        'ID' => getUID(),
                        'META_DATA_ID' => $metaDataId,
                        'REPORT_NAME' => Input::post('reportName'),
                        'REPORT_TYPE' => $reportType,
                        'DATA_VIEW_ID' => Input::post('dataViewId'),
                        'GROUP_DATA_VIEW_ID' => Input::post('groupDataViewId'),
                        'PAGE_SIZE' => Input::post('pageSize'),
                        'PAGE_ORIENTATION' => Input::post('pageOrientation'),
                        'PAGE_MARGIN_TOP' => Input::post('pageMarginTop'),
                        'PAGE_MARGIN_LEFT' => Input::post('pageMarginLeft'),
                        'PAGE_MARGIN_RIGHT' => Input::post('pageMarginRight'),
                        'PAGE_MARGIN_BOTTOM' => Input::post('pageMarginBottom'),
                        'PAGE_HEIGHT' => Input::post('pageHeight'),
                        'PAGE_WIDTH' => Input::post('pageWidth'), 
                        'FONT_FAMILY' => Input::post('fontFamily'), 
                        'RENDER_TYPE' => Input::post('renderType'), 
                        'IS_ARCHIVE' => Input::postCheck('isArchive') ? 1 : null, 
                        'IS_HDR_REPEAT_PAGE' => Input::postCheck('isHdrRepeatPage') ? 1 : null,
                        'IS_NOT_PAGE_BREAK' => Input::postCheck('isNotPageBreak') ? 1 : null,
                        'IS_BLANK' => Input::postCheck('isBlank') ? 1 : null,
                        'IS_SHOW_DV_BTN' => Input::postCheck('isShowDvBtn') ? 1 : null,
                        'IS_USE_SELF_DV' => Input::postCheck('isUseSelfDv') ? 1 : null,
                        'IS_AUTO_FILTER' => Input::postCheck('isAutoFilter') ? 1 : null,
                        'IS_GROUP_MERGE' => Input::postCheck('isGroupMerge') ? 1 : null,
                        'IS_TIMETABLE' => Input::postCheck('isTimetable') ? 1 : null,
                        'IS_EXPORT_NO_FOOTER' => Input::postCheck('isExportNoFooter') ? 1 : null,
                        'PROCESS_META_DATA_ID' => Input::post('calcProcessId'), 
                        'CALC_ORDER_NUM' => Input::post('calcOrderNum') 
                    );

                    if ($statementPkId = self::isExistsMetaLink('META_STATEMENT_LINK', 'META_DATA_ID', $metaDataId)) {
                        unset($statementLinkData['ID']);
                        $statementLinkData['REPORT_DETAIL_FILE_PATH'] = null;
                        $this->db->AutoExecute('META_STATEMENT_LINK', $statementLinkData, 'UPDATE', 'META_DATA_ID = '.$metaDataId);
                        $statementLinkData['ID'] = $statementPkId;
                    } else {
                        $this->db->AutoExecute('META_STATEMENT_LINK', $statementLinkData);
                    }

                    $this->db->UpdateClob('META_STATEMENT_LINK', 'REPORT_HEADER', Input::postWithDoubleSpace('reportHeader'), 'META_DATA_ID = '.$metaDataId);
                    $this->db->UpdateClob('META_STATEMENT_LINK', 'PAGE_HEADER', Input::postWithDoubleSpace('pageHeader'), 'META_DATA_ID = '.$metaDataId);
                    $this->db->UpdateClob('META_STATEMENT_LINK', 'REPORT_DETAIL', Input::postWithDoubleSpace('reportDetail'), 'META_DATA_ID = '.$metaDataId);
                    $this->db->UpdateClob('META_STATEMENT_LINK', 'PAGE_FOOTER', Input::postWithDoubleSpace('pageFooter'), 'META_DATA_ID = '.$metaDataId);
                    $this->db->UpdateClob('META_STATEMENT_LINK', 'REPORT_FOOTER', Input::postWithDoubleSpace('reportFooter'), 'META_DATA_ID = '.$metaDataId);

                    if (Input::postCheck('reportRowExpressionString_set')) {
                        $this->db->UpdateClob('META_STATEMENT_LINK', 'ROW_EXPRESSION', Input::postWithDoubleSpace('reportRowExpressionString_set'), 'META_DATA_ID = '.$metaDataId);
                        $this->db->UpdateClob('META_STATEMENT_LINK', 'GLOBAL_EXPRESSION', Input::postWithDoubleSpace('reportGlobalExpressionString_set'), 'META_DATA_ID = '.$metaDataId);
                        $this->db->UpdateClob('META_STATEMENT_LINK', 'SUPER_GLOBAL_EXPRESSION', Input::postWithDoubleSpace('reportSuperGlobalExpressionString_set'), 'META_DATA_ID = '.$metaDataId);
                        $this->db->UpdateClob('META_STATEMENT_LINK', 'UI_EXPRESSION', Input::postWithDoubleSpace('uiExpressionHeaderFooter_set'), 'META_DATA_ID = '.$metaDataId);
                        $this->db->UpdateClob('META_STATEMENT_LINK', 'UI_GROUP_EXPRESSION', Input::postWithDoubleSpace('uiExpressionGroup_set'), 'META_DATA_ID = '.$metaDataId);
                        $this->db->UpdateClob('META_STATEMENT_LINK', 'UI_DETAIL_EXPRESSION', Input::postWithDoubleSpace('uiExpressionDetail_set'), 'META_DATA_ID = '.$metaDataId);
                    }

                    if (Input::isEmpty('reportGroupingLoad') == false) {
                        self::clearStatementGroupLinkMapByLinkId($statementLinkData['ID']);

                        if (Input::postCheck('groupFieldPath')) {
                            $groupFieldPathData = Input::post('groupFieldPath');

                            foreach ($groupFieldPathData as $skr => $groupFieldPath) {

                                $stateLinkId = getUID();
                                $isUserOption = Input::param($_POST['groupIsUserOption'][$skr]);
                                $isDefault = Input::param($_POST['groupIsDefault'][$skr]);
                                $isSaveUserOption = null;

                                if ($isUserOption == '1' && $isDefault == '1') {
                                    $isSaveUserOption = 2;
                                } elseif ($isUserOption == '1' && ($isDefault == '' || $isDefault == '0')) {
                                    $isSaveUserOption = 1;
                                } elseif ($isDefault == '1' && ($isUserOption == '' || $isUserOption == '0')) {
                                    $isSaveUserOption = null;
                                }

                                $statementLinkGroupData = array(
                                    'ID' => $stateLinkId,
                                    'META_DATA_ID' => Input::param($metaDataId),
                                    'GROUP_FIELD_PATH' => Input::param($groupFieldPath),
                                    'GROUP_ORDER' => Input::param($_POST['groupOrderNum'][$skr]),
                                    'HEADER_BG_COLOR' => Input::param($_POST['groupHdrBgColor'][$skr]),
                                    'FOOTER_BG_COLOR' => Input::param($_POST['groupFtrBgColor'][$skr]),
                                    'IS_USER_OPTION' => $isSaveUserOption, 
                                    'META_STATEMENT_LINK_ID' => $statementLinkData['ID']
                                );
                                $this->db->AutoExecute('META_STATEMENT_LINK_GROUP', $statementLinkGroupData);

                                $this->db->UpdateClob('META_STATEMENT_LINK_GROUP', 'GROUP_HEADER', Input::paramWithDoubleSpace($_POST['groupHeader'][$skr]), 'ID = '.$stateLinkId);
                                $this->db->UpdateClob('META_STATEMENT_LINK_GROUP', 'GROUP_FOOTER', Input::paramWithDoubleSpace($_POST['groupFooter'][$skr]), 'ID = '.$stateLinkId);
                            }
                        }
                    } 

                    self::clearMetaVersionMap($metaDataId);

                    if (Input::postCheck('versionChildMetaDataId')) {

                        $versionChildMetaDatas = Input::post('versionChildMetaDataId');

                        foreach ($versionChildMetaDatas as $cv => $versionChildMetaDataId) {
                            if (!empty($versionChildMetaDataId)) {
                                $dataVersion = array(
                                    'ID' => getUID(),
                                    'SRC_META_DATA_ID' => $metaDataId,
                                    'TRG_META_DATA_ID' => $versionChildMetaDataId, 
                                    'ORDER_NUM' => ($cv + 1)
                                );
                                $this->db->AutoExecute('META_VERSION_MAP', $dataVersion);
                            }
                        }
                    }

                    if (defined('CONFIG_REPORT_SERVER_ADDRESS') && CONFIG_REPORT_SERVER_ADDRESS) {

                        self::clearMetaStatementTemplateMap($metaDataId);

                        if (Input::postCheck('templateStatementMetaId')) {

                            $templateStatementMetaIds = Input::post('templateStatementMetaId');

                            foreach ($templateStatementMetaIds as $cv => $templateStatementMetaId) {
                                if (!empty($templateStatementMetaId)) {
                                    $dataVersion = array(
                                        'ID' => getUID(),
                                        'SRC_META_DATA_ID' => $metaDataId,
                                        'TRG_META_DATA_ID' => $templateStatementMetaId
                                    );
                                    $this->db->AutoExecute('META_STATEMENT_TEMPLATE', $dataVersion);
                                }
                            }
                        }
                    }  
                } 

                if ($metaTypeId == Mdmetadata::$packageMetaTypeId) {
                    $packageLinkData = array(
                        'ID' => getUID(),
                        'META_DATA_ID' => Input::param($metaDataId),
                        'RENDER_TYPE' => Input::post('renderType'), 
                        'IS_IGNORE_MAIN_TITLE' => Input::postCheck('isIgnoreMainTitle') ? 1 : null, 
                        'MOBILE_THEME' => Input::post('mobileTheme'),
                        'SPLIT_COLUMN' => Input::post('split_column'),
                        'PACKAGE_CLASS' => Input::post('package_class'),
                        'IS_IGNORE_PACKAGE_TITLE' => Input::postCheck('isIgnorePackageTitle') ? 1 : null,
                        'IS_FILTER_BTN_SHOW' => Input::postCheck('isFilterShowButton') ? 1 : null,
                        'COUNT_META_DATA_ID' => Input::post('countMetaDataId'),
                        'TAB_BACKGROUND_COLOR' => Input::post('tabBackgroundColor'),
                        'IS_CHECK_PERMISSION' => Input::postCheck('isPermission') ? 1 : null,
                        'IS_REFRESH' => Input::postCheck('isRefresh') ? 1 : null,  
                        'DEFAULT_META_ID' => Input::post('defaultMetaDataId') 
                    );

                    if (self::isExistsMetaLink('META_PACKAGE_LINK', 'META_DATA_ID', $metaDataId)) {
                        unset($packageLinkData['ID']);
                        $this->db->AutoExecute('META_PACKAGE_LINK', $packageLinkData, 'UPDATE', 'META_DATA_ID = '.$metaDataId);
                    } else {
                        $this->db->AutoExecute('META_PACKAGE_LINK', $packageLinkData);
                    }
                } 

                if ($metaTypeId == Mdmetadata::$layoutMetaTypeId) {
                    $layoutLinkId = Input::post('layoutLinkId');
                    $result = $this->db->GetRow('SELECT ID FROM META_LAYOUT_LINK WHERE ID = ' . Input::post('layoutLinkId'));
                    if (count($result) > 0) {
                        $dataLayoutMapLink = array(
                            'THEME_CODE' => Input::post('themeCode'),
                            'IS_HIDE_BUTTON' => Input::postCheck('isHideButton') ? 1 : 0,
                            'CRITERIA_DATA_VIEW_ID' => Input::post('dataViewIdLayout'),
                            'USE_BORDER' => Input::postCheck('useBorder') ? 1 : 0,
                            'REFRESH_TIMER' => Input::post('refreshTimerLayout')
                        );
                        $this->db->AutoExecute('META_LAYOUT_LINK', $dataLayoutMapLink, 'UPDATE', 'ID = ' . $layoutLinkId);
                    } else {
                        $layoutLinkId = getUID();
                        $dataLayoutMapLink = array(
                            'ID' => $layoutLinkId,
                            'META_DATA_ID' => $metaDataId,
                            'IS_HIDE_BUTTON' => Input::postCheck('isHideButton') ? 1 : 0,
                            'CRITERIA_DATA_VIEW_ID' => Input::post('dataViewIdLayout'),
                            'USE_BORDER' => Input::postCheck('useBorder') ? 1 : 0,
                            'THEME_CODE' => Input::post('themeCode'),
                            'REFRESH_TIMER' => Input::post('refreshTimerLayout')
                        );
                        $this->db->AutoExecute('META_LAYOUT_LINK', $dataLayoutMapLink);
                    }

                    if (isset($_POST['bpMetaDataId'])) {

                        self::clearLayoutLinkParamMap(Input::post('layoutLinkId'));

                        foreach ($_POST['bpMetaDataId'] as $k => $row) {
                            $layoutParamMapId = getUID();
                            $dataLayoutParam = array(
                                'ID' => $layoutParamMapId,
                                'LAYOUT_PATH' => Input::param($_POST['layoutPath'][$k]),
                                'BP_META_DATA_ID' => Input::param($row),
                                'META_LAYOUT_LINK_ID' => $layoutLinkId,
                                'ORDER_NUM' => Input::param($_POST['orderNum'][$k])
                            );
                            $this->db->AutoExecute('META_LAYOUT_PARAM_MAP', $dataLayoutParam);
                        }
                    }
                } 

                if ($metaTypeId == Mdmetadata::$bpmMetaTypeId) {

                    $graphXml = Input::postNonTags('graphXml');

                    $bpmLinkData = array(
                        'ID' => getUID(),
                        'META_DATA_ID' => Input::param($metaDataId)
                    );

                    if (self::isExistsMetaLink('META_BPM_LINK', 'META_DATA_ID', $metaDataId)) {

                        unset($bpmLinkData['ID']);

                        $this->db->AutoExecute('META_BPM_LINK', $bpmLinkData, 'UPDATE', 'META_DATA_ID = '.$metaDataId);
                        $this->db->UpdateClob('META_BPM_LINK', 'GRAPH_XML', $graphXml, 'META_DATA_ID = '.$metaDataId);

                    } else {

                        $this->db->AutoExecute('META_BPM_LINK', $bpmLinkData);

                        if ($graphXml) {
                            $this->db->UpdateClob('META_BPM_LINK', 'GRAPH_XML', $graphXml, 'META_DATA_ID = '.$metaDataId);
                        }
                    }  
                } 

                if ($metaTypeId == Mdmetadata::$dmMetaTypeId) {
                    $dmLink = $this->db->GetRow('SELECT ID FROM META_DATAMART_LINK WHERE META_DATA_ID = ' . $metaDataId);

                    if ($dmLink) {
                        $this->db->Execute("DELETE FROM META_DATAMART_COLUMN_CRITERIA WHERE META_DATAMART_COLUMN_ID IN (SELECT ID FROM META_DATAMART_COLUMN WHERE META_DATAMART_LINK_ID = " . $dmLink['ID'] . ")");
                        $this->db->Execute("DELETE FROM META_DATAMART_COLUMN WHERE META_DATAMART_LINK_ID = " . $dmLink['ID']);
                        $this->db->Execute("DELETE FROM META_GROUP_RELATION WHERE MAIN_META_DATA_ID = " . $metaDataId);
                        $this->db->Execute("DELETE FROM META_DATAMART_SCHEDULE WHERE META_DATA_ID = " . $metaDataId);
                    } else {
                        $dmLink = array(
                            'ID' => getUID(),
                            'TABLE_NAME' => Input::post('dmTableName'),
                            'META_DATA_ID' => $metaDataId
                        );                    
                        $this->db->AutoExecute('META_DATAMART_LINK', $dmLink);                     
                    }

                    foreach ($_POST['dmOutput'] as $dmKey => $dmrow) {
                        $groupId = $_POST['dmMetaGroup'][$dmKey];
                        $dmLinkData = array(
                            'ID' => getUIDAdd($dmKey),
                            'META_DATAMART_LINK_ID' => $dmLink['ID'],
                            'META_GROUP_ID' => Input::param($groupId),
                            'SRC_PARAM_PATH' => Input::param($_POST['dmPath'][$dmKey]),
                            'EXPRESSION' => Input::param($_POST['dmExpression'][$dmKey]),
                            'IS_OUTPUT' => Input::param($dmrow),
                            'AGGREGATE_FUNCTION' => Input::param($_POST['dmAggregate'][$dmKey]),
                            'ALIAS_NAME' => Input::param($_POST['dmAs'][$dmKey]),
                            'SORT_TYPE' => Input::param($_POST['dmSortType'][$dmKey]),
                            'SORT_ORDER' => Input::param($_POST['dmSortOrder'][$dmKey]),
                            'IS_GROUP' => Input::param($_POST['dmGrouping'][$dmKey])
                        );                    
                        $this->db->AutoExecute('META_DATAMART_COLUMN', $dmLinkData);

                        if (isset($_POST['dmCriteria'.$groupId.$dmLinkData['SRC_PARAM_PATH']]) && !empty($_POST['dmCriteria'.$groupId.$dmLinkData['SRC_PARAM_PATH']])) {
                            foreach ($_POST['dmCriteria'.$groupId.$dmLinkData['SRC_PARAM_PATH']] as $criKey => $criteria) {
                                if (!empty($criteria)) {
                                    $dmCriteriaData = array(
                                        'ID' => getUIDAdd($criKey),
                                        'META_DATAMART_COLUMN_ID' => $dmLinkData['ID'],
                                        'CRITERIA' => Input::param($criteria)
                                    );                    
                                    $this->db->AutoExecute('META_DATAMART_COLUMN_CRITERIA', $dmCriteriaData);                            
                                }
                            }
                        }
                    }

                    if (isset($_POST['dmSourceGroup'])) {
                        foreach ($_POST['dmSourceGroup'] as $dmKey => $dmrow) {
                            if(!empty($dmrow)) {
                                $dmLinkData = array(
                                    'ID' => getUIDAdd($dmKey),
                                    'SRC_META_GROUP_ID' => Input::param($dmrow),
                                    'TRG_META_GROUP_ID' => Input::param($_POST['dmTargetGroup'][$dmKey]),
                                    'MAIN_META_DATA_ID' => Input::param($metaDataId),
                                    'SRC_PARAM_PATH' => Input::param($_POST['dmSourceGroupPath'][$dmKey]),
                                    'TRG_PARAM_PATH' => Input::param($_POST['dmTargetGroupPath'][$dmKey])
                                );                    
                                $this->db->AutoExecute('META_GROUP_RELATION', $dmLinkData);
                            }
                        }
                    }

                    if (isset($_POST['dmScheduleId'])) {
                        foreach ($_POST['dmScheduleId'] as $dmKey => $dmrow) {
                            $dmLinkData = array(
                                'ID' => getUIDAdd($dmKey),
                                'META_DATA_ID' => $metaDataId,
                                'SCHEDULE_ID' => Input::param($dmrow)
                            );                    
                            $this->db->AutoExecute('META_DATAMART_SCHEDULE', $dmLinkData);
                        }
                    }

                    $param = array(
                        'metadataid' => $metaDataId,
                        'create_datamart_table' => Input::post('dmTableName'),
                    );

                    $dmQuery = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'generate_datamart_query', $param);

                    if ($dmQuery['status'] === 'success') {
                        $dmLinkUpdate = array(
                            'TABLE_NAME' => Input::post('dmTableName')
                        );
                        $this->db->AutoExecute('META_DATAMART_LINK', $dmLinkUpdate, 'UPDATE', " ID = " . $dmLink['ID']);
                        $this->db->UpdateClob('META_DATAMART_LINK', 'SELECT_QUERY', $dmQuery['result'], 'ID = '.$dmLink['ID']);
                    }
                }

                if ($oldMetaTypeId != $metaTypeId) {

                    if ($oldMetaTypeId == Mdmetadata::$proxyMetaTypeId) {
                        self::clearMetaProxyMap($metaDataId);
                    } elseif ($oldMetaTypeId == Mdmetadata::$bookmarkMetaTypeId) {
                        self::clearMetaBookmarkLinkMap($metaDataId);
                    } elseif ($oldMetaTypeId == Mdmetadata::$dashboardMetaTypeId) {
                        self::clearMetaDashboardLinkMap($metaDataId);
                    } elseif ($oldMetaTypeId == Mdmetadata::$reportMetaTypeId) {
                        self::clearMetaReportLinkMap($metaDataId);
                    } elseif ($oldMetaTypeId == Mdmetadata::$fieldMetaTypeId) {
                        self::clearMetaFieldLinkMap($metaDataId);
                    } elseif ($oldMetaTypeId == Mdmetadata::$menuMetaTypeId) {
                        self::clearMenuLinkMap($metaDataId);
                    } elseif ($oldMetaTypeId == Mdmetadata::$calendarMetaTypeId) {
                        self::clearCalendarLinkMap($metaDataId);
                    } elseif ($oldMetaTypeId == Mdmetadata::$contentMetaTypeId) {
                        self::clearContentLinkMap($metaDataId);
                    } elseif ($oldMetaTypeId == Mdmetadata::$donutMetaTypeId) {
                        self::clearDonutLinkMap($metaDataId);
                    } elseif ($oldMetaTypeId == Mdmetadata::$cardMetaTypeId) {
                        self::clearCardLinkMap($metaDataId);
                    } elseif ($oldMetaTypeId == Mdmetadata::$reportTemplateMetaTypeId) {
                        self::clearReportTemplateLinkMap($metaDataId);
                    } elseif ($oldMetaTypeId == Mdmetadata::$diagramMetaTypeId) {
                        self::clearDiagramLinkMap($metaDataId);
                    } elseif ($oldMetaTypeId == Mdmetadata::$metaGroupMetaTypeId) {
                        self::clearGroupLinkMap($metaDataId);
                        self::clearGroupConfigMap($metaDataId);
                        self::clearGroupProcessDetailMap($metaDataId);
                        self::clearGroupProcessDetailRowMap($metaDataId);
                        self::clearDataViewGridOptions($metaDataId);
                        self::clearDataViewProcessBatch($metaDataId);
                        self::clearDataViewExportHeaderFooter($metaDataId);
                    } elseif ($oldMetaTypeId == Mdmetadata::$workSpaceMetaTypeId) {
                        self::clearWorkSpaceLinkMap($metaDataId);
                    } elseif ($oldMetaTypeId == Mdmetadata::$statementMetaTypeId) {
                        self::clearStatementLinkMap($metaDataId);
                    } elseif ($oldMetaTypeId == Mdmetadata::$packageMetaTypeId) {
                        self::clearPackageLinkMap($metaDataId);
                    } elseif ($oldMetaTypeId == Mdmetadata::$layoutMetaTypeId) {
                        self::clearLayoutLinkMap($metaDataId);
                    } elseif ($oldMetaTypeId == Mdmetadata::$bpmMetaTypeId) {
                        self::clearBpmLinkMap($metaDataId);
                    } 
                }

                if (isset($_FILES['meta_file'])) {

                    $file_arr = Arr::arrayFiles($_FILES['meta_file']);
                    $fileData = Input::post('meta_file_name');

                    foreach ($fileData as $f => $file) {

                        if ($file_arr[$f]['name'] != '') {

                            $newFileName = 'file_' . getUID() . $f;
                            $fileExtension = strtolower(substr($file_arr[$f]['name'], strrpos($file_arr[$f]['name'], '.') + 1));
                            $fileName = $newFileName . '.' . $fileExtension;

                            FileUpload::SetFileName($fileName);
                            FileUpload::SetTempName($file_arr[$f]['tmp_name']);
                            FileUpload::SetUploadDirectory(UPLOADPATH . 'meta/file/');
                            FileUpload::SetValidExtensions(explode(',', Config::getFromCache('CONFIG_FILE_EXT')));
                            FileUpload::SetMaximumFileSize(10485760); //10mb
                            $uploadResult = FileUpload::UploadFile();

                            if ($uploadResult) {

                                $attachFileId = getUID();

                                $dataAttachFile = array(
                                    'ATTACH_ID' => $attachFileId,
                                    'ATTACH_NAME' => ((empty($file)) ? $file_arr[$f]['name'] : $file),
                                    'ATTACH' => UPLOADPATH . 'meta/file/' . $fileName,
                                    'FILE_EXTENSION' => $fileExtension,
                                    'FILE_SIZE' => $file_arr[$f]['size'],
                                    'CREATED_USER_ID' => Ue::sessionUserKeyId()
                                );
                                $attachFile = $this->db->AutoExecute('FILE_ATTACH', $dataAttachFile);

                                if ($attachFile) {
                                    $dataMetaFile = array(
                                        'META_DATA_ID' => $metaDataId,
                                        'ATTACH_ID' => $attachFileId
                                    );
                                    $this->db->AutoExecute('META_DATA_ATTACH', $dataMetaFile);
                                }
                            }
                        }
                    }
                }

                self::saveMetaChangingLog($metaDataId);

                return array('folderId' => $folderId, 'metaDataId' => $metaDataId);
            }
            
        } catch (Exception $ex) {
            return array('status' => 'error', 'message' => $ex->getMessage());
        }

        return false;
    }

    public function getMetaDataBySystem($metaDataId) {
        
        if (Input::postCheck('dialogMode') && Input::isEmpty('dialogMode') === false) {
            return array(
                'META_DATA_ID' => '', 
                'META_DATA_CODE' => '', 
                'META_DATA_NAME' => '',
                'DESCRIPTION' => '', 
                'IS_ACTIVE' => '', 
                'CREATED_DATE' => '',
                'META_TYPE_ID' => '', 
                'META_TYPE_CODE' => '',
                'META_TYPE_NAME' => '', 
                'META_ICON_ID' => '', 
                'META_ICON_CODE' => '', 
                'FRACTION_RANGE' => '', 
                'PASSWORD_HASH' => '', 
                'STATUS_ID' => '', 
                'STATUS_NAME' => '', 
                'STATUS_COLOR' => '',
                'IS_NEED_LOCK' => '',
                'ICON_NAME' => ''
            );
        }
        
        $row = $this->db->GetRow("
            SELECT 
                MD.META_DATA_ID, 
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME,
                MD.DESCRIPTION,  
                MD.IS_ACTIVE, 
                MD.CREATED_DATE, 
                MD.META_TYPE_ID, 
                LOWER(MT.META_TYPE_CODE) AS META_TYPE_CODE, 
                MT.META_TYPE_NAME, 
                MD.META_ICON_ID, 
                DI.META_ICON_CODE, 
                MD.FRACTION_RANGE, 
                MD.PASSWORD_HASH, 
                MD.STATUS_ID, 
                MS.NAME AS STATUS_NAME, 
                MD.IS_NEED_LOCK, 
                MS.COLOR AS STATUS_COLOR, 
                SU.USERNAME, 
                MD.ICON_NAME 
            FROM META_DATA MD 
                LEFT JOIN META_TYPE MT ON MT.META_TYPE_ID = MD.META_TYPE_ID 
                LEFT JOIN META_DATA_ICON DI ON DI.META_ICON_ID = MD.META_ICON_ID 
                LEFT JOIN META_DATA_STATUS MS ON MS.ID = MD.STATUS_ID 
                LEFT JOIN UM_USER UM ON UM.USER_ID = MD.CREATED_USER_ID 
                LEFT JOIN UM_SYSTEM_USER SU ON SU.USER_ID = UM.SYSTEM_USER_ID 
            WHERE MD.META_DATA_ID = ".$this->db->Param(0), array($metaDataId)
        );

        if ($row) {
            return $row;
        }

        return null;
    }

    public function getMetaDataFilesModel($metaDataId) {
        $data = $this->db->GetAll("
            SELECT 
                FA.ATTACH_ID, 
                FA.ATTACH_NAME, 
                FA.ATTACH, 
                FA.FILE_EXTENSION, 
                FA.FILE_SIZE 
            FROM FILE_ATTACH FA 
                INNER JOIN META_DATA_ATTACH MP ON MP.ATTACH_ID = FA.ATTACH_ID 
            WHERE MP.META_DATA_ID = ".$this->db->Param(0), array($metaDataId));

        return $data;
    }

    public function clearDataViewGridOptions($metaDataId) {
        $this->db->Execute("DELETE FROM META_GROUP_GRID_OPTIONS WHERE MAIN_META_DATA_ID = " . $metaDataId);
    }

    public function clearDataViewProcessBatch($metaDataId) {
        $this->db->Execute("DELETE FROM META_DM_PROCESS_BATCH WHERE MAIN_META_DATA_ID = " . $metaDataId);
    }

    public function clearDataViewExportHeaderFooter($metaDataId) {
        $this->db->Execute("DELETE FROM CUSTOMER_DV_HDR_FTR WHERE META_DATA_ID = " . $metaDataId);
    }
    
    public function checkMetaLock($metaDataId, $sessionUserId = null, $metaTypeId = null) {
        
        if (Config::getFromCache('ds^bH?b..AG5PX*W') && !Session::isCheck(SESSION_PREFIX . 'isUrlAuthenticate')) {
            
            return array('status' => 'error', 'message' => 'Уг үйлдэл боломжгүй. Та DEV дээр засвар оруулна уу!');
        }
        
        if (!Config::getFromCache('CONFIG_IGNORE_CHECK_LOCK')) {
            
            $sessionUserId = ($sessionUserId ? $sessionUserId : Ue::sessionUserId());
            
            $response = $this->ws->getJsonByCurl(Mdmeta::getLockServerAddr().'checkLock/'.$metaDataId.'/'.$sessionUserId);
            
            $json = json_decode($response, true);            
            
            if ($json && ($json['isLocked'] == true || $json['isLocked'] == 'true')) {
                
                $this->view->metaRow = (new Mdmetadata())->getMetaData($metaDataId);
                $this->view->personName = $json['personName'];

                $response = array(
                    'html' => $this->view->renderPrint('system/part/lock/locked', Mdmeta::$viewPath),
                    'title' => 'Түгжсэн',
                    'status' => 'locked', 
                    'metaDataId' => $metaDataId, 
                    'request_btn' => $this->lang->line('request_send'), 
                    'close_btn' => $this->lang->line('close_btn')
                );

                return $response;
            }  
        } 
        
        return false;
    }

    public function saveBpInputParamsModel($metaDataId) {

        if (Input::postCheck('inputParam')) {

            $deleteRows = $changeGroup = $changeField = array();
            $inputParams = $_POST['inputParam'];
            $i = 1;

            foreach ($inputParams as $path => $row) {
                
                $rowId = Input::param($row['rowId']);
                $isNew = Input::param($row['isNew']);
                $isDelete = Input::param($row['isDelete']);
                $isChange = Input::param($row['isChange']);

                if ($isDelete == '1') {
                    
                    $deleteRows[] = $row;
                    
                } else {
                    
                    if ($isChange == '0' && $isNew != '1') {
                        $this->db->AutoExecute('META_PROCESS_PARAM_ATTR_LINK', array('ORDER_NUMBER' => $i), 'UPDATE', 'ID = '.$rowId);
                        $i++;
                        continue;
                    }
                    
                    $isGroup = false;
                    $isOpenPanel = false;
                    
                    $parentId = Input::param($row['parentId']);
                    $paramName = Input::param($row['paramName']);
                    $paramPath = Input::param($row['paramPath']);
                    $isPathChange = Input::param($row['isPathChange']);
                    $dataType = Input::param($row['dataType']);
                    $newRowId = $rowId;
                    $recordType = null;
                
                    if ($dataType == 'row' || $dataType == 'rows') {
                        $recordType = $dataType;
                        $dataType = 'group';
                    }

                    if ($isPathChange == '1') {

                        $row['paramName'] = $paramName;
                        $row['oldParamName'] = Input::param($row['oldParamName']);

                        if (strpos($paramPath, '.') !== false) {
                            $row['newPath'] = substr($paramPath, 0, -(strlen($row['oldParamName']))).$paramName;
                        } else {
                            $row['newPath'] = $paramName;
                        }

                        if ($dataType == 'group') {                        
                            $changeGroup[] = $row;
                        } else {
                            $changeField[] = $row;
                        }
                    }

                    $data = array(
                        'DATA_TYPE' => $dataType, 
                        'LABEL_NAME' => Input::param($row['labelName']), 
                        'PARAM_NAME' => $paramName, 
                        'PARAM_REAL_PATH' => $paramPath, 
                        'PARAM_PATH' => $paramPath, 
                        'RECORD_TYPE' => $recordType, 
                        'IS_SHOW' => isset($row['isShow']) ? 1 : 0, 
                        'IS_REQUIRED' => isset($row['isRequired']) ? 1 : 0,
                        'LOOKUP_META_DATA_ID' => Input::param(Arr::get($row, 'lookupMetaDataId')),
                        'TAB_NAME' => Input::param(Arr::get($row, 'tabName')), 
                        'LOOKUP_TYPE' => Input::param(Arr::get($row, 'lookupType')), 
                        'CHOOSE_TYPE' => Input::param(Arr::get($row, 'chooseType')), 
                        'VALUE_FIELD' => Input::param(Arr::get($row, 'valueField')), 
                        'DISPLAY_FIELD' => Input::param(Arr::get($row, 'displayField')), 
                        'DEFAULT_VALUE' => Input::param(Arr::get($row, 'defaultValue')), 
                        'ORDER_NUMBER' => $i
                    );

                    if (isset($row['featureNum'])) {

                        $data['SIDEBAR_NAME'] = Input::param($row['sidebarName']);
                        $data['FEATURE_NUM'] = Input::param($row['featureNum']);
                        $data['MIN_VALUE'] = Input::param($row['minValue']);
                        $data['MAX_VALUE'] = Input::param($row['maxValue']);
                        $data['SEPARATOR_TYPE'] = Input::param($row['separatorType']);
                        $data['PATTERN_ID'] = Input::param($row['patternId']);
                        $data['IS_REFRESH'] = isset($row['isRefresh']) ? 1 : 0;
                        $data['IS_FREEZE'] = isset($row['isFreeze']) ? 1 : 0;
                        $data['IS_USER_CONFIG'] = isset($row['isUserConfig']) ? 1 : 0;
                        $data['FRACTION_RANGE'] = Input::param(Arr::get($row, 'fractionRange'));
                        $data['GROUPING_NAME'] = Input::param($row['groupingName']);
                        $data['FILE_EXTENSION'] = Input::param(Arr::get($row, 'fileExtension'));
                        $data['EXPRESSION_STRING'] = Str::htmlCharToSingleQuote(Input::param($row['expressionString']));
                        $data['VALUE_CRITERIA'] = Input::param($row['valueCriteria']);
                        $data['PROCESS_GET_PARAM_PATH'] = Input::param($row['processGetParamPath']);
                        $data['GET_PROCESS_META_DATA_ID'] = Input::param($row['processMetaDataId']);
                        $data['THEME_POSITION_NO'] = Input::param($row['themePosition']);
                        $data['RENDER_TYPE'] = Input::param($row['renderType']);
                        $data['COLUMN_WIDTH'] = Input::param($row['columnWidth']);
                        $data['COLUMN_AGGREGATE'] = Input::param($row['columnAggregate']);
                        $data['ICON_NAME'] = Input::param($row['iconName']);
                        $data['LOOKUP_KEY_META_DATA_ID'] = null;
                        $data['OFFLINE_ORDER'] = Input::param(Arr::get($row, 'offlineOrder'));
                        $data['TAB_INDEX'] = Input::param(Arr::get($row, 'tabIndex'));
                        $data['WIZARD_STEP'] = Input::param(Arr::get($row, 'wizardStep'));
                        $data['PLACEHOLDER_NAME'] = Input::param(Arr::get($row, 'placeholderName'));
                        $data['MORE_META_DATA_ID'] = Input::param(Arr::get($row, 'moreMetaDataId'));
                        $data['DTL_BUTTON_NAME'] = Input::param(Arr::get($row, 'dtlButtonName'));
                        $data['GROUPING_NAME'] = Input::param(Arr::get($row, 'groupingName'));
                        $data['IS_THUMBNAIL'] = isset($row['isThumbnail']) ? 1 : null;

                        $isOpenPanel = true;
                    }
                    
                    if ($data['LOOKUP_TYPE'] == 'combo_with_popup') {
                        $data['LOOKUP_KEY_META_DATA_ID'] = Input::param($row['addonLookupMetaDataId']);
                    }

                    if (isset($row['isGroupAddon'])) {

                        $data['SIDEBAR_NAME'] = Input::param($row['sidebarName']);
                        $data['VISIBLE_CRITERIA'] = Input::param($row['visibleCriteria']);
                        $data['IS_SAVE'] = isset($row['isSave']) ? 1 : 0;
                        $data['IS_SHOW_ADD'] = isset($row['isShowAdd']) ? 1 : 0;
                        $data['IS_SHOW_MULTIPLE'] = isset($row['isShowMultiple']) ? 1 : 0;
                        $data['IS_SHOW_DELETE'] = isset($row['isShowDelete']) ? 1 : 0;
                        $data['IS_REFRESH'] = isset($row['isRefresh']) ? 1 : 0;
                        $data['IS_FIRST_ROW'] = isset($row['isFirstRow']) ? 1 : 0;
                        $data['IS_BUTTON'] = isset($row['isButton']) ? 1 : 0;
                        $data['DTL_THEME'] = Arr::get($row, 'dtlTheme');
                        $data['LOOKUP_KEY_META_DATA_ID'] = Input::param($row['lookupKeyMetaDataId']);
                        $data['PAGING_CONFIG'] = Arr::get($row, 'pagingConfig');
                        $data['IS_EXCEL_EXPORT'] = isset($row['isExcelExport']) ? 1 : null;
                        $data['IS_EXCEL_IMPORT'] = isset($row['isExcelImport']) ? 1 : null;
                        $data['IS_PATH_DISPLAY_ORDER'] = isset($row['isPathDisplayOrder']) ? 1 : null;
                        $data['DETAIL_MODIFY_MODE'] = Arr::get($row, 'detailModifyMode');
                        $data['COLUMN_COUNT'] = Arr::get($row, 'columnCount');
                        $data['COLUMN_WIDTH'] = Arr::get($row, 'columnWidth');
                        $data['GROUPING_NAME'] = Arr::get($row, 'groupingName');
                        $data['THEME_POSITION_NO'] = Arr::get($row, 'themePosition');
                        $data['WIZARD_STEP'] = Input::param(Arr::get($row, 'wizardStep'));

                        $isGroup = true;
                        $isOpenPanel = true;
                    }

                    if ($isNew == '1') {
                        
                        $newRowId = isset($row['newRowId']) ? $row['newRowId'] : $rowId;
                        $savedId = $newRowId;
                        
                        $data['ID'] = $newRowId;
                        $data['PARENT_ID'] = isset($row['newParentId']) ? $row['newParentId'] : $parentId;
                            
                        $data['PROCESS_META_DATA_ID'] = $metaDataId;
                        $data['IS_INPUT'] = 1;

                        $this->db->AutoExecute('META_PROCESS_PARAM_ATTR_LINK', $data);

                    } else {
                        $savedId = $rowId;
                        $this->db->AutoExecute('META_PROCESS_PARAM_ATTR_LINK', $data, 'UPDATE', 'ID = '.$rowId);
                    }

                    if ($isOpenPanel) {
                        
                        if (array_key_exists('jsonConfig', $row)) {
                                
                            $rowJsonConfig = trim($row['jsonConfig']);

                            if ($rowJsonConfig) {
                                $this->db->UpdateClob('META_PROCESS_PARAM_ATTR_LINK', 'JSON_CONFIG', $rowJsonConfig, 'ID = '.$savedId);
                            } else {
                                $this->db->AutoExecute('META_PROCESS_PARAM_ATTR_LINK', array('JSON_CONFIG' => null), 'UPDATE', 'ID = '.$savedId);
                            }
                        }
                        
                        self::clearSequenceConfigByPath($metaDataId, $paramPath);
                        
                        if (isset($row['autoNumber'])) {
                            $dataSequenceConfig = array(
                                'ID' => getUID(),
                                'META_DATA_ID' => $metaDataId,
                                'PARAM_NAME' => $paramPath,
                                'CODE_FORMAT' => $row['autoNumberCodeFormat'],
                                'SEQUENCE_FORMAT' => $row['autoNumberSequenceFormat'],
                                'IS_UNIQUE' => isset($row['autoNumberIsUnique']) ? 1 : 0
                            );
                            $this->db->AutoExecute('META_DATA_SEQUENCE_CONFIG', $dataSequenceConfig);                            
                        }

                        self::clearGroupParamConfigByPath($metaDataId, $paramPath);

                        if (isset($data['GET_PROCESS_META_DATA_ID']) 
                            && $data['GET_PROCESS_META_DATA_ID'] != '' 
                            && isset($_POST['paramProcessConfigParamPath'][$paramPath])) {

                            $paramProcessConfigParamPath = $_POST['paramProcessConfigParamPath'][$paramPath];

                            foreach ($paramProcessConfigParamPath as $prok => $processParam) {

                                $dataProcessConfigParamLink = array(
                                    'ID' => getUID(),
                                    'MAIN_PROCESS_META_DATA_ID' => $metaDataId,
                                    'FIELD_PATH' => $paramPath,
                                    'PROCESS_META_DATA_ID' => $data['GET_PROCESS_META_DATA_ID'],
                                    'PARAM_PATH' => Input::param($_POST['paramProcessConfigParamPath'][$paramPath][$prok]),
                                    'PARAM_META_DATA_CODE' => Input::param($_POST['paramProcessConfigParamMeta'][$paramPath][$prok]),
                                    'DEFAULT_VALUE' => Input::param($_POST['paramProcessConfigDefaultVal'][$paramPath][$prok]),
                                    'IS_GROUP' => intval($isGroup)
                                );
                                $this->db->AutoExecute('META_GROUP_PARAM_CONFIG', $dataProcessConfigParamLink);
                            }
                        }

                        if (isset($data['LOOKUP_KEY_META_DATA_ID']) 
                            && $data['LOOKUP_KEY_META_DATA_ID'] != '' 
                            && isset($_POST['paramGroupConfigParamPathKey'][$paramPath])) {

                            $lookupMetaDataId = $data['LOOKUP_KEY_META_DATA_ID'];
                            $paramGroupConfigParamData = $_POST['paramGroupConfigParamPathKey'][$paramPath];

                            foreach ($paramGroupConfigParamData as $rok => $configParam) {

                                $dataConfigParamLink = array(
                                    'ID' => getUID(),
                                    'MAIN_PROCESS_META_DATA_ID' => $metaDataId, 
                                    'FIELD_PATH' => $paramPath,
                                    'LOOKUP_META_DATA_ID' => $lookupMetaDataId,
                                    'PARAM_PATH' => Input::param($_POST['paramGroupConfigParamPathKey'][$paramPath][$rok]),
                                    'PARAM_META_DATA_CODE' => Input::param($_POST['paramGroupConfigParamMetaKey'][$paramPath][$rok]), 
                                    'DEFAULT_VALUE' => Input::param($_POST['paramGroupConfigDefaultValKey'][$paramPath][$rok]),
                                    'IS_GROUP' => intval($isGroup),
                                    'IS_KEY_LOOKUP' => '1'
                                );
                                $this->db->AutoExecute('META_GROUP_PARAM_CONFIG', $dataConfigParamLink);
                            }
                        }

                        if (isset($data['LOOKUP_META_DATA_ID']) 
                            && $data['LOOKUP_META_DATA_ID'] != '' 
                            && isset($_POST['paramGroupConfigParamPath'][$paramPath])) {

                            $lookupMetaDataId = $data['LOOKUP_META_DATA_ID'];
                            $paramGroupConfigParamData = $_POST['paramGroupConfigParamPath'][$paramPath];

                            foreach ($paramGroupConfigParamData as $rok => $configParam) {

                                $dataConfigParamLink = array(
                                    'ID' => getUID(),
                                    'MAIN_PROCESS_META_DATA_ID' => $metaDataId, 
                                    'FIELD_PATH' => $paramPath,
                                    'LOOKUP_META_DATA_ID' => $lookupMetaDataId,
                                    'PARAM_PATH' => Input::param($_POST['paramGroupConfigParamPath'][$paramPath][$rok]),
                                    'PARAM_META_DATA_CODE' => Input::param($_POST['paramGroupConfigParamMeta'][$paramPath][$rok]), 
                                    'DEFAULT_VALUE' => Input::param($_POST['paramGroupConfigDefaultVal'][$paramPath][$rok]),
                                    'IS_GROUP' => intval($isGroup),
                                    'IS_KEY_LOOKUP' => '0'
                                );
                                $this->db->AutoExecute('META_GROUP_PARAM_CONFIG', $dataConfigParamLink);
                            }
                        }

                        self::clearParamValuesByPath($metaDataId, $paramPath);

                        if (isset($_POST['paramDefaultValueId'][$paramPath]) 
                            && isset($data['LOOKUP_META_DATA_ID']) 
                            && $data['LOOKUP_META_DATA_ID'] != '') {

                            $paramDefaultValueIdData = $_POST['paramDefaultValueId'][$paramPath];

                            foreach ($paramDefaultValueIdData as $pvk => $pvkVal) {
                                $paramValue = array(
                                    'ID' => getUID(),
                                    'MAIN_META_DATA_ID' => $metaDataId,
                                    'PARAM_PATH' => $paramPath,
                                    'LOOKUP_META_DATA_ID' => $data['LOOKUP_META_DATA_ID'], 
                                    'VALUE_ID' => $pvkVal
                                );
                                $this->db->AutoExecute('META_PARAM_VALUES', $paramValue);
                            }
                        }

                        if (isset($_POST['paramDefaultValueIdKey'][$paramPath]) 
                            && isset($data['LOOKUP_KEY_META_DATA_ID']) 
                            && $data['LOOKUP_KEY_META_DATA_ID'] != '') {

                            $paramDefaultValueIdKeyData = $_POST['paramDefaultValueIdKey'][$paramPath];

                            foreach ($paramDefaultValueIdKeyData as $pvk => $pvkVal) {
                                $paramValue = array(
                                    'ID' => getUID(),
                                    'MAIN_META_DATA_ID' => $metaDataId,
                                    'PARAM_PATH' => $paramPath,
                                    'LOOKUP_META_DATA_ID' => $data['LOOKUP_KEY_META_DATA_ID'], 
                                    'VALUE_ID' => $pvkVal
                                );
                                $this->db->AutoExecute('META_PARAM_VALUES', $paramValue);
                            }
                        }

                        if (isset($_POST['fieldMappingLookupFieldPath'][$paramPath])) {

                            self::clearOneProcessLookupMap($metaDataId, $paramPath);

                            $fieldMappingLookupFieldPathData = $_POST['fieldMappingLookupFieldPath'][$paramPath];

                            foreach ($fieldMappingLookupFieldPathData as $fmLk => $fmLkVal) {
                                $paramLookupFieldMap = array(
                                    'ID' => getUID(),
                                    'PROCESS_META_DATA_ID' => $metaDataId,
                                    'FIELD_PATH' => $paramPath,
                                    'LOOKUP_FIELD_PATH' => $fmLkVal,
                                    'PARAM_FIELD_PATH' => Input::param($_POST['fieldMappingParamFieldPath'][$paramPath][$fmLk]),
                                    'IS_KEY_LOOKUP' => '0', 
                                    'IS_PROCESS' => '1'
                                );
                                $this->db->AutoExecute('META_PROCESS_LOOKUP_MAP', $paramLookupFieldMap);
                            }
                        }

                        if (isset($_POST['fieldMappingLookupFieldPathKey'][$paramPath])) {

                            self::clearOneProcessKeyLookupMap($metaDataId, $paramPath);

                            $fieldMappingLookupFieldPathData = $_POST['fieldMappingLookupFieldPathKey'][$paramPath];

                            foreach ($fieldMappingLookupFieldPathData as $fmLk => $fmLkVal) {
                                $paramLookupFieldMap = array(
                                    'ID' => getUID(),
                                    'PROCESS_META_DATA_ID' => $metaDataId,
                                    'FIELD_PATH' => $paramPath,
                                    'LOOKUP_FIELD_PATH' => $fmLkVal,
                                    'PARAM_FIELD_PATH' => Input::param($_POST['fieldMappingParamFieldPathKey'][$paramPath][$fmLk]),
                                    'IS_KEY_LOOKUP' => '1', 
                                    'IS_PROCESS' => '1'
                                );
                                $this->db->AutoExecute('META_PROCESS_LOOKUP_MAP', $paramLookupFieldMap);
                            }
                        }

                    }

                    if ($dataType == 'group' && $isNew == '1' && Arr::checkSearchKeyFromArray($inputParams, $paramPath.'.') == false) {
                        
                        if (isset($row['metaDataId'])) {
                            self::importMetaGroupToProcess($metaDataId, $row['metaDataId'], $newRowId, $paramPath);
                        } else {
                            self::importMetaGroupToProcess($metaDataId, null, $newRowId, $paramPath, $rowId);
                        }
                    }
                }
                
                $i++;
            }
            
            if (count($changeField) || count($changeGroup)) {
                
                $idPh = $this->db->Param(0);
                
                $bpExpressionData = $this->db->GetAll("
                    SELECT 
                        ID, 
                        'META_BUSINESS_PROCESS_LINK' AS TABLENAME, 
                        'ID' AS ID_FIELDNAME, 
                        LOAD_EXPRESSION_STRING, 
                        EVENT_EXPRESSION_STRING, 
                        VAR_FNC_EXPRESSION_STRING, 
                        SAVE_EXPRESSION_STRING 
                    FROM META_BUSINESS_PROCESS_LINK 
                    WHERE META_DATA_ID = $idPh 
                        AND (LOAD_EXPRESSION_STRING IS NOT NULL OR EVENT_EXPRESSION_STRING IS NOT NULL OR VAR_FNC_EXPRESSION_STRING IS NOT NULL OR SAVE_EXPRESSION_STRING IS NOT NULL) 

                    UNION ALL 

                    SELECT 
                        ED.ID, 
                        'META_BP_EXPRESSION_DTL' AS TABLENAME, 
                        'ID' AS ID_FIELDNAME, 
                        ED.LOAD_EXPRESSION_STRING, 
                        ED.EVENT_EXPRESSION_STRING, 
                        ED.VAR_FNC_EXPRESSION_STRING, 
                        ED.SAVE_EXPRESSION_STRING 
                    FROM META_BUSINESS_PROCESS_LINK PL 
                        INNER JOIN META_BP_EXPRESSION_DTL ED ON ED.BP_LINK_ID = PL.ID 
                    WHERE PL.META_DATA_ID = $idPh", array($metaDataId));
                
                if ($bpExpressionData) {
                    self::$isPathChangeExpData = true;
                    self::$pathChangeExpData = $bpExpressionData;
                }
                
                self::inputChangedFieldPathUpdate($metaDataId, $changeField, 'paramPath', 'oldParamName', false);
                self::inputChangedGroupPathUpdate($metaDataId, $changeGroup);
                
                if (self::$isPathChangeExpData) {
                    
                    foreach (self::$pathChangeExpData as $expRow) {
                        
                        if ($expRow['LOAD_EXPRESSION_STRING'] !== '') {
                            
                            $this->db->UpdateClob($expRow['TABLENAME'], 'LOAD_EXPRESSION_STRING', $expRow['LOAD_EXPRESSION_STRING'], $expRow['ID_FIELDNAME'].' = ' . $expRow['ID']); 
        
                        }
                        
                        if ($expRow['EVENT_EXPRESSION_STRING'] !== '') {
                            
                            $this->db->UpdateClob($expRow['TABLENAME'], 'EVENT_EXPRESSION_STRING', $expRow['EVENT_EXPRESSION_STRING'], $expRow['ID_FIELDNAME'].' = ' . $expRow['ID']); 
        
                        }
                        
                        if ($expRow['VAR_FNC_EXPRESSION_STRING'] !== '') {
                            
                            $this->db->UpdateClob($expRow['TABLENAME'], 'VAR_FNC_EXPRESSION_STRING', $expRow['VAR_FNC_EXPRESSION_STRING'], $expRow['ID_FIELDNAME'].' = ' . $expRow['ID']); 
        
                        }
                        
                        if ($expRow['SAVE_EXPRESSION_STRING'] !== '') {
                            
                            $this->db->UpdateClob($expRow['TABLENAME'], 'SAVE_EXPRESSION_STRING', $expRow['SAVE_EXPRESSION_STRING'], $expRow['ID_FIELDNAME'].' = ' . $expRow['ID']); 
        
                        }
                    }
                }
            }
            
            self::deleteProcessParamAttrLinks($metaDataId, $deleteRows);
        } 

        return true;
    }
    
    public function inputChangedFieldPathUpdate($metaDataId, $changeFields, $paramPathName, $oldParamName, $isIf, $newParamName = '', $oldGroupName = '', $dotCount = 0) {
        
        if (count($changeFields)) {
            
            foreach ($changeFields as $field) {
                
                if ($isIf) {
                    
                    if ($dotCount > 1) {
                        $field['newPath'] = str_replace('.'.$oldGroupName.'.', '.'.$newParamName.'.', $field[$paramPathName]);
                    } else {
                        $field['newPath'] = $newParamName.'.'.substr($field[$paramPathName], strlen($oldGroupName.'.'), 60);
                    }
                    
                    $field['paramName'] = $newParamName;
                    
                    if ($field['ALIAS_PATH'] !== '') {
                        
                        $this->db->AutoExecute('META_PROCESS_PARAM_LINK', 
                            array(
                                'DO_BP_PARAM_PATH' => $field['newPath']
                            ), 
                            'UPDATE', 
                            "DO_BP_ID = $metaDataId AND DO_BP_PARAM_IS_INPUT = 1 AND LOWER(DO_BP_PARAM_PATH) = LOWER('".$field['ALIAS_PATH']."')"); 

                        $this->db->AutoExecute('META_PROCESS_PARAM_LINK', 
                            array(
                                'DONE_BP_PARAM_PATH' => $field['newPath']
                            ), 
                            'UPDATE', 
                            "DONE_BP_ID = $metaDataId AND DONE_BP_PARAM_IS_INPUT = 1 AND LOWER(DONE_BP_PARAM_PATH) = LOWER('".$field['ALIAS_PATH']."')"); 
                    }
                    
                } else {
                    
                    $isNotEqualPath = $this->db->GetRow("
                        SELECT 
                            PARAM_PATH 
                        FROM META_PROCESS_PARAM_ATTR_LINK 
                        WHERE PROCESS_META_DATA_ID = $metaDataId 
                            AND IS_INPUT = 1 
                            AND LOWER(PARAM_REAL_PATH) = LOWER('".$field[$paramPathName]."') 
                            AND LOWER(PARAM_PATH) != LOWER(PARAM_REAL_PATH)");
                    
                    if ($isNotEqualPath) {
                        
                        $this->db->AutoExecute('META_PROCESS_PARAM_LINK', 
                            array(
                                'DO_BP_PARAM_PATH' => $field['newPath']
                            ), 
                            'UPDATE', 
                            "DO_BP_ID = $metaDataId AND DO_BP_PARAM_IS_INPUT = 1 AND LOWER(DO_BP_PARAM_PATH) = LOWER('".$isNotEqualPath['PARAM_PATH']."')"); 

                        $this->db->AutoExecute('META_PROCESS_PARAM_LINK', 
                            array(
                                'DONE_BP_PARAM_PATH' => $field['newPath']
                            ), 
                            'UPDATE', 
                            "DONE_BP_ID = $metaDataId AND DONE_BP_PARAM_IS_INPUT = 1 AND LOWER(DONE_BP_PARAM_PATH) = LOWER('".$isNotEqualPath['PARAM_PATH']."')"); 
                    }
                    
                }
                
                $this->db->AutoExecute('META_PROCESS_PARAM_LINK', 
                    array(
                        'DO_BP_PARAM_PATH' => $field['newPath']
                    ), 
                    'UPDATE', 
                    "DO_BP_ID = $metaDataId AND DO_BP_PARAM_IS_INPUT = 1 AND LOWER(DO_BP_PARAM_PATH) = LOWER('".$field[$paramPathName]."')"); 
                
                $this->db->AutoExecute('META_PROCESS_PARAM_LINK', 
                    array(
                        'DONE_BP_PARAM_PATH' => $field['newPath']
                    ), 
                    'UPDATE', 
                    "DONE_BP_ID = $metaDataId AND DONE_BP_PARAM_IS_INPUT = 1 AND LOWER(DONE_BP_PARAM_PATH) = LOWER('".$field[$paramPathName]."')"); 
                
                $this->db->AutoExecute('META_PROCESS_PARAM_ATTR_LINK', 
                    array(
                        'PARAM_REAL_PATH' => $field['newPath'], 
                        'PARAM_PATH' => $field['newPath'] 
                    ), 
                    'UPDATE', 
                    "PROCESS_META_DATA_ID = $metaDataId AND IS_INPUT = 1 AND LOWER(PARAM_REAL_PATH) = LOWER('".$field[$paramPathName]."')"); 
                
                // --------------------------------------
                
                $this->db->AutoExecute('META_GROUP_PARAM_CONFIG', 
                    array(
                        'FIELD_PATH' => $field['newPath'] 
                    ), 
                    'UPDATE', 
                    "MAIN_PROCESS_META_DATA_ID = $metaDataId AND LOWER(FIELD_PATH) = LOWER('".$field[$paramPathName]."')");
                
                $this->db->AutoExecute('META_GROUP_PARAM_CONFIG', 
                    array(
                        'PARAM_PATH' => $field['newPath'] 
                    ), 
                    'UPDATE', 
                    "MAIN_PROCESS_META_DATA_ID = $metaDataId AND LOWER(PARAM_PATH) = LOWER('".$field[$paramPathName]."')");
                
                $this->db->AutoExecute('META_PARAM_VALUES', 
                    array(
                        'PARAM_PATH' => $field['newPath'] 
                    ), 
                    'UPDATE', 
                    "MAIN_META_DATA_ID = $metaDataId AND LOWER(PARAM_PATH) = LOWER('".$field[$paramPathName]."')");
                
                $this->db->AutoExecute('META_PROCESS_LOOKUP_MAP', 
                    array(
                        'FIELD_PATH' => $field['newPath'] 
                    ), 
                    'UPDATE', 
                    "PROCESS_META_DATA_ID = $metaDataId AND LOWER(FIELD_PATH) = LOWER('".$field[$paramPathName]."')");
                
                $this->db->AutoExecute('META_SRC_TRG_PARAM', 
                    array(
                        'SRC_PARAM_NAME' => $field['newPath']
                    ), 
                    'UPDATE', 
                    "SRC_META_DATA_ID = $metaDataId AND LOWER(SRC_PARAM_NAME) = LOWER('".$field[$paramPathName]."')");
                
                $this->db->AutoExecute('META_SRC_TRG_PARAM', 
                    array(
                        'TRG_PARAM_NAME' => $field['newPath']
                    ), 
                    'UPDATE', 
                    "TRG_META_DATA_ID = $metaDataId AND LOWER(TRG_PARAM_NAME) = LOWER('".$field[$paramPathName]."')");
                
                $this->db->AutoExecute('META_WORKSPACE_PARAM_MAP', 
                    array(
                        'FIELD_PATH' => $field['newPath']
                    ), 
                    'UPDATE', 
                    "TARGET_META_ID = $metaDataId AND LOWER(FIELD_PATH) = LOWER('".$field[$paramPathName]."')");
                
                $this->db->AutoExecute('META_WORKSPACE_PARAM_MAP', 
                    array(
                        'PARAM_PATH' => $field['newPath']
                    ), 
                    'UPDATE', 
                    "TARGET_META_ID = $metaDataId AND IS_TARGET = 1 AND LOWER(PARAM_PATH) = LOWER('".$field[$paramPathName]."')");
                
                if (self::$isPathChangeExpData) {
                    
                    foreach (self::$pathChangeExpData as $key => $expRow) {
                        
                        if ($expRow['LOAD_EXPRESSION_STRING'] !== '') {
                            
                            self::$pathChangeExpData[$key]['LOAD_EXPRESSION_STRING'] = preg_replace('/(?<![a-zA-Z0-9.])'.$field[$paramPathName].'(?![a-zA-Z0-9.])/', $field['newPath'], $expRow['LOAD_EXPRESSION_STRING']);
        
                        }
                        
                        if ($expRow['EVENT_EXPRESSION_STRING'] !== '') {
                            
                            self::$pathChangeExpData[$key]['EVENT_EXPRESSION_STRING'] = preg_replace('/(?<![a-zA-Z0-9.])'.$field[$paramPathName].'(?![a-zA-Z0-9.])/', $field['newPath'], $expRow['EVENT_EXPRESSION_STRING']);
        
                        }
                        
                        if ($expRow['VAR_FNC_EXPRESSION_STRING'] !== '') {
                            
                            self::$pathChangeExpData[$key]['VAR_FNC_EXPRESSION_STRING'] = preg_replace('/(?<![a-zA-Z0-9.])'.$field[$paramPathName].'(?![a-zA-Z0-9.])/', $field['newPath'], $expRow['VAR_FNC_EXPRESSION_STRING']);
        
                        }
                        
                        if ($expRow['SAVE_EXPRESSION_STRING'] !== '') {
                            
                            self::$pathChangeExpData[$key]['SAVE_EXPRESSION_STRING'] = preg_replace('/(?<![a-zA-Z0-9.])'.$field[$paramPathName].'(?![a-zA-Z0-9.])/', $field['newPath'], $expRow['SAVE_EXPRESSION_STRING']);
        
                        }
                    }
                }
                
                self::changeProcessInputPathConfigs($metaDataId, $field[$paramPathName], $field['newPath']);
                
            }
        }
        
        return true;
    }
    
    public function inputChangedGroupPathUpdate($metaDataId, $changeGroups) {
        
        if (count($changeGroups)) {
            
            foreach ($changeGroups as $group) {
                
                self::inputChangedFieldPathUpdate($metaDataId, array($group), 'paramPath', 'oldParamName', false);
                
                $data = $this->db->GetAll("
                    SELECT 
                        PARAM_REAL_PATH AS OLD_PATH, 
                        PARAM_NAME AS OLD_PARAM_NAME, 
                        CASE WHEN LOWER(PARAM_PATH) != LOWER(PARAM_REAL_PATH)
                            THEN PARAM_PATH
                            ELSE NULL  
                        END AS ALIAS_PATH
                    FROM META_PROCESS_PARAM_ATTR_LINK 
                    WHERE PROCESS_META_DATA_ID = $metaDataId 
                        AND IS_INPUT = 1 
                        AND LOWER(PARAM_REAL_PATH) LIKE LOWER('".$group['paramPath'].".%') 
                    ORDER BY ORDER_NUMBER ASC");
                
                self::inputChangedFieldPathUpdate($metaDataId, $data, 'OLD_PATH', 'OLD_PARAM_NAME', true, $group['paramName'], $group['oldParamName'], substr_count($group['newPath'], '.'));
            }
        }
        
        return true;
    }
    
    public function changeProcessInputPathConfigs($metaDataId, $oldPath, $newPath) {
        
        $dataDrillPrmLink = $this->db->GetAll("
            SELECT 
                PRM.ID, 
                PRM.TRG_PARAM 
            FROM META_DM_DRILLDOWN_PARAM PRM 
                INNER JOIN META_DM_DRILLDOWN_DTL DTL ON DTL.ID = PRM.DM_DRILLDOWN_DTL_ID 
            WHERE DTL.LINK_META_DATA_ID = $metaDataId 
                AND LOWER(PRM.TRG_PARAM) = LOWER('$oldPath')");
        
        if ($dataDrillPrmLink) {
            
            foreach ($dataDrillPrmLink as $drillPrmLink) {
                    
                $this->db->AutoExecute('META_DM_DRILLDOWN_PARAM', 
                    array(
                        'TRG_PARAM' => $newPath
                    ), 
                    'UPDATE', 
                    'ID = '.$drillPrmLink['ID']);
            }
        }
        
        return true;
    }
    
    public function importMetaGroupToProcess($processMetaDataId, $mainMetaDataId = null, $parentId = null, $paramPath, $rowId = null) {
        
        if ($mainMetaDataId) {
            
            $where = "MAIN_META_DATA_ID = $mainMetaDataId ";
            $isPathMerge = true;
            
        } elseif ($rowId) {
            
            $where = "PARENT_ID = $rowId ";
            $isPathMerge = false;
        }
        
        $data = $this->db->GetAll("
            SELECT 
                ID,
                PARENT_ID, 
                IS_SHOW,                          
                ".$this->db->IfNull('IS_REQUIRED', '0')." AS IS_REQUIRED,                       
                MIN_VALUE,                     
                MAX_VALUE,                        
                DEFAULT_VALUE,                    
                RECORD_TYPE,                       
                LOOKUP_META_DATA_ID, 
                LOOKUP_KEY_META_DATA_ID, 
                LOOKUP_TYPE,                       
                DISPLAY_FIELD,                     
                VALUE_FIELD,                       
                CHOOSE_TYPE,                       
                PATTERN_ID, 
                FIELD_PATH, 
                PARAM_NAME, 
                SIDEBAR_NAME, 
                TAB_NAME, 
                FEATURE_NUM, 
                COLUMN_AGGREGATE, 
                LABEL_NAME, 
                SEPARATOR_TYPE, 
                FRACTION_RANGE, 
                DATA_TYPE, 
                FILE_EXTENSION, 
                DISPLAY_ORDER 
            FROM META_GROUP_CONFIG 
            WHERE $where 
            ORDER BY DISPLAY_ORDER ASC");
        
        if ($data) {
            
            $updateParentIds = $parentIds = array();
            
            foreach ($data as $row) {
                
                $id = getUID();
                
                if ($row['PARENT_ID'] != '' && $isPathMerge == false) {
                    
                    $removeLastPath = substr($paramPath, 0, strrpos($paramPath, '.'));
                    
                    $getLastPath  = substr($removeLastPath, strrpos($removeLastPath, '.') + 1);
                    $getFirstPath = strtok($row['FIELD_PATH'], '.');
                    
                    if ($getLastPath == $getFirstPath) {
                        $newFieldPath = $removeLastPath . '.' . substr($row['FIELD_PATH'], strlen($getFirstPath.'.'), 200);
                    } else {
                        $newFieldPath = $removeLastPath . '.' . $row['FIELD_PATH'];
                    }

                    if ($row['DATA_TYPE'] == 'group') {
                        
                        $parentIds[] = array(
                            'rowId'       => $row['ID'], 
                            'newParentId' => $id, 
                            'path'        => $paramPath
                        );
                    }
                    
                } else {
                    $newFieldPath = $paramPath . '.' . $row['FIELD_PATH'];
                }
                
                $insertData = array(
                    'ID' => $id, 
                    'PROCESS_META_DATA_ID' => $processMetaDataId,
                    'IS_SHOW' => $row['IS_SHOW'],                          
                    'IS_REQUIRED' => $row['IS_REQUIRED'],                      
                    'MIN_VALUE' => $row['MIN_VALUE'],                     
                    'MAX_VALUE' => $row['MAX_VALUE'],                        
                    'DEFAULT_VALUE' => $row['DEFAULT_VALUE'],                    
                    'RECORD_TYPE' => $row['RECORD_TYPE'],                       
                    'LOOKUP_META_DATA_ID' => $row['LOOKUP_META_DATA_ID'], 
                    'LOOKUP_KEY_META_DATA_ID' => $row['LOOKUP_KEY_META_DATA_ID'], 
                    'LOOKUP_TYPE' => $row['LOOKUP_TYPE'],                       
                    'DISPLAY_FIELD' => $row['DISPLAY_FIELD'],                     
                    'VALUE_FIELD' => $row['VALUE_FIELD'],                       
                    'CHOOSE_TYPE' => $row['CHOOSE_TYPE'],                       
                    'PATTERN_ID' => $row['PATTERN_ID'], 
                    'PARAM_NAME' => $row['PARAM_NAME'], 
                    'SIDEBAR_NAME' => $row['SIDEBAR_NAME'], 
                    'TAB_NAME' => $row['TAB_NAME'], 
                    'FEATURE_NUM' => $row['FEATURE_NUM'], 
                    'COLUMN_AGGREGATE' => $row['COLUMN_AGGREGATE'], 
                    'LABEL_NAME' => $row['LABEL_NAME'], 
                    'SEPARATOR_TYPE' => $row['SEPARATOR_TYPE'], 
                    'FRACTION_RANGE' => $row['FRACTION_RANGE'], 
                    'DATA_TYPE' => $row['DATA_TYPE'], 
                    'FILE_EXTENSION' => $row['FILE_EXTENSION'], 
                    'PARAM_REAL_PATH' => $newFieldPath, 
                    'PARAM_PATH' => $newFieldPath, 
                    'IS_INPUT' => 1, 
                    'ORDER_NUMBER' => $row['DISPLAY_ORDER']
                );
                
                if ($isPathMerge) {
                    if (empty($row['PARENT_ID'])) {
                        $insertData['PARENT_ID'] = $parentId;
                    } else {
                        $insertData['PARENT_ID'] = $row['PARENT_ID'];
                    }
                } else {
                    $insertData['PARENT_ID'] = $parentId;
                }
                
                if (!empty($row['RECORD_TYPE'])) {
                    $updateParentIds[$row['ID']] = $id;
                }

                $this->db->AutoExecute('META_PROCESS_PARAM_ATTR_LINK', $insertData);
            }
            
            if (count($updateParentIds) > 0) {
                
                foreach ($updateParentIds as $oldId => $newId) {
                    
                    $this->db->AutoExecute('META_PROCESS_PARAM_ATTR_LINK', 
                        array(
                            'PARENT_ID' => $newId 
                        ), 
                        'UPDATE', 
                        "PROCESS_META_DATA_ID = $processMetaDataId AND IS_INPUT = 1 AND PARENT_ID = $oldId"); 
                }
            }
            
            if (count($parentIds) > 0) {
                
                foreach ($parentIds as $pId) {
                    self::importMetaGroupToProcess($processMetaDataId, null, $pId['newParentId'], $pId['path'], $pId['rowId']);
                }
            }
        }

        return true;
    }

    public function deleteProcessParamAttrLinks($metaDataId, $deleteRows) {

        if (count($deleteRows)) {

            foreach ($deleteRows as $row) {

                $dataType = $row['dataType'];
                $rowId = $row['rowId'];

                if ($dataType == 'row' || $dataType == 'rows') {
                    $paramPath = strtolower($row['paramPath']);
                    $this->db->Execute("DELETE FROM META_PROCESS_PARAM_ATTR_LINK WHERE PROCESS_META_DATA_ID = $metaDataId AND IS_INPUT = 1 AND LOWER(PARAM_REAL_PATH) LIKE '$paramPath.%'");
                } 

                $this->db->Execute("DELETE FROM META_PROCESS_PARAM_ATTR_LINK WHERE ID = $rowId");
            }

            $this->db->Execute("
                DELETE 
                    FROM META_PROCESS_LOOKUP_MAP 
                WHERE IS_PROCESS = 1 
                    AND PROCESS_META_DATA_ID = $metaDataId 
                    AND LOWER(FIELD_PATH) NOT IN (
                        SELECT 
                            LOWER(PARAM_REAL_PATH) 
                        FROM META_PROCESS_PARAM_ATTR_LINK 
                        WHERE PROCESS_META_DATA_ID = $metaDataId 
                            AND IS_INPUT = 1 
                    )"); 

            $this->db->Execute("
                DELETE 
                    FROM META_GROUP_PARAM_CONFIG  
                WHERE MAIN_PROCESS_META_DATA_ID = $metaDataId 
                    AND LOWER(FIELD_PATH) NOT IN (
                        SELECT 
                            LOWER(PARAM_REAL_PATH) 
                        FROM META_PROCESS_PARAM_ATTR_LINK 
                        WHERE PROCESS_META_DATA_ID = $metaDataId 
                            AND IS_INPUT = 1 
                    )"); 
        }

        return true;
    }

    public function saveBpOutputParamsModel($metaDataId) {

        if (Input::postCheck('outputParam')) {

            $deleteRows = $changeGroup = $changeField = array();
            $outputParams = $_POST['outputParam'];
            $i = 1;

            foreach ($outputParams as $path => $row) {

                $rowId = Input::param($row['rowId']);
                $parentId = Input::param($row['parentId']);
                $isNew = Input::param($row['isNew']);
                $isDelete = Input::param($row['isDelete']);
                $paramName = Input::param($row['paramName']);
                $paramPath = Input::param($row['paramPath']);
                $isPathChange = Input::param($row['isPathChange']);
                $dataType = Input::param($row['dataType']);
                $newRowId = $rowId;
                $recordType = null;

                if ($isDelete == '1') {
                    $deleteRows[] = $row;
                }

                if ($dataType == 'row' || $dataType == 'rows') {
                    $recordType = $dataType;
                    $dataType = 'group';
                }
                
                if ($isPathChange == '1') {
                    
                    $row['paramName'] = $paramName;
                    $row['oldParamName'] = Input::param($row['oldParamName']);
                    
                    if (strpos($paramPath, '.') !== false) {
                        $row['newPath'] = rtrim($paramPath, '.'.$row['oldParamName']).'.'.$paramName;
                    } else {
                        $row['newPath'] = $paramName;
                    }
                        
                    if ($dataType == 'group') {                        
                        
                        $changeGroup[] = $row;
                        
                    } else {
                        
                        $changeField[] = $row;
                    }
                }

                $data = array(
                    'DATA_TYPE' => $dataType, 
                    'LABEL_NAME' => Input::param($row['labelName']), 
                    'PARAM_NAME' => $paramName, 
                    'PARAM_REAL_PATH' => $paramPath, 
                    'PARAM_PATH' => $paramPath, 
                    'RECORD_TYPE' => $recordType, 
                    'IS_SHOW' => isset($row['isShow']) ? 1 : 0, 
                    'ORDER_NUMBER' => $i
                );

                if ($isNew == '1') {
                    
                    $newRowId = isset($row['newRowId']) ? $row['newRowId'] : $rowId;
                    
                    $data['ID'] = $newRowId;
                    $data['PARENT_ID'] = isset($row['newParentId']) ? $row['newParentId'] : $parentId;
                        
                    $data['PROCESS_META_DATA_ID'] = $metaDataId;
                    $data['IS_INPUT'] = 0;
                    $data['IS_REQUIRED'] = 0;

                    $this->db->AutoExecute('META_PROCESS_PARAM_ATTR_LINK', $data);

                } else {
                    $this->db->AutoExecute('META_PROCESS_PARAM_ATTR_LINK', $data, 'UPDATE', 'ID = '.$rowId);
                }
                
                if ($dataType == 'group' && $isNew == '1' && Arr::checkSearchKeyFromArray($outputParams, $paramPath.'.') == false) {
                        
                    if (isset($row['metaDataId'])) {
                        self::importMetaGroupToProcessOutput($metaDataId, $row['metaDataId'], $newRowId, $paramPath);
                    } else {
                        self::importMetaGroupToProcessOutput($metaDataId, null, $newRowId, $paramPath, $rowId);
                    }
                }

                $i++;
            }
            
            self::outputChangedFieldPathUpdate($metaDataId, $changeField, 'paramPath', 'oldParamName', false);
            self::outputChangedGroupPathUpdate($metaDataId, $changeGroup);
            
            self::deleteProcessOutputParamAttrLinks($metaDataId, $deleteRows);
        } 

        return true;
    }
    
    public function outputChangedFieldPathUpdate($metaDataId, $changeFields, $paramPathName, $oldParamName, $isIf, $newParamName = '', $oldGroupName = '', $dotCount = 0) {
        
        if (count($changeFields)) {
            
            foreach ($changeFields as $field) {
                
                if ($isIf) {
                    
                    if ($dotCount > 1) {
                        $field['newPath'] = str_replace('.'.$oldGroupName.'.', '.'.$newParamName.'.', $field[$paramPathName]);
                    } else {
                        $field['newPath'] = $newParamName.'.'.ltrim($field[$paramPathName], $oldGroupName.'.');
                    }
                    
                    $field['paramName'] = $newParamName;
                    
                    if ($field['ALIAS_PATH'] !== '') {
                        
                        $this->db->AutoExecute('META_PROCESS_PARAM_LINK', 
                            array(
                                'DO_BP_PARAM_PATH' => $field['newPath']
                            ), 
                            'UPDATE', 
                            "DO_BP_ID = $metaDataId AND DO_BP_PARAM_IS_INPUT = 0 AND LOWER(DO_BP_PARAM_PATH) = LOWER('".$field['ALIAS_PATH']."')"); 

                        $this->db->AutoExecute('META_PROCESS_PARAM_LINK', 
                            array(
                                'DONE_BP_PARAM_PATH' => $field['newPath']
                            ), 
                            'UPDATE', 
                            "DONE_BP_ID = $metaDataId AND DONE_BP_PARAM_IS_INPUT = 0 AND LOWER(DONE_BP_PARAM_PATH) = LOWER('".$field['ALIAS_PATH']."')"); 
                    }
                    
                } else {
                    
                    $isNotEqualPath = $this->db->GetRow("
                        SELECT 
                            PARAM_PATH 
                        FROM META_PROCESS_PARAM_ATTR_LINK 
                        WHERE PROCESS_META_DATA_ID = $metaDataId 
                            AND IS_INPUT = 0 
                            AND LOWER(PARAM_REAL_PATH) = LOWER('".$field[$paramPathName]."') 
                            AND LOWER(PARAM_PATH) != LOWER(PARAM_REAL_PATH)");
                    
                    if ($isNotEqualPath) {
                        
                        $this->db->AutoExecute('META_PROCESS_PARAM_LINK', 
                            array(
                                'DO_BP_PARAM_PATH' => $field['newPath']
                            ), 
                            'UPDATE', 
                            "DO_BP_ID = $metaDataId AND DO_BP_PARAM_IS_INPUT = 0 AND LOWER(DO_BP_PARAM_PATH) = LOWER('".$isNotEqualPath['PARAM_PATH']."')"); 

                        $this->db->AutoExecute('META_PROCESS_PARAM_LINK', 
                            array(
                                'DONE_BP_PARAM_PATH' => $field['newPath']
                            ), 
                            'UPDATE', 
                            "DONE_BP_ID = $metaDataId AND DONE_BP_PARAM_IS_INPUT = 0 AND LOWER(DONE_BP_PARAM_PATH) = LOWER('".$isNotEqualPath['PARAM_PATH']."')"); 
                    }
                    
                }
                
                $this->db->AutoExecute('META_PROCESS_PARAM_LINK', 
                    array(
                        'DO_BP_PARAM_PATH' => $field['newPath']
                    ), 
                    'UPDATE', 
                    "DO_BP_ID = $metaDataId AND DO_BP_PARAM_IS_INPUT = 0 AND LOWER(DO_BP_PARAM_PATH) = LOWER('".$field[$paramPathName]."')"); 
                
                $this->db->AutoExecute('META_PROCESS_PARAM_LINK', 
                    array(
                        'DONE_BP_PARAM_PATH' => $field['newPath']
                    ), 
                    'UPDATE', 
                    "DONE_BP_ID = $metaDataId AND DONE_BP_PARAM_IS_INPUT = 0 AND LOWER(DONE_BP_PARAM_PATH) = LOWER('".$field[$paramPathName]."')"); 
                
                $this->db->AutoExecute('META_PROCESS_PARAM_ATTR_LINK', 
                    array(
                        'PARAM_REAL_PATH' => $field['newPath'], 
                        'PARAM_PATH' => $field['newPath'] 
                    ), 
                    'UPDATE', 
                    "PROCESS_META_DATA_ID = $metaDataId AND IS_INPUT = 0 AND LOWER(PARAM_REAL_PATH) = LOWER('".$field[$paramPathName]."')"); 
            }
        }
        
        return true;
    }
    
    public function outputChangedGroupPathUpdate($metaDataId, $changeGroups) {
        
        if (count($changeGroups)) {
            
            foreach ($changeGroups as $group) {
                
                self::outputChangedFieldPathUpdate($metaDataId, array($group), 'paramPath', 'oldParamName', false);
                
                $data = $this->db->GetAll("
                    SELECT 
                        PARAM_REAL_PATH AS OLD_PATH, 
                        PARAM_NAME AS OLD_PARAM_NAME, 
                        CASE WHEN LOWER(PARAM_PATH) != LOWER(PARAM_REAL_PATH)
                            THEN PARAM_PATH
                            ELSE NULL  
                        END AS ALIAS_PATH
                    FROM META_PROCESS_PARAM_ATTR_LINK 
                    WHERE PROCESS_META_DATA_ID = $metaDataId 
                        AND IS_INPUT = 0 
                        AND LOWER(PARAM_REAL_PATH) LIKE LOWER('".$group['paramPath'].".%') 
                    ORDER BY ORDER_NUMBER ASC");
                
                self::outputChangedFieldPathUpdate($metaDataId, $data, 'OLD_PATH', 'OLD_PARAM_NAME', true, $group['paramName'], $group['oldParamName'], substr_count($group['newPath'], '.'));
            }
        }
        
        return true;
    }

    public function importMetaGroupToProcessOutput($processMetaDataId, $mainMetaDataId = null, $parentId = null, $paramPath, $rowId = null) {
        
        if ($mainMetaDataId) {
            
            $where = "MAIN_META_DATA_ID = $mainMetaDataId ";
            $isPathMerge = true;
            
        } elseif ($rowId) {
            
            $where = "PARENT_ID = $rowId ";
            $isPathMerge = false;
        }
        
        $data = $this->db->GetAll("
            SELECT 
                ID,
                PARENT_ID, 
                IS_SHOW, 
                RECORD_TYPE,
                FIELD_PATH, 
                PARAM_NAME,  
                LABEL_NAME, 
                DATA_TYPE, 
                DISPLAY_ORDER 
            FROM META_GROUP_CONFIG 
            WHERE $where 
            ORDER BY DISPLAY_ORDER ASC");

        if ($data) {
            
            $updateParentIds = array();
            
            foreach ($data as $row) {
                
                $id = getUID();
                
                $insertData = array(
                    'ID' => $id, 
                    'PROCESS_META_DATA_ID' => $processMetaDataId,
                    'IS_SHOW' => $row['IS_SHOW'],
                    'IS_REQUIRED' => '0',
                    'RECORD_TYPE' => $row['RECORD_TYPE'],
                    'PARAM_NAME' => $row['PARAM_NAME'], 
                    'LABEL_NAME' => $row['LABEL_NAME'], 
                    'DATA_TYPE' => $row['DATA_TYPE'], 
                    'PARAM_REAL_PATH' => $paramPath.'.'.$row['FIELD_PATH'], 
                    'PARAM_PATH' => $paramPath.'.'.$row['FIELD_PATH'], 
                    'IS_INPUT' => 0, 
                    'ORDER_NUMBER' => $row['DISPLAY_ORDER']
                );
                
                if ($isPathMerge) {
                    if (empty($row['PARENT_ID'])) {
                        $insertData['PARENT_ID'] = $parentId;
                    } else {
                        $insertData['PARENT_ID'] = $row['PARENT_ID'];
                    }
                } else {
                    $insertData['PARENT_ID'] = $parentId;
                }
                
                if (!empty($row['RECORD_TYPE'])) {
                    $updateParentIds[$row['ID']] = $id;
                }

                $this->db->AutoExecute('META_PROCESS_PARAM_ATTR_LINK', $insertData);
            }
            
            if (count($updateParentIds) > 0) {
                
                foreach ($updateParentIds as $oldId => $newId) {
                    
                    $this->db->AutoExecute('META_PROCESS_PARAM_ATTR_LINK', 
                        array(
                            'PARENT_ID' => $newId 
                        ), 
                        'UPDATE', 
                        "PROCESS_META_DATA_ID = $processMetaDataId AND IS_INPUT = 0 AND PARENT_ID = $oldId"); 
                }
            }
        }

        return true;
    }
    
    public function deleteProcessOutputParamAttrLinks($metaDataId, $deleteRows) {

        if (count($deleteRows)) {

            foreach ($deleteRows as $row) {

                $dataType = $row['dataType'];
                $rowId = $row['rowId'];

                if ($dataType == 'row' || $dataType == 'rows') {
                    $paramPath = strtolower($row['paramPath']);
                    $this->db->Execute("DELETE FROM META_PROCESS_PARAM_ATTR_LINK WHERE PROCESS_META_DATA_ID = $metaDataId AND IS_INPUT = 0 AND LOWER(PARAM_REAL_PATH) LIKE '$paramPath.%'");
                } 

                $this->db->Execute("DELETE FROM META_PROCESS_PARAM_ATTR_LINK WHERE ID = $rowId");
            }
        }

        return true;
    }
    
    public function saveGroupParamsModel($metaDataId) {

        if (Input::postCheck('groupParam')) {

            $deleteRows = $changeGroup = $changeField = array();
            $groupParams = $_POST['groupParam'];
            $i = 1;
            $n = 1;
            
            foreach ($groupParams as $path => $row) {
                
                if (isset($row['rowId']) && isset($row['parentId'])) {

                    $rowId = Input::param($row['rowId']);
                    $isNew = Input::param($row['isNew']);
                    $isDelete = Input::param($row['isDelete']);
                    $isChange = Input::param($row['isChange']);

                    if ($isDelete == '1') {
                        $deleteRows[] = $row;
                    } else {
                        
                        if ($isChange == '0' && $isNew != '1') {
                            $this->db->AutoExecute('META_GROUP_CONFIG', array('DISPLAY_ORDER' => $i), 'UPDATE', 'ID = '.$rowId);
                            $i++;
                            continue;
                        }
                    
                        $isGroup = false;
                        $isOpenPanel = false;
                        
                        $parentId = Input::param($row['parentId']);
                        $paramName = Input::param($row['paramName']);
                        $paramPath = Input::param($row['paramPath']);
                        $isPathChange = Input::param($row['isPathChange']);
                        $dataType = Input::param($row['dataType']);
                        $newRowId = $rowId;
                        $recordType = null;
                        
                        if ($dataType === 'row' || $dataType === 'rows') {
                            $recordType = $dataType;
                            $dataType = 'group';
                        }

                        if ($isPathChange === '1') {

                            $row['paramName'] = $paramName;
                            $row['oldParamName'] = Input::param($row['oldParamName']);

                            if (strpos($paramPath, '.') !== false) {
                                $row['newPath'] = substr($paramPath, 0, -(strlen($row['oldParamName']))).$paramName;
                            } else {
                                $row['newPath'] = $paramName;
                            }

                            if ($dataType == 'group') {                        
                                $changeGroup[] = $row;
                            } else {
                                $changeField[] = $row;
                            }
                        }

                        $data = array(
                            'DATA_TYPE' => $dataType, 
                            'LABEL_NAME' => Input::param($row['labelName']), 
                            'PARAM_NAME' => $paramName, 
                            'FIELD_PATH' => $paramPath, 
                            'RECORD_TYPE' => $recordType, 
                            'IS_SHOW' => isset($row['isShow']) ? 1 : 0, 
                            'IS_REQUIRED' => isset($row['isRequired']) ? 1 : 0,
                            'IS_SELECT' => isset($row['isSelect']) ? 1 : 0,
                            'IS_SHOW_BASKET' => isset($row['isShowBasket']) ? 1 : 0,
                            'IS_RENDER_SHOW' => isset($row['isRenderShow']) ? 1 : 0,
                            'IS_CRITERIA' => isset($row['isCriteria']) ? 1 : 0,
                            'LOOKUP_META_DATA_ID' => Input::param(Arr::get($row, 'lookupMetaDataId')),
                            'TAB_NAME' => Input::param(Arr::get($row, 'tabName')), 
                            'LOOKUP_TYPE' => Input::param(Arr::get($row, 'lookupType')), 
                            'CHOOSE_TYPE' => Input::param(Arr::get($row, 'chooseType')), 
                            'VALUE_FIELD' => Input::param(Arr::get($row, 'valueField')), 
                            'DISPLAY_FIELD' => Input::param(Arr::get($row, 'displayField')), 
                            'DEFAULT_VALUE' => Input::param(Arr::get($row, 'defaultValue')), 
                            'INPUT_NAME' => Input::param(Arr::get($row, 'standartField')), 
                            'COLUMN_AGGREGATE' => Input::param(Arr::get($row, 'columnAggregate')), 
                            'BODY_ALIGN' => Input::param(Arr::get($row, 'bodyAlign')), 
                            'HEADER_ALIGN' => Input::param(Arr::get($row, 'headerAlign')), 
                            'TEXT_COLOR' => Input::param(Arr::get($row, 'textColor')), 
                            'TEXT_TRANSFORM' => Input::param(Arr::get($row, 'textTransform')), 
                            'TEXT_WEIGHT' => Input::param(Arr::get($row, 'textWeight')),
                            'BG_COLOR' => Input::param(Arr::get($row, 'bgColor')),
                            'FONT_SIZE' => Input::param(Arr::get($row, 'fontSize')),
                            'AGGREGATE_ALIAS_PATH' => Input::param(Arr::get($row, 'aggregateAliasPath')),
                            'COLUMN_NAME' => Input::param(Arr::get($row, 'columnName')),
                            'DISPLAY_ORDER' => $i
                        );

                        if (isset($row['featureNum'])) {

                            $data['SIDEBAR_NAME'] = Input::param($row['sidebarName']);
                            $data['FEATURE_NUM'] = Input::param($row['featureNum']);
                            $data['MIN_VALUE'] = Input::param($row['minValue']);
                            $data['MAX_VALUE'] = Input::param($row['maxValue']);
                            $data['SEPARATOR_TYPE'] = Input::param($row['separatorType']);
                            $data['PATTERN_ID'] = Input::param($row['patternId']);
                            $data['DATA_MASK'] = Input::param($row['dataMask']);
                            $data['IS_REFRESH'] = isset($row['isRefresh']) ? 1 : null;
                            $data['FRACTION_RANGE'] = Input::param(Arr::get($row, 'fractionRange'));
                            $data['SEARCH_GROUPING_NAME'] = Input::param($row['searchGroupName']);
                            $data['REF_STRUCTURE_ID'] = Input::param(Arr::get($row, 'refStructureId'));
                            $data['REF_PARAM_NAME'] = Input::param(Arr::get($row, 'refParamName'));
                            $data['ORDER_NUMBER'] = Input::param(Arr::get($row, 'orderNumber'));
                            $data['IS_TRANSLATE'] = isset($row['isTranslate']) ? 1 : null;
                            $data['IS_SHOW_MOBILE'] = isset($row['isShowMobile']) ? 1 : 0;
                            $data['IS_MERGE'] = isset($row['isMerge']) ? 1 : null;
                            $data['IS_COUNTCARD'] = isset($row['isCountCard']) ? 1 : null;
                            $data['IS_CRITERIA_SHOW_BASKET'] = isset($row['isCriteriaShowBasket']) ? 1 : null;
                            $data['IS_MANDATORY_CRITERIA'] = isset($row['isMandatoryCriteria']) ? 1 : null;
                            $data['IS_UNIQUE'] = isset($row['isUnique']) ? 1 : null;
                            $data['IS_GROUP'] = isset($row['isGroup']) ? 1 : null;
                            $data['IS_UM_CRITERIA'] = isset($row['isUmCriteria']) ? 1 : null;
                            $data['IS_SIDEBAR'] = isset($row['isSidebar']) ? 1 : null;
                            $data['IS_CRYPTED'] = isset($row['isCrypted']) ? 1 : null;
                            $data['IS_BASKET'] = isset($row['isBasket']) ? 1 : null;
                            $data['IS_BASKET_EDIT'] = isset($row['isBasketEdit']) ? 1 : null;
                            $data['IS_KPI_CRITERIA'] = isset($row['isKpiCriteria']) ? 1 : null;
                            $data['IS_FREEZE'] = isset($row['isFreeze']) ? 1 : null;
                            $data['IS_PASS_FILTER'] = isset($row['isPassFilter']) ? 1 : null;
                            $data['AGGREGATE_FUNCTION'] = Input::param($row['aggregateFunction']);
                            $data['COLUMN_WIDTH'] = Input::param($row['columnWidth']);

                            $data['EXPRESSION_STRING'] = Str::htmlCharToSingleQuote(Input::param($row['expressionString']));
                            $data['VALUE_CRITERIA'] = Input::param($row['valueCriteria']);
                            $data['STYLE_CRITERIA'] = Input::param($row['styleCriteria']);
                            
                            $data['ANALYSIS_DESCRIPTION'] = Input::param($row['analysisDescription']);
                            $data['ANALYSIS_EXPRESSION'] = Input::param($row['analysisExpression']);
                            $data['VALIDATION_CRITERIA'] = Input::param($row['validationCriteria']);

                            $data['PROCESS_GET_PARAM_PATH'] = Input::param($row['processGetParamPath']);
                            $data['INLINE_PROCESS_ID'] = Input::param($row['processMetaDataIdPath']);
                            $data['PROCESS_META_DATA_ID'] = Input::param($row['processMetaDataId']);

                            $data['EXCEL_COLUMN_WIDTH'] = Input::param(Arr::get($row, 'excelColumnWidth'));
                            $data['EXCEL_ROTATE'] = Input::param(Arr::get($row, 'excelRotate'));
                            $data['IS_IGNORE_EXCEL'] = isset($row['isIgnoreExcel']) ? 1 : null;
                            $data['COUNTCARD_THEME'] = Arr::get($row, 'countcardTheme');
                            $data['COUNTCARD_SELECTION'] = Input::param(Arr::get($row, 'countcardSelection'));
                            $data['COUNTCARD_ORDER_NUMBER'] = Input::param(Arr::get($row, 'countCardOrderNumber'));
                            $data['SECOND_DISPLAY_ORDER'] = Input::param(Arr::get($row, 'secondDisplayNumber'));
                            $data['LOG_COLUMN_NAME'] = Input::param(Arr::get($row, 'logColumnName'));
                            $data['ICON_NAME'] = Input::param(Arr::get($row, 'iconName'));
                            $data['IS_ADVANCED'] = Input::param(Arr::get($row, 'isAdvanced'));
                            $data['DEFAULT_OPERATOR'] = Input::param(Arr::get($row, 'defaultOperator'));
                            $data['PLACEHOLDER_NAME'] = Input::param(Arr::get($row, 'placeholderName'));
                            $data['RENDER_TYPE'] = Input::param(Arr::get($row, 'renderType'));
                            $data['THEME_POSITION_NO'] = Input::param(Arr::get($row, 'themePosition'));
                            $data['JSON_CONFIG'] = Input::param(Arr::get($row, 'jsonConfig'));
                            $data['IS_NOT_SHOW_CRITERIA'] = isset($row['isNotShowCriteria']) ? 1 : null;

                            $isOpenPanel = true;
                        }

                        if (isset($row['isGroupAddon'])) {

                            $data['SIDEBAR_NAME'] = Input::param($row['sidebarName']);
                            $data['VISIBLE_CRITERIA'] = Input::param($row['visibleCriteria']);
                            $data['LOOKUP_KEY_META_DATA_ID'] = Input::param($row['lookupKeyMetaDataId']);
                            $data['JOIN_TYPE'] = Input::param($row['joinType']);
                            $data['RELATION_TYPE'] = Input::param($row['relationType']);

                            $data['IS_SAVE'] = isset($row['isSave']) ? 1 : null;
                            $data['IS_BUTTON'] = isset($row['isButton']) ? 1 : null;
                            $data['IS_SIDEBAR'] = isset($row['isSidebar']) ? 1 : null;
                            $data['IS_CRYPTED'] = isset($row['isCrypted']) ? 1 : null;
                            $data['IS_BASKET'] = isset($row['isBasket']) ? 1 : null;
                            $data['IS_BASKET_EDIT'] = isset($row['isBasketEdit']) ? 1 : null;
                            $data['IS_SKIP_UNIQUE_ERROR'] = isset($row['isSkipUniqueError']) ? 1 : null;

                            $isGroup = true;
                            $isOpenPanel = true;
                        }

                        if ($isNew == '1') {
                            
                            $newRowId = isset($row['newRowId']) ? $row['newRowId'] : $rowId;
                            $savedId = $newRowId;
                            
                            $data['ID'] = $newRowId;
                            $data['PARENT_ID'] = issetDefaultVal($row['newParentId'], $parentId);
                            $data['MAIN_META_DATA_ID'] = $metaDataId;
                            
                            if (!isset($data['IS_SHOW_MOBILE'])) {
                                $data['IS_SHOW_MOBILE'] = 1;
                            }

                            $this->db->AutoExecute('META_GROUP_CONFIG', $data);

                        } else {
                            $savedId = $rowId;
                            $this->db->AutoExecute('META_GROUP_CONFIG', $data, 'UPDATE', 'ID = '.$rowId);
                        }

                        if ($isOpenPanel) {

                            if (array_key_exists('tableName', $row)) {
                                
                                $rowTableName  = trim($row['tableName']);
                                $rowPostgreSql = trim($row['postgreSql']);
                                $rowMsSql      = trim($row['msSql']);
                                
                                if ($rowTableName) {
                                    $this->db->UpdateClob('META_GROUP_CONFIG', 'TABLE_NAME', (new Mdmetadata())->objectNameCompress(Str::htmlCharToDoubleQuote($rowTableName)), 'ID = '.$savedId);
                                } else {
                                    $this->db->AutoExecute('META_GROUP_CONFIG', array('TABLE_NAME' => null), 'UPDATE', 'ID = '.$savedId);
                                }
                                
                                if ($rowPostgreSql) {
                                    $this->db->UpdateClob('META_GROUP_CONFIG', 'POSTGRE_SQL', (new Mdmetadata())->objectNameCompress(Str::htmlCharToDoubleQuote($rowPostgreSql)), 'ID = '.$savedId);
                                } else {
                                    $this->db->AutoExecute('META_GROUP_CONFIG', array('POSTGRE_SQL' => null), 'UPDATE', 'ID = '.$savedId);
                                }
                                
                                if ($rowMsSql) {
                                    $this->db->UpdateClob('META_GROUP_CONFIG', 'MS_SQL', (new Mdmetadata())->objectNameCompress(Str::htmlCharToDoubleQuote($rowMsSql)), 'ID = '.$savedId);
                                } else {
                                    $this->db->AutoExecute('META_GROUP_CONFIG', array('MS_SQL' => null), 'UPDATE', 'ID = '.$savedId);
                                }
                            }

                            self::clearGroupParamConfigByGroupPath($metaDataId, $paramPath);

                            if (isset($data['PROCESS_META_DATA_ID']) && $data['PROCESS_META_DATA_ID'] != '' && isset($_POST['paramProcessConfigParamPath'][$paramPath])) {

                                $paramProcessConfigParamPath = $_POST['paramProcessConfigParamPath'][$paramPath];

                                foreach ($paramProcessConfigParamPath as $prok => $processParam) {

                                    $dataProcessConfigParamLink = array(
                                        'ID' => getUIDAdd($n),
                                        'GROUP_META_DATA_ID' => $metaDataId,
                                        'FIELD_PATH' => $paramPath,
                                        'PROCESS_META_DATA_ID' => $data['PROCESS_META_DATA_ID'],
                                        'PARAM_PATH' => Input::param($_POST['paramProcessConfigParamPath'][$paramPath][$prok]),
                                        'PARAM_META_DATA_CODE' => Input::param($_POST['paramProcessConfigParamMeta'][$paramPath][$prok]),
                                        'DEFAULT_VALUE' => Input::param($_POST['paramProcessConfigDefaultVal'][$paramPath][$prok]),
                                        'IS_GROUP' => intval($isGroup)
                                    );
                                    $this->db->AutoExecute('META_GROUP_PARAM_CONFIG', $dataProcessConfigParamLink);
                                    
                                    $n ++;
                                }
                            }

                            if (isset($data['LOOKUP_META_DATA_ID']) && $data['LOOKUP_META_DATA_ID'] != '' && isset($_POST['paramGroupConfigParamPath'][$paramPath])) {

                                $lookupMetaDataId = $data['LOOKUP_META_DATA_ID'];
                                $paramGroupConfigParamData = $_POST['paramGroupConfigParamPath'][$paramPath];

                                foreach ($paramGroupConfigParamData as $rok => $configParam) {

                                    $dataConfigParamLink = array(
                                        'ID' => getUIDAdd($n),
                                        'GROUP_META_DATA_ID' => $metaDataId,
                                        'FIELD_PATH' => $paramPath,
                                        'LOOKUP_META_DATA_ID' => $lookupMetaDataId,
                                        'PARAM_PATH' => Input::param($_POST['paramGroupConfigParamPath'][$paramPath][$rok]),
                                        'PARAM_META_DATA_CODE' => Input::param($_POST['paramGroupConfigParamMeta'][$paramPath][$rok]), 
                                        'DEFAULT_VALUE' => Input::param($_POST['paramGroupConfigDefaultVal'][$paramPath][$rok]),
                                        'IS_GROUP' => intval($isGroup),
                                        'IS_KEY_LOOKUP' => '0'
                                    );
                                    $this->db->AutoExecute('META_GROUP_PARAM_CONFIG', $dataConfigParamLink);
                                    
                                    $n ++;
                                }
                            }

                            if (isset($data['LOOKUP_KEY_META_DATA_ID']) && $data['LOOKUP_KEY_META_DATA_ID'] != '' && isset($_POST['paramGroupConfigParamPathKey'][$paramPath])) {

                                $lookupMetaDataId = $data['LOOKUP_KEY_META_DATA_ID'];
                                $paramGroupConfigParamData = $_POST['paramGroupConfigParamPathKey'][$paramPath];

                                foreach ($paramGroupConfigParamData as $rok => $configParam) {

                                    $dataConfigParamLink = array(
                                        'ID' => getUIDAdd($n),
                                        'GROUP_META_DATA_ID' => $metaDataId, 
                                        'FIELD_PATH' => $paramPath,
                                        'LOOKUP_META_DATA_ID' => $lookupMetaDataId,
                                        'PARAM_PATH' => Input::param($_POST['paramGroupConfigParamPathKey'][$paramPath][$rok]),
                                        'PARAM_META_DATA_CODE' => Input::param($_POST['paramGroupConfigParamMetaKey'][$paramPath][$rok]), 
                                        'DEFAULT_VALUE' => Input::param($_POST['paramGroupConfigDefaultValKey'][$paramPath][$rok]),
                                        'IS_GROUP' => intval($isGroup),
                                        'IS_KEY_LOOKUP' => '1'
                                    );
                                    $this->db->AutoExecute('META_GROUP_PARAM_CONFIG', $dataConfigParamLink);
                                    
                                    $n ++;
                                }
                            }

                            self::clearParamValuesByPath($metaDataId, $paramPath);

                            if (isset($_POST['paramDefaultValueId'][$paramPath]) && isset($data['LOOKUP_META_DATA_ID']) && $data['LOOKUP_META_DATA_ID'] != '') {

                                $paramDefaultValueIdData = $_POST['paramDefaultValueId'][$paramPath];

                                foreach ($paramDefaultValueIdData as $pvk => $pvkVal) {
                                    $paramValue = array(
                                        'ID' => getUIDAdd($n),
                                        'MAIN_META_DATA_ID' => $metaDataId,
                                        'PARAM_PATH' => $paramPath,
                                        'LOOKUP_META_DATA_ID' => $data['LOOKUP_META_DATA_ID'], 
                                        'VALUE_ID' => $pvkVal
                                    );
                                    $this->db->AutoExecute('META_PARAM_VALUES', $paramValue);
                                    
                                    $n ++;
                                }
                            }

                            if (isset($_POST['paramDefaultValueIdKey'][$paramPath]) && isset($data['LOOKUP_KEY_META_DATA_ID']) && $data['LOOKUP_KEY_META_DATA_ID'] != '') {

                                $paramDefaultValueIdKeyData = $_POST['paramDefaultValueIdKey'][$paramPath];

                                foreach ($paramDefaultValueIdKeyData as $pvk => $pvkVal) {
                                    $paramValue = array(
                                        'ID' => getUIDAdd($n),
                                        'MAIN_META_DATA_ID' => $metaDataId,
                                        'PARAM_PATH' => $paramPath,
                                        'LOOKUP_META_DATA_ID' => $data['LOOKUP_KEY_META_DATA_ID'], 
                                        'VALUE_ID' => $pvkVal
                                    );
                                    $this->db->AutoExecute('META_PARAM_VALUES', $paramValue);
                                    
                                    $n ++;
                                }
                            }

                            if (isset($_POST['fieldMappingLookupFieldPath'][$paramPath])) {

                                self::clearOneProcessLookupMap($metaDataId, $paramPath);

                                $fieldMappingLookupFieldPathData = $_POST['fieldMappingLookupFieldPath'][$paramPath];

                                foreach ($fieldMappingLookupFieldPathData as $fmLk => $fmLkVal) {
                                    $paramLookupFieldMap = array(
                                        'ID' => getUIDAdd($n),
                                        'MAIN_META_DATA_ID' => $metaDataId,
                                        'FIELD_PATH' => $paramPath,
                                        'LOOKUP_FIELD_PATH' => $fmLkVal,
                                        'PARAM_FIELD_PATH' => Input::param($_POST['fieldMappingParamFieldPath'][$paramPath][$fmLk]),
                                        'IS_KEY_LOOKUP' => '0', 
                                        'IS_PROCESS' => '0'
                                    );
                                    $this->db->AutoExecute('META_PROCESS_LOOKUP_MAP', $paramLookupFieldMap);
                                    
                                    $n ++;
                                }
                            }

                            if (isset($_POST['fieldMappingLookupFieldPathKey'][$paramPath])) {

                                self::clearOneProcessKeyLookupMap($metaDataId, $paramPath);

                                $fieldMappingLookupFieldPathData = $_POST['fieldMappingLookupFieldPathKey'][$paramPath];

                                foreach ($fieldMappingLookupFieldPathData as $fmLk => $fmLkVal) {
                                    $paramLookupFieldMap = array(
                                        'ID' => getUIDAdd($n),
                                        'MAIN_META_DATA_ID' => $metaDataId,
                                        'FIELD_PATH' => $paramPath,
                                        'LOOKUP_FIELD_PATH' => $fmLkVal,
                                        'PARAM_FIELD_PATH' => Input::param($_POST['fieldMappingParamFieldPathKey'][$paramPath][$fmLk]),
                                        'IS_KEY_LOOKUP' => '1', 
                                        'IS_PROCESS' => '0'
                                    );
                                    $this->db->AutoExecute('META_PROCESS_LOOKUP_MAP', $paramLookupFieldMap);
                                    
                                    $n ++;
                                }
                            }

                            if ($dataType == 'group') {

                                self::clearSingleGroupConfigRelationMap($metaDataId, $paramPath);

                                if (isset($_POST['paramGroupRelationBatchNumber'][$paramPath])) {
                                    $paramGroupRelationBatchNumberData = $_POST['paramGroupRelationBatchNumber'][$paramPath];
                                    foreach ($paramGroupRelationBatchNumberData as $grk => $configRelation) {
                                        $dataGroupRelation = array(
                                            'ID' => getUIDAdd($n),
                                            'MAIN_META_DATA_ID' => $metaDataId,
                                            'BATCH_NUMBER' => Input::param($_POST['paramGroupRelationBatchNumber'][$paramPath][$grk]),
                                            'DEFAULT_VALUE' => isset($_POST['paramGroupRelationDefaultValue'][$paramPath][$grk]) ? Input::param($_POST['paramGroupRelationDefaultValue'][$paramPath][$grk]) : '',
                                            'TRG_PARAM_PATH' => Input::param($_POST['paramGroupRelationTrgParamPath'][$paramPath][$grk]),
                                            'SRC_PARAM_PATH' => Input::param($_POST['paramGroupRelationSrcParamPath'][$paramPath][$grk]),
                                            'FIELD_PATH' => $paramPath
                                        );
                                        $this->db->AutoExecute('META_GROUP_RELATION', $dataGroupRelation);
                                        
                                        $n ++;
                                    }
                                }

                            }
                        }

                        if ($dataType == 'group' && $isNew == '1' && Arr::checkSearchKeyFromArray($groupParams, $paramPath.'.') == false) {
                            
                            if (isset($row['metaDataId'])) {
                                self::importMetaGroupToGroup($metaDataId, $row['metaDataId'], $newRowId, $paramPath);
                            } else {
                                self::importMetaGroupToGroup($metaDataId, null, $newRowId, $paramPath, $rowId);
                            }
                        }
                    }
                    
                } elseif (isset($row['rowId']) && !isset($row['parentId'])) {
                    
                    $row['dataType'] = 'string';
                    $deleteRows[] = $row;
                }

                $i++;
            }
            
            if (count($changeField) || count($changeGroup)) {
                
                $idPh = $this->db->Param(0);
                
                $dvReportData = $this->db->GetAll("
                    SELECT 
                        ID, 
                        'META_STATEMENT_LINK' AS TABLENAME, 
                        'ID' AS ID_FIELDNAME, 
                        REPORT_HEADER, 
                        PAGE_HEADER, 
                        REPORT_DETAIL, 
                        PAGE_FOOTER, 
                        REPORT_FOOTER, 
                        NULL AS GROUP_HEADER, 
                        NULL AS GROUP_FOOTER, 
                        NULL AS REPORT_HTML_CONTENT, 
                        NULL AS REPORT_HTML_FILE_PATH 
                    FROM META_STATEMENT_LINK 
                    WHERE DATA_VIEW_ID = $idPh 
                        AND (REPORT_HEADER IS NOT NULL OR PAGE_HEADER IS NOT NULL OR REPORT_DETAIL IS NOT NULL OR PAGE_FOOTER IS NOT NULL OR REPORT_FOOTER IS NOT NULL) 

                    UNION ALL 

                    SELECT 
                        DTL.ID, 
                        'META_STATEMENT_LINK_GROUP' AS TABLENAME, 
                        'ID' AS ID_FIELDNAME, 
                        NULL AS REPORT_HEADER, 
                        NULL AS PAGE_HEADER, 
                        NULL AS REPORT_DETAIL, 
                        NULL AS PAGE_FOOTER, 
                        NULL AS REPORT_FOOTER, 
                        DTL.GROUP_HEADER, 
                        DTL.GROUP_FOOTER, 
                        NULL AS REPORT_HTML_CONTENT, 
                        NULL AS REPORT_HTML_FILE_PATH 
                    FROM META_STATEMENT_LINK_GROUP DTL 
                        INNER JOIN META_STATEMENT_LINK HDR ON HDR.ID = DTL.META_STATEMENT_LINK_ID 
                    WHERE HDR.DATA_VIEW_ID = $idPh 
                        AND (DTL.GROUP_HEADER IS NOT NULL OR DTL.GROUP_FOOTER IS NOT NULL)", array($metaDataId));
                
                if ($dvReportData) {
                    
                    self::$isPathChangeExpData = true;
                    self::$pathChangeExpData = $dvReportData;
                }
                
                $dvReportTempData = $this->db->GetAll("
                    SELECT 
                        ID, 
                        HTML_FILE_PATH
                    FROM META_REPORT_TEMPLATE_LINK 
                    WHERE DATA_MODEL_ID = $idPh 
                        AND HTML_FILE_PATH IS NOT NULL", array($metaDataId));
                
                if ($dvReportTempData) {
                    
                    $lastIndex = count(self::$pathChangeExpData);
                    
                    foreach ($dvReportTempData as $dvReportTempRow) {
                        
                        if (file_exists($dvReportTempRow['HTML_FILE_PATH'])) {
                            
                            self::$pathChangeExpData[$lastIndex]['ID'] = $dvReportTempRow['ID'];
                            self::$pathChangeExpData[$lastIndex]['TABLENAME'] = 'META_REPORT_TEMPLATE_LINK';
                            self::$pathChangeExpData[$lastIndex]['ID_FIELDNAME'] = 'ID';
                            self::$pathChangeExpData[$lastIndex]['REPORT_HEADER'] = null;
                            self::$pathChangeExpData[$lastIndex]['PAGE_HEADER'] = null;
                            self::$pathChangeExpData[$lastIndex]['REPORT_DETAIL'] = null;
                            self::$pathChangeExpData[$lastIndex]['PAGE_FOOTER'] = null;
                            self::$pathChangeExpData[$lastIndex]['REPORT_FOOTER'] = null;
                            self::$pathChangeExpData[$lastIndex]['GROUP_HEADER'] = null;
                            self::$pathChangeExpData[$lastIndex]['GROUP_FOOTER'] = null;
                            
                            self::$pathChangeExpData[$lastIndex]['REPORT_HTML_FILE_PATH'] = $dvReportTempRow['HTML_FILE_PATH'];
                            self::$pathChangeExpData[$lastIndex]['REPORT_HTML_CONTENT'] = file_get_contents($dvReportTempRow['HTML_FILE_PATH']);

                            $lastIndex ++;
                        }
                    }
                    
                    if ($lastIndex) {
                        self::$isPathChangeExpData = true;
                    } else {
                        self::$isPathChangeExpData = false;
                    }
                }
                
                self::groupChangedFieldPathUpdate($metaDataId, $changeField, 'paramPath', 'oldParamName', false);
                self::groupChangedGroupPathUpdate($metaDataId, $changeGroup);
                
                if (self::$isPathChangeExpData) {
                    
                    foreach (self::$pathChangeExpData as $expRow) {
                        
                        if ($expRow['REPORT_HEADER'] !== '') {
                            
                            $this->db->UpdateClob($expRow['TABLENAME'], 'REPORT_HEADER', $expRow['REPORT_HEADER'], $expRow['ID_FIELDNAME'].' = ' . $expRow['ID']); 
        
                        }
                        
                        if ($expRow['PAGE_HEADER'] !== '') {
                            
                            $this->db->UpdateClob($expRow['TABLENAME'], 'PAGE_HEADER', $expRow['PAGE_HEADER'], $expRow['ID_FIELDNAME'].' = ' . $expRow['ID']); 
        
                        }
                        
                        if ($expRow['REPORT_DETAIL'] !== '') {
                            
                            $this->db->UpdateClob($expRow['TABLENAME'], 'REPORT_DETAIL', $expRow['REPORT_DETAIL'], $expRow['ID_FIELDNAME'].' = ' . $expRow['ID']); 
        
                        }
                        
                        if ($expRow['PAGE_FOOTER'] !== '') {
                            
                            $this->db->UpdateClob($expRow['TABLENAME'], 'PAGE_FOOTER', $expRow['PAGE_FOOTER'], $expRow['ID_FIELDNAME'].' = ' . $expRow['ID']); 
        
                        }
                        
                        if ($expRow['REPORT_FOOTER'] !== '') {
                            
                            $this->db->UpdateClob($expRow['TABLENAME'], 'REPORT_FOOTER', $expRow['REPORT_FOOTER'], $expRow['ID_FIELDNAME'].' = ' . $expRow['ID']); 
        
                        }
                        
                        if ($expRow['GROUP_HEADER'] !== '') {
                            
                            $this->db->UpdateClob($expRow['TABLENAME'], 'GROUP_HEADER', $expRow['GROUP_HEADER'], $expRow['ID_FIELDNAME'].' = ' . $expRow['ID']); 

                        }
                        
                        if ($expRow['GROUP_FOOTER'] !== '') {
                            
                            $this->db->UpdateClob($expRow['TABLENAME'], 'GROUP_FOOTER', $expRow['GROUP_FOOTER'], $expRow['ID_FIELDNAME'].' = ' . $expRow['ID']); 
        
                        }
                        
                        if ($expRow['REPORT_HTML_CONTENT'] !== '') {
                            
                            @file_put_contents($expRow['REPORT_HTML_FILE_PATH'], $expRow['REPORT_HTML_CONTENT']);
        
                        }
                    }
                }
            }
            
            self::deleteGroupParamAttrLinks($metaDataId, $deleteRows);
            
            (new Mdmeta())->bpCacheClearByRelatedGroupId($metaDataId);
        } 

        return true;
    }
    
    public function importMetaGroupToGroup($groupMetaDataId, $mainMetaDataId = null, $parentId = null, $paramPath, $rowId = null) {
        
        if ($mainMetaDataId) {
            
            $where = "MAIN_META_DATA_ID = $mainMetaDataId ";
            $isPathMerge = true;
            
        } elseif ($rowId) {
            
            $where = "PARENT_ID = $rowId ";
            $isPathMerge = false;
        }
        
        $data = $this->db->GetAll("
            SELECT 
                ID,
                PARENT_ID,                      
                MIN_VALUE,                     
                MAX_VALUE,                        
                DEFAULT_VALUE,                    
                RECORD_TYPE,                       
                LOOKUP_META_DATA_ID, 
                LOOKUP_KEY_META_DATA_ID,
                LOOKUP_TYPE,                       
                DISPLAY_FIELD,                     
                VALUE_FIELD,                       
                CHOOSE_TYPE,                       
                PATTERN_ID, 
                FIELD_PATH, 
                PARAM_NAME, 
                SIDEBAR_NAME, 
                TAB_NAME, 
                FEATURE_NUM, 
                COLUMN_AGGREGATE, 
                BODY_ALIGN, 
                COLUMN_WIDTH,    
                HEADER_ALIGN,    
                TEXT_COLOR,  
                TEXT_TRANSFORM,  
                TEXT_WEIGHT, 
                BG_COLOR,  
                FONT_SIZE,  
                LABEL_NAME, 
                SEPARATOR_TYPE, 
                FRACTION_RANGE, 
                DATA_TYPE, 
                FILE_EXTENSION, 
                DISPLAY_ORDER, 
                JOIN_TYPE, 
                RELATION_TYPE, 
                COLUMN_NAME,
                AGGREGATE_FUNCTION, 
                ORDER_NUMBER, 
                IS_SHOW,                          
                IS_REQUIRED, 
                IS_SELECT, 
                IS_CRITERIA, 
                AGGREGATE_ALIAS_PATH 
            FROM META_GROUP_CONFIG 
            WHERE $where 
            ORDER BY DISPLAY_ORDER ASC");

        if ($data) {
            
            $updateParentIds = $parentIds = array();
            
            foreach ($data as $row) {
                
                $id = getUID();
                
                if ($row['PARENT_ID'] != '' && $isPathMerge == false) {
                    
                    $removeLastPath = substr($paramPath, 0, strrpos($paramPath, '.'));
                    
                    $getLastPath  = substr($removeLastPath, strrpos($removeLastPath, '.') + 1);
                    $getFirstPath = strtok($row['FIELD_PATH'], '.');
                    
                    if ($getLastPath == $getFirstPath) {
                        $newFieldPath = $removeLastPath . '.' . substr($row['FIELD_PATH'], strlen($getFirstPath.'.'), 200);
                    } else {
                        $newFieldPath = $removeLastPath . '.' . $row['FIELD_PATH'];
                    }
                    
                    if ($row['DATA_TYPE'] == 'group') {
                        
                        $parentIds[] = array(
                            'rowId'       => $row['ID'], 
                            'newParentId' => $id, 
                            'path'        => $paramPath
                        );
                    }
                    
                } else {
                    $newFieldPath = $paramPath . '.' . $row['FIELD_PATH'];
                }
                
                $insertData = array(
                    'ID'                        => $id, 
                    'MAIN_META_DATA_ID'         => $groupMetaDataId,                   
                    'MIN_VALUE'                 => $row['MIN_VALUE'],                     
                    'MAX_VALUE'                 => $row['MAX_VALUE'],                        
                    'DEFAULT_VALUE'             => $row['DEFAULT_VALUE'],                    
                    'RECORD_TYPE'               => $row['RECORD_TYPE'],                       
                    'LOOKUP_META_DATA_ID'       => $row['LOOKUP_META_DATA_ID'], 
                    'LOOKUP_KEY_META_DATA_ID'   => $row['LOOKUP_KEY_META_DATA_ID'], 
                    'LOOKUP_TYPE'               => $row['LOOKUP_TYPE'],                       
                    'DISPLAY_FIELD'             => $row['DISPLAY_FIELD'],                     
                    'VALUE_FIELD'               => $row['VALUE_FIELD'],                       
                    'CHOOSE_TYPE'               => $row['CHOOSE_TYPE'],                       
                    'PATTERN_ID'                => $row['PATTERN_ID'], 
                    'PARAM_NAME'                => $row['PARAM_NAME'], 
                    'SIDEBAR_NAME'              => $row['SIDEBAR_NAME'], 
                    'TAB_NAME'                  => $row['TAB_NAME'], 
                    'FEATURE_NUM'               => $row['FEATURE_NUM'], 
                    'COLUMN_AGGREGATE'          => $row['COLUMN_AGGREGATE'], 
                    'BODY_ALIGN'                => $row['BODY_ALIGN'], 
                    'COLUMN_WIDTH'              => $row['COLUMN_WIDTH'],
                    'HEADER_ALIGN'              => $row['HEADER_ALIGN'],
                    'TEXT_COLOR'                => $row['TEXT_COLOR'],
                    'TEXT_TRANSFORM'            => $row['TEXT_TRANSFORM'],
                    'TEXT_WEIGHT'               => $row['TEXT_WEIGHT'],
                    'BG_COLOR'                  => $row['BG_COLOR'],
                    'FONT_SIZE'                 => $row['FONT_SIZE'],
                    'LABEL_NAME'                => $row['LABEL_NAME'], 
                    'SEPARATOR_TYPE'            => $row['SEPARATOR_TYPE'], 
                    'FRACTION_RANGE'            => $row['FRACTION_RANGE'], 
                    'DATA_TYPE'                 => $row['DATA_TYPE'], 
                    'FILE_EXTENSION'            => $row['FILE_EXTENSION'], 
                    'FIELD_PATH'                => $newFieldPath,  
                    'DISPLAY_ORDER'             => $row['DISPLAY_ORDER'], 
                    'JOIN_TYPE'                 => $row['JOIN_TYPE'], 
                    'RELATION_TYPE'             => $row['RELATION_TYPE'],
                    'COLUMN_NAME'               => $row['COLUMN_NAME'], 
                    'AGGREGATE_FUNCTION'        => $row['AGGREGATE_FUNCTION'], 
                    'ORDER_NUMBER'              => $row['ORDER_NUMBER'], 
                    'IS_SHOW'                   => $row['IS_SHOW'],                          
                    'IS_REQUIRED'               => $row['IS_REQUIRED'],   
                    'IS_SELECT'                 => $row['IS_SELECT'],   
                    'IS_CRITERIA'               => $row['IS_CRITERIA'], 
                    'AGGREGATE_ALIAS_PATH'      => $row['AGGREGATE_ALIAS_PATH'] 
                );
                
                if ($isPathMerge) {
                    if (empty($row['PARENT_ID'])) {
                        $insertData['PARENT_ID'] = $parentId;
                    } else {
                        $insertData['PARENT_ID'] = $row['PARENT_ID'];
                    }
                } else {
                    $insertData['PARENT_ID'] = $parentId;
                }
                
                if (!empty($row['RECORD_TYPE'])) {
                    $updateParentIds[$row['ID']] = $id;
                }

                $this->db->AutoExecute('META_GROUP_CONFIG', $insertData);
            }
            
            if (count($updateParentIds) > 0) {
                
                foreach ($updateParentIds as $oldId => $newId) {
                    
                    $this->db->AutoExecute('META_GROUP_CONFIG', 
                        array(
                            'PARENT_ID' => $newId 
                        ), 
                        'UPDATE', 
                        "MAIN_META_DATA_ID = $groupMetaDataId AND PARENT_ID = $oldId"); 
                }
            }
            
            if (count($parentIds) > 0) {
                
                foreach ($parentIds as $pId) {
                    self::importMetaGroupToGroup($groupMetaDataId, null, $pId['newParentId'], $pId['path'], $pId['rowId']);
                }
            }
        }

        return true;
    }
    
    public function groupChangedFieldPathUpdate($metaDataId, $changeFields, $paramPathName, $oldParamName, $isIf, $newParamName = '', $oldGroupName = '', $dotCount = 0) {
        
        if (count($changeFields)) {
            
            foreach ($changeFields as $field) {
                
                if ($isIf) {
                    
                    if ($dotCount > 1) {
                        $field['newPath'] = str_replace('.'.$oldGroupName.'.', '.'.$newParamName.'.', $field[$paramPathName]);
                    } else {
                        $field['newPath'] = $newParamName.'.'.substr($field[$paramPathName], strlen($oldGroupName.'.'), 60);
                    }
                    
                    $field['paramName'] = $newParamName;
                }
                
                $this->db->AutoExecute('META_GROUP_CONFIG', 
                    array(
                        'FIELD_PATH' => $field['newPath'] 
                    ), 
                    'UPDATE', 
                    "MAIN_META_DATA_ID = $metaDataId AND LOWER(FIELD_PATH) = LOWER('".$field[$paramPathName]."')"); 
                
                $this->db->AutoExecute('META_GROUP_PARAM_CONFIG', 
                    array(
                        'FIELD_PATH' => $field['newPath'] 
                    ), 
                    'UPDATE', 
                    "GROUP_META_DATA_ID = $metaDataId AND LOWER(FIELD_PATH) = LOWER('".$field[$paramPathName]."')");
                
                $this->db->AutoExecute('META_GROUP_PARAM_CONFIG', 
                    array(
                        'PARAM_PATH' => $field['newPath'] 
                    ), 
                    'UPDATE', 
                    "GROUP_META_DATA_ID = $metaDataId AND LOWER(PARAM_PATH) = LOWER('".$field[$paramPathName]."')");
                
                $this->db->AutoExecute('META_GROUP_PARAM_CONFIG', 
                    array(
                        'PARAM_META_DATA_CODE' => $field['paramName'] 
                    ), 
                    'UPDATE', 
                    "LOOKUP_META_DATA_ID = $metaDataId AND LOWER(PARAM_META_DATA_CODE) = LOWER('".$field[$oldParamName]."')");
                
                $this->db->AutoExecute('META_PARAM_VALUES', 
                    array(
                        'PARAM_PATH' => $field['newPath'] 
                    ), 
                    'UPDATE', 
                    "MAIN_META_DATA_ID = $metaDataId AND LOWER(PARAM_PATH) = LOWER('".$field[$paramPathName]."')");
                
                $this->db->AutoExecute('META_PROCESS_LOOKUP_MAP', 
                    array(
                        'FIELD_PATH' => $field['newPath'] 
                    ), 
                    'UPDATE', 
                    "MAIN_META_DATA_ID = $metaDataId AND LOWER(FIELD_PATH) = LOWER('".$field[$paramPathName]."')");
                
                $this->db->AutoExecute('META_PROCESS_LOOKUP_MAP', 
                    array(
                        'LOOKUP_FIELD_PATH' => $field['newPath'] 
                    ), 
                    'UPDATE', 
                    "LOOKUP_META_ID = $metaDataId AND LOWER(LOOKUP_FIELD_PATH) = LOWER('".$field[$paramPathName]."')");
                
                $this->db->AutoExecute('META_GROUP_RELATION', 
                    array(
                        'FIELD_PATH' => $field['newPath'] 
                    ), 
                    'UPDATE', 
                    "MAIN_META_DATA_ID = $metaDataId AND LOWER(FIELD_PATH) = LOWER('".$field[$paramPathName]."')");
                
                $this->db->Execute("UPDATE META_GROUP_CONFIG SET EXPRESSION_STRING = REPLACE(EXPRESSION_STRING, '[".$field[$paramPathName]."]', '[".$field['newPath']."]') WHERE MAIN_META_DATA_ID = $metaDataId AND LOWER(EXPRESSION_STRING) LIKE LOWER('%[".$field[$paramPathName]."]%')");
                $this->db->Execute("UPDATE META_REPORT_TEMPLATE_LINK SET PAGING_CONFIG = REPLACE(PAGING_CONFIG, '".$field[$paramPathName]."|', '".$field['newPath']."|') WHERE DATA_MODEL_ID = $metaDataId AND LOWER(PAGING_CONFIG) LIKE LOWER('".$field[$paramPathName]."|%')");
                
                $this->db->AutoExecute('META_GROUP_CONFIG', 
                    array(
                        'DISPLAY_FIELD' => $field['newPath']
                    ), 
                    'UPDATE', 
                    "LOOKUP_META_DATA_ID = $metaDataId AND LOWER(DISPLAY_FIELD) = LOWER('".$field[$paramPathName]."')");
                
                $this->db->AutoExecute('META_GROUP_CONFIG', 
                    array(
                        'VALUE_FIELD' => $field['newPath']
                    ), 
                    'UPDATE', 
                    "LOOKUP_META_DATA_ID = $metaDataId AND LOWER(VALUE_FIELD) = LOWER('".$field[$paramPathName]."')");
                
                $this->db->AutoExecute('META_PROCESS_PARAM_ATTR_LINK', 
                    array(
                        'DISPLAY_FIELD' => $field['newPath']
                    ), 
                    'UPDATE', 
                    "LOOKUP_META_DATA_ID = $metaDataId AND LOWER(DISPLAY_FIELD) = LOWER('".$field[$paramPathName]."')");
                
                $this->db->AutoExecute('META_PROCESS_PARAM_ATTR_LINK', 
                    array(
                        'VALUE_FIELD' => $field['newPath']
                    ), 
                    'UPDATE', 
                    "LOOKUP_META_DATA_ID = $metaDataId AND LOWER(VALUE_FIELD) = LOWER('".$field[$paramPathName]."')");
                
                $this->db->AutoExecute('META_SRC_TRG_PARAM', 
                    array(
                        'SRC_PARAM_NAME' => $field['newPath']
                    ), 
                    'UPDATE', 
                    "SRC_META_DATA_ID = $metaDataId AND LOWER(SRC_PARAM_NAME) = LOWER('".$field[$paramPathName]."')");
                
                $this->db->AutoExecute('META_SRC_TRG_PARAM', 
                    array(
                        'TRG_PARAM_NAME' => $field['newPath']
                    ), 
                    'UPDATE', 
                    "TRG_META_DATA_ID = $metaDataId AND LOWER(TRG_PARAM_NAME) = LOWER('".$field[$paramPathName]."')");
                
                $this->db->AutoExecute('META_WORKSPACE_PARAM_MAP', 
                    array(
                        'FIELD_PATH' => $field['newPath']
                    ), 
                    'UPDATE', 
                    "TARGET_META_ID = $metaDataId AND LOWER(FIELD_PATH) = LOWER('".$field[$paramPathName]."')");
                
                $this->db->AutoExecute('META_WORKSPACE_PARAM_MAP', 
                    array(
                        'PARAM_PATH' => $field['newPath']
                    ), 
                    'UPDATE', 
                    "TARGET_META_ID = $metaDataId AND IS_TARGET = 1 AND LOWER(PARAM_PATH) = LOWER('".$field[$paramPathName]."')");
                
                if (self::$isPathChangeExpData) {
                    
                    foreach (self::$pathChangeExpData as $key => $expRow) {
                        
                        if ($expRow['REPORT_HEADER'] !== '') {
                            
                            self::$pathChangeExpData[$key]['REPORT_HEADER'] = str_ireplace('#'.$field[$paramPathName].'#', '#'.$field['newPath'].'#', $expRow['REPORT_HEADER']);
        
                        }
                        
                        if ($expRow['PAGE_HEADER'] !== '') {
                            
                            self::$pathChangeExpData[$key]['PAGE_HEADER'] = str_ireplace('#'.$field[$paramPathName].'#', '#'.$field['newPath'].'#', $expRow['PAGE_HEADER']);
        
                        }
                        
                        if ($expRow['REPORT_DETAIL'] !== '') {
                            
                            self::$pathChangeExpData[$key]['REPORT_DETAIL'] = str_ireplace('#'.$field[$paramPathName].'#', '#'.$field['newPath'].'#', $expRow['REPORT_DETAIL']);
        
                        }
                        
                        if ($expRow['PAGE_FOOTER'] !== '') {
                            
                            self::$pathChangeExpData[$key]['PAGE_FOOTER'] = str_ireplace('#'.$field[$paramPathName].'#', '#'.$field['newPath'].'#', $expRow['PAGE_FOOTER']);
        
                        }
                        
                        if ($expRow['REPORT_FOOTER'] !== '') {
                            
                            self::$pathChangeExpData[$key]['REPORT_FOOTER'] = str_ireplace('#'.$field[$paramPathName].'#', '#'.$field['newPath'].'#', $expRow['REPORT_FOOTER']);
        
                        }
                        
                        if ($expRow['GROUP_HEADER'] !== '') {
                            
                            self::$pathChangeExpData[$key]['GROUP_HEADER'] = str_ireplace('#'.$field[$paramPathName].'#', '#'.$field['newPath'].'#', $expRow['GROUP_HEADER']);
        
                        }
                        
                        if ($expRow['GROUP_FOOTER'] !== '') {
                            
                            self::$pathChangeExpData[$key]['GROUP_FOOTER'] = str_ireplace('#'.$field[$paramPathName].'#', '#'.$field['newPath'].'#', $expRow['GROUP_FOOTER']);
        
                        }
                        
                        if ($expRow['REPORT_HTML_CONTENT'] !== '') {
                            
                            self::$pathChangeExpData[$key]['REPORT_HTML_CONTENT'] = str_ireplace('#'.$field[$paramPathName].'#', '#'.strtolower($field['newPath']).'#', $expRow['REPORT_HTML_CONTENT']);
                            self::$pathChangeExpData[$key]['REPORT_HTML_CONTENT'] = str_ireplace('id="'.$field[$paramPathName].'"', 'id="'.strtolower($field['newPath']).'"', self::$pathChangeExpData[$key]['REPORT_HTML_CONTENT']);
        
                        }
                    }
                }
                
                self::changeGroupPathConfigs($metaDataId, $field[$paramPathName], $field['newPath']);
            }
        }
        
        return true;
    }
    
    public function changeGroupPathConfigs($metaDataId, $oldPath, $newPath) {
        
        $dataSrc = $this->db->GetAll("SELECT ID, CRITERIA, PARAMS FROM META_DM_DM_MAP WHERE SRC_META_DATA_ID = $metaDataId AND (LOWER(CRITERIA) LIKE LOWER('%$oldPath%') OR LOWER(PARAMS) LIKE LOWER('%=$oldPath'))");
        
        if ($dataSrc) {
            
            foreach ($dataSrc as $src) {
                
                if ($src['PARAMS'] != '') {
                    
                    $this->db->AutoExecute('META_DM_DM_MAP', 
                        array(
                            'PARAMS' => preg_replace('/\b='.$oldPath.'\b/u', '='.$newPath, $src['PARAMS'])
                        ), 
                        'UPDATE', 
                        'ID = '.$src['ID']);
                }
                
                if ($src['CRITERIA'] != '') {
                    
                    $this->db->AutoExecute('META_DM_DM_MAP', 
                        array(
                            'CRITERIA' => preg_replace('/\b'.$oldPath.'\b/u', $newPath, $src['CRITERIA'])
                        ), 
                        'UPDATE', 
                        'ID = '.$src['ID']);
                }
            }
        }
        
        $dataTrg = $this->db->GetAll("SELECT ID, PARAMS FROM META_DM_DM_MAP WHERE TRG_META_DATA_ID = $metaDataId AND LOWER(PARAMS) LIKE LOWER('$oldPath=%')");
        
        if ($dataTrg) {
            
            foreach ($dataTrg as $trg) {
                    
                $this->db->AutoExecute('META_DM_DM_MAP', 
                    array(
                        'PARAMS' => preg_replace('/\b'.$oldPath.'=\b/u', $newPath.'=', $trg['PARAMS'])
                    ), 
                    'UPDATE', 
                    'ID = '.$trg['ID']);
            }
        }
        
        $dataLayoutDtl = $this->db->GetAll("
            SELECT 
                DTL.ID 
            FROM META_GROUP_GRID_LAYOUT HDR 
                INNER JOIN META_GROUP_GRID_LAYOUT_DTL DTL ON DTL.HEADER_ID = HDR.ID
            WHERE HDR.MAIN_META_DATA_ID = $metaDataId 
                AND LOWER(DTL.FIELD_PATH) = LOWER('$oldPath')");
        
        if ($dataLayoutDtl) {
            
            foreach ($dataLayoutDtl as $layoutDtl) {
                    
                $this->db->AutoExecute('META_GROUP_GRID_LAYOUT_DTL', 
                    array(
                        'FIELD_PATH' => $newPath 
                    ), 
                    'UPDATE', 
                    'ID = '.$layoutDtl['ID']);
            }
        }
        
        $dataDrillDtl = $this->db->GetAll("
            SELECT 
                DTL.ID, 
                DTL.CRITERIA, 
                DTL.MAIN_GROUP_LINK_PARAM 
            FROM META_DM_DRILLDOWN_DTL DTL 
                INNER JOIN META_GROUP_LINK HDR ON HDR.ID = DTL.MAIN_GROUP_LINK_ID 
            WHERE HDR.META_DATA_ID = $metaDataId 
                AND (LOWER(DTL.CRITERIA) LIKE LOWER('%$oldPath%') OR LOWER(DTL.MAIN_GROUP_LINK_PARAM) = LOWER('$oldPath'))");
        
        if ($dataDrillDtl) {
            
            foreach ($dataDrillDtl as $drillDtl) {
                
                if ($drillDtl['MAIN_GROUP_LINK_PARAM'] != '') {
                    
                    $this->db->AutoExecute('META_DM_DRILLDOWN_DTL', 
                        array(
                            'MAIN_GROUP_LINK_PARAM' => $newPath
                        ), 
                        'UPDATE', 
                        'ID = '.$drillDtl['ID']);
                }
                
                if ($drillDtl['CRITERIA'] != '') {
                    
                    $this->db->AutoExecute('META_DM_DRILLDOWN_DTL', 
                        array(
                            'CRITERIA' => preg_replace('/\b'.$oldPath.'\b/u', $newPath, $drillDtl['CRITERIA'])
                        ), 
                        'UPDATE', 
                        'ID = '.$drillDtl['ID']);
                }
            }
        }
        
        $dataDrillPrm = $this->db->GetAll("
            SELECT 
                PRM.ID, 
                PRM.SRC_PARAM 
            FROM META_DM_DRILLDOWN_PARAM PRM 
                INNER JOIN META_DM_DRILLDOWN_DTL DTL ON DTL.ID = PRM.DM_DRILLDOWN_DTL_ID 
                INNER JOIN META_GROUP_LINK HDR ON HDR.ID = DTL.MAIN_GROUP_LINK_ID 
            WHERE HDR.META_DATA_ID = $metaDataId 
                AND LOWER(PRM.SRC_PARAM) = LOWER('$oldPath')");
        
        if ($dataDrillPrm) {
            
            foreach ($dataDrillPrm as $drillPrm) {
                    
                $this->db->AutoExecute('META_DM_DRILLDOWN_PARAM', 
                    array(
                        'SRC_PARAM' => $newPath
                    ), 
                    'UPDATE', 
                    'ID = '.$drillPrm['ID']);
            }
        }
        
        $dataDrillPrmLink = $this->db->GetAll("
            SELECT 
                PRM.ID, 
                PRM.TRG_PARAM 
            FROM META_DM_DRILLDOWN_PARAM PRM 
                INNER JOIN META_DM_DRILLDOWN_DTL DTL ON DTL.ID = PRM.DM_DRILLDOWN_DTL_ID 
            WHERE DTL.LINK_META_DATA_ID = $metaDataId 
                AND LOWER(PRM.TRG_PARAM) = LOWER('$oldPath')");
        
        if ($dataDrillPrmLink) {
            
            foreach ($dataDrillPrmLink as $drillPrmLink) {
                    
                $this->db->AutoExecute('META_DM_DRILLDOWN_PARAM', 
                    array(
                        'TRG_PARAM' => $newPath
                    ), 
                    'UPDATE', 
                    'ID = '.$drillPrmLink['ID']);
            }
        }
        
        $dataStatementGroup = $this->db->GetAll("
            SELECT 
                DTL.ID  
            FROM META_STATEMENT_LINK_GROUP DTL 
                INNER JOIN META_STATEMENT_LINK HDR ON HDR.ID = DTL.META_STATEMENT_LINK_ID
            WHERE HDR.DATA_VIEW_ID = $metaDataId 
                AND LOWER(DTL.GROUP_FIELD_PATH) = LOWER('$oldPath')");
        
        if ($dataStatementGroup) {
            
            foreach ($dataStatementGroup as $statementGroup) {
                    
                $this->db->AutoExecute('META_STATEMENT_LINK_GROUP', 
                    array(
                        'GROUP_FIELD_PATH' => $newPath
                    ), 
                    'UPDATE', 
                    'ID = '.$statementGroup['ID']);
            }
        }
        
        return true;
    }
    
    public function groupChangedGroupPathUpdate($metaDataId, $changeGroups) {
        
        if (count($changeGroups)) {
            
            foreach ($changeGroups as $group) {
                
                self::groupChangedFieldPathUpdate($metaDataId, array($group), 'paramPath', 'oldParamName', false);
                
                $data = $this->db->GetAll("
                    SELECT 
                        FIELD_PATH AS OLD_PATH, 
                        PARAM_NAME AS OLD_PARAM_NAME
                    FROM META_GROUP_CONFIG 
                    WHERE MAIN_META_DATA_ID = $metaDataId 
                        AND LOWER(FIELD_PATH) LIKE LOWER('".$group['paramPath'].".%') 
                    ORDER BY DISPLAY_ORDER ASC");
                
                self::groupChangedFieldPathUpdate($metaDataId, $data, 'OLD_PATH', 'OLD_PARAM_NAME', true, $group['paramName'], $group['oldParamName'], substr_count($group['newPath'], '.'));
            }
        }
        
        return true;
    }
    
    public function deleteGroupParamAttrLinks($metaDataId, $deleteRows) {

        if (count($deleteRows)) {

            foreach ($deleteRows as $row) {

                $dataType = $row['dataType'];
                $rowId = $row['rowId'];

                if ($dataType == 'row' || $dataType == 'rows') {
                    $paramPath = strtolower($row['paramPath']);
                    $this->db->Execute("DELETE FROM META_GROUP_CONFIG WHERE MAIN_META_DATA_ID = $metaDataId AND LOWER(FIELD_PATH) LIKE '$paramPath.%'");
                } 

                $this->db->Execute("DELETE FROM META_GROUP_CONFIG WHERE ID = $rowId");
            }

            $this->db->Execute("
                DELETE 
                    FROM META_PROCESS_LOOKUP_MAP 
                WHERE (IS_PROCESS = 0 OR IS_PROCESS IS NULL) 
                    AND MAIN_META_DATA_ID = $metaDataId 
                    AND LOWER(FIELD_PATH) NOT IN (
                        SELECT 
                            LOWER(FIELD_PATH) 
                        FROM META_GROUP_CONFIG 
                        WHERE MAIN_META_DATA_ID = $metaDataId  
                    )"); 

            $this->db->Execute("
                DELETE 
                    FROM META_GROUP_PARAM_CONFIG  
                WHERE GROUP_META_DATA_ID = $metaDataId 
                    AND LOWER(FIELD_PATH) NOT IN (
                        SELECT 
                            LOWER(FIELD_PATH) 
                        FROM META_GROUP_CONFIG 
                        WHERE MAIN_META_DATA_ID = $metaDataId 
                    )"); 
        }

        return true;
    }

    public function clearMetaFolderMap($metaDataId) {
        $this->db->Execute("DELETE FROM META_DATA_FOLDER_MAP WHERE META_DATA_ID = $metaDataId");
    }
    
    public function clearMetaTagMap($metaDataId) {
        $this->db->Execute("DELETE FROM META_TAG_MAP WHERE META_DATA_ID = $metaDataId");
    }

    public function clearMetaMetaMap($metaDataId) {
        $this->db->Execute("DELETE FROM META_META_MAP WHERE SRC_META_DATA_ID = $metaDataId");
    }
    
    public function clearMetaProxyMap($metaDataId) {
        $this->db->Execute("DELETE FROM META_PROXY_MAP WHERE SRC_META_DATA_ID = $metaDataId");
    }
    
    public function clearMetaVersionMap($metaDataId) {
        $this->db->Execute("DELETE FROM META_VERSION_MAP WHERE SRC_META_DATA_ID = $metaDataId");
    }
    
    public function clearMetaStatementTemplateMap($metaDataId) {
        $this->db->Execute("DELETE FROM META_STATEMENT_TEMPLATE WHERE SRC_META_DATA_ID = $metaDataId");
    }
    
    public function clearMetaBookmarkLinkMap($metaDataId) {
        $this->db->Execute("DELETE FROM META_BOOKMARK_LINKS WHERE META_DATA_ID = $metaDataId");
    }

    public function clearMetaReportLinkMap($metaDataId) {
        $this->db->Execute("DELETE FROM META_REPORT_LINK WHERE META_DATA_ID = $metaDataId");
        return true;
    }

    public function clearMetaDashboardLinkMap($metaDataId) {
        $this->db->Execute("DELETE FROM META_DASHBOARD_LINK WHERE META_DATA_ID = $metaDataId");
        return true;
    }

    public function clearMetaFieldLinkMap($metaDataId) {
        $this->db->Execute("DELETE FROM META_FIELD_LINK WHERE META_DATA_ID = $metaDataId");
        return true;
    }

    public function clearMenuLinkMap($metaDataId) {
        $this->db->Execute("DELETE FROM META_MENU_LINK WHERE META_DATA_ID = $metaDataId");
        return true;
    }

    public function clearCalendarLinkMap($metaDataId) {
        $this->db->Execute("DELETE FROM META_CALENDAR_LINK WHERE META_DATA_ID = $metaDataId");
        return true;
    }

    public function clearContentLinkMap($metaDataId) {
        $this->db->Execute("DELETE FROM META_CONTENT_LINK WHERE META_DATA_ID = $metaDataId");
        $this->db->Execute("DELETE FROM META_CONTENT_MAP WHERE SRC_META_DATA_ID = $metaDataId");
        return true;
    }

    public function clearContentMapLinkMap($metaDataId) {
        $this->db->Execute("DELETE FROM META_CONTENT_MAP WHERE SRC_META_DATA_ID = $metaDataId");
        return true;
    }

    public function clearDonutLinkMap($metaDataId) {
        $this->db->Execute("DELETE FROM META_DONUT WHERE META_DATA_ID = $metaDataId");
        return true;
    }

    public function clearCardLinkMap($metaDataId) {
        $this->db->Execute("DELETE FROM META_CARD WHERE META_DATA_ID = $metaDataId");
        return true;
    }

    public function clearDiagramLinkMap($metaDataId) {
        $this->db->Execute("DELETE FROM META_DASHBOARD_LINK WHERE META_DATA_ID = $metaDataId");
        return true;
    }

    public function clearReportTemplateLinkMap($metaDataId) {
        $template = $this->db->GetRow("SELECT ID, META_DATA_ID, DATA_MODEL_ID, HTML_FILE_PATH FROM META_REPORT_TEMPLATE_LINK WHERE META_DATA_ID = $metaDataId");
        if ($template && file_exists($template['HTML_FILE_PATH'])) {
            @unlink($template['HTML_FILE_PATH']);
        }
        $this->db->Execute("DELETE FROM META_REPORT_TEMPLATE_LINK WHERE META_DATA_ID = $metaDataId");
        return true;
    }

    public function clearGroupLinkMap($metaDataId) {
        $this->db->Execute("DELETE FROM META_GROUP_LINK WHERE META_DATA_ID = $metaDataId");
        return true;
    }

    public function clearGroupConfigMap($metaDataId) {
        $this->db->Execute("DELETE FROM META_GROUP_CONFIG WHERE MAIN_META_DATA_ID = $metaDataId");
        $this->db->Execute("DELETE FROM META_GROUP_PARAM_CONFIG WHERE GROUP_META_DATA_ID = $metaDataId AND (IS_KEY_LOOKUP = '0' OR IS_KEY_LOOKUP IS NULL)");
        $this->db->Execute("DELETE FROM META_GROUP_RELATION WHERE MAIN_META_DATA_ID = $metaDataId");
        return true;
    }

    public function clearSingleGroupConfigRelationMap($metaDataId, $fieldPath) {
        $this->db->Execute("DELETE FROM META_GROUP_RELATION WHERE MAIN_META_DATA_ID = $metaDataId AND LOWER(FIELD_PATH) = '" . strtolower($fieldPath) . "'");
        return true;
    }

    public function clearOneProcessLookupMap($mainMetaDataId, $fieldPath) {
        $this->db->Execute("DELETE FROM META_PROCESS_LOOKUP_MAP WHERE (MAIN_META_DATA_ID = $mainMetaDataId OR PROCESS_META_DATA_ID = $mainMetaDataId) AND LOWER(FIELD_PATH) = LOWER('$fieldPath') AND (IS_KEY_LOOKUP = '0' OR IS_KEY_LOOKUP IS NULL)");
        return true;
    }

    public function clearOneProcessKeyLookupMap($mainMetaDataId, $fieldPath) {
        $this->db->Execute("DELETE FROM META_PROCESS_LOOKUP_MAP WHERE (MAIN_META_DATA_ID = $mainMetaDataId OR PROCESS_META_DATA_ID = $mainMetaDataId) AND LOWER(FIELD_PATH) = LOWER('$fieldPath') AND IS_KEY_LOOKUP = '1'");
        return true;
    }

    public function clearGroupProcessDetailMap($metaDataId) {
        $this->db->Execute("DELETE FROM META_DM_PROCESS_DTL WHERE MAIN_META_DATA_ID = $metaDataId");
        $this->db->Execute("DELETE FROM META_DM_TRANSFER_PROCESS WHERE MAIN_META_DATA_ID = $metaDataId");
        return true;
    }

    public function clearGroupProcessDetailRowMap($metaDataId) {
        $this->db->Execute("DELETE FROM META_DM_ROW_PROCESS_PARAM WHERE MAIN_META_DATA_ID = $metaDataId");
        return true;
    }

    public function clearMetaBusinessProcessDefaultGetProcessMap($metaDataId) {
        $this->db->Execute("DELETE FROM META_PROCESS_DEFAULT_GET WHERE PROCESS_META_DATA_ID = $metaDataId");
        return true;
    }

    public function clearWorkSpaceLinkMap($metaDataId) {
        $this->db->Execute("DELETE FROM META_WORKSPACE_LINK WHERE META_DATA_ID = $metaDataId");
        return true;
    }

    public function clearStatementLinkMap($metaDataId) {
        $this->db->Execute("DELETE FROM META_STATEMENT_LINK WHERE META_DATA_ID = $metaDataId");
        return true;
    }

    public function clearPackageLinkMap($metaDataId) {
        $this->db->Execute("DELETE FROM META_PACKAGE_LINK WHERE META_DATA_ID = $metaDataId");
        return true;
    }
    
    public function clearBpmLinkMap($metaDataId) {
        $this->db->Execute("DELETE FROM META_BPM_LINK WHERE META_DATA_ID = $metaDataId");
        return true;
    }

    public function clearStatementGroupLinkMap($metaDataId) {
        $this->db->Execute("DELETE FROM META_STATEMENT_LINK_GROUP WHERE META_DATA_ID = $metaDataId");
        return true;
    }

    public function clearStatementGroupLinkMapByLinkId($linkId) {
        $this->db->Execute("DELETE FROM META_STATEMENT_LINK_GROUP WHERE META_STATEMENT_LINK_ID = $linkId");
        return true;
    }
    
    public function clearGroupSubQueryByLinkId($linkId) {
        $this->db->Execute("DELETE FROM META_GROUP_SUB_QUERY WHERE META_GROUP_LINK_ID = $linkId");
        return true;
    }

    public function clearLayoutLinkMap($metaDataId) {
        $this->db->Execute("DELETE FROM META_LAYOUT_LINK WHERE META_DATA_ID = $metaDataId");
        return true;
    }

    public function clearLayoutLinkParamMap($metaDataId) {
        $this->db->Execute("DELETE FROM META_LAYOUT_PARAM_MAP WHERE META_LAYOUT_LINK_ID = $metaDataId");
        return true;
    }

    public function clearLayoutLinkParamConfig($metaDataId) {
        $this->db->Execute("
            DELETE 
                FROM META_LAYOUT_PARAM_CONFIG  
            WHERE LAYOUT_PARAM_MAP_ID IN (
                    SELECT ID 
                    FROM META_LAYOUT_PARAM_MAP 
                    WHERE META_LAYOUT_LINK_ID = $metaDataId 
                )");
        return true;
    }

    public function clearWidgetLinkAndParam($metaDataId) {
        $this->db->Execute("DELETE FROM META_WIDGET_LINK WHERE META_DATA_ID = $metaDataId");
        return true;
    }

    public function clearGroupParamConfigByPath($metaDataId, $path) {
        $this->db->Execute("
            DELETE FROM META_GROUP_PARAM_CONFIG 
            WHERE MAIN_PROCESS_META_DATA_ID = $metaDataId 
                AND LOWER(FIELD_PATH) = LOWER('$path')"); 
        return true;
    }

    public function clearSequenceConfigByPath($metaDataId, $path) {
        $this->db->Execute("
            DELETE FROM META_DATA_SEQUENCE_CONFIG 
            WHERE META_DATA_ID = $metaDataId 
                AND LOWER(PARAM_NAME) = LOWER('$path')"); 
        return true;
    }
    
    public function clearGroupParamConfigByGroupPath($metaDataId, $path) {
        $this->db->Execute("
            DELETE FROM META_GROUP_PARAM_CONFIG 
            WHERE GROUP_META_DATA_ID = $metaDataId 
                AND LOWER(FIELD_PATH) = LOWER('$path')"); 
        return true;
    }

    public function clearParamValuesByPath($metaDataId, $path) {
        $this->db->Execute("
            DELETE FROM META_PARAM_VALUES 
            WHERE MAIN_META_DATA_ID = $metaDataId 
                AND LOWER(PARAM_PATH) = LOWER('$path')"); 
        return true;
    }
    
    public function clearMetaBugFixDtl($metaDataId) {
        $this->db->Execute("DELETE FROM META_BUG_FIXING_DTL WHERE META_DATA_ID = $metaDataId");
        return true;
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
                    AND MP.RECORD_ID = $id2Ph 
                    AND FA.IS_PHOTO = 0", array($metaDataId, $metaValueId));
            
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
                EMP.PICTURE 
            FROM ECM_CONTENT CO 
                INNER JOIN ECM_CONTENT_MAP MP ON MP.CONTENT_ID = CO.CONTENT_ID 
                LEFT JOIN UM_USER UM ON UM.USER_ID = CO.CREATED_USER_ID 
                LEFT JOIN UM_SYSTEM_USER US ON US.USER_ID = UM.SYSTEM_USER_ID 
                LEFT JOIN VW_EMPLOYEE EMP ON EMP.PERSON_ID = US.PERSON_ID 
            WHERE MP.REF_STRUCTURE_ID = $metaDataIdPh 
                AND (MP.RECORD_ID = $metaValueIdPh OR MP.MAIN_RECORD_ID = $metaValueIdPh)
                AND CO.IS_PHOTO = 0 
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

    public function getMetaDataValueRelationModel($metaDataId = 0, $metaValueId = 0) {
        $param = array(
            'dataviewid' => $metaDataId,
            'recordid' => $metaValueId,
        );

        $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'get_auto_mapped_records', $param);

        return $result;
    }

    public function getMetaGroupByMetaDatasModel($groupId) {
        $data = $this->db->GetAll("
            SELECT 
                MD.META_DATA_ID, 
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME, 
                MD.META_TYPE_ID,  
                LOWER(MT.META_TYPE_CODE) AS META_TYPE_CODE, 
                MT.META_TYPE_NAME, 
                MI.META_ICON_NAME, 
                MD.CREATED_DATE, 
                " . $this->db->IfNull('BP.FIRST_NAME', 'US.USERNAME') . " AS CREATED_PERSON_NAME,  
                GL.GROUP_TYPE    
            FROM META_META_MAP DM 
                INNER JOIN META_DATA MD ON DM.TRG_META_DATA_ID = MD.META_DATA_ID 
                LEFT JOIN META_TYPE MT ON MT.META_TYPE_ID = MD.META_TYPE_ID 
                LEFT JOIN META_DATA_ICON MI ON MI.META_ICON_ID = MD.META_ICON_ID 
                LEFT JOIN UM_SYSTEM_USER US ON US.USER_ID = MD.CREATED_USER_ID  
                LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = US.PERSON_ID 
                LEFT JOIN META_GROUP_LINK GL ON GL.META_DATA_ID = MD.META_DATA_ID 
            WHERE 
                DM.SRC_META_DATA_ID = $groupId 
                AND MD.IS_ACTIVE = 1 
            ORDER BY DM.ORDER_NUM ASC");

        if ($data) {
            return $data;
        }
        return null;
    }

    public function getChildMetaDatasModel($groupId, $isSecondOrder = false, $metaTypeId = null) {
        
        if (!$isSecondOrder && $metaTypeId == Mdmetadata::$proxyMetaTypeId) { // Proxy
            
            $mapTableName = 'META_PROXY_MAP';
            $isDefaultMapColumn = 'DM.IS_DEFAULT, ';
            $idInputName = 'proxyChildMetaDataId';
            $isProxy = true;
            
        } elseif (!$isSecondOrder && $metaTypeId == Mdmetadata::$statementMetaTypeId) { // Version
            
            $mapTableName = 'META_VERSION_MAP';
            $isDefaultMapColumn = '';
            $idInputName = 'versionChildMetaDataId';
            
        } else {
            $mapTableName = 'META_META_MAP';
            $isDefaultMapColumn = '';
            $idInputName = 'childMetaDataId';
        }
        
        if ($isSecondOrder && $mapTableName == 'META_META_MAP') {
            $isDefaultMapColumn .= 'DM.SECOND_ORDER_NUM, ';
        }

        $data = $this->db->GetAll("
            SELECT 
                MD.META_DATA_ID, 
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME, 
                MD.META_TYPE_ID,  
                LOWER(MT.META_TYPE_CODE) AS META_TYPE_CODE, 
                " . $this->db->IfNull('DT.DATA_TYPE_NAME', 'MT.META_TYPE_NAME') . " AS META_TYPE_NAME, 
                MI.META_ICON_NAME, 
                MD.CREATED_DATE, 
                " . $this->db->IfNull('BP.FIRST_NAME', 'US.USERNAME') . " AS CREATED_PERSON_NAME,  
                BL.BOOKMARK_URL, 
                BL.TARGET AS BOOKMARK_TARGET,     
                $isDefaultMapColumn 
                GL.GROUP_TYPE    
            FROM $mapTableName DM 
                INNER JOIN META_DATA MD ON DM.TRG_META_DATA_ID = MD.META_DATA_ID 
                LEFT JOIN META_TYPE MT ON MT.META_TYPE_ID = MD.META_TYPE_ID 
                LEFT JOIN META_DATA_ICON MI ON MI.META_ICON_ID = MD.META_ICON_ID 
                LEFT JOIN UM_SYSTEM_USER US ON US.USER_ID = MD.CREATED_USER_ID 
                LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = US.PERSON_ID 
                LEFT JOIN META_GROUP_LINK GL ON GL.META_DATA_ID = MD.META_DATA_ID 
                LEFT JOIN META_FIELD_LINK FL ON FL.META_DATA_ID = MD.META_DATA_ID 
                LEFT JOIN META_FIELD_DATA_TYPE DT ON DT.DATA_TYPE_CODE = FL.DATA_TYPE 
                LEFT JOIN META_BOOKMARK_LINKS BL ON BL.META_DATA_ID = MD.META_DATA_ID 
            WHERE DM.SRC_META_DATA_ID = ".$this->db->Param(0)." 
                AND MD.IS_ACTIVE = 1 
            ORDER BY DM.ORDER_NUM ASC", array($groupId));

        if ($metaTypeId == Mdmetadata::$dmMetaTypeId) {
            
            $dmLink = $this->db->GetAll(
                'SELECT DC.*
                FROM META_DATAMART_LINK DL
                INNER JOIN META_DATAMART_COLUMN DC ON DC.META_DATAMART_LINK_ID = DL.ID
                WHERE DL.META_DATA_ID = ' . $groupId
            );            
            $dmRelation = $this->db->GetAll(
                'SELECT *
                FROM META_GROUP_RELATION
                WHERE MAIN_META_DATA_ID = ' . $groupId
            );            
            $dmCriteria = $this->db->GetAll(
                'SELECT CC.*
                FROM META_DATAMART_LINK DL
                INNER JOIN META_DATAMART_COLUMN DC ON DC.META_DATAMART_LINK_ID = DL.ID
                INNER JOIN META_DATAMART_COLUMN_CRITERIA CC ON CC.META_DATAMART_COLUMN_ID = DC.ID
                WHERE DL.META_DATA_ID = ' . $groupId
            );            
            $dmCriteria = Arr::groupByArray($dmCriteria, 'META_DATAMART_COLUMN_ID');

            return array(
                'map' => $data,
                'dmLink' => $dmLink,
                'relation' => $dmRelation,
                'criteria' => $dmCriteria
            );
        }
        
        $html = '';
        $metaClass = new Mdmetadata();
        
        foreach ($data as $meta) {

            $rowMeta = $metaClass->renderMetaRow($meta);

            $html .= '<li class="meta-by-group ' . $meta['META_TYPE_CODE'] . '-type-code" id="' . $meta['META_DATA_ID'] . '">';
            $html .= '<figure class="directory">';
            
            if (isset($isProxy)) {
                $isChecked = '';
                if ($meta['IS_DEFAULT'] == 1) {
                    $isChecked = ' checked="checked"';
                }
                $html .= '<label><input type="radio" name="isDefaultMap" value="'.$meta['META_DATA_ID'].'"'.$isChecked.'/> Дефаулт эсэх</label>';
            }
            
            $secondOrderInput = '';
            
            if ($isSecondOrder) {
                $secondOrderInput = '<input type="text" name="mapSecondOrderNum[]" class="only-detail-view-show" title="Бодолтын дараалал" value="'.$meta['SECOND_ORDER_NUM'].'">';
            }
            
            $html .= '<div class="folder-link" title="' . $meta['META_DATA_NAME'] . '">
                        <div class="img-precontainer">
                            <div class="img-container directory"><span></span>
                                <img class="directory-img" src="' . $rowMeta['BIG_ICON'] . '"/>
                            </div>
                        </div>
                        <div class="img-precontainer-mini directory">
                            <div class="img-container-mini"><span></span>
                                <img class="directory-img" src="' . $rowMeta['SMALL_ICON'] . '"/>
                            </div>
                        </div>
                        <div class="box">
                            <h4 class="ellipsis">' . $secondOrderInput . $meta['META_DATA_NAME'] . '</h4>
                        </div>
                    </div>
                    <div class="file-code"><span class="d-block">' . $meta['META_DATA_CODE'] . '</span></div>
                    <div class="file-date"><span class="d-block">' . $meta['META_DATA_ID'] . '</span></div>
                    <div class="file-user">' . $meta['META_TYPE_NAME'] . '</div>
                    <input type="hidden" name="'.$idInputName.'[]" value="' . $meta['META_DATA_ID'] . '">
                </figure>
            </li>';
        }

        return $html;
    }

    public function getMetaDataFolderIdsNamesModel($metaDataId) {
        
        if (!empty($metaDataId)) {
            
            $data = $this->db->GetAll("
                SELECT 
                    FM.FOLDER_ID, 
                    FF.FOLDER_NAME 
                FROM META_DATA_FOLDER_MAP FM 
                    INNER JOIN FVM_FOLDER FF ON FF.FOLDER_ID = FM.FOLDER_ID 
                WHERE FM.META_DATA_ID = ".$this->db->Param(0), 
                array($metaDataId)
            );
            
            if ($data) {
                
                $folderInput = '';
                
                foreach ($data as $row) {
                    $folderInput .= '<div class="meta-folder-tag">
                        <input type="hidden" name="folderId[]" value="'.$row['FOLDER_ID'].'">    
                        <span class="parent-folder-name"><a href="mdmetadata/system#objectType=folder&objectId='.$row['FOLDER_ID'].'&focusMetaId='.$metaDataId.'" target="_blank" title="Фолдер руу очих">' . $row['FOLDER_NAME'] . '</a></span>
                        <span class="meta-folder-tag-remove" onclick="removeMetaFolderTag(this);"><i class="fa fa-times"></i></span>
                    </div>';
                }
                
                return $folderInput;
            }
        }
        
        return null;
    }

    public function getMetaDataFolderNamesModel($metaDataId) {
        
        $data = $this->db->GetAll("
            SELECT 
                FM.FOLDER_ID, 
                FF.FOLDER_NAME 
            FROM META_DATA_FOLDER_MAP FM 
                INNER JOIN FVM_FOLDER FF ON FF.FOLDER_ID = FM.FOLDER_ID 
            WHERE FM.META_DATA_ID = ".$this->db->Param(0), 
            array($metaDataId)
        );

        if ($data) {
            return Arr::implode_key(', ', $data, 'FOLDER_NAME', true);
        }
        return null;
    }
    
    public function getMetaTagIdsNamesModel($metaDataId) {
        $tagInput = '';
        if (!empty($metaDataId)) {
            $data = $this->db->GetAll("
                SELECT 
                    MT.ID, 
                    MT.NAME 
                FROM META_TAG_MAP TM 
                    INNER JOIN META_TAG MT ON MT.ID = TM.TAG_ID 
                WHERE TM.META_DATA_ID = $metaDataId"
            );
            if ($data) {
                foreach ($data as $row) {
                    $tagInput .= '<div class="meta-folder-tag">
                        <input type="hidden" name="tagId[]" value="' . $row['ID'] . '">    
                        <span class="parent-folder-name">' . $row['NAME'] . '</span>
                        <span class="meta-folder-tag-remove" onclick="removeMetaFolderTag(this);"><i class="fa fa-times"></i></span>
                    </div>';
                }
            }
        }
        return $tagInput;
    }
    
    public function getMetaBugFixesModel($metaDataId) {

        $data = $this->db->GetAll("
            SELECT 
                DISTINCT 
                BF.ID, 
                BF.DESCRIPTION, 
                BF.CREATED_DATE
            FROM META_BUG_FIXING BF 
                INNER JOIN META_BUG_FIXING_DTL FD ON FD.META_BUG_FIXING_ID = BF.ID 
            WHERE FD.META_DATA_ID = ".$this->db->Param(0)." 
            ORDER BY BF.CREATED_DATE ASC", array($metaDataId));
        
        $html = '';
        
        foreach ($data as $meta) {

            $html .= '<li class="meta-by-bugfix -type-code" id="' . $meta['ID'] . '">';
            $html .= '<figure class="directory">';
            
            $html .= '<div class="folder-link" title="' . $meta['DESCRIPTION'] . '">
                        <div class="img-precontainer">
                            <div class="img-container directory"><span></span>
                                <img class="directory-img" src="assets/core/global/img/meta/file.png"/>
                            </div>
                        </div>
                        <div class="img-precontainer-mini directory">
                            <div class="img-container-mini"><span></span>
                                <img class="directory-img" src="assets/core/global/img/meta/file-mini.png"/>
                            </div>
                        </div>
                        <div class="box">
                            <h4 class="ellipsis">' . $meta['DESCRIPTION'] . '</h4>
                        </div>
                    </div>
                    <div class="file-user"><span class="d-block">' . $meta['ID'] . '</span></div>
                    <div class="file-date"><span class="d-block">' . $meta['CREATED_DATE'] . '</span></div>
                    <input type="hidden" name="childMetaBugFixId[]" value="' . $meta['ID'] . '">
                </figure>
            </li>';
        }

        return $html;
    }

    public function getSystemAllTablesModel() {
        $tables = $this->db->MetaTables('TABLES');
        $array = array();
        foreach ($tables as $k => $table) {
            $array[$k]['TABLE_NAME'] = $table;
        }
        $data = Arr::array_msort($array, array('TABLE_NAME' => SORT_ASC));
        return $data;
    }

    public function getSystemAllViewsModel() {
        $tables = $this->db->MetaTables('VIEWS');
        $array = array();
        foreach ($tables as $k => $table) {
            $array[$k]['TABLE_NAME'] = $table;
        }
        $data = Arr::array_msort($array, array('TABLE_NAME' => SORT_ASC));
        return $data;
    }

    public function getTableFieldsModel($tableName) {
        return Arr::objectToArray($this->db->MetaColumns($tableName));
    }

    public function getStandartFieldsModel() {
        $array = array(
            array(
                'FIELD_NAME' => 'META_VALUE_ID'
            ),
            array(
                'FIELD_NAME' => 'META_VALUE_CODE'
            ),
            array(
                'FIELD_NAME' => 'META_VALUE_NAME'
            ),
            array(
                'FIELD_NAME' => 'META_VALUE'
            ),
            array(
                'FIELD_NAME' => 'PARENT_ID'
            )
        );

        return $array;
    }

    public function getWebServiceLanguageModel() {
        $data = $this->db->GetAll("
            SELECT 
                SERVICE_LANGUAGE_ID, 
                SERVICE_LANGUAGE_CODE  
            FROM WEB_SERVICE_LANGUAGE 
            ORDER BY SERVICE_LANGUAGE_CODE ASC");

        return $data;
    }

    public function getActiveDmReportModelModel() {
        $data = $this->db->GetAll("
            SELECT 
                REPORT_MODEL_ID, 
                REPORT_MODEL_NAME  
            FROM DM_REPORT_MODEL 
            ORDER BY REPORT_MODEL_NAME ASC");

        return $data;
    }

    public function getDmChartModel() {
        $data = $this->db->GetAll("
            SELECT 
                CHART_ID, 
                CHART_NAME   
            FROM DM_CHART 
            ORDER BY CHART_NAME ASC");

        return $data;
    }

    public function getDirectoryListModel() {

        $param = array(
            'systemMetaGroupId' => Config::getFromCache('ECM_CUSTOMER_DIRECTORY_LIST'),
            'showQuery' => 0, 
            'ignorePermission' => 1 
        );

        $result = array();

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($data['status'] == 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);
            $result = $data['result'];
        }

        return $result;
    }

    public function getBookmarkData($metaDataId) {
        $row = $this->db->GetRow("
            SELECT 
                BOOKMARK_URL, 
                TARGET 
            FROM META_BOOKMARK_LINKS 
            WHERE META_DATA_ID = ".$this->db->Param(0), 
            array($metaDataId) 
        );
        
        if ($row) {
            return $row;
        }
        
        $row['BOOKMARK_URL'] = null;
        $row['TARGET'] = null;

        return $row;
    }

    public function createFolderModel() {
        
        try {
            
            $folderId = getUID();
            $sessionUserId = Ue::sessionUserId();
            $currentDate = Date::currentDate('Y-m-d H:i:s');

            $data = array(
                'FOLDER_ID'        => $folderId,
                'FOLDER_CODE'      => Input::post('folderCode'),
                'FOLDER_NAME'      => Input::post('folderName'),
                'PARENT_FOLDER_ID' => Input::numeric('parentFolderId'),
                'IS_PRIVATE'       => 0, 
                'IS_ACTIVE'        => 1, 
                'FOLDER_TYPE'      => 'STATIC', 
                'CREATED_USER_ID'  => $sessionUserId, 
                'CREATED_DATE'     => $currentDate
            );
            $result = $this->db->AutoExecute('FVM_FOLDER', $data);

            if ($result) {

                if (Ue::sessionIsUseFolderPermission()) {

                    $sessionUserKeyId = Ue::sessionUserKeyId();

                    if ($sessionUserKeyId != 1) {

                        $dataPermission = array(
                            'ID'           => getUID(),
                            'FOLDER_ID'    => $folderId,
                            'USER_ID'      => $sessionUserKeyId,
                            'CREATED_DATE' => $currentDate
                        );
                        $this->db->AutoExecute('FVM_FOLDER_USER_PERMISSION', $dataPermission);
                    }
                }

                $response = array('status' => 'success', 'folderId' => $folderId, 'message' => $this->lang->line('msg_save_success'));

            } else {
                $response = array('status' => 'error', 'message' => $this->lang->line('msg_save_error'));
            }
            
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }

        return $response;
    }

    public function getFvmFolderByIdModel($folderId) {
        
        $row = $this->db->GetRow("
            SELECT 
                FF.FOLDER_ID, 
                FF.FOLDER_CODE, 
                FF.FOLDER_NAME, 
                FF.PARENT_FOLDER_ID, 
                FP.FOLDER_NAME AS PARENT_FOLDER_NAME 
            FROM FVM_FOLDER FF 
                LEFT JOIN FVM_FOLDER FP ON FP.FOLDER_ID = FF.PARENT_FOLDER_ID  
            WHERE FF.FOLDER_ID = ".$this->db->Param(0), 
            array($folderId)
        );
        
        return $row;
    }

    public function updateFolderModel() {

        $folderId = Input::post('folderId');

        $data = array(
            'FOLDER_CODE' => Input::post('folderCode'),
            'FOLDER_NAME' => Input::post('folderName'),
            'PARENT_FOLDER_ID' => ((Input::isEmpty('parentFolderId')) ? null : Input::post('parentFolderId')),
            'MODIFIED_USER_ID' => Ue::sessionUserKeyId(),
            'MODIFIED_DATE' => Date::currentDate('Y-m-d H:i:s')
        );
        $result = $this->db->AutoExecute('FVM_FOLDER', $data, 'UPDATE', 'FOLDER_ID = ' . $folderId);

        if ($result) {

            $response = array(
                'status' => 'success',
                'folderId' => $folderId,
                'message' => $this->lang->line('msg_edit_success')
            );

        } else {
            $response = array(
                'status' => 'error',
                'message' => $this->lang->line('msg_save_error')
            );
        }

        return $response;
    }

    public function getMetaIconType() {
        $data = $this->db->GetAll("
            SELECT 
                IT.ICON_TYPE_ID, 
                IT.ICON_TYPE_NAME, 
                IT.IS_DEFAULT, 
                (SELECT COUNT(META_ICON_ID) FROM META_DATA_ICON WHERE ICON_TYPE_ID = IT.ICON_TYPE_ID AND IS_ACTIVE = 1) AS COUNT_ICON
            FROM META_DATA_ICON_TYPE IT
            WHERE IT.IS_ACTIVE = 1 
            ORDER BY IT.ORDER_NUM ASC");

        if ($data) {
            return $data;
        }
        return null;
    }

    public function getMetaIconList($iconTypeId) {
        $data = $this->db->GetAll("
            SELECT 
                META_ICON_ID, 
                META_ICON_CODE, 
                META_ICON_NAME 
            FROM META_DATA_ICON 
            WHERE ICON_TYPE_ID = ".$this->db->Param(0)."  
                AND IS_ACTIVE = 1     
            ORDER BY META_ICON_ID ASC", 
            array($iconTypeId) 
        );

        if ($data) {
            return $data;
        }
        return null;
    }

    public function checkMetaDataCodeModel($metaDataCode) {
        $row = $this->db->GetRow("SELECT META_DATA_ID FROM META_DATA WHERE LOWER(META_DATA_CODE) = ".$this->db->Param(0), array(Str::lower($metaDataCode)));
        if ($row) {
            return true;
        }
        return false;
    }

    public function checkMetaDataCodeByUpdateModel($metaDataId, $metaDataCode) {
        $row = $this->db->GetRow("
            SELECT 
                META_DATA_ID 
            FROM META_DATA  
            WHERE META_DATA_ID <> ".$this->db->Param(0)." 
                AND LOWER(META_DATA_CODE) = ".$this->db->Param(1), 
            array($metaDataId, Str::lower($metaDataCode))    
        );
        if ($row) {
            return true;
        }
        return false;
    }
    
    public function checkIgnoreMetaTypeModel($metaTypeId, $metaDataId) {
        
        if (Ue::sessionIsUseFolderPermission()) {
            
            $sessionUserKeyId = Ue::sessionUserKeyId();
            $subType          = Input::post('bp_process_type', Input::post('groupType'));
            $id1Ph            = $this->db->Param(0);
            $id2Ph            = $this->db->Param(1);
            $id3Ph            = $this->db->Param(2);
            
            $rowType = $this->db->GetRow("
                SELECT 
                    FF.IGNORE_META_TYPE 
                FROM FVM_FOLDER_USER_PERMISSION FF 
                    LEFT JOIN META_TYPE MT ON MT.META_TYPE_CODE = FF.IGNORE_META_TYPE 
                WHERE (MT.META_TYPE_ID = $id1Ph OR FF.IGNORE_META_TYPE = $id2Ph) 
                    AND FF.USER_ID = $id3Ph", 
                array($metaTypeId, $subType, $sessionUserKeyId)
            );

            if ($rowType) {
                
                return false;
                
            } else {
                
                $rowMeta = $this->db->GetRow("
                    SELECT 
                        MD.META_DATA_ID 
                    FROM ( 
                        SELECT 
                            FM.META_DATA_ID 
                        FROM 
                        (
                            SELECT 
                                FF.FOLDER_ID 
                            FROM FVM_FOLDER FF 
                            CONNECT BY NOCYCLE FF.PARENT_FOLDER_ID = PRIOR FF.FOLDER_ID
                            START WITH FF.FOLDER_ID IN ( 
                                SELECT 
                                    FOLDER_ID 
                                FROM FVM_FOLDER_USER_PERMISSION 
                                WHERE FOLDER_ID IS NOT NULL 
                                    AND USER_ID = $id1Ph 
                                GROUP BY FOLDER_ID 
                            ) 
                            GROUP BY FF.FOLDER_ID 
                        ) F 
                        INNER JOIN META_DATA_FOLDER_MAP FM ON FM.FOLDER_ID = F.FOLDER_ID 

                        UNION ALL 

                        SELECT 
                            META_DATA_ID  
                        FROM FVM_FOLDER_USER_PERMISSION 
                        WHERE META_DATA_ID IS NOT NULL 
                            AND USER_ID = $id1Ph 
                    ) MD 
                    WHERE MD.META_DATA_ID = $id2Ph", 
                    array($sessionUserKeyId, $metaDataId)
                );
                
                if (!$rowMeta) {
                    return false;
                }
            }
        }
        
        return true;
    }

    public function getChildFolderIds($parentFolderId) {
        $data = $this->db->GetAll("
            SELECT 
                FOLDER_ID 
            FROM FVM_FOLDER 
            WHERE IS_ACTIVE = 1 
                AND LOWER(FOLDER_TYPE) = 'static' 
                AND PARENT_FOLDER_ID = ".$this->db->Param(0), array($parentFolderId));

        foreach ($data as $row) {
            self::$folderTreeDatas[self::$t] = $row['FOLDER_ID'];
            self::$t++;
            self::getChildFolderIds($row['FOLDER_ID']);
        }

        return self::$folderTreeDatas;
    }

    public function getMetaBusinessProcessLinkModel($metaDataId) {
        $row = $this->db->GetRow("
            SELECT 
                PL.CLASS_NAME, 
                PL.METHOD_NAME, 
                PL.INPUT_META_DATA_ID, 
                MDI.META_DATA_CODE AS INPUT_META_DATA_CODE, 
                MDI.META_DATA_NAME AS INPUT_META_DATA_NAME, 
                PL.OUTPUT_META_DATA_ID, 
                MDO.META_DATA_CODE AS OUTPUT_META_DATA_CODE, 
                MDO.META_DATA_NAME AS OUTPUT_META_DATA_NAME, 
                PL.WS_URL, 
                PL.SUB_TYPE, 
                PL.ACTION_TYPE, 
                SL.SERVICE_LANGUAGE_ID, 
                SL.SERVICE_LANGUAGE_CODE, 
                PL.ACTION_BTN, 
                PL.THEME,
                PL.COLUMN_COUNT,
                PL.TAB_COLUMN_COUNT,
                PL.LABEL_WIDTH,
                PL.WINDOW_HEIGHT,
                PL.WINDOW_SIZE,
                PL.WINDOW_TYPE,
                PL.WINDOW_WIDTH,
                PL.IS_TREEVIEW, 
                PL.IS_ADDON_PHOTO, 
                PL.IS_ADDON_FILE, 
                PL.IS_ADDON_COMMENT, 
                PL.IS_ADDON_COMMENT_TYPE, 
                PL.IS_ADDON_LOG, 
                PL.IS_ADDON_RELATION,
                PL.IS_ADDON_MV_RELATION,
                PL.IS_ADDON_WFM_LOG, 
                PL.IS_ADDON_WFM_LOG_TYPE,
                PL.REF_META_GROUP_ID, 
                MDS.META_DATA_CODE AS REF_META_GROUP_CODE, 
                MDS.META_DATA_NAME AS REF_META_GROUP_NAME, 
                PL.PROCESS_NAME, 
                PL.SYSTEM_META_GROUP_ID, 
                MDG.META_DATA_CODE AS SYSTEM_META_GROUP_CODE, 
                MDG.META_DATA_NAME AS SYSTEM_META_GROUP_NAME, 
                PL.GETDATA_PROCESS_ID, 
                MD.META_DATA_CODE AS GETDATA_PROCESS_CODE,
                MD.META_DATA_NAME AS GETDATA_PROCESS_NAME,
                PL.THEME_CODE,
                PL.SKIN,
                PL.RUN_MODE,
                MDD.HELP_CONTENT_ID, 
                PL.IS_SHOW_PREVNEXT,
                PL.IS_WIDGET, 
                PL.MOBILE_THEME, 
                PL.WORKIN_TYPE, 
                PL.IS_RULE, 
                PL.IS_TOOLS_BTN, 
                PL.IS_BPMN_TOOL, 
                PL.IS_OFFLINE_MODE, 
                PL.JSON_CONFIG, 
                PL.IS_SAVE_VIEW_LOG
            FROM META_BUSINESS_PROCESS_LINK PL 
                INNER JOIN META_DATA MDD ON MDD.META_DATA_ID = PL.META_DATA_ID 
                LEFT JOIN WEB_SERVICE_LANGUAGE SL ON SL.SERVICE_LANGUAGE_ID = PL.SERVICE_LANGUAGE_ID 
                LEFT JOIN META_DATA MD ON MD.META_DATA_ID = PL.GETDATA_PROCESS_ID 
                LEFT JOIN META_DATA MDG ON MDG.META_DATA_ID = PL.SYSTEM_META_GROUP_ID 
                LEFT JOIN META_DATA MDI ON MDI.META_DATA_ID = PL.INPUT_META_DATA_ID 
                LEFT JOIN META_DATA MDO ON MDO.META_DATA_ID = PL.OUTPUT_META_DATA_ID 
                LEFT JOIN META_DATA MDS ON MDS.META_DATA_ID = PL.REF_META_GROUP_ID 
            WHERE PL.META_DATA_ID = ".$this->db->Param(0), array($metaDataId)
        );

        if ($row) {
            return $row;
        } else {
            $row['CLASS_NAME'] = null;
            $row['METHOD_NAME'] = null;
            $row['INPUT_META_DATA_ID'] = null;
            $row['OUTPUT_META_DATA_ID'] = null;
            $row['WS_URL'] = null;
            $row['SERVICE_LANGUAGE_ID'] = null;
            $row['SERVICE_LANGUAGE_CODE'] = null;
            $row['SUB_TYPE'] = null;
            $row['ACTION_TYPE'] = null;
            $row['ACTION_BTN'] = null;
            $row['THEME'] = null;
            $row['COLUMN_COUNT'] = null;
            $row['LABEL_WIDTH'] = null;
            $row['WINDOW_SIZE'] = null;
            $row['WINDOW_HEIGHT'] = null;
            $row['WINDOW_TYPE'] = null;
            $row['WINDOW_WIDTH'] = null;
            $row['IS_TREEVIEW'] = null;
            $row['IS_ADDON_PHOTO'] = null;
            $row['IS_ADDON_FILE'] = null;
            $row['IS_ADDON_COMMENT'] = null;
            $row['IS_ADDON_COMMENT_TYPE'] = null;
            $row['IS_ADDON_LOG'] = null;
            $row['REF_META_GROUP_ID'] = null;
            $row['SYSTEM_META_GROUP_ID'] = null;
            $row['PROCESS_NAME'] = null;
            $row['GETDATA_PROCESS_ID'] = null;
            $row['GETDATA_PROCESS_NAME'] = null;
            $row['THEME_CODE'] = null;
            $row['SKIN'] = null;
            $row['RUN_MODE'] = null;
            $row['HELP_CONTENT_ID'] = null;
            $row['IS_SHOW_PREVNEXT'] = null;
            $row['IS_ADDON_RELATION'] = null;
            $row['IS_ADDON_WFM_LOG'] = null;
            $row['IS_ADDON_WFM_LOG_TYPE'] = null;
            $row['IS_WIDGET'] = null;
            $row['IS_TOOLS_BTN'] = null;
            $row['IS_BPMN_TOOL'] = null;
            $row['IS_SAVE_VIEW_LOG'] = null;
            $row['MOBILE_THEME'] = null;
            $row['WORKIN_TYPE'] = null;
            $row['IS_RULE'] = null;
            $row['IS_OFFLINE_MODE'] = null;
            $row['JSON_CONFIG'] = null;

            return $row;
        }
    }
    
    public function getBpMobileWidgetModel() {
        
        $data = $this->db->GetAll("
            SELECT 
                ID, 
                CODE, 
                NAME 
            FROM META_WIDGET 
            WHERE LOWER(CODE) LIKE 'bp_t%' 
                AND PREVIEW_MOBILEIMAGE IS NOT NULL 
            ORDER BY ID ASC");
        
        return $data;
    }

    public function getMetaReportLinkModel($metaDataId) {
        $row = $this->db->GetRow("
            SELECT
                WM.REPORT_MODEL_ID, 
                WM.REPORT_MODEL_NAME 
            FROM META_REPORT_LINK PL 
                INNER JOIN DM_REPORT_MODEL WM ON WM.REPORT_MODEL_ID = PL.REPORT_MODEL_ID 
            WHERE PL.META_DATA_ID = $metaDataId"
        );

        if ($row) {
            return $row;
        } else {
            $row['REPORT_MODEL_ID'] = null;
            $row['REPORT_MODEL_NAME'] = null;

            return $row;
        }
    }

    public function getContentLinkModel($metaDataId) {
        $row = $this->db->GetRow("
            SELECT
                WM.LAYOUT_ID, 
                WM.LAYOUT_NAME 
            FROM META_CONTENT_LINK PL 
                INNER JOIN META_CONTENT_LAYOUT WM ON WM.LAYOUT_ID = PL.LAYOUT_ID 
            WHERE PL.META_DATA_ID = $metaDataId"
        );

        if ($row) {
            return $row;
        } else {
            $row['LAYOUT_ID'] = null;
            $row['LAYOUT_NAME'] = null;

            return $row;
        }
    }

    public function getMetaMenuLinkModel($metaDataId) {
        $row = $this->db->GetRow("
            SELECT
                ML.MENU_POSITION,
                ML.MENU_ALIGN,
                ML.MENU_THEME,
                ML.ACTION_META_DATA_ID,
                ML.COUNT_META_DATA_ID,
                ML.WEB_URL,
                ML.URL_TARGET,
                MD.META_DATA_CODE AS ACTION_META_DATA_CODE,
                MD.META_DATA_NAME AS ACTION_META_DATA_NAME,
                MDA.META_DATA_CODE AS META_DATA_CODE,
                MDA.META_DATA_NAME AS META_DATA_NAME,
                MDC.META_DATA_CODE AS COUNT_META_DATA_CODE,
                MDC.META_DATA_NAME AS COUNT_META_DATA_NAME,
                ML.ICON_NAME,
                ML.PHOTO_NAME,
                ML.BG_PHOTO_NAME,
                ML.MENU_COLOR,
                ML.VIEW_TYPE,
                ML.IS_SHOW_CARD,
                ML.IS_CONTENT_UI,
                ML.IS_MODULE_SIDEBAR,
                ML.IS_DEFAULT_OPEN,
                ML.IS_OFFLINE_MODE, 
                ML.GLOBE_CODE,
                ML.MENU_CODE,
                ML.IS_MONPASS_KEY, 
                ML.MENU_TOOLTIP 
            FROM META_MENU_LINK ML
                LEFT JOIN META_DATA MD ON MD.META_DATA_ID = ML.ACTION_META_DATA_ID
                LEFT JOIN META_DATA MDA ON MDA.META_DATA_ID = ML.VIEW_META_DATA_ID
                LEFT JOIN META_DATA MDC ON ML.COUNT_META_DATA_ID = MDC.META_DATA_ID
            WHERE ML.META_DATA_ID = ".$this->db->Param(0), array($metaDataId)
        );

        return $row;
    }

    public function getMetaCalendarLinkModel($metaDataId, $mergeMetaRow = false) {
        
        $row = $this->db->GetRow("SELECT VIEW_SIZE FROM META_CALENDAR_LINK WHERE META_DATA_ID = $metaDataId");

        if ($mergeMetaRow) {
            $row = array_merge($row, self::getMetaDataModel($metaDataId));
        }

        if ($row) {
            return $row;
        } else {
            $row['VIEW_SIZE'] = null;
            return $row;
        }
    }

    public function getMetaDonutLinkModel($metaDataId) {
        $row = $this->db->GetRow("
            SELECT 
                D.DONUT_ID,
                D.META_DATA_ID,
                D.INFO,
                D.TEXT,
                D.DIMENSION,
                D.WIDTH,
                D.FONTSIZE,
                D.FGCOLOR,
                D.BGCOLOR,
                D.PROCESS_META_DATA_ID,
                PL.META_DATA_CODE AS PROCESS_META_DATA_CODE, 
                PL.META_DATA_NAME AS PROCESS_META_DATA_NAME, 
                D.URL,
                D.FILL,
                D.T_META_BUS_PROCESS_LINK_ID, 
                MD.META_DATA_NAME,
                MI.META_ICON_NAME 
            FROM META_DONUT D
                INNER JOIN META_DATA MD ON D.META_DATA_ID = MD.META_DATA_ID AND MD.IS_ACTIVE = 1 
                LEFT JOIN META_DATA PL ON D.PROCESS_META_DATA_ID = PL.META_DATA_ID 
                LEFT JOIN META_DATA_ICON MI ON MD.META_ICON_ID = MI.META_ICON_ID
            WHERE D.META_DATA_ID = $metaDataId"
        );
        if ($row) {
            return $row;
        } else {
            return null;
        }
    }

    public function getMetaCardLinkModel($metaDataId) {
        $row = $this->db->GetRow("
            SELECT
                MC.CARD_ID,
                MC.META_DATA_ID,
                MC.TEXT,
                MC.WIDTH,
                MC.HEIGHT,
                MC.FONTSIZE,
                MC.BGCOLOR,
                MC.ADDCLASS,
                MC.FONT_ICON,
                MC.URL,
                MC.IS_SHOW_URL,
                MC.TEXT_CSS,
                MC.PROCESS_META_DATA_ID,
                MC.TEXT_ALIGN, 
                PL.META_DATA_ID AS PROCESS_META_DATA_ID, 
                PL.META_DATA_CODE AS PROCESS_META_DATA_CODE, 
                PL.META_DATA_NAME AS PROCESS_META_DATA_NAME, 
                MD.META_DATA_NAME,
                MI.META_ICON_NAME,
                MC.IS_SEE,
                MC.VIEW_NAME,
                MC.DATA_VIEW_ID,
                MC.CHART_DATA_VIEW_ID,
                MC.CHART_TYPE,
                MDV1.META_DATA_CODE AS CHART_DATA_VIEW_CODE,
                MDV1.META_DATA_NAME AS CHART_DATA_VIEW_NAME,
                MC.DATA_VIEW_TYPE,
                MDV.META_DATA_CODE AS DATA_VIEW_CODE,
                MDV.META_DATA_NAME AS DATA_VIEW_NAME,
                MC.COLUMN_NAME,
                MC.AGGREGATE_NAME,
                '' AS CARD_RESULT
            FROM META_CARD MC 
                INNER JOIN META_DATA MD ON MC.META_DATA_ID = MD.META_DATA_ID AND MD.IS_ACTIVE = 1 
                LEFT JOIN META_DATA MDV ON MC.DATA_VIEW_ID = MDV.META_DATA_ID
                LEFT JOIN META_DATA MDV1 ON MC.CHART_DATA_VIEW_ID = MDV1.META_DATA_ID
                LEFT JOIN META_DATA PL ON MC.META_DATA_ID = PL.META_DATA_ID 
                LEFT JOIN META_DATA_ICON MI ON MI.META_ICON_ID = MD.META_ICON_ID 
            WHERE MC.META_DATA_ID = $metaDataId"
        );
        
        if ($row) {
            
            $dataViewId = $row['DATA_VIEW_ID'];
            
            if ($dataViewId && !Input::postCheck('metaTypeId')) {

                $param = array(
                    'systemMetaGroupId' => $dataViewId,
                    'showQuery' => 0, 
                    'ignorePermission' => 1, 
                    'aggregateFunction' => $row['AGGREGATE_NAME'], 
                    'aggregateField' => $row['COLUMN_NAME']
                );
                
                if (Input::isEmpty('workSpaceId') == false && Input::isEmpty('workSpaceParams') == false) {
                    
                    $this->load->model('mdwebservice', 'middleware/models/');
                    
                    $workSpaceId = Input::numeric('workSpaceId');
                    parse_str(Input::post('workSpaceParams'), $workSpaceParamArray);
                    $workSpaceParamArray = Arr::changeKeyLower($workSpaceParamArray);
                    
                    $getWorkSpaceParamMap = $this->model->getWorkSpaceParamMap($dataViewId, $workSpaceId);
                    
                    if ($getWorkSpaceParamMap) {
                        
                        $isParam = false;

                        foreach ($getWorkSpaceParamMap as $workSpaceParam) {

                            $fieldPath = strtolower($workSpaceParam['FIELD_PATH']);
                            $paramPath = strtolower($workSpaceParam['PARAM_PATH']);

                            if (isset($workSpaceParamArray['workspaceparam'][$fieldPath]) 
                                && $workSpaceParamArray['workspaceparam'][$fieldPath] != '') {

                                $paramDefaultCriteria[$paramPath][] = array(
                                    'operator' => '=',
                                    'operand' => $workSpaceParamArray['workspaceparam'][$fieldPath]
                                );
                                $isParam = true;

                            } elseif (isset($workSpaceParamArray[$paramPath]) 
                                && $workSpaceParamArray[$paramPath] != '') {

                                $paramDefaultCriteria[$paramPath][] = array(
                                    'operator' => '=',
                                    'operand' => $workSpaceParamArray[$paramPath]
                                );
                                $isParam = true;
                            }
                        }
                        
                        if ($isParam) {
                            $param['criteria'] = $paramDefaultCriteria;
                        }
                    }
                }
                
                $this->load->model('mdobject', 'middleware/models/');
                $dvCriteriaData = $this->model->dataViewHeaderDataModel($dataViewId);
                
                if ($dvCriteriaData) {
                    
                    $paramFilter = array();
                    
                    foreach ($dvCriteriaData as $dvCriteria) {
                        if ($dvCriteria['DEFAULT_VALUE'] != '') {
                            $paramFilter[$dvCriteria['META_DATA_CODE']][] = array(
                                'operator' => '=',
                                'operand' => Mdmetadata::setDefaultValue($dvCriteria['DEFAULT_VALUE'])
                            );
                        }
                    }
                    
                    if ($paramFilter) {
                        $param['criteria'] = $paramFilter;
                    }
                }
                
                if (!Input::isEmpty('filterJson')) {
                
                    $criteria = @json_decode(Str::cp1251_utf8(html_entity_decode($_POST['filterJson'], ENT_QUOTES, 'UTF-8')), true);

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
                
                if (Input::postCheck('defaultCriteriaData')) {
                    
                    parse_str(Input::post('defaultCriteriaData'), $defaultCriteriaData);

                    if (isset($defaultCriteriaData['param'])) {
                        
                        $this->load->model('mdobject', 'middleware/models/');
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
                            $operator = ($defaultCondition === '0') ? $defaultCriteriaCondition : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : 'like');

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
                                        $paramDefaultCriteria[$fieldLower][] = array(
                                            'operator' => $operator,
                                            'operand' => $paramVal
                                        );
                                    }
                                }

                            } else {

                                $defParamVal = Input::param(trim($defParamVal));
                                $defParamVal = Mdmetadata::setDefaultValue($defParamVal);
                                $mandatoryCriteria = isset($defaultCriteriaData['mandatoryCriteria'][$defParam]) ? '1' : '0';

                                if ($defParamVal != '' || $mandatoryCriteria === '1') {

                                    $defParamValue = (strtolower($operator) === 'like') ? '%'.$defParamVal.'%' : $defParamVal; 

                                    $getTypeCode = $this->model->getDataViewGridCriteriaRowModel($metaDataId, $defParam);
                                    $getTypeCodeLower = strtolower($getTypeCode['META_TYPE_CODE']);

                                    if ($getTypeCodeLower == 'date' || $getTypeCodeLower == 'datetime') {

                                        $defParamVal = str_replace(
                                            array('____-__-__', '___-__-__', '__-__-__', '_-__-__', '-__-__', '-__', '_', '__:__', ':__'), '', $defParamVal
                                        );

                                        $operator = ($defaultCondition === '0') ? '=' : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : '='); 
                                        $defParamValue = $defParamVal;

                                    } elseif ($getTypeCodeLower == 'long' || $getTypeCodeLower == 'integer' || $getTypeCodeLower == 'number') {

                                        $defParamVal = Number::decimal($defParamVal);

                                        $operator = ($defaultCondition === '0') ? '=' : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : '='); 
                                        $defParamValue = $defParamVal;

                                    } elseif ($getTypeCodeLower == 'bigdecimal') {

                                        $defParamVal = Number::decimal($defParamVal);

                                    } elseif ($getTypeCodeLower == 'boolean') {

                                        $operator = '=';
                                        $defParamValue = $defParamVal;
                                    }

                                    if ($defParam == 'booktypename') {
                                        $operator = ($defaultCondition === '0') ? '!=' : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : '!='); 
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
                
                WebService::$isUseReport = true;
                $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

                $row['CARD_RESULT'] = '';

                if ($data['status'] == 'success' && isset($data['result'])) {
                    
                    if (is_array($data['result']) && array_key_exists('aggregatecolumns', $data['result'])) {
                        unset($data['result']['aggregatecolumns']);
                        unset($data['result']['paging']);
                    }
                    
                    $resultValue = $this->ws->getValue($data['result']);
                    
                    if (is_numeric($resultValue)) {
                        $resultValue = Number::trimTrailingZeroes(number_format($resultValue, 2, '.', ','));
                    }
                    $row['CARD_RESULT'] = $resultValue;
                }
            }

            return $row;
        } else {
            return null;
        }
    }

    public function getMetaGroupLinkModel($metaDataId) {
        $row = $this->db->GetRow("
            SELECT 
                ML.ID, 
                ML.LABEL_POSITION, 
                ML.BANNER_POSITION, 
                ML.COLUMN_COUNT, 
                ML.LABEL_WIDTH, 
                ML.GROUP_TYPE, 
                ML.TABLE_NAME, 
                ML.IS_TREEVIEW, 
                ML.WINDOW_TYPE, 
                ML.WINDOW_SIZE, 
                ML.WINDOW_WIDTH, 
                ML.WINDOW_HEIGHT,
                ML.REF_META_GROUP_ID, 
                ML.IS_ENTITY, 
                ML.LIST_NAME,
                ML.IS_SKIP_UNIQUE_ERROR,
                ML.SEARCH_TYPE, 
                ML.FORM_CONTROL, 
                ML.IS_NOT_GROUPBY, 
                ML.IS_ALL_NOT_SEARCH, 
                ML.REF_STRUCTURE_ID,
                ML.IS_USE_RT_CONFIG,
                MD.META_DATA_NAME AS REF_STRUCTURE_NAME, 
                MD.META_DATA_CODE AS REF_STRUCTURE_CODE,
                ML.IS_USE_WFM_CONFIG,
                ML.IS_USE_SIDEBAR, 
                ML.IS_USE_QUICKSEARCH,
                ML.IS_USE_RESULT,
                ML.IS_EXPORT_TEXT,
                ML.BUTTON_BAR_STYLE, 
                ML.REFRESH_TIMER, 
                ML.M_CRITERIA_COL_COUNT, 
                ML.M_GROUP_CRITERIA_COL_COUNT, 
                ML.USE_BASKET, 
                ML.CALCULATE_PROCESS_ID, 
                ML.IS_COUNTCARD_OPEN, 
                ML.IS_LOOKUP_BY_THEME, 
                ML.QS_META_DATA_ID, 
                ML.DATA_LEGEND_DV_ID, 
                ML.IS_IGNORE_EXCEL_EXPORT, 
                ML.IS_USE_DATAMART, 
                ML.LIST_MENU_NAME, 
                ML.SHOW_POSITION, 
                ML.IS_CRITERIA_ALWAYS_OPEN, 
                ML.IS_ENTER_FILTER, 
                ML.IS_FILTER_LOG, 
                ML.IS_IGNORE_SORTING, 
                ML.IS_IGNORE_WFM_HISTORY, 
                ML.IS_DIRECT_PRINT, 
                ML.IS_CLEAR_DRILL_CRITERIA, 
                ML.POSTGRE_SQL, 
                ML.MS_SQL, 
                MDC.META_DATA_CODE AS CALCULATE_PROCESS_CODE, 
                MDC.META_DATA_NAME AS CALCULATE_PROCESS_NAME, 
                MDQ.META_DATA_CODE AS QS_META_DATA_CODE,  
                MDQ.META_DATA_NAME AS QS_META_DATA_NAME, 
                MDLE.META_DATA_CODE AS LEGEND_META_DATA_CODE,  
                MDLE.META_DATA_NAME AS LEGEND_META_DATA_NAME, 
                MDL.META_DATA_CODE AS LAYOUT_META_DATA_CODE, 
                MDL.META_DATA_NAME AS LAYOUT_META_DATA_NAME, 
                ML.RULE_PROCESS_ID, 
                MDR.META_DATA_CODE AS RULE_META_DATA_CODE, 
                MDR.META_DATA_NAME AS RULE_META_DATA_NAME, 
                ML.DRAG_ROWS_RUN_PROCESS_ID, 
                MDD.META_DATA_CODE AS DRAG_ROWS_RUN_PROCESS_CODE, 
                MDD.META_DATA_NAME AS DRAG_ROWS_RUN_PROCESS_NAME, 
                ML.LAYOUT_META_DATA_ID, 
                ML.EXTERNAL_META_DATA_ID, 
                ML.WS_URL, 
                ML.PANEL_TYPE, 
                ML.IS_PARENT_FILTER, 
                ML.IS_USE_SEMANTIC, 
                ML.IS_USE_BUTTON_MAP, 
                ML.IS_GMAP_USERLOCATION, 
                ML.IS_USE_COMPANY_DEPARTMENT_ID, 
                ML.IS_SHOW_FILTER_TEMPLATE, 
                ML.COLOR_SCHEMA, 
                ML.IS_IGNORE_CLEAR_FILTER, 
                ML.IS_FIRST_COL_FILTER, 
                ML.IS_CUSTOM, 
                ML.CLASS_NAME, 
                ML.METHOD_NAME 
            FROM META_GROUP_LINK ML 
                LEFT JOIN META_DATA MD ON MD.META_DATA_ID = ML.REF_STRUCTURE_ID 
                LEFT JOIN META_DATA MDC ON MDC.META_DATA_ID = ML.CALCULATE_PROCESS_ID 
                LEFT JOIN META_DATA MDQ ON MDQ.META_DATA_ID = ML.QS_META_DATA_ID 
                LEFT JOIN META_DATA MDLE ON MDLE.META_DATA_ID = ML.DATA_LEGEND_DV_ID 
                LEFT JOIN META_DATA MDL ON MDL.META_DATA_ID = ML.LAYOUT_META_DATA_ID 
                LEFT JOIN META_DATA MDR ON MDR.META_DATA_ID = ML.RULE_PROCESS_ID 
                LEFT JOIN META_DATA MDD ON MDD.META_DATA_ID = ML.DRAG_ROWS_RUN_PROCESS_ID 
            WHERE ML.META_DATA_ID = ".$this->db->Param(0), array($metaDataId));

        if ($row) {
            return $row;
        } else {
            $row['ID'] = null;
            $row['LABEL_POSITION'] = null;
            $row['BANNER_POSITION'] = null;
            $row['COLUMN_COUNT'] = null;
            $row['LABEL_WIDTH'] = null;
            $row['GROUP_TYPE'] = null;
            $row['TABLE_NAME'] = null;
            $row['IS_TREEVIEW'] = null;
            $row['WINDOW_TYPE'] = null;
            $row['WINDOW_SIZE'] = null;
            $row['WINDOW_WIDTH'] = null;
            $row['WINDOW_HEIGHT'] = null;
            $row['REF_META_GROUP_ID'] = null;
            $row['IS_ENTITY'] = null;
            $row['LIST_NAME'] = null;
            $row['IS_SKIP_UNIQUE_ERROR'] = '0';
            $row['SEARCH_TYPE'] = '0';
            $row['FOMR_CONTROL'] = '0';
            $row['IS_NOT_GROUPBY'] = null;
            $row['IS_ALL_NOT_SEARCH'] = null;
            $row['REF_STRUCTURE_ID'] = null;
            $row['REF_STRUCTURE_NAME'] = null;
            $row['IS_USE_RT_CONFIG'] = null;
            $row['IS_USE_WFM_CONFIG'] = null;
            $row['IS_USE_SIDEBAR'] = null;
            $row['IS_USE_QUICKSEARCH'] = null;
            $row['IS_USE_RESULT'] = null;
            $row['IS_EXPORT_TEXT'] = null;
            $row['BUTTON_BAR_STYLE'] = null;
            $row['REFRESH_TIMER'] = null;
            $row['M_CRITERIA_COL_COUNT'] = null;
            $row['M_GROUP_CRITERIA_COL_COUNT'] = null;
            $row['USE_BASKET'] = null;
            $row['IS_COUNTCARD_OPEN'] = null;
            $row['IS_LOOKUP_BY_THEME'] = null;
            $row['IS_IGNORE_EXCEL_EXPORT'] = null;
            $row['IS_USE_DATAMART'] = null;
            $row['LIST_MENU_NAME'] = null;
            $row['DATA_LEGEND_DV_ID'] = null;
            $row['SHOW_POSITION'] = null;
            $row['IS_CRITERIA_ALWAYS_OPEN'] = null;
            $row['IS_ENTER_FILTER'] = null;
            $row['IS_FILTER_LOG'] = null;
            $row['IS_IGNORE_SORTING'] = null;
            $row['IS_IGNORE_WFM_HISTORY'] = null;
            $row['IS_DIRECT_PRINT'] = null;
            $row['IS_CLEAR_DRILL_CRITERIA'] = null;
            $row['POSTGRE_SQL'] = null;
            $row['MS_SQL'] = null;
            $row['LAYOUT_META_DATA_ID'] = null;
            $row['IS_USE_BUTTON_MAP'] = null;
            $row['IS_GMAP_USERLOCATION'] = null;
            $row['IS_USE_COMPANY_DEPARTMENT_ID'] = null;
            $row['IS_SHOW_FILTER_TEMPLATE'] = null;
            $row['COLOR_SCHEMA'] = null;
            $row['IS_IGNORE_CLEAR_FILTER'] = null;
            $row['IS_CUSTOM'] = null;
            $row['CLASS_NAME'] = null;
            $row['METHOD_NAME'] = null;

            return $row;
        }
    }

    public function getMetaFieldLinkModel($metaDataId) {
        $row = $this->db->GetRow("
            SELECT 
                DATA_TYPE, 
                IS_SHOW, 
                IS_REQUIRED, 
                MIN_VALUE, 
                MAX_VALUE, 
                DEFAULT_VALUE, 
                PATTERN_ID, 
                LOOKUP_META_DATA_ID,
                LOOKUP_TYPE,
                DISPLAY_FIELD,
                VALUE_FIELD,
                CHOOSE_TYPE, 
                FILE_EXTENSION 
            FROM META_FIELD_LINK 
            WHERE META_DATA_ID = ".$this->db->Param(0), 
            array($metaDataId) 
        );

        if ($row) {
            return $row;
        } else {
            $row['DATA_TYPE'] = null;
            $row['IS_SHOW'] = null;
            $row['IS_REQUIRED'] = null;
            $row['MIN_VALUE'] = null;
            $row['MAX_VALUE'] = null;
            $row['DEFAULT_VALUE'] = null;
            $row['PATTERN_ID'] = null;
            $row['LOOKUP_META_DATA_ID'] = null;
            $row['LOOKUP_TYPE'] = null;
            $row['DISPLAY_FIELD'] = null;
            $row['VALUE_FIELD'] = null;
            $row['CHOOSE_TYPE'] = null;
            $row['FILE_EXTENSION'] = null;

            return $row;
        }
    }

    public function getGroupConfigRowByPath($metaDataId, $fieldPath) {
        $row = $this->db->GetRow("
            SELECT 
                MC.IS_SHOW, 
                MC.IS_REQUIRED, 
                MC.MIN_VALUE, 
                MC.MAX_VALUE, 
                MC.DEFAULT_VALUE, 
                MC.RECORD_TYPE, 
                MC.LOOKUP_META_DATA_ID, 
                MC.LOOKUP_TYPE, 
                MC.DISPLAY_FIELD, 
                MC.VALUE_FIELD, 
                MC.CHOOSE_TYPE, 
                MC.PATTERN_ID, 
                MC.FIELD_PATH, 
                MC.EXPRESSION_STRING, 
                MC.ENABLE_CRITERIA, 
                MC.TAB_NAME, 
                MC.SIDEBAR_NAME, 
                MC.COLUMN_NAME, 
                MC.JOIN_TYPE, 
                MC.PROCESS_META_DATA_ID, 
                MC.VISIBLE_CRITERIA, 
                MC.STYLE_CRITERIA, 
                MC.VALIDATION_CRITERIA, 
                MC.FEATURE_NUM, 
                MC.IS_COUNTCARD, 
                MC.PROCESS_META_DATA_ID, 
                MC.PROCESS_GET_PARAM_PATH, 
                MD.META_DATA_CODE AS LOOKUP_META_DATA_CODE, 
                MD.META_DATA_NAME AS LOOKUP_META_DATA_NAME, 
                MC.COLUMN_WIDTH,
                MC.TEXT_WEIGHT,
                MC.TEXT_COLOR,
                MC.HEADER_ALIGN,
                MC.BODY_ALIGN,
                MC.TEXT_TRANSFORM,  
                MC.BG_COLOR,
                MC.FONT_SIZE, 
                MC.COLUMN_AGGREGATE, 
                MC.IS_SELECT, 
                MC.RELATION_TYPE, 
                MC.IS_SAVE, 
                MC.IS_GROUP, 
                MC.INPUT_NAME,   
                MC.IS_UNIQUE,
                MC.IS_CRITERIA,
                MC.VALUE_CRITERIA,  
                MC.MANDATORY_CRITERIA,
                MC.REF_STRUCTURE_ID, 
                MC.SEPARATOR_TYPE,
                MC.AGGREGATE_FUNCTION, 
                MC.IS_BUTTON,
                MC.IS_MERGE,
                MC.ORDER_NUMBER,
                MC.LABEL_NAME,   
                MDS.META_DATA_CODE AS STRUCTURE_META_DATA_CODE, 
                MDS.META_DATA_NAME AS STRUCTURE_META_DATA_NAME, 
                MC.IS_RENDER_SHOW, 
                MC.REF_PARAM_NAME,
                MC.IS_SHOW_BASKET, 
                MC.IS_CRITERIA_SHOW_BASKET,  
                MC.IS_MANDATORY_CRITERIA,
                MC.IS_UM_CRITERIA,
                MC.LOOKUP_KEY_META_DATA_ID,
                MDK.META_DATA_CODE AS LOOKUP_KEY_META_DATA_CODE,
                MDK.META_DATA_NAME AS LOOKUP_KEY_META_DATA_NAME, 
                MC.FRACTION_RANGE, 
                MC.SEARCH_GROUPING_NAME, 
                MC.IS_SIDEBAR, 
                MC.IS_CRYPTED,
                MC.IS_BASKET,
                MC.IS_BASKET_EDIT, 
                MC.IS_TRANSLATE, 
                MC.IS_SHOW_MOBILE, 
                MC.EXCEL_COLUMN_WIDTH,
                MC.EXCEL_ROTATE, 
                MC.IS_IGNORE_EXCEL, 
                MC.COUNTCARD_THEME,
                MC.COUNTCARD_SELECTION, 
                MC.COUNTCARD_ORDER_NUMBER, 
                MC.SECOND_DISPLAY_ORDER, 
                MC.LOG_COLUMN_NAME,  
                MC.ICON_NAME, 
                MC.IS_ADVANCED, 
                MC.IS_KPI_CRITERIA, 
                MC.IS_PASS_FILTER, 
                MC.IS_FREEZE, 
                MC.IS_NOT_SHOW_CRITERIA, 
                MC.DEFAULT_OPERATOR, 
                MC.JSON_CONFIG, 
                MC.AGGREGATE_ALIAS_PATH 
            FROM META_GROUP_CONFIG MC 
                LEFT JOIN META_DATA MD ON MD.META_DATA_ID = MC.LOOKUP_META_DATA_ID    
                LEFT JOIN META_DATA MDS ON MDS.META_DATA_ID = MC.REF_STRUCTURE_ID    
                LEFT JOIN META_DATA MDK ON MDK.META_DATA_ID = MC.LOOKUP_KEY_META_DATA_ID    
            WHERE MC.MAIN_META_DATA_ID = ".$this->db->Param(0)."  
                AND LOWER(MC.FIELD_PATH) = ".$this->db->Param(1), 
            array($metaDataId, strtolower($fieldPath))
        );

        if ($row) {
            return $row;
        }
        return array();
    }

    public function getGroupParamConfig($metaDataId, $fieldPath) {
        $data = $this->db->GetAll("
            SELECT 
                PARAM_PATH, 
                PARAM_META_DATA_ID, 
                DEFAULT_VALUE, 
                IS_KEY_LOOKUP 
            FROM META_GROUP_PARAM_CONFIG 
            WHERE GROUP_META_DATA_ID = ".$this->db->Param(0)." 
                AND LOOKUP_META_DATA_ID IS NOT NULL     
                AND LOWER(FIELD_PATH) = " . $this->db->Param(1), 
            array($metaDataId, Str::lower($fieldPath))
        );

        $result = '';

        if ($data) {
            foreach ($data as $row) {
                if ($row['IS_KEY_LOOKUP'] == '1') {
                    $result .= Form::hidden(array('name' => 'paramGroupConfigParamPathKey[' . $fieldPath . '][]', 'value' => $row['PARAM_PATH']));
                    $result .= Form::hidden(array('name' => 'paramGroupConfigParamMetaKey[' . $fieldPath . '][]', 'value' => $row['PARAM_META_DATA_ID']));
                    $result .= Form::hidden(array('name' => 'paramGroupConfigDefaultValKey[' . $fieldPath . '][]', 'value' => $row['DEFAULT_VALUE']));
                } else {
                    $result .= Form::hidden(array('name' => 'paramGroupConfigParamPath[' . $fieldPath . '][]', 'value' => $row['PARAM_PATH']));
                    $result .= Form::hidden(array('name' => 'paramGroupConfigParamMeta[' . $fieldPath . '][]', 'value' => $row['PARAM_META_DATA_ID']));
                    $result .= Form::hidden(array('name' => 'paramGroupConfigDefaultVal[' . $fieldPath . '][]', 'value' => $row['DEFAULT_VALUE']));
                }
            }
        }
        return $result;
    }

    public function getGroupProcessParamConfig($metaDataId, $fieldPath) {
        $data = $this->db->GetAll("
            SELECT 
                PARAM_PATH, 
                PARAM_META_DATA_ID, 
                DEFAULT_VALUE 
            FROM META_GROUP_PARAM_CONFIG 
            WHERE GROUP_META_DATA_ID = ".$this->db->Param(0)."  
                AND PROCESS_META_DATA_ID IS NOT NULL     
                AND LOWER(FIELD_PATH) = " . $this->db->Param(1), 
            array($metaDataId, Str::lower($fieldPath))
        );

        $result = '';

        if ($data) {
            foreach ($data as $row) {
                $result .= Form::hidden(array('name' => 'paramProcessConfigParamPath[' . $fieldPath . '][]', 'value' => $row['PARAM_PATH']));
                $result .= Form::hidden(array('name' => 'paramProcessConfigParamMeta[' . $fieldPath . '][]', 'value' => $row['PARAM_META_DATA_ID']));
                $result .= Form::hidden(array('name' => 'paramProcessConfigDefaultVal[' . $fieldPath . '][]', 'value' => $row['DEFAULT_VALUE']));
            }
        }
        return $result;
    }

    public function getGroupRelationConfig($mainMetaDataId, $paramMetaDataId, $fieldPath) {
        $data = $this->db->GetAll("
            SELECT 
                SRC_META_GROUP_ID, 
                SRC_META_DATA_ID, 
                TRG_META_GROUP_ID, 
                TRG_META_DATA_ID, 
                BATCH_NUMBER, 
                TRG_PARAM_PATH, 
                DEFAULT_VALUE, 
                SRC_PARAM_PATH 
            FROM META_GROUP_RELATION 
            WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                AND TRG_META_GROUP_ID = ".$this->db->Param(1)."      
                AND LOWER(TRG_PARAM_PATH) LIKE ".$this->db->Param(2)." 
            GROUP BY 
                SRC_META_GROUP_ID, 
                SRC_META_DATA_ID, 
                TRG_META_GROUP_ID, 
                TRG_META_DATA_ID, 
                BATCH_NUMBER, 
                TRG_PARAM_PATH, 
                DEFAULT_VALUE, 
                SRC_PARAM_PATH", 
            array($mainMetaDataId, $paramMetaDataId, Str::lower($fieldPath).'%')
        );

        $result = '';

        if ($data) {
            foreach ($data as $row) {
                $result .= Form::hidden(array('name' => 'paramGroupRelationSrcGroupId[' . $fieldPath . '][]', 'value' => $row['SRC_META_GROUP_ID']));
                $result .= Form::hidden(array('name' => 'paramGroupRelationSrcMetaId[' . $fieldPath . '][]', 'value' => $row['SRC_META_DATA_ID']));
                $result .= Form::hidden(array('name' => 'paramGroupRelationTrgGroupId[' . $fieldPath . '][]', 'value' => $row['TRG_META_GROUP_ID']));
                $result .= Form::hidden(array('name' => 'paramGroupRelationTrgMetaId[' . $fieldPath . '][]', 'value' => $row['TRG_META_DATA_ID']));
                $result .= Form::hidden(array('name' => 'paramGroupRelationBatchNumber[' . $fieldPath . '][]', 'value' => $row['BATCH_NUMBER']));
                $result .= Form::hidden(array('name' => 'paramGroupRelationDefaultValue[' . $fieldPath . '][]', 'value' => $row['DEFAULT_VALUE']));
                $result .= Form::hidden(array('name' => 'paramGroupRelationTrgParamPath[' . $fieldPath . '][]', 'value' => $row['TRG_PARAM_PATH']));
                $result .= Form::hidden(array('name' => 'paramGroupRelationSrcParamPath[' . $fieldPath . '][]', 'value' => $row['SRC_PARAM_PATH']));
            }
        }
        return $result;
    }

    public function getParamRelationConfig($mainMetaDataId, $paramMetaDataId, $fieldPath) {
        $data = $this->db->GetAll("
            SELECT 
                SRC_META_GROUP_ID, 
                SRC_META_DATA_ID, 
                TRG_META_GROUP_ID, 
                TRG_META_DATA_ID, 
                BATCH_NUMBER, 
                TRG_PARAM_PATH, 
                SRC_PARAM_PATH 
            FROM META_GROUP_RELATION 
            WHERE MAIN_META_DATA_ID = $mainMetaDataId 
                AND SRC_META_GROUP_ID = $mainMetaDataId     
                AND SRC_META_DATA_ID = $paramMetaDataId     
                AND LOWER(SRC_PARAM_PATH) = '" . Str::lower($fieldPath) . "' 
            GROUP BY 
                SRC_META_GROUP_ID, 
                SRC_META_DATA_ID, 
                TRG_META_GROUP_ID, 
                TRG_META_DATA_ID, 
                BATCH_NUMBER, 
                TRG_PARAM_PATH, 
                SRC_PARAM_PATH");
        
        $result = '';
        
        if ($data) {
            foreach ($data as $row) {
                $result .= Form::hidden(array('name' => 'paramGroupRelationSrcGroupId[' . $fieldPath . '][]', 'value' => $row['SRC_META_GROUP_ID']));
                $result .= Form::hidden(array('name' => 'paramGroupRelationSrcMetaId[' . $fieldPath . '][]', 'value' => $row['SRC_META_DATA_ID']));
                $result .= Form::hidden(array('name' => 'paramGroupRelationTrgGroupId[' . $fieldPath . '][]', 'value' => $row['TRG_META_GROUP_ID']));
                $result .= Form::hidden(array('name' => 'paramGroupRelationTrgMetaId[' . $fieldPath . '][]', 'value' => $row['TRG_META_DATA_ID']));
                $result .= Form::hidden(array('name' => 'paramGroupRelationBatchNumber[' . $fieldPath . '][]', 'value' => $row['BATCH_NUMBER']));
                $result .= Form::hidden(array('name' => 'paramGroupRelationTrgParamPath[' . $fieldPath . '][]', 'value' => $row['TRG_PARAM_PATH']));
                $result .= Form::hidden(array('name' => 'paramGroupRelationSrcParamPath[' . $fieldPath . '][]', 'value' => $row['SRC_PARAM_PATH']));
            }
        }
        
        return $result;
    }

    public function getParamDefaultValues($mainMetaDataId, $fieldPath, $lookupMetaDataId) {
        if ($lookupMetaDataId == '') {
            return '';
        }

        $data = $this->db->GetAll("
            SELECT 
                VALUE_ID  
            FROM META_PARAM_VALUES 
            WHERE MAIN_META_DATA_ID = $mainMetaDataId  
                AND LOOKUP_META_DATA_ID = $lookupMetaDataId     
                AND LOWER(PARAM_PATH) = '".strtolower($fieldPath)."'");

        $result = '';

        if ($data) {
            foreach ($data as $row) {
                $result .= Form::hidden(array('name' => 'paramDefaultValueId[' . $fieldPath . '][]', 'value' => $row['VALUE_ID']));
            }
        }

        return $result;
    }

    public function commonMetaDataGridModel() {
        
        $selectedMetaId = Input::numeric('selectedMetaId');
        
        $page = Input::post('page', 1);
        $rows = Input::post('rows', 10);
        $offset = ($page - 1) * $rows;
        
        $selectColumn = $join = $where = '';

        if (Input::postCheck('folderId') && !Input::isEmpty('folderId')) {
            self::$folderTreeDatas = array();
            $folderId = Input::post('folderId');
            $childFolderIds = self::getChildFolderIds($folderId);
            $commaFolderIds = ((count($childFolderIds) > 0) ? $folderId . ',' . Arr::implode_r(',', $childFolderIds, true) : $folderId);
            $where .= " AND MD.META_DATA_ID IN (SELECT META_DATA_ID FROM META_DATA_FOLDER_MAP WHERE FOLDER_ID IN ($commaFolderIds))";
        }

        if (Input::postCheck('defaultCriteria')) {
            
            parse_str(Input::post('defaultCriteria'), $defaultQryStrings);
            
            foreach ($defaultQryStrings as $dk => $dv) {
                
                $dk = strtolower($dk);
                
                if ($dk == 'metatypeid' && !empty($dv)) {
                    if (strpos($dv, '|') === false) {
                        $where .= " AND MD.META_TYPE_ID = '" . Input::param($dv) . "'";
                    } else {
                        $where .= " AND MD.META_TYPE_ID IN (" . implode(',', explode("|", $dv)) . ")";
                    }
                } elseif ($dk == 'grouptype' && !empty($dv)) {
                    $where .= "AND (GL.GROUP_TYPE IS NULL OR (MD.META_TYPE_ID = 200101010000016 AND LOWER(GL.GROUP_TYPE) IN ('" . implode("','", explode("|", Input::param(strtolower($dv)))) . "')))";
                } elseif ($dk == 'iscomplexprocess' && $dv == '1') {
                    $isComplexProcess = true;
                } elseif ($dk == 'ignorepermission' && $dv == '1') {
                    $ignorePermission = true;
                }
            }
        }

        if (Input::postCheck('searchData')) {

            parse_str(Input::post('searchData'), $qryStrings);
            $condition = $qryStrings['condition'];

            foreach ($qryStrings as $k => $v) {
                $k = strtolower($k);
                
                if ($k == 'metadataid' && !empty($v)) {
                    if ($condition['metadataid'] == 'like') {
                        $where .= " AND MD.META_DATA_ID LIKE '%" . Input::param($v) . "%'";
                    } else {
                        $where .= " AND MD.META_DATA_ID = " . Input::param($v);
                    }
                } elseif ($k == 'metadatacode' && !empty($v)) {
                    if ($condition['metadatacode'] == 'like') {
                        $where .= " AND LOWER(MD.META_DATA_CODE) LIKE '%" . Str::lower(Input::param($v)) . "%'";
                    } else {
                        $where .= " AND LOWER(MD.META_DATA_CODE) = '" . Str::lower(Input::param($v)) . "'";
                    }
                } elseif ($k == 'metadataname' && !empty($v)) {
                    if ($condition['metadataname'] == 'like') {
                        $where .= " AND LOWER(MD.META_DATA_NAME) LIKE '%" . Str::lower(Input::param($v)) . "%'";
                    } else {
                        $where .= " AND LOWER(MD.META_DATA_NAME) = '" . Str::lower(Input::param($v)) . "'";
                    }
                } elseif ($k == 'metatypeid' && !empty($v)) {
                    if (strpos($v, '|') === false) {
                        $where .= " AND MD.META_TYPE_ID = '" . Input::param($v) . "'";
                    } else {
                        $where .= " AND MD.META_TYPE_ID IN (" . implode(',', explode("|", $v)) . ")";
                    }
                } elseif ($k == 'folderid' && !empty($v)) {
                    self::$folderTreeDatas = array();
                    $folderId = $v;
                    $childFolderIds = self::getChildFolderIds($folderId);
                    $commaFolderIds = ((count($childFolderIds) > 0) ? $folderId . ',' . Arr::implode_r(',', $childFolderIds, true) : $folderId);
                    $where .= " AND MD.META_DATA_ID IN (SELECT META_DATA_ID FROM META_DATA_FOLDER_MAP WHERE FOLDER_ID IN ($commaFolderIds))";
                }
            }
        }

        if (Input::postCheck('filterRules')) {
            $filterRules = json_decode(Str::cp1251_utf8($_POST['filterRules']), true);

            foreach ($filterRules as $rule) {
                $field = $rule['field'];
                $value = Input::param(Str::lower($rule['value']));
                if (!empty($value)) {
                    if ($field === 'META_DATA_ID') {
                        $where .= " AND MD.META_DATA_ID LIKE '%$value%'";
                    } elseif ($field === 'META_DATA_CODE') {
                        $where .= " AND LOWER(MD.META_DATA_CODE) LIKE '%$value%'";
                    } elseif ($field === 'META_DATA_NAME') {
                        $where .= " AND LOWER(MD.META_DATA_NAME) LIKE '%$value%'";
                    } elseif ($field === 'META_TYPE_NAME') {
                        $where .= " AND (LOWER(".$this->db->IfNull('DT.DATA_TYPE_NAME', 'MT.META_TYPE_NAME').") LIKE '%$value%')";
                    } elseif ($field === 'CREATED_PERSON_NAME') {
                        $where .= " AND (LOWER(".$this->db->IfNull('BP.FIRST_NAME', 'US.USERNAME').") LIKE '%$value%')";
                    }
                }
            }
        }
        
        if (isset($isComplexProcess)) {
            $selectColumn = 'CASE 
                WHEN (SELECT COUNT(META_PROCESS_WORKFLOW_ID) FROM META_PROCESS_WORKFLOW WHERE MAIN_BP_ID = MD.META_DATA_ID AND IS_ACTIVE = 1) > 0
                    THEN 1 
                ELSE 0 
                END AS IS_COMPLEX_PROCESS, ';
        }

        $sortField = 'CREATED_DATE';
        $sortOrder = 'DESC';
        
        if (Input::postCheck('sort') && Input::postCheck('order')) {
            $sortField = Input::post('sort');
            $sortOrder = Input::post('order');
        }
        
        if (!isset($ignorePermission) && Ue::sessionIsUseFolderPermission()) {
            
            $sessionUserKeyId = Ue::sessionUserKeyId();

            if ($sessionUserKeyId != 1) {
                
                $join = "INNER JOIN ( 
                    SELECT 
                        FM.META_DATA_ID 
                    FROM 
                    (
                        SELECT 
                            FF.FOLDER_ID 
                        FROM FVM_FOLDER FF 
                        CONNECT BY NOCYCLE FF.PARENT_FOLDER_ID = PRIOR FF.FOLDER_ID
                        START WITH FF.FOLDER_ID IN ( 
                            SELECT 
                                FOLDER_ID 
                            FROM FVM_FOLDER_USER_PERMISSION 
                            WHERE FOLDER_ID IS NOT NULL 
                                AND USER_ID = $sessionUserKeyId 
                            GROUP BY FOLDER_ID 
                        ) 
                        GROUP BY FF.FOLDER_ID 
                    ) F 
                    INNER JOIN META_DATA_FOLDER_MAP FM ON FM.FOLDER_ID = F.FOLDER_ID 
                    
                    UNION ALL 
                    
                    SELECT 
                        META_DATA_ID  
                    FROM FVM_FOLDER_USER_PERMISSION 
                    WHERE META_DATA_ID IS NOT NULL 
                        AND USER_ID = $sessionUserKeyId 
                ) FB ON FB.META_DATA_ID = MD.META_DATA_ID"; 
            }
        }
        
        if (!$selectedMetaId) {
            
            $rowCount = $this->db->GetRow(
                "SELECT 
                    COUNT(MD.META_DATA_ID) AS ROW_COUNT 
                FROM META_DATA MD 
                    INNER JOIN META_TYPE MT ON MT.META_TYPE_ID = MD.META_TYPE_ID 
                    LEFT JOIN META_DATA_ICON MI ON MI.META_ICON_ID = MD.META_ICON_ID 
                    LEFT JOIN UM_USER UM ON UM.USER_ID = MD.CREATED_USER_ID  
                    LEFT JOIN UM_SYSTEM_USER US ON US.USER_ID = UM.SYSTEM_USER_ID   
                    LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = US.PERSON_ID 
                    LEFT JOIN META_GROUP_LINK GL ON GL.META_DATA_ID = MD.META_DATA_ID  
                    LEFT JOIN META_FIELD_LINK FL ON FL.META_DATA_ID = MD.META_DATA_ID 
                    LEFT JOIN META_FIELD_DATA_TYPE DT ON DT.DATA_TYPE_CODE = FL.DATA_TYPE 
                    $join 
                WHERE MD.IS_ACTIVE = 1 $where");
            
        } else {
            $rowCount['ROW_COUNT'] = 1;
            $where = ' AND MD.META_DATA_ID = '.$selectedMetaId;
        }
        
        $selectList = "SELECT * 
                        FROM (
                            SELECT 
                                MD.META_DATA_ID, 
                                MD.META_DATA_CODE, 
                                MD.META_DATA_NAME, 
                                MD.META_TYPE_ID,  
                                LOWER(MT.META_TYPE_CODE) AS META_TYPE_CODE, 
                                " . $this->db->IfNull('DT.DATA_TYPE_NAME', 'MT.META_TYPE_NAME') . " AS META_TYPE_NAME, 
                                MI.META_ICON_NAME, 
                                MD.CREATED_DATE, 
                                " . $this->db->IfNull('BP.FIRST_NAME', 'US.USERNAME') . " AS CREATED_PERSON_NAME, 
                                $selectColumn 
                                GL.GROUP_TYPE 
                            FROM META_DATA MD 
                                INNER JOIN META_TYPE MT ON MT.META_TYPE_ID = MD.META_TYPE_ID 
                                LEFT JOIN META_DATA_ICON MI ON MI.META_ICON_ID = MD.META_ICON_ID 
                                LEFT JOIN UM_USER UM ON UM.USER_ID = MD.CREATED_USER_ID  
                                LEFT JOIN UM_SYSTEM_USER US ON US.USER_ID = UM.SYSTEM_USER_ID   
                                LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = US.PERSON_ID 
                                LEFT JOIN META_GROUP_LINK GL ON GL.META_DATA_ID = MD.META_DATA_ID  
                                LEFT JOIN META_FIELD_LINK FL ON FL.META_DATA_ID = MD.META_DATA_ID 
                                LEFT JOIN META_FIELD_DATA_TYPE DT ON DT.DATA_TYPE_CODE = FL.DATA_TYPE 
                                $join 
                            WHERE MD.IS_ACTIVE = 1 
                                $where
                        ) TEMP 
                        ORDER BY $sortField $sortOrder";
        
        $result = array();
        $result['total'] = $rowCount['ROW_COUNT'];
        $result['rows'] = array();

        if ($result['total'] > 0) {
            $rs = $this->db->SelectLimit($selectList, $rows, $offset);
            $result['rows'] = $rs->_array;
        }

        return $result;
    }

    public function getDMChartByMetaModel($metaDataId) {
        $row = $this->db->GetRow("SELECT CHART_ID FROM META_DASHBOARD_LINK WHERE META_DATA_ID = $metaDataId");
        if ($row) {
            return $row;
        }
        return null;
    }

    public function getMetaFieldDataTypeModel($where = null) {
        $data = $this->db->GetAll("
            SELECT 
                LOWER(DATA_TYPE_CODE) AS DATA_TYPE_CODE, 
                DATA_TYPE_NAME 
            FROM META_FIELD_DATA_TYPE 
            ".($where ? 'WHERE '.$where : '')." 
            ORDER BY DATA_TYPE_ID ASC");
        
        return $data;
    }

    public function getMetaFieldPatternModel($isDataMask = false) {
        $data = $this->db->GetAll("SELECT PATTERN_ID, PATTERN_NAME FROM META_FIELD_PATTERN WHERE ".($isDataMask ? 'IS_MASK = 2' : 'IS_MASK = 1 OR IS_MASK IS NULL'));
        return $data;
    }
    
    public function getDetailWidgetModel($typeId = 1) {
        $data = $this->db->GetAll("
            SELECT 
                ID, 
                CODE, 
                NAME, 
                PREVIEW_WEBIMAGE, 
                PREVIEW_MOBILEIMAGE 
            FROM META_WIDGET 
            WHERE (TYPE_ID = $typeId OR TYPE_ID = 5 OR TYPE_ID = 8) 
                AND IS_ACTIVE = 1 
            ORDER BY ID ASC");
        
        return $data;
    }

    public function getObjectFieldNameModel($metaDataId) {

        $data = $this->db->GetAll("
            SELECT 
                FIELD_PATH AS FIELD_NAME
            FROM META_GROUP_CONFIG MGC 
            WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                AND IS_SELECT = 1 
                AND PARENT_ID IS NULL 
                AND RECORD_TYPE IS NULL", 
            array($metaDataId)
        );
        
        if ($data) {
            return $data;
        }
        return array();
    }

    public function deleteMetaDataModel($metaDataId, $replaceMetaDataId) {
        
        $param = array(
            'id' => Input::param($metaDataId),
            'replaceId' => Input::param($replaceMetaDataId)
        );
        
        $result = $this->ws->runResponse(self::$gfServiceAddress, 'MD_DELETE', $param);

        if ($result['status'] == 'success') {
            
            (new Mdmeta())->dvCacheClearByMetaId($metaDataId);
            (new Mdmeta())->bpExpressionCacheClearById($metaDataId, true);
            
            return array('status' => 'success', 'message' => $this->lang->line('msg_delete_success'));
            
        } else {
            return array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
    }

    public function metaSearchModel($searchValue, $condition) {
        
        $join = '';
        
        if (Ue::sessionIsUseFolderPermission()) {
            
            $sessionUserKeyId = Ue::sessionUserKeyId();

            if ($sessionUserKeyId != 1) {
                
                $join .= "INNER JOIN ( 
                    SELECT 
                        FM.META_DATA_ID 
                    FROM 
                    (
                        SELECT 
                            FF.FOLDER_ID 
                        FROM FVM_FOLDER FF 
                        CONNECT BY NOCYCLE FF.PARENT_FOLDER_ID = PRIOR FF.FOLDER_ID
                        START WITH FF.FOLDER_ID IN ( 
                            SELECT 
                                FOLDER_ID 
                            FROM FVM_FOLDER_USER_PERMISSION 
                            WHERE FOLDER_ID IS NOT NULL 
                                AND USER_ID = $sessionUserKeyId 
                            GROUP BY FOLDER_ID 
                        ) 
                        GROUP BY FF.FOLDER_ID 
                    ) F 
                    INNER JOIN META_DATA_FOLDER_MAP FM ON FM.FOLDER_ID = F.FOLDER_ID 
                    
                    UNION ALL 
                    
                    SELECT 
                        META_DATA_ID  
                    FROM FVM_FOLDER_USER_PERMISSION 
                    WHERE META_DATA_ID IS NOT NULL 
                        AND USER_ID = $sessionUserKeyId 
                ) FB ON FB.META_DATA_ID = MD.META_DATA_ID"; 
            }
        }
        
        $searchValue = Str::lower($searchValue);

        if ($condition == 'like') {
            $where = "(
                LOWER(MD.META_DATA_NAME) LIKE '%$searchValue%' OR 
                LOWER(MD.META_DATA_CODE) LIKE '%$searchValue%'
            )";
        } else {
            $where = "(
                LOWER(MD.META_DATA_NAME) = '$searchValue' OR 
                LOWER(MD.META_DATA_CODE) = '$searchValue'
            )";
        }

        $data = $this->db->GetAll("
            SELECT 
                MD.META_DATA_ID, 
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME, 
                MD.CREATED_DATE, 
                MD.META_TYPE_ID,
                LOWER(MT.META_TYPE_CODE) AS META_TYPE_CODE, 
                " . $this->db->IfNull('BP.FIRST_NAME', 'UM.USERNAME') . " AS CREATED_PERSON_NAME, 
                DI.META_ICON_CODE, 
                BL.BOOKMARK_URL, 
                BL.TARGET AS BOOKMARK_TARGET, 
                RL.REPORT_MODEL_ID, 
                GL.GROUP_TYPE, 
                FMAP.FOLDER_ID 
            FROM META_DATA MD 
                INNER JOIN META_TYPE MT ON MT.META_TYPE_ID = MD.META_TYPE_ID 
                $join 
                LEFT JOIN META_DATA_FOLDER_MAP FMAP ON FMAP.META_DATA_ID = MD.META_DATA_ID 
                LEFT JOIN UM_SYSTEM_USER UM ON UM.USER_ID = MD.CREATED_USER_ID 
                LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = UM.PERSON_ID 
                LEFT JOIN META_DATA_ICON DI ON DI.META_ICON_ID = MD.META_ICON_ID 
                LEFT JOIN META_BOOKMARK_LINKS BL ON BL.META_DATA_ID = MD.META_DATA_ID 
                LEFT JOIN META_REPORT_LINK RL ON RL.META_DATA_ID = MD.META_DATA_ID 
                LEFT JOIN META_GROUP_LINK GL ON GL.META_DATA_ID = MD.META_DATA_ID 
            WHERE MD.IS_ACTIVE = 1 
                AND $where 
            ORDER BY 
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME 
            ASC"
        );

        if ($data) {
            return $data;
        }
        return null;
    }
    
    public function metaIdSearchModel($searchValue, $condition) {
        
        if (is_numeric($searchValue)) {
            
            $join = '';
            
            if (Ue::sessionIsUseFolderPermission()) {
            
                $sessionUserKeyId = Ue::sessionUserKeyId();

                if ($sessionUserKeyId != 1) {

                    $join .= "INNER JOIN ( 
                        SELECT 
                            FM.META_DATA_ID 
                        FROM 
                        (
                            SELECT 
                                FF.FOLDER_ID 
                            FROM FVM_FOLDER FF 
                            CONNECT BY NOCYCLE FF.PARENT_FOLDER_ID = PRIOR FF.FOLDER_ID
                            START WITH FF.FOLDER_ID IN ( 
                                SELECT 
                                    FOLDER_ID 
                                FROM FVM_FOLDER_USER_PERMISSION 
                                WHERE FOLDER_ID IS NOT NULL 
                                    AND USER_ID = $sessionUserKeyId 
                                GROUP BY FOLDER_ID 
                            ) 
                            GROUP BY FF.FOLDER_ID 
                        ) F 
                        INNER JOIN META_DATA_FOLDER_MAP FM ON FM.FOLDER_ID = F.FOLDER_ID 

                        UNION ALL 

                        SELECT 
                            META_DATA_ID  
                        FROM FVM_FOLDER_USER_PERMISSION 
                        WHERE META_DATA_ID IS NOT NULL 
                            AND USER_ID = $sessionUserKeyId 
                    ) FB ON FB.META_DATA_ID = MD.META_DATA_ID"; 
                }
            }

            $data = $this->db->GetAll("
                SELECT 
                    MD.META_DATA_ID, 
                    MD.META_DATA_CODE, 
                    MD.META_DATA_NAME, 
                    MD.CREATED_DATE, 
                    MD.META_TYPE_ID,
                    LOWER(MT.META_TYPE_CODE) AS META_TYPE_CODE, 
                    " . $this->db->IfNull('BP.FIRST_NAME', 'UM.USERNAME') . " AS CREATED_PERSON_NAME, 
                    DI.META_ICON_CODE, 
                    BL.BOOKMARK_URL, 
                    BL.TARGET AS BOOKMARK_TARGET, 
                    RL.REPORT_MODEL_ID, 
                    GL.GROUP_TYPE, 
                    FMAP.FOLDER_ID 
                FROM META_DATA MD 
                    INNER JOIN META_TYPE MT ON MT.META_TYPE_ID = MD.META_TYPE_ID 
                    $join 
                    LEFT JOIN META_DATA_FOLDER_MAP FMAP ON FMAP.META_DATA_ID = MD.META_DATA_ID 
                    LEFT JOIN UM_SYSTEM_USER UM ON UM.USER_ID = MD.CREATED_USER_ID 
                    LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = UM.PERSON_ID 
                    LEFT JOIN META_DATA_ICON DI ON DI.META_ICON_ID = MD.META_ICON_ID 
                    LEFT JOIN META_BOOKMARK_LINKS BL ON BL.META_DATA_ID = MD.META_DATA_ID 
                    LEFT JOIN META_REPORT_LINK RL ON RL.META_DATA_ID = MD.META_DATA_ID 
                    LEFT JOIN META_GROUP_LINK GL ON GL.META_DATA_ID = MD.META_DATA_ID 
                WHERE MD.IS_ACTIVE = 1 
                    AND MD.META_DATA_ID = ".$this->db->Param(0)." 
                ORDER BY 
                    MD.META_DATA_CODE, 
                    MD.META_DATA_NAME 
                ASC", array($searchValue)
            );

            if ($data) {
                return $data;
            }
        }
        
        return null;
    } 

    public function folderSearchModel($searchValue, $condition) {
        
        $join = '';
        
        if (Ue::sessionIsUseFolderPermission()) {
            
            $sessionUserKeyId = Ue::sessionUserKeyId();

            if ($sessionUserKeyId != 1) {
                $join = 'INNER JOIN FVM_FOLDER_USER_PERMISSION FB ON FB.FOLDER_ID = FF.FOLDER_ID AND FB.USER_ID = '.$sessionUserKeyId;
            }
        }
        
        $searchValue = Str::lower($searchValue);
        
        if ($condition == 'like') {
            $where = "(
                LOWER(FF.FOLDER_NAME) LIKE '%$searchValue%' OR 
                LOWER(FF.FOLDER_CODE) LIKE '%$searchValue%'
            )";
        } else {
            $where = "(
                LOWER(FF.FOLDER_NAME) = '$searchValue' OR 
                LOWER(FF.FOLDER_CODE) = '$searchValue'
            )";
        }

        $data = $this->db->GetAll("
            SELECT 
                FF.FOLDER_ID, 
                FF.FOLDER_NAME, 
                FF.PARENT_FOLDER_ID, 
                FF.CREATED_DATE, 
                " . $this->db->IfNull('BP.FIRST_NAME', 'UM.USERNAME') . " AS CREATED_PERSON_NAME 
            FROM FVM_FOLDER FF 
                $join 
                LEFT JOIN UM_SYSTEM_USER UM ON UM.USER_ID = FF.CREATED_USER_ID 
                LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = UM.PERSON_ID 
            WHERE FF.FOLDER_TYPE = 'STATIC' 
                AND FF.IS_ACTIVE = 1 
                AND $where 
            ORDER BY FF.FOLDER_NAME ASC");

        return $data;
    }

    public static function getMetaFolderPath($metaDataId) {
        global $db;
        
        $data = $db->GetAll("SELECT FOLDER_ID FROM META_DATA_FOLDER_MAP WHERE META_DATA_ID = ".$db->Param(0), array($metaDataId));
        $path = '';
        
        if ($data) {
            foreach ($data as $row) {
                $path .= '<i class="fa fa-folder-open text-orange-400"></i> ' . self::getResultPath($row['FOLDER_ID'], '0', $row['FOLDER_ID']) . '<br />';
            }
        }
        return $path;
    }

    public static function getResultPath($this_fol_id, $flarn, $keep_fol_id) {
        
        global $db;
        
        $html = '';
        $idPh = $db->Param(0);
        
        $val = $db->GetRow("SELECT FOLDER_ID, PARENT_FOLDER_ID, FOLDER_NAME FROM FVM_FOLDER WHERE FOLDER_ID = $idPh", array($this_fol_id));

        if ($val) {

            $cat_id_array[$flarn] = $val['FOLDER_ID'];
            $cat_parent_id_array[$flarn] = $val['PARENT_FOLDER_ID'];
            $cat_name_array[$flarn] = $val['FOLDER_NAME'];

            if ($cat_id_array[$flarn] > 0) {

                $next = $flarn + 1;
                $html .= self::getResultPath($cat_parent_id_array[$flarn], $next, $keep_fol_id);

                if ($keep_fol_id === $cat_id_array[$flarn]) {
                    $html .= '<a href="javascript:;" onclick="childRecordView(\'' . $cat_id_array[$flarn] . '\', \'folder\');">' . $cat_name_array[$flarn] . '</a> / ';
                } else {
                    $html .= '<a href="javascript:;" onclick="childRecordView(\'' . $cat_id_array[$flarn] . '\', \'folder\');">' . $cat_name_array[$flarn] . '</a> / ';
                }
            }
        }
        
        return $html;
    }

    public function googleMapCoordinateListModel($metaDataId, $rowId = '') {
        $where = ($rowId != '' ? ' AND ID IN (' . $rowId .')' : '');
        $mapArray = array();
        
        $result = $this->db->GetAll("
            SELECT 
                MGML.ID AS ROW_ID, 
                MGML.LIST_META_DATA_ID AS META_DATA_ID, 
                MD.META_DATA_NAME,
                MGML.DISPLAY_COLOR,
                MGML.ACTION_META_DATA_ID,
                MGML.ACTION_META_TYPE_ID,
                AMD.META_TYPE_ID,
                MGML.ICON_NAME,
                MGML.SERVICE_URL,
                IS_DYNAMIC,
                MGML.SERVICE_NAME
            FROM META_GOOGLE_MAP_LINK MGML 
                LEFT JOIN META_DATA MD ON MGML.LIST_META_DATA_ID = MD.META_DATA_ID
                LEFT JOIN META_DATA AMD ON MGML.ACTION_META_DATA_ID = AMD.META_DATA_ID
            WHERE MGML.META_DATA_ID = $metaDataId " . $where . "
            ORDER BY MGML.ORDER_NUM ASC");
        
        $array = $allMiddleCoordinate = array();
        $control = $controlBegin = $controlEnd = '';
        
        if ($result) {
            $i = 0;
            $fieldType = '';
            
            foreach ($result as $k => $val) {
                
                $_POST['metaDataId'] = $val['META_DATA_ID'];
                $array[$k]['META_GOOGLE_MAP_LINK_ID'] = $val['ROW_ID'];
                $array[$k]['META_DATA_ID'] = $val['META_DATA_ID'];
                $array[$k]['META_TYPE_ID'] = $val['META_TYPE_ID'];
                $array[$k]['ACTION_META_DATA_ID'] = $val['ACTION_META_DATA_ID'];
                $array[$k]['ACTION_META_TYPE_ID'] = $val['ACTION_META_TYPE_ID'];
                $array[$k]['META_DATA_NAME'] = $val['META_DATA_NAME'];
                $array[$k]['COLOR'] = $val['DISPLAY_COLOR'];
                $array[$k]['ICON'] = $val['ICON_NAME'];
                $array[$k]['DRAW_TYPE'] = 'COORDINATE';
                $array[$k]['DRAW_FIELD'] = '';
                $array[$k]['IS_DYNAMIC'] = $val['IS_DYNAMIC'];

                $gMap = array('');
                $gMapRegion = array('');
                if ($val['SERVICE_URL'] != null) {
                    $array[$k]['META_DATA_ID'] = $val['ROW_ID'];
                    $array[$k]['DRAW_TYPE'] = 'SERIVCE';
                    $array[$k]['DRAW_FIELD'] = '';
                    $gMap = self::carArray($val['SERVICE_URL']);    /*Service-с lat, lng агуулсан array ирэх ёстой*/
                    $control .= '<label><input type="checkbox" checked name="mapMarker[]" data-lat="0" data-lng="0" onclick="googleMapShowHideDynamicMarker(\'' . $val['ROW_ID'] . '\', this);"> ' . $val['SERVICE_NAME'] . '</label>';
                } else {
                    $coordinate = (new Mdobject())->dataViewDataGrid(false, false);
                    $endCount = count($coordinate['rows']) - 1;
                    $ii = 0;
                    foreach ($coordinate['rows'] as $key => $row) {
                        if (empty($key)) {
                            foreach ($row as $j => $jrow) {
                                $fieldType = self::checkFieldTtype($val['META_DATA_ID'], $j);
                                if ($fieldType == 'region') {
                                    $array[$k]['DRAW_TYPE'] = 'REGION';
                                    $array[$k]['DRAW_FIELD'] = $j;
                                } elseif ($fieldType == 'coordinate') {
                                    $array[$k]['DRAW_FIELD'] = $j;
                                    $array[$k]['DRAW_TYPE'] = 'MARKER';
                                }
                            }
                        }

                        if ($array[$k]['DRAW_TYPE'] == 'MARKER') {
                            $coorinate = trim($row[$array[$k]['DRAW_FIELD']], '|');
                            if (strlen($coorinate) > 0) {
                                $coordinate = explode("|", $coorinate);
                                $gMap[$ii]['META_VALUE_ID'] = $row['id'];
                                $gMap[$ii]['lat'] = floatval($coordinate['1']);
                                $gMap[$ii]['lng'] = floatval($coordinate['0']);
                                $gMap[$ii]['DRAW_TYPE'] = 'marker';
                                $gMap[$ii]['COLOR'] = false;
                                $gMap[$ii]['COORDINATES'] = false;
                                $gMap[$ii]['CENTER'] = false;
                                $gMap[$ii]['RADIUS'] = false;
                                $ii++;
                            }
                        } elseif ($array[$k]['DRAW_TYPE'] == 'REGION') {
                            $gMapRegionData = json_decode(html_entity_decode($row[$array[$k]['DRAW_FIELD']]), true);

                            if (isset($row['id']) and isset($gMapRegionData['drawType'])) {
                                $gMap[$ii]['META_VALUE_ID'] = $row['id'];
                                $gMap[$ii]['lat'] = false;
                                $gMap[$ii]['lng'] = false;
                                $gMap[$ii]['ACTION'] = false;
                                $gMap[$ii]['DRAW_TYPE'] = $gMapRegionData['drawType'];
                                $gMap[$ii]['COLOR'] = trim($gMapRegionData['color'], "#");
                                $gMap[$ii]['COORDINATES'] = false;
                                $gMap[$ii]['CENTER'] = false;
                                $gMap[$ii]['RADIUS'] = false;
                                if ($gMapRegionData['drawType'] == 'circle') {
                                    $gMap[$ii]['CENTER'] = $gMapRegionData['center'];
                                    $gMap[$ii]['RADIUS'] = $gMapRegionData['radius'];
                                } else if($gMapRegionData['drawType'] == 'polygon') {
                                    $gMap[$ii]['COORDINATES'] = (isset($gMapRegionData['coordinates']) ? $gMapRegionData['coordinates'] : false);
                                    $gMap[$ii]['CENTER'] = self::centerCoordinate($gMapRegionData['coordinates']);
                                    $gMap[$ii]['RADIUS'] = (isset($gMapRegionData['radius']) ? $gMapRegionData['radius'] : false);
                                } else if($gMapRegionData['drawType'] == 'polyline') {
                                    $gMap[$ii]['COORDINATES'] = (isset($gMapRegionData['coordinates']) ? $gMapRegionData['coordinates'] : false);
                                    $gMap[$ii]['CENTER'] = self::centerCoordinate($gMapRegionData['coordinates']);
                                    $gMap[$ii]['RADIUS'] = (isset($gMapRegionData['radius']) ? $gMapRegionData['radius'] : false);
                                } else {
                                    $gMap[$ii]['COORDINATES'] = (isset($gMapRegionData['coordinates']) ? $gMapRegionData['coordinates'] : false);
                                    $gMap[$ii]['CENTER'] = self::centerCoordinate($gMapRegionData['coordinates']);
                                    $gMap[$ii]['RADIUS'] = (isset($gMapRegionData['radius']) ? $gMapRegionData['radius'] : false);
                                }
                                $ii++;
                            }
                        }

                        if ($endCount == $key) {
                            foreach ($row as $j => $jrow) {
                                $fieldType = self::checkFieldTtype($val['META_DATA_ID'], $j);
                                if ($fieldType == 'region') {
                                    $control .= '<label><input type="checkbox" checked name="mapMarker[]" data-lat="" data-lng="" onclick="googleMapShowHideStaticMarker(\'' . $val['META_DATA_ID'] . '\', this);"> ' . $val['META_DATA_NAME'] . '</label>';
                                } elseif ($fieldType == 'coordinate') {
                                    $center = self::centerCoordinate($gMap);
                                    $control .= '<label><input type="checkbox" checked name="mapMarker[]" data-lat="' . $center['lat'] . '" data-lng="' . $center['lng'] . '" onclick="googleMapShowHideStaticMarker(\'' . $val['META_DATA_ID'] . '\', this);"> ' . $val['META_DATA_NAME'] . '</label>';
                                }
                            }
                        }
                    }
                }
                $array[$k]['GMAPDATA'] = $gMap;
                if ($array[$k]['DRAW_TYPE'] == 'MARKER') {
                    $array[$k]['CENTER'] = self::centerCoordinate($gMap);
                    $allMiddleCoordinate[$k] = $array[$k]['CENTER'];
                }
            }
        }

        $allMiddleLatLng = self::centerCoordinate($allMiddleCoordinate);

        $controlHtml = '<div class="panel-group accordion">';
        $controlHtml .= '<div class="panel panel-default">';
        $controlHtml .= '<div class="panel-heading">';
        $controlHtml .= '<h4 class="panel-title">';
        $controlHtml .= '<a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion1" href="#collapse_1_1">Ангилал </a>';
        $controlHtml .= '</h4>';
        $controlHtml .= '</div>';
        $controlHtml .= '<div id="collapse__1_1" class="panel-collapse in">';
        $controlHtml .= '<div class="panel-body">';
        $controlHtml .= '<div class="checkbox-list">';
        $controlHtml .= '<label><input type="checkbox" checked name="mapMarker[]" data-lat="' . $allMiddleLatLng['lat'] . '" data-lng="' . $allMiddleLatLng['lng'] . '" onclick="googleMapShowHideStaticMarker(\'all\', this); googleMapShowHideDynamicMarker(\'all\', this)"> Бүгд</label>';
        $controlHtml .= $control;
        $controlHtml .= '</div>';
        $controlHtml .= '</div>';
        $controlHtml .= '</div>';
        $controlHtml .= '</div>';
        $controlHtml .= '</div>';
        $mapArray['MAP'] = $array;
        $mapArray['CONTROL'] = $controlHtml;
        
        return $mapArray;
    }

    function carArray($url, $carId = '') {
        $dataList = json_decode(json_encode(Xml::loadFile($url)), 1);
        $car = array();
        foreach ($dataList['vehicle'] as $k => $row) {
            $car[$k]['META_VALUE_ID'] = $row['id'];
            $car[$k]['META_VALUE_NAME'] = $row['name'];
            $car[$k]['lat'] = floatval($row['lat']);
            $car[$k]['lng'] = floatval($row['lng']);
            $car[$k]['ACTION'] = '';
        }
        return $car;
    }

    public function centerCoordinate($coordinates) {
        $itemMinlat = false;
        $itemMinlng = false;
        $itemMaxlat = false;
        $itemMaxlng = false;

        if (count($coordinates) > 1) {
            foreach ($coordinates as $key => $val) {
                if (!isset($val['lat']) and !isset($val['lng'])) {
                    return false;
                }
                if ($itemMinlat === false) {
                    $itemMinlat = $val['lat'];
                } else {
                    $itemMinlat = ($val['lat'] < $itemMinlat) ? $val['lat'] : $itemMinlat;
                }
                if ($itemMaxlat === false) {
                    $itemMaxlat = $val['lat'];
                } else {
                    $itemMaxlat = ($val['lat'] > $itemMaxlat) ? $val['lat'] : $itemMaxlat;
                }
                if ($itemMinlng === false) {
                    $itemMinlng = $val['lng'];
                } else {
                    $itemMinlng = ($val['lng'] < $itemMinlng) ? $val['lng'] : $itemMinlng;
                }
                if ($itemMaxlng === false) {
                    $itemMaxlng = $val['lng'];
                } else {
                    $itemMaxlng = ($val['lng'] > $itemMaxlng) ? $val['lng'] : $itemMaxlng;
                }
            }
            $itemLat = $itemMaxlat - (($itemMaxlat - $itemMinlat) / 2);
            $itemLong = $itemMaxlng - (($itemMaxlng - $itemMinlng) / 2);
            return array('lat' => $itemLat, 'lng' => $itemLong);
        }
        return false;
    }

    public function checkFieldTtype($metaDataId, $paramName) {
        $data = $this->db->GetOne("
            SELECT 
                DATA_TYPE AS DATA_TYPE_CODE 
            FROM META_GROUP_CONFIG 
            WHERE MAIN_META_DATA_ID = $metaDataId 
                AND LOWER(PARAM_NAME) = LOWER('$paramName')");
        return $data;
    }

    public function getGroupProcessDtlModel($metaDataId) {
        $data = $this->db->GetAll("
            SELECT 
                MD.META_DATA_ID, 
                MD.META_DATA_CODE,  
                MD.META_DATA_NAME, 
                PD.IS_MULTI, 
                PD.PROCESS_NAME, 
                PD.ICON_NAME, 
                PD.ORDER_NUM, 
                PD.IS_CONFIRM, 
                PD.IS_SHOW_POPUP, 
                PD.BUTTON_STYLE, 
                PD.BATCH_NUMBER, 
                PD.CRITERIA,
                PD.ADVANCED_CRITERIA,
                PD.PASSWORD_PATH,
                PD.IS_WORKFLOW, 
                PD.IS_SHOW_BASKET, 
                PD.IS_MAIN, 
                PD.POST_PARAM, 
                PD.GET_PARAM,
                PD.IS_AUTO_MAP,
                PD.AUTO_MAP_SRC,
                PD.AUTO_MAP_ON_DELETE,
                PD.AUTO_MAP_ON_UPDATE,
                PD.AUTO_MAP_SRC_PATH,
                PD.AUTO_MAP_SRC_TABLE_NAME,
                PD.AUTO_MAP_DELETE_PROCESS_ID, 
                PD.AUTO_MAP_DATAVIEW_ID, 
                PD.AUTO_MAP_NAME_PATTERN, 
                PD.AUTO_MAP_TRG_NAME_PATTERN, 
                PD.CONFIRM_MESSAGE, 
                PD.IS_BP_OPEN, 
                PD.IS_BP_OPEN_DEFAULT, 
                PD.SHOW_POSITION, 
                PD.ICON_COLOR, 
                PD.IS_ROW_UPDATE, 
                PD.IS_SHOW_ROWSELECT, 
                PD.IS_PROCESS_TOOLBAR, 
                PD.IS_USE_PROCESS_TOOLBAR, 
                PD.IS_CONTEXT_MENU, 
                PD.IS_RUN_LOOP  
            FROM META_DM_PROCESS_DTL PD 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = PD.PROCESS_META_DATA_ID 
            WHERE PD.MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                AND MD.IS_ACTIVE = 1 
            ORDER BY PD.ORDER_NUM ASC", array($metaDataId));

        return $data;
    }

    public function getGroupBatchDtlModel($metaDataId) {
        $data = $this->db->GetAll("
            SELECT 
                BATCH_NUMBER, 
                BATCH_NAME, 
                ICON_NAME, 
                IS_DROPDOWN,
                IS_SHOW_POPUP,
                BUTTON_STYLE 
            FROM META_DM_PROCESS_BATCH  
            WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)."  
            ORDER BY BATCH_NUMBER ASC", array($metaDataId));

        return $data;
    }

// <editor-fold defaultstate="collapsed" desc="REPORT_TEMPLATE">
    public function getMetaDataByGroupModel($metaGroupId) {
        $data = $this->db->GetAll("
            SELECT 
                ID,
                PARAM_NAME, 
                MAIN_META_DATA_ID,
                PARENT_ID,
                LABEL_NAME,
                FIELD_PATH,
                COLUMN_WIDTH,
                TEXT_WEIGHT,
                TEXT_COLOR,
                RECORD_TYPE,
                IS_SHOW 
            FROM META_GROUP_CONFIG 
            WHERE MAIN_META_DATA_ID = ".$this->db->Param(0), 
            array($metaGroupId)
        );
        
        return $data;
    }

    public function getOnlyMetaDataByGroupModel($metaGroupId) {
        $data = $this->db->GetAll("
            SELECT 
                ID,
                PARENT_ID,
                MAIN_META_DATA_ID, 
                LABEL_NAME, 
                FIELD_PATH,
                COLUMN_WIDTH,
                TEXT_WEIGHT,
                TEXT_COLOR,
                RECORD_TYPE,
                IS_SHOW,
                IS_CRITERIA, 
                IS_SELECT 
            FROM META_GROUP_CONFIG 
            WHERE PARENT_ID IS NULL 
                AND DATA_TYPE <> 'group' 
                AND (IS_SELECT = 1 OR IS_CRITERIA = 1) 
                AND MAIN_META_DATA_ID = ".$this->db->Param(0)." 
            ORDER BY DISPLAY_ORDER ASC", array($metaGroupId)); 

        return $data;
    }

    public function getReplacedMetaDataByGroupModel($metaGroupId, $fieldpath) {
        $data = $this->db->GetAll("
            SELECT 
                ID,
                PARENT_ID, 
                MAIN_META_DATA_ID, 
                LABEL_NAME, 
                FIELD_PATH, 
                COLUMN_WIDTH,
                TEXT_WEIGHT,
                TEXT_COLOR,
                RECORD_TYPE
            FROM META_GROUP_CONFIG 
            WHERE MAIN_META_DATA_ID = $metaGroupId 
                AND PARENT_ID = (
                    SELECT 
                        ID 
                    FROM META_GROUP_CONFIG 
                    WHERE MAIN_META_DATA_ID = $metaGroupId 
                        AND LOWER(FIELD_PATH) = '".strtolower($fieldpath)."'
                )");

        return $data;
    }

    public function getMetaDataByParentModel($parentId) {
        $data = $this->db->GetAll("
            SELECT 
                ID,
                PARENT_ID,
                MAIN_META_DATA_ID,
                PARAM_NAME, 
                LABEL_NAME, 
                FIELD_PATH,
                COLUMN_WIDTH,
                TEXT_WEIGHT,
                TEXT_COLOR,
                RECORD_TYPE,
                IS_SHOW
            FROM META_GROUP_CONFIG 
            WHERE PARENT_ID = ".$this->db->Param(0), 
            array($parentId)
        );
        
        return $data;
    }

    public function isParentMetaGroupModel($parentid, $groupid) {
        $data = $this->db->GetRow("
            SELECT 
                ID
            FROM META_GROUP_CONFIG 
            WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                AND PARENT_ID = ".$this->db->Param(1), 
            array($groupid, $parentid)
        );

        if ($data) {
            return true;
        }
        return false;
    }

    public function getReportTemplateModel($metaDataId) {
        $row = $this->db->GetRow("
            SELECT 
                TL.ID,
                TL.META_DATA_ID,
                TL.DATA_MODEL_ID, 
                MD.META_DATA_CODE AS DATA_MODEL_CODE, 
                MD.META_DATA_NAME AS DATA_MODEL_NAME, 
                TL.HTML_CONTENT,
                TL.HTML_FILE_PATH,
                TL.DIRECTORY_ID, 
                TL.GET_MODE, 
                TL.PAGING_CONFIG, 
                TL.PAGE_MARGIN_TOP, 
                TL.PAGE_MARGIN_LEFT, 
                TL.PAGE_MARGIN_RIGHT, 
                TL.PAGE_MARGIN_BOTTOM, 
                TL.ARCHIVE_WFM_STATUS_CODE, 
                TL.HTML_HEADER_CONTENT,
                TL.HTML_FOOTER_CONTENT, 
                TL.IS_REPORT,
                TL.IS_ARCHIVE,
                TL.IS_AUTO_ARCHIVE, 
                TL.IS_EMAIL, 
                TL.IS_TABLE_LAYOUT_FIXED, 
                TL.IS_IGNORE_PRINT, 
                TL.IS_IGNORE_EXCEL, 
                TL.IS_IGNORE_PDF, 
                TL.IS_IGNORE_WORD,
                TL.IS_BLOCKCHAIN_VERIFY, 
                TL.CONFIG_STR
            FROM META_REPORT_TEMPLATE_LINK TL 
                LEFT JOIN META_DATA MD ON MD.META_DATA_ID = TL.DATA_MODEL_ID 
            WHERE TL.META_DATA_ID = ".$this->db->Param(0), 
            array($metaDataId)
        );

        return $row;
    }
    
    public function getHierarchyPathsByDvModel($metaDataId, $depth = 0, $parentId = null) {
        
        $html = '';
        
        if ($depth == 0) {
            $where = 'AND PARENT_ID IS NULL';
        } else {
            $where = 'AND PARENT_ID = '.$parentId;
        }
        
        $data = $this->db->GetAll("
            SELECT 
                ID, 
                ".$this->db->IfNull('LABEL_NAME', 'PARAM_NAME')." AS LABEL_NAME, 
                LOWER(FIELD_PATH) AS FIELD_PATH, 
                RECORD_TYPE 
            FROM META_GROUP_CONFIG 
            WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                AND IS_SELECT = 1 
                $where 
            ORDER BY DISPLAY_ORDER ASC", 
            array($metaDataId));
        
        if ($data) {
            
            if ($depth > 0) {
                $html .= '<ul>';
            }
            
            foreach ($data as $row) {
                
                $li = '<div class="metaData tag-meta" data-metaData="#'.$row['FIELD_PATH'].'#" draggable="true" tabindex="0">'.$this->lang->line($row['LABEL_NAME']).'</div>';
                
                $html .= '<li>';
                    
                if ($row['RECORD_TYPE']) {
                    $html .= '<input type="checkbox" class="toggle" id="'.$row['FIELD_PATH'].'"/>';
                    $html .= '<label for="'.$row['FIELD_PATH'].'" title="'.$row['FIELD_PATH'].'" class="tree_label">' . $li . '</label>';
                    $html .= self::getHierarchyPathsByDvModel($metaDataId, $depth + 1, $row['ID']);
                } else {
                    $html .= '<span class="tree_label" title="'.$row['FIELD_PATH'].'">' . $li . '</span>';
                }
                            
                $html .= '</li>';
            }
            
            if ($depth > 0) {
                $html .= '</ul>';
            }
        }
        
        return $html;
    }

    public function getReportTemplateList() {
        $data = $this->db->GetAll("
            SELECT 
                TEMP.ID,
                TEMP.META_DATA_ID,
                TEMP.DATA_MODEL_ID, 
                TEMP.HTML_CONTENT, 
                TEMP.HTML_FILE_PATH, 
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME
            FROM META_REPORT_TEMPLATE_LINK TEMP
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = TEMP.META_DATA_ID");

        return $data;
    }

    public function getReportTemplateOnlyMetaList() {
        $data = $this->db->GetAll("
            SELECT 
                MD.META_DATA_ID,
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME
            FROM META_REPORT_TEMPLATE_LINK TEMP
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = TEMP.META_DATA_ID");

        return $data;
    }

// </editor-fold>

    public function selectedRowConvertArray($metaDataId, $primaryField, $params = array()) {

        if (Input::postCheck('selectedRowData') && is_array($_POST['selectedRowData'])) {

            $selectedRowData = $_POST['selectedRowData'];

            if (array_key_exists(0, $selectedRowData)) {
                return array('items' => $selectedRowData);
            }

            return array('items' => array($selectedRowData));
        }

        if (Input::postCheck('selectedRows')) {

            $selectedData = $_POST['selectedRows'];

            if (count($selectedData) == 1 && $selectedData[0]['value'] == '') {
                return array('items' => array());
            }
            
            $ids = array();
            
            foreach ($selectedData as $k => $v) {
                if ($v['value'] != '') {
                    $ids[] = Input::param($v['value']);
                }
            }
            
            if ($ids) {
                
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

                    return array('items' => $data['result']);
                }
            }
        }

        return array('items' => array());
    }

    public function selectedRowCustomConvertArray($metaDataId) {

        if (Input::postCheck('selectedRows')) {

            $selectedData = $_POST['selectedRows'];

            if (count($selectedData) == 1 && empty($selectedData[0]['value'])) {
                return array('items' => array());
            }

            $paramCriteria = array();
            $isSearch = false;

            foreach ($selectedData as $k => $v) {
                if (!empty($v['value'])) {
                    $criteriavalue = explode(',', $v['value']);
                    foreach ($criteriavalue as $ckey => $cv) {
                        if (!empty($cv)) {
                            $paramCriteria['id'][$ckey] = array(
                                'operator' => '=',
                                'operand' => $cv
                            );
                            $isSearch = true;
                        }
                    }
                }
            }

            if ($isSearch) {

                $param = array(
                    'systemMetaGroupId' => $metaDataId,
                    'showQuery' => 0, 
                    'ignorePermission' => 1,  
                    'criteria' => $paramCriteria
                );

                $data = $this->ws->runArrayResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

                if ($data['status'] == 'success' && isset($data['result'])) {

                    unset($data['result']['paging']);
                    unset($data['result']['aggregatecolumns']);

                    return array('items' => $data['result']);
                }   
            }
        }

        return array('items' => array());
    }

    public function getStatementLinkModel($metaDataId) {
        $row = $this->db->GetRow("
            SELECT 
                MSL.ID,
                MSL.META_DATA_ID,
                MSL.REPORT_NAME, 
                MSL.REPORT_TYPE,
                MSL.DATA_VIEW_ID,
                MDV.META_DATA_CODE AS DATA_VIEW_CODE,
                MDV.META_DATA_NAME AS DATA_VIEW_NAME,
                MSL.REPORT_HEADER,
                MSL.PAGE_HEADER,
                MSL.REPORT_DETAIL,
                MSL.REPORT_DETAIL_FILE_PATH,
                MSL.PAGE_FOOTER,
                MSL.REPORT_FOOTER,
                MSL.PAGE_SIZE,
                MSL.PAGE_ORIENTATION,
                MSL.PAGE_MARGIN_TOP,
                MSL.PAGE_MARGIN_LEFT,
                MSL.PAGE_MARGIN_RIGHT,
                MSL.PAGE_MARGIN_BOTTOM,
                MSL.PAGE_WIDTH,
                MSL.PAGE_HEIGHT,
                MSL.FONT_FAMILY,
                MSL.RENDER_TYPE, 
                MSL.IS_ARCHIVE,
                MSL.ROW_EXPRESSION,
                MSL.GLOBAL_EXPRESSION,
                MSL.SUPER_GLOBAL_EXPRESSION,
                MDG.META_DATA_CODE AS GROUP_DATA_VIEW_CODE, 
                MDG.META_DATA_NAME AS GROUP_DATA_VIEW_NAME,
                MSL.GROUP_DATA_VIEW_ID, 
                MSL.PROCESS_META_DATA_ID, 
                MDC.META_DATA_CODE AS PROCESS_CODE, 
                MDC.META_DATA_NAME AS PROCESS_NAME,
                MSL.IS_NOT_PAGE_BREAK, 
                MSL.IS_HDR_REPEAT_PAGE, 
                MSL.IS_BLANK, 
                MSL.IS_SHOW_DV_BTN, 
                MSL.IS_USE_SELF_DV, 
                MSL.IS_AUTO_FILTER, 
                MSL.IS_GROUP_MERGE, 
                MSL.IS_TIMETABLE, 
                MSL.IS_EXPORT_NO_FOOTER, 
                MSL.CALC_ORDER_NUM  
            FROM META_STATEMENT_LINK MSL
                LEFT JOIN META_DATA MDV ON MDV.META_DATA_ID = MSL.DATA_VIEW_ID 
                LEFT JOIN META_DATA MDG ON MDG.META_DATA_ID = MSL.GROUP_DATA_VIEW_ID  
                LEFT JOIN META_DATA MDC ON MDC.META_DATA_ID = MSL.PROCESS_META_DATA_ID  
            WHERE MSL.META_DATA_ID = ".$this->db->Param(0), array($metaDataId)); 

        return $row;
    }

    public function getPackageLinkModel($metaDataId) {
        $row = $this->db->GetRow("
            SELECT
                T3.META_DATA_CODE, 
                T3.META_DATA_NAME,
                T0.RENDER_TYPE,
                T0.IS_IGNORE_MAIN_TITLE,
                T0.TAB_BACKGROUND_COLOR,
                T0.MOBILE_THEME,
                T0.IS_CHECK_PERMISSION,
                T0.SPLIT_COLUMN,
                T0.PACKAGE_CLASS,
                T0.IS_IGNORE_PACKAGE_TITLE,
                T0.IS_FILTER_BTN_SHOW,
                T0.COUNT_META_DATA_ID,
                T2.META_DATA_NAME AS COUNT_META_NAME, 
                T2.META_DATA_CODE AS COUNT_META_CODE, 
                T0.IS_REFRESH, 
                T0.DEFAULT_META_ID,
                T1.META_DATA_NAME AS DEFAULT_META_NAME, 
                T1.META_DATA_CODE AS DEFAULT_META_CODE, 
                T1.META_TYPE_ID AS DEFAULT_META_TYPE_ID 
            FROM META_PACKAGE_LINK T0 
                INNER JOIN META_DATA T3 ON T0.META_DATA_ID = T3.META_DATA_ID
                LEFT JOIN META_DATA T1 ON T0.DEFAULT_META_ID = T1.META_DATA_ID 
                LEFT JOIN META_DATA T2 ON T0.COUNT_META_DATA_ID = T2.META_DATA_ID 
            WHERE T0.META_DATA_ID = ".$this->db->Param(0), array($metaDataId));
        
        return $row;
    }

    public function getLookupCloneFieldModel($processMetaDataId, $paramRealPath) {

        $cache = phpFastCache();

        $paramRealPath = strtolower($paramRealPath);
        $result = $cache->get('bpLookupCloneField_'.$processMetaDataId.'_'.$paramRealPath);

        if (!is_array($result)) {
            $result = array();

            $data = $this->db->GetAll("
                SELECT  
                    LOWER(PAL.VALUE_CRITERIA) AS VALUE_CRITERIA, 
                    MD.META_DATA_CODE  
                FROM META_PROCESS_PARAM_ATTR_LINK PAL 
                    INNER JOIN META_DATA MD ON MD.META_DATA_ID = PAL.PARAM_META_DATA_ID 
                WHERE PAL.PROCESS_META_DATA_ID = $processMetaDataId 
                    AND LOWER(PAL.VALUE_CRITERIA) LIKE '%[$paramRealPath][%'");

            if ($data) {
                foreach ($data as $k => $row) {
                    preg_match_all('/\[(.*?)\]/', $row['VALUE_CRITERIA'], $paramExp);
                    $result[$k]['FIELD_NAME'] = $paramExp[1][1];
                    $result[$k]['META_DATA_CODE'] = $row['META_DATA_CODE'];
                }
            }

            $cache->set('bpLookupCloneField_'.$processMetaDataId.'_'.$paramRealPath, $result, Mdwebservice::$expressionCacheTime);
        }

        return $result;
    }

    public function isExistsMetaLink($tableName, $columnName, $metaDataId, $pkColumn = 'ID') {
        $row = $this->db->GetOne("SELECT $pkColumn FROM $tableName WHERE $columnName = ".$this->db->Param(0), array($metaDataId));
        if ($row) {
            return $row;
        }
        return false;
    }

    public function metaDataAutoCompleteModel($type, $q, $params = null) {

        $q = Str::lower($q);
        $where = '';

        if ($params) {
            parse_str($params, $params);

            if (count($params) > 0) {

                foreach ($params as $k => $v) {
                    $k = strtolower($k);
                    if ($k == 'metatypeid' && !empty($v)) {
                        if (strpos($v, '|') === false) {
                            $where .= " AND MD.META_TYPE_ID = '" . Input::param($v) . "'";
                        } else {
                            $where .= " AND MD.META_TYPE_ID IN (" . implode(",", explode("|", $v)) . ")";
                        }
                    } elseif ($k == 'grouptype' && !empty($v)) {
                        $where .= "AND (GL.GROUP_TYPE IS NULL OR (MD.META_TYPE_ID = 200101010000016 AND LOWER(GL.GROUP_TYPE) IN ('" . implode("','", explode("|", Input::param(Str::lower($v)))) . "')))";
                    }
                }
            }
        }

        if ($type == 'code') {

            $sql = "
                SELECT 
                    MD.META_DATA_ID, 
                    MD.META_DATA_CODE, 
                    MD.META_DATA_NAME 
                FROM META_DATA MD 
                    LEFT JOIN META_GROUP_LINK GL ON GL.META_DATA_ID = MD.META_DATA_ID 
                WHERE MD.IS_ACTIVE = 1 
                    $where  
                    AND LOWER(MD.META_DATA_CODE) LIKE '$q%'
                ORDER BY MD.META_DATA_CODE ASC"; 

        } elseif ($type == 'name') {

            $sql = "
                SELECT 
                    MD.META_DATA_ID, 
                    MD.META_DATA_CODE, 
                    MD.META_DATA_NAME 
                FROM META_DATA MD 
                    LEFT JOIN META_GROUP_LINK GL ON GL.META_DATA_ID = MD.META_DATA_ID 
                WHERE MD.IS_ACTIVE = 1 
                    $where 
                    AND LOWER(MD.META_DATA_NAME) LIKE '$q%'
                ORDER BY MD.META_DATA_CODE ASC";

        } elseif ($type == 'codename') {

            $sql = "
                SELECT 
                    MD.META_DATA_ID, 
                    MD.META_DATA_CODE, 
                    MD.META_DATA_NAME 
                FROM META_DATA MD 
                    LEFT JOIN META_GROUP_LINK GL ON GL.META_DATA_ID = MD.META_DATA_ID 
                WHERE MD.IS_ACTIVE = 1 
                    $where 
                    AND (LOWER(MD.META_DATA_CODE) LIKE '$q%' OR LOWER(MD.META_DATA_NAME) LIKE '$q%')
                ORDER BY MD.META_DATA_CODE ASC";

        }

        $sqlResult = $this->db->SelectLimit($sql, 30, -1);

        if ($sqlResult && isset($sqlResult->_array)) {

            $data = array();
            $rowsData = $sqlResult->_array;

            foreach ($rowsData as $row) {
                $name = $row['META_DATA_ID'].'|'.html_entity_decode($row['META_DATA_CODE'], ENT_QUOTES, 'UTF-8').'|'.html_entity_decode($row['META_DATA_NAME'], ENT_QUOTES, 'UTF-8');
                array_push($data, $name);
            }

            return $data;
        }

        return array();
    }

    public function metaDataAutoCompleteByIdModel($code, $field) {

        $row = $this->db->GetRow("
            SELECT 
                META_DATA_ID, 
                META_DATA_CODE, 
                META_DATA_NAME 
            FROM META_DATA 
            WHERE LOWER($field) = ".$this->db->Param(0), 
            array($code)
        ); 

        return $row;
    }

    public function getPfMetaStatusListModel() {

        $data = $this->db->GetAll("
            SELECT 
                ID, 
                NAME
            FROM META_DATA_STATUS 
            ORDER BY ID ASC"); 

        return $data;
    }
    
    public function getGroupParamsModel($dvMetaDataId, $rowId = null) {
        
        $bindParams = array($dvMetaDataId);
        $idPh = $this->db->Param(0);
        
        if ($rowId) { 
            array_push($bindParams, $rowId); 
            $where = 'GC.PARENT_ID = '.$this->db->Param(1);
        } else {
            $where = 'GC.PARENT_ID IS NULL';
        }
        
        if (Input::isEmpty('query') == false) {
            
            $params = self::queryToParamsModel(Input::postNonTags('query'), $rowId);

            if (isset($params['queryToParamStatus'])) {
                $params['status'] = $params['queryToParamStatus'];
                echo json_encode($params); exit; 
            }
        }
        
        $data = $this->db->GetAll("
            SELECT 
                GC.ID, 
                GC.PARENT_ID, 
                LOWER(GC.DATA_TYPE) AS DATA_TYPE, 
                GC.FIELD_PATH,  
                GC.COLUMN_NAME, 
                GC.IS_SHOW, 
                GC.IS_SELECT, 
                GC.IS_REQUIRED, 
                GC.DEFAULT_VALUE, 
                GC.LOOKUP_META_DATA_ID, 
                MDL.META_DATA_CODE AS LOOKUP_META_DATA_CODE, 
                MDL.META_DATA_NAME AS LOOKUP_META_DATA_NAME, 
                GC.LOOKUP_TYPE, 
                GC.DISPLAY_FIELD, 
                GC.VALUE_FIELD, 
                GC.CHOOSE_TYPE, 
                GC.RECORD_TYPE, 
                GC.TAB_NAME, 
                GC.LABEL_NAME,  
                GC.PARAM_NAME, 
                GC.BODY_ALIGN, 
                GC.COLUMN_WIDTH, 
                GC.HEADER_ALIGN, 
                GC.TEXT_COLOR, 
                GC.TEXT_TRANSFORM, 
                GC.TEXT_WEIGHT, 
                GC.BG_COLOR, 
                GC.FONT_SIZE, 
                GC.COLUMN_AGGREGATE, 
                GC.INPUT_NAME, 
                GC.IS_RENDER_SHOW,
                GC.IS_SHOW_BASKET, 
                GC.IS_CRITERIA, 
                GC.AGGREGATE_ALIAS_PATH 
            FROM META_GROUP_CONFIG GC  
                LEFT JOIN META_DATA MDL ON MDL.META_DATA_ID = GC.LOOKUP_META_DATA_ID     
            WHERE GC.MAIN_META_DATA_ID = $idPh     
                AND $where 
            ORDER BY GC.DISPLAY_ORDER ASC", $bindParams);
        
        if (isset($params) && $params) {
            $data = self::qryToParamsCheck($data, $params);
        }

        return $data;
    }

    public function getProcessInputParams($processMetaDataId, $rowId = null) {
        
        $bindParams = array($processMetaDataId);
        $idPh = $this->db->Param(0);
        
        if ($rowId) { 
            array_push($bindParams, $rowId); 
            $where = 'PAL.PARENT_ID = '.$this->db->Param(1);
        } else {
            $where = 'PAL.PARENT_ID IS NULL';
        }
        
        $data = $this->db->GetAll("
            SELECT 
                PAL.ID, 
                PAL.PARENT_ID, 
                LOWER(PAL.DATA_TYPE) AS DATA_TYPE, 
                PAL.PARAM_REAL_PATH,  
                PAL.IS_SHOW, 
                PAL.IS_REQUIRED, 
                PAL.DEFAULT_VALUE, 
                PAL.LOOKUP_META_DATA_ID, 
                MDL.META_DATA_CODE AS LOOKUP_META_DATA_CODE, 
                MDL.META_DATA_NAME AS LOOKUP_META_DATA_NAME, 
                PAL.LOOKUP_TYPE, 
                PAL.DISPLAY_FIELD, 
                PAL.VALUE_FIELD, 
                PAL.CHOOSE_TYPE, 
                PAL.RECORD_TYPE, 
                PAL.TAB_NAME, 
                PAL.LABEL_NAME,  
                PAL.PARAM_NAME   
            FROM META_PROCESS_PARAM_ATTR_LINK PAL 
                LEFT JOIN META_DATA MDL ON MDL.META_DATA_ID = PAL.LOOKUP_META_DATA_ID  
            WHERE PAL.PROCESS_META_DATA_ID = $idPh 
                AND PAL.IS_INPUT = 1 
                AND $where  
            ORDER BY PAL.ORDER_NUMBER ASC", $bindParams);

        return $data;
    }

    public function getProcessOutputParams($processMetaDataId, $rowId = null) {
        
        $bindParams = array($processMetaDataId);
        $idPh = $this->db->Param(0);
        
        if ($rowId) { 
            array_push($bindParams, $rowId); 
            $where = 'PARENT_ID = '.$this->db->Param(1); 
        } else {
            $where = 'PARENT_ID IS NULL';
        }
        
        $data = $this->db->GetAll("
            SELECT 
                ID, 
                PARENT_ID, 
                LOWER(DATA_TYPE) AS DATA_TYPE, 
                PARAM_REAL_PATH,  
                IS_SHOW, 
                RECORD_TYPE, 
                LABEL_NAME,  
                PARAM_NAME   
            FROM META_PROCESS_PARAM_ATTR_LINK    
            WHERE PROCESS_META_DATA_ID = $idPh     
                AND IS_INPUT = 0 
                AND $where  
            ORDER BY ORDER_NUMBER ASC", $bindParams); 

        return $data;
    }

    public function fieldDataTypeComboOptions($where) {

        $options = '';
        $dataType = self::getMetaFieldDataTypeModel($where);

        foreach ($dataType as $type) {
            $options .= '<option value="'.$type['DATA_TYPE_CODE'].'">'.$type['DATA_TYPE_NAME'].'</option>';
        }

        $options .= '<option value="row">Row</option>';
        $options .= '<option value="rows">Rows</option>';

        return $options;
    }

    public function getProcessInputParamAddon($processMetaDataId, $paramPath, $isNew = 0) {

        if ($processMetaDataId && $paramPath && $isNew == 0) {

            $row = $this->db->GetRow("
                SELECT 
                    PAL.MIN_VALUE, 
                    PAL.MAX_VALUE, 
                    PAL.PATTERN_ID,
                    PAL.SIDEBAR_NAME, 
                    PAL.FEATURE_NUM, 
                    PAL.IS_SAVE, 
                    PAL.SEPARATOR_TYPE, 
                    PAL.IS_BUTTON, 
                    PAL.EXPRESSION_STRING, 
                    PAL.VALUE_CRITERIA, 
                    PAL.VISIBLE_CRITERIA, 
                    PAL.IS_SHOW_ADD, 
                    PAL.IS_SHOW_DELETE, 
                    PAL.IS_SHOW_MULTIPLE, 
                    PAL.IS_REFRESH, 
                    PAL.IS_FIRST_ROW, 
                    PAL.IS_FREEZE, 
                    PAL.IS_USER_CONFIG, 
                    PAL.FRACTION_RANGE,   
                    PAL.GROUPING_NAME, 
                    PAL.GET_PROCESS_META_DATA_ID, 
                    PAL.PROCESS_GET_PARAM_PATH, 
                    PAL.FILE_EXTENSION, 
                    PAL.DTL_THEME, 
                    PAL.THEME_POSITION_NO, 
                    PAL.RENDER_TYPE, 
                    PAL.LOOKUP_KEY_META_DATA_ID, 
                    PAL.PAGING_CONFIG, 
                    PAL.COLUMN_WIDTH, 
                    PAL.COLUMN_AGGREGATE, 
                    PAL.IS_EXCEL_EXPORT, 
                    PAL.IS_EXCEL_IMPORT, 
                    PAL.DETAIL_MODIFY_MODE, 
                    PAL.IS_PATH_DISPLAY_ORDER, 
                    PAL.COLUMN_COUNT, 
                    PAL.ICON_NAME, 
                    MK.META_DATA_CODE AS LOOKUP_KEY_META_DATA_CODE, 
                    MK.META_DATA_NAME AS LOOKUP_KEY_META_DATA_NAME, 
                    PAL.MORE_META_DATA_ID, 
                    MM.META_DATA_CODE AS MORE_META_DATA_CODE, 
                    MM.META_DATA_NAME AS MORE_META_DATA_NAME, 
                    PAL.OFFLINE_ORDER, 
                    PAL.TAB_INDEX, 
                    PAL.PLACEHOLDER_NAME, 
                    PAL.DTL_BUTTON_NAME, 
                    PAL.IS_THUMBNAIL, 
                    PAL.JSON_CONFIG, 
                    PAL.WIZARD_STEP 
                FROM META_PROCESS_PARAM_ATTR_LINK PAL 
                    LEFT JOIN META_DATA MK ON MK.META_DATA_ID = PAL.LOOKUP_KEY_META_DATA_ID 
                    LEFT JOIN META_DATA MM ON MM.META_DATA_ID = PAL.MORE_META_DATA_ID 
                WHERE PAL.PROCESS_META_DATA_ID = ".$this->db->Param(0)." 
                    AND PAL.IS_INPUT = 1  
                    AND LOWER(PAL.PARAM_REAL_PATH) = ".$this->db->Param(1), 
                array($processMetaDataId, Str::lower($paramPath))
            );

        } else {

            $row = array(
                'LOOKUP_KEY_META_DATA_ID' => '', 
                'LOOKUP_KEY_META_DATA_CODE' => '', 
                'LOOKUP_KEY_META_DATA_NAME' => '', 
                'MIN_VALUE' => '', 
                'MAX_VALUE' => '', 
                'PATTERN_ID' => '', 
                'SIDEBAR_NAME' => '', 
                'FEATURE_NUM' => '', 
                'IS_SAVE' => '', 
                'SEPARATOR_TYPE' => '', 
                'IS_BUTTON' => '', 
                'EXPRESSION_STRING' => '', 
                'VALUE_CRITERIA' => '', 
                'VISIBLE_CRITERIA' => '', 
                'IS_SHOW_ADD' => '', 
                'IS_SHOW_DELETE' => '', 
                'IS_SHOW_MULTIPLE' => '', 
                'IS_REFRESH' => '', 
                'IS_FIRST_ROW' => '', 
                'IS_FREEZE' => '', 
                'IS_USER_CONFIG' => '', 
                'FRACTION_RANGE' => '', 
                'GROUPING_NAME' => '', 
                'GET_PROCESS_META_DATA_ID' => '', 
                'PROCESS_GET_PARAM_PATH' => '', 
                'FILE_EXTENSION' => '', 
                'DTL_THEME' => '', 
                'THEME_POSITION_NO' => '', 
                'RENDER_TYPE' => '', 
                'PAGING_CONFIG' => '', 
                'COLUMN_WIDTH' => '', 
                'COLUMN_AGGREGATE' => '', 
                'IS_EXCEL_EXPORT' => '', 
                'IS_EXCEL_IMPORT' => '', 
                'DETAIL_MODIFY_MODE' => '',
                'IS_PATH_DISPLAY_ORDER' => '', 
                'COLUMN_COUNT' => '', 
                'ICON_NAME' => '', 
                'OFFLINE_ORDER' => '', 
                'TAB_INDEX' => '', 
                'PLACEHOLDER_NAME' => '', 
                'MORE_META_DATA_ID' => '', 
                'MORE_META_DATA_CODE' => '', 
                'MORE_META_DATA_NAME' => '', 
                'DTL_BUTTON_NAME' => '', 
                'IS_THUMBNAIL' => '', 
                'JSON_CONFIG' => '', 
                'WIZARD_STEP' => ''
            );
        }

        return $row;
    }

    public function processParamAddCodeModel($code, $field) {

        $row = $this->db->GetRow("
            SELECT  
                '' AS ID, 
                '' AS PARENT_ID, 
                ".$this->db->IfNull('FL.DATA_TYPE', 'MT.META_TYPE_CODE')." AS DATA_TYPE,  
                MD.META_DATA_CODE AS PARAM_REAL_PATH,  
                1 AS IS_SHOW, 
                '' AS IS_REQUIRED, 
                '' AS DEFAULT_VALUE, 
                '' AS LOOKUP_META_DATA_ID, 
                '' AS LOOKUP_META_DATA_CODE, 
                '' AS LOOKUP_META_DATA_NAME, 
                '' AS LOOKUP_TYPE, 
                '' AS DISPLAY_FIELD, 
                '' AS VALUE_FIELD, 
                '' AS CHOOSE_TYPE, 
                '' AS RECORD_TYPE, 
                '' AS TAB_NAME,   
                MD.META_DATA_NAME AS LABEL_NAME, 
                MD.META_DATA_CODE AS PARAM_NAME, 
                MD.META_DATA_ID 
            FROM META_DATA MD 
                LEFT JOIN META_FIELD_LINK FL ON FL.META_DATA_ID = MD.META_DATA_ID 
                LEFT JOIN META_TYPE MT ON MT.META_TYPE_ID = MD.META_TYPE_ID 
            WHERE LOWER(MD.$field) = ".$this->db->Param(0), 
            array($code) 
        ); 

        if ($row && strtolower($row['DATA_TYPE']) == 'metagroup') {

            $row['RECORD_TYPE'] = 'rows';
            $row['DATA_TYPE'] = 'group';
        }

        return $row;
    }

    public function processParamAddMultiModel($paramGroupPath) {

        $array = array();
        $selectedRows = $_POST['selectedRows'];

        foreach ($selectedRows as $k => $row) {

            if (strtolower($row['META_TYPE_CODE']) == 'metagroup') {

                $metaDataId = $row['META_DATA_ID'];
                $recordType = 'rows';
                $dataType = 'group';

            } else {
                $metaDataId = null;
                $recordType = '';
                $dataType = $row['META_TYPE_CODE'];
            }

            $array[$k] = array(
                'ID' => getUID(),
                'PARENT_ID' => '',
                'DATA_TYPE' => $dataType, 
                'PARAM_REAL_PATH' => $paramGroupPath.$row['META_DATA_CODE'],
                'IS_SHOW' => '',
                'IS_REQUIRED' => '',
                'DEFAULT_VALUE' => '',
                'LOOKUP_META_DATA_ID' => '',
                'LOOKUP_META_DATA_CODE' => '',
                'LOOKUP_META_DATA_NAME' => '',
                'LOOKUP_TYPE' => '',
                'DISPLAY_FIELD' => '',
                'VALUE_FIELD' => '',
                'CHOOSE_TYPE' => '',
                'RECORD_TYPE' => $recordType,
                'TAB_NAME' => '',
                'LABEL_NAME' => $row['META_DATA_NAME'], 
                'PARAM_NAME' => $row['META_DATA_CODE'], 
                'META_DATA_ID' => $metaDataId 
            );
        }

        return $array;
    }

    public function getMetaGroupInputParams($newParamPath, $depth, $rowId, $isGroup = false) {

        $selectParentId = 'GC.PARENT_ID';
        $paramPath      = $newParamPath;
        
        if (Input::isEmpty('query') == false) {
            
            $params = self::queryToParamsModel(Input::postNonTags('query'), $rowId);

            if (isset($params['queryToParamStatus'])) {
                $params['status'] = $params['queryToParamStatus'];
                echo json_encode($params); exit; 
            }
        }
        
        if ($depth == '1') {

            $metaRow = self::getMetaDataByCodeModel($paramPath);

            if ($metaRow) {
                $metaDataId     = $metaRow['META_DATA_ID'];
                $where          = "GC.MAIN_META_DATA_ID = $metaDataId AND GC.PARENT_ID IS NULL";
                $selectParentId = "'$rowId' AS PARENT_ID";
            } else {
                $where          = '1 = 0';
            }

        } else {
            $where = "GC.PARENT_ID = $rowId";
        }
        
        if ($isGroup) {
            $selectPath = 'FIELD_PATH';
        } else {
            $selectPath = 'PARAM_REAL_PATH';
        }
        
        $sql = "
            SELECT  
                GC.ID, 
                {selectParentId}, 
                GC.DATA_TYPE,  
                '{paramPath}.'||GC.PARAM_NAME AS $selectPath,  
                GC.IS_SHOW, 
                GC.IS_REQUIRED, 
                GC.DEFAULT_VALUE, 
                GC.LOOKUP_META_DATA_ID, 
                MDL.META_DATA_CODE AS LOOKUP_META_DATA_CODE, 
                MDL.META_DATA_NAME AS LOOKUP_META_DATA_NAME, 
                GC.LOOKUP_TYPE, 
                GC.DISPLAY_FIELD, 
                GC.VALUE_FIELD, 
                GC.CHOOSE_TYPE, 
                GC.RECORD_TYPE, 
                GC.TAB_NAME, 
                GC.LABEL_NAME, 
                GC.PARAM_NAME, 
                GC.BODY_ALIGN, 
                GC.HEADER_ALIGN, 
                GC.TEXT_COLOR, 
                GC.TEXT_TRANSFORM, 
                GC.TEXT_WEIGHT, 
                GC.BG_COLOR, 
                GC.FONT_SIZE, 
                GC.COLUMN_AGGREGATE, 
                GC.INPUT_NAME, 
                GC.COLUMN_NAME, 
                GC.IS_RENDER_SHOW,
                GC.IS_SHOW_BASKET, 
                GC.IS_CRITERIA, 
                GC.IS_SELECT, 
                GC.AGGREGATE_ALIAS_PATH 
            FROM META_GROUP_CONFIG GC 
                LEFT JOIN META_DATA MDL ON MDL.META_DATA_ID = GC.LOOKUP_META_DATA_ID 
            WHERE {where}  
            ORDER BY GC.DISPLAY_ORDER ASC";
        
        $execSql = str_replace('{where}', $where, $sql);
        $execSql = str_replace('{paramPath}', $paramPath, $execSql);
        $execSql = str_replace('{selectParentId}', $selectParentId, $execSql);
        
        $data = $this->db->GetAll($execSql);
        
        if (!$data) {
            
            $paramPath = substr($newParamPath, strrpos($newParamPath, '.') + 1);
            
            $metaRow = self::getMetaDataByCodeModel($paramPath);

            if ($metaRow) {
                
                $metaDataId     = $metaRow['META_DATA_ID'];
                $where          = "GC.MAIN_META_DATA_ID = $metaDataId AND GC.PARENT_ID IS NULL";
                $selectParentId = "'$rowId' AS PARENT_ID";
                
                $execSql = str_replace('{where}', $where, $sql);
                $execSql = str_replace('{paramPath}', $newParamPath, $execSql);
                $execSql = str_replace('{selectParentId}', $selectParentId, $execSql);

                $data = $this->db->GetAll($execSql);
            }
        }
        
        if (isset($params) && $params) {
            $data = self::qryToParamsCheck($data, $params);
        }

        return $data;
    }
    
    public function qryToParamsCheck($data, $params) {
        $tmpData = $data;
            
        foreach ($params as $path => $pathRow) {
            $isExists = false;
            foreach ($tmpData as $row) {
                $lowerPath = strtolower($row['FIELD_PATH']);
                if ($path == $lowerPath) {
                    $isExists = true;
                    continue;
                }
            }

            if ($isExists == false) {
                $pathRow['isNew'] = 1;
                $data[] = $pathRow;
            }
        }
        
        return $data;
    }

    public function getMetaGroupOutputParams($newParamPath, $depth, $rowId) {
        
        $selectParentId = 'PARENT_ID';
        $paramPath      = $newParamPath;

        if ($depth == '1') {

            $metaRow = self::getMetaDataByCodeModel($paramPath);

            if ($metaRow) {
                $metaDataId     = $metaRow['META_DATA_ID'];
                $where          = "MAIN_META_DATA_ID = $metaDataId AND PARENT_ID IS NULL";
                $selectParentId = "'$rowId' AS PARENT_ID";
            }

        } else {
            $where = "PARENT_ID = $rowId";
            $paramPath = strtok($paramPath, '.'); 
        }
        
        $sql = "
            SELECT  
                ID, 
                {selectParentId}, 
                DATA_TYPE,  
                '{paramPath}.'||FIELD_PATH AS PARAM_REAL_PATH,  
                IS_SHOW, 
                RECORD_TYPE, 
                LABEL_NAME, 
                PARAM_NAME 
            FROM META_GROUP_CONFIG 
            WHERE {where}  
            ORDER BY DISPLAY_ORDER ASC";
        
        $execSql = str_replace('{where}', $where, $sql);
        $execSql = str_replace('{paramPath}', $paramPath, $execSql);
        $execSql = str_replace('{selectParentId}', $selectParentId, $execSql);
        
        $data = $this->db->GetAll($execSql);
        
        if (!$data) {
            
            $paramPath = substr($newParamPath, strrpos($newParamPath, '.') + 1);
            
            $metaRow = self::getMetaDataByCodeModel($paramPath);

            if ($metaRow) {
                
                $metaDataId     = $metaRow['META_DATA_ID'];
                $where          = "MAIN_META_DATA_ID = $metaDataId AND PARENT_ID IS NULL";
                $selectParentId = "'$rowId' AS PARENT_ID";
                
                $execSql = str_replace('{where}', $where, $sql);
                $execSql = str_replace('{paramPath}', $newParamPath, $execSql);
                $execSql = str_replace('{selectParentId}', $selectParentId, $execSql);

                $data = $this->db->GetAll($execSql);
            }
        }
        
        return $data;
    }
    
    public function getGroupParamAddonModel($groupMetaDataId, $paramPath, $isNew = 0, $isGroup = 'false') {
        
        $idPh = $this->db->Param(0);
        
        if ($groupMetaDataId && $paramPath && $isNew == 0) {

            $row = $this->db->GetRow("
                SELECT 
                    GC.MIN_VALUE, 
                    GC.MAX_VALUE, 
                    GC.PATTERN_ID,
                    GC.SIDEBAR_NAME, 
                    GC.FEATURE_NUM, 
                    GC.IS_SAVE, 
                    GC.SEPARATOR_TYPE, 
                    GC.IS_BUTTON, 
                    GC.EXPRESSION_STRING, 
                    GC.ANALYSIS_DESCRIPTION, 
                    GC.ANALYSIS_EXPRESSION, 
                    GC.VALIDATION_CRITERIA, 
                    GC.VALUE_CRITERIA, 
                    GC.VISIBLE_CRITERIA, 
                    GC.STYLE_CRITERIA, 
                    GC.FRACTION_RANGE,   
                    GC.PROCESS_META_DATA_ID, 
                    GC.PROCESS_GET_PARAM_PATH, 
                    GC.LOOKUP_KEY_META_DATA_ID, 
                    MK.META_DATA_CODE AS LOOKUP_KEY_META_DATA_CODE, 
                    MK.META_DATA_NAME AS LOOKUP_KEY_META_DATA_NAME, 
                    GC.JOIN_TYPE, 
                    GC.RELATION_TYPE, 
                    GC.IS_SIDEBAR, 
                    GC.IS_CRYPTED, 
                    GC.IS_BASKET, 
                    GC.IS_BASKET_EDIT, 
                    GC.SEARCH_GROUPING_NAME, 
                    GC.IS_CRITERIA_SHOW_BASKET, 
                    GC.IS_MANDATORY_CRITERIA, 
                    GC.IS_UNIQUE, 
                    GC.IS_GROUP, 
                    GC.IS_UM_CRITERIA, 
                    GC.IS_COUNTCARD, 
                    GC.AGGREGATE_FUNCTION, 
                    GC.ORDER_NUMBER, 
                    GC.IS_MERGE, 
                    GC.REF_STRUCTURE_ID, 
                    RT.META_DATA_CODE AS STRUCTURE_META_DATA_CODE, 
                    RT.META_DATA_NAME AS STRUCTURE_META_DATA_NAME, 
                    GC.REF_PARAM_NAME, 
                    GC.IS_SKIP_UNIQUE_ERROR, 
                    GC.TABLE_NAME, 
                    GC.COLUMN_WIDTH, 
                    GC.IS_TRANSLATE, 
                    GC.IS_SHOW_MOBILE, 
                    GC.EXCEL_COLUMN_WIDTH,
                    GC.EXCEL_ROTATE, 
                    GC.IS_IGNORE_EXCEL, 
                    GC.COUNTCARD_THEME, 
                    GC.COUNTCARD_SELECTION, 
                    GC.COUNTCARD_ORDER_NUMBER, 
                    GC.SECOND_DISPLAY_ORDER, 
                    GC.LOG_COLUMN_NAME, 
                    GC.ICON_NAME, 
                    GC.IS_ADVANCED,
                    GC.IS_KPI_CRITERIA, 
                    GC.IS_FREEZE, 
                    GC.IS_PASS_FILTER, 
                    GC.IS_NOT_SHOW_CRITERIA, 
                    GC.DEFAULT_OPERATOR, 
                    GC.POSTGRE_SQL, 
                    GC.MS_SQL,  
                    GC.INLINE_PROCESS_ID, 
                    GC.PLACEHOLDER_NAME, 
                    GC.RENDER_TYPE, 
                    GC.THEME_POSITION_NO, 
                    GC.JSON_CONFIG, 
                    GC.DATA_MASK 
                FROM META_GROUP_CONFIG GC 
                    LEFT JOIN META_DATA MK ON MK.META_DATA_ID = GC.LOOKUP_KEY_META_DATA_ID 
                    LEFT JOIN META_DATA RT ON RT.META_DATA_ID = GC.REF_STRUCTURE_ID 
                WHERE GC.MAIN_META_DATA_ID = $idPh    
                    AND LOWER(GC.FIELD_PATH) = ".$this->db->Param(1), 
                array($groupMetaDataId, Str::lower($paramPath))
            );

        } else { 
                    
            $row = array(
                'LOOKUP_KEY_META_DATA_ID' => '', 
                'LOOKUP_KEY_META_DATA_CODE' => '', 
                'LOOKUP_KEY_META_DATA_NAME' => '', 
                'MIN_VALUE' => '', 
                'MAX_VALUE' => '', 
                'PATTERN_ID' => '', 
                'SIDEBAR_NAME' => '', 
                'FEATURE_NUM' => '', 
                'IS_SAVE' => '', 
                'SEPARATOR_TYPE' => '', 
                'IS_BUTTON' => '', 
                'EXPRESSION_STRING' => '', 
                'VISIBLE_CRITERIA' => '', 
                'VALIDATION_CRITERIA' => '',
                'VALUE_CRITERIA' => '', 
                'STYLE_CRITERIA' => '', 
                'ANALYSIS_DESCRIPTION' => '', 
                'ANALYSIS_EXPRESSION' => '', 
                'FRACTION_RANGE' => '', 
                'PROCESS_META_DATA_ID' => '', 
                'PROCESS_GET_PARAM_PATH' => '', 
                'JOIN_TYPE' => '', 
                'RELATION_TYPE' => '', 
                'IS_SIDEBAR' => '', 
                'IS_CRYPTED' => '', 
                'IS_BASKET' => '', 
                'IS_BASKET_EDIT' => '', 
                'SEARCH_GROUPING_NAME' => '', 
                'IS_CRITERIA_SHOW_BASKET' => '', 
                'IS_MANDATORY_CRITERIA' => '', 
                'IS_UNIQUE' => '', 
                'IS_GROUP' => '', 
                'IS_UM_CRITERIA' => '', 
                'IS_COUNTCARD' => '', 
                'IS_SKIP_UNIQUE_ERROR' => '', 
                'AGGREGATE_FUNCTION' => '', 
                'ORDER_NUMBER' => '', 
                'IS_MERGE' => '', 
                'REF_STRUCTURE_ID' => '', 
                'STRUCTURE_META_DATA_CODE' => '', 
                'STRUCTURE_META_DATA_NAME' => '', 
                'REF_PARAM_NAME' => '', 
                'TABLE_NAME' => '', 
                'COLUMN_WIDTH' => '', 
                'IS_TRANSLATE' => '', 
                'IS_SHOW_MOBILE' => '1', 
                'EXCEL_COLUMN_WIDTH' => '', 
                'EXCEL_ROTATE' => '', 
                'IS_IGNORE_EXCEL' => '', 
                'COUNTCARD_THEME' => '', 
                'COUNTCARD_SELECTION' => '', 
                'COUNTCARD_ORDER_NUMBER' => '', 
                'SECOND_DISPLAY_ORDER' => '', 
                'LOG_COLUMN_NAME' => '', 
                'ICON_NAME' => '', 
                'IS_ADVANCED' => '', 
                'IS_KPI_CRITERIA' => '', 
                'IS_FREEZE' => '', 
                'IS_PASS_FILTER' => '', 
                'IS_NOT_SHOW_CRITERIA' => '', 
                'DEFAULT_OPERATOR' => '', 
                'POSTGRE_SQL' => '', 
                'MS_SQL' => '',
                'INLINE_PROCESS_ID' => '', 
                'PLACEHOLDER_NAME' => '', 
                'RENDER_TYPE' => '', 
                'THEME_POSITION_NO' => '', 
                'JSON_CONFIG' => '', 
                'DATA_MASK' => ''
            );
            
            if ($isGroup == 'true') {
                
                $row['JOIN_TYPE'] = 'LEFT JOIN';
                $row['RELATION_TYPE'] = 'hard';
                    
                $tableName = $this->db->GetOne("
                    SELECT 
                        GL.TABLE_NAME 
                    FROM META_DATA MD 
                        INNER JOIN META_GROUP_LINK GL ON GL.META_DATA_ID = MD.META_DATA_ID 
                    WHERE MD.META_DATA_CODE = $idPh", array($paramPath));
                
                if ($tableName) {
                    $row['TABLE_NAME'] = $tableName;
                }
            }
        }

        return $row;
    }
    
    public function groupParamAddMultiModel($paramGroupPath) {

        $array = array();
        $selectedRows = $_POST['selectedRows'];

        foreach ($selectedRows as $k => $row) {

            if (strtolower($row['META_TYPE_CODE']) == 'metagroup') {

                $metaDataId = $row['META_DATA_ID'];
                $recordType = 'rows';
                $dataType = 'group';

            } else {

                $metaDataId = null;
                $recordType = '';
                $dataType = $row['META_TYPE_CODE'];
            }

            $array[$k] = array(
                'ID' => getUID(),
                'PARENT_ID' => '',
                'DATA_TYPE' => $dataType, 
                'FIELD_PATH' => $paramGroupPath.$row['META_DATA_CODE'],
                'IS_SHOW' => '',
                'IS_REQUIRED' => '',
                'IS_SELECT' => '', 
                'IS_SHOW_BASKET' => '', 
                'IS_RENDER_SHOW' => '', 
                'IS_CRITERIA' => '', 
                'DEFAULT_VALUE' => '',
                'LOOKUP_META_DATA_ID' => '',
                'LOOKUP_META_DATA_CODE' => '',
                'LOOKUP_META_DATA_NAME' => '',
                'LOOKUP_TYPE' => '',
                'DISPLAY_FIELD' => '',
                'VALUE_FIELD' => '',
                'CHOOSE_TYPE' => '',
                'RECORD_TYPE' => $recordType,
                'TAB_NAME' => '',
                'LABEL_NAME' => $row['META_DATA_NAME'], 
                'PARAM_NAME' => $row['META_DATA_CODE'], 
                'COLUMN_AGGREGATE' => '', 
                'BODY_ALIGN' => '', 
                'HEADER_ALIGN' => '', 
                'TEXT_COLOR' => '', 
                'TEXT_TRANSFORM' => '', 
                'TEXT_WEIGHT' => '', 
                'BG_COLOR' => '', 
                'FONT_SIZE' => '', 
                'INPUT_NAME' => '', 
                'COLUMN_NAME' => '',  
                'AGGREGATE_ALIAS_PATH' => '',  
                'META_DATA_ID' => $metaDataId 
            );
        }

        return $array;
    }

    public function getBPFullExpressionDefaultVersionModel($metaDataId) {
        
        $row = $this->db->GetRow("
            SELECT 
                ED.ID AS CONFIG_ID,  
                PL.META_DATA_ID, 
                ED.EVENT_EXPRESSION_STRING, 
                ED.LOAD_EXPRESSION_STRING, 
                ED.VAR_FNC_EXPRESSION_STRING, 
                ED.SAVE_EXPRESSION_STRING, 
                MD.META_DATA_NAME, 
                PL.INPUT_META_DATA_ID, 
                PL.ACTION_TYPE, 
                ED.TITLE 
            FROM META_BUSINESS_PROCESS_LINK PL 
                INNER JOIN META_BP_EXPRESSION_DTL ED ON ED.BP_LINK_ID = PL.ID 
                INNER JOIN CUSTOMER_BP_EXP_CONFIG EX ON EX.EXP_DTL_ID = ED.ID 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = PL.META_DATA_ID 
            WHERE PL.META_DATA_ID = ".$this->db->Param(0)." 
                AND EX.IS_DEFAULT = 1", 
            array($metaDataId)
        );

        if ($row) {
            return $row;
        } else {
            return null;
        }
    }
    
    public function getSemanticConfigListModel($refStructureId = 0, $metaValueId = 0) {
        $data = array();

        if (!is_null($refStructureId) || !is_null($metaValueId)) {
            
            $sql = "SELECT MSTD.ID             AS \"dtlid\",
                        MSTD.IS_REQUIRED       AS \"isrequired\",
                        MSTD.MIN_VALUE         AS \"minvalue\",
                        MSTD.MAX_VALUE         AS \"maxvalue\",
                        UO.NAME                AS \"name\",
                        UO.TABLE_NAME          AS \"tablename\",
                        UO.META_DATA_ID        AS \"id\",
                        mstps.SRC_PARAM_PATH   AS \"srcparampath\",
                        mstpt.SRC_DTL_ID       AS \"srcdtlid\",
                        mstpt.TRG_PARAM_PATH   AS \"trgparampath\",
                        MSTD.RENDER_TYPE AS \"rendertype\"
                    FROM META_SEMANTIC_CONFIG MSC
                        INNER JOIN META_SEMANTIC_TEMPLATE MST ON MSC.TEMPLATE_ID = MST.ID
                        INNER JOIN META_SEMANTIC_TEMP_DTL MSTD ON MST.ID = MSTD.TEMPLATE_ID
                        INNER JOIN UM_OBJECT UO ON MSTD.OBJECT_ID = UO.OBJECT_ID
                        LEFT JOIN meta_semantic_temp_param mstps ON mstd.id = mstps.SRC_DTL_ID
                        LEFT JOIN meta_semantic_temp_param mstpt ON mstd.id = mstpt.TRG_DTL_ID
                    WHERE MSC.REF_STRUCTURE_ID = $refStructureId
                        AND MSC.PROCESS_META_DATA_ID = $metaValueId
                    ORDER BY MSTD.ORDER_NUM ASC";

            $data = $this->db->GetAll($sql);
        }

        return $data;
    }
    
    public function queryToParamsModel($query, $parentId = null) {
        
        try {
            
            $queryLower = trim(Str::lower($query));
            $primaryColName = null;
            
            if (strlen($queryLower) <= 30 && strpos($queryLower, 'select ') === false) {
                
                $objectName = $queryLower;
                $queryLower = 'SELECT * FROM ' . $queryLower;
                
                if (DB_DRIVER == 'postgres9') {

                    $keyRow = $this->db->GetRow(sprintf($this->db->metaKeySQL1, strtolower($objectName)));

                    if (isset($keyRow['COLUMN_NAME'])) {
                        $getPrimaryColumn[0] = $keyRow['COLUMN_NAME'];
                    }
                    
                } else {
                    $getPrimaryColumn = $this->db->MetaPrimaryKeys($objectName);
                } 
                
                if (isset($getPrimaryColumn[0])) {
                    $primaryColName = $getPrimaryColumn[0];
                }
                
            } else {
                
                $queryLower = str_replace('hh24:mi:ss', 'HH24-MI-SS', $queryLower);
                $queryLower = preg_replace('/:([_A-Za-z]+)/u', 'null', $queryLower);
            }

            if (strpos($queryLower, 'where') !== false) {
                $queryLower = str_replace('where', 'where 1 = 0 and ', $queryLower);
            } else {
                $queryLower .= ' where 1 = 0';
            }

            $rs = $this->db->Execute($queryLower);
            
            $response = array();
            
            if (isset($rs->_fieldobjs)) {
                
                $fieldObjs  = Arr::objectToArray($rs->_fieldobjs);
                $paths      = Arr::implode_key("','", $fieldObjs, 'name', true);
                $fieldMetas = self::getFieldLinkByPaths($paths);
                $parentPath = $parentPathLower = '';
                
                if (Input::postCheck('paramPath') && Input::numeric('depth') > 0) {
                    $parentPath = Input::post('paramPath') . '.';
                    $parentPathLower = strtolower($parentPath);
                }
                
                foreach ($fieldObjs as $k => $v) {
                    
                    $pathLower = strtolower($v['name']);
                    $pathNotUnderline = str_replace('_', '', $pathLower);
                    $pathNotUnderlinePath = $parentPathLower . $pathNotUnderline;
                    
                    $path = lcfirst(str_replace(' ', '', ucwords(strtr($pathLower, '_-', ' '))));
                    $inputName = '';
                    
                    if ($v['name'] && $primaryColName == $v['name']) {
                        $path = 'id';
                        $inputName = 'META_VALUE_ID';
                    }

                    $response[$pathNotUnderlinePath] = array(
                        'ID'                    => getUIDAdd($k), 
                        'PARENT_ID'             => $parentId, 
                        'DATA_TYPE'             => self::dbTypeConvert($v['type'], issetParam($v['scale'])), 
                        'FIELD_PATH'            => $parentPath . $path,
                        'COLUMN_NAME'           => $v['name'],
                        'IS_SHOW'               => 1,
                        'IS_SELECT'             => 1,
                        'IS_REQUIRED'           => 0,
                        'DEFAULT_VALUE'         => '',
                        'LOOKUP_META_DATA_ID'   => '',
                        'LOOKUP_META_DATA_CODE' => '',
                        'LOOKUP_META_DATA_NAME' => '',
                        'LOOKUP_TYPE'           => '',
                        'DISPLAY_FIELD'         => '',
                        'VALUE_FIELD'           => '',
                        'CHOOSE_TYPE'           => '',
                        'RECORD_TYPE'           => '',
                        'TAB_NAME'              => '',
                        'LABEL_NAME'            => '',
                        'PARAM_NAME'            => $path,
                        'BODY_ALIGN'            => '',
                        'COLUMN_WIDTH'          => '',
                        'HEADER_ALIGN'          => '',
                        'TEXT_COLOR'            => '',
                        'TEXT_TRANSFORM'        => '',
                        'TEXT_WEIGHT'           => '',
                        'BG_COLOR'              => '',
                        'FONT_SIZE'             => '',
                        'COLUMN_AGGREGATE'      => '',
                        'INPUT_NAME'            => $inputName,
                        'IS_RENDER_SHOW'        => '',
                        'IS_SHOW_BASKET'        => '',
                        'IS_CRITERIA'           => '', 
                        'AGGREGATE_ALIAS_PATH'  => ''
                    );
                    
                    if (isset($fieldMetas[$pathNotUnderline])) {
                        
                        $response[$pathNotUnderlinePath]['LABEL_NAME']            = $fieldMetas[$pathNotUnderline]['META_DATA_NAME'];
                        $response[$pathNotUnderlinePath]['CHOOSE_TYPE']           = $fieldMetas[$pathNotUnderline]['CHOOSE_TYPE'];
                        $response[$pathNotUnderlinePath]['DEFAULT_VALUE']         = $fieldMetas[$pathNotUnderline]['DEFAULT_VALUE'];
                        $response[$pathNotUnderlinePath]['DISPLAY_FIELD']         = $fieldMetas[$pathNotUnderline]['DISPLAY_FIELD'];
                        $response[$pathNotUnderlinePath]['VALUE_FIELD']           = $fieldMetas[$pathNotUnderline]['VALUE_FIELD'];
                        $response[$pathNotUnderlinePath]['LOOKUP_TYPE']           = $fieldMetas[$pathNotUnderline]['LOOKUP_TYPE'];
                        $response[$pathNotUnderlinePath]['PATTERN_ID']            = $fieldMetas[$pathNotUnderline]['PATTERN_ID'];
                        $response[$pathNotUnderlinePath]['LOOKUP_META_DATA_ID']   = $fieldMetas[$pathNotUnderline]['LOOKUP_META_DATA_ID'];
                        $response[$pathNotUnderlinePath]['LOOKUP_META_DATA_CODE'] = $fieldMetas[$pathNotUnderline]['LOOKUP_META_DATA_CODE'];
                        $response[$pathNotUnderlinePath]['LOOKUP_META_DATA_NAME'] = $fieldMetas[$pathNotUnderline]['LOOKUP_META_DATA_NAME'];
                    } else {
                        $response[$pathNotUnderlinePath]['LABEL_NAME']            = $path;
                    }
                }
            }
        
        } catch (ADODB_Exception $ex) {
            $response = array('queryToParamStatus' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }
    
    public function dbTypeConvert($dbType, $scale = '') {
        
        $dbType = strtolower($dbType);
        
        if ($dbType == 'int') {
            
            if ($scale == 1) {
                $type = 'boolean';
            } else {
                $type = 'long';
            }
            
        } elseif ($dbType == 'number') {
            $type = 'bigdecimal';
        } elseif ($dbType == 'date') {
            $type = 'date';
        } elseif ($dbType == 'clob') {
            $type = 'clob';
        } else {
            $type = 'string';
        } 
        
        return $type;
    }
    
    public function getFieldLinkByPaths($paths) {
        
        $paths = strtolower($paths);
        $paths = "'" . str_replace('_', '', $paths) . "'";
        
        $data = $this->db->GetAll("
            SELECT 
                LOWER(MD.META_DATA_CODE) AS META_DATA_CODE, 
                MD.META_DATA_NAME, 
                FL.CHOOSE_TYPE, 
                FL.DEFAULT_VALUE, 
                FL.DISPLAY_FIELD,
                FL.VALUE_FIELD,
                FL.LOOKUP_TYPE, 
                FL.PATTERN_ID, 
                FL.LOOKUP_META_DATA_ID, 
                LM.META_DATA_CODE AS LOOKUP_META_DATA_CODE, 
                LM.META_DATA_NAME AS LOOKUP_META_DATA_NAME 
            FROM META_FIELD_LINK FL 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = FL.META_DATA_ID 
                LEFT JOIN META_DATA LM ON LM.META_DATA_ID = FL.LOOKUP_META_DATA_ID 
            WHERE LOWER(MD.META_DATA_CODE) IN ($paths) 
            GROUP BY 
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME, 
                FL.CHOOSE_TYPE, 
                FL.DEFAULT_VALUE, 
                FL.DISPLAY_FIELD,
                FL.VALUE_FIELD,
                FL.LOOKUP_META_DATA_ID, 
                FL.LOOKUP_TYPE, 
                FL.PATTERN_ID, 
                LM.META_DATA_CODE, 
                LM.META_DATA_NAME");
        
        $result = array();
        
        if ($data) {
            foreach ($data as $row) {
                $result[$row['META_DATA_CODE']] = $row;
            }
        }
        
        return $result;
    }
    
    public function isAccessMetaModel($metaId) {
        
        if (Ue::sessionIsUseFolderPermission()) {
            
            $sessionUserKeyDepartmentId = Ue::sessionUserKeyDepartmentId();
            
            if ($sessionUserKeyDepartmentId) {
                
                $sessionUserKeyId = Ue::sessionUserKeyId();
                $idPh1 = $this->db->Param(0);
                $idPh2 = $this->db->Param(1);
                
                $row = $this->db->GetRow("
                    SELECT 
                        PR.META_DATA_ID 
                    FROM (
                        SELECT 
                            MD.META_DATA_ID 
                        FROM META_DATA MD 
                            INNER JOIN UM_USER UM ON UM.USER_ID = MD.CREATED_USER_ID 
                                AND UM.DEPARTMENT_ID = $idPh1  
                        WHERE MD.META_DATA_ID = $idPh2  
                        
                        UNION 
                        
                        SELECT 
                            META_DATA_ID    
                        FROM FVM_FOLDER_USER_PERMISSION FF 
                        WHERE META_DATA_ID = $idPh2 
                            AND USER_ID = $sessionUserKeyId 
                        GROUP BY META_DATA_ID 
                        
                        UNION 
                        
                        SELECT 
                            FF.META_DATA_ID  
                        FROM FVM_FOLDER_USER_PERMISSION FFP 
                            INNER JOIN META_DATA_FOLDER_MAP FF ON FF.FOLDER_ID = FFP.FOLDER_ID 
                        WHERE FF.META_DATA_ID = $idPh2 
                            AND FFP.USER_ID = $sessionUserKeyId 
                        GROUP BY FF.META_DATA_ID  
                        
                        UNION 
                        
                        SELECT 
                            FFP.META_DATA_ID 
                        FROM FVM_FOLDER FF 
                            INNER JOIN META_DATA_FOLDER_MAP FFP ON FFP.FOLDER_ID = FF.FOLDER_ID 
                                AND FFP.META_DATA_ID = $idPh2 
                        WHERE FFP.META_DATA_ID = $idPh2 
                            START WITH FF.FOLDER_ID IN ( 
                                SELECT 
                                    FOLDER_ID 
                                FROM FVM_FOLDER_USER_PERMISSION 
                                WHERE FOLDER_ID IS NOT NULL 
                                    AND USER_ID = $sessionUserKeyId 
                                GROUP BY FOLDER_ID 
                            ) 
                            CONNECT BY NOCYCLE FF.PARENT_FOLDER_ID = PRIOR FF.FOLDER_ID 
                        GROUP BY FFP.META_DATA_ID 
                        
                        UNION 
                        
                        SELECT 
                            META_DATA_ID 
                        FROM META_DATA_FOLDER_MAP 
                        WHERE FOLDER_ID IN ( 
                            SELECT 
                                FF.FOLDER_ID 
                            FROM FVM_FOLDER FF 
                            WHERE FF.FOLDER_ID IN ( 
                                    SELECT 
                                        FOLDER_ID 
                                    FROM META_DATA_FOLDER_MAP 
                                    WHERE META_DATA_ID = $idPh2  
                                )  
                                START WITH FF.FOLDER_ID IN ( 
                                    SELECT 
                                        FOLDER_ID 
                                    FROM FVM_FOLDER_USER_PERMISSION 
                                    WHERE FOLDER_ID IS NOT NULL 
                                        AND USER_ID = $sessionUserKeyId 
                                    GROUP BY FOLDER_ID 
                                ) 
                                CONNECT BY NOCYCLE FF.PARENT_FOLDER_ID = PRIOR FF.FOLDER_ID 
                            GROUP BY FF.FOLDER_ID
                        )
                    ) PR 
                    GROUP BY PR.META_DATA_ID", 
                    array($sessionUserKeyDepartmentId, $metaId)
                );
            
                if (!isset($row['META_DATA_ID'])) {
                    return array('status' => 'warning', 'message' => 'Та засах эрхгүй байна!');
                }
                
            } else {
                return array('status' => 'warning', 'message' => 'Та засах эрхгүй байна!');
            }
        }
        
        return null;
    }
    
    public function isCheckChangeLogMetaModel($metaId) {        
        try {
            
            $beforeDate = Date::beforeDate('Y-m-d H:i:s', '-1 hour');
            
            $row = $this->db->GetRow("
                SELECT 
                    T0.START_TIME, 
                    T1.USERNAME, 
                    T2.FIRST_NAME 
                FROM RECORD_VIEW_LOG T0 
                    INNER JOIN UM_SYSTEM_USER T1 ON T1.USER_ID = T0.USER_ID 
                    LEFT JOIN BASE_PERSON T2 ON T2.PERSON_ID = T1.PERSON_ID 
                WHERE T0.RECORD_ID = ".$this->db->Param(0)." 
                    AND T0.TABLE_NAME = 'META_DATA' 
                    AND T0.START_TIME IS NOT NULL 
                    AND T0.END_TIME IS NULL 
                    AND T0.START_TIME > TO_DATE('$beforeDate', 'YYYY-MM-DD HH24:MI:SS')", 
            array($metaId));
            
            if ($row) {
                
                $message = 'Startdate: '.$row['START_TIME'].'<br />';
                $message .= 'Username: '.$row['USERNAME'].'<br />';
                $message .= 'Firstname: '.$row['FIRST_NAME'].'<br /><br />';
                $message .= 'Уг мета дээр засвар хийж байгаа тул түр хүлээнэ үү!';
                
                return array('status' => 'warning', 'message' => $message); 
            }
            
        } catch (Exception $ex) { }
        
        return null;
    }

    public function getDmLinkModel($metaDataId) {
        $row = $this->db->GetRow("
            SELECT 
                ID,
                META_DATA_ID,
                SELECT_QUERY,
                TABLE_NAME,
                REF_STRUCTURE_ID                      
            FROM META_DATAMART_LINK 
            WHERE META_DATA_ID = ".$this->db->Param(0), 
            array($metaDataId)
        );

        if ($row) {
            return $row;
        } else {
            $row['ID'] = null;
            $row['META_DATA_ID'] = null;
            $row['SELECT_QUERY'] = null;
            $row['TABLE_NAME'] = null;
            $row['REF_STRUCTURE_ID'] = null;

            return $row;
        }
    }    

    public function getDmScheduleModel($metaDataId) {
        $rows = $this->db->GetAll("SELECT ID, META_DATA_ID, SCHEDULE_ID FROM META_DATAMART_SCHEDULE WHERE META_DATA_ID = ".$this->db->Param(0), array($metaDataId));
        $ids = '';
        
        if ($rows) {
            foreach ($rows as $row) {
                $ids .= $row['SCHEDULE_ID'] . ',';
            }
            $ids = rtrim($ids, ',');
        }
        
        return $ids;
    }    

    public function getDmScheduleListModel() {
        $param = array(
            'systemMetaGroupId' => '1471071040820452',
            'showQuery' => 0, 
            'ignorePermission' => 1 
        );

        $result = array();

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($data['status'] == 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);
            $result = $data['result'];
        }

        return $result;
    }    

    public function getMenuColorListModel() {
        $param = array(
            'systemMetaGroupId' => '1702954465281772',
            'showQuery' => 0, 
            'ignorePermission' => 1 
        );

        $result = array();

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($data['status'] == 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);
            $result = $data['result'];
        }

        return $result;
    }    
    
    public function renderMetaV2LeftSidebarModel($dvId) {
            
        $param = array(
            'systemMetaGroupId' => $dvId,
            'ignorePermission'  => 1, 
            'showQuery'         => 0
        );

        $data = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

        if ($data['status'] == 'success' && isset($data['result'])) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);
            return $data['result'];
        }
        
        return array();
    }
    
    public function getProcessInputParamAutoNumberAddon($processMetaDataId, $paramPath) {

        if ($processMetaDataId && $paramPath) {

            $row = $this->db->GetRow("
                SELECT
                    CODE_FORMAT,
                    SEQUENCE_FORMAT,
                    IS_UNIQUE AS AUTO_IS_UNIQUE,
                    '1' AS AUTO_NUMBER
                FROM META_DATA_SEQUENCE_CONFIG 
                WHERE META_DATA_ID = ".$this->db->Param(0)." 
                    AND LOWER(PARAM_NAME) = ".$this->db->Param(1),
                array($processMetaDataId, strtolower($paramPath))
            );

            if ($row) {
                return $row;
            }
        }
            
        return array(
            'AUTO_NUMBER' => '0',
            'CODE_FORMAT' => '', 
            'SEQUENCE_FORMAT' => '', 
            'AUTO_IS_UNIQUE' => ''             
        );
    }    
    
    public function changeMetaFolderMapModel() {
        
        try {
            
            $oldFolderId = Input::numeric('oldFolderId');
            $newFolderId = Input::numeric('newFolderId');
            $metaId = Input::numeric('metaId');
            
            if ($oldFolderId) {
                
                $this->db->AutoExecute('META_DATA_FOLDER_MAP', array('FOLDER_ID' => $newFolderId), 'UPDATE', "FOLDER_ID = $oldFolderId AND META_DATA_ID = $metaId");
                
            } else {
                $data = array(
                    'ID'           => getUID(), 
                    'FOLDER_ID'    => $newFolderId, 
                    'META_DATA_ID' => $metaId
                );
                $this->db->AutoExecute('META_DATA_FOLDER_MAP', $data);
            }
            
            $response = array('status' => 'success');
        
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }

    public function updateDiagramSettings($metaDataId) {
        // XML Төрөлтэй юм хадгалах болсон тул түр зуур шууд POST с утга авав. 
        if (isset($_POST['text'])) {
            $text = str_replace("\r", '', $_POST['text']);
            $text = str_replace("\n", '', $text);
            $text = str_replace("  ", '', $text);
        } else {
            $text = null;
        }
        $processId = Input::post('processId');
        if (is_array(Input::post('yaxis'))) {
            $yAxis = implode(",", Input::post('yaxis'));
        } else {
            $yAxis = Input::post('yaxis');
        }

        $dataDiagramLink = array(
            'ID' => getUID(),
            'META_DATA_ID' => $metaDataId,
            'PROCESS_META_DATA_ID' => $processId,
            'DASHBOARD_TYPE' => Input::post('dashboardType'),
            'DIAGRAM_TYPE' => Input::post('chartType'),
            'DIAGRAM_THEME' => Input::post('chartTheme'),
            'IS_USE_META' => ((Input::postCheck('isUseMeta')) ? 1 : 0),
            'IS_USE_LEGEND' => ((Input::postCheck('isUseLegend')) ? 1 : 0),
            'IS_INLINE_LEGEND' => ((Input::postCheck('isInlineLegend')) ? 1 : 0),
            'IS_USE_CRITERIA' => ((Input::postCheck('isUseCriteria')) ? 1 : 0),
            'IS_USE_GRAPH' => ((Input::postCheck('isuseGraph')) ? 1 : 0),
            'IS_USE_LIST' => ((Input::postCheck('isuseList')) ? 1 : 0),
            'LEGEND_POSITION' => Input::post('chartLegendPos'),
            'LEGEND_FORMAT' => Input::post('legendFormat'),
            'MINIMUM_VALUE' => Input::post('valueAxesMin'),
            'MAXIMUM_VALUE' => Input::post('valueAxesMax'),
            'COLOR_FIELD' => Input::post('colorField'),                    
            'TEXT' => $text,
            'WIDTH' => (Input::post('width') == '') ? Input::post('width') : Input::post('width'),
            'HEIGHT' => (Input::post('height') == '') ? Input::post('height') : Input::post('height'),
            'IS_SHOW_TITLE' => Input::post('isShowTitle'),
            'TITLE' => Input::post('chartTitle'),
            'IS_MULTIPLE' => (int) Input::post('isMultiple'),
            'IS_SHOW_LABEL' => (int) Input::post('isShowLabel'),
            'IS_DATA_LABEL' => (int) Input::post('isDataLabel'),
            'LABEL_STEP' => (int) Input::post('labelStep'),
            'IS_SHOW_EXPORT' => Input::post('isShowExport'),
            'IS_X_LABEL' => (int) Input::post('isXLabel'),
            'IS_Y_LABEL' => (int) Input::post('isYLabel'),
            'IS_BACKGROUND' => (int) Input::post('isBackground'),
            'IS_LITTLE' => (int) Input::post('isLittle'),
            'THEME' => (Input::post('theme') == '') ? Input::post('light') : Input::post('theme'),
            'XAXIS' => Input::post('xaxis'),
            'YAXIS' => $yAxis,
            'XAXISGROUP' => Input::post('xaxisGroup'),
            'YAXISGROUP' => Input::post('yaxisGroup'),
            'IS_VIEW_DATAGRID' => Input::post('isViewDataGrid'),                    
            'X_LABEL_ROTATION' => Input::post('xLabelRotation'),
            'CREATED_USER_ID' => Ue::sessionUserKeyId(),
            'CREATED_DATE' => Date::currentDate(),
            'IS_MULTIPLE_PROCESS' => '0',
            'XAXIS2' => '',
            'YAXIS2' => '',
            'XAXIS3' => '',
            'YAXIS3' => '',
            'XAXIS4' => '',
            'YAXIS4' => '',
            'PROCESS_META_DATA_ID2' => '',
            'PROCESS_META_DATA_ID3' => '',
            'PROCESS_META_DATA_ID4' => '', 
            'VALUE_AXIS_TITLE' => Input::post('valueAxisTitle'),
            'TEMPLATE_WIDTH' => Input::post('templateWidth'),
            'CATEGORY_AXIS_TITLE' => Input::post('categoryAxisTitle'),
            'LABEL_TEXT_SUBSTR' => Input::post('labelTextSubstr'),
            'COLOR' => Input::post('chartColor'),
            'COLOR2' => Input::post('chartColor2'),
        );

        if (Input::postCheck('isMultipleProcess')) {
            if ($_POST['isMultipleProcess'] === '1') {
                $dataDiagramLink['IS_MULTIPLE_PROCESS'] = Input::post('isMultipleProcess');
                $dataDiagramLink['XAXIS2'] = (isset($_POST['xaxis2'])) ? Input::post('xaxis2') : '';
                $dataDiagramLink['YAXIS2'] = (isset($_POST['yaxis2'])) ? Input::post('yaxis2') : '';
                $dataDiagramLink['XAXIS3'] = (isset($_POST['xaxis3'])) ? Input::post('xaxis3') : '';
                $dataDiagramLink['YAXIS3'] = (isset($_POST['yaxis3'])) ? Input::post('yaxis3') : '';
                $dataDiagramLink['XAXIS4'] = (isset($_POST['xaxis4'])) ? Input::post('xaxis4') : '';
                $dataDiagramLink['YAXIS4'] = (isset($_POST['yaxis4'])) ? Input::post('yaxis4') : '';
                $dataDiagramLink['PROCESS_META_DATA_ID2'] = (isset($_POST['processId2'])) ? Input::post('processId2') : '';
                $dataDiagramLink['PROCESS_META_DATA_ID3'] = (isset($_POST['processId3'])) ? Input::post('processId3') : '';
                $dataDiagramLink['PROCESS_META_DATA_ID4'] = (isset($_POST['processId4'])) ? Input::post('processId4') : '';
            }
        }
        if (self::isExistsMetaLink('META_DASHBOARD_LINK', 'META_DATA_ID', $metaDataId)) {
            unset($dataDiagramLink['ID']);
            $this->db->AutoExecute('META_DASHBOARD_LINK', $dataDiagramLink, 'UPDATE', 'META_DATA_ID = ' . $metaDataId);
        } else {
            $this->db->AutoExecute('META_DASHBOARD_LINK', $dataDiagramLink);
        }
        
        $addonSettings = array(
            'value' => Str::lower(Input::post('pie_charts_bullets_value')),
            'risKvalue' => Str::lower(Input::post('risk_heatmap_value')),
            'bubbleValue' => Str::lower(Input::post('animated_xy_bubble_value')),
            'category' => Str::lower(Input::post('category')),
            'title' => Str::lower(Input::post('pie_charts_bullets_title')),
            'min' => Input::post('chart_minimum'),
            'max' => Input::post('chart_maximum'),
            'isvertical' => Input::post('isvertical'),
            'stacky' => Input::post('stacky'),
            'stackx' => Input::post('stackx'),
            'stackxorder' => Input::post('stackxorder'),
            'centerlabeltext' => Input::post('centerlabeltext'),
            'centerlabelnumber' => Str::lower(Input::post('centerlabelnumber')),
            'labelfontsize' => Str::lower(Input::post('labelfontsize')),
            'labelmarkerwidth' => Str::lower(Input::post('labelmarkerwidth')),
            'labelmarkerheight' => Str::lower(Input::post('labelmarkerheight')),
            'criteriaPosition' => Input::post('chartCriteriaPostion'),
            'criteriaSplitColumnCount' => Input::post('chartCriteriaSplitColumnCount'),
        );
        $this->db->UpdateClob('META_DASHBOARD_LINK', 'ADDON_SETTINGS', json_encode($addonSettings), 'META_DATA_ID = '.$metaDataId);
    }

    public function getMetaDataDrillModel($metaDataId, $getFolder = false) {
        
        $metaDataIdPh = $this->db->Param(0);
        $bindVars = array($this->db->addQ($metaDataId));
        
        $row = $this->db->GetRow("
            SELECT 
                MD.META_DATA_ID, 
                MD.META_DATA_CODE, 
                MD.META_DATA_NAME, 
                MD.META_TYPE_ID, 
                MT.META_TYPE_CODE, 
                MD.DESCRIPTION, 
                MD.COPY_COUNT,
                BL.BOOKMARK_URL 
            FROM META_DATA MD 
                LEFT JOIN META_TYPE MT ON MT.META_TYPE_ID = MD.META_TYPE_ID 
                LEFT JOIN META_BOOKMARK_LINKS BL ON BL.META_DATA_ID = MD.META_DATA_ID 
            WHERE MD.META_DATA_ID = $metaDataIdPh", $bindVars);

        if ($getFolder && $row) {
            $folder = $this->db->GetRow("SELECT FOLDER_ID FROM META_DATA_FOLDER_MAP WHERE META_DATA_ID = $metaDataIdPh", $bindVars);
            if ($folder) {
                $row = array_merge($row, $folder);
            }
        }

        return $row;
    }    

}
