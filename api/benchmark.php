<?php

define('_VALID_PHP', true);

date_default_timezone_set("Asia/Irkutsk");
ini_set('soap.wsdl_cache_enabled', '1');
ini_set('default_socket_timeout', 6000);

ini_set('display_errors', 'On');
ini_set('display_startup_errors', 'On');
error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED);
        
require '../libs/Form.php';
        
$start = microtime(true);
        
$ff = Form::create(
    array(
        'id' => 'id', 
        'name' => 'name', 
        'class' => 'class', 
        'onsubmit' => 'onsubmit', 
        'method' => 'method', 
        'action' => 'action', 
        'enctype' => 'enctype', 
        'autocomplete' => 'autocomplete', 
        'target' => 'target', 
        'style' => 'style', 
        'role' => 'role', 
        'validate' => 'validate'
    )
);

$end = microtime(true);

$for_time = $end - $start;

$start = microtime(true);

$ff = Form::formC(
    array(
        'id' => 'id', 
        'name' => 'name', 
        'class' => 'class', 
        'onsubmit' => 'onsubmit', 
        'method' => 'method', 
        'action' => 'action', 
        'enctype' => 'enctype', 
        'autocomplete' => 'autocomplete', 
        'target' => 'target', 
        'style' => 'style', 
        'role' => 'role', 
        'validate' => 'validate'
    )
);

$end = microtime(true);

$foreach_time = $end - $start;

echo "For took: " . number_format($for_time * 1000, 3) . "ms <br />";
echo "Foreach took: " . number_format($foreach_time * 1000, 3) . "ms";

