<?php
define('_VALID_PHP', true);

require '../config/config.php';
date_default_timezone_set(CONFIG_TIMEZONE);

$getDuration = file_get_contents('../'.UPLOADPATH.'synctimer/timedurationTimer.txt');
$getBreak = file_get_contents('../'.UPLOADPATH.'synctimer/timebreakTimer.txt');

echo json_encode(
    array(
        'duration' => $getDuration,
        'break' => $getBreak
    )
);
exit;