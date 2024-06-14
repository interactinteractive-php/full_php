<?php if(!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Appmenu extends Controller {

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }

    public function index($menuCode = null) 
    {                     
        //$defaultModuleId = Session::get(SESSION_PREFIX . 'defaultModuleId');
        $redirectUrl = $this->redirectModule();
        
        if ($redirectUrl) {
            Message::add('s', '', $redirectUrl);
        }
        
        $this->view->title = 'APP MENU'; 
        
        $this->view->css = array_unique(array_merge(array('custom/css/vr-card-menu.css'), AssetNew::metaCss()));
        $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));
        $this->view->isAppmenuNewDesign = Config::getFromCacheDefault('IS_APPMENU_NEWDESIGN', null, 0);
        $this->view->colorSet = '#FF7E79,#9370DB,#00B9F6,#00C9CC,#FF986E,#4169E1,#FFA07A,#98CF5D,#EC87C0,#EB735B,#A88BF1,#29C88F,#FDB600';
        $this->view->isAppmenuPage = true;

        $this->view->menuList = $this->model->getMenuListModel($menuCode, true);
        
        if ($this->view->menuList['status'] == 'success' && isset($this->view->menuList['menuData']) && isset($this->view->menuList['menuData'][0])) {
            
            $this->view->getResetUser = Config::getFromCache('IsChangePassword') == '1' ? $this->model->getResetPasswordUser() : false;
            $appmenuGroupingCount = Config::getFromCacheDefault('appmenuGroupingCount', null, 10);
            
            if (array_key_exists('tagcode', $this->view->menuList['menuData'][0]) && count($this->view->menuList['menuData']) > $appmenuGroupingCount) {
                
                $this->view->js = array_unique(array_merge(array('custom/addon/plugins/jquery-mixitup/jquery.mixitup.min.js'), $this->view->js));
                                
                $this->view->menuList = Arr::groupByArrayByNullKey($this->view->menuList['menuData'], 'tagcode');
                ksort($this->view->menuList, SORT_NATURAL);
                
                $this->view->render('header');
                $this->view->render('appmenu/v2/grouping-v2');
                $this->view->render('footer');
                
            } else {
                $this->view->render('header');
                $this->view->render('appmenu/v2/index');
                $this->view->render('footer');
            }
            
        } else {
            $this->view->render('header');
            $this->view->render('appmenu/message');
            $this->view->render('footer');
        }
    }
    
    public function indexNew() 
    {   
        $this->view->title = 'APP MENU'; 
        
        $this->view->css = array_unique(array_merge(['custom/css/vr-card-menu.css'], AssetNew::metaCss()));
        $this->view->js = array_unique(array_merge(['custom/addon/admin/pages/scripts/app.js'], AssetNew::metaOtherJs()));
        $this->view->fullUrlJs = AssetNew::amChartJs();
        $this->view->isAppmenuNewDesign = Config::getFromCacheDefault('IS_APPMENU_NEWDESIGN', null, 0);
        $this->view->colorSet = '#FF7E79,#9370DB,#00B9F6,#00C9CC,#FF986E,#4169E1,#FFA07A,#98CF5D,#EC87C0,#EB735B,#A88BF1,#29C88F,#FDB600';
        $this->view->isAppmenuPage = true;
        
        $this->view->licenseList = $this->model->getMetaVerseLicenseListModel();
        $this->view->moduleList = $this->model->getMetaVerseModuleListModel();
        
        if ($this->view->moduleList) {
            
            $this->view->getResetUser = Config::getFromCache('IsChangePassword') == '1' ? $this->model->getResetPasswordUser() : false;
            $this->view->js = array_unique(array_merge(['custom/addon/plugins/jquery-mixitup/jquery.mixitup.min.js'], $this->view->js));

            $this->view->render('header');
            $this->view->render('appmenu/v3/grouping-v3');
            $this->view->render('footer');
            
        } else {
            $this->view->render('header');
            $this->view->render('appmenu/message');
            $this->view->render('footer');
        }
    }
    
    public function rappid($menuCode = null) 
    {
        // dd(http_build_query([
        //     'criteriaData'=>[
        //         'filterMainId'=>[[ 'operator'=>"=", 'operand'=>9911 ]]
        //     ],
        // ]));die;
        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        $this->view->fullUrlCss = [
            'http://localhost:8080/css/styles.css'
        ];
        $this->view->render('header');
        $this->view->render('appmenu/rappid');
        $this->view->render('footer');
    }
    
    public function sub($menuCode = null, $metaDataCode = '') 
    {
        $menuCode = Input::param($menuCode);
        $this->view->isAjax = is_ajax_request();
        
        if (!$this->view->isAjax) {
            $this->view->title = 'APP MENU';  
            $this->view->css = array_unique(array_merge(array('custom/css/vr-card-menu.css'), AssetNew::metaCss()));
            $this->view->js = AssetNew::metaOtherJs();
        }
        
        if (Input::postCheck('metaDataId') && Input::isEmpty('metaDataId') === false) {
            $this->view->metaDataId = Input::post('metaDataId');
        }
        
        $this->view->menuList = $this->model->getMenuListModel($menuCode);
        
        $this->load->model('mdmeta', 'middleware/models/');
        
        $this->view->isTab = ($metaDataCode == '') ? false : true;
        $this->view->uniqId = ($metaDataCode == '') ? getUID() : $this->model->getMetaDataIdByCodeModel($metaDataCode);
        $this->view->backUrl = 'appmenu/sub';
        
        $getMenuMetaId = $this->model->getMetaDataIdByCodeModel($menuCode);
        $getMenuLink = $this->model->getMenuLinkModel($getMenuMetaId);       
        
        if (isset($getMenuLink['VIEW_META_DATA_ID']) && $getMenuLink['VIEW_META_DATA_ID']) {
            $this->view->getDataview = $getMenuLink['VIEW_META_DATA_ID'];
        }
        
        if (!$this->view->isAjax) {
            $this->view->render('header');
        }
        
        $this->view->render('appmenu/sub');         
        
        if (!$this->view->isAjax) {
            $this->view->render('footer');      
        }
    }
    
    public function module($menuMetaDataId = '', $contentId = '')
    {
        $menuMetaDataId = Input::param($menuMetaDataId);
        $contentId = Input::param($contentId);
        
        if (empty($menuMetaDataId)) {
            
            Message::add('e', '', 'back');
        } 

        $currentURL = Uri::currentURL();
        
        if (strpos($currentURL, 'omid=') !== false && Input::getCheck('omid') == false) {
            
            $currentURL = str_replace(array('%5b', '%5d'), array('[', ']'), $currentURL);
            Message::add('s', '', $currentURL);
        }        
        
        if (!is_numeric($menuMetaDataId) || ($contentId && !is_numeric($contentId))) {
            
            set_status_header(404);
        
            $err = Controller::loadController('Err');
            $err->index();
            exit;
        }        
        
        if (Input::isEmptyGet('omid') == false) {
            
            $omid = Input::get('omid');
            
            if (!is_numeric($omid)) {
                
                set_status_header(404);
        
                $err = Controller::loadController('Err');
                $err->index();
                exit;
            }
            
            (new Mdobject())->dataview($omid);
            exit;
        }        
        
        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        
        $this->view->fullUrlJs = AssetNew::amChartJs();
        $this->view->contentHtml = null;
        
        if ($contentId != '') {
            
            $this->view->contentHtml = Mdlayout::index($contentId, false, true);
            
        } else {
            
            $cache = phpFastCache();
            $appMenuCache = $cache->get('appmenu_' . Ue::sessionUserId());

            if (issetParam($appMenuCache['count']) == 1 && isset($appMenuCache['actionmetadataid']) && isset($appMenuCache['actionmetatypeid']) && $appMenuCache['actionmetadataid'] && $appMenuCache['actionmetatypeid'] === Mdmetadata::$layoutMetaTypeId) {
                Message::add('s', '', URL.'mdlayoutrender/index/'.$appMenuCache['actionmetadataid'].'?mmid='.$appMenuCache['menuId'].'&mid='.$appMenuCache['menuId']);
            }
        }
        
        if (Input::isEmptyGet('openmenuid') == false) {
            $this->view->openMenuId = Input::get('openmenuid');
        }

        $this->view->getResetUser = Config::getFromCache('IsChangePassword') == '1' ? $this->model->getResetPasswordUser() : false;                  
        
        if (Config::getFromCache('iscontentVideo') == '1' && Session::get(SESSION_PREFIX.'isViewed') !== '1') {
            
            $this->view->getStartupMeta = $this->model->startupUser();
            Session::set(SESSION_PREFIX.'startupContent', '0');
            
            if (isset($this->view->getStartupMeta['IS_VIEWED_CONTENT']) && $this->view->getStartupMeta['IS_VIEWED_CONTENT'] == '0') {
                
                includeLib('Utils/Functions');
                $result = Functions::runProcess('GOV_SESSION_GET_004', array('userid' => Ue::sessionUserKeyId()));
                
                $this->view->contentPath = Config::getFromCache('agentVideoPath'); 

                if (isset($result['result']['count']) && $result['result']['count'] == '1') {
                    $this->view->contentPath = Config::getFromCache('unitVideoPath');
                }
                
                Session::set(SESSION_PREFIX.'startupContent', '1');
            }
        }
        
        if (Input::isEmptyGet('kmid') == false) {
            $this->view->firstMenuOpen = Input::get('kmid');
        }
        
        $this->view->render('header');
        $this->view->render('appmenu/module');
        $this->view->render('footer');     
    }

    public function mvmodule($id)
    {
        
        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        
        $this->view->fullUrlJs = AssetNew::amChartJs();
        $this->view->getResetUser = false;
        
        $_POST['indicatorId'] = $id;
        ob_start();
            (new Mdform)->mvProductRender();
            $getResult = ob_get_contents();
        ob_end_clean();               
        $getResult = json_decode($getResult, true);
        $this->view->contentHtml = $getResult['html'];
        
        header('Content-Type: text/html;charset=UTF-8');
        
        $this->view->render('header');
        $this->view->render('appmenu/module');
        $this->view->render('footer');     
    }
    
    public function redirectModule($redirect_url = null, $defaultModuleId = null, $clickMenuId = null)
    {       
        if (strpos($redirect_url, 'appmenu/indexnew') !== false) {
            return $redirect_url;
        }
        
        $this->load->model('appmenu');
        $appmenuRow = $this->model->getMenuListModel(null, true);

        $count = issetParam($appmenuRow['count']);

        if (!$count) {
            return $redirect_url;
        }

        if ($defaultModuleId) {
            
            $clickMenuIdUrl = ($clickMenuId != '') ? '&openmenuid=' . $clickMenuId : '';
            $redirect_url = URL.'appmenu/module/'.$defaultModuleId.'?mmid='.$defaultModuleId.$clickMenuIdUrl;
            $menuData = $appmenuRow['menuData'];

            foreach ($menuData as $menuRow) {
                if ($menuRow['metadataid'] == $defaultModuleId) {
                    $defaultMenuRow = $menuRow;
                    break;
                }
            }

            if (isset($defaultMenuRow)) {

                if ($defaultMenuRow['actionmetatypeid'] == Mdmetadata::$layoutMetaTypeId) {

                    $redirect_url = URL.'mdlayoutrender/index/'.$defaultMenuRow['actionmetadataid'].'?mmid='.$defaultModuleId.'&mid='.$defaultModuleId.$clickMenuIdUrl;

                } elseif ($defaultMenuRow['actionmetatypeid'] == Mdmetadata::$metaGroupMetaTypeId) {

                    $redirect_url = URL.'mdobject/dataview/'.$defaultMenuRow['actionmetadataid'].'?mmid='.$defaultModuleId.'&mid='.$defaultModuleId.$clickMenuIdUrl;

                } elseif ($defaultMenuRow['actionmetatypeid'] == Mdmetadata::$packageMetaTypeId) {

                    $redirect_url = URL.'mdobject/package/'.$defaultMenuRow['actionmetadataid'].'?mmid='.$defaultModuleId.'&mid='.$defaultModuleId.$clickMenuIdUrl;

                } elseif ($defaultMenuRow['actionmetatypeid'] == Mdmetadata::$pageMetaTypeId) {

                    $redirect_url = URL.'mdlayout/v2/'.$defaultMenuRow['actionmetadataid'].'?mmid='.$defaultModuleId.'&mid='.$defaultModuleId.$clickMenuIdUrl;

                } elseif ($defaultMenuRow['weburl']) {

                    $redirect_url = URL.$defaultMenuRow['weburl'] . '?mmid='.$defaultModuleId.'&mid='.$defaultModuleId.$clickMenuIdUrl;
                }
            }

        } elseif ($count == 1) {
            
            $default_redirect_url = $redirect_url;
            $redirect_url = URL.'appmenu/module/'.$appmenuRow['menuId'].'?mmid='.$appmenuRow['menuId'];
			
            if (isset($appmenuRow['actionmetadataid']) && isset($appmenuRow['actionmetatypeid']) && $appmenuRow['actionmetadataid']) {

                if ($appmenuRow['actionmetatypeid'] == Mdmetadata::$layoutMetaTypeId) {

                    $redirect_url = URL.'mdlayoutrender/index/'.$appmenuRow['actionmetadataid'].'?mmid='.$appmenuRow['menuId'].'&mid='.$appmenuRow['menuId'];

                } elseif ($appmenuRow['actionmetatypeid'] == Mdmetadata::$metaGroupMetaTypeId) {

                    $redirect_url = URL.'mdobject/dataview/'.$appmenuRow['actionmetadataid'].'?mmid='.$appmenuRow['menuId'].'&mid='.$appmenuRow['menuId'];

                } elseif ($appmenuRow['actionmetatypeid'] == Mdmetadata::$packageMetaTypeId) {

                    $redirect_url = URL.'mdobject/package/'.$appmenuRow['actionmetadataid'].'?mmid='.$appmenuRow['menuId'].'&mid='.$appmenuRow['menuId'];

                } elseif ($appmenuRow['actionmetatypeid'] == Mdmetadata::$pageMetaTypeId) {

                    $redirect_url = URL.'mdlayout/v2/'.$appmenuRow['actionmetadataid'].'?mmid='.$appmenuRow['menuId'].'&mid='.$appmenuRow['menuId'];
                }

            } elseif (!empty($appmenuRow['weburl'])) {
                
                if (strpos($appmenuRow['weburl'], 'mdform/indicatorProduct/') !== false) {
                    $redirect_url = $default_redirect_url;
                } else {
                    $redirect_url = URL.$appmenuRow['weburl'] . '&mmid=' . $appmenuRow['menuId'];
                }
            }

            if (is_array($appmenuRow['menuData']) && $appmenuRow['menuData'][0]['isshowcard'] == 'true') {
                $redirect_url = URL.'appmenu/sub/' . $appmenuRow['menuData'][0]['code'];
            }                    
        }
        
        return $redirect_url;
    }
    
    public function licenseExpired() {
        
        $this->view->appName = Input::post('appName');
        $this->view->endDate = Date::formatter(Input::post('endDate'), 'Y.m.d');
        
        $response = array(
            'html' => $this->view->renderPrint('appmenu/license/licenseExpired'),
            'title' => 'License has expired', 
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function licenseExpireWait() {
        
        $this->view->appName = Input::post('appName');
        $this->view->endDate = Date::formatter(Input::post('endDate'), 'Y.m.d');
        $this->view->days = Input::post('days');
        
        $response = array(
            'html' => $this->view->renderPrint('appmenu/license/licenseExpireWait'),
            'title' => 'License has expire', 
            'continue_btn' => $this->lang->line('continue_btn'), 
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function licenseExpireBefore() {
        
        $this->view->appName = Input::post('appName');
        $this->view->days = Input::post('days');
        
        $response = array(
            'html' => $this->view->renderPrint('appmenu/license/licenseExpireBefore'),
            'title' => 'License has expire', 
            'continue_btn' => $this->lang->line('continue_btn'), 
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function getip() {
        echo 'getIpAddress: ' . getIpAddress().'<br />';
        echo 'get_client_ip: ' . get_client_ip().'<br />';
        echo 'SERVER_NAME: ' . $_SERVER['SERVER_NAME'] . '<br />';
        echo 'HTTP_HOST: ' . $_SERVER['HTTP_HOST']; 
        exit;
    }
    
    public function redirectDefaultUrl() {
        
        $defaultModuleId = Session::get(SESSION_PREFIX . 'defaultModuleId');
        $redirectUrl = $this->redirectModule(null, $defaultModuleId);
        
        if (!$redirectUrl) {
            
            $configUrl = Config::getFromCache('CONFIG_START_LINK');
            
            if ($configUrl) {
                $redirectUrl = URL . $configUrl;
            } else {
                $redirectUrl = URL . 'appmenu';
            }
        } 
        
        Message::add('s', '', $redirectUrl);
    }
    
    public function saveStartupMeta() {
        
        $contentExpiredDate = Config::getFromCache('contentExpiredDate');
        
        if (issetParam($contentExpiredDate) !== '') {
            if ($contentExpiredDate === Date::currentDate('Y-m-d')) {
                return $this->db->AutoExecute('UM_USER', array('IS_VIEWED_CONTENT' => '1'), 'UPDATE', "USER_ID = '". Ue::sessionUserKeyId() ."'");
            }
        } else {
            return $this->db->AutoExecute('UM_USER', array('IS_VIEWED_CONTENT' => '1'), 'UPDATE', "USER_ID = '". Ue::sessionUserKeyId() ."'");
        }
        
    }

    public function landingpage() {
        $this->load->model('mdwidget', 'middleware/models/');

        $criteria = array();
        $paging = array(
            'offset' => 1,
            'pageSize' => 10000
        );        

        $this->view->cardList = $this->model->loadListModel(1634551647399267, $criteria, $paging);
        $this->view->moduleList = $this->model->loadListModel(1634274012184111, $criteria, $paging);
        $this->view->newsList = $this->model->loadListModel(1634554599900382, $criteria, $paging);

        $this->view->render('header');
        $this->view->render('appmenu/landingpage/index');
        $this->view->render('footer');
    }

    public function learnlandingpage() {
        $this->load->model('mdwidget', 'middleware/models/');

        $this->view->render('header');
        $this->view->render('appmenu/landingpage/learn');
        $this->view->render('footer');
    }
    
    public function testHeavyServiceAddress() {
        
        $configWsUrl = Config::getFromCache('heavyServiceAddress');
        
        if ($configWsUrl) {
            
            if (@file_get_contents($configWsUrl, false, stream_context_create(array('http' => array('timeout' => 2))))) {
                $result = WebService::runSerializeResponse($configWsUrl, 'getVersion');
                var_dump($result);die;
            } else {
                echo 'No service!';
            }
            
        } else {
            echo 'No config!';
        }
        
        exit;
    }
    
    public function kpi() {
        
        $this->load->model('mdmenu', 'middleware/models/');
        
        $this->view->title = 'KPI MENU'; 
        $this->view->css = array_unique(array_merge(array('custom/css/vr-card-menu.css'), AssetNew::metaCss()));
        $this->view->js = AssetNew::metaOtherJs();
        
        $this->view->menuList = $this->model->getKpiIndicatorMenuModel();

        /* $this->view->css = AssetNew::metaCss(); */
        $this->view->js = AssetNew::metaOtherJs();

        if ($this->view->menuList) {
            
            $this->load->model('appmenu');
            $this->view->getResetUser = Config::getFromCache('IsChangePassword') == '1' ? $this->model->getResetPasswordUser() : false;
            
            $this->view->render('header');
            $this->view->render('appmenu/kpi/index');
            $this->view->render('footer');
            
        } else {
            $this->view->render('header');
            $this->view->render('appmenu/message');
            $this->view->render('footer');
        }
    }
    
    public function newdesign($menuCode = null) 
    {   
        $defaultModuleId = Session::get(SESSION_PREFIX . 'defaultModuleId');
        $redirectUrl = $this->redirectModule(null, $defaultModuleId);
        
        if ($redirectUrl) {
            Message::add('s', '', $redirectUrl);
        }
        
        $this->view->title = 'New design'; 
        
        $this->view->css = array_unique(array_merge(array('custom/css/newdesign.css'), AssetNew::metaCss()));
        $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));

        $this->view->render('header');
        $this->view->render('appmenu/newdesign');
        $this->view->render('footer');
    }    
    
    public function newdesign2($menuCode = null) 
    {   
        $defaultModuleId = Session::get(SESSION_PREFIX . 'defaultModuleId');
        $redirectUrl = $this->redirectModule(null, $defaultModuleId);
        
        if ($redirectUrl) {
            Message::add('s', '', $redirectUrl);
        }
        
        $this->view->title = 'New design 2'; 
        
        $this->view->css = array_unique(array_merge(array('custom/css/newdesign.css'), AssetNew::metaCss()));
        $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));

        $this->view->render('header');
        $this->view->render('appmenu/newdesign_1');
        $this->view->render('footer');
    }    
    
    public function newdesign3($menuCode = null) 
    {   
        $defaultModuleId = Session::get(SESSION_PREFIX . 'defaultModuleId');
        $redirectUrl = $this->redirectModule(null, $defaultModuleId);
        
        if ($redirectUrl) {
            Message::add('s', '', $redirectUrl);
        }
        
        $this->view->title = 'New design 3'; 
        
        $this->view->css = array_unique(array_merge(array('custom/css/newdesign.css'), AssetNew::metaCss()));
        $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));

        $this->view->render('header');
        $this->view->render('appmenu/newdesign_2');
        $this->view->render('footer');
    }    
    
    public function newdesign4($menuCode = null) 
    {   
        $defaultModuleId = Session::get(SESSION_PREFIX . 'defaultModuleId');
        $redirectUrl = $this->redirectModule(null, $defaultModuleId);
        
        if ($redirectUrl) {
            Message::add('s', '', $redirectUrl);
        }
        
        $this->view->title = 'New design 4'; 
        
        $this->view->css = array_unique(array_merge(array('custom/css/newdesign.css'), AssetNew::metaCss()));
        $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));

        $this->view->render('header');
        $this->view->render('appmenu/newdesign_3');
        $this->view->render('footer');
    }    
    
}