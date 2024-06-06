<?php
define('_VALID_PHP', true);

$realpath = str_replace('api\sendMail.php', '', realpath(__FILE__));
$realpath = str_replace('api/sendMail.php', '', $realpath);

define('BASEPATH', $realpath);

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
require '../libs/ADOdb/adodb.inc.php';

includeLib('Mail/Mail');

$entityBody = file_get_contents('php://input');
@file_put_contents('../log/sendMail_log.log', $entityBody); 

$entityBody = json_decode($entityBody, true);

$body = html_entity_decode($entityBody['body'], ENT_QUOTES, 'UTF-8');

$result = Mail::sendPhpMailer(
    array(
        'subject' => $entityBody['subject'], 
        'altBody' => $entityBody['subject'], 
        'body'    => $body, 
        'toMail'  => $entityBody['to'] 
    )
);

echo json_encode($result, JSON_UNESCAPED_UNICODE); exit;