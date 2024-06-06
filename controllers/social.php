<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Social extends Controller {
    
    public static $acceptFileExtension = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf', 'zip', 'rar');
    public static $acceptImageExtension = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
    public static $viewName = "views";
    
    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }

    public function index() {
        self::scpost();
    }
    
    public function defaultPosts($metaDataId = null) {
        
        $this->view->pageNumber = 1;
        $this->view->userId = Ue::sessionUserId();
        
        $this->view->posts = $this->model->getLastPostsModel($this->view->userId, $this->view->pageNumber, 10, null, $metaDataId);
        
        return $this->view->renderPrint('social/posts');
    }
    
    public function posts() {
        
        $this->view->pageNumber = Input::post('page');
        $this->view->userId = Ue::sessionUserId();
        
        $this->view->posts = $this->model->getLastPostsModel($this->view->userId, $this->view->pageNumber, 10);
        
        $this->view->render('social/posts');
    }
    
    public function createPost() {
        
        $result = $this->model->createPostModel();
        
        if ($result['status'] == 'success') {
            
            $this->view->posts['rows'] = $result['data'];
            $this->view->userId = Ue::sessionUserId();
            
            $response = array(
                'status' => 'success', 
                'post'   => $this->view->renderPrint('social/posts')
            );
            
        } else {
            $response = $result;
        }
        
        echo json_encode($response); exit;
    }
    
    public function getPostsCommentByPostId($postId) {
        $data = $this->model->getPostsCommentByPostIdModel($postId);
        return $data;
    }
    
    public function postComment() {
        
        $this->view->postId = Input::post('postId');
        $result = $this->model->postCommentModel($this->view->postId);
        
        if ($result['status'] == 'success') {
            
            $this->view->comments = $result['data'];
            $this->view->userId = Ue::sessionUserId();
            
            $response = array(
                'status' => 'success', 
                'comments' => $this->view->renderPrint('social/comments'), 
                'commentCount' => count($this->view->comments)
            );
            
        } else {
            $response = $result;
        }
        
        echo json_encode($response); exit;
    }
    
    public function deleteComment() {
        
        $this->view->postId = Input::post('postId');
        $result = $this->model->deleteCommentModel($this->view->postId);
        
        if ($result['status'] == 'success') {
            
            $this->view->comments = $result['data'];
            $this->view->userId = Ue::sessionUserId();
            
            $response = array(
                'status' => 'success', 
                'comments' => $this->view->renderPrint('social/comments'), 
                'commentCount' => count($this->view->comments)
            );
            
        } else {
            $response = $result;
        }
        
        echo json_encode($response); exit;
    }
    
    public function deleteConfirm() {
        $response = array(
            'html' => 'Та устгахдаа итгэлтэй байна уу?',
            'title' => 'Сануулах',
            'yes_btn' => $this->lang->line('yes_btn'),
            'no_btn' => $this->lang->line('no_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function postActions() {
        
        $this->view->postId = Input::post('postId');
        
        $this->view->userId = Ue::sessionUserId();
        $this->view->savedUserId = $this->model->getPostByPostIdModel($this->view->postId);
        $this->view->isSaved = $this->model->isSavedItemsByUserIdModel($this->view->postId, $this->view->userId);
        
        $response = array(
            'status' => 'success', 
            'html' => $this->view->renderPrint('social/post_actions')
        );
        echo json_encode($response); exit;
    }
    
    public function deletePost() {
        $response = $this->model->deletePostModel();
        echo json_encode($response); exit;
    }
    
    public function saveLike() {
        $response = $this->model->saveLikeModel();
        echo json_encode($response); exit;
    }
    
    public function saveItem() {
        $response = $this->model->saveSclItemModel();
        echo json_encode($response); exit;
    }
    
    public function unSaveItem() {
        $response = $this->model->unSaveSclItemModel();
        echo json_encode($response); exit;
    }
    
    public function saved() {
        
        $this->view->title = 'Миний хадгалсан нийтлэлүүд';

        $this->view->js = array('custom/css/social/js/social.js');
        $this->view->css = array('custom/css/social/css/style.css');
        
        $this->view->savedItems = $this->model->getSavedItemsModel();
        
        $this->view->postsCount = $this->model->getSocialPostsCountModel();
        
        $this->view->activeUsersData = $this->model->getSocialActiveUsersModel(10);
        $this->view->activeUsers = $this->view->renderPrint('social/activeUsers');
        
        $this->view->createdGroups = $this->model->getCreatedGroupsModel();
        $this->view->joinedGroups = $this->model->getJoinedGroupsModel();
        
        $this->view->mainLeft = $this->view->renderPrint('social/mainLeft');
        $this->view->mainRight = $this->view->renderPrint('social/mainRight');
        
        $this->view->render('social/header');
        $this->view->render('social/saved');
        $this->view->render('social/footer');
    }
    
    public function post($id = '') {
        
        if ($id == '') {
            Message::add('e', '', 'back');
        }
        
        $this->view->title = 'Сошиал';

        $this->view->js = array('custom/css/social/js/social.js');
        $this->view->css = array('custom/css/social/css/style.css');
        
        $this->view->userId = Ue::sessionUserId();
        
        $this->view->posts = $this->model->getLastPostsModel($this->view->userId, 1, 1, $id);
        $this->view->posts = $this->view->renderPrint('social/posts');
        
        $this->view->postsCount = $this->model->getSocialPostsCountModel();
        
        $this->view->activeUsersData = $this->model->getSocialActiveUsersModel(10);
        $this->view->activeUsers = $this->view->renderPrint('social/activeUsers');
        
        $this->view->createdGroups = $this->model->getCreatedGroupsModel();
        $this->view->joinedGroups = $this->model->getJoinedGroupsModel();
        
        $this->view->createPost = '';
        
        $this->view->mainLeft = $this->view->renderPrint('social/mainLeft');
        $this->view->mainRight = $this->view->renderPrint('social/mainRight');
        
        $this->view->render('social/header');
        $this->view->render('social/index');
        $this->view->render('social/footer');
    }
    
    public function groups() {
        
        $this->view->title = 'Сошиал - Бүлгэмүүд';

        $this->view->js = array('custom/css/social/js/social.js');
        $this->view->css = array('custom/css/social/css/style.css');
        
        $this->view->pageNumber = 1;
        
        $this->view->groups = $this->model->getSocialGroupsModel($this->view->pageNumber, 10);
        
        $this->view->postsCount = $this->model->getSocialPostsCountModel();
        
        $this->view->activeUsersData = $this->model->getSocialActiveUsersModel(10);
        $this->view->activeUsers = $this->view->renderPrint('social/activeUsers');
        
        $this->view->createdGroups = $this->model->getCreatedGroupsModel();
        $this->view->joinedGroups = $this->model->getJoinedGroupsModel();
        
        $this->view->mainLeft = $this->view->renderPrint('social/mainLeft');
        $this->view->mainRight = $this->view->renderPrint('social/mainRight');
        
        $this->view->render('social/header');
        $this->view->render('social/groups');
        $this->view->render('social/footer');
    }
    
    public function createGroupForm() {
        
        $response = array(
            'title' => 'Бүлгэм үүсгэх', 
            'html' => $this->view->renderPrint('social/createGroup'), 
            'save_btn' => $this->lang->line('save_btn'), 
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function createGroup() {
        $this->model->createGroupModel();
    }
    
    public function group($id = '') {
        
        if ($id == '') {
            Message::add('e', '', 'back');
        }
        
        $this->view->groupId = Input::param($id);
        $this->view->userId = Ue::sessionUserId();
        $this->view->row = $this->model->getGroupRowByIdModel($this->view->groupId, $this->view->userId);
        
        if (!$this->view->row) {
            Message::add('e', '', 'back');
        }
        
        $this->view->title = 'Сошиал - Бүлгэм - ' . $this->view->row['GROUP_NAME'];

        $this->view->js = array('custom/css/social/js/social.js');
        $this->view->css = array('custom/css/social/css/style.css');
        
        $this->view->privacyType = $this->view->row['PRIVACY_TYPE'];
        $this->view->isJoined = $this->view->row['USER_ID'] ? true : false;
        
        if ($this->view->privacyType == 'closed' && $this->view->isJoined == false) {
            Message::add('d', 'Хаалттай бүлэг тул боломжгүй байна!', 'back');
        }
        
        $this->view->isAdmin = ($this->view->userId == $this->view->row['CREATED_USER_ID']) ? true : false;
        
        $this->view->pageNumber = 1;
        
        $this->view->posts = $this->model->getGroupPostsModel($this->view->groupId, $this->view->userId, $this->view->pageNumber, 10);
        
        $this->view->posts = $this->view->renderPrint('social/posts');
        
        $this->view->members = $this->model->getGroupMembersModel($this->view->groupId, 1, 20);
        
        $this->view->createdGroups = $this->model->getCreatedGroupsModel();
        $this->view->joinedGroups = $this->model->getJoinedGroupsModel();
        
        $this->view->createPost = $this->view->renderPrint('social/createPost');
        $this->view->mainLeft = $this->view->renderPrint('social/mainLeft');
        
        if ($this->view->isAdmin && $this->view->privacyType == 'closed') {
            $this->view->mainRight = $this->view->renderPrint('social/groupRightAdmin');
        } else {
            $this->view->mainRight = $this->view->renderPrint('social/groupRight');
        }
        
        if ($this->view->row['COVER_PICTURE']) {
            $this->view->cover = 'storage/uploads/social/posts/images/' . $this->view->row['COVER_PICTURE'] .'?v='.strtotime($this->view->row['MODIFIED_TIME']);
        } else {
            $this->view->cover = 'assets/core/global/css/social/img/big-cover.jpg';
        }
        
        $this->view->render('social/header');
        $this->view->render('social/group');
        $this->view->render('social/footer');
    }
    
    public function joinGroup($id = '') {
        
        if ($id == '') {
            Message::add('e', '', 'back');
        }
        
        $this->model->joinGroupModel($id);
    }
    
    public function exitGroup($id = '') {
        
        if ($id == '') {
            Message::add('e', '', 'back');
        }
        
        $this->model->exitGroupModel($id);
    }
    
    public function editGroupForm() {
        
        $this->view->groupId = Input::post('groupId');
        $this->view->userId = Ue::sessionUserId();
        
        $this->view->row = $this->model->getSimpleGroupRowByIdModel($this->view->groupId);
        
        if ($this->view->userId == $this->view->row['CREATED_USER_ID']) {
            
            $response = array(
                'title' => 'Бүлэг засах', 
                'status' => 'success', 
                'html' => $this->view->renderPrint('social/editGroup'), 
                'save_btn' => $this->lang->line('save_btn'), 
                'close_btn' => $this->lang->line('close_btn')
            );
            
        } else {
            $response = array(
                'status' => 'error', 
                'message' => 'Access denied'
            );
        }
        
        echo json_encode($response); exit;
    }
    
    public function updateGroup() {
        $this->model->updateGroupModel();
    }
    
    public function addMemberForm() {
        
        $this->view->groupId = Input::post('groupId');
        $this->view->userId = Ue::sessionUserId();
        
        $this->view->row = $this->model->getSimpleGroupRowByIdModel($this->view->groupId);
        
        if ($this->view->userId == $this->view->row['CREATED_USER_ID']) {
            
            $this->view->users = $this->model->getGroupNotJoinedUsersModel($this->view->groupId);
            
            $response = array(
                'title' => 'Гишүүн нэмэх', 
                'status' => 'success', 
                'html' => $this->view->renderPrint('social/addMember'), 
                'save_btn' => $this->lang->line('save_btn'), 
                'close_btn' => $this->lang->line('close_btn')
            );
            
        } else {
            $response = array(
                'status' => 'error', 
                'message' => 'Access denied'
            );
        }
        
        echo json_encode($response); exit;
    }
    
    public function addMember() {
        $this->model->addMemberModel();
    }
    
    public function deleteGroup($groupId = '') {
        
        if ($groupId == '') {
            Message::add('e', '', 'back');
        }
        
        $groupId = Input::param($groupId);
        
        $this->model->deleteGroupModel($groupId);
    }
    
    public function removeFromGroup() {
        $response = $this->model->removeFromGroupModel();
        echo json_encode($response); exit;
    }
    
    public function scpost() {
        $this->view->title = 'Сошиал';
        $this->view->uniqId = Input::post('uniqId');
        
        $this->view->js = array('custom/css/social/js/social.js');
        $this->view->css = array('custom/css/social/css/style.css');
        
        $this->view->mainRow = Input::post('mainRow');
        $this->view->metaDataId = isset($this->view->mainRow['dvmetadataid']) ? $this->view->mainRow['dvmetadataid'] : '';
        $this->view->dvresult = false;
        if ($this->view->metaDataId) {
            $this->view->dvresult = true;
            $this->view->postsData = $this->model->fncRunDataview($this->view->metaDataId);
            $this->view->posts = $this->view->renderPrint('government/social/posts');
            $this->view->postsCount = sizeof($this->view->posts);
        } else {
            $this->view->posts = self::defaultPosts();
            $this->view->postsCount = $this->model->getSocialPostsCountModel();
        }
        
        $this->view->activeUsersData = $this->model->getSocialActiveUsersModel(10);
        $this->view->activeUsers = $this->view->renderPrint('social/activeUsers');
        
        $this->view->createdGroups = $this->model->getCreatedGroupsModel();
        $this->view->joinedGroups = $this->model->getJoinedGroupsModel();
        $this->view->createPost = $this->view->renderPrint('social/createPost');
        
        $this->view->mainLeft = '';
        $this->view->mainRight = $this->view->renderPrint('social/mainRight');
        
        if (!is_ajax_request()) {
            $this->view->ajax = false;
            $this->view->mainLeft = $this->view->renderPrint('social/mainLeft');
            
            $this->view->render('social/header');
            $this->view->render('social/index');
            $this->view->render('social/footer');
        } else {
            $this->view->ajax = true;
            $html = $this->view->renderPrint('government/intranet/sub/scCss');
            $html .= $this->view->renderPrint('/social/index', self::$viewName);
            //$html .= $this->view->renderPrint('assets/css/social/js/social.js', 'assets/custom');
            
            $response = array(
                'Title' => '',
                'Width' => '700',
                'uniqId' => $this->view->uniqId,
                'mainRow' => $this->view->mainRow,
                'Html' => $html,
                'save_btn' => Lang::line('save_btn'), 
                'close_btn' => Lang::line('close_btn')
            );
            echo json_encode($response);
        }
        
    }
}
