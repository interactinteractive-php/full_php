<?php

if (!defined('_VALID_PHP'))
    exit('Direct access to this location is not allowed.');

class Government_model extends Model {

    private static $gfServiceAddress = GF_SERVICE_ADDRESS;
    private static $getDataViewCommand = 'PL_MDVIEW_004';

    public function __construct() {
        parent::__construct();
    }

    private function staticRunIt ($systemMetaGroupId, $criteria = array(), $isShowQuery = 0, $paging = array(), $isWithoutLogin = true) {
        $param = array(
            'systemMetaGroupId' => $systemMetaGroupId,
            'showQuery' => $isShowQuery,
            'criteria' => $criteria,
            'paging' => $paging,
            'pagingWithoutAggregate' => '1',
        );

        $result = array();

        if ($isWithoutLogin) {
            $param['ignorePermission'] = '1';
        }
        
        $fullAddressWithOtherPort = defined('GF_SERVICE_OTHER_ADDRESS') ? GF_SERVICE_OTHER_ADDRESS : GF_SERVICE_ADDRESS;
        $dataResult = WebService::runResponse($fullAddressWithOtherPort, 'PL_MDVIEW_004', $param);
        
        if ($dataResult['status'] === 'success') {
            if ($isShowQuery !== '1') {
                $result['paging'] = ''; //$dataResult['result']['paging'];
                $result['aggregatecolumns'] = ''; //$dataResult['result']['aggregatecolumns'];
                
                unset($dataResult['result']['paging']);
                unset($dataResult['result']['aggregatecolumns']);
            }
            
            $result['result'] = $dataResult['result'];
            return $result;
        } else {
            return $dataResult;    
        }
    }

    public function fncRunDataview($dataviewId, $field = "", $operand = "=", $operator = "", $paramFilter = "", $sortField = 'createddate', $sortK = 'desc', $iscriteriaOnly = "0", $pagination = false, $pageSize = false) {
        if (!$dataviewId) {
            return array();
        }

        if ($iscriteriaOnly) {
            $criteria = $paramFilter;
        } else {
            $criteria = array(
                $field => array(
                    array(
                        'operator' => $operand,
                        'operand' => ($operand == 'like') ? '%' . $operator . '%' : $operator
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

        $paging = array();

        if ($pagination || $pageSize) {
            $paging = array(
                'offset' => Input::post('offset') ? Input::post('offset') : '1',
                'pageSize' => $pageSize ? $pageSize : '50'
            );
        }

        if ($sortField) {
            $sortColumnNames[$sortField] = array('sortType' => $sortK);
            $paging['sortColumnNames'] = $sortColumnNames;
        }

        $data = self::staticRunIt($dataviewId, $criteria, '0', $paging);
        
        (Array) $response = array();
        if ($pagination) {
            $response = $data;
        } elseif (isset($data['result']) && $data['result']) {
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);
            $response = $data['result'];
        }

        return $response;
    }

    public function govsendMailModel($type) {
        includeLib('Utils/Functions');

        $id = Input::post('type') ? '' : Input::post('id');
        $id1 = Input::post('id');
        $removedData = array();

        $postData = Input::postData();
        $fileData = Input::fileData();
        $addFileData = array();
        
        if (Input::postCheck('isdraft') && Input::postCheck('processCode')) {
            $result = Functions::runProcess(Input::post('processCode'), array('id' => $id1));
            $removedData = isset($result['result']) ? $result['result'] : array();
            
        }

        if (Input::postcheck('type') && Input::postCheck('processCode')) {
            if (Input::post('type') == 'f') {
                
                $result = Functions::runProcess(Input::post('processCode'), array('id' => $id1));
                $addFileData = isset($result['result']) ? $result['result'] : array();
            }
        }

        $currentDate = Date::currentDate();
        $sessionUserKeyId = Ue::sessionUserKeyId();
        $sessionUserId = Ue::sessionUserId();

        $mmRecipientDv = $mmContentMapDv = array();
        (Array) $temp = array();

        try {

            if (isset($_FILES['bp_file'])) {

                $file_arr = Arr::arrayFiles($_FILES['bp_file']);
                $fileData = Input::post('bp_file_name');

                if ($fileData !== null) {

                    $file_path = self::bpUploadCustomPath('/metavalue/file/');

                    (Array) $fileData = array();
                    foreach ($file_arr as $key => $af) {
                        if (!in_array($af['name'], $fileData)) {
                            array_push($fileData, $af['name']);
                        }
                    }

                    if ($fileData) {
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

                                    $tempparam = array(
                                        'refStructureId' => NULL,
                                        'MM_CONTENT_DV' =>
                                        array(
                                            'fileName' => ((empty($file)) ? $file_arr[$f]['name'] : $file),
                                            'fileSize' => $file_arr[$f]['size'],
                                            'fileExtension' => $fileExtension,
                                            'physicalPath' => $file_path . $fileName,
                                            'id' => NULL,
                                            'createdDate' => $currentDate,
                                            'createdUserId' => $sessionUserKeyId,
                                        ),
                                        'id' => NULL,
                                        'recordId' => NULL,
                                        'contentId' => NULL,
                                    );

                                    array_push($mmContentMapDv, $tempparam);
                                }
                            }
                        }
                    }
                }
            }

            if (issetParam($postData['receiverId'])) {
                foreach ($postData['receiverId'] as $row) {
                    if ($row && !in_array($row, $temp)) {
                        $data = array(
                            'recipientTypeId' => '1',
                            'receiverId' => $row,
                            'id' => NULL,
                            'createdDate' => $currentDate,
                            'createdUserId' => $sessionUserKeyId,
                            'isTrashed' => '0',
                            'mailId' => $id,
                            'isRead' => '0',
                        );
                        array_push($mmRecipientDv, $data);
                        array_push($temp, $row);
                    }
                }
            }

            if (!$mmRecipientDv && $type == '0') {
                throw new Exception("Захидал хүлээн авагчийг оруулна уу!");
            }

            if (isset($removedData['mm_recipient_get_list'])) {
                foreach ($removedData['mm_recipient_get_list'] as $key => $row) {
                    $data = array(
                        'rowState' => 'REMOVED',
                        'id' => $row['id'],
                        'mailId' => $row['mailid'],
                    );
                    array_push($mmRecipientDv, $data);
                }
            }

            if (isset($removedData['mm_content_get_list'])) {
                foreach ($removedData['mm_content_get_list'] as $key => $row) {
                    $tempparam = array(
                        'refStructureId' => NULL,
                        'MM_CONTENT_DV' =>
                        array(
                            'fileName' => $row['filename'],
                            'fileSize' => $row['filesize'],
                            'fileExtension' => $row['fileextension'],
                            'physicalPath' => $row['physicalpath'],
                            'id' => $row['contentid'],
                            'createdDate' => $currentDate,
                            'createdUserId' => $sessionUserKeyId,
                        ),
                        'id' => $row['contentmapid'],
                        'recordId' => $id,
                        'contentId' => $row['contentid'],
                    );

                    array_push($mmContentMapDv, $tempparam);
                }
            }

            if (isset($addFileData['mm_content_get_list'])) {
                foreach ($addFileData['mm_content_get_list'] as $key => $row) {
                    $newPath = self::bpUploadCustomPath('/metavalue/file/');
                    $fileName = 'file_' .getUID() .'.' .  $row['fileextension'];
                    $newFilePath =  $newPath. $fileName; 
                    if (copy($row['physicalpath'], $newFilePath)) {
                        $tempparam = array(
                            'refStructureId' => NULL,
                            'MM_CONTENT_DV' =>
                            array(
                                'fileName' => $fileName,
                                'fileSize' => $row['filesize'],
                                'fileExtension' => $row['fileextension'],
                                'physicalPath' => $newPath. $fileName, //$row['physicalpath'],
                                'id' => '',
                                'createdDate' => $currentDate,
                                'createdUserId' => $sessionUserKeyId,
                            ),
                            'id' => '',
                            'recordId' => '',
                            'contentId' => '',
                        );

                        array_push($mmContentMapDv, $tempparam);
                    }
                }
            }

            $params = array(
                'senderId' => $sessionUserId,
                'subject' => $postData['subject'],
                'isSent' => ($type == '0') ? '1' : '0',
                'sentDate' => ($type == '0') ? $currentDate : '',
                'body' => Input::postNonTags('body'),
                'MM_RECIPIENT_DV' => $mmRecipientDv,
                'MM_CONTENT_MAP_DV' => $mmContentMapDv,
                'id' => $id,
                'isActive' => '1',
                'createdDate' => $currentDate,
                'createdUserId' => $sessionUserKeyId,
                'description' => NULL,
            );
            
            $result = Functions::runProcess('MM_MAIL_DV_001', $params);

            if ($result['status'] === 'success') {
                $result['text'] = ($type == '0') ? Lang::line('msg_mail_success') : Lang::line('msg_save_success');
            }
        } catch (Exception $ex) {
            (Array) $result = array();

            $result['status'] = 'warning';
            $result['text'] = $ex->getMessage();
        }

        return $result;
    }

    public function bpUploadCustomPath($customPath) {

        if (defined('CONFIG_FILE_UPLOAD_DATE_FOLDER') && CONFIG_FILE_UPLOAD_DATE_FOLDER) {

            $date = Date::currentDate('Ym');
            $newPath = UPLOADPATH . Mdwebservice::$uploadedPath . $date . $customPath;

            if (!is_dir($newPath)) {
                mkdir($newPath, 0777, true);
            }

            return $newPath;
        } else {
            $path = UPLOADPATH . ltrim($customPath, '/');
        }

        return $path;
    }

    public function bpUploadCustomPath1($customPath) {

        if (defined('CONFIG_FILE_UPLOAD_DATE_FOLDER') && CONFIG_FILE_UPLOAD_DATE_FOLDER) {

            $date = Date::currentDate('Ym');
            $newPath = UPLOADPATH . $customPath . $date . '/';

            if (!is_dir($newPath)) {
                mkdir($newPath, 0777, true);
            }

            return $newPath;
        } else {
            $path = UPLOADPATH . ltrim($customPath, '/');
        }

        return $path;
    }

    public function govrunProcessModel() {
        try {
            $postData = Input::post('dataRow');

            includeLib('Utils/Functions');
            $result = Functions::runProcess($postData['trashprocesscode'], array('id' => $postData['recordid']));

            if ($result['status'] === 'success') {
                $result['text'] = Lang::line('msg_save_success');
            }
        } catch (Exception $ex) {
            (Array) $result = array();

            $result['status'] = 'warning';
            $result['text'] = $ex->getMessage();
        }

        return $result;
    }

