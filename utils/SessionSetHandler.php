<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class SessionSetHandler extends Controller {
    
    public static function update() {
        
        $isAjax   = is_ajax_request();
        $lastTime = Session::get(SESSION_PREFIX . 'lastTime');
        
        if ($lastTime && (time() - $lastTime > SESSION_LIFETIME)) {
            
            Session::destroy();
            
            if ($isAjax == false) {
                
                Message::add('d', Lang::line('sessionExpired'), AUTH_URL . 'login');
                
            } else {
                
                $response = '{"status": "error", "message": "'.Lang::line('sessionExpired').'", "rows": [], "total": 0, "sessionCase": 0}';
                echo $response; exit;
            }
        }
        
        if (Input::postCheck('nult')) {
            
            return true;
            
        } elseif (!Ue::sessionUserId()) {
            
            if ($isAjax == false) {

                Message::add('d', Lang::line('sessionKilled'), URL . 'logout');

            } else {

                Session::destroy();

                $response = '{"status": "error", "message": "'. Lang::line('sessionKilled') .'", "rows": [], "total": 0, "sessionCase": 1}';
                echo $response; exit;
            }
        }
        
        if ($lastTime && (time() - $lastTime > Session::getIntLifeTime())) {
            
            global $db;

            $row = $db->GetRow("
                SELECT 
                    UPDATED_DATE, 
                    SESSION_ID  
                FROM UM_USER_SESSION 
                WHERE USER_ID = " . $db->Param(0) . "
                    AND SESSION_ID = " . $db->Param(1) . "
                ORDER BY UPDATED_DATE DESC", 
                array(Ue::sessionUserKeyId(), Ue::appUserSessionId())
            );

            if ($row) {

                Session::set(SESSION_PREFIX . 'lastTime', time());

                $db->Execute(
                    "UPDATE UM_USER_SESSION SET UPDATED_DATE = ".$db->sysTimeStamp." WHERE SESSION_ID = ".$db->Param(0), 
                    array($row['SESSION_ID'])
                );
                
                if (Session::get(SESSION_PREFIX.'isLoginAttempts')) {
                    
                    $db->Execute(
                        "UPDATE UM_LOGIN_ATTEMPTS SET UPDATED_DATE = ".$db->sysTimeStamp." WHERE SESSION_ID = ".$db->Param(0), 
                        array($row['SESSION_ID'])
                    );
                }

                return true;

            } else {

                if ($isAjax == false) {

                    Message::add('d', Lang::line('sessionKilled'), URL . 'logout');

                } else {

                    Session::destroy();

                    $response = '{"status": "error", "message": "'. Lang::line('sessionKilled') .'", "rows": [], "total": 0, "sessionCase": 1}';
                    echo $response; exit;
                }
            }
            
        } else {
            return true;
        }
    }

    public static function destroy() {

        $userKeyId = Ue::sessionUserKeyId();

        if ($userKeyId) {
            global $db;
            
            $sessionId = Ue::appUserSessionId();
            
            $db->Execute(
                "DELETE FROM UM_USER_SESSION WHERE USER_ID = ".$db->Param(0)." AND SESSION_ID = ".$db->Param(1), 
                array($userKeyId, $sessionId)
            );
        }
    }

    public static function initLogged($response) {
        
        Session::init();
        Session::set(SESSION_PREFIX . 'systemUserId', $response['id']);
        Session::set(SESSION_PREFIX . 'username', $response['username']);
        Session::set(SESSION_PREFIX . 'firstname', $response['personname']);
        Session::set(SESSION_PREFIX . 'personname', $response['personname']);
        Session::set(SESSION_PREFIX . 'appusersessionid', $response['sessionid']);
        Session::set(SESSION_PREFIX . 'SSID', session_id());
        Session::set(SESSION_PREFIX . 'position', $response['positionname']);
        Session::set(SESSION_PREFIX . 'phone', issetParam($response['phone']));
        Session::set(SESSION_PREFIX . 'mobile', issetParam($response['mobile']));
        Session::set(SESSION_PREFIX . 'email', issetParam($response['email']));
        Session::set(SESSION_PREFIX . 'isldap', issetParam($response['isldap']));
        Session::set(SESSION_PREFIX . 'lastTime', time());

        if ($response['languageid'] != '') {
            
            Session::set(SESSION_PREFIX . 'langid', $response['languageid']);
            Session::set(SESSION_PREFIX . 'langcode', $response['languagecode']);
            Session::set(SESSION_PREFIX . 'langshortcode', strtolower($response['languageshortcode']));
            
            Lang::load('main');
            
        } else {
            
            Session::set(SESSION_PREFIX . 'langid', Config::getFromCache('LANG_ID'));
            Session::set(SESSION_PREFIX . 'langcode', Config::getFromCache('LANG_NAME'));
            Session::set(SESSION_PREFIX . 'langshortcode', Config::getFromCache('LANG'));
        }
        
        if (WebService::$isCustomer) {
            
            Session::set(SESSION_PREFIX . 'customerid', issetParam($response['customerid']));
            Session::set(SESSION_PREFIX . 'crmuserid', issetParam($response['crmuserid']));
            Session::set(SESSION_PREFIX . 'customercode', issetParam($response['customercode']));
            Session::set(SESSION_PREFIX . 'customername', issetParam($response['customername']));
            Session::set(SESSION_PREFIX . 'picture', $response['emppicture']);
        }
        
        if (!$response['sessionid']) {
            file_put_contents('log/loginSessionid.txt', var_export($response, true));
        }
        
        return true;
    }
    
    public static function checkLoginFromDb() {
        global $db;

        $row = $db->GetRow("
            SELECT 
                UPDATED_DATE, 
                SESSION_ID  
            FROM UM_USER_SESSION 
            WHERE USER_ID = " . $db->Param(0) . "
                AND SESSION_ID = " . $db->Param(1) . "
            ORDER BY UPDATED_DATE DESC", 
            array(Ue::sessionUserKeyId(), Ue::appUserSessionId())
        );

        if ($row) {
            $timeFirst           = strtotime($row['UPDATED_DATE']);
            $currentDate         = Date::currentDate('Y-m-d H:i:s');
            $timeSecond          = strtotime($currentDate);
            $differenceInSeconds = $timeSecond - $timeFirst;

            if ($differenceInSeconds <= SESSION_LIFETIME) {
                $updateData = array(
                    'UPDATED_DATE' => $currentDate,
                    'LANGUAGE_ID'  => Input::param(Session::get(SESSION_PREFIX . 'langid'))
                );
                $db->AutoExecute('UM_USER_SESSION', $updateData, 'UPDATE', "SESSION_ID = '" . $row['SESSION_ID'] . "'");
                return true;
            }
        }
        
        return false;
    }
    
    public static function writeSystemLog($userId = '', $requestName = '') {
        global $db;
        try {
            $data = array(
                'ID' => getUID(),
                'IP_ADDRESS' => get_client_ip(),
                'CREATED_DATE' => Date::currentDate(),
                'REQUEST_NAME' => $requestName,
                'USER_ID' => $userId 
            );
            $db->AutoExecute('SYSTEM_WRITE_LOG', $data);
        } catch (Exception $ex) {}
    }

}
