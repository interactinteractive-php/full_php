<?php if(!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Profile extends Controller {

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }

    public function index() {
        $this->view->title = $this->lang->line('profile_title');

        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        $this->view->breadcrumbs[] = array('title' => $this->view->title);

        $this->view->isEdit = true;
        $this->view->row = $this->model->getProfileData();

        $this->view->render('header');
        $this->view->render('profile/index');
        $this->view->render('footer');
    }

    public function changePasswordForm() {
        
        $this->view->no_nowpassword = Input::post('no_nowpassword');
        $this->view->showMessage = Input::post('showMessage');
        $this->view->passwordMinLength = Config::getFromCacheDefault('passwordMinLength', null, 8);
        
        $response = array(
            'html' => $this->view->renderPrint('profile/sub/changePasswordForm'),
            'title' => $this->lang->line('change_password'),
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    public function changePassword() {
        $response = $this->model->changePasswordModel();
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    public function changelang($langCode) {
        $previousUrl = $_SERVER['HTTP_REFERER'];
        Lang::changeLanguage($langCode, $previousUrl);
    }

    public function changeFiscalPeriodYear() {
        $yearId = Input::post('yearId');
        Session::set(SESSION_PREFIX . 'periodYearId', $yearId);
    }

    public function changeFiscalPeriodChild() {
        
        self::changeFiscalPeriodYear();
        $childId = Input::post('childId');
        $fiscalPeriodRow = $this->model->checkFiscalPeriodModel($childId);
        
        global $db;

        if ($fiscalPeriodRow) {

            Session::init();

            $userKeyId = Ue::sessionUserKeyId();
            $fiscalPeriodStartDate = Date::format('Y-m-d', $fiscalPeriodRow['START_DATE']);
            $fiscalPeriodEndDate = Date::format('Y-m-d', $fiscalPeriodRow['END_DATE']);

            $userRow = $db->GetRow("
                SELECT 
                    PU.ID AS PID,  
                    FP.ID, 
                    FP.START_DATE, 
                    FP.END_DATE, 
                    FP.PARENT_ID 
                FROM FIN_FISCAL_PERIOD_USER PU 
                    INNER JOIN FIN_FISCAL_PERIOD FP ON FP.ID = PU.FISCAL_PERIOD_ID 
                WHERE PU.USER_ID = " . $db->Param(0) . "  
                ORDER BY PU.CREATED_DATE DESC", array($userKeyId)
            );

            if ($userRow) {

                $data = array(
                    'FISCAL_PERIOD_ID' => $childId,
                    'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s')
                );
                $db->AutoExecute('FIN_FISCAL_PERIOD_USER', $data, 'UPDATE', 'ID = ' . $userRow['PID']);
                
            } else {
                
                $data = array(
                    'ID' => getUID(),
                    'FISCAL_PERIOD_ID' => $childId,
                    'USER_ID' => $userKeyId,
                    'CREATED_DATE' => Date::currentDate('Y-m-d H:i:s')
                );
                $db->AutoExecute('FIN_FISCAL_PERIOD_USER', $data);
            }

            Session::set(SESSION_PREFIX . 'periodId', $childId);
            Session::set(SESSION_PREFIX . 'periodStartDate', $fiscalPeriodStartDate);
            Session::set(SESSION_PREFIX . 'periodEndDate', $fiscalPeriodEndDate);

            echo json_encode(array('startDate' => $fiscalPeriodStartDate, 'endDate' => $fiscalPeriodEndDate));
        }
    }
    
    public function getProfileData() {
        $response = $this->model->getProfileData();
        if ($response) {
            echo json_encode($response);
        } else {
            echo json_encode(array('status' => 'error'));
        }
    }
    
    public function userKeyChoose() {
        $this->view->title = 'Сонгох';

        $this->view->userkeyList = Info::getUserKeyList();

        $this->view->render('profile/userkey/header');
        $this->view->render('profile/userkey/index');
        $this->view->render('profile/userkey/footer');
    }

    public function userKeyLogin($keyHashId) {
        
        $keyId = Crypt::decrypt($keyHashId);

        if (is_numeric($keyId)) {

            $userKeyRow = Info::getUserKeyRowById($keyId);

            Session::init();
            Session::set(SESSION_PREFIX . 'userkeyid', $keyId);
            Session::set(SESSION_PREFIX . 'userKeyCompanyName', $userKeyRow['COMPANY_NAME']);

            $prevUrl = Session::get(SESSION_PREFIX . 'prevUrl');
            
            if ($prevUrl) {
                $redirectUrl = $prevUrl;
            } else {
                $redirectUrl = URL . Config::getFromCache('CONFIG_START_LINK');
            }

            $appMenu = Controller::loadController('Appmenu');
            $redirectUrl = $appMenu->redirectModule($redirectUrl);

            Message::add('s', '', $redirectUrl);
        }

        Message::add('s', '', URL . 'profile/userKeyChoose');
    }

    public function changeScenario() {
        $id = Input::numeric('id');

        if ($id || $id == '0') {

            global $db;

            $data = array('SCENARIO_ID' => $id);
            $db->AutoExecute('UM_USER_SESSION', $data, 'UPDATE', "SESSION_ID = '" . Ue::appUserSessionId() . "'");

            Session::set(SESSION_PREFIX . 'eaScenarioId', $id);
            WebService::runSerializeResponse(GF_SERVICE_ADDRESS, 'reload_other_session_values');

            $response = array('status' => 'success');
        } else {
            $response = array('status' => 'error', 'message' => 'Invalid id');
        }

        echo json_encode($response); exit;
    }

    public function changeUsernameForm() {
        $response = array(
            'html' => $this->view->renderPrint('profile/sub/changeUsernameForm'),
            'title' => $this->lang->line('change_username'),
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function changeUsername() {
        
        $row = $this->model->getUserPassword();
        
        $password = Input::post('password');
        $md5Hash = Hash::createMD5reverse($password);
        $sha256Hash = Hash::create('sha256', $password);
        
        if ($md5Hash == $row['PASSWORD_HASH'] || $sha256Hash == $row['PASSWORD_HASH']) {
            
            $userName = Input::post('user_name');
            $checkuser = $this->db->GetOne("SELECT COUNT(*) FROM UM_SYSTEM_USER WHERE LOWER(USERNAME) = LOWER(".$this->db->Param(0).")", array($userName));
            
            if (!$checkuser) {
                
                $userId = Ue::sessionUserId();
                $this->db->AutoExecute('UM_SYSTEM_USER', array('USERNAME' => $userName), 'UPDATE', 'USER_ID = '.$userId);
                
                $response = array(
                    'status' => 'success',
                    'message' => $this->lang->line('msg_edit_success')
                );
            } else {
                $response = array('status' => 'error', 'message' => Lang::line('Давхардаж байна'));
            }
        } else {
            $response = array('status' => 'error', 'message' => $this->lang->line('user_current_password_error'));
        }
        
        echo json_encode($response); exit;
    }
    
    public function newNotify() {
        
        $json = file_get_contents('storage/notify/appupdate.json');
        
        $this->view->isManual = false;
        $this->view->isOpen = true;
        
        if ($json) {
            
            $json = json_decode($json);
            
            if (isset($json->isManual) && $json->isManual == '1') {
                
                $this->view->isManual = true;
                $this->view->isOpen = false;
                
                if (isset($json->userId) && !empty($json->userId) && $json->userId == Ue::sessionUserId()) {
                    $this->view->isOpen = true;
                }
            }
        }
        
        if (Input::post('isOpen') == '1') {
            
            $this->view->isManual = true;
            $this->view->isOpen = false;
            
            if (isset($json->userId) && !empty($json->userId) && $json->userId == Ue::sessionUserId()) {
                $this->view->isOpen = true;
            }
        }
        
        $this->view->blockMsg = 'Систем дээр шинэчлэлт хийгдэж байна та түр хүлээнэ үү.';
        $this->view->alertMsg = 'Системийн шинэчлэл #time# дараа хийх гэж байгаа тул та хийж байгаа зүйлээ дуусгах эсвэл шинэчлэгдэж дуустал хүлээнэ үү.';
        
        if (!$this->view->isManual) {
            if (Input::isEmpty('blockMsg') == false) {
                $this->view->blockMsg = Input::post('blockMsg');
            }
            if (Input::isEmpty('alertMsg') == false) {
                $this->view->alertMsg = Input::post('alertMsg');
            }
        }
        
        $response = array(
            'html' => $this->view->renderPrint('profile/appnotify/newNotify'),
            'title' => 'Систем шинэчлэлтийн мэдэгдэл', 
            'isOpen' => $this->view->isOpen, 
            'save_btn' => $this->lang->line('save_btn'), 
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function saveNotify() {
        $response = $this->model->saveNotifyModel();
        echo json_encode($response); exit;
    }
    
}
