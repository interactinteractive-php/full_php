<?php
define('_VALID_PHP', true);

$realpath = str_replace('api\dbcheck.php', '', realpath(__FILE__));
$realpath = str_replace('api/dbcheck.php', '', $realpath);

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
require '../libs/ADOdb/adodb-exceptions.inc.php';
require '../libs/ADOdb/adodb-errorhandler.inc.php';
require '../libs/ADOdb/adodb.inc.php';

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
} catch (Exception $e) {
    set_status_header(500, $e->msg);
    exit;
} 

$db->SetCharSet(DB_CHATSET);
$db->Close();

echo 'success';

exit;

