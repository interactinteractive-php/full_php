<?php
define('_VALID_PHP', true);

require '../config/config.php';
require '../helper/functions.php';

date_default_timezone_set(CONFIG_TIMEZONE);

$uid = getUID();
$sysDateTime = date('Y-m-d H:i:s');

echo $uid.'|'.$sysDateTime; exit;
