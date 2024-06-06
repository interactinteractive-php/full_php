<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdcommon Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Common
 * @author	B.Och-Erdene <ocherdene@interactive.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdcommon
 */
class Mdcommon extends Controller {

    private static $viewPath = 'middleware/views/common/';
    public static $separator = '♠';

    public function __construct() {
        parent::__construct();
    }
    
    public static function getCacheDirectory() {
        
        if (defined('CACHE_PATH') && CACHE_PATH != '') {
            $tmp_dir = CACHE_PATH;
        } else {
            $tmp_dir = ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : sys_get_temp_dir();
        }
        
        return $tmp_dir;
    }

    public function deleteConfirm() {
        $response = array(
            'Html' => 'Та устгахдаа итгэлтэй байна уу?',
            'Title' => 'Сануулах',
            'yes_btn' => Lang::line('yes_btn'),
            'no_btn' => Lang::line('no_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function windowCloseConfirm() {
        $response = array(
            'Html' => 'Та хаахдаа итгэлтэй байна уу?',
            'Title' => 'Сануулах',
            'yes_btn' => Lang::line('yes_btn'),
            'no_btn' => Lang::line('no_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function rowRemoveConfirm() {
        $response = array(
            'Html' => 'Та уг мөрийг устгахдаа итгэлтэй байна уу?',
            'Title' => 'Сануулах',
            'yes_btn' => Lang::line('yes_btn'),
            'no_btn' => Lang::line('no_btn')
        );
        echo json_encode($response); exit;
    }

    public static function svgIconByColor($colorCode = 'EE2C24', $icon = 'marker', $header = true) {
        if ($header) {
            header('Content-type: image/svg+xml');
            echo self::svgIconContent($icon, $colorCode);
        } else {
            return self::svgIconContent($icon, $colorCode);
        }
    }
    
    public function svgIconJsonByColor($colorCode = 'EE2C24', $icon = 'svg') {
        echo json_encode(self::svgIconContent($icon, $colorCode)); exit;
    }
    
    public static function svgIconList() {
        return array('marker', 'vehicle', 'truck');
    }
    
    public static function svgIconContent($icon, $colorCode) {
        $string = '<?xml version="1.0" encoding="utf-8"?>';
        switch ($icon) {
            case 'marker': {
               $string .= '<svg version="1.1" class="svgIcon" id="marker" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="18.5px" height="30.3px" viewBox="0 0 18.5 30.3" enable-background="new 0 0 18.5 30.3" xml:space="preserve">';
                    $string .= '<path fill-rule="evenodd" clip-rule="evenodd" fill="#' . $colorCode . '" d="M9.2,0C4.1,0,0,3.9,0,8.6c0,1,0.3,2.4,0.7,3.3l8.5,18.4l8.6-18.5c0.4-0.8,0.7-2.2,0.7-3.2C18.5,3.9,14.3,0,9.2,0z"/>';
                    $string .= '<path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" d="M9,15.4c3.6,0,6.6-3,6.6-6.6c0-3.6-3-6.6-6.6-6.6c-3.6,0-6.6,3-6.6,6.6C2.4,12.4,5.4,15.4,9,15.4z"/>';
                    $string .= '<ellipse fill-rule="evenodd" clip-rule="evenodd" fill="#' . $colorCode . '" cx="9" cy="8.8" rx="5.4" ry="5.4"/>';
                $string .= '</svg>'; 
            };break;
            case 'vehicle' : {
                $string .= '<svg version="1.1" class="svgIcon" id="vehicle" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="18.5px" height="30.3px" viewBox="0 0 18.5 30.3" enable-background="new 0 0 18.5 30.3" xml:space="preserve">';
                    $string .= '<path fill="#' . $colorCode . '" d="M12.9,2.7C12.8,2.3,12.4,2,11.9,2H3.6C3.1,2,2.7,2.3,2.5,2.7L1,7.2v6C1,13.7,1.3,14,1.7,14h0.8c0.4,0,0.8-0.3,0.8-0.8v-0.8h9v0.8c0,0.4,0.3,0.8,0.8,0.8h0.8c0.4,0,0.8-0.3,0.8-0.8v-6L12.9,2.7z M3.6,10.2c-0.6,0-1.1-0.5-1.1-1.1C2.5,8.5,3,8,3.6,8s1.1,0.5,1.1,1.1C4.7,9.7,4.2,10.2,3.6,10.2z M11.9,10.2c-0.6,0-1.1-0.5-1.1-1.1c0-0.6,0.5-1.1,1.1-1.1S13,8.5,13,9.1C13,9.7,12.5,10.2,11.9,10.2z M2.5,6.5l1.1-3.4h8.3L13,6.5H2.5z"/>';
                $string .= '</svg>';
            }; break;
            case 'truck' : {
                $string .= '<svg version="1.1" class="svgIcon" id="truck" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="18.5px" height="30.3px" viewBox="-213.6 138 27.8 15.8" enable-background="new -213.6 138 27.8 15.8" xml:space="preserve">';
                    $string .= '<g id="meanicons_x5F_7">';
                        $string .= '<path fill="#'.$colorCode.'" d="M-193.5,149.1c-1.3,0-2.4,1.1-2.4,2.4c0,1.3,1.1,2.4,2.4,2.4c1.3,0,2.4-1.1,2.4-2.4C-191.2,150.1-192.2,149.1-193.5,149.1z M-193.5,152.5c-0.6,0-1.1-0.5-1.1-1.1c0-0.6,0.5-1.1,1.1-1.1c0.6,0,1.1,0.5,1.1,1.1C-192.4,152-192.9,152.5-193.5,152.5z"/>';
                        $string .= '<path fill="#'.$colorCode.'" d="M-209.2,149.1c-1.3,0-2.4,1.1-2.4,2.4c0,1.3,1.1,2.4,2.4,2.4c1.3,0,2.4-1.1,2.4-2.4C-206.9,150.1-207.9,149.1-209.2,149.1z M-209.2,152.5c-0.6,0-1.1-0.5-1.1-1.1c0-0.6,0.5-1.1,1.1-1.1c0.6,0,1.1,0.5,1.1,1.1C-208.1,152-208.6,152.5-209.2,152.5z"/>';
                        $string .= '<path fill="#'.$colorCode.'" d="M-186.2,138h-11.1h-0.5h-8c-0.2,0-0.4,0.2-0.4,0.4v2.6c0,0.2,0.2,0.4,0.3,0.4h4.1c0.2,0,0.3,0.1,0.4,0.2l-0.7,0.6c-0.1-0.1-0.2-0.2-0.4-0.2h-3.8v0h-0.4c-0.2,0-0.4,0.2-0.4,0.4l-1.6,1.7l-3,0.4c-0.3,0-0.4,0.2-0.4,0.4l-0.3,1.6v1.6c0,0.2-0.1,0.4-0.3,0.4l-0.3,1.9c0,0.2,0.2,0.5,0.4,0.5h0.3v0h0.3c0-0.3,0.1-0.6,0.2-0.8c0.5-1,1.5-1.8,2.7-1.8c0.4,0,0.8,0.1,1.1,0.2l0,0c0.9,0.4,1.5,1.3,1.7,2.4h0h0.5h9.3c0.2-1.5,1.5-2.7,3-2.7c1.6,0,2.8,1.2,3,2.7h4.2c0.3,0,0.4-0.2,0.4-0.5v-12.2C-185.8,138.2-186,138-186.2,138z M-203.1,144.2c0,0.2-0.2,0.5-0.4,0.5h-1.1h-1.1h-1.3c-0.2,0-0.5-0.2-0.5-0.4l0.9-1c0-0.2,0.2-0.4,0.4-0.4h0.4h2.1h0.1c0.2,0,0.4,0.2,0.4,0.4V144.2L-203.1,144.2z"/>';
                    $string .= '</g>';
                $string .= '</svg>';
            }; break;
        }
        return $string;
    }
    
    public static function getBpDefaultValuesParams($cache, $processId, $rowId) {
        
        $defValues = $cache->get('bpDefValues_'.$processId.'_'.$rowId);

        if ($defValues == null) {
            
            global $db;

            if (DB_DRIVER == 'oci8') {
                $subSql = "NOT REGEXP_LIKE(TMP.DEFAULT_VALUE, '^[[:digit:]]+$')";
            } elseif (DB_DRIVER == 'postgres9') {
                $subSql = "TMP.DEFAULT_VALUE ~ '^[0-9\.]+$'";
            }

            $defValues = $db->GetAll(
                "SELECT 
                    TMP.* 
                FROM (
                    SELECT 
                        ".$db->IfNull('PAL4.PARAM_REAL_PATH', $db->IfNull('PAL3.PARAM_REAL_PATH', $db->IfNull('PAL2.PARAM_REAL_PATH', 'PAL.PARAM_REAL_PATH')))." AS PARAM_REAL_PATH, 
                        ".$db->IfNull('CF.DEFAULT_VALUE', $db->IfNull('PAL3.DEFAULT_VALUE', $db->IfNull('PAL2.DEFAULT_VALUE', 'PAL.DEFAULT_VALUE')))." AS DEFAULT_VALUE, 
                        ".$db->IfNull('PAL4.LOOKUP_META_DATA_ID', $db->IfNull('PAL3.LOOKUP_META_DATA_ID', $db->IfNull('PAL2.LOOKUP_META_DATA_ID', 'PAL.LOOKUP_META_DATA_ID')))." AS LOOKUP_META_DATA_ID, 
                        ".$db->IfNull('PAL4.LOOKUP_TYPE', $db->IfNull('PAL3.LOOKUP_TYPE', $db->IfNull('PAL2.LOOKUP_TYPE', 'PAL.LOOKUP_TYPE')))." AS LOOKUP_TYPE, 
                        ".$db->IfNull('PAL4.DATA_TYPE', $db->IfNull('PAL3.DATA_TYPE', $db->IfNull('PAL2.DATA_TYPE', 'PAL.DATA_TYPE')))." AS DATA_TYPE 
                    FROM META_PROCESS_PARAM_ATTR_LINK PAL 
                        LEFT JOIN META_PROCESS_PARAM_ATTR_LINK PAL2 ON PAL2.PARENT_ID = PAL.ID 
                            AND PAL2.PROCESS_META_DATA_ID = PAL.PROCESS_META_DATA_ID AND PAL2.IS_INPUT = 1 
                        LEFT JOIN META_PROCESS_PARAM_ATTR_LINK PAL3 ON PAL3.PARENT_ID = PAL2.ID 
                            AND PAL3.PROCESS_META_DATA_ID = PAL2.PROCESS_META_DATA_ID AND PAL3.IS_INPUT = 1 
                        LEFT JOIN META_PROCESS_PARAM_ATTR_LINK PAL4 ON PAL4.PARENT_ID = PAL3.ID 
                            AND PAL4.PROCESS_META_DATA_ID = PAL3.PROCESS_META_DATA_ID AND PAL4.IS_INPUT = 1 
                        LEFT JOIN CUSTOMER_DV_FIELD CF ON CF.META_DATA_ID = PAL.PROCESS_META_DATA_ID 
                            AND (CF.FIELD_PATH = PAL.PARAM_REAL_PATH OR CF.FIELD_PATH = PAL2.PARAM_REAL_PATH OR CF.FIELD_PATH = PAL3.PARAM_REAL_PATH OR CF.FIELD_PATH = PAL4.PARAM_REAL_PATH)     
                    WHERE PAL.PROCESS_META_DATA_ID = ".$db->Param(0)." 
                        AND PAL.IS_INPUT = 1 
                        AND PAL.PARENT_ID = ".$db->Param(1)." 
                ) TMP 
                WHERE TMP.DEFAULT_VALUE IS NOT NULL 
                    AND $subSql", array($processId, $rowId));
            
            $cache->set('bpDefValues_'.$processId.'_'.$rowId, $defValues, Mdwebservice::$expressionCacheTime);
        }
        
        return $defValues;
    }
    
    public static function setValueBpDtlRowHtml($cache, $processId, $rowId, $html) {
        
        $valuesParams = self::getBpDefaultValuesParams($cache, $processId, $rowId);

        if ($valuesParams) {

            if (!class_exists('phpQuery')) {
                loadPhpQuery();
            }
            
            $detailHtml = phpQuery::newDocumentHTML($html);

            foreach ($valuesParams as $valuesParam) {

                $path = $valuesParam['PARAM_REAL_PATH']; $val = $valuesParam['DEFAULT_VALUE'];

                $matches = $detailHtml->find('input[data-path="'.$path.'"],select[data-path="'.$path.'"],textarea[data-path="'.$path.'"]');

                if ($matches->length) {
                    $val = Mdmetadata::setDefaultValue($val);
                    pq($matches[0])->val($val);
                }
            }

            $html = $detailHtml->html();
        }
        
        return $html;
    }
    
    public function cryptEncodeToDecodeByPost() {
        
        $processId = Input::numeric('processId');
        $rowId = Input::numeric('rowId');
        $html = Crypt::decrypt(Input::post('string'));
        
        if ($processId && $rowId) {
            
            $cache = phpFastCache();
            $html = self::setValueBpDtlRowHtml($cache, $processId, $rowId, $html);
        }
        
        echo $html; exit;
    }
    
    public static function getArrayProcessDetailParamsArray($processId, $rowId, $uniqId, $isPager, $groupPath, $groupFieldPathLower) {
        
        loadPhpQuery();
        
        $cache = phpFastCache();
        
        $bpDtlAddHtml = $cache->get('bpDtlAddDtl_'.$processId.'_'.$rowId);
        $bpDtlAddHtml = preg_replace('/bpAddDtlRow_\d+/', 'bpAddDtlRow_'.$uniqId, $bpDtlAddHtml);
        $bpDtlAddHtml = preg_replace('/bpAddMainMultiRow_\d+/', 'bpAddMainMultiRow_'.$uniqId, $bpDtlAddHtml);
        
        $rowData = null;
        
        if ($bpDtlAddHtml) {
            $bpDtlAddHtml = self::setValueBpDtlRowHtml($cache, $processId, $rowId, $bpDtlAddHtml);
        }
        
        if ($isPager) {
            
            $rowData = $cache->get('bpDtlData_'.$processId.'_'.$rowId);
            
            if ($rowData == null) {
            
                $bpDtlAddHtmlTemp = $bpDtlAddHtml;
                $bpDtlAddHtmlTemp = str_replace('name="param['.$groupPath.'.', 'name="param[', $bpDtlAddHtmlTemp);
                $bpDtlAddHtmlTemp = preg_replace('/name="param\[(.*?)\]\[\]"/i', 'name="$1"', $bpDtlAddHtmlTemp);
                $bpDtlAddHtmlTemp = preg_replace('/name="param\[(.*?)\]\[0\]\[\]"/i', 'name="$1"', $bpDtlAddHtmlTemp);
                $bpDtlAddHtmlTemp = str_replace('][0', '', $bpDtlAddHtmlTemp);
                
                $detailHtml = phpQuery::newDocumentHTML($bpDtlAddHtmlTemp);
                $detailParam = $detailHtml->find('input, select, textarea')->serializeArray();
                
                $rowData = array();

                foreach ($detailParam as $param) {

                    $name = strtolower($param['name']);
                    $value = $param['value'];

                    if ($name != 'mainrowcount') {

                        if (strpos($name, '.') !== false) {
                            Arr::assignArrayByPath($rowData, $name, $value);
                        } else {
                            $rowData[$name] = $value;
                        }
                    }
                }
                
                $cache->set('bpDtlData_'.$processId.'_'.$rowId, $rowData, Mdwebservice::$expressionCacheTime);
            }
        }
        
        return array('rowHtml' => $bpDtlAddHtml, 'rowData' => $rowData);
    }
    
    public function renderBpDtlRow() {
        
        $processId = Input::numeric('processId');
        $rowId = Input::numeric('rowId');
        $uniqId = Input::numeric('uniqId');
        $pageSize = Input::post('pageSize');
        $groupPath = Input::post('groupPath');
        $groupFieldPathLower = strtolower($groupPath);
        
        $detailHtmlData = self::getArrayProcessDetailParamsArray($processId, $rowId, $uniqId, $pageSize, $groupPath, $groupFieldPathLower);
        $bpDtlAddHtml = $detailHtmlData['rowHtml'];
                
        if ($pageSize) {
            
            $cacheId = $uniqId;
            $isEditMode = Input::post('isEditMode');
            $rowData = $detailHtmlData['rowData'];
            
            $this->load->model('mdexpression', 'middleware/models/');
            $expression = $this->model->getCacheExpressionModel($processId);
            
            $this->load->model('mdwebservice', 'middleware/models/');
            $returnData = $this->model->getShowInputParams($processId);
            
            $cacheParams = Mdcache::getDetailRowsFromCache($cacheId);
            
            $cacheArray  = $cacheParams['cacheArray'];
            $cachePath   = $cacheParams['cachePath'];
            
            if (Input::postCheck('params')) {
                
                parse_str($_POST['params'], $paramsArr);
                $paramsArr = isset($paramsArr['param']) ? Arr::changeKeyLower($paramsArr['param']) : $paramsArr;
                $groupRows = $cacheArray[$groupFieldPathLower];
                
                $cacheArray[$groupFieldPathLower] = Mdcache::modifiedGroupRows($cachePath, $groupRows, $paramsArr, $groupFieldPathLower);  
            } 
            
            $isCachedGroup = false; 
                    
            if (isset($cacheArray[$groupFieldPathLower])) {
                        
                $isCachedGroup = true; 
                $currentPageTotal = Input::post('currentPageTotal');

                if ($currentPageTotal > $pageSize) {
                    $divide = $currentPageTotal / $pageSize;

                    if (strpos($divide, '.') !== false) {
                        $pageNumberCeil = ceil($divide);
                        $pageNumbers = ($pageNumberCeil ? $pageNumberCeil : 1);
                    } else {
                        $pageNumbers = $divide + 1;
                    }

                } elseif ($currentPageTotal == $pageSize) {
                    $pageNumbers = 2;
                } else {
                    $pageNumbers = 1;
                }  

            } else {
                $pageNumbers = 1;
            }
            
            $isRowExpression = false;
            
            if ($groupExpression = issetParam($expression['add_row_'.$groupFieldPathLower])) {
                
                parse_str($_POST['headerData'], $headerDataArr);
                $headerData = isset($headerDataArr['param']) ? Arr::changeKeyLower($headerDataArr['param']) : $headerDataArr;

                $groupExpression = str_replace(
                    array(Mdexpression::$cachePrefix, Mdexpression::$cachePrefixHeader), 
                    array('$rowData',                 '$headerData'), 
                $groupExpression);
                    
                eval($groupExpression);
                
                $isRowExpression = true;
            }
            
            if ($isCachedGroup && $detailData = $cacheArray[$groupFieldPathLower]) {
                
                end($detailData);
                $lastKey = key($detailData) + 1;
                
                $pushData = array($lastKey => $rowData);
                
            } else {
                
                $lastKey = 0;
                $pushData = array($rowData);
            }
            
            $responseData = (new Mdcache())->pushDataSplice($returnData['pagerConfig'], $cacheId, $pageSize, $pageNumbers, $groupFieldPathLower, $cacheArray, $pushData, $isCachedGroup, $isEditMode);
            $detailDataCount = $responseData[$groupFieldPathLower.'_total'];
            
            $isAppend = false;
            
            if ($detailDataCount < $pageSize || Input::postCheck('append')) {
                $isAppend = true;
            }
            
            if ($isRowExpression == false && $isAppend) {

                $bpDtlAddHtmlFill = '';

                foreach ($pushData as $dk => $dv) {
                    $bpDtlAddHtmlReplace = str_replace('name="param['.$groupPath.'.mainRowCount][]"', 'name="param['.$groupPath.'.mainRowCount][]" value="'.$lastKey.'"', $bpDtlAddHtml);
                    $bpDtlAddHtmlReplace = str_replace('][0][]', ']['.$lastKey.'][]', $bpDtlAddHtmlReplace);
                    $bpDtlAddHtmlFill .= $bpDtlAddHtmlReplace;
                }

                $response = array(
                    'html'       => $bpDtlAddHtmlFill, 
                    'count'      => $detailDataCount, 
                    'pageNumber' => $pageNumbers, 
                    'append'     => '1'
                );
                
                if (isset($responseData[$groupFieldPathLower.'_aggregatecolumns'])) {
                    $response['aggregate'] = http_build_query($responseData[$groupFieldPathLower.'_aggregatecolumns']);
                }

                echo json_encode($response); exit;
                
            } else {
                
                $this->load->model('mdwebservice', 'middleware/models/');
                
                $parentProcessRow = $this->model->isFirstLevelProcessModel($processId, $groupPath);
                $childData = $this->model->onlyShowGroupParamsDataModel($processId, $parentProcessRow['ID']);

                $row = array(
                    'id'             => $parentProcessRow['ID'],
                    'code'           => $groupPath,
                    'recordtype'     => 'rows',
                    'isShowAdd'      => $parentProcessRow['IS_SHOW_ADD'],
                    'isShowDelete'   => $parentProcessRow['IS_SHOW_DELETE'],
                    'isShowMultiple' => $parentProcessRow['IS_SHOW_MULTIPLE'],
                    'data'           => $childData
                );
                $getDtlRowsPopup = array();
                
                if ($isRowExpression && $isAppend) {
                    
                    $detailData = $responseData[$groupFieldPathLower];
                
                    end($detailData);
                    $lastKey = key($detailData);
                    
                    $addRow = $responseData[$groupFieldPathLower][$lastKey];
                    unset($responseData[$groupFieldPathLower]);
                    
                    $responseData[$groupFieldPathLower][$lastKey] = $addRow;
                    
                    $isAppendSet = true;
                }
                
                $renderFirstLevelDtl = (new Mdwebservice())->renderFirstLevelDtl($uniqId, $processId, $row, $getDtlRowsPopup, true, $responseData);
                    
                $response = array(
                    'html'       => $renderFirstLevelDtl['gridBodyData'], 
                    'count'      => $detailDataCount, 
                    'pageNumber' => $pageNumbers, 
                    'append'     => '0'
                );
                
                if (isset($responseData[$groupFieldPathLower.'_aggregatecolumns'])) {
                    $response['aggregate'] = http_build_query($responseData[$groupFieldPathLower.'_aggregatecolumns']);
                }
                
                if (isset($isAppendSet)) {
                    $response['append'] = '1';
                }

                echo json_encode($response, JSON_UNESCAPED_UNICODE); exit;
            }
            
        } else {
            echo $bpDtlAddHtml; exit;
        }
    }
    
    public function renderBpThemeDtlRow() {
        
        $cache = phpFastCache();
        
        $processId = Input::post('processId');
        $rowId = Input::post('rowId');
        
        $bpDtlAddHtml = $cache->get('bpDtlAddDtl_'.$processId.'_'.$rowId);
        
        echo $bpDtlAddHtml; exit();
    }

    public function getAutoNumber() {
        $this->load->model('mdcommon', 'middleware/models/');
        
        if (Input::postCheck('storeKeeperKeyId')) {
            $param = array(
                'objectId' => Input::post('objectId'),
                'bookTypeId' => Input::post('bookTypeId'),
                'storeKeeperKeyId' => Input::post('storeKeeperKeyId')
            );
        } else {
            $param = array(
                'objectId' => Input::post('objectId'),
                'bookTypeId' => Input::post('bookTypeId')
            ); 
            
            if (Input::isEmpty('bookDate') == false) {
                $param['bookDate'] = Input::post('bookDate');
            }
        }
        $autoNum = $this->model->getAutoNumberModel($param);
        
        echo json_encode($autoNum); exit;
    }
    
    public static function standartColorClass() {
        return array(
            array(
                'id' => 'btn-secondary',
                'class' => 'btn-secondary',
                'name' => 'No color(default)'
            ),
            array(
                'id' => 'btn-primary',
                'class' => 'btn-primary',
                'name' => 'Primary'
            ),
            array(
                'id' => 'btn-success',
                'class' => 'btn-success',
                'name' => 'Success'
            ),
            array(
                'id' => 'btn-info',
                'class' => 'btn-info',
                'name' => 'Info'
            ),
            array(
                'id' => 'btn-warning',
                'class' => 'btn-warning',
                'name' => 'Warning'
            ),
            array(
                'id' => 'btn-danger',
                'class' => 'btn-danger',
                'name' => 'Danger'
            ),
            array(
                'id' => 'btn-link',
                'class' => 'btn-link',
                'name' => 'Link'
            ),
            array(
                'id' => 'blue',
                'class' => 'bg-blue',
                'name' => 'Blue'
            ),
            array(
                'id' => 'blue-hoki',
                'class' => 'bg-blue-hoki',
                'name' => 'Blue hoki'
            ),
            array(
                'id' => 'blue-steel',
                'class' => 'bg-blue-steel',
                'name' => 'Blue steel'
            ),
            array(
                'id' => 'blue-madison',
                'class' => 'bg-blue-madison',
                'name' => 'Blue madison'
            ),
            array(
                'id' => 'blue-chambray',
                'class' => 'bg-blue-chambray',
                'name' => 'Blue chambray'
            ),
            array(
                'id' => 'blue-ebonyclay',
                'class' => 'bg-blue-ebonyclay',
                'name' => 'Blue ebonyclay'
            ),
            array(
                'id' => 'green',
                'class' => 'bg-green',
                'name' => 'Green'
            ),
            array(
                'id' => 'green-meadow',
                'class' => 'bg-green-meadow',
                'name' => 'Green meadow'
            ),
            array(
                'id' => 'green-seagreen',
                'class' => 'bg-green-seagreen',
                'name' => 'Green seagreen'
            ),array(
                'id' => 'green-turquoise',
                'class' => 'bg-green-turquoise',
                'name' => 'Green turquoise'
            ),
            array(
                'id' => 'green-haze',
                'class' => 'bg-green-haze',
                'name' => 'Green haze'
            ),
            array(
                'id' => 'green-jungle',
                'class' => 'bg-green-jungle',
                'name' => 'Green jungle'
            ),
            array(
                'id' => 'red',
                'class' => 'bg-red',
                'name' => 'Red'
            ),
            array(
                'id' => 'red-pink',
                'class' => 'bg-red-pink',
                'name' => 'Red pink'
            ),
            array(
                'id' => 'red-sunglo',
                'class' => 'bg-red-sunglo',
                'name' => 'Red sunglo'
            ),
            array(
                'id' => 'red-intense',
                'class' => 'bg-red-intense',
                'name' => 'Red intense'
            ),
            array(
                'id' => 'red-thunderbird',
                'class' => 'bg-red-thunderbird',
                'name' => 'Red thunderbird'
            ),
            array(
                'id' => 'red-flamingo',
                'class' => 'bg-red-flamingo',
                'name' => 'Red flamingo'
            ),
            array(
                'id' => 'yellow',
                'class' => 'bg-yellow',
                'name' => 'Yellow'
            ),
            array(
                'id' => 'yellow-gold',
                'class' => 'bg-yellow-gold',
                'name' => 'Yellow gold'
            ),
            array(
                'id' => 'yellow-casablanca',
                'class' => 'bg-yellow-casablanca',
                'name' => 'Yellow casablanca'
            ),
            array(
                'id' => 'yellow-crusta',
                'class' => 'bg-yellow-crusta',
                'name' => 'Yellow crusta'
            ),
            array(
                'id' => 'yellow-lemon',
                'class' => 'bg-yellow-lemon',
                'name' => 'Yellow lemon'
            ),
            array(
                'id' => 'yellow-saffron',
                'class' => 'bg-yellow-saffron',
                'name' => 'Yellow saffron'
            ),
            array(
                'id' => 'purple',
                'class' => 'bg-purple',
                'name' => 'Purple'
            ),
            array(
                'id' => 'purple-plum',
                'class' => 'bg-purple-plum',
                'name' => 'Purple plum'
            ),
            array(
                'id' => 'purple-medium',
                'class' => 'bg-purple-medium',
                'name' => 'Purple medium'
            ),
            array(
                'id' => 'purple-studio',
                'class' => 'bg-purple-studio',
                'name' => 'Purple studio'
            ),
            array(
                'id' => 'purple-wisteria',
                'class' => 'bg-purple-wisteria',
                'name' => 'Purple wisteria'
            ),
            array(
                'id' => 'purple-seance',
                'class' => 'bg-purple-seance',
                'name' => 'Purple seance'
            ),
            array(
                'id' => 'grey',
                'class' => 'bg-grey',
                'name' => 'Grey'
            ),
            array(
                'id' => 'grey-cascade',
                'class' => 'bg-grey-cascade',
                'name' => 'Grey cascade'
            ),
            array(
                'id' => 'grey-silver',
                'class' => 'bg-grey-silver',
                'name' => 'Grey silver'
            ),
            array(
                'id' => 'grey-steel',
                'class' => 'bg-grey-steel',
                'name' => 'Grey steel'
            ),
            array(
                'id' => 'grey-cararra',
                'class' => 'bg-grey-cararra',
                'name' => 'Grey cararra'
            ),
            array(
                'id' => 'grey-gallery',
                'class' => 'bg-grey-gallery',
                'name' => 'Grey gallery'
            )
        );
    }
    
    public static function registerCyrillicLetters() {
        
        $alpha = array(
            array('code' => 'А'), 
            array('code' => 'Б'),
            array('code' => 'В'),
            array('code' => 'Г'),
            array('code' => 'Д'),
            array('code' => 'Е'),
            array('code' => 'Ё'),
            array('code' => 'Ж'),
            array('code' => 'З'),
            array('code' => 'И'),
            array('code' => 'Й'),
            array('code' => 'К'),
            array('code' => 'Л'),
            array('code' => 'М'),
            array('code' => 'Н'),
            array('code' => 'О'),
            array('code' => 'Ө'),
            array('code' => 'П'),
            array('code' => 'Р'),
            array('code' => 'С'),
            array('code' => 'Т'),
            array('code' => 'У'),
            array('code' => 'Ү'),
            array('code' => 'Ф'),
            array('code' => 'Х'),
            array('code' => 'Ц'),
            array('code' => 'Ч'),
            array('code' => 'Ш'),
            array('code' => 'Щ'),
            array('code' => 'Ъ'),
            array('code' => 'Ь'),
            array('code' => 'Ы'),
            array('code' => 'Э'),
            array('code' => 'Ю'),
            array('code' => 'Я')
        );

        return $alpha;
    }
    
    public function hardWindowAutoComplete() {                
        $q = Input::post('q');
        $type = Input::post('type');
        $metaDataId = Input::numeric('metaDataId');
        
        if ($metaDataId === null) {
            $this->load->model('mdmetadata', 'middleware/models/');
            $getMetaByCode = $this->model->getMetaDataByCodeModel(Input::post('metaDataCode'));
            $metaDataId = $getMetaByCode['META_DATA_ID'];
        }
        
        $this->load->model('mdobject', 'middleware/models/');
        
        if ($type === 'code') {
            if ($codeField = $this->model->getDataViewMetaValueCode($metaDataId)) {
                
                $idField = $this->model->getDataViewMetaValueId($metaDataId);
                $nameField = $this->model->getDataViewMetaValueName($metaDataId);
                        
                $criteria[$codeField][] = array(
                    'operator' => 'LIKE',
                    'operand' => $q.'%'
                );        
                
                $this->load->model('mdcommon', 'middleware/models/');
                $result = $this->model->getRowsDataViewByCodeNameModel($metaDataId, $criteria, $idField, $codeField, $nameField);
                        
                header('Content-Type: application/json');
                echo json_encode($result);
            }
        } else {
            header('Content-Type: application/json');
            echo json_encode(array());
        }
        exit;
    }    
    
    public function hardWindowComboData() {             
        $metaDataId = Input::numeric('metaDataId');
        
        if ($metaDataId === null) {
            $this->load->model('mdmetadata', 'middleware/models/');
            $getMetaByCode = $this->model->getMetaDataByCodeModel(Input::post('metaDataCode'));
            $metaDataId = $getMetaByCode['META_DATA_ID'];
        }        

        $this->load->model('mdcommon', 'middleware/models/');
        $result = $this->model->getRowsDataViewByMetaIdModel($metaDataId);

        header('Content-Type: application/json');
        echo json_encode($result); exit;
    }      
    
    public static function criteriaCondition($defaultCondition) {
        $html = '';
        if ($defaultCondition) {
            foreach ($defaultCondition as $key => $row) {
                $html .= '<li>'
                        . '<a href="javascript:;" class="li-criteriaCondition" data-criteria-condition="'. $row['value'] .'">'. $row['code'] .'</a>'
                    . '</li>';
            }
        }
        return $html;
    }
    
    public static function dataviewRenderCriteriaCondition($param, $control, $operandVal = '=', $position = 'left') {
        
        if ($param['LOOKUP_META_DATA_ID'] && $param['LOOKUP_TYPE'] && issetParam($param['IS_PASS_FILTER']) == '1') {
                
            $html = '<div class="input-group">
                '.$control.'
                <span class="input-group-btn">
                    <button type="button" class="btn default btn-bordered" tabindex="-1" onclick="dvOnlySearchForm(this, \''.$param['LOOKUP_META_DATA_ID'].'\', \''.$param['META_DATA_CODE'].'\');" style="border-top-left-radius:0!important; border-bottom-left-radius:0!important;"><i class="icon-folder-search"></i></button>
                </span>
            </div>';

        } else {
            
            $typeCode = $param['META_TYPE_CODE'];
            $defaultCondition = Info::defaultCriteriaCondition($typeCode);
            $html = $control;
            
            if ($defaultCondition) {
            
                $class = ($typeCode == 'date') ? 'input-group-date' : '';
                
                if ($typeCode != 'date' && $typeCode != 'datetime' && $typeCode != 'bigdecimal' 
                    && $typeCode != 'bigdecimal_null' && $typeCode != 'number' && $typeCode != 'integer' 
                    && $typeCode != 'decimal' && $typeCode != 'long') {
                    $operandVal = 'like';
                }

                if ($defaultOperator = issetParam($param['DEFAULT_OPERATOR'])) {
                    $operandVal = $defaultOperator;
                }

                $html .= '<span class="input-group-btn '. $class .' dv-filter-criteria-condition">';
                    $html .= '<button type="button" class="btn dropdown-toggle criteria-condition-btn dropdown-none-arrow" data-toggle="dropdown" aria-expanded="true" tabindex="-1">'.$operandVal.'</button>';

                    if ($position == 'top' && !empty($param['LOOKUP_META_DATA_ID']) && !empty($param['LOOKUP_TYPE'])) {

                        if ($param['LOOKUP_TYPE'] == 'popup') {
                            $html .= '<a href="javascript:;" class="btn btn-sm grey-cascade mdDataViewCriteria" onclick="addMdDataViewCriteria(this);" title="'.Lang::line('META_00057').'" tabindex="-1"><i class="icon-plus3 font-size-12"></i></a>';
                        }

                    } elseif ($position == 'top' && ($typeCode === 'bigdecimal' || $typeCode === 'integer')) { 
                        $html .= '<a href="javascript:;" class="btn btn-sm grey-cascade mdDataViewCriteria" onclick="addMdDataViewCriteria(this);" title="'.Lang::line('META_00057').'" tabindex="-1"><i class="icon-plus3 font-size-12"></i></a>';
                    }

                    $html .= Form::hidden(array('name' => 'criteriaCondition['. $param['META_DATA_CODE'] .']', 'value' => $operandVal)); 

                    $html .= '<ul class="dropdown-menu dropdown-menu-default dropdown-menu-right dropdown-menu-display" role="menu">'
                                . self::criteriaCondition($defaultCondition);
                    $html .= '</ul>';
                $html .= '</span>';
            }
        }
        
        return $html;
    }
    
    public static function dvRenderCriteria($param, $control) {
        
        $html = '';
        
        if ($param['LOOKUP_META_DATA_ID'] && $param['LOOKUP_TYPE'] && issetParam($param['IS_PASS_FILTER']) == '1') {
                
            $html .= '<div class="input-group">
                '.$control.'
                <span class="input-group-btn">
                    <button type="button" class="btn default btn-bordered" tabindex="-1" onclick="dvOnlySearchForm(this, \''.$param['LOOKUP_META_DATA_ID'].'\', \''.$param['META_DATA_CODE'].'\');" style="border-top-left-radius:0!important; border-bottom-left-radius:0!important;"><i class="icon-folder-search"></i></button>
                </span>
            </div>';

        } else {
            $html .= $control;  
        }
        
        return $html;
    }
    
    public static function criteriaCondidion($param, $control) {
        
        $html = '';
        
        if ($param['LOOKUP_META_DATA_ID'] && $param['LOOKUP_TYPE']) {
            
            if (issetParam($param['IS_PASS_FILTER']) == '1') {
                
                $html .= '<div class="input-group">
                    '.$control.'
                    <span class="input-group-btn">
                        <button type="button" class="btn default btn-bordered" tabindex="-1" onclick="dvOnlySearchForm(this, \''.$param['LOOKUP_META_DATA_ID'].'\', \''.$param['META_DATA_CODE'].'\');" style="border-top-left-radius:0!important; border-bottom-left-radius:0!important;"><i class="icon-folder-search"></i></button>
                    </span>
                </div>';
                
            } else {
                $html .= $control;  
            }
            
        } else {
            
            $selected = '';
            $metaTypeCode = $param['META_TYPE_CODE'];
            
            if ($metaTypeCode == 'boolean') {
                return '<div class="col-md-12 pl0 pr0">'.$control.Form::hidden(array('name' => 'criteriaCondition['.$param['META_DATA_CODE'].']','value' => '=')).'</div>';  
            }
            
            if ($metaTypeCode != 'date' && $metaTypeCode != 'datetime' && $metaTypeCode != 'bigdecimal' 
                    && $metaTypeCode != 'bigdecimal_null' && $metaTypeCode != 'number' && $metaTypeCode != 'integer' 
                    && $metaTypeCode != 'decimal' && $metaTypeCode != 'long') {
                $selected = 'like';
            }
            
            if ($defaultOperator = issetParam($param['DEFAULT_OPERATOR'])) {
                $selected = $defaultOperator;
            }
            
            $html .= '<div class="row pl10 pr10">';
                $html .= '<div class="col-md-2 pl0 pr0 dv-filter-criteria-condition">
                    '.Form::select(
                        array(
                            'name' => 'criteriaCondition['.$param['META_DATA_CODE'].']',
                            'class' => 'form-control form-control-sm right-radius-zero pl0 pr0',
                            'op_value' => 'value',
                            'op_text' => 'code',
                            'data' => Info::defaultCriteriaCondition($param['META_TYPE_CODE']),
                            'text' => 'notext', 
                            'value' => $selected, 
                            'tabindex' => '-1'
                        )
                    ).'
                </div>';
                $html .= '<div class="col-md-10 pl0 pr0">'.$control.'</div>';  
            $html .= '</div>';  
        }
        
        return $html;
    }
    
    public function dataviewCriteriaCondidion($param, $control) {
        
        $html = '';
        $className = 'col-md-12 col-sm-12';
        $defaultCriteriaCondition = Info::defaultCriteriaCondition($param['META_TYPE_CODE']);
        
        if ($defaultCriteriaCondition) {
            $className = 'col-md-10 col-sm-9';
        }
        
        if (!empty($param['LOOKUP_META_DATA_ID']) && !empty($param['LOOKUP_TYPE'])) {
            
            if ($param['LOOKUP_TYPE'] == 'popup') {
                $html .= '<div class="dataview-criteria-control" style="float: left; width: 91%">'.$control.'</div>';  
                $html .= '<div style="float: right; width: 9%; text-align: right"><a href="javascript:;" class="btn btn-sm grey-cascade" onclick="addDataViewCriteria(this);" title="'.Lang::line('META_00057').'"><i class="icon-plus3 font-size-12"></i></a></div>';  
            } else {
                $html .= $control;  
            }
            
        } else {
            
            $selected = '';
            $metaTypeCode = $param['META_TYPE_CODE'];
            
            if ($metaTypeCode != 'date' && $metaTypeCode != 'datetime' && $metaTypeCode != 'bigdecimal' 
                    && $metaTypeCode != 'bigdecimal_null' && $metaTypeCode != 'number' && $metaTypeCode != 'integer' 
                    && $metaTypeCode != 'decimal' && $metaTypeCode != 'long') {
                $selected = 'like';
            }
            
            if ($defaultOperator = issetParam($param['DEFAULT_OPERATOR'])) {
                $selected = $defaultOperator;
            }
            
            if ($metaTypeCode === 'bigdecimal' || $metaTypeCode === 'integer'){
                if ($defaultCriteriaCondition) {
                    $html .= '<div class="col-md-2 col-sm-3 pl0 pr0 dropdown-filter-">
                        '.Form::select(
                            array(
                                'name' => 'criteriaCondition['.$param['META_DATA_CODE'].'][]',
                                'class' => 'form-control form-control-sm right-radius-zero pl0 pr0',
                                'op_value' => 'value',
                                'op_text' => 'code',
                                'data' => $defaultCriteriaCondition,
                                'text' => 'notext', 
                                'widthPercent' => '100',
                                'value' => $selected
                            )
                        ).'
                    </div>';
                }
                $html .= '<div class="'. $className .' pl0 pr0">'.$control.'</div>';  
                
                $html = '<div class="dataview-criteria-control" style="float: left; width: 91%">'.$html.'</div>';  
                $html .= '<div style="float: right; width: 9%; text-align: right"><a href="javascript:;" class="btn btn-sm grey-cascade" onclick="addDataViewCriteria(this);" title="'.Lang::line('META_00057').'"><i class="icon-plus3 font-size-12"></i></a></div>';  
                
            } else {
                
                if ($defaultCriteriaCondition) {
                    $html .= '<div class="col-md-2 col-sm-3 pl0 pr0 dropdown-filter-">
                        '.Form::select(
                            array(
                                'name' => 'criteriaCondition['.$param['META_DATA_CODE'].']',
                                'class' => 'form-control form-control-sm right-radius-zero pl0 pr0',
                                'op_value' => 'value',
                                'op_text' => 'code',
                                'data' => $defaultCriteriaCondition,
                                'text' => 'notext', 
                                'widthPercent' => '100',
                                'value' => $selected
                            )
                        ).'
                    </div>';
                }
                $html .= '<div class="'. $className .' pl0 pr0">'.$control.'</div>';  
            }
        }
        
        return $html;
    }
    
    public function generateBpAjax() {
        
        Session::init();
        $logged = Session::isCheck(SESSION_PREFIX . 'LoggedIn');

        if ($logged == false) {
            Session::set(SESSION_PREFIX . 'LoggedIn', true);
            Session::set(SESSION_PREFIX . 'LoggedGuest', true);
        }
        
        $mdWebserviceCtrl = new Mdwebservice();
        $mdWebserviceCtrl->callMethodByMeta();        
    }
    
    public function personRegister() {
        
        $response = array(
            'title' => 'Бүртгэл', 
            'html'  => $this->view->renderPrint('person/add', self::$viewPath),
            'save_btn'  => $this->lang->line('save_btn'), 
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function savePersonRegister() {

        $result = $this->model->savePersonRegisterModel();

        header('Content-Type: application/json');
        echo json_encode($result); exit;
    }
    
    public function getPersonByIdKitUserId() {
        
        $userId = Input::post('userId');
        $result = $this->model->getPersonByIdKitUserIdModel($userId);

        header('Content-Type: application/json');
        echo json_encode($result); exit;
    }
    
    public function addPersonUploadPhoto() {

        $result = array();
        
        if (isset($_FILES['person_attach'])) {

            $photo = $_FILES['person_attach'];

            if ($photo['name'] != '') {
                
                $newPhotoName = 'photo_' . getUID();
                $photoExtension = strtolower(substr($photo['name'], strrpos($photo['name'], '.') + 1));
                $photoName = $newPhotoName . '.' . $photoExtension;
                
                Upload::$File = $photo;
                Upload::$method = 0;
                Upload::$SavePath = UPLOADPATH.'metavalue/photo_temp/original/';
                Upload::$ThumbPath = UPLOADPATH.'metavalue/photo_temp/thumb/';
                Upload::$NewWidth = 1000;
                Upload::$TWidth = 150;
                Upload::$NewName = $newPhotoName;
                Upload::$OverWrite = true;
                $uploadError = Upload::UploadFile();

                if ($uploadError == '') {

                    $thumbImage = UPLOADPATH.'metavalue/photo_temp/thumb/'.$photoName;
                    $origImage = UPLOADPATH.'metavalue/photo_temp/original/'.$photoName;
                    $thumbImageData = base64_encode(file_get_contents($thumbImage));
                    $origImageData = base64_encode(file_get_contents($origImage));
                    $mimeType = getMimetypeByExtension($photoExtension);

                    $result = array(
                        'extension' => $photoExtension, 
                        'mimeType' => $mimeType, 
                        'thumbBase64Data' => $thumbImageData, 
                        'origBase64Data' => $origImageData
                    );

                    @unlink($thumbImage);
                    @unlink($origImage);
                }
            }
            
            if (!empty($result)) {
                $response = array('status' => 'success', 'imageData' => $result, 'message' => 'Амжилттай нэмлээ');
            } else {
                $response = array('status' => 'error', 'message' => 'Алдаа гарлаа');
            }
            
        } else {
            $response = array('status' => 'info', 'message' => '');
        }
        
        echo json_encode($response); exit;
    }
    
    public function isClosedFiscalPeriod($date, $accountORdepartment = null) {
        
        global $db;
        
        $date = Date::formatter($date, 'Y-m-d');
        
        $count = $db->GetRow("
            SELECT 
                COUNT(ID) AS DATE_COUNT    
            FROM FIN_FISCAL_PERIOD 
            WHERE IS_CLOSED = 1 
                AND (
                    START_DATE <= ".$db->ToDate("'$date'", 'YYYY-MM-DD')." AND 
                    END_DATE >= ".$db->ToDate("'$date'", 'YYYY-MM-DD').")");
        
        if ($count['DATE_COUNT'] != '0') {
            
            return true;
            
        } elseif ($accountORdepartment) {

            $count = $db->GetRow("
                SELECT 
                    COUNT(ACCOUNT_ID) AS DATE_COUNT 
                FROM FIN_ACCOUNT FIN 
                    INNER JOIN (
                        SELECT 
                            DEPARTMENT_ID
                        FROM ORG_DEPARTMENT
                        START WITH DEPARTMENT_ID IN (
                            SELECT 
                                DISTINCT DEPARTMENT_ID 
                            FROM FIN_FISCAL_PERIOD_CLOSE_DEP T1 
                                INNER JOIN FIN_FISCAL_PERIOD T2 ON T2.ID = T1.FISCAL_PERIOD_ID
                                AND ".$db->ToDate("'$date'", 'YYYY-MM-DD')." BETWEEN T2.START_DATE AND T2.END_DATE)
                            CONNECT BY NOCYCLE PARENT_ID = PRIOR DEPARTMENT_ID 
                        ) T1 ON T1.DEPARTMENT_ID = FIN.DEPARTMENT_ID 
                  WHERE FIN.ACCOUNT_ID = $accountORdepartment");            

            if ($count['DATE_COUNT'] != '0') {
                return true;
            } else {

                $count = $db->GetRow("
                    SELECT 
                        COUNT(T1.FISCAL_PERIOD_ID) AS DATE_COUNT 
                    FROM FIN_FISCAL_PERIOD_CLOSE_ACC T1 
                        INNER JOIN FIN_FISCAL_PERIOD T2 ON T2.ID = T1.FISCAL_PERIOD_ID 
                            AND ".$db->ToDate("'$date'", 'YYYY-MM-DD')." BETWEEN T2.START_DATE AND T2.END_DATE 
                    WHERE T1.ACCOUNT_ID = $accountORdepartment");          
                        
                if ($count['DATE_COUNT'] != '0') {
                    
                    return true;  
                    
                } else {
                    
                    $count = $db->GetRow("
                        SELECT 
                            COUNT(ID) AS DATE_COUNT 
                        FROM FIN_FISCAL_PERIOD_CLOSE_ACC  
                        WHERE ACCOUNT_ID = $accountORdepartment 
                            AND CLOSE_DATE >= ".$db->ToDate("'$date'", 'YYYY-MM-DD'));          
                    
                    if ($count['DATE_COUNT'] != '0') {
                        return true;      
                    }
                }                      
            }           

            $prm = array(
                'bookDate' => $date, 
                'departmentId' => $accountORdepartment
            );

            $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, 'checkClosingInvoice', $prm);

            if ($result['status'] == 'error') {
                return true;
            }     
        } 
        
        return false;    
    }
    
    public function isClosedFiscalPeriodByPost() {
        $postDate = Input::post('date');
        $accountORdepartment = Input::post('accountORdepartment');
        
        if (self::isClosedFiscalPeriod($postDate, $accountORdepartment)) {
            echo 'true'; exit;
        }
        echo 'false'; exit;
    }
    
    public function showCheckListForm() {
        
        $this->view->tempId = Input::post('tempId'); 
        $this->view->refStructureId = Input::post('refStructureId');
        $row = Input::post('row');
        $this->view->oneSelectedRow = Arr::encode($row);
        $this->view->recordId = $row['id'];
        $this->view->dataViewId = Input::post('dataViewId');

        $this->view->checkList = $this->model->getBpCheckListModel($this->view->tempId, $this->view->refStructureId, $this->view->recordId);
        
        $this->view->groupedCheckList = Arr::groupByArray($this->view->checkList, 'GROUP_ID');
        
        $response = array(
            'html'  => $this->view->renderPrint('checklist/form', self::$viewPath),
            'title' => 'Check List', 
            'save_btn'  => $this->lang->line('save_btn'), 
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function saveCheckListForm() {
        
        $result = $this->model->saveCheckListFormModel();

        header('Content-Type: application/json');
        echo json_encode($result); exit;
    }

    public function getMetaValueName() {
        $result = $this->model->getMetaValueNameModel();
        echo json_encode($result); exit;
    }

    public function getDvResultAutoComplete() {
        $result = $this->model->getDvResultAutoCompleteModel();
        echo json_encode($result); exit;
    }
    
    public function chooseMetaByExp() {
        $msgPrc = Input::post('msgPrc');
        
        $msgPrcArr = explode('||', $msgPrc);
        
        if (count($msgPrcArr) > 0) {
            
            $processId = Input::post('processId');
            
            $this->load->model('mdwebservice', 'middleware/models/');
            $methodRow = $this->model->getMethodIdByMetaDataModel($processId);
            
            $this->view->methodCode = $methodRow['META_DATA_CODE'];
            $responseData = isset($_POST['responseData']['resultData']) && !empty($_POST['responseData']['resultData']) ? Arr::changeKeyLower($_POST['responseData']['resultData']) : array();
                    
            $this->load->model('mdcommon', 'middleware/models/');
            
            $this->view->array = array();
            
            foreach ($msgPrcArr as $msgPrcArrRow) {
                
                $msgPrcArrRowArr = explode('@@', $msgPrcArrRow);
                $message = trim($msgPrcArrRowArr[0]);
                $metaCode = trim($msgPrcArrRowArr[1]);
                $mappingParams = $defaultGet = '';
                
                if (strpos($metaCode, '[defaultGet]') !== false) {
                    $metaCode = str_replace('[defaultGet]', '', $metaCode);
                    $defaultGet = 1;
                }
                
                if (strpos($metaCode, 'map[') !== false) {
                    preg_match_all('/map\[(.*?)\]/i', $metaCode, $mapExpression);
                    
                    if (count($mapExpression[0]) > 0) {
                        
                        foreach ($mapExpression[1] as $ek => $ev) {

                            $evalStr = trim($ev);
                            $evalArrs = explode('|', $evalStr);
                            
                            foreach ($evalArrs as $evalArr) {
                                
                                $paramArrs = explode('@', $evalArr);
                                $outputParam = strtolower($paramArrs[0]);
                                $inputParam = $paramArrs[1];
                                
                                if (array_key_exists($outputParam, $responseData)) {
                                    $mappingParams .= $inputParam.'='.$responseData[$outputParam].'&';
                                } else {
                                    $mappingParams .= $inputParam.'='.$outputParam.'&';
                                }
                            }
                            
                            $mappingParams = rtrim($mappingParams, '&');
                            $metaCode = str_replace($mapExpression[0][$ek], '', $metaCode);
                        }
                    }
                    
                    $metaCode = trim($metaCode);
                }
                
                $metaRow = $this->model->getMetaRowByCodeModel($metaCode);
                
                if ($metaRow && !empty($metaRow['META_TYPE_CODE'])) {
                    $this->view->array[] = array(
                        'message' => $message, 
                        'metaCode' => $metaCode, 
                        'metaDataId' => $metaRow['META_DATA_ID'],  
                        'metaTypeCode' => $metaRow['META_TYPE_CODE'], 
                        'metaDataName' => $metaRow['META_DATA_NAME'], 
                        'mappingParams' => $mappingParams, 
                        'defaultGet' => $defaultGet
                    );
                }
            }
            
            $html = $this->view->renderPrint('expression/metaList', self::$viewPath);
        } else {
            $html = '';
        }
        
        $response = array(
            'html' => $html,
            'title' => $this->lang->line('choose_btn'), 
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function mapSvgRender() {
        $this->view->mapData = array(
            '01' => array(
                'color' => '#a56b13',
                'name' => 'Архангай',
                'count' => '45',
            ),
            '20' => array(
                'color' => '#0c224e',
                'name' => 'Улаанбаатар',
                'count' => '12',
            ),
            '06' => array(
                'color' => '#de615b',
                'name' => 'Дорноговь',
                'count' => '56',
            )
        );

        $this->view->render('header');
        $this->view->render('mapSvg', self::$viewPath);
        $this->view->render('footer');
    }
    
    public function saveFileByBase64() {
        
        $inputData = file_get_contents('php://input');
        @file_put_contents(BASEPATH.'log/service_response.log', $inputData);
        
        $postData = json_decode($inputData, true);
        
        if (!isset($postData['commandCode']) && !isset($postData['recordId']) && !isset($postData['fileList'])) {
            echo 'Oroltiin parameter todorhoi bus baina!'; exit;
        }
        
        $processCode = Input::param($postData['commandCode']);
        
        $this->load->model('mdwebservice', 'middleware/models/');
        $configRow = $this->model->getProcessConfigByCode($processCode);
        
        $refStructureId = $configRow['REF_META_GROUP_ID'];
        
        if (empty($refStructureId)) {
            echo 'Ref Structure todorhoilogdoogui baina!'; exit;
        }
        
        $recordId = Input::param($postData['recordId']);
        $fileList = Input::param($postData['fileList']); 
        $array = array();
        
        foreach ($fileList as $file) {
            $array['bp_photo_extension'][]  = $file['fileExtension'];
            $array['bp_photo_orig_data'][]  = $file['fileContent'];
            $array['bp_photo_thumb_data'][] = $file['fileContent'];
            $array['bp_photo_name'][]       = $file['fileName'];
        }
        
        $_POST = $array;
        
        $result = Mdwebservice::saveBpAddOn($refStructureId, $recordId);
        
        if ($result) {
            $status = 'success';
            $message = 'Success';
        } else {
            $status = 'error';
            $message = 'Error';
        }
        
        ob_start('ob_html_compress'); 
            $response = array(
                'status' => $status, 
                'message' => $message 
            );
            echo json_encode($response);
        ob_end_flush();
        
        exit();
    }
    
    public function saveNtrFingerDataCtrl() {
        $result = $this->model->saveNtrFingerDataModel();
        echo json_encode($result); exit;
    }    
    
    public function saveNtrUserFingerCtrl() {
        $result = $this->model->saveNtrUserFingerCtrlModel();
        echo json_encode($result); exit;
    }    
    
    public function numberToWordsByPost() {
        $number = Input::post('number');
        $currencyCode = Input::post('currencyCode');
        echo json_encode(amountToWords($number, $currencyCode)); exit;
    }
    
    public function checkDataPermissionByPost() {
        
        $objectCode = Input::post('objectCode');
        $actionId = Input::post('actionId');
        $recordId = Input::post('recordId');
                
        $result = $this->model->checkDataPermissionModel($objectCode, $actionId, $recordId);
        
        echo $result ? 'true' : 'false'; exit;
    } 
    
    public function checkDataPermission($objectCode, $actionCode, $recordId) {
        $this->load->model('mdcommon', 'middleware/models/');
        
        $actionCode = strtolower($actionCode);
        $actionId = '300101010000005';
    
        if ($actionCode == 'get') {
            $actionId = '300101010000004';
        } elseif ($actionCode == 'create') {
            $actionId = '300101010000001';
        } elseif ($actionCode == 'update') {
            $actionId = '300101010000002';
        } elseif ($actionCode == 'delete') {
            $actionId = '300101010000003';
        }
    
        $result = $this->model->checkDataPermissionModel($objectCode, $actionId, $recordId);
        return $result;
    } 
    
    public function hotkeys() {
        $response = array(
            'title' => 'Hot keys', 
            'html'  => $this->view->renderPrint('hotkey/hotkeys', self::$viewPath), 
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public static function comboBoolean() {
        $combo = Form::select(
            array(
                'data' => array(
                    array(
                        'value' => 'all', 
                        'text' => '---'
                    ), 
                    array(
                        'value' => '1', 
                        'text' => Lang::line('yes_btn')
                    ), 
                    array(
                        'value' => '0', 
                        'text' => Lang::line('no_btn')
                    )
                ), 
                'op_value' => 'value', 
                'op_text' => 'text', 
                'text' => 'notext'
            )
        );
        return $combo;
    }
    
    public function moneyBill() {
        $response = array(
            'title' => 'Мөнгөн дэвсгэрт', 
            'html' => $this->view->renderPrint('moneybill/moneybill', self::$viewPath), 
            'insert_btn' => 'Оруулах (F8)', 
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public static function clearUserBpDataTmpl($processId) {
        
        $tmp_dir = Mdcommon::getCacheDirectory();
        
        $userBpDataTmplFiles = glob($tmp_dir."/*/us/userBpDataTmpl_".$processId."_*.txt");
        foreach ($userBpDataTmplFiles as $userBpDataTmplFile) {
            @unlink($userBpDataTmplFile);
        }
        
        return true;
    }
    
    public function renderMoreMeta() {
        
        Auth::handleLogin();
        
        $moreMetaId = Input::numeric('moreMetaId');
        
        if ($moreMetaId) {
            
            $ids = Input::post('ids');
            
            if ($ids) {
                
                $this->load->model('mdmetadata', 'middleware/models/');
                $metaRow = $this->model->getMetaDataModel($moreMetaId);

                if ($metaRow) {
                    
                    if ($metaRow['META_TYPE_ID'] == Mdmetadata::$metaGroupMetaTypeId) {
                        
                        $this->load->model('mdobject', 'middleware/models/');
                        $attributes = $this->model->getDataViewMetaValueAttributes(null, null, $moreMetaId);
                        $configRow = $this->model->getDataViewConfigRowModel($moreMetaId);
                        
                        $idField = isset($attributes['id']) ? strtolower($attributes['id']) : 'id';
                        
                        $_POST['metaDataId'] = $moreMetaId;
                        $_POST['page'] = 1;
                        $_POST['rows'] = 100;
                        $_POST['defaultCriteriaData'] = 'criteriaCondition['.$idField.']=IN&param['.$idField.']=' . $ids;

                        $result = $this->model->dataViewDataGridModel();
                        
                        if ($result['status'] == 'success') {
                            
                            if (isset($result['rows'][0])) {
                                
                                $columns = $this->model->getOnlyShowColumnsModel($moreMetaId);
                                
                                $response = array('status' => 'success', 'columns' => $columns, 'rows' => $result['rows'], 'title' => $configRow['LIST_NAME']);
                                
                            } else {
                                $response = array('status' => 'error', 'message' => 'Бичлэг олдсонгүй!');
                            }
                            
                        } else {
                            $response = array('status' => 'error', 'message' => $result['message']);
                        }
                        
                    } elseif ($metaRow['META_TYPE_ID'] == Mdmetadata::$packageMetaTypeId) {
                        
                        $_POST['drillDownDefaultCriteria'] = 'filterId=' . $ids;
                        
                        (new Mdobject())->package($moreMetaId, 'json');
                        
                    } else {
                        $response = array('status' => 'error', 'message' => $metaRow['META_TYPE_CODE'] . ' not developed!');
                    }
                    
                    $response['metaType'] = $metaRow['META_TYPE_CODE'];
                    
                } else {
                    $response = array('status' => 'error', 'message' => $moreMetaId . ' үзүүлэлт олдсонгүй!');
                }
            
            } else {
                $response = array('status' => 'error', 'message' => 'Invalid record ids!');
            }
            
        } else {
            $response = array('status' => 'error', 'message' => 'Invalid meta id!');
        }
        
        jsonResponse($response);
    }
    
    public static function addCustomFonts($type) {
        
        $fonts = Config::getFromCache('reportCustomFonts');
        $result = '';
        
        if ($fonts) {
            
            $fontsArr = explode(',', $fonts);
            
            if ($type == 'jsCommaPath') {
                
                foreach ($fontsArr as $font) {
                    $result .= "URL_APP+'assets/custom/webfonts/$font/font.css',";
                }
                
            } elseif ($type == 'editorFamily') {
                
                foreach ($fontsArr as $font) {
                    
                    if ($font == 'nextmuseo') {
                        $result .= 'Next Museo=Next_MuseoSansCyrl;';
                    } elseif ($font == 'roboto') {
                        $result .= "Roboto Condensed='Roboto Condensed';";
                    }
                }
                
            } elseif ($type == 'linkUrl') {
                
                foreach ($fontsArr as $font) {
                    $result .= '<link href="assets/custom/webfonts/'.$font.'/font.css" rel="stylesheet" type="text/css">';
                }
            }
        }
        
        return $result;
    }
    
    public function createThumbImage($dvId = '') 
    {   
        if ($dvId == '') {
            echo 'Жагсаалтын ID ирсэнгүй!'; exit;
        }
        
        $param = array(
            'systemMetaGroupId' => $dvId,
            'ignorePermission' => 1, 
            'showQuery' => 0
        );

        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

        if (isset($result['result']) && isset($result['result'][0])) {
            unset($result['result']['aggregatecolumns']);
            unset($result['result']['paging']);
            $data = $result['result'];
        } else {
            $data = null;
        }
        
        if ($data) {
            
            try {
                
                includeLib('Image/image-magician/php_image_magician');
            
                foreach ($data as $row) {
                    
                    $row['imagefieldname'] = strtolower($row['imagefieldname']);
                    
                    if (file_exists($row[$row['imagefieldname']]) && filesize($row[$row['imagefieldname']])) {

                        $fileInfo = pathinfo($row[$row['imagefieldname']]); 
                        $ext = strtolower($fileInfo['extension']);

                        if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif' || $ext == 'bmp') {

                            $path = $fileInfo['dirname'] . '/thumb';

                            if (!is_dir($path)) {
                                mkdir($path, 0777);
                            }

                            $image = new imageLib($row[$row['imagefieldname']]);

                            $smallThumb = $path . '/' . str_replace('.'.$ext, '_sm.'.$ext, $fileInfo['basename']);
                            $middleThumb = $path . '/' . str_replace('.'.$ext, '_mid.'.$ext, $fileInfo['basename']);

                            $image->resizeImage(100, 200, 'landscape', true);
                            $image->saveImage($smallThumb, 95);

                            $image->resizeImage(400, 500, 'landscape', true);
                            $image->saveImage($middleThumb, 95);
                            
                            if (issetParam($row['isecmcontent']) == '1') {
                                
                                $this->db->AutoExecute(
                                    $row['tablename'], 
                                    array(
                                        'THUMB_PHYSICAL_PATH' => $smallThumb, 
                                        'MIDDLE_PHYSICAL_PATH' => $middleThumb
                                    ), 
                                    'UPDATE', 
                                    $row['idfieldname'].' = '.$row['id']
                                );
                                
                            } else {
                                $this->db->AutoExecute(
                                    $row['tablename'], 
                                    array(
                                        $row['imagefieldname'].'_SMALL_THUMB' => $smallThumb, 
                                        $row['imagefieldname'].'_MIDDLE_THUMB' => $middleThumb
                                    ), 
                                    'UPDATE', 
                                    $row['idfieldname'].' = '.$row['id']
                                );
                            }

                            $isCreateThumb = true;
                            
                        } elseif (in_array($ext, array('doc', 'docx', 'xls', 'xlsx', 'csv', 'ppt', 'pptx', 'pdf'))) {
                    
                            $thumbConvertUrl = Config::getFromCache('thumbSourceFileUrl');
                            $convertFileToThumbUrl = Config::getFromCache('convertFileToThumbUrl');

                            if ($thumbConvertUrl && $convertFileToThumbUrl) {

                                $convertUrl = $convertFileToThumbUrl . $thumbConvertUrl . $row[$row['imagefieldname']] . '&pagenumber=1&width=400';

                                $thumbData = getImageDataByCurl($convertUrl);

                                if ($thumbData['status'] == 'success') {

                                    $thumbPath = $fileInfo['dirname'] . '/thumb';

                                    if (!is_dir($thumbPath)) {
                                        mkdir($thumbPath, 0777);
                                    }

                                    $thumbFilePath = $thumbPath . '/' . str_replace('.'.$ext, '_mid.jpeg', $fileInfo['basename']);
                                    $fileWrite = @file_put_contents($thumbFilePath, $thumbData['data']);

                                    if ($fileWrite) {

                                        includeLib('Image/image-magician/php_image_magician');

                                        $image = new imageLib($thumbFilePath);

                                        $smallThumb = $thumbPath . '/' . str_replace('.'.$ext, '_sm.jpeg', $fileInfo['basename']);

                                        $image->resizeImage(100, 200, 'landscape', true);
                                        $image->saveImage($smallThumb, 95);
                                        
                                        if (issetParam($row['isecmcontent']) == '1') {
                                
                                            $this->db->AutoExecute(
                                                $row['tablename'], 
                                                array(
                                                    'THUMB_PHYSICAL_PATH' => $smallThumb, 
                                                    'MIDDLE_PHYSICAL_PATH' => $thumbFilePath
                                                ), 
                                                'UPDATE', 
                                                $row['idfieldname'].' = '.$row['id']
                                            );

                                        } else {
                                            $this->db->AutoExecute(
                                                $row['tablename'], 
                                                array(
                                                    $row['imagefieldname'].'_SMALL_THUMB' => $smallThumb, 
                                                    $row['imagefieldname'].'_MIDDLE_THUMB' => $thumbFilePath
                                                ), 
                                                'UPDATE', 
                                                $row['idfieldname'].' = '.$row['id']
                                            );
                                        }
                                        
                                        $isCreateThumb = true;
                                    } 
                                }
                            }
                        }
                    }
                }

                if (isset($isCreateThumb)) {
                    echo 'Амжилттай үүсгэлээ!'; exit;
                }
                
            } catch (Exception $ex) {
                
                echo $ex->getMessage(); exit;
            }
            
        } else {
            echo 'Өгөгдөл олдсонгүй!'; exit;
        }
    }
    
    public function controlSubType($dataType, $lookupType, $isDv = false) {
        $this->load->model('mdcommon', 'middleware/models/');
        $result = $this->model->controlSubTypeModel($dataType, $lookupType, $isDv);
        return $result;
    }
    
    public static function parseCodeErrorMsg($msg) {
        
        $result = 'Unknown error';
        
        if (strpos($msg, 'Use of undefined constant') !== false) {
            preg_match('/constant(.*?)- assumed/i', $msg, $matches);
            
            if (isset($matches[1]) && $matches[1]) {
                $result = 'Шалгуур ажиллахад сонгосон мөрнөөс "'.trim($matches[1]).'" багана олдсонгүй!';
            }
        }
        
        return $result;
    }
    
    public function renderProcess($metaDataId = '') {
        
        if (!$metaDataId) {
            echo json_encode(array('status' => 'error', 'message' => 'Invalid process id!')); exit;
        }
        
        Session::init();
        $logged = Session::isCheck(SESSION_PREFIX.'LoggedIn');
        
        if ($logged == false) {
            Session::set(SESSION_PREFIX.'LoggedIn', true);
        }
        
        $_POST['metaDataId'] = $metaDataId;
        $_POST['isSystemMeta'] = 'false';
        $_POST['isDialog'] = 'true';
        $_POST['valuePackageId'] = '';
        
        (new Mdwebservice())->callMethodByMeta(); exit;
    }
    
    public static function titleReplacerByVar($title) {
        
        $constantKeys = array(
            '[sysdatetime]', 
            '[sysdate]', 
            '[sysyear]', 
            '[sysmonth]', 
            '[sysday]',
            '[systime]', 
            '[fiscalPeriodName]'
        );
        
        foreach ($constantKeys as $constantKey) {

            if (strpos($title, $constantKey) !== false) {
                
                if ($constantKey == '[sysdatetime]') {
                    $replaceVal = Date::currentDate('Y-m-d H:i:s');
                } elseif ($constantKey == '[sysdate]') {
                    $replaceVal = Date::currentDate('Y-m-d');
                } elseif ($constantKey == '[sysyear]') {
                    $replaceVal = Date::currentDate('Y');
                } elseif ($constantKey == '[sysmonth]') {
                    $replaceVal = Date::currentDate('m');
                } elseif ($constantKey == '[sysday]') {
                    $replaceVal = Date::currentDate('d');
                } elseif ($constantKey == '[systime]') {
                    $replaceVal = Date::currentDate('H:i');
                } elseif ($constantKey == '[fiscalPeriodName]') {
                    $replaceVal = '<span data-rpbyvar="fiscalPeriodName">'.Ue::getSessionFiscalPeriodName().'</span>';
                }
                
                $title = str_replace($constantKey, $replaceVal, $title);
            }
        }
    
        return $title;
    }
    
    public function base64ToImage() {
        
        $imagePath = Input::post('imagePath');
        $dataUrl = str_replace('data:image/png;base64,', '', $_POST['dataUrl']);
        $decoded = base64_decode($dataUrl); 
        
        @file_put_contents($imagePath, $decoded);
        
        if (file_exists($imagePath) && mime_content_type($imagePath) == 'image/png') {
            $response = array('status' => 'success');
        } else {
            $response = array('status' => 'error');
        }
        
        jsonResponse($response);
    }
    
    public static function expressionEvalFixWithReturn($evalStr) {
        
        try {
            
            $returnedVal = @eval('return ('.$evalStr.');');
            return $returnedVal;
            
        } catch (Throwable $p) {
            
            $msg = $p->getMessage();
            
            if (strpos($msg, 'Undefined constant') !== false) {
                
                $constant = str_replace('Undefined constant "', '', $msg);
                $constant = str_replace('"', '', $constant);
                
                define($constant, null);
                
                return self::expressionEvalFixWithReturn($evalStr);
            }
            
            return null;
        } 
    }
    
    public static function expressionEvalFix($evalStr) {
        
        try {
            
            $returnedVal = @eval($evalStr);
            return $returnedVal;
            
        } catch (Throwable $p) {
            
            $msg = $p->getMessage();
            
            if (strpos($msg, 'Undefined constant') !== false) {
                
                $constant = str_replace('Undefined constant "', '', $msg);
                $constant = str_replace('"', '', $constant);
                
                define($constant, null);
                
                return self::expressionEvalFix($evalStr);
            }
            
            return null;
        } 
    }
    
    public static function checkMatchValue($glue, $needle, $checkVal) {
        
        if ($needle != '') {
            $needleArr = explode($glue, $needle);
            foreach ($needleArr as $equalVal) {
                if ($equalVal == $checkVal) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    public static function redirectHelpContentButton($configCode) {
        
        $button    = null;
        $contentId = Config::getFromCache($configCode);
        
        if ($contentId) {
            $button = html_tag('button', [
                'type' => 'button', 
                'class' => 'btn btn-circle btn-sm btn-info bp-btn-help mr-1', 
                'onclick' => 'redirectHelpContent(this, \''.$contentId.'\', \''.$configCode.'\', \'pf_config\');'
            ], Lang::line('menu_system_guide'));
        }
        
        return $button;
    }
    
}
