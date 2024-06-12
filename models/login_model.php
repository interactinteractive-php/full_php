<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Login_Model extends Model {
    
    private static $errorMsg = 'Системд нэвтрэх сервис хэвийн бус ажиллаж байгаа тул та систем хариуцсан ажилтантай холбогдоно уу';

    public function __construct() {
        parent::__construct();
    }

    public function runModel($customLoginData = null) {
        
        if (is_null($customLoginData)) {
            
            if (Input::isEmpty('csrf_token') == true) {    
                $this->redirectLogin('Csrf Token is empty!');
            } else {
                
                $sessionCsrfToken = Session::get(SESSION_PREFIX.'CsrfToken');
                $inputCsrfToken   = Input::post('csrf_token');
                
                if (!hash_equals((string)$sessionCsrfToken, (string)$inputCsrfToken)) {
                    $this->redirectLogin('Csrf Token is wrong!');
                }
            }
        }
        
        Session::delete(SESSION_PREFIX . 'CsrfToken');
        
        $this->setSessionDatabaseConnection();
        
        $isCheckLoginFailed = Login::isCheckLoginFailed();
        
        if ($isCheckLoginFailed) {
            
            Login::$trackType = Login::loginFailedTrackType();
            
            if (Login::$trackType == 'cookie') {
                
                $captchFailedCount = Config::getFromCache('captch_Login_Failed_Count');
                $failedCount       = (int) Login::getClientFailLoginCount('cookie');
                
                if ($failedCount > 0 && $failedCount >= $captchFailedCount) {
                    
                    $captcha = Input::post('security_code'); 
        
                    if (empty($captcha)) {
                        $this->redirectLogin(Lang::line('msg_fill_required_fields'));
                    }

                    if (Session::get(SESSION_PREFIX.'security_code') != md5(sha1($captcha))) {
                        
                        Cookie::set(Login::$failCookieKey, $failedCount + 1, time() + (86400 * Login::$cookieDays), true);
                        
                        $this->redirectLogin(Lang::line('Зурган дээрх код буруу байна'));
                    }
                }
            }
        }
        
        $loginInfo = Input::post('loginInfo');
        
        if ($loginInfo) {

            $loginInfo = Hash::decryption($loginInfo);

            if (!$loginInfo) {
                $this->redirectLogin('Input is wrong!');
            }

            $loginInfoArr = explode('^~^', $loginInfo);

            if (count($loginInfoArr) != 3) {
                $this->redirectLogin('Input is wrong!');
            }

            $user_name = Input::param($loginInfoArr[0]);
            $pass_word = Input::param($loginInfoArr[1]);
            
        } else {
            $user_name = Input::post('user_name');
            $pass_word = Input::post('pass_word');
        }

        $param = [
            'username'     => $user_name, 
            'password'     => $pass_word, 
            'externalhash' => Input::post('externalHash'), 
            'guid'         => Input::post('monpassUserId'), 
            'token'        => Input::post('token'), 
            'isHash'       => Input::post('isHash', '0')
        ];

        if (Config::getFromCache('CONFIG_USE_LDAP') && Config::getFromCache('ldap_login') == '3') {
            
            if (in_array(strtolower($param['username']), ['admin', 'cadmin', 'uadmin'])) {
                $param['isLdap'] = 0;
            } else {
                $param['isLdap'] = 1;
            }
            
        } else {
            $param['isLdap'] = Input::postCheck('isLdap') ? 1 : 0;
        }
        
        if (Input::numeric('isCustomer') == 1) {
            WebService::$isCustomer = true;
        }
        
        if (Config::getFromCache('custom_login') == 'statebank') {
            if ($customLoginData) {
                $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'dcTBank_setRole_EXT', $customLoginData);
                $result = issetParam($result['result']);
            } else {
                $param['teller'] = $param['username'];
                $param['credVal'] = $param['password'];
                $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'nes_login', $param);

                if ($result['status'] == 'error') {
                    $this->redirectLogin(issetParam($result['text']));
                }
                $result = issetParam($result['result']);

                return $result;
            }
        } else {
            $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'login', $param);
        }

        if (isset($result['status'])) {
            
            if ($connectionId = issetParam($result['result']['connectionid'])) {
                
                Session::set(SESSION_PREFIX.'isUseMultiDatabase', true);
                $this->setSessionDatabaseConnection(null, $connectionId);
                
            } elseif ($connectionId = issetParam($result['result']['unitname'])) {
                
                Session::set(SESSION_PREFIX.'isUseMultiDatabase', true);
                $this->setSessionDatabaseConnection(null, $connectionId);
            }
            
            $this->setLocalStorageUsername($result['status'], $param['username']);

            if ($isCheckLoginFailed) {
                
                includeLib('Detect/Browser');
                $browser = new Browser();

                Login::$ipAddress    = get_client_ip();

                Login::$userAgent    = $browser->getUserAgent();
                Login::$browserName  = $browser->getBrowser();
                Login::$platformName = $browser->getPlatform();

                if ($result['status'] == 'success' && isset($result['result'])) {
                    
                    $result['result']['isldap'] = issetParam($param['isLdap']);
                    
                    if (WebService::$isCustomer) {
                        
                        $tempArr = $result['result'];
                        $result = self::customerLoginToUserData($tempArr);
                    } 
                    
                    SessionSetHandler::initLogged($result['result']);
                    
                    self::saveSuccessLoginModel();
            
                    if (Login::$trackType == 'cookie') {
                        Cookie::set(Login::$failCookieKey, 0, time() + (86400 * Login::$cookieDays), true);
                    } 
                    
                    return $result['result'];

                } else {

                    if (isset($result['text'])) {

                        $message = $result['text'];
                        self::saveFailedLoginModel($message, $param);
                        $message = Lang::line($result['text']);

                    } else {
                        $message = self::$errorMsg;
                    }
                    
                    if (Session::get(SESSION_PREFIX . 'skipOauth')) {
                        Message::add('d', $message, AUTH_URL.'login/index/redirect');
                    }
                    
                    $this->redirectLogin($message);
                }
                
            } else {

                if ($result['status'] == 'success' && isset($result['result'])) {
                    
                    $result['result']['isldap'] = issetParam($param['isLdap']);

                    SessionSetHandler::initLogged($result['result']);
                    
                    return $result['result'];

                } else {

                    if (isset($result['text'])) {
                        $message = $result['text'];
                    } else {
                        $message = self::$errorMsg;
                    }

                    if (Session::get(SESSION_PREFIX . 'skipOauth')) {
                        Message::add('d', $message, AUTH_URL.'login/index/redirect');
                    } else {
                        $this->redirectLogin($message);
                    }
                }
            }
        }
        
        $this->redirectLogin(self::$errorMsg);
    }

    public function connectClientModel($userKeyId, $systemUserId) {      
        
        Session::init();

        $params = array('userKeyId' => $userKeyId);
        
        $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'connectClient', $params);
        
        if (isset($result['status']) && $result['status'] == 'success') {
            
            includeLib('Compress/Compression');
            
            $appSessionId = Ue::appUserSessionId();
            
            if (!$appSessionId) { 
                file_put_contents('log/appSession.txt', var_export($_SESSION, true));
            }
            
            $data = Compression::gzdeflate($appSessionId. '$' . $userKeyId . '$' . $systemUserId . '$' . Date::currentDate('Y-m-d H:i:s'));
            $data = Str::urlCharReplace($data);
            
            if (isset($result['result']['sessionvalues']) && $result['result']['sessionvalues']) {
                Session::set(SESSION_PREFIX.'sessionValues', $result['result']['sessionvalues']);
            }
            
            if (isset($result['result']['url']) && $result['result']['url']) {
                $url = $result['result']['url'];
            } else {
                $url = URL;
            }

            Message::add('s', '', $url . 'login/sign/' . $data);
            
        } else {
            Message::add('d', issetDefaultVal($result['text'], Lang::line('login_error_msg')), AUTH_URL . 'logout');
        }

        Message::add('d', 'Системийн тохиргоогоо шалгана уу. Хариуцсан ажилтантай холбогдоорой.', AUTH_URL . 'logout');
    }
    
    public function getCsrfTokenModel($prefix = null) {
        
        if (function_exists('mcrypt_create_iv')) {
            $token = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
        } else {
            $token = bin2hex(openssl_random_pseudo_bytes(32));
        }
        
        Session::set(SESSION_PREFIX.'CsrfToken' . $prefix, $token);
        
        return $token;
    }

    public function getUserKeyRow($userKeyId, $systemUserId) {
        
        try {
            
            $systemUserIdPh = $this->db->Param(0);
            $userKeyIdPh    = $this->db->Param(1);

            $bindVars = array($this->db->addQ($systemUserId), $this->db->addQ($userKeyId));

            $row = $this->db->GetRow(
                "SELECT 
                    UM.USER_ID, 
                    US.USER_ID AS USER_KEY_ID,
                    UM.USERNAME, 
                    " . $this->db->IfNull('UM.FIRST_NAME', 'UM.USERNAME') . " AS PERSON_NAME, 
                    UM.LAST_NAME, 
                    UM.PASSWORD_HASH, 
                    UM.EMP_PICTURE, 
                    UM.POSITION_NAME,  
                    UM.EMPLOYEE_ID, 
                    UM.EMPLOYEE_KEY_ID,
                    UM.LANGUAGE_ID,
                    RL.LANGUAGE_CODE,
                    LOWER(RL.SHORT_CODE) AS LANG_SHORT_CODE, 
                    DEP.DEPARTMENT_NAME AS COMPANY_NAME, 
                    (
                        SELECT 
                            MAX(MONPASS_USER_ID) 
                        FROM UM_USER_MONPASS_MAP 
                        WHERE USER_ID = US.USER_ID 
                            AND IS_ACTIVE = 1 
                    ) AS MONPASS_GUID, 
                    ( 
                        SELECT 
                            MAX(ROLE_ID) 
                        FROM UM_USER_ROLE 
                        WHERE USER_ID = US.USER_ID 
                            AND ROLE_ID = 1 
                            AND " . $this->db->IfNull('IS_ACTIVE', '1') . " = 1 
                    ) AS ROLE_ID, 
                    ( 
                        SELECT 
                            COUNT(1) 
                        FROM ORG_DEPARTMENT_LANGUAGE T0 
                            INNER JOIN REF_LANGUAGE T1 ON T0.LANGUAGE_ID = T1.LANGUAGE_ID 
                        WHERE T0.DEPARTMENT_ID = US.DEPARTMENT_ID 
                    ) AS LANG_COUNT, 
                    ( 
                        SELECT 
                            COUNT(1) 
                        FROM UM_USER_ROLE T0 
                            INNER JOIN UM_ROLE T1 ON T1.ROLE_ID = T0.ROLE_ID 
                        WHERE T0.USER_ID = US.USER_ID 
                            AND LOWER(T1.ROLE_CODE) = 'translate' 
                            AND " . $this->db->IfNull('T0.IS_ACTIVE', '1') . " = 1 
                    ) AS IS_TRANSLATE_USER, 
                    US.IS_USE_FOLDER_PERMISSION, 
                    US.DEFAULT_MENU_ID, 
                    USU.IS_TOUCH_MODE, 
                    USU.IS_USE_CHAT, 
                    USU.PERSON_ID, 
                    UC.MODULE_MENU_ID, 
                    US.DEPARTMENT_ID 
                FROM VW_USER UM 
                    INNER JOIN UM_USER US ON US.SYSTEM_USER_ID = UM.USER_ID 
                    INNER JOIN UM_SYSTEM_USER USU ON USU.USER_ID = UM.USER_ID 
                    LEFT JOIN REF_LANGUAGE RL ON RL.LANGUAGE_ID = UM.LANGUAGE_ID 
                    LEFT JOIN ORG_DEPARTMENT DEP ON DEP.DEPARTMENT_ID = US.DEPARTMENT_ID 
                    LEFT JOIN UM_USER_ROLE UR ON UR.USER_ID = US.USER_ID  
                    LEFT JOIN UM_MENU_CONFIG UC ON UC.IS_DEFAULT_MENU = 1 
                        AND (UC.USER_ID = US.USER_ID OR UC.ROLE_ID = UR.ROLE_ID) 
                WHERE UM.USER_ID = $systemUserIdPh 
                    AND US.USER_ID = $userKeyIdPh 
                ORDER BY UC.ID DESC, UC.USER_ID ASC", 
                $bindVars
            );

            if (Session::isCheck(SESSION_PREFIX . 'customerid') && $row) {

                $row['USERNAME'] = Session::get(SESSION_PREFIX . 'customername');
                $row['EMP_PICTURE'] = Session::get(SESSION_PREFIX . 'picture');
                $row['PERSON_NAME'] = Session::get(SESSION_PREFIX . 'customername');
                $row['LAST_NAME'] = null;
            }

            return $row;
        
        } catch (Exception $ex) {
            
            Message::add('d', $ex->msg, AUTH_URL . 'logout');
        }
    }
    
    public function getDefaultLanguageRow($deparmentId) {
        
        $row = $this->db->GetRow("
            SELECT 
                T1.LANGUAGE_ID,
                T1.LANGUAGE_CODE,
                LOWER(T1.SHORT_CODE) AS LANG_SHORT_CODE 
            FROM ORG_DEPARTMENT_LANGUAGE T0 
                INNER JOIN REF_LANGUAGE T1 ON T0.LANGUAGE_ID = T1.LANGUAGE_ID 
            WHERE T0.DEPARTMENT_ID = ".$this->db->Param(0), 
            array($deparmentId)
        );
        
        return $row;
    }
    
    public function sendPasswordModel() {
        
        $passwordResetUrl = AUTH_URL.'login/password_reset';
        
        if (Input::isEmpty('csrf_token') == true) {
            
            $this->redirectLogin('Csrf Token is empty!', $passwordResetUrl);
            
        } else {
            
            $sessionCsrfToken = Session::get(SESSION_PREFIX.'CsrfTokenps');
            $inputCsrfToken   = Input::post('csrf_token');
            
            if (!hash_equals((string)$sessionCsrfToken, (string)$inputCsrfToken)) {
                $this->redirectLogin('Csrf Token is wrong!', $passwordResetUrl);
            }
        }
        
        Session::delete(SESSION_PREFIX.'CsrfTokenps');
        
        $this->setSessionDatabaseConnection($passwordResetUrl);
        
        $type = Input::post('p_type');
        
        if ($type == 'phoneNumber') {
            self::passwordResetByPhone($passwordResetUrl);
        } else {
            self::passwordResetByEmail($passwordResetUrl);
        }
    }
    
    public function passwordResetByEmail($passwordResetUrl) {
        
        $email   = Input::post('p_email');
        $captcha = Input::post('security_code'); 
        
        if (empty($email) || empty($captcha)) {
            $this->redirectLogin(Lang::line('msg_fill_required_fields'), $passwordResetUrl);
        }
        
        if (!isValidEmail($email)) {
            $this->redirectLogin(Lang::line('validate_msg_email'), $passwordResetUrl);
        }
        
        if (Session::get(SESSION_PREFIX.'security_code') != md5(sha1($captcha))) {
            $this->redirectLogin(Lang::line('Зурган дээрх код буруу байна'), $passwordResetUrl);
        }

        if (Input::numeric('isCustomer') == 1) {
            WebService::$isCustomer = true;
        }

        $userRow = self::getUserRowByEmail($email);
        
        if ($userRow) {
            
            $passwordMinLength = (int) Config::getFromCacheDefault('passwordMinLength', null, 8);
            $newPassword = Str::random_string('alnum', $passwordMinLength); 
            
            $systemUserId = $userRow['USER_ID'];
            $systemUserName = $userRow['USERNAME'];
            $systemFirstName = $userRow['FIRST_NAME'];
            
            $emailSubject = Lang::line('user_new_password');
            $emailName = $systemFirstName ? $systemFirstName : $systemUserName;
            $isLdap = (Config::getFromCache('CONFIG_USE_LDAP') && Config::getFromCache('ldap_login') == '3' && Config::getFromCache('isLdapModifyPassword'));
            $loginName = $userRow['USERNAME'];
            
            if ($isLdap) {
                $loginName = $userRow['LDAP_LOGIN_NAME'] ? $userRow['LDAP_LOGIN_NAME'] : $loginName;
                $newPassword = Str::random_string('alphanumeric', $passwordMinLength); 
            }
            
            $emailBodyContent = file_get_contents('views/email_templates/password_reset.html');
            $emailBodyContent = str_replace(array('{name}', '{loginname}', '{newpassword}', '{title}'), array($emailName, $loginName, $newPassword, Config::getFromCache('TITLE')), $emailBodyContent);
            
            includeLib('Mail/PHPMailer/v2/PHPMailerAutoload');
            
            $mail = new PHPMailer();
            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();
            $mail->SMTPDebug = 0;
            
            if (!defined('SMTP_USER')) {
                
                $mail->SMTPAuth = false;
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );

            } else {
                $mail->SMTPAuth = (defined('SMTP_AUTH') ? SMTP_AUTH : true);
            
                if ($mail->SMTPAuth) {
                    $mail->Username = SMTP_USER; 
                    $mail->Password = SMTP_PASS; 
                } else {
                    $mail->SMTPOptions = array(
                        'ssl' => array(
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        )
                    );
                }
            }

            if (defined('SMTP_SSL_VERIFY') && !SMTP_SSL_VERIFY) {
            
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
            }            
            
            $mail->SMTPSecure = (defined('SMTP_SECURE') ? SMTP_SECURE : false);
            $mail->Host = SMTP_HOST;
            if (defined('SMTP_HOSTNAME') && SMTP_HOSTNAME) {
                $mail->Hostname = SMTP_HOSTNAME;
            }
            $mail->Port = SMTP_PORT;
            $mail->setFrom(EMAIL_FROM, EMAIL_FROM_NAME);
            $mail->Subject = $emailSubject;
            $mail->isHTML(true);
            $mail->Body = html_entity_decode($emailBodyContent);
            $mail->AltBody = Config::getFromCache('TITLE') . ' - ' . $emailSubject;
            
            $mail->addAddress($email);

            if ($mail->send()) {
                
                if ($isLdap) {
                    
                    $param = array(
                        'unicodePwd' => $newPassword, 
                        'options' => array(
                            'isCheckOldUnicodePwd' => 0, 
                            'systemUserId' => $systemUserId
                        )
                    );

                    $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'MODIFY_PASSWORD_LDAP_USER', $param);
                    
                    if (isset($result['status']) && $result['status'] == 'success') {
                        $this->redirectLogin(Lang::line('onlineanket_resetPassS'), AUTH_URL.'login', 's');
                    } else {
                        $this->redirectLogin($this->ws->getResponseMessage($result), $passwordResetUrl);
                    }
                    
                } else {
                    
                    $updateData = array(
                        'PASSWORD_HASH' => Hash::create('sha256', $newPassword),
                    );

                    if (WebService::$isCustomer && issetParam($userRow['CUST_USER_ID'])) {
                        $updateData['PASSWORD'] = $newPassword;
                        $updateResult = $this->db->AutoExecute('CRM_USER', $updateData, 'UPDATE', 'CUST_USER_ID = '. $userRow['CUST_USER_ID']); 
                    } else {
                        $updateResult = $this->db->AutoExecute('UM_SYSTEM_USER', $updateData, 'UPDATE', 'USER_ID = '.$systemUserId); 
                    }

                    if ($updateResult) {

                        if (Config::getFromCache('passwordSuggest') == '1') {

                            $userId = $this->db->GetOne("SELECT USER_ID FROM UM_USER WHERE SYSTEM_USER_ID = ".$this->db->Param(0), array($systemUserId));
                            $this->db->AutoExecute('UM_META_BLOCK', array('ID' => getUID(), 'CREATED_DATE' => Date::currentDate(), 'IS_CHANGE_PASSWORD' => '1', 'USER_ID' => $userId));
                        }

                        $this->redirectLogin(Lang::lineDefault('onlineanket_resetPassS', 'И-мэйл хаягаар шинэ нууц үг амжилттай илгээгдлээ. Та и-мэйл хаягаа шалгана уу.'), AUTH_URL.'login', 's');
                    }
                }
                
            } else {
                $this->redirectLogin($mail->ErrorInfo, $passwordResetUrl);
            }
            
        } else {
            $this->redirectLogin(Lang::lineDefault('invalid_email', 'И-мейл хаяг буруу байна.'), $passwordResetUrl);
        }
    }
    
    public function passwordResetByPhone($passwordResetUrl) {
        
        $phone   = Input::post('p_email');
        $captcha = Input::post('security_code'); 
        
        if (empty($phone) || empty($captcha)) {
            $this->redirectLogin(Lang::line('msg_fill_required_fields'), $passwordResetUrl);
        }
        
        if (!((boolean) preg_match("/^(\+\d{3}(\-){0,1}){0,1}(\d{8})$/i", $phone))) {
            $this->redirectLogin(Lang::line('Утасны дугаар буруу байна'), $passwordResetUrl);
        }
        
        if (Session::get(SESSION_PREFIX.'security_code') != md5(sha1($captcha))) {
            $this->redirectLogin(Lang::line('Зурган дээрх код буруу байна'), $passwordResetUrl);
        }
        
        $userRow = self::getUserRowByPhoneNumber($phone);
        
        if ($userRow) {
            
            $newPassword = Str::random_string('alnum', 8); 
            
            $smsBodyContent = 'Sain baina uu? Systemd nevtreh shine nuuts ug: {newpassword}';
            $smsBodyContent = str_replace('{newpassword}', $newPassword, $smsBodyContent);
            
            $param = array(
                'phoneNumber' => $phone, 
                'msg'         => $smsBodyContent
            );

            $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'SEND_SMS', $param);

            if (isset($result['status']) && $result['status'] == 'success') {
                
                $systemUserId = $userRow['USER_ID'];
                
                $updateData = array(
                    'PASSWORD_HASH' => Hash::create('sha256', $newPassword)
                );
                
                $updateResult = $this->db->AutoExecute('UM_SYSTEM_USER', $updateData, 'UPDATE', 'USER_ID = '.$systemUserId); 
                
                if ($updateResult) {
                    
                    if (Config::getFromCache('passwordSuggest') == '1') {
                        
                        $userId = $this->db->GetOne("SELECT USER_ID FROM UM_USER WHERE SYSTEM_USER_ID = ".$this->db->Param(0), array($systemUserId));
                        $this->db->AutoExecute('UM_META_BLOCK', array('ID' => getUID(), 'CREATED_DATE' => Date::currentDate(), 'IS_CHANGE_PASSWORD' => '1', 'USER_ID' => $userId));
                    }
                    
                    $this->redirectLogin(Lang::lineDefault('PF_SENT_SMS_NEW_PASS_SUCCESS_MSG', 'Таны утасны дугаар руу шинэ нууц үг амжилттай илгээлээ. Та утасаа шалгана уу.'), AUTH_URL.'login', 's');
                }
                
            } else {
                $this->redirectLogin($this->ws->getResponseMessage($result), $passwordResetUrl);
            }
            
        } else {
            $this->redirectLogin(Lang::line('Утасны дугаар буруу байна'), $passwordResetUrl);
        }
    }
    
    public function getUserRowByEmail($email) {
        
        $getProcessCode = Config::getFromCache('getPasswordResetUserDataByEmail');
        $email = strtolower($email);
        
        if ($getProcessCode) {
            
            $row = array();
            $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, $getProcessCode, array('email' => $email, 'isCustomer' => WebService::$isCustomer ? '1' :'0'));
                    
            if (isset($result['result']['userid'])) {
                $row = array(
                    'USER_ID' => $result['result']['userid'], 
                    'CUST_USER_ID' => issetParam($result['result']['custuserid']), 
                    'USERNAME' => $result['result']['username'], 
                    'LDAP_LOGIN_NAME' => $result['result']['ldaploginname'], 
                    'LAST_NAME' => $result['result']['lastname'], 
                    'FIRST_NAME' => $result['result']['firstname']
                );
            }
            
        } else {
        
            $emailPh = $this->db->Param(0);
            $bindVars = array($this->db->addQ($email));

            $row = $this->db->GetRow("
                SELECT 
                    US.USER_ID, 
                    '' AS CUST_USER_ID,
                    US.USERNAME, 
                    US.LDAP_LOGIN_NAME, 
                    BP.LAST_NAME, 
                    BP.FIRST_NAME 
                FROM UM_SYSTEM_USER US 
                    LEFT JOIN BASE_PERSON BP ON BP.PERSON_ID = US.PERSON_ID 
                WHERE LOWER(US.EMAIL) = $emailPh", $bindVars);
        }
        
        return $row;
    }
    
    public function getUserRowByPhoneNumber($phoneNumber) {
        
        $phonePh = $this->db->Param(0);
        $bindVars = array($this->db->addQ($phoneNumber));
        
        $row = $this->db->GetRow("
            SELECT 
                US.USER_ID, 
                US.USERNAME, 
                BP.LAST_NAME, 
                BP.FIRST_NAME 
            FROM UM_SYSTEM_USER US 
                INNER JOIN BASE_PERSON BP ON BP.PERSON_ID = US.PERSON_ID 
                LEFT JOIN HRM_EMPLOYEE EMP ON EMP.PERSON_ID = BP.PERSON_ID 
            WHERE ".$this->db->IfNull('EMP.EMPLOYEE_MOBILE', 'US.PHONE_NUMBER')." = $phonePh", $bindVars);
        
        return $row;
    }
    
    public function ldapControl() {
        
        $ldapLogin = Config::getFromCache('ldap_login');
        
        if ($ldapLogin == '0') {
            $control = '<label><span class="isLdapCheckBox"><input type="checkbox" name="isLdap" value="1" class="notuniform mr-1"></span> <span>Active directory</span></label>';
        } elseif ($ldapLogin == '1') {
            $control = '<label><span class="isLdapCheckBox"><input type="checkbox" name="isLdap" value="1" checked="checked" class="notuniform mr-1"></span> <span>Active directory</span></label>';
        } else {
            $control = null;
        }
        
        return $control;
    }
    
    public function sessionExpiredLoginModel() {
        
        $userId    = Input::post('i');
        $userKeyId = Input::post('k');
        $password  = Input::post('pass');
        
        $userIdPh = $this->db->Param(0);
        $bindVars = array($this->db->addQ($userId));
        
        if ($dbName = Input::post('cd97d6s8dg7sed4')) {
            $_POST['dbName'] = $dbName;
            $this->isUseMultiDatabaseModel();
            $this->setSessionDatabaseConnection();
        }
        
        $username = $this->db->GetOne("SELECT USERNAME FROM UM_SYSTEM_USER WHERE USER_ID = $userIdPh", $bindVars);

        if ($username) {
            
            $param = array(
                'username' => $username,
                'password' => $password,
                'isHash'   => 0 
            );
            
            if (Config::getFromCache('CONFIG_USE_LDAP') && Config::getFromCache('ldap_login') == '3') {
                $param['isLdap'] = 1;
            } else {
                $param['isLdap'] = Input::postCheck('isLdap') ? 1 : 0;
            }

            $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'login', $param);
            
            if (isset($result['status'])) {
            
                if ($result['status'] == 'success' && isset($result['result'])) {
                    
                    $result['result']['isldap'] = issetParam($param['isLdap']);
                    SessionSetHandler::initLogged($result['result']);
        
                    $resultClient = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'connectClient', array('userKeyId' => $userKeyId));

                    if (isset($resultClient['status']) && $resultClient['status'] == 'success') {
                        
                        $userRow      = self::getUserKeyRow($userKeyId, $userId);
                        $userKeyCount = count($result['result']['userkeys']);
                        $personName   = ($userRow['LAST_NAME']) ? Str::upper(Str::utf8_substr($userRow['LAST_NAME'], 0, 1)) . '.' . $userRow['PERSON_NAME'] : $userRow['PERSON_NAME'];

                        Session::init();

                        Session::set(SESSION_PREFIX . 'LoggedIn', true);
                        Session::set(SESSION_PREFIX . 'appusersessionid', Ue::appUserSessionId());
                        Session::set(SESSION_PREFIX . 'userkeyid', $userKeyId);

                        Session::set(SESSION_PREFIX . 'ConnectedAndLoggedIn', true);
                        Session::set(SESSION_PREFIX . 'userid', $userRow['USER_ID']);
                        Session::set(SESSION_PREFIX . 'username', $userRow['USERNAME']);
                        Session::set(SESSION_PREFIX . 'password', $userRow['PASSWORD_HASH']);
                        Session::set(SESSION_PREFIX . 'firstname', $userRow['PERSON_NAME']);
                        Session::set(SESSION_PREFIX . 'personname', $personName);

                        Session::set(SESSION_PREFIX . 'userKeyCompanyName', $userRow['COMPANY_NAME']);
                        Session::set(SESSION_PREFIX . 'picture', $userRow['EMP_PICTURE']);
                        Session::set(SESSION_PREFIX . 'position', $userRow['POSITION_NAME']);
                        Session::set(SESSION_PREFIX . 'employeeid', $userRow['EMPLOYEE_ID']);
                        Session::set(SESSION_PREFIX . 'employeekeyid', $userRow['EMPLOYEE_KEY_ID']);
                        Session::set(SESSION_PREFIX . 'userKeyDepartmentId', $userRow['DEPARTMENT_ID']);
                        Session::set(SESSION_PREFIX . 'personid', $userRow['PERSON_ID']);
                        Session::set(SESSION_PREFIX . 'monpassGUID', $userRow['MONPASS_GUID']);
                        Session::set(SESSION_PREFIX . 'roleid', $userRow['ROLE_ID']);
                        Session::set(SESSION_PREFIX . 'isUseFolderPermission', $userRow['IS_USE_FOLDER_PERMISSION']);
                        Session::set(SESSION_PREFIX . 'defaultModuleId', $userRow['DEFAULT_MENU_ID']);
                        Session::set(SESSION_PREFIX . 'touchMode', $userRow['IS_TOUCH_MODE']);
                        Session::set(SESSION_PREFIX . 'isUseChat', $userRow['IS_USE_CHAT']);

                        Session::set(SESSION_PREFIX . 'SSID', session_id());
                        Session::set(SESSION_PREFIX . 'lastTime', time());
                        Session::set(SESSION_PREFIX . 'userKeyCount', $userKeyCount);
                        Session::set(SESSION_PREFIX . 'isTranslateUser', $userRow['IS_TRANSLATE_USER']);
                        
                        if (Lang::isUseMultiLang()) {
                            if ($userRow['LANG_COUNT'] == '1') {

                                $langRow = self::getDefaultLanguageRow($userRow['DEPARTMENT_ID']);

                                Session::set(SESSION_PREFIX . 'langid', $langRow['LANGUAGE_ID']);
                                Session::set(SESSION_PREFIX . 'langcode', $langRow['LANGUAGE_CODE']);
                                Session::set(SESSION_PREFIX . 'langshortcode', $langRow['LANG_SHORT_CODE']);

                            } elseif ($userRow['LANGUAGE_ID'] != '') {
                                Session::set(SESSION_PREFIX . 'langid', $userRow['LANGUAGE_ID']);
                                Session::set(SESSION_PREFIX . 'langcode', $userRow['LANGUAGE_CODE']);
                                Session::set(SESSION_PREFIX . 'langshortcode', $userRow['LANG_SHORT_CODE']);
                            } 
                        } else {
                            Session::set(SESSION_PREFIX . 'langid', Config::getFromCache('LANG_ID'));
                            Session::set(SESSION_PREFIX . 'langcode', Config::getFromCache('LANG_NAME'));
                            Session::set(SESSION_PREFIX . 'langshortcode', Config::getFromCache('LANG'));
                        }
                        
                        Session::set(SESSION_PREFIX . 'langCount', $userRow['LANG_COUNT']);

                        if (Config::getFromCache('IS_URL_AUTHENTICATE') == '1') { 
                            Session::set(SESSION_PREFIX . 'isUrlAuthenticate', true);
                        }
                        
                        if (isset($resultClient['result']['sessionvalues']) && $resultClient['result']['sessionvalues']) {
                            Session::set(SESSION_PREFIX.'sessionValues', $resultClient['result']['sessionvalues']);
                        }
                        
                        if (Login::isCheckLoginFailed()) {
                            
                            Login::$trackType = Login::loginFailedTrackType();
                            
                            self::saveSuccessLoginModel();
            
                            if (Login::$trackType == 'cookie') {
                                Cookie::set(Login::$failCookieKey, 0, time() + (86400 * Login::$cookieDays), true);
                            } 
                        }

                        Ue::startFiscalPeriod();
                        
                        $response = array(
                            'status' => 'success', 
                            'picture' => file_exists($userRow['EMP_PICTURE']) ? $userRow['EMP_PICTURE'] : ''
                        );
        
                    } else {
                        $response = array('status' => 'warning', 'message' => Lang::line('login_error_msg'));
                    }

                } else {
                    $response = array('status' => 'warning', 'message' => isset($result['text']) ? $result['text'] : self::$errorMsg);
                }
            }
        
        } else {
            $response = array('status' => 'warning', 'message' => 'Хэрэглэгчийн нэр олдсонгүй!');
        }
        
        return $response;
    }
    
    public function saveSuccessLoginModel() {
        
        if (!Login::$userAgent) {
            
            includeLib('Detect/Browser');
            $browser = new Browser();

            Login::$ipAddress    = get_client_ip();

            Login::$userAgent    = $browser->getUserAgent();
            Login::$browserName  = $browser->getBrowser();
            Login::$platformName = $browser->getPlatform();
        }
                
        $ipAddress = Login::$ipAddress;
        
        $data = array(
            'ID'             => getUID(), 
            'USERNAME'       => Session::get(SESSION_PREFIX . 'username'), 
            'SYSTEM_USER_ID' => Session::get(SESSION_PREFIX . 'systemUserId'), 
            'SESSION_ID'     => Session::get(SESSION_PREFIX . 'appusersessionid'), 
            'IP_ADDRESS'     => $ipAddress, 
            'USER_AGENT'     => Login::$userAgent, 
            'BROWSER_NAME'   => Login::$browserName, 
            'PLATFORM_NAME'  => Login::$platformName, 
            'IS_LOGGEDIN'    => 1, 
            'IS_SUCCESS'     => 1, 
            'CREATED_DATE'   => Date::currentDate()
        );

        $this->db->AutoExecute('UM_LOGIN_ATTEMPTS', $data);
        
        if (Login::$trackType == 'ipaddress') {
            $this->db->AutoExecute('UM_LOGIN_ATTEMPTS', array('IS_LOGGEDIN' => 1), 'UPDATE', "IP_ADDRESS = '$ipAddress'");
        }
        
        Session::set(SESSION_PREFIX . 'isLoginAttempts', true);
        
        return true;
    }
    
    public function saveFailedLoginModel($message, $param) {
        
        $lowerMessage = Str::lower($message);
                    
        if ($lowerMessage == 'user_name_or_password_wrong' || $lowerMessage == 'user_name_not_found' || strpos($lowerMessage, 'нууц үг буруу') !== false) {
            
            $ipAddress   = Login::$ipAddress;
            $currentDate = Date::currentDate();
            
            $data = array(
                'ID'            => getUID(), 
                'USERNAME'      => $param['username'], 
                'IP_ADDRESS'    => $ipAddress, 
                'USER_AGENT'    => Login::$userAgent, 
                'BROWSER_NAME'  => Login::$browserName, 
                'PLATFORM_NAME' => Login::$platformName, 
                'IS_LOGGEDIN'   => 0, 
                'IS_SUCCESS'    => 0, 
                'FAILED_TYPE'   => '', 
                'CREATED_DATE'  => $currentDate
            );
            
            $result = $this->db->AutoExecute('UM_LOGIN_ATTEMPTS', $data);
            
            if ($result) {
                
                if (Login::$trackType == 'ipaddress') {
                    
                    $failedCount = self::getFailedLoginBetweenCountModel($ipAddress, $currentDate, 30); 
                    $keyCols = array('IP_ADDRESS');

                    $failedFields = array(
                        'ID'            => getUID(), 
                        'IP_ADDRESS'    => $ipAddress, 
                        'FAILED_COUNT'  => $failedCount, 
                        'MODIFIED_DATE' => $currentDate
                    );
                    $this->db->Replace('UM_LOGIN_FAILED', $failedFields, $keyCols, true);
                
                } elseif (Login::$trackType == 'cookie') {
                    
                    $failedCount = (int) Cookie::get(Login::$failCookieKey);
                    
                    Cookie::set(Login::$failCookieKey, $failedCount + 1, time() + (86400 * Login::$cookieDays), true); // 86400 = 1 day
                }   
            }
        }
        
        return true;
    }
    
    public function getFailedLoginBetweenCountModel($ipAddress, $currentDate, $minutes = 15) {
        
        $startDate = Date::weekdayAfter('Y-m-d H:i:s', $currentDate, '-'.$minutes.' minutes');
        
        $count = $this->db->GetOne("
            SELECT 
                COUNT(*) AS LOG_COUNT 
            FROM UM_LOGIN_ATTEMPTS 
            WHERE IP_ADDRESS = ".$this->db->Param(0)." 
                AND IS_LOGGEDIN = 0 
                AND CREATED_DATE BETWEEN ".$this->db->SQLDate('Y-m-d H:i:s', "'$startDate'", 'TO_DATE')." 
                AND ".$this->db->SQLDate('Y-m-d H:i:s', "'$currentDate'", 'TO_DATE'), 
            array($ipAddress)
        );
        
        return (int) $count;
    }
    
    public function getFailedLoginCountModel($ipAddress) {
        
        $row = $this->db->GetRow("
            SELECT 
                FAILED_COUNT, 
                MODIFIED_DATE 
            FROM UM_LOGIN_FAILED 
            WHERE IP_ADDRESS = " . $this->db->Param(0), 
            array($ipAddress) 
        );
        
        if ($row) {
            $response = array('count' => $row['FAILED_COUNT'], 'date' => $row['MODIFIED_DATE']);
        } else {
            $response = array('count' => 0);
        }
        
        return $response;
    }
    
    public function isDeviceCertifiedModel() {
        
        if (!Login::$ipAddress) {
            
            includeLib('Detect/Browser');
            $browser = new Browser();

            Login::$ipAddress    = get_client_ip();
            Login::$platformName = $browser->getPlatform();
        }
        
        $systemUserId = Session::get(SESSION_PREFIX . 'systemUserId');
        
        return $this->isSavedLoginDevice($systemUserId, Login::$ipAddress, Login::$platformName);
    }
    
    public function isSavedLoginDevice($systemUserId, $ipAddress, $platformName) {
        
        $id = $this->db->GetOne("
            SELECT 
                ID 
            FROM UM_LOGIN_DEVICE 
            WHERE SYSTEM_USER_ID = ".$this->db->Param(0)." 
                AND IP_ADDRESS = ".$this->db->Param(1)." 
                AND PLATFORM_NAME = ".$this->db->Param(2), 
            array($systemUserId, $ipAddress, $platformName)
        );
        
        return $id ? true : false;
    }
    
    public function sendVerificationCodeModel() {
        
        includeLib('Otp/otphp/lib/otphp');
        Session::init();
        
        $sendType = Input::post('sendType');
        
        if ($sendType == 'email') {
            
            includeLib('Mail/Mail');
            
            $totp = new \OTPHP\TOTP('base32secret3232', array('interval' => 1));
            $otp = $totp->now();
            
            $email = trim(Session::get(SESSION_PREFIX . 'email'));
            
            $body = 'Сайн байна уу?<br /><br />';
            $body .= 'Таны баталгаажуулах код: <span style="font-family: monospace">'.$otp.'</span>';
            
            $response = Mail::sendPhpMailer(
                array(
                    'subject' => 'Баталгаажуулах код', 
                    'altBody' => 'Баталгаажуулах код', 
                    'toMail'  => $email, 
                    'body'    => $body 
                )
            );
            
            if ($response['status'] == 'success') {
                
                $response['otp'] = $otp;
                $response['sendType'] = $sendType;
                
            } else {
                $response = array('status' => 'error', 'message' => $response['message']);
            }
            
        } elseif ($sendType == 'phoneNumber') {
            
            $totp = new \OTPHP\TOTP('base32secret3232', array('interval' => 1));
            $otp = $totp->now();
            
            $mobileNumber = trim(Session::get(SESSION_PREFIX . 'mobile'));
            $smsBodyContent = 'Sain baina uu? Tanii batalgaajuulah code: '.$otp;
            
            $param = array(
                'phoneNumber' => $mobileNumber, 
                'msg'         => $smsBodyContent
            );

            $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'SEND_SMS', $param);

            if (isset($result['status']) && $result['status'] == 'success') {
                
                $response = array('status' => 'success', 'otp' => $otp, 'sendType' => $sendType);
                
            } else {
                $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
            }
            
        } else {
            $response = array('status' => 'error', 'message' => 'Invalid type!');
        }
        
        return $response;
    }
    
    public function checkVerificationCodeModel() {
        
        Session::init();
        
        $otp  = Session::get(SESSION_PREFIX . 'otp');
        $code = Input::post('code');
        
        if ($otp == $code) {
            $response = array('status' => 'success');
        } else {
            $response = array('status' => 'error', 'message' => 'Буруу код байна');
        }
        
        return $response;
    }
    
    public function validDeviceVerificationModel($isSave) {
        
        if ($isSave == '1') {
            
            includeLib('Detect/Browser');
            $client = new Browser();
            
            $systemUserId = Session::get(SESSION_PREFIX . 'systemUserId');
            $ipAddress    = get_client_ip();
            $platformName = $client->getPlatform();
            
            $isSaved = $this->isSavedLoginDevice($systemUserId, $ipAddress, $platformName);
            
            if (!$isSaved) {
                
                $data = array(
                    'ID'             => getUID(), 
                    'SYSTEM_USER_ID' => $systemUserId, 
                    'IP_ADDRESS'     => $ipAddress, 
                    'USER_AGENT'     => $client->getUserAgent(), 
                    'BROWSER_NAME'   => $client->getBrowser(), 
                    'PLATFORM_NAME'  => $platformName, 
                    'CREATED_DATE'   => Date::currentDate()
                );

                $this->db->AutoExecute('UM_LOGIN_DEVICE', $data);
            }  
        } 
        
        $this->deviceVerificationAfterSendEmail();
        
        return true;
    }
    
    public function deviceVerificationAfterSendEmail() {
        
        $emailTemplateId = Config::getFromCache('deviceVerificationEmailTemplateId');
        
        if ($emailTemplateId) {
            
            $row = $this->db->GetRow("
                SELECT 
                    SUBJECT, 
                    MESSAGE 
                FROM EML_TEMPLATE 
                WHERE ID = ".$this->db->Param(0), 
                [$emailTemplateId]
            );
            
            $email = Session::get(SESSION_PREFIX . 'email');
            
            if ($row && $email) {
                
                includeLib('Mail/Mail');
                includeLib('Detect/Browser');
                
                $subject = html_entity_decode($row['SUBJECT']);
                $body = html_entity_decode($row['MESSAGE']);
                
                $personName = Session::get(SESSION_PREFIX . 'personname');
                $userName = $personName ? $personName : Session::get(SESSION_PREFIX . 'username');
        
                $client = new Browser();
                
                $arr = [
                    '[username]'       => $userName, 
                    '[loginDate]'      => Date::currentDate('Y-m-d'), 
                    '[loginTime]'      => Date::currentDate('H:i:s'), 
                    '[ipAddress]'      => get_client_ip(), 
                    '[platformName]'   => $client->getPlatform(), 
                    '[browserName]'    => $client->getBrowser(), 
                    '[browserVersion]' => $client->getVersion(), 
                    '[userAgent]'      => $client->getUserAgent()
                ];
                
                foreach ($arr as $key => $val) {
                    $subject = str_ireplace($key, $val, $subject);
                    $body = str_ireplace($key, $val, $body);
                }
                
                Mail::sendPhpMailer([
                    'subject' => $subject, 
                    'altBody' => $subject, 
                    'body'    => $body, 
                    'toMail'  => $email 
                ]);
            }
        }
        
        return true;
    }
    
    public function isUseMultiDatabaseModel() {
        
        $isUseMultiDatabase = Config::getFromCache('isUseMultiDatabase');
        
        if ($isUseMultiDatabase && DBUtil::getCountConnection()) {
            
            Session::set(SESSION_PREFIX.'isUseMultiDatabase', true);
            return true;
            
        } else {
            
            Session::delete(SESSION_PREFIX.'isUseMultiDatabase');
            return false;
        }
    }

    private function setSessionDatabaseConnection($redirectUrl = null, $connectionId = null) {
        
        if ($connectionId) { 
            $_POST['dbName'] = Crypt::encrypt($connectionId, 'db00x');
        }

        if (Session::get(SESSION_PREFIX.'isUseMultiDatabase') && Input::isEmpty('dbName') == false) {
            
            $dbId           = Crypt::decrypt(Input::post('dbName'), 'db00x');
            $connectionInfo = DBUtil::getConnectionByDbId($dbId);
            
            if ($connectionInfo) {
                
                global $db;

                $dbDriver = DBUtil::toShortName($connectionInfo['DB_TYPE']);
                $dbHost   = $connectionInfo['HOST_NAME'] . ':' . $connectionInfo['PORT'];
                $dbUser   = strtolower($connectionInfo['USER_NAME']);
                $dbPass   = $connectionInfo['USER_PASSWORD'];
                $dbSID    = $connectionInfo['SID'];
                
                if ($dbSID) {
                    $connectSID = true;
                } else {
                    $dbSID = $connectionInfo['SERVICE_NAME'];
                    $connectSID = false;
                }
                
                $db = ADONewConnection($dbDriver);
                $db->debug = DB_DEBUG;
                $db->connectSID = $connectSID;
                $db->autoRollback = true;
                $db->datetime = true;

                try {
                    $db->Connect($dbHost, $dbUser, $dbPass, $dbSID);
                } catch (Exception $e) {
                    if (!is_ajax_request()) {
                        Message::add('d', $e->msg, $redirectUrl ? $redirectUrl : AUTH_URL . 'login');
                    } else {
                        jsonResponse(['status' => 'warning', 'message' => $e->msg]);
                    }
                }
                
                $db->SetCharSet(DB_CHATSET);
                
                $this->db = $db;
                
                $secondDb = $dbHost . '|$|' . $dbUser . '|$|' . $dbPass . '|$|' . $dbSID . '|$|' . (int)$connectSID;
                $secondDb = Crypt::encrypt($secondDb, 'db49x');
                
                Session::set(SESSION_PREFIX . 'sdbun', (DB_DRIVER == 'oci8' ? $dbUser : $connectionInfo['ID']));
                Session::set(SESSION_PREFIX . 'sdbid', $connectionInfo['ID']);
                Session::set(SESSION_PREFIX . 'sdbnm', $connectionInfo['DB_NAME']);
                Session::set(SESSION_PREFIX . 'sdb', $secondDb);
                
                Config::$configArr = [];
                Config::$allConfigCodeArr = [];
            }
        }
        
        return true;
    }
    
    private function redirectLogin($msg, $redirectUrl = null, $mType = null) {
        
        $this->deleteSessionDatabaseConnection();
        
        Message::add($mType ? $mType : 'd', $msg, $redirectUrl ? $redirectUrl : AUTH_URL.'login');
    }
    
    public function deleteSessionDatabaseConnection() {
        
        Session::delete(SESSION_PREFIX . 'sdbun');
        Session::delete(SESSION_PREFIX . 'sdbid');
        Session::delete(SESSION_PREFIX . 'sdbnm');
        Session::delete(SESSION_PREFIX . 'sdb');
        Session::delete('flash_messages');
        
        return true;
    }
    
    public function selectMultiDbControl() {
        
        if ($this->isUseMultiDatabaseModel()) {
            
            $combo = Form::select([
                'name'     => 'dbName', 
                'class'    => 'select-rounded', 
                'data'     => DBUtil::getConnections(), 
                'op_value' => 'dbId', 
                'op_text'  => 'dbName', 
                'text'     => 'Бааз сонгох'
            ]);
            
            $control = '<div class="form-group form-group-feedback form-group-feedback-left database-choose">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="icon-database text-muted"></i>
                            </span> 
                        </span> 
                        '.$combo.' 
                    </div>									
                </div>
            </div>';
            
            return $control;
        }
        
        return null;
    }

    public function selectLoginTypeControl() {
        
        if (Config::getFromCache('showLoginRegister') === '1') {
            
            $combo = Form::select([
                'name'     => 'isCustomer', 
                'class'    => 'select-rounded', 
                'data'     => [
                    ['id' => '0', 'name' => Lang::line('system_user')],
                    ['id' => '1', 'name' => Lang::line('customer')]
                ], 
                'op_text'  => 'name', 
                'op_value' => 'id', 
                'text'     => 'notext'
            ]);
            
            $control = '<div class="form-group form-group-feedback form-group-feedback-left database-choose">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="icon-user text-muted"></i>
                            </span> 
                        </span> 
                        '.$combo.' 
                    </div>									
                </div>
            </div>';
            
            return $control;
        }
    }
    
    public function getBackgroundImagesModel() {
        
        $background = [];
        
        for ($b = 1; $b <= 6; $b++) {
            
            $configBackground = Config::getFromCache('login_background_image' . $b);
            
            if ($configBackground && file_exists($configBackground)) {
                
                $configBackgroundTitle = Config::getFromCache('login_background_title' . $b);
                $configBackgroundDescr = Config::getFromCache('login_background_descr' . $b);
                
                $background[] = [
                    'image' => $configBackground, 
                    'title' => $configBackgroundTitle, 
                    'descr' => $configBackgroundDescr
                ];
            } 
        }
        
        return $background;
    }
    
    public function checkDefaultPasswordModel($userId) {
        
        if (Config::getFromCache('defaultPasswordCheckGet')) {
            
            includeLib('Utils/Functions');
            $result = Functions::runProcess('GOV_PASSWORD_GET_LIST_004', array('id' => $userId));
            
            if (issetParam($result['result']['regnumber'])) {
                return Hash::create('sha256', $result['result']['regnumber']);
            }
        }
        
        return null;
    }
    
    public function getUserKeyParentChildModel() {
        
        if ($dvId = Config::getFromCache('loginChooseUserKeyDvId')) {
            
            $param = [
                'systemMetaGroupId' => $dvId,
                'showQuery' => 0, 
                'ignorePermission' => 1, 
                'criteria' => [
                    'userId' => [
                        [
                            'operator' => '=',
                            'operand' => Session::get(SESSION_PREFIX . 'systemUserId')
                        ]
                    ]
                ]
            ];
            
            if ($parentId = Input::numeric('parentId')) {
                $param['criteria']['parentId'][] = [
                    'operator' => '=',
                    'operand' => $parentId
                ];
            }

            $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

            if ($result['status'] == 'success' && isset($result['result'][0])) {

                unset($result['result']['paging']);
                unset($result['result']['aggregatecolumns']);

                $response = ['status' => 'success', 'data' => $result['result']];
            } else {
                $response = ['status' => 'error', 'message' => $this->ws->getResponseMessage($result)];
            }
            
        } else {
            $response = null;
        }

        return $response;
    }
    
    public function getUserNamePassByKeyIdModel($userKeyId) {
        
        $row = $this->db->GetRow("
            SELECT 
                US.USERNAME, US.PASSWORD_HASH, UM.SYSTEM_USER_ID  
            FROM UM_USER UM 
                INNER JOIN UM_SYSTEM_USER US ON US.USER_ID = UM.SYSTEM_USER_ID 
            WHERE UM.USER_ID = ".$this->db->Param(0), 
            [$userKeyId]
        );
        
        return $row;
    }
    
    public function getUserRowByOtpHashModel($hash) {
        
        $row = $this->db->GetRow("
            SELECT 
                UM.USER_ID 
            FROM UM_SYSTEM_USER UM 
                INNER JOIN UM_USER_OTP US ON US.USER_ID = UM.USER_ID 
            WHERE US.PASSCODE = ".$this->db->Param(0), 
            [$hash]
        );
        
        return $row;
    }
    
    public function activeAccountByPassModel() {
        
        if (Input::isEmpty('csrf_token') == true) {
            
            $this->redirectLogin('Csrf Token is empty!', 'back');
            
        } else {
            
            $sessionCsrfToken = Session::get(SESSION_PREFIX.'CsrfTokenaap');
            $inputCsrfToken   = Input::post('csrf_token');
            
            if (!hash_equals((string)$sessionCsrfToken, (string)$inputCsrfToken)) {
                $this->redirectLogin('Csrf Token is wrong!', 'back');
            }
        }
        
        Session::delete(SESSION_PREFIX.'CsrfTokenaap');
        
        $new_password         = Input::post('p_new_password');
        $new_password_confirm = Input::post('p_new_password_confirm');
        $captcha              = Input::post('security_code'); 
        $aapud                = Input::post('aapud');
        $aaph                 = Input::post('aaph');
        
        if (empty($new_password) || empty($new_password_confirm) || empty($captcha) || empty($aapud) || empty($aaph)) {
            $this->redirectLogin(Lang::line('msg_fill_required_fields'), 'back');
        }
        
        if (Session::get(SESSION_PREFIX.'security_code') != md5(sha1($captcha))) {
            $this->redirectLogin(Lang::line('Зурган дээрх код буруу байна'), 'back');
        }
        
        $systemUserId = Crypt::decrypt($aapud, 'aap');
        
        $updateData = array(
            'PASSWORD_HASH' => Hash::create('sha256', $new_password_confirm)
        );

        $updateResult = $this->db->AutoExecute('UM_SYSTEM_USER', $updateData, 'UPDATE', 'USER_ID = '.$systemUserId); 

        if ($updateResult) {

            if (Config::getFromCache('passwordSuggest') == '1') {

                $userId = $this->db->GetOne("SELECT USER_ID FROM UM_USER WHERE SYSTEM_USER_ID = ".$this->db->Param(0), array($systemUserId));
                $this->db->AutoExecute('UM_META_BLOCK', array('ID' => getUID(), 'CREATED_DATE' => Date::currentDate(), 'IS_CHANGE_PASSWORD' => '1', 'USER_ID' => $userId));
            }
            
            $hash = Crypt::decrypt($aaph, 'aap');
            
            $this->db->Execute("DELETE FROM UM_USER_OTP WHERE PASSCODE = ".$this->db->Param(0), array($hash));

            $this->redirectLogin('Нууц үг амжилттай солигдлоо.', AUTH_URL.'login', 's');
            
        } else {
            $this->redirectLogin('Error', 'back');
        }
    }
    
    public function setLocalStorageUsername($status, $username) {
        
        if ($status == 'success' && Config::getFromCache('PF_IS_LOGIN_SAVE_USERNAME')) {
            if (Input::numeric('isSaveUsername')) {
                Session::set(SESSION_PREFIX.'saveUsernameLocalStorage', base64_encode($username));
            } else {
                Session::set(SESSION_PREFIX.'saveUsernameLocalStorage', '_pf_no_value'); 
            }
        }
    }
    
    public function customerLoginToUserData($tempArr) {

        $result = array(
            'status' => 'success',
            'result' => array(
                'id' => issetParam($tempArr['id']),
                'employeeid' => issetParam($tempArr['employeeid']),
                'employeekeyid' => issetParam($tempArr['employeekeyid']),
                'languageid' => checkDefaultVal($tempArr['languageid'], '1'),
                'languagecode' => issetParam($tempArr['languagecode'], 'mongolian'),
                'languageshortcode' => issetParam($tempArr['languageshortcode'], 'mn'),
                'username' => checkDefaultVal($tempArr['username'], issetParam($tempArr['customercode'])),
                'personname' => checkDefaultVal($tempArr['customerfirstname'], issetParam($tempArr['customercode'])),
                'lastname' => checkDefaultVal($tempArr['customerlastname'], issetParam($tempArr['customercode'])),
                'emppicture' => issetParam($tempArr['emppicture']),
                'positionname' => issetParam($tempArr['positionname']),
                'monpassguid' => issetParam($tempArr['monpassguid']),
                'mobile' => issetParam($tempArr['phone']),
                'phone' => issetParam($tempArr['phone']),
                'email' => issetParam($tempArr['email']),
                'departmentid' => issetParam($tempArr['departmentid']),
                'sessionid' => issetParam($tempArr['sessionid']),
                'pincode' => issetParam($tempArr['pincode']),
                'crmuserid' => issetParam($tempArr['sessioncrmuserid']),
                'customerid' => issetParam($tempArr['customerid']),
                'customercode' => issetParam($tempArr['customercode']),
                'customername' => checkDefaultVal($tempArr['customerfirstname'], issetParam($tempArr['customercode'])),
                'userkeys' => array(
                    array(
                        'id' => issetParam($tempArr['userkeyid']),
                        'code' => issetParam($tempArr['customercode']),
                        'name' => issetParam($tempArr['username']),
                        'customerid' => issetParam($tempArr['customerid']),
                        'customername' => checkDefaultVal($tempArr['customerfirstname'], issetParam($tempArr['customercode'])),
                        'departmentid' => issetParam($tempArr['departmentid']),
                        'objectphoto' => issetParam($tempArr['emppicture'])
                    )
                )
            )
        );
        
        return $result;
    }
    
    public function registerModel() {
        
        $passwordResetUrl = AUTH_URL.'login/register';
        
        if (Input::isEmpty('csrf_token') == true) {
            
            $this->redirectLogin('Csrf Token is empty!', $passwordResetUrl);
            
        } else {
            
            $sessionCsrfToken = Session::get(SESSION_PREFIX.'CsrfTokenps');
            $inputCsrfToken   = Input::post('csrf_token');
            
            if (!hash_equals((string)$sessionCsrfToken, (string)$inputCsrfToken)) {
                $this->redirectLogin('Csrf Token is wrong!', $passwordResetUrl);
            }
        }
        
        Session::delete(SESSION_PREFIX.'CsrfTokenps');
        $this->setSessionDatabaseConnection($passwordResetUrl);
        
        $type = Input::post('p_type');
        $email   = Input::post('p_email');
        $registerNumber  = Input::post('p_registernumber');
        $lastName   = Input::post('p_lastname');
        $firstName   = Input::post('p_firstname');
        $captcha = Input::post('security_code'); 
        
        if (empty($email) || empty($captcha)) {
            $this->redirectLogin(Lang::line('msg_fill_required_fields'), $passwordResetUrl);
        }
        
        if (!isValidEmail($email)) {
            $this->redirectLogin(Lang::line('validate_msg_email'), $passwordResetUrl);
        }

        if (Session::get(SESSION_PREFIX.'security_code') != md5(sha1($captcha))) {
            $this->redirectLogin(Lang::line('Зурган дээрх код буруу байна'), $passwordResetUrl);
        }
        
        $passwordMinLength = (int) Config::getFromCacheDefault('passwordMinLength', null, 8);
        $newPassword = Str::random_string('alnum', $passwordMinLength); 

        $logged = Session::isCheck(SESSION_PREFIX.'LoggedIn');
        if ($logged == false) {
            Session::set(SESSION_PREFIX . 'LoggedIn', true);
        }

        unset($_POST); 
        $_POST['responseType'] = 'outputArray';
        $_POST['nult'] = true;
        $_POST['methodId'] = Config::getFromCacheDefault('userRegisterProcessId', null, '16613343873889');
        $_POST['processSubType'] = 'internal';
        $_POST['create'] = '1';
        $_POST['isSystemProcess'] = 'true';
        $_POST['param']['password'] = $newPassword;
        $_POST['param']['passwordHash'] = Hash::create('sha256', $newPassword);
        $_POST['param']['userName'] = $email;
        $_POST['param']['stateRegNumber'] = $registerNumber;
        $_POST['param']['lastName'] = $lastName;
        $_POST['param']['firstName'] = $firstName;
        $result = (new Mdwebservice())->runProcess();

        if (issetParam($result['status']) === 'success') {
            $this->redirectLogin(Lang::line('registry_succes_msg'), AUTH_URL.'login', 's');
        } else {
            $this->redirectLogin(checkDefaultVal($result['message'], Lang::line('Бүртгэл хийх явцад алдаа гарлаа. Та дахин оролдоно уу.')), $passwordResetUrl);
        }
    }

    public function ssoRunModel () {
        
        $postData = Input::postData();
        $dataStr = base64_decode($postData['hash']);
        parse_str($dataStr, $data);

        $isCustomer = issetParam($data['isCustomer']);
        $data['username'] = 'test123';

        includeLib('Utils/Functions');
        if (issetParam($data['departmentCode']) === '') {
            $dResult = Functions::runProcess('SSO_GET_UNIQUE_DEPARTMENT_CODE_004', array('filterId' => '1'));
            $data['departmentCode'] = issetParam($dResult['result']['configvalue']);
            $data['departmentCode'] = '19961001';
        }

        switch ($isCustomer) {
            case '1':
                $result = Functions::runProcess('CHECK_CRM_USER_004', array('filterusername' => $data['username']));
                if (issetParam($result['result']['id']) !== '') {
                    unset($_POST);
                    $_POST['isHash'] = '1';
                    $_POST['user_name'] = issetParam($result['result']['username']);
                    $_POST['pass_word'] = issetParam($result['result']['passwordhash']);
                    $_POST['isCustomer'] = '1';
                    $_POST['csrf_token'] = self::getCsrfTokenModel();
                    
                    self::runModel();
                } else {
                    $response = self::ssoRegisterModel($data, $isCustomer);
                }
                break;
            default:
                
                $result = Functions::runProcess('CHECK_UM_USER_004', array('filterusername' => $data['username']));
                if (issetParam($result['result']['id']) !== '') {
                    unset($_POST);
                    $_POST['isHash'] = '1';
                    $_POST['user_name'] = issetParam($result['result']['username']);
                    $_POST['pass_word'] = issetParam($result['result']['passwordhash']);
                    $_POST['csrf_token'] = self::getCsrfTokenModel();
                    
                    self::runModel();
                } else {
                    $response = self::ssoRegisterModel($data);
                }

                break;
        }
        
    }

    public function ssoRegisterModel($data, $isCustomer = '') {
        $logged = Session::isCheck(SESSION_PREFIX.'LoggedIn');
        if ($logged == false) {
            Session::set(SESSION_PREFIX . 'LoggedIn', true);
        }
        
        switch ($isCustomer) {
            case '1':
                unset($_POST); 
                
                $_POST['responseType'] = 'outputArray';
                $_POST['nult'] = true;
                $_POST['methodId'] = Config::getFromCacheDefault('crmUserRegisterProcessId', null, '1698053031807222'); /* SSO_CREATE_CRM_USER */
                $_POST['processSubType'] = 'external';
                $_POST['create'] = '0';
                $_POST['isSystemProcess'] = 'true';
                $_POST['param']['userName'] = $data['username'];
                $_POST['param']['email'] = issetParam($data['email']);
                $_POST['param']['phoneNumber'] = issetParam($data['phoneNumber']);
                $_POST['param']['lastName'] = issetParam($data['lastName']);
                $_POST['param']['firstName'] = issetParam($data['firstName']);
                $_POST['param']['stateRegNumber'] = issetParam($data['registerNumber']);
                $_POST['param']['departmentCode'] = issetParam($data['departmentCode']);
                
                $result = (new Mdwebservice())->runProcess();
                if (issetParam($result['status']) === 'success') {
                    $result = Functions::runProcess('CHECK_CRM_USER_004', array('filterusername' => $data['username']));
                    unset($_POST);

                    $_POST['isHash'] = '1';
                    $_POST['user_name'] = issetParam($result['result']['username']);
                    $_POST['pass_word'] = issetParam($result['result']['passwordhash']);
                    $_POST['isCustomer'] = '1';
                    $_POST['csrf_token'] = self::getCsrfTokenModel();
                    
                    self::runModel();

                } else {
                    /* $this->redirectLogin(checkDefaultVal($result['message'], Lang::line('Бүртгэл хийх явцад алдаа гарлаа. Та дахин оролдоно уу.')), $passwordResetUrl); */
                    $this->redirectLogin('Дахин оролдоно уу.', AUTH_URL.'login', 'w'); 
                }

                break;
            default:
                unset($_POST); 
                
                $_POST['responseType'] = 'outputArray';
                $_POST['nult'] = true;
                $_POST['methodId'] = Config::getFromCacheDefault('crmUserRegisterProcessId', null, '1698053031807222'); /* SSO_CREATE_CRM_USER */
                $_POST['processSubType'] = 'external';
                $_POST['create'] = '0';
                $_POST['isSystemProcess'] = 'true';
                $_POST['param']['userName'] = $data['username'];
                $_POST['param']['email'] = issetParam($data['email']);
                $_POST['param']['phoneNumber'] = issetParam($data['phoneNumber']);
                $_POST['param']['lastName'] = issetParam($data['lastName']);
                $_POST['param']['firstName'] = issetParam($data['firstName']);
                $_POST['param']['stateRegNumber'] = issetParam($data['registerNumber']);
                $_POST['param']['departmentId'] = issetParam($data['departmentCode']);
                
                var_dump($_POST);
                $result = (new Mdwebservice())->runProcess();
                var_dump($result);
                die;
                if (issetParam($result['status']) === 'success') {
                    $result = Functions::runProcess('CHECK_UM_USER_004', array('filterusername' => $data['username']));
                    
                    unset($_POST);

                    $_POST['isHash'] = '1';
                    $_POST['user_name'] = issetParam($result['result']['username']);
                    $_POST['pass_word'] = issetParam($result['result']['passwordhash']);
                    $_POST['csrf_token'] = self::getCsrfTokenModel();
                    
                    self::runModel();

                } else {
                    /* $this->redirectLogin(checkDefaultVal($result['message'], Lang::line('Бүртгэл хийх явцад алдаа гарлаа. Та дахин оролдоно уу.')), $passwordResetUrl); */
                    $this->redirectLogin('Дахин оролдоно уу.', AUTH_URL.'login', 'w'); 
                }
                break;
        }
    }
    
    public function supplierRegisterSaveModel() {
        
        try {
            
            $captcha = Input::post('security_code'); 
        
            if (empty($captcha)) {
                throw new Exception(Lang::line('msg_fill_required_fields'));
            }

            if (Session::get(SESSION_PREFIX.'security_code') != md5(sha1($captcha))) {
                throw new Exception(Lang::line('Зурган дээрх код буруу байна'));
            }
            
            $logged = Session::isCheck(SESSION_PREFIX.'LoggedIn');
                    
            if ($logged == false) {
                Session::set(SESSION_PREFIX . 'LoggedIn', true);
            }

            $_POST['nult'] = true;
            
            $response = (new Mdform())->saveKpiDynamicData();
            
            if ($response['status'] == 'success') {
                $response['message'] = 'Баталгаажуулалт хийгдэхээр и-мейл очих болно.';
            }
            
        } catch (Exception $ex) {
            $response = ['status' => 'error', 'message' => $ex->getMessage()];
        }
        
        return $response;
    }
    
    private static $mainCloudServiceAddress = 'http://172.23.0.3:8080/erp-services/RestWS/runJson';
    
    public function checkCloudUserCustomerIdModel($customerId) {
        
        $result = $this->ws->runRestJson(self::$mainCloudServiceAddress, 'checkCloudLicenseStatus_004', ['filterId' => $customerId]);
        
        if ($result['status'] == 'success' && isset($result['result'])) {
            
            if (array_key_exists('isvalid', $result['result'])) {
                
                if ($result['result']['isvalid'] == '1') {
                    
                    $response = [
                        'status'       => 'success', 
                        'email'        => issetParam($result['result']['email']), 
                        'connectionId' => issetParam($result['result']['connectionid'])
                    ];
                    
                } else {
                    $response = ['status' => 'error', 'message' => 'Өмнө нь хэрэглэсэн token байна!'];
                }
            } else {
                $response = ['status' => 'error', 'message' => 'Шалгах процессоос isValid талбар олдсонгүй!'];
            }
            
        } else {
            $response = ['status' => 'error', 'message' => $this->ws->getResponseMessage($result)];
        }
        
        return $response;
    }
    
    public function setDbConnectionBySignupModel($connectionId) {
        
        Session::set(SESSION_PREFIX.'isUseMultiDatabase', true);
        $this->setSessionDatabaseConnection(null, $connectionId);
        
        return true;
    }
    
    public function cloudUserSignupSaveModel() {
        
        try {
            
            $captcha = Input::post('security_code'); 
            if (empty($captcha)) {
                throw new Exception(Lang::line('msg_fill_required_fields'));
            }

            if (Session::get(SESSION_PREFIX.'security_code') != md5(sha1($captcha))) {
                throw new Exception(Lang::line('Зурган дээрх код буруу байна'));
            }
            
            $token = Input::post('token'); 
            if (empty($token)) {
                throw new Exception('Token parameter is required!');
            }
            
            $customerId = Hash::decryption($token);
            if (!$customerId) {
                throw new Exception('Token is wrong!');
            }
            
            $logged = Session::isCheck(SESSION_PREFIX.'LoggedIn');
                    
            if ($logged == false) {
                Session::set(SESSION_PREFIX . 'LoggedIn', true);
                Session::set(SESSION_PREFIX . 'lastTime', time());
            }

            $_POST['nult'] = true;
            $_POST['responseType'] = 'outputArray';
            $_POST['param']['customerId'] = $customerId;

            $response = (new Mdwebservice())->runProcess();
            
            if ($response['status'] == 'success') {
                $response['message'] = 'Бүртгэл амжилттай боллоо та нэвтрэх товчийг дарж нэвтэрнэ үү.';
            }
            
        } catch (Exception $ex) {
            $response = ['status' => 'error', 'message' => $ex->getMessage()];
        }
        
        return $response;
    }
    
}
