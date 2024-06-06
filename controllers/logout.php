<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Logout extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        
        Session::init();
        
        $isRunProcess = Config::getFromCache('IS_RUN_PROCESS_BEFORE_LOGOUT');
        
        if ($isRunProcess == '1') {
            
            $param = array(
                'sessionUserId' => Ue::sessionUserKeyId()
            );
            $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'confirmLogOut', $param);
            
            if (isset($result['result']['result']) && $result['result']['result'] == '0') {
                Message::add('s', '', URL . 'mdobject/dataview/1505265269375');
            }
        }
        
        $display      = '';
        $msgType      = 's';
        $skipOauth    = Session::get(SESSION_PREFIX . 'skipOauth');
        $isCheckLogin = Config::getFromCache('is_Check_Login_Failed');
        
        if ($isCheckLogin) {
            $this->db->AutoExecute('UM_LOGIN_ATTEMPTS', array('LOGOUT_DATE' => Date::currentDate()), 'UPDATE', "SESSION_ID = '".Session::get(SESSION_PREFIX . 'appusersessionid')."'");
        }
        
        SessionSetHandler::destroy();
        
        $this->ws->runResponse(GF_SERVICE_ADDRESS, 'logout');
        
        if (isset($_SESSION['flash_messages'])) {
            
            $display = Message::display();
            $msgType = Message::$msgType;  
        } 

        Session::destroy();
        
        if ($skipOauth) {
            Message::add($msgType, $display, AUTH_URL . 'login/index/redirect');
        }         
        
        Message::add($msgType, $display, AUTH_URL . 'login');
    }

}
