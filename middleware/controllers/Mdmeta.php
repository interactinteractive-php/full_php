<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdmeta Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Meta
 * @author	B.Och-Erdene <ocherdene@interactive.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdmeta
 */

class Mdmeta extends Controller {

    public static $viewPath = 'middleware/views/metadata/';
    public static $lockServerAddr = 'http://devlocal.veritech.mn/locker/metalock/';
    public static $onlyMenuTypeIds = array('200101010000025');

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }

    public function bannerRenderByPost() {
        
        $this->load->model('mdmetadata', 'middleware/models/');
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->bannerList = $this->model->getMetaDataPhotosModel($this->view->metaDataId);
        
        $metaRow = $this->model->getMetaDataModel($this->view->metaDataId);

        $response = array(
            'Html' => $this->view->renderPrint('system/link/banner/render', self::$viewPath),
            'Title' => $metaRow['META_DATA_NAME'],
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function getMenuLink($metaDataId) {
        $this->load->model('mdmeta', 'middleware/models/');
        return $this->model->getMenuLinkModel($metaDataId);
    }

    public function menuRenderByPost() {

        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->metaRow = $this->model->getMenuLinkModel($this->view->metaDataId);

        if ($this->view->metaRow['MENU_POSITION'] == 'horizontal') {
            $this->view->menuHtml = $this->model->horizontalMenuRenderModel($this->view->metaDataId, $this->view->metaRow);
        } else {
            $this->view->menuHtml = $this->model->verticalMenuRenderModel($this->view->metaDataId, $this->view->metaRow);
        }

        $response = array(
            'Html' => $this->view->renderPrint('system/link/menu/render', self::$viewPath),
            'Title' => $this->view->metaRow['META_DATA_NAME'],
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function menuRenderByMetaCode($metaDataCode, $isChild, $menuOpen = "") {
        $this->load->model('mdmeta', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $url = Input::get('url');
        $urlArr = explode('/', $url);
        $urlId = '';
        if (isset($urlArr[2])) {
            $urlId = $urlArr[2];
        }
        $metaDataId = $this->model->getMetaDataIdByCodeModel($metaDataCode);
        $this->view->menuHtml = $this->model->mainLeftMenuRenderModel($metaDataId, 0, $isChild, $menuOpen, $urlId);

        return $this->view->renderPrint('system/link/menu/render', self::$viewPath);
    }

    public function topMenuRenderByMetaCode($metaDataCode, $isChild, $menuOpen = "") {
        $this->load->model('mdmeta', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $url = Input::get('url');
        $urlArr = explode('/', $url);
        $urlId = '';
        if (isset($urlArr[2])) {
            $urlId = $urlArr[2];
        }
        $metaDataId = $this->model->getMetaDataIdByCodeModel($metaDataCode);
        $this->view->menuHtml = $this->model->topMenuRenderModel($metaDataId, 0, $isChild, $menuOpen, $urlId);

        return $this->view->renderPrint('system/link/menu/render', self::$viewPath);
    }
    
    public function topMenuRenderByService($isChild, $menuOpen = "") {
        $this->load->model('mdmeta', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $url = Input::get('url');
        $urlArr = explode('/', $url);
        $urlId = '';
        if (isset($urlArr[2])) {
            $urlId = $urlArr[2];
        }
        
        $menuData = $this->model->getMetaMenuListByServiceModel();

        if ($menuData['status'] == 'success') {
            $this->view->menuHtml = $this->model->topMenuRenderByDataModel($menuData['menuData'], 0, $isChild, $menuOpen, $urlId);
        } else {
            $this->view->menuHtml = html_tag('div', array('class' => 'alert alert-danger float-left'), $menuData['message']);
        }

        return $this->view->renderPrint('system/link/menu/render', self::$viewPath);
    }
    
    public function topChildMenuRenderByService($isChild, $menuOpen = "") {
        $this->load->model('mdmeta', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $url = Input::get('url');
        $urlArr = explode('/', $url);
        $urlId = '';
        if (isset($urlArr[2])) {
            $urlId = $urlArr[2];
        }
        
        $menuData = $this->model->getMetaMenuListByServiceModel();

        if ($menuData['status'] == 'success') {
            $this->view->menuHtml = $this->model->topChildMenuRenderByDataModel($menuData['menuData'], 0, $isChild, $menuOpen, $urlId);
        } else {
            $this->view->menuHtml = html_tag('div', array('class' => 'alert alert-danger float-left'), $menuData['message']);
        }

        return $this->view->renderPrint('system/link/menu/render', self::$viewPath);
    }
    
    public function topMetaMenuRenderByService($isChild, $menuOpen = '') {
        $this->load->model('mdmeta', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $url = Input::get('url');
        
        $urlArr = explode('/', $url);
        $urlId = '';
        if (isset($urlArr[2])) {
            $urlId = $urlArr[2];
        }
        
        $menuData = $this->model->getMetaMenuListByServiceModel('TOP_MENU');
                
        if ($menuData['status'] == 'success') {
            $this->view->menuHtml = $this->model->topMetaMenuRenderByDataModel($menuData['menuData'], '', 0, $isChild, $menuOpen, $urlId);
        } else {
            $this->view->menuHtml = ($menuData['message'] ? html_tag('div', array('class' => 'alert alert-danger float-left'), $menuData['message']) : '');
        }

        return $this->view->renderPrint('system/link/menu/render', self::$viewPath);
    }
    
    public function topMetaLimitMenuRenderByService($isChild, $menuOpen = '') {
        $this->load->model('mdmeta', 'middleware/models/');

        $url = Input::get('url');
        
        $urlArr = explode('/', $url);
        $urlId = '';
        if (isset($urlArr[2])) {
            $urlId = $urlArr[2];
        }
        
        $menuData = $this->model->getMetaMenuListByServiceModel('TOP_MENU');
                
        if ($menuData['status'] == 'success') {
            $menuHtml = $this->model->topMetaMenuRenderByLimitDataModel($menuData['menuData'], '', 0, $isChild, $menuOpen, $urlId);
        } else {
            $menuHtml = '';
        }
        
        return $menuHtml;
    }
    
    public function sidebarMetaLimitMenuRenderByService($isChild, $menuOpen = '') {
        $this->load->model('mdmeta', 'middleware/models/');

        $url = Input::get('url');
        
        $urlArr = explode('/', $url);
        $urlId = '';
        if (isset($urlArr[2])) {
            $urlId = $urlArr[2];
        }
        
        $menuData = $this->model->getMetaMenuListByServiceModel('TOP_MENU');
                
        if ($menuData['status'] == 'success') {
            $menuHtml = $this->model->sidebarMetaMenuRenderByLimitDataModel($menuData['menuData'], '', 0, $isChild, $menuOpen, $urlId);
        } else {
            $menuHtml = '';
        }
        
        return $menuHtml;
    }
    
    public function topMetaMenuRenderByModuleId($isChild, $moduleId, $menuOpen = '') {
        $this->load->model('mdmeta', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $url = Input::get('url');
        $urlArr = explode('/', $url);
        $urlId = '';
        if (isset($urlArr[2])) {
            $urlId = $urlArr[2];
        }
        
        $menuData = $this->model->getMetaMenuListByModuleIdModel($moduleId);
        
        if ($menuData['status'] == 'success') {
            $this->view->menuHtml = $this->model->topMetaMenuRenderByDataModel($menuData['menuData'][0]['child'], $moduleId, 0, $isChild, $menuOpen, $urlId);
        } else {
            $this->view->menuHtml = html_tag('div', array('class' => 'alert alert-danger float-left'), $menuData['message']);
        }

        return $this->view->renderPrint('system/link/menu/render', self::$viewPath);
    }
    
    public function leftMetaMenuModule($isChild, $moduleId, $menuOpen = '') {
        $this->load->model('mdmeta', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $menuId = Input::get('mid');
        $moduleId = Input::param($moduleId);
        
        $menuData = $this->model->getMenuListByParentIdCacheModel($moduleId);
        
        if ($menuData['status'] == 'success' && isset($menuData['menuData'][0]['child'])) {
                
            if (Config::getFromCache('CONFIG_MULTI_TAB')) {
                if (Config::getFromCache('CONFIG_TOP_MEGA_MENU')) {
                    $this->view->menuHtml = $this->model->topMetaMegaMenuModuleByTabModel($menuData['menuData'][0]['child'], $moduleId, 0, $isChild, $menuOpen, $menuId);
                } else
                    $this->view->menuHtml = $this->model->leftMetaMenuModuleByTabModel($menuData['menuData'][0]['child'], $moduleId, 0, $isChild, $menuOpen, $menuId);
            } else {
                $this->view->menuHtml = $this->model->leftMetaMenuModuleModel($menuData['menuData'][0]['child'], $moduleId, 0, $isChild, $menuOpen, $menuId);
            }
            
        } else {
            $this->view->menuHtml = null;//html_tag('div', array('class' => 'alert alert-danger float-left'), $menuData['message']);
        }

        return $this->view->renderPrint('system/link/menu/render', self::$viewPath);
    }
    
    public function topMetaMenuModule($moduleId) {
        $this->load->model('mdmeta', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $menuId = Input::get('mid');
        $moduleId = Input::param($moduleId);
        $sideBarHtml = $openMenuId = '';
        $moduleName = 'Empty';
        $this->view->menuHtml = null;
        
        $menuData = $this->model->getMenuListByParentIdCacheModel($moduleId);
        
        if ($menuData['status'] == 'success') {
            
            $moduleInfo = $this->model->getModuleNameModel($moduleId);
            
            if ($moduleName = issetParam($moduleInfo['META_DATA_NAME'])) {

                if (isset($menuData['menuData'][0]['child'])) {
                    $this->view->menuHtml = $this->model->topMetaMenuModuleModel($menuData['menuData'][0]['child'], $moduleId, 0, $menuId, $moduleInfo);  
                } else {
                    
                    $this->view->menuHtml = '<div style="height: 30px"></div>';
                    
                    if (Config::getFromCache('is_dev')) {
                        $this->view->menuHtml = '<ul class="navbar-nav d-flex align-content-around flex-wrap" data-no-scroll="true">';
                        $this->view->menuHtml .= '<li class="nav-item"><a href="javascript:;" class="navbar-nav-link" onclick="menuMetaAddByUser(this, \''.$moduleId.'\');"><span class="title"><i class="fa fa-plus" style="color: #999"></i></span></a></li>';
                        $this->view->menuHtml .= '</ul>';
                    }
                }

                $sideBarHtml = $this->model->moduleSidebarMenuModel($moduleId);  
                
                $this->load->model('mdmenu', 'middleware/models/');
                $openMenuId = $this->model->getOpenMenuIdModel($moduleId);
            }  
        } 

        return array(
            'moduleName' => $moduleName, 
            'menuHtml' => $this->view->menuHtml, 
            'sideBarHtml' => $sideBarHtml, 
            'openMenuId' => $openMenuId
        );
    }

    public function calendarRenderByPost() {

        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->metaRow = (new Mdmetadata())->getMetaCalendarLink($this->view->metaDataId, true);

        if ($this->view->metaRow['VIEW_SIZE'] == 'meta-mini-calendar') {
            $width = 600;
            $renderPath = 'system/link/calendar/renderMini';
        } else {
            $width = 1000;
            $renderPath = 'system/link/calendar/renderBig';
        }

        $response = array(
            'Html' => $this->view->renderPrint($renderPath, self::$viewPath),
            'Width' => $width,
            'Title' => $this->view->metaRow['META_DATA_NAME'],
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public static function menuServiceAnchor($row, $moduleId = '', $menuId = '', $isTab = '', $uniqId = '', $metaDataId = '') {
        $array = array();

        if (!empty($row['weburl'])) {
            $array['linkHref'] = $row['weburl'].($moduleId !== '' ? '&mmid='.$moduleId : '').($menuId !== '' ? '&mid='.$menuId : '');
            $array['linkTarget'] = $row['urltrg'];
            $array['linkOnClick'] = '';
                
            return $array;
        }
        
        $array['backLinkTarget'] = $metaDataId;
        
        if ($row['actionmetatypeid'] == Mdmetadata::$bookmarkMetaTypeId) {            
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "metaDirectURL('" . $row['bookmarkurl'] . ($moduleId !== '' ? '&mmid='.$moduleId : '').($menuId !== '' ? '&mid='.$menuId : '')."', '" . $row['bookmarktrg'] . "');";
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$businessProcessMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "callWebServiceByMeta('" . $row['actionmetadataid'] . "', true, '', false, {callerType: '" . $row['code'] . "', isMenu: true});";
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$reportMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "callReportByMeta('" . $row['reportmodelid'] . "');";
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$dashboardMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "callDashboardByMeta('" . $row['actionmetadataid'] . "');";
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$metaGroupMetaTypeId) {
            if ($row['grouptype'] == 'dataview') {
                if ($isTab) {
                    $array['linkHref'] = 'javascript:;';
                    $array['linkTarget'] = '_self';
                    $array['linkOnClick'] = "callModelByDataView('" . $row['actionmetadataid'] . "', '". $uniqId ."',  this);";
                } else {
                    $array['linkHref'] = 'mdobject/dataview/'.$row['actionmetadataid'].($moduleId !== '' ? '?mmid='.$moduleId : '').($menuId !== '' ? '&mid='.$menuId : '');
                    $array['linkTarget'] = '_self';
                    $array['linkOnClick'] = '';
                }
            } else {
                $array['linkHref'] = 'javascript:;';
                $array['linkTarget'] = '_self';
                $array['linkOnClick'] = '';
            }
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$contentMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "callContentByMeta('" . $row['actionmetadataid'] . "');";
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$googleMapMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "callGoogleMapByMeta('" . $row['actionmetadataid'] . "');";
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$bannerMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "callBannerByMeta('" . $row['actionmetadataid'] . "');";
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$menuMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = '';
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$packageMetaTypeId) {
            $array['linkHref'] = 'mdobject/package/'.$row['actionmetadataid'].($moduleId !== '' ? '?mmid='.$moduleId : '').($menuId !== '' ? '&mid='.$menuId : '');
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = '';
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$workSpaceMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "redirectWorkSpaceByMeta('".$row['actionmetadataid']."', 'mdworkspace/index/".$row['actionmetadataid'].($moduleId !== '' ? '&mmid='.$moduleId : '').($menuId !== '' ? '&mid='.$menuId : '')."', this);";
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$statementMetaTypeId) {
            $array['linkHref'] = 'mdstatement/index/'.$row['actionmetadataid'].($moduleId !== '' ? '?mmid='.$moduleId : '').($menuId !== '' ? '&mid='.$menuId : '');
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = '';
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$layoutMetaTypeId) {
            $array['linkHref'] = 'mdlayoutrender/index/'.$row['actionmetadataid'].($moduleId !== '' ? '?mmid='.$moduleId : '').($menuId !== '' ? '&mid='.$menuId : '');
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = '';
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$pageMetaTypeId) {
            $array['linkHref'] = 'mdlayout/v2/'.$row['actionmetadataid'].($moduleId !== '' ? '?mmid='.$moduleId : '').($menuId !== '' ? '&mid='.$menuId : '');
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = '';
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$taskFlowMetaTypeId) {
            $array['linkHref'] = 'mdprocessflow/metaProcessWorkflow/'.$row['actionmetadataid'].($moduleId !== '' ? '?mmid='.$moduleId : '').($menuId !== '' ? '&mid='.$menuId : '');
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = '';
        } else {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = '';
        }

        return $array;
    }
    
    public static function menuServiceAnchorByTab($row, $moduleId = '', $menuId = '', $moduleName = '') {
        $array = array();
        $moduleName = Lang::line($moduleName);

        if ($row['weburl'] != '') {
            
            if (strtolower($row['weburl']) == 'pftranslatemenu') {
                
                $array['linkHref'] = 'javascript:;';
                $array['linkTarget'] = '_self';
                $array['linkOnClick'] = 'menuMetaTranslator(this, \''.$moduleId.'\');';
            
            } else {
                
                if ($row['urltrg'] == 'iframe') {
                    
                    $array['linkHref'] = 'javascript:;';
                    $array['linkTarget'] = '_self';
                    $array['linkOnClick'] = "appMultiTab({weburl: '".$row['weburl']."', metaDataId: '".$row['metadataid']."', title: '".Lang::line($row['name'])."', dataTitle: '".Lang::line($row['name']).' - '.$moduleName."', type: 'iframe'}, this);";
                        
                } else {
                    if (!filter_var($row['weburl'], FILTER_VALIDATE_URL) === false) {
                        $array['linkHref'] = $row['weburl'].($moduleId !== '' ? '&mmid='.$moduleId : '').($menuId !== '' ? '&mid='.$menuId : '');
                        $array['linkTarget'] = $row['urltrg'];
                        $array['linkOnClick'] = '';
                    } elseif ($row['urltrg'] == '_alwaysself' || $row['urltrg'] == '_alwaysblank') {
                        $array['linkHref'] = $row['weburl'];
                        $array['linkTarget'] = $row['urltrg'];
                        $array['linkOnClick'] = '';
                    } else {
                        $array['linkHref'] = 'javascript:;';
                        $array['linkTarget'] = '_self';
                        $array['linkOnClick'] = "appMultiTab({weburl: '".$row['weburl']."', metaDataId: '".strtolower(str_replace('/', '', $row['weburl']))."', title: '".Lang::line($row['name'])."', dataTitle: '".Lang::line($row['name'])." - ".$moduleName."', type: 'selfurl'}, this);";
                    }
                }
            }
            
            return $array;
        }

        if ($row['actionmetatypeid'] == Mdmetadata::$bookmarkMetaTypeId) {
            if (!filter_var($row['bookmarkurl'], FILTER_VALIDATE_URL) === false) {
                $array['linkHref'] = $row['bookmarkurl'].($moduleId !== '' ? '&mmid='.$moduleId : '').($menuId !== '' ? '&mid='.$menuId : '');
                $array['linkTarget'] = $row['bookmarktrg'];
                $array['linkOnClick'] = '';
            } else {
                $array['linkHref'] = 'javascript:;';
                $array['linkTarget'] = '_self';
                $array['linkOnClick'] = "appMultiTab({weburl: '".$row['bookmarkurl']."', metaDataId: '".strtolower(str_replace('/', '', $row['bookmarkurl']))."', title: '".Lang::line($row['name'])."', dataTitle: '".Lang::line($row['name'])." - ".$moduleName."', type: 'selfurl'}, this);";
            }
            
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$businessProcessMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            
            if (isset($row['islocked']) && $row['islocked'] == 'true') {
                $array['linkOnClick'] = "metaUnLockByMenu(this, '$menuId', 'process', {metaDataId: '".$row['actionmetadataid']."', callerType: '" . $row['code'] . "', isMenu: true});";
            } else {
                $array['linkOnClick'] = "callWebServiceByMeta('" . $row['actionmetadataid'] . "', true, '', false, {callerType: '" . $row['code'] . "', isMenu: true});";
            }
            
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$reportMetaTypeId) {
            
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "callReportByMeta('" . $row['reportmodelid'] . "');";
            
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$dashboardMetaTypeId) {
            
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "callDashboardByMeta('" . $row['actionmetadataid'] . "');";
            
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$metaGroupMetaTypeId) {
            
            if ($row['grouptype'] == 'dataview') {
                
                $array['linkHref'] = 'javascript:;';
                $array['linkTarget'] = '_self';
                
                if (isset($row['islocked']) && $row['islocked'] == 'true') {
                    $array['linkOnClick'] = "metaUnLockByMenu(this, '$menuId', 'dataview', {metaDataId: '".$row['actionmetadataid']."', title: '".Lang::line($row['name'])."', type: 'dataview'});";
                } else {
                    $array['linkOnClick'] = "appMultiTab({metaDataId: '".$row['actionmetadataid']."', title: '".Lang::line($row['name'])."', dataTitle: '".Lang::line($row['name'])." - ".$moduleName."', type: 'dataview', proxyId: '".issetParam($row['proxymetadataid'])."'}, this);";
                }
                
            } else {
                $array['linkHref'] = 'javascript:;';
                $array['linkTarget'] = '_self';
                $array['linkOnClick'] = '';
            }
            
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$contentMetaTypeId) {
            
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "appMultiTab({metaDataId: '".$row['actionmetadataid']."', title: '".Lang::line($row['name'])."', dataTitle: '".Lang::line($row['name'])." - ".$moduleName."', type: 'content'}, this);";
            
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$googleMapMetaTypeId) {
            
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "callGoogleMapByMeta('" . $row['actionmetadataid'] . "');";
            
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$packageMetaTypeId) {
            
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            
            if (isset($row['islocked']) && $row['islocked'] == 'true') {
                $array['linkOnClick'] = "metaUnLockByMenu(this, '$menuId', 'package', {metaDataId: '".$row['actionmetadataid']."', title: '".Lang::line($row['name'])."', type: 'package'});";
            } else {
                $array['linkOnClick'] = "appMultiTab({metaDataId: '".$row['actionmetadataid']."', title: '".Lang::line($row['name'])."', dataTitle: '".Lang::line($row['name'])." - ".$moduleName."', type: 'package'}, this);";
            }
            
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$workSpaceMetaTypeId) {
            
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            
            if (isset($row['islocked']) && $row['islocked'] == 'true') {
                $array['linkOnClick'] = "metaUnLockByMenu(this, '$menuId', 'workspace', {metaDataId: '".$row['actionmetadataid']."', title: '".Lang::line($row['name'])."', type: 'workspace'});";
            } else {
                $array['linkOnClick'] = "appMultiTab({metaDataId: '".$row['actionmetadataid']."', title: '".Lang::line($row['name'])."', dataTitle: '".Lang::line($row['name'])." - ".$moduleName."', type: 'workspace'}, this);";
            }
            
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$statementMetaTypeId) {
            
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            
            if (isset($row['islocked']) && $row['islocked'] == 'true') {
                $array['linkOnClick'] = "metaUnLockByMenu(this, '$menuId', 'statement', {metaDataId: '".$row['actionmetadataid']."', title: '".Lang::line($row['name'])."', type: 'statement'});";
            } else {
                $array['linkOnClick'] = "appMultiTab({metaDataId: '".$row['actionmetadataid']."', title: '".Lang::line($row['name'])."', dataTitle: '".Lang::line($row['name'])." - ".$moduleName."', type: 'statement'}, this);";
            }
            
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$layoutMetaTypeId) {
            
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "appMultiTab({metaDataId: '".$row['actionmetadataid']."', title: '".Lang::line($row['name'])."', dataTitle: '".Lang::line($row['name'])." - ".$moduleName."', type: 'layout'}, this);";
            
        } elseif ($row['actionmetatypeid'] == Mdmetadata::$calendarMetaTypeId) {
            
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "appMultiTab({metaDataId: '".$row['actionmetadataid']."', title: '".Lang::line($row['name'])."', dataTitle: '".Lang::line($row['name'])." - ".$moduleName."', type: 'calendar'}, this);";
            
        } elseif ($row['actionmetatypeid'] == '123456') { /*KPI Indicator*/
            
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "appMultiTab({metaDataId: '".$row['actionmetadataid']."', title: '".Lang::line($row['name'])."', dataTitle: '".Lang::line($row['name'])." - ".$moduleName."', type: 'kpi', kpitypeid: '".$row['actionkpitypeid']."'}, this);";

        } elseif ($row['actionmetatypeid'] == Mdmetadata::$taskFlowMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "appMultiTab({metaDataId: '".$row['actionmetadataid']."', title: '".Lang::line($row['name'])."', dataTitle: '".Lang::line($row['name'])." - ".$moduleName."', type: 'taskflow'}, this);";
            
        } else {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = '';
        }

        return $array;
    }
    
    public static function menuAnchor($row) {
        $array = array();

        if (!empty($row['WEB_URL'])) {            
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "metaDirectURL('" . $row['WEB_URL'] . "', '" . $row['URL_TARGET'] . "');";

            return $array;
        }

        if ($row['META_TYPE_ID'] == Mdmetadata::$bookmarkMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "metaDirectURL('" . $row['BOOKMARK_URL'] . "', '" . $row['BOOKMARK_TARGET'] . "');";
        } elseif ($row['META_TYPE_ID'] == Mdmetadata::$businessProcessMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "callWebServiceByMeta('" . $row['META_DATA_ID'] . "', true);";
        } elseif ($row['META_TYPE_ID'] == Mdmetadata::$reportMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "callReportByMeta('" . $row['REPORT_MODEL_ID'] . "');";
        } elseif ($row['META_TYPE_ID'] == Mdmetadata::$dashboardMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "callDashboardByMeta('" . $row['META_DATA_ID'] . "');";
        } elseif ($row['META_TYPE_ID'] == Mdmetadata::$metaGroupMetaTypeId) {
            if ($row['GROUP_TYPE'] == 'dataview') {
                $array['linkHref'] = 'mdobject/dataview/' . $row['META_DATA_ID'];
                $array['linkTarget'] = '_self';
                $array['linkOnClick'] = '';
            } else {
                $array['linkHref'] = 'javascript:;';
                $array['linkTarget'] = '_self';
                $array['linkOnClick'] = '';
            }
        } elseif ($row['META_TYPE_ID'] == Mdmetadata::$contentMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "callContentByMeta('" . $row['META_DATA_ID'] . "');";
        } elseif ($row['META_TYPE_ID'] == Mdmetadata::$googleMapMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "callGoogleMapByMeta('" . $row['META_DATA_ID'] . "');";
        } elseif ($row['META_TYPE_ID'] == Mdmetadata::$bannerMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "callBannerByMeta('" . $row['META_DATA_ID'] . "');";
        } elseif ($row['META_TYPE_ID'] == Mdmetadata::$menuMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = '';
        } else {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = '';
        }

        return $array;
    }

    public function donutRenderByPost() {
        $this->load->model('mdmeta', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->donut = (new Mdmetadata())->getMetaDonutLink($this->view->metaDataId);

        if ($this->view->donut['PROCESS_META_DATA_ID'] != null) {
            $_POST['methodId'] = $this->view->donut['PROCESS_META_DATA_ID'];

            $this->load->model('mdwebservice', 'middleware/models/');
            
            $postData = Input::postData();
            $metaDataId = Input::param($postData['methodId']);
            $row = $this->model->getMethodIdByMetaDataModel($metaDataId);
            $param = array();

            if (isset($postData['param'])) {
                
                $paramData = $postData['param'];
                $paramList = $this->model->groupParamsDataModel($metaDataId, null, ' AND PAL.PARENT_ID IS NULL');

                foreach ($paramList as $input) {
                    
                    $typeCode = strtolower($input['META_TYPE_CODE']);
                    
                    if ($typeCode != 'group') {

                        if ($typeCode == 'boolean') {
                            if (isset($paramData[$input['META_DATA_CODE']])) {
                                $param[$input['META_DATA_CODE']] = '1';
                            } else {
                                $param[$input['META_DATA_CODE']] = '0';
                            }
                        } else {
                            if (isset($paramData[$input['META_DATA_CODE']])) {
                                $param[$input['META_DATA_CODE']] = $this->ws->convertDeParamType($paramData[$input['META_DATA_CODE']], $typeCode);
                            } else {
                                $param[$input['META_DATA_CODE']] = $this->ws->convertDeParamType(Mdmetadata::setDefaultValue($input['DEFAULT_VALUE']), $typeCode);
                            }
                        }
                    } else {
                        if ($input['IS_SHOW'] == '1') {
                            $param[$input['META_DATA_CODE']] = (new Mdwebservice())->fromPostGenerateArray(
                                $metaDataId, $input['ID'], $input['META_DATA_CODE'], $input['RECORD_TYPE'], $paramData, 0
                            );
                        }
                    }
                }
            }

            $result = $this->ws->caller($row['SERVICE_LANGUAGE_CODE'], $row['WS_URL'], $row['META_DATA_CODE'], 'return', $param);
            
            if ($this->ws->isException()) {
                $result = array('status' => 'error', 'message' => $this->ws->getErrorMessage());
            } else {
                if (isset($result['result']['result'])) {
                    $this->view->donut['TEXT'] = $result['result']['result'];
                }
            }
        }

        $this->view->css = array(
            'custom/addon/plugins/jquery-circliful/css/jquery.circliful.css'
        );
        $this->view->js = array('custom/addon/plugins/jquery-circliful/js/jquery.circliful.js');

        $response = array(
            'Html' => $this->view->renderPrint('system/link/donut/renderDonut', self::$viewPath),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function cardRenderByPost() {
        
        $this->load->model('mdmetadata', 'middleware/models/');
        
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->uniqId = getUID();
        
        $html = 'system/link/card/renderCard';
        $this->view->card = $this->model->getMetaCardLinkModel($this->view->metaDataId);
        
        $count = '0';
        if ($this->view->card) {
            
            if ($this->view->card['PROCESS_META_DATA_ID'] != null) {
                
                $_POST['methodId'] = $this->view->card['PROCESS_META_DATA_ID'];
                
                $this->load->model('mdwebservice', 'middleware/models/');
                
                $postData = Input::postData();
                $metaDataId = Input::param($postData['methodId']);
                
                $row = $this->model->getMethodIdByMetaDataModel($metaDataId);
                
                if (isset($row['SERVICE_LANGUAGE_CODE']) && $row['WS_URL'] && $row['META_DATA_CODE']) {
                    
                    $param = array();
                    $postData['param'] = array();
                    
                    if (isset($postData['param'])) {
                        
                        $paramData = $postData['param'];
                        $paramList = $this->model->groupParamsDataModel($metaDataId, null, ' AND PAL.PARENT_ID IS NULL');

                        foreach ($paramList as $input) {
                            
                            $typeCode = strtolower($input['META_TYPE_CODE']);
                            
                            if ($typeCode != 'group') {

                                if ($typeCode == 'boolean') {
                                    if (isset($paramData[$input['META_DATA_CODE']])) {
                                        $param[$input['META_DATA_CODE']] = '1';
                                    } else {
                                        $param[$input['META_DATA_CODE']] = '0';
                                    }
                                } else {
                                    if (isset($paramData[$input['META_DATA_CODE']])) {
                                        $param[$input['META_DATA_CODE']] = $this->ws->convertDeParamType($paramData[$input['PARAM_NAME']], $typeCode);
                                    } else {
                                        $param[$input['META_DATA_CODE']] = $this->ws->convertDeParamType(Mdmetadata::setDefaultValue($input['DEFAULT_VALUE']), $typeCode);
                                    }
                                }
                                
                            } elseif ($input['IS_SHOW'] == '1') {
                                $param[$input['META_DATA_CODE']] = (new Mdwebservice())->fromPostGenerateArray($metaDataId, $input['ID'], $input['META_DATA_CODE'], $input['RECORD_TYPE'], $paramData, 0);
                            }
                        }
                    }
                    
                    $result = $this->ws->caller($row['SERVICE_LANGUAGE_CODE'], $row['WS_URL'], $row['META_DATA_CODE'], 'return', $param);

                    if ($this->ws->isException()) {
                        $result = array('status' => 'error', 'message' => $this->ws->getErrorMessage());
                    } elseif (isset($result['result'])) {
                        foreach ($result['result'] AS $k => $v) {
                            $this->view->card['TEXT_FROM_SERVICE'] = $v;
                        }
                    }
                }
            }
            
            if ($this->view->card['IS_SEE'] == '1' && $this->view->card['VIEW_NAME'] != null) {
                
                try {
                    $count = $this->db->GetOne("SELECT COUNT(*) FROM ".$this->view->card['VIEW_NAME']);
                } catch (Exception $ex) {}
            }
            
            $this->view->card['ROW_COUNT'] = $count;    
            
            if ($this->view->card['CHART_DATA_VIEW_ID']) {
                $html = 'system/link/card/renderCard_v1';
            }
        }
        
        $this->view->fullUrlCss = array('middleware/assets/css/card/card.css');
        
        $response = array(
            'Html' => $this->view->renderPrint($html, self::$viewPath),
            'Title' => isset($this->view->card['META_DATA_NAME']) ? $this->view->card['META_DATA_NAME'] : '',
            'Width' => 400,
            'isSee' => isset($this->view->card['IS_SEE']) ? $this->view->card['IS_SEE'] : '',
            'count' => $count,
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    public function diagramRenderByPost() {

        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->diagram = (new Mdmetadata())->getMetaDiagramLink($this->view->metaDataId);
        if ($this->view->diagram['PROCESS_META_DATA_ID'] != null) {
            $_POST['methodId'] = $this->view->diagram['PROCESS_META_DATA_ID'];

            $this->load->model('mdwebservice', 'middleware/models/');
            
            $postData = Input::postData();
            $metaDataId = Input::param($postData['methodId']);
            $row = $this->model->getMethodIdByMetaDataModel($metaDataId);
            $param = array();

            if (isset($postData['param'])) {
                
                $paramData = $postData['param'];
                $paramList = $this->model->groupParamsDataModel($metaDataId, null, ' AND PAL.PARENT_ID IS NULL');

                foreach ($paramList as $input) {
                    
                    $typeCode = strtolower($input['META_TYPE_CODE']);
                    
                    if ($typeCode != 'group') {

                        if ($typeCode == 'boolean') {
                            if (isset($paramData[$input['META_DATA_CODE']])) {
                                $param[$input['META_DATA_CODE']] = '1';
                            } else {
                                $param[$input['META_DATA_CODE']] = '0';
                            }
                        } else {
                            if (isset($paramData[$input['META_DATA_CODE']])) {
                                $param[$input['META_DATA_CODE']] = $this->ws->convertDeParamType($paramData[$input['META_DATA_CODE']], $typeCode);
                            } else {
                                $param[$input['META_DATA_CODE']] = $this->ws->convertDeParamType(Mdmetadata::setDefaultValue($input['DEFAULT_VALUE']), $typeCode);
                            }
                        }
                    } else {
                        if ($input['IS_SHOW'] == '1') {
                            $param[$input['META_DATA_CODE']] = (new Mdwebservice())->fromPostGenerateArray(
                                $metaDataId, $input['ID'], $input['META_DATA_CODE'], $input['RECORD_TYPE'], $paramData, 0
                            );
                        }
                    }
                }
            }

            $result = $this->ws->caller($row['SERVICE_LANGUAGE_CODE'], $row['WS_URL'], $row['META_DATA_CODE'], 'return', $param);
            
            if ($this->ws->isException()) {
                $result = array('status' => 'error', 'message' => $this->ws->getErrorMessage());
            } else {
                if (isset($result['result']['result'])) {
                    $this->view->diagram['TEXT'] = $result['result']['result'];
                }
            }
        }

        $response = array(
            'Html' => $this->view->renderPrint('system/link/diagram/renderDiagram', self::$viewPath),
            'Title' => $this->view->diagram['META_DATA_NAME'],
            'width' => $this->view->diagram['WIDTH'],
            'height' => $this->view->diagram['HEIGHT'],
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function getColumnDiagramData() {
        $categoryList = array();
        $series = array();
        $dataArray = array();
        $check = false;
        $metaDataId = Input::numeric('metaDataId');
        $chartValues = (new Mdmetadata())->getMetaDiagramLink($metaDataId);
        $data = $chartValues['TEXT'];
        $chartType = $chartValues['DIAGRAM_TYPE'];
        $width = $chartValues['WIDTH'];
        $height = $chartValues['HEIGHT'];
        $chartTitle = $chartValues['TITLE'];
        $isTitle = $chartValues['IS_SHOW_TITLE'];
        $isExport = $chartValues['IS_SHOW_EXPORT'];
        $isLegend = $chartValues['IS_SHOW_LABEL'];
        if ($chartValues['PROCESS_META_DATA_ID'] == null) { // Хэрэв процесс эсвэл Dynamic view тохируулаагүй бол
            $data = simplexml_load_string($data);
            $phpArray = $this->ws->wsObjectToArray($data, 'request');
            $phpArray = $phpArray['elements']['4']['elements'];
            $getCategory = $phpArray['elements'];
            foreach ($getCategory as $row) {
                array_push($categoryList, $row['elements'][0]['value']);
                foreach ($row['elements'][1]['elements'] as $rowData) {
                    $dataArray = array('name' => $rowData['elements']['0']['value'], 'data' => $rowData['elements']['1']['value']);

                    for ($i = 0, $count = count($series); $i < $count; $i++) {
                        if ($series[$i]['name'] === $rowData['elements']['0']['value']) {
                            //                  $series[$i]['data'] = $rowData['elements']['1']['value'];
                            if (is_array($series[$i]['data'])) {
                                array_push($series[$i]['data'], $rowData['elements']['1']['value']);
                            }
                            $check = true;
                        }
                    }
                    if (!$check) {
                        array_push($series, $dataArray);
                    }
                }
            }
        } else {// Хэрэв процесс эсвэл Dynamic view тохируулсан бол веб сервис дуудана      
            // параметр цуглуулах        
            $param = array(
                'systemMetaGroupId' => $chartValues['PROCESS_META_DATA_ID'], //$chartValues['PROCESS_META_DATA_ID'], 
                'showQuery' => 0,
                'paging' => array(
                    'offset' => 1,
                    'pageSize' => 10
                )
            );

            // вебсервис дуудах
            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

            if ($data['status'] == 'success') {
                if (isset($data['result'])) {
                    unset($data['result']['paging']); // хуудаслалтыг хасаж байна                
                    unset($data['result']['aggregatecolumns']); // шүүлтийг хасаж байна                
                    foreach ($data['result'] AS $row) {
                        $counter = 0;
                        foreach ($row AS $k => $v) {
                            if ($counter == 0) {
                                $dataArray['data'] = $v;
                            } else if ($counter == 1) {
                                $dataArray['name'] = $v;
                            }

                            $counter++;
                        }
                        array_push($series, $dataArray);
                    }
                }
            } else {
                $data = null;
            }

            $categoryList = array($chartTitle);
        }
        $mainArray = array('chartType' => $chartType, 'isTitle' => $isTitle, 'title' => $chartTitle, 'width' => $width, 'height' => $height,
            'categories' => $categoryList, 'series' => $series, 'isLegend' => $isLegend, 'isExport' => $isExport);
        echo json_encode($mainArray, JSON_NUMERIC_CHECK);
    }

    public function getPieDiagramData() {
        $categoryList = array();
        $seriesData = array();
        $series = array();

        $metaDataId = Input::numeric('metaDataId');
        $chartValues = (new Mdmetadata())->getMetaDiagramLink($metaDataId);
        if ($chartValues['PROCESS_META_DATA_ID'] == null) { // Хэрэв процесс эсвэл Dynamic view тохируулаагүй бол
            $data = $chartValues['TEXT'];
            $data = simplexml_load_string($data);
            $phpArray = $this->ws->wsObjectToArray($data, 'request');
            $phpArray = $phpArray['elements']['4']['elements']; // CLEARED
            $title = $phpArray['elements']['0']['value'];
            $data = $phpArray['elements']['1']['elements'];

            foreach ($data AS $row) {
                $dataArray = array('name' => $row['elements']['0']['value'], 'y' => $row['elements']['1']['value']);
                array_push($series, $dataArray);
            }
        } else {// Хэрэв процесс эсвэл Dynamic view тохируулсан бол веб сервис дуудана      
            // параметр цуглуулах        
            $param = array(
                'systemMetaGroupId' => $chartValues['PROCESS_META_DATA_ID'], //$chartValues['PROCESS_META_DATA_ID'], 
                'showQuery' => 0,
                'paging' => array(
                    'offset' => 1,
                    'pageSize' => 10
                )
            );

            // вебсервис дуудах
            $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);
            if ($data['status'] == 'success') {
                if (isset($data['result'])) {
                    unset($data['result']['paging']); // хуудаслалтыг хасаж байна                
                    unset($data['result']['aggregatecolumns']); // шүүлтийг хасаж байна                
                    foreach ($data['result'] AS $row) {
                        $counter = 0;
                        foreach ($row AS $k => $v) {
                            if ($counter == 0) {
                                $dataArray['y'] = $v;
                            } else if ($counter == 1) {
                                $dataArray['name'] = $v;
                            }

                            $counter++;
                        }
                        array_push($series, $dataArray);
                    }
                }
            } else {
                $data = null;
            }

            $title = $chartValues['PROCESS_META_DATA_ID'];
        }

        $chartType = $chartValues['DIAGRAM_TYPE'];
        $width = $chartValues['WIDTH'];
        $height = $chartValues['HEIGHT'];
        $chartTitle = $chartValues['TITLE'];
        $isTitle = $chartValues['IS_SHOW_TITLE'];
        $isExport = $chartValues['IS_SHOW_EXPORT'];
        $isLegend = $chartValues['IS_SHOW_LABEL'];

        array_push($categoryList, $title);

        $mainArray = array('chartType' => $chartType, 'isTitle' => $isTitle, 'title' => $chartTitle, 'width' => $width, 'height' => $height,
            'categories' => $categoryList, 'series' => $series, 'isLegend' => $isLegend, 'isExport' => $isExport);
        echo json_encode($mainArray, JSON_NUMERIC_CHECK);
    }
    
    public function metaImport() {

        $this->view->importType = strtolower(Input::post('importType'));
        $this->view->rowId = Input::post('rowId');

        $title = 'Folder import';
        $status = 'success';
        $importBtn = $this->lang->line('META_00087');

        if ($this->view->importType === 'meta' || $this->view->importType === 'metas') {
            
            $title = 'Meta import';
            $html = $this->view->renderPrint('common/import/import', self::$viewPath);
            
        } elseif ($this->view->importType === 'foldermeta') {
            
            $title = 'Folder & Meta import';
            $html = $this->view->renderPrint('common/import/import', self::$viewPath);
            
        } elseif ($this->view->importType === 'upgrade') {
            
            $title = $importBtn = 'Шинэчлэх';
            
            $html = $this->view->renderPrint('common/import/upgrade', self::$viewPath);
            
        } else {
            $html = $this->view->renderPrint('common/import/import', self::$viewPath);
        }

        $response = array(
            'Html' => $html,
            'Title' => $title,
            'status' => $status,
            'import_btn' => $importBtn,
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function metaImportFile() {
        $result = $this->model->metaImportFileModel();
        echo json_encode($result); exit;
    }
    
    public function pfObjectExport() {
        
        $exportData = $this->model->getPfObjectExportModel();
        
        if ($exportData['status'] === 'success') {
                
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=true; path=/');
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename="'.$exportData['object'].'-'.date('YmdHis').'.txt"');
            header('Content-Type: application/force-download');
            header('Content-Type: application/octet-stream');
            header('Content-Type: application/download');
            header('Content-Transfer-Encoding: binary');

            echo $exportData['result'];
        }
        exit;
    }

    public function setExpressionCriteria() {

        $this->view->params = Input::postData();
        $this->view->lookupMetaDataId = '';
        $this->view->lookupMetaDataCode = '';
        $this->view->lookupMetaDataName = '';
        $this->view->lookupMetaDataParamMetaData = '';

        $this->view->processMetaDataId = '';
        $this->view->processMetaDataCode = '';
        $this->view->processMetaDataName = '';
        $this->view->processMetaDataParamMetaData = '';
        $this->view->processMetaDataParamOutput = '';

        if (isset($this->view->params['lookupMetaDataId']) && !empty($this->view->params['lookupMetaDataId'])) {
            
            $this->load->model('mdmetadata', 'middleware/models/');
            $row = $this->model->getMetaDataModel($this->view->params['lookupMetaDataId']);
            
            $this->view->lookupMetaDataId = $row['META_DATA_ID'];
            $this->view->lookupMetaDataCode = $row['META_DATA_CODE'];
            $this->view->lookupMetaDataName = $row['META_DATA_NAME'];

            $this->load->model('mdmeta', 'middleware/models/');
            $this->view->lookupMetaDataParamMetaData = $this->model->getMetaGroupParamMetaModel($this->view->lookupMetaDataId);
        }
        
        parse_str($this->view->params['paramConfig'], $this->view->paramsConfigs);

        if (isset($this->view->params['processMetaDataId']) && !empty($this->view->params['processMetaDataId'])) {
            
            $this->load->model('mdmetadata', 'middleware/models/');
            $row = $this->model->getMetaDataModel($this->view->params['processMetaDataId']);
            $rowPath = $this->model->getMetaDataModel($this->view->params['processMetaDataId']);
            
            $this->view->processMetaDataId = $row['META_DATA_ID'];
            $this->view->processMetaDataCode = $row['META_DATA_CODE'];
            $this->view->processMetaDataName = $row['META_DATA_NAME'];
            
            $this->view->processMetaDataIdPath = $rowPath['META_DATA_ID'];
            $this->view->processMetaDataCodePath = $rowPath['META_DATA_CODE'];
            $this->view->processMetaDataNamePath = $rowPath['META_DATA_NAME'];

            $this->load->model('mdmeta', 'middleware/models/');
            $this->view->processMetaDataParamMetaData = $this->model->getMetaProcessParamMetaModel($this->view->processMetaDataId);
        }
        
        parse_str($this->view->params['processParamConfig'], $this->view->processParamConfigs);

        $response = array(
            'Html' => $this->view->renderPrint('system/link/group/setExpressionCriteria', self::$viewPath),
            'Title' => 'Set expression criteria',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function setProcessExpressionCriteria() {

        $this->view->params = Input::postData();
        $this->view->lookupMetaDataId = '';
        $this->view->lookupMetaDataCode = '';
        $this->view->lookupMetaDataName = '';
        $this->view->lookupMetaDataParamMetaData = '';

        $this->view->processMetaDataId = '';
        $this->view->processMetaDataCode = '';
        $this->view->processMetaDataName = '';
        $this->view->processMetaDataIdPath = '';
        $this->view->processMetaDataCodePath = '';
        $this->view->processMetaDataNamePath = '';
        $this->view->processMetaDataParamMetaData = '';
        $this->view->processMetaDataParamOutput = '';

        if (isset($this->view->params['lookupMetaDataId']) && !empty($this->view->params['lookupMetaDataId'])) {
            
            $this->load->model('mdmetadata', 'middleware/models/');
            $row = $this->model->getMetaDataModel($this->view->params['lookupMetaDataId']);
            
            $this->view->lookupMetaDataId = $row['META_DATA_ID'];
            $this->view->lookupMetaDataCode = $row['META_DATA_CODE'];
            $this->view->lookupMetaDataName = $row['META_DATA_NAME'];

            $this->load->model('mdmeta', 'middleware/models/');
            $this->view->lookupMetaDataParamMetaData = $this->model->getMetaGroupParamMetaModel($this->view->lookupMetaDataId);
        }
        
        parse_str($this->view->params['paramConfig'], $this->view->paramsConfigs);

        if (isset($this->view->params['processMetaDataId']) && !empty($this->view->params['processMetaDataId'])) {
            
            $this->load->model('mdmetadata', 'middleware/models/');
            $row = $this->model->getMetaDataModel($this->view->params['processMetaDataId']);
            $rowPath = $this->model->getMetaDataModel($this->view->params['processMetaDataId']);
            
            $this->view->processMetaDataId = $row['META_DATA_ID'];
            $this->view->processMetaDataCode = $row['META_DATA_CODE'];
            $this->view->processMetaDataName = $row['META_DATA_NAME'];
            
            $this->view->processMetaDataIdPath = $rowPath['META_DATA_ID'];
            $this->view->processMetaDataCodePath = $rowPath['META_DATA_CODE'];
            $this->view->processMetaDataNamePath = $rowPath['META_DATA_NAME'];

            $this->load->model('mdmeta', 'middleware/models/');
            $this->view->processMetaDataParamMetaData = $this->model->getMetaProcessParamMetaModel($this->view->processMetaDataId);
        }

        if (isset($this->view->params['processMetaDataIdPath']) && !empty($this->view->params['processMetaDataIdPath'])) {
            
            $this->load->model('mdmetadata', 'middleware/models/');
            $rowPath = $this->model->getMetaDataModel($this->view->params['processMetaDataIdPath']);
            
            $this->view->processMetaDataIdPath = $rowPath['META_DATA_ID'];
            $this->view->processMetaDataCodePath = $rowPath['META_DATA_CODE'];
            $this->view->processMetaDataNamePath = $rowPath['META_DATA_NAME'];
        }
        
        parse_str($this->view->params['processParamConfig'], $this->view->processParamConfigs);

        $response = array(
            'Html' => $this->view->renderPrint('system/link/process/setProcessExpressionCriteria', self::$viewPath),
            'Title' => 'Set expression criteria',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function setExpressionCriteriaGroup() {

        $this->view->params = Input::postData();
        $this->view->lookupMetaDataId = '';
        $this->view->lookupMetaDataCode = '';
        $this->view->lookupMetaDataName = '';
        $this->view->lookupMetaDataParamMetaData = '';
        
        $this->view->lookupMetaDataIdKey = '';
        $this->view->lookupMetaDataCodeKey = '';
        $this->view->lookupMetaDataNameKey = '';
        $this->view->lookupMetaDataParamMetaDataKey = '';

        $this->view->processMetaDataId = '';
        $this->view->processMetaDataCode = '';
        $this->view->processMetaDataName = '';
        $this->view->processMetaDataParamMetaData = '';
        $this->view->processMetaDataParamOutput = '';

        if (isset($this->view->params['lookupMetaDataId']) && !empty($this->view->params['lookupMetaDataId'])) {
            
            $this->load->model('mdmetadata', 'middleware/models/');
            $row = $this->model->getMetaDataModel($this->view->params['lookupMetaDataId']);
            
            $this->view->lookupMetaDataId = $row['META_DATA_ID'];
            $this->view->lookupMetaDataCode = $row['META_DATA_CODE'];
            $this->view->lookupMetaDataName = $row['META_DATA_NAME'];

            $this->load->model('mdmeta', 'middleware/models/');

            $this->view->lookupMetaDataParamMetaData = $this->model->getMetaGroupParamMetaModel($this->view->lookupMetaDataId);
        }
        parse_str($this->view->params['paramConfig'], $this->view->paramsConfigs);
        
        if (isset($this->view->params['lookupMetaDataIdKey']) && !empty($this->view->params['lookupMetaDataIdKey'])) {
            
            $this->load->model('mdmetadata', 'middleware/models/');
            $row = $this->model->getMetaDataModel($this->view->params['lookupMetaDataIdKey']);
            
            $this->view->lookupMetaDataIdKey = $row['META_DATA_ID'];
            $this->view->lookupMetaDataCodeKey = $row['META_DATA_CODE'];
            $this->view->lookupMetaDataNameKey = $row['META_DATA_NAME'];

            $this->load->model('mdmeta', 'middleware/models/');

            $this->view->lookupMetaDataParamMetaDataKey = $this->model->getMetaGroupParamMetaModel($this->view->lookupMetaDataIdKey);
        }

        $response = array(
            'Html' => $this->view->renderPrint('system/link/group/setExpressionCriteriaGroup', self::$viewPath),
            'Title' => 'Set expression criteria group',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function setProcessExpressionCriteriaGroup() {

        $this->view->params = Input::postData();
        $this->view->lookupMetaDataId = '';
        $this->view->lookupMetaDataCode = '';
        $this->view->lookupMetaDataName = '';
        $this->view->lookupMetaDataParamMetaData = '';
        
        $this->view->lookupMetaDataIdKey = '';
        $this->view->lookupMetaDataCodeKey = '';
        $this->view->lookupMetaDataNameKey = '';
        $this->view->lookupMetaDataParamMetaDataKey = '';

        $this->view->processMetaDataId = '';
        $this->view->processMetaDataCode = '';
        $this->view->processMetaDataName = '';
        $this->view->processMetaDataParamMetaData = '';
        $this->view->processMetaDataParamOutput = '';

        if (isset($this->view->params['lookupMetaDataId']) && !empty($this->view->params['lookupMetaDataId'])) {
            
            $this->load->model('mdmetadata', 'middleware/models/');
            $row = $this->model->getMetaDataModel($this->view->params['lookupMetaDataId']);
            
            $this->view->lookupMetaDataId = $row['META_DATA_ID'];
            $this->view->lookupMetaDataCode = $row['META_DATA_CODE'];
            $this->view->lookupMetaDataName = $row['META_DATA_NAME'];

            $this->load->model('mdmeta', 'middleware/models/');

            $this->view->lookupMetaDataParamMetaData = $this->model->getMetaGroupParamMetaModel($this->view->lookupMetaDataId);
        }
        
        if (isset($this->view->params['lookupMetaDataIdKey']) && !empty($this->view->params['lookupMetaDataIdKey'])) {
            
            $this->load->model('mdmetadata', 'middleware/models/');
            $row = $this->model->getMetaDataModel($this->view->params['lookupMetaDataIdKey']);
            
            $this->view->lookupMetaDataIdKey = $row['META_DATA_ID'];
            $this->view->lookupMetaDataCodeKey = $row['META_DATA_CODE'];
            $this->view->lookupMetaDataNameKey = $row['META_DATA_NAME'];

            $this->load->model('mdmeta', 'middleware/models/');

            $this->view->lookupMetaDataParamMetaDataKey = $this->model->getMetaGroupParamMetaModel($this->view->lookupMetaDataIdKey);
        }
        
        parse_str($this->view->params['paramConfig'], $this->view->paramsConfigs);

        $response = array(
            'Html' => $this->view->renderPrint('system/link/process/setProcessExpressionCriteriaGroup', self::$viewPath),
            'Title' => 'Set expression criteria group',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function setGroupAnalysisCriteria() {

        $this->view->params = Input::postData();

        $response = array(
            'html' => $this->view->renderPrint('system/link/group/setGroupAnalysisCriteria', self::$viewPath)
        );
        echo json_encode($response); exit;
    }

    public function getMetaProcessParamMeta($metaDataId) {
        $this->load->model('mdmeta', 'middleware/models/');
        $data = $this->model->getMetaProcessParamMetaModel($metaDataId);
        return $data;
    }

    public function getMetaProcessParamMetaByPostJson() {
        $metaDataId = Input::post('processMetaDataId');
        $data = $this->model->getMetaProcessParamMetaModel($metaDataId);
        echo json_encode($data); exit;
    }

    public function setColumnRelation() {

        $this->view->params = Input::postData();
        $this->view->metaDataId = $this->view->params['metaDataId'];
        parse_str($this->view->params['groupRelation'], $this->view->paramsConfigs);

        $response = array(
            'Html' => $this->view->renderPrint('system/link/group/setColumnRelation', self::$viewPath),
            'Title' => 'Set Relation Config',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function setParamRelation() {

        $this->view->params = Input::postData();
        $this->view->paramId = $this->view->params['paramId'];
        $this->view->metaDataId = $this->view->params['metaDataId'];
        $this->view->joinType = $this->view->params['joinType'];
        $this->view->refParamName = $this->view->params['refParamName'];
        $this->view->refStructureData = $this->model->getStructureChildMetasModel($this->view->params['refStructureId']);
        parse_str($this->view->params['paramRelation'], $this->view->paramsConfigs);
        parse_str($this->view->params['allMetas'], $this->view->allMetas);

        $response = array(
            'Html' => $this->view->renderPrint('system/link/group/setParamRelation', self::$viewPath),
            'Title' => 'Set Relation Config',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function formatterValue($typeCode, $value) {
        $typeCode = strtolower($typeCode);

        switch ($typeCode) {
            case 'bigdecimal':
            case 'decimal':
                return Number::formatMoney($value, true);
                break;
            case 'date':
                return Date::formatter($value, 'Y-m-d');
                break;
            case 'datetime':
                return Date::formatter($value, 'Y-m-d H:i');
                break;
            case 'boolean':
                return Info::showYesNoByNumber($value);
                break;
            default:
                return $value;
        }
    }

    public function getDMTransferProcess($mainMetaDataId, $processMetaDataId, $basket = false) {
        $this->load->model('mdmeta', 'middleware/models/');
        return $this->model->getDMTransferProcessModel($mainMetaDataId, $processMetaDataId, $basket);
    }

    public function processDtlTransferProcess() {

        $this->view->params = Input::postData();
        parse_str($this->view->params['transferProcessData'], $this->view->paramsConfigs);
        parse_str($this->view->params['transferBasketProcessData'], $this->view->paramsBasketConfigs);
        
        $this->view->groupChildDatas = $this->model->getGroupChildMetasNotGroupType($this->view->params['metaDataId']);
        $this->view->defaultProcessInputParam = self::getMetaProcessParamMeta($this->view->params['processMetaDataId']);

        $response = array(
            'Html' => $this->view->renderPrint('system/link/group/processDtlTransferProcess', self::$viewPath),
            'Title' => 'Set Transfer Config',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function processDtlTransferAutoMap() {

        $this->view->params = Input::postData();
        parse_str($this->view->params['transferProcessData'], $this->view->paramsConfigs);
        $this->view->groupChildDatas = $this->model->getGroupChildMetasNotGroupType($this->view->params['metaDataId']);

        $response = array(
            'Html' => $this->view->renderPrint('system/link/group/processDtlTransferAutoMap', self::$viewPath),
            'Title' => 'Set Auto Map Config',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function processDtlCriteriaProcess() {

        $this->view->groupBpDtlCriteria = $_POST['groupBpDtlCriteria'];
        $this->view->groupBpDtlAdvancedCriteria = $_POST['groupBpDtlAdvancedCriteria'];
        $this->view->groupBpDtlConfirmMsg = $_POST['groupBpDtlConfirmMsg'];
        
        $response = array(
            'Html' => $this->view->renderPrint('system/link/group/processDtlCriteriaProcess', self::$viewPath),
            'Title' => 'Set Criteria Config',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function processDtlRequestParam() {

        $this->view->groupProcessDtlPostParam = $_POST['groupProcessDtlPostParam'];
        $this->view->groupProcessDtlGetParam = $_POST['groupProcessDtlGetParam'];
        $this->view->groupProcessDtlPasswordPath = $_POST['groupProcessDtlPasswordPath'];
        $this->view->groupProcessDtlOpenBP = $_POST['groupProcessDtlOpenBP'];
        $this->view->groupProcessDtlOpenBPdefault = $_POST['groupProcessDtlOpenBPdefault'];
        $this->view->groupProcessDtlIconColor = $_POST['groupProcessDtlIconColor'];
        $this->view->groupProcessShowPosition = $_POST['groupProcessShowPosition'];
        $this->view->groupProcessDtlIsShowRowSelect = $_POST['groupProcessDtlIsShowRowSelect'];
        $this->view->groupProcessDtlIsContextMenu = $_POST['groupProcessDtlIsContextMenu'];
        $this->view->groupProcessDtlIsRunLoop = $_POST['groupProcessDtlIsRunLoop'];
        $this->view->groupProcessDtlUseProcessToolbar = $_POST['groupProcessDtlUseProcessToolbar'];
        $this->view->groupProcessDtlProcessToolbar = $_POST['groupProcessDtlProcessToolbar'];
        
        $response = array(
            'Html' => $this->view->renderPrint('system/link/group/processDtlRequestParam', self::$viewPath),
            'Title' => 'Set Param Config',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function setColumnAttributes() {

        $this->view->params = Input::postData();

        $response = array(
            'Html' => $this->view->renderPrint('system/link/group/setColumnAttributes', self::$viewPath),
            'Title' => 'Set column attributes',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function setProcessColumnAttributes() {

        $this->view->params = Input::postData();

        $response = array(
            'Html' => $this->view->renderPrint('system/link/process/setProcessColumnAttributes', self::$viewPath),
            'Title' => 'Set column attributes',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function copyMetaData() {
        
        $this->load->model('mdmetadata', 'middleware/models/');
        
        $metaDataId = Input::numeric('metaDataId');
        $folderId = Input::numeric('folderId');
        
        $this->view->row = $this->model->getMetaDataModel($metaDataId);
        
        if ($this->view->row['META_TYPE_ID'] == Mdmetadata::$businessProcessMetaTypeId) {
            $bpRow = $this->model->getMetaBusinessProcessLinkModel($metaDataId);
            $sessionUserId = Ue::sessionUserId();
            if ($bpRow['METHOD_NAME'] && in_array(strtolower($bpRow['METHOD_NAME']), Mdmetadata::$ignoreMethodNames) && $sessionUserId != '144617860666271' && $sessionUserId != '1453998999913' && $sessionUserId != '1479354351113') {
                jsonResponse(array('status' => 'error', 'message' => 'Хуулах боломжгүй процесс байна! Та бүтээгдэхүүн хөгжүүлэлтийн хэлтэсийн захиралд хандана уу.'));
            }
        }
        
        $this->view->folderRow = $this->model->getFolderRowModel($folderId);

        $response = array(
            'html' => $this->view->renderPrint('system/part/copyMetaData', self::$viewPath),
            'title' => 'Үзүүлэлт хуулах',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function saveCopyMetaData() {
        $result = $this->model->saveCopyMetaDataModel();
        echo json_encode($result); exit;
    }

    public function tableStructure() {

        $metaDataId = Input::numeric('metaDataId');
        $this->view->row = $this->model->getMetaGroupLinkRowModel($metaDataId);
        $this->view->metaList = $this->model->getMetaGroupChildMetasModel($metaDataId);

        $response = array(
            'html' => $this->view->renderPrint('system/link/group/tableStructure', self::$viewPath),
            'title' => $this->lang->line('META_00040'),
            'save_btn' => 'Үүсгэх',
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function createTableStructure() {
        $this->load->model('mdmeta', 'middleware/models/');
        $result = $this->model->createTableStructureModel();
        echo json_encode($result); exit;
    }

    public function dataViewSql() {

        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->dataViewSql = $this->model->dataViewSqlModel($this->view->metaDataId);
        
        includeLib('Formatter/SqlFormatter');
        
        $response = array(
            'html' => $this->view->renderPrint('system/link/group/dataViewSql', self::$viewPath),
            'title' => 'SQL харах',
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function groupPathView() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $dataViewPath = $this->model->getGroupPathModel($this->view->metaDataId);
        
        if ($dataViewPath['status'] == 'error') {
            echo json_encode($dataViewPath);
        } else {
            $data = $dataViewPath['data']['default'];
            $this->view->metaDataName = $data['metadataname'];
            $this->view->path = $data['folderid'];
            $this->view->inResult = null;
            $this->view->outResult = null;

            if (isset($data['inputmetadataid'])) {
                $this->view->inResult = $this->model->drawInputOutputPathModel($data['inputmetadataid'], 'Оролтын групп');
            }
            if (isset($data['outputmetadataid'])) {
                $this->view->outResult = $this->model->drawInputOutputPathModel($data['outputmetadataid'], 'Гаралтын групп');
            }

            $this->view->result = null;
            if (isset($data['childs'])) {
                $this->view->result = $this->model->drawGroupPathModel($data['childs']);
            }
            $response = array(
                'html' => $this->view->renderPrint('system/link/group/groupPathView', self::$viewPath),
                'title' => 'Path харах',
                'close_btn' => $this->lang->line('close_btn')
            );
            echo json_encode($response);
        }
        exit;
    }

    public function groupCreate() {

        $entityList = $this->model->entityListModel();

        if ($entityList['status'] === 'error') {
            echo json_encode($entityList);
        } else {
            
            $this->view->folderId = Input::post('folderId');
            
            $this->load->model('mdmetadata', 'middleware/models/');
            $this->view->folderRow = $this->model->getFolderRowModel($this->view->folderId);
            
            $this->view->entityList = $entityList['data']['default'];
            
            $response = array(
                'html' => $this->view->renderPrint('system/link/group/groupCreate', self::$viewPath),
                'title' => $this->lang->line('metadata_create_group'),
                'save_btn' => $this->lang->line('save_btn'),
                'close_btn' => $this->lang->line('close_btn')
            );
            echo json_encode($response);
        }
        exit;
    }

    public function generateEntityToGroup() {
        $result = $this->model->generateEntityToGroupModel();
        echo json_encode($result); exit;
    }

    public function refreshStructure() {
        $result = $this->model->refreshStructureModel(Input::numeric('metaDataId'));
        echo json_encode($result); exit;
    }

    public function structureCreate() {

        $this->view->folderId = Input::post('folderId');
        $tablesList = $this->model->tablesListModel();
        
        if ($tablesList['status'] === 'error') {
            echo json_encode($tablesList);
        } else {
            $this->view->tablesList = $tablesList['data']['default'];

            $response = array(
                'html' => $this->view->renderPrint('system/link/group/structureCreate', self::$viewPath),
                'title' => $this->lang->line('META_00136'),
                'save_btn' => $this->lang->line('save_btn'),
                'close_btn' => $this->lang->line('close_btn')
            );
            echo json_encode($response);
        }
        exit;
    }

    public function generateTableToStructure() {
        $result = $this->model->generateTableToStructureModel();
        echo json_encode($result); exit;
    }
    
    public function initRepMetaGroupData() {
        $this->load->model('mdmetadata', 'middleware/models/');
        
        if (Input::post('groupType') === 'dataview') {
            echo json_encode($this->model->getSystemAllViewsModel());
        } else {
            echo json_encode($this->model->getSystemAllTablesModel());
        }
        exit;
    }

    public function lcWorkflowConfig() {

        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->metaDataCode = Input::post('metaDataCode');
        $this->view->metaDataName = Input::post('metaDataName');

        $response = array(
            'html' => $this->view->renderPrint('system/link/group/lcWorkflowConfig', self::$viewPath),
            'title' => 'Workflow status',
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function addWorkflowStatusForm() {

        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->metaDataCode = Input::post('metaDataCode');
        $this->view->metaDataName = Input::post('metaDataName');

        $response = array(
            'html' => $this->view->renderPrint('system/link/group/addWorkflowStatusForm', self::$viewPath),
            'title' => 'Workflow - form',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function editWorkflowStatusForm() {

        $this->view->wfmStatusId = Input::post('wfmStatusId');
        $this->view->wfmStatusList = $this->model->wfmStatusListModel($this->view->wfmStatusId);

        $response = array(
            'html' => $this->view->renderPrint('system/link/group/editWorkflowStatusForm', self::$viewPath),
            'title' => 'Workflow - form',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function saveWorkFlowStatus() {
        $result = $this->model->saveWorkFlowStatusModel();
        echo json_encode($result); exit;
    }

    public function updateWorkFlowStatus() {
        $result = $this->model->updateWorkFlowStatusModel();
        echo json_encode($result); exit;
    }

    public function initWorkFlowStatus() {
        $result = $this->model->initWorkFlowStatusModel();
        echo json_encode($result);
    }

    public function deleteWorkFlowStatus() {
        $result = $this->model->deleteWorkFlowStatusModel();
        echo json_encode($result); exit;
    }

    public function lifecycleBookConfig() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->metaDataName = Input::post('metaDataName');
        
        $response = array(
            'html' => $this->view->renderPrint('system/link/group/lifecycleBookConfig', self::$viewPath),
            'title' => 'Lifecycle book & Lifecycle',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function lifecycleBookList() {
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->metaDataName = Input::post('metaDataName');
        echo json_encode($this->model->lifecycleBookListModel($this->view->metaDataId, $this->view->metaDataName)); exit;
    }

    public function setParamDefaultValues() {

        $postData = Input::postData();
        $isKey = ($postData['isKey'] == 'false' ? false : true);
        
        $this->view->lookupMetaDataId = Input::param($postData['lookupMetaDataId']);
        $this->view->paramName = $postData['paramName'];
        $this->view->paramPath = $postData['paramPath'];

        if ($this->view->lookupMetaDataId != '') {
            
            parse_str($postData['paramValues'], $paramValues);
            $this->view->paramValues = null;
            
            if ($isKey && isset($paramValues['paramDefaultValueIdKey'])) {
                
                $this->view->paramValues = $paramValues['paramDefaultValueIdKey'][$this->view->paramPath];
                
            } elseif (!$isKey && isset($paramValues['paramDefaultValueId'])) {
                
                $this->view->paramValues = $paramValues['paramDefaultValueId'][$this->view->paramPath];
            }

            $metaRow = (new Mdmetadata())->getMetaData($this->view->lookupMetaDataId);
            $this->view->metaTypeId = $metaRow['META_TYPE_ID'];

            $this->view->button = Form::button(
                array(
                    'class' => 'btn btn-xs green-meadow',
                    'value' => '<i class="icon-plus3 font-size-12"></i> Нэмэх',
                    'onclick' => 'dataViewSelectableGrid(\'' . $metaRow['META_DATA_CODE'] . '\', \'' . $this->view->lookupMetaDataId . '\', \'' . $this->view->lookupMetaDataId . '\', \'multi\', \'\', this, \'addBasketParamValues\');'
                )
            );

            $htmlContent = $this->view->renderPrint('system/link/group/setParamDefaultValues', self::$viewPath);
            
        } else {
            
            if ($isKey) {
                $message = 'Та Lookup key үзүүлэлт тохируулна уу!';
            } else {
                $message = 'Та Lookup үзүүлэлт тохируулна уу!';
            }
            
            $htmlContent = html_tag('div', array('class' => 'alert alert-info'), $message);
        }

        $response = array(
            'html' => $htmlContent,
            'title' => $this->lang->line('META_00079'),
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function addLifeCycleBookForm() {

        $this->view->metaDataId = Input::numeric('metaDataId');

        $response = array(
            'html' => $this->view->renderPrint('system/link/group/addLifeCycleBookForm', self::$viewPath),
            'title' => 'Lifecycle book form',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function editLifeCycleBookForm() {

        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->lcBookId = Input::post('lcBookId');
        $this->view->getlifeCycleBook = $this->model->getlifeCycleBookModel($this->view->lcBookId);
        
        $response = array(
            'html' => $this->view->renderPrint('system/link/group/editLifeCycleBookForm', self::$viewPath),
            'title' => 'Lifecycle Book form',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function editLifeCycleForm() {
        
        $this->view->lifecycleId = Input::post('lifecycleId');
        $this->view->getlifeCycle = $this->model->getlifeCycleModel($this->view->lifecycleId);
        
        $response = array(
            'html' => $this->view->renderPrint('system/link/group/editLifeCycleForm', self::$viewPath),
            'title' => 'Lifecycle form',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function insertLifecycleBook() {
        $this->view->metaDataId = Input::numeric('metaDataId');
        echo json_encode($this->model->insertLifecycleBookModel($this->view->metaDataId)); exit;
    }
    
    public function updateLifecycleBook() {
        echo json_encode($this->model->updateLifecycleBookModel()); exit;
    }
    
    public function deleteLifecycle() {
        $result = $this->model->deleteLifecycle();
        echo json_encode($result); exit;
    }
    
    public function deleteLcBookLifecycle() {
        $result = $this->model->deleteLcBookLifecycleModel();
        echo json_encode($result); exit;
    }

    public function updateLifecycle() {
        echo json_encode($this->model->updateLifecycleModel()); exit;
    }
    
    public function editLcBookLifeCycleForm() {

        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->lcBookId = Input::post('lcBookId');
        $this->view->lifecycleId = Input::post('lifecycleId');

        $this->view->getLcBookLifecycle = $this->model->getLcBookLifecycleModel($this->view->lcBookId, $this->view->lifecycleId);
        
        $response = array(
            'html' => $this->view->renderPrint('system/link/group/editLcBookLifecycleForm', self::$viewPath),
            'title' => 'LCBook and Lifecycle edit form',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function updateLcBookLifecycle() {
        echo json_encode($this->model->updateLcBookLifecycleModel()); exit;
    }
    
    public function lifecycleEditor() {
        
        $this->view->dataModelId = Input::numeric('metaDataId');
        $this->view->dataModelName = Input::post('metaDataName');
        $this->view->lcBookId = Input::post('lcBookId');
        $this->view->lcBookName = Input::post('lcBookName');
        $this->view->lifecycleId = Input::post('lifecycleId');
        $this->view->lifecycleName = Input::post('lifecycleName');
        
        $response = array(
            'html' => $this->view->renderPrint('system/link/group/lifecycleEditor', self::$viewPath),
            'title' => 'Lifecycle editor',
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function bannerManagerList() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->initProcessContent = $this->model->initProcessContentModel($this->view->metaDataId);
        
        $response = array(
            'html' => $this->view->renderPrint('system/link/banner/bannerList', self::$viewPath),
            'title' => $this->lang->line('META_00108'),
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function addProcessContentFrom() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->processType = $this->model->processTypeModel();
        
        $response = array(
            'html' => $this->view->renderPrint('system/link/banner/addProcessContentFrom', self::$viewPath),
            'title' => $this->lang->line('META_00083'),
            'add_btn' => $this->lang->line('add_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function processIconList() {

        $this->view->processTypeId = Input::post('processTypeId');
        $this->view->processIconList = $this->model->processIconListModel($this->view->processTypeId);

        $this->view->render('system/link/banner/bannerIconList', self::$viewPath);
    }
    
    public function saveProcessContent() {
        echo json_encode($this->model->saveProcessContentModel()); exit;
    }
    
    public function deleteProcessContent() {
        echo json_encode($this->model->deleteProcessContentModel()); exit;
    }
    
    public function findParentMetaIdByMetaId() {
        $metaDataId = Input::post('currentMetaId');
        $selectedMetaDataId = Input::post('selectedMetaId');
        echo json_encode($this->model->findParentMetaIdByMetaIdModel($metaDataId, $selectedMetaDataId)); exit;
    }
    
    public function internalProcess() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->folderId = Input::post('folderId');
        
        $response = array(
            'html' => $this->view->renderPrint('system/link/process/internalProcess', self::$viewPath),
            'title' => $this->lang->line('META_00169'),
            'save_btn' => 'Үүсгэх',
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function internalProcessAction() {
        $response = $this->model->internalProcessActionModel();
        echo json_encode($response); exit;
    }
    
    public function getMetaGroupLink() {
        $this->load->model('mdmetadata', 'middleware/models/');
        $inputGroupId = Input::post('inputGroupId');
        $result = $this->model->getMetaGroupLinkModel($inputGroupId);
        echo json_encode($result); exit;
    }
    
    public function appendSystemMetaGroupId() {
        $this->load->model('mdmetadata', 'middleware/models/');
        $result = $this->model->getMetaDataByCodeModel('systemMetaGroupId');
        echo json_encode($result); exit;
    }
    
    public function checkPassword() {
        
        $metaDataId = Input::numeric('metaDataId');
        $result = $this->model->checkPasswordModel($metaDataId);
        
        if ($result) {
            $response = array(
                'status' => 'access',
                'html' => $this->view->renderPrint('system/part/checkPassword', self::$viewPath),
                'title' => $this->lang->line('login_btn'),
                'login_btn' => $this->lang->line('login_btn'),
                'close_btn' => $this->lang->line('close_btn')
            );
        } else {
            $response = array('status' => 'nopassword');
        }
        
        echo json_encode($response); exit;
    }
    
    public function checkPasswordProcess() {
        
        $result = $this->model->checkPasswordProcessModel();
        
        if ($result && isset($_POST['rowData']) && is_array($_POST['rowData'])) {
            
            $passPath = issetVar($_POST['rowData'][$result]);

            if (!$passPath) {
                $response = array('status' => 'nopassword');                
                echo json_encode($response); exit;
            }
            
            $response = array(
                'status' => 'access',
                'html' => $this->view->renderPrint('system/part/checkPassword', self::$viewPath),
                'title' => $this->lang->line('login_btn'),
                'login_btn' => $this->lang->line('login_btn'),
                'close_btn' => $this->lang->line('close_btn')
            );
            
        } else {
            $response = array('status' => 'nopassword');
        }
        
        echo json_encode($response); exit;
    }
    
    public function loginPassword() {

        $result = $this->model->loginPasswordModel();
        
        if ($result) {
            $response = array('status' => 'success');
        } else {
            $response = array('status' => 'error', 'message' => 'Түлхүүр үг буруу байна.');
        }
        echo json_encode($response); exit;
    }
    
    public function loginPasswordProcess() {

        $result = $this->model->loginPasswordProcessModel();
        
        $response = array();
        
        if ($result !== false) {
            $response = array(
                'password' => Hash::createMD5reverse(Input::post('passwordHash')),
                'passwordPath' => $result
            );
        }
        echo json_encode($response); exit;
    }
    
    public function checkPassPathDataViewProcessMap() {
        $response = $this->model->checkPassPathDataViewProcessMapModel();
        echo json_encode($response); 
    }
    
    public function metaUnLockForm() {
        
        $response = array(
            'status' => 'access',
            'html' => $this->view->renderPrint('system/part/metaUnlock', self::$viewPath),
            'title' => $this->lang->line('login_btn'),
            'login_btn' => $this->lang->line('login_btn'),
            'change_btn' => $this->lang->line('user_change_password'),
            'close_btn' => $this->lang->line('close_btn')
        );
        
        echo json_encode($response); exit;
    }
    
    public function metaUnLock() {
        
        $result = $this->model->metaUnLockModel();
        
        if ($result) {
            $response = array('status' => 'success');
        } else {
            $response = array('status' => 'error', 'message' => 'Түлхүүр үг буруу байна.');
        }
        echo json_encode($response); exit;
    }
    
    public function metaUnLockPasswordReset() {
        
        $isMetaUnlockGetPassMode = Config::getFromCache('isMetaUnlockGetPassMode');
        
        if ($isMetaUnlockGetPassMode && !Input::postCheck('getPassMode')) {
            $response = array('html' => $this->view->renderPrint('system/part/metaUnlockGetPassMode', self::$viewPath));
        } else {
            $response = $this->model->metaUnLockPasswordResetModel();
        }
        
        echo json_encode($response); exit;
    }
    
    public function setGetDataProcessParam() {

        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->getProcessId = Input::post('getProcessId');
        
        $this->load->model('mdmeta', 'middleware/models/');
        $this->view->paramDtl = $this->model->getGetDataProcessParamModel($this->view->metaDataId);
        
        $this->load->model('mdmetadata', 'middleware/models/');
        $bpRow = $this->model->getMetaBusinessProcessLinkModel($this->view->getProcessId);
        
        if (isset($bpRow['SUB_TYPE'])) {
            
            if ($bpRow['SUB_TYPE'] == 'internal' && $bpRow['ACTION_TYPE'] == 'get' && $bpRow['SYSTEM_META_GROUP_ID'] != '') {
                
                $this->load->model('mdobject', 'middleware/models/');
                $this->view->paramComboData = $this->model->getDataViewGridBodyDataModel($bpRow['SYSTEM_META_GROUP_ID']);
                
                $this->view->paramComboArr = array(
                    'name' => 'getDataProcessParamCode[]',
                    'class' => 'form-control form-control-sm select2', 
                    'data' => $this->view->paramComboData,
                    'style' => 'width: 391px',
                    'op_value' => 'META_DATA_CODE', 
                    'op_text' => 'META_DATA_CODE'
                );
                
            } else {
                
                $this->load->model('mdmeta', 'middleware/models/');
                $this->view->paramComboData = $this->model->getMetaProcessParamMetaModel($this->view->getProcessId);
                
                $this->view->paramComboArr = array(
                    'name' => 'getDataProcessParamCode[]',
                    'class' => 'form-control form-control-sm select2', 
                    'data' => $this->view->paramComboData,
                    'style' => 'width: 391px',
                    'op_value' => 'PARAM_REAL_PATH', 
                    'op_text' => 'PARAM_REAL_PATH'
                );
            }
        }

        $response = array(
            'html' => $this->view->renderPrint('system/link/process/setGetDataProcessParam', self::$viewPath),
            'title' => 'GetData Process Param',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function addLifeCycleForm() {

        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->lifecycleBookList = $this->model->getLifeCycleBookListModel($this->view->metaDataId);
        
        $response = array(
            'html' => $this->view->renderPrint('system/link/group/addLifeCycleForm', self::$viewPath),
            'title' => 'Lifecycle book form',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function insertLifecycle() {
        echo json_encode($this->model->insertLifecycleModel()); exit;        
    }
    
    public function editLcBookForm() {

        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->lcBookId = Input::post('lcBookId');

        $this->view->getlifeCycleBook = $this->model->getlifeCycleBookModel($this->view->lcBookId);
        
        $response = array(
            'html' => $this->view->renderPrint('system/link/group/editLifeCycleBookForm', self::$viewPath),
            'title' => 'LCBook edit form',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function workSpaceLink() {
        $this->view->render('system/link/workspace/workSpaceLink', self::$viewPath);   
    }
    
    public function workSpaceLinkEditMode() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->workSpace = $this->model->getWorkSpaceModel($this->view->metaDataId);
        
        $this->view->render('system/link/workspace/workSpaceLinkEditMode', self::$viewPath);
    }
    
    public function groupConfigBackup() {
        
        $metaDataId = Input::numeric('metaDataId');
        
        $this->load->model('mdmetadata', 'middleware/models/');
        $this->view->metaRow = $this->model->getMetaDataModel($metaDataId);
        
        $this->load->model('mdmeta', 'middleware/models/');
        $this->view->backupList = $this->model->getBackUpListModel($metaDataId);
        
        $response = array(
            'html' => $this->view->renderPrint('system/part/backup/groupConfigBackup', self::$viewPath),
            'title' => 'Тохиргооны нөөц',
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function addConfigBackup() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        
        $response = array(
            'html' => $this->view->renderPrint('system/part/backup/addConfigBackup', self::$viewPath),
            'title' => 'Нөөц үүсгэх',
            'save_btn' => $this->lang->line('save_btn'), 
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function createConfigBackup() {
        $response = $this->model->createConfigBackupModel();
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function restoreConfigBackUp() {
        $response = $this->model->restoreConfigBackUpModel();
        echo json_encode($response, JSON_UNESCAPED_UNICODE); 
    }
    
    public function setStatementReportGrouping() {
        
        $metaDataId = Input::numeric('metaDataId');
        $dataViewId = Input::post('dataViewId');
        
        if (isset($metaDataId)) {
            $this->view->groupingList = $this->model->getStatementReportGroupingModel($metaDataId);
        }
        
        $this->view->paramList = $this->model->getMetaGroupParamMetaModel($dataViewId);
        $this->view->dataViewId = $dataViewId;
        $this->view->formId = 'addMetaSystemForm';
        
        if (Input::post('editMode') == 'true') {
            $this->view->formId = 'editMetaSystemForm';
        }
        
        $response = array(
            'html' => $this->view->renderPrint('system/link/statement/setStatementReportGrouping', self::$viewPath),
            'title' => 'Report grouping',
            'save_btn' => $this->lang->line('save_btn'), 
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function dvChildSql() {
        
        $groupLinkId = Input::post('id');
        
        $this->view->paramList = $this->model->getMetaGroupSubQueryModel($groupLinkId);
        $this->view->formId = 'editMetaSystemForm';
        
        $response = array(
            'html' => $this->view->renderPrint('system/link/group/dvChildSql', self::$viewPath),
            'title' => 'Dataview sub query',
            'save_btn' => $this->lang->line('save_btn'), 
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function themeFieldMap() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->initProcessThemeField = $this->model->initProcessThemeFieldModel($this->view->metaDataId);   
        
        $response = array(
            'html' => $this->view->renderPrint('system/link/process/listThemeField', self::$viewPath),
            'title' => 'Theme field',
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function addProcessThemeFiledFrom() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->getProcessFieldList = $this->model->getProcessFieldListModel($this->view->metaDataId);
        
        $response = array(
            'html' => $this->view->renderPrint('system/link/process/addThemeFieldForm', self::$viewPath),
            'title' => 'Theme field',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function insertProcessThemeField() {
        echo json_encode($this->model->insertProcessThemeFieldModel()); exit;   
    }
    
    public function initProcessThemeField() {
        $this->metaDataId = Input::numeric('metaDataId');
        echo json_encode($this->model->initProcessThemeFieldModel($this->metaDataId)); exit;                
    }
    
    public function deleteProcessThemeField() {
        $this->rowId = Input::post('rowId');
        echo json_encode($this->model->deleteProcessThemeFieldModel($this->rowId)); exit;
    }

    public function updateProcessThemeFieldIsLabel() {
        $this->rowId = Input::post('rowId');
        $this->orderNum = Input::post('orderNum');
        echo json_encode($this->model->updateProcessThemeFieldIsLabelModel($this->rowId, $this->isLabel)); exit;
    }
    
    public function updateProcessThemeFieldOrderNum() {
        $this->rowId = Input::post('rowId');
        $this->orderNum = Input::post('orderNum');
        echo json_encode($this->model->updateProcessThemeFieldOrderNumModel($this->rowId, $this->orderNum));
    }
    
    public function getProcessThemeFieldOrderNum() {
        $this->rowId = Input::post('rowId');
        echo json_encode($this->model->getProcessThemeFieldOrderNumModel($this->rowId)); exit;
    }
    
    public function bpExpressionCacheClear() {        
        
        $metaDataId = Input::numeric('metaDataId');
        
        $metaRow = (new Mdmetadata())->getMetaData($metaDataId);
        
        self::bpParamsClearCache($metaDataId, $metaRow['META_DATA_CODE'], true);
        
        $response = array(
            'status' => 'success',
            'message' => 'Амжилттай цэвэрлэгдлээ'
        );
        echo json_encode($response); exit;
    }
    
    public function bpExpressionCacheClearByMetaId() {
        $metaDataId = Input::numeric('metaDataId');
        
        $response = self::bpExpressionCacheClearById($metaDataId, true);
        echo json_encode($response); exit;
    }

    public function bpExpressionCacheClearById($metaDataId, $isParamClear = true) {
        
        $this->load->model('mdmeta', 'middleware/models/');
        $row = $this->model->getBPFullExpressionModel($metaDataId);
        
        if ($row) {
            
            if ($isParamClear) {
                $metaRow = (new Mdmetadata())->getMetaData($metaDataId);
                self::bpParamsClearCache($metaDataId, $metaRow['META_DATA_CODE'], true);
            }

            $response = array(
                'status' => 'success',
                'message' => 'Амжилттай цэвэрлэгдлээ'
            );
            
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Уг процесс LINK табле дээр үүсээгүй байна'
            );
        }
        
        return $response;
    }
    
    public function serviceReload() {
        $this->load->model('mdmeta', 'middleware/models/');
        
        $result = $this->model->serviceReloadModel();
        return $result;
    }
    
    public function serverReloadByProcess($processCode) {
        $this->load->model('mdmeta', 'middleware/models/');
        
        $result = $this->model->serverReloadByProcessModel($processCode);
        return $result;
    }
    
    public function serverReloadByDataView($dataViewId) {
        $this->load->model('mdmeta', 'middleware/models/');
        
        $result = $this->model->serverReloadByDataViewModel($dataViewId);
        return $result;
    }

    public function bpParamsClearCache($processMetaDataId, $processMetaDataCode, $isExpressionClear = false) {
        
        $tmpDir = Mdcommon::getCacheDirectory();
        
        self::bpConfigsClearCache($tmpDir, $processMetaDataId, strtolower($processMetaDataCode));
                
        if ($isExpressionClear) {
            self::bpOnlyExpressionClearCache($processMetaDataId);
        }
        
        self::serverReloadByProcess($processMetaDataCode);
        
        return true;
    }
    
    public function bpConfigsClearCache($tmp_dir, $processMetaDataId, $processCode = null) {
        
        if ($processCode) {
            $bpRunProcessFiles = glob($tmp_dir."/*/bp/bpRunProcess_".$processCode.".txt");
            foreach ($bpRunProcessFiles as $bpRunProcessFile) {
                @unlink($bpRunProcessFile);
            }
        }
        
        $bpConfigFile = glob($tmp_dir."/*/bp/bpConfig_".$processMetaDataId.".txt");
        foreach ($bpConfigFile as $configFile) {
            @unlink($configFile);
        }
        
        $bpHdrFiles = glob($tmp_dir."/*/bp/bpHdr_".$processMetaDataId.".txt");
        foreach ($bpHdrFiles as $hdrFile) {
            @unlink($hdrFile);
        }
        
        $bpHeaderFiles = glob($tmp_dir."/*/bp/bpHeader_".$processMetaDataId.".txt");
        foreach ($bpHeaderFiles as $bpHeaderFile) {
            @unlink($bpHeaderFile);
        }
        
        $bpHdrOnlyShows = glob($tmp_dir."/*/bp/bpHdrOnlyShow_".$processMetaDataId.".txt");
        foreach ($bpHdrOnlyShows as $bpHdrOnlyShow) {
            @unlink($bpHdrOnlyShow);
        }
        
        $bpAdnlHdrFiles = glob($tmp_dir."/*/bp/bpAdnlHdr_".$processMetaDataId."_*.txt");
        foreach ($bpAdnlHdrFiles as $adnlHdrFile) {
            @unlink($adnlHdrFile);
        }
        
        $bpAdnlHeaderFiles = glob($tmp_dir."/*/bp/bpAdnlHeader_".$processMetaDataId."_*.txt");
        foreach ($bpAdnlHeaderFiles as $bpAdnlHeaderFile) {
            @unlink($bpAdnlHeaderFile);
        }
        
        $bpDtlFiles = glob($tmp_dir."/*/bp/bpDtl_".$processMetaDataId."_*.txt");
        foreach ($bpDtlFiles as $dtlFile) {
            @unlink($dtlFile);
        }
        
        $bpDtlFiles = glob($tmp_dir."/*/bp/bpDtl_".$processMetaDataId.".txt");
        foreach ($bpDtlFiles as $bpDtlFile) {
            @unlink($bpDtlFile);
        }
        
        $bpDetailFiles = glob($tmp_dir."/*/bp/bpDetail_".$processMetaDataId."_*.txt");
        foreach ($bpDetailFiles as $bpDetailFile) {
            @unlink($bpDetailFile);
        }
        
        $bpDtlHdrFiles = glob($tmp_dir."/*/bp/bpDetail_".$processMetaDataId.".txt");
        foreach ($bpDtlHdrFiles as $bpDtlHdrFile) {
            @unlink($bpDtlHdrFile);
        }
        
        $bpAllInputsFiles = glob($tmp_dir."/*/bp/bpAllInput_".$processMetaDataId.".txt");
        foreach ($bpAllInputsFiles as $bpAllInputsFile) {
            @unlink($bpAllInputsFile);
        }
        
        $bpLookupCloneFieldFiles = glob($tmp_dir."/*/bp/bpLookupCloneField_".$processMetaDataId."_*.txt");
        foreach ($bpLookupCloneFieldFiles as $bpLookupCloneFieldFile) {
            @unlink($bpLookupCloneFieldFile);
        }
        
        $bpStatusFile = glob($tmp_dir."/*/ms/ms_".$processMetaDataId.".txt");
        foreach ($bpStatusFile as $statusFile) {
            @unlink($statusFile);
        }
        
        $bpTabHeaderContentFiles = glob($tmp_dir."/*/bp/bpTabHeaderContent_".$processMetaDataId."_*.txt");
        foreach ($bpTabHeaderContentFiles as $bpTabHeaderContentFile) {
            @unlink($bpTabHeaderContentFile);
        }        
        
        $bpTabHeaderGroupedContentFiles = glob($tmp_dir."/*/bp/bpTabHeaderGroupedContent_".$processMetaDataId."_*.txt");
        foreach ($bpTabHeaderGroupedContentFiles as $bpTabHeaderGroupedContentFile) {
            @unlink($bpTabHeaderGroupedContentFile);
        }        
        
        $bpTabHdrContentFiles = glob($tmp_dir."/*/bp/bpTabHdrContent_".$processMetaDataId."_*.txt");
        foreach ($bpTabHdrContentFiles as $bpTabHdrContentFile) {
            @unlink($bpTabHdrContentFile);
        }  
        
        $bpDtlAddHtmlFiles = glob($tmp_dir."/*/bp/bpDtlAddHtml_".$processMetaDataId."_*.txt");
        foreach ($bpDtlAddHtmlFiles as $bpDtlAddHtmlFile) {
            @unlink($bpDtlAddHtmlFile);
        }
        
        $bpDtlAddDtlFiles = glob($tmp_dir."/*/bp/bpDtlAddDtl_".$processMetaDataId."_*.txt");
        foreach ($bpDtlAddDtlFiles as $bpDtlAddDtlFile) {
            @unlink($bpDtlAddDtlFile);
        }
        
        $bpDefValues = glob($tmp_dir."/*/bp/bpDefValues_".$processMetaDataId."_*.txt");
        foreach ($bpDefValues as $bpDefValue) {
            @unlink($bpDefValue);
        }
        
        $bpDtlDataFiles = glob($tmp_dir."/*/bp/bpDtlData_".$processMetaDataId."_*.txt");
        foreach ($bpDtlDataFiles as $bpDtlDataFile) {
            @unlink($bpDtlDataFile);
        }
        
        $bpStarLookupFiles = glob($tmp_dir."/*/bp/bpStarLookup_".$processMetaDataId."_*.txt");
        foreach ($bpStarLookupFiles as $bpStarLookupFile) {
            @unlink($bpStarLookupFile);
        }   
        
        $bpDtlUserFields = glob($tmp_dir."/*/bp/bpDtlUserFields_".$processMetaDataId."_*.txt");
        foreach ($bpDtlUserFields as $bpDtlUserField) {
            @unlink($bpDtlUserField);
        }
        
        $dvAttributesFiles = glob($tmp_dir."/*/dv/dvAttributes_*_".$processMetaDataId."_*.txt");
        foreach ($dvAttributesFiles as $dvAttributesFile) {
            @unlink($dvAttributesFile);
        }
        
        $bpDvGets = glob($tmp_dir."/*/dv/dvGet_*_".$processMetaDataId.".txt");
        foreach ($bpDvGets as $bpDvGet) {
            @unlink($bpDvGet);
        }
        
        $bpDvGetParams = glob($tmp_dir."/*/dv/dvGetParams_*_".$processMetaDataId.".txt");
        foreach ($bpDvGetParams as $bpDvGetParam) {
            @unlink($bpDvGetParam);
        }
        
        $bpDvActionTypes = glob($tmp_dir."/*/dv/dvBpActionType_*_".$processMetaDataId.".txt");
        foreach ($bpDvActionTypes as $bpDvActionType) {
            @unlink($bpDvActionType);
        }
        
        $bpActionTypes = glob($tmp_dir."/*/dv/bpActionType_".$processMetaDataId.".txt");
        foreach ($bpActionTypes as $bpActionType) {
            @unlink($bpActionType);
        }
        
        $bpAutoConfigFile = glob($tmp_dir."/*/bp/bpAutoNumberConfig_".$processMetaDataId.".txt");
        foreach ($bpAutoConfigFile as $configAutoFile) {
            @unlink($configAutoFile);
        } 
        
        $bpLayoutSections = glob($tmp_dir."/*/bp/bpLayoutSections_".$processMetaDataId.".txt");
        foreach ($bpLayoutSections as $bpLayoutSection) {
            @unlink($bpLayoutSection);
        }
        
        $bpOnlyDfltValFlds = glob($tmp_dir."/*/bp/bpOnlyDfltValFlds_".$processMetaDataId.".txt");
        foreach ($bpOnlyDfltValFlds as $bpOnlyDfltValFld) {
            @unlink($bpOnlyDfltValFld);
        }
        
        $bpRunProcessFiles = glob($tmp_dir."/*/bp/bpRunProcess_".$processMetaDataId.".txt");
        foreach ($bpRunProcessFiles as $bpRunProcessFile) {
            @unlink($bpRunProcessFile);
        }
        
        $bpFiles = glob($tmp_dir."/*/bp/bp_".$processMetaDataId."_*.txt");
        foreach ($bpFiles as $bpFile) {
            @unlink($bpFile);
        }
        
        return true;
    }
    
    public static function bpOnlyExpressionClearCache($processMetaDataId, $tmp_dir = null) {
        
        if (!$tmp_dir) {
            $tmp_dir = Mdcommon::getCacheDirectory();
        }
        
        $processExpressions = glob($tmp_dir."/*/pr/processExpression_".$processMetaDataId.".txt");
        foreach ($processExpressions as $processExpression) {
            @unlink($processExpression);
        }

        $processFullExpressions = glob($tmp_dir."/*/pr/processFullExpression_".$processMetaDataId.".txt");
        foreach ($processFullExpressions as $processFullExpression) {
            @unlink($processFullExpression);
        }

        $processFullExpressionWithoutEvents = glob($tmp_dir."/*/pr/processFullExpressionWithoutEvent_".$processMetaDataId.".txt");
        foreach ($processFullExpressionWithoutEvents as $processFullExpressionWithoutEvent) {
            @unlink($processFullExpressionWithoutEvent);
        }

        $processFullExpressionVarFncs = glob($tmp_dir."/*/pr/processFullExpressionVarFnc_".$processMetaDataId.".txt");
        foreach ($processFullExpressionVarFncs as $processFullExpressionVarFnc) {
            @unlink($processFullExpressionVarFnc);
        }
        
        $processFullExpressionWithoutEventsLogs = glob($tmp_dir."/*/pr/processFullExpressionWithoutEvent_".$processMetaDataId."_log.txt");
        foreach ($processFullExpressionWithoutEventsLogs as $processFullExpressionWithoutEventsLog) {
            @unlink($processFullExpressionWithoutEventsLog);
        }

        $processFullExpressionVarFncsLogs = glob($tmp_dir."/*/pr/processFullExpressionVarFnc_".$processMetaDataId."_log.txt");
        foreach ($processFullExpressionVarFncsLogs as $processFullExpressionVarFncsLog) {
            @unlink($processFullExpressionVarFncsLog);
        }

        $processFullExpressionSaves = glob($tmp_dir."/*/pr/processFullExpressionSave_".$processMetaDataId.".txt");
        foreach ($processFullExpressionSaves as $processFullExpressionSave) {
            @unlink($processFullExpressionSave);
        } 
        
        $processFullExpressionAfterSaves = glob($tmp_dir."/*/pr/processFullExpressionAfterSave_".$processMetaDataId.".txt");
        foreach ($processFullExpressionAfterSaves as $processFullExpressionAfterSave) {
            @unlink($processFullExpressionAfterSave);
        }
        
        $processFullExpressionCaches = glob($tmp_dir."/*/pr/processFullExpressionCache_".$processMetaDataId.".txt");
        foreach ($processFullExpressionCaches as $processFullExpressionCache) {
            @unlink($processFullExpressionCache);
        }
        
        $bpCaches = glob($tmp_dir."/*/bp/bp_".$processMetaDataId."_*.txt");
        foreach ($bpCaches as $bpCache) {
            @unlink($bpCache);
        }
        
        return true;
    }
    
    public function bpFullExpressionUseProcess($processMetaDataId) {
        $this->load->model('mdmeta', 'middleware/models/');
        $result = $this->model->bpFullExpressionUseProcessModel($processMetaDataId);
        return $result;
    }
    
    public function processCacheClear($tmp_dir, $processId, $processCode) {
        
        self::bpConfigsClearCache($tmp_dir, $processId, $processCode);
        self::bpOnlyExpressionClearCache($processId, $tmp_dir);

        return true;
    }
    
    public function dataViewCacheClear($tmp_dir, $dvId) {
        
        $dvCommandButtonFiles = glob($tmp_dir."/*/dv/dvCommandButton_".$dvId."_*.txt");
        foreach ($dvCommandButtonFiles as $dvCommandButton) {
            @unlink($dvCommandButton);
        }
        $dvExpFiles = glob($tmp_dir."/*/dv/dvScripts_".$dvId.".txt");
        foreach ($dvExpFiles as $dvExpFile) {
            @unlink($dvExpFile);
        }
        $dvConfigFiles = glob($tmp_dir."/*/dv/dvConfig_".$dvId.".txt");
        foreach ($dvConfigFiles as $dvConfig) {
            @unlink($dvConfig);
        }
        $dvGridOptions = glob($tmp_dir."/*/dv/dvGridOption_".$dvId.".txt");
        foreach ($dvGridOptions as $dvGridOption) {
            @unlink($dvGridOption);
        }
        $dvUmCriteriaFiles = glob($tmp_dir."/*/dv/dvHeaderDataUmCriteria_".$dvId.".txt");
        foreach ($dvUmCriteriaFiles as $dvUmCriteriaFile) {
            @unlink($dvUmCriteriaFile);
        }
        $dvUmCriteriaFiles = glob($tmp_dir."/*/dv/dvHdrDataUmCriteria_".$dvId.".txt");
        foreach ($dvUmCriteriaFiles as $dvUmCriteriaFile) {
            @unlink($dvUmCriteriaFile);
        }
        $dvHeaderDataFiles = glob($tmp_dir."/*/dv/dvHeaderData_".$dvId.".txt");
        foreach ($dvHeaderDataFiles as $dvHeaderDataFile) {
            @unlink($dvHeaderDataFile);
        }
        $dvHeaderDataFiles = glob($tmp_dir."/*/dv/dvHdrData_".$dvId.".txt");
        foreach ($dvHeaderDataFiles as $dvHeaderDataFile) {
            @unlink($dvHeaderDataFile);
        }
        $dvHeaderDataFiles = glob($tmp_dir."/*/dv/dvHdrData_".$dvId."_*.txt");
        foreach ($dvHeaderDataFiles as $dvHeaderDataFile) {
            @unlink($dvHeaderDataFile);
        }
        $dvMandatoryCriteriaFiles = glob($tmp_dir."/*/dv/dvMandatoryCriteria_".$dvId.".txt");
        foreach ($dvMandatoryCriteriaFiles as $dvMandatoryCriteriaFile) {
            @unlink($dvMandatoryCriteriaFile);
        }
        $dvMandatoryCriteriaFiles = glob($tmp_dir."/*/dv/dvMandatoryCriterias_".$dvId.".txt");
        foreach ($dvMandatoryCriteriaFiles as $dvMandatoryCriteriaFile) {
            @unlink($dvMandatoryCriteriaFile);
        }
        $dvMandatoryCriteriaFiles = glob($tmp_dir."/*/dv/dvMandatoryCriteria_".$dvId."_*.txt");
        foreach ($dvMandatoryCriteriaFiles as $dvMandatoryCriteriaFile) {
            @unlink($dvMandatoryCriteriaFile);
        }
        $dvMandatoryCriteriaFiles = glob($tmp_dir."/*/dv/dvMandatoryCriterias_".$dvId."_*.txt");
        foreach ($dvMandatoryCriteriaFiles as $dvMandatoryCriteriaFile) {
            @unlink($dvMandatoryCriteriaFile);
        }
        $dvCriteriaFiles = glob($tmp_dir."/*/dv/dvCriteria_".$dvId.".txt");
        foreach ($dvCriteriaFiles as $dvCriteriaFile) {
            @unlink($dvCriteriaFile);
        }
        $dvCriteriaFiles = glob($tmp_dir."/*/dv/dvCriterias_".$dvId.".txt");
        foreach ($dvCriteriaFiles as $dvCriteriaFile) {
            @unlink($dvCriteriaFile);
        }
        $dvCriteriaFiles = glob($tmp_dir."/*/dv/dvCriteria_".$dvId."_*.txt");
        foreach ($dvCriteriaFiles as $dvCriteriaFile) {
            @unlink($dvCriteriaFile);
        }
        $dvCriteriaFiles = glob($tmp_dir."/*/dv/dvCriterias_".$dvId."_*.txt");
        foreach ($dvCriteriaFiles as $dvCriteriaFile) {
            @unlink($dvCriteriaFile);
        }
        $dvGroupCriteriaFiles = glob($tmp_dir."/*/dv/dvGroupCriteria_".$dvId.".txt");
        foreach ($dvGroupCriteriaFiles as $dvGroupCriteriaFile) {
            @unlink($dvGroupCriteriaFile);
        }
        $dvGroupCriteriaFiles = glob($tmp_dir."/*/dv/dvGroupCriterias_".$dvId.".txt");
        foreach ($dvGroupCriteriaFiles as $dvGroupCriteriaFile) {
            @unlink($dvGroupCriteriaFile);
        }
        $dvAttributesFiles = glob($tmp_dir."/*/dv/dvAttributes_".$dvId.".txt");
        foreach ($dvAttributesFiles as $dvAttributesFile) {
            @unlink($dvAttributesFile);
        }
        $dvAttributesFiles = glob($tmp_dir."/*/dv/dvAttributes_".$dvId."_.txt");
        foreach ($dvAttributesFiles as $dvAttributesFile) {
            @unlink($dvAttributesFile);
        }
        $dvAttributesFiles = glob($tmp_dir."/*/dv/dvAttributes_".$dvId."_*.txt");
        foreach ($dvAttributesFiles as $dvAttributesFile) {
            @unlink($dvAttributesFile);
        }
        $dvAttributesFiles = glob($tmp_dir."/*/dv/dvAttributes_*_".$dvId."_*.txt");
        foreach ($dvAttributesFiles as $dvAttributesFile) {
            @unlink($dvAttributesFile);
        }
        $dvSubgridFiles = glob($tmp_dir."/*/dv/dvSubgrid_".$dvId.".txt");
        foreach ($dvSubgridFiles as $dvSubgridFile) {
            @unlink($dvSubgridFile);
        }
        $dvStandartFields = glob($tmp_dir."/*/dv/dvStandartFields_".$dvId.".txt");
        foreach ($dvStandartFields as $dvStandartField) {
            @unlink($dvStandartField);
        }
        $bpStatusFile = glob($tmp_dir."/*/ms/ms_".$dvId.".txt");
        foreach ($bpStatusFile as $statusFile) {
            @unlink($statusFile);
        }
        $bpStarLookupFiles = glob($tmp_dir."/*/bp/bpStarLookup_*_".$dvId.".txt");
        foreach ($bpStarLookupFiles as $bpStarLookupFile) {
            @unlink($bpStarLookupFile);
        } 
        $dvBps = glob($tmp_dir."/*/dv/dvBps_".$dvId.".txt");
        foreach ($dvBps as $dvBp) {
            @unlink($dvBp);
        }
        $dvGets = glob($tmp_dir."/*/dv/dvGet_".$dvId."_*.txt");
        foreach ($dvGets as $dvGet) {
            @unlink($dvGet);
        }
        $dvGetParams = glob($tmp_dir."/*/dv/dvGetParams_".$dvId."_*.txt");
        foreach ($dvGetParams as $dvGetParam) {
            @unlink($dvGetParam);
        }
        $dvAutoMaps = glob($tmp_dir."/*/dv/dvAutoMap_".$dvId."_*.txt");
        foreach ($dvAutoMaps as $dvAutoMap) {
            @unlink($dvAutoMap);
        }
        $dvUserFiles = glob($tmp_dir."/*/dv/dvUser_*_".$dvId."_*.txt");
        foreach ($dvUserFiles as $dvUserFile) {
            @unlink($dvUserFile);
        }
        $dvOnlyEmailColumns = glob($tmp_dir."/*/dv/dvOnlyEmailColumns_".$dvId.".txt");
        foreach ($dvOnlyEmailColumns as $dvOnlyEmailColumn) {
            @unlink($dvOnlyEmailColumn);
        }
        $dvOnlyShowColumns = glob($tmp_dir."/*/dv/dvOnlyShowColumns_".$dvId.".txt");
        foreach ($dvOnlyShowColumns as $dvOnlyShowColumn) {
            @unlink($dvOnlyShowColumn);
        }
        $dvMainQueries = glob($tmp_dir."/*/dv/dvMainQueries_".$dvId.".txt");
        foreach ($dvMainQueries as $dvMainQuery) {
            @unlink($dvMainQuery);
        }
        $dvPathFiles = glob($tmp_dir."/*/dv/dvPath_".$dvId.".txt");
        foreach ($dvPathFiles as $dvPathFile) {
            @unlink($dvPathFile);
        }
        $bpDvActionTypes = glob($tmp_dir."/*/dv/dvBpActionType_".$dvId."_*.txt");
        foreach ($bpDvActionTypes as $bpDvActionType) {
            @unlink($bpDvActionType);
        }
        $dvIsGetDataProcess = glob($tmp_dir."/*/dv/dvIsGetDataProcess_".$dvId."_*.txt");
        foreach ($dvIsGetDataProcess as $dvIsGetDataBp) {
            @unlink($dvIsGetDataBp);
        }
        $finAccountDvs = glob($tmp_dir."/*/dv/finAccountDv_".$dvId."_*.txt");
        foreach ($finAccountDvs as $finAccountDv) {
            @unlink($finAccountDv);
        }
        $dvUserConfigs = glob($tmp_dir."/*/dv/dvUserConfig_".$dvId."_*.txt");
        foreach ($dvUserConfigs as $dvUserConfig) {
            @unlink($dvUserConfig);
        }
        $dvLogColumns = glob($tmp_dir."/*/dv/dvLogColumns_".$dvId.".txt");
        foreach ($dvLogColumns as $dvLogColumn) {
            @unlink($dvLogColumn);
        }
        $dvUserConfigsMerge = glob($tmp_dir."/*/dv/dvUserConfigMergeCols2_".$dvId."_*.txt");
        foreach ($dvUserConfigsMerge as $dvUserConfigMerge) {
            @unlink($dvUserConfigMerge);
        }    
        $dvFiles = glob($tmp_dir."/*/dv/dv_".$dvId."_*.txt");
        foreach ($dvFiles as $dvFile) {
            @unlink($dvFile);
        }        
        
        self::bpConfigsClearCache($tmp_dir, $dvId);
        self::bpOnlyExpressionClearCache($dvId, $tmp_dir);
        
        return true;
    }

    public function clearProcessCache() {
        
        $tmp_dir = Mdcommon::getCacheDirectory();
        
        $bpConfigFiles = count(glob($tmp_dir."/*/bp/bpConfig_*.txt"));
        $dvConfigFiles = count(glob($tmp_dir."/*/dv/dvConfig_*.txt"));
        
        $this->view->cacheFileCount = ($bpConfigFiles + $dvConfigFiles); 
        
        $response = array(
            'html' => $this->view->renderPrint('system/part/clearProcessCache', self::$viewPath),
            'title' => 'Process & Dataview cache цэвэрлэх',
            'clear_btn' => $this->lang->line('clear_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function runClearProcessCache() {
        
        if (!Config::getFromCache('CONFIG_ALL_CACHE_CLEAR')) {
            echo json_encode(array('status' => 'info', 'message' => 'Цэвэрлэх үйлдэл идэвхгүй байна. Та тухайн үзүүлэлт дээрх цэвэрлэх үйлдлийг ашиглана уу.'));
            exit();
        }
        
        $tmp_dir = Mdcommon::getCacheDirectory();
        
        $bpFiles = glob($tmp_dir."/*/bp/bp*.txt");
        foreach ($bpFiles as $bpFile) {
            @unlink($bpFile);
        }
        
        $bpExpFiles = glob($tmp_dir."/*/pr/pr*.txt");
        foreach ($bpExpFiles as $expFile) {
            @unlink($expFile);
        }
        
        $dvExpFiles = glob($tmp_dir."/*/dv/dv*.txt");
        foreach ($dvExpFiles as $dvExpFile) {
            @unlink($dvExpFile);
        }
        
        $bpStatusFiles = glob($tmp_dir."/*/ms/ms*.txt");
        foreach ($bpStatusFiles as $bpStatusFile) {
            @unlink($bpStatusFile);
        }
        
        $kpiTemplateFiles = glob($tmp_dir."/*/kp/kpi*.txt");
        foreach ($kpiTemplateFiles as $kpiTemplateFile) {
            @unlink($kpiTemplateFile);
        }
        
        echo json_encode(array('status' => 'success', 'message' => 'Амжилттай цэвэрлэгдлээ')); exit;
    }
    
    public function dvCacheClearByPost() {
        $metaDataId = Input::numeric('metaDataId');
        
        $response = self::dvCacheClearByMetaId($metaDataId);
        echo json_encode($response); exit;
    }
    
    public function dvCacheClearByMetaId($metaDataId, $isRelatedProcessCacheClear = false) {
        
        $tmp_dir = Mdcommon::getCacheDirectory();
        
        self::dataViewCacheClear($tmp_dir, $metaDataId);
        self::serverReloadByDataView($metaDataId);
        
        if ($isRelatedProcessCacheClear) {
            self::bpCacheClearByRelatedGroupId($metaDataId);
        }
        
        return array('status' => 'success', 'message' => 'Амжилттай цэвэрлэгдлээ');
    }
    
    public function bpCacheClearByRelatedGroupId($metaDataId) {
        
        if (Lang::isUseMultiLang()) {
            
            global $db;
            
            $processIds = $db->GetAll("
                SELECT 
                    MD.META_DATA_ID, 
                    MD.META_DATA_CODE 
                FROM META_BUSINESS_PROCESS_LINK PL 
                    INNER JOIN META_DATA MD ON MD.META_DATA_ID = PL.META_DATA_ID 
                WHERE PL.SYSTEM_META_GROUP_ID = ".$db->Param(0), 
                array($metaDataId)
            );

            if ($processIds) {
                $meta = new Mdmeta();
                foreach ($processIds as $row) {
                    $meta->bpParamsClearCache($row['META_DATA_ID'], $row['META_DATA_CODE'], true); 
                }
            }
        }
        
        return true;
    }
    
    public function folderCacheClear() {
        $folderId = Input::post('id');
        
        $response = $this->model->folderCacheClearModel($folderId);
        echo json_encode($response); exit;
    }
    
    public function metaProcessTempUploadImage() {
        if (isset($_FILES['bp_photo'])) {
            $photo_arr['0'] = $_FILES['bp_photo'];
            $p = 0;
            if ($photo_arr[$p]['name'] != '') {
                $newPhotoName = "photo_" . getUID() . $p;
                $photoExtension = strtolower(substr($photo_arr[$p]['name'], strrpos($photo_arr[$p]['name'], '.') + 1));
                $photoFileName = $newPhotoName . "." . $photoExtension;
                Upload::$File = $photo_arr[$p];
                Upload::$method = 0;
                Upload::$SavePath = UPLOADPATH . "metavalue/photo_temp/original/";
                Upload::$ThumbPath = UPLOADPATH . "metavalue/photo_temp/thumb/";
                Upload::$NewWidth = 1000;
                Upload::$TWidth = 150;
                Upload::$NewName = $newPhotoName;
                Upload::$OverWrite = true;
                $uploadError = Upload::UploadFile();
                if ($uploadError == '') {
                    echo json_encode(array('status' => 'success', 'photoFileName' => $photoFileName, 'message' => 'Амжилттай заслаа'));
                } else {
                    echo json_encode(array('status' => 'error', 'message' => 'Алдаа гарлаа'));
                }
            }
        } 
        exit;
    }
    
    public function metaProcessTempDeleteImage() {
        $originalPhoto = Input::post('originalPhoto');
        $thumbPhoto = Input::post('thumbPhoto');
        if (is_file($originalPhoto)) {
            @unlink($originalPhoto);
        }
        if (is_file($thumbPhoto)) {
            @unlink($thumbPhoto);
        }
        echo json_encode(array(
            'status' => 'success',
            'message' => $this->lang->line('msg_delete_success')
        )); exit;
    }

    public function metaDelete() {
        
        $this->load->model('mdmetadata', 'middleware/models/');
        $this->view->metaDataId = Input::numeric('metaDataId');
        
        $checkLock = $this->model->checkMetaLock($this->view->metaDataId);

        if ($checkLock) {
            jsonResponse($checkLock);
        }
        
        $this->load->model('mdmeta', 'middleware/models/');
        
        $result = $this->model->isUsedMetaModel($this->view->metaDataId);
        $this->view->isParent = $result;
        
        $this->load->model('mdmetadata', 'middleware/models/');
        
        $this->view->metaRow = $this->model->getMetaDataBySystem($this->view->metaDataId);
        
        $response = array(
            'Html' => $this->view->renderPrint('system/deleteMetaBySystem', self::$viewPath),
            'Title' => 'Мета устгах',
            'status' => 'success', 
            'yes_btn' => $this->lang->line('yes_btn'),
            'no_btn' => $this->lang->line('no_btn')
        );
        jsonResponse($response);
    }
    
    public function setMetaModifiedDate($metaDataId) {
        return $this->db->AutoExecute('META_DATA', array('MODIFIED_DATE' => Date::currentDate('Y-m-d H:i:s')), 'UPDATE', 'META_DATA_ID = '.$metaDataId);
    }
   
    public static function getAfterSave($exp) {
        if (empty($exp)) {
            return null;
        }
        
        preg_match_all('/startAfterSave(.*?)endAfterSave/ms', $exp, $afterSaveExpression);
        
        if (count($afterSaveExpression[0]) > 0) {
            return trim($afterSaveExpression[1][0]);
        }
        
        return null;
    }
    
    public static function removeAfterSave($exp) {
        if (empty($exp)) {
            return null;
        }
        
        preg_match_all('/startAfterSave(.*?)endAfterSave/ms', $exp, $afterSaveExpression);
        
        if (count($afterSaveExpression[0]) > 0) {
            $exp = str_replace($afterSaveExpression[0][0], '', $exp);
            return $exp;
        }
        
        return $exp;
    }
    
    public function setProcessFullExpressionCriteria() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        
        $this->load->model('mdmetadata', 'middleware/models/');
        
        if (Input::post('codeFrom') != 'allinone' && $changeLogResponse = $this->model->isCheckChangeLogMetaModel($this->view->metaDataId)) {
            echo json_encode($changeLogResponse, JSON_UNESCAPED_UNICODE); exit;
        }
        
        $this->load->model('mdmeta', 'middleware/models/');
        $this->view->metaDatas = $this->model->getMetaProcessByMetaSingleDatasModel($this->view->metaDataId);
        
        $this->view->addVersion = Input::post('addVersion');
        $this->view->editVersion = Input::post('editVersion');
        
        $this->view->isVersionForm = false;
        
        if ($this->view->addVersion == 'true') {
            
            $this->view->expRow = $this->model->getBPFullExpressionByConfigModel($this->view->metaDataId);
            $this->view->expRow['AFTER_EXPRESSION_STRING'] = Mdmeta::getAfterSave(Arr::get($this->view->expRow, 'SAVE_EXPRESSION_STRING'));
            $this->view->expRow['SAVE_EXPRESSION_STRING'] = Mdmeta::removeAfterSave(Arr::get($this->view->expRow, 'SAVE_EXPRESSION_STRING'));
            
            $this->view->configId = isset($this->view->expRow['CONFIG_ID']) ? $this->view->expRow['CONFIG_ID'] : null;
            
            $this->view->isVersionForm = true;
            $this->view->versionForm = $this->view->renderPrint('system/link/process/fullexpression/newVersionTab', self::$viewPath);
            
            $title = 'Full Expression - '.$this->view->expRow['META_DATA_NAME'];
            
        } elseif ($this->view->editVersion == 'true') {
            
            $this->view->versionId = Input::post('versionId');
            
            $this->view->expRow = $this->model->getBPFullExpressionByVersionModel($this->view->versionId);
            $this->view->expRow['AFTER_EXPRESSION_STRING'] = Mdmeta::getAfterSave(Arr::get($this->view->expRow, 'SAVE_EXPRESSION_STRING'));
            $this->view->expRow['SAVE_EXPRESSION_STRING'] = Mdmeta::removeAfterSave(Arr::get($this->view->expRow, 'SAVE_EXPRESSION_STRING'));
            
            $this->view->configId = isset($this->view->expRow['CONFIG_ID']) ? $this->view->expRow['CONFIG_ID'] : null;
            
            $this->view->isVersionForm = true;
            $this->view->versionForm = $this->view->renderPrint('system/link/process/fullexpression/editVersionTab', self::$viewPath);
            
            $title = 'Full Expression - '.$this->view->expRow['META_DATA_NAME']. ' ('.$this->view->expRow['TITLE'].')';
            
        } else {
            
            $this->view->expRow = $this->model->getBPFullExpressionByConfigModel($this->view->metaDataId);
            
            $this->view->expRow['AFTER_EXPRESSION_STRING'] = Mdmeta::getAfterSave(Arr::get($this->view->expRow, 'SAVE_EXPRESSION_STRING'));
            $this->view->expRow['SAVE_EXPRESSION_STRING'] = Mdmeta::removeAfterSave(Arr::get($this->view->expRow, 'SAVE_EXPRESSION_STRING'));
            
            $this->view->configId = isset($this->view->expRow['CONFIG_ID']) ? $this->view->expRow['CONFIG_ID'] : null;
            
            $title = 'Full Expression - '.$this->view->expRow['META_DATA_NAME'];
            
            if (isset($this->view->expRow['TITLE'])) {
                $title .= ' ('.$this->view->expRow['TITLE'].')';
            }
        }
        
        $this->view->cacheExpressionList = $this->view->expRow['cacheExp'];
        $this->view->cacheExpressionForm = $this->view->renderPrint('system/link/process/fullexpression/cacheVersion', self::$viewPath);
        
        $response = [
            'Html' => $this->view->renderPrint('system/link/process/setProcessFullExpressionCriteria', self::$viewPath),
            'Title' => $title,
            'status' => 'success', 
            'create_version_btn' => 'Хувилбар үүсгэх',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        ];
        convJson($response);
    }    
    
    public function tempProcessFullExpressionForm() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->metaDatas = $this->model->getMetaProcessByMetaSingleDatasModel($this->view->metaDataId);
        
        $this->view->isVersionForm = false;
        $this->view->ignoreCacheForm = true;
        $this->view->configId = null;
        
        $this->view->expRow = $this->model->getBPFullExpressionByConfigModel($this->view->metaDataId);
        
        $cache = phpFastCache();
        $sessionId = Ue::appUserSessionId();
        $bpFullScriptsEvent = $cache->get('bp_'.$this->view->metaDataId.'_ExpEvent_'.$sessionId);
        
        if ($bpFullScriptsEvent) {
            $this->view->expRow['EVENT_EXPRESSION_STRING'] = $bpFullScriptsEvent;
            $this->view->expRow['LOAD_EXPRESSION_STRING'] = $cache->get('bp_'.$this->view->metaDataId.'_ExpLoad_'.$sessionId);
            $this->view->expRow['VAR_FNC_EXPRESSION_STRING'] = $cache->get('bp_'.$this->view->metaDataId.'_ExpVarFnc_'.$sessionId);
            $this->view->expRow['SAVE_EXPRESSION_STRING'] = $cache->get('bp_'.$this->view->metaDataId.'_ExpBeforeSave_'.$sessionId);
            $this->view->expRow['AFTER_EXPRESSION_STRING'] = $cache->get('bp_'.$this->view->metaDataId.'_ExpAfterSave_'.$sessionId);
        } else {
            $this->view->expRow['AFTER_EXPRESSION_STRING'] = Mdmeta::getAfterSave(Arr::get($this->view->expRow, 'SAVE_EXPRESSION_STRING'));
            $this->view->expRow['SAVE_EXPRESSION_STRING'] = Mdmeta::removeAfterSave(Arr::get($this->view->expRow, 'SAVE_EXPRESSION_STRING'));
        }
        
        $title = 'Full Expression - '.$this->view->expRow['META_DATA_NAME'];
        
        $response = array(
            'Html' => $this->view->renderPrint('system/link/process/setProcessFullExpressionCriteria', self::$viewPath),
            'Title' => $title,
            'status' => 'success', 
            'create_version_btn' => 'Хувилбар үүсгэх',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response, JSON_UNESCAPED_UNICODE); 
    }    
    
    public function saveFullExpression() {
        $response = $this->model->saveFullExpressionModel();
        echo json_encode($response, JSON_UNESCAPED_UNICODE); 
    }
    
    public function tempSaveFullExpression() {
        $response = $this->model->tempSaveFullExpressionModel();
        echo json_encode($response, JSON_UNESCAPED_UNICODE); 
    }
    
    public function fullExpNewVersion() {
        $response = array(
            'html' => $this->view->renderPrint('system/link/process/fullexpression/newVersion', self::$viewPath),
            'title' => 'Full Expression - Хувилбар үүсгэх',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response, JSON_UNESCAPED_UNICODE); 
    }
    
    public function saveNewVersionFullExpression() {
        $response = $this->model->saveNewVersionFullExpressionModel();
        echo json_encode($response, JSON_UNESCAPED_UNICODE); 
    }
    
    public function saveUpdateVersionFullExpression() {
        $response = $this->model->saveUpdateVersionFullExpressionModel();
        echo json_encode($response, JSON_UNESCAPED_UNICODE); 
    }
    
    public function deleteBpFullExpressionVersion() {
        $response = $this->model->deleteBpFullExpressionVersionModel();
        echo json_encode($response); exit;
    }

    public function bpFullExpressionList() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        
        $this->view->versionList = $this->model->getFullExpressionVersionListModel($this->view->metaDataId);
        
        $this->load->model('mdmetadata', 'middleware/models/');
        $metaRow = $this->model->getMetaDataModel($this->view->metaDataId);
        
        $response = array(
            'html' => $this->view->renderPrint('system/link/process/fullexpression/versionList', self::$viewPath),
            'title' => 'Full Expression - Хувилбарууд - '.$metaRow['META_DATA_NAME'],
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function layout() {
        $this->view->layoutLink = false;
        $this->view->render('system/link/layout/layoutLink', self::$viewPath);
    }
    
    public function layoutEditMode() {
        $this->load->model('mdlayoutrender', 'middleware/models/');
        
        $metaDataId = Input::numeric('metaDataId');
        $this->view->layoutLink = $this->model->getLayoutLinkModel($metaDataId);
        
        if ($this->view->layoutLink) {
            
            $this->view->layoutParamMap = $this->model->getLayoutParamMapControlModel($this->view->layoutLink['ID']);   
            
            if (!empty($this->view->layoutLink['CRITERIA_DATA_VIEW_ID'])) {
                $this->load->model('mdmetadata', 'middleware/models/');
                $this->view->getMetRow = $this->model->getMetaDataModel($this->view->layoutLink['CRITERIA_DATA_VIEW_ID']);
            }
            
            $this->view->render('system/link/layout/layoutLinkEditMode', self::$viewPath);
            
        } else {
            $this->view->layoutLink = array('ID' => getUID());
            $this->view->render('system/link/layout/layoutLink', self::$viewPath);
        }
    }
    
    public function layoutLoadName() {
        $themeCode = Input::post('themeCode');
        echo json_encode(file_get_contents(BASEPATH.'middleware/views/layoutrender/themes/'.$themeCode.'/theme.html')); exit;
    }
    
    public function deleteLayoutLinkParamMap() {
        if (Input::postCheck('layoutLinkParamMapId')) {
            echo json_encode($this->model->deleteLayoutLinkParamMapModel(Input::post('layoutLinkParamMapId')));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'id сонгоогүй байна'));
        }
        exit;
    }
    
    public function googleMapLink() {
        $this->view->googleMapLink = false;
        $this->view->render('system/link/gmap/googleMapLink', self::$viewPath);
    }
    
    public function googleMapLinkEditMode() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->googleMapLink = false;
        
        $this->view->render('system/link/gmap/googleMapLinkEditMode', self::$viewPath);
    }
    
    public function deleteGoogleMapLink() {
        if (Input::postCheck('metaGoogleMapLinkId')) {
            echo json_encode($this->model->deleteGoogleMapLinkModel(Input::post('metaGoogleMapLinkId')));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Google Map Id сонгоохгүй байна'));
        }
        exit;
    }
    
    public function initGoogleMapLink() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->initGoogleMapLink = $this->model->initGoogleMapLinkModel($this->view->metaDataId);
        
        $response = array(
            'Html' => $this->view->renderPrint('system/link/gmap/googleMapLinkParamMap', self::$viewPath),
            'Title' => 'Google map link params',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function chooseGoogleMapIcon() {
        
        $this->view->displayColor = Input::post('displayColor');
        $this->view->iconName = Input::post('iconName');
        
        $response = array(
            'Html' => $this->view->renderPrint('system/link/gmap/chooseGoogleMapIcon', self::$viewPath),
            'Title' => 'Choose Icon',
            'add_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function googleMapParam() {
        
        $this->view->googleMapLinkId = Input::post('googleMapLinkId');
        $this->view->listMetaDataId = Input::post('listMetaDataId');
        $this->view->actionMetaDataId = Input::post('actionMetaDataId');
        $this->view->actionMetaTypeId = Input::post('actionMetaTypeId');
        $this->view->googleMapParamHtml = $this->model->googleMapParamHtmlModel($this->view->listMetaDataId, $this->view->actionMetaDataId, $this->view->googleMapLinkId, $this->view->actionMetaTypeId);
        
        $response = array(
            'Html' => $this->view->renderPrint('system/link/gmap/googleMapParam', self::$viewPath),
            'Title' => 'Google map control',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function insertGoogleMapParam() {
        echo json_encode($this->model->insertGoogleMapParamModel()); exit;
    }
    
    public function deleteGoogleMapParam() {
        echo json_encode($this->model->deleteGoogleMapParamModel()); exit;
    }
    
    public function deleteGoogleMapParamDialog() {
        $response = array(
            'Html' => 'Та тохиргоог устгахдаа итгэлтэй байна уу?',
            'Title' => 'Сануулга',
            'yes_btn' => $this->lang->line('yes_btn'),
            'no_btn' => $this->lang->line('no_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function insertGoogleMapLink() {
        echo json_encode($this->model->insertGoogleMapLinkModel()); exit;
    }
    
    public function googleMapTransperAction() {
        
        $googleMapParam = $this->model->getGoogleMapParamModel(Input::post('metaGoogleMapLinkId'));
        
        $this->load->model('mdobject', 'middleware/models/');
        $data = array();
        $listMetaDataId = Input::post('listMetaDataId');
        $rowId = Input::post('rowId');

        $criteria['id'][] = array(
            'operator' => '=',
            'operand' => $rowId
        );
        $result = $this->model->getDataViewByCriteriaModel($listMetaDataId, $criteria);
        $data['actionMetaDataId'] = Input::post('actionMetaDataId');
        $data['actionMetaTypeId'] = Input::post('actionMetaTypeId');
        $data['dmMetaDataId'] = Input::post('actionMetaDataId');
        $data['isGetConsolidate'] = true;
        $data['oneSelectedRow'] = $result;
        
        if (count($result) and $googleMapParam) {
            foreach ($googleMapParam as $k => $row) {
                if (isset($result[$row['SRC_PARAM']])) {
                    $data[$row['TRG_PARAM']] = $result[$row['SRC_PARAM']];
                }
            }
        }
        echo json_encode(array('status' => 'success', 'params' => $data)); exit;
    }
    
    public function setGoogleMapRegion() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $result = html_entity_decode(Input::post('region'));
        $data = json_decode($result, true);
        
        $response = array(
            'Html' => $this->view->renderPrint('system/link/gmap/setGoogleMapControl', self::$viewPath),
            'Title' => 'Google map control',
            'insert_btn' => $this->lang->line('insert_btn'),
            'close_btn' => $this->lang->line('close_btn'),
            'drawType' => ($result != '' ? $data['drawType'] : 'polygon'),
            'lat' => ($result != '' ? $data['center']['lat'] : 47.9228419581677),
            'lng' => ($result != '' ? $data['center']['lng'] : 106.91702485084534),
            'color' => ($result != '' ? $data['color'] : '#1e90ff'),
            'coordinates' => ($data && $data['drawType'] != 'circle' ? $data['coordinates'] : false),
            'radius' => ($data && $data['drawType'] == 'circle' ? $data['radius'] : false),
            'mode' => ($result != '' ? 'edit' : 'new')
        );
        echo json_encode($response); exit;
    }
    
    public function customImageMarkerCtrl() {

        $picturePath = Input::post('picturePath');
        
        if (Input::numeric('id') || $picturePath) {
            
            $result = html_entity_decode(Input::post('region'));
            $this->view->data = $result;
            
            if ($picturePath) {
                $this->view->getPhoto['url'] = $picturePath;
            } else {
                $this->view->getPhoto = $this->model->getWhLocationPhotoModel(Input::numeric('id'));
            }

            if (empty($this->view->getPhoto['url'])) {
                $response = array('Html' => '');
                echo json_encode($response); exit;
            }

            $response = array(
                'Html' => $this->view->renderPrint('system/link/imageMarker/imageMarkerControl', self::$viewPath),
                'Title' => 'Image Marker',
                'insert_btn' => $this->lang->line('save_btn'),
                'close_btn' => $this->lang->line('close_btn')
            );
            
        } else {
            $response = array('Html' => '');
        }
        
        echo json_encode($response); exit;
    }
    
    public function customImageMarkerCtrl2() {
        
        if (Input::numeric('id')) {
            
            $result = html_entity_decode(Input::post('region'));
            $this->view->data = $result;
            $this->view->getPhoto = $this->model->getWhLocationPhotoModel2(Input::numeric('id'), Input::numeric('deviceId'));

            $response = array(
                'Html' => $this->view->renderPrint('system/link/imageMarker/imageMarkerControl', self::$viewPath),
                'Title' => 'Image Marker',
                'insert_btn' => $this->lang->line('save_btn'),
                'close_btn' => $this->lang->line('close_btn')
            );
            
        } else {
            $response = array('Html' => '');
        }
        
        echo json_encode($response); exit;
    }
    
    public function customImageMarkerCtrl3() {
        
        if (Input::numeric('id')) {
            
            $result = html_entity_decode(Input::post('region'));
            $this->view->data = $result;
            $this->view->getPhoto = $this->model->getWhLocationPhotoModel3(Input::numeric('id'));
            $this->view->locationId = '';

            $response = array(
                'Html' => $this->view->renderPrint('system/link/imageMarker/imageMarkerViewControl', self::$viewPath),
                'Title' => 'Image Marker',
                'insert_btn' => $this->lang->line('save_btn'),
                'close_btn' => $this->lang->line('close_btn')
            );
            
        } else {
            $response = array('Html' => '');
        }
        
        echo json_encode($response); exit;
    }
    
    public function customImageMarkerViewReferenceCtrl() {
        
        $p = Input::numeric('id');
        $location = Input::post('location');
        $this->view->postParams = Input::postData();
        $this->view->isWorkspace = Input::post('isworkspace');
        
        if ($location) {
            $this->view->getPhoto = $this->model->getAssetLocationPhotoViewModel($this->view->postParams);
        } else {
            $this->view->getPhoto = $this->model->getWhLocationPhotoViewReferenceModel($p);
        }
        
        $this->view->locationId = $p;
        $this->view->uniqId = getUID();
        
        if (empty($this->view->locationId)) {
            echo '<strong>ID талбарын утга хоосон байна!</strong>'; exit;
        } else {
            $this->view->render('system/link/imageMarker/imageMarkerViewReferenceControl', self::$viewPath);
        }
    }
    
    public function customImageMarkerCtrl4() {
        
        $p = Input::numeric('id');     
        
        $this->view->region = json_decode(html_entity_decode(Input::post('region'), ENT_QUOTES, 'UTF-8'), true);
        $this->view->isWorkspace = Input::post('isworkspace');
        
        $_POST['dataViewId'] = '1529649071315232';
        $_POST['location'] = 'region';
        $_POST['code'] = 'code';
        $_POST['name'] = 'name';
        $_POST['parentid'] = 'parentid';
        $_POST['location'] = 'region';
        $_POST['picturepath'] = 'picturepath';
        $_POST['profilephoto'] = 'profilephoto';
        
        $this->view->postParams = Input::postData();
        $location = Input::post('location');
        
        if ($location) {
            $this->view->getPhoto = $this->model->getAssetLocationPhotoViewModel($this->view->postParams);
        } else {
            $this->view->getPhoto = $this->model->getWhLocationPhotoViewReferenceModel($p);
        }
        
        $this->view->locationId = $p;
        $this->view->uniqId = getUID();
        
        if (empty($this->view->locationId)) {
            $response = array('Html' => '');
        } else {
            $response = array(
                'Html' => $this->view->renderPrint('system/link/imageMarker/imageMarkerViewReferenceControl4', self::$viewPath),
                'Title' => 'Image Marker',
                'uniqId' => $this->view->uniqId,
                'insert_btn' => $this->lang->line('save_btn'),
                'close_btn' => $this->lang->line('close_btn')
            );            
        }
        
        echo json_encode($response); exit;
    }
    
    public function customImageMarkerDrillDownViewReferenceCtrl() {
        
        $p = Input::numeric('locationId');
        $type = Input::post('type');
        $rlocationId = Input::post('rlocationId');
        $postParams = Input::post('postParams');
        
        $this->view->postParams = Arr::decode($postParams);
        $this->view->postParams['planpicture'] = Input::post('planPicture');
        $this->view->postParams['id'] = $p;
        $this->view->isWorkspace = issetVar($this->view->postParams['isworkspace']);

        if (isset($this->view->postParams['location'])) {
            $this->view->getPhoto = $this->model->getAssetLocationPhotoViewModel($this->view->postParams, $type, $p, $rlocationId);
        } else {
            $this->view->getPhoto = $this->model->getWhLocationPhotoViewReferenceModel($p);
        }
        
        $this->view->locationId = $p;

        if (!empty($this->view->getPhoto['url'])) {
            $response = array(
                'prevLocationId' => (isset($this->view->getPhoto['parentid']) && $type == 'back') ? $this->view->getPhoto['parentid'] : $this->view->locationId,
                'photo' => $this->view->getPhoto,
                'Html' => $this->view->renderPrint('system/link/imageMarker/drillDownImageMarkerViewReferenceControl', self::$viewPath)
            );
        } else {
            $response = array('Html' => '');
        }
        
        echo json_encode($response); exit;
    }
    
    public function customImageMarkerViewCtrl() {
        
        $this->view->getPhoto = $this->model->getWhLocationPhotoViewModel(Input::postData());
        $this->view->locationId = Input::post('locationId');
        
        $response = array(
            'Html' => $this->view->renderPrint('system/link/imageMarker/imageMarkerViewControl', self::$viewPath),
            'Title' => 'Image Marker View',
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function customImageMarkerDrillDownViewCtrl() {
        
        $p = Input::post('locationId');
        $this->view->getPhoto = $this->model->getWhLocationPhotoViewModel(Input::postData());
        $this->view->locationId = $p;

        if (!empty($this->view->getPhoto['url'])) {
            $response = array(
                'Html' => $this->view->renderPrint('system/link/imageMarker/drillDownImageMarkerViewControl', self::$viewPath),
                'photo' => Input::post('objectPhoto'),
                'itemKeyId' => Input::post('itemKeyId'),
                'prevLocation' => Input::post('prevLocationId'),
                'prevObjectPhoto' => Input::post('prevObjectPhoto'),
            );
        } else {
            $response = array('Html' => '');
        }
        echo json_encode($response); exit;
    }
    
    public function changeMetaFolder() {
        
        $postData = Input::postData();
        
        $this->view->countMeta = (isset($postData['metaDataIds']) ? count($postData['metaDataIds']) : 1);
        
        $response = array(
            'html' => $this->view->renderPrint('folder/sub/changeMetaFolder', self::$viewPath),
            'title' => 'Үзүүлэлтийн бүлэг солих',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function saveChangeMetaFolder() {
        
        $postData = Input::postData();
        $folderId = Input::param($postData['folderId']);
        $metaDataId = Input::param($postData['metaDataId']);
        $metaDataIds = (isset($postData['metaDataIds']) ? $postData['metaDataIds'] : null);
        
        $result = $this->model->saveChangeMetaFolderModel($folderId, $metaDataId, $metaDataIds);
        
        echo json_encode($result); exit;
    }
    
    public function processExport() {
        $postData = Input::postData();
        
        $this->view->countMeta = (isset($postData['metaDataIds']) ? count($postData['metaDataIds']) : 1);
        
        $response = array(
            'Html' => $this->view->renderPrint('common/export/processExport', self::$viewPath),
            'Title' => 'Дагасан мета',
            'download_btn' => $this->lang->line('download_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function clearCacheMetaFullExp() {
                
        $metaDataId = Input::post('metaDataId');
        $tmp_dir = Mdcommon::getCacheDirectory();
        $response = array('status' => 'success', 'message' => 'Амжилттай цэвэрлэгдлээ');
        
        if ($metaDataId == '_pfAppmenu') {
            
            $clearFiles = glob($tmp_dir."/*/ap/ap*.txt");
            
            if ($clearFiles) {
                foreach ($clearFiles as $clearFile) {
                    @unlink($clearFile);
                }
            }
            
            jsonResponse($response);
        }
        
        $this->load->model('mdmetadata', 'middleware/models/');
        
        $metaRow = $this->model->getMetaDataModel($metaDataId);
        $metaTypeId = $metaRow['META_TYPE_ID'];
        
        if ($metaTypeId == Mdmetadata::$metaGroupMetaTypeId) {
            
            $response = self::dvCacheClearByMetaId($metaDataId);
            
        } elseif ($metaTypeId == Mdmetadata::$businessProcessMetaTypeId) {
            
            $metaDataCode = $metaRow['META_DATA_CODE'];
            self::bpParamsClearCache($metaDataId, $metaDataCode, true);
            
        } elseif ($metaTypeId == Mdmetadata::$menuMetaTypeId) {
            
            $clearFiles = glob($tmp_dir."/*/le/leftmenu_*_".$metaDataId.".txt");
            
            if ($clearFiles) {
                foreach ($clearFiles as $clearFile) {
                    @unlink($clearFile);
                }
            }
        }
        
        jsonResponse($response);
    }
    
    public function clearCacheKpiTemplateById($templateId) {
        
        $tmp_dir = Mdcommon::getCacheDirectory();
        
        $kpiTemplateEventFiles = glob($tmp_dir."/*/kp/kpiFullExpressionEvent_".$templateId."_*.txt");
        foreach ($kpiTemplateEventFiles as $kpiTemplateEventFile) {
            @unlink($kpiTemplateEventFile);
        }
        
        $kpiTemplateVarFncFiles = glob($tmp_dir."/*/kp/kpiFullExpressionVarFnc_".$templateId."_*.txt");
        foreach ($kpiTemplateVarFncFiles as $kpiTemplateVarFncFile) {
            @unlink($kpiTemplateVarFncFile);
        }
        
        $kpiTemplateBeforeSaveFiles = glob($tmp_dir."/*/kp/kpiFullExpressionBeforeSave_".$templateId."_*.txt");
        foreach ($kpiTemplateBeforeSaveFiles as $kpiTemplateBeforeSaveFile) {
            @unlink($kpiTemplateBeforeSaveFile);
        }
        
        $kpiTemplateWithoutEventFiles = glob($tmp_dir."/*/kp/kpiFullExpressionWithoutEvent_".$templateId.".txt");
        foreach ($kpiTemplateWithoutEventFiles as $kpiTemplateWithoutEventFile) {
            @unlink($kpiTemplateWithoutEventFile);
        }
        
        $kpiTemplateEventFiles = glob($tmp_dir."/*/kp/kpiFullExpressionEvent_".$templateId.".txt");
        foreach ($kpiTemplateEventFiles as $kpiTemplateEventFile) {
            @unlink($kpiTemplateEventFile);
        }
        
        $kpiTemplateVarFncFiles = glob($tmp_dir."/*/kp/kpiFullExpressionVarFnc_".$templateId.".txt");
        foreach ($kpiTemplateVarFncFiles as $kpiTemplateVarFncFile) {
            @unlink($kpiTemplateVarFncFile);
        }
        
        $kpiTemplateBeforeSaveFiles = glob($tmp_dir."/*/kp/kpiFullExpressionBeforeSave_".$templateId.".txt");
        foreach ($kpiTemplateBeforeSaveFiles as $kpiTemplateBeforeSaveFile) {
            @unlink($kpiTemplateBeforeSaveFile);
        }
        
        $kpiTemplateAfterSaveFiles = glob($tmp_dir."/*/kp/kpiFullExpressionAfterSave_".$templateId.".txt");
        foreach ($kpiTemplateAfterSaveFiles as $kpiTemplateAfterSaveFile) {
            @unlink($kpiTemplateAfterSaveFile);
        }
        
        $indicators = glob($tmp_dir."/*/kp/kpi_".$templateId."_*.txt");
        foreach ($indicators as $indicator) {
            @unlink($indicator);
        } 
        
        $kpiUserConfigsMerges = glob($tmp_dir."/*/dv/dvUserConfigMergeCols2_".$templateId."_*.txt");
        foreach ($kpiUserConfigsMerges as $kpiUserConfigsMerge) {
            @unlink($kpiUserConfigsMerge);
        }                  
        
        return true;
    }
    
    public function clearCacheKpiTemplate() {
        
        $templateId = Input::post('templateId');
        
        self::clearCacheKpiTemplateById($templateId);
        
        echo json_encode(array('status' => 'success')); exit;
    }
    
    public function clearCacheFiscalPeriod() {
        
        $tmp_dir = Mdcommon::getCacheDirectory();
        
        $tmpFiles = glob($tmp_dir."/*/ge/ge*.txt");
        foreach ($tmpFiles as $tmpFile) {
            @unlink($tmpFile);
        }
        
        echo json_encode(array('status' => 'success')); exit;
    }
    
    public function setDvHeaderFooterEditor() {
        
        $metaDataId = Input::numeric('metaDataId');
        
        $this->view->metaList = array();
        
        if ($metaDataId != null) {
            $this->load->model('mdmetadata', 'middleware/models/');
            $this->view->metaList = $this->model->getOnlyMetaDataByGroupModel($metaDataId);
        }
        
        $this->load->model('mdmeta', 'middleware/models/');
        
        if (Input::post('dvExportHtml') == '0') {
            
            $htmlRow = $this->model->getDvHeaderFooterHtml($metaDataId);
            $this->view->dvExportHeader = Arr::get($htmlRow, 'HEADER_HTML');
            $this->view->dvExportFooter = Arr::get($htmlRow, 'FOOTER_HTML');
            
        } else {
            $this->view->dvExportHeader = Input::postNonTags('dvExportHeader');
            $this->view->dvExportFooter = Input::postNonTags('dvExportFooter');
        }
        
        $this->view->sysKeywords = (new Mdstatement())->sysKeywords();

        $response = array(
            'Html' => $this->view->renderPrint('system/link/group/hf_tinymce_editor', self::$viewPath),
            'Title' => 'Header Footer',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function isLock() {
        
        $id = Input::post('id');
        $response = $this->ws->getJsonByCurl(self::getLockServerAddr().'isLock/'.$id);
        
        echo $response; exit;
    }
    
    public function lockMeta() {
        
        $this->view->id = Input::post('id');
        $this->view->metaRow = (new Mdmetadata())->getMetaData($this->view->id);
        
        $response = array(
            'html' => $this->view->renderPrint('system/part/lock/lock', self::$viewPath),
            'title' => 'Түгжих',
            'lock_btn' => 'Түгжих',
            'close_btn' => $this->lang->line('close_btn')
        );
        
        echo json_encode($response); exit;
    }
    
    public function locking() {
        
        $id       = Input::post('id');
        $lockName = Input::post('lockName');
        $lockPass = Input::post('lockPass');
        
        $data = array(
            'id'       => $id, 
            'lockName' => $lockName, 
            'lockPass' => $lockPass
        );
        
        $this->load->model('mdmetadata', 'middleware/models/');
        
        $metaRow = $this->model->getMetaDataModel($id);
        
        if ($metaRow['META_TYPE_ID'] == Mdmetadata::$businessProcessMetaTypeId) {
            
            $this->load->model('mdmeta', 'middleware/models/');
            $data['childMetas'] = $this->model->getProcessChildMetasByLock($id);
            
        } elseif ($metaRow['META_TYPE_ID'] == Mdmetadata::$metaGroupMetaTypeId) {
            
            $this->load->model('mdmeta', 'middleware/models/');
            $data['childMetas'] = $this->model->getDVChildMetasByLock($id);
        }
        
        $response = $this->ws->redirectPost(self::getLockServerAddr().'locking', $data);
        
        echo $response; exit;
    }
    
    public function locked() {
        
        $this->view->id = Input::post('id');
        $this->view->personName = Input::post('personName');
        $this->view->metaRow = (new Mdmetadata())->getMetaData($this->view->id);
        
        $response = array(
            'html' => $this->view->renderPrint('system/part/lock/locked', self::$viewPath),
            'title' => 'Түгжээ',
            'unlock_btn' => 'Түгжээг болих',
            'access_btn' => 'Эрх өгөх',
            'close_btn' => $this->lang->line('close_btn')
        );
        
        echo json_encode($response); exit;
    }
    
    public function unlockMeta() {
        
        $this->view->id = Input::post('id');
        
        $response = array(
            'html' => $this->view->renderPrint('system/part/lock/unlock', self::$viewPath),
            'title' => 'Түгжээ',
            'unlock_btn' => 'Түгжээг болих'
        );
        
        echo json_encode($response); exit;
    }
    
    public function unlocking() {
        
        $data = array(
            'id' => Input::post('id'), 
            'lockName' => Input::post('lockName'), 
            'lockPass' => Input::post('lockPass'), 
            'descr' => Input::post('descr')
        );
        
        $response = $this->ws->redirectPost(self::getLockServerAddr().'unlockingMeta', $data);
        
        echo $response; exit;
        
        /*$id = Input::post('id');
        $lockName = Input::post('lockName');
        $lockPass = Input::post('lockPass');
        
        $response = $this->ws->getJsonByCurl(self::getLockServerAddr().'unlocking/'.$id.'/'.$lockName.'/'.$lockPass);
        
        echo $response; exit;*/
    }
    
    public function shareLockMeta() {
        
        $this->load->model('mduser', 'middleware/models/');
        
        $this->view->id = Input::post('id');
        $this->view->users = $this->model->getSystemActiveUsers();
        
        $response = array(
            'html' => $this->view->renderPrint('system/part/lock/sharelock', self::$viewPath),
            'title' => 'Эрх өгөх'
        );
        
        echo json_encode($response); exit;
    }
    
    public function sharelocking() {
        
        $data = array(
            'id' => Input::post('id'), 
            'lockName' => Input::post('lockName'), 
            'lockPass' => Input::post('lockPass'), 
            'userId' => Input::post('userId'), 
            'endTime' => Input::post('endTime'), 
            'descr' => Input::post('descr')
        );
        
        $response = $this->ws->redirectPost(self::getLockServerAddr().'sharelocking', $data);
        
        echo $response; exit;
    }
    
    public function checkLock() {
        
        // if (Config::getFromCache('CONFIG_IGNORE_CHECK_LOCK')) {
        
        $response = array('isLocked' => false);
        
        if (Config::getFromCache('is_dev')) {
            $response['isDev'] = 1;
        }
        
        echo json_encode($response); exit;
        // }
        
        $userId = Ue::sessionUserId();
        $id = Input::numeric('metaDataId');
        
        $response = $this->ws->getJsonByCurl(self::getLockServerAddr().'checkLock/'.$id.'/'.$userId);
        
        $json = json_decode($response, true);

        if ($json['isLocked'] == true || $json['isLocked'] == 'true') {
            
            $this->view->metaRow = (new Mdmetadata())->getMetaData($id);
            $this->view->personName = $json['personName'];
            
            $response = array(
                'html' => $this->view->renderPrint('system/part/lock/locked', self::$viewPath),
                'title' => 'Түгжсэн',
                'close_btn' => $this->lang->line('close_btn'), 
                'isLocked' => true
            );
            
            $response = json_encode($response, JSON_UNESCAPED_UNICODE);
        } 
        
        echo $response; exit;
    }
    
    public function multiLock() {
        $result = $this->model->multiLockModel();
        var_dump($result); exit;
    }
    
    public function gethash($data) {
        echo Hash::createMD5reverse($data);
    }
    
    public function getnewhash($data) {
        echo Hash::create('sha256', $data);
    }
    
    public function getmetalockhash($data) {
        echo Crypt::encrypt($data, 'md');
    }
    
    public function showdate() {
        echo Date::currentDate('Y-m-d H:i:s');
    }
    
    public function getThemeBook($dtlThemeId = '') {
        if ($dtlThemeId) {
            return $this->db->GetRow("SELECT ID, THEME_CODE, THEME_NAME, BACKGROUND_COLOR, FILE_PATH FROM META_THEME_BOOK WHERE ID = $dtlThemeId");
        } else {
            return $this->db->GetAll("SELECT ID, THEME_CODE, THEME_NAME, BACKGROUND_COLOR, FILE_PATH FROM META_THEME_BOOK WHERE IS_ACTIVE = 1");
        }
    }
    
    public function setProcessLookupFieldsMapping() {
        
        $postData = Input::postData();
        
        $lookupMetaDataId = $postData['lookupMetaDataId'];
        $isKey = ($postData['isKey'] == 'false' ? false : true);
        
        if ($lookupMetaDataId != '') {
            
            $mainMetaDataId = $postData['mainMetaDataId'];
            $this->view->paramName = $postData['paramName'];
            $this->view->paramPath = $postData['paramPath'];
            
            if ($isKey) {
                $this->view->fieldMappingLookupFieldPath = 'fieldMappingLookupFieldPathKey';
                $this->view->fieldMappingParamFieldPath = 'fieldMappingParamFieldPathKey';
            } else {
                $this->view->fieldMappingLookupFieldPath = 'fieldMappingLookupFieldPath';
                $this->view->fieldMappingParamFieldPath = 'fieldMappingParamFieldPath';
            }
            
            $this->load->model('mdobject', 'middleware/models/');
            
            $this->view->lookupParams = $this->model->getDataViewGridBodyDataModel($lookupMetaDataId);
            $this->view->tempField = false;
            
            if ($postData['fieldsMapping'] != '') {
                $this->view->tempField = true;
                parse_str($postData['fieldsMapping'], $this->view->fieldsMapping);
                $html = $this->view->renderPrint('system/link/process/v2/fieldMapping/groupLookupFieldMapping', self::$viewPath);
            } else {
                $this->load->model('mdmeta', 'middleware/models/');
                $this->view->fieldsMapping = $this->model->getProcessLookupFieldsMappingModel($mainMetaDataId, $this->view->paramPath, $isKey);
                $html = $this->view->renderPrint('system/link/process/v2/fieldMapping/groupLookupFieldMapping', self::$viewPath);
            }
            
        } else {
            
            if ($isKey) {
                $message = 'Та Lookup key үзүүлэлт тохируулна уу!';
            } else {
                $message = 'Та Lookup үзүүлэлт тохируулна уу!';
            }
            
            $html = html_tag('div', array('class' => 'alert alert-info'), $message);
        }
        
        $response = array(
            'html' => $html,
            'title' => 'Lookup Field Mapping',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function systemCacheClearForm() {
        
        $this->view->isUrlAuthenticate = Session::isCheck(SESSION_PREFIX . 'isUrlAuthenticate');
        
        if ($this->view->isUrlAuthenticate && Session::get(SESSION_PREFIX . 'loggedUrlAuthenticate')) {
            $this->view->isUrlAuthenticate = false;
        }
        
        $response = array(
            'html' => $this->view->renderPrint('system/part/systemCacheClearForm', self::$viewPath),
            'title' => 'Clear cache',
            'clear_btn' => $this->lang->line('clear_btn'), 
            'close_btn' => $this->lang->line('close_btn')
        );
        
        echo json_encode($response); exit;
    }
    
    public function systemCacheClear() {
        
        $postData = Input::postData();
        $tmp_dir = Mdcommon::getCacheDirectory();
        
        $clearFiles = array();
        
        if (isset($postData['isSystem'])) {
            
            $sysFiles = glob($tmp_dir."/*/sy/sy*.txt");
            $clearFiles = array_merge_recursive($clearFiles, $sysFiles);
            
            $this->model->serviceReloadConfigModel();
        }
        
        if (isset($postData['isMaster'])) {
            
            $tmpFiles1 = glob($tmp_dir."/*/fi/fi*.txt");
            $tmpFiles2 = glob($tmp_dir."/*/ge/ge*.txt");
            
            $clearFiles = array_merge_recursive($clearFiles, $tmpFiles1, $tmpFiles2);
        }
        
        if (isset($postData['isMenu'])) {
            
            $tmpFiles1 = glob($tmp_dir."/*/ap/ap*.txt");
            $tmpFiles2 = glob($tmp_dir."/*/le/le*.txt");
            $tmpFiles3 = glob($tmp_dir."/*/to/to*.txt");
            
            $clearFiles = array_merge_recursive($clearFiles, $tmpFiles1, $tmpFiles2, $tmpFiles3);
        }
        
        if (isset($postData['isKpi'])) {
            
            $tmpFiles = glob($tmp_dir."/*/kp/kp*.txt");
            $clearFiles = array_merge_recursive($clearFiles, $tmpFiles);
        }
        
        if (isset($postData['isDv'])) {
            
            $tmpFiles = glob($tmp_dir."/*/dv/dv*.txt");
            $clearFiles = array_merge_recursive($clearFiles, $tmpFiles);
        }
        
        if (isset($postData['isProcessConfig'])) {
            
            $tmpFiles = glob($tmp_dir."/*/bp/bp*.txt");
            $clearFiles = array_merge_recursive($clearFiles, $tmpFiles);
        }
        
        if (isset($postData['isProcessExpression'])) {
            
            $tmpFiles = glob($tmp_dir."/*/pr/pr*.txt");
            $clearFiles = array_merge_recursive($clearFiles, $tmpFiles);
        }
        
        if (count($clearFiles)) {
            foreach ($clearFiles as $clearFile) {
                @unlink($clearFile);
            }
        }
        
        jsonResponse(array('status' => 'success', 'message' => 'Амжилттай цэвэрлэгдлээ'));
    }
    
    public function checkUrlAuthLoginForm() {
        
        $metaId = Input::numeric('metaId');
        
        if ($metaId) {
            $this->load->model('mdmetadata', 'middleware/models/');
            $this->view->metaRow = $this->model->getMetaDataModel($metaId);
        }
        
        $response = array(
            'html' => $this->view->renderPrint('system/part/auth/login', self::$viewPath),
            'title' => 'Authenticate Login',
            'login_btn' => $this->lang->line('login_btn'), 
            'close_btn' => $this->lang->line('close_btn')
        );
        
        jsonResponse($response);
    }
    
    public function checkUrlAuthLogin() {
        
        $userName = Input::post('unlockUserName');
        $passWord = Input::post('unlockUserPass');
        
        if (Uri::validateUserPassword($userName, $passWord)) {
            $response = array('status' => 'success', 'message' => 'success');
        } else {
            $response = array('status' => 'error', 'message' => 'Access denied!');
        }
        
        echo json_encode($response); exit;
    }
    
    public function setCacheFullExpression() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->runModeData = array(
            array(
                'code' => 'load_first', 
                'name' => 'Load first'
            ), 
            array(
                'code' => 'add_row', 
                'name' => 'Add row'
            ), 
            array(
                'code' => 'add_multi', 
                'name' => 'Add multi rows'
            ),  
            array(
                'code' => 'function', 
                'name' => 'Function'
            ), 
            array(
                'code' => 'before_save', 
                'name' => 'Before save'
            ),
            array(
                'code' => 'before_save_rows', 
                'name' => 'Before save /loop/'
            ),
        );
        
        if (Input::postCheck('runMode')) {
            $this->view->runMode = Input::post('runMode');
            $this->view->groupPath = Input::post('groupPath');
            $this->view->code = Input::post('code');
            $this->view->descr = Input::post('descr');
            $this->view->expression = Input::postNonTags('expression');
        } else {
            $this->view->runMode = null;
            $this->view->groupPath = null;
            $this->view->code = null;
            $this->view->descr = null;
            $this->view->expression = null;
        }
        
        $response = array(
            'html' => $this->view->renderPrint('system/link/process/fullexpression/setCacheFullExpression', self::$viewPath),
            'title' => 'Cache expression',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }    
    
    public function dvSqlViewEditor() {
        
        $this->view->dialogId = $_POST['dialogId'];
        $this->view->query = Input::postNonTags('query');
        $this->view->postgreSql = Input::postNonTags('postgreSql');
        $this->view->msSql = Input::postNonTags('msSql');
        
        $response = array(
            'html' => $this->view->renderPrint('system/link/group/dataViewSqlViewEditor', self::$viewPath),
            'title' => 'Query editor',
            'format_btn' => $this->lang->line('Formatting'),
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function dvQueryEditor() {
        
        $this->view->metaId = Input::numeric('metaId');
        $this->load->model('mdmetadata', 'middleware/models/');
        
        if ($changeLogResponse = $this->model->isCheckChangeLogMetaModel($this->view->metaId)) {
            echo json_encode($changeLogResponse, JSON_UNESCAPED_UNICODE); exit;
        }
        
        $this->load->model('mdobject', 'middleware/models/');
        $queryRow = $this->model->getDVMainQueriesModel($this->view->metaId);
        
        $this->view->query = Mdmetadata::objectDeCompress($queryRow['TABLE_NAME']);
        $this->view->postgreSql = Mdmetadata::objectDeCompress($queryRow['POSTGRE_SQL']);
        $this->view->msSql = Mdmetadata::objectDeCompress($queryRow['MS_SQL']);
        
        $html = $this->view->renderPrint('system/link/group/dataViewSqlViewEditor', self::$viewPath);
        echo json_encode(array('html' => $html, 'status' => 'success'), JSON_UNESCAPED_UNICODE); exit;
    }
    
    public function dvQuerySave() {
        $response = $this->model->dvQuerySaveModel();
        jsonResponse($response);
    }
    
    public function dmSqlViewEditor() {
        
        $this->view->dialogId = $_POST['dialogId'];
        $this->view->query = Input::postNonTags('query');
        
        $response = array(
            'html' => $this->view->renderPrint('system/link/dm/dataViewSqlViewEditor', self::$viewPath),
            'title' => 'Query view',
            'format_btn' => $this->lang->line('Formatting'),
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function sqlFormatting() {
        includeLib('Formatter/SqlFormatter');
        $query = Security::jsReplacer($_POST['query']);
        echo SqlFormatter::format($query, false); 
    }
    
    public function changePassword()
    {
        $row = $this->model->getMetaUnLockModel();

        if ($row['PASSWORD_HASH'] == Hash::createMD5reverse(Input::post('currentPassword'))) {
            
            if (Input::post('newPassword') == Input::post('confirmPassword')) {
                $data = array(
                    "PASSWORD_HASH" => Hash::createMD5reverse(Input::post('confirmPassword'))
                );
                $result = $this->model->updateMetaUnLockPasswordModel($data);
                
                if ($result) {
                    $response = array(
                        'status' => 'success',
                        'message' => $this->lang->line('msg_edit_success')
                    );
                } else {
                    $response = array(
                        'status' => 'error',
                        'message' => $this->lang->line('msg_save_error')
                    );
                }
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => $this->lang->line('user_confirm_password_error')
                );
            }
        } else {
            $response = array(
                'status' => 'error',
                'message' => $this->lang->line('user_current_password_error')
            );
        }
        echo json_encode($response); exit;
    }   
    
    public function bpFieldExpressionEditor() {
        
        $this->view->expression = $_POST['expression'];
        $this->view->fieldPath = Input::post('fieldPath');
        $this->view->isJson = Input::numeric('isJson');
        
        $tagsSource = Input::post('tagsSource');
        $sourceId = Input::post('sourceId');
        
        if ($tagsSource == 'kpiTemplate' && $sourceId != '') {
            
            $this->load->model('mdform', 'middleware/models/');
            $this->view->paths = $this->model->getKpiIndicatorFactsByTemplateId($sourceId);
            
        } elseif ($tagsSource == 'kpiIndicator' && $sourceId != '') {
            
            $this->load->model('mdform', 'middleware/models/');
            $this->view->paths = $this->model->kpiSetMultiPathConfigModel($sourceId);
        }
        
        $response = array(
            'html' => $this->view->renderPrint('common/sub/expressionEditor', self::$viewPath),
            'title' => 'Expression editor',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function processDtlTransferProcessInlineEdit() {

        $this->view->params = Input::postData();
        parse_str($this->view->params['transferProcessData'], $this->view->paramsConfigs);
        
        $this->view->groupChildDatas = $this->model->getGroupChildMetasNotGroupType($this->view->params['metaDataId']);
        $this->view->defaultProcessInputParam = $this->model->getMetaProcessParamMetaInlineEditModel($this->view->params['processMetaDataId']);

        $response = array(
            'Html' => $this->view->renderPrint('system/link/group/processDtlTransferProcessInlineEdit', self::$viewPath),
            'Title' => 'Set Inline Edit Map Config',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }    
    
    public function getDMRowProcess($mainMetaDataId, $processMetaDataId, $basket = false) {
        $this->load->model('mdmeta', 'middleware/models/');
        return $this->model->getDMRowProcessModel($mainMetaDataId, $processMetaDataId, $basket);
    }    
    
    public static function getLockServerAddr() {

        $configLockServerAddr = Config::getFromCache('lockServerAddress');
        
        if ($configLockServerAddr) {
            return $configLockServerAddr;
        } else {
            return self::$lockServerAddr;
        }
    }

    public function createDmTable() {
        jsonResponse($this->model->createDmTableModel());
    }
    
    public function setProcessConfigsMultiField() {
        $this->view->maskData = (new Mdmetadata())->getMetaFieldPattern();
        
        if (Input::postCheck('isProcess')) {
            $viewName = 'setProcessConfigsMultiField';
        } else {
            $viewName = 'setMetaGroupConfigsMultiField';
        }
        
        $this->view->render('system/part/'.$viewName, self::$viewPath);
    }
    
    public function clipboardMetaPaste() {
        $response = $this->model->clipboardMetaPasteModel();
        jsonResponse($response);
    }
    
    public static function isAccessMetaImport() {
        $sessionUserId = Ue::sessionUserId();
        return ((defined('CONFIG_META_IMPORT') && CONFIG_META_IMPORT) || $sessionUserId == '144617860666271' || $sessionUserId == '1479354350613' || $sessionUserId == '1453998999913' || $sessionUserId == '1479354372402' || $sessionUserId == '1479354351113');
    }
    
    public static function isAccessMetaImportCopy() {
        $sessionUserId = Ue::sessionUserId();
        return ($sessionUserId == '144617860666271' || $sessionUserId == '1479354372402' || $sessionUserId == '1479354351113');
    }
    
    public static function isAddMetaAccess() {
        $hash = Hash::createMD5reverse(Uri::domain());
        return Config::getFromCache($hash) ? true : false;
    }
    
    public static function isAccessMetaSendTo() {
        return Config::getFromCache('metaSendToDomains');
    }
    
    public function customImageMarkerWithDvCtrl() {
        
        $p = Input::post('id');
        $location = Input::post('location');
        $this->view->postParams = Input::postData();
        $this->view->isWorkspace = Input::post('isworkspace');
        
        $this->view->getPhoto = [];
        $this->view->getPhoto['url'] = Input::post('picture');
        
        $this->view->locationId = $p;
        $this->view->uniqId = getUID();

        $this->view->render('system/link/imageMarker/imageMarkerViewReferenceDvControl', self::$viewPath);
    }    
    
}
