<?php

if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Dashboard Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Meta Layout
 * @category	Dashboard
 * @author	Ts.Ulaankhuu <ulaankhuu@veritech.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Dashboard
 */
class Dashboard extends Controller {

    private static $viewPath = "middleware/views/dashboard_static/";

    public function __construct() {
        parent::__construct();
    }

    public function sales() {
        $this->view->title = 'Sales';
        
        $_POST['widgetCode'] = 'sales';
        $_POST['uniqId'] = getUID();
        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');                
        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);
    }
    public function club_cash() {
        $this->view->title = 'КАССЫН МЭДЭЭ';
        
        $_POST['widgetCode'] = 'club_cash';
        $_POST['uniqId'] = getUID();
        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');                
        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);
    }
    public function sales_juicebar() {
        $this->view->title = 'Борлуулалтын мэдээ';
        
        $_POST['widgetCode'] = 'sales_juicebar';
        $_POST['uniqId'] = getUID();
        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');                
        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);

    }
    public function sales_shangrila() {
        $this->view->title = 'Борлуулалтын мэдээ';
        
        $_POST['widgetCode'] = 'sales_shangrila';
        $_POST['uniqId'] = getUID();
        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');                
        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);

    }

    public function club_bank() {
        $this->view->title = 'ХАРИЛЦАХЫН МЭДЭЭ  ';
        
        $_POST['widgetCode'] = 'club_bank';
        $_POST['uniqId'] = getUID();
        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');                
        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);
    }

    public function sales_club() {
        $this->view->title = 'Pивер клуб борлуулалт';
        
        $_POST['widgetCode'] = 'sales_club';
        $_POST['uniqId'] = getUID();
        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');                
        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);
    }

    public function dcreport() {

        $this->view->title = 'dcreport';
        
        $_POST['widgetCode'] = 'dcreport';
        $_POST['uniqId'] = getUID();
        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');     

        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);

    }
    public function dcreport2() {

        $this->view->title = 'dcreport';
        
        $_POST['widgetCode'] = 'dcreport2';
        $_POST['uniqId'] = getUID();
        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');     

        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);

    }
    public function dcreport4() {

        $this->view->title = 'Хашаа Байшин Тайлан';
        
        $_POST['widgetCode'] = 'dcreport4';
        $_POST['uniqId'] = getUID();
        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');     

        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);

    }
    public function dcreport3() {

        $this->view->title = 'dcreport3';
        
        $_POST['widgetCode'] = 'dcreport3';
        $_POST['uniqId'] = getUID();
        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');     

        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);

    }

    public function rclub() {
        $this->view->title = 'Club';
        
        $_POST['widgetCode'] = 'rclub';
        $_POST['uniqId'] = getUID();
        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');                
        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);

    }    

    public function rclub2() {
        $this->view->title = 'Club';
        
        $_POST['widgetCode'] = 'rclub2';
        $_POST['uniqId'] = getUID();
        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');                
        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);
    }    

    public function supply() {
        $this->view->title = 'Supply';
        
        $_POST['widgetCode'] = 'supply';
        $_POST['uniqId'] = getUID();
        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');                
        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);
    }

    public function industry() {
        $this->view->title = 'Industry';
        
        $_POST['widgetCode'] = 'industry';
        $_POST['uniqId'] = getUID();
        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');                
        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);
    }

    public function cash() {
        $this->view->title = 'Cash';
        
        $_POST['widgetCode'] = 'cash';
        $_POST['uniqId'] = getUID();
        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');                
        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);
    }    

    public function bank() {
        $this->view->title = 'Bank';
        
        $_POST['widgetCode'] = 'bank';
        $_POST['uniqId'] = getUID();
        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');                
        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);
    }    
    
    public function send() {
        $this->model->sendEmailModel();
    }
    
    public function salesStore() {
        $this->view->title = 'Sales Store';
        
        $_POST['widgetCode'] = 'salesstore';
        $_POST['uniqId'] = getUID();
        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');                
        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);
    }    
    
    public function hrms() {
        $this->view->title = 'HRMS';
        
        $_POST['widgetCode'] = 'hrms';
        $_POST['uniqId'] = getUID();
        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');                
        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);
    }    
    
    public function recruitment_status($startDate = false, $endDate = false) {
        $this->view->title = 'HRMS';
        
        $_POST['widgetCode'] = 'recruitment_status';
        $_POST['uniqId'] = getUID();
        $_POST['startDate'] = $startDate ? $startDate : Date::currentDate('Y-m') . '-01';
        $_POST['endDate'] = $endDate ? $endDate : Date::currentDate('Y-m-d');
        
        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');                
        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);
    }    
    
    public function hrm_activity($startDate = false, $endDate = false) {
        $this->view->title = 'HRMS';
        
        $_POST['widgetCode'] = 'hrm_activity';
        $_POST['uniqId'] = getUID();
        $_POST['startDate'] = $startDate ? $startDate : Date::currentDate('Y-m') . '-01';
        $_POST['endDate'] = $endDate ? $endDate : Date::currentDate('Y-m-d');
        
        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');                
        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);
    }    
    
    public function salary() {
        $this->view->title = 'SALARY';
        
        $_POST['widgetCode'] = 'salary';
        $_POST['uniqId'] = getUID();
        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');                
        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);
    }    
    
    public function sendMailForm() {
        
        $response = array(
            'html' => $this->view->renderPrint('sendmail', self::$viewPath),
            'title' => Lang::line('sendmail'),
            'send_btn' => Lang::line('send_btn'),
            'close_btn' => Lang::line('close_btn')
        );
        
        echo json_encode($response); exit;
    }    
    
    public function sendMail() {
        $response = $this->model->sendMailModel();
        echo json_encode($response); exit;
    }    
    
    public function waterGauge() {
        if (!is_ajax_request()) $this->view->render('header');

        $this->view->uniqId = getUID();
        $this->view->render('waterGauge', self::$viewPath);

        if (!is_ajax_request()) $this->view->render('footer');        
    }
    
    public function supplyManagement() {
        $this->view->title = 'Supply Management';
        
        $_POST['widgetCode'] = 'suma';
        $_POST['uniqId'] = getUID();
        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');                
        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);
    }    
    
    public function hrm_uz($startDate = '', $endDate = '', $depIds = '') {
        $this->view->title = 'HRMS';
        
        $_POST['widgetCode'] = 'hrm_uz';
        $_POST['uniqId'] = getUID();
        $_POST['startDate'] = $startDate;
        $_POST['endDate'] = $endDate;
        $_POST['depIds'] = $depIds;
        
        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');                
        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);
    }    

    public function glance($startDate = '', $endDate = '', $depIds = '', $isHierarchy = '') {
        $this->view->title = 'Executive dashboard at glance';
        
        $_POST['widgetCode'] = 'glance';
        $_POST['uniqId'] = getUID();
        $_POST['startDate'] = $startDate;
        $_POST['endDate'] = $endDate;
        $_POST['depIds'] = $depIds;
        $_POST['isHierarchy'] = $isHierarchy;
        
        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');                
        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('glance/index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);
    }
    
    public function glanceWithOther($startDate = '', $endDate = '', $depIds = '', $isHierarchy = '') {
        $this->view->title = 'Executive dashboard at glance';
        
        $_POST['widgetCode'] = 'withOther';
        $_POST['uniqId'] = getUID();
        $_POST['startDate'] = $startDate;
        $_POST['endDate'] = $endDate;
        $_POST['depIds'] = $depIds;
        $_POST['isHierarchy'] = $isHierarchy;
        
        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');                
        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('glance/index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);
    }

    public function project($startDate = '', $endDate = '', $depIds = '', $isHierarchy = '') {
        $this->view->title = 'Project';
        
        $_POST['widgetCode'] = 'project';
        $_POST['uniqId'] = getUID();
        $_POST['startDate'] = $startDate;
        $_POST['endDate'] = $endDate;
        $_POST['depIds'] = $depIds;
        $_POST['isHierarchy'] = $isHierarchy;

        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');                
        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);
    }    

    public function project4($startDate = '', $endDate = '', $depIds = '', $isHierarchy = '') {
        $this->view->title = 'Project';
        
        $_POST['widgetCode'] = 'project4';
        $_POST['uniqId'] = getUID();
        $_POST['startDate'] = $startDate;
        $_POST['endDate'] = $endDate;
        $_POST['depIds'] = $depIds;
        $_POST['isHierarchy'] = $isHierarchy;

        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');                
        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);
    }    

    public function project5($startDate = '', $endDate = '', $depIds = '', $isHierarchy = '') {
        $this->view->title = 'Project';
        
        $_POST['widgetCode'] = 'project5';
        $_POST['uniqId'] = getUID();
        $_POST['startDate'] = $startDate;
        $_POST['endDate'] = $endDate;
        $_POST['depIds'] = $depIds;
        $_POST['isHierarchy'] = $isHierarchy;

        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');                
        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);
    }    

    public function project3($startDate = '', $endDate = '', $depIds = '', $isHierarchy = '') {
        $this->view->title = 'Project';
        
        $_POST['widgetCode'] = 'project3';
        $_POST['uniqId'] = getUID();
        $_POST['startDate'] = $startDate;
        $_POST['endDate'] = $endDate;
        $_POST['depIds'] = $depIds;
        $_POST['isHierarchy'] = $isHierarchy;

        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');                
        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);
    }    

    public function project1($startDate = '', $endDate = '', $depIds = '', $isHierarchy = '') {
        $this->view->title = 'Project';
        
        $_POST['widgetCode'] = 'project1';
        $_POST['uniqId'] = getUID();
        $_POST['startDate'] = $startDate;
        $_POST['endDate'] = $endDate;
        $_POST['depIds'] = $depIds;
        $_POST['isHierarchy'] = $isHierarchy;

        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');                
        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);
    }    

    public function project2($startDate = '', $endDate = '', $depIds = '', $isHierarchy = '') {
        $this->view->title = 'Project';
        
        $_POST['widgetCode'] = 'project2';
        $_POST['uniqId'] = getUID();
        $_POST['startDate'] = $startDate;
        $_POST['endDate'] = $endDate;
        $_POST['depIds'] = $depIds;
        $_POST['isHierarchy'] = $isHierarchy;

        $widgetCtrl = Controller::loadController('Mdwidget', 'middleware/controllers/');                
        $this->view->widgetPreview = $widgetCtrl->runWidget();
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer', self::$viewPath);
    }    
    public function allimages() {
        $type = Input::post('charttype');
        $dtype = Input::post('diagram');
        $imageTypes = '{*.jpg,*.JPG,*.jpeg,*.JPEG,*.png,*.PNG,*.gif,*.GIF}';
        (String) $Html = "";
        $files = glob('middleware/views/dashboard/themes/'.$dtype.'-*.*');
        
        $index = 1;
        if ($files) {
            foreach ($files as $key => $img) {
                $Html .= '<div class="col"><a href="'.URL.'/'.$img.'" data-fancybox="images"><img src="'.URL.'/'.$img.'" class="d-block w-100" alt="img name"/></a></div>';
                $index++;
            }
        }else{
            $Html .="<div class='w-100'><h1>Диаграм зураг оруулаагүй байна</h1></div>";
        }
        echo json_encode(array('Html' => $Html));
    }    
    
    public function hrSummary($theme = ''){
        
        $this->load->model('mddatamodel', 'middleware/models/');
        includeLib('Utils/Functions');

        $this->view->title = 'Human resource summary';
        $this->view->uniqId = getUID();
        $this->view->theme = $theme;

        $this->view->js = AssetNew::metaOtherJs();
        $this->view->fullUrlCss = array('middleware/assets/css/scss/hr-main.css');
        $this->view->css = array_unique(array_merge(array('custom/addon/plugins/owl-carousel/owl.carousel.css'), AssetNew::metaCss()));
        $this->view->isAjax = is_ajax_request();

        $this->view->layoutType = 'ecommerce';

        (Array) $criteria['isDefault'][] = array(
            'operator' => '=',
            'operand' =>  '1'
        );

        if( $theme == 'vp'){
           $filterDv = '1592796649354';
        }
        
        $this->view->metaDataId = $filterDv;

        $this->load->model('mdobject', 'middleware/models/');

        $this->view->dataViewMandatoryHeaderData = '';
        $this->view->dataViewHeaderRealData = $this->model->dataViewHeaderDataModel($this->view->metaDataId);
        $this->view->row = $this->model->getDataViewConfigRowModel($this->view->metaDataId);
        $this->view->dataViewCriteriaType = strtolower($this->view->row['SEARCH_TYPE'] == '0' ? 'BUTTON' : Info::getSearchType($this->view->row['SEARCH_TYPE']));
        $this->view->dataViewHeaderData = Mdobject::findCriteria($this->view->metaDataId, $this->view->dataViewHeaderRealData);
        $this->view->defaultCriteria = $this->view->renderPrint('search/defaultCriteria', 'middleware/views/metadata/dataview/');
      
     
        $this->view->render('header', self::$viewPath);
        $this->view->render('custom/summarydashboard', self::$viewPath);
        $this->view->render('header', self::$viewPath);

    }    
    
    public function hrSummaryDashboardData($theme = ''){

     
        includeLib('Utils/Functions');
        $this->view->uniqId = getUID();
    
        $this->view->isAjax = is_ajax_request();
        $this->view->ajax = false;
        $criteria = array();
        
        $criteria['filterStartDate'] = array(
            array(
                'operator' => '=',
                'operand' =>  Date::currentDate('Y-m').'-01'
            )                     
        );
        $criteria['filterEndDate'] = array(
            array(
                'operator' => '=',
                'operand' =>  Date::currentDate('Y-m-d')
            )                     
        );
        // default 

        $this->load->model('mdasset', 'middleware/models/');
        $this->view->GroupbyList = $this->model->getDataMartDvRowsModel('1591862882772',$criteria);    
        $this->view->news = $this->model->getDataMartDvRowsModel('1591863387783');   
      
        // 8.news list
        $this->view->trasGroup = Arr::groupByArrayOnlyRows($this->view->GroupbyList, 'groupname');
        $this->view->main1_1 = Arr::naturalsort($this->model->getDataMartDvRowsModel('1592453115875'), 'category');
        
        $this->view->layoutPositionArr = $this->model->dashboardLayoutDataModel($theme, $criteria, '0', '1');
        
        $response = array(
            'Html' => $this->view->renderPrint('custom/dashboard/'.$theme.'', self::$viewPath),
            'uniqId' => $this->view->uniqId,
            'criteria' => $criteria
        );
        echo json_encode($response);
    }
    
    public function renderByQryStr($dashboardId = '') {
        
        if (!$dashboardId) {
            echo 'statementId parameter todorhoi bus bna'; exit;
        }
        
        Session::init();
        $logged = Session::isCheck(SESSION_PREFIX.'LoggedIn');

        if ($logged == false) {
            Session::set(SESSION_PREFIX . 'LoggedIn', true);
            Session::set(SESSION_PREFIX . 'lastTime', time());
        }

        $_POST['nult'] = true;
        $_POST['isWorkAlone'] = 1;
        
        $getData = Input::getData();
            
        if (isset($getData['param'])) {
            $params = $getData['param'];

            foreach ($params as $key => $val) {
                $isCorrectVal = (boolean) preg_match("/^[0-9a-zA-ZФЦУЖЭНГШҮЗКЪЙЫБӨАХРОЛДПЯЧЁСМИТЬВЮЕЩфцужэнгшүзкъйыбөахролдпячёсмитьвюещ_\(\)\>\<\=\-'\:\[\]\s]{1,500}$/i", $val);
                    
                if ($isCorrectVal) {
                    $params[$key] = Mdmetadata::setDefaultValue($val);
                }
            }
            
            if ($params) {
                $_POST['filterParams'] = $params;
            }
        }
        
        (new Mdlayoutrender())->layout($dashboardId, 1);
    }
    
    public function delayUrl() {
        sleep(20);
        header('Content-Type: application/javascript');
        
        echo 'console.log(\'dashboard done\');';
    }
        
}