    public function getIntranedSidebarModel($categoryid = null, $dataRow = null, $parent = '0', $metaDataId = '1565319131590210') {
        $criteria = array(
            'parentid' => array(
                array(
                    'operator' => '=',
                    'operand' => ($parent === '1') ? '-1' : Input::post('id')
                )
            ),
            'sessionUserId' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionUserId()
                )
            )
        );

        includeLib('Utils/Functions');

        if (Config::getFromCache('intranetDvId')) {
            $metaDataId = Config::getFromCache('intranetDvId');
        }

        $data = Functions::runDataViewWithoutLogin($metaDataId, $criteria);

        (Array) $response = array();

        if (isset($data['result']) && $data['result']) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);
            $response = $data['result'];
        }

        return $response;
    }

    public function getIntranetContentDtlModel($id) {
        $dataRow = Input::post('dataRow');

        $param = array('id' => $id, 'sessionUserId' => Ue::sessionUserId(), 'filterUserId' => Ue::sessionUserId());

        if (Config::getFromCache('isNotaryServer') && isset($dataRow) && isset($dataRow['id']) && issetParam($dataRow['categoryid']) == '9988') {
            $groupId = $dataRow['id'];
            $param['groupId'] = $groupId;
        }
        includeLib('Utils/Functions');
        $result = Functions::runProcess('SCL_POSTS_MAIN_POST_GET_LIST_004', $param);

        if (isset($result['result']) && $result['result']) {
            return $result['result'];
        }

        return array();
    }

    public function getIntranetCommentModel($id, $parentId = null) {
        (Array) $response = array();

        includeLib('Utils/Functions');
        $result = Functions::runProcess('INT_POST_COMMENT_GET_LIST_004', array('postId' => $id, 'parentId' => $parentId));

        if (isset($result['result']['int_post_comment_detail_list']) && $result['result']['int_post_comment_detail_list']) {
            $response = $result['result']['int_post_comment_detail_list'];
        }

        (Array) $data = array();

        if ($response) {
            $k = 0;
            foreach ($response as $key => $row) {
                $data[$k] = $row;
                $data[$k]['commenttext'] = json_decode(str_replace('<br>', '\n', json_encode($row['commenttxt'])));
                $data[$k]['child'] = $this->getIntranetCommentModel($row['postid'], $row['id']);
                $k++;
            }
        }

        return $data;
    }

    public function saveIntanetCommentModel() {
        $postId = Input::post('postId');
        $commentType = Input::post('commentType');
        $commentText = $_POST['commentText'];
        $commentId = Input::post('commentId');
        $ncommentId = getUID();

        $badwords = self::fncRunDataview('16165836075231');
        foreach($badwords as $ww) {
            $commentText = str_replace($ww['searchname'], $ww['replacename'], $commentText);
        }
        
        $result = array();

        switch ($commentType) {
            case 'comment':
                $param = array(
                    'ID' => (issetParam($commentId) !== '0') ? $commentId : $ncommentId,
                    'POST_ID' => $postId,
                    'COMMENT_TXT' => json_decode(str_replace('\n', '<br>', json_encode($commentText))),
                    'USER_ID' => Ue::sessionUserId(),
                    'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s')
                );
                if (issetParam($commentId) !== '0') {
                    $result = $this->db->AutoExecute('SCL_POSTS_COMMENT', $param, "UPDATE", "ID = '" . $commentId . "'");
                } else {
                    $result = $this->db->AutoExecute('SCL_POSTS_COMMENT', $param);
                }
                break;
            case 'reply':
                $param = array(
                    'ID' => $ncommentId,
                    'POST_ID' => $postId,
                    'PARENT_ID' => $commentId,
                    'COMMENT_TXT' => json_decode(str_replace('\n', '<br>', json_encode($commentText))),
                    'USER_ID' => Ue::sessionUserId(),
                    'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s')
                );

                $result = $this->db->AutoExecute('SCL_POSTS_COMMENT', $param);

                break;
        }

        includeLib('Utils/Functions');
        $result = Functions::runProcess('NTR_SOCIAL_COMMENT_LIST_004', array('id' => $ncommentId));
        (Array) $dataRow = array();

        if (issetParamArray($result['result'])) {
            $dataRow = $result['result'];
            $dataRow['comments'] = json_decode(str_replace('<br>', '\n', json_encode($dataRow['comments'])));
        }

        return array('commentId' => $commentId, 'dataRow' => $dataRow, 'datatype' => $commentType, 'postId' => $postId, 'html' => '');
    }

    public function deleteCommentModel() {
        $commentId = Input::post('commentId');
        $result = $this->db->Execute("DELETE FROM SCL_POSTS_COMMENT WHERE ID = '$commentId'");
        if ($result) {
            return array('status' => 'success');
        } else {
            return array('status' => 'error');
        }
    }

    public function saveCommentModel() {
        $commentId = Input::post('commentId');

        $result = $this->db->AutoExecute("SCL_POSTS_COMMENT", array('COMMENT_TXT' => json_decode(str_replace('\n', '<br>', json_encode($_POST['commentTxt'])))), 'UPDATE', "ID = '$commentId'");

        if ($result) {
            return array('status' => 'success');
        } else {
            return array('status' => 'error');
        }
    }

    public function getPollAtteptModel() {
        /* return true; */
        $postId = Input::post('postId');
        $userId = Ue::sessionUserId();

        $query = "SELECT DISTINCT P.ID, Q.POST_ID, R.USER_ID FROM SCL_POSTS P 
        INNER JOIN SCL_POLL_QUESTION Q ON P.ID = Q.POST_ID
        INNER JOIN SCL_POLL_RESULT R ON R.POLL_QUESTION_ID = Q.ID WHERE P.ID = $postId AND R.USER_ID = $userId";
        $getReadInfo = $this->db->GetRow($query);

        if ($getReadInfo) {
            return true;
        } else {
            return false;
        }
    }

    public function getRightSidebarContentModel() {
        (Array) $criteria = array();

        $param = array(
            'systemMetaGroupId' => 1567754961182,
            'showQuery' => '0',
            'ignorePermission' => 1,
            'criteria' => $criteria,
            'paging' => array(
                'offset' => 1,
                'pageSize' => 6
            )
        );

        $param['criteria'] = $criteria;

        $data = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

        if (isset($data['result']) && $data['result']) {

            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);
            return $data['result'];
        }
    }

    public function getGroupDvModel() {
        $param = array(
            'systemMetaGroupId' => 1572247923506234,
            'showQuery' => '0',
            'ignorePermission' => 1
        );

        $param['criteria'] = array(
            'createduserid' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionUserId()
                )
            )
        );

        $data = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);
        if (isset($data['result']) && $data['result']) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);
            return $data['result'];
        }
    }

    public function savePostModel() {
        includeLib('Utils/Functions');

        $id = Input::post('id');
        $removedData = array();
        $type = 1;
        $imgUrl = null;
        $subjectName = Input::post('subject');
        $bodyDescr = Input::postNonTags('body');
        if (Input::post('typeId') === '9999') {
            $bodyDescr = $subjectName = json_decode(str_replace('<br>', '\n', json_encode(Input::post('subject'))));
        }

        $postData = Input::postData();
        $fileData = Input::fileData();
        
        $currentDate = Date::currentDate();
        $sessionUserKeyId = Ue::sessionUserKeyId();
        $sessionUserId = Ue::sessionUserId();
        (Array) $isReadUsersArr = array();

        if (issetParam($postData['isedit'])) {
            includeLib('Compress/Compression');
            $removedData = Compression::decode_string_array($postData['mainData']);

            if (issetParamArray($removedData['int_post_user_dv'])) {
                $isReadArr = Arr::groupByArrayOnlyRows($removedData['int_post_user_dv'], 'isread');
                if (isset($isReadArr[1])) {
                    $isReadUsersArr = Arr::groupByArrayOnlyRow($isReadArr[1], 'userid', false);
                }
            }

            if (issetParam($removedData['userid']) && ($sessionUserId == '2' || $sessionUserId == '1')) {
                $sessionUserId = $removedData['userid'];
            }
        }

        $mmRecipientDv = $mmContentMapDv = array();

        $postId = Input::post('id');
        $ischanged = Input::post('ischanged');

        $intPostGroupsDv = $intPostGroupsDepartmentDv = $intPostUserDv = $intPostUserDepartmentDv = $intPollQuestionDv = array();

        (Array) $temp = $userIdsTemp = $departmentIdsTemp = $questionTemp = array();

        try {

            if (Input::post('isPin') && !Input::post('pindate')) {
                throw new Exception("Та хүчинтэй хугацаа оруулна уу!");
            }

            if (!Input::post('subject')) {
                throw new Exception("Гарчиг заавал оруулна уу!");
            }

            if (!Input::post('id') && (Input::post('typeId') === '4' || Input::post('typeId') === '5') && !isset($_FILES['bp_file'])) {
                throw new Exception("Файл заавал оруулна уу!");
            }

            if (isset($_FILES['bp_file'])) {

                $file_arr = Arr::arrayFiles($_FILES['bp_file']);

                (Array) $fileData = array();
                foreach ($file_arr as $key => $af) {
                    if (!in_array($af['name'], $fileData)) {
                        array_push($fileData, $af['name']);
                    }
                }

                if ($fileData) {

                    $file_path = self::bpUploadCustomPath('/metavalue/file/');

                    foreach ($fileData as $f => $file) {

                        if (isset($file_arr[$f]['name']) && $file_arr[$f]['name'] != '' && $file_arr[$f]['size'] != null) {
                            $fileExtension = strtolower(substr($file_arr[$f]['name'], strrpos($file_arr[$f]['name'], '.') + 1));

                            $newFileName = 'file_' . getUID() . $f;
                            $fileExtension = strtolower(substr($file_arr[$f]['name'], strrpos($file_arr[$f]['name'], '.') + 1));
                            $fileName = $newFileName . '.' . $fileExtension;

                            if (in_array($fileExtension, array('jpg', 'jpeg', 'png', 'gif', 'bmp'))) {
                                $file_path = self::bpUploadCustomPath1('social/posts/images/original/');
                                $fileThumbspath = self::bpUploadCustomPath1('social/posts/images/thumb/');

                                $fileAttr['name'] = $file_arr[$f]['name'];
                                $fileAttr['tmp_name'] = $file_arr[$f]['tmp_name'];
                                $fileAttr['size'] = $file_arr[$f]['size'];
                                $fileAttr['type'] = $file_arr[$f]['type'];

                                Upload::$File = $fileAttr;
                                Upload::$method = 0;
                                Upload::$SavePath = $file_path;
                                Upload::$ThumbPath = $fileThumbspath;
                                Upload::$NewWidth = 2000;
                                Upload::$NewName = $newFileName;
                                Upload::$OverWrite = true;
                                Upload::$CheckOnlyWidth = true;
                                $uploadError = Upload::UploadFile();

                                if ($uploadError == '') {
                                    $tempparam = array(
                                        'refStructureId' => NULL,
                                        'INT_CONTENT_DV' =>
                                        array(
                                            'fileName' => ((empty($file)) ? $file_arr[$f]['name'] : $file),
                                            'fileSize' => $file_arr[$f]['size'],
                                            'fileExtension' => $fileExtension,
                                            'physicalPath' => $file_path . $fileName,
                                            'thumbPhysicalPath' => $fileThumbspath . $fileName,
                                            'id' => NULL,
                                            'createdDate' => $currentDate,
                                            'createdUserId' => $sessionUserKeyId,
                                        ),
                                        'id' => NULL,
                                        'recordId' => NULL,
                                        'contentId' => NULL,
                                    );

                                    array_push($mmContentMapDv, $tempparam);
                                }
                            } else {

                                FileUpload::SetFileName($fileName);
                                FileUpload::SetTempName($file_arr[$f]['tmp_name']);
                                FileUpload::SetUploadDirectory($file_path);
                                FileUpload::SetValidExtensions(explode(',', Config::getFromCache('CONFIG_FILE_EXT')));
                                FileUpload::SetMaximumFileSize(FileUpload::GetConfigFileMaxSize()); //10mb
                                $uploadResult = FileUpload::UploadFile();

                                if ($uploadResult) {

                                    $tempparam = array(
                                        'refStructureId' => NULL,
                                        'INT_CONTENT_DV' =>
                                        array(
                                            'fileName' => ((empty($file)) ? $file_arr[$f]['name'] : $file),
                                            'fileSize' => $file_arr[$f]['size'],
                                            'fileExtension' => $fileExtension,
                                            'physicalPath' => $file_path . $fileName,
                                            'id' => NULL,
                                            'createdDate' => $currentDate,
                                            'createdUserId' => $sessionUserKeyId,
                                        ),
                                        'id' => NULL,
                                        'recordId' => NULL,
                                        'contentId' => NULL,
                                    );

                                    array_push($mmContentMapDv, $tempparam);
                                }
                            }
                        }
                    }
                }
            }

            if (isset($_FILES['bp_imgurl'])) {

                $bp_imgurl = $_FILES['bp_imgurl'];
                $file_path = self::bpUploadCustomPath('/metavalue/file/');
                $fileExtension = strtolower(substr($bp_imgurl['name'], strrpos($bp_imgurl['name'], '.') + 1));

                $newFileName = 'file_' . getUID() . $f;
                $fileExtension = strtolower(substr($bp_imgurl['name'], strrpos($bp_imgurl['name'], '.') + 1));
                $fileName = $newFileName . '.' . $fileExtension;

                if (in_array($fileExtension, array('jpg', 'jpeg', 'png', 'gif', 'bmp'))) {
                    $file_path = self::bpUploadCustomPath1('social/posts/images/original/');
                    $fileThumbspath = self::bpUploadCustomPath1('social/posts/images/thumb/');

                    $fileAttr['name'] = $bp_imgurl['name'];
                    $fileAttr['tmp_name'] = $bp_imgurl['tmp_name'];
                    $fileAttr['size'] = $bp_imgurl['size'];
                    $fileAttr['type'] = $bp_imgurl['type'];

                    Upload::$File = $fileAttr;
                    Upload::$method = 0;
                    Upload::$SavePath = $file_path;
                    Upload::$ThumbPath = $fileThumbspath;
                    Upload::$NewWidth = 2000;
                    Upload::$NewName = $newFileName;
                    Upload::$OverWrite = true;
                    Upload::$CheckOnlyWidth = true;
                    $uploadError = Upload::UploadFile();
                    $imgUrl = $file_path . $fileName;
                }
            }

            if (issetParam($postData['mainTypeId']) && ($postData['mainTypeId'] == '5' || $postData['mainTypeId'] == '4') && issetParam($postData['saveProcessCode']) === '') {
                switch ($postData['mainTypeId']) {
                    case '5':
                        $userdata = self::fncRunDataview('1572435589929864');

                        if (issetParam($userdata)) {

                            foreach ($userdata as $row) {
                                if ($row['userid'] && !in_array($row['userid'], $temp) && ($row['userid'] !== '1' && $row['userid'] !== '2')) {
                                    $data2 = array(
                                        'rowState' => 'ADDED',
                                        'userId' => $row['userid'],
                                        'id' => NULL,
                                        'postId' => $postId,
                                        'createdDate' => $currentDate,
                                        'createdUserId' => $sessionUserId,
                                        'isActive' => '1',
                                        'isRead' => ($ischanged == '1' ? issetParamZero($isReadUsersArr[$row['userid']]['isread']) : '0'),
                                        'readDate' => ($ischanged == '1' ? isset($isReadUsersArr[$row['userid']]['readdate']) ? $isReadUsersArr[$row['userid']]['readdate'] : null : null),
                                        'isDeleted' => '0',
                                        'deletedDate' => NULL,
                                    );
                                    array_push($intPostUserDv, $data2);

                                    $data = array(
                                        'rowState' => 'ADDED',
                                        'userId' => $row['userid'],
                                        'id' => NULL,
                                        'postId' => $postId,
                                        'createdDate' => $currentDate,
                                        'createdUserId' => NULL,
                                        'isActive' => '1',
                                        'isRead' => ($ischanged == '1' ? issetParamZero($isReadUsersArr[$row['userid']]['isread']) : '0'),
                                        'isDeleted' => '0',
                                        'deletedDate' => NULL,
                                    );
                                    array_push($intPostUserDepartmentDv, $data);
                                }
                            }
                        }

                        break;
                    default:
                        $data2 = array(
                            'rowState' => 'ADDED',
                            'userId' => $sessionUserId,
                            'id' => NULL,
                            'postId' => $postId,
                            'createdDate' => $currentDate,
                            'createdUserId' => $sessionUserId,
                            'isActive' => '1',
                            'isRead' => '1',
                            'isDeleted' => '0',
                            'deletedDate' => NULL,
                        );
                        array_push($intPostUserDv, $data2);

                        $data = array(
                            'rowState' => 'ADDED',
                            'userId' => $sessionUserId,
                            'id' => NULL,
                            'postId' => $postId,
                            'createdDate' => $currentDate,
                            'createdUserId' => NULL,
                            'isActive' => '1',
                            'isRead' => '1',
                            'isDeleted' => '0',
                            'deletedDate' => NULL,
                        );
                        array_push($intPostUserDepartmentDv, $data);
                        break;
                }
            } else {

                if (issetParam($postData['param']['userIds'])) {

                    foreach ($postData['param']['userIds'] as $row) {
                        if ($row && !in_array($row, $userIdsTemp)) {
                            $data1 = array(
                                'rowState' => 'ADDED',
                                'userId' => $row,
                                'groupId' => NULL,
                                'id' => NULL,
                                'postId' => $postId,
                                'departmentId' => NULL,
                            );
                            $data2 = array(
                                'rowState' => 'ADDED',
                                'userId' => $row,
                                'id' => NULL,
                                'postId' => $postId,
                                'createdDate' => $currentDate,
                                'createdUserId' => $sessionUserId,
                                'isActive' => '1',
                                'isRead' => ($ischanged == '1' ? issetParamZero($isReadUsersArr[$row]['isread']) : '0'),
                                'readDate' => ($ischanged == '1' ? isset($isReadUsersArr[$row]['readdate']) ? $isReadUsersArr[$row]['readdate'] : null : null),
                                'isDeleted' => '0',
                                'deletedDate' => NULL,
                            );

                            array_push($intPostGroupsDv, $data1);
                            array_push($intPostUserDv, $data2);
                            array_push($userIdsTemp, $row);
                        }
                    }
                }

                (Array) $departmentIdsTemp = array();

                if (issetParam($postData['param']['departmentIds'])) {
                    foreach ($postData['param']['departmentIds'] as $row) {
                        if ($row && !in_array($row, $departmentIdsTemp)) {
                            $data = array(
                                'rowState' => 'ADDED',
                                'userId' => null,
                                'groupId' => NULL,
                                'id' => NULL,
                                'postId' => $postId,
                                'departmentId' => $row,
                            );

                            array_push($intPostGroupsDepartmentDv, $data);
                            array_push($departmentIdsTemp, $row);
                        }
                    }
                }

                if ($departmentIdsTemp) {
                    foreach ($departmentIdsTemp as $row) {
                        $userResult = Functions::runProcess('INT_GET_DEPARTMENT_USER_LIST_004', array('departmentId' => $row));
                        if (isset($userResult['result']['int_post_user_department_dv'])) {
                            foreach ($userResult['result']['int_post_user_department_dv'] as $userData) {
                                if ($userData['userid']) {
                                    $data = array(
                                        'rowState' => 'ADDED',
                                        'userId' => $userData['userid'],
                                        'id' => NULL,
                                        'postId' => $postId,
                                        'createdDate' => $currentDate,
                                        'createdUserId' => NULL,
                                        'isActive' => '0',
                                        'isRead' => ($userData['userid'] == $sessionUserId) ? '1' : '0',
                                        'isDeleted' => '0',
                                        'deletedDate' => NULL,
                                    );
                                    array_push($intPostUserDepartmentDv, $data);

                                    if (!in_array($userData['userid'], $userIdsTemp)) {
                                        array_push($userIdsTemp, $userData['userid']);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if (!$mmRecipientDv && $type == '0') {
                throw new Exception("Захидал хүлээн авагчийг оруулна уу!");
            }

            if (issetParam($postData['isedit'])) {

                if (isset($removedData['int_post_groups_dv'])) {
                    foreach ($removedData['int_post_groups_dv'] as $key => $row) {
                        if (!in_array($row['userid'], $userIdsTemp)) {
                            $data1 = array(
                                'rowState' => 'REMOVED',
                                'userId' => $row['userid'],
                                'groupId' => NULL,
                                'id' => $row['id'],
                                'postId' => issetParam($row['postid']),
                                'departmentId' => NULL,
                            );
                            array_push($intPostGroupsDv, $data1);
                        }
                    }
                }

                if (isset($removedData['int_post_groups_department_dv'])) {
                    foreach ($removedData['int_post_groups_department_dv'] as $key => $row) {
                        if (!in_array($row['departmentid'], $departmentIdsTemp)) {
                            $data1 = array(
                                'rowState' => 'REMOVED',
                                'departmentid' => $row['departmentid'],
                                'groupId' => NULL,
                                'id' => $row['id'],
                                'postId' => $row['postid'],
                            );
                            array_push($intPostGroupsDepartmentDv, $data1);
                        }
                    }
                }

                if (isset($removedData['int_post_user_dv'])) {
                    foreach ($removedData['int_post_user_dv'] as $key => $row) {
                        if (!in_array($row['userid'], $userIdsTemp)) {
                            $data2 = array(
                                'rowState' => 'REMOVED',
                                'userId' => $row['userid'],
                                'id' => $row['id'],
                                'postId' => $row['postid'],
                            );

                            array_push($intPostUserDv, $data2);
                        }
                    }
                }

                if (isset($removedData['int_post_user_department_dv'])) {
                    foreach ($removedData['int_post_user_department_dv'] as $key => $row) {
                        $data2 = array(
                            'rowState' => 'REMOVED',
                            'userId' => $row['userid'],
                            'id' => $row['id'],
                            'postId' => $row['postid'],
                        );

                        array_push($intPostUserDv, $data2);
                    }
                }

                if (isset($removedData['int_content_map_dv'])) {
                    foreach ($removedData['int_content_map_dv'] as $key => $row) {
                        if (isset($postData['contentId'])) {
                            if (!in_array($row['contentid'], $postData['contentId'])) {
                                $tempparam = array(
                                    'rowState' => 'REMOVED',
                                    'id' => $row['id'],
                                    'contentId' => $row['contentid'],
                                );

                                array_push($mmContentMapDv, $tempparam);
                            }
                        } else {
                            $tempparam = array(
                                'rowState' => 'REMOVED',
                                'id' => $row['id'],
                                'contentId' => $row['contentid'],
                            );

                            array_push($mmContentMapDv, $tempparam);
                        }
                    }
                }

                if (isset($removedData['int_poll_question_dv'])) {
                    foreach ($removedData['int_poll_question_dv'] as $row) {
                        $tempquestion = array(
                            'rowstate' => 'REMOVED',
                            'id' => $row['id'],
                        );

                        array_push($intPollQuestionDv, $tempquestion);
                    }
                }
            }

            if (isset($postData['questionTxt']) && $postData['questionTxt']) {
                foreach ($postData['questionTxt'] as $key => $question) {
                    (Array) $intPollAnswerDv = array();

                    if (isset($postData['answerTxt'][$key]) && $postData['answerTxt'][$key]) {
                        foreach ($postData['answerTxt'][$key] as $row) {
                            if ($row) {
                                $tempanswer = array(
                                    'answerTxt' => $row,
                                    'likeCnt' => NULL,
                                    'pollQuestionId' => NULL,
                                    'likePercent' => NULL,
                                    'id' => NULL,
                                );
                                array_push($intPollAnswerDv, $tempanswer);
                            }
                        }
                    }

                    if (isset($postData['isother'][$key]) || (isset($postData['questionType'][$key]) && $postData['questionType'][$key] == '3')) {
                        $tempanswer = array(
                            'answerTxt' => 'Бусад',
                            'likeCnt' => NULL,
                            'pollQuestionId' => NULL,
                            'likePercent' => NULL,
                            'id' => NULL,
                            'isOther' => '1',
                        );
                        array_push($intPollAnswerDv, $tempanswer);
                    }

                    $tempquestion = array(
                        'questionTxt' => $question,
                        'answerTypeId' => isset($postData['questionType'][$key]) ? $postData['questionType'][$key] : '',
                        'INT_POLL_ANSWER_DV' => $intPollAnswerDv,
                        'id' => NULL,
                        'createdDate' => $currentDate,
                        'postId' => $postId,
                        'isRequired' => isset($postData['isrequired'][$key]) ? '1' : '0',
                        'isOther' => (isset($postData['questionType'][$key]) && $postData['questionType'][$key]) == '3' ? '1' : (isset($postData['isother'][$key]) ? '1' : '0'),
                        'limitCount' => isset($postData['limitCount'][$key]) ? $postData['limitCount'][$key] : '',
                        'createdUserId' => $sessionUserKeyId,
                    );

                    array_push($intPollQuestionDv, $tempquestion);
                }
            }

            $ptype = '';

            switch ($postData['mainTypeId']) {
                case '1':
                    $ptype = 'user';
                    break;
                case '2':
                    $ptype = 'department';
                    break;
                case '3':
                    $ptype = 'userDepartment';
                    break;
                case '4':
                    $ptype = 'private';
                    break;
                case '5':
                    $ptype = 'public';
                    break;
            }

            if (!in_array($sessionUserId, $userIdsTemp) && ($sessionUserId !== '2' && $sessionUserId !== '1')) {
                $data1 = array(
                    'rowState' => 'ADDED',
                    'userId' => $sessionUserId,
                    'groupId' => NULL,
                    'id' => NULL,
                    'postId' => $postId,
                    'departmentId' => NULL,
                );
                array_push($intPostGroupsDv, $data1);

                $data2 = array(
                    'rowState' => 'ADDED',
                    'userId' => $sessionUserId,
                    'id' => NULL,
                    'postId' => $postId,
                    'createdDate' => $currentDate,
                    'createdUserId' => $sessionUserId,
                    'isActive' => '1',
                    'isRead' => '1',
                    'isDeleted' => '0',
                    'deletedDate' => NULL,
                );
                array_push($intPostUserDv, $data2);
            }

            (Array) $intPostUserDv1 = array();

            $dataUsers = Arr::groupByArrayOnlyRows($intPostUserDv, 'rowState');
            $ticket = false;
            if (isset($dataUsers['REMOVED'])) {
                (Array) $intPostUserDv = array();
                $ticket = true;
                foreach ($dataUsers['REMOVED'] as $key => $row) {
                    array_push($intPostUserDv, $row);
                }
            }

            if (isset($dataUsers['ADDED'])) {
                (Array) $intPostUserDv = ($ticket) ? $intPostUserDv : array();
                foreach ($dataUsers['ADDED'] as $key => $row) {
                    array_push($intPostUserDv, $row);
                }
            }

            $dataGroups = Arr::groupByArrayOnlyRows($intPostGroupsDv, 'rowState');
            $ticket = false;
            if (isset($dataGroups['REMOVED'])) {
                (Array) $intPostGroupsDv = array();
                $ticket = true;

                foreach ($dataGroups['REMOVED'] as $key => $row) {
                    array_push($intPostGroupsDv, $row);
                }
            }

            if (isset($dataGroups['ADDED'])) {
                (Array) $intPostGroupsDv = ($ticket) ? $intPostGroupsDv : array();
                foreach ($dataGroups['ADDED'] as $key => $row) {
                    array_push($intPostGroupsDv, $row);
                }
            }

            $dataDep = Arr::groupByArrayOnlyRows($intPostGroupsDepartmentDv, 'rowState');
            $ticket = false;
            if (isset($dataDep['REMOVED'])) {
                (Array) $intPostGroupsDepartmentDv = array();
                $ticket = true;

                foreach ($dataDep['REMOVED'] as $key => $row) {
                    array_push($intPostGroupsDepartmentDv, $row);
                }
            }

            if (isset($dataDep['ADDED'])) {
                (Array) $intPostGroupsDepartmentDv = ($ticket) ? $intPostGroupsDepartmentDv : array();
                foreach ($dataDep['ADDED'] as $key => $row) {
                    array_push($intPostGroupsDepartmentDv, $row);
                }
            }

            $dataDep1 = Arr::groupByArrayOnlyRows($intPostUserDepartmentDv, 'rowState');
            $ticket = false;

            if (isset($dataDep1['REMOVED'])) {
                (Array) $intPostUserDepartmentDv = array();
                $ticket = true;

                foreach ($dataDep1['REMOVED'] as $key => $row) {
                    array_push($intPostUserDepartmentDv, $row);
                }
            }

            if (isset($dataDep1['ADDED'])) {
                (Array) $intPostUserDepartmentDv = ($ticket) ? $intPostUserDepartmentDv : array();
                foreach ($dataDep1['ADDED'] as $key => $row) {
                    array_push($intPostUserDepartmentDv, $row);
                }
            }

            $getVideoId = '';

            if (Input::isEmpty('youtubeid') == false) {
                $getVideoId = getYoutubeVideoID(Input::post('youtubeid'));
            }

            $params = array(
                'userId' => $sessionUserId,
                'groupId' => issetParam($removedData['groupid']),
                'description' => $subjectName,
                'isActive' => '1',
                'longDescr' => $bodyDescr,
                'isComment' => (Input::postCheck('isComment') && Input::post('isComment') == '1') ? Input::post('isComment') : '',
                'isUrgent' => (Input::postCheck('isUrgent') && Input::post('isUrgent') == '1') ? Input::post('isUrgent') : '',
                'isLike' => (Input::postCheck('isLike') && Input::post('isLike') == '1') ? Input::post('isLike') : '',
                'isLongDescr' => (Input::postCheck('isLongDescr') && Input::post('isLongDescr') == '1') ? Input::post('isLongDescr') : '',
                'isPin' => (Input::postCheck('isPin') && Input::post('isPin') == '1') ? Input::post('isPin') : '',
                'categoryId' => (issetParam($removedData['typeid']) !== '' && issetParam($removedData['categoryid']) === '9988') ? '9988' : Input::post('categoryId'),
                'typeId' => (issetParam($removedData['typeid']) !== '' && issetParam($removedData['categoryid']) === '9988') ? '3' : Input::post('typeId'),
                'INT_POST_GROUPS_DV' => $intPostGroupsDv,
                'INT_POST_GROUPS_DEPARTMENT_DV' => $intPostGroupsDepartmentDv,
                'INT_POST_USER_DV' => $intPostUserDv,
                'INT_POST_USER_DEPARTMENT_DV' => $intPostUserDepartmentDv,
                'INT_CONTENT_MAP_DV' => $mmContentMapDv,
                'INT_POLL_QUESTION_DV' => $intPollQuestionDv,
                'id' => $postId,
                'youtubeId' => $getVideoId,
                'soundcloudId' => NULL,
                'locationStr' => NULL,
                'privacyType' => $ptype,
                'createdDate' => $currentDate,
                'updatedDate' => NULL,
                'deletedDate' => NULL,
                'imgUrl' => $imgUrl,
                'viewCnt' => NULL,
                'likeCnt' => NULL,
                'commentCnt' => NULL,
                'viewPercent' => NULL,
                'userIds' => NULL,
                'groupIds' => NULL,
                'departmentIds' => NULL,
                'answerTypeId' => NULL,
                'endDate' => Input::post('enddate'),
                'startdate' => Input::post('startdate'),
                'pinDate' => Input::post('pindate'),
            );

            if (issetParam($postData['isedit'])) {
                $params['isedited'] = '1';
                $params['editedDate'] = $currentDate;
            }
            /*
              array (
              'groupName' => 'test',
              'privacyType' => 'public',
              'fileExtension' => 'png',
              'fileSize' => 56014,
              'fileName' => 'steve.png',
              'coverPicture' => 'storage/uploads/process/202005/file_1590400078340913_15874390506321.png',
              'description' => 'test',
              'isActive' => '1',
              'id' => NULL,
              ); */

            if (issetParam($postData['saveProcessCode']) !== '') {

                $tempUsers = array(array(
                        'id' => NULL,
                        'groupId' => $postId,
                        'userId' => $sessionUserId,
                        'membershipTypeId' => '1587439065739',
                        'isApproved' => '1',
                        'approvedDate' => $currentDate,
                        'createdUserId' => NULL,
                        'createdDate' => NULL,
                        'departmentId' => NULL,
                ));

                if ($postData['saveProcessCode'] == 'NTR_SOCIAL_GROUP_DV_001') {

                    foreach ($intPostGroupsDv as $key => $row) {
                        if ($row['userId'] !== $sessionUserId) {
                            array_push($tempUsers, array(
                                'id' => issetParam($row['id']),
                                'rowState' => issetParam($row['rowState']),
                                'groupId' => $postId,
                                'userId' => $row['userId'],
                                'membershipTypeId' => '1587439066158',
                                'isApproved' => '1',
                                'approvedDate' => $currentDate,
                                'createdUserId' => NULL,
                                'createdDate' => NULL,
                                'departmentId' => NULL,
                            ));
                        }
                    }
                }

                $param = array(
                    'groupName' => $params['description'],
                    'groupId' => Input::post('groupId'),
                    'sessionUserId' => $sessionUserId,
                    'privacyType' => issetParam($params['privacyType']) == 'private' ? 'closed' : issetParam($params['privacyType']), //'public',
                    'fileExtension' => issetParam($params['INT_CONTENT_MAP_DV'][0]['INT_CONTENT_DV']['fileExtension']),
                    'fileSize' => issetParam($params['INT_CONTENT_MAP_DV'][0]['INT_CONTENT_DV']['fileSize']),
                    'fileName' => issetParam($params['INT_CONTENT_MAP_DV'][0]['INT_CONTENT_DV']['fileName']),
                    'coverPicture' => issetParam($params['INT_CONTENT_MAP_DV'][0]['INT_CONTENT_DV']['physicalPath']) ? issetParam($params['INT_CONTENT_MAP_DV'][0]['INT_CONTENT_DV']['physicalPath']) : issetParam($removedData['coverpicture']),
                    'description' => $params['description'],
                    'isActive' => '1',
                    'id' => $postId,
                    'NTR_SOCIAL_GROUP_MEMBERS_DV' => $tempUsers,
                );

                $result = Functions::runProcess($postData['saveProcessCode'], $param);
            } else {
                $result = Functions::runProcess('INT_POSTS_DV_001', $params);
            }
            
            if ($result['status'] === 'success') {
                $data = isset($result['result']['id']) ? self::fncRunDataview("1564662649211486", "id", "=", $result['result']['id']) : array();
                $result['data'] = isset($data[0]) ? $data[0] : array();
                $result['text'] = Lang::line('msg_save_success');
            }
        } catch (Exception $ex) {
            (Array) $result = array();

            $result['status'] = 'warning';
            $result['text'] = $ex->getMessage();
        }

        return $result;
    }

    public function getMemberDvModel() {
        $groupId = Input::post('id');

        $param = array(
            'systemMetaGroupId' => '1572343946941267',
            'showQuery' => '0',
            'ignorePermission' => '1'
        );

        $param['criteria'] = array(
            'groupid' => array(
                array(
                    'operator' => '=',
                    'operand' => $groupId
                )
            )
        );

        $data = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);
        if (isset($data['result']) && $data['result']) {
            unset($data['result']['paging']);
            unset($data['result']['aggregatecolumns']);
            return $data['result'];
        }
    }

    public function saveGroupModel() {
        $userDv = $temp = $userList = array();

        $postData = Input::postData();

        $currentDate = Date::currentDate();
        $sessionUserId = Ue::sessionUserId();

        foreach ($postData['param']['userIds'] as $key => $row) {
            if ($row && !in_array($row, $temp)) {
                $data = array(
                    'userId' => $row,
                    'id' => NULL,
                    'groupId' => NULL,
                    'createdDate' => $currentDate,
                    'createdUserId' => $sessionUserId,
                );

                array_push($userDv, $data);
                array_push($temp, $row);
            }
        }

        $userIds = '';
        $userIds = implode(',', $postData['param']['userIds']);
        $params = array(
            'groupName' => $postData['group_name'],
            'positionId' => $postData['group_position'],
            'userIds' => $userIds,
            'INT_GROUP_MEMBERS_DV' => $userDv,
            'id' => NULL,
            'createdDate' => $currentDate,
            'createdUserId' => $sessionUserId,
        );

        includeLib('Utils/Functions');
        $result = Functions::runProcess('INT_GROUPS_DV_001', $params);

        if ($result['status'] === 'success') {
            $result['text'] = Lang::line('msg_save_success');
        }
        return $result;
    }

    public function editGroupModel() {
        $postData = Input::postData();

        $modifiedDate = Date::currentDate();
        $modifiedUserId = Ue::sessionUserId();

        $params = array(
            'groupName' => $postData['group_name'],
            'id' => $postData['id'],
            'positionId' => $postData['group_position'],
            'createdDate' => $postData['created_date'],
            'userIds' => 'UPDATED',
            'createdUserId' => $postData['user_id'],
            'modifiedDate' => $modifiedDate,
            'modifiedUserId' => $modifiedUserId
        );

        includeLib('Utils/Functions');
        $result = Functions::runProcess('INT_GROUPS_DV_001', $params);

        if ($result['status'] === 'success') {
            $result['text'] = Lang::line('msg_save_success');
        }
        return $result;
    }

    public function saveMemberModel() {

        $userDv = $temp = $userList = array();

        $postData = Input::postData();

        $currentDate = Date::currentDate();
        $sessionUserId = Ue::sessionUserId();

        foreach ($postData['param']['userIds'] as $key => $row) {
            if ($row && !in_array($row, $temp)) {
                $data = array(
                    'userId' => $row,
                    'id' => NULL,
                    'groupId' => $postData['groupId'],
                    'createdDate' => $currentDate,
                    'membershipTypeId' => '1587439066158',
                    'isApproved' => '1',
                    'createdUserId' => $sessionUserId,
                );

                array_push($userDv, $data);
                array_push($temp, $row);
            }
        }
        $userIds = implode(',', $postData['param']['userIds']);
        $params = array(
            'groupName' => $postData['group_name'],
            'positionId' => $postData['group_position'],
            'userIds' => $userIds,
            'INT_GROUP_MEMBERS_DV' => $userDv,
            'id' => $postData['groupId'],
            'createdDate' => $currentDate,
            'createdUserId' => $sessionUserId,
        );

        includeLib('Utils/Functions');
        $result = Functions::runProcess('INT_GROUPS_DV_001', $params);

        if ($result['status'] === 'success') {
            $result['text'] = Lang::line('msg_save_success');
            $result['groupId'] = $postData['groupId'];
        }
        return $result;
    }

    public function readContentModel() {
        includeLib('Utils/Functions');
        $postData = Input::postData();
        $result = Functions::runProcess('INT_POST_USER_READ_DV_002', array('id' => $postData['dataRow']['postuserid'], 'isread' => '1'));
        return $result;
    }

    public function deletePostModel() {
        includeLib('Utils/Functions');
        $postData = Input::postData();
        /* $result = Functions::runProcess('INT_POST_USER_DELETE_DV_002', array ('id' => $postData['dataRow']['postuserid'], 'isDeleted' => '1', 'deletedDate' => Date::currentDate())); */
        if (issetParam($postData['dataRow']['categoryid']) == '9988') {
            $result = Functions::runProcess('NTR_SOCIAL_GROUP_DV_005', array('id' => $postData['dataRow']['id']));
        } else {
            $result = Functions::runProcess('INT_POST_ACTIVE_DV_002', array('id' => $postData['dataRow']['id'], 'isActive' => '0', 'deletedDate' => Date::currentDate()));
        }
        return $result;
    }

    public function editPostModel($paramData) {
        includeLib('Utils/Functions');
        $postData = Input::postData();

        if (issetParam($paramData['id']) && issetParam($paramData['categoryid']) === '9988') {
            $result = Functions::runProcess('NTR_SOCIAL_GROUP_GET_MEMBERS_DV_004 ', array('id' => $paramData['id']));
        } else if (issetParam($paramData['id'])) {
            $result = Functions::runProcess('INT_POSTS_GET_LIST_004', array('id' => $paramData['id']));
        }

        return issetParamArray($result['result']);
    }

    public function saveLikeModel($static = '0') {
        if ($static) {
            try {

                $postId = Input::post('postId');
                $userId = Ue::sessionUserId();
                $commentId = Input::post('commentId');
                $likeId = Input::post('likeId');

                if (issetParam($likeId) !== '' && issetParam($likeId) !== '0') {
                    if (issetParam($commentId) !== '') {
                        $this->db->Execute("DELETE FROM SCL_LIKE WHERE POST_ID = $postId AND USER_ID = $userId AND COMMENT_ID = '$commentId'");
                    } else {
                        $this->db->Execute("DELETE FROM SCL_LIKE WHERE POST_ID = $postId AND USER_ID = $userId");
                    }
                } else {

                    $likeId = getUID();

                    $data = array(
                        'ID' => $likeId,
                        'POST_ID' => $postId,
                        'COMMENT_TXT' => Input::post('post_comment'),
                        'USER_ID' => $userId,
                        'COMMENT_ID' => issetParam($commentId),
                        'LIKE_TYPE_ID' => '1',
                        'CREATED_DATE' => Date::currentDate()
                    );

                    $this->db->AutoExecute('SCL_LIKE', $data);
                }

                $count = self::getPostLikeCountModel($postId, $commentId);

                $response = array('status' => 'success', 'likeId' => $likeId, 'count' => $count);
            } catch (ADODB_Exception $ex) {
                $response = array('status' => 'error', 'message' => $ex->getMessage());
            }

            return $response;
        } else {

            includeLib('Utils/Functions');
            (Array) $result = array();
            try {

                $targetType = Input::post('targetType');
                $postId = Input::post('postId');
                $likeTypeId = Input::post('likeTypeId');

                $userid = Ue::sessionUserId();
                $sessionUserKeyId = Ue::sessionUserKeyId();
                $currentDate = Date::currentDate();

                $params = array(
                    'postId' => ($targetType == 'post') ? $postId : '',
                    'userId' => $userid,
                    'likeTypeId' => $likeTypeId,
                    'commentId' => ($targetType == 'post') ? null : $postId
                );

                $check = Functions::runProcess('INT_POST_LIKE_DV_004', $params);

                if ($check['result']['id']) {
                    $result = Functions::runProcess('INT_POST_LIKE_DV_005', array('id' => $check['result']['id']));
                } else {
                    $params['createdDate'] = $currentDate;
                    $params['createdUserId'] = $sessionUserKeyId;
                    $params['id'] = '';

                    $result = Functions::runProcess('INT_POST_LIKE_DV_001', $params);
                }
            } catch (Exception $ex) {
                $result = array('status' => 'error', 'message' => $ex->getMessage());
            }

            return $result;
        }
    }

    public function getPostLikeCountModel($postId, $commentId = '') {
        $andWhere = "AND (COMMENT_ID IS NULL OR COMMENT_ID = 0)";
        if ($commentId) {
            $andWhere = "AND COMMENT_ID = '$commentId'";
        }

        $count = $this->db->GetOne("SELECT COUNT(ID) FROM SCL_LIKE WHERE POST_ID = $postId $andWhere");
        return $count;
    }

    public function savePollModel() {
        $response = array();
        try {
            $postData = Input::postData();

            includeLib('Compress/Compression');
            $decompressContent = Compression::decode_string_array($postData['pollData']);
            /*
              foreach ($decompressContent['scl_posts_question_list'] as $key => $question) {

              if ($question['isrequired'] == '1' && !isset($postData['answerData'][$key])) {
              throw new Exception("Заавал бөглөх талбарыг бөглөнө үү!");
              }

              } */

            (Array) $intPollResultDv = array();
            $sessionuserId = Ue::sessionUserId();


            foreach ($postData['question'] as $key => $question) {
                if (issetParam($postData['answerData'][$key]) && !is_array($postData['answerData'][$key])) {
                    $temp = array(
                        'answerId' => $postData['answerData'][$key],
                        'userId' => $sessionuserId,
                        'pollQuestionId' => $question,
                        'answerDescription' => isset($postData['answerDescription'][$key]) ? $postData['answerDescription'][$key] : '',
                        'id' => NULL,
                        'postId' => NULL,
                    );
                    array_push($intPollResultDv, $temp);
                } elseif (issetParam($postData['answerData'][$key]) && is_array($postData['answerData'][$key])) {
                    foreach ($postData['answerData'][$key] as $kk => $answerData) {
                        $temp = array(
                            'answerId' => $answerData,
                            'userId' => $sessionuserId,
                            'pollQuestionId' => $question,
                            'answerDescription' => isset($postData['answerDescription'][$key][$kk]) ? $postData['answerDescription'][$key][$kk] : '',
                            'id' => NULL,
                            'postId' => NULL,
                        );
                        array_push($intPollResultDv, $temp);
                    }
                }
            }

            $params = array(
                'id' => $decompressContent['id'],
                'INT_POLL_RESULT_DV' => $intPollResultDv
            );


            includeLib('Utils/Functions');
            $response = Functions::runProcess('INT_POST_RESULT_DV_001', $params);

            if ($response['status'] === 'success') {
                $response['text'] = Lang::line('msg_save_success');
            }
        } catch (Exception $ex) {
            (Array) $response = array();

            $response['status'] = 'warning';
            $response['text'] = $ex->getMessage();
        }

        return $response;
    }

    public function saveOmsConferenceModel() {

        try {

            includeLib('Utils/Functions');
            $postData = Input::postData();
            $currentDate = Date::currentDate('Y-m-d');
            $currentDate1 = Date::currentDate();
            $sessionUserKeyId = Ue::sessionUserKeyId();
            $id = null;

            (Array) $omsMeetingGroupDV = $omsMeetingGroupDepartmentDV = $omsMeetingUserDV = $omsMeetingUserDepartmentDV = $omsMetaDmRecordMap = array();

            $userIdsTemp = $departmentIdsTemp = array();

            $ptype = 'public';

            switch (Input::post('privacyType')) {
                case '2':
                    $ptype = 'user';
                    break;
                case '3':
                    $ptype = 'department';
                    break;
                default :
                /* case '1': */
                    $ptype = 'public';
                    $userdata = self::fncRunDataview('1572435589929864');

                    if (issetParam($userdata)) {

                        foreach ($userdata as $row) {
                            if ($row && !in_array($row, $userIdsTemp) && ($row['userid'] !== '1' && $row['userid'] !== '2')) {
                                $data = array(
                                    'id' => NULL,
                                    'meetingBookId' => NULL,
                                    'userId' => $row['userid'],
                                );
                                $data1 = array(
                                    'id' => NULL,
                                    'meetingBookId' => NULL,
                                    'userId' => $row['userid'],
                                    'createdDate' => $currentDate1,
                                    'createdUserId' => $sessionUserKeyId,
                                    'isActive' => '1',
                                );

                                array_push($omsMeetingGroupDV, $data);
                                array_push($omsMeetingUserDV, $data1);
                                array_push($userIdsTemp, $row);
                            }
                        }
                    }
                    break;
            }

            if (issetParam($postData['departmentIds'])) {
                foreach ($postData['departmentIds'] as $row) {
                    if ($row && !in_array($row, $departmentIdsTemp)) {
                        $data = array(
                            'id' => NULL,
                            'meetingBookId' => NULL,
                            'departmentId' => $row,
                        );

                        array_push($omsMeetingGroupDepartmentDV, $data);
                        array_push($departmentIdsTemp, $row);

                        $userResult = Functions::runProcess('INT_GET_DEPARTMENT_USER_LIST_004', array('departmentId' => $row));
                        if (isset($userResult['result']['int_post_user_department_dv'])) {
                            foreach ($userResult['result']['int_post_user_department_dv'] as $userData) {
                                if ($userData['userid']) {
                                    if (!in_array($userData['userid'], $userIdsTemp)) {
                                        $data = array(
                                            'id' => NULL,
                                            'meetingBookId' => NULL,
                                            'userId' => $userData['userid'],
                                            'createdDate' => $currentDate,
                                            'createdUserId' => $sessionUserKeyId,
                                            'isActive' => '1',
                                        );
                                        array_push($omsMeetingUserDepartmentDV, $data);

                                        $data = array(
                                            'id' => NULL,
                                            'meetingBookId' => NULL,
                                            'userId' => $userData['userid'],
                                        );
                                        array_push($omsMeetingGroupDV, $data);
                                        array_push($userIdsTemp, $userData['userid']);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if ($ptype == 'user' && issetParam($postData['userIds'])) {
                foreach ($postData['userIds'] as $row) {
                    if ($row && !in_array($row, $userIdsTemp)) {
                        $data = array(
                            'id' => NULL,
                            'meetingBookId' => NULL,
                            'userId' => $row,
                        );
                        $data1 = array(
                            'id' => NULL,
                            'meetingBookId' => NULL,
                            'userId' => $row,
                            'createdDate' => $currentDate1,
                            'createdUserId' => $sessionUserKeyId,
                            'isActive' => '1',
                        );

                        array_push($omsMeetingGroupDV, $data);
                        array_push($omsMeetingUserDV, $data1);
                        array_push($userIdsTemp, $row);
                    }
                }
            }

            if (issetParam($postData['isedit'])) {
                includeLib('Compress/Compression');

                $id = Input::post('id');
                $removedData = Compression::decode_string_array($postData['mainData']);

                if (isset($removedData['omsmeetinggroupdv'])) {
                    foreach ($removedData['omsmeetinggroupdv'] as $key => $row) {
                        $data1 = array(
                            'rowState' => 'REMOVED',
                            'id' => $row['id'],
                        );
                        array_push($omsMeetingGroupDV, $data1);
                    }
                }

                if (isset($removedData['omsmeetinggroupdepartmentdv'])) {
                    foreach ($removedData['omsmeetinggroupdepartmentdv'] as $key => $row) {
                        $data1 = array(
                            'rowState' => 'REMOVED',
                            'id' => $row['id'],
                        );
                        array_push($omsMeetingGroupDepartmentDV, $data1);
                    }
                }

                if (isset($removedData['omsmeetinguserdv'])) {
                    foreach ($removedData['omsmeetinguserdv'] as $key => $row) {
                        $data2 = array(
                            'rowState' => 'REMOVED',
                            'id' => $row['id'],
                        );

                        array_push($omsMeetingUserDV, $data2);
                    }
                }

                if (isset($removedData['omsmeetinguserdepartmentdv'])) {
                    foreach ($removedData['omsmeetinguserdepartmentdv'] as $key => $row) {
                        $data2 = array(
                            'rowState' => 'REMOVED',
                            'id' => isset($row['id']) ? $row['id'] : '',
                        );

                        array_push($omsMeetingUserDepartmentDV, $data2);
                    }
                }
            }

            if (isset($postData['taskTypeIds']) && $postData['taskTypeIds']) {
                foreach ($postData['taskTypeIds'] as $row) {
                    if ($row) {
                        $temp = array(
                            'id' => null,
                            'srcTableName' => 'MMS_MEETING_BOOK',
                            'srcRecordId' => null,
                            'trgTableName' => 'TM_TASK_TYPE',
                            'trgRecordId' => $row,
                        );
                        array_push($omsMetaDmRecordMap, $temp);
                    }
                }
            } else {
                $omsMetaDmRecordMap = NULL;
            }


            $logDv = array(
                'id' => NULL,
                'refStructureId' => '1553696503515026',
                'recordId' => NULL,
                'wfmStatusId' => '1574913179222261',
                'wfmDescription' => 'zahialga uusgesen',
                'createdDate' => $currentDate,
                'createdUserId' => $sessionUserKeyId,
            );


            if (isset($removedData['oms_meta_wfm_log_dv'])) {
                $logDv = array(
                    'id' => $removedData['oms_meta_wfm_log_dv']['id'],
                    'refStructureId' => $removedData['oms_meta_wfm_log_dv']['refstructureid'],
                    'recordId' => $removedData['oms_meta_wfm_log_dv']['recordid'],
                    'wfmStatusId' => $removedData['oms_meta_wfm_log_dv']['wfmstatusid'],
                    'wfmDescription' => $removedData['oms_meta_wfm_log_dv']['wfmdescription'],
                    'createdDate' => $removedData['oms_meta_wfm_log_dv']['createddate'],
                    'createdUserId' => $removedData['oms_meta_wfm_log_dv']['createduserid'],
                );
            }

            $resultTemplate = self::fncRunDataview('1574750241012291', 'id', '=', Input::post('templateId'));
            $templateName = $resultTemplate[0]['name'];
            $params = array(
                'id' => $id,
                'templateId' => Input::post('templateId'),
                'name' => Input::post('name'),
                'description' => Input::post('description'),
                'startDate' => Input::post('startDate'),
                'startTime' => Input::post('startDate') . ' ' . Input::post('startTime') . ':00',
                'endTime' => Input::post('startDate') . ' ' . Input::post('endTime') . ':00',
                'categoryId' => '1574135244447',
                'createdDate' => $currentDate1,
                'createdUserId' => $sessionUserKeyId,
                'isPost' => Input::post('isPost'),
                'wfmDescription' => 'Захиалга үүсгэсэн',
                'privacyType' => $ptype,
                'omsMeetingGroupDV' => $omsMeetingGroupDV,
                'omsMeetingGroupDepartmentDV' => $omsMeetingGroupDepartmentDV,
                'omsMeetingUserDV' => $omsMeetingUserDV,
                'omsMeetingUserDepartmentDV' => ($ptype == 'user') ? array() : $omsMeetingUserDepartmentDV,
                'OMS_META_WFM_LOG_DV' => $logDv,
                'OMS_META_DM_RECORD_MAP' => $omsMetaDmRecordMap,
                'code' => NULL,
                'bookTypeId' => '2',
                'wfmStatusId' => '1574913179222261',
                'templateName' => $templateName,
            );
            /* var_dump($params); die; */
            $result = Functions::runProcess('OMS_CONFERENCE_DV_001', $params);

            if ($result['status'] === 'success') {
                $result['text'] = Lang::line('msg_save_success');
            }
        } catch (Exception $ex) {
            (Array) $result = array();

            $result['status'] = 'warning';
            $result['text'] = $ex->getMessage();
        }

        return $result;
    }

    public function issueDetailModel($id, $did) {
        $criteria = array(
            'id' => array(
                array(
                    'operator' => '=',
                    'operand' => $id
                )
            )
        );

        includeLib('Utils/Functions');
        $data = Functions::runDataViewWithoutLogin($did, $criteria);
        var_dump($criteria);
        die;
        (Array) $response = array();

        if (isset($data['result'][0]) && $data['result'][0]) {
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);
            $response = $data['result'][0];
        }

        return $response;
    }

    public function attachFilesDetailModel($id) {

        $criteria = array(
            'id' => array(
                array(
                    'operator' => '=',
                    'operand' => $id
                )
            )
        );

        $param = array(
            'systemMetaGroupId' => defined('ISCOVID') ? '1585127690397' : '1561373762692941',
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

    public function authorDetailModel($id) {
        $criteria = array(
            'id' => array(
                array(
                    'operator' => '=',
                    'operand' => $id
                )
            )
        );

        $param = array(
            'systemMetaGroupId' => '1561349508459972',
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

    public function checkListDetailModel($id) {
        $criteria = array(
            'id' => array(
                array(
                    'operator' => '=',
                    'operand' => $id
                )
            )
        );

        $param = array(
            'systemMetaGroupId' => '1563876306517644',
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

    public function participantsDetailModel($id) {
        $criteria = array(
            'id' => array(
                array(
                    'operator' => '=',
                    'operand' => $id
                )
            )
        );

        $param = array(
            'systemMetaGroupId' => '1564452753245662',
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

    public function reviewDetailModel($id) {

        includeLib('Utils/Functions');
        $result = Functions::runProcess('CMS_SUBJECT_REVIEW_WS_LIST_004', array('id' => $id, 'departmentId' => Ue::sessionDepartmentId()));
        return isset($result['result']) ? $result['result'] : array();
        die;

        $criteria = array(
            'subjectid' => array(
                array(
                    'operator' => '=',
                    'operand' => $id
                )
            ),
            'filterusersessionid' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionUserId()
                )
            )
        );

        $param = array(
            'systemMetaGroupId' => '1564641240283038',
            'showQuery' => '0',
            'ignorePermission' => 1,
        );

        if ($criteria) {
            $param['criteria'] = $criteria;
        }

        $data = $this->ws->run('serialize', self::$getDataViewCommand, $param, self::$gfServiceAddress);
        if (isset($data['result']) && $data['result']) {
            unset($data['result']['aggregatecolumns']);
            return $data['result'];
        } else {
            return array();
        }
    }

    public function decisionDetailModel_1($id) {
        $criteria = array(
            'id' => array(
                array(
                    'operator' => '=',
                    'operand' => $id
                )
            )
        );

        $param = array(
            'systemMetaGroupId' => defined('ISCOVID') ? '1585127690516' : '1564735154401326',
            'showQuery' => '0',
            'ignorePermission' => 1,
        );

        if ($criteria) {
            $param['criteria'] = $criteria;
        }

        $data = $this->ws->run('serialize', self::$getDataViewCommand, $param, self::$gfServiceAddress);
       
        if (isset($data['result']) && $data['result']) {
            unset($data['result']['aggregatecolumns']);
            return $data['result'];
        } else {
            return array();
        }
    }

    public function decisionDetailModel($id) {
        includeLib('Utils/Functions');
        $result = Functions::runProcess('CMS_DECISION_WITH_FILE_LIST_004', array('id' => $id));
       
        return isset($result['result']) ? $result['result'] : array();
    }

    public function legalFrameworkModel($id) {
        $criteria = array(
            'id' => array(
                array(
                    'operator' => '=',
                    'operand' => $id
                )
            )
        );

        $param = array(
            'systemMetaGroupId' => '1566285244427',
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

    public function subjectReviewCitizen($id) {
        $criteria = array(
            'subjectId' => array(
                array(
                    'operator' => '=',
                    'operand' => $id
                )
            )
        );

        $param = array(
            'systemMetaGroupId' => '1571735722487028',
            'showQuery' => '0',
            'ignorePermission' => 1,
        );

        if ($criteria) {
            $param['criteria'] = $criteria;
        }

        $data = $this->ws->run('serialize', self::$getDataViewCommand, $param, self::$gfServiceAddress);
        /* var_dump($data); die; */
        if (isset($data['result']) && $data['result']) {
            unset($data['result']['aggregatecolumns']);
            return $data['result'];
        } else {
            return array();
        }
    }

    public function fdashboardLayoutDataModel($layoutPosition = '', $postData = array(), $request = '0', $agent = '0') {

        Session::init();
        $currentDate = Date::currentDate('Y-m-d');

        $paramFilter = self::filterData();
        $paramFilter1 = self::filterData('-30');
        $paramFilter2 = self::filterData('', 1);

        (Array) $dashboardArr = array();
        
        if (Config::getFromCache('isNotaryServer')) {
            $dashboardArr['pos_1_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos01') && $request == '0') ? "1591693666992" : '';      //  ХҮЛЭЭГДЭЖ БУЙ БИЧИГ
            $dashboardArr['pos_2_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos02') && $request == '0') ? "1591693667108" : '';      //  ГҮЙЦЭТГЭГЧЭЭР /АЖЛУУД/
            $dashboardArr['pos_3_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos03') && $request == '0') ? "1591693667192" : '';      //  ЦАГИЙН ДЭЛГЭРЭНГҮЙ БАЛАНС
            $dashboardArr['pos_4_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos04') && $request == '0') ? "1591693667304" : '';   //  төрсөн өдөрийн dv
            $dashboardArr['pos_6_dvid'] = ($layoutPosition === '' || $layoutPosition == 'pos06') ? "1591693667508" : '';      //  minii ajil
            $dashboardArr['pos_10_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos10') && $request == '0') ? "1591693668229" : '';     //  zurgiin tsomog
            $dashboardArr['pos_12_dvid'] = ($layoutPosition === '' || $layoutPosition == 'pos12') ? "1591693668434" : '';     //  Удирдлагын зөвлөгөө
            $dashboardArr['pos_13_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos13') && $request == '0') ? "1591693668586" : '';     //  баннер
        } else {
            $dashboardArr['pos_1_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos01') && $request == '0') ? "1568362202338" : '';      //  ХҮЛЭЭГДЭЖ БУЙ БИЧИГ\
            $dashboardArr['pos_2_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos02') && $request == '0') ? "1572351189226" : '';      //  ГҮЙЦЭТГЭГЧЭЭР /АЖЛУУД/
            $dashboardArr['pos_3_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos03') && $request == '0') ? "1572350684801" : '';      //  ЦАГИЙН ДЭЛГЭРЭНГҮЙ БАЛАНС
            $dashboardArr['pos_4_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos04') && $request == '0') ? "1464244142438861" : '';   //  төрсөн өдөрийн dv
            $dashboardArr['pos_6_dvid'] = ($layoutPosition === '' || $layoutPosition == 'pos06') ? "1586233740664" : '';      //  minii ajil
            $dashboardArr['pos_10_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos10') && $request == '0') ? "1586404271492" : '';     //  zurgiin tsomog
            $dashboardArr['pos_12_dvid'] = ($layoutPosition === '' || $layoutPosition == 'pos12') ? "1586931934699" : '';     //  Удирдлагын зөвлөгөө
            $dashboardArr['pos_13_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos13') && $request == '0') ? "1586494409900" : '';     //  баннер
        }

        $dashboardArr['pos_1'] = $this->fncRunDataview($dashboardArr['pos_1_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
        $dashboardArr['pos_2'] = $this->fncRunDataview($dashboardArr['pos_2_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
        $dashboardArr['pos_3'] = $this->fncRunDataview($dashboardArr['pos_3_dvid'], "filterEndDate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -1 days'), self::filterData('', '1'), "", "", "0");
        $dashboardArr['pos_4'] = $this->fncRunDataview($dashboardArr['pos_4_dvid'], "birthdate", "=", Date::formatter($currentDate, 'm-d'), array(), "", "", "0");
        $dashboardArr['pos_10'] = $this->fncRunDataview($dashboardArr['pos_10_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
        /* $dashboardArr['pos_11'] = $this->fncRunDataview($dashboardArr['pos_11_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0"); */
        $dashboardArr['pos_12'] = $this->fncRunDataview($dashboardArr['pos_12_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, 'createddate', 'desc', '0');
        $dashboardArr['pos_13'] = $this->fncRunDataview($dashboardArr['pos_13_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, 'createddate', 'desc', '0', false, '5');
        
        return $dashboardArr;
        
    }
    
    public function dashboardLayoutDataModel($layoutPosition = '', $postData = array(), $request = '0', $agent = '0') {

        Session::init();
        $currentDate = Date::currentDate('Y-m-d');

        $paramFilter = self::filterData();
        $paramFilter1 = self::filterData('-30');
        $paramFilter2 = self::filterData('', 1);

        (Array) $dashboardArr = array();
        
        if (Config::getFromCache('isNotaryServer')) {
            $dashboardArr['pos_0_dvid'] = ($layoutPosition === '' || $layoutPosition == 'pos00') ? "1591693667705" : '';      //  shuurhai medee
            $dashboardArr['pos_1_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos01') && $request == '0') ? "1591693666992" : '';      //  ХҮЛЭЭГДЭЖ БУЙ БИЧИГ
            $dashboardArr['pos_2_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos02') && $request == '0') ? "1591693667108" : '';      //  ГҮЙЦЭТГЭГЧЭЭР /АЖЛУУД/
            $dashboardArr['pos_3_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos03') && $request == '0') ? "1591693667192" : '';      //  ЦАГИЙН ДЭЛГЭРЭНГҮЙ БАЛАНС
            $dashboardArr['pos_4_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos04') && $request == '0') ? "1591693667304" : '';   //  төрсөн өдөрийн dv
            $dashboardArr['pos_5_dvid'] = ($layoutPosition === '' || $layoutPosition == 'pos05') ? "1593408541474" : '';      //  bichig barimt '1586404271676'
            $dashboardArr['pos_6_dvid'] = ($layoutPosition === '' || $layoutPosition == 'pos06') ? "1591693667508" : '';      //  minii ajil
            $dashboardArr['pos_7_dvid'] = ($layoutPosition === '' || $layoutPosition == 'pos07') ? "1591693667705" : '';      //  medee medeelel
            $dashboardArr['pos_8_dvid'] = ($layoutPosition === '' || $layoutPosition == 'pos08') ? "1591693667951" : '';      //  file-n san
            $dashboardArr['pos_9_dvid'] = ($layoutPosition === '' || $layoutPosition == 'pos09') ? "1591693668126" : '';      //  heleltsuulege
            $dashboardArr['pos_10_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos10') && $request == '0') ? "1591693668229" : '';     //  zurgiin tsomog
            $dashboardArr['pos_11_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos11') && $agent == '0') ? "1584011654594693" : '';  //  Ажилтны явцын хяналт
            $dashboardArr['pos_12_dvid'] = ($layoutPosition === '' || $layoutPosition == 'pos12') ? "1591693668434" : '';     //  Удирдлагын зөвлөгөө
            $dashboardArr['pos_13_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos13') && $request == '0') ? "1591693668586" : '';     //  баннер
            $dashboardArr['pos_14_dvid'] = ($layoutPosition === '' || $layoutPosition == 'pos14') ? "1592355418142" : '';       //  Санал хүсэлт
            $dashboardArr['pos_15_dvid'] = ($layoutPosition === '' || $layoutPosition == 'pos15') ? "1593408875432" : '';       //  Хүний нөөцийн хүсэлт
            $dashboardArr['pos_16_dvid'] = ($layoutPosition === '' || $layoutPosition == 'pos16') ? "1591166562522846" : '';    //  Нотариатчдын хүйс
        } else {
            $dashboardArr['pos_0_dvid'] = ($layoutPosition === '' || $layoutPosition == 'pos00') ? "1586846284071" : '';      //  shuurhai medee
            $dashboardArr['pos_1_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos01') && $request == '0') ? "1568362202338" : '';      //  ХҮЛЭЭГДЭЖ БУЙ БИЧИГ
            $dashboardArr['pos_2_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos02') && $request == '0') ? "1572351189226" : '';      //  ГҮЙЦЭТГЭГЧЭЭР /АЖЛУУД/
            $dashboardArr['pos_3_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos03') && $request == '0') ? "1572350684801" : '';      //  ЦАГИЙН ДЭЛГЭРЭНГҮЙ БАЛАНС
            $dashboardArr['pos_4_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos04') && $request == '0') ? "1464244142438861" : '';   //  төрсөн өдөрийн dv
            $dashboardArr['pos_5_dvid'] = ($layoutPosition === '' || $layoutPosition == 'pos05') ? "1571474482320" : '';      //  bichig barimt '1586404271676'
            $dashboardArr['pos_6_dvid'] = ($layoutPosition === '' || $layoutPosition == 'pos06') ? "1586233740664" : '';      //  minii ajil
            $dashboardArr['pos_7_dvid'] = ($layoutPosition === '' || $layoutPosition == 'pos07') ? "1586404272193" : '';      //  medee medeelel
            $dashboardArr['pos_8_dvid'] = ($layoutPosition === '' || $layoutPosition == 'pos08') ? "1586404271453" : '';      //  file-n san
            $dashboardArr['pos_9_dvid'] = ($layoutPosition === '' || $layoutPosition == 'pos09') ? "1586404271613" : '';      //  heleltsuulege
            $dashboardArr['pos_10_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos10') && $request == '0') ? "1586404271492" : '';     //  zurgiin tsomog
            $dashboardArr['pos_11_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos11') && $agent == '0') ? "1587095552879524" : '';  //  Ажилтны явцын хяналт
            $dashboardArr['pos_12_dvid'] = ($layoutPosition === '' || $layoutPosition == 'pos12') ? "1586931934699" : '';     //  Удирдлагын зөвлөгөө
            $dashboardArr['pos_13_dvid'] = (($layoutPosition === '' || $layoutPosition == 'pos13') && $request == '0') ? "1586494409900" : '';     //  баннер
            $dashboardArr['pos_14_dvid'] = ($layoutPosition === '' || $layoutPosition == 'pos14') ? "1586944748632632" : '';  //  Санал хүсэлт
            $dashboardArr['pos_15_dvid'] = ($layoutPosition === '' || $layoutPosition == 'pos15') ? "1568362804484" : '';     //  Хүний нөөцийн хүсэлт
            $dashboardArr['pos_16_dvid'] = ($layoutPosition === '' || $layoutPosition == 'pos16') ? "1591166562522846" : '';     //  Нотариатчдын хүйс
        }

        $dashboardArr['pos_0'] = $this->fncRunDataview($dashboardArr['pos_0_dvid'], "isurgent", "=", '1', array(), 'createddate', 'desc', '0', false, '3');
        $dashboardArr['pos_1'] = $this->fncRunDataview($dashboardArr['pos_1_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
        $dashboardArr['pos_2'] = $this->fncRunDataview($dashboardArr['pos_2_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
        $dashboardArr['pos_3'] = $this->fncRunDataview($dashboardArr['pos_3_dvid'], "filterEndDate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -1 days'), self::filterData('', '1'), "", "", "0");
        $dashboardArr['pos_4'] = $this->fncRunDataview($dashboardArr['pos_4_dvid'], "birthdate", "=", Date::formatter($currentDate, 'm-d'), array(), "", "", "0");
        $dashboardArr['pos_5'] = $this->fncRunDataview($dashboardArr['pos_5_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
        $dashboardArr['pos_6'] = $this->fncRunDataview($dashboardArr['pos_6_dvid'], "filterStartDate", "=", Date::formatter($currentDate, 'Y-m') . '-01', $paramFilter1, "", "", "0", false, '10');
        $dashboardArr['pos_7'] = $this->fncRunDataview($dashboardArr['pos_7_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
        $dashboardArr['pos_8'] = $this->fncRunDataview($dashboardArr['pos_8_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
        $dashboardArr['pos_9'] = $this->fncRunDataview($dashboardArr['pos_9_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
        $dashboardArr['pos_10'] = $this->fncRunDataview($dashboardArr['pos_10_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
        $dashboardArr['pos_11'] = $this->fncRunDataview($dashboardArr['pos_11_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
        $dashboardArr['pos_12'] = $this->fncRunDataview($dashboardArr['pos_12_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, 'createddate', 'desc', '0');
        $dashboardArr['pos_13'] = $this->fncRunDataview($dashboardArr['pos_13_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, 'createddate', 'desc', '0', false, '5');

        $filterdate = (($layoutPosition === '' || $layoutPosition == 'pos14') && isset($postData['date'])) ? $postData['date'] : $currentDate;
        
        $dashboardArr['pos_14'] = $this->fncRunDataview($dashboardArr['pos_14_dvid'], "startdate", "=", $filterdate, $paramFilter, 'createddate', 'desc', '0');
        $dashboardArr['pos_15'] = $this->fncRunDataview($dashboardArr['pos_15_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, 'createddate', 'desc', '0', false, '10');
        $dashboardArr['pos_16'] = $this->fncRunDataview($dashboardArr['pos_16_dvid']);

        return $dashboardArr;

    }

    public function filterData($addDate = '', $useFilterStartDate = null) {
        $currentDate = Date::currentDate('Y-m-d');

        $data = array(
            'sessionDepartmentId' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionDepartmentId()
                )
            ),
            'filterDepartmentId' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionDepartmentId()
                )
            ),
            'sessionUserId' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionUserId()
                )
            ),
            'filterUserId' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionUserId()
                )
            ),
            'sessionUserKeyId' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionUserKeyId()
                )
            ),
            'filterNextWfmUserId' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionUserKeyId()
                )
            ),
            'filterStartDateMonthStart' => array(
                array(
                    'operator' => '=',
                    'operand' => Date::currentDate('Y-m') . '-01'
                )
            ),
            'filterEndDateMonthEnd' => array(
                array(
                    'operator' => '=',
                    'operand' => Date::weekdayAfter('Y-m-d', $currentDate, ' -1 days')
                )
            ),
        );

        if ($useFilterStartDate) {
            $data['filterstartdate'] = array(
                array(
                    'operator' => '=',
                    'operand' => Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days')
                )
            );
        }

        if (!$useFilterStartDate) {
            $data['filterEndDate'] = array(
                array(
                    'operator' => '=',
                    'operand' => ($addDate) ? Date::formatter($currentDate, 'Y-m') . $addDate : $currentDate
                )
            );
        }

        return $data;
    }

    public function getPollDataModel() {

        includeLib('Utils/Functions');
        $result = Functions::runProcess('SCL_POST_QUESTION_GET_LIST_004', array('sessionuserid' => Ue::sessionUserKeyId()));
        return isset($result['result']) ? $result['result'] : array();
    }

    public function requestDataModel() {
        (Array) $dashboardArr = array();

        $uniqId = Input::post('uniqId');
        $colorData = array('#ff7e79', '#ff8e51', '#fec244', '#39e0d0', '#48c6f3', '#6373ed');
        $isagent = Input::post('isagent');

        $postData = Input::postData();
        $pos = Input::post('layoutPosition') ? Input::post('layoutPosition') : '';
        $dashboardArr = self::dashboardLayoutDataModel($pos, $postData, '1', $isagent);
        
        (Array) $hr_arr = $task_arr = $news_arr = $shuurhai_arr = $heleltsuuleg = array();
        (Array) $pos14 = $pos06 = $pos12 = $pos08 = array();
        (Array) $pos_5Arr = array();
        (Array) $pos_8Arr = array();

        (String) $pos05 = '';

        if ($dashboardArr['pos_0']) {
            foreach ($dashboardArr['pos_0'] as $key => $row) {
                (String) $html = '';
                $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                $bgcolorData = array('event-panel-pink', 'event-panel-green', 'event-panel-primary', 'event-panel-orange');

                $html .= '<li class="media" data-id="' . $row['id'] . '">';
                $html .= '<div class="media-left">';
                $html .= '<label>' . $row['monday'] . '</label>';
                $html .= '<p>' . issetParam($row['digitday']) . '</p>';
                $html .= '</div>';
                $html .= '<div class="media-body ' . $bgcolorData[rand(0, 3)] . ' mt2">';
                $html .= '<span class="event-time text-two-line" data-rowdata="' . $rowJson . '" onclick="drilldownLink_intranet_' . $uniqId . '(this)">' . $row['description'] . '</span>';
                $html .= '</div>';
                $html .= '</li>';

                $shuurhai_arr[$row['id']] = $html;
            }
        }

        (Array) $pos_6Arr = array();
        (Array) $pos06['all'] = $pos06['today'] = $pos06['past'] = array();

        if (isset($dashboardArr['pos_6'][0]['timetense'])) {
            $pos_6Arr = Arr::groupByArrayOnlyRows($dashboardArr['pos_6'], 'timetense');

            foreach ($pos_6Arr as $key => $array) {
                foreach ($array as $row) {
                    (String) $html = '';
                    $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');

                    switch ($key) {
                        case 'past':
                            $html .= '<div class="event-box" data-id="' . $row['id'] . '" data-taskdata="' . $rowJson . '" data-date="' . Date::formatter($row['enddate']) . '">';
                            $html .= '<div style="min-width:90px;float:left;">';
                            $html .= '<div class="calendar-date project-data-group">';
                            $html .= '<div class="d-flex flex-column">';
                            $html .= '<h3 class="text-black d-flex align-items-center">';
                            $html .= '<i data-feather="calendar" class="svg-14 mr-1 text-muted"></i>';
                            $html .= '<span>' . Date::formatter($row['startdate']) . '</span>';
                            $html .= '</h3>';
                            $html .= '<h3 class="text-black d-flex align-items-center">';
                            $html .= '<i data-feather="calendar" class="svg-14 mr-1 text-muted"></i>';
                            $html .= '<span class="text-danger">' . Date::formatter($row['enddate']) . '</span>';
                            $html .= '</h3>';
                            $html .= '</div>';
                            $html .= '</div>';
                            $html .= '</div>';
                            $html .= '<div class="col">';
                            $html .= '<div class="project-data-group">';
                            $html .= '<div style="width:31px;">';
                            $html .= '<div class="d-flex flex-column">';
                            $html .= '<h3>' . ((isset($row['starttime']) && $row['starttime']) ? Date::formatter($row['starttime'], 'H:i') : '') . '</h3>';
                            $html .= '<h3>' . ((isset($row['endtime']) && $row['endtime']) ? Date::formatter($row['endtime'], 'H:i') : '') . '</h3>';
                            $html .= '</div>';
                            $html .= '</div>';
                            $html .= '<div class="taskdesc">';
                            $html .= '<div>' . $row['tasktypename'] . '</div>';
                            $html .= '<div class="text-muted line-height-normal">' . $row['taskname'] . '</div>';
                            $html .= '</div>';
                            $html .= '</div>';
                            $html .= '</div>';
                            $html .= '</div>';
                            $pos06['past'][$row['id']] = $html;

                            break;
                        case 'today':
                            $html .= '<div class="event-box" data-id="' . $row['id'] . '" data-taskdata="' . $rowJson . '">';
                            $html .= '<div style="min-width:90px;float:left;">';
                            $html .= '<div class="calendar-date project-data-group">';
                            $html .= '<div class="d-flex flex-column">';
                            $html .= '<h3 class="text-black d-flex align-items-center">';
                            $html .= '<i data-feather="calendar" class="svg-14 mr-1 text-muted"></i>';
                            $html .= '<span>' . Date::formatter($row['startdate']) . '</span>';
                            $html .= '</h3>';
                            $html .= '<h3 class="text-black d-flex align-items-center">';
                            $html .= '<i data-feather="calendar" class="svg-14 mr-1 text-muted"></i>';
                            $html .= '<span class="text-danger">' . Date::formatter($row['enddate']) . '</span>';
                            $html .= '</h3>';
                            $html .= '</div>';
                            $html .= '</div>';
                            $html .= '</div>';
                            $html .= '<div class="col">';
                            $html .= '<div class="project-data-group">';
                            $html .= '<div style="width:31px;">';
                            $html .= '<div class="d-flex flex-column">';
                            $html .= '<h3>' . ((isset($row['starttime']) && $row['starttime']) ? Date::formatter($row['starttime'], 'H:i') : '') . '</h3>';
                            $html .= '<h3>' . ((isset($row['endtime']) && $row['endtime']) ? Date::formatter($row['endtime'], 'H:i') : '') . '</h3>';
                            $html .= '</div>';
                            $html .= '</div>';
                            $html .= '<div class="taskdesc">';
                            $html .= '<div>' . $row['tasktypename'] . '</div>';
                            $html .= '<div class="text-muted line-height-normal">' . $row['taskname'] . '</div>';
                            $html .= '</div>';
                            $html .= '</div>';
                            $html .= '</div>';
                            $html .= '</div>';
                            $pos06['today'][$row['id']] = $html;

                            break;
                    }

                    if ($html) {
                        $pos06['all'][$row['id']] = $html;
                    }
                }
            }
        }

        if ($dashboardArr['pos_7']) {
            foreach ($dashboardArr['pos_7'] as $key => $row) {
                (String) $html = '';

                $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');

                $html .= '<li class="list-group-item align-items-center" data-id="' . $row['id'] . '">';
                $html .= '<div class="avatar">';
                if (!empty($row['physicalpath']) && file_exists($row['physicalpath'])) {
                    $html .= '<img src="' . $row['physicalpath'] . '" onerror="onUserImageError(this);" alt="" width="44" height="44">';
                } else {
                    $html .= '<span class="avatar-initial rounded-circle" style="background: ' . $colorData[rand(0, 5)] . ';">' . Str::utf8_substr($row['description'], 0, 1) . '</span>';
                }
                $html .= '</div>';
                $html .= '<div class="list-body">';
                $html .= '<p class="person-name mb-0">';
                $html .= '<a href="javascript:void(0);" onclick="drilldownLink_intranet_' . $uniqId . '(this)" data-rowdata="' . $rowJson . '">' . $row['description'] . '</a>';
                $html .= '</p>';
                $html .= '<p class="person-location">' . $row['name'] . '</p>';
                $html .= '<p class="person-location">' . $row['createddate'] . '</p>';
                $html .= '</div>';
                $html .= '</li>';

                $news_arr[$row['id']] = $html;
            }
        }

        if ($dashboardArr['pos_11']) {

            $currentDate = Date::currentDate();
            $dd = Date::format('D', $currentDate);
            switch ($dd) {
                case 'Sun':
                    $currentDate = date('Y-m-d', strtotime($currentDate . ' - 2 days'));
                    Date::lastDay('Y-m-d', $currentDate, '2');
                    break;
                case 'Sat':
                    $currentDate = date('Y-m-d', strtotime($currentDate . ' - 1 days'));
                    Date::lastDay('Y-m-d', $currentDate, '1');
                    break;
                default:
                    break;
            }

            $a1 = Date::addWorkingDays('Y-m-d', '-', $currentDate, 5);
            $a2 = Date::addWorkingDays('Y-m-d', '-', $currentDate, 4);
            $a3 = Date::addWorkingDays('Y-m-d', '-', $currentDate, 3);
            $a4 = Date::addWorkingDays('Y-m-d', '-', $currentDate, 2);
            $a5 = Date::addWorkingDays('Y-m-d', '-', $currentDate, 1);

            foreach ($dashboardArr['pos_11'] as $key => $row) {
                (String) $html = '';

                $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');

                $html .= '<tr data-id="' . $row['userid'] . '">';
                $html .= '<td style="width: 350px;">';
                $html .= '<div class="d-flex align-items-center">';
                $html .= '<div class="mr-2">';
                $html .= '<a href="javascript:void(0);">';
                $html .= '<img src="' . $row['picture'] . '" class="rounded-circle" width="34" height="34" alt="" onerror="onUserImageError(this);" data-userid="' . (isset($row['userid']) ? $row['userid'] : '') . '">';
                $html .= '</a>';
                $html .= '</div>';
                $html .= '<div>';
                $html .= '<a href="javascript:void(0);" class="text-blue font-weight-bold" onclick="appMultiTab({metaDataId: \'1533876130306\', title: \'Цаг бүртгэл\', type: \'dataview\', proxyId: \'\'}, this);">' . $row['employeename'] . '</a>';
                $html .= '<div class="text-muted font-size-sm">';
                $html .= '<div class="line-height-normal font-size-11">' . $row['positionname'] . '</div>';
                $html .= '<span class="line-height-normal font-size-11">' . $row['departmentname'] . '</span>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</td>';
                $html .= '<td><div class="text-black" style="width: 80px;">' . $row[Str::lower(Date::format('l', $a1)) . 'outtime'] . '</div></td>';
                $html .= '<td><div class="text-black" style="width: 80px;">' . $row[Str::lower(Date::format('l', $a2)) . 'outtime'] . '</div></td>';
                $html .= '<td><div class="text-black" style="width: 80px;">' . $row[Str::lower(Date::format('l', $a3)) . 'outtime'] . '</div></td>';
                $html .= '<td><div class="text-black" style="width: 80px;">' . $row[Str::lower(Date::format('l', $a4)) . 'outtime'] . '</div></td>';
                $html .= '<td><div class="text-black" style="width: 80px;">' . $row[Str::lower(Date::format('l', $a5)) . 'outtime'] . '</div></td>';
                $html .= '<td style="width: 150px;">';
                $html .= $row['taskpercent'];
                $html .= '<div class="progress mt-1" style="height: 0.375rem;">';
                $html .= '<div class="progress-bar bg-success" style="width: ' . $row['taskpercent2'] . '%">';
                $html .= '<span class="sr-only">' . $row['taskpercent2'] . '% Complete</span>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</td>';
                $html .= '<td style="width: 150px;">';
                $html .= '<div class="d-flex align-items-center justify-content-end">';
                $html .= '<span class="text-black font-weight-bold mr-2">' . (($row['documentcc'] == '0/0') ? '' : $row['documentcc']) . '</span>';
                $html .= '<span class="badge border-radius-100 ' . ((int) $row['totalpercent'] > '50' ? 'bg-success' : 'bg-danger') . ' ">' . (($row['documentcc'] == '0/0') ? '' : $row['totalpercent']) . '</span>';
                $html .= '</div>';
                $html .= '</td>';
                $html .= '</tr>';

                $task_arr[$row['userid']] = $html;
            }
        }

        if ($dashboardArr['pos_14']) {

            $pos14Check = sizeof($dashboardArr['pos_14']) > 1 ? true : false;
            foreach ($dashboardArr['pos_14'] as $key => $row) {
                (String) $html = '';

                $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                $html .= '<div class="event-box event-box event-box' . $uniqId . '" data-row="' . $rowJson . '" data-id="' . $row['id'] . '" data-startdate="' . $row['startdate'] . '" data-wfmstatuscode="' . $row['wfmstatuscode'] . '">';
                $html .= '<div style="min-width:90px;float:left;">';
                $html .= '<div class="calendar-date project-data-group">';
                $html .= '<div class="d-flex flex-column">';
                $html .= '<h3 class="text-black d-flex align-items-center">';
                $html .= '<i data-feather="calendar" class="svg-14 mr-1 text-muted"></i>';
                $html .= '<span>' . Date::formatter($row['startdate']) . '</span>';
                $html .= '</h3>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<div class="col">';
                $html .= '<div class="d-flex align-items-center justify-content-between w-100">';
                $html .= '<div class="d-flex project-data-group">';
                $html .= '<div style="width:32px;">';
                $html .= '<div class="d-flex flex-column">';
                $html .= '<h3>' . Date::formatter($row['starttime'], 'H:i') . '</h3>';
                $html .= '<h3 class="text-danger">' . Date::formatter($row['endtime'], 'H:i') . '</h3>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<div class="taskdesc">';
                $html .= '<div>' . $row['booktypename'] . '</div>';
                $html .= '<div class="text-muted">' . $row['description'] . '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<div class="ml-auto" style="min-width: 110px;">';
                $html .= '<div class="d-flex flex-column">';
                $html .= '<h3 class="text-danger"><span class="btn btn-sm btn-light btn-rounded text-white" style="background-color: ' . ($row['wfmstatuscolor'] ? $row['wfmstatuscolor'] : '#3F51B5') . '">' . $row['wfmstatusname'] . '</span></h3>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';

                $pos14[$row['id']] = $html;
            }
        }

        if ($dashboardArr['pos_15']) {
            foreach ($dashboardArr['pos_15'] as $key => $row) {
                (String) $html = '';

                $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');

                $html .= '<div class="event-box" data-rowdata="' . $rowJson . '" data-id="' . issetVar($row['id']) . '">';
                $html .= '<div style="min-width:90px;float:left;">';
                $html .= '<div class="calendar-date project-data-group">';
                $html .= '<div class="d-flex flex-column">';
                $html .= '<h3 class="text-black d-flex align-items-center">';
                $html .= '<i data-feather="calendar" class="svg-14 mr-1 text-muted"></i>';
                $html .= '<span>' . (isset($row['startdate']) ? Date::formatter($row['startdate']) : '0000-00-00') . '</span>';
                $html .= '</h3>';
                $html .= '<h3 class="text-black d-flex align-items-center">';
                $html .= '<i data-feather="calendar" class="svg-14 mr-1 text-muted"></i>';
                $html .= '<span class="text-danger">' . (isset($row['enddate']) ? Date::formatter($row['enddate']) : '0000-00-00') . '</span>';
                $html .= '</h3>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<div class="col">';
                $html .= '<div class="project-data-group">';
                $html .= '<div style="width:35px;">';
                $html .= '<div class="d-flex flex-column">';
                $html .= '<h3>' . (isset($row['startdate']) ? Date::formatter($row['starttime'], 'H:i') : '--:--') . '</h3>';
                $html .= '<h3 class="text-danger">' . (isset($row['enddate']) ? Date::formatter($row['enddate'], 'H:i') : '--:--') . '</h3>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<div class="d-flex align-items-center justify-content-between w-100 taskdesc app-req">';
                $html .= '<div class="d-flex">';
                $html .= '<div class="avatar mr-2">';
                $html .= '<img src="' . issetVar($row['picture']) . '" class="rounded-circle" alt="" onerror="onUserImageError(this);">';
                $html .= '</div>';
                $html .= '<div class="mr-3" style="width:120px;">';
                $html .= '<div>' . issetVar($row['employeename']) . '</div>';
                $html .= '<div class="text-muted line-height-normal">' . issetVar($row['positionname']) . '</div>';
                $html .= '</div>';
                $html .= '<div style="width:250px;">';
                $html .= '<div>' . issetVar($row['booktypename']) . '</div>';
                $html .= '<div class="text-muted line-height-normal">' . issetVar($row['description']) . '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<div class="ml-auto" style="min-width: 110px;">';
                $html .= '<button type="button" class="btn btn-sm btn-light btn-rounded" data-row="' . $rowJson . '" onclick="drilldownLinkCustome2_' . $uniqId . '(this)" >Төлөв өөрчлөх</button>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';

                $hr_arr[issetVar($row['id'])] = $html;
            }
        }

        if ($dashboardArr['pos_12']) {
            foreach ($dashboardArr['pos_12'] as $key => $row) {
                (String) $html = '';

                $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                $target = '<li data-target="#carouselSlide2" data-slide-to="' . $key . '" data-id="' . $row['id'] . '"></li>';

                $html .= '<div class="carousel-item" data-id="' . $row['id'] . '">';
                $html .= '<div class="card card-hover card-blog-one">';
                $html .= '<a href="javascript:void(0);" onclick="wordstrue' . $uniqId . '(this);" data-rowdata="' . $rowJson . '">';
                $html .= '<div class="card-img-wrapper">';
                $html .= '<img src="' . $row['physicalpath'] . '" class="card-img" onerror="onUserImageError(this);">';
                $html .= '</div>';
                $html .= '<div class="marker t-10 l-10 bg-success tx-white text-one-line">';
                $html .= $row['description'];
                $html .= '</div>';
                $html .= '<div class="card-body">';
                $html .= '<p class="card-desc text-two-line">' . (isset($row['body']) ? Str::htmltotext($row['body']) : '') . '</p>';
                $html .= '</div>';
                $html .= '</a>';
                $html .= '<div class="card-footer">';
                $html .= '<a href="javascript:void(0);" class="mr-3" onclick="like_' . $uniqId . '(\'' . $row['id'] . '\', \'post\', \'1\', this)"><i ssdata-feather="heart" class="ssssvg-14 font-size-13 mr5 fa fa-heart' . ($row['isliked'] == '1' ? '' : '-o') . ' "></i> <span>' . $row['likecount'] . '</span></a>';
                $html .= '<span class="tx-gray-500 ml-auto d-flex"><i data-feather="calendar" class="svg-14"></i> <span>' . $row['tsag'] . '</span></span>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';

                $pos12[$row['id']]['target'] = $target;
                $pos12[$row['id']]['html'] = $html;
            }
        }

        if (isset($dashboardArr['pos_5'][0]['cardtitle'])) {
            $pos_5Arr = Arr::groupByArrayOnlyRows($dashboardArr['pos_5'], 'cardtitle');
            (String) $html = '';

            if ($pos_5Arr) {
                foreach ($pos_5Arr as $key => $row) {
                    $html .= '<div class="' . (Config::getFromCache('isNotaryServer') ? 'col' : 'col-4') . '">';
                    $html .= '<div class="card-header bg-transparent">';
                    $html .= '<div class="media align-items-center">';
                    $html .= '<div class="media-body">';
                    $html .= '<h6 class="tx-gray mb-0">' . $key . '</h6>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '<div class="profile-info">';
                    foreach ($row as $key2 => $subRow) {
                        $rowJson = htmlentities(json_encode($subRow), ENT_QUOTES, 'UTF-8');
                        $html .= '<div class="' . (Config::getFromCache('isNotaryServer') ? 'col' : 'col-md-4') . ' three-card-col">';
                        $html .= '<h5 class="tx-primary font-size-18 mb-1">';
                        $html .= '<a href="javascript:;" data-row="' . $rowJson . '" onclick="drilldownLinkCustome3_' . $uniqId . '(this)">' . $subRow['cardvalue'] . '</a>';
                        $html .= '</h5>';
                        $html .= '<p>' . $subRow['cardname'] . '</p>';
                        $html .= '</div>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</div>';
                }
            }

            $pos05 = $html;
        }

        if (isset($dashboardArr['pos_8'][0]['categorytypeid'])) {
            $pos_8Arr = Arr::groupByArray($dashboardArr['pos_8'], 'categorytypeid');

            foreach ($pos_8Arr as $key => $prow) {
                $pos08[$key] = array();

                if ($prow['rows']) {
                    foreach ($prow['rows'] as $key2 => $row) {
                        (String) $html = '';
                        $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');

                        $fileview = $icon = $color = '';
                        switch ($row['fileextension']) {
                            case 'png':
                            case 'gif':
                            case 'jpeg':
                            case 'pjpeg':
                            case 'jpg':
                            case 'x-png':
                            case 'bmp':
                                $icon = "icon-file-picture";
                                $color = "bg-blue";
                                break;
                            case 'zip':
                            case 'rar':
                                $icon = "icon-file-zip";
                                $color = "bg-pink";
                                break;
                            case 'pdf':
                                $icon = "icon-file-pdf";
                                $color = "bg-danger";
                                break;
                            case 'mp3':
                                $icon = "icon-file-music";
                                $color = "bg-purple";
                                break;
                            case 'mp4':
                                $icon = "icon-file-video";
                                $color = "bg-purple";
                                break;
                            case 'doc':
                            case 'docx':
                                $icon = "icon-file-word";
                                $color = "bg-primary";
                                break;
                            case 'ppt':
                            case 'pptx':
                                $icon = "icon-file-ppt";
                                $color = "bg-danger";
                                break;
                            case 'xls':
                            case 'xlsx':
                                $icon = "icon-file-xls";
                                $color = "bg-green";
                                break;
                            default:
                                $icon = "icon-file-jpg";
                                $color = "bg-primary";
                                break;
                        }

                        $html .= '<li class="list-group-item" data-id="' . $row['id'] . '">';
                        $html .= '<div onclick="dataViewFileViewer(this, \'1\', \'' . $row['fileextension'] . '\', \'' . $row['physicalpath'] . '\',  \'' . URL . $row['physicalpath'] . '\', \'undefined\');">';
                        $html .= '<div class="list-group-icon ' . $color . ' tx-white">';
                        $html .= '<i class="' . $icon . '"></i>';
                        $html .= '</div>';
                        $html .= '</div>';
                        $html .= '<div class="list-body">';
                        $html .= '<p class="person-name mb-0">';
                        $html .= '<a href="javascript:void(0);" onclick="drilldownLink_intranet_' . $uniqId . '(this)"  data-rowdata="<?php echo $rowJson; ?>">';
                        $html .= $row['description'];
                        $html .= '</a>';
                        $html .= '</p>';
                        $html .= '<p class="person-location">' . $row['createddate'] . '</p>';
                        $html .= '</div>';
                        $html .= '<a href="javascript:void(0);" class="person-more font-family-Oswald">' . (isset($row['filesize']) ? formatSizeUnits($row['filesize'], 0) : '') . '</a>';
                        $html .= '</li>';

                        $pos08[$key][$row['id']] = $html;
                    }
                }
            }
        }

        if ($dashboardArr['pos_9']) {

            foreach ($dashboardArr['pos_9'] as $key => $row) {
                (String) $html = '';
                $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');

                $html .= '<li class="list-group-item align-items-center" data-id="' . issetVar($row['id']) . '">';
                $html .= '<a href="javascript:;" data-rowdata="' . $rowJson . '" onclick="drilldownLink_intranet_<?php echo $this->uniqId ?>(this)">';
                $html .= '<div class="avatar mr-2">';
                $html .= '<img src="' . issetVar($row['physicalpath']) . '" class="rounded-circle" alt="" onerror="onUserImageError(this);" data-userid="' . issetVar($row['userid']) . '">';
                $html .= '</div>';
                $html .= '</a>';
                $html .= '<div class="mr-3">';
                $html .= '<a href="javascript:;" data-rowdata="<?php echo $rowJson; ?>" onclick="drilldownLink_intranet_<?php echo $this->uniqId ?>(this)" class="text-black">';
                $html .= '<h6>' . issetVar($row['description']) . '</h6>';
                $html .= '</a>';
                $html .= '<small>' . issetVar($row['positionname']) . ' ' . issetVar($row['name']) . '</small>';
                $html .= '<p class="text-justify mt-1 mb-0 forum-body-text">' . (issetParam($row['body']) ? Str::htmltotext($row['body']) : '') . '</p>';
                $html .= '</div>';
                $html .= '<div class="ml-auto tx-right">';

                $personname = explode(',', issetVar($row['commentpersonname']));
                $personpic = explode(',', issetVar($row['commentpicture']));

                if ($personname) {
                    $html .= '<div class="avatar-group justify-content-end">';
                    foreach ($personname as $key => $pname) {
                        if ($key < 4) {
                            $html .= '<a href="javascript:;">';
                            $html .= '<div class="avatar avatar-xs">';
                            $html .= '<img src="' . (issetParam($personpic[$key]) ? $personpic[$key] : '') . '" onerror="onUserImageError(this);" class="rounded-circle" alt="">';
                            $html .= '</div>';
                            $html .= '</a>';
                        }
                    }
                    $html .= '</div>';
                    $html .= '<a href="javascript:void(0);" class="text-nowrap"><small class="mt-1">' . count($personname) . ' сэтгэгдэл</small></a>';
                }

                $html .= '</div>';
                $html .= '</li>';

                $heleltsuuleg[issetVar($row['id'])] = $html;
            }
        }

        return array(
            'shuurhai_arr' => $shuurhai_arr,
            'news_arr' => $news_arr,
            'hr_arr' => $hr_arr,
            'task_arr' => $task_arr,
            'heleltsuuleg' => $heleltsuuleg,
            'pos14' => $pos14,
            'pos06' => $pos06,
            'pos12' => $pos12,
            'pos05' => $pos05,
            'pos08' => $pos08,
            'pos_11' => $dashboardArr['pos_11']
        );
    }

    public function getcalendarDataModel() {

        parse_str(urldecode(Input::post('defaultCriteriaData')), $defaultCriteriaData);

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
            $operator = ($defaultCondition == '0') ? $defaultCriteriaCondition : (isset($defaultCriteriaCondition[$defParam]) ? $defaultCriteriaCondition[$defParam] : 'like');
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
                        if ($paramVal != '') {
                            $paramDefaultCriteria[$fieldLower][] = array(
                                'operator' => $operator,
                                'operand' => $paramVal
                            );
                        }
                    }
                }
            } else {

                $defParamVal = Input::param(trim($defParamVal));
                $defParamVal = Mdmetadata::setDefaultValue($defParamVal);

                $paramDefaultCriteria[$fieldLower][] = array(
                    'operator' => $operator,
                    'operand' => ($defParamVal) ? $defParamVal : '0'
                );
            }
        }

        if (isset($param['criteria'])) {
            $param = array_merge($param, $paramDefaultCriteria);
        } else {
            $param = $paramDefaultCriteria;
        }

        $calData = $this->fncRunDataview(Input::post('metaDataId'), "", "", '', $param, '', '', '1', false);
        $holidayData = $this->fncRunDataview('1457687030107', "", "", '', $param, '', '', '0', false);

        (Array) $response = array();
        $cdataA = Arr::groupByArrayOnlyRows($calData, 'ispin');

        $response['total'] = isset($cdataA['0']) ? sizeof($cdataA['0']) : '0';

        foreach ($holidayData as $row) {

            $days = Date::diffDays($row['enddate'], $row['startdate']);
            if (0 < $days) {
                for ($index = 0; $index <= $days; $index++) {
                    $enddate = Date::nextDate($row['startdate'], $index);
                    $arr = array(
                        'id' => getUID(),
                        'name' => $row['holidayname'],
                        'confname' => '',
                        'startdate' => $enddate,
                        'stime' => '00:01',
                        'etime' => '23:59',
                        'start' => $enddate . 'T00:00:01',
                        'end' => $enddate . 'T23:59:59',
                        'starttime' => '',
                        'color' => NULL,
                        'statuscolor' => '#f1f1f1',
                        'desc' => NULL,
                        'createdusername' => '',
                        'icon' => '',
                        'wfmstatusid' => NULL,
                        'wfmdescription' => NULL,
                        'templateid' => '',
                        'createddate' => '',
                        'tooltiptext' => NULL,
                        'conftime' => '',
                        'wfmstatusname' => NULL,
                        'wfmstatuscolor' => NULL,
                        'showapprovebutton' => '0',
                        'showcancelbutton' => '0',
                        'showeditbutton' => '0',
                        'ispin' => '0',
                        'isholiday' => '1',
                        'wfmstatuscode' => NULL,
                    );

                    array_push($calData, $arr);
                }
            } else {
                $arr = array(
                    'id' => getUID(),
                    'name' => $row['holidayname'],
                    'confname' => '',
                    'startdate' => $row['startdate'],
                    'stime' => '00:01',
                    'etime' => '23:59',
                    'start' => $row['startdate'] . 'T00:00:01',
                    'end' => $row['enddate'] . 'T23:59:59',
                    'starttime' => '',
                    'color' => NULL,
                    'statuscolor' => '#f1f1f1',
                    'desc' => NULL,
                    'createdusername' => '',
                    'icon' => '',
                    'wfmstatusid' => NULL,
                    'wfmdescription' => NULL,
                    'templateid' => '',
                    'createddate' => '',
                    'tooltiptext' => NULL,
                    'conftime' => '',
                    'wfmstatusname' => NULL,
                    'wfmstatuscolor' => NULL,
                    'showapprovebutton' => '0',
                    'showcancelbutton' => '0',
                    'showeditbutton' => '0',
                    'ispin' => '0',
                    'isholiday' => '1',
                    'wfmstatuscode' => NULL,
                );

                array_push($calData, $arr);
            }
        }

        $response['rows'] = $calData;
        return $response;
    }

    public function getCreatedGroupsModel() {

        $userId = Ue::sessionUserId();

        $sql = "SELECT 
                GM.ID, 
                GM.GROUP_NAME, 
                GM.COVER_PICTURE 
            FROM SCL_GROUPS GM 
            WHERE GM.CREATED_USER_ID = $userId 
            ORDER BY GM.CREATED_DATE DESC";

        $data = $this->db->GetAll($sql);

        return $data;
    }

    public function getJoinedGroupsModel() {

        $userId = Ue::sessionUserId();

        $sql = "SELECT 
                SG.ID, 
                SG.GROUP_NAME, 
                SG.COVER_PICTURE 
            FROM SCL_GROUP_MEMBERS GM 
                INNER JOIN SCL_GROUPS SG ON SG.ID = GM.GROUP_ID 
            WHERE GM.USER_ID = $userId 
            ORDER BY GM.CREATED_DATE DESC";

        $data = $this->db->GetAll($sql);

        return $data;
    }

    public function getSocialActiveUsersModel($limit = 10) {

        $data = $this->db->GetAll("
            SELECT 
                EMP.LAST_NAME, 
                EMP.FIRST_NAME, 
                EMP.PICTURE 
            FROM 
            ( 
                SELECT 
                    SYSTEM_USER_ID 
                FROM UM_USER_SESSION 
                WHERE UPDATED_DATE > SYSDATE - (30/1440) 
                GROUP BY 
                    SYSTEM_USER_ID
            ) US 
                INNER JOIN UM_SYSTEM_USER UM ON UM.USER_ID = US.SYSTEM_USER_ID 
                INNER JOIN VW_EMPLOYEE EMP ON EMP.PERSON_ID = UM.PERSON_ID 
            GROUP BY 
                EMP.LAST_NAME, 
                EMP.FIRST_NAME,
                EMP.PICTURE");

        return $data;
    }

    public function saveSinglePostModel() {
        includeLib('Utils/Functions');

        $id = Input::post('id');

        (Array) $result = $intPostUserDepartmentDv = $intPostUserDv = $mmContentMapDv = array();

        $postData = Input::postData();
        $fileData = Input::fileData();
        $currentDate = Date::currentDate();
        $sessionUserKeyId = Ue::sessionUserKeyId();
        $sessionUserId = Ue::sessionUserId();

        $body = Input::postNonTags('description');
        $getVideoId = null;

        try {

            $file_path = self::bpUploadCustomPath('/social/posts/files/');

            if (isset($fileData['postFile'])) {

                $file_arr = Arr::arrayFiles($fileData['postFile']);
                $fileData = issetParamArray($fileData['postFile']['name']);

                if ($fileData) {
                    foreach ($fileData as $f => $file) {
                        if (isset($file_arr[$f]['name']) && $file_arr[$f]['name'] != '' && $file_arr[$f]['size'] != null) {

                            $newFileName = 'file_' . getUID() . $f;
                            $fileExtension = strtolower(substr($file_arr[$f]['name'], strrpos($file_arr[$f]['name'], '.') + 1));
                            $fileName = $newFileName . '.' . $fileExtension;

                            if (in_array($fileExtension, array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf', 'zip', 'rar'))) {

                                if (in_array($fileExtension, array('jpg', 'jpeg', 'png', 'gif', 'bmp'))) {


                                    $file_path = self::bpUploadCustomPath1('social/posts/images/original/');
                                    $fileThumbspath = self::bpUploadCustomPath1('social/posts/images/thumb/');

                                    $fileAttr['name'] = $file_arr[$f]['name'];
                                    $fileAttr['tmp_name'] = $file_arr[$f]['tmp_name'];
                                    $fileAttr['size'] = $file_arr[$f]['size'];
                                    $fileAttr['type'] = $file_arr[$f]['type'];

                                    Upload::$File = $fileAttr;
                                    Upload::$method = 0;
                                    Upload::$SavePath = $file_path;
                                    Upload::$ThumbPath = $fileThumbspath;
                                    Upload::$NewWidth = 2000;
                                    Upload::$NewName = $newFileName;
                                    Upload::$OverWrite = true;
                                    Upload::$CheckOnlyWidth = true;
                                    $uploadError = Upload::UploadFile();

                                    if ($uploadError == '') {
                                        $tempparam = array(
                                            'refStructureId' => NULL,
                                            'INT_CONTENT_DV' =>
                                            array(
                                                'fileName' => ((empty($file)) ? $file_arr[$f]['name'] : $file),
                                                'fileSize' => $file_arr[$f]['size'],
                                                'fileExtension' => $fileExtension,
                                                'physicalPath' => $file_path . $fileName,
                                                'thumbPhysicalPath' => $fileThumbspath . $fileName,
                                                'id' => NULL,
                                                'createdDate' => $currentDate,
                                                'createdUserId' => $sessionUserKeyId,
                                            ),
                                            'id' => NULL,
                                            'recordId' => NULL,
                                            'contentId' => NULL,
                                        );

                                        array_push($mmContentMapDv, $tempparam);
                                    }
                                } else {

                                    FileUpload::SetFileName($fileName);
                                    FileUpload::SetTempName($file_arr[$f]['tmp_name']);
                                    FileUpload::SetUploadDirectory($file_path);
                                    FileUpload::SetValidExtensions(explode(',', Config::getFromCache('CONFIG_FILE_EXT')));
                                    FileUpload::SetMaximumFileSize(FileUpload::GetConfigFileMaxSize()); //10mb
                                    $uploadResult = FileUpload::UploadFile();

                                    if ($uploadResult) {

                                        $tempparam = array(
                                            'refStructureId' => NULL,
                                            'INT_CONTENT_DV' =>
                                            array(
                                                'fileName' => ((empty($file)) ? $file_arr[$f]['name'] : $file),
                                                'fileSize' => $file_arr[$f]['size'],
                                                'fileExtension' => $fileExtension,
                                                'physicalPath' => $file_path . $fileName,
                                                'thumbPhysicalPath' => $file_path . $fileName,
                                                'id' => NULL,
                                                'createdDate' => $currentDate,
                                                'createdUserId' => $sessionUserKeyId,
                                            ),
                                            'id' => NULL,
                                            'recordId' => NULL,
                                            'contentId' => NULL,
                                        );

                                        array_push($mmContentMapDv, $tempparam);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if (isset($fileData['post_images_upload'])) {

                $file_arr = Arr::arrayFiles($fileData['post_images_upload']);
                $fileData = issetParamArray($fileData['post_images_upload']['name']);

                if ($fileData) {

                    foreach ($fileData as $f => $file) {

                        if (isset($file_arr[$f]['name']) && $file_arr[$f]['name'] != '' && $file_arr[$f]['size'] != null) {

                            $newFileName = 'file_' . getUID() . $f;
                            $fileExtension = strtolower(substr($file_arr[$f]['name'], strrpos($file_arr[$f]['name'], '.') + 1));
                            $fileName = $newFileName . '.' . $fileExtension;
                            if (in_array($fileExtension, array('jpg', 'jpeg', 'png', 'gif', 'bmp'))) {
                                $file_path = self::bpUploadCustomPath1('social/posts/images/original/');
                                $fileThumbspath = self::bpUploadCustomPath1('social/posts/images/thumb/');

                                $fileAttr['name'] = $file_arr[$f]['name'];
                                $fileAttr['tmp_name'] = $file_arr[$f]['tmp_name'];
                                $fileAttr['size'] = $file_arr[$f]['size'];
                                $fileAttr['type'] = $file_arr[$f]['type'];

                                Upload::$File = $fileAttr;
                                Upload::$method = 0;
                                Upload::$SavePath = $file_path;
                                Upload::$ThumbPath = $fileThumbspath;
                                Upload::$NewWidth = 2000;
                                Upload::$NewName = $newFileName;
                                Upload::$OverWrite = true;
                                Upload::$CheckOnlyWidth = true;
                                $uploadError = Upload::UploadFile();

                                if ($uploadError == '') {
                                    $tempparam = array(
                                        'refStructureId' => NULL,
                                        'INT_CONTENT_DV' =>
                                        array(
                                            'fileName' => ((empty($file)) ? $file_arr[$f]['name'] : $file),
                                            'fileSize' => $file_arr[$f]['size'],
                                            'fileExtension' => $fileExtension,
                                            'physicalPath' => $file_path . $fileName,
                                            'thumbPhysicalPath' => $fileThumbspath . $fileName,
                                            'id' => NULL,
                                            'createdDate' => $currentDate,
                                            'createdUserId' => $sessionUserKeyId,
                                        ),
                                        'id' => NULL,
                                        'recordId' => NULL,
                                        'contentId' => NULL,
                                    );

                                    array_push($mmContentMapDv, $tempparam);
                                }
                            }
                        }
                    }
                }
            }

            if (Input::isEmpty('youtubeText') == false) {
                $getVideoId = getYoutubeVideoID(Input::post('youtubeText'));
            }

            $params = array(
                'userId' => $sessionUserId,
                'groupId' => $postData['group_id'],
                'description' => Str::utf8_substr($body, 0, 20),
                'isActive' => '1',
                'longDescr' => $body,
                'isComment' => '1',
                'isUrgent' => '0',
                'isLike' => '1',
                'isLongDescr' => '',
                'isPin' => '0',
                'categoryId' => $postData['category_id'],
                'typeId' => $postData['type_id'],
                'INT_POST_GROUPS_DV' => array(),
                'INT_POST_GROUPS_DEPARTMENT_DV' => array(),
                'INT_POST_USER_DV' => $intPostUserDv,
                'INT_POST_USER_DEPARTMENT_DV' => $intPostUserDepartmentDv,
                'INT_CONTENT_MAP_DV' => $mmContentMapDv,
                'INT_POLL_QUESTION_DV' => array(),
                'id' => NULL,
                'youtubeId' => issetParam($getVideoId),
                'soundcloudId' => NULL,
                'locationStr' => NULL,
                'privacyType' => 'public',
                'createdDate' => $currentDate,
                'updatedDate' => NULL,
                'deletedDate' => NULL,
                'imgUrl' => NULL,
                'viewCnt' => NULL,
                'likeCnt' => NULL,
                'commentCnt' => NULL,
                'viewPercent' => NULL,
                'userIds' => NULL,
                'groupIds' => NULL,
                'departmentIds' => NULL,
                'answerTypeId' => NULL,
                'endDate' => NULL,
                'pinDate' => NULL,
            );

            $result = Functions::runProcess('NTR_PUBLIC_POSTS_DV_001', $params);

            if ($result['status'] === 'success') {
                $paramArr = array('pageNumber' => '1', 'pageRows' => '20', 'id' => $result['result']['id']);
                if (issetParam($postData['category_id']) == '9988') {
                    $result['result']['groupid'] = $postData['group_id'];
                }

                $result['text'] = Lang::line('msg_post_success');
            }
        } catch (Exception $ex) {
            (Array) $result = array();

            $result['status'] = 'warning';
            $result['text'] = $ex->getMessage();
        }

        return $result;
    }

    public function getPostsCommentByPostIdModel($postId) {

        $data = $this->db->GetAll("
            SELECT 
                PC.ID, 
                PC.COMMENT_TXT, 
                PC.CREATED_DATE, 
                SU.USER_ID,  
                BP.LAST_NAME, 
                BP.FIRST_NAME, 
                EMP.PICTURE 
            FROM SCL_POSTS_COMMENT PC 
                INNER JOIN UM_SYSTEM_USER SU ON SU.USER_ID = PC.USER_ID 
                INNER JOIN BASE_PERSON BP ON BP.PERSON_ID = SU.PERSON_ID 
                LEFT JOIN HRM_EMPLOYEE EMP ON EMP.PERSON_ID = SU.PERSON_ID 
            WHERE PC.POST_ID = $postId     
            ORDER BY PC.CREATED_DATE ASC");

        return $data;
    }

    public function lawInsertData($law) {
        $bpResult = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'LAW_GENERAL_DV_001', $law);
        return $bpResult;
    }

    function prepLawData($arr, $resArr, $lvl, $lawId, $parentId) {
        foreach ($arr as $key => $value) {
            $id = getUID();
            if (!isset($resArr['level_' . $lvl])) {
                $resArr['level_' . $lvl] = array();
            }
            array_push($resArr['level_' . $lvl],
                    array('id' => $id,
                        'parentId' => $parentId,
                        'numbering' => isset($value['num']) ? $value['num'] : '',
                        'type' => $lvl,
                        'lawid' => $lawId,
                        'text' => $value['text']));

            if (isset($value['children'])) {
                $resArr = self::prepLawData($value['children'], $resArr, $lvl + 1, $lawId, $id);
            }
        }
        return $resArr;
    }

    public function getLawDataModel($id) {
        $inparams = array('id' => $id);
        $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, "LAW_GENERAL_DV_004", $inparams);
        return $result;
    }

    public function saveAdditionalModel() {
        $id = Input::post('id');
        $editorData = Input::post('editorData');
        $inparams = array('id' => $id, 'addition' => $editorData);
        $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, "LAW_GENERAL_DATA_DV_SUB_002", $inparams);
        return $result;
    }

    public function moveLawTree() {
        $id = Input::post('id');
        $parent = Input::post('parent');
        $param = array(
            'PARENT_ID' => $parent
        );
        $result = $this->db->AutoExecute('LAW_GENERAL_DATA', $param, "UPDATE", "ID = '$id'");
        return $result;
    }

    public function renameLawTree() {
        $id = Input::post('id');
        $text = Input::post('text');
        $param = array(
            'TEXT' => $text
        );
        $result = $this->db->AutoExecute('LAW_GENERAL_DATA', $param, "UPDATE", "ID = '$id'");
        return $result;
    }

    public function createLawTree() {
        $id = getUniqId();
        $data = array(
            'ID' => $id,
            'NUMBERING' => '',
            'TEXT' => '',
            'TYPE' => '',
            'PARENT_ID' => '',
            'LAW_ID' => ''
        );
        $result = $this->db->AutoExecute('LAW_GENERAL_DATA', $data, 'INSERT');
        return $result;
    }

    public function deleteLawTree() {
        $id = Input::post('id');
        $result = $this->db->Execute("DELETE FROM LAW_GENERAL_DATA WHERE ID = $id");
        return $result;
    }

    public function getNotariesModel() {
        $postData = Input::postData();
        $html = '';
        if ($postData['notariassearch'] == 1) {
            (Array) $tempArrs = array();
            $param = array(
                'systemMetaGroupId' => '1584011654594693',
                'showQuery' => '0',
                'ignorePermission' => 1
            );

            if ($postData['departmentid']) {
                $tempArr = array(
                    'departmentid' => array(
                        array(
                            'operator' => '=',
                            'operand' => Input::post('departmentid'),
                        )
                    )
                );
                if (isset($param['criteria'])) {
                    $param['criteria'] = array_merge($param['criteria'], $tempArr);
                } else {
                    $param['criteria'] = $tempArr;
                }
            }

            if ($postData['schedule']) {
                $tempArr = array(
                    'weekId' => array(
                        array(
                            'operator' => '=',
                            'operand' => Input::post('schedule'),
                        )
                    )
                );

                if (isset($param['criteria'])) {
                    $param['criteria'] = array_merge($param['criteria'], $tempArr);
                } else {
                    $param['criteria'] = $tempArr;
                }
            }
            $data = $this->ws->run('serialize', self::$getDataViewCommand, $param, self::$gfServiceAddress);

            if (isset($data['result']) && $data['result']) {

                unset($data['result']['paging']);
                unset($data['result']['aggregatecolumns']);

                $response['rows'] = isset($data['result']) ? $data['result'] : array();


                if (isset($response['rows']) && $response['rows']) {
                    foreach ($response['rows'] as $notarias) {
                        $html .= '<tr data-id="2533735724792792">';
                        $html .= '<td style="width: 350px;">';
                        $html .= '<div class="d-flex align-items-center">';
                        $html .= '<div class="mr-2">';
                        $html .= '<a href="javascript:void(0);">';
                        $html .= '<img src="' . $notarias['officeimage'] . '" class="rounded-circle" width="34" height="34" alt="" onerror="onUserImageError(this);" data-userid="2533735724792792" data-hasqtip="0">';
                        $html .= '</a>';
                        $html .= '</div>';
                        $html .= '<div>';
                        $html .= '<a href="javascript:void(0);" class="text-blue font-weight-bold">' . $notarias['fullname'] . '</a>';
                        $html .= '<div class="text-muted font-size-sm">';
                        $html .= '<div class="line-height-normal font-size-11">' . $notarias['positionname'] . '</div>';
                        $html .= '<span class="line-height-normal font-size-11">' . $notarias['departmentname'] . '</span>';
                        $html .= '</div>';
                        $html .= '</div>';
                        $html .= '</div>';
                        $html .= '</td>';
                        $html .= '<td><div class="text-black" style="width: 80px;">' . $notarias['employeecode'] . '</div></td>';
                        $html .= '<td><div class="text-black" style="width: 200px;">' . $notarias['employeeaddress'] . '</div></td>';
                        $html .= '<td><div class="text-black" style="width: 80px;">' . $notarias['employeephone'] . '</div></td>';
                        $html .= '<td><div class="text-black" style="width: 80px;">' . $notarias['schedule'] . '</div></td>';
                        $html .= '</tr>';
                    }
                }
            }
            return $html;
        }
    }

    public function getLikePeopleByPostIdModel() {

        $postId = Input::post('postId');
        $andWhere = "AND (SCL.COMMENT_ID IS NULL OR SCL.COMMENT_ID = 0)";

        if (Input::post('commentId')) {
            $andWhere = "AND SCL.COMMENT_ID = '" . Input::post('commentId') . "'";
        }

        $data = $this->db->GetAll("
            SELECT 
                SCL.USER_ID,  
                BP.LAST_NAME, 
                BP.FIRST_NAME, 
                EMP.PICTURE, 
                SCL.CREATED_DATE
            FROM SCL_LIKE SCL 
                INNER JOIN UM_SYSTEM_USER SU ON SU.USER_ID = SCL.USER_ID 
                INNER JOIN BASE_PERSON BP ON BP.PERSON_ID = SU.PERSON_ID 
                LEFT JOIN HRM_EMPLOYEE EMP ON EMP.PERSON_ID = SU.PERSON_ID 
            WHERE SCL.POST_ID = $postId  $andWhere
            ORDER BY SCL.CREATED_DATE DESC");

        return $data;
    }

    public function sendRequestGroupModel() {

        try {

            $param = array(
                'groupId' => Input::post('groupId'),
                'userId' => Ue::sessionUserId(),
                'membershipTypeId' => '1587439066158',
                'isApproved' => NULL,
                'approvedDate' => NULL,
                'id' => NULL,
                'departmentId' => NULL,
            );

            includeLib('Utils/Functions');
            $result = Functions::runProcess('NTR_SOCIAL_GROUP_MEMBERS_DV_001', $param);

            if ($result['status'] === 'success') {
                $result['text'] = Lang::line('msg_send_success');
            } else {
                $result['text'] = Lang::line('msg_request_pending');
            }
        } catch (Exception $ex) {
            (Array) $result = array();

            $result['status'] = 'warning';
            $result['text'] = $ex->getMessage();
        }

        return $result;
    }

    public function requestActionGroupModel() {
        $type = Input::post('type');
        $mainId = Input::post('mainId');
        $where = issetParam($type) === '2' ? "GROUP_ID = '" . Input::post('mainId') . "' AND USER_ID = '" . Ue::sessionUserId() . "'" : "ID = '" . Input::post('mainId') . "'";
        $where = issetParam($type) === '3' ? "ID = '" . Input::post('mainId') . "'" : $where;

        try {
            if (issetParam($type) === '2' || issetParam($type) === '3') {
                $this->db->Execute("DELETE FROM SCL_GROUP_MEMBERS WHERE $where");
            } else {
                $this->db->AutoExecute("SCL_GROUP_MEMBERS", array('is_approved' => $type, 'MEMBERSHIP_TYPE_ID' => '1587439066158'), "UPDATE", $where);
            }

            $result['text'] = Lang::line('msg_save_success');
            $result['status'] = 'success';
        } catch (Exception $ex) {
            (Array) $result = array();

            $result['status'] = 'warning';
            $result['text'] = $ex->getMessage();
        }

        return $result;
    }

    public function generatecontentModel($saveCache, $data, $isHtml = false) {
        
        $newHtmlFileName = getUID();
        $html = strip_tags($saveCache, "<title><table><thead><tbody><tfood><tr><th><td><p><img>");
        $html = explode('</title>', $html);
        $html = (isset($html[1]) ? $html[1] : $html[0]);
        
        $temp_arr = preg_split('/(<[^>]*[^\/]>)/i', $html, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        
        (Int) $tcounter = 0;
        (String) $htmlcontent = $table = '';
        (Array) $law_arr = array();
        foreach ($temp_arr as $key => $row) {
            if (strpos($row, '<table') !== false) {
                $htmlcontent .= $row;
                $tcounter ++;
            }
            
            if (strpos($row, '/table') !== false) {
                $htmlcontent .= $row;
                $tcounter--;
            }
            
            if ($tcounter !== 0) {
                $htmlcontent .= $row;
            } else {
                if (!$htmlcontent) {
                    $htmlcontent = strpos($row, '<img') !== false ? $row : Str::sanitize($row);
                }
                
                if ($htmlcontent) {
                    array_push($law_arr, $htmlcontent);
                }
                
                $htmlcontent = '';
            }
        }
             
        $i = 0;
        $z = 0;
        $tablaa = '';
        
        $addinclass = 'editonly noedit';
        
        $tab1 = '<section style="text-indent:.5in;padding-left: 5rem;text-indent: unset"  class="drag-drop-law '. $addinclass .'">' . $tablaa . '%s</section>';
        $tab2 = '<section style="text-indent:1.0in;padding-left: 6rem;text-indent: unset" class="drag-drop-law '. $addinclass .'">' . $tablaa . $tablaa . '%s</section>';
        $tab3 = '<section style="text-indent:1.5in;padding-left: 7rem;text-indent: unset" class="drag-drop-law '. $addinclass .'">' . $tablaa . $tablaa . $tablaa . '%s</section>';
        
        (Array) $law = array();
        (Array) $tempArray = array();
        
        foreach ($law_arr as $lkey => $law_mor) {
            (String) $datapath = "";
            array_pop($law_arr);

            if (mb_strlen($law_mor) > 2 && !empty($law_mor) && strpos($law_mor, '<img') === false) {

                $firstnum = abs($law_mor);
                if (preg_match('/р зүйл[.]/i', $law_mor)) {
                    $i = $i + 1;
                    $law['zuil_' . $i]['name'] = $law_mor . '<br/><section></section>';
                    $law['zuil_' . $i]['paragraphid'] = $lkey;
                } else if (preg_match('/Р БҮЛЭГ/i', $law_mor)) {
                    $i = $i + 1;
                    $law['buleg_' . $i]['name'] = '<br/><br/>' . $law_mor . '<br/><section></section>';
                    $law['buleg_' . $i]['paragraphid'] = $lkey;
                } else if (preg_match('/АНГИ /i', $law_mor)) {
                    $i = $i + 1;
                    $law['angi_' . $i]['name'] = $law_mor . '<br/><section></section>';
                    $law['angi_' . $i]['paragraphid'] = $lkey;
                } else if (preg_match('/ХЭСЭГ /i', $law_mor)) {
                    $i = $i + 1;
                    $law['part_' . $i]['name'] = $law_mor . '<br/><section></section>';
                    $law['part_' . $i]['paragraphid'] = $lkey;
                } else if (preg_match('/ДЭД БҮЛЭГ/i', $law_mor) || preg_match('/дэд бүлэг/i', $law_mor)) {
                    $i = $i + 1;
                    $law['subpart_' . $i]['name'] = $law_mor . '<br/><section></section>';
                    $law['subpart_' . $i]['paragraphid'] = $lkey;
                } else if (preg_match('/^МОНГОЛ УЛСЫН ИХ ХУРЛЫН ДАРГА/i', $law_mor) || preg_match('/^МОНГОЛ УЛСЫН ИХ ХУРЛЫН ДЭД ДАРГА/i',
                                $law_mor) || preg_match('/БАГА ХУРЛЫН ДАРГА/i', $law_mor) || preg_match('/ХУРЛЫН НАРИЙН БИЧГИЙН ДАРГА/i',
                                $law_mor) || preg_match('/ХУРЛЫН ТАМГЫН ГАЗРЫН ЕРӨНХИЙ НАРИЙН БИЧГИЙН ДАРГА/i',
                                $law_mor) || preg_match('/ХУРЛЫН ТАМГЫН ГАЗРЫН НАРИЙН БИЧГИЙН ДАРГА/i',
                                $law_mor) || preg_match('/ХУРЛЫН ТЭРГҮҮЛЭГЧДИЙН ДАРГА/i', $law_mor)) {
                    $i = $i + 1;
                    $law_mor = str_replace("ДАРГА",
                            "ДАРГА&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
                            $law_mor);
                    $law['footer' . $i] = $law_mor . '<br/>';
                } else if ((preg_match('/\d{4}\sоны\s\d{1,2}\s(дүгээр|дугаар)\sсарын\s\d{1,2}[-](ны|ний)\sөдөр\s(Улаанбаатар хот|Төрийн ордон(.|,) Улаанбаатар хот)/i',
                                $law_mor) || preg_match('/^\d{4}\sоны\s\d{1,2}\sсарын\s\d{1,2}\sөдөр\s(Улаанбаатар хот|Төрийн ордон(.|,) Улаанбаатар хот)$/i',
                                $law_mor))) {

                    $lawrow1 = $lawrow2 = $lawrow3 = $lawrow4 = $law_mor;
                    preg_match('/\d{4}\sоны\s\d{1,2}\s(дүгээр|дугаар)\sсарын\s\d{1,2}[-](ны|ний)\sөдөр/i',
                            $lawrow1, $match1);
                    preg_match('/\d{4}\sоны\s\d{1,2}\sсарын\s\d{1,2}\sөдөр/i', $lawrow2, $match2);
                    preg_match('/Улаанбаатар хот/i', $lawrow3, $match3);
                    preg_match('/Төрийн ордон(.|,) Улаанбаатар хот/i', $lawrow4, $match4);

                    $i = $i + 1;
                    if (!empty($match1[0])) {
                        $law['date' . $i] = $match1[0] . '<br/>';
                    } else {
                        $law['date' . $i] = $match2[0] . '<br/>';
                    }
                    if (!empty($match4[0])) {
                        $law['position' . ($i + 1)] = $match4[0] . '<br/>';
                    } else {
                        $law['position' . ($i + 1)] = $match3[0] . '<br/>';
                    }
                } else if ((preg_match('/\d{4}\sоны\s\d{1,2}\s(дүгээр|дугаар)\sсарын\s\d{1,2}[-](ны|ний)\sөдөр\sДугаар\s\d{1,2}\s(Улаанбаатар хот|Төрийн ордон(.|,) Улаанбаатар хот)/i',
                                $law_mor) || preg_match('/^\d{4}\sоны\s\d{1,2}\sсарын\s\d{1,2}\sөдөр\sДугаар\s\d{1,2}\s(Улаанбаатар хот|Төрийн ордон(.|,) Улаанбаатар хот)$/i',
                                $law_mor))) {

                    $lawrow1 = $lawrow2 = $lawrow3 = $lawrow4 = $lawrow5 = $law_mor;
                    preg_match('/\d{4}\sоны\s\d{1,2}\s(дүгээр|дугаар)\sсарын\s\d{1,2}[-](ны|ний)\sөдөр/i',
                            $lawrow1, $match1);
                    preg_match('/\d{4}\sоны\s\d{1,2}\sсарын\s\d{1,2}\sөдөр/i', $lawrow2, $match2);
                    preg_match('/Улаанбаатар хот/i', $lawrow3, $match3);
                    preg_match('/Төрийн ордон(.|,) Улаанбаатар хот/i', $lawrow4, $match4);
                    preg_match('/Дугаар\s\d{1,2}/i', $lawrow5, $match5);

                    $i = $i + 1;
                    if (!empty($match1[0])) {
                        $law['date' . $i] = $match1[0] . '<br/>';
                    } else {
                        $law['date' . $i] = $match2[0] . '<br/>';
                    }

                    if (!empty($match5[0])) {
                        $law['number' . $i] = $match5[0] . '<br/>';
                    } else {
                        $law['number' . $i] = '';
                    }

                    if (!empty($match4[0])) {
                        $law['position' . ($i + 1)] = $match4[0] . '<br/>';
                    } else {
                        $law['position' . ($i + 1)] = $match3[0] . '<br/>';
                    }
                } else if (preg_match('/\d{4}\sоны\s\d{1,2}\s(дүгээр|дугаар)\sсарын\s\d{1,2}[-](ны|ний)\sөдөр/i',
                                $law_mor) || preg_match('/^\d{4}\sоны\s\d{1,2}\sсарын\s\d{1,2}\sөдөр$/i',
                                $law_mor)) {
                    if ($z == 1 || $z == 2) {
                        $i = $i + 1;
                        $law['date' . $i] = $law_mor . '<br/>';
                    } else {
                        $i = $i;
                        if (isset($law[$i])) {
                            $law[$i] .= $law_mor;
                        } else {

                        }
                    }
                } else if (preg_match('/Улаанбаатар хот/i', $law_mor) || preg_match('/Төрийн ордон(.|,) Улаанбаатар хот/i',
                                $law_mor)) {
                    if ($z == 2 || $z == 3) {
                        $i = $i + 1;
                        $law['position' . $i] = $law_mor . '<br/>';
                    } else {
                        $i = $i;
                        if (isset($law[$i])) {
                            $law[$i] .= $law_mor;
                        } else {

                        }

                    }
                } else if (preg_match('/ХУУЛЬ$/i', $law_mor)) {
                    $i = $i + 1;
                    $law['cccc_' . $i] = $law_mor . '<br/>';
                } else {
                    if (strpos($law_mor, '<table') === false) {
                        if (preg_match('/\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]/i', $law_mor)) {
                            $law_mor = str_replace("%s", $law_mor, $tab3);
                        } else if (preg_match('/\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]/i', $law_mor)) {
                            $law_mor = str_replace("%s", $law_mor, $tab2);
                        } else if (preg_match('/\d{1,3}[.]\d{1,3}[.]/i', $law_mor)) {
                            $law_mor = str_replace("%s", $law_mor, $tab1);
                        } else if (preg_match('/\d{1,3}(\/)/i', $law_mor)) {
                            $law_mor = str_replace("%s", $law_mor, $tab2);
                        } else if (preg_match('/\d{1,3}[.]/i', $law_mor)) {
                            $law_mor = str_replace("%s", $law_mor, $tab1);
                        } else if (preg_match('/^<[a]/i', $law_mor) || preg_match('/^\[\d\]/i', $law_mor)) {
                            $law_mor = $law_mor . '<br/>';
                        } else if (preg_match('/^<ol>/i', $law_mor)) {
                            $law_mor = $law_mor . '<br/>';
                        } else if (($z < 10 || $z == 0) && empty($law['zuil_' . $i]['name'])) {
                            $law_mor = '<section '. $datapath .' style="text-align:center;font-weight:bold" class="drag-drop-law '. $addinclass .'">' . $law_mor . '</section>';
                        }
                    }

                    if (isset($law[$i])) {
                        if (strpos($law_mor, 'table') !== false && strpos($law_mor, 'addition-changebookfull') === false) {
                            $law[$i] .= '<section '. $datapath .' class="drag-drop-law-table '. $addinclass .'">' . $law_mor . '</section>';
                        } else {
                            if (strpos($law_mor, 'drag-drop-law') === false ) {
                                $law[$i] .= '<section '. $datapath .' style="text-indent:.5in;padding-left: 5rem;text-indent: unset;" class="drag-drop-law '. $addinclass . '">' . $law_mor . '</section>';
                            } else {
                                $law[$i] .= $law_mor;
                            }
                        }
                    } else {
                        if (preg_match('/\d{1,3}[.]\d{1,3}[.]/i', $law_mor) || preg_match('/\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]/i',
                                        $law_mor) || preg_match('/\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]/i',
                                        $law_mor)) {
                            $law[$i] = $law_mor;
                        } else if (is_html($law_mor)) {
                            $law[$i] = $law_mor;
                        } else {
                            $law[$i] = $law_mor . '<br/>';
                        }
                    }
                }

            } elseif(mb_strlen($law_mor) > 2 && !empty($law_mor) && strpos($law_mor, '<img') !== false) {
                if (!isset($law[$i])) {
                    $law[$i] = '';
                }

                $law[$i] .= '<section '. $datapath .' style="text-align:center;font-weight:bold" class="drag-drop-law '. $addinclass .'">' . $law_mor . '</section>';
            }

            $z++;
        }
        
        $old_char = array('<sup>', '</sup>');
        $new_char = array('@a', '@b',);

        $html_result = '';
        
        for ($y = 0; $y <= sizeof($law); $y++) {
            $datapath = "";
            
            if (isset($law['zuil_' . $y]) && !empty($law['zuil_' . $y])) {
                $html_result .= '<section id="list_item_' . $y . '" '. $datapath .' class="__drag-drop-law">';
                    $html_result .= '<div class="__editLaw pull-left">';
                        $html_result .='<section class="msg_head opened_head '. $addinclass .'">';
                            $html_result .= '<label>' . str_replace($new_char, $old_char, $law['zuil_' . $y]['name']) . '</label>';
                        $html_result .= '</section>';
                        $html_result .= '<div class="msg_body" style="text-align: justify">';
                            $html_result .= str_replace($new_char, $old_char, @$law[$y]);
                        $html_result .= '</div>'; 
                    $html_result .= '</div>';
                    $html_result .= '<a href="javascript:;" class="editlaw">Засах</a>';
                    $html_result .= '<div class="clear"></div>';
                $html_result .= '</section>';
            } 
            else if (isset($law['buleg_' . $y]) && !empty($law['buleg_' . $y])) {
                $html_result .= '<section '. $datapath .' style="text-align:center;" class="drag-drop-law '. $addinclass .'"><strong>' . strtoupper($law['buleg_' . $y]['name'] . '' . issetParam($law[$y])) . '</strong></section>';
            } 
            else if (isset($law['angi_' . $y]) && !empty($law['angi_' . $y]['name'])) {
                $html_result .= '<section '. $datapath .' style="text-align:center;" class="drag-drop-law '. $addinclass .'"><strong>' . strtoupper($law['angi_' . $y]['name'] . '' . issetParam($law[$y])) . '</strong></section>';
            } 
            else if (isset($law['part_' . $y]) && !empty($law['part_' . $y]['name'])) {
                $html_result .= '<section '. $datapath .' style="text-align:center;" class="drag-drop-law '. $addinclass .'"><strong>' . strtoupper($law['part_' . $y]['name'] . '' . issetParam($law[$y])) . '</strong></section>';
            } 
            else if (isset($law['subpart_' . $y]) && !empty($law['subpart_' . $y]['name'])) {
                $html_result .= '<section '. $datapath .' style="text-align:center;" class="drag-drop-law '. $addinclass .'"><strong>' . strtoupper($law['subpart_' . $y]['name'] . '' . issetParam($law[$y])) . '</strong></section>';
            } 
            else if (isset($law['cccc_' . $y]) && !empty($law['cccc_' . $y])) {
                if (preg_match('/^МОНГОЛ УЛСЫН ХУУЛЬ/i', $law['cccc_' . $y])) {
                    $html_result .= '<section style="text-align:center;color:#275dff;font-size:16pt;">' . strtoupper($law['cccc_' . $y]) . '</section>' . issetParam($law[$y]);
                } else {
                    $html_result .= '<section style="text-align:center;"><strong>' . strtoupper(issetParam($law['cccc_' . $y])) . '</strong></section>' . issetParam($law[$y]);
                }
            } 
            else if (isset($law['footer' . $y]) && !empty($law['footer' . $y])) {
                $html_result .= '<section style="text-align:center;"><strong>' . $law['footer' . $y] . '</strong></section>';
                $html_result .= '<section style="margin-left:35px;">' . @$law[$y] . '</section>';
            } 
            else if (isset($law['date' . $y]) && !empty($law['date' . $y])) {
                $html_result .= '<section><table style="margin:auto;width:100%;color:#275dff;font-size:10pt;">
                                        <tr>
                                            <td align="left" width="33%">' . $law['date' . $y] . '</td>
                                            <td align="center" width="33%">' . issetParam($law['number' . $y]) . '</td>
                                            <td align="right" width="33%">' . issetParam($law['position' . ($y + 1)]) . '</td>
                                        </tr>
                                </table></section>';

                if (sizeof($law) == 4) {
                    $html_result .= issetParam($law[1]);
                }
                
                if (isset($law[$y]) && !empty($law[$y])) {
                    $html_result .= $law[$y];
                }
            }
            else if (isset($law[$y]) && !empty($law[$y])) {
                $html_result .= $law[$y];
            }
        }
        
        $html_result = str_replace($new_char, $old_char, $html_result);
        $html_result = preg_replace('#(<br */?>\s*)+#i', '<br/>', $html_result);
        
        return $html_result;
    }
    
    function findChild(array $group, $type) {
        (Array) $child = $arr = array();
        (Int) $count_child = 0;
        
        foreach ($group as $key => $group_line) {
            $cleanTxt = preg_replace('/^[^A-Za-zА-Яа-яӨөҮү ]+/', '', $group_line);
            $numbering = substr($group_line, 0, strlen($group_line[0]) - strlen($cleanTxt));
            $num_array = array_filter(explode('.', trim($numbering)));
            
            if (issetParam($num_array[$type]) && $count_child < $num_array[$type]) {

                if (!empty($arr)) {
                    $parent_num = $count_child - 1;
                    $child[$parent_num]['child'] = $this->findChild($arr, $type + 1);
                }
                
                $child[$count_child]['text'] = $cleanTxt;
                $child[$count_child]['type'] = $type + 1;
                $child[$count_child]['numbering'] = $numbering;
                $count_child++;
                
                $arr = array();
                continue;
                
            }
            
            if (issetParam($num_array[$type]) && $count_child == $num_array[$type]) {
                array_push($arr, $group_line);
            }
        }
        
        return $child;
    }
    
    function contains($str, array $arr, $suffix = '') {
        foreach ($arr as $a) {
            if (stripos($str, $a . $suffix) !== false) {
                return true;
            }
        }
        return false;
    }
    
    function getTaxonomyWordsModel() {
        $arr = array();
        $taxArray = $this->db->GetAll('select id, taxonomy_keyword, parent_id from lis_law_taxonomy where parent_id is null');
        if($taxArray){
            foreach ($taxArray as $key => $value) {
                $row = $this->db->GetAll('SELECT
                    id,
                    taxonomy_keyword,
                    parent_id,
                    level
                FROM
                    lis_law_taxonomy
                CONNECT BY PRIOR
                    id = parent_id
                START WITH id = ' . $value['ID']);

                if($row){
                    array_push($arr, array_reverse($row));
                }
            }
            return array('status' => 'success', 'result' => $arr);
        } else {
            return array('status' => 'error', 'result' => null);
        }
    }
    
    public function saveLawProcessModel() {
        
        (Array) $response = array('status' => 'error', 'text' => Lang::line('msg_save_error'));
        (Array) $postData = Input::postData();
        $sessionUserId = Ue::sessionUserKeyId();
        $currentDate = Date::currentDate();
        
        try {
            
            $content = $postData['content'];
            $lawId = $postData['lawId'] ? $postData['lawId'] : '1';
            
            /* 
             * buheldee 1 LAW uusne
             * 
             */
            
            $content = strip_tags($content, "<title><table><thead><tbody><tfood><tr><th><td><p><section><img>");
            $content = explode('</title>', $content);
            $content = (isset($content[1]) ? $content[1] : $content[0]);

            $law_arr = preg_split('/(<[^>]*[^\/]>)/i', $content, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            
            (Int) $tcounter = 0;
            (String) $htmlcontent = $table = '';
            $law = array();
            foreach ($law_arr as $key => $row) {

                if (strpos($row, '<table') !== false) {
                    $htmlcontent .= $row;
                    $tcounter ++;
                }

                if (strpos($row, '/table') !== false) {
                    $htmlcontent .= $row;
                    $tcounter--;
                }

                if ($tcounter !== 0) {
                    $htmlcontent .= $row;
                } else {
                    if (!$htmlcontent) {
                        $htmlcontent = strpos($row, '<img') !== false ? $row : Str::sanitize($row);
                    }

                    if ($htmlcontent) {
                        array_push($law, $htmlcontent);
                    }

                    $htmlcontent = '';
                }
            }
            
            $lawArr = self::lawFormatterToJson1($law, $content);
            
            (Array) $data_temp = $data_temp1 = array();
            $index = $parentindex = $cindex = $ccindex = 0;
            
            $result = $this->db->UpdateClob('LIS_LAW', 'BODY_TEXT', $content, " ID = '$lawId' ");
            $result = ($lawArr['header_array']) ? $this->db->UpdateClob('LIS_LAW', 'LAW_HEADER', $lawArr['header_array'], " ID = '$lawId' ") : $result;
            $result = ($lawArr['footer_array']) ? $this->db->UpdateClob('LIS_LAW', 'LAW_FOOTER', $lawArr['footer_array'], " ID = '$lawId' ") : $result;
            
            unset($lawArr['header_array']);
            unset($lawArr['footer_array']);
             
            if (!$result) {
                throw new Exception("Body text алдаа заасан!");;
            }
            
            $result = $this->db->Execute("DELETE FROM LIS_LAW_KEY WHERE LAW_ID = '$lawId'");
            if (!$result) {
                throw new Exception("Бүх KEY REMOVE алдаа заасан!");;
            }
            
            $result = $this->db->Execute("DELETE FROM LIS_LAW_PARAGRAPH WHERE LAW_ID = '$lawId'");
            if (!$result) {
                throw new Exception("Бүх PARAGRAPH REMOVE алдаа заасан!");;
            }
            
            $lawKeyId = getUID();
            $param = array(
                'ID' => $lawKeyId,
                'LAW_ID' => $lawId,
                'CREATED_DATE' => $currentDate,
                'CREATED_USER_ID' => $sessionUserId,
                'VERSION_NUMBER' => '0',
                'IS_ACTIVE' => '1'
            );
            
            /* 
             * uussen 1 LAW deer BODY-oo butneer n 1 KEY uusne;
             * 
             */
            
            $result = $this->db->AutoExecute("LIS_LAW_KEY", $param);
            
            if (!$result) {
                throw new Exception("key insert алдаа заасан!");;
            }
            
            $this->db->UpdateClob('LIS_LAW_KEY', 'BODY_TEXT', $content, " ID = '$lawKeyId' ");
            
            /* 
             * daraa n KEY deeree tulguurlaad PARAGRAPH uusne
             * 
             */
            
            (Array) $lawKey = array('subpart' => '/ДЭД БҮЛЭГ/i', 'part' => '/ХЭСЭГ /i', 'angi' => '/АНГИ /i', 'buleg' => '/Р БҮЛЭГ/i', 'zuil' => '/р зүйл[.]/i');
            $displayOrder = 1;
            
            foreach ($lawArr as $key => $row) {
                
                $paragraphId = getUID();
                $paragraphNumber = '';
                
                $title = Str::htmltotext($row['text']);
                
                if (strpos($row['text'], '<table') !== false) {
                    $title = 'Хүснэгт';
                }
                
                if (strpos($row['text'], '<img') !== false) {
                    $title = 'Зураг';
                }
                
                if ($title) {
                    $param = array(
                        'ID' => $paragraphId,
                        'PARAGRAPH_TYPE_ID' => '',
                        'LAW_ID' => $lawId,
                        'PARAGRAPH_NUMBER' => $paragraphNumber,
                        'PARENT_ID' => null,
                        'IS_ACTIVE' => '1',
                        'TITLE' => $title,
                        'CREATED_DATE' => $currentDate,
                        'CREATED_USER_ID' => $sessionUserId,
                        'VERSION_NUMBER' => '0',
                        'DISPLAY_ORDER' => $displayOrder++
                    );

                    $result = $this->db->AutoExecute("LIS_LAW_PARAGRAPH", $param);

                    if ($result) {

                        $this->db->UpdateClob('LIS_LAW_PARAGRAPH', 'PARAGRAPH_TEXT', $row['text'], " ID = '$paragraphId' ");

                        $plawKeyId = getUID();
                        $paramKey = array(
                            'ID' => $plawKeyId,
                            'LAW_ID' => $lawId,
                            'PARAGRAPH_ID' => $paragraphId,
                            'ACT_NUMBER' => $paragraphNumber,
                            'CREATED_DATE' => $currentDate,
                            'CREATED_USER_ID' => $sessionUserId,
                            'IS_ACTIVE' => '1',
                            'VERSION_NUMBER' => '0'
                        );

                        $result = $this->db->AutoExecute("LIS_LAW_KEY", $paramKey);
                        $this->db->UpdateClob('LIS_LAW_KEY', 'BODY_TEXT', $row['text'], " ID = '$plawKeyId' ");

                        if (isset($row['child'])) {
                            $displayOrder = self::saveChildLaw($row['child'], $paragraphId, $lawId, $displayOrder++, $sessionUserId, $currentDate);
                        }
                    }
                }

            }
            
            $response = array('status' => 'success', 'text' => Lang::line('msg_save_success'));
            
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'text' => Lang::line('msg_save_error'), 'ex' => $ex->msg);
        }
        
        return $response;
    }
    
    public function saveChildLaw($lawArr, $parentId, $lawId, $disOrder, $sessionUserId, $currentDate) {
        if (is_array($lawArr)) {
            foreach ($lawArr as $key => $row) {
                
                $text = '';
                if (isset($row['text'])) {
                    $text = $row['text'];
                } else {
                    $text = $row;
                }
                
                $title = Str::htmltotext($text);
                
                if (strpos($text, '<table') !== false) {
                    $title = 'Хүснэгт';
                }
                
                if (strpos($text, '<img') !== false) {
                    $title = 'Зураг';
                }
                
                if ($title) {
                    $paragraphId = getUID();
                    $paragraphNumber = ''; //self::numberSplitText($text);

                    $param = array(
                        'ID' => $paragraphId,
                        'PARAGRAPH_TYPE_ID' => '',
                        'LAW_ID' => $lawId,
                        'PARAGRAPH_NUMBER' => $paragraphNumber,
                        'PARENT_ID' => $parentId,
                        'IS_ACTIVE' => '1',
                        'TITLE' => $title,
                        'CREATED_DATE' => $currentDate,
                        'CREATED_USER_ID' => $sessionUserId,
                        'VERSION_NUMBER' => '0',
                        'DISPLAY_ORDER' => $disOrder++
                    );

                    $result = $this->db->AutoExecute("LIS_LAW_PARAGRAPH", $param);

                    if ($result) {

                        $this->db->UpdateClob('LIS_LAW_PARAGRAPH', 'PARAGRAPH_TEXT', $text, " ID = '$paragraphId' ");

                        $plawKeyId = getUID();
                        $paramKey = array(
                            'ID' => $plawKeyId,
                            'LAW_ID' => $lawId,
                            'PARAGRAPH_ID' => $paragraphId,
                            'ACT_NUMBER' => $paragraphNumber,
                            'CREATED_DATE' => $currentDate,
                            'CREATED_USER_ID' => $sessionUserId,
                            'IS_ACTIVE' => '1',
                            'VERSION_NUMBER' => '0'
                        );

                        $result = $this->db->AutoExecute("LIS_LAW_KEY", $paramKey);
                        $this->db->UpdateClob('LIS_LAW_KEY', 'BODY_TEXT', $text, " ID = '$plawKeyId' ");

                        if (isset($row['child'])) {
                            $disOrder = self::saveChildLaw($row['child'], $paragraphId, $lawId, $disOrder, $sessionUserId, $currentDate);
                        }
                    }
                }
            }
        }
        
        return $disOrder;
    }
    
    function lawFormatter($html, $printLaw = true, $editLaw = false, $return = false, $tag = false, $withsemantic = false, $isroot = false, $rootLawId = '') {
        if ($tag === false) {
            $html = cleanOut($html);
            $html = asci2uni($html);
            
            $old_char = array('<sup>', '</sup>', '</p>', '</div>', '&nbsp;', '<br />', '</h1>', '</h2>');
            $new_char = array('@a', '@b', '<br>', '<br>', '', '<br/>', '<br/>', '<br/>');

            $html = str_replace($old_char, $new_char, $html);

            $html = strip_tags($html, '<br><a><strike><em><i><table><thead><tbody><tfood><tr><th><td><img><ol><li>');
            $html = preg_replace('/\s\s+/', ' ', $html);
            $html = preg_replace('#(<br */?>\s*)+#i', '<br/>', $html);
            $html = trim($html);
            $html = str_replace(array('<em>. </em>', '<em>.</em>'), '. ', $html);

            $law_arr = explode('<br/>', $html);
            
        } else {
            $law_arr = $html;
        }
        
        $header = issetParam($law_arr['header']);
        $footer = issetParam($law_arr['footer']);
        
        $i = 0;
        $z = 0;
        $tablaa = '';
        
        $addinclass = $editLaw ? '' : 'noedit';
        $addinclass = $rootLawId ? $addinclass : 'editonly ' . $addinclass;
        
        $tab1 = '<section style="text-indent:.5in;padding-left: 5rem;text-indent: unset"  class="drag-drop-law '. $addinclass .'">' . $tablaa . '%s</section>';
        $tab2 = '<section style="text-indent:1.0in;padding-left: 6rem;text-indent: unset" class="drag-drop-law '. $addinclass .'">' . $tablaa . $tablaa . '%s</section>';
        $tab3 = '<section style="text-indent:1.5in;padding-left: 7rem;text-indent: unset" class="drag-drop-law '. $addinclass .'">' . $tablaa . $tablaa . $tablaa . '%s</section>';
        
        (Array) $law = array();
        (Array) $tempArray = array();
        
        foreach ($law_arr as $lkey => $law_mor) {
            $addinclass1 = '';
            if ($lkey !== 'header' && $lkey !== 'footer') {
                
                (String) $datapath = ($tag) ? 'data-paragraph-keyid="'. $lkey .'"' : "";
                array_pop($law_arr);
                
                if ($tag) {
                    $tab1 = '<section style="text-indent:.5in;padding-left: 2.5rem;text-indent: unset;"  %dataid data-title="title" class="%droplawcustom drag-drop-law '. $addinclass .'">' . $tablaa . '%addintionalIcon1%s</section>';
                    $tab2 = '<section style="text-indent:1.0in;padding-left: 4.5rem;text-indent: unset;" %dataid data-title="title" class="%droplawcustom drag-drop-law '. $addinclass .'">' . $tablaa . $tablaa . '%addintionalIcon1%s</section>';
                    $tab3 = '<section style="text-indent:1.5in;padding-left: 6.5rem;text-indent: unset;" %dataid data-title="title" class="%droplawcustom drag-drop-law '. $addinclass .'">' . $tablaa . $tablaa . $tablaa . '%addintionalIcon1%s</section>';
                    
                    $tab1 = str_replace("%dataid", $datapath, $tab1);
                    $tab2 = str_replace("%dataid", $datapath, $tab2);
                    $tab3 = str_replace("%dataid", $datapath, $tab3);
                    $addintionalIcon1 = '';
                    
                    if ($withsemantic) {
                        $rowDa = $this->db->GetAll("SELECT
                                                        MAX(T0.ID) AS ID,
                                                        T1.ID AS LAW_KEY_ID,
                                                        T3.LAW_TYPE_ID,
                                                        T4.ICON,
                                                        T5.IN_ACTIVE
                                                    FROM
                                                            META_DM_RECORD_MAP T0
                                                        INNER JOIN LIS_LAW_KEY        T1 ON T0.TRG_RECORD_ID = T1.ID
                                                        INNER JOIN LIS_LAW_PARAGRAPH  T2 ON T1.PARAGRAPH_ID = T2.ID
                                                        INNER JOIN LIS_LAW T3 ON T2.LAW_ID = T3.ID
                                                        INNER JOIN LIS_LAW_TYPE T4 ON T3.LAW_TYPE_ID = T4.ID
                                                        INNER JOIN (
                                                        SELECT 
                                                            t0.ID AS LAW_KEY_ID, 
                                                            t1.IN_ACTIVE
                                                        FROM LIS_LAW_KEY t0 
                                                        INNER JOIN lis_law_paragraph t1 ON t0.PARAGRAPH_ID = t1.ID
                                                        WHERE t0.ID = '$lkey' ) T5 ON T0.SRC_RECORD_ID = T5.LAW_KEY_ID
                                                    WHERE T0.SRC_TABLE_NAME = 'LIS_LAW_KEY'
                                                        AND T0.TRG_TABLE_NAME = 'LIS_LAW_KEY'
                                                        AND T0.SEMANTIC_TYPE_ID = '2003'
                                                        AND T0.SRC_RECORD_ID = '$lkey'
                                                    GROUP BY 
                                                        T1.ID,
                                                        T4.ICON,
                                                        T5.IN_ACTIVE,
                                                        T3.LAW_TYPE_ID");
                        
                        if ($rowDa) {
                            $ssId = '';
                            $addintionalIcon1 .= '<div class="icon-s">';
                            
                            foreach ($rowDa as $rrkey => $rrRow) {
                                $ssId = $rrRow['ID'];
                                $addintionalIcon1 .= '<i class="fa '. $rrRow['ICON'] .'"></i>';
                            }
                            
                            $addintionalIcon1 .= '</div>';
                            
                            $addinclass1 = 'drag-drop-law-custom' . (issetParam($rowDa[0]['IN_ACTIVE']) === '1' ? ' law-inactive' : '') ;
                            $tab1 = str_replace("%droplawcustom", $addinclass1, $tab1);
                            $tab2 = str_replace("%droplawcustom", $addinclass1, $tab2);
                            $tab3 = str_replace("%droplawcustom", $addinclass1, $tab3);

                            $tab1 = str_replace('data-title="title"', 'data-srcid="'. $ssId .'" data-lawkeyid="'. $lkey .'"', $tab1);
                            $tab2 = str_replace('data-title="title"', 'data-srcid="'. $ssId .'" data-lawkeyid="'. $lkey .'"', $tab2);
                            $tab3 = str_replace('data-title="title"', 'data-srcid="'. $ssId .'" data-lawkeyid="'. $lkey .'"', $tab3);
                        }
                            
                        if (!$rootLawId) {
                            $additionData = $this->db->GetAll("SELECT 
                                                                    t1.PREV_PARAGRAPH_TEXT ,
                                                                    t1.NEXT_PARAGRAPH_TEXT,
                                                                    TO_CHAR(t3.CREATED_DATE, 'YYYY-MM-DD') AS CREATED_DATE,
                                                                    t5.PARAGRAPH_TEXT,
                                                                    t5.LAW_ID
                                                                FROM lis_law_change_book t0 
                                                                INNER JOIN lis_law_change_book_dtl t1 ON t0.ID = t1.BOOK_ID
                                                                INNER JOIN META_DM_RECORD_MAP t2 ON t1.map_id = t2.ID
                                                                INNER JOIN LIS_LAW t3 ON t0.CHANGE_LAW_ID = t3.ID
                                                                INNER JOIN LIS_LAW_KEY t4 ON t2.TRG_RECORD_ID = t4.ID
                                                                INNER JOIN LIS_LAW_PARAGRAPH t5 ON t4.PARAGRAPH_ID = t5.ID
                                                                WHERE t2.SRC_RECORD_ID = '$lkey' ORDER BY t0.CREATED_DATE DESC");
                            if ($additionData) {
                                $addintionHtml = '<table class="table addition-changebookfull w-100" data-patid="'. $lkey .'">';
                                    $addintionHtml .= '<tbody>';

                                    foreach ($additionData as $akey => $arow) {
                                        $addintionHtml .= "<tr>";
                                            $addintionHtml .= "<td style='width: 80px'>". $arow['CREATED_DATE'] . "</td>";
                                            $addintionHtml .= "<td >" . $arow['PREV_PARAGRAPH_TEXT'] ."</td>";
                                        $addintionHtml .= "</tr>";
                                    }

                                    $addintionHtml .= '</tbody>';
                                $addintionHtml .= '</table>';

                                $tab1 = str_replace("%s", "%s$addintionHtml", $tab1);
                                $tab2 = str_replace("%s", "%s$addintionHtml", $tab2);
                                $tab3 = str_replace("%s", "%s$addintionHtml", $tab3);
                            }
                        }
                        
                    }
                        
                    $tab1 = str_replace("%addintionalIcon1", $addintionalIcon1, $tab1);
                    $tab2 = str_replace("%addintionalIcon1", $addintionalIcon1, $tab2);
                    $tab3 = str_replace("%addintionalIcon1", $addintionalIcon1, $tab3);
                }

                if (mb_strlen($law_mor) > 2 && !empty($law_mor) && strpos($law_mor, '<img') === false) {
                    
                    $firstnum = abs($law_mor);
                    if (preg_match('/р зүйл[.]/i', $law_mor)) {
                        $i = $i + 1;
                        $law['zuil_' . $i]['name'] = $law_mor . '<br/><section></section>';
                        $law['zuil_' . $i]['paragraphid'] = $lkey;
                    } else if (preg_match('/Р БҮЛЭГ/i', $law_mor)) {
                        $i = $i + 1;
                        $law['buleg_' . $i]['name'] = '<br/><br/>' . $law_mor . '<br/><section></section>';
                        $law['buleg_' . $i]['paragraphid'] = $lkey;
                    } else if (preg_match('/АНГИ /i', $law_mor)) {
                        $i = $i + 1;
                        $law['angi_' . $i]['name'] = $law_mor . '<br/><section></section>';
                        $law['angi_' . $i]['paragraphid'] = $lkey;
                    } else if (preg_match('/ХЭСЭГ /i', $law_mor)) {
                        $i = $i + 1;
                        $law['part_' . $i]['name'] = $law_mor . '<br/><section></section>';
                        $law['part_' . $i]['paragraphid'] = $lkey;
                    } else if (preg_match('/ДЭД БҮЛЭГ/i', $law_mor) || preg_match('/дэд бүлэг/i', $law_mor)) {
                        $i = $i + 1;
                        $law['subpart_' . $i]['name'] = $law_mor . '<br/><section></section>';
                        $law['subpart_' . $i]['paragraphid'] = $lkey;
                    } else if (preg_match('/^МОНГОЛ УЛСЫН ИХ ХУРЛЫН ДАРГА/i', $law_mor) || preg_match('/^МОНГОЛ УЛСЫН ИХ ХУРЛЫН ДЭД ДАРГА/i',
                                    $law_mor) || preg_match('/БАГА ХУРЛЫН ДАРГА/i', $law_mor) || preg_match('/ХУРЛЫН НАРИЙН БИЧГИЙН ДАРГА/i',
                                    $law_mor) || preg_match('/ХУРЛЫН ТАМГЫН ГАЗРЫН ЕРӨНХИЙ НАРИЙН БИЧГИЙН ДАРГА/i',
                                    $law_mor) || preg_match('/ХУРЛЫН ТАМГЫН ГАЗРЫН НАРИЙН БИЧГИЙН ДАРГА/i',
                                    $law_mor) || preg_match('/ХУРЛЫН ТЭРГҮҮЛЭГЧДИЙН ДАРГА/i', $law_mor)) {
                        $i = $i + 1;
                        
                        if ($tag) {
                            $law_mor = str_replace('&nbsp;', '', $law_mor);
                        }
                        
                        $law_mor = str_replace("ДАРГА",
                                "ДАРГА&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
                                $law_mor);
                        $law['footer' . $i] = $law_mor . '<br/>';
                    } else if ((preg_match('/\d{4}\sоны\s\d{1,2}\s(дүгээр|дугаар)\sсарын\s\d{1,2}[-](ны|ний)\sөдөр\s(Улаанбаатар хот|Төрийн ордон(.|,) Улаанбаатар хот)/i',
                                    $law_mor) || preg_match('/^\d{4}\sоны\s\d{1,2}\sсарын\s\d{1,2}\sөдөр\s(Улаанбаатар хот|Төрийн ордон(.|,) Улаанбаатар хот)$/i',
                                    $law_mor))) {

                        $lawrow1 = $lawrow2 = $lawrow3 = $lawrow4 = $law_mor;
                        preg_match('/\d{4}\sоны\s\d{1,2}\s(дүгээр|дугаар)\sсарын\s\d{1,2}[-](ны|ний)\sөдөр/i',
                                $lawrow1, $match1);
                        preg_match('/\d{4}\sоны\s\d{1,2}\sсарын\s\d{1,2}\sөдөр/i', $lawrow2, $match2);
                        preg_match('/Улаанбаатар хот/i', $lawrow3, $match3);
                        preg_match('/Төрийн ордон(.|,) Улаанбаатар хот/i', $lawrow4, $match4);

                        $i = $i + 1;
                        if (!empty($match1[0])) {
                            $law['date' . $i] = $match1[0] . '<br/>';
                        } else {
                            $law['date' . $i] = $match2[0] . '<br/>';
                        }
                        if (!empty($match4[0])) {
                            $law['position' . ($i + 1)] = $match4[0] . '<br/>';
                        } else {
                            $law['position' . ($i + 1)] = $match3[0] . '<br/>';
                        }
                    } else if ((preg_match('/\d{4}\sоны\s\d{1,2}\s(дүгээр|дугаар)\sсарын\s\d{1,2}[-](ны|ний)\sөдөр\sДугаар\s\d{1,2}\s(Улаанбаатар хот|Төрийн ордон(.|,) Улаанбаатар хот)/i',
                                    $law_mor) || preg_match('/^\d{4}\sоны\s\d{1,2}\sсарын\s\d{1,2}\sөдөр\sДугаар\s\d{1,2}\s(Улаанбаатар хот|Төрийн ордон(.|,) Улаанбаатар хот)$/i',
                                    $law_mor))) {

                        $lawrow1 = $lawrow2 = $lawrow3 = $lawrow4 = $lawrow5 = $law_mor;
                        preg_match('/\d{4}\sоны\s\d{1,2}\s(дүгээр|дугаар)\sсарын\s\d{1,2}[-](ны|ний)\sөдөр/i',
                                $lawrow1, $match1);
                        preg_match('/\d{4}\sоны\s\d{1,2}\sсарын\s\d{1,2}\sөдөр/i', $lawrow2, $match2);
                        preg_match('/Улаанбаатар хот/i', $lawrow3, $match3);
                        preg_match('/Төрийн ордон(.|,) Улаанбаатар хот/i', $lawrow4, $match4);
                        preg_match('/Дугаар\s\d{1,2}/i', $lawrow5, $match5);

                        $i = $i + 1;
                        if (!empty($match1[0])) {
                            $law['date' . $i] = $match1[0] . '<br/>';
                        } else {
                            $law['date' . $i] = $match2[0] . '<br/>';
                        }

                        if (!empty($match5[0])) {
                            $law['number' . $i] = $match5[0] . '<br/>';
                        } else {
                            $law['number' . $i] = '';
                        }

                        if (!empty($match4[0])) {
                            $law['position' . ($i + 1)] = $match4[0] . '<br/>';
                        } else {
                            $law['position' . ($i + 1)] = $match3[0] . '<br/>';
                        }
                    } else if (preg_match('/\d{4}\sоны\s\d{1,2}\s(дүгээр|дугаар)\sсарын\s\d{1,2}[-](ны|ний)\sөдөр/i',
                                    $law_mor) || preg_match('/^\d{4}\sоны\s\d{1,2}\sсарын\s\d{1,2}\sөдөр$/i',
                                    $law_mor)) {
                        if ($z == 1 || $z == 2) {
                            $i = $i + 1;
                            $law['date' . $i] = $law_mor . '<br/>';
                        } else {
                            $i = $i;
                            if (isset($law[$i])) {
                                $law[$i] .= $law_mor;
                            } else {
                                
                            }
                        }
                    } else if (preg_match('/Улаанбаатар хот/i', $law_mor) || preg_match('/Төрийн ордон(.|,) Улаанбаатар хот/i',
                                    $law_mor)) {
                        if ($z == 2 || $z == 3) {
                            $i = $i + 1;
                            $law['position' . $i] = $law_mor . '<br/>';
                        } else {
                            $i = $i;
                            if (isset($law[$i])) {
                                $law[$i] .= $law_mor;
                            } else {
                                
                            }
                            
                        }
                    } else if (preg_match('/ХУУЛЬ$/i', $law_mor)) {
                        $i = $i + 1;
                        $law['cccc_' . $i] = $law_mor . '<br/>';
                    } else {
                        if (strpos($law_mor, '<table') === false) {
                            if (preg_match('/\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]/i', $law_mor)) {
                                $law_mor = str_replace("%s", $law_mor, $tab3);
                            } else if (preg_match('/\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]/i', $law_mor)) {
                                $law_mor = str_replace("%s", $law_mor, $tab2);
                            } else if (preg_match('/\d{1,3}[.]\d{1,3}[.]/i', $law_mor)) {
                                $law_mor = str_replace("%s", $law_mor, $tab1);
                            } else if (preg_match('/\d{1,3}(\/)/i', $law_mor)) {
                                $law_mor = str_replace("%s", $law_mor, $tab2);
                            } else if (preg_match('/\d{1,3}[.]/i', $law_mor)) {
                                $law_mor = str_replace("%s", $law_mor, $tab1);
                            } else if (preg_match('/^<[a]/i', $law_mor) || preg_match('/^\[\d\]/i', $law_mor)) {
                                $law_mor = $law_mor . '<br/>';
                            } else if (preg_match('/^<ol>/i', $law_mor)) {
                                $law_mor = $law_mor . '<br/>';
                            } else if (($z < 10 || $z == 0) && empty($law['zuil_' . $i]['name'])) {
                                $law_mor = '<section '. $datapath .' style="text-align:center;font-weight:bold" class="drag-drop-law '. $addinclass .'">' . $law_mor . '</section>';
                            }
                        }

                        if (isset($law[$i])) {
                            if (strpos($law_mor, 'table') !== false && strpos($law_mor, 'addition-changebookfull') === false) {
                                $law[$i] .= '<section '. $datapath .' class="drag-drop-law-table '. $addinclass .'">' . $law_mor . '</section>';
                            } else {
                                if (strpos($law_mor, 'drag-drop-law') === false ) {
                                    $law[$i] .= '<section '. $datapath .' style="text-indent:.5in;padding-left: 5rem;text-indent: unset;" class="drag-drop-law '. $addinclass . ' ' . $addinclass1 .'">' . $law_mor . '</section>';
                                } else {
                                    $law[$i] .= $law_mor;
                                }
                            }
                        } else {
                            if (preg_match('/\d{1,3}[.]\d{1,3}[.]/i', $law_mor) || preg_match('/\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]/i',
                                            $law_mor) || preg_match('/\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]/i',
                                            $law_mor)) {
                                $law[$i] = $law_mor;
                            } else if (is_html($law_mor)) {
                                $law[$i] = $law_mor;
                            } else {
                                $law[$i] = $law_mor . '<br/>';
                            }
                        }
                    }
                    
                } elseif(mb_strlen($law_mor) > 2 && !empty($law_mor) && strpos($law_mor, '<img') !== false) {
                    if (!isset($law[$i])) {
                        $law[$i] = '';
                    }
                    
                    $law[$i] .= '<section '. $datapath .' style="text-align:center;font-weight:bold" class="drag-drop-law '. $addinclass .'">' . $law_mor . '</section>';
                }
                
                $z++;
            }
        }
        
        $old_char = array('<sup>', '</sup>');
        $new_char = array('@a', '@b',);

        $html_result = '';
        
        for ($y = 0; $y <= sizeof($law); $y++) {
            
            if (isset($law['zuil_' . $y]) && !empty($law['zuil_' . $y])) {
                $datapath = ($tag) ? 'data-paragraph-keyid="'. $law['zuil_' . $y]['paragraphid'] .'"' : "";
                $html_result .= '<section id="list_item_' . $y . '" '. $datapath .' class="__drag-drop-law">';
                    $html_result .= '<div class="__editLaw pull-left">';
                        $html_result .='<section class="msg_head opened_head '. $addinclass .'">';
                            $html_result .= '<label class="drag-drop-law">' . str_replace($new_char, $old_char, $law['zuil_' . $y]['name']) . '</label>';
                        $html_result .= '</section>';
                        $html_result .= '<div class="msg_body" style="text-align: justify">';
                            $html_result .= str_replace($new_char, $old_char, @$law[$y]);
                                $html_result .=  ($printLaw ? '<a href="javascript:printFormSheet(' . "'list_item_$y'" . ');" class="printpage">Хэвлэх</a>' : '');
                        $html_result .= '</div>'; 
                    $html_result .= '</div>';
                    $html_result .= ($editLaw !== false ? '<a href="javascript:;" class="editlaw">Засах</a>' : '');
                    $html_result .= '<div class="clear"></div>';
                $html_result .= '</section>';
            } 
            else if (isset($law['buleg_' . $y]) && !empty($law['buleg_' . $y])) {
                $datapath = ($tag) ? 'data-paragraph-keyid="'. $law['buleg_' . $y]['paragraphid'] .'"' : "";
                $html_result .= '<section '. $datapath .' style="text-align:center;" class="drag-drop-law '. $addinclass .'"><strong>' . strtoupper($law['buleg_' . $y]['name'] . '' . issetParam($law[$y])) . '</strong></section>';
            } 
            else if (isset($law['angi_' . $y]) && !empty($law['angi_' . $y]['name'])) {
                $datapath = ($tag) ? 'data-paragraph-keyid="'. $law['angi_' . $y]['paragraphid'] .'"' : "";
                $html_result .= '<section '. $datapath .' style="text-align:center;" class="drag-drop-law '. $addinclass .'"><strong>' . strtoupper($law['angi_' . $y]['name'] . '' . issetParam($law[$y])) . '</strong></section>';
            } 
            else if (isset($law['part_' . $y]) && !empty($law['part_' . $y]['name'])) {
                $datapath = ($tag) ? 'data-paragraph-keyid="'. $law['part_' . $y]['paragraphid'] .'"' : "";
                $html_result .= '<section '. $datapath .' style="text-align:center;" class="drag-drop-law '. $addinclass .'"><strong>' . strtoupper($law['part_' . $y]['name'] . '' . issetParam($law[$y])) . '</strong></section>';
            } 
            else if (isset($law['subpart_' . $y]) && !empty($law['subpart_' . $y]['name'])) {
                $datapath = ($tag) ? 'data-paragraph-keyid="'. $law['subpart_' . $y]['paragraphid'] .'"' : "";
                $html_result .= '<section '. $datapath .' style="text-align:center;" class="drag-drop-law '. $addinclass .'"><strong>' . strtoupper($law['subpart_' . $y]['name'] . '' . issetParam($law[$y])) . '</strong></section>';
            } 
            else if (isset($law['cccc_' . $y]) && !empty($law['cccc_' . $y])) {
                if (preg_match('/^МОНГОЛ УЛСЫН ХУУЛЬ/i', $law['cccc_' . $y])) {
                    $html_result .= '<section style="text-align:center;color:#275dff;font-size:16pt;">' . strtoupper($law['cccc_' . $y]) . '</section><br><br>' . issetParam($law[$y]);
                } else {
                    $html_result .= '<section style="text-align:center;"><strong>' . strtoupper(issetParam($law['cccc_' . $y])) . '</strong></section>' . issetParam($law[$y]);
                }
            } 
            else if (isset($law['footer' . $y]) && !empty($law['footer' . $y])) {
                $html_result .= '<br><section style="text-align:center;"><strong>' . $law['footer' . $y] . '</strong></section><br>';
                $html_result .= '<section style="margin-left:35px;">' . @$law[$y] . '</section>';
            } 
            else if (isset($law['date' . $y]) && !empty($law['date' . $y])) {
                $html_result .= '<section><table style="margin:auto;width:100%;color:#275dff;font-size:10pt;">
                                        <tr>
                                            <td align="left" width="33%">' . $law['date' . $y] . '</td>
                                            <td align="center" width="33%">' . issetParam($law['number' . $y]) . '</td>
                                            <td align="right" width="33%">' . issetParam($law['position' . ($y + 1)]) . '</td>
                                        </tr>
                                </table></section><br><br>';

                if (sizeof($law) == 4) {
                    $html_result .= issetParam($law[1]);
                }
                
                if (isset($law[$y]) && !empty($law[$y])) {
                    $html_result .= $law[$y];
                }
            }
            else if (isset($law[$y]) && !empty($law[$y])) {
                $html_result .= $law[$y];
            }
            
        }
        
        $html_result = str_replace($new_char, $old_char, $html_result);
        $html_result = preg_replace('#(<br */?>\s*)+#i', '<br/>', $html_result);
        
        $html_result .= $printLaw ? '<script type="text/javascript" src="/js/print.js"></script>' : '';
        
        if ($tag) {
            $temphhtml = $html_result;
            $html_result = $header;
            $html_result .= $temphhtml;
            $html_result .= $footer;
        }
        
        return $html_result;
    }
    
    function lawFormatterNoType($html, $printLaw = true, $editLaw = false, $return = false, $tag = false) {
        
        $html = strip_tags($html, "<table><thead><tbody><tfood><tr><th><td><span><p><section><img>");
        $law_arr = preg_split('/(<[^>]*[^\/]>)/i', $html, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        
        (Int) $tcounter = 0;
        (String) $htmlcontent = $table = '';
        $law_arrt = array();
        
        foreach ($law_arr as $key => $row) {
            if (strpos($row, '<table') !== false) {
                $htmlcontent .= $row;
                $tcounter ++;
            }
            
            if (strpos($row, '/table') !== false) {
                $htmlcontent .= $row;
                $tcounter--;
            }
            
            if ($tcounter !== 0) {
                $htmlcontent .= $row;
            } else {
                if (!$htmlcontent) {
                    $htmlcontent = strpos($row, '<img') !== false ? $row : Str::sanitize($row);
                }
                
                if ($htmlcontent) {
                    array_push($law_arrt, $htmlcontent);
                }
                
                $htmlcontent = '';
            }
        }
        
        $i = 0;
        $z = 0;
        $tablaa = '';
        
        $tab1 = '<section style="text-indent:.5in;padding-left: 5rem;text-indent: unset"  class="drag-drop-law ">' . $tablaa . '%s</section>';
        $tab2 = '<section style="text-indent:1.0in;padding-left: 6rem;text-indent: unset" class="drag-drop-law ">' . $tablaa . $tablaa . '%s</section>';
        $tab3 = '<section style="text-indent:1.5in;padding-left: 7rem;text-indent: unset" class="drag-drop-law ">' . $tablaa . $tablaa . $tablaa . '%s</section>';
        
        (Array) $law_arr = $law = array();
        (Array) $tempArray = array();
        
        $old_char = array('<sup>', '</sup>');
        $new_char = array('@a', '@b',);
        
        $html_result = $addinclass = '';
        
        foreach ($law_arrt as $kk => $rr) {
            if (strpos($rr, '<table') === false && strpos($rr, '<img') === false) {
                $rr = Str::htmltotext($rr);
            }
            
            array_push($law_arr, $rr);
        }
        
        foreach ($law_arr as $lkey => $law_mor) {
            $addinclass1 = '';
                
            (String) $datapath = "";
            array_pop($law_arr);

            if (mb_strlen($law_mor) > 2 && !empty($law_mor)) {

                $firstnum = abs($law_mor);
                if (preg_match('/р зүйл[.]/i', $law_mor)) {
                    $i = $i + 1;
                    $law['zuil_' . $i]['name'] = $law_mor . '<br/><section></section>';
                    $law['zuil_' . $i]['paragraphid'] = $lkey;
                } else if (preg_match('/Р БҮЛЭГ/i', $law_mor)) {
                    $i = $i + 1;
                    $law['buleg_' . $i]['name'] = '<br/><br/>' . $law_mor . '<br/><section></section>';
                    $law['buleg_' . $i]['paragraphid'] = $lkey;
                } else if (preg_match('/АНГИ /i', $law_mor)) {
                    $i = $i + 1;
                    $law['angi_' . $i]['name'] = $law_mor . '<br/><section></section>';
                    $law['angi_' . $i]['paragraphid'] = $lkey;
                } else if (preg_match('/ХЭСЭГ /i', $law_mor)) {
                    $i = $i + 1;
                    $law['part_' . $i]['name'] = $law_mor . '<br/><section></section>';
                    $law['part_' . $i]['paragraphid'] = $lkey;
                } else if (preg_match('/ДЭД БҮЛЭГ/i', $law_mor) || preg_match('/дэд бүлэг/i', $law_mor)) {
                    $i = $i + 1;
                    $law['subpart_' . $i]['name'] = $law_mor . '<br/><section></section>';
                    $law['subpart_' . $i]['paragraphid'] = $lkey;
                } else if (preg_match('/^МОНГОЛ УЛСЫН ИХ ХУРЛЫН ДАРГА/i', $law_mor) || preg_match('/^МОНГОЛ УЛСЫН ИХ ХУРЛЫН ДЭД ДАРГА/i',
                                $law_mor) || preg_match('/БАГА ХУРЛЫН ДАРГА/i', $law_mor) || preg_match('/ХУРЛЫН НАРИЙН БИЧГИЙН ДАРГА/i',
                                $law_mor) || preg_match('/ХУРЛЫН ТАМГЫН ГАЗРЫН ЕРӨНХИЙ НАРИЙН БИЧГИЙН ДАРГА/i',
                                $law_mor) || preg_match('/ХУРЛЫН ТАМГЫН ГАЗРЫН НАРИЙН БИЧГИЙН ДАРГА/i',
                                $law_mor) || preg_match('/ХУРЛЫН ТЭРГҮҮЛЭГЧДИЙН ДАРГА/i', $law_mor)) {
                    $i = $i + 1;

                    $law_mor = str_replace("ДАРГА",
                            "ДАРГА&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
                            $law_mor);
                    $law['footer' . $i] = $law_mor . '<br/>';
                } else if ((preg_match('/\d{4}\sоны\s\d{1,2}\s(дүгээр|дугаар)\sсарын\s\d{1,2}[-](ны|ний)\sөдөр\s(Улаанбаатар хот|Төрийн ордон(.|,) Улаанбаатар хот)/i',
                                $law_mor) || preg_match('/^\d{4}\sоны\s\d{1,2}\sсарын\s\d{1,2}\sөдөр\s(Улаанбаатар хот|Төрийн ордон(.|,) Улаанбаатар хот)$/i',
                                $law_mor))) {

                    $lawrow1 = $lawrow2 = $lawrow3 = $lawrow4 = $law_mor;
                    preg_match('/\d{4}\sоны\s\d{1,2}\s(дүгээр|дугаар)\sсарын\s\d{1,2}[-](ны|ний)\sөдөр/i',
                            $lawrow1, $match1);
                    preg_match('/\d{4}\sоны\s\d{1,2}\sсарын\s\d{1,2}\sөдөр/i', $lawrow2, $match2);
                    preg_match('/Улаанбаатар хот/i', $lawrow3, $match3);
                    preg_match('/Төрийн ордон(.|,) Улаанбаатар хот/i', $lawrow4, $match4);

                    $i = $i + 1;
                    if (!empty($match1[0])) {
                        $law['date' . $i] = $match1[0] . '<br/>';
                    } else {
                        $law['date' . $i] = $match2[0] . '<br/>';
                    }
                    if (!empty($match4[0])) {
                        $law['position' . ($i + 1)] = $match4[0] . '<br/>';
                    } else {
                        $law['position' . ($i + 1)] = $match3[0] . '<br/>';
                    }
                } else if ((preg_match('/\d{4}\sоны\s\d{1,2}\s(дүгээр|дугаар)\sсарын\s\d{1,2}[-](ны|ний)\sөдөр\sДугаар\s\d{1,2}\s(Улаанбаатар хот|Төрийн ордон(.|,) Улаанбаатар хот)/i',
                                $law_mor) || preg_match('/^\d{4}\sоны\s\d{1,2}\sсарын\s\d{1,2}\sөдөр\sДугаар\s\d{1,2}\s(Улаанбаатар хот|Төрийн ордон(.|,) Улаанбаатар хот)$/i',
                                $law_mor))) {

                    $lawrow1 = $lawrow2 = $lawrow3 = $lawrow4 = $lawrow5 = $law_mor;
                    preg_match('/\d{4}\sоны\s\d{1,2}\s(дүгээр|дугаар)\sсарын\s\d{1,2}[-](ны|ний)\sөдөр/i',
                            $lawrow1, $match1);
                    preg_match('/\d{4}\sоны\s\d{1,2}\sсарын\s\d{1,2}\sөдөр/i', $lawrow2, $match2);
                    preg_match('/Улаанбаатар хот/i', $lawrow3, $match3);
                    preg_match('/Төрийн ордон(.|,) Улаанбаатар хот/i', $lawrow4, $match4);
                    preg_match('/Дугаар\s\d{1,2}/i', $lawrow5, $match5);

                    $i = $i + 1;
                    if (!empty($match1[0])) {
                        $law['date' . $i] = $match1[0] . '<br/>';
                    } else {
                        $law['date' . $i] = $match2[0] . '<br/>';
                    }

                    if (!empty($match5[0])) {
                        $law['number' . $i] = $match5[0] . '<br/>';
                    } else {
                        $law['number' . $i] = '';
                    }

                    if (!empty($match4[0])) {
                        $law['position' . ($i + 1)] = $match4[0] . '<br/>';
                    } else {
                        $law['position' . ($i + 1)] = $match3[0] . '<br/>';
                    }
                } else if (preg_match('/\d{4}\sоны\s\d{1,2}\s(дүгээр|дугаар)\sсарын\s\d{1,2}[-](ны|ний)\sөдөр/i',
                                $law_mor) || preg_match('/^\d{4}\sоны\s\d{1,2}\sсарын\s\d{1,2}\sөдөр$/i',
                                $law_mor)) {
                    if ($z == 1 || $z == 2) {
                        $i = $i + 1;
                        $law['date' . $i] = $law_mor . '<br/>';
                    } else {
                        $i = $i;
                        if (isset($law[$i])) {
                            $law[$i] .= $law_mor;
                        } else {

                        }
                    }
                } else if (preg_match('/Улаанбаатар хот/i', $law_mor) || preg_match('/Төрийн ордон(.|,) Улаанбаатар хот/i',
                                $law_mor)) {
                    if ($z == 2 || $z == 3) {
                        $i = $i + 1;
                        $law['position' . $i] = $law_mor . '<br/>';
                    } else {
                        $i = $i;
                        if (isset($law[$i])) {
                            $law[$i] .= $law_mor;
                        } else {

                        }

                    }
                } else if (preg_match('/ХУУЛЬ$/i', $law_mor)) {
                    $i = $i + 1;
                    $law['cccc_' . $i] = $law_mor . '<br/>';
                } else {
                    if (strpos($law_mor, '<table') === false) {
                        if (preg_match('/\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]/i', $law_mor)) {
                            $law_mor = str_replace("%s", $law_mor, $tab3);
                        } 
                        else if (preg_match('/\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]/i', $law_mor)) {
                            $law_mor = str_replace("%s", $law_mor, $tab2);
                        } 
                        else if (preg_match('/\d{1,3}[.]\d{1,3}[.]/i', $law_mor)) {
                            $law_mor = str_replace("%s", $law_mor, $tab1);
                        } 
                        else if (preg_match('/\d{1,3}(\/)/i', $law_mor)) {
                            $law_mor = str_replace("%s", $law_mor, $tab2);
                        } 
                        else if (preg_match('/\d{1,3}[.]/i', $law_mor)) {
                            $law_mor = str_replace("%s", $law_mor, $tab1);
                        } 
                        else if (preg_match('/^<[a]/i', $law_mor) || preg_match('/^\[\d\]/i', $law_mor)) {
                            $law_mor = $law_mor . '<br/>';
                        } 
                        else if (preg_match('/^<ol>/i', $law_mor)) {
                            $law_mor = $law_mor . '<br/>';
                        } 
                        else if (($z < 10 || $z == 0) && empty($law['zuil_' . $i]['name'])) {
                            $styleAttr = Input::post('elementStyle', 'text-align:center;font-weight:bold');
                            $law_mor = '<section '. $datapath .' style="'. $styleAttr .'" class="drag-drop-law '. $addinclass .'">' . $law_mor . '</section>';
                        }
                    }

                    if (isset($law[$i])) {
                        if (strpos($law_mor, 'table') !== false && strpos($law_mor, 'addition-changebookfull') === false) {
                            $law[$i] .= '<section '. $datapath .' class="drag-drop-law-table '. $addinclass .'">' . $law_mor . '</section>';
                        } else {
                            if (strpos($law_mor, 'drag-drop-law') === false ) {
                                $law[$i] .= '<section '. $datapath .' style="text-indent:.5in;padding-left: 5rem;text-indent: unset;" class="drag-drop-law '. $addinclass . ' ' . $addinclass1 .'">' . $law_mor . '</section>';
                            } else {
                                $law[$i] .= $law_mor;
                            }
                        }
                    } else {
                        if (preg_match('/\d{1,3}[.]\d{1,3}[.]/i', $law_mor) || preg_match('/\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]/i',
                                        $law_mor) || preg_match('/\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]/i',
                                        $law_mor)) {
                            $law[$i] = $law_mor;
                        } else if (is_html($law_mor)) {
                            $law[$i] = $law_mor;
                        } else {
                            $law[$i] = $law_mor . '<br/>';
                        }
                    }
                }
            }

            $z++;
        }
        
        for ($y = 0; $y <= sizeof($law); $y++) {
            
            if (isset($law['zuil_' . $y]) && !empty($law['zuil_' . $y])) {
                $datapath = "";
                $html_result .= '<section id="list_item_' . $y . '" '. $datapath .' class="__drag-drop-law">';
                    $html_result .= '<div class="__editLaw pull-left">';
                        $html_result .='<section class="msg_head opened_head '. $addinclass .'">';
                            $html_result .= '<label>' . str_replace($new_char, $old_char, $law['zuil_' . $y]['name']) . '</label>';
                        $html_result .= '</section>';
                        $html_result .= '<div class="msg_body" style="text-align: justify">';
                            $html_result .= str_replace($new_char, $old_char, @$law[$y]);
                                $html_result .=  ($printLaw ? '<a href="javascript:printFormSheet(' . "'list_item_$y'" . ');" class="printpage">Хэвлэх</a>' : '');
                        $html_result .= '</div>'; 
                    $html_result .= '</div>';
                    $html_result .= ($editLaw !== false ? '<a href="javascript:;" class="editlaw">Засах</a>' : '');
                    $html_result .= '<div class="clear"></div>';
                $html_result .= '</section>';
            } 
            else if (isset($law['buleg_' . $y]) && !empty($law['buleg_' . $y])) {
                $datapath = "";
                $html_result .= '<section '. $datapath .' style="text-align:center;" class="drag-drop-law '. $addinclass .'"><strong>' . strtoupper($law['buleg_' . $y]['name'] . '' . issetParam($law[$y])) . '</strong></section>';
            } 
            else if (isset($law['angi_' . $y]) && !empty($law['angi_' . $y]['name'])) {
                $datapath = "";
                $html_result .= '<section '. $datapath .' style="text-align:center;" class="drag-drop-law '. $addinclass .'"><strong>' . strtoupper($law['angi_' . $y]['name'] . '' . issetParam($law[$y])) . '</strong></section>';
            } 
            else if (isset($law['part_' . $y]) && !empty($law['part_' . $y]['name'])) {
                $datapath = "";
                $html_result .= '<section '. $datapath .' style="text-align:center;" class="drag-drop-law '. $addinclass .'"><strong>' . strtoupper($law['part_' . $y]['name'] . '' . issetParam($law[$y])) . '</strong></section>';
            } 
            else if (isset($law['subpart_' . $y]) && !empty($law['subpart_' . $y]['name'])) {
                $datapath = "";
                $html_result .= '<section '. $datapath .' style="text-align:center;" class="drag-drop-law '. $addinclass .'"><strong>' . strtoupper($law['subpart_' . $y]['name'] . '' . issetParam($law[$y])) . '</strong></section>';
            } 
            else if (isset($law['cccc_' . $y]) && !empty($law['cccc_' . $y])) {
                if (preg_match('/^МОНГОЛ УЛСЫН ХУУЛЬ/i', $law['cccc_' . $y])) {
                    $html_result .= '<section style="text-align:center;color:#275dff;font-size:16pt;">' . strtoupper($law['cccc_' . $y]) . '</section><br><br>' . issetParam($law[$y]);
                } else {
                    $html_result .= '<section style="text-align:center;"><strong>' . strtoupper(issetParam($law['cccc_' . $y])) . '</strong></section>' . issetParam($law[$y]);
                }
            } 
            else if (isset($law['footer' . $y]) && !empty($law['footer' . $y])) {
                $html_result .= '<br><section style="text-align:center;"><strong>' . $law['footer' . $y] . '</strong></section><br>';
                $html_result .= '<section style="margin-left:35px;">' . @$law[$y] . '</section>';
            } 
            else if (isset($law['date' . $y]) && !empty($law['date' . $y])) {
                $html_result .= '<section><table style="margin:auto;width:100%;color:#275dff;font-size:10pt;">
                                        <tr>
                                            <td align="left" width="33%">' . $law['date' . $y] . '</td>
                                            <td align="center" width="33%">' . issetParam($law['number' . $y]) . '</td>
                                            <td align="right" width="33%">' . issetParam($law['position' . ($y + 1)]) . '</td>
                                        </tr>
                                </table></section><br><br>';

                if (sizeof($law) == 4) {
                    $html_result .= issetParam($law[1]);
                }
                
                if (isset($law[$y]) && !empty($law[$y])) {
                    $html_result .= $law[$y];
                }
            }
            else if (isset($law[$y]) && !empty($law[$y])) {
                $html_result .= $law[$y];
            }
            
        }
        
        $html_result = str_replace($new_char, $old_char, $html_result);
        $html_result = preg_replace('#(<br */?>\s*)+#i', '<br/>', $html_result);
        
        $html_result .= $printLaw ? '<script type="text/javascript" src="/js/print.js"></script>' : '';
        
        if ($tag) {
            $temphhtml = $html_result;
            $html_result = $header;
            $html_result .= $temphhtml;
            $html_result .= $footer;
        }
        
        return $html_result;
        
    }
    
    /*
     * law format
     * descr
        array('zuil', 'buleg', 'angi', 'part', 'subpart');
     * 1. buleg             БҮЛЭГ
     * 2. subpart           ДЭД БҮЛЭГ
     * 3. zuil              ЗҮЙЛ
     * 1. part              ХЭСЭГ
     * 1. angi              АНГИ
     * 
     * */
    
    public function numberSplitText($str) {
        $str = preg_replace('/[^0-9\.]/','-', $str);
        $str = preg_replace('/(\-+)(\.\.+)/','-', $str);
        $str = trim($str, '-');
        $arr = explode('-', $str);
        return $arr[0];
    }

    public function saveSemanticMapModel() {
        $paraKeyId = Input::post('paraKeyId');
        $selectedId = Input::post('selectedId');
        $pid = $this->db->GetOne("SELECT ID FROM LIS_LAW_KEY WHERE PARAGRAPH_ID = '$selectedId'");
        $id = getUID();
        $data = array(
            'ID' => $id,
            'SRC_RECORD_ID' => $paraKeyId,
            'TRG_RECORD_ID' => $pid,
            'SEMANTIC_TYPE_ID' => 2003,
            'SRC_TABLE_NAME' => 'LIS_LAW_KEY',
            'TRG_TABLE_NAME' => 'LIS_LAW_KEY'
        );
        
        $result = $this->db->AutoExecute('META_DM_RECORD_MAP', $data);
        return array('srcid' => $id, 'trgrecordId' => $pid, 'id' => $id, 'status' => 'success');
    }

    function buildTree(array &$elements, $parentId = 0) {
        $branch = array();
    
        foreach ($elements as $element) {
            if ($element['PARENT_ID'] == $parentId) {
                $children = self::buildTree($elements, $element['ID']);
                if ($children) {
                    $element['CHILDREN'] = $children;
                }
                array_push($branch, $element);
                // $branch[$element['ID']] = $element;
                unset($elements[$element['ID']]);
            }
        }
        return $branch;
    }
    
    public function getDataLawContentModel($rowId, $mainData, $isprint = false, $isedit = false, $text = false, $withsemantic = false, $isroot = false, $rootLawId = '') {
        
        if ($rootLawId === '' || !$isroot) {
            
            $qry = "SELECT 
                        t1.ID,
                        t0.PARAGRAPH_NUMBER, 
                        " . $this->db->IfNull('t0.DESCRIPTION',  't0.PARAGRAPH_TEXT'). " AS PARAGRAPH_TEXT, 
                        t0.DISPLAY_ORDER 
                    FROM LIS_LAW_PARAGRAPH t0
                    INNER JOIN LIS_LAW_KEY t1 ON t0.ID = t1.PARAGRAPH_ID
                        WHERE t0.LAW_ID = '$rowId' 
                            AND t1.IS_ACTIVE = 1
                            AND t0.IS_ACTIVE = 1
                            AND t0.IN_ACTIVE = 0
                    ORDER BY TO_NUMBER(t0.DISPLAY_ORDER) ASC";
            
        } else {
            
            $qry = "SELECT 
                        t3.ID,
                        t2.PARAGRAPH_NUMBER, 
                        " . $this->db->IfNull('t2.DESCRIPTION',  't2.PARAGRAPH_TEXT'). " AS PARAGRAPH_TEXT, 
                        t2.DISPLAY_ORDER ,
                        t4.SRC_RECORD_ID, 
                        t2.VERSION_NUMBER
                    FROM LIS_LAW t0
                    INNER JOIN LIS_LAW t1 ON t1.ID = t0.REF_LAW_ID 
                    INNER JOIN  LIS_LAW_PARAGRAPH t2 ON t1.ID = t2.LAW_ID
                    INNER JOIN LIS_LAW_KEY t3 ON t2.ID = t3.paragraph_id
                    LEFT JOIN (
                        SELECT 
                            DISTINCT 
                            t1.ID, 
                            t2.SRC_RECORD_ID
                        FROM LIS_LAW_KEY t1
                            INNER join META_DM_RECORD_MAP t2 ON t1.ID = t2.SRC_RECORD_ID
                            INNER JOIN LIS_LAW_KEY t3 ON t2.TRG_RECORD_ID = t3.ID
                        WHERE t3.LAW_ID = '$rootLawId'
                    ) t4 ON t3.ID = t4.ID
                    WHERE t0.ID = '$rootLawId'
                    ORDER BY t2.DISPLAY_ORDER";
            
        }
        /* echo $qry; die; */
        $data = $this->db->GetAll($qry);
        
        (Array) $law = array();
        
        if ($data) {
            $law = Arr::groupByArrayOnlyRow($data, 'ID', 'PARAGRAPH_TEXT');
        }
        
        $headerData = $this->db->GetRow("SELECT LAW_HEADER, LAW_FOOTER FROM LIS_LAW WHERE ID = '$rowId'");
        /* var_dump($headerData['LAW_HEADER']); die; */
        $law['header'] = self::lawFormatter($headerData['LAW_HEADER'], $isprint, $isedit);;
        $law['footer'] = self::lawFormatter($headerData['LAW_FOOTER'], $isprint, $isedit);
        
        if ($text) {
            return implode(' ', $law);
        }
        
        return self::lawFormatter($law, $isprint, $isedit, false, true, $withsemantic, $isroot, $rootLawId);
        
    }

    public function generateValidationToken($email, $username) {
        $qry = "select user_id from um_system_user where email = '$email' and username = '$username'";
        $sysuserId = $this->db->GetOne($qry);
        $token = getUniqId();
        $id = getUniqId();
        $data = array(
            'VALIDATE_ID' => $id,
            'USER_ID' => $sysuserId,
            'TOKEN' => $token
        );
        $resultValidate = $this->db->AutoExecute('UM_USER_VALIDATE', $data, 'INSERT');
        
        $qry = "select user_id from um_user where system_user_id = $sysuserId";
        $sysuserId = $this->db->GetOne($qry);

        $roleMapId = getUniqId();
        $data = array(
            'user_id' => $sysuserId,
            'role_id' => 999,
            'id' => $roleMapId
        );
        $resultRole = $this->db->AutoExecute('UM_USER_ROLE', $data, 'INSERT');
        if($resultValidate && $resultRole){
            return $token;
        }
    }

    public function legalActUserModel($email, $username) {
        $legalActTypes = Input::post('legalActTypes');
        $qry = "select user_id from um_system_user where email = '$email' and username = '$username'";
        $userId = $this->db->GetOne($qry);
        foreach ($legalActTypes as $value) {
            $id = getUniqId();
            $data = array(
                'ID' => $id,
                'LAW_CATEGORY_ID' => $value,
                'USER_ID' => $userId,
                'IS_NOTIFICATION' => 1
            );
            $this->db->AutoExecute('LIS_PORTAL_LAW_NOTIFICATION', $data, 'INSERT');
        }
        return true;
    }
    
    public function getProfileData() {
        $row = $this->db->GetRow("
            SELECT 
                LAST_NAME, 
                FIRST_NAME, 
                STATE_REG_NUMBER, 
                UM.USERNAME  
            FROM BASE_PERSON BP 
                INNER JOIN UM_SYSTEM_USER UM ON UM.PERSON_ID = BP.PERSON_ID 
            WHERE UM.USER_ID = ".$this->db->Param(0), 
            array(Ue::sessionUserId())
        );
        
        if ($row) {
            return $row;
        }
        return null;
    }
    
    public function getAddonchangesTree($lawId) {
        
        $qry = "SELECT
                        LL.ID,
                        LLK.ID KEYID,
                        LL.TITLE AS TEXT,
                        NULL AS PARAGRAPH_NUMBER,
                        'Хууль' TYPE,
                        0 AS PARENT_ID,
                        0 AS DISPLAY_ORDER
                    FROM
                        LIS_LAW LL
                    INNER JOIN LIS_LAW_KEY LLK ON LLK.LAW_ID = LL.ID AND LLK.PARAGRAPH_ID IS NULL
                    WHERE LL.ID = '$lawId'";
        
        $res = $this->db->GetAll($qry);
        
        $qry1 = " SELECT DISTINCT
                        ID,
                        KEYID,
                        TEXT,
                        PARAGRAPH_NUMBER,
                        TYPE,
                        " . $this->db->IfNull('PARENT_ID',  $lawId). " AS PARENT_ID,
                        DISPLAY_ORDER
                    FROM ( WITH LLP AS (
                        SELECT
                            LLP.ID,
                            LLK.ID   KEYID,
                            LLP.TITLE AS TEXT,
                            PARAGRAPH_NUMBER,
                            NULL AS TYPE,
                            LLP.PARENT_ID AS PARENT_ID,
                            LLP.DISPLAY_ORDER
                        FROM LIS_LAW_PARAGRAPH LLP
                        INNER JOIN LIS_LAW_KEY LLK ON LLK.PARAGRAPH_ID = LLP.ID
                        WHERE LLK.LAW_ID = '$lawId'
                    )
                    SELECT
                        LLP.ID,
                        LLP.KEYID,
                        LLP.TEXT,
                        LLP.PARAGRAPH_NUMBER,
                        LLP.TYPE,
                        LLP.PARENT_ID,
                        LLP.DISPLAY_ORDER,
                        1 AS LVL
                    FROM LLP LLP
                    WHERE LLP.PARENT_ID IS NULL
                    UNION ALL
                        SELECT
                            LLP1.ID,
                            LLP1.KEYID,
                            LLP1.TEXT,
                            LLP1.PARAGRAPH_NUMBER,
                            LLP1.TYPE,
                            LLP1.PARENT_ID,
                            LLP1.DISPLAY_ORDER,
                            2 LVL
                        FROM LLP LLP
                        INNER JOIN LLP LLP1 ON LLP1.PARENT_ID = LLP.ID
                    UNION ALL
                        SELECT
                            LLP2.ID,
                            LLP2.KEYID,
                            LLP2.TEXT,
                            LLP2.PARAGRAPH_NUMBER,
                            LLP2.TYPE,
                            LLP2.PARENT_ID,
                            LLP2.DISPLAY_ORDER,
                            3 LVL
                        FROM LLP LLP
                        INNER JOIN LLP LLP1 ON LLP1.PARENT_ID = LLP.ID
                        INNER JOIN LLP LLP2 ON LLP2.PARENT_ID = LLP1.ID
                    UNION ALL
                        SELECT
                            LLP3.ID,
                            LLP3.KEYID,
                            LLP3.TEXT,
                            LLP3.PARAGRAPH_NUMBER,
                            LLP3.TYPE,
                            LLP3.PARENT_ID,
                            LLP3.DISPLAY_ORDER,
                            4 LVL
                        FROM LLP LLP
                        INNER JOIN LLP LLP1 ON LLP1.PARENT_ID = LLP.ID
                        INNER JOIN LLP LLP2 ON LLP2.PARENT_ID = LLP1.ID
                        INNER JOIN LLP LLP3 ON LLP3.PARENT_ID = LLP2.ID
                    UNION ALL
                        SELECT
                            LLP4.ID,
                            LLP4.KEYID,
                            LLP4.TEXT,
                            LLP4.PARAGRAPH_NUMBER,
                            LLP4.TYPE,
                            LLP4.PARENT_ID,
                            LLP4.DISPLAY_ORDER,
                            5 LVL
                        FROM LLP LLP
                        INNER JOIN LLP LLP1 ON LLP1.PARENT_ID = LLP.ID
                        INNER JOIN LLP LLP2 ON LLP2.PARENT_ID = LLP1.ID
                        INNER JOIN LLP LLP3 ON LLP3.PARENT_ID = LLP2.ID
                        INNER JOIN LLP LLP4 ON LLP4.PARENT_ID = LLP3.ID ) ORDER BY DISPLAY_ORDER";
        
        $res1 = $this->db->GetAll($qry1);
        $res = array_merge($res, $res1) ;
        
        $result = Arr::changeKeyLower(self::buildTree($res));
        return $result;
    }
    
    public function paragraphSave($postData = array()) {
        
        try {
            $sessionUserId = Ue::sessionUserId();
            $id = getUID();
            $paragraphKeyId = issetParam($postData['paragraphKeyId']);
            $idAddtion = issetParam($postData['idAddtion']);
            $isAddtion = issetParam($postData['isAddtion']);
            
            if (issetParam($postData['lawtypeid']) === '' || issetParam($postData['lawtypeid']) === '1' || issetParam($postData['lawtypeid']) === '2') {
                
                includeLib('Utils/Functions');
                $currentDate = Date::currentDate();
                $sessionUserKeyId = Ue::sessionUserId();
                
                $descAddtion = issetParam($postData['descAddtion']);
                $html = issetParam($postData['html']);
                
                if (strpos($html, '<table') === false) { 
                    $html = Str::htmltotext($html);
                }
                
                if (issetParam($postData['addParagraphId']) !== '') {
                    $html = $descAddtion;
                    $postData['html'] = $_POST['html'] = $postData['descAddtion'];
                    
                    $qry = "SELECT 
                                T0.ID,
                                T0.LAW_ID,
                                T0.PARAGRAPH_ID,
                                T0.VERSION_NUMBER,
                                T0.ACT_NUMBER,
                                T1.PARAGRAPH_TEXT,
                                T1.DESCRIPTION,
                                T1.DISPLAY_ORDER AS DISPLAY_ORDER,
                                T1.RELATED_ID,
                                T1.PARENT_ID,
                                T1.VERSION_NUMBER AS PVERSION_NUMBER,
                                T1.TITLE
                            FROM LIS_LAW_KEY T0 
                            LEFT JOIN LIS_LAW_PARAGRAPH T1 ON T0.PARAGRAPH_ID = T1.ID
                            WHERE T0.ID = '". $postData['addParagraphId'] ."'";
                    
                    $addData = $this->db->GetRow($qry);
                    $addData['DISPLAY_ORDER'] = Number::formatDecimal((float) $addData['DISPLAY_ORDER'] + 0.1, 1);
                    
                    $_paragraphId = getUID();
                    $param = array(
                        'ID' => $_paragraphId,
                        'PARAGRAPH_TYPE_ID' => '',
                        'LAW_ID' => $addData['LAW_ID'],
                        'TITLE' => $addData['TITLE'],
                        'PARAGRAPH_NUMBER' => '', //self::numberSplitText($html),
                        'PARENT_ID' => issetParam($addData['PARENT_ID']) == '' ? null : $addData['PARENT_ID'],
                        'IS_ACTIVE' => '1',
                        'CREATED_DATE' => $currentDate,
                        'CREATED_USER_ID' => $sessionUserId,
                        'VERSION_NUMBER' => '0',
                        'DISPLAY_ORDER' => $addData['DISPLAY_ORDER'],
                    );
                    
                    $result = $this->db->AutoExecute("LIS_LAW_PARAGRAPH", $param);
                    
                    if ($result) {
                        
                        $this->db->UpdateClob('LIS_LAW_PARAGRAPH', 'PARAGRAPH_TEXT', '', " ID = '$_paragraphId'");
                        $this->db->UpdateClob('LIS_LAW_PARAGRAPH', 'DESCRIPTION', '', " ID = '$_paragraphId'");
                        
                        $paragraphKeyId = getUID();
                        $paramKey = array(
                            'ID' => $paragraphKeyId,
                            'LAW_ID' => $addData['LAW_ID'],
                            'PARAGRAPH_ID' => $_paragraphId,
                            'ACT_NUMBER' => self::numberSplitText($html),
                            'CREATED_DATE' => $currentDate,
                            'CREATED_USER_ID' => $sessionUserId,
                            'IS_ACTIVE' => '1',
                            'VERSION_NUMBER' => '0'
                        );

                        $result = $this->db->AutoExecute("LIS_LAW_KEY", $paramKey);
                        $this->db->UpdateClob('LIS_LAW_KEY', 'BODY_TEXT', $html, " ID = '$paragraphKeyId' ");
                        
                    }
                }
                
                $postData['descAddtion'] = $descAddtion;
                $postData['html'] = $html;

                $qry = "SELECT 
                            T0.ID,
                            T0.LAW_ID,
                            T0.PARAGRAPH_ID,
                            T0.VERSION_NUMBER,
                            T0.ACT_NUMBER,
                            T1.PARAGRAPH_TEXT,
                            T1.DESCRIPTION,
                            T1.DISPLAY_ORDER,
                            T1.RELATED_ID,
                            T1.PARENT_ID,
                            T1.VERSION_NUMBER AS PVERSION_NUMBER
                        FROM LIS_LAW_KEY T0 
                        LEFT JOIN LIS_LAW_PARAGRAPH T1 ON T0.PARAGRAPH_ID = T1.ID
                        WHERE T0.ID = '$paragraphKeyId'";

                $oldData = $this->db->GetRow($qry);

                $oldLaw = self::copyLawParagraphByOldContent($postData, $currentDate, $sessionUserKeyId, $oldData);
                $newLaw = self::newLawParagraphSave($postData, $currentDate, $sessionUserKeyId, $oldData);
                
                if (is_array($idAddtion)) {
                    foreach ($idAddtion as $kAddtion) {
                        self::saveChangeBook($kAddtion, $paragraphKeyId, $id, $currentDate, $sessionUserKeyId, $oldData, $oldLaw, $newLaw, $html);
                    }
                } else {
                    self::saveChangeBook($idAddtion, $paragraphKeyId, $id, $currentDate, $sessionUserKeyId, $oldData, $oldLaw, $newLaw, $html);
                }

            } else {
                
                $data = array(
                    'ID' => $id,
                    'SRC_RECORD_ID' => $paragraphKeyId,
                    'TRG_RECORD_ID' => $idAddtion,
                    'SEMANTIC_TYPE_ID' => '2003',
                    'SRC_TABLE_NAME' => 'LIS_LAW_KEY',
                    'TRG_TABLE_NAME' => 'LIS_LAW_KEY'
                );

                $result = $this->db->AutoExecute('META_DM_RECORD_MAP', $data);

                if (!$result) {
                    throw new Exception("new META_DM_RECORD_MAP error!");
                }
                
            }

            $response = array('status' => 'success', 'text' => Lang::line('msg_save_success'));
            
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'text' => Lang::line('msg_save_error'), 'ex' => $ex->msg);
        }
        
        return $response;
    }
    
    public function saveChangeBook($idAddtion, $paragraphKeyId, $id, $currentDate, $sessionUserKeyId, $oldData, $oldLaw, $newLaw, $html) {
        $qry = "SELECT 
                    T0.ID,
                    T0.LAW_ID,
                    T0.PARAGRAPH_ID,
                    T0.VERSION_NUMBER,
                    T0.ACT_NUMBER,
                    T1.PARAGRAPH_TEXT,
                    T1.DESCRIPTION,
                    T1.DISPLAY_ORDER,
                    T1.RELATED_ID,
                    T1.VERSION_NUMBER AS PVERSION_NUMBER
                FROM LIS_LAW_KEY T0 
                LEFT JOIN LIS_LAW_PARAGRAPH T1 ON T0.PARAGRAPH_ID = T1.ID
                WHERE T0.ID = '$idAddtion'";
        $chooseData = $this->db->GetRow($qry);

        $data = array(
            'ID' => $id,
            'SRC_RECORD_ID' => $paragraphKeyId,
            'TRG_RECORD_ID' => $idAddtion,
            'SEMANTIC_TYPE_ID' => '2003',
            'SRC_TABLE_NAME' => 'LIS_LAW_KEY',
            'TRG_TABLE_NAME' => 'LIS_LAW_KEY'
        );

        $result = $this->db->AutoExecute('META_DM_RECORD_MAP', $data);

        if (!$result) {
            throw new Exception("new META_DM_RECORD_MAP error!");
        }

        $data = array(
            'ID' => $id,
            'BOOK_DATE' => $currentDate,
            'LAW_ID' => $oldData['LAW_ID'],
            'CHANGE_LAW_ID' => $chooseData['LAW_ID'],
            'CREATED_DATE' => $currentDate,
            'CREATED_USER_ID' => $sessionUserKeyId,
            'ROW_STATE_CODE' => '',
        );

        $result = $this->db->AutoExecute('LIS_LAW_CHANGE_BOOK', $data);

        if (!$result) {
            throw new Exception("new LIS_LAW_CHANGE_BOOK error!");
        }

        $result = $this->db->UpdateClob('LIS_LAW_CHANGE_BOOK', 'PREV_BODY_TEXT', $oldLaw, " ID = '". $id ."'");

        if (!$result) {
            throw new Exception("old LIS_LAW_CHANGE_BOOK PREV_BODY_TEXT error!");
        }

        $result = $this->db->UpdateClob('LIS_LAW_CHANGE_BOOK', 'NEXT_BODY_TEXT', $newLaw, " ID = '". $id ."'");

        if (!$result) {
            throw new Exception("old LIS_LAW_CHANGE_BOOK NEXT_BODY_TEXT error!");
        }

        $data = array(
            'ID' => $id,
            'BOOK_ID' => $id,
            'PARAGRAPH_NUMBER' => $oldData['ACT_NUMBER'],
            'MAP_ID' => $id,
        );

        $result = $this->db->AutoExecute('LIS_LAW_CHANGE_BOOK_DTL', $data);

        if (!$result) {
            throw new Exception("new LIS_LAW_CHANGE_BOOK_DTL error!");
        }

        $result = $this->db->UpdateClob('LIS_LAW_CHANGE_BOOK_DTL', 'PREV_PARAGRAPH_TEXT', $oldData['PARAGRAPH_TEXT'], " ID = '". $id ."'");

        if (!$result) {
            throw new Exception("old LIS_LAW_CHANGE_BOOK_DTL PREV_PARAGRAPH_TEXT error!");
        }

        $result = $this->db->UpdateClob('LIS_LAW_CHANGE_BOOK_DTL', 'NEXT_PARAGRAPH_TEXT', $html, " ID = '". $id ."'");

        if (!$result) {
            throw new Exception("old LIS_LAW_CHANGE_BOOK_DTL NEXT_PARAGRAPH_TEXT error!");
        }
        
        return true;
    }
    
    public function copyLawParagraphByOldContent($postData, $currentDate, $sessionUserKeyId, $data) {
        
        $paragraphKeyId = issetParam($postData['paragraphKeyId']);
        $isAddtion = issetParam($postData['isAddtion']);
        $idAddtion = issetParam($postData['idAddtion']);
        $descAddtion = issetParam($postData['descAddtion']);
        $html = issetParam($postData['html']);
        
        $pid = getUID();
        
        $pdata = array(
            'ID' => $pid,
            'LAW_ID' => $data['LAW_ID'],
            'RELATED_ID' => $data['RELATED_ID'],
            'PARENT_ID' => $data['PARENT_ID'],
            'DISPLAY_ORDER' => $data['DISPLAY_ORDER'],
            'VERSION_NUMBER' => issetParamZero($data['PVERSION_NUMBER']),
            'IS_ACTIVE' => '0',
            'CREATED_DATE' => $currentDate,
            'CREATED_USER_ID' => $sessionUserKeyId,
        );

        $result = $this->db->AutoExecute('LIS_LAW_PARAGRAPH', $pdata);

        if (!$result) {
            throw new Exception("old LIS_LAW_PARAGRAPH error!");
        }

        $result = $this->db->UpdateClob('LIS_LAW_PARAGRAPH', 'PARAGRAPH_TEXT', $data['PARAGRAPH_TEXT'], " ID = '$pid'");
        $result = $this->db->UpdateClob('LIS_LAW_PARAGRAPH', 'DESCRIPTION', $data['DESCRIPTION'], " ID = '$pid'");

        if (!$result) {
            throw new Exception("old LIS_LAW_PARAGRAPH PARAGRAPH_TEXT error!");
        }
        
        $id = getUID();
        $kdata = array(
            'ID' => $id,
            'LAW_ID' => $data['LAW_ID'],
            'PARAGRAPH_ID' => $pid,
            'VERSION_NUMBER' => issetParamZero($data['VERSION_NUMBER']),
            'ACT_NUMBER' => $data['ACT_NUMBER'],
            'IS_ACTIVE' => '0',
            'CREATED_DATE' => $currentDate,
            'CREATED_USER_ID' => $sessionUserKeyId,
        );

        $result = $this->db->AutoExecute('LIS_LAW_KEY', $kdata);

        if (!$result) {
            throw new Exception("old LIS_LAW_KEY error!");
        }
        
        return self::getDataLawContentModel($data['LAW_ID'], array(), false, false, true);
    }
    
    public function newLawParagraphSave($postData, $currentDate, $sessionUserKeyId, $data, $inactive = '0') {
        
        $paragraphKeyId = issetParam($postData['paragraphKeyId']);
        $isAddtion = issetParam($postData['isAddtion']);
        $idAddtion = issetParam($postData['idAddtion']);
        $descAddtion = issetParam($postData['descAddtion']);
        $html = issetParam($postData['html']);
        
        $actnumber = $this->numberSplitText($descAddtion);
        
        $newParag = array(
            'VERSION_NUMBER' => issetParamZero($data['PVERSION_NUMBER']) + 1,
            'IN_ACTIVE' => $inactive
        );
        
        /*
        if (!is_numeric($actnumber)) {
            $descAddtion = $newParag['VERSION_NUMBER'] . '. ' . $descAddtion;
        }
        */
        
        $result = $this->db->AutoExecute('LIS_LAW_PARAGRAPH', $newParag, 'UPDATE', "ID = '". $data['PARAGRAPH_ID'] ."'");

        if (!$result) {
            throw new Exception("new LIS_LAW_PARAGRAPH error!");
        }
        
        $result = $this->db->UpdateClob('LIS_LAW_PARAGRAPH', 'PARAGRAPH_TEXT', $html, " ID = '". $data['PARAGRAPH_ID'] ."'");
        $result = $this->db->UpdateClob('LIS_LAW_PARAGRAPH', 'DESCRIPTION', $descAddtion, " ID = '". $data['PARAGRAPH_ID'] ."'");

        if (!$result) {
            throw new Exception("new LIS_LAW_PARAGRAPH PARAGRAPH_TEXT error!");
        }

        $newKey = array(
            'VERSION_NUMBER' => issetParamZero($data['VERSION_NUMBER']) + 1,
        );

        $result = $this->db->AutoExecute('LIS_LAW_KEY', $newKey, 'UPDATE', "ID = '". $data['ID'] ."'");

        if (!$result) {
            throw new Exception("new LIS_LAW_KEY error!");
        }
        
        return self::getDataLawContentModel($data['LAW_ID'], array(), false, false, true);
    }
    
    public function lawFormatterToJson($lawhtml) {
        $law = explode('%split', $lawhtml);
        
        (Array)  $lawTemp = $lawTempArr = array();
        (String) $tmpKey = '';
        
        /*
        * 1. buleg             БҮЛЭГ
        * 2. subpart           ДЭД БҮЛЭГ
        * 3. zuil              ЗҮЙЛ
        * 1. part              ХЭСЭГ --------- сонгууль
        * 1. angi              АНГИ --------- шийдвэр
        */

        (Array) $lawKey = array('buleg' => '/Р БҮЛЭГ/i', 'subpart' => '/ДЭД БҮЛЭГ/i', 'zuil' => '/р зүйл[.]/i', 'part' => '/ХЭСЭГ /i', 'angi' => '/АНГИ /i');
        
        foreach ($lawKey as $key => $row) {
            if (preg_match($row, $lawhtml) && !$tmpKey) {
                $tmpKey = $row;
                unset($lawKey[$key]);
            }
        }
        
        if ($tmpKey) {
            $lawTemp['header_array'] = $lawTemp['footer_array'] = '';
            $isheader = true;
            $pindex = $index = 0;
            
            $isheader = true;
            $pindex = $index = 0;
            
            foreach ($law as $key => $row) {
                if ($row) {
                    if (strpos($row, '<img') !== false) {
                        preg_match_all('/<img[^>]+>/i',$row, $imgTags); 
                        $imgsrc = '';

                        for ($i = 0; $i < count($imgTags[0]); $i++) {
                            preg_match('/src="([^"]+)/i',$imgTags[0][$i], $imgage);
                            $origImageSrc = str_ireplace( 'src="', '',  $imgage[0]);
                            $imgsrc .= '<img src="'. $origImageSrc .'" style="width: auto; height: auto;" />';
                        }

                        $row = $imgsrc . Str::htmltotext($row);
                    } elseif (strpos($row, '<table') === false) {
                        $row = Str::htmltotext($row);;
                    }
                    
                    if (preg_match($tmpKey, $row)) {
                        array_push($lawTempArr, $row);
                        $lawTemp[$index] = array('text' => $row, 'child' => array());
                        $isheader = false;
                        $pindex = $index;
                        $index++;
                    } elseif (preg_match('/^МОНГОЛ УЛСЫН ИХ ХУРЛЫН ДАРГА/i', $row) || preg_match('/^МОНГОЛ УЛСЫН ИХ ХУРЛЫН ДЭД ДАРГА/i',
                        $row) || preg_match('/БАГА ХУРЛЫН ДАРГА/i', $row) || preg_match('/ХУРЛЫН НАРИЙН БИЧГИЙН ДАРГА/i',
                        $row) || preg_match('/ХУРЛЫН ТАМГЫН ГАЗРЫН ЕРӨНХИЙ НАРИЙН БИЧГИЙН ДАРГА/i',
                        $row) || preg_match('/ХУРЛЫН ТАМГЫН ГАЗРЫН НАРИЙН БИЧГИЙН ДАРГА/i',
                        $row) || preg_match('/ХУРЛЫН ТЭРГҮҮЛЭГЧДИЙН ДАРГА/i', $row)) {
                            $lawTemp['footer_array'] = ($lawTemp['footer_array']) ? $lawTemp['footer_array'] . '&nbsp;' . $row : $row;
                    }  elseif ($isheader) {
                        $lawTemp['header_array'] = ($lawTemp['header_array']) ? $lawTemp['header_array'] . '&nbsp;' . $row : $row;
                    } else {
                        array_push($lawTemp[$pindex]['child'], $row);
                    }
                }
            }
            
            $tmpKey = '';
            
            foreach ($lawKey as $key => $row) {
                if (preg_match($row, $lawhtml) && !$tmpKey) {
                    $tmpKey = $row;
                    unset($lawKey[$key]);
                }
            }
            
            if ($tmpKey) {
                $lawTemp1 = array();
                
                foreach ($lawTemp as $key => $row) {
                    
                    if (isset($row['child']) && $key !== 'footer_array' && $key !== 'header_array') {
                        if (!isset($row['children'])) {
                            $row['children'] = array();
                        }
                        $index = $pindex = 0;
                        
                        foreach ($row['child'] as $ckey => $crow) {
                            if (preg_match($tmpKey, $crow)) {
                                if (!isset($row['children'][$pindex])) {
                                    $row['children'][$pindex] = array();
                                }
                                
                                $row['children'][$index] = array('text' => $crow, 'child' => array());
                                $pindex = $index;
                                $index++;
                            } else {
                                if (!isset($row['children'][$pindex])) {
                                    $row['children'][$pindex] = array('text' => $crow, 'child' => array());
                                    $index++;
                                } else {
                                    array_push($row['children'][$pindex]['child'], $crow);
                                }
                            }
                        }
                        
                        $row['child'] = $row['children'];
                        unset($row['children']);
                    }
                    
                    if ($key !== 'footer_array' && $key !== 'header_array') {
                        array_push($lawTemp1, $row);
                    } else {
                        $lawTemp1[$key] = str_replace('   ', '', $row);
                    }
                }
                
                $lawTemp = $lawTemp1;
            }
            
        } else {
            $lawTemp['header_array'] = $lawTemp['footer_array'] = '';
            $pindex = $index = 0;
            foreach ($law as $key => $row) {
                if (strpos($row, '<img') !== false) {
                    preg_match_all('/<img[^>]+>/i',$row, $imgTags); 
                    $imgsrc = '';

                    for ($i = 0; $i < count($imgTags[0]); $i++) {
                        preg_match('/src="([^"]+)/i',$imgTags[0][$i], $imgage);
                        $origImageSrc = str_ireplace( 'src="', '',  $imgage[0]);
                        $imgsrc .= '<img src="'. $origImageSrc .'" style="width: auto; height: auto;" />';
                    }

                    $row = $imgsrc . Str::htmltotext($row);
                } elseif (strpos($row, '<table') === false) {
                    $row = Str::htmltotext($row);;
                }
                
                if ($row) {
                    if (preg_match('/^МОНГОЛ УЛСЫН ИХ ХУРЛЫН ДАРГА/i', $row) || preg_match('/^МОНГОЛ УЛСЫН ИХ ХУРЛЫН ДЭД ДАРГА/i',
                        $row) || preg_match('/БАГА ХУРЛЫН ДАРГА/i', $row) || preg_match('/ХУРЛЫН НАРИЙН БИЧГИЙН ДАРГА/i',
                        $row) || preg_match('/ХУРЛЫН ТАМГЫН ГАЗРЫН ЕРӨНХИЙ НАРИЙН БИЧГИЙН ДАРГА/i',
                        $row) || preg_match('/ХУРЛЫН ТАМГЫН ГАЗРЫН НАРИЙН БИЧГИЙН ДАРГА/i',
                        $row) || preg_match('/ХУРЛЫН ТЭРГҮҮЛЭГЧДИЙН ДАРГА/i', $row)) {
                            $lawTemp['footer_array'] = $lawTemp['footer_array'] . '&nbsp;' . $row;
                    } elseif ($key < 3) {
                        $lawTemp['header_array'] = $lawTemp['header_array'] . '&nbsp;' . $row;
                    } else {
                        $lawTemp[$index]['text'] = $row;
                        $index++;
                    }
                }
            }
        }
        
        $arr = $lawTemp;
        return $arr;
        
    }
    
    public function lawFormatterToJson1($law, $lawhtml) {
        
        (Array)  $lawTemp = $lawTempArr = array();
        (String) $tmpKey = '';
        
        /*
        * 1. buleg             БҮЛЭГ
        * 2. subpart           ДЭД БҮЛЭГ
        * 3. zuil              ЗҮЙЛ
        * 1. part              ХЭСЭГ --------- сонгууль
        * 1. angi              АНГИ --------- шийдвэр
        */

        (Array) $lawKey = array('buleg' => '/Р БҮЛЭГ/i', 'subpart' => '/ДЭД БҮЛЭГ/i', 'zuil' => '/р зүйл[.]/i', 'part' => '/ХЭСЭГ /i', 'angi' => '/АНГИ /i');
        
        foreach ($lawKey as $key => $row) {
            if (preg_match($row, $lawhtml) && !$tmpKey) {
                $tmpKey = $row;
                unset($lawKey[$key]);
            }
        }
        
        if ($tmpKey) {
            $lawTemp['header_array'] = $lawTemp['footer_array'] = '';
            $isheader = true;
            $pindex = $index = 0;
            
            $isheader = true;
            $pindex = $index = 0;
            
            foreach ($law as $key => $row) {
                if ($row) {
                    if (strpos($row, '<img') !== false) {
                        preg_match_all('/<img[^>]+>/i',$row, $imgTags); 
                        $imgsrc = '';

                        for ($i = 0; $i < count($imgTags[0]); $i++) {
                            preg_match('/src="([^"]+)/i',$imgTags[0][$i], $imgage);
                            $origImageSrc = str_ireplace( 'src="', '',  $imgage[0]);
                            $imgsrc .= '<img src="'. $origImageSrc .'" style="width: auto; height: auto;" />';
                        }

                        $row = $imgsrc . Str::htmltotext($row);
                    } elseif (strpos($row, '<table') === false) {
                        $row = Str::htmltotext($row);;
                    }
                    
                    if (preg_match($tmpKey, $row)) {
                        array_push($lawTempArr, $row);
                        $lawTemp[$index] = array('text' => $row, 'child' => array());
                        $isheader = false;
                        $pindex = $index;
                        $index++;
                    } elseif (preg_match('/^МОНГОЛ УЛСЫН ИХ ХУРЛЫН ДАРГА/i', $row) || preg_match('/^МОНГОЛ УЛСЫН ИХ ХУРЛЫН ДЭД ДАРГА/i',
                        $row) || preg_match('/БАГА ХУРЛЫН ДАРГА/i', $row) || preg_match('/ХУРЛЫН НАРИЙН БИЧГИЙН ДАРГА/i',
                        $row) || preg_match('/ХУРЛЫН ТАМГЫН ГАЗРЫН ЕРӨНХИЙ НАРИЙН БИЧГИЙН ДАРГА/i',
                        $row) || preg_match('/ХУРЛЫН ТАМГЫН ГАЗРЫН НАРИЙН БИЧГИЙН ДАРГА/i',
                        $row) || preg_match('/ХУРЛЫН ТЭРГҮҮЛЭГЧДИЙН ДАРГА/i', $row)) {
                            $lawTemp['footer_array'] = ($lawTemp['footer_array']) ? $lawTemp['footer_array'] . '&nbsp;' . $row : $row;
                    }  elseif ($isheader) {
                        $lawTemp['header_array'] = ($lawTemp['header_array']) ? $lawTemp['header_array'] . '<br> ' . Str::htmltotext($row) : $row;
                    } else {
                        array_push($lawTemp[$pindex]['child'], $row);
                    }
                }
            }
            
            $tmpKey = '';
            
            foreach ($lawKey as $key => $row) {
                if (preg_match($row, $lawhtml) && !$tmpKey) {
                    $tmpKey = $row;
                    unset($lawKey[$key]);
                }
            }
            
            if ($tmpKey) {
                $lawTemp1 = array();
                
                foreach ($lawTemp as $key => $row) {
                    
                    if (isset($row['child']) && $key !== 'footer_array' && $key !== 'header_array') {
                        if (!isset($row['children'])) {
                            $row['children'] = array();
                        }
                        $index = $pindex = 0;
                        
                        foreach ($row['child'] as $ckey => $crow) {
                            if (preg_match($tmpKey, $crow)) {
                                if (!isset($row['children'][$pindex])) {
                                    $row['children'][$pindex] = array();
                                }
                                
                                $row['children'][$index] = array('text' => $crow, 'child' => array());
                                $pindex = $index;
                                $index++;
                            } else {
                                if (!isset($row['children'][$pindex])) {
                                    $row['children'][$pindex] = array('text' => $crow, 'child' => array());
                                    $index++;
                                } else {
                                    array_push($row['children'][$pindex]['child'], $crow);
                                }
                            }
                        }
                        
                        $row['child'] = $row['children'];
                        unset($row['children']);
                    }
                    
                    if ($key !== 'footer_array' && $key !== 'header_array') {
                        array_push($lawTemp1, $row);
                    } else {
                        $lawTemp1[$key] = str_replace('   ', '', $row);
                    }
                }
                
                $lawTemp = $lawTemp1;
            }
            
        } else {
            $lawTemp['header_array'] = $lawTemp['footer_array'] = '';
            $pindex = $index = 0;
            foreach ($law as $key => $row) {
                if (strpos($row, '<img') !== false) {
                    preg_match_all('/<img[^>]+>/i',$row, $imgTags); 
                    $imgsrc = '';

                    for ($i = 0; $i < count($imgTags[0]); $i++) {
                        preg_match('/src="([^"]+)/i',$imgTags[0][$i], $imgage);
                        $origImageSrc = str_ireplace( 'src="', '',  $imgage[0]);
                        $imgsrc .= '<img src="'. $origImageSrc .'" style="width: auto; height: auto;" />';
                    }

                    $row = $imgsrc . Str::htmltotext($row);
                } elseif (strpos($row, '<table') === false) {
                    $row = Str::htmltotext($row);;
                }
                
                if ($row) {
                    if (preg_match('/^МОНГОЛ УЛСЫН ИХ ХУРЛЫН ДАРГА/i', $row) || preg_match('/^МОНГОЛ УЛСЫН ИХ ХУРЛЫН ДЭД ДАРГА/i',
                        $row) || preg_match('/БАГА ХУРЛЫН ДАРГА/i', $row) || preg_match('/ХУРЛЫН НАРИЙН БИЧГИЙН ДАРГА/i',
                        $row) || preg_match('/ХУРЛЫН ТАМГЫН ГАЗРЫН ЕРӨНХИЙ НАРИЙН БИЧГИЙН ДАРГА/i',
                        $row) || preg_match('/ХУРЛЫН ТАМГЫН ГАЗРЫН НАРИЙН БИЧГИЙН ДАРГА/i',
                        $row) || preg_match('/ХУРЛЫН ТЭРГҮҮЛЭГЧДИЙН ДАРГА/i', $row)) {
                            $lawTemp['footer_array'] = $lawTemp['footer_array'] . '&nbsp;' . $row;
                    } elseif ($key < 3) {
                        $lawTemp['header_array'] = $lawTemp['header_array'] . '<br>' . Str::htmltotext($row) ;
                    } else {
                        $lawTemp[$index]['text'] = $row;
                        $index++;
                    }
                }
            }
        }
        
        $arr = $lawTemp;
        return $arr;
        
    }

    public function saveLawProcessModel_1($lawP, $htmlP) {
        
        (Array) $response = array('status' => 'error', 'text' => Lang::line('msg_save_error'));
        (Array) $postData = Input::postData();
        $sessionUserId = Ue::sessionUserKeyId();
        $currentDate = Date::currentDate();
        
        try {
            
            $lawId = $postData['lawId'];
            $content = $htmlP;
            $lawArr = self::lawFormatterToJson1($lawP, $htmlP);

            (Array) $data_temp = $data_temp1 = array();
            $index = $parentindex = $cindex = $ccindex = 0;
            
            $result = ($lawArr['header_array']) ? $this->db->UpdateClob('LIS_LAW', 'LAW_HEADER', $lawArr['header_array'], " ID = '$lawId' ") : $result;
            $result = ($lawArr['footer_array']) ? $this->db->UpdateClob('LIS_LAW', 'LAW_FOOTER', $lawArr['footer_array'], " ID = '$lawId' ") : $result;
            
            unset($lawArr['header_array']);
            unset($lawArr['footer_array']);
             
            if (!$result) {
                throw new Exception("Body text алдаа заасан!");;
            }
            
            $result = $this->db->Execute("DELETE FROM LIS_LAW_KEY WHERE LAW_ID = '$lawId'");
            if (!$result) {
                throw new Exception("Бүх KEY REMOVE алдаа заасан!");;
            }
            
            $result = $this->db->Execute("DELETE FROM LIS_LAW_PARAGRAPH WHERE LAW_ID = '$lawId'");
            if (!$result) {
                throw new Exception("Бүх PARAGRAPH REMOVE алдаа заасан!");;
            }
            
            $lawKeyId = getUID();
            $param = array(
                'ID' => $lawKeyId,
                'LAW_ID' => $lawId,
                'CREATED_DATE' => $currentDate,
                'CREATED_USER_ID' => $sessionUserId,
                'VERSION_NUMBER' => '0',
                'IS_ACTIVE' => '1'
            );
            
            /* 
             * uussen 1 LAW deer BODY-oo butneer n 1 KEY uusne;
             * 
             */
            
            $result = $this->db->AutoExecute("LIS_LAW_KEY", $param);
            
            if (!$result) {
                throw new Exception("key insert алдаа заасан!");;
            }
            
            $this->db->UpdateClob('LIS_LAW_KEY', 'BODY_TEXT', $content, " ID = '$lawKeyId' ");
            
            /* 
             * daraa n KEY deeree tulguurlaad PARAGRAPH uusne
             * 
             */
            
            (Array) $lawKey = array('subpart' => '/ДЭД БҮЛЭГ/i', 'part' => '/ХЭСЭГ /i', 'angi' => '/АНГИ /i', 'buleg' => '/Р БҮЛЭГ/i', 'zuil' => '/р зүйл[.]/i');
            $displayOrder = 1;
            
            foreach ($lawArr as $key => $row) {
                
                $paragraphId = getUID();
                $paragraphNumber = '';
                
                $title = Str::htmltotext($row['text']);
                
                if (strpos($row['text'], '<table') !== false) {
                    $title = 'Хүснэгт';
                }
                
                if (strpos($row['text'], '<img') !== false) {
                    $title = 'Зураг';
                }
                
                if ($title) {
                    $param = array(
                        'ID' => $paragraphId,
                        'PARAGRAPH_TYPE_ID' => '',
                        'LAW_ID' => $lawId,
                        'PARAGRAPH_NUMBER' => $paragraphNumber,
                        'PARENT_ID' => null,
                        'IS_ACTIVE' => '1',
                        'TITLE' => $title,
                        'CREATED_DATE' => $currentDate,
                        'CREATED_USER_ID' => $sessionUserId,
                        'VERSION_NUMBER' => '0',
                        'DISPLAY_ORDER' => $displayOrder++
                    );

                    $result = $this->db->AutoExecute("LIS_LAW_PARAGRAPH", $param);

                    if ($result) {

                        $this->db->UpdateClob('LIS_LAW_PARAGRAPH', 'PARAGRAPH_TEXT', $row['text'], " ID = '$paragraphId' ");

                        $plawKeyId = getUID();
                        $paramKey = array(
                            'ID' => $plawKeyId,
                            'LAW_ID' => $lawId,
                            'PARAGRAPH_ID' => $paragraphId,
                            'ACT_NUMBER' => $paragraphNumber,
                            'CREATED_DATE' => $currentDate,
                            'CREATED_USER_ID' => $sessionUserId,
                            'IS_ACTIVE' => '1',
                            'VERSION_NUMBER' => '0'
                        );

                        $result = $this->db->AutoExecute("LIS_LAW_KEY", $paramKey);
                        $this->db->UpdateClob('LIS_LAW_KEY', 'BODY_TEXT', $row['text'], " ID = '$plawKeyId' ");

                        if (isset($row['child'])) {
                            $displayOrder = self::saveChildLaw($row['child'], $paragraphId, $lawId, $displayOrder++, $sessionUserId, $currentDate);
                        }
                    }
                }

            }
            
            $response = array('status' => 'success', 'text' => Lang::line('msg_save_success'));
            
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'text' => Lang::line('msg_save_error'), 'ex' => $ex->msg);
        }
        
        return $response;
    }
    
    public function generateContentModelV2($html) {
        $html = cleanOut($html);
        $html = cleanOut($html);
        $html = asci2uni($html);
        $tag = $editLaw = $printLaw = $rootLawId =  false;
        $old_char = array('<sup>', '</sup>', '</p>', '</div>', '&nbsp;', '<br />', '</h1>', '</h2>');
        $new_char = array('@a', '@b', '<br>', '<br>', '', '<br/>', '<br/>', '<br/>');

        $html = str_replace($old_char, $new_char, $html);

        $html = strip_tags($html, '<br><a><strike><em><i><table><thead><tbody><tfood><tr><th><td><img><ol><li>');
        $html = preg_replace('/\s\s+/', ' ', $html);
        $html = preg_replace('#(<br */?>\s*)+#i', '<br/>', $html);
        $html = trim($html);
        $html = str_replace(array('<em>. </em>', '<em>.</em>'), '. ', $html);

        $law_arr = explode('<br/>', $html);
        
        $header = issetParam($law_arr['header']);
        $footer = issetParam($law_arr['footer']);
        
        $i = 0;
        $z = 0;
        $tablaa = '';
        
        $addinclass = $editLaw ? '' : 'noedit';
        $addinclass = $rootLawId ? $addinclass : 'editonly ' . $addinclass;
        
        $tab1 = '<section style="text-indent:.5in;padding-left: 5rem;text-indent: unset"  class="drag-drop-law '. $addinclass .'">' . $tablaa . '%s</section>';
        $tab2 = '<section style="text-indent:1.0in;padding-left: 6rem;text-indent: unset" class="drag-drop-law '. $addinclass .'">' . $tablaa . $tablaa . '%s</section>';
        $tab3 = '<section style="text-indent:1.5in;padding-left: 7rem;text-indent: unset" class="drag-drop-law '. $addinclass .'">' . $tablaa . $tablaa . $tablaa . '%s</section>';
        
        (Array) $law = array();
        (Array) $tempArray = array();
        
        foreach ($law_arr as $lkey => $law_mor) {
            $addinclass1 = '';
            if ($lkey !== 'header' && $lkey !== 'footer') {
                
                (String) $datapath = ($tag) ? 'data-paragraph-keyid="'. $lkey .'"' : "";
                array_pop($law_arr);
                
                if (mb_strlen($law_mor) > 2 && !empty($law_mor) && strpos($law_mor, '<img') === false) {
                    
                    $firstnum = abs($law_mor);
                    if (preg_match('/р зүйл[.]/i', $law_mor)) {
                        $i = $i + 1;
                        $law['zuil_' . $i]['name'] = $law_mor . '<br/><section></section>';
                        $law['zuil_' . $i]['paragraphid'] = $lkey;
                    } else if (preg_match('/Р БҮЛЭГ/i', $law_mor)) {
                        $i = $i + 1;
                        $law['buleg_' . $i]['name'] = '<br/><br/>' . $law_mor . '<br/><section></section>';
                        $law['buleg_' . $i]['paragraphid'] = $lkey;
                    } else if (preg_match('/АНГИ /i', $law_mor)) {
                        $i = $i + 1;
                        $law['angi_' . $i]['name'] = $law_mor . '<br/><section></section>';
                        $law['angi_' . $i]['paragraphid'] = $lkey;
                    } else if (preg_match('/ХЭСЭГ /i', $law_mor)) {
                        $i = $i + 1;
                        $law['part_' . $i]['name'] = $law_mor . '<br/><section></section>';
                        $law['part_' . $i]['paragraphid'] = $lkey;
                    } else if (preg_match('/ДЭД БҮЛЭГ/i', $law_mor) || preg_match('/дэд бүлэг/i', $law_mor)) {
                        $i = $i + 1;
                        $law['subpart_' . $i]['name'] = $law_mor . '<br/><section></section>';
                        $law['subpart_' . $i]['paragraphid'] = $lkey;
                    } else if (preg_match('/^МОНГОЛ УЛСЫН ИХ ХУРЛЫН ДАРГА/i', $law_mor) || preg_match('/^МОНГОЛ УЛСЫН ИХ ХУРЛЫН ДЭД ДАРГА/i',
                                    $law_mor) || preg_match('/БАГА ХУРЛЫН ДАРГА/i', $law_mor) || preg_match('/ХУРЛЫН НАРИЙН БИЧГИЙН ДАРГА/i',
                                    $law_mor) || preg_match('/ХУРЛЫН ТАМГЫН ГАЗРЫН ЕРӨНХИЙ НАРИЙН БИЧГИЙН ДАРГА/i',
                                    $law_mor) || preg_match('/ХУРЛЫН ТАМГЫН ГАЗРЫН НАРИЙН БИЧГИЙН ДАРГА/i',
                                    $law_mor) || preg_match('/ХУРЛЫН ТЭРГҮҮЛЭГЧДИЙН ДАРГА/i', $law_mor)) {
                        $i = $i + 1;
                        
                        if ($tag) {
                            $law_mor = str_replace('&nbsp;', '', $law_mor);
                        }
                        
                        $law_mor = str_replace("ДАРГА",
                                "ДАРГА&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
                                $law_mor);
                        $law['footer' . $i] = $law_mor . '<br/>';
                    } else if ((preg_match('/\d{4}\sоны\s\d{1,2}\s(дүгээр|дугаар)\sсарын\s\d{1,2}[-](ны|ний)\sөдөр\s(Улаанбаатар хот|Төрийн ордон(.|,) Улаанбаатар хот)/i',
                                    $law_mor) || preg_match('/^\d{4}\sоны\s\d{1,2}\sсарын\s\d{1,2}\sөдөр\s(Улаанбаатар хот|Төрийн ордон(.|,) Улаанбаатар хот)$/i',
                                    $law_mor))) {

                        $lawrow1 = $lawrow2 = $lawrow3 = $lawrow4 = $law_mor;
                        preg_match('/\d{4}\sоны\s\d{1,2}\s(дүгээр|дугаар)\sсарын\s\d{1,2}[-](ны|ний)\sөдөр/i',
                                $lawrow1, $match1);
                        preg_match('/\d{4}\sоны\s\d{1,2}\sсарын\s\d{1,2}\sөдөр/i', $lawrow2, $match2);
                        preg_match('/Улаанбаатар хот/i', $lawrow3, $match3);
                        preg_match('/Төрийн ордон(.|,) Улаанбаатар хот/i', $lawrow4, $match4);

                        $i = $i + 1;
                        if (!empty($match1[0])) {
                            $law['date' . $i] = $match1[0] . '<br/>';
                        } else {
                            $law['date' . $i] = $match2[0] . '<br/>';
                        }
                        if (!empty($match4[0])) {
                            $law['position' . ($i + 1)] = $match4[0] . '<br/>';
                        } else {
                            $law['position' . ($i + 1)] = $match3[0] . '<br/>';
                        }
                    } else if ((preg_match('/\d{4}\sоны\s\d{1,2}\s(дүгээр|дугаар)\sсарын\s\d{1,2}[-](ны|ний)\sөдөр\sДугаар\s\d{1,2}\s(Улаанбаатар хот|Төрийн ордон(.|,) Улаанбаатар хот)/i',
                                    $law_mor) || preg_match('/^\d{4}\sоны\s\d{1,2}\sсарын\s\d{1,2}\sөдөр\sДугаар\s\d{1,2}\s(Улаанбаатар хот|Төрийн ордон(.|,) Улаанбаатар хот)$/i',
                                    $law_mor))) {

                        $lawrow1 = $lawrow2 = $lawrow3 = $lawrow4 = $lawrow5 = $law_mor;
                        preg_match('/\d{4}\sоны\s\d{1,2}\s(дүгээр|дугаар)\sсарын\s\d{1,2}[-](ны|ний)\sөдөр/i',
                                $lawrow1, $match1);
                        preg_match('/\d{4}\sоны\s\d{1,2}\sсарын\s\d{1,2}\sөдөр/i', $lawrow2, $match2);
                        preg_match('/Улаанбаатар хот/i', $lawrow3, $match3);
                        preg_match('/Төрийн ордон(.|,) Улаанбаатар хот/i', $lawrow4, $match4);
                        preg_match('/Дугаар\s\d{1,2}/i', $lawrow5, $match5);

                        $i = $i + 1;
                        if (!empty($match1[0])) {
                            $law['date' . $i] = $match1[0] . '<br/>';
                        } else {
                            $law['date' . $i] = $match2[0] . '<br/>';
                        }

                        if (!empty($match5[0])) {
                            $law['number' . $i] = $match5[0] . '<br/>';
                        } else {
                            $law['number' . $i] = '';
                        }

                        if (!empty($match4[0])) {
                            $law['position' . ($i + 1)] = $match4[0] . '<br/>';
                        } else {
                            $law['position' . ($i + 1)] = $match3[0] . '<br/>';
                        }
                    } else if (preg_match('/\d{4}\sоны\s\d{1,2}\s(дүгээр|дугаар)\sсарын\s\d{1,2}[-](ны|ний)\sөдөр/i',
                                    $law_mor) || preg_match('/^\d{4}\sоны\s\d{1,2}\sсарын\s\d{1,2}\sөдөр$/i',
                                    $law_mor)) {
                        if ($z == 1 || $z == 2) {
                            $i = $i + 1;
                            $law['date' . $i] = $law_mor . '<br/>';
                        } else {
                            $i = $i;
                            if (isset($law[$i])) {
                                $law[$i] .= $law_mor;
                            } else {
                                
                            }
                        }
                    } else if (preg_match('/Улаанбаатар хот/i', $law_mor) || preg_match('/Төрийн ордон(.|,) Улаанбаатар хот/i',
                                    $law_mor)) {
                        if ($z == 2 || $z == 3) {
                            $i = $i + 1;
                            $law['position' . $i] = $law_mor . '<br/>';
                        } else {
                            $i = $i;
                            if (isset($law[$i])) {
                                $law[$i] .= $law_mor;
                            } else {
                                
                            }
                            
                        }
                    } else if (preg_match('/ХУУЛЬ$/i', $law_mor)) {
                        $i = $i + 1;
                        $law['cccc_' . $i] = $law_mor . '<br/>';
                    } else {
                        if (strpos($law_mor, '<table') === false) {
                            if (preg_match('/\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]/i', $law_mor)) {
                                $law_mor = str_replace("%s", $law_mor, $tab3);
                            } else if (preg_match('/\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]/i', $law_mor)) {
                                $law_mor = str_replace("%s", $law_mor, $tab2);
                            } else if (preg_match('/\d{1,3}[.]\d{1,3}[.]/i', $law_mor)) {
                                $law_mor = str_replace("%s", $law_mor, $tab1);
                            } else if (preg_match('/\d{1,3}(\/)/i', $law_mor)) {
                                $law_mor = str_replace("%s", $law_mor, $tab2);
                            } else if (preg_match('/\d{1,3}[.]/i', $law_mor)) {
                                $law_mor = str_replace("%s", $law_mor, $tab1);
                            } else if (preg_match('/^<[a]/i', $law_mor) || preg_match('/^\[\d\]/i', $law_mor)) {
                                $law_mor = $law_mor . '<br/>';
                            } else if (preg_match('/^<ol>/i', $law_mor)) {
                                $law_mor = $law_mor . '<br/>';
                            } else if (($z < 10 || $z == 0) && empty($law['zuil_' . $i]['name'])) {
                                $law_mor = '<section '. $datapath .' style="text-align:center;font-weight:bold" class="drag-drop-law '. $addinclass .'">' . $law_mor . '</section>';
                            }
                        }

                        if (isset($law[$i])) {
                            if (strpos($law_mor, 'table') !== false && strpos($law_mor, 'addition-changebookfull') === false) {
                                $law[$i] .= '<section '. $datapath .' class="drag-drop-law-table '. $addinclass .'">' . $law_mor . '</section>';
                            } else {
                                if (strpos($law_mor, 'drag-drop-law') === false ) {
                                    $law[$i] .= '<section '. $datapath .' style="text-indent:.5in;padding-left: 5rem;text-indent: unset;" class="drag-drop-law '. $addinclass . ' ' . $addinclass1 .'">' . $law_mor . '</section>';
                                } else {
                                    $law[$i] .= $law_mor;
                                }
                            }
                        } else {
                            if (preg_match('/\d{1,3}[.]\d{1,3}[.]/i', $law_mor) || preg_match('/\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]/i',
                                            $law_mor) || preg_match('/\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]/i',
                                            $law_mor)) {
                                $law[$i] = $law_mor;
                            } else if (is_html($law_mor)) {
                                $law[$i] = $law_mor;
                            } else {
                                $law[$i] = $law_mor . '<br/>';
                            }
                        }
                    }
                    
                } elseif(mb_strlen($law_mor) > 2 && !empty($law_mor) && strpos($law_mor, '<img') !== false) {
                    if (!isset($law[$i])) {
                        $law[$i] = '';
                    }
                    
                    $law[$i] .= '<section '. $datapath .' style="text-align:center;font-weight:bold" class="drag-drop-law '. $addinclass .'">' . $law_mor . '</section>';
                }
                
                $z++;
            }
        }
        
        $old_char = array('<sup>', '</sup>');
        $new_char = array('@a', '@b',);

        $html_result = '';
        
        for ($y = 0; $y <= sizeof($law); $y++) {
            
            if (isset($law['zuil_' . $y]) && !empty($law['zuil_' . $y])) {
                $datapath = ($tag) ? 'data-paragraph-keyid="'. $law['zuil_' . $y]['paragraphid'] .'"' : "";
                $html_result .= '<section id="list_item_' . $y . '" '. $datapath .' class="__drag-drop-law">';
                    $html_result .= '<div class="__editLaw pull-left">';
                        $html_result .='<section class="msg_head opened_head '. $addinclass .'">';
                            $html_result .= '<label>' . str_replace($new_char, $old_char, $law['zuil_' . $y]['name']) . '</label>';
                        $html_result .= '</section>';
                        $html_result .= '<div class="msg_body" style="text-align: justify">';
                            $html_result .= str_replace($new_char, $old_char, @$law[$y]);
                                $html_result .=  ($printLaw ? '<a href="javascript:printFormSheet(' . "'list_item_$y'" . ');" class="printpage">Хэвлэх</a>' : '');
                        $html_result .= '</div>'; 
                    $html_result .= '</div>';
                    $html_result .= ($editLaw !== false ? '<a href="javascript:;" class="editlaw">Засах</a>' : '');
                    $html_result .= '<div class="clear"></div>';
                $html_result .= '</section>';
            } 
            else if (isset($law['buleg_' . $y]) && !empty($law['buleg_' . $y])) {
                $datapath = ($tag) ? 'data-paragraph-keyid="'. $law['buleg_' . $y]['paragraphid'] .'"' : "";
                $html_result .= '<section '. $datapath .' style="text-align:center;" class="drag-drop-law '. $addinclass .'"><strong>' . strtoupper($law['buleg_' . $y]['name'] . '' . issetParam($law[$y])) . '</strong></section>';
            } 
            else if (isset($law['angi_' . $y]) && !empty($law['angi_' . $y]['name'])) {
                $datapath = ($tag) ? 'data-paragraph-keyid="'. $law['angi_' . $y]['paragraphid'] .'"' : "";
                $html_result .= '<section '. $datapath .' style="text-align:center;" class="drag-drop-law '. $addinclass .'"><strong>' . strtoupper($law['angi_' . $y]['name'] . '' . issetParam($law[$y])) . '</strong></section>';
            } 
            else if (isset($law['part_' . $y]) && !empty($law['part_' . $y]['name'])) {
                $datapath = ($tag) ? 'data-paragraph-keyid="'. $law['part_' . $y]['paragraphid'] .'"' : "";
                $html_result .= '<section '. $datapath .' style="text-align:center;" class="drag-drop-law '. $addinclass .'"><strong>' . strtoupper($law['part_' . $y]['name'] . '' . issetParam($law[$y])) . '</strong></section>';
            } 
            else if (isset($law['subpart_' . $y]) && !empty($law['subpart_' . $y]['name'])) {
                $datapath = ($tag) ? 'data-paragraph-keyid="'. $law['subpart_' . $y]['paragraphid'] .'"' : "";
                $html_result .= '<section '. $datapath .' style="text-align:center;" class="drag-drop-law '. $addinclass .'"><strong>' . strtoupper($law['subpart_' . $y]['name'] . '' . issetParam($law[$y])) . '</strong></section>';
            } 
            else if (isset($law['cccc_' . $y]) && !empty($law['cccc_' . $y])) {
                if (preg_match('/^МОНГОЛ УЛСЫН ХУУЛЬ/i', $law['cccc_' . $y])) {
                    $html_result .= '<section style="text-align:center;color:#275dff;font-size:16pt;">' . strtoupper($law['cccc_' . $y]) . '</section><br><br>' . issetParam($law[$y]);
                } else {
                    $html_result .= '<section style="text-align:center;"><strong>' . strtoupper(issetParam($law['cccc_' . $y])) . '</strong></section>' . issetParam($law[$y]);
                }
            } 
            else if (isset($law['footer' . $y]) && !empty($law['footer' . $y])) {
                $html_result .= '<br><section style="text-align:center;"><strong>' . $law['footer' . $y] . '</strong></section><br>';
                $html_result .= '<section style="margin-left:35px;">' . @$law[$y] . '</section>';
            } 
            else if (isset($law['date' . $y]) && !empty($law['date' . $y])) {
                $html_result .= '<section><table style="margin:auto;width:100%;color:#275dff;font-size:10pt;">
                                        <tr>
                                            <td align="left" width="33%">' . $law['date' . $y] . '</td>
                                            <td align="center" width="33%">' . issetParam($law['number' . $y]) . '</td>
                                            <td align="right" width="33%">' . issetParam($law['position' . ($y + 1)]) . '</td>
                                        </tr>
                                </table></section><br><br>';

                if (sizeof($law) == 4) {
                    $html_result .= issetParam($law[1]);
                }
                
                if (isset($law[$y]) && !empty($law[$y])) {
                    $html_result .= $law[$y];
                }
            }
            else if (isset($law[$y]) && !empty($law[$y])) {
                $html_result .= $law[$y];
            }
            
        }
        
        $html_result = str_replace($new_char, $old_char, $html_result);
        $html_result = preg_replace('#(<br */?>\s*)+#i', '<br/>', $html_result);
        
        $html_result .= $printLaw ? '<script type="text/javascript" src="/js/print.js"></script>' : '';
        
        if ($tag) {
            $temphhtml = $html_result;
            $html_result = $header;
            $html_result .= $temphhtml;
            $html_result .= $footer;
        }
        
        return $html_result;
    }
    
    public function getcontentTreeListV2($lawId) {
        
        $data = self::getAddonchangesTree($lawId);
        
        (String) $html = '';
        if (issetParam($data[0]['children'])) {
            foreach ($data[0]['children'] as $bkey => $buleg) {
                if (issetParamArray($buleg['children'])) {
                    $html .= '<section data-paragraph-keyid="'. $buleg['keyid'] .'" style="text-align:center;" class="drag-drop-law"><strong><br>' . $buleg['text'] . '<br></strong></section>';
                    foreach ($buleg['children'] as $zkey => $zuil) {
                        if (issetParamArray($zuil['children'])) {
                            if (preg_match('/р зүйл[.]/i', $zuil['text'])) {
                                $html .= '<section id="list_item_2" data-paragraph-keyid="'. $zuil['keyid'] .'" class="__drag-drop-law">';
                                    $html .= '<div class="__editLaw pull-left">';
                                        $html .= '<section class="msg_head opened_head editonly noedit">';
                                            $html .= '<label class="drag-drop-law">' . $zuil['text'] . '</label>';
                                        $html .= '</section>';
                                        $html .= '<div class="msg_body" style="text-align: justify;">';
                                            foreach ($zuil['children'] as $pkey => $paragraph) {
                                                if (preg_match('/\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]/i', $paragraph['text'])) {
                                                    $html .= '<section style="padding-left: 6.5rem;text-indent: unset" data-paragraph-keyid="'. $paragraph['keyid'] .'" class="drag-drop-law ">'. $paragraph['text'] .'</section>';
                                                } else if (preg_match('/\d{1,3}[.]\d{1,3}[.]\d{1,3}[.]/i', $paragraph['text'])) {
                                                    $html .= '<section style="padding-left: 4.5rem;text-indent: unset" data-paragraph-keyid="'. $paragraph['keyid'] .'" class="drag-drop-law ">'. $paragraph['text'] .'</section>';
                                                } else if (preg_match('/\d{1,3}[.]\d{1,3}[.]/i', $paragraph['text'])) {
                                                    $html .= '<section style="padding-left: 2.5rem;text-indent: unset"  data-paragraph-keyid="'. $paragraph['keyid'] .'" class="drag-drop-law ">'. $paragraph['text'] .'</section>';
                                                } else if (preg_match('/\d{1,3}(\/)/i', $paragraph['text'])) {
                                                    $html .= '<section style="padding-left: 4.5rem;text-indent: unset" data-paragraph-keyid="'. $paragraph['keyid'] .'" class="drag-drop-law ">'. $paragraph['text'] .'</section>';
                                                } else if (preg_match('/\d{1,3}[.]/i', $paragraph['text'])) {
                                                    $html .= '<section style="padding-left: 2.5rem;text-indent: unset"  data-paragraph-keyid="'. $paragraph['keyid'] .'" class="drag-drop-law ">'. $paragraph['text'] .'</section>';
                                                } else {
                                                    $html .= '<section style="padding-left: 2.5rem;text-indent: unset"  data-paragraph-keyid="'. $paragraph['keyid'] .'" class="drag-drop-law ">'. $paragraph['text'] .'</section>';
                                                }
                                            }
                                        $html .= '</div>';
                                    $html .= '</div>';
                                    $html .= '<div class="clear"></div>';
                                $html .= '</section>';
                            }
                            else {
                                $html .= '<section data-paragraph-keyid="'. $zuil['keyid'] .'" class="drag-drop-law">' . $zuil['text'] . '</section>';
                            }
                        } else {
                            $html .= '<section data-paragraph-keyid="'. $zuil['keyid'] .'" class="drag-drop-law">' . $zuil['text'] . '</section>';
                        }
                    }
                } else {
                    $html .= '<section data-paragraph-keyid="'. $buleg['keyid'] .'" class="drag-drop-law">' . $buleg['text'] . '</section>';
                }
            }
        }
        
        return $html;
    }
    
}

?>