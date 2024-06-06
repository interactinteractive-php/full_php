<?php

if (!defined('_VALID_PHP'))
    exit('Direct access to this location is not allowed.');

/**
 * Mdalert Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Notification
 * @author	B.Munkh-Erdene <munkherdene@interactive.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdalert
 */
class Mdalert extends Controller {

    private static $viewPath = "middleware/views/alert/";

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }

    /**
     * index
     */
    public function index() {
        $this->view->css = array(
            'custom/addon/plugins/jquery-easyui/themes/metro/easyui.css',
            'custom/addon/plugins/jquery-easyui/themes/icon.css'
        );
        $this->view->js = array(
            'custom/addon/plugins/jquery-easyui/jquery.easyui.min.js',
            'custom/addon/plugins/jquery-easyui/locale/easyui-lang-' . Lang::getCode() . '.js'
        );
        $this->view->render('header');
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer');
    }
    
    /**
     * notification list for header
     * @param type $limit
     * @return type
     */
    public function showAlertListForHdr($limit = 30) {
        try {

            $total = (new Mdalert())->getUnreadAlertMessageCount();  
            if ($total != 0) {
                $list = (new Mdalert())->getUnreadAlertMessageListNew($limit);
            } else {
                $list = null;
            }

            $this->view->list = $list;
            $this->view->totalCount = $total;
            
            return $this->view->renderPrint('alerts', self::$viewPath); // Theme M3
        } catch (Exception $e) {
            return null;
        }
    }
    
    /**
     * get count
     * @return type
     */
    public function getUnreadAlertMessageCount() {
        $this->load->model('mdalert', 'middleware/models/');
        return $this->model->getUnreadAlertMessageCountPhp();
    }
    
    /**
     * get unread list
     * @param type $limit
     * @return type
     */
    public function getUnreadAlertMessageListNew($limit = 30) {
        $this->load->model('mdalert', 'middleware/models/');      
        return $this->model->getUnreadAlertMessageListPhpNew($limit);
    }

    /**
     * show notification
     * @param type $notificationId
     */
    public function show($notificationUserId) {
        $this->load->model('mdalert', 'middleware/models/');
        $notificationUserId = Input::param($notificationUserId);
        $this->view->notification = $this->model->getAlertPhp($notificationUserId);
        if ($this->view->notification) {            
            // Харахад харсан гэж тэмдэглэх эсэх
            $this->model->markAsReadNotificationPhp($notificationUserId);
        }
        
        $response = array(
            'action' => $this->view->notification['ACTION'],
            'title' => 'Alert',                
            'width' => '700',
            'html' => $this->view->renderPrint('showAlert', self::$viewPath), 
            'create_btn' => Lang::line('save_btn'),
            'close_btn' => Lang::line('close_btn')                
        );

        echo json_encode($response); exit;
    }
    
    public function callAlertProcess() {
        $processMetaDataId = Input::post('processMetaDataId');
        $notificationActionId = Input::post('notificationActionId');
        $notificationUserId = Input::post('notificationUserId');        
        $_POST['methodId'] = $processMetaDataId;
        $_POST['processSubType'] = 'internal';
        
        $notificationUserParam = $this->model->getNotificationUserParam($processMetaDataId, $notificationActionId, $notificationUserId);
        $param = array();
        foreach($notificationUserParam AS $row) {
            if($row['NTF_PARAM_PATH'] == 'createdDate') {
                $param[$row['NTF_PARAM_PATH']] = Date::currentDate('Y-m-d H:i:s');
            }else{
                $param[$row['NTF_PARAM_PATH']] = $row['NTF_PARAM_VALUE'];
            }
            
        }
        $_POST['param'] = $param;
        
        try{
            $mdWebservice = new Mdwebservice();
            $result = $mdWebservice->runProcess();
            $this->markAsRead($notificationUserId);
        }  catch (Exception $e) {
            
        }
    }
    
    public function markAsRead($notificationUserId) {
        return $this->model->markAsReadNotificationPhp($notificationUserId);
    }

    public function createSendNotification($templateCode, $url, $notificationTypeId, $toUserId, $systemId = null, $inputParams = null)
    {
        $this->load->model('mdalert', 'middleware/models/');
        $this->model->createSendNotificationPhp($templateCode, $url, $notificationTypeId, $toUserId, $systemId, $inputParams);
    }
    
    // to delete
    public function showAll() {
        $this->view->css = array('plugins/jquery-easyui/themes/icon.css',
            'plugins/jquery-easyui/themes/metro/easyui.css');
        $this->view->js = array('plugins/jquery-easyui/jquery.easyui.min.js',
            'plugins/jquery-easyui/locale/easyui-lang-' . Lang::line('lang_code') . '.js');
        $this->view->title = Lang::line('notification_all_list');

        $this->view->render('header');
//        $this->view->render('index_m2', self::$viewPath);
        $this->view->renderPrint('index', self::$viewPath); // Theme M3
        $this->view->render('footer');
    }
    
    // set Notification on process
    public function notificationConfigWindow() {
        $this->view->defaultValue = $this->model->getNotificationConfig(Input::post('processMetaDataId'));
        $this->view->outputMetaDataList = $this->model->getOutputMetas(Input::post('outputMetaDataId'));
        foreach($this->view->defaultValue AS $k => $row) {
            $notificationId = $row['NOTIFICATION_ID'];
            $this->view->defaultValue[$k]['NOTIFICAITON_PARAM'] = $this->findParamsLogic($notificationId);
        }
        $response = array(
            'Html' => $this->view->renderPrint('system/link/notification/notificationConfigWindow', 'middleware/views/metadata/'),
            'Title' => 'Notification тохируулах',
            'save_btn' => Lang::line('save_btn'),
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response);
    }
    
    public function saveNotificaitonConfig() {
        $processId = Input::post('processId');
        $data = json_decode($_POST['data'], true);
        $dataArray = array();
        foreach ($data AS $k => $v) {
            $dataArray[Security::sanitize($v['name'])][] = Security::sanitize($v['value']);
        }
        
        $result = $this->model->saveNotificaitonConfig($processId, $dataArray);
        if($result) {
            $response = array(
                'status' => 'success',
            );
            
        }else{
            $response = array(
                'status' => 'failed',
            );
        }
        
        echo json_encode($response);
    }

    public function findParams($notificationId = null) {
        if($notificationId == null) {
            $notificationId = Input::post('notificationId');
        }
        $result = $this->model->findParams($notificationId);
        if($result) {
            $response = array(
                'status' => 'success',
                'text' => $result['TEXT'],
                'typeName' => $result['NOTIFICATION_TYPE_NAME'],
                'params' => $this->findParamsLogic($notificationId)
            );
            
        }else{
            $response = array(
                'status' => 'failed',
            );
        }
        
        echo json_encode($response);
    }
    
    public function findParamsLogic($notificationId) {
        $result = $this->model->findParams($notificationId);
        if($result) {
            return  $this->extractParams($result['TEXT'], '[', ']');
            
        }else{
            return null;
        }
    }
    
    private function extractParams($string, $determiner, $determinerLast) {
        $paramList = array();
        $messageStringArray = explode(" ", $string); // array болгож задлана
        $index = 0;
        foreach($messageStringArray AS $k => $oneString) { // element болгоноор давтана
            $first = strpos($oneString, $determiner); // [ element хайж байна
            $second = strpos($oneString, $determinerLast); // ] element хайж байна
            if($first === 0) { // байвал
                $key = substr($oneString, 1, $second-1); // түлхүүр үг
                $paramList[$index] = $key;
                $index++;
            }
        }

        return $paramList;
    }

}
