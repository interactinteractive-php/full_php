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
require '../libs/Hash.php';
require '../libs/Date.php';
require '../libs/WebService.php';

WebService::$isDefaultSessionId = true;

$tmpCode = Input::post('tmpCode');
        
$param = array(
    'tmpCode' => $tmpCode
);

if (Input::isEmpty('paramData') == false) {
    $paramData = Input::post('paramData');

    foreach ($paramData as $inputField) {
        if ($inputField['value'] != '') {
            $param[$inputField['inputPath']] = $inputField['value'];
        } 
    }
}

$data = WebService::runSerializeResponse(GF_SERVICE_ADDRESS, 'getSAMPLE_004', $param);

if (isset($data['result']) && isset($data['result'][0])) {
    $result = $data['result'];
} else {
    $result = array();
}

echo json_encode($result); exit;