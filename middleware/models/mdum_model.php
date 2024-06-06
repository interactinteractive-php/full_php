<?php

if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdum_Model extends Model {

    const CONST_ACTION_CREATE = '300101010000001';
    const CONST_ACTION_UPDATE = '300101010000002';
    const CONST_ACTION_DELETE = '300101010000003';
    const CONST_ACTION_GET = '300101010000004';
    const CONST_ACTION_RUN = '300101010000005';
    const CONST_ACTION_LIST = '300101010000006';

    public function __construct() {
        parent::__construct();
    }

    public function getRoleModel($roleId) {
        return $this->db->GetRow("SELECT * FROM UM_ROLE WHERE ROLE_ID = " . $roleId);
    }

    public function getUmActionModel() {
        return $this->db->GetAll("SELECT * FROM UM_ACTION");
    }

    public function getUserPermissionTablesModel($strdbStructureId = null) {
        $sql = "SELECT * FROM DB_STRUCTURE WHERE TABLE_DV_ID IS NOT NULL";

        if (!is_null($strdbStructureId)) {
            $sql.=" AND ID = $strdbStructureId";
        }

        $data = $this->db->GetAll($sql);

        return $data;
    }

    public function getRoleUsersModel($roleId = null, $userId = null) {
        
        $page = Input::postCheck('page') ? Input::post('page') : 1;
        $rows = Input::postCheck('rows') ? Input::post('rows') : 10;
        $offset = ($page - 1) * $rows;
        $where = '';
        
        $sortField = ' UR.ID ';
        $sortOrder = 'DESC';
        
        if (Input::postCheck('sort') && Input::postCheck('order')) {
            $sortField = Input::post('sort');
            $sortOrder = Input::post('order');
        }

        if (!is_null($roleId)) {
            
            if (Input::postCheck('filterRules')) {
                $filterRules = json_decode(Str::cp1251_utf8($_POST['filterRules']), true);

                foreach ($filterRules as $rule) {

                    $field = $rule['field'];
                    $value = Input::param(Str::lower($rule['value']));

                    if ($value != '') {
                        if ($field === 'LAST_NAME') {
                            $where .= " AND LOWER(BP.LAST_NAME) LIKE '%$value%'";
                        } elseif ($field === 'FIRST_NAME') {
                            $where .= " AND LOWER(BP.FIRST_NAME) LIKE '%$value%'";
                        } elseif ($field === 'USERNAME') {
                            $where .= " AND LOWER(US.USERNAME) LIKE '%$value%'";
                        } elseif ($field === 'DEPARTMENT_NAME') {
                            $where .= " AND LOWER(OG.DEPARTMENT_NAME) LIKE '%$value%'";
                        } elseif ($field === 'POSITION_NAME') {
                            $where .= " AND LOWER(E.POSITION_NAME) LIKE '%$value%'";
                        } elseif ($field === 'IS_ACTIVE_TXT') {
                            $where .= " AND UR.IS_ACTIVE = $value";
                        } elseif ($field === 'PARENT_DEPARTMENT') {
                            $where .= " AND (LOWER(OG1.DEPARTMENT_NAME) LIKE '%$value%' OR LOWER(OG2.DEPARTMENT_NAME) LIKE '%$value%')";
                        } elseif ($field === 'DEPARTMENT_CODE') {
                            $where .= " AND LOWER(OG.DEPARTMENT_CODE) LIKE '%$value%'";
                        } 
                    }
                }
            }
        
            $mainQr = "FROM UM_SYSTEM_USER US
                INNER JOIN UM_USER U ON US.USER_ID = U.SYSTEM_USER_ID
                INNER JOIN UM_USER_ROLE UR ON U.USER_ID = UR.USER_ID
                LEFT JOIN BASE_PERSON BP ON US.PERSON_ID = BP.PERSON_ID 
                LEFT JOIN VW_EMPLOYEE E ON E.PERSON_ID = BP.PERSON_ID
                LEFT JOIN ORG_DEPARTMENT OG ON OG.DEPARTMENT_ID = U.DEPARTMENT_ID
                LEFT JOIN ORG_DEPARTMENT OG1 ON OG.PARENT_ID = OG1.DEPARTMENT_ID 
                LEFT JOIN ORG_DEPARTMENT OG2 ON OG1.PARENT_ID = OG2.DEPARTMENT_ID 
                LEFT JOIN UM_USER UU ON UR.CREATED_USER_ID = UU.USER_ID
                LEFT JOIN UM_SYSTEM_USER USU ON UU.SYSTEM_USER_ID = USU.USER_ID
                LEFT JOIN BASE_PERSON BP1 ON USU.PERSON_ID = BP1.PERSON_ID
            WHERE US.INACTIVE = 0 AND UR.ROLE_ID = " . $roleId . $where;

            $selectList = "SELECT
                                UR.ID, 
                                UR.IS_ACTIVE,
                                UR.CREATED_DATE, 
                                USU.USERNAME AS CREATED_USER_NAME, 
                                US.USERNAME, 
                                BP.LAST_NAME, 
                                BP.FIRST_NAME, 
                                OG.DEPARTMENT_NAME, 
                                OG.DEPARTMENT_CODE, 
                                CASE WHEN OG1.PARENT_ID IS NULL THEN OG1.DEPARTMENT_NAME 
                                ELSE OG1.DEPARTMENT_NAME ||' - '||OG2.DEPARTMENT_NAME END AS PARENT_DEPARTMENT,
                                E.POSITION_NAME 
                                $mainQr 
                            GROUP BY 
                                UR.ID, 
                                UR.IS_ACTIVE, 
                                US.USERNAME, 
                                BP.LAST_NAME, 
                                BP.FIRST_NAME, 
                                UR.CREATED_DATE, 
                                USU.USERNAME, 
                                OG.DEPARTMENT_CODE,
                                OG.DEPARTMENT_NAME,
                                CASE WHEN OG1.PARENT_ID IS NULL THEN OG1.DEPARTMENT_NAME 
                                ELSE OG1.DEPARTMENT_NAME ||' - '||OG2.DEPARTMENT_NAME END,
                                E.POSITION_NAME       
                            ORDER BY $sortField $sortOrder";
            
        } else {
            
            if (Input::postCheck('filterRules')) {
                $filterRules = json_decode(Str::cp1251_utf8($_POST['filterRules']), true);

                foreach ($filterRules as $rule) {

                    $field = $rule['field'];
                    $value = Input::param(Str::lower($rule['value']));

                    if ($value != '') {
                        if ($field === 'ROLE_CODE') {
                            $where .= " AND LOWER(ROLE.ROLE_CODE) LIKE '%$value%'";
                        } elseif ($field === 'ROLE_NAME') {
                            $where .= " AND LOWER(ROLE.ROLE_NAME) LIKE '%$value%'";
                        } elseif ($field === 'IS_ACTIVE_TXT') {
                            $where .= " AND UR.IS_ACTIVE = $value";
                        } 
                    }
                }
            }
            
            $mainQr = "FROM UM_SYSTEM_USER US
                INNER JOIN UM_USER U ON US.USER_ID = U.SYSTEM_USER_ID
                INNER JOIN UM_USER_ROLE UR ON U.USER_ID = UR.USER_ID
                INNER JOIN UM_ROLE ROLE ON UR.ROLE_ID = ROLE.ROLE_ID
                LEFT JOIN BASE_PERSON BP ON US.PERSON_ID = BP.PERSON_ID
            WHERE US.INACTIVE = 0 AND U.IS_ACTIVE = 1 AND UR.IS_ACTIVE = 1 AND U.USER_ID = " . $userId.$where;

            $selectList = "SELECT
              UR.ID, UR.IS_ACTIVE, ROLE.ROLE_CODE, ROLE.ROLE_NAME
              $mainQr
            ORDER BY $sortField $sortOrder";
        }

        $selectCount = "SELECT COUNT(1) AS ROW_COUNT " . $mainQr;

        $rowCount = $this->db->GetRow($selectCount);
        
        $result = array();
        $result['total'] = $rowCount['ROW_COUNT'];
        $result['rows'] = array();

        if ($result['total'] > 0) {
            $rs = $this->db->SelectLimit($selectList, $rows, $offset);
            $result['rows'] = $rs->_array;
        }
        
        return $result;
    }

    public function getUserListModel($roleId, $txt) {
        
        $this->db->StartTrans();
        $this->db->Execute(Ue::createSessionInfo());

        $sqlData = $this->db->SelectLimit("SELECT U.USER_ID, US.USERNAME, BP.LAST_NAME, BP.FIRST_NAME, VE.DEPARTMENT_NAME "
                . "FROM UM_SYSTEM_USER US "
                . "INNER JOIN UM_USER U ON US.USER_ID = U.SYSTEM_USER_ID "
                . "LEFT JOIN BASE_PERSON BP ON US.PERSON_ID = BP.PERSON_ID "
                . "LEFT JOIN ORG_DEPARTMENT VE ON U.DEPARTMENT_ID = VE.DEPARTMENT_ID "
                . "WHERE (US.INACTIVE = 0 OR US.INACTIVE IS NULL) AND U.IS_ACTIVE = 1 AND "
                . "U.USER_ID NOT IN(SELECT DISTINCT USER_ID FROM UM_USER_ROLE WHERE ROLE_ID = " . $roleId . ") AND "
                . "(LOWER(US.USERNAME) LIKE LOWER('" . $txt . "%') OR LOWER(BP.LAST_NAME) LIKE LOWER('" . $txt . "%') OR LOWER(BP.FIRST_NAME) LIKE LOWER('" . $txt . "%'))",
                30, -1);

        $this->db->CompleteTrans();

        if (isset($sqlData->_array)) {
            return $sqlData->_array;
        }

        return array();
    }

    public function saveRoleUserModel($roleId, $userId) {

        $result = $this->db->GetRow("SELECT ID FROM UM_USER_ROLE WHERE USER_ID = $userId AND ROLE_ID = $roleId");
        
        if (count($result)) {
            if ($result['IS_ACTIVE'] == 1) {
                return array('status' => 'success', 'message' => "Дүр дээр уг хэрэглэгч өмнө нь нэмэгдсэн байгаа тул ахин нэмэх боломжгүй");
            } else {
                return array('status' => 'success', 'message' => "Дүр дээр хэрэглэгч өмнө нэмэгдсэн ба тохиргоо нь идэвхигүй байгаа тул нэмэх боломжгүй. Жагсаалтаас тохиргоог идэвхитэй болгоно уу.");
            }
        }

        $data = array(
            'ID' => getUID(),
            'USER_ID' => $userId,
            'ROLE_ID' => $roleId,
            'IS_ACTIVE' => 1,
            'CREATED_USER_ID' => Ue::sessionUserKeyId(),
            'CREATED_DATE' => Date::currentDate()
        );

        $result = $this->db->AutoExecute('UM_USER_ROLE', $data);

        if ($result) {
            return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
        }
        return array('status' => 'warning', 'message' => Lang::line('msg_save_error'));
    }
    
    public function saveRoleUserMultiModel() {
        
        $sessionUserKeyId = Ue::sessionUserKeyId();
        $currentDate = Date::currentDate();
        $roleId = Input::post('roleId');
        $rows = $_POST['rows'];
        
        foreach ($rows as $row) {
            
            $userId = $row['id'];
            
            if ($userId) {
                
                $existRow = $this->db->GetRow("SELECT ID FROM UM_USER_ROLE WHERE USER_ID = $userId AND ROLE_ID = $roleId");
            
                if (!isset($existRow['ID'])) {

                    $data = array(
                        'ID' => getUID(),
                        'USER_ID' => $userId,
                        'ROLE_ID' => $roleId,
                        'IS_ACTIVE' => 1,
                        'CREATED_USER_ID' => $sessionUserKeyId,
                        'CREATED_DATE' => $currentDate
                    );
                    $this->db->AutoExecute('UM_USER_ROLE', $data);
                }
            }
        }
        
        return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
    }

    public function changeIsActiveModel($id, $isActive) {
        $data = array(
            'IS_ACTIVE' => !$isActive,
            'MODIFIED_USER_ID' => Ue::sessionUserKeyId(),
            'MODIFIED_DATE' => Date::currentDate()
        );
        $result = $this->db->AutoExecute('UM_USER_ROLE', $data, 'UPDATE', 'ID = ' . $id);
        if ($result) {
            return array('status' => 'success', 'message' => 'Амжилттай хадгаллаа');
        } else {
            return array('status' => 'error', 'message' => 'Алдаа гарлаа');
        }
    }
    
    public function roleUserRemoveModel() {
        $id = Input::numeric('id');
        
        if ($id) {
            
            try {
                
                $this->db->Execute("DELETE FROM UM_USER_ROLE WHERE ID = ".$this->db->Param(0), array($id));
                $result = array('status' => 'success', 'message' => $this->lang->line('msg_delete_success'));
                
            } catch (Exception $ex) {
                $result = array('status' => 'error', 'message' => $ex->getMessage());
            }
            
        } else {
            $result = array('status' => 'error', 'message' => 'Invalid id!');
        }
        
        return $result;
    }

    public function getMetaTypeIdModel($srcMetaDataId) {
        return $this->db->GetOne("SELECT META_TYPE_ID FROM META_DATA WHERE META_DATA_ID = " . $srcMetaDataId);
    }

    public function getChildTreeMetasModel($isSaved, $srcMetaDataId, $roleId, $userId, $metaTypeId, $isDenied, $searchText, $debug = NULL) {
        if (!empty($searchText)) {
            $metaTypeId = null;
        }
        $param = array(
            'isSaved'       => $isSaved, 
            'srcMetaDataId' => $srcMetaDataId, 
            'roleId'        => $roleId,
            'userId'        => $userId, 
            'metaTypeId'    => $metaTypeId, 
            'isDenied'      => $isDenied,
            'searchText'    => $searchText . '%'
        );

        $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'getChildTreeMetasModel', $param);

        if (isset($result['status']) && $result['status'] == 'success' && isset($result['result'])) {
            return $result['result'];
        } else {
            return array();
        }
    }

    public function createMetaPermissionModel() {

        $roleId = Input::post('roleId', null);
        $userId = Input::post('userId', null);
        $isDenied = Input::post('isDenied', 0);
        $checkedIdList = isset($_POST['checkedIdList']) ? $_POST['checkedIdList'] : null;

        $param = array(
            'roleId' => $roleId,
            'userId' => $userId,
            'checkedIdList' => $checkedIdList,
            'isDenied' => $isDenied
        );

        $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'createMetaPermission', $param);
        
        if ($result['status'] == 'success') {
            $result = array('status' => 'success', 'message' => Lang::line('msg_save_success'));
        } else {
            $result = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }
        
        return $result;
    }

    public function deleteMetaPermissionModel() {

        $roleId = Input::post('roleId');
        $userId = Input::post('userId');
        $isDenied = Input::post('isDenied', 0);
        $checkedDataList = isset($_POST['checkedDataList']) ? $_POST['checkedDataList'] : null;

        foreach ($checkedDataList as $key => $value) {
            $splitedMetaDataId = explode('-', $value['metaDataId']);
            $checkedDataList[$key]['metaDataId'] = $splitedMetaDataId[0];
        }

        $param = array(
            'roleId' => $roleId,
            'userId' => $userId,
            'checkedDataList' => $checkedDataList,
            'isDenied' => $isDenied
        );

        $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'deleteChildTreeMetasModel', $param);

        if (isset($result['status']) && $result['status'] == 'success') {
            return true;
        } else {
            return false;
        }
    }

    public function getPermissionCriteriaModel($permissionId) {
        return $this->db->GetRow("SELECT FIELD_CRITERIA, RECORD_CRITERIA FROM UM_META_PERMISSION WHERE PERMISSION_ID = " . $permissionId);
    }

    public function getUsersModel($txt) {

        $sqlData = $this->db->SelectLimit("SELECT U.USER_ID, US.USERNAME, BP.LAST_NAME, BP.FIRST_NAME "
                . "FROM UM_SYSTEM_USER US "
                . "INNER JOIN UM_USER U ON US.USER_ID = U.SYSTEM_USER_ID "
                . "LEFT JOIN BASE_PERSON BP ON US.PERSON_ID = BP.PERSON_ID "
                . "WHERE US.INACTIVE = 0 AND U.IS_ACTIVE = 1 AND "
                . "(LOWER(US.USERNAME) LIKE LOWER('" . $txt . "%') OR LOWER(BP.LAST_NAME) LIKE LOWER('" . $txt . "%') OR LOWER(BP.FIRST_NAME) LIKE LOWER('" . $txt . "%'))",
                30, -1);

        if (isset($sqlData->_array)) {
            return $sqlData->_array;
        }

        return array();
    }

    public function getRolesByUserIdModel($isSaved, $userId) {
        $where = "";
        $join = " INNER ";
        $select = $qr = "";
        if ($isSaved == 1) {
            $qr = "SELECT DISTINCT R.*, UR.ID AS USER_ROLE_ID "
                    . "FROM UM_ROLE R "
                    . "INNER JOIN UM_USER_ROLE UR ON R.ROLE_ID = UR.ROLE_ID "
                    . "WHERE UR.IS_ACTIVE = 1 AND UR.USER_ID = " . $userId;
        } else {
            $qr = "SELECT DISTINCT R.*, NULL AS USER_ROLE_ID "
                    . "FROM UM_ROLE R "
                    . "WHERE R.ROLE_ID NOT IN"
                    . "("
                    . "SELECT DISTINCT ROLE_ID "
                    . "FROM UM_USER_ROLE UR "
                    . "WHERE UR.IS_ACTIVE = 1 "
                    . "AND UR.USER_ID = " . $userId
                    . ")";
        }

        $this->db->StartTrans();
        $this->db->Execute(Ue::createSessionInfo());

        $sqlData = $this->db->GetAll($qr);

        $this->db->CompleteTrans();

        return $sqlData;
    }

    public function saveUserDataPermissionModel() {
        
        try {
            
            $roleId = Input::post('roleId');
            $userId = Input::post('userId');
            $dbStructureId = Input::post('strId');
            $hierarchy = Input::post('isHierarchy');
            $rows = $_POST['rows'];
            $umActionList = $this->getUmActionModel();

            foreach ($rows as $row) {

                if (!is_null($umActionList) && isset($row['actionname'])) {
                    $actionName = $row['actionname'];

                    foreach ($umActionList as $action) {
                        $actionId = $action['ACTION_ID'];

                        if (strpos($actionName, 'btn-success" actionid="' . $actionId) !== false) {
                            $this->saveUserDataPermission($userId, $roleId, $dbStructureId, $row['id'], $actionId, $hierarchy);
                        } else {
                            $this->deleteExist($userId, $roleId, $dbStructureId, $row['id'], $actionId, $hierarchy);
                        }
                    }

                } else {
                    $actionId = Input::post('actionId');

                    if (!is_null($actionId) && !is_null($hierarchy)) {

                        $isExist = $this->checkIsEsixt($userId, $roleId, $dbStructureId, $row['id'], $actionId, $hierarchy);

                        if ($isExist > 0) {
                            continue;
                        }
                    }

                    $this->saveUserDataPermission($userId, $roleId, $dbStructureId, $row['id'], $actionId, $hierarchy);
                }
            }
        
            return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
            
        } catch (Exception $ex) {
            
            return array('status' => 'error', 'message' => $ex->getMessage());
        }
    }

    public function saveUserDataPermissionToUserModel() {
        $dbStructureId = Input::post('dbStructureId');
        $recordId = Input::post('recordId');
        $hierarchy = 0;
        $rows = $_POST['rows'];
        $umActionList = $this->getUmActionModel();

        foreach ($rows as $row) {
            if (!is_null($umActionList) && isset($row['actionname'])) {
                $actionName = $row['actionname'];

                foreach ($umActionList as $action) {
                    $actionId = $action['ACTION_ID'];
                    $userOrRoleId = $row['userid'];
                    if ($row['datatype'] == 'Дүр') {
                        if (strpos($actionName, 'btn-success" actionid="' . $actionId) !== false) {
                            $this->deleteExist(null, $userOrRoleId, $dbStructureId, $recordId, $actionId, $hierarchy);
                            $this->saveUserDataPermission(null, $userOrRoleId, $dbStructureId, $recordId, $actionId, $hierarchy);
                        } else {
                            $this->deleteExist(null, $userOrRoleId, $dbStructureId, $recordId, $actionId, $hierarchy);
                        }
                    } else {
                        if (strpos($actionName, 'btn-success" actionid="' . $actionId) !== false) {
                            $this->deleteExist($userOrRoleId, null, $dbStructureId, $recordId, $actionId, $hierarchy);
                            $this->saveUserDataPermission($userOrRoleId, null, $dbStructureId, $recordId, $actionId, $hierarchy);
                        } else {
                            $this->deleteExist($userOrRoleId, null, $dbStructureId, $recordId, $actionId, $hierarchy);
                        }
                    }
                }
            } else {
                $actionId = Input::post('actionId');
                $userId = $roleId = null;

                if (Input::postCheck('isUser')) {
                    $userId = $row['id'];
                } else {
                    $roleId = $row['id'];
                }


                if (!is_null($actionId) && !is_null($hierarchy)) {

                    $isExist = $this->checkIsEsixt($userId, $roleId, $dbStructureId, $recordId, $actionId, $hierarchy);

                    if ($isExist > 0) {
                        continue;
                    }
                }

                $this->saveUserDataPermission($userId, $roleId, $dbStructureId, $recordId, $actionId, $hierarchy);
            }
        }

        return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
    }

    private function checkIsEsixt($userId, $roleId, $dbStructureId, $id, $actionId, $hierarchy) {
        $isExist = $this->db->GetOne("SELECT COUNT(1) FROM UM_USER_DATA_PERMISSION "
                . "WHERE USER_ID " . ($userId == '' ? " IS NULL " : " = " . $userId)
                . " AND ROLE_ID " . ($roleId == '' ? " IS NULL " : " = " . $roleId)
                . " AND DB_STRUCTURE_ID = " . $dbStructureId
                . " AND RECORD_ID = " . $id
                . " AND ACTION_ID = " . $actionId
            );

        return $isExist;
    } 

    private function checkIsEsixtActionNull($userId, $roleId, $dbStructureId, $id) {
        $isExist = $this->db->GetOne("SELECT COUNT(1) FROM UM_USER_DATA_PERMISSION "
                . "WHERE USER_ID " . ($userId == '' ? " IS NULL " : " = " . $userId)
                . " AND ROLE_ID " . ($roleId == '' ? " IS NULL " : " = " . $roleId)
                . " AND DB_STRUCTURE_ID = " . $dbStructureId
                . " AND RECORD_ID = " . $id
                . " AND ACTION_ID IS NULL"
            );

        return $isExist;
    }

    private function deleteExist($userId, $roleId, $dbStructureId, $id, $actionId, $hierarchy) {

        $this->db->Execute("UPDATE UM_USER_DATA_PERMISSION SET IS_ACTIVE = 0, MODIFIED_DATE = '" . Date::currentDate() . "', MODIFIED_USER_ID = " . Ue::sessionUserKeyId() . " "
            . "WHERE USER_ID " . ($userId == '' ? " IS NULL " : " = " . $userId)
            . " AND ROLE_ID " . ($roleId == '' ? " IS NULL " : " = " . $roleId)
            . " AND DB_STRUCTURE_ID = " . $dbStructureId
            . " AND RECORD_ID = " . $id
            . " AND ACTION_ID = " . $actionId
            . " AND IS_ACTIVE = 1"
        );
    }

    private function saveUserDataPermission($userId, $roleId, $dbStructureId, $id, $actionId, $hierarchy) {
        if (!empty($actionId)) {
            $isExist = $this->checkIsEsixt($userId, $roleId, $dbStructureId, $id, $actionId, $hierarchy);
        } else {
            $isExist = 0;
        }

        if ($isExist > 0) {
            $this->db->Execute("UPDATE UM_USER_DATA_PERMISSION SET IS_ACTIVE = 1, IS_HIERARCHY = ".(!is_null($hierarchy) ? $hierarchy : '0').", MODIFIED_DATE = '" . Date::currentDate() . "', MODIFIED_USER_ID = " . Ue::sessionUserKeyId() . " "
                . "WHERE USER_ID " . ($userId == '' ? " IS NULL " : " = " . $userId)
                . " AND ROLE_ID " . ($roleId == '' ? " IS NULL " : " = " . $roleId)
                . " AND DB_STRUCTURE_ID = " . $dbStructureId
                . " AND RECORD_ID = " . $id
                . " AND ACTION_ID = " . $actionId
                . " AND IS_ACTIVE = 0"
            );

        } else {

            $isExistAction = $this->checkIsEsixtActionNull($userId, $roleId, $dbStructureId, $id);

            if ($isExistAction > 0 && empty($actionId)) {
                $this->db->Execute("UPDATE UM_USER_DATA_PERMISSION SET IS_ACTIVE = 1, MODIFIED_DATE = '" . Date::currentDate() . "', MODIFIED_USER_ID = " . Ue::sessionUserKeyId() . " "
                    . "WHERE USER_ID " . ($userId == '' ? " IS NULL " : " = " . $userId)
                    . " AND ROLE_ID " . ($roleId == '' ? " IS NULL " : " = " . $roleId)
                    . " AND DB_STRUCTURE_ID = " . $dbStructureId
                    . " AND RECORD_ID = " . $id
                    . " AND ACTION_ID IS NULL "
                    . " AND IS_ACTIVE = 0"
                );
            } else {
                $data = array(
                    'ID' => getUID(),
                    'USER_ID' => $userId,
                    'ROLE_ID' => $roleId,
                    'DB_STRUCTURE_ID' => $dbStructureId,
                    'RECORD_ID' => $id,
                    'ACTION_ID' => $actionId,
                    'IS_HIERARCHY' => !is_null($hierarchy) ? $hierarchy : '0',
                    'IS_ACTIVE' => 1,
                    'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                    'CREATED_DATE' => Date::currentDate()
                );

                $this->db->AutoExecute('UM_USER_DATA_PERMISSION', $data);           
            }
        }
    }

    public function removeUserDataPermissionModel($param) {
        $roleId = Input::post('roleId');
        $userId = Input::post('userId');
        $dbStructureId = Input::post('strId');

        foreach ($param as $value) {
            $this->db->Execute("UPDATE UM_USER_DATA_PERMISSION SET IS_ACTIVE = 0, MODIFIED_DATE = '" . Date::currentDate() . "', MODIFIED_USER_ID = " . Ue::sessionUserKeyId() . " "
                    . "WHERE USER_ID " . ($userId == '' ? " IS NULL " : " = " . $userId)
                    . " AND ROLE_ID " . ($roleId == '' ? " IS NULL " : " = " . $roleId)
                    . " AND DB_STRUCTURE_ID = " . $dbStructureId
                    . " AND RECORD_ID = " . $value
                    . " AND IS_ACTIVE = 1");
        }

        return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
    }

    public function enableUserDataPermissionModel($param) {
        $roleId = Input::post('roleId');
        $userId = Input::post('userId');
        $dbStructureId = Input::post('strId');

        foreach ($param as $value) {
            $this->db->Execute("UPDATE UM_USER_DATA_PERMISSION SET IS_ACTIVE = 1, MODIFIED_DATE = '" . Date::currentDate() . "', MODIFIED_USER_ID = " . Ue::sessionUserKeyId() . " "
                    . "WHERE USER_ID " . ($userId == '' ? " IS NULL " : " = " . $userId)
                    . " AND ROLE_ID " . ($roleId == '' ? " IS NULL " : " = " . $roleId)
                    . " AND DB_STRUCTURE_ID = " . $dbStructureId
                    . " AND RECORD_ID = " . $value
                    . " AND IS_ACTIVE = 0");
        }

        return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
    }

    public function removeUserDataPermissionFinModel($param) {
        $recordId = Input::post('recordId');
        $dbStructureId = Input::post('strId');

        foreach ($param as $value) {
            $this->db->Execute("DELETE FROM UM_USER_DATA_PERMISSION "
                    . "WHERE USER_ID " . (Str::upper($value['datatype']) == Str::upper('Дүр') ? " IS NULL " : " = " . $value['userid'])
                    . " AND ROLE_ID " . (Str::upper($value['datatype']) == Str::upper('Хэрэглэгч') ? " IS NULL " : " = " . $value['userid'])
                    . " AND DB_STRUCTURE_ID = " . $dbStructureId
                    . " AND RECORD_ID = " . $recordId);
        }

        return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
    }

    public function setRoleToUserModel() {
        $roleId = Input::post('roleId');
        $userId = Input::post('userId');
        $checkedDataList = isset($_POST['checkedDataList']) ? $_POST['checkedDataList'] : NULL;

        foreach ($checkedDataList as $value) {
            $checkRole = $this->db->GetRow("SELECT * FROM UM_USER_ROLE WHERE USER_ID = " . $userId . " AND ROLE_ID = " . $value['ROLE_ID'] . " AND IS_ACTIVE = 1");
            if (count($checkRole) == 0) {
                $data = array(
                    'ID' => getUID(),
                    'USER_ID' => $userId,
                    'ROLE_ID' => $value['ROLE_ID'],
                    'IS_ACTIVE' => 1,
                    'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                    'CREATED_DATE' => Date::currentDate()
                );

                $result = $this->db->AutoExecute('UM_USER_ROLE', $data, 'INSERT');
            }
        }

        return true;
    }

    public function getCriteriaListByDataviewModel($id) {
        if (is_null(Input::post('userId', NULL)) && !is_null(Input::post('roleId', NULL))) {
            return $this->db->GetAll(
                "SELECT DISTINCT
                    AA.ID, 
                    AA.CODE, 
                    AA.NAME, 
                    AA.DESCRIPTION, 
                    AA.META_DATA_ID, 
                    AA.CRITERIA_STRING,
                    (CASE WHEN CC.PERMISSION_ID IS NULL THEN 0 ".$this->db->IfNull('BB.CRITERIA_ID', '0')." END) AS CHECKED_VAL,
                    (CASE WHEN CC.PERMISSION_ID IS NULL THEN 0 ELSE BB.BATCH_NUMBER END) AS BATCH_NUMBER
                FROM UM_CRITERIA AA
                LEFT JOIN UM_META_PERM_CRITERIA BB ON BB.CRITERIA_ID = AA.ID
                LEFT JOIN UM_META_PERMISSION CC ON CC.PERMISSION_ID = BB.PERMISSION_ID AND CC.ROLE_ID = " . Input::post('roleId') .
                        " WHERE AA.META_DATA_ID = " . $id .
                        " ORDER BY AA.NAME"
            );
        } else if (!is_null(Input::post('userId', NULL)) && is_null(Input::post('roleId', NULL))) {
            return $this->db->GetAll(
                            "SELECT DISTINCT
                    AA.ID, 
                    AA.CODE, 
                    AA.NAME, 
                    AA.DESCRIPTION, 
                    AA.META_DATA_ID, 
                    AA.CRITERIA_STRING,
                    (CASE WHEN CC.PERMISSION_ID IS NULL THEN 0 ELSE ".$this->db->IfNull('BB.CRITERIA_ID', '0')." END) AS CHECKED_VAL,
                    (CASE WHEN CC.PERMISSION_ID IS NULL THEN 0 ELSE BB.BATCH_NUMBER END) AS BATCH_NUMBER
                FROM UM_CRITERIA AA
                LEFT JOIN UM_META_PERM_CRITERIA BB ON BB.CRITERIA_ID = AA.ID
                LEFT JOIN UM_META_PERMISSION CC ON CC.PERMISSION_ID = BB.PERMISSION_ID AND CC.USER_ID = " . Input::post('userId') .
                            " WHERE AA.META_DATA_ID = " . $id .
                            " ORDER BY AA.NAME"
            );
        }
    }

    // <editor-fold defaultstate="collapsed" desc="Um Meta Permission">
    public function getUmMetaPermissionModel($selectedRow) {
        $sql = "SELECT MP.PERMISSION_ID,
                    MP.META_DATA_ID,
                    MP.ACTION_ID,
                    MP.USER_ID,
                    MP.RECORD_CRITERIA,
                    MP.BATCH_NUMBER
                FROM UM_META_PERMISSION MP
                    WHERE MP.META_DATA_ID = " . $selectedRow['tabledvid'] . "
                    AND MP.ACTION_ID      = " . self::CONST_ACTION_LIST;


        if (isset($selectedRow['userid'])) {
            $sql.=" AND MP.USER_ID = " . $selectedRow['userid'];
        }

        if (isset($selectedRow['roleid'])) {
            $sql.=" AND MP.ROLE_ID = " . $selectedRow['roleid'];
        }

        $data = $this->db->GetRow($sql);

        $result = array();
        if (isset($data['RECORD_CRITERIA']) && !is_null($data['RECORD_CRITERIA']) && !is_null($data['BATCH_NUMBER'])) {
            $recordCriteria = str_replace(array('[', ']', '(', ')'), '', $data['RECORD_CRITERIA']);
            $recordCriteriaSplited = preg_split('/(\  OR  |  AND  )/', $recordCriteria);
            $batchNumberSplited = explode(',', $data['BATCH_NUMBER']);

            $recordCriteriaResult = array();
            foreach ($recordCriteriaSplited as $key => $value) {
                $criteriaValueSplited = explode('AND', $value);
                $recordCriteriaResult[$key] = array();
                foreach ($criteriaValueSplited as $val) {
                    $tmp = explode(' ', $val);
                    $filteredCriterias = array_values(array_filter($tmp,
                                    function($value) {
                                return $value !== '';
                            }));
                    $recordCriteriaResult[$key][$filteredCriterias[0]] = $filteredCriterias;
                }
            }

            $result = array(
                'recordCriteriaResult' => $recordCriteriaResult,
                'batchNumberSplited' => $batchNumberSplited,
            );
        }

        return $result;
    }

    public function saveDataPermissionCriteriaModel() {
        $paramName = Input::post('paramName');

        if (!is_null($paramName)) {
            $paramAction = $_POST['paramAction'];
            $paramValue = Input::post('paramValue');
            $tableDvId = Input::post('tableDvId');
            $userId = Input::post('userId');
            $roleId = Input::post('roleId');
            $batchNumber = Input::post('batchNumber');

            $this->deleteExistUmMetaPermission($userId, $roleId, $tableDvId);

            $criteriaStr = '(';
            $prevCondition = '';
            $cnt = count($paramName);
            foreach ($paramName as $key => $value) {
                if (isset($batchNumber[$key + 1]) && $batchNumber[$key] == $batchNumber[$key + 1]) {
                    $condition = ' OR  ';
                } else {
                    $condition = ' AND  ';
                }

                $criteriaStr.= '(';
                $hasParamValue = false;
                foreach ($value as $index => $val) {
                    if ($paramValue[$key][$index] != '') {
                        $criteriaStr.="[" . $val . "] " . $paramAction[$key][$index] . " '" . $paramValue[$key][$index] . "' AND ";
                        $hasParamValue = true;
                    }
                }
                $criteriaStr = trim($criteriaStr, ' AND');
                $criteriaStr.= ')';

                if (!$hasParamValue) {
                    $criteriaStr = str_replace(' ' . $prevCondition . '()', '', $criteriaStr);
                }

                if (($key + 1) != $cnt) {
                    $criteriaStr.= ' ' . $condition;
                }

                $prevCondition = $condition;
            }

            $criteriaStr.= ')';

            $dataInsert = array(
                'PERMISSION_ID' => getUID(),
                'META_DATA_ID' => $tableDvId,
                'ACTION_ID' => self::CONST_ACTION_LIST,
                'USER_ID' => $userId,
                'ROLE_ID' => $roleId,
                'RECORD_CRITERIA' => $criteriaStr,
                'BATCH_NUMBER' => implode(',', $batchNumber),
                'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                'CREATED_DATE' => Date::currentDate()
            );

            $this->db->AutoExecute('UM_META_PERMISSION', $dataInsert);

            $response = array(
                'message' => Lang::line('msg_save_success'),
                'status' => 'success',
            );
        } else {
            $response = array(
                'message' => Lang::line('msg_error'),
                'status' => 'error'
            );
        }

        return $response;
    }

    // </editor-fold>

    public function changePasswordRowsModel() {

        $data = $this->db->GetAll("
            SELECT 
                EMP.EMPLOYEE_CODE, 
                BP.LAST_NAME, 
                BP.FIRST_NAME, 
                BP.STATE_REG_NUMBER, 
                UM.SYSTEM_USER_ID   
            FROM UM_USER_ROLE UR 
                INNER JOIN UM_USER UM ON UM.USER_ID = Ur.USER_ID 
                INNER JOIN UM_SYSTEM_USER US ON US.USER_ID = UM.SYSTEM_USER_ID 
                INNER JOIN HRM_EMPLOYEE EMP ON EMP.PERSON_ID = US.PERSON_ID 
                INNER JOIN BASE_PERSON BP ON BP.PERSON_ID = US.PERSON_ID 
            WHERE UR.ROLE_ID = 1488416627208  
            GROUP BY EMP.EMPLOYEE_CODE, BP.LAST_NAME, BP.FIRST_NAME, BP.STATE_REG_NUMBER, UM.SYSTEM_USER_ID 
            ORDER BY BP.FIRST_NAME ASC");

        $rows = '';

        foreach ($data as $row) {

            $newPassword = Str::random_string('alnum', 6);

            $updateData = array(
                'USERNAME' => $row['EMPLOYEE_CODE'],
                'PASSWORD_HASH' => Hash::create('sha256', $newPassword)
            );

            $updateResult = $this->db->AutoExecute('UM_SYSTEM_USER', $updateData, 'UPDATE', 'USER_ID = ' . $row['SYSTEM_USER_ID']);

            if ($updateResult) {
                $rows .= '<tr>
                    <td class="text">' . $row['EMPLOYEE_CODE'] . '</td>
                    <td class="text">' . $row['LAST_NAME'] . '</td>
                    <td class="text">' . $row['FIRST_NAME'] . '</td>
                    <td class="text">' . $row['STATE_REG_NUMBER'] . '</td>
                    <td class="text">' . $newPassword . '</td>
                </tr>';
            }
        }

        return $rows;
    }

}
