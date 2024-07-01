<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdstatement Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Statement (Report)
 * @author	B.Och-Erdene, Ts.Ulaankhuu
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdstatement
 */

class Mdstatement extends Controller {

    private static $viewPath = 'middleware/views/statement/';
    public static $uniqId;
    public static $numIterations = 0;
    public static $rowsCount = 0;
    private static $addonTableLoopCount = 0;
    private static $childCount = 1;
    public static $isStatementModeNum = 0;
    public static $freezeLeftColumnCount = 0;
    private static $addonTableBody = null;
    private static $addonTableFoot = null;
    public static $autoGenerateGroupFooter = null;
    public static $tmpReportFooter = null;
    private static $isMultiDetail = false;
    public static $isWithDrillDown = false;
    public static $isHdrRepeatPage = false;
    public static $isIgnoreHdrRepeatPage = false;
    public static $isKpiIndicator = false;
    public static $isPivotView = false;
    public static $isFooterQrCode = false;
    public static $isAutoSearch = false;
    public static $isReportServer = false;
    public static $UIExpression = array();
    public static $dataViewColumnsType = array();
    public static $dataViewColumnsTypeScale = array();
    public static $dataViewColumnsSetScale = array();
    private static $addonTableBodyRows = array();
    public static $drillDownColumns = array();
    public static $isRenderColumn = array();
    public static $filterParams = array();
    private static $isRunGroupingRow = array();
    private static $isRunGroupingDv = array();
    public static $filterParamsLower = array();
    public static $truncAmountFields = array();
    public static $constantKeys = array();
    public static $pivotGrouping = array();
    public static $qrData = array();
    public static $data;

    public function __construct() {
        parent::__construct();        
    }

