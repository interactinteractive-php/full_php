<?php if(!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Webmail extends Controller {

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }
    
    public function index() 
    {   
        $this->view->title = 'Webmail'; 
        
        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        $this->view->isAjax = is_ajax_request();
        
        $row = $this->model->getUserEmailModel();
        
        if (!$this->view->isAjax) {
            $this->view->render('header');
        }
        
        if ($row) {
            
            $password = Crypt::decrypt($row['PASSWORD_HASH']);
            
            $_ENV['RAINLOOP_INCLUDE_AS_API'] = true;
            require 'rainloop/index.php';
            
            $this->view->ssoHash = \RainLoop\Api::GetUserSsoHash($row['EMAIL'], $password, array('Language' => $this->lang->getCode()));
            $this->view->render('webmail/index');
            
        } else {
            $this->view->render('webmail/setmail');
        }
        
        if (!$this->view->isAjax) {
            $this->view->render('footer');   
        }
    }
    
    public function testmail()
    {
        $response = $this->model->webmailLoginCheckModel();
        
        $isAjax = Input::post('isAjax');
        
        if ($isAjax == '1') {
            
            $this->view->isAjax = is_ajax_request();
            
            $_ENV['RAINLOOP_INCLUDE_AS_API'] = true;
            require 'rainloop/index.php';
            
            $this->view->ssoHash = \RainLoop\Api::GetUserSsoHash($response['email'], $response['password'], array('Language' => $this->lang->getCode()));
            
            $response['iframe'] = $this->view->renderPrint('webmail/index');
        }
        
        if ($response['status'] == 'success') {
            unset($response['email']);
            unset($response['password']);
        }
            
        echo json_encode($response); exit;
    }
    
    public function getSsoHash() 
    {   
        $row = $this->model->getUserEmailModel();
        
        if ($row) {
            
            $password = Crypt::decrypt($row['PASSWORD_HASH']);
            
            $_ENV['RAINLOOP_INCLUDE_AS_API'] = true;
            require 'rainloop/index.php';
            
            $ssoHash = \RainLoop\Api::GetUserSsoHash($row['EMAIL'], $password, array('Language' => $this->lang->getCode()));
            
            $response = array('status' => 'success', 'ssoHash' => $ssoHash);
            
        } else {
            $response = array('status' => 'error', 'message' => 'Тохиргоо хийнэ үү');
        }    
        
        echo json_encode($response); exit;
    }
    
}