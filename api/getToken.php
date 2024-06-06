<?php
define('_VALID_PHP', true);

ini_set('soap.wsdl_cache_enabled', '1');
ini_set('default_socket_timeout', 6000);

require '../helper/functions.php';

ob_start('remove_utf8_bom');
require '../config/config.php';
ob_end_flush();

date_default_timezone_set(CONFIG_TIMEZONE);

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

require '../libs/Hash.php';
require '../libs/Date.php';
require '../libs/WebService.php';

$token = WebService::getToken();

jsonResponse(array('token' => $token));