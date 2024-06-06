<?php

if (!defined('_VALID_PHP'))
    exit('Direct access to this location is not allowed.');

/**
 * Mdintranet Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Intranet
 * @author	Batbilguun.g <batbilguun.g@interactive.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Intranet
 */

class Mdintranet extends Controller {

    const viewPath = 'middleware/views/asset/';
    const glviewPath = 'middleware/views/generalledger/';

    private static $viewPath2 = 'middleware/views/asset/government/';
    public static $faAssetDeprDataView = 'FA_ASSET_BOOK_DEPR_LIST';
    public static $faAssetDeprFilterDataView = 'FA_ASSET_BOOK_DEPR_LIST_CACHE';
    public static $faAssetDeprObjectId = 20005;
    public static $faAssetDeprBookTypeId = 15;

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }

    public function reloadContentbar() {
        $typeId = Input::post('typeId');
        $categoryId = Input::post('categoryId');
        var_dump($typeId);
        var_dump($categoryId);
        $allContent = $this->model->getIntranetContentModel($typeId, $categoryId);
        var_dump($allContent);
    }

    public function intranet2() {
        $this->view->css = AssetNew::metaCss();
        $this->view->fullUrlCss = array('middleware/assets/css/intranet/style.css');
        $this->view->fullUrlJs = array('assets/core/js/plugins/media/gallery.js');
        $this->view->render('header');
        $this->view->render('intranet2', self::$viewPath2);
        $this->view->render('footer');
    }

    public function intranet3() {
        $this->view->render('header');
        $this->view->render('intranet3', self::$viewPath2);
        $this->view->render('footer');
    }

    public function intranet4() {
        $this->view->render('header');
        $this->view->render('intranet4', self::$viewPath2);
        $this->view->render('footer');
    }

    public function intranet5() {
        $this->view->render('header');
        $this->view->render('intranet5', self::$viewPath2);
        $this->view->render('footer');
    }

    public function intranet7() {
        $this->view->render('header');
        $this->view->render('intranet7', self::$viewPath2);
        $this->view->render('footer');
    }

    public function intranet8() {
        $this->view->render('header');
        $this->view->render('intranet8', self::$viewPath2);
        $this->view->render('footer');
    }

    public function intranet11() {
        $this->view->render('header');
        $this->view->render('intranet11', self::$viewPath2);
        $this->view->render('footer');
    }

    public function intranet12() {
        $this->view->render('header');
        $this->view->render('intranet12', self::$viewPath2);
        $this->view->render('footer');
    }

    public function intranet13() {
        $this->view->title = 'Хянах самбар';
        $this->view->uniqId = getUID();
        
        if (!is_ajax_request()) {
            $this->view->css = array_unique(array_merge(array('custom/css/vr-card-menu.css'), AssetNew::metaCss()));
            $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));
            $this->view->fullUrlJs = array(
                'assets/core/js/plugins/visualization/echarts/echarts.min.js',
                'assets/core/js/plugins/charts/echarts/pies_donuts.js',
                'assets/core/js/plugins/charts/echarts/columns_waterfalls.js',
                'assets/core/js/plugins/charts/echarts/areas.js',
            );
            
            $this->view->fullUrlCss = array('middleware/assets/css/intranet/style.css');
            $this->view->render('header');
            $this->view->render('intranet13', self::$viewPath2);
            $this->view->render('footer');
        } else {
            $response = array(
                'Html' => $this->view->renderPrint('intranet13', self::$viewPath2),
                'Title' => $this->view->title,
                'uniqId' => $this->view->uniqId
            );
            
            echo json_encode($response);
            exit;
        }
    }

    public function intranet14() {
        $this->view->render('header');
        $this->view->render('intranet14', self::$viewPath2);
        $this->view->render('footer');
    }

    public function intranet_file() {
        $this->view->fullUrlCss = array('middleware/assets/css/intranet/style.css');
        $this->view->render('header');
        $this->view->render('intranet_file', self::$viewPath2);
        $this->view->render('footer');
    }

    public function hr_dashboard() {
        $this->view->render('header');
        $this->view->render('hr_dashboard', self::$viewPath2);
        $this->view->render('footer');
    }

    public function cms_meeting_list() {
        $this->view->render('header');
        $this->view->render('cms_meeting_list', self::$viewPath2);
        $this->view->render('footer');
    }

    public function full_calendar() {
        $this->view->render('header');
        $this->view->render('full_calendar', self::$viewPath2);
        $this->view->render('footer');
    }

    public function gradient_colors() {
        $this->view->render('header');
        $this->view->render('gradient_colors', self::$viewPath2);
        $this->view->render('footer');
    }

    public function intranet_forward() {
        $this->view->fullUrlCss = array('middleware/assets/css/intranet/style.css');
        $this->view->render('header');
        $this->view->render('intranet_forward', self::$viewPath2);
        $this->view->render('footer');
    }
    
    public function intranet_det() {
        $this->view->fullUrlCss = array('middleware/assets/css/intranet/style.css');
        $this->view->render('header');
        $this->view->render('detail_get_poll', self::$viewPath2);
        $this->view->render('footer');
    }

    public function eaService() {
        $this->load->model('mdasset', 'middleware/models/');

        $this->view->uniqId = getUID();

        if (!is_ajax_request()) {
            $this->view->css = array_unique(array_merge(array('custom/css/vr-card-menu.css'), AssetNew::metaCss()));
            $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));
            $this->view->render('header');
        }

        $this->view->metaDataId = '1565788142980';
        $this->view->metaDataId1 = '1565751393181';
        $this->view->leftSideBarMenu = $this->model->getLeftSidebarModel('1565788142980');

        $this->view->rightSideBarMenuChild = $this->model->getIntranedSidebarChildModel();
        $this->view->getIntranetAllContent = $this->model->getIntranetAllContentModel();

        $mdObjectCtrl = Controller::loadController('Mdobject', 'middleware/controllers/');
        $this->view->dataViewHeaderRealData = $mdObjectCtrl->dataViewHeaderDataCtl($this->view->metaDataId);

        $this->view->dataViewHeaderData = $mdObjectCtrl->findCriteria('1565751393181', $this->view->dataViewHeaderRealData);


        $this->view->render('ea/easervice', self::viewPath);
    }

    public function eaObject() {
        $this->load->model('mdasset', 'middleware/models/');

        $this->view->uniqId = getUID();

        if (!is_ajax_request()) {
            $this->view->css = array_unique(array_merge(array('custom/css/vr-card-menu.css'), AssetNew::metaCss()));
            $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));
            $this->view->render('header');
        }

        $this->view->metaDataId = '1565751393112';
        $this->view->metaDataId1 = '1565751393181';
        $this->view->leftSideBarMenu = $this->model->getLeftSidebarModel('1565751393112');


        $this->view->rightSideBarMenuChild = $this->model->getIntranedSidebarChildModel();
        $this->view->getIntranetAllContent = $this->model->getIntranetAllContentModel();

        $mdObjectCtrl = Controller::loadController('Mdobject', 'middleware/controllers/');
        $this->view->dataViewHeaderRealData = $mdObjectCtrl->dataViewHeaderDataCtl($this->view->metaDataId);

        $this->view->dataViewHeaderData = $mdObjectCtrl->findCriteria('1565751393181', $this->view->dataViewHeaderRealData);


        $this->view->render('ea/eaobject', self::viewPath);
    }

    public function eaRepository() {
        $this->load->model('mdasset', 'middleware/models/');

        $this->view->uniqId = getUID();

        if (!is_ajax_request()) {
            $this->view->css = array_unique(array_merge(array('custom/css/vr-card-menu.css'), AssetNew::metaCss()));
            $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));
            $this->view->render('header');
        }

        $this->view->metaDataId = '1559891180690';
        $this->view->metaDataId1 = '1559891180690';
        $this->view->leftSideBarMenu = $this->model->getLeftSidebarModel('1559891180690');


        $this->view->rightSideBarMenuChild = $this->model->getIntranedSidebarChildModel();
        $this->view->getIntranetAllContent = $this->model->getIntranetAllContentModel();

        $mdObjectCtrl = Controller::loadController('Mdobject', 'middleware/controllers/');
        $this->view->dataViewHeaderRealData = $mdObjectCtrl->dataViewHeaderDataCtl($this->view->metaDataId);
        $this->view->dataViewHeaderData = $mdObjectCtrl->findCriteria($this->view->metaDataId, $this->view->dataViewHeaderRealData);

        $this->view->render('ea/repository', self::viewPath);
    }

    public function getSubMenuRender() {
        $postData = Input::postData();
        $metadata = Input::post('metadata');
        (String) $Html = "";
        $menuData = $this->model->getLeftSidebarModel($metadata, $postData['id']);

        if ($menuData) {
            foreach ($menuData as $key => $row) {
                $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                $Html .= '<li class="nav-item">
                            <a href="javascript:;" 
                            data-row="' . $rowJson . '"
                            li-status="closed"
                            onclick="getSubMenuEa_' . $postData['uniqId'] . '(this, ' . $row['id'] . ', ' . ($postData['subLevel'] + 1) . ', ' . (isset($row['metadataid']) ? $row['metadataid'] : '') . ')" class="nav-link pl-' . $postData['subLevel'] . '">' . $row['name'] . '</a>
                            <ul class="nav nav-group-sub add-submenu-' . $row['id'] . '" data-submenu-title="Layouts"></ul>
                        </li>';
            }

            $menu = '1';
        } else {
            $menu = '0';
        }

        echo json_encode(array('id' => $postData['id'], 'Html' => $Html, 'menu' => $menu, 'menuData' => $menuData));
    }

    public function renderContentEa() {

        $postData = Input::postData();

        $index = 1;
        (String) $Html = "";

        if (!isset($postData['metadataid']) || !$postData['metadataid']) {
            echo json_encode(array('postData' => $postData, 'Html' => '', 'menuData' => array()));
            die;
        }

        (Array) $filterParam = array();

        if (Input::postCheck('filterParam') && !Input::isEmpty('filterParam')) {
            parse_str(Input::post('filterParam'), $filterParam);
        }

        $menuData = $this->model->getSidebarContentModel($postData['metadataid'], $filterParam);

        $param = array('templateId' => $postData['menuId']);
        $pathList = $this->model->getProcessCodeResult('1565262536462', $param);

        if ($menuData) {
            foreach ($menuData as $key => $row) {

                $rowJson = Arr::encode(array('workSpaceParam' => $row, 'isFlow' => ''));

                $Html .= '<li>
                            <a href="javascript:void(0);" onclick="getEaContentRender_' . $postData['uniqId'] . '(this, \'' . $row['name'] . '\')" data-row="' . $rowJson . '" class="media d-flex align-items-center">
                                <div class="mr-2" style="margin-top: -3px;">
                                    <h1 class="rownumber">' . ( ($index < 10) ? '0' . $index : $index ) . '.</h1>
                                </div>
                                <div class="media-body">
                                    <div class="media-title font-weight-bold mb-0" style="line-height: normal;font-size: 12px;">
                                        ' . $row['name'] . '
                                    </div>';
                if ($pathList) {
                    foreach ($pathList as $path) {
                        $Html .= '<span class="text-muted font-weight-bold font-size-sm w-100 float-left" style="font-size: .65rem">';
                        $Html .= '<i class="' . ($path['icon'] ? $path['icon'] : '') . ' mr-1" style="font-size:13px;top:-1px;"></i> ';
                        if (isset($row[Str::lower($path['code'])])) {
                            $Html .= ($row[Str::lower($path['code'])] ? $row[Str::lower($path['code'])] : '');
                        }
                        $Html .= '</span>';
                    }
                } else {
                    $Html .= '<span class="text-muted font-weight-bold font-size-sm w-100 float-left" style="font-size: .65rem; height: 10px !important"></span>';
                    $Html .= '<span class="text-muted font-weight-bold font-size-sm w-100 float-left" style="font-size: .65rem; height: 10px !important"></span>';
                }

                $Html .= '</div>
                        </a>
                    </li>';
                $index++;
            }
        }

        echo json_encode(array('postData' => $postData, 'Html' => $Html, 'menuData' => $menuData));
    }

    public function eaLayout() {
        $this->load->model('mdasset', 'middleware/models/');

        $this->view->uniqId = getUID();

        if (!is_ajax_request()) {
            $this->view->css = array_unique(array_merge(array('custom/css/vr-card-menu.css'), AssetNew::metaCss()));
            $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));
            $this->view->render('header');
        }

        $this->view->metaDataId = '1565262544864';
        $this->view->leftSideBarMenu = $this->model->getLeftSidebarModel('1565262544864');
        $this->view->rightSideBarMenuChild = $this->model->getIntranedSidebarChildModel();
        $this->view->getIntranetAllContent = $this->model->getIntranetAllContentModel();

        $mdObjectCtrl = Controller::loadController('Mdobject', 'middleware/controllers/');
        $this->view->dataViewHeaderRealData = $mdObjectCtrl->dataViewHeaderDataCtl($this->view->metaDataId);
        $this->view->dataViewHeaderData = $mdObjectCtrl->findCriteria($this->view->metaDataId, $this->view->dataViewHeaderRealData);

        $this->view->render('layout/repository', self::viewPath);
    }

    public function getLayoutSubMenuRender() {

        $postData = Input::postData();
        (String) $Html = "";
        $menuData = $this->model->getLeftSidebarModel('1565262544864', $postData['id']);

        if ($menuData) {
            foreach ($menuData as $key => $row) {
                $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                $Html .= '<li class="nav-item">
                            <a href="javascript:;" 
                            data-row="' . $rowJson . '"
                            li-status="closed"
                            onclick="getSubMenuEa_' . $postData['uniqId'] . '(this, ' . $row['id'] . ', ' . ($postData['subLevel'] + 1) . ', ' . (isset($row['metadataid']) ? $row['metadataid'] : '') . ')" class="nav-link pl-' . $postData['subLevel'] . '">' . $row['name'] . '</a>
                            <ul class="nav nav-group-sub add-submenu-' . $row['id'] . '" data-submenu-title="Layouts"></ul>
                        </li>';
            }

            $menu = '1';
        } else {
            $menu = '0';
        }

        echo json_encode(array('id' => $postData['id'], 'Html' => $Html, 'menu' => $menu, 'menuData' => $menuData));
    }
    
}
