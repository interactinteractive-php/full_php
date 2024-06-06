<?php

if (!defined('_VALID_PHP'))
    exit('Direct access to this location is not allowed.');

/**
 * Mdnotification Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Notification
 * @author	B.Munkh-Erdene <munkherdene@interactive.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdnotification
 */
class Mdnotification extends Controller {

    private static $viewPath = 'middleware/views/notification/';
    
    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }

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
    public function showNotificationListForHdrNew($limit = 30, $notificationTypeList = '1,2,3,4,6,7,8,9,10', $viewType = 'notifications') {
        
        if (Config::getFromCache('CONFIG_SYSTEM_NOTIFICATION_HIDE')) {
            return null;
        } else {
            try {
                $total = (new Mdnotification())->getUnreadNotificationMessageCount($notificationTypeList);
    
                $this->view = new View();
                $this->view->totalCount = ($total ? $total : '');
    
                return $this->view->renderPrint($viewType, self::$viewPath);
                
            } catch (Exception $e) {
                return null;
            }
        }
    }
    
    public function getNotificationList() {
        $this->view->list = (new Mdnotification())->getUnreadNotificationMessageListNew(50, '1,2,3,4');
        $this->model->setReadAllNotificationByUserModel();
        $this->view->render('notificationList', self::$viewPath);
    }

    /**
     * show notification
     * @param type $notificationId
     */
    public function show($notificationUserId) {
        
        $notificationUser = $this->model->getNotificationUser($notificationUserId);

        if ($notificationUser) {
            $this->model->markAsReadNotificationUser($notificationUserId);
        }

        if (isset($notificationUser['DIRECT_URL']) && $notificationUser['DIRECT_URL'] != '') {
            header("Location: " . URL . $notificationUser['DIRECT_URL']);
            exit;
        } else {
            Message::add('s', '', URL . '/');
        }
    }

    /**
     * All notification list
     */
    public function notificationList() {
        echo json_encode($this->model->getAllNotificationMessageList());
    }

    /**
     * get unread list
     * @param type $limit
     * @return type
     */
    public function getUnreadNotificationMessageList($limit = 10) {
        $this->load->model('mdnotification', 'middleware/models/');     
        return $this->model->getUnreadNotificationMessageListPhpNew($limit);
    }

    /**
     * get unread list
     * @param type $limit
     * @return type
     */
    public function getUnreadNotificationMessageListNew($limit = 10, $notificationTypeList = '1,2,3,4') {
        $this->load->model('mdnotification', 'middleware/models/');   
        return $this->model->getUnreadNotificationMessageListPhpNew($limit, $notificationTypeList);
    }

    /**
     * get count
     * @return type
     */
    public function getUnreadNotificationMessageCount($notificationTypeList = '1,2,3,4') {
        $this->load->model('mdnotification', 'middleware/models/');
        return $this->model->getUnreadNotificationMessageCountPhp($notificationTypeList);
    }

    public function createSendNotification($templateCode, $url, $notificationTypeId, $toUserId, $systemId = null, $inputParams = null) {
        $this->load->model('mdnotification', 'middleware/models/');
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
        if (Input::post('outputMetaDataId') == null) {
            $this->view->outputMetaDataList = null;
        } else {
            $this->view->outputMetaDataList = $this->model->getOutputMetas(Input::post('outputMetaDataId'));
        }
        foreach ($this->view->defaultValue AS $k => $row) {
            if (isset($row['NOTIFICATION_ID'])) {
                $notificationId = $row['NOTIFICATION_ID'];
                $this->view->defaultValue[$k]['NOTIFICAITON_PARAM'] = $this->findParamsLogic($notificationId);
            } else {
                $this->view->defaultValue[$k] = null;
            }
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
        if ($result) {
            $response = array(
                'status' => 'success',
            );
        } else {
            $response = array(
                'status' => 'failed',
            );
        }

        echo json_encode($response);
    }

    public function findParams($notificationId = null) {
        if ($notificationId == null) {
            $notificationId = Input::post('notificationId');
        }
        $result = $this->model->findParams($notificationId);
        if ($result) {
            $response = array(
                'status' => 'success',
                'text' => $result['TEXT'],
                'typeName' => $result['NOTIFICATION_TYPE_NAME'],
                'params' => $this->findParamsLogic($notificationId)
            );
        } else {
            $response = array(
                'status' => 'failed',
            );
        }

        echo json_encode($response);
    }

    public function findParamsLogic($notificationId) {
        $result = $this->model->findParams($notificationId);
        if ($result) {
            return $this->extractParams($result['TEXT'], '[', ']');
        } else {
            return null;
        }
    }

    private function extractParams($string, $determiner, $determinerLast) {
        $paramList = array();
        $messageStringArray = explode(" ", $string); // array болгож задлана
        $index = 0;
        foreach ($messageStringArray AS $k => $oneString) { // element болгоноор давтана
            $first = strpos($oneString, $determiner); // [ element хайж байна
            $second = strpos($oneString, $determinerLast); // ] element хайж байна
            if ($first === 0) { // байвал
                $key = substr($oneString, 1, $second - 1); // түлхүүр үг
                $paramList[$index] = $key;
                $index++;
            }
        }

        return $paramList;
    }
    
    public function createNotification($notifId, $currentDate, $sessionUserKeyId, $message, $notificationTypeId = 2, $qry = '',  $directUrl = '') {
        $this->load->model('Mdnotification', 'middleware/models/');
        return $this->model->createNotificationModel($notifId, $currentDate, $sessionUserKeyId, $message, $notificationTypeId, $qry,  $directUrl);
    }
}
