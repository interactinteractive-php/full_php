<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdlock_Model extends Model {
    
    private static $schemaName = 'VR_PASSWORD_CENTER';
    private static $categoryTreeDatas = array();
    private static $t = 0;
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getChildCategoryIds($parentId) {
        
        try {
            $schemaName = self::$schemaName;
        
            $data = $this->db->GetAll("
                SELECT 
                    ID 
                FROM $schemaName.PWD_CATEGORY 
                WHERE PARENT_ID = ".$this->db->Param(0), 
                array($parentId)
            );

            foreach ($data as $row) {
                self::$categoryTreeDatas[self::$t] = $row['ID'];
                self::$t++;
                self::getChildCategoryIds($row['ID']);
            }
            
        } catch (Exception $ex) {
            self::$categoryTreeDatas = array();
        }

        return self::$categoryTreeDatas;
    }
    
    public function lockDataGridModel() {
        
        try {
            
            $page = Input::postCheck('page') ? Input::post('page') : 1;
            $rows = Input::postCheck('rows') ? Input::post('rows') : 10;
            $offset = ($page - 1 ) * $rows;

            $schemaName = self::$schemaName;
            $where = '';

            if (Input::postCheck('categoryId') && !Input::isEmpty('categoryId')) {

                $categoryId = Input::post('categoryId');

                if ($categoryId != '99999999') {

                    self::$categoryTreeDatas = array();
                    $childCategoryIds = self::getChildCategoryIds($categoryId);
                    $commaCategoryIds = ((count($childCategoryIds) > 0) ? $categoryId . ',' . Arr::implode_r(',', $childCategoryIds, true) : $categoryId);
                    $where .= " AND PM.META_DATA_ID IN (SELECT META_DATA_ID FROM $schemaName.PWD_CATEGORY_MAP WHERE CATEGORY_ID IN ($commaCategoryIds))";
                }
            }

            if (Input::postCheck('filterRules')) {

                $filterRules = json_decode(Str::cp1251_utf8($_POST['filterRules']), true);

                foreach ($filterRules as $rule) {

                    $field = $rule['field'];
                    $value = Input::param(Str::lower($rule['value']));

                    if ($value != '') {
                        if ($field === 'META_DATA_CODE') {
                            $where .= " AND LOWER(PM.META_DATA_CODE) LIKE '%$value%'";
                        } elseif ($field === 'META_DATA_NAME') {
                            $where .= " AND LOWER(PM.META_DATA_NAME) LIKE '%$value%'";
                        } elseif ($field === 'LABEL_NAME') {
                            $where .= " AND LOWER(PM.LABEL_NAME) LIKE '%$value%'";
                        } elseif ($field === 'META_TYPE_NAME') {
                            $where .= " AND LOWER(MT.META_TYPE_NAME) LIKE '%$value%'";
                        } elseif ($field === 'DESCRIPTION') {
                            $where .= " AND LOWER(PM.DESCRIPTION) LIKE '%$value%'";
                        } elseif ($field === 'IS_LOCKED') {
                            $where .= " AND PM.IS_LOCKED = $value";
                        }
                    }
                }
            }

            $sortField = 'PM.ID';
            $sortOrder = 'DESC';
            if (Input::postCheck('sort') && Input::postCheck('order')) {
                $sortField = Input::post('sort');
                $sortOrder = Input::post('order');
            }

            $rowCount = $this->db->GetRow(
                "SELECT 
                    COUNT(1) AS ROW_COUNT 
                FROM (   
                    SELECT 
                        PM.META_DATA_ID, 
                        PM.META_DATA_CODE, 
                        PM.META_DATA_NAME,  
                        PM.META_TYPE_NAME, 
                        PM.IS_LOCKED, 
                        PM.DESCRIPTION, 
                        CAT.NAME 
                    FROM (
                            SELECT 
                                PM.ID, 
                                PM.META_DATA_ID, 
                                MD.META_DATA_CODE, 
                                MD.META_DATA_NAME,  
                                MT.META_TYPE_NAME, 
                                PM.IS_LOCKED, 
                                PM.DESCRIPTION, 
                                PM.CREATED_DATE, 
                                ".$this->db->IfNull($this->db->IfNull($this->db->IfNull($this->db->IfNull('BPG.MONGOLIAN', 'BP.GLOBE_CODE'), $this->db->IfNull('GGD.MONGOLIAN', 'GL.GLOBE_CODE')), $this->db->IfNull('SGD.MONGOLIAN', 'SL.REPORT_NAME')), 'MD.META_DATA_NAME')." AS LABEL_NAME 
                            FROM $schemaName.PWD_CERTIFIED_BOOK PM 

                                INNER JOIN META_DATA MD ON MD.META_DATA_ID = PM.META_DATA_ID 
                                INNER JOIN META_TYPE MT ON MT.META_TYPE_ID = MD.META_TYPE_ID 

                                LEFT JOIN META_BUSINESS_PROCESS_LINK BP ON BP.META_DATA_ID = PM.META_DATA_ID 
                                LEFT JOIN GLOBE_DICTIONARY BPG ON LOWER(BPG.CODE) = LOWER(BP.GLOBE_CODE) 

                                LEFT JOIN META_GROUP_LINK GL ON GL.META_DATA_ID = PM.META_DATA_ID 
                                LEFT JOIN GLOBE_DICTIONARY GGD ON LOWER(GGD.CODE) = LOWER(GL.GLOBE_CODE) 

                                LEFT JOIN META_STATEMENT_LINK SL ON SL.META_DATA_ID = PM.META_DATA_ID 
                                LEFT JOIN GLOBE_DICTIONARY SGD ON LOWER(SGD.CODE) = LOWER(SL.REPORT_NAME) 
                        ) PM  
                        LEFT JOIN $schemaName.PWD_CATEGORY_MAP CM ON CM.META_DATA_ID = PM.META_DATA_ID 
                        LEFT JOIN $schemaName.PWD_CATEGORY CAT ON CAT.ID = CM.CATEGORY_ID 
                    WHERE 1 = 1 $where 
                    GROUP BY 
                        PM.META_DATA_ID, 
                        PM.META_DATA_CODE, 
                        PM.META_DATA_NAME,  
                        PM.META_TYPE_NAME, 
                        PM.IS_LOCKED, 
                        PM.DESCRIPTION, 
                        CAT.NAME 
                )");

            $result = array();
            $result['total'] = $rowCount['ROW_COUNT'];
            $result['rows'] = array();

            if ($result['total'] > 0) {

                $selectList = "
                    SELECT 
                        PM.META_DATA_ID, 
                        PM.META_DATA_CODE, 
                        PM.META_DATA_NAME,  
                        PM.META_TYPE_NAME, 
                        PM.IS_LOCKED, 
                        PM.DESCRIPTION, 
                        CAT.NAME, 
                        MAX(PM.CREATED_DATE) AS CREATED_DATE, 
                        PM.LABEL_NAME 
                    FROM (
                            SELECT 
                                PM.ID, 
                                PM.META_DATA_ID, 
                                MD.META_DATA_CODE, 
                                MD.META_DATA_NAME,  
                                MT.META_TYPE_NAME, 
                                PM.IS_LOCKED, 
                                PM.DESCRIPTION, 
                                PM.CREATED_DATE, 
                                ".$this->db->IfNull($this->db->IfNull($this->db->IfNull($this->db->IfNull('BPG.MONGOLIAN', 'BP.GLOBE_CODE'), $this->db->IfNull('GGD.MONGOLIAN', 'GL.GLOBE_CODE')), $this->db->IfNull('SGD.MONGOLIAN', 'SL.REPORT_NAME')), 'MD.META_DATA_NAME')." AS LABEL_NAME 
                            FROM $schemaName.PWD_CERTIFIED_BOOK PM 

                                INNER JOIN META_DATA MD ON MD.META_DATA_ID = PM.META_DATA_ID 
                                INNER JOIN META_TYPE MT ON MT.META_TYPE_ID = MD.META_TYPE_ID 

                                LEFT JOIN META_BUSINESS_PROCESS_LINK BP ON BP.META_DATA_ID = PM.META_DATA_ID 
                                LEFT JOIN GLOBE_DICTIONARY BPG ON LOWER(BPG.CODE) = LOWER(BP.GLOBE_CODE) 

                                LEFT JOIN META_GROUP_LINK GL ON GL.META_DATA_ID = PM.META_DATA_ID 
                                LEFT JOIN GLOBE_DICTIONARY GGD ON LOWER(GGD.CODE) = LOWER(GL.GLOBE_CODE) 

                                LEFT JOIN META_STATEMENT_LINK SL ON SL.META_DATA_ID = PM.META_DATA_ID 
                                LEFT JOIN GLOBE_DICTIONARY SGD ON LOWER(SGD.CODE) = LOWER(SL.REPORT_NAME) 
                        ) PM  
                        LEFT JOIN $schemaName.PWD_CATEGORY_MAP CM ON CM.META_DATA_ID = PM.META_DATA_ID 
                        LEFT JOIN $schemaName.PWD_CATEGORY CAT ON CAT.ID = CM.CATEGORY_ID 
                    WHERE 1 = 1 $where 
                    GROUP BY 
                        PM.META_DATA_ID, 
                        PM.META_DATA_CODE, 
                        PM.META_DATA_NAME,  
                        PM.META_TYPE_NAME, 
                        PM.IS_LOCKED, 
                        PM.DESCRIPTION, 
                        CAT.NAME, 
                        PM.ID, 
                        PM.LABEL_NAME 
                    ORDER BY $sortField $sortOrder";

                $rs = $this->db->SelectLimit($selectList, $rows, $offset);

                $result['rows'] = $rs->_array;
            }
        
        } catch (Exception $ex) {
            $result = array('total' => 0, 'rows' => array());
        }

        return $result;
    }
    
    public function getLockCategoryListModel() {
        
        try {
            $data = $this->db->GetAll("
                SELECT 
                    ID, 
                    LPAD('-', 2*(LEVEL-1), '-') ||' '|| NAME AS CAT_NAME  
                FROM ".self::$schemaName.".PWD_CATEGORY  
                    START WITH PARENT_ID IS NULL
                    CONNECT BY PRIOR ID = PARENT_ID 
                ORDER BY ID ASC");

            return $data;
        } catch (Exception $ex) {
            return array();
        }
    }
    
    public function getTreeLockCategoryListModel() {
        
        try {
            $data = $this->db->GetAll("
                SELECT 
                    ID, 
                    NAME, 
                    PARENT_ID 
                FROM ".self::$schemaName.".PWD_CATEGORY 
                ORDER BY ID ASC");

            $tempArray = array('ID' => '99999999', 'NAME' => 'Бүгд', 'PARENT_ID' => null);

            array_unshift($data, $tempArray);

            return $data;
        } catch (Exception $ex) {
            return array();
        }
    }
    
    public function lockTempSaveModel() {
        
        try {
            
            $lockName = strtolower(Input::post('lockName'));
            $lockPass = Hash::createMD5reverse(Input::post('lockPass'));
            
            if ($userId = self::getLockUserModel($lockName, $lockPass)) {
                
                if (!Input::postCheck('metaDataId')) {
                    return array('status' => 'warning', 'message' => 'Үзүүлэлт сонгоно уу');
                }
                
                $categoryId = Input::post('categoryId');
                $isLocked = Input::postCheck('isLocked') ? 1 : 0;
                $description = Input::post('description');
                $metaDataIds = Input::post('metaDataId');
                
                foreach ($metaDataIds as $metaDataId) {
                    
                    if (self::isExistsMetaModel($metaDataId) == false) {
                        
                        $data = array(
                            'ID' => getUID(), 
                            'META_DATA_ID' => $metaDataId, 
                            'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s'), 
                            'CREATED_USER_ID' => $userId, 
                            'DESCRIPTION' => $description,
                            'IS_LOCKED' => $isLocked
                        );
                        $result = $this->db->AutoExecute(self::$schemaName.'.PWD_CERTIFIED_BOOK', $data);
                        
                        $logData = array(
                            'META_DATA_ID' => $metaDataId, 
                            'DESCRIPTION' => $description,
                            'LICENSER_USER_ID' => $userId, 
                            'LOG_TYPE' => 'added'
                        );
                        self::lockWriteLog($logData);
                        
                        if ($result && $categoryId) {
                            
                            $data = array(
                                'ID' => getUID(), 
                                'CATEGORY_ID' => $categoryId, 
                                'META_DATA_ID' => $metaDataId
                            );
                            $this->db->AutoExecute(self::$schemaName.'.PWD_CATEGORY_MAP', $data);
                        }
                    }
                }

                return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
                
            } else {
                return array('status' => 'warning', 'message' => 'Lock name, password буруу байна');
            }
            
        } catch (Exception $ex) {
            return array('status' => 'error', 'message' => 'Error');
        }
    }
    
    public function getLockUserModel($lockName, $lockPass) {
        
        try {
            
            $userId = $this->db->GetOne("
                SELECT 
                    ID 
                FROM ".self::$schemaName.".PWD_QA_USER  
                WHERE LOWER(USER_NAME) = ".$this->db->Param(0)." 
                    AND PASSWORD = ".$this->db->Param(1), 
                array($lockName, $lockPass)
            );

            return $userId;
        } catch (Exception $ex) {
            return null;
        }
    }
    
    public function isExistsMetaModel($metaDataId) {
        
        try {
            
            $id = $this->db->GetOne("
                SELECT 
                    META_DATA_ID 
                FROM ".self::$schemaName.".PWD_CERTIFIED_BOOK  
                WHERE META_DATA_ID = ".$this->db->Param(0), array($metaDataId));

            return ($id ? true : false);
        } catch (Exception $ex) {
            return false;
        }
    }
    
    public function deleteLockModel() {
        
        $lockName = strtolower(Input::post('lockName'));
        $lockPass = Hash::createMD5reverse(Input::post('lockPass'));

        if ($userId = self::getLockUserModel($lockName, $lockPass)) {
            
            try {
                
                $rows = json_decode($_POST['selectedRows'], true);
                $ids = Arr::implode_key(',', $rows, 'META_DATA_ID', true);
                $schemaName = self::$schemaName;
                
                $this->db->Execute("DELETE FROM $schemaName.PWD_PERMISSION_BOOK WHERE META_DATA_ID IN ($ids)");
                $this->db->Execute("DELETE FROM $schemaName.PWD_CATEGORY_MAP WHERE META_DATA_ID IN ($ids)");
                $this->db->Execute("DELETE FROM $schemaName.PWD_CERTIFIED_BOOK WHERE META_DATA_ID IN ($ids)");
                
                return array('status' => 'success', 'message' => Lang::line('msg_delete_success'));
                
            } catch (Exception $ex) {
                return array('status' => 'error', 'message' => 'Error - deleteLock');
            }
            
        } else {
            return array('status' => 'warning', 'message' => 'Lock name, password буруу байна');
        }
    }
    
    public function saveLockModel() {
        
        $lockName = strtolower(Input::post('lockName'));
        $lockPass = Hash::createMD5reverse(Input::post('lockPass'));

        if ($userId = self::getLockUserModel($lockName, $lockPass)) {
            
            try {
                
                $schemaName = self::$schemaName;
                $rows = json_decode($_POST['selectedRows'], true);
                $description = Input::post('description');
                
                foreach ($rows as $row) {
                    
                    $data = array(
                        'MODIFIED_DATE' => Date::currentDate('Y-m-d H:i:s'), 
                        'MODIFIED_USER_ID' => $userId, 
                        'DESCRIPTION' => $description, 
                        'IS_LOCKED' => 1
                    );
                    
                    $metaDataId = $row['META_DATA_ID'];
                    
                    $this->db->AutoExecute($schemaName.'.PWD_CERTIFIED_BOOK', $data, 'UPDATE', 'META_DATA_ID = '.$metaDataId);
                    
                    $logData = array(
                        'META_DATA_ID' => $metaDataId, 
                        'DESCRIPTION' => $description,
                        'LICENSER_USER_ID' => $userId, 
                        'LOG_TYPE' => 'locked'
                    );
                    self::lockWriteLog($logData);
                }
                
                return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
                
            } catch (Exception $ex) {
                return array('status' => 'error', 'message' => 'Error - saveLock');
            }
            
        } else {
            return array('status' => 'warning', 'message' => 'Lock name, password буруу байна');
        }
    }
    
    public function saveUnlockModel() {
        
        $lockName = strtolower(Input::post('lockName'));
        $lockPass = Hash::createMD5reverse(Input::post('lockPass'));

        if ($userId = self::getLockUserModel($lockName, $lockPass)) {
            
            try {
                
                $schemaName = self::$schemaName;
                $rows = json_decode($_POST['selectedRows'], true);
                
                foreach ($rows as $row) {
                    
                    $metaDataId = $row['META_DATA_ID'];
                    $check = $this->db->GetOne("
                        SELECT 
                            ID 
                        FROM $schemaName.PWD_CERTIFIED_BOOK 
                        WHERE META_DATA_ID = ".$this->db->Param(0)." 
                            AND CREATED_USER_ID = ".$this->db->Param(1), 
                        array($metaDataId, $userId)
                    );
                    
                    if ($check) {
                        
                        $data = array(
                            'MODIFIED_DATE' => Date::currentDate('Y-m-d H:i:s'), 
                            'MODIFIED_USER_ID' => $userId, 
                            'IS_LOCKED' => 0
                        );
                        $this->db->AutoExecute($schemaName.'.PWD_CERTIFIED_BOOK', $data, 'UPDATE', 'META_DATA_ID = '.$metaDataId);
                        
                        $logData = array(
                            'META_DATA_ID' => $metaDataId, 
                            'LICENSER_USER_ID' => $userId, 
                            'LOG_TYPE' => 'unlocked', 
                            'DESCRIPTION' => Input::post('descr')
                        );
                        self::lockWriteLog($logData);
                    
                        $isUnlocked = true;
                    }
                }
                
                if (isset($isUnlocked)) {
                    return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
                } else {
                    return array('status' => 'warning', 'message' => 'Таны түгжсэн үзүүлэлт биш байна. Зөвхөн таны өөрийн түгжсэн үзүүлэлт дээр боломжтой.');
                }
                
            } catch (Exception $ex) {
                return array('status' => 'error', 'message' => 'Error - saveUnlock');
            }
            
        } else {
            return array('status' => 'warning', 'message' => 'Lock name, password буруу байна');
        }
    } 
    
    public function shareLockSaveModel() {
        
        $lockName = strtolower(Input::post('lockName'));
        $lockPass = Hash::createMD5reverse(Input::post('lockPass'));
        
        if ($licenserUserId = self::getLockUserModel($lockName, $lockPass)) {
            
            try {
                
                $schemaName = self::$schemaName;
                
                $userId = Input::numeric('userId');
                $endTime = Input::post('endTime');
                $rows = json_decode($_POST['selectedRows'], true);
                $description = Input::post('description');
                
                foreach ($rows as $row) {
                    
                    $metaDataId = $row['META_DATA_ID'];
                    $check = $this->db->GetOne("
                        SELECT 
                            ID 
                        FROM $schemaName.PWD_CERTIFIED_BOOK 
                        WHERE META_DATA_ID = ".$this->db->Param(0)." 
                            AND CREATED_USER_ID = ".$this->db->Param(1), 
                        array($metaDataId, $licenserUserId)
                    );
                    
                    if ($check) {
                        
                        $data = array(
                            'ID' => getUID(), 
                            'USER_ID' => $userId, 
                            'META_DATA_ID' => $metaDataId, 
                            'END_TIME' => $endTime, 
                            'LICENSER_USER_ID' => $licenserUserId, 
                            'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s'), 
                            'DESCRIPTION' => $description
                        );
                        $this->db->AutoExecute($schemaName.'.PWD_PERMISSION_BOOK', $data);
                        
                        $updateData = array(
                            'DESCRIPTION' => $description
                        );
                        $this->db->AutoExecute($schemaName.'.PWD_CERTIFIED_BOOK', $updateData, 'UPDATE', 'META_DATA_ID = '.$metaDataId);
                        
                        $logData = array(
                            'META_DATA_ID' => $metaDataId, 
                            'LICENSER_USER_ID' => $licenserUserId, 
                            'USER_ID' => $userId, 
                            'DESCRIPTION' => $description, 
                            'END_TIME' => $endTime, 
                            'LOG_TYPE' => 'sharelock'
                        );
                        self::lockWriteLog($logData);
                        
                        $isUnlocked = true;
                    }
                }
                
                if (isset($isUnlocked)) {
                    return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
                } else {
                    return array('status' => 'warning', 'message' => 'Таны түгжсэн үзүүлэлт биш байна. Зөвхөн таны өөрийн түгжсэн үзүүлэлт дээр боломжтой.');
                }
                
            } catch (Exception $ex) {
                return array('status' => 'error', 'message' => 'Error - shareLockSave');
            }
            
        } else {
            return array('status' => 'warning', 'message' => 'Lock name, password буруу байна');
        }
    }
    
    public function lockCategorySaveModel() {
        
        try {
                
            $schemaName = self::$schemaName;

            $data = array(
                'ID' => getUID(), 
                'NAME' => Input::post('categoryName'), 
                'PARENT_ID' => Input::numeric('categoryId')
            );
            $this->db->AutoExecute($schemaName.'.PWD_CATEGORY', $data);

            return array('status' => 'success', 'message' => Lang::line('msg_save_success'));

        } catch (Exception $ex) {
            return array('status' => 'error', 'message' => 'Error - lockCategorySave');
        }
    }
    
    public function getLockCategoryRowModel($id) {
        
        try {
            $row = $this->db->GetRow("SELECT * FROM ".self::$schemaName.".PWD_CATEGORY WHERE ID = ".$this->db->Param(0), array($id));
        } catch (Exception $ex) {
            $row = array();
        }
        return $row;
    }
    
    public function lockCategoryEditSaveModel() {
        
        try {
                
            $schemaName = self::$schemaName;

            $data = array(
                'NAME' => Input::post('categoryName'), 
                'PARENT_ID' => Input::numeric('categoryId')
            );
            $this->db->AutoExecute($schemaName.'.PWD_CATEGORY', $data, 'UPDATE', 'ID = '.Input::numeric('id'));

            return array('status' => 'success', 'message' => Lang::line('msg_save_success'));

        } catch (Exception $ex) {
            return array('status' => 'error', 'message' => 'Error - lockCategoryEditSave');
        }
    }
    
    public function lockCategoryDeleteModel() {
        
        try {

            $schemaName = self::$schemaName;
            $id = Input::numeric('id');
            
            $childIds = self::getChildCategoryIds($id);
            
            if ($childIds) {
                $this->db->Execute("DELETE FROM $schemaName.PWD_CATEGORY WHERE ID IN (".Arr::implode_r(',', $childIds, true).")");
            }
            
            $this->db->Execute("DELETE FROM $schemaName.PWD_CATEGORY WHERE ID = ".$this->db->Param(0), array($id));
            $this->db->Execute("DELETE FROM $schemaName.PWD_CATEGORY_MAP WHERE CATEGORY_ID = ".$this->db->Param(0), array($id));

            return array('status' => 'success', 'message' => Lang::line('msg_delete_success'));

        } catch (Exception $ex) {
            return array('status' => 'error', 'message' => 'Error - lockCategoryDelete');
        }
    }
    
    public function lockEditMetaRowSaveModel() {
        
        try {

            $schemaName = self::$schemaName;
                
            $categoryId = Input::numeric('categoryId');
            $rows = json_decode($_POST['selectedRows'], true);

            foreach ($rows as $row) {

                $metaDataId = $row['META_DATA_ID'];
                
                $this->db->Execute("DELETE FROM $schemaName.PWD_CATEGORY_MAP WHERE META_DATA_ID = ".$this->db->Param(0), array($metaDataId));
                
                $data = array(
                    'ID' => getUID(), 
                    'CATEGORY_ID' => $categoryId, 
                    'META_DATA_ID' => $metaDataId
                );
                $this->db->AutoExecute($schemaName.'.PWD_CATEGORY_MAP', $data);
            }

            return array('status' => 'success', 'message' => Lang::line('msg_save_success'));

        } catch (Exception $ex) {
            return array('status' => 'error', 'message' => 'Error - lockEditMetaRowSave');
        }
    }
    
    public function lockWriteLog($data) {
        
        $data['ID'] = getUID();
        $data['CREATED_DATE'] = Date::currentDate('Y-m-d H:i:s');
        
        $schemaName = self::$schemaName;
        $result = $this->db->AutoExecute($schemaName.'.PWD_LOG', $data);
        
        return $result;
    }
    
    public function getLockUserByMetaDataIdModel($metaDataId) {
        
        try {
            
            $userId = $this->db->GetOne("
                SELECT 
                    QU.SYSTEM_USER_ID  
                FROM ".self::$schemaName.".PWD_CERTIFIED_BOOK CB 
                    INNER JOIN ".self::$schemaName.".PWD_QA_USER QU ON QU.ID = CB.CREATED_USER_ID  
                WHERE CB.META_DATA_ID = ".$this->db->Param(0), array($metaDataId)); 

            return $userId;
        } catch (Exception $ex) {
            return null;
        }
    }
    
    public function sendRequestEditModel() {
        
        try {

            $metaDataId = Input::numeric('metaDataId');
            
            if (!$metaDataId) {
                throw new Exception('Invalid id!'); 
            }
            
            $schemaName     = self::$schemaName;
            $licenserUserId = self::getLockUserByMetaDataIdModel($metaDataId);
            $sessionUserId  = Ue::sessionUserId();
            $description    = Input::post('description');
            $endTime        = Input::post('endTime');

            $data = array(
                'ID'               => getUID(), 
                'META_DATA_ID'     => $metaDataId, 
                'DESCRIPTION'      => $description, 
                'LICENSER_USER_ID' => $licenserUserId, 
                'CREATED_USER_ID'  => $sessionUserId, 
                'CREATED_DATE'     => Date::currentDate('Y-m-d H:i:s'), 
                'END_TIME'         => $endTime, 
                'STATUS'           => 'new' 
            );
            $result = $this->db->AutoExecute($schemaName.'.PWD_REQUEST', $data);
            
            if ($result) {
                
                includeLib('Mail/Mail');
                
                $idPh = $this->db->Param(0);
                $lRow = $this->db->GetRow("SELECT PERSON_NAME, EMAIL FROM $schemaName.PWD_QA_USER WHERE SYSTEM_USER_ID = $idPh", array($licenserUserId));
                
                $emailSubject   = 'Үзүүлэлт засах хүсэлт';
                $toPersonName   = $lRow['PERSON_NAME'];
                $toEmail        = $lRow['EMAIL'];
                $fromPersonName = $this->db->GetOne("SELECT USERNAME FROM UM_SYSTEM_USER WHERE USER_ID = $idPh", array($sessionUserId));
                $metaRow        = $this->db->GetRow("SELECT META_DATA_CODE, META_DATA_NAME FROM META_DATA WHERE META_DATA_ID = $idPh", array($metaDataId));
                
                $emailBodyContent = file_get_contents('middleware/views/lock/email_templates/request.html');
                $emailBodyContent = str_replace('{toPersonName}', $toPersonName, $emailBodyContent);
                $emailBodyContent = str_replace('{fromPersonName}', $fromPersonName, $emailBodyContent);
                $emailBodyContent = str_replace('{metaDataCode}', $metaRow['META_DATA_CODE'], $emailBodyContent);
                $emailBodyContent = str_replace('{metaDataName}', $metaRow['META_DATA_NAME'], $emailBodyContent);
                $emailBodyContent = str_replace('{description}', $description, $emailBodyContent);
                $emailBodyContent = str_replace('{endtime}', $endTime, $emailBodyContent);
                $emailBodyContent = str_replace('{directUrl}', URL, $emailBodyContent);
                
                $mailResult = Mail::sendPhpMailer(
                    array(
                        'subject' => $emailSubject, 
                        'altBody' => 'Request - ' . $emailSubject, 
                        'body'    => $emailBodyContent, 
                        'toMail'  => $toEmail 
                    )
                );
                
                if ($mailResult['status'] != 'success') {
                    return array('status' => 'error', 'message' => $mailResult['message']);
                }
            }

            return array('status' => 'success', 'message' => Lang::line('msg_save_success'));

        } catch (Exception $ex) {
            return array('status' => 'error', 'message' => 'Error - sendRequestEdit');
        }
    }
    
    public function requestCountModel() {
        
        try {
            $count = $this->db->GetOne("
                SELECT 
                    COUNT(ID) AS REQ_COUNT   
                FROM ".self::$schemaName.".PWD_REQUEST 
                WHERE STATUS = 'new' 
                    AND LICENSER_USER_ID = ".$this->db->Param(0), 
                array(Ue::sessionUserId())
            ); 

            return $count;
        } catch (Exception $ex) {
            return 0;
        }
    }
    
    public function lockRequestsModel() {
        
        try {
            $data = $this->db->GetAll("
                SELECT 
                    PR.ID, 
                    MD.META_DATA_CODE, 
                    MD.META_DATA_NAME, 
                    PR.DESCRIPTION, 
                    SU.USERNAME, 
                    PR.END_TIME, 
                    PR.CREATED_DATE, 
                    PR.STATUS 
                FROM ".self::$schemaName.".PWD_REQUEST PR 
                    INNER JOIN UM_SYSTEM_USER SU ON SU.USER_ID = PR.CREATED_USER_ID 
                    INNER JOIN META_DATA MD ON MD.META_DATA_ID = PR.META_DATA_ID 
                WHERE PR.LICENSER_USER_ID = ".$this->db->Param(0)." 
                    AND PR.STATUS = 'new' 
                ORDER BY PR.CREATED_DATE DESC", array(Ue::sessionUserId())); 

            return $data;
        } catch (Exception $ex) {
            return array();
        }
    }
    
    public function getLockRequestModel($id) {
        
        try {
            $row = $this->db->GetRow("
                SELECT 
                    PR.ID, 
                    PR.META_DATA_ID, 
                    PR.CREATED_USER_ID, 
                    PR.LICENSER_USER_ID, 
                    SU.USERNAME, 
                    PR.END_TIME 
                FROM ".self::$schemaName.".PWD_REQUEST PR 
                    INNER JOIN UM_SYSTEM_USER SU ON SU.USER_ID = PR.CREATED_USER_ID  
                WHERE PR.ID = ".$this->db->Param(0), array($id)); 

            return $row;
        } catch (Exception $ex) {
            return array();
        }
    }
    
    public function getLockUserByUserIdModel($licenserUserId) {
        
        try {
            $userId = $this->db->GetOne("
                SELECT 
                    ID  
                FROM ".self::$schemaName.".PWD_QA_USER   
                WHERE SYSTEM_USER_ID = ".$this->db->Param(0), 
                array($licenserUserId)
            ); 

            return $userId;
        } catch (Exception $ex) {
            return null;
        }
    }
    
    public function requestAcceptModel() {
        
        try {

            $schemaName = self::$schemaName;

            $licenserUserId = Input::numeric('licenserUserId');
            $realLicenserUserId = self::getLockUserByUserIdModel($licenserUserId);

            $data = array(
                'ID' => getUID(), 
                'USER_ID' => Input::numeric('userId'), 
                'META_DATA_ID' => Input::numeric('metaDataId'), 
                'END_TIME' => Input::post('endTime'), 
                'LICENSER_USER_ID' => $realLicenserUserId, 
                'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s')
            );
            $result = $this->db->AutoExecute($schemaName.'.PWD_PERMISSION_BOOK', $data);
            
            if ($result) {
                
                $logData = array(
                    'META_DATA_ID' => $data['META_DATA_ID'], 
                    'LICENSER_USER_ID' => $realLicenserUserId, 
                    'USER_ID' => $data['USER_ID'], 
                    'END_TIME' => $data['END_TIME'], 
                    'LOG_TYPE' => 'sharelock'
                );
                self::lockWriteLog($logData);
                        
                $this->db->AutoExecute($schemaName.'.PWD_REQUEST', array('STATUS' => 'done'), 'UPDATE', 'ID = '.Input::numeric('id'));
            }

            return array('status' => 'success', 'message' => Lang::line('msg_save_success'));

        } catch (Exception $ex) {
            return array('status' => 'error', 'message' => 'Error - requestAccept');
        }
    }
    
    public function lockHistoryModel() {
        
        try {
            
            $metaId = Input::numeric('metaId');
        
            if ($metaId) {

                $response = array('status' => 'success');
                $schemaName = self::$schemaName;
                $idPh = $this->db->Param(0);

                $metaRow = $this->db->GetRow("
                    SELECT 
                        MD.META_DATA_CODE, 
                        MD.META_DATA_NAME, 
                        MT.META_TYPE_NAME  
                    FROM META_DATA MD 
                        INNER JOIN META_TYPE MT ON MT.META_TYPE_ID = MD.META_TYPE_ID 
                    WHERE MD.META_DATA_ID = $idPh", array($metaId));

                $response['metaRow'] = $metaRow;

                $firstLockRow = $this->db->GetRow("
                    SELECT 
                        CB.DESCRIPTION, 
                        CB.CREATED_DATE, 
                        QU.PERSON_NAME, 
                        QU.EMAIL 
                    FROM $schemaName.PWD_CERTIFIED_BOOK CB 
                        INNER JOIN $schemaName.PWD_QA_USER QU ON QU.ID = CB.CREATED_USER_ID 
                    WHERE CB.META_DATA_ID = $idPh 
                    ORDER BY CB.CREATED_DATE ASC", array($metaId));

                $response['firstLockRow'] = $firstLockRow;

                $historyData = $this->db->GetAll("
                    SELECT 
                        PL.DESCRIPTION, 
                        PL.CREATED_DATE, 
                        PL.LOG_TYPE, 
                        PL.END_TIME, 
                        QU.PERSON_NAME, 
                        QU.EMAIL, 
                        SU.USERNAME 
                    FROM $schemaName.PWD_LOG PL 
                        INNER JOIN $schemaName.PWD_QA_USER QU ON QU.ID = PL.LICENSER_USER_ID 
                        LEFT JOIN $schemaName.PWD_PERMISSION_BOOK PB ON PB.META_DATA_ID = PL.META_DATA_ID 
                            AND PB.LICENSER_USER_ID = PL.LICENSER_USER_ID 
                            AND PL.LOG_TYPE = 'sharelock' 
                            AND PL.END_TIME = PB.END_TIME 
                        LEFT JOIN UM_SYSTEM_USER SU ON SU.USER_ID = PB.USER_ID     
                    WHERE PL.META_DATA_ID = $idPh 
                        AND PL.LOG_TYPE <> 'added' 
                    ORDER BY PL.CREATED_DATE DESC", array($metaId));

                $response['historyData'] = $historyData;

            } else {
                $response = array('status' => 'error', 'message' => 'Invalid id!');
            }
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => 'No data!');
        }
        
        return $response;
    }
    
}