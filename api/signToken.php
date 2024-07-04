<?php
define('_VALID_PHP', true);

$realpath = str_replace('api\signToken.php', '', realpath(__FILE__));
$realpath = str_replace('api/signToken.php', '', $realpath);

define('BASEPATH', $realpath);

ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED);

require '../helper/functions.php';
require '../libs/Uri.php';
require '../libs/Session.php';
require '../libs/Hash.php';

ob_start('remove_utf8_bom');
require '../config/config.php';
ob_end_flush();

if (is_ajax_request()) {

    Session::init();
    
    if ($userId = Session::get(SESSION_PREFIX . 'userid')) {

        $date = date('Y-m-d H:i:s');
        $hash = Hash::encryption($date.'^~^'.$userId.'^~^'.getUID());

        echo $hash;
    }
}

exit;