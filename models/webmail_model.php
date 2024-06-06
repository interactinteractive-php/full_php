<?php if(!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Webmail_Model extends Model {

    public function __construct() {
        parent::__construct();
    }
    
    public function getUserEmailModel() {
        
        $row = $this->db->GetRow("
            SELECT 
                EMAIL, 
                PASSWORD_HASH
            FROM UM_USER_EMAIL 
            WHERE IS_DEFAULT = 1 
                AND USER_ID = ".$this->db->Param(0), array(Ue::sessionUserId())
        );
        
        if ($row) {
            return $row;
        }
        return false;
    }
    
    public function saveEmailModel($param) {
        
        $data = array(
            'ID'            => getUID(),
            'USER_ID'       => Ue::sessionUserId(),
            'EMAIL'         => $param['email'],
            'PASSWORD_HASH' => $param['password'], 
            'IS_DEFAULT'    => 1
        );
        $this->db->AutoExecute('UM_USER_EMAIL', $data);
        
        return true;
    }
    
    public function webmailLoginCheckModel() {
        
        $url           = Config::getFromCache('webmailLoginUrl');
        $url           = $url.'rainloop/index.php?ExternalLogin';
        $email         = Input::post('webmail_email');
        $password      = Input::post('webmail_password');
        $password_hash = Crypt::encrypt($password);

        $data = array(
            'Email'    => $email, 
            'Login'    => '',
            'Password' => $password, 
            'Output'   => 'json' 
        );
        
        $postString = http_build_query($data, '', '&');
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded; charset=UTF-8"));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($response, true);
        
        if (isset($result['Result']) && $result['Result'] == true) {
            
            $saveData = array(
                'email'    => $data['Email'], 
                'password' => $password_hash
            );
            $this->model->saveEmailModel($saveData);
        
            $response = array(
                'status'   => 'success', 
                'message'  => 'Амжилттай нэвтэрлээ. Та түр хүлээнэ үү.', 
                'email'    => $email, 
                'password' => $password
            );
            
        } else {
            $response = array('status' => 'error', 'message' => 'Нэвтрэх боломжгүй байна.');
        }
        
        return $response;
    }

}