<?php
define('_VALID_PHP', true);

require '../config/config.php';

date_default_timezone_set(CONFIG_TIMEZONE);
ini_set('soap.wsdl_cache_enabled', '1');
ini_set('default_socket_timeout', 6000);

switch (ENVIRONMENT) {
    case 'development':
        ini_set('display_errors', 'On');
        ini_set('display_startup_errors', 'On');
        error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED);
    break;
    case 'production':
        ini_set('display_errors', '0');
        ini_set('display_startup_errors', '0');
        error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED);
    break;
    default:
        exit('The application environment is not set correctly.');
}

require '../helper/functions.php';
require '../libs/Controller.php';
require '../libs/Session.php';
require '../libs/Lang.php';
require '../libs/Security.php';
require '../libs/Str.php';
require '../libs/Input.php';
require '../libs/Arr.php';
require '../libs/Hash.php';
require '../libs/Date.php';
require '../libs/WebService.php';

WebService::$isDefaultSessionId = true;

$result = array();
$methodCode = Input::post('methodCode');

if ($methodCode == 'GetCivilInfo') {
    
    $registerNumber = Input::post('registerNumber');
    
    if ($registerNumber) {
        
        ini_set('max_execution_time', 10);
        ini_set('default_socket_timeout', 10);
        
        $param = array('register' => Str::lower($registerNumber));
        $data = WebService::wsdlCall('http://192.168.154.165/Civil.asmx?wsdl', 'GetCivilInfo', $param);
        
        if ($data) {
            
            $array = Arr::objectToArray($data);
            
            if (isset($array['GetCivilInfoResult'])) {
                
                $result = Arr::changeKeyLower($array['GetCivilInfoResult']);
                
                if (isset($result['married_info']['married_info']) && !array_key_exists(0, $result['married_info']['married_info'])) {
                    $result['married_info']['married_info'] = array($result['married_info']['married_info']);
                }
            }
        }
        
        /*$array = array ( 'GetCivilInfoResult' => array ( 'CIVIL_ID' => '1905151', 'REGISTER_NUM' => 'ше79052805', 'REGISTERED_NUM' => '112238130906', 'FORENAME' => 'боржигин', 'SURNAME' => 'чөдөр', 'GIVEN_NAME' => 'алтангэрэл', 'CREATED_DATE' => '9/6/2010 10:12:57 AM', 'BIRTH_DATE' => '1979/05/28', 'SEX_NAME' => 'Эмэгтэй', 'MO_REGISTER_NUM' => 'ше54071309', 'MO_SURNAME' => 'дашцэрэн', 'MO_GIVEN_NAME' => 'мөнхтуяа', 'MARRIED_INFO' => array ( 'Married_Info' => array ( 0 => array ( 'W_REGISTER_NUM' => 'ше79052805', 'W_SURNAME' => 'чөдөр', 'W_GIVEN_NAME' => 'алтангэрэл', 'H_REGISTER_NUM' => 'ше76091611', 'H_SURNAME' => 'александр', 'H_GIVEN_NAME' => 'ганболд', 'MARRIED_DATE' => '6/1/1996 12:00:00 AM', 'REG_DATE' => '10/27/2003 12:00:00 AM', ), 1 => array ( 'W_REGISTER_NUM' => 'ше79052805', 'W_SURNAME' => 'чөдөр', 'W_GIVEN_NAME' => 'алтангэрэл', 'H_REGISTER_NUM' => 'ше76091611', 'H_SURNAME' => 'александр', 'H_GIVEN_NAME' => 'ганболд', 'MARRIED_DATE' => '6/1/1996 12:00:00 AM', 'REG_DATE' => '10/27/2003 12:00:00 AM', ), 2 => array ( 'W_REGISTER_NUM' => 'ше79052805', 'W_SURNAME' => 'чөдөр', 'W_GIVEN_NAME' => 'алтангэрэл', 'H_REGISTER_NUM' => 'ше76091611', 'H_SURNAME' => 'александр', 'H_GIVEN_NAME' => 'ганболд', 'MARRIED_DATE' => '6/1/1996 12:00:00 AM', 'REG_DATE' => '10/27/2003 12:00:00 AM', ), 3 => array ( 'W_REGISTER_NUM' => 'ше79052805', 'W_SURNAME' => 'чөдөр', 'W_GIVEN_NAME' => 'алтангэрэл', 'H_REGISTER_NUM' => 'ше76091611', 'H_SURNAME' => 'александр', 'H_GIVEN_NAME' => 'ганболд', 'MARRIED_DATE' => '6/1/1996 12:00:00 AM', 'REG_DATE' => '10/27/2003 12:00:00 AM', ), 4 => array ( 'W_REGISTER_NUM' => 'ше79052805', 'W_SURNAME' => 'чөдөр', 'W_GIVEN_NAME' => 'алтангэрэл', 'H_REGISTER_NUM' => 'ше76091611', 'H_SURNAME' => 'александр', 'H_GIVEN_NAME' => 'ганболд', 'MARRIED_DATE' => '6/1/1996 12:00:00 AM', 'REG_DATE' => '10/27/2003 12:00:00 AM', ), 5 => array ( 'W_REGISTER_NUM' => 'ше79052805', 'W_SURNAME' => 'чөдөр', 'W_GIVEN_NAME' => 'алтангэрэл', 'H_REGISTER_NUM' => 'ше76091611', 'H_SURNAME' => 'александр', 'H_GIVEN_NAME' => 'ганболд', 'MARRIED_DATE' => '6/1/1996 12:00:00 AM', 'REG_DATE' => '10/27/2003 12:00:00 AM', ), 6 => array ( 'W_REGISTER_NUM' => 'ше79052805', 'W_SURNAME' => 'чөдөр', 'W_GIVEN_NAME' => 'алтангэрэл', 'H_REGISTER_NUM' => 'ше76091611', 'H_SURNAME' => 'александр', 'H_GIVEN_NAME' => 'ганболд', 'MARRIED_DATE' => '6/1/1996 12:00:00 AM', 'REG_DATE' => '10/27/2003 12:00:00 AM', ), 7 => array ( 'W_REGISTER_NUM' => 'ше79052805', 'W_SURNAME' => 'чөдөр', 'W_GIVEN_NAME' => 'алтангэрэл', 'H_REGISTER_NUM' => 'ше76091611', 'H_SURNAME' => 'александр', 'H_GIVEN_NAME' => 'ганболд', 'MARRIED_DATE' => '6/1/1996 12:00:00 AM', 'REG_DATE' => '10/27/2003 12:00:00 AM', ), 8 => array ( 'W_REGISTER_NUM' => 'ше79052805', 'W_SURNAME' => 'чөдөр', 'W_GIVEN_NAME' => 'алтангэрэл', 'H_REGISTER_NUM' => 'ше76091611', 'H_SURNAME' => 'александр', 'H_GIVEN_NAME' => 'ганболд', 'MARRIED_DATE' => '6/1/1996 12:00:00 AM', 'REG_DATE' => '10/27/2003 12:00:00 AM', ), 9 => array ( 'W_REGISTER_NUM' => 'ше79052805', 'W_SURNAME' => 'чөдөр', 'W_GIVEN_NAME' => 'алтангэрэл', 'H_REGISTER_NUM' => 'ше76091611', 'H_SURNAME' => 'александр', 'H_GIVEN_NAME' => 'ганболд', 'MARRIED_DATE' => '6/1/1996 12:00:00 AM', 'REG_DATE' => '10/27/2003 12:00:00 AM', ), 10 => array ( 'W_REGISTER_NUM' => 'ше79052805', 'W_SURNAME' => 'чөдөр', 'W_GIVEN_NAME' => 'алтангэрэл', 'H_REGISTER_NUM' => 'ше76091611', 'H_SURNAME' => 'александр', 'H_GIVEN_NAME' => 'ганболд', 'MARRIED_DATE' => '6/1/1996 12:00:00 AM', 'REG_DATE' => '10/27/2003 12:00:00 AM', ), 11 => array ( 'W_REGISTER_NUM' => 'ше79052805', 'W_SURNAME' => 'чөдөр', 'W_GIVEN_NAME' => 'алтангэрэл', 'H_REGISTER_NUM' => 'ше76091611', 'H_SURNAME' => 'александр', 'H_GIVEN_NAME' => 'ганболд', 'MARRIED_DATE' => '6/1/1996 12:00:00 AM', 'REG_DATE' => '10/27/2003 12:00:00 AM', ), 12 => array ( 'W_REGISTER_NUM' => 'ше79052805', 'W_SURNAME' => 'чөдөр', 'W_GIVEN_NAME' => 'алтангэрэл', 'H_REGISTER_NUM' => 'ше76091611', 'H_SURNAME' => 'александр', 'H_GIVEN_NAME' => 'ганболд', 'MARRIED_DATE' => '6/1/1996 12:00:00 AM', 'REG_DATE' => '10/27/2003 12:00:00 AM', ), 13 => array ( 'W_REGISTER_NUM' => 'ше79052805', 'W_SURNAME' => 'чөдөр', 'W_GIVEN_NAME' => 'алтангэрэл', 'H_REGISTER_NUM' => 'ше76091611', 'H_SURNAME' => 'александр', 'H_GIVEN_NAME' => 'ганболд', 'MARRIED_DATE' => '6/1/1996 12:00:00 AM', 'REG_DATE' => '10/27/2003 12:00:00 AM', ), 14 => array ( 'W_REGISTER_NUM' => 'ше79052805', 'W_SURNAME' => 'чөдөр', 'W_GIVEN_NAME' => 'алтангэрэл', 'H_REGISTER_NUM' => 'ше80032316', 'H_SURNAME' => 'жигмэдсүрэн', 'H_GIVEN_NAME' => 'батмөнх', 'MARRIED_DATE' => '8/4/2016 12:00:00 AM', 'REG_DATE' => '9/4/2017 12:00:00 AM', ), ), ), ), );
        if (isset($array['GetCivilInfoResult'])) {
            $result = Arr::changeKeyLower($array['GetCivilInfoResult']);
        }*/
    } 
}

echo json_encode($result); exit;