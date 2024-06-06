<?php
define('_VALID_PHP', true);

require '../helper/functions.php';

if (is_ajax_request()) {

    ob_start('remove_utf8_bom');
    require '../config/config.php';
    ob_end_flush();

    date_default_timezone_set(CONFIG_TIMEZONE);

    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED);

    require '../libs/Hash.php';
    
    $uname = $_POST['uname'];
    $upass = $_POST['upass'];
    
    if ($uname != '' && $upass != '') {
        
        $date = date('Y-m-dH:i:s');
        $loginInfo = Hash::encryption($uname.'^~^'.$upass.'^~^'.$date);

        echo $loginInfo;
    }
}