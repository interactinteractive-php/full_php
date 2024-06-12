<?php
if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');
class Anket extends Controller {

    private static $viewPath = 'anket/';

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->view->title = 'Анкет';

        $this->view->promp = $this->model->getJobDetailPrompModel('1590634299873396');
        $this->view->resultList = $this->model->getJobListModel();

        $this->getAnketConfig();
        $this->view->render(self::$viewPath . 'header');
        $this->view->render(self::$viewPath . 'index');
        $this->view->render(self::$viewPath . 'footer');
    }

    public function detail($id = null) {
        if (!$id) {
            Message::add('s', '', 'back');
        }
        $this->getAnketConfig();

        $this->view->id = Input::param($id);
        $this->view->title = $this->view->anketOrgName . ' - Ажлын байр';
        $this->view->jobDetail = $this->model->getJobDetailModel($this->view->id);
        $this->view->promp = $this->model->getJobDetailPrompModel('1590634299873396');

        $this->view->render(self::$viewPath . 'header');
        $this->view->render(self::$viewPath . 'detailview');
        $this->view->render(self::$viewPath . 'footer');
    }

    public function form($campaignKeyId = '', $positionId = '', $kpiTemplateId = '') {

        $logged = Session::isCheck(SESSION_PREFIX.'LoggedIn');
        
        if ($logged == false) {
            Session::set(SESSION_PREFIX . 'LoggedIn', true);
            Session::set(SESSION_PREFIX . 'lastTime', time());
        }
        
        $this->getAnketConfig();

        $this->view->title = $this->view->anketOrgName . ' - Ажлын байрны анкет бөглөх';

        $this->view->positionId = $positionId;
        $this->view->campaignKeyId = $campaignKeyId;
        $this->view->kpiTemplateId = $kpiTemplateId;
        $this->view->departmentName = null;
        $this->view->departmentId = null;
        $this->view->positionName = null;
        $this->view->templateId = null;
        $this->view->hrAnketWarningMessage = $this->model->getJobDetailPrompModel('1602654138899');
        $this->view->hrAnketSaveAfterMessage = $this->model->getJobDetailPrompModel('16098505306191');

        $this->view->value = $this->model->getJobDataById($campaignKeyId);
        $isEndCk = false; //$this->model->checkIsEndCkModel($this->view->campaignKeyId);

        if (!$isEndCk) {

            $this->view->js = array('core/js/plugins/addon/phpjs/phpjs.min.js');
            
            $bpId = Config::getFromCache('anketFormProcessId');
            $bpId = $bpId ? $bpId : '16372245835031';
            
            $_POST['isSystemMeta'] = 'false';
            $_POST['isDialog'] = false;
            $_POST['nult'] = true;
            $_POST['isEditMode'] = false;

            $bpContent = (new Mdwebservice())->callMethodByMeta($bpId, null, true);
            
            loadPhpQuery();
        
            $bpContentHtml = phpQuery::newDocumentHTML($bpContent);
            $bpContentHtml->find('.meta-toolbar, .dv-right-tools-btn, .bp-btn-translate')->remove();

            $this->view->contentHtml = $bpContentHtml->html();
            $this->view->contentHtml = str_replace('/*bpScriptLoadEnd*/', 'anketFormLoadEnd();', $this->view->contentHtml);

            $this->view->render(self::$viewPath . 'header');
            $this->view->render(self::$viewPath . 'form');
            $this->view->render(self::$viewPath . 'footer');
        } else {
            $this->view->render(self::$viewPath . 'endpage');
        }
    }
    
    public function runProcess() {
        
        $captcha = Input::post('captchaCode'); 
        
        if (empty($captcha)) {
            jsonResponse(array('status' => 'error', 'message' => 'Зурган дээрх кодыг оруулна уу.'));
        }
        
        if (Session::get(SESSION_PREFIX.'security_code') != md5(sha1($captcha))) {
            jsonResponse(array('status' => 'error', 'message' => 'Зурган дээрх код буруу байна.'));
        }
        
        $_POST['nult'] = 1;
        $_POST['responseType'] = 'outputArray';
        $param = (new Mdwebservice())->runProcess();
        
        $response = array('status' => $param['status'], 'message' => $param['message'], 'uniqId' => $param['uniqId']);
        
        jsonResponse($response);
    }

    public function getJobList() {
        $result = $this->model->getJobListModel();
        echo json_encode($result);
    }

    public function render($departmentId = null, $campaignKeyId = null, $positionId = null, $templateId = null) {

        $departmentName = Input::get('departmentName');
        $positionName = Input::get('positionName');
        $this->getAnketConfig();

        $this->view->title = $this->view->anketOrgName . ' - Хүний нөөцийн сонгон шалгаруулалтын систем';
        $this->view->departmentId = $departmentId;
        $this->view->departmentName = $departmentName;
        $this->view->positionName = $positionName;
        $this->view->positionId = $positionId;
        $this->view->templateId = $templateId;
        $this->view->campaignKeyId = $campaignKeyId;
        $this->view->value = '123';//$this->model->getJobDataById($this->view->id);

        $this->view->render(self::$viewPath . 'form');
    }

    public function contact() {
        $this->view->title = 'Холбоо барих';

        $this->view->render(self::$viewPath . 'header');
        $this->view->render(self::$viewPath . 'contact');
        $this->view->render(self::$viewPath . 'footer');
    }

    public function getAnketConfig() {
        $this->view->anketLogo = Config::getFromCache('anketLogo');
        $this->view->anketLogoWhite = Config::getFromCache('anketLogoWhite');
        $this->view->anketColor = Config::getFromCache('anketColor');
        $this->view->anketHoverColor = Config::getFromCacheDefault('anketHoverColor', null, '#c5cfe0');
        $this->view->anketFacebookLink = Config::getFromCache('anketFacebookLink');
        $this->view->anketTwitterLink = Config::getFromCache('anketTwitterLink');
        $this->view->anketLinkedinLink = Config::getFromCache('anketLinkedinLink');
        $this->view->anketContactLocation = Config::getFromCache('anketContactLocation');
        $this->view->anketContactPhone = Config::getFromCache('anketContactPhone');
        $this->view->anketContactHeaderLocation = Config::getFromCache('anketContactHeaderLocation');
        $this->view->anketWorkTime = Config::getFromCache('anketWorkTime');
        $this->view->anketCopyrigthtext = Config::getFromCache('anketCopyrigthtext');        
        $this->view->anketOrgName = Config::getFromCache('anketOrgName');        
        $this->view->publicAnket = Config::getFromCache('anketNoAd');        
        $this->view->anketInstaLink = Config::getFromCache('anketInstaLink');
        $this->view->anketBriefMessage = Config::getFromCache('anketBriefMessage');
    }
    
}