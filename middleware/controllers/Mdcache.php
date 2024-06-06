<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdcache Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Process detail cache
 * @author	B.Och-Erdene <ocherdene@interactive.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdcache
 */
class Mdcache extends Controller {
    
    public static $gfServiceAddress = GF_SERVICE_ADDRESS;
    
    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }
    
    public function fillParamDataSplice($processId, $cacheId, $pagerConfig, $fillParamData, $runMode = null) {
        
        $saveRows = array();
        
        if ($fillParamData) {

            foreach ($pagerConfig as $groupPath => $configRow) {
                
                $groupPath = strtolower($groupPath);

                if (isset($configRow['configArr']['pagesize']) && isset($fillParamData[$groupPath])) {
                    
                    $pageSize = $configRow['configArr']['pagesize'];
                    
                    if ($runMode == 'load_first') {
                        $expression = (new Mdexpression())->getCacheExpression($processId);
                    
                        if ($groupExpression = issetParam($expression['load_first_'.$groupPath])) {
                            
                            $groupExpression = str_replace(Mdexpression::$cachePrefix, '$rowDatas[$rk]', $groupExpression);
                            $rowDatas = $fillParamData[$groupPath];
                            
                            foreach ($rowDatas as $rk => $rv) {
                                eval($groupExpression);
                            }
                            
                            $fillParamData[$groupPath] = $rowDatas;
                        }
                    }
                    
                    $saveRows[$groupPath] = $fillParamData[$groupPath];

                    $fillParamData[$groupPath.'_total'] = count($fillParamData[$groupPath]);

                    if ($aggregateColumns = self::calcAggregateColumns($configRow['aggregateColumns'], $fillParamData[$groupPath])) {
                        $fillParamData[$groupPath.'_aggregatecolumns'] = $aggregateColumns;
                    }

                    array_splice($fillParamData[$groupPath], $pageSize);
                } 
            }
        }
        
        self::createCacheFile($cacheId, $saveRows);
        
        return $fillParamData;
    }
    
    public function calcAggregateColumns($configAggregateColumns, $data) {
        
        $aggregatedColumns = array();

        if ($configAggregateColumns) {

            foreach ($configAggregateColumns as $column) {
                
                $columnName = strtolower($column['PARAM_NAME']);
                
                if ($data && array_key_exists($columnName, $data[key($data)])) {
                    
                    $aggrValue = 0;
                    
                    if ($column['COLUMN_AGGREGATE'] == 'sum') {
                        $aggrValue = array_sum(array_column($data, $columnName));
                    }
                    
                    $aggregatedColumns[$column['PARAM_REAL_PATH']] = $aggrValue;
                    
                } else {
                    $aggregatedColumns[$column['PARAM_REAL_PATH']] = 0;
                }
            }
        }
        
        return $aggregatedColumns;
    }
    
    public function createCacheFile($cacheId, $saveRows) {
        
        $cacheTmpDir = Mdcommon::getCacheDirectory();
        $cacheDir = $cacheTmpDir.'/getData';

        if (!is_dir($cacheDir)) {

            mkdir($cacheDir, 0777);

        } else {

            $files = glob($cacheDir.'/*');
            $now   = time();
            $day   = 0.5;

            foreach ($files as $file) {
                if (is_file($file) && ($now - filemtime($file) >= 60 * 60 * 24 * $day)) {
                    @unlink($file);
                } 
            }
        }

        $cachePath = $cacheDir.'/'.$cacheId.'.txt';
        
        file_put_contents($cachePath, var_export($saveRows, true));
        
        return true;
    }
    
    public function pushDataSplice($pagerConfig, $cacheId, $pageSize, $pageNumbers, $groupFieldPathLower, $cacheArray, $rowData, $isCachedGroup, $isEditMode) {
        
        $cacheTmpDir = Mdcommon::getCacheDirectory();
        $cacheDir = $cacheTmpDir.'/getData';
        $cachePath = $cacheDir.'/'.$cacheId.'.txt';
        
        if ($isCachedGroup) {
            
            $cacheArrayTemp = $cacheArray[$groupFieldPathLower];
            $cacheArray[$groupFieldPathLower] = $cacheArrayTemp + $rowData;

        } else {
            $cacheArray[$groupFieldPathLower] = $rowData;
        }
        
        file_put_contents($cachePath, var_export($cacheArray, true));
        
        $array[$groupFieldPathLower.'_total'] = count($cacheArray[$groupFieldPathLower]);
        $configRow = $pagerConfig[$groupFieldPathLower];
        
        if ($aggregateColumns = self::calcAggregateColumns($configRow['aggregateColumns'], $cacheArray[$groupFieldPathLower])) {
            $array[$groupFieldPathLower.'_aggregatecolumns'] = $aggregateColumns;
        }
        
        $this->load->model('mdcache', 'middleware/models/'); 
        $array[$groupFieldPathLower] = $this->model->getPage($cacheArray[$groupFieldPathLower], $pageSize, $pageNumbers);
        
        return $array;
    }
    
    public function detailPagerFillRows() {
        
        $response = $this->model->detailPagerFillRowsModel();
        
        ob_start('ob_html_compress'); 
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        ob_end_flush();
        
        exit;
    }
    
    public function deleteRow() {
        $response = $this->model->deleteRowModel();
        echo json_encode($response, JSON_UNESCAPED_UNICODE); exit;
    }
    
    public static function getDetailRowsFromCache($cacheId) {
        
        $cacheDir = Mdcommon::getCacheDirectory();
        $cachePath = $cacheDir.'/getData/'.$cacheId.'.txt';

        $cacheStr = file_get_contents($cachePath);
        eval('$cacheArray = '.$cacheStr.';'); 
        
        return array('cacheArray' => $cacheArray, 'cacheDir' => $cacheDir, 'cachePath' => $cachePath, 'cacheStr' => $cacheStr);
    }
    
    public function getDetailOnlyRowsFromCache($cacheId) {
        
        $cacheDir = Mdcommon::getCacheDirectory();
        $cachePath = $cacheDir.'/getData/'.$cacheId.'.txt';

        $cacheStr = file_get_contents($cachePath);
        eval('$cacheArray = '.$cacheStr.';'); 
        
        return $cacheArray;
    }
    
    public function getDetailFromCache($cacheId, $processMetaDataId, $paramsArr) {
        
        $cacheDir = Mdcommon::getCacheDirectory();
        $cachePath = $cacheDir.'/getData/'.$cacheId.'.txt';

        $cacheStr = file_get_contents($cachePath);

        if (empty($cacheStr)) return false;
        
        eval('$cacheArray = '.$cacheStr.';'); 
        
        $this->load->model('mdwebservice', 'middleware/models/');
        $returnData = $this->model->getShowInputParams($processMetaDataId);
                        
        if (isset($returnData['pagerConfig']) && count($returnData['pagerConfig'])) {
            
            $paramsArr = Arr::changeKeyLower($paramsArr);
            $pagerConfig = $returnData['pagerConfig'];
            
            foreach ($pagerConfig as $groupPath => $configRow) {
                
                $groupPathLower = strtolower($groupPath);
                
                if (isset($cacheArray[$groupPathLower])) {

                    if ($dataLoop = issetParam($paramsArr[$groupPathLower.'.mainrowcount'])) {
                        
                        $groupRows = $cacheArray[$groupPathLower];
                        
                        unset($paramsArr[$groupPathLower.'.mainrowcount']);
                        
                        $groupTypePaths = array();
                        $evalFields = '';

                        foreach ($paramsArr as $key => $keyVal) {

                            if (strpos($key, $groupPathLower.'.') !== false) {
                                $replacedKey = str_replace($groupPathLower.'.', '', $key);

                                if (strpos($replacedKey, '.') !== false) {

                                    $replacedKeyArr = explode('.', $replacedKey);
                                    $bracketsKey = '';

                                    foreach ($replacedKeyArr as $rk => $rv) {
                                        $bracketsKey .= '[\''.$rv.'\']';
                                        
                                        if ($rk == 0) {
                                            $groupTypePaths[$rv] = 1;
                                        }
                                    }

                                    $evalFields .= '$groupRows[$v]'.$bracketsKey.' = returnNull($paramsArr[\''.$key.'\'][$v][0]); ';
                                } else {
                                    $evalFields .= '$groupRows[$v][\''.$replacedKey.'\'] = returnNull($paramsArr[\''.$key.'\'][$v][0]); ';
                                }
                            }
                        }
                        
                        if ($groupTypePaths) {
                            
                            if ((float)phpversion() < 5.6) {
                                
                                foreach ($groupTypePaths as $groupTypePath => $groupTypeV) {
                                    
                                    $evalFields .= '$tempGroupRows = array();
                                    foreach ($groupRows[$v][\''.$groupTypePath.'\'] as $k => $v) {
                                        if ($k != \'createduserid\' && $k != \'createddate\' && $k != \'modifieduserid\' && $k != \'modifieddate\' && (!is_array($v) && $v != \'\')) {
                                            $tempGroupRows[] = $v;
                                        }
                                    }
                                    $concatVals = implode(\'\', $tempGroupRows); 
                                    if ($concatVals == \'\') {
                                        $groupRows[$v][\''.$groupTypePath.'\'] = \'\';
                                    } ';
                                }
                                
                            } else {
                                foreach ($groupTypePaths as $groupTypePath => $groupTypeV) {
                                    $evalFields .= '$concatVals = implode(\'\', array_filter($groupRows[$v][\''.$groupTypePath.'\'], function($v,$k){return ($k == \'createduserid\' || $k == \'createddate\' || $k == \'modifieduserid\' || $k == \'modifieddate\') ? \'\' : (!is_array($v) && $v != \'\');}, ARRAY_FILTER_USE_BOTH)); 
                                    if ($concatVals == \'\') {
                                        $groupRows[$v][\''.$groupTypePath.'\'] = \'\';
                                    } ';
                                }
                            }
                        }

                        foreach ($dataLoop as $k => $v) {
                            eval($evalFields);
                        }

                        $cacheArray[$groupPathLower] = $groupRows;
                    }
                } 
            }      
        }
        
        return $cacheArray;
    }
    
    public static function modifiedGroupRows($cachePath, $groupRows, $paramsArr, $groupPathLower) {
        
        if ($dataLoop = issetParam($paramsArr[$groupPathLower.'.mainrowcount'])) {
            
            unset($paramsArr[$groupPathLower.'.mainrowcount']);
            
            if (Input::postCheck('lookupParams')) {
                $lookupParams = $_POST['lookupParams'];
                
                foreach ($lookupParams as $lookupParam) {
                    $paramsArr[$lookupParam['path']][$lookupParam['rowId']][0] = array(
                        'id' => $lookupParam['id'], 
                        'code' => $lookupParam['code'], 
                        'name' => $lookupParam['name'], 
                        'rowdata' => $lookupParam['rowdata']    
                    );
                }
            }
            
            $evalFields = '';
            
            foreach ($paramsArr as $key => $keyVal) {

                $replacedKey = str_replace($groupPathLower.'.', '', $key);

                if (strpos($replacedKey, '.') !== false) {

                    $replacedKeyArr = explode('.', $replacedKey);
                    $bracketsKey = '';

                    foreach ($replacedKeyArr as $rk => $rv) {
                        $bracketsKey .= '[\''.$rv.'\']';
                    }

                    $evalFields .= '$groupRows[$v]'.$bracketsKey.' = issetParamZero($paramsArr[\''.$key.'\'][$v][0]); ';
                } else {
                    $evalFields .= '$groupRows[$v][\''.$replacedKey.'\'] = issetParamZero($paramsArr[\''.$key.'\'][$v][0]); ';
                }
            }

            foreach ($dataLoop as $k => $v) {
                eval($evalFields);
            }

            $cacheStr = file_get_contents($cachePath);
            eval('$cacheArray = '.$cacheStr.';');

            $cacheArray[$groupPathLower] = $groupRows;

            file_put_contents($cachePath, var_export($cacheArray, true));
        }
        
        return $groupRows;
    }
    
    public function allRowsCalculate() {
        
        $cacheId = Input::post('cacheId');
        $code = Input::post('code');
        
        if ($code) {
            
            $processId = Input::post('processId');
            $expression = (new Mdexpression())->getCacheExpression($processId);
            
            if ($groupExpression = issetParam($expression['function_'.$code])) {
                
                $groupPath = Input::post('groupPath');
                $groupPathLower = strtolower($groupPath);
                
                $cacheParams = self::getDetailRowsFromCache($cacheId);
                $cacheArray = $cacheParams['cacheArray'];
                $cachePath = $cacheParams['cachePath'];
                
                if (isset($cacheArray[$groupPathLower])) {

                    parse_str($_POST['params'], $paramsArr);
                    $paramsArr = isset($paramsArr['param']) ? Arr::changeKeyLower($paramsArr['param']) : $paramsArr;
                    $groupRows = $cacheArray[$groupPathLower];

                    $rowDatas = self::modifiedGroupRows($cachePath, $groupRows, $paramsArr, $groupPathLower);
                    
                    parse_str($_POST['headerData'], $headerDataArr);
                    $headerData = isset($headerDataArr['param']) ? Arr::changeKeyLower($headerDataArr['param']) : $headerDataArr;
                    
                    $groupExpression = str_replace(
                        array(Mdexpression::$cachePrefix, Mdexpression::$cachePrefixHeader), 
                        array('$rowDatas[$rk]',           '$headerData'), 
                    $groupExpression);

                    /*
                    $isEditMode = Input::post('isEditMode'); 
                    if ($isEditMode == 'true') { 
                        $groupExpression .= '$rowDatas[$rk][\'rowstate\'] = \'modified\';'; 
                    }*/

                    foreach ($rowDatas as $rk => $rv) {
                        eval($groupExpression);
                    }

                    $cacheArray[$groupPathLower] = $rowDatas;

                    file_put_contents($cachePath, var_export($cacheArray, true)); 
                    
                    if (Input::postCheck('varNames')) {
                        
                        $varNames    = Input::post('varNames');
                        $varNamesArr = explode('|', $varNames);
                        $response    = array('status' => 'success');
                        
                        foreach ($varNamesArr as $k => $varName) {
                            if (isset(${$varName})) {
                                $response[$varName] = ${$varName};
                            }
                        }
                        
                        echo json_encode($response);
                        
                    } else {
                        echo 'success'; 
                    }
                    
                    exit;
                }
            }
        }
        
        echo 'error'; exit;
    }
    
    public function lookupFieldReload() {
        
        $processId = Input::post('processId');
        $fieldPath = Input::post('fieldPath');
        $headerData = $_POST['headerData'];
        
        $data = $this->db->GetAll("
            SELECT 
                LOWER(PARAM_PATH) AS PARAM_PATH, 
                DEFAULT_VALUE, 
                LOOKUP_META_DATA_ID, 
                PARAM_META_DATA_CODE  
            FROM META_GROUP_PARAM_CONFIG  
            WHERE MAIN_PROCESS_META_DATA_ID = ".$this->db->Param(0)." 
                AND IS_GROUP = 0 
                AND (IS_KEY_LOOKUP = 0 OR IS_KEY_LOOKUP IS NULL)  
                AND LOOKUP_META_DATA_ID IS NOT NULL 
                AND LOWER(FIELD_PATH) = " . $this->db->Param(1), 
            array($processId, strtolower($fieldPath))
        );
        
        if ($data) {
            
            $cacheDir = Mdcommon::getCacheDirectory();
        
            $cacheId = Input::post('cacheId');
            $groupPathLower = strtolower(Input::post('groupPath'));
            $cachePath = $cacheDir.'/getData/'.$cacheId.'.txt';
            $cacheStr = file_get_contents($cachePath);

            eval('$cacheArray = '.$cacheStr.';'); 
            
            if (isset($cacheArray[$groupPathLower])) {
                
                $groupRows = $cacheArray[$groupPathLower];
                
                $matchPath = strtolower(Input::post('matchPath'));
                $matchPathArr = explode('==', $matchPath);
                
                $cacheFieldPath = $matchPathArr[0];
                $dvPath = $matchPathArr[1];
                
                $paramFilter = array();
                
                $paramFilter[$dvPath][] = array(
                    'operator' => 'IN', 
                    'operand'  => Arr::implode_key(',', $groupRows, $cacheFieldPath, true) 
                );
                
                parse_str($headerData, $headerDataArr);
                $headerDataArr = isset($headerDataArr['param']) ? Arr::changeKeyLower($headerDataArr['param']) : $headerDataArr;

                $lookupMetaDataId = $data[0]['LOOKUP_META_DATA_ID'];

                foreach ($data as $row) {

                    $value = '';
                    $isValue = false;

                    if (isset($headerDataArr[$row['PARAM_PATH']])) {

                        if (is_array($headerDataArr[$row['PARAM_PATH']])) {

                            $value = Arr::implode_r(',', $headerDataArr[$row['PARAM_PATH']], true);

                        } else {

                            $value = trim($headerDataArr[$row['PARAM_PATH']]);

                            if ($value == '' && trim($row['DEFAULT_VALUE']) == 'nullval') {
                                $value = '';
                                $isValue = true;
                            } 
                        }

                    } else {

                        if ($row['DEFAULT_VALUE'] != '') {
                            $value = trim($row['DEFAULT_VALUE']);
                            if ($value == 'nullval') {
                                $value = '';
                                $isValue = true;
                            }
                        }
                    }

                    if ($value != '' || $isValue) {

                        $operator = '=';

                        if (strpos($value, ',') !== false) {
                            $operator = 'IN';
                        }

                        $paramFilter[$row['PARAM_META_DATA_CODE']][] = array(
                            'operator' => $operator,
                            'operand' => $value
                        );
                    }
                }

                if (count($paramFilter) > 0) {

                    $param = array(
                        'systemMetaGroupId' => $lookupMetaDataId,
                        'showQuery' => 0, 
                        'ignorePermission' => 1,  
                        'criteria' => $paramFilter
                    );      

                    $dataResult = $this->ws->runArrayResponse(self::$gfServiceAddress, Mddatamodel::$getDataViewCommand, $param);

                    if ($dataResult['status'] == 'success' && isset($dataResult['result'])) {

                        unset($dataResult['result']['aggregatecolumns']);

                        if (array_key_exists('paging', $dataResult['result'])) {
                            unset($dataResult['result']['paging']);
                        }

                        $rows = $dataResult['result'];

                        if ($rows) {
                            
                            $fieldPathArr = explode('.', $fieldPath);
                            $processPath = strtolower($fieldPathArr[1]);

                            foreach ($rows as $row) {

                                $cacheKey = array_search($row[$dvPath], array_column($groupRows, $cacheFieldPath));
                                
                                if ($cacheKey !== false) {
                                    $groupRows[$cacheKey][$processPath]['rowdata'] = $row;
                                }
                            }
                            
                            $cacheArray[$groupPathLower] = $groupRows;

                            file_put_contents($cachePath, var_export($cacheArray, true)); 

                            echo 'success'; exit;
                        }
                    }
                }
            
            }
        }
        
        echo 'error'; exit;
    }
    
    public function bpDetailExcelExport() {
        
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
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
        
        $listName = Lang::line('META_00062');
        $sheet->setTitle(Str::excelSheetName(Str::utf8_substr($listName, 0, 31)));
        
        $startRowIndex = 1;
        $headerParams = $_POST['headerData'];
        
        $processId = Input::post('processId');
        $rowId = Input::post('rowId');
        $cacheId = Input::post('cacheId');
        $groupPath = Input::post('groupPath');
        $groupPathLower = strtolower($groupPath);
        
        $this->load->model('mdwebservice', 'middleware/models/');
        $processData = $this->model->groupParamsDataModel($processId, $rowId);
        $bpPath = $headerData = array();
        
        foreach ($processData as $pRow) {
            $bpPath[$pRow['PARAM_REAL_PATH']] = array(
                'typeCode' => $pRow['META_TYPE_CODE'], 
                'fieldPath' => $pRow['META_DATA_CODE']
            );
        }
        
        $h = 0;
        
        foreach ($headerParams as $key => $row) {
            
            if ($row['isLookup'] == 'true') {
                
                $sheet->setCellValue(numToAlpha($h + 2) . $startRowIndex, $row['labelName'].'/Код');
                
                $headerData[$h] = $row;
                $headerData[$h]['lookupCode'] = 1;
                $h++;
                
                $sheet->setCellValue(numToAlpha($h + 2) . $startRowIndex, $row['labelName'].'/Нэр');
                
                $headerData[$h] = $row;
                $headerData[$h]['lookupName'] = 1;
                $h++;
                
            } else {
                $sheet->setCellValue(numToAlpha($h + 2) . $startRowIndex, $row['labelName']);
                $headerData[$h] = $row;
                $h++;
            }
        }
        
        $headerCount = count($headerData);

        $sheet->setCellValue('A'.$startRowIndex, '№');
        
        $i = $startRowIndex + 1;
        
        $cacheParams = self::getDetailRowsFromCache($cacheId);
        $cacheArray = $cacheParams['cacheArray'];
        $cachePath = $cacheParams['cachePath'];
        $exportDataRows = array();
        
        if (isset($cacheArray[$groupPathLower])) {

            parse_str($_POST['params'], $paramsArr);
            $paramsArr = isset($paramsArr['param']) ? Arr::changeKeyLower($paramsArr['param']) : $paramsArr;
            $groupRows = $cacheArray[$groupPathLower];

            $exportDataRows = self::modifiedGroupRows($cachePath, $groupRows, $paramsArr, $groupPathLower);
        }
        
        if (!$exportDataRows) {
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=false; path=/');
            echo 'No data!'; exit();
        }
        
        foreach ($exportDataRows as $key => $value) {
            
            $sheet->setCellValue(numToAlpha(1) . $i, ++$key);
            
            foreach ($headerData as $k => $item) {
                
                $path = $bpPath[$item['path']];
                $typeCode = $path['typeCode'];
                $fieldPathLower = strtolower($path['fieldPath']);
                
                $numToAlpha = numToAlpha($k + 2);
                $cellValue = '';
                
                if (isset($value[$fieldPathLower])) {
                    
                    if (is_array($value[$fieldPathLower])) {
                        if ($item['isLookup'] == 'true' && isset($item['lookupCode'])) {
                            $cellValue = $value[$fieldPathLower]['code'];
                        } elseif ($item['isLookup'] == 'true' && isset($item['lookupName'])) {
                            $cellValue = $value[$fieldPathLower]['name'];
                        } else {
                            $cellValue = $value[$fieldPathLower]['code'];
                        }
                    } else {
                        $cellValue = $value[$fieldPathLower];
                    }
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
                } else {
                    $sheet->setCellValueExplicit($numToAlpha . $i, $cellValue, PHPExcel_Cell_DataType::TYPE_STRING);
                }

            }
            $i++;
        }
        
        if (count($_POST['footerData'])) {
            
            $aggregateColumns = self::calcAggregateColumns($_POST['footerData'], $exportDataRows);
            $footerData = $_POST['footerData'];
            
            foreach ($headerData as $k => $item) {
                if (isset($aggregateColumns[$item['path']])) {
                    $footerStyle['font']['bold'] = true;
                    $footerStyle['alignment']['horizontal'] = PHPExcel_Style_Alignment::HORIZONTAL_RIGHT;
                    $sheet->setCellValueExplicit(numToAlpha($k + 2) . $i, $aggregateColumns[$item['path']], PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    $sheet->getStyle(numToAlpha($k + 2) . $i)->applyFromArray($footerStyle);
                    $sheet->getStyle(numToAlpha($k + 2) . $i)->getNumberFormat()->setFormatCode('0.000');
                }
            }
        }
        
        $sheet->freezePane('A'.($startRowIndex + 1));
        
        foreach (range(0, $headerCount) as $columnID) {
            $sheet->getColumnDimensionByColumn($columnID)->setAutoSize(true);
        }
        
        $sheet->getStyle('A'.$startRowIndex.':' . numToAlpha($headerCount + 1) . $startRowIndex)->applyFromArray(
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
        
        try {
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=true; path=/');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8');
            header('Content-Disposition: attachment;filename="' . $listName . ' - ' . Date::currentDate('YmdHi') . '.xlsx"');
            header('Content-Transfer-Encoding: binary');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            flush();
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            
        } catch (Exception $e) {
            
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=false; path=/');
            echo $e->getMessage(); exit();
        }
    }
    
    public static function createCacheFolder($tempdir, $day = 0.5) {
        
        if (!is_dir($tempdir)) {

            mkdir($tempdir, 0777);

        } else {

            $files = glob($tempdir.'/*');
            $now   = time();

            foreach ($files as $file) {
                if (is_file($file) && ($now - filemtime($file) >= 60 * 60 * 24 * $day)) {
                    unlink($file);
                } 
            }
        }
        
        return $tempdir;
    }
    
}
