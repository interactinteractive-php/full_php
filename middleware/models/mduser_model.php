<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');
    
class Mduser_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function getSystemActiveUsers() {
        $data = $this->db->GetAll("
            SELECT 
                USER_ID, 
                USERNAME  
            FROM UM_SYSTEM_USER  
            WHERE (INACTIVE = 0 OR INACTIVE IS NULL) 
                AND USERNAME IS NOT NULL 
            ORDER BY USERNAME ASC");

        return $data;
    }

    public function getUmObjectByCode($code)
    {
        $row = $this->db->GetRow("
            SELECT 
                OBJECT_ID, 
                NAME 
            FROM UM_OBJECT 
            WHERE LOWER(CODE) = ".$this->db->Param(0), 
            array(Str::lower($code))
        );
        
        return $row;
    }

    public function getUmPermissionByObjectId($objectId)
    {
        $data = $this->db->GetAll("
            SELECT 
                UP.PERMISSION_ID, UP.NAME     
            FROM UM_PERMISSION UP 
                INNER JOIN UM_OPERATION UO ON UO.OPERATION_ID = UP.OPERATION_ID 
            WHERE UP.OBJECT_ID = ".$this->db->Param(0)." 
            ORDER BY UO.OPERATION_ID ASC", array($objectId));
        return $data;
    }

    public function getSavedUmUserByObjectId($objectId, $recordId)
    {
        $data = $this->db->GetAll("
            SELECT 
                UM.USER_ID, 
                BP.FIRST_NAME, 
                BP.LAST_NAME 
            FROM UM_SYSTEM_USER UM 
                INNER JOIN UM_RECORD_PERMISSION RP ON RP.USER_ID = UM.USER_ID 
                INNER JOIN UM_PERMISSION UP ON UP.PERMISSION_ID = RP.PERMISSION_ID 
                INNER JOIN BASE_PERSON BP ON BP.PERSON_ID = UM.PERSON_ID 
            WHERE RP.OBJECT_ID = ".$this->db->Param(0)." 
                AND RP.RECORD_ID = ".$this->db->Param(1)." 
            GROUP BY 
                UM.USER_ID, 
                BP.FIRST_NAME, 
                BP.LAST_NAME 
            ORDER BY BP.FIRST_NAME ASC", array($objectId, $recordId));
        
        return $data;
    }

    public function getSavedUmRecordPermissionByObjectId($objectId, $recordId)
    {
        $data = $this->db->GetAll("
            SELECT 
                UM.USER_ID, 
                RP.ROLE_ID, 
                RP.RECORD_ID, 
                RP.PERMISSION_ID, 
                RP.OBJECT_ID 
            FROM UM_SYSTEM_USER UM 
                INNER JOIN UM_RECORD_PERMISSION RP ON RP.USER_ID = UM.USER_ID 
                INNER JOIN UM_PERMISSION UP ON UP.PERMISSION_ID = RP.PERMISSION_ID 
            WHERE RP.OBJECT_ID = ".$this->db->Param(0)." 
                AND RP.RECORD_ID = ".$this->db->Param(1), array($objectId, $recordId));
        
        return $data;
    }

    public function userDataGridModel()
    {
        $page = Input::post('page', 1);
        $rows = Input::post('rows', 10);
        $offset = ($page - 1) * $rows;

        $where = "WHERE USERNAME IS NOT NULL";
        
        if (Input::postCheck('filterRules')) {
            $filterRules = json_decode(Str::cp1251_utf8($_POST['filterRules']), true);

            foreach ($filterRules as $rule) {
                
                $field = $rule['field'];
                $value = Input::param(Str::lower($rule['value']));
                
                if (!empty($value)) {
                    if ($field == 'LAST_NAME') {
                        $where .= " AND LOWER(TRIM(LAST_NAME)) LIKE '%$value%'";
                    } 
                    if ($field == 'FIRST_NAME') {
                        $where .= " AND LOWER(TRIM(FIRST_NAME)) LIKE '%$value%'";
                    } 
                    if ($field == 'USERNAME') {
                        $where .= " AND LOWER(TRIM(USERNAME)) LIKE '%$value%'";
                    } 
                    if ($field == 'STATE_REG_NUMBER') {
                        $where .= " AND LOWER(TRIM(STATE_REG_NUMBER)) LIKE '%$value%'";
                    }
                    if ($field == 'DEPARTMENT_NAME') {
                        $where .= " AND LOWER(TRIM(DEPARTMENT_NAME)) LIKE '%$value%'";
                    }
                }
            }
        }

        $sortField = 'FIRST_NAME';
        $sortOrder = 'ASC';
        
        if (Input::postCheck('sort') && Input::postCheck('order')) {
            $sortField = Input::post('sort');
            $sortOrder = Input::post('order');
        }

        $rowCount = $this->db->GetRow(
            "SELECT 
                COUNT(USER_ID) AS ROW_COUNT 
            FROM VW_USER 
            ".$where); 

        $selectList = "SELECT 
                           USER_ID, 
                           USERNAME, 
                           PERSON_ID, 
                           LAST_NAME, 
                           FIRST_NAME, 
                           STATE_REG_NUMBER, 
                           DEPARTMENT_NAME 
                       FROM VW_USER 
                       ".$where." 
                       ORDER BY $sortField $sortOrder";

        $result = $items = array();
        $result['total'] = $rowCount['ROW_COUNT'];
        $result['rows'] = array();

        if ($result['total'] > 0) {
            $rs = $this->db->SelectLimit($selectList, $rows, $offset);
            $result['rows'] = $rs->_array;
        }

        return $result;
    }

    public function isCheckObjectPermissionModel($objectCode, $operationName, $recordId, $userId)
    {
        $data = $this->db->GetAll("
            SELECT 
                RP.PERMISSION_ID 
            FROM UM_RECORD_PERMISSION RP 
                INNER JOIN UM_PERMISSION UP ON UP.PERMISSION_ID = RP.PERMISSION_ID 
                INNER JOIN UM_OBJECT OBJ ON OBJ.OBJECT_ID = RP.OBJECT_ID  
            WHERE LOWER(OBJ.CODE) = ".$this->db->Param(0)." 
                AND RP.RECORD_ID = ".$this->db->Param(1), 
            array(Str::lower($objectCode), $recordId)
        );

        if (count($data) > 0) {
            
            $row = $this->db->GetRow("
                SELECT 
                    RP.PERMISSION_ID 
                FROM UM_RECORD_PERMISSION RP 
                    INNER JOIN UM_PERMISSION UP ON UP.PERMISSION_ID = RP.PERMISSION_ID 
                    INNER JOIN UM_OPERATION UO ON UO.OPERATION_ID = UP.OPERATION_ID 
                    INNER JOIN UM_OBJECT OBJ ON OBJ.OBJECT_ID = RP.OBJECT_ID  
                WHERE LOWER(OBJ.CODE) = ".$this->db->Param(0)." 
                    AND RP.RECORD_ID = ".$this->db->Param(1)." 
                    AND LOWER(UO.NAME) = ".$this->db->Param(2)." 
                    AND RP.USER_ID = ".$this->db->Param(3), 
                array(Str::lower($objectCode), $recordId, Str::lower($operationName), $userId)
            );
            
            if ($row) {
                return true;
            }
            return false;
        }
        return true;
    }

    public function umUserDataGridModel()
    {
        $page = Input::post('page', 1);
        $rows = Input::post('rows', 10);
        $offset = ($page - 1) * $rows;

        $where = "WHERE USERNAME IS NOT NULL";
        if (Input::postCheck('lastname')) {
            if (Input::isEmpty('lastname') == false) {    
                $where .= " AND LOWER(TRIM(LAST_NAME)) LIKE '".Str::lower(Input::post('lastname'))."%'";
            }
        }
        if (Input::postCheck('username')) {     
            if (Input::isEmpty('username') == false) {     
                $where .= " AND LOWER(TRIM(USERNAME)) LIKE '".Str::lower(Input::post('username'))."%'";
            } 
        }

        $sortField = 'CREATE_DATE';
        $sortOrder = 'ASC';
        if (Input::postCheck('sort') && Input::postCheck('order')) {
            $sortField = Input::post('sort');
            $sortOrder = Input::post('order');
        }

        $rowCount = $this->db->GetRow(
            "SELECT 
                COUNT(USER_ID) AS ROW_COUNT 
            FROM UM_SYSTEM_USER 
            ".$where); 

        $selectList = "SELECT 
                           USER_ID, 
                           USERNAME, 
                           EMAIL,
                           INACTIVE,
                           CREATE_DATE,
                           CHANGE_DATE
                       FROM UM_SYSTEM_USER  
                       ".$where." 
                       ORDER BY $sortField $sortOrder";

        $result = $items = array();
        $result["total"] = $rowCount['ROW_COUNT'];
        $result["rows"] = array();

        if ($result["total"] > 0) {
            $rs = $this->db->SelectLimit($selectList, $rows, $offset);
            $result["rows"] = $rs->_array;
        }

        return $result;
    }

    public function getUserRowByCrtSerialNumberModel($crtSerialNumber) {

        $row = $this->db->GetRow("
            SELECT 
                UM.USER_ID, 
                UM.LAST_NAME, 
                UM.FIRST_NAME, 
                UM.EMP_PICTURE AS PICTURE, 
                UM.POSITION_NAME 
            FROM VW_USER UM 
                INNER JOIN UM_USER_MONPASS_MAP RT ON RT.USER_ID = UM.USER_ID 
            WHERE RT.CERTIFICATE_SERIAL_NUMBER = ".$this->db->Param(0), array($crtSerialNumber));

        return $row;
    }

    // <editor-fold defaultstate="collapsed" desc="User Meta Permission">

    public function renderMenuTreeViewModel($mainMetaDataId, $srcMetaDataId, $depth = 0, $srcId = "", $trgId = "") {
        $menu = '';
        $data = self::getChildMenuMetasModel($srcMetaDataId);

        if ($data) {
            foreach ($data as $row) {
                if ($depth == 0) {
                    $menu .= '<tr data-parent="1" class="tabletree-' . $mainMetaDataId . $row['SRC_META_DATA_ID'] . $row['TRG_META_DATA_ID'] . '">';
                } else {
                    $menu .= '<tr data-parent="0" class="tabletree-' . $mainMetaDataId . $trgId . $row['TRG_META_DATA_ID'] . ' tabletree-parent-' . $mainMetaDataId . $srcId . $row['SRC_META_DATA_ID'] . '">';
                }
                $menu .= '<td class="middle">' . Form::checkbox(array('name' => 'menuMetaId[]', 'value' => $row['TRG_META_DATA_ID'], 'class' => 'notuniform'))
                        . Form::hidden(array('name' => 'srcMetaDataId[]', 'value' => $srcId))
                        . Form::hidden(array('name' => 'mainMetaDataId[]', 'value' => $mainMetaDataId))
                        . Form::hidden(array('name' => 'parentId[]', 'value' => $row['SRC_META_DATA_ID'])) . '</td>';
                $menu .= '<td class="middle">' . $row['META_DATA_CODE'] . '</td>';
                $menu .= '<td class="middle">' . $row['META_DATA_NAME'] . '</td>';
                $menu .= '</tr>';
                $menu .= self::renderMenuTreeViewModel($mainMetaDataId, $row['TRG_META_DATA_ID'], $depth + 1, $row['SRC_META_DATA_ID'], $row['TRG_META_DATA_ID']);
            }
        }

        return $menu;
    }

    public function getMenuActionMetaModel($metaDataId){
        $result = $this->db->GetAll("SELECT
                                        MML.ID,
                                        MML.META_DATA_ID,
                                        MML.MENU_POSITION,
                                        MML.MENU_ALIGN,
                                        MML.MENU_THEME,
                                        MML.ACTION_META_DATA_ID,
                                        MML.WEB_URL,
                                        MML.URL_TARGET,
                                        MML.ICON_NAME,
                                        MML.PHOTO_NAME,
                                        SMD.META_DATA_CODE AS TRG_META_DATA_CODE,
                                        SMD.META_DATA_NAME AS TRG_META_DATA_NAME,
                                        SMD.META_TYPE_ID,
                                        TMD.META_DATA_CODE AS SRC_META_DATA_CODE,
                                        TMD.META_DATA_NAME AS SRC_META_DATA_NAME,
                                        TMD.META_TYPE_ID AS TRG_META_TYPE_ID,
                                        MGL.GROUP_TYPE
                                    FROM META_MENU_LINK MML
                                    INNER JOIN META_DATA SMD
                                    ON MML.ACTION_META_DATA_ID = SMD.META_DATA_ID
                                    INNER JOIN META_DATA TMD
                                    ON MML.META_DATA_ID = TMD.META_DATA_ID
                                    LEFT JOIN META_GROUP_LINK MGL
                                    ON MML.ACTION_META_DATA_ID = MGL.META_DATA_ID
                                    WHERE MML.META_DATA_ID=$metaDataId");
        return $result;
    }

    public function getGroupActionMetaModel($metaDataId) {
        $result = $this->db->GetAll("SELECT
                                        MPD.ID,
                                        MPD.MAIN_META_DATA_ID,
                                        MPD.PROCESS_META_DATA_ID,
                                        MPD.ICON_NAME,
                                        SMD.META_DATA_CODE AS TRG_META_DATA_CODE,
                                        ".$this->db->IfNull('MPD.PROCESS_NAME', 'SMD.META_DATA_NAME')." AS TRG_META_DATA_NAME,
                                        SMD.META_TYPE_ID,
                                        TMD.META_DATA_NAME AS SRC_META_DATA_NAME, 
                                        TMD.META_DATA_CODE AS SRC_META_DATA_CODE,
                                        TMD.META_TYPE_ID AS TRG_META_TYPE_ID
                                    FROM META_DM_PROCESS_DTL MPD
                                    INNER JOIN META_DATA TMD
                                    ON MPD.MAIN_META_DATA_ID = TMD.META_DATA_ID
                                    INNER JOIN META_DATA SMD
                                    ON MPD.PROCESS_META_DATA_ID = SMD.META_DATA_ID
                                    WHERE MPD.MAIN_META_DATA_ID=$metaDataId");
        return $result;
    }

    public function getProcessActionMetaModel($metaDataId) {
        $result = $this->db->GetAll("SELECT
                                        MPD.ID,
                                        MPD.MAIN_META_DATA_ID,
                                        MPD.PROCESS_META_DATA_ID,
                                        MPD.ICON_NAME,
                                        SMD.META_DATA_CODE AS SRC_META_DATA_CODE,
                                        SMD.META_DATA_NAME AS SRC_META_DATA_NAME,
                                        SMD.META_TYPE_ID,
                                        ".$this->db->IfNull('MPD.PROCESS_NAME', 'TMD.META_DATA_NAME')." AS TRG_META_DATA_NAME, 
                                        TMD.META_DATA_CODE AS TRG_META_DATA_CODE,
                                        TMD.META_TYPE_ID AS TRG_META_TYPE_ID
                                    FROM META_DM_PROCESS_DTL MPD
                                    INNER JOIN META_DATA SMD
                                    ON MPD.MAIN_META_DATA_ID = SMD.META_DATA_ID
                                    INNER JOIN META_DATA TMD
                                    ON MPD.PROCESS_META_DATA_ID = TMD.META_DATA_ID
                                    WHERE MPD.MAIN_META_DATA_ID=$metaDataId");
        return $result;
    }

    public function userPermissionDataGridOnMainModel($id, $pagination = true){
        $page = Input::postCheck('page') ? Input::post('page') : 1;
        $rows = Input::postCheck('rows') ? Input::post('rows') : 10;
        $offset = ($page - 1) * $rows;
        $subCondition = "";

        if (Input::postCheck('filterRules')) {
            $filterRules = json_decode(Str::cp1251_utf8($_POST['filterRules']), true);
            foreach ($filterRules as $rule) {
                $field = $rule['field'];
                $value = Input::param(Str::lower($rule['value']));
                if (!empty($value)) {
                    if ($field === 'META_DATA_CODE') {
                        $subCondition .= " AND (LOWER(MD.META_DATA_CODE) LIKE '%$value%')";
                    } elseif ($field === 'META_DATA_NAME') {
                        $subCondition .= " AND (LOWER(MD.META_DATA_NAME) LIKE '%$value%')";
                    } elseif ($field === 'USERNAME') {
                        $subCondition .= " AND (LOWER(US.USERNAME) LIKE '%$value%')";
                    } elseif ($field === 'ROLE_CODE') {
                        $subCondition .= " AND (LOWER(ROL.ROLE_CODE) LIKE '%$value%')";
                    } elseif ($field === 'ROLE_NAME') {
                        $subCondition .= " AND (LOWER(ROL.ROLE_NAME) LIKE '%$value%')";
                    } elseif ($field === 'GROUP_CODE') {
                        $subCondition .= " AND (LOWER(GR.GROUP_CODE) LIKE '%$value%')";
                    } elseif ($field === 'GROUP_NAME') {
                        $subCondition .= " AND (LOWER(GR.GROUP_NAME) LIKE '%$value%')";
                    }
                }
            }
        }

        if ($id != "") {
            $subCondition .= " AND PER.META_DATA_ID=$id";
        }
        $sortField = 'MD.META_DATA_CODE';
        $sortOrder = 'ASC';
        if (Input::postCheck('sort') && Input::postCheck('order')) {
            $sortField = Input::post('sort');
            $sortOrder = Input::post('order');
        }

        $selectCount = "SELECT
                            COUNT(PER.PERMISSION_ID) AS ROW_COUNT
                       FROM 
                       UM_META_PERMISSION PER
                       INNER JOIN META_DATA MD 
                       ON MD.META_DATA_ID = PER.META_DATA_ID
                       LEFT JOIN UM_ROLE ROL
                       ON ROL.ROLE_ID = PER.ROLE_ID
                       LEFT JOIN UM_USER US
                       ON US.USER_ID = PER.USER_ID
                       LEFT JOIN UM_GROUP GR
                       ON GR.GROUP_ID = PER.GROUP_ID
                       LEFT JOIN UM_ACTION AC
                       ON AC.ACTION_ID = PER.ACTION_ID
                       WHERE PER.META_DATA_ID IS NOT NULL $subCondition";

        $selectList = "SELECT
                            PER.PERMISSION_ID,
                            PER.META_DATA_ID,
                            MD.META_DATA_CODE,
                            MD.META_DATA_NAME, 
                            PER.ROLE_ID,
                            ROL.ROLE_CODE,
                            ROL.ROLE_NAME,
                            PER.USER_ID,
                            US.USERNAME,
                            GR.GROUP_CODE,
                            PER.GROUP_ID,
                            GR.GROUP_NAME,
                            PER.ACTION_ID,
                            AC.ACTION_CODE,
                            AC.ACTION_NAME,
                            PER.FIELD_CRITERIA,
                            PER.RECORD_CRITERIA
                       FROM 
                       UM_META_PERMISSION PER
                       INNER JOIN META_DATA MD 
                       ON MD.META_DATA_ID = PER.META_DATA_ID
                       LEFT JOIN UM_ROLE ROL
                       ON ROL.ROLE_ID = PER.ROLE_ID
                       LEFT JOIN UM_USER US
                       ON US.USER_ID = PER.USER_ID
                       LEFT JOIN UM_GROUP GR
                       ON GR.GROUP_ID = PER.GROUP_ID
                       LEFT JOIN UM_ACTION AC
                       ON AC.ACTION_ID = PER.ACTION_ID
                       WHERE PER.META_DATA_ID IS NOT NULL $subCondition
                       ORDER BY $sortField $sortOrder";

        $rowCount = $this->db->GetRow($selectCount);
        $result["total"] = $rowCount['ROW_COUNT'];
        $result["rows"] = array();

        if ($pagination) {
            if ($result["total"] > 0) {
                $rs = $this->db->SelectLimit($selectList, $rows, $offset);
                $result["rows"] = $rs->_array;
            }
        } else {
            $rs = $this->db->SelectLimit($selectList);
            if (isset($rs->_array)) {
                $result["rows"] = $rs->_array;
            }
        }
        return $result;
    }

    public function getMetaListModel($pagination = true){
        $page = Input::postCheck('page') ? Input::post('page') : 1;
        $rows = Input::postCheck('rows') ? Input::post('rows') : 10;
        $offset = ($page - 1) * $rows;
        $subCondition = "";
        $join = "";

        if (Input::postCheck('filterRules')) {
            $filterRules = json_decode(Str::cp1251_utf8($_POST['filterRules']), true);
            foreach ($filterRules as $rule) {
                $field = $rule['field'];
                $value = Input::param(Str::lower($rule['value']));
                if (!empty($value)) {
                    if ($field === 'META_DATA_CODE') {
                        $subCondition .= " AND (LOWER(MD.META_DATA_CODE) LIKE '%$value%')";
                    } elseif ($field === 'META_DATA_NAME') {
                        $subCondition .= " AND (LOWER(MD.META_DATA_NAME) LIKE '%$value%')";
                    } elseif ($field === 'DESCRIPTION') {
                        $subCondition .= " AND (LOWER(MD.DESCRIPTION) LIKE '%$value%')";
                    } elseif ($field === 'USERNAME') {
                        $subCondition .= " AND (LOWER(US.USERNAME) LIKE '%$value%')";
                    }
                }
            }
        }
        if (Input::postCheck('metaTypeId')) {
            $metaTypeId = Input::post('metaTypeId');
            $subCondition .= " AND MD.META_TYPE_ID =" . $metaTypeId . "";
            if($metaTypeId == Mdmetadata::$metaGroupMetaTypeId){
               $join = " LEFT JOIN META_GROUP_LINK MGL
                         ON MD.META_DATA_ID = MGL.META_DATA_ID";
               $subCondition .= " AND MGL.GROUP_TYPE ='dataview'"; 
               $subCondition .= " AND MD.META_DATA_ID IN (SELECT ACTION_META_DATA_ID FROM META_MENU_LINK WHERE ACTION_META_DATA_ID IS NOT NULL)"; 
            }
            if($metaTypeId == Mdmetadata::$businessProcessMetaTypeId || $metaTypeId == Mdmetadata::$workSpaceMetaTypeId){
               $join = "LEFT JOIN META_DM_PROCESS_DTL PRO
                        ON MD.META_DATA_ID = PRO.PROCESS_META_DATA_ID"; 
               $subCondition .= " AND (MD.META_DATA_ID IN (SELECT ACTION_META_DATA_ID FROM META_MENU_LINK WHERE ACTION_META_DATA_ID IS NOT NULL)"; 
               $subCondition .= " OR PRO.MAIN_META_DATA_ID IN (SELECT ACTION_META_DATA_ID FROM META_MENU_LINK WHERE ACTION_META_DATA_ID IS NOT NULL))"; 
            }
        }
        $sortField = 'META_DATA_CODE';
        $sortOrder = 'ASC';
        if (Input::postCheck('sort') && Input::postCheck('order')) {
            $sortField = Input::post('sort');
            $sortOrder = Input::post('order');
        }

        $selectCount = "SELECT 
                            COUNT(DISTINCT(MD.META_DATA_ID)) AS ROW_COUNT
                          FROM
                          META_DATA MD
                          LEFT JOIN UM_USER US
                          ON MD.CREATED_USER_ID = US.USER_ID
                          $join
                       WHERE MD.META_DATA_ID IS NOT NULL 
                       AND MD.IS_PRODUCTION=1  $subCondition";

        $selectList = "SELECT 
                            DISTINCT MD.META_DATA_ID,
                            MD.META_DATA_CODE,
                            MD.META_DATA_NAME,
                            MD.DESCRIPTION,
                            US.USERNAME
                          FROM
                          META_DATA MD
                          LEFT JOIN UM_USER US
                          ON MD.CREATED_USER_ID = US.USER_ID
                          $join
                       WHERE MD.META_DATA_ID IS NOT NULL 
                       AND  MD.IS_PRODUCTION=1 $subCondition
                       ORDER BY $sortField $sortOrder";

        $rowCount = $this->db->GetRow($selectCount);
        $result["total"] = $rowCount['ROW_COUNT'];
        $result["rows"] = array();

        if ($pagination) {
            if ($result["total"] > 0) {
                $rs = $this->db->SelectLimit($selectList, $rows, $offset);
                $result["rows"] = $rs->_array;
            }
        } else {
            $rs = $this->db->SelectLimit($selectList);
            if (isset($rs->_array)) {
                $result["rows"] = $rs->_array;
            }
        }
        return $result;            
    }

    public function userRoleDataModel(){
        return $this->db->GetAll("SELECT ROLE_ID, ROLE_CODE, ROLE_NAME FROM UM_ROLE");
    }

    public function userGroupDataModel(){
        return $this->db->GetAll("SELECT GROUP_ID, GROUP_CODE, GROUP_NAME FROM UM_GROUP");
    }

    public function permissionActionDataModel(){
        return $this->db->GetAll("SELECT ACTION_ID, ACTION_CODE, ACTION_NAME FROM UM_ACTION");
    }

    public function createPermissionModel() {
        (Array) $paramList = array();
        if (Input::postCheck('metaDataId')) {
            $totalMetaDataId = explode(',', Input::numeric('metaDataId'));
            foreach ($totalMetaDataId as $metaDataId) {
                if (Input::postCheck('actionId')) {
                    $totalAction = Input::post('actionId');
                    foreach ($totalAction as $actionId) {
                        if (Input::postCheck('roleId')) {
                            $totalRole = Input::post('roleId');
                            foreach ($totalRole as $roleId) {
                                $param = array(
                                    'PERMISSION_ID' => getUID(),
                                    'META_DATA_ID' => $metaDataId,
                                    'ACTION_ID' => $actionId,
                                    'ROLE_ID' => $roleId,
                                    'FIELD_CRITERIA' => Input::post('field_criteria'),
                                    'RECORD_CRITERIA' => Input::post('record_criteria')
                                );
                                array_push($paramList, $param);
                            }
                        }
                        if (Input::postCheck('groupId')) {
                            $totalGroup = Input::post('groupId');
                            foreach ($totalGroup as $groupId) {
                                $param = array(
                                    'PERMISSION_ID' => getUID(),
                                    'META_DATA_ID' => $metaDataId,
                                    'ACTION_ID' => $actionId,
                                    'GROUP_ID' => $groupId,
                                    'FIELD_CRITERIA' => Input::post('field_criteria'),
                                    'RECORD_CRITERIA' => Input::post('record_criteria')
                                );
                                array_push($paramList, $param);
                            }
                        }
                        if (Input::postCheck('userId')) {
                            $users = Input::post('userId');
                            if ($users != '') {
                                $totalUser = explode(',', $users);
                                foreach ($totalUser as $userId) {
                                    $param = array(
                                        'PERMISSION_ID' => getUID(),
                                        'META_DATA_ID' => $metaDataId,
                                        'ACTION_ID' => $actionId,
                                        'USER_ID' => $userId,
                                        'FIELD_CRITERIA' => Input::post('field_criteria'),
                                        'RECORD_CRITERIA' => Input::post('record_criteria')
                                    );
                                    array_push($paramList, $param);
                                }
                            }
                        }
                    }
                } else {
                    return array(
                        'status' => 'error',
                        'message' => 'Та action сонгоно уу!'
                    );
                }
            }
        } else {
            return array(
                'status' => 'error',
                'message' => 'Та metadata сонгоно уу!'
            );
        }
        $not_error = true;
        $errorMsg = "";
        foreach ($paramList as $childParam) {
            $result = $this->db->AutoExecute('UM_META_PERMISSION', $childParam);
            if (!$result) {
                $not_error = false;
                $errorMsg = $this->db->ErrorMsg();
            }
        }
        if ($not_error) {
            $response = array(
                'status' => 'success',
                'result' => 'Aмжилттай хадгалагдлаа'
            );
        } else {
            $response = array(
                'status' => 'error',
                'result' => 'Aмжилтгүй боллоо',
                'text' => $errorMsg
            );
        }
        return $response;
    }

    public function updatePermissionModel(){
        $result = self::deletePermissionModel();
        if($result){
            $response = self::createPermissionModel();
            return $response;
        }else{
           return array(
                'status' => 'error',
                'result' => 'Aмжилтгүй боллоо',
                'text' => 'Aмжилтгүй боллоо'
            ); 
        }
    }

    public function getPermissionByIdModel($id){
        $result = $this->db->GetRow("SELECT 
                                        PER.PERMISSION_ID, 
                                        PER.META_DATA_ID, 
                                        PER.ACTION_ID, 
                                        PER.USER_ID,
                                        US.USERNAME,
                                        PER.ROLE_ID, 
                                        PER.GROUP_ID, 
                                        PER.FIELD_CRITERIA, 
                                        PER.RECORD_CRITERIA 
                                    FROM UM_META_PERMISSION PER
                                    LEFT JOIN UM_USER US
                                    ON US.USER_ID = PER.USER_ID
                                    WHERE PERMISSION_ID=$id");
        return $result;
    }

    public function getPermissionMetaTypeListModel() {
         $data = $this->db->GetAll("
            SELECT 
                ID, 
                META_TYPE_ID, 
                META_TYPE_NAME, 
                ORDER_NUM 
            FROM META_TYPE_PERMISSION 
            ORDER BY ORDER_NUM ASC");
        return $data;
    }

    public function getPermissionMetaTypeByIdModel($id) {
         $data = $this->db->GetRow("
            SELECT 
                ID, 
                META_TYPE_ID, 
                META_TYPE_NAME, 
                ORDER_NUM 
            FROM META_TYPE_PERMISSION 
            WHERE META_TYPE_ID = $id");
        return $data;
    }
    // </editor-fold>

    public function getChildMenuMetasModel($srcMetaDataId) {
        $data = $this->db->GetAll("
            SELECT 
                MM.SRC_META_DATA_ID, 
                MM.TRG_META_DATA_ID, 
                MD.META_DATA_CODE AS TRG_META_DATA_CODE, 
                MD.META_DATA_NAME AS TRG_META_DATA_NAME,
                MD1.META_DATA_CODE AS SRC_META_DATA_CODE, 
                MD1.META_DATA_NAME AS SRC_META_DATA_NAME 
            FROM META_META_MAP MM 
                INNER JOIN META_DATA MD ON MD.META_DATA_ID = MM.TRG_META_DATA_ID 
                INNER JOIN META_DATA MD1 ON MD1.META_DATA_ID = MM.SRC_META_DATA_ID 
            WHERE MD.META_TYPE_ID = " . Mdmetadata::$menuMetaTypeId . " 
                AND MD.IS_ACTIVE = 1 
                AND MM.SRC_META_DATA_ID = $srcMetaDataId 
            ORDER BY MM.ORDER_NUM ASC");

        return $data;
    }
    
    public function getGlobalUserListByCloudIdModel($cloudId) {
        
        $userId = Ue::sessionUserId();
        $cloudId = strtolower(Input::param($cloudId));
        
        $data = $this->db->GetAll("
            SELECT 
                USERNAME, 
                PASSWORD, 
                DOMAIN_NAME, 
                AFTER_LOGIN_URL 
            FROM UM_GLOBAL_USER  
            WHERE SYSTEM_USER_ID = $userId 
                AND LOWER(CLOUD_ID) = '$cloudId'");
        
        return $data;
    }

    public function toQuickMenuModel() {
        
        try {
            
            $userId     = Ue::sessionUserId();
            $metaDataId = Input::post('metaDataId');
            $fullUrl    = str_replace(array(URL, '?mmid='), array('', '&mmid='), Input::post('url'));
            $hotKey     = Input::post('hotKey');
            
            $systemId = $menuHtml = null;
            
            parse_str($fullUrl, $url);
            
            if (isset($url['mmid'])) {
                $systemId = $url['mmid'];
                $fullUrl = str_replace('&mmid=', '?mmid=', $fullUrl);
            }
            
            if (self::isExistsQuickMenu($userId, $metaDataId, $systemId) == false) {
                
                $this->load->model('mdmetadata', 'middleware/models/');

                $id = getUID();
                $metaType = Input::post('metaType');
                $isRenderMenu = Input::post('isRenderMenu');
                $saveHotKey = null;
                
                if (is_numeric($metaDataId)) {
                    
                    $metaRow = $this->model->getMetaDataModel($metaDataId);
                    $saveMetaDataId = $metaDataId;
                    $saveUrlCode = null;
                    $menuName = $metaRow['META_DATA_NAME'];
                    
                } else {
                    $saveMetaDataId = null;
                    $saveUrlCode = $metaDataId;
                    $menuName = Input::post('menuName');
                }
                
                if ($systemId && $hotKey && in_array($hotKey, array('F4', 'F6', 'F7', 'F8', 'F9', 'F10'))) {
                    
                    $this->db->AutoExecute(
                        'UM_QUICK_MENU', 
                        array('HOT_KEY' => null), 
                        'UPDATE', 
                        "USER_ID = $userId AND SYSTEM_ID = $systemId AND HOT_KEY = '$hotKey'"
                    );
                    
                    $saveHotKey = $hotKey;
                }

                $data = array(
                    'ID'            => $id, 
                    'USER_ID'       => $userId, 
                    'SYSTEM_ID'     => $systemId, 
                    'SYSTEM_URL'    => $fullUrl, 
                    'META_TYPE'     => $metaType, 
                    'META_DATA_ID'  => $saveMetaDataId, 
                    'DISPLAY_ORDER' => 1, 
                    'MENU_NAME'     => $menuName, 
                    'ICON_NAME'     => null, 
                    'URL_CODE'      => $saveUrlCode, 
                    'HOT_KEY'       => $saveHotKey, 
                    'CREATED_DATE'  => Date::currentDate()
                );
                $this->db->AutoExecute('UM_QUICK_MENU', $data);
                
                $userClass = new Mduser();
                
                if ($isRenderMenu == '0' && $systemId) {
                    $menuHtml = $userClass->renderQuickMenu($systemId, $userId);
                } elseif ($systemId) {
                    $menuHtml = $userClass->renderQuickMenuItem($systemId, $userId, $metaDataId);
                }

                return array('status' => 'success', 'menuHtml' => $menuHtml);

            } else {
                
                if (is_numeric($metaDataId)) {
                    $this->db->Execute("DELETE FROM UM_QUICK_MENU WHERE USER_ID = $userId AND META_DATA_ID = $metaDataId");
                } else {
                    $this->db->Execute("DELETE FROM UM_QUICK_MENU WHERE USER_ID = $userId AND LOWER(URL_CODE) = LOWER('$metaDataId')");
                }
                
                return array('status' => 'success', 'remove' => '1');
            }
            
        } catch (ADODB_Exception $ex) {
            return array('status' => 'error', 'message' => 'An error occurred');
        }
    }
    
    public function isExistsQuickMenu($userId, $metaDataId, $systemId) {
        
        if (is_numeric($metaDataId)) {
            $where = "META_DATA_ID = " . $this->db->Param(0);
        } else {
            $where = "LOWER(URL_CODE) = LOWER(".$this->db->Param(0).")";
        }
        
        if ($systemId) {
            $row = $this->db->GetRow("
                SELECT 
                    ID 
                FROM UM_QUICK_MENU 
                WHERE USER_ID = ".$this->db->Param(1)." 
                    AND SYSTEM_ID = ".$this->db->Param(2)." 
                    AND $where", array($metaDataId, $userId, $systemId));
        } else {
            $row = $this->db->GetRow("
                SELECT 
                    ID 
                FROM UM_QUICK_MENU 
                WHERE USER_ID = ".$this->db->Param(1)." 
                    AND SYSTEM_ID IS NULL 
                    AND $where", array($metaDataId, $userId)); 
        }
        
        if ($row) {
            return true;
        }
        return false;
    }
    
    public function getQuickMenuListModel($systemId, $userId) {
        
        $userId = ($userId) ? $userId : Ue::sessionUserId();
        
        $data = $this->db->GetAll("
            SELECT 
                SYSTEM_ID, 
                SYSTEM_URL, 
                META_TYPE, 
                META_DATA_ID, 
                MENU_NAME, 
                ICON_NAME, 
                URL_CODE, 
                HOT_KEY 
            FROM UM_QUICK_MENU 
            WHERE USER_ID = ".$this->db->Param(0)." 
                AND SYSTEM_ID = ".$this->db->Param(1)." 
            ORDER BY CREATED_DATE DESC", array($userId, $systemId));
        
        return $data;
    }
    
    public function getQuickMenuItemModel($systemId, $userId, $metaDataId) {
        
        if (is_numeric($metaDataId)) {
            $where = "META_DATA_ID = " . $this->db->Param(2);
        } else {
            $where = "LOWER(URL_CODE) = LOWER(".$this->db->Param(2).")";
        }
        
        $row = $this->db->GetRow("
            SELECT 
                SYSTEM_ID, 
                SYSTEM_URL, 
                META_TYPE, 
                META_DATA_ID, 
                MENU_NAME, 
                ICON_NAME, 
                URL_CODE, 
                HOT_KEY 
            FROM UM_QUICK_MENU 
            WHERE USER_ID = ".$this->db->Param(0)." 
                AND SYSTEM_ID = ".$this->db->Param(1)." 
                AND $where", array($userId, $systemId, $metaDataId));
        
        return $row;
    }
    
    public function isSavedQuickMenuItemModel($metaDataId) {
        
        $userId = Ue::sessionUserId();
        
        if ($userId) {
            $row = $this->db->GetRow("SELECT ID FROM UM_QUICK_MENU WHERE USER_ID = ".$this->db->Param(0)." AND META_DATA_ID = ".$this->db->Param(1), array($userId, $metaDataId));
            return $row;
        }

        return null;
    }
    
    public function isSavedQuickMenuUrlCodeModel($urlCode) {
        
        $userId = Ue::sessionUserId();
        
        $row = $this->db->GetRow("
            SELECT 
                ID  
            FROM UM_QUICK_MENU 
            WHERE USER_ID = ".$this->db->Param(0)." 
                AND LOWER(URL_CODE) = LOWER(".$this->db->Param(1).")", 
            array($userId, $urlCode)
        );
        
        return $row;
    }
    
    public function getCleanFieldsConfigModel($metaDataId) {
        
        $cache = phpFastCache();
        $data = $cache->get('bpHdrOnlyShow_'.$metaDataId);
        
        if ($data == null) {
            
            $data = $this->db->GetAll("
                SELECT 
                    PARAM_REAL_PATH AS PARAM_PATH, 
                    LABEL_NAME  
                FROM CUSTOMER_BP_CLEAN_DEFAULT CD 
                    INNER JOIN META_PROCESS_PARAM_ATTR_LINK PAL ON 
                        PAL.PROCESS_META_DATA_ID = CD.PROCESS_META_DATA_ID 
                        AND PAL.IS_INPUT = 1 
                        AND PAL.IS_SHOW = 1 
                        AND PAL.PARENT_ID IS NULL 
                        AND LOWER(PAL.PARAM_REAL_PATH) = LOWER(CD.PARAM_PATH) 
                WHERE CD.PROCESS_META_DATA_ID = ".$this->db->Param(0)." 
                ORDER BY PAL.ORDER_NUMBER ASC", array($metaDataId));
            
            $cache->set('bpHdrOnlyShow_'.$metaDataId, $data, Mdwebservice::$expressionCacheTime);
        }
        
        return $data;
    }
    
    public function bpCleanFieldUserConfigModel($metaDataId) {
        
        $sessionUserId = Ue::sessionUserId();
        
        $data = self::getCleanFieldsConfigModel($metaDataId);
        
        $userRow = $this->db->GetRow("
            SELECT 
                NOCLEAN_PARAM_PATH 
            FROM CUSTOMER_BP_CLEAN 
            WHERE USER_ID = ".$this->db->Param(0)." 
                AND META_DATA_ID = ".$this->db->Param(1), 
            array($sessionUserId, $metaDataId)
        );
        
        $savePathArr = array();
        
        if ($userRow) {
            $savePath = explode('|', $userRow['NOCLEAN_PARAM_PATH']);
            foreach ($savePath as $path) {
                $savePathArr[$path] = 1;
            }
        }
        
        return array('paths' => $data, 'savePath' => $savePathArr);
    }
    
    public function cleanFieldUserConfigSaveModel() {
        
        try {
            if (Input::postCheck('userConfigHidden')) {

                $metaDataId = Input::numeric('metaDataId');
                $cleanParamPath = $noCleanParamPath = array();
                $userConfigHidden = $_POST['userConfigHidden'];

                foreach ($userConfigHidden as $k => $v) {

                    if ($v == '0') {
                        $noCleanParamPath[] = $k;
                    } elseif ($v == '1') {
                        $cleanParamPath[] = $k;
                    }
                }

                $keyCols = array('USER_ID', 'META_DATA_ID');

                $configFields = array(
                    'ID'                 => getUID(), 
                    'USER_ID'            => Ue::sessionUserId(), 
                    'META_DATA_ID'       => $metaDataId, 
                    'CLEAN_PARAM_PATH'   => implode('|', $cleanParamPath), 
                    'NOCLEAN_PARAM_PATH' => implode('|', $noCleanParamPath)   
                );
                $this->db->Replace('CUSTOMER_BP_CLEAN', $configFields, $keyCols, $autoquote = true);

                return array('status' => 'success', 'cleanParamPath' => $configFields['CLEAN_PARAM_PATH'], 'noCleanParamPath' => $configFields['NOCLEAN_PARAM_PATH']);
            }
            
        } catch (ADODB_Exception $ex) {
            return array('status' => 'error', 'message' => $ex->getMessage());
        }
    }
    
    public function getUserKeysModel() {
        
        $userId = Ue::sessionUserId();
        $userKeyId = Ue::sessionUserKeyId();
        
        $data = $this->db->GetAll("
            SELECT 
                UM.USER_ID, 
                ORG.DEPARTMENT_CODE, 
                ORG.DEPARTMENT_NAME, 
                ORG.OBJECT_PHOTO 
            FROM UM_USER UM 
                INNER JOIN ORG_DEPARTMENT ORG ON ORG.DEPARTMENT_ID = UM.DEPARTMENT_ID
            WHERE UM.SYSTEM_USER_ID = ".$this->db->Param(0)." 
                AND UM.IS_ACTIVE = 1 
                AND UM.USER_ID <> ".$this->db->Param(1)."  
            ORDER BY ORG.DEPARTMENT_CODE ASC", array($userId, $userKeyId));

        return $data;
    }
    
    public function changeKeyModel($encrypted) {
            
        includeLib('Compress/Compression');

        $encrypted    = Str::urlCharReplace($encrypted, true);
        $decrypted    = Compression::gzinflate($encrypted);
        $decryptedArr = explode('$', $decrypted);

        $decryptedUserKeyId = $decryptedArr[0];
        $departmentName     = $decryptedArr[1];

        $resultClient = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'connectClient', array('userKeyId' => $decryptedUserKeyId));

        Session::set(SESSION_PREFIX . 'userkeyid', $decryptedUserKeyId);
        Session::set(SESSION_PREFIX . 'userKeyCompanyName', $departmentName);
        
        if (isset($resultClient['result']['sessionvalues']) && $resultClient['result']['sessionvalues']) {
            Session::set(SESSION_PREFIX.'sessionValues', $resultClient['result']['sessionvalues']);
        }
        
        $this->load->model('login');
        $response = $this->model->getUserKeyRow($decryptedUserKeyId, Ue::sessionUserId());
        $defaultModuleId = ($response['DEFAULT_MENU_ID']) ? $response['DEFAULT_MENU_ID'] : $response['MODULE_MENU_ID'];
        
        Session::set(SESSION_PREFIX . 'isUseFolderPermission', $response['IS_USE_FOLDER_PERMISSION']);
        Session::set(SESSION_PREFIX . 'defaultModuleId', $defaultModuleId);
        Session::set(SESSION_PREFIX . 'isTranslateUser', $response['IS_TRANSLATE_USER']);

        Ue::loginCacheClear();
        Ue::startFiscalPeriod();
        
        return true;
    }
    
    public function saveUserTouchModeModel() {
        
        $isTouchMode = Input::numeric('isTouchMode');
        
        if (is_numeric($isTouchMode)) {
            
            try {
                
                $userId = Ue::sessionUserId();
            
                $this->db->AutoExecute('UM_SYSTEM_USER', array('IS_TOUCH_MODE' => $isTouchMode), 'UPDATE', 'USER_ID = '.$userId);

                Session::set(SESSION_PREFIX . 'touchMode', $isTouchMode);
                
                $result = array('status' => 'success');
                
            } catch (Exception $ex) {
                $result = array('status' => 'error', 'message' => 'error');
            }
            
        } else {
            $result = array('status' => 'error', 'message' => 'Invalid value!');
        }
        
        return $result;
    }
    
    public function dataAccessPasswordModel() {
        
        $param = array('password' => Input::post('accessPass'));
        
        $result = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'securityPassword', $param);

        if ($result['status'] == 'success') {
            $result = array('status' => 'success', 'message' => 'Successfully');
        } else {
            $result = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
        }

        return $result;
    }
    
    public function getBpValueTemplateModel() {
        
        $idPh = $this->db->Param(0);
        $processIdPh = $this->db->Param(1);
        
        $data = $this->db->GetAll("
            SELECT 
                ID, 
                NAME, 
                CASE WHEN CREATED_USER_ID = $idPh THEN 1 
                ELSE 0 END AS IS_EDIT 
            FROM CUSTOMER_PROCESS_TEMPLATE 
            WHERE (USER_ID = $idPh OR USER_ID IS NULL) 
                AND PROCESS_ID = $processIdPh 
            ORDER BY ID DESC", 
            array(Ue::sessionUserId(), Input::numeric('processId'))
        );
        
        return $data;
    }
    
    public function updateBpValueTemplateModel() {
        
        try {
            
            $id = Input::numeric('id');
            
            if ($id) {
                
                $data = array('NAME' => Input::post('templateName'));
                $this->db->AutoExecute('CUSTOMER_PROCESS_TEMPLATE', $data, 'UPDATE', "ID = $id");
                
                $response = array('status' => 'success', 'message' => $this->lang->line('msg_save_success'));
                
            } else {
                $response = array('status' => 'error', 'message' => 'Invalid id!');
            }
            
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }
    
    public function deleteBpValueTemplateModel() {
        
        try {
            
            $id = Input::numeric('id');
            
            if ($id) {
                
                $sessionUserId = Ue::sessionUserId();
                $processId = Input::numeric('processId');
                
                $this->db->Execute("DELETE FROM CUSTOMER_PROCESS_TEMPLATE WHERE ID = ".$this->db->Param(0)." AND CREATED_USER_ID = ".$this->db->Param(1), array($id, $sessionUserId));
                
                Mdcommon::clearUserBpDataTmpl($processId);
                
                $response = array('status' => 'success', 'message' => $this->lang->line('msg_delete_success'));
                
            } else {
                $response = array('status' => 'error', 'message' => 'Invalid id!');
            }
            
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }
    
    public function isBpSavedDataTmplModel($metaDataId) {
        
        $cache = phpFastCache();
        
        $sessionUserId = Ue::sessionUserId();
        $data = $cache->get('userBpDataTmpl_' . $metaDataId . '_' . $sessionUserId);
        
        if (!$data && $sessionUserId) {
            
            $idPh = $this->db->Param(0);
            $processIdPh = $this->db->Param(1);

            $row = $this->db->GetRow("
                SELECT 
                    ID 
                FROM CUSTOMER_PROCESS_TEMPLATE 
                WHERE (USER_ID = $idPh OR USER_ID IS NULL) 
                    AND PROCESS_ID = $processIdPh", 
                array($sessionUserId, $metaDataId)
            );
            
            if ($row) {
                $data = 'true';
            } else {
                $data = 'false';
            }
            
            $cache->set('userBpDataTmpl_' . $metaDataId . '_' . $sessionUserId, $data, Mdwebservice::$expressionCacheTime);
        }
        
        if ($data == 'true') {
            return true;
        }
        
        return false;
    }
    
    public function checkPinCodeModel() {
        
        $pinCode = Input::post('pinCode');
        
        if ($pinCode) {
            
            $check = $this->db->GetRow("
                SELECT 
                    USER_ID 
                FROM UM_SYSTEM_USER 
                WHERE USER_ID = ".$this->db->Param(0)." 
                    AND PIN_CODE = ".$this->db->Param(1), 
                array(Ue::sessionUserId(), $pinCode)
            );

            if ($check) {
                $response = array('status' => 'success');
            } else {
                $response = array('status' => 'error', 'message' => 'Пин код буруу байна!');
            }
            
        } else {
            $response = array('status' => 'error', 'message' => 'Пин код оруулна уу!');
        }
        
        return $response;
    }
    
    public function gettokenDataByUserId() {
        $operator = $this->db->GetRow("
            SELECT 
                T2.CERTIFICATE_SERIAL_NUMBER, 
                LOWER(T3.STATE_REG_NUMBER) AS STATE_REG_NUMBER
            FROM UM_USER T0 
                INNER JOIN UM_SYSTEM_USER T1 ON T0.SYSTEM_USER_ID = T1.USER_ID
                INNER JOIN UM_USER_MONPASS_MAP T2 ON T0.USER_ID = T2.USER_ID
                INNER JOIN BASE_PERSON t3 ON t1.PERSON_ID = t3.PERSON_ID
            WHERE T1.USER_ID = ".$this->db->Param(0)." 
                AND T2.IS_ACTIVE = 1", 
            array(Ue::sessionUserId())
        );
        
        return $operator;
    }
    
    public function pinCodeResetModel() {
        
        try {
            
            $randNum = Str::random_string('numeric', 4); 
            $userId  = Ue::sessionUserId();
            $id1Ph   = $this->db->Param(0);
            
            $this->db->AutoExecute('UM_SYSTEM_USER', array('PIN_CODE' => $randNum), 'UPDATE', 'USER_ID = '.$userId);
            
            $email = $this->db->GetOne("SELECT EMAIL FROM UM_SYSTEM_USER WHERE USER_ID = $id1Ph", array($userId));
            $body  = 'Шинэ пин код: '.$randNum;
            
            if ($email) {
                
                includeLib('Mail/Mail');
                
                Mail::sendPhpMailer(
                    array(
                        'subject' => 'Veritech ERP пин код', 
                        'altBody' => 'Veritech ERP пин код', 
                        'body'    => $body, 
                        'toMail'  => $email 
                    )
                );
            }
            
            if ($sessionEmployeeId = Ue::sessionEmployeeId()) {
                
                $phoneNumber = $this->db->GetOne("SELECT EMPLOYEE_MOBILE FROM HRM_EMPLOYEE WHERE EMPLOYEE_ID = $id1Ph", array($sessionEmployeeId));
            
                if ($phoneNumber) {

                    $param = array(
                        'phoneNumber' => $phoneNumber, 
                        'msg'         => 'Veritech ERP shine pin code: '.$randNum 
                    );
                    $this->ws->runResponse(GF_SERVICE_ADDRESS, 'SEND_SMS', $param);
                }
            }
            
            return array('status' => 'success', 'message' => 'Та и-мейл хаягаа шалгана уу. Хэрвээ Inbox фолдерт байхгүй бол SPAM, JUNK фолдероос шалгана уу.');
            
        } catch (Exception $ex) {
            return array('status' => 'warning', 'message' => $ex->getMessage());
        }
    }
    
    public function changePinCodeModel() {
        
        try {
            
            $newPassword = Input::post('newPassword');
            $confirmPassword = Input::post('confirmPassword');
            
            if ($newPassword == $confirmPassword) {
                
                $currentPassword = Input::post('currentPassword');
                $userId  = Ue::sessionUserId();
                $id1Ph   = $this->db->Param(0);
                $id2Ph   = $this->db->Param(1);
            
                $check = $this->db->GetOne("SELECT USER_ID FROM UM_SYSTEM_USER WHERE USER_ID = $id1Ph AND PIN_CODE = $id2Ph", array($userId, $currentPassword));
                
                if ($check) {
                    
                    $this->db->AutoExecute('UM_SYSTEM_USER', array('PIN_CODE' => $newPassword), 'UPDATE', 'USER_ID = '.$userId);
                    $response = array('status' => 'success', 'message' => 'Пин код амжилттай солигдлоо.');
                    
                } else {
                    $response = array('status' => 'error', 'message' => 'Одоогийн пин код буруу байна.');
                }
                
            } else {
                $response = array('status' => 'error', 'message' => 'Шинэ пин код ижил биш байна.');
            }
            
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }
    
    public function getUserInfoByContentIdModel() {
        
        $id = Input::post('id');
        
        if ($id) {
            $row = $this->db->GetRow("
                SELECT 
                    US.USERNAME, 
                    EMP.LAST_NAME, 
                    EMP.FIRST_NAME, 
                    EMP.PICTURE, 
                    EC.FILE_NAME, 
                    EC.CREATED_DATE 
                FROM ECM_CONTENT EC 
                    LEFT JOIN UM_USER UM ON UM.USER_ID = EC.CREATED_USER_ID 
                    LEFT JOIN UM_SYSTEM_USER US ON US.USER_ID = UM.SYSTEM_USER_ID 
                    LEFT JOIN VW_EMPLOYEE EMP ON EMP.PERSON_ID = US.PERSON_ID 
                WHERE EC.CONTENT_ID = ".$this->db->Param(0), 
            array($id));
            
            return $row;
        }
    }
    
    public function startupMetaModel() {
        $get = $this->db->GetRow("SELECT * FROM UM_META_BLOCK WHERE USER_ID = ".$this->db->Param(0), array(Ue::sessionUserKeyId()));
        return $get;
    }    
    
    public function startupMetaAllUserModel() {
        $get = $this->db->GetRow("SELECT * FROM UM_META_BLOCK WHERE IS_ALL_USER = ".$this->db->Param(0), array(1));
        return $get;
    }
    
    public function getCloudUserDbConnectionsModel() {
        
        if (Config::getFromCache('cloud_domain_name') == Uri::domain() && !Session::unitName()) {
            try {

                $data = $this->db->GetAll("
                    SELECT 
                        T1.ID, 
                        T1.CUSTOMER_ID, 
                        T2.CUSTOMER_NAME 
                    FROM MDM_CONNECTIONS T1 
                        INNER JOIN CRM_CUSTOMER T2 ON T2.CUSTOMER_ID = T1.CUSTOMER_ID 
                    WHERE T1.IS_ACTIVE = 1
                    ORDER BY T2.CUSTOMER_NAME ASC");
                
                return $data;

            } catch (Exception $ex) { }
        }
        
        return [];
    }
    
    public function connectCloudUserDbModel() {
        
        $connectionId = Input::numeric('connectionId');
        $connectionInfo = DBUtil::getConnectionByDbId($connectionId);
        
        if (!$connectionInfo) {
            return ['status' => 'error', 'message' => 'Connection not found!'];
        }
        
        global $db;
        
        $dbDriver = DBUtil::toShortName($connectionInfo['DB_TYPE']);
        $dbHost   = $connectionInfo['HOST_NAME'] . ':' . $connectionInfo['PORT'];
        $dbUser   = strtolower($connectionInfo['USER_NAME']);
        $dbPass   = $connectionInfo['USER_PASSWORD'];
        $dbSID    = $connectionInfo['SID'];

        if ($dbSID) {
            $connectSID = true;
        } else {
            $dbSID = $connectionInfo['SERVICE_NAME'];
            $connectSID = false;
        }

        $db = ADONewConnection($dbDriver);
        $db->debug = DB_DEBUG;
        $db->connectSID = $connectSID;
        $db->autoRollback = true;
        $db->datetime = true;

        try {
            $db->Connect($dbHost, $dbUser, $dbPass, $dbSID);
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->msg];
        }
        
        $db->SetCharSet(DB_CHATSET);
        
        $secondDb = $dbHost . '|$|' . $dbUser . '|$|' . $dbPass . '|$|' . $dbSID . '|$|' . (int)$connectSID;
        $secondDb = Crypt::encrypt($secondDb, 'db49x');

        Session::set(SESSION_PREFIX . 'sdbun', (DB_DRIVER == 'oci8' ? $dbUser : $connectionInfo['ID']));
        Session::set(SESSION_PREFIX . 'sdbid', $connectionInfo['ID']);
        Session::set(SESSION_PREFIX . 'sdbnm', $connectionInfo['DB_NAME']);
        Session::set(SESSION_PREFIX . 'sdb', $secondDb);
        
        $param = [
            'username' => 'admin', 
            'password' => '123',
            'userId' => 1
        ];
        $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'login', $param);
        $errorMsg = 'Системд нэвтрэх сервис хэвийн бус ажиллаж байгаа тул та систем хариуцсан ажилтантай холбогдоно уу';
        
        if (isset($result['status'])) {
            
            if ($result['status'] == 'success' && isset($result['result'])) {
                
                SessionSetHandler::initLogged($result['result']);
                
                $this->ws->runResponse(GF_SERVICE_ADDRESS, 'connectClient', ['userKeyId' => 1]);
        
                return ['status' => 'success', 'url' => Config::getFromCacheDefault('CONFIG_START_LINK', null, 'appmenu')];
                
            } elseif (isset($result['text'])) {
                $errorMsg = $result['text'];
            }
        }
        
        $this->load->model('login');
        $this->model->deleteSessionDatabaseConnection();
        
        return ['status' => 'error', 'message' => $errorMsg];
    }
    
}