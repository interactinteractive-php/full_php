<?php
define('_VALID_PHP', true);

require '../config/config.php';

$getDuration = file_get_contents('../'.UPLOADPATH.'synctimergov/timestart.txt');
$getBreak = file_get_contents('../'.UPLOADPATH.'synctimergov/timepause.txt');

echo json_encode(
    array(
        'duration' => $getDuration,
        'break' => $getBreak
    )
);
exit;