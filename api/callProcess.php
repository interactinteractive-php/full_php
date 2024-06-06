<?php
define('_VALID_PHP', true);
$realpath = str_replace('api\callProcess.php', '', realpath(__FILE__));
$realpath = str_replace('api/callProcess.php', '', $realpath);

define('BASEPATH', $realpath);

if (isset($_POST['processCode']) && !empty($_POST['processCode']) && isset($_POST['paramData']) && !empty($_POST['paramData'])) {

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
    require '../libs/Config.php';
    require '../libs/Lang.php';
    require '../libs/Security.php';
    require '../libs/Str.php';
    require '../libs/Input.php';
    require '../libs/Hash.php';
    require '../libs/Date.php';
    require '../libs/WebService.php';

    loadPhpFastCache();
    WebService::$isDefaultSessionId = true;

    $processCode = Input::post('processCode');
    $paramData = Input::post('paramData');

    $data = WebService::runSerializeResponse(GF_SERVICE_ADDRESS, $processCode, $paramData);

    echo json_encode($data); exit;
}