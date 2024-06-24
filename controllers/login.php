<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Login extends Controller {
    
    public static $ipAddress = null;
    public static $browserName = null;
    public static $platformName = null;
    public static $userAgent = null;
    public static $trackType = null;
    public static $cookieDays = 30;
    public static $failCookieKey = '8dd0bcaf3586459aca5bda8a35439445';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index($redirect = '') {
        
        Auth::isLogin();
        
        $callbackUrl = Config::getFromCache('CONFIG_OAUTH_REDIRECT_URL');
        
        if ($callbackUrl && $redirect === '') {

            if (isset($_SESSION['flash_messages'])) {
                echo 'Auth error: ' . Message::display(); die;
            }
            
            $client_id = Config::getFromCache('CONFIG_OAUTH_CLIENT_ID');
            Message::add('s', '', $callbackUrl . '?client_id=' . $client_id . '&redirect_uri=' . URL . 'login/sisiOauth&state=OSCD6SX&scope=bio&response_type=code');
        }                        
        
        $this->view->title = $this->lang->line('login_title');
        
        $this->view->isDan = Config::getFromCache('CONFIG_USE_DAN');
        $this->view->isKhalamj = Config::getFromCache('CONFIG_USE_KHALAMJ');
        $this->view->isEToken = Config::getFromCache('CONFIG_USE_ETOKEN');
        $this->view->isEFinger = Config::getFromCache('CONFIG_USE_EFINGER');
        $this->view->isFinger = Config::getFromCache('CONFIG_USE_FINGER');
        $this->view->isLDap = Config::getFromCache('CONFIG_USE_LDAP');
        $this->view->isPhoneSign = Config::getFromCache('CONFIG_USE_PHONELOGIN');
        $this->view->isLoginSaveUsername = Config::getFromCache('PF_IS_LOGIN_SAVE_USERNAME');
        $this->view->isSupplierRegister = Config::getFromCache('PF_SUPPLIER_REGISTER_INDICATOR_ID');
        $this->view->isPasswordReset = true;
        $this->view->csrf_token = $this->model->getCsrfTokenModel();
        $this->view->selectMultiDbControl = $this->model->selectMultiDbControl();
        
        if ($this->view->isLDap) {
            
            $this->view->ldapControl = $this->model->ldapControl();
            
            if ($this->view->ldapControl != '') {
                $this->view->isPasswordReset = false;
            }
            
        } else {
            $this->view->ldapControl = null;
        }
        
        $loginLayout    = Config::getFromCache('loginLayout'); 
        $configMainLogo = Config::getFromCache('main_logo_path');
        $configLogo     = Config::getFromCache('logo_path');
        
        if ($configMainLogo && file_exists($configMainLogo)) {
            $this->view->mainLogo = $configMainLogo;  
        } 
        
        if ($configLogo && file_exists($configLogo)) {
            $this->view->logo = $configLogo;
        } 
        
        $this->view->mainLoginTitle = Config::getFromCacheDefault('login_main_title', null, 'Business in the Cloud');
        $this->view->loginTitle = Config::getFromCacheDefault('login_title', null, 'Орчин үеийн стратеги төлөвлөлт, удирдлагын онлайн ERP цогц шийдэл');
        $this->view->color_mode = Config::getFromCacheDefault('center_login_mode', null, '');
        $this->view->loginFooterTitle = Config::getFromCacheDefault('loginFooterTitle', null, '<span>Powered by</span> <a href="https://veritech.mn/" target="_blank" rel="noopener noreferrer" style="color:#024490">Veritech ERP</a>');
        
        $this->view->background = $this->model->getBackgroundImagesModel();
        
        if (Login::isCheckLoginFailed()) {
            
            $captchFailedCount = Config::getFromCache('captch_Login_Failed_Count');
            $failedCount       = (int) (new self())->getClientFailLoginCount();
            
            if ($failedCount > 0 && $failedCount >= $captchFailedCount) { /* Captcha failed */
                $this->view->isLoginCaptcha = true;
            }
        }
        
        if ($loginLayout && file_exists('views/login/layout/' . $loginLayout . '/index.php')) {
            
            $this->view->selectLoginTypeControl = $this->model->selectLoginTypeControl();
			
            $this->view->render('login/layout/'.$loginLayout.'/header');
            $this->view->render('login/layout/'.$loginLayout.'/index');
            $this->view->render('login/layout/'.$loginLayout.'/footer');
        } else {
            $this->view->render('login/header');
            $this->view->render('login/index');
            $this->view->render('login/footer');
        }
    }
    
    public function run() {
        
        Auth::isLogin();
        
        if (Config::getFromCache('CONFIG_USE_PHONELOGIN') === '1' && Input::post('isPhoneSign') === '1') {
            self::runPhoneNumber();
        }

        $response = $this->model->runModel();

        if (Config::getFromCache('custom_login') == 'statebank') {
            $this->chooseUserRoleCustomLogin($response);    
            exit;
        }        
        
        $this->chooseUserKey($response);
    }

    private function chooseUserKey($response, $isDeviceVerification = true) {
        
        if (isset($response['userkeys']) && is_array($response['userkeys'])) {
            
            $this->view->systemUserId = $response['id'];
            $this->view->userkeys = $response['userkeys'];
            $count = count($this->view->userkeys);
            
            if ($count > 0) {
                
                if ($isDeviceVerification) {
                    $this->deviceVerification($this->view->userkeys);
                }
                
                if ($count == 1) {

                    $this->model->connectClientModel($response['userkeys'][0]['id'], $this->view->systemUserId);
                    
                } else {
                
                    includeLib('Compress/Compression');

                    Session::set(SESSION_PREFIX . 'userKeyCount', $count);

                    $configMainLogo = Config::getFromCache('main_logo_path');

                    if ($configMainLogo && file_exists($configMainLogo)) {
                        $this->view->mainLogo = $configMainLogo;
                    }
                    
                    $userKeyData = $this->model->getUserKeyParentChildModel();
                    
                    if ($userKeyData) {
                        
                        if ($userKeyData['status'] == 'success') {
                            
                            $this->view->userkeys = $userKeyData['data'];
                            $this->view->userKeysRender = $this->view->renderPrint('profile/userkey/parentChildRender');
                            $viewPath = 'profile/userkey/parentChild';
                            
                        } else {
                            Message::add('d', $userKeyData['message'], AUTH_URL . 'logout');
                        }
                        
                    } else {
                        $viewPath = 'profile/userkey/index';
                    }

                    $this->view->render('profile/userkey/header');
                    $this->view->render($viewPath);
                    $this->view->render('profile/userkey/footer');
                }
                
            } else {
                Message::add('d', 'Нэвтрэх систем тохируулагдаагүй байна. Та систем хариуцсан ажилтантай холбогдоно уу.', AUTH_URL.'login');
            }
            
        } else {
            Message::add('d', 'Уг хэрэглэгчид нэвтрэх эрхтэй систем олдсонгүй. Та эрхээ нээлгэнэ үү.', AUTH_URL . 'login');
        }
    }
    
    public function deviceVerification($userKeys) {
        
        $isDeviceVerificationByEmail = Config::getFromCache('isDeviceVerificationByEmail');
        $isDeviceVerificationByPhone = Config::getFromCache('isDeviceVerificationByPhone');
        
        if ($isDeviceVerificationByEmail || $isDeviceVerificationByPhone) {
            
            $this->view->email = null;
            $this->view->phoneNumber = null;
            
            $check = $this->model->isDeviceCertifiedModel();
            
            if ($isDeviceVerificationByEmail) {
                $this->view->email = trim(Session::get(SESSION_PREFIX . 'email'));
            }
            
            if ($isDeviceVerificationByPhone) {
                $this->view->phoneNumber = trim(Session::get(SESSION_PREFIX . 'mobile'));
            }

            if (!$check && ($this->view->email || $this->view->phoneNumber)) {
                
                Session::set(SESSION_PREFIX . 'userkeys', $userKeys);
                
                $configMainLogo = Config::getFromCache('main_logo_path');
        
                if ($configMainLogo && file_exists($configMainLogo)) {
                    $this->view->mainLogo = $configMainLogo;  
                } 

                $this->view->personName = Session::get(SESSION_PREFIX . 'personname');
                $this->view->step1      = $this->view->renderPrint('login/verification/step1');

                $this->view->render('profile/userkey/header');
                $this->view->render('login/verification/index');
                $this->view->render('profile/userkey/footer');

                exit;
            }
        }
    }

    public function connectClient($encryptedId, $systemUserId) {
        
        includeLib('Compress/Compression');
        
        $encryptedId = Str::urlCharReplace($encryptedId, true);
        $decryptedId = Compression::gzinflate($encryptedId);

        $this->model->connectClientModel($decryptedId, $systemUserId);
    }

    public function sign($data = null) {
        
        if (is_null($data)) {
            Message::add('d', 'Буруу үйлдэл хийсэн байна. (s)', AUTH_URL . 'logout');
        }
        
        includeLib('Compress/Compression');
        
        $data = Str::urlCharReplace($data, true);
        $data = Compression::gzinflate($data);
        
        $result = explode('$', $data);
        
        if (count($result) != 4) {
            Message::add('d', 'Буруу үйлдэл хийсэн байна. (c)', AUTH_URL . 'logout');
        }
        
        $decryptedSessionId     = $result[0];
        $decryptedUserKeyId     = $result[1];
        $decryptedSystemUserId  = $result[2];
        $encryptedNowDate       = $result[3];

        Session::init();
        
        if (!$decryptedSessionId || !$decryptedUserKeyId || !$decryptedSystemUserId || !$encryptedNowDate) {
            file_put_contents('log/login.txt', var_export($_SESSION, true));
            Message::add('d', 'Буруу үйлдэл хийсэн байна. (i) ' . $data, AUTH_URL . 'logout');
        }

        if ((strtotime(Date::currentDate('Y-m-d H:i:s')) - strtotime($encryptedNowDate)) > 10) {
            Message::add('d', 'Буруу үйлдэл хийсэн байна. (d)', AUTH_URL . 'logout');
        }
        
        $response = $this->model->getUserKeyRow($decryptedUserKeyId, $decryptedSystemUserId);
        
        $personName = ($response['LAST_NAME']) ? Str::upper(Str::utf8_substr($response['LAST_NAME'], 0, 1)) . '.' . $response['PERSON_NAME'] : $response['PERSON_NAME'];
        $defaultModuleId = ($response['DEFAULT_MENU_ID']) ? $response['DEFAULT_MENU_ID'] : $response['MODULE_MENU_ID'];
        
        Session::delete(SESSION_PREFIX . 'LoggedGuest');
        
        Session::set(SESSION_PREFIX . 'LoggedIn', true);
        Session::set(SESSION_PREFIX . 'appusersessionid', $decryptedSessionId);
        Session::set(SESSION_PREFIX . 'userkeyid', $decryptedUserKeyId);

        Session::set(SESSION_PREFIX . 'ConnectedAndLoggedIn', true);
        Session::set(SESSION_PREFIX . 'userid', $response['USER_ID']);
        Session::set(SESSION_PREFIX . 'username', $response['USERNAME']);
        Session::set(SESSION_PREFIX . 'password', $response['PASSWORD_HASH']);
        Session::set(SESSION_PREFIX . 'firstname', $response['PERSON_NAME']);
        Session::set(SESSION_PREFIX . 'personname', $personName);

        Session::set(SESSION_PREFIX . 'userKeyCompanyName', $response['COMPANY_NAME']);
        Session::set(SESSION_PREFIX . 'picture', $response['EMP_PICTURE']);
        Session::set(SESSION_PREFIX . 'position', $response['POSITION_NAME']);
        Session::set(SESSION_PREFIX . 'employeeid', $response['EMPLOYEE_ID']);
        Session::set(SESSION_PREFIX . 'employeekeyid', $response['EMPLOYEE_KEY_ID']);
        Session::set(SESSION_PREFIX . 'userKeyDepartmentId', $response['DEPARTMENT_ID']);
        Session::set(SESSION_PREFIX . 'personid', $response['PERSON_ID']);
        Session::set(SESSION_PREFIX . 'monpassGUID', $response['MONPASS_GUID']);
        Session::set(SESSION_PREFIX . 'roleid', $response['ROLE_ID']);
        Session::set(SESSION_PREFIX . 'isUseFolderPermission', $response['IS_USE_FOLDER_PERMISSION']);
        Session::set(SESSION_PREFIX . 'touchMode', $response['IS_TOUCH_MODE']);
        Session::set(SESSION_PREFIX . 'isUseChat', $response['IS_USE_CHAT']);
        Session::set(SESSION_PREFIX . 'defaultModuleId', $defaultModuleId);
        Session::set(SESSION_PREFIX . 'SSID', session_id());
        Session::set(SESSION_PREFIX . 'isTranslateUser', $response['IS_TRANSLATE_USER']);

        if (Lang::isUseMultiLang()) {
            if ($response['LANG_COUNT'] == '1') {
                
                $langRow = $this->model->getDefaultLanguageRow($response['DEPARTMENT_ID']);
                
                Session::set(SESSION_PREFIX . 'langid', $langRow['LANGUAGE_ID']);
                Session::set(SESSION_PREFIX . 'langcode', $langRow['LANGUAGE_CODE']);
                Session::set(SESSION_PREFIX . 'langshortcode', $langRow['LANG_SHORT_CODE']);
                
            } elseif ($response['LANGUAGE_ID'] != '') {
                Session::set(SESSION_PREFIX . 'langid', $response['LANGUAGE_ID']);
                Session::set(SESSION_PREFIX . 'langcode', $response['LANGUAGE_CODE']);
                Session::set(SESSION_PREFIX . 'langshortcode', $response['LANG_SHORT_CODE']);
            } 
        } else {
            Session::set(SESSION_PREFIX . 'langid', Config::getFromCache('LANG_ID'));
            Session::set(SESSION_PREFIX . 'langcode', Config::getFromCache('LANG_NAME'));
            Session::set(SESSION_PREFIX . 'langshortcode', Config::getFromCache('LANG'));
        }
        
        Session::set(SESSION_PREFIX . 'langCount', $response['LANG_COUNT']);
        
        if (Config::getFromCache('IS_URL_AUTHENTICATE') == '1') {
            Session::set(SESSION_PREFIX . 'isUrlAuthenticate', true);
        }
        
        $checkDefaultPass = $this->model->checkDefaultPasswordModel($response['USER_ID']);
        
        if ($checkDefaultPass) {
            Session::set(SESSION_PREFIX . 'passwordCheck', $checkDefaultPass);
        }
        
        Ue::loginCacheClear();
        Ue::startFiscalPeriod();

        if (Session::isCheck(SESSION_PREFIX . '_redirect_url')) {

            $redirect_url = Session::get(SESSION_PREFIX . '_redirect_url');
            Session::delete(SESSION_PREFIX . '_redirect_url');
            
        } else {
            $redirect_url = URL . Config::getFromCacheDefault('CONFIG_START_LINK', null, 'appmenu');
        }
        
        $appMenu = Controller::loadController('Appmenu');
        $redirect_url = $appMenu->redirectModule($redirect_url, $defaultModuleId);
        
        Message::add('s', '', $redirect_url);
    }
    
    public function password_reset() {
        
        Auth::isLogin();
        
        if (Config::getFromCache('hideLoginForgotPassword') == '1') {
            Message::add('s', '', URL . 'login');
        }
        
        $this->view->title = $this->lang->line('password_recovery');
        
        $this->view->csrf_token = $this->model->getCsrfTokenModel('ps');
        
        $configMainLogo   = Config::getFromCache('main_logo_path');
        $configLogo       = Config::getFromCache('logo_path');
        $configBackground = Config::getFromCache('login_background_image1');
        $loginLayout      = Config::getFromCache('loginLayout'); 

        if ($configMainLogo && file_exists($configMainLogo)) {
            $this->view->mainLogo = $configMainLogo;  
        } 
        
        if ($configLogo && file_exists($configLogo)) {
            $this->view->logo = $configLogo;
        } 
        
        $this->view->mainLoginTitle   = Config::getFromCacheDefault('login_main_title', null, 'Business in the Cloud');
        $this->view->loginTitle       = Config::getFromCacheDefault('login_title', null, 'Орчин үеийн стратеги төлөвлөлт, удирдлагын онлайн ERP цогц шийдэл');
        $this->view->passwordByPhone  = Config::getFromCache('RECOVER_PASSWORD_BY_PHONE');
        $this->view->loginFooterTitle = Config::getFromCacheDefault('loginFooterTitle', null, '<span>Powered by</span> <a href="http://veritech.mn/" target="_blank">Veritech ERP</a>');
        $this->view->infoMessage      = Config::getFromCache('passwordResetInfoMessage');
        
        if ($configBackground && file_exists($configBackground)) {
            $this->view->background = $configBackground;
        } else {
            $this->view->background = null;
        }
        
        $this->view->selectMultiDbControl = $this->model->selectMultiDbControl();
        
        if ($loginLayout && file_exists('views/login/layout/'.$loginLayout.'/password_reset.php')) {
            $this->view->selectLoginTypeControl = $this->model->selectLoginTypeControl();
            $this->view->background = $this->model->getBackgroundImagesModel();
            
            $this->view->render('login/layout/'.$loginLayout.'/header');
            $this->view->render('login/layout/'.$loginLayout.'/password_reset');
            $this->view->render('login/layout/'.$loginLayout.'/footer_password_reset');
            
        } else {
            $this->view->render('login/header');
            $this->view->render('login/password_reset');
            $this->view->render('login/footer_password_reset'); 
        }
    }
    
    public function send_password() {
        Auth::isLogin();
        $this->model->sendPasswordModel();
    }
    
    public function activeAccountByPass($hash = '') {
        Auth::isLogin();
        
        if ($hash == '') {
            Message::add('d', '', AUTH_URL . 'login');
        }
        
        $hash = Input::param($hash);
        $userRow = $this->model->getUserRowByOtpHashModel($hash);
        
        if (!$userRow) {
            Message::add('d', '', AUTH_URL . 'login');
        }
        
        $this->view->title = $this->lang->line('password_recovery');
        
        $this->view->csrf_token = $this->model->getCsrfTokenModel('aap');
        $this->view->userId = Crypt::encrypt($userRow['USER_ID'], 'aap');
        $this->view->hash = Crypt::encrypt($hash, 'aap');
        
        $configMainLogo   = Config::getFromCache('main_logo_path');
        $configLogo       = Config::getFromCache('logo_path');
        $configBackground = Config::getFromCache('login_background_image1');

        if ($configMainLogo && file_exists($configMainLogo)) {
            $this->view->mainLogo = $configMainLogo;  
        } 
        
        if ($configLogo && file_exists($configLogo)) {
            $this->view->logo = $configLogo;
        } 
        
        $this->view->mainLoginTitle   = Config::getFromCacheDefault('login_main_title', null, 'Business in the Cloud');
        $this->view->loginTitle       = Config::getFromCacheDefault('login_title', null, 'Орчин үеийн стратеги төлөвлөлт, удирдлагын онлайн ERP цогц шийдэл');
        $this->view->loginFooterTitle = Config::getFromCacheDefault('loginFooterTitle', null, '<span>Powered by</span> <a href="http://veritech.mn/" target="_blank">Veritech ERP</a>');
        
        if ($configBackground && file_exists($configBackground)) {
            $this->view->background = $configBackground;
        } else {
            $this->view->background = null;
        }
        
        $this->view->render('login/header');
        $this->view->render('login/activeAccountByPass/index');
        $this->view->render('login/activeAccountByPass/footer'); 
    }
    
    public function activeAccountByPassSubmit() {
        Auth::isLogin();
        $this->model->activeAccountByPassModel();
    }
    
    public function cloud($data) {
        
        if (is_null($data)) {
            Message::add('d', 'Буруу үйлдэл хийсэн байна. (cl)', AUTH_URL . 'logout');
        }
        
        includeLib('Compress/Compression');
        
        $data = Str::urlCharReplace($data, true);
        $data = Compression::gzinflate($data);
        parse_str($data, $dataArr);
        
        if (((isset($dataArr['u']) && isset($dataArr['p'])) || (isset($dataArr['uk']))) 
            && isset($dataArr['d']) && isset($dataArr['t'])) {
            
            $encryptedNowDate = $dataArr['d'].' '.$dataArr['t'];
            
            if ((strtotime(Date::currentDate('Y-m-d H:i:s')) - strtotime($encryptedNowDate)) > 20) {
                Message::add('d', 'Буруу үйлдэл хийсэн байна. (cld)', AUTH_URL . 'logout');
            }
            
            if (isset($dataArr['uk'])) {
                
                $userKeyId = $dataArr['uk'];
                $userRow = $this->model->getUserNamePassByKeyIdModel($userKeyId);
                
                if ($userRow) {
                    $dataArr['u'] = $userRow['USERNAME'];
                    $dataArr['p'] = $userRow['PASSWORD_HASH'];
                }
            }
            
            $_POST['csrf_token'] = $this->model->getCsrfTokenModel();
            $_POST['user_name'] = $dataArr['u'];
            $_POST['pass_word'] = $dataArr['p'];
            $_POST['monpassUserId'] = null;
            $_POST['isHash'] = 1;

            $response = $this->model->runModel();

            if (isset($response['userkeys']) && is_array($response['userkeys'])) {
                
                if (isset($dataArr['l'])) {
                    Session::set(SESSION_PREFIX . '_redirect_url', URL . Str::urlCharAndReplace($dataArr['l'], true));
                } else {
                    Session::set(SESSION_PREFIX . '_redirect_url', URL . 'appmenu');
                }
                
                $this->view->userkeys = $response['userkeys'];
                $count = count($this->view->userkeys);
                
                if (isset($userKeyId)) {
                        
                    foreach ($this->view->userkeys as $uk) {
                        if ($uk['id'] == $userKeyId) {
                            $acceptUserKeyId = $uk['id'];
                            break;
                        }
                    }

                    if (isset($acceptUserKeyId)) {
                        $this->model->connectClientModel($acceptUserKeyId, $userRow['SYSTEM_USER_ID']);
                    } else {
                        Message::add('d', 'Буруу үйлдэл хийсэн байна. (uk)', AUTH_URL . 'logout');
                    }
                }

                if ($count > 1) {
                    
                    $this->view->render('profile/userkey/header');
                    $this->view->render('profile/userkey/index');
                    $this->view->render('profile/userkey/footer');

                } elseif ($count == 1) {

                    $this->model->connectClientModel($response['userkeys'][0]['id']);

                } else {
                    Message::add('d', 'Нэвтрэх систем тохируулагдаагүй байна. Та систем хариуцсан ажилтантай холбогдоно уу.', AUTH_URL.'login');
                }

            } else {
                Message::add('d', 'Уг хэрэглэгчид нэвтрэх эрхтэй систем олдсонгүй. Та эрхээ нээлгэнэ үү.', AUTH_URL . 'login');
            }
            
        } else {
            Message::add('d', 'Буруу үйлдэл хийсэн байна. (cli)', AUTH_URL . 'logout');
        }
    }
    
    public function sessionExpired() {
        
        if (Input::numeric('sessionDestroy') == 1) {
            Session::destroy();
        }
        
        if (Config::getFromCache('CONFIG_USE_LDAP')) {
            $this->view->ldapControl = $this->model->ldapControl();
        } else {
            $this->view->ldapControl = null;
        }
        
        $this->view->render('login/session_expired');
    }
    
    public function sessionExpiredLogin() {
        $response = $this->model->sessionExpiredLoginModel();
        echo json_encode($response); exit;
    }
    
    public static function isCheckLoginFailed() {
        $is = Config::getFromCache('is_Check_Login_Failed');
        return $is;
    }
    
    public static function loginFailedTrackType() {
        $trackType = Config::getFromCache('login_Failed_Track_Type');
        return $trackType;
    }
    
    public static function getClientFailLoginCount($defaultTrackType = null) {
        
        if (!$defaultTrackType) {
            $trackType = self::loginFailedTrackType();
        } else {
            $trackType = $defaultTrackType;
        }
        
        if ($trackType == 'ipaddress') {
            
            $loginIns = &getInstance();
            $loginIns->load->model('login');
            
            $ipAddress   = get_client_ip();
            $failedRow   = $loginIns->model->getFailedLoginCountModel($ipAddress);
            $failedCount = $failedRow['count'];
            
        } elseif ($trackType == 'cookie') {
            
            $failedCount = Cookie::get(Login::$failCookieKey);
            
        } else {
            $failedCount = 0;
        }
        
        return $failedCount;
    }
    
    public function sisiOauth() {
        Auth::isLogin();        
        $client_id = Config::getFromCache('CONFIG_OAUTH_CLIENT_ID');
        $client_secret = Config::getFromCache('CONFIG_OAUTH_CLIENT_SECRET'); 
        $tokenUrl = Config::getFromCache('CONFIG_OAUTH_CLIENT_TOKEN');
        $apiMe = Config::getFromCache('CONFIG_OAUTH_CLIENT_ME');

        $curl = curl_init();
        $params = 'code='.$_GET['code'].'&grant_type=authorization_code&redirect_uri='.URL.'login/sisiOauth&state=OSCD6SX&scope=bio&response_type=code'; 

        curl_setopt_array($curl, array(
            CURLOPT_URL => $tokenUrl,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POST => true, 
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . base64_encode($client_id.':'.$client_secret),
                'Content-Type: application/x-www-form-urlencoded'
            )
        ));    

        $response = curl_exec($curl); 
        $err = curl_error($curl);

        curl_close($curl);	

        if ($err) {
            var_dump($err); die('Error');
        }
        $response = json_decode($response, true);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiMe,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $response['access_token']
            )
        ));    

        $response = curl_exec($curl); 
        $err = curl_error($curl);

        curl_close($curl);	

        if ($err) {
            var_dump($err); die('Error');
        }
        $response = json_decode($response, true);                
        
        $userName = '';
        $userHash = '';
        foreach ($response as $row) {
            if ($row['Type'] === 'username') {
                $userName = $row['Value'];
            }
            if ($row['Type'] === 'pub_hash') {
                $userHash = $row['Value'];
            }
        }
        
        $_POST['user_name'] = $userName;
        $_POST['externalHash'] = $userHash;
        $_POST['csrf_token'] = $this->model->getCsrfTokenModel();
        
        $this->run();
    }

    public function page() {
        Session::init();
        Session::set(SESSION_PREFIX . 'skipOauth', '1');
        
        Message::add('s', '', URL . 'login/index/redirect');
    }
    
    public function runEToken() {
        
        $monpassServerAddress = Config::getFromCache('monpassServerAddress');
        $monpassServerAddress = rtrim($monpassServerAddress, '/');
        
        $url = $monpassServerAddress.'/TokenLogin/LoginCheck';
        $data = array('seasonId' => Input::post('seasonId'));

        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
		
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        if ($result === false) { /* Handle error */ }

        $res = json_decode($result);

        $status = $res->status;

        if ($status == 'Success') {
            
            $_POST['monpassUserId'] = $res->message;
            $_POST['csrf_token'] = $this->model->getCsrfTokenModel();

            $response = $this->model->runModel();
            
            if (isset($response['userkeys']) && is_array($response['userkeys'])) {
                
                $this->view->systemUserId = $response['id'];
                $this->view->userkeys = $response['userkeys'];
                $count = count($this->view->userkeys);

                if ($count > 1) {
                    
                    includeLib('Compress/Compression');

                    $this->view->render('profile/userkey/header');
                    $this->view->render('profile/userkey/index');
                    $this->view->render('profile/userkey/footer');

                } elseif ($count == 1) {

                    $this->model->connectClientModel($response['userkeys'][0]['id'], $this->view->systemUserId);

                } else {
                    Message::add('d', 'Нэвтрэх систем тохируулагдаагүй байна. Та систем хариуцсан ажилтантай холбогдоно уу.', AUTH_URL.'login');
                }
            
            } else {
                Message::add('d', Lang::line('login_error_msg'), AUTH_URL . 'login');
            }			
        } else {
            Message::add('d', Lang::line('login_error_msg'), URL . 'login');
        }
    }
    
    public function sendVerificationCode() {
        
        $response = $this->model->sendVerificationCodeModel();
        
        if ($response['status'] == 'success') {
            
            Session::init();
            Session::set(SESSION_PREFIX . 'otp', $response['otp']);
            
            if ($response['sendType'] == 'email') {
                
                $this->view->message = 'Баталгаажуулах код таны и-мейл хаяг руу амжилттай илгээгдлээ.';
                
            } elseif ($response['sendType'] == 'phoneNumber') {
                
                $this->view->message = 'Баталгаажуулах код таны утас руу амжилттай илгээгдлээ.';
            }
            
            $response['step2'] = $this->view->renderPrint('login/verification/step2');

            unset($response['otp']);
            unset($response['sendType']);
        }
        
        jsonResponse($response);
    }
    
    public function checkVerificationCode() {
        $response = $this->model->checkVerificationCodeModel();
        jsonResponse($response);
    }
    
    public function validDeviceVerification($isSave) {
        
        if ($isSave != '' && ($isSave == '0' || $isSave == '1')) {
            
            Session::init();
            
            $result = $this->model->validDeviceVerificationModel($isSave);
            
            if ($result) {
                
                $response = array(
                    'id'       => Session::get(SESSION_PREFIX . 'systemUserId'), 
                    'userkeys' => Session::get(SESSION_PREFIX . 'userkeys')
                );
                
                Session::delete(SESSION_PREFIX . 'otp');
                Session::delete(SESSION_PREFIX . 'userkeys');
                
                $this->chooseUserKey($response, false);
            }
            
        } else {
            Message::add('d', 'Буруу хаяг байна', AUTH_URL . 'logout');
        }
    }
    
    public function verificationStep1() {
        
        Session::init();
        
        $isDeviceVerificationByEmail = Config::getFromCache('isDeviceVerificationByEmail');
        $isDeviceVerificationByPhone = Config::getFromCache('isDeviceVerificationByPhone');
        
        $this->view->email = null;
        $this->view->phoneNumber = null;

        if ($isDeviceVerificationByEmail) {
            $this->view->email = trim(Session::get(SESSION_PREFIX . 'email'));
        }

        if ($isDeviceVerificationByPhone) {
            $this->view->phoneNumber = trim(Session::get(SESSION_PREFIX . 'mobile'));
        }
                
        $this->view->render('login/verification/step1');
    }

    public function monpassOauth() {

        $_POST['token'] = Input::get('code');
        $_POST['csrf_token'] = $this->model->getCsrfTokenModel();
        
        $this->run();
    }
    
    public function getUserKeyChild() {

        $userKeyData = $this->model->getUserKeyParentChildModel();
                    
        if ($userKeyData) {

            if ($userKeyData['status'] == 'success') {
                
                includeLib('Compress/Compression');
                
                $this->view->systemUserId = Session::get(SESSION_PREFIX . 'systemUserId');
                $this->view->userkeys = $userKeyData['data'];
                $userKeysRender = $this->view->renderPrint('profile/userkey/parentChildRender');
                
                $response = array('status' => 'success', 'html' => $userKeysRender);

            } else {
                $response = $userKeyData;
            }

        } else {
            $response = array('status' => 'error', 'message' => 'No data!');
        }
        
        jsonResponse($response);
    }
    
    public function authorize() {
        
        $hash = $_GET['hash'];
        
        $jsonStr = str_replace(array('tttnmhttt', 'ttttntsuttt'), array('+', '='), $hash);
        $userData = @json_decode(Hash::decryption($jsonStr), true);
        
        if ($userData) {
            
            $userData = Arr::changeKeyLower($userData);
            
            if ((strtotime(Date::currentDate('Y-m-d H:i:s')) - strtotime($userData['expiredate'])) > 30) {
                Message::add('d', 'Буруу үйлдэл хийсэн байна. (sso expired)', AUTH_URL . 'logout');
            }

            $_POST['csrf_token'] = $this->model->getCsrfTokenModel();
            $_POST['user_name'] = $userData['username'];
            $_POST['pass_word'] = $userData['password'];
            $_POST['isHash'] = 1;

            if (issetParam($userData['iscustomer']) == '1') {
                $_POST['isCustomer'] = '1';
                $_POST['isHash'] = 0;
            }
            
            $_POST['monpassUserId'] = null;

            $response = $this->model->runModel();
        
            $this->chooseUserKey($response);
        
        } else {
            Message::add('d', 'Буруу үйлдэл хийсэн байна. (not found userdata)', AUTH_URL . 'logout');
        }
    }
    
    public function register() {
        if (Config::getFromCache('showLoginRegister') !== '1') {
            Message::add('s', '', URL . 'login');
        }
		
        $this->view->title = $this->lang->line('register');
        $this->view->csrf_token = $this->model->getCsrfTokenModel('ps');
        
        $configMainLogo   = Config::getFromCache('main_logo_path');
        $configLogo       = Config::getFromCache('logo_path');
        $configBackground = Config::getFromCache('login_background_image1');
        $loginLayout      = Config::getFromCache('loginLayout'); 
        
        if ($configMainLogo && file_exists($configMainLogo)) {
            $this->view->mainLogo = $configMainLogo;  
        } 
        
        if ($configLogo && file_exists($configLogo)) {
            $this->view->logo = $configLogo;
        } 
        
        $this->view->mainLoginTitle   = Config::getFromCacheDefault('login_main_title', null, 'Business in the Cloud');
        $this->view->loginTitle       = Config::getFromCacheDefault('login_title', null, 'Орчин үеийн стратеги төлөвлөлт, удирдлагын онлайн ERP цогц шийдэл');
        $this->view->passwordByPhone  = Config::getFromCache('RECOVER_PASSWORD_BY_PHONE');
        $this->view->loginFooterTitle = Config::getFromCacheDefault('loginFooterTitle', null, '<span>Powered by</span> <a href="http://veritech.mn/" target="_blank">Veritech ERP</a>');
        $this->view->infoMessage      = Config::getFromCache('passwordResetInfoMessage');
        
        if ($configBackground && file_exists($configBackground)) {
            $this->view->background = $configBackground;
        } else {
            $this->view->background = null;
        }
        
        $this->view->selectMultiDbControl = $this->model->selectMultiDbControl();
        
        if ($loginLayout && file_exists('views/login/layout/'.$loginLayout.'/register.php')) {
            
            $this->view->background = $this->model->getBackgroundImagesModel();

            $this->view->render('login/layout/'.$loginLayout.'/header');
            $this->view->render('login/layout/'.$loginLayout.'/register');
            $this->view->render('login/layout/'.$loginLayout.'/footer');
            
        } else {
            Message::add('s', '', URL . 'login');
        }
    }
	
    public function runRegister() {
        $this->model->registerModel();
    }
    
    public function runPhoneNumber() {
        try {

            if (Config::getFromCache('CONFIG_USE_PHONELOGIN') !== '1') {
                throw new Exception("Буруу үйлдэл хийсэн байна."); 
            }
            
            $postData = Input::postData();

            $curl = curl_init();
            $username = Config::getFromCacheDefault('MssSignatureUser', null, '5296722-ap');
            $password = Config::getFromCacheDefault('MssSignaturePass', null, 'LiuIudOz4lbLolI886qd');
            $userPass = base64_encode($username . ':' . $password);
            $url = Config::getFromCacheDefault('MssSignatureUrl', null, 'https://10.10.50.163:9061/rest/service');

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{ 
                "MSS_SignatureReq":{ 
                    "AdditionalServices":[ 
                        {
                                "Description": "http://uri.etsi.org/TS102204/v1.1.2#validate"
                            },
                            {
                                "Description": "http://www.methics.fi/KiuruMSSP/v5.0.0#signingCertificate"
                            },
                            {
                                "Description": "http://mss.ficom.fi/TS102204/v1.0.0#userLang",
                                "UserLang": {
                                    "Value": "MN"
                                }
                            }
                    ],
                    "DataToBeDisplayed":{ 
                        "Data":"Та гарын үсгээ оруулна уу",
                        "Encoding":"UTF-8",
                        "MimeType":"text/plain"
                    },
                    "DataToBeSigned":{ 
                        "Data":"data",
                        "Encoding":"UTF-8",
                        "MimeType":"text/plain"
                    },
                    "MessagingMode":"synch",
                    "MobileUser":{ 
                        "MSISDN":"976'. $postData['user_phonenumber'] .'"
                    },
                    "SignatureProfile":"http://alauda.mobi/nonRepudiation",
                    "MSS_Format": "http://www.methics.fi/KiuruMSSP/v3.2.0#PKCS1"
                }
                }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Basic ' . $userPass
                ),
            ));
            
            $response = curl_exec($curl);       
            $err = curl_error($curl);
            curl_close($curl);
            
            if (!is_dir(UPLOADPATH . 'temp')) {
                mkdir(UPLOADPATH . 'temp', 0777, true);
            }

            $registerNumber = '';
            if ($err) {
                $response = array('status' => 'error', 'message' => $err);
            } else {
                $response = json_decode($response, true);
                if (!issetParam($response['Fault'])) {
                    $response['cert_data'] = array();
                    if (issetParamArray($response['MSS_SignatureResp']['ServiceResponses'][0]['SigningCertificate']['Certificates'])) {
                        $tmp = array();
                        foreach ($response['MSS_SignatureResp']['ServiceResponses'][0]['SigningCertificate']['Certificates'] as $key => $row) {
                            $filetPath = UPLOADPATH . 'temp/cert.crt';

                            $cert_txt = '-----BEGIN CERTIFICATE-----' . "\n";
                            $cert_txt .= $row . "\n";
                            $cert_txt .= '-----END CERTIFICATE-----';

                            $certFile = fopen($filetPath, "w");
                            fwrite($certFile, $cert_txt);
                            fclose($certFile);

                            $ssl = openssl_x509_parse(file_get_contents($filetPath));
                            array_push($tmp, issetParamArray($ssl['subject']));
                            if (issetParam($ssl['subject']['UID']) !== '')
                                $registerNumber = issetParam($ssl['subject']['UID']);
                            
                            @unlink($filetPath);
                        }
                        
                        $response['cert_data'] = $tmp;
                    }
                }
            }
            
            if (!$registerNumber) {
                throw new Exception("Мэдээлэл олдсонгүй!"); 
            }

            includeLib('Utils/Functions');
            $postsData = Functions::runProcess('crGsignLoginGet_004', array('registerednum' => $registerNumber));
            if (!issetParam($postsData['result']['username'])) {
                throw new Exception("Хэрэглэгчийн мэдээлэл олдсонгүй!"); 
            }

            unset($_POST);
            $_POST['user_name'] = $postsData['result']['username'];
            $_POST['pass_word'] = $postsData['result']['passwordhash'];
            $_POST['isHash'] = '1';
            $_POST['csrf_token'] = $this->model->getCsrfTokenModel();

            $response = $this->model->runModel();
            
            if (Config::getFromCache('custom_login') == 'statebank') {
                $this->chooseUserRoleCustomLogin($response);    
                exit;
            }    
            $this->chooseUserKey($response);

        } catch (Exception $e) {
            Message::add('d', $e->getMessage(), AUTH_URL.'login');
        }
    }
    
    public function runFinger() {

        $mddoc = &getInstance();
        $mddoc->load->model('mddoc', 'middleware/models/');
        $imagePath = $mddoc->model->bpTemplateUploadGetPath();
        
        if (!Config::getFromCache('CONFIG_USE_FINGER'))  {
            echo json_encode(array('status' => 'error', 'message' => 'Буруу үйлдэл хийсэн байна.'));
        }
        
        $postData = Input::postData();
        $filePath = base64_to_jpeg($postData['operatorFinger'], $imagePath . getUID() .'.jpg' );

        includeLib('Utils/Functions');
        $postsData = Functions::runProcess('NTR_REGISTER_GET_LIST_004', array('userName' => $postData['registerNumber']));
                
        if (issetParamArray($postsData['result'])) {
            $param = array(
                "auth" => array(
                    "citizen" => array(
                        "regnum" => $postsData['result']['stateregnumber'],        // Иргэний регистрийн дугаар
                        "fingerprint" => file_get_contents($filePath)          // file_get_contents($citizenData['filePath']) // Иргэний хурууны хээний зураг. 310x310 харьцаатай PNG өртгөлтэй
                    ),
                    "operator" => array(
                        "regnum" => $postsData['result']['stateregnumber'],     // Үйлчилгээг үзүүлэгч ажилтны регистрийн дугаар
                        "fingerprint" => file_get_contents($filePath)  // Үйлчилгээг үзүүлэгч ажилтны хурууны хээний зураг. 310x310 харьцаатай PNG өртгөлтэй
                    ),
                ),
                "regnum" => $postsData['result']['stateregnumber'],
                "civilId" => "",                 // Иргэний регистрийн дугаар
                'citizenRegnum' => $postsData['result']['stateregnumber'],
                'citizenFingerPrint' => file_get_contents($filePath)
            );

            $mdinteg = &getInstance();
            $mdinteg->load->model('mdintegration', 'middleware/models/');

            $processRow['WS_URL'] = 'https://xyp.gov.mn/citizen-'. Config::getFromCacheDefault('XYP_WSDL_VERSION', null, '') .'/ws?WSDL';
            $processRow['CLASS_NAME'] = (Input::post('isaddress')) ? 'WS100103_getCitizenAddressInfo' : 'WS100101_getCitizenIDCardInfo';
            
            $result = $mdinteg->model->callXypService($processRow, $param, false);
            
            if (issetParamArray($result['data']['result'])) {
                $this->load->model('login', 'models/');
                echo json_encode(array('status' => 'success', 'username' => $postsData['result']['username'], 'pass_word' => $postsData['result']['passwordhash']));
                die();
            }

            echo json_encode(array('status' => 'error', 'message' => Lang::line('login_error_msg')));
        } else {
            echo json_encode(array('status' => 'error', 'message' => Lang::line('login_error_msg')));
        }
        
    }

    public function runDan() {

        if (!Config::getFromCache('CONFIG_USE_DAN'))  {
            echo json_encode(array('status' => 'error', 'message' => 'Буруу үйлдэл хийсэн байна.'));
        }

        $postData = Input::postData();
        if (issetParam($postData['code'])) {
            $code = $postData['code'];
            $url = 'https://sso.gov.mn/oauth2/token';
            $data = array(
                'grant_type' => 'authorization_code', 
                'code' => $code,
                'client_id' => Config::getFromCache('DAN_CLIENT_ID'),
                'client_secret' => Config::getFromCache('DAN_CONSUMER_SECRET'),
                'redirect_uri' => URL . Config::getFromCache('DAN_REDIRECT_URI') 
            );
    
            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded",
                    'method'  => 'POST',
                    'content' => http_build_query($data)
                )
            );
            
            $context  = stream_context_create($options);
            $result = file_get_contents($url, false, $context);

            if ($result === FALSE) { 
                echo json_encode(array('status' => 'error', 'message' => Lang::line('login_error_msg')));
            } else{
                $dataJson = json_decode($result);
                $access_token = $dataJson->access_token;
                if ($access_token) {
                    $url = 'https://sso.gov.mn/oauth2/api/v1/service';
                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    $headers = array(
                        "Accept: application/json",
                        "Authorization: Bearer $access_token",
                    );
                    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                    $resp = curl_exec($curl);
                    curl_close($curl);

                    $respArr = json_decode($resp, true);
                    
                    if (isset($respArr['1']['services']['WS100101_getCitizenIDCardInfo']['response']['regnum'])){
                        includeLib('Utils/Functions');
                        $postsData = Functions::runProcess('NTR_REGISTER_GET_LIST_004', array('stateregnumber' => $respArr['1']['services']['WS100101_getCitizenIDCardInfo']['response']['regnum']));
                        
                        if (issetParamArray($postsData['result'])  && Config::getFromCache('CONFIG_USE_DAN')) {
                            $this->load->model('login', 'models/');
                            $_POST['user_name'] = $postsData['result']['username'];
                            $_POST['isHash'] = 1;
                            $_POST['pass_word'] = $postsData['result']['passwordhash'];
                            $_POST['csrf_token'] = $this->model->getCsrfTokenModel();

                            self::run();
                        } else {
                            echo json_encode(array('status' => 'error', 'message' => Lang::line('login_error_msg')));
                        }
                    }
                    else{
                        echo json_encode(array('status' => 'error', 'message' => Lang::line('login_error_msg')));
                    }
                } else {
                    echo json_encode(array('status' => 'error', 'message' => Lang::line('login_error_msg')));
                }
            }
        } else {
            echo json_encode(array('status' => 'error', 'message' => Lang::line('login_error_msg')));
        }
    }
    
    public function runCustom() {
        
        $registerNum = Input::post('registerNumber');
        if ($registerNum) {
            convJson(array('status' => 'error', 'message' => Lang::line('check_registernum')));
        }

        includeLib('Utils/Functions');
        $postsData = Functions::runProcess('NTR_REGISTER_GET_LIST_004', array('stateregnumber' => $registerNum));
        if (issetParamArray($postsData['result'])) {
            $outParam = array(
                            'status' => 'success', 
                            'user_name' => $postsData['result']['username'], 
                            'pass_word' => $postsData['result']['passwordhash'],
                            'csrf_token' => $this->model->getCsrfTokenModel()
                        );
            convJson($outParam);
        } else {
            convJson(array('status' => 'error', 'message' => Lang::line('login_error_msg')));
        }
    }

    public function runKhalamj() {

        if (!Config::getFromCache('CONFIG_USE_KHALAMJ'))  {
            echo json_encode(array('status' => 'error', 'message' => 'Буруу үйлдэл хийсэн байна.'));
        }

        $postData = Input::postData();
        if (issetParam($postData['code'])) {

            $code = $postData['code'];
            $clientId = Config::getFromCache('KHALAMJ_CLIENT_ID');
            $redirectUri = URL . Config::getFromCache('KHALAMJ_REDIRECT_URI') ;
            $clientSecret = Config::getFromCache('KHALAMJ_CONSUMER_SECRET'); 
            
            $url =  Config::getFromCache('KHALAMJ_MAIN_URL') . '/token';
            $data = array(
                'grant_type' => 'authorization_code', 
                'code' => $code,
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri' => $redirectUri
            );

            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded",
                    'method'  => 'POST',
                    'content' => http_build_query($data)
                )
            );
            
            $context  = stream_context_create($options);
            $result = file_get_contents($url, false, $context);

            if ($result === FALSE) { 
                echo json_encode(array('status' => 'error', 'message' => Lang::line('login_error_msg')));
            } else {
                $dataJson = json_decode($result);
                $access_token = $dataJson->access_token;
                if ($access_token) {
                    try {
                        
                        $token = $data['access_token'];
                        $data = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+', explode('.', $token)[1]))), true);
                        
                        includeLib('Utils/Functions');
                        $postsData = Functions::runProcess('NTR_REGISTER_GET_LIST_004', array('stateregnumber' => issetParam($data['preferred_username'])));
                        
                        if (issetParamArray($postsData['result'])  && Config::getFromCache('CONFIG_USE_KHALAMJ')) {
                            $this->load->model('login', 'models/');
                            $_POST['user_name'] = $postsData['result']['username'];
                            $_POST['isHash'] = 1;
                            $_POST['pass_word'] = $postsData['result']['passwordhash'];
                            $_POST['csrf_token'] = $this->model->getCsrfTokenModel();

                            self::run();
                        } else {
                            $currentDate = Date::currentDate();
                            $randomPass = randomPassword();
                            $param = array (
                                        'firstName' => issetParam($postData['firstname']),
                                        'lastName' => issetParam($postData['lastname']),
                                        'stateRegNumber' => issetParam($postData['registerNumber']),
                                        'picture' => '',
                                        'userName' => issetParam($postData['registerNumber']),
                                        'email' => issetParam($postData['email']),
                                        'departmentId' => NULL,
                                        'passwordHash' => $randomPass,
                                        'passwordSalt' => $randomPass,
                                        'passwordExpiryDate' => Date::nextDate($currentDate, '730'),
                                        'allowConCurrentLogins' => '0',
                                        'id' => NULL,
                                        'CREATE_UM_SYSTEM_USER' => array (
                                            'id' => NULL,
                                            'userName' => issetParam($postData['registerNumber']),
                                            'passwordHash' => Hash::create('sha256', $randomPass),
                                            'passwordSalt' => $randomPass,
                                            'email' => issetParam($postData['email']),
                                            'allowConCurrentLogins' => '0',
                                            'inActive' => '0',
                                            'personId' => NULL,
                                            'userFullName' => NULL,
                                            'typeCode' => 'internal',
                                            'passwordExpiryDate' => Date::nextDate($currentDate, '730'),
                                            'CREATE_UM_USER' => array (
                                                'id' => NULL,
                                                'isActive' => '1',
                                                'userName' => issetParam($postData['registerNumber']),
                                                'systemUserId' => NULL,
                                                'departmentId' => NULL,
                                            ),
                                        ),
                                    );
                            
                            Functions::runProcess('SWS_CREATE_USER_DV_001', $param);

                            $this->load->model('login', 'models/');
                            $_POST['user_name'] = $postData['registerNumber'];
                            $_POST['isHash'] = 1;
                            $_POST['pass_word'] = Hash::create('sha256', $randomPass);
                            $_POST['csrf_token'] = $this->model->getCsrfTokenModel();

                            self::run();
                        }

                    } catch (\Exception $e) { // Also tried JwtException
                        echo json_encode(array('status' => 'error', 'message' => Lang::line('login_error_msg')));
                    }
                    
                } else {
                    echo json_encode(array('status' => 'error', 'message' => Lang::line('login_error_msg')));
                }
            }
        } else {
            echo json_encode(array('status' => 'error', 'message' => Lang::line('login_error_msg')));
        }
    }

    public function ekhalamj() {

        $getData = Input::getData();
        if (issetParam($getData['token']) !== '' && Config::getFromCache('CONFIG_USE_KHALAMJ')) {
            
            $data = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+', explode('.', $getData['token'])[1]))), true);
            
            $postData['username'] = issetParam($data['preferred_username']);
            $postData['registerNumber'] = issetParam($data['preferred_username']);
            $postData['name'] = issetParam($data['name']);
            $postData['lastname'] = issetParam($data['family_name']);
            $postData['firstname'] = issetParam($data['given_name']);
            $postData['name'] = issetParam($data['name']);
            $postData['email'] = issetParam($data['email']);
            
            includeLib('Utils/Functions');
            $result = Functions::runProcess('NTR_REGISTER_GET_LIST_004', array('stateregnumber' => $postData['username']));
            
            if (issetParamArray($result['result'])) {
                $this->load->model('login', 'models/');
                $_POST['user_name'] = $result['result']['username'];
                $_POST['isHash'] = 1;
                $_POST['pass_word'] = $result['result']['passwordhash'];
                $_POST['csrf_token'] = $this->model->getCsrfTokenModel();
    
                self::run();
            } else {
                $currentDate = Date::currentDate();
                $randomPass = randomPassword();
                $param = array (
                            'firstName' => issetParam($postData['firstname']),
                            'lastName' => issetParam($postData['lastname']),
                            'stateRegNumber' => issetParam($postData['registerNumber']),
                            'picture' => '',
                            'userName' => issetParam($postData['registerNumber']),
                            'email' => issetParam($postData['email']),
                            'departmentId' => NULL,
                            'passwordHash' => $randomPass,
                            'passwordSalt' => $randomPass,
                            'passwordExpiryDate' => Date::nextDate($currentDate, '730'),
                            'allowConCurrentLogins' => '0',
                            'id' => NULL,
                            'CREATE_UM_SYSTEM_USER' => array (
                                    'id' => NULL,
                                    'userName' => issetParam($postData['registerNumber']),
                                    'passwordHash' => Hash::create('sha256', $randomPass),
                                    'passwordSalt' => $randomPass,
                                    'email' => issetParam($postData['email']),
                                    'allowConCurrentLogins' => '0',
                                    'inActive' => '0',
                                    'personId' => NULL,
                                    'userFullName' => NULL,
                                    'typeCode' => 'internal',
                                    'passwordExpiryDate' => Date::nextDate($currentDate, '730'),
                                    'CREATE_UM_USER' => array (
                                        'id' => NULL,
                                        'isActive' => '1',
                                        'userName' => issetParam($postData['registerNumber']),
                                        'systemUserId' => NULL,
                                        'departmentId' => NULL,
                                    ),
                                ),
                        );
                
                Functions::runProcess('SWS_CREATE_USER_DV_001', $param);
    
                $this->load->model('login', 'models/');
                $_POST['user_name'] = $postData['username'];
                $_POST['isHash'] = 1;
                $_POST['pass_word'] = Hash::create('sha256', $randomPass);
                $_POST['csrf_token'] = $this->model->getCsrfTokenModel();
    
                self::run();
            }

        } else {
            Message::add('e', 'Алдаатай хүсэлт', URL . 'login');
        }

    }
    
    public function civilRun($token = '', $username = '', $menuId = '') {
        includeLib('Utils/Functions');
        $currentDate = Date::currentDate();
        $validDate = Config::getFromCache('validDate_CVL');
        
        if ($token === 'd2hwCE6pGbrm2nri' && $username && $currentDate < $validDate) {
            $result = Functions::runProcess('CVL_USERNAME_GET_LIST_004', array('username' => $username));
            if (issetParamArray($result['result'])) {
                $this->load->model('login', 'models/');
                $_POST['user_name'] = $result['result']['username'];
                $_POST['isHash'] = 1;
                $_POST['pass_word'] = $result['result']['passwordhash'];
                $_POST['csrf_token'] = $this->model->getCsrfTokenModel();
    
                Auth::isLogin();
                $response = $this->model->runModel();
                $this->chooseUserKey($response);
            }
        } else {
            Message::add('e', 'Алдаатай хүсэлт', URL . 'login');
        }
    }

    public function chooseUserRoleCustomLogin($response) {
        if ($response && is_array($response)) {
            $this->view->responseData = Hash::encryption(json_encode($response, JSON_UNESCAPED_UNICODE));
            $this->view->roleList = json_decode(Hash::decryption($response['nesresponse']), true);
            
            $this->view->render('profile/userkey/header');
            $this->view->render('profile/userkey/rolelist');
            $this->view->render('profile/userkey/footer');   
        }
    }

    public function customLoginRole($roleId, $response) {
        if ($roleId && $response) {
            $response = Str::urlCharReplace($response, true);
            $responseData = json_decode(Hash::decryption($response), true);
            $responseData['roleId'] = $roleId;
            
            $response = $this->model->runModel($responseData);
            
            $this->chooseUserKey($response);
        }
    }

    public function sso() {
        Auth::isLogin();
        /* $_POST['hash'] = 'dXNlcm5hbWU9YmlsZ3V1biZsYXN0TmFtZT1iaWxndXVuJmZpcnN0TmFtZT1iaWxndXVuJnBhc3N3b3JkPTEyMyZpcEFkcmVzcz0xOTIuMTAwLjEwMC4xJnJlZ2lzdGVyTnVtYmVyPdCw0LAxMjM0NTY3OCZyb2xlSWQ9MSZpc0N1c3RvbWVyPTE='; */
        $_POST['hash'] = 'dXNlcm5hbWU9YmlsZ3V1biZsYXN0TmFtZT1iaWxndXVuJmZpcnN0TmFtZT1iaWxndXVuJnBhc3N3b3JkPTEyMyZpcEFkcmVzcz0xOTIuMTAwLjEwMC4xJnJlZ2lzdGVyTnVtYmVyPdCw0LAxMjM0NTY3OCZyb2xlSWQ9MQ==';

        $response = $this->model->ssoRunModel();
        $this->chooseUserKey($response);  
    }
    
    public function supplier_register() {
        
        Auth::isLogin();
        
        $this->view->indicatorId = Config::getFromCache('PF_SUPPLIER_REGISTER_INDICATOR_ID');
        
        if (!$this->view->indicatorId) {
            Message::add('s', '', URL . 'login');
        }
        
        $this->view->title = $this->lang->line('Нийлүүлэгчээр бүртгүүлэх');
        
        $this->view->fullUrlCss = [
            'assets/core/js/plugins/select2/select2.css', 
            'assets/core/js/plugins/addon/uniform/css/uniform.default.css',
            'assets/custom/css/login/supplier.css'
        ];
        
        $this->view->fullUrlJs = [
            'lang/mn/main_lang.js', 
            'assets/core/js/plugins/addon/phpjs/phpjs.min.js', 
            'assets/custom/js/plugins.min.js', 
            'middleware/assets/js/mdmetadata.js', 
            'middleware/assets/js/mdbp.js',
            'middleware/assets/js/mdexpression.js'
        ];
        
        loadPhpQuery();
        
        $configMainLogo   = Config::getFromCache('main_logo_path');
        $configLogo       = Config::getFromCache('logo_path');
        $configBackground = Config::getFromCache('login_background_image1');

        if ($configMainLogo && file_exists($configMainLogo)) {
            $this->view->mainLogo = $configMainLogo;  
        } 
        
        if ($configLogo && file_exists($configLogo)) {
            $this->view->logo = $configLogo;
        } 
        
        $this->view->mainLoginTitle   = Config::getFromCacheDefault('login_main_title', null, 'Business in the Cloud');
        $this->view->loginTitle       = Config::getFromCacheDefault('login_title', null, 'Орчин үеийн стратеги төлөвлөлт, удирдлагын онлайн ERP цогц шийдэл');
        $this->view->passwordByPhone  = Config::getFromCache('RECOVER_PASSWORD_BY_PHONE');
        $this->view->loginFooterTitle = Config::getFromCacheDefault('loginFooterTitle', null, '<span>Powered by</span> <a href="http://veritech.mn/" target="_blank">Veritech ERP</a>');
        $this->view->infoMessage      = Config::getFromCache('passwordResetInfoMessage');
        
        if ($configBackground && file_exists($configBackground)) {
            $this->view->background = $configBackground;
        } else {
            $this->view->background = null;
        }
        
        $logged = Session::isCheck(SESSION_PREFIX.'LoggedIn');
                    
        if ($logged == false) {
            Session::set(SESSION_PREFIX . 'LoggedIn', true);
        }

        $_POST['nult'] = true;
        
        $this->view->actionType = 'create';
                    
        $_POST['isResponseArray'] = 1;
        $_POST['param']['indicatorId'] = $this->view->indicatorId;
        $_POST['param']['actionType'] = $this->view->actionType;

        $indicatorContent = (new Mdform())->kpiIndicatorTemplateRender();
        $contentHtml = $indicatorContent['html'];
        $bpContentHtml = phpQuery::newDocumentHTML($contentHtml);
        
        $captchaInput = '<div class="form-group form-group-feedback form-group-feedback-left mt-3">
            <div class="input-group">
                <span class="input-group-btn">
                    <img src="api/captcha?fDownload=1" id="captcha"><br />
                    <a href="javascript:;" onclick="new_captcha();" class="captcha-refresh">'.$this->lang->line('captcha_new_code').'</a>
                </span>
                '.Form::text(['name' => 'security_code', 'id' => 'security_code', 'class' => 'form-control pl-2', 'placeholder' => $this->lang->line('captcha_typing_code'), 'required' => 'required', 'autocomplete' => 'off', 'tabindex' => 2]).'
            </div>
        </div>';
                    
        $bpContentHtml->find('.kpi-ind-tmplt-section > .row:last')->after($captchaInput);
        
        $this->view->contentHtml = $bpContentHtml->html();
        
        $this->view->render('login/header');
        $this->view->render('login/supplierRegister/index');
        $this->view->render('login/supplierRegister/footer'); 
    }
    
    public function supplierRegisterSave() {
        $response = $this->model->supplierRegisterSaveModel();
        convJson($response);
    }
    
    public function cloud_user_signup() {
        Auth::isLogin();
        
        $token = Input::get('token');
        if (!$token) {
            Message::add('d', 'Token parameter is required!', URL . 'login');
        }
        
        $customerId = Hash::decryption($token);
        if (!$customerId) {
            Message::add('d', 'Token is wrong!', URL . 'login');
        }
        
        $this->model->deleteSessionDatabaseConnection();
        
        $checkLicense = $this->model->checkCloudUserCustomerIdModel($customerId);
        if ($checkLicense['status'] != 'success') {
            Message::add('d', $checkLicense['message'], URL . 'login');
        }
        
        $this->view->title = $this->lang->line('Хэрэглэгч бүртгүүлэх');
        
        $this->view->fullUrlCss = [
            'assets/core/icon/fontawesome/all.min.css',
            'assets/core/icon/fontawesome/v4-shims.min.css',
            'assets/core/js/plugins/select2/select2.css', 
            'assets/core/js/plugins/addon/uniform/css/uniform.default.css',
            'assets/custom/css/login/process_render.css'
        ];
        
        $this->view->fullUrlJs = [
            'lang/mn/main_lang.js', 
            'assets/core/js/plugins/addon/phpjs/phpjs.min.js', 
            'assets/custom/js/plugins.min.js', 
            'middleware/assets/js/mdmetadata.js', 
            'middleware/assets/js/mdbp.js',
            'middleware/assets/js/mdexpression.js'
        ];
        
        $this->view->processId = Config::getFromCacheDefault('PF_CLOUD_USER_SIGNUP_PROCESS_ID', null, '1713769133625164');
        $this->view->email = $checkLicense['email'];
        
        loadPhpQuery();
        
        $configMainLogo   = Config::getFromCache('main_logo_path');
        $configLogo       = Config::getFromCache('logo_path');
        $configBackground = Config::getFromCache('login_background_image1');

        if ($configMainLogo && file_exists($configMainLogo)) {
            $this->view->mainLogo = $configMainLogo;  
        } 
        
        if ($configLogo && file_exists($configLogo)) {
            $this->view->logo = $configLogo;
        } 
        
        $this->view->mainLoginTitle   = Config::getFromCacheDefault('login_main_title', null, 'Business in the Cloud');
        $this->view->loginTitle       = Config::getFromCacheDefault('login_title', null, 'Орчин үеийн стратеги төлөвлөлт, удирдлагын онлайн ERP цогц шийдэл');
        $this->view->loginFooterTitle = Config::getFromCacheDefault('loginFooterTitle', null, '<span>Powered by</span> <a href="http://veritech.mn/" target="_blank">Veritech ERP</a>');
        $this->view->infoMessage      = Config::getFromCache('passwordResetInfoMessage');
        
        if ($configBackground && file_exists($configBackground)) {
            $this->view->background = $configBackground;
        } else {
            $this->view->background = null;
        }
        
        $logged = Session::isCheck(SESSION_PREFIX.'LoggedIn');
                    
        if ($logged == false) {
            Session::set(SESSION_PREFIX . 'LoggedIn', true);
            Session::set(SESSION_PREFIX . 'lastTime', time());
        }

        $_POST['isSystemMeta'] = 'false';
        $_POST['isDialog'] = false;
        $_POST['nult'] = true;
        
        $bpContent = (new Mdwebservice())->callMethodByMeta($this->view->processId, null, true);

        $bpContentHtml = phpQuery::newDocumentHTML($bpContent);
        $bpContentHtml->find('.bp-btn-back, .bp-btn-saveadd, .bp-btn-saveedit, .bp-btn-save, .bp-btn-quickmenu, .bp-btn-fieldclean, .bp-btn-testcase, .dv-right-tools-btn, .meta-toolbar, #boot-fileinput-error-wrap')->remove();
        
        $captchaInput = '<tr>
            <td colspan="2">
                <div class="input-group">
                    <span class="input-group-btn">
                        <img src="api/captcha?fDownload=1" id="captcha"><br />
                        <a href="javascript:;" onclick="new_captcha();" class="captcha-refresh">'.$this->lang->line('captcha_new_code').'</a>
                    </span>
                    '.Form::text(['name' => 'security_code', 'id' => 'security_code', 'class' => 'form-control pl-2', 'placeholder' => $this->lang->line('captcha_typing_code'), 'required' => 'required', 'autocomplete' => 'off', 'tabindex' => 2]).'
                </div>
            </td>
        </tr>';
                    
        $bpContentHtml->find('table.bp-header-param > tbody > tr:last')->after($captchaInput);
        $bpContentHtml->find('input[data-path="userName"]')->val($this->view->email)->attr('readonly', 'readonly');
        
        $this->view->contentHtml = $bpContentHtml->html();
        
        $this->view->render('login/header');
        $this->view->render('login/license/index');
        $this->view->render('login/license/footer'); 
    }
    
    public function cloudUserSignupSave() {
        $response = $this->model->cloudUserSignupSaveModel();
        convJson($response);
    }
    
}
