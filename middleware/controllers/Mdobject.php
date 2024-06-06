<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdobject Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Object
 * @author	B.Och-Erdene <ocherdene@interactive.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdobject
 */
class Mdobject extends Controller {

    private static $dataViewPath = 'middleware/views/metadata/dataview/';
    private static $mainViewPath = 'middleware/views/metadata/';
    private static $packageViewPath = 'middleware/views/metadata/package/';
    public static $exportHeaderConfig = array();
    public static $onlyShowColumns = array();
    public static $indentRows = array();
    public static $exportDataRows = array();
    public static $exportedIds = array();
    public static $pfKpiTemplateDynamicColumn = null;

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }

    public static function gridDefaultOptions($option = '') {
        $array = array(
            'detaultViewer' => 'detail',
            'viewTheme' => 'jeasyuiTheme3',
            'mobileTheme' => 'DV_theme1',
            'resizeHandle' => 'right',
            'fitColumns' => 'false',
            'autoRowHeight' => 'true', 
            'striped' => 'false',
            'method' => 'post',
            'nowrap' => 'true',
            'loadMsg' => Lang::line('PL_0262'),
            'pagination' => 'true',
            'rownumbers' => 'true',
            'singleSelect' => 'true',
            'ctrlSelect' => 'false',
            'checkOnSelect' => 'true',
            'selectOnCheck' => 'true',
            'pagePosition' => 'bottom',
            'pageNumber' => '1',
            'pageSize' => '50',
            'pageList' => '[50,100,200,300,500]',
            'sortName' => '',
            'sortOrder' => '',
            'multiSort' => 'false',
            'remoteSort' => 'true',
            'showHeader' => 'true',
            'showFooter' => 'false',
            'scrollbarSize' => '18', 
            'mergeCells' => 'false',
            'mergeCellsKeyField' => '',
            'enableFilter' => 'true', 
            'filterAutoComplete' => 'false',
            'showCheckbox' => 'true',
            'showFileicon' => 'true',
            'firstRowSelect' => 'false',
            'inlineEdit' => 'false', 
            'drillDblClickRow' => '', 
            'groupsum' => 'false',
            'grouptitlefreeze' => 'false',
            'groupField' => '', 
            'json_config' => ''
        );

        if (!empty($option)) {
            if (isset($array[$option])) {
                return $array[$option];
            }
            return '';
        }
        return $array;
    }

    public function getGridOptions($metaObjectId) {
        $this->load->model('mdobject', 'middleware/models/');
        return $this->model->getGridOptionsModel($metaObjectId);
    }
    
    public function autoCompleteByIdMulti() {
        
        $processMetaDataId = Input::post('processMetaDataId');
        $loopupMetaId = Input::post('lookupId');
        $paramRealPath = Input::post('paramRealPath');
        $code = Str::lower(Input::post('code'));
        
        $attributes = $this->model->getDataViewMetaValueAttributes($processMetaDataId, $paramRealPath, $loopupMetaId);
        
        $idField = strtolower($attributes['id']);
        
        $param = array(
            'systemMetaGroupId' => $loopupMetaId,
            'showQuery' => 0, 
            'ignorePermission' => 1,  
            'criteria' => array(
                $idField => array(
                    array(
                        'operator' => 'IN',
                        'operand' => $code
                    )
                )
            )
        );

        $data = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

        if ($data['status'] == 'success' && isset($data['result'])) {
        
            unset($data['result']['aggregatecolumns']);
            unset($data['result']['paging']);
            
            $codeField = strtolower($attributes['code']);
            $nameField = strtolower($attributes['name']);
            $dataResult = $data['result'];
            
            $ids = $codes = $names = '';
            
            foreach ($dataResult as $row) {
                $ids .= $row[$idField].',';
                $codes .= $row[$codeField].', ';
                $names .= $row[$nameField].', ';
            }
                        
            echo json_encode(
                array(
                    'ids' => rtrim($ids, ','), 
                    'codes' => rtrim($codes, ', '), 
                    'names' => rtrim($names, ', ')
                ), JSON_UNESCAPED_UNICODE
            );
            
        } else {
            echo '';
        }
    }
    
    public function autoCompleteById() {

        $processMetaDataId = Input::post('processMetaDataId');
        $loopupMetaId = Input::post('lookupId');
        $paramRealPath = Input::post('paramRealPath');
        $code = !is_array(Input::post('code')) ? Str::lower(Input::post('code')) : '';
        
        $isName = $isId = $row = false;
        $isValueNotEmpty = true;

        if ($code == '') {
            $isValueNotEmpty = false;
        }

        if ($loopupMetaId != '' && $isValueNotEmpty) {

            if (Input::postCheck('isName')) {
                $isNamePost = Input::post('isName');
                if ($isNamePost == 'true') {
                    $isName = true;
                }
                if ($isNamePost == 'idselect') {
                    $isId = true;
                }
            }
                
            $attributes = $this->model->getDataViewMetaValueAttributes($processMetaDataId, $paramRealPath, $loopupMetaId);
            
            if ($filterPath = Input::post('filterPath')) {
                $isId = $isName = false;
                $attributes['code'] = $filterPath;
            }

            if ($isId) {

                if (isset($attributes['id'])) {

                    $idField = strtolower($attributes['id']);

                    $criteria[$idField][] = array(
                        'operator' => '=',
                        'operand' => $code
                    );

                    if (Input::isEmpty('params') === false) {
                        parse_str(urldecode(Input::post('params')), $inputParamArr);
                        foreach ($inputParamArr as $key => $rowVal) {
                            
                            if (is_array($rowVal)) {
                                
                                $criteria[$key][] = array(
                                    'operator' => 'IN', 
                                    'operand' => Arr::implode_r(',', $rowVal, true) 
                                );
                                
                            } elseif ($rowVal != '') {
                                
                                if (strpos($rowVal, '^') === false) {
                                    $criteria[$key][] = array(
                                        'operator' => '=',
                                        'operand' => $rowVal
                                    );
                                } else {
                                    $criteria[$key][] = array(
                                        'operator' => 'IN',
                                        'operand' => Arr::implode_r(',', explode('^', $rowVal), true)
                                    );
                                }
                            }
                        }
                    }

                    $result = $this->model->getDataViewByCriteriaModel($loopupMetaId, $criteria, $idField, 'id');
                    
                    if ($result) {
                        
                        $codeField = strtolower($attributes['code']);
                        $nameField = strtolower($attributes['name']);
                        
                        $this->load->model('mdmetadata', 'middleware/models/');
                        $controlsData = $this->model->getLookupCloneFieldModel($processMetaDataId, $paramRealPath);

                        $row = array(
                            'META_VALUE_ID' => ($idField ? $result[$idField] : (isset($result['id']) ? $result['id'] : '')),
                            'META_VALUE_CODE' => (isset($result[$codeField]) ? html_entity_decode($result[$codeField], ENT_QUOTES, 'UTF-8') : ''),
                            'META_VALUE_NAME' => (isset($result[$nameField]) ? html_entity_decode($result[$nameField], ENT_QUOTES, 'UTF-8') : ''), 
                            'controlsData' => $controlsData, 
                            'rowData' => $result
                        );
                    }
                }

            } else {
                if ($isName) {
                    if (isset($attributes['name'])) {

                        if (isset($attributes['isDefaultValues'])) {
                            Mdmetadata::$isProcessParamValues = true;
                            Mdwebservice::$processMetaDataId = $processMetaDataId;
                            Mdwebservice::$paramRealPath = $paramRealPath;
                        }

                        $idField = strtolower($attributes['id']);
                        $nameField = strtolower($attributes['name']);

                        $criteria[$nameField][] = array(
                            'operator' => '=',
                            'operand' => $code
                        );

                        if (Input::isEmpty('params') === false) {
                            
                            parse_str(urldecode(Input::post('params')), $inputParamArr);
                            
                            foreach ($inputParamArr as $key => $rowVal) {
                                
                                if (is_array($rowVal)) {
                                    $criteria[$key][] = array(
                                        'operator' => 'IN', 
                                        'operand' => Arr::implode_r(',', $rowVal, true) 
                                    );
                                } elseif ($rowVal != '') {
                                    
                                    if (strpos($rowVal, '^') === false) {
                                        $criteria[$key][] = array(
                                            'operator' => '=',
                                            'operand' => $rowVal
                                        );
                                    } else {
                                        $criteria[$key][] = array(
                                            'operator' => 'IN',
                                            'operand' => Arr::implode_r(',', explode('^', $rowVal), true)
                                        );
                                    }
                                }
                            }
                        }

                        $result = $this->model->getDataViewByCriteriaModel($loopupMetaId, $criteria, $idField, 'name');

                        if ($result) {
                            $codeField = strtolower($attributes['code']);
                            
                            $this->load->model('mdmetadata', 'middleware/models/');
                            $controlsData = $this->model->getLookupCloneFieldModel($processMetaDataId, $paramRealPath);

                            $row = array(
                                'META_VALUE_ID' => ($idField ? $result[$idField] : (isset($result['id']) ? $result['id'] : '')),
                                'META_VALUE_CODE' => (isset($result[$codeField]) ? html_entity_decode($result[$codeField], ENT_QUOTES, 'UTF-8') : ''),
                                'META_VALUE_NAME' => (isset($result[$nameField]) ? html_entity_decode($result[$nameField], ENT_QUOTES, 'UTF-8') : ''), 
                                'controlsData' => $controlsData, 
                                'rowData' => $result
                            );
                        }
                    }

                } else {

                    if (isset($attributes['code'])) {

                        if (isset($attributes['isDefaultValues'])) {
                            Mdmetadata::$isProcessParamValues = true;
                            Mdwebservice::$processMetaDataId = $processMetaDataId; 
                            Mdwebservice::$paramRealPath = $paramRealPath;
                        }

                        $idField = strtolower($attributes['id']);
                        $codeField = strtolower($attributes['code']);

                        $criteria[$codeField][] = array(
                            'operator' => '=',
                            'operand' => $code
                        );

                        if (Input::isEmpty('params') === false) {
                            parse_str(urldecode(Input::post('params')), $inputParamArr);

                            foreach ($inputParamArr as $key => $rowVal) {
                                
                                if (is_array($rowVal)) {
                                    $criteria[$key][] = array(
                                        'operator' => 'IN', 
                                        'operand' => Arr::implode_r(',', $rowVal, true) 
                                    );
                                } elseif ($rowVal != '') {
                                    
                                    if (strpos($rowVal, '^') === false) {
                                        $criteria[$key][] = array(
                                            'operator' => '=',
                                            'operand' => $rowVal 
                                        );
                                    } else {
                                        $criteria[$key][] = array(
                                            'operator' => 'IN',
                                            'operand' => Arr::implode_r(',', explode('^', $rowVal), true)
                                        );
                                    }
                                }
                            }
                        }

                        $result = $this->model->getDataViewByCriteriaModel($loopupMetaId, $criteria, $idField, 'code');

                        if ($result) {
                            $nameField = strtolower($attributes['name']);
                            
                            $this->load->model('mdmetadata', 'middleware/models/');
                            $controlsData = $this->model->getLookupCloneFieldModel($processMetaDataId, $paramRealPath);

                            $row = array(
                                'META_VALUE_ID' => $idField ? $result[$idField] : (isset($result['id']) ? $result['id'] : ''),
                                'META_VALUE_CODE' => isset($result[$codeField]) ? html_entity_decode($result[$codeField], ENT_QUOTES, 'UTF-8') : '',
                                'META_VALUE_NAME' => isset($result[$nameField]) ? html_entity_decode($result[$nameField], ENT_QUOTES, 'UTF-8') : '', 
                                'controlsData' => $controlsData, 
                                'rowData' => $result
                            );
                        }
                    }
                }
            }
        }

        if ($row) {
            echo json_encode($row, JSON_UNESCAPED_UNICODE);
        } else {
            $response = array('META_VALUE_ID' => '', 'META_VALUE_CODE' => '', 'META_VALUE_NAME' => '');
            echo json_encode($response);
        }
    }

    public function bpLinkedCombo() {
        
        if (Input::isEmpty('jsonAttr') === false) {
            $mdWebserviceCtrl = Controller::loadController('Mdwebservice', 'middleware/controllers/');
            $mdWebserviceCtrl->comboDataSet(); exit;
        }
        
        if (Input::postCheck('processMetaDataId')) {
            $inputMetaDataId = Input::post('processMetaDataId');
            $isProcess = true;
        } else {
            $inputMetaDataId = Input::post('inputMetaDataId');
            $isProcess = false;
        }
        
        $this->load->model('mdobject', 'middleware/models/');
        
        $selfParam = Input::post('selfParam');
        $inputParams = Input::post('inputParams');
        $inputParamArr = Str::simple_parse_str($inputParams);
        $linkedCombo = $this->model->getLinkedComboMetaGroup($inputMetaDataId, $selfParam, $isProcess);
        
        if (isset($linkedCombo['LOOKUP_META_DATA_ID'])) {
            if ($isProcess) {
                $data = $this->model->responseDataForProcessLinkedCombo($inputMetaDataId, $linkedCombo['LOOKUP_META_DATA_ID'], $selfParam, $inputParamArr);
            } else {
                $data = $this->model->responseDataForMetaGroupLinkedCombo($inputMetaDataId, $linkedCombo['LOOKUP_META_DATA_ID'], $selfParam, $inputParamArr);
            }
        } else {
            $data = array($selfParam => array());
        }

        echo json_encode($data, JSON_UNESCAPED_UNICODE); 
    }

    public function autoCompleteObjectTypeByMetaCode() {

        $code = Str::lower(Input::post('code'));
        $row = $this->model->getMetaDataObjectTypeRowByCodeModel($code);

        if ($row) {
            echo json_encode($row, JSON_UNESCAPED_UNICODE);
        } else {
            $response = array('META_DATA_ID' => '', 'META_DATA_CODE' => '', 'META_DATA_NAME' => '');
            echo json_encode($response);
        }
        exit;
    }
    
    public static function findMandatoryCriteria($dataViewId, $dataViewHeaderData) {
        $cache = phpFastCache();
        $userId = Ue::sessionUserId();
        
        $data = $cache->get('dvMandatoryCriterias_'.$dataViewId.'_'.$userId);
        
        if ($data == null) {
            $data = Arr::multidimensional_list($dataViewHeaderData, array('IS_MANDATORY_CRITERIA' => '1'));
            
            $cache->set('dvMandatoryCriterias_'.$dataViewId.'_'.$userId, $data, Mdwebservice::$expressionCacheTime);
        }
        
        return $data;
    }
    
    public static function findCriteria($dataViewId, $dataViewHeaderData) {
        $cache = phpFastCache();
        $userId = Ue::sessionUserId();
        
        $data = $cache->get('dvCriterias_'.$dataViewId.'_'.$userId);
        $dataGroup = $cache->get('dvGroupCriterias_'.$dataViewId.'_'.$userId);
        
        if ($data == null) {
            $data = $searchHeaderArr = $searchContentArr = $notShowCriteria = array();
            
            foreach ($dataViewHeaderData as $key => $row) {
                if ($row['IS_MANDATORY_CRITERIA'] != '1' && $row['IS_SHOW'] == '1') {
                    
                    if ($row['IS_NOT_SHOW_CRITERIA'] == '1') {
                        array_push($notShowCriteria, $row);
                    } else {
                        $searchGroupName = trim($row['SEARCH_GROUPING_NAME']);
                    
                        if (!empty($searchGroupName)) {
                            if (!in_array($searchGroupName, $searchHeaderArr)) {
                                $searchHeaderArr[$key] = $searchGroupName;
                            }
                            $groupKey = array_search($searchGroupName, $searchHeaderArr);
                            $searchContentArr[$groupKey][] = $row;
                        } else {
                            array_push($data, $row);
                        }
                    }
                }
            }
            
            $cache->set('dvCriterias_'.$dataViewId.'_'.$userId, $data, Mdwebservice::$expressionCacheTime);
            
            $dataGroup = array(
                'header' => $searchHeaderArr, 
                'content' => $searchContentArr, 
                'notShowCriteria' => $notShowCriteria
            );
            $cache->set('dvGroupCriterias_'.$dataViewId.'_'.$userId, $dataGroup, Mdwebservice::$expressionCacheTime);
        }
        
        return array(
            'data' => $data,
            'dataGroup' => $dataGroup
        );
    }

    public function dataValueViewer($hidden = '0', $dialogMode = false) {
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $metaDataId = Input::numeric('metaDataId');
        $workSpaceId = Input::numeric('workSpaceId');
        $workSpaceParams = Input::post('workSpaceParams');
        $uriParams = Input::post('uriParams');
        $permissionCriteria = Input::post('permissionCriteria');
        $dataGridDefaultHeight = Input::post('dataGridDefaultHeight');
        $calendarParams = Input::post('calendarParams');
        $this->view->viewType = strtolower(Input::post('viewType'));
        $this->view->saveCriteriaTemplate = Config::getFromCache('saveCustomerFilter');
        
        if (!isset($this->view->callerType)) {
            $this->view->callerType = Input::post('callerType');
        }
        
        if (!isset($this->view->packageRenderType)) {
            $this->view->packageRenderType = Input::post('packageRenderType');
        }
        
        self::saveDataViewConfigUser($metaDataId, $this->view->viewType);
        
        if ($this->view->viewType == 'explorer') {
            
            $content = self::explorerDataViewer($metaDataId, $workSpaceId, $workSpaceParams, $uriParams, $hidden, $dialogMode, $permissionCriteria, $dataGridDefaultHeight, $calendarParams);
            
        } elseif ($this->view->viewType == 'ganttchart') {
            
            $content = self::explorerDataViewer($metaDataId, $workSpaceId, $workSpaceParams, $uriParams, $hidden, $dialogMode, $permissionCriteria, $dataGridDefaultHeight, $calendarParams, 'ganttchart');
            
        } elseif ($this->view->viewType == 'calendar') {
            
            $content = self::calendarDataViewer($metaDataId, $workSpaceId, $workSpaceParams, $uriParams, $hidden, $dialogMode, $permissionCriteria);
            
        } else {
            $content = self::detailDataViewer($metaDataId, $workSpaceId, $workSpaceParams, $uriParams, $hidden, $dialogMode, $permissionCriteria, $dataGridDefaultHeight, $calendarParams);
        }
        
        if ($dialogMode) {
            return $content;
        } else {
            $content;
        }
    }

    public function detailDataViewer($metaDataId, $workSpaceId = null, $workSpaceParams = null, $uriParams = null, $hidden, $dialogMode = false, $permissionCriteria = null, $dataGridDefaultHeight = '', $calendarParams = null, $isBasket = false, $dataviewUniqId = '', $isBasketView = '0') {
        if (!isset($this->view)) {
            $this->view = new View();
        }
        $this->load->model('mdobject', 'middleware/models/');
        
        $this->view->metaDataId = $metaDataId;
        $this->view->uriParams = $uriParams;
        $this->view->calendarParams = $calendarParams;
        $this->view->dataGridDefaultOption = Mdobject::gridDefaultOptions();
        
        $this->view->dataGridOptionData = $this->model->getDVGridOptionsModel($this->view->metaDataId);
        $this->view->row = $this->model->getDataViewConfigRowModel($this->view->metaDataId);
        
        $this->view->metaDataCode = $this->view->row['META_DATA_CODE'];
        $this->view->refStructureId = $this->view->row['REF_STRUCTURE_ID'];
        $this->view->title = $this->lang->line($this->view->row['LIST_NAME']);
        $this->view->layoutLink = array();

        if (Input::isEmpty('uriParams') === false) {
            $uriParamsArray = json_decode(str_replace("&quot;", "\"", Input::post('uriParams')), true);
            if ($uriParamsArray) {
                $this->view->fillPath = $uriParamsArray;
            }
        }
        
        if (Input::isEmpty('drillDownDefaultCriteria') === false) {
            
            $drillDownDefaultCriteria = Input::postNonTags('drillDownDefaultCriteria');
            
            if (Json::isJson($drillDownDefaultCriteria)) {
                
                $decodeJsonDrill = json_decode($drillDownDefaultCriteria, true);
                
                $this->view->fillPath = $decodeJsonDrill;
                $this->view->drillDownDefaultCriteria = $drillDownDefaultCriteria;                   
                
            } else {
                
                parse_str(Input::post('drillDownDefaultCriteria'), $postParam);

                $addonJsonParam = isset($_POST['addonJsonParam']) ? json_decode($_POST['addonJsonParam'], true) : array();
                $defaultCriteriaParams = isset($_POST['defaultCriteriaParams']) ? json_decode($_POST['defaultCriteriaParams'], true) : array();

                foreach ($postParam as $pp => $pv) {
                    if ($pv == 'undefined' || $pv == 'null') {
                        if ($addonJsonParam) {
                            foreach ($addonJsonParam as $ap => $av) {
                                if ($av !== '') {
                                    $postParam[strtolower($ap)] = $av;
                                } else {
                                    $postParam[strtolower($pp)] = '';
                                }
                            }
                        } else {
                            $postParam[strtolower($pp)] = '';
                        }
                    }
                }

                if ($defaultCriteriaParams) {
                    foreach ($defaultCriteriaParams as $ap => $av) {
                        if (!isset($postParam[strtolower($ap)]) || $postParam[strtolower($ap)] == '') {
                            $postParam[strtolower($ap)] = $av;
                        } 
                    }
                }

                $this->view->fillPath = $postParam;
                $this->view->drillDownDefaultCriteria = json_encode($postParam, JSON_UNESCAPED_UNICODE);   
                
                Mdobject::$pfKpiTemplateDynamicColumn = issetParam($postParam['pfKpiTemplateDynamicColumn']);
            }
        }
        
        if (Input::isEmpty('dashboardDrillDownCriteria') === false) {
            $this->view->fillPath = Input::post('dashboardDrillDownCriteria');
            $this->view->drillDownDefaultCriteria = json_encode($this->view->fillPath);   
        }
        
        if (issetParam($this->view->row['DATA_LEGEND_DV_ID'])) {
            $this->view->dataviewLegendData = $this->model->getDataLegendModel($this->view->row['DATA_LEGEND_DV_ID']);
        }        
        
        $this->view->isGridType = 'datagrid';
        $this->view->isTreeGridData = $this->view->row['TREE_GRID'];
        
        if ($this->view->isTreeGridData) {
            $this->view->isGridType = 'treegrid';
        }
        
        $viewPath = 'viewer/detail/index';
        
        $this->view->subUniqId = $metaDataId;
        $this->view->dvDefaultCriteria = Input::post('dvDefaultCriteria');
        
        if (Input::postCheck('srcDataViewId')) {
            $this->view->subUniqId = getUID();
            $this->view->srcDataViewId = Input::post('srcDataViewId');
            $this->view->rowIndex = Input::post('rowIndex');
            
            $this->view->dataGridColumnData = $this->model->renderDataViewGridCache($this->view->metaDataId, $this->view->metaDataCode, $this->view->refStructureId, false, $isBasket);
            
            $viewPath = 'viewer/detail/subgrid';
        }
        
        $this->view->isRowColor = false;
        
        if ($this->view->row['COUNT_ROWCOLOR'] != '0') {
            $this->view->isRowColor = true;
        }
        
        $this->view->isTextColor = false;
        
        if ($this->view->row['COUNT_TEXTCOLOR'] != '0') {
            $this->view->isTextColor = true;
        }
        
        $this->view->isUseSidebar = $this->view->row['IS_USE_SIDEBAR'];
        $this->view->buttonBarStyle = $this->view->row['BUTTON_BAR_STYLE'];
        $this->view->refreshTimer = $this->view->row['REFRESH_TIMER'];
        $this->view->quickSearch = '';
        
        if (!isset($this->view->srcDataViewId)) {
            
            $this->view->dataGridColumnData = $this->model->renderDataViewGridModel($this->view->metaDataId, $this->view->metaDataCode, $this->view->refStructureId, false, $isBasket);
            
            $this->view->isLayout = false;
            $this->view->workSpaceId = $workSpaceId;
            $this->view->workSpaceParams = $workSpaceParams;
            $this->view->hiddenFields = $hidden;
            $this->view->dataGridDefaultHeight = $this->view->row['WINDOW_HEIGHT'] == 'auto' ? 'auto' : $dataGridDefaultHeight;
            $this->view->dataGridDefaultHeight = empty($this->view->dataGridDefaultHeight) ? $this->view->row['WINDOW_HEIGHT'] : $this->view->dataGridDefaultHeight;
            
            $this->view->toolbar = true;
            $this->view->isPrint = false;
            
            if ($this->view->row['COUNT_REPORT_TEMPLATE'] != '0') {
                $this->view->isPrint = true;
            }
            
            $this->view->methodId = 1;
            $this->view->isDialog = false;
            $this->view->methodRow['META_DATA_NAME'] = $this->view->metaDataId;
            
            $this->view->isStatementBtnSee = ($this->view->row['COUNT_STATEMENT_TEMPLATE'] != '0') ? true : false;
            $this->view->isGoogleMap = ($this->view->row['COUNT_GOOGLE_MAP'] != '0') ? true : false;
            $this->view->isCardSee = ($this->view->row['COUNT_CARD'] != '0') ? true : false;
            $this->view->isCalendarSee = ($this->view->row['COUNT_CALENDAR_SEE'] != '0') ? true : false;
            $this->view->isExportText = ($this->view->row['IS_EXPORT_TEXT'] == '1') ? true : false;
            $this->view->useBasketBtn = $this->view->row['USE_BASKET'] == '1' ? true : false;
            $this->view->useBasket = false;
            $this->view->calendarMetaDataId = $this->view->row['CALENDAR_META_DATA_ID'];
            
            $this->view->selectedRowData = '';
            if (issetParam($_POST['isSelectedBasket']) == '1') {
                $this->view->useBasket = true;
                $this->view->selectedRowData = issetParam($_POST['selectedRowData']);
                $this->view->chooseTypeBasket = issetParam($_POST['chooseTypeBasket']);
                
                if ($this->view->useBasket && isset($_POST['selectedRowData'][0])) {
                    if (isset($this->view->row['idField'])) {
                        $this->view->primaryField = $this->view->row['idField'];
                    } else {
                        $this->view->primaryField = 'id';
                    }                
                    $this->view->selectedRowData = $this->model->selectedRowConvertArrayTheme($this->view->metaDataId, $this->view->primaryField);                
                }
            }
            
            $this->load->model('mdobject', 'middleware/models/');
            $this->view->dataViewWorkFlowBtn = ($this->view->row['COUNT_WFM_WORKFLOW'] != '0' && $this->view->row['IS_USE_WFM_CONFIG'] == '1') ? true : false;
            $this->view->dataViewProcessCommand = $this->model->dataViewProcessCommandModel($this->view->metaDataId, $this->view->metaDataCode, false, $isBasket, $dataviewUniqId);
            
            $this->view->openDefaultBp = $this->view->dataViewProcessCommand['isBpOpen'];

            $this->view->permissionCriteria = $permissionCriteria;

            if ($this->view->permissionCriteria !== null) {
                $this->view->dataViewHeaderRealData = $this->model->dataViewHeaderDataUmCriteriaModel($this->view->metaDataId);
                if (trim($permissionCriteria) !== '_ERP_') {
                    $this->view->permissionCriteriaData = Mdpermission::getCriteriaStringToArray($permissionCriteria);    
                }
            } else {
                $this->view->dataViewHeaderRealData = $this->model->dataViewHeaderDataModel($this->view->metaDataId);
            }
            
            $this->view->dataViewMandatoryHeaderData = self::findMandatoryCriteria($this->view->metaDataId, $this->view->dataViewHeaderRealData);
            $this->view->dataViewHeaderData = self::findCriteria($this->view->metaDataId, $this->view->dataViewHeaderRealData);

            $this->view->isCheckDataViewHeaderData = true;
            $this->view->dataViewCriteriaType = strtolower($this->view->row['SEARCH_TYPE'] == '0' ? 'BUTTON' : Info::getSearchType($this->view->row['SEARCH_TYPE']));

            $this->view->metaLayoutLinkId = $this->view->row['LAYOUT_META_DATA_ID'];
            $this->view->metaLayoutBtn = ($this->view->metaLayoutLinkId) ? true : false;
            $this->view->isEmptyCriteria = empty($this->view->dataViewHeaderData['data']) && empty($this->view->dataViewHeaderData['dataGroup']['header']) ? false : true;

            $this->view->isDashboard = ($this->view->row['COUNT_DASHBOARD_LINK'] != '0') ? true : false;
            $this->view->isReportTemplate = (isset($this->view->row['C_REPORT_TEMPLATE']) && $this->view->row['C_REPORT_TEMPLATE'] != '0' && $this->view->row['IS_USE_RT_CONFIG'] == '1') ? true : false;

            $categoryList = $this->view->row['treeCategoryList'];
            
            if (!$categoryList) {
                $this->view->isTree = false;
                $this->view->treeCategoryList = array();
                $this->view->filterFieldList = array();
            } else {
                $this->view->isTree = true;
                $this->view->treeCategoryList = $categoryList['CATEGORY_LIST'];
                $this->view->filterFieldList = $categoryList['FILTER_FIELD'];
            }

            $this->view->dataViewClass = $this->model->getDataViewClassModel($this->view->hiddenFields, $this->view->dataViewCriteriaType, $this->view->isCheckDataViewHeaderData, $this->view->dataViewHeaderData, $this->view->isTree);
            $this->view->dvScripts = Mdexpression::searchGenerateScripts($this->view->metaDataId);
            
            $this->view->layoutTypes = null;
            $this->view->layoutType = null;
            
            if (issetParam($this->view->row['dataViewLayoutTypes'])) {
                
                if (isset($this->view->row['dataViewLayoutTypes']['card'])) {
                    
                    $this->view->layoutType = 'card';
                    $this->view->layoutTypes = $this->view->renderPrint('viewer/detail/layout/card', self::$dataViewPath);
                    
                } elseif (isset($this->view->row['dataViewLayoutTypes']['card1'])) {
                    
                    $this->view->layoutType = 'card1';
                    $this->view->layoutTypes = $this->view->renderPrint('viewer/detail/layout/card1', self::$dataViewPath);
                    
                } elseif (isset($this->view->row['dataViewLayoutTypes']['card2'])) {
                    
                    $this->view->layoutType = 'card2';
                    $this->view->drillDownLink = $this->model->getExplorerDrillDownLinkModel($this->view->metaDataId, $this->view->metaDataCode);

                    $this->view->layoutTypes = $this->view->renderPrint('viewer/detail/layout/card2', self::$dataViewPath);
                    
                } elseif (isset($this->view->row['dataViewLayoutTypes']['ecommerce_basket'])) {
                                        
                    $this->view->layoutType = 'ecommerce_basket';
                    $this->view->layoutTypes = $this->view->renderPrint('viewer/detail/layout/ecommerce_basket', self::$dataViewPath);
                    
                } elseif (isset($this->view->row['dataViewLayoutTypes']['card_business'])) {
                    $layoutThemeSt = $this->view->row['dataViewLayoutTypes']['card_business']['LAYOUT_THEME'];
                    
                    switch ($layoutThemeSt) {
                        case 'suggestlist':

                            $this->view->layoutType = 'suggestlist';
                            $this->view->layoutTypes = $this->view->renderPrint('viewer/widget/suggestlist', self::$dataViewPath);

                            break;
                        
                        case 'cardlist':

                            $this->view->layoutType = 'cardlist';
                            $this->view->layoutTypes = $this->view->renderPrint('viewer/widget/cardlist', self::$dataViewPath);

                            break;
                        
                        case 'news_widget':

                            $this->view->layoutType = 'newswidget';
                            $this->view->layoutTypes = $this->view->renderPrint('viewer/widget/news_widget', self::$dataViewPath);

                            break;
                        
                        case 'file_widget':

                            $this->view->layoutType = 'filewidget';
                            $this->view->layoutTypes = $this->view->renderPrint('viewer/widget/file_widget', self::$dataViewPath);

                            break;
                       
                        default:
                            
                            $this->view->layoutType = 'business';
                            $this->view->layoutTypes = $this->view->renderPrint('viewer/detail/layout/business', self::$dataViewPath);
                            
                            break;
                    }
                    
                } elseif (isset($this->view->row['dataViewLayoutTypes']['card_collaterial'])) {
                    
                    $this->view->layoutType = 'business_collaterial';
                    $this->view->layoutTypes = $this->view->renderPrint('viewer/detail/layout/business_collaterial', self::$dataViewPath);
                    
                    
                } elseif (isset($this->view->row['dataViewLayoutTypes']['card_collaterial_w'])) {
                    
                    $this->view->layoutType = 'business_collaterial';
                    $this->view->layoutTypes = $this->view->renderPrint('viewer/detail/layout/business_collaterial_widget', self::$dataViewPath);
                    
                } elseif (isset($this->view->row['dataViewLayoutTypes']['card_detail'])) {
                    
                    $this->view->layoutType = 'detail';
                    $this->view->layoutTypes = $this->view->renderPrint('viewer/detail/layout/detail', self::$dataViewPath);
                    
                } elseif (isset($this->view->row['dataViewLayoutTypes']['datalist'])) {
                    
                    $this->view->layoutType = 'datalist';
                    $this->view->layoutTheme = $this->view->row['dataViewLayoutTypes']['datalist']['LAYOUT_THEME'];
                    
                    if ($this->view->layoutTheme == 'htmlstyler') {
                        $this->view->layoutType = 'htmlstyler';
                    }
                    
                    $this->view->layoutTypes = $this->view->renderPrint('viewer/detail/layout/datalist/'.$this->view->layoutTheme, self::$dataViewPath);
                    
                } elseif (isset($this->view->row['dataViewLayoutTypes']['treeview'])) {
                    
                    $this->view->layoutType = 'treeview';
                    $this->view->layoutTheme = 'treeview_' . $this->view->row['dataViewLayoutTypes']['treeview']['LAYOUT_THEME'];
                    $this->view->layoutTypes = '';
                    
                } elseif (isset($this->view->row['dataViewLayoutTypes']['ecommerce_nofilter'])) {
                    
                    $this->view->layoutType = 'ecommerce_nofilter';
                    $this->view->layoutTheme = 'ecommerce_nofilter_' . $this->view->row['dataViewLayoutTypes']['ecommerce_nofilter']['LAYOUT_THEME'];
                    $this->view->layoutTypes = $this->view->renderPrint('viewer/detail/layout/ecommerce/' . $this->view->layoutTheme, self::$dataViewPath);
                    
                } elseif (isset($this->view->row['dataViewLayoutTypes']['ecommerce'])) {
                    $layoutThemeSt = $this->view->row['dataViewLayoutTypes']['ecommerce']['LAYOUT_THEME'];
                    if (issetParam($layoutThemeSt) !== '') {
                        $this->view->layoutType = 'ecommerce';
                        $this->view->layoutTheme = 'ecommerce_' . $layoutThemeSt;
                        
                        switch ($layoutThemeSt) {
                            case 'listwidget':
                                $this->view->layoutTypes = $this->view->renderPrint('viewer/widget/listwidget', self::$dataViewPath);
                                break;
                            case 'treewidget':
                                $this->view->layoutTypes = $this->view->renderPrint('viewer/widget/treewidget', self::$dataViewPath);
                                break;
                            case 'timeline_list':
                                $this->view->layoutTypes = $this->view->renderPrint('viewer/detail/layout/ecommerce/timeline_list', self::$dataViewPath);
                                break;
                            default:
                                $this->view->layoutTypes = $this->view->renderPrint('viewer/detail/layout/ecommerce/' . $this->view->layoutTheme, self::$dataViewPath);
                                break;
                        }
                    }
                }
            
            }
            $this->view->defaultCriteria = $this->view->renderPrint('search/defaultCriteria', self::$dataViewPath);
            $this->view->defaultCriteriaMandatory = $this->view->renderPrint('search/defaultCriteriaMandatory', self::$dataViewPath);
            $this->view->detailHeader = $this->view->renderPrint('viewer/detail/header', self::$dataViewPath);            
            $this->view->defaultViewer = $this->view->dataGridOptionData['DETAULTVIEWER'];
            
            $this->view->quickSearch = $this->view->renderPrint('search/quicksearch', self::$dataViewPath);
        }
        
        if ($isBasketView) {
            $this->view->subgrid = '';
        } else {
            $this->view->subgrid = $this->view->row['subgrid'];
        }
        
        $this->view->checklist = $this->view->row['checklist']; 
        $this->view->isDynamicHeight = '1';
        
        if (Input::postCheck('isDynamicHeight')) {
            $this->view->isDynamicHeight = Input::post('isDynamicHeight');
            $this->view->dataGridDefaultHeight = '';
        }
        
        $this->view->ignorePermission = Input::post('ignorePermission');
        $this->view->dvIgnoreToolbar = Input::post('dvIgnoreToolbar');
        $this->view->isBasketView = '0';
        
        if ($isBasket) {
            $this->view->isBasketView = $isBasketView;
            $viewPath = ($this->view->layoutType == 'ecommerce_basket' ? 'viewer/detail/itembasket' : 'viewer/detail/basket');
            $this->view->layoutType = '';
        }
        
        $this->view->advancedCriteria = '';
        
        if (issetParam($this->view->layoutType) == 'ecommerce' || issetParam($this->view->layoutType) == 'ecommerce_nofilter') {
            
            $this->view->getChildDataviewData = $this->model->getChildDataviewDataModel($this->view->metaDataId);
            $this->view->getIsCountCardData = $this->model->getDataViewCountCardModel($this->view->metaDataId);
            $this->view->ganttChartView = false;
            if (issetParam($this->view->row['dataViewLayoutTypes']['explorer']['LAYOUT_THEME']) == 'ganttchart') {
                $this->view->ganttChartView = true;
            }
            
            $advancedCriteria = ($this->view->dataViewHeaderData) ? Arr::multidimensional_list($this->view->dataViewHeaderData, array('IS_ADVANCED' => '1')) : array();
        
            includeLib('Compress/Compression');
            $this->view->advancedCriteria = Compression::encode_string_array(array('fillPath' => isset($this->view->fillPath) ? $this->view->fillPath : array(), 'advancedCriteria' => $advancedCriteria));
        
            if ($this->view->getChildDataviewData) {
                $this->view->getChildDataviewData = $this->model->getEcommerceCountModel($this->view->getChildDataviewData, $this->view->title, $this->view->metaDataId);
            }
            
            if (!empty($this->view->row['LAYOUT_META_DATA_ID'])) {
                $ml = &getInstance();
                $ml->load->model('mdmetadata', 'middleware/models/');
                $getType = $ml->model->getMetaTypeById($this->view->row['LAYOUT_META_DATA_ID']);

                if (Mdmetadata::$layoutMetaTypeId == $getType) {
                    $ml = &getInstance();
                    $ml->load->model('mdlayoutrender', 'middleware/models/');
                    $this->view->layoutLink = $ml->model->getLayoutLinkModel($this->view->row['LAYOUT_META_DATA_ID']);

                } elseif (Mdmetadata::$metaGroupMetaTypeId == $getType) {

                    $ml = &getInstance();
                    $ml->load->model('mdobject', 'middleware/models/');
                    $this->view->layoutLink = $ml->model->getDataViewConfigRowModel($this->view->row['LAYOUT_META_DATA_ID']);
                    $this->view->layoutLink['META_DATA_NAME'] = $this->lang->line($this->view->layoutLink['LIST_NAME']);
                }
            }
            
            $viewPath = (issetParam($this->view->layoutType) == 'ecommerce') ? 'viewer/detail/ecommerce' : 'viewer/detail/ecommerce_nofilter';
            
            if (issetParam($_POST['isSelectedBasket']) == '1') {
                $this->view->layoutTypes = ($this->view->defaultViewer === 'detail') ? '' : $this->view->layoutTypes ;
                $viewPath = 'viewer/detail/sub/ecommerce_basket';
            }
            
            $this->view->ecommerce_js = $this->view->renderPrint('viewer/detail/main/ecommerce_js', self::$dataViewPath);
            $this->view->ecommerce_css = $this->view->renderPrint('viewer/detail/main/ecommerce_css', self::$dataViewPath);            
        }
        
        if (!isset($this->view->srcDataViewId)) {
            $this->view->mainDvScripts = $this->view->renderPrint('viewer/detail/main/scripts', self::$dataViewPath);
        }
        
        if (issetParam($this->view->layoutType) == 'treeview') {
            
            $this->view->getChildDataviewData = $this->model->getChildDataviewDataModel($this->view->metaDataId);
            $this->view->getIsCountCardData = $this->model->getDataViewCountCardModel($this->view->metaDataId);

            $this->view->uniqId = getUID();
            $this->view->idField = $this->view->row['idField'];
            $this->view->nameField = $this->view->row['nameField'];
            
            $this->view->filterParams = $this->model->dataViewHeaderDataModel($this->view->metaDataId);
            $this->view->filter = $this->view->renderPrint('viewer/panel/filter', self::$dataViewPath);

            if ($this->view->getChildDataviewData) {
                $this->view->getChildDataviewData = $this->model->getEcommerceCountModel($this->view->getChildDataviewData, $this->view->title, $this->view->metaDataId);
            }
            
            if (!empty($this->view->row['LAYOUT_META_DATA_ID'])) {
                $ml = &getInstance();
                $ml->load->model('mdlayoutrender', 'middleware/models/');
                $this->view->layoutLink = $ml->model->getLayoutLinkModel($this->view->row['LAYOUT_META_DATA_ID']);
            }
            
            $viewPath = 'viewer/detail/treeview';
        }
        
        if ($dialogMode) {
            return $this->view->renderPrint($viewPath, self::$dataViewPath);
        } else {
            $this->view->render($viewPath, self::$dataViewPath);
        }
    }
    
    public function explorerDataViewer($metaDataId, $workSpaceId = null, $workSpaceParams = null, $uriParams = null, $hidden, $dialogMode = false, $permissionCriteria = null, $dataGridDefaultHeight = '', $calendarParams = null, $viewtype = '') {
        if (!isset($this->view)) {
            $this->view = new View();
        }
        
        $this->view->treeCategoryList = $this->view->filterFieldList = array();
        
        $this->view->isTree = $this->view->useBasket = $this->view->isPrint = $this->view->isDialog = $this->view->isRowColor = $this->view->isTextColor = $this->view->isLayout = false;
        $this->view->toolbar = $this->view->isCheckDataViewHeaderData = true;
        
        $this->view->name2 = $this->view->name3 = $this->view->name4 = $this->view->name5 = $this->view->name6 = $this->view->name7 = $this->view->groupName = $this->view->photoField = $this->view->iconField = '';
        $this->view->fillPath = $this->view->drillDownDefaultCriteria = '';
        
        $this->view->ajaxSync = Input::post('async', 'true');
        $this->view->hiddenFields = $hidden;
        $this->view->metaDataId = $metaDataId;
        $this->view->workSpaceId = $workSpaceId;
        $this->view->workSpaceParams = $workSpaceParams;
        $this->view->uriParams = $uriParams;
        $this->view->calendarParams = $calendarParams;
        $this->view->dataGridDefaultOption = Mdobject::gridDefaultOptions();
        $this->view->dataGridOptionData = $this->model->getDVGridOptionsModel($this->view->metaDataId);
        $this->view->dataGridDefaultHeight = $dataGridDefaultHeight;
        
        $this->view->row = $this->model->getDataViewConfigRowModel($this->view->metaDataId);
       
        $this->view->metaDataCode = $this->view->row['META_DATA_CODE'];
        $this->view->refStructureId = $this->view->row['REF_STRUCTURE_ID'];
        
        $this->view->isGridType = 'datagrid';
        $this->view->isTreeGridData = $this->view->row['TREE_GRID'];
        
        if ($this->view->isTreeGridData) {
            $this->view->isGridType = 'treegrid';
        }
        
        $this->view->dataViewWorkFlowBtn = ($this->view->row['COUNT_WFM_WORKFLOW'] != '0' && $this->view->row['IS_USE_WFM_CONFIG'] == '1') ? true : false;
        $this->view->dataViewProcessCommand = $this->model->dataViewProcessCommandModel($this->view->metaDataId, $this->view->metaDataCode, false);
        
        $this->view->permissionCriteria = $permissionCriteria;
        
        if ($this->view->permissionCriteria !== null) {
            $dataViewHeaderData = $this->model->dataViewHeaderDataUmCriteriaModel($this->view->metaDataId);
            if (trim($permissionCriteria) !== '_ERP_') {
                $this->view->permissionCriteriaData = Mdpermission::getCriteriaStringToArray($permissionCriteria);                
            }
        } else {
            $dataViewHeaderData = $this->model->dataViewHeaderDataModel($this->view->metaDataId); 
        }
        
        $this->view->dataViewMandatoryHeaderData = self::findMandatoryCriteria($this->view->metaDataId, $dataViewHeaderData);
        $this->view->dataViewHeaderData = self::findCriteria($this->view->metaDataId, $dataViewHeaderData);
        
        $this->view->dataViewCriteriaType = strtolower($this->view->row['SEARCH_TYPE'] == '0' ? 'BUTTON' : Info::getSearchType($this->view->row['SEARCH_TYPE']));
        
        $this->view->methodId = 1;
        $this->view->methodRow['META_DATA_NAME'] = $this->view->metaDataId;
        $this->view->isEmptyCriteria = empty($this->view->dataViewHeaderData['data']) && empty($this->view->dataViewHeaderData['dataGroup']['header']) ? false : true;
        $this->view->dvDefaultCriteria = Input::post('dvDefaultCriteria');
        
        $categoryList = $this->view->row['treeCategoryList'];
        
        if ($categoryList) {
            $this->view->isTree = true;
            $this->view->treeCategoryList = $categoryList['CATEGORY_LIST'];
            $this->view->filterFieldList = $categoryList['FILTER_FIELD'];
        }
        
        $this->view->dataViewClass = $this->model->getDataViewClassModel($this->view->hiddenFields, $this->view->dataViewCriteriaType, $this->view->isCheckDataViewHeaderData, $this->view->dataViewHeaderData, $this->view->isTree);
        $this->view->dvScripts = Mdexpression::searchGenerateScripts($this->view->metaDataId);
        
        self::getDrillDownDefaultCriteria();
        
        $this->view->defaultCriteria = $this->view->renderPrint('search/defaultCriteria', self::$dataViewPath);
        $this->view->defaultCriteriaMandatory = $this->view->renderPrint('search/defaultCriteriaMandatory', self::$dataViewPath);
        $this->view->detailHeader = $this->view->renderPrint('viewer/detail/header', self::$dataViewPath);
        
        $this->view->defaultImage = 'assets/core/global/img/metaicon/big/'.$this->view->row['dataViewLayoutTypes']['explorer']['DEFAULT_IMAGE'];
        
        self::explorerViewDefaultValue();
        
        $this->view->layoutTheme = 'explorer1';
        
        if (isset($this->view->row['dataViewLayoutTypes']['explorer']['LAYOUT_THEME']) && $this->view->row['dataViewLayoutTypes']['explorer']['LAYOUT_THEME'] !== '') {
            $this->view->layoutTheme = strtolower($this->view->row['dataViewLayoutTypes']['explorer']['LAYOUT_THEME']);
        }

        $viewPath = 'viewer/explorer/index';
        
        $this->view->contextSelector = 'div#object-value-list-'. $this->view->metaDataId .' div.product-item';
        $this->view->contextSelector = self::explorerViewContextSelector($this->view->layoutTheme, $this->view->metaDataId, $this->view->contextSelector);
        
        $this->view->clickRowFunction = $this->model->renderClickRowFunction($this->view->metaDataId, $this->view->row['dataViewLayoutTypes']);
        
        $this->view->dvIgnoreToolbar = Input::post('dvIgnoreToolbar');
        $this->view->mainDvScripts = $this->view->renderPrint('viewer/explorer/main/scripts', self::$dataViewPath);
        
        if ($dialogMode) {
            return $this->view->renderPrint($viewPath, self::$dataViewPath);
        } else {
            $this->view->render($viewPath, self::$dataViewPath);
        }
    }   
    
    public function calendarDataViewer($metaDataId, $workSpaceId = null, $workSpaceParams = null, $uriParams = null, $hidden, $dialogMode = false, $permissionCriteria = null, $dataGridDefaultHeight = '', $calendarParams = null) {
        if (!isset($this->view)) {
            $this->view = new View();
        }
        
        $viewPath = 'viewer/calendar/fullcalendar/';
        $this->view->isTree = $this->view->useBasket = $this->view->isPrint = $this->view->isDialog = $this->view->isRowColor = $this->view->isTextColor = $this->view->isLayout = false;
        $this->view->fillPath = $this->view->drillDownDefaultCriteria = '';
        
        $this->view->hiddenFields = $hidden;
        $this->view->metaDataId = $metaDataId;
        $this->view->mid = $metaDataId;
        $this->view->workSpaceId = $workSpaceId;
        $this->view->workSpaceParams = $workSpaceParams;
        $this->view->uriParams = $uriParams;
        $this->view->dataGridOptionData = $this->model->getDVGridOptionsModel($this->view->metaDataId);
        
        $this->view->row = $this->model->getDataViewConfigRowModel($this->view->metaDataId);
        
        unset($this->view->row['dataViewLayoutTypes']['calendar']['fields']['__linkid']);
       
        $this->view->metaDataCode = $this->view->row['META_DATA_CODE'];
        $this->view->refStructureId = $this->view->row['REF_STRUCTURE_ID'];
        $this->view->layoutTheme = $this->view->row['dataViewLayoutTypes']['calendar']['LAYOUT_THEME'];
        
        $this->view->dataViewWorkFlowBtn = ($this->view->row['COUNT_WFM_WORKFLOW'] != '0' && $this->view->row['IS_USE_WFM_CONFIG'] == '1') ? true : false;
        $this->view->dataViewProcessCommand = $this->model->dataViewProcessCommandModel($this->view->metaDataId, $this->view->metaDataCode, false);
        
        $this->view->permissionCriteria = $permissionCriteria;
        
        if ($this->view->permissionCriteria !== null) {
            $dataViewHeaderData = $this->model->dataViewHeaderDataUmCriteriaModel($this->view->metaDataId);
            if (trim($permissionCriteria) !== '_ERP_') {
                $this->view->permissionCriteriaData = Mdpermission::getCriteriaStringToArray($permissionCriteria);                
            }
        } else {
            $dataViewHeaderData = $this->model->dataViewHeaderDataModel($this->view->metaDataId); 
        }
        
        $this->view->dataViewMandatoryHeaderData = self::findMandatoryCriteria($this->view->metaDataId, $dataViewHeaderData);
        $this->view->dataViewHeaderData = self::findCriteria($this->view->metaDataId, $dataViewHeaderData);
        
        $this->view->methodId = 1;
        $this->view->methodRow['META_DATA_NAME'] = $this->view->metaDataId;
        $this->view->isEmptyCriteria = true;
        
        if ($this->view->dataViewMandatoryHeaderData || (isset($this->view->dataViewHeaderData['data']) && !empty($this->view->dataViewHeaderData['data']))) {
            if (!isset($this->view->callerType)) {
                $this->view->callerType = Input::post('callerType');
            }
            $this->view->isEmptyCriteria = false;
            $this->view->searchForm = $this->view->renderPrint($viewPath . 'searchForm', self::$dataViewPath);
        }
        
        self::getDrillDownDefaultCriteria();
        
        $this->view->calendarScripts = $this->view->renderPrint($viewPath . 'scripts', self::$dataViewPath);
        
        if ($dialogMode) {
            return $this->view->renderPrint($viewPath . 'index', self::$dataViewPath);
        } else {
            $this->view->render($viewPath . 'index', self::$dataViewPath);
        }
    }   
    
    public function dataViewConfigRow($hidden = '0', $dialogMode = false) {        
        
        $metaDataId = Input::numeric('metaDataId');
        $workSpaceId = Input::numeric('workSpaceId');
        $workSpaceParams = Input::post('workSpaceParams');
        $uriParams = Input::post('uriParams');
        $permissionCriteria = Input::post('permissionCriteria');
        $dataGridDefaultHeight = Input::post('dataGridDefaultHeight');
        $calendarParams = Input::post('calendarParams');
        $this->view->viewType = strtolower(Input::post('viewType'));        
        
        $row = $this->model->getDataViewConfigRowModel($metaDataId);
        $this->view->viewType = isset($row['dataViewLayoutTypes']['explorer']) ? 'explorer' : 'detail';
        
        if ($this->view->viewType == 'explorer') {
            $content = self::explorerDataViewer($metaDataId, $workSpaceId, $workSpaceParams, $uriParams, 0, true, $permissionCriteria, $dataGridDefaultHeight, $calendarParams);
        } else {
            $content = self::detailDataViewer($metaDataId, $workSpaceId, $workSpaceParams, $uriParams, 0, true, $permissionCriteria, $dataGridDefaultHeight, $calendarParams);
        }

        convJson(['row' => ['title' => Lang::line($row['LIST_NAME'])], 'html' => $content]);
    }
    
    public function explorerViewDefaultValue() {
        
        $this->view->isCardSee = ($this->view->row['COUNT_CARD'] != '0') ? true : false;
        $this->view->isCalendarSee = ($this->view->row['COUNT_CALENDAR_SEE'] != '0') ? true : false;
        $this->view->calendarMetaDataId = $this->view->row['CALENDAR_META_DATA_ID'];
        $this->view->buttonBarStyle = $this->view->row['BUTTON_BAR_STYLE'];
        $this->view->refreshTimer = $this->view->row['REFRESH_TIMER'];
        
        if ($this->view->row['COUNT_REPORT_TEMPLATE'] != '0') {
            $this->view->isPrint = true;
        }
        
        if ($this->view->row['COUNT_ROWCOLOR'] != '0') {
            $this->view->isRowColor = true;
        }
        
        if ($this->view->row['COUNT_TEXTCOLOR'] != '0') {
            $this->view->isTextColor = true;
        }
        
        $this->view->name1 = isset($this->view->row['dataViewLayoutTypes']['explorer']['fields']['name1']) ? strtolower($this->view->row['dataViewLayoutTypes']['explorer']['fields']['name1']) : '';
        
        if (isset($this->view->row['dataViewLayoutTypes']['explorer']['fields']['photo']) && $this->view->row['dataViewLayoutTypes']['explorer']['fields']['photo'] != '') {
            $this->view->photoField = strtolower($this->view->row['dataViewLayoutTypes']['explorer']['fields']['photo']);
        }
        
        if (isset($this->view->row['dataViewLayoutTypes']['explorer']['fields']['icon']) && $this->view->row['dataViewLayoutTypes']['explorer']['fields']['icon'] != '') {
            $this->view->iconField = strtolower($this->view->row['dataViewLayoutTypes']['explorer']['fields']['icon']);
        }
        
        if (isset($this->view->row['dataViewLayoutTypes']['explorer']['fields']['name2']) && $this->view->row['dataViewLayoutTypes']['explorer']['fields']['name2'] != '') {
            $this->view->name2 = strtolower($this->view->row['dataViewLayoutTypes']['explorer']['fields']['name2']);
        }
        
        if (isset($this->view->row['dataViewLayoutTypes']['explorer']['fields']['name3']) && $this->view->row['dataViewLayoutTypes']['explorer']['fields']['name3'] != '') {
            $this->view->name3 = strtolower($this->view->row['dataViewLayoutTypes']['explorer']['fields']['name3']);
        }
        
        if (isset($this->view->row['dataViewLayoutTypes']['explorer']['fields']['name4']) && $this->view->row['dataViewLayoutTypes']['explorer']['fields']['name4'] != '') {
            $this->view->name4 = strtolower($this->view->row['dataViewLayoutTypes']['explorer']['fields']['name4']);
        }
        
        if (isset($this->view->row['dataViewLayoutTypes']['explorer']['fields']['name5']) && $this->view->row['dataViewLayoutTypes']['explorer']['fields']['name5'] != '') {
            $this->view->name5 = strtolower($this->view->row['dataViewLayoutTypes']['explorer']['fields']['name5']);
        }
        
        if (isset($this->view->row['dataViewLayoutTypes']['explorer']['fields']['name6']) && $this->view->row['dataViewLayoutTypes']['explorer']['fields']['name6'] != '') {
            $this->view->name6 = strtolower($this->view->row['dataViewLayoutTypes']['explorer']['fields']['name6']);
        }
        
        if (isset($this->view->row['dataViewLayoutTypes']['explorer']['fields']['name7']) && $this->view->row['dataViewLayoutTypes']['explorer']['fields']['name7'] != '') {
            $this->view->name7 = strtolower($this->view->row['dataViewLayoutTypes']['explorer']['fields']['name7']);
        }
        if (isset($this->view->row['dataViewLayoutTypes']['explorer']['fields']['name8']) && $this->view->row['dataViewLayoutTypes']['explorer']['fields']['name8'] != '') {
            $this->view->name8 = strtolower($this->view->row['dataViewLayoutTypes']['explorer']['fields']['name8']);
        }
        if (isset($this->view->row['dataViewLayoutTypes']['explorer']['fields']['name9']) && $this->view->row['dataViewLayoutTypes']['explorer']['fields']['name9'] != '') {
            $this->view->name9 = strtolower($this->view->row['dataViewLayoutTypes']['explorer']['fields']['name9']);
        }
        if (isset($this->view->row['dataViewLayoutTypes']['explorer']['fields']['name10']) && $this->view->row['dataViewLayoutTypes']['explorer']['fields']['name10'] != '') {
            $this->view->name10 = strtolower($this->view->row['dataViewLayoutTypes']['explorer']['fields']['name10']);
        }
        if (isset($this->view->row['dataViewLayoutTypes']['explorer']['fields']['body']) && $this->view->row['dataViewLayoutTypes']['explorer']['fields']['body'] != '') {
            $this->view->body = strtolower($this->view->row['dataViewLayoutTypes']['explorer']['fields']['body']);
        }
        
        if (isset($this->view->row['dataViewLayoutTypes']['explorer']['fields']['groupName']) && $this->view->row['dataViewLayoutTypes']['explorer']['fields']['groupName'] != '') {
            $this->view->groupName = strtolower($this->view->row['dataViewLayoutTypes']['explorer']['fields']['groupName']);
        }
    }
    
    public function getDrillDownDefaultCriteria() {
        
        if (Input::isEmpty('drillDownDefaultCriteria') === false) {
            
            $drillDownDefaultCriteria = Input::postNonTags('drillDownDefaultCriteria');
            
            if (Json::isJson($drillDownDefaultCriteria)) {
                $postParam = json_decode($drillDownDefaultCriteria, true);
            } else {
                parse_str(Input::post('drillDownDefaultCriteria'), $postParam);
            }
 
            $addonJsonParam = isset($_POST['addonJsonParam']) ? json_decode($_POST['addonJsonParam'], true) : array();
            $defaultCriteriaParams = isset($_POST['defaultCriteriaParams']) ? json_decode($_POST['defaultCriteriaParams'], true) : array();

            foreach ($postParam as $pp => $pv) {
                if ($pv == 'undefined' || $pv == 'null') {
                    if ($addonJsonParam) {
                        foreach ($addonJsonParam as $ap => $av) {
                            if ($av !== '')
                                $postParam[strtolower($ap)] = $av;
                            else
                                $postParam[strtolower($pp)] = '';
                        }
                    } else {
                        $postParam[strtolower($pp)] = '';
                    }
                }
            }
            
            if ($defaultCriteriaParams) {
                foreach ($defaultCriteriaParams as $ap => $av) {
                    if (!isset($postParam[strtolower($ap)]) || $postParam[strtolower($ap)] == '') {
                        $postParam[strtolower($ap)] = $av;
                    } 
                }
            }

            $this->view->fillPath = $postParam;
            $this->view->drillDownDefaultCriteria = json_encode($postParam);
        }
    }
    
    public function explorerViewContextSelector($layoutTheme, $metaDataId, $contextSelector) {
        switch ($layoutTheme) {
            case 'explorer1':
            case 'explorer2':
            case 'explorer3':
            case 'explorer4':
            case 'explorer5':
            case 'explorer10':
            case 'explorer11':
            case 'explorer12':
                $contextSelector = 'div#object-value-list-'.$metaDataId.' ul.dv-explorer li.dv-explorer-row';
                break;
            case 'carousel1':
                $contextSelector = 'div#object-value-list-'.$metaDataId.' div.pf-carousel-1 div.product-item';
                break;
            case 'carousel2':
                $contextSelector = 'div#object-value-list-'.$metaDataId.' div.pf-carousel-2 div.product-item';
                break;
            case 'carousel3':
                $contextSelector = 'div#object-value-list-'.$metaDataId.' div.pf-carousel-3 div.product-item';
                break;
            case 'carousel-mini1':
                $contextSelector = 'div#object-value-list-'.$metaDataId.' div.carousel-mini-1 div.mini-list';
                break;
            case 'orgchart':
                $contextSelector = 'div#object-value-list-'.$metaDataId.' div.orgchart div.node';
                break;
            case 'orgchartmultiparent':
                $contextSelector = 'div#object-value-list-'.$metaDataId.' div.orgchartmultiparent div.node';
                break;
            case 'card1':
                $contextSelector = 'div#object-value-list-'.$metaDataId.' div.board-card div.list-card';
                break;
            case 'widget1':
                $contextSelector = 'div#object-value-list-'.$metaDataId.' div.pf-widget1 div.pf-widget1-table-row';
                break;
            case 'socialview':
                $contextSelector = 'div#object-value-list-'.$metaDataId.' div.board-socialview div.socialview-row';
                break;
            case 'commentview':
                $contextSelector = 'div#object-value-list-'.$metaDataId.' div.board-commentview div.commentview-row';
                break;
            case 'hr_comment_view':
                $contextSelector = 'div#object-value-list-'.$metaDataId.' div.board-commentview div.commentview-row';
                break;
            case 'hr_worktask_view':
                $contextSelector = 'div#object-value-list-'.$metaDataId.' div.board-commentview div.work_view-row';
                break;
            case 'feedback_comment_view':
                $contextSelector = 'div#object-value-list-'.$metaDataId.' div.board-commentview div.feedback_comment_view';
                break;
            case 'gallery1':
                $contextSelector = 'div#object-value-list-'.$metaDataId.' div.gallery1-dv li.gallery-item';
                break;
            case 'gallery2':
                $contextSelector = 'div#object-value-list-'.$metaDataId.' div.gallery2-dv li.gallery-item';
                break;
            case 'gallery3':
            $contextSelector = 'div#object-value-list-'.$metaDataId.' div.gallery3-dv li.gallery-item';
                break;
            case 'gallery4':
            $contextSelector = 'div#object-value-list-'.$metaDataId.' div.gallery4-dv li.gallery-item';
                break;
            case 'timeline':
                $contextSelector = 'div#object-value-list-'.$metaDataId.' div.timeline-item';
                break;
            case 'lifecycle_card':
                $contextSelector = 'div#object-value-list-'.$metaDataId.' div.dv-selection-item';
                break;
        }
        
        return $contextSelector;
    }
    
    public function dataViewDataGrid($pagination = true, $isJson = true, $metaDataId = null, $criteria = array()) {
        
        if (!is_ajax_request()) {
            Message::add('i', '', URL . 'appmenu');
        }
        
        $this->load->model('mdobject', 'middleware/models/');
        $result = $this->model->dataViewDataGridModel((Input::param($pagination) == 'false' ? false : true), $metaDataId, $criteria);
        
        if ($isJson) {
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
            return $result;
        }
    }
    
    public function dataViewAggregateData() {
        
        if (!is_ajax_request()) {
            Message::add('i', '', URL . 'appmenu');
        }
        
        if (Input::numeric('pi') == 1) {
            Mddatamodel::$ignorePermission = true;
        }
        
        $_POST['pagingWithoutAggregate'] = 2;
        $result = $this->model->dataViewDataGridModel(true, null, array());
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    
    public function getDataViewRows($dvId) {
        
        if (!is_ajax_request()) {
            Message::add('i', '', URL . 'appmenu');
        }
        
        $result = $this->model->dataViewDataGridModel(true, null, array());
        
        header('Content-Type: application/json');
        echo json_encode($result); exit;
    }
    
    public function getDataViewMergeRows($pagination = true, $isJson = true, $metaDataId = null, $criteria = array()) {
        
        if (!is_ajax_request()) {
            Message::add('i', '', URL . 'appmenu');
        }
        
        $this->load->model('mdobject', 'middleware/models/');
        $result = $this->model->dataViewDataGridModel((Input::param($pagination) == 'false' ? false : true), $metaDataId, $criteria);
        
        if (issetParam($result['status']) == 'success') {
            $configRow = $this->model->getDataViewConfigRowModel(Input::numeric('metaDataId'));
            $mergeRowsDvId = issetParam($configRow['dataViewLayoutTypes']['calendar']['fields']['mergeRowsDvId']);
            
            if ($mergeRowsDvId) {
                $_POST['metaDataId'] = $mergeRowsDvId;
                $mergeResult = $this->model->dataViewDataGridModel((Input::param($pagination) == 'false' ? false : true));

                if (issetParam($mergeResult['status']) == 'success' && count($mergeResult['rows'])) {
                    $result['rows'] = array_merge($mergeResult['rows'], $result['rows']);
                }
            }
        }
        
        if ($isJson) {
            jsonResponse($result);
        } else {
            return $result;
        }
    }
    
    public function googleMapDataGrid() {
        $result = $this->model->googleMapDataGridModel();
        echo json_encode($result, JSON_UNESCAPED_UNICODE); 
    }
    
    public function dataview($metaDataId = '', $hidden = false, $dataType = '', $sync = true) {
        
        if ($metaDataId == '') {
            
            Message::add('e', '', 'back');
            
        } else {
            
            if (!is_numeric($metaDataId)) {
                
                set_status_header(404);
        
                $err = Controller::loadController('Err');
                $err->index();
                exit;
            }
        }
        
        $this->load->model('mdobject', 'middleware/models/');
        
        if (!isset($this->view)) {
            $this->view = new View();
        }
        
        $this->view->isDrilldown = false;
        
        if (Input::postCheck('metaDataId') && Input::isEmpty('drillDownDefaultCriteria') === false) {
            $drillDownMetaData = $this->model->getDrillDownMetaDataModel();

            if ($drillDownMetaData) {
                
                if (Input::post('isDrillMainMetaDataId') === '1') { 
                    
                    if (strpos($drillDownMetaData[0]['LINK_META_DATA_ID'], ',') !== false) {
                        
                        $metaDataIds = explode(',', $drillDownMetaData[0]['LINK_META_DATA_ID']);
                        $criterias = explode(',', $drillDownMetaData[0]['CRITERIA']);
                        $selectedRow = $_POST['rowData'];
                        
                        foreach ($metaDataIds as $mk => $metaId) {
                            $rules = Str::lower(trim($criterias[$mk]));
                            
                            if (isset($selectedRow['pfnextstatuscolumn'])) {
                                unset($selectedRow['pfnextstatuscolumn']);
                            }
                    
                            foreach ($selectedRow as $sk => $sv) {
                                if (is_string($sv) && strpos($sv, "'") === false) {
                                    $sv = "'".Str::lower($sv)."'";
                                } elseif (is_null($sv)) {
                                    $sv = "''";
                                }
                                
                                $sk = ($sk == '' ? 'tmpkey' : $sk);

                                $rules = preg_replace('/\b'.$sk.'\b/u', $sv, $rules);
                            }
                            
                            if (trim($rules) != '' && eval(sprintf('return (%s);', $rules))) {
                                $metaDataId = $metaId;
                                break;
                            }
                        }
                        
                    } else {
                        $metaDataId = $drillDownMetaData[0]['LINK_META_DATA_ID'];
                    }
                }
                
                $this->view->isDrilldown = true;
            }
        }
        
        $this->view->metaDataId = Input::param($metaDataId);
        
        $this->view->row = $this->model->getDataViewConfigRowModel($this->view->metaDataId);
        
        if (!isset($this->view->row['LIST_NAME'])) {
            Message::add('e', '', 'back');
        }
        
        $this->view->title = $this->lang->line($this->view->row['LIST_NAME']);
        $this->view->description = $this->lang->line(issetParam($this->view->row['DESCRIPTION']));
        $this->view->isAjax = Input::post('isAjax') ? true : is_ajax_request();
        
        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        $this->view->fullUrlJs = AssetNew::amChartJs();
        
        $this->view->metaDataCode = $this->view->row['META_DATA_CODE'];
        $this->view->folderId = '';
        $this->view->metaBackLink = '';
        
        if (isset($this->view->row['FOLDER_ID'])) {
            $this->view->folderId = $this->view->row['FOLDER_ID'];
            $this->view->metaBackLink = 'mdmetadata/system#objectType=folder&objectId=' . $this->view->row['FOLDER_ID'];
        }
        
        $this->view->gridOption = $this->model->getDVGridOptionsModel($this->view->metaDataId);
        $this->view->drillDownDefaultCriteria = Input::post('drillDownDefaultCriteria');
        
        if (isset($this->view->row['PANEL_TYPE'])) {
            self::dataViewPanelRender($dataType);
        }        

        $this->view->isBackLink = false;
        
        if (Input::postCheck('backTargetLink')) {
            $this->view->isBackLink = true;
            $this->view->backTargetLink = Input::post('backTargetLink');
        }

        $this->view->isDashboard = ($this->view->row['COUNT_DASHBOARD_LINK'] != '0') ? true : false;
        $this->view->isReportTemplate = (isset($this->view->row['C_REPORT_TEMPLATE']) && $this->view->row['C_REPORT_TEMPLATE'] != '0' && $this->view->row['IS_USE_RT_CONFIG'] == '1') ? true : false;
        
        $this->view->objectValueViewType = 'detail';
        
        if ($this->view->gridOption['DETAULTVIEWER'] == 'gmap') {
            
            $this->view->objectValueViewType = 'gmap';
            
        } elseif (isset($this->view->row['dataViewLayoutTypes'])) {
            
            if (isset($this->view->row['dataViewLayoutTypes']['explorer']) && 
                (
                    $this->view->gridOption['DETAULTVIEWER'] == '' 
                    || $this->view->gridOption['DETAULTVIEWER'] == 'explorer' 
                    || ($this->view->gridOption['DETAULTVIEWER'] != 'detail' && $this->view->gridOption['DETAULTVIEWER'] != 'explorer')
                )) {
                
                $this->view->objectValueViewType = $this->view->gridOption['DETAULTVIEWER'] == 'calendar' ? $this->view->gridOption['DETAULTVIEWER'] : 'explorer';
                
            } elseif (isset($this->view->row['dataViewLayoutTypes']['calendar']) && $this->view->gridOption['DETAULTVIEWER'] == 'calendar') {
                
                $this->view->objectValueViewType = 'calendar';
            }
        }
        
        $this->view->workSpaceId = null;
        $this->view->workSpaceParams = null;
        $this->view->hiddenFields = issetParam($hidden);
        
        if (Input::postCheck('workSpaceId')) {
            $this->view->workSpaceId = Input::numeric('workSpaceId');
            $this->view->workSpaceParams = Input::post('workSpaceParams');
        }
        
        $this->view->permissionCriteria = Input::post('permissionCriteria');
        $this->view->dvDefaultCriteria = Input::post('dvDefaultCriteria');
        $this->view->uriParams = null;
        
        if (Input::postCheck('uriParams')) {
            $this->view->uriParams = Input::post('uriParams');
        }
        
        if ($kpiIndicatorMapConfig = Input::post('kpiIndicatorMapConfig')) {
            $this->view->kpiIndicatorMapConfig = json_encode($kpiIndicatorMapConfig, JSON_UNESCAPED_UNICODE);
        }
        
        if (Input::postCheck('params')) {
            $requestParams = urldecode(Input::post('params'));
            parse_str($requestParams, $params);
            
            if (is_array($params)) {
                unset($params['autoSearch']);
                $this->view->uriParams = json_encode($params);
            }
        }
        
        if (isset($_GET['dv'])) {
            $this->view->uriParams = json_encode($_GET['dv'], JSON_UNESCAPED_UNICODE);
        }
        
        $this->view->calendarParams = Input::get('startParamPath');
        $this->view->dataGridDefaultHeight = Input::post('dataGridDefaultHeight');
        $this->view->isDynamicHeight = Input::post('isDynamicHeight', '1');
        $this->view->isNeedTitle = Input::post('isNeedTitle', '0');
        $this->view->isSelectedBasket = Input::post('isSelectedBasket', '0');
        $this->view->chooseTypeBasket = Input::post('chooseType', '0');
        
        $this->view->selectedRowData = '0';
        if (Input::postCheck('selectedRowData') || Input::postCheck('selectedRows')) {
            $this->view->selectedRowData = Input::postCheck('selectedRowData') ? Input::post('selectedRowData') : Input::post('selectedRows');
        }
        
        if ($this->view->title == '&nbsp;' || $this->view->title == '' 
            || $this->view->title == ' ' || $this->view->title == ' ' || $this->view->title == ' ') {
            $this->view->isEmptyTitle = true;
        } 
        
        $this->view->needTitle = Input::post('needTitle', '1');
        $this->view->ajaxSync = $sync;
        $this->view->callerType = Input::post('callerType');
        
        if (Input::postCheck('workSpaceId') || Input::post('isIgnoreTitle') == '1' || $this->view->callerType == 'package') {
            $this->view->needTitle = '0';
            $this->view->packageRenderType = Input::post('packageRenderType');
        } 
        
        $this->renderDataValueViewer();
        
        if ($dataType == 'json') {
            
            echo json_encode(array(
                'Title'      => $this->view->title, 
                'Html'       => $this->view->renderPrint('index', self::$dataViewPath),
                'metaDataId' => $metaDataId,
                'save_btn'   => $this->lang->line('save_btn'),
                'close_btn'  => $this->lang->line('close_btn'),
                'Width' => (issetParam($this->view->row['WINDOW_SIZE']) === 'custom' && issetParam($this->view->row['WINDOW_WIDTH'])) ? $this->view->row['WINDOW_WIDTH'] : '1200',
                'Height' => (issetParam($this->view->row['WINDOW_SIZE']) === 'custom' && issetParam($this->view->row['WINDOW_HEIGHT'])) ? $this->view->row['WINDOW_HEIGHT'] : 'auto',
            )); exit;
            
        } elseif ($dataType == 'array') {
            
            return array(
                'Title'      => $this->view->title, 
                'Html'       => $this->view->renderPrint('index', self::$dataViewPath),
                'metaDataId' => $metaDataId,
                'save_btn'   => $this->lang->line('save_btn'),
                'close_btn'  => $this->lang->line('close_btn')
            );
            
        } else {
            
            if ($this->view->isAjax == false) {
                $this->view->render('header');
            } 
            
            $this->view->render('index', self::$dataViewPath);

            if ($this->view->isAjax == false) {
                $this->view->render('footer');
            }
        } 
    }
    
    public function renderDataValueViewer() {
        
        $postArray = array();
        
        if (Input::post('ignorePermission') == 1) {
            $postArray['ignorePermission'] = 1;
        }
        if (Input::post('dvIgnoreToolbar') == 1) {
            $postArray['dvIgnoreToolbar'] = 1;
        } 
        if (Input::isEmpty('proxyId') == false) {
            $postArray['proxyId'] = Input::numeric('proxyId');
        }
        if (Input::isEmpty('runSrcMetaId') == false) {
            $postArray['runSrcMetaId'] = Input::numeric('runSrcMetaId');
        }
        
        unset($_POST);
        
        $_POST['metaDataId'] = $this->view->metaDataId;
        $_POST['viewType'] = $this->view->objectValueViewType;
        
        if ($this->view->uriParams) {
            $_POST['uriParams'] = $this->view->uriParams;
        }
        if (isset($this->view->calendarParams) && $this->view->calendarParams) {
            $_POST['calendarParams'] = $this->view->calendarParams;
        }
        if ($this->view->workSpaceId) {
            $_POST['workSpaceId'] = $this->view->workSpaceId;
            $_POST['workSpaceParams'] = htmlentities($this->view->workSpaceParams, ENT_QUOTES, 'utf-8');
        }
        if ($this->view->permissionCriteria != '') {
            $_POST['permissionCriteria'] = $this->view->permissionCriteria;
        }
        if ($this->view->dvDefaultCriteria != '') {
            $_POST['dvDefaultCriteria'] = $this->view->dvDefaultCriteria;
        }
        if ($this->view->dataGridDefaultHeight != '') {
            $_POST['dataGridDefaultHeight'] = $this->view->dataGridDefaultHeight;
        }
        if ($this->view->drillDownDefaultCriteria != '') {
            $_POST['drillDownDefaultCriteria'] = $this->view->drillDownDefaultCriteria;
        }
        if (isset($this->view->isDynamicHeight) && $this->view->isDynamicHeight == '0') {
            $_POST['isDynamicHeight'] = $this->view->isDynamicHeight;
        }
        if (isset($this->view->ajaxSync)) {
            $_POST['async'] = $this->view->ajaxSync;
        }
        if (isset($this->view->isSelectedBasket)) {
            $_POST['isSelectedBasket'] = $this->view->isSelectedBasket;
        }
        if (isset($this->view->selectedRowData)) {
            $_POST['selectedRowData'] = $this->view->selectedRowData;
        }
        if (isset($this->view->chooseTypeBasket)) {
            $_POST['chooseTypeBasket'] = $this->view->chooseTypeBasket;
        }
        
        if ($postArray) {
            $_POST = array_merge($_POST, $postArray);
        }
        
        $this->view->dataValueViewer = $this->dataValueViewer($this->view->hiddenFields, true);
    }

    public function getDataModelHeaderData($dataModelId, $sourceId) {
        $this->load->model('mdobject', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $this->view->headerRow = $this->model->getDataModelHeaderDataModel($dataModelId, $sourceId);

        return $this->view->renderPrint('viewer/header/index', self::$dataViewPath);
    }

    public static function getFeatureCellIndex($headerRow, $cellIndex) {
        $row = Arr::multidimensional_search($headerRow['featureRowNum'], array('FEATURE_NUM' => $cellIndex));
        
        if ($row) {
            $value = '';
            if (isset($headerRow['rowData'][$row['FIELD_PATH']])) {
                $value = $headerRow['rowData'][$row['FIELD_PATH']];
            }
            return array('labelName' => $row['META_DATA_NAME'], 'value' => (new Mdmeta())->formatterValue($row['META_TYPE_CODE'], $value));
        }
        return false;
    }

    public function transferProcessAction() {

        $mainMetaDataId    = Input::post('mainMetaDataId');
        $processMetaDataId = Input::post('processMetaDataId');
        $metaTypeId        = Input::post('metaTypeId');
        $selectedRow       = Input::post('selectedRow');
        $advancedCriteria  = Input::post('advancedCriteria');
        $result            = array();

        if (empty($advancedCriteria)) {
            $result = $this->model->checkProcessActionModel($mainMetaDataId, $processMetaDataId, $metaTypeId, $selectedRow, true, true);
        } else {
            $result = $this->model->checkProcessAdvancedCriteriaModel($mainMetaDataId, $processMetaDataId, $metaTypeId, $selectedRow, $advancedCriteria);
        }

        echo json_encode($result); exit;
    }

    public function transferLifeCycleAction() {

        $mainMetaDataId = Input::post('mainMetaDataId');
        $selectedRow = Input::post('selectedrow');

        $result = $this->model->checkLifeCycleActionModel($mainMetaDataId, $selectedRow);
        echo json_encode($result); exit;
    }

    public function isConfirmProcess() {

        $mainMetaDataId = Input::post('mainMetaDataId');
        $processMetaDataId = Input::post('processMetaDataId');

        $message = $this->model->getDvProcessInfoModel($mainMetaDataId, $processMetaDataId);

        $response = array(
            'title' => Lang::line('msg_title_confirm'),
            'message' => $message,
            'yes_btn' => Lang::line('yes_btn'),
            'no_btn' => Lang::line('no_btn')
        );
        echo json_encode($response); exit;
    }

    public function dataViewSearchFilterForm() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->getIsCountCardData = $this->model->getDataViewCountCardModel($this->view->metaDataId);

        $this->view->render('search/countcard', self::$dataViewPath);
    }

    public function renderCountCardByFieldPath($metaDataId, $fieldPath, $dataType, $theme, $selection, $jsonConfig = '') {
        $this->load->model('mdobject', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $this->view->metaDataId       = $metaDataId;
        $this->view->fieldPath        = strtolower($fieldPath);
        $this->view->getTypeCode      = $dataType;
        $this->view->theme            = $theme;
        $this->view->selection        = $selection;
        $this->view->jsonConfig        = $jsonConfig;
        $this->view->getCountCardData = $this->model->getCountCardDataModel($metaDataId, $fieldPath, $jsonConfig);

        return $this->view->renderPrint('search/cardview', self::$dataViewPath);
    }

    public function renderCountCardByPost() {

        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->fieldPath = strtolower(Input::post('fieldPath'));
        $this->view->getCountCardData = $this->model->getCountCardDataModel($this->view->metaDataId, $this->view->fieldPath);
        $this->view->getTypeCode = Input::post('fieldType');
        $this->view->theme = Input::post('cardTheme');
        $this->view->selection = Input::post('cardSelection');

        $this->view->render('search/cardview', self::$dataViewPath);
    }

    public function renderDataViewSearchForm() {

        $metaDataId = Input::numeric('metaDataId');

        $this->view->dataGridHeadData = $this->model->getDataViewGridHeaderModel($metaDataId);
        $this->view->render('search/dataGridSearch', self::$dataViewPath);
    }

    public function runConfirmProcess() {

        $postData = Input::postData();
        $mainMetaDataId = $postData['mainMetaDataId'];
        $processMetaDataId = $postData['processMetaDataId'];
        $selectedRows = $postData['selectedRows'];

        $result = $this->model->runConfirmLoopProcessModel($mainMetaDataId, $processMetaDataId, $selectedRows);
        echo json_encode($result);
    }

    public function runConfirmOneLoopProcess() {

        $postData = Input::postData();
        $mainMetaDataId = Input::numeric('mainMetaDataId');
        $processMetaDataId = Input::numeric('processMetaDataId');
        $selectedRow = $postData['selectedRow'];

        $result = $this->model->runConfirmOneLoopProcessModel($mainMetaDataId, $processMetaDataId, $selectedRow);
        echo json_encode($result); 
    }

    public function setDataModelGridOption() {

        $this->view->params = Input::postData();
        $this->view->metaDataId = (isset($this->view->params['metaDataId']) ? $this->view->params['metaDataId'] : null);
        $this->view->gridOption = $this->model->getDVGridOptionsModel($this->view->metaDataId, true);
        $this->view->defaultGridOption = array_change_key_case(Mdobject::gridDefaultOptions(), CASE_UPPER);
        
        $configRow = $this->model->getDataViewConfigRowModel($this->view->metaDataId);
        
        $this->view->defaultViewerArr = array(
            array(
                'code' => 'detail', 
                'name' => 'Detail view'
            ),
            array(
                'code' => 'gmap', 
                'name' => 'Google Map'
            )
        );
        
        if (is_array($configRow['dataViewLayoutTypes'])) {
            
            foreach ($configRow['dataViewLayoutTypes'] as $v) {    
                $themeName = ($v['LAYOUT_TYPE'] == 'explorer' ? ($v['LAYOUT_THEME'] == '' ? $v['LAYOUT_TYPE'] : $v['LAYOUT_THEME']) : $v['LAYOUT_TYPE']);
                array_push($this->view->defaultViewerArr, array('code' => $v['LAYOUT_TYPE'], 'name' => $themeName));
            }
        }

        $this->load->model('mdmeta', 'middleware/models/');

        if ($this->view->metaDataId) {
            $this->view->groupChildDatas = $this->model->getGroupChildMetasNotGroupType($this->view->metaDataId);
        } else {
            $this->view->groupChildDatas = array();
        }

        $response = array(
            'Html' => $this->view->renderPrint('system/link/group/setDataModelGridOption', self::$mainViewPath),
            'Title' => 'Grid Option',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function exportParamReplacer($content, $metaDataId = null, $dvHeaders = null, $criteriaData = null) {
        
        $constantKeys = (new Mdstatement())->constantKeys();
        
        foreach ($constantKeys as $constantKey => $constantKeyValue) {
            $content = str_ireplace($constantKey, $constantKeyValue, $content);
        }
        
        $content = Mdstatement::configValueReplacer($content);
        
        if ($criteriaData) {
            
            foreach ($criteriaData as $criteriaKey => $criteria) {
                
                $criteriaKeyLower = strtolower($criteriaKey);
                
                if (isset($dvHeaders[$criteriaKeyLower]) && stripos($content, '#'.$criteriaKeyLower.'#') !== false) {
                    $pathRow = $dvHeaders[$criteriaKeyLower]; 
                    $value = $this->model->getCriteriaValue($metaDataId, $criteriaKey, $criteria, $pathRow);
                    $content = str_ireplace('#'.$criteriaKeyLower.'#', $value, $content);
                }
            }
        }
        
        $content = preg_replace('/\#([A-Za-z0-9_.-]+)\#/s', '', $content);
        
        return $content;
    }
    
    public static function getRowCount($text, $width = 55) {
        $rc = 0;
        $line = explode("\n", $text);
        foreach ($line as $source) {
            $rc += intval((strlen($source) / $width) + 1);
        }
        return $rc;
    }

    public function dataViewExcelExport() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        $metaDataId = Input::numeric('metaDataId');
        $total      = Input::numeric('total');
        
        if ($total && $total > 2000) {
            self::bigDataViewExcelExport($metaDataId);
            exit;
        }
        
        unset($_POST['treeConfigs']);
        unset($_POST['treeGrid']);
        
        $_POST['ignorePermission'] = 1;
        $_POST['isResponseCriteria'] = 1;
        $_POST['isResponseSql'] = 1;

        $exportData = $this->model->dataViewDataGridModel(false);

        if ($exportData['status'] == 'error') {
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=false; path=/');
            echo $exportData['message']; exit;
        }
        
        $treeField = Input::post('treeField');
        $configRow = $this->model->getDataViewConfigRowModel($metaDataId);
        $isTreeData = false;
        
        if ($treeField) {
            $isTreeData = true;
            $idField = $configRow['idField'];
            $parentField = $configRow['parentField'];
        }
        
        if (isset($exportData['sql']) && $exportData['sql']) {
            
            try {
                
                $sql = $exportData['sql'];
                
                if ($treeField) {
                    
                    $sql = "SELECT 
                                U.*, 
                                CASE WHEN LEVEL > 1 THEN LEVEL * 2 ELSE 0 END AS INDENTLEVEL  
                            FROM ($sql) U
                            START WITH U.$parentField IS NULL 
                            CONNECT BY NOCYCLE PRIOR U.$idField = U.$parentField";
                }
                
                $this->db->StartTrans(); 
                $this->db->Execute(Ue::createSessionInfo());

                $exportDataRows = $this->db->GetAll($sql);

                $this->db->CompleteTrans();
            
            } catch (Exception $ex) {
                
                header('Pragma: no-cache');
                header('Expires: 0');
                header('Set-Cookie: fileDownload=false; path=/');
                echo $ex->getMessage(); exit;
            }
            
            $exportDataRows = Arr::changeKeyLower($exportDataRows);
            
            if (isset($exportDataRows[0])) {
                
                $aggregateColumns = $this->model->getDataViewAggregateColumnsModel($metaDataId);
                
                if ($aggregateColumns) {
                    $firstRow = $exportDataRows[0];
                    foreach ($aggregateColumns as $aggregateColumn) {
                        $lowerPath = strtolower($aggregateColumn['FIELD_PATH']);
                        if (array_key_exists($lowerPath, $firstRow)) {
                            
                            $aggregateVal = 0;
                            
                            if ($aggregateColumn['COLUMN_AGGREGATE'] == 'sum') {
                                $aggregateVal = helperSumFieldBp($exportDataRows, $lowerPath);
                            } elseif ($aggregateColumn['COLUMN_AGGREGATE'] == 'min') {
                                $aggregateVal = helperMinFieldBp($exportDataRows, $lowerPath);
                            } elseif ($aggregateColumn['COLUMN_AGGREGATE'] == 'max') {
                                $aggregateVal = helperMaxFieldBp($exportDataRows, $lowerPath);
                            }
                            
                            $exportData['footer'][0][$lowerPath] = $aggregateVal;
                        }
                    }
                }
            }

        } elseif (isset($exportData['rows'])) {
            
            $exportDataRows = $exportData['rows'];
        }
        
        $headerData = $this->model->getDataViewGridHeaderModel($metaDataId, "(IS_IGNORE_EXCEL IS NULL OR IS_IGNORE_EXCEL = 0) AND META_TYPE_CODE <> 'file'");
        
        if (Mdobject::$pfKpiTemplateDynamicColumn) {
            $kpiColumns = $this->model->getKpiTemplateColumns(Mdobject::$pfKpiTemplateDynamicColumn);
            $headerData = array_merge($headerData, $kpiColumns);
        }
        
        $headerCount = count($headerData);
        $gridOptions = $this->model->getDVGridOptionsModel($metaDataId);
        
        $listName = $this->lang->line($configRow['LIST_NAME']);
        
        includeLib('Office/Excel/PHPExcel');
        includeLib('Office/Excel/PHPExcel/Writer/Excel2007');

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()
                ->setCreator('Veritech ERP')
                ->setCompany('Veritech ERP')
                ->setLastModifiedBy('')
                ->setTitle('Office 2007 - Document')
                ->setSubject('Office 2007 - Document')
                ->setDescription('')
                ->setKeywords('')
                ->setCategory('');
        
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
        
        $sheet->setTitle(Str::excelSheetName(Str::utf8_substr($listName, 0, 31)));
        
        $startRowIndex = 1;
        
        $exportHtml = $this->model->getDvExportTemplateHtml($metaDataId);
        
        if (isset($exportHtml['header'])) {
            
            $dvHeaders = $this->model->getOnlyHeaderFieldsByKey($metaDataId);
            $headerHtml = self::exportParamReplacer($exportHtml['header'], $metaDataId, $dvHeaders, $exportData['criteria']);
            $headerHtml = str_replace('&nbsp;', '<font color="#ffffff">_</font>', $headerHtml);
            
            $htmlObj = new PHPExcel_Helper_HTML;
            $richText = $htmlObj->toRichTextObject(mb_convert_encoding(html_entity_decode($headerHtml), 'HTML-ENTITIES', 'UTF-8'));
            
            $headerLastIndex = $sheet->getHighestRow();
            $startRowIndex = $headerLastIndex + 2;
            
            $sheet->setCellValue('B1', $richText);
            $sheet->mergeCells('B1:'.numToAlpha($headerCount + 1).$headerLastIndex);
            
            $numRows = self::getRowCount($richText);
            $sheet->getStyle('B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getRowDimension(1)->setRowHeight($numRows * 11);
            $sheet->getStyle('B1')->getAlignment()->setWrapText(true);
        }
        
        $colSpanCount = $excelColumnWidth = $excelRotate = $bigdecimalColumns = $style = $alphaColumns = array(); 
        $aColumnName = numToAlpha(1);
        
        foreach ($headerData as $key => $row) {
            
            if ($row['SIDEBAR_NAME'] != '') {
                $sideBarName = '';
                $isMergeColumn = true;
                $colSpanCount[$row['SIDEBAR_NAME']] = (isset($colSpanCount[$row['SIDEBAR_NAME']]) ? $colSpanCount[$row['SIDEBAR_NAME']] : 0) + 1;
            }
            
            if ($row['META_TYPE_CODE'] == 'bigdecimal') {
                $bigdecimalColumns[numToAlpha($key + 2)] = 1;
            }
        }
        
        if (isset($isMergeColumn)) {
            
            foreach ($headerData as $key => $row) {
                
                $columnAlphaIndex = numToAlpha($key + 2);
                
                if ($row['SIDEBAR_NAME'] != '') {
                    
                    if ($sideBarName != $row['SIDEBAR_NAME']) {
                        
                        $sheet->mergeCells($columnAlphaIndex . $startRowIndex.':'.numToAlpha($key + ($colSpanCount[$row['SIDEBAR_NAME']] + 1)).$startRowIndex);
                        
                        $sheet->setCellValue($columnAlphaIndex . $startRowIndex, Lang::line($row['SIDEBAR_NAME']));
                        $sideBarName = $row['SIDEBAR_NAME'];
                    } 
                    
                    $sheet->setCellValue($columnAlphaIndex . ($startRowIndex + 1), Lang::line($row['LABEL_NAME']));
                } else {
                    $sheet->mergeCells($columnAlphaIndex. $startRowIndex.':'.$columnAlphaIndex.($startRowIndex + 1));
                    $sheet->setCellValue($columnAlphaIndex . $startRowIndex, Lang::line($row['LABEL_NAME']));
                }
                
                if ($isTreeData && $treeField == $row['FIELD_PATH']) {
                    $treeColAlpha = $columnAlphaIndex;
                }
            }
            
            $startRowIndex = $startRowIndex + 1;
            
            $sheet->mergeCells('A'.($startRowIndex - 1).':'.'A'.$startRowIndex);
            $sheet->setCellValue('A'.($startRowIndex - 1), '№');
            
        } else {
            
            foreach ($headerData as $key => $row) {
                
                $columnNumberIndex = $key + 2;
                $columnAlphaIndex = numToAlpha($columnNumberIndex);
                
                $sheet->setCellValue($columnAlphaIndex . $startRowIndex, Lang::line($row['LABEL_NAME']));
                
                if ($row['EXCEL_COLUMN_WIDTH'] != '') {
                    $excelColumnWidth[$columnNumberIndex] = $row['EXCEL_COLUMN_WIDTH'];
                }
                
                if ($row['EXCEL_ROTATE'] != '') {
                    $excelRotate[$columnNumberIndex] = $row['EXCEL_ROTATE'];
                }
                
                if ($isTreeData && $treeField == $row['FIELD_PATH']) {
                    $treeColAlpha = $columnAlphaIndex;
                }
            }
            
            $sheet->setCellValue('A'.$startRowIndex, '№');
        }
        
        $i = $startRowIndex + 1;

        $repArr = array(
            "&quot;" => '"', 
            "&nbsp;" => ' ', 
            "<ul>" => '', 
            "<UL>" => '', 
            "</UL>" => '', 
            "</ul>" => '', 
            "<li>" => '', 
            "<LI>" => '',
        );    
        
        if (isset($exportDataRows[0])) {
            
            if ($groupField = Input::post('groupField')) {
            
                $key = 0;

                $lastColumnName = numToAlpha($headerCount + 1);
                $groupedRows    = Arr::groupByArray($exportDataRows, $groupField);

                foreach ($groupedRows as $groupedName => $groupedVal) {

                    $exportDataRows = $groupedVal['rows'];

                    if ($gridOptions['GROUPSUM'] === 'true' && isset($exportData['footer'])) {
                        $footerData = $exportData['footer'][0];
                        $cellSumValue = [];

                        foreach ($exportDataRows as $value) {
                            foreach ($headerData as $k => $item) {
                                if (isset($value[$item['FIELD_PATH']]) && is_numeric($value[$item['FIELD_PATH']]) && 'bigdecimal' == $item['META_TYPE_CODE']) {
                                    if (!isset($cellSumValue[$item['FIELD_PATH']])) {
                                        $cellSumValue[$item['FIELD_PATH']] = 0;
                                    }                                
                                    if (isset($value['pfsumrules']) && $value['pfsumrules']) {
                                        if ($value['pfsumrules'] == 'addition') {
                                            $cellSumValue[$item['FIELD_PATH']] += isset($value[$item['FIELD_PATH']]) ? $value[$item['FIELD_PATH']] : 0;
                                        } elseif ($value['pfsumrules'] == 'subtract') {
                                            $cellSumValue[$item['FIELD_PATH']] -= isset($value[$item['FIELD_PATH']]) ? $value[$item['FIELD_PATH']] : 0;
                                        }
                                    } elseif (array_key_exists('pfsumrules', $value) && !$value['pfsumrules']) {
                                        $cellSumValue[$item['FIELD_PATH']] += 0;
                                    } else {
                                        $cellSumValue[$item['FIELD_PATH']] += isset($value[$item['FIELD_PATH']]) ? $value[$item['FIELD_PATH']] : 0;
                                    }
                                }
                            }
                        }

                        $sheet->setCellValue($aColumnName.$i, $groupedName);
                        $sheet->mergeCells($aColumnName.$i.':B'.$i);
                        $sheet->getStyle($aColumnName.$i)->applyFromArray(array('font' => array('bold' => true)));                    

                        foreach ($headerData as $k => $item) {
                            $numToAlpha = numToAlpha($k + 2);

                            if (isset($cellSumValue[$item['FIELD_PATH']])) {
                                $sheet->setCellValueExplicit($numToAlpha . $i, $cellSumValue[$item['FIELD_PATH']], PHPExcel_Cell_DataType::TYPE_NUMERIC);
                                $sheet->getStyle($numToAlpha.$i)->applyFromArray(array('font' => array('bold' => true, 'color' => array('rgb' => '3333ff'))));                    
                            } else {
                                $sheet->setCellValueExplicit($numToAlpha . $i, '', PHPExcel_Cell_DataType::TYPE_STRING);
                            }
                        }                    

                    } else {

                        $sheet->setCellValue($aColumnName.$i, $groupedName);
                        $sheet->mergeCells($aColumnName.$i.':'.$lastColumnName.$i);
                        $sheet->getStyle($aColumnName.$i)->applyFromArray(array('font' => array('bold' => true)));

                    }

                    $i++;

                    foreach ($exportDataRows as $value) {

                        $sheet->setCellValue($aColumnName . $i, ++$key);

                        foreach ($headerData as $k => $item) {

                            $typeCode   = $item['META_TYPE_CODE'];
                            $numToAlpha = numToAlpha($k + 2);
                            $cellValue  = isset($value[$item['FIELD_PATH']]) ? $value[$item['FIELD_PATH']] : '';

                            if ($item['FIELD_PATH'] == 'pfnextstatuscolumn' && is_array($cellValue)) {
                                $wfmString = '';
                                foreach ($cellValue as $cellRow) {
                                    if (isset($cellRow['wfmstatusname'])) {
                                        $wfmString .= $cellRow['wfmstatusname'] . ', ';
                                    }
                                }
                                $cellValue = rtrim($wfmString, ', ');
                            }

                            if ($typeCode == 'date') {

                                $sheet->setCellValueExplicit($numToAlpha . $i, Date::formatter($cellValue, 'Y-m-d'), PHPExcel_Cell_DataType::TYPE_STRING);

                            } elseif ($typeCode == 'datetime') {

                                $sheet->setCellValueExplicit($numToAlpha . $i, Date::formatter($cellValue, 'Y-m-d H:i'), PHPExcel_Cell_DataType::TYPE_STRING);

                            } elseif ($typeCode == 'bigdecimal') {

                                $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_NUMERIC);

                            } elseif ($typeCode == 'boolean') {

                                $sheet->setCellValueExplicit($numToAlpha . $i, ($cellValue == '1' ? '✓' : ''), PHPExcel_Cell_DataType::TYPE_STRING);
                                $sheet->getStyle($numToAlpha . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                            } elseif ($item['LABEL_NAME'] == '*') {

                                if (strpos($cellValue, 'fa-chain-broken')) {
                                    $cellValue = '✗';
                                } elseif (strpos($cellValue, 'fa-chain')) {
                                    $cellValue = '✓';
                                }

                                $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);

                            } else {

                                if (strpos($cellValue, '<br>') !== false || strpos($cellValue, '<br />') !== false 
                                    || strpos($cellValue, '<br/>') !== false || strpos($cellValue, '</li>') !== false 
                                    || strpos($cellValue, '</LI>') !== false || strpos($cellValue, '</a>') !== false) {

                                    $cellValue = strip_tags(str_replace(array('<br/>','<br>','<br />','</li>','</LI>'), "\n", strtr(trim($cellValue), $repArr)));
                                    $alphaColumns[$numToAlpha] = 1;

                                } elseif (strpos($cellValue, '<tr>') !== false || strpos($cellValue, '<TR>') !== false) {

                                    $repArr = array(
                                        "<table>" => '', 
                                        "<TABLE>" => '', 
                                        "</TABLE>" => '', 
                                        "</table>" => '', 
                                        "<li>" => '', 
                                        "<LI>" => '', 
                                        "</td><td" => ' - <td', 
                                    );      
                                    $cellValue = strip_tags(str_replace(array('</tr>','</tr>'), "\n", strtr($cellValue, $repArr)));    
                                    $alphaColumns[$numToAlpha] = 1;
                                }

                                $cellValue = str_replace('&quot;', '"', $cellValue);
                                $cellValue = strip_tags($cellValue);

                                $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);
                            }
                        }

                        $i++;
                    }
                }

            } else {

                $firstRow = $exportDataRows[0];
                $isBulkUpdate = false;

                if (isset($firstRow['pfupdatetblname']) 
                    && isset($firstRow['pfupdatecolname']) 
                    && isset($firstRow['pfupdatecolval']) 
                    && isset($firstRow['pfupdateequalcolname']) 
                    && isset($firstRow['pfupdateequalcolval']) 
                    ) {

                    $isBulkUpdate = true;
                    $bulkUpdateEqualColVal = strtolower($firstRow['pfupdateequalcolval']);
                    $bulkIds = array();
                }

                foreach ($exportDataRows as $key => $value) {

                    $sheet->setCellValue($aColumnName . $i, ++$key);

                    foreach ($headerData as $k => $item) {

                        $typeCode   = $item['META_TYPE_CODE'];
                        $numToAlpha = numToAlpha($k + 2);
                        $cellValue  = isset($value[$item['FIELD_PATH']]) ? $value[$item['FIELD_PATH']] : '';

                        if ($item['FIELD_PATH'] == 'pfnextstatuscolumn' && is_array($cellValue)) {
                            $wfmString = '';
                            foreach ($cellValue as $cellRow) {
                                if (isset($cellRow['wfmstatusname'])) {
                                    $wfmString .= $cellRow['wfmstatusname'] . ', ';
                                }
                            }
                            $cellValue = rtrim($wfmString, ', ');
                        }

                        if ($typeCode == 'date') {

                            $sheet->setCellValueExplicit($numToAlpha . $i, Date::formatter($cellValue, 'Y-m-d'), PHPExcel_Cell_DataType::TYPE_STRING);

                        } elseif ($typeCode == 'datetime') {

                            $sheet->setCellValueExplicit($numToAlpha . $i, Date::formatter($cellValue, 'Y-m-d H:i'), PHPExcel_Cell_DataType::TYPE_STRING);

                        } elseif ($typeCode == 'bigdecimal') {

                            $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_NUMERIC);

                        } elseif ($typeCode == 'boolean') {

                            $sheet->setCellValueExplicit($numToAlpha . $i, ($cellValue == '1' ? '✓' : ''), PHPExcel_Cell_DataType::TYPE_STRING);
                            $sheet->getStyle($numToAlpha . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                        } elseif ($item['LABEL_NAME'] == '*') {

                            if (strpos($cellValue, 'fa-chain-broken')) {
                                $cellValue = '✗';
                            } elseif (strpos($cellValue, 'fa-chain')) {
                                $cellValue = '✓';
                            }

                            $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);

                        } else {

                            if (strpos($cellValue, '<br>') !== false || strpos($cellValue, '<br />') !== false 
                                || strpos($cellValue, '<br/>') !== false || strpos($cellValue, '</li>') !== false 
                                || strpos($cellValue, '</LI>') !== false || strpos($cellValue, '</a>') !== false) {

                                $cellValue = strip_tags(str_replace(array('<br/>','<br>','<br />','</li>','</LI>'), "\n", strtr(trim($cellValue), $repArr)));
                                $alphaColumns[$numToAlpha] = 1;

                            } elseif (strpos($cellValue, '<tr>') !== false || strpos($cellValue, '<TR>') !== false) {

                                $repArr = array(
                                    "<table>" => '', 
                                    "<TABLE>" => '', 
                                    "</TABLE>" => '', 
                                    "</table>" => '', 
                                    "<li>" => '', 
                                    "<LI>" => '', 
                                    "</td><td" => ' - <td', 
                                );      
                                $cellValue = strip_tags(str_replace(array('</tr>','</tr>'), "\n", strtr($cellValue, $repArr)));    
                                $alphaColumns[$numToAlpha] = 1;
                            }

                            $cellValue = str_replace('&quot;', '"', $cellValue);
                            $cellValue = str_replace('&gt;', '>', $cellValue);
                            $cellValue = str_replace('&lt;', '<', $cellValue);
                            $cellValue = strip_tags($cellValue);

                            $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);
                        }
                    }

                    if ($isTreeData && isset($treeColAlpha)) {
                        $sheet->getStyle($treeColAlpha . $i)->getAlignment()->setIndent($value['indentlevel']);
                    }

                    $i++;

                    if ($isBulkUpdate && $value[$bulkUpdateEqualColVal]) {
                        $bulkIds[] = $value[$bulkUpdateEqualColVal];
                    }
                }
            }
        }
        
        $getHighestRowNum = $sheet->getHighestRow();
        
        if ($alphaColumns) {
            foreach ($alphaColumns as $alphaColumnK => $alphaColumn) {
                $sheet->getStyle($alphaColumnK.'2:'.$alphaColumnK.$getHighestRowNum)->getAlignment()->setWrapText(true);
            }
        }
        
        if (isset($exportData['footer']) && $gridOptions['SHOWFOOTER'] !== 'false') {
            
            $footerData = $exportData['footer'][0];
            
            foreach ($headerData as $k => $item) {
                
                if (isset($footerData[$item['FIELD_PATH']])) {
                    
                    $footerStyle['font']['bold'] = true;
                    $footerStyle['alignment']['horizontal'] = PHPExcel_Style_Alignment::HORIZONTAL_RIGHT;
                    
                    $sheet->setCellValueExplicit(numToAlpha($k + 2) . $i, trim($footerData[$item['FIELD_PATH']]), PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    $sheet->getStyle(numToAlpha($k + 2) . $i)->applyFromArray($footerStyle);
                }
            }
            
            $getHighestRowNum ++;
        }
        
        if ($bigdecimalColumns) {
            foreach ($bigdecimalColumns as $bigdecimalK => $bigdecimalV) {
                $sheet->getStyle($bigdecimalK.'2:'.$bigdecimalK.$getHighestRowNum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            }
        }
        
        if (isset($exportHtml['footer'])) {
            
            $dvHeaders = isset($dvHeaders) ? $dvHeaders : $this->model->getOnlyHeaderFieldsByKey($metaDataId);
            $footerHtml = self::exportParamReplacer($exportHtml['footer'], $metaDataId, $dvHeaders, $exportData['criteria']);
            $footerHtml = str_replace('&nbsp;', '<font color="#ffffff">_</font>', $footerHtml);
            
            $htmlObj = new PHPExcel_Helper_HTML;
            $richText = $htmlObj->toRichTextObject(mb_convert_encoding(html_entity_decode($footerHtml), 'HTML-ENTITIES', 'UTF-8'));
            
            $rowIndexByLast = $getHighestRowNum + 2;
            
            $sheet->setCellValue('B'.$rowIndexByLast, $richText);
            $sheet->mergeCells('B'.$rowIndexByLast.':'.numToAlpha($headerCount + 1).$rowIndexByLast);
            
            $numRows = self::getRowCount($richText);
            
            $sheet->getStyle('B'.$rowIndexByLast)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
            $sheet->getRowDimension($rowIndexByLast)->setRowHeight($numRows * 11);
            $sheet->getStyle('B'.$rowIndexByLast)->getAlignment()->setWrapText(true);
        }
        
        $sheet->freezePane('A'.($startRowIndex + 1));

        $headerDataCount = $headerCount;
        
        foreach (range(0, $headerDataCount) as $columnID) {
            $sheet->getColumnDimensionByColumn($columnID)->setAutoSize(true);
        }
        
        $lastStartRowIndex = (isset($isMergeColumn) ? ($startRowIndex - 1) : $startRowIndex);
        
        if ($excelColumnWidth) {
            
            foreach ($excelColumnWidth as $excelColumnWidthKey => $excelColumnWidthVal) {
                $excelColumnWidthAlpha = numToAlpha($excelColumnWidthKey);
                $sheet->getColumnDimension($excelColumnWidthAlpha)->setAutoSize(false);
                $sheet->getColumnDimension($excelColumnWidthAlpha)->setWidth($excelColumnWidthVal);
            }
        }
        
        if ($excelRotate) {
            
            foreach ($excelRotate as $excelRotateKey => $excelRotateVal) {
                $excelRotateAlpha = numToAlpha($excelRotateKey);
                $sheet->getStyle($excelRotateAlpha.$lastStartRowIndex)->getAlignment()->setTextRotation($excelRotateVal);
            }
            
            $sheet->getRowDimension($lastStartRowIndex)->setRowHeight(70);
        }
        
        $sheet->getStyle('A'.$lastStartRowIndex.':' . numToAlpha($headerDataCount + 1) . $startRowIndex)->applyFromArray(
            array(
                'font' => array(
                    'bold' => true
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'wrap' => true
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                ),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '74ad42')
                )
            )
        );
        
        if (count($exportData['criteria']) > 0) {
            
            $dvHeaders = isset($dvHeaders) ? $dvHeaders : $this->model->getOnlyHeaderFieldsByKey($metaDataId);
            
            $objPHPExcel->createSheet();
            $objPHPExcel->setActiveSheetIndex(1);
            
            $sheet = $objPHPExcel->getActiveSheet();
            $sheet->setTitle(Lang::line('metadata_filter'));
            
            $criteriaData = $exportData['criteria'];
            
            $sn = 1;
            
            foreach ($criteriaData as $criteriaKey => $criteria) {
                
                $criteriaKeyLower = strtolower($criteriaKey);
                
                if (isset($dvHeaders[$criteriaKeyLower])) {
                    $pathRow = $dvHeaders[$criteriaKeyLower]; 
                
                    $value = $this->model->getCriteriaValue($metaDataId, $criteriaKey, $criteria, $pathRow);

                    $sheet->setCellValue('A'.$sn, Lang::line($pathRow['LABEL_NAME']));
                    $sheet->setCellValue('B'.$sn, $value);

                    $sn++;
                }
            }
            
            $sheet->getStyle('A1:A'.($sn - 1))->applyFromArray(
                array(
                    'font' => array(
                        'bold' => true
                    ),
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                        'wrap' => true
                    ),
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '74ad42')
                    )
                )
            );
            
            $objPHPExcel->setActiveSheetIndex(0);
        }
        
        if (isset($isBulkUpdate) && $isBulkUpdate && $bulkIds) { 
            
            try {
                
                $pfUpdateTblName = Input::param($firstRow['pfupdatetblname']);
                $pfUpdateColName = Input::param($firstRow['pfupdatecolname']);
                $pfUpdateColVal = Input::param($firstRow['pfupdatecolval']);
                $pfUpdateEqualColName = Input::param($firstRow['pfupdateequalcolname']);

                $bulkIdsSplit = array_chunk($bulkIds, 500); $where = '';

                foreach ($bulkIdsSplit as $bulkIdsArr) {
                    $where .= " $pfUpdateEqualColName IN ('".implode("','", $bulkIdsArr)."') OR";
                }

                $where = rtrim($where, ' OR');

                $this->db->Execute("UPDATE $pfUpdateTblName SET $pfUpdateColName = '$pfUpdateColVal' WHERE $where");
            
            } catch (Exception $ex) {
                // return $ex->getMessage();
            }
        }
        
        try {
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=true; path=/');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8');
            header('Content-Disposition: attachment;filename="' . $listName . ' - ' . Date::currentDate('YmdHi') . '.xlsx"');
            header('Content-Transfer-Encoding: binary');
            header('Cache-Control: must-revalidate');

            ob_end_clean();
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            
        } catch (Exception $e) {
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=false; path=/');
            echo $e->getMessage();
        }
        
        exit;
    }
    
    public function setExcelIndentColumn($depth, $parentId = 0, $idField, $parentField) {
        foreach (self::$exportDataRows as $k => $row) {
            
            if ($depth == 0 && $parentId == 0) {
                $parentId = '';
            }
            
            if ($row[$parentField] == $parentId) {
                
                unset(self::$exportDataRows[$k]);
                
                if (!isset(self::$exportedIds[$row[$idField]])) {
                    
                    self::$exportedIds[$row[$idField]] = 1;
                
                    $row['indentLevel'] = $depth * 2;
                    self::$indentRows[] = $row;  
                } 
                
                self::setExcelIndentColumn($depth + 1, $row[$idField], $idField, $parentField);
            }
        }
    }
    
    public function bigDataViewExcelExport($metaDataId) {
        
        $_POST['isExportExcel'] = 1;
        $exportData = $this->model->dataViewDataGridModel(false, $metaDataId);
        
        if ($exportData['status'] == 'error') {
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=false; path=/');
            echo $exportData['message']; exit;
        }
        
        $excelCriteria = "(IS_IGNORE_EXCEL IS NULL OR IS_IGNORE_EXCEL = 0) AND META_TYPE_CODE <> 'file'";
        $excelCriteria .= " AND ((FIELD_PATH <> 'wfmstatusname' AND COLUMN_NAME IS NOT NULL) OR (FIELD_PATH = 'wfmstatusname'))"; 
        
        $headerDatas = $this->model->getDataViewGridHeaderModel($metaDataId, $excelCriteria);
        $configRow   = $this->model->getDataViewConfigRowModel($metaDataId);
        
        if (Mdobject::$pfKpiTemplateDynamicColumn) {
            $kpiColumns = $this->model->getKpiTemplateColumns(Mdobject::$pfKpiTemplateDynamicColumn);
            $headerDatas = array_merge($headerDatas, $kpiColumns);
        }

        $header = $widths = array();
        
        $header['№'] = 'integer';
        $widths[] = 5;

        foreach ($headerDatas as $headerDataRow) {
            
            $headerTypeCode = $headerDataRow['META_TYPE_CODE'];
            $columnWidth    = $headerDataRow['EXCEL_COLUMN_WIDTH'];
            $sidebarName    = $headerDataRow['SIDEBAR_NAME'];
            $labelName      = Lang::line($headerDataRow['LABEL_NAME']);
            
            if ($sidebarName) {
                $labelName = Lang::line($sidebarName).'/'.$labelName;
            }
            
            if ($headerTypeCode == 'date') {
                
                $header[$labelName] = 'date';
                $widths[] = $columnWidth ? $columnWidth : 13.5;
                
            } elseif ($headerTypeCode == 'datetime') {
                
                $header[$labelName] = 'datetime';
                $widths[] = $columnWidth ? $columnWidth : 18.5;
                
            } elseif ($headerTypeCode == 'bigdecimal') {
                
                $header[$labelName] = 'price';
                $widths[] = $columnWidth ? $columnWidth : 13;
                
            } elseif ($headerTypeCode == 'number' || $headerTypeCode == 'long' || $headerTypeCode == 'integer') {
                
                $header[$labelName] = 'integer';
                $widths[] = $columnWidth ? $columnWidth : 13;
                
            } else {
                $header[$labelName] = 'string';
                $widths[] = $columnWidth ? $columnWidth : 13;
            }
        }
        
        if (isset($exportData['sql']) && $exportData['sql']) {
            
            try {
                
                $this->db->StartTrans(); 
                $this->db->Execute(Ue::createSessionInfo());

                $exportDataRows = $this->db->GetAll($exportData['sql']);

                $this->db->CompleteTrans();
            
            } catch (Exception $ex) {
                
                header('Pragma: no-cache');
                header('Expires: 0');
                header('Set-Cookie: fileDownload=false; path=/');
                echo $ex->getMessage(); exit;
            }

        } elseif (isset($exportData['rows'])) {

            $exportDataRows = $exportData['rows'];
        }
        
        $listName = Lang::line($configRow['LIST_NAME']);
        
        includeLib('Office/Excel/xlsxwriter/xlsxwriter.class');
        
        try {

            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=true; path=/');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8');
            header('Content-Disposition: attachment;filename="' . $listName . ' - ' . Date::currentDate('YmdHi') . '.xlsx"');
            header('Content-Transfer-Encoding: binary');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            
            ob_end_clean();

            $writer = new XLSXWriter();
            $writer->setAuthor('Veritech ERP');
            $writer->writeSheetHeader($listName, $header, array('freeze_rows'=>1,'widths'=>$widths,'color'=>'#000','fill'=>'#74ad42','font-style'=>'bold','border'=>'left,right','border-style'=>'thin','border-color'=>'#000','height'=>'15.5','valign'=>'center'));
            $writer->writeSheet($exportDataRows, $listName);
            $writer->writeToStdOut(); 

        } catch (Exception $e) {

            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=false; path=/');
            echo $e->getMessage(); 
        }

        exit;
    }
    
    public function dataViewTextExport() {
        
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        ini_set('mbstring.internal_encoding', 'UTF-8');

        $metaDataId = Input::numeric('metaDataId');

        $configRow = $this->model->getDataViewConfigRowModel($metaDataId);
        $headerData = $this->model->getDataViewGridHeaderModel($metaDataId, '1 = 1', 1, true, false);
        $exportData = $this->model->dataViewDataGridModel(false);

        if ($exportData['status'] == 'error') {
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=false; path=/');
            echo $exportData['message']; exit();
        }
        
        $metaCode = strtolower($configRow['META_DATA_CODE']);
        
        $ids = array();
        $isUpdateQry = false;
        
        if (substr($metaCode, 0, 10) == 'ttum_file_') {
            
            $wfmStatusId = '1501215327579221';
            $isUpdateQry = true;
            $currentDate = Date::currentDate('Y-m-d H:i:s');
            $sessionUserKeyId = Ue::sessionUserKeyId();
            $insertInto = 'INSERT ALL ';
        }
        
        $exportDataRows = $exportData['rows'];
        $exportText = '';
        $fileName = Date::currentDate('Ymd').'_'.Ue::getSessionUserName().'_'.Date::currentDate('His').'.txt';
        $width = 40;
        
        foreach ($exportDataRows as $key => $value) {
            
            foreach ($headerData as $k => $item) {
                
                $repArr = array(
                    "&quot;" => '"', 
                    "&nbsp;" => ' '
                );    
                
                $cellValue = isset($value[$item['FIELD_PATH']]) ? strip_tags(str_replace(array('<br/>','<br>','<br />'), "\n", strtr(trim($value[$item['FIELD_PATH']]), $repArr))) : '';
                
                $strLength = mb_strlen($cellValue);
                $align = $item['BODY_ALIGN'];
                
                $columnWidth = ($item['MAX_VALUE'] != '' ? str_replace(array('px', '%'), '', $item['MAX_VALUE']) : $width);
                
                if ($strLength > $columnWidth) {
                    $repeatCount = 0;
                    $cellValue = mb_substr($cellValue, 0, $columnWidth);
                } else {
                    $repeatCount = $columnWidth - $strLength;
                } 

                if ($align == 'right') {
                    $exportText .= str_repeat(' ', $repeatCount) . $cellValue;
                } else {
                    $exportText .= $cellValue . str_repeat(' ', $repeatCount);
                }
                
                if ($item === end($headerData)) {
                    $exportText .= "\n";
                }
            }
            
            $id = $value['id'];
            
            if ($isUpdateQry && $id) {
                
                array_push($ids, $id); 
                
                $insertInto .= "INTO META_WFM_LOG(ID, REF_STRUCTURE_ID, RECORD_ID, WFM_STATUS_ID, WFM_DESCRIPTION, CREATED_DATE, CREATED_USER_ID) VALUES(".getUID().", 1454051895826, ".$value['generalledgerbookid'].", $wfmStatusId, '$fileName', ".$this->db->ToDate("'$currentDate'", 'YYYY-MM-DD HH24:MI:SS').", $sessionUserKeyId) ";
                
                /*$wfmLogData = array(
                    'ID' => getUID(), 
                    'REF_STRUCTURE_ID' => '1454051895826', 
                    'RECORD_ID' => $id, 
                    'WFM_STATUS_ID' => $wfmStatusId, 
                    'WFM_DESCRIPTION' => $fileName, 
                    'CREATED_DATE' => $currentDate, 
                    'CREATED_USER_ID' => $sessionUserKeyId
                );
                $this->db->AutoExecute('META_WFM_LOG', $wfmLogData);*/
            }
        }
        
        if ($isUpdateQry && $ids) {
            
            $idsChunk = array_chunk($ids, 500);
            
            foreach ($idsChunk as $idChunk) {
                $this->db->Execute("UPDATE FIN_GENERAL_LEDGER SET WFM_STATUS_ID = $wfmStatusId WHERE ID IN (".implode(',', $idChunk).")");
            }
            
            $insertInto .= 'SELECT * FROM DUAL';
            
            $this->db->Execute($insertInto);
        }
        
        try {
            
            $this->model->writeDvTextFileModel($fileName, $exportText);
            
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=true; path=/');
            header('Content-Encoding: UTF-8');
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header('Content-Type: text/plain;charset=utf-8');
            header('Content-Disposition: attachment; filename="'.$fileName.'"');
            header('Content-Transfer-Encoding: binary');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            flush();

            echo $exportText; exit;
            
        } catch (Exception $e) {
            
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=false; path=/');
            
            echo $e->getMessage(); exit;
        }
    }

    public function downloadFile() {
        
        $readFileUrl = Input::get('file');
        $fileName = Input::get('fileName');

        if ($readFileUrl) {
            
            $readFileUrl = str_replace(array('../', '..\\'), '', $readFileUrl);
            $subStrFileUrl = str_replace("\\", '/', substr($readFileUrl, 0, 16));
            
            if ($subStrFileUrl == 'storage/uploads/' && file_exists($readFileUrl)) {
                $readFile = $readFileUrl;
            } else {
                Message::add('w', 'Татах файл олдсонгүй', 'back');
            }
            
            $ext = strtolower(substr($readFileUrl, strrpos($readFileUrl, '.') + 1));
            $allowedExtentions = array(
                'pdf', 'doc', 'docx', 'xls', 'xlsx', 'xlsm', 'ppt', 'pptx', 'rtx', 'rtf', 'vsd', 'vsdx', 'json', 
                'bmp', 'png', 'gif', 'jpg', 'jpeg', 'tiff', 'tif', 'heif', 'hevc', 'svg', 'rar', 'zip', 'ifc', 
                'mp3', 'mp4', '3gp', 'mpeg', 'mpg', 'mpe', 'mov', 'qt', 'avi', 'movie', 'webp', 'wmv', 'db', 
                'html', 'dwg', 'msg'
            );

            if (isset($readFile) && in_array($ext, $allowedExtentions)) {
                
                $contentId = Input::get('contentId');
                
                $this->load->model('mdpreview', 'middleware/models/');                
                $this->model->createEcmContentLogModel($contentId, 2);
                
                if (!$fileName) {
                    
                    $contentRow = $this->model->getEcmContentRowModel($contentId);
                    
                    if ($contentRow && $contentRow['FILE_NAME']) {
                        
                        $fileName = $contentRow['FILE_NAME'];
                        $fileNameLower = Str::lower($fileName);
                        
                        if (strpos($fileNameLower, '.'.$ext) == false) {
                            $fileName = $fileName.'.'.$ext;
                        }
                        
                    } else {
                        $fileName = 'erp_file_' . getUID() . '.' . $ext;
                    }
                    
                } elseif (strpos($fileName, '.') === false) {
                    $fileName .= '.' . $ext;
                } 
                
                fileDownload($fileName, $readFile);
            }
        }
    }
    
    public function getAjaxTree() {
        $dataViewId = Input::param($_REQUEST['dataViewId']);
        $structureMetaDataId = Input::param($_REQUEST['structureMetaDataId']);
        $parent = Input::param($_REQUEST['parent']);
        
        $folderList = $this->model->getTreeDataByValue($dataViewId, $structureMetaDataId, $parent);
        
        jsonResponse($folderList);
    }    
    
    public function getAjaxTreeView() {
        $dataViewId = Input::param($_REQUEST['dataViewId']);
        $structureMetaDataId = Input::param($_REQUEST['structureMetaDataId']);
        $parent = Input::param($_REQUEST['parent']);
        
        $folderList = $this->model->getTreeDataViewByValue($dataViewId, $structureMetaDataId, $parent);
        
        jsonResponse($folderList);
    }    
    
    public function package($metaDataId, $dataType = '') {

        $this->view->metaDataId = Input::param($metaDataId);
        $this->view->fillPath = array();
        $this->view->drillDownDefaultCriteria = '';   
        $this->view->uriParams = Input::post('uriParams');

        if (!is_numeric($this->view->metaDataId)) {
                
            set_status_header(404);

            $err = Controller::loadController('Err');
            $err->index();
            exit;
        }
        
        if (Input::isEmpty('drillDownDefaultCriteria') === false) {
            
            parse_str(Input::post('drillDownDefaultCriteria'), $postParam);
 
            $addonJsonParam = isset($_POST['addonJsonParam']) ? json_decode($_POST['addonJsonParam'], true) : array();
            $defaultCriteriaParams = isset($_POST['defaultCriteriaParams']) ? json_decode($_POST['defaultCriteriaParams'], true) : array();

            foreach ($postParam as $pp => $pv) {
                if ($pv == 'undefined' || $pv == 'null') {
                    if ($addonJsonParam) {
                        foreach ($addonJsonParam as $ap => $av) {
                            if ($av !== '') {
                                $postParam[strtolower($ap)] = $av;
                            } else {
                                $postParam[strtolower($pp)] = '';
                            }
                        }
                    } else {
                        $postParam[strtolower($pp)] = '';
                    }
                }
            }
            
            if ($defaultCriteriaParams) {
                foreach ($defaultCriteriaParams as $ap => $av) {
                    if (!isset($postParam[strtolower($ap)]) || $postParam[strtolower($ap)] == '') {
                        $postParam[strtolower($ap)] = $av;
                    } 
                }
            }

            $this->view->fillPath = $postParam;
            $this->view->drillDownDefaultCriteria = Input::post('drillDownDefaultCriteria');   
        }
        
        $this->load->model('mdobject', 'middleware/models/');
        
        $this->view->workSpaceId = Input::postCheck('workSpaceId') ? Input::post('workSpaceId') : $this->view->metaDataId;
        $this->view->row = $this->model->getPackageLinkModel($this->view->metaDataId); 
        
        if (!$this->view->row) {
            Message::add('e', '', 'back');
        }
        
        $this->view->title = '';
        $this->view->pageTitle = $this->lang->line($this->view->row['META_DATA_NAME']);
        
        if ($this->view->row['IS_IGNORE_MAIN_TITLE'] != '1') {
            $this->view->title = $this->view->pageTitle;
        }

        $this->view->css = array_unique(array_merge(AssetNew::metaCss(), AssetNew::lifeCycleCss()));
        $this->view->js = array_unique(array_merge(AssetNew::metaOtherJs(), AssetNew::lifeCycleJs()));
        $this->view->fullUrlJs = AssetNew::amChartJs();
        
        $this->view->packageCode = $this->view->row['META_DATA_CODE'];
        $this->view->spliteNumber = isset($this->view->row['SPLIT_COLUMN']) ? $this->view->row['SPLIT_COLUMN'] : '4';
        $this->view->packageClass = isset($this->view->row['PACKAGE_CLASS']) ? $this->view->row['PACKAGE_CLASS'] : '';
        $this->view->isIgnorePackTitle = isset($this->view->row['IS_IGNORE_PACKAGE_TITLE'] ) ? $this->view->row['IS_IGNORE_PACKAGE_TITLE'] : '0';
        $this->view->isFilterShowButton = isset($this->view->row['IS_FILTER_BTN_SHOW']) ? $this->view->row['IS_FILTER_BTN_SHOW'] : '0';

        $this->view->packageChildMetas = $this->view->row['IS_CHECK_PERMISSION'] == '1' ? $this->model->getPackageChildMetasPermissionModel($this->view->metaDataId) : $this->model->getPackageChildMetasModel($this->view->metaDataId);        
        
        $this->view->isAjax = is_ajax_request();
        $this->view->row['DEFAULT_META_ID'] = issetParam($this->view->fillPath['defaultmetaid']) ? $this->view->fillPath['defaultmetaid'] : $this->view->row['DEFAULT_META_ID'];
        
        if (!$this->view->isAjax) {
            $this->view->render('header');
        }
        
        if ($this->view->row['RENDER_TYPE'] == 'tab' || empty($this->view->row['RENDER_TYPE'])) {
            
            if ($this->view->row['COUNT_META_DATA_ID']) {
                
                $countParam = array(
                    'systemMetaGroupId' => $this->view->row['COUNT_META_DATA_ID'],
                    'showQuery' => 0, 
                    'ignorePermission' => 1, 
                    'pagingWithoutAggregate' => 1, 
                    'paging' => array('offset' => '1', 'pageSize' => '50')
                );    

                $countResult = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'PL_MDVIEW_004', $countParam);
                
                if (isset($countResult['result']) && isset($countResult['result'][0]['metadataid'])) {
                    unset($countResult['result']['paging']);
                    unset($countResult['result']['aggregatecolumns']);    
                    $this->view->countResult = Arr::groupByArrayOnlyRow($countResult['result'], 'metadataid', false);
                }
            }
            
            $this->view->packageTabs = $this->view->renderPrint('tabs', self::$packageViewPath);
            
        } elseif ($this->view->row['RENDER_TYPE'] == 'onepage') {
            $this->view->packageTabs = $this->view->renderPrint('onepage', self::$packageViewPath);
        } elseif ($this->view->row['RENDER_TYPE'] == 'column') {
            $this->view->packageTabs = $this->view->renderPrint('column', self::$packageViewPath);
        } elseif ($this->view->row['RENDER_TYPE'] == 'column12') {
            $this->view->packageTabs = $this->view->renderPrint('column12', self::$packageViewPath);
        } elseif ($this->view->row['RENDER_TYPE'] == 'leftside') {
            
            $packageChildMetas = $this->view->packageChildMetas;
            $this->view->packageChildMetas = array();
            $this->view->usedDefCriteria = false;
            
            if ($packageChildMetas) {
                
                foreach ($packageChildMetas as $key => $metas) {
                    
                    $dvConfigRow = $this->model->getDataViewConfigRowModel($metas['META_DATA_ID']); 
                    
                    if (!isset($this->view->leftSideClass) && $dvFilterColorSchema = issetParam($dvConfigRow['COLOR_SCHEMA'])) {
                        $this->view->leftSideClass = 'dv-searchcolor-'.$dvFilterColorSchema;
                    }
                    
                    $this->view->dataViewHeaderRealData = $this->model->dataViewHeaderDataModel($metas['META_DATA_ID']); 
                    $this->view->dataViewHeaderData = self::findCriteria($metas['META_DATA_ID'], $this->view->dataViewHeaderRealData);
                    $this->view->dataViewMandatoryHeaderData = self::findMandatoryCriteria($metas['META_DATA_ID'], $this->view->dataViewHeaderRealData);
            
                    $defaultCriteria = isset($this->view->dataViewHeaderData['data']) ? Arr::multidimensional_list($this->view->dataViewHeaderData['data'], array('IS_ADVANCED' => '0')) : array();
                    
                    if ($this->view->dataViewMandatoryHeaderData) {
                        $defaultCriteria = array_merge($this->view->dataViewMandatoryHeaderData, (!$defaultCriteria) ? array() : $defaultCriteria);
                    }
                    
                    $metas['DEFAULT_CRITERIA'] = $defaultCriteria;
                    $this->view->usedDefCriteria = ($defaultCriteria) ? true : $this->view->usedDefCriteria;
                    array_push($this->view->packageChildMetas, $metas);
                }
                
                $countCriteria = array();
                
                if ($firstCriteria = issetParam($this->view->packageChildMetas[0]['DEFAULT_CRITERIA'])) {
                    
                    foreach ($firstCriteria as $firstCriteriaRow) {
                        
                        if ($firstCriteriaRow['DEFAULT_VALUE']) {
                            
                            $criteriaValue = Mdmetadata::setDefaultValue($firstCriteriaRow['DEFAULT_VALUE']);
                            $operator = '=';
                            
                            if ($firstCriteriaRow['META_TYPE_CODE'] == 'string') {
                                $criteriaValue = '%'.$criteriaValue.'%';
                                $operator = 'like';
                            } 
                            
                            $countCriteria[$firstCriteriaRow['META_DATA_CODE']][] = array(
                                'operator' => $operator,
                                'operand' => $criteriaValue
                            );
                        }
                    }
                }
                
                $countParam = array(
                    'systemMetaGroupId' => $this->view->row['COUNT_META_DATA_ID'],
                    'showQuery' => 0, 
                    'ignorePermission' => 1,
                    'pagingWithoutAggregate' => 1, 
                    'paging' => array('offset' => '1', 'pageSize' => '50'), 
                    'criteria' => $countCriteria
                );    
                
                $counttDataview = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'PL_MDVIEW_004', $countParam);
                $this->view->counttDataview = array();

                if (isset($counttDataview['result']) && isset($counttDataview['result'][0]['metadataid'])) {
                    unset($counttDataview['result']['paging']);
                    unset($counttDataview['result']['aggregatecolumns']);
                    $this->view->counttDataview = Arr::groupByArrayOnlyRow($counttDataview['result'], 'metadataid', false);
                }
            }
            
            $this->view->packageTabs = $this->view->renderPrint('leftside', self::$packageViewPath);
        }        
        
        if ($dataType == 'json') {
            echo json_encode(
                array(
                    'status' => 'success', 
                    'metaType' => 'PACKAGE', 
                    'Title' => $this->view->pageTitle, 
                    'Html' => $this->view->renderPrint('index', self::$packageViewPath)
                )
            ); exit;
        } else {
            $this->view->render('index', self::$packageViewPath);
        }
        
        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }
    }
    
    public function getNameByType($metaDataId, $metaTypeId, $metaDataName) {
        $this->load->model('mdobject', 'middleware/models/');
        return $this->model->getNameByTypeModel($metaDataId, $metaTypeId, $metaDataName);
    }
    
    public function dataViewAdvancedConfigForm() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->dataViewColumnDataFields = $this->model->getDataViewGridAllFieldsModel($this->view->metaDataId);
       
        $isUserConfig = helperSumFieldBp($this->view->dataViewColumnDataFields, 'MAIN_META_DATA_ID');

        $this->view->isUserConfig = $isUserConfig ? true : false;

        $response = array(
            'Html' => $this->view->renderPrint('viewer/detail/dataViewAdvancedConfigForm', self::$dataViewPath), 
            'Title' => $this->lang->line('META_00112'), 
            'isUserConfig' => $this->view->isUserConfig, 
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function saveMetaGroupConfigUser() {
        $result = $this->model->saveMetaGroupConfigUserModel();
        
        $gridOptionRow = array_change_key_case(Mdobject::gridDefaultOptions(), CASE_UPPER);
        $result['objectValueViewType'] = $gridOptionRow['DETAULTVIEWER'];
        
        echo json_encode($result); exit;
    }
    
    public function resetDataViewUserConfig() {
        
        $metaDataId = Input::numeric('metaDataId');
        $userId = Ue::sessionUserId();
        
        $response = $this->model->resetDataViewUserConfigModel($metaDataId, $userId);
        jsonResponse($response);
    }
    
    public function dataViewHelp() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        
        $this->view->dataViewColumnDataFields = $this->model->getDataViewGridAllFieldsModel($this->view->metaDataId);
        $this->view->checkMetaData = $this->model->getCheckMetaDataFieldsModel($this->view->metaDataId);

        $response = array(
            'Html' => $this->view->renderPrint('viewer/detail/dataViewHelp', self::$dataViewPath),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function generateDataviewFields() {
        echo json_encode($this->model->getDataViewGridHeaderModel(Input::numeric('metaDataId'))); exit;
    }
    
    public function getWorkflowNextStatus() {
        $result = $this->model->getWorkflowNextStatusModel();
        echo json_encode($result, JSON_UNESCAPED_UNICODE); 
    }

    public function isWfmStatusAssign($wfmStatusId) {
        $this->load->model('mdobject', 'middleware/models/');
        $isAssign = $this->model->getUseAssignModel($wfmStatusId);
        return ($isAssign == '1' ? true : false);
    }
    
    public function getRowWfmStatusForm($type = '1') {

        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->isSee = Input::post('isSee');
        $this->view->isForm = (!Input::postCheck('form')) ? true : false;
        $this->view->employeeName = Ue::getSessionUserName();
        $this->view->positionName = Ue::getSessionPositionName();
        $this->view->deparmentName = Ue::sessionDepartmentName();
        $this->view->picture = Ue::getSessionPhoto('height="53"');
        $this->view->dataRow = Input::post('dataRow');
        
        if (Input::postCheck('selectedRowData')) {
            $this->view->selectedRowData = Arr::decode($_POST['selectedRowData']);
            
            if ($this->view->selectedRowData) {
                $this->view->dataRow = $this->view->selectedRowData;
            }
        }
        
        if (Input::postCheck('serializeData')) {
            parse_str(Input::post('serializeData'), $this->view->serializeData);
            $this->view->serializeData = $this->view->serializeData['rowdata'];
        }
        
        $this->load->model('mdmeta', 'middleware/models/');
        
        $this->view->dmetaDataId = '1487153693627';
        $this->view->refStructureId = $this->model->getRefStructureIdByMidModel($this->view->metaDataId);
        $this->view->recordId = isset($this->view->dataRow['id']) ? $this->view->dataRow['id'] : '0';
        $this->view->wfmStatusId = isset($this->view->dataRow['wfmstatusid']) ? $this->view->dataRow['wfmstatusid'] : '0';
        $this->view->userKeyId = Ue::sessionUserKeyId();
        $this->view->createdUserId = issetParam($this->view->dataRow['createduserid']);
        
        $this->load->model('mdobject', 'middleware/models/');
        
        $this->view->newWfmStatusId = (Input::postCheck('wfmStatusId')) ? Input::post('wfmStatusId') : $this->view->wfmStatusId;
        $this->view->isUseAssign = $this->model->getUseAssignModel($this->view->wfmStatusId);
        $this->view->getWfmStatus = $this->model->getRowWfmStatusModel($this->view->newWfmStatusId);

        $this->view->isFilePreview = Config::getFromCache('isFilePreview');
        
        if ($this->view->isSee != 'false') {
            
            $this->view->isSentShowWorkflowLog = '0'; //Config::getFromCache('isSentShowWorkflowLog');
            $this->view->isShowTimeSpent = Config::getFromCache('isShowTimeSpentWorkflowLog');
            $this->view->isIgnoreRuleCodeWorkflowLog = Config::getFromCache('isIgnoreRuleCodeWorkflowLog');
        
            if ($type !== '1') {
                $this->view->metaDataId = Input::post('dmmetaDataId');
            }
            
            $this->view->wfmStatusLog = $this->model->getRowWfmStatusLogModel($this->view->metaDataId, array(), $this->view->getWfmStatus['IS_HIDE_NEXT_USER']);
            
            $this->view->wfmStatusNext = (isset($this->view->wfmStatusLog['data']['next']) && !empty($this->view->wfmStatusLog['data']['next'])) ? $this->view->wfmStatusLog['data']['next'] : array();
            
            $this->view->newWfmStatusName = '';
            $this->view->newWfmStatusColor = '';
            
        } else {

            $refStructureId = Input::post('refStructureId');
            
            if (isset($_POST['isMany'])) {
                
                $rowId = $this->view->dataRow[0]['id'];
                $wfmStatusId = issetParam($this->view->dataRow[0]['wfmstatusid']);
                
            } else {
                
                $rowId = $this->view->dataRow['id'];
                $wfmStatusId = issetParam($this->view->dataRow['wfmstatusid']) ? $this->view->dataRow['wfmstatusid'] : issetParam($this->view->dataRow['wfmStatusId']);
            }

            if (Input::post('changeAssign') != 'changeHardAssign') {

                $checkIsSemanticsProcess = $this->model->checkIsSemanticsProcess($this->view->metaDataId, $rowId, Input::post('wfmStatusId'));

                if ($checkIsSemanticsProcess && isset($checkIsSemanticsProcess['processmetadataid'])) {
                    echo json_encode(array(
                        'processMetaDataId' => $checkIsSemanticsProcess['processmetadataid']
                    ));
                    exit;
                }
            }

            $this->view->newWfmStatusName = Input::post('newWfmStatusName');
            $this->view->newWfmStatusColor = Input::post('newWfmStatusColor');

            $this->view->wfmStatusAssignment = $this->model->getWfmStatusAssignmentModel($wfmStatusId, $refStructureId, $rowId);
        }
        
        if ($type === '1') {
            
            $html = $this->view->renderPrint('common/sub/wfmStatusForm', self::$mainViewPath);

            $width = '800';
            $processOther = '';
            
            $response = array(
                'Html' => $html,
                'Width' => $width,
                'fullscreen' => ($this->view->isSee === 'false') ? (($this->view->isFilePreview == '1' && $this->view->getWfmStatus['IS_FILE_PREVIEW']) ? 1 : 0) : 0,
                'ProcessOther' => $processOther,
                'Title' => ($this->view->isSee === 'false') ? Lang::line('wfm_log_changehistory') : Lang::line('wfm_log_history'),
                'save_btn' => $this->lang->line('save_btn'),
                'close_btn' => $this->lang->line('close_btn')
            );
            
            jsonResponse($response);
            
        } else {
            $this->view->render('common/sub/wfmStatusForm', self::$mainViewPath);
        }
    }

    public function setRowWfmStatus() {
        $result = $this->model->setRowWfmStatusModel();
        convJson($result);
    }
    
    public function checkMetaDataType() {
                
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->mainMetaDataId = Input::numeric('mainMetaDataId');
        $_POST['uriParams'] = urldecode(Input::post('uriParams'));
        
        $metaRow = $this->model->getMetaTypeLinkDataModel($this->view->metaDataId);
        $response = array();
        
        if ($metaRow['META_TYPE_ID'] == Mdmetadata::$metaGroupMetaTypeId) {
            
            ob_start(); 
            
            $_POST['ignorePermission'] = 1;
            self::dataview($this->view->metaDataId);
            
            $dataViewHtml = ob_get_clean(); 
            
            $response = array('Html' => $dataViewHtml, 'fullscreen' => true);
            
        } elseif ($metaRow['META_TYPE_ID'] == Mdmetadata::$statementMetaTypeId) {
            
            $response = array('Html' => (new Mdstatement())->index($this->view->metaDataId, true), 'fullscreen' => true);
            
        } elseif ($metaRow['META_TYPE_ID'] == Mdmetadata::$workSpaceMetaTypeId) {
            
            $response = array('Html' => (new Mdworkspace())->index($this->view->metaDataId, '0'), 'fullscreen' => true);
            
        } elseif ($metaRow['META_TYPE_ID'] == Mdmetadata::$businessProcessMetaTypeId) {
            
            if (Input::post('isDialog') == 'true') {
                $response = array('webservice' => true);
            } else {
                $_POST['isDialog'] = 'true';
                
                if (Input::isEmpty('uriParams') == false) {
                    $_POST['defaultGetParams'] = 'defaultgetpf=1&' . Input::post('uriParams');
                }
                
                $response = array('Html' => (new Mdwebservice())->callMethodByMeta($this->view->metaDataId, $this->view->mainMetaDataId, '0'), 'fullscreen' => true, 'webservice' => false);
            }
            
        } elseif ($metaRow['META_TYPE_ID'] == Mdmetadata::$bookmarkMetaTypeId) {
            
            if (strtolower($metaRow['BOOKMARK_URL']) == 'easervicedataviewpivotview' && Input::isEmpty('uriParams') == false) {
                
                parse_str($_POST['uriParams'], $uriParams);
                $uriParams = Arr::changeKeyLower($uriParams);
                
                $_POST = $uriParams; 
                $_POST['resultArray'] = 1; 
                $pivot = (new Mdpivot())->dataViewPivotView(issetParam($uriParams['metadataid']), issetParam($uriParams['templateid']));
                
                $response = array('Html' => $pivot['html']);
            }
            
        } elseif ($metaRow['META_TYPE_ID'] == Mdmetadata::$diagramMetaTypeId) {
            
            (new Mddashboard())->diagramRenderByPost();
            
        }
        
        echo json_encode($response); exit;
    }
    
    public function dataViewFolderChildList() {
        
        $this->view->dataViewId = Input::post('dataViewId'); 
        $this->view->refStructureId = Input::post('refStructureId'); 
        $this->view->folderId = Input::post('folderId'); 
        $this->view->filtedField = Input::post('filtedField');
        
        $this->view->photoField = Input::post('photoField'); 
        $this->view->iconField = Input::post('iconField'); 
        $this->view->defaultImage = Input::post('defaultImage');
        $this->view->name1 = Input::post('name1');
        $this->view->name2 = Input::post('name2');
        $this->view->name3 = Input::post('name3');
        $this->view->name4 = Input::post('name4');
        $this->view->name5 = Input::post('name5');
        $this->view->name6 = Input::post('name6');
        $this->view->name7 = Input::post('name7');
        $this->view->name8 = Input::post('name8');
        $this->view->name9 = Input::post('name9');
        $this->view->name10 = Input::post('name10');
        $this->view->body = Input::post('body');
        
        $this->view->groupName = Input::post('groupName');
        $this->view->clickRowFunction = Input::post('clickRowFunction');
        $this->view->layoutTheme = Input::post('layoutTheme');
        
        $dataGridOptionData = Input::post('dataGridOptionData');
        $this->view->dataGridOptionData = $dataGridOptionData;
        
        $this->view->isTreeGridData = null;
        
        $this->view->row = $this->model->getDataViewConfigRowModel($this->view->dataViewId);
        
        if (empty($this->view->refStructureId)) {
            $this->view->refStructureId = $this->view->row['REF_STRUCTURE_ID'];
        }

        if ($this->view->refStructureId) {
            
            $this->view->folderList = $this->model->getRefStructureListModel($this->view->refStructureId, $this->view->folderId);
            
        } else {
            
            $this->view->folderList = array();
            $this->view->isTreeGridData = $this->view->row['TREE_GRID'];
            $this->view->parentId = Input::numeric('parentId');
            
            if ($this->view->isTreeGridData && ($this->view->layoutTheme == 'explorer11')) {
                $_POST['treeConfigs'] = $this->view->isTreeGridData;
                $_POST['id'] = $this->view->folderId;
            }
        }

        $_POST['sort']  = $dataGridOptionData['SORTNAME'];
        $_POST['order'] = $dataGridOptionData['SORTORDER'];
        
        if ($this->view->layoutTheme == 'pivot_view') {
            
            $_POST['metaDataId'] = $this->view->dataViewId;
            $_POST['rows']       = $dataGridOptionData['PAGESIZE'];
            
            $this->view->header = $this->model->getDataViewGridAllFieldsModel($this->view->dataViewId);
            $this->view->header = Arr::groupByArray($this->view->header, 'FIELD_PATH');
            
            $this->view->columnName = strtolower(issetParam($this->view->row['dataViewLayoutTypes']['explorer']['fields']['columnName']));
            $this->view->color = issetParam($this->view->row['dataViewLayoutTypes']['explorer']['fields']['color']);            
            $this->view->defaultCriteriaData = Input::post('defaultCriteriaData');            
            $this->view->drillDownDefaultCriteria = Input::post('drillDownDefaultCriteria');            
            
            $this->view->isAllShowField = issetParam($this->view->row['dataViewLayoutTypes']['explorer']['fields']['allshowfield']);
            
            if ($this->view->isAllShowField) {
                
                $_POST['page'] = 1;
                $_POST['rows'] = issetDefaultVal($this->view->dataGridOptionData['PAGESIZE'], 200);
            }
            
            $this->view->recordList = $this->model->dataViewDataGridModel();
            
            if ($this->view->recordList['status'] == 'success' && isset($this->view->recordList['rows'])) {
                
                $this->view->totalCount = $this->view->recordList['total'];
                $this->view->recordList = $this->view->recordList['rows'];
                $this->view->pureRecordList = $this->view->recordList;
                
                if ($this->view->groupName && $this->view->recordList && isset($this->view->recordList[0][$this->view->groupName])) {
                    
                    $this->view->recordList = Arr::groupByArray($this->view->recordList, $this->view->groupName);
                    
                    $getCountCardData = $this->model->getCountCardDataModel($this->view->dataViewId, $this->view->groupName);
                    $this->view->totalCount = $getCountCardData ? count($getCountCardData) : 0;
                    
                    if ($columnDvId = issetParam($this->view->row['dataViewLayoutTypes']['explorer']['fields']['metadataid'])) {
                        
                        $columnData = $this->model->dataViewDataGridModel(true, $columnDvId);
                        
                        if (isset($columnData['rows'][0][$this->view->columnName])) {
                            $this->view->columnData = $columnData['rows'];
                        }
                    }
                }  
                
            } else {
                $this->view->recordList = null;
            }              
            
            $this->view->render('viewer/explorer/layout/'.$this->view->layoutTheme, self::$dataViewPath);
            exit;
            
        } elseif ($this->view->layoutTheme == 'kanban_board') {
            
            $this->view->mid = $this->view->dataViewId;
            $this->view->metaDataCode = $this->view->row['META_DATA_CODE'];
            $this->view->refStructureId = $this->view->row['REF_STRUCTURE_ID'];

            $this->view->dataViewWorkFlowBtn = ($this->view->row['COUNT_WFM_WORKFLOW'] != '0' && $this->view->row['IS_USE_WFM_CONFIG'] == '1') ? true : false;
            $this->view->dataViewProcessCommand = $this->model->dataViewProcessCommandModel($this->view->mid, $this->view->metaDataCode, false);
        
            $this->view->render('viewer/explorer/layout/kanban_board', self::$dataViewPath);
            exit;  
            
        } elseif ($this->view->layoutTheme == 'ganttchart') {
            
            $this->view->uid = getUID();
            $this->view->metaDataCode = $this->view->row['META_DATA_CODE'];
            $this->view->dataViewProcessCommand = $this->model->dataViewProcessCommandModel($this->view->dataViewId, $this->view->metaDataCode, false, false, $this->view->dataViewId);

            $this->view->fieldConfigs = $this->model->getDataViewGridCriteriaRowModel($this->view->dataViewId, 'pf_all_field');
            
            $this->view->name1 = issetParam($this->view->row['dataViewLayoutTypes']['explorer']['fields']['name1']);
            $this->view->name2 = issetParam($this->view->row['dataViewLayoutTypes']['explorer']['fields']['name2']);
            $this->view->name3 = issetParam($this->view->row['dataViewLayoutTypes']['explorer']['fields']['name3']);
            
            $this->view->defaultCriteriaData = base64_encode(Input::post('defaultCriteriaData'));
            
            $this->view->render('viewer/explorer/layout/ganttchart', self::$dataViewPath);
            exit;
        } 

        $this->view->recordList = $this->model->getDataViewRecordListModel($this->view->dataViewId, $this->view->filtedField, $this->view->folderId);
        $this->view->pureRecordList = $this->view->recordList;

        $this->view->isGrouped = false;
        
        if ($this->view->groupName && $this->view->recordList && isset($this->view->recordList[0][$this->view->groupName])) {
            $this->view->isGrouped = true;
            $this->view->recordList = Arr::groupByArray($this->view->recordList, $this->view->groupName);
        }
        
        $this->view->isBack = true;
        
        if (empty($this->view->folderId)) {
            $this->view->isBack = false;
        }               
        
        /*if ($this->view->dataViewId == '1477031482440') {
            $this->view->layoutTheme = 'orgchart_new';
        }*/
        
        if ($this->view->layoutTheme == 'orgchart' || $this->view->layoutTheme == 'orgchartmultiparent') {
            
            $this->view->title = Lang::line($this->view->row['LIST_NAME']);
            
            $id = $this->view->row['dataViewLayoutTypes']['explorer']['fields']['id'];
            $parent = $this->view->row['dataViewLayoutTypes']['explorer']['fields']['parent'];
            
            $name1 = $this->view->row['dataViewLayoutTypes']['explorer']['fields']['name1'];
            $name2 = $this->view->row['dataViewLayoutTypes']['explorer']['fields']['name2'];
            
            $this->view->dataSource = $this->model->buildOrgChartDataSource($this->view->row['dataViewLayoutTypes']['explorer']['fields'], $this->view->recordList, $id, $parent, $name1, $name2);

            $this->view->render('viewer/explorer/layout/'.$this->view->layoutTheme, self::$dataViewPath);

        } elseif ($this->view->layoutTheme == 'bank_card' || $this->view->layoutTheme == 'bank_card2') {
            
            $this->view->metaDataCode = $this->view->row['META_DATA_CODE'];
            $this->view->drillDownLink = $this->model->getExplorerDrillDownLinkModel($this->view->dataViewId, $this->view->metaDataCode);
            $this->view->allField = $this->model->getDataViewGridCriteriaRowModel($this->view->dataViewId, 'pf_all_field');

            $this->view->render('viewer/explorer/layout/'.$this->view->layoutTheme, self::$dataViewPath);
            
        } elseif ($this->view->layoutTheme == 'mindchart') {
        
            $this->view->title = Lang::line($this->view->row['LIST_NAME']);
            
            $id = $this->view->row['dataViewLayoutTypes']['explorer']['fields']['id'];
            $parent = $this->view->row['dataViewLayoutTypes']['explorer']['fields']['parent'];
            
            $name1 = $this->view->row['dataViewLayoutTypes']['explorer']['fields']['name1'];
            $name2 = '';
            
            $this->view->dataSource = $this->model->buildMindChartDataSource($this->view->row['dataViewLayoutTypes']['explorer']['fields'], $this->view->recordList, $id, $parent, $name1, $name2);

            $this->view->render('viewer/explorer/layout/'.$this->view->layoutTheme, self::$dataViewPath);            
            
        } elseif ($this->view->layoutTheme == 'carousel1' || $this->view->layoutTheme == 'carousel2' || $this->view->layoutTheme == 'carousel3' || $this->view->layoutTheme == 'carousel_marquee' || $this->view->layoutTheme == 'carousel_marquee_inline') {
            
            $this->view->name1 = $this->view->row['dataViewLayoutTypes']['explorer']['fields']['name1'];
            $this->view->name2 = issetParam($this->view->row['dataViewLayoutTypes']['explorer']['fields']['name2']);
            $this->view->photo = issetParam($this->view->row['dataViewLayoutTypes']['explorer']['fields']['photo']);
            
            $this->view->render('viewer/explorer/layout/'.$this->view->layoutTheme, self::$dataViewPath);
            
        } elseif ($this->view->layoutTheme == 'cleangrid' || $this->view->layoutTheme == 'tasklist') {
            
            $this->view->header = $this->model->getDataViewGridAllFieldsModel($this->view->dataViewId);
            
            $this->view->render('viewer/explorer/layout/'.$this->view->layoutTheme, self::$dataViewPath);
            
        } elseif (preg_match('/sv_/', $this->view->layoutTheme)) {
            
            $this->view->parentField = $this->model->getStandartFieldModel($this->view->dataViewId, 'parent_id');
            $this->view->layoutPath = $this->model->getGridLayoutPathModel($this->view->dataViewId);
            $this->view->gridLayout = $this->model->getGridLayoutModel($this->view->dataViewId);
            
            if ($this->view->layoutPath) {
                $this->view->layoutPath = Arr::groupByArrayOnlyRow($this->view->layoutPath, 'POSITION_NAME', 'FIELD_PATH');
            }
            
            $this->view->render('viewer/explorer/social/explorer_v2', self::$dataViewPath);
            
        } elseif ($this->view->layoutTheme == 'commentview') {
            
            $this->view->dataViewProcessCommand = $this->model->dataViewProcessCommandModel($this->view->dataViewId, $this->view->row['META_DATA_CODE'], false);
            
            $this->view->nameLinkId1 = issetParam($this->view->row['dataViewLayoutTypes']['explorer']['fields']['__linkid']['name1']);
            $this->view->nameLinkId2 = issetParam($this->view->row['dataViewLayoutTypes']['explorer']['fields']['__linkid'][$this->view->name2]);
            $this->view->nameLinkId3 = issetParam($this->view->row['dataViewLayoutTypes']['explorer']['fields']['__linkid'][$this->view->name3]);
            $this->view->nameLinkId4 = issetParam($this->view->row['dataViewLayoutTypes']['explorer']['fields']['__linkid'][$this->view->name4]);
            $this->view->nameLinkId5 = issetParam($this->view->row['dataViewLayoutTypes']['explorer']['fields']['__linkid'][$this->view->name5]);
            $this->view->nameLinkId6 = issetParam($this->view->row['dataViewLayoutTypes']['explorer']['fields']['__linkid'][$this->view->name6]);
            $this->view->nameLinkId7 = issetParam($this->view->row['dataViewLayoutTypes']['explorer']['fields']['__linkid'][$this->view->name7]);
            
            $this->view->render('viewer/explorer/layout/'.$this->view->layoutTheme, self::$dataViewPath);
            
        } elseif ($this->view->layoutTheme == 'postview') {
            
            $this->view->metaDataCode = $this->view->row['META_DATA_CODE'];
            $this->view->dataViewProcessCommand = $this->model->dataViewProcessCommandModel($this->view->dataViewId, $this->view->metaDataCode, false);            
            $this->view->render('viewer/explorer/layout/'.$this->view->layoutTheme, self::$dataViewPath);
        
        } elseif ($this->view->layoutTheme == 'hr_comment_view') {
            
            $this->view->comment = issetParam($this->view->row['dataViewLayoutTypes']['explorer']['fields']['comment']);
            $this->view->nameLinkId4 = issetParam($this->view->row['dataViewLayoutTypes']['explorer']['fields']['__linkid'][$this->view->comment]);
            if (isset($this->view->row['dataViewLayoutTypes']['explorer']['fields']['name10']) && $this->view->row['dataViewLayoutTypes']['explorer']['fields']['name10'] != '') {
                $this->view->name10 = strtolower($this->view->row['dataViewLayoutTypes']['explorer']['fields']['name10']);
            }
            $this->view->js = array(
                'assets/custom/js/isotope.pkgd.min.js',
            );
            $this->view->render('viewer/explorer/layout/'.$this->view->layoutTheme, self::$dataViewPath);
        
        } elseif ($this->view->layoutTheme == 'hr_worktask_view') {

            $this->view->name5 = issetParam($this->view->row['dataViewLayoutTypes']['explorer']['fields']['name5']);
            $this->view->render('viewer/explorer/layout/'.$this->view->layoutTheme, self::$dataViewPath);
        
        } elseif ($this->view->layoutTheme == 'feedback_comment_view') {
            
            $this->view->name5 = issetParam($this->view->row['dataViewLayoutTypes']['explorer']['fields']['name5']);
            $this->view->render('viewer/explorer/layout/'.$this->view->layoutTheme, self::$dataViewPath);
        
        } elseif ($this->view->layoutTheme == 'taskboard') {

            $groupId = issetDefaultVal($this->view->row['dataViewLayoutTypes']['explorer']['fields']['metaid'], 1539738377128981);
            $this->view->columnList = $this->model->getTaskBoardColumnListModel(Str::lower($groupId), $this->view->recordList, Input::post('workSpaceParams'));

            $this->view->render('viewer/explorer/layout/taskboard', self::$dataViewPath);
            
        } elseif ($this->view->layoutTheme == 'taskboard_new') {

            $groupId = issetDefaultVal($this->view->row['dataViewLayoutTypes']['explorer']['fields']['metaid'], 1577238633751);
            $this->view->columnList = $this->model->getTaskBoardColumnListModel(Str::lower($groupId), $this->view->recordList, Input::post('workSpaceParams'));

            $this->view->render('viewer/explorer/layout/taskboard2', self::$dataViewPath);

        } elseif ($this->view->layoutTheme == 'taskboard1') {
            
            $groupId = issetDefaultVal($this->view->row['dataViewLayoutTypes']['explorer']['fields']['metaid'], 1539738377128981);
            $this->view->columnList = $this->model->getTaskBoardColumnListModel(Str::lower($groupId), $this->view->recordList);
            
            $this->view->render('viewer/explorer/layout/taskboard1', self::$dataViewPath);
        
        } elseif ($this->view->layoutTheme == 'lifecycle_card') {
            
            $idField = $this->view->row['idField'] ? $this->view->row['idField'] : 'id';
            $nameField = $this->view->row['nameField'] ? $this->view->row['nameField'] : 'name';
            $parentField = $this->view->row['parentField'] ? $this->view->row['parentField'] : 'parentid';
            
            $this->view->columnList = $this->model->getLifecycleHierarchyCardModel($this->view->dataViewId, $this->view->recordList, $idField, $nameField, $parentField);

            $this->view->render('viewer/explorer/layout/lifecycle_card', self::$dataViewPath);

        } else {
            $this->view->render('viewer/explorer/layout/'.$this->view->layoutTheme, self::$dataViewPath);
        } 
    }
    
    public function getGanttChartData($dataViewId) {
        
        if (!is_numeric($dataViewId)) {
            echo json_encode(array('data' => array())); exit;
        }
        
        $row = $this->model->getDataViewConfigRowModel($dataViewId);
        $relationDvId = issetParam($row['dataViewLayoutTypes']['explorer']['fields']['relationDvId']);
        $parentId = Input::get('parent_id');
        $isParentFilter = Input::get('isParentFilter');
        
        $_POST['metaDataId']  = $dataViewId;
        $_POST['treeGrid']    = 1;
        $_POST['treeConfigs'] = $row['TREE_GRID'];
        $_POST['rows']        = 1000;
        
        if (is_numeric($parentId)) {
            $_POST['id'] = $parentId;
        }
        
        if ($isParentFilter == 1) {
            $_POST['isParentFilter'] = 1;
        }
        
        if ($param = Input::get('param')) {
            $_POST['defaultCriteriaData'] = base64_decode($param);
        }
        
        $result = $this->model->dataViewDataGridModel(true);
        
        if (isset($result['status']) && $result['status'] == 'success' && isset($result['rows'])) {
            $recordList = $result['rows'];
        } elseif (isset($result[0])) {
            $recordList = $result;
        } else {
            echo json_encode(array('status' => 'error', 'message' => $result['message'])); exit;
        }
        
        $dataSource = $this->model->buildGanttChartDataSource($row, $recordList);
        $dataSourceJson = array('data' => $dataSource);

        if ($relationDvId) {
            
            unset($_POST['treeGrid']);
            unset($_POST['treeConfigs']);
            unset($_POST['id']);
            
            $_POST['metaDataId'] = $relationDvId;
            $_POST['rows']       = 10000;

            $result = $this->model->dataViewDataGridModel(true);

            if ($result['status'] == 'success' && isset($result['rows'][0])) {
                $dataSourceJson['links'] = $result['rows'];
            }
        }
        
        echo json_encode($dataSourceJson, JSON_UNESCAPED_UNICODE);
    }
    
    public function explorerBackList() {
        
        $refStructureId = Input::post('refStructureId');
        $folderId = Input::post('folderId');
        
        $row = $this->model->getParentFolderByExplorerModel($refStructureId, $folderId);

        $response = array(
            'folderId' => $row['PARENT_ID']
        );
        echo json_encode($response); exit;
    }
    
    public function explorerSidebar() {

        $this->view->dataViewId = Input::post('dataViewId');
        $this->view->refStructureId = Input::post('refStructureId');
        $this->view->selectedRow = Input::post('selectedRow');
        $this->view->recordId = isset($this->view->selectedRow['id']) ? $this->view->selectedRow['id'] : null;
        $this->view->wfmStatusId = isset($this->view->selectedRow['wfmstatusid']) ? $this->view->selectedRow['wfmstatusid'] : null;
        
        $this->view->dataGridHeadData = $this->model->getDataViewGridHeaderModel($this->view->dataViewId, '1 = 1', 2);
        $this->view->sidebarDataviewList = $this->model->getSidebarDataviewListModel($this->view->dataViewId);

        if ($this->view->wfmStatusId) {
            $this->view->logUser = $this->model->getWfmLogLastCreatedModel($this->view->refStructureId, $this->view->recordId, $this->view->wfmStatusId);
            $this->view->assignmentUsers = $this->model->getWfmStatusAssignmentModel($this->view->wfmStatusId, $this->view->refStructureId, $this->view->recordId);

            $this->view->wfmStatusButtons = (new Mdworkflow())->getWorkflowNextStatus($this->view->dataViewId, $this->view->selectedRow, $this->view->refStructureId);
            $this->view->workflow = $this->view->renderPrint('viewer/explorer/workflow', self::$dataViewPath);

            $this->view->assignment = $this->view->renderPrint('viewer/explorer/assignment', self::$dataViewPath);
            
        } else {
            $this->view->workflow = null;
            $this->view->assignment = null;
        }
        
        $this->view->render('viewer/explorer/sidebar', self::$dataViewPath);
    }
    
    public function explorerSidebarPreview() {

        $this->view->dataViewId = Input::post('dataViewId');
        $this->view->refStructureId = Input::post('refStructureId');
        $this->view->selectedRow = Input::post('selectedRow');
        
        $this->view->recordId = isset($this->view->selectedRow['id']) ? $this->view->selectedRow['id'] : null;
        
        $this->view->dataGridHeadData = $this->model->getDataViewGridHeaderModel($this->view->dataViewId, '1 = 1', 2);
        $this->view->sidebarDataviewList = $this->model->getSidebarDataviewListModel($this->view->dataViewId);
        
        $this->view->render('viewer/explorer/sidebarPreview', self::$dataViewPath);
    }
    
    public function getFolderRecord() {

        $result = array();
        $refStructureId = Input::post('refStructureId');
        $metaValueId = Input::post('metaValueId');

        $folderRecord = $this->model->getFolderRecord($refStructureId, $metaValueId);
        if (isset($folderRecord[0])) {
            $result = $folderRecord[0];
        }

        echo json_encode($result); exit;
    }

    public function getDataViewValueRowByMetaCode($metaDataCode, $fieldName, $value) {
        
        $this->load->model('mdmetadata', 'middleware/models/');
        $dataViewId = $this->model->getMetaDataIdByCodeModel($metaDataCode);
        
        $this->load->model('mdobject', 'middleware/models/');
        
        $criteria[$fieldName][] = array(
            'operator' => '=',
            'operand' => $value
        );

        $result = $this->model->getDataViewByCriteriaModel($dataViewId, $criteria);
        
        if ($result) {
            
            $idField = strtolower($this->model->getDataViewMetaValueId($dataViewId));
            $codeField = strtolower($this->model->getDataViewMetaValueCode($dataViewId));
            $nameField = strtolower($this->model->getDataViewMetaValueName($dataViewId));

            $row = array(
                'META_VALUE_ID' => ($idField ? $result[$idField] : (isset($result['id']) ? $result['id'] : '')),
                'META_VALUE_CODE' => (isset($result[$codeField]) ? htmlentities($result[$codeField], ENT_QUOTES, 'UTF-8') : ''),
                'META_VALUE_NAME' => (isset($result[$nameField]) ? htmlentities($result[$nameField], ENT_QUOTES, 'UTF-8') : '')
            );
            
            return $row;
        }
        
        return null;
    }
    
    public function dataViewUseBasketView() {
        
        $this->view->uniqId = getUID();
        $this->view->metaDataId = Input::numeric('metaDataId');
        $workSpaceId = Input::numeric('workSpaceId');
        $workSpaceParams = Input::post('workSpaceParams');
        $uriParams = Input::post('uriParams');
        $permissionCriteria = Input::post('permissionCriteria');
        $dataGridDefaultHeight = Input::post('dataGridDefaultHeight');
        $calendarParams = Input::post('calendarParams');

        $item = array();
        
        if (Input::postCheck('selectedRows')) {
            $selectedRows = Input::post('selectedRows');
            foreach ($selectedRows as $key1 => $row) {
                $row['action'] = '<a data-index-row='. $key1 .' href="javascript:;" onclick="deleteSelectableBasketWindow_'. $this->view->metaDataId .'(this);" class="btn btn-xs red" style="padding-top: 0px;margin-top: -2px;" title="'.$this->lang->line('META_00002').'"><i class="far fa-trash"></i></a>';
                array_push($item, $row);
            }
        }
        
        $this->view->selectedBasketRows = json_encode($item);
        
        $content = self::detailDataViewer($this->view->metaDataId, $workSpaceId, $workSpaceParams, $uriParams, '0', true, $permissionCriteria, $dataGridDefaultHeight, $calendarParams, true, $this->view->uniqId, '1');
        
        jsonResponse(array(
            'Title'      => 'Сагсанд', 
            'Html'       => $content, 
            'metaDataId' => $this->view->metaDataId, 
            'save_btn'   => $this->lang->line('save_btn'),
            'close_btn'  => $this->lang->line('close_btn')
        ));
    }
    
    public function dataViewInlineEditProcess() {
        $metaDataId = Input::numeric('metaDataId');
        $param = Input::post('row');
        $getInlineEditMapDataGroup = $getProcessParamsGroup = $processMaps = array();        
        
        $getProcess = $this->model->dataViewInlineEditProcessdModel($metaDataId, Input::post('actionType'));
        
        if ($getProcess['status'] === 'success') {
            
            $this->load->model('mdobject', 'middleware/models/');
            $getInlineEditMapData = $this->model->getInlineEditMapConfig($metaDataId);

            if ($getInlineEditMapData) {
                $getInlineEditMapDataGroup = Arr::groupByArrayOnlyRow($getInlineEditMapData, 'SRC_PARAM_PATH', 'TRG_PARAM_PATH');
                $getProcessParamsGroup = Arr::groupByArray(Mdwebservice::groupParamsData($getProcess['PROCESS_META_DATA_ID']), 'META_DATA_CODE');
                
                foreach ($param as $pk => $pv) {
                    if (isset($getInlineEditMapDataGroup[$pk])) {
                        $processMaps[$getInlineEditMapDataGroup[$pk]] = $pv;
                    }
                }
            }            
            $param = array_merge($param, $processMaps);
            
            $this->load->model('mdwebservice', 'middleware/models/');
            
            $row = $this->model->getMethodIdByMetaDataModel($getProcess['PROCESS_META_DATA_ID']);
            $result = (new Mdwebservice)->call($row, $param);
            
            if ($this->ws->isException()) {
                
                $response = array('status' => 'error', 'message' => $this->ws->getErrorMessage());

            } else {

                if ($result['status'] == 'success') {
                    $response = array('status' => 'success', 'message' => Lang::line('msg_save_success'));
                } else {
                    $response = array('status' => 'error', 'message' => $this->ws->getResponseMessage($result));
                }
            }            
            
        } else {
            $response = $getProcess;
        }
        
        jsonResponse($response);
    }
    
    public function dataViewInlineEditCombo($id, $value, $name) {
        $inputMetaDataId = $id;
        $param = array(
            'systemMetaGroupId' => $inputMetaDataId,
            'showQuery' => 0, 
            'ignorePermission' => 1 
        );
        
        $cache = phpFastCache();
        
        $array = $cache->get('dvErlData_' . $inputMetaDataId);
        
        if ($array == null) {

            $result = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

            $array = array();

            if ($result['status'] == 'success' && isset($result['result'])) {
                
                unset($result['result']['aggregatecolumns']);
                unset($result['result']['paging']);

                foreach ($result['result'] as $k => $v) {
                    $array[$k]['META_VALUE_ID'] = $v[$value];
                    $array[$k]['META_VALUE_NAME'] = $v[$name];
                    //$array[$k]['ROW_DATA'] = $v;
                }
                
                $cache->set('dvErlData_' . $inputMetaDataId, $array, Mdwebservice::$expressionCacheTime);
            }
        }
        
        echo jsonResponse($array);        
    }

    public function dataViewSetOrder() {
        $mainDvId = $this->model->getStandartFieldModel(Input::post('dataViewId'), 'meta_value_id');
        $rows = $_POST['rows'];
        $rowsCount = count($rows);
        $currentOrder = (int) Input::post('currentOrder');
        $origPrevOrder = 0;

        if (isset($_POST['prevOrder']) && $_POST['prevOrder'] == 0) {
            $prevOrder = 0;
            $origPrevOrder = 0;
        } elseif (!Input::isEmpty('prevOrder')) {
            $prevOrder = (int) Input::post('prevOrder');
            $origPrevOrder = $prevOrder;
        }

        if (!isset($prevOrder)) {
            $prevOrder = 1;
            $origPrevOrder = 0;
        } else {
            $prevOrder++;
            $prevOrder++;
            $origPrevOrder++;
        }

        $this->db->StartTrans();
        
        $ii = $prevOrder;
        $ii++;
        
        if ($origPrevOrder <= $currentOrder) {
            if (isset($rows[$currentOrder]['ordertablename']) && isset($rows[$currentOrder]['orderordercolumn']) && isset($rows[$currentOrder]['orderidcolumn'])) {
                $this->db->Execute('UPDATE ' . $rows[$currentOrder]['ordertablename'] . ' SET ' . $rows[$currentOrder]['orderordercolumn'] . ' = ' . $prevOrder . ' WHERE ' . $rows[$currentOrder]['orderidcolumn'] . ' = ' . $rows[$currentOrder][$mainDvId]);
                for ($i = $origPrevOrder; $i < $rowsCount; $i++) {
                    if ($currentOrder == $i) {
                        continue;
                    }
                    echo $i . ' --- ';
                    echo $ii.'<br>';
                    // echo 'UPDATE ' . $rows[$i]['ordertablename'] . ' SET ' . $rows[$i]['orderordercolumn'] . ' = ' . $ii . ' WHERE ' . $rows[$i]['orderidcolumn'] . ' = ' . $rows[$i][$mainDvId]; die;
                    $this->db->Execute('UPDATE ' . $rows[$i]['ordertablename'] . ' SET ' . $rows[$i]['orderordercolumn'] . ' = ' . $ii . ' WHERE ' . $rows[$i]['orderidcolumn'] . ' = ' . $rows[$i][$mainDvId]);
                    $ii++;
                }
            }


        } else {
            $prevOrder--;
            $this->db->Execute('UPDATE ' . $rows[$currentOrder]['ordertablename'] . ' SET ' . $rows[$currentOrder]['orderordercolumn'] . ' = ' . $prevOrder . ' WHERE ' . $rows[$currentOrder]['orderidcolumn'] . ' = ' . $rows[$currentOrder][$mainDvId]);

            $ii = $currentOrder;
            $ii++;
            // $ii++;
            // if ($currentOrder != 0) {
            // }
            $currentOrder++;
            // $ii++;
            // $currentOrder++;            
            for ($i = $currentOrder; $i < $rowsCount; $i++) {
                // echo $i . ' down ';
                // echo $ii;
                if ($prevOrder == $i) {
                // //     // $ii++;
                    $ii++;
                // //     // continue;
                }
                // echo $ii; die;
                // echo $i . ' | ';
                // echo ($ii > $origPrevOrder ? $ii : $i) . ' ';
                $this->db->Execute('UPDATE ' . $rows[$i]['ordertablename'] . ' SET ' . $rows[$i]['orderordercolumn'] . ' = ' . $ii . ' WHERE ' . $rows[$i]['orderidcolumn'] . ' = ' . $rows[$i][$mainDvId]);
                $ii++;
            }            
        }

        $this->db->CompleteTrans();
    }
    
    public function dataViewHeaderDataCtl($metaDataId) {
        $this->load->model('mdobject', 'middleware/models/');
        return $this->model->dataViewHeaderDataModel($metaDataId); 
    }
    
    public function dataViewPrintExportData($metaDataId, $selectedRows = array(), $footerSum = false, $header = false) {
        
        $this->load->model('mdobject', 'middleware/models/');
        
        if (!$selectedRows) {
            
            $_POST['ignorePermission'] = 1;
            $_POST['isResponseCriteria'] = 1;
        
            $exportData = $this->model->dataViewDataGridModel(false);
            
        } else {
            $exportData = $selectedRows;
        }

        if ($exportData['status'] == 'error') {
            return array('status' => 'error', 'message' => $exportData['message']);
        }
        
        if (!isset(Mdobject::$exportHeaderConfig[$metaDataId])) {
        
            $headerData = $this->model->getDataViewGridHeaderModel($metaDataId, "(IS_IGNORE_EXCEL IS NULL OR IS_IGNORE_EXCEL = 0) AND META_TYPE_CODE <> 'file'");

            $mergeArr = $secondHeadRow = $columnWidthArr = $mergeColWidth = array();
            $headerRow = 1;
            
            $hdrHtml = '<table border="1" cellpadding="3" style="border-collapse: collapse">';
            $hdrHtml .= '<thead>';
            $hdrHtml .= '<tr>';
            $hdrHtml .= '<th style="background-color: #cccccc; width: 20px; border: 1px solid #000000; vertical-align: middle" valign="middle" datarowspan="0">№</th>';

            foreach ($headerData as $key => $row) {

                if ($row['SIDEBAR_NAME']) {

                    if (!isset($mergeArr[$row['SIDEBAR_NAME']])) {
                        $hdrHtml .= '<th style="text-align: center; background-color: #cccccc;font-weight: 600; border: 1px solid #000000;width'.$row['SIDEBAR_NAME'].'" datacolspan="'.$row['SIDEBAR_NAME'].'">'.$this->lang->line($row['SIDEBAR_NAME']).'</th>';
                        $mergeArr[$row['SIDEBAR_NAME']] = 1;
                    } else {
                        $mergeArr[$row['SIDEBAR_NAME']] += 1;
                    }

                    $secondHeadRow[$row['SIDEBAR_NAME']][] = $row;

                } else {
                    
                    if ($row['COLUMN_WIDTH']) {
                        
                        $style = 'width: '.$row['COLUMN_WIDTH'];
                        $columnWidthArr[$row['FIELD_PATH']] = $row['COLUMN_WIDTH'];
                        
                    } else {
                        
                        $style = 'width: 120px';
                        $columnWidthArr[$row['FIELD_PATH']] = '120px';
                    }
                    
                    $hdrHtml .= '<th style="text-align: center; background-color: #cccccc;font-weight: 600;border: 1px solid #000000;'.$style.'" datarowspan="0">'.$this->lang->line($row['LABEL_NAME']).'</th>';
                }
            }

            $hdrHtml .= '</tr>';

            if ($mergeArr) {

                $hdrHtml = str_replace('datarowspan="0"', 'rowspan="2"', $hdrHtml);

                foreach ($mergeArr as $mergeName => $mergeCount) {
                    $hdrHtml = str_replace('datacolspan="'.$mergeName.'"', 'colspan="'.$mergeCount.'"', $hdrHtml);
                }

                $hdrHtml .= '<tr>';

                foreach ($secondHeadRow as $secondName => $secondRow) {
                    foreach ($secondRow as $secondCol) {
                        
                        if ($secondCol['COLUMN_WIDTH']) {
                        
                            $style = 'width: '.$secondCol['COLUMN_WIDTH'];
                            $columnWidthArr[$secondCol['FIELD_PATH']] = $secondCol['COLUMN_WIDTH'];

                        } else {

                            $style = 'width: 120px';
                            $columnWidthArr[$secondCol['FIELD_PATH']] = '120px';
                        }
                        
                        if ($secondCol['SIDEBAR_NAME']) {
                            if (!isset($mergeColWidth[$secondCol['SIDEBAR_NAME']])) {
                                $mergeColWidth[$secondCol['SIDEBAR_NAME']] = (int) $columnWidthArr[$secondCol['FIELD_PATH']];
                            } else {
                                $mergeColWidth[$secondCol['SIDEBAR_NAME']] += (int) $columnWidthArr[$secondCol['FIELD_PATH']];
                            }
                        }
                    
                        $hdrHtml .= '<th style="text-align: center; background-color: #cccccc;font-weight: 600;border: 1px solid #000000;'.$style.'">'.$this->lang->line($secondCol['LABEL_NAME']).'</th>';
                    }
                }

                $hdrHtml .= '</tr>';
                
                $headerRow = 2;
                
                foreach ($mergeColWidth as $mergeName => $mergeWidth) {
                    $hdrHtml = str_replace('width'.$mergeName.'"', 'width: '.$mergeWidth.'"', $hdrHtml);
                }
            }

            $hdrHtml .= '</thead>';
            
            Mdobject::$exportHeaderConfig[$metaDataId] = array('headerData' => $headerData, 'headerHtml' => $hdrHtml, 'headerRow' => $headerRow, 'columnWidthArr' => $columnWidthArr);
            
        } else {
            
            $prevObj = Mdobject::$exportHeaderConfig[$metaDataId];
            
            $headerData     = $prevObj['headerData'];
            $hdrHtml        = $prevObj['headerHtml'];
            $headerRow      = $prevObj['headerRow'];
            $columnWidthArr = $prevObj['columnWidthArr'];
        }

        $printHtml = $headerTemplateRow = '';
        $exportDataRows = $exportData['rows'];

        if ($header) {
            loadPhpQuery();

            $exportHtml = $this->model->getDvExportTemplateHtml($metaDataId);
            $headerHtml = self::exportParamReplacer($exportHtml['header']);
            $detailHtml = phpQuery::newDocumentHTML($headerHtml);
            $headerTemplateRow = $detailHtml['table:eq(0) > tbody > tr']->length;

            if ($exportDataRows) {
                foreach ($exportDataRows[0] as $oneKey => $oneRow) {
                    $headerHtml = str_ireplace('#'.$oneKey.'#', $oneRow, $headerHtml);                
                }
            }

            $printHtml .= $headerHtml;
        }
        
        $printHtml .= $hdrHtml;                
        
        if ($exportDataRows) {
            
            $gridOptions = $this->model->getDVGridOptionsModel($metaDataId);
            
            $i = 1;
            $printHtml .= '<tbody>';
            
            if (isset($gridOptions['GROUPFIELD']) && $gridOptions['GROUPFIELD']) {
                
                $groupField   = strtolower($gridOptions['GROUPFIELD']);
                $colspanCount = count($headerData) + 1;
                $groupedRows  = Arr::groupByArray($exportDataRows, $groupField);
                
                foreach ($groupedRows as $groupedName => $groupedVal) {
                    
                    $printHtml .= '<tr><td style="text-align: left; font-weight: 600; padding: 5px 10px;border: 1px solid #000000;" colspan="'.$colspanCount.'">' . $groupedName . '</td></tr>';
                    
                    foreach ($groupedVal['rows'] as $value) {
                        $printHtml .= self::dvPrintBodyRows($i, $headerData, $columnWidthArr, $value);
                        $i++;
                    }
                }
                
            } else {
                
                foreach ($exportDataRows as $key => $value) {

                    $printHtml .= self::dvPrintBodyRows($i, $headerData, $columnWidthArr, $value);
                    
                    $i++;
                }
            }

            $printHtml .= '</tbody>';

            if ($footerSum) {
                $printHtml .= '<tfoot>';
                $printHtml .= self::dvPrintFooterRows($headerData, $columnWidthArr, $exportDataRows, $footerSum);
                $printHtml .= '</tfoot>';
            }
        }
        
        $printHtml .= '</table>';
                
        return array('status' => 'success', 'data' => $printHtml, 'headerRow' => $headerRow, 'headerTemplateRow' => $headerTemplateRow);
    }
    
    public function dvPrintBodyRows($i, $headerData, $columnWidthArr, $value) {
        
        $printHtml = '<tr><td style="text-align: center;border: 1px solid #000000; width: 20px">'.$i.'</td>';
                
        foreach ($headerData as $item) {

            $typeCode = $item['META_TYPE_CODE'];
            $cellValue = array_key_exists($item['FIELD_PATH'], $value) ? $value[$item['FIELD_PATH']] : '';
            $width = issetDefaultVal($columnWidthArr[$item['FIELD_PATH']], 120);
            
            if (is_array($cellValue)) {
                $wfmString = '';
                foreach ($cellValue as $cellRow) {
                    if (isset($cellRow['wfmstatusname'])) {
                        $wfmString .= $cellRow['wfmstatusname'] . ', ';
                    }
                }
                $cellValue = rtrim($wfmString, ', ');
            }

            if ($typeCode == 'date') {
                $printHtml .= '<td style="border: 1px solid #000000;width:'.$width.'px">' . Date::formatter($cellValue, 'Y-m-d') . '</td>';
            } elseif ($typeCode == 'datetime') {
                $printHtml .= '<td style="border: 1px solid #000000;width:'.$width.'px">' . Date::formatter($cellValue, 'Y-m-d H:i') . '</td>';
            } elseif ($typeCode == 'bigdecimal') {
                $printHtml .= '<td style="text-align: right; border: 1px solid #000000;width:'.$width.'px">' . Number::trimTrailingZeroes(number_format($cellValue, 2, '.', ',')) . '</td>';
            } elseif ($typeCode == 'boolean') {
                $printHtml .= '<td style="text-align: center; border: 1px solid #000000;width:'.$width.'px">' . ($cellValue == '1' ? '✓' : '') . '</td>';
            } elseif ($item['LABEL_NAME'] == '*') {

                if (strpos($cellValue, 'fa-chain-broken')) {
                    $cellValue = '✗';
                } elseif (strpos($cellValue, 'fa-chain')) {
                    $cellValue = '✓';
                }
                $printHtml .= '<td style="border: 1px solid #000000;width:'.$width.'px">' . $cellValue . '</td>';

            } else {
                $printHtml .= '<td style="border: 1px solid #000000;width:'.$width.'px">' . $cellValue . '</td>';
            }
        }

        $printHtml .= '</tr>';
        
        return $printHtml;
    }
    
    public function dvPrintFooterRows($headerData, $columnWidthArr, $rows, $footerSum) {
        
        $printHtml = '<tr><td style="text-align: center;border: 1px solid #000000; width: 20px; background-color: #cccccc"></td>';
                
        foreach ($headerData as $itemKey => $item) {

            $typeCode = $item['META_TYPE_CODE'];
            $width = issetDefaultVal($columnWidthArr[$item['FIELD_PATH']], 120);

            $footerText = '';
            
            if ($itemKey === 0) {
                $footerText = 'Нийт дүн ';
            }

            if (array_key_exists($item['FIELD_PATH'], $footerSum)) {
                $printHtml .= '<td style="text-align: right; border: 1px solid #000000;width:'.$width.'px; background-color: #cccccc">' . $footerText . Number::trimTrailingZeroes(number_format(helperSumFieldBp($rows, $item['FIELD_PATH']), 2, '.', ',')) . '</td>';
            } else {
                $printHtml .= '<td style="text-align: right; border: 1px solid #000000;width:'.$width.'px; background-color: #cccccc">' . $footerText . '</td>';
            }

        }

        $printHtml .= '</tr>';
        
        return $printHtml;
    }
    
    public function dataViewDirectPrint() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        $metaDataId = Input::numeric('metaDataId');
        
        $response = self::dataViewPrintExportData($metaDataId);
        
        if ($response['status'] == 'error') {
            echo json_encode($response); exit;
        }
        
        $printHtml = '<html>';
            $printHtml .= '<head>';
                $printHtml .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>'; 
            $printHtml .= '</head>';
            $printHtml .= '<body>';
                $printHtml .= $response['data'];
            $printHtml .= '</body>';
        $printHtml .= '</html>';
        
        $_POST['orientation'] = 'landscape';
        $_POST['size'] = 'a4';
        $_POST['top'] = '20px';
        $_POST['left'] = '20px';
        $_POST['bottom'] = '20px';
        $_POST['right'] = '20px';
        $_POST['fontFamily'] = 'Arial';
        
        $css = Mdpreview::printCss('return');
        
        echo json_encode(array('status' => 'success', 'html' => $printHtml, 'css' => $css)); exit;
    }    
    
    public function dataViewPrintPopup() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        
        $response = self::dataViewPrintExportData($this->view->metaDataId);
        
        if ($response['status'] == 'error') {
            echo json_encode($response); exit;
        }
        
        $this->view->dataViewId = $this->view->metaDataId;
        
        $headerHtml = $footerHtml = '';
        $dvHdrFtrHtml = $this->model->getDvExportTemplateHtml($this->view->metaDataId);
        
        if (isset($dvHdrFtrHtml['header'])) {
            $headerHtml .= '<span contenteditable="true" id="1">';
            $headerHtml .= self::exportParamReplacer($dvHdrFtrHtml['header']);
            $headerHtml .= '</span>';
            $headerHtml .= '<br /><br />';
        } else {
            $headerHtml .= '<span contenteditable="true" id="1" style="display:block;min-height:17px"></span>';
            $headerHtml .= '<br />';
        }
        
        if (isset($dvHdrFtrHtml['footer'])) {
            $footerHtml .= '<br />';
            $footerHtml .= '<span contenteditable="true" id="2">';
            $footerHtml .= self::exportParamReplacer($dvHdrFtrHtml['footer']);
            $footerHtml .= '</span>';
        } else {
            $footerHtml .= '<br />';
            $footerHtml .= '<span contenteditable="true" id="2" style="display:block;min-height:17px"></span>';
        }
        
        $printHtml = $headerHtml;
        $printHtml .= $response['data'];
        $printHtml .= $footerHtml;
        $printHtml .= '<div class="print-width-dpi"></div>';
        
        $this->view->pageProperty = array(
            'reportName'        => $this->lang->line('list'), 
            'pageSize'          => 'a4', 
            'pageOrientation'   => 'landscape', 
            'pagePrint'         => true,
            'pagePdf'           => false, 
            'pagePdfView'       => false, 
            'pageExcel'         => false,
            'pageWord'          => false,
            'pageSearch'        => false,
            'pageArchive'       => false, 
            'pageMarginTop'     => '40px', 
            'pageMarginLeft'    => '20px', 
            'pageMarginRight'   => '20px', 
            'pageMarginBottom'  => '40px', 
            'pageWidth'         => null,
            'pageHeight'        => null, 
            'fontFamily'        => 'Arial', 
            'dataViewId'        => $this->view->metaDataId, 
            'metaDataId'        => $this->view->metaDataId
        );

        $this->view->style = '';
        $this->view->style .= 'padding-top: '.$this->view->pageProperty['pageMarginTop'].';';
        $this->view->style .= 'padding-left: '.$this->view->pageProperty['pageMarginLeft'].';';
        $this->view->style .= 'padding-right: '.$this->view->pageProperty['pageMarginRight'].';';
        $this->view->style .= 'padding-bottom: '.$this->view->pageProperty['pageMarginBottom'].';';

        $this->view->contentHtml = $printHtml;
        
        echo json_encode(array('status' => 'success', 'html' => $this->view->renderPrint('control', 'middleware/views/preview/'))); exit;
    }    
    
    public function dataViewPanelRender($dataType = '') {
        
        $panelType = $this->view->row['PANEL_TYPE'];
        
        $this->view->uniqId = getUID();
        $this->view->idField = $this->view->row['idField'];
        $this->view->nameField = $this->view->row['nameField'];
        
        $this->view->filterParams = $this->model->dataViewHeaderDataModel($this->view->metaDataId);
        $this->view->filter = $this->view->renderPrint('viewer/panel/filter', self::$dataViewPath);
        $this->view->dataViewProcessCommand = $this->model->dataViewProcessCommandModel($this->view->metaDataId, $this->view->metaDataCode, false, false, $this->view->uniqId);
        $this->view->isTree = false;
        $this->view->treeCategoryList = array();
        $this->view->filterFieldList = array();
            
        if ($this->view->row['treeCategoryList']) {
            $searchType = Info::getSearchType($this->view->row['SEARCH_TYPE']);
            if ($searchType == 'LEFT') {
                $this->view->isTree = true;
                $this->view->treeCategoryList = $this->view->row['treeCategoryList']['CATEGORY_LIST'];
                $this->view->filterFieldList = $this->view->row['treeCategoryList']['FILTER_FIELD'];
            }
        } 
            
        if ($panelType == 'oneColumn') {
            
            $this->view->mainColumn = self::panelMainColumn();
            
            $this->view->panel = $this->view->renderPrint('viewer/panel/oneColumn', self::$dataViewPath);
            
        } elseif ($panelType == 'menuView') {
            
            $this->view->mainColumn = self::panelMenuView();
            
            $this->view->panel = $this->view->renderPrint('viewer/panel/menuColumn', self::$dataViewPath);
            
        } elseif ($panelType == 'twoColumn') {
            
            if (Input::isEmpty('drillDownDefaultCriteria') == false) {
                parse_str(Input::post('drillDownDefaultCriteria'), $criteria);
                
                if (isset($criteria['listtitlename'])) {
                    $this->view->title = $criteria['listtitlename'];
                }
            }
            
            if (Input::isEmpty('filterMenuTreeIds') == false) {
                $filterMenuTreeIds = Input::post('filterMenuTreeIds');
                $last = end($filterMenuTreeIds);
                $clickMenuId = $last['id'];
            }
            
            $this->view->mainColumn = self::panelMainColumn();
            
            $this->view->panel = $this->view->renderPrint('viewer/panel/twoColumn', self::$dataViewPath);
        }
        
        if ($this->view->isAjax == false) {
            $this->view->render('header');
        } 
        
        if ($dataType == 'json') {
            
            $response = array(
                'title' => $this->view->title, 
                'metaDataId' => $this->view->metaDataId, 
                'html' => $this->view->renderPrint('viewer/panel/index', self::$dataViewPath)
            );
            
            if (isset($clickMenuId)) {
                $response['clickMenuId'] = $clickMenuId;
            }
            
            if (Input::isEmpty('filterObjectDtl') == false) {
                $response['filterObjectDtl'] = Input::post('filterObjectDtl');
            }
            
            jsonResponse($response);
            
        } else {
            $this->view->render('viewer/panel/index', self::$dataViewPath);
        }

        if ($this->view->isAjax == false) {
            $this->view->render('footer');
        }

        exit;
    }
    
    public function panelMenuView($getResult = '') {
        
        $this->view->isPanelDvChangeTreeIcon = Config::getFromCache('isPanelDvChangeTreeIcon');
        
        if ($getResult) {
            
            if ($dvId = Input::numeric('dvId')) {
                
                $this->view->metaDataId = $dvId;
                
                $row = $this->model->getDataViewConfigRowModel($this->view->metaDataId);
                $this->view->idField = $row['idField'];
                $this->view->nameField = $row['nameField'];
        
                $this->view->mainColumnData = $this->model->getPanelDataListModel($this->view->metaDataId);
                $this->view->render('viewer/panel/menuView', self::$dataViewPath);
            }
            
        } else {
            
            if (issetParam($this->view->row['IS_FIRST_COL_FILTER']) == '1') {
                loadPhpQuery();
                
                $filterHtml = phpQuery::newDocumentHTML($this->view->filter);
                $filterParam = $filterHtml->find('input, select, textarea')->serializeArray();
                $params = '';
                
                foreach ($filterParam as $row) {
                    $params .= $row['name'] . '=' . $row['value'] . '&';
                }
                
                $_POST['formFilter'] = 1;
                $_POST['params'] = $params;
            }
            
            $this->view->mainColumnData = $this->model->getPanelDataListModel($this->view->metaDataId);

            return $this->view->renderPrint('viewer/panel/menuView', self::$dataViewPath);
        }
    }
    
    public function panelMainColumn($getResult = '') {
        
        $this->view->isPanelDvChangeTreeIcon = Config::getFromCache('isPanelDvChangeTreeIcon');
        
        if ($getResult) {
            
            if ($dvId = Input::numeric('dvId')) {
                
                $this->view->metaDataId = $dvId;
                
                $row = $this->model->getDataViewConfigRowModel($this->view->metaDataId);
                $this->view->idField = $row['idField'];
                $this->view->nameField = $row['nameField'];
        
                $this->view->mainColumnData = $this->model->getPanelDataListModel($this->view->metaDataId);
                $this->view->render('viewer/panel/mainColumn', self::$dataViewPath);
            }
            
        } else {
            
            if (issetParam($this->view->row['IS_FIRST_COL_FILTER']) == '1') {
                loadPhpQuery();
                
                $filterHtml = phpQuery::newDocumentHTML($this->view->filter);
                $filterParam = $filterHtml->find('input, select, textarea')->serializeArray();
                $params = '';
                
                foreach ($filterParam as $row) {
                    $params .= $row['name'] . '=' . $row['value'] . '&';
                }
                
                $_POST['formFilter'] = 1;
                $_POST['params'] = $params;
            }
            
            $this->view->mainColumnData = $this->model->getPanelDataListModel($this->view->metaDataId);

            return $this->view->renderPrint('viewer/panel/mainColumn', self::$dataViewPath);
        }
    }
    
    public function dvPanelChildDataList() {
        
        $dvId           = Input::numeric('dvId');
        $listMetaDataId = Input::numeric('listMetaDataId');
        
        $treeData = $this->model->getPanelDataListModel($dvId);
        
        unset($_POST['id']);
        
        if (Input::post('isIgnoreSecond') != '1') {
            $secondData = $this->model->getPanelDataListModel($listMetaDataId, true);
        } else {
            $secondData = array();
        }
        
        header('Content-Type: application/json');
        echo json_encode(array('treeData' => $treeData, 'secondData' => $secondData), JSON_UNESCAPED_UNICODE);
    }
    
    public function dvPanelChildDataTreeList() {
        $dataViewId = Input::numeric('listMetaDataId');
        $parent = Input::post('parent');
        
        $secondData = $this->model->getPanelDataTreeListModel($dataViewId, $parent);
        
        header('Content-Type: application/json');
        echo json_encode($secondData, JSON_UNESCAPED_UNICODE);
    }
    
    public static function dvPanelMainMenuRender($row, $idField, $nameField) {
        
        $menu = [];
        $isChildren = false;
        $childPath = 'childs';
        
        if (!isset($row['childs']) && isset($row['children'])) {
            $isChildren = true;
            $childPath = 'children';
        }
        
        if (isset($row[$childPath])) {
            
            if (!$isChildren) {
                $menu[] = '<ul class="nav nav-group-sub" style="display: block;">';
            } else {
                $menu[] = '<ul class="nav nav-group-sub">';
            }

            foreach ($row[$childPath] as $child) {
                
                $childRow = $child;
                unset($childRow[$childPath]);
                
                $rowJson = htmlentities(json_encode($childRow), ENT_QUOTES, 'UTF-8');
                $subMenu = $icon = '';

                if ($iconName = issetParam($child['icon'])) {
                    $icon = '<i class="'.$iconName.' font-weight-bold" style="color: '.issetParam($child['color']).';"></i> ';
                }

                if (issetParam($child['childrecordcount']) && !issetParam($child['isgroupcolumn'])) {
                    $subMenu = ' nav-item-submenu';
                }
                
                if (issetParam($child['_clickrow']) == '1') {
                    $subMenu .= ' nav-item-menu-click';
                }

                $menu[] = '<li class="nav-item'.$subMenu.' with-icon">
                    <a href="javascript:void(0);" data-id="'.$child[$idField].'" data-listmetadataid="'.$child['metadataid'].'" data-listmetadatacriteria="'.issetParam($child['listmetadatacriteria']).'" data-metatypeid="'.issetParam($child['metatypeid']).'" data-rowdata="'.$rowJson.'" class="nav-link v2">
                        '.$icon . $child[$nameField].'
                    </a>
                    '.self::dvPanelMainMenuRender($child, $idField, $nameField).'
                </li>';
            }
                
            $menu[] = '</ul>';
        }
        
        return implode('', $menu);
    }
    
    public function getDataviewTemplateData() {
        jsonResponse($this->model->dataviewSavedCriteriaModel(Input::post('metaDataId'), false));
    }
    
    public function deleteDataviewTemplateData() {
        jsonResponse($this->model->dataviewDeleteCriteriaModel(Input::post('templateId')));
    }
    
    public function getDataviewCriteriaTemplate() {
        
        $metaDataId = Input::numeric('metaDataId');
        $id         = Input::numeric('id');
        $viewType   = Input::post('viewtype');
        
        if ($viewType == 'top' || $viewType == 'button' || $viewType == 'left') {
            
            $this->load->model('mdobject', 'middleware/models/');
            
            $this->view->metaDataId = $metaDataId;
            $this->view->layoutType = null;
            
            $this->view->row = $this->model->getDataViewConfigRowModel($this->view->metaDataId);
            $this->view->dataViewHeaderRealData = $this->model->dataViewHeaderDataModel($this->view->metaDataId); 
            $this->view->dataViewHeaderData = self::findCriteria($this->view->metaDataId, $this->view->dataViewHeaderRealData);
            $this->view->isCheckDataViewHeaderData = true;
            $this->view->dataViewCriteriaType = strtolower($this->view->row['SEARCH_TYPE'] == '0' ? 'BUTTON' : Info::getSearchType($this->view->row['SEARCH_TYPE']));
            
            $dvSavedCrieria = $this->model->dataviewSavedRowCriteriaModel($id);
            
            if (isset($dvSavedCrieria['CRITERIA']) && $dvSavedCrieria['CRITERIA']) {
                
                $criteria = json_decode($dvSavedCrieria['CRITERIA'], true);
                
                if (isset($criteria['criteria']) && is_array($criteria['criteria'])) {
                    
                    unset($criteria['criteria']['showquery']);
                    unset($criteria['criteria']['issavecriteriatemplate']);
                    unset($criteria['criteria']['criteriatemplatename']);
                    unset($criteria['criteria']['criteriatemplatedescription']);
                    unset($criteria['criteria']['pagingwithoutaggregate']);
                    
                    foreach ($criteria['criteria'] as $key => $crow) {
                        $values = '';
                        foreach ($crow as $row) {
                            $values .= str_replace('%', '', $row['operand']) . ',';
                        }
                        $this->view->fillPath[$key] = str_replace('%', '', rtrim($values, ','));
                    }
                }
            }
                    
            $this->view->render('search/defaultCriteria', self::$dataViewPath);
            
        } else {
        
            $filterParams = $this->model->dataViewHeaderDataModel($metaDataId);

            if ($dvSavedCrieria = $this->model->dataviewSavedRowCriteriaModel($id)) {
                if ($dvSavedCrieria['CRITERIA']) {
                    $criteria = json_decode($dvSavedCrieria['CRITERIA'], true);

                    if (isset($criteria['criteria']) && is_array($criteria['criteria'])) {

                        unset($criteria['criteria']['showquery']);
                        unset($criteria['criteria']['issavecriteriatemplate']);
                        unset($criteria['criteria']['criteriatemplatename']);
                        unset($criteria['criteria']['criteriatemplatedescription']);
                        unset($criteria['criteria']['pagingwithoutaggregate']);

                        foreach ($criteria['criteria'] as $key => $crow) {
                            $values = '';
                            foreach ($crow as $row) {
                                $values .= str_replace('%', '', $row['operand']) . ',';
                            }
                            $fillPath[$key] = str_replace('%', '', rtrim($values, ','));
                        }
                    }
                }
            }

            $html = '';
            $isAdv = false;
            if (Input::post('isadvancedCriteria')) {
                $filterParams = ($filterParams) ? Arr::multidimensional_list($filterParams, array('IS_ADVANCED' => '1')) : array();
                $isAdv = true;
            }

            if (!empty($filterParams) && !isset($filterParams['data'])) {
                
                if ($viewType === 'ecommerce') {

                    foreach ($filterParams as $param) {
                        if ($isAdv) { 
                            $html .=  '<div class="col-md-12 pl0 pr0">'; 
                                $html .= '<div class="form-group row dv-criteria-row">';
                                    $html .= '<label class="col-form-label col-lg-4 text-right">'. $this->lang->line($param['META_DATA_NAME']) .'</label>' ;
                                    $html .= '<div class="col-lg-8">';
                                        $html .= '<div class="input-group input-group-criteria">';
                                            if ($param['LOOKUP_META_DATA_ID'] != '' && $param['LOOKUP_TYPE'] == 'combo' && $param['CHOOSE_TYPE'] != 'singlealways') {
                                                $param['CHOOSE_TYPE'] = 'multi';
                                            }

                                            if ($param['LOOKUP_META_DATA_ID'] == '' && $param['LOOKUP_TYPE'] == '' && ($param['META_TYPE_CODE'] === 'bigdecimal' || $param['META_TYPE_CODE'] === 'integer')) {
                                                $html .=  Mdcommon::dataviewRenderCriteriaCondition(
                                                    $param,     
                                                    Mdwebservice::renderParamControl($metaDataId, $param, "param[".$param['META_DATA_CODE']."][]", $param['META_DATA_CODE'], (isset($fillPath) ? $fillPath : false)),
                                                    '=',
                                                    'top'
                                                );
                                            } 
                                            else {
                                                $html .=  Mdcommon::dataviewRenderCriteriaCondition(
                                                    $param,     
                                                    Mdwebservice::renderParamControl($metaDataId, $param, "param[".$param['META_DATA_CODE']."]", $param['META_DATA_CODE'], (isset($fillPath) ? $fillPath : false)),
                                                    '=',
                                                    'top'
                                                );
                                            }
                                        $html .= '</div>';
                                    $html .= '</div>';
                                $html .= '</div>';
                            $html .=  '</div>';
                            
                        } elseif ($param['IS_ADVANCED'] !== '1' && $param['LOOKUP_TYPE'] !== 'tab') {
                            
                            $html .= '<div class="form-group dv-criteria-row">'.
                                '<label class="col-form-label panel-title">'.$this->lang->line($param['META_DATA_NAME']).'</label>'.
                                '<div>';
                                    $operandVal = '=';
                                    
                                    if ($param['META_TYPE_CODE'] == 'string') {
                                        $operandVal = 'like';
                                    }

                                    if ($defaultOperator = issetParam($param['DEFAULT_OPERATOR'])) {
                                        $operandVal = $defaultOperator;
                                    }
                                    
                                    $html .= Mdwebservice::renderParamControl($metaDataId,
                                            $param, 
                                            "param[".$param['META_DATA_CODE']."]", 
                                            $param['META_DATA_CODE'], 
                                            (isset($fillPath) ? $fillPath : false) , '', true);
                                    $html .= Form::select(
                                        array(
                                            'name' => 'criteriaCondition['. $param['META_DATA_CODE'] .']',
                                            'id' => 'criteriaCondition['. $param['META_DATA_CODE'] .']',
                                            'class' => 'form-control form-control-sm right-radius-zero float-right hidden',
                                            'op_value' => 'value',
                                            'op_text' => 'code',
                                            'data' => Info::defaultCriteriaCondition($param['META_TYPE_CODE']),
                                            'text' => 'notext',
                                            'value' => $operandVal
                                        )
                                    );                                          
                                $html .= '</div>';                                    
                            $html .= '</div>';
                        }
                    }            

                } elseif ($viewType === 'leftweb') {

                    foreach ($filterParams as $param) {

                        if (empty($param['IS_MANDATORY_CRITERIA'])) {

                            $html .= '<div class="panel-group accordion dv-criteria-row" id="accordion4-'.$metaDataId.'">'.
                                '<div class="panel">'.
                                    '<div class="panel-heading">'.
                                        '<h4 class="panel-title"><a class="accordion-toggle accordion-toggle-styled expanded" tabindex="-1" data-toggle="collapse">' . $this->lang->line($param['META_DATA_NAME']) . '</a></h4>'.
                                    '</div>'.
                                    '<div aria-expanded="true">';
                            
                                    $operandVal = '=';
                                    
                                    if ($param['META_TYPE_CODE'] == 'string') {
                                        $operandVal = 'like';
                                    }

                                    if ($defaultOperator = issetParam($param['DEFAULT_OPERATOR'])) {
                                        $operandVal = $defaultOperator;
                                    }
            
                                    $html .= Mdwebservice::renderParamControl($metaDataId,
                                            $param, 
                                            "param[".$param['META_DATA_CODE']."]", 
                                            $param['META_DATA_CODE'], 
                                            (isset($fillPath) ? $fillPath : false) , '', true);
                                    $html .= Form::select(
                                        array(
                                            'name' => 'criteriaCondition['. $param['META_DATA_CODE'] .']',
                                            'id' => 'criteriaCondition['. $param['META_DATA_CODE'] .']',
                                            'class' => 'form-control form-control-sm right-radius-zero float-right hidden',
                                            'op_value' => 'value',
                                            'op_text' => 'code',
                                            'data' => Info::defaultCriteriaCondition($param['META_TYPE_CODE']),
                                            'text' => 'notext',
                                            'value' => $operandVal
                                        )
                                    );                                           
                            $html .= '</div>'.
                                '</div>'.
                            '</div>';                        
                        }
                    }            

                } else {

                    foreach ($filterParams as $param) {
                        if (empty($param['IS_MANDATORY_CRITERIA'])) {
                            $html .= '<div class="form-group row dv-criteria-row">'.
                                '<label class="col-form-label col-lg-3">'.$this->lang->line($param['META_DATA_NAME']).'</label>'.
                                '<div class="col-lg-9">';
                                    $operandVal = '=';
                                    $html .= Mdwebservice::renderParamControl($metaDataId,
                                            $param, 
                                            'param['.$param['META_DATA_CODE'].']', 
                                            $param['META_DATA_CODE'], 
                                            (isset($fillPath) ? $fillPath : false) , '', true);
                                    $html .= Form::select(
                                        array(
                                            'name' => 'criteriaCondition['. $param['META_DATA_CODE'] .']',
                                            'id' => 'criteriaCondition['. $param['META_DATA_CODE'] .']',
                                            'class' => 'form-control form-control-sm right-radius-zero float-right hidden',
                                            'op_value' => 'value',
                                            'op_text' => 'code',
                                            'data' => Info::defaultCriteriaCondition($param['META_TYPE_CODE']),
                                            'text' => 'notext',
                                            'value' => $operandVal
                                        )
                                    );                                             
                                $html .= '</div>';                                    
                            $html .= '</div>';
                        }
                    }
                }
            }
        }
        
        echo $html; exit;
    }
    
    public function addinProcessCriteria() {
        $this->view->uniqId = getUID();
        $this->view->metaDataId = Input::post('metaDataId');
        
        $metaDataId = Input::post('metaDataId');
        $this->view->selectedRow = Input::post('dataRow');
        
        includeLib('Utils/Functions');
        $result = Functions::runProcess(issetDefaultVal($this->view->selectedRow['additionalsearch'], 'filterValuesGet_004'), array('id' => '1')); /* @$this->view->selectedRow['additionalsearch'] CHANTSAL 1ts bdag geed soliulaw */
        $this->view->filterValues = isset($result['result']['filtervalueget']) ? $result['result']['filtervalueget'] : array();
        $this->view->filterValuesGr = Arr::groupByArrayOnlyRows($this->view->filterValues, 'typename');
        
        $response = array(
            'Title' => '',
            'Width' => '700',
            'uniqId' => $this->view->uniqId,
            'Html' => $this->view->renderPrint('fullSearch', 'middleware/views/metadata/dataview/search/'),
            'save_btn' => Lang::line('save_btn'), 
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response);
    }
    
    public function searchFilterValues() {
        try {
            
            $postData = Input::postData();
            $this->view->uniqId = $postData['uniqId'];
            
            if (!isset($postData['options'])) {
                throw new Exception(Lang::line('NOT_OPTION_CHECKBOX_001')); 
            }
            
            (Array) $selectedValues = $this->view->fillData = array();
            
            includeLib('Compress/Compression');
            $data = Compression::decode_string_array($postData['dataComp']);
            
            if (!isset($data['filterValues'])) {
                throw new Exception(Lang::line('NOT_FILTER_NO_CHOSEN_001')); 
            }
            
            if (!isset($data['selectedRow'])) {
                throw new Exception(Lang::line('NOT_FOUND_SELECTEDROW_001')); 
            }
            
            $selectedRow = $data['selectedRow'];
            
            if (!isset($selectedRow['civilid'])) {
                throw new Exception(Lang::line('NOT_FOUND_CIVILID_IN_SELECTED_ROW')); 
            }
            
            includeLib('Utils/Functions');

            foreach ($postData['options'] as $row) {
                $search = Arr::multidimensional_search($data['filterValues'], array('registrytypeid' => $row['value']));
                if ($search) {
                    array_push($selectedValues, $search);
                }
            }

            $criteria = array(
                            'civilid' => array(
                                array(
                                    'operator' => '=',
                                    'operand' => $selectedRow['civilid']
                                )
                            )
                        );

            foreach ($selectedValues as $row) {
                $row['data'] = array();
                if ($row['dataviewid']) {
                    $data = Functions::runDataViewWithoutLogin($row['dataviewid'], $criteria);
                    $row['data'] = isset($data['result']) ? $data['result'] : array();
                }

                if ($row['data']) {
                    array_push($this->view->fillData, $row);
                }
            }

            if (Config::getFromCache('saveAdvSearchLogElec') === '1') {
                $data = array(
                    'ID'              => getUID(), 
                    'DV_META_DATA_ID' => Input::post('dataViewId'), 
                    'CREATED_USER_ID' => Ue::sessionUserKeyId(), 
                    'CREATED_DATE'    => Date::currentDate(), 
                    'ROW_COUNT'       => '1', 
                    'IP_ADDRESS'      => get_client_ip(),
                    'FILTER1'         => $selectedRow['registernum'],
                    'FILTER70'        => '1'
                );
                $this->db->AutoExecute('CUSTOMER_DV_FILTER_DATA', $data);
            }

            $response = array(
                'Title' => '',
                'Width' => '700',
                'status' => 'success',
                'uniqId' => $this->view->uniqId,
                'Html' => $this->view->renderPrint('fillForm', 'middleware/views/metadata/dataview/search/'),
                'save_btn' => Lang::line('save_btn'), 
                'close_btn' => Lang::line('close_btn')
            );
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'text' => $ex->getMessage());
        }
        
        echo json_encode($response);
    }
    
    public function treeViewSetOrder() {
        $mainDvId = $this->model->getStandartFieldModel(Input::post('dataViewId'), 'meta_value_id');
        $rows = $_POST['orderParam'];
        $rowsCount = count($rows);
        $n = 1;
        
        $this->db->StartTrans();

        for ($i = 0; $i < $rowsCount; $i++) {
            $rowdata = json_decode(html_entity_decode($rows[$i]['rowdata'], ENT_QUOTES), true);
            $idCol = issetDefaultVal($rowdata['orderidcolumn'], 'ID');
           
            $this->db->Execute('UPDATE ' . $rowdata['ordertablename'] . ' SET ' . $rowdata['orderordercolumn'] . ' = ' . $n . ' WHERE ' . $idCol . ' = ' . $rowdata[$mainDvId]);
            $n++;
        } 

        $this->db->CompleteTrans();
    }    
    
    public function treeViewSetParent() {
        
        try {
            $row = $_POST['orderParam'];
            $parentId = Input::post('parentId');
            $primaryId = Input::post('primaryId');

            $rowdata = json_decode(str_replace('\&quot;', '&quot;', $row), true);
            $idCol = issetDefaultVal($rowdata['orderidcolumn'], 'ID');

            $parentId = empty($parentId) ? 'null' : $parentId;
            $this->db->Execute('UPDATE ' . $rowdata['ordertablename'] . ' SET PARENT_ID = ' . $parentId . ' WHERE ' . $idCol . ' = ' . $primaryId);
            
            $response = array('status' => 'success');
            
        } catch(Exception $ex) {
            $response = array('status' => 'error');
        }
        
        echo json_encode($response);
    }    
    
    public function advancedCriteriaForm() {
        try {
            
            $postData = Input::postData();

            
            includeLib('Compress/Compression');
            $advancedCriteria = Compression::decode_string_array($postData['criteria']);

            $this->view->metaDataId = $postData['metaDataId'];
            $this->view->advancedCriteria = $advancedCriteria['advancedCriteria'];
            $this->view->fillPath = $advancedCriteria['fillPath'];

            $response = array(
                'Title' => '',
                'Width' => '700',
                'Html' => $this->view->renderPrint('search/advCriteria', self::$dataViewPath),
                'save_btn' => Lang::line('save_btn'), 
                'close_btn' => Lang::line('close_btn')
            );
            echo json_encode($response);
            
        } catch (Exception $ex) {
            
        }
    }

    public function getXypInfoDataView() {

        /**
         * GET Session User RegisterNum and Finger image
         */
        $param = array(
            'sessionUserId' => Ue::sessionUserKeyId()
        );
        $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'ntrHurGetOperatorInfo', $param);

        if ($data['status'] == 'success') {
            /**
             * Call Xyp Service
             */
            $param = array(
                'auth' => array(
                    'citizen' => array(
                        'regnum' => Input::post('registerNum'),
                        'fingerprint' => Input::post('filePath')
                    ),
                    'operator' => array(
                        'regnum' => $data['result']['stateregnumber'],
                        'fingerprint' => $data['result']['filepath']
                    )
                ),
                'sessionUserId' => Ue::sessionUserKeyId(),
                'civilId' => ''
            );
            $this->load->model('mdwebservice', 'middleware/models/');            
            $row = $this->model->getMethodIdByMetaDataModel('1539575972407143');

            $this->load->model('Mdintegration', 'middleware/models/');
            $data = $this->model->callXypService($row, $param);            

            if (isset($data['data']['return']['resultcode']) && $data['data']['return']['resultcode'] != '0') {
                $response = array('message' => $data['data']['return']['resultmessage'], 'status' => 'error');
            } else {
                $getResultData = isset($data['data']['return']['response']) ? $data['data']['return']['response'] : array();
                $response = array();

                if ($getResultData) {
                    foreach ($getResultData as $key => $value) {
                        $response['xyp'.$key] = $value;
                    }
                }

                $result = array('status' => 'success', 'message' => 'ХУР-с мэдээлэл амжилттай татагдлаа', 'data' => $response);
            }      

        } else {
            $result = array('status' => 'error', 'message' => $this->ws->getResponseMessage($data));
        }

        jsonResponse($result);
    }    

    public function getPolygonList() {
        jsonResponse($this->model->getPolygonListModel());
    }
    
    public function goToPagePivotView() {        
        $this->view->dataViewId = Input::post('dataViewId');        
        $this->view->name1 = Input::post('name1');        
        $this->view->groupName = Input::post('groupName');
        $this->view->clickRowFunction = Input::post('clickRowFunction');
        $this->view->layoutTheme = Input::post('layoutTheme');
        
        $this->view->row = $this->model->getDataViewConfigRowModel($this->view->dataViewId);
        
        $_POST['metaDataId'] = $this->view->dataViewId;
        $_POST['treeGrid']   = 1;
        $_POST['page']       = Input::post('page');
        $_POST['rows']       = Input::post('rows');
        
        $this->view->header = $this->model->getDataViewGridAllFieldsModel($this->view->dataViewId);
        $this->view->header = Arr::groupByArray($this->view->header, 'FIELD_PATH');
        $this->view->columnName = Input::post('columnName');
        $this->view->color = Input::post('color');
        $this->view->totalCount = 0;
        $this->view->isAllShowField = issetParam($this->view->row['dataViewLayoutTypes']['explorer']['fields']['allshowfield']);
        
        /*if ($this->view->isAllShowField) {
            $_POST['rows'] = 200;
        }*/

        $this->view->recordList = $this->model->dataViewDataGridModel();
        
        if ($this->view->recordList['status'] == 'success' && isset($this->view->recordList['rows'])) {
            
            $this->view->totalCount = $this->view->recordList['total'];
            $this->view->recordList = $this->view->recordList['rows'];
            $this->view->pureRecordList = $this->view->recordList;

            if ($this->view->groupName && $this->view->recordList && isset($this->view->recordList[0][$this->view->groupName])) {

                $this->view->recordList = Arr::groupByArray($this->view->recordList, $this->view->groupName);                    
                
                $getCountCardData = $this->model->getCountCardDataModel($this->view->dataViewId, $this->view->groupName);
                $this->view->totalCount = $getCountCardData ? count($getCountCardData) : 0;
                
                if ($columnDvId = issetParam($this->view->row['dataViewLayoutTypes']['explorer']['fields']['metadataid'])) {
                    
                    $_POST['page'] = 1;
                    
                    $columnData = $this->model->dataViewDataGridModel(true, $columnDvId);

                    if (isset($columnData['rows'][0][$this->view->columnName])) {
                        $this->view->columnData = $columnData['rows'];
                    }
                }
            }                
        } else {
            $this->view->recordList = null;
        }                        

        $response = array(
            'Html' => $this->view->renderPrint('viewer/explorer/layout/'.$this->view->layoutTheme.'_pager', self::$dataViewPath),
            'status' => 'success', 
            'total' => $this->view->totalCount
        );
        jsonResponse($response);
    }
    
    public function getRowsIdCommaByEncrypt() {
        
        $metaDataId = Input::numeric('metaDataId');
        
        $_POST['page'] = 1;
        $_POST['rows'] = 5000;
        $_POST['ignorePermission'] = 1;
        $_POST['pagingWithoutAggregate'] = 1;

        $response = $this->model->dataViewDataGridModel(true, $metaDataId);
        
        if ($response['status'] == 'success') {
            
            $rows = $response['rows']; 
            $response = array('status' => 'success', 'total' => count($rows));
            
            if ($response['total']) {
                $firstRow = $rows[0];
                
                $this->load->model('mddatamodel', 'middleware/models/');
                $fields = $this->model->getCodeNameFieldNameModel($metaDataId);
                $idField = $fields['id'] ? $fields['id'] : 'id';
                
                if (array_key_exists($idField, $firstRow)) {
                    
                    includeLib('Compress/Compression');
                    
                    $response['data'] = Compression::gzdeflate(Arr::implode_key(',', $rows, $idField, true));
                    
                } else {
                    $response = array('status' => 'error', 'message' => 'META_VALUE_ID талбар тохируулна уу!');
                }
            }
        }
        
        jsonResponse($response);
    }
    
    public function saveDataViewConfigUser($metaDataId, $viewType) {
        
        if (Input::numeric('isSaveViewer') == 1) {
            return $this->model->saveDataViewConfigUserModel($metaDataId, $viewType);
        }
        
        return true;
    }
    
    public function getDataViewRowsByCriteria($pagination, $metaDataId, $criteria = array()) {
        
        $this->load->model('mdobject', 'middleware/models/');
        $result = $this->model->dataViewDataGridModel($pagination, $metaDataId, $criteria);
        
        return $result;
    }    
    
    public function draggableRowsToFilter() {
        $response = $this->model->draggableRowsToFilterModel();
        convJson($response);
    }
    
}
