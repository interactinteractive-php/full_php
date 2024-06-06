<?php if(!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Chat extends Controller {

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }
    
    public function index() {   
        $response = $this->model->getChatUsersModel();
        header('Content-Type: application/json');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function message() {
        $response = $this->model->saveChatMessageModel();
        header('Content-Type: application/json');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function getChatHistoryMessages() {
        $response = $this->model->getChatHistoryMessagesModel();
        header('Content-Type: application/json');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function attachFile() {
        $response = $this->model->chatAttachFileModel();
        header('Content-Type: application/json');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function downloadFile($mid = '') {
            
        $response = $this->model->chatDownloadFileModel($mid);
        
        if ($response['status'] == 'success') {
            
            fileDownload($response['realFileName'].'.'.$response['fileExtension'], $response['fullPath']);
            
        } else {
            
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=false; path=/');
            
            echo $response['message']; 
        }
        
        exit;
    }
    
    public function readMessage() {
        $response = $this->model->chatReadMessageModel();
        header('Content-Type: application/json');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function checkRemoveChatMsg() {
        $response = $this->model->checkRemoveChatMsgModel();
        header('Content-Type: application/json');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function removeChatMsg() {
        $response = $this->model->removeChatMsgModel();
        header('Content-Type: application/json');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function removeMassChatMsg() {
        $response = $this->model->removeMassChatMsgModel();
        header('Content-Type: application/json');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function addGroup() {
        
        $controlConfig = array(
            'META_DATA_CODE' => 'sendChatUsers', 
            'LOWER_PARAM_NAME' => 'sendchatusers', 
            'PARAM_REAL_PATH' => 'sendChatUsers', 
            'LOOKUP_META_DATA_ID' => '1565070936581248', 
            'LOOKUP_KEY_META_DATA_ID' => null, 
            'LOOKUP_TYPE' => 'combo_with_popup', 
            'CHOOSE_TYPE' => 'multi', 
            'META_DATA_NAME' => 'Users', 
            'LABEL_NAME' => 'Users', 
            'ATTRIBUTE_ID_COLUMN' => 'id', 
            'ATTRIBUTE_CODE_COLUMN' => null, 
            'ATTRIBUTE_NAME_COLUMN' => 'name', 
            'META_TYPE_CODE' => 'long', 
            'IS_SHOW' => '1', 
            'IS_REQUIRED' => '0', 
            'DEFAULT_VALUE' => null, 
            'DISPLAY_FIELD' => null, 
            'VALUE_FIELD' => null, 
            'IS_REFRESH' => '0', 
            'GROUP_PARAM_CONFIG_TOTAL' => '0', 
            'GROUP_PARAM_CONFIG_COUNT' => '0', 
            'GROUP_CONFIG_PARAM_PATH' => null, 
            'GROUP_CONFIG_LOOKUP_PATH' => null, 
            'GROUP_CONFIG_PARAM_PATH_GROUP' => null, 
            'GROUP_CONFIG_FIELD_PATH_GROUP' => null, 
            'GROUP_CONFIG_FIELD_PATH' => null, 
            'GROUP_CONFIG_GROUP_PATH' => null
        );
        
        $this->view->control = Mdwebservice::renderParamControl(1, $controlConfig, 'sendChatUsers', '', null);
        
        $response = array(
            'html' => $this->view->renderPrint('client/tpls/addGroup', 'chat/')
        );
        
        header('Content-Type: application/json');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function createGroup() {
        $response = $this->model->createChatGroupModel();
        header('Content-Type: application/json');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function getGroups() {
        $response = $this->model->getChatGroupsModel();
        header('Content-Type: application/json');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function editGroup() {
        
        $groupId = Input::numeric('groupId');
        
        $this->view->row = $this->model->getChatGroupModel($groupId);
        $this->view->isAdmin = ($this->view->row['CREATED_USER_ID'] == Ue::sessionUserId()) ? true : false;
        
        $members = $this->model->getChatGroupMembersModel($groupId);
        
        $this->view->membersCount = count($members);
        $this->view->members = json_encode($members);
        
        $response = array(
            'html' => $this->view->renderPrint('client/tpls/editGroup', 'chat/')
        );
        
        jsonResponse($response);
    }
    
    public function updateGroup() {
        $response = $this->model->updateChatGroupModel();
        jsonResponse($response);
    }
    
    public function groupDelete() {
        $response = $this->model->deleteChatGroupModel();
        jsonResponse($response);
    }
    
    public function groupExit() {
        $response = $this->model->exitChatGroupModel();
        jsonResponse($response);
    }
    
    public function groupMessage() {
        $response = $this->model->saveGroupChatMessageModel();
        jsonResponse($response);
    }
    
    public function getGroupChatHistoryMessages() {
        $response = $this->model->getGroupChatHistoryMessagesModel();
        jsonResponse($response);
    }
    
    public function massMessageForm() {
        
        $controlConfig = array(
            'META_DATA_CODE' => 'sendChatUsers', 
            'LOWER_PARAM_NAME' => 'sendchatusers', 
            'PARAM_REAL_PATH' => 'sendChatUsers', 
            'LOOKUP_META_DATA_ID' => '1565070936581248', 
            'LOOKUP_KEY_META_DATA_ID' => null, 
            'LOOKUP_TYPE' => 'combo_with_popup', 
            'CHOOSE_TYPE' => 'multi', 
            'META_DATA_NAME' => 'Users', 
            'LABEL_NAME' => 'Users', 
            'ATTRIBUTE_ID_COLUMN' => 'id', 
            'ATTRIBUTE_CODE_COLUMN' => null, 
            'ATTRIBUTE_NAME_COLUMN' => 'name', 
            'META_TYPE_CODE' => 'long', 
            'IS_SHOW' => '1', 
            'IS_REQUIRED' => '0', 
            'DEFAULT_VALUE' => null, 
            'DISPLAY_FIELD' => null, 
            'VALUE_FIELD' => null, 
            'IS_REFRESH' => '0', 
            'GROUP_PARAM_CONFIG_TOTAL' => '0', 
            'GROUP_PARAM_CONFIG_COUNT' => '0', 
            'GROUP_CONFIG_PARAM_PATH' => null, 
            'GROUP_CONFIG_LOOKUP_PATH' => null, 
            'GROUP_CONFIG_PARAM_PATH_GROUP' => null, 
            'GROUP_CONFIG_FIELD_PATH_GROUP' => null, 
            'GROUP_CONFIG_FIELD_PATH' => null, 
            'GROUP_CONFIG_GROUP_PATH' => null
        );
        
        $this->view->control = Mdwebservice::renderParamControl(1, $controlConfig, 'sendChatUsers', '', null);
        
        $response = array(
            'html' => $this->view->renderPrint('client/tpls/massMessage', 'chat/')
        );
        
        jsonResponse($response);
    }
    
    public function sendMassMessage() {
        $response = $this->model->chatSendMassMessageModel();
        jsonResponse($response);
    }
    
    public function saveContactViewMode() {
        $response = $this->model->saveContactViewModeModel();
        header('Content-Type: application/json');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function getChatOrgList() {
        $response = $this->model->getChatOrgListModel();
        header('Content-Type: application/json');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function getChatContactsByOrgId() {
        $response = $this->model->getChatContactsByOrgIdModel();
        header('Content-Type: application/json');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function deleteConversations() {
        $response = $this->model->chatDeleteConversationsModel();
        jsonResponse($response);
    }
    
    public function saveUserStatus() {
        $response = $this->model->saveChatUserStatusModel();
        jsonResponse($response);
    }
    
    public function getUserStatus() {
        $response = $this->model->getChatUserStatusModel();
        header('Content-Type: application/json');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function activeUserStatus() {
        $response = $this->model->activeChatUserStatusModel();
        jsonResponse($response);
    }
    
    public function updateUserStatus() {
        $response = $this->model->updateChatUserStatusModel();
        jsonResponse($response);
    }
    
    public function deleteUserStatus() {
        $response = $this->model->deleteChatUserStatusModel();
        jsonResponse($response);
    }
    
    public function getUserInfo() {
        $response = $this->model->getChatUserInfoModel();
        header('Content-Type: application/json');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function chatForwardMessage() {
        $response = $this->model->chatForwardMessageModel();
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
}