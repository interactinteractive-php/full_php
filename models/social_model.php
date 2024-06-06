<?php if(!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');
    
class Social_Model extends Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function fncRunDataview($dataviewId, $field = "", $operand = "=", $operator = "", $paramFilter = "", $sortField = 'createddate', $sortK = 'desc', $iscriteriaOnly = "0", $pagination = false, $pageSize = false) {
        if ($iscriteriaOnly) {
            $criteria = $paramFilter;
        } else {
            $criteria = array(
                            $field => array(
                                array(
                                    'operator' => $operand,
                                    'operand' => ($operand == 'like') ? '%'.$operator.'%' : $operator
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
                'pageSize' => $pageSize ? $pageSize : '20'
            );
        }
        
        if ($sortField) {
            $sortColumnNames[$sortField] = array('sortType' => $sortK);
            $paging['sortColumnNames'] = $sortColumnNames;
        }
        
        $data = Functions::runDataViewWithoutLogin($dataviewId, $criteria, '0', $paging);
        
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
    
    public function getPostSelectQuery($userId, $where = '') {
        
        $sql = "
            SELECT 
                POST.ID, 
                POST.YOUTUBE_ID, 
                POST.SOUNDCLOUD_ID, 
                POST.LOCATION_STR, 
                POST.DESCRIPTION, 
                POST.CREATED_DATE, 
                SU.USER_ID, 
                BP.LAST_NAME, 
                BP.FIRST_NAME, 
                EMP.PICTURE, 
                SL.ID AS LIKE_ID, 
                (SELECT COUNT(ID) FROM SCL_LIKE WHERE POST_ID = POST.ID) AS LIKE_COUNT 
            FROM SCL_POSTS POST 
                INNER JOIN UM_SYSTEM_USER SU ON SU.USER_ID = POST.USER_ID 
                INNER JOIN BASE_PERSON BP ON BP.PERSON_ID = SU.PERSON_ID 
                LEFT JOIN HRM_EMPLOYEE EMP ON EMP.PERSON_ID = SU.PERSON_ID 
                LEFT JOIN SCL_LIKE SL ON SL.POST_ID = POST.ID AND SL.USER_ID = $userId 
            WHERE POST.IS_ACTIVE = 1 
                $where 
            ORDER BY POST.CREATED_DATE DESC";
        
        return $sql;
    }
    
    public function getLastPostsModel($userId = null, $page = 1, $limit = 10, $postId = null, $metaDataId = null) {
        
        $rows = $limit;
        $offset = ($page - 1) * $rows;
        
        if (Input::isEmpty('groupId') == false) {
            $where = 'AND POST.GROUP_ID = '.Input::post('groupId');
        } else {
            $where = 'AND POST.GROUP_ID IS NULL ';
        }
        
        if ($postId) {
            $where = " AND POST.ID = $postId";
        }
        
        $sql = self::getPostSelectQuery($userId, $where);
        
        $count = $this->db->GetOne("SELECT COUNT(CT.ID) AS ROW_COUNT FROM ($sql) CT");
        
        if ($page <= ceil($count / $limit)) {
            
            $data = $this->db->SelectLimit($sql, $limit, $offset);

            if (isset($data->_array)) {
                $data = $data->_array;
                return array('rows' => $data, 'total' => $count);
            }
        }
        
        return null;
    }
    
    public function createPostModel() {
        
        try {
            
            $postId = getUID();
            $userId = Ue::sessionUserId();
            
            $data = array(
                'ID'           => $postId, 
                'USER_ID'      => $userId, 
                'DESCRIPTION'  => Input::post('description'), 
                'YOUTUBE_ID'   => Input::post('youtubeText'), 
                'IS_ACTIVE'    => 1, 
                'PRIVACY_TYPE' => 'public', 
                'CREATED_DATE' => Date::currentDate()
            );
            
            if (Input::isEmpty('youtubeText') == false) {
                
                $getVideoId = getYoutubeVideoID(Input::post('youtubeText'));
                
                if ($getVideoId) {
                    $data['YOUTUBE_ID'] = $getVideoId;
                } else {
                    $response = array('status' => 'error', 'message' => 'Зөв хаяг оруулна уу!');
                    return $response;
                }
            }
            
            if (Input::isEmpty('group_id') == false) {
                $data['GROUP_ID'] = Input::post('group_id');
            }
                
            $result = $this->db->AutoExecute('SCL_POSTS', $data);

            if ($result) {
                
                if (isset($_FILES['postFile'])) {
                    
                    $totalFile = count($_FILES['postFile']['name']);

                    for ($i = 0; $i < $totalFile; $i++) {
                        
                        if ($_FILES['postFile']['error'][$i] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['postFile']['tmp_name'][$i])) {
                            
                            $fileName      = $_FILES['postFile']['name'][$i];
                            $fileExtension = strtolower(substr($fileName, strrpos($fileName, '.') + 1));
                            $getUID        = getUIDAdd($i);    
                            
                            if (in_array($fileExtension, Social::$acceptFileExtension)) {
                                
                                $newFileName   = 'file_' . $getUID;
                                $fileName      = $newFileName . '.' . $fileExtension;
                                $fileUrl       = UPLOADPATH . 'social/posts/images/';
                                
                                if (in_array($fileExtension, array('jpg', 'jpeg', 'png', 'gif', 'bmp'))) {
                                    
                                    $fileAttr['name']     = $_FILES['postFile']['name'][$i];
                                    $fileAttr['tmp_name'] = $_FILES['postFile']['tmp_name'][$i];
                                    $fileAttr['size']     = $_FILES['postFile']['size'][$i];
                                    $fileAttr['type']     = $_FILES['postFile']['type'][$i];
                                    
                                    Upload::$File = $fileAttr;
                                    Upload::$method = 0;
                                    Upload::$SavePath = $fileUrl;
                                    Upload::$ThumbPath = UPLOADPATH . 'social/posts/images/thumb/';
                                    Upload::$NewWidth = 2000;
                                    Upload::$NewName = $newFileName;
                                    Upload::$OverWrite = true;
                                    Upload::$CheckOnlyWidth = true;
                                    $uploadError = Upload::UploadFile();

                                    if ($uploadError == '') {
                                        
                                        $mediaData = array(
                                            'ID'             => $getUID, 
                                            'POST_ID'        => $postId, 
                                            'FILE_PATH'      => $fileUrl, 
                                            'FILE_NAME'      => $fileAttr['name'], 
                                            'FILE_SIZE'      => $fileAttr['size'], 
                                            'NEW_FILE_NAME'  => $fileName,
                                            'FILE_EXTENSION' => $fileExtension, 
                                            'FILE_TYPE'      => 'image', 
                                            'CREATED_DATE'   => Date::currentDate()
                                        );
                                        
                                        $this->db->AutoExecute('SCL_POSTS_MEDIA', $mediaData);
                                    }
                                    
                                } else {
                                    
                                    FileUpload::SetFileName($fileName);
                                    FileUpload::SetTempName($_FILES['postFile']['tmp_name'][$i]);
                                    FileUpload::SetUploadDirectory(UPLOADPATH . 'social/posts/documents/');
                                    FileUpload::SetValidExtensions(explode(',', Config::getFromCache('CONFIG_FILE_EXT')));
                                    FileUpload::SetMaximumFileSize(FileUpload::GetConfigFileMaxSize());
                                    $uploadResult = FileUpload::UploadFile();

                                    if ($uploadResult) {
                                        
                                        $mediaData = array(
                                            'ID'             => $getUID, 
                                            'POST_ID'        => $postId, 
                                            'FILE_PATH'      => UPLOADPATH . 'social/posts/documents/', 
                                            'FILE_NAME'      => $_FILES['postFile']['name'][$i], 
                                            'FILE_SIZE'      => $_FILES['postFile']['size'][$i], 
                                            'NEW_FILE_NAME'  => $fileName,
                                            'FILE_EXTENSION' => $fileExtension, 
                                            'CREATED_DATE'   => Date::currentDate()
                                        );
                                        
                                        $this->db->AutoExecute('SCL_POSTS_MEDIA', $mediaData);
                                    }
                                }
                            }
                        }
                    }
                }
                
                $lastQry = 'AND POST.ID = ' . $postId;
                
                $sql = self::getPostSelectQuery($userId, $lastQry);
                $postData = $this->db->GetAll($sql);
                
                $response = array('status' => 'success', 'data' => $postData);
            }

        } catch (ADODB_Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }

        return $response;
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
    
    public function postCommentModel($postId) {
        
        try {
            
            $id = getUID();
            
            $data = array(
                'ID'           => $id, 
                'POST_ID'      => $postId, 
                'COMMENT_TXT'  => Input::post('post_comment'), 
                'USER_ID'      => Ue::sessionUserId(), 
                'CREATED_DATE' => Date::currentDate()
            );
                
            $result = $this->db->AutoExecute('SCL_POSTS_COMMENT', $data);

            if ($result) {
                
                $commentData = self::getPostsCommentByPostIdModel($postId);
                
                $response = array('status' => 'success', 'data' => $commentData);
            }

        } catch (ADODB_Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }

        return $response;
    }
    
    public function deleteCommentModel($postId) {
        
        try {
            
            $commentId = Input::post('commentId');
                
            $result = $this->db->Execute("DELETE FROM SCL_POSTS_COMMENT WHERE ID = $commentId");

            if ($result) {
                $commentData = self::getPostsCommentByPostIdModel($postId);
                $response = array('status' => 'success', 'data' => $commentData);
            }

        } catch (ADODB_Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }

        return $response;
    }
    
    public function getPostByPostIdModel($postId) {
        
        $userId = $this->db->GetOne("SELECT USER_ID FROM SCL_POSTS WHERE ID = $postId");
        
        return $userId;
    }
    
    public function deletePostModel() {
        
        $postId = Input::post('postId');
        
        $response = self::deleteSinglePost($postId);

        return $response;
    }
    
    public function deleteSinglePost($postId) {
        
        try {
            
            $medias = self::getPostsMediaByPostIdModel($postId);

            if ($medias) {
                foreach ($medias as $media) {

                    if ($media['FILE_PATH'] && file_exists($media['FILE_PATH'] . $media['NEW_FILE_NAME'])) {

                        unlink($media['FILE_PATH'] . $media['NEW_FILE_NAME']);

                        if (file_exists($media['FILE_PATH'] . 'thumb/' . $media['NEW_FILE_NAME'])) {
                            unlink($media['FILE_PATH'] . 'thumb/' . $media['NEW_FILE_NAME']);
                        }
                    }
                }
            }

            $this->db->Execute("DELETE FROM SCL_POSTS_MEDIA WHERE POST_ID = $postId");
            $this->db->Execute("DELETE FROM SCL_POSTS_COMMENT WHERE POST_ID = $postId");
            $this->db->Execute("DELETE FROM SCL_POSTS WHERE ID = $postId");
            $this->db->Execute("DELETE FROM SCL_LIKE WHERE POST_ID = $postId");
            $this->db->Execute("DELETE FROM SCL_SAVED_ITEMS WHERE POST_ID = $postId");
            
            $response = array('status' => 'success', 'message' => Lang::line('msg_delete_success'));
            
        } catch (ADODB_Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }
    
    public function saveLikeModel() {
        
        try {
            
            $postId = Input::post('postId');
            $likeId = Input::post('likeId');
            $userId = Ue::sessionUserId();
            
            if ($likeId) { //Unlike
                
                $this->db->Execute("DELETE FROM SCL_LIKE WHERE POST_ID = $postId AND USER_ID = $userId");
            
            } else { //Like
                
                $likeId = getUID();
                
                $data = array(
                    'ID'           => $likeId, 
                    'POST_ID'      => $postId, 
                    'COMMENT_TXT'  => Input::post('post_comment'), 
                    'USER_ID'      => $userId, 
                    'LIKE_TYPE_ID' => Input::post('likeTypeId'), 
                    'CREATED_DATE' => Date::currentDate()
                );

                $this->db->AutoExecute('SCL_LIKE', $data);
            }
            
            $count = self::getPostLikeCountModel($postId);

            $response = array('status' => 'success', 'likeId' => $likeId, 'count' => $count);

        } catch (ADODB_Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }

        return $response;
    }
    
    public function getPostLikeCountModel($postId) {
        $count = $this->db->GetOne("SELECT COUNT(ID) FROM SCL_LIKE WHERE POST_ID = $postId");
        return $count;
    }
    
    public function getPostsMediaByPostIdModel($postId) {
        $data = $this->db->GetAll("SELECT * FROM SCL_POSTS_MEDIA WHERE POST_ID = $postId ORDER BY CREATED_DATE ASC");
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
    
    public function getSocialPostsCountModel($metaDataId = null) {
        
        $userId = Ue::sessionUserId();
        
        $row = $this->db->GetRow("
            SELECT 
                (
                    SELECT COUNT(ID) FROM SCL_POSTS WHERE USER_ID = $userId AND IS_ACTIVE = 1 
                ) AS POSTS_COUNT, 
                (
                    SELECT 
                        COUNT(PM.ID) 
                    FROM SCL_POSTS SP 
                        INNER JOIN SCL_POSTS_MEDIA PM ON PM.POST_ID = SP.ID 
                    WHERE SP.USER_ID = $userId 
                        AND SP.IS_ACTIVE = 1 
                        AND PM.FILE_TYPE = 'image'  
                ) AS IMAGE_COUNT, 
                (
                    SELECT COUNT(ID) FROM SCL_POSTS WHERE USER_ID = $userId AND IS_ACTIVE = 1 AND YOUTUBE_ID IS NOT NULL 
                ) AS VIDEO_COUNT 
            FROM UM_SYSTEM_USER UM
            WHERE UM.USER_ID = $userId");
        
        return $row;
    }
    
    public function isSavedItemsByUserIdModel($postId, $userId) {
        
        $isSaved = $this->db->GetOne("SELECT ID FROM SCL_SAVED_ITEMS WHERE POST_ID = $postId AND USER_ID = $userId");
        
        if ($isSaved) {
            return true;
        }
        
        return false;
    }
    
    public function saveSclItemModel() {
        
        try {
            
            $data = array(
                'ID'           => getUID(), 
                'POST_ID'      => Input::post('postId'), 
                'USER_ID'      => Ue::sessionUserId(),  
                'CREATED_DATE' => Date::currentDate()
            );

            $this->db->AutoExecute('SCL_SAVED_ITEMS', $data);
            
            return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
        
        } catch (ADODB_Exception $ex) {
            return array('status' => 'error', 'message' => $ex->getMessage());
        }
    }
    
    public function unSaveSclItemModel() {
        
        try {
            
            $postId = Input::post('postId');
            $userId = Ue::sessionUserId();
            
            $this->db->Execute("DELETE FROM SCL_SAVED_ITEMS WHERE POST_ID = $postId AND USER_ID = $userId");
            
            return array('status' => 'success', 'message' => 'Амжилттай');
        
        } catch (ADODB_Exception $ex) {
            return array('status' => 'error', 'message' => $ex->getMessage());
        }
    }
    
    public function getSavedItemsModel() {
        
        $userId = Ue::sessionUserId();
        
        $data = $this->db->GetAll("
            SELECT 
                SP.ID, 
                SP.DESCRIPTION, 
                (SELECT COUNT(ID) FROM SCL_POSTS_COMMENT WHERE POST_ID = SP.ID) AS COMMENT_COUNT, 
                EMP.PICTURE, 
                BP.LAST_NAME, 
                BP.FIRST_NAME 
            FROM SCL_SAVED_ITEMS SS 
                INNER JOIN SCL_POSTS SP ON SP.ID = SS.POST_ID AND SP.IS_ACTIVE = 1 
                INNER JOIN UM_SYSTEM_USER UM ON UM.USER_ID = SP.USER_ID 
                LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = UM.PERSON_ID 
                LEFT JOIN HRM_EMPLOYEE EMP ON EMP.PERSON_ID = UM.PERSON_ID 
            WHERE SS.USER_ID = $userId     
            ORDER BY SP.CREATED_DATE DESC");
        
        return $data;
    }
    
    public function getSocialGroupsModel($page = 1, $limit = 10, $groupId = null) {
        
        $rows = $limit;
        $offset = ($page - 1) * $rows;
        $where = '';
        
        $sql = self::getGroupSelectQuery($where);
        
        $count = $this->db->GetOne("SELECT COUNT(CT.ID) AS ROW_COUNT FROM ($sql) CT");
        
        if ($page <= ceil($count / $limit)) {
            
            $data = $this->db->SelectLimit($sql, $limit, $offset);

            if (isset($data->_array)) {
                $data = $data->_array;
                return array('rows' => $data, 'total' => $count);
            }
        }
        
        return null;
    }
    
    public function getGroupSelectQuery($userId, $where = '') {
        
        $sql = "
            SELECT 
                GRP.*, 
                (SELECT COUNT(ID) FROM SCL_GROUP_MEMBERS WHERE GROUP_ID = GRP.ID) AS MEMBER_COUNT 
            FROM SCL_GROUPS GRP  
            WHERE GRP.IS_ACTIVE = 1 
                $where 
            ORDER BY GRP.CREATED_DATE DESC";
        
        return $sql;
    }
    
    public function createGroupModel() {
        
        try {
            
            $groupId = getUID();
        
            $data = array(
                'ID'              => $groupId, 
                'GROUP_NAME'      => Input::post('name'), 
                'PRIVACY_TYPE'    => Input::post('privacyType'), 
                'DESCRIPTION'     => Input::post('descr'),
                'IS_ACTIVE'       => 1, 
                'CREATED_USER_ID' => Ue::sessionUserId(), 
                'CREATED_DATE'    => Date::currentDate()
            );
            
            if (isset($_FILES['cover']) && $_FILES['cover']['name'] != '') {
                
                $newPhotoName = 'cover_' . $groupId;
                $photoExtension = strtolower(substr($_FILES['cover']['name'], strrpos($_FILES['cover']['name'], '.') + 1));
                $photoName = $newPhotoName . '.' . $photoExtension;
                $fileUrl = UPLOADPATH . 'social/posts/images/';

                Upload::$File = $_FILES['cover'];
                Upload::$method = 0;
                Upload::$SavePath = $fileUrl;
                Upload::$ThumbPath = UPLOADPATH . 'social/posts/images/thumb/';
                Upload::$NewWidth = 1000;
                Upload::$NewName = $newPhotoName;
                Upload::$OverWrite = true;
                Upload::$CheckOnlyWidth = true;
                $uploadError = Upload::UploadFile();

                if ($uploadError == '') {
                    $data['COVER_PICTURE'] = $photoName;
                }
            }

            $result = $this->db->AutoExecute('SCL_GROUPS', $data);
            
            if ($result) {
                
                $member = array(
                    'ID'           => getUID(), 
                    'GROUP_ID'     => $groupId, 
                    'USER_ID'      => $data['CREATED_USER_ID'], 
                    'CREATED_DATE' => $data['CREATED_DATE']
                );

                $this->db->AutoExecute('SCL_GROUP_MEMBERS', $member);
            }
            
            Message::add('s', '', URL . 'social/group/' . $groupId);
            
        } catch (ADODB_Exception $ex) {
            Message::add('d', $ex->getMessage(), URL . 'social/groups');
        }
    }
    
    public function getGroupPostsModel($groupId, $userId = null, $page = 1, $limit = 10, $postId = null) {
        
        $rows = $limit;
        $offset = ($page - 1) * $rows;
        $where = '';
        
        if ($postId) {
            $where = " AND POST.ID = $postId";
        }
        
        $sql = "
            SELECT 
                POST.ID, 
                POST.YOUTUBE_ID, 
                POST.SOUNDCLOUD_ID, 
                POST.LOCATION_STR, 
                POST.DESCRIPTION, 
                POST.CREATED_DATE, 
                SU.USER_ID, 
                BP.LAST_NAME, 
                BP.FIRST_NAME, 
                EMP.PICTURE, 
                SL.ID AS LIKE_ID, 
                (SELECT COUNT(ID) FROM SCL_LIKE WHERE POST_ID = POST.ID) AS LIKE_COUNT 
            FROM SCL_POSTS POST 
                INNER JOIN UM_SYSTEM_USER SU ON SU.USER_ID = POST.USER_ID 
                INNER JOIN BASE_PERSON BP ON BP.PERSON_ID = SU.PERSON_ID 
                LEFT JOIN HRM_EMPLOYEE EMP ON EMP.PERSON_ID = SU.PERSON_ID 
                LEFT JOIN SCL_LIKE SL ON SL.POST_ID = POST.ID AND SL.USER_ID = $userId 
            WHERE POST.IS_ACTIVE = 1 
                AND POST.GROUP_ID = $groupId 
                $where 
            ORDER BY POST.CREATED_DATE DESC";
        
        $count = $this->db->GetOne("SELECT COUNT(CT.ID) AS ROW_COUNT FROM ($sql) CT");
        
        if ($page <= ceil($count / $limit)) {
            
            $data = $this->db->SelectLimit($sql, $limit, $offset);

            if (isset($data->_array)) {
                $data = $data->_array;
                return array('rows' => $data, 'total' => $count);
            }
        }
        
        return null;
    }
    
    public function getGroupRowByIdModel($id, $userId) {
        
        $row = $this->db->GetRow("
            SELECT 
                SG.*, 
                " . $this->db->IfNull('SG.MODIFIED_DATE', 'SG.CREATED_DATE') . " AS MODIFIED_TIME, 
                (SELECT COUNT(ID) FROM SCL_GROUP_MEMBERS WHERE GROUP_ID = SG.ID) AS MEMBER_COUNT, 
                GM.USER_ID 
            FROM SCL_GROUPS SG 
                LEFT JOIN SCL_GROUP_MEMBERS GM ON GM.GROUP_ID = SG.ID AND GM.USER_ID = $userId 
            WHERE SG.ID = $id");
        
        return $row;
    }
    
    public function getSimpleGroupRowByIdModel($id) {
        
        $row = $this->db->GetRow("SELECT * FROM SCL_GROUPS WHERE ID = $id");
        return $row;
    }
    
    public function getGroupMembersModel($groupId, $page = 1, $limit = 10) {
        
        $rows = $limit;
        $offset = ($page - 1) * $rows;
        
        $sql = "SELECT 
                GM.USER_ID, 
                BP.LAST_NAME, 
                BP.FIRST_NAME, 
                EMP.PICTURE 
            FROM SCL_GROUP_MEMBERS GM 
                INNER JOIN UM_SYSTEM_USER SU ON SU.USER_ID = GM.USER_ID 
                INNER JOIN BASE_PERSON BP ON BP.PERSON_ID = SU.PERSON_ID 
                LEFT JOIN HRM_EMPLOYEE EMP ON EMP.PERSON_ID = SU.PERSON_ID 
            WHERE GM.GROUP_ID = $groupId 
            ORDER BY GM.CREATED_DATE ASC";
        
        $data = $this->db->SelectLimit($sql, $limit, $offset);

        if (isset($data->_array)) {
            return $data->_array;
        }
        
        return null;
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
    
    public function joinGroupModel($id) {
        
        $groupId = Input::param($id);
        
        $check = $this->db->GetRow("SELECT ID FROM SCL_GROUPS WHERE ID = $groupId");
        
        if ($check) {
            
            $userId = Ue::sessionUserId();
            
            $map = $this->db->GetRow("SELECT ID FROM SCL_GROUP_MEMBERS WHERE GROUP_ID = $groupId AND USER_ID = $userId");
            
            if (!$map) {
                
                $member = array(
                    'ID'           => getUID(), 
                    'GROUP_ID'     => $groupId, 
                    'USER_ID'      => $userId, 
                    'CREATED_DATE' => Date::currentDate()
                );

                $this->db->AutoExecute('SCL_GROUP_MEMBERS', $member);
            }
            
            Message::add('s', '', URL . 'social/group/' . $groupId);
        }
        
        Message::add('e', '', 'back');
    }
    
    public function exitGroupModel($id) {
        
        $groupId = Input::param($id);
        
        $check = $this->db->GetRow("SELECT ID FROM SCL_GROUPS WHERE ID = $groupId");
        
        if ($check) {
            
            $userId = Ue::sessionUserId();
            
            $this->db->Execute("DELETE FROM SCL_GROUP_MEMBERS WHERE GROUP_ID = $groupId AND USER_ID = $userId");
            
            Message::add('s', '', URL . 'social/groups');
        }
        
        Message::add('e', '', 'back');
    }
    
    public function updateGroupModel() {
        
        try {
            
            $groupId = Input::post('groupId');
            $sessionUserId = Ue::sessionUserId();
            
            $row = self::getSimpleGroupRowByIdModel($groupId);
            
            if ($sessionUserId != $row['CREATED_USER_ID']) {
                
                Message::add('s', '', URL . 'social/group/' . $groupId);
            }
        
            $data = array(
                'GROUP_NAME'       => Input::post('name'), 
                'PRIVACY_TYPE'     => Input::post('privacyType'), 
                'DESCRIPTION'      => Input::post('descr'), 
                'MODIFIED_USER_ID' => Ue::sessionUserId(), 
                'MODIFIED_DATE'    => Date::currentDate()
            );
            
            if (isset($_FILES['cover']) && $_FILES['cover']['name'] != '') {
                
                $newPhotoName = 'cover_' . $groupId;
                $photoExtension = strtolower(substr($_FILES['cover']['name'], strrpos($_FILES['cover']['name'], '.') + 1));
                $photoName = $newPhotoName . '.' . $photoExtension;
                $fileUrl = UPLOADPATH . 'social/posts/images/';

                Upload::$File = $_FILES['cover'];
                Upload::$method = 0;
                Upload::$SavePath = $fileUrl;
                Upload::$ThumbPath = UPLOADPATH . 'social/posts/images/thumb/';
                Upload::$NewWidth = 1000;
                Upload::$NewName = $newPhotoName;
                Upload::$OverWrite = true;
                Upload::$CheckOnlyWidth = true;
                $uploadError = Upload::UploadFile();

                if ($uploadError == '') {
                    $data['COVER_PICTURE'] = $photoName;
                }
            }

            $this->db->AutoExecute('SCL_GROUPS', $data, 'UPDATE', 'ID = ' . $groupId);
            
            Message::add('s', 'Success', URL . 'social/group/' . $groupId);
            
        } catch (ADODB_Exception $ex) {
            
            Message::add('d', $ex->getMessage(), URL . 'social/groups');
        }
    }
    
    public function getGroupNotJoinedUsersModel($groupId) {
        
        $data = $this->db->GetAll("
            SELECT 
                EMP.LAST_NAME, 
                EMP.FIRST_NAME, 
                EMP.PICTURE, 
                UM.USER_ID 
            FROM UM_SYSTEM_USER UM 
                INNER JOIN VW_EMPLOYEE EMP ON EMP.PERSON_ID = UM.PERSON_ID 
                LEFT JOIN SCL_GROUP_MEMBERS GM ON GM.GROUP_ID = $groupId AND GM.USER_ID = UM.USER_ID 
            WHERE GM.ID IS NULL        
            GROUP BY 
                EMP.LAST_NAME, 
                EMP.FIRST_NAME,
                EMP.PICTURE, 
                UM.USER_ID 
            ORDER BY EMP.FIRST_NAME ASC");
        
        return $data;
    }
    
    public function addMemberModel() {
        
        try {
            
            $groupId = Input::post('groupId');
            $sessionUserId = Ue::sessionUserId();
            
            $row = self::getSimpleGroupRowByIdModel($groupId);
            
            if ($sessionUserId != $row['CREATED_USER_ID']) {
                
                Message::add('s', 'Access denied!', URL . 'social/group/' . $groupId);
            }
            
            $userIds = Input::post('userId');
            
            foreach ($userIds as $userId) {
                
                $map = $this->db->GetRow("SELECT ID FROM SCL_GROUP_MEMBERS WHERE GROUP_ID = $groupId AND USER_ID = $userId");
            
                if (!$map) {

                    $member = array(
                        'ID'           => getUID(), 
                        'GROUP_ID'     => $groupId, 
                        'USER_ID'      => $userId, 
                        'CREATED_DATE' => Date::currentDate()
                    );

                    $this->db->AutoExecute('SCL_GROUP_MEMBERS', $member);
                }
            }
            
            Message::add('s', 'Success', URL . 'social/group/' . $groupId);
            
        } catch (ADODB_Exception $ex) {
            
            Message::add('d', $ex->getMessage(), URL . 'social/groups');
        }
    }
    
    public function deleteGroupModel($groupId) {
        
        try {
            
            $sessionUserId = Ue::sessionUserId();
            
            $row = self::getSimpleGroupRowByIdModel($groupId);
            
            if ($sessionUserId != $row['CREATED_USER_ID']) {
                
                Message::add('s', 'Access denied!', URL . 'social/group/' . $groupId);
            }
            
            $posts = $this->db->GetAll("SELECT ID FROM SCL_POSTS WHERE GROUP_ID = $groupId");
            
            if ($posts) {
                
                foreach ($posts as $post) {
                    self::deleteSinglePost($post['ID']);
                }
            }
            
            $this->db->Execute("DELETE FROM SCL_GROUP_MEMBERS WHERE GROUP_ID = $groupId");
            $this->db->Execute("DELETE FROM SCL_GROUPS WHERE ID = $groupId");
            
            if ($row['COVER_PICTURE'] && file_exists(UPLOADPATH . 'social/posts/images/' . $row['COVER_PICTURE'])) {
                
                unlink(UPLOADPATH . 'social/posts/images/' . $row['COVER_PICTURE']);
                
                if (file_exists(UPLOADPATH . 'social/posts/images/thumb/' . $row['COVER_PICTURE'])) {
                    unlink(UPLOADPATH . 'social/posts/images/thumb/' . $row['COVER_PICTURE']);
                }
            }
            
            Message::add('s', 'Success', URL . 'social/groups');
            
        } catch (ADODB_Exception $ex) {
            
            Message::add('d', $ex->getMessage(), URL . 'social/groups');
        }
    }
    
    public function removeFromGroupModel() {
        
        $groupId = Input::post('groupId');
        $userId  = Input::post('userId');
        
        $result = $this->db->Execute("DELETE FROM SCL_GROUP_MEMBERS WHERE GROUP_ID = $groupId AND USER_ID = $userId");
        
        if ($result) {
            return array('status' => 'success', 'message' => 'Success');
        } else {
            return array('status' => 'error', 'message' => 'Error');
        }
    }

}