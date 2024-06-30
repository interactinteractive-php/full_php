<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * RestApi Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Restful API
 * @author	B.Och-Erdene <ocherdene@veritech.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/RestApi
 */

class Restapi extends Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        
        includeLib('Compress/Compression');
        
        $jsonBody = file_get_contents('php://input');
        $jsonBody = Compression::decompress($jsonBody);
            
        $param = json_decode($jsonBody, true);
        $isMobile = false;
        
        if (isset($param['request'])) {
            
            $param       = $param['request'];
            $commandName = issetVar($param['command']);
            $parameters  = issetParam($param['parameters']);
            $isMobile    = (issetVar($param['isMobile']) || issetVar($param['ismobile'])) ? true : false;
            $isLowerCase = (issetVar($param['isLowerCase']) || issetVar($param['islowercase'])) ? true : false;
            $isSessionId = $isUserPass = false;
            
            if (array_key_exists('sessionId', $param) || array_key_exists('sessionid', $param)) {
                
                $isSessionId = true;
                
                $sessionId   = issetVar($param['sessionId']);
                $sessionId   = $sessionId ? $sessionId : issetVar($param['sessionid']);
                
                $setSession = $this->model->setUserSessionBySessionIdModel($sessionId);
                
            } else {
                
                $isUserPass = true;
                
                $username = issetVar($param['username']);
                $password = issetVar($param['password']);
                
                $setSession = $this->model->setUserSessionByUserPassModel($username, $password);
            }
            
            if (!$setSession) {
                
                if ($isSessionId) {
                    $response = ['status' => 'error', 'message' => 'Session not found!'];
                } else {
                    $response = ['status' => 'error', 'message' => 'Username or password is wrong!'];
                }
                
            } else {
                
                switch ($commandName) {
                    
                    case 'kpiIndicatorDataListConfig':
                    {
                        $kpiMainIndicatorId = Input::param($parameters['indicatorId']);

                        $this->load->model('mdform', 'middleware/models/');
                        $configRow = $this->model->getKpiIndicatorRowModel($kpiMainIndicatorId);

                        if ($configRow) {
                            
                            $result = [
                                'id'        => $configRow['ID'],
                                'code'      => $configRow['CODE'],
                                'name'      => $configRow['NAME'],
                                'kpiTypeId' => $configRow['KPI_TYPE_ID'], 
                                'isUseWorkflow'   => $configRow['IS_USE_WORKFLOW'], 
                                'metaStructureId' => null, 
                                'jsonConfig'      => null
                            ];
                            
                            if ($configRow['JSON_CONFIG'] != '') {
                                $result['jsonConfig'] = json_decode($configRow['JSON_CONFIG'], true);
                            }
                            
                            if ($kpiMainIndicatorId == '17095242488819') {
                                $result['metaStructureId'] = 1505450297170;
                            }
                            
                            $configRow['isIgnoreStandardFields'] = true;
                            
                            $columnsData = $this->model->getKpiIndicatorColumnsModel($kpiMainIndicatorId, $configRow);
                            $processData = $this->model->getKpiIndicatorProcessModel($kpiMainIndicatorId);
                            $relationData = $this->model->getKpiIndicatorMapWithoutTypeModel($kpiMainIndicatorId, 10000009);              
                            
                            array_walk($columnsData, function(&$value) {      
                                unset($value['LOOKUP_META_DATA_ID']);
                                unset($value['INPUT_NAME']);
                                unset($value['LOOKUP_CRITERIA']);
                                unset($value['DEFAULT_VALUE']);
                                unset($value['IS_INPUT']);
                                unset($value['IS_UNIQUE']);
                                unset($value['TRG_TABLE_NAME']);
                                unset($value['SEMANTIC_TYPE_NAME']);
                                unset($value['REPORT_AGGREGATE_FUNCTION']);
                                unset($value['EXPRESSION_STRING']);
                                
                                if ($value['JSON_CONFIG'] != '') {
                                    $value['JSON_CONFIG'] = json_decode($value['JSON_CONFIG'], true);
                                }
                            }); 
                            
                            array_walk($relationData, function(&$value) {      
                                unset($value['TABLE_NAME']);
                                unset($value['QUERY_STRING']);
                                unset($value['SRC_TABLE_NAME']);
                                unset($value['SRC_QUERY_STRING']);
                                unset($value['CREATED_USER_ID']);
                            });
                            
                            $result['columns'] = $columnsData;
                            $result['process'] = $processData;
                            $result['relation'] = $relationData;
                            
                            $response = ['status' => 'success', 'result' => $result];
                        } else {
                            $response = ['status' => 'error', 'text' => 'Not found indicator!'];
                        }
                    }
                    break;
                    
                    case 'kpiIndicatorConfig':
                    {
                        $kpiMainIndicatorId = Input::param($parameters['indicatorId']);

                        $this->load->model('mdform', 'middleware/models/');
                        $configRow = $this->model->getKpiIndicatorRowModel($kpiMainIndicatorId);

                        if ($configRow) {
                            
                            $result = [];
                            
                            if ($configRow['KPI_TYPE_ID'] == '2008') { //Method
                                
                                $parentIndicatorId = $configRow['PARENT_ID'];
                                $data = $this->model->getKpiIndicatorTemplateModel($parentIndicatorId);
                                
                                if ($data) {
                                    
                                    $dataFirstRow = $data[0];
                                    $rowExp = $this->model->getKpiIndicatorFullExpressionModel($parentIndicatorId);
                                    
                                    $result['header'] = [
                                        'NAME' => $dataFirstRow['NAME'],
                                        'LABEL_WIDTH' => $dataFirstRow['LABEL_WIDTH'],
                                        'KPI_TYPE_ID' => $dataFirstRow['KPI_TYPE_ID'],
                                        'NAME_PATTERN' => $dataFirstRow['NAME_PATTERN'],
                                        'IS_USE_COMPONENT' => $dataFirstRow['IS_USE_COMPONENT'], 
                                        'RENDER_THEME' => $dataFirstRow['RENDER_THEME'],
                                        'PROFILE_PICTURE' => $dataFirstRow['PROFILE_PICTURE'],
                                        'WINDOW_TYPE' => $dataFirstRow['WINDOW_TYPE'],
                                        'WINDOW_SIZE' => $dataFirstRow['WINDOW_SIZE'],
                                        'WINDOW_WIDTH' => $dataFirstRow['WINDOW_WIDTH'],
                                        'WINDOW_HEIGHT' => $dataFirstRow['WINDOW_HEIGHT'], 
                                        'PARENT_INDICATOR_ID' => $parentIndicatorId, 
                                        'fullExpression' => $rowExp
                                    ];
                                    
                                    unset($data[0]);
                                    
                                    $result['detail'] = array_values($data);
                                            
                                } else {
                                    $response = ['status' => 'error', 'text' => 'No config indicator!'];
                                }
                            }
                            
                            $response = ['status' => 'success', 'result' => $result];
                        } else {
                            $response = ['status' => 'error', 'text' => 'Not found indicator!'];
                        }
                    }
                    break;
                    
                    case 'kpiIndicatorDataListFilterConfig':
                    {
                        $kpiMainIndicatorId = Input::param($parameters['indicatorId']);
                        $_POST['ignoreColName'] = issetVar($parameters['ignoreColName']);
                        
                        if ($criteria = issetParam($parameters['criteria'])) {
                            $_POST['criteria'] = $criteria;
                        }
                        
                        $this->load->model('mdform', 'middleware/models/');
                        $result = $this->model->filterKpiIndicatorValueFormModel($kpiMainIndicatorId);

                        if ($result['status'] == 'success') {
                            $response = ['status' => 'success', 'result' => $result['data']];
                        } else {
                            $response = ['status' => 'error', 'text' => $result['message']];
                        }
                    }
                    break;
                    
                    case 'kpiIndicatorDataList':
                    case 'kpiIndicatorDataListByAliasName':
                    {
                        $_POST['indicatorId'] = Input::paramNum($parameters['indicatorId']);
                        $_POST['page']        = issetParam($parameters['paging']['offset']);
                        $_POST['rows']        = issetParam($parameters['paging']['pageSize']);
                        $_POST['isGoogleMap'] = issetParam($parameters['isGoogleMap']);
                        
                        if ($sortColumnNames = issetVar($parameters['sortColumnNames'])) {
                            if (is_array($sortColumnNames)) {
                                $sortColumns = '';
                                foreach ($sortColumnNames as $sortColumnName => $sortType) {
                                    $sortColumns .= $sortColumnName.'='.$sortType['sortType'].'&';
                                }
                                $_POST['sortFields'] = rtrim($sortColumns, '&');
                            }
                        }
                        
                        if ($criteria = issetParam($parameters['criteria'])) {
                            $_POST['criteria'] = $criteria;
                        }
                        
                        if ($commandName == 'kpiIndicatorDataListByAliasName') {
                            Mdform::$isTrgAliasName = true;
                        }

                        $this->load->model('mdform', 'middleware/models/');
                        $result = $this->model->indicatorDataGridModel();

                        if ($result['status'] == 'success') {

                            $response = [
                                'status' => 'success', 
                                'result' => ['rows' => $result['rows'], 'paging' => ['totalcount' => $result['total']]]
                            ];

                        } else {
                            $response = ['status' => 'error', 'text' => $result['message']];
                        }
                    }
                    break;

                    case 'kpiIndicatorDataSave':
                    case 'kpiIndicatorDataSaveByAliasName':
                    {
                        $kpiMainIndicatorId = Input::param($parameters['indicatorId']);
                        $kpiCrudIndicatorId = issetVar($parameters['crudIndicatorId']);
                        
                        $postParameters = ['kpiMainIndicatorId' => $kpiMainIndicatorId, 'kpiCrudIndicatorId' => $kpiCrudIndicatorId];
                        
                        if (isset($parameters['endToEndLog'])) {
                            $postParameters['endToEndLog'] = $parameters['endToEndLog'];
                        }
                        
                        $_POST = $postParameters;
                        
                        if ($commandName == 'kpiIndicatorDataSaveByAliasName') {
                            Mdform::$isTrgAliasName = true;
                        }
                        
                        Mdform::$mvSaveParams = Arr::changeKeyUpper($parameters);

                        $this->load->model('mdform', 'middleware/models/');
                        $response = $this->model->saveMetaVerseDataModel();
                        $response = Arr::changeKeyName($response, 'message', 'text');
                    }
                    break;
                
                    case 'kpiIndicatorGetData':
                    case 'kpiIndicatorGetDataByAliasName':
                    {
                        $kpiMainIndicatorId = issetVar($parameters['indicatorId']);
                        
                        if ($kpiMainIndicatorId) {
                            
                            $this->load->model('mdform', 'middleware/models/');
                            unset($parameters['indicatorId']);
                            
                            $crudIndicatorId = isset($parameters['crudIndicatorId']) ? Input::param($parameters['crudIndicatorId']) : null;
                            $recordId = isset($parameters['recordId']) ? Input::param($parameters['recordId']) : null;
                            
                            if ($commandName == 'kpiIndicatorGetDataByAliasName') {
                                Mdform::$isGetTrgAliasName = true;
                            }
                            
                            if (isset($parameters['isLookupRowData']) && $parameters['isLookupRowData']) {
                                Mdform::$isGetLookupRowData = true;
                            }
                            
                            if ($crudIndicatorId) {
                                
                                $row = $this->model->getKpiIndicatorProcessModel($kpiMainIndicatorId, $crudIndicatorId);
                                $configRow = $row[0];
                                
                                if ($configRow['is_fill_relation'] && isset($parameters['selectedRow'])) {
                                    $_POST['fillSelectedRow'] = $parameters['selectedRow'];
                                    $result = $this->model->getDefaultFillDataModel($configRow['structure_indicator_id']);
                                } else {
                                    $result = $this->model->getMetaVerseDataModel($configRow['structure_indicator_id'], ['recordId' => $recordId]);
                                }
                                
                            } else {
                                if ($recordId) {
                                    $result = $this->model->getMetaVerseDataModel($kpiMainIndicatorId, ['recordId' => $recordId]);
                                } elseif (count($parameters) > 0) {
                                    $result = $this->model->getMetaVerseDataModel($kpiMainIndicatorId, $parameters);
                                } else {
                                    $result = ['status' => 'error', 'message' => 'Invalid parameters!'];
                                }
                            }
                            
                        } else {
                            $result = ['status' => 'error', 'message' => 'Invalid indicatorId!'];
                        }

                        if ($result['status'] == 'success') {
                            $response = ['status' => 'success', 'result' => isset($result['detailData']) ? $result['detailData'] : (isset($result['data']) ? $result['data'] : [])];
                        } else {
                            $response = ['status' => 'error', 'text' => $result['message']];
                        }
                    }
                    break;
                    
                    case 'kpiIndicatorRemoveData':
                    {
                        $kpiMainIndicatorId = issetVar($parameters['indicatorId']);
                        
                        if ($kpiMainIndicatorId) {
                            
                            $selectedRows = isset($parameters['selectedRows']) ? Input::param($parameters['selectedRows']) : null;
                            
                            if ($selectedRows) {
                                $this->load->model('mdform', 'middleware/models/');
                                
                                $_POST['indicatorId'] = $kpiMainIndicatorId;
                                $_POST['crudIndicatorId'] = issetVar($parameters['crudIndicatorId']);
                                $_POST['selectedRows'] = Arr::changeKeyUpper($selectedRows);
                                
                                $response = $this->model->removeKpiDynamicDataModel();
                                
                                if ($response['status'] == 'success') {
                                    Mdform::clearCacheData($kpiMainIndicatorId);
                                }
                                
                                $response = Arr::changeKeyName($response, 'message', 'text');
                                
                            } else {
                                $response = ['status' => 'error', 'text' => 'Invalid parameters!'];
                            }
                        } else {
                            $response = ['status' => 'error', 'text' => 'Invalid indicatorId!'];
                        }
                    }
                    break;
                    
                    case 'kpiIndicatorGetMetaInfo':
                    {
                        $kpiMainIndicatorId = Input::param($parameters['indicatorId']);

                        $this->load->model('mdform', 'middleware/models/');
                        $result = $this->model->getKpiIndicatorMetaInfoModel($kpiMainIndicatorId);

                        if ($result['status'] == 'success') {
                            $response = ['status' => 'success', 'result' => $result['detailData']];
                        } else {
                            $response = ['status' => 'error', 'text' => $result['message']];
                        }
                    }
                    break;
                
                    case 'kpiIndicatorModuleMenu':
                    {
                        $this->load->model('mdmenu', 'middleware/models/');
                        $menuData = $this->model->getKpiIndicatorMenuModel($isMobile);
                        
                        $response = ['status' => 'success', 'result' => $menuData];
                    }
                    break;
                    
                    case 'kpiIndicatorChildMenu':
                    {
                        $menuId = issetVar($parameters['menuId']);
                        
                        if ($menuId) {
                            $this->load->model('mdmenu', 'middleware/models/');
                            $menuData = $this->model->getKpiMenuListByParentIdCacheModel($menuId, $isMobile);
                        } else {
                            $menuData = [];
                        }
                        
                        $response = ['status' => 'success', 'result' => $menuData];
                    }
                    break;
                
                    case 'kpiIndicatorChartDataConfig':
                    {
                        $_POST = $parameters;

                        $this->load->model('mdform', 'middleware/models/');
                        $result = $this->model->kpiIndicatorChartDataConfigModel();

                        if ($result['status'] == 'success') {
                            $response = $result;
                        } else {
                            $response = ['status' => 'error', 'text' => $result['message']];
                        }
                    }
                    break;
                    
                    case 'kpiIndicatorDashboardConfig':
                    {
                        $indicatorId = Input::param($parameters['indicatorId']);

                        $this->load->model('mdform', 'middleware/models/');
                        $dashboardConfigs = $this->model->getKpiDashboardChartsModel($indicatorId);

                        if ($dashboardConfigs['layoutCode']) {
                            $response = ['status' => 'success', 'result' => $dashboardConfigs];
                        } else {
                            $response = ['status' => 'error', 'text' => 'Тохиргоо олдсонгүй!'];
                        }
                    }
                    break;
                    
                    case 'kpiIndicatorDashboardFilterConfig':
                    {
                        $indicatorId = Input::param($parameters['indicatorId']);

                        $this->load->model('mdform', 'middleware/models/');
                        $filterParams = $this->model->getKpiDashboardFilterParamsModel($indicatorId);
                        $response = ['status' => 'success', 'result' => []];
                        
                        if ($filterParams) {
            
                            $_POST['isChartList'] = 1;

                            $filterData = $this->model->filterKpiIndicatorValueFormModel($indicatorId, $filterParams);

                            if ($filterData['status'] == 'success') {
                                $response['result'] = $filterData['data'];
                            } 
                        } 
                    }
                    break;
                    
                    case 'kpiIndicatorCharts':
                    {
                        $indicatorId = Input::param($parameters['indicatorId']);

                        $this->load->model('mdform', 'middleware/models/');
                        $chartConfigs = $this->model->getKpiIndicatorChildChartsModel($indicatorId);

                        if ($chartConfigs['status'] == 'success') {
                            $response = ['status' => 'success', 'result' => $chartConfigs['data']];
                        } else {
                            $response = ['status' => 'error', 'text' => $chartConfigs['message']];
                        }
                    }
                    break;
                    
                    case 'kpiIndicatorExcelExport':
                    {
                        $indicatorId = Input::param($parameters['indicatorId']);
                        
                        $_POST['nult'] = 1;
                        $_POST['indicatorId'] = $indicatorId;
                        $_POST['fDownload'] = 1;
                        
                        if ($criteria = issetParam($parameters['criteria'])) {
                            $_POST['criteria'] = $criteria;
                        }
                        
                        if (issetVar($parameters['isBase64']) == '1') {
                            
                            $response = (new Mdform())->indicatorExcelExport(true);
                            
                            if ($response['status'] == 'error') {
                                $response['text'] = $response['message'];
                                unset($response['message']);
                            }
                            
                        } else {
                            (new Mdform())->indicatorExcelExport();
                            exit;
                        }
                    }
                    break;
                    
                    case 'kpiIndicatorRawDataMart':
                    {
                        $indicatorId = issetVar($parameters['indicatorId']);
                        
                        if ($indicatorId) {
                            $this->load->model('mdform', 'middleware/models/');
                            $response = $this->model->runAllKpiDataMartByIndicatorIdModel($indicatorId);
                        } else {
                            $response = ['status' => 'error', 'text' => 'Invalid indicatorId!'];
                        }
                    }
                    break;
                    
                    case 'kpiIndicatorRuleDiscount':
                    {
                        if ($parameters) {
                            $parameters = Arr::changeKeyLower($parameters);
                            $metaProcessId = issetVar($parameters['metaprocessid']);
                        
                            if ($metaProcessId) {
                                $response = $this->model->checkMetaVerseRuleDiscountModel($metaProcessId, $parameters);
                            } else {
                                $response = ['status' => 'error', 'text' => 'Invalid indicatorId!'];
                            }
                        } else {
                            $response = ['status' => 'error', 'text' => 'Parameter not found!'];
                        }
                    }
                    break;
                    
                    case 'kpiIndicatorGetRelation':
                    {   
                        if (!isset($parameters['indicatorId']) || (isset($parameters['indicatorId']) && !$parameters['indicatorId'])) {
                            $response = ['status' => 'error', 'text' => 'Invalid indicatorId!'];
                            break;
                        } 
                        
                        if (!isset($parameters['semanticTypeId']) || (isset($parameters['semanticTypeId']) && !$parameters['semanticTypeId'])) {
                            $response = ['status' => 'error', 'text' => 'Invalid semanticTypeId!'];
                            break;
                        } 
                        
                        $indicatorId    = Input::param($parameters['indicatorId']);
                        $semanticTypeId = Input::param($parameters['semanticTypeId']);

                        $this->load->model('mdform', 'middleware/models/');
                        $relations = $this->model->getChildRenderStructureModel($indicatorId, explode(',', $semanticTypeId));

                        $response = ['status' => 'success', 'result' => $relations];
                    }
                    break;
                    
                    case 'removeFile':
                    {
                        if (isset($parameters['path']) && isset($parameters['path'][0])) {
                            
                            $paths = Input::param($parameters['path']);
                            
                            foreach ($paths as $path) {
                                if ($path && file_exists($path)) {
                                    @unlink($path);
                                }
                            }
                            
                            $response = ['status' => 'success', 'text' => 'success'];
                        } else {
                            $response = ['status' => 'error', 'text' => 'Invalid path!'];
                        }
                    }
                    break;
                    
                    default: 
                    {
                        $response = ['status' => 'error', 'text' => 'Wrong command!'];
                    }
                    break;
                }

                array_walk_recursive($response, function (&$item, $key) {
                    $item = null === $item ? '' : $item;
                });
                
                if ($isMobile || $isLowerCase) {
                    $response = Arr::changeKeyLower($response);
                }
            }
        
        } else {
            $response = ['status' => 'error', 'text' => 'Invalid command!'];
        }
        
        $isMobile = true;
        
        if ($isMobile || self::isPhpGzCompressionInProcess() == false) {
            
            $compressed = json_encode(['response' => $response], JSON_UNESCAPED_UNICODE);
            header('Content-Type: application/json; charset=utf-8');
            
        } else {
            
            $compressed = gzencode(json_encode(['response' => $response], JSON_UNESCAPED_UNICODE));

            header('Content-Type: application/json; charset=utf-8');
            header('Content-Encoding: gzip');
            header('Content-Length: ' . strlen($compressed));
        }

        echo $compressed;
    }
    
    public function explore($version = '', $indicatorId = '') {
        
        $response = array('status' => 'error', 'message' => 'Bad request');
        
        if ($version == 'v1' && $indicatorId != '') {
            
            try {
                
                $indicatorId = Input::paramNum($indicatorId);
                
                if (!$indicatorId) {
                    throw new Exception('Invalid indicatorId!');
                }
                
                $select   = Input::get('select');
                $where    = Input::get('where');
                $group_by = Input::get('group_by');
                $order_by = Input::get('order_by');
                $limit    = Input::paramNum(Input::get('limit'));
                $offset   = Input::paramNum(Input::get('offset'));

                $_POST['indicatorId'] = $indicatorId;
                
                if ($limit) {
                    $_POST['rows'] = $limit;
                }
                
                if ($offset) {
                    $_POST['page'] = $offset;
                }
                
                if ($select) {
                    
                    $selectArr = explode(',', trim($select));
                    $selectColumns = array();
                    
                    foreach ($selectArr as $selectColumn) {
                        
                        $selectColumn = trim($selectColumn);
                        $isCorrectSelectColumn = (boolean) preg_match("/^[0-9a-zA-Z_\(\)\s]{1,30}$/i", $selectColumn);
                        
                        if (!$isCorrectSelectColumn) {
                            throw new Exception('Invalid selectColumn!');
                        }
                        
                        $selectColumn = strtolower($selectColumn);
                        $selectColumns[$selectColumn] = 1;
                    }
                    
                    $_POST['selectColumns'] = $selectColumns;
                }

                if ($where) {
                    
                    $where = $_GET['where'];
                    $isCorrectWhere = (boolean) preg_match("/^[0-9a-zA-ZФЦУЖЭНГШҮЗКЪЙЫБӨАХРОЛДПЯЧЁСМИТЬВЮЕЩфцужэнгшүзкъйыбөахролдпячёсмитьвюещ_\(\)\>\<\=\-'\:\s]{1,500}$/i", $where);
                    
                    if (!$isCorrectWhere) {
                        throw new Exception('Invalid where!');
                    }
                    
                    $where = str_replace('&#39;', "'", $where);
                    
                    if (strpos($where, "date'") !== false) {
                        preg_match_all('/date\'([\w\W]*?)\'/i', $where, $dates);

                        if (count($dates[1]) > 0) {
                            foreach ($dates[1] as $ek => $ev) {
                                if (!isValidDate($ev, 'Y-m-d')) {
                                    throw new Exception('Invalid date!');
                                }
                                $where = str_replace($dates[0][$ek], $this->db->SQLDate('Y-m-d', "'".$ev."'", 'TO_DATE'), $where);
                            }
                        }
                    }
                    
                    if (strpos($where, "datetime'") !== false) {
                        preg_match_all('/datetime\'([\w\W]*?)\'/i', $where, $datetimes);

                        if (count($datetimes[1]) > 0) {
                            foreach ($datetimes[1] as $ek => $ev) {
                                if (!isValidDate($ev, 'Y-m-d H:i')) {
                                    throw new Exception('Invalid datetime!');
                                }
                                $where = str_replace($datetimes[0][$ek], $this->db->SQLDate('Y-m-d H:i', "'".$ev."'", 'TO_DATE'), $where);
                            }
                        }
                    }
        
                    $_POST['whereClause'] = $where;
                }
                
                if ($group_by) {
                    
                    $group_byArr = explode(',', trim($group_by));
                    $groupColumns = array();
                    
                    foreach ($group_byArr as $groupColumn) {
                        
                        $groupColumn = trim($groupColumn);
                        $isCorrectGroupColumn = (boolean) preg_match("/^[0-9a-zA-Z_\(\)]{1,30}$/i", $groupColumn);
                        
                        if (!$isCorrectGroupColumn) {
                            throw new Exception('Invalid groupColumn!');
                        }
                        
                        $groupColumns[$groupColumn] = 1;
                    }
                    
                    $_POST['ignoreOrderIdField'] = 1;
                    $_POST['groupColumns'] = $groupColumns;
                }
                
                if ($order_by) {
                    
                    $order_byArr = explode(',', trim($order_by));
                    $sortColumns = '';
                    
                    foreach ($order_byArr as $orderColumn) {
                        
                        $orderColumn = strtolower(trim($orderColumn));
                        $isCorrectOrderColumn = (boolean) preg_match("/^[0-9a-zA-Z_\(\)\s]{1,30}$/i", $orderColumn);
                        
                        if (!$isCorrectOrderColumn) {
                            throw new Exception('Invalid orderColumn!');
                        }
                        
                        $orderColumn = Str::remove_doublewhitespace($orderColumn);
                        
                        $orderDirectionAsc  = substr($orderColumn, -4);
                        $orderDirectionDesc = substr($orderColumn, -5);
                        $isDirectionAsc     = ($orderDirectionAsc == ' asc');
                        $isDirectionDesc    = ($orderDirectionDesc == ' desc');
                        
                        if ($isDirectionAsc || $isDirectionDesc) {
                            
                            if ($isDirectionAsc) {
                                $sortColumnName = str_replace(' asc', '', $orderColumn);
                                $sortType = 'asc';
                            } else {
                                $sortColumnName = str_replace(' desc', '', $orderColumn);
                                $sortType = 'desc';
                            }
                            
                            $sortColumns .= $sortColumnName.'='.$sortType.'&';
                        } else {
                            throw new Exception('Invalid orderColumn!');
                        }
                    }
                    
                    $_POST['ignoreOrderIdField'] = 1;
                    $_POST['sortFields'] = rtrim($sortColumns, '&');
                }

                $this->load->model('mdform', 'middleware/models/');
                $result = $this->model->indicatorDataGridModel();
                
                if ($result['status'] == 'success') {
                    
                    $rows = $result['rows'];
                    
                    if (Input::get('lowercase') == '1') {
                        $rows = Arr::changeKeyLower($rows);
                    }
                    
                    $response = [
                        'status' => 'success', 
                        'result' => ['totalcount' => $result['total'], 'rows' => $rows]
                    ];
                    
                    unset($rows);

                } else {
                    $response = array('status' => 'error', 'message' => $result['message']);
                }
                
            } catch (Exception $ex) {
                $response = array('status' => 'error', 'message' => $ex->getMessage());
            }
        }
        
        if ($response['status'] == 'error') {
            set_status_header(400);
        }
        
        $compressed = json_encode($response, JSON_UNESCAPED_UNICODE);
        header('Content-Type: application/json; charset=utf-8');
            
        echo $compressed;
    }
    
    public function isPhpGzCompressionInProcess() {

        if (in_array('ob_gzhandler', ob_list_handlers())) {
            return true;
        }

        if (extension_loaded('zlib') && isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) {
            return true;
        }

        return false;
    }
    
    public function uploadFile() {
        
        header("Content-Type: application/json");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization");
        
        $token = Input::post('token');
        
        if ($token) {
            
            $token = str_replace(array('tttnmhttt', 'ttttntsuttt'), array('+', '='), $token);
            $tokenDate = Hash::decryption($token);
            
            if ((strtotime(Date::currentDate('Y-m-d H:i:s')) - strtotime($tokenDate)) > 20) {
                set_status_header(400);
                $response = array('status' => 'error', 'message' => 'Bad Request /expiredate/');
            } else {

                $uploadResult = Mdwebservice::bpFileUpload(array('ID' => getUID(), 'META_TYPE_CODE' => 'file'), $_FILES['file']);

                if ($uploadResult) {
                    $val = $uploadResult['path'] . $uploadResult['newname'];
                    $response = array('status' => 'success', 'files' => $val);
                } else {
                    $response = array('status' => 'error', 'message' => 'Unkhown error!');
                }
            }
        } 
        
        echo json_encode(array('response' => $response), JSON_UNESCAPED_UNICODE);
    }
    
    public function runIndicatorFromMetaProcessData() {
        
        $param = file_get_contents('php://input');
        @file_put_contents('log/runIndicatorFromMetaProcessData.log', $param);
        
        $inputParam = json_decode($param, true);
        
        if (is_array($inputParam)) {
            
            $bpCode  = array_key_first($inputParam);
            $bpParam = $inputParam[$bpCode];

            if ($bpParam && isset($bpParam['_metadataid']) && isset($bpParam['_indicatorid'])) {
                $response = $this->model->runIndicatorFromMetaProcessDataModel($bpParam, $param);
            } else {
                $response = ['status' => 'info', 'message' => 'No params! /_metadataid, _indicatorid/'];
            }
        
        } else {
            $response = ['status' => 'info', 'message' => 'No params!'];
        }
        
        convJson($response);
    }
    
    public function runIndicatorFromMetaProcessDataTest() {
        
        $param = file_get_contents('log/runIndicatorFromMetaProcessData.log');
        $inputParam = json_decode($param, true);
        
        if (is_array($inputParam)) {
            
            $bpCode  = array_key_first($inputParam);
            $bpParam = $inputParam[$bpCode];

            if ($bpParam && isset($bpParam['_metadataid']) && isset($bpParam['_indicatorid'])) {
                $response = $this->model->runIndicatorFromMetaProcessDataModel($bpParam, $param);
            } else {
                $response = ['status' => 'info', 'message' => 'No params! /_metadataid, _indicatorid/'];
            }
        
        } else {
            $response = ['status' => 'info', 'message' => 'No params!'];
        }
        
        convJson($response);
    }
    
}