<?php

if (!defined('_VALID_PHP'))
    exit('Direct access to this location is not allowed.');

class Mdnotification_Model extends Model {

    private static $notificationTypeList = "1,2,3,4";

    public function __construct() {
        parent::__construct();
    }

    public function getUnreadNotificationMessageListPhp($limit = 10, $systemId = null) {

        $nowDate = Date::currentDate();
        if ($systemId) {
            $systemId = (float) $systemId;
            $result = $this->db->GetAll("SELECT NALL.id, NALL.notificationtypeid, NALL.notifydate, NALL.message, NALL.create_date FROM (
                    SELECT 
                    N.NOTIFICATION_ID id,
                    N.NOTIFICATION_TYPE_ID notificationtypeid,
                    TO_CHAR(NU.NOTIFY_DATE, 'YYYY-MM-DD HH24:mi:ss') notifydate,
                    N.MESSAGE message,  
                    TO_CHAR(N.created_date, 'YYYY-MM-DD HH24:mi:ss')
                    FROM NTF_NOTIFICATION N 
                    INNER JOIN NTF_NOTIFICATION_USER NU ON N.NOTIFICATION_ID = NU.NOTIFICATION_ID 
                    INNER JOIN NTF_NOTIFICATION_SYSTEM NS ON N.NOTIFICATION_ID = NS.NOTIFICATION_ID
                    WHERE NU.USER_ID = " . Ue::sessionUserKeyId() . " 
                    AND ((TO_CHAR(NU.NOTIFY_DATE, 'YYYY-MM-DD HH24:mi:ss') <= '" . $nowDate . "') OR (NU.NOTIFY_DATE IS NULL))
                    AND NU.READ_DATE IS NULL 
                    AND NS.SYSTEM_ID = $systemId
                    AND N.NOTIFICATION_TYPE_ID IN (" . self::$notificationTypeList . ")
                    ORDER BY NU.NOTIFY_DATE DESC
                    ) NALL 
                WHERE ROWNUM <= $limit");
        } else {
            $result = $this->db->GetAll("SELECT NALL.id, NALL.notificationtypeid, NALL.notifydate, NALL.message, NALL.created_date  FROM (
                    SELECT 
                    N.NOTIFICATION_ID id,
                    N.NOTIFICATION_TYPE_ID notificationtypeid,
                    TO_CHAR(NU.NOTIFY_DATE, 'YYYY-MM-DD HH24:mi:ss') notifydate,
                    N.MESSAGE message,  
                    TO_CHAR(N.created_date, 'YYYY-MM-DD HH24:mi:ss') created_date
                    FROM NTF_NOTIFICATION N 
                    INNER JOIN NTF_NOTIFICATION_USER NU ON N.NOTIFICATION_ID = NU.NOTIFICATION_ID
                    WHERE NU.USER_ID = " . Ue::sessionUserKeyId() . " 
                    AND ((TO_CHAR(NU.NOTIFY_DATE, 'YYYY-MM-DD HH24:mi:ss') <= '" . $nowDate . "') OR (NU.NOTIFY_DATE IS NULL))
                    AND NU.READ_DATE IS NULL
                    AND N.NOTIFICATION_TYPE_ID IN (" . self::$notificationTypeList . ")
                    ORDER BY NU.NOTIFY_DATE DESC
                    ) NALL 
                WHERE ROWNUM <= $limit");
        }

        return $result;
    }

