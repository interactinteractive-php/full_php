<?php
define('_VALID_PHP', true);
require '../config/config.php';

date_default_timezone_set(CONFIG_TIMEZONE);

@file_put_contents('../'.UPLOADPATH.'synctimer/time'.$_POST['type'].'.txt', $_POST['timeStr']);
exit;