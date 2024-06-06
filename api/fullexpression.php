<?php
define('_VALID_PHP', true);

require '../config/config.php';
require '../helper/functions.php';
require '../libs/Security.php';
require '../libs/Str.php';
require '../libs/Input.php';

$expCode = Input::post('expCode');

if ($expCode == 'pagerDetailAllRowsSum') {
    
    function getCacheDirectory() {
        
        if (defined('CACHE_PATH') && CACHE_PATH != '') {
            $tmp_dir = CACHE_PATH;
        } else {
            $tmp_dir = ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : sys_get_temp_dir();
        }
        
        return $tmp_dir;
    }
    
    $cacheId   = Input::post('cacheId');
    $groupPath = Input::post('groupPath');
    $fieldPath = Input::post('fieldPath');
    $aggregate = Input::post('aggregate');
    $aggrValue = '0';
    
    if ($cacheId && $groupPath && $fieldPath && $aggregate) {
        
        $groupPathLower = strtolower($groupPath);
        $fieldPathLower = strtolower($fieldPath);

        $cacheDir = getCacheDirectory();
        $cachePath = $cacheDir.'/getData/'.$cacheId.'.txt';

        $cacheStr = file_get_contents($cachePath);
        eval('$cacheArray = '.$cacheStr.';'); 

        if (isset($cacheArray[$groupPathLower]) && is_countable($cacheArray[$groupPathLower])) {

            $groupRows = $cacheArray[$groupPathLower];
            $total = count($groupRows);

            if ($total) {

                if ($aggregate == 'sum') {
                    $aggrValue = array_sum(array_column($groupRows, $fieldPathLower));
                }
            }
        }
    }
    
    echo $aggrValue; exit;
    
} elseif ($expCode == 'numberToWords') {
    
    $number = str_replace(',', '', Input::post('number'));
    $currencyCode = Input::post('currencyCode');
    $langCode = Input::post('langCode', 'mn');
    
    $words = amountToWords($number, $currencyCode, $langCode);
    echo $words; exit;
}
