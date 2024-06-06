<?php if(!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Conference_model extends Model {
    

    private static $gfServiceAddress = GF_SERVICE_ADDRESS;
    private static $getDataViewCommand = 'PL_MDVIEW_004';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function fncRunDataview($dataviewId, $field = "", $operand = "", $operator = "", $paramFilter = "", $iscriteriaOnly = "0" ) {
        
        if ($iscriteriaOnly) {
            $criteria = $paramFilter;
        } else {
            $criteria = array(
                            $field => array(
                                array(
                                    'operator' => $operator,
                                    'operand' => $operand
                                )
                            )
                        );
            
            if ($paramFilter) {
                foreach ($paramFilter as $key => $param) {
                    $criteria[$key] = $param;
                }
                
            }
        }
        
        includeLib('Utils/Functions');
        $data = Functions::runDataViewWithoutLogin($dataviewId, $criteria);
        
        (Array) $response = array();
        
        if (isset($data['result']) && $data['result']) {
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);
            $response = $data['result'];
        }
        
        return $response;
        
    }
    
    public function memberRoleModel($param) {
        $data = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'CMS_READ_ONLY_GET_DV_004', $param);
        
        if (isset($data['status']) && $data['status'] === 'success') {

            return array(
                'status' => 'success', 
                'id' => issetParam($data['result']['id']),
                'roleid' => issetParam($data['result']['id']),
                'readonly' => isset($data['result']['readonly']) ? $data['result']['readonly'] : '0',
                'showbutton' => isset($data['result']['showbutton']) ? $data['result']['showbutton'] : '0'
            );

        } else {
            $message = (isset($data['text']) && $data['text']) ? $data['text'] : 'Error олдсонгүй';
            return array('status' => 'error', 'message' => $message );
        }
    }
    
    public function reviewGovByIdModel($id) {
        $criteria = array(
            'id' => array(
                array(
                    'operator' => '=',
                    'operand' => $id
                )
            )
        );

        $param = array(
            'systemMetaGroupId' => '1564710581331',
            'showQuery' => '0',
            'ignorePermission' => 1,
        );

        if ($criteria) {
            $param['criteria'] = $criteria;
        }

        $data = $this->ws->run('serialize', self::$getDataViewCommand, $param, self::$gfServiceAddress);

        if (isset($data['result']) && $data['result']) {
            return $data['result'];
        } else {
            return array();
        }
    }
    
    public function memberPercentIdModel($id ,$bookid,$keyid, $statusid) {

        $currentDate = Date::currentDate('H:i');

        $dparam = array(
            'id' => $id,
            'time1' =>$currentDate,
            'employeekeyid'=>$keyid,
            'wfmstatus'=>$statusid,
            'wfmstatusid'=>$statusid,
            'bookid' => $bookid,
        );
       
        $data = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'CMS_MEETING_PARTICIPANT_DV_002', $dparam);
        
        if (isset($data['result']) && $data['result']) {
            $param = array(
                'id' => $bookid,
            );

            $data = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'CMS_MEETING_PERC_SET_LIST_004', $param);

            if (isset($data['status']) && $data['status'] === 'success') {

                return array(
                    'status' => 'success', 
                    'percent' => $data['result']['perc'],
                    'time1' => $currentDate
                );

            } else {
                $message = (isset($data['text']) && $data['text']) ? $data['text'] : 'Error олдсонгүй';
                return array('status' => 'error', 'message' => $message );
            }

        } else {
            return array();
        }
    }

    public function memberPercentIdModelV1($id ,$bookid,$keyid) {

        $currentDate = Date::currentDate('H:i');

        $dparam = array(
            'id' => $id,
            'bookid' => $bookid,
            'employeekeyid'=>$keyid,
            'participantroleid'=> '1',
            'isactive'=> '1',
        );
        
        $data = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'CMS_MEETING_PARTICIPANT_DV_002', $dparam);
        
       
        if (isset($data['result']) && $data['result']) {
            $param = array(
                'id' => $bookid,
            );
            $data = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'CMS_MEETING_PERC_SET_LIST_004', $param);
            
            if (isset($data['status']) && $data['status'] === 'success') {
                $this->db->AutoExecute('MMS_MEETING_PARTICIPANT', array('WFM_STATUS_ID' => NULL), 'UPDATE', "ID = '$id'");
                
                return array(
                    'status' => 'success', 
                    'percent' => $data['result']['perc'],
                );

            } else {
                $message = (isset($data['text']) && $data['text']) ? $data['text'] : 'Error олдсонгүй';
                return array('status' => 'error', 'message' => $message );
            }

        } else {
            return array();
        }
    }
    
    public function memberPercentIdModel22($id ,$bookid,$keyid,$statusid) {

        $currentDate = Date::currentDate('H:i');

        $dparam = array(
            'id' => $id,
            'time1' =>$currentDate,
            'employeekeyid'=>$keyid,
            'wfmstatusid'=>$statusid,
            'bookid' => $bookid,
        );
        
        $data = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'CMS_MEETING_ATT_SHOW_DV_002', $dparam);

        if (isset($data['result']) && $data['result']) {
            $param = array(
                'id' => $bookid,
            );

            $data = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'CMS_MEETING_PERC_SET_LIST_004', $param);

            if (isset($data['status']) && $data['status'] === 'success') {

                return array(
                    'status' => 'success', 
                    'percent' => $data['result'],
                    'time1' => $currentDate
                );

            } else {
                $message = (isset($data['text']) && $data['text']) ? $data['text'] : 'Error олдсонгүй';
                return array('status' => 'error', 'message' => $message );
            }

        } else {
            return array();
        }
    }
    
    public function issueGovMemberModel($id) {

        $currentDate = Date::currentDate('H:i');
        
        $param = array(
            'subjectid' => $id,
        );
        
        $data = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'CMS_SUBJECT_MAP_LIST', $param);
        
        // pa($data);
        // die;

        if (isset($data['status']) && $data['status'] === 'success') {

            return array(
                'status' => 'success', 
                'response' => $data['result']
            );

        } else {
            $message = (isset($data['text']) && $data['text']) ? $data['text'] : 'Error олдсонгүй';
            return array('status' => 'error', 'message' => $message );
        }
    }

    public function meetingstartModel($postData) {

        $currentDateId = Date::currentDate('H:i');
        $currentDate = Date::currentDate();
        parse_str($postData['customdata'], $data);
        
        $param = array(
            'id' => $data['bookid'],
            'startTime' => $currentDate
        );
       
        $result = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'CMS_MEETING_BOOK_HEADER_START_SAVE_DV_002', $param);
       
        if (isset($result['status']) && $result['status'] === 'success') {
            $this->db->AutoExecute('MMS_MEETING_BOOK', array('END_TIME' => '', 'ACTION' => '1'), 'UPDATE', "ID = '". $data['bookid'] ."'");
            
            return array(
                'status' => 'success', 
                'response' => $result['result'],
                'currentDate' => $currentDateId
            );
        } else {
            $message = (isset($result['text']) && $result['text']) ? $result['text'] : 'Error олдсонгүй';
            return array('status' => 'error', 'message' => $message );
        }
    }
    
    public function memberPercentIdModelV11($id ,$bookid,$keyid) {

        $currentDate = Date::currentDate('H:i');

        $dparam = array(
            'id' => $id,
            'employeekeyid'=>$keyid,
            'bookid' => $bookid,
        );
        
        $data = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'CMS_MEETING_ATT_SHOW_DV_002', $dparam);

        if (isset($data['result']) && $data['result']) {
            $param = array(
                'id' => $bookid,
            );
            $data = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'CMS_MEETING_PERC_SET_LIST_004', $param);

            if (isset($data['status']) && $data['status'] === 'success') {
                $this->db->AutoExecute('MMS_MEETING_PARTICIPANT', array('WFM_STATUS_ID' => NULL), 'UPDATE', "ID = '$id'");
                return array(
                    'status' => 'success', 
                    'percent' => $data['result']['perc'],
                );
            } else {
                $message = (isset($data['text']) && $data['text']) ? $data['text'] : 'Error олдсонгүй';
                return array('status' => 'error', 'message' => $message );
            }

        } else {
            return array();
        }
    }
    
    public function memberPercentIdModelV2($id ,$bookid,$keyid ,$desc) {

        $currentDate = Date::currentDate('H:i');

        $dparam = array(
            'id' => $id,
            'employeekeyid'=>$keyid,
            'wfmstatusid' => '1560435540485',
            'bookid' => $bookid,
            'wfmDescription' => $desc,
        );

        
        $data = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'CMS_MEETING_PARTICIPANT_TASALSAN_DV_002', $dparam);
        
        if (isset($data['status']) && $data['status'] === 'success') {

            return array(
                'status' => 'success', 
                'result' => $data['result'],
            );

        } else {
            $message = (isset($data['text']) && $data['text']) ? $data['text'] : 'Error олдсонгүй';
            return array('status' => 'error', 'message' => $message );
        }
    }

    public function memberPercentIdModelV3($id ,$bookid,$keyid,$desc) {

        $currentDate = Date::currentDate('H:i');

        $dparam = array(
            'id' => $id,
            'bookId' => $bookid,
            'employeekeyid'=>$keyid,
            'wfmstatusid' => '1561975198624',
            'bookid' => $bookid,
            'wfmDescription' => $desc,
        );
        
        $data = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'CMS_MEETING_PARTICIPANT_TOMILGOO_DV_002', $dparam);
        
        if (isset($data['status']) && $data['status'] === 'success') {

            return array(
                'status' => 'success', 
                'result' => $data['result'],
            );

        } else {
            $message = (isset($data['text']) && $data['text']) ? $data['text'] : 'Error олдсонгүй';
            return array('status' => 'error', 'message' => $message );
        }
    }
    
    public function memberPercentIdModelV4($id ,$bookid,$keyid,$desc) {

        $currentDate = Date::currentDate('H:i');

        $dparam = array(
            'id' => $id,
            'bookId' => $bookid,
            'time26' => $currentDate,
            'employeekeyid'=>$keyid,
            'wfmstatusid' => '1560435540602',
            'bookid' => $bookid,
            'wfmDescription' => $desc,
        );
        
        $data = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'CMS_MEETING_TIME26_DV_002', $dparam);
        
        if (isset($data['status']) && $data['status'] === 'success') {

            return array(
                'status' => 'success', 
                'result' => $data['result'],
            );

        } else {
            $message = (isset($data['text']) && $data['text']) ? $data['text'] : 'Error олдсонгүй';
            return array('status' => 'error', 'message' => $message );
        }
    }
    
    public function wfstatusModel($userid, $desc, $bookId, $status, $employkeyId) {

        $currentDate = Date::currentDate('H:i');

        $param = array(
            'id' => $userid,
        );

        $result1 = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'CMS_MEETING_PARTICIPANT_ABSENT_DV_004', $param);

        // var_dump($result1);die();

        if (isset($result1['status']) && $result1['status'] === 'success') {

            $dparam = array(
                'id' => $userid,
                'wfmstatusid' => $status,
                'bookid' => $bookId,
                'wfmDescription' => $desc,
            );

            $ticket = false;
            $tick = true;
            for ($index1 = 2; $index1 <= 7; $index1++) {
                
                if (isset($result1['result']['time' . $index1]) && $result1['result']['time' . $index1]) {
                    $dparam['time' . $index1] = $result1['result']['time' . $index1];
                    $ticket = true;
                    $tick = false;
                }
                
                if ($ticket) {
                    $ticket = false;
                    $cc = $index1 + 1;
                    if ($cc <= 7) {
                        $dparam['time' . $cc] = $currentDate;
                    }
                }
            }
            
            if ($tick) {
                $dparam['time2'] = $currentDate;
            }
            
            $data = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'CMS_MEETING_PARTICIPANT_ABSENT_DV_002', $dparam);

            if (isset($data['status']) && $data['status'] === 'success') {

                return array(
                    'status' => 'success', 
                    'response' => $data['result'],
                    'wfmdescription' => $desc
                );
    
            } else {
                $message = (isset($data['text']) && $data['text']) ? $data['text'] : 'Error олдсонгүй';
                return array('status' => 'error', 'message' => $message );
            }

        }
    }

    public function interruptModel($postData, $count) {

        $currentDate = Date::currentDate('H:i');

        parse_str($postData['emplKeyIds'], $emps);

        $id = $emps['bookid'];

        (Array) $interrupt = array();

        $param = array(
            'id' => $id,
        );
        
        $result1 = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'cms_meeting_att_header_dv_004', $param);

        if (isset($result1['status']) && $result1['status'] === 'success' && isset($id)) {

            $ticket = false;
            $tick = true;

            if (isset($result1['result']['cms_meeting_att_break_dv'])) {

                foreach ($result1['result']['cms_meeting_att_break_dv'] as $empTime) {

                    for ($index1 = 8; $index1 <= 25; $index1++) {

                        if (isset($empTime['time' . $index1]) && $empTime['time' . $index1]) {
                            $temps['time' . $index1] = $empTime['time' . $index1];
                            $ticket = true;
                            $tick = false;
                        }

                        if ($ticket) {
                            $ticket = false;
                            $cc = $index1 + 1;
                            if ($cc <= 25) {
                                $temps['time' . $cc] = $currentDate;
                            }
                        }

                    }

                    if ($tick) {
                        $temps['time' . $count] = $currentDate;
                    }

                    foreach ($emps['employeekey'] as $key => $row) {
                        if ($row === $empTime['employeekeyid']) {
                            $temps['id'] = $emps['users'][$key];
                            $temps['bookId'] = $emps['bookid'];
                            $temps['employeekeyid'] = $row;
                            array_push($interrupt, $temps);
                        }
                    }
                }
            }

            $param = array(
                'id' => $id,
                'cms_meeting_att_break_dv' => $interrupt,
            );

            $data = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'CMS_MEETING_ATT_HEADER_DV_002', $param);

            if (isset($data['status']) && $data['status'] === 'success') {

                return array(
                    'status' => 'success', 
                    'response' => $data['result']
                );

            } else {
                $message = (isset($data['text']) && $data['text']) ? $data['text'] : 'Error олдсонгүй';
                return array('status' => 'error', 'message' => $message );
            }
        }
    }

    public function endMeetingModel($postData) {

        $currentDate = Date::currentDate();
        $currentDateEnd = Date::currentDate('H:i');
        parse_str($postData['meetingData'], $Data);

        (Array) $interrupt = array();

        $param = array(
            'id' => $Data['bookid'],
        );

        $result1 = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'CMS_MEETING_ATT_HEADER_END_SAVE_DV_004', $param);
       
        if (isset($result1['status']) && $result1['status'] === 'success') {

            $ticket = false;
            $tick = true;
        
            foreach ($result1['result']['cms_meeting_end_save_dv'] as $row) {

                for ($index1 = 1; $index1 <= 26; $index1++) {

                    if (($row['wfmstatusid'] == '')) {
                        $temps['time' . $index1] = '';
                        $temps['wfmstatusid'] = '1560435540485';
                        $tick = true;
                    } else{
                        $temps['wfmstatusid'] = $row['wfmstatusid'];
                        $temps['time' . $index1] = $row['time' . $index1];
                    }
                    
                    if($row['wfmstatusid'] == '1560435540485'){
                        $temps['time' . $index1] = '';
                    }
                    
                    if ($ticket) {
                        $ticket = true;
                        $cc = $index1 + 1;
                        if ($cc <= 26) {
                            $temps['time' . $cc] = '';
                        }
                    }
                }
                
                foreach ($Data['users'] as $key => $rows) {

                    if ($rows === $row['id']) {
                        $temps['id'] = $rows;
                        $temps['employeeKeyId'] = $row['employeekeyid'];
                        $temps['bookId'] = $Data['bookid'];
                        array_push($interrupt, $temps);
                    }
                }
                
            }
            
            $param = array(
                'id' => $Data['bookid'],
                'endtime' => $currentDate,
                'totalbreaktime' => $Data['breakTime'],
                'duration' => $Data['durationTime'],
                'percentofattendance' => $Data['percent'],
                'cms_meeting_end_save_dv' => $interrupt,
            );
            
            $data = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'cms_meeting_att_header_end_save_dv_002', $param);

            if (isset($data['status']) && $data['status'] === 'success') {
                $result = $this->db->AutoExecute('MMS_MEETING_BOOK', array('ACTION' => '3', 'WFM_STATUS_ID' => '1553696553882138'), 'UPDATE', "ID = '". $Data['bookid'] ."'");
                
                $lparam = array(
                    'ID' => getUID(),
                    'RECORD_ID' => $Data['bookid'],
                    'REF_STRUCTURE_ID' => '1553696503515026',
                    'WFM_STATUS_ID' => '1553696553882138',
                    'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                    'CREATED_DATE' => Date::currentDate()
                );
                 
                $log = $this->db->AutoExecute('META_WFM_LOG', $lparam);
                
                return array(
                    'status' => 'success', 
                    'response' => $data['result'],
                    'currentDate' => $currentDateEnd
                );
    
            } 
            else {
                $message = (isset($data['text']) && $data['text']) ? $data['text'] : 'Error олдсонгүй';
                return array('status' => 'error', 'message' => $message );
            }
            
        }
        
    }

    public function getConferencingEmployeeModel() {
        $cache = phpFastCache(); 
        $data = $cache->get('getConferencingEmployeeList');
        
        if ($data == null) {
        
            $param = array(
                'systemMetaGroupId' => '1565410205828',
                'ignorePermission' => 1, 
                'showQuery' => 0
            );

            $result = $this->ws->runSerializeResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);
            
            if (isset($result['result']) && isset($result['result'][0])) {
                
                unset($result['result']['aggregatecolumns']);
                unset($result['result']['paging']);
                $dataList = $result['result'];
                $keyList = $empList = array();
                
                foreach ($dataList as $row) {
                    $keyList[Str::lower($row['name'])] = $row['id'];
                    $empList[] = array('id' => $row['id'], 'name' => $row['name'], 'type' => 'contact');
                }
                
                $data = array('keyList' => $keyList, 'empList' => $empList);
                
            } else {
                $data = null;
            }
        
            $cache->set('getConferencingEmployeeList', $data, Mdwebservice::$expressionCacheTime);
        }
            
        return $data;
    }
    
    public function saveConferencingProtocolModel() {
        
        try {
            
            $employeeKeyId = $empName = null;
            $currentDate = Date::currentDate();
            $note = Input::post('note');
            
            preg_match_all('/@+([a-zA-ZФЦУЖЭНГШҮЗКЪЙЫБӨАХРОЛДПЯЧЁСМИТЬВЮЕЩфцужэнгшүзкъйыбөахролдпячёсмитьвюещ0-9_\-.,]+)/', $note, $matches);
            
            if (count($matches[0]) > 0) {
                
                $note = trim(str_replace('&nbsp;', '', str_replace($matches[0][0], '', $note)));
                $empName = strip_tags($matches[1][0]);
                
                $lowerEmpName = Str::lower($empName);
                
                $cacheList = self::getConferencingEmployeeModel();
                $keyList = $cacheList['keyList'];
                $employeeKeyId = issetParam($keyList[$lowerEmpName]);
            }
            
            $data = array(
                'ID'              => getUID(),
                'SUBJECT_ID'      => Input::post('subjectId'),
                'EMPLOYEE_KEY_ID' => $employeeKeyId,
                'NOTE'            => $note,
                'CREATED_DATE'    => $currentDate,
                'CREATED_USER_ID' => Ue::sessionUserKeyId()
            );
            $result = $this->db->AutoExecute('MMS_SUBJECT_PROTOCOL', $data);
            
            if ($result) {
                $response = array('status' => 'success', 'name' => $empName, 'note' => $note, 'time' => $currentDate);
            } else {
                $response = array('status' => 'error', 'message' => 'Save error');
            }
            
        } catch (ADODB_Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }
    
    public function saveFinishLogFormModel() {
        
        $currentDate      = Date::currentDate();
        $sessionUserKeyId = Ue::sessionUserKeyId();
        
        $postData = Input::postData();
        $fileData = Input::fileData();
        
        $CMS_META_DM_RECORD_MAP_DV = array();
        $areaList = $postData['areaId'];
        
        foreach ($areaList as $row) {
            array_push($CMS_META_DM_RECORD_MAP_DV, array (
                    'id' => NULL,
                    'srcTableName' => 'MMS_MEETING_SUBJECT',
                    'srcRecordId' => $postData['id'],
                    'trgTableName' => 'MMS_MEETING_SUBJECT_AREA',
                    'trgRecordId' => $row,
                ));
        }
        
        $param = array (
                    'areaId' => implode(',', $areaList),
                    'decision' => $postData['decision'],
                    'CMS_META_DM_RECORD_MAP_DV' => $CMS_META_DM_RECORD_MAP_DV,
                    'id' => $postData['id'],
                );
        
        $result = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'CMS_SUBJECT_DECISION_DV_002', $param);
        
        if (isset($result['status']) && $result['status'] === 'success') {
            
            if (isset($_FILES['bp_file'])) {

                $file_arr = Arr::arrayFiles($_FILES['bp_file']);
                $fileData = Input::post('bp_file_name');

                if ($fileData !== null) {
                    $widgetCtrl = Controller::loadController('Mdwebservice', 'middleware/controllers/');                
                    $file_path = $widgetCtrl->bpUploadCustomPath('/metavalue/file/');

                    foreach ($fileData as $f => $file) {
                        if (isset($file_arr[$f]['name']) && $file_arr[$f]['name'] != '' && $file_arr[$f]['size'] != null) {

                            $newFileName = 'file_' . getUID() . $f;
                            $fileExtension = strtolower(substr($file_arr[$f]['name'], strrpos($file_arr[$f]['name'], '.') + 1));
                            $fileName = $newFileName . '.' . $fileExtension;

                            FileUpload::SetFileName($fileName);
                            FileUpload::SetTempName($file_arr[$f]['tmp_name']);
                            FileUpload::SetUploadDirectory($file_path);
                            FileUpload::SetValidExtensions(explode(',', Config::getFromCache('CONFIG_FILE_EXT')));
                            FileUpload::SetMaximumFileSize(FileUpload::GetConfigFileMaxSize()); //10mb
                            $uploadResult = FileUpload::UploadFile();

                            if ($uploadResult) {

                                $contentId = getUID();
                                $dataContent = array(
                                    'CONTENT_ID'      => $contentId,
                                    'FILE_NAME'       => ((empty($file)) ? $file_arr[$f]['name'] : $file),
                                    'PHYSICAL_PATH'   => $file_path . $fileName,
                                    'FILE_EXTENSION'  => $fileExtension,
                                    'FILE_SIZE'       => $file_arr[$f]['size'],
                                    'CREATED_USER_ID' => $sessionUserKeyId,
                                    'CREATED_DATE'    => $currentDate,
                                    'IS_EMAIL'        => issetVar($_POST['bp_file_sendmail'][$f]),
                                    'IS_PHOTO'        => 0
                                );
                                $dataContentFile = $this->db->AutoExecute('ECM_CONTENT', $dataContent);

                                if ($dataContentFile) {
                                    $dataContentMap = array(
                                        'ID'               => getUID(),
                                        'REF_STRUCTURE_ID' => '1564467460244',
                                        'RECORD_ID'        => $postData['id'],
                                        'CONTENT_ID'       => $contentId,
                                        'ORDER_NUM'        => ($f + 1)
                                    );
                                    $this->db->AutoExecute('ECM_CONTENT_MAP', $dataContentMap);
                                }
                            }
                        }
                    }
                }
                
            }
            
            $result['text'] = Lang::line('msg_save_success');
        }
        
        return $result;
        
    }
    
    public function saveTimeConferenceFormModel() {
        
        $currentDate = Date::currentDate('Y-m-d');
        $postData = Input::postData();
        
        $cmsSubjectTimeDv = array();
        $index = 0;
        foreach ($postData['timeId'] as $key => $row) {
            if ($postData['startTime'][$key] && $postData['endTime'][$key]) {
                $index++;
                $temp = array (
                                'id' => $postData['timeId'][$key],
                                'rowState' => $postData['rowState'][$key],
                                'subjectId' => $postData['id'],
                                'startTime' => $currentDate.' ' .$postData['startTime'][$key],
                                'endTime' => $currentDate.' ' .$postData['endTime'][$key],
                                'orderNum' => $index,
                            );
                array_push($cmsSubjectTimeDv, $temp);
            }
        }
        
        $param = array (
                    'id' => $postData['id'],
                    'maxOrder' => $index,
                    'CMS_SUBJECT_TIME_DV' => $cmsSubjectTimeDv,
                );
        
        $result = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'CMS_MEETING_SUBJECT_LOG_DV_002', $param);
        
        if (isset($result['status']) && $result['status'] === 'success') {
            $result['text'] = Lang::line('msg_save_success');
            
            $criteria = array(
                'id' => array(
                    array(
                        'operator' => '=',
                        'operand' => $postData['id']
                    )
                )
            );
            
            /*
            switch ($postData['typeId']) {
                case '1561974650875':
                    array_merge($criteria, Conference::$heleltsegTypeId);
                    break;
                case '1561974650898':
                    array_merge($criteria, Conference::$taniltsahTypeId);
                    break;
            }
            */
            
            $data = $this->fncRunDataview("1564710579741", "", "", "", $criteria, "1");
            
            $data = isset($data[0]) ? $data[0] : array();
            $data['starttime'] = Date::formatter($data['starttime'], 'H:i:s');
            $data['endtime'] = Date::formatter($data['endtime'], 'H:i:s');
            
            $result['data'] = $data;
            $result['datacompress'] = htmlentities(json_encode($data), ENT_QUOTES, 'UTF-8');
        }
        
        return $result;
    }
    
    public function saveConferenceOrderNumber() {
        $postData = Input::postData();
        (Array) $mapArr = array();
        
        foreach ($postData['params'] as $key => $row) {
            $temp = array (
                'id' => $row['mapid'],
                'meetingBookId' => $row['meetingbookid'],
                'orderNum' => $row['ordernum'],
                'rowState' => 'unchanged',
            );
            array_push($mapArr, $temp);
        }
        
        $param = array (
                    'CMS_MEETING_SUBJECT_MAP_DV' => $mapArr,
                    'id' => $postData['id'],
                );
        
        $result = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'CMS_SUBJECT_ORDERNUM_SET_DV_002', $param);
        
        if (isset($result['status']) && $result['status'] === 'success') {
            $result['text'] = Lang::line('msg_save_success');
        }
        
        return $result;
    }
    
    public function saveConferenceMember() {
        $postData = Input::postData();
        (Array) $mapArr = array();
        
        foreach ($postData['params'] as $key => $row) {
            $temp = array (
                'id' => $row['id'],
                'subjectId' => $row['subjectid'],
                'orderNum' => $row['ordernum'],
                'rowState' => 'unchanged',
            );
            array_push($mapArr, $temp);
        }
        
        $param = array (
                    'CMS_SUBJECT_PARTICIPANT_DV' => $mapArr,
                    'id' => $postData['id'],
                );
        
        $result = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'CMS_SUBJECT_PARTICIPANT_ORDER_SET_DV_002', $param);
        
        if (isset($result['status']) && $result['status'] === 'success') {
            $result['text'] = Lang::line('msg_save_success');
        }
        
        return $result;
    }
    
    public function getConferenceDataModel($id) {
        return $this->db->GetRow("SELECT 
                                        ID, 
                                        DURATION, 
                                        START_TIME, 
                                        END_TIME, 
                                        TOTAL_BREAK_TIME, 
                                        ACTION 
                                    FROM MMS_MEETING_BOOK WHERE ID = '$id'");
    }
    
    public function setprotocolModel() {
        
        $currentDate = Date::currentDate('Y-m-d H:i:s');
        $currentTime = Date::formatter($currentDate, 'H:i:s');
        $subjectId = Input::post('subjectId');
        $bookId = Input::post('bookId');
        
        $orderNum = $this->db->GetOne("SELECT ORDER_NUM + 1  FROM MMS_SUBJECT_PROTOCOL WHERE SUBJECT_ID = " . $this->db->Param(0) . " AND BOOK_ID = " . $this->db->Param(1) . "  ORDER BY ID DESC", array($subjectId, $bookId));
        $check = $this->db->GetOne("SELECT COUNT(*) FROM MMS_SUBJECT_PROTOCOL WHERE BOOK_ID = " . $this->db->Param(0) . " ORDER BY ID DESC", array($bookId));
        
        if (!$orderNum) {
            $orderNum = '1';
        } else {
            $data = array('END_TIME' => $currentDate,);
            $this->db->AutoExecute('MMS_SUBJECT_PROTOCOL', $data, "UPDATE", "END_TIME IS NULL AND BOOK_ID = '" . $bookId . "'");
        }
        
        if (Input::post('id')) {
            $data = array(
                        'SUBJECT_ID' => $subjectId,
                        'END_TIME' => $currentDate
                    );
            $result = $this->db->AutoExecute('MMS_SUBJECT_PROTOCOL', $data, 'UPDATE', "ID = '". Input::post('id') ."'");
            return array('status' => 'success', 'text' => $result, 'id' => Input::post('id'));
        } else {
            $data = array(
                        'ID' => getUID(),
                        'SUBJECT_ID' => Input::post('subjectId'),
                        'BOOK_ID' => Input::post('bookId'),
                        'CREATED_DATE' => $currentDate,
                        'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                        'EMPLOYEE_KEY_ID' => Input::post('employeeKeyId'),
                        'START_TIME' => $currentDate,
                        'SUBJECT_PARTICIPANT_ID' => Input::post('participantId'),
                        'ORDER_NUM' => $orderNum,
                        'TYPE_ID' => Input::post('type'),
                        'IS_ACTIVE' => '1'
                    );
            $result = $this->db->AutoExecute('MMS_SUBJECT_PROTOCOL', $data);
            return array('status' => 'success', 'text' => $result, 'id' => $data['ID']);
        }
    }
    
    public function rmprotocolModel() {
        
        $currentDate = Date::currentDate('Y-m-d H:i:s');
        $currentTime = Date::formatter($currentDate, 'H:i:s');
        
        $data = array(
                    'END_TIME' => $currentDate,
                    'IS_ACTIVE' => '0'
                );
        $result = $this->db->AutoExecute('MMS_SUBJECT_PROTOCOL', $data, 'UPDATE', "ID = '". Input::post('id') ."'");
        return array('status' => 'success', 'text' => $result, 'id' => Input::post('id'));
    }
    
    public function endprotocolModel() {
        
        $currentDate = Date::currentDate('Y-m-d H:i:s');
        $currentTime = Date::formatter($currentDate, 'H:i:s');
        
        $data = array(
                    'END_TIME' => $currentDate
                );
        $result = $this->db->AutoExecute('MMS_SUBJECT_PROTOCOL', $data, 'UPDATE', "END_TIME IS NULL AND BOOK_ID = '". Input::post('bookId') ."'");
        return array('status' => 'success', 'text' => $result, 'id' => Input::post('id'));
    }
}

?>