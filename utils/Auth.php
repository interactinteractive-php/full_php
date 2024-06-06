<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Auth {
    
    public static function handleLogin()
    {
        Session::init();
        
        $logged      = Session::isCheck(SESSION_PREFIX.'LoggedIn');
        $loggedGuest = Session::isCheck(SESSION_PREFIX.'LoggedGuest');
        
        if ($logged == false && $loggedGuest == false) {
            
            Session::destroy();
            
            if (is_ajax_request() == false) {
                
                Session::init();
                Session::set(SESSION_PREFIX.'_redirect_url', Uri::currentURL());
                
                Message::add('s', '', AUTH_URL.'login');
                
            } else {

                $response = '{"status": "error", "message": "'.Lang::line('sessionExpired').'", "rows": [], "total": 0, "sessionCase": 2}';
                echo $response; exit;
            }
            
        } elseif ($logged == true) {
            
            SessionSetHandler::update();
            
        } elseif ($loggedGuest == true && is_ajax_request() == false) {
            
            Session::destroy();
            Message::add('s', '', URL . 'logout');
        }
        
        Lang::load('main');
    }

    public static function isLogin()
    {
        Session::init();
        
        if (Session::isCheck(SESSION_PREFIX.'LoggedIn')) {
            
            $logged = Session::get(SESSION_PREFIX.'LoggedIn');
            $userKeyId = Session::get(SESSION_PREFIX.'userkeyid');
            
            if ($logged == true && $userKeyId) {
                
                Message::add('s', '', URL . Config::getFromCache('CONFIG_START_LINK'));
            }
        }
    }

}
