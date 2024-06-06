<?php
/**
 * DirectoryIndex
 *
 * @package     IA PHPframework
 * @author	B.Och-Erdene
 */

define('BASEPATH', str_replace('index.php', '', realpath(__FILE__)));
define('_VALID_PHP', true);

require BASEPATH.'config/config.php'; 
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

require BASEPATH.'helper/functions.php';
require BASEPATH.LIBS.'Cache/phpfastcache/phpfastcache.php';
require BASEPATH.LIBS.'ADOdb/adodb-exceptions.inc.php';
require BASEPATH.LIBS.'ADOdb/adodb-errorhandler.inc.php';
require BASEPATH.LIBS.'ADOdb/adodb.inc.php';

function autoload($class) {
    $libs = LIBS . $class .'.php';
    if (file_exists($libs)) {
        return require $libs;
    }
    $utils = UTILS . $class .'.php';
    if (file_exists($utils)) {
        return require $utils;
    }
    $md_libs = MD_LIB . $class .'.php';
    if (file_exists($md_libs)) {
        return require $md_libs;
    }
    
    if (defined('IA_PROJECTS') && IA_PROJECTS) {
        $projects = IA_PROJECTS . $class .'.php';
        if (file_exists($projects)) {
            return require $projects;
        }
    }
}

function &getInstance() {
    return Controller::getInstance();
}

spl_autoload_register('autoload', true, true);

Session::init();

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
    if (!is_ajax_request()) {
        echo headerRefreshHtml($e->msg);
    } else {
        echo json_encode(array('error' => $e->msg)); 
    }
    exit;
} 

$db->SetCharSet(DB_CHATSET);

$lang = new Lang();
$lang->load('main');

$bootstrap = new Bootstrap();
$bootstrap->init();

$db->Close();

exit;