    public function index($metaDataId = '', $dialogMode = false) {
        
        Session::init();
        
        if ($metaDataId == '') {
            Message::add('e', '', 'back');
        }
        
        if (!isset($this->view)) {
            $this->view = new View();
        }
        
        $this->load->model('mdstatement', 'middleware/models/');
        
        $this->view->row = $this->model->getDefaultStatementModel(Input::param($metaDataId));
        
        if (!$this->view->row) {
            Message::add('e', '', 'back');
        }
        
        $this->view->metaDataId = $this->view->row['META_DATA_ID'];
        $this->view->title = $this->lang->line($this->view->row['REPORT_NAME']);
        
        $this->view->css = AssetNew::metaCss();
        $this->view->fullUrlCss = AssetNew::amChartCss();
        
        $this->view->js = array_unique(array_merge(
            AssetNew::metaOtherJs(),
            AssetNew::highchartJs()
        ));
        $this->view->fullUrlJs = AssetNew::amChartJs();
        $this->view->fillData = array();
        
        if (Input::postCheck('postData') && Input::isEmpty('postData') === false) {
            parse_str(Input::post('postData'), $this->view->fillData);
        }
        
        $getQryStr = Input::getData();
        
        if (isset($getQryStr['param'])) {
            
            foreach ($getQryStr['param'] as $paramName => $paramVal) {
                $this->view->fillData[$paramName] = Mdmetadata::setDefaultValue($paramVal);
            }
            
            $this->view->fillData = Arr::changeKeyLower($this->view->fillData);
        }
        
        if (isset($getQryStr['autosearch']) || $this->view->row['IS_AUTO_FILTER'] == '1') {
            $this->view->autoSearch = 1;
        } 
        
        $this->view->folderId = '';
        $this->view->metaBackLink = 'mdmetadata/system';
        
        if (isset($this->view->row['FOLDER_ID'])) {
            $this->view->folderId = $this->view->row['FOLDER_ID'];
            $this->view->metaBackLink = 'mdmetadata/system#objectType=folder&objectId=' . $this->view->row['FOLDER_ID'];
        }

        $this->view->isBackLink = Config::getFromCache('CONFIG_OBJECT_BACKLINK');
        $this->view->reportType = $this->view->row['REPORT_TYPE'];
        $this->view->isAjax = is_ajax_request();

        if (!$this->view->isAjax) {
            $this->view->render('header');
        }
        
        if ($dialogMode) {
            return $this->view->renderPrint('index', self::$viewPath);
        } else { 
            $this->view->render('index', self::$viewPath);
        }
        
        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }
    }
    
    public function dialogIndex($metaDataId) {
        $html = self::index($metaDataId, true);
        echo json_encode(array('Html' => $html)); exit;
    }
    
    public function sysKeywords() {
        $this->load->model('mdstatement', 'middleware/models/');
        return $this->model->getSysKeysModel();
    }
    
    public function getStatementHtmlRow($statementId) {
        $this->load->model('mdstatement', 'middleware/models/');
        return $this->model->getStatementHtmlRowModel($statementId);
    }
    
    public function reportViewer() {

        $metaDataId = Input::numeric('metaDataId');
        self::dataModelReportViewer($metaDataId);
    }
    
    public function dataModelReportViewer($metaDataId) {
        
        Auth::handleLogin();
        
        if (!isset($this->view)) {
            $this->view = new View();
        }
        
        $this->view->row = $this->model->getStatementRowModel($metaDataId);
        
        if (self::$isKpiIndicator == false) {
            $this->view->metaDataId = $this->view->row['META_DATA_ID'];
            $this->view->dataViewId = $this->view->row['DATA_VIEW_ID'];
        } else {
            $this->view->metaDataId = $this->view->row['MAIN_INDICATOR_ID'];
            $this->view->dataViewId = $this->view->row['DATA_INDICATOR_ID'];
            Mdform::$kpiTemplateId = $this->view->dataViewId;

            if ($this->view->row['KPI_TYPE_ID'] == '1045') {
                self::$isPivotView = true;
            } elseif ($this->view->row['KPI_TYPE_ID'] == '1044') {
                Mdform::$isRawDataMart = true;
            }
        }
        
        $this->view->reportType = $this->view->row['REPORT_TYPE'];
        $this->view->isSearchForm = true;
        $this->view->isSearchFormDisabled = false;
        $this->view->isClearButton = true;
        $this->view->isUserGroupingButton = false;
        $this->view->fillParamData = null;
        
        Mdexpression::$searchMainSelector = 'dataview_statement_search_'.$this->view->metaDataId;
        
        if (self::$isKpiIndicator == false) {
            
            if ($this->view->dataViewId) {

                $this->load->model('mdobject', 'middleware/models/');

                $dataViewSearchData = $this->model->dataViewHeaderDataModel($this->view->dataViewId);

                $this->load->model('mdstatement', 'middleware/models/');

                $this->view->dataViewSearchData = $this->model->dataViewHeaderDataResolveModel($dataViewSearchData);

            } elseif ($this->view->row['IS_BLANK'] == '1') {

                $this->view->isBlank = 1;
                $this->view->autoSearch = 1;
            }

            if (Input::postCheck('fillData') && Input::isEmpty('fillData') === false) {
                $this->view->fillParamData = Input::post('fillData');
            }

            if ((Input::postCheck('autoSearch') && Input::post('autoSearch') == '1') || $this->view->row['IS_AUTO_FILTER'] == '1') {

                $this->view->autoSearch = 1;

            } else {

                $isStatementAutoFilterPopup = Config::getFromCache('isStatementAutoFilterPopup');

                if ($isStatementAutoFilterPopup == '1') {
                    $this->view->popupSearch = $this->view->renderPrint('dataview/popupSearch', self::$viewPath);
                }
            }
            
        } else {
            
            $this->view->isBlank = 1;
            
            if (self::$isPivotView || $this->view->row['IS_AUTO_FILTER'] == '1') {
                $this->view->autoSearch = 1;
                self::$isAutoSearch = true;
            }
        }
        
        if ($this->view->row['COUNT_USER_GROUPING'] != '0') {
            $this->view->isUserGroupingButton = true;
        }
        
        $this->view->pageProperties = array(
            'reportName' => $this->lang->line($this->view->row['REPORT_NAME']), 
            'pageSize' => $this->view->row['PAGE_SIZE'], 
            'pageOrientation' => $this->view->row['PAGE_ORIENTATION'], 
            'pagePrint' => true,
            'pagePdf' => true, 
            'pagePdfView' => Config::getFromCache('CONFIG_STATEMENT_PDF_VIEW') ? true : false, 
            'pageExcel' => Config::getFromCache('CONFIG_STATEMENT_XLS_EXP') === '1' ? false : true,
            'pageWord' => Config::getFromCache('CONFIG_STATEMENT_DOC_EXP') === '1' ? false : true,
            'pageSearch' => true,
            'pageArchive' => ($this->view->row['IS_ARCHIVE'] == '1') ? true : false, 
            'pageMarginTop' => $this->view->row['PAGE_MARGIN_TOP'], 
            'pageMarginLeft' => $this->view->row['PAGE_MARGIN_LEFT'],
            'pageMarginRight' => $this->view->row['PAGE_MARGIN_RIGHT'],
            'pageMarginBottom' => $this->view->row['PAGE_MARGIN_BOTTOM'], 
            'pageWidth' => $this->view->row['PAGE_WIDTH'],
            'pageHeight' => $this->view->row['PAGE_HEIGHT'], 
            'fontFamily' => $this->view->row['FONT_FAMILY'], 
            'fontSize' => $this->view->row['FONT_SIZE'], 
            'dataViewId' => $this->view->dataViewId,
            'metaDataId' => $this->view->metaDataId, 
            'isIgnoreFooter' => $this->view->row['IS_EXPORT_NO_FOOTER']
        );
        
        if (self::$isPivotView) {
            $this->view->pageProperties['pageOrientation'] = 'landscape';
            $this->view->pageProperties['pageSize'] = 'pivot';
        }
        
        if (defined('CONFIG_REPORT_SERVER_ADDRESS') && CONFIG_REPORT_SERVER_ADDRESS && !Input::numeric('ignoreIframe')) {
            
            $layoutId = $this->model->getReportLayoutIdModel($this->view->row['META_DATA_ID']);
            
            if ($layoutId) {
                
                $this->view->reportLayoutId = $layoutId; 
                $this->view->pageProperties['reportLayoutId'] = $layoutId;
                
                $this->view->reportLayoutTemplateList = $this->model->getReportLayoutTemplateModel($this->view->row['META_DATA_ID']);
                
                if (Config::getFromCache('is_dev')) {
                    $this->view->isChooseReportFrame = true; 
                }
            }
        } 

        $this->view->searchForm = $this->view->renderPrint('dataview/search', self::$viewPath);
                
        $this->view->reportPreview = (new Mdpreview())->renderToolbar($this->view->pageProperties);
        
        if (self::$isKpiIndicator) {
            return $this->view->renderPrint('dataview/index', self::$viewPath);
        } 
        
        $this->view->render('dataview/index', self::$viewPath);
    }    
    
    public static function constantKeys() {
        
        if (!self::$constantKeys) {
            self::$constantKeys = array(
                '#sysdatetime#'           => Date::currentDate('Y-m-d H:i:s'), 
                '#sysdate#'               => Date::currentDate('Y-m-d'), 
                '#sysyear#'               => Date::currentDate('Y'), 
                '#sysmonth#'              => Date::currentDate('m'), 
                '#sysday#'                => Date::currentDate('d'),
                '#systime#'               => Date::currentDate('H:i'),
                '#sessionPersonName#'     => Ue::getSessionPersonWithLastName(),
                '#sessionUserName#'       => Ue::getSessionUserName(),
                '#sessionEmployeeId#'     => Ue::sessionEmployeeId(), 
                '#sessionPosition#'       => Ue::getSessionPositionName(),
                '#sessionPhone#'          => Ue::getSessionPhoneNumber(),
                '#sessionEmail#'          => Ue::getSessionEmail(),
                '#sessionDepartmentName#' => Ue::sessionUserKeyDepartmentName(), 
                '#sessionFiscalPeriodName#' => Ue::getSessionFiscalPeriodName()
            );
        }
        
        return self::$constantKeys;
    }

    public function paramKeywordReplacer($content, $paramValues) {

        foreach (self::constantKeys() as $constantKey => $constantKeyValue) {
            $content = str_ireplace($constantKey, $constantKeyValue, $content);
        }
        
        if (isset(self::$data['_EXPRESSION_GLOBAL'])) {  

            foreach (self::$data['_EXPRESSION_GLOBAL'] as $rowGlobalKey => $rowGlobal) {
                
                $typeCode = $this->model->getTypeCodeColumnModel(self::$dataViewColumnsType, $rowGlobalKey);
                
                if ($typeCode == 'bigdecimal') {
                    $rowGlobal = self::detailFormatMoney($rowGlobal);
                } elseif ($typeCode == 'bigdecimal_null') {
                    $rowGlobal = self::detailFormatMoneyNull($rowGlobal);
                } elseif ($typeCode == 'string' || $typeCode == 'description' || $typeCode == 'description_auto') {
                    $rowGlobal = $rowGlobal;
                } elseif ($typeCode == 'date') {
                    $rowGlobal = Date::formatter($rowGlobal, 'Y-m-d');
                } elseif ($typeCode == 'datetime') {
                    $rowGlobal = Date::formatter($rowGlobal, 'Y-m-d H:i');
                } elseif ($typeCode == 'scale') {
                    $rowGlobal = self::detailFormatMoneyScale($rowGlobal, $rowGlobalKey);
                } elseif ($typeCode == 'setscale') {
                    $rowGlobal = self::detailFormatMoneySetScale($rowGlobal, $rowGlobalKey);
                } elseif ($typeCode == 'decimal') {
                    $rowGlobal = self::formatAmountEmpty($rowGlobal);
                } elseif ($typeCode == 'decimal_zero') {
                    $rowGlobal = self::formatDecimalZero($rowGlobal);
                } elseif ($typeCode == 'floatempty') {
                    $rowGlobal = self::formatAmountFloatEmpty($rowGlobal);
                } elseif ($typeCode == 'floatemptyscale') {
                    $rowGlobal = self::formatAmountFloatEmptyScale($rowGlobal, $rowGlobalKey);
                } elseif ($typeCode == 'decimal_to_time') {
                    $rowGlobal = self::decimal_to_time($rowGlobal);
                } elseif (is_numeric($rowGlobal)) {
                    $rowGlobal = self::detailFormatMoney($rowGlobal);
                }
                
                $content = str_replace('#'.$rowGlobalKey.'#', $rowGlobal, $content);
            }
        }        
        
        if (!empty($paramValues)) {
            foreach ($paramValues as $paramKey => $paramValue) {
                if (!is_array($paramValue)) {                    
                    $content = str_replace('#'.$paramKey.'#', $paramValue, $content);
                }
            }
        }
        
        return $content;
    }
    
    public static function onlyGroupRowReplacer($content, $paramValues) { 
        
        foreach ($paramValues as $paramKey => $paramValue) {              
            $content = str_ireplace('#'.$paramKey.'#', $paramValue, $content);
        }
        
        return $content;
    }

    public function reportGrouping($renderType, $statementId, $dataViewId, $groupingData, $rows, $params, $depth = 0, $reportDetail, $tableHtml, $tableBody, $tableFootHtml = '', $constantKeys, $expressionArr, $isGroupMerge = 0) {
        
        $html = '';        

        if (isset($groupingData[$depth])) {
            
            if (!isset($rows[0][$groupingData[$depth]['GROUP_FIELD_PATH']])) {
                return html_tag('div', ['class' => 'alert alert-warning'], 'Grouping хийж байгаа талбар үндсэн Datasource-с олдсонгүй! /statementId: '.$statementId.', dataViewId: '.$dataViewId.', groupPath: '.$groupingData[$depth]['GROUP_FIELD_PATH'].'/');
            }
            
            $groupingCount = count($groupingData);
            
            if (self::$isHdrRepeatPage == true) {
                
                $groupHeaderHtml = $groupingData[$depth]['GROUP_HEADER'];
                $groupFooterHtml = $groupingData[$depth]['GROUP_FOOTER'];
                
            } else {
                
                $groupHeaderHtml = ($isGroupMerge ? '' : '<div style="height: '.($depth == 0 ? 10 : 0).'px;"></div>');
                
                $groupHeaderHtml .= Str::cleanOut($groupingData[$depth]['GROUP_HEADER']);
                $groupFooterHtml = Str::cleanOut($groupingData[$depth]['GROUP_FOOTER']);
            }          
            
            $groupedArray = Arr::groupByArray($rows, $groupingData[$depth]['GROUP_FIELD_PATH']);
            
            $groupCounter = 1;

            foreach ($groupedArray as $groupedRow) {
                
                self::$addonTableBodyRows = [];
                
                $rowDepth = $depth + 1;
                self::$data['rownum_'.$rowDepth] = 0;
                
                self::$data['groupDataViewCriteria'][$groupingData[$depth]['GROUP_FIELD_PATH']] = $groupedRow['row'][$groupingData[$depth]['GROUP_FIELD_PATH']];
                
                $groupHeader = $groupHeaderHtml;
                $groupFooter = $groupFooterHtml;
                $tableFoot   = $tableFootHtml;
                
                foreach ($groupedRow['row'] as $groupKey => $groupValue) {

                    $typeCode = $this->model->getTypeCodeColumnModel(self::$dataViewColumnsType, $groupKey);
                
                    self::$data['_EXPRESSION_GLOBAL'][$groupKey] = $groupValue;
                    
                    $groupValue = self::stCellValue($typeCode, $groupKey, $groupValue);

                    $params = array_merge($params, [$groupKey => $groupValue]);
                    
                    if (strpos($groupHeader.$groupFooter.$tableFoot.self::$addonTableFoot, 'sum(#'.$groupKey.'#)')) {
                        
                        if ($groupingCount != $rowDepth) {
                            
                            $sumVal = helperSumFieldBp($groupedRow['rows'], $groupKey);
                        
                            if ($typeCode == 'scale') {
                                $sumVal = self::detailFormatMoneyScale($sumVal, $groupKey);
                            } elseif ($typeCode == 'setscale') {
                                $sumVal = self::detailFormatMoneySetScale($sumVal, $groupKey);
                            } elseif ($typeCode == 'decimal') {
                                $sumVal = self::formatAmountEmpty($sumVal);
                            } elseif ($typeCode == 'decimal_zero') {
                                $sumVal = self::formatDecimalZero($sumVal);
                            } elseif ($typeCode == 'floatempty') {
                                $sumVal = self::formatAmountFloatEmpty($sumVal);
                            } elseif ($typeCode == 'floatemptyscale') {
                                $sumVal = self::formatAmountFloatEmptyScale($sumVal, $groupKey);
                            } elseif ($typeCode == 'decimal_to_time') {
                                $sumVal = self::decimal_to_time($sumVal);
                            } else {

                                if (isset(Mdstatement::$truncAmountFields[$groupKey])) {
                                    $sumVal = number_format(Number::truncate($sumVal, Mdstatement::$truncAmountFields[$groupKey]), Mdstatement::$truncAmountFields[$groupKey], '.', ',');
                                } else {
                                    $sumVal = self::detailFormatMoney($sumVal);
                                }
                            }
                            
                            $groupHeader = str_replace('sum(#'.$groupKey.'#)', $sumVal, $groupHeader);
                            $groupFooter = str_replace('sum(#'.$groupKey.'#)', $sumVal, $groupFooter);
                            $tableFoot = str_replace('sum(#'.$groupKey.'#)', $sumVal, $tableFoot);
                            
                            self::$addonTableFoot = str_replace('sum(#'.$groupKey.'#)', $sumVal, self::$addonTableFoot);
                            
                        } else {
                            
                            $groupHeader = str_replace('sum(#'.$groupKey.'#)', $groupKey.'_sum_'.$rowDepth, $groupHeader);
                            $groupFooter = str_replace('sum(#'.$groupKey.'#)', $groupKey.'_sum_'.$rowDepth, $groupFooter);
                            $tableFoot = str_replace('sum(#'.$groupKey.'#)', $groupKey.'_sum_'.$rowDepth, $tableFoot);

                            self::$addonTableFoot = str_replace('sum(#'.$groupKey.'#)', $groupKey.'_sum_'.$rowDepth, self::$addonTableFoot);

                            foreach (range($rowDepth, $groupingCount) as $number) {
                                self::$data[$groupKey.'_sum_'.$number] = 0;
                            }
                        }
                    }
                    
                    if (isset(self::$data[$groupKey.'_sum_'.$rowDepth])) {
                        self::$data[$groupKey.'_sum_'.$rowDepth] = 0;
                    }
                    
                    if (strpos($groupHeader.$groupFooter.$tableFoot.self::$addonTableFoot, 'avg(#'.$groupKey.'#)')) {
                        
                        $groupHeader = str_replace('avg(#'.$groupKey.'#)', $groupKey.'_avg_'.$rowDepth, $groupHeader);
                        $groupFooter = str_replace('avg(#'.$groupKey.'#)', $groupKey.'_avg_'.$rowDepth, $groupFooter);
                        $tableFoot = str_replace('avg(#'.$groupKey.'#)', $groupKey.'_avg_'.$rowDepth, $tableFoot);
                        self::$addonTableFoot = str_replace('avg(#'.$groupKey.'#)', $groupKey.'_avg_'.$rowDepth, self::$addonTableFoot);
                        
                        foreach (range($rowDepth, $groupingCount) as $number) {
                            self::$data[$groupKey.'_avg_'.$number] = 0;
                            self::$data[$groupKey.'_avgCount_'.$number] = 0;
                        }
                    }
                    if (isset(self::$data[$groupKey.'_avg_'.$rowDepth])) {
                        self::$data[$groupKey.'_avg_'.$rowDepth] = 0;
                        self::$data[$groupKey.'_avgCount_'.$rowDepth] = 0;
                    }
                    
                    if (strpos($groupHeader.$groupFooter.$tableFoot.self::$addonTableFoot, 'min(#'.$groupKey.'#)')) {
                        
                        $groupHeader = str_replace('min(#'.$groupKey.'#)', $groupKey.'_min_'.$rowDepth, $groupHeader);
                        $groupFooter = str_replace('min(#'.$groupKey.'#)', $groupKey.'_min_'.$rowDepth, $groupFooter);
                        $tableFoot = str_replace('min(#'.$groupKey.'#)', $groupKey.'_min_'.$rowDepth, $tableFoot);
                        self::$addonTableFoot = str_replace('min(#'.$groupKey.'#)', $groupKey.'_min_'.$rowDepth, self::$addonTableFoot);
                        
                        foreach (range($rowDepth, $groupingCount) as $number) {
                            self::$data[$groupKey.'_min_'.$number] = [];
                        }
                    }
                    if (isset(self::$data[$groupKey.'_min_'.$rowDepth])) {
                        self::$data[$groupKey.'_min_'.$rowDepth] = [];
                    }
                    
                    if (strpos($groupHeader.$groupFooter.$tableFoot.self::$addonTableFoot, 'max(#'.$groupKey.'#)')) {
                        
                        $groupHeader = str_replace('max(#'.$groupKey.'#)', $groupKey.'_max_'.$rowDepth, $groupHeader);
                        $groupFooter = str_replace('max(#'.$groupKey.'#)', $groupKey.'_max_'.$rowDepth, $groupFooter);
                        $tableFoot = str_replace('max(#'.$groupKey.'#)', $groupKey.'_max_'.$rowDepth, $tableFoot);
                        self::$addonTableFoot = str_replace('max(#'.$groupKey.'#)', $groupKey.'_max_'.$rowDepth, self::$addonTableFoot);
                        
                        foreach (range($rowDepth, $groupingCount) as $number) {
                            self::$data[$groupKey.'_max_'.$number] = [];
                        }
                    }
                    if (isset(self::$data[$groupKey.'_max_'.$rowDepth])) {
                        self::$data[$groupKey.'_max_'.$rowDepth] = [];
                    }
                }
                
                $groupHeader = str_replace('#rownum#', $groupCounter, $groupHeader);
                $groupFooter = str_replace('#rownum#', $groupCounter, $groupFooter);
                
                if (strpos($expressionArr['gloExp'], 'self::getOneDataView') === false 
                    && strpos($expressionArr['gloExp'], 'self::runProcessValue') === false) {
                    $html .= self::paramKeywordReplacer($groupHeader, $params);
                } else {
                    self::$data['prevGroupHtml'] .= $groupHeader;
                }
                
                if ($groupingCount == $rowDepth) {
                    
                    eval($expressionArr['gloExp']);
                    
                    $prevGroupHtmlReplace = self::paramKeywordReplacer(self::$data['prevGroupHtml'], '');
                    $html .= self::onlyGroupRowReplacer($prevGroupHtmlReplace, $params);
                    
                    self::$data['prevGroupHtml'] = '';
                    
                    $htmlDetail = self::reportDetail($statementId, $groupedRow['rows'], $tableBody, $rowDepth, $constantKeys, $expressionArr);
                    
                    if ($renderType == 'card') {
                        
                        $html .= $htmlDetail;
                        
                    } elseif (self::$isHdrRepeatPage) {
                        
                        $html .= $htmlDetail;
                        
                        if ($tableFoot) {
                            $tableFoot = self::paramKeywordReplacer($tableFoot, $params);
                            $html .= $tableFoot;
                        }
                        
                    } else {
                            
                        $tableHtml['tbody']->html($htmlDetail);
                        
                        if ($tableFoot) {
                            $tableFoot = self::paramKeywordReplacer($tableFoot, $params);
                            $tableHtml['tfoot']->html($tableFoot);
                        }

                        if ($reportDetail->find('table > thead')->length == 0) {
                            $reportDetail->find('table:eq(0)')->html($tableHtml->html());
                        } else {
                            $reportDetail->find('table:has(thead):eq(0)')->html($tableHtml->html());
                        }
                        
                        if (self::$isMultiDetail == true) {
                            
                            if (self::$addonTableFoot) {
                                
                                $addonTableFootLoad = phpQuery::newDocumentHTML(self::$addonTableFoot);
                            
                                for ($i = 1; $i <= self::$addonTableLoopCount; ++$i) {

                                    $addonTableFootRowHtml = $addonTableFootLoad['div#foot-'.$i]->html();

                                    $reportDetail['table:has(thead):eq('.$i.') > tbody']->html(self::$addonTableBodyRows[$i]);
                                    $reportDetail['table:has(thead):eq('.$i.') > tfoot']->html($addonTableFootRowHtml);
                                }
                                
                            } else {
                                
                                for ($i = 1; $i <= self::$addonTableLoopCount; ++$i) {

                                    $reportDetail['table:has(thead):eq('.$i.') > tbody']->html(self::$addonTableBodyRows[$i]);
                                }
                            }
                        }
                        
                        $html .= $reportDetail->html();
                    }
                    
                } else {
                    
                    $html .= self::reportGrouping($renderType, $statementId, $dataViewId, $groupingData, $groupedRow['rows'], $params, $rowDepth, $reportDetail, $tableHtml, $tableBody, $tableFootHtml, $constantKeys, $expressionArr, $isGroupMerge);
                }

                $html .= self::paramKeywordReplacer($groupFooter, $params);
                
                if (isset(self::$data['rownum_'.($rowDepth - 1)])) {
                    self::$data['rownum_'.($rowDepth - 1)] += 1;
                }
                
                foreach ($groupedRow['row'] as $groupKey => $groupValue) {
                    
                    if (isset(self::$data[$groupKey.'_sum_'.$rowDepth])) {
                        if (isset(self::$data[$groupKey.'_sum_'.($rowDepth - 1)])) {
                            self::$data[$groupKey.'_sum_'.($rowDepth - 1)] += self::$data[$groupKey.'_sum_'.$rowDepth];
                        }
                        
                        $typeCode = $this->model->getTypeCodeColumnModel(self::$dataViewColumnsType, $groupKey);
                        
                        if ($typeCode == 'scale') {
                            $html = preg_replace('/\b'.$groupKey.'_sum_'.$rowDepth.'\b/u', self::detailFormatMoneyScale(self::$data[$groupKey.'_sum_'.$rowDepth], $groupKey), $html);
                        } elseif ($typeCode == 'setscale') {
                            $html = preg_replace('/\b'.$groupKey.'_sum_'.$rowDepth.'\b/u', self::detailFormatMoneySetScale(self::$data[$groupKey.'_sum_'.$rowDepth], $groupKey), $html);
                        } elseif ($typeCode == 'decimal') {
                            $html = preg_replace('/\b'.$groupKey.'_sum_'.$rowDepth.'\b/u', self::formatAmountEmpty(self::$data[$groupKey.'_sum_'.$rowDepth]), $html);
                        } elseif ($typeCode == 'decimal_zero') {
                            $html = preg_replace('/\b'.$groupKey.'_sum_'.$rowDepth.'\b/u', self::formatDecimalZero(self::$data[$groupKey.'_sum_'.$rowDepth]), $html);
                        } elseif ($typeCode == 'floatempty') {
                            $html = preg_replace('/\b'.$groupKey.'_sum_'.$rowDepth.'\b/u', self::formatAmountFloatEmpty(self::$data[$groupKey.'_sum_'.$rowDepth]), $html);
                        } elseif ($typeCode == 'floatemptyscale') {
                            $html = preg_replace('/\b'.$groupKey.'_sum_'.$rowDepth.'\b/u', self::formatAmountFloatEmptyScale(self::$data[$groupKey.'_sum_'.$rowDepth], $groupKey), $html);
                        } elseif ($typeCode == 'decimal_to_time') {
                            $html = preg_replace('/\b'.$groupKey.'_sum_'.$rowDepth.'\b/u', self::decimal_to_time(self::$data[$groupKey.'_sum_'.$rowDepth]), $html);
                        } else {
                            
                            if (isset(Mdstatement::$truncAmountFields[$groupKey])) {
                                $html = preg_replace('/\b'.$groupKey.'_sum_'.$rowDepth.'\b/u', number_format(Number::truncate(self::$data[$groupKey.'_sum_'.$rowDepth], Mdstatement::$truncAmountFields[$groupKey]), Mdstatement::$truncAmountFields[$groupKey], '.', ','), $html);
                            } else {
                                $html = preg_replace('/\b'.$groupKey.'_sum_'.$rowDepth.'\b/u', self::detailFormatMoney(self::$data[$groupKey.'_sum_'.$rowDepth]), $html);
                            }
                        }
                    }
                    
                    if (isset(self::$data[$groupKey.'_avg_'.$rowDepth])) {
                        
                        if (isset(self::$data[$groupKey.'_avg_'.($rowDepth - 1)]) && self::$data[$groupKey.'_avg_'.$rowDepth] != '') {
                            self::$data[$groupKey.'_avg_'.($rowDepth - 1)] += self::$data[$groupKey.'_avg_'.$rowDepth];
                            self::$data[$groupKey.'_avgCount_'.($rowDepth - 1)] += 1;
                        } 
                        
                        if (self::$data[$groupKey.'_avg_'.$rowDepth] && self::$data[$groupKey.'_avgCount_'.$rowDepth] != '') {
                            
                            $typeCode = $this->model->getTypeCodeColumnModel(self::$dataViewColumnsType, $groupKey);
                            $avgAmount = self::$data[$groupKey.'_avg_'.$rowDepth] / self::$data[$groupKey.'_avgCount_'.$rowDepth];
                            
                            if ($typeCode == 'scale') {
                                $html = preg_replace('/\b'.$groupKey.'_avg_'.$rowDepth.'\b/u', self::detailFormatMoneyScale($avgAmount, $groupKey), $html);
                            } elseif ($typeCode == 'setscale') {
                                $html = preg_replace('/\b'.$groupKey.'_avg_'.$rowDepth.'\b/u', self::detailFormatMoneySetScale($avgAmount, $groupKey), $html);
                            } elseif ($typeCode == 'decimal') {
                                $html = preg_replace('/\b'.$groupKey.'_avg_'.$rowDepth.'\b/u', self::formatAmountEmpty($avgAmount), $html);
                            } elseif ($typeCode == 'decimal_zero') {
                                $html = preg_replace('/\b'.$groupKey.'_avg_'.$rowDepth.'\b/u', self::formatDecimalZero($avgAmount), $html);
                            } elseif ($typeCode == 'floatempty') {
                                $html = preg_replace('/\b'.$groupKey.'_avg_'.$rowDepth.'\b/u', self::formatAmountFloatEmpty($avgAmount), $html);
                            } elseif ($typeCode == 'floatemptyscale') {
                                $html = preg_replace('/\b'.$groupKey.'_avg_'.$rowDepth.'\b/u', self::formatAmountFloatEmptyScale($avgAmount, $groupKey), $html);
                            } elseif ($typeCode == 'decimal_to_time') {
                                $html = preg_replace('/\b'.$groupKey.'_avg_'.$rowDepth.'\b/u', self::decimal_to_time($avgAmount), $html);
                            } else {
                                
                                if (isset(Mdstatement::$truncAmountFields[$groupKey])) {
                                    $html = preg_replace('/\b'.$groupKey.'_avg_'.$rowDepth.'\b/u', number_format(Number::truncate($avgAmount, Mdstatement::$truncAmountFields[$groupKey]), Mdstatement::$truncAmountFields[$groupKey], '.', ','), $html);
                                } else {
                                    $html = preg_replace('/\b'.$groupKey.'_avg_'.$rowDepth.'\b/u', self::detailFormatMoney($avgAmount), $html);
                                }
                            }

                        } else {
                            $html = preg_replace('/\b'.$groupKey.'_avg_'.$rowDepth.'\b/u', '0', $html);
                        }
                    }
                    
                    if (isset(self::$data[$groupKey.'_min_'.$rowDepth])) {
                        if (isset(self::$data[$groupKey.'_min_'.($rowDepth - 1)])) {
                            array_push(self::$data[$groupKey.'_min_'.($rowDepth - 1)], min(self::$data[$groupKey.'_min_'.$rowDepth]));
                        } 
                        
                        $min = 0;
                        
                        if (count(self::$data[$groupKey.'_min_'.$rowDepth])) {
                            $min = self::detailFormatMoney(min(self::$data[$groupKey.'_min_'.$rowDepth]));
                        }
                        
                        $html = preg_replace('/\b'.$groupKey.'_min_'.$rowDepth.'\b/u', $min, $html);
                    }
                    
                    if (isset(self::$data[$groupKey.'_max_'.$rowDepth])) {
                        if (isset(self::$data[$groupKey.'_max_'.($rowDepth - 1)])) {
                            array_push(self::$data[$groupKey.'_max_'.($rowDepth - 1)], max(self::$data[$groupKey.'_max_'.$rowDepth]));
                        } 
                        
                        $max = 0;
                        
                        if (count(self::$data[$groupKey.'_max_'.$rowDepth])) {
                            $max = self::detailFormatMoney(max(self::$data[$groupKey.'_max_'.$rowDepth]));
                        }
                        
                        $html = preg_replace('/\b'.$groupKey.'_max_'.$rowDepth.'\b/u', $max, $html);
                    }
                }
                
                $html = self::matchDefaultValue($html);
                $html = self::calculateExpression($html);
                $html = self::runExpression($html);
                $html = self::runExpressionTag($html);
                $html = self::moneyFormat($html);
                
                $groupCounter++;
            } 
            
            if ($depth == 0) {
                self::$data['groupCount_1'] = $groupCounter - 1;
            }
        }
        
        return $html;
    }
    
    public function reportDetail($statementId, $groupedRow, $tableBody, $rowDepth, $constantKeys, $expressionArr) {
        
        $appendTableRow = ''; 
        
        if (count($groupedRow) > 0) {
            
            $rowNum = 0;
            
            foreach ($groupedRow as $n => $row) {
                
                $rowNum++;
                self::$numIterations++;

                $tableBodyRow = $tableBody;
                $addonTableBodyRow = self::$addonTableBody;
                
                eval($expressionArr['rowExp']);
                
                foreach ($row as $k => $v) {
                    
                    if (is_numeric($v)) {
                        
                        if (isset(self::$data[$k.'_sum'])) {
                            self::$data[$k.'_sum'] += $v;
                        }
                        if (isset(self::$data[$k.'_avg'])) {
                            self::$data[$k.'_avg'] += $v;
                            self::$data[$k.'_avgCount'] += 1;
                        }
                        if (isset(self::$data[$k.'_min'])) {
                            array_push(self::$data[$k.'_min'], $v);
                        }
                        if (isset(self::$data[$k.'_max'])) {
                            array_push(self::$data[$k.'_max'], $v);
                        }

                        if (isset(self::$data[$k.'_sum_'.$rowDepth])) {
                            self::$data[$k.'_sum_'.$rowDepth] += $v;
                        }
                        if (isset(self::$data[$k.'_avg_'.$rowDepth])) {
                            self::$data[$k.'_avg_'.$rowDepth] += $v;
                            self::$data[$k.'_avgCount_'.$rowDepth] += 1;
                        }
                        if (isset(self::$data[$k.'_min_'.$rowDepth])) {
                            array_push(self::$data[$k.'_min_'.$rowDepth], $v);
                        }
                        if (isset(self::$data[$k.'_max_'.$rowDepth])) {
                            array_push(self::$data[$k.'_max_'.$rowDepth], $v);
                        }
                    }
                        
                    if (isset(self::$isRenderColumn[$k])) {
                        
                        $anchorStart = $anchorEnd = '';

                        if (isset(self::$drillDownColumns[$k])) {
                            $anchorStart = '<a href="javascript:;" data-row-data="'.$rowDepth.$n.'|'.$statementId.'|'.$row['rid'].'|'.self::$uniqId.'|'.$k.'|'.Mdstatement::$isStatementModeNum.'" onclick="drillDownStatement(this);">';
                            $anchorEnd = '</a>';
                        }
                                
                        $typeCode = $this->model->getTypeCodeColumnModel(self::$dataViewColumnsType, $k);
                        
                        $v = self::stCellValue($typeCode, $k, $v);

                        $tableBodyRow = str_replace('#'.$k.'#', $anchorStart.$v.$anchorEnd, $tableBodyRow);
                        
                        if (self::$isMultiDetail) {
                            $addonTableBodyRow = str_replace('#'.$k.'#', $anchorStart.$v.$anchorEnd, $addonTableBodyRow);
                        }
                    }                                        
                }
                
                if (isset(self::$data['_EXPRESSION_GLOBAL'])) {
                    foreach (self::$data['_EXPRESSION_GLOBAL'] as $rowGlobalKey => $rowGlobal) {

                        $typeCode = $this->model->getTypeCodeColumnModel(self::$dataViewColumnsType, $rowGlobalKey);

                        if ($typeCode == 'bigdecimal') {
                            $rowGlobal = self::detailFormatMoney($rowGlobal);
                        } elseif ($typeCode == 'bigdecimal_null') {
                            $rowGlobal = self::detailFormatMoneyNull($rowGlobal);
                        } elseif ($typeCode == 'string' || $typeCode == 'description' || $typeCode == 'description_auto') {
                            $rowGlobal = $rowGlobal;
                        } elseif ($typeCode == 'date') {
                            $rowGlobal = Date::formatter($rowGlobal, 'Y-m-d');
                        } elseif ($typeCode == 'datetime') {
                            $rowGlobal = Date::formatter($rowGlobal, 'Y-m-d H:i');
                        } elseif ($typeCode == 'scale') {
                            $rowGlobal = self::detailFormatMoneyScale($rowGlobal, $rowGlobalKey);
                        } elseif ($typeCode == 'setscale') {
                            $rowGlobal = self::detailFormatMoneySetScale($rowGlobal, $rowGlobalKey);
                        } elseif ($typeCode == 'decimal') {
                            $rowGlobal = self::formatAmountEmpty($rowGlobal);
                        } elseif ($typeCode == 'decimal_zero') {
                            $rowGlobal = self::formatDecimalZero($rowGlobal);
                        } elseif ($typeCode == 'floatempty') {
                            $rowGlobal = self::formatAmountFloatEmpty($rowGlobal);
                        } elseif ($typeCode == 'floatemptyscale') {
                            $rowGlobal = self::formatAmountFloatEmptyScale($rowGlobal, $rowGlobalKey);
                        } elseif ($typeCode == 'decimal_to_time') {
                            $rowGlobal = self::decimal_to_time($rowGlobal);
                        } elseif (is_numeric($rowGlobal)) {
                            $rowGlobal = self::detailFormatMoney($rowGlobal);
                        }

                        $tableBodyRow = str_replace('#'.$rowGlobalKey.'#', $rowGlobal, $tableBodyRow);
                    }
                }
                
                $tableBodyRow = str_replace('#rownum#', $rowNum, $tableBodyRow);
                $tableBodyRow = str_replace('#allrownum#', self::$numIterations, $tableBodyRow);
                
                self::$data['count'] += 1;

                self::$data['rownum_'.$rowDepth] += 1;
                
                $tableBodyRow = self::matchDefaultValue($tableBodyRow);
                $tableBodyRow = self::calculateExpression($tableBodyRow);
                $tableBodyRow = self::runExpression($tableBodyRow);
                $tableBodyRow = self::runExpressionTag($tableBodyRow);
                $tableBodyRow = self::dropZeroMoneyFormat($tableBodyRow);
                $tableBodyRow = self::moneyFormat($tableBodyRow);
                
                if (self::$isMultiDetail) {
                            
                    $addonTableBodyRow = str_replace('#rownum#', $rowNum, $addonTableBodyRow);
                    $addonTableBodyRow = str_replace('#allrownum#', self::$numIterations, $addonTableBodyRow);

                    for ($i = 1; $i <= self::$addonTableLoopCount; ++$i) {

                        $addonTableBodyLoad = phpQuery::newDocumentHTML($addonTableBodyRow);
                        $addonTableBodyRowHtml = $addonTableBodyLoad['div#body-'.$i]->html();

                        self::$addonTableBodyRows[$i] = (isset(self::$addonTableBodyRows[$i]) ? self::$addonTableBodyRows[$i] : '') . $addonTableBodyRowHtml;
                    }
                }

                $appendTableRow .= $tableBodyRow;
            }
        }
        
        return $appendTableRow;
    }
    
    public function renderDataModelByFilter($dialogMode = false) {
        
        Auth::handleLogin();
            
        $postData = Input::postData();

        if (!isset($postData['dataViewId']) && !isset($postData['statementId'])) {
            echo 'Oroltiin parametr todorhoi bus bna'; exit;
        }
        
        $this->load->model('mdstatement', 'middleware/models/');
        
        loadBarCodeImageData();
        loadPhpQuery();
        
        $statementId = Input::param($postData['statementId']);
        $dataViewId  = Input::param($postData['dataViewId']);
        
        if (Input::numeric('isKpiIndicator') == 1) {
            
            self::$isKpiIndicator = true;
            self::$isFooterQrCode = Config::getFromCache('IS_MV_STATEMENT_FOOTER_QRCODE') ? true : false;
        }
        
        if (isset($postData['param'])) {
            
            $params = $postData['param'];
            
            unset($postData['param']);
            
            self::$filterParamsLower = array_change_key_case($postData, CASE_LOWER);
            
        } else {
            $params = array();
        }
        
        $renderStatement = self::renderStatement($statementId, $dataViewId, $params);
        
        $htmlData = $renderStatement['htmlData'];
        $status = $renderStatement['status'];
        $message = $renderStatement['message'];
        
        if ($htmlData) {
        
            $htmlData .= '<div class="print-width-dpi"></div>';
            
            $htmlData = self::editable($htmlData);
            $htmlData = self::qrcode($htmlData);
            
            $this->load->model('mdstatement', 'middleware/models/');

            $fileId = $this->model->writeStatementHtmlFile($htmlData);
            
        } else {
            $fileId = getUID();
            $htmlData = html_tag('div', array('class' => 'alert alert-warning'), $this->lang->line('msg_no_record_found'));
        }

        if (Config::getFromCache('renderStatementSaveLog') === '1') {
            $this->model->writeStatementRenderSysLog($dataViewId, $statementId, 'renderStatement', $params, self::$rowsCount);
        }

        if ($dialogMode) {
            
            ob_start('ob_html_compress'); 
                $minifyHtmlData = '<div data-file-id="'.$fileId.'" data-count="'.self::$rowsCount.'"></div>'.$htmlData;
                $htmlData = null;
            ob_end_flush();
            
            return $minifyHtmlData;
            
        } else {
            
            ob_start('ob_html_compress'); 
                $response = [
                    'status'     => $status, 
                    'message'    => $message, 
                    'freezeNumberOfColumn' => issetParam(self::$data['freezeNumberOfColumn']),
                    'childCount' => self::$childCount, 
                    'htmlData'   => '<div data-file-id="'.$fileId.'" data-count="'.self::$rowsCount.'"></div>'.$htmlData
                ];
                $htmlData = null;
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                $response = [];
            ob_end_flush();
            
            exit;
        }
    }
    
    public function renderStatement($statementId, $dataViewId, $params) {
        
        $htmlData = array();
        $status = 'success';
        $message = '';
        
        $getChildStatement = $this->model->getChildStatementListModel($statementId);
        
        if ($filterLanguageCode = issetParam($params['filterLanguageCode'])) {
            Lang::$memoryLangCode = $filterLanguageCode;
            Lang::load('main', false, $filterLanguageCode);
        }
        
        if ($getChildStatement['child']) {
            
            if ($getChildStatement['isNotPageBreak'] == '1') {
                $pageBreakTag = '';
            } else {
                $pageBreakTag = '<div style="page-break-after: always;"></div>';
            }
            
            $childStatements  = $getChildStatement['child'];
            self::$childCount = count($childStatements);
            
            $htmlData[] = self::generateReportHeaderFooter($dataViewId, $params, $getChildStatement['srcRow']['REPORT_HEADER']);
            $htmlData[] = self::generateReportHeaderFooter($dataViewId, $params, $getChildStatement['srcRow']['PAGE_HEADER']);
            
            foreach ($childStatements as $childStatement) {
                
                $childStatementId = $childStatement['STATEMENT_META_ID'];
                $childDataViewId  = $childStatement['DATA_VIEW_ID'];
                
                $renderDataViewStatement = self::renderDataViewStatement($childStatementId, $childDataViewId, $params);
                
                if ($renderDataViewStatement['status'] == 'success') {
                    
                    $message = '';
                    $status = $renderDataViewStatement['status'];
                    $htmlData[] = $renderDataViewStatement['htmlData'];
                    
                    if ($renderDataViewStatement['htmlData'] != '' && $childStatement !== end($childStatements)) {
                        $htmlData[] = $pageBreakTag;
                    }
                    
                } else {
                    $status = $renderDataViewStatement['status'];
                    $message = $renderDataViewStatement['message'];
                }
            }
            
            $htmlData[] = self::generateReportHeaderFooter($dataViewId, $params, $getChildStatement['srcRow']['PAGE_FOOTER']);
            $htmlData[] = self::generateReportHeaderFooter($dataViewId, $params, $getChildStatement['srcRow']['REPORT_FOOTER']);
            
            if ($status == 'success' && self::$isFooterQrCode) {
                
                self::$qrData = array(
                    'statementId'   => $statementId, 
                    'statementName' => $getChildStatement['srcRow']['REPORT_NAME'], 
                    'filterData'    => Input::post('filterData')
                );
                
                $htmlData[] = self::generateReportQrCode();
            }
            
        } else {
            
            $renderDataViewStatement = self::renderDataViewStatement($statementId, $dataViewId, $params);
            
            if ($renderDataViewStatement['status'] == 'success') {
                
                $htmlData[] = $renderDataViewStatement['htmlData'];
                
                if (self::$isFooterQrCode) {
                    
                    self::$qrData = [
                        'statementId'   => $statementId, 
                        'statementName' => $renderDataViewStatement['statementName'], 
                        'filterData'    => Input::post('filterData')
                    ];

                    $htmlData[] = self::generateReportQrCode();
                }
                
            } else {
                $status = $renderDataViewStatement['status'];
                $message = $renderDataViewStatement['message'];
            }
        }
        
        return ['htmlData' => implode('', $htmlData), 'status' => $status, 'message' => $message];
    }
    
    public function generateReportQrCode() {
        
        includeLib('QRCode/qrlib');
        
        $qrStr = 'Тайлангийн нэр: '.self::$qrData['statementName'] . "\n";
        $qrStr .= 'Огноо: '.Date::currentDate('Y-m-d H:i') . "\n";
        $qrStr .= 'Тайлан бэлдсэн хэрэглэгч: '.Ue::getSessionPersonWithLastName();
        
        ob_start();

        QRcode::png($qrStr, null, 'L', 6, 0);
        $imageData = ob_get_contents();

        ob_end_clean();
        
        $qrCode = '<div style="text-align: right"><img src="data:image/png;base64,'.base64_encode($imageData).'" style="height: 100px"></div>';
        
        return $qrCode;
    }
    
    public function renderDataViewStatement($statementId, $dataViewId, $params) {
        
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        $this->load->model('mdstatement', 'middleware/models/');
        
        self::$uniqId = getUID();
        self::$drillDownColumns = $this->model->getDrillDownColumnDataModel($statementId, $dataViewId);

        if (count(self::$drillDownColumns)) {
            self::$isWithDrillDown = true;
        }
        
        $getRowStatement = $this->model->getStatementRowModel($statementId);
        
        if (!$dataViewId && $getRowStatement['IS_BLANK'] == '1') {
            
            $result = ['status' => 'success', 'rows' => []];
            
        } elseif (Mdstatement::$isKpiIndicator) {
            
            Mdstatement::$isStatementModeNum = 1;
            
            if ($getRowStatement['KPI_TYPE_ID'] == '1045') {
                
                self::$isPivotView = true;
                $getRowStatement['IS_HDR_REPEAT_PAGE'] = '1';
            }
            
            $result = $this->model->getIndicatorDataModel($dataViewId, $params);
            
        } else {
            $result = $this->model->getDataViewDataModel($dataViewId, $params, $getRowStatement);
        }
        
        if ($result['status'] == 'success') {
            
            $this->load->model('mdstatement', 'middleware/models/');
            
            if ($getRowStatement['IS_HDR_REPEAT_PAGE'] == '1') {
                self::$isHdrRepeatPage = true;
            }
            
            if ($getRowStatement['IS_BLANK'] == '1' && !isset($result['rows'][0])) {
                
                $dataViewColumnsType = $this->model->getTypeCodeDataViewParamsModel($dataViewId);
                $result['rows'][0]   = $this->model->getBlankOneRowModel($dataViewColumnsType);
            }
        
            $html = '';
            $realParams = $params;
            
            if (isset($result['rows'][0])) {
                
                self::$rowsCount += count($result['rows']);
                
                if (self::$rowsCount > 30000) {
                    return ['status' => 'error', 'message' => 'Тайлангийн мөр 30,000-аас их мөр ирсэн тул та тухайн тайланг жагсаалтаар харна уу!'];
                }
                
                self::$UIExpression = Mdexpression::statementUIExpression($getRowStatement, $params);
                $getHtmlRow = $this->model->getStatementHtmlRowModel($statementId);
                
                if ($getHtmlRow['REPORT_DETAIL'] != '') {
                        
                    $reportDetailHtml = $this->model->reportDetailEvalModel($getHtmlRow['REPORT_DETAIL']);
                    
                } else {
                    $reportDetailHtml = '';
                }
                
                if (isset($realParams['isGroupingIgnore']) && $realParams['isGroupingIgnore'] == '1') {
                    $groupingData = [];
                } else {
                    
                    $groupingCount = '';
                    
                    /* future remove code */
                    $configstaticTms = Config::getFromCache('staticTmsReport');
                    if (issetParam($getRowStatement['IS_TIMETABLE']) === '1' || ($configstaticTms && $dataViewId == $configstaticTms)) { //'1581480427654'

                        $datetime1 = date_create($realParams['filterStartDate']);
                        $datetime2 = date_create($realParams['filterEndDate']);

                        $interval = date_diff($datetime1, $datetime2);
                        $days = $interval->days;
                        $groupingCount = 31 - (($days == 0) ? 1 : $days);
                    }
                    
                    $groupingData = $this->model->getLinkGroupDataModel($statementId, $reportDetailHtml, $groupingCount);
                }
                
                $mdExpressionCtrl = Controller::loadController('Mdexpression', 'middleware/controllers/');
                $expressionArr = $mdExpressionCtrl->statementExpression($getRowStatement);
                
                self::$data['statementRealParams'] = $realParams;
                
                eval($expressionArr['superGloExp']);
                eval($expressionArr['gloExp']);
                
                if (isset($freezeNumberOfColumn)) {
                    self::$data['freezeNumberOfColumn'] = $freezeNumberOfColumn;
                }
                
                if (!isset($dataViewColumnsType)) {
                    $dataViewColumnsType = $this->model->getTypeCodeDataViewParamsModel($dataViewId);
                }
                
                $params = $this->model->setParamsValueModel($dataViewColumnsType, $params);
                
                $reportHeader = self::paramKeywordReplacer($getHtmlRow['REPORT_HEADER'], $params);
                $pageHeader   = self::paramKeywordReplacer($getHtmlRow['PAGE_HEADER'], $params);
                $pageFooter   = self::paramKeywordReplacer($getHtmlRow['PAGE_FOOTER'], $params);
                $reportFooter = $getHtmlRow['REPORT_FOOTER'];
                
                $constantKeys = Arr::changeKeyLower(self::constantKeys());        
                $renderType = $getHtmlRow['RENDER_TYPE'];
              
                if ($groupingData) {
                    
                    $mergeHtml = $reportHeader.$pageHeader.$pageFooter.$reportFooter.Mdstatement::$tmpReportFooter;
                    
                    preg_match_all('/sum\(\#(.*?)\#\)/i', $mergeHtml, $sumAggregate); // aggregate (sum)
                    preg_match_all('/avg\(\#(.*?)\#\)/i', $mergeHtml, $avgAggregate); // aggregate (avg)
                    preg_match_all('/min\(\#(.*?)\#\)/i', $mergeHtml, $minAggregate); // aggregate (min)
                    preg_match_all('/max\(\#(.*?)\#\)/i', $mergeHtml, $maxAggregate); // aggregate (max)
                    preg_match_all('/count\(\)/i', $mergeHtml, $countAggregate); // aggregate (count)
                    
                    if (count($sumAggregate[1]) > 0) {
                        foreach ($sumAggregate[1] as $s => $sv) {
                            self::$data[$sv.'_sum'] = 0;
                        }
                    }
                    if (count($avgAggregate[1]) > 0) {
                        foreach ($avgAggregate[1] as $a => $av) {
                            self::$data[$av.'_avg'] = 0;
                            self::$data[$av.'_avgCount'] = 0;
                        }
                    }
                    if (count($minAggregate[1]) > 0) {
                        foreach ($minAggregate[1] as $m => $mv) {
                            self::$data[$mv.'_min'] = [];
                        }
                    }
                    if (count($maxAggregate[1]) > 0) {
                        foreach ($maxAggregate[1] as $ma => $mav) {
                            self::$data[$mav.'_max'] = [];
                        }
                    }
                    
                    self::$data['count'] = 0;
                    
                    $reportDetail = $reportDetailHtml;
                    
                    if ($getHtmlRow['RENDER_TYPE'] == 'card') {
                        
                        $detailHtml = $tableBody = $reportDetail;
                        $tableHtml = $tableFoot = ''; 
                        
                    } else {

                        $detailHtml = phpQuery::newDocumentHTML($reportDetail);
                        
                        $tableHeadCount = $detailHtml['table > thead']->length;
                        
                        if ($tableHeadCount == 0) {
                            $tableHtml = $detailHtml['table:eq(0)'];
                        } else {
                            $tableHtml = $detailHtml['table:has(thead):eq(0)'];
                        }
                        
                        foreach ($tableHtml['thead > tr'] as $tr) {
                            $backColor = pq($tr)->css('background-color');

                            if ($backColor) {
                                foreach (pq($tr)->find('th, td') as $cells) {
                                    pq($cells)->css('background-color', $backColor.' !important');
                                }
                            }
                        }
                        
                        $colGroup  = $tableHtml['colgroup']->html();
                        $tableHead = $tableHtml['thead']->html();
                        $tableBody = $tableHtml['tbody']->html();
                        $tableFoot = $tableHtml['tfoot']->html();
                        
                        $tableHtml->empty();
                        
                        if ($colGroup) {
                            $tableHtml->append('<colgroup>'.$colGroup.'</colgroup>');
                        }
                        if ($tableHead) {
                            $tableHtml->append('<thead>'.$tableHead.'</thead>');
                        }
                        if ($tableBody) {
                            $tableHtml->append('<tbody>'.$tableBody.'</tbody>');
                        }
                        if ($tableFoot) {
                            $tableHtml->append('<tfoot>'.$tableFoot.'</tfoot>');
                        }
                        
                        if ($tableHeadCount > 1) {
                            
                            self::$isMultiDetail = true;
                            
                            self::$addonTableLoopCount = $tableHeadCount - 1;
                            
                            for ($i = 1; $i <= self::$addonTableLoopCount; ++$i) {
                                
                                $addonTableHtml = $detailHtml['table:has(thead):eq('.$i.')'];
                                
                                foreach ($addonTableHtml['thead > tr'] as $tr) {
                                    $backColor = pq($tr)->css('background-color');
                                    
                                    if ($backColor) {
                                        foreach (pq($tr)->find('th, td') as $cells) {
                                            pq($cells)->css('background-color', $backColor.' !important');
                                        }
                                    }
                                }
                                
                                $otherTableHead = $addonTableHtml['thead']->html();
                                $otherTableBody = $addonTableHtml['tbody']->html();
                                $otherTableFoot = $addonTableHtml['tfoot']->html();
                                
                                $addonTableHtml->empty();
                                
                                $addonTableHtml->append('<thead>'.$otherTableHead.'</thead>');

                                if ($otherTableBody) {
                                    $addonTableHtml->append('<tbody>'.$otherTableBody.'</tbody>');
                                }
                                if ($otherTableFoot) {
                                    $addonTableHtml->append('<tfoot>'.$otherTableFoot.'</tfoot>');
                                }
                                
                                self::$addonTableBody .= '<div id="body-'.$i.'">'.$otherTableBody.'</div>';
                                self::$addonTableFoot .= '<div id="foot-'.$i.'">'.$otherTableFoot.'</div>';

                            }
                        }
                    }
                    
                    self::$data['prevGroupHtml'] = '';      
                    
                    self::$isRenderColumn = $this->model->isRenderColumnModel($tableBody.self::$addonTableBody, $expressionArr['rowFields'], $dataViewId, $getRowStatement);    
                    
                    $html .= $pageHeader;
                    $html .= $reportHeader;
                    
                    if (self::$isHdrRepeatPage) {
                        
                        if (self::$isIgnoreHdrRepeatPage) {
                            
                            foreach ($groupingData as $g => $group) {
                                
                                if (isset($group['isIgnoreHdrRepeatPage'])) {
                                    
                                    $groupedArray = Arr::groupByArray($result['rows'], $group['GROUP_FIELD_PATH']);
                                    $groupHeaderHtml = $group['GROUP_HEADER'];
                                    
                                    self::$data['groupCount_1'] = 0;
                                    
                                    foreach ($groupedArray as $groupedRow) {
                                        
                                        $groupHeader = $groupHeaderHtml;
                                        
                                        foreach ($groupedRow['row'] as $paramKey => $paramValue) {                  
                                            $groupHeader = str_replace('#'.$paramKey.'#', $paramValue, $groupHeader);
                                        }
                                        
                                        $html .= $groupHeader;
                                        
                                        $tableHtml['tbody']->empty();
                                        $tableHtml['tfoot']->remove();
                                        
                                        $groupingHtml = self::reportGrouping($renderType, $statementId, $dataViewId, $groupingData, $groupedRow['rows'], $params, 1, $detailHtml, $tableHtml, $tableBody, $tableFoot, $constantKeys, $expressionArr, $getRowStatement['IS_GROUP_MERGE']);
                                        
                                        $tableHtml->addClass('pf-repeat-page-header no-freeze');
                                        $tableHtml['tbody']->html($groupingHtml);
                                        
                                        $html .= $tableHtml->html($tableHtml->html());
                                    }
                                }
                            }
                            
                        } else {
                        
                            $groupingHtml = self::reportGrouping($renderType, $statementId, $dataViewId, $groupingData, $result['rows'], $params, 0, $detailHtml, $tableHtml, $tableBody, $tableFoot, $constantKeys, $expressionArr, $getRowStatement['IS_GROUP_MERGE']);

                            $tableHtml->addClass('pf-repeat-page-header');

                            $tableHtml['tbody']->empty();
                            $tableHtml['tfoot']->remove();

                            if ($tableBody) {
                                $tableHtml['tbody']->html($groupingHtml . Mdstatement::$tmpReportFooter);
                            }

                            if (self::$isPivotView) {

                                $html .= '<div class="pivot-datatable-wrapper" data-left-count="'.Mdstatement::$freezeLeftColumnCount.'">';
                                    $html .= $tableHtml->html($tableHtml->html());
                                $html .= '</div>';

                            } else {
                                $html .= $tableHtml->html($tableHtml->html());
                            }
                        }
                        
                    } else {
                        $html .= self::reportGrouping($renderType, $statementId, $dataViewId, $groupingData, $result['rows'], $params, 0, $detailHtml, $tableHtml, $tableBody, $tableFoot, $constantKeys, $expressionArr, $getRowStatement['IS_GROUP_MERGE']);
                    }
                    
                    $html .= $reportFooter;
                    $html .= $pageFooter;
                    
                    if (count($sumAggregate[1]) > 0) {
                        foreach ($sumAggregate[1] as $s => $sv) {
                            
                            $typeCode = $this->model->getTypeCodeColumnModel(self::$dataViewColumnsType, $sv);
                        
                            if ($typeCode == 'scale') {
                                $html = str_replace($sumAggregate[0][$s], self::detailFormatMoneyScale(self::$data[$sv.'_sum'], $sv), $html);
                            } elseif ($typeCode == 'setscale') {
                                $html = str_replace($sumAggregate[0][$s], self::detailFormatMoneySetScale(self::$data[$sv.'_sum'], $sv), $html);
                            } elseif ($typeCode == 'decimal') {
                                $html = str_replace($sumAggregate[0][$s], self::formatAmountEmpty(self::$data[$sv.'_sum']), $html);
                            } elseif ($typeCode == 'decimal_zero') {
                                $html = str_replace($sumAggregate[0][$s], self::formatDecimalZero(self::$data[$sv.'_sum']), $html);
                            } elseif ($typeCode == 'floatempty') {
                                $html = str_replace($sumAggregate[0][$s], self::formatAmountFloatEmpty(self::$data[$sv.'_sum']), $html);
                            } elseif ($typeCode == 'integer') {
                                $html = str_replace($sumAggregate[0][$s], self::formatAmountFloatEmpty(self::$data[$sv.'_sum']), $html);
                            } elseif ($typeCode == 'floatemptyscale') {
                                $html = str_replace($sumAggregate[0][$s], self::formatAmountFloatEmptyScale(self::$data[$sv.'_sum'], $sv), $html);
                            } elseif ($typeCode == 'decimal_to_time') {
                                $html = str_replace($sumAggregate[0][$s], self::decimal_to_time(self::$data[$sv.'_sum']), $html);
                            } else {
                                
                                if (isset(Mdstatement::$truncAmountFields[$sv])) {
                                    $html = str_replace($sumAggregate[0][$s], number_format(Number::truncate(self::$data[$sv.'_sum'], Mdstatement::$truncAmountFields[$sv]), Mdstatement::$truncAmountFields[$sv], '.', ','), $html);
                                } else {
                                    $html = str_replace($sumAggregate[0][$s], self::detailFormatMoney(self::$data[$sv.'_sum']), $html);
                                }
                            }
                        }
                    }
                    if (count($avgAggregate[1]) > 0) {
                        foreach ($avgAggregate[1] as $a => $av) {
                            if (self::$data[$av.'_avg'] && self::$data[$av.'_avgCount']) {
                                
                                $typeCode = $this->model->getTypeCodeColumnModel(self::$dataViewColumnsType, $av);
                                $avgAmount = self::$data[$av.'_avg'] / self::$data[$av.'_avgCount'];
                                
                                if ($typeCode == 'scale') {
                                    $html = str_replace($avgAggregate[0][$a], self::detailFormatMoneyScale($avgAmount, $av), $html);
                                } elseif ($typeCode == 'setscale') {
                                    $html = str_replace($avgAggregate[0][$a], self::detailFormatMoneySetScale($avgAmount, $av), $html);
                                } elseif ($typeCode == 'decimal') {
                                    $html = str_replace($avgAggregate[0][$a], self::formatAmountEmpty($avgAmount), $html);
                                } elseif ($typeCode == 'decimal_zero') {
                                    $html = str_replace($avgAggregate[0][$a], self::formatDecimalZero($avgAmount), $html);
                                } elseif ($typeCode == 'floatempty') {
                                    $html = str_replace($avgAggregate[0][$a], self::formatAmountFloatEmpty($avgAmount), $html);
                                } elseif ($typeCode == 'integer') {
                                    $html = str_replace($avgAggregate[0][$a], self::formatAmountFloatEmpty($avgAmount), $html);
                                } elseif ($typeCode == 'floatemptyscale') {
                                    $html = str_replace($avgAggregate[0][$a], self::formatAmountFloatEmptyScale($avgAmount, $av), $html);
                                } elseif ($typeCode == 'decimal_to_time') {
                                    $html = str_replace($avgAggregate[0][$a], self::decimal_to_time($avgAmount), $html);
                                } else {

                                    if (isset(Mdstatement::$truncAmountFields[$av])) {
                                        $html = str_replace($avgAggregate[0][$a], number_format(Number::truncate($avgAmount, Mdstatement::$truncAmountFields[$av]), Mdstatement::$truncAmountFields[$av], '.', ','), $html);
                                    } else {
                                        $html = str_replace($avgAggregate[0][$a], self::detailFormatMoney($avgAmount), $html);
                                    }
                                }
                            
                            } else {
                                $html = str_replace($avgAggregate[0][$a], '0', $html);
                            }
                        }
                    }
                    if (count($minAggregate[1]) > 0) {
                        foreach ($minAggregate[1] as $m => $mv) {
                            $html = str_replace($minAggregate[0][$m], self::detailFormatMoney(min(self::$data[$mv.'_min'])), $html);
                        }
                    }
                    if (count($maxAggregate[1]) > 0) {
                        foreach ($maxAggregate[1] as $ma => $mav) {
                            $html = str_replace($maxAggregate[0][$ma], self::detailFormatMoney(max(self::$data[$mav.'_max'])), $html);
                        }
                    }
                    if (count($countAggregate[0]) > 0) {
                        $html = str_replace('count()', self::formatAmountFloatEmpty(self::$data['count']), $html);
                    }
                    
                    $html = str_replace('groupCount(1)', self::$data['groupCount_1'], $html);
                    $html = str_replace(array("\n", "\r", "\t"), '', $html);
                    
                } else {
                    
                    $dataRows = $result['rows'];
                    
                    if ($getHtmlRow['REPORT_DETAIL'] != '') {
                        
                        $reportDetailHtml = Str::cleanOut($getHtmlRow['REPORT_DETAIL']);
                        
                    } elseif ($getHtmlRow['REPORT_DETAIL_FILE_PATH'] != '' && file_exists($getHtmlRow['REPORT_DETAIL_FILE_PATH'])) {
                        
                        $reportDetailHtml = Str::cleanOut(file_get_contents($getHtmlRow['REPORT_DETAIL_FILE_PATH']));
                        
                    } else {
                        $reportDetailHtml = '';
                    }
                    
                    $addonTableBody = $addonTableFoot = '';
                    
                    if ($renderType == 'card' || $renderType == 'notloop') {
                        
                        $tableBody = $reportDetailHtml;
                        $tableFoot = '';
                        
                    } else {
                        
                        $detailHtml = phpQuery::newDocumentHTML($reportDetailHtml);
                        $tableHeadCount = $detailHtml['table > thead']->length;
                        
                        if ($tableHeadCount == 0) {
                            $tableHtml = $detailHtml['table:eq(0)'];
                        } else {
                            $tableHtml = $detailHtml['table:has(thead):eq(0)'];
                        }
                        
                        foreach ($tableHtml['thead > tr'] as $tr) {
                            $backColor = pq($tr)->css('background-color');

                            if ($backColor) {
                                foreach (pq($tr)->find('th, td') as $cells) {
                                    pq($cells)->css('background-color', $backColor.' !important');
                                }
                            }
                        }
                        
                        $colGroup  = $tableHtml['colgroup']->html();
                        $tableHead = $tableHtml['thead']->html();
                        $tableBody = $tableHtml['tbody']->html();
                        $tableFoot = $tableHtml['tfoot']->html();

                        $tableHtml->empty();
                        
                        if ($colGroup) {
                            $tableHtml->append('<colgroup>'.$colGroup.'</colgroup>');
                        }
                        if ($tableHead) {
                            $tableHtml->append('<thead>'.$tableHead.'</thead>');
                        }
                        if ($tableBody) {
                            $tableHtml->append('<tbody>'.$tableBody.'</tbody>');
                        }
                        if ($tableFoot) {
                            $tableHtml->append('<tfoot>'.$tableFoot.'</tfoot>');
                        }
                        
                        if ($tableHeadCount > 1) {
                            
                            self::$isMultiDetail = true;
                            
                            $loopCount = $tableHeadCount - 1;
                            
                            for ($i = 1; $i <= $loopCount; ++$i) {
                                
                                $addonTableHtml = $detailHtml['table:has(thead):eq('.$i.')'];
                                
                                foreach ($addonTableHtml['thead > tr'] as $tr) {
                                    $backColor = pq($tr)->css('background-color');
                                    
                                    if ($backColor) {
                                        foreach (pq($tr)->find('th, td') as $cells) {
                                            pq($cells)->css('background-color', $backColor.' !important');
                                        }
                                    }
                                }
                                
                                $otherTableHead = $addonTableHtml['thead']->html();
                                $otherTableBody = $addonTableHtml['tbody']->html();
                                $otherTableFoot = $addonTableHtml['tfoot']->html();
                                
                                $addonTableHtml->empty();
                                
                                $addonTableHtml->append('<thead>'.$otherTableHead.'</thead>');

                                if ($otherTableBody) {
                                    $addonTableHtml->append('<tbody>'.$otherTableBody.'</tbody>');
                                }
                                if ($otherTableFoot) {
                                    $addonTableHtml->append('<tfoot>'.$otherTableFoot.'</tfoot>');
                                }
                                
                                $addonTableBody .= '<div id="body-'.$i.'">'.$otherTableBody.'</div>';
                                $addonTableFoot .= $otherTableFoot;
                            }
                            
                            $addonTableBodyRows = array();
                        }
                    }
                    
                    $mergeDetail = $tableBody.$addonTableBody;
                    
                    $mergeHtml = $reportHeader.$pageHeader.$pageFooter.$reportFooter.$tableFoot.$addonTableFoot.$mergeDetail;
                
                    preg_match_all('/sum\(\#(.*?)\#\)/i', $mergeHtml, $sumAggregate); // aggregate (sum)
                    preg_match_all('/avg\(\#(.*?)\#\)/i', $mergeHtml, $avgAggregate); // aggregate (avg)
                    preg_match_all('/min\(\#(.*?)\#\)/i', $mergeHtml, $minAggregate); // aggregate (min)
                    preg_match_all('/max\(\#(.*?)\#\)/i', $mergeHtml, $maxAggregate); // aggregate (max)
                    preg_match_all('/count\(\)/i', $mergeHtml, $countAggregate); // aggregate (count)
                    
                    if (count($sumAggregate[1]) > 0) {
                        foreach ($sumAggregate[1] as $s => $sv) {
                            self::$data[$sv.'_sum'] = 0;
                            $mergeDetail = str_replace("sum(#$sv#)", "sum(|$sv|)", $mergeDetail);
                            $tableBody = str_replace("sum(#$sv#)", "sum(|$sv|)", $tableBody);
                            $addonTableBody = str_replace("sum(#$sv#)", "sum(|$sv|)", $addonTableBody);
                        }
                    }
                    if (count($avgAggregate[1]) > 0) {
                        foreach ($avgAggregate[1] as $a => $av) {
                            self::$data[$av.'_avg'] = 0;
                            self::$data[$av.'_avgCount'] = 0;
                            $mergeDetail = str_replace("avg(#$av#)", "avg(|$av|)", $mergeDetail);
                            $tableBody = str_replace("avg(#$av#)", "avg(|$av|)", $tableBody);
                            $addonTableBody = str_replace("avg(#$av#)", "avg(|$av|)", $addonTableBody);
                        }
                    }
                    if (count($minAggregate[1]) > 0) {
                        foreach ($minAggregate[1] as $m => $mv) {
                            self::$data[$mv.'_min'] = array();
                            $mergeDetail = str_replace("min(#$mv#)", "min(|$mv|)", $mergeDetail);
                            $tableBody = str_replace("min(#$mv#)", "min(|$mv|)", $tableBody);
                            $addonTableBody = str_replace("min(#$mv#)", "min(|$mv|)", $addonTableBody);
                        }
                    }
                    if (count($maxAggregate[1]) > 0) {
                        foreach ($maxAggregate[1] as $ma => $mav) {
                            self::$data[$mav.'_max'] = array();
                            $mergeDetail = str_replace("max(#$mav#)", "max(|$mav|)", $mergeDetail);
                            $tableBody = str_replace("max(#$mav#)", "max(|$mav|)", $tableBody);
                            $addonTableBody = str_replace("max(#$mav#)", "max(|$mav|)", $addonTableBody);
                        }
                    }
                    if (count($countAggregate[0]) > 0) {
                        $row_count = count($dataRows);
                    }
                    
                    $isRenderColumn = $this->model->isRenderColumnModel($mergeDetail, $expressionArr['rowFields']); 
                    
                    $numIterations = 0;
                    $appendTableRow = '';
                    
                    if (is_null($getRowStatement['PROCESS_META_DATA_ID'])) {
                        
                        include BASEPATH . 'middleware/views/statement/dataview/parts/noCalculateDetail.php'; 
                        
                    } else {
                        
                        $repFinStyles = $this->model->getRepFinStylesModel();
                        $isRowStyle = false;
                        
                        if (array_key_exists('styletag', $dataRows[0])) {
                            $isRowStyle = true;
                        }
                        
                        $tabSizeIds = array('1499429114847', '1499429114848', '1499429114849', '1499429114850', '1499429114851', '1499429114852');
                        
                        foreach ($dataRows as $n => $row) {
                        
                            $numIterations++;

                            $tableBodyRow = $tableBody;
                            $addonTableBodyRow = $addonTableBody;

                            eval($expressionArr['rowExp']);
                            
                            if ($isRowStyle && isset($repFinStyles[$row['styletag']])) {
                                
                                $tableBodyRow = str_replace('<tr style="', '<tr style="'.$repFinStyles[$row['styletag']]['row'], $tableBodyRow, $replacedCount);
                                if ($replacedCount == 0) {
                                    $tableBodyRow = str_replace('<tr', '<tr style="'.$repFinStyles[$row['styletag']]['row'].'"', $tableBodyRow);
                                }
                                
                                /* TabSize */
                                if (in_array($row['styletag'], $tabSizeIds)) {
                                    $tableBodyRow = str_replace('">#name#', $repFinStyles[$row['styletag']]['cell'].'">#name#', $tableBodyRow, $replacedCount);
                                    if ($replacedCount == 0) {
                                        $tableBodyRow = str_replace('>#name#', ' style="'.$repFinStyles[$row['styletag']]['cell'].'">#name#', $tableBodyRow);
                                    } 
                                }
                            }

                            foreach ($row as $k => $v) {
                                
                                if (isset(self::$data[$k.'_sum'])) {
                                    self::$data[$k.'_sum'] += $v;
                                }
                                if (isset(self::$data[$k.'_avg']) && $v != '') {
                                    self::$data[$k.'_avg'] += $v;
                                    self::$data[$k.'_avgCount'] += 1;
                                }
                                if (isset(self::$data[$k.'_min'])) {
                                    array_push(self::$data[$k.'_min'], $v);
                                }
                                if (isset(self::$data[$k.'_max'])) {
                                    array_push(self::$data[$k.'_max'], $v);
                                }

                                if (isset($isRenderColumn[$k])) {

                                    $anchorStart = $anchorEnd = '';

                                    if (isset(self::$drillDownColumns[$k])) {
                                        $anchorStart = '<a href="javascript:;" data-row-data="'.$n.'|'.$statementId.'|'.$row['rid'].'|'.self::$uniqId.'|'.$k.'|'.Mdstatement::$isStatementModeNum.'" onclick="drillDownStatement(this);">';
                                        $anchorEnd = '</a>';
                                    }

                                    $typeCode = $this->model->getTypeCodeColumnModel(self::$dataViewColumnsType, $k);
                                    
                                    $v = self::stCellValue($typeCode, $k, $v);

                                    $tableBodyRow = str_replace('#'.$k.'#', $anchorStart.$v.$anchorEnd, $tableBodyRow);

                                    if (self::$isMultiDetail) {
                                        $addonTableBodyRow = str_replace('#'.$k.'#', $anchorStart.$v.$anchorEnd, $addonTableBodyRow);
                                    }
                                }
                            }

                            $tableBodyRow = str_replace('#rownum#', $numIterations, $tableBodyRow);

                            if (self::$isMultiDetail && isset($loopCount)) {

                                $addonTableBodyRow = str_replace('#rownum#', $numIterations, $addonTableBodyRow);

                                for ($i = 1; $i <= $loopCount; ++$i) {

                                    $addonTableBodyLoad = phpQuery::newDocumentHTML($addonTableBodyRow);
                                    $addonTableBodyRowHtml = $addonTableBodyLoad['div#body-'.$i]->html();

                                    $addonTableBodyRows[$i] = (isset($addonTableBodyRows[$i]) ? $addonTableBodyRows[$i] : '') . $addonTableBodyRowHtml;
                                }
                            }

                            $appendTableRow .= $tableBodyRow;
                        }
                    }
                    
                    if ($renderType == 'card') {
                        
                        $reportDetailHtml = $appendTableRow;
                        
                    } elseif ($renderType == 'notloop') {
                        
                        $reportDetailHtml = $tableBody;
                        
                    } else {
                        
                        if (self::$isMultiDetail == false) {
                            
                            $tableHtml['tbody']->html($appendTableRow);
                            $reportDetailHtml = $tableHtml->html($tableHtml->html());
                        
                        } else {
                            
                            $detailHtml['table:has(thead):eq(0) > tbody']->html($appendTableRow);

                            if (isset($loopCount)) {
                                for ($i = 1; $i <= $loopCount; ++$i) {
                                    $detailHtml['table:has(thead):eq('.$i.') > tbody']->html($addonTableBodyRows[$i]);
                                }
                            }
                            
                            $reportDetailHtml = $detailHtml->html();
                        }
                    }

                    $html .= $pageHeader;
                    $html .= $reportHeader;
                    
                    if (self::$isPivotView) {
                        
                        $html .= '<div class="pivot-datatable-wrapper" data-left-count="'.Mdstatement::$freezeLeftColumnCount.'">';
                            $html .= $reportDetailHtml;
                        $html .= '</div>';
                        
                    } else {
                        $html .= $reportDetailHtml;
                    }
                    
                    $html .= $reportFooter;
                    $html .= $pageFooter;

                    if (count($sumAggregate[1]) > 0) {
                        foreach ($sumAggregate[1] as $s => $sv) {
                            
                            $typeCode = $this->model->getTypeCodeColumnModel(self::$dataViewColumnsType, $sv);
                        
                            if ($typeCode == 'scale') {
                                $html = str_replace($sumAggregate[0][$s], self::detailFormatMoneyScale(self::$data[$sv.'_sum'], $sv), $html);
                            } elseif ($typeCode == 'setscale') {
                                $html = str_replace($sumAggregate[0][$s], self::detailFormatMoneySetScale(self::$data[$sv.'_sum'], $sv), $html);
                            } else {
                                $amount = number_format(self::$data[$sv.'_sum'], 3, '.', '');
                                
                                if ($typeCode == 'decimal') {
                                    $html = str_replace($sumAggregate[0][$s], self::formatAmountEmpty($amount), $html);
                                } elseif ($typeCode == 'decimal_zero') {
                                    $html = str_replace($sumAggregate[0][$s], self::formatDecimalZero($amount), $html);
                                } elseif ($typeCode == 'floatempty') {
                                    $html = str_replace($sumAggregate[0][$s], self::formatAmountFloatEmpty($amount), $html);
                                } elseif ($typeCode == 'integer') {
                                    $html = str_replace($sumAggregate[0][$s], self::formatAmountFloatEmpty($amount), $html);
                                } elseif ($typeCode == 'floatemptyscale') {
                                    $html = str_replace($sumAggregate[0][$s], self::formatAmountFloatEmptyScale($amount, $sv), $html);
                                } elseif ($typeCode == 'decimal_to_time') {
                                    $html = str_replace($sumAggregate[0][$s], self::decimal_to_time($amount), $html);
                                } else {
                                    
                                    if (isset(Mdstatement::$truncAmountFields[$sv])) {
                                        $html = str_replace($sumAggregate[0][$s], number_format(Number::truncate($amount, Mdstatement::$truncAmountFields[$sv]), Mdstatement::$truncAmountFields[$sv], '.', ','), $html);
                                    } else {
                                        $html = str_replace($sumAggregate[0][$s], self::detailFormatMoney($amount), $html);
                                    }
                            
                                }
                            }
                        
                            $html = str_replace("sum(|$sv|)", self::$data[$sv.'_sum'], $html);
                        }
                    }
                    if (count($avgAggregate[1]) > 0) {
                        
                        foreach ($avgAggregate[1] as $a => $av) {
                            
                            $typeCode = $this->model->getTypeCodeColumnModel(self::$dataViewColumnsType, $av);
                            
                            if (self::$data[$av.'_avg'] && self::$data[$av.'_avgCount']) {
                                $avgAmount = self::$data[$av.'_avg'] / self::$data[$av.'_avgCount'];
                            } else {
                                $avgAmount = '0';
                            }
                            
                            if ($typeCode == 'decimal') {
                                $avgAmount = self::formatAmountEmpty($avgAmount);
                            } elseif ($typeCode == 'decimal_zero') {
                                $avgAmount = self::formatDecimalZero($avgAmount);
                            } elseif ($typeCode == 'floatempty' || $typeCode == 'integer') {
                                $avgAmount = self::formatAmountFloatEmpty($avgAmount);
                            } elseif ($typeCode == 'floatemptyscale') {
                                $avgAmount = self::formatAmountFloatEmptyScale($avgAmount, $av);
                            } elseif ($typeCode == 'scale') {
                                $avgAmount = self::detailFormatMoneyScale($avgAmount, $av);
                            } elseif ($typeCode == 'setscale') {
                                $avgAmount = self::detailFormatMoneySetScale($avgAmount, $av);
                            } elseif ($typeCode == 'decimal_to_time') {
                                $avgAmount = self::decimal_to_time($avgAmount);
                            } else {
                                $avgAmount = self::detailFormatMoney($avgAmount);
                            }
                            
                            $html = str_replace($avgAggregate[0][$a], $avgAmount, $html);
                            
                            if (self::$data[$av.'_avg'] && self::$data[$av.'_avgCount']) {
                                $html = str_replace("avg(|$av|)", (self::$data[$av.'_avg'] / self::$data[$av.'_avgCount']), $html);
                            } else {
                                $html = str_replace("avg(|$av|)", '0', $html);
                            }
                        }
                    }
                    if (count($minAggregate[1]) > 0) {
                        foreach ($minAggregate[1] as $m => $mv) {
                            $html = str_replace($minAggregate[0][$m], self::detailFormatMoney(min(self::$data[$mv.'_min'])), $html);
                            $html = str_replace("min(|$mv|)", min(self::$data[$mv.'_min']), $html);
                        }
                    }
                    if (count($maxAggregate[1]) > 0) {
                        foreach ($maxAggregate[1] as $ma => $mav) {
                            $html = str_replace($maxAggregate[0][$ma], self::detailFormatMoney(max(self::$data[$mav.'_max'])), $html);
                            $html = str_replace("max(|$mav|)", max(self::$data[$mav.'_max']), $html);
                        }
                    }
                    if (count($countAggregate[0]) > 0) {
                        $html = str_ireplace('count()', self::formatAmountFloatEmpty($row_count), $html);
                    }
                    
                    $html = str_replace(array("\n", "\r", "\t"), '', $html);
                    $html = self::getval($html, $dataRows);
                    
                    if ($renderType == 'notloop' && count($dataRows) == 1) {
                        
                        $row = $dataRows[0];
                        
                        foreach ($row as $k => $v) {

                            $anchorStart = $anchorEnd = '';

                            if (isset(self::$drillDownColumns[$k])) {
                                $anchorStart = '<a href="javascript:;" data-row-data="'.$n.'|'.$statementId.'|'.$row['rid'].'|'.self::$uniqId.'|'.$k.'|'.Mdstatement::$isStatementModeNum.'" onclick="drillDownStatement(this);">';
                                $anchorEnd = '</a>';
                            }

                            $typeCode = $this->model->getTypeCodeColumnModel(self::$dataViewColumnsType, $k);
                            
                            $v = self::stCellValue($typeCode, $k, $v);

                            $html = str_replace('#'.$k.'#', $anchorStart.$v.$anchorEnd, $html);
                        }
                    }
                }
                
                if (isset(Mdstatement::$UIExpression['headerFooter'])) {
                    
                    Mdstatement::$UIExpression['headerFooter'] = str_replace('$rowParams', 'self::$data[\'_EXPRESSION_GLOBAL\']', Mdstatement::$UIExpression['headerFooter']);
                    
                    $hdrFtrEval = str_replace('$objHtmlReplace', '$hdrFtrHtml', Mdstatement::$UIExpression['headerFooter']);
                    
                    $hdrFtrHtml = phpQuery::newDocument($html);
                    
                    eval($hdrFtrEval);
                    
                    $html = $hdrFtrHtml;
                }
                
                /* future remove code */
                $configstaticTms = Config::getFromCache('staticTmsReport');
                
                if (issetParam($getRowStatement['IS_TIMETABLE']) === '1' || $configstaticTms) {
                    if (issetParam($getRowStatement['IS_TIMETABLE']) === '1' || $dataViewId ==  $configstaticTms) {  //'1581480427654'
                    
                        $datetime1 = date_create($realParams['filterStartDate']);
                        $datetime2 = date_create($realParams['filterEndDate']);
    
                        $interval = date_diff($datetime1, $datetime2);
                        $days = $interval->days;
                        $days = ($days == 0) ? 1 : $days;
                        $removetags = '';
                        
                        if ($days < 31) {
                            
                            for ((int) $d12 = $days+1; $d12 <= 31; $d12++) {
                                $removetags .= '$objHtmlReplace->find(\'[data-path="d'.  ($d12 > 9 ? $d12 : '0'.$d12)  .'"]\')->remove(); '; 
                            }
                            
                            $hdrFtrEval = str_replace('$objHtmlReplace', '$hdrFtrHtml', $removetags);
                            $hdrFtrHtml = phpQuery::newDocument($html);
    
                            eval($hdrFtrEval);
    
                            $html = $hdrFtrHtml;
                        }
                        
                        for ($i = 1, $index = 0; $index <= $days; $i++, $index++) {
                            $dd = Date::nextDate($realParams['filterStartDate'], $index, 'd') . '<br>' . Lang::line(Date::nextDate($realParams['filterStartDate'], $index, 'D'));
                            $html = str_replace("#day_$i#", $dd, $html);
                        }
                            
                        $html = str_replace("#diff#", $days, $html);
                        $html = str_replace("#rowCount#", $days, $html);
                    }
                }
                
                $html = self::paramKeywordReplacer($html, $params);
                $html = self::formatDate($html, $realParams);
                $html = self::prevDate($html, $realParams);
                $html = self::matchDefaultValue($html);
                $html = self::calculateExpression($html);
                $html = self::runExpression($html);
                $html = self::runExpressionTag($html);
                $html = self::runExpressionStr($html);
                $html = self::numberToWords($html);
                $html = self::reportDateDiff($html);
                $html = self::barcode($html);                
                $html = self::textStyler($html);
                $html = self::langLine($html);
                $html = self::scaleMoneyFormat($html);
                $html = self::dropZeroMoneyFormat($html);
                $html = self::moneyFormat($html);
                $html = self::numberToTime($html);
                $html = self::replaceCyrillicToLatin($html);
                $html = self::pathReplaceByFirstRow($result['rows'][0], $html);
                $html = str_replace('|zero|', '', $html);
                $html = str_replace('-0.00<', '0<', $html);
            }
            
            $html = self::assetsReplacer($html);
            
            $html = self::configValueReplacer($html, $realParams);
            $html = self::reportSubstr($html);
            $html = self::reportCase($html);
            $html = self::reportPageBreak($html);
            $html = self::reportFiscalPeriodDate($html);
            $html = self::drillLinkReplacer($statementId, $html);
            
            $response = array('status' => 'success', 'statementName' => $getRowStatement['REPORT_NAME'], 'htmlData' => $html);
            
        } else {
            $response = array('status' => 'error', 'message' => $result['message']);
        }
        
        return $response;
    }
    
    public static function formatAmountFloatEmpty($v) {
        return (empty($v) || !is_numeric($v)) ? '' : str_replace('.00', '', number_format($v, 2, '.', ','));
    }
    
    public static function formatAmountFloatEmptyScale($v, $field) {
        if (empty($v) || !is_numeric($v)) {
            return '';
        } else {
            $scale = self::$dataViewColumnsSetScale[$field];
            $number = str_replace('.000', '', number_format($v, $scale, '.', ','));
            
            return $number;
        }
    }
    
    public static function stCellValue($typeCode, $k, $v) {
        
        if ($typeCode == 'bigdecimal') {
            $v = self::detailFormatMoney($v);
        } elseif ($typeCode == 'bigdecimal_null') {
            $v = self::detailFormatMoneyNull($v);
        } elseif ($typeCode == 'date') {
            $v = Date::formatter($v, 'Y-m-d');
        } elseif ($typeCode == 'datetime') {
            $v = Date::formatter($v, 'Y-m-d H:i');
        } elseif ($typeCode == 'scale') {
            $v = self::detailFormatMoneyScale($v, $k);
        } elseif ($typeCode == 'setscale') {
            $v = self::detailFormatMoneySetScale($v, $k);
        } elseif ($typeCode == 'decimal') {
            $v = self::formatAmountEmpty($v);
        } elseif ($typeCode == 'decimal_zero') {
            $v = self::formatDecimalZero($v);
        } elseif ($typeCode == 'floatempty') {
            $v = self::formatAmountFloatEmpty($v);
        } elseif ($typeCode == 'floatemptyscale') {
            $v = self::formatAmountFloatEmptyScale($v, $k);
        } elseif ($typeCode == 'barcode' && $v != '') {
            $v = self::generateBarcode($v);
        } elseif ($typeCode == 'signature' && $v != '') {
            $v = '<img src="data:image/jpeg;base64,'.$v.'" style="width: 80px; height : 60px;">';
        } elseif ($typeCode == 'html_decode') {
            $v = html_entity_decode($v, ENT_QUOTES, 'UTF-8');
        } elseif ($typeCode == 'description' || $typeCode == 'description_auto') {
            $v = Str::nlTobr($v);
        } elseif ($typeCode == 'check') {
            $v = ($v == '1' ? '✓' : '');
        } elseif ($typeCode == 'decimal_to_time') {
            $v = self::decimal_to_time($v);
        } /*else {
            $v = '&#8203;'.$v;
        }*/
        
        return $v;
    }
    
    public static function decimal_to_time($v) {
        if ($v != '' && $v != '0') {
            $h = floor($v / 60);
            $m = $v % 60;
            $h = $h < 10 ? '0'.$h : $h;
            $m = $m < 10 ? '0'.$m : $m;
            $v = $h.':'.$m;
        } 
        return $v;
    }
    
    public function generateReportHtml() {
        set_time_limit(0);
        
        $inputData = file_get_contents('php://input');
        @file_put_contents(BASEPATH.'log/service_response.log', $inputData);
        
        $postData = json_decode($inputData, true);
        
        if (is_null($postData))
            parse_str($inputData, $postData);
        
        if (!isset($postData['dataViewId']) && !isset($postData['statementId'])) {
            echo 'Oroltiin parametr todorhoi bus baina!'; exit;
        }
        
        $dataViewId = Input::param($postData['dataViewId']);
        $statementId = Input::param($postData['statementId']);
        
        $this->load->model('mdstatement', 'middleware/models/');
        
        loadBarCodeImageData();
        loadPhpQuery();
        
        $status = 'success';
        $message = '';
        $htmlData = '';
        
        if (isset($postData['param'])) {
            $params = $postData['param'];
        } else {
            $params = array();
        }
        
        $getChildStatement = $this->model->getChildStatementListModel($statementId);
                
        if ($getChildStatement['child']) {
            
            if ($getChildStatement['isNotPageBreak'] == '1') {
                $pageBreakTag = '';
            } else {
                $pageBreakTag = '<div style="page-break-after: always;"></div>';
            }
            
            $childStatements = $getChildStatement['child'];
            
            $htmlData .= self::generateReportHeaderFooter($dataViewId, $params, $getChildStatement['srcRow']['REPORT_HEADER']);
            $htmlData .= self::generateReportHeaderFooter($dataViewId, $params, $getChildStatement['srcRow']['PAGE_HEADER']);
            
            foreach ($childStatements as $childStatement) {
                
                $childStatementId = $childStatement['STATEMENT_META_ID'];
                $childDataViewId = $childStatement['DATA_VIEW_ID'];
                
                $renderDataViewStatement = self::renderDataViewStatement($childStatementId, $childDataViewId, $params);
                
                if ($renderDataViewStatement['status'] == 'success') {
                    
                    $message = '';
                    $status = $renderDataViewStatement['status'];
                    $htmlData .= $renderDataViewStatement['htmlData'];
                    
                    if ($childStatement !== end($childStatements)) {
                        $htmlData .= $pageBreakTag;
                    }
                    
                } else {
                    $status = $renderDataViewStatement['status'];
                    $message = $renderDataViewStatement['message'];
                }
            }
            
            $htmlData .= self::generateReportHeaderFooter($dataViewId, $params, $getChildStatement['srcRow']['PAGE_FOOTER']);
            $htmlData .= self::generateReportHeaderFooter($dataViewId, $params, $getChildStatement['srcRow']['REPORT_FOOTER']);
            
        } else {
            
            $renderDataViewStatement = self::renderDataViewStatement($statementId, $dataViewId, $params);
            
            if ($renderDataViewStatement['status'] == 'success') {
                $htmlData .= $renderDataViewStatement['htmlData'];
            } else {
                $status = $renderDataViewStatement['status'];
                $message = $renderDataViewStatement['message'];
            }
        }

        $htmlData = self::editable($htmlData);
        $htmlData = self::qrcode($htmlData);

        ob_start('ob_html_compress'); 
            $response = array(
                'status' => $status, 
                'message' => $message, 
                'htmlData' => $htmlData
            );
            echo json_encode($response);
        ob_end_flush(); 
        
        exit;
    }
    
    public function generateReportHeaderFooter($dataViewId, $params, $html) {
        
        $result = null;
        $trimHtml = trim($html);
        
        if (!empty($trimHtml)) {
            
            $realParams = $params;
            $dataViewColumnsType = $this->model->getTypeCodeDataViewParamsModel($dataViewId);               
            $params = $this->model->setParamsValueModel($dataViewColumnsType, $params);
            
            $result = self::paramKeywordReplacer(Str::cleanOut($html), $params);
            $result = self::configValueReplacer($result, $realParams);
            $result = self::formatDate($result, $realParams);
        }
        
        return $result;
    }
    
    public function renderHtmlByQryStr($statementId = '') {
        
        if (!$statementId) {
            echo 'statementId parameter todorhoi bus bna'; exit;
        }
        
        $this->view->style = '';
        $row = $this->model->getStatementRowModel($statementId);
        
        if ($row) {
            
            loadBarCodeImageData();
            loadPhpQuery();
        
            $this->view->title = $this->lang->line($row['REPORT_NAME']);
            
            $dataViewId = $row['DATA_VIEW_ID'];
            $getData = Input::getData();
            
            if (isset($getData['param'])) {
                
                Session::init();
                $logged = Session::isCheck(SESSION_PREFIX.'LoggedIn');

                if ($logged == false) {
                    Session::set(SESSION_PREFIX . 'LoggedIn', true);
                    Session::set(SESSION_PREFIX . 'lastTime', time());
                }
                
                $_POST['nult'] = true;
            
                $params = $getData['param'];
                
                foreach ($params as $key => $val) {
                    $params[$key] = Mdmetadata::setDefaultValue($val);
                }

            } else {
                $params = array();
            }
        
            $renderStatement = self::renderStatement($statementId, $dataViewId, $params);
            $status = $renderStatement['status'];
            
            if ($status == 'success') {
                
                $htmlData = $renderStatement['htmlData'];
                
                if ($htmlData) {
                    
                    $_POST['fontFamily'] = $row['FONT_FAMILY'];
                    
                    $this->view->style = Mdpreview::printCss('return');
                    $this->view->paddingTop = $row['PAGE_MARGIN_TOP'];
                    $this->view->paddingLeft = $row['PAGE_MARGIN_LEFT'];
                    $this->view->paddingRight = $row['PAGE_MARGIN_RIGHT'];
                    $this->view->paddingBottom = $row['PAGE_MARGIN_BOTTOM'];
                            
                    $htmlData = self::qrcode($htmlData);
                    
                    $this->view->contentHtml = $htmlData;
                    
                } else {
                    $this->view->contentHtml = html_tag('div', array('class' => 'alert alert-warning'), $this->lang->line('msg_no_record_found'));
                }
                
            } else {
                $this->view->contentHtml = $renderStatement['message'];
            }
        
        } else {
            $this->view->title = 'Error - Statement';
            $this->view->contentHtml = $statementId . ' уг statementId-аар тохиргоо олдсонгүй!';
        }
        
        $this->view->render('print/pagesource', self::$viewPath);
    }
    
    public static function detailFormatMoney($v) {
        return (empty($v) || !is_numeric($v)) ? '0' : number_format($v, 2, '.', ',');
    }
    
    public static function detailFormatMoneyNull($v) {
        return ($v == '') ? '' : (is_numeric($v) ? ($v == 0 ? '0' : number_format($v, 2, '.', ',')) : $v);
    }
    
    public static function detailFormatMoneyScale($v, $field) {
        if (empty($v) || !is_numeric($v)) {
            return '0';
        } else {
            $scale = self::$dataViewColumnsTypeScale[$field];
            
            if ($scale == '0') {
                $number = number_format($v, $scale, '.', ',');
            } else {
                $number = Number::trimTrailingZeroes(number_format($v, $scale, '.', ','));
            }
            
            return $number;
        }
    }
    
    public static function detailFormatMoneySetScale($v, $field) {
        if (empty($v) || !is_numeric($v)) {
            return '0';
        } else {
            $scale = self::$dataViewColumnsSetScale[$field];
            $number = number_format($v, $scale, '.', ',');
            
            return $number;
        }
    }
    
    public static function formatAmountEmpty($v) {
        return empty($v) ? '|zero|' : Number::trimTrailingZeroes(number_format($v, 2, '.', ','));
    }
    
    public static function formatDecimalZero($v) {
        return (empty($v) || !is_numeric($v)) ? '0' : Number::trimTrailingZeroes(number_format($v, 2, '.', ','));
    }
    
    public static function getval($html, $dataRows) {
        if (strpos($html, 'getval(') !== false) {
            preg_match_all('/getval\((.*?)\)/i', $html, $getValues);

            if (count($getValues[0]) > 0) {
                
                foreach ($getValues[1] as $ek => $ev) {
                    
                    if (strpos($ev, ',') !== false) {
                        $evArr = explode(',', $ev);
                        
                        if (count($evArr) == 2) {
                            
                            $params = trim(strip_tags($evArr[0]));
                            $column = trim(strip_tags($evArr[1]));
                            
                            $value = self::getValue($params, $column, $dataRows);
                        
                            $html = str_replace($getValues[0][$ek], $value, $html);
                        }
                    } 
                }
            }
        }
        
        return $html;
    }
    
    public static function getTypeCodeColumn($dataViewColumnsType, $column) {
        
        if (isset($dataViewColumnsType[$column])) {
            return $dataViewColumnsType[$column];
        }
        
        return 'string';
    }
    
    public static function getValue($params, $column, $dataRows) {
        
        $params = explode('&', $params);
        $column = strtolower($column);
        $searchArray = array();
        
        foreach ($params as $param) {
            $param = explode('=', $param);

            $key = strtolower($param[0]);
            $value = $param[1];
            
            $searchArray[$key] = $value;
        }
        
        $rowArr = Arr::multidimensional_search($dataRows, $searchArray);
        $returnValue = null;
        
        if (isset($rowArr[$column])) {
            $typeCode = self::getTypeCodeColumn(self::$dataViewColumnsType, $column);
            
            if ($typeCode == 'bigdecimal') {
                $returnValue = empty($rowArr[$column]) ? '' : self::detailFormatMoney($rowArr[$column]);
            } else {
                $returnValue = $rowArr[$column];
            }
        }
            
        return $returnValue;
    }

    public static function runExpression($html) {
        if (strpos($html, 'runExp[') !== false) {
            
            $html = str_replace(array('sum()', 'min()', 'max()', 'avg()'), '0', $html);
            preg_match_all('/runExp\[(.*?)\]/i', $html, $htmlExpression);
            
            if (count($htmlExpression[0]) > 0) {
                foreach ($htmlExpression[1] as $ek => $ev) {
                    
                    $ev = str_replace('++', '+', $ev);
                    $ev = str_replace('--', '-', $ev);
                    $ev = str_replace('+-', '+', $ev);
                    $ev = str_replace('-+', '+', $ev);
                    $ev = str_replace(',', '', html_entity_decode($ev));
                    $ev = str_replace('|zero|', '0', $ev);
                    $ev = str_replace('||', 'OROR', $ev);
                    $ev = str_replace('|', ',', $ev);
                    $ev = str_replace('OROR', '||', $ev);
                    
                    $calcEval = Mdcommon::expressionEvalFix($ev);
                    $calcEval = (is_numeric($calcEval) && is_infinite($calcEval)) ? 0 : $calcEval;
                    $calcEval = is_nan($calcEval) ? 0 : $calcEval;
                    
                    $html = str_replace($htmlExpression[0][$ek], ($calcEval) ? self::detailFormatMoney($calcEval) : '', $html);
                }
            }
        }
        
        return $html;
    }
    
    public static function runExpressionTag($html) {
        if (strpos($html, 'runExpTag[') !== false) {
            
            $html = str_replace(array('sum()', 'min()', 'max()', 'avg()'), '0', $html);
            preg_match_all('/runExpTag\[(.*?)\]/i', $html, $htmlExpression);
            
            if (count($htmlExpression[0]) > 0) {
                
                foreach ($htmlExpression[1] as $ek => $ev) {
                    
                    $ev = str_replace(',', '', html_entity_decode($ev));
                    $ev = str_replace('return <', "return '<", $ev);
                    $ev = str_replace('>;', ">';", $ev);
                    
                    $ev = str_replace('++', '+', $ev);
                    $ev = str_replace('--', '-', $ev);
                    $ev = str_replace('+-', '+', $ev);
                    $ev = str_replace('-+', '+', $ev);
                    $ev = str_replace('|zero|', '0', $ev);
                    $ev = str_replace('||', 'OROR', $ev);
                    $ev = str_replace('|', ',', $ev);
                    $ev = str_replace('OROR', '||', $ev);
                    
                    $calcEval = self::findNumberFormatting(Mdcommon::expressionEvalFix($ev));
                    
                    $html = str_replace($htmlExpression[0][$ek], $calcEval, $html);
                }
            }
        }
        
        return $html;
    }
    
    public static function runExpressionStr($html) {
        if (strpos($html, 'runExpStr[') !== false) {
            
            $html = str_replace(array('sum()', 'min()', 'max()', 'avg()'), '0', $html);
            preg_match_all('/runExpStr\[(.*?)\]/i', $html, $htmlExpression);
            
            if (count($htmlExpression[0]) > 0) {
                
                foreach ($htmlExpression[1] as $ek => $ev) {
                    
                    $ev = str_replace('return <', "return '<", $ev);
                    $ev = str_replace('>;', ">';", $ev);
                    
                    $calcEval = Mdcommon::expressionEvalFix($ev);
                    $html = str_replace($htmlExpression[0][$ek], $calcEval, $html);
                }
            }
        }
        
        return $html;
    }

    public static function findNumberFormatting($str) {
        
        if ($str == '') {
            return null;
        }
        
        $htmlObj = phpQuery::newDocumentHTML($str);      
        $matches = $htmlObj->find('span:not(:empty)');
        
        if ($matches->length) {
            foreach ($matches as $tag) {
                
                $getText = pq($tag)->text();
                $getNumber = str_replace(',', '', strip_tags($getText));
                        
                if (is_numeric($getNumber)) {
                    $number = self::detailFormatMoney($getNumber);
                    pq($tag)->text($number);
                }
            }
            
            return $htmlObj->html($htmlObj->html());
            
        } else {
            
            $getNumber = str_replace(',', '', strip_tags($str));
            
            if (is_numeric($getNumber)) {
                return self::detailFormatMoney($getNumber);
            }
            return $str;
        }
    }

    public static function calculateExpression($html) {
        if (strpos($html, 'calExp[') !== false) {
            
            $html = str_replace(array('sum()', 'min()', 'max()', 'avg()'), '0', $html);
            preg_match_all('/calExp\[(.*?)\]/i', $html, $htmlExpression);

            if (count($htmlExpression[0]) > 0) {
                
                foreach ($htmlExpression[1] as $ek => $ev) {
                    
                    $evalStr = str_replace(',', '', strip_tags($ev));
                    $evalStr = str_replace('&nbsp;', '', $evalStr);
                    $evalStr = trim(Str::remove_doublewhitespace(Str::remove_whitespace_feed($evalStr)));
                    
                    $evalStr = rtrim($evalStr, '+');
                    $evalStr = rtrim($evalStr, '-');
                    $evalStr = rtrim($evalStr, '+');
                    $evalStr = rtrim($evalStr, '-');
                    
                    $evalStr = ltrim($evalStr, '+');
                    $evalStr = ltrim($evalStr, '+');
                    $evalStr = ltrim($evalStr, '-');
                    
                    $evalStr = str_replace('++', '+', $evalStr);
                    $evalStr = str_replace('--', '-', $evalStr);
                    $evalStr = str_replace('+-', '-', $evalStr);
                    $evalStr = str_replace('-+', '+', $evalStr);
                    $evalStr = str_replace('+ -', '-', $evalStr);
                    $evalStr = str_replace('- +', '+', $evalStr);
                    $evalStr = str_replace('+ +', '+', $evalStr);
                    $evalStr = str_replace('- -', '-', $evalStr);
                    $evalStr = str_replace('|zero|', '0', $evalStr);
                    $evalStr = str_replace('|', ',', $evalStr);
                    $evalStr = trim($evalStr);
                    
                    $calcEval = Mdcommon::expressionEvalFixWithReturn($evalStr);
                    $calcEval = (is_numeric($calcEval) && is_infinite($calcEval)) ? 0 : $calcEval;
                    $calcEval = is_nan($calcEval) ? 0 : $calcEval;
                    
                    $html = str_replace($htmlExpression[0][$ek], ($calcEval) ? self::detailFormatMoney($calcEval) : '0', $html);
                }
            }
        }

        return $html;
    }

    public static function generateBarcode($value) {
        
        if ($value == '') { return ''; }
        
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        return '<img src="data:image/png;base64,'.base64_encode($generator->getBarcode($value, $generator::TYPE_CODE_128, 2, 25)).'" border="0" style="width: 100%;height: 15px;">';
    }
    
    public static function generateBarcodeStyle($value, $styles) {
        
        if ($value == '') { return ''; }
        
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        return '<img src="data:image/png;base64,'.base64_encode($generator->getBarcode($value, $generator::TYPE_CODE_128, 2, 25)).'" border="0" style="'.$styles.'">';
    }
    
    public static function generateBarcodeSvg($value) {
        
        if ($value == '') { return ''; }
        
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
        $svg = $generator->getBarcode($value, $generator::TYPE_CODE_128, 3, 150, 'black', false);
        
        return "<img src='data:image/svg+xml;utf8,".$svg."' border='0' style='width: 100%;height: 15px;'>";
    }
    
    public static function generateBarcodeSvgStyle($value, $styles) {
        
        if ($value == '') { return ''; }
        
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
        $svg = $generator->getBarcode($value, $generator::TYPE_CODE_128, 3, 150, 'black', false);
        
        return "<img src='data:image/svg+xml;utf8,".$svg."' border='0' style='".$styles."'>";
    }

    public static function assetsReplacer($html) {
        $html = str_replace(array('"storage/uploads/', "'storage/uploads/"), array('"'.URL.'storage/uploads/', "'".URL."storage/uploads/"), $html);
        return $html;
    }
    
    public static function numberToWords($html) {
        if (strpos($html, 'n2w(') !== false) {
            preg_match_all('/n2w\((.*?)\)/i', $html, $htmlNumberToWords);

            if (count($htmlNumberToWords[0]) > 0) {
                foreach ($htmlNumberToWords[1] as $ek => $ev) {
                    
                    if (strpos($ev, '|') === false) {
                        $words = amountToWords(strip_tags($ev));
                    } else {
                        $evArr = explode('|', $ev);
                        
                        if (count($evArr) == 3) {
                            
                            if (isset($evArr[2])) {
                                $words = amountToWords(strip_tags($evArr[0]), strip_tags($evArr[1]), strip_tags($evArr[2]));
                            } else {
                                $words = amountToWords(strip_tags($evArr[0]), strip_tags($evArr[1]));
                            }
                            
                        } else {
                            $words = amountToWords(strip_tags($evArr[0]), strip_tags($evArr[1]));
                        }
                    }
                    
                    $html = str_replace($htmlNumberToWords[0][$ek], $words, $html);
                }
            }
        }
        
        return $html;
    }
    
    public static function numberToTime($html) {
        if (strpos($html, 'n2time(') !== false) {
            preg_match_all('/n2time\((.*?)\)/i', $html, $htmlNumberToTime);
            
            if (count($htmlNumberToTime[0]) > 0) {
                foreach ($htmlNumberToTime[1] as $ek => $ev) {

                    $minutes = str_replace(',', '', strip_tags($ev));
                    $hours = floor($minutes / 60); 
                    $minutes = $minutes % 60; 
                    
                    $time = sprintf('%02d:%02d', $hours, $minutes); 
                    
                    $html = str_replace($htmlNumberToTime[0][$ek], $time, $html);
                }
            }
        }
        
        return $html;
    }
    
    public static function matchDefaultValue($html) {
        
        if (strpos($html, 'defaultValue(') !== false) {
            preg_match_all('/defaultValue\(([\w\W]*?)\)/i', $html, $defaulValues);
            
            if (isset($defaulValues[0][0])) {

                foreach ($defaulValues[1] as $ek => $ev) {
                    
                    $evArr = explode('|', $defaulValues[1][$ek]);
                    
                    $evVal = trim($evArr[0]);
                    $evDefaultVal = trim($evArr[1]);
                    
                    if ($evVal == '') {
                        $evVal = $evDefaultVal;
                    }
                    
                    $html = str_replace($defaulValues[0][$ek], $evVal, $html);
                }
            }
        }
        
        return $html;
    }
    
    public static function pathReplaceByFirstRow($row, $html) {
        if (strpos($html, 'pathReplace(') !== false) {
            preg_match_all('/pathReplace\((.*?)\)/i', $html, $pathReplace);
            
            if (count($pathReplace[0]) > 0) {
                foreach ($pathReplace[1] as $ek => $ev) {

                    $path = str_replace(array(',', '#'), '', strip_tags($ev));
                    $typeCode = isset(self::$dataViewColumnsType[$path]) ? self::$dataViewColumnsType[$path] : '';
                    $val = issetParam($row[$path]);
                    
                    if ($typeCode == 'bigdecimal') {
                        $val = self::detailFormatMoney($val);
                    } elseif ($typeCode == 'bigdecimal_null') {
                        $val = self::detailFormatMoneyNull($val);
                    } elseif ($typeCode == 'date') {
                        $val = Date::formatter($val, 'Y-m-d');
                    } elseif ($typeCode == 'datetime') {
                        $val = Date::formatter($val, 'Y-m-d H:i');
                    }
                    
                    $html = str_replace($pathReplace[0][$ek], $val, $html);
                }
            }
        }
        
        return $html;
    }
    
    public static function reportDateDiff($html) {
        if (strpos($html, 'dateDiff(') !== false) {
            preg_match_all('/dateDiff\((.*?)\)/i', $html, $htmlDateDiffs);

            if (count($htmlDateDiffs[0]) > 0) {
                foreach ($htmlDateDiffs[1] as $ek => $ev) {

                    $evArr = explode(',', str_replace('&nbsp;', '', $ev));
                    
                    $diffs = Date::diffDays(strip_tags($evArr[0]), strip_tags($evArr[1]), strip_tags($evArr[2]));
                    
                    $html = str_replace($htmlDateDiffs[0][$ek], $diffs, $html);
                }
            }
        }
        
        return $html;
    }
    
    public static function prevDate($html, $params) {
        if (strpos($html, 'prevDate(') !== false) {
            
            preg_match_all('/prevDate\((.*?)\)/i', $html, $htmlPrevDates);

            if (count($htmlPrevDates[0]) > 0) {
                
                $params = Arr::changeKeyLower($params);
                
                foreach ($htmlPrevDates[1] as $ek => $ev) {

                    $evArr = explode(',', str_replace('&nbsp;', '', $ev));
                    
                    if (count($evArr) == 3) {
                        $path = strip_tags(trim($evArr[0]));
                        
                        if (isset($params[$path])) {
                            $date = $params[$path];
                        } else {
                            $date = $path;
                        }
                        
                        $format = strip_tags(trim($evArr[1]));
                        $criteria = strip_tags(trim($evArr[2]));

                        $afterDay = Date::weekdayAfter($format, $date, $criteria);
                        $html = str_replace($htmlPrevDates[0][$ek], $afterDay, $html);
                    }
                }
            }
        }
        
        return $html;
    }
    
    public static function formatDate($html, $params) {
        if (strpos($html, 'formatDate(') !== false) {
            
            preg_match_all('/formatDate\((.*?)\)/i', $html, $htmlFormatDates);

            if (count($htmlFormatDates[0]) > 0) {
                
                $params = Arr::changeKeyLower($params);
                
                foreach ($htmlFormatDates[1] as $ek => $ev) {

                    $evArr = explode(',', str_replace('&nbsp;', '', $ev));
                    
                    if (count($evArr) == 2) {
                        $path = strip_tags(trim($evArr[0]));
                        
                        if (isset($params[$path])) {
                            
                            $format = strip_tags(trim($evArr[1]));
                            
                            $afterDay = Date::formatter($params[$path], $format);
                            $html = str_replace($htmlFormatDates[0][$ek], $afterDay, $html);
                        }
                    }
                }
            }
        }
        
        return $html;
    }
    
    public function reportFiscalPeriodDate($html) {
        if (strpos($html, 'fiscalPeriodDate[') !== false) {
            
            preg_match_all('/fiscalPeriodDate\[(.*?)\]/i', $html, $fiscalPeriodDates);

            if (count($fiscalPeriodDates[0]) > 0) {
                
                $fiscalPeriodStartDate = Ue::sessionFiscalPeriodStartDate();
                
                foreach ($fiscalPeriodDates[1] as $k => $ev) {

                    $ev = strtolower(strip_tags(trim(str_replace('&nbsp;', '', $ev))));
                    $v = '';
                    
                    if ($ev == 'year') {
                        $v = Date::formatter($fiscalPeriodStartDate, 'Y');
                    } elseif ($ev == 'quarter') {
                        $month = Date::formatter($fiscalPeriodStartDate, 'm');
                        
                        if ($month >= 1 && $month <= 3) {
                            $v = '1';
                        } elseif ($month >= 4 && $month <= 6) {
                            $v = '2';
                        } elseif ($month >= 7 && $month <= 9) {
                            $v = '3';
                        } elseif ($month >= 9 && $month <= 12) {
                            $v = '4';
                        }
                    }

                    $html = str_replace($fiscalPeriodDates[0][$k], $v, $html);
                }
            }
        }
        
        return $html;
    }
    
    public static function barcode($html) {
        if (strpos($html, 'barcode(') !== false) {
            preg_match_all('/barcode\((.*?)\)/i', $html, $htmlBarcodes);
            
            if (count($htmlBarcodes[0]) > 0) {
                foreach ($htmlBarcodes[1] as $ek => $ev) {
                    $ev = str_replace('&#8203;', '', $ev);
                    if (!empty($ev)) {
                        if (strpos($ev, ',') === false) {
                            $ev = self::generateBarcode(strip_tags($ev));
                        } else {
                            $exArr = explode(',', $ev);
                            $ev = self::generateBarcodeStyle(strip_tags($exArr[0]), $exArr[1]);
                        }
                    }
                    $html = str_replace($htmlBarcodes[0][$ek], $ev, $html);
                }
            }
        }
        
        if (strpos($html, 'barcodeSvg(') !== false) {
            preg_match_all('/barcodeSvg\((.*?)\)/i', $html, $htmlSvgBarcodes);
            
            if (count($htmlSvgBarcodes[0]) > 0) {
                foreach ($htmlSvgBarcodes[1] as $ek => $ev) {
                    $ev = str_replace('&#8203;', '', $ev);
                    if (!empty($ev)) {
                        if (strpos($ev, ',') === false) {
                            $ev = self::generateBarcodeSvg(strip_tags($ev));
                        } else {
                            $exArr = explode(',', $ev);
                            $ev = self::generateBarcodeSvgStyle(strip_tags($exArr[0]), $exArr[1]);
                        }
                    }
                    $html = str_replace($htmlSvgBarcodes[0][$ek], $ev, $html);
                }
            }
        }
        
        return $html;
    }
    
    public static function qrcode($html) {
        if (strpos($html, 'qrcode(') !== false) {
            preg_match_all('/qrcode\((.*?)\)/i', $html, $htmlQrCodes);
            
            if (count($htmlQrCodes[0]) > 0) {
                
                includeLib('QRCode/qrlib');
                
                foreach ($htmlQrCodes[1] as $ek => $ev) {
                    $ev = str_replace('&#8203;', '', $ev);
                    if (!empty($ev)) {
                        
                        $evArr = explode('|', $ev);
                        $qrStr = trim($evArr[0]);
                        
                        if ($qrStr) {
                            
                            $height = '100';
                        
                            if (isset($evArr[1]) && trim($evArr[1])) {

                                $heightInline = str_replace('px', '', trim($evArr[1]));

                                if (is_numeric($heightInline)) {
                                    $height = $heightInline;
                                }
                            }

                            ob_start();

                            QRcode::png(strip_tags($qrStr), null, 'L', 6, 0);
                            $imageData = ob_get_contents();

                            ob_end_clean();

                            $ev = '<img src="data:image/png;base64,'.base64_encode($imageData).'" style="height: '.$height.'px">';
                            
                        } else {
                            $ev = '';
                        }
                    }
                    
                    $html = str_replace($htmlQrCodes[0][$ek], $ev, $html);
                }
            }
        }
        
        return $html;
    }
    
    public static function editable($html) {
        if (strpos($html, 'editable(') !== false) {
            preg_match_all('/editable\(([\w\W]*?)\)/i', $html, $htmlEdits);
            
            if (count($htmlEdits[0]) > 0) {
                foreach ($htmlEdits[1] as $ek => $ev) {
                    $html = str_replace($htmlEdits[0][$ek], '<span contenteditable="true" id="'.$ek.'">'.$ev.'</span>', $html);
                }
            }
        }
        
        if (strpos($html, 'edit(') !== false) {
            preg_match_all('/edit\(([\w\W]*?)\)/i', $html, $htmlEdits);
            
            if (count($htmlEdits[0]) > 0) {
                foreach ($htmlEdits[1] as $ek => $ev) {
                    $html = str_replace($htmlEdits[0][$ek], '<span class="not-style" contenteditable="true" id="'.$ek.'">'.$ev.'</span>', $html);
                }
            }
        }
        
        return $html;
    }
    
    public static function textStyler($html) {
        if (strpos($html, 'l-r(') !== false) {
            preg_match_all('/l-r\(([\w\W]*?)\)/i', $html, $LeftRotateStyles);
            
            if (count($LeftRotateStyles[0]) > 0) {
                foreach ($LeftRotateStyles[1] as $ek => $ev) {
                    $html = str_replace($LeftRotateStyles[0][$ek], '<div class="left-rotate">'.$ev.'</div>', $html);
                }
            }
        }
        if (strpos($html, 'r-r(') !== false) {
            preg_match_all('/r-r\(([\w\W]*?)\)/i', $html, $RightRotateStyles);
            
            if (count($RightRotateStyles[0]) > 0) {
                foreach ($RightRotateStyles[1] as $ek => $ev) {
                    $html = str_replace($RightRotateStyles[0][$ek], '<div class="right-rotate">'.$ev.'</div>', $html);
                }
            }
        }
        
        return $html;
    }
    
    public static function reportSubstr($html) {
        if (strpos($html, 'substr(') !== false) {
            preg_match_all('/substr\((.*?)\)/i', $html, $htmlSubstr);

            if (count($htmlSubstr[0]) > 0) {
                foreach ($htmlSubstr[1] as $ek => $ev) {
                    
                    if (strpos($ev, ',') !== false) {
                        $evArr = explode(',', $ev);
                        
                        if (count($evArr) == 3) {
                            $words = Str::utf8_substr(strip_tags($evArr[0]), strip_tags($evArr[1]), strip_tags($evArr[2]));
                            $html = str_replace($htmlSubstr[0][$ek], $words, $html);
                        }
                    }
                }
            }
        }
        
        return $html;
    } 
    
    public static function reportCase($html) {
        if (strpos($html, 'upper(') !== false) {
            preg_match_all('/upper\((.*?)\)/i', $html, $htmlUpper);

            if (count($htmlUpper[0]) > 0) {
                foreach ($htmlUpper[1] as $ek => $ev) {
                    
                    $html = str_replace($htmlUpper[0][$ek], Str::upper(strip_tags($ev)), $html);
                }
            }
        }
        
        if (strpos($html, 'lower(') !== false) {
            preg_match_all('/lower\((.*?)\)/i', $html, $htmlLower);

            if (count($htmlLower[0]) > 0) {
                foreach ($htmlLower[1] as $ek => $ev) {
                    
                    $html = str_replace($htmlLower[0][$ek], Str::lower(strip_tags($ev)), $html);
                }
            }
        }
        
        return $html;
    }
    
    public static function reportPageBreak($html) {
        $html = str_replace('<!-- pagebreak -->', '<div style="page-break-after: always;"></div>', $html);
        return $html;
    }
    
    public static function configValueReplacer($content, $params = array()) {

        preg_match_all('/#config_(.*?)#/', $content, $parseContent);

        if (count($parseContent[1]) > 0) {
            
            if (isset(Mdtemplate::$responseData['filterdepartmentid'])) {
                $departmentId = Mdtemplate::$responseData['filterdepartmentid'];
            } else {
                
                $departmentId = null;
                $params = Arr::changeKeyLower($params);
            
                if (isset($params['departmentid'])) {
                    $departmentId = $params['departmentid'];
                } elseif (isset($params['filterdepartmentid'])) {
                    $departmentId = $params['filterdepartmentid'];
                }

                if (!$departmentId) {
                    $sessionUserKeyDepartmentId = Ue::sessionUserKeyDepartmentId();
                    $departmentId = $sessionUserKeyDepartmentId ? $sessionUserKeyDepartmentId : Ue::sessionDepartmentId();
                }

                if (is_array($departmentId)) {
                    if (count($departmentId) === 1) {
                        $departmentId = $departmentId[0];
                    } else {
                        $departmentId = null;
                    }
                }
            }
            
            foreach ($parseContent[1] as $k => $val) {
                if ($val == 'costCenterDepartmentName') {
                    $content = str_ireplace($parseContent[0][$k], (new Mdstatement())->getCostDepartmentName($departmentId), $content);
                } else {
                    $content = str_ireplace($parseContent[0][$k], Config::get($val, 'departmentId='.$departmentId.';'), $content);
                }
            }
        }
        
        return $content;
    }
    
    public function getCostDepartmentName($departmentId) {
        $this->load->model('mdstatement', 'middleware/models/');
        $name = $this->model->getCostDepartmentNameModel($departmentId);
        return $name;
    }
    
    public static function langLine($html) {
        if (strpos($html, 'lang(') !== false) {
            preg_match_all('/lang\((.*?)\)/i', $html, $langCodes);
            
            if (count($langCodes[0]) > 0) {
                foreach ($langCodes[1] as $ek => $ev) {
                    $ev = str_replace('&#8203;', '', $ev);
                    if (!empty($ev)) {
                        $ev = Lang::line(strip_tags($ev));
                    }
                    $html = str_replace($langCodes[0][$ek], $ev, $html);
                }
            }
        }
        
        return $html;
    }
    
    public static function moneyFormat($html) {
        if (strpos($html, 'm(') !== false) {
            preg_match_all('/m\((.*?)\)/i', $html, $moneys);
            
            if (count($moneys[0]) > 0) {
                foreach ($moneys[1] as $ek => $ev) {
                    $ev = str_replace('&#8203;', '', $ev);
                    $calcEval = str_replace(',', '', strip_tags($ev));
                    $html = str_replace($moneys[0][$ek], ($calcEval) ? self::detailFormatMoney($calcEval) : '0', $html);
                }
            }
        }
        
        return $html;
    }
    
    public static function scaleMoneyFormat($html) {
        
        if (strpos($html, 'ms(') !== false) {
            preg_match_all('/ms\((.*?)\)/i', $html, $moneys);
            
            if (count($moneys[0]) > 0) {
                foreach ($moneys[1] as $ek => $ev) {
                    
                    $ev = str_replace('&#8203;', '', $ev);
                    $ev = str_replace(',', '', strip_tags($ev));
                    $evArr = explode('|', $ev);
                    
                    if (empty($evArr[0])) {
                        $sv = '0';
                    } else {
                        $sv = number_format($evArr[0], issetDefaultVal($evArr[1], 2), '.', ',');
                    }
        
                    $html = str_replace($moneys[0][$ek], $sv, $html);
                }
            }
        }
        
        return $html;
    }
    
    public static function dropZeroMoneyFormat($html) {
        if (strpos($html, 'dzm(') !== false) {
            preg_match_all('/dzm\((.*?)\)/i', $html, $moneys);
            
            if (count($moneys[0]) > 0) {
                foreach ($moneys[1] as $ek => $ev) {
                    
                    $ev = str_replace('&#8203;', '', $ev);
                    $calcEval = str_replace(',', '', strip_tags($ev));
                    
                    $html = str_replace($moneys[0][$ek], ($calcEval) ? self::dropZeroFormatMoney($calcEval) : '', $html);
                }
            }
        }
        
        return $html;
    }
    
    public static function replaceCyrillicToLatin($html) {
        if (strpos($html, 'c2l(') !== false) {
            preg_match_all('/c2l\((.*?)\)/i', $html, $moneys);
            
            if (count($moneys[0]) > 0) {
                foreach ($moneys[1] as $ek => $ev) {
                    
                    if (Lang::getCode() == 'en') {
                        
                        $ev = str_replace('&#8203;', '', $ev);
                        $calcEval = strip_tags($ev);
                    
                        $html = str_replace($moneys[0][$ek], cyrillicToLatin($calcEval), $html);
                    } else {
                        $html = str_replace($moneys[0][$ek], $moneys[1][$ek], $html);
                    }
                }
            }
        }
        
        return $html;
    }
    
    public static function drillLinkReplacer($statementId, $html) {
        if (strpos($html, 'drill(') !== false) {
            preg_match_all('/drill\((.*?)\)/i', $html, $drills);
            if (count($drills[0]) > 0) {
                foreach ($drills[1] as $ek => $ev) {
                    
                    $evArr = explode(',', $ev);
                    $drillPath = $evArr[0];
                    
                    unset($evArr[0]);
                    $linkText = trim(implode(',', $evArr));
                    
                    $anchorStart = '<a href="javascript:;" data-row-data="0|'.$statementId.'|1|'.self::$uniqId.'|'.$drillPath.'|'.Mdstatement::$isStatementModeNum.'" onclick="drillDownStatement(this);">';
                    $anchorEnd = '</a>';
                            
                    $html = str_replace($drills[0][$ek], $anchorStart.$linkText.$anchorEnd, $html);
                }
            }
        }
        
        return $html;
    }
    
    public static function dropZeroFormatMoney($m) {
        
        if (is_numeric($m)) {
            $m = number_format($m, 2, '.', ',');
            $m = Number::trimTrailingZeroes($m);
        }
        
        return $m;
    }

    public function excelExport()  {
        
        /*Auth::handleLogin();
        
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        includeLib('Office/Excel/phpspreadsheet/vendor/autoload');
        loadPhpQuery();
        
        $reportName = Input::post('reportName');
        $fileId = Input::post('fileId');
        $fileName = $reportName.' - '.Date::currentDate('YmdHi').'.xlsx';
        
        $htmlContent = $this->model->readStatementHtmlFile($fileId);
        $htmlContent = str_replace(array('href="javascript:;" ', '<br>', '<br/>', '<br />'), '', $htmlContent);
        $editableObjs = issetParam($_POST['editableObjs']);
        
        $contentObject = phpQuery::newDocumentHTML($htmlContent);

        if ($contentObject->find('#ignore-excel')->length > 0) {
            $contentObject->find('#ignore-excel')->remove();
        }
    
        if ($editableObjs) {
            foreach ($editableObjs as $k => $v) {
                $contentObject->find('span[contenteditable="true"][id="'.$k.'"]')->html($v);
            }
        }
        
        $htmlContent = $contentObject->html();
        
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
        
        $spreadsheet   = $reader->loadFromString($htmlContent);
        $sheet         = $spreadsheet->getActiveSheet();
        $highestColumn = $sheet->getHighestDataColumn();

        foreach (range('A', $highestColumn) as $ecolumn) {
            $sheet->getColumnDimension($ecolumn)->setAutoSize(true);
        }
        
        header('Pragma: no-cache');
        header('Expires: 0');
        header('Set-Cookie: fileDownload=true; path=/');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$fileName.'"');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output'); 
        exit;*/
        
        Auth::handleLogin();
        
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        $reportName = Input::post('reportName');
        $fileId = Input::post('fileId');
        $fileName = $reportName.' - '.Date::currentDate('YmdHi').'.xls';
        
        try {
            
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=true; path=/');
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header('Content-Type: application/vnd.ms-excel;charset=utf-8');
            header('Content-Disposition: attachment; filename="'.$fileName.'"');
            header('Content-Transfer-Encoding: binary');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            
            ob_clean();
            flush();
            
            $isDefaultExcelExport = true;
            
            $exportServerAddress = defined('CONFIG_FILECONVERTER_SERVER_ADDRESS') ? CONFIG_FILECONVERTER_SERVER_ADDRESS : (defined('CONFIG_REPORT_SERVER_ADDRESS') ? CONFIG_REPORT_SERVER_ADDRESS : null);
            
            if ($exportServerAddress) {
                
                if (strpos($exportServerAddress, 'fileConverter') !== false) {
                    $exportServerAddress .= 'Converter.aspx';
                } else {
                    $exportServerAddress = str_replace(array('report/', 'report_dev/'), '', $exportServerAddress);
                    $exportServerAddress .= 'fileConverter/Converter.aspx';
                }
                
                $checkHeaderStatus = @file_get_contents($exportServerAddress, false, stream_context_create(['http' => ['timeout' => 2], 'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]));
                
                if ($checkHeaderStatus !== false) {
                        
                    if (Input::isEmpty('statementContent')) {
                        $statementHtmlPath = 'cache/statement_html/'.$fileId.'.html';
                    } else {
                        $htmlContent = html_entity_decode(Input::postNonTags('statementContent'), ENT_QUOTES, 'UTF-8');

                        loadPhpQuery();
                        $contentObject = phpQuery::newDocumentHTML($htmlContent);

                        if ($contentObject->find('#ignore-excel')->length > 0) {
                            $contentObject->find('#ignore-excel')->remove();
                        }
                        
                        $editableObjs = issetParam($_POST['editableObjs']);
                        
                        if ($editableObjs) {
                            foreach ($editableObjs as $k => $v) {
                                $contentObject->find('span[contenteditable="true"][id="'.$k.'"]')->html($v);
                            }
                        }

                        $htmlContent = $contentObject->html();
                        $fileId = $this->model->writeStatementHtmlFile($htmlContent, true);

                        $statementHtmlPath = 'cache/statement_html/'.$fileId.'.html';
                    }

                    if (file_exists($statementHtmlPath)) {
                        
                        $exportServerAddress .= '?mode=html_to_excel&fromurl='. URL . $statementHtmlPath;
                        $getFileRequest = @file_get_contents($exportServerAddress, false, stream_context_create(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]));

                        if ($getFileRequest) {
                            echo $getFileRequest; exit();
                        }
                    }
                }  
            } 
            
            if ($isDefaultExcelExport) {
                
                if (Input::isEmpty('statementContent')) {
                    $htmlContent = $this->model->readStatementHtmlFile($fileId);
                } else {
                    $htmlContent = html_entity_decode(Input::postNonTags('statementContent'), ENT_QUOTES, 'UTF-8');
                }

                echo excelHeadTag($htmlContent, $reportName, issetParam($_POST['editableObjs'])); exit();
            }
            
        } catch (Exception $e) {
            
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=false; path=/');
            
            echo $e->getMessage(); exit();
        }
    }
    
    public function wordExport()  {
        
        Auth::handleLogin();
        
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        $reportName = Input::post('reportName');
        $orientation = Input::post('orientation');
        $fileId = Input::post('fileId');
        $fileName = $reportName.' - '.Date::currentDate('YmdHi').'.doc';
        
        try {
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=true; path=/');
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document;charset=utf-8');
            header('Content-Disposition: attachment; filename="'.$fileName.'"');
            header('Content-Transfer-Encoding: binary');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            flush();
            
            if (Input::isEmpty('statementContent')) {
                $htmlContent = $this->model->readStatementHtmlFile($fileId);
            } else {
                $htmlContent = html_entity_decode(Input::postNonTags('statementContent'), ENT_QUOTES, 'UTF-8');
            }
            
            $attr = array(
                'orientation' => $orientation, 
                'editableObjs' => issetParam($_POST['editableObjs']), 
                'top' => Input::post('top'), 
                'left' => Input::post('left'), 
                'right' => Input::post('right'), 
                'bottom' => Input::post('bottom')
            );
            
            echo wordHeadTag($htmlContent, $attr); exit();
            
        } catch (Exception $e) {
            
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=false; path=/');
            
            echo $e->getMessage(); exit();
        }
    }
    
    public function pdfExport() {
        
        Auth::handleLogin();
        
        $reportName  = Input::post('reportName');
        $orientation = Input::post('orientation');
        $size        = Input::post('size');
        $fileId      = Input::post('fileId');
        
        if (Input::isEmpty('statementContent')) {
            $htmlContent = $this->model->readStatementHtmlFile($fileId);
        } else {
            $htmlContent = html_entity_decode(Input::postNonTags('statementContent'), ENT_QUOTES, 'UTF-8');
        }
        
        $htmlContent = str_replace("\xE2\x80\x8B", '', $htmlContent);
        
        if ($editableObjs = issetParam($_POST['editableObjs'])) {
            
            loadPhpQuery();
            $contentObject = phpQuery::newDocumentHTML($htmlContent);
            
            foreach ($editableObjs as $k => $v) {
                $contentObject->find('span[contenteditable="true"][id="'.$k.'"]')->html($v);
            }
            
            $htmlContent = $contentObject->html();
        }
        
        $htmlContent = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $htmlContent);
        $htmlContent = preg_replace('#<iframe(.*?)>(.*?)</iframe>#is', '', $htmlContent);
        $htmlContent = str_replace('font-size: 11px', 'font-size: 10.4px', $htmlContent);
        
        $css = '<style type="text/css">';
            $css .= Mdpreview::printCss('statementPdf');
        $css .= '</style>';
        
        includeLib('PDF/Pdf');
        
        $pdf = Pdf::createSnappyPdf(($orientation == 'portrait' ? 'Portrait' : 'Landscape'), ($size != 'custom' ? $size : 'letter'));
        
        if (!is_null($pdf)) {
            Pdf::setSnappyOutput($pdf, $css . $htmlContent, $reportName);
        } else {
            $pdf = Pdf::createMPdf(($orientation == 'portrait' ? 'P' : 'L'), ($size != 'custom' ? $size : 'letter'));  
            Pdf::setMpdfOutput($pdf, $css . $htmlContent, $reportName);
        }
    }
    
    public function pdfView() {
        
        Auth::handleLogin();
        
        $cacheTmpDir = Mdcommon::getCacheDirectory();
        $cacheDir = $cacheTmpDir.'/statement_pdf';
        
        $fileId = Input::post('fileId');
        $uniqId = getUID();
        
        $reportName = Input::post('reportName');
        $orientation = Input::post('orientation');
        $size = Input::post('size');
        
        if (Input::isEmpty('statementContent')) {
            $htmlContent = $this->model->readStatementHtmlFile($fileId);
        } else {
            $htmlContent = html_entity_decode(Input::postNonTags('statementContent'), ENT_QUOTES, 'UTF-8');
        }
        
        if ($editableObjs = issetParam($_POST['editableObjs'])) {
            
            loadPhpQuery();
            $contentObject = phpQuery::newDocumentHTML($htmlContent);
            
            foreach ($editableObjs as $k => $v) {
                $contentObject->find('span[contenteditable="true"][id="'.$k.'"]')->html($v);
            }
            
            $htmlContent = $contentObject->html();
        }
        
        $htmlContent = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $htmlContent);
        $htmlContent = preg_replace('#<iframe(.*?)>(.*?)</iframe>#is', '', $htmlContent);
        $htmlContent = str_replace('font-size: 11px', 'font-size: 10.4px', $htmlContent);
        
        $css = '<style type="text/css">';
            $css .= Mdpreview::printCss('statementPdf');
        $css .= '</style>';
        
        includeLib('PDF/Pdf');
        
        $pdf = Pdf::createSnappyPdf(($orientation == 'portrait' ? 'Portrait' : 'Landscape'), ($size != 'custom' ? strtoupper($size) : 'LETTER'));
        
        if (!is_dir($cacheDir)) {

            mkdir($cacheDir, 0777);

        } else {
            
            $currentHour = (int) Date::currentDate('H');
            
            /* Оройны 17 цагаас 19 цагийн хооронд шалгаж өмнө нь үүссэн файлуудыг устгана */
            if ($currentHour >= 17 && $currentHour <= 19) { 
                
                $files = glob($cacheDir.'/*');
                $now   = time();
                $day   = 0.5;

                foreach ($files as $file) {
                    if (is_file($file) && ($now - filemtime($file) >= 60 * 60 * 24 * $day)) {
                        @unlink($file);
                    } 
                }
            }
        }
        
        Pdf::generateFromHtml($pdf, $css . $htmlContent, $cacheDir.'/'.$uniqId);
        
        echo json_encode(array('status' => 'success', 'url' => URL.'api/pdf/web/viewer.html?file=../../../cache/statement_pdf/'.$uniqId.'.pdf#zoom=page-width')); exit;
    }
    
    public function getAllVariablesByJson() {
        $reportType = Input::post('reportType');
        $dataViewId = Input::post('dataViewId');
        
        $sysKeys = $this->model->getSysKeysModel();
        
        if ($reportType == 'dataview') {
            $this->load->model('mdstatement', 'middleware/models/');
            
            $variables = array();
            
            $dataViewColumns = $this->model->getDataViewColumnsModel($dataViewId);
            
            if ($dataViewColumns) {
                $variables = $dataViewColumns;
                foreach ($sysKeys as $row) {
                    array_push($variables, $row);
                }
            } else {
                $variables = $sysKeys;
            }
        } else {
            $variables = $sysKeys;
        }
        
        header('Content-type: application/json');
        echo json_encode($variables); exit;
    }
    
    public function processRunning($metaDataId, $postData, $paramList, $statementMetaDataId) {
        
        $this->load->model('mdstatement', 'middleware/models/');
        
        parse_str($postData['params'], $paramsArr);
        $params = Arr::changeKeyLower($paramsArr['param']);
            
        $configWsUrl = Config::getFromCache('heavyServiceAddress');
        
        if ($configWsUrl && @file_get_contents($configWsUrl, false, stream_context_create(array('http' => array('timeout' => 3))))) {
            $serviceAddress = $configWsUrl;
        } else {
            $serviceAddress = GF_SERVICE_ADDRESS;
        }
            
        $checkMapOwn = $this->model->isCheckOwnMetaMapModel($statementMetaDataId);
                
        if ($checkMapOwn) {
            
            $result = array('status' => 'success');
            $param  = array('tablename' => 'own');
            
        } else {
            
            $this->load->model('mdwebservice', 'middleware/models/');
        
            $row = $this->model->getMethodIdByMetaDataModel($metaDataId);
            
            if (is_array($params)) {
                
                foreach ($params as $paramsK => $paramsV) {
                    if (is_array($paramsV) && isset($paramsV[0]) && $paramsV[0] == '') {
                        unset($params[$paramsK]);
                    }
                }
            }

            $param = $params;

            foreach ($paramList as $rowVal) {

                $paramRealPath = strtolower($rowVal['META_DATA_CODE']);

                if ($paramRealPath === 'filterstartdate') {
                    $param['filterstartdate'] = $postData['filterstartdate'];
                } elseif ($paramRealPath === 'filterenddate') {
                    $param['filterenddate'] = $postData['filterenddate'];
                } elseif ($paramRealPath === 'tablename' && isset($postData['tablename'])) {
                    $param['tablename'] = $postData['tablename'];
                } else {

                    if (isset($postData[$paramRealPath])) {

                        $paramVal = $postData[$paramRealPath];
                        
                        if (is_array($paramVal)) {
                            
                            $paramCommaVal = Arr::implode_r(',', $paramVal, true);
                            
                            if ($paramCommaVal != '') {
                                $param[$paramRealPath] = $paramVal;
                            }
                            
                        } else {
                            $param[$paramRealPath] = $paramVal;
                        }
                        
                    } elseif (isset($params[$paramRealPath])) {

                        $paramVal = $params[$paramRealPath];
                        
                        if (is_array($paramVal)) {
                            
                            $paramCommaVal = Arr::implode_r(',', $paramVal, true);
                            
                            if ($paramCommaVal != '') {
                                $param[$paramRealPath] = $paramVal;
                            }
                            
                        } else {
                            $param[$paramRealPath] = $paramVal;
                        }
                        
                    } elseif ($rowVal['DEFAULT_VALUE'] != '') {

                        $param[$paramRealPath] = Mdmetadata::setDefaultValue($rowVal['DEFAULT_VALUE']);
                    }
                }
            }
            
            $param['filterdepartmentid'] = null;
            
            if (isset($postData['departmentid'])) {
                
                $param['filterdepartmentid'] = $postData['departmentid'];
                
            } elseif (isset($params['filterdepartmentid'])) {
                
                $param['filterdepartmentid'] = $params['filterdepartmentid'];
            }

            if (isset($postData['isintegration'])) {

                if (array_key_exists('isintegration', $param)) {
                    unset($param['isintegration']);
                }

                $param['isintegration'] = $postData['isintegration'];
            }

            if (isset($postData['ignoredepartmentids'])) {
                $param['ignoredepartmentids'] = $postData['ignoredepartmentids'];
            }

            $result = $this->ws->runSerializeResponse($serviceAddress, $row['META_DATA_CODE'], $param);
        } 
        
        if ($result['status'] == 'success') {
            
            $response = array(
                'status' => 'success',
                'message' => 'Successfully'
            );
            
            if ($checkMapOwn && $statementMetaDataId) {
                
                $this->load->model('mdstatement', 'middleware/models/');
                $childStatement = $this->model->getChildCalculatingStatementModel($statementMetaDataId); 
                
                if ($childStatement && isset($param['tablename'])) {
                    
                    $this->load->model('mdwebservice', 'middleware/models/');
                    
                    $calculatedTableName[strtolower($param['tablename'])] = 1;
                    
                    foreach ($childStatement as $child) {
                        
                        $row = $this->model->getMethodIdByMetaDataModel($child['PROCESS_META_DATA_ID']);
                        $paramChild = $params;
                        
                        foreach ($paramList as $rowVal) {

                            $paramRealPath = strtolower($rowVal['META_DATA_CODE']);

                            if ($paramRealPath === 'filterstartdate') {
                                $paramChild['filterstartdate'] = $postData['filterstartdate'];
                            } elseif ($paramRealPath === 'filterenddate') {
                                $paramChild['filterenddate'] = $postData['filterenddate'];
                            } elseif ($paramRealPath === 'tablename' && $child['DEFAULT_VALUE'] != '') {
                                $paramChild['tablename'] = $child['DEFAULT_VALUE'];
                            } else {
                                if (isset($postData[$paramRealPath])) {
                                    
                                    $paramVal = $postData[$paramRealPath];
                        
                                    if (is_array($paramVal)) {

                                        $paramCommaVal = Arr::implode_r(',', $paramVal, true);

                                        if ($paramCommaVal != '') {
                                            $paramChild[$paramRealPath] = $paramVal;
                                        }

                                    } else {
                                        $paramChild[$paramRealPath] = $paramVal;
                                    }
                                    
                                } elseif (isset($params[$paramRealPath])) {
                                    
                                    $paramVal = $params[$paramRealPath];
                        
                                    if (is_array($paramVal)) {

                                        $paramCommaVal = Arr::implode_r(',', $paramVal, true);

                                        if ($paramCommaVal != '') {
                                            $paramChild[$paramRealPath] = $paramVal;
                                        }

                                    } else {
                                        $paramChild[$paramRealPath] = $paramVal;
                                    }
                                    
                                } elseif ($rowVal['DEFAULT_VALUE'] != '') {
                                    $paramChild[$paramRealPath] = Mdmetadata::setDefaultValue($rowVal['DEFAULT_VALUE']);
                                }
                            }
                        }
                        
                        if (isset($paramChild['tablename']) && !isset($calculatedTableName[strtolower($paramChild['tablename'])])) {
                            
                            $paramChild['filterdepartmentid'] = null;
            
                            if (isset($postData['departmentid'])) {

                                $paramChild['filterdepartmentid'] = $postData['departmentid'];

                            } elseif (isset($params['filterdepartmentid'])) {

                                $paramChild['filterdepartmentid'] = $params['filterdepartmentid'];
                            }
                            
                            if (isset($postData['isintegration'])) {
                                
                                if (array_key_exists('isintegration', $paramChild)) {
                                    unset($paramChild['isintegration']);
                                }
            
                                $paramChild['isintegration'] = $postData['isintegration'];
                            }
                            
                            if (isset($postData['ignoredepartmentids'])) {
                                $paramChild['ignoredepartmentids'] = $postData['ignoredepartmentids'];
                            }
                            
                            $result = $this->ws->runSerializeResponse($serviceAddress, $row['META_DATA_CODE'], $paramChild); 
                            
                            if ($result['status'] !== 'success') {
                                
                                $response = array(
                                    'status'  => 'error',
                                    'message' => $this->ws->getResponseMessage($result)
                                );
                                return $response;
                                
                            } else {
                                $calculatedTableName[strtolower($paramChild['tablename'])] = 1;
                            }
                        }
        
                    }
                }
            }
            
        } else {
            $response = array(
                'status' => 'error',
                'message' => $this->ws->getResponseMessage($result)
            );
        }
        
        return $response;
    }
    
    public function processRun() {
        
        Auth::handleLogin();
        
        $this->load->model('mdwebservice', 'middleware/models/');
        
        $postData = Arr::changeKeyLower(Input::postData());

        $metaDataId = $postData['processmetaid'];
        
        $paramList = $this->model->groupParamsDataModel($metaDataId, null, ' AND PAL.PARENT_ID IS NULL');
        
        if (!$paramList) {
            $response = array(
                'status' => 'error',
                'message' => '<strong>' . $metaDataId . '</strong> -тай бодолт хийх процесс олдсонгүй!'
            );            
            echo json_encode($response); exit;
        }
        
        $statementMetaDataId = isset($postData['statementid']) ? $postData['statementid'] : null;
        
        $result = self::processRunning($metaDataId, $postData, $paramList, $statementMetaDataId);
        
        echo json_encode($result); exit;
    }
    
    public function checkCriteria() {
        
        Auth::handleLogin();
        
        $metadataId = Input::numeric('metaDataId');
        $isprocess = Input::post('isProcess');
        
        $this->view->statementDataList = $this->model->getStatementDataListModel($metadataId, $isprocess);
        
        $response = array(
            'html' => $this->view->renderPrint('printDropdownSettings', 'middleware/views/statement/print/')
        );
        echo json_encode($response); exit;
    }
    
    public function printOption() {
        
        Auth::handleLogin();
        
        if (!isset($this->view)) {
            $this->view = new View();
        }
        
        $print_options = $_POST['print_options'];
        
        $this->view->pageHeaderTitle = 'Tемплейт';
        $this->view->numberOfCopies = $print_options['numberOfCopies'];
        $this->view->isPrintNewPage = $print_options['isPrintNewPage'];
        $this->view->isShowPreview = $print_options['isShowPreview'];
        
        $statementId = Input::post('statementId');
        $row = $this->model->getStatementRowModel($statementId);
        
        if ($row['IS_USE_SELF_DV'] == '1') {
            $_POST['dataViewId'] = $row['DATA_VIEW_ID'];
        }
        
        $this->view->pageProperty = array(
            'pageSize' => $row['PAGE_SIZE'], 
            'pageOrientation' => $row['PAGE_ORIENTATION'], 
            'pagePrint' => true,
            'pagePdf' => true, 
            'pagePdfView' => Config::getFromCache('CONFIG_STATEMENT_PDF_VIEW') ? true : false, 
            'pageExcel' => Config::getFromCache('CONFIG_STATEMENT_XLS_EXP') === '1' ? false : true,
            'pageWord' => Config::getFromCache('CONFIG_STATEMENT_DOC_EXP') === '1' ? false : true,
            'pageSearch' => true,
            'pageArchive' => false, 
            'pageMarginTop' => $row['PAGE_MARGIN_TOP'], 
            'pageMarginLeft' => $row['PAGE_MARGIN_LEFT'],
            'pageMarginRight' => $row['PAGE_MARGIN_RIGHT'],
            'pageMarginBottom' => $row['PAGE_MARGIN_BOTTOM'], 
            'pageWidth' => $row['PAGE_WIDTH'],
            'pageHeight' => $row['PAGE_HEIGHT'], 
            'fontFamily' => $row['FONT_FAMILY'], 
            'fontSize' => $row['FONT_SIZE']
        );
        
        $this->view->style = '';
        $this->view->style .= 'padding-top: '.$this->view->pageProperty['pageMarginTop'].';';
        $this->view->style .= 'padding-left: '.$this->view->pageProperty['pageMarginLeft'].';';
        $this->view->style .= 'padding-right: '.$this->view->pageProperty['pageMarginRight'].';';
        $this->view->style .= 'padding-bottom: '.$this->view->pageProperty['pageMarginBottom'].';';
        
        if (!empty($this->view->pageProperty['fontFamily'])) {
            $this->view->style .= 'font-family: '.$this->view->pageProperty['fontFamily'].';';
        }
        
        if (!empty($this->view->pageProperty['fontSize'])) {
            $this->view->style .= 'font-size: '.$this->view->pageProperty['fontSize'].';';
        }
        
        $this->view->contentHtml = (new Mdstatement())->renderDataModelByFilter(true);
        
        if (Config::getFromCache('isCheckStatementExportPermission')) {
            
            $preview = &getInstance();
            $preview->load->model('mdpreview', 'middleware/models/');
                
            $check = $preview->model->isCheckStatementExportPermissionModel();
            
            if (!$check) {
                $this->view->pageProperty['pagePrint'] = false;
                $this->view->pageProperty['pagePdf'] = false;
                $this->view->pageProperty['pagePdfView'] = false;
                $this->view->pageProperty['pageExcel'] = false;
                $this->view->pageProperty['pageWord'] = false;
            }
        }
        
        $html = $this->view->isShowPreview == '1' ? $this->view->renderPrint('printOption', 'middleware/views/statement/print/') : $this->view->contentHtml;
        
        $response = array(
            'Html' => $html,
            'Title' => 'Хэвлэх',
            'print_btn' => $this->lang->line('print_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function dataModelReportDrillViewer($metaDataId, $row, $contentHtml, $isSearchFormDisabled = true) {
        
        Auth::handleLogin();
        
        if (!isset($this->view)) {
            $this->view = new View();
        }
        
        $this->view->row = $row;
        $this->view->dataViewId = $row['DATA_VIEW_ID'];
        $this->view->metaDataId = $metaDataId;
        $this->view->reportType = $this->view->row['REPORT_TYPE'];
        $this->view->isDrill = true;
        $this->view->isSearchForm = true;
        $this->view->isSearchFormDisabled = $isSearchFormDisabled;
        $this->view->isClearButton = false;
        
        Mdexpression::$searchMainSelector = 'dataview_statement_search_'.$this->view->metaDataId;
        $this->view->dvScripts = Mdexpression::searchGenerateScripts($this->view->dataViewId);
        
        if (self::$isKpiIndicator == false) {
            
            $this->load->model('mdobject', 'middleware/models/');
            $dataViewSearchData = $this->model->dataViewHeaderDataModel($this->view->dataViewId);

            $this->load->model('mdstatement', 'middleware/models/');
            $this->view->dataViewSearchData = $this->model->dataViewHeaderDataResolveModel($dataViewSearchData);
            
        } else {
            $this->load->model('mdstatement', 'middleware/models/');
            $this->view->isSearchForm = false;
        }
        
        $this->view->fillParamData = null;
        $this->view->isUserGroupingButton = false;
        
        if ($this->view->row['COUNT_USER_GROUPING'] != '0') {
            $this->view->isUserGroupingButton = true;
        }        
        
        if (Input::postCheck('param')) {
            $this->view->fillParamData = $_POST['param'];
        }
                
        $pageProperties = [
            'reportName'        => $this->lang->line($this->view->row['REPORT_NAME']), 
            'pageSize'          => $this->view->row['PAGE_SIZE'], 
            'pageOrientation'   => $this->view->row['PAGE_ORIENTATION'], 
            'pagePrint'         => true,
            'pagePdf'           => true, 
            'pagePdfView'       => Config::getFromCache('CONFIG_STATEMENT_PDF_VIEW') ? true : false, 
            'pageExcel'         => Config::getFromCache('CONFIG_STATEMENT_XLS_EXP') === '1' ? false : true,
            'pageWord'          => Config::getFromCache('CONFIG_STATEMENT_DOC_EXP') === '1' ? false : true,
            'pageSearch'        => true,
            'pageArchive'       => false, 
            'pageMarginTop'     => $this->view->row['PAGE_MARGIN_TOP'], 
            'pageMarginLeft'    => $this->view->row['PAGE_MARGIN_LEFT'],
            'pageMarginRight'   => $this->view->row['PAGE_MARGIN_RIGHT'],
            'pageMarginBottom'  => $this->view->row['PAGE_MARGIN_BOTTOM'], 
            'pageWidth'         => $this->view->row['PAGE_WIDTH'],
            'pageHeight'        => $this->view->row['PAGE_HEIGHT'], 
            'fontFamily'        => $this->view->row['FONT_FAMILY'], 
            'fontSize'          => $this->view->row['FONT_SIZE'], 
            'contentHtml'       => $contentHtml, 
            'dataViewId'        => $this->view->row['DATA_VIEW_ID'], 
            'isIgnoreFooter'    => $this->view->row['IS_EXPORT_NO_FOOTER'], 
            'metaDataId'        => $this->view->metaDataId
        ];
        
        if (self::$isPivotView) {
            $pageProperties['pageOrientation'] = 'landscape';
            $pageProperties['pageSize'] = 'pivot';
        }
        
        if (defined('CONFIG_REPORT_SERVER_ADDRESS') && CONFIG_REPORT_SERVER_ADDRESS) {
            
            $layoutId = $this->model->getReportLayoutIdModel($this->view->row['META_DATA_ID']);
            
            if ($layoutId) {
                
                $this->view->reportLayoutId       = $layoutId; 
                $pageProperties['reportLayoutId'] = $layoutId;
                $pageProperties['reportId']       = issetParam($this->view->row['reportId']);
                $pageProperties['expandReportId'] = issetParam($this->view->row['expandReportId']);
                
                $this->view->reportLayoutTemplateList = $this->model->getReportLayoutTemplateModel($this->view->row['META_DATA_ID']);
                
                $this->view->isIframe = true;
            }
        } 
        
        $this->view->searchForm = $this->view->renderPrint('dataview/search', self::$viewPath);
        
        $this->view->reportPreview = (new Mdpreview())->renderToolbar($pageProperties);
        
        return $this->view->renderPrint('dataview/index', self::$viewPath);
    }
    
    public function drilldown() {
        
        Auth::handleLogin();
        
        $title = $contentHtml = '';
        
        $statementId = Input::numeric('statementId');
        $columnName  = Input::post('columnName');
        $rId         = Input::post('rId');
        
        if ($rId == 'iframe') {
            
            $rowData = Arr::changeKeyLower(Input::post('rowData'));

            parse_str(Input::post('filterParams'), $filterParams);
            
            $columnName  = strtolower($columnName);
            $drillParams = Arr::changeKeyLower($filterParams['param']);
            
        } else {
            
            $rId = (int) $rId - 1;
            $uniqId = Input::post('uniqId');

            $cacheTmpDir = Mdcommon::getCacheDirectory();
            $file_path = $cacheTmpDir.'/statement/'.$uniqId.'.txt';

            $fileArray = file_get_contents($file_path);
            eval('$fileArray = '.$fileArray.';'); 

            $rowData = $fileArray['rows'][$rId];
            
            $drillParams = $fileArray['params'];
        }
        
        $this->load->model('mdstatement', 'middleware/models/');
        
        $getLinkMetaRow = $this->model->getDrillDownStatementCriteriaModel($statementId, $columnName, $rowData, $drillParams);
        
        if ($getLinkMetaRow) {
            
            $getTypeId  = $getLinkMetaRow['typeId'];
            $linkMetaId = $getLinkMetaRow['linkMetaId'];
            $postArr    = $getLinkMetaRow['linkParam'];

            if ($getTypeId == Mdmetadata::$statementMetaTypeId) {

                $row = $this->model->getStatementRowModel($linkMetaId);
                
                $title = $this->lang->line($row['REPORT_NAME']);

                unset($_POST); 

                $_POST['dataViewId']  = $row['DATA_VIEW_ID'];
                $_POST['statementId'] = $row['META_DATA_ID'];
                
                foreach ($postArr as $postKey => $postVal) {
                    $_POST['param'][$postKey] = $postVal;
                }
                
                if (!isset($row['REPORT_LAYOUT_ID']) || !defined('CONFIG_REPORT_SERVER_ADDRESS')) {
                    
                    $reportHtml = (new Mdstatement())->renderDataModelByFilter(true);
                    
                } else {
                    
                    $reportHtml = '';
                    
                    $_POST['expandDataViewId'] = $row['GROUP_DATA_VIEW_ID'];
                    $_POST['layoutId']         = $linkMetaId;
                    $_POST['resultReturn']     = 1;

                    $iframeReport = self::iframeReportFilter();
                    
                    if ($iframeReport['status'] != 'success') {
                        $response = ['metaType' => 'error', 'message' => $iframeReport['message']];
                        echo json_encode($response, JSON_UNESCAPED_UNICODE); exit;
                    }
                    
                    $row['reportId'] = $iframeReport['reportId'];
                    
                    if (isset($iframeReport['expandReportId'])) {
                        $row['expandReportId'] = $iframeReport['expandReportId'];
                    }
                }
                
                $contentHtml = (new Mdstatement())->dataModelReportDrillViewer($linkMetaId, $row, $reportHtml);
                $reportHtml = null;

                $response = [
                    'title' => $title,  
                    'html' => $contentHtml,
                    'metaType' => 'statement',
                    'close_btn' => Lang::line('close_btn')
                ];

                echo json_encode($response, JSON_UNESCAPED_UNICODE); 
                $response = [];
                exit;

            } elseif ($getTypeId == Mdmetadata::$businessProcessMetaTypeId) {

                unset($_POST);

                $_POST['metaDataId'] = $linkMetaId;
                $_POST['callerType'] = 'drilldown';

                foreach ($postArr as $postKey => $postVal) {
                    $_POST['recordId'] = $postVal;
                }

                (new Mdwebservice())->callMethodByMeta(); exit;

            } elseif ($getTypeId == Mdmetadata::$bookmarkMetaTypeId) {

                $this->load->model('mdmetadata', 'middleware/models/');
                $webLinkRow = $this->model->getBookmarkData($linkMetaId);

                $webUrl = isset($webLinkRow['BOOKMARK_URL']) ? Str::lower($webLinkRow['BOOKMARK_URL']) : '';

                if ($webUrl == 'mdgl/edit_entry') {
                    
                    if (Input::isEmpty('glbookid_nextprev') == false && Input::isEmpty('glbookRecordType') == false) {
                        $glbookid_nextprev = Input::post('glbookid_nextprev');
                        $glbookRecordType  = Input::post('glbookRecordType');
                    }                    

                    unset($_POST);

                    $_POST['id'] = isset($postArr['id']) ? $postArr['id'] : null;
                    $_POST['dialogMode'] = true;
                    $_POST['drillDownParams'] = $drillParams;
                    $_POST['drillDownParams']['statementId'] = $statementId;
                    
                    if (isset($glbookid_nextprev) && isset($glbookRecordType)) {
                        $_POST['id'] = $glbookid_nextprev;
                        $_POST['glbookRecordType'] = $glbookRecordType;
                    }
                    
                    if (isset($_POST['id']) && $_POST['id'] != '') {
                        
                        $this->load->model('mdgl', 'middleware/models/');
                        
                        $checkPermission = $this->model->checkTransactionPermissionModel($_POST['id']);
                        
                        $gl = Controller::loadController('Mdgl', 'middleware/controllers/');
                        
                        if ($checkPermission) {
                            $gl->edit_entry();
                        } else {
                            $gl->view_entry();
                        }
                        
                    } else {
                        echo json_encode(['metaType' => 'error', 'message' => 'ID тохиргоо дутуу байна!'], JSON_UNESCAPED_UNICODE); exit;
                    }
                }

            } elseif ($getTypeId == Mdmetadata::$metaGroupMetaTypeId) {
                
                if ($postArr) {
                    $_POST['drillDownDefaultCriteria'] = http_build_query($postArr);
                }
                
                $_POST['runSrcMetaId'] = $statementId;
                
                $contentDecode = (new Mdobject())->dataview($linkMetaId, '0', 'array');
                
                $contentDecode['status'] = 'success';
                $contentDecode['metaType'] = 'meta_group';
                $contentDecode['linkMetaDataId'] = $linkMetaId;
                
                echo json_encode($contentDecode, JSON_UNESCAPED_UNICODE); exit;
                
            } elseif ($getTypeId == 'kpi') {
                
                $linkIndicatorId = $getLinkMetaRow['linkIndicatorId'];
                $kpiTypeId = $getLinkMetaRow['kpiTypeId'];
                
                if ($kpiTypeId == 1000 || $kpiTypeId == 1040 || $kpiTypeId == 1044 || $kpiTypeId == 1045 || $kpiTypeId == '16641793815766') { /*List*/
                    
                    if ($postArr) {
                        $_POST['drillDownCriteria'] = http_build_query($postArr);
                    }

                    $_POST['isIgnoreTitle'] = 1;
                    $_POST['isDrilldown'] = 1;
                
                    $contentDecode = (new Mdform())->indicatorList($linkIndicatorId, true);
                    $contentDecode['metaType'] = 'kpi_dataview';
                    
                } elseif ($kpiTypeId == 2010) { /*Statement*/
                    
                    Mdstatement::$isKpiIndicator = true;
                    
                    $row = $this->model->getStatementRowModel($linkIndicatorId);
                    $title = $this->lang->line($row['REPORT_NAME']);

                    unset($_POST); 
                    
                    $_POST['isKpiIndicator'] = 1;
                    $_POST['dataViewId']  = $row['DATA_INDICATOR_ID'];
                    $_POST['statementId'] = $row['MAIN_INDICATOR_ID'];
                    
                    Mdform::$kpiTemplateId = $row['DATA_INDICATOR_ID'];
                    
                    if ($row['KPI_TYPE_ID'] == '1045') {
                        self::$isPivotView = true;
                    } elseif ($row['KPI_TYPE_ID'] == '1044') {
                        Mdform::$isRawDataMart = true;
                    }

                    foreach ($postArr as $postKey => $postVal) {
                        $_POST['filterData'][$postKey][] = $postVal; 
                    }

                    $reportHtml = (new Mdstatement())->renderDataModelByFilter(true);
                    $contentHtml = (new Mdstatement())->dataModelReportDrillViewer($linkIndicatorId, $row, $reportHtml);

                    $response = [
                        'title' => $title,  
                        'html' => $contentHtml,
                        'metaType' => 'statement',
                        'close_btn' => Lang::line('close_btn')
                    ];

                    jsonResponse($response);
                }
                
                $contentDecode['status'] = 'success';
                $contentDecode['linkMetaDataId'] = $linkIndicatorId;
                
                jsonResponse($contentDecode);
            }
            
        } else {
            
            $response = ['metaType' => 'error'];
            echo json_encode($response); exit;
        }
    }
    
    public function drilldownStatementExternal() {
        
        $title = $contentHtml = '';
        
        $inputData = file_get_contents('php://input');
        @file_put_contents(BASEPATH.'log/service_response.log', $inputData);

        $postData = json_decode($inputData, true);

        if (is_null($postData))
            parse_str($inputData, $postData);

        $_POST['statementId'] = $postData['statementId'];
        $_POST['columnName'] = $postData['columnName'];
        $_POST['rId'] = $postData['rId'];
        $_POST['uniqId'] = $postData['uniqId'];
        
        $statementId = Input::post('statementId');
        $columnName = Input::post('columnName');
        $rId = Input::post('rId');
        $rId = (int) $rId - 1;
        $uniqId = Input::post('uniqId');
        
        $cacheTmpDir = Mdcommon::getCacheDirectory();
        $file_path = $cacheTmpDir.'/statement/'.$uniqId.'.txt';
        
        $fileArray = file_get_contents($file_path);
        eval('$fileArray = '.$fileArray.';'); 
        
        $rowData = $fileArray['rows'][$rId];
        $drillParams = $fileArray['params'];
        
        $this->load->model('mdstatement', 'middleware/models/');
		
        loadBarCodeImageData();
        loadPhpQuery();		
        
        $getLinkMetaRow = $this->model->getDrillDownStatementCriteriaModel($statementId, $columnName, $rowData, $drillParams);

        if ($getLinkMetaRow) {
            
            $getTypeId = $getLinkMetaRow['typeId'];
            $linkMetaId = $getLinkMetaRow['linkMetaId'];
            $postArr = $getLinkMetaRow['linkParam'];

            if ($getTypeId == Mdmetadata::$statementMetaTypeId) {

                $row = $this->model->getStatementRowModel($linkMetaId);

                $title = Lang::line($row['REPORT_NAME']);

                unset($_POST); 

                $_POST['dataViewId'] = $row['DATA_VIEW_ID'];
                $_POST['statementId'] = $row['META_DATA_ID'];
                
                foreach ($postArr as $postKey => $postVal) {
                    $_POST['param'][$postKey] = $postVal;
                }
                
                $renderDataViewStatement = self::renderDataViewStatement($linkMetaId, $row['DATA_VIEW_ID'], $_POST['param']);
                
                if ($renderDataViewStatement['status'] == 'success') {
                    
                    $message = '';
                    $status = $renderDataViewStatement['status'];
                    $htmlData = $renderDataViewStatement['htmlData'];
                    
                } else {
                    $status = $renderDataViewStatement['status'];
                    $message = $renderDataViewStatement['message'];
                    $htmlData = '';
                }

                ob_start('ob_html_compress'); 
                    $response = array(
                        'status' => $status, 
                        'message' => $message, 
                        'html' => $htmlData
                    );
                    echo json_encode($response);
                ob_end_flush();
            }
            
        } else {
            $response = array(
                'status' => 'error',
            );
            echo json_encode($response);
        }
        
        exit;
    }
    
    public function setReportExpressionCriteria() {
        
        Auth::handleLogin();
        
        $this->view->statementId = Input::post('statementId');
        $this->view->getStatementRow = $this->model->getStatementRowModel($this->view->statementId);
        
        $this->view->metaDatas = array();
        $this->view->metaDatasGroup = array();
        
        $this->load->model('mdmetadata', 'middleware/models/');
        
        if (!empty($this->view->getStatementRow['DATA_VIEW_ID'])) {
            $this->view->metaDatas = $this->model->getOnlyMetaDataByGroupModel($this->view->getStatementRow['DATA_VIEW_ID']);
        }        

        $response = array(
            'Html' => $this->view->renderPrint('system/link/statement/setReportExpressionCriteria', 'middleware/views/metadata/'),
            'Title' => 'Report Expression',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        
        echo json_encode($response); exit;
    }      
    
    public function runProcessValue($processCode, $params, $response) {
        
        $paramData = array();
        $params = strtolower($params);
        $paramsArr = explode('|', $params);
        
        if (isset(self::$data['statementRealParams'])) {
            $statementRealParams = Arr::changeKeyLower(self::$data['statementRealParams']);
        }
        
        $_EXPRESSION_GLOBAL = Arr::changeKeyLower(self::$data['_EXPRESSION_GLOBAL']);
        
        foreach ($paramsArr as $pdata){
            $fieldPathArr = explode('@', $pdata);
            
            if (isset($statementRealParams) && array_key_exists($fieldPathArr[0], $statementRealParams)) {
                array_push($paramData, array(
                    'inputPath' => $fieldPathArr[1],
                    'value' => $statementRealParams[$fieldPathArr[0]]
                ));                
            } elseif (array_key_exists($fieldPathArr[0], $_EXPRESSION_GLOBAL)) {
                array_push($paramData, array(
                    'inputPath' => $fieldPathArr[1],
                    'value' => $_EXPRESSION_GLOBAL[$fieldPathArr[0]]
                ));
            } else {
                return null;
            }
        }
        
        $postData = array(
            'processCode' => $processCode,
            'responsePath' => $response,
            'paramData' => $paramData
        );
        $response = $this->model->runProcessValueModel($postData);
        
        return $response;
    }    
    
    private function runOneDataView($dataViewCode, $response, $criteria) {
        
        if (!isset(self::$isRunGroupingDv[$dataViewCode])) {
            
            $getRows = $this->model->runOneDataViewModel($dataViewCode, self::$data['statementRealParams']);
            
            self::$isRunGroupingDv[$dataViewCode] = $getRows;
            
        } else {
            
            $getRows = self::$isRunGroupingDv[$dataViewCode];
        }
        
        $joinGroupKeys = 'return ';
        self::$data['getOneDataViewCriteriaString'][$dataViewCode . $criteria . $response] = 'return ';
        $seperator = '$response."_".';
        
        if ($criteria != '') {
            $paramsArr = explode('|', $criteria);
        
            foreach ($paramsArr as $k => $pdata) {

                $fieldPathArr = explode('@', $pdata);

                if ($k > 0) {
                    $seperator = '."_".';
                }
                
                $joinGroupKeys .= $seperator . '$rowdv[\''.$fieldPathArr[1].'\']';
                self::$data['getOneDataViewCriteriaString'][$dataViewCode . $criteria . $response] .= $seperator . 'self::$data[\'_EXPRESSION_GLOBAL\'][\''.$fieldPathArr[0].'\']';
            }

            $joinGroupKeys .= ';';

            self::$data['getOneDataViewCriteriaString'][$dataViewCode . $criteria . $response] .= ';';
            
            $resultArr = array();

            foreach ($getRows as $rowdv) {
                $id = @eval($joinGroupKeys);

                if (isset($resultArr[$id])) {
                    $resultArr[$id] += (isset($rowdv[$response]) && is_numeric($rowdv[$response])) ? $rowdv[$response] : 0;
                } else {
                    $resultArr[$id] = (isset($rowdv[$response]) && is_numeric($rowdv[$response])) ? $rowdv[$response] : 0;
                }                
            }
            
        } else {
            
            foreach ($getRows as $rowdv) {

                if (isset($resultArr[$response])) {
                    $resultArr[$response] += (isset($rowdv[$response]) && is_numeric($rowdv[$response])) ? $rowdv[$response] : 0;
                } else {
                    $resultArr[$response] = (isset($rowdv[$response]) && is_numeric($rowdv[$response])) ? $rowdv[$response] : 0;
                }                
            }
        }
        
        return $resultArr;
    }
    
    public function getOneDataView($dataViewCode, $criteria, $response) {
        
        $response = strtolower($response);
        $criteria = strtolower($criteria);
        
        if ($criteria != '') {
                    
            $ckey = @eval(self::$data['getOneDataViewCriteriaString'][$dataViewCode . $criteria . $response]);
            
            if (!isset(self::$data['getOneDataViewRows'][$ckey])) {

                if (!isset(self::$isRunGroupingRow[$dataViewCode.$criteria.$response])) {

                    self::$isRunGroupingRow[$dataViewCode.$criteria.$response] = array('true');

                    $getOneDataViewRows = $this->runOneDataView($dataViewCode, $response, $criteria);  
                    
                    if (isset(self::$data['getOneDataViewRows'])) {
                        self::$data['getOneDataViewRows'] = array_merge(self::$data['getOneDataViewRows'], $getOneDataViewRows);
                    } else {
                        self::$data['getOneDataViewRows'] = $getOneDataViewRows;
                    }
                    
                    $ckey = @eval(self::$data['getOneDataViewCriteriaString'][$dataViewCode . $criteria . $response]);

                    return isset(self::$data['getOneDataViewRows'][$ckey]) ? self::$data['getOneDataViewRows'][$ckey] : 0;

                } else {
                    return 0;
                }

            } else {
                return self::$data['getOneDataViewRows'][$ckey];
            }
            
        } else {
            
            if (!isset(self::$isRunGroupingRow[$dataViewCode.$criteria.$response])) {

                self::$isRunGroupingRow[$dataViewCode.$criteria.$response] = array('true');

                $getOneDataViewRows = $this->runOneDataView($dataViewCode, $response, $criteria);  

                if (isset(self::$data['getOneDataViewRows'])) {
                    self::$data['getOneDataViewRows'] = array_merge(self::$data['getOneDataViewRows'], $getOneDataViewRows);
                } else {
                    self::$data['getOneDataViewRows'] = $getOneDataViewRows;
                }

                return isset(self::$data['getOneDataViewRows'][$response]) ? self::$data['getOneDataViewRows'][$response] : 0;

            } else {
                return 0;
            }
        }
    }
    
    public function fromDvToDrilldown() {
        
        Auth::handleLogin();
        
        $statementId = Input::post('statementId');
        $row = $this->model->getStatementRowModel($statementId);
        
        if ($row) {
            
            $statementId = $row['META_DATA_ID'];
            parse_str($_POST['params'], $postArr);

            unset($_POST); 

            $_POST['dataViewId'] = $row['DATA_VIEW_ID'];
            $_POST['statementId'] = $statementId;

            foreach ($postArr as $postKey => $postVal) {
                $_POST['param'][$postKey] = $postVal;
                $_POST['dvMap'][strtolower($postKey)] = 1;
            }

            $reportHtml = (new Mdstatement())->renderDataModelByFilter(true);

            $response = array(
                'status' => 'success', 
                'title' => $this->lang->line($row['REPORT_NAME']),  
                'html' => (new Mdstatement())->dataModelReportDrillViewer($statementId, $row, $reportHtml, false),
                'close_btn' => $this->lang->line('close_btn')
            );
            
        } else {
            $response = array(
                'status' => 'warning',
                'message' => 'Тайлан олдсонгүй. '.($statementId ? $statementId.' statementId шалгана уу.' : 'statementId хоосон.')
            );
        }
        
        echo json_encode($response); exit;
    }
    
    public function groupingUserOption() {
        
        Auth::handleLogin();
        
        $this->view->statementId = Input::post('statementId');
        $this->view->groupingFields = $this->model->getGroupingUserOptionModel($this->view->statementId);
        
        $response = array(
            'html' => $this->view->renderPrint('user/grouping', self::$viewPath),
            'title' => 'Бүлэглэх тохиргоо', 
            'save_btn' => $this->lang->line('save_btn'), 
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function groupingUserOptionSave() {
        Auth::handleLogin();
        $response = $this->model->groupingUserOptionSaveModel();
        echo json_encode($response); exit;
    }
    
    public function themeRenderData($processMetaDataId, $inputMetaDataId, $fillData, $paramList, $bpTab) {
        Mdwebservice::themeRenderData($processMetaDataId, $inputMetaDataId, $fillData, $paramList, $bpTab);
    }
    
    public function urltopdf() {
        includeLib('PDF/Pdf');
        
        $options = array(
            'title'            => 'Veritech ERP',
            'orientation'      => 'Landscape',
            'page-size'        => 'A4',
            'encoding'         => 'UTF-8',
            'margin-top'       => 10,
            'margin-left'      => 10,
            'margin-right'     => 10,
            'margin-bottom'    => 10,
            'images'           => true,
            'enable-javascript'=> true, 
            'javascript-delay' => 3000, 
            'viewport-size'    => '1600x900', 
            'footer-line'      => false
        );
        
        $pdf = Pdf::webUrlToPdf($options);
        
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="file.pdf"');
        
        echo $pdf->getOutput(URL.'login');
    }
    
    public function iframeReportDesigner() {
        
        $statementId = Input::post('statementId');
        
        if (Input::postCheck('dvId')) {
            
            $dvId = Input::post('dvId');
            $getReport = $this->model->getReportIdModel($dvId);
            
        } else {
            
            $this->load->model('mdmetadata', 'middleware/models/');
            $stRow = $this->model->getStatementLinkModel($statementId);
            $this->load->model('mdstatement', 'middleware/models/');
            
            $dvId = $stRow['DATA_VIEW_ID'];
            $getReport = $this->model->getReportIdModel($dvId);
        }
        
        if ($getReport['status'] == 'success') {
            
            $this->view->uniqId = getUID();
            $this->view->layoutId = $statementId;
            $this->view->reportId = $getReport['reportId'];
            $this->view->windowHeight = Input::post('windowHeight') - 87;
            
            if (Input::isEmpty('expandDvId') == false) {
                
                $expandReport = $this->model->getReportIdModel(Input::post('expandDvId'));
                
                if ($expandReport['status'] != 'success') {
                    
                    $response = array(
                        'status' => 'error', 
                        'message' => $expandReport['message']
                    );
                    
                    echo json_encode($response); exit;
                }
                
                $expandReportId = $expandReport['reportId'];
            }
            
            $this->view->iframeUrl = CONFIG_REPORT_SERVER_ADDRESS . 'Designer.aspx?reportid='.$this->view->reportId.'&layoutId='.$this->view->layoutId;
            
            if ($sdbid = Session::get(SESSION_PREFIX . 'sdbid')) {
                $this->view->iframeUrl .= '&dbId=' . $sdbid;
            }
            
            if (isset($expandReportId)) {
                $this->view->iframeUrl .= '&subReportIds=' . $expandReportId;
            }
            
            $response = array(
                'status' => 'success', 
                'html' => $this->view->renderPrint('iframeDesigner', 'middleware/views/metadata/system/link/statement/'),
                'title' => 'Report designer', 
                'close_btn' => $this->lang->line('close_btn')
            );
            
        } else {
            $response = array(
                'status' => 'error', 
                'message' => $getReport['message']
            );
        }
        
        echo json_encode($response); exit;
    }
    
    public function iframeReportFilter() {
        
        Auth::handleLogin();
            
        $postData = Input::postData();

        if (!isset($postData['dataViewId']) && !isset($postData['statementId'])) {
            echo 'Oroltiin parametr todorhoi bus bna'; exit;
        }
        
        if (isset($postData['param'])) { 
            
            $params = $postData['param'];
            
            unset($postData['param']);
            
            self::$filterParamsLower = array_change_key_case($postData, CASE_LOWER);
            
            if ($filterLanguageCode = issetParam($params['filterLanguageCode'])) {
                Lang::$memoryLangCode = $filterLanguageCode;
                Lang::load('main', false, $filterLanguageCode);
            }
            
        } else {
            $params = array();
        }
        
        $statementId      = Input::param($postData['statementId']);
        $dataViewId       = Input::param($postData['dataViewId']);
        $expandDataViewId = Input::param($postData['expandDataViewId']);
        $layoutId         = Input::param($postData['layoutId']);
        
        if ($statementId == $layoutId && $expandDataViewId) {
            
            $_POST['subDvId'] = $expandDataViewId;
            
        } elseif (Input::postCheck('comboLayoutId') && $expandDataViewId = $this->model->getExpandDataViewIdModel($layoutId)) {
            
            $_POST['subDvId'] = $expandDataViewId;
        }
        
        $_POST['showPivot'] = 1;
        
        $result = $this->model->getDataViewDataModel($dataViewId, $params);
        
        if ($result['status'] == 'success') {
            
            $this->model->setReportParamsModel($result['reportId'], $dataViewId, $params);
            
            if (Lang::$memoryLangCode) {
                $result['langCode'] = Lang::$memoryLangCode;
            }
        }
        
        if (Input::postCheck('resultReturn')) {
            return $result;
        } else {
            echo json_encode($result); exit;
        }
    }
    
    public function iframeReportTemplateCopy() {
        
        Auth::handleLogin();
        
        $this->view->layoutId = Input::post('statementId');
        
        $response = array(
            'status' => 'success', 
            'html' => $this->view->renderPrint('iframeTemplateCopy', 'middleware/views/metadata/system/link/statement/'),
            'title' => 'Report template copy', 
            'copy_btn' => $this->lang->line('copy_btn'), 
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function iframeReportTemplateCopySave() {
        Auth::handleLogin();
        $result = $this->model->iframeReportTemplateCopySaveModel();
        echo json_encode($result); exit;
    }
    
    public function popupSearch() {
        
        Auth::handleLogin();
        
        $this->view->dataViewId = Input::numeric('metaDataId');
        
        $this->load->model('mdobject', 'middleware/models/');
        
        $dataViewSearchData = $this->model->dataViewHeaderDataModel($this->view->dataViewId);

        $this->load->model('mdstatement', 'middleware/models/');
        
        $this->view->fillParamData = null;
        $this->view->dataViewSearchData = $this->model->dataViewHeaderDataResolveModel($dataViewSearchData);
            
        $this->view->render('dataview/popupSearch', self::$viewPath);
    }
    
}