<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Profile_Model extends Model {

    public function __construct() {
        parent::__construct();
    }
    
    public function getProfileData()
    {
        $row = $this->db->GetRow("
            SELECT 
                LAST_NAME, 
                FIRST_NAME, 
                STATE_REG_NUMBER, 
                UM.USERNAME  
            FROM BASE_PERSON BP 
                INNER JOIN UM_SYSTEM_USER UM ON UM.PERSON_ID = BP.PERSON_ID 
            WHERE UM.USER_ID = ".$this->db->Param(0), 
            array(Ue::sessionUserId())
        );
        
        if ($row) {
            return $row;
        }
        return null;
    }
    
    public function getUserPassword()
    {
       $row = $this->db->GetRow("SELECT PASSWORD_HASH FROM UM_SYSTEM_USER WHERE USER_ID = ".$this->db->Param(0), array(Ue::sessionUserId()));
       return $row;
    }   
    
    public function updateUserPassword($data)
    {
        if (Input::post('resetPassword')) {
            $data['PASSWORD_RESET_DATE'] = Date::currentDate();  
        } 
        
        $result = $this->db->AutoExecute('UM_SYSTEM_USER', $data, 'UPDATE', 'USER_ID = '.Ue::sessionUserId());
        
        if ($result) {
            return true;
        } 
        
        return false;
    }
    
    public function fiscalPeriodModel($yearId)
    {
        return Info::childFiscalPeriod($yearId);
    }
    
    public function checkFiscalPeriodModel($childId)
    {
        $row = $this->db->GetRow("SELECT START_DATE, END_DATE FROM FIN_FISCAL_PERIOD WHERE ID = ".$this->db->Param(0), array($childId));
        
        if ($row) {
            return $row;
        } 
        return false;
    }
    
    public function saveNotifyModel() {
        
        $postData = Input::postData();
        
        if (isset($postData['isOpen']) && $postData['isOpen'] == '1') {
            
            $postData = array('isActive' => '0');
            $isFilePut = true;
            
        } elseif (isset($postData['isOpen']) && $postData['isOpen'] == '0') {
            
            $isFilePut = false;
            
        } else {
            
            if (isset($postData['isManual']) && $postData['isManual'] == '1') {
                $postData['userId'] = Ue::sessionUserId();
            }
            
            $isFilePut = true;
        }
        
        if ($isFilePut) {
            
            $isPut = file_put_contents('storage/notify/appupdate.json', json_encode($postData));
            
            $isRunProcess = Config::getFromCache('IS_RUN_PROCESS_AFTER_SHIFT_F1');
            
            if ($isRunProcess == '1' && Input::isEmpty('ruleId') == false && Input::isEmpty('fiscalPeriodId') == false) {
                
                $param = array(
                    'ruleId' => Input::post('ruleId'),
                    'fiscalPeriodId' => Input::post('fiscalPeriodId')
                );

                $this->ws->runResponse(GF_SERVICE_ADDRESS, 'finClearingLogUpdateManual_001', $param);
            }
            
        } else {
            $isPut = true;
        }
        
        if ($isPut) {
            return array('status' => 'success', 'message' => 'Success');
        }
        
        return array('status' => 'error', 'message' => 'Error');
    }
    
    public function changePasswordModel() {
        
        $confirmPassword = Input::post('confirmPassword');
        $validatePass    = Mduser::validatePassword($confirmPassword);
        
        if ($validatePass['status'] != 'success') {
            return $validatePass;
        }
        
        $currentPassword = Input::post('currentPassword');
        $newPassword     = Input::post('newPassword');
        
        if (Session::get(SESSION_PREFIX.'isldap') == 1 && Config::getFromCache('isLdapModifyPassword')) {
            
            if ($newPassword == $confirmPassword) {
                
                $param = array(
                    'isCheckOldUnicodePwd' => 1, 
                    'oldUnicodePwd' => $currentPassword, 
                    'unicodePwd' => $confirmPassword, 
                    'options' => array(
                        'systemUserId' => Ue::sessionUserId()
                    )
                );
                
                $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'MODIFY_PASSWORD_LDAP_USER', $param);
                
                if (isset($result['status']) && $result['status'] == 'success') {
                    $response = array('status' => 'success', 'message' => $this->lang->line('msg_edit_success'));
                } else {
                    $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
                }
                
            } else {
                $response = array('status' => 'error', 'message' => $this->lang->line('user_confirm_password_error'));
            }
            
        } else {
        
            $row        = self::getUserPassword();
            $md5Hash    = Hash::createMD5reverse($currentPassword);
            $sha256Hash = Hash::create('sha256', $currentPassword);

            if (Input::postCheck('no_nowpassword') || $row['PASSWORD_HASH'] == $md5Hash || $row['PASSWORD_HASH'] == $sha256Hash) {

                if ($newPassword == $confirmPassword) {
                    
                    $newPasswordOldHash = Hash::createMD5reverse($confirmPassword);
                    $newPasswordNewHash = Hash::create('sha256', $confirmPassword);
                    
                    if ($row['PASSWORD_HASH'] == $newPasswordOldHash || $row['PASSWORD_HASH'] == $newPasswordNewHash) {
                        
                        $response = array('status' => 'error', 'message' => $this->lang->line('checkNewPasswordOld'));
                        
                    } else {
                        
                        $data = array('PASSWORD_HASH' => $newPasswordNewHash);
                        $result = self::updateUserPassword($data);

                        if ($result) {

                            if (Config::getFromCache('passwordSuggest') == '1') {
                                $this->db->Execute("DELETE FROM UM_META_BLOCK WHERE USER_ID = ".$this->db->Param(0), array(Ue::sessionUserKeyId()));
                            }

                            if (Config::getFromCache('defaultPasswordCheckGet') !== '') {
                                Session::set(SESSION_PREFIX . 'password', $data['PASSWORD_HASH']);
                            }

                            $response = array('status' => 'success', 'message' => $this->lang->line('msg_edit_success'));

                        } else {
                            $response = array('status' => 'error', 'message' => $this->lang->line('msg_save_error'));
                        }
                    }
                    
                } else {
                    $response = array('status' => 'error', 'message' => $this->lang->line('user_confirm_password_error'));
                }
                
            } else {
                $response = array('status' => 'error', 'message' => $this->lang->line('user_current_password_error'));
            }
        }
        
        return $response;
    }
    
}