<?php
$response = '{"status": "3"}';
$json = @file_get_contents('../storage/notify/appupdate.json');

if ($json) {
    
    $json = json_decode($json);
    
    if ($json->isActive == '1') {
        
        $current = date('Y-m-d H:i');
        $nowDate = new DateTime($current);
        $notifyDate = new DateTime($json->startDate);
        
        if (isset($json->userId) && !empty($json->userId) && isset($_GET['i'])) {
            
            $userId = $_GET['i'];
            $userId = filter_var($userId, FILTER_SANITIZE_STRING);
            $userId = trim($userId);
            $userId = stripslashes($userId);
            $userId = strip_tags($userId);
            
            if ($userId == $json->userId) {
                
                $response = '{"status": "5", "message": "Таниас бусад хэрэглэгчид блоклогдсон байгааг анхаарна уу!"}';
                
                if ($notifyDate > $nowDate) {
            
                    $diff = $nowDate->diff($notifyDate);
                    $min = $diff->format('%i');

                    if ($json->prevTime >= $min) {
                        $response = '{"status": "1", "minute": "'.$min.'", "message": "'.str_replace('#time#', '<span id=\"checkupdate-timer\">'.$min.'</span> минутын', $json->description).'"}';
                    }
                }
        
                header('Content-Type: application/json');
                echo $response; exit();
            }
        }
        
        if ($notifyDate > $nowDate) {
            
            $diff = $nowDate->diff($notifyDate);
            $min = $diff->format('%i');
            
            if ($json->prevTime >= $min) {
                $response = '{"status": "1", "minute": "'.$min.'", "message": "'.str_replace('#time#', '<span id=\"checkupdate-timer\">'.$min.'</span> минутын', $json->description).'"}';
            }
            
        } else {
            
            $diffSecond = $notifyDate->diff($nowDate);
            $minSecond = $diffSecond->format('%i');
            
            $blockMessage = 'Систем дээр шинэчлэлт хийгдэж байна та түр хүлээнэ үү.';
            
            if (isset($json->isManual) && $json->isManual == '1') {
                
                if (!empty($json->blockDescription)) {
                    $blockMessage = $json->blockDescription;
                }
                
                $isManual = true;
            }
            
            if ($minSecond <= 1) {
                
                $response = '{"status": "2", "message": "<span id=\"nf-last-update-msg\">'.$blockMessage.'</span>"}';
                
            } else {
                
                if (isset($isManual)) {
                    
                    $response = '{"status": "2", "message": "<span id=\"nf-last-update-msg\">'.$blockMessage.'</span>"}';
                    
                } else {
        
                    ini_set('default_socket_timeout', 1500);
                    ini_set('max_execution_time', 1500);
                    
                    define('_VALID_PHP', true);
                    require '../config/config.php';
                    
                    try {
                        $client = new \SoapClient(GF_SERVICE_ADDRESS.'erp-services/SoapWS?wsdl', array('exceptions' => 1, 'trace' => 1, 'encoding' => 'UTF-8'));

                        $param = array('run' => 
                            array(
                                'pDataElement' => array(
                                    'key' => 'request',
                                    'elements' => array(
                                        array(
                                            'key' => 'sessionId',
                                            'value' => '65178215-7896-4513-8e26-896df9cb36ad'
                                        ),
                                        array(
                                            'key' => 'sessionUpdated', 
                                            'value' => 'true' 
                                        ),
                                        array(
                                            'key' => 'command', 
                                            'value' => 'reload_command'
                                        ), 
                                        array(
                                            'key' => 'parameters',
                                            'elements' => array()
                                        )
                                    )
                                )
                            )
                        );
                        $result = $client->__soapCall('run', $param);

                        if (!isset($result->pDataElement)) {

                            $response = '{"status": "2", "message": "<span id=\"nf-last-update-msg\">'.$blockMessage.'</span>"}';

                        } else {

                            @file_put_contents('../storage/notify/appupdate.json', '{"isActive":"0"}');

                            $response = '{"status": "4", "message": "Шинэчилж дууссан."}';
                        }

                    } catch (\SoapFault $e) {

                        $response = '{"status": "2", "message": "<span id=\"nf-last-update-msg\">'.$blockMessage.'</span>"}';
                    }
                    
                }
            }
        }
        
    } else {
        
        define('_VALID_PHP', true);
        
        require '../config/config.php';
        
        ini_set('display_errors', '0');
        ini_set('display_startup_errors', '0');
        error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED);
        
        date_default_timezone_set(CONFIG_TIMEZONE);
        
        require '../helper/functions.php';
        require '../libs/Uri.php';
        require '../libs/Session.php';
        
        Session::init();
        $logged = Session::isCheck(SESSION_PREFIX.'LoggedIn');

        if ($logged == false) {
            
            $response = '{"status": "6", "message": "Session expired"}';
            
        } elseif (Session::isCheck(SESSION_PREFIX . 'lastTime') && (time() - Session::get(SESSION_PREFIX . 'lastTime') > SESSION_LIFETIME)) { 
            
            Session::destroy();
            
            $response = '{"status": "6", "message": "Session expired"}';
            
        } elseif (defined('SESSION_LIFETIME_USER') && SESSION_LIFETIME_USER) {
            
            if (Session::isCheck(SESSION_PREFIX . 'lastTime') && (time() - Session::get(SESSION_PREFIX . 'lastTime') > SESSION_LIFETIME_USER)) {
                
                if ($logged) {
                    Session::destroy();
                }
                
                $response = '{"status": "6", "message": "Session expired"}';
                
            } elseif (isset($_GET['k']) && $logged) {
                
                $userKeyId = $_GET['k'];
                $userKeyId = filter_var($userKeyId, FILTER_SANITIZE_STRING);
                $userKeyId = trim($userKeyId);
                $userKeyId = stripslashes($userKeyId);
                $userKeyId = strip_tags($userKeyId);
                $sessionUserKeyId = Session::get(SESSION_PREFIX . 'userkeyid');
                
                if ($userKeyId && $sessionUserKeyId != $userKeyId) {
                    $response = '{"status": "7", "message": "User key is not same!"}';
                }
                
                $response = pollingUpdateNotification($sessionUserKeyId, $response);
            }
            
        } elseif (defined('USE_CHAT') && USE_CHAT) {
            
            if (Session::isCheck(SESSION_PREFIX . 'lastTime') && (time() - Session::get(SESSION_PREFIX . 'lastTime') > 600)) {
                
                $response = '{"status": "3", "idle": "1"}';
            }
            
            if (isset($_GET['k']) && $logged) {
                
                $userKeyId = $_GET['k'];
                $userKeyId = filter_var($userKeyId, FILTER_SANITIZE_STRING);
                $userKeyId = trim($userKeyId);
                $userKeyId = stripslashes($userKeyId);
                $userKeyId = strip_tags($userKeyId);
                $sessionUserKeyId = Session::get(SESSION_PREFIX . 'userkeyid');

                if ($userKeyId && $sessionUserKeyId != $userKeyId) {
                    
                    if (strpos($response, '"idle"') !== false) {
                        $response = '{"status": "7", "idle": "1", "message": "User key is not same!"}';
                    } else {
                        $response = '{"status": "7", "message": "User key is not same!"}';
                    }
                }
                
                $response = pollingUpdateNotification($sessionUserKeyId, $response);
            }
            
        } elseif (isset($_GET['k']) && $logged) {
            
            $userKeyId = $_GET['k'];
            $userKeyId = filter_var($userKeyId, FILTER_SANITIZE_STRING);
            $userKeyId = trim($userKeyId);
            $userKeyId = stripslashes($userKeyId);
            $userKeyId = strip_tags($userKeyId);
            $sessionUserKeyId = Session::get(SESSION_PREFIX . 'userkeyid');

            if ($userKeyId && $sessionUserKeyId != $userKeyId) {
                $response = '{"status": "7", "message": "User key is not same!"}';
            }
            
            $response = pollingUpdateNotification($sessionUserKeyId, $response);
        }
    }
}

