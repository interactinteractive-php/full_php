<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdcache_Model extends Model {

    public function __construct() {
        parent::__construct();
    }
    
    public function detailPagerFillRowsModel() {
        
        $cacheDir = Mdcommon::getCacheDirectory();
        
        $processMetaDataId = Input::post('processId');
        $uniqId            = Input::post('uniqId');
        $cacheId           = Input::post('cacheId');
        $page              = Input::post('page');
        $rows              = Input::post('rows');
        $groupPath         = Input::post('groupPath');
        $filterRules       = Input::postNonTags('filterRules');
        $sort              = Input::post('sort');
        $groupPathLower    = strtolower($groupPath);
        
        $cachePath = $cacheDir.'/getData/'.$cacheId.'.txt';
        
        $cacheStr = file_get_contents($cachePath);
        eval('$cacheArray = '.$cacheStr.';'); 
        
        $groupRows = $cacheArray[$groupPathLower];
        
        if (Input::postCheck('params')) {
            
            parse_str($_POST['params'], $paramsArr);
            $paramsArr = isset($paramsArr['param']) ? Arr::changeKeyLower($paramsArr['param']) : $paramsArr;

            $groupRows = (new Mdcache())->modifiedGroupRows($cachePath, $groupRows, $paramsArr, $groupPathLower);
        }
        
        $aggregateStr = '';
        $criteria = array();
        
        if ($sort) {
            $criteria['sort_key'] = substr($sort, strlen($groupPathLower.'.'));
            $criteria['sort_order'] = Input::post('order');
        }
        
        if ($filterRules) {
            
            $filterRules = json_decode($filterRules, true);
            
            foreach ($filterRules as $filterRule) {
                
                $field = str_replace($groupPathLower.'.', '', $filterRule['field']);
                
                if ($filterRule['type'] == 'boolean') {
                    
                    if ($filterRule['value'] == '0') {
                        $criteria['where'][] = array($field, array(0, '0', null, ''));
                    } else {
                        $criteria['where'][] = array($field, array(1, '1'));
                    }
                    
                } elseif ($filterRule['type'] == 'lookup-code') {
                    
                    $criteria['where'][] = array($field.'.code', '~', $filterRule['value']);
                    
                } elseif ($filterRule['type'] == 'lookup-name') {
                    
                    $criteria['where'][] = array($field.'.name', '~', $filterRule['value']);
                    
                } elseif ($filterRule['type'] == 'bigdecimal' || $filterRule['type'] == 'integer' || $filterRule['type'] == 'number' || $filterRule['type'] == 'decimal') {
                    
                    $filterValue = trim($filterRule['value']);
                    $filterOneChar = substr($filterValue, 0, 1);
                    $filterTwoChar = substr($filterValue, 0, 2);
                    
                    if ($filterTwoChar == '<=') {
                        
                        $filterValue = str_replace('<=', '', $filterValue);
                        $criteria['where'][] = array($field, '<=', $filterValue);
                        
                    } elseif ($filterTwoChar == '>=') {
                        
                        $filterValue = str_replace('>=', '', $filterValue);
                        $criteria['where'][] = array($field, '>=', $filterValue);
                        
                    } elseif ($filterOneChar == '>') {
                        
                        $filterValue = str_replace('>', '', $filterValue);
                        $criteria['where'][] = array($field, '>', $filterValue);
                        
                    } elseif ($filterOneChar == '<') {
                        
                        $filterValue = str_replace('<', '', $filterValue);
                        $criteria['where'][] = array($field, '<', $filterValue);
                        
                    } elseif ($filterOneChar == '=') {
                        
                        $filterValue = str_replace('=', '', $filterValue);
                        $criteria['where'][] = array($field, '==', $filterValue);
                        
                    } else {
                        $criteria['where'][] = array($field, '~', $filterValue);
                    }
                    
                } else {
                    $criteria['where'][] = array($field, '~', $filterRule['value']);
                }
            }
        }
        
        if ($criteria) {

            includeLib('Array/arrch');
            $groupRows = Arrch\Arrch::find($groupRows, $criteria, 'all');
            
            $total = count($groupRows);
            
        } else {
            $total = count($groupRows);
        }
        
        if (Input::post('reloadFooter') == '1' || isset($criteria['where'])) {
            
            $this->load->model('mdwebservice', 'middleware/models/');
            $bpConfig = $this->model->getShowInputParams($processMetaDataId);
            
            if (isset($bpConfig['pagerConfig']) && isset($bpConfig['pagerConfig'][$groupPathLower]) 
                && isset($bpConfig['pagerConfig'][$groupPathLower]['aggregateColumns'])) {
                
                if ($aggregateColumns = (new Mdcache())->calcAggregateColumns($bpConfig['pagerConfig'][$groupPathLower]['aggregateColumns'], $groupRows)) {
                    $aggregateStr = http_build_query($aggregateColumns);
                }
            }
        }
        
        $pageNumbers = ceil($total / $rows);

        if ($page > $pageNumbers) {
            $page = $pageNumbers;
            $setPageNumber = true;
        }
                    
        $responseData = self::getPage($groupRows, $rows, $page);
        
        if ($responseData) {
            
            $this->load->model('mdwebservice', 'middleware/models/');

            if ($parentProcessRow = $this->model->isFirstLevelProcessModel($processMetaDataId, $groupPathLower)) {
                
                $childData = $this->model->onlyShowGroupParamsDataModel($processMetaDataId, $parentProcessRow['ID']);
                
                $row = array(
                    'id' => $parentProcessRow['ID'],
                    'code' => $groupPath,
                    'recordtype' => 'rows',
                    'isShowAdd' => $parentProcessRow['IS_SHOW_ADD'],
                    'isShowDelete' => $parentProcessRow['IS_SHOW_DELETE'],
                    'isShowMultiple' => $parentProcessRow['IS_SHOW_MULTIPLE'],
                    'data' => $childData
                );

                $getDtlSidebar = array();
                
                $renderFirstLevelDtl = (new Mdwebservice())->renderFirstLevelDtl($uniqId, $processMetaDataId, $row, $getDtlSidebar, true, array($groupPathLower => $responseData));
                
                $result = array(
                    'total' => $total, 
                    'filtercount' => 0, 
                    'gridBodyData' => issetParam($renderFirstLevelDtl['gridBodyData']), 
                    'aggregateStr' => $aggregateStr
                );
                
                if (isset($setPageNumber)) {
                    $result['pageNumber'] = $page;
                }
            }
            
        } else {
            $result = array(
                'total' => $total, 
                'filtercount' => 0, 
                'gridBodyData' => '', 
                'aggregateStr' => $aggregateStr
            );
        }
        
        return $result;
    }
    
    public function getPage($array, $pageSize, $page = 1) {
        
        $page = $page < 1 ? 1 : $page;
        $start = ($page - 1) * $pageSize;
        
        return array_slice($array, $start, $pageSize, true);
    }
    
    public function deleteRowModel() {
        
        $cacheDir = Mdcommon::getCacheDirectory();
        
        $processId = Input::post('processId');
        $cacheId = Input::post('cacheId');
        $groupPath = Input::post('groupPath');
        $rowIndex = Input::post('rowIndex');
        $groupPathLower = strtolower($groupPath);
        
        $cachePath = $cacheDir.'/getData/'.$cacheId.'.txt';
        $cacheStr = file_get_contents($cachePath);
        
        eval('$cacheArray = '.$cacheStr.';'); 
        
        if (isset($cacheArray[$groupPathLower][$rowIndex])) {
            
            if (Input::postCheck('cacheRemove') == false) {
                
                $cacheRow = $cacheArray[$groupPathLower][$rowIndex];
                $cacheRowId = issetParam($cacheRow['id']);
                
                if ($cacheRowId) {
                    
                    $isIgnoreRemovedRowState = Input::numeric('isIgnoreRemovedRowState');
                
                    $cacheArray[$groupPathLower.'_deletedrows'][$rowIndex] = $cacheRow;

                    if ($isIgnoreRemovedRowState) {

                        $rowFields = $cacheArray[$groupPathLower.'_deletedrows'][$rowIndex];
                        $cacheArray[$groupPathLower.'_deletedrows'][$rowIndex]['isignoreremovedrowstate'] = '1';

                        foreach ($rowFields as $rowFieldKey => $rowField) {
                            if (is_array($rowField) && isset($rowField['id']) && !isset($rowField['rowdata'])) {
                                unset($cacheArray[$groupPathLower.'_deletedrows'][$rowIndex][$rowFieldKey]);
                            }
                        }

                    } else {
                        $cacheArray[$groupPathLower.'_deletedrows'][$rowIndex]['rowstate'] = 'removed';
                    }
                }
            }
            
            unset($cacheArray[$groupPathLower][$rowIndex]);

            $this->load->model('mdwebservice', 'middleware/models/');
            $bpConfig = $this->model->getShowInputParams($processId);
            
            $aggregateStr = '';
            
            if (isset($bpConfig['pagerConfig']) && isset($bpConfig['pagerConfig'][$groupPathLower]) 
                && isset($bpConfig['pagerConfig'][$groupPathLower]['aggregateColumns'])) {
                
                if ($aggregateColumns = (new Mdcache())->calcAggregateColumns($bpConfig['pagerConfig'][$groupPathLower]['aggregateColumns'], $cacheArray[$groupPathLower])) {
                    
                    $cacheArray[$groupPathLower.'_aggregatecolumns'] = $aggregateColumns;
                    $aggregateStr = http_build_query($aggregateColumns);
                }
            }
            
            file_put_contents($cachePath, var_export($cacheArray, true));
            
            $response = array('status' => 'success', 'aggregateStr' => $aggregateStr);
            
        } else {
            $response = array('status' => 'warning', 'message' => "'$rowIndex' уг мөр олдсонгүй");
        }
        
        return $response;
    }
    
}