<?php if(!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Chat_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function getChatUsersModel() {
        
        $sessionUserId       = Ue::sessionUserId();
        $sessionDepartmentId = Ue::sessionDepartmentId();
        
        $sessionUserIdPh       = $this->db->Param(0);
        $sessionDepartmentIdPh = $this->db->Param(1);
        
        $bindVars = array($sessionUserId, $sessionDepartmentId);
        
        $this->db->StartTrans(); 
        $this->db->Execute(Ue::createSessionInfo());
            
        $data = $this->db->GetAll(" 
            SELECT 
                HDR.USER_ID, 
                HDR.PICTURE, 
                HDR.USER_FULL_NAME, 
                HDR.FIRST_NAME, 
                HDR.DEPARTMENT_NAME, 
                HDR.LAST_CHAT_ID, 
                CASE WHEN HDR.LAST_CHAT_ID IS NULL 
                THEN 0 
                ELSE 
                    (
                        SELECT 
                            COUNT(ID) 
                        FROM ZZCHAT 
                        WHERE TO_ID = $sessionUserIdPh 
                            AND FROM_ID = HDR.USER_ID 
                            AND READ = 0 
                            AND DELETED_BY_TO IS NULL 
                    )
                END AS NEW_MSG_COUNT, 
                ZC.READ, 
                ZC.FROM_ID, 
                ZC.TO_ID, 
                HDR.STATUS 
            FROM (
                SELECT 
                    UM.USER_ID, 
                    EMP.PICTURE, 
                    CASE WHEN BP.FIRST_NAME IS NULL 
                    THEN UM.USERNAME 
                    ELSE SUBSTR(BP.LAST_NAME, 0, 1) || '.' || BP.FIRST_NAME END AS USER_FULL_NAME,  
                    BP.FIRST_NAME, 
                    ORG.DEPARTMENT_NAME, 
                    " . $this->db->IfNull('MAX(CH.ID)', '1') . " AS LAST_CHAT_ID, 
                    CASE WHEN ORG.DEPARTMENT_ID = $sessionDepartmentIdPh 
                    THEN 1 
                    ELSE 0 END AS SELF_DEPARTMENT, 
                    ZS.STATUS, 
                    ORG.DISPLAY_ORDER, 
                    EMP.WORK_START_DATE, 
                    HP.DISPLAY_ORDER AS POS_DISPLAY_ORDER 
                FROM UM_SYSTEM_USER UM 
                    LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = UM.PERSON_ID 
                    LEFT JOIN HRM_EMPLOYEE EMP ON EMP.PERSON_ID = BP.PERSON_ID 
                    LEFT JOIN HRM_EMPLOYEE_KEY EMPK ON EMPK.EMPLOYEE_ID = EMP.EMPLOYEE_ID 
                        AND EMPK.IS_ACTIVE = 1 
                    LEFT JOIN HRM_POSITION_KEY PK ON PK.POSITION_KEY_ID = EMPK.POSITION_KEY_ID 
                    LEFT JOIN HRM_POSITION HP ON HP.POSITION_ID = PK.POSITION_ID 
                    LEFT JOIN VW_ZZ_DEPARTMENT ORG ON ORG.DEPARTMENT_ID = EMPK.DEPARTMENT_ID 
                    LEFT JOIN (
                        SELECT
                            MAX(ID) AS ID,
                            TO_ID AS USER_ID
                        FROM
                            ZZCHAT
                        WHERE FROM_ID = $sessionUserIdPh 
                            AND DELETED_BY_FROM IS NULL
                        GROUP BY
                            TO_ID
                            
                        UNION ALL
                        
                        SELECT
                            MAX(ID) AS ID,
                            FROM_ID AS USER_ID
                        FROM
                            ZZCHAT
                        WHERE TO_ID = $sessionUserIdPh 
                            AND DELETED_BY_TO IS NULL
                        GROUP BY
                            FROM_ID
                    ) CH ON CH.USER_ID = UM.USER_ID
                    LEFT JOIN ZZCHAT_STATUS ZS ON ZS.USER_ID = UM.USER_ID 
                        AND ZS.IS_ACTIVE = 1 
                WHERE (UM.INACTIVE = 0 OR UM.INACTIVE IS NULL) 
                    AND UM.IS_USE_CHAT = 1 
                    AND UM.USER_ID NOT IN (1, 2) 
                GROUP BY 
                    UM.USER_ID, 
                    BP.LAST_NAME, 
                    BP.FIRST_NAME, 
                    UM.USERNAME, 
                    EMP.PICTURE, 
                    ORG.DEPARTMENT_ID, 
                    ORG.DEPARTMENT_NAME, 
                    ZS.STATUS, 
                    ORG.DISPLAY_ORDER, 
                    EMP.WORK_START_DATE, 
                    HP.DISPLAY_ORDER 
                ) HDR 
                LEFT JOIN ZZCHAT ZC ON ZC.ID = HDR.LAST_CHAT_ID 
            ORDER BY 
                HDR.LAST_CHAT_ID DESC, 
                HDR.SELF_DEPARTMENT DESC, 
                TO_NUMBER(HDR.DISPLAY_ORDER) ASC, 
                TO_NUMBER(HDR.POS_DISPLAY_ORDER) ASC, 
                HDR.WORK_START_DATE ASC, 
                HDR.FIRST_NAME ASC", $bindVars);
        
        $this->db->CompleteTrans();
        
        $users = $groups = array();
        
        foreach ($data as $row) {
            
            $users['_' . $row['USER_ID']] = array(
                'userId'         => $row['USER_ID'], 
                'picture'        => $row['PICTURE'], 
                'fullName'       => $row['USER_FULL_NAME'], 
                'firstName'      => $row['FIRST_NAME'], 
                'departmentName' => $row['DEPARTMENT_NAME'], 
                'lastChatId'     => $row['LAST_CHAT_ID'], 
                'unReadCount'    => $row['NEW_MSG_COUNT'],  
                'status'         => $row['STATUS']
            );
        }
        
        $dataGroups = $this->db->GetAll("
            SELECT 
                G.ID, 
                CU.USERID 
            FROM ZZCHAT_CHATROOMS G 
                INNER JOIN ZZCHAT_CHATROOMS_USERS U ON U.CHATROOMID = G.ID 
                INNER JOIN ZZCHAT_CHATROOMS_USERS CU ON CU.CHATROOMID = G.ID 
            WHERE U.USERID = $sessionUserIdPh 
            GROUP BY 
                G.ID, 
                CU.USERID", $bindVars);
        
        if ($dataGroups) {
            
            foreach ($dataGroups as $group) {
                $groups[$group['ID']][$group['USERID']] = 1;
            }
        }
        
        $umRow = $this->db->GetRow("
            SELECT 
                UM.CHAT_CONTACT_VIEW, 
                ZS.STATUS 
            FROM UM_SYSTEM_USER UM 
                LEFT JOIN ZZCHAT_STATUS ZS ON ZS.USER_ID = UM.USER_ID 
                    AND ZS.IS_ACTIVE = 1 
            WHERE UM.USER_ID = $sessionUserIdPh", $bindVars);
        
        $contactsView  = ($umRow['CHAT_CONTACT_VIEW'] ? $umRow['CHAT_CONTACT_VIEW'] : 'all');
        $userStatus    = $umRow['STATUS'];
        $supportUserId = Config::getFromCache('chatSupportSystemUserId');
        
        return array('users' => $users, 'groups' => $groups, 'contactsView' => $contactsView, 'status' => $userStatus, 'supportUserId' => $supportUserId);
    }    
  
    public function saveChatMessageModel() {
        
        try {
            
            $sessionUserId = Ue::sessionUserId();
            
            $data = array(
                'ID'              => Input::numeric('mid'),
                'FROM_ID'         => $sessionUserId,
                'TO_ID'           => Input::numeric('to'),
                'MESSAGE'         => Input::post('messageText'),
                'READ'            => 0,
                'IS_FILE'         => Input::post('isFile') == 'true' ? 1 : 0, 
                'CREATED_DATE'    => Input::post('sysDateTime'),
                'CREATED_USER_ID' => $sessionUserId
            );

            $this->db->AutoExecute('ZZCHAT', $data);
            
            $response = array('status' => 'success');
            
        } catch (ADODB_Exception $ex) {
            
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }

        return $response;
    }    

    public function getChatHistoryMessagesModel() {

        $page = Input::numeric('page', 1);
        $rows = Input::numeric('rows', 30);
        $offset = ($page - 1) * $rows;
        
        $sessionUserId   = Ue::sessionUserId();
        $sessionUserIdPh = $this->db->Param(0);
        $toUserIdPh      = $this->db->Param(1);
        
        $bindVars = array($sessionUserId, Input::numeric('to'));
                            
        $query = "
            SELECT 
                T0.ID, 
                T0.FROM_ID, 
                T0.TO_ID, 
                T0.MESSAGE, 
                T0.CREATED_DATE, 
                T0.IS_FILE, 
                T0.READ, 
                T0.IS_FILE_DOWNLOADED, 
                T0.HEADER_ID, 
                T0.FORWARD_MESSAGE, 
                T0.FORWARD_DATE, 
                CASE WHEN BP.FIRST_NAME IS NULL  
                    THEN T1.USERNAME  
                ELSE SUBSTR(BP.LAST_NAME, 0, 1) || '.' || BP.FIRST_NAME END AS FORWARD_USER_NAME 
            FROM ZZCHAT T0 
                INNER JOIN UM_SYSTEM_USER T1 ON T1.USER_ID = T0.CREATED_USER_ID 
                LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = T1.PERSON_ID 
            WHERE T0.FROM_ID = $sessionUserIdPh 
                AND T0.TO_ID = $toUserIdPh 
                AND T0.DELETED_BY_FROM IS NULL 
                
            UNION ALL 
            
            SELECT
                T0.ID, 
                T0.FROM_ID, 
                T0.TO_ID, 
                T0.MESSAGE, 
                T0.CREATED_DATE, 
                T0.IS_FILE, 
                T0.READ, 
                T0.IS_FILE_DOWNLOADED, 
                T0.HEADER_ID, 
                T0.FORWARD_MESSAGE, 
                T0.FORWARD_DATE, 
                CASE WHEN BP.FIRST_NAME IS NULL  
                    THEN T1.USERNAME  
                ELSE SUBSTR(BP.LAST_NAME, 0, 1) || '.' || BP.FIRST_NAME END AS FORWARD_USER_NAME 
            FROM ZZCHAT T0 
                INNER JOIN UM_SYSTEM_USER T1 ON T1.USER_ID = T0.CREATED_USER_ID 
                LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = T1.PERSON_ID 
            WHERE T0.TO_ID = $sessionUserIdPh  
                AND T0.FROM_ID = $toUserIdPh  
                AND T0.DELETED_BY_TO IS NULL 
            ORDER BY 
                CREATED_DATE DESC";
        
        /*
        SELECT 
                T0.ID, 
                T0.FROM_ID, 
                T0.TO_ID, 
                T0.MESSAGE, 
                T0.CREATED_DATE, 
                T0.IS_FILE, 
                T0.READ, 
                T0.IS_FILE_DOWNLOADED, 
                T0.HEADER_ID, 
                T0.FORWARD_MESSAGE, 
                T0.FORWARD_DATE, 
                CASE WHEN BP.FIRST_NAME IS NULL  
                    THEN T1.USERNAME  
                ELSE SUBSTR(BP.LAST_NAME, 0, 1) || '.' || BP.FIRST_NAME END AS FORWARD_USER_NAME 
            FROM ZZCHAT T0 
                INNER JOIN UM_SYSTEM_USER T1 ON T1.USER_ID = T0.CREATED_USER_ID 
                LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = T1.PERSON_ID 
            WHERE (T0.FROM_ID = $sessionUserIdPh AND T0.TO_ID = $toUserIdPh AND T0.DELETED_BY_FROM IS NULL) 
                OR (T0.TO_ID = $sessionUserIdPh AND T0.FROM_ID = $toUserIdPh AND T0.DELETED_BY_TO IS NULL) 
            ORDER BY T0.CREATED_DATE DESC
         */

        $rs = $this->db->SelectLimit($query, $rows, $offset, $bindVars);

        if (isset($rs->_array)) {
            
            $result = $rs->_array;
            
            if (Input::post('isReadUpdate') == 'true' && is_countable($result) && count($result)) {
                
                $updateRows = array_filter($result, function($ar) {
                    return !$ar['READ'];
                });
                
                if ($updateRows) {
                    $this->db->AutoExecute('ZZCHAT', array('READ' => 1), 'UPDATE', "ID IN (".Arr::implode_key(',', $updateRows, 'ID', true).") AND TO_ID = $sessionUserId");
                }
            }
            
            return $result;
        }

        return array();
    }    
    
    public function chatAttachFileModel() {
        
        $filePath = UPLOADPATH . 'chat/';
        $multiFiles = $_FILES['attachFile']['name'];
        $currentDate = Date::currentDate();
        $successFile = array();
        
        foreach ($multiFiles as $k => $realFileName) {
            
            $uid = getUIDAdd($k).'';
            $newFileName = 'file_' . $uid;
            $fileExtension = strtolower(substr($realFileName, strrpos($realFileName, '.') + 1));
            $fileName = $newFileName . '.' . $fileExtension;
            
            $fileAttr['name']     = $realFileName;
            $fileAttr['tmp_name'] = $_FILES['attachFile']['tmp_name'][$k];
            $fileAttr['size']     = $_FILES['attachFile']['size'][$k];
            $fileAttr['type']     = $_FILES['attachFile']['type'][$k];
                                    
            if (in_array($fileExtension, array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff'))) {
                
                Upload::$File = $fileAttr;
                Upload::$method = 0;
                Upload::$SavePath = $filePath;
                Upload::$NewWidth = 1000;
                Upload::$NewName = $newFileName;
                Upload::$OverWrite = true;
                Upload::$CheckOnlyWidth = true;

                $uploadError = Upload::UploadFile();

                if ($uploadError == '') {
                    
                    $uploadResult = true;
                    array_push(FileUpload::$uploadedRealPathFiles, $filePath . $fileName);
                    
                } else {
                    $uploadResult = false;
                    $message = $uploadError;
                }
            
            } else {
                
                FileUpload::SetFileName($fileName);
                FileUpload::SetTempName($fileAttr['tmp_name']);
                FileUpload::SetUploadDirectory($filePath);
                FileUpload::SetValidExtensions(explode(',', Config::getFromCache('CONFIG_FILE_EXT')));
                FileUpload::SetMaximumFileSize(FileUpload::GetConfigFileMaxSize());

                $uploadResult = FileUpload::UploadFile();
                
                if ($uploadResult) {
                    array_push(FileUpload::$uploadedRealPathFiles, $filePath . $fileName);
                }
            }
            
            if ($uploadResult) {
                
                $successFile[] = array(
                    'uid'      => $uid, 
                    'fileInfo' => $realFileName.'$$'.$filePath.'$$'.$fileName.'$$'.$fileExtension.'$$'.$fileAttr['size']
                );
            }
        }

        if ($successFile) {
            $response = array('status' => 'success', 'dateTime' => $currentDate, 'list' => $successFile);
        } else {
            $response = array('status' => 'error', 'message' => $message);
        }
        
        return $response;
    }
    
    public function chatReadMessageModel() {
        
        try {
            
            $mids = Input::post('mids');
            
            if (is_countable($mids) && count($mids)) {
                
                $sessionUserId = Ue::sessionUserId();
            
                $this->db->AutoExecute('ZZCHAT', array('READ' => 1), 'UPDATE', "ID IN (".Arr::implode_r(',', $mids, true).") AND (FROM_ID = $sessionUserId OR TO_ID = $sessionUserId)");
            }
            
            return array('status' => 'success');
            
        } catch (ADODB_Exception $ex) {
            
            return array('status' => 'error', 'message' => $ex->getMessage());
        }
    }
    
    public function removeChatMsgModel() {
        
        try {
            
            $mid = Input::numeric('mid');
            
            if ($mid) {
                
                $sessionUserId = Ue::sessionUserId();
                
                $row = $this->db->GetRow("
                    SELECT 
                        IS_FILE, 
                        MESSAGE, 
                        FORWARD_MESSAGE 
                    FROM ZZCHAT 
                    WHERE ID = ".$this->db->Param(0)." 
                        AND FROM_ID = ".$this->db->Param(1), 
                    array($mid, $sessionUserId)
                );
                
                if ($row) {
                    
                    $this->db->Execute("DELETE FROM ZZCHAT WHERE ID = $mid");
                    
                    if (Input::post('isIgnoreFile') != '1' && $row['IS_FILE'] == '1') {
                        
                        $fileArr = explode('$$', $row['MESSAGE']);
                        
                        if (isset($fileArr[1]) && isset($fileArr[2]) && file_exists($fileArr[1] . $fileArr[2])) {
                            unlink($fileArr[1] . $fileArr[2]);
                        }
                        
                    } elseif ($row['FORWARD_MESSAGE'] != '') {
                        
                        $fileArr = explode('$$', $row['FORWARD_MESSAGE']);
                        
                        if (isset($fileArr[1]) && isset($fileArr[2]) && file_exists($fileArr[1] . $fileArr[2])) {
                            unlink($fileArr[1] . $fileArr[2]);
                        }
                    }
                    
                    return array('status' => 'success');
                    
                } else {
                    return array('status' => 'error', 'message' => 'Invalid chat value!');
                }
                
            } else {
                return array('status' => 'error', 'message' => 'Invalid numeric value!');
            }
            
        } catch (ADODB_Exception $ex) {
            
            return array('status' => 'error', 'message' => $ex->getMessage());
        }
    }
    
    public function removeMassChatMsgModel() {
        
        try {
            
            $headerId = Input::numeric('headerId');
            
            if ($headerId) {
                
                $sessionUserId = Ue::sessionUserId();
                
                $data = $this->db->GetAll("
                    SELECT 
                        ID, 
                        IS_FILE, 
                        MESSAGE, 
                        TO_ID 
                    FROM ZZCHAT 
                    WHERE HEADER_ID = ".$this->db->Param(0)." 
                        AND FROM_ID = ".$this->db->Param(1), 
                    array($headerId, $sessionUserId)
                );
                
                if ($data) {
                    
                    $this->db->Execute("DELETE FROM ZZCHAT WHERE HEADER_ID = " . $this->db->Param(0), array($headerId));
                    
                    $row = $data[0];
                    
                    if ($row['IS_FILE'] == '1') {
                        
                        $fileArr = explode('$$', $row['MESSAGE']);
                        
                        if (isset($fileArr[1]) && isset($fileArr[2]) && file_exists($fileArr[1] . $fileArr[2])) {
                            unlink($fileArr[1] . $fileArr[2]);
                        }
                    }
                    
                    $list = array();
                    
                    foreach ($data as $val) {
                        $list[] = array('mid' => $val['ID'], 'cid' => $val['TO_ID']);
                    }
                    
                    return array('status' => 'success', 'list' => $list);
                    
                } else {
                    return array('status' => 'error', 'message' => 'Invalid chat value!');
                }
                
            } else {
                return array('status' => 'error', 'message' => 'Invalid numeric value!');
            }
            
        } catch (ADODB_Exception $ex) {
            
            return array('status' => 'error', 'message' => $ex->getMessage());
        }
    }
    
    public function checkRemoveChatMsgModel() {
        
        $mid = Input::numeric('mid');
        
        if ($mid) {
            
            $headerId = $this->db->GetOne("
                SELECT 
                    HEADER_ID 
                FROM ZZCHAT 
                WHERE ID = ".$this->db->Param(0)." 
                    AND FROM_ID = ".$this->db->Param(1), 
                array($mid, Ue::sessionUserId()) 
            );
            
            if ($headerId) {
                $response = array(
                    'status'   => 'success', 
                    'isMass'   => true, 
                    'message'  => $this->lang->line('delete_option_message'), 
                    'headerId' => $headerId
                );
            } else {
                $response = array('status' => 'success', 'isMass' => false);
            }
            
        } else {
            $response = array('status' => 'error', 'message' => 'Invalid numeric value!');
        }
        
        return $response;
    }
    
    public function createChatGroupModel() {
        
        try {
            
            $uid           = getUID();
            $sessionUserId = Ue::sessionUserId();
            $isAddUsers    = false;
            $addedUsers    = array();
            
            $data = array(
                'ID'              => $uid,
                'NAME'            => Input::post('groupName'),
                'LASTACTIVITY'    => $uid,
                'CREATEDBY'       => $sessionUserId, 
                'TYPE'            => 1,
                'CREATED_DATE'    => Date::currentDate(),
                'CREATED_USER_ID' => $sessionUserId
            );

            $result = $this->db->AutoExecute('ZZCHAT_CHATROOMS', $data);
            
            if ($result) {
                
                $dataUsers = array(
                    'USERID'          => $sessionUserId,
                    'CHATROOMID'      => $data['ID'],
                    'LASTACTIVITY'    => $uid,
                    'CREATED_DATE'    => $data['CREATED_DATE'],
                    'CREATED_USER_ID' => $sessionUserId
                );

                $this->db->AutoExecute('ZZCHAT_CHATROOMS_USERS', $dataUsers);
                
                $membersCount = 1;
                
                if (Input::isEmpty('sendChatUsers') == false) {
                
                    $sendChatUsers = Input::post('sendChatUsers');
                    $alreadyUsers = array();

                    foreach ($sendChatUsers as $userId) {

                        if ($sessionUserId == $userId || isset($alreadyUsers[$userId])) {
                            continue;
                        }

                        $dataUsers = array(
                            'USERID'          => $userId,
                            'CHATROOMID'      => $data['ID'],
                            'LASTACTIVITY'    => $uid,
                            'CREATED_DATE'    => $data['CREATED_DATE'],
                            'CREATED_USER_ID' => $sessionUserId
                        );

                        $this->db->AutoExecute('ZZCHAT_CHATROOMS_USERS', $dataUsers);
                        
                        $membersCount ++;
                        
                        $isAddUsers = true;
                        $addedUsers[] = $userId;
                        $alreadyUsers[$userId] = 1;
                    }
                }
            }
            
            $response = array(
                'status'       => 'success', 
                'id'           => $data['ID'], 
                'name'         => $data['NAME'], 
                'isAddUsers'   => $isAddUsers, 
                'addedUsers'   => $addedUsers, 
                'membersCount' => $membersCount
            );
            
        } catch (ADODB_Exception $ex) {
            
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }

        return $response;
    }
    
    public function getChatGroupsModel() {
        
        $data = $this->db->GetAll("
            SELECT 
                G.ID, 
                G.NAME, 
                (
                    SELECT 
                        COUNT(CU.USERID) 
                    FROM ZZCHAT_CHATROOMS_USERS CU 
                        INNER JOIN UM_SYSTEM_USER SU ON SU.USER_ID = CU.USERID 
                    WHERE CU.CHATROOMID = G.ID
                ) AS MEMBER_COUNT 
            FROM ZZCHAT_CHATROOMS G 
                INNER JOIN ZZCHAT_CHATROOMS_USERS U ON U.CHATROOMID = G.ID 
            WHERE U.USERID = " . $this->db->Param(0) . " 
            ORDER BY G.LASTACTIVITY DESC", 
            array(Ue::sessionUserId())
        );
        
        return $data;
    }
    
    public function getChatGroupModel($groupId) {
        
        $row = $this->db->GetRow("SELECT * FROM ZZCHAT_CHATROOMS WHERE ID = " . $this->db->Param(0), array($groupId));
        return $row;
    }
    
    public function isAdminGroupModel($groupId) {
        
        $row = $this->db->GetRow("SELECT CREATED_USER_ID FROM ZZCHAT_CHATROOMS WHERE ID = " . $this->db->Param(0), array($groupId));
        
        if ($row && $row['CREATED_USER_ID'] == Ue::sessionUserId()) {
            return true;
        }
        
        return false;
    }
    
    public function getChatGroupCountMembersModel($groupId) {
        
        $count = $this->db->GetOne("
            SELECT 
                COUNT(CU.USERID) 
            FROM ZZCHAT_CHATROOMS_USERS CU 
                INNER JOIN UM_SYSTEM_USER SU ON SU.USER_ID = CU.USERID 
            WHERE CU.CHATROOMID = " . $this->db->Param(0), 
            array($groupId)
        );
        
        return $count;
    }
    
    public function getChatGroupMembersModel($groupId) {
        
        $data = $this->db->GetAll("
            SELECT 
                CU.USERID 
            FROM ZZCHAT_CHATROOMS_USERS CU 
                INNER JOIN UM_SYSTEM_USER SU ON SU.USER_ID = CU.USERID 
            WHERE CU.CHATROOMID = " . $this->db->Param(0) . " 
            ORDER BY CU.LASTACTIVITY ASC", 
            array($groupId)
        );
        
        return $data;
    }
    
    public function updateChatGroupModel() {
        
        try {
            
            $groupId       = Input::numeric('id');
            $sessionUserId = Ue::sessionUserId();
            $isGroupRename = false;
            $isAddUsers    = false;
            $isRemoveUsers = false;
            $addedUsers    = array();
            $removedUsers  = array();
            
            if (Input::isEmpty('groupName') == false) {
                
                $prevRow   = self::getChatGroupModel($groupId);
                $groupName = Input::post('groupName');
                
                if ($prevRow['NAME'] != $groupName) {
                    
                    $dataUpdate = array(
                        'NAME'             => $groupName,
                        'MODIFIED_DATE'    => Date::currentDate(), 
                        'MODIFIED_USER_ID' => $sessionUserId
                    );

                    $this->db->AutoExecute('ZZCHAT_CHATROOMS', $dataUpdate, 'UPDATE', 'ID = ' . $groupId);
                    
                    $isGroupRename = true;
                }
            }
            
            if (Input::isEmpty('addGroupChatUsers') == false) {
                
                $addGroupChatUsers = Input::post('addGroupChatUsers');
                $uid = getUID();
                $alreadyUsers = array();
                
                foreach ($addGroupChatUsers as $key => $val) {
                    
                    if (isset($alreadyUsers[$val])) {
                        continue;
                    }
                    
                    try {
                        
                        $dataUsers = array(
                            'USERID'          => $val,
                            'CHATROOMID'      => $groupId,
                            'LASTACTIVITY'    => $uid + $key,
                            'CREATED_DATE'    => Date::currentDate(),
                            'CREATED_USER_ID' => $sessionUserId
                        );

                        $result = $this->db->AutoExecute('ZZCHAT_CHATROOMS_USERS', $dataUsers);
                        
                        if ($result) {
                            $addedUsers[] = $val;
                            $alreadyUsers[$val] = 1;

                            $isAddUsers = true;
                        }
                        
                    } catch (Exception $ex) {
                        $errorMsg = $ex->getMessage();
                    }
                }
            }
            
            if (Input::isEmpty('removeUserId') == false) {
                
                $removeUserIds = Input::post('removeUserId');
                
                $id1Ph = $this->db->Param(0);
                $id2Ph = $this->db->Param(1);
                
                foreach ($removeUserIds as $key => $val) {
                    
                    $this->db->Execute("DELETE FROM ZZCHAT_CHATROOMS_USERS WHERE CHATROOMID = $id1Ph AND USERID = $id2Ph", array($groupId, $val));
                    
                    $removedUsers[] = $val;
                }
                
                $isRemoveUsers = true;
            }
            
            $membersCount = self::getChatGroupCountMembersModel($groupId);
            
            $response = array(
                'status'        => 'success', 
                'isGroupRename' => $isGroupRename, 
                'groupName'     => $groupName,
                'isAddUsers'    => $isAddUsers, 
                'addedUsers'    => $addedUsers, 
                'isRemoveUsers' => $isRemoveUsers, 
                'removedUsers'  => $removedUsers, 
                'membersCount'  => $membersCount
            );
            
        } catch (Exception $ex) {
            
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }
    
    public function deleteChatGroupModel() {
        
        try {
            
            $groupId = Input::numeric('groupId');
            $idPh = $this->db->Param(0);
            
            $fileMessages = $this->db->GetAll("
                SELECT 
                    MESSAGE 
                FROM ZZCHAT 
                WHERE CHATROOMID = $idPh  
                    AND IS_FILE = 1",  
                array($groupId) 
            );
            
            $this->db->Execute("DELETE FROM ZZCHAT_CHATROOMS_USERS WHERE CHATROOMID = $idPh", array($groupId)); 
            $this->db->Execute("DELETE FROM ZZCHAT WHERE CHATROOMID = $idPh", array($groupId)); 
            $this->db->Execute("DELETE FROM ZZCHAT_CHATROOMS WHERE ID = $idPh", array($groupId)); 
            
            if ($fileMessages) {
                foreach ($fileMessages as $msg) {
                        
                    $fileArr = explode('$$', $msg['MESSAGE']);

                    if (isset($fileArr[1]) && isset($fileArr[2]) && file_exists($fileArr[1] . $fileArr[2])) {
                        unlink($fileArr[1] . $fileArr[2]);
                    }
                }
            }
            
            $response = array('status' => 'success');
            
        } catch (ADODB_Exception $ex) {
            
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }
    
    public function exitChatGroupModel() {
        
        try {
            
            $groupId       = Input::numeric('groupId');
            $sessionUserId = Ue::sessionUserId();
            
            $this->db->Execute("DELETE FROM ZZCHAT_CHATROOMS_USERS WHERE USERID = ".$this->db->Param(0)." AND CHATROOMID = ".$this->db->Param(1), array($sessionUserId, $groupId));
            
            $response = array('status' => 'success');
            
        } catch (ADODB_Exception $ex) {
            
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }
    
    public function saveGroupChatMessageModel() {
        
        try {
            
            $sessionUserId = Ue::sessionUserId();
            
            $data = array(
                'ID'              => Input::numeric('mid'),
                'FROM_ID'         => $sessionUserId,
                'CHATROOMID'      => Input::numeric('to'),
                'MESSAGE'         => Input::post('messageText'),
                'READ'            => 0,
                'IS_FILE'         => Input::post('isFile') == 'true' ? 1 : 0, 
                'CREATED_DATE'    => Input::post('sysDateTime'),
                'CREATED_USER_ID' => $sessionUserId
            );

            $this->db->AutoExecute('ZZCHAT', $data);
            
            $response = array('status' => 'success');
            
        } catch (ADODB_Exception $ex) {
            
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }

        return $response;
    }    
    
    public function getGroupChatHistoryMessagesModel() {

        $page = Input::numeric('page', 1);
        $rows = Input::numeric('rows', 30);
        $offset = ($page - 1) * $rows;
        
        $toUserIdPh = $this->db->Param(0);
        $bindVars = array(Input::numeric('to'));
        
        $query = "
            SELECT 
                T0.ID, 
                T0.FROM_ID, 
                T0.MESSAGE,
                T0.CREATED_DATE, 
                T0.IS_FILE, 
                T0.READ, 
                CASE WHEN BP.FIRST_NAME IS NULL  
                    THEN T1.USERNAME  
                ELSE SUBSTR(BP.LAST_NAME, 0, 1) || '.' || BP.FIRST_NAME END AS USER_FULL_NAME 
            FROM ZZCHAT T0
                INNER JOIN UM_SYSTEM_USER T1 ON T1.USER_ID = T0.FROM_ID 
                LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = T1.PERSON_ID 
            WHERE T0.CHATROOMID = $toUserIdPh 
            ORDER BY T0.CREATED_DATE DESC";

        $rs = $this->db->SelectLimit($query, $rows, $offset, $bindVars);

        if (isset($rs->_array)) {
            
            $result = $rs->_array;
            
            return $result;
        }

        return array();
    }    
    
    public function getChatAllUsersModel() {
        
        $this->db->StartTrans(); 
        $this->db->Execute(Ue::createSessionInfo());
        
        $data = $this->db->GetAll("
            SELECT 
                UM.USER_ID, 
                EMP.PICTURE, 
                SUBSTR(BP.LAST_NAME, 0, 1) || '.' || BP.FIRST_NAME AS USER_FULL_NAME, 
                BP.FIRST_NAME, 
                ORG.DEPARTMENT_NAME 
            FROM UM_SYSTEM_USER UM 
                LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = UM.PERSON_ID 
                LEFT JOIN HRM_EMPLOYEE EMP ON EMP.PERSON_ID = BP.PERSON_ID 
                LEFT JOIN HRM_EMPLOYEE_KEY EMPK ON EMPK.EMPLOYEE_ID = EMP.EMPLOYEE_ID 
                    AND EMPK.IS_ACTIVE = 1 
                LEFT JOIN VW_ZZ_DEPARTMENT ORG ON ORG.DEPARTMENT_ID = EMPK.DEPARTMENT_ID 
            WHERE UM.INACTIVE = 0 OR UM.INACTIVE IS NULL 
                AND UM.USER_ID NOT IN (1, 2) 
            GROUP BY 
                UM.USER_ID, 
                BP.LAST_NAME, 
                BP.FIRST_NAME, 
                EMP.PICTURE, 
                ORG.DEPARTMENT_ID, 
                ORG.DEPARTMENT_NAME");
        
        $this->db->CompleteTrans();
        
        return $data;
    }
    
    public function chatSendMassMessageModel() {
        
        try {
            
            $uid                  = getUID();
            $sessionUserId        = Ue::sessionUserId();
            $sendMsg              = Input::post('sendMsg');
            $massChatSendUserType = Input::post('massChatSendUserType');
            $list                 = array();
            $sentUserList         = array();
            $isFile               = 0;
            
            if (isset($_FILES['attachFile']['name']) && $_FILES['attachFile']['name'] != '') {
            
                $newFileName   = 'file_' . getUID();
                $realFileName  = $_FILES['attachFile']['name'];
                $fileExtension = strtolower(substr($realFileName, strrpos($realFileName, '.') + 1));
                $fileName      = $newFileName . '.' . $fileExtension;
                $filePath      = UPLOADPATH . 'chat/';

                if (in_array($fileExtension, array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff'))) {

                    Upload::$File = $_FILES['attachFile'];
                    Upload::$method = 0;
                    Upload::$SavePath = $filePath;
                    Upload::$NewWidth = 1000;
                    Upload::$NewName = $newFileName;
                    Upload::$OverWrite = true;
                    Upload::$CheckOnlyWidth = true;

                    $uploadError = Upload::UploadFile();

                    if ($uploadError == '') {
                        $uploadResult = true;
                        $isFile = 1;
                    } else {
                        $uploadResult = false;
                        return array('status' => 'error', 'message' => $uploadError);
                    }

                } else {

                    FileUpload::SetFileName($fileName);
                    FileUpload::SetTempName($_FILES['attachFile']['tmp_name']);
                    FileUpload::SetUploadDirectory($filePath);
                    FileUpload::SetValidExtensions(explode(',', Config::getFromCache('CONFIG_FILE_EXT')));
                    FileUpload::SetMaximumFileSize(FileUpload::GetConfigFileMaxSize());

                    $uploadResult = FileUpload::UploadFile();
                }

                if ($uploadResult) {

                    $sendMsg = $realFileName.'$$'.UPLOADPATH.'chat/$$'.$fileName.'$$'.$fileExtension.'$$'.$_FILES['attachFile']['size'].'$$'.$sendMsg;
                    $isFile  = 1;
                }
            }
            
            if ($massChatSendUserType == 'all') {
                
                $users = self::getChatAllUsersModel();
                
                foreach ($users as $k => $userRow) {
                    
                    $userId = $userRow['USER_ID'];
                    
                    if ($sessionUserId == $userId || isset($sentUserList[$userId])) {
                        continue;
                    }

                    $data = array(
                        'ID'              => getUIDAdd($k),
                        'FROM_ID'         => $sessionUserId,
                        'TO_ID'           => $userId,
                        'MESSAGE'         => $sendMsg,
                        'READ'            => 0,
                        'IS_FILE'         => $isFile, 
                        'HEADER_ID'       => $uid, 
                        'CREATED_DATE'    => Date::currentDate(),
                        'CREATED_USER_ID' => $sessionUserId
                    );

                    $this->db->AutoExecute('ZZCHAT', $data);

                    $list[] = array(
                        'mid'      => $data['ID'], 
                        'chatId'   => $data['TO_ID'], 
                        'msg'      => $sendMsg, 
                        'dateTime' => $data['CREATED_DATE'], 
                        'isFile'   => $isFile
                    );
                    
                    $sentUserList[$userId] = 1;
                }
                
                $response = array('status' => 'success', 'list' => $list);
                    
            } else {
                
                if (Input::isEmpty('sendChatUsers') == false) {
                
                    $sendChatUsers = Input::post('sendChatUsers');
                    
                    foreach ($sendChatUsers as $k => $userId) {

                        if ($sessionUserId == $userId || isset($sentUserList[$userId])) {
                            continue;
                        }

                        $data = array(
                            'ID'              => getUIDAdd($k),
                            'FROM_ID'         => $sessionUserId,
                            'TO_ID'           => $userId,
                            'MESSAGE'         => $sendMsg,
                            'READ'            => 0,
                            'IS_FILE'         => $isFile, 
                            'HEADER_ID'       => $uid, 
                            'CREATED_DATE'    => Date::currentDate(),
                            'CREATED_USER_ID' => $sessionUserId
                        );

                        $this->db->AutoExecute('ZZCHAT', $data);

                        $list[] = array(
                            'mid'      => strval($data['ID']), 
                            'chatId'   => $data['TO_ID'], 
                            'msg'      => $sendMsg, 
                            'dateTime' => $data['CREATED_DATE'], 
                            'isFile'   => $isFile
                        );
                        
                        $sentUserList[$userId] = 1;
                    }

                    $response = array('status' => 'success', 'list' => $list);

                } else {
                    $response = array('status' => 'error', 'message' => 'Хэрэглэгч сонгоно уу!');
                }
            }
            
        } catch (ADODB_Exception $ex) {
            
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }

        return $response;
    }
    
    public function chatDownloadFileModel($mid = '') {
        
        if ($mid) {
            $msgId = Input::param($mid);
        } else {
            $msgId = Input::numeric('msgId');
        }
        
        if ($msgId) {
            
            try {
                
                $idPh     = $this->db->Param(0);
                $userIdPh = $this->db->Param(1);

                $row = $this->db->GetRow("
                    SELECT 
                        MESSAGE, 
                        FORWARD_MESSAGE, 
                        IS_FILE, 
                        IS_FILE_DOWNLOADED 
                    FROM ZZCHAT 
                    WHERE (ID = $idPh AND TO_ID IS NULL) 
                        OR (ID = $idPh AND (FROM_ID = $userIdPh OR TO_ID = $userIdPh))", 
                    array($msgId, Ue::sessionUserId())
                );

                $response = array('status' => 'error', 'message' => 'Файл олдсонгүй!');
                $msgArr = array();
                
                if ($row) {
                    
                    if ($row['IS_FILE'] == '1' && $row['MESSAGE']) {
                        
                        $msg    = $row['MESSAGE'];
                        $msgArr = explode('$$', $msg);
                        
                    } elseif ($row['FORWARD_MESSAGE']) {
                        
                        $msg    = $row['FORWARD_MESSAGE'];
                        $msgArr = explode('$$', $msg);
                    }
                }
                
                if (is_countable($msgArr) && count($msgArr) > 1) {
                        
                    $realFileName = $msgArr[0];
                    $path         = $msgArr[1];
                    $fileName     = $msgArr[2];
                    $fileExt      = $msgArr[3];
                    $fullPath     = $path . $fileName;

                    if (file_exists($fullPath)) {

                        if ($row['IS_FILE_DOWNLOADED'] != '1') {
                            $this->db->AutoExecute('ZZCHAT', array('IS_FILE_DOWNLOADED' => 1), 'UPDATE', 'ID = '.$msgId);
                        }

                        $response = array(
                            'status'        => 'success', 
                            'realFileName'  => $realFileName, 
                            'fullPath'      => $fullPath, 
                            'fileExtension' => strtolower($fileExt) 
                        );
                    }
                }
            
            } catch (ADODB_Exception $ex) {
                
                $response = array('status' => 'error', 'message' => $ex->getMessage());
            }
            
        } else {
            $response = array('status' => 'error', 'message' => 'Invalid numeric id!');
        }
        
        return $response;
    }
    
    public function saveContactViewModeModel() {
        
        try {
            
            $this->db->AutoExecute('UM_SYSTEM_USER', 
                array('CHAT_CONTACT_VIEW' => Input::post('view')), 
                'UPDATE', 
                'USER_ID = '.Ue::sessionUserId()
            );
            
            $response = array('status' => 'success');
            
        } catch (ADODB_Exception $ex) {
                
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }
    
    public function getChatOrgListModel() {
        
        $where = '';
        $supportUserId = Config::getFromCache('chatSupportSystemUserId');
        
        if ($supportUserId) {
            $where = 'AND UM.USER_ID <> '.$this->db->Param(0);
        }
        
        $this->db->StartTrans(); 
        $this->db->Execute(Ue::createSessionInfo());
        
        $data = $this->db->GetAll("
            SELECT 
                ORG.DEPARTMENT_ID, 
                ORG.DEPARTMENT_NAME 
            FROM UM_SYSTEM_USER UM 
                INNER JOIN BASE_PERSON BP ON BP.PERSON_ID = UM.PERSON_ID 
                INNER JOIN HRM_EMPLOYEE EMP ON EMP.PERSON_ID = BP.PERSON_ID 
                INNER JOIN HRM_EMPLOYEE_KEY EMPK ON EMPK.EMPLOYEE_ID = EMP.EMPLOYEE_ID 
                    AND EMPK.IS_ACTIVE = 1 
                INNER JOIN VW_ZZ_DEPARTMENT ORG ON ORG.DEPARTMENT_ID = EMPK.DEPARTMENT_ID 
            WHERE (UM.INACTIVE = 0 OR UM.INACTIVE IS NULL) 
                AND UM.IS_USE_CHAT = 1 
                $where 
            GROUP BY 
                ORG.DEPARTMENT_ID, 
                ORG.DEPARTMENT_NAME, 
                ORG.DISPLAY_ORDER 
            ORDER BY TO_NUMBER(ORG.DISPLAY_ORDER) ASC", array($supportUserId));
        
        $this->db->CompleteTrans();
        
        return $data;
    }
    
    public function getChatContactsByOrgIdModel() {
        
        $where = '';
        $supportUserId = Config::getFromCache('chatSupportSystemUserId');
        $orgIdPh = $this->db->Param(0);
        
        if ($supportUserId) {
            $where = 'AND UM.USER_ID <> '.$this->db->Param(1);
        }
        
        $data = $this->db->GetAll("
            SELECT 
                UM.USER_ID
            FROM UM_SYSTEM_USER UM 
                INNER JOIN BASE_PERSON BP ON BP.PERSON_ID = UM.PERSON_ID 
                INNER JOIN HRM_EMPLOYEE EMP ON EMP.PERSON_ID = BP.PERSON_ID 
                INNER JOIN HRM_EMPLOYEE_KEY EMPK ON EMPK.EMPLOYEE_ID = EMP.EMPLOYEE_ID 
                    AND EMPK.IS_ACTIVE = 1 
                    AND EMPK.DEPARTMENT_ID = $orgIdPh  
                INNER JOIN ORG_DEPARTMENT ORG ON ORG.DEPARTMENT_ID = EMPK.DEPARTMENT_ID 
            WHERE (UM.INACTIVE = 0 OR UM.INACTIVE IS NULL) 
                AND UM.IS_USE_CHAT = 1  
                $where 
            GROUP BY 
                UM.USER_ID, 
                BP.FIRST_NAME  
            ORDER BY BP.FIRST_NAME ASC", 
            array(Input::numeric('orgId'), $supportUserId) 
        );
        
        return $data;
    }
    
    public function chatDeleteConversationsModel() {
        
        $chatId = Input::numeric('chatId');
        
        if ($chatId) {
            
            try {
                
                $sessionUserId = Ue::sessionUserId();
                $idPh1 = $this->db->Param(0);
                $idPh2 = $this->db->Param(1);
                
                $this->db->AutoExecute('ZZCHAT', array('DELETED_BY_FROM' => 1), 'UPDATE', "FROM_ID = $sessionUserId AND TO_ID = $chatId");
                $this->db->AutoExecute('ZZCHAT', array('DELETED_BY_TO' => 1), 'UPDATE', "TO_ID = $sessionUserId AND FROM_ID = $chatId");
                
                $deletedMessages = $this->db->GetAll("
                    SELECT 
                        ID, 
                        MESSAGE, 
                        IS_FILE 
                    FROM ZZCHAT 
                    WHERE (
                        (FROM_ID = $idPh1 AND TO_ID = $idPh2) 
                            OR (TO_ID = $idPh1 AND FROM_ID = $idPh2)
                        ) 
                        AND DELETED_BY_FROM = 1 
                        AND DELETED_BY_TO = 1", 
                    array($sessionUserId, $chatId)
                );
                
                if ($deletedMessages) {
                    
                    foreach ($deletedMessages as $row) {
                        
                        if ($row['IS_FILE'] == '1') {
                            $fileArr = explode('$$', $row['MESSAGE']);

                            if (isset($fileArr[1]) && isset($fileArr[2]) && file_exists($fileArr[1] . $fileArr[2])) {
                                unlink($fileArr[1] . $fileArr[2]);
                            }
                        }
                        
                        $this->db->Execute("DELETE FROM ZZCHAT WHERE ID = $idPh1", array($row['ID']));
                    }
                }
                
                $response = array('status' => 'success', 'message' => $this->lang->line('msg_delete_success'));
                
            } catch (Exception $ex) {
                $response = array('status' => 'error', 'message' => $ex->getMessage());
            }
            
        } else {
            $response = array('status' => 'error', 'message' => 'Invalid id!');
        }
        
        return $response;
    }
    
    public function saveChatUserStatusModel() {
        
        try {
            
            $data = array(
                'ID'           => getUID(), 
                'USER_ID'      => Ue::sessionUserId(), 
                'STATUS'       => Input::post('status'), 
                'CREATED_DATE' => Date::currentDate()
            );
            
            $this->db->AutoExecute('ZZCHAT_STATUS', $data);
            
            $response = array('status' => 'success', 'id' => $data['ID'], 'message' => $this->lang->line('msg_save_success'));
            
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }
    
    public function activeChatUserStatusModel() {
        
        try {
            
            $id = Input::numeric('id');
            
            if ($id) {
                
                $sessionUserId = Ue::sessionUserId();
                
                $this->db->AutoExecute('ZZCHAT_STATUS', 
                    array('IS_ACTIVE' => 0), 
                    'UPDATE', 
                    'USER_ID = '.$sessionUserId
                );
                
                if (Input::post('isStatusActive') == '1') {
                    
                    $this->db->AutoExecute('ZZCHAT_STATUS', 
                        array('IS_ACTIVE' => 1, 'MODIFIED_DATE' => Date::currentDate()), 
                        'UPDATE', 
                        "ID = $id AND USER_ID = $sessionUserId"
                    );
                }
                
                $response = array('status' => 'success', 'message' => $this->lang->line('msg_save_success'));
                
            } else {
                $response = array('status' => 'error', 'message' => 'Invalid id!');
            }
            
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }
    
    public function getChatUserStatusModel() {
        
        $data = $this->db->GetAll("
            SELECT 
                ID, 
                STATUS, 
                IS_ACTIVE 
            FROM ZZCHAT_STATUS 
            WHERE USER_ID = ".$this->db->Param(0), 
            array(Ue::sessionUserId())
        );
        
        return $data;
    }
    
    public function updateChatUserStatusModel() {
        
        try {
            
            $id = Input::numeric('id');
            
            if ($id) {
                
                $sessionUserId = Ue::sessionUserId();
                
                $this->db->AutoExecute('ZZCHAT_STATUS', 
                    array('STATUS' => Input::post('status'), 'MODIFIED_DATE' => Date::currentDate()), 
                    'UPDATE', 
                    "ID = $id AND USER_ID = $sessionUserId"
                );
                
                $response = array('status' => 'success', 'message' => $this->lang->line('msg_save_success'));
                
            } else {
                $response = array('status' => 'error', 'message' => 'Invalid id!');
            }
            
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }
    
    public function deleteChatUserStatusModel() {
        
        try {
            
            $id = Input::numeric('id');
            
            if ($id) {
                
                $sessionUserId = Ue::sessionUserId();
                
                $this->db->Execute("DELETE FROM ZZCHAT_STATUS WHERE ID = ".$this->db->Param(0)." AND USER_ID = ".$this->db->Param(1), array($id, $sessionUserId));
                
                $response = array('status' => 'success', 'message' => $this->lang->line('msg_delete_success'));
                
            } else {
                $response = array('status' => 'error', 'message' => 'Invalid id!');
            }
            
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }
    
    public function getChatUserInfoModel() {
        
        $id = Input::numeric('id');
        
        if ($id) {
            
            $chatTooltipDvId = Config::getFromCache('chatTooltipDvId');
                
            if ($chatTooltipDvId) {

                $param = array(
                    'systemMetaGroupId' => $chatTooltipDvId,
                    'showQuery' => 0, 
                    'ignorePermission' => 1, 
                    'criteria' => array(
                        'userId' => array(
                            array(
                                'operator' => '=',
                                'operand' => $id
                            )
                        )
                    )
                );

                $data = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

                if ($data['status'] == 'success' && isset($data['result'][0])) {

                    $this->load->model('mdobject', 'middleware/models/');

                    $columnConfigs = $this->model->getOnlyShowColumnsModel($chatTooltipDvId);

                    unset($data['result']['paging']);
                    unset($data['result']['aggregatecolumns']);

                    $row = $data['result'][0];

                    $row['isdv'] = 1;
                    $row['columnConfigs'] = $columnConfigs;

                    return $row;
                }
            }
            
            $row = $this->db->GetRow("
                SELECT 
                    EMP.PICTURE, 
                    SUBSTR(BP.LAST_NAME, 0, 1) || '.' || BP.FIRST_NAME AS USERFULLNAME, 
                    ORG.DEPARTMENT_NAME AS DEPARTMENTNAME, 
                    HP.POSITION_NAME AS POSITIONNAME,
                    EMP.EMPLOYEE_MOBILE AS EMPLOYEEMOBILE, 
                    EMP.EMPLOYEE_PHONE AS EMPLOYEEPHONE, 
                    EMP.EMPLOYEE_EMAIL AS EMPLOYEEEMAIL,  
                    ZS.STATUS 
                FROM UM_SYSTEM_USER UM 
                    INNER JOIN BASE_PERSON BP ON BP.PERSON_ID = UM.PERSON_ID 
                    INNER JOIN HRM_EMPLOYEE EMP ON EMP.PERSON_ID = BP.PERSON_ID 
                    INNER JOIN HRM_EMPLOYEE_KEY EMPK ON EMPK.EMPLOYEE_ID = EMP.EMPLOYEE_ID 
                        AND EMPK.IS_ACTIVE = 1 
                    INNER JOIN ORG_DEPARTMENT ORG ON ORG.DEPARTMENT_ID = EMPK.DEPARTMENT_ID 
                    LEFT JOIN HRM_POSITION_KEY PK ON PK.POSITION_KEY_ID = EMPK.POSITION_KEY_ID 
                    LEFT JOIN HRM_POSITION HP ON HP.POSITION_ID = PK.POSITION_ID 
                    LEFT JOIN ZZCHAT_STATUS ZS ON ZS.USER_ID = UM.USER_ID 
                        AND ZS.IS_ACTIVE = 1 
                WHERE UM.USER_ID = ".$this->db->Param(0), 
                array($id)
            );
            
            if ($row) {
                $row = Arr::changeKeyLower($row);
            }
            
        } else {
            $row = array();
        }
        
        return $row;
    }
    
    public function chatForwardMessageModel() {
        
        try {
            
            $mid = Input::numeric('mid');
            $row = $this->db->GetRow("
                SELECT 
                    T0.MESSAGE,
                    T0.IS_FILE, 
                    T0.CREATED_DATE, 
                    T0.CREATED_USER_ID, 
                    T0.FORWARD_MESSAGE, 
                    CASE WHEN BP.FIRST_NAME IS NULL  
                        THEN T1.USERNAME  
                    ELSE SUBSTR(BP.LAST_NAME, 0, 1) || '.' || BP.FIRST_NAME END AS USER_FULL_NAME 
                FROM ZZCHAT T0 
                    INNER JOIN UM_SYSTEM_USER T1 ON T1.USER_ID = T0.CREATED_USER_ID  
                    LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = T1.PERSON_ID 
                WHERE T0.ID = ".$this->db->Param(0), 
                array($mid)
            );
            
            if ($row) {
                
                $toUserId = Input::numeric('userId');
                
                if (!$toUserId) {
                    throw new Exception('Invalid userId!'); 
                }
                
                $forwardData = array(
                    'msg'          => $row['MESSAGE'], 
                    'createdDate'  => $row['CREATED_DATE'], 
                    'userFullName' => $row['USER_FULL_NAME'], 
                    'userId'       => $row['CREATED_USER_ID'], 
                    'isFile'       => 0
                );
                
                if ($row['IS_FILE'] == '1') {
                    
                    $fileArr = explode('$$', $row['MESSAGE']);
                    
                    if (isset($fileArr[1]) && isset($fileArr[2]) && file_exists($fileArr[1] . $fileArr[2])) {
                        
                        $path = $fileArr[1];
                        $fileName = $fileArr[2];
                        $fileExtension = $fileArr[3];
                        $filePath = $path . $fileName;
                        $newFileName = 'file_' . getUID() . '.' . $fileExtension;
                        $newPath = $path . $newFileName;
                        
                        @copy($filePath, $newPath);
                        
                        $forwardData['isFile'] = 1;
                        $forwardData['msg'] = $fileArr[0].'$$'.$path.'$$'.$newFileName.'$$'.$fileExtension.'$$'.$fileArr[4];
                        
                    } else {
                        $forwardData['msg'] = 'No file!';
                    }
                    
                } elseif ($row['MESSAGE'] == '' && $row['FORWARD_MESSAGE'] != '') {
                    
                    $forwardData['msg'] = $row['FORWARD_MESSAGE'];
                }
                
                $sessionUserId = Ue::sessionUserId();
            
                $data = array(
                    'ID'              => getUID(),
                    'FROM_ID'         => $sessionUserId,
                    'TO_ID'           => $toUserId,
                    'MESSAGE'         => Input::post('msg'),
                    'READ'            => 0,
                    'IS_FILE'         => 0, 
                    'FORWARD_ID'      => $mid, 
                    'FORWARD_MESSAGE' => $forwardData['msg'],
                    'FORWARD_DATE'    => $forwardData['createdDate'],
                    'CREATED_DATE'    => Date::currentDate(),
                    'CREATED_USER_ID' => $sessionUserId
                );

                $this->db->AutoExecute('ZZCHAT', $data);
                
                $forwardData['id'] = $data['ID'];
                $forwardData['date'] = $data['CREATED_DATE'];
                
                $result = array('status' => 'success', 'forwardData' => $forwardData);
                
            } else {
                $result = array('status' => 'error', 'message' => 'Forward хийх мессеж устсан байна.');
            }
            
        } catch (Exception $ex) {
            $result = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $result;
    }

}