function pollingUpdateNotification($sessionUserKeyId, $response) {
    
    $lastTime = Session::get(SESSION_PREFIX . 'ntfLastTime');
            
    if (!$lastTime) {
        $time = time();
        Session::set(SESSION_PREFIX . 'ntfLastTime', $time);
        $lastTime = $time;
    }

    if ($lastTime && (time() - $lastTime > 110)) {

        Session::set(SESSION_PREFIX . 'ntfLastTime', time());
        
        require '../libs/Crypt.php';
        require '../libs/ADOdb/adodb-exceptions.inc.php';
        require '../libs/ADOdb/adodb-errorhandler.inc.php';
        require '../libs/ADOdb/adodb.inc.php';
        
        $realpath = str_replace('api\notify.php', '', realpath(__FILE__));
        $realpath = str_replace('api/notify.php', '', $realpath);

        define('BASEPATH', $realpath);
        define('ADODB_ERROR_LOG_TYPE', 3);
        define('ADODB_ERROR_LOG_DEST', 'log/db_errors.log');
        define('ADODB_ASSOC_CASE', 1);

        $ADODB_CACHE_DIR = BASEPATH.'storage/dbcache';
        $ADODB_COUNTRECS = false;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

        $db = ADONewConnection(DB_DRIVER);
        $db->debug = DB_DEBUG;
        $db->connectSID = defined('DB_SID') ? DB_SID : true;
        $db->autoRollback = true;
        $db->datetime = true;

        try {
            
            $db->Connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
            /* 
             * middleware/models/mdnotification_model.php 
             * function getUnreadNotificationMessageCountPhp
             * мөн адил засвар хийнэ
             */
            
            $newNtfCount = $db->GetOne("
                SELECT 
                    COUNT(N.NOTIFICATION_ID) C 
                FROM NTF_NOTIFICATION N 
                    INNER JOIN NTF_NOTIFICATION_USER NU ON N.NOTIFICATION_ID = NU.NOTIFICATION_ID 
                WHERE NU.USER_ID = ".$db->Param(0)."
                    AND N.NOTIFICATION_TYPE_ID NOT IN (5) 
                    AND NU.READ_DATE IS NULL     
                    AND ((NU.NOTIFY_DATE <= ".$db->ToDate($db->Param(1), 'YYYY-MM-DD HH24:MI:SS').") OR (NU.NOTIFY_DATE IS NULL))", 
                array($sessionUserKeyId, date('Y-m-d H:i:s')) 
            );
            
        } catch (Exception $e) {
            
            $newNtfCount = '0';
        } 

        $db->SetCharSet(DB_CHATSET);
        $db->Close();

        $response = str_replace('"}', '", "newnotification": "'.$newNtfCount.'"}', $response);
    }
    
    return $response;
}

header('Content-Type: application/json');
echo $response; exit;