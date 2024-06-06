<?php

if (!defined('_VALID_PHP'))
    exit('Direct access to this location is not allowed.');

/**
 * Mdasset Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Asset Management
 * @author	Ts.Ulaankhuu <ulaankhuu@veritech.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Assetmanagement
 */
class Mdasset extends Controller {

    const viewPath = 'middleware/views/asset/';
    const glviewPath = 'middleware/views/generalledger/';

    private static $assetsPath = 'middleware/views/asset/mobi/';
    private static $viewPath2 = 'middleware/views/asset/government/';
    private static $viewPath3 = 'middleware/views/asset/ea/';
    private static $viewPath4 = 'middleware/views/asset/covid/';
    private static $isGLComboDataArray = array(array('ID' => 1, 'NAME' => 'Тийм'), array('ID' => 0, 'NAME' => 'Үгүй'));
    public static $faAssetDeprDataView = 'FA_ASSET_BOOK_DEPR_LIST';
    public static $faAssetDeprFilterDataView = 'FA_ASSET_BOOK_DEPR_LIST_CACHE';
    public static $faAssetDeprObjectId = 20005;
    public static $faAssetDeprBookTypeId = 15;

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
        self::$faAssetDeprDataView = Config::getFromCacheDefault('DEPR_CALC_DATAVIEW', null, 'FA_ASSET_BOOK_DEPR_LIST');
    }

    public function assets() {
        $cssFiles = array(
            "assets/custom/css/bp-skins/skin.css"
        );

        $buffer = '';

        foreach ($cssFiles as $cssFile) {
            $buffer .= file_get_contents($cssFile);
        }

        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
        $buffer = str_replace(': ', ':', $buffer);
        $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);

        ob_start("ob_gzhandler");

        header('Cache-Control: public');
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');
        header("Content-type: text/css");

        echo($buffer);
    }

    public function deprecation($param = null) {

        $this->view->title = 'Элэгдэл тооцох';

        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        $this->view->fullUrlJs = array('middleware/assets/js/mdgl.js');

        $this->view->isAjax = is_ajax_request();

        if (!$this->view->isAjax) {
            $this->view->render('header');
        }

        $this->view->uniqId = getUID();

        $lookupCode = Input::post('lookupCode');
        $this->view->filterSystemTypeId = $param ? '0' : '1';

        $this->load->model('mdmetadata', 'middleware/models/');
        $this->view->assetDVMetadataId = $this->model->getMetaDataIdByCodeModel(self::$faAssetDeprDataView);
        $departmentDvId = $this->model->getMetaDataIdByCodeModel('ORG_DEPARTMENT_COST_CENTER_SUB');
        $this->load->model('mdasset', 'middleware/models/');
        $this->view->selectDefaultDepartment = $this->model->getDataMartDvRowsModel($departmentDvId, array(
            'id' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionUserKeyDepartmentId()
                )
            )
        ));
        $this->view->selectDefaultDepartment = $this->view->selectDefaultDepartment ? $this->view->selectDefaultDepartment[0] : array();

        $this->view->calcMethod = Config::getFromCache('DEPR_METHOD');
        $this->view->isNotUseGLAsset = Config::getFromCache('IS_NOT_USE_GL_ASSET');
        $this->view->isBookDateDisable = Config::getFromCache('ISUSEDAILYBOOKREMOVE');
        $this->view->IS_NOT_SHOW_ACC_IN_DEPR = Config::getFromCache('IS_NOT_SHOW_ACC_IN_DEPR');
        $this->view->IS_NOT_SHOW_SK_IN_DEPR = Config::getFromCache('IS_NOT_SHOW_SK_IN_DEPR');
        $this->view->IS_NOT_SHOW_EMPLOYEE_IN_DEPR = Config::getFromCache('IS_NOT_SHOW_EMPLOYEE_IN_DEPR');
        $this->view->IS_NOT_SHOW_CUSTOMER_IN_DEPR = Config::getFromCache('IS_NOT_SHOW_CUSTOMER_IN_DEPR');
        $this->view->IS_NOT_SHOW_CAT_IN_DEPR = Config::getFromCache('IS_NOT_SHOW_CAT_IN_DEPR');
        $this->view->IS_NOT_SHOW_DIFF_IN_DEPR = Config::getFromCache('IS_NOT_SHOW_DIFF_IN_DEPR');

        $this->view->header = $this->view->renderPrint('depreciation/header', self::viewPath);

        $this->view->additionalTabContent = $this->view->renderPrint('depreciation/additional', self::viewPath);
        $this->view->render('depreciation/index', self::viewPath);

        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }
    }

    public function getDepreciationAssets() {
        $this->load->model('mdobject', 'middleware/models/');

        $uniqId = Input::post('uniqId');
        $cacheFileName = 'vd_' . $uniqId;

        $pageNumber = 1;
        $pageSize = 20;

        $tmp_dir = Mdcommon::getCacheDirectory();
        $cache = phpFastCache();

        $virtualDatas = glob($tmp_dir . '/*/vd/' . $cacheFileName . '.txt');

        if (count($virtualDatas)) {
            @unlink($virtualDatas[0]);
        }

        $result = $this->model->dataViewDataGridModel(false);

        $cache->set($cacheFileName, $result, Mdwebservice::$expressionCacheTime);

        $start = --$pageNumber * $pageSize;
        $result['rows'] = array_slice($result['rows'], $start, $pageSize);

        header('Content-Type: application/json');

        echo json_encode($result);
    }

    public function getDepreciationAssetsCache() {

        $result = $this->model->getDepreciationAssetsCacheModel();

        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }

    public function getDepreciationAssetsNavigation() {

        $result = $this->model->getDepreciationAssetsNavigationModel();

        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }

    public function detailedDeprAsset() {

        $this->view->usageYear = Input::post('usageyear');
        $this->view->stusageyear = Input::post('stusageyear');
        $this->view->originalusageyear = Input::post('originalusageyear');
        $this->view->originalstusageyear = Input::post('originalstusageyear');
        $this->view->accountName = Input::post('accountname');
        $this->view->assetLocationName = Input::post('assetLocationName');
        $this->view->assetEmployeeName = Input::post('assetEmployeeName');
        $this->view->assetDeprMethodName = Input::post('assetDeprMethodName');
        $this->view->accountId = Input::post('accountId');
        $this->view->customerCode = Input::post('customerCode');

        $response = array(
            'html' => $this->view->renderPrint('depreciation/detailAsset', self::viewPath),
            'title' => 'Дэлгэрэнгүй',
            'close_btn' => Lang::line('close_btn'),
        );
        echo json_encode($response);
        exit;
    }

    public function deprLookupAutoComplete() {

        $type = Input::post('type');
        $lookupCode = Input::post('lookupCode');
        $where = '';

        $this->load->model('mdmetadata', 'middleware/models/');
        $lookupId = $this->model->getMetaDataIdByCodeModel($lookupCode);

        if ($lookupId && $lookupId != '') {

            $this->load->model('mdobject', 'middleware/models/');

            $idField = $this->model->getDataViewMetaValueId($lookupId);
            $codeField = $this->model->getDataViewMetaValueCode($lookupId);
            $nameField = $this->model->getDataViewMetaValueName($lookupId);

            if ($type == 'code') {

                if ($codeField) {
                    $q = Input::post('q');
                    $q = trim(str_replace('_', '', str_replace('_-_', '', $q)));

                    (Array) $criteria[$codeField][] = array(
                        'operator' => 'LIKE',
                        'operand' => $q . '%'
                    );

                    parse_str(urldecode(Input::post('params')), $cardFilterData);

                    if (count($cardFilterData) > 0) {
                        foreach ($cardFilterData as $key => $val) {
                            if (!empty($val)) {
                                $criteria[$key][] = array(
                                    'operator' => '=',
                                    'operand' => Input::param($val)
                                );
                            }
                        }
                    }

                    $result = $this->model->getRowsDataViewByCriteriaModel($lookupId, $criteria, $idField, $codeField, $nameField, $where);
                    echo json_encode($result);
                }
            } else {

                if ($nameField) {
                    $q = Input::post('q');
                    $q = trim(str_replace('_', '', str_replace('_-_', '', $q)));

                    (Array) $criteria[$nameField][] = array(
                        'operator' => 'LIKE',
                        'operand' => '%' . $q . '%'
                    );
                    $result = $this->model->getRowsDataViewByCriteriaModel($lookupId, $criteria, $idField, $codeField, $nameField, $where);
                    echo json_encode($result);
                }
            }
        }
    }

    public function deprAutoCompleteById() {

        $this->load->model('mdmetadata', 'middleware/models/');

        $lookupCode = Input::post('lookupCode');

        $lookupId = $this->model->getMetaDataIdByCodeModel($lookupCode);
        $code = Str::lower(trim(Input::post('code')));

        $isName = false;
        $isCode = false;
        $isValueNotEmpty = true;
        $row = false;

        if ($code == '') {
            $isValueNotEmpty = false;
        }

        $this->load->model('mdobject', 'middleware/models/');

        if ($lookupId && $lookupId != '' && $isValueNotEmpty) {

            if (Input::postCheck('isName')) {
                if (Input::post('isName') == 'true') {
                    $isName = true;
                } else {
                    $isCode = true;
                }
            }

            if ($isName) {
                if ($nameField = $this->model->getDataViewMetaValueName($lookupId)) {

                    $this->load->model('mdobject', 'middleware/models/');

                    $criteria[$nameField][] = array(
                        'operator' => '=',
                        'operand' => $code
                    );

                    parse_str(urldecode(Input::post('params')), $cardFilterData);

                    if (count($cardFilterData) > 0) {
                        foreach ($cardFilterData as $key => $val) {
                            if (!empty($val)) {
                                $criteria[$key][] = array(
                                    'operator' => '=',
                                    'operand' => Input::param($val)
                                );
                            }
                        }
                    }

                    $result = $this->model->getDataViewByCriteriaModel($lookupId, $criteria);

                    if ($result) {
                        $idField = strtolower($this->model->getDataViewMetaValueId($lookupId));
                        $codeField = strtolower($this->model->getDataViewMetaValueCode($lookupId));
                        $nameField = strtolower($nameField);

                        $row = array(
                            'META_VALUE_ID' => ($idField ? $result[$idField] : (isset($result['id']) ? $result['id'] : "")),
                            'META_VALUE_CODE' => (isset($result[$codeField]) ? $result[$codeField] : ""),
                            'META_VALUE_NAME' => (isset($result[$nameField]) ? $result[$nameField] : ""),
                            'rowData' => $result
                        );
                    }
                }
            } else {
                if ($codeField = $this->model->getDataViewMetaValueCode($lookupId)) {

                    (Array) $criteria[$codeField][] = array(
                        'operator' => '=',
                        'operand' => $code
                    );

                    parse_str(urldecode(Input::post('params')), $cardFilterData);

                    if (count($cardFilterData) > 0) {
                        foreach ($cardFilterData as $key => $val) {
                            if (!empty($val)) {
                                $criteria[$key][] = array(
                                    'operator' => '=',
                                    'operand' => Input::param($val)
                                );
                            }
                        }
                    }

                    $result = $this->model->getDataViewByCriteriaModel($lookupId, $criteria);

                    if ($result) {
                        $idField = strtolower($this->model->getDataViewMetaValueId($lookupId));
                        $nameField = strtolower($this->model->getDataViewMetaValueName($lookupId));
                        $codeField = strtolower($codeField);

                        $row = array(
                            'META_VALUE_ID' => ($idField ? $result[$idField] : (isset($result['id']) ? $result['id'] : "")),
                            'META_VALUE_CODE' => (isset($result[$codeField]) ? $result[$codeField] : ""),
                            'META_VALUE_NAME' => (isset($result[$nameField]) ? $result[$nameField] : ""),
                            'rowData' => $result
                        );
                    }
                }
            }
        }

        if ($row) {
            echo json_encode($row);
        } else {
            $response = array(
                'META_VALUE_ID' => '',
                'META_VALUE_CODE' => '',
                'META_VALUE_NAME' => ''
            );
            echo json_encode($response);
        }
        exit;
    }

    public function createGlFromDepr() {

        $generalLedgerParams = array(
            'description' => 'Үндсэн хөрөнгийн элэгдэл ' . Input::post('glDescription'),
            'bookTypeId' => self::$faAssetDeprBookTypeId,
            'objectId' => self::$faAssetDeprObjectId,
            'bookDate' => Input::post('bookDate'),
            'assetKeeperKeyId' => Input::post('cashierKeeperId'),
            'calcStandardAmt' => Input::post('calcstandardamt'),
            'cacheLockId' => Input::post('uniqId')
            /* 'deprAccountId' => Input::post('deprAccountId'), */
        );

        $this->load->model('mdasset', 'middleware/models/');
        $this->model->modifyCacheRows();

        $this->load->model('mdgl', 'middleware/models/');

        $result = $this->model->getTemplateModel($generalLedgerParams);

        if ($result['status'] == 'success') {

            $this->view->uniqId = getUID();
            $this->view->isFieldSet = (Input::postCheck('bpTabLength') ? Input::post('bpTabLength') : 1);
            $this->view->paramList = $result['data'];
            $this->view->glBpMainWindowId = "glTemplateSectionStatic";

            $this->view->currencyList = $this->model->currencyListModel();

            $this->view->isDataView = false;
            $this->view->dataViewId = null;
            $this->view->incomeTaxDeduction = Config::getFromCache('FIN_INCOMETAX_DEDUCTION');

            if (Input::isEmpty('dataViewId') == false) {
                $this->view->isDataView = true;
                $this->view->dataViewId = Input::post('dataViewId');
            }

            $this->view->amountScale = (new Mdgl())->getAmountScale();

            $this->view->header1 = $this->view->renderPrint('main/glGridHeader1', self::glviewPath);
            $this->view->header2 = $this->view->renderPrint('main/glGridHeader2', self::glviewPath);
            $this->view->gridBodyData = $this->view->renderPrint('main/gridBodyData', self::glviewPath);
            $glhtml = $this->view->renderPrint('main/glGridForProcess', self::glviewPath);

            $response = array(
                'Html' => $glhtml,
                'status' => $result['status']
            );
        } else {
            $response = array(
                'status' => $result['status'],
                'message' => $result['message']
            );
        }

        echo json_encode($response);
        exit;
    }

    public function createAssetBook() {
        $result = $this->model->createAssetBookModel();
        echo json_encode($result);
        exit;
    }

    public function renderConnectionMobi($assetId = '', $selectId = '') {
        $this->view->selectedRow = array();
        $this->view->taskId = (Input::postCheck('taskId') ? Input::post('taskId') : '');
        $this->view->assetId = ($assetId) ? $assetId : (Input::postCheck('assetId') ? Input::post('assetId') : '');

        $this->view->metaDataId = '1529649071315232'; //mobSiteEquipmentDropList
        $this->view->taskTabMetaDataId = '1533787099324'; //mobTaskByEquipmentList
        $this->view->pkiTabMetaDataId = '1533787143129'; //MOB_EQUIPMENT_CONFIG_DV_002
        $this->view->uniqId = getUID();

        $this->view->css = array_unique(array_merge(array('custom/css/vr-card-menu.css'), AssetNew::metaCss()));
        $this->view->fullUrlCss = array('middleware/assets/css/mobi/style.css');
        $this->view->js = array_unique(array_merge(array('custom/pages/scripts/appmenu.js'), AssetNew::metaOtherJs()));
        $this->view->selectedTreeId = $selectId;
        $param = array(
            'assetId' => ($assetId) ? $assetId : $this->view->assetId,
            'id' => ($assetId) ? $assetId :  $this->view->assetId
        );
        includeLib('Utils/Functions');
        $selectedRow = Functions::runProcess('mobGetCheckKeyEquipmentList_004', $param);
        
        $this->view->selectedRow = Arr::encode(array('dataRow' => isset($selectedRow['result']) ? $selectedRow['result'] : array('assetid' => $this->view->assetId)));

        $this->load->model('mdobject', 'middleware/models/');
        $this->view->mainMetaDataCode = $this->model->getStandartFieldModel($this->view->metaDataId, 'meta_value_code');
        $this->view->mainMetaDataName = $this->model->getStandartFieldModel($this->view->metaDataId, 'meta_value_name');
        $this->view->isEdit = 'true';

        $this->view->title = 'Сайтын ажил';

        if (!is_ajax_request()) {
            $this->view->render('header');
        }

        if (Input::postCheck('dataType') && Input::post('dataType') == 'json') {
            $response = array(
                'Html' => $this->view->renderPrint('index', self::$assetsPath),
                'Title' => $this->lang->line('META_00112'),
                'mainId' => $this->view->uniqId,
                'finish_btn' => $this->lang->line('finish_btn'),
                'close_btn' => $this->lang->line('close_btn')
            );
            echo json_encode($response);
            exit;
        } else {
            $this->view->render('index', self::$assetsPath);
        }

        if (!is_ajax_request()) {
            $this->view->render('footer');
        }
    }

    public function renderConnectionViewMobi() {
        $this->view->selectedRow = array();
        $this->view->taskId = (Input::postCheck('taskId') ? Input::post('taskId') : '');
        $this->view->assetId = (Input::postCheck('assetId') ? Input::post('assetId') : '');

        $this->view->metaDataId = '1529649071315232'; //mobSiteEquipmentDropList
        $this->view->taskTabMetaDataId = '1533787099324'; //mobTaskByEquipmentList
        $this->view->pkiTabMetaDataId = '1533787143129'; //MOB_EQUIPMENT_CONFIG_DV_002
        $this->view->uniqId = getUID();

        $this->view->css = array_unique(array_merge(array('custom/css/vr-card-menu.css'), AssetNew::metaCss()));
        $this->view->fullUrlCss = array('middleware/assets/css/mobi/style.css');
        $this->view->js = array_unique(array_merge(array('custom/pages/scripts/appmenu.js'), AssetNew::metaOtherJs()));
        $param = array(
            'assetId' => $this->view->assetId,
            'id' => $this->view->assetId
        );
        includeLib('Utils/Functions');
        $selectedRow = Functions::runProcess('mobGetCheckKeyEquipmentList_004', $param);
        $this->view->selectedRow = Arr::encode(array('dataRow' => isset($selectedRow['result']) ? $selectedRow['result'] : array('assetid' => $this->view->assetId)));

        $this->load->model('mdobject', 'middleware/models/');
        $this->view->mainMetaDataCode = $this->model->getStandartFieldModel($this->view->metaDataId, 'meta_value_code');
        $this->view->mainMetaDataName = $this->model->getStandartFieldModel($this->view->metaDataId, 'meta_value_name');
        $this->view->isEdit = 'false';

        $this->view->title = 'Сайтын ажил';

        if (!is_ajax_request()) {
            $this->view->render('header');
        }

        if (Input::postCheck('dataType') && Input::post('dataType') == 'json') {
            $response = array(
                'Html' => $this->view->renderPrint('index', self::$assetsPath),
                'Title' => Lang::line('META_00112'),
                'mainId' => $this->view->uniqId,
                'finish_btn' => Lang::line('finish_btn'),
                'close_btn' => Lang::line('close_btn')
            );
            echo json_encode($response);
            exit;
        } else {
            $this->view->render('index', self::$assetsPath);
        }
        if (!is_ajax_request()) {
            $this->view->render('footer');
        }
    }

    public function getAssetsListTree() {
            
        $lifecycleId = Input::get('lifecycleId');
        $recordId = Input::get('assetId');
        $selectedTreeId = Input::get('selectedTreeId');
        $lifecycletaskId = (Input::getCheck('lifecycletaskId')) ? Input::get('lifecycletaskId') : '';
            
        if (Input::get('locationId')) {
            $param = array(
                'parentid' => array(
                    array(
                        'operator' => '=',
                        'operand' => Input::get('locationId')
                    )
                )
            );

            $result = $this->model->getAssetsListTreeModel('1556641184469', $lifecycleId, $recordId, $param, $lifecycletaskId);
            
        } else {

            $param = array(
                'parentid' => array(
                    array(
                        'operator' => 'IS NULL',
                        'operand' => ''
                    ),
                ),
            );

            if (Input::getCheck('selectedRow') && Input::isEmptyGet('selectedRow') === false) {

                $selectedRow = Arr::decode(Input::get('selectedRow'));

                $filterAssetId = (!empty($selectedRow['dataRow']) ? $selectedRow['dataRow']['assetid'] : '');

                if ($filterAssetId) {

                    $param = array(
                        'filterAssetId' => array(
                            array(
                                'operator' => '=',
                                'operand' => $filterAssetId
                            )
                        )
                    );
                }
            }

            if (Input::postCheck('selectedRowData') && Input::isEmpty('selectedRowData') === false) {
                parse_str($selectedRow, Input::post('selectedRow'));
            }

            $result = $this->model->getAssetsListTreeModel('1556771042438414', $lifecycleId, $recordId, $param, $lifecycletaskId);
        }
        
        $response = $this->recursiveTreeList($result, $lifecycleId, $recordId, $lifecycletaskId, false, $selectedTreeId);
        
        echo json_encode($response);
        exit;
    }

    private function recursiveTreeList($result, $lifecycleId, $recordId, $lifecycletaskId = null, $isOpen = false, $selectedTreeId = '') {

        $response = array();

        foreach ($result as $value) {

            $taskname = isset($value['name']) ? $value['name'] : '';
            $rowJson = htmlentities(json_encode($value), ENT_QUOTES, 'UTF-8');
            
            $drawTreeList = array(
                'icon' => 'hidden',
                'id' => $value['id'],
                'li_attr' => array('data-tid' => $value['parentid']),
                'data' => $value,
                'state' => array(
                    'disabled' => false,
                    'loaded' => true, 
                    'opened' => false, 
                    'selected' => false,
                ),
                'a_attr' => '',
                'children' => false,
                'text' => '<div class="selected-row-link" data-row-data="' . $rowJson . '"><img src="' . $value['icon'] . '" onerror="onFileImgError(this);" height="24" width="24" style="border:1px solid #CCC; margin-right: 5px"></img><span title="' . $value['name'] . '">' . $taskname . '</span></div>',
            );
            
            $param = array(
                'parentid' => array(
                    array(
                        'operator' => '=',
                        'operand' => $value['locationid']
                    )
                )
            );

            $resultChild = array(); //$this->model->getAssetsListTreeModel('1556641184469', $lifecycleId, $recordId, $param, $lifecycletaskId);
			
            if (issetParam($value['haschild']) === '1') {
                $drawTreeList['children'] = true;
                /*$drawTreeList['state'] = array(
                                                'disabled' => false,
                                                'loaded' => false,
                                                'opened' => ($selectedTreeId) ? true : false, 
                                                'selected' => false,
                                            );*/
            }
            
            $response[] = $drawTreeList;
        }

        return $response;
    }

    public function getConnectionData() {

        $this->view->assetId = (Input::postCheck('assetId') && Input::post('assetId')) ? Input::post('assetId') : '0';

        $this->view->uniqId = Input::post('uniqId');
        $this->view->locationId = Input::post('locationId');
        $this->view->directorypath = Input::post('directorypath');
        $this->view->srcRecordId = Input::post('srcRecordId');
        $this->view->checkkeyid = Input::post('checkkeyid');
        $this->view->taskId = (Input::postCheck('taskId') && Input::post('taskId')) ? Input::post('taskId') : '';

        $this->view->connectionData = $this->model->getConnectionDataModel($this->view->assetId, $this->view->locationId, $this->view->checkkeyid);
        
        $this->view->connectionPort = array();
        $this->view->installation = array();
        $this->view->isEdit = Input::postCheck('isEdit') ? Input::post('isEdit') : 'false';

        if (isset($this->view->connectionData['connectionPort'])) {
            $connectionPort = Arr::groupByArrayOnlyRows($this->view->connectionData['connectionPort'], 'INSTALLATION_ID');
            $this->view->connectionPort = $connectionPort;
        }

        if (isset($this->view->connectionData['installation'])) {
            $this->view->installation = $this->view->connectionData['installation'];
        }

        $response = array(
            'Html' => $this->view->renderPrint('connection', self::$assetsPath),
            'Title' => $this->lang->line('META_00191'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response);
        exit;
    }

    public function setConnectionDataForm() {

        $this->view->assetId = Input::post('assetId');
        $this->view->dataRow = Input::post('dataRow');
        $this->view->taskId = (Input::postCheck('taskId') && Input::post('taskId')) ? Input::post('taskId') : '';

        $this->view->uniqId = Input::post('uniqId');
        $this->view->locationId = Input::post('locationId');
        $this->view->directorypath = Input::post('directorypath');
        $this->view->directorypathFull = '<span style="color: #4b8df8">' . $this->view->directorypath . '->' . $this->view->dataRow['PORT_TYPE_NAME'] . '#' . $this->view->dataRow['PORT_ORDER'] . '</span>';
        $this->view->srcRecordId = Input::post('srcRecordId');
        $this->view->checkkeyid = Input::post('checkkeyid');
        
        $this->view->connectionTypeList = $this->model->getConnectionTypeListModel();
        
        $this->view->connectionId = Input::post('connectionId');
        $this->view->installationId = Input::post('installationId');
        $this->view->isstart = Input::post('isstart');
        
        $this->view->installationUserId = Ue::sessionUserKeyId();
        $this->view->installationUserName = Ue::getSessionPersonName();
        $this->view->installationDate = Date::currentDate();
        
        $this->view->isEdit = Input::postCheck('isEdit') ? Input::post('isEdit') : 'false';
        
        $this->view->defaultSelectData = $this->model->getConnectionFormDataModel($this->view->assetId);
        $this->view->installation = $this->model->getRowInstallationDataModel($this->view->installationId);
        $this->view->conData = $this->model->getConnectionFormRowsDataModel($this->view->installationId, true);
        
        $response = array(
            'Html' => $this->view->renderPrint('dataForm', self::$assetsPath),
            'Title' => Lang::line('META_00191'),
            'save_btn' => Lang::line('save_btn'),
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response);
        exit;
    }

    public function getConnectionIdData() {
        $data = $this->model->getConnectionDataModel(Input::post('assetId'));
        echo json_encode($data['connection']);
    }

    public function getConnectionTypeData() {
        $data = $this->model->getConnectionDataByCheckKeyIdModel(Input::post('checkKeyId'));
        echo json_encode($data);
    }

    public function savePortconnection() {
        $response = $this->model->savePortconnectionModel();
        echo json_encode($response);
    }

    public function getPortListSelectData() {
        $response = $this->model->getConnectionDataByPortModel(Input::post('checkKeyId'), Input::post('portType'), Input::post('srcPort'), Input::post('assetPortId'));
        echo json_encode($response);
    }

    public function getPortListArray($id, $portType, $selfPort, $assetPortId = null) {
        $this->load->model('mdasset', 'middleware/models/');
        return $this->model->getConnectionDataByPortTypeModel($id, $portType, $selfPort, $assetPortId);
    }

    public function renderEquipmentInstallationMobi() {
        $this->view->selectedRow = array();
        $this->view->wfmStatusParams = '';
        $this->view->taskId = (Input::postCheck('taskId') ? Input::post('taskId') : '');
        $this->view->assetId = (Input::postCheck('assetId') ? Input::post('assetId') : '');

        $this->view->metaDataId = '1529649071315232'; //mobSiteEquipmentDropList
        $this->view->taskTabMetaDataId = '1533787099324'; //mobTaskByEquipmentList
        $this->view->pkiTabMetaDataId = '1533787143129'; //MOB_EQUIPMENT_CONFIG_DV_002

        $this->view->uniqId = getUID();

        $this->view->css = array_unique(array_merge(array('custom/css/vr-card-menu.css'), AssetNew::metaCss()));
        $this->view->fullUrlCss = array('middleware/assets/css/mobi/style.css');
        $this->view->js = array_unique(array_merge(array('custom/pages/scripts/appmenu.js'), AssetNew::metaOtherJs()));

        $param = array(
            'assetId' => $this->view->assetId,
            'id' => $this->view->assetId
        );
        includeLib('Utils/Functions');
        $selectedRow = Functions::runProcess('mobGetCheckKeyEquipmentList_004', $param);
        $this->view->selectedRow = Arr::encode(array('dataRow' => isset($selectedRow['result']) ? $selectedRow['result'] : array('assetid' => $this->view->assetId)));
        $this->view->wfmStatusParams = (Input::postCheck('wfmStatusParams') ? Input::post('wfmStatusParams') : '');

        $this->load->model('mdobject', 'middleware/models/');
        $this->view->mainMetaDataCode = $this->model->getStandartFieldModel($this->view->metaDataId, 'meta_value_code');
        $this->view->mainMetaDataName = $this->model->getStandartFieldModel($this->view->metaDataId, 'meta_value_name');

        $response = array(
            'Html' => $this->view->renderPrint('equipmentInstallation', self::$assetsPath),
            'Title' => 'Төхөөрөмж суурьлуулах',
            'mainId' => $this->view->uniqId,
            'save_btn' => Lang::line('save_btn'),
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response);
        exit;
    }

    public function renderEquipmentConnectionMobi() {

        $selectedRow = Input::post('selectedRow');

        $this->view->connectionId = $selectedRow['connectionid'];
        $this->view->locationId = $selectedRow['assetlocationid'];
        $this->view->nextLocationId = $selectedRow['nextlocationid'];

        $this->view->connectionData = $this->model->getConnectionInstallationDataModel($this->view->connectionId, $this->view->locationId, $this->view->nextLocationId);

        $this->view->taskId = Input::post('taskId', '');

        $this->view->dataRow = (isset($this->view->connectionData) && isset($this->view->connectionData['connection'][0])) ? $this->view->connectionData['connection'][0] : array();
        $this->view->assetId = (isset($this->view->dataRow['ASSET_ID'])) ? $this->view->dataRow['ASSET_ID'] : 0;
        $this->view->dataRow['PORT_ORDER'] = 1;

        $this->view->installationId = $this->view->connectionData['installationId'];
        $this->view->checkkeyid = $this->view->connectionData['checkKeyIdId'];
        $this->view->srcRecordId = $this->view->connectionData['checkKeyIdId'];
        $this->view->uniqId = getUID();

        $this->view->directorypath = $selectedRow['linename'];
        $this->view->directorypathFull = '<span style="color: #4b8df8"></span>';
        $this->view->connectionTypeList = $this->model->getConnectionTypeListModel();

        $this->view->installationUserId = Ue::sessionUserKeyId();
        $this->view->installationUserName = Ue::getSessionPersonName();
        $this->view->installationDate = Date::currentDate();

        //Default select value list 
        $this->view->defaultSelectData = $this->model->getConnectionFormDataModel($this->view->assetId);

        //Installation general data
        $this->view->installation = $this->model->getRowInstallationDataModel($this->view->installationId);

        //Connection data
        $this->view->conData = $this->model->getConnectionFormRowsDataModel($this->view->installationId, true);

        $response = array(
            'Html' => $this->view->renderPrint('dataForm', self::$assetsPath),
            'Title' => Lang::line('META_00191'),
            'mainId' => $this->view->uniqId,
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response);
        exit;
    }

    public function saveEquipmentInstallationStatus() {
        $this->view->wfmStatusParams = array();
        parse_str(Input::post('wfmStatusParams'), $this->view->wfmStatusParams);

        $this->load->model('mdobject', 'middleware/models/');

        $dataViewId = $this->view->wfmStatusParams['dataViewId'];
        $newWfmStatusid = $this->view->wfmStatusParams['statusId'];

        $_POST['newWfmStatusid'] = $newWfmStatusid;
        $_POST['metaDataId'] = $dataViewId;
        $_POST['dataRow'] = $_POST['selectedRow'];

        $ss = $this->model->setRowWfmStatusModel();
        echo json_encode($ss);
    }

    public function ministry_detail($id = null) {

        $this->view->fullUrlCss = array('middleware/assets/css/intranet/style.css');

        $this->view->title = 'Parliament';
        $this->view->uniqId = getUID();

        $this->load->model('government', 'middleware/models/');
        $this->view->did = Input::post('dataViewId');

        $this->view->selectedRow = $row = Input::post('selectedRow');
        $mainData = $this->model->IssueDetailModel($row['id'], $this->view->did);

        $this->view->mainData = ($mainData) ? $mainData : $this->view->selectedRow;
        $this->view->tagArr = (isset($this->view->mainData['tagname']) && $this->view->mainData['tagname']) ? explode(', ', $this->view->mainData['tagname']) : array();

        $this->view->attachFilesDv = $this->model->attachFilesDetailModel($row['id']);
        $this->view->result3 = $this->model->AuthorDetailModel($row['id']);
        $this->view->result4 = $this->model->CheckListDetailModel($row['id']);
        $this->view->result5 = $this->model->ParticipantsDetailModel($row['id']);
        $this->view->result6 = $this->model->ReviewDetailModel($row['id']);
        $this->view->result7 = $this->model->DecisionDetailModel($row['id']);
        $this->view->result8 = $this->model->legalFrameworkModel($row['id']);

        if (!is_ajax_request()) {
            $this->view->isAjax = false;

            $this->view->render('header');
            $this->view->render('detail_get_poll', self::$viewPath2);
            $this->view->render('footer');
        } else {
            $response = array('html' => $this->view->renderPrint('detail_get_poll', self::$viewPath2), 'uniqId' => $this->view->uniqId);
            echo json_encode($response);
            exit;
        }



//        $this->view->title = 'Parliament';
//        $this->view->uniqId = getUID();
//        
//        $this->load->model('government', 'middleware/models/');
//        $this->view->did = Input::post('dataViewId');
//
//        $this->view->selectedRow = $row = Input::post('selectedRow');
//        $mainData = $this->model->IssueDetailModel($row['id'], $this->view->did);
//
//        $this->view->mainData = ($mainData) ? $mainData : $this->view->selectedRow;
//        $this->view->tagArr = (isset($this->view->mainData['tagname']) && $this->view->mainData['tagname']) ? explode(', ', $this->view->mainData['tagname']) :  array();
//        
//        $this->view->attachFilesDv = $this->model->attachFilesDetailModel($row['id']);
//        $this->view->result3 = $this->model->AuthorDetailModel($row['id']);
//        $this->view->result4 = $this->model->CheckListDetailModel($row['id']);
//        $this->view->result5 = $this->model->ParticipantsDetailModel($row['id']);
//        $this->view->result6 = $this->model->ReviewDetailModel($row['id']);
//        $this->view->result7 = $this->model->DecisionDetailModel($row['id']);
//        $this->view->result8 = $this->model->legalFrameworkModel($row['id']);
//
//        if (!is_ajax_request()) {
//            $this->view->isAjax = false;
//            $this->view->render('header');
//        }
//
//        $response = array('html' => $this->view->renderPrint('detail_get_poll', self::$viewPath2), 'uniqId' => $this->view->uniqId);
//        echo json_encode($response);
//        exit;
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

    public function saveSubjectReflection() {
        $postData = Input::postData();
        $subjectReviewDtl = array();
        foreach ($postData['reflection'] as $key => $reflection) {
            $tempparam = array(
                'id' => isset($postData['id'][$key]) ? $postData['id'][$key] : '',
                'reflection' => $reflection,
                'subjectId' => $postData['subjectId']
            );
            array_push($subjectReviewDtl, $tempparam);
        }

        $temp = array(
            'id' => $postData['subjectId'],
            'subjectReviewDtl' => $subjectReviewDtl
        );

    //   var_dump($postData); die;

        includeLib('Utils/Functions');
        $result = Functions::runProcess('CMS_REVIEW_REFLECT_DV_002', $temp);

        if ($result['status'] === 'success') {
            $result['text'] = Lang::line('msg_save_success');
        }

        echo json_encode($result);
    }

    public function sendSubject() {
        
        vaR_dump(Input::postData()); die;
        
        
        $id = Input::post('sendId');
        $subjectId = Input::post('sendSubjectId');
        
        $param['id'] = $id;
        $param['subjectId'] = $subjectId;
        $param['wfmStatusId'] = 2000;
        $param['wfmDescription'] = 'Санал илгээв';
        $param['isSend'] = 1;

        includeLib('Utils/Functions');
        $result = Functions::runProcess('CMS_SANAL_ILGEEH_DV_002', $param);

        if ($result['status'] === 'success') {
            $result['text'] = Lang::line('msg_save_success');
        }
        echo json_encode($result);
    }

    public function deleteConnectionPort() {
        $this->db->AutoExecute('FA_CONNECTION', array('IS_ACTIVE' => '0'), 'UPDATE', "INSTALLATION_ID = '". Input::post('installationId') ."'");
        $this->db->AutoExecute('FA_INSTALLATION', array('IS_ACTIVE' => '0'), 'UPDATE', "INSTALLATION_ID = '". Input::post('installationId') ."'");
        
        echo json_encode(array('status' => 'success', 'text' => Lang::line('msg_save_success')));
    }

    public function sales_dashboard($theme='') {
     
        
        $this->view->title = 'Борлуулалтын - Хянах самбар';
        $this->view->theme = $theme;

        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        $data = array();

        $this->view->isAjax = is_ajax_request();
        $this->view->p6 = $this->model->getDataMartDvRowsModel('1587703620909798');
        //var_dump($this->view->p6);die;
        if (!$this->view->isAjax) {
            $this->view->render('header');
        }
        
        $this->view->render('/sales/dashboard_index', self::viewPath);
        
        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }
    }

    public function sales_dashboardtheme($theme = ''){

        //   $this->load->model('mddatamodel', 'middleware/models/');
        includeLib('Utils/Functions');
   
        $this->load->model('mddatamodel', 'middleware/models/');

        $id = Input::post('id');
        $path = Input::post('path');
        $this->view->uniqId = getUID();
      
        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        
        $this->view->isAjax = is_ajax_request();
        $this->view->ajax = false;

        // dashboard 

        $this->view->topCards = $this->model->getDataMartDvRowsModel('1587698683538202');
        $this->view->gender = $this->model->getDataMartDvRowsModel('1587713737432436');
        $this->view->ageGenderDistrubition = $this->model->getDataMartDvRowsModel('1584519978307'); 
        $this->view->nationality = $this->model->getDataMartDvRowsModel('1584519974533');
        $this->view->infectionSources = $this->model->getDataMartDvRowsModel('1584519977676');
        $this->view->district = $this->model->getDataMartDvRowsModel('1592269229423244');
        $this->view->map = $this->model->getDataMartDvRowsModel('1585644710962227');
        $this->view->cases = $this->model->getDataMartDvRowsModel('1584519974694');
        $this->view->capacity = $this->model->getDataMartDvRowsModel("1592269928393353");
        $this->view->clusters = $this->model->getDataMartDvRowsModel('1584519976997');
        $this->view->locationCapacity = $this->model->getDataMartDvRowsModel('1587703620909798');
        $this->view->trans = $this->model->getDataMartDvRowsModel('1591862882772');
        $this->view->trasGroup = Arr::groupByArrayOnlyRows($this->view->trans, 'groupname');

        //theme1 data
        if($theme == 'theme1'){
            $this->view->p1 = $this->model->getDataMartDvRowsModel('1587698683538202');
            $this->view->p2 = $this->model->getDataMartDvRowsModel('1594168739348763');
            $this->view->p3 = $this->model->getDataMartDvRowsModel('1593678275946');
            $this->view->p6 = $this->model->getDataMartDvRowsModel('1587713737432436');
            $this->view->p7 = $this->model->getDataMartDvRowsModel('1594092393421320');
            $this->view->p8 = $this->model->getDataMartDvRowsModel('1587703620909798');
            $this->view->p11 = $this->model->getDataMartDvRowsModel('1594092393421320');
    
            $this->view->chartName = array_column($this->view->p2, 'datevalue');
            $this->view->chartName2 = array_column($this->view->p3, 'datevalue');
            $this->view->chartval1 = array_column($this->view->p2, 'expense');
            $this->view->chartval2 = array_column($this->view->p2, 'income');
            $this->view->chartval21 = array_column($this->view->p3, 'expense');
            $this->view->chartval22 = array_column($this->view->p3, 'income');
        }else if($theme == 'theme2'){
            $this->view->data1 = $this->model->getDataMartDvRowsModel('');
            $this->view->data2 = $this->model->getDataMartDvRowsModel('');
            $this->view->data3 = $this->model->getDataMartDvRowsModel('');
            $this->view->data4 = $this->model->getDataMartDvRowsModel('');
            $this->view->data5 = $this->model->getDataMartDvRowsModel('');
            $this->view->data6 = $this->model->getDataMartDvRowsModel('');
            $this->view->data7= $this->model->getDataMartDvRowsModel('1593680065214');
            $this->view->data8 = $this->model->getDataMartDvRowsModel('');
            $this->view->data9 = $this->model->getDataMartDvRowsModel('');
            $this->view->dataGroup7 = Arr::groupByArrayOnlyRows($this->view->data7, 'datevalue');
        }else if($theme == 'theme3'){
            $this->view->p1 = $this->model->getDataMartDvRowsModel('1587698683538202');
            $this->view->p2 = $this->model->getDataMartDvRowsModel('1594168739348763');
            $this->view->p3 = $this->model->getDataMartDvRowsModel('1593678275946');
            $this->view->p6 = $this->model->getDataMartDvRowsModel('1587713737432436');
            $this->view->p7 = $this->model->getDataMartDvRowsModel('1594092393421320');
            $this->view->p8 = $this->model->getDataMartDvRowsModel('1587703620909798');
            $this->view->p11 = $this->model->getDataMartDvRowsModel('1594092393421320');
    
            $this->view->chartName = array_column($this->view->p2, 'datevalue');
            $this->view->chartName2 = array_column($this->view->p3, 'datevalue');
            $this->view->chartval1 = array_column($this->view->p2, 'expense');
            $this->view->chartval2 = array_column($this->view->p2, 'income');
            $this->view->chartval21 = array_column($this->view->p3, 'expense');
            $this->view->chartval22 = array_column($this->view->p3, 'income');
        }
       
        //theme2 data
   
        if ($theme) {
            $response = array(
                'Html' => $this->view->renderPrint('sales/layout/'.$theme.'', self::viewPath),
                'uniqId' => $this->view->uniqId,
                'theme' => $theme,
                'card' =>  $this->view->p1,
            );
        } else {
            $response = array(
                'Html' => $this->view->renderPrint('sales/dashboard', self::viewPath),
                'uniqId' => $this->view->uniqId,
                'theme' => $theme
            );
        }
        echo json_encode($response);
    }
    
    public function covid_dashboard() {
        
        $this->load->model('mddatamodel', 'middleware/models/');
        
        $this->view->title = 'COVID-19 - Хянах самбар';
        
        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        $this->view->fullUrlCss = array('middleware/assets/css/covid19/main.css');
        
        $this->view->isAjax = is_ajax_request();
        
        $this->view->topCards = $this->model->getDataMartDvRowsModel('1585229344460602');
        $this->view->gender = $this->model->getDataMartDvRowsModel('1585234546818079');
        $this->view->ageGenderDistrubition = $this->model->getDataMartDvRowsModel('1584519978307');
        $this->view->nationality = $this->model->getDataMartDvRowsModel('1584519974533');
        $this->view->infectionSources = $this->model->getDataMartDvRowsModel('1584519977676');
        $this->view->district = $this->model->getDataMartDvRowsModel('1584519974361');
        $this->view->cases = $this->model->getDataMartDvRowsModel('1584519974694');
        $this->view->clusters = $this->model->getDataMartDvRowsModel('1584519976997');
        $this->view->locationCapacit = $this->model->getDataMartDvRowsModel('1585249620841337');
        $this->view->locationCapacity = $this->model->getDataMartDvRowsModel('1585216510013');
        
        if (!$this->view->isAjax) {
            $this->view->render('header');
        }
        
        $this->view->render('dashboard', self::$viewPath4);
        
        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }
    }
    
    public function covid_dashboard2() {
        
        $this->load->model('mddatamodel', 'middleware/models/');
        
        $this->view->title = 'COVID-19 - Хянах самбар';
        
        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        $this->view->fullUrlCss = array('middleware/assets/css/covid19/main2.css');
        
        $this->view->isAjax = is_ajax_request();
        
        $this->view->topCards = $this->model->getDataMartDvRowsModel('1585229344460602');
        $this->view->leftCards = $this->model->getDataMartDvRowsModel('1585229344460602');
        $this->view->gender = $this->model->getDataMartDvRowsModel('1585234546818079');
        $this->view->ageGenderDistrubition = $this->model->getDataMartDvRowsModel('1584519978307');
        $this->view->nationality = $this->model->getDataMartDvRowsModel('1584519974533');
        $this->view->infectionSources = $this->model->getDataMartDvRowsModel('1584519977676');
        $this->view->district = $this->model->getDataMartDvRowsModel('1584519974361');
        $this->view->cases = $this->model->getDataMartDvRowsModel('1584519974694');
        $this->view->clusters = $this->model->getDataMartDvRowsModel('1584519976997');
        $this->view->locationCapacity = $this->model->getDataMartDvRowsModel('1585249620841337');
        $this->view->weeklyAndDay = $this->model->getDataMartDvRowsModel('1585216510013');
        $this->view->map = $this->model->getDataMartDvRowsModel('1585644710962227');
        
        
        if (!$this->view->isAjax) {
            $this->view->render('header');
        }
        
        $this->view->render('dashboard2', self::$viewPath4);
        
        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }
    }
    
    public function resource() {
        
        $this->load->model('mddatamodel', 'middleware/models/');
        
        $this->view->title = 'COVID-19 - Нөөцийн самбар';
        
        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        $this->view->fullUrlJs = AssetNew::amChartJs();
        $this->view->fullUrlCss = array('middleware/assets/css/covid19/main2.css');
        
        $this->view->isAjax = is_ajax_request();
        
        $this->view->topCards = $this->model->getDataMartDvRowsModel('1588314169196254');
        $this->view->lsHospitalTech = $this->model->getDataMartDvRowsModel('1587615239862');
        $this->view->lsHospital = $this->model->getDataMartDvRowsModel('1587615239743');
        $this->view->hospital = $this->model->getDataMartDvRowsModel('1588398514265647');

        $this->view->trans = $this->model->getDataMartDvRowsModel('1587615050005');

        $this->view->mbchart1 = $this->model->getDataMartDvRowsModel('1587615574799');
        $this->view->mbchart2 = $this->model->getDataMartDvRowsModel('1587615575240');
        $this->view->mbchart3 = $this->model->getDataMartDvRowsModel('1587615575623');

        $this->view->leftCards = $this->model->getDataMartDvRowsModel('1588314169196254');
        $this->view->gender = $this->model->getDataMartDvRowsModel('1588328755018908');
        $this->view->emplCapacity = $this->model->getDataMartDvRowsModel('1587615049886');
        $this->view->allCapacity = $this->model->getDataMartDvRowsModel('1587615059411');
        $this->view->ageGenderDistrubition = $this->model->getDataMartDvRowsModel('1584519978307');
        $this->view->nationality = $this->model->getDataMartDvRowsModel('1587615049854');
        $this->view->infectionSources = $this->model->getDataMartDvRowsModel('1584519977676');
        $this->view->district = $this->model->getDataMartDvRowsModel('1584519974361');
        $this->view->cases = $this->model->getDataMartDvRowsModel('1584519974694');
        $this->view->clusters = $this->model->getDataMartDvRowsModel('1584519976997');
        $this->view->locationCapacity = $this->model->getDataMartDvRowsModel('1587615049785');
        $this->view->weeklyAndDay = $this->model->getDataMartDvRowsModel('1585216510013');
        $this->view->map = $this->model->getDataMartDvRowsModel('1585644710962227');
        $this->view->trasGroup = Arr::groupByArrayOnlyRows($this->view->trans, 'groupname');
        // pa( $this->view->mbchart3);
        if (!$this->view->isAjax) {
            $this->view->render('header');
        }
        
        $this->view->render('resource', self::$viewPath4);
        
        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }
    }
 
    public function selectdistrict() {
 
        $this->view->uniqId = getUID();
        $id = Input::post('id');

        includeLib('Utils/Functions');
        $result = Functions::runProcess('dataListByMap_004', array ('filtercity' => $id));
      
        //pa($result);
        echo json_encode(
            array(
            'uniqId' => $this->view->uniqId, 
            'data' => $result['result'],
    
        ));
    }
    
    public function selecthospital() {
 
        $this->view->uniqId = getUID();
        $id = Input::post('id');

        includeLib('Utils/Functions');
        $result = Functions::runProcess('getHdr_004', array ('filterhospital' => $id));
        $data1 =  Functions::runProcess('getNiislelHdr_004.1', array('filterhospital' => $id));
        $data2 =  Functions::runProcess('getNiislelHdr_004.2', array('filterhospital' => $id));
        $data3 =  Functions::runProcess('getNiislelHdr_004.3', array('filterhospital' => $id));
        //pa($data3['result']);
        echo json_encode(
            array(
            'uniqId' => $this->view->uniqId, 
            'data' => $result['result'],
            'adata' => $data1['result'],
            'bdata' => $data2['result'],
            'cdata' => $data3['result'],
        ));
    }
    
    public function covid_dashboard5() {
        $this->load->model('mddatamodel', 'middleware/models/');
        
        $this->view->title = 'COVID-19 - Хянах самбар';
        
        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        
        $this->view->isAjax = is_ajax_request();
        
        $this->view->topCards = $this->model->getDataMartDvRowsModel('1600657774750');  //1586197022973	
        $this->view->leftCards = $this->model->getDataMartDvRowsModel('1600657774750'); //1586197022973
        $this->view->gender = $this->model->getDataMartDvRowsModel('1585234546818079');
        $this->view->ageGenderDistrubition = $this->model->getDataMartDvRowsModel('1600657774695'); //1586197022883	
        $this->view->nationality = $this->model->getDataMartDvRowsModel('1586197023033');
        $this->view->infectionSources = $this->model->getDataMartDvRowsModel('1600657774797'); //1586197023010	
        $this->view->district = $this->model->getDataMartDvRowsModel('1584519974361');
        $this->view->cases = $this->model->getDataMartDvRowsModel('1584519974694');
        $this->view->clusters = $this->model->getDataMartDvRowsModel('1584519976997');
        $this->view->locationCapacity = $this->model->getDataMartDvRowsModel('1600657773527'); //1586197022806	
        $this->view->weeklyAndDay = $this->model->getDataMartDvRowsModel('1600657774869'); //1586197023063	
        $this->view->map = $this->model->getDataMartDvRowsModel('1600657773594');  //1586197022856	
        $this->view->datalist = $this->model->getDataMartDvRowsModel('1589235813056');
        // pa( $this->view->topCards);
        if (!$this->view->isAjax) {
            $this->view->render('header');
        }
        
        $this->view->render('dashboard5', self::$viewPath4);
        
        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }
    }

    public function covid_dashboard3() {
        
        $this->load->model('mddatamodel', 'middleware/models/');
        
        $this->view->title = 'COVID-19 - Хянах самбар';
        
        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        
        $this->view->isAjax = is_ajax_request();
        
        $this->view->topCards = $this->model->getDataMartDvRowsModel('1586197022973');
        $this->view->leftCards = $this->model->getDataMartDvRowsModel('1586197022973');
        $this->view->gender = $this->model->getDataMartDvRowsModel('1585234546818079');
        $this->view->ageGenderDistrubition = $this->model->getDataMartDvRowsModel('1586197022883');
        $this->view->nationality = $this->model->getDataMartDvRowsModel('1586197023033');
        $this->view->infectionSources = $this->model->getDataMartDvRowsModel('1586197023010');
        $this->view->district = $this->model->getDataMartDvRowsModel('1584519974361');
        $this->view->cases = $this->model->getDataMartDvRowsModel('1584519974694');
        $this->view->clusters = $this->model->getDataMartDvRowsModel('1584519976997');
        $this->view->locationCapacity = $this->model->getDataMartDvRowsModel('1586197022806');
        $this->view->weeklyAndDay = $this->model->getDataMartDvRowsModel('1586197023063');
        $this->view->map = $this->model->getDataMartDvRowsModel('1586197022856');
        $this->view->datalist = $this->model->getDataMartDvRowsModel('1589235813056');
        //pa( $this->view->locationCapacity);
        if (!$this->view->isAjax) {
            $this->view->render('header');
        }
        
        $this->view->render('dashboard3', self::$viewPath4);
        
        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }
    }
    
    
    public function covid_dashboard4() {
        
        $this->load->model('mddatamodel', 'middleware/models/');
        
        $this->view->title = 'Хянах самбар';
        
        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        
        $this->view->isAjax = is_ajax_request();
        
        $this->view->topCards = $this->model->getDataMartDvRowsModel('1589228434596');
        $this->view->leftCards = $this->model->getDataMartDvRowsModel('1586197022973');
        $this->view->gender = $this->model->getDataMartDvRowsModel('1585234546818079');
        $this->view->ageGenderDistrubition = $this->model->getDataMartDvRowsModel('1589228434556');
        $this->view->nationality = $this->model->getDataMartDvRowsModel('1589228434528');
        $this->view->infectionSources = $this->model->getDataMartDvRowsModel('1589228434633');
        $this->view->district = $this->model->getDataMartDvRowsModel('1584519974361');
        $this->view->cases = $this->model->getDataMartDvRowsModel('1584519974694');
        $this->view->clusters = $this->model->getDataMartDvRowsModel('1584519976997');
        $this->view->locationCapacity = $this->model->getDataMartDvRowsModel('1589228434467');
        $this->view->weeklyAndDay = $this->model->getDataMartDvRowsModel('1589228434660');
        $this->view->map = $this->model->getDataMartDvRowsModel('1589228434497');
        //pa( $this->view->locationCapacity);
        if (!$this->view->isAjax) {
            $this->view->render('header');
        }
        
        $this->view->render('dashboard4', self::$viewPath4);
        
        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }
    }
    
    public function covid_world() {

        $this->view->title = 'COVID-19 - World Data';
        $this->view->isAjax = is_ajax_request();
        $this->view->fullUrlCss = array('middleware/assets/css/covid19/main2.css');
        if (!$this->view->isAjax) {
            $this->view->render('header');
        }
        $this->view->render('worlddata', self::$viewPath4);
        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }
    }
    
    public function covid_world_google() {

        $this->view->title = 'COVID-19 - World Data';
        $this->view->isAjax = is_ajax_request();
        $this->view->fullUrlCss = array('middleware/assets/css/covid19/main2.css');
        if (!$this->view->isAjax) {
            $this->view->render('header');
        }
        $this->view->render('worlddataGoogle', self::$viewPath4);
        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }
    }
    
    public function cloud_profile() {
        $this->view->title = 'VeriCloud - Хэрэглэгчийн хэсэг';
        $this->view->render('header');
        $this->view->render('cloud_profile', self::$viewPath4);
        $this->view->render('footer');
    }

    public function cloud_dashboard() {
        $this->view->title = 'VeriCloud - Хянах самбар';
        $this->view->fullUrlCss = array('middleware/assets/css/covid19/main.css');
        $this->view->render('header');
        $this->view->render('cloud_dashboard', self::$viewPath4);
        $this->view->render('footer');
    }

    public function pivot_matrix() {
        $this->view->title = 'Pivot Matrix';
        $this->view->render('header');
        $this->view->render('pivot_matrix', self::$viewPath4);
        $this->view->render('footer');
    }

    public function dvexplorer() {
        $this->view->title = 'DvExplorer';
        $this->view->fullUrlCss = array('middleware/assets/css/covid19/main.css');
        $this->view->render('header');
        $this->view->render('dvexplorer', self::$viewPath4);
        $this->view->render('footer');
    }

    public function process_layout() {
        $this->view->title = 'Process Layout';
        $this->view->render('header');
        $this->view->render('process_layout', self::$viewPath4);
        $this->view->render('footer');
    }

    public function lifecycle() {
        $this->view->title = 'LifeCycle';
        $this->view->render('header');
        $this->view->render('lifecycle', self::$viewPath4);
        $this->view->render('footer');
    }
    
    public function sales_calendar() {
        $this->load->model('government', 'models/');
        $this->view->title = 'Борлуулалтын календар';
        $this->view->uniqId = getUID();
        $this->view->metaDataId = '1587438778356';
        $this->view->data = array(); //$this->model->fncRunDataview($this->view->metaDataId);
        $this->view->searchRoom = $this->model->fncRunDataview("1587118711156074");
        $this->view->wfmStatusList = $this->model->fncRunDataview("1580193305168844");
        
     
        $this->view->css = array_unique(array_merge(array('custom/css/vr-card-menu.css'), AssetNew::metaCss()));
        $this->view->fullUrlCss = array('middleware/assets/css/intranet/style.css');
        $this->view->js = array_unique(array_merge(array(
            'custom/addon/admin/pages/scripts/app.js',
            'custom/gov/multiselect.js'
            ), AssetNew::metaOtherJs()));
        
        $this->view->render('header');
        $this->view->render('/sales/index', self::viewPath);
        $this->view->render('footer');
        
    }
  
    public function emp_calendar() {
        $this->load->model('government', 'models/');
        $this->view->title = 'Борлуулалтын календар';
        $this->view->uniqId = getUID();
        $this->view->metaDataId = '1590661185498';
        $this->view->data = array(); //$this->model->fncRunDataview($this->view->metaDataId);
        $this->view->searchRoom = $this->model->fncRunDataview("1590661186061");
        $this->view->wfmStatusList = $this->model->fncRunDataview("1580193305168844");
        
     
        $this->view->css = array_unique(array_merge(array('custom/css/vr-card-menu.css'), AssetNew::metaCss()));
        $this->view->fullUrlCss = array('middleware/assets/css/intranet/style.css');
        $this->view->js = array_unique(array_merge(array(
            'custom/addon/admin/pages/scripts/app.js',
            ), AssetNew::metaOtherJs()));
        $this->view->isAjax = is_ajax_request();
        
        if (!$this->view->isAjax) {
            $this->view->render('header');
        }
        
        $this->view->render('/sales/emp_calendar', self::viewPath);
        
        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }        
        
    }
    
    public function getSalesDetail() {
        includeLib('Utils/Functions');
        $this->view->uniqId = getUID();
        $id = Input::post('id');
        $result = Functions::runProcess('fitSalesCalendar_DV_004', array('id' => $id));
        $data = Functions::runProcess('fitSalesCalendar_DV_004', array('id' => $id));

        echo json_encode(array(
            'uniqId' => $this->view->uniqId, 
            'close_btn' => Lang::line('close_btn'), 
            'data' => $result['result'],
            'cdata' => $data['result']));
    }
    
    public function memberShipDashboard() {
        
        $this->load->model('mddatamodel', 'middleware/models/');
        includeLib('Utils/Functions');
        $this->view->title = 'Гишүүдийн судалгаа';
        
        $this->view->css = AssetNew::metaCss();
        $this->view->js = array_merge(AssetNew::metaOtherJs(), AssetNew::highchartJs());
       
        $this->view->fullUrlCss = array('middleware/assets/css/covid19/main2.css');
        $this->view->isAjax = is_ajax_request();
        
        // $this->view->topCards = Functions::runProcess('dashboard3_chart1', array ('id' => '1'));
        $this->view->topCards = $this->model->getDataMartDvRowsModel('1589699818574223');
        $this->view->leftCards = $this->model->getDataMartDvRowsModel('1589509171898');
        $this->view->gender = $this->model->getDataMartDvRowsModel('1589509171887');
        $this->view->ageGenderDistrubition = $this->model->getDataMartDvRowsModel('1589509171875');
        $this->view->nationality = $this->model->getDataMartDvRowsModel('1589509171793');
        $this->view->infectionSources = $this->model->getDataMartDvRowsModel('1589509171771');
        $this->view->district = $this->model->getDataMartDvRowsModel('1589509171760');
        $this->view->cases = $this->model->getDataMartDvRowsModel('1589509171750');
        $this->view->clusters = $this->model->getDataMartDvRowsModel('1589509171739');
        $this->view->locationCapacity = $this->model->getDataMartDvRowsModel('1589509171729');
        $this->view->weeklyAndDay = $this->model->getDataMartDvRowsModel('1589771892792580');
        $this->view->map = $this->model->getDataMartDvRowsModel('1589509171717');
        
        if (!$this->view->isAjax) {
            $this->view->render('header');
        }
        
        $this->view->render('custom/membership', self::viewPath);
        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }
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

        if( $theme == 'main1' ){
           $filterDv = '1592796648860';
        }elseif( $theme == 'main2'){
           $filterDv = '1592796649046';
        }elseif( $theme == 'recruitment1'){
           $filterDv = '1592796649101';
        }elseif( $theme == 'recruitment2'){
           $filterDv = '1592796649150';
        }elseif( $theme == 'request'){
           $filterDv = '1592796649254';
        }elseif( $theme == 'relation1'){
            $filterDv = '1592796649394';
        }elseif( $theme == 'relation2'){
            $filterDv = '1592796649447';
        }elseif( $theme == 'ceo'){
           $filterDv = '1592796649304';
        }elseif( $theme == 'vp'){
           $filterDv = '1592796649354';
        }elseif( $theme == 'performance'){
           $filterDv = '1605706112953';
        }elseif( $theme == 'performance2'){
           $filterDv = '1605706113579';
        } else{
            $filterDv = '1592183050793168';
        }
        
        $this->view->metaDataId = $filterDv;

        $this->load->model('mdobject', 'middleware/models/');

        $this->view->dataViewMandatoryHeaderData = '';
        $this->view->dataViewHeaderRealData = $this->model->dataViewHeaderDataModel($this->view->metaDataId);
        $this->view->row = $this->model->getDataViewConfigRowModel($this->view->metaDataId);
        $this->view->dataViewCriteriaType = strtolower($this->view->row['SEARCH_TYPE'] == '0' ? 'BUTTON' : Info::getSearchType($this->view->row['SEARCH_TYPE']));
        $this->view->dataViewHeaderData = Mdobject::findCriteria($this->view->metaDataId, $this->view->dataViewHeaderRealData);
        $this->view->defaultCriteria = $this->view->renderPrint('search/defaultCriteria', 'middleware/views/metadata/dataview/');
      
     
        if (!$this->view->isAjax) {
            $this->view->render('header');
        }
        $this->view->render('custom/summarydashboard', self::viewPath);
        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }

    }

    public function hrSummaryDashboardData($theme = ''){

     
        includeLib('Utils/Functions');
        $this->view->uniqId = getUID();
    
        $this->view->isAjax = is_ajax_request();
        $this->view->ajax = false;
        // $criteria = array();
        $criteria = array('isDefault' => array(
            array(
                'operator' => '=',
                'operand' =>  '1'
            )            
        ));
        $criteriaData = Input::post('formtData');
        
        if ($criteriaData) {
            foreach ($criteriaData as $crikey => $crival) {
                if (strpos($crival, '#,#') !== false) {
                    $criteria[$crikey][] = array(
                        'operator' => 'IN',
                        'operand' =>  str_replace('#,#', ',', $crival)
                    );
                } else {
                    $criteria[$crikey][] = array(
                        'operator' => '=',
                        'operand' =>  $crival
                    );
                }
            }
        } 
//        else {
//            $criteria['filterStartDate'] = array(
//                array(
//                    'operator' => '=',
//                    'operand' =>  Ue::sessionFiscalPeriodStartDate()
//                )                     
//            );
//            $criteria['filterEndDate'] = array(
//                array(
//                    'operator' => '=',
//                    'operand' =>  Ue::sessionFiscalPeriodEndDate()
//                )                     
//            );
//        }
        // default 

        $this->view->GroupbyList = $this->model->getDataMartDvRowsModel('1591862882772',$criteria);    
        $this->view->news = $this->model->getDataMartDvRowsModel('1591863387783');   
      
        // 8.news list
        $this->view->trasGroup = Arr::groupByArrayOnlyRows($this->view->GroupbyList, 'groupname');
        $this->view->main1_1 = Arr::naturalsort($this->model->getDataMartDvRowsModel('1592453115875'), 'category');
        
        $this->view->layoutPositionArr = $this->model->dashboardLayoutDataModel($theme, $criteria, '0', '1');

        if($theme == 'relation2'){
            $pchart8 = $this->view->layoutPositionArr['relation2_8'];
            $pchart9 = $this->view->layoutPositionArr['relation2_9'];

            $this->view->chartName8 = array_column($pchart8, 'name');
            $this->view->chartName9 = array_column($pchart9, 'name');

            $this->view->chartval8 = array_column($pchart8, 'val1');
            $this->view->chartval81 = array_column($pchart8, 'val2');
            $this->view->chartval91 = array_column($pchart9, 'val1');
            $this->view->chartval92 = array_column($pchart9, 'val2');
        }
        
        if ($theme) {
            $response = array(
                'Html' => $this->view->renderPrint('custom/dashboard/'.$theme.'', self::viewPath),
                'uniqId' => $this->view->uniqId,
                'criteria' => $criteria
            );
        } else {
            
            $response = array(
                'Html' => $this->view->renderPrint('custom/summarydata', self::viewPath),
                'uniqId' => $this->view->uniqId,
                'theme' => $theme
            );
        }
        echo json_encode($response);
    }
    
    public function getAllSumDuuregLicense() {
        $criteria = array();
        $criteria['cityid'][] = array(
                        'operator' => '=',
                        'operand' =>  Input::post('aimagId')
                    );
        $this->view->map = $this->model->getDataMartDvRowsModel('1597718350176545', $criteria);
        
        if (issetParam($this->view->map[0]['districtid'])) {
            $this->view->map = Arr::groupByArrayOnlyRow($this->view->map, 'districtid', false);
        }
        
        echo json_encode($this->view->map);
    }
    
    public function getLicenseCountAndAreaByLocation() {
        echo json_encode(array(array('sum_duureg_id' => '6713', 'NAME' => 'Бүрэнтогтох', 'CHART_CODE' => 'MN-HG-BU', 'total' => '8')));
    }

    public function coviddashboard() {
        
        $currentDate = Date::currentDate('y_m_d');
        $cache = phpFastCache();
        
        $this->load->model('mddatamodel', 'middleware/models/');
        includeLib('Utils/Functions');
        $this->view->title = 'Удирдлагын дашбоард';
        
        $this->view->type = '0';
        $this->view->css = AssetNew::metaCss();
        $this->view->js = array_merge(AssetNew::metaOtherJs(), AssetNew::highchartJs());
       
        $this->view->fullUrlCss = array('middleware/assets/css/covid19/main2.css');
        $this->view->isAjax = is_ajax_request();
        $this->view->uniqId = getUID();
        $this->view->criteria = array();
        
        $this->view->criteria['filterYear'] = array(array(
                    'operator' => '=',
                    'operand' =>  '2020'
                ));
        $this->view->criteria['filterMonth'] = array(array(
                    'operator' => '=',
                    'operand' =>  '9'
                ));
        
        $map = $cache->get('bpmap_gl_' . $currentDate);
        if ($map == null) {
            $this->view->map = $this->model->getDataMartDvRowsModel('1599478648184'); //1599542765853
            if ($this->view->map) {
                $cache->set('bpmap_gl_' . $currentDate, $this->view->map, '144000000');
            }
        } else {
            $this->view->map = $map;
        }
        
        $frontier = $cache->get('bpfrontier_gl_' . $currentDate);
        if ($frontier == null) {
            $this->view->frontier = $this->model->getDataMartDvRowsModel('1600505224643713'); //1599542765853
            if ($this->view->frontier) {
                $cache->set('bpfrontier_gl_' . $currentDate, $this->view->frontier, '144000000');
            }
        } else {
            $this->view->frontier = $frontier;
        }
        
        $tdData = $cache->get('bpfrontier_gl_' . $currentDate);
        if ($tdData == null) {
            $this->view->frontier = $this->model->getDataMartDvRowsModel('1599546032044'); //1599542765853
            if ($this->view->tdData) {
                $cache->set('bptdData_gl_' . $currentDate, $this->view->tdData, '144000000');
            }
        } else {
            $this->view->tdData = $tdData;
        }
        
        $khalamj = $cache->get('bpkhalamj_gl_' . $currentDate);
        if ($khalamj == null) {
            $this->view->khalamj = $this->model->getDataMartDvRowsModel('1599651188131873');
            if ($this->view->khalamj) {
                $cache->set('bpkhalamj_gl_' . $currentDate, $this->view->khalamj, '144000000');
            }
        } else {
            $this->view->khalamj = $khalamj;
        }
        
        $negdsen = $cache->get('bpnegdsen_gl_' . $currentDate);
        if ($negdsen == null) {
            $this->view->negdsen = $this->model->getDataMartDvRowsModel('1599301531440492');
            if ($this->view->negdsen) {
                $cache->set('bpnegdsen_gl_' . $currentDate, $this->view->negdsen, '144000000');
            }
        } else {
            $this->view->negdsen = $negdsen;
        }
        
        $a119Caller = $cache->get('bpa119Caller_gl_' . $currentDate);
        if ($a119Caller == null) {
            $this->view->a119Caller = $this->model->getDataMartDvRowsModel('1599378870179767', $this->view->criteria);
            if ($this->view->a119Caller) {
                $cache->set('bpa119Caller_gl_' . $currentDate, $this->view->a119Caller, '144000000');
            }
        } else {
            $this->view->a119Caller = $a119Caller;
        }
        
        $a11Urgent = $cache->get('bpa11Urgent_gl_' . $currentDate);
        if ($a11Urgent == null) {
            $this->view->a11Urgent = $this->model->getDataMartDvRowsModel('1599543579821', $this->view->criteria);
            if ($this->view->a11Urgent) {
                $cache->set('bpa11Urgent_gl_' . $currentDate, $this->view->a11Urgent, '144000000');
            }
        } else {
            $this->view->a11Urgent = $a11Urgent;
        }
        
        $a11Treatment = $cache->get('bpa11Treatment_gl_' . $currentDate);
        if ($a11Treatment == null) {
            $this->view->a11Treatment = $this->model->getDataMartDvRowsModel('1599543579041', $this->view->criteria);
            if ($this->view->a11Treatment) {
                $cache->set('bpa11Treatment_gl_' . $currentDate, $this->view->a11Treatment, '144000000');
            }
        } else {
            $this->view->a11Treatment = $a11Treatment;
        }
        
        $huleenawah = $cache->get('bphuleenawah_gl_' . $currentDate);
        if ($huleenawah == null) {
            $this->view->huleenawah = $this->model->getDataMartDvRowsModel('1599474553144712', $this->view->criteria);
            if ($this->view->huleenawah) {
                $cache->set('bphuleenawah_gl_' . $currentDate, $this->view->huleenawah, '144000000');
            }
        } else {
            $this->view->huleenawah = $huleenawah;
        }
        
        $bpgrippe_gl_ = $cache->get('bpgrippe_gl_' . $currentDate);
        if ($bpgrippe_gl_ == null) {
            $this->view->grippe = $this->model->getDataMartDvRowsModel('1599478643008', $this->view->criteria);
            if ($this->view->grippe) {
                $cache->set('bpgrippe_gl_' . $currentDate, $this->view->grippe, '144000000');
            }
        } else {
            $this->view->grippe = $bpgrippe_gl_;
        }
        
        $covidDataFromMn = $cache->get('bpcovidDataFromMn_gl_' . $currentDate);
        if ($covidDataFromMn == null) {
            $covidDataFromMn = $this->model->getDataMartDvRowsModel('1599476439466255', $this->view->criteria);
            if ($covidDataFromMn) {
                $cache->set('bpcovidDataFromMn_gl_' . $currentDate, $covidDataFromMn, '144000000');
            }
        }
        
        $stockData = $cache->get('bpstockData_gl_' . $currentDate);
        if ($stockData == null) {
            $stockData = $this->model->getDataMartDvRowsModel('1599545157049');
            if ($stockData) {
                $cache->set('bpstockData_gl_' . $currentDate, $stockData, '144000000');
            }
        }
        
        $this->view->covidDataFromMn = issetParamArray($covidDataFromMn[0]);
        
        $this->view->stockType = array(); //$this->model->getDataMartDvRowsModel('1599285567302808');
        $this->view->stockData = $stockData;
        
        $dataGlobal = $cache->get('bpthevirustracker_gl_' . $currentDate);
        $this->view->mData1 = $cache->get('bpthevirustracker_kr_' . $currentDate);
        $this->view->mData2 = $cache->get('bpthevirustracker_mn_' . $currentDate);
        $this->view->mData3 = $cache->get('bpthevirustracker_ru_' . $currentDate);
        $this->view->mData4 = $cache->get('bpthevirustracker_cn_' . $currentDate);

        $dataGlobal1 = file_get_contents('https://api.thevirustracker.com/free-api?global=stats');
        $dataGlobal = json_decode($dataGlobal1, true);

        if ($this->view->mData1 == null) {

            $mdataKr = file_get_contents('https://api.thevirustracker.com/free-api?countryTimeline=KR');
            $this->view->mData1 = json_decode($mdataKr, true);
            
            if ($this->view->mData1) {
                $cache->set('bpthevirustracker_kr_' . $currentDate, $mdataKr, '144000000');
            }
        } else {
            $this->view->mData1 = json_decode($this->view->mData1, true);
        }
        
        if ($this->view->mData2 == null) {

            $mdataMn = file_get_contents('https://api.thevirustracker.com/free-api?countryTimeline=MN');
            $this->view->mData2 = json_decode($mdataMn, true);
            
            if ($this->view->mData2) {
                $cache->set('bpthevirustracker_mn_' . $currentDate, $mdataMn, '144000000');
            }
        } else {
            $this->view->mData2 = json_decode($this->view->mData2, true);
        }

        if ($this->view->mData3 == null) {

            $mdataRu = file_get_contents('https://api.thevirustracker.com/free-api?countryTimeline=RU');
            $this->view->mData3 = json_decode($mdataRu, true);
            
            if ($this->view->mData3) {
                $cache->set('bpthevirustracker_ru_' . $currentDate, $mdataRu, '144000000');
            }
        } else {
            $this->view->mData3 = json_decode($this->view->mData3, true);
        }

        if ($this->view->mData4 == null) {

            $mdataCn = file_get_contents('https://api.thevirustracker.com/free-api?countryTimeline=CN');
            $this->view->mData4 = json_decode($mdataCn, true);
            
            if ($this->view->mData4) {
                $cache->set('bpthevirustracker_cn_' . $currentDate, $mdataCn, '144000000');
            }
        } else {
            $this->view->mData4 = json_decode($this->view->mData4, true);
        }
        
        (Array) $tempArr = array();
        
        if (issetParam($this->view->mData1['timelineitems'][0])) {
            
            foreach ($this->view->mData1['timelineitems'][0] as $key => $row) {
                if (isset($row['new_daily_cases'])) {
                    $tempArr[$key] = array('korea' => $row['new_daily_cases'], 'mon' => '0', 'russia' => '0', 'china' => '0', 'date' => Date::formatter($key, 'Y-m-d'));
                }
            }
            
        }
        if (issetParam($this->view->mData2['timelineitems'][0])) {
            
            foreach ($this->view->mData2['timelineitems'][0] as $key => $row) {
                if (isset($row['new_daily_cases']) && isset($tempArr[$key])) {
                    $tempArr[$key] = array('mon' => $row['new_daily_cases'], 'korea' => $tempArr[$key]['korea'], 'russia' => '0', 'china' => '0',  'date' => Date::formatter($key, 'Y-m-d'));
                } elseif(isset($row['new_daily_cases'])) {
                    $tempArr[$key] = array('mon' => $row['new_daily_cases'], 'korea' => '0', 'russia' => '0', 'china' => '0',  'date' => Date::formatter($key, 'Y-m-d'));
                }
            }
            
        }
        if (issetParam($this->view->mData3['timelineitems'][0])) {
            
            foreach ($this->view->mData3['timelineitems'][0] as $key => $row) {
                if (isset($row['new_daily_cases']) && isset($tempArr[$key])) {
                    $tempArr[$key] = array('russia' => $row['new_daily_cases'], 'mon' => $tempArr[$key]['mon'], 'korea' => $tempArr[$key]['korea'], 'china' => '0',  'date' => Date::formatter($key, 'Y-m-d'));
                } elseif(isset($row['new_daily_cases'])) {
                    $tempArr[$key] = array('russia' => $row['new_daily_cases'], 'mon' => '0', 'korea' => '0', 'china' => '0',  'date' => Date::formatter($key, 'Y-m-d'));
                }
            }
            
        }
        if (issetParam($this->view->mData4['timelineitems'][0])) {
            
            foreach ($this->view->mData4['timelineitems'][0] as $key => $row) {
                if (isset($row['new_daily_cases']) && isset($tempArr[$key])) {
                    $tempArr[$key] = array('russia' => $tempArr[$key]['russia'], 'mon' => $tempArr[$key]['mon'], 'korea' => $tempArr[$key]['korea'], 'china' => $row['new_daily_cases'],  'date' => Date::formatter($key, 'Y-m-d'));
                } elseif(isset($row['new_daily_cases'])) {
                    $tempArr[$key] = array('russia' => $row['new_daily_cases'], 'mon' => '0', 'korea' => '0', 'china' => $row['new_daily_cases'],  'date' => Date::formatter($key, 'Y-m-d'));
                }
            }
            
        }
        
        $this->view->cchartData = array();
        foreach ($tempArr as $key => $row) {
            array_push($this->view->cchartData, $row);
        }
        
        $this->view->covidcss = $this->view->renderPrint('custom/css', self::viewPath);
        $this->view->covidjs = $this->view->renderPrint('custom/js', self::viewPath);
        $this->view->covidData = issetParamArray($dataGlobal['results'][0]);
        
        if (!$this->view->isAjax) {
            $this->view->render('header');
        }
        
        $this->view->render('custom/mapdashboard', self::viewPath);
        
        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }
        
    }
    
    public function stockDataList() {
        $criteria = array();
        $dataViewId = Input::post('dataViewId');
        /*
        if (Input::post('stockTypeId')) {
            $criteria['typeid'][] = array(
                        'operator' => '=',
                        'operand' =>  Input::post('stockTypeId')
                    );
        }*/
        /*
        switch ($dataViewId) {
            case '1599283528592721':
                $country = 'drugname';
                $visits = 'totalquantity';
                break;
            case '1599279904704':
            case '1599285040204311':
            default:
                $country = 'name';
                $visits = 'quantity';
                break;
        }*/
        
        $stockData = $this->model->getDataMartDvRowsModel($dataViewId, $criteria);
        $this->view->stockData = array();
        /*
        if ($stockData) {
            foreach ($stockData as $key => $row) {
                $temp = array('visits' => $row[$visits], 'country' => $row[$country]);
                array_push($this->view->stockData, $temp);
            }
        }
        */
        header('Content-Type: application/json');
        echo json_encode(array('stockData' => $stockData));
    }
    
    public function aa119DataList() {
        
        $dataViewId = Input::post('dataViewId');
        $criteria['filterYear'] = array(array(
                    'operator' => '=',
                    'operand' =>  Input::post('filteryear')
                ));
        $criteria['filterMonth'] = array(array(
                    'operator' => '=',
                    'operand' =>  Input::post('filtermonth')
                ));
        $a119Caller = $this->model->getDataMartDvRowsModel($dataViewId, $criteria);
        $grippe = array();
        if ($dataViewId == '1599474553144712') {
            $grippe = $this->model->getDataMartDvRowsModel('1599478643008', $criteria);
        }
        
        header('Content-Type: application/json');
        echo json_encode(array('a119Caller' => $a119Caller, 'grippe' => $grippe));
    }
    
    public function getAllSumDuuregLicenseCv() {
        $criteria = array();
        $criteria['departmentcode'][] = array(
                        'operator' => '=',
                        'operand' =>  Input::post('aimagId')
                    );
        $this->view->map = $this->model->getDataMartDvRowsModel('1599492545702987', $criteria);
        
        echo json_encode($this->view->map);
    }
    
    public function getAimagData() {
        $criteria = array();
        $criteria['cityid'][] = array(
                        'operator' => '=',
                        'operand' =>  Input::post('aimagId')
                    );
        
        $this->view->map = $this->model->getDataMartDvRowsModel('1599659078096461', $criteria);
        $this->view->map2 = $this->model->getDataMartDvRowsModel('1600580467916828', $criteria);
        
        echo json_encode(array('data1' => issetParamArray($this->view->map[0]), 'data2' => issetParamArray($this->view->map2[0])));
    }
    
    public function getMapFilterTypeData() {
        $criteria = array();
        $this->view->map = $this->model->getDataMartDvRowsModel(Input::post('id'), $criteria);
        echo json_encode(array('map' => $this->view->map));
    }
    
    public function getDrill() {
        $this->load->model('mdobject', 'middleware/models/');
        $ddown = $this->model->getDrillDownMetaDataModel(Input::post('metadataid'), 'name');
        $result = array();
        
        if ($ddown) {        
            $sourceParam = '';
            $sizeDrillDownArray = count($ddown);
            $rowData = Input::post('rowdata');

            $link_metatypecode = $ddown[0]['META_TYPE_CODE'];
            $link_linkmetadataid = $ddown[0]['LINK_META_DATA_ID'];
            $link_linkcriteria = $ddown[0]['CRITERIA'];
            $link_dialogWidth = $ddown[0]['DIALOG_WIDTH'];
            $link_dialogHeight = $ddown[0]['DIALOG_HEIGHT'];
            
            $clinkMetadataId = 1;
            if ($ddown[0]['DEFAULT_VALUE']) {
                $sourceParam = ($ddown[0]['TRG_PARAM']) ? $ddown[0]['TRG_PARAM']."=". $ddown[0]['DEFAULT_VALUE'] : '';
            } else {
                $sourceParam = ($ddown[0]['TRG_PARAM']) ? $ddown[0]['TRG_PARAM']."=". issetParam($rowData[Str::lower($ddown[0]['SRC_PARAM'])]) : '';
            }

            if ($sizeDrillDownArray > 1) {
                $linkDrillDown = self::drilldownParams($ddown, $rowData);              

                $link_linkmetadataid = $linkDrillDown['LINK_METADATAID'];
                $link_linkcriteria = Str::nlToSpace($linkDrillDown['LINK_CRITERIA']);
                $link_metatypecode = $linkDrillDown['LINK_METATYPECODE'];
                $clinkMetadataId = $linkDrillDown['LINK_COUNT'];
                $sourceParam = $linkDrillDown['LINK_PARAM'];
            }              
            
            $isnewTab = 'false';
            if (isset($ddown[0]['SHOW_TYPE'])) {
                $showType = strtolower($ddown[0]['SHOW_TYPE']);
                if ($showType == 'tab') {
                    $isnewTab = true;
                } elseif ($showType) {
                    $isnewTab = "'".$showType."'";
                }
            }            
            
            $result = array(
                'sourceParam' => $sourceParam,
                'link_metatypecode' => $link_metatypecode,
                'link_linkmetadataid' => $link_linkmetadataid,
                'link_linkcriteria' => $link_linkcriteria,
                'link_dialogWidth' => $link_dialogWidth,
                'link_dialogHeight' => $link_dialogHeight,
                'clinkMetadataId' => $clinkMetadataId,
                'isnewTab' => $isnewTab,
            );
        }
        jsonResponse($result);
    }
    
    public function drilldownParams($drilldownRows, $rowData) {
        (Array) $array_linkmetadataid_cc = $array_linkcriteria = $array_metatypecode = $array_dialogWidth = $array_dialogHeight = $array_linkmetadataid = $array_param = array();
        $clinkMetadataId = -1;
        
        foreach ($drilldownRows as $key => $dvalue) {
            if (!in_array($dvalue['LINK_META_DATA_ID'] . '_' . $dvalue['CRITERIA'], $array_linkmetadataid_cc)) {
                array_push($array_linkmetadataid_cc, $dvalue['LINK_META_DATA_ID'] . '_' . $dvalue['CRITERIA']);
                array_push($array_linkmetadataid, $dvalue['LINK_META_DATA_ID']);
                array_push($array_linkcriteria, $dvalue['CRITERIA']);
                array_push($array_dialogWidth, $dvalue['DIALOG_WIDTH']);
                array_push($array_dialogHeight, $dvalue['DIALOG_HEIGHT']);
                array_push($array_metatypecode, $dvalue['META_TYPE_CODE']);
                
                $clinkMetadataId++;
                if ($dvalue['DEFAULT_VALUE']) {
                    $array_param[$clinkMetadataId] = ($dvalue['TRG_PARAM']) ? $dvalue['TRG_PARAM']."=". $dvalue['DEFAULT_VALUE'] : '';
                } else {
                    $array_param[$clinkMetadataId] = ($dvalue['TRG_PARAM']) ? $dvalue['TRG_PARAM']."=". issetParam($rowData[Str::lower($dvalue['SRC_PARAM'])]) : '';
                }                 
            } else {
                if ($dvalue['DEFAULT_VALUE']) {
                    $array_param[$clinkMetadataId] .= ($dvalue['TRG_PARAM']) ? "&".$dvalue['TRG_PARAM']."=". $dvalue['DEFAULT_VALUE'] : '';
                } else {
                    $array_param[$clinkMetadataId] .= ($dvalue['TRG_PARAM']) ? "&".$dvalue['TRG_PARAM']."=". issetParam($rowData[Str::lower($dvalue['SRC_PARAM'])]) : '';
                }                
            }                       
        }
        
        $clinkMetadataId++;
       
        $link_linkmetadataid = Arr::implode_r(',', $array_linkmetadataid, true);
        $link_dialogHeight = Arr::implode_r(',', $array_dialogHeight, true);
        $link_dialogWidth = Arr::implode_r(',', $array_dialogWidth, true);
        $link_linkcriteria = Arr::implode_r(',', $array_linkcriteria, true);
        $link_metatypecode = Arr::implode_r(',', $array_metatypecode, true);
        $link_param = Arr::implode_r(",", $array_param, true);

        return array('LINK_METADATAID' => $link_linkmetadataid, 'DIALOG_HEIGHT' => $link_dialogHeight, 'DIALOG_WIDTH' => $link_dialogWidth, 'LINK_CRITERIA' => $link_linkcriteria, 'LINK_METATYPECODE' => $link_metatypecode, 'LINK_COUNT' => $clinkMetadataId, 'LINK_PARAM' => $link_param);
    }    
    
    public function sendMailForm() {
        
        if (!isset($this->view)) {
            $this->view = new View();
        }
        
        $this->view->mailList = $this->model->getDataMartDvRowsModel('1611113151330915');
        
        $response = array(
            'html' => $this->view->renderPrint('sendMail', self::$viewPath4),
            'title' => $this->lang->line('sendmail'),
            'send_btn' => $this->lang->line('send_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }    
    
    public function sendMail() {
        
        $emailTo = rtrim(Input::post('emailTo'), ';');
        $emailToCc = rtrim(Input::post('emailToCc'), ';');
        $emailSubject = Input::post('emailSubject');
        $emailBody = Input::post('emailBody');
        $emailFile = Input::post('emailFile');
        $emailToArr = Input::post('emailToArr');
        $emailToCcArr = Input::post('emailToCcArr');
        
        $emailTo .= $emailTo ? ($emailToArr ? ';'.implode(';',array_unique($emailToArr)) : '') : ($emailToArr ? implode(';',array_unique($emailToArr)) : '');
        $emailToCc .= $emailToCc ? ($emailToCcArr ? ';'.implode(';',array_unique($emailToCcArr)) : '') : ($emailToCcArr ? implode(';',array_unique($emailToCcArr)) : '');
        
        $emailBody = $emailBody ? $emailBody.'<br/><br/>'.$emailFile : $emailFile;
        $response = $this->model->sendMailModel($emailTo, $emailToCc, $emailSubject, $emailBody);

        echo json_encode($response); exit;
    }    
    
}