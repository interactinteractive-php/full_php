<?php
define('_VALID_PHP', true);
$realpath = str_replace('api\callDataview.php', '', realpath(__FILE__));
$realpath = str_replace('api/callDataview.php', '', $realpath);

define('BASEPATH', $realpath);

if (isset($_POST['dataviewId']) && !empty($_POST['dataviewId'])) {

    require '../config/config.php';

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

    require '../helper/functions.php';
    require '../libs/Controller.php';
    require '../libs/Uri.php';
    require '../libs/Session.php';
    require '../libs/Config.php';
    require '../libs/Lang.php';
    require '../libs/Security.php';
    require '../libs/Str.php';
    require '../libs/Input.php';
    require '../libs/Hash.php';
    require '../libs/Date.php';
    require '../libs/WebService.php';
    require '../libs/Model.php';
    require '../utils/Ue.php';
    loadPhpFastCache();
    
    Session::init();
    
    if (!Session::isCheck(SESSION_PREFIX.'LoggedIn')) {
        WebService::$isDefaultSessionId = true;
    }

    $dataviewId = Input::post('dataviewId');
    $treeGrid = Input::post('treeGrid');
    $pageSize = Input::post('pageSize', 1000);
    $pagingWithoutAggregate = Input::post('pagingWithoutAggregate');
    $criteriaData = Input::post('criteriaData');

    $param = array(
        'systemMetaGroupId' => $dataviewId,
        'showQuery' => 0, 
        'ignorePermission' => 1,
        'paging' => array(
            'offset' => 1,
            'pageSize' => $pageSize
        ),         
        'criteria' => $criteriaData
    );    
    if ($treeGrid) {
        $param['treeGrid'] = 1;
    }
    if ($pagingWithoutAggregate) {
        $param['pagingWithoutAggregate'] = 1;
    }
    
    $data = WebService::runSerializeResponse(GF_SERVICE_ADDRESS, 'PL_MDVIEW_004', $param);
    
    unset($data['result']['paging']);
    unset($data['result']['aggregatecolumns']);    

    echo json_encode($data); exit;
}