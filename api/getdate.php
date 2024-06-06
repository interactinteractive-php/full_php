<?php
$result = '';

if (isset($_POST['dateType'])) {
    
    define('_VALID_PHP', true);
    require '../config/config.php';
    date_default_timezone_set(CONFIG_TIMEZONE);

    $dateType = strtolower(filter_var($_POST['dateType'], FILTER_UNSAFE_RAW));
    
    if ($dateType == 'sysdate') {
        $result = date('Y-m-d');
    } elseif ($dateType == 'sysdatetime') {
        $result = date('Y-m-d H:i:s');
    } elseif ($dateType == 'sysyear') {
        $result = date('Y');
    } elseif ($dateType == 'sysmonth') {
        $result = date('m');
    } elseif ($dateType == 'sysday') {
        $result = date('d');
    } elseif ($dateType == 'syshour') {
        $result = date('H');
    } elseif ($dateType == 'sysminute') {
        $result = date('i');
    } elseif ($dateType == 'sysdaynumber') {
        $result = date('N');
    } elseif ($dateType == 'systime') {
        $result = date('H:i');
    } elseif ($dateType == 'sysyearstart') {
        $result = date('Y').'-01-01';
    } elseif ($dateType == 'sysyearend') {
        $result = date('Y').'-12-31';
    } elseif ($dateType == 'sysmonthstart') {
        $result = date('Y-m').'-01';
    } elseif ($dateType == 'sysmonthend') {
        $result = date('Y-m').'-'.date('t', strtotime('now'));
    } elseif (strpos($dateType, 'sysyearprevmonth') !== false) {
        $result = str_replace('sysyearprevmonth', date('Y-m', strtotime('-1 months')), $dateType);
    } elseif (strpos($dateType, 'sysyearmonth') !== false) {
        $result = str_replace('sysyearmonth', date('Y-m'), $dateType);
    }
}

echo $result; exit;
