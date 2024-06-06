<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdmenu Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Menu
 * @author	B.Och-Erdene <ocherdene@interactive.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdmenu
 */
class Mdmenu extends Controller {

    private static $viewPath = 'middleware/views/menu/';

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }

    public function firstLevelMenu() {
        $this->load->model('appmenu');
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $this->view->appMenuName = (defined('CONFIG_APPMENU_NAME') ? CONFIG_APPMENU_NAME : 'ERP Modules');
        
        $this->view->menuList = $this->model->getMenuListModel();
        
        if (defined('CONFIG_TOP_MENU') && CONFIG_TOP_MENU) {
            $this->view->render('firstLevelTopMenu', self::$viewPath);
        } else {
            $this->view->render('firstLevelMenu', self::$viewPath);
        }
    }
    
    public function childMenu() {
        $parentId = Input::post('parentId');
        echo (new Mdmeta())->leftMetaMenuModule(false, $parentId, 'close_all');
    }
    
    public function getLeftMenuCount() {
        $this->load->model('mdmeta', 'middleware/models/');
        
        $countMetaDataIds = explode(',', trim(Input::post('countMetaDataIds'), ','));
        parse_str(Input::post('listmetadatacriteria'), $criteria);
        $result = array();
        
        foreach ($countMetaDataIds as $countMetaDataId) {
            $leftMenuCount = $this->model->getLeftMenuCountModel($countMetaDataId, $criteria);
            $result[$countMetaDataId] = $leftMenuCount;
        }

        echo json_encode($result); exit;
    }
    
    public function getTopMenuByModuleId() {
        
        $_POST['ignoreModuleSidebar'] = 1;
        
        $moduleId = Input::post('moduleId');
        $isKpiIndicator = Input::numeric('isKpiIndicator');
        
        if ($isKpiIndicator == 1) {
            $menuRender = (new Mdmenu())->topKpiMenuModule($moduleId);
        } else {
            $menuRender = (new Mdmeta())->topMetaMenuModule($moduleId);
        }
        
        if (isset($menuRender['menuHtml'])) {
            $quickMenu = (new Mduser())->renderQuickMenu($moduleId);
            echo json_encode(array('topMenu' => $menuRender['menuHtml'], 'quickMenu' => $quickMenu, 'openMenuId' => issetParam($menuRender['openMenuId'])));
        } else {
            echo json_encode(array('topMenu' => ''));
        }
        exit;
    }
    
    public function topKpiMenuModule($moduleId) {
        
        $this->load->model('mdmenu', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $moduleId = Input::param($moduleId);
        $sideBarHtml = '';
        $moduleName = 'Empty';
        $this->view->menuHtml = null;
        
        $menuData = $this->model->getKpiMenuListByParentIdCacheModel($moduleId);
        $moduleInfo = $this->model->getKpiIndicatorModuleRowModel($moduleId);
            
        if ($moduleName = issetParam($moduleInfo['NAME'])) {

            if ($menuData) {
                $this->view->menuHtml = $this->model->topKpiMenuModuleRenderModel($moduleId, $moduleName, $menuData);  
            } else {
                $this->view->menuHtml = '<div style="height: 30px"></div>';
            }
            
            $this->load->model('mdmeta', 'middleware/models/');
            
            $sideBarHtml = $this->model->moduleSidebarKpiMenuModel($moduleId, true);  
        }  
        
        return array(
            'moduleName' => $moduleName, 
            'menuHtml' => $this->view->menuHtml, 
            'sideBarHtml' => $sideBarHtml
        );
    }
    
}