    public function getUnreadNotificationMessageListPhpNew($limit = 10, $notificationTypeList = '1,2,3,4', $systemId = null) {			

        $nowDate = Date::currentDate();
        $sessionUserKeyId = Ue::sessionUserKeyId();
        $result = array();
        
        if ($systemId) {
            
            $result = $this->db->GetAll("
                SELECT 
                    NALL.ID, 
                    NALL.NOTIFICATIONTYPEID, 
                    NALL.NOTIFYDATE, 
                    NALL.MESSAGE, 
                    NALL.CREATE_DATE, 
                    NALL.NOTIFICATION_USER_ID AS NOTIFICATIONUSERID, 
                    NALL.IS_NO_MSG_SUBSTR 
                FROM (
                    SELECT 
                        N.NOTIFICATION_ID AS ID,
                        N.NOTIFICATION_TYPE_ID AS NOTIFICATIONTYPEID, 
                        N.IS_NO_MSG_SUBSTR, 
                        NVL(TO_CHAR(NU.CREATED_DATE,'YYYY-MM-DD HH24:MI:SS'), NU.NOTIFY_DATE) AS NOTIFYDATE,
                        NU.MESSAGE,  
                        TO_CHAR(N.CREATED_DATE, 'YYYY-MM-DD HH24:MI:SS') AS CREATED_DATE,
                        NU.NOTIFICATION_USER_ID 
                    FROM NTF_NOTIFICATION N 
                        INNER JOIN NTF_NOTIFICATION_USER NU ON N.NOTIFICATION_ID = NU.NOTIFICATION_ID 
                        INNER JOIN NTF_NOTIFICATION_SYSTEM NS ON N.NOTIFICATION_ID = NS.NOTIFICATION_ID
                    WHERE NU.USER_ID = $sessionUserKeyId 
                        AND ((TO_CHAR(NU.NOTIFY_DATE, 'YYYY-MM-DD HH24:MI:SS') <= '$nowDate') OR (NU.NOTIFY_DATE IS NULL))
                        AND NU.READ_DATE IS NULL 
                        AND NS.SYSTEM_ID = $systemId
                        AND N.NOTIFICATION_TYPE_ID NOT IN (5)
                    ORDER BY NU.CREATED_DATE DESC
                ) NALL 
                WHERE ROWNUM <= $limit");
            
        } else {
            
            $where = '';
            if (Config::getFromCache('hideSeenNotification')) {
                $where = 'AND NU.VIEWED_DATE IS NULL';
            }
            
            $order = '';
            if (Config::getFromCache('isNtfNotViewedDateFirst')) {
                $order = 'NT.IS_VIEWED_DATE ASC, ';
            }
            
            $searchValue = Input::post('q');
            if ($searchValue) {
                $searchValue = Str::upper($searchValue);
                $where .= " AND (UPPER(NU.MESSAGE) LIKE '%".$searchValue."%' OR UPPER(NTP.NTF_PARAM_VALUE) LIKE '%".$searchValue."%' OR UPPER(N.MESSAGE) LIKE '%".$searchValue."%')";
            }

            $page = Input::post('p', 1);
            $offset = ($page - 1 ) * $limit;            
            
            $rs = $this->db->SelectLimit("
                SELECT 
                    NT.*, 
                    NU.MESSAGE, 
                    N.MESSAGE AS MESSAGE_LIST 
                FROM (
                    SELECT 
                        N.NOTIFICATION_ID AS ID, 
                        N.NOTIFICATION_TYPE_ID AS NOTIFICATIONTYPEID, 
                        N.IS_NO_MSG_SUBSTR, 
                        ".$this->db->IfNull("TO_CHAR(NU.CREATED_DATE, 'YYYY-MM-DD HH24:MI:SS')", "TO_CHAR(NU.NOTIFY_DATE, 'YYYY-MM-DD HH24:MI:SS')")." AS NOTIFYDATE, 
                        TO_CHAR(N.CREATED_DATE, 'YYYY-MM-DD HH24:MI:SS') AS CREATED_DATE, 
                        NU.NOTIFICATION_USER_ID AS NOTIFICATIONUSERID, 
                        NU.VIEWED_DATE, 
                        CASE WHEN NU.VIEWED_DATE IS NULL THEN 0 ELSE 1 END AS IS_VIEWED_DATE, 
                        NT.ICON, 
                        NT.ICON_COLOR, 
                        MPN.IS_SEND_LIST
                    FROM NTF_NOTIFICATION N 
                        INNER JOIN NTF_NOTIFICATION_USER NU ON N.NOTIFICATION_ID = NU.NOTIFICATION_ID 
                        LEFT JOIN NTF_NOTIFICATION_TYPE NT ON NT.NOTIFICATION_TYPE_ID = N.NOTIFICATION_TYPE_ID 
                        LEFT JOIN META_PROCESS_NTF MPN ON MPN.NOTIFICATION_ID = N.NOTIFICATION_ID 
                            AND MPN.IS_SEND_LIST = 1 
                        LEFT JOIN NTF_NOTIFICATION_USER_PARAM NTP ON NTP.NOTIFICATION_USER_ID = NU.NOTIFICATION_USER_ID 
                    WHERE NU.USER_ID = ".$this->db->Param(0)."  
                        AND N.NOTIFICATION_TYPE_ID NOT IN (5) 
                        AND ((TO_CHAR(NU.NOTIFY_DATE, 'YYYY-MM-DD HH24:MI:SS') <= ".$this->db->Param(1).") OR (NU.NOTIFY_DATE IS NULL)) 
                        $where     
                    GROUP BY N.NOTIFICATION_ID,
                        N.NOTIFICATION_TYPE_ID,
                        N.IS_NO_MSG_SUBSTR,
                        ".$this->db->IfNull("TO_CHAR(NU.CREATED_DATE, 'YYYY-MM-DD HH24:MI:SS')", "TO_CHAR(NU.NOTIFY_DATE, 'YYYY-MM-DD HH24:MI:SS')").",
                        TO_CHAR(N.CREATED_DATE, 'YYYY-MM-DD HH24:MI:SS'),
                        NU.NOTIFICATION_USER_ID,
                        NU.VIEWED_DATE,
                        NT.ICON,
                        NT.ICON_COLOR,
                        MPN.IS_SEND_LIST
                ) NT
                    INNER JOIN NTF_NOTIFICATION N ON NT.ID = N.NOTIFICATION_ID 
                    INNER JOIN NTF_NOTIFICATION_USER NU ON NT.NOTIFICATIONUSERID = NU.NOTIFICATION_USER_ID
                ORDER BY $order NT.NOTIFYDATE DESC", $limit, $offset, array($sessionUserKeyId, $nowDate));
            
            if ($rs && isset($rs->_array)) {
                $result = $rs->_array;
            }
        }
        
        if (isset($result) && $result) {
        
            foreach ($result as $key => $value) {
                
                if ($value['IS_SEND_LIST'] == '1' && $value['MESSAGE_LIST']) {
                    
                    $result[$key]['MESSAGE'] = $value['MESSAGE_LIST'];
                    
                } else {
                
                    $replaceParams = $this->findParamsNew($value['NOTIFICATIONUSERID']);
                    if ($replaceParams != null) {
                        $result[$key]['MESSAGE'] = $this->replaceMessageNew($value['MESSAGE'], $replaceParams);
                    }
                }
            }
        }
        
        return $result;
    }

    public function findParamsNew($notificaitonUserId) {
        
        $data = $this->db->GetAll("
            SELECT 
                NTF_PARAM_PATH AS NAME, 
                NTF_PARAM_VALUE AS VALUE 
            FROM NTF_NOTIFICATION_USER_PARAM 
            WHERE NOTIFICATION_USER_ID = " . $this->db->Param(0), 
            array($notificaitonUserId)
        );
        
        return $data;
    }

    public function getUnreadNotificationMessageCount($systemId = null) {
        try {
            $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'PL_NTF_UNMC_005', array('userid' => Ue::sessionUserKeyId(), 'systemId' => $systemId));
            if (isset($result['result'])) {
                return $result['result']['result'];
            }

            return 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function getUnreadNotificationMessageCountPhp($notificationTypeList='1,2,3,4', $systemId = null) {
        
        $currentDate = Date::currentDate();
        $sessionUserKeyId = Ue::sessionUserKeyId();
        
        if ($systemId) {
            $systemId = (float) $systemId;
            return $this->db->GetOne("
                SELECT 
                    COUNT(N.NOTIFICATION_ID) C 
                FROM NTF_NOTIFICATION N 
                    INNER JOIN NTF_NOTIFICATION_USER NU ON N.NOTIFICATION_ID = NU.NOTIFICATION_ID 
                    INNER JOIN NTF_NOTIFICATION_SYSTEM NS ON N.NOTIFICATION_ID = NS.NOTIFICATION_ID 
                WHERE ((TO_CHAR(NU.NOTIFY_DATE, 'YYYY-MM-DD HH24:MI:SS') <= '$currentDate') OR (NU.NOTIFY_DATE IS NULL)) 
                    AND NU.READ_DATE IS NULL 
                    AND N.NOTIFICATION_TYPE_ID NOT IN (5) 
                    AND NU.USER_ID = $sessionUserKeyId AND NS.SYSTEM_ID = " . $systemId);
        } else {
            /* 
             * api/notify.php мөн адил засвар хийнэ
             */
            
            return $this->db->GetOne("
                SELECT 
                    COUNT(N.NOTIFICATION_ID) C 
                FROM NTF_NOTIFICATION N 
                    INNER JOIN NTF_NOTIFICATION_USER NU ON N.NOTIFICATION_ID = NU.NOTIFICATION_ID 
                WHERE NU.USER_ID = ".$this->db->Param(0)."
                    AND N.NOTIFICATION_TYPE_ID NOT IN (5) 
                    AND NU.READ_DATE IS NULL     
                    AND ((NU.NOTIFY_DATE <= ".$this->db->ToDate($this->db->Param(1), 'YYYY-MM-DD HH24:MI:SS').") OR (NU.NOTIFY_DATE IS NULL))", 
                array($sessionUserKeyId, $currentDate) 
            );
        }
    }

    public function getNotificationPhp($notificationId) {
        try {
            $notification = $this->db->GetRow("SELECT * FROM NTF_NOTIFICATION WHERE NOTIFICATION_ID = " . $notificationId);
            return $notification;
        } catch (Exception $e) {
            return null;
        }
    }

    public function getNotificationUser($notificationUserId) {
        try {
            $notificationUser = $this->db->GetRow("SELECT * FROM NTF_NOTIFICATION_USER WHERE NOTIFICATION_USER_ID = " . $notificationUserId);
            return $notificationUser;
        } catch (Exception $e) {
            return null;
        }
    }

    public function markAsReadNotificationPhp($notificationId) {
        try {
            $userId = Ue::sessionUserKeyId();
            $data = array('READ_DATE' => Date::currentDate());
            $this->db->AutoExecute('NTF_NOTIFICATION_USER', $data, 'UPDATE', "NOTIFICATION_ID = " . $notificationId . " AND USER_ID = $userId");
            return true;
        } catch (Exception $e) {
            return null;
        }
    }

    public function markAsReadNotificationUser($notificationUserId) {
        try {
            
            $currentDate = Date::currentDate();
            
            $data = array('READ_DATE' => $currentDate, 'VIEWED_DATE' => $currentDate);
            $this->db->AutoExecute('NTF_NOTIFICATION_USER', $data, 'UPDATE', 'NOTIFICATION_USER_ID = ' . $notificationUserId);
            
            return true;
        } catch (Exception $e) {
            return null;
        }
    }

    public function getAllNotificationMessageList($systemId = null) {
        try {
            $param = array(
                'userid' => Ue::sessionUserKeyId(),
                'status' => 'all',
                'systemId' => $systemId
            );
            $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'PL_NTML_004', $param);
            
            if ($result) {
                return $result['result'];
            } else {
                return null;
            }
        } catch (Exception $e) {
            return null;
        }
    }

    public function createSendNotificationPhp($templateCode, $url, $notificationTypeId, $toUserId, $systemId = null, $inputParams = null) {
        $nowDate = Date::currentDate();
        try {
            $message = $this->replaceMessage($templateCode, $inputParams);
            $id = getUniqId();
            $data = array(
                'NOTIFICATION_ID' => $id,
                'CREATE_DATE' => $nowDate,
                'CREATE_USER_ID' => Ue::sessionUserKeyId(),
                'NOTIFICATION_TYPE_ID' => $notificationTypeId,
                'MESSAGE' => $message,
                'DIRECT_URL' => $url,
                'TEMPLATE_ID' => $this->findTemplateIdByCode($templateCode)
            );
            $this->db->AutoExecute('NTF_NOTIFICATION', $data);

            $notificationSystemId = getUniqId();
            $notificationSystemData = array(
                'ID' => $notificationSystemId,
                'NOTIFICATION_ID' => $id,
                'SYSTEM_ID' => $systemId
            );
            $this->db->AutoExecute('NTF_NOTIFICATION_SYSTEM', $notificationSystemData);

            if (is_array($toUserId)) {
                foreach ($toUserId AS $oneUserId) {
                    $notificationUserId = getUniqId();
                    $notificationUserData = array(
                        'NOTIFICATION_USER_ID' => $notificationUserId,
                        'NOTIFICATION_ID' => $id,
                        'USER_ID' => $oneUserId['userId'],
                        'NOTIFY_DATE' => $nowDate
                    );
                    $this->db->AutoExecute('NTF_NOTIFICATION_USER', $notificationUserData);
                }
            } else {
                $notificationUserId = getUniqId();
                $notificationUserData = array(
                    'NOTIFICATION_USER_ID' => $notificationUserId,
                    'NOTIFICATION_ID' => $id,
                    'USER_ID' => $toUserId,
                    'NOTIFY_DATE' => $nowDate
                );
                $this->db->AutoExecute('NTF_NOTIFICATION_USER', $notificationUserData);
            }

            return $id;
        } catch (Exception $e) {
            return null;
        }
    }

    public function sendNotificationPhp($notificationId, $userId, $notifyDate = null, $roleId = null) {
        if ($userId == null) {
            return null;
        }
        try {
            $notificationUserId = getUniqId();
            $notificationUserData = array(
                'NOTIFICATION_USER_ID' => $notificationUserId,
                'NOTIFICATION_ID' => $notificationId,
                'USER_ID' => $userId,
                'NOTIFY_DATE' => ($notifyDate) ? $notifyDate : Date::currentDate(),
                'ROLE_ID' => $roleId,
                'CREATED_DATE' => Date::currentDate(),
                'CREATED_USER_ID' => Ue::sessionUserKeyId()
            );
            $this->db->AutoExecute('NTF_NOTIFICATION_USER', $notificationUserData);

            return $notificationUserId;
        } catch (Exception $e) {
            return null;
        }
    }

    private function putRowState($inputParams) {

        if ($inputParams) {
            if (is_array($inputParams)) {
                foreach ($inputParams AS $k => $row) {
                    $inputParams[$k]['rowState'] = 'unchanged';
                    $inputParams[$k]['id'] = null;
                }
            }
        }

        return $inputParams;
    }

    private function replaceMessage($templateCode, $inputParams) {
        $message = $this->db->GetOne("SELECT MONGOLIAN FROM NTF_TEMPLATE NT INNER JOIN GLOBE_DICTIONARY GD ON GD.CODE = NT.GLOBE_DICTIONARY_CODE WHERE NT.GLOBE_DICTIONARY_CODE = '" . $templateCode . "'");
        if ($message) {
            $messageStringArray = explode(" ", $message); // array болгож задлана
            foreach ($messageStringArray AS $k => $oneString) { // element болгоноор давтана
                $first = strpos($oneString, "["); // # element хайж байна
                if ($first === 0) { // байвал
                    $key = substr($oneString, 1, strlen($oneString) - 2); // түлхүүр үг
                    foreach ($inputParams AS $inputParam) {
                        if ($inputParam['name'] == $key) {
                            $messageStringArray[$k] = $inputParam['value']; // түлхүүрээр утга оноож байна
                        }
                    }
                }
            }

            $message = implode(" ", $messageStringArray);
        }
        return $message;
    }

    private function replaceMessageNew($message, $inputParams, $determiner = '[', $determinerLast = ']') {
        if ($inputParams != null) {
            $messageStringArray = explode(' ', $message); // array болгож задлана
            foreach ($messageStringArray AS $k => $oneString) { // element болгоноор давтана
                $first = strpos($oneString, $determiner); // [ element хайж байна
                $second = strpos($oneString, $determinerLast); // ] element хайж байна
                if ($first === 0) { // байвал
                    $key = substr($oneString, 1, $second - 1); // түлхүүр үг
                    foreach ($inputParams AS $inputParam) {
                        if (strtolower($inputParam['NAME']) == strtolower($key)) {
                            $messageStringArray[$k] = $inputParam['VALUE']; // түлхүүрээр утга оноож байна
                        }
                    }
                }
            }

            $message = implode(' ', $messageStringArray);
        }
        return $message;
    }

    private function findTemplateIdByCode($templateCode) {
        $templateId = $this->db->GetOne("SELECT NT.TEMPLATE_ID FROM NTF_TEMPLATE NT INNER JOIN GLOBE_DICTIONARY GD ON GD.CODE = NT.GLOBE_DICTIONARY_CODE WHERE NT.GLOBE_DICTIONARY_CODE = '" . $templateCode . "'");
        return $templateId;
    }

    public function saveNotificaitonConfig($processId, $dataArray) {
        try {
            $this->clearMetaProcessNtf($processId);
            for ($i = 0; $i < count($dataArray['notificationId[]']); $i++) {
                $id = getUniqId();
                $data = array(
                    'ID' => $id,
                    'BUSINESS_PROCESS_LINK_ID' => $processId,
                    'NOTIFICATION_ID' => $dataArray['notificationId[]'][$i],
                    'IN_PARAM_CRITERIA' => $dataArray['inParamCriteria[]'][$i],
                    'OUT_PARAM_CRITERIA' => $dataArray['outParamCriteria[]'][$i],
                    'USER_QUERY' => $dataArray['userQuery[]'][$i],
                    'NOTIFY_DATE' => $dataArray['notifyDate[]'][$i],
                    'CREATED_DATE' => Date::currentDate(),
                    'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                    'IS_SEND_MAIL' => isset($dataArray['isSendMail[]'][$i]) ? $dataArray['isSendMail[]'][$i] : null
                );
                $this->db->AutoExecute('META_PROCESS_NTF', $data);
            }

            $this->saveProcessNotificationParam($id, $dataArray);

            return $id;
        } catch (Exception $e) {
            return false;
        }
    }

    private function saveProcessNotificationParam($id, $dataArray) {
        try {
            for ($i = 0; $i < count($dataArray['notificationId[]']); $i++) {
                if (!isset($dataArray['notificationInputParamsp[' . $i . '][]'])) {
                    continue;
                }
                $notificationParamList = $dataArray['notificationParamList[' . $i . '][]'];
                $paramPathArray = $dataArray['notificationInputParamsp[' . $i . '][]'];
                $defaultValueArray = $dataArray['defaultValue[' . $i . '][]'];
                // repeate params
                foreach ($notificationParamList AS $key => $val) {
                    $data = array(
                        'ID' => getUniqId(),
                        'PROCESS_NTF_ID' => $id,
                        'NTF_PARAM_PATH' => $val,
                        'PROCESS_PARAM_PATH' => $paramPathArray[$key],
                        'DEFAULT_VALUE' => $defaultValueArray[$key]
                    );
                    $this->db->AutoExecute('META_PROCESS_NTF_PARAM', $data);
                }
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    private function clearMetaProcessNtf($processId) {
        try {
            // clear
            $metaProcessNtfList = $this->db->GetAll("SELECT DISTINCT ID FROM META_PROCESS_NTF WHERE BUSINESS_PROCESS_LINK_ID = $processId");
            foreach ($metaProcessNtfList AS $metaProcessNtf) {
                $tmpId = $metaProcessNtf['ID'];
                $this->db->Execute("DELETE FROM META_PROCESS_NTF_PARAM WHERE PROCESS_NTF_ID = " . $tmpId);
            }
            $this->db->Execute("DELETE FROM META_PROCESS_NTF WHERE BUSINESS_PROCESS_LINK_ID = " . $processId);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getNotificationConfig($processMetaDataId) {
        $result = $this->db->GetAll("SELECT * FROM META_PROCESS_NTF WHERE BUSINESS_PROCESS_LINK_ID = $processMetaDataId");
        if ($result) {
            foreach ($result AS $k => $v) {
                $paramArray = $this->getProcessNotificationParam($v['ID']);
                $result[$k]['PARAMS'] = $paramArray;
            }
            return $result;
        } else {
            return array(0 => array());
        }
    }

    public function getProcessNotificationParam($processNtfId) {
        $result = $this->db->GetAll("SELECT * FROM META_PROCESS_NTF_PARAM WHERE PROCESS_NTF_ID = $processNtfId");
        if ($result) {
            return $result;
        } else {
            return array(0 => array());
        }
    }

    public function getOutputMetas($outputMetaDataId) {
        $result = $this->db->GetAll("SELECT MD.META_DATA_CODE, MD.META_DATA_NAME, MD.META_DATA_ID 
                                    FROM META_GROUP_CONFIG MGC 
                                    WHERE MGC.MAIN_META_DATA_ID = $outputMetaDataId ORDER BY MGC.DISPLAY_ORDER ASC");
        if ($result) {
            return $result;
        } else {
            return array(0 => array());
        }
    }

    public function checkHasNotification($processId, $id) {
        try {
            $hasNotificationArray = $this->db->GetAll("
                SELECT NP.PROCESS_META_DATA_ID, 
                NP.USER_PROCESS_META_DATA_ID, 
                NP.NOTIFICATION_ID, 
                NP.NOTIFY_DATE, 
                MD.META_DATA_CODE, 
                WL.SERVICE_LANGUAGE_CODE, 
                BL.CLASS_NAME, 
                BL.METHOD_NAME, 
                BL.WS_URL, 
                BL.SUB_TYPE, 
                BL.ID BUSINESS_PROCESS_LINK_ID  
                FROM NTF_NOTIFICATION_PROCESS NP
                LEFT JOIN META_BUSINESS_PROCESS_LINK BL ON NP.PROCESS_META_DATA_ID = BL.META_DATA_ID
                LEFT JOIN META_DATA MD ON NP.USER_PROCESS_META_DATA_ID = MD.META_DATA_ID 
                LEFT JOIN WEB_SERVICE_LANGUAGE WL ON BL.SERVICE_LANGUAGE_ID = WL.SERVICE_LANGUAGE_ID
                WHERE NP.PROCESS_META_DATA_ID = $processId");
            if (count($hasNotificationArray) > 0) {
                foreach ($hasNotificationArray AS $notificationProcess) {
                    $getUserListProcessId = $notificationProcess['USER_PROCESS_META_DATA_ID'];
                    $param = $this->collectParams($getUserListProcessId, $id);
                    $result = $this->ws->caller($notificationProcess['SERVICE_LANGUAGE_CODE'], $notificationProcess['WS_URL'], $notificationProcess['META_DATA_CODE'], 'return', $param);
                    if ($result['status'] == 'success') {
                        foreach ($result['result'] AS $userArray) {
                            $this->sendNotificationPhp($notificationProcess['NOTIFICATION_ID'], $userArray['userid'], $notificationProcess['NOTIFY_DATE'], null);
                        }
                    }
                }

                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    private function collectParams($processMetaDataId, $id) {
        $param = array('id' => $id);
        $paramList = $this->db->GetAll("SELECT DEFAULT_VALUE, PARAM_NAME FROM META_PROCESS_PARAM_ATTR_LINK WHERE PROCESS_META_DATA_ID = $processMetaDataId");
        foreach ($paramList AS $row) {
            $param[$row['PARAM_NAME']] = $row['DEFAULT_VALUE'];
        }
        return $param;
    }

    public function findParams($notificaitonId) {
        if ($notificaitonId == null) {
            return false;
        }
        return $this->db->GetRow("SELECT 
                N.NOTIFICATION_ID, 
                NVL2(GD.MONGOLIAN, GD.MONGOLIAN, N.MESSAGE) TEXT,
                substr(NVL2(GD.MONGOLIAN, GD.MONGOLIAN, N.MESSAGE), 1, 25) || '...' TEXT_SHORT, 
                N.DIRECT_URL,
                NT.NOTIFICATION_TYPE_NAME 
                FROM NTF_NOTIFICATION N
                LEFT JOIN GLOBE_DICTIONARY GD ON GD.CODE = N.MESSAGE 
                LEFT JOIN NTF_NOTIFICATION_TYPE NT ON N.NOTIFICATION_TYPE_ID = NT.NOTIFICATION_TYPE_ID 
                WHERE N.NOTIFICATION_ID = $notificaitonId");
    }

    public function createNotificationModel($notifId, $currentDate, $sessionUserKeyId, $message, $notificationTypeId, $qry,  $directUrl) {
        if ($directUrl) {
            $data = array(
                'NOTIFICATION_ID' => $notifId,
                'NOTIFICATION_TYPE_ID' => $notificationTypeId,
                'CREATED_DATE' => $currentDate,
                'CREATED_USER_ID' => $sessionUserKeyId,
                'MESSAGE' => $message,
                'DIRECT_URL' => $directUrl);

            $result = $this->db->AutoExecute('NTF_NOTIFICATION', $data);
            if ($result && $qry) {
                $this->db->Execute("INSERT INTO NTF_NOTIFICATION_USER ($qry)");
                return true;
            }
        }
        return false;
    }
    
    public function setReadAllNotificationByUserModel() {
        
        $sessionUserKeyId = Ue::sessionUserKeyId();
        $currentDate = Date::currentDate();
        
        $this->db->Execute("
            UPDATE 
                NTF_NOTIFICATION_USER 
            SET READ_DATE = ".$this->db->Param(0)." 
            WHERE NOTIFICATION_USER_ID IN (
                SELECT 
                    NU.NOTIFICATION_USER_ID 
                FROM NTF_NOTIFICATION N 
                    INNER JOIN NTF_NOTIFICATION_USER NU ON N.NOTIFICATION_ID = NU.NOTIFICATION_ID 
                WHERE NU.READ_DATE IS NULL 
                    AND N.NOTIFICATION_TYPE_ID NOT IN (5) 
                    AND NU.USER_ID = ".$this->db->Param(1)." 
            )", array($currentDate, $sessionUserKeyId));
    }
    
}