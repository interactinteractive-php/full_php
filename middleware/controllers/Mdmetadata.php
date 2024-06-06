<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdmetadata Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Metadata
 * @author	B.Och-Erdene <ocherdene@interactive.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdmetadata
 */
class Mdmetadata extends Controller {

    public static $defaultCacheTime = 14400;
    public static $viewPath = 'middleware/views/metadata/';
    public static $dataViewPath = 'middleware/views/metadata/dataview/';
    public static $umObjectCode = 'meta_data';
    public static $folderUmObjectCode = 'folder';
    public static $isProcessParamValues = false;
    public static $bookmarkMetaTypeId = '200101010000010';
    public static $businessProcessMetaTypeId = '200101010000011';
    public static $reportMetaTypeId = '200101010000012';
    public static $dashboardMetaTypeId = '200101010000013';
    public static $expressionMetaTypeId = '200101010000015';
    public static $metaGroupMetaTypeId = '200101010000016';
    public static $fieldMetaTypeId = '200101010000017';
    public static $contentMetaTypeId = '200101010000023';
    public static $googleMapMetaTypeId = '200101010000024';
    public static $bannerMetaTypeId = '200101010000026';
    public static $menuMetaTypeId = '200101010000025';
    public static $calendarMetaTypeId = '200101010000027';
    public static $donutMetaTypeId = '200101010000028';
    public static $reportTemplateMetaTypeId = '200101010000029';
    public static $cardMetaTypeId = '200101010000031';
    public static $diagramMetaTypeId = '200101010000032';
    public static $packageMetaTypeId = '200101010000033';
    public static $workSpaceMetaTypeId = '200101010000034';
    public static $statementMetaTypeId = '200101010000035';
    public static $layoutMetaTypeId = '200101010000036';
    public static $widgetMetaTypeId = '200101010000038';
    public static $proxyMetaTypeId = '200101010000040';
    public static $bpmMetaTypeId = '200101010000041';
    public static $dmMetaTypeId = '200101010000042';
    public static $taskFlowMetaTypeId = '200101010000043';
    public static $pageMetaTypeId = '200101010000044';
    public static $defaultMetaBigIconPath = 'assets/core/global/img/meta/file.png';
    public static $defaultMeta2BigIconPath = 'assets/core/global/img/meta2/file.png';
    public static $defaultMetaSmallIconPath = 'assets/core/global/img/meta/file-mini.png';
    public static $metaBigIconPath = 'assets/core/global/img/metaicon/big/';
    public static $metaSmallIconPath = 'assets/core/global/img/metaicon/small/';
    public static $metaGroupBigIconPath = 'assets/core/global/img/meta/rar.png';
    public static $metaGroupSmallIconPath = 'assets/core/global/img/meta/rar-mini.png';
    public static $dataViewBigIconPath = 'assets/core/global/img/meta/dataview.png';
    public static $dataView2BigIconPath = 'assets/core/global/img/meta2/dataview.png';
    public static $dataViewSmallIconPath = 'assets/core/global/img/meta/dataview-mini.png';
    public static $tableStructureBigIconPath = 'assets/core/global/img/meta/tablestructure.png';
    public static $tableStructureSmallIconPath = 'assets/core/global/img/meta/tablestructure-mini.png';
    public static $dashboardBigIconPath = 'assets/core/global/img/meta2/dashboard.png';
    public static $reportBigIconPath = 'assets/core/global/img/meta2/report.png';
    public static $cardBigIconPath = 'assets/core/global/img/meta2/card.png';
    public static $dmBigIconPath = 'assets/core/global/img/meta/datamart.png';
    public static $statementBigIconPath = 'assets/core/global/img/meta2/statement.png';
    public static $pathDefaultValues = array();
    public static $defaultKeywordReplacer = array();
    public static $ignoreMethodNames = array('executeupdate', 'runcustomquery');

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }
    
    public function index () {
        self::system();
    }
    
    public static function getAllowMetaStatusTypeIds() {
        return array(
            self::$businessProcessMetaTypeId, 
            self::$metaGroupMetaTypeId, 
            self::$googleMapMetaTypeId, 
            self::$menuMetaTypeId, 
            self::$reportTemplateMetaTypeId, 
            self::$dashboardMetaTypeId, 
            self::$diagramMetaTypeId, 
            self::$cardMetaTypeId, 
            self::$statementMetaTypeId, 
            self::$packageMetaTypeId, 
            self::$workSpaceMetaTypeId
        );
    }

    public function getMetaData($metaDataId, $getFolder = false) {
        $this->load->model('mdmetadata', 'middleware/models/');
        $row = $this->model->getMetaDataModel($metaDataId, $getFolder);
        return $row;
    }
    
    public function getMetaTypeById($id) {
        $this->load->model('mdmetadata', 'middleware/models/');

        if (!is_ajax_request()) {
            return $this->model->getMetaTypeById($id);
        } else {
            echo $this->model->getMetaTypeById($id);
        }
    }

    public function getMetaDataValuesByDataView($arr, $requestType = '', $inputParamArr = array()) {
        $this->load->model('mdmetadata', 'middleware/models/');
        $valueList = $this->model->getMetaDataValuesByDataViewModel($arr, $requestType, $inputParamArr);
        return $valueList;
    }
    
    public function getSingleMetaDataValuesByDataView($arr, $editValue, $inputParamArr = array()) {
        $this->load->model('mdmetadata', 'middleware/models/');
        $valueList = $this->model->getSingleMetaDataValuesByDataViewModel($arr, $editValue, $inputParamArr);
        return $valueList;
    }

    public function getMetaTypeList($typeIds = null) {
        $this->load->model('mdmetadata', 'middleware/models/');
        $rows = $this->model->getMetaTypeListModel($typeIds);
        return $rows;
    }
    
    public function getMetaTypeListByAddMode($typeIds = null) {
        $this->load->model('mdmetadata', 'middleware/models/');
        $rows = $this->model->getMetaTypeListByAddModeModel($typeIds);
        return $rows;
    }
    
    public function getProcessSubTypeListByAddMode() {
        $this->load->model('mdmetadata', 'middleware/models/');
        $rows = $this->model->getProcessSubTypeListByAddModeModel();
        return $rows;
    }
    
    public function getGroupSubTypeListByAddMode() {
        $this->load->model('mdmetadata', 'middleware/models/');
        $rows = $this->model->getGroupSubTypeListByAddModeModel();
        return $rows;
    }

    public function getMetaTypeProcessList() {
        $this->load->model('mdmetadata', 'middleware/models/');
        $rows = $this->model->getMetaTypeProcessListModel();
        return $rows;
    }

    public function metaAssets() {
        $this->view->css = array_unique(array_merge(array(
            'custom/css/fileexplorer.css',
            'custom/addon/plugins/jquery-easyui/themes/metro/easyui.css',
            'custom/addon/plugins/jstree/dist/themes/default/style.min.css'
            ), AssetNew::lifeCycleCss()));
        $this->view->js = array_unique(array_merge(array(
            'custom/addon/plugins/jquery-easyui/jquery.easyui.min.js',
            'custom/addon/plugins/jquery-easyui/locale/easyui-lang-' . Lang::getCode() . '.js'
            ), AssetNew::metaOtherJs(), AssetNew::highchartJs(), AssetNew::lifeCycleJs()));

        $this->view->fullUrlCss = AssetNew::amChartCss();
        $this->view->fullUrlJs = AssetNew::amChartJs();
    }

    public function system() {
        
        Uri::isUrlAuth();

        $this->load->model('mdmetadata', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }
        
        $this->view->title = $this->lang->line('META_00038');
        $this->metaAssets();
        $this->view->params = null;
        $this->view->isAjax = is_ajax_request();

        if (!$this->view->isAjax) {
            $this->view->render('header');
        }
        
        $this->view->render('system/index', self::$viewPath);

        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }
    }

    public function reporttype() {
        $this->view->title = 'Тайлан загварууд';
        $this->metatype('metatypeid=200101010000012,200101010000013&control=0');
    }

    public function metatype($params) {
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $this->metaAssets();
        $this->view->params = $params;
        $this->view->isAjax = is_ajax_request();

        if (!$this->view->isAjax) {
            $this->view->render('header');
        }
        
        $this->view->render('system/index', self::$viewPath);

        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }
    }

    public function getMetaDataPhotos($metaDataId) {
        $this->load->model('mdmetadata', 'middleware/models/');
        return $this->model->getMetaDataPhotosModel($metaDataId);
    }

    public function defaultList() {

        $this->view->isAdd = true;
        $this->view->isEdit = true;
        $this->view->isDelete = true;

        $this->view->isControl = true;
        $this->view->isBack = false;

        $this->view->folderId = null;
        $this->view->rowId = null;
        $this->view->rowType = null;
        $this->view->params = null;

        if (Input::isEmpty('params') == false) {
            
            $params = Input::post('params');
            parse_str($params, $param);
            
            if (isset($param['metatypeid'])) {
                $this->view->folderList = $this->model->getParentFolderListBySystem();
                $this->view->metaList = $this->model->getNotFolderMetaListByType($param['metatypeid']);
            } else {
                $this->view->folderList = $this->model->getParentFolderListBySystem();
                $this->view->metaList = $this->model->getNotFolderMetaListBySystem();
            }
            
            if (isset($param['control']) && $param['control'] == '0') {
                $this->view->isAdd = false;
                $this->view->isEdit = false;
                $this->view->isDelete = false;
                $this->view->isControl = false;
            }
            
        } else {
            $this->view->folderList = $this->model->getParentFolderListBySystem();
            $this->view->metaList = $this->model->getNotFolderMetaListBySystem();
        }
        
        $this->view->metaRender = $this->view->renderPrint('system/part/metaRender', self::$viewPath);

        $this->view->render('system/metaList', self::$viewPath);
    }
    
    public function metaRender() {
        $this->view->rowId = Input::numeric('folderId');
        
        if ($this->view->rowId) {
            $this->view->metaList = $this->model->getFolderWithMetaListBySystem($this->view->rowId);
        } else {
            $this->view->metaList = $this->model->getNotFolderMetaListBySystem();
        }
        
        $this->view->render('system/part/metaRender', self::$viewPath);
    }

    public function historyBackList() {
        $row = $this->model->getParentFolderBySystem(Input::post('ROW_ID'));

        $response = array(
            'rowId' => $row['PARENT_FOLDER_ID'],
            'rowType' => 'folder'
        );
        echo json_encode($response); exit;
    }

    public function childRecordList() {

        $this->view->isAdd = true;
        $this->view->isEdit = true;
        $this->view->isDelete = true;
        $this->view->isBack = true;
        $this->view->isControl = true;

        $this->view->folderList = null;
        $this->view->metaList = null;

        $this->view->folderId = null;
        $this->view->rowId = null;
        $this->view->rowType = null;
        $this->view->params = null;

        $type = Input::post('TYPE');

        if ($type == 'folder') {
            
            $rowId = Input::numeric('ROW_ID');
            
            if (Input::isEmpty('params') == false) {
                
                $this->view->params = Input::post('params');
                parse_str($this->view->params, $param);
                
                if (isset($param['metatypeid'])) {
                    $this->view->folderList = $this->model->getChildFolderListBySystem($rowId);
                    $this->view->metaList = $this->model->getFolderWithMetaListByType($rowId, $param['metatypeid']);
                } else {
                    $this->view->folderList = $this->model->getChildFolderListBySystem($rowId);
                    $this->view->metaList = $this->model->getFolderWithMetaListBySystem($rowId);
                }
                
                if (isset($param['control']) && $param['control'] == '0') {
                    $this->view->isAdd = false;
                    $this->view->isEdit = false;
                    $this->view->isDelete = false;
                    $this->view->isControl = false;
                }
                
            } else {
                $this->view->folderList = $this->model->getChildFolderListBySystem($rowId);
                $this->view->metaList = $this->model->getFolderWithMetaListBySystem($rowId);
            }
            
            $this->view->folderId = $rowId;
            $this->view->rowId = $rowId;
            $this->view->rowType = 'folder';
            
        } else {
            $this->view->isBack = false;
            $this->view->folderList = $this->model->getParentFolderListBySystem();
            $this->view->metaList = $this->model->getNotFolderMetaListBySystem();
        }
        
        $this->view->metaRender = $this->view->renderPrint('system/part/metaRender', self::$viewPath);

        $this->view->render('system/metaList', self::$viewPath);
    }
    
    public function allTypeSearch() {

        $this->view->isAdd = true;
        $this->view->isEdit = true;
        $this->view->isDelete = true;
        $this->view->isBack = true;

        $this->view->folderId = null;
        $this->view->rowId = null;
        $this->view->rowType = null;
        $this->view->params = null;

        $this->view->searchType = Input::post('searchType');
        $this->view->condition = Input::post('condition');
        $this->view->searchValue = Input::post('value');

        if ($this->view->searchType == 'meta') {
            $this->view->metaList = $this->model->metaSearchModel($this->view->searchValue, $this->view->condition);
            $this->view->render('system/search/searchMetaList', self::$viewPath);
        } elseif ($this->view->searchType == 'folder') {
            $this->view->folderList = $this->model->folderSearchModel($this->view->searchValue, $this->view->condition);
            $this->view->render('system/search/searchFolderList', self::$viewPath);
        } elseif ($this->view->searchType == 'id') {
            $this->view->metaList = $this->model->metaIdSearchModel($this->view->searchValue, $this->view->condition);
            $this->view->render('system/search/searchMetaList', self::$viewPath);
        }
    }

    public function addMetaBySystem() {
        
        $this->view->metaCode = Input::post('metaCode');
        $this->view->metaName = Input::post('metaName');
        
        $this->view->folderId = Input::post('folderId');
        $this->view->folderName = null;
        $this->view->typeIds = Input::post('typeIds');
        
        if ($getFolder = $this->model->getFolderRowModel($this->view->folderId)) {
            $this->view->folderName = $getFolder['FOLDER_NAME'];
        }
        
        $this->view->isDialog = Input::post('isDialog') == 'true' ? true : false;

        $this->view->sidebar = $this->view->renderPrint('system/sidebar/addModeSidebar', self::$viewPath);
        $this->view->render('system/addMetaBySystem', self::$viewPath);
    }

    public function createMetaSystemModuleForm() {
        
        $result = $this->model->createMetaSystemModuleModel();
        
        $reponseMetaDataTypeId = '';
        
        if ($result) {
            if (isset($result['status'])) {
                $response = $result;
            } else {
                
                $metaTypeId = Input::post('META_TYPE_ID');
                
                if ($metaTypeId == self::$reportTemplateMetaTypeId) {
                    $reponseMetaDataTypeId = 'reportTemplate';
                } elseif ($metaTypeId == self::$diagramMetaTypeId) {
                    $reponseMetaDataTypeId = 'dashboard';
                }
                
                $response = array(
                    'status' => 'success',
                    'message' => $this->lang->line('msg_save_success'),
                    'folderId' => $result['folderId'],
                    'metaDataId' => $result['metaDataId'],
                    'metaTypeId' => $reponseMetaDataTypeId,
                );
            }
        } else {
            $response = array(
                'status' => 'error',
                'message' => $this->lang->line('msg_save_error')
            );
        }
        echo json_encode($response); exit;
    }

    public function viewMetaBySystem() {

        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->folderId = Input::post('folderId');
        $this->view->metaRow = $this->model->getMetaDataBySystem($this->view->metaDataId);
        
        $checkLock = $this->model->checkMetaLock($this->view->metaDataId, null, $this->view->metaRow['META_TYPE_ID']);

        if ($checkLock) {
            $this->view->isLocked = true;
        }

        $this->view->folderNames = $this->model->getMetaDataFolderNamesModel($this->view->metaDataId);
        $this->view->sidebar = $this->view->renderPrint('system/sidebar/viewModeSidebar', self::$viewPath);

        $this->view->metaFileRows = $this->model->getMetaDataFilesModel($this->view->metaDataId);
        $this->view->metaFiles = $this->view->renderPrint('system/part/viewFile', self::$viewPath);

        $response = array(
            'mainHtml' => $this->view->renderPrint('system/viewMetaBySystem', self::$viewPath)
        );
        echo json_encode($response); exit;
    }

    public function editMetaBySystem() {

        $this->view->metaDataId = Input::numeric('metaDataId');
        
        if ($accessResponse = $this->model->isAccessMetaModel($this->view->metaDataId)) {
            echo json_encode($accessResponse, JSON_UNESCAPED_UNICODE); exit;
        }
        
        if ($changeLogResponse = $this->model->isCheckChangeLogMetaModel($this->view->metaDataId)) {
            echo json_encode($changeLogResponse, JSON_UNESCAPED_UNICODE); exit;
        }
        
        $this->view->folderId = Input::post('folderId');

        $this->view->metaRow = $this->model->getMetaDataBySystem($this->view->metaDataId);
        
        $this->view->isEdit = true;
        $this->view->isDialog = false;
        
        if (Input::post('isDialog') == 'true') {
            $this->view->isDialog = true;
        }
        
        $this->view->isBackBtn = false;
        
        if (Input::post('isBack') == '1') {
            
            if ($this->view->metaRow['META_TYPE_ID'] != self::$diagramMetaTypeId) {
                $this->view->checkMetaData = false;
            }
            
            $this->view->isBackBtn = true;
        }
        
        if (Input::postCheck('dialogMode') && Input::isEmpty('dialogMode') === false) {
            
            if (Input::post('metaDataType') == 'reportTemplate') {
                $this->view->metaRow['META_TYPE_ID'] = self::$reportTemplateMetaTypeId;
            } elseif (Input::post('metaDataType') == 'dashboard') {
                $this->view->metaRow['META_TYPE_ID'] = self::$diagramMetaTypeId;
            }
            
            $this->view->checkMetaData = true;
            $this->view->metaDatas = null;
            $this->view->folderIdsNames = null;
            $this->view->tagIdsNames = null;
            $this->view->bugFixes = null;
            
        } else {
            
            $this->view->metaDatas = $this->model->getChildMetaDatasModel($this->view->metaDataId, ($this->view->metaRow['META_TYPE_ID'] == self::$statementMetaTypeId), $this->view->metaRow['META_TYPE_ID']);
            
            if ($this->view->metaRow['META_TYPE_ID'] == self::$proxyMetaTypeId) {
                $this->view->proxyChildMetas = $this->model->getChildMetaDatasModel($this->view->metaDataId, false, self::$proxyMetaTypeId);
            } elseif ($this->view->metaRow['META_TYPE_ID'] == self::$statementMetaTypeId) {
                $this->view->versionChildMetas = $this->model->getChildMetaDatasModel($this->view->metaDataId, false, self::$statementMetaTypeId);
            }
            
            $this->view->folderIdsNames = $this->model->getMetaDataFolderIdsNamesModel($this->view->metaDataId);
            $this->view->tagIdsNames = $this->model->getMetaTagIdsNamesModel($this->view->metaDataId);
            $this->view->bugFixes = $this->model->getMetaBugFixesModel($this->view->metaDataId);
        }
        
        $checkLock = $this->model->checkMetaLock($this->view->metaDataId, null, $this->view->metaRow['META_TYPE_ID']);
        
        if ($checkLock) {
            $this->view->isLocked = true;
        }
        
        $this->view->metaFileRows = $this->model->getMetaDataFilesModel($this->view->metaDataId);
        $this->view->metaFiles = $this->view->renderPrint('system/part/viewFile', self::$viewPath);
        
        $this->view->sidebar = $this->view->renderPrint('system/sidebar/editModeSidebar', self::$viewPath);

        $response = array(
            'mainHtml' => $this->view->renderPrint('system/editMetaBySystem', self::$viewPath), 
            'status' => 'success', 
            'title' => $this->lang->line('edit_btn'), 
            'save_btn' => $this->lang->line('save_btn'), 
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function updateMetaSystemModuleForm() {

        $result = $this->model->updateMetaSystemModuleModel();

        if ($result) {
            if (isset($result['status'])) {
                $response = $result;
            } else {
                $response = array(
                    'status' => 'success',
                    'message' => $this->lang->line('msg_save_success'),
                    'folderId' => $result['folderId'],
                    'metaDataId' => $result['metaDataId']
                );
            }
        } else {
            $response = array(
                'status' => 'error',
                'message' => $this->lang->line('msg_save_error')
            );
        }
        echo json_encode($response); 
    }

    public function saveBpInputParams() {
        
        $result = $this->model->saveBpInputParamsModel(Input::post('mainMetaDataId'));

        if ($result) {
            $response = array('status' => 'success', 'message' => $this->lang->line('msg_save_success'));
        } else {
            $response = array('status' => 'error', 'message' => $this->lang->line('msg_save_error'));
        }
        
        echo json_encode($response); exit;
    }

    public function saveBpOutputParams() {

        $result = $this->model->saveBpOutputParamsModel(Input::post('mainMetaDataId'));

        if ($result) {
            $response = array('status' => 'success', 'message' => $this->lang->line('msg_save_success'));
        } else {
            $response = array('status' => 'error', 'message' => $this->lang->line('msg_save_error'));
        }
        
        echo json_encode($response); exit;
    }

    public function getAllTargetLink() {
        $array[] = array('TARGET_ID' => 'null', 'TARGET_NAME' => '- '. Lang::line('metadata_choose_view') .' -');
        $array[] = array('TARGET_ID' => '_blank', 'TARGET_NAME' => Lang::line('metadata_choose_view_1'));
        $array[] = array('TARGET_ID' => '_self', 'TARGET_NAME' => Lang::line('metadata_choose_view_2'));
        return $array;
    }

    public function getSystemAllTables() {
        $this->load->model('mdmetadata', 'middleware/models/');
        $tables = $this->model->getSystemAllTablesModel();
        return $tables;
    }

    public function getSystemAllViews() {
        $this->load->model('mdmetadata', 'middleware/models/');
        $tables = $this->model->getSystemAllViewsModel();
        return $tables;
    }

    public function getStandartFields() {
        $this->load->model('mdmetadata', 'middleware/models/');
        $fields = $this->model->getStandartFieldsModel();
        return $fields;
    }

    public function getWebServiceLanguage() {
        $this->load->model('mdmetadata', 'middleware/models/');
        $data = $this->model->getWebServiceLanguageModel();
        return $data;
    }

    public function getActiveDmReportModel() {
        $this->load->model('mdmetadata', 'middleware/models/');
        $fields = $this->model->getActiveDmReportModelModel();
        return $fields;
    }

    public function getDmChart() {
        $this->load->model('mdmetadata', 'middleware/models/');
        $fields = $this->model->getDmChartModel();
        return $fields;
    }

    public function getDirectoryList() {
        $this->load->model('mdmetadata', 'middleware/models/');
        $fields = $this->model->getDirectoryListModel();
        return $fields;
    }

    public function objectBookmarkLinks() {
        $this->view->render('system/link/bookmark/objectBookmarkLinks', self::$viewPath);
    }

    public function objectBookmarkLinksEditMode() {
        
        $this->view->metaTypeId = Input::post('metaTypeId');
        $this->view->metaDataId = Input::numeric('metaDataId');
        $getObjectRow = $this->model->getBookmarkData($this->view->metaDataId);
        $this->view->bookmarkUrl = $getObjectRow['BOOKMARK_URL'];
        $this->view->target = $getObjectRow['TARGET'];

        $this->view->render('system/link/bookmark/objectBookmarksLinksEditMode', self::$viewPath);
    }

    public function businessProcessLinks() {
        $this->view->widgetData = $this->model->getBpMobileWidgetModel();
        $this->view->render('system/link/process/businessProcessLinks', self::$viewPath);
    }
    
    public function businessProcessLinksEditMode() {
        
        $this->view->metaTypeId = Input::post('metaTypeId');
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->bpRow = $this->model->getMetaBusinessProcessLinkModel($this->view->metaDataId);
        $this->view->widgetData = $this->model->getBpMobileWidgetModel();

        $this->view->render('system/link/process/businessProcessLinksEditMode', self::$viewPath);
    }
    
    public function taskFlowLinks() {
        $this->view->render('system/link/taskflow/taskFlowLinks', self::$viewPath);
    }
    
    public function taskFlowLinksEditMode() {
        
        $this->view->metaTypeId = Input::post('metaTypeId');
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->bpRow = $this->model->getMetaBusinessProcessLinkModel($this->view->metaDataId);

        $this->view->render('system/link/taskflow/taskFlowLinksEditMode', self::$viewPath);
    }

    public function reportLink() {
        $this->view->render('system/link/report/reportLink', self::$viewPath);
    }

    public function reportLinkEditMode() {

        $this->view->metaTypeId = Input::post('metaTypeId');
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->rlRow = $this->model->getMetaReportLinkModel($this->view->metaDataId);

        $this->view->render('system/link/report/reportLinkEditMode', self::$viewPath);
    }

    public function dashboardLink() {
        $this->view->render('system/link/dashboard/dashboardLink', self::$viewPath);
    }

    public function dashboardLinkEditMode() {

        $this->view->metaTypeId = Input::post('metaTypeId');
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->dbRow = $this->model->getDMChartByMetaModel($this->view->metaDataId);

        $this->view->render('system/link/dashboard/dashboardLinkEditMode', self::$viewPath);
    }

    public function fieldLink() {
        $this->view->render('system/link/field/fieldLink', self::$viewPath);
    }

    public function fieldLinkEditMode() {

        $this->view->metaTypeId = Input::post('metaTypeId');
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->flRow = $this->model->getMetaFieldLinkModel($this->view->metaDataId);

        $displayFieldAttr = array(
            'name' => 'displayField',
            'id' => 'displayField',
            'class' => 'form-control select2',
            'disabled' => 'disabled'
        );
        $valueFieldAttr = array(
            'name' => 'valueField',
            'id' => 'valueField',
            'class' => 'form-control select2',
            'disabled' => 'disabled'
        );
        $chooseTypeAttr = array(
            'name' => 'chooseType',
            'id' => 'chooseType',
            'class' => 'form-control select2',
            'data' => array(
                array(
                    'id' => 'single',
                    'name' => 'Single'
                ),
                array(
                    'id' => 'multi',
                    'name' => 'Multi'
                )
            ),
            'op_value' => 'id',
            'op_text' => 'name',
            'disabled' => 'disabled'
        );
        $lookupMetaDataAttr = array(
            'name' => 'lookupMetaDataId',
            'id' => 'lookupMetaDataId',
            'class' => 'form-control select2',
            'op_value' => 'META_DATA_ID',
            'op_text' => 'META_DATA_NAME',
            'disabled' => 'disabled'
        );

        if (!empty($this->view->flRow['LOOKUP_TYPE']) && !empty($this->view->flRow['LOOKUP_META_DATA_ID'])) {
            $data = $this->model->getObjectFieldNameModel($this->view->flRow['LOOKUP_META_DATA_ID']);
            $displayFieldAttr = array(
                'name' => 'displayField',
                'id' => 'displayField',
                'class' => 'form-control select2',
                'data' => $data,
                'op_value' => 'FIELD_NAME',
                'op_text' => 'FIELD_NAME',
                'value' => $this->view->flRow['DISPLAY_FIELD']
            );
            $valueFieldAttr = array(
                'name' => 'valueField',
                'id' => 'valueField',
                'class' => 'form-control select2',
                'data' => $data,
                'op_value' => 'FIELD_NAME',
                'op_text' => 'FIELD_NAME',
                'value' => $this->view->flRow['VALUE_FIELD']
            );
            $chooseTypeAttr = array(
                'name' => 'chooseType',
                'id' => 'chooseType',
                'class' => 'form-control select2',
                'data' => array(
                    array(
                        'id' => 'single',
                        'name' => 'Single'
                    ),
                    array(
                        'id' => 'multi',
                        'name' => 'Multi'
                    )
                ),
                'op_value' => 'id',
                'op_text' => 'name',
                'value' => $this->view->flRow['CHOOSE_TYPE']
            );
            $lookupMetaDataAttr = array(
                'name' => 'lookupMetaDataId',
                'id' => 'lookupMetaDataId',
                'class' => 'form-control select2',
                'op_value' => 'META_DATA_ID',
                'op_text' => 'META_DATA_NAME',
                'value' => $this->view->flRow['LOOKUP_META_DATA_ID']
            );
        }

        $this->view->displayFieldCombo = Form::select($displayFieldAttr);
        $this->view->valueFieldCombo = Form::select($valueFieldAttr);
        $this->view->chooseTypeCombo = Form::select($chooseTypeAttr);
        $this->view->lookupMetaDataCombo = Form::select($lookupMetaDataAttr);

        $this->view->render('system/link/field/fieldLinkEditMode', self::$viewPath);
    }

    public function contentLinkEditMode() {
        
        $this->view->metaTypeId = Input::post('metaTypeId');
        $this->view->metaDataId = Input::numeric('metaDataId');

        $this->view->ltRow = $this->model->getContentLinkModel($this->view->metaDataId);
        $this->view->cellLinks = (new Mdlayout())->setCellLinkSavedRender($this->view->ltRow['LAYOUT_ID'], $this->view->metaDataId);

        $this->view->render('system/link/content/contentLinkEditMode', self::$viewPath);
    }

    public function menuLink() {
        $this->view->widgetData = $this->model->getDetailWidgetModel(4);
        $this->view->render('system/link/menu/menuLink', self::$viewPath);
    }

    public function menuLinkEditMode() {
        $this->view->metaTypeId = Input::post('metaTypeId');
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->menuRow = $this->model->getMetaMenuLinkModel($this->view->metaDataId);
        $this->view->widgetData = $this->model->getDetailWidgetModel(4);
        $this->view->menuColorData = $this->model->getMenuColorListModel();
        
        $this->view->render('system/link/menu/menuLinkEditMode', self::$viewPath);
    }

    public function calendarLink() {
        $this->view->render('system/link/calendar/calendarLink', self::$viewPath);
    }

    public function getMetaCalendarLink($metaDataId, $mergeMetaRow = false) {
        $this->load->model('mdmetadata', 'middleware/models/');
        return $this->model->getMetaCalendarLinkModel($metaDataId, $mergeMetaRow);
    }

    public function calendarLinkEditMode() {
        $this->load->model('mdcalendar', 'middleware/models/');
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->calRow = $this->model->getCalendarLinkDataModel($this->view->metaDataId);

        $this->view->render('system/link/calendar/calendarLinkEditMode', self::$viewPath);
    }

    public function donutLink() {
        $this->view->donut = array();
        $this->view->render('system/link/donut/donutLink', self::$viewPath);
    }

    public function getMetaDonutLink($metaDataId) {
        $this->load->model('mdmetadata', 'middleware/models/');
        return $this->model->getMetaDonutLinkModel($metaDataId);
    }

    public function donutLinkEditMode() {

        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->donut = (new Mdmetadata())->getMetaDonutLink($this->view->metaDataId);

        $this->view->render('system/link/donut/donutLink', self::$viewPath);
    }

    public function groupLink() {
        $this->view->render('system/link/group/groupLink', self::$viewPath);
    }

    public function groupLinkEditMode() {
        
        $this->view->metaTypeId = Input::post('metaTypeId');
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->gRow = $this->model->getMetaGroupLinkModel($this->view->metaDataId);
        
        $this->view->render('system/link/group/groupLinkEditMode', self::$viewPath);
    }

    public function dmLinkEditMode() {
        
        $this->view->metaTypeId = Input::post('metaTypeId');
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->gRow = $this->model->getDmLinkModel($this->view->metaDataId);
        $this->view->savedSchedule = $this->model->getDmScheduleModel($this->view->metaDataId);
        $this->view->scheduleList = $this->model->getDmScheduleListModel();
        
        $this->view->render('system/link/dm/dmLinkEditMode', self::$viewPath);
    }    

    public function cardLink() {
        $this->view->card = array();
        $this->view->render('system/link/card/cardLink', self::$viewPath);
    }

    public function cardLinkEditMode() {
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->card = $this->model->getMetaCardLinkModel($this->view->metaDataId);

        $this->view->render('system/link/card/cardLink', self::$viewPath);
    }

    public function getMetaCardLink($metaDataId) {
        $this->load->model('mdmetadata', 'middleware/models/');
        return $this->model->getMetaCardLinkModel($metaDataId);
    }

    public function diagramLink() {
        $this->view->render('system/link/diagram/diagramLink', self::$viewPath);
    }

    public function diagramLinkEditMode() {
        $this->load->model('mddashboard', 'middleware/models/');

        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->diagram = $this->model->getMetaDiagramLinkModel($this->view->metaDataId);

        if (Input::postCheck('checkMeta')) {
            $this->view->checkMeta = Input::post('checkMeta');
        }
        
        $this->view->render('system/link/diagram/diagramLinkEditMode', self::$viewPath);
    }

    public function addFolder() {

        $this->view->folderId = Input::post('folderId');
        $this->view->folderName = null;
        
        if ($getFolder = $this->model->getFolderRowModel($this->view->folderId)) {
            $this->view->folderName = $getFolder['FOLDER_NAME'];
        }
        
        $this->view->sidebar = $this->view->renderPrint('folder/sub/sidebar/addModeSidebar', self::$viewPath);
        
        $this->view->render('folder/sub/addFolder', self::$viewPath);
    }

    public function createFolder() {
        
        $result = $this->model->createFolderModel();

        if ($result['status'] == 'success') {
            $response = array(
                'status' => 'success',
                'message' => $this->lang->line('msg_save_success'),
                'folderId' => $result['folderId']
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => $result['message']
            );
        }
        echo json_encode($response); exit;
    }

    public function editFolder() {

        $folderId = Input::post('folderId');
        $this->view->row = $this->model->getFvmFolderByIdModel($folderId);

        $this->view->isEdit = true;
        $this->view->sidebar = $this->view->renderPrint('folder/sub/sidebar/editModeSidebar', self::$viewPath);

        $response = array(
            'mainHtml' => $this->view->renderPrint('folder/sub/editFolder', self::$viewPath)
        );
        echo json_encode($response); exit;
    }

    public function updateFolder() {

        $result = $this->model->updateFolderModel();

        if ($result['status'] == 'success') {
            $response = array(
                'status' => 'success',
                'message' => $this->lang->line('msg_save_success'),
                'folderId' => $result['folderId']
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => $this->lang->line('msg_save_error')
            );
        }
        echo json_encode($response); exit;
    }

    public function iconChoose() {

        $this->view->metaIconId = Input::post('metaIconId');
        $this->view->iconType = $this->model->getMetaIconType();

        $response = array(
            'Html' => $this->view->renderPrint('system/part/iconChoose', self::$viewPath),
            'Title' => $this->lang->line('META_00200'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function iconList() {

        $this->view->iconTypeId = Input::post('iconTypeId');
        $this->view->iconList = $this->model->getMetaIconList($this->view->iconTypeId);

        $this->view->render('system/part/iconList', self::$viewPath);
    }

    public function commonSelectableDataGrid() {
        $this->load->model('mdobject', 'middleware/models/');
                
        Mddatamodel::$ignorePermission = true;
        $result = $this->model->dataViewDataGridModel();   

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function childFolderSystem() {
        
        $isExternalServer = issetVar($_REQUEST['isExternalServer']);
        
        if ($isExternalServer == '1') {
            
            $url = Mdupgrade::metaImportExternalServerAddress();
            
            if ($url) {
                $data = $this->ws->curlRequest($url . '/mdupgrade/getMetaFolderList', $_REQUEST, true);

                header('Content-Type: application/json');
                echo $data; exit;
            }
        }
        
        $response = $this->model->childFolderSystemModel();
        jsonResponse($response);
    }

    public function metaDataSelectableGrid() {

        $this->view->chooseType = Input::post('chooseType');
        $this->view->singleSelect = ($this->view->chooseType == 'multi') ? 'false' : 'true';
        $this->view->defaultCriteria = '';
        $this->view->searchParams = '';
        $this->view->isExternalServer = 0;
        $this->view->isNamedParam = false;
        
        if (Input::isEmpty('params') == false) {
            
            $requestParams = Input::post('params');
            parse_str($requestParams, $params);
            
            if (count($params) > 0) {
                
                foreach ($params as $k => $v) {
                    $this->view->{$k} = $v;
                    
                    if ($k === 'autoSearch' && $v == '1') {
                        
                        $this->view->defaultCriteria = "defaultCriteria: '" . Str::remove_querystring_var($requestParams, 'autoSearch') . "'";
                        $this->view->searchParams = "queryParams: {" . $this->view->defaultCriteria . ", isExternalServer: '".issetDefaultVal($params['isExternalServer'], '0')."'},";  
                    } 
                }
                
                $this->view->isNamedParam = true; 
            }
        }
        
        if ($selectedId = Input::numeric('selectedId')) {
            
            $_POST['selectedMetaId'] = $selectedId;
            $result = $this->model->commonMetaDataGridModel();
            
            if (isset($result['rows'][0])) {
                
                $this->view->selectedRows = array_map(function($arr) {
                    return $arr + array('action' => '<a href="javascript:;" onclick="deleteCommonMetaDataBasket(this);" class="btn btn-xs red" title="'.$this->lang->line('META_00002').'"><i class="fa fa-trash"></i></a>');
                }, $result['rows']);
            }
        }
        
        $this->view->searchForm = $this->view->renderPrint('common/sub/searchForm', self::$viewPath);

        $response = array(
            'Html' => $this->view->renderPrint('common/sub/selectableGrid', self::$viewPath),
            'Title' => $this->lang->line('META_00133'),
            'choose_btn' => $this->lang->line('choose_btn'),
            'close_btn' => $this->lang->line('close_btn'),
            'addbasket_btn' => $this->lang->line('addbasket_btn')
        );
        echo json_encode($response); exit;
    }

    public function commonMetaDataGrid() {
        
        $isExternalServer = Input::post('isExternalServer');
        
        if ($isExternalServer == '1') {
            
            $url = Mdupgrade::metaImportExternalServerAddress();  
            
            if ($url) {
                $data = $this->ws->curlRequest($url . '/mdupgrade/commonMetaDataGrid', $_POST, true);

                header('Content-Type: application/json');
                echo $data; exit;
            }
        }
        
        $response = $this->model->commonMetaDataGridModel();
        jsonResponse($response);
    }

    public function setGroupParamAttributesNew() {

        $this->view->metaDataId = Input::numeric('metaDataId');
        
        $this->view->depth = 0;
        $this->view->isNew = 0;
        $this->view->rowId = '';
        
        $this->view->fieldDataTypeOptions = $this->model->fieldDataTypeComboOptions('IS_DV = 1');
        
        if ($this->view->metaDataId) {
            
            $this->view->params = $this->model->getGroupParamsModel($this->view->metaDataId);
            
        } else {
            
            $this->view->params = null;
            
            if (Input::isEmpty('query') == false) {
                $this->view->params = $this->model->queryToParamsModel(Input::postNonTags('query'));
                
                if (isset($this->view->params['queryToParamStatus'])) {
                    $this->view->params['status'] = $this->view->params['queryToParamStatus'];
                    echo json_encode($this->view->params); exit; 
                }
                
                $this->view->isNew = 1;
            }
        }
        
        $this->view->paramsRender = $this->view->renderPrint('system/link/group/v2/input', self::$viewPath);
        
        $response = array(
            'Html' => $this->view->renderPrint('system/link/group/v2/setParamAttributes', self::$viewPath),
            'Title' => 'Param Attributes',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function setDataModelProcessEditMode() {

        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->processDtl = $this->model->getGroupProcessDtlModel($this->view->metaDataId);
        $this->view->batchDtl = $this->model->getGroupBatchDtlModel($this->view->metaDataId);
        $this->view->colors = Mdcommon::standartColorClass();

        $response = array(
            'Html' => $this->view->renderPrint('system/link/group/setDataModelProcessEditMode', self::$viewPath),
            'Title' => 'DataModel Process',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function getDMChartByMeta($metaDataId) {
        $this->load->model('mdmetadata', 'middleware/models/');
        $row = $this->model->getDMChartByMetaModel($metaDataId);
        return $row;
    }

    public function dashboard() {
        $metaDataId = Input::numeric('metaDataId');
        $this->view->row = $this->model->getDMChartByMetaModel($metaDataId);
        $this->view->render('system/link/dashboard/show', self::$viewPath);
    }

    public function getMetaFieldDataType() {
        $this->load->model('mdmetadata', 'middleware/models/');
        $data = $this->model->getMetaFieldDataTypeModel();
        return $data;
    }

    public function getMetaFieldPattern($isDataMask = false) {
        $this->load->model('mdmetadata', 'middleware/models/');
        $data = $this->model->getMetaFieldPatternModel($isDataMask);
        return $data;
    }

    public function lookupFieldName() {
        $lookupMetaDataId = Input::post('lookupMetaDataId');
        $data = $this->model->getObjectFieldNameModel($lookupMetaDataId);
        
        $this->load->model('mddatamodel', 'middleware/models/');
        $getIdCodeName = $this->model->getCodeNameFieldNameModel($lookupMetaDataId);
        
        echo json_encode(array('fields' => $data, 'idCodeName' => $getIdCodeName)); exit;
    }
    
    public function getObjectFieldName($lookupMetaDataId) {
        $this->load->model('mdmetadata', 'middleware/models/');
        $data = $this->model->getObjectFieldNameModel($lookupMetaDataId);
        return $data;
    }

    public static function setDefaultValue($value) {

        if ($value == '') {
            return null;
        }
        
        if (is_array($value)) {
            return null;
        }
        
        if (!array_key_exists($value, self::$pathDefaultValues)) {
            self::$pathDefaultValues[$value] = Mdmetadata::getDefaultValue($value);
        } 
        
        return self::$pathDefaultValues[$value];
    }
    
    public static function getDefaultValue($value) {
        
        $lowerValue = strtolower($value);
        
        if ($lowerValue == 'sysdate') {
            return Date::currentDate('Y-m-d');
        } elseif ($lowerValue == 'sysdatetime') {
            return Date::currentDate('Y-m-d H:i:s');
        } elseif ($lowerValue == 'sysyear') {
            return Date::currentDate('Y');
        } elseif ($lowerValue == 'sysmonth') {
            return Date::currentDate('m');
        } elseif ($lowerValue == 'sysday') {
            return Date::currentDate('d');
        } elseif ($lowerValue == 'systime') {
            return Date::currentDate('H:i');
        } elseif ($lowerValue == 'sysyearstart') {
            return Date::currentDate('Y').'-01-01';
        } elseif ($lowerValue == 'sysyearend') {
            return Date::currentDate('Y').'-12-31';
        } elseif ($lowerValue == 'sysmonthstart') {
            return Date::currentDate('Y-m').'-01';
        } elseif ($lowerValue == 'sysmonthend') {
            return Date::currentDate('Y-m').'-'.date('t', strtotime('now'));
        } elseif ($lowerValue == 'sysweekstartdate') { 

            $dto = new DateTime();
            $dto->setISODate(date('Y'), date('W'));
            
            return $dto->format('Y-m-d');
        
        } elseif ($lowerValue == 'sysweekenddate') { 
            
            $dto = new DateTime();
            $dto->setISODate(date('Y'), date('W'));
            $dto->modify('+6 days');
            
            return $dto->format('Y-m-d');
            
        } elseif ($lowerValue == 'mondaythisweek') {
            return date('Y-m-d', strtotime('monday this week'));
        } elseif ($lowerValue == 'fridaythisweek') {
            return date('Y-m-d', strtotime('friday this week'));
        } elseif ($lowerValue == 'sessionuserid') {
            return Ue::sessionUserId();
        } elseif ($lowerValue == 'sessionuserkeyid') {
            return Ue::sessionUserKeyId();
        } elseif ($lowerValue == 'sessionemployeeid') {
            return Ue::sessionEmployeeId();
        } elseif ($lowerValue == 'sessionemployeekeyid') {
            return Ue::sessionEmployeeKeyId();
        } elseif ($lowerValue == 'sessionpositionkeyid') {
            return Ue::sessionPositionKeyId();
        } elseif ($lowerValue == 'sessiondepartmentid') {
            return Ue::sessionDepartmentId();
        } elseif ($lowerValue == 'sessiondepartmentcode') {
            return Ue::sessionDepartmentCode();
        } elseif ($lowerValue == 'sessionuserkeydepartmentid') {
            return Ue::sessionUserKeyDepartmentId();
        } elseif ($lowerValue == 'sessionuserkeydepartmentcode') {
            return Ue::sessionUserKeyDepartmentCode();
        } elseif ($lowerValue == 'sessionpersonname') { 
            return Ue::getSessionPersonWithLastName();
        } elseif ($lowerValue == 'sessionemail') { 
            return Ue::getSessionEmail();
        } elseif ($lowerValue == 'sessionstoreid') {
            return Ue::sessionStoreId();
        } elseif ($lowerValue == 'sessionpersonid') {
            return Session::get(SESSION_PREFIX.'personid');
        } elseif ($lowerValue == 'sessioncustomerid') {
            return Session::get(SESSION_PREFIX.'customerid');
        } elseif ($lowerValue == 'fiscalperiodstartdate') {
            return Ue::sessionFiscalPeriodStartDate();
        } elseif ($lowerValue == 'fiscalperiodenddate') {
            return Ue::sessionFiscalPeriodEndDate();
        } elseif ($lowerValue == 'fiscalperiodyearid') {
            return Ue::sessionFiscalPeriodYearId();
        } elseif ($lowerValue == 'fiscalperiodid') {
            return Ue::sessionFiscalPeriodId();
        } elseif ($lowerValue == 'fiscalperiodyear') {
            return date('Y', strtotime(Ue::sessionFiscalPeriodStartDate()));
        } elseif ($lowerValue == 'fiscalperiodprevmonthid') {
            return Ue::getFiscalPeriodPrevMonthId();
        } elseif ($lowerValue == 'currentfiscalperiodid') {
            return Ue::currentFiscalPeriodId();
        } elseif ($lowerValue == 'currentfiscalperiodstartdate') {
            return Ue::currentFiscalPeriodStartDate();
        } elseif ($lowerValue == 'currentfiscalperiodenddate') {
            return Ue::currentFiscalPeriodEndDate();
        } elseif ($lowerValue == 'sessionscenarioid') {
            return Ue::sessionScenarioId();
        } elseif ($lowerValue == 'sessioncashregisterid') {
            return Session::get(SESSION_PREFIX.'cashRegisterId');
        } elseif ($lowerValue == 'ipaddress') {
            return get_client_ip();
        } elseif ($lowerValue == 'langcode') {
            return Lang::getCode();
        } elseif ($lowerValue == 'filteroptionalval') {
            return null;
        } elseif (strpos($lowerValue, 'sysdate[') !== false) {
            preg_match('/sysdate\[(.*?)\]/', $lowerValue, $dateCriteria);
            if (!empty($dateCriteria)) {
                return Date::weekdayAfter('Y-m-d', Date::currentDate('Y-m-d'), $dateCriteria[1]);
            }
        } elseif (strpos($lowerValue, 'sysdatetime[') !== false) {
            preg_match('/sysdatetime\[(.*?)\]/', $lowerValue, $dateCriteria);
            if (!empty($dateCriteria)) {
                return Date::weekdayAfter('Y-m-d H:i:s', Date::currentDate('Y-m-d H:i:s'), $dateCriteria[1]);
            }
        } elseif (strpos($lowerValue, 'sysyear[') !== false) {
            preg_match('/sysyear\[(.*?)\]/', $lowerValue, $dateCriteria);
            if (!empty($dateCriteria)) {
                return Date::weekdayAfter('Y', Date::currentDate('Y'), $dateCriteria[1]);
            }
        } elseif (strpos($lowerValue, 'sysmonth[') !== false) {
            preg_match('/sysmonth\[(.*?)\]/', $lowerValue, $dateCriteria);
            if (!empty($dateCriteria)) {
                return Date::weekdayAfter('m', Date::currentDate('m'), $dateCriteria[1]);
            }
        } elseif (strpos($lowerValue, 'sysday[') !== false) {
            preg_match('/sysday\[(.*?)\]/', $lowerValue, $dateCriteria);
            if (!empty($dateCriteria)) {
                return Date::weekdayAfter('d', Date::currentDate('d'), $dateCriteria[1]);
            }
        } elseif (strpos($lowerValue, 'systime[') !== false) {
            preg_match('/systime\[(.*?)\]/', $lowerValue, $dateCriteria);
            if (!empty($dateCriteria)) {
                return Date::weekdayAfter('H:i', Date::currentDate('H:i'), $dateCriteria[1]);
            }
        } elseif (strpos($lowerValue, 'sysmonthstart[') !== false) {
            preg_match('/sysmonthstart\[(.*?)\]/', $lowerValue, $dateCriteria);
            if (!empty($dateCriteria)) {
                return Date::weekdayAfter('Y-m-d', Date::currentDate('Y-m').'-01', $dateCriteria[1]);
            }
        } elseif (strpos($lowerValue, 'sysyearstart[') !== false) {
            preg_match('/sysyearstart\[(.*?)\]/', $lowerValue, $dateCriteria);
            if (!empty($dateCriteria)) {
                return Date::weekdayAfter('Y-m-d', Date::currentDate('Y').'-01-01', $dateCriteria[1]);
            }
        } elseif (strpos($lowerValue, 'nowdate[') !== false) {
            
            $nowDay = Date::currentDate('d');
            $nowDate = Date::currentDate('Y-m-d');
            
            if ($nowDay == '01') {
                return $nowDate;
            }
            
            preg_match('/nowdate\[(.*?)\]/', $lowerValue, $dateCriteria);
            if (!empty($dateCriteria)) {
                return Date::weekdayAfter('Y-m-d', $nowDate, $dateCriteria[1]);
            }
        } else {
            
            $sessionValues = Session::get(SESSION_PREFIX.'sessionValues');
            
            if ($sessionValues && array_key_exists($lowerValue, $sessionValues)) {
                return $sessionValues[$lowerValue];
            }
        }
        
        $value = str_ireplace(
            array('sysdatetime', 'sysdate', 'fiscalperiodstartdatetime', 'fiscalperiodenddatetime', 'fiscalperiodstartdate', 'fiscalperiodenddate'), 
            array(Date::currentDate('Y-m-d H:i:s'), Date::currentDate('Y-m-d'), Ue::sessionFiscalPeriodStartDateTime(), Ue::sessionFiscalPeriodEndDateTime(), Ue::sessionFiscalPeriodStartDate(), Ue::sessionFiscalPeriodEndDate()), 
            $value);
        
        return $value;
    }

    public static function defaultKeywordReplacer($content) {
        
        $content = Str::lower($content);
        
        if (!self::$defaultKeywordReplacer) {
            self::$defaultKeywordReplacer = array(
                'sysdatetime'           => Date::currentDate('Y-m-d H:i:s'), 
                'sysdate'               => Date::currentDate('Y-m-d'), 
                'sysyear'               => Date::currentDate('Y'), 
                'sysmonth'              => Date::currentDate('m'), 
                'sysday'                => Date::currentDate('d'),
                'systime'               => Date::currentDate('H:i'),
                'sessionuserid'         => Ue::sessionUserId(),
                'sessionuserkeyid'      => Ue::sessionUserKeyId(),
                'sessionemployeeid'     => Ue::sessionEmployeeId(), 
                'sessionemployeekeyid'  => Ue::sessionEmployeeKeyId(),
                'sessiondepartmentid'   => Ue::sessionDepartmentId(),
                'fiscalperiodstartdate' => Ue::sessionFiscalPeriodStartDate(),
                'fiscalperiodenddate'   => Ue::sessionFiscalPeriodEndDate()
            );
        }
        
        foreach (self::$defaultKeywordReplacer as $key => $val) {
            $content = str_replace($key, $val, $content);
        }

        return $content;
    }
    
    public static function criteriaMethodReplacer($rules) {
        $rules = str_replace('isclosedfiscalperiod', '(new Mdcommon())->isClosedFiscalPeriod', $rules);
        $rules = str_replace('checkdatapermission', '(new Mdcommon())->checkDataPermission', $rules);
        $rules = str_replace('match(', 'Mdcommon::checkMatchValue(', $rules);
        return $rules;        
    }

    public function dataViewSelectableGrid() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');

        $this->load->model('mdobject', 'middleware/models/');
        
        $this->view->row = $this->model->getDataViewConfigRowModel($this->view->metaDataId);
        
        $this->view->basketGridWidth = 1200;
        $this->view->basketGridHeight = 'auto';
                
        if (isset($this->view->row['META_DATA_CODE'])) {
            
            if (issetParam($this->view->row['IS_LOOKUP_BY_THEME']) == '1') {
                
                $_POST['isSelectedBasket'] = '1';
                $_POST['ignorePermission'] = '1';
                
                $getDataView = (new Mdobject)->dataview($this->view->metaDataId, 0, 'json');
                echo $getDataView; exit;
                
            } else {
             
                if (isset($this->view->row['uniqueField'])) {
                    $this->view->primaryField = $this->view->row['uniqueField'];
                } elseif (isset($this->view->row['idField'])) {
                    $this->view->primaryField = $this->view->row['idField'];
                } else {
                    $this->view->primaryField = 'id';
                }

                $this->view->processMetaDataId = Input::post('processMetaDataId');
                $this->view->paramRealPath = Input::post('paramRealPath');

                $this->view->chooseType = Input::post('chooseType');
                $this->view->singleSelect = ($this->view->chooseType == 'multi' || $this->view->chooseType == 'multicomma') ? 'false' : 'true';   
                $this->view->isGridShow = Input::post('isGridShow') == 'false' ? false : true;

                $this->view->criteriaParams = null;
                $this->view->searchParams = null;
                $this->view->requestParams = null;
                $this->view->isNamedParam = false;
                $this->view->isBasketGrid = true;
                $this->view->isComboGrid = Input::post('isComboGrid', '');

                $linkedPopupData = Input::post('linkedPopup');
                $params = array();

                if (Input::postCheck('params')) {

                    $this->view->requestParams = urldecode(Input::post('params'));
                    parse_str($this->view->requestParams, $params);

                    if (count($params) > 0) {

                        $inputDVparam = '';

                        if (isset($params['param'])) {
                            $this->view->criteriaParams = Arr::changeKeyLower($params['param']);
                            $params = $this->view->criteriaParams;
                        } else {
                            $this->view->criteriaParams = Arr::changeKeyLower($params);
                        }

                        $this->view->fillPath = $this->view->criteriaParams;

                        foreach ($params as $k => $v) {

                            $this->view->{$k} = $v;

                            if ($linkedPopupData == 'OK') {
                                if (is_array($v)) {
                                    foreach ($v as $vk) {
                                        if ($vk != '') {
                                            $inputDVparam .= $k . '[]=' . $vk . '&';
                                        }
                                    }
                                } elseif ($v != '') {
                                    $inputDVparam .= $k . '=' . $v . '&';
                                }
                            }

                            if (($k == 'autoSearch' || $k == 'autosearch') && $v == '1') {
                                $this->view->searchParams = ", searchData: '" . Str::remove_querystring_var($inputDVparam, 'autoSearch') . "'";

                                if ($linkedPopupData == 'OK') {
                                    $this->view->searchParams .= ", searchDataLinkedPopup: 'OK'";
                                }
                            }
                        }

                        $this->view->isNamedParam = true;
                    }
                }

                $this->load->model('mdmetadata', 'middleware/models/');

                $this->view->selectedRow = json_encode($this->model->selectedRowConvertArray($this->view->metaDataId, $this->view->primaryField, $params));

                $this->load->model('mdobject', 'middleware/models/');

                $this->view->metaDataCode = $this->view->row['META_DATA_CODE'];
                $this->view->refStructureId = $this->view->row['REF_STRUCTURE_ID'];

                $this->view->isGridType = 'datagrid';
                $this->view->isTreeGridData = $this->view->row['TREE_GRID'];

                if ($this->view->isTreeGridData) {
                    $this->view->isGridType = 'treegrid';
                }

                $this->view->isRowColor = false;

                if ($this->view->row['COUNT_ROWCOLOR'] != '0') {
                    $this->view->isRowColor = true;
                }

                $this->view->isTextColor = false;

                if ($this->view->row['COUNT_TEXTCOLOR'] != '0') {
                    $this->view->isTextColor = true;
                }

                $this->view->folderTreeView = null;

                $this->view->processButtons = $this->model->dataViewProcessCommandModel($this->view->metaDataId, $this->view->metaDataCode, true, false);         
                $this->view->dataGridHeader = $this->model->renderDataViewGridModel($this->view->metaDataId, $this->view->metaDataCode, $this->view->refStructureId, true);

                $this->view->dataGridSearchFields = $this->model->dataViewHeaderDataModel($this->view->metaDataId);
                $this->view->dataGridSearchForm = $this->view->renderPrint('common/dataGridSearch', self::$viewPath);

                $this->view->dataGridBodyData = $this->model->getDataViewGridBodyDataModel($this->view->metaDataId);
                $this->view->dataGridBody = $this->view->renderPrint('common/dataGridBody', self::$viewPath);

                $this->view->dataGridDefaultOption = Mdobject::gridDefaultOptions();
                $this->view->dataGridOptionData = $this->model->getDVGridOptionsModel($this->view->metaDataId);                    

                $this->view->dataGridColumnData = $this->model->renderDataViewGridCache($this->view->metaDataId, $this->view->metaDataCode, $this->view->refStructureId);
                $this->view->subgrid = $this->view->row['subgrid'];
                
                if (Input::postCheck('printCopiesParams') && Input::isEmpty('printCopiesParams') == false) {
                    parse_str(Input::post('printCopiesParams'), $printCopiesParams);
                    $this->view->printCopiesParams = json_encode($printCopiesParams);
                }

                $this->view->dataViewMandatoryHeaderData = Mdobject::findMandatoryCriteria($this->view->metaDataId, $this->view->dataGridSearchFields);
                $this->view->defaultCriteriaMandatory = $this->view->renderPrint('search/defaultCriteriaMandatory', self::$dataViewPath);
                
                if (issetParam($this->view->row['WINDOW_SIZE']) === 'custom') {
                    
                    if ($this->view->row['WINDOW_WIDTH']) {
                        $this->view->basketGridWidth = (int) $this->view->row['WINDOW_WIDTH'];
                    }
                    
                    if ($this->view->row['WINDOW_HEIGHT']) {
                        $this->view->basketGridHeight = $this->view->row['WINDOW_HEIGHT'];
                    }
                }
                
                $title = $this->lang->line($this->view->row['LIST_NAME']);

                $categoryList = $this->model->getTreeCategoryList($this->view->metaDataId);

                if (!$categoryList) {

                    $this->view->isTree = false;
                    $this->view->treeCategoryList = array();
                    $this->view->filterFieldList = array();

                    $this->view->gridLayout = $this->view->renderPrint('common/withoutTreeView', self::$viewPath);

                } else {
                    $this->view->isTree = true;
                    $this->view->treeCategoryList = $categoryList['CATEGORY_LIST'];
                    $this->view->filterFieldList = $categoryList['FILTER_FIELD'];
                    
                    $this->view->gridLayout = $this->view->renderPrint('common/withTreeView', self::$viewPath);
                }           

                $html = $this->view->renderPrint('common/selectableGrid', self::$viewPath);
            }
            
        } else {
            $title = $this->lang->line('list');
            $html = $this->view->renderPrint('dataview/form/notdataview', self::$viewPath);
        }
        
        $response = array(
            'Html' => $html, 
            'Title' => $title,
            'Width' => $this->view->basketGridWidth,
            'choose_btn' => $this->lang->line('choose_btn'),
            'close_btn' => $this->lang->line('close_btn'),
            'addbasket_btn' => $this->lang->line('addbasket_btn')
        );
        
        echo json_encode($response); exit;
    }
    
    public function dataViewCustomSelectableGrid() {
        
        if (Input::postCheck('metaDataCode')) {
            
            $metaDataCode = Input::post('metaDataCode');
            
            $this->view->metaRow = $this->model->getMetaDataByCodeModel($metaDataCode);
            
            if (!$this->view->metaRow) {
                jsonResponse(array('status' => 'error', 'message' => '/'.$metaDataCode.'/ мета олдсонгүй!'));
            }
            
            $this->view->metaDataId = $this->view->metaRow['META_DATA_ID'];
            
            $this->load->model('mdobject', 'middleware/models/');
            $this->view->processButtons = $this->model->dataViewProcessCommandModel($this->view->metaDataId, $metaDataCode, true);  
            
        } else {
            
            $postData = Input::postData();
            $metaData = $postData['metaData'];
            
            $this->view->metaDataId = $metaData['DATAVIEW_ID'];
            $this->view->metaRow = $this->model->getMetaDataModel($this->view->metaDataId);
            
            if (!$this->view->metaRow) {
                jsonResponse(array('status' => 'error', 'message' => '/'.$this->view->metaDataId.'/ мета олдсонгүй!'));
            }
            
            $this->load->model('mdobject', 'middleware/models/');
            
            if ($metaData['IS_DEBIT'] == '1') {
                $addProcess = $metaData['DEBIT_PROCESS_ID'];
                $editProcess = $metaData['DEBIT_EDIT_PROCESS_ID'];
            } elseif ($metaData['IS_DEBIT'] == '0') {
                $addProcess = $metaData['CREDIT_PROCESS_ID'];
                $editProcess = $metaData['CREDIT_EDIT_PROCESS_ID'];
            } else {
                $addProcess = array($metaData['DEBIT_PROCESS_ID'], $metaData['CREDIT_PROCESS_ID']);
                $editProcess = array(
                    'debit' => $metaData['DEBIT_EDIT_PROCESS_ID'],
                    'credit' => $metaData['CREDIT_EDIT_PROCESS_ID'],
                );
            }
            
            $this->view->processButtons = array(
                'add_btn' => $this->model->dataViewSingleProcessCommandModel($this->view->metaDataId, $addProcess, false),
                'edit_btn' => $this->model->dataViewSingleProcessCommandModel($this->view->metaDataId, $editProcess, true)
            );
        }
        
        $this->view->folderTreeView = null;

        $this->view->chooseType = Input::post('chooseType');
        $this->view->singleSelect = ($this->view->chooseType == 'multi') ? 'false' : 'true';
        
        $this->view->criteriaParams = null;
        $this->view->searchParams = null;
        $this->view->requestParams = null;
        
        if (Input::postCheck('params')) {
            
            $this->view->requestParams = urldecode(Input::post('params'));
            parse_str($this->view->requestParams, $params);
            
            if (count($params) > 0) {
                
                if (isset($params['param'])) {
                    $this->view->criteriaParams = Arr::changeKeyLower($params['param']);
                } else {
                    $this->view->criteriaParams = Arr::changeKeyLower($params);
                }
                
                $this->view->searchParams = ", defaultCriteriaData: '" . $this->view->requestParams . "'";
                $this->view->isNamedParam = true;
                $this->view->fillPath = $this->view->criteriaParams;
            }
        }
        
        if (Input::post('accountGlFilter') !== null) {
            $this->view->searchParams = ", glAccountCriteriaData: '" . Input::post('accountGlFilter') . "'";
        }
        
        $this->view->isNamedParam = true;
        $this->view->processMetaDataId = '';
        $this->view->paramMetaDataId = '';
        $this->view->paramRealPath = '';
        $this->view->accountId = Input::postCheck('accountId') ? Input::post('accountId') : '';
        $this->view->uniqId = Input::postCheck('uniqId') ? Input::post('uniqId') : $this->view->metaDataId;
        
        $this->load->model('mdmetadata', 'middleware/models/');
        
        $this->view->selectedRow = json_encode($this->model->selectedRowCustomConvertArray($this->view->metaDataId));

        $this->load->model('mdobject', 'middleware/models/');
        
        $this->view->row = $this->model->getDataViewConfigRowModel($this->view->metaDataId);
        $this->view->metaDataCode = $this->view->row['META_DATA_CODE'];
        $this->view->refStructureId = $this->view->row['REF_STRUCTURE_ID'];
        $this->view->dataGridColumnData = $this->model->renderDataViewGridCache($this->view->metaDataId, $this->view->metaDataCode, $this->view->refStructureId);
        
        $this->view->primaryField = ($this->view->row['idField']) ? $this->view->row['idField'] : 'id'; 
        
        $this->view->isGridType = 'datagrid';
        $this->view->isTreeGridData = $this->view->row['TREE_GRID'];
        
        if ($this->view->isTreeGridData) {
            $this->view->isGridType = 'treegrid';
        }
        
        $this->view->isRowColor = false;
        if ($this->view->row['COUNT_ROWCOLOR'] != '0') {
            $this->view->isRowColor = true;
        }
        
        $this->view->isTextColor = false;
        if ($this->view->row['COUNT_TEXTCOLOR'] != '0') {
            $this->view->isTextColor = true;
        }
        
        if (Input::postCheck('fillData') && Input::isEmpty('fillData') === false) {
            $this->view->fillPath = array();
            parse_str(Input::post('fillData'), $fillPaths);

            foreach ($fillPaths as $key => $fillPath) {
                $this->view->fillPath[strtolower($key)] = $fillPath;
            }
        }
        
        $this->view->useMandatoryCriteria = false;
        
        $this->view->dataGridHeadData = $this->model->getDataViewGridHeaderModel($this->view->metaDataId);
        $this->view->dataGridHead = $this->view->renderPrint('common/dataGridHead', self::$viewPath);
        $this->view->dataGridHeader = $this->model->renderDataViewGridModel($this->view->metaDataId, $this->view->metaDataCode, '', true);
        
        $this->view->dataGridSearchFields = $this->model->dataViewHeaderDataModel($this->view->metaDataId);
        
        $this->view->dataViewMandatoryHeaderData = Mdobject::findMandatoryCriteria($this->view->metaDataId, $this->view->dataGridSearchFields);
        
        if (Input::postCheck('useMandatory') && Input::isEmpty('useMandatory') === false) {
            $this->view->useMandatoryCriteria = true;
            $this->view->defaultCriteriaMandatory = $this->view->renderPrint('search/defaultCriteriaMandatory', self::$dataViewPath);
        }
        
        $this->view->dataGridSearchForm = $this->view->renderPrint('common/dataGridSearch', self::$viewPath);
        $this->view->dataGridBodyData = $this->model->getDataViewGridBodyDataModel($this->view->metaDataId);
        $this->view->dataGridBody = $this->view->renderPrint('common/dataGridBody', self::$viewPath);
        $this->view->dataGridDefaultOption = Mdobject::gridDefaultOptions();
        $this->view->dataGridOptionData = $this->model->getDVGridOptionsModel($this->view->metaDataId);          
        
        $response = array(
            'Html' => $this->view->renderPrint('dataView/selectableGrid', 'middleware/views/generalledger/'),
            'Title' => $this->lang->line($this->view->row['LIST_NAME']),
            'metaDataId' => $this->view->metaDataId, 
            'choose_btn' => $this->lang->line('choose_btn'),
            'close_btn' => $this->lang->line('close_btn'),
            'addbasket_btn' => $this->lang->line('addbasket_btn')
        );
        echo json_encode($response); exit;
    }

    public function selectableGroupInputFills() {
        
        $processMetaDataId = Input::post('processMetaDataId');
        $paramRealPath = Input::post('paramRealPath');
        $lookupMetaDataId = Input::post('lookupMetaDataId');
        
        $controlsData = $this->model->getLookupCloneFieldModel($processMetaDataId, $paramRealPath);
        
        $this->load->model('mdobject', 'middleware/models/');
        $attributes = $this->model->getDataViewMetaValueAttributes($processMetaDataId, $paramRealPath, $lookupMetaDataId);

        $resultArray = array(
            'valueField' => isset($attributes['id']) ? strtolower($attributes['id']) : '',
            'codeField' => isset($attributes['code']) ? strtolower($attributes['code']) : '',
            'displayField' => isset($attributes['name']) ? strtolower($attributes['name']) : '',
            'controlsData' => $controlsData
        );
        echo json_encode($resultArray); exit;
    }

    public function getMetaMetaMapBySrcId($metaDataId) {
        $this->load->model('mdmetadata', 'middleware/models/');
        $data = $this->model->getMetaGroupByMetaDatasModel($metaDataId);
        return $data;
    }

    public function deleteMetaData() {
        $metaDataId = Input::numeric('metaDataId');
        $replaceMetaDataId = Input::post('replaceMetaDataId');
        
        $result = $this->model->deleteMetaDataModel($metaDataId, $replaceMetaDataId);
        echo json_encode($result); exit;
    }

    public function folderTreeView() {
        $this->load->model('mdmetadata', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $this->view->render('folder/sub/treeView', self::$viewPath);
    }

    public function metaSearchStandartType() {
        $array = array(
            array(
                'code' => 'meta',
                'name' => Lang::line('META_00133')
            ),
            array(
                'code' => 'folder',
                'name' => Lang::line('META_00085')
            ),
            array(
                'code' => 'id',
                'name' => 'ID'
            )
        );

        return $array;
    }

    public function googleMapView($isView = 'true') {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->rowId = Input::post('rowId');
        $map = $this->model->googleMapCoordinateListModel($this->view->metaDataId, $this->view->rowId);
        $this->view->gMapCoordeinate = $map['MAP'];
        $this->view->control = $map['CONTROL'];
        
        if ($isView == 'true') {
            echo json_encode(
                array(
                    'Html' => $this->view->renderPrint('system/link/gmap/google', self::$viewPath),
                    'Gmap' => $this->view->gMapCoordeinate,
                    'metaDataId' => $this->view->metaDataId
                )
            );
        } else {
            echo json_encode($map['MAP']);
        }
        exit;
    }
    
    public function googleMapCoordinateList() {
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->rowId = Input::post('rowId');
        $map = $this->model->googleMapCoordinateListModel($this->view->metaDataId, $this->view->rowId);
        
        echo json_encode($map['MAP']); exit;
    }
    
    public function getMapInfo($metaDataId, $metaValueId) {
        echo $metaDataId . ' - ' . $metaValueId; exit;
    }

    public function setGMapCoordinate($latitude = '47.91674471367652', $longitude = '106.9163167476654') {

        $this->view->markerColor = Config::getFromCacheDefault('map_market_color', null, 'EE2C24');
        $this->view->latitude = Input::param($latitude);
        $this->view->longitude = Input::param($longitude);

        if (Input::isEmpty('latitude') == false && Input::isEmpty('longitude') == false) {
            
            $latitude = Input::post('latitude');
            $longitude = Input::post('longitude');
            
            if ((float) $latitude > (float) $longitude) {
                $latitudeTmp = $latitude;
                $latitude = $longitude;
                $longitude = $latitudeTmp;
            }
            
            $this->view->latitude = $latitude;
            $this->view->longitude = $longitude;
        }

        $response = array(
            'Html' => $this->view->renderPrint('system/link/gmap/setgmapcoordinate', self::$viewPath),
            'Title' => 'Цэг тодорхойлох',
            'insert_btn' => $this->lang->line('insert_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function getChildFolderIds($folderId) { 
        $this->load->model('mdmetadata', 'middleware/models/');
        Mdmetadata_Model::$folderTreeDatas = array();
        return $this->model->getChildFolderIds($folderId);
    }

    public function renderMetaRow($row) {

        $array['META_DATA_ID'] = $row['META_DATA_ID'];
        $array['META_DATA_CODE'] = $row['META_DATA_CODE'];
        $array['META_DATA_NAME'] = $row['META_DATA_NAME'];
        $array['META_TYPE_ID'] = $row['META_TYPE_ID'];
        $array['FOLDER_ID'] = issetParam($row['FOLDER_ID']);
        $array['META_TYPE_CODE'] = $row['META_TYPE_CODE'];
        $array['CREATED_DATE'] = $row['CREATED_DATE'];
        $array['CREATED_PERSON_NAME'] = $row['CREATED_PERSON_NAME'];

        if (empty($row['META_ICON_CODE'])) {
            $array['BIG_ICON'] = Mdmetadata::$defaultMetaBigIconPath;
            $array['SMALL_ICON'] = Mdmetadata::$defaultMetaSmallIconPath;
        } else {
            $array['BIG_ICON'] = Mdmetadata::$metaBigIconPath . $row['META_ICON_CODE'];
            $array['SMALL_ICON'] = Mdmetadata::$metaSmallIconPath . $row['META_ICON_CODE'];
        }

        if ($row['META_TYPE_ID'] == Mdmetadata::$bookmarkMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "metaDirectURL('" . $row['BOOKMARK_URL'] . "', '" . $row['BOOKMARK_TARGET'] . "');";
        } elseif ($row['META_TYPE_ID'] == Mdmetadata::$businessProcessMetaTypeId) {         
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "callWebServiceByMeta('" . $row['META_DATA_ID'] . "', false, '', true);";
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
                $array['BIG_ICON'] = Mdmetadata::$dataViewBigIconPath;
                $array['SMALL_ICON'] = Mdmetadata::$dataViewSmallIconPath;
                $array['linkHref'] = 'mdobject/dataview/' . $row['META_DATA_ID'];
                $array['linkTarget'] = '_self';
                $array['linkOnClick'] = '';
                $array['META_TYPE_CODE'] = 'dataview';
            } elseif ($row['GROUP_TYPE'] == 'tablestructure') {
                $array['BIG_ICON'] = Mdmetadata::$tableStructureBigIconPath;
                $array['SMALL_ICON'] = Mdmetadata::$tableStructureSmallIconPath;
                $array['linkHref'] = 'mdobject/dataview/' . $row['META_DATA_ID'];
                $array['linkTarget'] = '_self';
                $array['linkOnClick'] = '';
                $array['META_TYPE_CODE'] = 'tablestructure';
            } else {
                $array['BIG_ICON'] = Mdmetadata::$metaGroupBigIconPath;
                $array['SMALL_ICON'] = Mdmetadata::$metaGroupSmallIconPath;
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
            $array['linkOnClick'] = "callMenuByMeta('" . $row['META_DATA_ID'] . "');";
        } elseif ($row['META_TYPE_ID'] == Mdmetadata::$calendarMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "callCalendarByMeta('" . $row['META_DATA_ID'] . "');";
        } elseif ($row['META_TYPE_ID'] == Mdmetadata::$donutMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "callDonutByMeta('" . $row['META_DATA_ID'] . "');";
        } elseif ($row['META_TYPE_ID'] == Mdmetadata::$cardMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "callCardByMeta('" . $row['META_DATA_ID'] . "');";
        } elseif ($row['META_TYPE_ID'] == Mdmetadata::$diagramMetaTypeId) {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = "callDiagramByMeta('" . $row['META_DATA_ID'] . "');";
        } elseif ($row['META_TYPE_ID'] == Mdmetadata::$packageMetaTypeId) {
            $array['linkHref'] = 'mdobject/package/' . $row['META_DATA_ID'];
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = '';
        } elseif ($row['META_TYPE_ID'] == Mdmetadata::$workSpaceMetaTypeId) {
            $array['linkHref'] = 'mdworkspace/index/' . $row['META_DATA_ID'];
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = '';
        } elseif ($row['META_TYPE_ID'] == Mdmetadata::$statementMetaTypeId) {
            $array['linkHref'] = 'mdstatement/index/' . $row['META_DATA_ID'];
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = '';
        } elseif ($row['META_TYPE_ID'] == Mdmetadata::$widgetMetaTypeId) {
            $array['linkHref'] = 'mdwidget/index/' . $row['META_DATA_ID'];
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = '';
        } elseif ($row['META_TYPE_ID'] == Mdmetadata::$dmMetaTypeId) {
            if (empty($row['META_ICON_CODE'])) {
                $array['BIG_ICON'] = Mdmetadata::$dmBigIconPath;            
                $array['SMALL_ICON'] = Mdmetadata::$dmBigIconPath;
            }
            if (empty($row['REF_STRUCTURE_ID'])) {
                $array['linkHref'] = 'javascript:;';
            } else {
                $array['linkHref'] = 'mdobject/dataview/' . $row['REF_STRUCTURE_ID'];
            }
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = '';            
        } else {
            $array['linkHref'] = 'javascript:;';
            $array['linkTarget'] = '_self';
            $array['linkOnClick'] = '';
        }

        return $array;
    }

    public function reportTemplateLink() {
        $this->view->render('system/link/reportTemplate/reportTemplateLink', self::$viewPath);
    }

    public function reportTemplateEditMode() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->bpRow = $this->model->getReportTemplateModel($this->view->metaDataId);

        if ($this->view->bpRow) {

            if ($this->view->bpRow['HTML_CONTENT'] == '' && file_exists($this->view->bpRow['HTML_FILE_PATH'])) {
                $this->view->bpRow['HTML_CONTENT'] = file_get_contents($this->view->bpRow['HTML_FILE_PATH']);
            } else {
                includeLib('Compress/Compression');
                $this->view->bpRow['HTML_CONTENT'] = Compression::decompress($this->view->bpRow['HTML_CONTENT']);
            }
        }
        
        $this->view->render('system/link/reportTemplate/reportTemplateEditMode', self::$viewPath);
    }

    public function setTemplateEditor() {
        
        $this->view->htmlContent = Input::postNonTags('htmlContent');
        $this->view->metaDataId = Input::post('metaGroupId');
        $this->view->paths = $this->model->getHierarchyPathsByDvModel($this->view->metaDataId);
        $this->view->sysKeywords = (new Mdstatement())->sysKeywords();
        
        $this->view->fields = null;

        $response = array(
            'html' => $this->view->renderPrint('system/link/reportTemplate/tinymce_editor', self::$viewPath),
            'title' => 'Template editor',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function setHeaderFooterTemplateEditor() {
        
        $this->view->htmlHeaderContent = Input::postNonTags('htmlHeaderContent');
        $this->view->htmlFooterContent = Input::postNonTags('htmlFooterContent');
        $this->view->metaDataId = Input::post('metaGroupId');
        $this->view->sysKeywords = (new Mdstatement())->sysKeywords();
        
        $this->view->fields = null;

        $response = array(
            'html' => $this->view->renderPrint('system/link/reportTemplate/header_footer_editor', self::$viewPath),
            'title' => 'Template Header Footer editor',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function getMetaDataByGroup($metaGroupId) {
        $this->load->model('mdmetadata', 'middleware/models/');
        $result = $this->model->getMetaDataByGroupModel($metaGroupId);
        return $result;
    }
    
    public function getOnlyMetaDataByGroup($metaGroupId) {
        $this->load->model('mdmetadata', 'middleware/models/');
        $result = $this->model->getOnlyMetaDataByGroupModel($metaGroupId);
        return $result;
    }

    public function getMetaDataByParent($metaGroupId) {
        $this->load->model('mdmetadata', 'middleware/models/');
        $result = $this->model->getMetaDataByParentModel($metaGroupId);
        return $result;
    }

    public function isParentMetaGroup($parentid, $groupid) {
        $this->load->model('mdmetadata', 'middleware/models/');
        $result = $this->model->isParentMetaGroupModel($parentid, $groupid);
        return $result;
    }
    
    public function statementLink() {
        $this->view->render('system/link/statement/statementLink', self::$viewPath);
    }
    
    public function statementEditMode() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->bpRow = $this->model->getStatementLinkModel($this->view->metaDataId);
        $this->view->linkId = Arr::get($this->view->bpRow, 'ID');
        
        if ($this->view->bpRow && file_exists($this->view->bpRow['REPORT_DETAIL_FILE_PATH'])) {
            $this->view->bpRow['REPORT_DETAIL'] = file_get_contents($this->view->bpRow['REPORT_DETAIL_FILE_PATH']);
        }
        
        if (defined('CONFIG_REPORT_SERVER_ADDRESS') && CONFIG_REPORT_SERVER_ADDRESS) {
            $this->load->model('mdstatement', 'middleware/models/');
            $this->view->templateList = $this->model->getReportLayoutTemplateModel($this->view->metaDataId);
        }

        $this->view->render('system/link/statement/statementLinkEditMode', self::$viewPath);
    }
    
    public function packageLink() {
        $this->view->render('system/link/package/packageLink', self::$viewPath);
    }
    
    public function packageLinkEditMode() {

        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->bpRow = $this->model->getPackageLinkModel($this->view->metaDataId);

        $this->view->render('system/link/package/packageLinkEditMode', self::$viewPath);
    }
    
    public function proxyEditMode() {
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->render('system/link/proxy/proxyEditMode', self::$viewPath);
    }
    
    public function setStatementEditor() {
        
        $this->view->htmlContent = Input::postNonTags('htmlContent');
        $this->view->reportType = Input::post('reportType');
        
        if ($this->view->reportType == 'dataview') {
            $this->view->metaList = array();
            if (Input::numeric('metaDataId') != null) {
                $this->view->metaList = self::getOnlyMetaDataByGroup(Input::numeric('metaDataId'));
            }
        }

        $response = array(
            'Html' => $this->view->renderPrint('system/link/statement/editor', self::$viewPath),
            'Title' => Input::post('editorName'),
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function setTmceStatementEditor() {
        
        $htmlContent = base64_decode(Input::postNonTags('htmlContent'));
        
        $this->view->htmlContent = !empty($htmlContent) ? $htmlContent : '<br />'; 
        
        $this->view->metaList = array();
        
        if (Input::isEmpty('metaDataId') == false) {
            $this->view->metaList = self::getOnlyMetaDataByGroup(Input::numeric('metaDataId'));
        }
        
        $pageSize = Input::post('pageSize');
        $pageOrientation = Input::post('pageOrientation');
        $pageMarginLeft = Input::post('pageMarginLeft');
        $pageMarginRight = Input::post('pageMarginRight');
        $pageMarginTop = Input::post('pageMarginTop');
        $pageMarginBottom = Input::post('pageMarginBottom');
        $pageInnerHeight = Input::post('pageInnerHeight');
        
        $this->view->pageSize = $pageSize ? $pageSize : 'a4';
        $this->view->pageOrientation = $pageOrientation ? $pageOrientation : 'portrait';
        
        $this->view->pageMarginLeft = $pageMarginLeft ? $pageMarginLeft : '0';
        $this->view->pageMarginRight = $pageMarginRight ? $pageMarginRight : '0';
        $this->view->pageMarginTop = $pageMarginTop ? $pageMarginTop : '0';
        $this->view->pageMarginBottom = $pageMarginBottom ? $pageMarginBottom : '0';
        $this->view->pageInnerHeight = $pageInnerHeight ? $pageInnerHeight : '500';
        
        $this->view->dialogName = Input::post('dialogName');
        $this->view->temparoryId = getUID();
        
        $this->view->sysKeywords = (new Mdstatement())->sysKeywords();

        $response = array(
            'Html' => $this->view->renderPrint('system/link/statement/tinymce_editor', self::$viewPath),
            'Title' => Input::post('editorName'),
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function setStatementGroupEditor() {
        
        $this->view->htmlContent = Input::postNonTags('htmlContent');
        $this->view->dataViewId = Input::postNonTags('dataViewId');
        $this->view->metaList = array();
        
        if (Input::post('dataViewId') != null) {
            $this->view->metaList = self::getOnlyMetaDataByGroup(Input::post('dataViewId'));
        }

        $response = array(
            'Html' => $this->view->renderPrint('system/link/statement/groupEditor', self::$viewPath),
            'Title' => Input::post('editorName'),
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function objectNameCompress($objectName) {
        
        if (strlen(trim($objectName)) > 30) {
            $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'compress', array('value' => $objectName));
            
            if (isset($result['result'])) {
                return Str::htmlCharToDoubleQuote($result['result']);
            }
        }
        
        return $objectName;
    }

    public static function objectNameDeCompress($objectName) {
        
        if (strlen(trim($objectName)) > 30) {    
            includeLib('Compress/Compression');
            return Str::doubleQuoteToHtmlChar(Compression::decompress($objectName));
        }
        
        return $objectName;
    }
    
    public static function objectDeCompress($objectName) {
        
        if (strlen(trim($objectName)) > 30) {    
            includeLib('Compress/Compression');
            return Compression::decompress($objectName);
        }
        
        return $objectName;
    }

    public function getBpDvRowData(){
        $this->load->model('mdtemplate', 'middleware/models/');
        
        $metaDataId = Input::numeric('metaDataId');
        $rowId = Input::post('rowId');
        $result = $this->model->getRowData($metaDataId, $rowId);
        
        echo json_encode($result); exit;
    }
    
    public function metaFullOptions() {
        
        $title = 'Тохиргоо';
        $this->view->metaDataId = Input::numeric('metaDataId');
        
        if ($this->view->metaDataId) {
            
            if ($accessResponse = $this->model->isAccessMetaModel($this->view->metaDataId)) {
                jsonResponse($accessResponse);
            }
            
            if ($changeLogResponse = $this->model->isCheckChangeLogMetaModel($this->view->metaDataId)) {
                jsonResponse($changeLogResponse);
            }
            
            $this->view->metaRow = $this->model->getMetaDataBySystem($this->view->metaDataId);
            $this->view->folderIdsNames = $this->model->getMetaDataFolderIdsNamesModel($this->view->metaDataId);
            $this->view->metaTypeCode = $this->view->metaRow['META_TYPE_CODE'];
            $this->view->uniqId = getUID();
            
            if ($this->view->metaRow['META_TYPE_ID'] == Mdmetadata::$businessProcessMetaTypeId) {
                
                $title = 'Бизнес процессын тохиргоо';
                
                $this->view->bpRow = $this->model->getMetaBusinessProcessLinkModel($this->view->metaDataId);
                $this->view->bpRow['sidebar'] = $this->model->renderMetaV2LeftSidebarModel('1575012303405');
                $this->view->widgetData = $this->model->getBpMobileWidgetModel();
                
                $this->view->leftSideBar = $this->view->renderPrint('system/options/leftSidebar', self::$viewPath);
                $this->view->defaultView = $this->view->renderPrint('system/options/process/main', self::$viewPath);
                
            } elseif ($this->view->metaRow['META_TYPE_ID'] == Mdmetadata::$metaGroupMetaTypeId) {
                
                $title = 'Жагсаалтын тохиргоо';
                
                $this->view->bpRow = $this->model->getMetaGroupLinkModel($this->view->metaDataId);
                $this->view->bpRow['sidebar'] = $this->model->renderMetaV2LeftSidebarModel('1589509561746');
                
                $this->view->leftSideBar = $this->view->renderPrint('system/options/leftSidebar', self::$viewPath);
                $this->view->defaultView = $this->view->renderPrint('system/options/dataview/main', self::$viewPath);
            }
            
            $title .= ' - ' . $this->view->metaRow['META_DATA_NAME'];
        
            $response = [
                'status' => 'success', 
                'title'  => $title,
                'html'   => $this->view->renderPrint('system/options/index', self::$viewPath),
                'uniqId' => $this->view->uniqId, 
                'type'   => $this->view->metaRow['META_TYPE_CODE']
            ];
        
        } else {
            $response = ['status' => 'error', 'Invalid id!'];
        }
        
        convJson($response);
    }
    
    public function dvDataListWithProcess() {
        $dvId = Input::numeric('dvId');
        
        if ($dvId) {
            $this->load->model('mdobject', 'middleware/models/');
            $response = $this->model->getPanelDataListModel($dvId, true);
        } else {
            $response = array('status' => 'error', 'Invalid id!');
        }
        
        echo json_encode($response); exit;
    }
    
    public function metaDataAutoComplete() {
        
        $type = Input::post('type');
        $q = Input::post('q');
        $params = Input::post('params');
        
        $result = $this->model->metaDataAutoCompleteModel($type, $q, $params);            
        echo json_encode($result); exit;
    }
    
    public function metaDataAutoCompleteById() {

        $code = Str::lower(trim(Input::post('code')));
        
        $isName = $isId = false;
        $isValueNotEmpty = true;
        $row = false;

        if ($code == '') {
            $isValueNotEmpty = false;
        }
        
        if ($isValueNotEmpty) {

            if (Input::postCheck('isName')) {
                if (Input::post('isName') == 'true') {
                    $isName = true;
                }
                if (Input::post('isName') == 'idselect') {
                    $isId = true;
                }
            }
            
            if ($isId) {
                
                $result = $this->model->metaDataAutoCompleteByIdModel($code, 'META_DATA_ID');
                $row = $result;
                
            } else {
                
                if ($isName) {
                    $result = $this->model->metaDataAutoCompleteByIdModel($code, 'META_DATA_NAME');
                    $row = $result;
                } else {
                    $result = $this->model->metaDataAutoCompleteByIdModel($code, 'META_DATA_CODE');
                    $row = $result;
                }
            }
        }
        
        if ($row) {
            echo json_encode($row);
        } else {
            echo json_encode(array('META_DATA_ID' => '', 'META_DATA_CODE' => '', 'META_DATA_NAME' => ''));
        }
        exit;
    }
    
    public function getMetaStatus($metaDataId) {
        
        $cache = phpFastCache();
        $row = $cache->get('ms_'.$metaDataId);

        if ($row == null) {
            
            global $db;
            
            $row = $db->GetRow("
                SELECT 
                    MS.*  
                FROM META_DATA MD 
                    LEFT JOIN META_DATA_STATUS MS ON MS.ID = MD.STATUS_ID 
                WHERE MD.META_DATA_ID = $metaDataId");
            
            $cache->set('ms_'.$metaDataId, $row, Mdwebservice::$expressionCacheTime);
        }
        
        return $row;
    }
    
    public function solveReportTemplateFileName() {
        
        $data = $this->db->GetAll("
            SELECT 
                META_DATA_ID, 
                HTML_FILE_PATH 
            FROM META_REPORT_TEMPLATE_LINK 
            WHERE HTML_FILE_PATH IS NOT NULL"); 
        
        foreach ($data as $row) {
            
            $metaDataId = $row['META_DATA_ID'];
            $newPath = 'storage/uploads/report_template/'.$metaDataId.'.html';

            rename($row['HTML_FILE_PATH'], $newPath);
            
            $this->db->AutoExecute('META_REPORT_TEMPLATE_LINK', array('HTML_FILE_PATH' => $newPath), 'UPDATE', 'META_DATA_ID = '.$metaDataId);
            
            @unlink($row['HTML_FILE_PATH']);
        }
        
        return true;
    }
    
    public function deleteReportTemplateFileName() {
        
        $path  = 'storage/uploads/report_template/';
        $files = array_diff(scandir($path), array('.', '..'));
        
        foreach ($files as $val) {
            
            $check = $this->db->GetRow("
                SELECT 
                    META_DATA_ID 
                FROM META_REPORT_TEMPLATE_LINK 
                WHERE HTML_FILE_PATH = '$path".$val."'"); 
            
            if (!$check) {
                @unlink($path.$val);
            }
        }
        
        return true;
    }
    
    public function setExcelTemplate() {
        $this->load->model('mdcontentui', 'middleware/models/');
        
        $this->view->processMetaDataId = Input::post('processMetaDataId');
        $this->view->excelTemplateFile = $this->model->getExcelTemplateDetail();
        
        $response = array(
            'html' => $this->view->renderPrint('system/link/process/setExcelTemplate', self::$viewPath),
            'title' => $this->lang->line('Excel template'), 
            'save_btn' => $this->lang->line('save_btn'), 
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function setParamAttributesEditModeNew() {

        $this->view->metaDataId = Input::numeric('metaDataId');
        
        /*if ($changeLogResponse = $this->model->isCheckChangeLogMetaModel($this->view->metaDataId)) {
            echo json_encode($changeLogResponse, JSON_UNESCAPED_UNICODE); exit;
        }*/
        
        $this->view->depth = 0;
        $this->view->isNew = 0;
        $this->view->rowId = '';
        
        $this->view->fieldDataTypeOptions = $this->model->fieldDataTypeComboOptions('IS_BP = 1');
        
        if ($this->view->metaDataId) {
            $this->view->params = $this->model->getProcessInputParams($this->view->metaDataId);
        } else {
            $this->view->params = null;
        }
        
        $this->view->paramsRender = $this->view->renderPrint('system/link/process/v2/input', self::$viewPath);
        
        $response = array(
            'Html' => $this->view->renderPrint('system/link/process/v2/setParamAttributesEditMode', self::$viewPath),
            'Title' => $this->lang->line('META_00046'),
            'status' => 'success', 
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
    
    public function setOutputParamAttributesEditModeNew() {

        $this->view->metaDataId = Input::numeric('metaDataId');
        
        $this->view->depth = 0;
        $this->view->isNew = 0;
        $this->view->rowId = '';
        
        $this->view->fieldDataTypeOptions = $this->model->fieldDataTypeComboOptions('IS_DV = 1');
        
        if ($this->view->metaDataId) {
            $this->view->params = $this->model->getProcessOutputParams($this->view->metaDataId);
        } else {
            $this->view->params = null;
        }
        
        $this->view->paramsRender = $this->view->renderPrint('system/link/process/v2/output', self::$viewPath);
        
        $response = array(
            'Html' => $this->view->renderPrint('system/link/process/v2/setOutputParamAttributesEditMode', self::$viewPath),
            'Title' => $this->lang->line('META_00104'),
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function getProcessParamAddon() {

        $this->view->processMetaDataId = Input::numeric('processMetaDataId');
        $this->view->paramPath = Input::post('paramPath');
        $this->view->depth = Input::numeric('depth');
        $this->view->dataType = Input::post('dataType');
        $this->view->lookupType = Input::post('lookupType');
        $this->view->isGroup = Input::post('isGroup');
        $this->view->isNew = Input::numeric('isNew');
        
        $this->view->paramRow = $this->model->getProcessInputParamAddon($this->view->processMetaDataId, $this->view->paramPath, $this->view->isNew);
        $this->view->paramRow['LOOKUP_META_DATA_ID'] = Input::numeric('lookupMetaDataId');
        
        if ($this->view->isNew == 1) {
            $this->view->paramRow = array_merge($this->view->paramRow, $this->model->getProcessInputParamAutoNumberAddon($this->view->processMetaDataId, null));
        } else {
            $this->view->paramRow = array_merge($this->view->paramRow, $this->model->getProcessInputParamAutoNumberAddon($this->view->processMetaDataId, $this->view->paramPath));
        }
        
        $this->view->positionList = Mdwidget::positionList();
        $this->view->controlSubType = (new Mdcommon())->controlSubType($this->view->dataType, $this->view->lookupType);
        
        ob_start('ob_html_compress'); 
        
        if ($this->view->isGroup == 'true') {
            
            $this->view->widgetData = $this->model->getDetailWidgetModel(1);
            $this->view->render('system/link/process/v2/inputAddonGroup', self::$viewPath);
            
        } else {
            $this->view->maskData = $this->model->getMetaFieldPatternModel();
            $this->view->render('system/link/process/v2/inputAddon', self::$viewPath);
        }
        
        ob_end_flush();
    }
    
    public function getChildProcessParam() {

        $this->view->processMetaDataId = Input::post('processMetaDataId');
        $this->view->rowId = Input::post('rowId');
        $this->view->depth = Input::post('depth');
        $this->view->isNew = Input::post('isNew');
        
        $this->view->fieldDataTypeOptions = $this->model->fieldDataTypeComboOptions('IS_BP = 1');
        
        if ($this->view->isNew == '0') {
            
            $this->view->params = $this->model->getProcessInputParams($this->view->processMetaDataId, $this->view->rowId);
            
        } else {
            $paramPath = Input::post('paramPath');
            $this->view->params = $this->model->getMetaGroupInputParams($paramPath, $this->view->depth, $this->view->rowId);
            
            $this->view->newRowId = Input::post('newRowId');
        }
        
        ob_start('ob_html_compress'); 
        $this->view->render('system/link/process/v2/input', self::$viewPath);
        ob_end_flush();
    }
    
    public function getChildProcessOutputParam() {

        $this->view->processMetaDataId = Input::post('processMetaDataId');
        $this->view->rowId = Input::post('rowId');
        $this->view->depth = Input::post('depth');
        $this->view->isNew = Input::post('isNew');
        
        $this->view->fieldDataTypeOptions = $this->model->fieldDataTypeComboOptions('IS_DV = 1');
        
        if ($this->view->isNew == '0') {
            
            $this->view->params = $this->model->getProcessOutputParams($this->view->processMetaDataId, $this->view->rowId);
            
        } else {
            $paramPath = Input::post('paramPath');
            $this->view->params = $this->model->getMetaGroupOutputParams($paramPath, $this->view->depth, $this->view->rowId);
            
            $this->view->newRowId = Input::post('newRowId');
        }
        
        ob_start('ob_html_compress'); 
        $this->view->render('system/link/process/v2/output', self::$viewPath);
        ob_end_flush();
    }
    
    public function processParamAddCode() {
        
        $parentId = Input::numeric('parentId');
        $realCode = trim(Input::post('code'));
        $code = Str::lower($realCode);
        
        $isName = $isId = $row = false;
        $isValueNotEmpty = true;

        if ($code == '') {
            $isValueNotEmpty = false;
        }
        
        if ($isValueNotEmpty) {

            if (Input::postCheck('isName')) {
                if (Input::post('isName') == 'true') {
                    $isName = true;
                }
                if (Input::post('isName') == 'idselect') {
                    $isId = true;
                }
            }
            
            if ($isId) {
                
                $result = $this->model->processParamAddCodeModel($code, 'META_DATA_ID');
                $row = $result;
                
            } else {
                
                if ($isName) {
                    $result = $this->model->processParamAddCodeModel($code, 'META_DATA_NAME');
                    $row = $result;
                } else {
                    $result = $this->model->processParamAddCodeModel($code, 'META_DATA_CODE');
                    $row = $result;
                }
            }
        }
        
        $paramGroupPath = Input::post('paramGroupPath');
        
        if (!empty($paramGroupPath)) {
            $paramGroupPath = substr($paramGroupPath, 0, (strrpos($paramGroupPath, '.'))).'.';
        }
        
        if ($row) {
            
            $row['ID'] = getUID();
            $row['PARENT_ID'] = $parentId;
            $row['PARAM_REAL_PATH'] = $paramGroupPath.$row['PARAM_REAL_PATH'];
            
            $this->view->params = [$row];
            
        } else {
            
            $this->view->params = [
                [
                    'ID' => getUID(),
                    'PARENT_ID' => $parentId,
                    'DATA_TYPE' => 'string', 
                    'PARAM_REAL_PATH' => $paramGroupPath.$realCode,
                    'IS_SHOW' => 1,
                    'IS_REQUIRED' => '',
                    'DEFAULT_VALUE' => '',
                    'LOOKUP_META_DATA_ID' => '',
                    'LOOKUP_META_DATA_CODE' => '',
                    'LOOKUP_META_DATA_NAME' => '',
                    'LOOKUP_TYPE' => '',
                    'DISPLAY_FIELD' => '',
                    'VALUE_FIELD' => '',
                    'CHOOSE_TYPE' => '',
                    'RECORD_TYPE' => '',
                    'TAB_NAME' => '',
                    'LABEL_NAME' => $realCode,
                    'PARAM_NAME' => $realCode
                ]
            ];
        }
        
        $this->view->fieldDataTypeOptions = $this->model->fieldDataTypeComboOptions('IS_BP = 1');
        $this->view->rowId = $parentId;
        $this->view->depth = Input::post('depth');
        $this->view->isNew = 1;
        
        convJson([
            'path' => $this->view->params[0]['PARAM_REAL_PATH'], 
            'html' => $this->view->renderPrint('system/link/process/v2/input', self::$viewPath)
        ]);
    }
    
    public function processOutputParamAddCode() {
        
        $parentId = Input::post('parentId');
        $realCode = trim(Input::post('code'));
        $code = Str::lower($realCode);
        
        $isName = $isId = $row = false;
        $isValueNotEmpty = true;

        if ($code == '') {
            $isValueNotEmpty = false;
        }
        
        if ($isValueNotEmpty) {

            if (Input::postCheck('isName')) {
                if (Input::post('isName') == 'true') {
                    $isName = true;
                }
                if (Input::post('isName') == 'idselect') {
                    $isId = true;
                }
            }
            
            if ($isId) {
                
                $result = $this->model->processParamAddCodeModel($code, 'META_DATA_ID');
                $row = $result;
                
            } else {
                
                if ($isName) {
                    $result = $this->model->processParamAddCodeModel($code, 'META_DATA_NAME');
                    $row = $result;
                } else {
                    $result = $this->model->processParamAddCodeModel($code, 'META_DATA_CODE');
                    $row = $result;
                }
            }
        }
        
        $paramGroupPath = Input::post('paramGroupPath');
        
        if (!empty($paramGroupPath)) {
            $paramGroupPath = substr($paramGroupPath, 0, (strrpos($paramGroupPath, '.'))).'.';
        }
        
        if ($row) {
            
            $row['ID'] = getUID();
            $row['PARENT_ID'] = $parentId;
            $row['PARAM_REAL_PATH'] = $paramGroupPath.$row['PARAM_REAL_PATH'];
            
            $this->view->params = [$row];
            
        } else {
            
            $this->view->params = [
                [
                    'ID' => getUID(),
                    'PARENT_ID' => $parentId,
                    'DATA_TYPE' => 'string', 
                    'PARAM_REAL_PATH' => $paramGroupPath.$realCode,
                    'IS_SHOW' => 1,
                    'RECORD_TYPE' => '',
                    'LABEL_NAME' => $realCode,
                    'PARAM_NAME' => $realCode
                ]
            ];
        }
        
        $this->view->fieldDataTypeOptions = $this->model->fieldDataTypeComboOptions('IS_DV = 1');
        $this->view->rowId = $parentId;
        $this->view->depth = Input::post('depth');
        $this->view->isNew = 1;
        
        convJson([
            'path' => $this->view->params[0]['PARAM_REAL_PATH'], 
            'html' => $this->view->renderPrint('system/link/process/v2/output', self::$viewPath)
        ]);
    }
    
    public function processParamAddMulti() {
        
        $paramGroupPath = Input::post('paramGroupPath');
        
        if (!empty($paramGroupPath)) {
            $paramGroupPath = substr($paramGroupPath, 0, (strrpos($paramGroupPath, '.'))).'.';
        }
        
        $this->view->params = $this->model->processParamAddMultiModel($paramGroupPath);
        
        $this->view->fieldDataTypeOptions = $this->model->fieldDataTypeComboOptions('IS_BP = 1');
        $this->view->rowId = Input::post('parentId');
        $this->view->depth = Input::post('depth');
        $this->view->isNew = 1;
        
        ob_start('ob_html_compress'); 
        
        $this->view->render('system/link/process/v2/input', self::$viewPath);
        
        ob_end_flush();
    }
    
    public function processOutputParamAddMulti() {
        
        $paramGroupPath = Input::post('paramGroupPath');
        
        if (!empty($paramGroupPath)) {
            $paramGroupPath = substr($paramGroupPath, 0, (strrpos($paramGroupPath, '.'))).'.';
        }
        
        $this->view->params = $this->model->processParamAddMultiModel($paramGroupPath);
        
        $this->view->fieldDataTypeOptions = $this->model->fieldDataTypeComboOptions('IS_DV = 1');
        $this->view->rowId = Input::post('parentId');
        $this->view->depth = Input::post('depth');
        $this->view->isNew = 1;
        
        ob_start('ob_html_compress'); 
        
        $this->view->render('system/link/process/v2/output', self::$viewPath);
        
        ob_end_flush();
    }
    
    public function getGroupParamConfig($processMetaDataId, $fieldPath, $isGroup = false) {
        
        $result = '';
        
        if ($processMetaDataId && $fieldPath) {
            
            $processMetaDataIdPh = $this->db->Param(0);
            
            if ($isGroup) {
                $where = "GROUP_META_DATA_ID = $processMetaDataIdPh ";
            } else {
                $where = "MAIN_PROCESS_META_DATA_ID = $processMetaDataIdPh ";
            }
            
            $data = $this->db->GetAll("
                SELECT 
                    PARAM_PATH, 
                    PARAM_META_DATA_CODE, 
                    DEFAULT_VALUE, 
                    IS_KEY_LOOKUP 
                FROM META_GROUP_PARAM_CONFIG 
                WHERE $where 
                    AND LOOKUP_META_DATA_ID IS NOT NULL     
                    AND LOWER(FIELD_PATH) = ".$this->db->Param(1), 
                array($processMetaDataId, strtolower($fieldPath))
            );

            if ($data) {
                foreach ($data as $row) {
                    if ($row['IS_KEY_LOOKUP'] == '1') {
                        $result .= Form::hidden(array('name' => 'paramGroupConfigParamPathKey[' . $fieldPath . '][]', 'value' => $row['PARAM_PATH']));
                        $result .= Form::hidden(array('name' => 'paramGroupConfigParamMetaKey[' . $fieldPath . '][]', 'value' => $row['PARAM_META_DATA_CODE']));
                        $result .= Form::hidden(array('name' => 'paramGroupConfigDefaultValKey[' . $fieldPath . '][]', 'value' => $row['DEFAULT_VALUE']));
                    } else {
                        $result .= Form::hidden(array('name' => 'paramGroupConfigParamPath[' . $fieldPath . '][]', 'value' => $row['PARAM_PATH']));
                        $result .= Form::hidden(array('name' => 'paramGroupConfigParamMeta[' . $fieldPath . '][]', 'value' => $row['PARAM_META_DATA_CODE']));
                        $result .= Form::hidden(array('name' => 'paramGroupConfigDefaultVal[' . $fieldPath . '][]', 'value' => $row['DEFAULT_VALUE']));
                    }
                }
            }
        }
        
        return $result;
    }

    public function getGroupProcessParamConfig($processMetaDataId, $fieldPath, $isGroup = false) {
        
        $result = '';
        
        if ($processMetaDataId && $fieldPath) {
            
            $processMetaDataIdPh = $this->db->Param(0);
            
            if ($isGroup) {
                $where = "GROUP_META_DATA_ID = $processMetaDataIdPh ";
            } else {
                $where = "MAIN_PROCESS_META_DATA_ID = $processMetaDataIdPh ";
            }
            
            $data = $this->db->GetAll("
                SELECT 
                    PARAM_PATH, 
                    PARAM_META_DATA_CODE, 
                    DEFAULT_VALUE 
                FROM META_GROUP_PARAM_CONFIG 
                WHERE $where 
                    AND PROCESS_META_DATA_ID IS NOT NULL 
                    AND LOWER(FIELD_PATH) = " . $this->db->Param(1), 
                array($processMetaDataId, strtolower($fieldPath)) 
            );

            if ($data) {
                foreach ($data as $row) {
                    $result .= Form::hidden(array('name' => 'paramProcessConfigParamPath[' . $fieldPath . '][]', 'value' => $row['PARAM_PATH']));
                    $result .= Form::hidden(array('name' => 'paramProcessConfigParamMeta[' . $fieldPath . '][]', 'value' => $row['PARAM_META_DATA_CODE']));
                    $result .= Form::hidden(array('name' => 'paramProcessConfigDefaultVal[' . $fieldPath . '][]', 'value' => $row['DEFAULT_VALUE']));
                }
            }
        }
        
        return $result;
    }
    
    public function getParamDefaultValues($mainMetaDataId, $fieldPath, $lookupMetaDataId, $isKey = false) {
        
        $result = '';
        
        if ($mainMetaDataId && $lookupMetaDataId && $fieldPath) {
            
            $data = $this->db->GetAll("
                SELECT 
                    VALUE_ID 
                FROM META_PARAM_VALUES 
                WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                    AND LOOKUP_META_DATA_ID = ".$this->db->Param(1)." 
                    AND LOWER(PARAM_PATH) = ".$this->db->Param(2), 
                array($mainMetaDataId, $lookupMetaDataId, strtolower($fieldPath))
            );

            if ($data) {
                if ($isKey) {
                    foreach ($data as $row) {
                        $result .= Form::hidden(array('name' => 'paramDefaultValueIdKey[' . $fieldPath . '][]', 'value' => $row['VALUE_ID']));
                    }
                } else {
                    foreach ($data as $row) {
                        $result .= Form::hidden(array('name' => 'paramDefaultValueId[' . $fieldPath . '][]', 'value' => $row['VALUE_ID']));
                    }
                }
            }
        }

        return $result;
    }
    
    public function getGroupRelationConfig($mainMetaDataId, $fieldPath) {
        
        $result = '';
        
        if ($mainMetaDataId && $fieldPath) {

            $data = $this->db->GetAll("
                SELECT 
                    BATCH_NUMBER, 
                    SRC_PARAM_PATH, 
                    TRG_PARAM_PATH, 
                    DEFAULT_VALUE  
                FROM META_GROUP_RELATION 
                WHERE MAIN_META_DATA_ID = ".$this->db->Param(0)." 
                    AND LOWER(FIELD_PATH) = ".$this->db->Param(1)."  
                GROUP BY 
                    BATCH_NUMBER, 
                    SRC_PARAM_PATH, 
                    TRG_PARAM_PATH, 
                    DEFAULT_VALUE", array($mainMetaDataId, strtolower($fieldPath)));

            if ($data) {
                foreach ($data as $row) {
                    $result .= Form::hidden(array('name' => 'paramGroupRelationBatchNumber[' . $fieldPath . '][]', 'value' => $row['BATCH_NUMBER']));
                    $result .= Form::hidden(array('name' => 'paramGroupRelationDefaultValue[' . $fieldPath . '][]', 'value' => $row['DEFAULT_VALUE']));
                    $result .= Form::hidden(array('name' => 'paramGroupRelationTrgParamPath[' . $fieldPath . '][]', 'value' => $row['TRG_PARAM_PATH']));
                    $result .= Form::hidden(array('name' => 'paramGroupRelationSrcParamPath[' . $fieldPath . '][]', 'value' => $row['SRC_PARAM_PATH']));
                }
            }
        }
        
        return $result;
    }
    
    public function getGroupParamAddon() {

        $this->view->groupMetaDataId = Input::post('groupMetaDataId');
        $this->view->paramPath = Input::post('paramPath');
        $this->view->depth = Input::post('depth');
        $this->view->dataType = Input::post('dataType');
        $this->view->lookupType = Input::post('lookupType');
        $this->view->isGroup = Input::post('isGroup');
        $this->view->isNew = Input::post('isNew');
        
        $this->view->paramRow = $this->model->getGroupParamAddonModel($this->view->groupMetaDataId, $this->view->paramPath, $this->view->isNew, $this->view->isGroup);
        $this->view->paramRow['LOOKUP_META_DATA_ID'] = Input::post('lookupMetaDataId');
        
        $this->view->maskData = (new Mdmetadata())->getMetaFieldPattern();
        $this->view->positionList = Mdwidget::positionList();
        $this->view->controlSubType = (new Mdcommon())->controlSubType($this->view->dataType, $this->view->lookupType, true);
        
        if ($this->view->isGroup == 'true') {
            $this->view->render('system/link/group/v2/inputAddonGroup', self::$viewPath);
        } else {
            $this->view->dataMask = (new Mdmetadata())->getMetaFieldPattern(true);
            $this->view->render('system/link/group/v2/inputAddon', self::$viewPath);
        }
    }
    
    public function getChildGroupParam() {

        $this->view->groupMetaDataId = Input::post('groupMetaDataId');
        $this->view->rowId = Input::post('rowId');
        $this->view->depth = Input::post('depth');
        $this->view->isNew = Input::post('isNew');
        
        $this->view->fieldDataTypeOptions = $this->model->fieldDataTypeComboOptions('IS_DV = 1');
        
        if ($this->view->isNew == '0') {
            
            $this->view->params = $this->model->getGroupParamsModel($this->view->groupMetaDataId, $this->view->rowId);
            
        } else {
            
            $paramPath = Input::post('paramPath');
            $this->view->params = $this->model->getMetaGroupInputParams($paramPath, $this->view->depth, $this->view->rowId, true);
            
            $this->view->newRowId = Input::post('newRowId');
        }
        
        if (Input::postCheck('alreadyPaths')) {
            
            $alreadyPaths = Input::post('alreadyPaths');
            
            foreach ($this->view->params as $p => $paramRow) {
                if (isset($alreadyPaths[strtolower($paramRow['FIELD_PATH'])])) {
                    unset($this->view->params[$p]);
                }
            }
            
            $this->view->params = array_values($this->view->params);
        }
        
        $this->view->render('system/link/group/v2/input', self::$viewPath);
    }
    
    public function groupParamAddCode() {
        
        $parentId = Input::numeric('parentId');
        $realCode = trim(Input::post('code'));
        $code = Str::lower($realCode);
        
        $isName = $isId = $row = false;
        $isValueNotEmpty = true;

        if ($code == '') {
            $isValueNotEmpty = false;
        }
        
        if ($isValueNotEmpty) {

            if (Input::postCheck('isName')) {
                if (Input::post('isName') == 'true') {
                    $isName = true;
                }
                if (Input::post('isName') == 'idselect') {
                    $isId = true;
                }
            }
            
            if ($isId) {
                
                $result = $this->model->processParamAddCodeModel($code, 'META_DATA_ID');
                $row = $result;
                
            } else {
                
                if ($isName) {
                    $result = $this->model->processParamAddCodeModel($code, 'META_DATA_NAME');
                    $row = $result;
                } else {
                    $result = $this->model->processParamAddCodeModel($code, 'META_DATA_CODE');
                    $row = $result;
                }
            }
        }
        
        $paramGroupPath = Input::post('paramGroupPath');
        
        if (!empty($paramGroupPath)) {
            $paramGroupPath = substr($paramGroupPath, 0, (strrpos($paramGroupPath, '.'))).'.';
        }
        
        if ($row) {
            
            $columnName = $row['PARAM_REAL_PATH'];
            $columnNameLower = strtolower(substr($columnName, 0, 6));
            
            if ($columnNameLower == 'filter') {
                
                $columnName = '';
                
            } else {
                if (countUpperLetter($columnName) > 0 && countLowerLetter($columnName) > 0) {
                    $columnName = rtrim(ltrim(preg_replace('/([A-Z])/', '_$1', $columnName), '_'), '_');
                } 

                $columnName = strtoupper($columnName);
            }
            
            $row['ID'] = getUID();
            $row['PARENT_ID'] = $parentId;
            $row['FIELD_PATH'] = $paramGroupPath.$row['PARAM_REAL_PATH'];
            
            $row['IS_SELECT'] = 1;
            $row['IS_SHOW_BASKET'] = '';
            $row['IS_RENDER_SHOW'] = '';
            $row['IS_CRITERIA'] = '';
            
            $row['BODY_ALIGN'] = '';
            $row['HEADER_ALIGN'] = '';
            $row['TEXT_COLOR'] = '';
            $row['TEXT_TRANSFORM'] = '';
            $row['TEXT_WEIGHT'] = '';
            $row['BG_COLOR'] = '';
            $row['FONT_SIZE'] = '';
            $row['INPUT_NAME'] = '';
            $row['COLUMN_NAME'] = $columnName;
            $row['COLUMN_AGGREGATE'] = '';
            $row['AGGREGATE_ALIAS_PATH'] = '';
            
            $this->view->params = array($row);
            
        } else {
            
            $columnName = $realCode;
            $columnNameLower = strtolower(substr($columnName, 0, 6));
            
            if ($columnNameLower == 'filter') {
                
                $columnName = '';
                
            } else {
                if (countUpperLetter($columnName) > 0 && countLowerLetter($columnName) > 0) {
                    $columnName = rtrim(ltrim(preg_replace('/([A-Z])/', '_$1', $columnName), '_'), '_');
                }

                $columnName = strtoupper($columnName);
            }
            
            $this->view->params = array(
                array(
                    'ID' => getUID(),
                    'PARENT_ID' => $parentId,
                    'DATA_TYPE' => 'string', 
                    'FIELD_PATH' => $paramGroupPath.$realCode,
                    'IS_SHOW' => '',
                    'IS_REQUIRED' => '',
                    'IS_SELECT' => 1, 
                    'IS_SHOW_BASKET' => '', 
                    'IS_RENDER_SHOW' => '', 
                    'IS_CRITERIA' => '', 
                    'DEFAULT_VALUE' => '',
                    'LOOKUP_META_DATA_ID' => '',
                    'LOOKUP_META_DATA_CODE' => '',
                    'LOOKUP_META_DATA_NAME' => '',
                    'LOOKUP_TYPE' => '',
                    'DISPLAY_FIELD' => '',
                    'VALUE_FIELD' => '',
                    'CHOOSE_TYPE' => '',
                    'RECORD_TYPE' => '',
                    'TAB_NAME' => '',
                    'LABEL_NAME' => '',
                    'PARAM_NAME' => $realCode, 
                    'COLUMN_AGGREGATE' => '', 
                    'BODY_ALIGN' => '', 
                    'HEADER_ALIGN' => '', 
                    'TEXT_COLOR' => '', 
                    'TEXT_TRANSFORM' => '', 
                    'TEXT_WEIGHT' => '', 
                    'BG_COLOR' => '', 
                    'FONT_SIZE' => '', 
                    'INPUT_NAME' => '', 
                    'AGGREGATE_ALIAS_PATH' => '', 
                    'COLUMN_NAME' => $columnName
                )
            );
        }
        
        $this->view->fieldDataTypeOptions = $this->model->fieldDataTypeComboOptions('IS_DV = 1');
        $this->view->rowId = $parentId;
        $this->view->depth = Input::numeric('depth');
        $this->view->isNew = 1;
        
        echo json_encode(array(
            'path' => $this->view->params[0]['FIELD_PATH'], 
            'html' => $this->view->renderPrint('system/link/group/v2/input', self::$viewPath)
        )); exit;
    }
    
    public function groupParamAddMulti() {
        
        $paramGroupPath = Input::post('paramGroupPath');
        
        if (!empty($paramGroupPath)) {
            $paramGroupPath = substr($paramGroupPath, 0, (strrpos($paramGroupPath, '.'))).'.';
        }
        
        $this->view->params = $this->model->groupParamAddMultiModel($paramGroupPath);
        
        $this->view->fieldDataTypeOptions = $this->model->fieldDataTypeComboOptions('IS_DV = 1');
        $this->view->rowId = Input::numeric('parentId');
        $this->view->depth = Input::numeric('depth');
        $this->view->isNew = 1; 
        
        $this->view->render('system/link/group/v2/input', self::$viewPath);
    }

    public function weblink() {
        
        $response = array('Html' => '');

        $getLink = $this->model->getBookmarkData(Input::numeric('metaDataId'));
        
        if ($getLink['BOOKMARK_URL']) {
            $getExp = explode('/', $getLink['BOOKMARK_URL']);

            $firstUpperClassName = ucfirst($getExp[0]);
            $methodName = $getExp[1];
            
            unset($getExp[0]);
            unset($getExp[1]);
            
            $ctrInstance = new $firstUpperClassName;
            
            if (count($getExp) > 0) {
                
                if (count($getExp) == 1) {
                    
                    if ($getExp[2]) {
                        $response = $ctrInstance->{$methodName}(Input::param($getExp[2]));
                    } else {
                        $response = call_user_func_array(array($ctrInstance, $methodName), $getExp);
                    }
                    
                } else {
                    $response = call_user_func_array(array($ctrInstance, $methodName), $getExp);
                }
                
            } else {
                $response = $ctrInstance->{$methodName}(Input::post('workSpaceParams'));
            }
        }

        jsonResponse($response);
    }
    
    public function gotoFolder($metaDataId = '') {
        
        if ($metaDataId == '') {
            Message::add('e', '', URL . 'mdmetadata/system');
        }
        
        $cleanMetaDataId = Input::param($metaDataId);
        $metaRow = $this->model->getMetaDataModel($cleanMetaDataId, true);
        
        if (isset($metaRow['FOLDER_ID'])) {
            Message::add('s', '', URL . 'mdmetadata/system#objectType=folder&objectId='.$metaRow['FOLDER_ID'].'&focusMetaId='.$cleanMetaDataId);
        } else {
            Message::add('s', '', URL . 'mdmetadata/system');
        }
    }
    
    public function gotoEditMeta($metaDataId = '') {
        
        if ($metaDataId == '') {
            Message::add('e', '', URL . 'mdmetadata/system');
        }
        
        $cleanMetaDataId = Input::param($metaDataId);
        $metaRow = $this->model->getMetaDataModel($cleanMetaDataId, true);
        
        if (isset($metaRow['FOLDER_ID'])) {
            Message::add('s', '', URL . 'mdmetadata/system#objectType=folder&objectId='.$metaRow['FOLDER_ID'].'&focusMetaId='.$cleanMetaDataId.'&editMetaId='.$cleanMetaDataId);
        } else {
            Message::add('s', '', URL . 'mdmetadata/system#focusMetaId='.$cleanMetaDataId.'&editMetaId='.$cleanMetaDataId);
        }
    }

    public function getGroupChildDM() {
        jsonResponse($this->model->getGroupParamsModel(Input::post('groupMetaDataId')));
    }
    
    public function changeMetaFolderMap() {
        $response = $this->model->changeMetaFolderMapModel();
        jsonResponse($response);
    }

    public function wfmUserDataSelectableGrid() {

        $this->view->chooseType = Input::post('chooseType');
        $this->view->singleSelect = ($this->view->chooseType == 'multi') ? 'false' : 'true';
        $this->view->defaultCriteria = $defaultCriteria = '';
        $this->view->searchParams = '';

        $this->view->isNamedParam = false;

        if (Input::postCheck('params') && Input::isEmpty('params') == false) {
            
            $requestParams = Input::post('params');
            parse_str($requestParams, $params);
            
            if (count($params) > 0) {
                
                foreach ($params['param'] as $k => $v) {
                    $this->view->{$k} = $v;
                }
                $this->view->defaultCriteria = "defaultCriteriaData: '" . Str::remove_querystring_var($requestParams, 'autoSearch') . "'";
                $defaultCriteria = Str::remove_querystring_var($requestParams, 'autoSearch');                
                $this->view->isNamedParam = true; 
            }
        }
        $selectedRowData = Input::post('selectedRow');
        $selectedRowData['isCallAutoStatus'] = '1';
        $this->view->wfmStatusButtons = (new Mdworkflow())->getWorkflowNextStatus(Input::post('dataViewId'), $selectedRowData, Input::post('refStructureId'), true, true);
        
        if (is_array($this->view->wfmStatusButtons)) {
            foreach ($this->view->wfmStatusButtons as $key => $row) {
                if ($row['processname']) {
                    $row['wfmstatusname'] = $row['processname'];
                }
                $this->view->wfmStatusButtons[$key] = $row;
            }
        } else {
            $this->view->wfmStatusButtons = array();
        }

        $islookup = '1487162489111041';
        $param = array(
            'systemMetaGroupId' => $islookup,
            'showQuery' => 0,
            'ignorePermission' => 1,
            'paging' => array(
                'offset' => 1,
                'pageSize' => 100
            ),                
        );
        
        $this->view->rulesData = array();
        $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

        $ml = &getInstance();
        $ml->load->model('mdobject', 'middleware/models/');        
        $this->view->mainMetaDataValue = $ml->model->getStandartFieldModel($islookup, 'meta_value_id');
        $this->view->mainMetaDataName = $ml->model->getStandartFieldModel($islookup, 'meta_value_name');            

        if ($data['status'] == 'success' && isset($data['result'])) {
            
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);            
            $this->view->rulesData = $data['result'];
        }        
        
        $this->view->dataGridHeader = $ml->model->renderDataViewGridModel('1487153693627', 'sysUmUserListWFM', '', true);
        $this->view->searchParams = "queryParams: {metaDataId:'1487153693627', defaultCriteriaData:'".$defaultCriteria."'},";
        $this->view->searchForm = $this->view->renderPrint('common/sub/wfmSearchForm', self::$viewPath);

        $response = array(
            'Html' => $this->view->renderPrint('common/sub/wfmUserselectableGrid', self::$viewPath),
            'Title' => 'Хэрэглэгчийн жагсаалт',
            'choose_btn' => $this->lang->line('choose_btn'),
            'close_btn' => $this->lang->line('close_btn'),
            'addbasket_btn' => $this->lang->line('addbasket_btn')
        );
        echo json_encode($response); exit;
    }    

    public function changeWfmRule() {
        $this->view->rulesData = array();

        $selectedRowData = Input::post('selectedRow');
        $selectedRowData['isCallAutoStatus'] = '1';        
        $this->view->wfmStatusButtons = (new Mdworkflow())->getWorkflowNextStatus(Input::post('dataViewId'), $selectedRowData, Input::post('refStructureId'), true, true);
        
        foreach ($this->view->wfmStatusButtons as $key => $row) {
            if ($row['processname']) {
                $row['wfmstatusname'] = $row['processname'];
            }
            $this->view->wfmStatusButtons[$key] = $row;
        }

        $islookup = '1487162489111041';
        $param = array(
            'systemMetaGroupId' => $islookup,
            'showQuery' => 0,
            'ignorePermission' => 1,
            'paging' => array(
                'offset' => 1,
                'pageSize' => 100
            ),                
        );
        
        $this->view->rulesData = array();
        $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);        

        $ml = &getInstance();
        $ml->load->model('mdobject', 'middleware/models/');        
        $this->view->mainMetaDataValue = $ml->model->getStandartFieldModel($islookup, 'meta_value_id');
        $this->view->mainMetaDataName = $ml->model->getStandartFieldModel($islookup, 'meta_value_name');            
        $this->view->wfmRuleId = Input::post('ruleId');

        if ($data['status'] == 'success' && isset($data['result'])) {
            
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);            
            $this->view->rulesData = $data['result'];
        }        

        $response = array(
            'Html' => $this->view->renderPrint('common/sub/wfmSearchForm', self::$viewPath),
            'Title' => 'Дүрэм өөрчлөх',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;        
    }

    public function getMetaDataDrill($metaDataId, $getFolder = false) {
        $this->load->model('mdmetadata', 'middleware/models/');
        $row = $this->model->getMetaDataDrillModel($metaDataId, $getFolder);
        if (!is_ajax_request()) {
            return $row;
        } else {
            echo json_encode($row);
        }
    }    
    
}