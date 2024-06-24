<?php if(!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Cron extends Controller {

    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        
        set_status_header(404);
        
        $err = Controller::loadController('Err');
        $err->index();
        exit;
    }
    
    public function posApiSendData() {
        $result = $this->model->posApiSendDataModel();
        echo $result; exit;
    }
    
    public function posDiscountEmployeeSendMail() {
        $result = $this->model->posDiscountEmployeeSendMailModel();
        echo $result; exit;
    }
    
    public function emdSendData() {
        $result = $this->model->emdSendDataModel();
        echo $result; exit;
    }
    
    public function testmail() {
        $result = $this->model->testmailModel();
        echo $result; exit;
    }
    
    public function importBankBilling($date = '', $account = '') {
        set_time_limit(0);
        
        $result = $this->model->importBankBillingModel($date, $account);
        
        $html = '<html>
            <head>
                <meta charset="utf-8" />
                <title>Import Bank Billing</title>
            </head>
            <body>'.json_encode($result, JSON_UNESCAPED_UNICODE).'</body>
        </html>';
        
        echo $html; 
    }
    
    public function importBankBillingByBankCode($bankCode = '') {
        ignore_user_abort(true);
        set_time_limit(0);
        
        if ($bankCode != '') {
            $result = $this->model->importBankBillingByBankCodeModel($bankCode);
        } else {
            $result = array('status' => 'error', 'message' => 'Invalid bankCode!');
        }
        
        $html = '<html>
            <head>
                <meta charset="utf-8" />
                <title>Import Bank Billing by BankCode</title>
            </head>
            <body>'.json_encode($result, JSON_UNESCAPED_UNICODE).'</body>
        </html>';
        
        echo $html; 
    }
    
    public function importBankBillingBankCode($bankCode) {
        set_time_limit(0);
        
        $result = $this->model->importBankBillingModel('', '', $bankCode);
        
        $html = '<html>
            <head>
                <meta charset="utf-8" />
                <title>Import Bank Billing</title>
            </head>
            <body>'.json_encode($result, JSON_UNESCAPED_UNICODE).'</body>
        </html>';
        
        echo $html; 
    }
    
    public function runGenerateKpiDataMart($mainIndicatorId, $sourceRecordId = '') {
        
        $phpUrl = Config::getFromCache('PHP_URL');
        $phpUrl = rtrim($phpUrl, '/');
        $phpUrl = $phpUrl.'/';
        
        $this->ws->curlQueue($phpUrl . 'cron/runGenerateKpiDataHeaderToDetail/'.$mainIndicatorId.'/'.$sourceRecordId);
        
        echo 'working..';
    }
    
    public function runGenerateKpiDataHeaderToDetail($mainIndicatorId, $sourceRecordId = '') {
        set_time_limit(0);
        
        $logged = Session::isCheck(SESSION_PREFIX.'LoggedIn');
        
        if ($logged == false) {
            Session::set(SESSION_PREFIX . 'LoggedIn', true);
        }
        
        $_POST['nult'] = true;
        $_POST['sourceRecordId'] = $sourceRecordId;
        
        (new Mdform())->generateKpiDataMart($mainIndicatorId);
        
        echo 'working..';
    }
    
    public function runGenerateKpiDetailDataMart($mainIndicatorId, $sourceRecordId = '') {
        ignore_user_abort(true);
        set_time_limit(0);
        
        $logged = Session::isCheck(SESSION_PREFIX.'LoggedIn');
        
        if ($logged == false) {
            Session::set(SESSION_PREFIX . 'LoggedIn', true);
        }
        
        $_POST['nult'] = true;
        $_POST['sourceRecordId'] = $sourceRecordId;
        
        (new Mdform())->generateKpiDetailDataMart($mainIndicatorId);
    }
    
    public function runGenerateKpiRelationDataMart($mainIndicatorId) {
        set_time_limit(0);
        
        $logged = Session::isCheck(SESSION_PREFIX.'LoggedIn');
        
        if ($logged == false) {
            Session::set(SESSION_PREFIX . 'LoggedIn', true);
        }
        
        $_POST['nult'] = true;
        
        (new Mdform())->generateKpiRelationDataMart($mainIndicatorId);
    }
    
    public function runAllKpiDataMart() {
        
        $logged = Session::isCheck(SESSION_PREFIX.'LoggedIn');
        
        if ($logged == false) {
            Session::set(SESSION_PREFIX . 'LoggedIn', true);
        }
        
        $_POST['nult'] = true;
        
        (new Mdform())->runAllKpiDataMart();
    }
    
    public function kpiDmCalcMart() {
        
        $logged = Session::isCheck(SESSION_PREFIX.'LoggedIn');
        
        if ($logged == false) {
            Session::set(SESSION_PREFIX . 'LoggedIn', true);
        }
        
        $_POST['nult'] = true;
        
        (new Mdform())->runAllKpiDataMart('kpiDmCalcMart');
    }
    
    public function runOneKpiDataMart($startIndicatorId, $fileName) {
        
        $logged = Session::isCheck(SESSION_PREFIX.'LoggedIn');
        
        if ($logged == false) {
            Session::set(SESSION_PREFIX . 'LoggedIn', true);
        }
        
        $_POST['nult'] = true;
        
        (new Mdform())->runOneKpiDataMart($startIndicatorId, $fileName);
    }
    
    public function generateKpiDataHeaderToDetail($mainIndicatorId) {
        
        $logged = Session::isCheck(SESSION_PREFIX.'LoggedIn');
        
        if ($logged == false) {
            Session::set(SESSION_PREFIX . 'LoggedIn', true);
        }
        
        $_POST['nult'] = true;
        $_POST['isGeneratedDate'] = 1;
        
        $this->load->model('mdform', 'middleware/models/');
        $this->model->generateKpiDataMartModel($mainIndicatorId, $mainIndicatorId);
        
        echo 'working..';
    }
    
    public function clearKpiIndicatorCache($mode) {
        $response = $this->model->clearKpiIndicatorCacheModel($mode);
        convJson($response);
    }
    
    public function jsonToProcess() {
        $response = $this->model->jsonToProcessModel();
        convJson($response);
    }
    
    public function cloudDbPrepare() {
        $response = $this->model->cloudDbPrepareModel();
        convJson($response);
    }
    
}