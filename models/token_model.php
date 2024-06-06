<?php

if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Token_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function MapMonpassUser() {
        Session::init();
        $userId = Ue::sessionUserKeyId();
        
        $this->db->AutoExecute('UM_USER_MONPASS_MAP', array('IS_ACTIVE' => 0), 'UPDATE', 'USER_ID = '.$userId);
        
        $data = array(
            'ID' => getUID(),
            'USER_ID' => Input::param($userId),
            'MONPASS_USER_ID' => Input::post('monpassUserId'), 
            'CERTIFICATE_SERIAL_NUMBER' => Input::post('certificateSerialNumber'), 
            'TOKEN_SERIAL_NUMBER' => Input::post('tokenSerialNumber'), 
            'IS_ACTIVE' => 1 
        );

        $result = $this->db->AutoExecute('UM_USER_MONPASS_MAP', $data);

        if ($result) {
            Session::set(SESSION_PREFIX.'monpassGUID', Input::post('monpassUserId'));
            return true;
        }
        return false;
    }    
    
}
